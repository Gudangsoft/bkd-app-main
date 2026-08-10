<?php

namespace App\Http\Livewire;

use App\Models\AssignmentLetter;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Payment;
use App\Models\Rekening;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class DataDosenAssessorTable extends DataTableComponent
{
    protected $model = Payment::class;

    public $selected_id, $status;
    public $uid, $name, $assessor = [], $assessor_one, $assessor_two, $image, $amount, $description;
    public $rekening_id, $rekenings = [];

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setDefaultSort('id', 'desc');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->isHidden(),
            Column::make("Nama", "user_id")->searchable()
                ->format(function ($value) {
                    $user = User::find($value);
                    return is_null($user) ? 'User Deleted' : $user->name . '<span class=\'badge bg-info\'>' . $user->getAssessorFee->name . '</span>';
                })->html(),
            Column::make("Asesor 1", "assessor_one_id")
                ->format(function ($value) {

                    $user = User::find($value);

                    if ($value == auth()->user()->id) {
                        $assessor_one = '<b class=\'text-primary\'>' . $user->name . '</b>';
                    } else {
                        $assessor_one = is_null($user) ? null : $user->name;
                    }
                    // dd($assessor_one);
                    return is_null($assessor_one) ? 'User Deleted' : $assessor_one;
                })
                ->html(),
            Column::make("Asesor 2", "assessor_two_id")
                ->format(function ($value) {

                    $user = User::find($value);

                    if ($value == auth()->user()->id) {
                        $assessor_two = '<b class=\'text-primary\'>' . $user->name . '</b>';
                    } else {
                        $assessor_two = is_null($user) ? null : $user->name;
                    }
                    // dd($assessor_two);
                    return is_null($assessor_two) ? 'User Deleted' : $assessor_two;
                })
                ->html(),
            // Column::make("Nominal (RP)", "amount")
            //     ->format(function ($value) {
            //         return 'Rp ' . number_format($value);
            //     })
            //     ->html()
            //     ->footer(function ($rows) {

            //         return 'Subtotal: Rp ' . number_format($rows->sum('amount'));
            //     }),
            // Column::make("Tanggal Bayar", "created_at")
            //     ->format(function ($value) {
            //         return Carbon::parse($value)->format('d-m-Y H:i:s');
            //     })
            //     ->sortable(),
            // Column::make("Bukti Bayar", "proof_of_payment")
            //     ->format(function ($value) {
            //         return "<a wire:ignore href=\"/storage/images/proof_of_payment/" . $value . "\"><i data-acorn-icon=\"file-image\" class=\"icon\" data-acorn-size=\"25\"></i></a>";
            //     })
            //     ->html(),
            Column::make("Penilaian Asesor 1", "status_accessor_one")
                ->view('admin.data.assessor.data-dosen.view.status-one'),
            Column::make("Penilaian Asesor 2", "status_accessor_two")
                ->view('admin.data.assessor.data-dosen.view.status-two'),
            // ->view('admin.data.assessor.data-dosen.view.status-two')->hideIf(auth()->user()->getRoleNames()[0] != 'admin'),
            Column::make("Status Pembayaran", "status")
                ->view('admin.data.assessor.data-dosen.view.status'),
            Column::make('', 'id')
                ->view('admin.data.assessor.data-dosen.view.action'),
        ];
    }

    public function builder(): Builder
    {
        $this->uid = auth()->user()->id;

        return Payment::query()
            ->where('assessor_one_id', $this->uid)->orWhere('assessor_two_id', $this->uid);
    }
    public function updatePaymentStatus($id, $status)
    {
        Payment::findOrFail($id)->update(['status' => $status]);
    }

    public function updateStatusAccessorOne($id, $status)
    {
        Payment::findOrFail($id)->update(['status_accessor_one' => $status]);
    }

    public function updateStatusAccessorTwo($id, $status)
    {
        Payment::findOrFail($id)->update(['status_accessor_two' => $status]);
    }

    public function edit($id)
    {
        $this->selected_id = $id;

        $payment = Payment::find($id);
        $this->uid              = $payment->user_id;
        $this->rekening_id      = $payment->rekening_id;
        $this->rekenings        = Rekening::where('status', true)->get();
        $this->name             = $payment->user->name;
        $this->assessor_one     = $payment->assessor_one_id;
        $this->assessor_two     = $payment->assessor_two_id;
        $this->assessor         = User::role('asesor')->where('is_active', true)->get();
        $this->image            = $payment->image;
        $this->amount           = $payment->amount;
        $this->description      = $payment->description;
        // dd($this->uid);
        $this->dispatch('editOpenModal');
    }

    public function deleteConfirm($id)
    {
        $this->selected_id = $id;
        $this->dispatch('deleteConfirmModal');
    }

    public function delete()
    {
        $data = Payment::findOrFail($this->selected_id)->delete();
        $this->dispatch('closeDeleteConfirmModal');
    }

    public function customView(): string
    {
        return 'admin.payments.modal';
    }
}
