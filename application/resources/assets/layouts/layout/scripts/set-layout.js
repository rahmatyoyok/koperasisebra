$(document).ready(function(){
	var getUrl = window.location;
	var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];

	$(".li-layout").on("click", function(){
		var key = $(this).data('key');
		var value = $(this).data('value');
		$.ajax({
	        type: "POST",
	        dataType: "json",
	        url: baseUrl + "/setLayout",
	        headers: {
	            'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr("content"),
	        },
	        data: {
	        	'key': key,
	        	'value': value
	        },
	        success: function(data){
	        	toastr.clear();
	            toastr["success"]("Tema berhasil diubah", "Success!");
	        },
	        error: function(){
	        	toastr.clear();
	            toastr["error"]("Tema tidak tersedia", "Error!");
	        }
	    });
	});

	$(".select-layout").on("change", function(){
		var key = $(this).data('key');
		var value = $(this).val();
		$.ajax({
	        type: "POST",
	        dataType: "json",
	        url: baseUrl + "/setLayout",
	        headers: {
	            'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr("content"),
	        },
	        data: {
	        	'key': key,
	        	'value': value
	        },
	        success: function(data){
	        	toastr.clear();
	            toastr["success"]("Tema berhasil diubah", "Success!");
	        },
	        error: function(){
	        	toastr.clear();
	            toastr["error"]("Tema tidak tersedia", "Error!");
	        }
	    });
	});
});