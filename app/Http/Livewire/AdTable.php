<?php

namespace App\Http\Livewire;

use App\Models\Ad;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class AdTable extends DataTableComponent
{
    protected $model = Ad::class;

    public $selected_id, $title, $url, $description, $type, $image;

    public function configure(): void
    {
        $this->setTheme('tailwind');
        $this->setPrimaryKey('id');
        $this->setDefaultSort('id', 'desc');
        $this->setSearchStatus(true);
        $this->setSearchEnabled();
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id')->sortable()->isHidden(),
            Column::make('Image', 'image')
                ->format(function ($value) {
                    if (!$value) {
                        return '<span class="text-xs text-gray-400">&mdash;</span>';
                    }
                    return '<img src="' . asset('storage/images/ads/' . $value) . '" class="h-10 w-16 rounded-lg object-cover" alt="">';
                })
                ->html(),
            Column::make('Title', 'title')->sortable()->searchable(),
            Column::make('Type', 'type')
                ->format(function ($value) {
                    $label = $value === Ad::TYPE_HOME_POPUP ? 'Home Popup' : 'Home Slider';
                    return '<span class="inline-flex items-center rounded-full bg-accent-50 px-2.5 py-1 text-xs font-semibold text-accent-700">' . e($label) . '</span>';
                })
                ->html(),
            Column::make('Url', 'url')
                ->format(fn ($value) => $value ? '<a href="' . e($value) . '" target="_blank" class="text-accent-600 hover:underline">' . e(\Illuminate\Support\Str::limit($value, 30)) . '</a>' : '<span class="text-xs text-gray-400">&mdash;</span>')
                ->html(),
            Column::make('Status', 'status')
                ->view('admin.ads.view.status'),
            Column::make('Actions', 'id')
                ->view('admin.ads.view.action'),
        ];
    }

    public function filters(): array
    {
        return [
            SelectFilter::make('Type')
                ->options([
                    '' => 'All',
                    Ad::TYPE_HOME_SLIDER => 'Home Slider',
                    Ad::TYPE_HOME_POPUP => 'Home Popup',
                ])
                ->filter(function (Builder $builder, string $value) {
                    $builder->where('type', $value);
                }),
        ];
    }

    public function builder(): Builder
    {
        return Ad::query();
    }

    public function updateStatus($id)
    {
        $ad = Ad::findOrFail($id);
        $ad->update(['status' => $ad->status == 1 ? 0 : 1]);
    }

    public function edit($id)
    {
        $this->selected_id = $id;

        $ad = Ad::findOrFail($id);
        $this->title = $ad->title;
        $this->url = $ad->url;
        $this->description = $ad->description;
        $this->type = $ad->type;
        $this->image = $ad->image;

        $this->dispatch('editOpenModal');
    }

    public function deleteConfirm($id)
    {
        $this->selected_id = $id;
        $this->dispatch('deleteConfirmModal');
    }

    public function delete()
    {
        Ad::findOrFail($this->selected_id)->delete();
        $this->dispatch('closeDeleteConfirmModal');
    }

    public function customView(): string
    {
        return 'admin.ads.modal';
    }
}
