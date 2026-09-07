<div class="col-xl-4 col-lg-6 col-sm-6 col-12">
    <div class="debit-card {{ $color ?? 'color-1' }}">
        <div class="card-details">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-name fw-semibold">{{ $card->type ?? 'CREDIT CARD' }}</h5>
                <img class="img-fluid network" src="{{ asset('front/assets/images/svg/network.svg') }}"
                    alt="network">
            </div>
            <img class="img-fluid chip" src="{{ asset('front/assets/images/svg/Chip.svg') }}" alt="chip">
            <div class="ac-details">
                <h6>AC No.</h6>
                <h3>{{ $card->card_number }}</h3>
            </div>
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex gap-2">
                    <h6>Exp.</h6>
                    <h5>{{ $card->exp_date ? \Carbon\Carbon::parse($card->exp_date)->format('m/y') : '' }}</h5>
                </div>
                <div class="d-flex gap-2">
                    <h6>CVV</h6>
                    <h5>***</h5>
                </div>
            </div>
            <div class="user-name">
                <h5>{{ $card->holder_name }}</h5>
            </div>
        </div>
        <div class="card-hover">
            <div class="d-flex align-items-center gap-3">
                <a data-bs-toggle="modal" data-bs-target="#edit-card-{{ $card->id }}" href="#"
                    class="text-white">
                    <i class="ri-edit-2-fill edit-icon"></i> Edit</a>
                <a href="#delete-card-{{ $card->id }}" data-bs-toggle="modal" class="text-white"><i
                        class="ri-delete-bin-fill"></i> Delete</a>
            </div>
        </div>
    </div>
</div>
