<?php

namespace AdamJedlicka\Admin\Fields;

class ID extends Field
{
    protected $visibleOn = ['index', 'detail'];

    protected $indexSize = 'small';

    public static function make(string $displayName = 'ID', $options = 'id')
    {
        return parent::make($displayName, $options);
    }
}
