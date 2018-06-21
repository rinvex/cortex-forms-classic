{{-- Email Action Template --}}
<script type="text/html" id="email">

    <div class="panel panel-default template-panel">

        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#action-accordion" href="#UNIQUEID">
                    <i class="fa fa-envelope"></i> {{ trans('cortex/forms::common.send_email') }}
                </a>
                <button class="btn btn-xs btn-danger pull-right removePanel" data-panel="UNIQUEID" type="button"><i class="fa fa-remove"></i></button>
            </h4>
        </div>

        <div id="UNIQUEID" class="panel-collapse collapse">
            <div class="panel-body">

                <div class="row">

                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('actions[email][UNIQUEID][to]', trans('cortex/forms::common.email_to'), ['class' => 'control-label']) }}
                            {{ Form::text('actions[email][UNIQUEID][to]', null, ['class' => 'form-control', 'placeholder' => trans('cortex/forms::common.email_to'), 'required' => 'required']) }}
                            <small class="text-muted">{{ trans('cortex/forms::common.to_desc') }}</small>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="form-group">
                            {{ Form::label('actions[email][UNIQUEID][subject]', trans('cortex/forms::common.email_subject'), ['class' => 'control-label']) }}
                            {{ Form::text('actions[email][UNIQUEID][subject]', null, ['class' => 'form-control', 'placeholder' => trans('cortex/forms::common.email_subject'), 'required' => 'required']) }}
                        </div>
                    </div>

                </div>

                <div class="row">

                    <div class="col-md-12">
                        <div class="form-group">
                            {{ Form::label('actions[email][UNIQUEID][body]', trans('cortex/forms::common.email_body'), ['class' => 'control-label']) }}
                            {{ Form::textarea('actions[email][UNIQUEID][body]', null, ['class' => 'form-control', 'placeholder' => trans('cortex/forms::common.email_body'), 'rows' => 5, 'required' => 'required']) }}
                            <small class="text-muted">@lang('cortex/forms::common.body_desc')</small>
                        </div>
                    </div>

                </div>

            </div>
        </div>

    </div>

</script>

{{-- API Action Template --}}
<script type="text/html" id="api">

    <div class="panel panel-default template-panel">

        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#action-accordion" href="#UNIQUEID">
                    <i class="fa fa-external-link"></i> @lang('cortex/forms::common.call_api')
                </a>
                <button class="btn btn-xs btn-danger pull-right removePanel" data-panel="UNIQUEID" type="button"><i class="fa fa-remove"></i></button>
            </h4>
        </div>

        <div id="UNIQUEID" class="panel-collapse collapse">
            <div class="panel-body">

                <div class="row">

                    <div class="col-md-4">

                        <div class="form-group">
                            {{ Form::label('actions[api][UNIQUEID][method]', trans('cortex/forms::common.api_request_method'), ['class' => 'control-label']) }}
                            {{ Form::select('actions[api][UNIQUEID][method]', ['POST' => trans('cortex/forms::common.api_request_method_post'), 'GET' => trans('cortex/forms::common.api_request_method_get')], null, ['class' => 'form-control select2', 'data-minimum-results-for-search' => 'Infinity', 'data-width' => '100%', 'required' => 'required']) }}
                        </div>

                    </div>

                    <div class="col-md-8">

                        <div class="form-group">
                            {{ Form::label('actions[api][UNIQUEID][end_point]', trans('cortex/forms::common.api_request_end_point'), ['class' => 'control-label']) }}
                            {{ Form::text('actions[api][UNIQUEID][end_point]', null, ['class' => 'form-control', 'placeholder' => trans('cortex/forms::common.api_request_end_point'), 'required' => 'required']) }}
                        </div>

                    </div>

                </div>

                <div class="row">

                    <div class="col-md-12">

                        <div class="form-group">
                            {{ Form::label('actions[api][UNIQUEID][body]', trans('cortex/forms::common.api_request_body'), ['class' => 'control-label']) }}
                            {{ Form::textarea('actions[api][UNIQUEID][body]', null, ['class' => 'form-control', 'placeholder' => trans('cortex/forms::common.api_request_body'), 'rows' => 5, 'required' => 'required']) }}
                            <small class="text-muted">@lang('cortex/forms::common.body_desc')</small>
                        </div>

                    </div>

                </div>

            </div>
        </div>

    </div>

</script>

{{-- Database Action Template --}}
<script type="text/html" id="database">

    <div class="panel panel-default template-panel">

        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#action-accordion" href="#UNIQUEID">
                    <i class="fa fa-database"></i> @lang('cortex/forms::common.store_in_database')
                </a>
                <button class="btn btn-xs btn-danger pull-right removePanel" data-panel="UNIQUEID" type="button"><i class="fa fa-remove"></i></button>
            </h4>
        </div>

        <div id="UNIQUEID" class="panel-collapse collapse">
            <div class="panel-body">

                <div class="row">

                    <div class="col-md-12">

                        <div class="form-group">
                            {{ Form::label('actions[database][UNIQUEID][unique_field]', trans('cortex/forms::common.database_unique_identifier'), ['class' => 'control-label']) }}
                            {{ Form::text('actions[database][UNIQUEID][unique_field]', null, ['class' => 'form-control', 'placeholder' => trans('cortex/forms::common.database_unique_identifier')]) }}
                            <small class="text-muted ">This field will be used to avoid duplicates, if not filled there will be no duplication detection.</small>
                        </div>

                    </div>

                </div>

            </div>
        </div>

    </div>

</script>
