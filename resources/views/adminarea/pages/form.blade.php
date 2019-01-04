{{-- Master Layout --}}
@extends('cortex/foundation::adminarea.layouts.default')

{{-- Page Title --}}
@section('title')
    {{ extract_title(Breadcrumbs::render()) }}
@endsection

@push('head-elements')
    <meta name="turbolinks-cache-control" content="no-cache">
@endpush

@push('vendor-scripts')
    <script src="{{ mix('js/formbuilder.js') }}" defer></script>
@endpush

@push('inline-scripts')
    {!! JsValidator::formRequest(Cortex\Forms\Http\Requests\Adminarea\FormFormRequest::class)->selector("#adminarea-forms-create-form, #adminarea-forms-{$form->getRouteKey()}-update-form")->ignore('.skip-validation') !!}

    @include('cortex/forms::adminarea.partials.templates')
    @include('cortex/forms::adminarea.partials.builder')
@endpush

{{-- Main Content --}}
@section('content')

    @includeWhen($form->exists, 'cortex/foundation::common.partials.modal', ['id' => 'delete-confirmation'])

    @include('cortex/foundation::common.partials.modal', ['id' => 'formbuilder-preview'])

    <div class="content-wrapper">
        <section class="content-header">
            <h1>{{ Breadcrumbs::render() }}</h1>
        </section>

        {{-- Main content --}}
        <section class="content">

            <div class="nav-tabs-custom">
                @if($form->exists && $currentUser->can('delete', $form))
                    <div class="pull-right">
                        <a href="#" data-toggle="modal" data-target="#delete-confirmation"
                           data-modal-action="{{ route('adminarea.forms.destroy', ['form' => $form]) }}"
                           data-modal-title="{!! trans('cortex/foundation::messages.delete_confirmation_title') !!}"
                           data-modal-button="<a href='#' class='btn btn-danger' data-form='delete' data-token='{{ csrf_token() }}'><i class='fa fa-trash-o'></i> {{ trans('cortex/foundation::common.delete') }}</a>"
                           data-modal-body="{!! trans('cortex/foundation::messages.delete_confirmation_body', ['resource' => trans('cortex/forms::common.form'), 'identifier' => $form->full_name]) !!}"
                           title="{{ trans('cortex/foundation::common.delete') }}" class="btn btn-default" style="margin: 4px"><i class="fa fa-trash text-danger"></i>
                        </a>
                    </div>
                @endif
                {!! Menu::render('adminarea.forms.tabs', 'nav-tab') !!}

                <div class="tab-content">

                    <div class="tab-pane active" id="details-tab">

                        @if ($form->exists)
                            {{ Form::model($form, ['url' => route('adminarea.forms.update', ['form' => $form]), 'method' => 'put', 'id' => "adminarea-forms-{$form->getRouteKey()}-update-form"]) }}
                        @else
                            {{ Form::model($form, ['url' => route('adminarea.forms.store'), 'id' => 'adminarea-forms-create-form']) }}
                        @endif

                        {{ Form::hidden('content', '', ['class' => 'skip-validation']) }}

                            @if($form->exists)
                                <div class="row">
                                    <div class="col-md-12">

                                        <div class="form-group">
                                            {{ Form::label("adminarea-forms-{$form->getRouteKey()}-embed-form", trans('cortex/forms::common.embed_form'), ['class' => 'control-label']) }}
                                            {{ Form::text('basics[embed_form]', $embedCode, ['class' => 'form-control', 'placeholder' => trans('cortex/forms::common.embed_form'), 'id' => "adminarea-forms-{$form->getRouteKey()}-embed-form", 'readonly' => 'readonly']) }}
                                        </div>

                                    </div>
                                </div>
                            @endif

                            <div class="box-group" id="accordion">

                                <div class="panel box box-default">
                                    <div class="box-header with-border">
                                        <h4 class="box-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseBasics" aria-expanded="true" class="">
                                                {{ trans('cortex/forms::common.basics') }}
                                            </a>
                                        </h4>
                                    </div>

                                    <div id="collapseBasics" class="panel-collapse collapse in" aria-expanded="true">

                                        <div class="box-body">

                                            <div class="row">

                                                <div class="col-md-4">

                                                    {{-- Name --}}
                                                    <div class="form-group{{ $errors->has('basics.name') ? ' has-error' : '' }}">
                                                        {{ Form::label('basics[name]', trans('cortex/forms::common.name'), ['class' => 'control-label']) }}
                                                        {{ Form::text('basics[name]', null, ['class' => 'form-control', 'placeholder' => trans('cortex/forms::common.name'), 'data-slugify' => '[name="basics[slug]"]', 'required' => 'required', 'autofocus' => 'autofocus']) }}

                                                        @if ($errors->has('basics.name'))
                                                            <span class="help-block">{{ $errors->first('basics.name') }}</span>
                                                        @endif
                                                    </div>

                                                </div>

                                                <div class="col-md-4">

                                                    {{-- Slug --}}
                                                    <div class="form-group{{ $errors->has('basics.slug') ? ' has-error' : '' }}">
                                                        {{ Form::label('basics[slug]', trans('cortex/forms::common.slug'), ['class' => 'control-label']) }}
                                                        {{ Form::text('basics[slug]', null, ['class' => 'form-control', 'placeholder' => trans('cortex/forms::common.slug'), 'required' => 'required']) }}

                                                        @if ($errors->has('basics.slug'))
                                                            <span class="help-block">{{ $errors->first('basics.slug') }}</span>
                                                        @endif
                                                    </div>

                                                </div>

                                                <div class="col-md-4">

                                                    {{-- Is Active --}}
                                                    <div class="form-group{{ $errors->has('basics.is_active') ? ' has-error' : '' }}">
                                                        {{ Form::label('basics[is_active]', trans('cortex/forms::common.is_active'), ['class' => 'control-label']) }}
                                                        {{ Form::select('basics[is_active]', [1 => trans('cortex/forms::common.yes'), 0 => trans('cortex/forms::common.no')], null, ['class' => 'form-control select2', 'data-minimum-results-for-search' => 'Infinity', 'data-width' => '100%', 'required' => 'required']) }}

                                                        @if ($errors->has('basics.is_active'))
                                                            <span class="help-block">{{ $errors->first('basics.is_active') }}</span>
                                                        @endif
                                                    </div>

                                                </div>

                                            </div>

                                            <div class="row">

                                                <div class="col-md-4">

                                                    {{-- Is Public --}}
                                                    <div class="form-group{{ $errors->has('basics.is_public') ? ' has-error' : '' }}">
                                                        {{ Form::label('basics[is_public]', trans('cortex/forms::common.is_public'), ['class' => 'control-label']) }}
                                                        {{ Form::select('basics[is_public]', [1 => trans('cortex/forms::common.yes'), 0 => trans('cortex/forms::common.no')], null, ['class' => 'form-control select2', 'data-minimum-results-for-search' => 'Infinity', 'data-width' => '100%', 'required' => 'required']) }}

                                                        @if ($errors->has('basics.is_public'))
                                                            <span class="help-block">{{ $errors->first('basics.is_public') }}</span>
                                                        @endif
                                                    </div>

                                                </div>

                                                @can('assign', \Cortex\Auth\Models\Role::class)

                                                    <div class="col-md-4">

                                                        {{-- Roles --}}
                                                        <div class="form-group{{ $errors->has('basics.roles') ? ' has-error' : '' }}">
                                                            {{ Form::label('basics[roles][]', trans('cortex/auth::common.roles'), ['class' => 'control-label']) }}
                                                            {{ Form::hidden('basics[roles]', '', ['class' => 'skip-validation']) }}
                                                            {{ Form::select('basics[roles][]', $roles, null, ['class' => 'form-control select2', 'placeholder' => trans('cortex/auth::common.select_roles'), 'multiple' => 'multiple', 'data-close-on-select' => 'false', 'data-width' => '100%']) }}

                                                            @if ($errors->has('basics.roles'))
                                                                <span class="help-block">{{ $errors->first('basics.roles') }}</span>
                                                            @endif
                                                        </div>

                                                    </div>

                                                @endcan

                                                @can('grant', \Cortex\Auth\Models\Ability::class)

                                                    <div class="col-md-4">

                                                        {{-- Abilities --}}
                                                        <div class="form-group{{ $errors->has('basics.abilities') ? ' has-error' : '' }}">
                                                            {{ Form::label('basics[abilities][]', trans('cortex/auth::common.abilities'), ['class' => 'control-label']) }}
                                                            {{ Form::hidden('basics[abilities]', '', ['class' => 'skip-validation']) }}
                                                            {{ Form::select('basics[abilities][]', $abilities, null, ['class' => 'form-control select2', 'placeholder' => trans('cortex/auth::common.select_abilities'), 'multiple' => 'multiple', 'data-close-on-select' => 'false', 'data-width' => '100%']) }}

                                                            @if ($errors->has('basics.abilities'))
                                                                <span class="help-block">{{ $errors->first('basics.abilities') }}</span>
                                                            @endif
                                                        </div>

                                                    </div>

                                                @endcan

                                            </div>

                                            <div class="row">

                                                <div class="col-md-12">

                                                    {{-- Tags --}}
                                                    <div class="form-group{{ $errors->has('basics.tags') ? ' has-error' : '' }}">
                                                        {{ Form::label('basics[tags][]', trans('cortex/pages::common.tags'), ['class' => 'control-label']) }}
                                                        {{ Form::hidden('basics[tags]', '', ['class' => 'skip-validation']) }}
                                                        {{ Form::select('basics[tags][]', $tags, null, ['class' => 'form-control select2', 'multiple' => 'multiple', 'data-width' => '100%', 'data-tags' => 'true']) }}

                                                        @if ($errors->has('basics.tags'))
                                                            <span class="help-block">{{ $errors->first('basics.tags') }}</span>
                                                        @endif
                                                    </div>

                                                </div>

                                            </div>

                                            <div class="row">

                                                <div class="col-md-12">

                                                    {{-- Description --}}
                                                    <div class="form-group{{ $errors->has('basics.description') ? ' has-error' : '' }}">
                                                        {{ Form::label('basics[description]', trans('cortex/forms::common.description'), ['class' => 'control-label']) }}
                                                        {{ Form::textarea('basics[description]', null, ['class' => 'form-control tinymce', 'placeholder' => trans('cortex/forms::common.description'), 'rows' => 5]) }}

                                                        @if ($errors->has('basics.description'))
                                                            <span class="help-block">{{ $errors->first('basics.description') }}</span>
                                                        @endif
                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                                <div class="panel box box-default">
                                    <div class="box-header with-border">
                                        <h4 class="box-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseFormbuilder" class="collapsed" aria-expanded="false">
                                                {{ trans('cortex/forms::common.formbuilder') }}
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseFormbuilder" class="panel-collapse collapse" aria-expanded="false">
                                        <div class="box-body">

                                            <div class="row">

                                                <div class="col-md-12">

                                                    <div class="formbuilder"></div>

                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="panel box box-default">
                                    <div class="box-header with-border">
                                        <h4 class="box-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseSubmission" class="collapsed" aria-expanded="false">
                                                {{ trans('cortex/forms::common.submission') }}
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseSubmission" class="panel-collapse collapse" aria-expanded="false">
                                        <div class="box-body">

                                            <div class="row">

                                                <div class="col-md-6">

                                                    {{-- On Success Action --}}
                                                    <div class="form-group{{ $errors->has('submission.on_success.action') ? ' has-error' : '' }}">
                                                        {{ Form::label('submission[on_success][action]', trans('cortex/forms::common.on_success'), ['class' => 'control-label']) }}
                                                        {{ Form::select('submission[on_success][action]', ['show_message' => trans('cortex/forms::common.show_message'), 'redirect_to' => trans('cortex/forms::common.redirect_to')], null, ['class' => 'form-control select2', 'data-minimum-results-for-search' => 'Infinity', 'data-width' => '100%', 'required' => 'required']) }}

                                                        @if ($errors->has('submission.on_success.action'))
                                                            <span class="help-block">{{ $errors->first('submission.on_success.action') }}</span>
                                                        @endif
                                                    </div>

                                                </div>

                                                <div class="col-md-6">

                                                    {{-- On Failure Action --}}
                                                    <div class="form-group{{ $errors->has('submission.on_failure.action') ? ' has-error' : '' }}">
                                                        {{ Form::label('submission[on_failure][action]', trans('cortex/forms::common.on_failure'), ['class' => 'control-label']) }}
                                                        {{ Form::select('submission[on_failure][action]', ['show_message' => trans('cortex/forms::common.show_message'), 'redirect_to' => trans('cortex/forms::common.redirect_to')], null, ['class' => 'form-control select2', 'data-minimum-results-for-search' => 'Infinity', 'data-width' => '100%', 'required' => 'required']) }}

                                                        @if ($errors->has('submission.on_failure.action'))
                                                            <span class="help-block">{{ $errors->first('submission.on_failure.action') }}</span>
                                                        @endif
                                                    </div>

                                                </div>

                                            </div>

                                            <div class="row">

                                                <div class="col-md-6">

                                                    {{-- On Success Content --}}
                                                    <div class="form-group{{ $errors->has('submission.on_success.content') ? ' has-error' : '' }}">
                                                        {{ Form::textarea('submission[on_success][content]', null, ['class' => 'form-control tinymce', 'placeholder' => trans('cortex/forms::common.content'), 'rows' => 5, 'required' => 'required']) }}

                                                        @if ($errors->has('submission.on_success.content'))
                                                            <span class="help-block">{{ $errors->first('submission.on_success.content') }}</span>
                                                        @endif
                                                    </div>

                                                </div>

                                                <div class="col-md-6">

                                                    {{-- On Failure Content --}}
                                                    <div class="form-group{{ $errors->has('submission.on_failure.content') ? ' has-error' : '' }}">
                                                        {{ Form::textarea('submission[on_failure][content]', null, ['class' => 'form-control tinymce', 'placeholder' => trans('cortex/forms::common.content'), 'rows' => 5, 'required' => 'required']) }}

                                                        @if ($errors->has('submission.on_failure.content'))
                                                            <span class="help-block">{{ $errors->first('submission.on_failure.content') }}</span>
                                                        @endif
                                                    </div>

                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="panel box box-default">
                                    <div class="box-header with-border">
                                        <h4 class="box-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseActions" class="collapsed" aria-expanded="false">
                                                {{ trans('cortex/forms::common.actions') }}
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseActions" class="panel-collapse collapse" aria-expanded="false">
                                        <div class="box-body">

                                            <div class="row">

                                                <div class="col-md-12">

                                                    <button class="btn btn-primary" id="emailBtn" data-template="email" type="button"><i class="fa fa-envelope"></i> {{ trans('cortex/forms::common.send_email') }}</button>
                                                    <button class="btn btn-primary" id="apiBtn" data-template="api" type="button"><i class="fa fa-external-link"></i> @lang('cortex/forms::common.call_api')</button>
                                                    <button class="btn btn-primary" id="databaseBtn" data-template="database" type="button"><i class="fa fa-database"></i> @lang('cortex/forms::common.store_in_database')</button>

                                                </div>

                                            </div>

                                            <hr />

                                            <div class="row">

                                                <div class="col-md-12">

                                                    <div id="actions-container"></div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-12">

                                    <div class="pull-right">
                                        <a href="#" data-target="#formbuilder-preview" id="formbuilder-preview-button"
                                           data-modal-title="{!! trans('cortex/forms::common.formbuilder_preview') !!}"
                                           data-modal-body="<div class='formbuilder-render'></div>"
                                           title="{{ trans('cortex/forms::common.preview') }}" class="btn btn-info btn-flat" style="margin: 4px">{{ trans('cortex/forms::common.preview') }}
                                        </a>
                                        {{ Form::button(trans('cortex/forms::common.submit'), ['class' => 'btn btn-primary btn-flat', 'type' => 'submit', 'id' => 'formbuilder-submit-button']) }}
                                    </div>

                                    @include('cortex/foundation::adminarea.partials.timestamps', ['model' => $form])

                                </div>

                            </div>

                        {{ Form::close() }}

                    </div>

                </div>

            </div>

        </section>

    </div>

@endsection
