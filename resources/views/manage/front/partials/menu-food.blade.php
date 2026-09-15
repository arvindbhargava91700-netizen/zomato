@php
    $firstVariant = $food->variants->first();
    if ($firstVariant) {
        $salePrice = $firstVariant->sale_price;
        $price = $firstVariant->price;
        $displayPrice = $salePrice ?: $price;
        $strikePrice = $salePrice ? $price : null;
    } else {
        $salePrice = $food->discount_price;
        $price = $food->base_price;
        $displayPrice = $salePrice ?: $price;
        $strikePrice = $salePrice ? $price : null;
    }

    $image = $food->image ? asset($food->image) : asset('front/assets/images/menu/13.jpg');
    $vegIcon = $food->is_veg ? 'front/assets/images/svg/veg.svg' : 'front/assets/images/svg/nonveg.svg';
    $isCustomized = $food->variants->count() > 0;
@endphp

<div class="product-details-box">
    <div class="product-img">
        <img class="img-fluid img" src="{{ $image }}" alt="{{ $food->name }}">
    </div>
    <div class="product-content">
        <div class="description d-flex align-items-center justify-content-between gap-1">
            <div>
                <div class="d-flex align-items-center gap-2">
                    <img class="img-fluid" src="{{ asset($vegIcon) }}" alt="veg">
                    <h6 class="product-name">{{ $food->name }}</h6>
                    @if ($isCustomized)
                        <h6 class="customized">Customized</h6>
                    @endif
                </div>

                <p class="mb-1">{{ $food->short_description ?: $food->description }}</p>
                @if ($isCustomized)
                    <div class="variant-tags d-flex align-items-center flex-wrap gap-1 mt-1">
                            @foreach ($food->variants as $variant)
                                <span id="variant-{{ $variant->id }}" class="badge bg-light text-dark border fw-normal" style="font-size: 11px;">
                                    {{ $variant->variant_name }}: {{ $currencySymbol }}{{ number_format($variant->sale_price ?: $variant->price, 0) }}
                                </span>
                            @endforeach
                    </div>
                @endif
            </div>
            <div class="product-box-price">
                <h2 class="theme-color fw-semibold">
                    {{ $currencySymbol }}{{ $displayPrice }}
                    @if ($strikePrice)
                        <span>/ <del>{{ $currencySymbol }}{{ $strikePrice }}</del></span>
                    @endif
                </h2>
                <button type="button" class="btn theme-outline add-btn mt-0"
                    data-id="{{ $food->id }}" data-name="{{ $food->name }}"
                    data-price="{{ $displayPrice }}" data-img="{{ $image }}"
                    data-desc="{{ $food->short_description ?: $food->description }}"
                    data-has-variants="{{ $isCustomized ? '1' : '0' }}"
                    data-variants='@json($food->variants)'>+Add</button>
            </div>
        </div>
    </div>
</div>
