<?php

declare(strict_types=1);

namespace Cortex\Forms\Transformers\Adminarea;

use Rinvex\Support\Traits\Escaper;
use Cortex\Forms\Models\FormResponse;
use League\Fractal\TransformerAbstract;

class FormResponseTransformer extends TransformerAbstract
{
    use Escaper;

    /**
     * @return array
     */
    public function transform(FormResponse $formResponse): array
    {
        $formFields = collect($formResponse->form->content)->whereIn('type', ['text', 'select', 'textarea', 'checkbox-group', 'date', 'file', 'number', 'radio-group']);

        $response = ['created_at' => $formResponse->created_at->format(config('app.date_format'))] + collect($formResponse->content)->map(function ($fieldValue, $fieldName) use ($formFields, $formResponse) {
            return $formFields->firstWhere('name', $fieldName)['type'] === 'file' && $formResponse->media->count() ? asset($formResponse->getFirstMedia('form_response', ['field' => $fieldName])->getUrl()) : $fieldValue;
        })->toArray();

        $missingFields = $formFields->pluck('name')->filter()->diff(array_keys($response))->mapWithKeys(function ($item) {
            return [$item => null];
        })->toArray();

        return $this->escape($response + $missingFields);
    }
}
