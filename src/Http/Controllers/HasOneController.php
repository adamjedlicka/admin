<?php

namespace AdamJedlicka\Admin\Http\Controllers;

use AdamJedlicka\Admin\Facades\Resources;

class HasOneController extends Controller
{
    public function create(string $resource, $resourceKey, string $relationship)
    {
        $relationshipName = $relationship;

        $resource = Resources::forName($resource);
        $model = $resource::$model::findOrFail($resourceKey);
        $relationship = $model->$relationship();
        $foreignKeyName = $relationship->getForeignKeyName();

        $hasManyField = $resource->getFields()->named($relationshipName);
        $relatedFieldName = $hasManyField->getRelatedField($resource)->getName();

        $relatedModel = $relationship->getRelated();
        $relatedResource = Resources::forModel($relatedModel);

        return $relatedResource;
    }
}
