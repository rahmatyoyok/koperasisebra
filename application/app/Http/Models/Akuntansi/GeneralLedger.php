<?php

namespace App\Http\Models\Akuntansi;

use Illuminate\Database\Eloquent\Model;
use DB;

class GeneralLedger extends Model
{
    protected $table = 'ak_general_ledgers';
    public $timestamps = false;

    protected $primaryKey = 'gl_id';

    protected $fillable = [
        'coa_id','periode','posting_no','posting_date','beginning_balance','ending_balance','fiscal_year','fiscal_month',
        'created_at','created_by','updated_at','updated_by'
    ];

    public static function generateNoPosting($periode){
        $pr = substr($periode,-2).substr($periode,0,2);
        $response = $pr."/PS/";
        $seq = collect(DB::select("select ifnull(max(cast(SUBSTR(posting_no, 9, 7) as int)),0)+1 as seq from ak_general_ledgers"))->first();

        return $response.sprintf( '%04d',(int)$seq->seq); 
    }

    public static function getBukuBesar($coa_id, $periode){
        $crPeriode = substr($periode,-4).substr($periode,0,2); 
        $periodeX = substr($periode,-4).'-'.substr($periode,0,2).'-01';
        $periodeBefore = date('mY', strtotime('-1 day', strtotime($periodeX))); 

        $querys = "select * from (
                        select b.tr_date, b.posting_no, b.desc, reff_no, is_trigger, tigger_table, tigger_table_key_id, a.debit, a.kredit ,0 saldo
                        from ak_journal_details a
                        inner join ak_journal_headers b on b.journal_header_id = a.journal_header_id and b.is_deleted = 0
                        where 
                                a.coa_id = ".$coa_id."
                                and cast(date_format(b.tr_date, '%Y%m') as int) = ".$crPeriode."
                        union all
                        select 
                                null, null, 'Saldo Periode Sebelumnya', null, null, null, null, 0,0,
                                case 
                                    when glx.saldoawal is not null or glx.saldoawal is not null then if(glx.saldoawal is not null, glx.saldoawal, glx.saldoakhir)
                                    when (select ca.khaidah_type from ak_coa ca where ca.coa_id = xas.coa_id) = 1 then 
                                        ifnull(sum(xas.debit),0) - ifnull(sum(xas.kredit),0)
                                    when (select ca.khaidah_type from ak_coa ca where ca.coa_id = xas.coa_id) = 2 then ifnull(sum(xas.kredit),0) - ifnull(sum(xas.debit),0)
                                    else 0 
                                end as saldo
                        from 
                                (select aa.* from ak_journal_details aa, ak_journal_headers ba where ba.journal_header_id = aa.journal_header_id and ba.is_deleted = 0 
                                        and aa.coa_id = ".$coa_id."
                                        and cast(date_format(ba.tr_date, '%Y%m') as int) < ".$crPeriode.")xas,
                                (
                                    select sum(s.saldoawal)saldoawal, sum(s.saldoakhir)saldoakhir from 
                                    ( select count(*)cx, gla.beginning_balance saldoawal, null saldoakhir from ak_general_ledgers gla where gla.periode = '".$periode."' and gla.coa_id = ".$coa_id."
                                        union all
                                        select count(*), null, glb.ending_balance akhir from ak_general_ledgers glb where glb.periode = '".$periodeBefore."' and glb.coa_id = ".$coa_id.")s
                                )glx
                    )xt order by xt.tr_date";
        $query = DB::select($querys);
        return $query;   
    }

    public static function getPostingByPeriode($periode){
        return GeneralLedger::where('periode', $periode)->count();
    }

    /**
     * Eksekusi Posting
     * 
     * @param string periode mmyyyy
     * @param string nomor postng
     * @param int user id
     * 
     * @return int 
     */
    public static function eksekusiPostingBukuBesar($periode, $posting_no, $user_id){
        $response = false;

        $dates = substr($periode,-4).'-'.substr($periode,0,2).'-01';
        $d      = strtotime($dates);

        DB::beginTransaction();
        try{

            $noPosting = GeneralLedger::generateNoPosting($periode);
            // Update Header Jurnal
            HeaderJurnals::whereRaw("cast(date_format(tr_date, '%Y%m') as int) = ".date('Ym', $d))
                            ->update(array('fiscal_year' => date('Y', $d), 
                                            'fiscal_month' => date('m', $d),
                                            'posting_no' => $posting_no,
                                            'updated_by' => $user_id
                                        ));
            
            // Proses Hitung Saldo Awal Akhir 
            $returnPokok = collect(DB::select('SELECT ProsesPostingJurnalPerPeriode(?, ?, ?) AS nb', array( $dates, $noPosting, $user_id)))->first()->nb;
            
            GeneralLedger::kalkulasiLabaRugi($periode, $user_id);

            DB::commit();
            $response = true;
        }
        catch(ValidationException $e){
            DB::rollback();
            print("ERROR VALIDATION");
            notify()->flash('Gagal!', 'warning', [
                'text' => 'Posting Jurnal Gagal',
            ]);
            
            die();
        }catch(\Exception $e){
            DB::rollback();
            throw $e;
            print("ERROR EXCEPTION");
            die();
        }

        return $response;
    }

    public static function getLIstPosting(){
        return GeneralLedger::selectRaw("distinct periode, posting_no, date_format(posting_date, '%Y-%m-%d')tanggal")
                                ->orderByRaw("cast(fiscal_year as int) desc, cast(fiscal_month as int) desc")->get();
    }

    public static function getlistLabaRugi($periode, $divisi, $formulaTypes){
        $response = [];
        // Validasi Periode
        if(strlen(trim($periode)) == 6){
            // Validasi Divisi
            if(in_array($divisi,array("SP", "UM", "TK"))){

                
                $grIdDesc = "";
                switch($formulaTypes){
                    case "PENDAPATAN" : $grIdDesc = "'PENDAPATAN'"; break;
                    case "BEBANLANGSUNG" : 
                        $grIdDesc = ($divisi == 'SP') ? "'BEBAN LANGSUNG'": (($divisi == 'UM') ? "'BEBAN JASA','BEBAN MATERIAl'": (($divisi == 'TK') ? "'BEBAN JASA','BEBAN MATERIAl'": "")); 
                    break;
                    case "BEBANOPERASIONAL" :
                        if($divisi == 'SP')
                            $grIdDesc = "'BEBAN JASA','BEBAN MATERIAL','BEBAN ADMINITRASI','BEBAN KARYAWAN'";
                        elseif($divisi == "UM")
                            $grIdDesc = "'BEBAN ADMINITRASI','BEBAN KARYAWAN', 'BEBAN PENYUSUTAN'";
                        elseif($divisi == "TK")
                            $grIdDesc = "'BEBAN ADMINITRASI','BEBAN KARYAWAN'";
                        
                    break;
                    case "PENDAPATANLUARUSAHA" :
                        $grIdDesc = "'PENDAPATAN DILUAR USAHA'"; 
                    break;
                    case "BEBANLUARUSAHA" :
                        $grIdDesc = "'BEBAN DILUAR USAHA'"; 
                    break;
                }

                if($grIdDesc <> ""){
                    $query  = "Select gr.desc group_desc, @rownum_a := case when @groupnum_a = gr.desc then @rownum_a + 1 else 1 end AS counted, hr.desc header_desc, c.*, ifnull(gl.ending_balance ,0)ending_balance,
                                @groupnum_a := gr.desc
                                from (SELECT @rownum_a := 1, @groupnum_a:='') r1, ak_coa c
                                left join ak_general_ledgers gl on gl.coa_id = c.coa_id and gl.periode = '".$periode."'
                                inner join ak_coa hr on c.header_coa_id = hr.coa_id
                                inner join ak_group_coa gr on gr.group_coa_id = c.group_coa_id
                                where 
                                    c.header_coa_id <> 0
                                    and gr.desc in (".$grIdDesc.")
                                    -- and LEFT(c.code, 2) = '".$divisi."'
                                order by gr.group_coa_id, 2 desc";
                    // echo $query;
                    $response =  DB::select($query);
                    // dd($response);
                }
            }
        }

        return $response;
    }

    public static function getlistLabaRugiNew($periode, $formulaTypes){
        $response = [];
        // Validasi Periode
        if(strlen(trim($periode)) == 6){
            // Validasi Divisi
                $grIdDesc = "";
                $listCoka = "";
                switch($formulaTypes){
                    case "PENDAPATAN" : 
                            $grIdDesc = "'PENDAPATAN'"; 
                            $listCoka = "and c.code in ('SP-A101','TK-A201', 'TK-A207','TK-A210', 'UM-A301','UM-A303','UM-A304','UM-A305','UM-A399')";
                        break;
                    case "BEBANLANGSUNG" : 
                        $grIdDesc = "'BEBAN LANGSUNG','BEBAN MATERIAL','BEBAN JASA','BEBAN ADMINISTRASI','BEBAN KARYAWAN','BEBAN PENYUSUTAN'";
                        $listCoka = "and c.code in ('SP-C201','UM-D201','UM-D202','UM-D204','UM-D205','UM-D206','UM-D299','UM-E301','UM-E302','UM-E303','UM-E304','UM-E306','UM-E307','UM-E399','UM-F401','UM-F402','UM-F403','UM-F404','UM-F405','UM-F408','UM-F499','UM-G501','UM-G502','UM-G503','UM-G504','UM-G505','UM-G506','UM-G507','UM-G508','UM-H610','UM-H611','UM-H612','UM-H613','TK-E361')";
                    break;
                    // case "BEBANOPERASIONAL" :
                    //     if($divisi == 'SP')
                    //         $grIdDesc = "'BEBAN JASA','BEBAN MATERIAL','BEBAN ADMINITRASI','BEBAN KARYAWAN'";
                    //     elseif($divisi == "UM")
                    //         $grIdDesc = "'BEBAN ADMINITRASI','BEBAN KARYAWAN', 'BEBAN PENYUSUTAN'";
                    //     elseif($divisi == "TK")
                    //         $grIdDesc = "'BEBAN ADMINITRASI','BEBAN KARYAWAN'";
                        
                    // break;
                    case "PENDAPATANLUARUSAHA" :
                        $grIdDesc = "'PENDAPATAN DILUAR USAHA'"; 
                        $listCoka = "and c.code in ('UM-B101','UM-B102','UM-B103','UM-B104','UM-B108')";
                        
                    break;
                    case "BEBANPAJAK" :
                        $grIdDesc = "'BEBAN PAJAK'"; 
                        $listCoka = "and c.code in ('UM-J805')";
                        
                    break;
                    case "BEBANLUARUSAHA" :
                        $grIdDesc = "'BEBAN DILUAR USAHA','BEBAN PAJAK'";                         
                        $listCoka = "and c.code in ('UM-I702','UM-J804')";
                    break;
                }

                if($grIdDesc <> ""){
                    $query  = "Select 
                                gr.desc group_desc, @rownum_a := case when @groupnum_a = gr.desc then @rownum_a + 1 else 1 end AS counted, hr.desc header_desc, c.*, 
                                    case 
                                        when left(c.code,2) = 'SP' then 'Simpan Pinjam'
                                        when left(c.code,2) = 'TK' then 'Toko'
                                        when left(c.code,2) = 'UM' then 'Usaha Umum'
                                    end divisi, 
                                    ifnull(gl.ending_balance ,0)ending_balance,
                                    @groupnum_a := gr.desc
                                from (SELECT @rownum_a := 1, @groupnum_a:='') r1, ak_coa c
                                left join ak_general_ledgers gl on gl.coa_id = c.coa_id and gl.periode = '".$periode."'
                                inner join ak_coa hr on c.header_coa_id = hr.coa_id
                                inner join ak_group_coa gr on gr.group_coa_id = c.group_coa_id
                                where 
                                    c.header_coa_id <> 0
                                    and gr.desc in (".$grIdDesc.")
                                    ".$listCoka."
                                order by left(c.code,2), c.activity_code";
                    // echo $query;
                    $response =  DB::select($query);
                    // dd($response);
                }
            
        }

        return $response;
    }

    public static function getlistNeraca($periode, $formulaTypes){
        $response = [];
        // Validasi Periode
        if(strlen(trim($periode)) == 6){
            
            $grIdDesc = "";
            switch($formulaTypes){
                case "ASETLANCAR" : $grIdDesc = "'ASET LANCAR'"; break;
                case "ASETTETAP" : $grIdDesc = "'ASET TETAP'"; break;
                case "ASETLAIN" : $grIdDesc = "'ASET LAIN'"; break;
                
                case "LIABILITASLANCAR" : $grIdDesc = "'LIABILITAS LANCAR'"; break;
                case "LIABILITASJANGKAPANJANG" : $grIdDesc = "'LIABILITAS JANGKA PANJANG'"; break;
                case "EKUITAS" : $grIdDesc = "'EKUITAS'"; break;
            }

            if($grIdDesc <> ""){
                $query  = "select gr.desc group_desc, hr.code, hr.desc header_desc, sum(gl.ending_balance)saldo
                            from ak_coa c
                            inner join ak_general_ledgers gl on gl.coa_id = c.coa_id and gl.periode = ?
                            inner join ak_coa hr on c.header_coa_id = hr.coa_id
                            inner join ak_group_coa gr on gr.group_coa_id = c.group_coa_id
                            where 
                                    gr.desc = (".$grIdDesc.")
                            group by hr.coa_id
                            order by cast(hr.code as int)";
                
                $response = DB::select($query, array($periode));
            }
        }
        return $response;
    }

    public static function getlistNeracaNew($periode, $formulaTypes){
        $response = [];
        // Validasi Periode
        if(strlen(trim($periode)) == 6){
            
            $CodeIn = "";
            switch($formulaTypes){

                // AKTIVA

                case "KASSETARAKAS" : $CodeIn = "'1000101/UM','1000102/USP','1000103/TK','1000104/UM','1000105/UM','1000106/USP','1000107/TK','1000109/USP'"; break;
                case "PIUTANGUSAHA" : $CodeIn = "'1000201/UM','1000204/TK','1000210/UM','1000401/USP','1000402/USP'"; break;
                case "PIUTANGDEVIDEN" : $CodeIn = "'1000209/UM'"; break;
                case "PERSEDIAANBARANG" : $CodeIn = "'1000601/TK', '1000602/UM'"; break;
                case "BIAYADIBAYARDIMUKA" : $CodeIn = "'1000702/UM'"; break;
                case "NILAIPEROLEHANASET" : $CodeIn = "'1100001/UM','1100002/UM','1100003/UM','1100004/UM','1100005/UM'"; break;
                case "AKMPENYUSUTAN" : $CodeIn = "'1100102/UM','1100103/UM','1100104/UM','1100105/UM'"; break;
                case "PIUTANGLAIN" : $CodeIn = "'1000300/UM'"; break;
                case "INVJANGKAPANJANG" : $CodeIn = "'1000902/UM'"; break;

                // PASIVA
                case "DANADANA" : $CodeIn = "'3000101/UM','3000102/UM','3000103/UM','3000104/UM','3000105/UM'"; break;
                case "KEWAJIBANLANCAR" : $CodeIn = "'3000205/USP','3000299/UM ','3000399/UM'"; break;
                case "TABUNGANANGGOTA" : $CodeIn = "'3000401/USP'"; break;
                case "HUTANGPAJAK" : $CodeIn = "'3000601/UM','3000606/UM'"; break;
                case "EKUITAS" : $CodeIn = "'2000101/UM','2000104/USP','2000105/USP','2000107/UM','2000108/UM'"; break;

                // case "ASETLANCAR" : $grIdDesc = "'ASET LANCAR'"; break;
                // case "ASETTETAP" : $grIdDesc = "'ASET TETAP'"; break;
                // case "ASETLAIN" : $grIdDesc = "'ASET LAIN'"; break;
                
                // case "LIABILITASLANCAR" : $grIdDesc = "'LIABILITAS LANCAR'"; break;
                // case "LIABILITASJANGKAPANJANG" : $grIdDesc = "'LIABILITAS JANGKA PANJANG'"; break;
                // case "EKUITAS" : $grIdDesc = "'EKUITAS'"; break;
            }

            if($CodeIn <> ""){
                $query  = "select 
                                gr.desc group_desc,
                                hr.desc header_desc, 
                                c.code, 
                                c.desc, 
                                gl.ending_balance
                            from ak_coa c
                            inner join ak_general_ledgers gl on gl.coa_id = c.coa_id and gl.periode = ?
                            inner join ak_coa hr on c.header_coa_id = hr.coa_id
                            inner join ak_group_coa gr on gr.group_coa_id = c.group_coa_id
                            where 
                                c.code in (".$CodeIn.")
                            order by c.activity_code";
                
                $response = DB::select($query, array($periode));
            }
        }
        return $response;
    }

    public static function saldoLabaPeriodeSebelumnya($periode, $coaid){
        return collect(DB::select("SELECT ifnull(sum(x.ending_balance),0)saldo from ak_general_ledgers x 
                                WHERE x.periode = date_format(DATE_SUB(concat(RIGHT(?,4),'-',left(?,2),'-01'), INTERVAL 1 MONTH), '%m%Y')
                                        and x.coa_id = (select c.coa_id from ak_coa c where c.code = ?)", array($periode, $periode, $coaid) ))->first()->saldo;
    }

    public static function kalkulasiLabaRugi($periode, $user_id){
        $coaLabarugiBersih = '2000202';
        $reponse = 0;

        $labaBersih = 0;
        foreach(array('UM', 'SP', 'TK') as $divisi){
            $totalPendapatan = 0; $totalBebanLangsung = 0; $totalBebanOps = 0; $totalPendapatanLuarUsaha = 0; $totalBebanLuarUsaha = 0;

            $listPendapatan         = GeneralLedger::getlistLabaRugi($periode, $divisi, "PENDAPATAN");
            $listBebanLangsung      = GeneralLedger::getlistLabaRugi($periode, $divisi, "BEBANLANGSUNG");
            $listBebanOpersaional   = GeneralLedger::getlistLabaRugi($periode, $divisi, "BEBANOPERASIONAL");
            $listPendapatanLuarUsaha   = GeneralLedger::getlistLabaRugi($periode, $divisi, "PENDAPATANLUARUSAHA");
            $listBebanLuarUsaha   = GeneralLedger::getlistLabaRugi($periode, $divisi, "BEBANLUARUSAHA");

            // Pendapatan
            foreach($listPendapatan as $rwpd){
                $totalPendapatan += $rwpd->ending_balance;
            } 
            // End Pendapatan
            
            // Beban Langsung
            foreach($listBebanLangsung as $rwsBl){
                $totalBebanLangsung += $rwsBl->ending_balance;
            }
            // End Beban Lansung

            // Beban Operasional
            foreach($listBebanOpersaional as $rwsOps){
                $totalBebanOps += $rwsOps->ending_balance;
            }
            // End Beban Operasional

            $labaSebelumPajak = $totalPendapatan - $totalBebanLangsung - $totalBebanOps;

            // Pendapatan Luar Usaha
            foreach($listPendapatanLuarUsaha as $rwsLu){
                $totalPendapatanLuarUsaha += $rwsLu->ending_balance;
            }
            // End Pendapatan Luar Usaha

            // BEBAN DILUAR USAHA
            foreach($listBebanLuarUsaha as $rwsDu){
                $totalBebanLuarUsaha += $rwsDu->ending_balance;
            }
            // End BEBAN DILUAR USAHA
            
            $labaBersih = $labaBersih + ($labaSebelumPajak - $totalPendapatanLuarUsaha - $totalBebanLuarUsaha);
        }
        
        // Proses Jurnal
        $saldoAwalLaba = GeneralLedger::saldoLabaPeriodeSebelumnya($periode, $coaLabarugiBersih);
        
        $reponse = GeneralLedger::whereRaw("ak_general_ledgers.coa_id = (select d.coa_id from ak_coa d where d.code = '".$coaLabarugiBersih."') and periode = '".$periode."'")
                            ->update(array('beginning_balance' => $labaBersih, 
                                            'ending_balance' => $saldoAwalLaba, 
                                            'updated_by' => $user_id));


        return $reponse;
    }

}
