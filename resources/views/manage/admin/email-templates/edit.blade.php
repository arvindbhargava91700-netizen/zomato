@extends('layouts.admin.main')

@section('title', 'Edit Email Template - Admin Dashboard')

@section('content')
<div class="nxl-content">
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Edit Email Template</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.email-templates.index') }}">Email Templates</a></li>
                <li class="breadcrumb-item">Edit</li>
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
                <h5 class="card-title mb-0 fw-bold">Template Details</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.email-templates.update', $emailTemplate->id) }}" method="POST" class="row g-3">
                    @csrf
                    @method('PUT')

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Template Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Order Confirmation" value="{{ old('name', $emailTemplate->name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Slug <span class="text-danger">*</span></label>
                        <input type="text" name="slug" class="form-control" placeholder="order_confirmation" value="{{ old('slug', $emailTemplate->slug) }}" required>
                        <small class="text-muted">Machine name used in code, e.g. <code>order_confirmation</code>. Auto-formatted.</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Subject <span class="text-danger">*</span></label>
                        <input type="text" name="subject" class="form-control" placeholder="Your order #@{{ order_id }} is confirmed" value="{{ old('subject', $emailTemplate->subject) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Category</label>
                        <input type="text" name="category" class="form-control" placeholder="order / auth / marketing" value="{{ old('category', $emailTemplate->category) }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Email Body (HTML) <span class="text-danger">*</span></label>
                        <textarea name="body" id="body-input" class="d-none">{{ old('body', $emailTemplate->body) }}</textarea>
                        <div id="quill-editor" style="height:340px;background:#fff;"></div>
                        <small class="text-muted d-block mt-2">
                            Use <code>@{{ variable }}</code> placeholders, e.g. <code>@{{ customer_name }}</code>, <code>@{{ order_id }}</code>, <code>@{{ app_name }}</code>, <code>@{{ promo_code }}</code>.
                        </small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select">
                            <option value="active" {{ old('status', $emailTemplate->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $emailTemplate->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="col-12 d-flex gap-2 mt-2">
                        <button type="submit" class="btn btn-danger text-white fw-semibold px-4" style="background-color: #cb202d; border: none;">
                            <i class="feather-save me-1"></i> Update Template
                        </button>
                        <a href="{{ route('admin.email-templates.index') }}" class="btn btn-light border text-secondary fw-semibold">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
                placeholder: 'Write your email content here...',
                modules: {
                    toolbar: [
                        [{ header: [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline'],
                        [{ list: 'ordered' }, { list: 'bullet' }],
                        ['link', 'image'],
                        ['clean']
                    ]
                }
            });

            var initial = @json(old('body', $emailTemplate->body));
            if (initial) {
                quill.clipboard.dangerouslyPasteHTML(initial);
            }

            document.querySelector('form').addEventListener('submit', function () {
                document.getElementById('body-input').value = quill.root.innerHTML;
            });
        });
    </script>
@endpush
@endsection
