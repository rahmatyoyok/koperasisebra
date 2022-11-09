<?php

namespace App\Http\Models\SimpanPinjam;

use Illuminate\Database\Eloquent\Model;
use DB;

class Kalkulasi extends Model
{
    public static function getKalkulasi($periode, $type = "", $listPerson = ""){

        $sStatustype_1 = ($type == 'exportposting') ? ' and ps.status = 1':'';
        $sStatustype_2 = ($type == 'exportposting') ? ' and sw.status = 1':'';
        $sStatustype_3 = ($type == 'exportposting') ? ' and ccl.status = 1':'';
        $sStatustype_4 = ($type == 'exportposting') ? ' and ccl.status = 1':'';

        $xlistPerson =  ($type == 'exportposting') ? " and FIND_IN_SET(p.person_id,'".$listPerson."')":'';

        $periodeToko = substr($periode,2).substr($periode,0,2).tanggalCutoffHutangToko();

        $squery = "select 
                            p.person_id, p.first_name, p.last_name, p.niak, p.id_card_number, b.nama_bank bank, c.account_number,
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
                            c.company_name, c.bank_id, c.account_number, 
                            sum(simpanan_pokok)simpanan_pokok, sum(simpanan_wajib)simpanan_wajib, sum(principal_amount)pinjaman_pokok, sum(rates_amount)bunga_pinjaman, sum(pokok_elektronik)pokok_elektronik, sum(bunga_elektronik)bunga_elektronik, 
                            sum(hutang_toko)hutang_toko,
                            tmps.status_id,
                            tmps.payment_status
                    from 
                    (
                                    select 
                                        ps.person_id, 
                                        ps.total simpanan_pokok, 0 simpanan_wajib, 0 principal_amount, 0 rates_amount, 0 pokok_elektronik, 0 bunga_elektronik, 0 hutang_toko,
                                        ps.status status_id,
                                        case 
                                                when ps.status = 0 then 'Kalkulasi / Entry'
                                                when ps.status = 1 then 'Posting'
                                                when ps.status = 2 then 'Terbayar'
                                        end as status,
                                        case when ps.status = 2 then 1 else 0 end as payment_status
                                    from sp_principal_savings ps
                                    where 
                                        ps.is_deleted = 0 and date_format(ps.tr_date,'%m%Y') = '".$periode."' ".$sStatustype_1."
                                union all
                                    select
                                        sw.person_id, 0 simpanan_pokok, sw.total simpanan_wajib, 0 principal_amount, 0 rates_amount, 0 pokok_elektronik, 0 bunga_elektronik, 0 hutang_toko,
                                        sw.status status_id,
                                        case 
                                            when sw.status = 0 then 'Kalkulasi / Entry'
                                            when sw.status = 1 then 'Posting'
                                            when sw.status = 2 then 'Terbayar'
                                        end as status,
                                        case when sw.status = 2 then 1 else 0 end as payment_status
                                    from sp_periodic_savings sw
                                    where 
                                        sw.is_deleted = 0 and sw.periode = '".$periode."' ".$sStatustype_2."
                                union all
                                    select 
                                        pnjm.person_id, 0 simpanan_pokok, 0 simpanan_wajib, ccl.principal_amount, ccl.rates_amount, 0 pokok_elektronik, 0 bunga_elektronik, 0 hutang_toko,
                                        ccl.status status_id,
                                        case 
                                            when ccl.status = 0 then 'Kalkulasi / Entry'
                                            when ccl.status = 1 then 'Posting'
                                            when ccl.status = 2 then 'Terbayar'
                                        end as status,
                                        case when ccl.status = 2 then 1 else 0 end as payment_status
                                    from sp_loan_installments ccl 
                                    inner join sp_loans pnjm on ccl.loan_id = pnjm.loan_id and pnjm.transaction_type_id = 1 and pnjm.status = 1 and pnjm.is_deleted = 0
                                    where 
                                        ccl.is_deleted = 0 and ccl.periode = '".$periode."' ".$sStatustype_3."
                            union all
                                select 
                                            pnjm.person_id, 0 simpanan_pokok, 0 simpanan_wajib, 0 principal_amount, 0 rates_amount, ccl.principal_amount pokok_elektronik, ccl.rates_amount bunga_elektronik, 0 hutang_toko,
                                            ccl.status status_id,
                                            case 
                                                when ccl.status = 0 then 'Kalkulasi / Entry'
                                                when ccl.status = 1 then 'Posting'
                                                when ccl.status = 2 then 'Terbayar'
                                            end as status,
                                            case when ccl.status = 2 then 1 else 0 end as payment_status
                                        from sp_loan_installments ccl 
                                        inner join sp_loans pnjm on ccl.loan_id = pnjm.loan_id and pnjm.transaction_type_id = 2 and pnjm.status = 1 and pnjm.is_deleted = 0
                                        where 
                                            ccl.is_deleted = 0 and ccl.periode = '".$periode."' ".$sStatustype_4."
                            union all  
                                select 
                                    po.person_id, 0 simpanan_pokok, 0 simpanan_wajib, 0 principal_amount, 0 rates_amount, 0 pokok_elektronik, 0 bunga_elektronik,
                                    sum(p.payment_amount)hutang_toko,                	                
                                    p.payment_status,
                                    case 
                                                        when p.payment_status = 0 then 'Kalkulasi / Entry'
                                                        when p.payment_status = 1 then 'Terbayar'
                                        end as status,
                                        p.payment_status
                                from ospos_sales_payments p
                                inner join ospos_sales s on  s.sale_id = p.sale_id 
                                inner join ospos_people po on po.person_id = s.customer_id
                                where 1=1
                                    and DATE_FORMAT(p.payment_time, '%Y%m%d') < ".$periodeToko."
                                    and p.payment_type = 'Kredit' 
                                group by po.person_id, p.payment_status    
                    )tmps
                    inner join ospos_people p on p.person_id = tmps.person_id and p.is_deleted = 0 and p.status = 1
                    inner join ospos_customers c on p.person_id = c.person_id	
                    left join banks b on b.bank_id = c.bank_id	
                    where 
                        1=1 ".$xlistPerson."
                    group by p.person_id, p.person_id, p.first_name, p.last_name, p.niak, p.id_card_number, p.member_type, p.member_status, c.company_name, c.bank_id, c.account_number, tmps.payment_status, tmps.status_id
                    order by member_type, member_status, first_name";
        // echo $squery;
        $query = DB::select($squery);
        return $query;             
    }

    public static function processKalkulasiPerPeriode($userId, $periode){ 
        $periodeDate = substr($periode, -4).'-'.substr($periode, 0, 2).'-01';
        
        $returnPokok = collect(DB::select('SELECT KalkulasiSpPokokUserBaru(?) AS nb', array($userId)))->first()->nb;
        $returnWajib = collect(DB::select('SELECT KalkulasiSpWajibPerPeriode(?, ?) AS nb', array($periodeDate, $userId)))->first()->nb;
        $returnPinjaman = collect(DB::select('SELECT KalkulasiCicilanPinjamanPeriode(?, ?) AS nb', array($periodeDate, $userId)))->first()->nb;
        $returnElektronik = collect(DB::select('SELECT KalkulasiCicilanElektronikPeriode(?, ?) AS nb', array($periodeDate, $userId)))->first()->nb;
        
        $validRtn = (int)$returnPokok + (int)$returnWajib + (int)$returnPinjaman + (int)$returnElektronik;
        if($validRtn == 4){
            return true;
        }
        else{
            return false;
        }

    }

    public static function postingKalkulasiPerPeriode($userId, $periode, $listPerson){
        $periodeDate = substr($periode, -4).'-'.substr($periode, 0, 2).'-01';
        $returnPokok = collect(DB::select('SELECT PostingKalkulasiPerPeriode(?,?) AS nb', array($periodeDate, $listPerson)))->first()->nb;

        if($returnPokok == 1){
            return true;
        }
        else{
            return false;
        }
    }

    public static function peneriamaanKalkulasiPerPeriodeuser($userId, $periode, $niak, $bank_id, $attacment, $spPokok, $spWajib, $usp, $elektronik, $hutangToko){
        // $returnPokok = collect(DB::select("SELECT processPenerimaanKalkulasiperUser('".$periode."', '".$niak."', ".$bank_id.", '".$attacment."', ".$spPokok.", ".$spWajib.", ".$usp.", ".$elektronik.", ".$userId.") AS nb"))->first()->nb;
        $tglCutoff = tanggalCutoffHutangToko();

        
        $returnPokok = collect(DB::select("SELECT processPenerimaanKalkulasiperUser('".$periode."', '".$niak."', ".$bank_id.", '".$attacment."', ".$spPokok.", ".$spWajib.", ".$usp.", ".$elektronik.", ".$hutangToko.", ".$tglCutoff.", ".$userId.") AS nb"))->first()->nb;
        

        
        // echo "SELECT processPenerimaanKalkulasiperUser('".$periode."', '".$niak."', ".$bank_id.", '".$attacment."', ".$spPokok.", ".$spWajib.", ".$usp.", ".$elektronik.", ".$userId.") AS nb;<br>";
        if($returnPokok == 1){
            return true;
        }
        else{
            return false;
        }
    }
}