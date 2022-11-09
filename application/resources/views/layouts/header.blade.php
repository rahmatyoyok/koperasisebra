<!-- BEGIN HEADER -->
<div class="page-header">
    <!-- BEGIN HEADER TOP -->
    <div class="page-header-top">
        <div class="container">
            <!-- BEGIN LOGO -->
            <div class="page-logo">
                <a href="#">
                    <img src="{{ assets('EARSIP.png')}}" alt="logo" class="" style="margin-top:20px;" width="180" >
                </a>
            </div>
            <!-- END LOGO -->
            <!-- BEGIN RESPONSIVE MENU TOGGLER -->
            <a href="javascript:;" class="menu-toggler"></a>
            <!-- END RESPONSIVE MENU TOGGLER -->
            <!-- BEGIN TOP NAVIGATION MENU -->
            <div class="top-menu">
                <ul class="nav navbar-nav pull-right">
                    <!-- BEGIN USER LOGIN DROPDOWN -->
                    @include('includes.dropdown-notification')
                    <li class="droddown dropdown-separator">
                        <span class="separator"></span>
                    </li>

                    @include('includes.dropdown-user')
                    <!-- END USER LOGIN DROPDOWN -->
                </ul>
            </div>
            <!-- END TOP NAVIGATION MENU -->
        </div>
    </div>
    <!-- END HEADER TOP -->
    <!-- BEGIN HEADER MENU -->
    <div class="page-header-menu">
        <div class="container">
            <!-- BEGIN MEGA MENU -->
            <!-- DOC: Apply "hor-menu-light" class after the "hor-menu" class below to have a horizontal menu with white background -->
            <!-- DOC: Remove data-hover="dropdown" and data-close-others="true" attributes below to disable the dropdown opening on mouse hover -->
            <div class="hor-menu  ">
                <ul class="nav navbar-nav">

                     <li class="menu-dropdown classic-menu-dropdown">
                        <a href="javascript:;"> Beranda </a>
                        <ul class="dropdown-menu pull-left">
                            <!-- <li aria-haspopup="true" class=" ">
                                <a href="{{url('home')}}" class="nav-link  ">
                                    KPRI SEBRA</a>
                            </li> -->
                            <li aria-haspopup="true" class=" ">
                                <a href="{{url('usaha/home')}}" class="nav-link  ">
                                    Usaha Umum
                                </a>
                            </li>
                            <li aria-haspopup="true" class=" ">
                                <a href="{{url('simpanpinjam/home')}}" class="nav-link  ">
                                    Simpan Pinjam</a>
                            </li>
                            <li aria-haspopup="true" class=" ">
                                <a href="{{url('akuntansi/home')}}" class="nav-link  ">Akuntansi</a>
                            </li>
                        </ul>
                    </li>
                    @if(\Auth::user()->level_id == 1 || \Auth::user()->level_id == 7 || \Auth::user()->level_id == 8 || \Auth::user()->level_id == 9 || \Auth::user()->level_id == 10 || \Auth::user()->level_id == 13 || \Auth::user()->level_id == 11 || \Auth::user()->level_id == 14  )
                    <li class="menu-dropdown classic-menu-dropdown">
                        <a href="javascript:;"> Work Order </a>
                        <ul class="dropdown-menu pull-left">
                            <li aria-haspopup="true" class=" ">
                                <a href="{{url('usaha/wo/create')}}" class="nav-link  ">
                                    Tambah
                                </a>
                            </li>
                            <li aria-haspopup="true" class=" ">
                                <a href="{{url('usaha/wo')}}" class="nav-link  ">
                                    Monitoring Transaksi</a>
                            </li>
                            <li aria-haspopup="true" class=" ">
                                <a href="{{url('usaha/wo/report')}}" class="nav-link  ">
                                    Laporan Rekap</a>
                            </li>
                            
                            <li aria-haspopup="true" class=" ">
                                <a href="{{url('usaha/wo/report-pembayaran')}}" class="nav-link  ">
                                    Laporan Pembayaran</a>
                            </li>
                        </ul>
                    </li>
                    @endif

                    @if(\Auth::user()->level_id == 1 || \Auth::user()->level_id == 7 || \Auth::user()->level_id == 8 || \Auth::user()->level_id == 9 || \Auth::user()->level_id == 10 || \Auth::user()->level_id == 13 )
                    <li class="menu-dropdown classic-menu-dropdown">
                        <a href="javascript:;"> Purchase Order </a>
                        <ul class="dropdown-menu pull-left">
                            <li aria-haspopup="true" class=" ">
                                <a href="{{url('usaha/po')}}" class="nav-link  ">
                                    Monitoring</a>
                            </li>
                            <li aria-haspopup="true" class=" ">
                                <a href="{{url('usaha/po/report')}}" class="nav-link  ">
                                    Laporan Rekap</a>
                            </li>
                            <li aria-haspopup="true" class=" ">
                                <a href="{{url('usaha/po/report-pembayaran')}}" class="nav-link  ">
                                    Laporan Pembayaran</a>
                            </li>
                        </ul>
                    </li>
                    @endif

                    @if(\Auth::user()->level_id == 1 || \Auth::user()->level_id == 7 || \Auth::user()->level_id == 8 || \Auth::user()->level_id == 9 || \Auth::user()->level_id == 10 || \Auth::user()->level_id == 11 || \Auth::user()->level_id == 14 || \Auth::user()->level_id == 13 )
                    <li class="menu-dropdown classic-menu-dropdown">
                        <a href="javascript:;"> Persekot PO </a>
                        <ul class="dropdown-menu pull-left">
                            @if(\Auth::user()->level_id == 1 ||  \Auth::user()->level_id == 9  )
                            <li aria-haspopup="true" class=" ">
                                <a href="{{url('usaha/persekotpo/verifikasi')}}" class="nav-link  ">
                                    Verifikasi</a>
                            </li>
                            @endif
                            
                            @if(\Auth::user()->level_id == 1 || \Auth::user()->level_id == 7 || \Auth::user()->level_id == 8 || \Auth::user()->level_id == 9 || \Auth::user()->level_id == 10 || \Auth::user()->level_id == 11 || \Auth::user()->level_id == 14 || \Auth::user()->level_id == 13)
                            <li aria-haspopup="true" class=" ">
                                <a href="{{url('usaha/persekotpo')}}" class="nav-link  ">
                                    Realisasi</a>
                            </li>
                            <li aria-haspopup="true" class=" ">
                                <a href="{{url('usaha/persekotpo/report')}}" class="nav-link "> Rekap Persekot PO</a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    
                    
                    <li class="menu-dropdown classic-menu-dropdown">
                        <a href="javascript:;"> Persekot </a>
                        <ul class="dropdown-menu pull-left">
                            @if(\Auth::user()->level_id == 1 || \Auth::user()->level_id == 7 || \Auth::user()->level_id == 8 || \Auth::user()->level_id == 9 || \Auth::user()->level_id == 10  )
                            <li aria-haspopup="true" class=" ">
                                <a href="{{url('usaha/persekot/create')}}" class="nav-link  ">
                                    Pengajuan
                                </a>
                            </li>
                            @endif

                            @if(\Auth::user()->level_id == 1 ||  \Auth::user()->level_id == 9  )
                            <li aria-haspopup="true" class=" ">
                                <a href="{{url('usaha/persekot/verifikasi')}}" class="nav-link  ">
                                    Verifikasi</a>
                            </li>
                            @endif

                            @if(\Auth::user()->level_id == 1 || \Auth::user()->level_id == 7 || \Auth::user()->level_id == 8 || \Auth::user()->level_id == 9 || \Auth::user()->level_id == 10  )
                            <li aria-haspopup="true" class=" ">
                                <a href="{{url('usaha/persekot')}}" class="nav-link  ">
                                    Realisasi</a>
                            </li>
                            @endif
                            @if(\Auth::user()->level_id == 1 || \Auth::user()->level_id == 7 || \Auth::user()->level_id == 8 || \Auth::user()->level_id == 9 || \Auth::user()->level_id == 10 || \Auth::user()->level_id == 13 || \Auth::user()->level_id == 11 || \Auth::user()->level_id == 14  )
                            <li aria-haspopup="true" class=" ">
                                <a href="{{url('usaha/persekot/list-pnpo')}}" class="nav-link  ">
                                    PNPO</a>
                            </li>
                            @endif

                            @if(\Auth::user()->level_id == 1 || \Auth::user()->level_id == 7 || \Auth::user()->level_id == 8 || \Auth::user()->level_id == 9 || \Auth::user()->level_id == 10  )
                            <li aria-haspopup="true" class=" ">
                                <a href="{{url('usaha/persekot/pembayaran')}}" class="nav-link  ">
                                    Pembayaran</a>
                            </li>
                            <li aria-haspopup="true" class=" ">
                                <a href="{{url('usaha/persekot/report')}}" class="nav-link "> Rekap Persekot </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    

                    @if(\Auth::user()->level_id == 1 || \Auth::user()->level_id == 7 || \Auth::user()->level_id == 8 || \Auth::user()->level_id == 9 || \Auth::user()->level_id == 10  )
                    <li class="menu-dropdown classic-menu-dropdown">
                        <a href="javascript:;"> Aset </a>
                        <ul class="dropdown-menu pull-left">

                            <li aria-haspopup="true" class=" ">
                                <a href="{{url('usaha/aset/create')}}" class="nav-link  ">
                                    Pendaftaran Aset
                                </a>
                            </li>
                            <li aria-haspopup="true" class=" ">
                                <a href="{{url('usaha/aset')}}" class="nav-link  ">
                                    List Pendaftaran Aset</a>
                            </li>
                            <li aria-haspopup="true" class=" ">
                                <a href="{{url('usaha/aset/list')}}" class="nav-link  ">
                                    Daftar Aset</a>
                            </li>
                        </ul>
                    </li>
                    @endif
                    
                    @if(\Auth::user()->level_id == 1 || \Auth::user()->level_id == 7 || \Auth::user()->level_id == 8 || \Auth::user()->level_id == 9 || \Auth::user()->level_id == 10  )
                    <li class="menu-dropdown classic-menu-dropdown">
                        <a href="javascript:;"> Master </a>
                        <ul class="dropdown-menu pull-left">
                            <li aria-haspopup="true" class=" ">
                                <a href="{{url('usaha/master/stockcode')}}" class="nav-link  ">
                                    Stockcode</a>
                            </li>
                            <li aria-haspopup="true" class=" ">
                                <a href="{{url('usaha/master/pr')}}" class="nav-link  ">
                                    PR</a>
                            </li>
                            <li aria-haspopup="true" class=" ">
                                <a href="{{url('usaha/master/supplier')}}" class="nav-link  ">
                                    Supplier</a>
                            </li>
                            <li aria-haspopup="true" class=" ">
                                <a href="{{url('usaha/master/client')}}" class="nav-link  ">
                                    Client
                                </a>
                            </li>
                            <li aria-haspopup="true" class=" ">
                                <a href="{{url('usaha/master/spv')}}" class="nav-link  ">
                                    SPV
                                </a>
                            </li>
                            <li aria-haspopup="true" class=" ">
                                <a href="{{url('usaha/master/lokasi')}}" class="nav-link  ">
                                    Lokasi
                                </a>
                            </li>
                            <li aria-haspopup="true" class=" ">
                                <a href="{{url('usaha/master/setting')}}" class="nav-link  ">
                                    Pengaturan Usaha Umum
                                </a>
                            </li>

                        </ul>
                    </li>
                    <li class="menu-dropdown classic-menu-dropdown">
                        <a href="{{url('pengaturan/user')}}"> Pengaturan Pengguna
                            <span class="arrow"></span>
                        </a>
                    </li>
                    @endif

                </ul>
            </div>
            <!-- END MEGA MENU -->
        </div>
    </div>
    <!-- END HEADER MENU -->
</div>
<!-- END HEADER -->
