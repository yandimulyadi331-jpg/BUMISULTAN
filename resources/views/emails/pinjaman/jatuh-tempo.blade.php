@component('mail::message')
# Pengingat Pembayaran Cicilan Pinjaman

@if($tipeNotifikasi === 'jatuh_tempo_hari_ini')
**HARI INI** adalah tanggal jatuh tempo cicilan pinjaman Anda.
@elseif($tipeNotifikasi === 'jatuh_tempo_besok')
**BESOK** adalah tanggal jatuh tempo cicilan pinjaman Anda.
@elseif($tipeNotifikasi === 'jatuh_tempo_3_hari')
Cicilan pinjaman Anda akan jatuh tempo dalam **3 hari**.
@elseif($tipeNotifikasi === 'jatuh_tempo_7_hari')
Cicilan pinjaman Anda akan jatuh tempo dalam **7 hari**.
@elseif($tipeNotifikasi === 'sudah_lewat_jatuh_tempo')
⚠️ **PENTING:** Cicilan pinjaman Anda sudah melewati tanggal jatuh tempo.
@endif

## Detail Pinjaman

<table style="width: 100%; border-collapse: collapse;">
<tr>
    <td style="padding: 8px; border: 1px solid #ddd;"><strong>Nomor Pinjaman</strong></td>
    <td style="padding: 8px; border: 1px solid #ddd;">{{ $pinjaman->nomor_pinjaman }}</td>
</tr>
<tr>
    <td style="padding: 8px; border: 1px solid #ddd;"><strong>Nama Peminjam</strong></td>
    <td style="padding: 8px; border: 1px solid #ddd;">
        @if($pinjaman->kategori_peminjam === 'crew' && $pinjaman->karyawan)
            {{ $pinjaman->karyawan->nama_lengkap ?? $pinjaman->nama_peminjam }}
        @else
            {{ $pinjaman->nama_peminjam }}
        @endif
    </td>
</tr>
<tr>
    <td style="padding: 8px; border: 1px solid #ddd;"><strong>Cicilan Per Bulan</strong></td>
    <td style="padding: 8px; border: 1px solid #ddd;">Rp {{ number_format($pinjaman->cicilan_per_bulan, 0, ',', '.') }}</td>
</tr>
<tr>
    <td style="padding: 8px; border: 1px solid #ddd;"><strong>Total Pinjaman</strong></td>
    <td style="padding: 8px; border: 1px solid #ddd;">Rp {{ number_format($pinjaman->total_pinjaman, 0, ',', '.') }}</td>
</tr>
<tr>
    <td style="padding: 8px; border: 1px solid #ddd;"><strong>Total Terbayar</strong></td>
    <td style="padding: 8px; border: 1px solid #ddd;">Rp {{ number_format($pinjaman->total_terbayar, 0, ',', '.') }}</td>
</tr>
<tr>
    <td style="padding: 8px; border: 1px solid #ddd;"><strong>Sisa Pinjaman</strong></td>
    <td style="padding: 8px; border: 1px solid #ddd;">Rp {{ number_format($pinjaman->sisa_pinjaman, 0, ',', '.') }}</td>
</tr>
<tr>
    <td style="padding: 8px; border: 1px solid #ddd;"><strong>Tanggal Jatuh Tempo</strong></td>
    <td style="padding: 8px; border: 1px solid #ddd;">
        @php
            $now = \Carbon\Carbon::now();
            $tanggalJatuhTempo = \Carbon\Carbon::create($now->year, $now->month, $pinjaman->tanggal_jatuh_tempo_setiap_bulan);
            if ($tanggalJatuhTempo->isPast()) {
                $tanggalJatuhTempo->addMonth();
            }
        @endphp
        {{ $tanggalJatuhTempo->format('d F Y') }}
    </td>
</tr>
</table>

@if($tipeNotifikasi === 'sudah_lewat_jatuh_tempo')
@component('mail::panel')
⚠️ **Peringatan Keterlambatan**

Pembayaran Anda sudah melewati tanggal jatuh tempo. Mohon segera lakukan pembayaran untuk menghindari denda keterlambatan.
@endcomponent
@endif

## 💳 Cara Pembayaran

Silakan hubungi bagian keuangan **Bumi Sultan** untuk melakukan pembayaran cicilan:

- **📞 Telepon:** 0857-1537-5490
- **📧 Email:** manajemenbumisultan@gmail.com
- **🏢 Kantor:** Datang langsung pada hari kerja (Senin-Jumat, 08:00-17:00 WIB)
- **💰 Transfer Bank:** Hubungi keuangan untuk nomor rekening

---

<div style="background: #f8f9fa; padding: 15px; border-left: 4px solid #007bff; margin: 20px 0;">
    <strong>ℹ️ Informasi:</strong><br>
    Email ini dikirim secara otomatis oleh Sistem Manajemen Pinjaman Bumi Sultan. Jika Anda sudah melakukan pembayaran, harap abaikan email ini atau konfirmasi ke bagian keuangan.
</div>

Terima kasih atas kepercayaan Anda kepada **Bumi Sultan**.

Hormat kami,<br>
**Tim Keuangan Bumi Sultan**<br>
📞 0857-1537-5490 | 📧 manajemenbumisultan@gmail.com
@endcomponent
