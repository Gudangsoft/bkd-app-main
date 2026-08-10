<?php

namespace App\Http\Livewire;

use App\Exports\PaymentExport;
use Illuminate\Support\Facades\Artisan;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Payment;
use App\Models\Rekening;
use App\Models\FinanceAssessor;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class PaymentTable extends DataTableComponent
{
    protected $model = Payment::class;

    public $selected_id, $status;
    public $uid, $name, $assessor = [], $assessor_one, $assessor_two, $image, $amount, $description;
    public $rekening_id, $rekenings = [];

    public function configure(): void
    {
        // Only this table has been migrated to the Tailwind admin layout so
        // far; every other DataTableComponent stays on the package's default
        // (config('livewire-tables.theme') = bootstrap-5) since those pages
        // still use the old Bootstrap layout and don't load the compiled
        // Tailwind CSS at all.
        $this->setTheme('tailwind');

        $this->setPrimaryKey('id');
        $this->setDefaultSort('created_at', 'desc');
        $this->setSearchStatus(true);
        $this->setSearchEnabled();
        $this->setSearchLive();
    }

    // export data
    public function bulkActions(): array
    {
        return [
            'export' => 'Export',
            'deleteSelectedConfirm' => 'Delete',
        ];
    }

    public function export()
    {
        $payments = $this->getSelected();

        $this->clearSelected();
        // dd($payments);
        return Excel::download(new PaymentExport($payments), 'data-pembayaran.xlsx');
    }

    public function deleteSelectedConfirm()
    {
        if (auth()->user()->getRoleNames()[0] == "asesor") {
            $this->dispatch('closeDeleteSelectedConfirmModal');
        } else {
            $this->dispatch('deleteSelectedConfirmModal');
        }
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->isHidden(),
            Column::make("Name", "user.name"),
            Column::make("Status Dosen", "user.assessor_fee")
                ->format(fn($value) => $value ==  1 ? 'Serdos' : 'Non Serdos'),
            // Column::make("Nama", "user_id")->searchable()
            //     ->format(function ($value) {
            //         $user = User::find($value);
            //         return is_null($user) ? 'User Deleted' : $user->name . '<span class=\'badge bg-info\'>' . $user->getAssessorFee->name . '</span>';
            //     })->html(),
            Column::make("Asesor 1", "assessor_one_id")
                ->format(function ($value) {
                    $user = User::find($value);
                    if (is_null($user)) {
                        return 'User Deleted';
                    }
                    return e($user->name) . ' <span class="ml-1 inline-flex items-center rounded-full bg-accent-50 px-2 py-0.5 text-[11px] font-medium text-accent-700">' . e($user->status) . '</span>';
                })
                ->html(),
            Column::make("Asesor 2", "assessor_two_id")
                ->format(function ($value) {
                    $user = User::find($value);
                    if (is_null($user)) {
                        return 'User Deleted';
                    }
                    return e($user->name) . ' <span class="ml-1 inline-flex items-center rounded-full bg-accent-50 px-2 py-0.5 text-[11px] font-medium text-accent-700">' . e($user->status) . '</span>';
                })->html(),
            Column::make("Nominal (RP)", "amount")
                ->format(function ($value) {
                    return 'Rp ' . number_format($value);
                })
                ->html()
                ->footer(function ($rows) {

                    return 'Subtotal: Rp ' . number_format($rows->sum('amount'));
                }),
            Column::make("Tanggal Bayar", "created_at")
                ->format(function ($value) {
                    return Carbon::parse($value)->format('d-m-Y H:i:s');
                })
                ->sortable(),
            Column::make("Bukti Bayar", "id")
                ->format(function ($value) {
                    return '<a href="#" wire:click="proofOfPayment(\'' . $value . '\')" class="inline-flex text-gray-400 hover:text-accent-600">'
                        . '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">'
                        . '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />'
                        . '</svg></a>';
                })
                ->html(),
            Column::make("Status Asesor 1", "status_accessor_one")
                ->view('admin.payments.view.status-one')
                ->hideIf(auth()->user()->getRoleNames()[0] == "asesor"),
            Column::make("Status Asesor 2", "status_accessor_two")
                ->view('admin.payments.view.status-two')
                ->hideIf(auth()->user()->getRoleNames()[0] == "asesor"),
            // ->view('admin.payments.view.status-two')->hideIf(auth()->user()->getRoleNames()[0] != 'admin'),
            Column::make("Status Pembayaran", "status")
                ->view('admin.payments.view.status'),
            Column::make('Actions', 'id')
                ->view('admin.payments.view.action'),
        ];
    }

    public function filters(): array
    {
        $assessor = User::role('asesor')->get();
        $assessor_id = $assessor->pluck('id');
        $assessor_name = $assessor->pluck('name');
        // dd($assessor_name);
        foreach ($assessor as $k => $v) {
            $ass[$v->id] = $v->name;
        }
        // dd($ass);

        $campusOptions = array_merge(
            ['' => '--- PILIH ---'],
            User::whereNotNull('campus_origin')
                ->where('campus_origin', '!=', '')
                ->distinct()
                ->orderBy('campus_origin')
                ->pluck('campus_origin', 'campus_origin')
                ->toArray()
        );

        return [
            SelectFilter::make('Asesor 1', 'assessor_one_id')
                ->setFilterPillTitle('Verified')
                ->options($ass)
                ->filter(function (Builder $builder, string $value) {
                    $builder->where('assessor_one_id', $value);
                    // $builder->where('assessor_one_id', $value)->orWhere('assessor_two_id', $value);
                }),
            SelectFilter::make('Asesor 2', 'assessor_two_id')
                ->setFilterPillTitle('Verified')
                ->options($ass)
                ->filter(function (Builder $builder, string $value) {
                    $builder->where('assessor_two_id', $value);
                    // $builder->where('assessor_one_id', $value)->orWhere('assessor_two_id', $value);
                }),
            SelectFilter::make('Status', 'status')
                ->setFilterPillTitle('Verified')
                ->options([
                    '' => '--- PILIH ---',
                    '1' => 'LUNAS',
                    '2' => 'PENDING',
                    '0' => 'DITOLAK',
                ])
                ->filter(function (Builder $builder, int $value) {
                    // dd($value);
                    $builder->where('payments.status', $value);
                    // $builder->where('assessor_one_id', $value)->orWhere('assessor_two_id', $value);
                }),
            SelectFilter::make('Kampus Asal', 'campus_origin')
                ->setFilterPillTitle('Kampus')
                ->options($campusOptions)
                ->filter(function (Builder $builder, string $value) {
                    $builder->whereHas('user', function ($q) use ($value) {
                        $q->where('campus_origin', $value);
                    });
                }),
        ];
    }

    public function builder(): Builder
    {
        $sub = User::select('campus_origin')
            ->whereColumn('users.id', 'payments.user_id')
            ->limit(1);

        if (isset(auth()->user()->id) && auth()->user()->getRoleNames()[0] == "dosen" || auth()->user()->getRoleNames()[0] == "guest") {
            return Payment::query()
                ->addSelect(['payments.*', 'user_campus_origin' => $sub])
                ->where('user_id', $this->uid);
        } elseif (auth()->user()->getRoleNames()[0] == "asesor") {
            return Payment::query()
                ->addSelect(['payments.*', 'user_campus_origin' => $sub])
                ->where('assessor_one_id', auth()->user()->id)->orWhere('assessor_two_id', auth()->user()->id);
        } else {
            return Payment::query()
                ->addSelect(['payments.*', 'user_campus_origin' => $sub]);
        }
    }
    public function updatePaymentStatus($id, $status)
    {
        $payment = Payment::findOrFail($id);
        $payment->update(['status' => $status]);

        if ($payment->status == 1) {
            $finance_assessor_one = FinanceAssessor::where('dosen_id', $payment->user_id)->where('user_id', $payment->assessor_one_id)->update(['status' => 1]);
            $finance_assessor_two = FinanceAssessor::where('dosen_id', $payment->user_id)->where('user_id', $payment->assessor_two_id)->update(['status' => 1]);
        } else {
            $finance_assessor_one = FinanceAssessor::where('dosen_id', $payment->user_id)->where('user_id', $payment->assessor_one_id)->update(['status' => 0]);
            $finance_assessor_two = FinanceAssessor::where('dosen_id', $payment->user_id)->where('user_id', $payment->assessor_two_id)->update(['status' => 0]);
        }
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

        $this->uid = null;
        $this->rekening_id = null;
        $this->rekenings = [];
        $this->name = null;
        $this->assessor_one = null;
        $this->assessor_two = null;
        $this->assessor = [];
        $this->amount = null;
        $this->image = null;
        $this->description = null;

        $payment = Payment::find($id);
        $this->uid = $payment->user_id;
        $this->rekening_id = $payment->rekening_id;
        $this->rekenings = Rekening::where('status', true)->get();
        $this->name = $payment->user->name;
        $this->assessor_one = $payment->assessor_one_id;
        $this->assessor_two = $payment->assessor_two_id;
        $this->assessor = User::role('asesor')->where('is_active', true)->get();
        $this->image = $payment->image;
        $this->amount = $payment->amount;
        $this->description = $payment->description;
        // dd($this->uid);
        $this->dispatch('editOpenModal');
    }

    public function proofOfPayment($id)
    {
        $this->image = null;
        $this->description = null;

        $payment = Payment::find($id);
        $this->image = $payment->image;
        $this->description = $payment->description;

        $this->dispatch('proofOfPaymentModal');

        // dd($id);
    }

    public function deleteConfirm($id)
    {
        $this->selected_id = $id;
        $this->dispatch('deleteConfirmModal');
    }

    public function delete()
    {
        $data = Payment::findOrFail($this->selected_id);
        if ($data) {
            FinanceAssessor::where('user_id', $data->assessor_one_id)->where('dosen_id', $data->user_id)->delete();
            FinanceAssessor::where('user_id', $data->assessor_two_id)->where('dosen_id', $data->user_id)->delete();
            $data->forceDelete();
        }
        $this->dispatch('closeDeleteConfirmModal');
    }

    public function deleteSelected()
    {
        Payment::whereIn('id', $this->getSelected())->delete();
        $this->dispatch('closeDeleteSelectedConfirmModal');
    }

    public function customView(): string
    {
        return 'admin.payments.modal';
    }
}
