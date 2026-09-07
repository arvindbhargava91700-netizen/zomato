@extends('layouts.restaurant.main')

@section('title', 'Dining Setup & Table Management - Restaurant Partner')

@section('content')
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Dining Setup & Table Management</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('restaurant.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Dining</li>
                <li class="breadcrumb-item active">Setup & Tables</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto d-flex align-items-center gap-2">
            <a href="{{ route('restaurant.dining-setup.tables.batch-create') }}" class="btn btn-outline-primary fw-semibold">
                <i class="feather-layers me-1"></i> Quick Batch Tables
            </a>
            <a href="{{ route('restaurant.dining-setup.tables.create') }}" class="btn btn-danger text-white fw-semibold" style="background-color: #cb202d; border: none;">
                <i class="feather-plus me-1"></i> Add Table
            </a>
        </div>
    </div>

    <!-- [ page-header ] end -->

    <!-- [ Main Content ] start -->
    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
                <i class="feather-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
                <i class="feather-alert-triangle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(isset($errors) && $errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
                <strong>Please correct the errors below:</strong>
                <ul class="mb-0 mt-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif


        <!-- Summary Stats Cards -->
        <div class="row g-3 mb-4">
            <div class="col-xxl-3 col-md-6">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                    <div class="card-body p-3 d-flex align-items-center gap-3">
                        <div class="avatar-lg bg-soft-primary text-primary rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; font-size: 22px;">
                            <i class="feather-grid"></i>
                        </div>
                        <div>
                            <div class="fs-12 text-muted fw-semibold text-uppercase">Total Tables</div>
                            <div class="fs-4 fw-bold text-dark">{{ $totalTables }} <span class="fs-13 text-muted fw-normal">Tables</span></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xxl-3 col-md-6">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                    <div class="card-body p-3 d-flex align-items-center gap-3">
                        <div class="avatar-lg bg-soft-success text-success rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; font-size: 22px;">
                            <i class="feather-check-circle"></i>
                        </div>
                        <div>
                            <div class="fs-12 text-muted fw-semibold text-uppercase">Available for Booking</div>
                            <div class="fs-4 fw-bold text-success">{{ $availableTablesCount }} <span class="fs-13 text-muted fw-normal">Active</span></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xxl-3 col-md-6">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                    <div class="card-body p-3 d-flex align-items-center gap-3">
                        <div class="avatar-lg bg-soft-info text-info rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; font-size: 22px;">
                            <i class="feather-users"></i>
                        </div>
                        <div>
                            <div class="fs-12 text-muted fw-semibold text-uppercase">Total Seating Capacity</div>
                            <div class="fs-4 fw-bold text-dark">{{ $totalSeatingCapacity }} <span class="fs-13 text-muted fw-normal">Seats</span></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xxl-3 col-md-6">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                    <div class="card-body p-3 d-flex align-items-center gap-3">
                        <div class="avatar-lg bg-soft-warning text-warning rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; font-size: 22px;">
                            <i class="feather-clock"></i>
                        </div>
                        <div>
                            <div class="fs-12 text-muted fw-semibold text-uppercase">Slot Duration</div>
                            <div class="fs-4 fw-bold text-dark">{{ $restaurant->slot_duration_minutes ?: 60 }} <span class="fs-13 text-muted fw-normal">Mins / Slot</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="card stretch stretch-full border-0 shadow-sm rounded-3 mb-4">
            <div class="card-header p-4 border-bottom">
                <ul class="nav nav-pills card-header-pills" id="diningTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active fw-bold px-4 py-2" id="tables-tab" data-bs-toggle="tab" data-bs-target="#tablesPane" type="button" role="tab">
                            <i class="feather-grid me-1"></i> Restaurant Tables ({{ $totalTables }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold px-4 py-2" id="slots-tab" data-bs-toggle="tab" data-bs-target="#slotsPane" type="button" role="tab">
                            <i class="feather-clock me-1"></i> Live Slot Availability Inspector
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold px-4 py-2" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settingsPane" type="button" role="tab">
                            <i class="feather-settings me-1"></i> Dining Hours & Slot Config
                        </button>
                    </li>
                </ul>
            </div>

            <div class="card-body p-4">
                <div class="tab-content" id="diningTabContent">

                    <!-- ============================================================== -->
                    <!-- TAB 1: RESTAURANT TABLES -->
                    <!-- ============================================================== -->
                    <div class="tab-pane fade show active" id="tablesPane" role="tabpanel">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                            <div>
                                <h6 class="fw-bold mb-1">Defined Restaurant Tables</h6>
                                <p class="text-muted small mb-0">Manage your dining floor tables, seat capacities, and maintenance status.</p>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <a href="{{ route('restaurant.dining-setup.tables.batch-create') }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="feather-plus-circle me-1"></i> Batch Add
                                </a>
                                <a href="{{ route('restaurant.dining-setup.tables.create') }}" class="btn btn-sm btn-primary">
                                    <i class="feather-plus me-1"></i> Add New Table
                                </a>
                            </div>
                        </div>

                        @if($tables->isEmpty())
                            <div class="text-center py-5 border rounded-3 bg-light">
                                <div class="avatar-lg bg-soft-primary text-primary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width:60px; height:60px; font-size:26px;">
                                    <i class="feather-grid"></i>
                                </div>
                                <h5 class="fw-bold">No Tables Defined Yet</h5>
                                <p class="text-muted small max-w-400 mx-auto mb-3">Add your restaurant's physical tables (e.g. T1 with 2 seats, T2 with 4 seats) to enable intelligent table-by-table slot allocation.</p>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('restaurant.dining-setup.tables.batch-create') }}" class="btn btn-primary">
                                        <i class="feather-layers me-1"></i> Quick Generate Tables (T1 - T6)
                                    </a>
                                    <a href="{{ route('restaurant.dining-setup.tables.create') }}" class="btn btn-outline-primary">
                                        <i class="feather-plus me-1"></i> Add Single Table
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover align-middle border mb-0" id="customerList">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 60px;">#</th>
                                            <th>Table Number</th>
                                            <th>Guest Capacity</th>
                                            <th>Current Status</th>
                                            <th>Assigned Bookings</th>
                                            <th class="text-end" style="width: 140px;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tables as $idx => $table)
                                            <tr>
                                                <td class="text-muted fw-semibold">{{ $idx + 1 }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="table-avatar bg-dark text-white fw-bold rounded-2 px-2 py-1 fs-12 font-monospace">
                                                            {{ $table->table_number }}
                                                        </div>
                                                        <span class="fw-bold text-dark">{{ $table->table_number }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-soft-info text-info fs-12 px-2 py-1">
                                                        <i class="feather-users me-1"></i> {{ $table->capacity }} {{ $table->capacity == 1 ? 'Person' : 'Persons' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($table->status === 'available')
                                                        <span class="badge bg-soft-success text-success px-2 py-1"><i class="feather-check-circle me-1"></i> Available</span>
                                                    @elseif($table->status === 'maintenance')
                                                        <span class="badge bg-soft-warning text-warning px-2 py-1"><i class="feather-tool me-1"></i> In Maintenance</span>
                                                    @else
                                                        <span class="badge bg-soft-secondary text-secondary px-2 py-1"><i class="feather-slash me-1"></i> Inactive</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="text-muted small">
                                                        {{ $table->bookings()->where('book_date', '>=', now()->toDateString())->count() }} upcoming bookings
                                                    </span>
                                                </td>
                                                <td class="text-end">
                                                    <div class="d-inline-flex gap-1">
                                                        <!-- Toggle Status -->
                                                        <form action="{{ route('restaurant.dining-setup.tables.toggle-status', $table->id) }}" method="POST" class="d-inline">
                                                             @csrf
                                                             @method('PATCH')
                                                             <button type="submit" class="btn btn-sm btn-light border p-1 px-2" title="Toggle Maintenance / Available">
                                                                 <i class="feather-power text-{{ $table->status === 'available' ? 'success' : 'warning' }}"></i>
                                                             </button>
                                                         </form>

                                                        <!-- Edit Button (Direct Page) -->
                                                        <a href="{{ route('restaurant.dining-setup.tables.edit', $table->id) }}"
                                                           class="btn btn-sm btn-light border p-1 px-2 text-primary"
                                                           title="Edit Table">
                                                            <i class="feather-edit-2"></i>
                                                        </a>


                                                        <!-- Delete Button -->
                                                        <form action="{{ route('restaurant.dining-setup.tables.destroy', $table->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete table {{ $table->table_number }}?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-light border p-1 px-2 text-danger" title="Delete Table">
                                                                <i class="feather-trash-2"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                    <!-- ============================================================== -->
                    <!-- TAB 2: LIVE SLOT AVAILABILITY INSPECTOR -->
                    <!-- ============================================================== -->
                    <div class="tab-pane fade" id="slotsPane" role="tabpanel">
                        <!-- Date Filter Bar -->
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4 p-3 bg-light rounded-3 border">
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <label class="fw-bold fs-13 mb-0 me-2"><i class="feather-calendar me-1"></i> Inspect Date:</label>
                                @php
                                    $todayStr = now()->toDateString();
                                    $tomorrowStr = now()->addDay()->toDateString();
                                    $afterTomorrowStr = now()->addDays(2)->toDateString();
                                @endphp
                                <a href="{{ route('restaurant.dining-setup.index', ['date' => $todayStr]) }}"
                                   class="btn btn-sm {{ $selectedDate === $todayStr ? 'btn-primary' : 'btn-outline-secondary bg-white' }}">
                                    Today ({{ now()->format('d M') }})
                                </a>
                                <a href="{{ route('restaurant.dining-setup.index', ['date' => $tomorrowStr]) }}"
                                   class="btn btn-sm {{ $selectedDate === $tomorrowStr ? 'btn-primary' : 'btn-outline-secondary bg-white' }}">
                                    Tomorrow ({{ now()->addDay()->format('d M') }})
                                </a>
                                <a href="{{ route('restaurant.dining-setup.index', ['date' => $afterTomorrowStr]) }}"
                                   class="btn btn-sm {{ $selectedDate === $afterTomorrowStr ? 'btn-primary' : 'btn-outline-secondary bg-white' }}">
                                    {{ now()->addDays(2)->format('D, d M') }}
                                </a>
                            </div>

                            <form action="{{ route('restaurant.dining-setup.index') }}" method="GET" class="d-flex align-items-center gap-2">
                                <input type="date" name="date" class="form-control form-control-sm" value="{{ $selectedDate }}" min="{{ now()->toDateString() }}" onchange="this.form.submit()">
                                <button type="submit" class="btn btn-sm btn-dark">Load</button>
                            </form>
                        </div>

                        <div class="mb-3 d-flex align-items-center justify-content-between">
                            <h6 class="fw-bold mb-0">
                                Generated Time Slots for <span class="text-primary">{{ $selectedDateObj->format('l, F d, Y') }}</span>
                            </h6>
                            <div class="d-flex align-items-center gap-3 fs-12">
                                <span><i class="feather-circle text-success fill-success me-1"></i> Available</span>
                                <span><i class="feather-circle text-warning fill-warning me-1"></i> Limited</span>
                                <span><i class="feather-circle text-danger fill-danger me-1"></i> Fully Booked</span>
                            </div>
                        </div>

                        @if(empty($slotMatrix))
                            <div class="alert alert-warning">
                                <i class="feather-alert-circle me-1"></i> No slots could be generated. Please configure valid Opening and Closing times in the Dining Config tab.
                            </div>
                        @else
                            <div class="row g-3">
                                @foreach($slotMatrix as $slot)
                                    @php
                                        $statusClass = 'border-success bg-soft-success';
                                        $badgeClass = 'bg-success text-white';
                                        $statusText = 'Available';

                                        if ($slot['status'] === 'full') {
                                            $statusClass = 'border-danger bg-soft-danger';
                                            $badgeClass = 'bg-danger text-white';
                                            $statusText = 'Fully Booked';
                                        } elseif ($slot['status'] === 'limited') {
                                            $statusClass = 'border-warning bg-soft-warning';
                                            $badgeClass = 'bg-warning text-dark';
                                            $statusText = 'Fast Filling';
                                        }
                                    @endphp
                                    <div class="col-xxl-3 col-lg-4 col-md-6 col-12">
                                        <div class="card h-100 border shadow-none rounded-3 p-3 {{ $statusClass }}" style="border-width: 1.5px !important;">
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <h6 class="fw-bold mb-0 text-dark">
                                                    <i class="feather-clock me-1 text-primary"></i> {{ $slot['label'] }}
                                                </h6>
                                                <span class="badge {{ $badgeClass }} fs-11">{{ $statusText }}</span>
                                            </div>

                                            <div class="p-4 bg-white rounded-2 border mb-2 fs-12">
                                                <div class="d-flex justify-content-between mb-1">
                                                    <span class="text-muted">Total Tables:</span>
                                                    <span class="fw-bold text-dark">{{ $slot['total_tables'] }}</span>
                                                </div>
                                                <div class="d-flex justify-content-between mb-1">
                                                    <span class="text-muted">Booked:</span>
                                                    <span class="fw-bold text-danger">{{ $slot['booked_count'] }} Tables</span>
                                                </div>
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-muted">Free Tables:</span>
                                                    <span class="fw-bold text-success">{{ $slot['available_count'] }} Free</span>
                                                </div>
                                            </div>

                                            <div class="fs-11 text-muted">
                                                @if($slot['available_count'] > 0)
                                                    <i class="feather-check me-1 text-success"></i> Free tables ready for guests
                                                @else
                                                    <i class="feather-x me-1 text-danger"></i> All tables occupied for this slot
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- ============================================================== -->
                    <!-- TAB 3: DINING CONFIGURATION -->
                    <!-- ============================================================== -->
                    <div class="tab-pane fade" id="settingsPane" role="tabpanel">
                        <div class="row justify-content-center">
                            <div class="col-lg-8 col-12">
                                <div class="p-3 border rounded-3 bg-light mb-4">
                                    <h6 class="fw-bold mb-1">Configure Restaurant Dining & Slot System</h6>
                                    <p class="text-muted small mb-0">System automatically segments operating hours into reservation slots based on your slot duration.</p>
                                </div>

                                <form action="{{ route('restaurant.dining-setup.settings.update') }}" method="POST">
                                    @csrf
                                    <div class="row g-3">
                                        <!-- Opening Time -->
                                        <div class="col-md-6 col-12">
                                            <label class="form-label fw-bold">Opening Time <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-white"><i class="feather-sun text-warning"></i></span>
                                                <input type="time"
                                                       name="opening_time"
                                                       id="configOpeningTime"
                                                       class="form-control"
                                                       value="{{ $restaurant->getRawOriginal('opening_time') ? date('H:i', strtotime($restaurant->getRawOriginal('opening_time'))) : '11:00' }}"
                                                       required>
                                            </div>
                                            <small class="text-muted">E.g. 11:00 AM</small>
                                        </div>

                                        <!-- Closing Time -->
                                        <div class="col-md-6 col-12">
                                            <label class="form-label fw-bold">Closing Time <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-white"><i class="feather-moon text-primary"></i></span>
                                                <input type="time"
                                                       name="closing_time"
                                                       id="configClosingTime"
                                                       class="form-control"
                                                       value="{{ $restaurant->getRawOriginal('closing_time') ? date('H:i', strtotime($restaurant->getRawOriginal('closing_time'))) : '23:00' }}"
                                                       required>
                                            </div>
                                            <small class="text-muted">E.g. 11:00 PM</small>
                                        </div>

                                        <!-- Slot Duration -->
                                        <div class="col-md-6 col-12">
                                            <label class="form-label fw-bold">Slot Duration <span class="text-danger">*</span></label>
                                            <select name="slot_duration_minutes" id="configSlotDuration" class="form-select" required>
                                                <option value="15" {{ ($restaurant->slot_duration_minutes == 15) ? 'selected' : '' }}>15 Minutes</option>
                                                  <option value="30" {{ ($restaurant->slot_duration_minutes == 30) ? 'selected' : '' }}>30 Minutes</option>
                                                <option value="45" {{ ($restaurant->slot_duration_minutes == 45) ? 'selected' : '' }}>45 Minutes</option>
                                                <option value="60" {{ ($restaurant->slot_duration_minutes == 60 || !$restaurant->slot_duration_minutes) ? 'selected' : '' }}>60 Minutes (1 Hour - Recommended)</option>
                                                <option value="90" {{ ($restaurant->slot_duration_minutes == 90) ? 'selected' : '' }}>90 Minutes (1.5 Hours)</option>
                                                <option value="120" {{ ($restaurant->slot_duration_minutes == 120) ? 'selected' : '' }}>120 Minutes (2 Hours)</option>
                                            </select>
                                            <small class="text-muted">How long each table booking session lasts.</small>
                                        </div>

                                        <!-- Advance Booking Days -->
                                        <div class="col-md-6 col-12">
                                            <label class="form-label fw-bold">Advance Booking Window <span class="text-danger">*</span></label>
                                            <select name="advance_booking_days" class="form-select" required>
                                                <option value="3" {{ ($restaurant->advance_booking_days == 3) ? 'selected' : '' }}>Up to 3 Days in Advance</option>
                                                <option value="7" {{ ($restaurant->advance_booking_days == 7 || !$restaurant->advance_booking_days) ? 'selected' : '' }}>Up to 7 Days in Advance (Standard)</option>
                                                <option value="14" {{ ($restaurant->advance_booking_days == 14) ? 'selected' : '' }}>Up to 14 Days in Advance</option>
                                                <option value="30" {{ ($restaurant->advance_booking_days == 30) ? 'selected' : '' }}>Up to 30 Days in Advance</option>
                                            </select>
                                            <small class="text-muted">How far in advance customers can reserve tables.</small>
                                        </div>

                                        <div class="col-12 pt-3">
                                            <button type="submit" class="btn btn-primary fw-bold px-4">
                                                <i class="feather-save me-1"></i> Save Dining Configuration
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
@endsection

@push('scripts')
<script>
    $(function () {
        // Auto switch tab from URL hash
        if (window.location.hash) {
            var tabBtn = $('button[data-bs-target="' + window.location.hash + 'Pane"]');
            if (tabBtn.length && window.bootstrap && bootstrap.Tab) {
                var tab = new bootstrap.Tab(tabBtn[0]);
                tab.show();
            }
        }
    });
</script>
@endpush

