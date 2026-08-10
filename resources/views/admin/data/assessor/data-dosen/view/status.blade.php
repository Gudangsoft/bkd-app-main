<div>
    <div class="btn-group">
        <button class="btn btn-sm btn-{{ $row->status == 1 ? 'success' : ($row->status == 2 ? 'danger' : 'light') }} dropdown-toggle" type="button" id="defaultDropdown" data-bs-toggle="dropdown"
            data-bs-auto-close="true" aria-expanded="false">
            {{ $row->status == 1 ? 'LUNAS' : ($row->status == 2 ? 'DITOLAK' : 'PENDING') }}
        </button>
        @role('admin|finance')
        <ul class="dropdown-menu" aria-labelledby="defaultDropdown">
            <li><a class="dropdown-item" href="#" wire:click='updatePaymentStatus({{ $row->id }}, 1)'>Lunas</a></li>
            <li><a class="dropdown-item" href="#" wire:click='updatePaymentStatus({{ $row->id }}, 0)'>Pending</a></li>
            <li><a class="dropdown-item" href="#" wire:click='updatePaymentStatus({{ $row->id }}, 2)'>Ditolak</a></li>
        </ul>
        @endrole
    </div>
</div>
