<?php

declare(strict_types=1);

namespace Gocanto\Reshape;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;

final class Version
{
    private CarbonImmutable $date;

    private function __construct()
    {
        //
    }

    public static function fromCarbon(CarbonInterface $date): self
    {
        $version = new self();
        $version->date = CarbonImmutable::parse($date)->startOfDay();

        return $version;
    }

    public static function fromDate(string $date): self
    {
        $version = new self();
        $version->date = CarbonImmutable::parse($date)->startOfDay();

        return $version;
    }

    public function getDate(): CarbonImmutable
    {
        return $this->date;
    }

    public function includes(Version $version): bool
    {
        return $version->getDate()->lessThanOrEqualTo($this->date);
    }

    public function toString(): string
    {
        return $this->date->toDateString();
    }
}