<?php

declare(strict_types=1);

namespace Cortex\Forms\Models;

use Rinvex\Tags\Traits\Taggable;
use Cortex\Forms\Events\FormCreated;
use Cortex\Forms\Events\FormDeleted;
use Cortex\Forms\Events\FormUpdated;
use Cortex\Forms\Events\FormRestored;
use Rinvex\Tenants\Traits\Tenantable;
use Cortex\Foundation\Traits\Auditable;
use Rinvex\Support\Traits\HashidsTrait;
use Rinvex\Support\Traits\HasTimezones;
use Rinvex\Forms\Models\Form as BaseForm;
use Spatie\Activitylog\Traits\LogsActivity;
use Silber\Bouncer\Database\HasRolesAndAbilities;

/**
 * Cortex\Forms\Models\Form.
 *
 * @property int                 $id
 * @property int                 $entity_id
 * @property string              $entity_type
 * @property string              $slug
 * @property string              $name
 * @property string              $description
 * @property array               $content
 * @property array               $actions
 * @property array               $submission
 * @property bool                $is_active
 * @property bool                $is_public
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent                              $entity
 * @property \Illuminate\Database\Eloquent\Collection|\Cortex\Tenants\Models\Tenant[] $tenants
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
    use HasTimezones;
    use LogsActivity;
    use HasRolesAndAbilities;

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => FormCreated::class,
        'updated' => FormUpdated::class,
        'deleted' => FormDeleted::class,
        'restored' => FormRestored::class,
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
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->mergeFillable(['abilities', 'roles', 'tags']);

        $this->mergeRules(['tags' => 'nullable|array']);

        parent::__construct($attributes);
    }
}
