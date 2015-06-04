<?php /* Smarty version Smarty-3.1.18, created on 2014-12-01 09:55:34
         compiled from ".\templates\header.tpl" */ ?>
<?php /*%%SmartyHeaderCode:148375410a1242435b1-16965198%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '10e0737838b4a574ef135d0c601e7b602cfaf37a' => 
    array (
      0 => '.\\templates\\header.tpl',
      1 => 1417424132,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '148375410a1242435b1-16965198',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_5410a124247434_64732667',
  'variables' => 
  array (
    'tr_title' => 0,
    'page_title' => 0,
    'languages' => 0,
    'top_menu' => 0,
    'bottom_menu' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5410a124247434_64732667')) {function content_5410a124247434_64732667($_smarty_tpl) {?><HTML>
<HEAD>
    <meta charset="utf-8">
    <TITLE><?php echo $_smarty_tpl->tpl_vars['tr_title']->value;?>
 - <?php echo $_smarty_tpl->tpl_vars['page_title']->value;?>
</TITLE>
</HEAD>
<header>
    <?php echo $_smarty_tpl->tpl_vars['languages']->value;?>

    <?php echo $_smarty_tpl->tpl_vars['top_menu']->value;?>

    <?php echo $_smarty_tpl->tpl_vars['bottom_menu']->value;?>

</header><?php }} ?>
