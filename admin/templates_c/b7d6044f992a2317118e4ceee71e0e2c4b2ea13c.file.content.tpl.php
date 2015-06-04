<?php /* Smarty version Smarty-3.1.18, created on 2014-10-12 14:35:14
         compiled from "C:\xampp\htdocs\admin\modules\content\content.tpl" */ ?>
<?php /*%%SmartyHeaderCode:810454380412106979-49371556%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b7d6044f992a2317118e4ceee71e0e2c4b2ea13c' => 
    array (
      0 => 'C:\\xampp\\htdocs\\admin\\modules\\content\\content.tpl',
      1 => 1413117305,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '810454380412106979-49371556',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_54380412125d72_46307920',
  'variables' => 
  array (
    'menu_content' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_54380412125d72_46307920')) {function content_54380412125d72_46307920($_smarty_tpl) {?><div class="row">
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
                                        <script type="text/javascript" src="/plugins/tinymce/tinymce.js"></script>
                                        <script type="text/javascript">
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
                                                       value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['menu_content']->value['Title'], ENT_QUOTES, 'UTF-8', true);?>
">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label" for="form-field-1">
                                                Aliasas
                                            </label>

                                            <div class="col-sm-9">
                                                <input placeholder="Text Field" id="form-field-1"
                                                       class="form-control" type="text" name="alias"
                                                       value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['menu_content']->value['Alias'], ENT_QUOTES, 'UTF-8', true);?>
">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label" for="form-field-2">
                                                Matomumas
                                            </label>

                                            <div class="col-sm-9">
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" name="visible" class="grey"
                                                           <?php if ($_smarty_tpl->tpl_vars['menu_content']->value['IsVisible']) {?>checked<?php }?>>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label" for="form-field-2">
                                                Raktažodžiai
                                            </label>

                                            <div class="col-sm-9">
                                                <textarea placeholder="Raktažodžiai" id="form-field-22" name="keywords"
                                                          class="form-control"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['menu_content']->value['Meta_keywords'], ENT_QUOTES, 'UTF-8', true);?>
</textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label" for="form-field-2">
                                                Apibūdinimas
                                            </label>

                                            <div class="col-sm-9">
                                                <textarea placeholder="Apibūdinimas" id="form-field-22"
                                                          name="description"
                                                          class="form-control"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['menu_content']->value['Meta_description'], ENT_QUOTES, 'UTF-8', true);?>
</textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label" for="form-field-2">
                                                Indeksuoti robots.txt
                                            </label>

                                            <div class="col-sm-9">
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" name="robots" class="grey"
                                                           <?php if ($_smarty_tpl->tpl_vars['menu_content']->value['Robots']) {?>checked<?php }?>>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label" for="form-field-2">
                                                Turinys
                                            </label>
                                            <div class="col-sm-9">
                                                <textarea class="moxiemanager" name="content"><?php echo $_smarty_tpl->tpl_vars['menu_content']->value['Content'];?>
</textarea>
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




<?php }} ?>
