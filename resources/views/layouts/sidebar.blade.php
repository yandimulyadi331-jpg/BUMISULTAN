 <!-- Menu -->

 <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
     <div class="app-brand demo" style="padding-left: 15px; margin-left: 0; display: flex; align-items: center; justify-content: center; gap: 4px;">
         <span class="app-brand-logo demo" style="flex-shrink: 0; margin-right: 2px;">
             <img src="{{ asset('assets/template/img/logo/logobumisultan.png') }}" alt="BumisultanAPP" width="70" height="70" style="object-fit: contain;">
         </span>
         
         <a href="index.html" class="app-brand-link" style="padding: 0; flex-grow: 0;">
             <span class="app-brand-text demo menu-text fw-bold" style="white-space: nowrap; font-size: 24px; margin-left: -40px;">BumisultanAPP</span>
         </a>

         <a href="javascript:void(0);" class="layout-menu-toggle menu-link ms-auto" title="Toggle Sidebar" style="padding: 0 8px; flex-shrink: 0;">
             <i class="ti ti-dots-vertical" style="font-size: 1.2rem; color: white;"></i>
         </a>
     </div>

     <style>
         /* Saat sidebar tertutup (collapsed) */
         .layout-menu-collapsed .app-brand-text {
             display: none !important;
         }
         
         .layout-menu-collapsed .layout-menu-toggle {
             display: none !important;
         }
         
         .layout-menu-collapsed .app-brand-logo {
             margin: 0 auto !important;
         }
         
         /* Saat sidebar terbuka */
         .layout-menu:not(.layout-menu-collapsed) .app-brand-text {
             display: inline-block !important;
         }
     </style>

     <div class="menu-inner-shadow"></div>

     <ul class="menu-inner py-1">
         <!-- Dashboards -->
         <li class="menu-item {{ request()->is(['dashboard', 'dashboard/*']) ? 'active' : '' }}">
             <a href="{{ route('dashboard.index') }}" class="menu-link">
                 <i class="menu-icon tf-icons ti ti-home"></i>
                 <div>Dashboard</div>
             </a>
         </li>
         
         @if (auth()->user()->hasRole(['super admin']) || auth()->user()->can('trackingpresensi.index'))
             <li class="menu-item {{ request()->is(['trackingpresensi', 'trackingpresensi/*']) ? 'active' : '' }}">
                 <a href="{{ route('trackingpresensi.index') }}" class="menu-link">
                     <i class="menu-icon tf-icons ti ti-map-pin"></i>
                     <div>Tracking Presensi</div>
                 </a>
             </li>
         @endif
         
         @if (auth()->user()->hasRole(['super admin']) || auth()->user()->can('aktivitaskaryawan.index'))
             <li class="menu-item {{ request()->is(['aktivitaskaryawan', 'aktivitaskaryawan/*']) ? 'active' : '' }}">
                 <a href="{{ route('aktivitaskaryawan.index') }}" class="menu-link">
                     <i class="menu-icon tf-icons ti ti-activity"></i>
                     <div>Aktivitas Karyawan</div>
                 </a>
             </li>
         @endif
         
         @if (auth()->user()->hasRole(['super admin']) || auth()->user()->can('kunjungan.index'))
             <li class="menu-item {{ request()->is(['kunjungan', 'kunjungan/*']) ? 'active' : '' }}">
                 <a href="{{ route('kunjungan.index') }}" class="menu-link">
                     <i class="menu-icon tf-icons ti ti-map-pin"></i>
                     <div>Kunjungan</div>
                 </a>
             </li>
         @endif

         @if (auth()->user()->hasRole(['super admin']) || auth()->user()->can('kunjungan.index'))
             <li class="menu-item {{ request()->is(['tracking-kunjungan', 'tracking-kunjungan/*']) ? 'active' : '' }}">
                 <a href="{{ route('tracking-kunjungan.index') }}" class="menu-link">
                     <i class="menu-icon tf-icons ti ti-map-2"></i>
                     <div>Tracking Kunjungan</div>
                 </a>
             </li>
         @endif

         @if (auth()->user()->hasRole(['super admin']) || auth()->user()->hasAnyPermission(['karyawan.index', 'departemen.index', 'cabang.index', 'cuti.index', 'jamkerja.index', 'jabatan.index', 'grup.index']))
             <li
                 class="menu-item {{ request()->is(['karyawan', 'karyawan/*', 'departemen', 'departemen/*', 'cabang', 'cuti', 'jamkerja', 'jabatan', 'grup', 'grup/*']) ? 'open' : '' }}">
                 <a href="javascript:void(0);" class="menu-link menu-toggle">
                     <i class="menu-icon tf-icons ti ti-database"></i>
                     <div>Data Master</div>

                 </a>
                 <ul class="menu-sub">
                     @if (auth()->user()->hasRole(['super admin']) || auth()->user()->can('karyawan.index'))
                         <li class="menu-item {{ request()->is(['karyawan', 'karyawan/*']) ? 'active' : '' }}">
                             <a href="{{ route('karyawan.index') }}" class="menu-link">
                                 <div>Karyawan</div>
                             </a>
                         </li>
                     @endif
                     @if (auth()->user()->hasRole(['super admin']) || auth()->user()->can('departemen.index'))
                         <li class="menu-item {{ request()->is(['departemen', 'departemen/*']) ? 'active' : '' }}">
                             <a href="{{ route('departemen.index') }}" class="menu-link">
                                 <div>Departemen</div>
                             </a>
                         </li>
                     @endif
                     @if (auth()->user()->hasRole(['super admin']) || auth()->user()->can('grup.index'))
                         <li class="menu-item {{ request()->is(['grup', 'grup/*']) ? 'active' : '' }}">
                             <a href="{{ route('grup.index') }}" class="menu-link">
                                 <div>Grup</div>
                             </a>
                         </li>
                     @endif
                     @if (auth()->user()->hasRole(['super admin']) || auth()->user()->can('jabatan.index'))
                         <li class="menu-item {{ request()->is(['jabatan', 'jabatan/*']) ? 'active' : '' }}">
                             <a href="{{ route('jabatan.index') }}" class="menu-link">
                                 <div>Jabatan</div>
                             </a>
                         </li>
                     @endif
                     @if (auth()->user()->hasRole(['super admin']) || auth()->user()->can('cabang.index'))
                         <li class="menu-item {{ request()->is(['cabang', 'cabang/*']) ? 'active' : '' }}">
                             <a href="{{ route('cabang.index') }}" class="menu-link">
                                 <div>Cabang</div>
                             </a>
                         </li>
                     @endif
                     @if (auth()->user()->hasRole(['super admin']) || auth()->user()->can('cuti.index'))
                         <li class="menu-item {{ request()->is(['cuti', 'cuti/*']) ? 'active' : '' }}">
                             <a href="{{ route('cuti.index') }}" class="menu-link">
                                 <div>Cuti</div>
                             </a>
                         </li>
                     @endif
                     @if (auth()->user()->hasRole(['super admin']) || auth()->user()->can('jamkerja.index'))
                         <li class="menu-item {{ request()->is(['jamkerja', 'jamkerja/*']) ? 'active' : '' }}">
                             <a href="{{ route('jamkerja.index') }}" class="menu-link">
                                 <div>Jam Kerja</div>
                             </a>
                         </li>
                     @endif


                 </ul>
             </li>
         @endif
         @if (auth()->user()->hasRole(['super admin']) || auth()->user()->hasAnyPermission([
                     'gajipokok.index',
                     'jenistunjangan.index',
                     'tunjangan.index',
                     'bpjskesehatan.index',
                     'bpjstenagakerja.index',
                     'penyesuaiangaji.index',
                 ]))
             <li
                 class="menu-item {{ request()->is(['gajipokok', 'jenistunjangan', 'tunjangan', 'bpjskesehatan', 'bpjstenagakerja', 'penyesuaiangaji', 'penyesuaiangaji/*', 'slipgaji', 'slipgaji/*']) ? 'open' : '' }}">
                 <a href="javascript:void(0);" class="menu-link menu-toggle">
                     <i class="menu-icon tf-icons ti ti-moneybag"></i>
                     <div>Payroll</div>

                 </a>
                 <ul class="menu-sub">
                     @if (auth()->user()->hasRole(['super admin']) || auth()->user()->can('jenistunjangan.index'))
                         <li class="menu-item {{ request()->is(['jenistunjangan', 'jenistunjangan/*']) ? 'active' : '' }}">
                             <a href="{{ route('jenistunjangan.index') }}" class="menu-link">
                                 <div>Jenis Tunjangan</div>
                             </a>
                         </li>
                     @endif
                     @if (auth()->user()->hasRole(['super admin']) || auth()->user()->can('gajipokok.index'))
                         <li class="menu-item {{ request()->is(['gajipokok', 'gajipokok/*']) ? 'active' : '' }}">
                             <a href="{{ route('gajipokok.index') }}" class="menu-link">
                                 <div>Gaji Pokok</div>
                             </a>
                         </li>
                     @endif
                     @if (auth()->user()->hasRole(['super admin']) || auth()->user()->can('tunjangan.index'))
                         <li class="menu-item {{ request()->is(['tunjangan', 'tunjangan/*']) ? 'active' : '' }}">
                             <a href="{{ route('tunjangan.index') }}" class="menu-link">
                                 <div>Tunjangan</div>
                             </a>
                         </li>
                     @endif
                     @if (auth()->user()->hasRole(['super admin']) || auth()->user()->can('bpjskesehatan.index'))
                         <li class="menu-item {{ request()->is(['bpjskesehatan', 'bpjskesehatan/*']) ? 'active' : '' }}">
                             <a href="{{ route('bpjskesehatan.index') }}" class="menu-link">
                                 <div>BPJS Kesehatan</div>
                             </a>
                         </li>
                     @endif
                     @if (auth()->user()->hasRole(['super admin']) || auth()->user()->can('bpjstenagakerja.index'))
                         <li class="menu-item {{ request()->is(['bpjstenagakerja', 'bpjstenagakerja/*']) ? 'active' : '' }}">
                             <a href="{{ route('bpjstenagakerja.index') }}" class="menu-link">
                                 <div>BPJS Tenaga Kerja</div>
                             </a>
                         </li>
                     @endif
                     @if (auth()->user()->hasRole(['super admin']) || auth()->user()->can('penyesuaiangaji.index'))
                         <li class="menu-item {{ request()->is(['penyesuaiangaji', 'penyesuaiangaji/*']) ? 'active' : '' }}">
                             <a href="{{ route('penyesuaiangaji.index') }}" class="menu-link">
                                 <div>Penyesuaian Gaji</div>
                             </a>
                         </li>
                     @endif
                     @if (auth()->user()->hasRole(['super admin']) || auth()->user()->can('potongan_pinjaman.index'))
                         <li class="menu-item {{ request()->is(['potongan-pinjaman', 'potongan-pinjaman/*']) ? 'active' : '' }}">
                             <a href="{{ route('potongan_pinjaman.index') }}" class="menu-link">
                                 <div>Potongan Pinjaman</div>
                             </a>
                         </li>
                     @endif
                     @if (auth()->user()->hasRole(['super admin']) || auth()->user()->can('slipgaji.index'))
                         <li class="menu-item {{ request()->is(['slipgaji', 'slipgaji/*']) ? 'active' : '' }}">
                             <a href="{{ route('slipgaji.index') }}" class="menu-link">
                                 <div>Slip Gaji</div>
                             </a>
                         </li>
                     @endif
                 </ul>
             </li>
         @endif
         @if (auth()->user()->hasRole(['super admin']) || auth()->user()->hasAnyPermission(['presensi.index']))
             <li class="menu-item {{ request()->is(['presensi', 'presensi/*']) ? 'active' : '' }}">
                 <a href="{{ route('presensi.index') }}" class="menu-link">
                     <i class="menu-icon tf-icons ti ti-device-desktop"></i>
                     <div>Monitoring Presensi</div>
                 </a>
             </li>
         @endif

         @if (auth()->user()->hasRole(['super admin']) || auth()->user()->hasAnyPermission(['presensi.index']))
             <li class="menu-item {{ request()->is(['tugas-luar', 'tugas-luar/*']) ? 'active' : '' }}">
                 <a href="{{ route('tugas-luar.index') }}" class="menu-link">
                     <i class="menu-icon tf-icons ti ti-briefcase"></i>
                     <div>Tugas Luar</div>
                 </a>
             </li>
         @endif

         @if (auth()->user()->hasRole(['super admin']) || auth()->user()->hasAnyPermission(['izinabsen.index', 'izinsakit.index', 'izincuti.index', 'izindinas.index']))
             <li class="menu-item {{ request()->is(['izinabsen', 'izinabsen/*', 'izinsakit', 'izincuti', 'izindinas']) ? 'active' : '' }}">
                 <a href="{{ route('izinabsen.index') }}" class="menu-link">
                     <i class="menu-icon tf-icons ti ti-folder-check"></i>
                     <div>Pengajuan Absen</div>
                     @if (!empty($notifikasi_ajuan_absen))
                         <div class="badge bg-danger rounded-pill ms-auto">{{ $notifikasi_ajuan_absen }}</div>
                     @endif
                 </a>
             </li>
         @endif
         @if (auth()->user()->hasRole(['super admin']) || auth()->user()->hasAnyPermission(['lembur.index']))
             <li class="menu-item {{ request()->is(['lembur', 'lembur/*']) ? 'active' : '' }}">
                 <a href="{{ route('lembur.index') }}" class="menu-link">
                     <i class="menu-icon tf-icons ti ti-clock"></i>
                     <div>Lembur</div>
                     @if (!empty($notifikasi_lembur))
                         <div class="badge bg-danger rounded-pill ms-auto">{{ $notifikasi_lembur }}</div>
                     @endif
                 </a>
             </li>
         @endif
         @if (auth()->user()->hasRole(['super admin']) || auth()->user()->hasAnyPermission(['harilibur.index', 'jamkerjabydept.index', 'generalsetting.index']))
             <li
                 class="menu-item {{ request()->is(['harilibur', 'harilibur/*', 'jamkerjabydept', 'jamkerjabydept/*', 'generalsetting', 'denda']) ? 'open' : '' }}">
                 <a href="javascript:void(0);" class="menu-link menu-toggle">
                     <i class="menu-icon tf-icons ti ti-settings"></i>
                     <div>Konfigurasi</div>
                 </a>
                 <ul class="menu-sub">
                     <li class="menu-item {{ request()->is(['generalsetting', 'generalsetting/*']) ? 'active' : '' }}">
                         <a href="{{ route('generalsetting.index') }}" class="menu-link">
                             <div>General Setting</div>
                         </a>
                     </li>
                     @if (isset($general_setting) && $general_setting->denda)
                         <li class="menu-item {{ request()->is(['denda', 'denda/*']) ? 'active' : '' }}">
                             <a href="{{ route('denda.index') }}" class="menu-link">
                                 <div>Denda</div>
                             </a>
                         </li>
                     @endif

                     <li class="menu-item {{ request()->is(['harilibur', 'harilibur/*']) ? 'active' : '' }}">
                         <a href="{{ route('harilibur.index') }}" class="menu-link">
                             <div>Hari Libur</div>
                         </a>
                     </li>
                     <li class="menu-item {{ request()->is(['jamkerjabydept', 'jamkerjabydept/*']) ? 'active' : '' }}">
                         <a href="{{ route('jamkerjabydept.index') }}" class="menu-link">
                             <div>Jam Kerja Departemen</div>
                         </a>
                     </li>
                 </ul>
             </li>
         @endif
         @if (auth()->user()->hasRole(['super admin']) || auth()->user()->hasAnyPermission(['laporan.presensi']))
             <li class="menu-item {{ request()->is(['laporan', 'laporan/*']) ? 'open' : '' }} ">
                 <a href="javascript:void(0);" class="menu-link menu-toggle">
                     <i class="menu-icon tf-icons ti ti-adjustments-alt"></i>
                     <div>Laporan</div>
                 </a>
                 <ul class="menu-sub">
                     <li class="menu-item {{ request()->is(['laporan/presensi']) ? 'active' : '' }}">
                         <a href="{{ route('laporan.presensi') }}" class="menu-link">
                             <div>Presensi & Gaji</div>
                         </a>
                     </li>
                 </ul>
             </li>
         @endif
         @if (auth()->user()->hasRole(['super admin']))
             <li
                 class="menu-item {{ request()->is(['roles', 'roles/*', 'permissiongroups', 'permissiongroups/*', 'permissions', 'permissions/*', 'users', 'users/*']) ? 'open' : '' }} ">
                 <a href="javascript:void(0);" class="menu-link menu-toggle">
                     <i class="menu-icon tf-icons ti ti-adjustments-alt"></i>
                     <div>Utilities</div>
                 </a>
                 <ul class="menu-sub">
                     <li class="menu-item {{ request()->is(['users', 'users/*']) ? 'active' : '' }}">
                         <a href="{{ route('users.index') }}" class="menu-link">
                             <div>User</div>
                         </a>
                     </li>
                     <li class="menu-item {{ request()->is(['roles', 'roles/*']) ? 'active' : '' }}">
                         <a href="{{ route('roles.index') }}" class="menu-link">
                             <div>Role</div>
                         </a>
                     </li>
                     <li class="menu-item {{ request()->is(['permissions', 'permissions/*']) ? 'active' : '' }}"">
                         <a href="{{ route('permissions.index') }}" class="menu-link">
                             <div>Permission</div>
                         </a>
                     </li>
                     <li class="menu-item  {{ request()->is(['permissiongroups', 'permissiongroups/*']) ? 'active' : '' }}">
                         <a href="{{ route('permissiongroups.index') }}" class="menu-link">
                             <div>Group Permission</div>
                         </a>
                     </li>
                     @if (auth()->user()->hasRole(['super admin']) || auth()->user()->can('bersihkanfoto.index'))
                         <li class="menu-item {{ request()->is(['bersihkanfoto', 'bersihkanfoto/*']) ? 'active' : '' }}">
                             <a href="{{ route('bersihkanfoto.index') }}" class="menu-link">
                                 <div>Bersihkan Foto</div>
                             </a>
                         </li>
                     @endif
                 </ul>
             </li>
         @endif
         @if (auth()->user()->hasRole(['super admin']))
             <li class="menu-item {{ request()->is(['wagateway', 'wagateway/*']) ? 'active' : '' }}">
                 <a href="{{ route('wagateway.index') }}" class="menu-link">
                     <i class="menu-icon tf-icons ti ti-brand-whatsapp"></i>
                     <div>WA Gateway</div>
                 </a>
             </li>
         @endif
         @if (auth()->user()->hasRole(['super admin']))
             <li class="menu-item {{ request()->is(['gedung*', 'ruangan*', 'barang*', 'kendaraan*', 'aktivitas*', 'peminjaman*', 'service*', 'pengunjung*', 'inventaris*', 'peminjaman-inventaris*', 'pengembalian-inventaris*', 'peralatan*', 'peminjaman-peralatan*', 'history-inventaris*', 'administrasi*', 'dokumen*']) ? 'active open' : '' }}">
                 <a href="javascript:void(0);" class="menu-link menu-toggle">
                     <i class="menu-icon tf-icons ti ti-building"></i>
                     <div>Fasilitas & Asset</div>
                 </a>
                 <ul class="menu-sub">
                     <li class="menu-item {{ request()->is(['gedung', 'gedung/*', 'ruangan*', 'barang*']) ? 'active' : '' }}">
                         <a href="{{ route('gedung.index') }}" class="menu-link">
                             <div>Manajemen Gedung</div>
                         </a>
                     </li>
                     <li class="menu-item {{ request()->is(['kendaraan*', 'aktivitas*', 'peminjaman*', 'service*']) && !request()->is(['aktivitaskaryawan*']) ? 'active' : '' }}">
                         <a href="{{ route('kendaraan.index') }}" class="menu-link">
                             <div>Manajemen Kendaraan</div>
                         </a>
                     </li>
                     <li class="menu-item {{ request()->is(['pengunjung', 'pengunjung/*']) ? 'active' : '' }}">
                         <a href="{{ route('pengunjung.index') }}" class="menu-link">
                             <div>Manajemen Pengunjung</div>
                         </a>
                     </li>
                     
                     <!-- Menu Manajemen Inventaris -->
                     <li class="menu-item {{ request()->is(['inventaris*', 'peminjaman-inventaris*', 'pengembalian-inventaris*', 'history-inventaris*']) ? 'active' : '' }}">
                         <a href="{{ route('inventaris.index') }}" class="menu-link">
                             <div>Manajemen Inventaris</div>
                         </a>
                     </li>

                    <!-- Menu Manajemen Peralatan BS -->
                    <li class="menu-item {{ request()->is(['peralatan*', 'peminjaman-peralatan*']) ? 'active' : '' }}">
                        <a href="{{ route('peralatan.index') }}" class="menu-link">
                            <div>Manajemen Peralatan BS</div>
                        </a>
                    </li>

                    <!-- Menu Manajemen Administrasi -->
                    <li class="menu-item {{ request()->is(['administrasi*']) ? 'active' : '' }}">
                        <a href="{{ route('administrasi.index') }}" class="menu-link">
                            <div>Manajemen Administrasi</div>
                        </a>
                    </li>

                    <!-- Menu Manajemen Dokumen -->
                    <li class="menu-item {{ request()->is(['dokumen*']) ? 'active' : '' }}">
                        <a href="{{ route('dokumen.index') }}" class="menu-link">
                            <div>Manajemen Dokumen</div>
                        </a>
                    </li>
                </ul>
            </li>
        @endif
        
        @if (auth()->user()->hasRole(['super admin']))
            <!-- Menu Manajemen Saung Santri -->
            <li class="menu-item {{ request()->is(['santri*', 'jadwal-santri*', 'absensi-santri*', 'ijin-santri*', 'keuangan-santri*', 'pelanggaran-santri*', 'khidmat*']) ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons ti ti-users"></i>
                    <div>Manajemen Saung Santri</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ request()->is(['santri', 'santri/*']) && !request()->is(['jadwal-santri*', 'absensi-santri*', 'ijin-santri*', 'keuangan-santri*', 'pelanggaran-santri*', 'khidmat*']) ? 'active' : '' }}">
                        <a href="{{ route('santri.index') }}" class="menu-link">
                            <div>Data Santri</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->is(['jadwal-santri*', 'absensi-santri*']) ? 'active' : '' }}">
                        <a href="{{ route('jadwal-santri.index') }}" class="menu-link">
                            <div>Jadwal & Absensi Santri</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->is(['ijin-santri*']) ? 'active' : '' }}">
                        <a href="{{ route('ijin-santri.index') }}" class="menu-link">
                            <div>Ijin Santri</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->is(['keuangan-santri*']) ? 'active' : '' }}">
                        <a href="{{ route('keuangan-santri.index') }}" class="menu-link">
                            <div>Keuangan Santri</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->is(['pelanggaran-santri*']) ? 'active' : '' }}">
                        <a href="{{ route('pelanggaran-santri.index') }}" class="menu-link">
                            <div>Pelanggaran Santri</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->is(['khidmat*']) ? 'active' : '' }}">
                        <a href="{{ route('khidmat.index') }}" class="menu-link">
                            <div>Khidmat (Belanja Masak)</div>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Menu Manajemen Tukang -->
            @if (auth()->user()->hasRole(['super admin']) || auth()->user()->hasAnyPermission(['tukang.index', 'kehadiran-tukang.index', 'keuangan-tukang.index']))
                <li class="menu-item {{ request()->is(['tukang*', 'kehadiran-tukang*', 'keuangan-tukang*', 'cash-lembur*']) ? 'active open' : '' }}">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons ti ti-tool"></i>
                        <div>Manajemen Tukang</div>
                    </a>
                    <ul class="menu-sub">
                        @if (auth()->user()->hasRole(['super admin']) || auth()->user()->can('tukang.index'))
                            <li class="menu-item {{ request()->is(['tukang']) || (request()->is(['tukang/*']) && !request()->is(['tukang/kehadiran*', 'tukang/keuangan*'])) ? 'active' : '' }}">
                                <a href="{{ route('tukang.index') }}" class="menu-link">
                                    <div>Data Tukang</div>
                                </a>
                            </li>
                        @endif
                        @if (auth()->user()->hasRole(['super admin']) || auth()->user()->can('kehadiran-tukang.index'))
                            <li class="menu-item {{ request()->is(['kehadiran-tukang*']) ? 'active' : '' }}">
                                <a href="{{ route('kehadiran-tukang.index') }}" class="menu-link">
                                    <div>Kehadiran Tukang</div>
                                </a>
                            </li>
                        @endif
                        @if (auth()->user()->hasRole(['super admin']) || auth()->user()->can('keuangan-tukang.index'))
                            <li class="menu-item {{ request()->is(['keuangan-tukang*', 'cash-lembur*']) ? 'active' : '' }}">
                                <a href="{{ route('keuangan-tukang.index') }}" class="menu-link">
                                    <div>Keuangan Tukang</div>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
        @endif

        <!-- Manajemen Keuangan -->
        @if (auth()->user()->hasRole(['super admin']))
            <li class="menu-item {{ request()->is(['dana-operasional', 'dana-operasional/*']) ? 'active' : '' }}">
                <a href="{{ route('dana-operasional.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-moneybag"></i>
                    <div>Manajemen Keuangan</div>
                </a>
            </li>

            <!-- Manajemen Pinjaman -->
            <li class="menu-item {{ request()->is(['pinjaman', 'pinjaman/*']) ? 'active' : '' }}">
                <a href="{{ route('pinjaman.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-cash"></i>
                    <div>Manajemen Pinjaman</div>
                </a>
            </li>

            <!-- Manajemen Perawatan Gedung -->
            <li class="menu-item {{ request()->is(['perawatan', 'perawatan/*']) ? 'active' : '' }}">
                <a href="{{ route('perawatan.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-building-warehouse"></i>
                    <div>Manajemen Perawatan</div>
                </a>
            </li>

            <!-- Temuan -->
            <li class="menu-item {{ request()->is(['temuan', 'temuan/*']) ? 'active' : '' }}">
                <a href="{{ route('temuan.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-alert-circle"></i>
                    <div>Temuan</div>
                </a>
            </li>

            <!-- Pusat Informasi -->
            <li class="menu-item {{ request()->is(['admin/informasi', 'admin/informasi/*']) ? 'active' : '' }}">
                <a href="{{ route('admin.informasi.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-info-circle"></i>
                    <div>Pusat Informasi</div>
                </a>
            </li>

            <!-- KPI Crew -->
            <li class="menu-item {{ request()->is(['kpicrew', 'kpicrew/*']) ? 'active' : '' }}">
                <a href="{{ route('kpicrew.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-chart-line"></i>
                    <div>KPI Crew</div>
                </a>
            </li>

            <!-- Yayasan Masar -->
            @if (auth()->user()->hasRole(['super admin']) || auth()->user()->hasAnyPermission(['yayasan_masar.index', 'monitoring_presensi_yayasan.index', 'laporan_presensi_yayasan.index']))
                <li class="menu-item {{ request()->is(['yayasan-masar', 'yayasan-masar/*', 'monitoring-presensi-yayasan', 'monitoring-presensi-yayasan/*', 'laporan-presensi-yayasan', 'laporan-presensi-yayasan/*']) ? 'active open' : '' }}">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons ti ti-building"></i>
                        <div>Yayasan Masar</div>
                    </a>
                    <ul class="menu-sub">
                        <li class="menu-item {{ request()->is(['yayasan-masar', 'yayasan-masar/*']) && !request()->is(['monitoring-presensi-yayasan*', 'laporan-presensi-yayasan*']) ? 'active' : '' }}">
                            <a href="{{ route('yayasan_masar.index') }}" class="menu-link">
                                <div>Data Jamaah</div>
                            </a>
                        </li>
                        @if (auth()->user()->hasRole(['super admin']) || auth()->user()->can('yayasan_masar.index'))
                            <li class="menu-item {{ request()->is(['yayasan-presensi', 'yayasan-presensi/*']) ? 'active' : '' }}">
                                <a href="{{ route('yayasan-presensi.index') }}" class="menu-link">
                                    <div>Monitoring Presensi Yayasan</div>
                                </a>
                            </li>
                        @endif
                        @if (auth()->user()->hasRole(['super admin']) || auth()->user()->can('yayasan_masar.index'))
                            <li class="menu-item {{ request()->is(['laporan/presensi-yayasan', 'laporan/presensi-yayasan/*']) ? 'active' : '' }}">
                                <a href="{{ route('yayasan-laporan.presensi') }}" class="menu-link">
                                    <div>Laporan Presensi Yayasan</div>
                                </a>
                            </li>
                        @endif
                        @if (auth()->user()->hasRole(['super admin']) || auth()->user()->hasAnyPermission(['distribusi-hadiah.index', 'hadiah.index']))
                            <li class="menu-item {{ request()->is(['majlistaklim/hadiah*', 'majlistaklim/distribusi*', 'majlistaklim/laporan*']) ? 'active' : '' }}">
                                <a href="{{ route('majlistaklim.hadiah.index') }}" class="menu-link">
                                    <div>Distribusi Hadiah</div>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
        @endif
     </ul>
 </aside>
 <!-- / Menu -->

 <script>
     document.addEventListener('DOMContentLoaded', function() {
         const appBrandText = document.querySelector('.app-brand-text');
         const layoutMenu = document.getElementById('layout-menu');
         const layoutToggle = document.querySelector('.layout-menu-toggle');
         let isLocked = false;

         if (appBrandText) {
             appBrandText.style.cursor = 'pointer';
             appBrandText.title = 'Click to lock/unlock sidebar toggle';

             appBrandText.addEventListener('click', function(e) {
                 e.preventDefault();
                 isLocked = !isLocked;

                 if (isLocked) {
                     // Lock the sidebar - disable toggle
                     layoutToggle.style.pointerEvents = 'none';
                     layoutToggle.style.opacity = '0.5';
                     appBrandText.style.backgroundColor = 'rgba(255, 255, 255, 0.1)';
                     appBrandText.style.padding = '2px 6px';
                     appBrandText.style.borderRadius = '4px';
                     localStorage.setItem('sidebar-locked', 'true');
                 } else {
                     // Unlock the sidebar - enable toggle
                     layoutToggle.style.pointerEvents = 'auto';
                     layoutToggle.style.opacity = '1';
                     appBrandText.style.backgroundColor = 'transparent';
                     localStorage.setItem('sidebar-locked', 'false');
                 }
             });

             // Check if sidebar was locked before
             if (localStorage.getItem('sidebar-locked') === 'true') {
                 isLocked = true;
                 layoutToggle.style.pointerEvents = 'none';
                 layoutToggle.style.opacity = '0.5';
                 appBrandText.style.backgroundColor = 'rgba(255, 255, 255, 0.1)';
                 appBrandText.style.padding = '2px 6px';
                 appBrandText.style.borderRadius = '4px';
             }
         }
     });
 </script>
