<?php

declare(strict_types=1);

use Cortex\Forms\Models\Form;
use Diglactic\Breadcrumbs\Generator;
use Diglactic\Breadcrumbs\Breadcrumbs;

Breadcrumbs::register('frontarea.cortex.forms.forms.index', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('frontarea.home'));
    $breadcrumbs->push(trans('cortex/forms::common.forms'), route('frontarea.cortex.forms.forms.index'));
});

Breadcrumbs::register('frontarea.cortex.forms.forms.show', function (Generator $breadcrumbs, Form $form) {
    $breadcrumbs->parent('frontarea.cortex.forms.forms.index');
    $breadcrumbs->push(strip_tags($form->name), route('frontarea.cortex.forms.forms.show', ['form' => $form]));
});

Breadcrumbs::register('frontarea.cortex.forms.forms.embed', function (Generator $breadcrumbs, Form $form) {
    $breadcrumbs->parent('frontarea.cortex.forms.forms.index');
    $breadcrumbs->push(strip_tags($form->name), route('frontarea.cortex.forms.forms.embed', ['form' => $form]));
});
