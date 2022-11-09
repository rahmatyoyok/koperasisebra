var TableDatatablesResponsive = function () {
    var initTable = function () {
        var table = $('table[id=table_jurnal]');
        var oTable = table.DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url : BaseUrl+'/akuntansi/daftar_jurnal_json',
                data : {jurnalType : jurnalTypes },
                error : function(jqXHR, ajaxOptions, thrownError) {
                  alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);

                  window.location.reload(true);
                }
            },
            columns:[
                {data : 'division', name : 'division'},
                {data : 'tr_desc', name : 'tr_desc'},
                // {data : 'id_card_number',  "render": function ( data, type, row ) {
                //                     return row.anggota.id_card_number;
                //                 },
                //     "targets": 0,
                //     "orderable": false
                // },
                {data : 'journal_no', name : 'journal_no'}, 
                {data : 'reff_no', name : 'reff_no'}, 
                {data : 'trdate', name : 'tr_date'}, 
                {data : 'desc', name : 'desc'}, 
                {data : 'total', name : 'total', sClass: 'text-right'}, 
                {data : 'action', name: 'action', sClass: 'text-center', orderable: false, searchable: false},
            ],
            initComplete: function () {
                this.api().columns().every(function (index) {
                    var column = this;
                    var colCount = this.columns().nodes().length - 1;
                    if(index !== colCount){ // && index !== 5
                        var input = document.createElement("input");
                        $(input).addClass('form-control');
                        $(input).appendTo($(column.footer()).empty())
                        .on('change', function () {
                            column.search($(this).val(), false, false, true).draw();
                        });
                    }
                });
                $(".dataTables_length select").select2();
            },
            // Internationalisation. For more info refer to http://datatables.net/manual/i18n
            "language": {
                "url" : "http://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            },

            // Or you can use remote translation file
            //"language": {
            //   url: '//cdn.datatables.net/plug-ins/3cfcc339e89/i18n/Portuguese.json'
            //},

            // setup responsive extension: http://datatables.net/extensions/responsive/
            responsive: {
                details: {
                    type: 'column',
                    target: 'tr'
                }
            },
            order : [[ 4, 'desc']],
            // pagination control
            "lengthMenu": [
                [10, 15, 20, 100, -1],
                [10, 15, 20, 100, "All"] // change per page values here
            ],
            // set the initial value
            "pageLength": 10,
            //"pagingType": 'bootstrap_extended', // pagination type

            // "dom": "<'row' <'col-md-12'B>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable

            // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
            // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js). 
            // So when dropdowns used the scrollable div should be removed. 
            "dom": "<'row' <'col-md-12'T>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r>t<'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
        });
    }

    var initUploadFile = function(){

        $('button[id=uploadFile]').click(function(e){
            e.preventDefault();

            var csrftoken = $('meta[name="csrf-token"]').attr('content');
            var formData = new FormData();
            formData.append('fileToUpload', $('input[id=exampleInputFile]')[0].files[0]);


            $.ajax({
                url: BaseUrl+"/akuntansi/jurnal/importfilejurnal",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                headers: {'X-CSRF-TOKEN': csrftoken},
                success: function(response) {
                    // console.log(response.descrf);
                    $('meta[name="csrf-token"]').attr('content',response.descrf);
                    window.location.href = BaseUrl+"/akuntansi/jurnal/importData";
                },
                error: function(jqXHR, textStatus, errorMessage) {
                    alert(errorMessage);
                    // console.log(errorMessage); // Optional
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

            
            initTable();
            initUploadFile();
        }

    };

}();

jQuery(document).ready(function() {
    TableDatatablesResponsive.init();
});