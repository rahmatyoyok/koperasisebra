var FormValidationPenarikan = function() {
    var saldoakhir = 0;
    var handleselect2 = function(){
        $('select[name=f_payment_method]').select2();

        $('select[name=person_id]').select2({
            minimumInputLength: 0,
            placeholder:"Pilih Anggota",
            ajax: {
                url: BaseUrl+"/api/select2/personInvestasi",
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
            saldoakhir = 0;
            saldoakhir = data.saldo_investasi;
            $('input[name=saldo]').val(data.saldo_investasi);
            // $.getJSON( BaseUrl+"/api/table/saldoinvesatasi?q="+data.personIdencrypt, function( datas ) {
            //     if(datas.status == 'success'){
            //         saldoakhir = datas.saldo;
            //         $('input[name=saldo]').val(datas.saldo);
            //     }
            //   });
    
            App.unblockUI();
        });
    }

    var handleMaskNumber = function(){
        $('.date-picker').datepicker({
            rtl: App.isRTL(),
            autoclose: true
        });

        $('input[name=total]').keyup(function(){
            // var saldo = parseFloat($('input[name=saldo]').val().replace(/\./g,"").replace("Rp ",""));
            var saldo = saldoakhir - parseFloat($(this).val().replace(/\./g,"").replace("Rp ",""));

            if($(this).val() == ''){
                saldo = saldoakhir;
            }
            
            $('input[name=saldo]').val(saldo)
        });
    }

    
    return {
        //main function to initiate the module
        init: function() {
            
            handleselect2();
            handleMaskNumber();
        }
    };
}();

jQuery(document).ready(function() {
    FormValidationPenarikan.init();
});
