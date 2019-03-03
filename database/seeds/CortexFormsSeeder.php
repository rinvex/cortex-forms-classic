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
        $abilities = [
            ['name' => 'list', 'title' => 'List forms', 'entity_type' => 'form'],
            ['name' => 'import', 'title' => 'Import forms', 'entity_type' => 'form'],
            ['name' => 'create', 'title' => 'Create forms', 'entity_type' => 'form'],
            ['name' => 'update', 'title' => 'Update forms', 'entity_type' => 'form'],
            ['name' => 'delete', 'title' => 'Delete forms', 'entity_type' => 'form'],
            ['name' => 'audit', 'title' => 'Audit forms', 'entity_type' => 'form'],

            ['name' => 'list', 'title' => 'List form response', 'entity_type' => 'form_response'],
            ['name' => 'import', 'title' => 'Import form response', 'entity_type' => 'form_response'],
            ['name' => 'create', 'title' => 'Create form response', 'entity_type' => 'form_response'],
            ['name' => 'update', 'title' => 'Update form response', 'entity_type' => 'form_response'],
            ['name' => 'delete', 'title' => 'Delete form response', 'entity_type' => 'form_response'],
            ['name' => 'audit', 'title' => 'Audit form response', 'entity_type' => 'form_response'],
        ];

        collect($abilities)->each(function (array $ability) {
            app('cortex.auth.ability')->create($ability);
        });
    }
}
