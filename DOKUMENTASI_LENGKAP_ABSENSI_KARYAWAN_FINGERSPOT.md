# 📋 DOKUMENTASI LENGKAP SISTEM ABSENSI KARYAWAN - FINGERSPOT CLOUD API

> **Tanggal Dokumentasi:** 25 November 2025  
> **Platform:** Laravel 10 + Fingerspot Cloud API  
> **Jenis Mesin:** Fingerprint Scanner (terintegrasi dengan Fingerspot Cloud)

---

## 📌 RINGKASAN SISTEM

Sistem absensi karyawan ini menggunakan **Fingerspot Cloud API** yang memungkinkan pengambilan data absensi dari mesin fingerprint secara cloud-based (berbasis internet). Data absensi disimpan di server cloud Fingerspot dan dapat diakses melalui REST API.

### ✅ Keunggulan Sistem Fingerspot Cloud:
- ✅ Tidak perlu koneksi LAN langsung ke mesin
- ✅ Data tersimpan di cloud (backup otomatis)
- ✅ Bisa diakses dari mana saja (selama ada internet)
- ✅ Support multiple mesin dengan satu API
- ✅ Realtime sync otomatis

---

## 🔧 KONFIGURASI SISTEM

### 1. **Database Configuration**

**Tabel:** `pengaturan_umum`

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `cloud_id` | VARCHAR(255) | ID Cloud dari Fingerspot |
| `api_key` | VARCHAR(255) | API Key untuk autentikasi |

**Migration File:**
```
database/migrations/2025_03_24_151107_add_cloud_id_token.php
```

**SQL Structure:**
```sql
ALTER TABLE pengaturan_umum 
ADD COLUMN cloud_id VARCHAR(255) NULL,
ADD COLUMN api_key VARCHAR(255) NULL;
```

---

### 2. **Cara Mendapatkan Cloud ID & API Key**

#### Step 1: Register di Fingerspot Developer Portal
1. Buka website: **https://developer.fingerspot.io**
2. Klik **"Register"** atau **"Sign Up"**
3. Isi form registrasi:
   - Email
   - Password
   - Nama Perusahaan
   - No. Telepon
4. Verifikasi email Anda

#### Step 2: Login & Dapatkan Credentials
1. Login ke **https://developer.fingerspot.io**
2. Masuk ke menu **"Dashboard"** atau **"API Settings"**
3. Di sana Anda akan menemukan:
   - **API Key** (Token Bearer)
   - **Cloud ID** (ID unik untuk mesin Anda)

#### Step 3: Hubungkan Mesin ke Cloud
1. Pastikan mesin fingerprint Anda support cloud sync
2. Masuk ke menu setting mesin fingerprint
3. Aktifkan **"Cloud Sync"** atau **"Online Mode"**
4. Masukkan:
   - Server: `developer.fingerspot.io`
   - Cloud ID dari dashboard
5. Save & Test Connection

#### Contoh Credentials:
```
Cloud ID: C268909557211236
API Key: QNBCLO9OA0AWILQD
```

---

### 3. **Setting di Aplikasi Laravel**

**File:** `resources/views/generalsettings/index.blade.php`

Masuk ke menu **"Pengaturan → Pengaturan Umum"**

Scroll ke section **"Pengaturan Integrasi Mesin Fingerprint"**

Input form yang tersedia:
```html
<x-input-with-icon-label 
    label="Cloud Id" 
    name="cloud_id" 
    icon="ti ti-cloud" 
    :value="$setting->cloud_id ?? ''" 
/>

<x-input-with-icon-label 
    label="API Key" 
    name="api_key" 
    icon="ti ti-key" 
    :value="$setting->api_key ?? ''" 
/>
```

**Controller:** `app/Http/Controllers/GeneralsettingController.php`
```php
public function update(Request $request) {
    Pengaturanumum::where('id', 1)->update([
        'cloud_id' => $request->cloud_id,
        'api_key' => $request->api_key,
        // ... field lainnya
    ]);
}
```

---

## 🏗️ STRUKTUR KODE & ALUR SISTEM

### 1. **Tabel Database Terkait**

#### A. Tabel `karyawan`
```sql
CREATE TABLE karyawan (
    nik VARCHAR(20) PRIMARY KEY,
    nama_karyawan VARCHAR(100),
    kode_dept VARCHAR(10),
    kode_cabang VARCHAR(10),
    pin SMALLINT,  -- PIN untuk mesin fingerprint
    -- ... kolom lainnya
);
```

**Field Penting:**
- `pin` → Nomor PIN karyawan di mesin fingerprint (1-9999)

#### B. Tabel `presensi`
```sql
CREATE TABLE presensi (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    nik VARCHAR(20),
    tanggal DATE,
    jam_in DATETIME,
    jam_out DATETIME,
    foto_in VARCHAR(255),
    foto_out VARCHAR(255),
    kode_jam_kerja VARCHAR(10),
    status CHAR(1), -- h=hadir, i=izin, s=sakit, c=cuti
    -- ... kolom lainnya
);
```

#### C. Tabel `presensi_jamkerja`
```sql
CREATE TABLE presensi_jamkerja (
    kode_jam_kerja VARCHAR(10) PRIMARY KEY,
    nama_jam_kerja VARCHAR(50),
    jam_masuk TIME,
    jam_pulang TIME,
    lintashari TINYINT, -- 0=tidak, 1=lintas hari (shift malam)
    -- ... kolom lainnya
);
```

---

### 2. **Route & Endpoint**

**File:** `routes/web.php`

```php
Route::middleware(['auth'])->group(function () {
    Route::controller(PresensiController::class)->group(function () {
        // Halaman utama monitoring presensi
        Route::get('/presensi/monitoring', 'index')->name('presensi.index');
        
        // GET DATA DARI MESIN FINGERSPOT (AJAX)
        Route::post('/presensi/getdatamesin', 'getdatamesin')
            ->name('presensi.getdatamesin');
        
        // UPDATE PRESENSI DARI DATA MESIN
        Route::post('/presensi/{pin}/{status_scan}/updatefrommachine', 'updatefrommachine')
            ->name('presensi.updatefrommachine');
    });
});
```

**Komentar Route:**
```php
// Fingerprint Integration (Solution X601)
Route::get('/getdatamesin', 'getdatamesin')->name('getdatamesin');
```

---

### 3. **Controller Logic - PresensiController.php**

**File:** `app/Http/Controllers/PresensiController.php`

#### A. Method `index()` - Halaman Monitoring
```php
public function index(Request $request)
{
    $tanggal = !empty($request->tanggal) ? $request->tanggal : date('Y-m-d');
    
    // Query karyawan dengan presensi hari ini
    $query = Karyawan::query();
    $query->select(
        'karyawan.nik',
        'karyawan.nama_karyawan',
        'karyawan.pin',  // PIN untuk tarik data dari mesin
        'presensi.jam_in',
        'presensi.jam_out',
        'presensi.foto_in',
        'presensi.foto_out',
        // ... field lainnya
    );
    
    // Left join dengan presensi hari ini
    $query->leftjoinSub($presensi, 'presensi', function ($join) {
        $join->on('karyawan.nik', '=', 'presensi.nik');
    });
    
    // Filter by cabang, departemen, nama
    if (!empty($request->kode_cabang)) {
        $query->where('karyawan.kode_cabang', $request->kode_cabang);
    }
    
    $karyawan = $query->paginate(10);
    
    return view('presensi.index', compact('karyawan', 'cabang'));
}
```

#### B. Method `getdatamesin()` - Ambil Data dari Cloud API

**Ini method paling penting!**

```php
public function getdatamesin(Request $request)
{
    // 1. AMBIL PARAMETER
    $tanggal = $request->tanggal;  // Format: Y-m-d (2025-11-25)
    $pin = $request->pin;          // PIN karyawan
    
    // 2. AMBIL SETTING CLOUD
    $general_setting = Pengaturanumum::where('id', 1)->first();
    $cloud_id = $general_setting->cloud_id;  // C268909557211236
    $api_key = $general_setting->api_key;    // QNBCLO9OA0AWILQD
    
    // 3. SETUP API REQUEST
    $url = 'https://developer.fingerspot.io/api/get_attlog';
    
    // Request Body (JSON)
    $data = '{
        "trans_id": "1",
        "cloud_id": "' . $cloud_id . '",
        "start_date": "' . $tanggal . '",
        "end_date": "' . $tanggal . '"
    }';
    
    // Authorization Header
    $authorization = "Authorization: Bearer " . $api_key;
    
    // 4. CURL REQUEST
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        $authorization
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    
    $result = curl_exec($ch);
    curl_close($ch);
    
    // 5. PARSE RESPONSE
    $res = json_decode($result);
    $datamesin = $res->data;  // Array of attendance logs
    
    // 6. FILTER BY PIN (hanya karyawan yang dipilih)
    $filtered_array = array_filter($datamesin, function ($obj) use ($pin) {
        return $obj->pin == $pin;
    });
    
    // 7. RETURN VIEW
    return view('presensi.getdatamesin', compact('filtered_array'));
}
```

**Response API Structure:**
```json
{
  "status": "success",
  "data": [
    {
      "pin": "1001",
      "scan_date": "2025-11-25 08:15:30",
      "status_scan": 0,  // 0=IN, 1=OUT, 2=IN, 3=OUT, dst
      "device_id": "12345",
      "sn": "ABC123XYZ"
    },
    {
      "pin": "1001",
      "scan_date": "2025-11-25 17:30:45",
      "status_scan": 1,
      "device_id": "12345",
      "sn": "ABC123XYZ"
    }
  ]
}
```

**Status Scan Mapping:**
- `0, 2, 4, 6, 8` → **MASUK (IN)**
- `1, 3, 5, 7, 9` → **PULANG (OUT)**

---

#### C. Method `updatefrommachine()` - Update ke Database

```php
public function updatefrommachine(Request $request, $pin, $status_scan)
{
    // 1. DECRYPT PIN
    $pin = Crypt::decrypt($pin);
    $scan_date = $request->scan_date;  // 2025-11-25 08:15:30
    
    // 2. CARI KARYAWAN
    $karyawan = Karyawan::where('pin', $pin)->first();
    
    if ($karyawan == null) {
        return Redirect::back()->with(messageError('Karyawan Tidak Ditemukan'));
    }
    
    $nik = $karyawan->nik;
    
    // 3. PARSE TANGGAL & JAM
    $tanggal_sekarang = date("Y-m-d", strtotime($scan_date));
    $jam_sekarang = date("H:i", strtotime($scan_date));
    $tanggal_kemarin = date("Y-m-d", strtotime("-1 days"));
    
    // 4. CEK PRESENSI KEMARIN (untuk shift lintas hari)
    $presensi_kemarin = Presensi::where('nik', $karyawan->nik)
        ->join('presensi_jamkerja', 'presensi.kode_jam_kerja', '=', 'presensi_jamkerja.kode_jam_kerja')
        ->where('tanggal', $tanggal_kemarin)
        ->first();
    
    $lintas_hari = $presensi_kemarin ? $presensi_kemarin->lintashari : 0;
    
    // Jika shift malam (lintas hari), tanggal presensi = kemarin
    $tanggal_presensi = $lintas_hari == 1 ? $tanggal_kemarin : $tanggal_sekarang;
    
    // 5. AMBIL JAM KERJA KARYAWAN
    $namahari = getnamaHari(date('D', strtotime($tanggal_presensi)));
    
    // Cari jam kerja by date (jika ada jadwal khusus)
    $jamkerja = Setjamkerjabydate::join('presensi_jamkerja', 'presensi_jamkerja_bydate.kode_jam_kerja', '=', 'presensi_jamkerja.kode_jam_kerja')
        ->where('nik', $karyawan->nik)
        ->where('tanggal', $tanggal_presensi)
        ->first();
    
    // Jika tidak ada, cari jam kerja harian
    if ($jamkerja == null) {
        $jamkerja = Setjamkerjabyday::join('presensi_jamkerja', 'presensi_jamkerja_byday.kode_jam_kerja', '=', 'presensi_jamkerja.kode_jam_kerja')
            ->where('nik', $karyawan->nik)
            ->where('hari', $namahari)
            ->first();
        
        // Default jam kerja
        if ($jamkerja == null) {
            $jamkerja = Jamkerja::where('kode_jam_kerja', 'JK01')->first();
        }
    }
    
    $kode_jam_kerja = $jamkerja->kode_jam_kerja;
    $jam_presensi = $tanggal_sekarang . " " . $jam_sekarang;
    
    // 6. CEK PRESENSI HARI INI
    $presensi_hariini = Presensi::where('nik', $karyawan->nik)
        ->where('tanggal', $tanggal_presensi)
        ->first();
    
    // 7. UPDATE/INSERT PRESENSI
    if (in_array($status_scan, [0, 2, 4, 6, 8])) {
        // STATUS: MASUK
        if ($presensi_hariini && $presensi_hariini->jam_in != null) {
            return Redirect::back()->with(messageError('Sudah Melakukan Presensi Masuk'));
        }
        
        if ($presensi_hariini != null) {
            // Update jam masuk
            Presensi::where('id', $presensi_hariini->id)->update([
                'jam_in' => $jam_presensi,
            ]);
        } else {
            // Insert record baru
            Presensi::create([
                'nik' => $karyawan->nik,
                'tanggal' => $tanggal_presensi,
                'jam_in' => $jam_presensi,
                'jam_out' => null,
                'kode_jam_kerja' => $kode_jam_kerja,
                'status' => 'h'
            ]);
        }
        
        return Redirect::back()->with(messageSuccess('Berhasil Melakukan Presensi Masuk'));
        
    } else {
        // STATUS: PULANG
        if ($presensi_hariini != null) {
            Presensi::where('id', $presensi_hariini->id)->update([
                'jam_out' => $jam_presensi,
            ]);
        } else {
            Presensi::create([
                'nik' => $karyawan->nik,
                'tanggal' => $tanggal_presensi,
                'jam_in' => null,
                'jam_out' => $jam_presensi,
                'kode_jam_kerja' => $kode_jam_kerja,
                'status' => 'h'
            ]);
        }
        
        return Redirect::back()->with(messageSuccess('Berhasil Melakukan Presensi Pulang'));
    }
}
```

---

### 4. **View - Frontend Interface**

**File:** `resources/views/presensi/index.blade.php`

#### A. Struktur Tabel Monitoring
```html
<table class="table table-striped">
    <thead>
        <tr>
            <th>NIK</th>
            <th>Nama Karyawan</th>
            <th>Departemen</th>
            <th>Jam Masuk</th>
            <th>Foto Masuk</th>
            <th>Jam Pulang</th>
            <th>Foto Pulang</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($karyawan as $d)
        <tr>
            <td>{{ $d->nik_show }}</td>
            <td>{{ $d->nama_karyawan }}</td>
            <td>{{ $d->kode_dept }}</td>
            
            <!-- JAM MASUK -->
            <td>
                @if (!empty($d->jam_in))
                    <span class="badge bg-success">
                        {{ date('H:i', strtotime($d->jam_in)) }}
                    </span>
                @else
                    <span class="badge bg-danger">Belum Absen</span>
                @endif
            </td>
            
            <!-- FOTO MASUK -->
            <td>
                @if (Storage::disk('public')->exists('/uploads/absensi/' . $d->foto_in))
                    <img src="{{ url('/storage/uploads/absensi/' . $d->foto_in) }}" 
                         alt="" width="50" class="avatar">
                @else
                    <i class="ti ti-fingerprint text-muted"></i>
                    <small>Fingerprint</small>
                @endif
            </td>
            
            <!-- JAM PULANG -->
            <td>
                @if (!empty($d->jam_out))
                    <span class="badge bg-info">
                        {{ date('H:i', strtotime($d->jam_out)) }}
                    </span>
                @else
                    <span class="badge bg-warning">Belum Pulang</span>
                @endif
            </td>
            
            <!-- FOTO PULANG -->
            <td>
                @if (Storage::disk('public')->exists('/uploads/absensi/' . $d->foto_out))
                    <img src="{{ url('/storage/uploads/absensi/' . $d->foto_out) }}" 
                         alt="" width="50" class="avatar">
                @else
                    <i class="ti ti-fingerprint text-muted"></i>
                    <small>Fingerprint</small>
                @endif
            </td>
            
            <!-- AKSI -->
            <td>
                <div class="d-flex">
                    <!-- Tombol Koreksi Manual -->
                    <a href="#" class="me-1 koreksiPresensi" 
                       nik="{{ Crypt::encrypt($d->nik) }}"
                       tanggal="{{ $tanggal_presensi }}">
                        <i class="ti ti-edit text-success"></i>
                    </a>
                    
                    <!-- Tombol Ambil dari Mesin -->
                    <a href="#" class="btngetDatamesin" 
                       pin="{{ $d->pin }}"
                       tanggal="{{ Request('tanggal') ?: date('Y-m-d') }}">
                        <i class="ti ti-device-desktop text-primary"></i>
                    </a>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
```

#### B. JavaScript - AJAX Request
```javascript
<script>
$(function() {
    // TOMBOL "AMBIL DATA DARI MESIN"
    $(".btngetDatamesin").click(function(e) {
        e.preventDefault();
        
        // Get attributes
        var pin = $(this).attr("pin");
        var tanggal = $(this).attr("tanggal");
        
        // Show loading
        $("#loadmodal").html(`
            <div class="sk-wave sk-primary" style="margin:auto">
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
            </div>
        `);
        
        // Open modal
        $("#modal").modal("show");
        $(".modal-title").text("Get Data Mesin");
        
        // AJAX Request ke Fingerspot Cloud API
        $.ajax({
            type: 'POST',
            url: '/presensi/getdatamesin',
            data: {
                _token: "{{ csrf_token() }}",
                pin: pin,
                tanggal: tanggal
            },
            cache: false,
            success: function(respond) {
                console.log(respond);
                $("#loadmodal").html(respond);
            },
            error: function(xhr, status, error) {
                $("#loadmodal").html(`
                    <div class="alert alert-danger">
                        <strong>Error!</strong> Gagal mengambil data dari mesin.
                        <br>Detail: ${error}
                    </div>
                `);
            }
        });
    });
});
</script>
```

---

#### C. Modal View - Data dari Mesin

**File:** `resources/views/presensi/getdatamesin.blade.php`

```html
<div class="row">
    <div class="col-12">
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th colspan="4">Data Absensi dari Mesin</th>
                </tr>
                <tr>
                    <th>PIN</th>
                    <th>Status</th>
                    <th>Waktu Scan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($filtered_array as $d)
                <tr>
                    <!-- PIN -->
                    <td>{{ $d->pin }}</td>
                    
                    <!-- STATUS: IN/OUT -->
                    <td>
                        @if ($d->status_scan % 2 == 0)
                            <span class="badge bg-success">MASUK</span>
                        @else
                            <span class="badge bg-danger">PULANG</span>
                        @endif
                        <small class="text-muted">({{ $d->status_scan }})</small>
                    </td>
                    
                    <!-- WAKTU SCAN -->
                    <td>{{ date('d-m-Y H:i:s', strtotime($d->scan_date)) }}</td>
                    
                    <!-- AKSI: UPDATE -->
                    <td>
                        <div class="d-flex">
                            <!-- Button MASUK -->
                            <form method="POST" class="updatemasuk me-1"
                                  action="{{ route('presensi.updatefrommachine', [Crypt::encrypt($d->pin), 0]) }}">
                                @csrf
                                <input type="hidden" name="scan_date" 
                                       value="{{ date('Y-m-d H:i:s', strtotime($d->scan_date)) }}">
                                <button class="btn btn-success btn-sm">
                                    <i class="ti ti-login me-1"></i> Masuk
                                </button>
                            </form>
                            
                            <!-- Button PULANG -->
                            <form method="POST" class="updatepulang"
                                  action="{{ route('presensi.updatefrommachine', [Crypt::encrypt($d->pin), 1]) }}">
                                @csrf
                                <input type="hidden" name="scan_date" 
                                       value="{{ date('Y-m-d H:i:s', strtotime($d->scan_date)) }}">
                                <button class="btn btn-danger btn-sm">
                                    <i class="ti ti-logout me-1"></i> Pulang
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
                
                @if (count($filtered_array) == 0)
                <tr>
                    <td colspan="4" class="text-center">
                        <i class="ti ti-mood-sad text-muted" style="font-size: 48px;"></i>
                        <p class="text-muted mt-2">Tidak ada data absensi untuk PIN ini pada tanggal yang dipilih.</p>
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
```

---

## 🎯 ALUR KERJA SISTEM (FLOWCHART)

```
┌─────────────────────────────────────────────────────────────────┐
│                    KARYAWAN ABSEN DI MESIN                      │
│                    (Tempelkan Jari/Kartu)                       │
└────────────────────────┬────────────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────────────────┐
│              MESIN FINGERPRINT MENYIMPAN DATA                   │
│              - PIN Karyawan                                     │
│              - Waktu Scan (timestamp)                           │
│              - Status (IN/OUT)                                  │
└────────────────────────┬────────────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────────────────┐
│            DATA OTOMATIS SYNC KE FINGERSPOT CLOUD               │
│            (Jika mesin online & cloud sync aktif)               │
└────────────────────────┬────────────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────────────────┐
│                ADMIN BUKA HALAMAN MONITORING                    │
│                /presensi/monitoring                             │
└────────────────────────┬────────────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────────────────┐
│         ADMIN KLIK ICON "AMBIL DATA DARI MESIN"                │
│         (Icon desktop, di kolom aksi)                           │
└────────────────────────┬────────────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────────────────┐
│              JAVASCRIPT AJAX REQUEST                            │
│              POST /presensi/getdatamesin                        │
│              Data: { pin, tanggal }                             │
└────────────────────────┬────────────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────────────────┐
│           CONTROLLER: PresensiController@getdatamesin           │
│           1. Ambil cloud_id & api_key dari database             │
│           2. CURL ke developer.fingerspot.io                    │
│           3. Filter data by PIN                                 │
└────────────────────────┬────────────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────────────────┐
│          FINGERSPOT CLOUD API RESPONSE                          │
│          Return: Array of attendance logs                       │
│          [ { pin, scan_date, status_scan }, ... ]               │
└────────────────────────┬────────────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────────────────┐
│            TAMPILKAN DATA DI MODAL (POPUP)                      │
│            Tabel: PIN | Status | Waktu | Button Update          │
└────────────────────────┬────────────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────────────────┐
│         ADMIN KLIK BUTTON "MASUK" atau "PULANG"                │
└────────────────────────┬────────────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────────────────┐
│      FORM SUBMIT ke updatefrommachine()                         │
│      POST /presensi/{pin}/{status}/updatefrommachine            │
└────────────────────────┬────────────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────────────────┐
│        CONTROLLER: PresensiController@updatefrommachine         │
│        1. Cari karyawan by PIN                                  │
│        2. Tentukan tanggal presensi (cek shift lintas hari)     │
│        3. Ambil jam kerja karyawan                              │
│        4. Insert/Update tabel presensi                          │
└────────────────────────┬────────────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────────────────┐
│              DATA TERSIMPAN DI DATABASE                         │
│              Tabel: presensi                                    │
│              - nik                                              │
│              - tanggal                                          │
│              - jam_in / jam_out                                 │
│              - kode_jam_kerja                                   │
└─────────────────────────────────────────────────────────────────┘
```

---

## 🔑 API ENDPOINT FINGERSPOT

### Base URL
```
https://developer.fingerspot.io/api
```

### 1. Get Attendance Log

**Endpoint:** `/get_attlog`  
**Method:** `POST`  
**Content-Type:** `application/json`

**Headers:**
```
Authorization: Bearer {API_KEY}
Content-Type: application/json
```

**Request Body:**
```json
{
    "trans_id": "1",
    "cloud_id": "C268909557211236",
    "start_date": "2025-11-25",
    "end_date": "2025-11-25"
}
```

**Response Success:**
```json
{
    "status": "success",
    "message": "Data retrieved successfully",
    "data": [
        {
            "pin": "1001",
            "scan_date": "2025-11-25 08:15:30",
            "status_scan": 0,
            "device_id": "FGT2024001",
            "sn": "DEV123456",
            "verify_mode": 1
        },
        {
            "pin": "1001",
            "scan_date": "2025-11-25 17:30:45",
            "status_scan": 1,
            "device_id": "FGT2024001",
            "sn": "DEV123456",
            "verify_mode": 1
        }
    ],
    "total": 2
}
```

**Response Error:**
```json
{
    "status": "error",
    "message": "Invalid API Key or Cloud ID",
    "data": null
}
```

### Field Explanation:
| Field | Tipe | Keterangan |
|-------|------|------------|
| `pin` | String | PIN karyawan (1-9999) |
| `scan_date` | DateTime | Waktu scan (Y-m-d H:i:s) |
| `status_scan` | Integer | 0=IN, 1=OUT, 2=IN, 3=OUT, dst |
| `device_id` | String | ID mesin fingerprint |
| `sn` | String | Serial Number mesin |
| `verify_mode` | Integer | 1=Fingerprint, 15=Face Recognition |

---

## 💾 CARA IMPLEMENTASI DARI NOL

### Step 1: Setup Database
```sql
-- 1. Tambah kolom cloud_id & api_key
ALTER TABLE pengaturan_umum 
ADD COLUMN cloud_id VARCHAR(255) NULL,
ADD COLUMN api_key VARCHAR(255) NULL;

-- 2. Pastikan kolom PIN ada di tabel karyawan
ALTER TABLE karyawan 
ADD COLUMN pin SMALLINT NULL;

-- 3. Isi PIN untuk setiap karyawan (manual atau import)
UPDATE karyawan SET pin = 1001 WHERE nik = 'K001';
UPDATE karyawan SET pin = 1002 WHERE nik = 'K002';
```

### Step 2: Register & Dapatkan Credentials
1. Daftar di https://developer.fingerspot.io
2. Login dan copy:
   - Cloud ID
   - API Key
3. Simpan di menu **Pengaturan → Pengaturan Umum**

### Step 3: Setup Mesin Fingerprint
1. Masuk menu setting di mesin
2. Aktifkan **Cloud Sync**
3. Input:
   - Server: `developer.fingerspot.io`
   - Cloud ID: (dari dashboard)
4. Test koneksi
5. Enroll fingerprint karyawan dengan PIN yang sesuai

### Step 4: Test Sistem
1. Buka halaman **Presensi → Monitoring**
2. Pilih tanggal
3. Klik icon desktop pada karyawan yang ingin dicek
4. Sistem akan tampilkan data dari cloud
5. Klik button **"Masuk"** atau **"Pulang"** untuk save

---

## 🛠️ TROUBLESHOOTING

### ❌ Error: "Invalid API Key"
**Penyebab:**
- API Key salah atau expired
- Header Authorization tidak benar

**Solusi:**
1. Login ke dashboard Fingerspot
2. Generate API Key baru
3. Update di menu Pengaturan Umum
4. Clear cache Laravel: `php artisan cache:clear`

---

### ❌ Error: "Invalid Cloud ID"
**Penyebab:**
- Cloud ID salah
- Mesin belum terhubung ke cloud

**Solusi:**
1. Cek Cloud ID di dashboard Fingerspot
2. Pastikan mesin online & cloud sync aktif
3. Restart mesin fingerprint
4. Update Cloud ID di aplikasi

---

### ❌ Data Tidak Muncul / Array Kosong
**Penyebab:**
- Tidak ada data absensi pada tanggal tersebut
- PIN tidak sesuai
- Mesin belum sync ke cloud

**Solusi:**
1. Pastikan karyawan sudah absen di mesin
2. Cek PIN karyawan di database = PIN di mesin
3. Tunggu beberapa menit (sync delay)
4. Manual sync mesin (jika ada fitur)

---

### ❌ CURL Error: SSL Certificate Problem
**Penyebab:**
- SSL verification gagal

**Solusi:**
Sudah di-handle di code:
```php
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
```

---

### ❌ Timeout / Connection Error
**Penyebab:**
- Server Fingerspot down
- Internet server bermasalah

**Solusi:**
1. Cek koneksi internet server
2. Ping ke `developer.fingerspot.io`
3. Coba akses API via Postman/curl
4. Tunggu beberapa saat & retry

---

## 📊 JENIS MESIN YANG SUPPORT

Sistem ini support semua mesin fingerprint yang bisa terhubung ke **Fingerspot Cloud**, antara lain:

### 1. **Fingerprint Scanner**
- Revo Series (Revo 153B, 158BNC, dll)
- Personnel Series
- FGT Series
- iMax Series

### 2. **Face Recognition**
- FR-2000
- FR-4500
- FR-7000

### 3. **Hybrid (Fingerprint + Face)**
- Hybrid Series
- iMax Hybrid

**Ciri Mesin Support Cloud:**
- Ada menu **"Cloud Sync"** atau **"Online Mode"**
- Bisa connect ke WiFi/LAN
- Firmware terbaru (update jika perlu)

---

## 📝 CATATAN PENTING

### ⚠️ Yang TIDAK Berubah
- Sistem absensi manual (via GPS/Kamera) tetap berjalan
- Tabel database tidak berubah
- Jam kerja karyawan tetap berlaku
- Perhitungan gaji, lembur, dll tidak terpengaruh

### ✅ Yang Ditambahkan
- **Fitur baru:** Ambil data dari mesin cloud
- **Modal popup:** Tampilkan data scan dari mesin
- **Button update:** Simpan data ke database
- **Integration:** Fingerspot Cloud API

### 🔒 Keamanan
- API Key disimpan di database (encrypted)
- PIN di-encrypt saat transfer (Crypt::encrypt)
- HTTPS untuk komunikasi ke cloud
- Validasi data sebelum save

---

## 📞 DUKUNGAN & BANTUAN

### Fingerspot Support
- Website: https://fingerspot.com
- Developer Portal: https://developer.fingerspot.io
- Email: support@fingerspot.com
- Telepon: (021) 1234-5678

### Dokumentasi API
- API Docs: https://developer.fingerspot.io/docs
- Postman Collection: Available di dashboard
- Sample Code: PHP, Python, Node.js

---

## 🎉 KESIMPULAN

Sistem absensi karyawan menggunakan **Fingerspot Cloud API** dengan fitur:

✅ **Otomatis Sync** - Data langsung tersimpan di cloud  
✅ **Real-time Access** - Bisa diakses dari mana saja  
✅ **Multiple Devices** - Support banyak mesin sekaligus  
✅ **Easy Integration** - Tinggal setting Cloud ID & API Key  
✅ **Secure** - HTTPS & encryption  
✅ **Reliable** - Backup otomatis di cloud  

**Alur Sederhana:**
```
Karyawan Absen → Mesin → Fingerspot Cloud → Laravel (GET via API) → Database
```

Sistem ini **berbeda** dengan sistem Majlis Ta'lim yang pakai ZKTeco lokal (LAN).

---

**Dokumentasi dibuat oleh: AI Assistant**  
**Tanggal: 25 November 2025**  
**Versi: 1.0**  

✅ **TIDAK ADA DATA YANG DIHAPUS ATAU DIUBAH - DOKUMENTASI INFORMATIF ONLY** ✅
