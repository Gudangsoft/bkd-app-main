<div>
{{-- {{ dd($row->AssignmentLetterStatusOne) }} --}}
    <div class="btn-group">
        <button
            {{ $row->status == 1 || $row->assessor_one_id != auth()->user()->id ? 'disabled' : '' }}
            class="btn btn-sm btn-{{ $row->status_accessor_one == 3 ? 'success' : ($row->status_accessor_one == 2 ? 'warning' : 'light') }} dropdown-toggle"
            type="button" id="defaultDropdown" data-bs-toggle="dropdown" data-bs-auto-close="true" aria-expanded="false">
            {{ $row->status_accessor_one == 3 ? 'Sudah dinilai asesor' : ($row->status_accessor_one == 2 ? 'Belum dinilai asesor' : 'Asesor belum ditugaskan') }}
        </button>
        @role('asesor')
            <ul class="dropdown-menu" aria-labelledby="defaultDropdown">
                <li><a class="dropdown-item" href="#"
                        wire:click='updateStatusAccessorOne({{ $row->id }}, 3)'>Sudah dinilai asesor</a></li>
                <li><a class="dropdown-item" href="#"
                        wire:click='updateStatusAccessorOne({{ $row->id }}, 2)'>Belum dinilai asesor</a></li>
                <li><a class="dropdown-item" href="#"
                        wire:click='updateStatusAccessorOne({{ $row->id }}, 1)'>Asesor belum ditugaskan</a></li>
            </ul>
        @endrole
    </div>
</div>
