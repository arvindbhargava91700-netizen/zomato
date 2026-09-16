@extends('layouts.admin.main')

@section('title', getPageTitle('SMS Configuration'))

@section('content')
<div class="nxl-content">
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">SMS Configuration</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">SMS Configuration</li>
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
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
            <div class="card-header border-bottom py-3">
                <h5 class="card-title mb-0 fw-bold">SMS Gateway Settings</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.sms-configuration.update') }}" method="POST" class="row g-3">
                    @csrf

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Provider</label>
                        <select name="sms_provider" class="form-select">
                            <option value="">Select Provider</option>
                            <option value="twilio" {{ ($settings['sms_provider']->value ?? '') == 'twilio' ? 'selected' : '' }}>Twilio</option>
                            <option value="msg91" {{ ($settings['sms_provider']->value ?? '') == 'msg91' ? 'selected' : '' }}>MSG91</option>
                            <option value="textlocal" {{ ($settings['sms_provider']->value ?? '') == 'textlocal' ? 'selected' : '' }}>Textlocal</option>
                            <option value="custom" {{ ($settings['sms_provider']->value ?? '') == 'custom' ? 'selected' : '' }}>Custom / Other</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">API Key</label>
                        <input type="text" name="sms_api_key" class="form-control" placeholder="API Key" value="{{ $settings['sms_api_key']->value ?? '' }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">API Secret</label>
                        <input type="text" name="sms_api_secret" class="form-control" placeholder="API Secret" value="{{ $settings['sms_api_secret']->value ?? '' }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Sender ID</label>
                        <input type="text" name="sms_sender_id" class="form-control" placeholder="SENDER" value="{{ $settings['sms_sender_id']->value ?? '' }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Base URL</label>
                        <input type="text" name="sms_base_url" class="form-control" placeholder="https://api.provider.com" value="{{ $settings['sms_base_url']->value ?? '' }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Route / Template ID</label>
                        <input type="text" name="sms_route" class="form-control" placeholder="e.g. 4 / template id" value="{{ $settings['sms_route']->value ?? '' }}">
                    </div>

                    <div class="col-12 d-flex gap-2 mt-2">
                        <button type="submit" class="btn btn-danger text-white fw-semibold px-4" style="background-color: #cb202d; border: none;">
                            <i class="feather-save me-1"></i> Save Configuration
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
