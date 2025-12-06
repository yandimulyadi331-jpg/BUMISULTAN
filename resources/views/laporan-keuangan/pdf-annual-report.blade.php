<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan {{ $periode['nama_periode'] }} - Bumi Sultan</title>
    <style>
        /* ===== GLOBAL STYLES ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', 'Helvetica', sans-serif;
            font-size: 10pt;
            color: #333;
            line-height: 1.6;
        }

        /* ===== COVER PAGE ===== */
        .cover-page {
            page-break-after: always;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 50px;
            text-align: center;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .cover-logo {
            font-size: 48pt;
            font-weight: bold;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .cover-title {
            font-size: 32pt;
            font-weight: bold;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .cover-subtitle {
            font-size: 18pt;
            margin-bottom: 40px;
            font-weight: 300;
        }

        .cover-periode {
            font-size: 20pt;
            margin-bottom: 10px;
            border: 2px solid white;
            padding: 15px 40px;
            border-radius: 5px;
        }

        .cover-footer {
            position: absolute;
            bottom: 50px;
            font-size: 11pt;
            opacity: 0.9;
        }

        /* ===== TABLE OF CONTENTS ===== */
        .toc-page {
            page-break-after: always;
            padding: 40px;
        }

        .toc-title {
            font-size: 22pt;
            font-weight: bold;
            margin-bottom: 25px;
            color: #1e3c72;
            border-bottom: 3px solid #1e3c72;
            padding-bottom: 10px;
        }

        .toc-item {
            margin-bottom: 12px;
            display: flex;
            justify-content: space-between;
            padding: 8px;
            border-bottom: 1px dashed #ccc;
        }

        /* ===== CONTENT PAGES ===== */
        .content-page {
            padding: 40px;
        }

        .section-title {
            font-size: 20pt;
            font-weight: bold;
            color: #1e3c72;
            margin-bottom: 20px;
            border-left: 5px solid #1e3c72;
            padding-left: 15px;
        }

        .subsection-title {
            font-size: 14pt;
            font-weight: bold;
            color: #2a5298;
            margin-top: 20px;
            margin-bottom: 10px;
        }

        /* ===== FINANCIAL HIGHLIGHTS ===== */
        .highlights-grid {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }

        .highlights-row {
            display: table-row;
        }

        .highlight-box {
            display: table-cell;
            width: 33.33%;
            padding: 20px;
            text-align: center;
            border: 2px solid #1e3c72;
            margin: 5px;
        }

        .highlight-box:first-child {
            background-color: #e8f4f8;
        }

        .highlight-box:nth-child(2) {
            background-color: #fff4e6;
        }

        .highlight-box:last-child {
            background-color: #e8f5e9;
        }

        .highlight-label {
            font-size: 11pt;
            color: #666;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .highlight-value {
            font-size: 20pt;
            font-weight: bold;
            color: #1e3c72;
        }

        .highlight-change {
            font-size: 9pt;
            margin-top: 5px;
        }

        .highlight-change.positive {
            color: green;
        }

        .highlight-change.negative {
            color: red;
        }

        /* ===== TABLES ===== */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        thead {
            background-color: #1e3c72;
            color: white;
        }

        thead th {
            padding: 12px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #1e3c72;
        }

        tbody td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tbody tr:hover {
            background-color: #f0f0f0;
        }

        .table-total {
            background-color: #e8f4f8 !important;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        /* ===== SUMMARY BOXES ===== */
        .summary-box {
            background-color: #f5f5f5;
            border-left: 4px solid #1e3c72;
            padding: 15px;
            margin-bottom: 20px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #ddd;
        }

        .summary-row:last-child {
            border-bottom: none;
            font-weight: bold;
            font-size: 12pt;
        }

        /* ===== HEADER & FOOTER ===== */
        .page-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 50px;
            background-color: #1e3c72;
            color: white;
            text-align: center;
            line-height: 50px;
            font-weight: bold;
            font-size: 11pt;
        }

        .page-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 30px;
            background-color: #f5f5f5;
            border-top: 2px solid #1e3c72;
            text-align: center;
            line-height: 30px;
            font-size: 9pt;
            color: #666;
        }

        /* ===== PAGE BREAKS ===== */
        .page-break {
            page-break-after: always;
        }

        /* ===== UTILITIES ===== */
        .mb-30 {
            margin-bottom: 30px;
        }

        .text-success {
            color: green;
        }

        .text-danger {
            color: red;
        }

        .text-primary {
            color: #1e3c72;
        }

        .font-weight-bold {
            font-weight: bold;
        }

        /* ===== CHART PLACEHOLDER ===== */
        .chart-container {
            background-color: #f9f9f9;
            border: 2px solid #ddd;
            padding: 30px;
            text-align: center;
            margin-bottom: 30px;
            min-height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .chart-bar {
            display: inline-block;
            width: 60px;
            background-color: #1e3c72;
            margin: 0 5px;
            vertical-align: bottom;
        }

        .chart-label {
            display: inline-block;
            width: 60px;
            margin: 5px 5px 0 5px;
            font-size: 8pt;
            text-align: center;
        }
    </style>
</head>
<body>

    {{-- ===== COVER PAGE ===== --}}
    <div class="cover-page">
        <div class="cover-logo">🏢 BUMI SULTAN</div>
        <div class="cover-title">Laporan Keuangan</div>
        <div class="cover-subtitle">{{ ucfirst($periode['type']) }} Report</div>
        <div class="cover-periode">{{ $periode['nama_periode'] }}</div>
        <div class="cover-footer">
            Dicetak pada: {{ $tanggal_cetak }}
        </div>
    </div>

    {{-- ===== TABLE OF CONTENTS ===== --}}
    <div class="toc-page">
        <div class="toc-title">DAFTAR ISI</div>
        
        <div style="margin-bottom: 20px; padding: 10px; background-color: #f0f0f0;">
            <strong>BAGIAN I: INFORMASI NARATIF</strong>
        </div>
        <div class="toc-item">
            <span>1. Surat dari Pimpinan</span>
            <span>Hal. 3</span>
        </div>
        <div class="toc-item">
            <span>2. Profil Bumi Sultan</span>
            <span>Hal. 4</span>
        </div>
        <div class="toc-item">
            <span>3. Penjelasan Kategori Dana Operasional</span>
            <span>Hal. 5</span>
        </div>
        <div class="toc-item">
            <span>4. Analisis Kinerja & Tren</span>
            <span>Hal. 6</span>
        </div>
        
        <div style="margin: 20px 0 20px 0; padding: 10px; background-color: #f0f0f0;">
            <strong>BAGIAN II: LAPORAN KEUANGAN</strong>
        </div>
        <div class="toc-item">
            <span>5. Ringkasan Keuangan</span>
            <span>Hal. 7</span>
        </div>
        <div class="toc-item">
            <span>6. Laporan Pemasukan & Pengeluaran</span>
            <span>Hal. 8</span>
        </div>
        <div class="toc-item">
            <span>7. Rekap Tahunan Per Kategori</span>
            <span>Hal. 9</span>
        </div>
        <div class="toc-item">
            <span>8. Posisi Saldo</span>
            <span>Hal. 10</span>
        </div>
        <div class="toc-item">
            <span>9. Arus Kas Operasional</span>
            <span>Hal. 11</span>
        </div>
        <div class="toc-item">
            <span>10. Transaksi Terbesar</span>
            <span>Hal. 12</span>
        </div>
        @if($periode['type'] == 'tahunan')
        <div class="toc-item">
            <span>11. Grafik Performa Bulanan</span>
            <span>Hal. 13</span>
        </div>
        @endif
        <div class="toc-item">
            <span>{{ $periode['type'] == 'tahunan' ? '12' : '11' }}. Catatan & Lampiran</span>
            <span>Hal. {{ $periode['type'] == 'tahunan' ? '14' : '13' }}</span>
        </div>
    </div>

    {{-- ===== BAGIAN I: INFORMASI NARATIF ===== --}}
    
    {{-- ===== SECTION 1: SURAT DARI PIMPINAN ===== --}}
    <div class="content-page page-break">
        <div class="section-title">1. SURAT DARI PIMPINAN</div>
        
        <p style="margin-bottom: 30px; font-style: italic; color: #666;">
            Assalamu'alaikum Warahmatullahi Wabarakatuh
        </p>

        <p style="margin-bottom: 15px; text-align: justify; line-height: 1.8;">
            Alhamdulillah, puji syukur kita panjatkan kehadirat Allah SWT yang telah memberikan rahmat dan karunia-Nya 
            sehingga kami dapat menyajikan Laporan Keuangan untuk periode <strong>{{ $periode['nama_periode'] }}</strong>.
        </p>

        <p style="margin-bottom: 15px; text-align: justify; line-height: 1.8;">
            Laporan ini merupakan bentuk transparansi dan akuntabilitas pengelolaan dana operasional Bumi Sultan. 
            Selama periode ini, total pemasukan kami mencapai <strong>Rp {{ number_format($data['pendapatan'], 0, ',', '.') }}</strong> 
            dengan pengeluaran sebesar <strong>Rp {{ number_format($data['pengeluaran'], 0, ',', '.') }}</strong>, 
            sehingga menghasilkan {{ $data['laba_rugi'] >= 0 ? 'surplus' : 'defisit' }} sebesar 
            <strong>Rp {{ number_format(abs($data['laba_rugi']), 0, ',', '.') }}</strong>.
        </p>

        <p style="margin-bottom: 15px; text-align: justify; line-height: 1.8;">
            Dana operasional kami digunakan untuk berbagai keperluan, terutama untuk <strong>Khidmat Santri</strong> yang merupakan 
            prioritas utama dengan alokasi terbesar. Kami juga memastikan bahwa setiap rupiah digunakan secara efisien dan efektif 
            untuk mendukung operasional sehari-hari.
        </p>

        <p style="margin-bottom: 15px; text-align: justify; line-height: 1.8;">
            {{ $data['laba_rugi'] >= 0 
                ? 'Alhamdulillah, surplus yang tercapai menunjukkan bahwa pengelolaan keuangan berjalan dengan baik dan terkendali. 
                   Kami akan terus berupaya meningkatkan efisiensi dalam setiap pengeluaran.' 
                : 'Defisit yang terjadi akan menjadi evaluasi bagi kami untuk lebih cermat dalam mengelola pengeluaran di periode mendatang.' 
            }}
        </p>

        <p style="margin-bottom: 30px; text-align: justify; line-height: 1.8;">
            Semoga laporan ini dapat memberikan gambaran yang jelas mengenai kondisi keuangan Bumi Sultan. 
            Kami terbuka untuk saran dan masukan demi perbaikan ke depan.
        </p>

        <p style="margin-bottom: 5px; font-style: italic;">
            Wassalamu'alaikum Warahmatullahi Wabarakatuh
        </p>

        <div style="margin-top: 50px;">
            <p style="margin-bottom: 5px;"><strong>{{ \Carbon\Carbon::parse($periode['tanggal_akhir'])->locale('id')->isoFormat('D MMMM YYYY') }}</strong></p>
            <p style="margin-bottom: 60px;">Pimpinan Bumi Sultan</p>
            <p style="margin-bottom: 5px; border-bottom: 1px solid #333; display: inline-block; padding: 0 50px;">&nbsp;</p>
        </div>
    </div>

    {{-- ===== SECTION 2: PROFIL BUMI SULTAN ===== --}}
    <div class="content-page page-break">
        <div class="section-title">2. PROFIL BUMI SULTAN</div>

        <div class="subsection-title">Tentang Kami</div>
        <p style="margin-bottom: 20px; text-align: justify; line-height: 1.8;">
            Bumi Sultan adalah lembaga yang bergerak dalam bidang pendidikan dan pembinaan santri. 
            Kami berkomitmen untuk memberikan pelayanan terbaik dalam pengelolaan operasional sehari-hari, 
            khususnya dalam pemenuhan kebutuhan santri dan pemeliharaan fasilitas.
        </p>

        <div class="subsection-title">Visi</div>
        <div class="summary-box" style="background-color: #e8f4f8;">
            <p style="text-align: center; font-size: 11pt; font-style: italic; padding: 10px;">
                "Menjadi lembaga yang amanah dan profesional dalam pengelolaan dana operasional 
                untuk mendukung keberlangsungan program pendidikan dan pembinaan santri."
            </p>
        </div>

        <div class="subsection-title">Misi</div>
        <ul style="margin-bottom: 20px; line-height: 1.8;">
            <li>Mengelola dana operasional secara transparan dan akuntabel</li>
            <li>Mengutamakan pemenuhan kebutuhan santri (Khidmat) sebagai prioritas utama</li>
            <li>Menjaga efisiensi dan efektivitas dalam setiap pengeluaran</li>
            <li>Memelihara fasilitas dan sarana prasarana dengan baik</li>
            <li>Memberikan laporan berkala kepada stakeholder</li>
        </ul>

        <div class="subsection-title">Struktur Dana Operasional</div>
        <p style="margin-bottom: 15px;">
            Dana operasional Bumi Sultan dikelola melalui sistem yang terintegrasi dengan kategorisasi yang jelas, 
            sehingga memudahkan monitoring dan evaluasi penggunaan dana.
        </p>

        <table>
            <thead>
                <tr>
                    <th>Aspek</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Periode Pelaporan</strong></td>
                    <td>Bulanan, Triwulanan, Semester, dan Tahunan</td>
                </tr>
                <tr>
                    <td><strong>Sistem Pencatatan</strong></td>
                    <td>Real-time digital melalui sistem Bumi Sultan</td>
                </tr>
                <tr>
                    <td><strong>Metode</strong></td>
                    <td>Cash Basis (dicatat saat dana diterima/dikeluarkan)</td>
                </tr>
                <tr>
                    <td><strong>Kategori Utama</strong></td>
                    <td>10+ kategori yang disesuaikan dengan kebutuhan operasional</td>
                </tr>
                <tr>
                    <td><strong>Transparansi</strong></td>
                    <td>Laporan dapat diakses oleh pihak yang berwenang</td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- ===== SECTION 3: PENJELASAN KATEGORI ===== --}}
    <div class="content-page page-break">
        <div class="section-title">3. PENJELASAN KATEGORI DANA OPERASIONAL</div>
        
        <p style="margin-bottom: 30px; color: #666;">
            Berikut adalah penjelasan detail mengenai setiap kategori dana operasional yang digunakan di Bumi Sultan:
        </p>

        <div class="subsection-title">🍽️ Khidmat Santri</div>
        <div class="summary-box" style="border-left-color: #28a745;">
            <p style="margin-bottom: 10px;"><strong>Deskripsi:</strong></p>
            <p style="margin-bottom: 10px;">
                Kategori ini merupakan prioritas utama yang diperuntukkan untuk kebutuhan makan santri sehari-hari. 
                Dana khidmat dikeluarkan rutin setiap hari untuk memastikan santri mendapatkan asupan gizi yang cukup.
            </p>
            <p style="margin-bottom: 10px;"><strong>Nominal Standar:</strong> ± Rp 450.000/hari (dapat berubah sesuai kebutuhan dan jumlah santri)</p>
            <p style="margin-bottom: 0;"><strong>Frekuensi:</strong> Harian</p>
        </div>

        <div class="subsection-title">⚡ Utilitas</div>
        <div class="summary-box" style="border-left-color: #ffc107;">
            <p style="margin-bottom: 10px;"><strong>Deskripsi:</strong></p>
            <p style="margin-bottom: 10px;">
                Dana untuk pembayaran kebutuhan utilitas seperti listrik (PLN), air (PDAM), internet/wifi, dan pulsa. 
                Kategori ini memastikan operasional sehari-hari berjalan lancar.
            </p>
            <p style="margin-bottom: 10px;"><strong>Contoh Pengeluaran:</strong> Token listrik, tagihan air bulanan, paket internet, pulsa komunikasi</p>
            <p style="margin-bottom: 0;"><strong>Frekuensi:</strong> Bulanan atau sesuai kebutuhan</p>
        </div>

        <div class="subsection-title">🚗 Transport & Kendaraan</div>
        <div class="summary-box" style="border-left-color: #007bff;">
            <p style="margin-bottom: 10px;"><strong>Deskripsi:</strong></p>
            <p style="margin-bottom: 10px;">
                Dana untuk keperluan transportasi dan perawatan kendaraan operasional, termasuk BBM, servis rutin, 
                dan perbaikan kendaraan yang digunakan untuk kepentingan Bumi Sultan.
            </p>
            <p style="margin-bottom: 10px;"><strong>Contoh Pengeluaran:</strong> Bensin/solar, oli, service berkala, ganti ban, parkir, tol</p>
            <p style="margin-bottom: 0;"><strong>Frekuensi:</strong> Sesuai kebutuhan mobilitas</p>
        </div>

        <div class="subsection-title">🍴 Konsumsi</div>
        <div class="summary-box" style="border-left-color: #17a2b8;">
            <p style="margin-bottom: 10px;"><strong>Deskripsi:</strong></p>
            <p style="margin-bottom: 10px;">
                Dana untuk kebutuhan konsumsi di luar khidmat santri, seperti rapat, tamu, dan kegiatan khusus. 
                Berbeda dengan Khidmat yang rutin harian, konsumsi ini bersifat insidental.
            </p>
            <p style="margin-bottom: 10px;"><strong>Contoh Pengeluaran:</strong> Snack rapat, makan tamu, catering acara</p>
            <p style="margin-bottom: 0;"><strong>Frekuensi:</strong> Insidental</p>
        </div>

        <div class="subsection-title">📝 ATK & Perlengkapan</div>
        <div class="summary-box" style="border-left-color: #6c757d;">
            <p style="margin-bottom: 10px;"><strong>Deskripsi:</strong></p>
            <p style="margin-bottom: 10px;">
                Dana untuk pembelian alat tulis kantor dan perlengkapan administrasi yang dibutuhkan untuk 
                mendukung kegiatan operasional sehari-hari.
            </p>
            <p style="margin-bottom: 10px;"><strong>Contoh Pengeluaran:</strong> Kertas, pulpen, spidol, map, amplop, tinta printer</p>
            <p style="margin-bottom: 0;"><strong>Frekuensi:</strong> Bulanan atau sesuai kebutuhan</p>
        </div>

        <div class="subsection-title">🧹 Kebersihan</div>
        <div class="summary-box" style="border-left-color: #20c997;">
            <p style="margin-bottom: 10px;"><strong>Deskripsi:</strong></p>
            <p style="margin-bottom: 10px;">
                Dana untuk pembelian perlengkapan kebersihan guna menjaga kebersihan dan kenyamanan lingkungan 
                Bumi Sultan.
            </p>
            <p style="margin-bottom: 10px;"><strong>Contoh Pengeluaran:</strong> Sabun, detergen, sapu, pel, pembersih lantai, tisu</p>
            <p style="margin-bottom: 0;"><strong>Frekuensi:</strong> Bulanan</p>
        </div>

        <div class="subsection-title">🔧 Maintenance (Perawatan)</div>
        <div class="summary-box" style="border-left-color: #fd7e14;">
            <p style="margin-bottom: 10px;"><strong>Deskripsi:</strong></p>
            <p style="margin-bottom: 10px;">
                Dana untuk perawatan dan perbaikan bangunan, fasilitas, serta peralatan. Termasuk renovasi ringan 
                dan perbaikan yang diperlukan untuk menjaga kondisi aset tetap baik.
            </p>
            <p style="margin-bottom: 10px;"><strong>Contoh Pengeluaran:</strong> Perbaikan atap, cat, las, tukang, ganti kunci, perbaikan AC</p>
            <p style="margin-bottom: 0;"><strong>Frekuensi:</strong> Sesuai kebutuhan</p>
        </div>

        <div class="subsection-title">💊 Kesehatan</div>
        <div class="summary-box" style="border-left-color: #dc3545;">
            <p style="margin-bottom: 10px;"><strong>Deskripsi:</strong></p>
            <p style="margin-bottom: 10px;">
                Dana untuk keperluan kesehatan santri dan karyawan, termasuk obat-obatan, vitamin, dan biaya 
                pengobatan darurat.
            </p>
            <p style="margin-bottom: 10px;"><strong>Contoh Pengeluaran:</strong> Obat, vitamin, P3K, biaya dokter/klinik</p>
            <p style="margin-bottom: 0;"><strong>Frekuensi:</strong> Sesuai kebutuhan</p>
        </div>

        <div class="subsection-title">📱 Komunikasi</div>
        <div class="summary-box" style="border-left-color: #6610f2;">
            <p style="margin-bottom: 10px;"><strong>Deskripsi:</strong></p>
            <p style="margin-bottom: 10px;">
                Dana untuk kebutuhan komunikasi seperti pulsa telepon, paket data, dan biaya komunikasi lainnya 
                yang mendukung koordinasi operasional.
            </p>
            <p style="margin-bottom: 10px;"><strong>Contoh Pengeluaran:</strong> Pulsa HP, paket data, biaya SMS</p>
            <p style="margin-bottom: 0;"><strong>Frekuensi:</strong> Bulanan</p>
        </div>

        <div class="subsection-title">📋 Administrasi</div>
        <div class="summary-box" style="border-left-color: #e83e8c;">
            <p style="margin-bottom: 10px;"><strong>Deskripsi:</strong></p>
            <p style="margin-bottom: 10px;">
                Dana untuk keperluan administrasi seperti pengurusan surat, dokumen, legalisir, materai, 
                dan biaya administrasi lainnya.
            </p>
            <p style="margin-bottom: 10px;"><strong>Contoh Pengeluaran:</strong> Materai, legalisir, pengurusan izin, fotocopy dokumen</p>
            <p style="margin-bottom: 0;"><strong>Frekuensi:</strong> Sesuai kebutuhan</p>
        </div>

        <div class="subsection-title">💰 Dana Masuk</div>
        <div class="summary-box" style="border-left-color: #28a745; background-color: #d4edda;">
            <p style="margin-bottom: 10px;"><strong>Deskripsi:</strong></p>
            <p style="margin-bottom: 10px;">
                Kategori ini mencatat semua dana yang masuk ke kas Bumi Sultan, baik dari donatur, setoran, 
                atau sumber pemasukan lainnya yang sah.
            </p>
            <p style="margin-bottom: 10px;"><strong>Sumber Dana:</strong> Donasi, setoran, transfer, dan pemasukan lainnya</p>
            <p style="margin-bottom: 0;"><strong>Pencatatan:</strong> Setiap transaksi dana masuk dicatat dengan rinci</p>
        </div>
    </div>

    {{-- ===== SECTION 4: ANALISIS KINERJA & TREN ===== --}}
    <div class="content-page page-break">
        <div class="section-title">4. ANALISIS KINERJA & TREN</div>

        <div class="subsection-title">Ringkasan Eksekutif</div>
        <p style="margin-bottom: 20px; text-align: justify; line-height: 1.8;">
            Periode {{ $periode['nama_periode'] }} menunjukkan {{ $data['laba_rugi'] >= 0 ? 'kinerja positif' : 'tantangan' }} 
            dalam pengelolaan dana operasional. Total pemasukan sebesar Rp {{ number_format($data['pendapatan'], 0, ',', '.') }} 
            @if($data['perubahan_pendapatan'] != 0)
                mengalami {{ $data['perubahan_pendapatan'] > 0 ? 'kenaikan' : 'penurunan' }} 
                {{ number_format(abs($data['perubahan_pendapatan']), 2) }}% dibanding periode sebelumnya
            @endif, 
            sementara pengeluaran mencapai Rp {{ number_format($data['pengeluaran'], 0, ',', '.') }}
            @if($data['perubahan_pengeluaran'] != 0)
                yang {{ $data['perubahan_pengeluaran'] > 0 ? 'naik' : 'turun' }} 
                {{ number_format(abs($data['perubahan_pengeluaran']), 2) }}%
            @endif.
        </p>

        <div class="subsection-title">Analisis Pemasukan</div>
        <div class="summary-box">
            <p style="margin-bottom: 15px;"><strong>Total Pemasukan:</strong> Rp {{ number_format($data['pendapatan'], 0, ',', '.') }}</p>
            @if(count($data['pendapatan_per_kategori']) > 0)
                @php
                    $pendapatanTertinggi = $data['pendapatan_per_kategori']->first();
                @endphp
                <p style="margin-bottom: 15px;">
                    <strong>Sumber Tertinggi:</strong> {{ $pendapatanTertinggi->kategori }} 
                    (Rp {{ number_format($pendapatanTertinggi->total, 0, ',', '.') }} - 
                    {{ $data['pendapatan'] > 0 ? number_format(($pendapatanTertinggi->total / $data['pendapatan']) * 100, 1) : 0 }}%)
                </p>
            @endif
            <p style="margin-bottom: 0;">
                <strong>Tren:</strong> 
                @if($data['perubahan_pendapatan'] > 5)
                    <span style="color: green;">▲ Pemasukan meningkat signifikan, menunjukkan dukungan yang baik</span>
                @elseif($data['perubahan_pendapatan'] > 0)
                    <span style="color: green;">▲ Pemasukan meningkat stabil</span>
                @elseif($data['perubahan_pendapatan'] < -5)
                    <span style="color: red;">▼ Pemasukan menurun, perlu perhatian khusus</span>
                @elseif($data['perubahan_pendapatan'] < 0)
                    <span style="color: orange;">▼ Pemasukan sedikit menurun</span>
                @else
                    <span style="color: #666;">— Pemasukan stabil</span>
                @endif
            </p>
        </div>

        <div class="subsection-title">Analisis Pengeluaran</div>
        <div class="summary-box">
            <p style="margin-bottom: 15px;"><strong>Total Pengeluaran:</strong> Rp {{ number_format($data['pengeluaran'], 0, ',', '.') }}</p>
            @if(count($data['pengeluaran_per_kategori']) > 0)
                @php
                    $pengeluaranTertinggi = $data['pengeluaran_per_kategori']->first();
                @endphp
                <p style="margin-bottom: 15px;">
                    <strong>Kategori Terbesar:</strong> {{ $pengeluaranTertinggi->kategori }} 
                    (Rp {{ number_format($pengeluaranTertinggi->total, 0, ',', '.') }} - 
                    {{ $data['pengeluaran'] > 0 ? number_format(($pengeluaranTertinggi->total / $data['pengeluaran']) * 100, 1) : 0 }}%)
                </p>
            @endif
            <p style="margin-bottom: 0;">
                <strong>Evaluasi:</strong> 
                @if($data['perubahan_pengeluaran'] > 10)
                    <span style="color: red;">▲ Pengeluaran meningkat tinggi, perlu efisiensi</span>
                @elseif($data['perubahan_pengeluaran'] > 5)
                    <span style="color: orange;">▲ Pengeluaran meningkat, perlu monitoring</span>
                @elseif($data['perubahan_pengeluaran'] > 0)
                    <span style="color: #666;">▲ Pengeluaran meningkat wajar</span>
                @elseif($data['perubahan_pengeluaran'] < 0)
                    <span style="color: green;">▼ Pengeluaran berkurang, efisiensi baik</span>
                @else
                    <span style="color: #666;">— Pengeluaran stabil</span>
                @endif
            </p>
        </div>

        <div class="subsection-title">Rasio & Indikator Keuangan</div>
        <table>
            <thead>
                <tr>
                    <th>Indikator</th>
                    <th class="text-right">Nilai</th>
                    <th>Interpretasi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Rasio Pengeluaran terhadap Pemasukan</td>
                    <td class="text-right">{{ $data['pendapatan'] > 0 ? number_format(($data['pengeluaran'] / $data['pendapatan']) * 100, 2) : 0 }}%</td>
                    <td>
                        @php $rasio = $data['pendapatan'] > 0 ? ($data['pengeluaran'] / $data['pendapatan']) * 100 : 0; @endphp
                        @if($rasio < 80)
                            <span style="color: green;">Sangat Efisien</span>
                        @elseif($rasio < 95)
                            <span style="color: #28a745;">Efisien</span>
                        @elseif($rasio < 100)
                            <span style="color: orange;">Perlu Perhatian</span>
                        @else
                            <span style="color: red;">Defisit</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Perubahan Kas</td>
                    <td class="text-right">Rp {{ number_format($data['laba_rugi'], 0, ',', '.') }}</td>
                    <td style="color: {{ $data['laba_rugi'] >= 0 ? 'green' : 'red' }}">
                        {{ $data['laba_rugi'] >= 0 ? 'Surplus' : 'Defisit' }}
                    </td>
                </tr>
                <tr>
                    <td>Rata-rata Pengeluaran Harian</td>
                    <td class="text-right">
                        Rp {{ number_format($data['pengeluaran'] / max(1, $periode['tanggal_awal']->diffInDays($periode['tanggal_akhir']) + 1), 0, ',', '.') }}
                    </td>
                    <td>Per hari</td>
                </tr>
                <tr>
                    <td>Persentase Khidmat Santri</td>
                    <td class="text-right">
                        @php
                            $khidmatTotal = $data['pengeluaran_per_kategori']->where('kategori', 'Khidmat')->first()->total ?? 0;
                        @endphp
                        {{ $data['pengeluaran'] > 0 ? number_format(($khidmatTotal / $data['pengeluaran']) * 100, 2) : 0 }}%
                    </td>
                    <td>Dari total pengeluaran</td>
                </tr>
            </tbody>
        </table>

        <div class="subsection-title">Rekomendasi</div>
        <div class="summary-box" style="background-color: #fff3cd; border-left-color: #ffc107;">
            <p style="margin-bottom: 10px; font-weight: bold;">Berdasarkan analisis di atas, berikut rekomendasi:</p>
            <ol style="margin-left: 20px; line-height: 1.8;">
                @if($data['laba_rugi'] >= 0)
                    <li>Pertahankan surplus dengan terus menjaga efisiensi pengeluaran</li>
                    <li>Alokasikan sebagian surplus untuk dana cadangan (emergency fund)</li>
                @else
                    <li>Evaluasi pengeluaran yang dapat dikurangi tanpa mengurangi kualitas layanan</li>
                    <li>Cari sumber pemasukan tambahan untuk menutupi defisit</li>
                @endif
                <li>Monitor kategori dengan pengeluaran terbesar secara berkala</li>
                <li>Tingkatkan dokumentasi untuk setiap transaksi</li>
                <li>Lakukan review keuangan minimal sebulan sekali</li>
            </ol>
        </div>
    </div>

    {{-- ===== BAGIAN II: LAPORAN KEUANGAN ===== --}}

    {{-- ===== SECTION 5: RINGKASAN KEUANGAN ===== --}}
    <div class="content-page page-break">
        <div class="section-title">5. RINGKASAN KEUANGAN</div>
        <p style="margin-bottom: 30px; color: #666;">
            Ringkasan keuangan untuk periode {{ $periode['nama_periode'] }}.
        </p>

        <div class="highlights-grid">
            <div class="highlights-row">
                <div class="highlight-box">
                    <div class="highlight-label">PEMASUKAN</div>
                    <div class="highlight-value">Rp {{ number_format($data['pendapatan'], 0, ',', '.') }}</div>
                    <div class="highlight-change {{ $data['perubahan_pendapatan'] >= 0 ? 'positive' : 'negative' }}">
                        {{ $data['perubahan_pendapatan'] >= 0 ? '▲' : '▼' }} 
                        {{ number_format(abs($data['perubahan_pendapatan']), 2) }}%
                    </div>
                </div>
                <div class="highlight-box">
                    <div class="highlight-label">PENGELUARAN</div>
                    <div class="highlight-value">Rp {{ number_format($data['pengeluaran'], 0, ',', '.') }}</div>
                    <div class="highlight-change {{ $data['perubahan_pengeluaran'] >= 0 ? 'negative' : 'positive' }}">
                        {{ $data['perubahan_pengeluaran'] >= 0 ? '▲' : '▼' }} 
                        {{ number_format(abs($data['perubahan_pengeluaran']), 2) }}%
                    </div>
                </div>
                <div class="highlight-box">
                    <div class="highlight-label">SELISIH (Surplus/Defisit)</div>
                    <div class="highlight-value" style="color: {{ $data['laba_rugi'] >= 0 ? 'green' : 'red' }}">
                        Rp {{ number_format($data['laba_rugi'], 0, ',', '.') }}
                    </div>
                    <div class="highlight-change {{ $data['perubahan_laba_rugi'] >= 0 ? 'positive' : 'negative' }}">
                        {{ $data['perubahan_laba_rugi'] >= 0 ? '▲' : '▼' }} 
                        {{ number_format(abs($data['perubahan_laba_rugi']), 2) }}%
                    </div>
                </div>
            </div>
        </div>

        <div class="subsection-title">Informasi Tambahan</div>
        <div class="summary-box">
            <div class="summary-row">
                <span>Total Transaksi</span>
                <span>{{ number_format($data['total_transaksi'], 0, ',', '.') }} transaksi</span>
            </div>
            <div class="summary-row">
                <span>Rata-rata Transaksi Harian</span>
                <span>{{ number_format($data['rata_rata_transaksi_harian'], 2, ',', '.') }} transaksi/hari</span>
            </div>
            <div class="summary-row">
                <span>Saldo Awal Periode</span>
                <span>Rp {{ number_format($data['saldo_awal'], 0, ',', '.') }}</span>
            </div>
            <div class="summary-row">
                <span>Saldo Akhir Periode</span>
                <span>Rp {{ number_format($data['saldo_akhir'], 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    {{-- ===== SECTION 6: LAPORAN PEMASUKAN & PENGELUARAN ===== --}}
    <div class="content-page page-break">
        <div class="section-title">6. LAPORAN PEMASUKAN & PENGELUARAN</div>
        <p style="margin-bottom: 30px; color: #666;">
            Rincian pemasukan dan pengeluaran untuk periode {{ $periode['nama_periode'] }}.
        </p>

        <div class="subsection-title">A. Pemasukan (Dana Masuk)</div>
        <table>
            <thead>
                <tr>
                    <th>Kategori</th>
                    <th class="text-right">Jumlah (Rp)</th>
                    <th class="text-right">Persentase</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['pendapatan_per_kategori'] as $item)
                <tr>
                    <td>{{ $item->kategori }}</td>
                    <td class="text-right">{{ number_format($item->total, 0, ',', '.') }}</td>
                    <td class="text-right">{{ $data['pendapatan'] > 0 ? number_format(($item->total / $data['pendapatan']) * 100, 2) : 0 }}%</td>
                </tr>
                @endforeach
                <tr class="table-total">
                    <td>TOTAL PENDAPATAN</td>
                    <td class="text-right">{{ number_format($data['pendapatan'], 0, ',', '.') }}</td>
                    <td class="text-right">100%</td>
                </tr>
            </tbody>
        </table>

        <div class="subsection-title">B. Pengeluaran (Dana Keluar)</div>
        <table>
            <thead>
                <tr>
                    <th>Kategori</th>
                    <th class="text-right">Jumlah (Rp)</th>
                    <th class="text-right">Persentase</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['pengeluaran_per_kategori'] as $item)
                <tr>
                    <td>{{ $item->kategori }}</td>
                    <td class="text-right">{{ number_format($item->total, 0, ',', '.') }}</td>
                    <td class="text-right">{{ $data['pengeluaran'] > 0 ? number_format(($item->total / $data['pengeluaran']) * 100, 2) : 0 }}%</td>
                </tr>
                @endforeach
                <tr class="table-total">
                    <td>TOTAL PENGELUARAN</td>
                    <td class="text-right">{{ number_format($data['pengeluaran'], 0, ',', '.') }}</td>
                    <td class="text-right">100%</td>
                </tr>
            </tbody>
        </table>

        <div class="summary-box">
            <div class="summary-row">
                <span>TOTAL PEMASUKAN</span>
                <span>Rp {{ number_format($data['pendapatan'], 0, ',', '.') }}</span>
            </div>
            <div class="summary-row">
                <span>TOTAL PENGELUARAN</span>
                <span>(Rp {{ number_format($data['pengeluaran'], 0, ',', '.') }})</span>
            </div>
            <div class="summary-row" style="color: {{ $data['laba_rugi'] >= 0 ? 'green' : 'red' }}">
                <span>{{ $data['laba_rugi'] >= 0 ? 'SURPLUS (Sisa Lebih)' : 'DEFISIT (Sisa Kurang)' }}</span>
                <span>Rp {{ number_format($data['laba_rugi'], 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    {{-- ===== SECTION 7: REKAP TAHUNAN PER KATEGORI ===== --}}
    <div class="content-page page-break">
        <div class="section-title">7. REKAP {{ strtoupper($periode['type']) }} PER KATEGORI</div>
        <p style="margin-bottom: 30px; color: #666;">
            Rincian lengkap pengeluaran dan pemasukan per kategori selama periode {{ $periode['nama_periode'] }}, 
            termasuk detail nominal yang dikeluarkan untuk setiap kategori operasional.
        </p>

        <div class="subsection-title">A. Rekap Pengeluaran Per Kategori</div>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kategori</th>
                    <th class="text-right">Total (Rp)</th>
                    <th class="text-center">Jumlah Transaksi</th>
                    <th class="text-right">Rata-rata/Transaksi</th>
                    <th class="text-right">% dari Total</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach($data['pengeluaran_per_kategori'] as $item)
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td><strong>{{ $item->kategori }}</strong></td>
                    <td class="text-right"><strong>{{ number_format($item->total, 0, ',', '.') }}</strong></td>
                    <td class="text-center">{{ number_format($item->jumlah_transaksi ?? 0, 0, ',', '.') }}</td>
                    <td class="text-right">
                        {{ number_format(($item->jumlah_transaksi ?? 0) > 0 ? $item->total / $item->jumlah_transaksi : 0, 0, ',', '.') }}
                    </td>
                    <td class="text-right">
                        <strong>{{ $data['pengeluaran'] > 0 ? number_format(($item->total / $data['pengeluaran']) * 100, 2) : 0 }}%</strong>
                    </td>
                </tr>
                @endforeach
                <tr class="table-total">
                    <td colspan="2" class="text-center"><strong>TOTAL PENGELUARAN</strong></td>
                    <td class="text-right"><strong>{{ number_format($data['pengeluaran'], 0, ',', '.') }}</strong></td>
                    <td colspan="2"></td>
                    <td class="text-right"><strong>100%</strong></td>
                </tr>
            </tbody>
        </table>

        <div class="subsection-title" style="margin-top: 30px;">B. Detail Kategori Pengeluaran Terbesar</div>
        
        @php
            $topKategori = $data['pengeluaran_per_kategori']->take(5);
        @endphp
        
        @foreach($topKategori as $index => $kategori)
            <div class="summary-box" style="margin-bottom: 20px; border-left-width: 5px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <strong style="font-size: 11pt; color: #1e3c72;">
                        {{ $index + 1 }}. {{ $kategori->kategori }}
                    </strong>
                    <strong style="font-size: 11pt; color: #dc3545;">
                        Rp {{ number_format($kategori->total, 0, ',', '.') }}
                    </strong>
                </div>
                
                <div style="margin-bottom: 8px;">
                    <div style="background-color: #e0e0e0; height: 20px; border-radius: 10px; overflow: hidden;">
                        <div style="background-color: #1e3c72; height: 100%; width: {{ $data['pengeluaran'] > 0 ? ($kategori->total / $data['pengeluaran']) * 100 : 0 }}%; 
                                    display: flex; align-items: center; justify-content: center; color: white; font-size: 9pt;">
                            {{ $data['pengeluaran'] > 0 ? number_format(($kategori->total / $data['pengeluaran']) * 100, 1) : 0 }}%
                        </div>
                    </div>
                </div>
                
                <div style="display: flex; justify-content: space-between; font-size: 9pt; color: #666;">
                    <span>Jumlah Transaksi: {{ number_format($kategori->jumlah_transaksi ?? 0, 0, ',', '.') }}x</span>
                    <span>Rata-rata: Rp {{ number_format(($kategori->jumlah_transaksi ?? 0) > 0 ? $kategori->total / $kategori->jumlah_transaksi : 0, 0, ',', '.') }}</span>
                </div>
                
                @if($kategori->kategori == 'Khidmat')
                    <div style="margin-top: 10px; padding: 8px; background-color: #fff3cd; border-radius: 5px; font-size: 9pt;">
                        <strong>📌 Catatan:</strong> Dana Khidmat digunakan untuk kebutuhan makan santri sehari-hari. 
                        Nominal standar ± Rp 450.000/hari (dapat berubah sesuai jumlah santri dan kebutuhan).
                    </div>
                @elseif($kategori->kategori == 'Utilitas')
                    <div style="margin-top: 10px; padding: 8px; background-color: #d1ecf1; border-radius: 5px; font-size: 9pt;">
                        <strong>📌 Catatan:</strong> Kategori Utilitas mencakup pembayaran listrik (PLN), air (PDAM), 
                        internet/wifi, dan pulsa untuk kebutuhan operasional sehari-hari.
                    </div>
                @elseif($kategori->kategori == 'Transport & Kendaraan')
                    <div style="margin-top: 10px; padding: 8px; background-color: #cce5ff; border-radius: 5px; font-size: 9pt;">
                        <strong>📌 Catatan:</strong> Dana transport untuk BBM, servis kendaraan, dan keperluan mobilitas 
                        operasional Bumi Sultan.
                    </div>
                @elseif($kategori->kategori == 'Maintenance')
                    <div style="margin-top: 10px; padding: 8px; background-color: #ffe5cc; border-radius: 5px; font-size: 9pt;">
                        <strong>📌 Catatan:</strong> Dana maintenance untuk perawatan dan perbaikan bangunan, fasilitas, 
                        serta peralatan agar tetap dalam kondisi baik.
                    </div>
                @elseif($kategori->kategori == 'Kebersihan')
                    <div style="margin-top: 10px; padding: 8px; background-color: #d4edda; border-radius: 5px; font-size: 9pt;">
                        <strong>📌 Catatan:</strong> Kategori Kebersihan untuk pembelian perlengkapan kebersihan guna 
                        menjaga kenyamanan lingkungan Bumi Sultan.
                    </div>
                @endif
            </div>
        @endforeach

        <div class="subsection-title" style="margin-top: 30px;">C. Rekap Pemasukan Per Kategori</div>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kategori</th>
                    <th class="text-right">Total (Rp)</th>
                    <th class="text-center">Jumlah Transaksi</th>
                    <th class="text-right">Rata-rata/Transaksi</th>
                    <th class="text-right">% dari Total</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach($data['pendapatan_per_kategori'] as $item)
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td><strong>{{ $item->kategori }}</strong></td>
                    <td class="text-right"><strong>{{ number_format($item->total, 0, ',', '.') }}</strong></td>
                    <td class="text-center">{{ number_format($item->jumlah_transaksi ?? 0, 0, ',', '.') }}</td>
                    <td class="text-right">
                        {{ number_format(($item->jumlah_transaksi ?? 0) > 0 ? $item->total / $item->jumlah_transaksi : 0, 0, ',', '.') }}
                    </td>
                    <td class="text-right">
                        <strong>{{ $data['pendapatan'] > 0 ? number_format(($item->total / $data['pendapatan']) * 100, 2) : 0 }}%</strong>
                    </td>
                </tr>
                @endforeach
                <tr class="table-total">
                    <td colspan="2" class="text-center"><strong>TOTAL PEMASUKAN</strong></td>
                    <td class="text-right"><strong>{{ number_format($data['pendapatan'], 0, ',', '.') }}</strong></td>
                    <td colspan="2"></td>
                    <td class="text-right"><strong>100%</strong></td>
                </tr>
            </tbody>
        </table>

        <div class="subsection-title" style="margin-top: 30px;">D. Ringkasan Komparatif</div>
        <div class="summary-box" style="background-color: #f8f9fa;">
            <div class="summary-row">
                <span><strong>Total Pemasukan</strong></span>
                <span style="color: green;"><strong>Rp {{ number_format($data['pendapatan'], 0, ',', '.') }}</strong></span>
            </div>
            <div class="summary-row">
                <span><strong>Total Pengeluaran</strong></span>
                <span style="color: red;"><strong>Rp {{ number_format($data['pengeluaran'], 0, ',', '.') }}</strong></span>
            </div>
            <div class="summary-row" style="background-color: {{ $data['laba_rugi'] >= 0 ? '#d4edda' : '#f8d7da' }}; padding: 10px; margin-top: 10px;">
                <span><strong>{{ $data['laba_rugi'] >= 0 ? 'SURPLUS (Sisa Lebih)' : 'DEFISIT (Sisa Kurang)' }}</strong></span>
                <span style="color: {{ $data['laba_rugi'] >= 0 ? 'green' : 'red' }}; font-size: 12pt;">
                    <strong>Rp {{ number_format($data['laba_rugi'], 0, ',', '.') }}</strong>
                </span>
            </div>
        </div>

        @if($periode['type'] == 'tahunan')
        <div style="margin-top: 30px; padding: 15px; background-color: #e7f3ff; border-left: 4px solid #0066cc;">
            <p style="margin-bottom: 10px; font-weight: bold; color: #0066cc;">💡 Insight Tahunan:</p>
            <p style="margin-bottom: 8px; font-size: 10pt; line-height: 1.6;">
                • Kategori <strong>{{ $data['pengeluaran_per_kategori']->first()->kategori ?? '-' }}</strong> 
                menjadi pengeluaran terbesar dengan total 
                <strong>Rp {{ number_format($data['pengeluaran_per_kategori']->first()->total ?? 0, 0, ',', '.') }}</strong>
            </p>
            <p style="margin-bottom: 8px; font-size: 10pt; line-height: 1.6;">
                • Efisiensi pengeluaran: 
                {{ $data['pendapatan'] > 0 ? number_format(($data['pengeluaran'] / $data['pendapatan']) * 100, 1) : 0 }}% 
                dari pemasukan
            </p>
            <p style="margin-bottom: 0; font-size: 10pt; line-height: 1.6;">
                • Status keuangan tahun ini: 
                <strong style="color: {{ $data['laba_rugi'] >= 0 ? 'green' : 'red' }}">
                    {{ $data['laba_rugi'] >= 0 ? 'SEHAT (Surplus)' : 'PERLU PERHATIAN (Defisit)' }}
                </strong>
            </p>
        </div>
        @endif
    </div>

    {{-- ===== SECTION 8: POSISI SALDO ===== --}}
    <div class="content-page page-break">
        <div class="section-title">8. POSISI SALDO</div>
        <p style="margin-bottom: 30px; color: #666;">
            Posisi saldo kas pada awal dan akhir periode {{ $periode['nama_periode'] }}.
        </p>

        <table>
            <thead>
                <tr>
                    <th>Keterangan</th>
                    <th class="text-right">Jumlah (Rp)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="2" style="background-color: #e8f4f8; font-weight: bold;">SALDO KAS</td>
                </tr>
                <tr>
                    <td style="padding-left: 30px;">Saldo Awal Periode ({{ $periode['tanggal_awal']->format('d M Y') }})</td>
                    <td class="text-right">{{ number_format($data['saldo_awal'], 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td style="padding-left: 30px;">Kas Masuk (Pendapatan)</td>
                    <td class="text-right text-success">{{ number_format($data['pendapatan'], 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td style="padding-left: 30px;">Kas Keluar (Pengeluaran)</td>
                    <td class="text-right text-danger">({{ number_format($data['pengeluaran'], 0, ',', '.') }})</td>
                </tr>
                <tr class="table-total">
                    <td>Saldo Akhir Periode ({{ $periode['tanggal_akhir']->format('d M Y') }})</td>
                    <td class="text-right">{{ number_format($data['saldo_akhir'], 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <div class="subsection-title">Perubahan Posisi Keuangan</div>
        <div class="summary-box">
            <div class="summary-row">
                <span>Perubahan Kas</span>
                <span style="color: {{ $data['laba_rugi'] >= 0 ? 'green' : 'red' }}">
                    Rp {{ number_format($data['laba_rugi'], 0, ',', '.') }}
                </span>
            </div>
            <div class="summary-row">
                <span>Persentase Perubahan</span>
                <span style="color: {{ $data['laba_rugi'] >= 0 ? 'green' : 'red' }}">
                    {{ $data['saldo_awal'] > 0 ? number_format(($data['laba_rugi'] / $data['saldo_awal']) * 100, 2) : 0 }}%
                </span>
            </div>
        </div>
    </div>

    {{-- ===== SECTION 9: ARUS KAS OPERASIONAL ===== --}}
    <div class="content-page page-break">
        <div class="section-title">9. ARUS KAS OPERASIONAL</div>
        <p style="margin-bottom: 30px; color: #666;">
            Ringkasan keluar masuk kas untuk periode {{ $periode['nama_periode'] }}.
        </p>

        <table>
            <thead>
                <tr>
                    <th>Aktivitas</th>
                    <th class="text-center">Jumlah Transaksi</th>
                    <th class="text-right">Total (Rp)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="3" style="background-color: #e8f4f8; font-weight: bold;">ARUS KAS DARI AKTIVITAS OPERASIONAL</td>
                </tr>
                <tr>
                    <td style="padding-left: 30px;">Kas Masuk dari Operasional</td>
                    <td class="text-center">{{ number_format($data['arus_kas_masuk_count'], 0, ',', '.') }}</td>
                    <td class="text-right text-success">{{ number_format($data['arus_kas_masuk'], 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td style="padding-left: 30px;">Kas Keluar untuk Operasional</td>
                    <td class="text-center">{{ number_format($data['arus_kas_keluar_count'], 0, ',', '.') }}</td>
                    <td class="text-right text-danger">({{ number_format($data['arus_kas_keluar'], 0, ',', '.') }})</td>
                </tr>
                <tr class="table-total">
                    <td>ARUS KAS BERSIH DARI AKTIVITAS OPERASIONAL</td>
                    <td class="text-center">{{ number_format($data['total_transaksi'], 0, ',', '.') }}</td>
                    <td class="text-right" style="color: {{ $data['arus_kas_bersih'] >= 0 ? 'green' : 'red' }}">
                        {{ number_format($data['arus_kas_bersih'], 0, ',', '.') }}
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="subsection-title">Rekonsiliasi Laba Bersih dengan Arus Kas</div>
        <div class="summary-box">
            <div class="summary-row">
                <span>Laba/Rugi Bersih</span>
                <span>Rp {{ number_format($data['laba_rugi'], 0, ',', '.') }}</span>
            </div>
            <div class="summary-row">
                <span>Arus Kas Bersih</span>
                <span>Rp {{ number_format($data['arus_kas_bersih'], 0, ',', '.') }}</span>
            </div>
            <div class="summary-row">
                <span>Selisih</span>
                <span>Rp {{ number_format($data['laba_rugi'] - $data['arus_kas_bersih'], 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    {{-- ===== SECTION 10: TRANSAKSI TERBESAR ===== --}}
    <div class="content-page page-break">
        <div class="section-title">10. TRANSAKSI TERBESAR (10 Teratas)</div>
        <p style="margin-bottom: 30px; color: #666;">
            Daftar 10 transaksi dengan nilai terbesar selama periode {{ $periode['nama_periode'] }}.
        </p>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Tipe</th>
                    <th>Kategori</th>
                    <th>Keterangan</th>
                    <th class="text-right">Jumlah (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['transaksi_terbesar'] as $index => $transaksi)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($transaksi->tanggal_realisasi)->format('d M Y') }}</td>
                    <td>
                        <span style="color: {{ $transaksi->tipe_transaksi == 'Dana Masuk' ? 'green' : 'red' }}">
                            {{ $transaksi->tipe_transaksi == 'Dana Masuk' ? '▲ Masuk' : '▼ Keluar' }}
                        </span>
                    </td>
                    <td>{{ $transaksi->kategori }}</td>
                    <td>{{ Str::limit($transaksi->keterangan, 40) }}</td>
                    <td class="text-right font-weight-bold">{{ number_format($transaksi->nominal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- ===== SECTION 11: MONTHLY CHART (hanya untuk tahunan) ===== --}}
    @if($periode['type'] == 'tahunan' && count($data['data_bulanan']) > 0)
    <div class="content-page page-break">
        <div class="section-title">11. GRAFIK PERFORMA BULANAN</div>
        <p style="margin-bottom: 30px; color: #666;">
            Grafik perbandingan pemasukan dan pengeluaran per bulan tahun {{ $periode['tahun'] }}.
        </p>

        <div class="subsection-title">Tren Pemasukan vs Pengeluaran</div>
        <div class="chart-container">
            <div style="width: 100%;">
                @foreach($data['data_bulanan'] as $bulan)
                    @php
                        $maxValue = max($data['data_bulanan']->pluck('pendapatan')->max(), $data['data_bulanan']->pluck('pengeluaran')->max());
                        $heightPendapatan = $maxValue > 0 ? ($bulan['pendapatan'] / $maxValue) * 200 : 0;
                        $heightPengeluaran = $maxValue > 0 ? ($bulan['pengeluaran'] / $maxValue) * 200 : 0;
                    @endphp
                    <div style="display: inline-block; margin: 0 8px; text-align: center; vertical-align: bottom;">
                        <div class="chart-bar" style="height: {{ $heightPendapatan }}px; background-color: #28a745;"></div>
                        <div class="chart-bar" style="height: {{ $heightPengeluaran }}px; background-color: #dc3545; margin-left: 5px;"></div>
                        <div class="chart-label">{{ $bulan['bulan'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <table style="margin-top: 20px;">
            <thead>
                <tr>
                    <th>Bulan</th>
                    <th class="text-right">Pemasukan</th>
                    <th class="text-right">Pengeluaran</th>
                    <th class="text-right">Selisih</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['data_bulanan'] as $bulan)
                <tr>
                    <td>{{ $bulan['bulan'] }}</td>
                    <td class="text-right">{{ number_format($bulan['pendapatan'], 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($bulan['pengeluaran'], 0, ',', '.') }}</td>
                    <td class="text-right" style="color: {{ $bulan['laba_rugi'] >= 0 ? 'green' : 'red' }}">
                        {{ number_format($bulan['laba_rugi'], 0, ',', '.') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- ===== SECTION 12: CATATAN & LAMPIRAN ===== --}}
    <div class="content-page">
        <div class="section-title">{{ $periode['type'] == 'tahunan' ? '12' : '11' }}. CATATAN & LAMPIRAN</div>
        <p style="margin-bottom: 30px; color: #666;">
            Catatan tambahan dan informasi penting mengenai laporan keuangan periode {{ $periode['nama_periode'] }}.
        </p>

        <div class="subsection-title">1. Kebijakan Akuntansi</div>
        <p style="margin-bottom: 15px; text-align: justify; line-height: 1.8;">
            <strong>a. Basis Pencatatan:</strong><br>
            Laporan keuangan ini disusun dengan menggunakan basis kas (cash basis), dimana pendapatan dan pengeluaran 
            dicatat pada saat kas diterima atau dikeluarkan.
        </p>
        <p style="margin-bottom: 15px; text-align: justify; line-height: 1.8;">
            <strong>b. Mata Uang Pelaporan:</strong><br>
            Semua nilai dalam laporan ini disajikan dalam Rupiah (Rp) dan dibulatkan ke angka terdekat.
        </p>
        <p style="margin-bottom: 20px; text-align: justify; line-height: 1.8;">
            <strong>c. Pengakuan Transaksi:</strong><br>
            Setiap transaksi dicatat secara real-time melalui sistem digital Bumi Sultan dengan mencantumkan 
            tanggal, nominal, kategori, dan keterangan yang jelas.
        </p>

        <div class="subsection-title">2. Penjelasan Kategori Transaksi</div>
        <table style="font-size: 9pt;">
            <thead>
                <tr>
                    <th style="width: 25%;">Kategori</th>
                    <th>Penjelasan</th>
                    <th style="width: 20%;">Sifat</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Khidmat Santri</strong></td>
                    <td>Dana untuk kebutuhan makan santri (± Rp 450.000/hari, dapat berubah)</td>
                    <td>Rutin Harian</td>
                </tr>
                <tr>
                    <td><strong>Utilitas</strong></td>
                    <td>Listrik (PLN), air (PDAM), internet/wifi, pulsa</td>
                    <td>Rutin Bulanan</td>
                </tr>
                <tr>
                    <td><strong>Transport & Kendaraan</strong></td>
                    <td>BBM, servis kendaraan, oli, ban, parkir, tol</td>
                    <td>Sesuai Kebutuhan</td>
                </tr>
                <tr>
                    <td><strong>Konsumsi</strong></td>
                    <td>Makan/minum untuk rapat, tamu, acara khusus</td>
                    <td>Insidental</td>
                </tr>
                <tr>
                    <td><strong>ATK & Perlengkapan</strong></td>
                    <td>Alat tulis kantor, kertas, pulpen, spidol, tinta printer</td>
                    <td>Rutin Bulanan</td>
                </tr>
                <tr>
                    <td><strong>Kebersihan</strong></td>
                    <td>Sabun, detergen, sapu, pel, pembersih, tisu</td>
                    <td>Rutin Bulanan</td>
                </tr>
                <tr>
                    <td><strong>Maintenance</strong></td>
                    <td>Perbaikan bangunan, renovasi, cat, las, tukang</td>
                    <td>Sesuai Kebutuhan</td>
                </tr>
                <tr>
                    <td><strong>Kesehatan</strong></td>
                    <td>Obat-obatan, vitamin, P3K, biaya dokter/klinik</td>
                    <td>Sesuai Kebutuhan</td>
                </tr>
                <tr>
                    <td><strong>Komunikasi</strong></td>
                    <td>Pulsa telepon, paket data, SMS</td>
                    <td>Rutin Bulanan</td>
                </tr>
                <tr>
                    <td><strong>Administrasi</strong></td>
                    <td>Materai, legalisir, pengurusan surat, dokumen</td>
                    <td>Sesuai Kebutuhan</td>
                </tr>
                <tr>
                    <td><strong>Dana Masuk</strong></td>
                    <td>Semua sumber pemasukan (donasi, setoran, transfer)</td>
                    <td>Sesuai Penerimaan</td>
                </tr>
            </tbody>
        </table>

        <div class="subsection-title" style="margin-top: 30px;">3. Manajemen Risiko</div>
        <p style="margin-bottom: 15px; text-align: justify; line-height: 1.8;">
            <strong>a. Risiko Likuiditas:</strong><br>
            Untuk mengelola risiko likuiditas, Bumi Sultan menjaga saldo kas minimum untuk memastikan operasional 
            berjalan lancar. Monitoring dilakukan secara harian.
        </p>
        <p style="margin-bottom: 15px; text-align: justify; line-height: 1.8;">
            <strong>b. Risiko Operasional:</strong><br>
            Setiap pengeluaran melewati proses verifikasi dan dokumentasi yang jelas untuk meminimalkan 
            kesalahan pencatatan dan penyalahgunaan.
        </p>
        <p style="margin-bottom: 20px; text-align: justify; line-height: 1.8;">
            <strong>c. Pengendalian Internal:</strong><br>
            Sistem digital memastikan setiap transaksi tercatat otomatis dengan timestamp, user, dan bukti 
            transaksi yang dapat diaudit kapan saja.
        </p>

        <div class="subsection-title">4. Komitmen & Kontinjensi</div>
        <div class="summary-box">
            <p style="margin-bottom: 10px;"><strong>Komitmen Rutin:</strong></p>
            <ul style="margin-left: 20px; margin-bottom: 15px;">
                <li>Khidmat Santri: Komitmen harian untuk memenuhi kebutuhan makan santri</li>
                <li>Utilitas: Komitmen bulanan untuk pembayaran listrik, air, dan internet</li>
                <li>Gaji Karyawan: Tidak termasuk dalam dana operasional (dikelola terpisah)</li>
            </ul>
            
            <p style="margin-bottom: 10px;"><strong>Dana Kontinjensi:</strong></p>
            <p style="margin-bottom: 0;">
                Bumi Sultan berupaya menyisihkan surplus untuk dana darurat (emergency fund) guna mengantisipasi 
                kebutuhan mendesak atau perbaikan tidak terduga.
            </p>
        </div>

        <div class="subsection-title">5. Tanggung Jawab Sosial</div>
        <p style="margin-bottom: 20px; text-align: justify; line-height: 1.8;">
            Sebagai lembaga pendidikan, Bumi Sultan berkomitmen untuk mengelola dana dengan penuh amanah, 
            mengutamakan kesejahteraan santri, dan berkontribusi positif bagi masyarakat sekitar.
        </p>

        <div class="subsection-title">6. Informasi Laporan</div>
        <div class="summary-box">
            <div class="summary-row">
                <span>Periode Laporan</span>
                <span>{{ $periode['tanggal_awal']->format('d M Y') }} - {{ $periode['tanggal_akhir']->format('d M Y') }}</span>
            </div>
            <div class="summary-row">
                <span>Total Hari</span>
                <span>{{ $periode['tanggal_awal']->diffInDays($periode['tanggal_akhir']) + 1 }} hari</span>
            </div>
            <div class="summary-row">
                <span>Tanggal Cetak</span>
                <span>{{ $tanggal_cetak }}</span>
            </div>
            <div class="summary-row">
                <span>Dibuat oleh</span>
                <span>Sistem Bumi Sultan (Otomatis)</span>
            </div>
            <div class="summary-row">
                <span>Sifat Laporan</span>
                <span>{{ ucfirst($periode['type']) }} Report</span>
            </div>
        </div>

        <div class="subsection-title" style="margin-top: 30px;">7. Pernyataan Penutup</div>
        <p style="margin-bottom: 20px; text-align: justify; line-height: 1.8;">
            Laporan keuangan ini disusun berdasarkan catatan dan bukti transaksi yang valid dan dapat dipertanggungjawabkan. 
            Manajemen Bumi Sultan menyatakan bahwa informasi yang disajikan dalam laporan ini adalah benar dan akurat 
            sesuai dengan kondisi keuangan periode {{ $periode['nama_periode'] }}.
        </p>

        <div style="margin-top: 40px; padding: 20px; background-color: #f8f9fa; border: 2px solid #1e3c72; border-radius: 5px;">
            <p style="text-align: center; font-weight: bold; font-size: 11pt; margin-bottom: 10px; color: #1e3c72;">
                PERNYATAAN TRANSPARANSI & AKUNTABILITAS
            </p>
            <p style="text-align: center; font-style: italic; font-size: 10pt; line-height: 1.6; color: #666;">
                "Kami berkomitmen untuk mengelola setiap rupiah dengan penuh amanah dan bertanggung jawab. 
                Laporan ini adalah wujud transparansi kami kepada seluruh stakeholder Bumi Sultan."
            </p>
        </div>

        <div style="margin-top: 60px; text-align: center; color: #666; font-size: 9pt;">
            <p style="margin-bottom: 5px; font-weight: bold; font-size: 11pt; color: #1e3c72;">*** AKHIR LAPORAN ***</p>
            <p style="margin-bottom: 5px;">Dokumen ini dibuat secara otomatis oleh Sistem Bumi Sultan</p>
            <p style="margin-bottom: 5px;">dan sah tanpa tanda tangan basah</p>
            <p style="margin-top: 20px; font-size: 8pt;">
                © {{ date('Y') }} Bumi Sultan - Semua transaksi tercatat dan teraudit secara digital
            </p>
        </div>
    </div>

</body>
</html>
