@extends('layouts.restaurant.main')

@section('title', 'Edit Menu - Zomato Partner')

@section('content')
<div class="nxl-content">
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Edit Menu</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('restaurant.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('restaurant.menus.index') }}">Menus</a></li>
                <li class="breadcrumb-item">{{ $menu->name }}</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto d-flex align-items-center gap-2">
            <a href="{{ route('restaurant.menus.index') }}" class="btn btn-light border text-secondary fw-semibold">
                <i class="feather-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>
    <!-- [ page-header ] end -->

    <!-- [ Main Content ] start -->
    <div class="main-content">
        <div class="row justify-content-center">
            <div class="col-xl-7 col-lg-8 col-md-10">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="card-title mb-0 fw-bold"><i class="feather-edit text-danger me-2"></i>Replace Menu Image</h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('restaurant.menus.update', $menu->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="text-center mb-4">
                                <h6 class="fw-semibold text-dark mb-3">Current Menu Image</h6>
                                @if($menu->image)
                                    <img src="{{ asset($menu->image) }}" alt="{{ $menu->name }}" class="rounded-3 border shadow-sm mb-3"
                                        style="max-width: 100%; max-height: 320px; object-fit: contain;">
                                @else
                                    <div class="rounded bg-light d-flex align-items-center justify-content-center border mx-auto mb-3"
                                        style="width: 160px; height: 160px;">
                                        <i class="feather-image text-muted fs-1"></i>
                                    </div>
                                @endif

                                <!-- Upload Drop Zone -->
                                <label for="image" class="d-block mb-0" style="cursor: pointer;">
                                    <div id="dropzone" class="border-2 border-dashed rounded-3 p-5 bg-light bg-opacity-50"
                                        style="border-color: #cb202d; transition: all .2s ease;">
                                        <i class="feather-image text-danger display-4 d-block mb-3"></i>
                                        <h6 class="fw-bold text-dark mb-1">Click to choose a new image</h6>
                                        <p class="text-muted fs-12 mb-0">JPG, PNG, WEBP, GIF — Max 4MB | Leave empty to keep current</p>
                                    </div>
                                </label>
                                <input type="file" name="image" id="image" class="d-none @error('image') is-invalid @enderror" accept="image/*">
                                @error('image') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror

                                <!-- Preview -->
                                <div id="previewWrap" class="d-none mt-3">
                                    <img id="imagePreview" src="#" alt="Preview" class="rounded-3 border shadow-sm"
                                        style="max-width: 100%; max-height: 320px; object-fit: contain;">
                                    <div class="mt-2">
                                        <span id="fileName" class="badge bg-soft-info text-info fs-12"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-center gap-2 border-top pt-3">
                                <a href="{{ route('restaurant.menus.index') }}" class="btn btn-light border px-4 fw-semibold">Cancel</a>
                                <button type="submit" id="submitBtn" class="btn btn-danger text-white px-5 fw-semibold" style="background-color: #cb202d; border: none;">
                                    <i class="feather-save me-1"></i> Update Menu
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var imageInput = document.getElementById('image');
        var dropzone = document.getElementById('dropzone');
        var previewWrap = document.getElementById('previewWrap');
        var imagePreview = document.getElementById('imagePreview');
        var fileName = document.getElementById('fileName');

        var editForm = document.getElementById('image') ? document.getElementById('image').closest('form') : null;
        if (editForm) {
            editForm.addEventListener('submit', function() {
                var btn = document.getElementById('submitBtn');
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Submitting...';
            });
        }

        function showPreview(file) {
            if (!file) return;
            var reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                previewWrap.classList.remove('d-none');
                fileName.textContent = file.name;
            };
            reader.readAsDataURL(file);
        }

        imageInput.addEventListener('change', function() {
            showPreview(this.files[0]);
        });

        if (dropzone) {
            ['dragover', 'dragenter'].forEach(function(evt) {
                dropzone.addEventListener(evt, function(e) {
                    e.preventDefault();
                    dropzone.style.backgroundColor = '#fde8e8';
                });
            });
            ['dragleave', 'drop'].forEach(function(evt) {
                dropzone.addEventListener(evt, function(e) {
                    e.preventDefault();
                    dropzone.style.backgroundColor = '';
                });
            });
            dropzone.addEventListener('drop', function(e) {
                var files = e.dataTransfer.files;
                if (files && files[0]) {
                    imageInput.files = files;
                    showPreview(files[0]);
                }
            });
        }
    });
</script>
@endpush