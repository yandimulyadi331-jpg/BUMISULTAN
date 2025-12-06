# 🎯 PANDUAN ADMIN - MANAJEMEN DOKUMEN

## 👤 Role & Permission

### Super Admin (Full Access)
✅ Tambah dokumen baru
✅ Edit dokumen apapun
✅ Hapus dokumen
✅ View semua dokumen (termasuk Restricted)
✅ Download semua dokumen
✅ Lihat access logs
✅ Manage kategori dokumen

### User Biasa (Limited Access)
✅ View dokumen Public & View-Only
✅ Download dokumen Public saja
❌ Tidak bisa view dokumen Restricted
❌ Tidak bisa edit/hapus
❌ Tidak bisa lihat access logs

---

## 🚀 Workflow Admin

### 1️⃣ Tambah Dokumen Baru

**Scenario: Upload Surat Keputusan**

```
STEP 1: Buka Menu
Fasilitas & Asset → Manajemen Dokumen → Tambah Dokumen

STEP 2: Isi Informasi Dasar
- Nama Dokumen: "Surat Keputusan Pengangkatan Direktur 2024"
- Kategori: SK (Surat Keputusan)
- Status: Aktif
- Deskripsi: "SK tentang pengangkatan direktur baru periode 2024-2029"

STEP 3: Upload File
- Pilih: Upload File
- Browse file: SK_Direktur_2024.pdf
- Ukuran maks: 10MB

STEP 4: Isi Lokasi Fisik (Penting!)
- Nomor Loker: L001
- Lokasi Loker: Ruang Arsip Lantai 2
- Rak: R1
- Baris: B1

💡 Preview Kode: SK-001-L001 (auto-generated)

STEP 5: Pilih Access Level
- Restricted ← Pilih ini (hanya admin bisa akses)

STEP 6: Isi Metadata
- Tanggal Dokumen: 01/01/2024
- Tanggal Berlaku: 01/01/2024
- Tanggal Berakhir: 31/12/2029
- Nomor Referensi: 001/SK/DIR/2024
- Penerbit: Dewan Komisaris
- Tags: sk, direktur, 2024, pengangkatan

STEP 7: Simpan
Klik "Simpan Dokumen"

✅ RESULT: Dokumen dengan kode SK-001-L001 berhasil tersimpan
```

---

### 2️⃣ Tambah Link Eksternal (Google Drive)

**Scenario: Simpan MOU di Google Drive**

```
STEP 1-2: Sama seperti di atas

STEP 3: Pilih Link Eksternal
- Pilih: Link Eksternal
- URL: https://drive.google.com/file/d/xxxxx/view
  
STEP 4: Loker (Opsional untuk link)
- Kosongkan atau isi jika ada dokumen fisik juga

STEP 5: Access Level
- View Only ← Semua bisa lihat, tapi tidak download
  (karena sudah di Google Drive)

STEP 6: Isi metadata seperti biasa

STEP 7: Simpan

✅ RESULT: Link tersimpan dengan kode MOU-001-L000
```

---

### 3️⃣ Organize Loker Fisik

**Best Practice:**

```
FORMAT KODE LOKER: L001, L002, L003...

STRUKTUR:
Loker → Rak → Baris

CONTOH:
L001 = Loker di Ruang Arsip Lt.2
  ├── R1 = Rak 1
  │   ├── B1 = Baris 1 → SK-001-L001
  │   ├── B2 = Baris 2 → SK-002-L001
  │   └── B3 = Baris 3 → SK-003-L001
  ├── R2 = Rak 2
  │   └── B1 = Baris 1 → PKS-001-L001
  └── R3 = Rak 3

L002 = Loker di Ruang Arsip Lt.3
  └── R1
      └── B1 → KTK-001-L002
```

**Labeling Fisik:**
1. Print label loker: "L001 - Ruang Arsip Lt.2"
2. Tempel di loker fisik
3. Atur dokumen sesuai rak & baris
4. Update di sistem

---

### 4️⃣ Manage Access Control

**Scenario Decision Tree:**

```
Dokumen Sensitif/Rahasia?
├─ YES → Restricted
│   Contoh: SK Gaji, Kontrak Rahasia, NDA
│   ✅ Hanya admin bisa view & download
│
├─ NO, tapi tidak ingin didownload sembarangan?
│   └─ View Only
│       Contoh: SOP Internal, Laporan Tahunan
│       ✅ Semua bisa baca, tidak bisa download
│
└─ NO, boleh public?
    └─ Public
        Contoh: Pengumuman, Prosedur Umum
        ✅ Semua bisa baca & download
```

**Contoh Penerapan:**

| Jenis Dokumen | Access Level | Alasan |
|---------------|--------------|--------|
| SK Pengangkatan Direksi | Restricted | Sensitif |
| SK Libur Nasional | Public | Umum |
| Kontrak Karyawan | Restricted | Pribadi |
| SOP Absensi | View Only | Internal tapi tidak perlu download |
| MOU dengan Mitra | View Only | Pihak ketiga bisa lihat, tidak download |
| Laporan Keuangan | Restricted | Rahasia |
| Buku Panduan | Public | Edukasi |

---

### 5️⃣ Monitoring & Maintenance

**Check Berkala (Weekly/Monthly):**

```
✅ DOKUMEN EXPIRED
- Filter: Status = Kadaluarsa
- Action: Update atau arsipkan

✅ DOKUMEN MENDEKATI EXPIRED
- Check tanggal berakhir
- Persiapkan renewal

✅ LOKER MANAGEMENT
- Pastikan loker fisik teratur
- Label masih terbaca
- Dokumen di tempat yang benar

✅ ACCESS LOGS REVIEW
- Lihat siapa saja yang akses dokumen
- Detect unusual activity
- Compliance check
```

**Dashboard Quick Check:**
```
Buka Manajemen Dokumen →
Lihat statistik:
- Total dokumen: XXX
- Aktif: XXX
- Kadaluarsa: XXX
- Most viewed: ???
- Most downloaded: ???
```

---

### 6️⃣ Backup & Security

**Best Practice:**

```
1. REGULAR BACKUP
   - Backup database weekly
   - Backup folder storage/documents monthly
   - Keep 3 months retention

2. ACCESS CONTROL
   - Review access level regularly
   - Update jika ada perubahan kebijakan
   - Monitor access logs

3. FILE MANAGEMENT
   - Jangan upload file >10MB (compress dulu)
   - Gunakan nama file yang jelas
   - Konsisten dengan naming convention

4. DOCUMENTATION
   - Update metadata lengkap
   - Isi tags untuk search
   - Referensi nomor surat dengan benar
```

---

### 7️⃣ Training User

**Checklist Training:**

```
✅ Cara akses menu
✅ Cara search dokumen
✅ Cara preview dokumen
✅ Cara download (jika ada akses)
✅ Memahami access level
✅ Cara cari dokumen fisik by kode loker
✅ Siapa yang dihubungi jika butuh akses khusus
```

**Demo Script:**
```
1. "Ini cara cari dokumen di sistem..."
2. "Ketik kode atau nama dokumen di search box"
3. "Klik icon mata untuk preview"
4. "Lihat kode dokumen, contoh SK-001-L001"
5. "L001 itu nomor loker fisiknya"
6. "Kalau butuh dokumen fisik, pergi ke loker tersebut"
7. "Kalau tidak bisa download, berarti restricted atau view only"
8. "Hubungi admin jika butuh akses khusus"
```

---

## 🚨 Troubleshooting Admin

### Problem 1: User tidak bisa download dokumen
```
CHECK:
1. Access level dokumen = Public?
2. User punya role yang benar?
3. File masih ada di storage?

FIX:
- Edit dokumen, ubah access level ke Public
- Atau jelaskan ke user bahwa dokumen restricted
```

### Problem 2: Preview dokumen tidak muncul
```
CHECK:
1. php artisan storage:link sudah jalan?
2. File extension supported? (PDF, JPG, PNG)
3. File corrupt?

FIX:
php artisan storage:link
Atau re-upload file
```

### Problem 3: Kode dokumen duplicate
```
TIDAK MUNGKIN!
Sistem auto-generate unique per kategori.

Jika terjadi:
- Check database integrity
- Contact developer
```

### Problem 4: Loker fisik tidak terorganisir
```
FIX:
1. Buat sistem label fisik
2. Update di sistem sesuai realita
3. Training staff untuk disiplin
4. Audit berkala
```

---

## 📊 Reports & Analytics

**Monthly Report Checklist:**

```
✅ Total dokumen baru bulan ini
✅ Dokumen paling banyak diakses
✅ Dokumen yang akan expired bulan depan
✅ User yang paling aktif akses dokumen
✅ Dokumen yang belum pernah diakses
✅ Loker yang paling penuh
```

**How to Generate:**
```
1. Export data dari index page
2. Filter by tanggal created_at
3. Sort by jumlah_view atau jumlah_download
4. Check tanggal_berakhir untuk expired docs
5. Query access_logs untuk user activity
```

---

## 🎓 Admin Tips & Tricks

1. **Batch Upload**
   ```
   - Upload multiple files sekaligus
   - Gunakan naming convention yang jelas
   - Example: SK_2024_001_Pengangkatan.pdf
   ```

2. **Kategori Baru** (Future)
   ```
   - Tambah kategori di database jika perlu
   - Pastikan kode unik (3-5 huruf)
   - Pilih warna yang berbeda
   ```

3. **Shortcut Search**
   ```
   - Cari by kode: SK-001
   - Cari by loker: L001
   - Cari by tag: kontrak
   - Kombinasi: SK L001
   ```

4. **Quick Filter**
   ```
   - Bookmark filter favorites
   - Example: /dokumen?category_id=1&status=aktif
   ```

5. **Maintenance Mode**
   ```
   - Saat maintenance, export semua data
   - Backup files ke external storage
   - Update dokumen expired secara batch
   ```

---

## 📞 Escalation Path

```
Level 1: User Support
- Cara search & download
- Penjelasan access level
→ Solved by: Admin

Level 2: Technical Issue  
- Preview tidak muncul
- File corrupt
- Upload error
→ Solved by: IT Support

Level 3: System Error
- Database issue
- Permission error
- Bug/Feature request
→ Solved by: Developer
```

---

## 🎯 Success Metrics

**KPI untuk Sistem Dokumen:**

```
✅ Adoption Rate: 80%+ user aktif gunakan sistem
✅ Search Success: 90%+ user temukan dokumen <2 menit
✅ Access Control: 0 unauthorized access
✅ Compliance: 100% dokumen penting ter-track
✅ Physical Match: 95%+ dokumen fisik sesuai sistem
✅ Expired Management: 100% dokumen expired dihandle
```

---

## 🎉 Admin Checklist - Go Live

**Before Launch:**
```
✅ Migration success
✅ Seeder running
✅ Storage linked
✅ Test upload file
✅ Test upload link
✅ Test download
✅ Test preview
✅ Test access control
✅ Test search & filter
✅ Train super admin
✅ Train users
✅ Prepare loker fisik
✅ Print labels
✅ Backup plan ready
```

**After Launch:**
```
✅ Monitor first week closely
✅ Collect feedback
✅ Quick fixes if needed
✅ Follow up training
✅ Document lessons learned
```

---

**SISTEM SIAP DIGUNAKAN! 🚀**

*Good luck, Admin! You got this! 💪*

---

*Last Updated: 7 Nov 2024*
*Admin Guide v1.0*
