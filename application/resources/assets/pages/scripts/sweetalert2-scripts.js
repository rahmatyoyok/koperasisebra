var SweetAlert2Plugin = function () {

	var datatables = function () {
		$('[id^=table] tbody').on('click', 'tr button.dt-btn', function(e) {
	        e.preventDefault();
	        var form = $(this).parents('form');
	        swal({
	            title: "Apakah anda yakin?",
	            text: $(this).data("swa-text"),
	            type: "warning",
	            showCancelButton: true
	        }).then(function() {
	            App.blockUI();
	            form.submit();
	        }).catch(swal.noop);
	    });
	}

	var simpan = function () {
		$("button:submit.simpan").on("click", function(e){
			e.preventDefault();
	        var form = $(this).parents('form');
	        swal({
	            title: "Apakah anda yakin?",
	            text: $(this).data("swa-text"),
	            type: "warning",
	            showCancelButton: true
	        }).then(function() {
	            App.blockUI();
	            form.submit();
	        }).catch(swal.noop);
		});
	}

	var ubah = function () {
		$("button:submit.ubah").on("click", function(e){
			e.preventDefault();
	        var form = $(this).parents('form');
	        swal({
	            title: "Apakah anda yakin?",
	            text: $(this).data("swa-text"),
	            type: "warning",
	            showCancelButton: true
	        }).then(function() {
	            App.blockUI();
	            form.submit();
	        }).catch(swal.noop);
		});
	}

	var link = function(){
		$("button.linkto").on("click", function(e){
			var datahref = $(this).data('href');

			e.preventDefault();
	        var form = $(this).parents('form');
	        swal({
	            title: "Apakah anda yakin?",
	            text: $(this).data("swa-text"),
	            type: "warning",
	            showCancelButton: true
	        }).then(function() {
				App.blockUI();
				window.location.href = datahref;
	        }).catch(swal.noop);
		});
	}

    return {
        init: function () {
        	datatables();
        	simpan();
			ubah();
			link();
        }
    };

}();

jQuery(document).ready(function() {
   SweetAlert2Plugin.init();
});
