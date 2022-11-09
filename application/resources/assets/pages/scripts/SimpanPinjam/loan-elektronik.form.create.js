var FormValidationMd = function() {
    
    
    var calculateAngsuran = function(){
        var pinjaman    = parseFloat($('input[name=loan_amount]').val().replace(/\./g,"").replace("Rp ","").replace("",0));
        var tenor       = parseFloat($('input[name=tenure]').val().replace(/\./g,"").replace("Rp ","").replace("",0));
        var bunga       = parseFloat($('input[name=interest_rates]').val().replace(/\,/g,".").replace("Rp ","").replace("",0));

        // var bungaPinjaman = pinjaman + ((pinjaman*(bunga/100)*tenor));
        var bungaPinjaman = (pinjaman*(bunga/100)*tenor);
        var totalPinjaman = bungaPinjaman+pinjaman;
        // var jumlahAngsuran = jumlahPinjaman/tenor;
        var jumlahAngsuranPokok = pinjaman/tenor;
        var jumlahAngsuranBunga = bungaPinjaman/tenor;


        $('input[name=rates_loan_total_label]').val(Math.ceil(bungaPinjaman));
        $('input[name=rates_loan_total]').val(Math.ceil(bungaPinjaman));

        $('input[name=loan_total_label]').val(Math.ceil(totalPinjaman));
        $('input[name=loan_total]').val(Math.ceil(totalPinjaman));
        

        $('input[name=principal_amount_label]').val(Math.ceil(jumlahAngsuranPokok));
        $('input[name=principal_amount]').val(Math.ceil(jumlahAngsuranPokok));
        $('input[name=rates_amount_label]').val(Math.ceil(jumlahAngsuranBunga));
        $('input[name=rates_amount]').val(Math.ceil(jumlahAngsuranBunga));

        $('input[name=angsuran_total_amount_label]').val(Math.ceil(jumlahAngsuranPokok + jumlahAngsuranBunga));
    }
    
    var handleselect2 = function(){
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

            App.unblockUI();
        });
    };

    var handleMaskNumber = function(){
        $('.date-picker').datepicker({
            rtl: App.isRTL(),
            autoclose: true
        });

        $('input[name=loan_amount], input[name=interest_rates], input[name=tenure]').keyup(function(){
            calculateAngsuran();
        });


        
    };

return {
        //main function to initiate the module
        init: function() {
            
            handleselect2();
            handleMaskNumber();
        }
    };
}();

jQuery(document).ready(function() {
    FormValidationMd.init();
});