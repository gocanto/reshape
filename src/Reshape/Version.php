<?php

declare(strict_types=1);

namespace Gocanto\Reshape;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Carbon\Exceptions\InvalidFormatException;
use JetBrains\PhpStorm\Pure;

final class Version
{
    private CarbonImmutable $date;

    private function __construct()
    {
        //
    }

    /**
     * @throws ReshapeException
     */
    public static function make(mixed $needle = null): self
    {
        if ($needle instanceof self) {
            return $needle;
        }

        if ($needle instanceof CarbonInterface) {
            return self::fromCarbon($needle);
        }

        if (\is_string($needle)) {
            return self::fromDate($needle);
        }

        return self::fromCarbon(CarbonImmutable::now());
    }

    public static function fromCarbon(CarbonInterface $date): self
    {
        $version = new self();
        $version->date = CarbonImmutable::parse($date)->startOfDay();

        return $version;
    }

    /**
     * @throws ReshapeException
     */
    public static function fromDate(string $date): self
    {
        $version = new self();

        try {
            $version->date = CarbonImmutable::parse($date)->startOfDay();
        } catch (InvalidFormatException $exception) {
            throw ReshapeException::fromThrowable($exception, 'The given date is invalid.');
        }

        return $version;
    }

    public function getDate(): CarbonImmutable
    {
        return $this->date;
    }

    #[Pure]
    public function includes(Version $version): bool
    {
        return $version->getDate()->lessThanOrEqualTo($this->date);
    }

    public function toString(): string
    {
        return $this->date->toDateString();
    }
}