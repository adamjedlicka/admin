<?php

namespace AdamJedlicka\Admin;

use AdamJedlicka\Admin\FieldCollection;
use AdamJedlicka\Admin\Fields\BelongsTo;
use AdamJedlicka\Admin\Fields\BelongsToMany;
use AdamJedlicka\Admin\Fields\PivotBelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use AdamJedlicka\Admin\Http\Requests\RelationshipRequest;

class PivotResource extends Resource
{
    public static $model = Pivot::class;

    /**
     * Resource this pivot is representing
     *
     * @var \AdamJedlicka\Admin\Resource
     */
    protected $resource;

    /**
     * Parent resource which is queried to get this resource
     *
     * @var \AdamJedlicka\Admin\Resource
     */
    protected $parentResource;

    /**
     * @var \AdamJedlicka\Admin\FieldCollection
     */
    protected $fields;

    /**
     * BelongsToMany field on $parentResource
     *
     * @var \AdamJedlicka\Admin\Fields\BelongsToMany
     */
    protected $belongsToManyField;

    protected function __construct(Resource $resource, Resource $parentResource, BelongsToMany $field)
    {
        $this->resource = $resource;
        $this->parentResource = $parentResource;
        $this->belongsToManyField = $field;

        $this->modelInstance = $this->resource->getModel();

        $this->fields = new FieldCollection([
            PivotBelongsTo::make(
                $this->resource->name(),
                $this->belongsToManyField->getName(),
                $this->parentResource
            )
        ]);
    }

    public static function fromRequest(RelationshipRequest $request)
    {
        return new static(
            $request->relatedResource(),
            $request->resource(),
            $request->resource()->getFields()->named($request->relationship)
        );
    }

    public function fields()
    {
        throw new \BadMethodCallException('Do not call PivotResource::field directly-');
    }

    public function getFields() : FieldCollection
    {
        return $this->fields
            ->merge($this->belongsToManyField->getFields());
    }

    public function getPolicies() : array
    {
        $name = $this->resource->name();

        return [
            'attach' => $this->authorizeIfPolicyExists("attach$name", $this->modelInstance),
        ];
    }
}
