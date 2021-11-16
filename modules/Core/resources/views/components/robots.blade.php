@if(config('app.debug') == true)
    <meta name="robots" content="noindex,nofollow">
@else
    @hasSection('meta-robots')
        @yield('meta-robots')
    @else
        <meta name="robots" content="all"/>
    @endif
@endif
