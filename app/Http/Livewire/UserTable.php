<?php

namespace App\Http\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class UserTable extends DataTableComponent
{
    protected $model = User::class;
    // protected $listeners = ['refreshCustomerLinksTable' => '$refresh'];

    public $selected_id, $category;
    public $uid, $name, $email, $role, $status, $nidn, $progdi, $campus_origin, $assessor_fee;

    public function configure(): void
    {
        $this->setTheme('tailwind');
        $this->setPrimaryKey('id');
        $this->setDefaultSort('id', 'desc');
        $this->setBulkActionsStatus(true);
        $this->setBulkActions([
            'deleteSelectedConfirm' => 'Delete',
        ]);
        $this->setSearchStatus(true);
        $this->setSearchEnabled();
    }

    public function columns(): array
    {
        return [
            Column::make("ID", "id")->searchable(),
            Column::make("Role", "id")
                ->sortable()
                ->searchable()
                ->format(
                    fn($value, $row, Column $column) => '<span class="inline-flex items-center rounded-full bg-accent-50 px-2.5 py-1 text-xs font-semibold text-accent-700">' . e($row->getRoleNames()->isEmpty() ? 'guest' : $row->getRoleNames()[0]) . '</span>'
                )
                ->html(),
            Column::make("Name", "name")
                ->sortable()->searchable(),
            Column::make("Email", "email"),
            Column::make("NIDN", "nidn"),
            Column::make("Progdi", "progdi"),
            Column::make("Asal Kampus", "campus_origin"),
            Column::make("Status Serdos", "assessor_fee")
                ->view('admin.users.view.status_dosen'),
            Column::make("Status Asesor", "status")
                ->view('admin.users.view.status_assessor'),
            Column::make("Active", "is_active")
                ->view('admin.users.view.status'),
            Column::make("Created at", "created_at")
                ->sortable(),
            Column::make('Actions', 'id')
                ->view('admin.users.view.action'),
        ];
    }

    public function filters(): array
    {
        return [
            SelectFilter::make('Role')
                ->options([
                    '' => 'All',
                    'dosen' => 'Dosen',
                    'asesor' => 'Asesor',
                ])
                ->filter(function (Builder $builder, string $value) {
                    $builder->role($value);
                }),
            SelectFilter::make('Status')
                ->options([
                    '' => 'All',
                    'user' => 'User',
                    'internal' => 'Internal',
                    'external' => 'Eksternal',
                    'external_dif' => 'Eksternal (di luar kepanitiaan)',
                    'serdos' => 'Serdos',
                    'non-serdos' => 'Non Serdos',
                ])
                ->filter(function (Builder $builder, string $value) {
                    $builder->where('status', $value);
                }),
            SelectFilter::make('Is Active')
                ->options([
                    '' => 'All',
                    '1' => 'Active',
                    '0' => 'Non Active',
                ])
                ->filter(function (Builder $builder, string $value) {
                    $builder->where('is_active', $value);
                }),
        ];
    }

    public function builder(): Builder
    {
        switch ($this->category) {
            case 'dosen':
                return User::query()->role('dosen');
                break;
            case 'asesor':
                return User::query()->role('asesor');
                break;
            default:
                return User::query();
                break;
        }
    }

    public function edit($id)
    {
        $this->selected_id = $id;

        $user = User::find($id);
        $this->uid = $user->id;
        $this->name = $user->name;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->nidn = $user->nidn;
        $this->progdi = $user->progdi;
        // Legacy records saved the campus name into progdi (there was no
        // dedicated field before). Fall back to it here so the edit form
        // shows the real saved data instead of a blank Asal Kampus.
        $this->campus_origin = $user->campus_origin ?: $user->progdi;
        $this->status = $user->status;
        $this->assessor_fee = $user->assessor_fee;
        $this->role = $user->getRoleNames()->isEmpty() ? 'guest' : $user->getRoleNames()[0];
        // dd($this->role);
        $this->dispatch('editOpenModal');
    }

    public function resetPassword($id)
    {
        try {
            User::find($id)->update([
                'password' => Hash::make('bkdstekomoke'),
            ]);

            $this->dispatch('resetPasswordOpenModal');
        } catch (Exception $error) {
            dd($error->getMessage());
        }
    }

    public function deleteConfirm($id)
    {
        $this->selected_id = $id;
        $this->dispatch('deleteConfirmModal');
    }

    public function delete()
    {
        // dd($this->selected_id);
        User::findOrFail($this->selected_id)->delete();
        $this->dispatch('closeDeleteConfirmModal');
    }


    public function updateStatusConfirm($id)
    {
        $this->selected_id = $id;
        $this->dispatch('updateStatusConfirmModal');
    }

    public function updateStatus()
    {
        $data = User::findOrFail($this->selected_id);
        ($data->is_active == 1 ? $data->update(['is_active' => 0]) : $data->update(['is_active' => 1]));
        $this->dispatch('closeUpdateStatusConfirmModal');
    }

    public function deleteSelectedConfirm()
    {
        // dd($this->getSelected());
        $this->dispatch('deleteSelectedConfirmModal');
    }

    public function deleteSelected()
    {
        User::whereIn('id', $this->getSelected())->delete();
        $this->dispatch('closeDeleteSelectedConfirmModal');
    }

    public function loginAs($id)
    {
        // The /data/master-data route this table is embedded on isn't
        // middleware-gated, so this check can't rely on route protection -
        // it has to hold on its own since it grants full account access.
        abort_unless(auth()->user()?->hasRole('admin'), 403);

        $target = User::findOrFail($id);

        if ($target->id === auth()->id() || $target->hasRole('admin') || !$target->is_active) {
            return;
        }

        session(['impersonator_id' => auth()->id()]);
        auth()->login($target);
        // Jetstream's AuthenticateSession middleware compares this against
        // the current user's password on every request and force-logs-out
        // on a mismatch - without updating it here, the very next request
        // (the redirect below) gets treated as a hijacked session.
        session()->put('password_hash_' . auth()->getDefaultDriver(), $target->getAuthPassword());

        return redirect()->route('dashboard.index');
    }

    public function customView(): string
    {
        return 'admin.users.modal';
    }
}
