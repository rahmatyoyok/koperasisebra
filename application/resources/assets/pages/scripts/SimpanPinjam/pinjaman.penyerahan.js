var FormValidationMd = function() {
    var calculateTotal = function(){
        var biaya = 0, diterima = 0;
        var pinjaman = parseFloat($('input[name=loan_amount]').val().replace(/\./g,"").replace("Rp ","").replace("",0));
        var adm_rp = parseFloat($('input[name=biaya_administrasi_rupiah]').val().replace(/\./g,"").replace("Rp ","").replace("",0));
        var prov_rp = parseFloat($('input[name=biaya_provisi_rupiah]').val().replace(/\./g,"").replace("Rp ","").replace("",0));

        
        biaya = adm_rp + prov_rp + vBDaperma + vBMaterai + vBLain;
        diterima = pinjaman - biaya;
        $('span[id=lJumlahBiaya]').text('Rp. '+addThousandSeparator(biaya));
        $('span[id=ltotalDiterima]').text('Rp. '+addThousandSeparator(diterima));
        $('input[name=totalDiterima]').val(diterima);
    };

    var calculateAngsuran = function(){
        var pinjaman    = parseFloat($('input[name=loan_amount]').val().replace(/\./g,"").replace("Rp ","").replace("",0));
        var tenor       = parseFloat($('input[name=tenure]').val().replace(/\./g,"").replace("Rp ","").replace("",0));
        
        var bungaPinjaman = (pinjaman*(vBesarBunga)*tenor);
        var jumlahAngsuranPokok = pinjaman/tenor;
        var jumlahAngsuranBunga = bungaPinjaman/tenor;

        // alert(pinjaman);

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
        $('select[name=payment_method]').on('select2:select', function (e) {
            // Do something
            var data = e.params.data;
            $('.showBank').removeClass('hide');
            if(data.text == 'Tunai'){
                $('.showBank').addClass('hide');
            }

          });
    };

    var handleMaskNumber = function(){
        $('.date-picker').datepicker({
            rtl: App.isRTL(),
            autoclose: true
        });

        $('input[name=biaya_provisi_rupiah], input[name=biaya_administrasi_rupiah]').keyup(function(){
            calculateTotal();
        });

        $('input[name=loan_amount], input[name=interest_rates], input[name=tenure]').keyup(function(){
            var pinjamans    = parseFloat($('input[name=loan_amount]').val().replace(/\./g,"").replace("Rp ","").replace("",0));
            if(pinjamans > vNilaiPinjaman){
                $('input[name=loan_amount]').val(vNilaiPinjaman)
            }

            if(vLimitPinjaman < (pinjamans+vSaldoPinjaman)){
                alert('Pengajuan Pinjaman Melebihi Batas Pinjaman');
            }

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