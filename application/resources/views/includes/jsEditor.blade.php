<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="{{assets('global/plugins/bootstrap-wysihtml5/wysihtml5-0.3.0.js')}}" type="text/javascript"></script>
<script src="{{assets('global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js')}}" type="text/javascript"></script>
<script src="{{assets('global/plugins/bootstrap-markdown/lib/markdown.js')}}" type="text/javascript"></script>
<script src="{{assets('global/plugins/bootstrap-markdown/js/bootstrap-markdown.js')}}" type="text/javascript"></script>
<script src="{{assets('global/plugins/bootstrap-summernote/summernote.min.js')}}" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<script>
var ComponentsEditors = function() {
    var t = function() {
            jQuery().wysihtml5 && $(".wysihtml5").size() > 0 && $(".wysihtml5").wysihtml5({
                stylesheets: ["../assets/global/plugins/bootstrap-wysihtml5/wysiwyg-color.css"]
            })
        },
        s = function() {
            $("#summernote_1").summernote({
                height: 300
            })
        };
    return {
        init: function() {
            t(), s()
        }
    }
}();
jQuery(document).ready(function() {
    ComponentsEditors.init()
});
</script>
