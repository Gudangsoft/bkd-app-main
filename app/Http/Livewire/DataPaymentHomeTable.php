<?php

namespace App\Http\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Payment;
use App\Models\Rekening;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class DataPaymentHomeTable extends DataTableComponent
{
    protected $model = Payment::class;

    public $selected_id, $status;
    public $uid, $name, $assessor = [], $assessor_one, $assessor_two, $image, $amount, $description;
    public $rekening_id, $rekenings = [];

    public function configure(): void
    {
        $this->setTheme('tailwind');
        $this->setPrimaryKey('id');
        $this->setDefaultSort('id', 'desc');
    }

    public function columns(): array
    {
        return [
            Column::make("ID", "id"),
            Column::make("Nama", "user.name")->searchable(),
            Column::make("Progdi", "user.progdi")->searchable(),
            Column::make("Asesor 1", "assessor_one_id")
                ->format(function ($value) {
                    $user = User::find($value);
                    return is_null($user) ? 'User Deleted' : $user->name;
                })
                ->html(),
            Column::make("Asesor 2", "assessor_two_id")
                ->format(function ($value) {
                    $user = User::find($value);
                    return is_null($user) ? 'User Deleted' : $user->name;
                })->html(),

            Column::make("Status Asesor 1", "status_accessor_one")
                ->view('admin.payments.view.status-one'),
            Column::make("Status Asesor 2", "status_accessor_two")
                ->view('admin.payments.view.status-two'),
        ];
    }

    public function builder(): Builder
    {
        $sub = User::select('campus_origin')
            ->whereColumn('users.id', 'payments.user_id')
            ->limit(1);

        return Payment::query()
            ->addSelect(['payments.*', 'user_campus_origin' => $sub]);
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
