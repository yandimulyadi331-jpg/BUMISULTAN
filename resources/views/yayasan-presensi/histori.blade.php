@extends('layouts.mobile.app')
@section('content')
    <style>
        :root {
            --bg-primary: #f8f9fa;
            --bg-secondary: #ffffff;
            --text-primary: #1a202c;
            --text-secondary: #718096;
            --border-color: #e2e8f0;
        }

        body {
            background-color: var(--bg-primary);
        }

        .header-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            margin-bottom: 20px;
            border-radius: 0 0 15px 15px;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .history-card {
            background: var(--bg-secondary);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
            border-left: 4px solid #667eea;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .history-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
        }

        .history-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .tanggal {
            font-weight: 600;
            color: var(--text-primary);
            font-size: 14px;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-h {
            background-color: #d4edda;
            color: #155724;
        }

        .status-i {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .status-s {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-a {
            background-color: #f8d7da;
            color: #721c24;
        }

        .history-detail {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid var(--border-color);
        }

        .detail-item {
            font-size: 13px;
        }

        .detail-label {
            color: var(--text-secondary);
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .detail-value {
            color: var(--text-primary);
            font-weight: 600;
            font-size: 14px;
        }

        .search-section {
            background: white;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .search-section input {
            border-radius: 8px;
            border: 1px solid var(--border-color);
            padding: 10px 12px;
            width: 100%;
            font-size: 14px;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: var(--text-secondary);
        }

        .empty-icon {
            font-size: 48px;
            margin-bottom: 10px;
            opacity: 0.5;
        }
    </style>

    <div class="header-section">
        <div style="display: flex; align-items: center; margin-bottom: 20px;">
            <a href="javascript:history.back()" class="btn-icon me-3">
                <i class="ti ti-arrow-left" style="font-size: 24px;"></i>
            </a>
            <h1 style="margin: 0; font-size: 24px;">Histori Presensi Yayasan</h1>
        </div>
    </div>

    <div style="padding: 0 15px;">
        <div class="search-section">
            <form method="GET" action="{{ route('yayasan-presensi.histori') }}">
                <div style="display: flex; gap: 10px;">
                    <input type="date" name="dari" value="{{ request('dari') }}" placeholder="Dari Tanggal">
                    <input type="date" name="sampai" value="{{ request('sampai') }}" placeholder="Sampai Tanggal">
                    <button type="submit" class="btn btn-sm btn-primary">Cari</button>
                </div>
            </form>
        </div>

        @if ($datapresensi->count() > 0)
            @foreach ($datapresensi as $d)
                <div class="history-card">
                    <div class="history-header">
                        <span class="tanggal">{{ date('d M Y', strtotime($d->tanggal)) }}</span>
                        <span class="status-badge status-{{ $d->status }}">
                            {{ $d->status == 'h' ? 'Hadir' : ($d->status == 'i' ? 'Izin' : ($d->status == 's' ? 'Sakit' : 'Alpha')) }}
                        </span>
                    </div>

                    <div class="history-detail">
                        <div class="detail-item">
                            <div class="detail-label">Jam Masuk</div>
                            <div class="detail-value">
                                {{ $d->jam_in ? date('H:i', strtotime($d->jam_in)) : '-' }}
                            </div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Jam Pulang</div>
                            <div class="detail-value">
                                {{ $d->jam_out ? date('H:i', strtotime($d->jam_out)) : '-' }}
                            </div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Jam Kerja</div>
                            <div class="detail-value">
                                {{ $d->nama_jam_kerja ?? '-' }}
                            </div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Total Jam</div>
                            <div class="detail-value">
                                {{ $d->total_jam ?? '-' }}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="ti ti-inbox"></i>
                </div>
                <p>Tidak ada data presensi</p>
            </div>
        @endif
    </div>
@endsection
