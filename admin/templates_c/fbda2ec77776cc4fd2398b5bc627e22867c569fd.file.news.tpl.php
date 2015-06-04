<?php /* Smarty version Smarty-3.1.18, created on 2015-03-25 13:45:12
         compiled from "D:\xampp\htdocs\admin\modules\news\news.tpl" */ ?>
<?php /*%%SmartyHeaderCode:39595512ace63e6a99-87915737%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'fbda2ec77776cc4fd2398b5bc627e22867c569fd' => 
    array (
      0 => 'D:\\xampp\\htdocs\\admin\\modules\\news\\news.tpl',
      1 => 1427287510,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '39595512ace63e6a99-87915737',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_5512ace640dba1_98239015',
  'variables' => 
  array (
    'list_menu' => 0,
    'list_form' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5512ace640dba1_98239015')) {function content_5512ace640dba1_98239015($_smarty_tpl) {?>
<script src="/admin/assets/plugins/jQuery/jquery-2.1.1.min.js"></script>
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
                        <div class="col-sm-5 col-md-2">
                            <div class="user-left">
                                <div class="left">
                                    <h4>MENU</h4>
                                    <hr>
                                    <div class="panel-body panel-scroll height-600">
                                        <?php echo $_smarty_tpl->tpl_vars['list_menu']->value;?>

                                    </div>
                                </div>
                                <table class="table table-condensed table-hover">

                                </table>

                            </div>
                        </div>
                        <div class="col-sm-7 col-md-10">
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
                                    <?php echo $_smarty_tpl->tpl_vars['list_form']->value;?>

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
</div><?php }} ?>
