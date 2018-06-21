{{-- Master Layout --}}
@extends('cortex/foundation::frontarea.layouts.default')

{{-- Page Title --}}
@section('title')
    {{ extract_title(Breadcrumbs::render()) }}
@endsection

@push('vendor-scripts')
    <script src="{{ mix('js/formbuilder.js', 'assets') }}" defer></script>
    <script src="{{ mix('js/embed.js', 'assets') }}" defer></script>
@endpush

@push('inline-scripts')
    @include('cortex/forms::frontarea.partials.renderer')
@endpush

@section('body-attributes')data-spy="scroll" data-offset="0" data-target="#navigation"@endsection

{{-- Main Content --}}
@section('content')
    <div id="intro">
        <div class="container">
            <div class="row">
                <div class="formbuilder-render"></div>
            </div>
        </div>
    </div>
@endsection
