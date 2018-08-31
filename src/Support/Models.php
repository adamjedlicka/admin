<?php

namespace AdamJedlicka\Luna\Support;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use AdamJedlicka\Luna\Facades\Resources;

class Models
{
    /**
     * Persists current request into model
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return mixed
     */
    public function persist(Model $model)
    {
        $resource = Resources::forModel($model);

        return DB::transaction(function () use ($resource, $model) {
            $resource->getFields()
                ->onlyFor('edit')
                ->each(function ($field) use ($model) {
                    $field->persist($model, request($field->getName()));
                });

            return tap($model, function ($model) {
                $model->saveOrFail();
            });
        });
    }
}
