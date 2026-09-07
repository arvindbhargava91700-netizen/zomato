<div class="modal address-details-modal fade" id="edit-card-{{ $card->id }}" tabindex="-1"
    aria-labelledby="editCard{{ $card->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('card.update', $card->id) }}">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editCard{{ $card->id }}">Edit Card</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="editname{{ $card->id }}" class="form-label">Card Holder Name</label>
                            <input type="text" class="form-control" id="editname{{ $card->id }}" name="holder_name"
                                value="{{ old('holder_name', $card->holder_name) }}" placeholder="Enter your last name">
                        </div>
                        <div class="col-md-12">
                            <label for="editcardnumber{{ $card->id }}" class="form-label">Card Number</label>
                            <input type="text" class="form-control" id="editcardnumber{{ $card->id }}" name="card_number"
                                value="{{ old('card_number', $card->card_number) }}" placeholder="Enter card number">
                        </div>
                        <div class="col-md-8">
                            <label for="editdate{{ $card->id }}" class="form-label">Exp. Date</label>
                            <input type="date" class="form-control" id="editdate{{ $card->id }}" name="exp_date"
                                value="{{ old('exp_date', $card->exp_date) }}">
                        </div>
                        <div class="col-md-4">
                            <label for="editcvv{{ $card->id }}" class="form-label">CVV</label>
                            <input type="password" class="form-control" id="editcvv{{ $card->id }}" name="cvv"
                                value="{{ old('cvv', $card->cvv) }}" placeholder="Enter your cvv">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn gray-btn mt-0" data-bs-dismiss="modal">CANCEL</a>
                    <button type="submit" class="btn theme-btn mt-0">Edit Card</button>
                </div>
            </form>
        </div>
    </div>
</div>
