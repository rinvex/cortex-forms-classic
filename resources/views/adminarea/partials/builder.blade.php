<script>
    function inject_action_template(template, reverseOrder = false) {
        let uniqueId = Math.random().toString(36).slice(2);
        let newActionTemplate = $('#'+template).html().replace(/UNIQUEID/g, uniqueId);
        $('#actions-container').find('.panel-collapse').collapse('hide');
        reverseOrder ? $('#actions-container').prepend(newActionTemplate) : $('#actions-container').append(newActionTemplate);

        // Implicit Laravel validation rules does NOT work with `proengsoft/laravel-jsvalidation`
        // so we are attaching validation rules manually for all input fields in the newly added template
        $('#actions-container').find('.panel-collapse').first().find('input, textarea, select').not('input[name*="unique_field"]').each(function(index, element) {
            $('#actions-container').closest('form').validate();

            if ($(this).is('select')) {
                $(this).select2();
            }

            $(this).rules('add',{
                required: true,
            });
        });

        $('#actions-container').find('.panel-collapse').first().collapse('show');
        highlight_required();

        return uniqueId;
    }

    window.addEventListener('turbolinks:load', function() {

        // Select form embed code on focus or click
        $("[id*='-embed-form']").on('focus click keypress', function (event) {
            event.preventDefault();
            $(this).select();
        });

        let FormBuilderInit = function(window,$) {

            let FBOptions = {
                sortableControls: true,
                disabledAttrs: ['access'],
                stickyControls: {
                    enable: true,
                    offset: {
                        top: 50,
                    },
                },
                disabledActionButtons: ['data', 'clear', 'save'],
            };

            let FBContent = '{!! old('content', (is_array($form->content) ? json_encode($form->content) : $form->content)) !!}';
            let FBActions = JSON.parse('{!! json_encode(old('actions', $form->actions)) !!}');

            if (FBContent) {
                FBOptions.formData = FBContent;
            }

            let FormBuilder = $('.formbuilder').formBuilder(FBOptions);

            if (FBActions) {
                for (let action in FBActions) {
                    for (let item in FBActions[action]) {
                        let uniqueId = inject_action_template(action);

                        for (let field in FBActions[action][item]) {
                            $("[name='actions["+action+"]["+uniqueId+"]["+field+"]']").val(FBActions[action][item][field]);
                        }
                    }
                }
            }

            $('#formbuilder-preview-button').click(function (event) {
                event.preventDefault();

                let button = $(this); // Button that triggered the modal
                let modalTitle = button.data('modal-title');
                let modalBody = button.data('modal-body');

                let modal = $('#formbuilder-preview');
                modal.find('.modal-body').html(modalBody);
                modal.find('.modal-title').html(modalTitle);

                let FBRender = $('.formbuilder-render');

                $(FBRender).formRender({
                    formData: FormBuilder.actions.getData('json', true),
                    dataType: 'json'
                });

                modal.modal('show');
            });

            $('#formbuilder-submit-button').click(function (event) {
                $('[name="content"]').val(FormBuilder.actions.getData('json'));
                $('[name="content"]').closest('form').submit();
                highlight_errored_accordion();
            });

            $('#emailBtn, #apiBtn, #databaseBtn').click(function (event) {
                inject_action_template($(this).data('template'), true);
            });

            $(document).on('click', '.removePanel', function () {
                let element = $(this);
                let panel = element.closest('div.template-panel');

                panel.remove();
            });
        }

        // Fired only once when turbolinks enabled (the very begining visit or hard refresh)
        $(document).on('formbuilder.ready', function () {
            FormBuilderInit(window, $);
        });

        // Assigned after the first load of the page or hard refresh
        if (window.FormBuilderReady) {
            FormBuilderInit(window, $);
        }

    });
</script>
