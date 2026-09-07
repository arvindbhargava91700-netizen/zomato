<div class="nxl-content">
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Raise a Support Ticket</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route($rp . 'index') }}">My Tickets</a></li>
                <li class="breadcrumb-item">Create</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto d-flex align-items-center gap-2">
            <a href="{{ route($rp . 'index') }}" class="btn btn-light border text-secondary fw-semibold">
                <i class="feather-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>

    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
            <div class="card-header border-bottom py-3">
                <h5 class="card-title mb-0 fw-bold">Ticket Details</h5>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route($rp . 'store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3 mb-2">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                            <select name="category" class="form-select" required>
                                <option value="">Select category</option>
                                @foreach(\App\Models\Ticket::CATEGORIES as $key => $label)
                                    <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Priority</label>
                            <select name="priority" class="form-select">
                                <option value="low" {{ old('priority', 'medium') == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ old('priority', 'medium') == 'high' ? 'selected' : '' }}>High</option>
                                <option value="urgent" {{ old('priority', 'medium') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Subject <span class="text-danger">*</span></label>
                        <input type="text" name="subject" class="form-control" value="{{ old('subject') }}" placeholder="Short title of your issue" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control" rows="5" placeholder="Explain your issue in detail" required>{{ old('description') }}</textarea>
                    </div>

                    <div class="row g-3 mb-2">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Related Order ID <span class="text-muted">(optional)</span></label>
                            <input type="text" name="order_id" class="form-control" value="{{ old('order_id') }}" placeholder="e.g. ORD1024">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Attachment <span class="text-muted">(optional, jpg/png/pdf)</span></label>
                            <input type="file" name="attachment" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 border-top pt-3">
                        <a href="{{ route($rp . 'index') }}" class="btn btn-light border px-4 fw-semibold">Cancel</a>
                        <button type="submit" class="btn btn-danger text-white px-5 fw-semibold" style="background-color: #cb202d; border: none;">Submit Ticket</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
