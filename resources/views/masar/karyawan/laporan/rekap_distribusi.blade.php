@extends('layouts.mobile.app')
@section('content')
<style>
    :root {
        --primary-color: #2F5D62;
        --bg-color: #e8f0f2;
        --shadow-light: #ffffff;
        --shadow-dark: #c5d3d5;
    }

    body {
        background-color: var(--bg-color);
        min-height: 100vh;
    }

    #app-body {
        background-color: var(--bg-color);
        min-height: 100vh;
        padding-bottom: 80px;
    }

    #header-section {
        padding: 20px;
        background-color: var(--bg-color);
        position: relative;
    }

    .logo-wrapper {
        text-align: center;
        padding: 15px 0;
    }

    .logo-title {
        display: block;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 5px;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
    }

    .logo-subtitle {
        display: block;
        font-size: 0.9rem;
        color: #5a7c80;
        font-weight: 500;
    }

    .back-btn {
        position: absolute;
        left: 20px;
        top: 20px;
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: var(--bg-color);
        box-shadow: 8px 8px 16px var(--shadow-dark), -8px -8px 16px var(--shadow-light);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-color);
        font-size: 24px;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .back-btn:active {
        box-shadow: inset 4px 4px 8px var(--shadow-dark), inset -4px -4px 8px var(--shadow-light);
    }

    .filter-section {
        background: var(--bg-color);
        padding: 0 20px 20px 20px;
    }

    .search-box {
        position: relative;
        margin-bottom: 15px;
    }

    .search-box input {
        width: 100%;
        padding: 12px 15px 12px 45px;
        border: none;
        border-radius: 15px;
        background: var(--bg-color);
        box-shadow: inset 4px 4px 8px var(--shadow-dark), inset -4px -4px 8px var(--shadow-light);
        font-size: 14px;
        color: var(--primary-color);
    }

    .search-box input::placeholder {
        color: #5a7c80;
    }

    .search-box ion-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 20px;
        color: #5a7c80;
    }

    .filter-row {
        display: flex;
        gap: 10px;
        align-items: center;
        margin-bottom: 10px;
    }

    .filter-dropdown {
        flex: 1;
        position: relative;
    }

    .filter-dropdown select {
        width: 100%;
        padding: 10px 30px 10px 12px;
        border: none;
        border-radius: 12px;
        background: var(--bg-color);
        box-shadow: 6px 6px 12px var(--shadow-dark), -6px -6px 12px var(--shadow-light);
        font-size: 13px;
        color: var(--primary-color);
        appearance: none;
        cursor: pointer;
    }

    .filter-dropdown::after {
        content: '▼';
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 10px;
        color: #5a7c80;
        pointer-events: none;
    }

    .clear-btn {
        padding: 10px 18px;
        background: var(--bg-color);
        border: none;
        border-radius: 12px;
        font-size: 13px;
        color: var(--primary-color);
        font-weight: 600;
        cursor: pointer;
        box-shadow: 6px 6px 12px var(--shadow-dark), -6px -6px 12px var(--shadow-light);
        transition: all 0.3s ease;
    }

    .clear-btn:active {
        box-shadow: inset 3px 3px 6px var(--shadow-dark), inset -3px -3px 6px var(--shadow-light);
    }

    #content-section {
        padding: 0 20px 20px 20px;
    }

    .table-wrapper {
        background: var(--bg-color);
        border-radius: 20px;
        padding: 15px;
        box-shadow: 8px 8px 16px var(--shadow-dark), -8px -8px 16px var(--shadow-light);
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    thead {
        background: var(--bg-color);
    }

    th {
        padding: 12px 8px;
        text-align: left;
        font-size: 0.7rem;
        font-weight: 700;
        color: var(--primary-color);
        text-transform: uppercase;
        border-bottom: 2px solid #d0dfe1;
        white-space: nowrap;
    }

    tbody tr {
        transition: all 0.2s ease;
    }

    tbody tr:hover {
        background: rgba(47, 93, 98, 0.05);
    }

    td {
        padding: 12px 8px;
        font-size: 0.8rem;
        color: #5a7c80;
        border-bottom: 1px solid #d0dfe1;
    }

    tbody tr:last-child td {
        border-bottom: none;
    }

    .penerima-cell {
        font-weight: 600;
        color: var(--primary-color);
    }

    .penerima-type {
        display: block;
        font-size: 0.65rem;
        color: #5a7c80;
        font-weight: 400;
        margin-top: 2px;
    }

    .status-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 0.65rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .status-diterima {
        background: linear-gradient(145deg, #4ade80, #22c55e);
        color: white;
    }

    .status-pending {
        background: linear-gradient(145deg, #fbbf24, #f59e0b);
        color: white;
    }

    .status-ditolak {
        background: linear-gradient(145deg, #f87171, #ef4444);
        color: white;
    }

    .hadiah-cell {
        font-weight: 600;
        color: var(--primary-color);
    }

    .jumlah-cell {
        font-size: 0.7rem;
        color: #5a7c80;
    }

    .petugas-cell {
        font-size: 0.75rem;
        color: #5a7c80;
    }

    .tanggal-cell {
        font-size: 0.75rem;
        color: #5a7c80;
        white-space: nowrap;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 20px;
        border-radius: 50%;
        background: var(--bg-color);
        box-shadow: inset 4px 4px 8px var(--shadow-dark), inset -4px -4px 8px var(--shadow-light);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .empty-icon ion-icon {
        font-size: 40px;
        color: #5a7c80;
        opacity: 0.5;
    }

    .empty-state h5 {
        color: var(--primary-color);
        margin-bottom: 8px;
        font-weight: 700;
    }

    .empty-state p {
        color: #5a7c80;
        font-size: 0.9rem;
    }
</style>
</style>

<div id="app-body">
    <!-- Header -->
    <div id="header-section">
        <div class="logo-wrapper">
            <span class="logo-title">Rekap Distribusi Hadiah</span>
            <span class="logo-subtitle">MASAR</span>
        </div>
        <a href="{{ route('masar.karyawan.laporan.index') }}" class="back-btn">
            <ion-icon name="arrow-back-outline"></ion-icon>
        </a>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <div class="search-box">
            <ion-icon name="search-outline"></ion-icon>
            <input type="text" id="searchInput" placeholder="Cari penerima atau hadiah...">
        </div>

        <div class="filter-row">
            <div class="filter-dropdown">
                <select id="statusFilter">
                    <option value="">Semua Status</option>
                    <option value="diterima">Diterima</option>
                    <option value="pending">Pending</option>
                    <option value="ditolak">Ditolak</option>
                </select>
            </div>
            <div class="filter-dropdown">
                <select id="categoryFilter">
                    <option value="">Semua</option>
                    <option value="jamaah">Jamaah</option>
                    <option value="umum">Non-Jamaah</option>
                </select>
            </div>
            <button class="clear-btn" onclick="clearFilters()">Reset</button>
        </div>
    </div>

    <!-- Content Section -->
    <div id="content-section">
        <div class="table-wrapper">
            @if($distribusiList->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Penerima</th>
                            <th>Status</th>
                            <th>Hadiah</th>
                            <th>Jumlah</th>
                            <th>Petugas</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        @foreach($distribusiList as $distribusi)
                            <tr data-status="{{ $distribusi->status_distribusi }}" 
                                data-category="{{ $distribusi->jamaah_id ? 'jamaah' : 'umum' }}"
                                data-search="{{ strtolower($distribusi->penerima . ' ' . ($distribusi->hadiah->nama_hadiah ?? '')) }}">
                                
                                <td class="penerima-cell">
                                    {{ $distribusi->penerima }}
                                    <span class="penerima-type">{{ $distribusi->jamaah_id ? 'Jamaah' : 'Umum' }}</span>
                                </td>
                                
                                <td>
                                    <span class="status-badge status-{{ $distribusi->status_distribusi }}">
                                        {{ ucfirst($distribusi->status_distribusi) }}
                                    </span>
                                </td>
                                
                                <td class="hadiah-cell">
                                    {{ $distribusi->hadiah->nama_hadiah ?? '-' }}
                                </td>
                                
                                <td class="jumlah-cell">
                                    {{ $distribusi->jumlah }}x {{ $distribusi->ukuran ? "({$distribusi->ukuran})" : '' }}
                                </td>
                                
                                <td class="petugas-cell">
                                    {{ $distribusi->petugas_distribusi ?? 'Admin' }}
                                </td>
                                
                                <td class="tanggal-cell">
                                    {{ date('d/m/Y', strtotime($distribusi->tanggal_distribusi)) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <ion-icon name="document-text-outline"></ion-icon>
                    </div>
                    <h5>Belum ada data distribusi</h5>
                    <p>Data distribusi hadiah akan muncul di sini</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const categoryFilter = document.getElementById('categoryFilter');
    const tableRows = document.querySelectorAll('#tableBody tr');

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const statusValue = statusFilter.value;
        const categoryValue = categoryFilter.value;

        tableRows.forEach(row => {
            const searchData = row.getAttribute('data-search') || '';
            const statusData = row.getAttribute('data-status') || '';
            const categoryData = row.getAttribute('data-category') || '';

            const matchesSearch = searchData.includes(searchTerm);
            const matchesStatus = !statusValue || statusData === statusValue;
            const matchesCategory = !categoryValue || categoryData === categoryValue;

            if (matchesSearch && matchesStatus && matchesCategory) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    function clearFilters() {
        searchInput.value = '';
        statusFilter.value = '';
        categoryFilter.value = '';
        filterTable();
    }

    searchInput.addEventListener('input', filterTable);
    statusFilter.addEventListener('change', filterTable);
    categoryFilter.addEventListener('change', filterTable);
</script>
@endsection

