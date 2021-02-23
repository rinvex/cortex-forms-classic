<?php

declare(strict_types=1);

namespace Cortex\Forms\Http\Requests\Adminarea;

use Rinvex\Support\Traits\Escaper;
use Cortex\Foundation\Http\FormRequest;

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
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $data = $this->all();

        // Set abilities
        if (! empty($data['abilities'])) {
            if ($this->user()->can('grant', \Cortex\Auth\Models\Ability::class)) {
                $abilities = array_map('intval', $this->get('abilities', []));
                $data['abilities'] = $this->user()->isA('superadmin') ? $abilities
                    : $this->user()->getAbilities()->pluck('id')->intersect($abilities)->toArray();
            } else {
                unset($data['abilities']);
            }
        }

        // Set roles
        if (! empty($data['roles'])) {
            if ($data['roles'] && $this->user()->can('assign', \Cortex\Auth\Models\Role::class)) {
                $roles = array_map('intval', $this->get('roles', []));
                $data['roles'] = $this->user()->isA('superadmin') ? $roles
                    : $this->user()->roles->pluck('id')->intersect($roles)->toArray();
            } else {
                unset($data['roles']);
            }
        }

        $data['content'] = json_decode($data['content'] ?? '', true);

        $this->replace($data);
    }

    /**
     * Configure the validator instance.
     *
     * @param \Illuminate\Validation\Validator $validator
     *
     * @return void
     */
    public function withValidator($validator): void
    {
        // Sanitize input data before submission
        $this->replace($this->escape($this->all()));

        $validator->after(function ($validator) {
            collect(['content', 'submission', 'actions'])->each(function ($item) use ($validator) {
                ! empty($this->get($item)) || $validator->errors()->add('actions', trans('cortex/forms::messages.missing_'.$item));
            });
        });
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'basics.slug' => 'required|alpha_dash|max:150',
            'basics.name' => 'required|string|strip_tags|max:150',
            'basics.description' => 'nullable|string|max:32768',
            'basics.is_active' => 'sometimes|boolean',
            'basics.is_public' => 'sometimes|boolean',
            'basics.abilities' => 'nullable|array',
            'basics.roles' => 'nullable|array',
            'basics.tags' => 'nullable|array',
            'content' => 'required|array',
            'submission.on_success.action' => 'required|in:show_message,redirect_to',
            'submission.on_success.content' => 'required|string|max:150',
            'submission.on_failure.action' => 'required|in:show_message,redirect_to',
            'submission.on_failure.content' => 'required|string|max:150',
            'actions.email.*.to' => 'sometimes|required|string|strip_tags|max:150',
            'actions.email.*.subject' => 'sometimes|required|string|strip_tags|max:150',
            'actions.email.*.body' => 'sometimes|required|string|max:32768',
            'actions.api.*.method' => 'sometimes|required|string|strip_tags|max:150',
            'actions.api.*.end_point' => 'sometimes|required|string|strip_tags|max:1500',
            'actions.api.*.body' => 'sometimes|required|string|max:32768',
            'actions.database.*.to' => 'sometimes|required|string|strip_tags|max:150',
        ];
    }
}
