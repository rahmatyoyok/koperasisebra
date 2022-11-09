var FormValidationMd = function() {

    var HeaderInput = function(){
        $('select[name=division]').select2({});
        $('select[name=tr_type_id]').select2({});
    }

    var addDetail = function(){
        // $('select.inputCoa').select2({});
        select2coa();

        $('a[id=addData]').click(function(e){
            e.preventDefault();


            // if ($('select.inputCoa').data('select2')) {
            //     $('select.inputCoa').select2('destroy');
            // }

            var lastSeq = 0;
            $('input[name*=seq').each(function(){
                var vlseq = parseFloat($(this).val());
                if(lastSeq < vlseq){
                    lastSeq = vlseq;
                }
            });

            lastSeq++;
            $('tbody[id=listTable]').append('<tr>'+
                                            '<td><div class="form-group form-md-line-input" style="margin:0px 5px;padding-top:0px"><input type="text" name="seq[]" class="form-control input-sm" value="'+lastSeq+'"></div></td>'+
                                            '<td><div class="form-group form-md-line-input" style="margin:0px 5px;padding-top:0px"><select class="form-control input-sm inputCoa" name="coa_id[]" tabindex="0" aria-hidden="false"></select></div></td>'+
                                            '<td><div class="form-group form-md-line-input" style="margin:0px 5px;padding-top:0px"><input name="coa_code[]" type="hidden" value=""><input class="form-control input-sm" name="detail_desc[]" type="text" value=""></div></td>'+
                                            '<td><div class="form-group form-md-line-input" style="margin:0px 5px;padding-top:0px"><input class="form-control input-sm rupiah" name="f_debit[]" type="text" value="" style="text-align: right;"></div></td>'+
                                            '<td><div class="form-group form-md-line-input" style="margin:0px 5px;padding-top:0px"><input class="form-control input-sm rupiah" name="f_kredit[]" type="text" value="" style="text-align: right;"></div></td>'+
                                            '<td width="10%" class="text-center"><a href="javascript:;" class="btn btn-block red delete" data-number="1"><i class="fa fa-trash"></i> Hapus</a></td>'+
                                            '</tr>');
            select2coa();

        });
        
    }

    var select2coa = function(){
        var initials = [];
        $('tbody[id=listTable]').find('select.inputCoa').each(function(e){
            var dataId = $(this).val();
            var dataText = $(this).children("option:selected").text();
            
            if(dataId !== null){
                initials.push({id: dataId, name: dataText});
            }
            
        });

        $('select.inputCoa').select2({
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
            // templateSelection: function (result) {
            //     var rs = result.text.split(' - ');
            //     return rs[0];
            // },
            
        }).on('select2:select', function (e) {
 
            // $(this).parents(':eq(2)').find("input[name*=desc]").val(e.params.data.desc);
            $(this).parents(':eq(2)').find("input[name*=coa_code]").val(e.params.data.code);
            // $(this).children("option:selected").html(e.params.data.code);

            // var arryCoa = [];
            // $('tbody[id=listTable]').find('select.inputCoa').each(function(e){
            //     arryCoa.push($(this).val());
            // });

 
        });

        $('tbody[id=listTable]').find('select.inputCoa').each(function(e){
            var dataId = $(this).val();
            if(dataId !== null){
                $(this).val(dataId).trigger('change');
            }

        });

        $('input[name*=f_debit], input[name*=f_kredit]').keyup(function(){
            sumTotaltDetail();
            // alert('');
        });

        $('input.rupiah').inputmask("decimal", {
			radixPoint: ',',
			groupSeparator: '.',
			min: $(this).data('min'),
			placeholder: '0,00',
			digits: 0,
			digitsOptional: !1,
			autoGroup: true,
			prefix: 'Rp. '
        });

        $('a.delete').click(function(e){
            $(this).parents(':eq(1)').remove();     
        });
    }

    var sumTotaltDetail = function(){


        var totalDebit = 0;
        var totalKredit = 0;
        
        $('input[name=total_debit], input[name=total_kredit]').inputmask("decimal", {
			radixPoint: ',',
			groupSeparator: '.',
			min: $(this).data('min'),
			placeholder: '0,00',
			digits: 0,
			digitsOptional: !1,
			autoGroup: true,
			prefix: 'Rp. '
        });
        
        $('input[name*=f_debit').each(function(){

            var vl = parseFloat($(this).inputmask('unmaskedvalue'));
            // console.log(vl);

            if(isNaN(vl)){vl = 0}
            totalDebit = totalDebit + vl;

            $('input[name=total_debit]').inputmask("setvalue", totalDebit);
            validBalanced();

        });

        $('input[name*=f_kredit').each(function(){
            var vlk = parseFloat($(this).inputmask('unmaskedvalue'));

            if(isNaN(vlk)){vlk = 0}
            totalKredit = totalKredit + vlk;

            $('input[name=total_kredit]').inputmask("setvalue", totalKredit);
            validBalanced();
        });

    }   

    var validBalanced = function(){
        var totalfDebit = parseFloat($('input[name=total_debit]').inputmask('unmaskedvalue'));
        var totalfKredit = parseFloat($('input[name=total_kredit]').inputmask('unmaskedvalue'));

        $('input[name=total_debit]').removeClass('notbalance');
        $('input[name=total_kredit]').removeClass('notbalance')
        $('span[id=BalanceStatus]').text('Balance');
        if(totalfDebit !== totalfKredit){
            $('input[name=total_debit]').addClass('notbalance');
            $('input[name=total_kredit]').addClass('notbalance');

            $('span[id=BalanceStatus]').text('Tidak Balance');
        }
        
    }
    
    var formSubmit = function(){
        $('form[id=formEntryJurnal]').submit(function(e) {
            // e.preventDefault();
            
            var totalfDebits = parseFloat($('input[name=total_debit]').inputmask('unmaskedvalue'));
            var totalfKredits = parseFloat($('input[name=total_kredit]').inputmask('unmaskedvalue'));
            var validCoa     = 0;


            App.unblockUI();


            $('select[name*=coa_id').each(function(){
                if( !$(this).val() ) { 
                    validCoa++;
                }
                
            });

            if((totalfDebits > 0) && (totalfDebits == totalfKredits)){
                
                if(validCoa == 0){
                    return true;
                }
                else{
                    alert('Pilih Coa Terlebih Dahulu')
                }
            }else{
                alert('Tiak Balance');
            }
            
            return false;
        });
    }
    
    return {
        //main function to initiate the module
        init: function() {
            HeaderInput();
            addDetail();   
            sumTotaltDetail();
            formSubmit();
        }
    };
}();

jQuery(document).ready(function() {
    FormValidationMd.init();
});
