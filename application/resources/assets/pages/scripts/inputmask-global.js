var InputMask = function () {

	var rupiah = function () {
		$(".rupiah").inputmask("decimal", {
			radixPoint: ',',
			groupSeparator: '.',
			min: $(this).data('min'),
			placeholder: '0,00',
			digits: 0,
			digitsOptional: !1,
			autoGroup: true,
			prefix: 'Rp. '
		});
	}

	var numeric = function () {
		$(".numeric").inputmask("decimal", {
			radixPoint: ',',
			groupSeparator: '.',
			min: $(this).data('min'),
			placeholder: '0,00',
			digits: 0,
			digitsOptional: !1,
			autoGroup: true,
			prefix: ''
		});
	}

	var presentase = function () {
		$(".presentase").inputmask("decimal", {
			radixPoint: ',',
			groupSeparator: '.',
			placeholder: '0,00',
			autoGroup: true,
			prefix: ''
		});
	}
	
    return {
        init: function () {
        	rupiah();
			numeric();
			presentase();
        }
    };

}();

jQuery(document).ready(function() {
   InputMask.init();
});
