var SweetAlert2Plugin = function () {

	var datatablesPlugin = function () {
		$('[id^=table] tbody').on('click', 'tr button.dt-btn', function(e) {
	        e.preventDefault();
	        var form = $(this).parents('form');
	        swal({
	            title: "Apakah Anda yakin?",
	            text: $(this).data("swa-text"),
	            type: "warning",
	            showCancelButton: true
	        }).then(function() {
	            App.blockUI();
	            form.submit();
	        }).catch(swal.noop);
	    });
	}

	var simpanButton = function () {
		$("button:submit:simpan").on("click", function(e){
			e.preventDefault();
			console.log($(this));
		});
	}

    return {
        init: function () {
        	datatablesPlugin();
        }
    };

}();

jQuery(document).ready(function() {    
   SweetAlert2Plugin.init(); 
});