<div>
    <div class="btn-group">
        <button {{ $row->status == 2 ? 'disabled' : '' }} class="btn btn-sm btn-{{ $row->status_accessor_two == 3 ? 'success' : ($row->status_accessor_two == 2 ? 'warning' : 'light') }} dropdown-toggle" type="button" id="defaultDropdown" data-bs-toggle="dropdown"
            data-bs-auto-close="true" aria-expanded="false">
            {{ $row->status_accessor_two == 3 ? 'Memenuhi' : ($row->status_accessor_two == 2 ? 'Penilaian belum bisa dilakukan' : 'Belum ditentukan') }}
        </button>
        @role('admin')
        <ul class="dropdown-menu" aria-labelledby="defaultDropdown">
            <li><a class="dropdown-item" href="#" wire:click='updateStatusAccessorTwo({{ $row->id }}, 3)'>Memenuhi</a></li>
            <li><a class="dropdown-item" href="#" wire:click='updateStatusAccessorTwo({{ $row->id }}, 2)'>Penilaian belum bisa dilakukan</a></li>
            <li><a class="dropdown-item" href="#" wire:click='updateStatusAccessorTwo({{ $row->id }}, 1)'>Belum ditentukan</a></li>
        </ul>
        @endrole
    </div>
</div>
