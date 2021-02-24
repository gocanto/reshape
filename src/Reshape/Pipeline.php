<?php

declare(strict_types=1);

namespace Gocanto\Reshape;

use Gocanto\Reshape\Contracts\PipeInterface;

class Pipeline
{
    private array $pipes = [];

    public function addPipe(PipeInterface $pipe) : void
    {
        $this->pipes[] = $pipe;
    }

    /**
     * @return PipeInterface[]
     */
    public function get(): array
    {
        return $this->pipes;
    }

    public function isNotEmpty() : bool
    {
        return !$this->isEmpty();
    }

    public function isEmpty() : bool
    {
        return count($this->pipes) === 0;
    }
}