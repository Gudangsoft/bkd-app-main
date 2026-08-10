<?php

namespace App\Http\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Rekening;
use Illuminate\Database\Eloquent\Builder;

class RekeningTable extends DataTableComponent
{
    protected $model = Rekening::class;
    public $selected_id, $username, $user_id, $nama_nasabah, $nomor_rekening, $jenis_rekening;

    public function configure(): void
    {
        $this->setTheme('tailwind');
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable()->isHidden(),
            Column::make('Username', 'getUser.name'),
            Column::make('Nama Nasabah', 'nama_nasabah'),
            Column::make('No Rekening', 'nomor_rekening'),
            Column::make('Jenis Rekening', 'jenis_rekening')
                ->format(function ($value) {
                    return strtoupper($value);
                })
                ->html(),
            Column::make('Saldo', 'saldo')
                ->format(function ($value) {
                    return 'Rp ' . number_format($value, 2);
                })
                ->html(),
            Column::make("Active", "status")
                ->view('admin.data.rekening.view.status'),
            Column::make('Actions', 'id')
                ->view('admin.data.rekening.view.action'),
        ];
    }

    public function builder(): Builder
    {
        if(auth()->user()->getRoleNames()[0] == 'admin') {
            return Rekening::query();
        }else{
            return Rekening::query()->where('user_id', auth()->user()->id);
        }
    }

    public function updateStatus($id)
    {
        $data = Rekening::findOrFail($id);
        ($data->status == 1 ? $data->update(['status' => 0]) : $data->update(['status' => 1]));
    }

    public function edit($id)
    {
        $this->selected_id = $id;

        $data = Rekening::find($id);
        $this->username         = $data->getUser->name;
        $this->user_id          = $data->user_id;
        $this->nama_nasabah     = $data->nama_nasabah;
        $this->nomor_rekening   = $data->nomor_rekening;
        $this->jenis_rekening   = $data->jenis_rekening;
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
        $data = Rekening::findOrFail($this->selected_id)->delete();
        $this->dispatch('closeDeleteConfirmModal');
    }

    public function customView(): string
    {
        return 'admin.data.rekening.modal';
    }
}
