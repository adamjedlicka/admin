<?php

namespace AdamJedlicka\Admin\Http\Controllers;

use AdamJedlicka\Admin\Dial;
use AdamJedlicka\Admin\Create;

class HasManyController extends Controller
{
    public function index(string $resource, $resourceKey, string $relationship)
    {
        $resource = $this->getResource($resource);
        $model = $resource->model()::findOrFail($resourceKey);
        $query = $model->{$relationship}();

        $relatedModel = $query->getRelated();
        $relatedResource = $this->getResource($relatedModel);
        $relatedField = $resource->getField($relationship)->getRelatedField($resource);

        $fields = $relatedResource->getFields('index')
            ->filter(function ($field) use ($relatedField) {
                return $field->getName() != $relatedField->getName();
            })
            ->values();

        return (new Dial($fields, $query))
            ->detailUrl("/resources/{$relatedResource->name()}/\${{$relatedModel->getKeyName()}}")
            ->editUrl("/resources/{$relatedResource->name()}/\${{$relatedModel->getKeyName()}}/edit")
            ->deleteUrl("/api/resources/{$relatedResource->name()}/\${{$relatedModel->getKeyName()}}");
    }

    public function create(string $resource, $resourceKey, string $relationship)
    {
        $relationshipName = $relationship;

        $resource = $this->getResource($resource);
        $model = $resource->model()::findOrFail($resourceKey);
        $relationship = $model->$relationship();
        $foreignKeyName = $relationship->getForeignKeyName();

        $hasManyField = $resource->getField($relationshipName);
        $relatedFieldName = $hasManyField->getRelatedField($resource)->getName();

        $relatedModel = $relationship->getRelated();
        $relatedResource = $this->getResource($relatedModel);

        $fields = $relatedResource->getFields('edit');

        $fields->named($relatedFieldName)
            ->default($resourceKey)
            ->cannotBeChanged();

        return (new Create($fields))
            ->title('Create')
            ->storeUrl("/api/resources/{$relatedResource->name()}");
    }
}
