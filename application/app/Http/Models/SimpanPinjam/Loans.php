<?php

namespace App\Http\Models\SimpanPinjam;

use Illuminate\Database\Eloquent\Model;
use DB, Auth;

class Loans extends Model
{
    protected $table = 'sp_loans';
    public $timestamps = false;

    protected $primaryKey = 'loan_id';

    protected $fillable = [
        'transaction_type_id', 'person_id','loan_type_id','ref_code', 'loan_date', 'unit_desc', 'loan_amount',  'due_type',  'due_date','tenure','interest_type',
        'biaya_administrasi_rupiah','interest_rates','biaya_provisi_rupiah', 'resiko_daperma', 'biaya_materai', 'biaya_lain',  
        'loan_total','rates_total', 'late_tolerance', 'daily_fines', 'principal_amount', 'rates_amount','attacment','bank_id','transfer_bank_account_id','rekening_no',
        'status', 'lampiran_pengajuan', 'is_deleted', 'created_at', 'created_by','updated_at', 'updated_by'
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'person_id');
    }

    public function loantype()
    {
        return $this->belongsTo(LoanTypes::class, 'loan_type_id');
    }

    public static function getListPinjaman($tr_type)
    {
        $userLevel = Auth::user()->level_id;

        $dts_b = '"read_by_bendahara":1'; $dts_w = '"read_by_wakil":1'; $dts_k = '"read_by_ketua":1'; $dts_m = '"read_by_manajer":1'; $dts_sa = '"read_by_staffall":1'; $dts_sp = '"read_by_staffsp":1'; 
        $return = Loans::with('anggota')->with('anggota.customer')
                    ->leftJoin(DB::raw("(select @rownum_a := case when @groupnum_a = apr_loan.loan_id then @rownum_a + 1 else 1 end AS rank, IFNULL(apr_loan.level_id,0)lvl_id,  apr_loan.*, @groupnum_a := apr_loan.loan_id
                    from (SELECT @rownum_a := 1, @groupnum_a:=0) r1, sp_loan_approvals apr_loan order by apr_loan.approval_date DESC, apr_loan.loan_approval_id desc)apr"),
                    function($join){
                        $join->on("apr.loan_id","=","sp_loans.loan_id")->whereRaw(DB::raw('apr.rank = 1'));
                         }
                    )
                    ->select(['person_id', DB::raw('max(loan_date) as loan_date'),  
                                DB::raw('ifnull((select sum(a.loan_total) from sp_loans a where a.loan_id = sp_loans.loan_id and a.is_deleted = 0 and a.status = 1 and a.transaction_type_id = 1),0) as total_pinjaman'),
                                DB::raw('ifnull((select sum(a.loan_total) from sp_loans a where a.loan_id = sp_loans.loan_id and a.is_deleted = 0 and a.status = 1 and a.transaction_type_id = 1),0) - ifnull((select sum(aa.principal_amount) from sp_loan_installments aa where aa.loan_id = sp_loans.loan_id and aa.is_deleted = 0 and aa.status = 2),0) as saldo_pinjaman'),
                                DB::raw('sum(case 
                                                when 9 = '.$userLevel.' /*bendahara*/ and apr.lvl_id is null then 1
                                                when 7 = '.$userLevel.' /*ketua*/ and apr.lvl_id = 9 and apr.status = 1 then 1
                                                when 10 = '.$userLevel.' /*manajer*/ and apr.lvl_id = 7 and apr.status = 1 then 1
                                                when 11 = '.$userLevel.' /*Staff All*/ and apr.lvl_id = 7 and apr.status = 1 then 1
                                                when 12 = '.$userLevel.' /*Staff SimpanPinjam*/ and apr.lvl_id = 7 and apr.status = 1 then 1
                                            else 0 end)need_approval'),
                                DB::raw("sum(case 
                                            when 7 = ".$userLevel." /*ketua*/ and apr.status = 0 and ifnull(apr.status_desc REGEXP '$dts_k',0) = 0 then 1 
                                            when 8 = ".$userLevel." /*wakil*/ and apr.status = 0 and ifnull(apr.status_desc REGEXP '$dts_w',0) = 0 then 1 
                                            when 9 = ".$userLevel." /*bendahara*/ and apr.status = 0 and ifnull(apr.status_desc REGEXP '$dts_b',0) = 0 then 1 
                                            when 10 = ".$userLevel." /*manajer*/ and apr.status = 0 and ifnull(apr.status_desc REGEXP '$dts_m',0) = 0 then 1 
                                            when 11 = ".$userLevel." /*Staff All*/  and apr.status = 0 and ifnull(apr.status_desc REGEXP '$dts_sa',0) = 0 then 1 
                                            when 12 = ".$userLevel." /*Staff SimpanPinjam*/ and apr.status = 0 and ifnull(apr.status_desc REGEXP '$dts_sp',0) = 0 then 1 
                                            else 0 end)as read_status")
                            ])
                    ->where('transaction_type_id',$tr_type)
                    ->where('is_deleted','0')
                    ->groupBy('person_id')
                    ->get();
         
                    // echo $return->toSql();die;
        return $return;
    }
    
    public static function getdataPinjamanperCustomer($person_id, $trType = 1){
        $query = DB::select("select a.*, dt.lastlevel_app, dt.level_name level_name, dt.description level, dt.status approval_status, dt.name user_approval, dt.desc,
        case 
                when a.transaction_type_id = 1 then 
                        a.loan_amount - (select ifnull(sum(li.principal_amount),0) from sp_loan_installments li where li.loan_id = a.loan_id and li.status = 2 and li.is_deleted = 0)
                when a.transaction_type_id = 2 then 
                        (a.loan_amount+a.rates_amount) - (select ifnull(sum(li.principal_amount),0) from sp_loan_installments li where li.loan_id = a.loan_id and li.status = 2 and li.is_deleted = 0)
        end as saldo_pokok_pinjaman
from sp_loans a
left join (select 
                @rownum_a := case when @groupnum_a = b.loan_id then @rownum_a + 1 else 1 end AS rank, IFNULL(b.level_id,0)lvl_id,  b.*,
                u.name, l.id lastlevel_app, l.name level_name, l.description, 
                @groupnum_a := b.loan_id
                from (SELECT @rownum_a := 1, @groupnum_a:=0) r1, sp_loan_approvals b
                INNER JOIN users u on u.id = b.approval_by
                INNER JOIN levels l on l.id = u.level_id 
                order by b.approval_date DESC, b.loan_approval_id desc)dt on dt.loan_id = a.loan_id and dt.rank = 1
                            where a.person_id = ?
                                and a.transaction_type_id = ?", array($person_id, $trType));
        
        return $query;
    }

    public static function getDataDetailPinjamanById($loanId){
       
        $query = DB::select("select
                                l.* , pr.niak, concat(pr.first_name, ' ',pr.last_name)nama,  concat(lt.name,' - (',(lt.interest_rates*100),'%)') jenis_bunga, pr.company_name,
                                (principal_amount + rates_amount) total_angsuran, '' tgl_pengajuan
                            from sp_loans l
                            inner join (select a.*, c.company_name, c.bank_id, c.account_number from ospos_people a, ospos_customers c where a.person_id = c.person_id)pr on pr.person_id = l.person_id
                            inner join sp_loan_types lt on lt.loan_type_id = l.loan_type_id
                            where l.loan_id = ?", array($loanId));
        return $query;
    }
    
    public static function getByIdPinjaman($id)
    {
        $return = Loans::with('loantype')->with('anggota')->with('anggota.customer')->select()
                    ->addSelect(DB::raw('(SELECT ifnull(max(sl.seq_number),0) FROM sp_loan_installments sl where sl.is_deleted = 0 and sl.loan_id = sp_loans.loan_id)seq_number'))
                    ->addSelect(DB::raw('loan_amount - (select ifnull(sum(li.principal_amount),0) from sp_loan_installments li where li.loan_id = sp_loans.loan_id and li.status = 2 and li.is_deleted = 0)saldo_pokok_pinjaman'))
                    ->addSelect(DB::raw('rates_total - (select ifnull(sum(li.rates_amount),0) from sp_loan_installments li where li.loan_id = sp_loans.loan_id and li.status = 2 and li.is_deleted = 0)saldo_bunga_pinjaman'))
                    ->addSelect(DB::raw('(select sum(c.loan_amount)-sum(ifnull(c.total_bayar_pokok,0))saldo_pinjaman 
                                                from (
                                                    select 
                                                                                    a.person_id,
                                                            case when a.transaction_type_id = 2 then a.loan_amount + a.rates_total else a.loan_amount end as loan_amount,
                                                            (select case when a.transaction_type_id = 2 then sum(b.principal_amount) + sum(b.rates_amount)  
                                                                    else sum(b.principal_amount) end as principal_amount
                                                                from sp_loan_installments b where b.loan_id = a.loan_id and b.status = 2 and b.is_deleted = 0)total_bayar_pokok 
                                                    from sp_loans a where a.status =1 and a.transfer_date is not null and a.is_deleted = 0)c where c.person_id = sp_loans.person_id
                                        )saldo_pinjaman'))
                    ->find($id);
        
        return $return;
    }

    public static function savePelunasan(){
        // $model = new LoanInstallments();
        // $model->loan_id = $data->loan_id;
        // $model->periode = date('mY');
        // // $model->ref_code = 
        // $model->principal_amount = $jumlahBayarPokok;
        // $model->rates_amount = $jumlahBayarAngsuran;

        // $model->payment_method = $req->payment_method;
        // if($model->payment_method == 1){
        //     $bank = CompanyBankAccount::with('bank')->where('divisi','SP')->first();
        //     $model->company_bank_id = $bank->bank_account_id;
        // }
        // $model->status = 2;
        // $model->payment_date = date('Y-m-d');
        // $model->created_at = date('Y-m-d H:m:i');
        // $model->created_by = $user_id;
        // if($model->save()){

        // $valRef = "(select concat('06',DATE_FORMAT(now(), '%Y%m'), LPAD((select last_number + 1 from seq_rd where name = 'seq_ref_cicilanpinjaman'), 6, '0')))";
        // DB::table('sp_loan_installments')
        //     ->where('loan_detail_id', $model->loan_detail_id)
        //     ->update(['ref_code' =>DB::raw($valRef)]);

        //     TriggerSimpanPinjam::cicilanPinjaman($model->loan_detail_id , $user_id);
        // }

        // notify()->flash('Success!', 'success', [
        //     'text' => 'Penerimaan Angsuran Pinjaman Berhasil',
        // ]);
        
        // DB::commit();
        // $person_id = Crypt::encrypt($data->person_id);
        // return redirect('simpanpinjam/pinjaman/'.$person_id);
    }

    public static function getpinjamanbelumlunas($person_id){
        $queryPinjaman = DB::select("select * from (select 
                                            a.loan_id,
                                            case when a.transaction_type_id = 2 then a.loan_amount + a.rates_total else a.loan_amount end as loan_rates_amount,
                                            (select 
                                                            ifnull(sum(b.principal_amount),0) principal_amount
                                                    from sp_loan_installments b 
                                                    where b.loan_id = a.loan_id and b.status = 2 and is_deleted = 0)total_bayar,
                                            a.loan_amount,
                                            a.rates_amount
                                    from sp_loans a 
                                    where a.person_id = ? and a.status <> 0 and a.transfer_date is not null and is_deleted = 0
                                            and a.transaction_type_id = 1)c
                                    where (c.loan_amount - c.total_bayar) > 0", array($person_id));

        return $queryPinjaman;
    }

    /**
     * check sudah baca ketika status dihapus
     */
    public static function getNotifHasntRead($personid)
    {
        $valueType = '0';
        switch(Auth::user()->level_id){
            case 9: $valueType = '"read_by_bendahara":1'; break;
            case 8: $valueType = '"read_by_wakil":1'; break;
            case 7: $valueType = '"read_by_ketua":1'; break;
            case 10: $valueType = '"read_by_manajer":1'; break;
            case 11: $valueType = '"read_by_staffall":1'; break;
            case 12: $valueType = '"read_by_staffsp":1'; break;
        }

        $query = collect(DB::select("select 
                                        sp_loans.*, apr.*, case when apr.status_desc is null or length(apr.status_desc) = 0 then '$valueType' else CONCAT(apr.status_desc,' , ','$valueType') end as valueStatus
                                    from sp_loans
                                    left join (select @rownum_a := case when @groupnum_a = apr_loan.loan_id then @rownum_a + 1 else 1 end AS rank, IFNULL(apr_loan.level_id,0)lvl_id,  apr_loan.*, @groupnum_a := apr_loan.loan_id
                                                from (SELECT @rownum_a := 1, @groupnum_a:=0) r1, sp_loan_approvals apr_loan order by apr_loan.approval_date DESC, apr_loan.loan_approval_id desc)apr on 
                                                            apr.loan_id = sp_loans.loan_id and apr.rank = 1
                                    where 
                                            person_id = ?
                                            and apr.status = 0
                                            and (apr.status_desc is null or apr.status_desc REGEXP '$valueType' = 0)", array($personid)));
        
        // foreach($query as $r){
        //     echo '{'.str_replace('{','',str_replace('}','',$r->valueStatus)).'}';
        // }

        return $query;
    }
}