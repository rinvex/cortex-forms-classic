<script>
    window.addEventListener('turbolinks:load', function() {

        let FormBuilderInit = function(window,$) {
            $('.formbuilder-render').formRender({
                formData: '{!! is_array($form->content) ? json_encode($form->content) : $form->content !!}',
                dataType: 'json'
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
