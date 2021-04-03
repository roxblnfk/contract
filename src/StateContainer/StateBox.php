<?php

declare(strict_types=1);

namespace roxblnfk\Contract\StateContainer;

use ArrayAccess;

class StateBox implements StateBoxInterface, ArrayAccess
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

    final public function offsetSet($offset, $value)
    {
        if ($offset === null) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    final public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    final public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    final public function offsetGet($offset)
    {
        return $this->data[$offset] ?? null;
    }
}
