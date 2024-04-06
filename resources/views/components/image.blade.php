@props([
    'config',
    'path',
    'manipulations' => [],
])
@php
    $url = app(\Dashed\Drift\UrlBuilder::class)->url($config, $path, $manipulations);
    $lazyLoad = app(\Dashed\Drift\DriftManager::class)->configs()->where('name', $config)->first()->forceLazyLoad;
//                    $size = app(\Dashed\Drift\UrlBuilder::class)->size($url);
@endphp
<img
        src="{{ $url }}"
        {{ $attributes }}
        @if($lazyLoad) loading="lazy" @endif
        @if($size ?? false) width="{{ $size['width'] }}" height="{{ $size['height'] }}" @endif
>
