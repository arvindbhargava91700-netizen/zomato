@extends('layouts.restaurant.main')

@section('title', 'Notifications - Restaurant Partner')

@section('content')
<div class="nxl-content">
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Notifications</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('restaurant.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Notifications</li>
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

        <div class="card stretch stretch-full">
            <div class="card-header border-bottom py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <h5 class="card-title mb-0 fw-bold">
                    All Notifications
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <span class="badge bg-danger ms-1">{{ auth()->user()->unreadNotifications->count() }} unread</span>
                    @endif
                </h5>
                <form method="POST" action="{{ route('restaurant.notifications.read-all') }}">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-light border text-secondary fw-semibold">
                        <i class="feather-check me-1"></i>Mark All as Read
                    </button>
                </form>
            </div>
            <div class="card-body p-0">
                @forelse($notifications as $notification)
                    @php
                        $data = $notification->data;
                        $unread = $notification->read_at === null;
                        $icon = $data['icon'] ?? 'feather-bell';
                    @endphp
                    <a href="{{ route('restaurant.notifications.show', $notification->id) }}"
                        class="d-flex align-items-start gap-3 px-4 py-3 text-decoration-none border-bottom {{ $unread ? 'bg-soft-primary' : '' }}"
                        style="transition: background 0.15s ease;">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 mt-1"
                            style="width: 40px; height: 40px; background: {{ $unread ? 'rgba(13,110,253,0.12)' : 'rgba(100,116,139,0.12)' }};">
                            <i class="{{ $icon }} {{ $unread ? 'text-primary' : 'text-secondary' }}"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold text-dark mb-1">{{ $data['title'] ?? 'Notification' }}</div>
                            <div class="text-muted fs-13 mb-1">{{ $data['message'] ?? '' }}</div>
                            <div class="fs-12 text-muted">
                                {{ $notification->created_at->diffForHumans() }}
                                @if($unread)
                                    <span class="badge bg-primary ms-2">New</span>
                                @endif
                            </div>
                        </div>
                        <i class="feather-chevron-right text-muted mt-2 flex-shrink-0"></i>
                    </a>
                @empty
                    <div class="text-center py-5 text-muted">
                        <i class="feather-bell display-6 d-block mb-2 text-secondary"></i>
                        No notifications yet.
                    </div>
                @endforelse
            </div>

            @if($notifications->hasPages())
                <div class="card-footer bg-white py-3 border-top">
                    <div class="d-flex justify-content-end">
                        {{ $notifications->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection