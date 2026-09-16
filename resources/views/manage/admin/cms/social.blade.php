@extends('layouts.admin.main')

@section('title', getPageTitle('Social Media Setting'))

@section('content')
<div class="nxl-content">
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Social Media Setting</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">CMS Management</li>
                <li class="breadcrumb-item">Social Media Setting</li>
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

        <form action="{{ route('admin.cms.social.update') }}" method="POST">
            @csrf
            
            <div class="card stretch stretch-full border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header border-bottom py-3">
                    <h5 class="card-title mb-0 fw-bold">Social Media Links</h5>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted fs-13 mb-3">Add your official brand social media URLs. These will appear in the website footer and email templates.</p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="facebook_url" class="form-label fw-semibold">
                                <i class="feather-facebook text-primary me-1"></i> Facebook Page URL
                            </label>
                            <input type="url" name="facebook_url" id="facebook_url" class="form-control @error('facebook_url') is-invalid @enderror" value="{{ old('facebook_url', $setting->facebook_url) }}" placeholder="https://facebook.com/yourbrand">
                            @error('facebook_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="twitter_url" class="form-label fw-semibold">
                                <i class="feather-twitter text-info me-1"></i> Twitter / X URL
                            </label>
                            <input type="url" name="twitter_url" id="twitter_url" class="form-control @error('twitter_url') is-invalid @enderror" value="{{ old('twitter_url', $setting->twitter_url) }}" placeholder="https://twitter.com/yourbrand">
                            @error('twitter_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="instagram_url" class="form-label fw-semibold">
                                <i class="feather-instagram text-danger me-1"></i> Instagram URL
                            </label>
                            <input type="url" name="instagram_url" id="instagram_url" class="form-control @error('instagram_url') is-invalid @enderror" value="{{ old('instagram_url', $setting->instagram_url) }}" placeholder="https://instagram.com/yourbrand">
                            @error('instagram_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="linkedin_url" class="form-label fw-semibold">
                                <i class="feather-linkedin text-primary me-1"></i> LinkedIn URL
                            </label>
                            <input type="url" name="linkedin_url" id="linkedin_url" class="form-control @error('linkedin_url') is-invalid @enderror" value="{{ old('linkedin_url', $setting->linkedin_url) }}" placeholder="https://linkedin.com/company/yourbrand">
                            @error('linkedin_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="youtube_url" class="form-label fw-semibold">
                                <i class="feather-youtube text-danger me-1"></i> YouTube URL
                            </label>
                            <input type="url" name="youtube_url" id="youtube_url" class="form-control @error('youtube_url') is-invalid @enderror" value="{{ old('youtube_url', $setting->youtube_url) }}" placeholder="https://youtube.com/@yourbrand">
                            @error('youtube_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-end mb-5">
                <button type="submit" class="btn btn-primary">
                    <i class="feather-save me-2"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
