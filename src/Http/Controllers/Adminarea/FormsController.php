<?php

declare(strict_types=1);

namespace Cortex\Forms\Http\Controllers\Adminarea;

use Exception;
use Illuminate\Http\Request;
use Cortex\Forms\Models\Form;
use Illuminate\Foundation\Http\FormRequest;
use Cortex\Foundation\DataTables\LogsDataTable;
use Cortex\Foundation\Importers\DefaultImporter;
use Cortex\Forms\DataTables\Adminarea\FormsDataTable;
use Cortex\Foundation\DataTables\ImportLogsDataTable;
use Cortex\Foundation\Http\Requests\ImportFormRequest;
use Cortex\Foundation\DataTables\ImportRecordsDataTable;
use Cortex\Forms\Http\Requests\Adminarea\FormFormRequest;
use Cortex\Foundation\Http\Controllers\AuthorizedController;
use Cortex\Forms\DataTables\Adminarea\FormResponsesDataTable;

class FormsController extends AuthorizedController
{
    /**
     * {@inheritdoc}
     */
    protected $resource = Form::class;

    /**
     * List all forms.
     *
     * @param \Cortex\Forms\DataTables\Adminarea\FormsDataTable $formsDataTable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(FormsDataTable $formsDataTable)
    {
        return $formsDataTable->with([
            'id' => 'adminarea-forms-index',
        ])->render('cortex/foundation::adminarea.pages.datatable-index');
    }

    /**
     * List form logs.
     *
     * @param \Cortex\Forms\Models\Form                   $form
     * @param \Cortex\Foundation\DataTables\LogsDataTable $logsDataTable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function logs(Form $form, LogsDataTable $logsDataTable)
    {
        return $logsDataTable->with([
            'resource' => $form,
            'tabs' => 'adminarea.forms.tabs',
            'id' => "adminarea-forms-{$form->getRouteKey()}-logs",
        ])->render('cortex/foundation::adminarea.pages.datatable-tab');
    }

    /**
     * List form responses.
     *
     * @param \Cortex\Forms\Models\Form                                 $form
     * @param \Cortex\Forms\DataTables\Adminarea\FormResponsesDataTable $responsesDataTable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function responses(Form $form, FormResponsesDataTable $formResponsesDataTable)
    {
        return $formResponsesDataTable->with([
            'resource' => $form,
            'tabs' => 'adminarea.forms.tabs',
            'id' => "adminarea-forms-{$form->getRouteKey()}-responses",
        ])->render('cortex/foundation::adminarea.pages.datatable-tab');
    }

    /**
     * Import forms.
     *
     * @param \Cortex\Forms\Models\Form                            $form
     * @param \Cortex\Foundation\DataTables\ImportRecordsDataTable $importRecordsDataTable
     *
     * @return \Illuminate\View\View
     */
    public function import(Form $form, ImportRecordsDataTable $importRecordsDataTable)
    {
        return $importRecordsDataTable->with([
            'resource' => $form,
            'tabs' => 'adminarea.forms.tabs',
            'url' => route('adminarea.forms.stash'),
            'id' => "adminarea-forms-{$form->getRouteKey()}-import",
        ])->render('cortex/foundation::adminarea.pages.datatable-dropzone');
    }

    /**
     * Stash forms.
     *
     * @param \Cortex\Foundation\Http\Requests\ImportFormRequest $request
     * @param \Cortex\Foundation\Importers\DefaultImporter       $importer
     *
     * @return void
     */
    public function stash(ImportFormRequest $request, Form $form, DefaultImporter $importer)
    {
        // Handle the import
        $importer->config['resource'] = $form;
        $importer->handleImport();
    }

    /**
     * Hoard forms.
     *
     * @param \Cortex\Foundation\Http\Requests\ImportFormRequest $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function hoard(ImportFormRequest $request)
    {
        foreach ((array) $request->get('selected_ids') as $recordId) {
            $record = app('cortex.foundation.import_record')->find($recordId);

            try {
                $fillable = collect($record['data'])->intersectByKeys(array_flip(app('rinvex.forms.form')->getFillable()))->toArray();

                tap(app('rinvex.forms.form')->firstOrNew($fillable), function ($instance) use ($record) {
                    $instance->save() && $record->delete();
                });
            } catch (Exception $exception) {
                $record->notes = $exception->getMessage().(method_exists($exception, 'getMessageBag') ? "\n".json_encode($exception->getMessageBag())."\n\n" : '');
                $record->status = 'fail';
                $record->save();
            }
        }

        return intend([
            'back' => true,
            'with' => ['success' => trans('cortex/foundation::messages.import_complete')],
        ]);
    }

    /**
     * List form import logs.
     *
     * @param \Cortex\Foundation\DataTables\ImportLogsDataTable $importLogsDatatable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function importLogs(ImportLogsDataTable $importLogsDatatable)
    {
        return $importLogsDatatable->with([
            'resource' => trans('cortex/forms::common.form'),
            'tabs' => 'adminarea.forms.tabs',
            'id' => 'adminarea-forms-import-logs',
        ])->render('cortex/foundation::adminarea.pages.datatable-tab');
    }

    /**
     * Create new form.
     *
     * @param \Illuminate\Http\Request  $request
     * @param \Cortex\Forms\Models\Form $form
     *
     * @return \Illuminate\View\View
     */
    public function create(Request $request, Form $form)
    {
        return $this->form($request, $form);
    }

    /**
     * Edit given form.
     *
     * @param \Illuminate\Http\Request  $request
     * @param \Cortex\Forms\Models\Form $form
     *
     * @return \Illuminate\View\View
     */
    public function edit(Request $request, Form $form)
    {
        return $this->form($request, $form);
    }

    /**
     * Show form create/edit form.
     *
     * @param \Illuminate\Http\Request  $request
     * @param \Cortex\Forms\Models\Form $form
     *
     * @return \Illuminate\View\View
     */
    protected function form(Request $request, Form $form)
    {
        $form['basics'] = [
            'slug' => $form->slug,
            'name' => $form->name,
            'description' => $form->description,
            'is_active' => $form->is_active,
            'is_public' => $form->is_public,
            'abilities' => $form->abilities,
            'roles' => $form->roles,
            'tags' => $form->tags,
        ];

        $tags = app('rinvex.tags.tag')->pluck('name', 'id');
        $embedCode = htmlentities('<div data-embed-src="'.route('frontarea.forms.embed', ['form' => $form]).'"></div><script src="'.url(mix('js/embed.js')).'" defer></script>', ENT_COMPAT, 'UTF-8');
        $abilities = $request->user(app('request.guard'))->getManagedAbilities();
        $roles = $request->user(app('request.guard'))->getManagedRoles();

        return view('cortex/forms::adminarea.pages.form', compact('form', 'roles', 'abilities', 'tags', 'embedCode'));
    }

    /**
     * Store new form.
     *
     * @param \Cortex\Forms\Http\Requests\Adminarea\FormFormRequest $request
     * @param \Cortex\Forms\Models\Form                             $form
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(FormFormRequest $request, Form $form)
    {
        return $this->process($request, $form);
    }

    /**
     * Update given form.
     *
     * @param \Cortex\Forms\Http\Requests\Adminarea\FormFormRequest $request
     * @param \Cortex\Forms\Models\Form                             $form
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(FormFormRequest $request, Form $form)
    {
        return $this->process($request, $form);
    }

    /**
     * Process stored/updated form.
     *
     * @param \Illuminate\Foundation\Http\FormRequest $request
     * @param \Cortex\Forms\Models\Form               $form
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function process(FormRequest $request, Form $form)
    {
        // Prepare required input fields
        $data = $request->validated();
        $data['slug'] = $data['basics']['slug'];
        $data['name'] = $data['basics']['name'];
        $data['description'] = $data['basics']['description'];
        $data['is_active'] = $data['basics']['is_active'];
        $data['is_public'] = $data['basics']['is_public'];
        $data['abilities'] = $data['basics']['abilities'];
        $data['roles'] = $data['basics']['roles'];
        $data['tags'] = $data['basics']['tags'];
        unset($data['basics']);

        // Save form
        $form->fill($data)->save();

        return intend([
            'url' => route('adminarea.forms.index'),
            'with' => ['success' => trans('cortex/foundation::messages.resource_saved', ['resource' => trans('cortex/forms::common.form'), 'identifier' => $form->getRouteKey()])],
        ]);
    }

    /**
     * Destroy given form.
     *
     * @param \Cortex\Forms\Models\Form $form
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(Form $form)
    {
        $form->delete();

        return intend([
            'url' => route('adminarea.forms.index'),
            'with' => ['warning' => trans('cortex/foundation::messages.resource_deleted', ['resource' => trans('cortex/forms::common.form'), 'identifier' => $form->getRouteKey()])],
        ]);
    }
}
