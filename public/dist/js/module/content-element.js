$(function () {
    'use strict'



    $(function($) {
        const fbTemplate = $('#fb-editor');
        const options = {
            disableFields: [
                'autocomplete',
                'paragraph',
                'header',
                'hidden',
                'button',
            ],
            disabledActionButtons: [
                'data',
                'save'
            ],
            controlOrder: [
                'text',
                'textarea',
                'number',
                'select',
                'checkbox-group',
                'radio-group',
                'date',
                'file',
            ]
        };

        let formData = $('#content_elements_formData').val();
        const formBuilder = $(fbTemplate).formBuilder(options);

        if ((null != formData) || ("" !== formData)){
            setTimeout(function(){ formBuilder.actions.setData(formData); }, 500);
        }

        $("#saveData").on("click", (e) => {
            e.preventDefault();
            const result = formBuilder.actions.getData('json');

            $("#content_elements_formData").val(result);
            $('#formCe').validate({
                errorElement: 'span',
                errorPlacement: function (error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });

            $("#formCe").submit();
        });

    });

})
