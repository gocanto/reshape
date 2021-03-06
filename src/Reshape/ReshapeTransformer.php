<?php

declare(strict_types=1);

namespace Gocanto\Reshape;

use Gocanto\Reshape\Contract\Entry;
use Gocanto\Reshape\Contract\Pipe;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\Pure;

abstract class ReshapeTransformer
{
    public Version $version;

    /**
     * @throws ReshapeException
     */
    public function __construct(string | Version $version)
    {
        $this->version = Version::make($version);
    }

    abstract protected function getBaseData(mixed $item) : array;

    public function transform(mixed $item) : array
    {
        $pipeline = $this->getPipeline();

        $data = $this->getBaseData($item);

        if ($pipeline->isEmpty()) {
            return $data;
        }

        foreach ($pipeline->get() as $pipe) {
            if ($this->shouldRunPipe($pipe)) {
                $data = $pipe->transform($data);
            }
        }

        return $data;
    }

    protected function shouldRunPipe(Pipe $pipe) : bool
    {
        return $this->version->includes($pipe->getVersion());
    }

    #[Pure]
    protected function getPipeline() : Pipeline
    {
        return new Pipeline;
    }

    public function transformCollection(iterable $items) : array
    {
        if (empty($items) || ($items instanceof Collection && $items->isEmpty())) {
            return [];
        }

        $data = [];

        foreach ($items as $item) {
            $data[] = $this->transform($item);
        }

        return $data;
    }

    public function transformEntry(?Entry $item) : ?array
    {
        if ($item === null) {
            return null;
        }

        return $this->transform($item);
    }
}