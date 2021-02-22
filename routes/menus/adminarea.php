<?php

declare(strict_types=1);

use Cortex\Forms\Models\Form;
use Rinvex\Menus\Models\MenuItem;
use Rinvex\Menus\Models\MenuGenerator;

Menu::register('adminarea.sidebar', function (MenuGenerator $menu) {
    $menu->findByTitleOrAdd(trans('cortex/foundation::common.cms'), 40, 'fa fa-file-text-o', 'header', [], function (MenuItem $dropdown) {
        $dropdown->route(['adminarea.cortex.forms.forms.index'], trans('cortex/forms::common.forms'), 20, 'fa fa-wpforms')->ifCan('list', app('rinvex.forms.form'))->activateOnRoute('adminarea.cortex.forms.forms');
    });
});

Menu::register('adminarea.cortex.forms.forms.tabs', function (MenuGenerator $menu, Form $form) {
    $menu->route(['adminarea.cortex.forms.forms.import'], trans('cortex/forms::common.records'))->ifCan('import', $form)->if(Route::is('adminarea.cortex.forms.forms.import*'));
    $menu->route(['adminarea.cortex.forms.forms.import.logs'], trans('cortex/forms::common.logs'))->ifCan('import', $form)->if(Route::is('adminarea.cortex.forms.forms.import*'));
    $menu->route(['adminarea.cortex.forms.forms.create'], trans('cortex/forms::common.details'))->ifCan('create', $form)->if(Route::is('adminarea.cortex.forms.forms.create'));
    $menu->route(['adminarea.cortex.forms.forms.edit', ['form' => $form]], trans('cortex/forms::common.details'))->ifCan('update', $form)->if($form->exists);
    $menu->route(['adminarea.cortex.forms.forms.responses', ['form' => $form]], trans('cortex/forms::common.responses'))->ifCan('list', app('rinvex.forms.form_response'))->if($form->exists && array_key_exists('database', $form->actions ?? []));
    $menu->route(['adminarea.cortex.forms.forms.logs', ['form' => $form]], trans('cortex/forms::common.logs'))->ifCan('audit', $form)->if($form->exists);
});
