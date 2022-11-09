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
            "pageLength": 5,            
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

    var initTable = function(){
        
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
            window.location.href= BaseUrl+'/simpanpinjam/investasi/bungainvestasi?periode='+periodes;
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

    

    var initProses = function(){
        $('button[id=StartKalkulasi]').click(function(){
            App.blockUI({});
            var param = $('input[name=periodekKalkulasi]').val();
            $.ajax({
                url: BaseUrl+"/simpanpinjam/investasi/kalkulasiperbulan",
                dataType:'json',
                data:{ params : param},
                success:function(response)
                {
                    // App.unblockUI();
                    window.location.href = BaseUrl+'/simpanpinjam/investasi/bungainvestasi?periode='+response.periode;
                }
            });

            
        });

        $('button[id=PostingKalkulasi]').click(function(){
            App.blockUI({});
            var param = $('input[name=periodekKalkulasi]').val();
            $.ajax({
                url: BaseUrl+"/simpanpinjam/investasi/postingDataBayar",
                dataType:'json',
                data:{ params : param},
                success:function(response)
                {
                    window.location.reload();
                    App.unblockUI();
                    // var tableRes = $('table[id=table_simpananwajib]').DataTable();
                    // tableRes.ajax.reload();
                    
                }
            });

        });    


        $('label[id=exportExcel]').click(function(){
            var validCheck = $('input[name="datachecked[]"]:checked').length;
            var bulanPeriode = $('input[name=periodekKalkulasi]').val();
            if(validCheck>0){
                var fperiode = getperiode(bulanPeriode);

                App.blockUI();
                $.ajax({
                    method:'GET',
                    url: BaseUrl+"/simpanpinjam/investasi/postingDataBayar",
                    dataType:'json',
                    data: $('form[id=actionProcess]').serialize() + "&periode="+fperiode,
                    success:function(response)
                    {   
                        window.location.href = BaseUrl+"/simpanpinjam/investasi/exportExcelPosting?fperiode="+fperiode+"&fulperiode="+bulanPeriode;
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

            $('div[id=modalUploadPoenerimaan]').modal('hide');
            App.blockUI();
            var bulanPeriode = $('input[name=periodekKalkulasi]').val();
            $('input[name=uploadTFPeriode]').val(getperiode(bulanPeriode));
            
            $('form[id=actionProcess]').attr('action', BaseUrl+"/simpanpinjam/investasi/uploadTransfer").submit();
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