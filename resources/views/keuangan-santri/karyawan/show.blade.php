@extends('layouts.mobile.app')
@section('content')
    <style>
        :root {
            --primary-gradient-start: #17a2b8;
            --primary-gradient-end: #20c997;
            --card-bg: #ffffff;
            --text-dark: #2c3e50;
            --text-muted: #7f8c8d;
        }

        body {
            background: linear-gradient(135deg, var(--primary-gradient-start) 0%, var(--primary-gradient-end) 100%);
            min-height: 100vh;
        }

        #header-section {
            height: auto;
            padding: 20px;
            position: relative;
            background: transparent;
        }

        #section-back {
            position: absolute;
            left: 15px;
            top: 15px;
        }

        .back-btn {
            color: #ffffff;
            font-size: 30px;
            text-decoration: none;
            background: rgba(255,255,255,0.2);
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            backdrop-filter: blur(10px);
        }

        .back-btn:hover {
            background: rgba(255,255,255,0.3);
        }

        #header-title {
            text-align: center;
            color: #ffffff;
            margin-top: 10px;
        }

        #header-title h3 {
            font-weight: bold;
            margin: 0;
            font-size: 1.3rem;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        #header-title p {
            margin: 5px 0 0 0;
            opacity: 0.9;
            font-size: 0.9rem;
        }

        #content-section {
            padding: 20px 15px;
            margin-top: -10px;
        }

        .santri-info-card {
            background: white;
            border-radius: 20px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .santri-name {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 5px;
        }

        .santri-nis {
            font-size: 0.9rem;
            color: var(--text-muted);
            margin-bottom: 10px;
        }

        .badge-custom {
            padding: 5px 12px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
        }

        .badge-aktif {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .saldo-card {
            background: linear-gradient(135deg, var(--primary-gradient-start) 0%, var(--primary-gradient-end) 100%);
            border-radius: 15px;
            padding: 20px;
            margin: 15px 0;
            color: white;
            box-shadow: 0 6px 20px rgba(23, 162, 184, 0.3);
        }

        .saldo-label {
            font-size: 0.85rem;
            opacity: 0.9;
            margin-bottom: 8px;
        }

        .saldo-value {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .saldo-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            padding-top: 15px;
            border-top: 1px solid rgba(255,255,255,0.3);
        }

        .saldo-detail-item {
            text-align: center;
        }

        .saldo-detail-label {
            font-size: 0.75rem;
            opacity: 0.85;
            margin-bottom: 5px;
        }

        .saldo-detail-value {
            font-size: 1rem;
            font-weight: 600;
        }

        .filter-card {
            background: white;
            border-radius: 20px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        .filter-select {
            border: 2px solid rgba(23, 162, 184, 0.2);
            border-radius: 12px;
            padding: 10px;
            font-size: 0.9rem;
        }

        .filter-select:focus {
            border-color: var(--primary-gradient-start);
            box-shadow: 0 0 0 3px rgba(23, 162, 184, 0.1);
        }

        .transaction-card {
            background: white;
            border-radius: 15px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }

        .transaction-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.12);
        }

        .transaction-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 10px;
        }

        .transaction-date {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-bottom: 3px;
        }

        .transaction-code {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--primary-gradient-start);
        }

        .transaction-desc {
            font-size: 0.9rem;
            color: var(--text-dark);
            margin: 8px 0;
            font-weight: 600;
        }

        .transaction-note {
            font-size: 0.75rem;
            color: var(--text-muted);
            font-style: italic;
        }

        .transaction-amount {
            font-size: 1.2rem;
            font-weight: 700;
            text-align: right;
        }

        .amount-income {
            color: #10b981;
        }

        .amount-expense {
            color: #ef4444;
        }

        .badge-jenis {
            padding: 4px 10px;
            border-radius: 10px;
            font-size: 0.7rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .badge-pemasukan {
            background: rgba(16, 185, 129, 0.1);
            color: #059669;
        }

        .badge-pengeluaran {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
        }

        .category-badge {
            padding: 4px 10px;
            border-radius: 10px;
            font-size: 0.7rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            margin-top: 5px;
        }

        .saldo-after {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-top: 5px;
        }

        .no-data {
            text-align: center;
            padding: 40px 20px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        }

        .no-data-icon {
            font-size: 4rem;
            color: var(--text-muted);
            margin-bottom: 15px;
            opacity: 0.5;
        }

        .summary-header {
            background: white;
            border-radius: 15px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            text-align: center;
        }

        .summary-header h5 {
            font-size: 1rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 5px;
        }

        .summary-header p {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin: 0;
        }
    </style>

    <div id="header-section">
        <div id="section-back">
            <a href="{{ route('keuangan-santri.karyawan.index') }}" class="back-btn">
                <i class="ti ti-arrow-left"></i>
            </a>
        </div>
        <div id="header-title">
            <h3><i class="ti ti-history me-2"></i>Riwayat Transaksi</h3>
            <p>{{ $santri->nama_lengkap }}</p>
        </div>
    </div>

    <div id="content-section">
        {{-- Info Santri --}}
        <div class="santri-info-card">
            <div class="santri-name">{{ $santri->nama_lengkap }}</div>
            <div class="santri-nis">NIS: {{ $santri->nis }}</div>
            @if($santri->status_santri == 'aktif')
                <span class="badge-custom badge-aktif">Aktif</span>
            @else
                <span class="badge-custom" style="background: #6c757d; color: white;">
                    {{ ucfirst($santri->status_santri) }}
                </span>
            @endif

            @if($santri->keuanganSaldo)
                <div class="saldo-card">
                    <div class="saldo-label">Saldo Saat Ini</div>
                    <div class="saldo-value">
                        Rp {{ number_format($santri->keuanganSaldo->saldo_akhir, 0, ',', '.') }}
                    </div>
                    <div class="saldo-details">
                        <div class="saldo-detail-item">
                            <div class="saldo-detail-label">Total Setoran</div>
                            <div class="saldo-detail-value">
                                Rp {{ number_format($santri->keuanganSaldo->total_pemasukan, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="saldo-detail-item">
                            <div class="saldo-detail-label">Total Penarikan</div>
                            <div class="saldo-detail-value">
                                Rp {{ number_format($santri->keuanganSaldo->total_pengeluaran, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-warning mt-3">
                    <i class="ti ti-info-circle me-2"></i>Belum ada transaksi
                </div>
            @endif
        </div>

        {{-- Filter --}}
        <div class="filter-card">
            <form method="GET" action="{{ route('keuangan-santri.karyawan.show', $santri->id) }}">
                <div class="row g-2">
                    <div class="col-7">
                        <select name="periode" id="periodeSelect" class="form-select filter-select">
                            <option value="hari_ini" {{ request('periode', 'hari_ini') == 'hari_ini' ? 'selected' : '' }}>Hari Ini</option>
                            <option value="minggu_ini" {{ request('periode') == 'minggu_ini' ? 'selected' : '' }}>Minggu Ini</option>
                            <option value="bulan_ini" {{ request('periode') == 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
                            <option value="tahun_ini" {{ request('periode') == 'tahun_ini' ? 'selected' : '' }}>Tahun Ini</option>
                            <option value="semua" {{ request('periode') == 'semua' ? 'selected' : '' }}>Semua</option>
                        </select>
                    </div>
                    <div class="col-5">
                        <select name="jenis" class="form-select filter-select">
                            <option value="">Semua Jenis</option>
                            <option value="pemasukan" {{ request('jenis') == 'pemasukan' ? 'selected' : '' }}>Setoran</option>
                            <option value="pengeluaran" {{ request('jenis') == 'pengeluaran' ? 'selected' : '' }}>Penarikan</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ti ti-search me-1"></i> Terapkan Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Summary --}}
        <div class="summary-header">
            <h5>
                @if(request('periode') == 'semua')
                    Semua Transaksi
                @elseif(request('periode') == 'minggu_ini')
                    Transaksi Minggu Ini
                @elseif(request('periode') == 'bulan_ini')
                    Transaksi Bulan Ini
                @elseif(request('periode') == 'tahun_ini')
                    Transaksi Tahun Ini
                @else
                    Transaksi Hari Ini
                @endif
            </h5>
            <p>Total: {{ $transactions->total() }} transaksi</p>
        </div>

        {{-- List Transaksi --}}
        @forelse($transactions as $transaction)
            <div class="transaction-card">
                <div class="transaction-header">
                    <div style="flex: 1;">
                        <div class="transaction-date">
                            {{ $transaction->tanggal_transaksi->format('d M Y, H:i') }}
                        </div>
                        <div class="transaction-code">
                            {{ $transaction->kode_transaksi }}
                        </div>
                    </div>
                    <div>
                        <span class="badge-jenis badge-{{ $transaction->jenis == 'pemasukan' ? 'pemasukan' : 'pengeluaran' }}">
                            <i class="ti ti-arrow-{{ $transaction->jenis == 'pemasukan' ? 'up' : 'down' }}"></i>
                            {{ $transaction->jenis == 'pemasukan' ? 'Setor' : 'Tarik' }}
                        </span>
                    </div>
                </div>

                <div class="transaction-desc">
                    {{ $transaction->deskripsi }}
                </div>

                @if($transaction->catatan)
                    <div class="transaction-note">
                        {{ $transaction->catatan }}
                    </div>
                @endif

                @if($transaction->category)
                    <span class="category-badge" style="background: {{ $transaction->category->warna }}20; color: {{ $transaction->category->warna }};">
                        <i class="{{ $transaction->category->icon }}"></i>
                        {{ $transaction->category->nama_kategori }}
                    </span>
                @endif

                <div class="mt-3 d-flex justify-content-between align-items-center">
                    <div class="saldo-after">
                        Saldo: Rp {{ number_format($transaction->saldo_setelah, 0, ',', '.') }}
                    </div>
                    <div class="transaction-amount {{ $transaction->jenis == 'pemasukan' ? 'amount-income' : 'amount-expense' }}">
                        {{ $transaction->jenis == 'pemasukan' ? '+' : '-' }} 
                        Rp {{ number_format($transaction->jumlah, 0, ',', '.') }}
                    </div>
                </div>

                @if($transaction->creator)
                    <div style="font-size: 0.7rem; color: var(--text-muted); margin-top: 8px;">
                        <i class="ti ti-user me-1"></i>
                        Oleh: {{ $transaction->creator->name }}
                    </div>
                @endif
            </div>
        @empty
            <div class="no-data">
                <div class="no-data-icon">
                    <i class="ti ti-receipt-off"></i>
                </div>
                <p><strong>Tidak ada transaksi</strong></p>
                <small>Belum ada transaksi untuk periode yang dipilih</small>
            </div>
        @endforelse

        {{-- Pagination --}}
        @if($transactions->hasPages())
            <div style="background: white; padding: 15px; border-radius: 15px; margin-top: 20px;">
                {{ $transactions->links() }}
            </div>
        @endif

        <div style="height: 80px;"></div>
    </div>
@endsection
