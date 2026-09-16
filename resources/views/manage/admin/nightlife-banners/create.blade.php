@extends('layouts.admin.main')

@section('title', getPageTitle('Add New Nightlife Banner'))

@section('content')
<div class="nxl-content">
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Add New Nightlife Banner</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.nightlife-banners.index') }}">Nightlife Banners</a></li>
                <li class="breadcrumb-item">Add New</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto d-flex align-items-center gap-2">
            <a href="{{ route('admin.nightlife-banners.index') }}" class="btn btn-light border text-secondary fw-semibold">
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
                <h5 class="card-title mb-0 fw-bold">Nightlife Banner Information</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.nightlife-banners.store') }}" method="POST" enctype="multipart/form-data" id="nightlifeBannerForm">
                    @csrf

                    <div class="row g-4">
                        <!-- Title / Heading -->
                        <div class="col-md-6">
                            <label for="title" class="form-label fw-semibold">Title / Heading <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required placeholder="e.g. Nightlife Near You, Party Hotspots">
                            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Slug -->
                        <div class="col-md-6">
                            <label for="slug" class="form-label fw-semibold">Slug <small class="text-muted">(Auto-generated or custom)</small></label>
                            <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug') }}" placeholder="e.g. nightlife-near-you">
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

                        <!-- Banner Upload -->
                        <div class="col-md-6">
                            <label for="banner" class="form-label fw-semibold">Banner Image <small class="text-muted">(Wide image, Max 4MB: PNG, JPG, WEBP, GIF)</small></label>
                            <input type="file" name="banner" id="banner" class="form-control @error('banner') is-invalid @enderror" accept="image/*" onchange="previewImage(this, 'bannerPreview')">
                            @error('banner') <div class="invalid-feedback">{{ $message }}</div> @enderror

                            <div class="mt-3 d-none" id="bannerPreviewWrapper">
                                <span class="d-block fs-11 text-muted mb-1">Banner Preview:</span>
                                <img id="bannerPreview" src="#" alt="Banner Preview" class="rounded border p-1 bg-white shadow-sm" style="width: 100%; max-width: 320px; height: 140px; object-fit: cover;">
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="col-12">
                            <label for="description" class="form-label fw-semibold">Description</label>
                            <textarea name="description" id="description" rows="4" class="form-control @error('description') is-invalid @enderror" placeholder="Short description for the nightlife section...">{{ old('description') }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 border-top pt-4 mt-4">
                        <a href="{{ route('admin.nightlife-banners.index') }}" class="btn btn-light border text-secondary fw-semibold">Cancel</a>
                        <button type="submit" class="btn btn-danger text-white fw-semibold px-4" id="submitBtn" style="background-color: #cb202d; border: none;">
                            <span class="btn-text"><i class="feather-save me-1"></i> Save Banner</span>
                            <span class="btn-spinner d-none"><span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Saving...</span>
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
    // Live slug generation from title
    document.getElementById('title').addEventListener('input', function () {
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

    // Submit spinner
    var form = document.getElementById('nightlifeBannerForm');
    form.addEventListener('submit', function (e) {
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }
        e.preventDefault();
        var btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.querySelector('.btn-text').classList.add('d-none');
        btn.querySelector('.btn-spinner').classList.remove('d-none');
        setTimeout(function () { form.submit(); }, 350);
    });
</script>
@endpush
@endsection