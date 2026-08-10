<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Payment;
use App\Models\FinanceAssessor;

class FinanceAssessorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $payment = Payment::all();

        foreach ($payment as $k => $v) {
            FinanceAssessor::create([
                'user_id' => $v->assessor_one_id,
                'dosen_id' => $v->user_id,
                'dosen_status' => $v->user->assessor_fee,
                'amount' => $v->amount_one,
                'assessor_of' => 1,
                'status' => $v->status == 1 ? 1 : 0,
                'created_at' => $v->created_at,
                'updated_at' => $v->updated_at
            ]);
            FinanceAssessor::create([
                'user_id' => $v->assessor_two_id,
                'dosen_id' => $v->user_id,
                'dosen_status' => $v->user->assessor_fee,
                'amount' => $v->amount_two,
                'assessor_of' => 2,
                'status' => $v->status == 1 ? 1 : 0,
                'created_at' => $v->created_at,
                'updated_at' => $v->updated_at
            ]);
            echo "Data created successfully... " . PHP_EOL;
        }
    }
}
