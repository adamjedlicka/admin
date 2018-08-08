<?php

namespace AdamJedlicka\Admin\Fields;

class ID extends Field
{
    protected $indexVisible = true;

    protected $indexSize = 'small';

    public static function make(string $displayName = 'ID', $options = 'id')
    {
        return parent::make($displayName, $options);
    }
}
