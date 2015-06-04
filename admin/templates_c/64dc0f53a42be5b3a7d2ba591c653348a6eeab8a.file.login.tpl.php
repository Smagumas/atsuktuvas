<?php /* Smarty version Smarty-3.1.18, created on 2014-11-24 18:39:55
         compiled from "templates\login.tpl" */ ?>
<?php /*%%SmartyHeaderCode:94915411fbd2219936-01743614%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '64dc0f53a42be5b3a7d2ba591c653348a6eeab8a' => 
    array (
      0 => 'templates\\login.tpl',
      1 => 1410632538,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '94915411fbd2219936-01743614',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_5411fbd2240a45_54052437',
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5411fbd2240a45_54052437')) {function content_5411fbd2240a45_54052437($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ('templates/top.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<body class="login">
<div class="main-login col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4">
    <div class="logo">
        <img src="assets/images/logo.png">
    </div>
    <!-- start: LOGIN BOX -->
    <div class="box-login">
        <h3>Prisijungimas</h3>
        <p>Įveskite prisijungimo vardą ir slaptažodį, kad prisijungti</p>
        <form class="form-login" method="post" action="">
            <div class="errorHandler alert alert-danger no-display">
                <i class="fa fa-remove-sign"></i> Ivyko klaidų. Patikrinkite duomenis
            </div>
            <fieldset>
                <div class="form-group">
							<span class="input-icon">
								<input type="text" class="form-control" name="username" placeholder="Username">
								<i class="fa fa-user"></i> </span>
                </div>
                <div class="form-group form-actions">
							<span class="input-icon">
								<input type="password" class="form-control password" name="password" placeholder="Password">
								<i class="fa fa-lock"></i> </span>
                </div>
                <div class="form-actions">
                    <label for="remember" class="checkbox-inline">
                        <input type="hidden" name="remember" value="off">
                        <input type="checkbox" class="grey remember" id="remember" name="remember">
                        Likti prisijungus
                    </label>
                    <button type="submit" name="admin_form" class="btn btn-green pull-right">
                        Prisijungti <i class="fa fa-arrow-circle-right"></i>
                    </button>
                </div>
            </fieldset>
        </form>
        <!-- start: COPYRIGHT -->
        <div class="copyright">
            2014 &copy; Atsuktuvas.
        </div>
        <!-- end: COPYRIGHT -->
    </div>
    <!-- end: LOGIN BOX -->
</div>
<!-- start: MAIN JAVASCRIPTS -->
<!--[if lt IE 9]>
<script src="assets/plugins/respond.min.js"></script>
<script src="assets/plugins/excanvas.min.js"></script>
<script type="text/javascript" src="assets/plugins/jQuery/jquery-1.11.1.min.js"></script>
<![endif]-->
<!--[if gte IE 9]><!-->
<script src="assets/plugins/jQuery/jquery-2.1.1.min.js"></script>
<!--<![endif]-->
<script src="assets/plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js"></script>
<script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/plugins/iCheck/jquery.icheck.min.js"></script>
<script src="assets/plugins/jquery.transit/jquery.transit.js"></script>
<script src="assets/js/main.js"></script>
<!-- end: MAIN JAVASCRIPTS -->
<!-- start: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
<script src="assets/plugins/jquery-validation/dist/jquery.validate.min.js"></script>
<script src="assets/js/login.js"></script>
<!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
<script>
    jQuery(document).ready(function () {
        Main.init();
        Login.init();
    });
</script>
</body>
<!-- end: BODY -->
</html><?php }} ?>
