@extends('layouts.restaurant.main')

@section('title', 'Quick Batch Tables Generator - Restaurant Partner')

@section('content')
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Quick Batch Tables Generator</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('restaurant.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('restaurant.dining-setup.index') }}">Dining & Tables</a></li>
                <li class="breadcrumb-item active">Batch Generator</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto d-flex align-items-center gap-2">
            <a href="{{ route('restaurant.dining-setup.index') }}" class="btn btn-light border text-secondary fw-semibold">
                <i class="feather-arrow-left me-1"></i> Back to Tables
            </a>
        </div>
    </div>
    <!-- [ page-header ] end -->

    <!-- [ Main Content ] start -->
    <div class="main-content">
        @if(isset($errors) && $errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
                <strong>Please correct the errors below:</strong>
                <ul class="mb-0 mt-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-lg-8 col-12">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                    <div class="card-header p-4 border-bottom bg-white">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-lg bg-soft-primary text-primary rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; font-size: 22px;">
                                <i class="feather-layers"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1">Quick Batch Table Generator</h5>
                                <p class="text-muted small mb-0">Generate a series of tables (e.g. T1 to T6) with uniform seating capacity in one click.</p>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <form action="{{ route('restaurant.dining-setup.tables.batch') }}" method="POST">
                            @csrf

                            <div class="row g-4">
                                <!-- Prefix & Start Number -->
                                <div class="col-md-6 col-12">
                                    <label class="form-label fw-bold">Table Prefix <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="feather-tag text-muted"></i></span>
                                        <input type="text"
                                               name="prefix"
                                               class="form-control @error('prefix') is-invalid @enderror"
                                               value="{{ old('prefix', 'T') }}"
                                               placeholder="E.g. T, Table-, Booth-"
                                               required
                                               autofocus>
                                    </div>
                                    <small class="text-muted">Prefix prepended to each table number (e.g. 'T' gives T1, T2...)</small>
                                    @error('prefix') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6 col-12">
                                    <label class="form-label fw-bold">Start Number <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="feather-hash text-muted"></i></span>
                                        <input type="number"
                                               name="start_num"
                                               class="form-control @error('start_num') is-invalid @enderror"
                                               value="{{ old('start_num', 1) }}"
                                               min="1"
                                               max="500"
                                               required>
                                    </div>
                                    <small class="text-muted">Starting sequence number.</small>
                                    @error('start_num') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>

                                <!-- Count & Capacity -->
                                <div class="col-md-6 col-12">
                                    <label class="form-label fw-bold">Total Tables to Generate <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="feather-grid text-muted"></i></span>
                                        <input type="number"
                                               name="count"
                                               class="form-control @error('count') is-invalid @enderror"
                                               value="{{ old('count', 6) }}"
                                               min="1"
                                               max="50"
                                               required>
                                    </div>
                                    <small class="text-muted">Number of sequential tables to add.</small>
                                    @error('count') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6 col-12">
                                    <label class="form-label fw-bold">Seating Capacity Each <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="feather-users text-muted"></i></span>
                                        <input type="number"
                                               name="capacity"
                                               class="form-control @error('capacity') is-invalid @enderror"
                                               value="{{ old('capacity', 4) }}"
                                               min="1"
                                               max="50"
                                               required>
                                    </div>
                                    <small class="text-muted">Max guests capacity for each generated table.</small>
                                    @error('capacity') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>

                                <!-- Status -->
                                <div class="col-12">
                                    <label class="form-label fw-bold">Initial Availability Status <span class="text-danger">*</span></label>
                                    <div class="row g-3">
                                        <div class="col-md-4 col-12">
                                            <label class="p-3 border rounded-3 d-flex align-items-center gap-3 cursor-pointer w-100 h-100 bg-light-hover" style="cursor: pointer;">
                                                <input type="radio" name="status" value="available" {{ old('status', 'available') === 'available' ? 'checked' : '' }}>
                                                <div>
                                                    <div class="fw-bold text-success"><i class="feather-check-circle me-1"></i> Available</div>
                                                    <small class="text-muted fs-11">Ready for online reservations</small>
                                                </div>
                                            </label>
                                        </div>

                                        <div class="col-md-4 col-12">
                                            <label class="p-3 border rounded-3 d-flex align-items-center gap-3 cursor-pointer w-100 h-100 bg-light-hover" style="cursor: pointer;">
                                                <input type="radio" name="status" value="maintenance" {{ old('status') === 'maintenance' ? 'checked' : '' }}>
                                                <div>
                                                    <div class="fw-bold text-warning"><i class="feather-tool me-1"></i> Maintenance</div>
                                                    <small class="text-muted fs-11">Under repair / Offline</small>
                                                </div>
                                            </label>
                                        </div>

                                        <div class="col-md-4 col-12">
                                            <label class="p-3 border rounded-3 d-flex align-items-center gap-3 cursor-pointer w-100 h-100 bg-light-hover" style="cursor: pointer;">
                                                <input type="radio" name="status" value="inactive" {{ old('status') === 'inactive' ? 'checked' : '' }}>
                                                <div>
                                                    <div class="fw-bold text-secondary"><i class="feather-slash me-1"></i> Inactive</div>
                                                    <small class="text-muted fs-11">Disabled from booking</small>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                    @error('status') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-12 pt-3 border-top d-flex align-items-center gap-2">
                                    <button type="submit" class="btn btn-primary fw-bold px-4">
                                        <i class="feather-check-circle me-1"></i> Generate Tables
                                    </button>
                                    <a href="{{ route('restaurant.dining-setup.index') }}" class="btn btn-light border">
                                        Cancel
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
@endsection
