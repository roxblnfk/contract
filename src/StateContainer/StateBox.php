<?php

declare(strict_types=1);

namespace roxblnfk\Contract\StateContainer;

class StateBox implements StateBoxInterface
{
    private array $data = [];

    public static function fromArray(array $values): self
    {
        $result = new self();
        $result->data = $values;
        return $result;
    }

    final public function &link(int|string $key): mixed
    {
        return $this->data[$key];
    }

    final public function get(int|string $key): mixed
    {
        return $this->data[$key] ?? null;
    }

    final public function set(int|string $key, mixed $value): void
    {
        $this->data[$key] = $value;
    }

    public function dropState(): void
    {
        $this->data = [];
    }
}
