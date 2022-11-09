var TableDatatablesResponsive = function () {

    var initTable = function () {
        var table = $('table[id=table_pinjaman]');

        var oTable = table.dataTable({
            processing: true,
            serverSide: true,
            ajax: BaseUrl+'/simpanpinjam/daftarpinjamans_elektronik_json',
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
                {data : 'total_pinjaman', name : 'total_pinjaman', sClass: 'text-right'}, 
                {data : 'saldo_pinjaman', name : 'saldo_pinjaman', sClass: 'text-right'}, 
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
            order : [[ 8, 'asc']],
            // pagination control
            "lengthMenu": [
                [5, 10, 15, 20, -1],
                [5, 10, 15, 20, "All"] // change per page values here
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