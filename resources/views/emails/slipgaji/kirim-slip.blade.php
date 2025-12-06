@component('mail::message')

**Yth. {{ $karyawan->nama_karyawan }},**

Terlampir kami sampaikan Slip Gaji untuk periode **{{ getNamabulan($bulan) }} {{ $tahun }}**.

**Data Karyawan:**

<table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
<tr>
    <td style="padding: 8px; border: 1px solid #ddd; width: 40%;"><strong>NIK</strong></td>
    <td style="padding: 8px; border: 1px solid #ddd;">{{ $karyawan->nik }}</td>
</tr>
<tr>
    <td style="padding: 8px; border: 1px solid #ddd;"><strong>Nama</strong></td>
    <td style="padding: 8px; border: 1px solid #ddd;">{{ $karyawan->nama_karyawan }}</td>
</tr>
@if(isset($karyawan->jabatan))
<tr>
    <td style="padding: 8px; border: 1px solid #ddd;"><strong>Jabatan</strong></td>
    <td style="padding: 8px; border: 1px solid #ddd;">{{ $karyawan->jabatan->nama_jabatan ?? '-' }}</td>
</tr>
@endif
@if(isset($karyawan->departemen))
<tr>
    <td style="padding: 8px; border: 1px solid #ddd;"><strong>Departemen</strong></td>
    <td style="padding: 8px; border: 1px solid #ddd;">{{ $karyawan->departemen->nama_dept ?? '-' }}</td>
</tr>
@endif
<tr>
    <td style="padding: 8px; border: 1px solid #ddd;"><strong>Periode Gaji</strong></td>
    <td style="padding: 8px; border: 1px solid #ddd;">{{ getNamabulan($bulan) }} {{ $tahun }}</td>
</tr>
</table>

@if($pdfPath && file_exists($pdfPath))
@component('mail::panel')
**Lampiran Slip Gaji**

Silakan buka file PDF terlampir untuk melihat detail lengkap slip gaji Anda termasuk rincian gaji pokok, tunjangan, potongan, dan take home pay.
@endcomponent
@else
@component('mail::panel')
**Informasi**

Untuk melihat detail lengkap slip gaji Anda, silakan akses melalui aplikasi atau hubungi bagian HRD.
@endcomponent
@endif

---

**Catatan Penting:**

- Slip gaji ini bersifat rahasia dan hanya untuk keperluan pribadi Anda.
- Jika terdapat pertanyaan atau ketidaksesuaian data, silakan segera hubungi bagian HRD.
- Pastikan semua data yang tertera sudah sesuai dengan catatan Anda.

---

**Kontak HRD:**

Untuk pertanyaan atau informasi lebih lanjut, silakan hubungi:
- Telepon: 0857-1537-5490
- Email: manajemenbumisultan@gmail.com
- Kunjungi: Bagian HRD (Senin-Jumat, 08:00-17:00 WIB)

Terima kasih,

**Tim HRD**  
**Bumi Sultan**

---

**(Email ini dibuat secara otomatis oleh sistem, mohon untuk tidak dibalas)**

---

**Keterangan:**

- Slip gaji ini bersifat rahasia dan hanya untuk keperluan pribadi Anda.
- Apabila Anda bukan penerima yang dituju, mohon abaikan dan hapus email ini.
- Untuk pertanyaan terkait slip gaji, silakan hubungi bagian HRD.

Copyright © {{ date('Y') }} Bumi Sultan. All Rights Reserved.  
Mohon Anda tidak membalas email ini. Untuk pertanyaan atau saran, hubungi Tim HRD Bumi Sultan.

@endcomponent
