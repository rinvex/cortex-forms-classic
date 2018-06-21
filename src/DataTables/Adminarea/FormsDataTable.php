<?php

declare(strict_types=1);

namespace Cortex\Forms\DataTables\Adminarea;

use Cortex\Forms\Models\Form;
use Cortex\Foundation\DataTables\AbstractDataTable;
use Cortex\Forms\Transformers\Adminarea\FormTransformer;

class FormsDataTable extends AbstractDataTable
{
    /**
     * {@inheritdoc}
     */
    protected $model = Form::class;

    /**
     * {@inheritdoc}
     */
    protected $transformer = FormTransformer::class;

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns(): array
    {
        $link = config('cortex.foundation.route.locale_prefix')
            ? '"<a href=\""+routes.route(\'adminarea.forms.edit\', {form: full.id, locale: \''.$this->request->segment(1).'\'})+"\">"+data+"</a>"'
            : '"<a href=\""+routes.route(\'adminarea.forms.edit\', {form: full.id})+"\">"+data+"</a>"';

        return [
            'name' => ['title' => trans('cortex/forms::common.name'), 'render' => $link, 'responsivePriority' => 0],
            'slug' => ['title' => trans('cortex/forms::common.slug')],
            'is_active' => ['title' => trans('cortex/forms::common.is_active')],
            'is_public' => ['title' => trans('cortex/forms::common.is_public')],
            'responses' => ['title' => trans('cortex/forms::common.responses')],
            'created_at' => ['title' => trans('cortex/forms::common.created_at'), 'render' => "moment(data).format('YYYY-MM-DD, hh:mm:ss A')"],
            'updated_at' => ['title' => trans('cortex/forms::common.updated_at'), 'render' => "moment(data).format('YYYY-MM-DD, hh:mm:ss A')"],
        ];
    }
}
