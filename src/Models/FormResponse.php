<?php

declare(strict_types=1);

namespace Cortex\Forms\Models;

use Rinvex\Tags\Traits\Taggable;
use Spatie\MediaLibrary\HasMedia;
use Cortex\Foundation\Traits\Auditable;
use Rinvex\Support\Traits\HashidsTrait;
use Cortex\Foundation\Events\ModelCreated;
use Cortex\Foundation\Events\ModelDeleted;
use Cortex\Foundation\Events\ModelUpdated;
use Cortex\Foundation\Events\ModelRestored;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\InteractsWithMedia;
use Cortex\Foundation\Traits\FiresCustomModelEvent;
use Rinvex\Forms\Models\FormResponse as BaseFormResponse;

/**
 * Cortex\Forms\Models\FormResponse.
 *
 * @property int                                                                             $id
 * @property string                                                                          $unique_identifier
 * @property array                                                                           $content
 * @property int                                                                             $form_id
 * @property int                                                                             $user_id
 * @property string                                                                          $user_type
 * @property \Carbon\Carbon|null                                                             $created_at
 * @property \Carbon\Carbon|null                                                             $updated_at
 * @property \Carbon\Carbon|null                                                             $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent                              $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Forms\Models\FormResponse ofUser(\Illuminate\Database\Eloquent\Model $user)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Forms\Models\FormResponse whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Forms\Models\FormResponse whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Forms\Models\FormResponse whereFormId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Forms\Models\FormResponse whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Forms\Models\FormResponse whereUserType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Forms\Models\FormResponse whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FormResponse extends BaseFormResponse implements HasMedia
{
    use Taggable;
    use Auditable;
    use HashidsTrait;
    use LogsActivity;
    use InteractsWithMedia;
    use FiresCustomModelEvent;

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => ModelCreated::class,
        'deleted' => ModelDeleted::class,
        'restored' => ModelRestored::class,
        'updated' => ModelUpdated::class,
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
        parent::__construct($attributes);

        $this->mergeFillable(['tags']);

        $this->mergeRules(['tags' => 'nullable|array']);

        $this->setTable(config('rinvex.forms.tables.form_responses'));
    }

    /**
     * Register media collections.
     *
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('form_response');
    }
}
