<?php

namespace AdamJedlicka\Admin\Fields;

use Illuminate\Support\Str;
use AdamJedlicka\Admin\Panel;
use AdamJedlicka\Admin\Resource;
use Illuminate\Database\Eloquent\Model;
use AdamJedlicka\Admin\Facades\ResourceService;
use function AdamJedlicka\Admin\Support\get_resource_from_name;
use function AdamJedlicka\Admin\Support\get_resource_from_model;

/**
 * TODO : One big not good. DO SOMETHING ABOUT IT!
 * Maybe something like constructor for fields so we can commonly used stuff like relationships.
 */
class HasOne extends Field
{
    protected $visibleOn = ['index', 'detail', 'edit'];

    protected $panel = true;

    /**
     * @var \Illuminate\Database\Eloquent\Relations\HasOne
     */
    protected $relationship;

    /**
     * @var \AdamJedlicka\Admin\Resource
     */
    protected $relatedResource;

    /**
     * @var string
     */
    protected $foreignKey;

    protected function prepare(Resource $resource, Model $model)
    {
        $this->relationship = $model->{$this->name}();
        $this->foreignKey = $this->relationship->getForeignKeyName();
        $this->relatedResource = ResourceService::getResourceFromModel(
            $model->{$this->name} ?? $this->relationship->getRelated()
        );
    }

    public function retrieve(Model $model)
    {
        return $model->getAttribute($this->name);
    }

    public function persist(Model $model, $value)
    {
        if (!$value) return;

        $model->saved(function ($model) use ($value) {
            $key = $this->relationship->getRelated()->getKeyName();

            $model->{$this->name}()->updateOrCreate([$key => $value[$key] ?? null], $value);
        });
    }

    protected function metaInfo(Resource $resource)
    {
        return [
            'fields' => collect($this->relatedResource->getFields())
                ->filter(function (Field $field) {
                    if (!$field instanceof BelongsTo) return $field;

                    return $this->foreignKey != $field->getForeignKey();
                })
                ->values(),
        ];
    }

    protected function metaValue(Resource $resource, Model $model)
    {
        return [
            'title' => $this->relatedResource->getTitle(),
            'res' => $this->relatedResource->model,
        ];
    }

    protected function resolveName(string $displayName) : string
    {
        return Str::camel($displayName);
    }
}
