<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/logout',function () {
    Auth::logout();
    return redirect('login');
});
Auth::routes();

Route::get('refreshCaptcha', 'Auth\LoginController@refreshCaptcha');
Route::post('setLayout', 'Auth\LoginController@setLayout');

Route::group(['prefix' => '/', 'namespace' => 'App', 'middleware' => 'auth'], function()
{
	# LANDING PAGE
	Route::get('/', function(){
		return redirect('home');
	});

  	Route::get('home', 'HomeController@usaha');
	Route::get('getInfoNotifUsaha', 'HomeController@getInfoNotifUsaha');
	Route::get('getDownload', 'HomeController@getDownload');
  	Route::get('home/profile', 'HomeController@profile');
	Route::post('home/change-profile', 'Pengaturan\UserController@change_profile');
	Route::post('home/change-password', 'Pengaturan\UserController@change_password');
	Route::get('lock', 'HomeController@lock');
	Route::post('unlock', 'HomeController@unlock');
	# END LANDING PAGE

	Route::get('prototype-usaha', 'PrototypeUsahaController@index');
	Route::get('prototype-sp', 'PrototypeSimpanPinjamController@index');


	Route::get('usaha/home', 'HomeController@usaha');
	Route::get('simpanpinjam/home', 'HomeController@simpanpinjam');
	Route::get('akuntansi/home', 'HomeController@akuntansi');

	Route::get('tester/coba', 'HomeController@tester');

	# USAHA UMUM
	Route::group(['prefix' => 'usaha', 'namespace' => 'UsahaUmum'], function()
	{
		# MASTER
		Route::group(['prefix' => 'master', 'namespace' => 'Master'], function()
		{
      		Route::resource('setting', 'SettingController');
			Route::resource('client', 'ClientController');
			Route::resource('lokasi', 'LokasiController');
			Route::resource('pr', 'PrController');
			Route::resource('spv', 'SpvController');
			Route::resource('stockcode', 'StockcodeController');
      		Route::resource('supplier', 'SupplierController');

		});
		# END MASTER

		# PERSEKOT
		Route::get('persekot/add', 'PersekotController@add');
		Route::get('persekot/verifikasi', 'PersekotController@listVerifikasi');
		Route::get('persekot/verifikasi-detail/{id}', 'PersekotController@showVerifikasi');
		Route::post('persekot/verifikasi-persekot', 'PersekotController@prosesVerifikasi');
		Route::get('persekot/proses/{id}', 'PersekotController@showProses');
		Route::post('persekot/proses-persekot', 'PersekotController@prosesNextStep');
		Route::get('persekot/pembayaran', 'PersekotController@pembayaran');
		Route::get('persekot/proses-pembayaran/{id}', 'PersekotController@getProsesPembayaran');
		Route::post('persekot/proses-pembayaran', 'PersekotController@postProsesPembayaran');
		Route::get('persekot/report', 'PersekotController@report');
		Route::get('persekot/list-pnpo', 'PersekotController@listPnpo');
		Route::get('persekot/pindah-persekotpo/{id}', 'PersekotController@pindahPersekotPo');
		Route::post('persekot/pindah-persekotpo', 'PersekotController@updatePersekotPO');
		Route::resource('persekot', 'PersekotController');
		# END PERSEKOT

		# PERSEKOT PO
		Route::get('persekotpo/add', 'PersekotPOController@add');
		Route::get('persekotpo/verifikasi', 'PersekotPOController@listVerifikasi');
		Route::get('persekotpo/verifikasi-detail/{id}', 'PersekotPOController@showVerifikasi');
		Route::post('persekotpo/verifikasi-persekot', 'PersekotPOController@prosesVerifikasi');
		Route::get('persekotpo/proses/{id}', 'PersekotPOController@showProses');
		Route::post('persekotpo/proses-persekot', 'PersekotPOController@prosesNextStep');
		Route::get('persekotpo/pembayaran', 'PersekotPOController@pembayaran');
		Route::get('persekotpo/proses-pembayaran/{id}', 'PersekotPOController@getProsesPembayaran');
		Route::post('persekotpo/proses-pembayaran', 'PersekotPOController@postProsesPembayaran');
		Route::get('persekotpo/report', 'PersekotPOController@report');
		Route::get('persekotpo/list-pnpo', 'PersekotPOController@listPnpo');
		Route::resource('persekotpo', 'PersekotPOController');
		# END PERSEKOT
		
		# PURCHASE ORDER
		Route::get('po/report-pembayaran', 'PurchaseOrderController@reportPembayaran');
		Route::get('po/report', 'PurchaseOrderController@report');
		Route::get('po/pembayaran', 'PurchaseOrderController@pembayaran');
		Route::post('po/proses-pembayaran', 'PurchaseOrderController@pembayaranProses');
		Route::get('po/persekot-po', 'PurchaseOrderController@persekotPO');
		Route::get('po/penerimaan/{id}', 'PurchaseOrderController@penerimaan');
		Route::resource('po', 'PurchaseOrderController');
		# END PURCHASE ORDER

		# WORK ORDER
		Route::get('wo/report-pembayaran', 'WorkOrderController@reportPembayaran');
		Route::get('wo/report', 'WorkOrderController@report');
		Route::get('wo/pembayaran', 'WorkOrderController@pembayaran');
		Route::post('wo/proses-pembayaran', 'WorkOrderController@pembayaranProses');
		Route::get('wo/pengiriman/{id}', 'WorkOrderController@pengiriman');
		Route::resource('wo', 'WorkOrderController');
		# END WORK ORDER

		# ASET
		Route::get('aset/list', 'AsetController@list');
		Route::resource('aset', 'AsetController');
		# END ASET

    	# PEMBELIAN LANGSUNG
		Route::resource('purchase', 'PurchaseController');
		# END PEMBELIAN LANGSUNG

		# PDF
		Route::get('pdf/persekot', 'PdfController@persekot');
		Route::get('pdf/persekotPO', 'PdfController@persekotPO');
		Route::get('pdf/wo', 'PdfController@wo');
		Route::get('pdf/po', 'PdfController@po');
		Route::get('pdf/purchase', 'PdfController@purchase');
		# END PDF

		Route::resource('setting', 'SettingController');

	});
	# END USAHA UMUM

	# SIMPAN PINJAM
	Route::group(['prefix' => 'simpanpinjam', 'namespace' => 'SimpanPinjam'], function()
	{

		Route::group(['prefix' => 'master', 'namespace' => 'Master'], function()
		{
			Route::get('konfig/jenispinjaman', 'ConfigloanController@indexloantype');
			Route::get('konfig/listjenispinjaman', 'ConfigloanController@list_loantype_json');
		});

		# ANGGOTA
		
		Route::get('anggota/pengundurandiri', 'AnggotaController@listResignForm');
		
		Route::get('anggota/dataanggota_json', 'AnggotaController@getSaldoAnggota');
		Route::get('daftarresignanggota_json', 'AnggotaController@list_resign_json');
		Route::get('daftaranggota_json', 'AnggotaController@list_json');
		Route::get('anggota/pengajuanpengudurandiri', 'AnggotaController@form_pengundurandiri');

		Route::get('anggota/approvalresignsatu/{id}', 'AnggotaController@approveResign');
		Route::get('anggota/approvalresigndua/{id}', 'AnggotaController@approveResign');

		Route::get('anggota/showpengundurandiri/{id}', 'AnggotaController@showResign');
		Route::get('anggota/pelunasansaldo/{id}', 'AnggotaController@setPelunasan');
		
		Route::post('anggota/prosespembayaranPelunasan/{id}', 'AnggotaController@prosessPelunasan');
		Route::post('anggota/submitpengundurandirianggota', 'AnggotaController@saveResignForm');
		Route::resource('anggota', 'AnggotaController');
		# END ANGGOTA
		
		# SIMPANAN POKOK
		Route::post('simpananpokok/apprterima/{id}', 'SimpananPokokController@receiving_appr');
		Route::get('simpananpokok/prosesterima/{id}', 'SimpananPokokController@receiving_process');
		Route::get('simpananpokok/pembatalan/{id}', 'SimpananPokokController@batalSimpok');
		Route::get('daftarsimpananpokok_json', 'SimpananPokokController@list_json');
		Route::resource('simpananpokok', 'SimpananPokokController');
		# END SIMPANAN POKOK


		# KONFIGURASI
		Route::get('getKonfigurasiSimpanan','KonfigurasiSimpananController@getKonfigurasiSimpanan');
		Route::get('getKonfigurasiPinjaman','KonfigurasiPinjamanController@getKonfigurasiPinjaman');
		Route::post('simpanKonfigurasiSimpanan','KonfigurasiSimpananController@store');
		Route::post('simpanKonfigurasiPinjaman','KonfigurasiPinjamanController@store');


		Route::resource('konfigurasiSimpanan','KonfigurasiSimpananController');
		# END KONFIGURASI

		# SIMPANAN INVESTASI

		Route::post('investasi/uploadTransfer', 'SimpananInvestasiController@uploadTransfer');
		Route::get('investasi/exportExcelPosting', 'SimpananInvestasiController@exportExcelPosting');
		Route::get('investasi/postingDataBayar', 'SimpananInvestasiController@PostingSelectedPerPeriode');
		Route::get('investasi/kalkulasiperbulan', 'SimpananInvestasiController@kalkulasiBungaInvestasi');
		Route::get('investasi/bungainvestasi', 'SimpananInvestasiController@HistoryBungaInvestasi');

		Route::get('investasi/approvalpengajuantiga', 'SimpananInvestasiController@ApproveDetailTransaksi');
		Route::get('investasi/approvalpengajuandua', 'SimpananInvestasiController@ApproveDetailTransaksi');
		Route::get('investasi/approvalpengajuansatu', 'SimpananInvestasiController@ApproveDetailTransaksi');

		Route::post('investasi/apprterima/{id}', 'SimpananInvestasiController@receiving_appr');
		Route::get('investasi/prosesterima/{id}', 'SimpananInvestasiController@receiving_process');

		Route::post('investasi/apprserah/{id}', 'SimpananInvestasiController@transfer_appr');
		Route::get('investasi/prosesserah/{id}', 'SimpananInvestasiController@transfer_process');


		Route::get('investasi/penarikan', 'SimpananInvestasiController@release');
		Route::post('investasi/penarikanstore', 'SimpananInvestasiController@release');
		Route::get('daftarsimpananinvestasi_json', 'SimpananInvestasiController@list_json');
		Route::resource('investasi', 'SimpananInvestasiController');
		# END SIMPANAN INVESTASI

		# SIMPANAN Wajib
		Route::post('wajib/apprterima/{id}', 'SimpananWajibController@receiving_appr');
		Route::get('wajib/prosesterima/{id}', 'SimpananWajibController@receiving_process');
		Route::get('daftarsimpananwajib_json', 'SimpananWajibController@list_json');
		Route::get('wajib/prosesperbulan', 'SimpananWajibController@periodicprocess');
		Route::get('wajib/kalkulasiperbulan', 'SimpananWajibController@kalkulasiSimpananWajib');
		Route::get('wajib/postingkalkulasiperbulan', 'SimpananWajibController@postingKalkulasi');
		Route::get('wajib/testexcel', 'SimpananWajibController@exportExcelSimpananWajib');
		Route::get('wajib/pembatalan/{id}', 'SimpananWajibController@batalSimpok');

		Route::resource('wajib', 'SimpananWajibController');
		# END SIMPANAN Wajib

		# Pinjaman Koperasi
		Route::get('pinjaman/prosesterima/{id}', 'PinjamanController@penerimaan_cicilan');
		Route::post('pinjaman/apprangsuran/{id}', 'PinjamanController@receivingAngsuran');

		Route::post('pinjaman/apprterima/{id}', 'PinjamanController@transfer_appr');
		Route::get('pinjaman/prosesserah/{id}', 'PinjamanController@transfer_process');
		Route::get('pinjaman/approvalpengajuansatu', 'PinjamanController@ApproveDetailTransaksi');
		Route::get('pinjaman/approvalpengajuandua', 'PinjamanController@ApproveDetailTransaksi');
		Route::get('detail_pinjaman_json', 'PinjamanController@detail_pinjaman_json');
		Route::get('daftarpinjamans_json', 'PinjamanController@list_pinjaman_json');
		Route::resource('pinjaman', 'PinjamanController');
		# End Pinjaman

		# Pinjaman Elektronik
		
		Route::get('elektronik/prosesterima/{id}', 'PinjamanElektronikController@penerimaan_cicilan');
		Route::post('elektronik/apprangsuran/{id}', 'PinjamanElektronikController@receivingAngsuran');

		Route::post('elektronik/apprterima/{id}', 'PinjamanElektronikController@transfer_appr');
		Route::get('elektronik/prosesserah/{id}', 'PinjamanElektronikController@transfer_process');
		Route::get('elektronik/approvalpengajuansatu', 'PinjamanElektronikController@ApproveDetailTransaksi');
		Route::get('elektronik/approvalpengajuandua', 'PinjamanElektronikController@ApproveDetailTransaksi');
		Route::get('detail_pinjaman_elektronik_json', 'PinjamanElektronikController@detail_pinjaman_json');
		Route::get('daftarpinjamans_elektronik_json', 'PinjamanElektronikController@list_pinjaman_json');
		Route::resource('elektronik', 'PinjamanElektronikController');
		# End Pinjaman

		# Pinjaman Kalkulasi
		Route::post('kalkulasi/uploadPenerimaan', 'KalkulasiBulananController@uploadPenerimaan');
		Route::get('kalkulasi/exportExcelPosting', 'KalkulasiBulananController@exportExcelPosting');
		Route::post('kalkulasi/postingDataBayar', 'KalkulasiBulananController@PostingSelectedPerPeriode');
		Route::get('kalkulasi/kalkulasiperbulan', 'KalkulasiBulananController@KalkulasiPerPeriode');
		Route::resource('kalkulasi', 'KalkulasiBulananController');
		# End Pinjaman

		# Piutang Toko
		Route::get('piutangtoko/daftar', 'PiutangTokoController@index');
		// Route::get('piutangtoko/daftar_json', 'PiutangTokoController@listpiutang');

		# end Piutang Toko

		# PDF
		Route::get('pdf/investasi', 'PdfController@investasi');
		# END PDF
	});
	# END SIMPAN PINJAM


	# AKUNTANSI
	Route::group(['prefix' => 'akuntansi', 'namespace' => 'Akuntansi'], function()
	{
		Route::get('toko/hutang-pembelian','TokoController@hutangPembelian');
		Route::post('toko/bayar-pembelian','TokoController@bayarHutangPembelian');
		
		Route::get('toko/hutang-penjualan','TokoController@hutangPenjualan');
		Route::post('toko/bayar-penjualan','TokoController@bayaraHutangPenjualan');

		Route::get('toko/get','TokoController@get');
		Route::get('toko/jurnal','TokoController@jurnal');
		Route::get('toko','TokoController@index');

		Route::get('toko/kartu-piutang','TokoController@kartuPiutang');
		Route::get('toko/kartu-stok','TokoController@kartuStok');
		Route::get('toko/simpan-kartu-stok','TokoController@simpanKartuStok');
		Route::get('toko/kartu-piutang-detail','TokoController@kartuStokDetail');
		Route::get('toko/jurnal-periodik','TokoController@jurnalPeriodik');

    	Route::resource('rekening', 'RekeningController');

		Route::resource('coa', 'CoaController');

		Route::get('groupcoa/sdelete/{id}','GroupCoaController@status_delete');
		Route::resource('groupcoa', 'GroupCoaController');
		
		// Jurnal
		
		/* Route::get('jurnal/entryjkm', 'JournalController@entryJurnalJkm');
		Route::get('jurnal/jkk', 'JournalController@indexJkk');
		Route::get('jurnal/jkm', 'JournalController@indexJkm');
		Route::resource('coa', 'CoaController'); */
		



		
		# Jurnal

			
			Route::any('jurnal/saveimportData', 'JournalController@saveImportSession');
			Route::get('jurnal/importData', 'JournalController@showTempImportJurnal');
			Route::get('jurnal/getDetailimportData', 'JournalController@listTempImportDetail');

			// jurnal detail
			Route::get('jurnal/jkm/{id}', 'JournalController@showJurnal');
			Route::get('jurnal/jkk/{id}', 'JournalController@showJurnal');
			Route::get('jurnal/jrr/{id}', 'JournalController@showJurnal');

			// Jurnal entry
			Route::post('jurnal/updateNewJurnal/{id}', 'JournalController@storeUpdateJurnal');
			Route::post('jurnal/saveNewJurnal', 'JournalController@storeJurnal');

			Route::get('jurnal/entryjkm/{id}', 'JournalController@editJurnal');
			Route::get('jurnal/entryjkk/{id}', 'JournalController@editJurnal');
			Route::get('jurnal/entryjrr/{id}', 'JournalController@editJurnal');


			Route::get('jurnal/entryjkm', 'JournalController@entryJurnalJkm');
			Route::get('jurnal/entryjkk', 'JournalController@entryJurnalJkk');
			Route::get('jurnal/entryjrr', 'JournalController@entryJurnalJrr');
			
			// Donwloads
			Route::post('jurnal/importfilejurnal', 'JournalController@uploadImportJurnal');
			// Route::get('jurnal/importfilejurnal', 'JournalController@uploadImportJurnal');
			Route::get('jurnal/downloadfile', 'JournalController@downloadFileMaster');

			// Jurnal Index
			Route::get('jurnal/jkk', 'JournalController@indexJkk');
			Route::get('jurnal/jkm', 'JournalController@indexJkm');
			Route::get('jurnal/jrr', 'JournalController@indexJrr');
			Route::get('jurnal/monitoring', 'JournalController@indexMonitoringJurnals');

			// Jurnal index json
			Route::get('daftar_jurnal_json', 'JournalController@list_jurnal_json');

		Route::resource('jurnal', 'JournalController');
		# End Jurnal
	
			#Penyusutan
			Route::get('penyusutan/detailpenyusutan/{id}', 'JournalController@detailHistoryPenyusutan');
			Route::get('penyusutan/hapusPostingPenyusutan', 'JournalController@deleteHistoryPenyusutan');
			Route::get('penyusutan/daftarpenyusutanjson', 'JournalController@list_index_penyusutan_json');
			Route::get('penyusutan/kalkulasiPostingPenyusutan', 'JournalController@kalkulasiPenyusutan');
			Route::get('penyusutan', 'JournalController@indexPenyusutan');
			#End Penyusutan

	#Akuntansi

		
		Route::get('laporan/periodehasposting', 'LaporanAkuntansiController@checkhasposting');
		Route::get('laporan/postingJurnal', 'LaporanAkuntansiController@postingJurnalUmum');
		Route::get('laporan/bukubesar', 'LaporanAkuntansiController@showBukuBesar');
		Route::get('laporan/labarugiperiode', 'LaporanAkuntansiController@showLabaRugi');
		Route::get('laporan/neracaperiode', 'LaporanAkuntansiController@showNeraca');
		Route::get('laporan/perubahanekuitas', 'LaporanAkuntansiController@showPerubahanEkuitas');

	#end Akuntansi
	
	});
	# END AKUNTANSI


	# PENGATURAN
	Route::group(['prefix' => 'pengaturan', 'namespace' => 'Pengaturan'], function()
	{
		Route::post('user/reset/{id}', 'UserController@reset');
		Route::post('user/change-status/{id}', 'UserController@change_status');
		Route::resource('user', 'UserController');
		
	});
	# END PENGATURAN
});
