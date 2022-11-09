
@if(preg_match('/MSIE\s(?P<v>\d+)/i', @$_SERVER['HTTP_USER_AGENT'], $B) && $B['v'] <= 9)
<script src="{{ assets('global/plugins/respond.min.js') }}"></script>
<script src="{{ assets('global/plugins/excanvas.min.js') }}"></script>
<script src="{{ assets('global/plugins/ie8.fix.min.js') }}"></script>
@endif

<!-- BEGIN CORE PLUGINS -->
<script src="{{ assets('global/plugins/jquery.min.js') }}" type="text/javascript"></script>
<script src="{{ assets('global/plugins/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
<script src="{{ assets('global/plugins/js.cookie.min.js') }}" type="text/javascript"></script>
<script src="{{ assets('global/plugins/jquery-slimscroll/jquery.slimscroll.js') }}" type="text/javascript"></script>
<script src="{{ assets('global/plugins/jquery-nicescroll/jquery.nicescroll.js') }}" type="text/javascript"></script>
<script src="{{ assets('global/plugins/jquery.blockui.min.js') }}" type="text/javascript"></script>
<script src="{{ assets('global/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}" type="text/javascript"></script>
<script src="{{ assets('global/plugins/moment.min.js') }}" type="text/javascript"></script>
<script src="{{ assets('global/plugins/numeral.js') }}" type="text/javascript"></script>
<script src="{{ assets('global/plugins/bootstrap-toastr/toastr.js') }}" type="text/javascript"></script>
<script src="{{ assets('global/plugins/sweetalert2/sweetalert2.js') }}" type="text/javascript"></script>
<!-- END CORE PLUGINS -->

<!-- BEGIN PAGE LEVEL PLUGINS -->
@stack('plugins')
<!-- END PAGE LEVEL PLUGINS -->

<!-- BEGIN THEME GLOBAL SCRIPTS -->
<script src="{{ assets('global/scripts/app.js') }}" type="text/javascript"></script>
<!-- END THEME GLOBAL SCRIPTS -->

<!-- BEGIN PAGE LEVEL SCRIPTS -->
@stack('scripts')
<!-- END PAGE LEVEL SCRIPTS -->

<!-- BEGIN THEME LAYOUT SCRIPTS -->
<script src="{{ assets('layouts/layout3/scripts/layout.js') }}" type="text/javascript"></script>
<script src="{{ assets('layouts/layout/scripts/set-layout.js')}}" type="text/javascript"></script>
<script src="{{ assets('layouts/layout3/scripts/demo.js') }}" type="text/javascript"></script>
<script src="{{ assets('layouts/global/scripts/quick-sidebar.js') }}" type="text/javascript"></script>
<script src="{{ assets('layouts/global/scripts/quick-nav.js') }}" type="text/javascript"></script>
<!-- END THEME LAYOUT SCRIPTS -->

<script type="text/javascript">
$(document).ready(function(){

	
@if(notify()->ready())
	toastr["{{notify()->type()}}"]("{{notify()->option('text')}}", "{{notify()->message()}}");
@endif


});
</script>
