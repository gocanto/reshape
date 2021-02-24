<?php

declare(strict_types=1);

namespace Gocanto\Reshape;

use Gocanto\Reshape\Contract\Pipe;

class Pipeline
{
    private array $pipes = [];

    public function add(array $pipes): void
    {
        foreach ($pipes as $pipe) {
            $this->push($pipe);
        }
    }

    public function push(Pipe $pipe) : void
    {
        if (\array_key_exists($pipe->getKey(), $this->pipes)) {
            throw new ReshapeException("The given pipe {$pipe->getKey()} already exists.");
        }

        $this->pipes[$pipe->getKey()] = $pipe;
    }

    /**
     * @return Pipe[]
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