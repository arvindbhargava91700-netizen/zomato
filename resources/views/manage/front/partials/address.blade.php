@php
    $label = strtolower($address->label ?? '');
    $icon = 'ri-home-4-fill';
    if ($label === 'office') {
        $icon = 'ri-briefcase-4-fill';
    } elseif ($label !== '' && $label !== 'home') {
        $icon = 'ri-account-circle-fill';
    }
@endphp
<div class="col-md-6">
    <div class="address-box white-bg" data-address-id="{{ $address->id }}">
        <div class="address-title">
            <div class="d-flex align-items-center gap-2">
                <i class="{{ $icon }} icon"></i>
                <h6>{{ $address->label }}</h6>
            </div>
            <div class="d-flex align-items-center gap-3">
                <a href="#edit-address-{{ $address->id }}" class="edit-btn" data-bs-toggle="modal">Edit</a>
                <a href="#delete-address-{{ $address->id }}" class="text-danger" data-bs-toggle="modal" style="font-size: 1.1rem; line-height: 1;"><i class="ri-delete-bin-line"></i></a>
            </div>
        </div>
        <div class="address-details">
            <h6>
                {{ $address->address }}, {{ $address->city }}, {{ $address->country }}-{{ $address->zip }}
            </h6>
            <h6 class="phone-number">{{ $address->phone }}</h6>
        </div>
    </div>
</div>
