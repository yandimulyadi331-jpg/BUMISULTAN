@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between mb-4">
        <h2><i class="bi bi-calendar3"></i> Kalender Agenda</h2>
        <a href="{{ route('agenda.index') }}" class="btn btn-secondary">Kembali ke List</a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="text-center mb-4">
                <h4>{{ date('F Y', mktime(0, 0, 0, $bulan, 1, $tahun)) }}</h4>
                <div class="btn-group">
                    <a href="?bulan={{ $bulan-1 }}&tahun={{ $tahun }}" class="btn btn-sm btn-outline-primary">← Prev</a>
                    <a href="?bulan={{ date('m') }}&tahun={{ date('Y') }}" class="btn btn-sm btn-primary">Today</a>
                    <a href="?bulan={{ $bulan+1 }}&tahun={{ $tahun }}" class="btn btn-sm btn-outline-primary">Next →</a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr class="text-center">
                            <th>Min</th><th>Sen</th><th>Sel</th><th>Rab</th><th>Kam</th><th>Jum</th><th>Sab</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $firstDay = date('w', mktime(0, 0, 0, $bulan, 1, $tahun));
                            $daysInMonth = date('t', mktime(0, 0, 0, $bulan, 1, $tahun));
                            $day = 1;
                        @endphp
                        @for($week = 0; $week < 6; $week++)
                            <tr>
                                @for($dow = 0; $dow < 7; $dow++)
                                    <td style="height: 100px; vertical-align: top;">
                                        @if(($week == 0 && $dow >= $firstDay) || ($week > 0 && $day <= $daysInMonth))
                                            @if($day <= $daysInMonth)
                                                <div class="fw-bold">{{ $day }}</div>
                                                @php
                                                    $currentDate = date('Y-m-d', mktime(0, 0, 0, $bulan, $day, $tahun));
                                                    $agendaHariIni = $agendaBulanIni->filter(function($a) use ($currentDate) {
                                                        return $a->tanggal_mulai->format('Y-m-d') == $currentDate;
                                                    });
                                                @endphp
                                                @foreach($agendaHariIni as $item)
                                                    <div class="mt-1">
                                                        <a href="{{ route('agenda.show', $item) }}" class="badge bg-{{ $item->prioritas_badge }}" style="font-size: 10px; text-decoration: none;">
                                                            {{ substr($item->waktu_mulai, 0, 5) }} {{ Str::limit($item->judul, 15) }}
                                                        </a>
                                                    </div>
                                                @endforeach
                                                @php $day++; @endphp
                                            @endif
                                        @endif
                                    </td>
                                @endfor
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
