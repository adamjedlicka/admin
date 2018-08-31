<?php

namespace AdamJedlicka\Luna;

use AdamJedlicka\Luna\FieldCollection;
use AdamJedlicka\Luna\Fields\BelongsTo;
use AdamJedlicka\Luna\Facades\Resources;
use AdamJedlicka\Luna\Fields\BelongsToMany;
use AdamJedlicka\Luna\Fields\PivotBelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use AdamJedlicka\Luna\Http\Requests\RelationshipRequest;

class PivotResource extends Resource
{
    public static $model = Pivot::class;

    public function title()
    {
        return $this->resource->title();
    }

    /**
     * Resource this pivot is representing
     *
     * @var \AdamJedlicka\Luna\Resource
     */
    protected $resource;

    /**
     * Parent resource which is queried to get this resource
     *
     * @var \AdamJedlicka\Luna\Resource
     */
    protected $parentResource;

    /**
     * @var \AdamJedlicka\Luna\FieldCollection
     */
    protected $fields;

    /**
     * BelongsToMany field on $parentResource
     *
     * @var \AdamJedlicka\Luna\Fields\BelongsToMany
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
            )->cannotBeChanged()
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

    public static function fromResource(Resource $resource, Resource $parentResource, string $fieldName)
    {
        $belongsToManyField = $parentResource->getFields()->named($fieldName);

        return new static($resource, $parentResource, $belongsToManyField);
    }

    public function fields()
    {
        throw new \BadMethodCallException('Do not call PivotResource::field directly-');
    }

    public function getFields() : FieldCollection
    {
        return $this->fields
            ->merge($this->belongsToManyField->getFields())
            ->each(function ($field) {
                $field->setModel($this->modelInstance);
            });
    }

    public function getPolicies() : array
    {
        $name = $this->resource->name();

        return [
            'attachAny' => $this->authorizeIfPolicyExists("attachAny$name", $this->parentResource->getModel()),
            'attach' => $this->authorizeIfPolicyExists("attach$name", $this->parentResource->getModel(), $this->modelInstance),
            'update' => $this->authorizeIfPolicyExists("update$name", $this->parentResource->getModel(), $this->modelInstance),
            'detach' => $this->authorizeIfPolicyExists("detach$name", $this->parentResource->getModel(), $this->modelInstance),
        ];
    }
}
