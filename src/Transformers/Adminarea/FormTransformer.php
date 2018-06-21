<?php

declare(strict_types=1);

namespace Cortex\Forms\Transformers\Adminarea;

use Cortex\Forms\Models\Form;
use Rinvex\Support\Traits\Escaper;
use League\Fractal\TransformerAbstract;

class FormTransformer extends TransformerAbstract
{
    use Escaper;

    /**
     * @return array
     */
    public function transform(Form $form): array
    {
        return $this->escape([
            'id' => (string) $form->getRouteKey(),
            'name' => (string) $form->name,
            'slug' => (string) $form->slug,
            'responses' => (int) $form->responses->count(),
            'is_active' => (bool) $form->is_active,
            'is_public' => (bool) $form->is_public,
            'created_at' => (string) $form->created_at,
            'updated_at' => (string) $form->updated_at,
        ]);
    }
}
