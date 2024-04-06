<?php

namespace Dashed\Drift;

class Config
{
    public function __construct(
        public string $name,
        public string $filesystemDisk,
        public string $cachingStrategy,
        public bool $forceLazyLoad = false
    ) {
    }
}
