<!-- DOC: Apply "dropdown-dark" class after "dropdown-extended" to change the dropdown styte -->
<!-- DOC: Apply "dropdown-hoverable" class after below "dropdown" and remove data-toggle="dropdown" data-hover="dropdown" data-close-others="true" attributes to enable hover dropdown mode -->
<!-- DOC: Remove "dropdown-hoverable" and add data-toggle="dropdown" data-hover="dropdown" data-close-others="true" attributes to the below A element with dropdown-toggle class -->
<li class="dropdown {{ session('dropdown-hoverable-option') == 'yes' ? 'dropdown-hoverable' : '' }} dropdown-extended {{ session('page-header-top-dropdown-style-option') == 'dark' ? 'dropdown-dark' : '' }} dropdown-notification" id="header_notification_bar">
    <a href="javascript:;" class="dropdown-toggle" {{ session('dropdown-hoverable-option') == 'yes' ? '' : 'data-toggle=dropdown data-hover=dropdown data-close-others=true' }}>
         <i class="icon-bell"></i>
        <span class="badge badge-default infoTotal"> 0 </span>
    </a>
    <ul class="dropdown-menu">
        
        <li>
            <ul class="dropdown-menu-list scroller" style="height:250px;" data-handle-color="#637283">
                <li>
                    <a href="{{ url('usaha/persekot/verifikasi') }}">
                        <span class="time" id="totalVerifikasiPersekot">0</span>
                        <span class="details">
                             Verifikasi Persekot </span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('usaha/persekotpo/verifikasi') }}">
                        <span class="time" id="totalVerifikasiPersekotPO">0</span>
                        <span class="details">
                             Verifikasi Persekot PO </span>
                    </a>
                </li>

                <li>
                    <a href="{{ url('simpanpinjam/investasi') }}">
                        <span class="time" id="totalPengajuanInvestasi">0</span>
                        <span class="details"> Pengajuan Investasi </span>
                    </a>
                </li>

                <li>
                    <a href="{{ url('simpanpinjam/investasi') }}">
                        <span class="time" id="totalPenarikanInvestasi">0</span>
                        <span class="details"> Penarikan Investasi </span>
                    </a>
                </li>

                <li>
                    <a href="{{ url('simpanpinjam/pinjaman') }}">
                        <span class="time" id="totalPinjamanSp">0</span>
                        <span class="details"> Pinjaman SP </span>
                    </a>
                </li>

                <li>
                    <a href="{{ url('simpanpinjam/elektronik') }}">
                        <span class="time" id="totalPinjamanEl">0</span>
                        <span class="details"> Pinjaman Elektronik </span>
                    </a>
                </li>
              
            </ul>
        </li>
       
    </ul>
</li>