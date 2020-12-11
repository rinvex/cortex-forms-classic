<?php

declare(strict_types=1);

use Cortex\Forms\Models\Form;
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;
use DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator;

Breadcrumbs::register('adminarea.cortex.forms.forms.index', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/forms::common.forms'), route('adminarea.cortex.forms.forms.index'));
});

Breadcrumbs::register('adminarea.cortex.forms.forms.import', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.cortex.forms.forms.index');
    $breadcrumbs->push(trans('cortex/forms::common.import'), route('adminarea.cortex.forms.forms.import'));
});

Breadcrumbs::register('adminarea.cortex.forms.forms.import.logs', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.cortex.forms.forms.index');
    $breadcrumbs->push(trans('cortex/forms::common.import'), route('adminarea.cortex.forms.forms.import'));
    $breadcrumbs->push(trans('cortex/forms::common.logs'), route('adminarea.cortex.forms.forms.import.logs'));
});

Breadcrumbs::register('adminarea.cortex.forms.forms.create', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.cortex.forms.forms.index');
    $breadcrumbs->push(trans('cortex/forms::common.create_form'), route('adminarea.cortex.forms.forms.create'));
});

Breadcrumbs::register('adminarea.cortex.forms.forms.edit', function (BreadcrumbsGenerator $breadcrumbs, Form $form) {
    $breadcrumbs->parent('adminarea.cortex.forms.forms.index');
    $breadcrumbs->push(strip_tags($form->name), route('adminarea.cortex.forms.forms.edit', ['form' => $form]));
});

Breadcrumbs::register('adminarea.cortex.forms.forms.logs', function (BreadcrumbsGenerator $breadcrumbs, Form $form) {
    $breadcrumbs->parent('adminarea.cortex.forms.forms.index');
    $breadcrumbs->push(strip_tags($form->name), route('adminarea.cortex.forms.forms.edit', ['form' => $form]));
    $breadcrumbs->push(trans('cortex/forms::common.logs'), route('adminarea.cortex.forms.forms.logs', ['form' => $form]));
});

Breadcrumbs::register('adminarea.cortex.forms.forms.responses', function (BreadcrumbsGenerator $breadcrumbs, Form $form) {
    $breadcrumbs->parent('adminarea.cortex.forms.forms.index');
    $breadcrumbs->push(strip_tags($form->name), route('adminarea.cortex.forms.forms.edit', ['form' => $form]));
    $breadcrumbs->push(trans('cortex/forms::common.responses'), route('adminarea.cortex.forms.forms.responses', ['form' => $form]));
});
