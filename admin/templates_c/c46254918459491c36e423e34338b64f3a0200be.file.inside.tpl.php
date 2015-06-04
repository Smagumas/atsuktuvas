<?php /* Smarty version Smarty-3.1.18, created on 2014-11-24 18:41:51
         compiled from "templates\inside.tpl" */ ?>
<?php /*%%SmartyHeaderCode:22774541206b8bdc130-04876215%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c46254918459491c36e423e34338b64f3a0200be' => 
    array (
      0 => 'templates\\inside.tpl',
      1 => 1412881248,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '22774541206b8bdc130-04876215',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_541206b8c0edc0_88827266',
  'variables' => 
  array (
    'module_title' => 0,
    'template' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_541206b8c0edc0_88827266')) {function content_541206b8c0edc0_88827266($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ('templates/top.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ('templates/sidepanel.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>


<!-- start: MAIN CONTAINER -->
<div class="main-container inner">
    <!-- start: PAGE -->
    <div class="main-content">
        <div class="container">
            <!-- start: PAGE HEADER -->
            <!-- start: TOOLBAR -->
            <div class="toolbar row">
                <div class="col-sm-6 hidden-xs">
                    <div class="page-header">
                        <h1><?php echo $_smarty_tpl->tpl_vars['module_title']->value;?>
</h1>
                    </div>
                </div>
                <div class="col-sm-6 col-xs-12">
                    <div class="toolbar-tools pull-right">
                        <!-- start: TOP NAVIGATION MENU -->
                        <ul class="nav navbar-right">
                            <!-- start: TO-DO DROPDOWN -->
                            <li class="menu-search">
                                <a href="#">
                                    <i class="fa fa-search"></i> SEARCH
                                </a>
                                <!-- start: SEARCH POPOVER -->
                                <div class="popover bottom search-box transition-all">
                                    <div class="arrow"></div>
                                    <div class="popover-content">
                                        <!-- start: SEARCH FORM -->
                                        <form class="" id="searchform" action="#">
                                            <div class="input-group">
                                                <input type="text" class="form-control" placeholder="Search">
															<span class="input-group-btn">
																<button class="btn btn-main-color btn-squared" type="button">
                                                                    <i class="fa fa-search"></i>
                                                                </button> </span>
                                            </div>
                                        </form>
                                        <!-- end: SEARCH FORM -->
                                    </div>
                                </div>
                                <!-- end: SEARCH POPOVER -->
                            </li>
                        </ul>
                        <!-- end: TOP NAVIGATION MENU -->
                    </div>
                </div>
            </div>
            <!-- end: TOOLBAR -->
            <!-- end: PAGE HEADER -->
            <!-- start: PAGE CONTENT -->
            <div class="row">
                <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['template']->value, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

            </div>
            <!-- end: PAGE CONTENT-->
        </div>
    </div>
    <!-- end: PAGE -->
</div>
<!-- end: MAIN CONTAINER -->

<?php echo $_smarty_tpl->getSubTemplate ('templates/bot.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }} ?>
