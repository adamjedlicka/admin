<?php

namespace AdamJedlicka\Admin\Http\Controllers;

class HasOneController extends Controller
{
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
