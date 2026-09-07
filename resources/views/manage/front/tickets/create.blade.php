@extends('layouts.front.main')

@section('content')
    <!-- page head section starts -->
    <section class="page-head-section">
        <div class="container page-heading">
            <h2 class="h3 mb-3 text-white text-center">Raise a Support Ticket</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb flex-lg-nowrap justify-content-center justify-content-lg-star">
                    <li class="breadcrumb-item">
                        <a href="{{ route('index') }}"><i class="ri-home-line"></i> Home</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('help') }}">Support</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Raise a Ticket</li>
                </ol>
            </nav>
        </div>
    </section>
    <!-- page head section end -->

    <section class="section-b-space">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm support-form-card">
                        <div class="card-body p-4 p-md-5">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="support-form-icon"><i class="ri-edit-2-line"></i></span>
                                <h4 class="fw-bold mb-0">Ticket Details</h4>
                            </div>
                            <p class="text-muted mb-4">Please provide as much detail as possible so we can assist you
                                quickly.</p>

                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('tickets-store') }}" enctype="multipart/form-data">
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                                    <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                                        <option value="">Select a category</option>
                                        @foreach(\App\Models\Ticket::CATEGORIES as $key => $label)
                                            <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Order ID <span class="text-muted">(optional)</span></label>
                                    <input type="text" name="order_id" value="{{ old('order_id') }}"
                                        class="form-control @error('order_id') is-invalid @enderror"
                                        placeholder="e.g. ORD123456">
                                    @error('order_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Subject <span class="text-danger">*</span></label>
                                    <input type="text" name="subject" value="{{ old('subject') }}"
                                        class="form-control @error('subject') is-invalid @enderror"
                                        placeholder="Short summary of the issue" required>
                                    @error('subject')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Priority <span class="text-danger">*</span></label>
                                    <select name="priority" class="form-select @error('priority') is-invalid @enderror" required>
                                        <option value="">Select priority</option>
                                        @foreach(\App\Models\Ticket::PRIORITIES as $key)
                                            <option value="{{ $key }}" {{ old('priority') == $key ? 'selected' : '' }}>{{ ucfirst($key) }}</option>
                                        @endforeach
                                    </select>
                                    @error('priority')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
                                    <textarea name="description" rows="5"
                                        class="form-control @error('description') is-invalid @enderror"
                                        placeholder="Describe your issue in detail (min 10 characters)" required>{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Attachment <span class="text-muted">(jpg, png, pdf - optional)</span></label>
                                    <input type="file" name="attachment"
                                        class="form-control @error('attachment') is-invalid @enderror"
                                        accept=".jpg,.jpeg,.png,.pdf">
                                    @error('attachment')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-flex gap-2 justify-content-end">
                                    <a href="{{ route('tickets-index') }}" class="btn btn-outline-secondary">Cancel</a>
                                    <button type="submit" class="btn theme-btn"><i class="ri-send-plane-line me-1"></i> Submit Ticket</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('styles')
        <style>
            .support-form-card { border-radius: 16px; }
            .support-form-icon {
                width: 40px; height: 40px; border-radius: 10px;
                display: flex; align-items: center; justify-content: center;
                background: rgba(255,141,47,.12); color: #ff8d2f; font-size: 1.15rem;
            }
        </style>
    @endpush
@endsection
