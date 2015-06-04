<?php /* Smarty version Smarty-3.1.18, created on 2014-10-12 14:35:11
         compiled from "C:\xampp\htdocs\admin\modules\settings\settings.tpl" */ ?>
<?php /*%%SmartyHeaderCode:21468543592e282b3e4-98344988%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'bfda3807dbafa5e839fc15038779a45aef0ac975' => 
    array (
      0 => 'C:\\xampp\\htdocs\\admin\\modules\\settings\\settings.tpl',
      1 => 1413117310,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '21468543592e282b3e4-98344988',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_543592e282b3e5_72012646',
  'variables' => 
  array (
    'list_menu' => 0,
    'list_form' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_543592e282b3e5_72012646')) {function content_543592e282b3e5_72012646($_smarty_tpl) {?>


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
