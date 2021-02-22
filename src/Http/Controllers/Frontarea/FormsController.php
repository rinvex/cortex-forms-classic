<?php

declare(strict_types=1);

namespace Cortex\Forms\Http\Controllers\Frontarea;

use Exception;
use Illuminate\Support\Arr;
use Cortex\Forms\Models\Form;
use GuzzleHttp\Client as HttpClient;
use Illuminate\Support\Facades\Mail;
use Cortex\Foundation\Http\FormRequest;
use Cortex\Forms\Http\Requests\Frontarea\FormFormRequest;
use Cortex\Foundation\Http\Controllers\AbstractController;

class FormsController extends AbstractController
{
    /**
     * Show given form.
     *
     * @param \Cortex\Forms\Models\Form $form
     *
     * @return \Illuminate\View\View
     */
    protected function show(Form $form)
    {
        return view('cortex/forms::frontarea.pages.form', compact('form'));
    }

    /**
     * Embed given form.
     *
     * @param \Cortex\Forms\Models\Form $form
     *
     * @return \Illuminate\View\View
     */
    protected function embed(Form $form)
    {
        $includeVendors = true;

        return view('cortex/forms::frontarea.pages.embed', compact('form', 'includeVendors'));
    }

    /**
     * Respond to given form.
     *
     * @param \Cortex\Forms\Http\Requests\Frontarea\FormFormRequest $request
     * @param \Cortex\Forms\Models\Form                             $form
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function respond(FormFormRequest $request, Form $form)
    {
        try {
            foreach ($form->actions as $key => $actions) {
                foreach ($actions as $action) {
                    $this->{$key.'ActionHandler'}($request, $form, $action);
                }
            }

            $action = Arr::get($form->submission, 'on_success.action');
            $content = Arr::get($form->submission, 'on_success.content');

            switch ($action) {
                case 'redirect_to':
                    return intend([
                        'url' => $content,
                        'with' => ['success' => trans('cortex/forms::message.response_sent')],
                    ]);

                default:
                case 'show_message':
                    return intend([
                        'back' => true,
                        'with' => ['success' => $content],
                    ]);
            }
        } catch (Exception $exception) {
            logger($exception->getMessage().' - '.$exception->getTraceAsString());
            $action = Arr::get($form->submission, 'on_failure.action');
            $content = Arr::get($form->submission, 'on_failure.content');

            switch ($action) {
                case 'redirect_to':
                    return intend([
                        'url' => $content,
                        'with' => ['error' => trans('cortex/forms::message.response_exception')],
                    ]);

                default:
                case 'show_message':
                    return intend([
                        'back' => true,
                        'with' => ['error' => $content],
                    ]);
            }
        }
    }

    /**
     * Handle email action.
     *
     * @param \Cortex\Foundation\Http\FormRequest $request
     * @param \Cortex\Forms\Models\Form               $form
     * @param array                                   $action
     *
     * @throws \Exception
     *
     * @return void
     */
    protected function emailActionHandler(FormRequest $request, Form $form, $action)
    {
        $data = $request->validated();

        $to = explode(',', Arr::get($action, 'to', []));
        $subject = Arr::get($action, 'subject', null);
        $body = Arr::get($action, 'body', null);

        if (empty($to) || empty($subject) || empty($body)) {
            throw new Exception(trans('cortex/forms::messages.invalid_parameters'));
        }

        $body = $this->replaceFieldsInContent($body, $data);

        Mail::send('cortex/forms::emails.response', compact('body'), function ($message) use ($to, $subject) {
            $message->to($to)->subject($subject);
        });
    }

    /**
     * Handle api action.
     *
     * @param \Cortex\Foundation\Http\FormRequest $request
     * @param \Cortex\Forms\Models\Form               $form
     * @param array                                   $action
     *
     * @throws \Exception
     *
     * @return void
     */
    protected function apiActionHandler(FormRequest $request, Form $form, $action)
    {
        $data = $request->validated();

        $method = mb_strtolower(Arr::get($action, 'method', null));
        $endPoint = Arr::get($action, 'end_point', null);
        $body = Arr::get($action, 'body', null);

        if (empty($endPoint) || empty($method) || empty($body)) {
            throw new Exception(trans('cortex/forms::messages.invalid_parameters'));
        }

        $httpClient = new HttpClient();
        $paramsType = $method === 'post' ? 'body' : 'query';
        $body = $this->replaceFieldsInContent($body, $data);

        $httpClient->{$method}($endPoint, [
            $paramsType => json_decode($body, true),
            'headers' => [
                'Accept' => 'application/json',
                'Content-type' => 'application/json',
            ],
        ]);
    }

    /**
     * Handle database action.
     *
     * @param \Cortex\Foundation\Http\FormRequest $request
     * @param \Cortex\Forms\Models\Form               $form
     * @param array                                   $action
     *
     * @throws \Exception
     *
     * @return void
     */
    protected function databaseActionHandler(FormRequest $request, Form $form, $action)
    {
        $data = $request->validated();

        $responseData = ['unique_identifier' => null];

        if (! empty($uniqueField = Arr::get($action, 'unique_field'))) {
            $uniqueFieldData = Arr::get($data, $uniqueField, null);
            $uniqueResponse = $form->responses()->where('unique_identifier', $uniqueFieldData)->first();

            if (! $uniqueFieldData || $uniqueResponse) {
                throw new \Exception(trans('cortex/forms::messages.unique_constraint', ['unique' => $uniqueField]));
            }

            $responseData['unique_identifier'] = $uniqueFieldData;
        }

        $responseData['content'] = $data;

        $responseRecord = $form->responses()->create($responseData);

        foreach ($request->all() as $key => $value) {
            ! $request->hasFile($key) || $responseRecord->addMediaFromRequest($key)->sanitizingFileName(function ($fileName) {
                return md5($fileName).'.'.pathinfo($fileName, PATHINFO_EXTENSION);
            })->withCustomProperties(['field' => $key])->toMediaCollection('form_response', config('cortex.forms.media.disk'));
        }
    }

    /**
     * Replace fields in content.
     *
     * @param string $content
     * @param array  $fields
     *
     * @return string
     */
    protected function replaceFieldsInContent(string $content, array $fields): string
    {
        foreach ($fields as $key => $field) {
            ! is_array($field) || $field = implode(', ', $field);
            $content = preg_replace('/\['.$key.'\]/', $field, $content);
        }

        return $content;
    }
}
