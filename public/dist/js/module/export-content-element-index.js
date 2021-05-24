$(function () {
    'use strict'

    $(function() {

        $('.downloadButton').on('click', function () {
            let $this = $(this);
            let id  = $this.data("id");
            let url  = $this.data("url");
            $.ajax({
                url: url,
                type: 'POST',
                data:{
                    'id': id,
                },
                success: function() {
                    toastr.success('Die Extension heruntergeladen wird')
                },
                error: function() {
                    toastr.error('Oops, an error occurred');
                }
            });

        });
    })

})
