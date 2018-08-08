<?php

namespace AdamJedlicka\Admin\Fields;

class ID extends Field
{
    protected $indexVisible = true;

    protected $indexSize = 'small';

    public static function make(string $displayName = 'ID', ? string $field = 'id')
    {
        return parent::make($displayName, $field);
    }
}
