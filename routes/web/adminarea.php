<?php

declare(strict_types=1);

Route::domain(domain())->group(function () {
    Route::name('adminarea.')
         ->namespace('Cortex\Forms\Http\Controllers\Adminarea')
         ->middleware(['web', 'nohttpcache', 'can:access-adminarea'])
         ->prefix(config('cortex.foundation.route.locale_prefix') ? '{locale}/'.config('cortex.foundation.route.prefix.adminarea') : config('cortex.foundation.route.prefix.adminarea'))->group(function () {

        // Forms Routes
             Route::name('cortex.forms.forms.')->prefix('forms')->group(function () {
                 Route::match(['get', 'post'], '/')->name('index')->uses('FormsController@index');
                 Route::get('import')->name('import')->uses('FormsController@import');
                 Route::post('import')->name('stash')->uses('FormsController@stash');
                 Route::post('hoard')->name('hoard')->uses('FormsController@hoard');
                 Route::get('import/logs')->name('import.logs')->uses('FormsController@importLogs');
                 Route::get('create')->name('create')->uses('FormsController@create');
                 Route::post('create')->name('store')->uses('FormsController@store');
                 Route::get('{form}')->name('show')->uses('FormsController@show');
                 Route::get('{form}/edit')->name('edit')->uses('FormsController@edit');
                 Route::put('{form}/edit')->name('update')->uses('FormsController@update');
                 Route::get('{form}/logs')->name('logs')->uses('FormsController@logs');
                 Route::get('{form}/responses')->name('responses')->uses('FormsController@responses');
                 Route::delete('{form}')->name('destroy')->uses('FormsController@destroy');
             });
         });
});
