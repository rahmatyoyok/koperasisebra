<?php

namespace App\Http\Models\SimpanPinjam;

use Illuminate\Database\Eloquent\Model;
use DB, Auth;

class InvestmentSavings extends Model
{
    protected $table = 'sp_investment_savings';
    public $timestamps = false;

    protected $primaryKey = 'investment_saving_id';

    protected $fillable = [
		'transaction_type','person_id', 'ref_code' ,'tr_date',  'payment_ref', 'total', 'payment_method', 'payment_date',
        'status', 'is_deleted', 'created_at', 'created_by','updated_at', 'updated_by'
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'person_id');
    }

    public function approval_investasi()
    {
        return $this->hasMany('InvestmentSavingApprovals');
    }

    public static function getListSimpananInvestasi()
    {   
        
        $userLevel = Auth::user()->level_id;

        $dts_w = '"read_by_bendahara":1'; $dts_w = '"read_by_wakil":1'; $dts_k = '"read_by_ketua":1'; $dts_m = '"read_by_manajer":1'; $dts_sa = '"read_by_staffall":1'; $dts_sp = '"read_by_staffsp":1'; 
        $return = InvestmentSavings::with('anggota')->with('anggota.customer')
                    ->leftJoin(DB::raw("(select @rownum_a:= case when @groupnum_a = apr_inv.investment_saving_id then @rownum_a + 1 else 1 end AS rank, 
                    IFNULL(apr_inv.level_id,0)lvl_id, apr_inv.*, @groupnum_a := investment_saving_id from sp_investment_saving_approvals apr_inv, (SELECT @rownum_a := 1, @groupnum_a:=0) r1 order by apr_inv.approval_date DESC, apr_inv.investment_saving_approval_id desc)apr"),
                    function($join){
                        $join->on("apr.investment_saving_id","=","sp_investment_savings.investment_saving_id")->whereRaw(DB::raw('apr.rank = 1'));
                         }
                    )
                    ->select(['person_id', 
                                DB::raw('max(tr_date) as tr_date'),  
                                'payment_ref', 
                                DB::raw('(select sum(a.total) - ifnull((select sum(b.total) from sp_investment_savings b where b.transaction_type <> 1 and b.status <> 0 and b.person_id = a.person_id),0) as saldo
                                            from sp_investment_savings a
                                            where a.transaction_type = 1 and a.status <> 0 and a.person_id = sp_investment_savings.person_id
                                            group by person_id) as total'),
                                DB::raw('sum(case 
                                                when 9 = '.$userLevel.' /*bendahara*/ and apr.lvl_id is null then 1
                                                when 8 = '.$userLevel.' /*wakil*/ and apr.lvl_id = 9 and apr.status = 1 then 1 
                                                when 7 = '.$userLevel.' /*ketua*/ and apr.lvl_id = 8 and apr.status = 1 then 1 
                                                when 10 = '.$userLevel.' /*manajer*/ and apr.lvl_id = 7 and sp_investment_savings.status = 0 and apr.status = 1 then 1 
                                                when 11 = '.$userLevel.' /*Staff All*/ and apr.lvl_id = 7 and sp_investment_savings.status = 0 and apr.status = 1 then 1 
                                                when 12 = '.$userLevel.' /*Staff SimpanPinjam*/ and apr.lvl_id = 7 and sp_investment_savings.status = 0 and apr.status = 1 then 1
                                            else 0 end)need_approval'),
                                DB::raw("sum(
                                    case
                                        when 9 = ".$userLevel." /*bendahara*/ and apr.status = 0 and apr.status_desc REGEXP '$dts_w' = 0 then 1  
                                        when 8 = ".$userLevel." /*wakil*/ and apr.status = 0 and apr.status_desc REGEXP '$dts_w' = 0 then 1 
                                        when 7 = ".$userLevel." /*ketua*/ and apr.status = 0 and apr.status_desc REGEXP '$dts_k' = 0 then 1 
                                        when 10 = ".$userLevel." /*manajer*/ and apr.status = 0 and apr.status_desc REGEXP '$dts_m' = 0 then 1 
                                        when 11 = ".$userLevel." /*Staff All*/  and apr.status = 0 and apr.status_desc REGEXP '$dts_sa' = 0 then 1 
                                        when 12 = ".$userLevel." /*Staff SimpanPinjam*/ and apr.status = 0 and apr.status_desc REGEXP '$dts_sp' = 0 then 1 
                                        else 0 end
                                )read_status")

                    ])
                    ->where('is_deleted','0')
                    ->groupBy('person_id');
                    // echo $return->toSql(); die;
        return $return->get();
    }

    public static function getSaldoKaryawan($id)
    {
        $query = collect(DB::select('select sum(a.total) - (select ifnull(sum(b.total),0)
                                        from sp_investment_savings b
                                            where b.person_id = a.person_id and b.transaction_type = 2
                                                    and b.status = 1 and b.is_deleted = 0) as saldo
                                    from sp_investment_savings a
                                    where a.person_id = ? and a.transaction_type in (1,3)
                                            and a.status = 1 and a.is_deleted = 0', array($id)));

        return $query->first();
    }

    public static function getdataInvesasiperCustomer($person_id){
        $query = DB::select("select 
                                    a.*, dt.lastlevel_app, dt.level_name level_name, dt.description level, dt.status approval_status, dt.name user_approval
                                from sp_investment_savings a
                                left join (select 
                            @rownum_a:= case when @groupnum_a = b.investment_saving_id then @rownum_a + 1 else 1 end AS rank, 
                            b.*, u.name, l.id lastlevel_app, l.name level_name, l.description,
                            @groupnum_a := b.investment_saving_id
                            from (SELECT @rownum_a := 1, @groupnum_a:=0) r1, sp_investment_saving_approvals b
                            INNER JOIN users u on u.id = b.approval_by
                            INNER JOIN levels l on l.id = b.level_id 
                            order by b.approval_date desc, b.investment_saving_approval_id desc )dt on dt.investment_saving_id = a.investment_saving_id and dt.rank = 1
                                where a.person_id =  ?", array($person_id));
        
        return $query;
    }

    public static function getdataInvesasiperTransaksi($trId){
        $query = DB::select("select 
                                a.*, dt.lastlevel_app, dt.level_name level_name, dt.description level, dt.status approval_status, dt.name user_approval
                            from sp_investment_savings a
                            left join (select b.*, u.name, l.id lastlevel_app, l.name level_name, l.description
                                        from sp_investment_saving_approvals b
                                        inner join (select max(b.updated_at)last_approval, b.investment_saving_id
                                                    from sp_investment_saving_approvals b
                                                    group by b.investment_saving_id )t on t.investment_saving_id = b.investment_saving_id and t.last_approval = b.updated_at
                                        INNER JOIN users u on u.id = b.approval_by
                                        INNER JOIN levels l on l.id = b.level_id )dt on dt.investment_saving_id = a.investment_saving_id
                            where a.investment_saving_id =  ?", array($trId));
        
        return $query;
    }


    public static function getByIdInvestasi($id)
    {
        $return = InvestmentSavings::with('anggota')->with('anggota.customer')->with('anggota.customer.bank')->find($id);

        return $return;
    }

    public static function getSaldoByPerson($id){
        $sql = collect(DB::select("select
                sum(a.total) - ifnull((select sum(b.total) from sp_investment_savings b where b.transaction_type <> 1 and b.status <> 0 and b.person_id = a.person_id),0) as saldo
                from sp_investment_savings a
                where 
                    a.transaction_type = 1
                    and a.status <> 0
                    and a.person_id = ?
                ", array($id)));
    
        
        return $sql->first();
    }

    public static function prosessKalkulasiBunga($userId, $periode){

        $periodeDate = substr($periode, -4).'-'.substr($periode, 0, 2).'-01';
        // echo $periodeDate;die;
        $returnKalkulasi = collect(DB::select('SELECT KalkulasiBungaInvestasiPeriode(?, ?) AS nb', array($periodeDate, $userId)))->first()->nb;

        return $returnKalkulasi;

    }

    public static function getlistHistoryBunga($periode, $type = "", $listPerson = ""){

        $xlistPerson =  ($type == 'exportposting') ? " and FIND_IN_SET(p.person_id,'".$listPerson."')":'';

        $squery = "select 
                        p.first_name, p.last_name, p.niak, p.id_card_number, b.nama_bank bank, c.account_number,
                        case 
                                when p.member_type = 0 then 'Mengundurkan Diri'
                                when p.member_type = 1 then 'Aktif'
                                when p.member_type = 2 then 'Pasif'
                        end as member_type, 
                        case 
                                when p.member_status = 1 then 'Karyawan'
                                when p.member_status = 2 then 'Pensiunan'
                                when p.member_status = 3 then 'SCM'
                        end as member_status, 
                        c.company_name, c.jabatan, c.nomor_induk, a.person_bank_id, a.rekening_no, 
                        a.*
                from sp_investment_interests a
                inner join ospos_people p on p.person_id = a.person_id
                inner join ospos_customers c on p.person_id = c.person_id	
                left join banks b on b.bank_id = c.bank_id
                where a.periode = ? ".$xlistPerson;

        $query = DB::select($squery, array($periode));
        return $query;             
    }

    public static function postingKalkulasiPerPeriode($userId, $periode){
        $periodeDate = substr($periode, -4).'-'.substr($periode, 0, 2).'-01';
        $returnPosting = collect(DB::select('SELECT PostingKalkulasiPerPeriodeBungaInvestasi(?) AS nb', array($periodeDate)))->first()->nb;
        // echo $periodeDate;

        // return false;
        if($returnPosting == 1){
            return true;
        }
        else{
            return false;
        }
    }

    public static function transferKalkulasiPerPeriodeuser($userId, $periode, $listNiak, $attacment){

        $reurn = DB::table('sp_investment_interests')
                    ->where('is_deleted',0)
                    ->where('status',1)
                    ->where('periode', $periode)
                    ->whereRaw("FIND_IN_SET(sp_investment_interests.person_id, (SELECT GROUP_CONCAT(m.person_id) FROM ospos_people m JOIN ospos_people d ON (m.person_id = d.person_id)
                                                                where FIND_IN_SET(m.niak,'".$listNiak."')))")
                    ->update(['status' => 2, 'updated_by' => $userId, 'attacment' => $attacment]);

        
        if($reurn == 1){

            
            return true;
        }
        else{
            return false;
        }
    }

    public static function getNotifHasntRead($personid)
    {
        $valueType = '0';
        switch(Auth::user()->level_id){
            case 8: $valueType = '"read_by_wakil":1'; break;
            case 7: $valueType = '"read_by_ketua":1'; break;
            case 10: $valueType = '"read_by_manajer":1'; break;
            case 11: $valueType = '"read_by_staffall":1'; break;
            case 12: $valueType = '"read_by_staffsp":1'; break;
        }

        $query = collect(DB::select("select sp_investment_savings.*, apr.*, case when apr.status_desc is null or length(apr.status_desc) = 0 then '$valueType' else CONCAT(apr.status_desc,' , ','$valueType') end as valueStatus
                                from sp_investment_savings
                                left join (select @rownum_a:= case when @groupnum_a = apr_inv.investment_saving_id then @rownum_a + 1 else 1 end AS rank, 
                                            IFNULL(apr_inv.level_id,0)lvl_id, apr_inv.*, @groupnum_a := investment_saving_id from sp_investment_saving_approvals apr_inv, (SELECT @rownum_a := 1, @groupnum_a:=0) r1 order by apr_inv.approval_date DESC, apr_inv.investment_saving_approval_id desc)apr on apr.investment_saving_id = sp_investment_savings.investment_saving_id and rank = 1
                                where 
                                    person_id = ?
                                    and apr.status = 0
                                    and apr.status_desc REGEXP '$valueType' = 0", array($personid)));
        
        // foreach($query as $r){
        //     echo '{'.str_replace('{','',str_replace('}','',$r->valueStatus)).'}';
        // }

        return $query;
    }
}