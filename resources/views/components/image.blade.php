@props([
    'config',
    'path',
    'manipulations' => [],
])
@php
    $url = app(\Dashed\Drift\UrlBuilder::class)->url($config, $path, $manipulations);
                    $size = app(\Dashed\Drift\UrlBuilder::class)->size($url);
@endphp
<img
        src="{{ $url }}"
        {{ $attributes }}
        @if($size) width="{{ $size['width'] }}"   height="{{ $size['height'] }}" @endif
>
