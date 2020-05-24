<?php

declare(strict_types=1);

Broadcast::channel('adminarea-forms-index', function ($user) {
    return $user->can('list', app('rinvex.forms.form'));
}, ['guards' => ['admin']]);
