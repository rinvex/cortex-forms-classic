<?php

declare(strict_types=1);

use Cortex\Forms\Models\Form;
use Rinvex\Menus\Models\MenuItem;
use Cortex\Forms\Models\FormResponse;
use Rinvex\Menus\Models\MenuGenerator;

Menu::register('adminarea.sidebar', function (MenuGenerator $menu, Form $form) {
    $menu->findByTitleOrAdd(trans('cortex/foundation::common.cms'), 40, 'fa fa-file-text-o', [], function (MenuItem $dropdown) use ($form) {
        $dropdown->route(['adminarea.forms.index'], trans('cortex/forms::common.forms'), 20, 'fa fa-wpforms')->ifCan('list', $form)->activateOnRoute('adminarea.forms');
    });
});

Menu::register('adminarea.forms.tabs', function (MenuGenerator $menu, Form $form, FormResponse $formResponse) {
    $menu->route(['adminarea.forms.import'], trans('cortex/forms::common.records'))->ifCan('import', $form)->if(Route::is('adminarea.forms.import*'));
    $menu->route(['adminarea.forms.import.logs'], trans('cortex/forms::common.logs'))->ifCan('import', $form)->if(Route::is('adminarea.forms.import*'));
    $menu->route(['adminarea.forms.create'], trans('cortex/forms::common.details'))->ifCan('create', $form)->if(Route::is('adminarea.forms.create'));
    $menu->route(['adminarea.forms.edit', ['form' => $form]], trans('cortex/forms::common.details'))->ifCan('update', $form)->if($form->exists);
    $menu->route(['adminarea.forms.responses', ['form' => $form]], trans('cortex/forms::common.responses'))->ifCan('list', $formResponse)->if($form->exists && array_key_exists('database', $form->actions ?? []));
    $menu->route(['adminarea.forms.logs', ['form' => $form]], trans('cortex/forms::common.logs'))->ifCan('audit', $form)->if($form->exists);
});
