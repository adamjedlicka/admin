<?php

namespace AdamJedlicka\Admin\Serializers;

use JsonSerializable;
use InvalidArgumentException;
use AdamJedlicka\Admin\Resource;
use AdamJedlicka\Admin\Fields\Id;
use AdamJedlicka\Admin\Fields\Field;
use AdamJedlicka\Admin\Fields\BelongsTo;
use AdamJedlicka\Admin\Fields\BelongsToMany;
use AdamJedlicka\Admin\Fields\PivotBelongsTo;
use AdamJedlicka\Admin\Facades\ResourceService;

class PivotSerializer extends IndexSerializer implements JsonSerializable
{
    /**
     * @var \AdamJedlicka\Admin\Resource
     */
    protected $resource;

    /**
     * @var \AdamJedlicka\Admin\Resource
     */
    protected $relatedResource;

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * @var string
     */
    protected $relationship;

    /**
     * @var \AdamJedlicka\Admin\Fields\BelongsToMany
     */
    protected $field;

    public function __construct(Resource $resource, $key, $belongsToMany)
    {
        $this->resource = $resource;
        $this->model = $this->resource->model()::findOrFail($key);

        if (is_string($belongsToMany)) {
            $this->relationship = $belongsToMany;
            $this->field = $resource->getField($belongsToMany);
        } else if ($belongsToMany instanceof BelongsToMany) {
            $this->relationship = $belongsToMany->getName();
            $this->field = $belongsToMany;
        } else {
            throw new InvalidArgumentException;
        }

        $this->indexQuery = $this->model->{$this->relationship}();
        dd($this->indexQuery);

        $this->relatedResource = ResourceService::getResourceFromModel(
            $this->indexQuery->getRelated()
        );

        // We don't want to display any normal fiels
        $this->exceptFields = $this->resource->getFields()
            ->map(function (Field $field) {
                return $field->getName();
            })
            ->toArray();

        $this->extraFields = $this->fields();
    }

    protected function fields()
    {
        return collect([

            Id::make('Id', function ($model) {
                return $model->pivot->getKey();
            }),

            PivotBelongsTo::make($this->relatedResource->name()),

        ])->merge($this->field->getFields());
    }
}
