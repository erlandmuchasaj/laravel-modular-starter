@if(app()->environment('production'))
	@if (config('app.google_site_verification') && !empty(config('app.google_site_verification')))
	    <meta name="google-site-verification" content="{{ config('app.google_site_verification') }}" />
    @endif
@endif