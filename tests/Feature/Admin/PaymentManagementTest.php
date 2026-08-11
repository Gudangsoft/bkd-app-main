<?php

namespace Tests\Feature\Admin;

use App\Models\AssessorFee;
use App\Models\FinanceAssessor;
use App\Models\Payment;
use App\Models\Rekening;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    private function admin(): User
    {
        $admin = User::factory()->create(['is_active' => true]);
        $admin->assignRole('admin');

        return $admin;
    }

    private function asesor(string $status): User
    {
        $user = User::factory()->create(['is_active' => true, 'status' => $status]);
        $user->assignRole('asesor');

        return $user;
    }

    private function feeTier(float $internal = 100000, float $external = 150000, float $externalDif = 50000): AssessorFee
    {
        return AssessorFee::create([
            'name' => 'Tier',
            'internal' => $internal,
            'external' => $external,
            'external_dif' => $externalDif,
            'status' => 1,
        ]);
    }

    private function rekening(float $saldo = 0): Rekening
    {
        return Rekening::create([
            'user_id' => null,
            'nama_nasabah' => 'Eko Siswanto',
            'nomor_rekening' => '0262014814',
            'jenis_rekening' => 'BCA',
            'saldo' => $saldo,
            'status' => 1,
        ]);
    }

    public function test_admin_can_create_a_payment_and_it_credits_rekening_and_creates_finance_records(): void
    {
        $fee = $this->feeTier();
        $dosen = User::factory()->create(['is_active' => true, 'assessor_fee' => $fee->id]);
        $dosen->assignRole('dosen');
        $assessorOne = $this->asesor('internal');
        $assessorTwo = $this->asesor('external');
        $rekening = $this->rekening(saldo: 500000);

        $response = $this->actingAs($this->admin())->post(route('payments.store'), [
            'user_list' => [$dosen->id],
            'assessor_one' => [$assessorOne->id],
            'assessor_two' => [$assessorTwo->id],
            'bea_asesor_one' => 100000,
            'bea_asesor_two' => 150000,
            'amount' => 250000,
            'rekening_id' => $rekening->id,
            'description' => 'Test payment',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('payments', [
            'user_id' => $dosen->id,
            'assessor_one_id' => $assessorOne->id,
            'assessor_two_id' => $assessorTwo->id,
            'amount' => 250000,
        ]);

        $this->assertSame(750000.0, (float) $rekening->fresh()->saldo);

        $this->assertDatabaseHas('finance_assessors', [
            'dosen_id' => $dosen->id,
            'user_id' => $assessorOne->id,
            'assessor_of' => 1,
            'amount' => 100000,
        ]);
        $this->assertDatabaseHas('finance_assessors', [
            'dosen_id' => $dosen->id,
            'user_id' => $assessorTwo->id,
            'assessor_of' => 2,
            'amount' => 150000,
        ]);
    }

    /**
     * Regression test for tonight's "let admin edit payment assessors"
     * feature: swapping Asesor 1 should recalculate their fee from the
     * AssessorFee tier, move the FinanceAssessor payout record to the new
     * assessor, reset that slot's review status, and adjust the rekening
     * balance by the amount delta - not just rename a field.
     */
    public function test_changing_assessor_one_recalculates_fee_moves_finance_record_and_reconciles_saldo(): void
    {
        $fee = $this->feeTier(internal: 100000, external: 150000);
        $dosen = User::factory()->create(['is_active' => true, 'assessor_fee' => $fee->id]);
        $dosen->assignRole('dosen');
        $oldAssessorOne = $this->asesor('internal');
        $newAssessorOne = $this->asesor('external');
        $assessorTwo = $this->asesor('internal');
        $rekening = $this->rekening(saldo: 1000000);

        $payment = Payment::create([
            'user_id' => $dosen->id,
            'rekening_id' => $rekening->id,
            'assessor_one_id' => $oldAssessorOne->id,
            'assessor_two_id' => $assessorTwo->id,
            'amount_one' => 100000,
            'amount_two' => 100000,
            'amount' => 200000,
            'status_accessor_one' => 3,
            'status_accessor_two' => 1,
            'status' => 0,
        ]);

        FinanceAssessor::create([
            'dosen_id' => $dosen->id,
            'dosen_status' => $fee->id,
            'user_id' => $oldAssessorOne->id,
            'amount' => 100000,
            'assessor_of' => 1,
            'status' => 0,
        ]);

        $response = $this->actingAs($this->admin())->put(route('payments.update', $payment->id), [
            'assessor_one_id' => $newAssessorOne->id,
            'assessor_two_id' => $assessorTwo->id,
            'rekening_id' => $rekening->id,
            'description' => 'Ganti asesor 1',
        ]);

        $response->assertRedirect();

        $payment->refresh();
        $this->assertSame($newAssessorOne->id, $payment->assessor_one_id);
        // New assessor is 'external' -> 150000, not the old 100000.
        $this->assertEquals(150000, $payment->amount_one);
        $this->assertEquals(100000, $payment->amount_two);
        $this->assertEquals(250000, $payment->amount);
        // A newly-assigned reviewer hasn't reviewed yet.
        $this->assertEquals(1, $payment->status_accessor_one);
        // Untouched slot keeps its prior status.
        $this->assertEquals(1, $payment->status_accessor_two);

        $this->assertDatabaseMissing('finance_assessors', [
            'dosen_id' => $dosen->id,
            'user_id' => $oldAssessorOne->id,
            'assessor_of' => 1,
        ]);
        $this->assertDatabaseHas('finance_assessors', [
            'dosen_id' => $dosen->id,
            'user_id' => $newAssessorOne->id,
            'assessor_of' => 1,
            'amount' => 150000,
        ]);

        // Total went from 200000 to 250000: rekening should only move by
        // the +50000 delta, not be double-credited with the full new amount.
        $this->assertSame(1050000.0, (float) $rekening->fresh()->saldo);
    }

    public function test_non_admin_cannot_manage_finances(): void
    {
        $dosen = User::factory()->create(['is_active' => true]);
        $dosen->assignRole('dosen');

        $response = $this->actingAs($dosen)->get(route('finances.index'));

        $response->assertForbidden();
    }
}
