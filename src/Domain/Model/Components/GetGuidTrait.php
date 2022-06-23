<?php

namespace App\Domain\Model\Components;

trait GetGuidTrait
{
    public function getGuid(): string
    {
        return $this->guid;
    }
}
