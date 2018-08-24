<?php

namespace AdamJedlicka\Admin\Fields;

use Illuminate\Support\Str;
use AdamJedlicka\Admin\Resource;
use AdamJedlicka\Admin\FieldCollection;
use Illuminate\Database\Eloquent\Model;
use AdamJedlicka\Admin\Facades\ResourceService;

class BelongsToMany extends Field
{
    protected $visibleOn = ['detail'];

    protected $panel = true;

    /**
     * @var array
     */
    protected $fields = [];

    public function meta(Resource $resource)
    {
        return [
            'name' => $this->relatedResource($resource)->name(),
        ];
    }

    public function fields(array $fields) : self
    {
        $this->fields = $fields;

        foreach ($this->fields as $field) {
            $field->options(function ($model) use ($field) {
                return $model->pivot->{$field->getName()};
            });
        }

        return $this;
    }

    public function getFields(Resource $resource) : FieldCollection
    {
        $relatedResource = $this->relatedResource($resource);
        $relatedPivotKeyName = $this->relatedPivotKeyName($resource);

        return (new FieldCollection([

            PivotBelongsTo::make($relatedResource->name(), $relatedPivotKeyName, function ($model) use ($relatedPivotKeyName) {
                return $model->pivot->$relatedPivotKeyName;
            })->sortable(),

        ]))->merge($this->fields)
            ->each(function ($field) use ($resource) {
                $field->setResource($resource);
            });
    }

    protected function resolveName(string $displayName) : string
    {
        return Str::camel($displayName);
    }

    protected function relatedResource(Resource $resource) : Resource
    {
        $model = $resource->model()::make();
        $relationship = $model->{$this->getName()}();

        return ResourceService::getResourceFromModel($relationship->getRelated());
    }

    protected function relatedPivotKeyName(Resource $resource) : string
    {
        $model = $resource->model()::make();
        $relationship = $model->{$this->getName()}();

        return $relationship->getRelatedPivotKeyName();
    }
}
