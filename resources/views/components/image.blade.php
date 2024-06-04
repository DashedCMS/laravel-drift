@props([
    'config',
    'path',
    'manipulations' => [],
    'alt' => '',
    'loading' => Customsetting::get('image_force_lazy_load', null, false) ? 'lazy' : 'eager',
    'mediaId',
    'conversion' => 'medium',
    'class' => ''
])
@if(($path->id ?? false) || (int)$path == $path)
    <x-dashed-files::image
            class="{{ $class }}"
            loading="{{ $loading }}"
            :mediaId="$path->id ?? $path"
            conversion="{{ $conversion }}"
    />
@else
    @php
        $url = app(\Dashed\Drift\UrlBuilder::class)->url($config, $path, $manipulations);
        $lazyLoad = app(\Dashed\Drift\DriftManager::class)->configs()->where('name', $config)->first()->forceLazyLoad;
        $showSizes = app(\Dashed\Drift\DriftManager::class)->configs()->where('name', $config)->first()->showSizes;
        if($showSizes){
            $size = app(\Dashed\Drift\UrlBuilder::class)->size($url);
        }
    @endphp
    <img
            class="{{ $class }}"
            alt="{{ $alt }}"
            src="{{ $url }}"
            {{ $attributes }}
            @if($lazyLoad && !array_key_exists('loading', collect($attributes)->toArray())) loading="lazy" @endif
            @if($size ?? false) width="{{ $size['width'] }}" height="{{ $size['height'] }}" @endif
    >
@endif