<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Http\Models\SimpanPinjam\Anggota;
use App\Http\Models\SimpanPinjam\LoanTypes;

use App\Http\Models\Akuntansi\Coa;
use Illuminate\Support\Facades\Crypt;


use DB, Session;

class Select2Controller extends ApiController
{
	
	public function PersonData(Request $req)
	{
		$q = $req->get('q') ?: null;
		$page 		= $req->get("page") ?: 1;
		$perPage 	= 10;
		$offset = ($page - 1) * $perPage;

		$data = Anggota::with('customer')
						->select()
						->addSelect(DB::raw('ifnull((select sum(c.loan_amount)-sum(ifnull(c.total_bayar_pokok,0))saldo_pinjaman 
												from (
													select 
																					a.person_id,
															case when a.transaction_type_id = 2 then a.loan_amount + a.rates_total else a.loan_amount end as loan_amount,
															(select case when a.transaction_type_id = 2 then sum(b.principal_amount) + sum(b.rates_amount)  
																	else sum(b.principal_amount) end as principal_amount
																from sp_loan_installments b where b.loan_id = a.loan_id and b.status = 2 and b.is_deleted = 0)total_bayar_pokok 
													from sp_loans a where a.status =1 and a.transfer_date is not null and a.is_deleted = 0)c where c.person_id = ospos_people.person_id
												),0)saldo_pinjaman'))
						->where('is_deleted',0)
						->where('status',1)
						->where('member_type','<>',0)
						->where(function($p) use ($q){
							$p->where('niak','like','%'.$q.'%')
							->orwhere('first_name','like','%'.$q.'%')
							->orwhere('last_name','like','%'.$q.'%');
						});
		$count = $data->count();
		$endCount = $offset + $perPage;
		$morePages = $count > $endCount;
		
		$returnData = $data->skip($offset)->take($perPage)->get()->toArray();
		

		$r=0;

		// dd($returnData);
		foreach ($returnData as $vl) {
			$returnData[$r]['jenis_anggota_desc'] = sp_member_type()[array_search($vl['member_type'], array_column(sp_member_type(), 'id'))]['name'];
			$returnData[$r]['status_anggota_desc'] = sp_member_status()[array_search($vl['member_status'], array_column(sp_member_status(), 'id'))]['name'];
			$returnData[$r]['personIdencrypt'] = Crypt::encrypt($vl['person_id']);
			$r++;
		}

		$return = [
			"results" => $returnData,
			"pagination" => [
				"more" => $morePages
			]
		];


		return response()->json($return, 200);
	}

	public function PersonDataSaldoNotZero(Request $req)
	{
		$q = $req->get('q') ?: null;
		$page 		= $req->get("page") ?: 1;
		$perPage 	= 10;
		$offset = ($page - 1) * $perPage;

		$data = Anggota::with('customer')
						->select()
						->addSelect(DB::raw('(select sum(a.total) - ifnull((select sum(b.total) from sp_investment_savings b where b.transaction_type <> 1 and b.status <> 0 and b.person_id = a.person_id),0) as saldo
												from sp_investment_savings a
												where a.transaction_type = 1 and a.status <> 0 and a.person_id = ospos_people.person_id
												group by person_id) as saldo_investasi'))
						->addSelect(DB::raw('ifnull((select sum(c.loan_amount)-sum(ifnull(c.total_bayar_pokok,0))saldo_pinjaman 
												from (
													select 
																					a.person_id,
															case when a.transaction_type_id = 2 then a.loan_amount + a.rates_total else a.loan_amount end as loan_amount,
															(select case when a.transaction_type_id = 2 then sum(b.principal_amount) + sum(b.rates_amount)  
																	else sum(b.principal_amount) end as principal_amount
																from sp_loan_installments b where b.loan_id = a.loan_id and b.status = 2 and b.is_deleted = 0)total_bayar_pokok 
													from sp_loans a where a.status =1 and a.transfer_date is not null and a.is_deleted = 0)c where c.person_id = ospos_people.person_id
												),0)saldo_pinjaman'))
						->where('is_deleted',0)
						->where('status',1)
						->where('member_type','<>',0)
						->whereRaw('(select sum(a.total) - ifnull((select sum(b.total) from sp_investment_savings b where b.transaction_type <> 1 and b.status <> 0 and b.person_id = a.person_id),0) as saldo
						from sp_investment_savings a
						where a.transaction_type = 1 and a.status <> 0 and a.person_id = ospos_people.person_id
						group by person_id) > 0')
						->where(function($p) use ($q){
							$p->where('niak','like','%'.$q.'%')
							->orwhere('first_name','like','%'.$q.'%')
							->orwhere('last_name','like','%'.$q.'%');
						});
		$count = $data->count();
		$endCount = $offset + $perPage;
		$morePages = $count > $endCount;
		
		$returnData = $data->skip($offset)->take($perPage)->get()->toArray();
		

		$r=0;

		// dd($returnData);
		foreach ($returnData as $vl) {
			$returnData[$r]['jenis_anggota_desc'] = sp_member_type()[array_search($vl['member_type'], array_column(sp_member_type(), 'id'))]['name'];
			$returnData[$r]['status_anggota_desc'] = sp_member_status()[array_search($vl['member_status'], array_column(sp_member_status(), 'id'))]['name'];
			$returnData[$r]['personIdencrypt'] = Crypt::encrypt($vl['person_id']);
			$r++;
		}

		$return = [
			"results" => $returnData,
			"pagination" => [
				"more" => $morePages
			]
		];


		return response()->json($return, 200);
	}

	public function listMasterPO(Request $request)
	{
		$q = $request->get('q') ?: null;
		$jenis_pekerjaan = $request->get('jenis_pekerjaan') ?: 1;

		if($jenis_pekerjaan ==1){
			//Stockcode
			$data = DB::table('stockcodes')->selectRaw(
				"stockcode_id as id,
				CONCAT(stockcode,'-',nama_stockcode) as text");
				$data->where('status',1);
			if($q){
				$data->where('stockcode', 'LIKE', '%'.$q.'%')->orWhere('nama_stockcode', 'LIKE', '%'.$q.'%');
			}
		}else{
			//PR
			$data = DB::table('prs')->selectRaw(
				"pr_id as id,
				CONCAT(pr,'-',nama_pr) as text");
				$data->where('status',1);
			if($q){
				$data->where('pr', 'LIKE', '%'.$q.'%')->orWhere('nama_pr', 'LIKE', '%'.$q.'%');
			}
		}


		$page = $request->get("page") ?: 1;
		$perPage = 100;
		$offset = ($page - 1) * $perPage;

		$count = $data->count();
		$endCount = $offset + $perPage;
		$morePages = $count > $endCount;

		$return = [
			"results" => $data->skip($offset)->take($perPage)->get()->toArray(),
			"pagination" => [
				"more" => $morePages
			]
		];


		return response()->json($return, 200);
	}

	public function listJenisBunga(Request $req)
	{
		$q = $req->get('q') ?: null;
		$page 		= $req->get("page") ?: 1;
		$perPage 	= 10;
		$offset = ($page - 1) * $perPage;

		$data = LoanTypes::getListLoanType();

		$count = $data->count();
		$endCount = $offset + $perPage;
		$morePages = $count > $endCount;
		
		$returnData = $data->skip($offset)->take($perPage)->get()->toArray();
		

		$r=0;
		foreach ($returnData as $vl) {
			$returnData[$r]['Idencrypt'] = Crypt::encrypt($vl['loan_type_id']);

			
			$returnData[$r]['interest_type'] = sp_array_mdrray_search(sp_interest_type(), 'id', 'name', $vl['interest_type']);
			$r++;
		}

		$return = [
			"results" => $returnData,
			"pagination" => [
				"more" => $morePages
			]
		];


		return response()->json($return, 200);
	}


	public function listCoaAktif(Request $req){
		$q 			= $req->get('q') ?: null;
		$page 		= $req->get("page") ?: 1;
		$perPage 	= 10;
		$offset 	= ($page - 1) * $perPage;

		$pramBy[]		= array('laravelRaw'=>true, "content"=>"a.group_detail = 2");
		$data = Coa::getListCoa($pramBy, $q);

		$count = $data->count();
		$endCount = $offset + $perPage;
		$morePages = $count > $endCount;
		
		$returnData = $data->skip($offset)->take($perPage)->get()->toArray();
		

		$r='';
		$returnx = [];
		$headertext = ''; 
		foreach ($returnData as $vl) {
			if($headertext <> $vl->header_desc){
				$r = ($r == '') ? 0 : $r+1;
				$returnx[$r]['text'] = $vl->header_desc;
				$returnx[$r]['children'][] = $vl;
			}
			else{
				$returnx[$r]['children'][] = $vl;
			}


			$headertext = $vl->header_desc;
			// $returnx[] = array('text' => $vl->header_desc);
		// 	$returnData[$r]['personIdencrypt'] = Crypt::encrypt($vl['person_id']);
		// 	$r++;
		}

		// echo var_dump($r); exit;

		$return = [
			"results" => $returnx,
			"pagination" => [
				"more" => $morePages
			]
		];


		return response()->json($return, 200);
	}

	public function listCoaAsset(Request $req){
		$q 			= $req->get('q') ?: null;
		$page 		= $req->get("page") ?: 1;
		$perPage 	= 10;
		$offset 	= ($page - 1) * $perPage;

		

		$pramBy[]		= array('laravelRaw'=>true, "content"=>"a.group_detail = 2");

		if(isset($req->asetType)){
			switch($req->asetType){
				// Aset Perolehan
				case 1:
					$pramBy[]		= array('laravelRaw'=>false, "key"=>"a.header_coa_id", "value"=>10);	
				break;

				// Beban Penyusutan
				case 2:
					$pramBy[]		= array('laravelRaw'=>true, "content" => "a.code like 'UM-H%'");	
				break;

				// Akumulasi Penyusutan
				case 3:
					$pramBy[]		= array('laravelRaw'=>false, "key"=>"a.header_coa_id", "value"=>11);	
				break;
			}
		}


		$data = Coa::getListCoa($pramBy, $q);

		$count = $data->count();
		$endCount = $offset + $perPage;
		$morePages = $count > $endCount;
		
		$returnData = $data->skip($offset)->take($perPage)->get()->toArray();
		

		$r='';
		$returnx = [];
		$headertext = ''; 
		foreach ($returnData as $vl) {
			if($headertext <> $vl->header_desc){
				$r = ($r == '') ? 0 : $r+1;
				$returnx[$r]['text'] = $vl->header_desc;
				$returnx[$r]['children'][] = $vl;
			}
			else{
				$returnx[$r]['children'][] = $vl;
			}


			$headertext = $vl->header_desc;
		}

		$return = [
			"results" => $returnx,
			"pagination" => [
				"more" => $morePages
			]
		];

		return response()->json($return, 200);
	}

	public function testToken(Request $req){
		$sessionToken = Session::token();
		$token = $req->header('X-CSRF-TOKEN');

		if (! is_string($sessionToken) || ! is_string($token)) {
			return false;
		}

		$responseValid = hash_equals($sessionToken, $token);
		
		$req->session()->regenerateToken(); // regenerate token
		$new_csrf = csrf_token();
		$req->session()->put('_token', $new_csrf);

		// "sessionToken" => $sessionToken, "token"=>$token,
		$return = [ "csrf" => $new_csrf];
		
		if($responseValid){
			$q = $req->get('q') ?: null;
			$page 		= $req->get("page") ?: 1;
			$perPage 	= 10;
			$offset = ($page - 1) * $perPage;

			$data = Anggota::with('customer')
							->where('is_deleted',0)
							->where('status',1)
							->where('member_type','<>',0)
							->whereRaw("ospos_people.person_id not in (select sp_resign_forms.person_id from sp_resign_forms where sp_resign_forms.is_deleted = 0)")
							->where(function($p) use ($q){
								$p->where('niak','like','%'.$q.'%')
								->orwhere('first_name','like','%'.$q.'%')
								->orwhere('last_name','like','%'.$q.'%');
							});

			$count = $data->count();
			$endCount = $offset + $perPage;
			$morePages = $count > $endCount;
			
			$returnData = $data->skip($offset)->take($perPage)->get()->toArray();
			
			$r=0;
			foreach ($returnData as $vl) {


				$returnData[$r]['jenis_anggota_desc'] = sp_member_type()[array_search($vl['member_type'], array_column(sp_member_type(), 'id'))]['name'];
				$returnData[$r]['status_anggota_desc'] = sp_member_status()[array_search($vl['member_status'], array_column(sp_member_status(), 'id'))]['name'];
				$returnData[$r]['person_id'] = Crypt::encrypt($vl['person_id']);
				$r++;
			}

			$return = [
				"csrf" => $new_csrf,
				"results" => $returnData,
				"pagination" => [
					"more" => $morePages
				]
			];
		}
		


		return response()->json($return, 200);


	}
}
