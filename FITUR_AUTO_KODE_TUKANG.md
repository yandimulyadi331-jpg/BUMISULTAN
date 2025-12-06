# AUTO GENERATE KODE TUKANG

## 🎯 Fitur

Kode tukang sekarang **DIBUAT OTOMATIS** oleh sistem dengan format:
- **TK001** (tukang pertama)
- **TK002** (tukang kedua)
- **TK003** (tukang ketiga)
- dan seterusnya...

## ✨ Cara Kerja

### Saat Tambah Data Baru:
1. Sistem **otomatis generate** kode berikutnya
2. Field kode tukang **readonly** (tidak bisa diubah manual)
3. Format: **TK** + **3 digit angka** (001, 002, 003, ...)
4. Urutan berdasarkan data terakhir di database

### Saat Edit Data:
1. Kode tukang **tidak bisa diubah**
2. Field readonly untuk menjaga konsistensi
3. Hanya data lain yang bisa diedit

## 🔢 Contoh Urutan:

| Data Ke- | Kode Otomatis |
|----------|---------------|
| 1 | TK001 |
| 2 | TK002 |
| 3 | TK003 |
| ... | ... |
| 10 | TK010 |
| 99 | TK099 |
| 100 | TK100 |
| 999 | TK999 |

## 💡 Keuntungan:

✅ **Tidak ada duplikasi** - Sistem pastikan kode unique  
✅ **Otomatis urut** - Tidak perlu input manual  
✅ **Konsisten** - Format sama untuk semua data  
✅ **User friendly** - Tidak perlu mikir kode apa  
✅ **Anti error** - Tidak bisa salah ketik atau duplikat  

## 🛠️ Implementasi Teknis:

### Method Generate Kode:
```php
private function generateKodeTukang()
{
    // Ambil kode terakhir dari database
    $lastTukang = Tukang::orderBy('id', 'desc')->first();
    
    if (!$lastTukang) {
        return 'TK001'; // Mulai dari TK001
    }
    
    // Ekstrak nomor dan tambah 1
    preg_match('/\d+/', $lastTukang->kode_tukang, $matches);
    $newNumber = (int)$matches[0] + 1;
    
    // Format dengan 3 digit
    return 'TK' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
}
```

### Field di Form:
```html
<input type="text" name="kode_tukang" 
    value="{{ $kode_tukang }}" 
    readonly 
    style="background-color: #f0f0f0;">
<small>Kode dibuat otomatis oleh sistem</small>
```

## 📝 Catatan:

- ⚠️ Jangan hapus data tukang secara manual dari database
- ⚠️ Jika ada gap (misal TK001, TK003), sistem akan lanjut dari nomor terakhir
- ✅ Kode tetap konsisten meski ada data yang dihapus

## 🎨 Tampilan:

Field kode tukang akan tampil:
- **Warna abu-abu** (readonly)
- **Icon info** dengan keterangan "Kode dibuat otomatis"
- **Tidak bisa diklik** atau diedit

---

**Status:** ✅ Sudah Aktif
**Update:** 10 November 2025
