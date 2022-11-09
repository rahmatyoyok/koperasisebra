var FormValidationMd = function() {

    var handleValidation1 = function() {
        // for more info visit the official plugin documentation: 
        // http://docs.jquery.com/Plugins/Validation
        var form1 = $('#form_sample_1');
        var error1 = $('.alert-danger', form1);
        var success1 = $('.alert-success', form1);

        form1.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "", // validate all fields including form hidden input
            messages: {
                f_jeniskelamin: {
                    required: 'Pilih salah satu',
                    minlength: jQuery.validator.format("At least {0} items must be selected"),
                },
            },
            rules: {
                first_name: {
                    minlength : 2,
                    required : true
                },
                id_card_number : {
                    minlength : 20,
                    maxlength : 20,
                    required : true
                },
                f_jeniskelamin:{
                    required : true,
                },
                born_place:{
                    required : true
                },
                born_date:{
                    required : true
                },
                account_number:{
                    required : true
                },
                company_name:{ required : true }
            },

            invalidHandler: function(event, validator) { //display error alert on form submit              
                success1.hide();
                error1.show();
                App.scrollTo(error1, -200);
            },

            errorPlacement: function(error, element) {
                if (element.is(':checkbox')) {
                    error.insertAfter(element.closest(".md-checkbox-list, .md-checkbox-inline, .checkbox-list, .checkbox-inline"));
                } else if (element.is(':radio')) {
                    error.insertAfter(element.closest(".md-radio-list, .md-radio-inline, .radio-list,.radio-inline"));
                } else {
                    error.insertAfter(element); // for other inputs, just perform default behavior
                }
            },

            highlight: function(element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').addClass('has-error'); // set error class to the control group
            },

            unhighlight: function(element) { // revert the change done by hightlight
                $(element)
                    .closest('.form-group').removeClass('has-error'); // set error class to the control group
            },

            success: function(label) {
                label
                    .closest('.form-group').removeClass('has-error'); // set success class to the control group
            },

            submitHandler: function(form) {
                success1.show();
                error1.hide();
                form.submit();
            }
        });

    }

    var inputHandle = function(){
        $('.date-picker').datepicker({
            rtl: App.isRTL(),
            autoclose: true
        });
    }

    
    return {
        //main function to initiate the module
        init: function() {
            inputHandle();
    
            handleValidation1();
        }
    };
}();

jQuery(document).ready(function() {
    FormValidationMd.init();
});