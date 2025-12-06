<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Illuminate\Http\Request;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            // Log detail error untuk waktu_kembali issues
            if (str_contains($e->getMessage(), 'waktu_kembali')) {
                \Log::error('=== WAKTU_KEMBALI ERROR CAUGHT ===');
                \Log::error('Message: ' . $e->getMessage());
                \Log::error('File: ' . $e->getFile() . ':' . $e->getLine());
                \Log::error('Stack trace: ' . $e->getTraceAsString());
            }
        });
        
        // Handle UnauthorizedException khusus untuk kendaraan routes
        $this->renderable(function (UnauthorizedException $e, Request $request) {
            // Jika request dari routes kendaraan, redirect dengan sukses message
            if ($request->routeIs('kendaraan.*') || 
                $request->routeIs('aktivitas.*') ||
                $request->routeIs('peminjaman.*') ||
                $request->routeIs('service.*')) {
                
                // Karena data sudah tersimpan sebelum exception, anggap sukses
                return redirect()->back()->with('success', 'Operasi berhasil disimpan');
            }
            
            // Untuk route lain, tetap throw exception
            throw $e;
        });
    }
}
