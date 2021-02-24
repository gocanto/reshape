<?php

declare(strict_types=1);

namespace Gocanto\Reshape\Contracts;

use Gocanto\Reshape\Version;

interface PipeInterface
{
    public function transform(array $item) : array;

    public function getVersion() : Version;
}