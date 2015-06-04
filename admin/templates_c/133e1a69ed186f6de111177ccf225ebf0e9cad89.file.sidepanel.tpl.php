<?php /* Smarty version Smarty-3.1.18, created on 2014-11-24 18:41:52
         compiled from "templates\sidepanel.tpl" */ ?>
<?php /*%%SmartyHeaderCode:198054147ded492940-28405713%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '133e1a69ed186f6de111177ccf225ebf0e9cad89' => 
    array (
      0 => 'templates\\sidepanel.tpl',
      1 => 1413044200,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '198054147ded492940-28405713',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_54147ded49e4c4_89591250',
  'variables' => 
  array (
    'menu' => 0,
    'item' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_54147ded49e4c4_89591250')) {function content_54147ded49e4c4_89591250($_smarty_tpl) {?><!-- start: PAGESLIDE LEFT -->
<a class="closedbar inner hidden-sm hidden-xs" href="#">
</a>
<nav id="pageslide-left" class="pageslide inner">
    <div class="navbar-content">
        <!-- start: SIDEBAR -->
        <div class="main-navigation left-wrapper transition-left">
            <div class="navigation-toggler hidden-sm hidden-xs">
                <a href="#main-navbar" class="sb-toggle-left">
                </a>
            </div>
            <!-- start: MAIN NAVIGATION MENU -->
            <ul class="main-navigation-menu">
                <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['menu']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
?>
                    <li>
                        <a href="/admin/<?php echo $_smarty_tpl->tpl_vars['item']->value['Module'];?>
"><i class="fa fa-cogs"></i>
                            <span class="title"><?php echo $_smarty_tpl->tpl_vars['item']->value['Title'];?>
</span></a>
                    </li>
                <?php } ?>
            </ul>
            <!-- end: MAIN NAVIGATION MENU -->
        </div>
        <!-- end: SIDEBAR -->
    </div>
    <div class="slide-tools">
        <div class="col-xs-6 text-right no-padding">
            <a class="btn btn-sm log-out text-right" href="?action=logout">
                <i class="fa fa-power-off"></i><a href="?action=logout">Atsijungti</a>
            </a>
        </div>
    </div>
</nav>
<!-- end: PAGESLIDE LEFT --><?php }} ?>
