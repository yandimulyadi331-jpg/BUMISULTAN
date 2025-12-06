# 🔧 PANDUAN PERBAIKAN STEP-BY-STEP

## Priority Level 1: CRITICAL SECURITY FIXES

---

## 1️⃣ DISABLE DEBUG MODE

### Langkah 1: Edit `.env` file

**File:** `.env`

```dotenv
# SEBELUM:
APP_DEBUG=true
LOG_LEVEL=debug
APP_ENV=local

# SESUDAH:
APP_DEBUG=false
LOG_LEVEL=warning
APP_ENV=production
```

### Langkah 2: Clear cache

```bash
php artisan config:cache
php artisan view:cache
php artisan route:cache
```

---

## 2️⃣ SECURE ENVIRONMENT CREDENTIALS

### Langkah 1: Remove Credentials dari `.env`

**DO NOT COMMIT .env FILE TO GIT**

Pastikan `.gitignore` memiliki:
```
.env
.env.local
.env.*.local
```

### Langkah 2: Setup Environment Variables di Server

**Untuk Production Server (Contoh: Ubuntu/Debian):**

```bash
# Buat file untuk environment variables
sudo nano /etc/environment

# Tambahkan:
APP_NAME="Bumi Sultan Super App"
APP_ENV="production"
APP_KEY="your-app-key-here"
APP_DEBUG="false"
DB_HOST="localhost"
DB_DATABASE="bumisultan_prod"
DB_USERNAME="bumisultan_user"
DB_PASSWORD="strong-random-password-here"
MAIL_PASSWORD="app-specific-password-here"
```

### Langkah 3: Ubah Database User

```bash
# Login ke MySQL
mysql -u root -p

# Create user baru dengan limited privileges
CREATE USER 'bumisultan_user'@'localhost' IDENTIFIED BY 'strong-random-password';

# Grant hanya permissions yang perlu
GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, ALTER, 
      INDEX, DROP ON bumisultan_prod.* 
      TO 'bumisultan_user'@'localhost';

FLUSH PRIVILEGES;

# Ubah password root jika belum ada
ALTER USER 'root'@'localhost' IDENTIFIED BY 'strong-root-password';
```

### Langkah 4: Update `.env`

```dotenv
DB_USERNAME=bumisultan_user
DB_PASSWORD=strong-random-password-here
```

---

## 3️⃣ FIX CORS CONFIGURATION

**File:** `config/cors.php`

### Langkah 1: Ganti dengan restricted origins

```php
<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie', 'notification/*'],

    'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],

    'allowed_origins' => [
        'https://yourdomain.com',
        'https://www.yourdomain.com',
        'https://app.yourdomain.com',
        'https://api.yourdomain.com',
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['Content-Type', 'Authorization', 'X-Requested-With'],

    'exposed_headers' => [],

    'max_age' => 3600,

    'supports_credentials' => true,

];
```

### Langkah 2: Add CORS middleware ke routes

**File:** `routes/api.php`

```php
Route::middleware('cors')->group(function () {
    Route::apiResource('/presensi', PresensiController::class);
    // Other API routes...
});
```

---

## 4️⃣ FIX PASSWORD HASHING IN USER CREATION

**File:** `app/Http/Controllers/UserController.php`

### Perubahan Line 63:

```php
// SEBELUM:
$user = User::create([
    'name' => $request->name,
    'username' => $request->username,
    'email' => $request->email,
    'password' => $request->password,  // ❌ PLAINTEXT
]);

// SESUDAH:
$user = User::create([
    'name' => $request->name,
    'username' => $request->username,
    'email' => $request->email,
    'password' => Hash::make($request->password),  // ✅ HASHED
]);
```

**Pastikan import di atas file:**
```php
use Illuminate\Support\Facades\Hash;
```

---

## 5️⃣ ADD AUTHENTICATION TO PUBLIC ROUTES

**File:** `routes/web.php`

### Langkah 1: Review routes yang public

```php
// SEBELUM:
Route::controller(FacerecognitionpresensiController::class)->group(function () {
    Route::get('/facerecognition-presensi', 'index');
    Route::post('/facerecognition-presensi/store', 'store');
});

// SESUDAH:
Route::middleware('auth')->controller(FacerecognitionpresensiController::class)->group(function () {
    Route::get('/facerecognition-presensi', 'index');
    Route::post('/facerecognition-presensi/store', 'store');
});
```

### Langkah 2: Protect API endpoints

**File:** `routes/api.php`

```php
// Semua routes harus protected dengan auth:sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('/presensi', PresensiController::class);
    Route::get('/notifications', [NotificationController::class, 'getTodayNotifications']);
    // ... semua API routes
});
```

---

## 6️⃣ ADD INPUT VALIDATION

### Example 1: File - `app/Http/Controllers/UserController.php`

```php
public function store(Request $request)
{
    // TAMBAHKAN VALIDATION
    $request->validate([
        'name' => 'required|string|max:255',
        'username' => 'required|string|unique:users|max:255',
        'email' => 'required|email|unique:users|max:255',
        'password' => 'required|string|min:8|confirmed',
        'role' => 'required|exists:roles,id'
    ], [
        'username.unique' => 'Username sudah digunakan',
        'email.unique' => 'Email sudah terdaftar',
        'password.min' => 'Password minimal 8 karakter',
    ]);

    // Jika validation fail, Laravel otomatis redirect dengan errors
}
```

### Example 2: File - `app/Http/Controllers/PinjamanController.php`

```php
public function store(Request $request)
{
    $request->validate([
        'kategori_peminjam' => 'required|in:crew,non_crew,tukang',
        'nomor_pinjaman' => 'required|string|unique:pinjaman|regex:/^[A-Z0-9-]+$/',
        'jumlah_pinjaman' => 'required|numeric|min:100000|max:500000000',
        'tenor' => 'required|integer|min:1|max:60',
        'persentase_pembayaran' => 'required|numeric|min:0|max:100',
        'tanggal_pengajuan' => 'required|date|before_or_equal:today',
        'tanggal_jatuh_tempo' => 'required|date|after:tanggal_pengajuan',
    ]);
}
```

---

## 7️⃣ SECURE FILE UPLOADS

**File:** `app/Http/Controllers/SignupControllerImproved.php`

### Langkah 1: Add proper file validation

```php
public function store(Request $request)
{
    $request->validate([
        // ... other validations ...
        'foto_profil' => 'required|image|mimes:jpeg,png,jpg|max:5120',  // 5MB max
        'foto_wajah_multiple' => 'required|json',
        // ... other validations ...
    ], [
        'foto_profil.image' => 'File harus gambar',
        'foto_profil.mimes' => 'Format gambar hanya JPEG atau PNG',
        'foto_profil.max' => 'Ukuran gambar maksimal 5MB',
    ]);
}
```

### Langkah 2: Validate MIME type saat save

```php
protected function validateAndSaveImage($base64Image, $destination) {
    try {
        // Decode base64
        $image = str_replace('data:image/jpeg;base64,', '', $base64Image);
        $image = str_replace('data:image/png;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        $imageData = base64_decode($image);
        
        // Validasi MIME type menggunakan finfo
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_buffer($finfo, $imageData);
        finfo_close($finfo);
        
        if (!in_array($mimeType, ['image/jpeg', 'image/png'])) {
            throw new \Exception('Invalid image MIME type: ' . $mimeType);
        }
        
        // Validasi gambar bisa di-open oleh image library
        if (!@imagecreatefromstring($imageData)) {
            throw new \Exception('Invalid image data');
        }
        
        // Save ke file
        if (!file_exists($destination)) {
            mkdir($destination, 0755, true);
        }
        
        $filename = uniqid() . '.jpg';
        file_put_contents($destination . '/' . $filename, $imageData);
        
        return $filename;
    } catch (\Exception $e) {
        throw new \Exception('File upload failed: ' . $e->getMessage());
    }
}
```

---

## Priority Level 2: HIGH PRIORITY FIXES

---

## 8️⃣ IMPLEMENT RATE LIMITING

**File:** `routes/web.php`

```php
// Login route dengan rate limiting
Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.loginuser');
    })->name('loginuser');
    
    Route::middleware('throttle:5,1')->group(function () {
        Route::post('/login', [LoginController::class, 'store'])->name('login');
    });
});

// File uploads dengan rate limiting
Route::middleware('throttle:10,60')->group(function () {
    Route::post('/karyawan/import', [KaryawanController::class, 'import_proses']);
    Route::post('/upload', [FileController::class, 'store']);
});
```

**File:** `app/Http/Middleware/ThrottleRequests.php` (custom config)

```php
'throttle' => [
    'login' => '5,1',           // 5 attempts per 1 minute
    'api' => '60,1',            // 60 requests per 1 minute
    'upload' => '10,60',        // 10 uploads per 60 minutes
    'password_reset' => '3,60', // 3 attempts per 60 minutes
],
```

---

## 9️⃣ ADD AUTHORIZATION POLICIES

### Langkah 1: Create Policy

```bash
php artisan make:policy AdministrasiPolicy --model=Administrasi
```

**File:** `app/Policies/AdministrasiPolicy.php`

```php
<?php

namespace App\Policies;

use App\Models\Administrasi;
use App\Models\User;

class AdministrasiPolicy
{
    public function view(User $user, Administrasi $administrasi): bool
    {
        // Hanya user yang membuat atau admin yang bisa view
        return $user->id === $administrasi->created_by || $user->hasRole('admin');
    }

    public function update(User $user, Administrasi $administrasi): bool
    {
        // Hanya creator atau admin yang bisa update
        return $user->id === $administrasi->updated_by || $user->hasRole('admin');
    }

    public function delete(User $user, Administrasi $administrasi): bool
    {
        // Hanya admin yang bisa delete
        return $user->hasRole('admin');
    }
}
```

### Langkah 2: Register Policy

**File:** `app/Providers/AuthServiceProvider.php`

```php
protected $policies = [
    Administrasi::class => AdministrasiPolicy::class,
    Pinjaman::class => PinjamanPolicy::class,
    // ... other policies
];
```

### Langkah 3: Use Policy di Controller

**File:** `app/Http/Controllers/AdministrasiController.php`

```php
public function show($id)
{
    $administrasi = Administrasi::findOrFail($id);
    $this->authorize('view', $administrasi);  // ✅ Check authorization
    
    return view('administrasi.show', compact('administrasi'));
}

public function update(Request $request, $id)
{
    $administrasi = Administrasi::findOrFail($id);
    $this->authorize('update', $administrasi);  // ✅ Check authorization
    
    // Lanjutkan update...
}
```

---

## 🔟 SETUP LOGGING

**File:** `config/logging.php`

```php
'channels' => [
    'stack' => [
        'driver' => 'stack',
        'channels' => ['single', 'stderr'],
        'ignore_exceptions' => false,
    ],

    'single' => [
        'driver' => 'single',
        'path' => storage_path('logs/laravel.log'),
        'level' => env('LOG_LEVEL', 'debug'),
        'days' => 30,  // Keep logs for 30 days
    ],

    'api' => [
        'driver' => 'single',
        'path' => storage_path('logs/api.log'),
        'level' => 'debug',
        'days' => 30,
    ],

    'security' => [
        'driver' => 'single',
        'path' => storage_path('logs/security.log'),
        'level' => 'warning',
        'days' => 90,
    ],
],
```

### Create logging middleware

**File:** `app/Http/Middleware/LogApiRequests.php`

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogApiRequests
{
    public function handle(Request $request, Closure $next)
    {
        $start = microtime(true);

        $response = $next($request);

        $duration = microtime(true) - $start;

        Log::channel('api')->info('API Request', [
            'method' => $request->getMethod(),
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'user_id' => auth()->id(),
            'status' => $response->getStatusCode(),
            'duration_ms' => round($duration * 1000, 2),
        ]);

        return $response;
    }
}
```

Register di kernel:
```php
// app/Http/Kernel.php
protected $routeMiddleware = [
    // ...
    'log.api' => \App\Http\Middleware\LogApiRequests::class,
];
```

---

## 1️⃣1️⃣ ENCRYPT SENSITIVE DATABASE FIELDS

**File:** `app/Models/Karyawan.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    protected $casts = [
        'email' => 'encrypted',        // Encrypt email
        'no_hp' => 'encrypted',        // Encrypt phone
        'no_ktp' => 'encrypted',       // Encrypt KTP
        'alamat' => 'encrypted',       // Encrypt address
    ];

    protected $visible = [
        'nik',
        'nama_karyawan',
        'no_hp',
        'email',
        'alamat',
        // ... other fields
    ];
}
```

**Jalankan migration untuk encrypt existing data:**

```bash
php artisan tinker

# Di tinker:
\App\Models\Karyawan::all()->each->save();
```

---

## 1️⃣2️⃣ SETUP BACKUP STRATEGY

### Langkah 1: Install Laravel Backup package

```bash
composer require spatie/laravel-backup
php artisan vendor:publish --provider="Spatie\Backup\BackupServiceProvider"
```

**File:** `config/backup.php`

```php
return [
    'backup' => [
        'name' => 'bumisultan',

        'source' => [
            'files' => [
                'include' => [
                    base_path(),
                ],
                'exclude' => [
                    base_path('vendor'),
                    base_path('node_modules'),
                    storage_path('framework'),
                ],
            ],

            'databases' => ['mysql'],
        ],

        'destination' => [
            's3' => [
                'disk_name' => 'backup-s3',
                'path' => 'bumisultan-backups',
            ],
        ],

        'password' => env('BACKUP_ENCRYPTION_PASSWORD'),

        'compression' => 'gzip',

        'notification' => [
            'mail' => 'admin@yourdomain.com',
        ],
    ],

    'cleanup' => [
        'default' => 'keep_last_10_days',
        'strategies' => [
            'keep_last_10_days' => [
                'delete_when_older_than_days' => 10,
            ],
        ],
    ],
];
```

### Langkah 2: Schedule backup

**File:** `app/Console/Kernel.php`

```php
protected function schedule(Schedule $schedule)
{
    // Run backup setiap hari jam 2 pagi
    $schedule->command('backup:run')->daily()->at('02:00');

    // Cleanup old backups setiap minggu
    $schedule->command('backup:clean')->weekly();
}
```

### Langkah 3: Setup S3 disk

**File:** `.env`

```dotenv
AWS_ACCESS_KEY_ID=your-key
AWS_SECRET_ACCESS_KEY=your-secret
AWS_DEFAULT_REGION=ap-southeast-1
AWS_BUCKET=bumisultan-backups
```

---

## 1️⃣3️⃣ ADD WEBHOOK SIGNATURE VALIDATION

**File:** `app/Http/Middleware/ValidateWebhookSignature.php`

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ValidateWebhookSignature
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->hasHeader('X-Webhook-Signature')) {
            return response()->json(['error' => 'Missing signature'], 401);
        }

        $signature = $request->header('X-Webhook-Signature');
        $payload = $request->getContent();

        $expected = hash_hmac(
            'sha256',
            $payload,
            env('WEBHOOK_SECRET')
        );

        if (!hash_equals($expected, $signature)) {
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        return $next($request);
    }
}
```

**Register middleware:**

```php
// routes/web.php
Route::middleware('validate.webhook')->group(function () {
    Route::post('/webhooks/whatsapp', [WhatsAppController::class, 'webhook']);
    Route::post('/webhooks/payment', [PaymentController::class, 'webhook']);
});
```

---

## Priority Level 3: MEDIUM PRIORITY FIXES

---

## 1️⃣4️⃣ IMPLEMENT QUERY CACHING

**File:** `app/Models/Departemen.php`

```php
<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;

class Departemen extends Model
{
    public static function getAllCached()
    {
        return Cache::remember('departemen_all', 3600, function() {
            return static::orderBy('nama_dept')->get();
        });
    }

    public static function getByIdCached($id)
    {
        return Cache::remember("departemen_{$id}", 3600, function() use ($id) {
            return static::find($id);
        });
    }

    public static function clearCache()
    {
        Cache::forget('departemen_all');
    }
}
```

**Usage di controller:**

```php
// Sebelum
$departemen = Departemen::orderBy('nama_dept')->get();

// Sesudah
$departemen = Departemen::getAllCached();
```

---

## 1️⃣5️⃣ ADD API DOCUMENTATION

### Install Laravel Scribe

```bash
composer require --dev knuckleswtf/scribe
php artisan scribe:install
```

**Add documentation comments to controllers:**

```php
/**
 * Get all employees
 * 
 * @queryParam page integer The page number. Example: 1
 * @queryParam per_page integer Items per page. Example: 15
 * @queryParam nama_karyawan string Filter by name. Example: John
 * 
 * @response 200 {
 *   "data": [...],
 *   "links": {...},
 *   "meta": {...}
 * }
 */
public function index(Request $request)
{
    // ...
}
```

Generate docs:
```bash
php artisan scribe:generate
```

---

## 1️⃣6️⃣ ADD TWO-FACTOR AUTHENTICATION

### Install Laravel Fortify

```bash
composer require laravel/fortify
php artisan vendor:publish --provider="Laravel\Fortify\FortifyServiceProvider"
```

**File:** `config/fortify.php`

```php
'features' => [
    Features::registration(),
    Features::resetPasswords(),
    Features::emailVerification(),
    Features::twoFactorAuthentication([
        'confirmPassword' => true,
    ]),
],
```

### Enable 2FA di User Model

**File:** `app/Models/User.php`

```php
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    use TwoFactorAuthenticatable;
    
    // ...
}
```

---

## 1️⃣7️⃣ ADD AUTOMATED TESTING

### Create test untuk authentication

```bash
php artisan make:test LoginTest
```

**File:** `tests/Feature/LoginTest.php`

```php
<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class LoginTest extends TestCase
{
    public function test_user_can_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect('/dashboard');
    }

    public function test_user_cannot_login_with_wrong_password()
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function test_login_is_rate_limited()
    {
        $user = User::factory()->create();

        // Try 6 times (more than throttle limit)
        for ($i = 0; $i < 6; $i++) {
            $this->post('/login', [
                'email' => $user->email,
                'password' => 'wrong',
            ]);
        }

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong',
        ]);

        $response->assertStatus(429);  // Too Many Requests
    }
}
```

Run tests:
```bash
php artisan test
```

---

Terima kasih telah menggunakan panduan ini. Semoga aplikasi Anda menjadi lebih aman dan siap untuk production! 🚀
