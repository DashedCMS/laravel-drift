<?php

namespace Dashed\Drift;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;
use Intervention\Image\Facades\Image;

class UrlBuilder
{
    public function __construct(
        public ManipulationsTransformer $manipulationsTransformer,
    )
    {
    }

    public function url(string $configName, string $path, array $manipulations = []): string
    {
        if (!isset($manipulations['encode'])) {
            $manipulations['encode'] = 'webp';
        }

        $encodedManipulations = $this->manipulationsTransformer->encode($manipulations);

        return URL::signedRoute('__media.manipulate', [
            $configName,
            $encodedManipulations,
            $path,
        ]);
    }

    public function size(string $url): array
    {
        return Cache::rememberForever("dashed::url-builder::size::{$url}", function () use ($url) {
            if (str($url)->lower()->contains(['.png', '.jpg', '.jpeg', '.webp'])){
                $image = Image::make(
                    $url,
                );

                return [
                    'width' => $image->width(),
                    'height' => $image->height(),
                ];
            }

            return [];
        });
    }
}
