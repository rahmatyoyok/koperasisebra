var SimpananPinjaman = function () {
    var total = 0; 
    var dataid = "";
    var detailPinjamaniId = "";
    var hreffAction = "";
    var approvalDesc = "";
    const bulanIndo = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus','September','Oktober','November', 'Desember'];
    var convertTanggalIndo = function(param){
        tanggal = param.split("-")[2];
        bulan = param.split("-")[1];
        tahun = param.split("-")[0];
    
        return tanggal + " " + bulanIndo[Math.abs(bulan)+1] + " " + tahun;
    }

    var initactionbutton = function(){
        
        $('a.detailBtn').click(function(){
            dataid = $(this).data('id');
            
            App.blockUI({});

            $.ajax({
                url: BaseUrl+"/simpanpinjam/detail_pinjaman_elektronik_json",
                dataType:'json',
                data:{ ParamId : dataid},
                success:function(response)
                {
                    App.unblockUI();
                    $('span[id=popup_unitdesc]').text(response.header.unit_desc);
                    $('span[id=popup_noref]').text(response.header.ref_code);
                    $('span[id=popup_tgl_pengajuan]').text(response.header.tgl_pengajuan);
                    $('span[id=popup_niak]').text(response.header.niak);
                    $('span[id=popup_nama]').text(response.header.nama);
                    $('span[id=popup_companyname]').text(response.header.company_name);

                    $('span[id=popup_loan]').text(response.header.loan_amount);
                    $('span[id=popup_tenor]').text(response.header.tenure + ' Bulan');
                    $('span[id=popup_jenis_bunga]').text(response.header.jenis_bunga);

                    $('span[id=popup_ttl_piutang]').text(response.header.loan_total);
                    $('span[id=popup_ttl_bunga]').text(response.header.rates_total);
                    $('span[id=popup_angsuran_pokok]').text(response.header.principal_amount);
                    $('span[id=popup_angsuran_bunga]').text(response.header.rates_amount);
                    $('span[id=popup_ttl_angsuran]').text(response.header.total_angsuran);


                    $('tbody[id=tableDetailAngsuran]').html('');
                    var appendAngsuran = "";
                    var noAngsuran = 1;
                    $.each(response.mdetail, function(keys, vl){
                        var tanggalbayar = vl.payment_date.length > 10 ? convertTanggalIndo(vl.payment_date.substring(0, 10)): ""; 

                        var statusss =  parseFloat(vl.status_bayar) < 2 ? "Belum Terbayar": "Terbayar"; 
                        appendAngsuran += '<tr><td>'+noAngsuran+'</td><td>'+tanggalbayar+'</td><td style="text-align:right">Rp. '+addThousandSeparator(vl.principal_amount)+'</td><td style="text-align:right">Rp. '+addThousandSeparator(vl.rates_amount)+'</td><td style="text-align:right">Rp. '+addThousandSeparator(vl.principal_amount)+'</td><td><b>'+statusss+'</b></td></tr>';

                        noAngsuran++;
                    });
                    // console.log(appendAngsuran);
                    $('tbody[id=tableDetailAngsuran]').html(appendAngsuran);

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

        $('a.approveBtn').click(function(){
            detailPinjamaniId   = '';
            hreffAction         ='';

            // var jenistransaksi = $(this).parents(':eq(2)').find('td.jenisTransaksi').html();
            var subtotal = $(this).parents(':eq(2)').find('td.subtotal').text();
            var tenor = $(this).parents(':eq(2)').find('td.tenor').text();
            
            var dataId = $(this).data('id');
            
            $('p[id=descPinjaman]').html('Pinjaman Anggota Sebesar <span><b>'+subtotal+'</b> Dengan Tenor <b>'+tenor+'</b></span>');
            detailPinjamaniId = dataId;
            hreffAction = $(this).data('hreffaction')
            $('div[id=actionApproveal]').modal('show');
        });

        $('button[id=approvePinjaman]').click(function(){
            approvalDesc = $('textarea[name=detailApprovalDesc]').val();
            App.blockUI({});

            $.ajax({
                url: BaseUrl+"/"+hreffAction,
                dataType:'json',
                data:{ ParamId : detailPinjamaniId, approveType : 1, desc : approvalDesc},
                success:function(response)
                {
                    App.unblockUI();
                    if(response.return){
                        window.location.reload();
                    }
                    else{
                        alert('Gagal');
                    }
                }
            });
        }); 

        $('button[id=rejectPinjaman]').click(function(){
            approvalDesc = $('textarea[name=detailApprovalDesc]').val();
            App.blockUI({});
            $.ajax({
                url: BaseUrl+"/"+hreffAction,
                dataType:'json',
                data:{ ParamId : detailPinjamaniId, approveType : 0, desc : approvalDesc},
                success:function(response)
                {
                    App.unblockUI();
                    if(response.return){
                        window.location.reload();
                    }
                    else{
                        alert('Gagal');
                    }
                }
            });
        }); 

        $('button.moveLink').click(function(){
            var link = $(this).data('spwjbid');
            window.location.href = BaseUrl+'/simpanpinjam/elektronik/prosesserah/'+link;
        });
    }

    var initGetTotal = function () {
            
            // $('table[id=detailSetorInvesatasi]').find('tbody td.subtotal').each(function(e){
            //     total +=parseFloat($(this).text().replace(/\./g,"").replace("Rp ",""));
                
            //     $('span[id=TotalInvestasi]').text(addThousandSeparator(total));
            // });
        }
    
         
    var Angsuran = function(){
        $('button[id=bayarAngsuran]').click(function(){
            window.location.href = BaseUrl+"/simpanpinjam/elektronik/prosesterima/"+dataid;
        });

    }

    return {

        //main function to initiate the module
        init: function () {
            initactionbutton();
            initGetTotal();
            Angsuran();
        
        }

    };

}();

jQuery(document).ready(function() {
    SimpananPinjaman.init();
});
