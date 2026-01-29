# 📱 MOCKUP VISUAL UI APLIKASI KARYAWAN - DETAILED SCENARIOS

## SCENARIO 1: Karyawan NON SHIFT - Jam 10:30 (DALAM JAM KERJA)

### Screen A1: Dashboard - Shift Active

```
╔═════════════════════════════════════════════════════╗
║                   PERAWATAN                    [⚙] ║
╠═════════════════════════════════════════════════════╣
║                                                     ║
║  🟢 NON SHIFT AKTIF                               ║
║  Jam Kerja: 08:00 - 17:00                         ║
║  Waktu Sekarang: 10:30                            ║
║  ⏳ Sisa Waktu: 6 jam 30 menit                     ║
║                                                     ║
╠═════════════════════════════════════════════════════╣
║                                                     ║
║  📊 PROGRESS HARI INI                              ║
║  ██████████░░░░░░░░░░░░░░░░░░ 5/10 (50%)         ║
║                                                     ║
║  ✅ Selesai: 5                                     ║
║  ⏳ Menunggu: 3                                     ║
║  ❌ Belum Bisa: 2                                  ║
║                                                     ║
╠═════════════════════════════════════════════════════╣
║  DAFTAR CHECKLIST                                  ║
╠═════════════════════════════════════════════════════╣
║                                                     ║
║  ✅ 08:00 - Bersihkan Area Kerja                  ║
║     Status: SELESAI ON-TIME                        ║
║     Oleh: Doni | Jam: 08:15 | +10 pts            ║
║                                                     ║
║  ✅ 09:00 - Cek Barang Gudang                     ║
║     Status: SELESAI ON-TIME                        ║
║     Oleh: Doni | Jam: 09:30 | +10 pts            ║
║                                                     ║
║  ✅ 11:00 - Monitor Keamanan                      ║
║     Status: SELESAI ON-TIME                        ║
║     Oleh: Doni | Jam: 11:05 | +10 pts            ║
║                                                     ║
║  ✅ 12:00 - Buang Sampah Pagi                     ║
║     Status: SELESAI ON-TIME                        ║
║     Oleh: Doni | Jam: 12:20 | +10 pts            ║
║                                                     ║
║  ✅ 13:00 - Cek Inventaris                        ║
║     Status: SELESAI ON-TIME                        ║
║     Oleh: Doni | Jam: 13:40 | +10 pts            ║
║                                                     ║
╠─ SEDANG BISA DIKERJAKAN ─────────────────────────╣
║                                                     ║
║  ⏳ 14:00 - Bersihkan Ruang Rapat                 ║
║     Siap dari: 14:00 ✓ (Bisa dikerjakan sekarang) ║
║     Status: BELUM DIKERJAKAN                       ║
║     [Buka Checklist] ────────────────────────▶    ║
║                                                     ║
║  ⏳ 15:30 - Bersihkan Pantry                      ║
║     Siap dari: 15:30 ✓ (Bisa dikerjakan sekarang) ║
║     Status: BELUM DIKERJAKAN                       ║
║     [Buka Checklist] ────────────────────────▶    ║
║                                                     ║
╠─ MENUNGGU WAKTU ──────────────────────────────────╣
║                                                     ║
║  ⏱ 17:00 - Absen Pulang Verifikasi               ║
║     Siap dari: 17:00 (6 jam 30 menit lagi)        ║
║     Status: AKAN AKTIF NANTI                       ║
║     [Unlock di 17:00]                              ║
║                                                     ║
╠═════════════════════════════════════════════════════╣
║                                                     ║
║  🔒 TERSEMBUNYI (DILUAR JAM KERJA)                ║
║                                                     ║
║  ❌ 18:00 - Monitor Malam (SHIFT 2)               ║
║     Alasan: Bukan jadwal Anda → HIDDEN             ║
║                                                     ║
║  ❌ 21:00 - Kunci Gudang (SHIFT 2)                ║
║     Alasan: Bukan jadwal Anda → HIDDEN             ║
║                                                     ║
╠═════════════════════════════════════════════════════╣
║                                                     ║
║  [   ABSEN PULANG   ]  [   LIHAT DETAIL   ]       ║
║                                                     ║
╚═════════════════════════════════════════════════════╝
```

### Screen A2: Buka Checklist yang SIAP

```
╔═════════════════════════════════════════════════════╗
║  ← CHECKLIST DETAIL                          [⟲] ║
╠═════════════════════════════════════════════════════╣
║                                                     ║
║  ✅ JAM KERJA VALID                               ║
║  Anda dalam jam kerja: 08:00-17:00               ║
║  Waktu sekarang: 10:35                            ║
║  Checklist siap pada: 14:00 ✓                     ║
║                                                     ║
╠═════════════════════════════════════════════════════╣
║                                                     ║
║  📋 BERSIHKAN RUANG RAPAT                         ║
║                                                     ║
║  Kategori: Kebersihan                              ║
║  Jadwal Shift: NON SHIFT (08:00-17:00)            ║
║  Jam Checklist: 14:00                              ║
║  Waktu Mulai Kerja: 14:00                         ║
║  Waktu Maksimal: 15:00                            ║
║  Status: ✅ BISA DIKERJAKAN (Tepat Waktu)        ║
║                                                     ║
║  ─────────────────────────────────────────────     ║
║                                                     ║
║  📝 DESKRIPSI:                                     ║
║  Bersihkan ruang rapat dari debu, sanitasi        ║
║  kursi dan meja, rapikan barang-barang yang       ║
║  tersebar, dan pastikan proyektor berfungsi.      ║
║                                                     ║
║  ─────────────────────────────────────────────     ║
║                                                     ║
║  📷 FOTO BUKTI:                                    ║
║  [📷 Ambil Foto] atau [📤 Pilih dari Gallery]    ║
║                                                     ║
║  📝 CATATAN:                                       ║
║  ┌─────────────────────────────────────────┐     ║
║  │ Ketik catatan di sini (opsional)...     │     ║
║  │                                          │     ║
║  │                                          │     ║
║  └─────────────────────────────────────────┘     ║
║                                                     ║
║  ─────────────────────────────────────────────     ║
║                                                     ║
║  [    BATAL    ]        [✓ SELESAIKAN]           ║
║                                                     ║
╚═════════════════════════════════════════════════════╝
```

---

## SCENARIO 2: Karyawan SHIFT 2 - Jam 22:30 (DALAM JAM KERJA)

### Screen B1: Dashboard - SHIFT 2 Active (Cross-Midnight)

```
╔═════════════════════════════════════════════════════╗
║                   PERAWATAN                    [⚙] ║
╠═════════════════════════════════════════════════════╣
║                                                     ║
║  🔵 SHIFT 2 AKTIF                                 ║
║  Jam Kerja: 20:00 - 08:00 (Esok Hari)            ║
║  Waktu Sekarang: 22:30 (Jam 22:30 Malam)         ║
║  ⏳ Sisa Waktu: 9 jam 30 menit                     ║
║                                                     ║
╠═════════════════════════════════════════════════════╣
║                                                     ║
║  📊 PROGRESS SHIFT 2                               ║
║  ████░░░░░░░░░░░░░░░░░░░░░░░░░░░░ 2/6 (33%)    ║
║                                                     ║
║  ✅ Selesai: 2                                     ║
║  ⏳ Menunggu: 3                                     ║
║  ❌ Belum Bisa: 1                                  ║
║                                                     ║
╠═════════════════════════════════════════════════════╣
║  DAFTAR CHECKLIST SHIFT 2 (20:00-08:00)          ║
╠═════════════════════════════════════════════════════╣
║                                                     ║
║  ✅ 20:00 - Monitor Pintu Masuk                   ║
║     Status: SELESAI ON-TIME                        ║
║     Oleh: Rina | Jam: 20:15 | +10 pts            ║
║                                                     ║
║  ✅ 21:00 - Cek Sistem Keamanan                   ║
║     Status: SELESAI ON-TIME                        ║
║     Oleh: Rina | Jam: 21:30 | +10 pts            ║
║                                                     ║
╠─ SEDANG BISA DIKERJAKAN ─────────────────────────╣
║                                                     ║
║  ⏳ 23:00 - Monitoring Ruang Server               ║
║     Siap dari: 23:00 ✓ (Bisa dikerjakan sekarang) ║
║     Waktu sekarang: 22:30 (Tinggal 30 menit)     ║
║     Status: BELUM DIKERJAKAN                       ║
║     [Buka Checklist] ────────────────────────▶    ║
║                                                     ║
║  ⏳ 02:00 - Pengecekan Pagi (Area Belakang)      ║
║     Siap dari: 02:00 (3 jam 30 min dari sekarang) ║
║     Status: MENUNGGU WAKTU                         ║
║     [Countdown: 03:30]                             ║
║                                                     ║
╠─ MENUNGGU WAKTU ──────────────────────────────────╣
║                                                     ║
║  ⏱ 07:00 - Persiapan End-Shift                    ║
║     Siap dari: 07:00 (8 jam 30 menit lagi)        ║
║     Status: AKAN AKTIF NANTI                       ║
║     [Unlock di 07:00]                              ║
║                                                     ║
╠═════════════════════════════════════════════════════╣
║                                                     ║
║  🔒 TERSEMBUNYI (DILUAR JAM KERJA)                ║
║                                                     ║
║  ❌ 08:00 - Bersihkan Area Kerja (NON SHIFT)      ║
║     Alasan: Bukan jadwal Anda → HIDDEN             ║
║                                                     ║
║  ❌ 12:00 - Buang Sampah (NON SHIFT)              ║
║     Alasan: Bukan jadwal Anda → HIDDEN             ║
║                                                     ║
╠═════════════════════════════════════════════════════╣
║                                                     ║
║  [   ABSEN PULANG   ]  [   LIHAT DETAIL   ]       ║
║                                                     ║
╚═════════════════════════════════════════════════════╝
```

---

## SCENARIO 3: Karyawan Coba Akses DILUAR Jam Kerja

### Screen C1: Buka App Jam 18:30 (NON SHIFT - diluar jam)

```
╔═════════════════════════════════════════════════════╗
║                   PERAWATAN                    [⚙] ║
╠═════════════════════════════════════════════════════╣
║                                                     ║
║  ⚠️  DILUAR JAM KERJA                             ║
║                                                     ║
║  Waktu Sekarang: 18:30                            ║
║  Jadwal Kerja Anda: 08:00 - 17:00                 ║
║  Status: 🔒 PERIODE TERTUTUP                      ║
║                                                     ║
╠═════════════════════════════════════════════════════╣
║                                                     ║
║  ❌ CHECKLIST TIDAK DAPAT DIAKSES                 ║
║                                                     ║
║  Alasan:                                            ║
║  • Jam kerja Anda telah berakhir pada 17:00      ║
║  • Periode checklist otomatis ditutup              ║
║  • Tidak ada checklist dapat diakses diluar jam   ║
║    kerja yang berlaku                              ║
║                                                     ║
╠═════════════════════════════════════════════════════╣
║                                                     ║
║  📋 SUMMARY HARI INI:                              ║
║                                                     ║
║  Total Checklist: 10                               ║
║  ✅ Selesai On-Time: 7                             ║
║  ⏳ Incomplete: 3                                  ║
║  └─ 14:00 - Bersihkan Ruang Rapat                 ║
║  └─ 15:30 - Bersihkan Pantry                      ║
║  └─ 17:00 - Absen Pulang Verifikasi              ║
║                                                     ║
║  KPI Points Hari Ini: +70 pts                      ║
║  (3 checklist incomplete: -30 pts)                 ║
║                                                     ║
╠═════════════════════════════════════════════════════╣
║                                                     ║
║  ❓ BANTUAN:                                       ║
║  Jika ada keberatan dengan status checklist,      ║
║  silakan hubungi administrator atau catat catatan ║
║  Anda untuk ditinjau kemudian.                     ║
║                                                     ║
║  [Hubungi Admin]  [Kembali ke Dashboard]         ║
║                                                     ║
╚═════════════════════════════════════════════════════╝
```

---

## SCENARIO 4: Absen Pulang - Berbagai Kondisi

### Screen D1: Semua Checklist Selesai (Jam 15:00)

```
╔═════════════════════════════════════════════════════╗
║  PULANG LEBIH AWAL - DIIZINKAN                  ✓ ║
╠═════════════════════════════════════════════════════╣
║                                                     ║
║  ✅ SEMUA CHECKLIST SELESAI!                      ║
║                                                     ║
║  Progress: [██████████████████████] 10/10 (100%) ║
║                                                     ║
╠═════════════════════════════════════════════════════╣
║                                                     ║
║  📊 SUMMARY PULANG:                                ║
║                                                     ║
║  Jam Kerja: 08:00 - 17:00                         ║
║  Waktu Sekarang: 15:00                            ║
║  Pulang Lebih Awal: 2 jam lebih awal              ║
║                                                     ║
║  Total Checklist: 10/10 ✓                         ║
║  Status: ✅ PULANG LEBIH AWAL - VALID             ║
║                                                     ║
║  KPI Points Today: +100 pts                        ║
║  Bonus Early Leave: +10 pts                        ║
║  ────────────────                                  ║
║  Total: +110 pts ⭐                               ║
║                                                     ║
╠═════════════════════════════════════════════════════╣
║                                                     ║
║  ℹ️  Informasi:                                    ║
║  Anda telah menyelesaikan semua checklist tepat   ║
║  waktu. Anda diizinkan pulang lebih awal dengan  ║
║  penghargaan bonus poin. Keputusan ini tercatat   ║
║  dalam sistem untuk keperluan evaluasi kinerja.   ║
║                                                     ║
╠═════════════════════════════════════════════════════╣
║                                                     ║
║  [BATAL]            [✓ PULANG SEKARANG]          ║
║                                                     ║
╚═════════════════════════════════════════════════════╝
```

### Screen D2: Belum Semua Selesai (Jam 16:00)

```
╔═════════════════════════════════════════════════════╗
║  PERHATIAN - CHECKLIST BELUM SELESAI            ⚠ ║
╠═════════════════════════════════════════════════════╣
║                                                     ║
║  ⚠️  ADA CHECKLIST YANG BELUM SELESAI             ║
║                                                     ║
║  Progress: [████████░░░░░░░░░░░░] 6/10 (60%)     ║
║                                                     ║
╠═════════════════════════════════════════════════════╣
║                                                     ║
║  📊 SUMMARY PULANG:                                ║
║                                                     ║
║  Waktu Sekarang: 16:00                            ║
║  Jam Kerja Berakhir: 17:00 (1 jam lagi)           ║
║  Sisa Waktu: 1 jam untuk menyelesaikan            ║
║                                                     ║
║  Selesai: 6/10 ✓                                  ║
║  Belum: 4/10 ✗                                    ║
║  Status: ⚠️ INCOMPLETE                            ║
║                                                     ║
║  ─────────────────────────────────────────────     ║
║                                                     ║
║  CHECKLIST YANG BELUM SELESAI:                     ║
║  ❌ 14:00 - Bersihkan Ruang Rapat                 ║
║  ❌ 15:00 - Cek Inventaris                        ║
║  ❌ 15:30 - Bersihkan Pantry                      ║
║  ❌ 17:00 - Absen Pulang Verifikasi              ║
║                                                     ║
╠═════════════════════════════════════════════════════╣
║                                                     ║
║  PILIH SALAH SATU:                                 ║
║                                                     ║
║  ┏━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┓   ║
║  ┃ [1] SELESAIKAN CHECKLIST DULU        ┃   ║
║  ┃                                        ┃   ║
║  ┃ Lanjutkan mengerjakan 4 checklist     ┃   ║
║  ┃ yang tersisa. Anda masih punya 1 jam. ┃   ║
║  ┃                                        ┃   ║
║  ┃ [SELESAIKAN]                           ┃   ║
║  ┗━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┛   ║
║                                                     ║
║  ┏━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┓   ║
║  ┃ [2] PULANG DENGAN CATATAN              ┃   ║
║  ┃                                        ┃   ║
║  ┃ Pulang sekarang, 4 checklist tercatat  ┃   ║
║  ┃ INCOMPLETE. KPI akan dikurangi.        ┃   ║
║  ┃                                        ┃   ║
║  ┃ Alasan Pulang:                         ┃   ║
║  ┃ ┌─────────────────────────────────┐   ┃   ║
║  ┃ │ Ketik alasan pulang... (max255) │   ┃   ║
║  ┃ │                                 │   ┃   ║
║  ┃ │                                 │   ┃   ║
║  ┃ └─────────────────────────────────┘   ┃   ║
║  ┃                                        ┃   ║
║  ┃ ☐ Saya memahami konsekuensinya         ┃   ║
║  ┃   (-30 pts, status INCOMPLETE)         ┃   ║
║  ┃                                        ┃   ║
║  ┃ [PULANG]                               ┃   ║
║  ┗━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┛   ║
║                                                     ║
║  [BATAL]                                           ║
║                                                     ║
╚═════════════════════════════════════════════════════╝
```

---

## SCENARIO 5: Checklist Belum Siap (Countdown Timer)

### Screen E1: Waiting untuk Checklist Siap

```
╔═════════════════════════════════════════════════════╗
║  ← DAFTAR CHECKLIST                            [⟲] ║
╠═════════════════════════════════════════════════════╣
║                                                     ║
║  ✅ JAM KERJA VALID                               ║
║  Anda dalam jam kerja: 08:00-17:00 (10:45)      ║
║                                                     ║
╠═════════════════════════════════════════════════════╣
║                                                     ║
║  ⏳ CHECKLIST BELUM SIAP                          ║
║                                                     ║
║  Nama: BUANG SAMPAH & RESTOCK                      ║
║  Jadwal Shift: NON SHIFT (08:00-17:00)            ║
║  Siap Dari: 12:00                                  ║
║  Waktu Sekarang: 10:45                            ║
║                                                     ║
║  ─────────────────────────────────────────────     ║
║                                                     ║
║  ⏱ COUNTDOWN:                                      ║
║  [████░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░] 1:15   ║
║                                                     ║
║  Checklist akan siap dalam:                        ║
║  1 jam 15 menit                                    ║
║                                                     ║
║  ─────────────────────────────────────────────     ║
║                                                     ║
║  💡 Tip:                                           ║
║  Checklist ini akan otomatis aktif pada 12:00.    ║
║  Silakan kembali kemudian untuk mengerjakannya.    ║
║                                                     ║
║  [KEMBALI KE DAFTAR]                              ║
║                                                     ║
╚═════════════════════════════════════════════════════╝
```

---

## SCENARIO 6: Coba Akses Checklist dari SHIFT LAIN

### Screen F1: Mencoba Akses Checklist SHIFT 2

```
╔═════════════════════════════════════════════════════╗
║  ← DETAIL CHECKLIST                            [⟲] ║
╠═════════════════════════════════════════════════════╣
║                                                     ║
║  ⚠️  AKSES DITOLAK                                ║
║                                                     ║
║  Status: 🔒 HIDDEN - CHECKLIST TIDAK UNTUK ANDA   ║
║                                                     ║
╠═════════════════════════════════════════════════════╣
║                                                     ║
║  📋 MONITORING MALAM                              ║
║                                                     ║
║  Jadwal Checklist: SHIFT 2 (20:00 - 08:00)       ║
║  Jadwal Kerja Anda: NON SHIFT (08:00 - 17:00)    ║
║                                                     ║
║  Waktu Sekarang: 14:00 (NON SHIFT)                ║
║                                                     ║
║  ─────────────────────────────────────────────     ║
║                                                     ║
║  ❌ AKSES DITOLAK                                 ║
║                                                     ║
║  Alasan:                                            ║
║  Checklist ini hanya dapat diakses oleh            ║
║  karyawan yang sedang bekerja pada:                ║
║                                                     ║
║  🔵 SHIFT 2 (20:00 - 08:00)                       ║
║                                                     ║
║  Anda saat ini sedang bekerja pada:                ║
║  🟢 NON SHIFT (08:00 - 17:00)                     ║
║                                                     ║
║  ─────────────────────────────────────────────     ║
║                                                     ║
║  ℹ️  Catatan:                                      ║
║  Checklist ini tersembunyi (hidden) karena bukan  ║
║  jadwal kerja Anda. Hanya karyawan yang ditugaskan ║
║  pada SHIFT 2 yang dapat melihat dan mengerjakan   ║
║  checklist ini.                                    ║
║                                                     ║
║  Jika Anda merasa ada kesalahan dalam penugasan,   ║
║  hubungi administrator.                            ║
║                                                     ║
║  [KEMBALI]                [HUBUNGI ADMIN]         ║
║                                                     ║
╚═════════════════════════════════════════════════════╝
```

---

## SCENARIO 7: Setelah Jam Kerja Berakhir (Auto-Lock)

### Screen G1: Jam 17:30 - Periode Sudah Tertutup

```
╔═════════════════════════════════════════════════════╗
║                   PERAWATAN                    [⚙] ║
╠═════════════════════════════════════════════════════╣
║                                                     ║
║  🔒 PERIODE TERTUTUP                              ║
║                                                     ║
║  Waktu Sekarang: 17:30                            ║
║  Jam Kerja Anda: 08:00 - 17:00                    ║
║  Periode Ditutup: 17:00                            ║
║  Status: LOCKED - Tidak dapat diakses              ║
║                                                     ║
╠═════════════════════════════════════════════════════╣
║                                                     ║
║  ❌ CHECKLIST TIDAK DAPAT DIAKSES                 ║
║                                                     ║
║  Periode checklist telah otomatis ditutup pada    ║
║  17:00 (saat jam kerja Anda berakhir).             ║
║                                                     ║
║  Anda TIDAK LAGI dapat:                            ║
║  ✗ Membuka checklist                              ║
║  ✗ Menambah centang                               ║
║  ✗ Mengedit catatan atau foto                     ║
║  ✗ Mengirim/submit checklist                      ║
║                                                     ║
╠═════════════════════════════════════════════════════╣
║                                                     ║
║  📋 SUMMARY HARI INI (NON SHIFT):                  ║
║                                                     ║
║  Total Checklist: 10                               ║
║  ✅ Selesai: 7                                     ║
║  ❌ Tidak Selesai: 3                               ║
║  ────────────────                                  ║
║  Status: INCOMPLETE                                ║
║                                                     ║
║  Checklist yang tidak selesai:                     ║
║  └─ 14:00 - Bersihkan Ruang Rapat                 ║
║  └─ 15:30 - Bersihkan Pantry                      ║
║  └─ 17:00 - Absen Pulang Verifikasi              ║
║                                                     ║
║  KPI Points: +70 pts (7 x on-time)                ║
║  Penalty: -30 pts (3 x incomplete)                ║
║  ────────────────                                  ║
║  Total: +40 pts                                    ║
║                                                     ║
╠═════════════════════════════════════════════════════╣
║                                                     ║
║  ❓ KEBERATAN?                                     ║
║  Jika ada permasalahan dengan status checklist,    ║
║  silakan hubungi administrator sebelum esok hari.  ║
║  Periode akan direset otomatis pada 08:00 besok.  ║
║                                                     ║
║  [HUBUNGI ADMIN]  [KEMBALI KE DASHBOARD]         ║
║                                                     ║
╚═════════════════════════════════════════════════════╝
```

---

## SCENARIO 8: Reset Otomatis - Shift Baru Dimulai

### Screen H1: Jam 20:00 - SHIFT 2 Mulai (Reset untuk SHIFT 2)

```
╔═════════════════════════════════════════════════════╗
║                   PERAWATAN                    [⚙] ║
╠═════════════════════════════════════════════════════╣
║                                                     ║
║  🔵 SHIFT 2 MULAI - PERIODE RESET                 ║
║                                                     ║
║  Jam Kerja: 20:00 - 08:00 (Esok Hari)            ║
║  Waktu Sekarang: 20:00                            ║
║  Status: ✅ PERIODE BARU AKTIF                     ║
║                                                     ║
╠═════════════════════════════════════════════════════╣
║                                                     ║
║  📊 PROGRESS SHIFT 2                               ║
║  [░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░] 0/6 (0%)║
║                                                     ║
║  ✅ Selesai: 0 (Shift baru dimulai)                ║
║  ⏳ Menunggu: 6                                     ║
║                                                     ║
╠═════════════════════════════════════════════════════╣
║  DAFTAR CHECKLIST SHIFT 2 (PERIODE BARU)          ║
╠═════════════════════════════════════════════════════╣
║                                                     ║
║  ⏳ 20:00 - Monitor Pintu Masuk                    ║
║     Siap dari: 20:00 ✓ (Bisa dikerjakan sekarang) ║
║     Status: BELUM DIKERJAKAN                       ║
║     [Buka Checklist] ────────────────────────▶    ║
║                                                     ║
║  ⏳ 21:00 - Cek Sistem Keamanan                    ║
║     Siap dari: 21:00 (1 jam lagi)                 ║
║     Status: MENUNGGU WAKTU                         ║
║     [Unlock di 21:00]                              ║
║                                                     ║
║  ⏳ 23:00 - Monitoring Ruang Server                ║
║     Siap dari: 23:00 (3 jam lagi)                 ║
║     Status: MENUNGGU WAKTU                         ║
║     [Unlock di 23:00]                              ║
║                                                     ║
║  ⏳ 02:00 - Pengecekan Pagi                        ║
║     Siap dari: 02:00 (6 jam lagi)                 ║
║     Status: MENUNGGU WAKTU                         ║
║     [Unlock di 02:00]                              ║
║                                                     ║
║  ⏳ 05:00 - Persiapan Pagi Hari                    ║
║     Siap dari: 05:00 (9 jam lagi)                 ║
║     Status: MENUNGGU WAKTU                         ║
║     [Unlock di 05:00]                              ║
║                                                     ║
║  ⏳ 07:00 - Persiapan End-Shift                    ║
║     Siap dari: 07:00 (11 jam lagi)                ║
║     Status: MENUNGGU WAKTU                         ║
║     [Unlock di 07:00]                              ║
║                                                     ║
╠═════════════════════════════════════════════════════╣
║                                                     ║
║  💡 INFO:                                          ║
║  Periode checklist NON SHIFT tadi telah ditutup   ║
║  otomatis. Periode SHIFT 2 baru telah direset dan ║
║  kini checklist Shift 2 sudah siap dikerjakan.    ║
║                                                     ║
║  ℹ️  Semua checklist NON SHIFT hidden (tidak       ║
║  ditampilkan) untuk Shift 2 kali ini.             ║
║                                                     ║
╠═════════════════════════════════════════════════════╣
║                                                     ║
║  [   ABSEN PULANG   ]  [   LIHAT DETAIL   ]       ║
║                                                     ║
╚═════════════════════════════════════════════════════╝
```

---

## KEY VISUAL INDICATORS

### Status Icons
```
✅ = SELESAI ON-TIME
⏳ = MENUNGGU/BELUM SIAP
❌ = TIDAK SELESAI/DITOLAK
🔒 = LOCKED/HIDDEN
🟢 = SHIFT ACTIVE (USER BERADA DI SHIFT TERSEBUT)
🔵 = SHIFT ACTIVE (SHIFT LAIN SEDANG BERJALAN)
⚠️  = WARNING/PERHATIAN
💡 = INFO/HELPFUL HINT
```

### Color Coding (Untuk Implementasi)
```
Green (#27ae60)    = ON-TIME / SELESAI / ALLOWED
Red (#e74c3c)      = INCOMPLETE / ERROR / REJECTED
Orange (#f39c12)   = WARNING / PENDING / WAITING
Gray (#95a5a6)     = HIDDEN / DISABLED / LOCKED
Blue (#3498db)     = INFO / ACTIVE SHIFT
```

### Time Format
```
Jam Kerja: HH:MM (24-jam format)
Timestamp: YYYY-MM-DD HH:MM:SS
Countdown: H:MM (Jam:Menit)
Date: YYYY-MM-DD
```

---

## IMPLEMENTATION NOTES

### Required Data Points Per Checklist Item
```
1. Master ID
2. Nama Kegiatan
3. Status (pending/completed/abandoned/rejected)
4. Jam Mulai (untuk display "siap dari")
5. Kode Jam Kerja Required (untuk validation)
6. User's Current Shift
7. Current DateTime
8. Periode Status (active/closed)
9. Points (on-time vs off-time)
10. Completion Time (actual)
```

### Required API Response Fields
```
- isInWorkHours (boolean)
- kodeJamKerja (user's current shift)
- periodeKey (unique per shift)
- periodeStatus (active/closed)
- checklists[] dengan fields di atas
- totalVisible, totalHidden, stats
- error messages (jika applicable)
```

---

**Status Mockup:** ✅ COMPLETE  
**Ready untuk:** Implementation & Frontend Development
