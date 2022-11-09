<?php

namespace App\Http\Models\SimpanPinjam;

use Illuminate\Database\Eloquent\Model;
use DB;

class Anggota extends Model
{
    protected $table = 'ospos_people';
    public $timestamps = false;

    protected $primaryKey = 'person_id';

    protected $fillable = [
		'first_name', 'last_name' ,'gender',  'phone_number', 'email', 'address_1', 'city',
        'niak', 'bron_place', 'born_date', 'id_card_number','member_type', 'member_status', 'status'
    ];

    public function customer()
    {
        return $this->hasOne(Customers::class, 'person_id');
    }

    public function resign()
    {
        return $this->hasOne(ResignForms::class, 'person_id');
    }

    public function simpananpokok()
    {
        return $this->hasMany('PrincipalSavings');
    }

    public function simpananinvestasi()
    {
        return $this->hasMany('InvestmentSavings');
    }

    public static function getListAnggota()
    {
        $return = Anggota::get();
        return $return;
    }

    public static function getSaldoByperson($personId){
        
        // Saldo Simpanan
        $response['saldoPokok'] = PrincipalSavings::where('status',2)->where('is_deleted',0)->where('person_id',$personId)->sum('total');
        $response['saldoWajib'] = PeriodicSavings::where('status',2)->where('is_deleted',0)->where('person_id',$personId)->sum('total');
        $response['saldoInvestasi'] = InvestmentSavings::getSaldoKaryawan($personId)->saldo;

        if(strlen($response['saldoInvestasi']) <= 0)
            $response['saldoInvestasi'] = 0;

        // Saldo Pinjaman
        $queryPinjaman = DB::select("select transaction_type_id, sum(c.loan_amount)-sum(ifnull(c.total_bayar_pokok,0))saldo_pinjaman , sum(c.rates_amount)rates_amount
                                                        from (
                                                            select 
                                                                    a.transaction_type_id,
                                                                    case when a.transaction_type_id = 2 then a.loan_amount + a.rates_total
                                                                    else a.loan_amount
                                                                    end as loan_amount,
                                                                    (select 
                                                                            case when a.transaction_type_id = 2 then sum(b.principal_amount) + sum(b.rates_amount)  
                                                                            else 
                                                                                sum(b.principal_amount) 
                                                                            end as principal_amount
                                                                        from sp_loan_installments b 
                                                                        where b.loan_id = a.loan_id and b.status = 2 and is_deleted = 0)total_bayar_pokok ,
                                                                        a.rates_amount
                                                        from sp_loans a 
                                                            where a.person_id = ? and a.status = 1 and a.transfer_date is not null and is_deleted = 0)c
                                                            group by transaction_type_id", array($personId));
        
        $response['pinjaman_usp'] = $response['rates_pinjaman_usp'] = $response['pinjaman_elektronik'] = 0;
        foreach($queryPinjaman as $dt){ 
            if($dt->transaction_type_id == 1){
                $response['pinjaman_usp'] = $dt->saldo_pinjaman; 
                $response['rates_pinjaman_usp'] = ($response['pinjaman_usp'] > 0) ? $dt->rates_amount : 0;
            }
            
            if($dt->transaction_type_id == 2)
                $response['pinjaman_elektronik'] = $dt->saldo_pinjaman;
        }

        return $response;
    }
    
    

}