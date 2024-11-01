(function ($) {
    $(document).ready(function () {
        $(document).on('click', '#inserttopost', function () {
            var targetdomain = window.location.toString().split("/")[0] + "//" + window.location.toString().split("/")[2];
            var embedline = $('.code-textarea.active').val();
            parent.postMessage("ygvideoembed|" + embedline, targetdomain);
        });

        $(document).on('change', '#code-type', function () {
            if ($(this).val() == 'iframe') {
                $('#js-code').removeClass('active');
                $('#html-code').addClass('active');
               $('#iframe-settings').removeClass('d-none');
            } else {
                $('#js-code').addClass('active');
                $('#html-code').removeClass('active');
                $('#iframe-settings').addClass('d-none');
            }
        });
        
        $(document).on('change', '.size-inp', function () {
            var width = $('#width').val();
            var height = $('#height').val();
            var embedCode = $('#html-code').val();
            $('#html-code').val(embedCode.replace(/(\[.*."\])/, '[ygplayer type="iframe" width="'+width+'" height="'+height+'"]'));
        });
    });
})(jQuery);
