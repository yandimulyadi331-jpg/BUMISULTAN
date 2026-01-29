# Template Penagihan - Format Surat Tagihan Profesional

## 📋 Update Template

Template penagihan telah diupdate dengan format **Surat Tagihan Keuangan** profesional seperti surat tagihan bank, dengan struktur:

```
╔════════════════════════════════════════════════════════════╗
║              SURAT TAGIHAN KEUANGAN - BUMI SULTAN          ║
╚════════════════════════════════════════════════════════════╝

📋 INFORMASI SURAT
- Nomor Surat
- Perihal
- Tanggal

DATA PEMINJAM
- Nama
- No. Identitas
- No. Telp/WA
- Alamat

DATA PINJAMAN
- Nomor Pinjaman
- Tanggal Pencairan
- Jumlah Pinjaman Pokok
- Tenor Pinjaman
- Cicilan per Bulan
- Sisa Pokok Pinjaman
- Terbayar

STATUS PEMBAYARAN
- Cicilan Ke
- Tanggal Jatuh Tempo
- Status (TERTUNGGAK)
- Nominal Cicilan

METODE PEMBAYARAN
- Bank BCA
- Atas Nama: YANDI MULYADI
- No. Rekening: 4061932571

PENUTUP
- Hormat kami
- BUMI SULTAN
- Bagian Keuangan
- Alamat lengkap
```

---

## 🎯 Komponen Template

### 1. Header (Judul Surat)
```
╔════════════════════════════════════════════════════════════╗
║              SURAT TAGIHAN KEUANGAN - BUMI SULTAN          ║
╚════════════════════════════════════════════════════════════╝
```

### 2. Informasi Surat
```
📋 INFORMASI SURAT
Nomor Surat : BS/KEU/MM/YYYY
Perihal : Pemberitahuan & Penagihan Kewajiban Pembayaran
Tanggal : [Tanggal Hari Ini]
```

### 3. Kepala Surat (Alamat Tujuan)
```
Kepada Yth,

👤 DATA PEMINJAM
Nama : [Nama Peminjam]
No. Identitas : [NIK]
No. Telp/WA : [No HP]
Alamat : [Alamat]
```

### 4. Isi Surat (Data Pinjaman)
```
💼 DATA PINJAMAN
• Nomor Pinjaman : [No]
• Tanggal Pencairan : [Tgl]
• Jumlah Pinjaman Pokok : Rp [Jumlah]
• Tenor Pinjaman : [Bulan]
• Cicilan per Bulan : Rp [Jumlah]
• Sisa Pokok Pinjaman : Rp [Jumlah]
• Terbayar : [%]

⏰ STATUS PEMBAYARAN
• Cicilan Ke : [No]
• Tanggal Jatuh Tempo : [Tgl]
• Status : ⚠️ TERTUNGGAK ([N] hari)
• Nominal Cicilan : Rp [Jumlah]
```

### 5. Metode Pembayaran
```
💳 METODE PEMBAYARAN
Pembayaran dapat dilakukan melalui Transfer Bank:

🏦 BANK BCA
Atas Nama : YANDI MULYADI
No. Rekening : 4061932571
```

### 6. Penutup Resmi
```
PENUTUP

Demikian surat pemberitahuan dan penagihan ini kami sampaikan.
Atas perhatian dan kerja sama Saudara/i, kami ucapkan
terima kasih.

Hormat kami,

BUMI SULTAN
Bagian Keuangan

📍 Jl. Raya Jonggol No.37, RT.02/RW.02, Jonggol,
Kec. Jonggol, Kabupaten Bogor, Jawa Barat 16830
```

---

## 📝 Format Penempatan Data

| Data | Format | Sumber |
|------|--------|--------|
| Nomor Surat | BS/KEU/MM/YYYY | Generated otomatis |
| Tanggal | DD-MM-YYYY | Tanggal sekarang |
| Nama Peminjam | Text | `nama_peminjam_lengkap` |
| NIK | Text | `nik_peminjam` |
| No Telp | Text | `no_telp_peminjam` |
| Alamat | Text | `alamat_peminjam` |
| No Pinjaman | Text | `nomor_pinjaman` |
| Tgl Pencairan | DD-MM-YYYY | `tanggal_pencairan` |
| Total Pinjaman | Rp XXX.XXX | `total_pinjaman` |
| Tenor | Number | `tenor_bulan` |
| Cicilan/Bulan | Rp XXX.XXX | `cicilan_per_bulan` |
| Sisa Pinjaman | Rp XXX.XXX | `sisa_pinjaman` |
| Persentase Bayar | XXX% | `persentase_pembayaran` |
| Cicilan Ke | Number | `cicilan_ke` |
| Tgl Jatuh Tempo | DD-MM-YYYY | `tanggal_jatuh_tempo` |
| Hari Tertunda | Number hari | `hari_tertunda` |
| Nominal Cicilan | Rp XXX.XXX | `jumlah_cicilan` |

---

## 🎨 Styling WhatsApp

Template menggunakan formatting WhatsApp:
- **Text tebal** → `*text*`
- **Garis pemisah** → `═` (karakter Unicode)
- **Icon emoji** → 💼, 👤, 📊, ⏰, 💳, ⚠️, 📍
- **Box header** → ╔ ╗ ╚ ╝ (karakter Unicode)

---

## 📱 Tampilan di WhatsApp

Template akan terlihat profesional dan terstruktur di aplikasi WhatsApp dengan:
- ✅ Format surat resmi yang rapi
- ✅ Data lengkap dan terorganisir
- ✅ Informasi pembayaran jelas
- ✅ Tanda urgency (⚠️ TERTUNGGAK)
- ✅ Branding BUMI SULTAN resmi

---

## 🔧 Kustomisasi

Jika ingin mengubah template:

1. **Edit nama bank/rekening:** Ubah di method `getPesanPenagihan()` baris bank BCA
2. **Edit alamat:** Ubah di bagian PENUTUP
3. **Edit struktur:** Edit string `$pesan` di method tersebut
4. **Edit nomor surat format:** Ubah `BS/KEU/` + format tanggal

---

## ✅ Testing

Setelah update, test dengan:

1. Buka Dashboard
2. Klik icon WhatsApp di section "Pinjaman Jatuh Tempo"
3. Konfirmasi dialog
4. WhatsApp akan terbuka dengan template surat tagihan lengkap
5. Verify semua data sudah terisi dengan benar

---

**Status:** ✅ Implemented - Format Surat Tagihan Profesional
