<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8" />
    <title>@yield('title', config('app.name'))</title>

    {{-- Meta Data --}}
    @include('cortex/foundation::common.partials.meta')
    <meta name="turbolinks-cache-control" content="no-cache">
    @stack('head-elements')

    {{-- Styles --}}
    <link href="{{ mix('css/vendor.css', 'assets') }}" rel="stylesheet">
    <link href="{{ mix('css/theme-frontarea.css', 'assets') }}" rel="stylesheet">
    <link href="{{ mix('css/app.css', 'assets') }}" rel="stylesheet">
    @stack('styles')

    {{-- Scripts --}}
    <script>
        window.Laravel = <?php echo json_encode(['csrfToken' => csrf_token()]); ?>;
        window.Accessarea = "<?php echo request()->route('accessarea'); ?>";
    </script>
    <script src="{{ mix('js/manifest.js', 'assets') }}" defer></script>
    <script src="{{ mix('js/vendor.js', 'assets') }}" defer></script>
    <script src="{{ mix('js/formbuilder.js', 'assets') }}" defer></script>
    <script src="{{ mix('js/embed.js', 'assets') }}" defer></script>
    @stack('vendor-scripts')
    <script src="{{ mix('js/app.js', 'assets') }}" defer></script>
</head>
<body class="hold-transition skin-blue fixed sidebar-mini">
    {{-- Main content --}}
    <div class="wrapper">
        {{ Form::open(['url' => route('frontarea.forms.embed.respond', ['form' => $form]), 'id' => "frontarea-forms-{$form->getRouteKey()}-respond-form", 'files' => true]) }}
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
