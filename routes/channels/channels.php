<?php

declare(strict_types=1);

use Illuminate\Contracts\Auth\Access\Authorizable;

Broadcast::channel('cortex.forms.forms.index', function (Authorizable $user) {
    return $user->can('list', app('rinvex.forms.form'));
}, ['guards' => ['admin']]);
