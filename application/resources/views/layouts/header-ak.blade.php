

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
                            <li aria-haspopup="true" class=" ">
                                <a href="{{url('home')}}" class="nav-link  ">
                                    KPRI SEBRA</a>
                            </li>
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

                    <!-- <li class="menu-dropdown classic-menu-dropdown">
                        <a href="{{url('akuntansi/rekening')}}"> Rekening Bank
                            <span class="arrow"></span>
                        </a>
                    </li> -->

                    @if(\Auth::user()->level_id == 1 || \Auth::user()->level_id == 7 || \Auth::user()->level_id == 8 || \Auth::user()->level_id == 9 || \Auth::user()->level_id == 10  )
                    <li class="menu-dropdown classic-menu-dropdown">
                        <a href="javascript:;"> Chart of Account </a>
                        <ul class="dropdown-menu pull-left">
                        <li aria-haspopup="true" class=" ">
                                <a href="{{url('akuntansi/coa')}}" class="nav-link  ">
                                    <i class="icon-user-follow "></i> Daftar COA
                                </a>
                            </li>

                            <li aria-haspopup="true" class=" ">
                                <a href="{{url('akuntansi/coa/create')}}" class="nav-link  ">
                                    <i class="icon-user-follow "></i> Tambah COA
                                </a>
                            </li>

                            
                            <li aria-haspopup="true" class=" ">
                                <a href="{{url('akuntansi/groupcoa')}}" class="nav-link  ">
                                    <i class="icon-users"></i> Group COA</a>
                            </li>
                            <li aria-haspopup="true" class=" ">
                                <a href="{{url('akuntansi/groupcoa/create')}}" class="nav-link  ">
                                    <i class="icon-users"></i> Tambah Group COA</a>
                            </li>
                            
                        </ul>
                    </li>
                    @endif

                    @if(\Auth::user()->level_id == 1 || \Auth::user()->level_id == 7 || \Auth::user()->level_id == 8 || \Auth::user()->level_id == 9 || \Auth::user()->level_id == 10 || \Auth::user()->level_id == 14  )
                    <li class="menu-dropdown classic-menu-dropdown">
                        <a href="javascript:;"> Toko </a>
                        <ul class="dropdown-menu pull-left">
                            <li class="menu-dropdown classic-menu-dropdown">
                                <a href="{{url('akuntansi/toko')}}"> Jurnal
                                    <span class="arrow"></span>
                                </a>
                            </li>
                            {{-- <li class="menu-dropdown classic-menu-dropdown">
                                <a href="{{url('akuntansi/toko/jurnal-periodik')}}"> Jurnal Periodik
                                    <span class="arrow"></span>
                                </a>
                            </li> --}}
                            {{-- <li class="menu-dropdown classic-menu-dropdown">
                                <a href="{{url('akuntansi/toko/hutang-pembelian')}}"> Hutang Pembelian
                                    <span class="arrow"></span>
                                </a>
                            </li>
                            <li class="menu-dropdown classic-menu-dropdown">
                                <a href="{{url('akuntansi/toko/hutang-penjualan')}}"> Hutang Penjualan
                                    <span class="arrow"></span>
                                </a>
                            </li> --}}
                            <li class="menu-dropdown classic-menu-dropdown">
                                <a href="{{url('akuntansi/toko/kartu-piutang')}}"> Kartu Piutang
                                    <span class="arrow"></span>
                                </a>
                            </li>
                            <li class="menu-dropdown classic-menu-dropdown">
                                <a href="{{url('akuntansi/toko/kartu-stok')}}"> Kartu Stok
                                    <span class="arrow"></span>
                                </a>
                            </li>
                            
                        </ul>
                    </li>
                    @endif

                    @if(\Auth::user()->level_id == 1 || \Auth::user()->level_id == 7 || \Auth::user()->level_id == 8 || \Auth::user()->level_id == 9 || \Auth::user()->level_id == 10 || \Auth::user()->level_id == 11 || \Auth::user()->level_id == 14   )
                    <li class="menu-dropdown mega-menu-dropdown">
                        <a href="javascript:;"> Jurnal </a>
                        <ul class="dropdown-menu pull-left">
                            <li aria-haspopup="true" class=" ">
                                <a href="{{url('akuntansi/jurnal/jkm')}}" class="nav-link  ">
                                    <i class="icon-login"></i> Kas Masuk (JKM) </a>
                            </li>
                            <li aria-haspopup="true" class=" ">
                                <a href="{{url('akuntansi/jurnal/jkk')}}" class="nav-link  ">
                                    <i class="icon-logout"></i> Kas Keluar (JKK) </a>
                            </li>
                            <li aria-haspopup="true" class=" ">
                                <a href="{{url('akuntansi/jurnal/jrr')}}" class="nav-link  ">
                                    <i class="icon-refresh"></i> Manual (JRR) </a>
                            </li>

                            <li aria-haspopup="true" class=" ">
                                <a href="{{url('akuntansi/jurnal/monitoring')}}" class="nav-link  ">
                                    <i class="fa fa-list-alt"></i> Monitoring Jurnal </a>
                            </li>
                        </ul>
                    </li>


                    <li class="menu-dropdown classic-menu-dropdown">
                        <a href="{{url('akuntansi/penyusutan')}}"> Akumulasi Penyusutan</a>
                    </li>


                    @endif

                    @if(\Auth::user()->level_id == 1 || \Auth::user()->level_id == 7 || \Auth::user()->level_id == 8 || \Auth::user()->level_id == 9 || \Auth::user()->level_id == 10   )

                    <li class="menu-dropdown classic-menu-dropdown">
                        <a href="{{url('akuntansi/laporan/postingJurnal')}}"> Posting Jurnal</a>
                    </li>

                    <li aria-haspopup="true" class=" ">
                        <a href="{{url('akuntansi/laporan/bukubesar')}}" class="nav-link">Laporan Buku Besar </a>
                    </li>

                    <li aria-haspopup="true" class=" ">
                        <a href="{{url('akuntansi/laporan/labarugiperiode')}}" class="nav-link">Laporan Laba Rugi </a>
                    </li>

                    <li aria-haspopup="true" class=" ">
                        <a href="{{url('akuntansi/laporan/neracaperiode')}}" class="nav-link"> Laporan Neraca </a>
                    </li>
{{-- 
                    <li aria-haspopup="true" class=" ">
                        <a href="{{url('akuntansi/laporan/perubahanekuitas')}}" class="nav-link"> Laporan Perubahan Ekuitas </a>
                    </li>

                    <li aria-haspopup="true" class=" ">
                        <a href="{{url('akuntansi/laporan/neracaperiode')}}" class="nav-link"> Laporan Arus kas </a>
                    </li> --}}


                    <!-- <li class="menu-dropdown mega-menu-dropdown">
                        <a href="javascript:;"> Laporan </a>
                        <ul class="dropdown-menu pull-left">
                            

                            

                            

                        </ul>
                    </li> -->
                    @endif
                </ul>
            </div>
            <!-- END MEGA MENU -->
        </div>
    </div>
    <!-- END HEADER MENU -->
</div>
<!-- END HEADER -->
