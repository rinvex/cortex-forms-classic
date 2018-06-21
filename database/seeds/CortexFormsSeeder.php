<?php

declare(strict_types=1);

use Illuminate\Database\Seeder;

class CortexFormsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Bouncer::allow('admin')->to('list', config('rinvex.forms.models.form'));
        Bouncer::allow('admin')->to('import', config('rinvex.forms.models.form'));
        Bouncer::allow('admin')->to('create', config('rinvex.forms.models.form'));
        Bouncer::allow('admin')->to('update', config('rinvex.forms.models.form'));
        Bouncer::allow('admin')->to('delete', config('rinvex.forms.models.form'));
        Bouncer::allow('admin')->to('audit', config('rinvex.forms.models.form'));

        Bouncer::allow('admin')->to('list', config('rinvex.forms.models.form_response'));
        Bouncer::allow('admin')->to('import', config('rinvex.forms.models.form_response'));
        Bouncer::allow('admin')->to('create', config('rinvex.forms.models.form_response'));
        Bouncer::allow('admin')->to('update', config('rinvex.forms.models.form_response'));
        Bouncer::allow('admin')->to('delete', config('rinvex.forms.models.form_response'));
        Bouncer::allow('admin')->to('audit', config('rinvex.forms.models.form_response'));
    }
}
