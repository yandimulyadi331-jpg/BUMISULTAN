@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Laporan Presensi Yayasan</h3>
                    </div>
                    <form method="POST" action="{{ route('yayasan-laporan.cetakpresensi') }}" target="_blank">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="from">Dari Tanggal</label>
                                        <input type="date" class="form-control @error('from') is-invalid @enderror" 
                                               id="from" name="from" value="{{ old('from', date('Y-m-d')) }}" required>
                                        @error('from')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="to">Sampai Tanggal</label>
                                        <input type="date" class="form-control @error('to') is-invalid @enderror" 
                                               id="to" name="to" value="{{ old('to', date('Y-m-d')) }}" required>
                                        @error('to')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="cabang">Cabang</label>
                                        <select class="form-control @error('cabang') is-invalid @enderror" 
                                                id="cabang" name="cabang" required>
                                            <option value="all">Semua Cabang</option>
                                            @foreach ($cabang as $cb)
                                                <option value="{{ $cb->kode_cabang }}" 
                                                    {{ old('cabang') == $cb->kode_cabang ? 'selected' : '' }}>
                                                    {{ $cb->nama_cabang }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('cabang')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="format">Format</label>
                                        <select class="form-control @error('format') is-invalid @enderror" 
                                                id="format" name="format" required>
                                            <option value="pdf" {{ old('format', 'pdf') == 'pdf' ? 'selected' : '' }}>PDF</option>
                                            <option value="excel" {{ old('format') == 'excel' ? 'selected' : '' }}>Excel</option>
                                        </select>
                                        @error('format')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-download"></i> Cetak Laporan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
