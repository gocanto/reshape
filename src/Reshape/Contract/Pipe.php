<?php

declare(strict_types=1);

namespace Gocanto\Reshape\Contract;

use Gocanto\Reshape\Version;

interface Pipe
{
    public function transform(array $item) : array;

    public function getVersion() : Version;

    public function getKey(): string;
}