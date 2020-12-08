@push('vendor-scripts')
    <script src="{{ mix('js/formbuilder.js') }}" defer></script>
    <script src="{{ mix('js/embed.js') }}" defer></script>
@endpush

{{ Form::open(['url' => route('frontarea.cortex.forms.forms.embed.respond', ['form' => $form]), 'id' => "frontarea-cortex-forms-forms-{$form->getRouteKey()}-respond-form", 'files' => true]) }}
    <div class="formbuilder-render"></div>
{{ Form::close() }}

{{-- Scripts --}}
@include('cortex/forms::frontarea.partials.renderer')
