var TableDatatablesResponsive = function () {
    
    var initTable1_2 = function () {

        var table = $('#sample_1_2');

        // begin first table
        table.dataTable({

            // Internationalisation. For more info refer to http://datatables.net/manual/i18n
            "language": {
                "aria": {
                    "sortAscending": ": activate to sort column ascending",
                    "sortDescending": ": activate to sort column descending"
                },
                "emptyTable": "No data available in table",
                "info": "Showing _START_ to _END_ of _TOTAL_ records",
                "infoEmpty": "No records found",
                "infoFiltered": "(filtered1 from _MAX_ total records)",
                "lengthMenu": "Show _MENU_",
                "search": "Search:",
                "zeroRecords": "No matching records found",
                "paginate": {
                    "previous":"Prev",
                    "next": "Next",
                    "last": "Last",
                    "first": "First"
                }
            },

            // Or you can use remote translation file
            //"language": {
            //   url: '//cdn.datatables.net/plug-ins/3cfcc339e89/i18n/Portuguese.json'
            //},

            // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
            // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js). 
            // So when dropdowns used the scrollable div should be removed. 
            //"dom": "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r>t<'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",

            "bStateSave": false, // save datatable state(pagination, sort, etc) in cookie.

            "lengthMenu": [
                [5, 15, 20, -1],
                [5, 15, 20, "All"] // change per page values here
            ],

            // set the initial value
            "pageLength": 20,            
            "pagingType": "bootstrap_full_number",
            "autoWidth": false,
            "columnDefs": [
                {  // set default column settings
                    'orderable': false,
                    'targets': [0]
                }, 
                {
                    "width": "100px",
                    "targets": [4]
                },
                {
                    "className": "dt-right", 
                    //"targets": [2]
                },

                {  // set default column settings
                    'orderable': false,
                    'targets': [6]
                },
                {  // set default column settings
                    'orderable': false,
                    'targets': [7]
                },{  // set default column settings
                    'orderable': false,
                    'targets': [8]
                },{  // set default column settings
                    'orderable': false,
                    'targets': [9]
                },
                {  // set default column settings
                    'orderable': false,
                    'targets': [10]
                },
                {  // set default column settings
                    'orderable': false,
                    'targets': [11]
                },
            ],

            "order": [
                [1, "asc"]
            ], // set first column as a default sort by asc

            initComplete: function () {

                // username column
                this.api().column(3).every(function(){
                    var column = this;
                    var select = $('<select class="form-control input-sm"><option value="">Select</option></select>')
                        .appendTo( $(column.footer()).empty() )
                        .on( 'change', function () {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );     
                            column
                                .search( val ? '^'+val+'$' : '', true, false )
                                .draw();
                        } );
     
                    column.data().unique().sort().each( function ( d, j ) {
                        select.append( '<option value="'+d+'">'+d+'</option>' )
                    } );
                });


                this.api().column(4).every(function(){
                    var column = this;
                    var select = $('<select class="form-control input-sm"><option value="">Select</option></select>')
                        .appendTo( $(column.footer()).empty() )
                        .on( 'change', function () {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );     
                            column
                                .search( val ? '^'+val+'$' : '', true, false )
                                .draw();
                        } );
     
                    column.data().unique().sort().each( function ( d, j ) {
                        select.append( '<option value="'+d+'">'+d+'</option>' )
                    } );
                });

                this.api().column(5).every(function(){
                    var column = this;
                    var select = $('<select class="form-control input-sm"><option value="">Select</option></select>')
                        .appendTo( $(column.footer()).empty() )
                        .on( 'change', function () {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );     
                            column
                                .search( val ? '^'+val+'$' : '', true, false )
                                .draw();
                        } );
     
                    column.data().unique().sort().each( function ( d, j ) {
                        select.append( '<option value="'+d+'">'+d+'</option>' )
                    } );
                });

            }
        });

        var tableWrapper = jQuery('#sample_1_2_wrapper');

        table.find('.group-checkable').change(function () {
            var set = jQuery(this).attr("data-set");
            var checked = jQuery(this).is(":checked");
            jQuery(set).each(function () {
                if (checked) {
                    $(this).prop("checked", true);
                    $(this).parents('tr').addClass("active");
                } else {
                    $(this).prop("checked", false);
                    $(this).parents('tr').removeClass("active");
                }
            });
        });

        table.on('change', 'tbody tr .checkboxes', function () {
            $(this).parents('tr').toggleClass("active");
        });
    }

    var initTable = function () {
        var fperiode = $('input[name=periodekKalkulasi]').val();
        var table = $('table[id=table_simpananwajib]');
        var oTable = table.dataTable({
            processing: true,
            serverSide: true,
            ajax: {
               url :  BaseUrl+'/simpanpinjam/daftarsimpananwajib_json',
               data: function(d){
                    d.periode = getperiode(fperiode);
               },
            }, 
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
                {data : 'status', name: 'status', sClass: 'text-center', orderable: false, searchable: false},
                
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
            order : [[ 1, 'asc']],
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

    var initInputFormat = function(){
        $('input[name=periodekKalkulasi]').datepicker( {
            format: "MM yyyy",
            startView: "months", 
            minViewMode: "months",
            language: 'id'
        }).on('changeDate', function (ev) {
            var periodes = getperiode($('input[name=periodekKalkulasi]').val());
            App.blockUI({});
            window.location.href= BaseUrl+'/simpanpinjam/kalkulasi?periode='+periodes;
            // checkposting();
            // var tableRes = $('table[id=table_simpananwajib]').DataTable();
            // tableRes.ajax.reload();
        });
    }

    var getperiode = function(fperiode){
        var objbln = function(bln){
            var bulan = ["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
            return bulan.indexOf(bln)+1;
        }
        // var fperiode = $('input[name=periodekKalkulasi]').val();
        var res = fperiode.split(" ");
        var nmrbln = objbln(res[0]).toString();
        var blnoke = nmrbln.length < 2 ? "0" + nmrbln : nmrbln;
        var blnokethn = blnoke+res[1];
        return blnokethn
    }

    var checkposting = function(){
        var fperiode = $('input[name=periodekKalkulasi]').val();
        var blnokethn = getperiode(fperiode);
        $.ajax({
            url: BaseUrl+"/api/table/checkstatusposting",
            dataType:'json',
            data:{ periode : blnokethn},
            success:function(response){
                if(response.postingstatus == true){
                    $('button[id=StartKalkulasi]').hide();
                    $('button[id=PostingKalkulasi]').hide();
                }
            }
        });
        return blnokethn;
    }

    var initProses = function(){
        $('button[id=StartKalkulasi]').click(function(){
            App.blockUI({});
            var param = $('input[name=periodekKalkulasi]').val();
            $.ajax({
                url: BaseUrl+"/simpanpinjam/kalkulasi/kalkulasiperbulan",
                dataType:'json',
                data:{ params : param},
                success:function(response)
                {
                    // App.unblockUI();
                    window.location.href = BaseUrl+'/simpanpinjam/kalkulasi?periode='+response.periode;
                }
            });

            
        });

        $('button[id=PostingKalkulasi]').click(function(){
            App.blockUI({});
            var param = $('input[name=periodekKalkulasi]').val();
            $.ajax({
                url: BaseUrl+"/simpanpinjam/wajib/postingkalkulasiperbulan",
                dataType:'json',
                data:{ params : param},
                success:function(response)
                {
                    App.unblockUI();
                    var tableRes = $('table[id=table_simpananwajib]').DataTable();
                    tableRes.ajax.reload();
                }
            });

        });    


        $('button[id=prosesExport]').click(function(){
            var validCheck = $('input[name="datachecked[]"]:checked').length;
            var bulanPeriode = $('input[name=periodekKalkulasi]').val();
            if(validCheck>0){
                var fperiode = getperiode(bulanPeriode);

                App.blockUI();
                $.ajax({
                    method:'POST',
                    url: BaseUrl+"/simpanpinjam/kalkulasi/postingDataBayar",
                    dataType:'json',
                    data: $('form[id=actionProcess]').serialize() + "&periode="+fperiode,
                    success:function(response)
                    {   
                        window.location.href = BaseUrl+"/simpanpinjam/kalkulasi/exportExcelPosting?fperiode="+fperiode+"&fulperiode="+bulanPeriode;
                    }
                }).done(function(){
                    App.unblockUI();
                });
                
            }else{
                alert('Pilih data yang akan di export menjadi lampiran excel');
            }
        });

        $('button[id=prosesPenerimaan]').click(function(){
            $('div[id=modalUploadPoenerimaan]').modal();    
        });

        $('button[id=btnUploadExcel]').click(function(e){
            e.preventDefault();
            var bulanPeriode = $('input[name=periodekKalkulasi]').val();
            $('input[name=uploadPenerimaanPeriode]').val(getperiode(bulanPeriode));
            
            $('form[id=actionProcess]').attr('action', BaseUrl+"/simpanpinjam/kalkulasi/uploadPenerimaan").submit();
        });
    }

    return {

        //main function to initiate the module
        init: function () {

            if (!jQuery().dataTable) {
                return;
            }

            initTable1_2();
            initTable();
            initProses();
            initInputFormat();
        
        }

    };

}();

jQuery(document).ready(function() {
    TableDatatablesResponsive.init();
});