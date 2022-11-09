

<!-- BEGIN HEADER -->
<div class="page-header">
    <!-- BEGIN HEADER TOP -->
    <div class="page-header-top">
        <div class="container-fluid">
            <!-- BEGIN LOGO -->
            <div class="page-logo">
                <a href="#">
                    <img src="{{ assets('BIROUMUM.png')}}" alt="logo" class="" style="margin-top:10px;" width="180" >
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
        <div class="container-fluid">
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

                    <li class="menu-dropdown mega-menu-dropdown">
                        <a href="javascript:;"> Master <span class="arrow"></span></a>

                        <ul class="dropdown-menu" style="min-width: 710px">
                            <li>
                                <div class="mega-menu-content">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <ul class="mega-menu-submenu">
                                                <li><h3>Simpanan</h3></li>
                                                <li><a href="{{url('simpanpinjam/getKonfigurasiSimpanan')}}"> <i class="icon-settings"></i> Konfigurasi Simpanan </a></li>
                                                <!-- <li><a href="{{url('prototype-sp?menu=configSHU')}}"> <i class="icon-calculator "></i> Konfigurasi Formula SHU </a></li> -->
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <ul class="mega-menu-submenu">
                                                <li><h3>Pinjaman</h3></li>
                                                <li><a href="{{url('simpanpinjam/getKonfigurasiPinjaman')}}"> <i class="icon-settings"> </i> Konfigurasi Pinjaman </a></li>
                                                <!-- <li><a href="{{url('simpanpinjam/master/konfig/jenispinjaman')}}"> <i class="icon-calendar"> </i> Konfigurasi Jenis Pinjaman </a></li> -->
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </li>

                    <li class="menu-dropdown mega-menu-dropdown">
                        <a href="javascript:;"> Data Anggota <span class="arrow"></span></a>
                        <ul class="dropdown-menu" style="min-width: 710px">
                            <li>
                                <div class="mega-menu-content">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <ul class="mega-menu-submenu">
                                                <li><h3>Anggota</h3></li>
                                                <li><a href="{{url('simpanpinjam/anggota')}}" class="nav-link  "><i class="icon-users"></i> Daftar Anggota</a></li>
                                                <li aria-haspopup="true" class=" "><a href="{{url('simpanpinjam/anggota/create')}}" class="nav-link  "><i class="icon-user-follow "></i> Tambah Anggota</a></li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <ul class="mega-menu-submenu">
                                                <li><h3>Pengunduan Diri </h3></li>
                                                <li><a href="{{url('simpanpinjam/anggota/pengundurandiri')}}"> <i class="fa fa-pencil-square-o"> </i> Daftar Pengajuan Pengunduran Diri </a></li>
                                                <li><a href="{{url('simpanpinjam/anggota/pengajuanpengudurandiri')}}"> <i class="fa fa-pencil-square-o"> </i> Pengajuan Pengunduran Diri </a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </li>

                    <li aria-haspopup="true" class="menu-dropdown mega-menu-dropdown">
                        <a href="javascript:;"> Simpanan & Investasi
                            <span class="arrow"></span>
                        </a>
                        <ul class="dropdown-menu" style="min-width: 710px">
                            <li>
                                <div class="mega-menu-content">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <ul class="mega-menu-submenu">
                                                <li><h3>Simpanan Pokok</h3></li>
                                                <li><a href="{{url('simpanpinjam/simpananpokok')}}"> <i class="icon-list"></i> Daftar Simpanan Pokok </a></li>
                                                <li><a href="{{url('simpanpinjam/simpananpokok/create')}}"> <i class="icon-plus"></i> Setor (Tambah) Simpanan Pokok </a></li>
                                            </ul>
                                        </div>
                                        <div class="col-md-4">
                                            <ul class="mega-menu-submenu">
                                                <li><h3>Simpanan Wajib</h3></li>
                                                <li><a href="{{url('simpanpinjam/wajib')}}"> <i class="icon-list"> </i> Daftar Simpanan Wajib </a></li>
                                                <li><a href="{{url('simpanpinjam/wajib/create')}}"> <i class="icon-plus"> </i> Setor (Tambah) Simpanan Wajib </a></li>
                                                {{-- <li><a href="{{url('simpanpinjam/wajib/prosesperbulan')}}"> <i class="icon-calendar"> </i> Setor (Tambah) Perbulan </a></li> --}}
                                            </ul>
                                        </div>
                                        @if(App\Http\Models\Pengaturan\Menu::isValid('simpanpinjam/investasi', auth()->user()->level_id))
                                        <div class="col-md-4">
                                            <ul class="mega-menu-submenu">
                                                <li><h3>Investasi</h3></li>
                                                <li><a href="{{url('simpanpinjam/investasi')}}"> <i class="icon-list"></i> Investasi </a></li>
                                                <li><a href="{{url('simpanpinjam/investasi/bungainvestasi')}}"> <i class="fa fa-map-signs"></i> Bunga Investasi </a></li>
                                                @if(App\Http\Models\Pengaturan\Menu::isValid('simpanpinjam/investasi/create', auth()->user()->level_id))
                                                    <li><a href="{{url('simpanpinjam/investasi/create')}}"> <i class="icon-login"></i> Pengajuan (Tambah) Investasi </a></li>
                                                @endif
                                                @if(App\Http\Models\Pengaturan\Menu::isValid('simpanpinjam/investasi/penarikan', auth()->user()->level_id))
                                                    <li><a href="{{url('simpanpinjam/investasi/penarikan')}}"> <i class="icon-logout"></i> Pengajuan Penarikan Investasi </a></li>
                                                @endif
                                            </ul>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </li>

                    <li class="menu-dropdown mega-menu-dropdown">
                            <a href="javascript:;"> Pinjaman <span class="arrow"></span></a>

                            <ul class="dropdown-menu" style="min-width: 710px">
                                <li>
                                    <div class="mega-menu-content">
                                        <div class="row">
                                            @if(App\Http\Models\Pengaturan\Menu::isValid('simpanpinjam/pinjaman', auth()->user()->level_id))
                                            <div class="col-md-4">
                                                <ul class="mega-menu-submenu">
                                                    <li><h3>Koperasi</h3></li>
                                                    <!--li aria-haspopup="true" class=" ">
                                                        <a href="{{url('prototype-sp?menu=simulasiPinjaman')}}" class="nav-link  ">
                                                            <i class="icon-calculator"></i> Simulasi Pinjaman </a>
                                                    </li-->


                                                    <li><a href="{{url('simpanpinjam/pinjaman')}}" class="nav-link  "> <i class="icon-list"></i> Daftar Pinjaman </a></li>
                                                    @if(App\Http\Models\Pengaturan\Menu::isValid('simpanpinjam/pinjaman/create', auth()->user()->level_id))
                                                        <li><a href="{{url('simpanpinjam/pinjaman/create')}}" class="nav-link  "> <i class="fa fa-plus-square"></i> Pengajuan Pinjaman </a></li>
                                                    @endif

                                                    {{-- <li>
                                                        <a href="#" class="nav-link  ">
                                                            <i class="icon-calendar"></i> Angsuran Pinjaman
                                                        </a>
                                                    </li> --}}
                                                </ul>
                                            </div>
                                            @endif
                                            <div class="col-md-4">
                                                <ul class="mega-menu-submenu">
                                                    <li><h3>Elektronik</h3></li>
                                                    <li><a href="{{url('simpanpinjam/elektronik')}}" class="nav-link  "> <i class="fa fa-laptop"></i> Daftar Pinjaman Elektronik </a></li>
                                                    <li><a href="{{url('simpanpinjam/elektronik/create')}}" class="nav-link  "> <i class="fa fa-plus-square"></i> Pengajuan Pinjaman Elektronik</a></li>
                                                    
                                                </ul>
                                            </div>

                                            <div class="col-md-4">
                                                <ul class="mega-menu-submenu">
                                                    <li><h3>Piutang Toko</h3></li>
                                                    <li><a href="{{url('simpanpinjam/piutangtoko/daftar')}}" class="nav-link  "> <i class="fa fa-laptop"></i> Daftar Piutang Toko </a></li>
                                                    {{-- <li><a href="{{url('simpanpinjam/elektronik/create')}}" class="nav-link  "> <i class="fa fa-plus-square"></i> Pengajuan Pinjaman Elektronik</a></li> --}}
                                                    
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </li>

                        <li class="menu-dropdown classic-menu-dropdown">
                            <a href="{{url('simpanpinjam/kalkulasi')}}"> Kalkulasi Bulanan
                                <span class="arrow"></span>
                            </a>
                        </li>

                        {{--  <li class="menu-dropdown classic-menu-dropdown">
                            <a href="{{url('simpanpinjam/kalkulasiSHU')}}"> SHU
                                <span class="arrow"></span>
                            </a>
                        </li>  --}}


                </ul>
            </div>
            <!-- END MEGA MENU -->
        </div>
    </div>
    <!-- END HEADER MENU -->
</div>
<!-- END HEADER -->
