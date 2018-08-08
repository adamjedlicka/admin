<?php

namespace AdamJedlicka\Admin\Fields;

class ID extends Field
{
    public static function make(string $displayName = 'ID', ? string $field = 'id')
    {
        return parent::make($displayName, $field);
    }
}
