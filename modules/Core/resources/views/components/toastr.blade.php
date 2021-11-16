<script>
    var toastr_conifg = {!! Session::has('toastr') ? Session::get('toastr') : 'null' !!};
    var param = $.extend({
        type: 'info',
        title: null,
        message: "{{ __('Something went wrong') }}"
    }, toastr_conifg);

    if (toastr_conifg != null) {
        if (window.toastr != undefined) {
            toastr.options = {
                closeButton: true,
                newestOnTop: true,
                progressBar: true,
            }

            if (param.type === 'warning') {
                toastr.warning(param.message, param.title);
            }
            
            if (param.type === 'success') {
                toastr.success(param.message, param.title);
            }

            if (param.type === 'error') {
                toastr.error(param.message, param.title);
            }

            if (param.type === 'info') {
                toastr.info(param.message, param.title);
            }
        }
    }
</script>