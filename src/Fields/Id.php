<?php

namespace AdamJedlicka\Admin\Fields;

class Id extends Field
{
    protected $visibleOn = ['index', 'detail'];

    protected $indexSize = 'small';
}
