<?php

if (! function_exists('terbilang'))
{
	function terbilang($nilai) {
		$huruf = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
        if($nilai==0){
            return "";
        }elseif ($nilai < 12&$nilai!=0) {
            return "" . $huruf[$nilai];
        } elseif ($nilai < 20) {
            return Terbilang($nilai - 10) . " Belas ";
        } elseif ($nilai < 100) {
            return Terbilang($nilai / 10) . " Puluh " . Terbilang($nilai % 10);
        } elseif ($nilai < 200) {
            return " Seratus " . Terbilang($nilai - 100);
        } elseif ($nilai < 1000) {
            return Terbilang($nilai / 100) . " Ratus " . Terbilang($nilai % 100);
        } elseif ($nilai < 2000) {
            return " Seribu " . Terbilang($nilai - 1000);
        } elseif ($nilai < 1000000) {
            return Terbilang($nilai / 1000) . " Ribu " . Terbilang($nilai % 1000);
        } elseif ($nilai < 1000000000) {
            return Terbilang($nilai / 1000000) . " Juta " . Terbilang($nilai % 1000000);
        }elseif ($nilai < 1000000000000) {
            return Terbilang($nilai / 1000000000) . " Milyar " . Terbilang($nilai % 1000000000);
        }elseif ($nilai < 100000000000000) {
            return Terbilang($nilai / 1000000000000) . " Trilyun " . Terbilang($nilai % 1000000000000);
        }elseif ($nilai <= 100000000000000) {
            return "Maaf Tidak Dapat di Prose Karena Jumlah nilai Terlalu Besar ";
        }
	}
}


if (! function_exists('statusPersekot'))
{
	function statusPersekot($val)
	{
		if($val == 1){
			return 'Belum Diverifikasi';
		}elseif($val == 2){
			return 'Disetujui';
		}elseif($val == 3){
			return 'Realisasi';
		}elseif($val == 4){
			return 'PNPO';
		}elseif($val == 5){
			return 'Lunas';
		}else{
			return 'Ditolak';
		}
	}
}


if (! function_exists('jenisWO'))
{
	function jenisWO($val)
	{
		if($val == 1){
			return 'Purchase Order(PO)';
		}else{
			return 'Persekot PO';
		}
	}
}



if (! function_exists('statusPembayaran'))
{
	function statusPembayaran($val)
	{
		if($val == 1){
			return 'Sudah Dibayar';
		}else{
			return 'Belum Dibayar';
		}
	}
}


if (! function_exists('getMetodePembayaran'))
{
	function getMetodePembayaran()
	{
		return [
			'1' => 'Transfer',
			'2' => 'Tunai'
		];
	}
}

if (! function_exists('statusMetodePembayaran'))
{
	function statusMetodePembayaran($val)
	{
		if($val == 1){
			return 'Transfer';
		}else{
			return 'Tunai';
		}
	}
}

if (! function_exists('getPR'))
{
	function getPR()
	{
		return [
			'1' => 'PR',
			'0' => 'Non PR'
		];
	}
}

if (! function_exists('getStockcode'))
{
	function getStockcode()
	{
		return [
			'1' => 'Stockcode',
			'0' => 'Non Stockcode'
		];
	}
}


if (! function_exists('statusPR'))
{
	function statusPR($val)
	{
		if($val == 1){
			return 'PR';
		}else{
			return 'Non PR';
		}
	}
}

if (! function_exists('statusStockcode'))
{
	function statusStockcode($val)
	{
		if($val == 1){
			return 'Stockcode';
		}else{
			return 'Non Stockcode';
		}
	}
}


if (! function_exists('jenisPekerjaan'))
{
	function jenisPekerjaan($val)
	{
		if($val == 1){
			return 'Material';
		}elseif ($val == 2) {
			return 'Jasa';
		}elseif ($val == 3) {
			return 'Material Jasa';
		}else{
			return 'Tidak Ditemukan';
		}
	}
}

if (! function_exists('labelJenisPekerjaan'))
{
	function labelJenisPekerjaan($val)
	{
		if($val == 1){
			return 'Stockcode';
		}elseif ($val == 2) {
			return 'Purchashing Requisition';
		}else{
			return 'Tidak Ditemukan';
		}
	}
}

if (! function_exists('getLevel'))
{
	function getLevel($val)
	{
		if($val == 1){
			return 'SUPERADMIN';
		}elseif ($val == 2) {
			return 'ADMIN';
		}elseif ($val == 3) {
			return 'KABUPATEN/KOTA';
		}elseif ($val == 4) {
			return 'OPERATOR SKPD';
		}else{
			return 'ADMIN SKPD';
		}
	}
}


if (! function_exists('getDays'))
{
	function getDays()
	{
		return [
			'Mon' => 'Senin',
			'Tue' => 'Selasa',
			'Wed' => 'Rabu',
			'Thu' => 'Kamis',
			'Fri' => 'Jumat',
			'Sat' => 'Sabtu',
			'Sun' => 'Minggu'
		];
	}
}

if (! function_exists('getMonths'))
{
	function getMonths()
	{
		return [
			1	=> "Januari",
			2	=> "Februari",
			3	=> "Maret",
			4	=> "April",
			5	=> "Mei",
			6	=> "Juni",
			7	=> "Juli",
			8	=> "Agustus",
			9	=> "September",
			10	=> "Oktober",
			11	=> "November",
			12	=> "Desember"
		];
	}
}

if (! function_exists('toRp'))
{
	function toRp($parm)
	{
		return 'Rp. ' . number_format( floatval($parm), 0 , '' , '.' ) . ',00';
		// return '' . number_format( floatval($parm), 0 , '' , '.' ) . ',00';
	}
}

if (! function_exists('toDecimal'))
{
	function toDecimal($parm)
	{
		return number_format( floatval($parm), 0 , '' , '.' );
	}
}

if (! function_exists('formatRpComma'))
{
	function formatRpComma($parm)
	{
		return "Rp. ".number_format($parm,2,",",".");
	}
}

if (! function_exists('replaceRp'))
{
	function replaceRp($parm)
	{
		if($parm == ""){
			return 0;
		}
		return str_replace(["Rp. ", ".", "_", ",00"], "", $parm);
	}
}

if (! function_exists('formatDatePhp'))
{
	function formatDatePhp($parm)
	{
		return date('Y-m-d', strtotime($parm));
	}
}

if (! function_exists('formatDateView'))
{
	function formatDateView($parm)
	{
		return date("d-m-Y", strtotime($parm));
	}
}


if (! function_exists('doubleToInt'))
{
	function doubleToInt($parm)
	{
		return number_format($parm, 0, '', '');
	}
}


if (! function_exists('formatNoRpComma'))
{
	function formatNoRpComma($parm, $coma = 2)
	{
		return number_format($parm,$coma,",",".");
	}
}


if (! function_exists('formatNoZeroRpComma'))
{
	function formatNoZeroRpComma($parm)
	{
		return $parm+0;
	}
}

if (! function_exists('strPadKode'))
{
	function strPadKode($parm)
	{
		return str_pad($parm,  2, "0", STR_PAD_LEFT);
	}
}

if (! function_exists('formatVolume'))
{
	function formatVolume($parm)
	{
		return round($parm,2);
	}
}

if (! function_exists('formatAnggaran'))
{
	function formatAnggaran($parm)
	{
		return round($parm,2);
	}
}


if (! function_exists('tglIndo'))
{
	function tglIndo($parm)
	{
		$array_bulan = array(1=>"Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
		$dataBulan = date('n',strtotime($parm));
		return date('d',strtotime($parm))." ".$array_bulan[$dataBulan]." ".date('Y',strtotime($parm));
	}
}

if (! function_exists('hariIndo'))
{
	function hariIndo($parm)
	{
		$day = date('D', strtotime($parm));
		$dayList = array(
			'Sun' => 'Minggu',
			'Mon' => 'Senin',
			'Tue' => 'Selasa',
			'Wed' => 'Rabu',
			'Thu' => 'Kamis',
			'Fri' => 'Jumat',
			'Sat' => 'Sabtu'
		);
		return $dayList[$day];
	}
}

if (! function_exists('getInfoBulanRomawi'))
{
	function getInfoBulanRomawi($parm)
	{
		$month = date('m',strtotime($parm));
		$monthList = array(
			'01' => 'I',
			'02' => 'II',
			'03' => 'III',
			'04' => 'IV',
			'05' => 'V',
			'06' => 'VI',
			'07' => 'VII',
			'08' => 'VIII',
			'09' => 'IX',
			'10' => 'X',
			'11' => 'XI',
			'12' => 'XII',
		);
		return $monthList[$month];
	}
}


if (! function_exists('getInfoBulanSingkat'))
{
	function getInfoBulanSingkat($parm)
	{

		$month = date('m', strtotime($parm));
		$monthList = array(
			'01' => 'JAN',
			'02' => 'FEB',
			'03' => 'MAR',
			'04' => 'APR',
			'05' => 'MEI',
			'06' => 'JUN',
			'07' => 'JUL',
			'08' => 'AGT',
			'09' => 'SEP',
			'10' => 'OKT',
			'11' => 'NOV',
			'12' => 'DES',
		);
		return $monthList[$month];
	}
}

if (! function_exists('getInfoBulan'))
{
	function getInfoBulan($parm)
	{

		$month = date('m', strtotime($parm));
		$monthList = array(
			'01' => 'Januari',
			'02' => 'Februari',
			'03' => 'Maret',
			'04' => 'April',
			'05' => 'Mei',
			'06' => 'Juni',
			'07' => 'Juli',
			'08' => 'Agustus',
			'09' => 'September',
			'10' => 'Oktober',
			'11' => 'November',
			'12' => 'Desember',
		);
		return $monthList[$month];
	}
}

if (! function_exists('tglIndoAngka'))
{
	function tglIndoAngka($parm)
	{
		return date('d/m/Y',strtotime($parm));
	}
}

if (! function_exists('waktuIndo'))
{
	function waktuIndo($parm)
	{
		return date('H:i', strtotime($parm));
	}
}

if (! function_exists('tglWaktuIndo'))
{
	function tglWaktuIndo($parm)
	{
		if($parm == '0000-00-00 00:00:00'){
			return "-";
		}
		$array_bulan = array(1=>"Januari","Februari","Maret", "April", "Mei", "Juni","Juli","Agustus","September","Oktober", "November","Desember");
		$dataBulan = date('n',strtotime($parm));
		$dataWaktu = date('H:i',strtotime($parm));
		return date('d',strtotime($parm))." ".$array_bulan[$dataBulan]." ".date('Y',strtotime($parm))." ".$dataWaktu;
	}
}

if (! function_exists('hariTglWaktuIndo'))
{
	function hariTglWaktuIndo($parm)
	{
		if($parm == '0000-00-00 00:00:00'){
			return "-";
		}
		$array_bulan = array(1=>"Januari","Februari","Maret", "April", "Mei", "Juni","Juli","Agustus","September","Oktober", "November","Desember");
		$dataBulan = date('n',strtotime($parm));

		$array_hari = array(1=>"Senin","Selasa","Rabu","Kamis","Jumat", "Sabtu","Minggu");
		$dataHari = date('N',strtotime($parm));

		$dataWaktu = date('H:i',strtotime($parm));

		return $array_hari[$dataHari].", ".date('d',strtotime($parm))." ".$array_bulan[$dataBulan]." ".date('Y',strtotime($parm))." ".$dataWaktu;
	}
}

if (! function_exists('timeInterval'))
{
	function timeInterval($start, $end)
	{
		$date1=date_create($start);
		$date2=date_create($end);
		$diff=date_diff($date1,$date2);
		$jam=$diff->format('%h')+($diff->format('%a')*24);
		$menit=$diff->format('%i')+($diff->format('%a')*24);
		$detik=$diff->format('%s')+($diff->format('%a')*24);
		$time=['jam'=>$jam,'menit'=>$menit,'detik'=>$detik];
		return $time;
	}
}

if (! function_exists('timeInterval24'))
{
	function timeInterval24($start, $end)
	{
		$date1=date_create($start);
		$date2=date_create($end);
		$diff=date_diff($date1,$date2);
		$jam=$diff->format('%h')+($diff->format('%a')*24);
		$menit=$diff->format('%i')+($diff->format('%a')*24);
		$detik=$diff->format('%s')+($diff->format('%a')*24);
		$time=Date('H:i:s',strtotime($jam.':'.$menit.':'.$detik));
		return $time;
	}
}

if (! function_exists('dateDiff'))
{
	function dateDiff($parm)
	{
		$datetime1 = new DateTime();
		$datetime2 = new DateTime($parm);
		$interval = $datetime1->diff($datetime2);

		$year = $interval->format('%y');
		$month = $interval->format('%m');
		$day = $interval->format('%a');
		$hour = $interval->format('%h');
		$min = $interval->format('%i');

		$words = "";
		if($year > 0){
			$words .= $year;
			if($year == 1){
				$words .= " year ";
			}else{
				$words .= " years ";
			}
		}
		if($month > 0){
			$words .= $month;
			if($month == 1){
				$words .= " month ";
			}else{
				$words .= " months ";
			}
		}
		if($day > 0){
			$words .= $day;
			if($day == 1){
				$words .= " day ";
			}else{
				$words .= " days ";
			}
		}
		if($hour > 0){
			$words .= $hour;
			if($hour == 1){
				$words .= " hour ";
			}else{
				$words .= " hours ";
			}
		}
		if($min > 0){
			$words .= $min;
			if($min == 1){
				$words .= " min ";
			}else{
				$words .= " mins ";
			}
		}

		$con = $datetime1 > $datetime2 ? " ago" : " later";

		return $words.$con;
	}
}

if (! function_exists('collect_count'))
{
	function collect_count($array, $count)
	{
		$return = [];
		for ($i=0; $i <= $count-1 ; $i++)
		{
			if(count($array) >= $count)
				array_push($return, $array[$i]);
		}
		return collect($return);
	}
}

if (! function_exists('same_collection'))
{
	function same_collection($collection1, $collection2)
	{
		if(count($collection1->diffAssoc($collection2)) == 0)
			return true;
		return false;
	}
}


if (! function_exists('sp_payment_method_list'))
{
	/**
	 * array return
	 */
	function sp_payment_method_list()
	{
		
		// return array("1"=>"CASH",2=>"BANK");
		return App\Http\Models\Akuntansi\AkTransactionTypes::where('show_status', 1)->pluck('show_name','transaction_type_id');
	}

}


if (! function_exists('sp_payment_method'))
{
	/**
	 * string returntype (id, name)
	 * string param
	 */
	function sp_payment_method_name($returnTYpe, $param)
	{
		$model = App\Http\Models\Akuntansi\AkTransactionTypes::where('show_status', 1);
		if($returnTYpe == 'id'){
			$model->where('show_name', $param);
			$data	= $model->first();
			$return = $data->transaction_type_id;
			
		}
		elseif($returnTYpe == 'name'){
			$model->where('transaction_type_id', $param);
			$data	= $model->first();
			$return = $data->show_name;
		}
		return $return;
	}
}


if (! function_exists('sp_array_mdrray_search'))
{
	/**
	 * array return
	 */
	function sp_array_mdrray_search($array, $keyname, $valname,$val)
	{
		$return = null;
		$arr = $array;
		foreach($arr as $key => $value){
			if($value[$keyname] == $val)
				$return = $value[$valname];
		}

		return $return;
	}
}


if (! function_exists('sp_investmenttype_list'))
{
	/**
	 * array return
	 */
	function sp_investmenttype_list()
	{
		return array("1"=>"Penyetoran",2=>"Penarikan", 3=>"Bunga Investasi");
	}

}

if (! function_exists('sp_interest_type'))
{
	/**
	 * array return
	 */
	function sp_interest_type()
	{
		return [
			array('id' => 1, 'name' => 'BUNGA / POKOK PINJAMAN'),
			array('id' => 2, 'name' => 'BUNGA / POKOK ANGSURAN'),
			array('id' => 3, 'name' => 'BUNGA / SISA POKOK'),
			array('id' => 4, 'name' => 'BUNGA / SISA POKOK / TAHUN'),
		];
	}

}


if (! function_exists('sp_member_type_nonresign'))
{
	/**
	 * array return
	 */
	function sp_member_type_nonresign()
	{
		return [
			array('id' => 1, 'name' => 'Aktif'),
			array('id' => 2, 'name' => 'Pasif'),
		];
	}

}

if (! function_exists('sp_member_type'))
{
	/**
	 * array return
	 */
	function sp_member_type()
	{
		return [
			array('id' => 0, 'name' => 'Mengundurkan Diri'),
			array('id' => 1, 'name' => 'Aktif'),
			array('id' => 2, 'name' => 'Pasif'),
		];
	}

}

if (! function_exists('sp_member_status'))
{
	/**
	 * array return
	 */
	function sp_member_status()
	{
		return [
			array('id' => 1, 'name' => 'Karyawan'),
			array('id' => 2, 'name' => 'Pensiunan'),
			array('id' => 3, 'name' => 'SCM'),
			array('id' => 4, 'name' => 'Non-Karyawan UBRS'),
		];
	}

}

if (! function_exists('get_levelusers_byid'))
{
	/**
	 * array return
	 */
	function get_levelusers_byid($levelid)
	{
		return App\Http\Models\User::getLevelUsers($levelid);
	}

}


if (! function_exists('get_next_approval'))
{
	/**
	 * array return
	 */
	function get_next_approval($menu,$levelid = null)
	{
		$return = $levelid;
		switch($menu){
			case 'pengajuan_investasi': 
				if($levelid == 9)
					$return = 8;
				elseif($levelid == 8)
					$return = 7;
				else	
					$return = 9;
			break;

			case 'pengajuan_pinjaman': 
				if($levelid == 9)
					$return = 7;
				else	
					$return = 9;
			break;
		}
		return $return;
		// return App\Http\Models\User::getLevelUsers($levelid);
	}

}



if (! function_exists('validateDate'))
{
	/**
	 * array return
	 */
	function validateDate($date, $format = 'Y-m-d H:i:s')
	{
		$d = DateTime::createFromFormat($format, $date);
    	return $d && $d->format($format) == $date;
	}

}


if (! function_exists('tanggalCutoffHutangToko'))
{
	/**
	 * array return
	 */
	function tanggalCutoffHutangToko()
	{
		return 16;
	}

}

