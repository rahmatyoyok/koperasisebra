var Anggota = function () {
    
    var initactionbutton = function(){
        $('button.moveLinkInvestasi').click(function(){
            var link = $(this).data('spwjbid');
            window.location.href = BaseUrl+'/simpanpinjam/investasi/prosesterima/'+link;
        });

        $("button.moveLinkPokok").on("click", function(e){
			e.preventDefault();
            var form = $(this).parents('form');
            var dt = $(this).data("spwjbid");

	        swal({
	            title: "Apakah anda yakin?",
	            text: $(this).data("swa-text"),
	            type: "warning",
	            showCancelButton: true
	        }).then(function() {
                App.blockUI();
                
	            window.location.href = BaseUrl+"/simpanpinjam/simpananpokok/prosesterima/"+dt;
	        }).catch(swal.noop);
        });
        
        $("button.moveLinkWajib").on("click", function(e){
			e.preventDefault();
            var form = $(this).parents('form');
            var dt = $(this).data("spwjbid");

	        swal({
	            title: "Apakah anda yakin?",
	            text: $(this).data("swa-text"),
	            type: "warning",
	            showCancelButton: true
	        }).then(function() {
                App.blockUI();
                
	            window.location.href = BaseUrl+"/simpanpinjam/wajib/prosesterima/"+dt;
	        }).catch(swal.noop);
        });

        $('button.moveLinkPinjaman').click(function(){
            var link = $(this).data('spwjbid');
            window.location.href = BaseUrl+'/simpanpinjam/pinjaman/prosesserah/'+link;
        });

        $('a.detailBtnPinjaman').click(function(){
            var dataid = $(this).data('id');
            
            App.blockUI({});

            $.ajax({
                url: BaseUrl+"/simpanpinjam/detail_pinjaman_json",
                dataType:'json',
                data:{ ParamId : dataid},
                success:function(response)
                {
                    App.unblockUI();
                    $('span[id=popup_noref]').text(response.header.ref_code);
                    $('span[id=popup_tgl_pengajuan]').text(response.header.tgl_pengajuan);
                    $('span[id=popup_niak]').text(response.header.niak);
                    $('span[id=popup_nama]').text(response.header.nama);
                    $('span[id=popup_companyname]').text(response.header.company_name);

                    $('span[id=popup_loan]').text(response.header.loan_amount);
                    $('span[id=popup_tenor]').text(response.header.tenure + ' Bulan');
                    $('span[id=popup_jenis_bunga]').text(response.header.jenis_bunga);

                    $('span[id=popup_administrasi]').text(response.header.biaya_administrasi_rupiah);
                    $('span[id=popup_provisi]').text(response.header.biaya_provisi_rupiah);
                    $('span[id=popup_daperma]').text(response.header.resiko_daperma);
                    $('span[id=popup_materai]').text(response.header.biaya_materai);
                    $('span[id=popup_lain]').text(response.header.biaya_lain);

                    $('span[id=popup_ttl_piutang]').text(response.header.loan_total);
                    $('span[id=popup_ttl_bunga]').text(response.header.rates_total);
                    $('span[id=popup_angsuran_pokok]').text(response.header.principal_amount);
                    $('span[id=popup_angsuran_bunga]').text(response.header.rates_amount);
                    $('span[id=popup_ttl_angsuran]').text(response.header.total_angsuran);
                    $('html, body').animate({scrollTop: '0px'}, 0);
                    App.unblockUI();


                    // if(response.return){
                    //     window.location.reload();
                    // }
                    // else{
                    //     alert('Gagal');
                    // }
                }
            });

            $('div[id=actionDetailPinjaman]').modal('show');
        });
        


    }
    
    return {

        

        //main function to initiate the module
        init: function () {
            initactionbutton();
            // initGetTotal();
        
        }

    };

}();

jQuery(document).ready(function() {
    Anggota.init();
});
