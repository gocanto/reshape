<?php

declare(strict_types=1);

namespace Gocanto\Reshape;

use Gocanto\Reshape\Contract\Pipe;
use JetBrains\PhpStorm\Pure;

class Pipeline
{
    private array $pipes = [];

    /**
     * @throws ReshapeException
     */
    public function add(array $pipes): void
    {
        foreach ($pipes as $pipe) {
            $this->push($pipe);
        }
    }

    /**
     * @throws ReshapeException
     */
    public function push(Pipe $pipe) : void
    {
        if (\array_key_exists($pipe->getKey(), $this->pipes)) {
            throw ReshapeException::make("The given pipe {$pipe->getKey()} already exists.");
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

    #[Pure]
    public function isNotEmpty() : bool
    {
        return !$this->isEmpty();
    }

    #[Pure]
    public function isEmpty() : bool
    {
        return count($this->pipes) === 0;
    }
}