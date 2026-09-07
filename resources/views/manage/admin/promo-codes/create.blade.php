@extends('layouts.admin.main')

@section('title', 'Create Campaign - Admin Dashboard')

@section('content')
<div class="nxl-content">
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Create Promo Campaign</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.promo-codes.index') }}">Promo Codes</a></li>
                <li class="breadcrumb-item">Create</li>
            </ul>
        </div>
    </div>

    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
            <div class="card-header border-bottom py-3">
                <h5 class="card-title mb-0 fw-bold">Campaign Details</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.promo-codes.store') }}" method="POST" class="row g-3">
                    @csrf

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Campaign Name <span class="text-danger">*</span></label>
                        <input type="text" name="campaign_name" class="form-control" placeholder="e.g. Welcome Offer" value="{{ old('campaign_name') }}" required>
                        <small class="text-muted">Codes will be prefixed with the first word, e.g. <code>WELCOME-XXXX</code></small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Code Type</label>
                        <select name="code_type" class="form-select">
                            <option value="">Select Type</option>
                            <option value="welcome" {{ old('code_type') == 'welcome' ? 'selected' : '' }}>Welcome</option>
                            <option value="referral" {{ old('code_type') == 'referral' ? 'selected' : '' }}>Referral</option>
                            <option value="festival" {{ old('code_type') == 'festival' ? 'selected' : '' }}>Festival</option>
                            <option value="custom" {{ old('code_type') == 'custom' ? 'selected' : '' }}>Custom</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Discount Type <span class="text-danger">*</span></label>
                        <select name="discount_type" id="discount_type" class="form-select" required>
                            <option value="percentage" {{ old('discount_type', 'percentage') == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                            <option value="flat" {{ old('discount_type') == 'flat' ? 'selected' : '' }}>Flat Amount</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">
                            Discount Value <span class="text-danger">*</span>
                            <span id="discount_suffix">(%)</span>
                        </label>
                        <input type="number" step="0.01" name="discount_value" class="form-control" placeholder="50" value="{{ old('discount_value') }}" required>
                    </div>

                    <div class="col-md-4" id="max_discount_wrap">
                        <label class="form-label fw-semibold">Maximum Discount ({{ $currencySymbol }})</label>
                        <input type="number" step="0.01" name="maximum_discount_amount" class="form-control" placeholder="100" value="{{ old('maximum_discount_amount') }}">
                        <small class="text-muted">Leave blank for no cap (percentage only).</small>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Minimum Order Amount ({{ $currencySymbol }})</label>
                        <input type="number" step="0.01" name="minimum_order_amount" class="form-control" placeholder="300" value="{{ old('minimum_order_amount', 0) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Per User Limit</label>
                        <input type="number" name="per_user_limit" class="form-control" placeholder="1" value="{{ old('per_user_limit', 1) }}" min="1">
                        <small class="text-muted">Max times one user can use this campaign.</small>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Total Codes <span class="text-danger">*</span></label>
                        <input type="number" name="total_codes" class="form-control" placeholder="1000" value="{{ old('total_codes') }}" min="1" max="5000" required>
                        <small class="text-muted">Max 5000 per campaign.</small>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Valid From</label>
                        <input type="date" name="valid_from" class="form-control" value="{{ old('valid_from') }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Valid Until</label>
                        <input type="date" name="valid_until" class="form-control" value="{{ old('valid_until') }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select">
                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="col-12 d-flex gap-2 mt-2">
                        <button type="submit" class="btn btn-danger text-white fw-semibold px-4" style="background-color: #cb202d; border: none;">
                            <i class="feather-plus me-1"></i> Create Campaign & Generate Codes
                        </button>
                        <a href="{{ route('admin.promo-codes.index') }}" class="btn btn-light border text-secondary fw-semibold">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var type = document.getElementById('discount_type');
        var suffix = document.getElementById('discount_suffix');
        var maxWrap = document.getElementById('max_discount_wrap');
        function toggle() {
            if (type.value === 'percentage') {
                suffix.textContent = '(%)';
                maxWrap.style.display = '';
            } else {
                suffix.textContent = '({{ $currencySymbol }})';
                maxWrap.style.display = 'none';
            }
        }
        type.addEventListener('change', toggle);
        toggle();
    });
</script>
@endpush
@endsection
