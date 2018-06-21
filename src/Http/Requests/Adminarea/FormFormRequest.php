<?php

declare(strict_types=1);

namespace Cortex\Forms\Http\Requests\Adminarea;

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
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $data = $this->all();

        // Set abilities
        if (! empty($data['abilities'])) {
            if ($this->user($this->route('guard'))->can('grant', \Cortex\Auth\Models\Ability::class)) {
                $abilities = array_map('intval', $this->get('abilities', []));
                $data['abilities'] = $this->user($this->route('guard'))->can('superadmin') ? $abilities
                    : $this->user($this->route('guard'))->getAbilities()->pluck('id')->intersect($abilities)->toArray();
            } else {
                unset($data['abilities']);
            }
        }

        // Set roles
        if (! empty($data['roles'])) {
            if ($data['roles'] && $this->user($this->route('guard'))->can('assign', \Cortex\Auth\Models\Role::class)) {
                $roles = array_map('intval', $this->get('roles', []));
                $data['roles'] = $this->user($this->route('guard'))->can('superadmin') ? $roles
                    : $this->user($this->route('guard'))->roles->pluck('id')->intersect($roles)->toArray();
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
            'basics.slug' => 'required|string',
            'basics.name' => 'required|string|max:150',
            'basics.description' => 'nullable|string|max:10000',
            'basics.is_active' => 'sometimes|boolean',
            'basics.is_public' => 'sometimes|boolean',
            'basics.abilities' => 'nullable|array',
            'basics.roles' => 'nullable|array',
            'basics.tags' => 'nullable|array',
            'content' => 'required|array',
            'submission.on_success.action' => 'required|in:show_message,redirect_to',
            'submission.on_success.content' => 'required|string',
            'submission.on_failure.action' => 'required|in:show_message,redirect_to',
            'submission.on_failure.content' => 'required|string',
            'actions.email.*.to' => 'sometimes|required|string',
            'actions.email.*.subject' => 'sometimes|required|string',
            'actions.email.*.body' => 'sometimes|required|string',
            'actions.api.*.method' => 'sometimes|required|string',
            'actions.api.*.end_point' => 'sometimes|required|string',
            'actions.api.*.body' => 'sometimes|required|string',
            'actions.database.*.to' => 'sometimes|required|string',
        ];
    }
}
