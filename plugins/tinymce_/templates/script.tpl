{literal}
    <script type="text/javascript">
        tinymce.PluginManager.load('moxiemanager', '/plugins/tinymce/plugins/moxiemanager/plugin.min.js');
        tinymce.init({
            selector: ".tinyMceEditor",
            language : 'lt',
            theme: "modern",

            plugins: [
                "advlist autolink autosave lists link image charmap print preview anchor",
                "visualblocks code fullscreen",
                "insertdatetime media table contextmenu paste moxiemanager"
            ],

            toolbar: "newdocument cut copy paste | undo redo | preview | styleselect removeformat | bold italic underline strikethrough | subscript superscript | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | charmap link unlink  anchor insertfile image media code ",
            autosave_ask_before_unload: false,
            height: 300,
            relative_urls: false
        });
    </script>
{/literal}