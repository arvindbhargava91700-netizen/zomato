@extends('layouts.restaurant.main')

@section('title', 'POS Billing - Dining Bill')

@php
    $bill = $booking->billBreakdown();
    $items = $booking->bill_items ?? [];
    $offer = $booking->diningOffer;

    $initialCart = [];
    foreach ($items as $it) {
        $initialCart[] = [
            'name' => $it['name'],
            'price' => (float) $it['price'],
            'qty' => (int) $it['qty'],
            'food_id' => $it['food_id'] ?? null,
            'variant' => $it['variant'] ?? null,
        ];
    }
@endphp

@push('styles')
<style>
    :root {
        --pos-bg: #f5f2ee;
        --pos-primary: #ff4d4f;
        --pos-primary-dark: #e03a3c;
        --pos-accent: #ff9f43;
        --pos-dark: #2b2a33;
        --pos-green: #1fad5c;
        --pos-red: #e74c3c;
    }

    .pos-wrap { background: var(--pos-bg); border-radius: 20px; }

    .pos-header-card {
        background: linear-gradient(120deg, #2b2a33 0%, #3d3a45 55%, #4a3f35 100%);
        border: none; border-radius: 18px; overflow: hidden;
        color: #fff; position: relative;
    }
    .pos-header-card::after {
        content: ''; position: absolute; top: -60px; right: -40px;
        width: 220px; height: 220px; border-radius: 50%;
        background: radial-gradient(circle, rgba(255, 159, 67, .35) 0%, transparent 70%);
    }
    .pos-header-card::before {
        content: ''; position: absolute; bottom: -80px; right: 120px;
        width: 200px; height: 200px; border-radius: 50%;
        background: radial-gradient(circle, rgba(255, 77, 79, .3) 0%, transparent 70%);
    }

    .pos-avatar {
        width: 54px; height: 54px; border-radius: 14px;
        background: linear-gradient(135deg, var(--pos-accent), var(--pos-primary));
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 24px; color: #fff; box-shadow: 0 6px 16px rgba(255, 77, 79, .4);
    }
    .pos-info-chip {
        background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.12);
        border-radius: 12px; padding: 8px 14px; backdrop-filter: blur(4px);
    }
    .pos-info-chip .label { font-size: 11px; text-transform: uppercase; letter-spacing: .6px; opacity: .7; }
    .pos-info-chip .value { font-weight: 700; font-size: 15px; }

    /* ---- Menu panel ---- */
    .pos-panel {
        border: none; border-radius: 18px; overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,.05);
    }
    .pos-search {
        background: #f6f6f9; border: 2px solid #ecedf2; border-radius: 12px;
        transition: border-color .2s;
    }
    .pos-search:focus-within { border-color: var(--pos-primary); }
    .pos-search input {
        background: transparent; border: none; outline: none;
        box-shadow: none !important; padding: 10px 12px; font-weight: 500;
    }

    .pos-cat-pill {
        border: 1.5px solid #e4e5ec; background: #fff; color: #6b6b7b;
        border-radius: 50rem; padding: 7px 16px; font-weight: 600; font-size: 13px;
        transition: all .2s ease; white-space: nowrap;
    }
    .pos-cat-pill:hover { border-color: var(--pos-primary); color: var(--pos-primary); }
    .pos-cat-pill.active {
        background: linear-gradient(135deg, var(--pos-primary), var(--pos-accent));
        border-color: transparent; color: #fff;
        box-shadow: 0 6px 14px rgba(255, 77, 79, .3);
    }

    /* ---- Food cards ---- */
    .pos-food-card {
        position: relative; border: none; border-radius: 16px; overflow: hidden;
        background: #fff; box-shadow: 0 4px 14px rgba(0,0,0,.04);
        transition: transform .2s ease, box-shadow .2s ease; cursor: pointer;
    }
    .pos-food-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 14px 28px rgba(0,0,0,.10);
    }
    .pos-food-card .food-img-wrap {
        height: 108px; overflow: hidden; position: relative; background: #f0f0f3;
    }
    .pos-food-card .food-img-wrap img { width: 100%; height: 100%; object-fit: cover; transition: transform .35s ease; }
    .pos-food-card:hover .food-img-wrap img { transform: scale(1.08); }
    .pos-veg-badge {
        position: absolute; top: 10px; left: 10px; background: #fff;
        width: 22px; height: 22px; border-radius: 4px; display: flex;
        align-items: center; justify-content: center; box-shadow: 0 2px 6px rgba(0,0,0,.15);
    }
    .pos-spicy-badge {
        position: absolute; top: 10px; right: 10px; background: rgba(231,76,60,.92);
        color: #fff; font-size: 11px; font-weight: 700; padding: 3px 8px;
        border-radius: 50rem; letter-spacing: .4px;
    }
    .pos-food-card .food-body { padding: 12px 14px 10px; }
    .pos-food-card .food-name {
        font-weight: 700; font-size: 12px; color: var(--pos-dark);
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .pos-food-card .food-cat {
        font-size: 11px; color: #a0a0b0; font-weight: 600; text-transform: uppercase; letter-spacing: .5px;
    }
    .pos-food-card .food-price {
        color: var(--pos-primary); font-weight: 700; font-size: 14px;
    }
    .pos-food-card .food-strike { color: #b9b9c6; font-size: 12px; text-decoration: line-through; font-weight: 500; }
    .pos-add-fab {
        position: absolute; right: 12px; bottom: -16px;
        width: 34px; height: 34px; border-radius: 12px; border: none;
        background: linear-gradient(135deg, var(--pos-primary), var(--pos-accent));
        color: #fff; font-size: 20px; line-height: 1;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 6px 14px rgba(255, 77, 79, .35); transition: transform .15s ease;
    }
    .pos-add-fab:hover { transform: scale(1.1); }
    .pos-food-card .qty-on-card {
        position: absolute; right: 10px; bottom: -16px;
        display: flex; align-items: center; gap: 6px;
        background: var(--pos-dark); border-radius: 12px; padding: 4px 6px;
        box-shadow: 0 6px 14px rgba(0,0,0,.25);
    }
    .qty-on-card button {
        width: 26px; height: 26px; border-radius: 8px; border: none;
        background: rgba(255,255,255,.14); color: #fff; font-weight: 700; line-height: 1;
        display: inline-flex; align-items: center; justify-content: center;
    }
    .qty-on-card button:hover { background: var(--pos-primary); }
    .qty-on-card .n { color: #fff; font-weight: 700; font-size: 13px; min-width: 18px; text-align: center; }

    /* ---- Cart ---- */
    .pos-cart { position: sticky; top: 100px; }
    .pos-cart .cart-head {
        background: linear-gradient(135deg, var(--pos-dark), #3d3a45);
        color: #fff; padding: 16px 18px;
    }
    .pos-cart-body { max-height: 340px; overflow-y: auto; background: #fbfbfd; }
    .pos-cart-body::-webkit-scrollbar { width: 5px; }
    .pos-cart-body::-webkit-scrollbar-thumb { background: #d7d7e0; border-radius: 10px; }

    .pos-cart-item {
        display: flex; align-items: center; gap: 10px;
        padding: 10px 14px; border-bottom: 1px dashed #e8e8ef;
        animation: posIn .25s ease;
    }
    @keyframes posIn { from { opacity: 0; transform: translateX(10px); } to { opacity: 1; transform: none; } }
    .pos-cart-item .ci-veg { width: 16px; height: 16px; flex: 0 0 16px; }
    .pos-cart-item .ci-info { flex: 1; min-width: 0; }
    .pos-cart-item .ci-name { font-weight: 700; font-size: 13px; color: var(--pos-dark); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .pos-cart-item .ci-unit { font-size: 11.5px; color: #90909e; }
    .pos-cart-item .ci-total { font-weight: 800; font-size: 13px; color: var(--pos-dark); min-width: 62px; text-align: right; }
    .ci-remove {
        border: none; background: transparent; color: #c2c2cf;
        font-size: 17px; line-height: 1; padding: 2px 4px;
    }
    .ci-remove:hover { color: var(--pos-red); }

    .qty-stepper {
        display: inline-flex; align-items: center; gap: 2px;
        background: #fff; border: 1.5px solid #e8e8f0; border-radius: 50rem; padding: 2px;
    }
    .qty-stepper button {
        width: 26px; height: 26px; border-radius: 50rem; border: none;
        background: #f2f2f7; color: var(--pos-dark); font-weight: 800; line-height: 1;
        display: inline-flex; align-items: center; justify-content: center;
        transition: background .15s;
    }
    .qty-stepper button.minus:hover { background: #fdeaea; color: var(--pos-red); }
    .qty-stepper button.plus:hover { background: #e6f6ec; color: var(--pos-green); }
    .qty-stepper .qty-val { min-width: 28px; text-align: center; font-weight: 800; font-size: 14px; }

    .pos-custom-add {
        background: #fff; border-top: 1px solid #ececf2; padding: 12px 14px;
    }

    .pos-summary {
        background: #fff; padding: 16px 18px; border-top: 1px solid #ececf2;
    }
    .pos-summary .row-line { display: flex; justify-content: space-between; padding: 4px 0; font-size: 14px; color: #6b6b7b; }
    .pos-summary .row-line b { color: var(--pos-dark); }
    .pos-summary .row-line.total {
        border-top: 1.5px dashed #dcdce6; margin-top: 6px; padding-top: 10px;
        font-size: 17px; color: var(--pos-dark);
    }
    .pos-summary .row-line.total b { font-size: 20px; color: var(--pos-green); }

    .pos-save-btn {
        background: linear-gradient(135deg, var(--pos-primary), var(--pos-accent));
        border: none; color: #fff; font-weight: 800; font-size: 16px; letter-spacing: .5px;
        border-radius: 14px; padding: 12px 18px; box-shadow: 0 8px 20px rgba(255, 77, 79, .35);
        transition: transform .15s, box-shadow .15s;
    }
    .pos-save-btn:hover { transform: translateY(-2px); box-shadow: 0 12px 26px rgba(255, 77, 79, .45); color: #fff; }

    .pos-offer-banner {
        background: linear-gradient(135deg, #fff7ed, #ffede0);
        border: 1px dashed var(--pos-accent); color: #c2410c;
        border-radius: 12px; padding: 10px 14px; font-size: 13px; font-weight: 600;
    }
    .pos-cart-empty { padding: 36px 16px; text-align: center; color: #b0b0bd; }
    .pos-cart-empty i { font-size: 38px; }

    /* ---- Print receipt ---- */
    #printReceipt { display: none; }
    @media print {
        @page { margin: 8mm; }
        body * { visibility: hidden; }
        #printReceipt, #printReceipt * { visibility: visible; }
        #printReceipt {
            display: block !important; position: absolute; left: 0; top: 0;
            width: 100%; background: #fff; padding: 8px;
        }
        .no-print { display: none !important; }
    }
    .pos-receipt {
        font-family: 'Segoe UI', Arial, sans-serif; color: #1a1a1a;
        max-width: 430px; margin: 0 auto; line-height: 1.35;
    }
    .pos-receipt .r-head { text-align: center; border-bottom: 2px dashed #999; padding-bottom: 10px; }
    .pos-receipt .r-head h3 { margin: 0 0 2px; font-size: 22px; font-weight: 800; letter-spacing: .5px; }
    .pos-receipt .r-head .r-sub { font-size: 11px; color: #555; }
    .pos-receipt .r-meta { display: flex; justify-content: space-between; font-size: 12px; padding: 5px 0; border-bottom: 1px dotted #ddd; }
    .pos-receipt .r-meta b { color: #111; }
    .pos-receipt table { width: 100%; border-collapse: collapse; font-size: 12px; margin-top: 8px; }
    .pos-receipt table th { border-bottom: 2px solid #999; padding: 5px 2px; text-align: left; font-size: 11px; text-transform: uppercase; letter-spacing: .4px; }
    .pos-receipt table td { padding: 4px 2px; border-bottom: 1px dotted #ccc; }
    .pos-receipt .r-total { margin-top: 10px; font-size: 13px; }
    .pos-receipt .r-total .row { display: flex; justify-content: space-between; padding: 3px 0; }
    .pos-receipt .r-total .grand { font-weight: 800; font-size: 16px; border-top: 2px solid #1a1a1a; margin-top: 6px; padding-top: 6px; }
    .pos-receipt .r-status { display: inline-block; padding: 2px 10px; border-radius: 4px; font-size: 11px; font-weight: 700; }
    .pos-receipt .r-status.paid { background: #e6f6ec; color: #15803d; }
    .pos-receipt .r-status.pending { background: #fff4e0; color: #b45309; }
    .pos-receipt .r-foot { text-align: center; margin-top: 16px; border-top: 1px dashed #999; padding-top: 10px; font-size: 11px; color: #555; }
</style>
@endpush

@section('content')
<div class="nxl-content" id="billPage"
     data-tax="{{ App\Models\Booking::TAX_PERCENTAGE }}"
     @if($offer)
         data-discount-type="{{ $offer->discount_type }}"
         data-discount-value="{{ $offer->discount_value }}"
         data-min-bill="{{ $offer->min_bill_amount }}"
         data-max-discount="{{ $offer->max_discount_amount }}"
         data-cover-charge="{{ $offer->cover_charge ?? 0 }}"
         data-discount-label="{{ $bill['discount_label'] }}"
     @endif>
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">POS Billing</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('restaurant.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('restaurant.bookings.index') }}">Table Bookings</a></li>
                <li class="breadcrumb-item">Bill</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto d-flex align-items-center gap-2">
            @if($booking->isBillPaid())
                <span class="badge bg-success px-3 py-2 rounded-pill fw-semibold">
                    <i class="feather-check-circle me-1"></i> Bill Paid
                </span>
            @else
                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-semibold">
                    <i class="feather-clock me-1"></i> Bill Pending
                </span>
            @endif
            @if($booking->bill_items && !empty($booking->bill_items))
                <button type="submit" form="deleteBillForm" class="btn btn-outline-danger fw-semibold no-print" id="deleteBillBtn" onclick="return confirm('Delete this bill and all its items? You can create a new bill for this booking later.');">
                    <i class="feather-trash-2 me-1"></i> Delete Bill
                </button>
            @endif
            <button type="button" class="btn btn-danger fw-semibold no-print" id="printBillBtn">
                <i class="feather-printer me-1"></i> Print Bill
            </button>
            <a href="{{ route('restaurant.bookings.index') }}" class="btn btn-light border text-secondary fw-semibold no-print">
                <i class="feather-arrow-left me-1"></i> Back to Bookings
            </a>
        </div>
    </div>
    <!-- [ page-header ] end -->

    <!-- [ Main Content ] start -->
    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="pos-wrap p-3 p-lg-4 mb-4">
            <!-- Booking header -->
            <div class="pos-header-card p-4 mb-4 position-relative">
                <div class="row g-3 position-relative" style="z-index:1;">
                    <div class="col-md-4 d-flex align-items-center gap-3">
                        <div class="pos-avatar"><i class="feather-users"></i></div>
                        <div>
                            <div class="fs-14 fw-bold">{{ $booking->customer_name }}</div>
                            <div class="fs-12 opacity-75">{{ $booking->phone }}</div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="row g-2">
                            <div class="col-6 col-lg-3">
                                <div class="pos-info-chip">
                                    <div class="label">Date</div>
                                    <div class="value">{{ $booking->book_date->format('d M Y') }}</div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-3">
                                <div class="pos-info-chip">
                                    <div class="label">Time</div>
                                    <div class="value">{{ $booking->book_time->format('h:i A') }}</div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-3">
                                <div class="pos-info-chip">
                                    <div class="label">Guests</div>
                                    <div class="value">{{ $booking->guests }}</div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-3">
                                <div class="pos-info-chip">
                                    <div class="label">Booking</div>
                                    <div class="value text-uppercase">#{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <form action="{{ route('restaurant.bookings.bill.store', $booking->id) }}" method="POST" id="billForm">
                @csrf

                <div class="row g-4">
                    <!-- ======= LEFT: MENU ======= -->
                    <div class="col-xl-8">
                        <div class="pos-panel bg-white">
                            <div class="p-3 border-bottom">
                                <div class="row g-2 align-items-center">
                                    <div class="col-lg-5">
                                        <div class="pos-search d-flex align-items-center">
                                            <i class="feather-search ms-3 text-muted"></i>
                                            <input type="text" id="posSearch" class="form-control" placeholder="Search menu...">
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <div class="d-flex gap-2 flex-nowrap overflow-auto py-1" id="posCats">
                                            <button type="button" class="pos-cat-pill active" data-cat="all">All</button>
                                            @foreach($categories as $cat)
                                                <button type="button" class="pos-cat-pill" data-cat="{{ $cat->id }}">{{ $cat->name }}</button>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="p-3">
                                @if($foods->isEmpty())
                                    <div class="text-center py-5 text-muted">
                                        <i class="feather-inbox display-5 d-block mb-3"></i>
                                        No food items in your menu yet. Add foods from the Menu section first.
                                    </div>
                                @else
                                    <div class="row g-3" id="posFoodGrid">
                                        @foreach($foods as $food)
                                            @php
                                                $firstVariant = $food->variants->first();
                                                if ($firstVariant) {
                                                    $price = $firstVariant->sale_price ?: $firstVariant->price;
                                                    $strike = $firstVariant->sale_price ? $firstVariant->price : null;
                                                } else {
                                                    $price = $food->discount_price ?: $food->base_price;
                                                    $strike = $food->discount_price ? $food->base_price : null;
                                                }
                                                $foodImg = $food->image ? asset($food->image) : asset('front/assets/images/menu/13.jpg');
                                                $vegIcon = $food->is_veg ? 'front/assets/images/svg/veg.svg' : 'front/assets/images/svg/nonveg.svg';
                                                $variantsJson = json_encode(
                                                    $food->variants->map(fn ($v) => [
                                                        'id' => $v->id,
                                                        'label' => $v->variant_name,
                                                        'price' => (float) ($v->sale_price ?: $v->price),
                                                    ])->values()->all(),
                                                    JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP
                                                );
                                            @endphp
                                            <div class="col-6 col-md-4 col-xxl-3 pos-food-col" data-cat="{{ $food->food_category_id }}">
                                                <div class="pos-food-card" data-food-id="{{ $food->id }}"
                                                     data-name="{{ $food->name }}"
                                                     data-price="{{ $price }}"
                                                     data-veg="{{ $food->is_veg ? 1 : 0 }}"
                                                     data-img="{{ $foodImg }}"
                                                     data-category="{{ $food->category->name ?? '' }}"
                                                     data-variants="{!! $variantsJson !!}">
                                                    <div class="food-img-wrap">
                                                        <img src="{{ $foodImg }}" alt="{{ $food->name }}" loading="lazy">
                                                        <span class="pos-veg-badge">
                                                            <img src="{{ asset($vegIcon) }}" alt="veg" width="16">
                                                        </span>
                                                        @if($food->is_spicy)
                                                            <span class="pos-spicy-badge"><i class="feather-zap me-1"></i>Spicy</span>
                                                        @endif
                                                    </div>
                                                    <div class="food-body">
                                                        <div class="d-flex align-items-start justify-content-between gap-2">
                                                            <div class="min-w-0">
                                                                <div class="food-name" title="{{ $food->name }}">{{ $food->name }}</div>
                                                                <div class="food-cat mt-1">{{ $food->category->name ?? 'Menu Item' }}</div>
                                                            </div>
                                                            <div class="text-end">
                                                                <div class="food-price">{{ $currencySymbol }}{{ number_format((float) $price, 2) }}</div>
                                                                @if($strike)
                                                                    <div class="food-strike">{{ $currencySymbol }}{{ number_format((float) $strike, 2) }}</div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- ======= RIGHT: CART ======= -->
                    <div class="col-xl-4">
                        <div class="pos-cart">
                            <div class="pos-panel">
                                <div class="cart-head d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="feather-shopping-cart"></i>
                                        <h6 class="mb-0 fw-bold text-white">Current Order</h6>
                                    </div>
                                    <span class="badge rounded-pill text-white fw-bold" id="cartCount" style="background: rgba(255,255,255,.18);">0</span>
                                </div>

                                <div class="pos-cart-body" id="posCartBody">
                                    <div class="pos-cart-empty" id="cartEmpty">
                                        <i class="feather-shopping-bag d-block mb-2"></i>
                                        <div class="fs-14 fw-semibold">No items yet</div>
                                        <div class="fs-12 mt-1">Tap a dish on the left to add it</div>
                                    </div>
                                </div>

                                <!-- Custom item adder -->
                                <div class="pos-custom-add">
                                    <div class="fs-12 fw-bold text-uppercase text-muted mb-2" style="letter-spacing:.5px;">
                                        <i class="feather-plus me-1"></i> Custom Item
                                    </div>
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control" id="customName" placeholder="Item name">
                                        <input type="number" step="0.01" min="0" class="form-control" id="customPrice" placeholder="Price" style="max-width:100px;">
                                        <button type="button" class="btn btn-dark" id="customAddBtn"><i class="feather-plus"></i></button>
                                    </div>
                                </div>

                                <div class="pos-summary">
                                    @if($offer)
                                        <div class="pos-offer-banner mb-3">
                                            <i class="feather-tag me-1"></i> {{ $offer->title }} — {{ $bill['discount_label'] }}
                                            @if($offer->min_bill_amount)
                                                <span class="d-block fs-12 opacity-75 mt-1">Min bill {{ $currencySymbol }}{{ number_format($offer->min_bill_amount, 2) }}</span>
                                            @endif
                                        </div>
                                    @endif

                                    <div class="row-line">
                                        <span>Subtotal</span>
                                        <b>{{ $currencySymbol }}<span id="sumSubtotal">0.00</span></b>
                                    </div>
                                    <div class="row-line text-danger" id="discountRow" style="display:none;">
                                        <span>Offer (<span id="sumDiscountLabel">—</span>)</span>
                                        <b>- {{ $currencySymbol }}<span id="sumDiscount">0.00</span></b>
                                    </div>
                                    <div class="row-line" id="coverChargeRow" style="display:none;">
                                        <span>Cover Charge</span>
                                        <b>{{ $currencySymbol }}<span id="sumCoverCharge">0.00</span></b>
                                    </div>
                                    <div class="row-line">
                                        <span>Tax (<span id="sumTaxPct">{{ App\Models\Booking::TAX_PERCENTAGE }}</span>%)</span>
                                        <b>{{ $currencySymbol }}<span id="sumTax">0.00</span></b>
                                    </div>
                                    <div class="row-line total">
                                        <span class="fw-bold">Payable</span>
                                        <b>{{ $currencySymbol }}<span id="sumPayable">0.00</span></b>
                                    </div>

                                    <div class="d-grid gap-2 mt-3">
                                        <button type="submit" class="btn pos-save-btn w-100">
                                            <i class="feather-check-circle me-2"></i> Save Bill
                                        </button>

                                        @if($booking->isBillPaid())
                                            <div class="text-center">
                                                <span class="badge bg-success px-3 py-2 rounded-pill fw-semibold fs-12">
                                                    <i class="feather-check-circle me-1"></i>
                                                    Paid {{ $booking->bill_paid_at ? 'on ' . $booking->bill_paid_at->format('d M Y h:i A') : '' }}
                                                </span>
                                            </div>
                                        @elseif($booking->bill_items && !empty($booking->bill_items))
                                            <button type="submit" form="markPaidForm" class="btn btn-outline-success w-100 fw-semibold no-print" id="markPaidBtn">
                                                <i class="feather-check me-2"></i> Mark as Paid
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Standalone action forms (used via the form="..." attribute to avoid nested forms) -->
        <form id="markPaidForm" action="{{ route('restaurant.bookings.bill.mark-paid', $booking->id) }}" method="POST" style="display:none;">
            @csrf
        </form>
        <form id="deleteBillForm" action="{{ route('restaurant.bookings.bill.delete', $booking->id) }}" method="POST" style="display:none;">
            @csrf
        </form>
    </div>
    <!-- [ Main Content ] end -->
</div>

<!-- Print Receipt (shown only when printing) -->
<div id="printReceipt">
    <div class="pos-receipt">
        <div class="r-head">
            <h3>{{ $restaurant->restaurant_name ?? 'Restaurant' }}</h3>
            <div class="r-sub">{{ $restaurant->address ?? '' }}</div>
            <div class="r-sub">{{ $restaurant->mobile ? 'Ph: ' . $restaurant->mobile : '' }}</div>
        </div>
        <div class="r-meta">
            <span>Bill No: <b id="rBillNo"></b></span>
            <span id="rDate"></span>
        </div>
        <div class="r-meta">
            <span>Customer: <b id="rCustomer"></b></span>
            <span id="rGuests"></span>
        </div>
        <div class="r-meta">
            <span>Phone: <b id="rPhone"></b></span>
            <span>Status: <span class="r-status {{ $booking->isBillPaid() ? 'paid' : 'pending' }}" id="rStatus"></span></span>
        </div>
        <table>
            <thead>
                <tr>
                    <th style="width:42%">Item</th>
                    <th class="text-center">Qty</th>
                    <th class="text-center">Price</th>
                    <th class="text-end">Amount</th>
                </tr>
            </thead>
            <tbody id="rItems"></tbody>
        </table>
        <div class="r-total">
            <div class="row"><span>Subtotal</span><span id="rSubtotal"></span></div>
            <div class="row" id="rDiscountRow"><span>Offer Discount</span><span id="rDiscount"></span></div>
            <div class="row" id="rCoverRow"><span>Cover Charge</span><span id="rCover"></span></div>
            <div class="row"><span>Tax ({{ App\Models\Booking::TAX_PERCENTAGE }}%)</span><span id="rTax"></span></div>
            <div class="row grand"><span>Total Payable</span><span id="rPayable"></span></div>
        </div>
        <div class="r-foot">
            <b>Thank you for dining with us!</b><br>
            Please visit again.
        </div>
    </div>
</div>

<!-- Variant Modal -->
<div class="modal fade" id="variantModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0" style="border-radius: 20px; overflow: hidden;">
            <div style="height: 6px; background: linear-gradient(90deg, #ff4d4f, #ff9f43);"></div>
            <div class="modal-body p-4">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <img src="" id="variantFoodImg" alt="" style="width:64px;height:64px;border-radius:14px;object-fit:cover;">
                    <div>
                        <h5 class="fw-bold mb-1" id="variantFoodName">—</h5>
                        <div class="fs-13 text-muted" id="variantFoodCat"></div>
                        <div class="fs-12 fw-semibold" id="variantFoodVeg"></div>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <label class="form-label fw-bold fs-13">Choose Size / Variant</label>
                <select id="variantSelect" class="form-select form-select-lg mb-3"></select>

                <div class="d-flex align-items-center justify-content-between">
                    <div class="qty-stepper">
                        <button type="button" class="minus" id="variantMinus"><i class="feather-minus"></i></button>
                        <span class="qty-val" id="variantQty">1</span>
                        <button type="button" class="plus" id="variantPlus"><i class="feather-plus"></i></button>
                    </div>
                    <h4 class="mb-0 fw-bold text-success" id="variantTotal">{{ $currencySymbol }}0.00</h4>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0 px-4 pb-4">
                <button type="button" class="btn pos-save-btn w-100" id="variantAddBtn">
                    <i class="feather-plus-circle me-2"></i> Add to Order
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(function () {
        const $page = $('#billPage');
        const taxPct = parseFloat($page.data('tax')) || 0;
        const dType = $page.data('discount-type');
        const dVal = parseFloat($page.data('discount-value')) || 0;
        const minBill = parseFloat($page.data('min-bill')) || 0;
        const maxDisc = parseFloat($page.data('max-discount')) || 0;
        const dLabel = $page.data('discount-label') || '';
        const coverCharge = parseFloat($page.data('cover-charge')) || 0;
        const CURRENCY = '{{ $currencySymbol }}';
        const vegIcon = '{{ asset('front/assets/images/svg/veg.svg') }}';
        const nonvegIcon = '{{ asset('front/assets/images/svg/nonveg.svg') }}';

        let cart = @json($initialCart);
        let totals = { subtotal: 0, discount: 0, coverCharge: 0, tax: 0, payable: 0 };

        function money(n) {
            return CURRENCY + Number(n).toFixed(2);
        }

        function cartKey(item) {
            return (item.food_id || 'custom') + '|' + (item.variant || '');
        }

        function addToCart(item, qty) {
            const key = cartKey(item);
            const existing = cart.find(i => cartKey(i) === key);
            if (existing) {
                existing.qty += qty;
            } else {
                cart.push({ ...item, qty });
            }
            renderCart();
        }

        function removeFromCart(key) {
            cart = cart.filter(i => cartKey(i) !== key);
            renderCart();
        }

        function renderCart() {
            const $body = $('#posCartBody');
            const $count = $('#cartCount');
            const totalQty = cart.reduce((s, i) => s + i.qty, 0);
            $count.text(totalQty);

            if (cart.length === 0) {
                $body.html('<div class="pos-cart-empty"><i class="feather-shopping-bag d-block mb-2"></i><div class="fs-14 fw-semibold">No items yet</div><div class="fs-12 mt-1">Tap a dish on the left to add it</div></div>');
            } else {
                let html = '';
                cart.forEach(item => {
                    const key = cartKey(item);
                    const veg = item.veg ? vegIcon : nonvegIcon;
                    html += '<div class="pos-cart-item" data-key="' + key + '">' +
                        '<img src="' + veg + '" class="ci-veg" alt="">' +
                        '<div class="ci-info">' +
                            '<div class="ci-name">' + item.name + '</div>' +
                            '<div class="ci-unit">' + money(item.price) + ' × ' + item.qty + '</div>' +
                        '</div>' +
                        '<div class="qty-stepper">' +
                            '<button type="button" class="minus" data-action="dec"><i class="feather-minus"></i></button>' +
                            '<span class="qty-val">' + item.qty + '</span>' +
                            '<button type="button" class="plus" data-action="inc"><i class="feather-plus"></i></button>' +
                        '</div>' +
                        '<div class="ci-total">' + money(item.price * item.qty) + '</div>' +
                        '<button type="button" class="ci-remove" data-action="remove"><i class="feather-x"></i></button>' +
                    '</div>';
                });
                $body.html(html);
            }
            recalc();
        }

        function recalc() {
            let subtotal = cart.reduce((s, i) => s + (i.price * i.qty), 0);

            let discount = 0;
            const eligible = !minBill || subtotal >= minBill;
            if (dType && eligible && subtotal > 0) {
                if (dType === 'percentage') {
                    discount = subtotal * (dVal / 100);
                    if (maxDisc > 0) discount = Math.min(discount, maxDisc);
                } else {
                    discount = dVal;
                }
                discount = Math.min(discount, subtotal);
            }

            const discounted = subtotal - discount;
            const taxable = discounted + coverCharge;
            const tax = taxable * (taxPct / 100);
            const payable = taxable + tax;

            totals = { subtotal, discount, coverCharge, tax, payable };

            $('#sumSubtotal').text(subtotal.toFixed(2));
            $('#sumDiscount').text(discount.toFixed(2));
            $('#sumCoverCharge').text(coverCharge.toFixed(2));
            $('#sumTax').text(tax.toFixed(2));
            $('#sumPayable').text(payable.toFixed(2));

            if (discount > 0) {
                $('#discountRow').show();
                $('#sumDiscountLabel').text(dLabel);
            } else {
                $('#discountRow').hide();
            }

            if (coverCharge > 0) {
                $('#coverChargeRow').show();
            } else {
                $('#coverChargeRow').hide();
            }
        }

        // ---- Menu interaction ----
        let pendingFood = null;

        function foodClick($card) {
            pendingFood = {
                food_id: $card.data('food-id'),
                name: $card.data('name'),
                price: parseFloat($card.data('price')),
                veg: $card.data('veg'),
                img: $card.data('img'),
                category: $card.data('category') || '',
            };

            let variants = [];
            try { variants = JSON.parse($card.attr('data-variants')) || []; } catch (e) { variants = []; }

            if (variants.length > 1) {
                openVariantModal(pendingFood, variants);
            } else {
                addToCart(pendingFood, 1);
            }
        }

        function openVariantModal(food, variants) {
            $('#variantFoodImg').attr('src', food.img);
            $('#variantFoodName').text(food.name);
            $('#variantFoodCat').text(food.category);
            $('#variantFoodVeg').html(food.veg
                ? '<span class="text-success">Veg</span>'
                : '<span class="text-danger">Non-Veg</span>');

            const $sel = $('#variantSelect');
            $sel.empty();
            variants.forEach(v => {
                $sel.append('<option value="' + v.price + '" data-label="' + v.label + '">' + v.label + ' — ' + money(v.price) + '</option>');
            });
            $('#variantQty').text(1);
            updateVariantTotal();
            $('#variantModal').modal('show');
        }

        function updateVariantTotal() {
            const price = parseFloat($('#variantSelect').val()) || 0;
            const qty = parseInt($('#variantQty').text()) || 1;
            $('#variantTotal').text(money(price * qty));
        }

        $('#variantSelect').on('change', updateVariantTotal);
        $('#variantPlus').on('click', function () {
            $('#variantQty').text((parseInt($('#variantQty').text()) || 0) + 1);
            updateVariantTotal();
        });
        $('#variantMinus').on('click', function () {
            const n = (parseInt($('#variantQty').text()) || 1) - 1;
            if (n < 1) return;
            $('#variantQty').text(n);
            updateVariantTotal();
        });
        $('#variantAddBtn').on('click', function () {
            const price = parseFloat($('#variantSelect').val()) || pendingFood.price;
            const label = $('#variantSelect option:selected').data('label') || '';
            const qty = parseInt($('#variantQty').text()) || 1;
            const item = {
                ...pendingFood,
                price,
                variant: label,
                name: pendingFood.name + (label ? ' (' + label + ')' : ''),
            };
            addToCart(item, qty);
            $('#variantModal').modal('hide');
        });

        // Grid click
        $('#posFoodGrid').on('click', '.pos-food-card', function (e) {
            if ($(e.target).closest('button').length) return;
            foodClick($(this));
        });

        // Cart actions
        $('#posCartBody').on('click', 'button', function () {
            const $item = $(this).closest('.pos-cart-item');
            const key = $item.data('key');
            const action = $(this).data('action');
            const idx = cart.findIndex(i => cartKey(i) === key);

            if (action === 'inc') {
                cart[idx].qty += 1;
            } else if (action === 'dec') {
                cart[idx].qty -= 1;
                if (cart[idx].qty <= 0) { cart = cart.filter(i => cartKey(i) !== key); }
            } else if (action === 'remove') {
                cart = cart.filter(i => cartKey(i) !== key);
            }
            renderCart();
        });

        // Search + category filter
        function applyFilters() {
            const q = ($('#posSearch').val() || '').toLowerCase();
            const cat = $('.pos-cat-pill.active').data('cat');

            $('#posFoodGrid .pos-food-col').each(function () {
                const $col = $(this);
                const $card = $col.find('.pos-food-card');
                const name = ($card.data('name') || '').toLowerCase();
                const catId = String($col.data('cat'));
                let show = true;
                if (cat !== 'all' && catId !== String(cat)) show = false;
                if (q && !name.includes(q)) show = false;
                $col.toggle(show);
            });
        }
        $('#posSearch').on('input', applyFilters);
        $('#posCats').on('click', '.pos-cat-pill', function () {
            $('.pos-cat-pill').removeClass('active');
            $(this).addClass('active');
            applyFilters();
        });

        // Custom item
        $('#customAddBtn').on('click', function () {
            const name = $('#customName').val().trim();
            const price = parseFloat($('#customPrice').val());
            if (!name || isNaN(price) || price < 0) {
                alert('Please enter a valid item name and price.');
                return;
            }
            addToCart({ food_id: null, name, price, veg: 0 }, 1);
            $('#customName').val('');
            $('#customPrice').val('');
        });

        // Submit: build hidden inputs
        $('#billForm').on('submit', function () {
            $('input[name^="bill_items"]').remove();
            cart.forEach((item, i) => {
                $('<input>').attr({ type: 'hidden', name: 'bill_items[' + i + '][name]' }).val(item.name).appendTo(this);
                $('<input>').attr({ type: 'hidden', name: 'bill_items[' + i + '][price]' }).val(item.price).appendTo(this);
                $('<input>').attr({ type: 'hidden', name: 'bill_items[' + i + '][qty]' }).val(item.qty).appendTo(this);
            });
        });

        // ---- Print bill ----
        function buildReceipt() {
            $('#rBillNo').text('#' + String({{ $booking->id }}).padStart(4, '0'));
            const now = new Date();
            $('#rDate').text(now.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) + ' ' +
                now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }));
            $('#rCustomer').text(@json($booking->customer_name));
            $('#rGuests').text('Guests: ' + {{ $booking->guests }});
            $('#rPhone').text(@json($booking->phone));
            $('#rStatus').text('{{ $booking->isBillPaid() ? 'PAID' : 'PENDING' }}');

            let rows = '';
            cart.forEach(item => {
                rows += '<tr>' +
                    '<td>' + item.name + '</td>' +
                    '<td class="text-center">' + item.qty + '</td>' +
                    '<td class="text-center">' + money(item.price) + '</td>' +
                    '<td class="text-end">' + money(item.price * item.qty) + '</td>' +
                '</tr>';
            });
            $('#rItems').html(rows);

            $('#rSubtotal').text(money(totals.subtotal));
            if (totals.discount > 0) {
                $('#rDiscountRow').show();
                $('#rDiscount').text('- ' + money(totals.discount));
            } else {
                $('#rDiscountRow').hide();
            }
            if (totals.coverCharge > 0) {
                $('#rCoverRow').show();
                $('#rCover').text(money(totals.coverCharge));
            } else {
                $('#rCoverRow').hide();
            }
            $('#rTax').text(money(totals.tax));
            $('#rPayable').text(money(totals.payable));
        }

        $('#printBillBtn').on('click', function () {
            buildReceipt();
            window.print();
        });

        renderCart();
    });
</script>
@endpush
@endsection
