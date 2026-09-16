@extends('layouts.admin.main')

@section('title', getPageTitle('Email Templates'))

@section('content')
<div class="nxl-content">
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Email Templates</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Email Templates</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto d-flex align-items-center gap-2">
            <a href="{{ route('admin.email-templates.create') }}" class="btn btn-danger text-white fw-semibold" style="background-color: #cb202d; border: none;">
                <i class="feather-plus me-1"></i> Add Template
            </a>
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
                <h5 class="card-title mb-0 fw-bold">Email Templates List</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Name</th>
                                <th>Slug</th>
                                <th>Subject</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($templates as $template)
                                <tr>
                                    <td class="ps-4 fw-semibold">{{ $template->name }}</td>
                                    <td><code>{{ $template->slug }}</code></td>
                                    <td>{{ $template->subject }}</td>
                                    <td>
                                        @if($template->category)
                                            <span class="badge bg-soft-primary text-primary fs-12">{{ $template->category }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <form action="{{ route('admin.email-templates.toggle-status', $template->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            @if($template->status === 'active')
                                                <button type="submit" class="btn btn-sm btn-success border-0 px-3 rounded-pill fw-semibold">Active</button>
                                            @else
                                                <button type="submit" class="btn btn-sm btn-secondary border-0 px-3 rounded-pill fw-semibold">Inactive</button>
                                            @endif
                                        </form>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="d-flex align-items-center justify-content-end gap-1">
                                            <a href="{{ route('admin.email-templates.edit', $template->id) }}" class="btn btn-sm btn-light border text-primary p-2 rounded-2" title="Edit">
                                                <i class="feather-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.email-templates.destroy', $template->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this email template?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light border text-danger p-2 rounded-2" title="Delete">
                                                    <i class="feather-trash-2"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="feather-inbox display-6 d-block mb-2 text-secondary"></i>
                                        No email templates found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($templates->hasPages())
                <div class="card-footer bg-white py-3 border-top">
                    <div class="d-flex justify-content-end">
                        {{ $templates->links() }}
                    </div>
                </div>
            @endif
        </div>

        <div class="card stretch stretch-full border-0 shadow-sm rounded-3 mt-4">
            <div class="card-header border-bottom py-3">
                <h5 class="card-title mb-0 fw-bold">Email Header &amp; Footer (Global)</h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">This header and footer are automatically added to <strong>every</strong> email template (welcome, vendor, delivery partner, order, etc.). Use placeholders like <code>@{{ app_name }}</code>, <code>@{{ year }}</code>, <code>@{{ support_email }}</code>.</p>
                <form action="{{ route('admin.email-templates.layout') }}" method="POST" class="row g-3">
                    @csrf
                    <div class="col-12">
                        <label class="form-label fw-semibold">Header</label>
                        <textarea name="email_header_html" id="header-input" class="d-none">{{ old('email_header_html', $emailHeaderHtml) }}</textarea>
                        <div id="quill-header" style="height:180px;background:#fff;"></div>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Footer</label>
                        <textarea name="email_footer_html" id="footer-input" class="d-none">{{ old('email_footer_html', $emailFooterHtml) }}</textarea>
                        <div id="quill-footer" style="height:200px;background:#fff;"></div>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-danger text-white fw-semibold px-4" style="background-color:#cb202d;border-color:#cb202d;">
                            <i class="feather-save me-1"></i> Save Header &amp; Footer
                        </button>
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
            function initQuill(elId, inputId, initial) {
                var q = new Quill('#' + elId, {
                    theme: 'snow',
                    placeholder: 'Design your ' + elId.replace('quill-', '') + '...',
                    modules: {
                        toolbar: [
                            ['bold', 'italic', 'underline'],
                            [{ list: 'ordered' }, { list: 'bullet' }],
                            ['link', 'image'],
                            ['clean']
                        ]
                    }
                });
                if (initial) {
                    q.clipboard.dangerouslyPasteHTML(initial);
                }
                document.getElementById(inputId).closest('form').addEventListener('submit', function () {
                    document.getElementById(inputId).value = q.root.innerHTML;
                });
            }

            initQuill('quill-header', 'header-input', @json(old('email_header_html', $emailHeaderHtml)));
            initQuill('quill-footer', 'footer-input', @json(old('email_footer_html', $emailFooterHtml)));
        });
    </script>
@endpush
@endsection
