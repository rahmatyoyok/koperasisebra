var TableDatatablesResponsive = function () {
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

    var initInputFormat = function(){
        var initials = Initialized;
        // initials.push({id: s, name: values[s].name});

        $('input[name=periodekKalkulasi]').datepicker( {
            format: "MM yyyy",
            startView: "months", 
            minViewMode: "months",
            language: 'id'
        }).on('changeDate', function (ev) {
            var periodes = getperiode($('input[name=periodekKalkulasi]').val());
            $('input[name=pmperiode]').val(periodes);
            $('div.table-responsive').hide();

            // App.blockUI({});
            // window.location.href= BaseUrl+'/simpanpinjam/kalkulasi?periode='+periodes;
        });

        $('select[name=coa_id]').select2({
            data: initials,
            allowClear : true,
            minimumInputLength: 3,
            placeholder:"Pilih Coa",
            ajax: {
                url: BaseUrl+"/api/select2/coaaktif",
                dataType: 'json',
                cache: true,
                data: function (params) {
                    return { q: $.trim(params.term), page : 1 };
                },

            },    
        }).on('select2:select', function (e) {
            App.blockUI();
            var data = e.params.data; 
            // console.log();
            $('h4[id=labelCoa]').text('Buku Besar : '+ data.code +' - '+data.desc);
            $('div.table-responsive').hide();

            App.unblockUI();
        });

        // $('#myselect2').val().trigger('change');


        $('button[id=StartSubmit]').click(function(){
            App.blockUI();
        });

        $('button[id=StartPosting]').click(function(e){
            e.preventDefault();
            App.blockUI();

            var periodep = $('input[name=pmperiode]').val();
            var coap = $('select[name=coa_id]').val();
            
            $.ajax({
                url: BaseUrl+"/akuntansi/laporan/postingCoaPeriode",
                dataType:'json',
                data:{ ParamPeriode : periodep, ParamCoa : coap },
                success:function(response){

                }
            });

        })

    }
    
    return {

        //main function to initiate the module
        init: function () {

            if (!jQuery().dataTable) {
                return;
            }

            initInputFormat();
        
        }

    };

}();

jQuery(document).ready(function() {
    TableDatatablesResponsive.init();
});