<?php

declare(strict_types=1);

namespace roxblnfk\Contract\StateContainer;

use RuntimeException;
use WeakReference;

final class StateContainer implements StateBoxInterface
{
    /** @var WeakReference[][]|callable[][] */
    private array $objects = [];

    /** @var bool State dropping in process */
    private bool $dropping = false;

    public function createStateBox(array $initValues = []): StateBox
    {
        $box = StateBox::fromArray($initValues);
        $this->registerObject($box);
        return $box;
    }

    public function registerObject(object $object, string $method = null): void
    {
        if ($object === $this) {
            return;
        }
        $this->objects[] = [WeakReference::create($object), $method];
    }

    public function registerClosure(callable $callable): void
    {
        $this->objects[] = is_array($callable) ? $callable : [$callable];
    }

    public function dropState(): void
    {
        if ($this->dropping) {
            return;
        }
        try {
            $this->dropping = true;
            $newBoxes = [];
            foreach ($this->objects as $pair) {
                $object = $pair[0]->get();
                if ($object === null) {
                    continue;
                }
                if ($pair[1] === null) {
                    if ($object instanceof StateBoxInterface) {
                        $object->dropState();
                    } elseif (is_callable($object)) {
                        $object();
                    } else {
                        throw new RuntimeException();
                    }
                } elseif (is_callable($pair)) {
                    $pair();
                } else {
                    throw new RuntimeException();
                }
                $newBoxes[] = $pair;
            }

            $this->objects = $newBoxes;
        } finally {
            $this->dropping = false;
        }
    }
}
