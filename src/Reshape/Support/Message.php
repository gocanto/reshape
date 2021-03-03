<?php

declare(strict_types=1);

namespace Gocanto\Reshape\Support;

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use JsonException;

final class Message
{
    private int $code = ErrorCode::NO_CONTENT;

    private array $context = [];
    private array $messages = [];

    #[Pure]
    public static function error(): self
    {
        $message = new self();
        $message->code = ErrorCode::FORBIDDEN;

        return $message;
    }

    #[Pure]
    public static function default(): self
    {
        return new self();
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function setCode(int $code): void
    {
        $this->code = $code;
    }

    public function getContext(string $key = null): array
    {
        return $key === null ? $this->context : $this->context[$key];
    }

    public function addContext(string $key, array $context): void
    {
        $this->context[$key] = $context;
    }

    public function getMessages(): array
    {
        return $this->messages;
    }

    public function addMessage(string $key, string $message): void
    {
        $this->messages[$key] = \ucwords(\mb_strtolower($message));
    }

    public function getFullMessage(): string
    {
        return \trim(\array_reduce(
            $this->messages,
            static fn (string $carrier, string $item) => $carrier . ' ' . $item,
            ''
        ));
    }

    /**
     * @throws JsonException
     */
    public function toJson(): string
    {
        return \json_encode($this->messages, JSON_THROW_ON_ERROR);
    }

    #[ArrayShape(['code' => "int", 'context' => "array", 'messages' => "array"])]
    public function toArray(): array
    {
        return [
            'code' => $this->code,
            'context' => $this->context,
            'messages' => $this->messages,
        ];
    }
}
