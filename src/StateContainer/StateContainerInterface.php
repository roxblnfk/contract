<?php

declare(strict_types=1);

namespace roxblnfk\Contract\StateContainer;

use WeakReference;

final class StateContainerInterface implements StateBoxInterface
{
    /** @var WeakReference[] */
    private array $boxRefs = [];
    /** @var bool State dropping in process */
    private bool $dropping = false;

    public function createStateBox(array $initValues = []): StateBox
    {
        $box = StateBox::fromArray($initValues);
        $this->addStateBox($box);
        return $box;
    }

    public function addStateBox(StateBoxInterface $box): void
    {
        if ($box === $this) {
            return;
        }
        $this->boxRefs[] = WeakReference::create($box);
    }

    public function dropState(): void
    {
        if ($this->dropping) {
            return;
        }
        try {
            $this->dropping = true;
            $newBoxes = [];
            foreach ($this->boxRefs as $boxReference) {
                $box = $boxReference->get();
                if ($box instanceof StateBoxInterface) {
                    $box->dropState();
                    $newBoxes[] = $boxReference;
                }
            }

            $this->boxRefs = $newBoxes;
        } finally {
            $this->dropping = false;
        }
    }
}
