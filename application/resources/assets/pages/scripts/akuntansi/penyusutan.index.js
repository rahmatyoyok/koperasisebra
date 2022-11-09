var TableDatatablesResponsive = function () {
    var initTable = function () {
        var table = $('table[id=tb_list_penyusutan]');
        var oTable = table.DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url : BaseUrl+'/akuntansi/penyusutan/daftarpenyusutanjson',
            },
            columns:[
                {data : 'coa', name : 'coa'},
                {data : 'nama', name : 'nama'},
                {data : 'tgl_penerimaan', name : 'tgl_penerimaan'}, 
                {data : 'total', name : 'total', sClass: 'text-right'}, 
                {data : 'masa_manfaat', name : 'masa_manfaat', sClass: 'text-right'}, 
                {data : 'periode_akhir', name : 'periode_akhir'}, 
                {data : 'sisa_masa_manfaat', name : 'sisa_masa_manfaat', sClass: 'text-right'},  
                {data : 'total_penyusutan', name : 'total_penyusutan', sClass: 'text-right'}, 
                {data : 'nilai_buku', name : 'nilai_buku', sClass: 'text-right', width: 200}, 
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
        return blnokethn;
    }

    var initInput = function (){
        $('input[name=periodekKalkulasi]').datepicker( {
            format: "MM yyyy",
            startView: "months", 
            minViewMode: "months",
            language: 'id'
        }).on('changeDate', function (ev) {
            // App.blockUI({});
            var periodes = getperiode($('input[name=periodekKalkulasi]').val());
            $('input[name=pmperiode]').val(periodes);
            $('input[name=periodekKalkulasi]').datepicker('hide');
        });

        $('button[id=actionkalkulasi]').click(function(){
            App.blockUI({});

            $('div[id=stack2]').modal('toggle');
            $('div[id=stack1]').modal('toggle');

            var paramf = $('input[name=pmperiode]').val();
            $.ajax({
                url: BaseUrl+"/akuntansi/penyusutan/kalkulasiPostingPenyusutan",
                dataType:'json',
                data:{ fperiode : paramf },
                success:function(r){
                    if(r.responses == true){
                        window.location.reload();
                    }
                    else{
                        $('div[id=stack3]').modal('show');
                    }
                        
                    App.unblockUI();
                }
            }).done(function(){
                App.unblockUI();
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
            initInput();
        }

    };

}();

jQuery(document).ready(function() {
    TableDatatablesResponsive.init();
});