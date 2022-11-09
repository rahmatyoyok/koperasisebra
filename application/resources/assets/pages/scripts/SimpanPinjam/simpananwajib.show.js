var FormValidationMd = function() {

    var initbutton = function(){
        $("button.moveLink").on("click", function(e){
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
        
        $("button.moveLinkBatal").on("click", function(e){
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
                $.ajax({
                    url: BaseUrl+"/simpanpinjam/wajib/pembatalan/"+dt,
                    dataType:'json',
                    // data:{ ParamId : detailPinjamaniId, approveType : 1, desc : approvalDesc},
                    success:function(rtn)
                    {   
                        if(rtn.response){
                            window.location.reload();
                        }
                        else{
                            alert('Gagal');
                            App.unblockUI();
                        }
                    }
                });

	        }).catch(swal.noop);
		});
    }

    return {
        //main function to initiate the module
        init: function() {
            initbutton();
        }
    };
}();

jQuery(document).ready(function() {
    FormValidationMd.init();
});
