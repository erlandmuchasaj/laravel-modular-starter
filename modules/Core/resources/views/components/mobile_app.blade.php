{{-- allow user to pin --}}
<link rel="manifest" href="{{ url('/site.manifest.json') }}">
<link rel="apple-touch-icon" href="{{ asset('/website/images/icon.png') }}">

<!-- Add to homescreen for Chrome on Android -->
<meta name="mobile-web-app-capable" content="yes">
<meta name="application-name" content="{{ config('app.name') }}">

<!-- For iOS web apps. Delete if not needed. https://github.com/h5bp/mobile-boilerplate/issues/94 -->

<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-title" content="{{ config('app.name') }}">
<meta name="apple-mobile-web-app-status-bar-style" content="black">

{{-- For Mozilla --}}
<meta name="theme-color" content="#fafafa">
<meta name="msapplication-navbutton-color" content="#ffffff">
<meta name="msapplication-starturl" content="{{ url('/?utm_source=homescreen') }}">
