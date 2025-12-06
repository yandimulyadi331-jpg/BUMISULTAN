# 📋 DOKUMENTASI LOGIKA INTEGRASI DOKUMEN ADMIN-KARYAWAN

**Tanggal:** 16 November 2025  
**Versi:** 2.0  
**Status:** ✅ IMPLEMENTED

---

## 🎯 OVERVIEW SISTEM

Sistem Manajemen Dokumen terintegrasi penuh antara **Mode Admin** dan **Mode Karyawan** dengan pembagian akses yang jelas:

### Mode Admin (`/dokumen`)
- ✅ **Full CRUD Operations** (Create, Read, Update, Delete)
- ✅ Upload dokumen (file atau link eksternal)
- ✅ Set level akses: `public`, `view_only`, `restricted`
- ✅ Manajemen kategori, loker, dan metadata lengkap
- ✅ View all documents tanpa filter

### Mode Karyawan (`/fasilitas/dokumen-karyawan`)
- ✅ **READ ONLY** - Tidak bisa create/edit/delete
- ✅ Auto-filter: Hanya tampil dokumen `public` dan `view_only`
- ✅ Dokumen `restricted` otomatis hidden
- ✅ Mobile-friendly interface
- ✅ Real-time sync dengan admin uploads

---

## 🔄 ALUR INTEGRASI DATA

### 1️⃣ **Admin Upload Dokumen File**

```
ADMIN ACTION:
1. Admin masuk ke /dokumen
2. Klik "Tambah Dokumen"
3. Pilih "Jenis Dokumen: File"
4. Upload file (max 10MB)
5. Set "Access Level: Public" atau "View Only"
6. Simpan

DATABASE:
- Table: documents
- Field jenis_dokumen: "file"
- Field file_path: "documents/timestamp_filename.ext"
- Field access_level: "public" atau "view_only"

KARYAWAN VIEW:
✅ Dokumen langsung muncul di /fasilitas/dokumen-karyawan
✅ Badge "BARU" muncul (jika < 7 hari)
✅ Badge biru "File" ditampilkan
✅ Tombol "Lihat" membuka modal detail
✅ Tombol "Download" untuk download file
```

---

### 2️⃣ **Admin Upload Dokumen Link**

```
ADMIN ACTION:
1. Admin masuk ke /dokumen
2. Klik "Tambah Dokumen"
3. Pilih "Jenis Dokumen: Link"
4. Input URL (https://example.com/doc.pdf)
5. Set "Access Level: Public" atau "View Only"
6. Simpan

DATABASE:
- Table: documents
- Field jenis_dokumen: "link"
- Field file_path: "https://example.com/doc.pdf"
- Field jenis_file: "link"
- Field access_level: "public" atau "view_only"

KARYAWAN VIEW:
✅ Dokumen langsung muncul di /fasilitas/dokumen-karyawan
✅ Badge "BARU" muncul (jika < 7 hari)
✅ Badge pink "Link" ditampilkan
✅ Tombol "Buka Link" membuka URL di tab baru
✅ Log akses tetap tercatat
```

---

### 3️⃣ **Admin Set Dokumen Restricted**

```
ADMIN ACTION:
1. Admin edit dokumen
2. Ubah "Access Level: Restricted"
3. Simpan

DATABASE:
- Field access_level: "restricted"

KARYAWAN VIEW:
❌ Dokumen TIDAK MUNCUL di listing karyawan
❌ Filter controller: whereIn('access_level', ['public', 'view_only'])
```

---

## 🗂️ STRUKTUR DATABASE

### Tabel: `documents`

| Field | Type | Keterangan |
|-------|------|------------|
| `id` | bigint | Primary key |
| `kode_dokumen` | varchar | Auto-generate: LPR-001-L000 |
| `nama_dokumen` | varchar | Nama dokumen |
| `document_category_id` | bigint | FK ke document_categories |
| `deskripsi` | text | Deskripsi opsional |
| `jenis_dokumen` | enum | **'file' atau 'link'** |
| `file_path` | varchar | Path file atau URL link |
| `file_size` | varchar | Ukuran file (null untuk link) |
| `file_extension` | varchar | Extension (null untuk link) |
| `jenis_file` | varchar | MIME type atau 'link' |
| `access_level` | enum | **'public', 'view_only', 'restricted'** |
| `nomor_loker` | varchar | Nomor loker fisik |
| `lokasi_loker` | varchar | Lokasi loker |
| `rak` | varchar | Rak penyimpanan |
| `baris` | varchar | Baris penyimpanan |
| `status` | enum | 'aktif', 'arsip', 'kadaluarsa' |
| `jumlah_view` | int | Counter view |
| `jumlah_download` | int | Counter download |
| `created_at` | timestamp | Tanggal upload |
| `updated_at` | timestamp | Tanggal update |

---

## 🛠️ CONTROLLER LOGIC

### File: `app/Http/Controllers/DokumenController.php`

#### **Constructor Middleware**
```php
public function __construct()
{
    $this->middleware('auth');
    
    // Super admin only untuk create/edit/delete
    $this->middleware('role:super admin')->only([
        'create', 'store', 'edit', 'update', 'destroy'
    ]);
}
```

#### **Method: index() - Admin**
```php
public function index()
{
    // Auto-redirect non-admin ke view karyawan
    if (!auth()->user()->hasRole('super admin')) {
        return redirect()->route('dokumen.karyawan.index');
    }
    
    // Admin lihat semua dokumen
    $documents = Document::with(['category', 'uploader'])->latest()->paginate(15);
    return view('dokumen.index', compact('documents'));
}
```

#### **Method: indexKaryawan() - Karyawan**
```php
public function indexKaryawan(Request $request)
{
    $query = Document::with(['category', 'uploader']);
    
    // ✅ FILTER OTOMATIS: Hanya public dan view_only
    $query->whereIn('access_level', ['public', 'view_only']);
    
    // Search & filter
    if ($request->filled('search')) {
        $query->search($request->search);
    }
    
    $documents = $query->latest()->paginate(10);
    return view('dokumen.index-karyawan', compact('documents'));
}
```

#### **Method: showKaryawan() - AJAX Detail**
```php
public function showKaryawan($id)
{
    $document = Document::with(['category', 'uploader'])->findOrFail($id);
    
    // Check permission
    if (!$document->canView()) {
        return response()->json([
            'success' => false,
            'message' => 'Anda tidak memiliki akses'
        ], 403);
    }
    
    // Log access & increment view
    DocumentAccessLog::logAccess($document->id, 'view');
    $document->incrementView();
    
    return response()->json([
        'success' => true,
        'document' => [
            'kode_dokumen' => $document->kode_dokumen,
            'nama_dokumen' => $document->nama_dokumen,
            'jenis_dokumen' => $document->jenis_dokumen, // 'file' atau 'link'
            'link_url' => $document->jenis_dokumen === 'link' ? $document->file_path : null,
            // ... field lainnya
        ]
    ]);
}
```

#### **Method: downloadKaryawan() - Download/Redirect**
```php
public function downloadKaryawan($id)
{
    $document = Document::findOrFail($id);
    
    // Check permission
    if (!$document->canDownload()) {
        return redirect()->back()->with('error', 'Tidak ada akses');
    }
    
    // Log & increment download
    DocumentAccessLog::logAccess($document->id, 'download');
    $document->incrementDownload();
    
    if ($document->jenis_dokumen === 'file') {
        // Download file fisik
        $filePath = storage_path('app/public/' . $document->file_path);
        return response()->download($filePath);
    } else {
        // Redirect ke link eksternal
        return redirect($document->file_path);
    }
}
```

---

## 🎨 TAMPILAN KARYAWAN (VIEW)

### File: `resources/views/dokumen/index-karyawan.blade.php`

#### **Card Layout dengan Badge Visual**

```blade
@forelse($documents as $doc)
<div class="col-12 mb-3">
    <div class="dokumen-card">
        <!-- Badge BARU (jika < 7 hari) -->
        @if($doc->created_at && $doc->created_at->diffInDays(now()) <= 7)
            <span class="badge-new">
                <ion-icon name="sparkles-outline"></ion-icon> BARU
            </span>
        @endif
        
        <div class="dokumen-header">
            <div class="dokumen-kode">
                <i class="{{ $doc->file_icon }}"></i>
                {{ $doc->kode_dokumen }}
            </div>
            <div style="display: flex; flex-direction: column; gap: 5px;">
                <!-- Badge Kategori -->
                <span class="badge-kategori" style="background-color: {{ $doc->category->warna }};">
                    {{ $doc->category->kode_kategori }}
                </span>
                
                <!-- Badge Jenis: FILE atau LINK -->
                @if($doc->jenis_dokumen === 'link')
                    <span class="jenis-badge badge-link">
                        <ion-icon name="link-outline"></ion-icon> Link
                    </span>
                @else
                    <span class="jenis-badge badge-file">
                        <ion-icon name="document-attach-outline"></ion-icon> File
                    </span>
                @endif
            </div>
        </div>

        <div class="dokumen-nama">
            {{ Str::limit($doc->nama_dokumen, 50) }}
        </div>

        <!-- ... metadata lainnya ... -->

        <div class="dokumen-footer">
            <div class="d-flex gap-1">
                <span class="badge bg-{{ strtolower($doc->status) }}">
                    {{ ucfirst($doc->status) }}
                </span>
                <span class="stats-badge">
                    <i class="ti ti-eye"></i> {{ $doc->jumlah_view }}
                </span>
                <span class="stats-badge">
                    <i class="ti ti-download"></i> {{ $doc->jumlah_download }}
                </span>
            </div>
            
            <!-- Tombol Aksi: Berbeda untuk FILE vs LINK -->
            <div class="d-flex gap-2">
                @if($doc->jenis_dokumen === 'link')
                    <!-- LINK: Buka langsung di tab baru -->
                    <a href="{{ route('dokumen.karyawan.download', $doc->id) }}" 
                       target="_blank" 
                       class="btn-action btn-preview">
                        <ion-icon name="open-outline"></ion-icon>
                        Buka Link
                    </a>
                @else
                    <!-- FILE: Modal detail + download -->
                    <button onclick="showDokumenModal({{ $doc->id }})" 
                            class="btn-action btn-preview">
                        <ion-icon name="eye-outline"></ion-icon>
                        Lihat
                    </button>
                    @if($doc->canDownload())
                        <a href="{{ route('dokumen.karyawan.download', $doc->id) }}" 
                           class="btn-action btn-download">
                            <ion-icon name="download-outline"></ion-icon>
                        </a>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
@empty
    <div class="empty-state">
        <i class="ti ti-folder-off"></i>
        <p>Belum ada dokumen</p>
    </div>
@endforelse
```

#### **Modal Detail dengan Info Jenis Dokumen**

```javascript
function showDokumenModal(id) {
    fetch(`/fasilitas/dokumen-karyawan/${id}/show`)
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                const doc = data.document;
                let html = `
                    <div class="mb-3">
                        <strong>Jenis Dokumen:</strong><br>
                        ${doc.jenis_dokumen === 'link' 
                            ? '<span class="jenis-badge badge-link"><ion-icon name="link-outline"></ion-icon> Link Eksternal</span>'
                            : '<span class="jenis-badge badge-file"><ion-icon name="document-attach-outline"></ion-icon> File Dokumen</span>'
                        }
                    </div>
                `;
                
                // Jika link, tampilkan URL
                if(doc.jenis_dokumen === 'link' && doc.link_url) {
                    html += `
                        <div class="mb-3">
                            <strong>Link URL:</strong><br>
                            <a href="${doc.link_url}" target="_blank">
                                <ion-icon name="open-outline"></ion-icon> ${doc.link_url}
                            </a>
                        </div>
                    `;
                }
                
                // ... tampilkan field lainnya ...
                
                document.getElementById('dokumenModalBody').innerHTML = html;
            }
        });
}
```

---

## 🔐 ACCESS LEVEL MATRIX

| Access Level | Admin View | Karyawan View | Karyawan Download |
|--------------|-----------|---------------|-------------------|
| **Public** | ✅ Yes | ✅ Yes | ✅ Yes |
| **View Only** | ✅ Yes | ✅ Yes | ✅ Yes (Link hanya redirect) |
| **Restricted** | ✅ Yes | ❌ No | ❌ No |

---

## 📊 FITUR TRACKING & ANALYTICS

### Logging Otomatis
Setiap aksi karyawan dicatat di tabel `document_access_logs`:

```php
// Table: document_access_logs
- document_id
- user_id
- action ('view', 'download')
- ip_address
- user_agent
- created_at
```

### Counter Otomatis
- **jumlah_view**: +1 setiap kali modal detail dibuka
- **jumlah_download**: +1 setiap kali file didownload atau link dibuka

---

## 🚀 TESTING SCENARIO

### Test Case 1: Admin Upload File → Karyawan View
```
1. Admin login → /dokumen
2. Tambah dokumen → Upload file.pdf
3. Set access_level: "public"
4. Simpan

Expected Result:
✅ Karyawan lihat dokumen di /fasilitas/dokumen-karyawan
✅ Badge "BARU" muncul (animated pulse)
✅ Badge biru "File" tampil
✅ Klik "Lihat" → Modal terbuka dengan detail
✅ Klik "Download" → File terdownload
✅ Counter view dan download bertambah
```

### Test Case 2: Admin Upload Link → Karyawan Access
```
1. Admin login → /dokumen
2. Tambah dokumen → Input link: https://docs.google.com/...
3. Set access_level: "view_only"
4. Simpan

Expected Result:
✅ Karyawan lihat dokumen di /fasilitas/dokumen-karyawan
✅ Badge pink "Link" tampil
✅ Klik "Buka Link" → Tab baru terbuka ke URL
✅ Counter download bertambah (meski redirect, tetap log)
✅ Tidak ada tombol "Download" fisik
```

### Test Case 3: Admin Set Restricted → Karyawan Hidden
```
1. Admin edit dokumen public
2. Ubah access_level: "restricted"
3. Simpan

Expected Result:
❌ Dokumen HILANG dari view karyawan
❌ Jika karyawan coba akses manual via URL → 403 Forbidden
✅ Admin masih bisa lihat di /dokumen
```

---

## 🎨 VISUAL INDICATORS

### Badge Colors & Meanings

| Badge | Color | Icon | Meaning |
|-------|-------|------|---------|
| **BARU** | Red gradient | sparkles | Dokumen < 7 hari |
| **File** | Blue | document-attach | Dokumen file fisik |
| **Link** | Pink | link | Link eksternal |
| **Aktif** | Green | - | Status aktif |
| **Arsip** | Gray | - | Status arsip |
| **Kadaluarsa** | Red | - | Status expired |

### Animation Effects
- **Badge BARU**: Pulse animation (scale 1 → 1.05)
- **Card Hover**: translateY(-5px) + shadow enhancement
- **Corner Fold**: Pseudo-element ::before dengan border trick

---

## 🔧 TROUBLESHOOTING

### Problem: Karyawan lihat dokumen restricted
**Solution:** Check controller filter:
```php
// Di indexKaryawan()
$query->whereIn('access_level', ['public', 'view_only']);
```

### Problem: Link tidak buka di tab baru
**Solution:** Tambahkan attribute `target="_blank"`:
```blade
<a href="..." target="_blank">Buka Link</a>
```

### Problem: Badge "BARU" tidak muncul
**Solution:** Check kondisi created_at:
```blade
@if($doc->created_at && $doc->created_at->diffInDays(now()) <= 7)
```

### Problem: Modal tidak menampilkan jenis_dokumen
**Solution:** Pastikan controller mengirim field:
```php
'jenis_dokumen' => $document->jenis_dokumen,
'link_url' => $document->jenis_dokumen === 'link' ? $document->file_path : null,
```

---

## 📝 SUMMARY

### ✅ Implemented Features
1. ✅ **Auto-filter access_level** di karyawan
2. ✅ **Visual badge** untuk file vs link
3. ✅ **Badge "BARU"** untuk dokumen < 7 hari
4. ✅ **Smart action buttons** (Lihat/Download untuk file, Buka Link untuk link)
5. ✅ **Modal detail** dengan info jenis_dokumen dan link_url
6. ✅ **Logging otomatis** setiap aksi karyawan
7. ✅ **Counter real-time** untuk view dan download
8. ✅ **Mobile-friendly layout** dengan card grid

### 🎯 Integration Flow
```
ADMIN UPLOAD ➜ DATABASE ➜ KARYAWAN VIEW (Auto-filtered) ➜ ACTION ➜ LOG
```

### 🔐 Security
- ✅ Middleware authentication di semua route
- ✅ Controller middleware untuk CRUD operations
- ✅ Model method canView() dan canDownload()
- ✅ 403 response jika akses ditolak

---

**Dokumentasi dibuat oleh:** GitHub Copilot  
**Last Update:** 16 November 2025, 11:02 PM  
**Status:** ✅ Production Ready
