var TableDatatablesResponsive = function () {
    
    var formatRupiah = function(angka, prefix){
        var number_string = angka.toString().replace(/[^,\d]/g, '').toString(),
        split   		= number_string.split(','),
        sisa     		= split[0].length % 3,
        rupiah     		= split[0].substr(0, sisa),
        ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);

        // tambahkan titik jika yang di input sudah menjadi angka ribuan
        if(ribuan){
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
    }

    var initgetDetail = function(){

        $('a.openModals').click(function(e){
            e.preventDefault();
            var indexarray  = $(this).data('indexarray'); 
            var unikCode    = $(this).data('uniqcode') ;

            $('div[id=full]').modal('show');
            var csrftoken = $('meta[name="csrf-token"]').attr('content');
            
            

            $.ajax({
                url: BaseUrl+"/akuntansi/jurnal/getDetailimportData",
                type: "GET",
                data: {indexparam : indexarray, unikCodeParam : unikCode},
                contentType: false,
                headers: {'X-CSRF-TOKEN': csrftoken},
                success: function(response) {
                    $('meta[name="csrf-token"]').attr('content',response.descrf);
                    var dt = response.data;

                    $('p[id=labelDivisi]').html(dt.DivisiCode);
                    $('p[id=labelJenisTransaksi]').html(dt.JenisTransaksi);
                    $('p[id=labelNoREfrensi]').html(dt.NoRefrensi);
                    $('p[id=labeltanggal]').html(dt.TglTransaksi);
                    $('p[id=labelKeterangan]').html(dt.HeaderDesc);

                    $("tbody[id=listTableModal]").html("");
                    var nx = 1;
                    $.each(dt.DetailTransksi, function(k, e){
                        // alert("");
                        console.log(e.Debit);
                        var htmlsAppend = "<tr>"+
                                                "<td>"+nx+"</td>"+
                                                "<td>"+e.KodeCoa+"</td>"+
                                                "<td>"+e.DetailDesc+"</td>"+
                                                '<td class="text-right rupiah" style="padding:10px 18px;">'+formatRupiah(e.Debit, 'Rp. ')+'</td>'+
                                                '<td class="text-right rupiah" style="padding:10px 18px;">'+formatRupiah(e.Kredit, 'Rp. ')+'</td>'+
                                            "</tr>";

                        $("tbody[id=listTableModal]").append(htmlsAppend);
                        nx = nx+1;
                    });

                    $('td[id=lebelTotalDebit]').html(formatRupiah(dt.TotalDebit, 'Rp. '));
                    $('td[id=lebelTotalKredit]').html(formatRupiah(dt.TotalKredit, 'Rp. '));

                    // console.log(formatRupiah(dt.TotalDebit));

                },
                error: function(jqXHR, textStatus, errorMessage) {
                    console.log(errorMessage); // Optional
                }
             });
        });
        

        $('a[id=saveImportData]').click(function(e){
            e.preventDefault();
            App.blockUI();

            var csrftoken = $('meta[name="csrf-token"]').attr('content');
            
            $.ajax({
                url: BaseUrl+"/akuntansi/jurnal/saveimportData",
                dataType:'json',
                type: "POST",
                data: {_token: csrftoken,faction : "simpan"},
                contentType: false,
                headers: {'X-CSRF-TOKEN': csrftoken},
                success:function(response){
                    $('meta[name="csrf-token"]').attr('content',response.descrf);
                    
                    window.location.href = BaseUrl+"/akuntansi/jurnal/jkm";
                }
            });
        });
    }

    return {

        //main function to initiate the module
        init: function () {

            if (!jQuery().dataTable) {
                return;
            }

            initgetDetail();
        }

    };

}();

jQuery(document).ready(function() {
    TableDatatablesResponsive.init();
});