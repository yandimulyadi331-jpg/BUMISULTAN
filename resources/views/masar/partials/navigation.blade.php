<!-- Majlis Ta'lim Navigation Tabs -->
<div class="card mb-3">
    <div class="card-body">
        <ul class="nav nav-pills" role="tablist">
            <li class="nav-item" role="presentation">
                <a href="{{ route('masar.jamaah.index') }}" 
                   class="nav-link {{ !request()->has('jenis_kelamin') && request()->is('masar/jamaah') ? 'active' : '' }}">
                    <i class="ti ti-users me-1"></i> Semua Jamaah
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a href="{{ route('masar.jamaah.index') }}?jenis_kelamin=laki-laki" 
                   class="nav-link {{ request('jenis_kelamin') === 'laki-laki' ? 'active' : '' }}">
                    <i class="ti ti-user me-1"></i> Jamaah Laki-laki
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a href="{{ route('masar.jamaah.index') }}?jenis_kelamin=perempuan" 
                   class="nav-link {{ request('jenis_kelamin') === 'perempuan' ? 'active' : '' }}">
                    <i class="ti ti-user-circle me-1"></i> Jamaah Perempuan
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a href="{{ route('masar.hadiah.index') }}" 
                   class="nav-link {{ request()->is('masar/hadiah*') ? 'active' : '' }}">
                    <i class="ti ti-gift me-1"></i> Hadiah
                </a>
            </li>
            <li class="nav-item dropdown" role="presentation">
                <a class="nav-link dropdown-toggle {{ request()->is('masar/laporan*') ? 'active' : '' }}" 
                   data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">
                    <i class="ti ti-file-report me-1"></i> Laporan
                </a>
                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item" href="{{ route('masar.laporan.stokUkuran') }}">
                            <i class="ti ti-chart-bar me-1"></i> Laporan Stok Per Ukuran
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('masar.laporan.rekapDistribusi') }}">
                            <i class="ti ti-file-report me-1"></i> Rekapitulasi Distribusi
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</div>
