<?php

namespace AdamJedlicka\Luna\Fields;

class Id extends Field
{
    protected $visibleOn = ['index', 'detail'];

    protected $indexSize = 'small';
}
