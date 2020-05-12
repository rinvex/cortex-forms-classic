<?php

declare(strict_types=1);

use Cortex\Forms\Models\Form;
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;
use DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator;

Breadcrumbs::register('frontarea.forms.index', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('frontarea.home'));
    $breadcrumbs->push(trans('cortex/forms::common.forms'), route('frontarea.forms.index'));
});

Breadcrumbs::register('frontarea.forms.show', function (BreadcrumbsGenerator $breadcrumbs, Form $form) {
    $breadcrumbs->parent('frontarea.forms.index');
    $breadcrumbs->push(strip_tags($form->name), route('frontarea.forms.show', ['form' => $form]));
});

Breadcrumbs::register('frontarea.forms.embed', function (BreadcrumbsGenerator $breadcrumbs, Form $form) {
    $breadcrumbs->parent('frontarea.forms.index');
    $breadcrumbs->push(strip_tags($form->name), route('frontarea.forms.embed', ['form' => $form]));
});
