/* ========================================
   ROUTES UNTUK SISTEM MANAJEMEN INVENTARIS
   Tambahkan ke file routes/web.php
   ======================================== */

// INVENTARIS MANAGEMENT ROUTES
Route::middleware(['auth'])->group(function () {
    
    // ============ MASTER DATA INVENTARIS ============
    Route::resource('inventaris', InventarisController::class);
    
    Route::prefix('inventaris')->name('inventaris.')->group(function () {
        Route::post('/import-barang', [InventarisController::class, 'importFromBarang'])->name('import-barang');
        Route::get('/barangs-for-import', [InventarisController::class, 'getBarangsForImport'])->name('barangs-import');
        Route::get('/export-pdf', [InventarisController::class, 'exportPdf'])->name('export-pdf');
        Route::get('/export-aktivitas-pdf', [InventarisController::class, 'exportAktivitasPdf'])->name('export-aktivitas-pdf');
    });
    
    // ============ PEMINJAMAN INVENTARIS ============
    Route::resource('peminjaman-inventaris', PeminjamanInventarisController::class);
    
    Route::prefix('peminjaman-inventaris')->name('peminjaman-inventaris.')->group(function () {
        Route::post('/{peminjamanInventaris}/setujui', [PeminjamanInventarisController::class, 'setujui'])->name('setujui');
        Route::post('/{peminjamanInventaris}/tolak', [PeminjamanInventarisController::class, 'tolak'])->name('tolak');
        Route::get('/check-ketersediaan/{inventarisId}', [PeminjamanInventarisController::class, 'checkKetersediaan'])->name('check-ketersediaan');
        Route::get('/export-pdf', [PeminjamanInventarisController::class, 'exportPdf'])->name('export-pdf');
    });
    
    // ============ PENGEMBALIAN INVENTARIS ============
    Route::prefix('pengembalian-inventaris')->name('pengembalian-inventaris.')->group(function () {
        Route::get('/', [PengembalianInventarisController::class, 'index'])->name('index');
        Route::get('/create/{peminjamanId}', [PengembalianInventarisController::class, 'create'])->name('create');
        Route::post('/', [PengembalianInventarisController::class, 'store'])->name('store');
        Route::get('/{pengembalianInventaris}', [PengembalianInventarisController::class, 'show'])->name('show');
        Route::get('/select/peminjaman-aktif', [PengembalianInventarisController::class, 'getPeminjamanAktif'])->name('peminjaman-aktif');
        Route::get('/export-pdf', [PengembalianInventarisController::class, 'exportPdf'])->name('export-pdf');
    });
    
    // ============ INVENTARIS EVENT ============
    Route::resource('inventaris-event', InventarisEventController::class);
    
    Route::prefix('inventaris-event')->name('inventaris-event.')->group(function () {
        Route::post('/{inventarisEvent}/add-inventaris', [InventarisEventController::class, 'addInventaris'])->name('add-inventaris');
        Route::delete('/{inventarisEvent}/remove-inventaris/{itemId}', [InventarisEventController::class, 'removeInventaris'])->name('remove-inventaris');
        Route::post('/{inventarisEvent}/cek-ketersediaan', [InventarisEventController::class, 'cekKetersediaan'])->name('cek-ketersediaan');
        Route::post('/{inventarisEvent}/distribusi', [InventarisEventController::class, 'distribusi'])->name('distribusi');
        Route::get('/{inventarisEvent}/get-inventaris', [InventarisEventController::class, 'getInventarisForEvent'])->name('get-inventaris');
        Route::get('/{inventarisEvent}/get-karyawan', [InventarisEventController::class, 'getKaryawanForDistribusi'])->name('get-karyawan');
        Route::get('/{inventarisEvent}/export-pdf', [InventarisEventController::class, 'exportPdf'])->name('export-pdf');
    });
    
    // ============ HISTORY INVENTARIS ============
    Route::prefix('history-inventaris')->name('history-inventaris.')->group(function () {
        Route::get('/', [HistoryInventarisController::class, 'index'])->name('index');
        Route::get('/dashboard', [HistoryInventarisController::class, 'dashboard'])->name('dashboard');
        Route::get('/{historyInventaris}', [HistoryInventarisController::class, 'show'])->name('show');
        Route::get('/inventaris/{inventaris}', [HistoryInventarisController::class, 'byInventaris'])->name('by-inventaris');
        Route::get('/karyawan/{karyawan}', [HistoryInventarisController::class, 'byKaryawan'])->name('by-karyawan');
        Route::get('/export/pdf', [HistoryInventarisController::class, 'exportPdf'])->name('export-pdf');
        Route::get('/export/excel', [HistoryInventarisController::class, 'exportExcel'])->name('export-excel');
    });
    
});
