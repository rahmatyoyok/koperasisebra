var FormValidationMd = function() {
    var varLimitPinjaman = 30000000;
    var calculateTotal = function(){
        var biaya = 0, diterima = 0;
        var pinjaman = parseFloat($('input[name=loan_amount]').val().replace(/\./g,"").replace("Rp ","").replace("",0));
        var adm_pr = parseFloat($('input[name=biaya_administrasi_persentase]').val().replace(/\,/g,".").replace("Rp ","").replace("",0));
        var adm_rp = parseFloat($('input[name=biaya_administrasi_rupiah]').val().replace(/\./g,"").replace("Rp ","").replace("",0));

        var prov_pr = parseFloat($('input[name=biaya_provisi_persentase]').val().replace(/\,/g,".").replace("Rp ","").replace("",0));
        var prov_rp = parseFloat($('input[name=biaya_provisi_rupiah]').val().replace(/\./g,"").replace("Rp ","").replace("",0));

        var daperma = parseFloat($('input[name=resiko_daperma]').val().replace(/\./g,"").replace("Rp ","").replace("",0));
        var materai = parseFloat($('input[name=biaya_materai]').val().replace(/\./g,"").replace("Rp ","").replace("",0));
        var biaya_lain = parseFloat($('input[name=biaya_lain]').val().replace(/\./g,"").replace("Rp ","").replace("",0));

        if(adm_pr != 0){
            adm_rp = pinjaman * (adm_pr/100);
            $('input[name=biaya_administrasi_rupiah]').val(adm_rp);
        }

        if(prov_pr != 0){
            adprov_rpm_rp = pinjaman * (prov_pr/100);
            $('input[name=biaya_provisi_rupiah]').val(prov_rp);
        }

        biaya = adm_rp + prov_rp + daperma + materai + biaya_lain;
        diterima = pinjaman - biaya;
        $('input[name=totalBiaya_label]').val(biaya);
        $('input[name=totalDiterima_label]').val(diterima);
    };

    var calculateAngsuran = function(){
        var pinjaman    = parseFloat($('input[name=loan_amount]').val().replace(/\./g,"").replace("Rp ","").replace("",0));
        var tenor       = parseFloat($('input[name=tenure]').val().replace(/\./g,"").replace("Rp ","").replace("",0));
        var bunga       = parseFloat($('input[name=interest_rates]').val().replace(/\,/g,".").replace("Rp ","").replace("",0));

        // var bungaPinjaman = pinjaman + ((pinjaman*(bunga/100)*tenor));
        var bungaPinjaman = (pinjaman*(bunga/100)*tenor);
        // var jumlahAngsuran = jumlahPinjaman/tenor;
        var jumlahAngsuranPokok = pinjaman/tenor;
        var jumlahAngsuranBunga = bungaPinjaman/tenor;

        $('input[name=principal_loan_total_label]').val(Math.ceil(pinjaman));
        $('input[name=principal_loan_total]').val(Math.ceil(pinjaman));
        $('input[name=rates_loan_total_label]').val(Math.ceil(bungaPinjaman));
        $('input[name=rates_loan_total]').val(Math.ceil(bungaPinjaman));
        $('input[name=total_hutang_label]').val(Math.ceil(bungaPinjaman) +  Math.ceil(pinjaman));


        $('input[name=principal_amount_label]').val(Math.ceil(jumlahAngsuranPokok));
        $('input[name=principal_amount]').val(Math.ceil(jumlahAngsuranPokok));
        $('input[name=rates_amount_label]').val(Math.ceil(jumlahAngsuranBunga));
        $('input[name=rates_amount]').val(Math.ceil(jumlahAngsuranBunga));
        $('input[name=total_angsuran_label]').val(Math.ceil(jumlahAngsuranPokok) +  Math.ceil(jumlahAngsuranBunga));
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
            varLimitPinjaman = parseFloat(data.customer.credit_limit);
            $('input[name=f_name]').val(data.first_name +' '+data.last_name);
            $('input[name=f_unit_kerja]').val(data.customer.company_name);
            $('input[name=f_credit_limit]').val(varLimitPinjaman);
            $('input[name=f_saldo_pinjaman]').val(parseFloat(data.saldo_pinjaman));
            

            console.log(varLimitPinjaman);

            App.unblockUI();
        });


        $('select[name=loan_type_id]').select2().select2("val", '5');
        $('select[name=loan_type_id]').select2({
            minimumInputLength: 0,
            placeholder:"Pilih Jenis Bunga",
            ajax: {
                url: BaseUrl+"/api/select2/jenisbungapinjaman",
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
                                text: dt.name + ' - (Bunga : '+(dt.interest_rates*100)+'%)',
                                id: dt.loan_type_id,
                                jumlahBunga : dt.interest_rates,
                                dataparsing : dt
                            }
                        }),
                    };
                },
                
            },
            templateSelection: function (result) {
                return result.text;
              },

        }).on('select2:select', function (e) {
            App.blockUI();
            var data = e.params.data.dataparsing; 

            $('input[name=interest_type_label]').val(data.interest_type);
            $('input[name=interest_type]').val(data.interest_type);
            $('input[name=interest_rates_label]').val(data.interest_rates);
            $('input[name=interest_rates]').val(data.interest_rates*100);


            calculateAngsuran();
            App.unblockUI();
        });
    };

    var handleMaskNumber = function(){
        $('.date-picker').datepicker({
            rtl: App.isRTL(),
            autoclose: true
        });

        $('input[name=biaya_administrasi_persentase], input[name=biaya_administrasi_rupiah], input[name=biaya_provisi_persentase], input[name=biaya_provisi_rupiah], input[name=resiko_daperma], input[name=biaya_materai], input[name=biaya_lain]').keyup(function(){
            calculateTotal();
        });

        $('input[name=loan_amount], input[name=interest_rates], input[name=tenure]').keyup(function(){
            calculateTotal();
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