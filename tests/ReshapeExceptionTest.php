<?php

declare(strict_types=1);

namespace Gocanto\Reshape\Tests;

use Exception;
use Gocanto\Reshape\ReshapeException;
use Gocanto\Reshape\Support\Message;
use PHPUnit\Framework\TestCase;

class ReshapeExceptionTest extends TestCase
{
    private Exception $previous;

    protected function setUp(): void
    {
        parent::setUp();

        $this->previous = new Exception('gus');
    }

    /**
     * @test
     */
    public function itCanBeBuiltOutOfMessages(): void
    {
        $message = new Message();
        $exception = ReshapeException::fromMessage($message, $this->previous);

        self::assertSame($message, $exception->getFailure());
        self::assertSame($message->getCode(), $exception->getCode());
        self::assertSame($message->getFullMessage(), $exception->getMessage());
        self::assertSame($this->previous, $exception->getPrevious());
    }

    /**
     * @test
     */
    public function itCanBeBuiltOutOfThrowable(): void
    {
        $exception = ReshapeException::fromThrowable($this->previous);

        $message = $exception->getFailure();
        self::assertSame($message->getCode(), $exception->getCode());
        self::assertSame($message->getFullMessage(), \ucwords($exception->getMessage()));
        self::assertSame($this->previous, $exception->getPrevious());
    }

    /**
     * @test
     */
    public function itCanBeMadeFromData(): void
    {
        $exception = ReshapeException::make('gus', 99, $this->previous);

        $message = $exception->getFailure();
        self::assertSame($message->getCode(), $exception->getCode());
        self::assertSame($message->getFullMessage(), \ucwords($exception->getMessage()));
        self::assertSame($this->previous, $exception->getPrevious());
    }
}