<?php

namespace App\Http\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\AssignmentLetter;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class AssignmentLetterTable extends DataTableComponent
{
    protected $model = AssignmentLetter::class;
    public $selected_id;
    public $dosen,$dosen_id, $assessor, $assessor_id, $file, $url, $created_by, $status, $description;
    public $user_role = [];

    public function mount()
    {
        $this->user_role = auth()->user()->getRoleNames()[0];
    }

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
            Column::make('Nama Asesor', 'assessor.name'),
            Column::make('Dosen ', 'dosen_id')
            ->format(function ($value) {
                $user = User::find($value);
                if (is_null($user)) {
                    return 'User Deleted';
                }
                return e($user->name) . ' <span class="ml-1 inline-flex items-center rounded-full bg-accent-50 px-2 py-0.5 text-[11px] font-medium text-accent-700">' . e($user->getAssessorFee->name) . '</span>';
            })->html(),
            // Column::make('File Surat', 'file'),
            Column::make('Url File Surat', 'url')
                ->format(function ($value) {
                    return "<a href='$value'>$value</a>";
                })
                ->html(),
            Column::make("Status", "status")
                ->view('admin.data.assignment-letter.view.status'),
            Column::make('Actions', 'id')
                ->view('admin.data.assignment-letter.view.action')->hideIf($this->user_role == 'asesor'),
        ];
    }

    public function builder(): Builder
    {
        if($this->user_role == 'asesor')
        {
            return AssignmentLetter::query()->where('assessor_id', auth()->user()->id);
        }else{
            return AssignmentLetter::query();
        }
    }

    public function updateStatus($id)
    {
        $data = AssignmentLetter::findOrFail($id);
        ($data->status == 1 ? $data->update(['status' => 0]) : $data->update(['status' => 1]));
    }

    public function edit($id)
    {
        $this->selected_id = $id;

        $data = AssignmentLetter::find($id);
        $this->assessor_id = $data->assessor_id;
        $this->assessor = $data->assessor->name;
        $this->dosen_id = $data->dosen_id;
        $this->dosen = $data->dosen->name;
        $this->file = $data->file;
        $this->url = $data->url;
        $this->description = $data->description;
        $this->status = $data->status;
        $this->created_by = $data->created_by;

        $this->dispatch('editOpenModal');
    }

    public function deleteConfirm($id)
    {
        $this->selected_id = $id;
        $this->dispatch('deleteConfirmModal');
    }

    public function delete()
    {
        $data = AssignmentLetter::findOrFail($this->selected_id)->delete();
        $this->dispatch('closeDeleteConfirmModal');
    }

    public function customView(): string
    {
        return 'admin.data.assignment-letter.modal';
    }
}
