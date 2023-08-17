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
        //get default disk from config
        return Storage::disk($config->filesystemDisk)->exists("__media-cache/{$path}/{$signature}");
    }

    public function resolve(string $path, string $signature, Config $config): string
    {
        return Storage::disk($config->filesystemDisk)->get("__media-cache/{$path}/{$signature}");
    }

    public function cache(string $path, string $signature, string|Image $image, Config $config): void
    {
        //Todo: put the width and height in the cache
        Storage::disk($config->filesystemDisk)->put("__media-cache/{$path}/{$signature}", (string) $image);
    }
}
