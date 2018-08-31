<?php

namespace AdamJedlicka\Luna\Fields;

use AdamJedlicka\Luna\Resource;
use AdamJedlicka\Luna\Fields\Traits\HasForeignKey;

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
