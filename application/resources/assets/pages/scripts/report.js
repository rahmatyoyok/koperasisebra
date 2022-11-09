var ReportFilter = function () {

    $('#select-bidang').select2({
        ajax: {
                url: "{{ url('api/select2/get-bidang2') }}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term || '',
                        page: params.page || 1,
                        kode: $('#select-urusan').val()
                    }
                    return query;
                }
            }
        }).on("select2:select", function(){
            var kodebidang = $('#select-bidang').val();
            $('#select-unit').select2({
                ajax: {
                    url: "{{ url('api/select2/get-unit3') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        var query = {
                            q: params.term || '',
                            page: params.page || 1,
                            kode: kodebidang
                        }
                        return query;
                    }
                }
            }).on("select2:select", function(){
                var kodeunit = $('#select-unit').val();
                $("#select-sub-unit").select2({
                    ajax: {
                        url: "{{ url('api/select2/get-sub-unit2') }}",
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            var query = {
                                q: params.term || '',
                                page: params.page || 1,
                                Kode: kodeunit
                            }
                            return query;
                        }
                    }
                }).on("select2:select", function(){
                    var kodesub = $('#select-sub-unit').val();
                    $("#select-program").select2({
                        ajax: {
                            url: "{{ url('api/select2/get-program2') }}",
                            dataType: 'json',
                            delay: 250,
                            data: function (params) {
                                var query = {
                                    q: params.term || '',
                                    page: params.page || 1,
                                    kode: kodesub
                                }
                                return query;
                            }
                        }
                    }).on("select2:select", function(){
                        var kodeprogram = $("#select-program").val();
                        $("#select-kegiatan").select2({
                            ajax: {
                                url: "{{ url('api/select2/get-kegiatan2') }}",
                                dataType: 'json',
                                delay: 250,
                                data: function (params) {
                                    var query = {
                                        q: params.term || '',
                                        page: params.page || 1,
                                        kode: kodeprogram
                                    }
                                    return query;
                                }
                            }
                        });
                    });
                });
            });
        });

}();

jQuery(document).ready(function() {
   ReportFilter.init();
});
