<div class="row">
    <div class="col-sm-12">
        <div class="tabbable">
            <ul class="nav nav-tabs tab-padding tab-space-3 tab-blue" id="myTab4">
                <li class="active">
                    <a data-toggle="tab" href="#edit_lt">
                        Lietuvių
                    </a>
                </li>
                <li>
                    <a data-toggle="tab" href="#edit_en">
                        Anglų
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div id="edit_lt" class="tab-pane fade in active">
                    <div class="row">
                        <div class="col-sm-5 col-md-3">
                            <div class="user-left">
                                <div class="left">
                                    <h4>MENU</h4>
                                    <hr>
                                    <div class="panel-body panel-scroll height-600">
                                        <div id="tree-1" class="tree-demo"></div>
                                        <script src="/admin/assets/plugins/jQuery/jquery-2.1.1.min.js"></script>
                                        <script src="/admin/scripts/jstree.min.js"></script>
                                        <script src="/admin/scripts/TreeModule.js"></script>

                                        <script>
                                            UITreeview.setupTree($("#tree-1"));
                                        </script>
                                        <script type="text/javascript" src="/plugins/tinymce/tinymce.min.js"></script>
                                        <script type="text/javascript">
                                            tinymce.init({
                                                selector: ".mce",
                                                theme: "modern",
                                                language :'en',
                                                plugins: [
                                                    "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                                                    "searchreplace wordcount visualblocks visualchars code fullscreen",
                                                    "insertdatetime media nonbreaking save table contextmenu directionality",
                                                    "emoticons template paste textcolor colorpicker textpattern filemanager"
                                                ],
                                                toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
                                                toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | print preview code ",
                                                image_advtab: true ,
                                                removed_menuitems: 'newdocument',
                                                external_filemanager_path:"/plugins/tinymce/tinymce/js/filemanager/",
                                                filemanager_title:"Responsive Filemanager" ,
                                                external_plugins: {
                                                    "filemanager" : "/plugins/tinymce/tinymce/js/filemanager/plugin.min.js"
                                                }
                                            });

                                            /*
                                            tinymce.PluginManager.load('moxiemanager', '/plugins/tinymce/plugins/moxiemanager/plugin.min.js');
                                            tinymce.init({
                                                selector: ".moxiemanager",
                                                //language: 'lt',
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
                                            */
                                        </script>
                                    </div>
                                </div>
                                <table class="table table-condensed table-hover">

                                </table>

                            </div>
                        </div>
                        <div class="col-sm-7 col-md-9">
                            <div class="panel panel-white space10">
                                <div class="panel-heading">
                                    <i class="clip-menu"></i>
                                    Edit
                                    <div class="panel-tools">
                                        <a class="btn btn-xs btn-link panel-close" href="#">
                                            <i class="fa fa-times"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="panel-body panel-scroll height-600">
                                    <form role="form" class="form-horizontal" method="post">
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label" for="form-field-1">
                                                Pavadinimas
                                            </label>

                                            <div class="col-sm-9">
                                                <input placeholder="Text Field" id="form-field-1"
                                                       class="form-control" type="text" name="title"
                                                       value="{$menu_content.Title|escape}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label" for="form-field-1">
                                                Aliasas
                                            </label>

                                            <div class="col-sm-9">
                                                <input placeholder="Text Field" id="form-field-1"
                                                       class="form-control" type="text" name="alias"
                                                       value="{$menu_content.Alias|escape}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label" for="form-field-2">
                                                Matomumas
                                            </label>

                                            <div class="col-sm-9">
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" name="visible" class="grey"
                                                           {if $menu_content.IsVisible}checked{/if}>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label" for="form-field-2">
                                                Raktažodžiai
                                            </label>

                                            <div class="col-sm-9">
                                                <textarea placeholder="Raktažodžiai" id="form-field-22" name="keywords"
                                                          class="form-control">{$menu_content.Meta_keywords|escape}</textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label" for="form-field-2">
                                                Apibūdinimas
                                            </label>

                                            <div class="col-sm-9">
                                                <textarea placeholder="Apibūdinimas" id="form-field-22"
                                                          name="description"
                                                          class="form-control">{$menu_content.Meta_description|escape}</textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label" for="form-field-2">
                                                Indeksuoti robots.txt
                                            </label>

                                            <div class="col-sm-9">
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" name="robots" class="grey"
                                                           {if $menu_content.Robots}checked{/if}>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label" for="form-field-2">
                                                Turinys
                                            </label>
                                            <div class="col-sm-9">
                                                <textarea class="mce" name="content">{$menu_content.Content}</textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label" for="form-field-2">
                                                Modulis
                                            </label>
                                            <div class="col-sm-9">
                                            <select size="1" name="modules">
                                                <option value="1">-No Module-</option>
                                                {foreach from=$modules item=module}
                                                <option value="{$module.Id}"
                                                        {if $module.Id == $menu_content.Module_Id}selected{/if}>
                                                    {$module.Title}
                                                </option>
                                                {/foreach}
                                            </select>

                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label" for="form-field-2">
                                                Template
                                            </label>
                                            <div class="col-sm-9">
                                                <select size="1" name="templates">
                                                    {foreach from=$templates item=template}
                                                        <option value="{$template.Id}"
                                                                {if $template.Id == $menu_content.Template_Id}selected{/if}>
                                                            {$template.Title}
                                                        </option>
                                                    {/foreach}
                                                </select>

                                            </div>
                                        </div>
                                </div>

                                        <input type="hidden" name="action" value="edit_menu">
                                        <button type="submit" class="btn btn-success">Saugoti</button>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div id="edit_en" class="tab-pane fade">
                    ANTRAS TAB
                </div>

            </div>
        </div>
    </div>
</div>




