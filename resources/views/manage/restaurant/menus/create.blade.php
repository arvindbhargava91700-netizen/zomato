@extends('layouts.restaurant.main')

@section('title', 'Upload Menu - Zomato Partner')

@section('content')
<div class="nxl-content">
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Upload Menu</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('restaurant.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('restaurant.menus.index') }}">Menus</a></li>
                <li class="breadcrumb-item">Upload New</li>
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
                        <h5 class="card-title mb-0 fw-bold"><i class="feather-upload text-danger me-2"></i>Upload Menu Images</h5>
                    </div>
                    <div class="card-body p-4">
                        <form id="menuUploadForm" action="{{ route('restaurant.menus.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                             <!-- Inline status (shown during/after upload) -->
                            <div id="uploadStatus" class="d-none text-center mt-3"></div>

                            <div class="text-center mb-4">
                                <!-- Upload Drop Zone -->
                                <label for="images" class="d-block mb-0" style="cursor: pointer;">
                                    <div id="dropzone" class="border-2 border-dashed rounded-3 p-5 bg-light bg-opacity-50"
                                        style="border-color: #cb202d; transition: all .2s ease;">
                                        <i class="feather-image text-danger display-4 d-block mb-3"></i>
                                        <h6 class="fw-bold text-dark mb-1">Drop menu images here or click to browse</h6>
                                        <p class="text-muted fs-12 mb-0">Select multiple — JPG, PNG, WEBP, GIF | Max 4MB each</p>
                                    </div>
                                </label>
                                <input type="file" name="images[]" id="images" class="d-none @error('images') is-invalid @enderror" accept="image/*" multiple required>
                                @error('images') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                @error('images.*') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror

                                <!-- Preview Grid -->
                                <div id="previewWrap" class="d-none mt-4">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span id="fileCount" class="badge bg-soft-info text-info fs-12"></span>
                                        <button type="button" id="clearSelection" class="btn btn-sm btn-light border text-danger fw-semibold">
                                            <i class="feather-x me-1"></i>Clear All
                                        </button>
                                    </div>
                                    <div id="previewGrid" class="d-flex flex-wrap gap-3 justify-content-center"></div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-center gap-2 border-top pt-3">
                                <a href="{{ route('restaurant.menus.index') }}" class="btn btn-light border px-4 fw-semibold">Cancel</a>
                                <button type="submit" id="submitBtn" class="btn btn-danger text-white px-5 fw-semibold" style="background-color: #cb202d; border: none;" disabled>
                                    <i class="feather-upload me-1"></i> Upload Menus
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
        var form = document.getElementById('menuUploadForm');
        var imagesInput = document.getElementById('images');
        var dropzone = document.getElementById('dropzone');
        var previewWrap = document.getElementById('previewWrap');
        var previewGrid = document.getElementById('previewGrid');
        var fileCount = document.getElementById('fileCount');
        var clearSelection = document.getElementById('clearSelection');
        var submitBtn = document.getElementById('submitBtn');
        var uploadStatus = document.getElementById('uploadStatus');

        var selectedFiles = [];
        var previews = [];

        function showStatus(html, isError) {
            uploadStatus.innerHTML = html;
            uploadStatus.classList.remove('d-none');
            uploadStatus.className = isError
                ? 'text-center mt-3 alert alert-danger py-2 fs-13'
                : 'text-center mt-3 alert alert-success py-2 fs-13';
        }

        function hideStatus() {
            uploadStatus.classList.add('d-none');
        }

        function renderPreviews() {
            previews.forEach(function(p) { if (p.parentNode) p.parentNode.removeChild(p); });
            previews = [];

            selectedFiles.forEach(function(file, idx) {
                var wrap = document.createElement('div');
                wrap.className = 'position-relative';
                wrap.style.width = '110px';

                var img = document.createElement('img');
                img.className = 'rounded border shadow-sm';
                img.style.width = '110px';
                img.style.height = '110px';
                img.style.objectFit = 'cover';

                var reader = new FileReader();
                reader.onload = function(e) { img.src = e.target.result; };
                reader.readAsDataURL(file);

                var name = document.createElement('small');
                name.className = 'd-block text-muted text-truncate mt-1 fs-11';
                name.title = file.name;
                name.textContent = file.name;
                name.style.maxWidth = '110px';

                var removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'btn btn-sm btn-danger rounded-circle position-absolute top-0 end-0 d-flex align-items-center justify-content-center';
                removeBtn.style.width = '26px';
                removeBtn.style.height = '26px';
                removeBtn.style.transform = 'translate(30%, -30%)';
                removeBtn.style.padding = '0';
                removeBtn.title = 'Remove this image';
                removeBtn.innerHTML = '&times;';
                removeBtn.addEventListener('click', function() {
                    selectedFiles.splice(idx, 1);
                    syncInputFiles();
                    renderPreviews();
                });

                wrap.appendChild(img);
                wrap.appendChild(removeBtn);
                wrap.appendChild(name);
                previews.push(wrap);
                previewGrid.appendChild(wrap);
            });

            if (selectedFiles.length > 0) {
                previewWrap.classList.remove('d-none');
                fileCount.textContent = selectedFiles.length + ' image(s) selected';
                submitBtn.disabled = false;
            } else {
                previewWrap.classList.add('d-none');
                submitBtn.disabled = true;
            }
        }

        function syncInputFiles() {
            if (typeof DataTransfer === 'undefined') {
                imagesInput.value = '';
                return;
            }
            var dt = new DataTransfer();
            selectedFiles.forEach(function(f) { dt.items.add(f); });
            imagesInput.files = dt.files;
        }

        imagesInput.addEventListener('change', function() {
            selectedFiles = Array.from(this.files);
            renderPreviews();
        });

        if (dropzone) {
            ['dragover', 'dragenter'].forEach(function(evt) {
                dropzone.addEventListener(evt, function(e) {
                    e.preventDefault();
                    dropzone.style.backgroundColor = '#fde8e8';
                    dropzone.style.borderColor = '#a0131e';
                });
            });
            ['dragleave', 'drop'].forEach(function(evt) {
                dropzone.addEventListener(evt, function(e) {
                    e.preventDefault();
                    dropzone.style.backgroundColor = '';
                    dropzone.style.borderColor = '#cb202d';
                });
            });
            dropzone.addEventListener('drop', function(e) {
                var files = e.dataTransfer.files;
                if (files && files.length > 0) {
                    selectedFiles = Array.from(files);
                    imagesInput.files = files;
                    syncInputFiles();
                    renderPreviews();
                }
            });
        }

        if (clearSelection) {
            clearSelection.addEventListener('click', function() {
                imagesInput.value = '';
                selectedFiles = [];
                renderPreviews();
                hideStatus();
            });
        }

        form.addEventListener('submit', function(e) {
            if (selectedFiles.length === 0) return;

            e.preventDefault();
            hideStatus();

            var originalHtml = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Submitting... 0%';

            var fd = new FormData();
            fd.append('_token', '{{ csrf_token() }}');
            selectedFiles.forEach(function(file) {
                fd.append('images[]', file, file.name);
            });

            var xhr = new XMLHttpRequest();
            xhr.open('POST', form.getAttribute('action'), true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.setRequestHeader('Accept', 'application/json');

            xhr.upload.onprogress = function(evt) {
                if (evt.lengthComputable) {
                    var percent = Math.round((evt.loaded / evt.total) * 100);
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Submitting... ' + percent + '%';
                }
            };

            xhr.onload = function() {
                var res = {};
                try { res = JSON.parse(xhr.responseText); } catch (err) {}

                submitBtn.innerHTML = originalHtml;
                submitBtn.disabled = false;

                if (xhr.status >= 200 && xhr.status < 300 && res.success) {
                    showStatus('<i class="feather-check-circle me-1"></i>' + (res.message || 'Menu uploaded successfully. Redirecting...'));
                    setTimeout(function() {
                        window.location.href = res.redirect || '{{ route('restaurant.menus.index') }}';
                    }, 700);
                } else {
                    var msg = res.errors
                        ? Object.keys(res.errors).map(function(k) { return res.errors[k].join(' '); }).join(' ')
                        : (res.message || 'Upload failed. Please try again.');
                    showStatus('<i class="feather-alert-circle me-1"></i>' + msg, true);
                    // restore selection state so user can retry
                    renderPreviews();
                }
            };

            xhr.onerror = function() {
                submitBtn.innerHTML = originalHtml;
                submitBtn.disabled = false;
                showStatus('<i class="feather-alert-circle me-1"></i>Network error. Please try again.', true);
            };

            xhr.send(fd);
        });
    });
</script>
@endpush