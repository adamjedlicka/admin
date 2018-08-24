<?php

namespace AdamJedlicka\Admin\Http\Controllers;

use AdamJedlicka\Admin\Dial;
use AdamJedlicka\Admin\Attach;

class BelongsToManyController extends Controller
{
    public function index(string $resource, $key, string $relationship)
    {
        $resource = $this->getResource($resource);
        $model = $resource->model()::findOrFail($key);

        $fields = $resource->getField($relationship)->getFields($resource);
        $query = $model->$relationship();

        $relatedPivotKeyName = $query->getRelatedPivotKeyName();

        return (new Dial($fields, $query))
            ->detachUrl("/api/relationships/{$resource->name()}/$key/belongsToMany/$relationship/detach/\${{$relatedPivotKeyName}}");
    }

    public function create(string $resource, $key, string $relationship)
    {
        $resource = $this->getResource($resource);
        $fields = $resource->getField($relationship)->getFields($resource);

        return (new Attach($fields))
            ->attachUrl("/api/relationships/{$resource->name()}/$key/belongsToMany/$relationship/attach");
    }

    public function attach(string $resource, $key, string $relationship)
    {
        $resource = $this->getResource($resource);
        $model = $resource->model()::findOrFail($key);
        $relationship = $model->$relationship();

        $relatedKeyName = $relationship->getRelatedPivotKeyName();

        request()->validate([
            $relatedKeyName => 'required',
        ]);

        $relationship->attach(request()->only($relatedKeyName), request()->except($relatedKeyName));

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function detach(string $resource, $key, string $relationship, $what)
    {
        $resource = $this->getResource($resource);
        $model = $resource->model()::findOrFail($key);
        $relationship = $model->$relationship();

        $relationship->detach($what);

        return response()->json([
            'status' => 'success'
        ]);
    }
}
