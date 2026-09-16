<div class="modal fade" id="delete-address-{{ $address->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('address.destroy', $address->id) }}" class="delete-address-form">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Confirm Delete</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0 fs-15">Are you sure you want to delete this address? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn gray-btn mt-0" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger mt-0 text-white border-0" style="background-color: #cb202d;">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
