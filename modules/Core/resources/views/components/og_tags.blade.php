{{-- Twitter Card data --}}
@hasSection('twitter_site')
    <meta name="twitter:site" content="@yield('twitter_site')">
@endif

<meta name="twitter:widgets:csp" content="on">
<meta name="twitter:card" content="@yield('twitter_card', 'summary')">
<meta name="twitter:title" content="@yield('og_title', config('app.seo_title'))">
<meta name="twitter:description" content="@yield('og_description', config('app.seo_description'))">
<meta name="twitter:image" content="@yield('og_image', config('app.seo_image'))">
<meta name="twitter:url" content="@yield('og_url', url()->current())">

{{-- Facebook OpenGraph data --}}
<meta property="og:type" content="@yield('og_type', 'website')">
<meta property="og:title" content="@yield('og_title', config('app.seo_title'))">
<meta property="og:description" content="@yield('og_description', config('app.seo_description'))">
<meta property="og:image" content="@yield('og_image', config('app.seo_image'))">
<meta property="og:url" content="@yield('og_url', request()->fullUrl())">
<meta property="og:site_name" content="{{ config('app.name') }}">
<meta property="og:locale" content="{{ str_replace('_', '-', app()->getLocale()) }}">

<link rel="image_src" href="@yield('og_image', config('app.seo_image'))">
