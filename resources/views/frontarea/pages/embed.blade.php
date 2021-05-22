<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8" />
    <title>@yield('title', config('app.name'))</title>

    {{-- Meta Data --}}
    @include('cortex/foundation::frontarea.partials.meta')
    <meta name="turbolinks-cache-control" content="no-cache">
    @stack('head-elements')

    {{-- Styles --}}
    <link href="{{ mix('css/vendor.css') }}" rel="stylesheet">
    <link href="{{ mix('css/theme-frontarea.css') }}" rel="stylesheet">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    @stack('styles')

    {{-- Scripts --}}
    <script>
        window.Laravel = <?php echo json_encode(['csrfToken' => csrf_token()]); ?>;
        window.Accessarea = "<?php echo request()->accessarea(); ?>";
    </script>
    <script src="{{ mix('js/manifest.js') }}" defer></script>
    <script src="{{ mix('js/vendor.js') }}" defer></script>
    <script src="{{ mix('js/formbuilder.js') }}" defer></script>
    <script src="{{ mix('js/embed.js') }}" defer></script>
    @stack('vendor-scripts')
    <script src="{{ mix('js/app.js') }}" defer></script>
</head>
<body class="hold-transition skin-blue fixed sidebar-mini">
    {{-- Main content --}}
    <div class="wrapper">
        {{ Form::open(['url' => route('frontarea.cortex.forms.forms.embed.respond', ['form' => $form]), 'id' => "frontarea-cortex-forms-forms-{$form->getRouteKey()}-respond-form", 'files' => true]) }}
            <div class="formbuilder-render"></div>
        {{ Form::close() }}
    </div>

    {{-- Scripts --}}
    @include('cortex/forms::frontarea.partials.renderer')
    @stack('inline-scripts')

    {{-- Alerts --}}
    @alerts('default')
</body>
</html>
