{{-- SEO DATA --}}
<meta name="title" content="@yield('seo_title', config('app.seo_title'))">
<meta name="description" content="@yield('seo_description', config('app.seo_description'))">
<link rel="canonical" href="@yield('canonical', url()->current())">

{{-- human - This tags are depricated --}}
<meta name="author" content="@yield('seo_author', config('app.seo_author'))">
<meta name="keyword" content="@yield('seo_keyword', config('app.seo_keyword'))">
<meta name="copyright" content="@yield('seo_copyright', config('app.seo_copyright'))">