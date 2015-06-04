<?php /* Smarty version Smarty-3.1.18, created on 2015-04-25 13:05:09
         compiled from "D:\xampp\htdocs\functions\pages\registration\registration.tpl" */ ?>
<?php /*%%SmartyHeaderCode:13749553a60ec8811d7-40187797%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a30010228b387c933ef25a20444afbdbd7e3f5ec' => 
    array (
      0 => 'D:\\xampp\\htdocs\\functions\\pages\\registration\\registration.tpl',
      1 => 1429948219,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '13749553a60ec8811d7-40187797',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_553a60ec89c756_78942076',
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_553a60ec89c756_78942076')) {function content_553a60ec89c756_78942076($_smarty_tpl) {?><div>
        <form action="?action=register" method="post">
        Username: <input type="text" name="username" size="30"><br>
        Name: <input type="text" name="name" size="30"><br>
        Password: <input type="password" name="password" size="30"><br>
        Confirm your password: <input type="password" name="password_conf" size="30"><br>
        Email: <input type="text" name="email" size="30"><br>
        <input type="submit" value="Register">
        </form>

        <form action="?action=login" method="post">
        Username: <input type="text" name="username" size="30"><br>
        Password: <input type="password" name="password" size="30"><br>
        <input type="submit" value="Login">
    </form>
</div><?php }} ?>
