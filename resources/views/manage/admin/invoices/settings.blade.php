@extends('layouts.admin.main')

@section('title', getPageTitle('Invoice Settings'))

@section('content')
<div class="nxl-content">
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Invoice Settings</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Invoice Settings</li>
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

        <div class="row">
            <!-- Left Card (Edit Form) -->
            <div class="col-lg-5 col-md-12 mb-4">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                    <div class="card-header border-bottom py-3">
                        <h5 class="card-title mb-0 fw-bold">Edit Invoice Details</h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('admin.invoices.settings.update') }}" method="POST" id="invoiceSettingsForm" enctype="multipart/form-data">
                            @csrf
                            
                            <h6 class="fw-bold mb-3">General Details</h6>
                            
                            <div class="mb-4">
                                <label for="logo_lg" class="form-label fw-semibold">Company Logo (Optional)</label>
                                <input type="file" name="logo_lg" id="logo_lg" class="form-control @error('logo_lg') is-invalid @enderror" accept="image/*">
                                @error('logo_lg') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                @if(isset($setting->logo_lg) && $setting->logo_lg)
                                    <div class="mt-2">
                                        <img src="{{ asset($setting->logo_lg) }}" alt="Logo" style="max-height: 80px; border: 1px dashed #ccc; padding: 5px;">
                                    </div>
                                @endif
                                <small class="text-muted d-block mt-1">Logo to display on the top right of the invoice.</small>
                            </div>

                            <div class="mb-4">
                                <label for="invoice_prefix" class="form-label fw-semibold">Invoice Prefix</label>
                                <input type="text" name="invoice_prefix" id="invoice_prefix" class="form-control @error('invoice_prefix') is-invalid @enderror" value="{{ old('invoice_prefix', $setting->invoice_prefix) }}" placeholder="e.g. INV-">
                                @error('invoice_prefix') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-4">
                                <label for="gst_number" class="form-label fw-semibold">Company GST Number</label>
                                <input type="text" name="gst_number" id="gst_number" class="form-control @error('gst_number') is-invalid @enderror" value="{{ old('gst_number', $setting->gst_number) }}" placeholder="e.g. 22AAAAA0000A1Z5">
                                @error('gst_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <hr class="my-4">
                            <h6 class="fw-bold mb-3 border-bottom pb-2">Signatory Details</h6>

                            <div class="mb-4">
                                <label for="signature" class="form-label fw-semibold">Digital Signature (Optional)</label>
                                <input type="file" name="signature" id="signature" class="form-control @error('signature') is-invalid @enderror" accept="image/*">
                                @error('signature') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                @if(isset($setting->signature) && $setting->signature)
                                    <div class="mt-2">
                                        <img src="{{ asset($setting->signature) }}" alt="Signature" style="max-height: 80px; border: 1px dashed #ccc; padding: 5px;">
                                    </div>
                                @endif
                                <small class="text-muted d-block mt-1">Max size: 2MB. Max dimensions: 1024x1024 pixels.</small>
                            </div>

                            <div class="mb-4">
                                <label for="signatory_designation" class="form-label fw-semibold">Authorized Signatory Name/Title</label>
                                <input type="text" name="signatory_designation" id="signatory_designation" class="form-control @error('signatory_designation') is-invalid @enderror" value="{{ old('signatory_designation', $setting->signatory_designation) }}" placeholder="e.g. HR Manager or Authorized Signatory">
                                @error('signatory_designation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Terms & Conditions (Optional)</label>
                                <textarea name="invoice_terms" id="invoice_terms_input" class="d-none">{{ old('invoice_terms', $setting->invoice_terms) }}</textarea>
                                <div id="quill-editor" style="height:200px;background:#fff;"></div>
                                @error('invoice_terms') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" id="saveBtn" class="btn btn-danger text-white fw-semibold" style="background-color: #cb202d; border: none;">
                                    <span class="btn-text">Save & Refresh Preview</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right Card (Live Preview) -->
            <div class="col-lg-7 col-md-12 mb-4">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                    <div class="card-header border-bottom py-3 d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 fw-bold">Live Preview</h5>
                        <a href="{{ route('admin.invoices.preview') }}" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="feather-external-link me-2"></i>Open in New Tab</a>
                    </div>
                    <div class="card-body p-0" style="background: #f1f2f6; height: 800px;">
                        <iframe src="{{ route('admin.invoices.preview') }}" width="100%" height="100%" frameborder="0" style="border-radius: 0 0 8px 8px;"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>

@push('styles')
    <link rel="stylesheet" href="{{ asset('admin/assets/vendors/css/quill.min.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('admin/assets/vendors/js/quill.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var quill = new Quill('#quill-editor', {
                theme: 'snow',
                placeholder: 'Enter any terms and conditions to display on the invoice...',
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline'],
                        [{ list: 'ordered' }, { list: 'bullet' }],
                        ['clean']
                    ]
                }
            });

            var initial = @json(old('invoice_terms', $setting->invoice_terms ?? ''));
            if (initial) {
                quill.clipboard.dangerouslyPasteHTML(initial);
            }

            // On Form Submit: Update Quill Input and Show Loading Spinner
            document.getElementById('invoiceSettingsForm').addEventListener('submit', function () {
                // Update hidden textarea
                document.getElementById('invoice_terms_input').value = quill.root.innerHTML;
                
                // Show loading spinner and disable button
                var saveBtn = document.getElementById('saveBtn');
                saveBtn.disabled = true;
                saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Saving...';
            });
        });
    </script>
@endpush
@endsection
