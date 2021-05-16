$(function () {
    'use strict'

    $(function() {
        $('.custom-control-input').change(function() {
            let $this = $(this);
            let status = this.checked;
            let id  = $this.data("id");
            let url  = $this.data("url");
            $.ajax({
                url: url,
                type: 'POST',
                data:{
                    'id': id,
                    'status': status,
                },
                success: function() {
                    let span = $this
                        .closest('tr')
                        .children('td')
                        .find('.badge');
                    if (true === span.hasClass('badge-danger')){
                      span.addClass('badge-success').removeClass('badge-danger');
                      span.text('Geprüft');
                    }else{
                        span.addClass('badge-danger').removeClass('badge-success');
                        span.text('Noch nicht geprüft');
                    }
                    toastr.success('Der Status hat sich erfolgreich geändert, das Modul kann exportiert werden')
                },
                error: function() {
                    toastr.error('Oops, an error occurred');
                }
            });
        });
    })
});
