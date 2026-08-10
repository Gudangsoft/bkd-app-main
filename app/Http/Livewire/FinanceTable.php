<?php

namespace App\Http\Livewire;

use App\Exports\PaymentExport;
use App\Models\FinanceAssessor;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Payment;
use App\Models\Rekening;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class FinanceTable extends DataTableComponent
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
        $this->setBulkActionsStatus(true);
    }

    public function bulkActions(): array
    {
        return [
            'export' => 'Export',
        ];
    }

    public function export()
    {
        $payments = $this->getSelected();

        $this->clearSelected();
        // dd($payments);
        return Excel::download(new PaymentExport($payments), 'data-pembayaran.xlsx');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->isHidden(),
            Column::make("Nama", "user_id")->searchable()
                ->format(function ($value) {
                    $user = User::find($value);
                    return is_null($user) ? 'User Deleted' : ($user->name ?? '-');
                })
                ->html(),
            Column::make("Progdi", "user_id")->searchable()
                ->format(function ($value) {
                    $user = User::find($value);
                    return is_null($user) ? 'User Deleted' : ($user->progdi ?? '-');
                })
                ->html(),
            Column::make("Asesor 1", "assessor_one_id")
                ->format(function ($value) {
                    $user = User::find($value);
                    return is_null($user) ? 'User Deleted' : ($user->name ?? '-');
                })
                ->html(),
            Column::make("Asesor 2", "assessor_two_id")
                ->format(function ($value) {
                    $user = User::find($value);
                    return is_null($user) ? 'User Deleted' : ($user->name ?? '-');
                })->html(),
            Column::make("Nominal (RP)", "amount")
                ->format(function ($value) {
                    return 'Rp ' . number_format($value);
                })
                ->html()
                ->secondaryHeader(function ($rows) {

                    return 'Subtotal: <b>Rp ' . number_format($rows->sum('amount')) . '</b>';
                }),
            Column::make("Tanggal Input", "created_at")
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
            Column::make("Status Pembayaran", "status")
                ->view('admin.data.finance.view.status'),
            Column::make('Actions', 'id')
                ->view('admin.data.finance.view.action'),
        ];
    }

    public function filters(): array
    {
        $years = Payment::select(DB::raw('YEAR(created_at) as year'), DB::raw('COUNT(*) as total'))
            ->groupBy('year')
            ->get();
        if ($years->isNotEmpty()) {
            foreach ($years as $key => $value) {
                $arrayYear[$value->year] = $value->year;
            }
        } else {
            $arrayYear[Carbon::now()->format('Y')] = Carbon::now()->format('Y');
        }

        return [
            SelectFilter::make('Tahun')
                ->options($arrayYear)
                ->filter(function (Builder $builder, string $value) {
                    $builder->whereYear('created_at', $value);
                }),
            SelectFilter::make('Bulan')
                ->options([
                    '' => 'All',
                    1 => 'Januari',
                    2 => 'Februari',
                    3 => 'Maret',
                    4 => 'April',
                    5 => 'Mei',
                    6 => 'Juni',
                    7 => 'Juli',
                    8 => 'Agustus',
                    9 => 'September',
                    10 => 'Oktober',
                    11 => 'November',
                    12 => 'Desember'
                ])
                ->filter(function (Builder $builder, string $value) {
                    $builder->whereMonth('created_at', $value);
                }),
            SelectFilter::make('Is Active')
                ->options([
                    '' => 'All',
                    '1' => 'LUNAS',
                    '0' => 'PENDING',
                    '2' => 'DITOLAK',
                ])
                ->filter(function (Builder $builder, string $value) {
                    $builder->where('status', $value);
                }),
        ];
    }

    public function builder(): Builder
    {
        return Payment::query();
    }

    public function updatePaymentStatus($id, $status)
    {
        // dd($status);
        $payment = Payment::findOrFail($id);
        $payment->update(['status' => $status]);

        if($payment->status == 1) {
            FinanceAssessor::where('dosen_id', $payment->user_id)->where('user_id', $payment->assessor_one_id)->update(['status' => 1]);
            FinanceAssessor::where('dosen_id', $payment->user_id)->where('user_id', $payment->assessor_two_id)->update(['status' => 1]);
        }else{
            FinanceAssessor::where('dosen_id', $payment->user_id)->where('user_id', $payment->assessor_one_id)->update(['status' => 0]);
            FinanceAssessor::where('dosen_id', $payment->user_id)->where('user_id', $payment->assessor_two_id)->update(['status' => 0]);
        }

        // return redirect()->route('finances.index', ['category' => 'dosen']);
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

        $this->dispatch('proofOfPaymentModal');
    }

    public function deleteConfirm($id)
    {
        $this->selected_id = $id;
        $this->dispatch('deleteConfirmModal');
    }

    public function delete()
    {
        $data = Payment::findOrFail($this->selected_id);
        if($data) {
            FinanceAssessor::where('user_id', $data->assessor_one_id)->where('dosen_id', $data->user_id)->delete();
            FinanceAssessor::where('user_id', $data->assessor_two_id)->where('dosen_id', $data->user_id)->delete();
            $data->delete();
        }
        $this->dispatch('closeDeleteConfirmModal');
    }

    public function customView(): string
    {
        return 'admin.data.finance.modal';
    }
}
