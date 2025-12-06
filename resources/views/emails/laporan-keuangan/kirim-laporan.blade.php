@component('mail::message')

**Yth. Penerima Laporan Keuangan,**

Terlampir kami sampaikan Laporan Keuangan untuk periode **{{ $periodeLabel }}**.

Berikut adalah ringkasan laporan keuangan:

@component('mail::panel')
**Periode Laporan**  
{{ $tanggalAwal }} - {{ $tanggalAkhir }}

---

**Ringkasan Keuangan:**

Total Pemasukan: **Rp {{ number_format($totalMasuk, 0, ',', '.') }}**  
Total Pengeluaran: **Rp {{ number_format($totalKeluar, 0, ',', '.') }}**  
Saldo Akhir: **Rp {{ number_format($saldoAkhir, 0, ',', '.') }}**
@endcomponent

Silakan buka file PDF terlampir untuk melihat laporan lengkap dan detail transaksi.

Terima kasih,

**Tim Manajemen Keuangan**  
**Bumi Sultan**

---

**(Email ini dibuat secara otomatis oleh sistem, mohon untuk tidak dibalas)**

---

**Keterangan:**

- Laporan ini dibuat secara otomatis oleh sistem untuk keperluan informasi internal.
- Apabila Anda bukan penerima yang dituju, mohon abaikan email ini dan hapus lampirannya.
- Apabila memerlukan informasi lebih lanjut, silakan menghubungi Tim Keuangan Bumi Sultan melalui email **manajemenbumisultan@gmail.com**.

---

Copyright © {{ date('Y') }} Bumi Sultan. All Rights Reserved.  
Mohon Anda tidak membalas email ini. Untuk pertanyaan atau saran, hubungi Tim Keuangan Bumi Sultan.

@endcomponent
