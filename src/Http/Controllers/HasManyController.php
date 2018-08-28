<?php

namespace AdamJedlicka\Admin\Http\Controllers;

use AdamJedlicka\Admin\Dial;
use AdamJedlicka\Admin\Create;
use AdamJedlicka\Admin\Facades\Resources;

class HasManyController extends Controller
{
    public function index(string $resource, $resourceKey, string $relationship)
    {
        $resource = Resources::forName($resource);
        $model = $resource::$model::findOrFail($resourceKey);
        $query = $model->{$relationship}();

        $relatedModel = $query->getRelated();
        $relatedResource = Resources::forModel($relatedModel);
        $relatedField = $resource->getField($relationship)->getRelatedField($resource);

        $fields = $relatedResource->getFields()->named($relatedField->getName());

        return (new Dial($relatedResource));
    }

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

        $fields = $relatedResource->getFields('edit');

        $fields->named($relatedFieldName)
            ->default($resourceKey)
            ->cannotBeChanged();

        return $relatedResource;
    }
}
