@extends('layouts.restaurant.main')

@section('title', 'Add New Restaurant Blog')

@section('content')
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10"><i class="feather-plus-circle me-2 text-primary"></i> Create New Blog Article</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('restaurant.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('restaurant.blogs.index') }}">Blogs</a></li>
                <li class="breadcrumb-item active">Add Blog</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto d-flex align-items-center gap-2">
            <a href="{{ route('restaurant.blogs.index') }}" class="btn btn-light border text-secondary fw-semibold">
                <i class="feather-arrow-left me-1"></i> Back to Blogs
            </a>
        </div>
    </div>
    <!-- [ page-header ] end -->

    <!-- [ Main Content ] start -->
    <div class="main-content">
        @if(isset($errors) && $errors->any())
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <strong>Please fix the errors below:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('restaurant.blogs.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row g-4">
                <!-- Left Column: Blog Content -->
                <div class="col-lg-8 col-12">
                    <div class="card stretch stretch-full border-0 shadow-sm rounded-3 mb-4">
                        <div class="card-header p-4 border-bottom bg-white">
                            <h6 class="fw-bold mb-0 text-dark">
                                <i class="feather-file-text me-2 text-primary"></i> Blog Details & Content
                            </h6>
                        </div>

                        <div class="card-body p-4">
                            <!-- Blog Title -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Blog Title <span class="text-danger">*</span></label>
                                <input type="text"
                                       name="title"
                                       id="blogTitleInput"
                                       class="form-control form-control-lg fw-semibold"
                                       value="{{ old('title') }}"
                                       placeholder="E.g. Best Biryani in Lucknow – Our Special Secret Recipe"
                                       required>
                                <small class="text-muted">A compelling, descriptive title for your food story or recipe.</small>
                            </div>

                            <!-- Blog Category & Cuisine -->
                            <div class="row g-3 mb-4">
                                <div class="col-md-6 col-12">
                                    <label class="form-label fw-bold">Food / Blog Category <span class="text-danger">*</span></label>
                                    <select name="food_category_id" class="form-select form-control" required>
                                        <option value="">-- Select Category --</option>
                                        @if(isset($categories))
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat->id }}" {{ old('food_category_id') == $cat->id ? 'selected' : '' }}>
                                                    {{ $cat->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <small class="text-muted">Choose the primary food category.</small>
                                </div>

                                <div class="col-md-6 col-12">
                                    <label class="form-label fw-bold">Cuisine / Tag</label>
                                    <select name="cuisine_id" class="form-select form-control">
                                        <option value="">-- Select Cuisine --</option>
                                        @if(isset($cuisines))
                                            @foreach($cuisines as $cui)
                                                <option value="{{ $cui->id }}" {{ old('cuisine_id') == $cui->id ? 'selected' : '' }}>
                                                    {{ $cui->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <small class="text-muted">E.g. Indian, Italian, Mughlai, Chinese...</small>
                                </div>
                            </div>

                            <!-- Blog URL Slug -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Permalink / Slug <span class="text-danger">*</span></label>

                                <div class="input-group">
                                    <span class="input-group-text bg-light font-monospace fs-12">{{ url('/blog') }}?slug=</span>
                                    <input type="text"

                                           name="slug"
                                           id="blogSlugInput"
                                           class="form-control font-monospace"
                                           value="{{ old('slug') }}"
                                           placeholder="best-biryani-in-lucknow"
                                           required>
                                </div>
                                <small class="text-muted">Auto-generated from title. You can customize the URL slug.</small>
                            </div>


                            <!-- Short Description / Excerpt -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Short Summary / Excerpt</label>
                                <textarea name="short_description"
                                          class="form-control"
                                          rows="3"
                                          placeholder="Discover our special Lucknow-style dum biryani made with slow-cooked spices and fragrant aged basmati rice...">{{ old('short_description') }}</textarea>
                                <small class="text-muted">A brief summary displayed on the blog card and search previews (max 600 characters).</small>
                            </div>

                            <!-- Full Article Content -->
                            <div class="mb-3">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <label class="form-label fw-bold mb-0">Full Story / Article Content <span class="text-danger">*</span></label>
                                    <span class="fs-12 text-muted">HTML & formatting supported</span>
                                </div>

                                <textarea name="content"
                                          id="blogContentArea"
                                          class="form-control"
                                          rows="14"
                                          placeholder="Write your article story, recipe steps, special ingredients, dining experience tips, and chef recommendations here..."
                                          style="font-family: inherit; font-size: 14px; line-height: 1.6;"
                                          required>{{ old('content') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Publishing, Media & Settings -->
                <div class="col-lg-4 col-12">
                    <!-- Publishing Status Card -->
                    <div class="card border-0 shadow-sm rounded-3 mb-4">
                        <div class="card-header p-4 border-bottom bg-white">
                            <h6 class="fw-bold mb-0 text-dark">
                                <i class="feather-send me-2 text-primary"></i> Publishing & Review
                            </h6>
                        </div>
                        <div class="card-body p-4">
                            <label class="form-label fw-bold">Initial Status <span class="text-danger">*</span></label>
                            <div class="d-flex flex-column gap-2 mb-4">
                                <label class="p-3 border rounded-3 d-flex align-items-center gap-3 cursor-pointer {{ old('status', 'published') === 'published' ? 'bg-soft-success border-success' : 'bg-light' }}" style="cursor: pointer;">
                                    <input type="radio" name="status" value="published" {{ old('status', 'published') === 'published' ? 'checked' : '' }} class="form-check-input mt-0">
                                    <div>
                                        <div class="fw-bold text-dark"><i class="feather-check-circle text-success me-1"></i> Submit for Review (Publish)</div>
                                        <div class="fs-12 text-muted">Goes to Admin review, then published live on approval.</div>
                                    </div>
                                </label>

                                <label class="p-3 border rounded-3 d-flex align-items-center gap-3 cursor-pointer {{ old('status') === 'draft' ? 'bg-soft-secondary border-secondary' : 'bg-light' }}" style="cursor: pointer;">
                                    <input type="radio" name="status" value="draft" {{ old('status') === 'draft' ? 'checked' : '' }} class="form-check-input mt-0">
                                    <div>
                                        <div class="fw-bold text-dark"><i class="feather-file text-secondary me-1"></i> Save as Draft</div>
                                        <div class="fs-12 text-muted">Keep editing privately; not visible to customers yet.</div>
                                    </div>
                                </label>
                            </div>

                            <div class="p-3 bg-soft-info text-info rounded-3 border border-info border-opacity-25 fs-12 mb-3">
                                <i class="feather-info me-1"></i> <b>Admin Review Workflow:</b> When you submit a blog, it is reviewed by the admin team to maintain quality and authenticity before going live.
                            </div>

                            <button type="submit" class="btn btn-primary w-100 fw-bold py-2">
                                <i class="feather-save me-1"></i> Save & Submit Blog
                            </button>
                        </div>
                    </div>

                    <!-- Featured Image Card -->
                    <div class="card border-0 shadow-sm rounded-3 mb-4">
                        <div class="card-header p-4 border-bottom bg-white">
                            <h6 class="fw-bold mb-0 text-dark">
                                <i class="feather-image me-2 text-primary"></i> Featured Banner Image
                            </h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3 text-center">
                                <img id="imagePreview"
                                     src="{{ asset('front/assets/images/blog/blog-grid/1.png') }}"
                                     alt="Preview"
                                     class="img-fluid rounded-3 border shadow-sm w-100 object-fit-cover"
                                     style="max-height: 200px; display: block;">
                            </div>

                            <label class="form-label fw-bold">Upload Banner Image</label>
                            <input type="file"
                                   name="featured_image"
                                   id="featuredImageInput"
                                   class="form-control"
                                   accept="image/*"
                                   onchange="previewFeaturedImage(this);">
                            <small class="text-muted d-block mt-1">Recommended: 1200x800px (JPG, PNG, WebP up to 5MB).</small>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- [ Main Content ] end -->
@endsection

@push('scripts')
<script>
    // Auto-generate slug from title
    document.getElementById('blogTitleInput').addEventListener('input', function () {
        var title = this.value;
        var slug = title.toLowerCase()
            .trim()
            .replace(/[^\w\s-]/g, '')
            .replace(/[\s_-]+/g, '-')
            .replace(/^-+|-+$/g, '');
        document.getElementById('blogSlugInput').value = slug;
    });

    // Image preview
    function previewFeaturedImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('imagePreview').src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
