<?php

namespace AdamJedlicka\Admin\Fields;

use AdamJedlicka\Admin\Resource;
use AdamJedlicka\Admin\Fields\Traits\HasForeignKey;

class HasMany extends RelationshipField
{
    protected $visibleOn = ['detail'];

    protected $panel = true;

    public function exports(Resource $resource)
    {
        return [
            'relatedResourceName' => $this->relatedResource->name(),
            'relatedFieldName' => $this->getRelatedField()->getName(),
            'policies' => $this->relatedResource->getPolicies(),
        ];
    }
}
