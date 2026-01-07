// ===================================================
// HELPER FUNCTION: EXPORT PDF PER KUARTAL
// Untuk data besar (> 3000 transaksi per tahun)
// ===================================================

/**
 * Tambahkan di view export PDF atau dashboard
 * Untuk memberikan opsi export per kuartal
 */

// 1. Tambahkan tombol di view (Blade)
/*
<div class="card">
    <div class="card-header">
        <h5>📄 Export Laporan Tahunan (Per Kuartal)</h5>
        <p class="text-muted">Untuk data besar, export dibagi per kuartal agar lebih cepat</p>
    </div>
    <div class="card-body">
        <label>Pilih Tahun:</label>
        <select id="tahunKuartal" class="form-control" style="width: 150px; display: inline-block;">
            <option value="2025">2025</option>
            <option value="2024">2024</option>
            <option value="2023">2023</option>
        </select>
        
        <hr>
        
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-primary" onclick="exportKuartal(1)">
                <i class="fas fa-file-pdf"></i> Q1 (Jan-Mar)
            </button>
            <button type="button" class="btn btn-info" onclick="exportKuartal(2)">
                <i class="fas fa-file-pdf"></i> Q2 (Apr-Jun)
            </button>
            <button type="button" class="btn btn-success" onclick="exportKuartal(3)">
                <i class="fas fa-file-pdf"></i> Q3 (Jul-Sep)
            </button>
            <button type="button" class="btn btn-warning" onclick="exportKuartal(4)">
                <i class="fas fa-file-pdf"></i> Q4 (Okt-Des)
            </button>
        </div>
        
        <button type="button" class="btn btn-secondary mt-2" onclick="exportSemuaKuartal()">
            <i class="fas fa-download"></i> Download Semua Kuartal (4 file)
        </button>
    </div>
</div>
*/

// 2. Tambahkan JavaScript di view
/*
<script>
function exportKuartal(kuartal) {
    const tahun = document.getElementById('tahunKuartal').value;
    
    // Mapping kuartal ke tanggal
    const kuartalMap = {
        1: { start: `${tahun}-01-01`, end: `${tahun}-03-31`, label: 'Q1 (Januari-Maret)' },
        2: { start: `${tahun}-04-01`, end: `${tahun}-06-30`, label: 'Q2 (April-Juni)' },
        3: { start: `${tahun}-07-01`, end: `${tahun}-09-30`, label: 'Q3 (Juli-September)' },
        4: { start: `${tahun}-10-01`, end: `${tahun}-12-31`, label: 'Q4 (Oktober-Desember)' }
    };
    
    const periode = kuartalMap[kuartal];
    
    // Tampilkan loading
    Swal.fire({
        title: 'Memproses...',
        text: `Sedang generate PDF ${periode.label} ${tahun}`,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Buat URL export
    const url = `{{ route('dana-operasional.export-pdf') }}?filter_type=range&start_date=${periode.start}&end_date=${periode.end}`;
    
    // Download PDF
    window.location.href = url;
    
    // Tutup loading setelah 2 detik
    setTimeout(() => {
        Swal.close();
    }, 2000);
}

function exportSemuaKuartal() {
    const tahun = document.getElementById('tahunKuartal').value;
    
    Swal.fire({
        title: 'Download 4 File PDF',
        text: `Download laporan ${tahun} Q1, Q2, Q3, dan Q4?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Download Semua',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Download dengan delay agar tidak overload
            for (let q = 1; q <= 4; q++) {
                setTimeout(() => {
                    exportKuartal(q);
                }, (q - 1) * 2000); // Delay 2 detik antar download
            }
            
            Swal.fire({
                icon: 'success',
                title: 'Download Dimulai!',
                text: '4 file PDF akan didownload satu per satu (delay 2 detik)',
                timer: 5000
            });
        }
    });
}

// Auto-close loading saat PDF sudah mulai download
window.addEventListener('focus', function() {
    if (Swal.isVisible() && Swal.isLoading()) {
        setTimeout(() => {
            Swal.close();
        }, 1000);
    }
});
</script>
*/

// 3. ATAU tambahkan route helper di Controller (Optional)
/*
public function exportKuartal(Request $request, $tahun, $kuartal)
{
    // Validasi kuartal
    if (!in_array($kuartal, [1, 2, 3, 4])) {
        return back()->with('error', 'Kuartal tidak valid');
    }
    
    // Mapping kuartal ke tanggal
    $periodeKuartal = [
        1 => ['start' => "$tahun-01-01", 'end' => "$tahun-03-31", 'label' => 'Q1'],
        2 => ['start' => "$tahun-04-01", 'end' => "$tahun-06-30", 'label' => 'Q2'],
        3 => ['start' => "$tahun-07-01", 'end' => "$tahun-09-30", 'label' => 'Q3'],
        4 => ['start' => "$tahun-10-01", 'end' => "$tahun-12-31", 'label' => 'Q4'],
    ];
    
    $periode = $periodeKuartal[$kuartal];
    
    // Redirect ke export-pdf dengan parameter range
    return redirect()->route('dana-operasional.export-pdf', [
        'filter_type' => 'range',
        'start_date' => $periode['start'],
        'end_date' => $periode['end']
    ]);
}
*/

// 4. Tambahkan route di web.php (Optional)
/*
Route::middleware('role:super admin')->prefix('dana-operasional')->name('dana-operasional.')->group(function () {
    Route::get('/export-kuartal/{tahun}/{kuartal}', [DanaOperasionalController::class, 'exportKuartal'])
        ->name('export-kuartal')
        ->where(['tahun' => '[0-9]{4}', 'kuartal' => '[1-4]']);
});

// Contoh URL:
// /dana-operasional/export-kuartal/2025/1  -> Q1 2025
// /dana-operasional/export-kuartal/2025/2  -> Q2 2025
*/

// 5. ALTERNATIF: Export Per Semester
/*
function exportSemester(semester) {
    const tahun = document.getElementById('tahunKuartal').value;
    
    const semesterMap = {
        1: { start: `${tahun}-01-01`, end: `${tahun}-06-30`, label: 'Semester 1 (Jan-Jun)' },
        2: { start: `${tahun}-07-01`, end: `${tahun}-12-31`, label: 'Semester 2 (Jul-Des)' }
    };
    
    const periode = semesterMap[semester];
    const url = `{{ route('dana-operasional.export-pdf') }}?filter_type=range&start_date=${periode.start}&end_date=${periode.end}`;
    
    window.location.href = url;
}
*/

// ===================================================
// INFO: Kapan Menggunakan Strategi Apa?
// ===================================================
/*
< 3,000 transaksi/tahun    → Export 1 tahun langsung ✅
3,000 - 6,000 transaksi    → Export per SEMESTER (2 file) ✅
6,000 - 10,000 transaksi   → Export per KUARTAL (4 file) ✅
> 10,000 transaksi         → Export per BULAN (12 file) ✅
*/

// ===================================================
// MONITORING: Cek Jumlah Data Per Tahun
// ===================================================
/*
// Jalankan di browser console atau tinker
fetch('/api/dana-operasional/count?tahun=2025')
    .then(r => r.json())
    .then(data => {
        console.log(`Total transaksi 2025: ${data.total}`);
        
        if (data.total < 3000) {
            console.log('✅ AMAN: Export 1 tahun langsung');
        } else if (data.total < 6000) {
            console.log('⚠️ DISARANKAN: Export per semester');
        } else if (data.total < 10000) {
            console.log('⚠️ WAJIB: Export per kuartal');
        } else {
            console.log('❌ DITOLAK: Export per bulan saja');
        }
    });
*/

// ===================================================
// ENDPOINT API: Count Transaksi (Optional)
// ===================================================
/*
// Tambahkan di DanaOperasionalController:
public function countTransaksi(Request $request)
{
    $tahun = $request->get('tahun', date('Y'));
    
    $total = RealisasiDanaOperasional::whereYear('tanggal_realisasi', $tahun)
        ->where('status', 'active')
        ->count();
    
    $rekomendasi = '';
    if ($total < 3000) {
        $rekomendasi = 'Export 1 tahun langsung AMAN';
    } else if ($total < 6000) {
        $rekomendasi = 'DISARANKAN: Export per semester (2 file)';
    } else if ($total < 10000) {
        $rekomendasi = 'WAJIB: Export per kuartal (4 file)';
    } else {
        $rekomendasi = 'DITOLAK: Export per bulan (12 file)';
    }
    
    return response()->json([
        'tahun' => $tahun,
        'total' => $total,
        'rekomendasi' => $rekomendasi
    ]);
}

// Route:
Route::get('/dana-operasional/count', [DanaOperasionalController::class, 'countTransaksi'])
    ->name('dana-operasional.count');
*/
