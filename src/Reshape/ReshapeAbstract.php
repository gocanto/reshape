<?php

declare(strict_types=1);

namespace Gocanto\Reshape;

use Gocanto\Reshape\Contracts\ModelInterface;
use Gocanto\Reshape\Contracts\PipeInterface;
use Illuminate\Support\Collection;

abstract class ReshapeAbstract
{
    public Version $version;

    public function __construct(string | Version $version)
    {
        $this->version = \is_string($version) ? Version::fromDate($version) : $version;
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

    private function shouldRunPipe(PipeInterface $pipe) : bool
    {
        return $this->version->includes($pipe->getVersion());
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

    public function transformModel(?ModelInterface $item) : ?array
    {
        if ($item === null) {
            return null;
        }

        return $this->transform($item);
    }

    protected function getPipeline() : Pipeline
    {
        return new Pipeline;
    }
}