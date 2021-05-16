$(function () {
    'use strict'

    $(function() {
        $('.custom-control-input').change(function() {
            let val = this.checked;
            let id  = $(this).data("id");
            console.log(id);
            console.log(val);
            $.ajax({
                url: "/admin/tbs-module-revision/change-status",
                type: 'post',
                success: function(response) {
                    var json = $.parseJSON(response);
                    //object javascrit
                    console.log(response)

                },
                error: function(reponse) {

                }
            });
        });
    })
});
