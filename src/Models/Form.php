<?php

declare(strict_types=1);

namespace Cortex\Forms\Models;

use Rinvex\Tags\Traits\Taggable;
use Rinvex\Tenants\Traits\Tenantable;
use Cortex\Foundation\Traits\Auditable;
use Rinvex\Support\Traits\HashidsTrait;
use Rinvex\Forms\Models\Form as BaseForm;
use Spatie\Activitylog\Traits\LogsActivity;
use Silber\Bouncer\Database\HasRolesAndAbilities;

/**
 * Cortex\Forms\Models\Form.
 *
 * @property int                                                                             $id
 * @property int                                                                             $entity_id
 * @property string                                                                          $entity_type
 * @property string                                                                          $slug
 * @property string                                                                          $name
 * @property string                                                                          $description
 * @property array                                                                           $content
 * @property array                                                                           $actions
 * @property array                                                                           $submission
 * @property boolean                                                                         $is_active
 * @property boolean                                                                         $is_public
 * @property \Carbon\Carbon|null                                                             $created_at
 * @property \Carbon\Carbon|null                                                             $updated_at
 * @property \Carbon\Carbon|null                                                             $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent                              $entity
 * @property \Illuminate\Database\Eloquent\Collection|\Cortex\Tenants\Models\Tenant[]        $tenants
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Forms\Models\Form whereActions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Forms\Models\Form whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Forms\Models\Form whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Forms\Models\Form whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Forms\Models\Form whereEntityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Forms\Models\Form whereEntityType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Forms\Models\Form whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Forms\Models\Form whereIsPublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Forms\Models\Form whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Forms\Models\Form whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Forms\Models\Form whereSubmission($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Forms\Models\Form whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Forms\Models\Form withAllTenants($tenants, $group = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Forms\Models\Form withAnyTenants($tenants, $group = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Forms\Models\Form withTenants($tenants, $group = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Forms\Models\Form withoutAnyTenants()
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Forms\Models\Form withoutTenants($tenants, $group = null)
 * @mixin \Eloquent
 */
class Form extends BaseForm
{
    use Taggable;
    use Auditable;
    use Tenantable;
    use HashidsTrait;
    use LogsActivity;
    use HasRolesAndAbilities;

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'entity_id',
        'entity_type',
        'slug',
        'name',
        'description',
        'content',
        'actions',
        'submission',
        'is_active',
        'is_public',
        'abilities',
        'roles',
        'tags',
    ];

    /**
     * The default rules that the model will validate against.
     *
     * @var array
     */
    protected $rules = [
        'entity_id' => 'nullable|integer',
        'entity_type' => 'nullable|string|max:150',
        'slug' => 'required|string',
        'name' => 'required|string|max:150',
        'description' => 'nullable|string|max:10000',
        'content' => 'required|array',
        'actions' => 'required|array',
        'submission' => 'required|array',
        'is_active' => 'sometimes|boolean',
        'is_public' => 'sometimes|boolean',
        'tags' => 'nullable|array',
    ];

    /**
     * Indicates whether to log only dirty attributes or all.
     *
     * @var bool
     */
    protected static $logOnlyDirty = true;

    /**
     * The attributes that are logged on change.
     *
     * @var array
     */
    protected static $logFillable = true;

    /**
     * The attributes that are ignored on change.
     *
     * @var array
     */
    protected static $ignoreChangedAttributes = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Attach the given abilities to the model.
     *
     * @param mixed $abilities
     *
     * @return void
     */
    public function setAbilitiesAttribute($abilities): void
    {
        static::saved(function (self $model) use ($abilities) {
            $abilities = collect($abilities)->filter();

            $model->abilities->pluck('id')->similar($abilities)
            || activity()
                ->performedOn($model)
                ->withProperties(['attributes' => ['abilities' => $abilities], 'old' => ['abilities' => $model->abilities->pluck('id')->toArray()]])
                ->log('updated');

            $model->abilities()->sync($abilities, true);
        });
    }

    /**
     * Attach the given roles to the model.
     *
     * @param mixed $roles
     *
     * @return void
     */
    public function setRolesAttribute($roles): void
    {
        static::saved(function (self $model) use ($roles) {
            $roles = collect($roles)->filter();

            $model->roles->pluck('id')->similar($roles)
            || activity()
                ->performedOn($model)
                ->withProperties(['attributes' => ['roles' => $roles], 'old' => ['roles' => $model->roles->pluck('id')->toArray()]])
                ->log('updated');

            $model->roles()->sync($roles, true);
        });
    }
}
