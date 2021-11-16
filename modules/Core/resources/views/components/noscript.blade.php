<!-- Google Tag Manager (noscript) -->
@if(app()->environment('production'))
    @if (config("app.google_tagmanager") && config("app.google_tagmanager") != "GTM-XXXXXXX")
        <noscript>
            <iframe src="https://www.googletagmanager.com/ns.html?id={{ config("app.google_tagmanager") }}"
                    height="0" width="0" style="display:none;visibility:hidden"></iframe>
        </noscript>
    @endif
@endif
<!-- End Google Tag Manager (noscript) -->
