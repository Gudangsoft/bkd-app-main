<div class="modal modal-right large fade" id="editOpenModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title text-primary fw-bold">@lang('dashboard.payment.create')</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('payments.update', $selected_id ?? 0) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="mb-1">Nama Dosen</label>
                        <select name="user_list[]" class="form-control" id="select2Basic" disabled>
                            <option value="{{ $uid }}">{{ $name }}</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="mb-1">@lang('dashboard.payment.assessor_one')</label>
                        <select name="assessor_one[]" class="form-control" id="select2BasicA" disabled>
                            @foreach ($assessor as $item)
                                <option value="{{ $item->id }}" {{ $item->id == $assessor_one ? 'selected' : '' }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="mb-1">@lang('dashboard.payment.assessor_two')</label>
                        <select name="assessor_two[]" class="form-control" id="select2BasicB" disabled>
                            @foreach ($assessor as $item)
                                <option value="{{ $item->id }}" {{ $item->id == $assessor_two ? 'selected' : '' }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="mb-1">@lang('dashboard.payment.amount')</label>
                        <input type="number" name="amount" class="form-control" placeholder="" value="{{ $amount }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="mb-1">@lang('dashboard.payment.rekening_payment')</label>
                        <select name="rekening_id" class="form-control">
                            @foreach ($rekenings as $item)
                                <option value="{{ $item->id }}" {{ $item->id == $rekening_id ? 'selected' : '' }}>{{ $item->jenis_bank }}
                                    {{ $item->nomor_rekening }} a.n {{ $item->nama_nasabah }}</option>
                            @endforeach
                        </select>
                        <small class="text-danger">@lang('dashboard.payment.payment_info')</small>
                    </div>
                    <div class="mb-3">
                        <label class="mb-1">@lang('dashboard.payment.proof_of_payment')</label>
                        <input type="file" name="image" class="form-control" placeholder="Image" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label class="mb-1">@lang('dashboard.payment.description') (opsional)</label>
                        <textarea name="description" class="form-control" id="" cols="5" rows="3">{{ $description }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">@lang('dashboard.save')</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade modal-close-out" id="deleteConfirmModal" tabindex="-1" role="dialog"
    aria-labelledby="verticallyCenteredLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <h3>
                    @lang('dashboard.user.delete_confirm')
                </h3>
            </div>
            <div class="modal-footer text-center">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" wire:click='delete()'>Delete</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-close-out" id="deleteSelectedConfirmModal" tabindex="-1" role="dialog"
    aria-labelledby="verticallyCenteredLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <h3>
                    @lang('dashboard.user.delete_selected_confirm')
                </h3>
            </div>
            <div class="modal-footer text-center">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" wire:click='deleteSelected()'>Delete</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-close-out" id="updateStatusConfirmModal" tabindex="-1" role="dialog"
    aria-labelledby="verticallyCenteredLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <h3>
                    @lang('dashboard.user.update_status')
                </h3>
            </div>
            <div class="modal-footer text-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary"
                    wire:click='updateStatus()'>@lang('dashboard.yes')</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-close-out" id="resetPasswordOpenModal" tabindex="-1" role="dialog"
    aria-labelledby="verticallyCenteredLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <h3>
                    @lang('dashboard.user.reset_password_success')
                </h3>
            </div>
            <div class="modal-footer text-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
