<?php

declare(strict_types=1);

Route::domain(domain())->group(function () {
    Route::name('frontarea.')
         ->middleware(['web'])
         ->namespace('Cortex\Forms\Http\Controllers\Frontarea')
         ->prefix(config('cortex.foundation.route.locale_prefix') ? '{locale}/'.config('cortex.foundation.route.prefix.frontarea') : config('cortex.foundation.route.prefix.frontarea'))->group(function () {

        // Forms Routes
             Route::name('cortex.forms.forms.')->prefix('forms')->group(function () {
                 Route::get('/')->name('index')->uses('FormsController@index');
                 Route::get('{form}')->name('show')->uses('FormsController@show');
                 Route::post('{form}')->name('show.respond')->uses('FormsController@respond');
                 Route::get('{form}/embed')->name('embed')->uses('FormsController@embed');
                 Route::post('{form}/embed')->name('embed.respond')->uses('FormsController@respond');
             });
         });
});
