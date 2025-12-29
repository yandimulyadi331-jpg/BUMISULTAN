sem_remove@extends('layouts.app')
@section('titlepage', 'Atur Permission Role')

@section('content')
@section('navigasi')
    <span class="text-muted fw-light">Settings</span> / 
    <span class="text-muted fw-light">Roles</span> / 
    <span class="text-primary">Atur Permission - {{ ucwords($role->name) }}</span>
@endsection

<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-light border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-1">
                                <ion-icon name="shield-outline" class="me-2"></ion-icon>
                                Manajemen Permission Role: <span class="badge bg-primary">{{ ucwords($role->name) }}</span>
                            </h4>
                            <p class="text-muted mb-0">
                                Pilih permission yang akan diberikan ke role ini. Total: <strong>{{ count($rolePermissions) }} permission aktif</strong> dari <strong>{{ $permissionGroups->sum(fn($g) => count($g['permissions'])) }} total</strong>
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary">
                                <ion-icon name="arrow-back-outline" class="me-1"></ion-icon>
                                Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Start -->
    <form action="{{ route('roles.updatePermissions', Crypt::encrypt($role->id)) }}" method="POST" id="permissionForm">
        @csrf
        @method('PUT')

        <!-- Quick Actions -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="d-flex gap-2 flex-wrap">
                    <button type="button" class="btn btn-sm btn-outline-primary" id="selectAllBtn">
                        <ion-icon name="checkmark-done-outline" class="me-1"></ion-icon>
                        Pilih Semua
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger" id="deselectAllBtn">
                        <ion-icon name="close-circle-outline" class="me-1"></ion-icon>
                        Batal Semua
                    </button>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-info" id="filterCRUDBtn" data-filter="crud">
                            <ion-icon name="document-text-outline" class="me-1"></ion-icon>
                            CRUD Only
                        </button>
                        <button type="button" class="btn btn-outline-info" id="filterAllBtn" data-filter="all">
                            <ion-icon name="filter-outline" class="me-1"></ion-icon>
                            Tampilkan Semua
                        </button>
                    </div>
                    <div class="ms-auto">
                        <input type="text" class="form-control form-control-sm" id="searchPermissions" 
                               placeholder="Cari permission..." style="width: 250px;">
                    </div>
                </div>
            </div>
        </div>

        <!-- Notification -->
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Permission Groups Container -->
        <div id="permissionGroupsContainer" class="row g-3">
            @forelse ($permissionGroups as $group)
                <div class="col-lg-4 col-md-6 col-12 permission-group-card" data-group-id="{{ $group['id'] }}">
                    <div class="card h-100 border-2 border-light">
                        <!-- Group Header -->
                        <div class="card-header bg-gradient sticky-top" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0 text-white d-flex align-items-center">
                                    <ion-icon name="folder-outline" class="me-2" style="font-size: 1.3em;"></ion-icon>
                                    {{ $group['name'] }}
                                    <span class="badge bg-white text-dark ms-2">{{ count($group['permissions']) }}</span>
                                </h5>
                            </div>
                            <div class="form-check mt-2">
                                <input class="form-check-input group-select-all" type="checkbox" 
                                       id="selectGroup{{ $group['id'] }}" data-group-id="{{ $group['id'] }}">
                                <label class="form-check-label text-white small" for="selectGroup{{ $group['id'] }}">
                                    Pilih Semua Modul Ini
                                </label>
                            </div>
                        </div>

                        <!-- Group Body -->
                        <div class="card-body permission-list" style="max-height: 600px; overflow-y: auto;">
                            @forelse ($group['permissions'] as $permission)
                                <div class="permission-item mb-2" data-permission-name="{{ $permission['name'] }}">
                                    <div class="form-check">
                                        <input class="form-check-input permission-checkbox group-{{ $group['id'] }}" 
                                               type="checkbox" 
                                               name="permissions[]"
                                               value="{{ $permission['name'] }}"
                                               id="perm{{ $permission['id'] }}"
                                               data-action="{{ explode('.', $permission['name'])[1] ?? 'other' }}"
                                               @if($permission['is_assigned']) checked @endif>
                                        <label class="form-check-label" for="perm{{ $permission['id'] }}">
                                            <small class="text-muted">{{ $permission['name'] }}</small>
                                        </label>
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted text-center py-3">
                                    <ion-icon name="alert-circle-outline"></ion-icon>
                                    Tidak ada permission
                                </p>
                            @endforelse
                        </div>

                        <!-- Group Footer Stats -->
                        <div class="card-footer bg-light small text-muted">
                            <span class="group-count" data-group-id="{{ $group['id'] }}">0</span> / {{ count($group['permissions']) }} dipilih
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-warning">
                        <ion-icon name="alert-circle-outline" class="me-2"></ion-icon>
                        Tidak ada permission group ditemukan. Jalankan seeder untuk membuat permission.
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Action Bar -->
        <div class="row mt-4 sticky-bottom bg-white p-3 border-top">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted">
                            Total Permission Dipilih: <strong id="totalSelected">{{ count($rolePermissions) }}</strong> / {{ $permissionGroups->sum(fn($g) => count($g['permissions'])) }}
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                            <ion-icon name="close-outline" class="me-1"></ion-icon>
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <ion-icon name="checkmark-done-outline" class="me-1"></ion-icon>
                            Simpan Permission
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Statistics Section -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <ion-icon name="stats-chart-outline" class="me-2"></ion-icon>
                    Statistik Permission
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3 col-6 mb-3">
                        <div class="p-2 bg-light rounded">
                            <h6 class="text-muted mb-1">Total Permission</h6>
                            <h3 class="text-primary mb-0">{{ $permissionGroups->sum(fn($g) => count($g['permissions'])) }}</h3>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="p-2 bg-light rounded">
                            <h6 class="text-muted mb-1">Permission Groups</h6>
                            <h3 class="text-info mb-0">{{ count($permissionGroups) }}</h3>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="p-2 bg-light rounded">
                            <h6 class="text-muted mb-1">Dipilih</h6>
                            <h3 class="text-success mb-0" id="statSelected">{{ count($rolePermissions) }}</h3>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="p-2 bg-light rounded">
                            <h6 class="text-muted mb-1">Coverage %</h6>
                            <h3 class="text-warning mb-0" id="statCoverage">
                                {{ round(count($rolePermissions) / $permissionGroups->sum(fn($g) => count($g['permissions'])) * 100, 1) }}%
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card-header.sticky-top {
        z-index: 10;
        top: 0;
    }
    
    .sticky-bottom {
        position: sticky;
        bottom: 0;
        box-shadow: 0 -2px 4px rgba(0,0,0,0.1);
    }
    
    .permission-item {
        padding: 0.5rem 0;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        transition: all 0.2s ease;
    }
    
    .permission-item:last-child {
        border-bottom: none;
    }
    
    .permission-item:hover {
        background-color: rgba(102, 126, 234, 0.05);
        border-radius: 4px;
        padding-left: 0.25rem;
        padding-right: 0.25rem;
    }
    
    .permission-group-card {
        transition: all 0.3s ease;
    }
    
    .permission-group-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
    
    .group-select-all {
        cursor: pointer;
    }
    
    .permission-checkbox:checked {
        background-color: #667eea;
        border-color: #667eea;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('permissionForm');
    const selectAllBtn = document.getElementById('selectAllBtn');
    const deselectAllBtn = document.getElementById('deselectAllBtn');
    const filterCRUDBtn = document.getElementById('filterCRUDBtn');
    const filterAllBtn = document.getElementById('filterAllBtn');
    const searchInput = document.getElementById('searchPermissions');
    const totalSelectedSpan = document.getElementById('totalSelected');
    const groupSelectAllCheckboxes = document.querySelectorAll('.group-select-all');
    const permissionCheckboxes = document.querySelectorAll('.permission-checkbox');

    // Update counter
    function updateCounter() {
        const checked = document.querySelectorAll('.permission-checkbox:checked').length;
        const total = document.querySelectorAll('.permission-checkbox').length;
        totalSelectedSpan.textContent = checked;
        document.getElementById('statSelected').textContent = checked;
        document.getElementById('statCoverage').textContent = (checked / total * 100).toFixed(1) + '%';

        // Update group counters
        document.querySelectorAll('.permission-group-card').forEach(card => {
            const groupId = card.dataset.groupId;
            const groupChecked = card.querySelectorAll('.permission-checkbox:checked').length;
            const groupTotal = card.querySelectorAll('.permission-checkbox').length;
            card.querySelector('.group-count').textContent = groupChecked;
        });
    }

    // Select All
    selectAllBtn.addEventListener('click', function() {
        permissionCheckboxes.forEach(cb => cb.checked = true);
        groupSelectAllCheckboxes.forEach(cb => cb.checked = true);
        updateCounter();
    });

    // Deselect All
    deselectAllBtn.addEventListener('click', function() {
        permissionCheckboxes.forEach(cb => cb.checked = false);
        groupSelectAllCheckboxes.forEach(cb => cb.checked = false);
        updateCounter();
    });

    // Filter CRUD
    filterCRUDBtn.addEventListener('click', function() {
        permissionCheckboxes.forEach(cb => {
            const action = cb.dataset.action;
            const item = cb.closest('.permission-item');
            if (['index', 'create', 'show', 'edit', 'delete'].includes(action)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    });

    // Filter All
    filterAllBtn.addEventListener('click', function() {
        document.querySelectorAll('.permission-item').forEach(item => {
            item.style.display = '';
        });
    });

    // Search Permissions
    searchInput.addEventListener('keyup', function() {
        const query = this.value.toLowerCase();
        permissionCheckboxes.forEach(cb => {
            const label = cb.nextElementSibling.textContent.toLowerCase();
            const item = cb.closest('.permission-item');
            if (label.includes(query)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    });

    // Group Select All
    groupSelectAllCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const groupId = this.dataset.groupId;
            const isChecked = this.checked;
            document.querySelectorAll(`.group-${groupId}`).forEach(cb => {
                cb.checked = isChecked;
            });
            updateCounter();
        });
    });

    // Individual checkbox change
    permissionCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateCounter();
        });
    });

    // Initialize counter
    updateCounter();
});
</script>
@endsection
