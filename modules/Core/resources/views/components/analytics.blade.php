@if(app()->environment('production'))
	@if (config("app.google_analytic") && config("app.google_analytic") != "UA-XXXXX-X")
	    <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('app.google_analytic') }}"></script>
	    <script>
	        window.dataLayer = window.dataLayer || [];
	        function gtag() {dataLayer.push(arguments);}
	        gtag('js', new Date());
	        gtag('config', '{{ config('app.google_analytic') }}', {'anonymizeIp': true});
	    </script>
    @endif
@endif