var TableDatatablesResponsive = function () {

    var initTable = function () {
        var table = $('table[id=table_investasi]');

        var oTable = table.dataTable({
            processing: true,
            serverSide: true,
            ajax: BaseUrl+'/simpanpinjam/daftarsimpananinvestasi_json',
            columns:[
                {data : 'niak', name : 'niak'},
                {data : 'first_name', name : 'first_name'},
                {data : 'born_place',
                    "render": function ( data, type, row ) {
                                    return row.anggota.born_place + ', '+row.born_date;
                                },
                    "targets": 0},
                {data : 'id_card_number',  "render": function ( data, type, row ) {
                                    return row.anggota.id_card_number;
                                },
                    "targets": 0,
                    "orderable": false
                },
                {data : 'company_name', name : 'company_name'}, 
                {data : 'status_anggota', "render": function ( data, type, row ) {
                            return '<span class="label label-sm label-'+((row.anggota.status = 1) ? 'success': 'danger')+'"> '+row.status_anggota+' </span>';
                        }, sClass: 'text-center',
                "targets": 0},
                {data : 'total', name : 'total', sClass: 'text-right'}, 
                {data : 'need_approval', name: 'need_approval', sClass: 'text-center',
                    "render": function ( data, type, row ) { 
                        return ((parseInt(row.need_approval) > 0) ? '<span class="label label-sm label-warning"> Lakukan Pengechekan </span>' : (((parseInt(row.read_status) > 0) ? '<span class="label label-sm label-danger"> Butuh Pengecekan </span>':"")));
                    }
                },
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
            "language": {
                "url" : "http://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
                },
            responsive: {
                details: {
                    type: 'column',
                    target: 'tr'
                }
            },
            order : [[ 7, 'desc']],
            // pagination control
            "lengthMenu": [
                [5, 10, 15, 20, -1],
                [5, 10, 15, 20, "All"] // change per page values here
            ],
            // set the initial value
            "pageLength": 10,
            "dom": "<'row' <'col-md-12'T>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r>t<'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
        });
    }


    return {

        //main function to initiate the module
        init: function () {

            if (!jQuery().dataTable) {
                return;
            }

            
            initTable();
        
        }

    };

}();

jQuery(document).ready(function() {
    TableDatatablesResponsive.init();
});