<?php

declare(strict_types=1);

namespace Gocanto\Reshape;

use Exception;
use Gocanto\Reshape\Support\Message;
use Throwable;

class ReshapeException extends Exception
{
    private ?Message $failure = null;

    public static function fromMessage(Message $message, Throwable $previous = null): self
    {
        $e = new self($message->getFullMessage(), $message->getCode(), $previous);
        $e->failure = $message;

        return $e;
    }

    public static function fromThrowable(Throwable $e, ?string $message = null): self
    {
        return self::make($message ?? $e->getMessage(), $e->getCode(), $e);
    }

    public static function make(string $message = '', int $code = 0, Throwable $previous = null): self
    {
        $e = new self($message, $code, $previous);
        $e->failure = $e->buildFailure($message, $code);

        return $e;
    }

    public function getFailure(): Message
    {
        return $this->failure
            ?? $this->buildFailure($this->getMessage(), $this->getCode());
    }

    private function buildFailure(string $message, ?int $code = null): Message
    {
        $failure = new Message();
        $failure->addMessage(__CLASS__, $message);

        if ($code !== null) {
            $failure->setCode($code);
        }

        return $failure;
    }
}