$(function () {
    'use strict'

    $(function () {
        bsCustomFileInput.init();
    });
    $(function() {
        $('.item').matchHeight();
    });
    //triggered when modal is about to be shown
    $('#descriptionModal').on('show.bs.modal', function(e) {

        //get data-id attribute of the clicked element
        let description = $(e.relatedTarget).data('description');

        //populate the textbox
        $(e.currentTarget).find('#description').append(description);
    });

});
