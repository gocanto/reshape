<?php

declare(strict_types=1);

namespace Gocanto\Reshape\Tests\Support;

use Gocanto\Reshape\Support\ErrorCode;
use PHPUnit\Framework\TestCase;

class ErrorCodeTest extends TestCase
{
    /**
     * @test
     */
    public function itHasValidErrorCodes(): void
    {
        self::assertSame(500, ErrorCode::INTERNAL_SERVER_ERROR);
        self::assertSame(404, ErrorCode::HTTP_NOT_FOUND);
        self::assertSame(204, ErrorCode::NO_CONTENT);
        self::assertSame(403, ErrorCode::FORBIDDEN);
    }
}