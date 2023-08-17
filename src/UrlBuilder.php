<?php

namespace Dashed\Drift;

use Illuminate\Support\Facades\URL;

class UrlBuilder
{
    public function __construct(
        public ManipulationsTransformer $manipulationsTransformer,
    ) {
    }

    public function url(string $configName, string $path, array $manipulations = []): string
    {
        if (! isset($manipulations['encode'])) {
            $manipulations['encode'] = 'webp';
        }

        $encodedManipulations = $this->manipulationsTransformer->encode($manipulations);

        return URL::signedRoute('__media.manipulate', [
            $configName,
            $encodedManipulations,
            $path,
        ]);
    }
}
