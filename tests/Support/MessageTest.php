<?php

declare(strict_types=1);

namespace Gocanto\Reshape\Tests\Support;

use Gocanto\Reshape\Support\ErrorCode;
use Gocanto\Reshape\Support\Message;
use JsonException;
use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase
{
    /**
     * @test
     * @throws JsonException
     */
    public function itHasDefaultValues(): void
    {
        $message = new Message();

        self::assertEmpty($message->getContext());
        self::assertEmpty($message->getMessages());
        self::assertEmpty($message->getFullMessage());
        self::assertSame(ErrorCode::NO_CONTENT, $message->getCode());
        self::assertSame(\json_encode([], JSON_THROW_ON_ERROR), $message->toJson());

        self::assertSame([
            'code' => ErrorCode::NO_CONTENT,
            'context' => [],
            'messages' => [],
        ], $message->toArray());
    }

    /**
     * @test
     * @throws JsonException
     */
    public function itHoldsValidState(): void
    {
        $message = new Message();
        $message->setCode(99);
        $message->addContext('foo', ['bar']);
        $message->addMessage('foo', 'bar');

        self::assertSame(['bar'], $message->getContext('foo'));
        self::assertSame(['foo' => ['bar']], $message->getContext());

        self::assertSame(['foo' => 'Bar'], $message->getMessages());
        self::assertSame('Bar', $message->getFullMessage());

        self::assertSame(99, $message->getCode());

        self::assertSame('{"foo":"Bar"}', $message->toJson());

        self::assertSame([
            'code' => 99,
            'context' => [
                'foo' => [
                    'bar',
                ],
            ],
            'messages' => [
                'foo' => 'Bar',
            ],
        ], $message->toArray());
    }
}