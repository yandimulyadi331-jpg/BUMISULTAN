<form action="{{ route('slipgaji.sendEmailSelected') }}" method="POST" id="formKirimEmail">
    @csrf
    <div class="row">
        <div class="col-12">
            <div class="form-group mb-3">
                <label class="form-label">Bulan <span class="text-danger">*</span></label>
                <select name="bulan" id="bulan" class="form-select" required>
                    <option value="">-- Pilih Bulan --</option>
                    <option value="01">Januari</option>
                    <option value="02">Februari</option>
                    <option value="03">Maret</option>
                    <option value="04">April</option>
                    <option value="05">Mei</option>
                    <option value="06">Juni</option>
                    <option value="07">Juli</option>
                    <option value="08">Agustus</option>
                    <option value="09">September</option>
                    <option value="10">Oktober</option>
                    <option value="11">November</option>
                    <option value="12">Desember</option>
                </select>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="form-group mb-3">
                <label class="form-label">Tahun <span class="text-danger">*</span></label>
                <select name="tahun" id="tahun" class="form-select" required>
                    <option value="">-- Pilih Tahun --</option>
                    @for ($t = $start_year; $t <= date('Y'); $t++)
                        <option value="{{ $t }}" {{ date('Y') == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endfor
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="alert alert-info">
                <i class="fa fa-info-circle me-2"></i>
                <strong>Pilih Karyawan</strong><br>
                Centang karyawan yang akan menerima slip gaji via email
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="form-group mb-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-search"></i></span>
                    <input type="text" id="searchKaryawan" class="form-control" placeholder="Cari nama karyawan...">
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="form-check mb-3" style="background: #f8f9fa; padding: 12px; border-radius: 8px; border: 2px solid #28a745;">
                <input class="form-check-input" type="checkbox" id="checkAll" style="width: 20px; height: 20px; cursor: pointer;">
                <label class="form-check-label ms-2" for="checkAll" style="font-weight: 600; font-size: 1.05rem; cursor: pointer;">
                    <i class="fa fa-check-square text-success me-1"></i> Pilih Semua Karyawan
                </label>
                <small class="d-block text-muted ms-4 mt-1">
                    Centang untuk memilih seluruh karyawan yang memiliki email
                </small>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="form-group mb-3">
                <label class="form-label">
                    Daftar Karyawan 
                    <span class="badge bg-primary" id="countSelected">0 dipilih</span>
                    <span class="badge bg-secondary" id="countTotal">{{ $karyawan->count() }} total</span>
                </label>
                <div style="max-height: 400px; overflow-y: auto; border: 1px solid #dee2e6; border-radius: 8px; padding: 10px; background: #fff;">
                    @if($karyawan->count() > 0)
                        <div id="karyawanList">
                            @foreach($karyawan as $k)
                                <div class="form-check mb-2 p-2 karyawan-item" style="border-bottom: 1px solid #f0f0f0; transition: background 0.2s;" data-nama="{{ strtolower($k->nama_karyawan) }}">
                                    <input class="form-check-input karyawan-check" type="checkbox" name="nik[]" value="{{ $k->nik }}" id="karyawan_{{ $k->nik }}" style="width: 18px; height: 18px; cursor: pointer;">
                                    <label class="form-check-label ms-2" for="karyawan_{{ $k->nik }}" style="cursor: pointer; width: 100%;">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong>{{ $k->nama_karyawan }}</strong>
                                                <br>
                                                <small class="text-muted">
                                                    <i class="fa fa-id-card me-1"></i>{{ $k->nik }} | 
                                                    <i class="fa fa-envelope me-1"></i>{{ $k->email }}
                                                </small>
                                                @if($k->departemen)
                                                    <br>
                                                    <small class="text-muted">
                                                        <i class="fa fa-building me-1"></i>{{ $k->departemen->nama_dept }}
                                                    </small>
                                                @endif
                                            </div>
                                            <i class="fa fa-check-circle text-success" style="font-size: 1.2rem; display: none;"></i>
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-warning mb-0">
                            <i class="fa fa-exclamation-triangle me-2"></i>
                            Tidak ada karyawan dengan email yang terdaftar
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="form-group">
                <button type="submit" class="btn btn-success w-100" id="btnSubmit" {{ $karyawan->count() == 0 ? 'disabled' : '' }}>
                    <i class="fa fa-envelope me-2"></i> Kirim Email Slip Gaji
                </button>
            </div>
        </div>
    </div>
</form>

<script>
$(document).ready(function() {
    // Check All functionality
    $('#checkAll').on('change', function() {
        var isChecked = $(this).is(':checked');
        $('.karyawan-check:visible').prop('checked', isChecked);
        updateCheckboxStyle();
        updateSelectedCount();
    });

    // Individual checkbox change
    $('.karyawan-check').on('change', function() {
        updateCheckboxStyle();
        updateSelectedCount();
        
        // Update checkAll status
        var totalVisible = $('.karyawan-check:visible').length;
        var totalChecked = $('.karyawan-check:visible:checked').length;
        $('#checkAll').prop('checked', totalVisible === totalChecked && totalVisible > 0);
    });

    // Search functionality
    $('#searchKaryawan').on('keyup', function() {
        var searchText = $(this).val().toLowerCase();
        
        $('.karyawan-item').each(function() {
            var nama = $(this).data('nama');
            if (nama.includes(searchText)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
        
        updateSelectedCount();
    });

    // Update checkbox visual style
    function updateCheckboxStyle() {
        $('.karyawan-item').each(function() {
            var checkbox = $(this).find('.karyawan-check');
            var checkIcon = $(this).find('.fa-check-circle');
            
            if (checkbox.is(':checked')) {
                $(this).css('background', '#f0f9ff');
                checkIcon.show();
            } else {
                $(this).css('background', 'transparent');
                checkIcon.hide();
            }
        });
    }

    // Update selected count
    function updateSelectedCount() {
        var total = $('.karyawan-check').length;
        var selected = $('.karyawan-check:checked').length;
        
        $('#countSelected').text(selected + ' dipilih');
        $('#countTotal').text(total + ' total');
        
        // Enable/disable submit button
        if (selected > 0) {
            $('#btnSubmit').prop('disabled', false);
        } else {
            $('#btnSubmit').prop('disabled', true);
        }
    }

    // Form submit validation
    $('#formKirimEmail').on('submit', function(e) {
        e.preventDefault();
        
        var bulan = $('#bulan').val();
        var tahun = $('#tahun').val();
        var selected = $('.karyawan-check:checked').length;
        
        if (!bulan || !tahun) {
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian',
                text: 'Silakan pilih bulan dan tahun terlebih dahulu',
                confirmButtonColor: '#3085d6'
            });
            return false;
        }
        
        if (selected === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian',
                text: 'Silakan pilih minimal 1 karyawan',
                confirmButtonColor: '#3085d6'
            });
            return false;
        }
        
        Swal.fire({
            title: 'Konfirmasi Pengiriman',
            html: `Kirim slip gaji ke <strong>${selected}</strong> karyawan untuk periode <strong>${getNamaBulan(bulan)} ${tahun}</strong>?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fa fa-envelope"></i> Ya, Kirim Email',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Mengirim Email...',
                    html: 'Mohon tunggu, sistem sedang mengirim slip gaji ke email karyawan',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Submit form via AJAX
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message || 'Slip gaji berhasil dikirim',
                            confirmButtonColor: '#28a745'
                        }).then(() => {
                            $('#modal').modal('hide');
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: xhr.responseJSON?.message || 'Terjadi kesalahan saat mengirim email',
                            confirmButtonColor: '#dc3545'
                        });
                    }
                });
            }
        });
    });
    
    function getNamaBulan(bulan) {
        const namaBulan = {
            '01': 'Januari', '02': 'Februari', '03': 'Maret',
            '04': 'April', '05': 'Mei', '06': 'Juni',
            '07': 'Juli', '08': 'Agustus', '09': 'September',
            '10': 'Oktober', '11': 'November', '12': 'Desember'
        };
        return namaBulan[bulan] || bulan;
    }
    
    // Hover effect
    $('.karyawan-item').hover(
        function() {
            if (!$(this).find('.karyawan-check').is(':checked')) {
                $(this).css('background', '#f8f9fa');
            }
        },
        function() {
            if (!$(this).find('.karyawan-check').is(':checked')) {
                $(this).css('background', 'transparent');
            }
        }
    );
    
    // Initialize count
    updateSelectedCount();
});
</script>

<style>
.karyawan-item:hover {
    cursor: pointer;
}
</style>
