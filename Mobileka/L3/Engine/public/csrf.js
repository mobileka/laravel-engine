$(function() {
    jQuery.ajaxSetup({
        headers: {
            'x-csrf-token': $('meta[name="csrf_token"]').attr('content')
        }
    });
});