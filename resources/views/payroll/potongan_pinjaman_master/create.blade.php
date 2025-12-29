<form action="{{ route('potongan_pinjaman_master.store') }}" method="POST" id="formPotongan">
    @csrf
    <div class="row">
        <div class="col-12">
            <div class="form-group mb-3">
                <label class="form-label">Karyawan <span class="text-danger">*</span></label>
                <select name="nik" id="nik" class="form-select select2" required>
                    <option value="">-- Pilih Karyawan --</option>
                    @foreach($karyawan as $k)
                        <option value="{{ $k->nik }}">
                            {{ $k->nik_show ?? $k->nik }} - {{ $k->nama_karyawan }} 
                            ({{ $k->kode_dept }} - {{ $k->kode_cabang }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="form-group mb-3">
                <label class="form-label">Referensi Pinjaman (Opsional)</label>
                <select name="pinjaman_id" id="pinjaman_id" class="form-select select2">
                    <option value="">-- Pilih Pinjaman (Opsional) --</option>
                    @foreach($pinjaman as $p)
                        <option value="{{ $p->id }}" data-nik="{{ $p->karyawan_id }}" 
                            data-sisa="{{ $p->sisa_pinjaman }}" data-cicilan="{{ $p->cicilan_per_bulan }}">
                            {{ $p->nomor_pinjaman }} - {{ $p->karyawan->nama_karyawan ?? 'N/A' }} 
                            (Sisa: Rp {{ number_format($p->sisa_pinjaman, 0, ',', '.') }})
                        </option>
                    @endforeach
                </select>
                <small class="text-muted">Pilih jika potongan ini terkait dengan pinjaman yang sudah ada</small>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <x-input-with-icon label="Total Pinjaman" name="jumlah_pinjaman" icon="fa fa-money-bill" 
                placeholder="5000000" required="true" align="right" money="true" />
        </div>
        <div class="col-md-6">
            <x-input-with-icon label="Cicilan per Bulan" name="cicilan_per_bulan" icon="fa fa-money-bill" 
                placeholder="1000000" required="true" align="right" money="true" />
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-info">
                <i class="fa fa-info-circle me-2"></i>
                <strong>Jumlah Bulan:</strong> <span id="jumlahBulanText">-</span> bulan
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label class="form-label">Bulan Mulai <span class="text-danger">*</span></label>
                <select name="bulan_mulai" id="bulan_mulai" class="form-select" required>
                    @foreach($nama_bulan as $index => $nama)
                        @if($index > 0)
                            <option value="{{ $index }}" {{ date('n') == $index ? 'selected' : '' }}>
                                {{ $nama }}
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label class="form-label">Tahun Mulai <span class="text-danger">*</span></label>
                <select name="tahun_mulai" id="tahun_mulai" class="form-select" required>
                    @for($y = date('Y'); $y >= $start_year; $y--)
                        <option value="{{ $y }}" {{ date('Y') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group mb-3">
                <label class="form-label" for="tanggal_potongan">Tanggal Potongan per Bulan <span class="text-danger">*</span></label>
                <input type="number" class="form-control" name="tanggal_potongan" id="tanggal_potongan" 
                       placeholder="Contoh: 25" min="1" max="31" value="25" required>
                <small class="text-muted">Tanggal jatuh tempo potongan setiap bulannya (1-31). Contoh: 25 = akan dipotong tanggal 25 setiap bulan</small>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-success">
                <i class="fa fa-calendar me-2"></i>
                <strong>Periode Selesai:</strong> <span id="periodeSelesaiText">-</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="form-group mb-3">
                <label class="form-label">Keterangan</label>
                <textarea name="keterangan" class="form-control" rows="3" 
                    placeholder="Keterangan tambahan..."></textarea>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-save me-2"></i> Simpan
            </button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                Batal
            </button>
        </div>
    </div>
</form>

<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2({
        dropdownParent: $('#modal'),
        width: '100%'
    });

    // Auto-fill dari pinjaman
    $('#pinjaman_id').change(function() {
        const selected = $(this).find('option:selected');
        if (selected.val()) {
            const nik = selected.data('nik');
            const sisa = selected.data('sisa');
            const cicilan = selected.data('cicilan');

            $('#nik').val(nik).trigger('change');
            $('input[name="jumlah_pinjaman"]').val(formatRupiah(sisa));
            $('input[name="cicilan_per_bulan"]').val(formatRupiah(cicilan));
            
            calculateJumlahBulan();
        }
    });

    // Calculate jumlah bulan
    function calculateJumlahBulan() {
        const jumlahPinjaman = toNumber($('input[name="jumlah_pinjaman"]').val());
        const cicilanPerBulan = toNumber($('input[name="cicilan_per_bulan"]').val());

        if (jumlahPinjaman > 0 && cicilanPerBulan > 0) {
            const jumlahBulan = Math.ceil(jumlahPinjaman / cicilanPerBulan);
            $('#jumlahBulanText').text(jumlahBulan);
            calculatePeriodeSelesai(jumlahBulan);
        } else {
            $('#jumlahBulanText').text('-');
            $('#periodeSelesaiText').text('-');
        }
    }

    // Calculate periode selesai
    function calculatePeriodeSelesai(jumlahBulan) {
        const bulanMulai = parseInt($('#bulan_mulai').val());
        const tahunMulai = parseInt($('#tahun_mulai').val());

        if (bulanMulai && tahunMulai && jumlahBulan > 0) {
            const startDate = new Date(tahunMulai, bulanMulai - 1, 1);
            const endDate = new Date(startDate);
            endDate.setMonth(startDate.getMonth() + jumlahBulan - 1);

            const namaBulan = {!! json_encode($nama_bulan) !!};
            const bulanSelesai = endDate.getMonth() + 1;
            const tahunSelesai = endDate.getFullYear();

            $('#periodeSelesaiText').text(namaBulan[bulanSelesai] + ' ' + tahunSelesai);
        }
    }

    // Event listeners
    $('input[name="jumlah_pinjaman"], input[name="cicilan_per_bulan"]').on('input', calculateJumlahBulan);
    $('#bulan_mulai, #tahun_mulai').change(function() {
        const jumlahPinjaman = toNumber($('input[name="jumlah_pinjaman"]').val());
        const cicilanPerBulan = toNumber($('input[name="cicilan_per_bulan"]').val());
        if (jumlahPinjaman > 0 && cicilanPerBulan > 0) {
            const jumlahBulan = Math.ceil(jumlahPinjaman / cicilanPerBulan);
            calculatePeriodeSelesai(jumlahBulan);
        }
    });

    // Helper functions
    function toNumber(str) {
        return parseFloat(str.replace(/[^0-9.-]+/g, '')) || 0;
    }

    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID').format(angka);
    }

    // Initial calculation
    calculateJumlahBulan();
});
</script>
