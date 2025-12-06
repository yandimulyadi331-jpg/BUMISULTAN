@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
@php
    $logoPath = public_path('assets/template/img/logo/logobumisultan.png');
    if (file_exists($logoPath)) {
        $logoData = base64_encode(file_get_contents($logoPath));
        $logoSrc = 'data:image/png;base64,' . $logoData;
    } else {
        $logoSrc = config('app.url') . '/assets/template/img/logo/logobumisultan.png';
    }
@endphp
<img src="{{ $logoSrc }}" class="logo" alt="Bumi Sultan" style="max-height: 80px;">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
