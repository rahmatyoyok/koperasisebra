var FormValidationMd = function() {

    var handleselect2 = function(){
        var tokenss = $('meta[name="csrf-token"]').attr('content');
        $('select[name=f_payment_method]').select2();

        $('select[name=person_id]').select2({
            minimumInputLength: 0,
            placeholder:"Pilih Anggota",
            ajax: {
                method:'POST',
                url: BaseUrl+"/api/select2/testToken",
                dataType: 'json',
                cache: false,
                beforeSend :function (request){
                    request.setRequestHeader("X-CSRF-TOKEN", tokenss);
                },
                headers: {
                    'X-CSRF-TOKEN': tokenss
                },
                data: function (params) {
                    return { q: $.trim(params.term), page : 1 };
                },
                processResults: function (data, param) {
                    param.page = param.page || 1;

                    tokenss = data.csrf;
                    $('meta[name="csrf-token"]').attr('content', data.csrf);
                    $('input[name="_token"]').val(data.csrf);
                    
                    return { 
                        results: $.map(data.results, function (dt) {
                            return {
                                text: dt.niak + ' - '+ dt.first_name +' '+dt.last_name +' ( '+dt.customer.company_name+' ) ',
                                id: dt.person_id,
                                niak : dt.niak,
                                dataparsing : dt
                            }
                        }),
                    };
                },
                
            },
            templateSelection: function (result) {
                return result.niak;
              },

        }).on('select2:select', function (e) {

            App.blockUI();
            var data = e.params.data.dataparsing;
            
            $('input[name=f_name]').val(data.first_name +' '+data.last_name);
            $('input[name=f_ttl]').val(data.born_place +' '+data.born_date);
            $('input[name=f_no_identitas]').val(data.id_card_number);
            $('input[name=f_alamat]').val(data.address_1);

            $('input[name=f_jenis_anggota]').val(data.jenis_anggota_desc);
            $('input[name=f_status]').val(data.status_anggota_desc);
            $('input[name=f_rekening]').val(data.customer.account_number);
            $('input[name=f_unit_kerja]').val(data.customer.company_name);
            $('input[name=f_no_induk]').val(data.customer.nomor_induk);
            $('input[name=f_jabatan]').val(data.customer.jabatan);
            
            
            $('input[name=f_simpanan_pokok]').val(0);
            $('input[name=f_simpanan_wajib]').val(0);
            $('input[name=f_simpanan_investasi]').val(0);
            $('input[name=f_pinjaman_usp]').val(0);
            $('input[name=f_pinjaman_elektronik]').val(0);
                
            $.ajax({
                url: BaseUrl+"/simpanpinjam/anggota/dataanggota_json",
                dataType:'json',
                    beforeSend :function (request){
                        request.setRequestHeader("X-CSRF-TOKEN", tokenss);
                    },
                    headers: {
                        'X-CSRF-TOKEN': tokenss
                    },
                data:{ ParamId : data.person_id},
                success:function(response)
                {
                    if(response.response){
                        $('input[name=f_simpanan_pokok]').val(response.datasaldo.saldoPokok);
                        $('input[name=f_simpanan_wajib]').val(response.datasaldo.saldoWajib);
                        $('input[name=f_simpanan_investasi]').val(response.datasaldo.saldoInvestasi);

                        $('input[name=f_pinjaman_usp]').val(response.datasaldo.pinjaman_usp);
                        $('input[name=f_pinjaman_elektronik]').val(response.datasaldo.pinjaman_elektronik);
                    }
                    tokenss = response.csrf;
                    $('meta[name="csrf-token"]').attr('content', response.csrf);
                    $('input[name="_token"]').val(response.csrf);
                }
            })
            .done(function(){
                App.unblockUI();
            });

        });;

    }

    return {
        //main function to initiate the module
        init: function() {
            handleselect2();
        }
    };
}();

jQuery(document).ready(function() {
    FormValidationMd.init();
});