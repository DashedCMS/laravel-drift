<?php

namespace Dashed\Drift\CachingStrategies;

use Dashed\Drift\Config;
use Dashed\Drift\Contracts\CachingStrategy;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Image;

class FilesystemCachingStrategy implements CachingStrategy
{
    public function validate(string $path, string $signature, Config $config): bool
    {
        return Storage::exists("__media-cache/{$path}/{$signature}");
    }

    public function resolve(string $path, string $signature, Config $config): string
    {
        return Storage::get("__media-cache/{$path}/{$signature}");
    }

    public function cache(string $path, string $signature, string|Image $image, Config $config): void
    {
        //Todo: put the width and height in the cache
        Storage::put("__media-cache/{$path}/{$signature}", (string) $image);
    }
}
