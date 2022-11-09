var TableDatatablesResponsive = function () {
    var dataid = null;
    var actionInit = function(){
        $('a.deletehistory').click(function(e){
            e.preventDefault();
            dataid = null;
            dataid = $(this).data('id');
            
            $('span[id=periodeid]').text($(this).data('periode'));
            $('div[id=pertanyaanPenyusutan]').modal('show');
        });

        $('button[id=hapusPenyusutan]').click(function(e){
            e.preventDefault();
            App.blockUI({});
            $('div[id=pertanyaanPenyusutan]').modal('hide');
            $.ajax({
                url: BaseUrl+"/akuntansi/penyusutan/hapusPostingPenyusutan",
                dataType:'json',
                data:{ deleteId : dataid },
                success:function(r){
                    if(r.responses == true){
                        window.location.reload();
                    }
                    else{
                        $('div[id=stack3]').modal('show');
                    }
                        
                    App.unblockUI();
                }
            }).done(function(){
                App.unblockUI();
            });

            
        });
    }

    return {

        //main function to initiate the module
        init: function () {

            if (!jQuery().dataTable) {
                return;
            }

            actionInit();
        
        }

    };

}();


jQuery(document).ready(function() {
    TableDatatablesResponsive.init();
});