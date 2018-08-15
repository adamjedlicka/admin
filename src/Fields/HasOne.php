<?php

namespace AdamJedlicka\Admin\Fields;

use Illuminate\Support\Str;
use AdamJedlicka\Admin\Panel;
use AdamJedlicka\Admin\Resource;
use Illuminate\Database\Eloquent\Model;
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
        $model->saved(function ($model) use ($value) {
            $key = $model->{$this->name}()->getRelated()->getKeyName();

            call_user_func([$model, $this->name])
                ->updateOrCreate([$key => $value[$key] ?? null], $value);
        });
    }

    protected function meta(Resource $resource)
    {
        if ($resource->getModel()) {
            $model = $resource->getModel();
            $hasOneModel = $model->{$this->name};
        } else {
            $modeName = $resource->fullyQualifiedModelName();
            $model = new $modeName;
            $hasOneModel = $model->{$this->name}()->getRelated();
        }

        $hasOneResource = get_resource_from_model($hasOneModel);

        return [
            'fields' => $hasOneResource->getFields(),
            'title' => $hasOneResource->title(),
        ];
    }

    protected function resolveName(string $displayName) : string
    {
        return Str::camel($displayName);
    }
}
