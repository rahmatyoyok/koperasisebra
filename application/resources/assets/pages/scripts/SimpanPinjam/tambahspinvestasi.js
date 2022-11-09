var FormValidationMd = function() {

    var handleValidation1 = function() {
        // for more info visit the official plugin documentation: 
        // http://docs.jquery.com/Plugins/Validation
        var form1 = $('#formSpInvestasi');
        var error1 = $('.alert-danger', form1);
        var success1 = $('.alert-success', form1);

        form1.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "", // validate all fields including form hidden input
            rules: {
                f_tanggal:{ required : true },
                f_niak:{ required : true },
                f_total:{ required : true },
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

    var handleselect2 = function(){
        $('select[name=f_payment_method]').select2();

        $('select[name=person_id]').select2({
            minimumInputLength: 0,
            placeholder:"Pilih Anggota",
            ajax: {
                url: BaseUrl+"/api/select2/person",
                dataType: 'json',
                cache: false,
                data: function (params) {
                    return { q: $.trim(params.term), page : 1 };
                },
                processResults: function (data, param) {
                    param.page = param.page || 1;

                    return { 
                        results: $.map(data.results, function (dt) {
                            return {
                                text: dt.niak + ' - '+ dt.first_name +' '+dt.last_name +' ( '+dt.customer.company_name+' ) ',
                                id: dt.person_id,
                                niak : dt.niak,
                                dataparsing : dt
                            }
                        }),
                    };
                },
                
            },
            templateSelection: function (result) {
                return result.niak;
              },

        }).on('select2:select', function (e) {

            App.blockUI();
            var data = e.params.data.dataparsing;
            

            $('input[name=f_name]').val(data.first_name +' '+data.last_name);
            $('input[name=f_unit_kerja]').val(data.customer.company_name);
            
            $('input[name=saldo]').val(0);
            $.getJSON( BaseUrl+"/api/table/saldoinvesatasi?q="+data.personIdencrypt, function( datas ) {
                if(datas.status == 'success'){
                    $('input[name=saldo]').val(datas.saldo);
                }
              });
    
            App.unblockUI();
        });
    }

    var handleMaskNumber = function(){
        $('.date-picker').datepicker({
            rtl: App.isRTL(),
            autoclose: true
        });

        $('input[name=f_total]').maskNumber({
            thousands: '.',
            integer: true,
        });
    }

    
    return {
        //main function to initiate the module
        init: function() {
            // handleValidation1();
            handleselect2();
            handleMaskNumber();
        }
    };
}();

jQuery(document).ready(function() {
    FormValidationMd.init();
});
