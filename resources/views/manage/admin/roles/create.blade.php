@extends('layouts.admin.main')

@section('title', 'Add Role - Admin Dashboard')

@push('styles')
<style>
    :root {
        --za-red: #cb202d;
        --za-red-dark: #a51523;
        --za-soft: rgb(203 32 45 / 0.08);
    }

    /* ---------- Hero banner ---------- */
    .role-hero {
        background: linear-gradient(135deg, #8b0f1a 0%, #cb202d 55%, #ef5a50 100%);
        border-radius: 18px;
        padding: 28px 30px;
        color: #fff;
        position: relative;
        overflow: hidden;
        box-shadow: 0 12px 30px rgba(203, 32, 45, 0.25);
    }
    .role-hero::before {
        content: '';
        position: absolute;
        right: -60px;
        top: -60px;
        width: 220px;
        height: 220px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.08);
    }
    .role-hero::after {
        content: '';
        position: absolute;
        right: 60px;
        bottom: -90px;
        width: 160px;
        height: 160px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.06);
    }
    .role-hero-icon {
        width: 58px;
        height: 58px;
        border-radius: 16px;
        background: rgba(255, 255, 255, 0.16);
        backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        border: 1px solid rgba(255, 255, 255, 0.25);
    }
    .role-hero-chip {
        background: rgba(255, 255, 255, 0.16);
        border: 1px solid rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(4px);
        border-radius: 30px;
        padding: 5px 14px;
        font-size: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    /* ---------- Cards ---------- */
    .role-card {
        border: 1px solid #eceff3;
        border-radius: 18px;
        box-shadow: 0 6px 18px rgba(15, 23, 42, 0.05);
        transition: box-shadow 0.2s ease, transform 0.2s ease;
    }
    .role-card .card-head {
        border-bottom: 1px dashed #e7eaef;
        padding: 16px 22px;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .role-card .card-head .head-icon {
        width: 38px;
        height: 38px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
        color: #fff;
        background: linear-gradient(135deg, var(--za-red), var(--za-red-dark));
        flex-shrink: 0;
    }
    .input-icon-wrap {
        position: relative;
    }
    .input-icon-wrap > i,
        .input-icon-wrap > svg {
        position: absolute;
        left: 13px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 16px;
        pointer-events: none;
    }
    .input-icon-wrap .form-control,
    .input-icon-wrap .form-select {
        padding-left: 40px;
    }

    /* ---------- Form focus ---------- */
    .role-form .form-control:focus,
    .role-form .form-select:focus {
        border-color: var(--za-red);
        box-shadow: 0 0 0 0.2rem var(--za-soft);
    }

    /* ---------- Category section ---------- */
    .category-head {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0 0 14px;
    }
    .category-head .cat-line {
        flex: 1;
        height: 1px;
        background: linear-gradient(90deg, #e2e8f0, transparent);
    }

    /* ---------- Module cards ---------- */
    .module-card {
        border: 1px solid #eceff3;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 3px 10px rgba(15, 23, 42, 0.04);
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
        transition: box-shadow 0.2s ease, transform 0.2s ease;
    }
    .module-card:hover {
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.08);
        transform: translateY(-2px);
    }
    .module-card-accent {
        height: 4px;
        background: var(--accent, var(--za-red));
    }
    .module-card-head {
        padding: 13px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        flex-wrap: wrap;
        border-bottom: 1px solid #f0f2f5;
    }
    .module-card-head .m-icon {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 15px;
        flex-shrink: 0;
    }
    .module-count-chip {
        font-size: 11px;
        font-weight: 700;
        border-radius: 30px;
        padding: 2px 10px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .perm-row {
        border: 1px solid #e8ebef;
        border-radius: 10px;
        padding: 9px 12px;
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        background: #fff;
        transition: all 0.15s ease;
        margin: 0;
    }
    .perm-row:hover {
        border-color: var(--accent, var(--za-red));
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.06);
    }
    .perm-row:has(input:checked) {
        border-color: var(--accent, var(--za-red));
        background: var(--accent-soft, var(--za-soft));
    }
    .perm-row input[type="checkbox"] {
        width: 17px;
        height: 17px;
        margin: 0;
        flex-shrink: 0;
        accent-color: var(--za-red);
    }
    .perm-row input[type="checkbox"]:checked {
        accent-color: var(--accent, var(--za-red));
    }
    .perm-action-pill {
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        border-radius: 20px;
        padding: 2px 9px;
        display: inline-block;
        flex-shrink: 0;
    }

    .perm-search-box {
        position: relative;
    }
    .perm-search-box i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 14px;
    }
    .perm-search-box input {
        padding-left: 36px;
        border-radius: 30px;
        font-size: 13px;
    }

    .selected-count-chip {
        background: linear-gradient(135deg, var(--za-red), var(--za-red-dark));
        color: #fff;
        border-radius: 30px;
        padding: 5px 15px;
        font-weight: 700;
        font-size: 12px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 4px 10px rgba(203, 32, 45, 0.3);
    }
    .module-btn {
        font-size: 11px;
        font-weight: 700;
        border-radius: 8px;
        padding: 5px 12px;
        border: 1px solid transparent;
        transition: all 0.15s ease;
    }
    .module-btn.check {
        color: var(--accent, var(--za-red));
        border-color: var(--accent, var(--za-red));
        background: transparent;
    }
    .module-btn.check:hover {
        color: #fff;
        background: var(--accent, var(--za-red));
    }
    .module-btn.uncheck {
        color: #64748b;
        border-color: #e2e8f0;
        background: #f8fafc;
    }
    .module-btn.uncheck:hover {
        color: #334155;
        border-color: #cbd5e1;
        background: #f1f5f9;
    }

    /* ---------- Bottom action bar ---------- */
    .role-action-bar {
        position: sticky;
        bottom: 0;
        background: rgba(255, 255, 255, 0.92);
        backdrop-filter: blur(6px);
        border: 1px solid #eceff3;
        border-radius: 16px;
        padding: 14px 20px;
        margin-top: 10px;
        box-shadow: 0 -6px 20px rgba(15, 23, 42, 0.08);
        z-index: 5;
    }
    .btn-za {
        background: linear-gradient(135deg, var(--za-red), var(--za-red-dark));
        color: #fff;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        padding: 11px 26px;
        box-shadow: 0 6px 14px rgba(203, 32, 45, 0.3);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .btn-za:hover {
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 9px 18px rgba(203, 32, 45, 0.35);
    }
    .btn-outline-za {
        color: var(--za-red);
        border: 1px solid var(--za-red);
        border-radius: 10px;
        font-weight: 600;
        padding: 11px 22px;
        background: transparent;
    }
    .btn-outline-za:hover {
        background: var(--za-soft);
        color: var(--za-red-dark);
    }

    .help-tip {
        border-left: 3px solid var(--za-red);
        background: #fff7f7;
        border-radius: 10px;
        padding: 10px 12px;
        font-size: 12px;
    }

    /* ---------- Submit loading ---------- */
    .spinner {
        animation: nxl-spin 0.9s linear infinite;
        display: inline-block;
    }
    @keyframes nxl-spin {
        to { transform: rotate(360deg); }
    }
    .btn.is-submitting {
        opacity: 0.85;
        pointer-events: none;
        cursor: wait;
    }
</style>
@endpush

@section('content')
<div class="nxl-content">
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Add New Role</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.roles.index') }}">Roles</a></li>
                <li class="breadcrumb-item">Add New</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto d-flex align-items-center gap-2">
            <a href="{{ route('admin.roles.index') }}" class="btn btn-light border text-secondary fw-semibold">
                <i class="feather-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>
    <!-- [ page-header ] end -->

    <div class="main-content">

        @php
            $totalPermCount = $permissionStructure->sum(fn ($section) => collect($section['modules'])->sum('total'));
            $selectedPermCount = count(old('permissions', []));
            $palette = ['#cb202d', '#0ea5e9', '#10b981', '#f59e0b', '#8b5cf6', '#6366f1', '#ef4444', '#14b8a6', '#f43f5e'];
            $actionMeta = [
                'view' => ['label' => 'View', 'class' => 'bg-soft-primary text-primary'],
                'edit' => ['label' => 'Edit', 'class' => 'bg-soft-warning text-warning'],
                'delete' => ['label' => 'Delete', 'class' => 'bg-soft-danger text-danger'],
            ];
        @endphp

        <!-- [ Hero Banner ] -->
        <div class="role-hero mb-4 d-flex align-items-center gap-4 flex-wrap">
            <div class="role-hero-icon">
                <i class="feather-user-check"></i>
            </div>
            <div class="flex-grow-1" style="min-width: 220px;">
                <h4 class="fw-bold text-white mb-1">Create a New Role</h4>
                <p class="mb-0 text-white-50 fs-14">Define a role and grant it the exact access it needs across the admin panel.</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <span class="role-hero-chip"><i class="feather-layers me-1"></i>{{ $permissionStructure->count() }} Modules</span>
                <span class="role-hero-chip"><i class="feather-shield me-1"></i>{{ $totalPermCount }} Permissions</span>
                <span class="role-hero-chip"><i class="feather-zap me-1"></i>View · Edit · Delete</span>
            </div>
        </div>

        <form action="{{ route('admin.roles.store') }}" method="POST" class="role-form">
            @csrf

            <!-- [ Role Details ] -->
            <div class="row g-4 mb-4">
                <div class="col-lg-8">
                    <div class="card role-card">
                        <div class="card-head">
                            <div class="head-icon"><i class="feather-user"></i></div>
                            <div>
                                <h6 class="fw-bold text-dark mb-0">Role Details</h6>
                                <small class="text-muted">Basic information about the role</small>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label fw-semibold text-dark">Role Name <span class="text-danger">*</span></label>
                                    <div class="input-icon-wrap">
                                        <i class="feather-tag"></i>
                                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="e.g. Cashier, Manager, Delivery Partner">
                                    </div>
                                    @error('name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="slug" class="form-label fw-semibold text-dark">Slug <small class="text-muted">(Auto-generated from name)</small></label>
                                    <div class="input-icon-wrap">
                                        <i class="feather-link"></i>
                                        <input type="text" name="slug" id="slug" class="form-control slug-input @error('slug') is-invalid @enderror" value="{{ old('slug') }}" placeholder="cashier" style="padding-right: 42px;">
                                        <button type="button" class="btn btn-light border slug-refresh" data-bs-toggle="tooltip" title="Regenerate from Role Name" style="position: absolute; right: 4px; top: 50%; transform: translateY(-50%); padding: 2px 8px; font-size: 12px; line-height: 1; z-index: 2;">
                                            <i class="feather-refresh-cw"></i>
                                        </button>
                                    </div>
                                    @error('slug') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-12">
                                    <label for="description" class="form-label fw-semibold text-dark">Description</label>
                                    <div class="input-icon-wrap">
                                        <i class="feather-align-left" style="top: 18px; transform: none;"></i>
                                        <textarea name="description" id="description" rows="3" class="form-control @error('description') is-invalid @enderror" placeholder="A short summary of what this role is responsible for...">{{ old('description') }}</textarea>
                                    </div>
                                    @error('description') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card role-card h-100">
                        <div class="card-head">
                            <div class="head-icon" style="background: linear-gradient(135deg,#10b981,#059669);"><i class="feather-toggle-right"></i></div>
                            <div>
                                <h6 class="fw-bold text-dark mb-0">Status</h6>
                                <small class="text-muted">Activate instantly or save draft</small>
                            </div>
                        </div>
                        <div class="card-body p-4 d-flex flex-column gap-3">
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror

                            <div class="help-tip">
                                <i class="feather-info me-1 text-danger"></i>
                                <span class="fw-semibold text-dark d-block mb-1">Tip</span>
                                An inactive role can only be assigned once it is turned on. Users hold one role at a time.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- [ Permissions ] -->
            <div class="card role-card">
                <div class="card-head">
                    <div class="head-icon"><i class="feather-shield"></i></div>
                    <div>
                        <h6 class="fw-bold text-dark mb-0">Permissions</h6>
                        <small class="text-muted">Each module has its own card — enable access per module</small>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
                        <div class="selected-count-chip">
                            <i class="feather-check-square"></i>
                            <span id="selectedCountLabel">{{ $selectedPermCount }} / {{ $totalPermCount }}</span>
                            selected
                        </div>

                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <div class="perm-search-box">
                                <i class="feather-search"></i>
                                <input type="text" id="permissionSearch" class="form-control form-control-sm" placeholder="Search permission..." style="width: 220px;">
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-za" id="permissionCheckAll">
                                <i class="feather-check-square me-1"></i> Check All
                            </button>
                            <button type="button" class="btn btn-sm btn-light border text-secondary fw-semibold" id="permissionClearAll">
                                <i class="feather-x-square me-1"></i> Clear All
                            </button>
                        </div>
                    </div>

                    @error('permissions') <div class="alert alert-danger py-2 fs-13">{{ $message }}</div> @enderror

                    @php $moduleIndex = 0; @endphp
                    @foreach($permissionStructure as $section)
                        <div class="category-head">
                            <h6 class="fw-bold text-dark text-uppercase fs-12 mb-0" style="letter-spacing: 0.5px;">{{ $section['category'] }}</h6>
                            <div class="cat-line"></div>
                            <span class="badge bg-light text-secondary fs-11">{{ count($section['modules']) }} modules</span>
                        </div>

                        <div class="row g-3 mb-4">
                            @foreach($section['modules'] as $module)
                                @php
                                    $accent = $palette[$moduleIndex % count($palette)];
                                    $accentSoft = 'rgb(' . implode(',', array_map(fn ($h) => hexdec(substr($accent, $h, 2)), [1, 3, 5])) . ' / 0.08)';
                                @endphp
                                <div class="col-xl-6">
                                    <div class="module-card module-group" data-module-key="{{ $module['key'] }}"
                                         data-module-label="{{ strtolower($module['label']) }}" style="--accent: {{ $accent }}; --accent-soft: {{ $accentSoft }};">
                                        <div class="module-card-accent"></div>
                                        <div class="module-card-head">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="m-icon" style="background: {{ $accent }};">
                                                    <i class="{{ $module['icon'] }}"></i>
                                                </div>
                                                <div>
                                                    <h6 class="fw-bold text-dark mb-0">{{ $module['label'] }}</h6>
                                                    <small class="text-muted">{{ $module['total'] }} access level{{ $module['total'] !== 1 ? 's' : '' }}</small>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="module-count-chip" style="background: {{ $accentSoft }}; color: {{ $accent }};">
                                                    <span data-module-count="{{ $module['key'] }}">0</span>/{{ $module['total'] }}
                                                </span>
                                                <button type="button" class="module-btn check" data-module-key="{{ $module['key'] }}">
                                                    <i class="feather-check me-1"></i>Check All
                                                </button>
                                                <button type="button" class="module-btn uncheck" data-module-key="{{ $module['key'] }}">
                                                    <i class="feather-minus me-1"></i>Uncheck
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body p-3 pt-3" style="display: flex; flex-direction: column; gap: 8px;">
                                            @foreach($module['permissions'] as $permission)
                                                @php
                                                    $action = substr($permission->slug, strrpos($permission->slug, '.') + 1);
                                                    $actionCol = $actionMeta[$action] ?? ['label' => ucfirst($action), 'class' => 'bg-light text-secondary'];
                                                    $isChecked = in_array($permission->id, old('permissions', []));
                                                @endphp
                                                <label class="perm-row" data-search="{{ strtolower($permission->name . ' ' . $permission->description) }}">
                                                    <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                                           class="permission-check form-check-input m-0" data-module-key="{{ $module['key'] }}"
                                                           {{ $isChecked ? 'checked' : '' }}>
                                                    <span class="lh-sm flex-grow-1">
                                                        <span class="d-block fw-semibold text-dark fs-13">{{ $permission->name }}</span>
                                                        <span class="d-block text-muted fs-11">{{ $permission->description }}</span>
                                                    </span>
                                                    <span class="perm-action-pill {{ $actionCol['class'] }}">{{ $actionCol['label'] }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @php $moduleIndex++; @endphp
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- [ Action bar ] -->
            <div class="role-action-bar d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-2 text-muted fs-13">
                    <i class="feather-shield text-danger"></i>
                    Permissions are tied to the role &amp; apply to all users assigned to it.
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-za px-4">Cancel</a>
                    <button type="submit" class="btn btn-za px-5">
                        <i class="feather-check me-1"></i> Save Role
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        function slugify(str) {
            return str.toLowerCase().trim()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .replace(/^-+|-+$/g, '');
        }

        // Auto-generate slug from role name until the user edits it manually.
        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');
        let slugTouched = false;

        if (nameInput && slugInput) {
            slugInput.addEventListener('input', function () {
                slugTouched = this.value.length > 0;
            });
            nameInput.addEventListener('input', function () {
                if (!slugTouched) {
                    slugInput.value = slugify(this.value);
                }
            });
            document.querySelectorAll('.slug-refresh').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    slugTouched = false;
                    slugInput.value = slugify(nameInput.value);
                });
            });
        }

        const checkboxes = document.querySelectorAll('.permission-check');
        const searchInput = document.getElementById('permissionSearch');
        const selectedLabel = document.getElementById('selectedCountLabel');
        const totalCount = {{ $totalPermCount }};

        function updateCounter() {
            const selected = document.querySelectorAll('.permission-check:checked').length;
            if (selectedLabel) selectedLabel.textContent = selected + ' / ' + totalCount;

            document.querySelectorAll('[data-module-count]').forEach(function (chip) {
                const key = chip.getAttribute('data-module-count');
                chip.textContent = document.querySelectorAll('.permission-check:checked[data-module-key="' + key + '"]').length;
            });
        }

        function moduleChecks(key) {
            return document.querySelectorAll('.permission-check[data-module-key="' + key + '"]');
        }

        // Global check / clear
        document.getElementById('permissionCheckAll').addEventListener('click', function () {
            checkboxes.forEach(function (cb) { cb.checked = true; });
            updateCounter();
        });
        document.getElementById('permissionClearAll').addEventListener('click', function () {
            checkboxes.forEach(function (cb) { cb.checked = false; });
            updateCounter();
        });

        // Per-module check / uncheck all
        document.querySelectorAll('.module-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const key = btn.getAttribute('data-module-key');
                const checked = btn.classList.contains('check');
                moduleChecks(key).forEach(function (cb) { cb.checked = checked; });
                updateCounter();
            });
        });

        // Live counter on toggle
        checkboxes.forEach(function (cb) {
            cb.addEventListener('change', updateCounter);
        });

        // Search filter (hides whole module cards)
        if (searchInput) {
            searchInput.addEventListener('input', function () {
                const q = this.value.trim().toLowerCase();
                document.querySelectorAll('.module-card').forEach(function (card) {
                    let visible = false;
                    card.querySelectorAll('.perm-row').forEach(function (row) {
                        const hay = row.getAttribute('data-search');
                        const show = !q || hay.indexOf(q) !== -1;
                        row.style.display = show ? '' : 'none';
                        if (show) visible = true;
                    });
                    card.closest('.col-xl-6').style.display = (!q || visible) ? '' : 'none';
                    document.querySelectorAll('.category-head').forEach(function (head) {
                        const section = head.nextElementSibling;
                        const anyVisible = section && Array.from(section.querySelectorAll('.col-xl-6')).some(function (col) {
                            return col.style.display !== 'none';
                        });
                        head.style.display = (!q || anyVisible) ? '' : 'none';
                        if (section) section.style.display = (!q || anyVisible) ? '' : 'none';
                    });
                });
            });
        }

        updateCounter();

        // Submit loading state on role forms
        document.querySelectorAll('form.role-form').forEach(function (form) {
            form.addEventListener('submit', function () {
                const btn = form.querySelector('button[type="submit"]');
                if (btn && !btn.disabled) {
                    btn.disabled = true;
                    btn.classList.add('is-submitting');
                    btn.innerHTML = '<i class="feather-loader spinner me-1 text-white"></i>  <span class="text-white">Saving...<span>';
                }
            });
        });
    })();
</script>
@endpush