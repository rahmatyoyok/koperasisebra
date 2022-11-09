var SimpananInvestasi = function () {
    var total = 0; 
    var detailInvestasiId = "";
    var hreffAction = "";
    var approvalDesc = "";

    var initactionbutton = function(){
        
        $('a.approveBtn').click(function(){
            detailInvestasiId   = '';
            hreffAction         ='';

            // alert($(this).parents(':eq(1)').html());
            var jenistransaksi = $(this).parents(':eq(1)').find('td.jenisTransaksi').html();
            var subtotal = $(this).parents(':eq(1)').find('td.subtotal').text();
            
            var dataId = $(this).data('id');
            
            $('p[id=descInvestasi]').html(jenistransaksi+' Investasi Sebesar <span>'+subtotal+'</span>');
            detailInvestasiId = dataId;
            hreffAction = $(this).data('hreffaction')
            $('div[id=actionApproveal]').modal('show');
        });

        $('button[id=approveInvestasi]').click(function(){
            approvalDesc = $('textarea[name=detailApprovalDesc]').val();
            App.blockUI({});
            $('div[id=actionApproveal]').modal('hide');

            $.ajax({
                url: BaseUrl+"/"+hreffAction,
                dataType:'json',
                data:{ ParamId : detailInvestasiId, approveType : 1, desc : approvalDesc},
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

        $('button[id=rejectInvestasi]').click(function(){
            approvalDesc = $('textarea[name=detailApprovalDesc]').val();
            App.blockUI({});
            $.ajax({
                url: BaseUrl+"/"+hreffAction,
                dataType:'json',
                data:{ ParamId : detailInvestasiId, approveType : 0, desc : approvalDesc},
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

            var jenistransaksi = $(this).parents(':eq(1)').find('td.jenisTransaksi').data('trtype');

            if(jenistransaksi == 1){
                window.location.href = BaseUrl+'/simpanpinjam/investasi/prosesterima/'+link;
            }
            else if(jenistransaksi == 2){
                window.location.href = BaseUrl+'/simpanpinjam/investasi/prosesserah/'+link;
            }
                
        });

        
    }

    var initGetTotal = function () {
            
            $('table[id=detailSetorInvesatasi]').find('tbody td.subtotal').each(function(e){
                total +=parseFloat($(this).text().replace(/\./g,"").replace("Rp ",""));
                
                $('span[id=TotalInvestasi]').text(addThousandSeparator(total));
            });
        }


    return {

        //main function to initiate the module
        init: function () {
            initactionbutton();
            initGetTotal();
        
        }

    };

}();

jQuery(document).ready(function() {
    SimpananInvestasi.init();
});
