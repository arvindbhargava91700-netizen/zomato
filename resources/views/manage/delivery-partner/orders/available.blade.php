@extends('layouts.delivery-partner.main')

@section('title', 'New Deliveries - Zomato Delivery')

@section('content')
<div class="nxl-content">
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">New Deliveries</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('delivery-partner.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">New Deliveries</li>
            </ul>
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

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card stretch stretch-full">
            <div class="card-header border-bottom py-3">
                <h5 class="card-title mb-0 fw-bold">Incoming Delivery Requests</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="customerList">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Order</th>
                                <th>Restaurant</th>
                                <th>Customer</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th>Expires In</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($requests as $request)
                                <tr>
                                    <td class="ps-4 fw-bold text-dark">#{{ $request->order->id }}</td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $request->order->restaurant?->restaurant_name ?? '-' }}</div>
                                        <span class="text-muted fs-12 d-block">{{ Str::limit($request->order->restaurant?->address, 30) }}</span>
                                    </td>
                                    <td>{{ $request->order->user?->name ?? '-' }}</td>
                                    <td>
                                        @foreach ($request->order->items as $item)
                                            <div class="fs-12">{{ $item->name }} <span class="text-muted">× {{ $item->qty }}</span></div>
                                        @endforeach
                                    </td>
                                    <td class="fw-bold text-dark">{{ $currencySymbol }}{{ number_format($request->order->total, 2) }}</td>
                                    <td class="text-muted fs-12">
                                        @if ($request->expires_at)
                                            <span class="countdown" data-expires="{{ $request->expires_at->timestamp }}">
                                                {{ $request->expires_at->diffForHumans(now()) }}
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <form method="POST" action="{{ route('delivery-partner.requests.accept', $request->id) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success text-white fw-semibold"
                                                style="background-color:#2ba842;border:none;">
                                                Accept
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('delivery-partner.requests.reject', $request->id) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-light border text-danger fw-semibold">
                                                Reject
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($requests->hasPages())
                <div class="card-footer bg-white py-3 border-top">
                    <div class="d-flex justify-content-end">
                        {{ $requests->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>

@push('scripts')
    <script>
        (function () {
            var els = document.querySelectorAll('.countdown');
            function tick() {
                els.forEach(function (el) {
                    var exp = parseInt(el.getAttribute('data-expires'), 10) * 1000;
                    var diff = exp - Date.now();
                    if (diff <= 0) { el.textContent = 'Expired'; return; }
                    var m = Math.floor(diff / 60000);
                    var s = Math.floor((diff % 60000) / 1000);
                    el.textContent = m + 'm ' + s + 's';
                });
            }
            tick();
            setInterval(tick, 1000);
        })();
    </script>
@endpush
@endsection