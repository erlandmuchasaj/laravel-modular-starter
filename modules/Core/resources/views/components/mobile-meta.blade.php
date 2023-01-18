{{-- allow user to pin --}}
<link rel="manifest" href="{{ asset('site.webmanifest') }}">
<link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
<link rel="mask-icon" color="#5bbad5" href="{{ asset('safari-pinned-tab.svg') }}">

<!-- Add to homescreen for Chrome on Android -->
<meta name="mobile-web-app-capable" content="yes">
<meta name="application-name" content="{{ config('app.name') }}">

<!-- For iOS web apps. Delete if not needed. https://github.com/h5bp/mobile-boilerplate/issues/94 -->
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-title" content="{{ config('app.name') }}">
<meta name="apple-mobile-web-app-status-bar-style" content="black">

{{-- For Mozilla --}}
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="theme-color" content="#ffffff">
<meta name="msapplication-navbutton-color" content="#ffffff">
<meta name="msapplication-starturl" content="{{ url('/?utm_source=homescreen') }}">
