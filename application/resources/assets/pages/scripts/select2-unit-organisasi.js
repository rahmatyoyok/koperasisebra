var Select2UnitOrganisasi = function () {

    var loc = window.location;
    var baseUrl = "";
    if(loc.hostname === "localhost")
        baseUrl = location.protocol+"//"+loc.host+"/"+loc.pathname.split("/")[1];
    else
        baseUrl = loc.origin;

	var unit = function () {
		$("#Unit").select2({
	        ajax: {
	            url: baseUrl + "/api/select2/get-unit2",
	            dataType: 'json',
	            delay: 250,
	            data: function (params) {
	                var query = {
	                    q: params.term || '',
	                    page: params.page || 1
	                }
	                return query;
	            }
	        }
	    }).on("select2:select", function(){
	        var kode = $(this).val();
	        $("#SubUnit").select2({
	            ajax: {
	                url: baseUrl + "/api/select2/get-sub-unit2",
	                dataType: 'json',
	                delay: 250,
	                data: function (params) {
	                    var query = {
	                        q: params.term || '',
	                        page: params.page || 1,
	                        Kode: kode
	                    }
	                    return query;
	                }
	            }
	        });
	    });
	}

	var subunit = function () {
		$("#SubUnit").select2({
	        ajax: {
	            url: baseUrl + "/api/select2/get-sub-unit2",
	            dataType: 'json',
	            delay: 250,
	            data: function (params) {
	                var query = {
	                    q: params.term || '',
	                    page: params.page || 1
	                }
	                return query;
	            }
	        }
	    });
	}

	var scroller = function () {
		$("select").on("select2:open", function () {
	        $('.select2-results__options').niceScroll();
	    });
	}

    return {
        init: function () {
        	unit();
        	subunit();
        	scroller();
        }
    };

}();

jQuery(document).ready(function() {
   Select2UnitOrganisasi.init();
});
