$(function () {
    'use strict'

    $(function () {
        //Bootstrap Duallistbox
        $('.duallistbox').bootstrapDualListbox({
            nonSelectedListLabel: 'Nicht ausgewählt',
            selectedListLabel: 'Ausgewählte',
            moveOnSelect: false,
            // default text
        })

        //Bootstrap Duallistbox
        $('.duallistboxCustom').bootstrapDualListbox({
            nonSelectedListLabel: 'Nicht ausgewählt',
            selectedListLabel: 'Ausgewählte',
            moveOnSelect: false,
            // default text
        })

        $("label[for='bootstrap-duallistbox-selected-list_']").css('color','#28a745');


        $('#exportForm').submit(function(e) {


        });


    })
})
