var TableDatatablesResponsive = function () {
    
    var initTables = function(){
        
        var table = $('table[id=table_jurnal]');
        var oTable = table.DataTable({
            searching: false,
            processing: true,
            serverSide: true,
            ajax: {
                url : BaseUrl+'/akuntansi/daftar_jurnal_json',
                data:function(d){
                    d.div = $('select[id=division]').val();
                    d.jourType = $('select[id=journal_type]').val();
                    d.transtype = $('select[id=transaction_type_id]').val();
                    d.trdate = $('input[name=tr_date]').val();
                }
            },
            columns:[
                {data : 'type', name : 'type'},
                {data : 'division', name : 'division'},
                {data : 'tr_desc', name : 'tr_desc'},
                {data : 'journal_no', name : 'journal_no'}, 
                {data : 'reff_no', name : 'reff_no'}, 
                {data : 'tr_date', name : 'tr_date'}, 
                {data : 'desc', name : 'desc'}, 
                {data : 'total', name : 'total', sClass: 'text-right'}, 
                {data : 'action', name: 'action', sClass: 'text-center', orderable: false, searchable: false},
            ],
            "order": [],
            
            "createdRow": function ( row, data, index ) {
                    let dateCell = data.tr_date;
                    if (dateCell !== undefined && dateCell.length > 0) {

                    //         let date = moment.unix(dateCell).format('d F Y'); // I am not sure that the format is the same using moment js. Or you can use your format here
                            $('td', row).eq(5).html(convertDateDBtoIndo(dateCell)); // 3 here is equal to the cell in which the date should be placed in the table.
                        }
                // if ( data[5].replace(/[\$,]/g, '') * 1 > 150000 ) {
                    $('td', row).eq(5).addClass('highlight'+dateCell);
                // }
            },
            // "rowCallback": function (row, data, index) {
            //     let dateCell = data.tr_date;
            //     // alert(dateCell);
            //     // if (dateCell !== undefined && dateCell > 0) {
            //     //     alert(dateCell);
            //     //     let date = moment.unix(dateCell).format('d F Y'); // I am not sure that the format is the same using moment js. Or you can use your format here
            //     //     $('td:eq(5)', row).html('ddd'); // 3 here is equal to the cell in which the date should be placed in the table.
            //     // }
            // },
            // initComplete: function () {
            //     this.api().columns().every(function (index) {
            //         var column = this;
            //         var colCount = this.columns().nodes().length - 1;
            //         if(index !== colCount){ // && index !== 5
            //             var input = document.createElement("input");
            //             $(input).addClass('form-control');
            //             $(input).appendTo($(column.footer()).empty())
            //             .on('change', function () {
            //                 column.search($(this).val(), false, false, true).draw();
            //             });
            //         }
            //     });
            //     $(".dataTables_length select").select2();
            // },
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
            // order : [[ 1, 'asc']],
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

        $('button[id=searchJurnal]').click(function(){
            oTable.ajax.reload();
            // alert('');
            return false;
        });
    }

    var handleMaskNumber = function () {
        $('.date-picker').datepicker({
            rtl: App.isRTL(),
            autoclose: true
        });

        $('select').select2([]);
    }

    return {

        //main function to initiate the module
        init: function () {

            if (!jQuery().dataTable) {
                return;
            }

            handleMaskNumber();
            initTables();
        
        }

    };

}();

jQuery(document).ready(function() {
    TableDatatablesResponsive.init();
});