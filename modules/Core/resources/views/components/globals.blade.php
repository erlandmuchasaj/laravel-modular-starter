<script>
    const AppHelper = window.AppHelper || {};
        AppHelper.csrf = "{{ csrf_token() }}";
        AppHelper.locale = "{{ app()->getLocale() }}";
        AppHelper.languages = '{!! locales()->toJson() !!}';
        AppHelper.app_path = "{{ app_path() }}";
        AppHelper.base_path = "{{ base_path() }}";
        AppHelper.config_path = "{{ config_path() }}";
        AppHelper.lang_path = "{{ lang_path() }}";
        AppHelper.public_path = "{{ public_path() }}";
        AppHelper.resource_path = "{{ resource_path() }}";
        AppHelper.storage_path = "{{ storage_path() }}";
        AppHelper.fullBaseUrl = "{!! rtrim(url('/'), '/\\') !!}";
        AppHelper.currentUrl = "{!! url()->current() !!}";
        AppHelper.assetsDirectory = "{!! asset('/') !!}";
</script>
