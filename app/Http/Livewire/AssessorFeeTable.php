<?php

namespace App\Http\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\AssessorFee;
use Illuminate\Database\Eloquent\Builder;

class AssessorFeeTable extends DataTableComponent
{
    protected $model = AssessorFee::class;

    public $name, $internal, $external, $selected_id;

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
            Column::make('Name', 'name'),
            Column::make('Internal', 'internal')
                ->format(function ($value) {
                    return 'Rp ' . number_format($value, 2);
                })
                ->html(),
            Column::make('Eksternal', 'external')
                ->format(function ($value) {
                    return 'Rp ' . number_format($value, 2);
                })
                ->html(),
            Column::make('Eksternal Non Kepanitiaan', 'external_dif')
                ->format(function ($value) {
                    return 'Rp ' . number_format($value, 2);
                })
                ->html(),
            Column::make('Actions', 'id')
                ->view('admin.data.assessor-fee.view.action'),
        ];
    }

    public function builder(): Builder
    {
        return AssessorFee::query()->where('id', '<', 3);
    }

    public function edit($id)
    {
        $this->selected_id = $id;

        $data = AssessorFee::find($id);
        $this->name             = $data->name;
        $this->internal         = $data->internal;
        $this->external         = $data->external;
        // dd($this->uid);
        $this->dispatch('editOpenModal');
    }

    public function customView(): string
    {
        return 'admin.data.assessor-fee.modal';
    }
}
