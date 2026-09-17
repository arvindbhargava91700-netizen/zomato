@extends('layouts.admin.main')

@section('title', getPageTitle('IP Settings'))

@section('content')
<div class="nxl-content">
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">IP Settings & Tracking</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">IP Settings</li>
            </ul>
        </div>
    </div>

    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
            <div class="card-header border-bottom py-3 d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0 fw-bold">Tracked IP Addresses</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>IP Address</th>
                                <th>Failed Attempts</th>
                                <th>Status</th>
                                <th>Last Email Tried</th>
                                <th>Last Attempt / Blocked At</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ips as $ip)
                                <tr>
                                    <td><span class="fw-semibold text-primary">{{ $ip->ip_address }}</span></td>
                                    <td>
                                        <span class="badge {{ $ip->failed_attempts >= 5 ? 'bg-soft-danger text-danger' : 'bg-soft-secondary text-secondary' }}">
                                            {{ $ip->failed_attempts }} Attempts
                                        </span>
                                    </td>
                                    <td>
                                        @if($ip->is_blocked)
                                            <span class="badge bg-danger">Blocked</span>
                                        @else
                                            <span class="badge bg-success">Active</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-dark fw-medium d-block">{{ $ip->details['last_email'] ?? 'N/A' }}</span>
                                        @if(isset($ip->details['user_agent']))
                                            <small class="text-muted text-truncate d-inline-block mt-1" style="max-width: 200px;" title="{{ $ip->details['user_agent'] }}">
                                                <i class="feather-monitor me-1"></i>{{ $ip->details['user_agent'] }}
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($ip->is_blocked && $ip->blocked_at)
                                            <span class="text-muted small">Blocked: {{ $ip->blocked_at->format('d M, Y h:i A') }}</span><br>
                                            @if($ip->blocked_at->copy()->addHours(24)->isFuture())
                                                <small class="text-danger">Unblocks in {{ $ip->blocked_at->copy()->addHours(24)->diffForHumans(null, true) }}</small>
                                            @endif
                                        @else
                                            <span class="text-muted small">Last Try: {{ $ip->updated_at->format('d M, Y h:i A') }}</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <form action="{{ route('admin.ip-settings.toggle-block', $ip->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @if($ip->is_blocked)
                                                <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Are you sure you want to unblock this IP address?')">Unblock</button>
                                            @else
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to manually block this IP address?')">Block</button>
                                            @endif
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="feather-shield fs-1 d-block mb-3"></i>
                                        No IP tracking records found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($ips->hasPages())
                <div class="card-footer border-top py-3">
                    {{ $ips->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
