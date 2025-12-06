# 🚀 QUICK REFERENCE - SISTEM INVENTARIS

## 📦 AUTO-GENERATE CODES
```php
Inventaris::generateKodeInventaris()        // INV-00001
PeminjamanInventaris::generateKodePeminjaman() // PJM-00001
PengembalianInventaris::generateKodePengembalian() // KMB-00001
InventarisEvent::generateKodeEvent()        // EVT-00001
```

## 🔗 KEY RELATIONSHIPS

### Inventaris
```php
$inventaris->barang              // Barang (existing data)
$inventaris->cabang              // Cabang
$inventaris->peminjaman          // hasMany PeminjamanInventaris
$inventaris->peminjamanAktif()   // Only active borrowings
$inventaris->histories           // hasMany HistoryInventaris
$inventaris->createdBy           // User
```

### PeminjamanInventaris
```php
$peminjaman->inventaris          // Inventaris
$peminjaman->karyawan            // Karyawan (peminjam)
$peminjaman->disetujuiOleh       // User (approver)
$peminjaman->pengembalian        // PengembalianInventaris
$peminjaman->event               // InventarisEvent
```

### PengembalianInventaris
```php
$pengembalian->peminjaman        // PeminjamanInventaris
$pengembalian->diterimаOleh      // User (receiver)
```

### InventarisEvent
```php
$event->pic                      // User (person in charge)
$event->eventItems               // InventarisEventItem (pivot)
$event->inventaris               // belongsToMany Inventaris
$event->peminjaman               // hasMany PeminjamanInventaris
```

### HistoryInventaris
```php
$history->inventaris             // Inventaris
$history->karyawan               // Karyawan
$history->user                   // User (actor)
$history->peminjaman             // PeminjamanInventaris
$history->pengembalian           // PengembalianInventaris
```

## 🛠️ HELPER METHODS

### Inventaris
```php
$inventaris->isTersedia()                    // bool
$inventaris->jumlahTersedia()                // int (total - dipinjam)
$inventaris->logHistory($jenis, $desc, $data) // Log aktivitas
```

### PeminjamanInventaris
```php
$peminjaman->isTerlambat()                   // bool
$peminjaman->hariKeterlambatan()             // int (hari)
$peminjaman->hitungDenda($tarifPerHari)      // decimal
$peminjaman->setujui($userId, $catatan)      // Approve
$peminjaman->tolak($userId, $catatan)        // Reject
$peminjaman->prosesPeminjaman()              // Mark as borrowed
```

### InventarisEvent
```php
$event->addInventaris($id, $jumlah, $ket)    // Add item to event
$event->cekKetersediaanInventaris()          // Check availability
$event->distribusiKeKaryawan($karyawanIds)   // Auto-distribute
```

## 🔍 SCOPES

### Inventaris
```php
Inventaris::tersedia()->get()                // Status = tersedia
Inventaris::byKategori('Elektronik')->get()
Inventaris::byCabang($cabangId)->get()
```

### PeminjamanInventaris
```php
PeminjamanInventaris::aktif()->get()         // disetujui|dipinjam|terlambat
PeminjamanInventaris::terlambat()->get()
PeminjamanInventaris::byKaryawan($id)->get()
PeminjamanInventaris::byEvent($eventId)->get()
```

### InventarisEvent
```php
InventarisEvent::aktif()->get()              // persiapan|berlangsung
InventarisEvent::byJenis('Naik Gunung')->get()
InventarisEvent::upcoming()->get()           // Order by date
```

### HistoryInventaris
```php
HistoryInventaris::byInventaris($id)->get()
HistoryInventaris::byJenisAktivitas('pinjam')->get()
HistoryInventaris::byKaryawan($id)->get()
HistoryInventaris::recent(30)->get()         // Last 30 days
```

## 📋 COMMON QUERIES

### Get Available Inventaris
```php
$available = Inventaris::where('status', 'tersedia')
    ->where(function($q) {
        $q->whereDoesntHave('peminjamanAktif')
          ->orWhereHas('peminjaman', function($q2) {
              $q2->where('status', 'dikembalikan');
          });
    })->get();
```

### Get Peminjaman Terlambat
```php
$terlambat = PeminjamanInventaris::where('status', 'dipinjam')
    ->where('tanggal_kembali_rencana', '<', now())
    ->with(['inventaris', 'karyawan'])
    ->get();
```

### Get Top Inventaris
```php
$top = Inventaris::withCount('peminjaman')
    ->orderByDesc('peminjaman_count')
    ->limit(10)
    ->get();
```

### Get History by Date Range
```php
$history = HistoryInventaris::whereBetween('created_at', [$start, $end])
    ->with(['inventaris', 'karyawan', 'user'])
    ->get();
```

## 🎨 STATUS VALUES

### Inventaris Status
- `tersedia` - Available
- `dipinjam` - Borrowed
- `maintenance` - Under maintenance
- `rusak` - Damaged
- `hilang` - Lost

### Peminjaman Status
- `menunggu_approval` - Waiting for approval
- `disetujui` - Approved
- `ditolak` - Rejected
- `dipinjam` - Currently borrowed
- `dikembalikan` - Returned
- `terlambat` - Late

### Event Status
- `persiapan` - Preparation
- `berlangsung` - Ongoing
- `selesai` - Completed
- `dibatalkan` - Cancelled

### Kondisi Barang
- `baik` - Good condition
- `rusak_ringan` - Minor damage
- `rusak_berat` - Major damage
- `hilang` - Lost

### Jenis Aktivitas (History)
- `input` - New item
- `update` - Data updated
- `pinjam` - Borrowed
- `kembali` - Returned
- `pindah_lokasi` - Location changed
- `maintenance` - Maintenance
- `perbaikan` - Repair
- `hapus` - Deleted

## 📊 VALIDATION RULES

### Inventaris
```php
'nama_barang' => 'required|string|max:255',
'kategori' => 'required|string',
'jumlah' => 'required|integer|min:1',
'satuan' => 'required|string',
'kondisi' => 'required|in:baik,rusak ringan,rusak berat',
'cabang_id' => 'required|exists:cabangs,id',
'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
```

### Peminjaman
```php
'inventaris_id' => 'required|exists:inventaris,id',
'karyawan_id' => 'required|exists:karyawans,id',
'jumlah_pinjam' => 'required|integer|min:1',
'tanggal_pinjam' => 'required|date',
'tanggal_kembali_rencana' => 'required|date|after:tanggal_pinjam',
'keperluan' => 'required|string',
'ttd_peminjam' => 'required|string',
```

### Pengembalian
```php
'peminjaman_inventaris_id' => 'required|exists:peminjaman_inventaris,id',
'tanggal_pengembalian' => 'required|date',
'jumlah_kembali' => 'required|integer|min:1',
'kondisi_barang' => 'required|in:baik,rusak_ringan,rusak_berat,hilang',
'ttd_peminjam' => 'required|string',
'ttd_petugas' => 'required|string',
```

## 🔗 ROUTE PATTERNS

```
/inventaris                           GET|POST
/inventaris/create                    GET
/inventaris/{id}                      GET|PUT|DELETE
/inventaris/{id}/edit                 GET
/inventaris/import-barang             POST
/inventaris/export-pdf                GET

/peminjaman-inventaris                GET|POST
/peminjaman-inventaris/{id}/setujui   POST
/peminjaman-inventaris/{id}/tolak     POST

/pengembalian-inventaris              GET|POST
/pengembalian-inventaris/create/{id}  GET

/inventaris-event                     GET|POST
/inventaris-event/{id}/distribusi     POST

/history-inventaris                   GET
/history-inventaris/dashboard         GET
```

## 💾 STORAGE PATHS

```
storage/app/public/inventaris/        - Foto inventaris
storage/app/public/peminjaman/        - Foto peminjaman
storage/app/public/pengembalian/      - Foto pengembalian
```

## 🎯 PERMISSION SUGGESTIONS

```php
// Inventaris
'inventaris.view'
'inventaris.create'
'inventaris.edit'
'inventaris.delete'
'inventaris.import'

// Peminjaman
'peminjaman.view'
'peminjaman.create'
'peminjaman.approve'
'peminjaman.reject'

// Pengembalian
'pengembalian.view'
'pengembalian.process'

// Event
'event.view'
'event.create'
'event.manage'
'event.distribute'

// History
'history.view'
'history.export'
```

## 🐛 DEBUGGING TIPS

```php
// Check inventaris availability
dd($inventaris->jumlahTersedia());

// Check if late
dd($peminjaman->isTerlambat());

// Check relationships loaded
dd($inventaris->relationLoaded('peminjaman'));

// Log query
DB::enableQueryLog();
// ... your query ...
dd(DB::getQueryLog());

// Check event readiness
dd($event->eventItems->every->isReady());
```

## 📊 USEFUL AGGREGATES

```php
// Total inventaris
Inventaris::count();

// Total value
Inventaris::sum('harga_perolehan');

// Peminjaman aktif
PeminjamanInventaris::aktif()->count();

// Total denda
PengembalianInventaris::sum('denda');

// By kategori
Inventaris::groupBy('kategori')
    ->selectRaw('kategori, COUNT(*) as total')
    ->get();
```

---

**Version:** 1.0
**Last Update:** 2025-11-06
