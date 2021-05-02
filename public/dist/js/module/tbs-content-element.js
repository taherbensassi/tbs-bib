$(function () {
    'use strict'

    $(function () {
        $(document).on('click', '[data-toggle="lightbox"]', function(event) {
            event.preventDefault();
            $(this).ekkoLightbox({
                alwaysShowClose: true
            });
        });


    })
    $(function () {
        bsCustomFileInput.init();
    });

});
