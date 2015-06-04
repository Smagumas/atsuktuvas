<?php /* Smarty version Smarty-3.1.18, created on 2015-03-25 14:57:49
         compiled from "D:\xampp\htdocs\functions\pages\news\news.tpl" */ ?>
<?php /*%%SmartyHeaderCode:206405512beddbde071-53048651%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '35fc8b822827b9916ea5765077cd2f203bb72883' => 
    array (
      0 => 'D:\\xampp\\htdocs\\functions\\pages\\news\\news.tpl',
      1 => 1427291397,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '206405512beddbde071-53048651',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'news' => 0,
    'newItem' => 0,
    'baseLink' => 0,
    'tr_read_more' => 0,
    'pages' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_5512beddbfd472_79981354',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5512beddbfd472_79981354')) {function content_5512beddbfd472_79981354($_smarty_tpl) {?><div class="newsa no-margin">
    <h1>NAUJIENOS TEST</h1>
    <?php  $_smarty_tpl->tpl_vars['newItem'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['newItem']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['news']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['newItem']->key => $_smarty_tpl->tpl_vars['newItem']->value) {
$_smarty_tpl->tpl_vars['newItem']->_loop = true;
?>
        <div class="line5"></div>
        <div>
            <span><?php echo $_smarty_tpl->tpl_vars['newItem']->value['Date_Created'];?>
 |</span> <strong><a href="<?php echo $_smarty_tpl->tpl_vars['baseLink']->value;?>
/<?php echo $_smarty_tpl->tpl_vars['newItem']->value['Alias'];?>
">
                    <?php echo $_smarty_tpl->tpl_vars['newItem']->value['Title'];?>
</a></strong>
        </div>
        <br>
        <img src="<?php echo $_smarty_tpl->tpl_vars['newItem']->value['Image'];?>
" width="200" style="float: left;margin-right: 10px;" />
        <?php echo $_smarty_tpl->tpl_vars['newItem']->value['Short'];?>
<p><a href="<?php echo $_smarty_tpl->tpl_vars['baseLink']->value;?>
/<?php echo $_smarty_tpl->tpl_vars['newItem']->value['Alias'];?>
"><?php echo $_smarty_tpl->tpl_vars['tr_read_more']->value;?>
</a>
        </p>
        <div style="clear: both;"></div>
    <?php } ?>
    <div style="clear: both;"></div>
    <div class="pages"><?php echo $_smarty_tpl->tpl_vars['pages']->value;?>
</div>
</div><!--/#news--><?php }} ?>
