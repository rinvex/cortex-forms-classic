<?php

declare(strict_types=1);

namespace Cortex\Forms\DataTables\Adminarea;

use Cortex\Forms\Models\FormResponse;
use Cortex\Foundation\DataTables\AbstractDataTable;
use Cortex\Forms\Transformers\Adminarea\FormResponseTransformer;

class FormResponsesDataTable extends AbstractDataTable
{
    /**
     * {@inheritdoc}
     */
    protected $model = FormResponse::class;

    /**
     * {@inheritdoc}
     */
    protected $transformer = FormResponseTransformer::class;

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return datatables($this->query())
            ->setTransformer(app($this->transformer))
            ->orderColumn('name', 'name->"$.'.app()->getLocale().'" $1')
            ->make(true);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns(): array
    {
        return collect($this->resource->content)->whereIn('type', ['text', 'select', 'textarea', 'checkbox-group', 'date', 'file', 'number', 'radio-group'])->pluck('label', 'name')->prepend(trans('cortex/forms::common.created_at'), 'created_at')->mapWithKeys(function ($value, $key) {
            return [$key => ['title' => $value, 'orderable' => false, 'searchable' => false]];
        })->toArray();
    }
}
