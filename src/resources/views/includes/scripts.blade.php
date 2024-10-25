@once
    @if($type === 'css' && config('tabulator.use_cdn'))
        <link href="{{ config('tabulator.cdn.css') }}" rel="stylesheet">
    @endif

    @if($type === 'js' && config('tabulator.use_cdn'))
        <script src="{{ config('tabulator.cdn.js') }}"></script>
    @endif
@endonce