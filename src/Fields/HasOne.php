<?php

namespace AdamJedlicka\Admin\Fields;

use Illuminate\Support\Str;
use AdamJedlicka\Admin\Panel;
use AdamJedlicka\Admin\Resource;
use Illuminate\Database\Eloquent\Model;
use AdamJedlicka\Admin\Facades\ResourceService;
use function AdamJedlicka\Admin\Support\get_resource_from_name;
use function AdamJedlicka\Admin\Support\get_resource_from_model;

class HasOne extends Field
{
    protected $visibleOn = ['index', 'detail', 'edit'];

    protected $isPanel = true;

    public function retrieve(Model $model)
    {
        return $model->getAttribute($this->name);
    }

    public function persist(Model $model, $value)
    {
        if (!$value) return;

        $model->saved(function ($model) use ($value) {
            $key = $model->{$this->name}()->getRelated()->getKeyName();

            call_user_func([$model, $this->name])
                ->updateOrCreate([$key => $value[$key] ?? null], $value);
        });
    }

    protected function metaInfo(Resource $resource)
    {
        $hasOneResource = $this->hasOneResource($resource);

        return [
            'fields' => $hasOneResource->getFields(),
        ];
    }

    protected function metaValue(Resource $resource, Model $model)
    {
        $hasOneResource = $this->hasOneResource($resource);

        return [
            'title' => $hasOneResource->getTitle(),
        ];
    }

    protected function resolveName(string $displayName) : string
    {
        return Str::camel($displayName);
    }

    public function getRules() : array
    {
        $hasOneResource = $this->hasOneResource($this->resource);

        return $hasOneResource->getRules();
    }

    protected function hasOneResource(Resource $resource) : Resource
    {
        if ($resource->getModel()) {
            $model = $resource->getModel();
        } else {
            $modeName = $resource->fullyQualifiedModelName();
            $model = new $modeName;
        }

        $hasOneModel = $model->{$this->name} ?? $model->{$this->name}()->getRelated();
        return ResourceService::getResourceFromModel($hasOneModel);
    }
}
