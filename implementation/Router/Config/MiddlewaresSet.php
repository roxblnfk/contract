<?php

declare(strict_types=1);

namespace roxblnfk\Contract\Implementation\Router\Config;

use SplDoublyLinkedList;

/**
 * @internal
 */
final class MiddlewaresSet implements \IteratorAggregate
{
    private SplDoublyLinkedList $definitions;
    private ?ActionDefinition $action = null;

    public function __construct()
    {
        $this->definitions = new SplDoublyLinkedList();
    }

    public function prepend(MiddlewareDefinition $definition): void
    {
        $this->definitions->unshift($definition);
    }

    public function append(MiddlewareDefinition $definition): void
    {
        $this->definitions->push($definition);
    }

    public function getIterator(): SplDoublyLinkedList
    {
        return $this->definitions;
    }

    public function hasAction(): bool
    {
        return $this->action !== null;
    }

    public function getAction(): ?ActionDefinition
    {
        return $this->action;
    }

    public function setAction(?ActionDefinition $action): void
    {
        $this->action = $action;
    }
}
