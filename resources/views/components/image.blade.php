@props([
    'config',
    'path',
    'manipulations' => [],
])

<img
    src="{{ app(\Dashed\Drift\UrlBuilder::class)->url($config, $path, $manipulations) }}"
    {{ $attributes }}
>
