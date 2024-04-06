<?php

namespace Dashed\Drift\Http\Controllers;

use Dashed\Drift\DriftManager;
use Dashed\Drift\ManipulationsTransformer;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ImagesController
{
    public function __construct(
        public DriftManager             $driftManager,
        public ManipulationsTransformer $manipulationsTransformer,
    )
    {
    }

    public function __invoke(
        string $configName,
        string $manipulations,
        string $path,
    ): Response
    {
        $signature = request('signature');

        /** @var \Dashed\Drift\Config|null $config */
        $config = $this->driftManager
            ->configs()
            ->firstWhere('name', $configName);

        abort_if(
            is_null($config),
            Response::HTTP_NOT_FOUND,
            'Config not found',
        );

        /** @var \Dashed\Drift\Contracts\CachingStrategy $cachingStrategy */
        $cachingStrategy = new $config->cachingStrategy();

        if ($cachingStrategy->validate($path, $signature, $config)) {
            $cachedImage = $cachingStrategy->resolve($path, $signature, $config);

            if (str($path)->lower()->endsWith(['.png', '.jpg', '.jpeg', '.webp']) && !in_array('keepOriginal', array_values($this->manipulationsTransformer->decode($manipulations)))) {
                $image = Image::make($cachedImage);

                $image->encode((string)str($image->mime())->afterLast('/'));
                $mime = $image->mime();
            } else {
                $image = $cachedImage;
            }

            return response((string)$image)->header('Content-Type', $mime ?? Storage::disk($config->filesystemDisk)->mimeType($path));
        }

        abort_unless(
            Storage::disk($config->filesystemDisk)->exists($path),
            Response::HTTP_NOT_FOUND,
            'Image not found',
        );

        $image = Storage::disk($config->filesystemDisk)->get($path);
        if (str($path)->lower()->endsWith(['.png', '.jpg', '.jpeg', '.webp']) && !in_array('keepOriginal', array_values($this->manipulationsTransformer->decode($manipulations)))) {
            $image = Image::make(
                $image,
            );
            $mime = $image->mime();

            foreach ($this->manipulationsTransformer->decode($manipulations) as $method => $arguments) {
                is_array($arguments)
                    ? $image->{$method}(...$arguments)
                    : $image->{$method}($arguments);
            }
        }

        $cachingStrategy->cache($path, $signature, $image, $config);

        return response((string)$image)->header('Content-Type', $mime ?? Storage::disk($config->filesystemDisk)->mimeType($path));
    }
}
