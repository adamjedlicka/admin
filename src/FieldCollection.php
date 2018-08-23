<?php

namespace AdamJedlicka\Admin;

use Illuminate\Support\Collection;

class FieldCollection extends Collection
{
    public function names()
    {
        $names = [];
        foreach ($this->items as $item) {
            $names[] = $item->getName();
        }

        return $names;
    }
}
