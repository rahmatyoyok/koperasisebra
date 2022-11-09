jQuery(document).ready(function() {
  $(".inputNumber").val(0);
  $(".dataTables_filter").hide();
  function toRp(bilangan){
      var	number_string = bilangan.toString(),
       split	= number_string.split(','),
       sisa 	= split[0].length % 3,
       rupiah 	= split[0].substr(0, sisa),
       ribuan 	= split[0].substr(sisa).match(/\d{1,3}/gi);

      if (ribuan) {
       separator = sisa ? '.' : '';
       rupiah += separator + ribuan.join('.');
      }
      rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;

      return 'Rp. ' +rupiah;
  }

  $(".inputNumber").bind('keyup mouseup', function () {
      if(isNaN($(this).val()) || $(this).val() == ""){
          console.log("kosong");
      }else{

          var volume = 1;
          var total = 1;

          var volume1 = $("#volume1").val();
          var volume2 = $("#volume2").val();
          var volume3 = $("#volume3").val();


          if(volume1 != 0){ volume = volume*volume1; }
          if(volume2 != 0){ volume = volume*volume2; }
          if(volume3 != 0){ volume = volume*volume3; }

          var hargaRinc = $("#hargaRinc").val();
          total = volume*hargaRinc;
          total = "Rp."+total.toLocaleString(undefined, {maximumFractionDigits:2});

          $("#volumeRinc").val(volume);
          $("#totalRinc").val(total);
      }
  });

  
});
