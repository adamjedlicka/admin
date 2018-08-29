<?php

namespace AdamJedlicka\Admin;

use AdamJedlicka\Admin\FieldCollection;
use AdamJedlicka\Admin\Fields\BelongsTo;
use AdamJedlicka\Admin\Fields\PivotBelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use AdamJedlicka\Admin\Http\Requests\RelationshipRequest;

class PivotResource extends Resource
{
    public static $model = Pivot::class;

    /**
     * @var \AdamJedlicka\Admin\Resource
     */
    protected $parentResource;

    /**
     * @var \AdamJedlicka\Admin\Resource
     */
    protected $relatedResource;

    /**
     * @var \AdamJedlicka\Admin\FieldCollection
     */
    protected $fields;

    /**
     * @var \AdamJedlicka\Admin\Fields\BelongsToMany
     */
    protected $belongsToManyField;

    public function __construct(RelationshipRequest $request)
    {
        $this->parentResource = $request->resource();
        $this->relatedResource = $request->relatedResource();
        $this->modelInstance = $this->parentResource->getModel();

        $this->belongsToManyField = $this->parentResource->getFields()->named($request->relationship);

        $this->fields = new FieldCollection([
            PivotBelongsTo::make(
                $this->relatedResource->name(),
                $this->belongsToManyField->getName(),
                $this->parentResource
            )
        ]);
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
        $name = $this->relatedResource->name();

        return [
            'attach' => $this->authorizeIfPolicyExists("attach$name", $this->modelInstance),
        ];
    }
}
