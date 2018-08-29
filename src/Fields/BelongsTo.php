<?php

namespace AdamJedlicka\Admin\Fields;

use Illuminate\Support\Str;
use AdamJedlicka\Admin\Resource;
use Illuminate\Database\Eloquent\Model;
use AdamJedlicka\Admin\Facades\Resources;
use AdamJedlicka\Admin\Fields\Traits\HasForeignKey;

class BelongsTo extends RelationshipField
{
    protected $visibleOn = ['index', 'detail', 'edit'];

    protected function __construct(...$args)
    {
        parent::__construct(...$args);

        $this->sortsAs = $this->getForeignKeyName();
    }

    public function retrieve(Model $model)
    {
        return optional($model->getAttribute($this->name))->getKey();
    }

    public function persist(Model $model, $value)
    {
        $model->setAttribute($this->getForeignKeyName(), $value);
    }

    public function exports(Resource $resource)
    {
        return [
            'relatedResourceName' => $this->relatedResource->name(),
        ];
    }

    public function meta(Resource $resource, Model $model)
    {
        $relatedModel = $model->{$this->getName()};

        if ($relatedModel) {
            $this->relatedResource->setModel($relatedModel);
            $title = $this->relatedResource->title();
        }

        return [
            'title' => $title ?? null,
        ];
    }

    /**
     * Returns name of the foreign key
     *
     * BUG : BelongsTo has getForeignKey instead of getForeignKeyName
     *       Create pull request to Laravel?
     *
     * @return string
     */
    public function getForeignKeyName() : string
    {
        return $this->relationship->getForeignKey();
    }
}
