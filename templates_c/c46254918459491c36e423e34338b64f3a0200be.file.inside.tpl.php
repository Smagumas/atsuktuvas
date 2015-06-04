<?php /* Smarty version Smarty-3.1.18, created on 2015-03-25 14:58:05
         compiled from "templates\inside.tpl" */ ?>
<?php /*%%SmartyHeaderCode:170305457dcdd418ae1-88724276%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c46254918459491c36e423e34338b64f3a0200be' => 
    array (
      0 => 'templates\\inside.tpl',
      1 => 1427291878,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '170305457dcdd418ae1-88724276',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_5457dcdd489f72_37854894',
  'variables' => 
  array (
    'h1' => 0,
    'module' => 0,
    'Content' => 0,
    'template' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5457dcdd489f72_37854894')) {function content_5457dcdd489f72_37854894($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ('header.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>


        <div class="about">
            <h1><?php echo $_smarty_tpl->tpl_vars['h1']->value;?>
</h1>
            <?php if ($_smarty_tpl->tpl_vars['module']->value=='main') {?>
                <?php echo $_smarty_tpl->tpl_vars['Content']->value;?>

            <?php } else { ?>
                <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['template']->value, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

            <?php }?>


        </div><!--/#about-->


<?php echo $_smarty_tpl->getSubTemplate ('bottom.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }} ?>
