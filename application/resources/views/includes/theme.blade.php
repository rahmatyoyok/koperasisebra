<div class="theme-panel hidden-xs hidden-sm">
    <div class="toggler"> </div>
    <div class="toggler-close"> </div>
    <div class="theme-options">
        <div class="theme-option theme-colors clearfix">
            <span> THEME COLOR </span>
            <ul>
                <li class="color-default tooltips li-layout {{ session('color-option') == 'default' || session('color-option') == '' ? 'current' : '' }}" data-style="default" data-container="body" data-placement="bottom" data-original-title="Default" data-key="color-option" data-value="default"> </li>
                <li class="color-darkblue tooltips li-layout {{ session('color-option') == 'darkblue' ? 'current' : '' }}" data-style="darkblue" data-container="body" data-placement="bottom" data-original-title="Dark Blue" data-key="color-option" data-value="darkblue"> </li>
                <li class="color-blue tooltips li-layout {{ session('color-option') == 'blue' ? 'current' : '' }}" data-style="blue" data-container="body" data-placement="bottom" data-original-title="Blue" data-key="color-option" data-value="blue"> </li>
                <li class="color-grey tooltips li-layout {{ session('color-option') == 'grey' ? 'current' : '' }}" data-style="grey" data-container="body" data-placement="bottom" data-original-title="Grey" data-key="color-option" data-value="grey"> </li>
                <li class="color-light tooltips li-layout {{ session('color-option') == 'light' ? 'current' : '' }}" data-style="light" data-container="body" data-placement="bottom" data-original-title="Light" data-key="color-option" data-value="light"> </li>
                <li class="color-light2 tooltips li-layout {{ session('color-option') == 'light2' ? 'current' : '' }}" data-style="light2" data-container="body" data-placement="bottom" data-html="true" data-original-title="Light 2" data-key="color-option" data-value="light2"> </li>
            </ul>
        </div>
        {{-- <div class="theme-option">
            <span> Layout </span>
            <select class="layout-option form-control input-sm select-layout" data-key="layout-option">
                <option value="fluid" selected="selected">Fluid</option>
                <option value="boxed">Boxed</option>
            </select>
        </div> --}}
        {{-- <div class="theme-option">
            <span> Header </span>
            <select class="page-header-option form-control input-sm select-layout" data-key="page-header-option">
                <option value="fixed" selected="selected">Fixed</option>
                <option value="default">Default</option>
            </select>
        </div> --}}
        <div class="theme-option">
            <span> Top Menu Dropdown</span>
            <select class="page-header-top-dropdown-style-option form-control input-sm select-layout" data-key="page-header-top-dropdown-style-option">
                <option value="light" {{ session('page-header-top-dropdown-style-option') == 'light' ? 'selected="selected"' : '' }}>Light</option>
                <option value="dark" {{ session('page-header-top-dropdown-style-option') == 'dark' ? 'selected="selected"' : '' }}>Dark</option>
            </select>
        </div>
        <div class="theme-option">
            <span> Dropdown Hoverable</span>
            <select class="dropdown-hoverable-option form-control input-sm select-layout" data-key="dropdown-hoverable-option">
                <option value="no" {{ session('dropdown-hoverable-option') == 'no' ? 'selected="selected"' : '' }}>No</option>
                <option value="yes" {{ session('dropdown-hoverable-option') == 'yes' ? 'selected="selected"' : '' }}>Yes</option>
            </select>
        </div>
        <div class="theme-option">
            <span> Sidebar Mode</span>
            <select class="sidebar-option form-control input-sm select-layout" data-key="sidebar-option">
                <option value="default" {{ session('sidebar-option') == 'default' ? 'selected="selected"' : '' }}>Default</option>
                <option value="fixed" {{ session('sidebar-option') == 'fixed' ? 'selected="selected"' : '' }}>Fixed</option>
            </select>
        </div>
        {{-- <div class="theme-option">
            <span> Sidebar Menu </span>
            <select class="sidebar-menu-option form-control input-sm select-layout" data-key="sidebar-menu-option">
                <option value="accordion" selected="selected">Accordion</option>
                <option value="hover">Hover</option>
            </select>
        </div> --}}
        <div class="theme-option">
            <span> Sidebar Style </span>
            <select class="sidebar-style-option form-control input-sm select-layout" data-key="sidebar-style-option">
                <option value="default" {{ session('sidebar-style-option') == 'default' ? 'selected="selected"' : '' }}>Default</option>
                <option value="light" {{ session('sidebar-style-option') == 'light' ? 'selected="selected"' : '' }}>Light</option>
            </select>
        </div>
        <div class="theme-option">
            <span> Sidebar Position </span>
            <select class="sidebar-pos-option form-control input-sm select-layout" data-key="sidebar-pos-option">
                <option value="left" {{ session('sidebar-pos-option') == 'left' ? 'selected="selected"' : '' }}>Left</option>
                <option value="right" {{ session('sidebar-pos-option') == 'right' ? 'selected="selected"' : '' }}>Right</option>
            </select>
        </div>
        <div class="theme-option">
            <span> Footer </span>
            <select class="page-footer-option form-control input-sm select-layout" data-key="page-footer-option">
                <option value="default" {{ session('page-footer-option') == 'default' ? 'selected="selected"' : '' }}>Default</option>
                <option value="fixed" {{ session('page-footer-option') == 'fixed' ? 'selected="selected"' : '' }}>Fixed</option>
            </select>
        </div>
    </div>
</div>