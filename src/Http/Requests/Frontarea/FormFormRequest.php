<?php

declare(strict_types=1);

namespace Cortex\Forms\Http\Requests\Frontarea;

use Illuminate\Support\Arr;
use Rinvex\Support\Traits\Escaper;
use Illuminate\Foundation\Http\FormRequest;

class FormFormRequest extends FormRequest
{
    use Escaper;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = [];
        $form = $this->route('form') ?? app('rinvex.forms.form');

        collect($form->content)->whereIn('type', ['text', 'select', 'textarea', 'checkbox-group', 'date', 'file', 'number', 'radio-group'])->each(function ($field) use (&$rules) {
            $rules[$field['name']][] = Arr::get($field, 'required') ? 'required' : 'nullable';
            $field['type'] !== 'checkbox-group' || $rules[$field['name']][] = 'array';
            in_array($field['type'], ['checkbox-group', 'file']) || $rules[$field['name']][] = 'string';
            //$field['type'] !== 'file' || $rules[$field['name']][] = 'mimes:' . config('cortex.forms.allowed_mimes'); // @TODO: remove this rule as this already handled by spatie/medialibrary
        });

        return $rules;
    }
}
