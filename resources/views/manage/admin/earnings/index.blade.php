@extends('layouts.admin.main')

@section('title', getPageTitle('Earnings'))

@section('styles')
<link rel="stylesheet" href="{{ asset('admin/assets/vendors/css/dataTables.bs5.min.css') }}" />
@endsection

@php
    $titles = [
        'commission' => 'Restaurant Commission',
        'tax' => 'Tax / GST',
        'platform' => 'Platform Share',
    ];
    $icons = [
        'commission' => 'feather-percent',
        'tax' => 'feather-file-text',
        'platform' => 'feather-share-2',
    ];
    $colors = [
        'commission' => 'primary',
        'tax' => 'warning',
        'platform' => 'success',
    ];
    $columns = [
        'commission' => ['Food Total', 'Commission %'],
        'tax' => ['Food Total', 'GST %'],
        'platform' => ['Delivery Charge', 'Platform Share %'],
    ];
@endphp

@section('content')
<div class="nxl-content">
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Earnings</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Earnings</li>
                <li class="breadcrumb-item">{{ $titles[$type] }}</li>
            </ul>
        </div>
    </div>
    <!-- [ page-header ] end -->

    <!-- [ Main Content ] start -->
    <div class="main-content">
        <!-- Grand Total -->
        <div class="card stretch stretch-full border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body p-3 d-flex align-items-center gap-3"
                style="background: linear-gradient(135deg, #cb202d 0%, #f97316 100%); border-radius: 16px;">
                <div class="rounded-circle bg-white bg-opacity-25 text-white d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="feather-trending-up"></i>
                </div>
                <div>
                    <div class="fs-13 fw-semibold text-white-50" style="letter-spacing: .5px;">TOTAL EARNINGS</div>
                    <div class="fs-3 fw-bold text-white">{{ $currencySymbol }}{{ number_format($grandTotal, 2) }}</div>
                </div>
                <span class="ms-auto fs-12 text-white-50">From approved settlements</span>
            </div>
        </div>

        <!-- Earning Type Summary -->
        <div class="row g-3 mb-4">
            @foreach(['commission', 'tax', 'platform'] as $t)
                <div class="col-md-4">
                    <a href="{{ route('admin.earnings.' . $t) }}" class="text-decoration-none">
                        <div class="card stretch stretch-full border-0 shadow-sm rounded-3 {{ $t === $type ? 'border-start border-4' : '' }}" style="{{ $t === $type ? 'border-start-color: var(--bs-' . $colors[$t] . ') !important;' : '' }}">
                            <div class="card-body p-3 d-flex align-items-center gap-3">
                                <div class="rounded-circle bg-soft-{{ $colors[$t] }} text-{{ $colors[$t] }} d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                                    <i class="{{ $icons[$t] }}"></i>
                                </div>
                                <div>
                                    <div class="fs-14 fw-bold text-dark">{{ $currencySymbol }}{{ number_format($summary[$t], 2) }}</div>
                                    <div class="fs-12 text-muted">{{ $titles[$t] }}</div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        <!-- Earnings Table -->
        <div class="card stretch stretch-full">
            <div class="card-header border-bottom py-3 d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0 fw-bold">{{ $titles[$type] }} Listing</h5>
                <span class="fs-12 text-muted">From the incomes table</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                   <table class="table table-hover" id="customerList">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Order</th>
                                <th>Restaurant</th>
                                <th>Paid On</th>
                                <th>{{ $columns[$type][0] }}</th>
                                <th>{{ $columns[$type][1] }}</th>
                                <th class="text-end pe-4">Earning Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rows as $row)
                                <tr>
                                    <td class="ps-4 fw-bold text-dark">#{{ $row['order_id'] }}</td>
                                    <td class="fw-semibold text-dark">{{ $row['restaurant'] }}</td>
                                    <td class="text-muted fs-12">{{ $row['paid_at'] }}</td>
                                    <td>{{ $currencySymbol }}{{ number_format($row['base'], 2) }}</td>
                                    <td>{{ $row['percentage'] }}%</td>
                                    <td class="text-end pe-4 fw-bold {{ $type === 'platform' ? 'text-success' : 'text-dark' }}">{{ $currencySymbol }}{{ number_format($row['amount'], 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="feather-inbox display-6 d-block mb-2 text-secondary"></i>
                                        No earnings found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if(count($rows) > 0)
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="5" class="ps-4 fw-bold text-dark">Total {{ $titles[$type] }}</td>
                                    <td class="text-end pe-4 fw-bold text-success">{{ $currencySymbol }}{{ number_format($total, 2) }}</td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>
@endsection