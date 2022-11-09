<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<html lang="en">

    <!-- BEGIN HEAD -->
    <head>
        <meta charset="utf-8" />
        <title>@yield('title')
        </title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="{{ strip_tags(config('app.desc')) }}" name="description" />
        <meta content="{{ strip_tags(config('app.author')) }}" name="author" />
        <meta content="{{ csrf_token() }}" name="csrf-token">
        @include('includes.styles')
        <style>
            .table-scrollable{
                border:none!important;
            }

            .page-header .page-header-top .top-menu .navbar-nav > li.dropdown-notification .dropdown-menu .dropdown-menu-list > li > a .red {
                background: #EF4836;
                color:#ffffff;
                font-weight:bold;
            }
            .page-header .page-header-top .top-menu .navbar-nav > li.dropdown-notification .dropdown-menu .dropdown-menu-list > li > a:hover .red {
                background: #EF4836;color:#ffffff;
                font-weight:bold;
            }
        </style>

        <script>
            function convertDateDBtoIndo(string) {
                bulanIndo = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September' , 'Oktober', 'November', 'Desember'];
            
                tanggal = string.split("-")[2];
                bulan = string.split("-")[1];
                tahun = string.split("-")[0];
            
                return tanggal + " " + bulanIndo[Math.abs(bulan)] + " " + tahun;
            }
        </script>
        <link rel="shortcut icon" href="{{ assets('Ico.png')}}" />
    </head>
    <!-- END HEAD -->

    <body class="page-header-fixed page-sidebar-closed-hide-logo page-container-bg-solid page-header-top-fixed page-content-white page-md {{ (session('sidebar-option') == 'fixed' ? 'page-sidebar-fixed' : '') }} {{ (session('sidebar-pos-option') == 'right' ? 'page-sidebar-reversed' : '') }} {{ (session('page-footer-option') == 'fixed' ? 'page-footer-fixed' : '') }}">
        <div class="page-wrapper">
            <div class="page-wrapper-row">
                <div class="page-wrapper-top">
                    <!-- BEGIN HEADER -->
                    @include('layouts.header-sp')
                    <!-- END HEADER -->
                </div>
            </div>
            <div class="page-wrapper-row full-height">
                <div class="page-wrapper-middle">
                    <!-- BEGIN CONTAINER -->
                    <div class="page-container">
                        <!-- BEGIN CONTENT -->
                        <div class="page-content-wrapper">
                            @yield('content')
                            <!-- BEGIN CONTENT BODY -->

                            <!-- END CONTENT BODY -->
                        </div>
                        <!-- END CONTENT -->
                        <!-- BEGIN QUICK SIDEBAR -->
                        <a href="javascript:;" class="page-quick-sidebar-toggler">
                            <i class="icon-login"></i>
                        </a>
                        <!-- END QUICK SIDEBAR -->
                    </div>
                    <!-- END CONTAINER -->
                </div>
            </div>
            <div class="page-wrapper-row">
                <div class="page-wrapper-bottom">
                    @include('layouts.footer')
                </div>
            </div>
        </div>
        <!-- BEGIN SCRIPTS -->
        @include('includes.scripts')
        <!-- END SCRIPTS -->
        
        <script>
            function ajaxNotif(){
                $.ajax({
                        method: "GET",
                        url: "{{ url('getInfoNotifUsaha') }}",
                        dataType: "json",
                        success : function(edata){

                            $('.infoTotal').html(edata.total);
                            $('#totalVerifikasiPersekot').html(edata.totalVerifikasiPersekot);
                            $('#totalVerifikasiPersekotPO').html(edata.totalVerifikasiPersekotPO);

                            $('#totalPengajuanInvestasi').removeClass('red');
                            if(parseInt(edata.totalVerifikasiInvestasiIn) > 0){
                                $('#totalPengajuanInvestasi').addClass('red');
                                $('#totalPengajuanInvestasi').html(edata.totalVerifikasiInvestasiIn);
                            }

                            $('#totalPenarikanInvestasi').removeClass('red');
                            if(parseInt(edata.totalVerifikasiInvestasiOut) > 0){
                                $('#totalPenarikanInvestasi').addClass('red');
                                $('#totalPenarikanInvestasi').html(edata.totalVerifikasiInvestasiOut);
                            }

                            $('#totalPinjamanSp').removeClass('red');
                            if(parseInt(edata.totalVerifikasiPinjamanSP) > 0){
                                $('#totalPinjamanSp').addClass('red');
                                $('#totalPinjamanSp').html(edata.totalVerifikasiPinjamanSP);
                            }
                            
                            $('#totalPinjamanEl').removeClass('red');
                            if(parseInt(edata.totalVerifikasiPinjamanEl) > 0){
                                $('#totalPinjamanEl').addClass('red');
                                $('#totalPinjamanEl').html(edata.totalVerifikasiPinjamanEl);
                            }
                        }
                    });
            } 

            ajaxNotif();
            setInterval(function(){
                ajaxNotif();
            }, 30000);
        </script>
    
    </body>

</html>
