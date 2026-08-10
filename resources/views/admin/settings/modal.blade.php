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
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" wire:click='delete()'>Save changes</button>
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
                    @lang('dashboard.user.delete_confirm')
                </h3>
            </div>
            <div class="modal-footer text-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" wire:click='updateStatus()'>Save changes</button>
            </div>
        </div>
    </div>
</div>
