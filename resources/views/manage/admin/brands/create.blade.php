@extends('layouts.admin.main')

@section('title', 'Add New Brand - Admin Dashboard')

@section('content')
<div class="nxl-content">
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Add New Brand</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.brands.index') }}">Brands</a></li>
                <li class="breadcrumb-item">Add New</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto d-flex align-items-center gap-2">
            <a href="{{ route('admin.brands.index') }}" class="btn btn-light border text-secondary fw-semibold">
                <i class="feather-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>
    <!-- [ page-header ] end -->

    <!-- [ Main Content ] start -->
    <div class="main-content">
        <!-- Alerts -->
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <div class="fw-bold mb-1"><i class="feather-alert-triangle me-1"></i> Please fix the following errors:</div>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
            <div class="card-header border-bottom py-3">
                <h5 class="card-title mb-0 fw-bold">Brand Information</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-4">
                        <!-- Brand Name -->
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-semibold">Brand Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="e.g. McDonald's, Domino's, KFC, Burger King">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Slug -->
                        <div class="col-md-6">
                            <label for="slug" class="form-label fw-semibold">Slug <small class="text-muted">(Auto-generated or custom)</small></label>
                            <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug') }}" placeholder="e.g. mcdonalds, dominos">
                            @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Status -->
                        <div class="col-md-6">
                            <label for="status" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Logo Upload -->
                        <div class="col-md-6">
                            <label for="logo" class="form-label fw-semibold">Brand Logo <small class="text-muted">(Square image, Max 2MB: PNG, JPG, WEBP, SVG)</small></label>
                            <input type="file" name="logo" id="logo" class="form-control @error('logo') is-invalid @enderror" accept="image/*" onchange="previewImage(this, 'logoPreview')">
                            @error('logo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            
                            <div class="mt-3 d-none" id="logoPreviewWrapper">
                                <span class="d-block fs-11 text-muted mb-1">Logo Preview:</span>
                                <img id="logoPreview" src="#" alt="Logo Preview" class="rounded border p-1 bg-white shadow-sm" style="width: 80px; height: 80px; object-fit: contain;">
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="col-12">
                            <label for="description" class="form-label fw-semibold">Description</label>
                            <textarea name="description" id="description" rows="4" class="form-control @error('description') is-invalid @enderror" placeholder="Brief overview or bio of the brand...">{{ old('description') }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 border-top pt-4 mt-4">
                        <a href="{{ route('admin.brands.index') }}" class="btn btn-light border text-secondary fw-semibold">Cancel</a>
                        <button type="submit" class="btn btn-danger text-white fw-semibold px-4" style="background-color: #cb202d; border: none;">
                            <i class="feather-save me-1"></i> Save Brand
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>

@push('scripts')
<script>
    // Live slug generation from name
    document.getElementById('name').addEventListener('input', function () {
        const slugInput = document.getElementById('slug');
        if (!slugInput.dataset.touched) {
            slugInput.value = this.value
                .toLowerCase()
                .trim()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/[\s-]+/g, '-');
        }
    });

    document.getElementById('slug').addEventListener('input', function () {
        this.dataset.touched = true;
    });

    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
        const wrapper = document.getElementById(previewId + 'Wrapper');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                wrapper.classList.remove('d-none');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
@endsection
