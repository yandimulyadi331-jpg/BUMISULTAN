<!-- Majlis Ta'lim Navigation Tabs -->
<div class="card mb-3">
    <div class="card-body">
        <ul class="nav nav-pills" role="tablist">
            <li class="nav-item" role="presentation">
                <a href="{{ route('majlistaklim.hadiah.index') }}" 
                   class="nav-link {{ request()->is('majlistaklim/hadiah*') ? 'active' : '' }}">
                    <i class="ti ti-gift me-1"></i> Hadiah
                </a>
            </li>
            <li class="nav-item dropdown" role="presentation">
                <a class="nav-link dropdown-toggle {{ request()->is('majlistaklim/laporan*') ? 'active' : '' }}" 
                   data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">
                    <i class="ti ti-file-report me-1"></i> Laporan
                </a>
                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item" href="{{ route('majlistaklim.laporan.stokUkuran') }}">
                            <i class="ti ti-chart-bar me-1"></i> Laporan Stok Per Ukuran
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('majlistaklim.laporan.rekapDistribusi') }}">
                            <i class="ti ti-file-report me-1"></i> Rekapitulasi Distribusi
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</div>
