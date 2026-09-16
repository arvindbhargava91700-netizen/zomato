@extends('layouts.admin.main')

@section('title', getPageTitle('Email Configuration'))

@section('content')
<div class="nxl-content">
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Email Configuration</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Email Configuration</li>
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
                <h5 class="card-title mb-0 fw-bold">SMTP Settings</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.email-configuration.update') }}" method="POST" class="row g-3">
                    @csrf
                    @method('POST')

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Mailer</label>
                        <select name="mail_mailer" class="form-select">
                            <option value="smtp" {{ ($settings['mail_mailer']->value ?? '') == 'smtp' ? 'selected' : '' }}>SMTP</option>
                            <option value="sendmail" {{ ($settings['mail_mailer']->value ?? '') == 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                            <option value="log" {{ ($settings['mail_mailer']->value ?? '') == 'log' ? 'selected' : '' }}>Log (testing)</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Host</label>
                        <input type="text" name="mail_host" class="form-control" placeholder="smtp.gmail.com" value="{{ $settings['mail_host']->value ?? '' }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Port</label>
                        <input type="number" name="mail_port" class="form-control" placeholder="587" value="{{ $settings['mail_port']->value ?? '' }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Username</label>
                        <input type="text" name="mail_username" class="form-control" placeholder="you@domain.com" value="{{ $settings['mail_username']->value ?? '' }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Password / App Password</label>
                        <input type="text" name="mail_password" class="form-control" placeholder="••••••••" value="{{ $settings['mail_password']->value ?? '' }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Encryption</label>
                        <select name="mail_encryption" class="form-select">
                            <option value="tls" {{ ($settings['mail_encryption']->value ?? 'tls') == 'tls' ? 'selected' : '' }}>TLS</option>
                            <option value="ssl" {{ ($settings['mail_encryption']->value ?? '') == 'ssl' ? 'selected' : '' }}>SSL</option>
                            <option value="null" {{ ($settings['mail_encryption']->value ?? '') == 'null' ? 'selected' : '' }}>None</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">From Address</label>
                        <input type="email" name="mail_from_address" class="form-control" placeholder="no-reply@domain.com" value="{{ $settings['mail_from_address']->value ?? '' }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">From Name</label>
                        <input type="text" name="mail_from_name" class="form-control" placeholder="Your App Name" value="{{ $settings['mail_from_name']->value ?? '' }}">
                    </div>

                    <div class="col-12 d-flex gap-2 mt-2">
                        <button type="submit" class="btn btn-danger text-white fw-semibold px-4" style="background-color: #cb202d; border: none;">
                            <i class="feather-save me-1"></i> Save Configuration
                        </button>
                    </div>
                </form>

                <hr class="my-4">

                <h6 class="fw-bold mb-2">Send Test Email</h6>
                <form action="{{ route('admin.email-configuration.test') }}" method="POST" class="row g-2 align-items-center">
                    @csrf
                    <div class="col-md-6">
                        <input type="email" name="test_email" class="form-control" placeholder="recipient@example.com" required>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-outline-primary fw-semibold">
                            <i class="feather-send me-1"></i> Send Test
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
