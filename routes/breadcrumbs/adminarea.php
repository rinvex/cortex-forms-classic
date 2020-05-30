<?php

declare(strict_types=1);

use Cortex\Forms\Models\Form;
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;
use DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator;

Breadcrumbs::register('adminarea.forms.index', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/forms::common.forms'), route('adminarea.forms.index'));
});

Breadcrumbs::register('adminarea.forms.import', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.forms.index');
    $breadcrumbs->push(trans('cortex/forms::common.import'), route('adminarea.forms.import'));
});

Breadcrumbs::register('adminarea.forms.import.logs', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.forms.index');
    $breadcrumbs->push(trans('cortex/forms::common.import'), route('adminarea.forms.import'));
    $breadcrumbs->push(trans('cortex/forms::common.logs'), route('adminarea.forms.import.logs'));
});

Breadcrumbs::register('adminarea.forms.create', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.forms.index');
    $breadcrumbs->push(trans('cortex/forms::common.create_form'), route('adminarea.forms.create'));
});

Breadcrumbs::register('adminarea.forms.edit', function (BreadcrumbsGenerator $breadcrumbs, Form $form) {
    $breadcrumbs->parent('adminarea.forms.index');
    $breadcrumbs->push(strip_tags($form->name), route('adminarea.forms.edit', ['form' => $form]));
});

Breadcrumbs::register('adminarea.forms.logs', function (BreadcrumbsGenerator $breadcrumbs, Form $form) {
    $breadcrumbs->parent('adminarea.forms.index');
    $breadcrumbs->push(strip_tags($form->name), route('adminarea.forms.edit', ['form' => $form]));
    $breadcrumbs->push(trans('cortex/forms::common.logs'), route('adminarea.forms.logs', ['form' => $form]));
});

Breadcrumbs::register('adminarea.forms.responses', function (BreadcrumbsGenerator $breadcrumbs, Form $form) {
    $breadcrumbs->parent('adminarea.forms.index');
    $breadcrumbs->push(strip_tags($form->name), route('adminarea.forms.edit', ['form' => $form]));
    $breadcrumbs->push(trans('cortex/forms::common.responses'), route('adminarea.forms.responses', ['form' => $form]));
});
