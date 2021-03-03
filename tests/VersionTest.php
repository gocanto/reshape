<?php

declare(strict_types=1);

namespace Gocanto\Reshape\Tests;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Gocanto\Reshape\ReshapeException;
use Gocanto\Reshape\Version;
use PHPUnit\Framework\TestCase;

class VersionTest extends TestCase
{
    private CarbonImmutable $now;

    protected function setUp(): void
    {
        $this->now = CarbonImmutable::create(2021, 03, 03);

        Carbon::setTestNow($this->now);
        CarbonImmutable::setTestNow($this->now);
    }

    /**
     * @test
     */
    public function itCanBeBuiltOutOfCarbonInstances(): void
    {
        $mutable = Carbon::now();
        $version = Version::fromCarbon($mutable);

        self::assertSame($mutable->startOfDay()->toDateString(), $version->getDate()->toDateString());
        self::assertSame($mutable->toDateString(), $version->toString());

        $immutable = CarbonImmutable::now();
        $version = Version::fromCarbon($immutable);

        self::assertSame($immutable->startOfDay()->toDateString(), $version->getDate()->toDateString());
        self::assertSame($immutable->toDateString(), $version->toString());
    }

    /**
     * @test
     * @throws ReshapeException
     */
    public function itCanBeBuiltOutOfDateStrings(): void
    {
        $version = Version::fromDate($this->now->toDateString());

        self::assertSame($this->now->toDateString(), $version->getDate()->toDateString());

        // -- throws exception on invalid dates.
        $this->expectException(ReshapeException::class);
        Version::fromDate('foo');
    }

    /**
     * @test
     * @throws ReshapeException
     */
    public function itOfferAFactoryMethod(): void
    {
        $allowed = [
            Version::make()->toString(),
            Version::make()->toString(),
            Version::make($this->now)->toString(),
            Version::make($this->now->toDateString())->toString(),
        ];

        foreach ($allowed as $item) {
            self::assertSame($this->now->toDateString(), $item);
        }
    }

    /**
     * @test
     */
    public function itProperlyChecksForContainingVersions(): void
    {
        $target = Version::fromCarbon($this->now->subMonth());
        $version = Version::fromCarbon($this->now);

        self::assertTrue($version->includes($target));

        $target = Version::fromCarbon($this->now->addMonth());
        $version = Version::fromCarbon($this->now);

        self::assertFalse($version->includes($target));
    }
}