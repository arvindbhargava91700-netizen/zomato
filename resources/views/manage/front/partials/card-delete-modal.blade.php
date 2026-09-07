<div class="modal address-details-modal fade" id="delete-card-{{ $card->id }}" tabindex="-1"
    aria-labelledby="removeCard{{ $card->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="removeCard{{ $card->id }}">delete Card</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you Sure, Your Card is Delete</p>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn gray-btn mt-0" data-bs-dismiss="modal">CANCEL</a>
                <form method="POST" action="{{ route('card.destroy', $card->id) }}" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn theme-outline mt-0">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
