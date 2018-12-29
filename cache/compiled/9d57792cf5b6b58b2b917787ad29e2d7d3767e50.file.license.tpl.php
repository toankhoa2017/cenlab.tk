<?php /* Smarty version Smarty-3.1.13, compiled from "W:\domains\cenlab.tk\modules\license\views\license.tpl" */ ?>
<?php /*%%SmartyHeaderCode:273035c24b1901b8b41-84600078%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9d57792cf5b6b58b2b917787ad29e2d7d3767e50' => 
    array (
      0 => 'W:\\domains\\cenlab.tk\\modules\\license\\views\\license.tpl',
      1 => 1545721410,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '273035c24b1901b8b41-84600078',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'message' => 0,
    'contact' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5c24b1903be7e3_15668334',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c24b1903be7e3_15668334')) {function content_5c24b1903be7e3_15668334($_smarty_tpl) {?><!DOCTYPE html>
<html lang="en">
<head>
<title>Welcome on board</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
<link rel="icon" type="image/png" href="/assets/license/images/icons/favicon.ico"/>
<!--===============================================================================================-->
<link rel="stylesheet" type="text/css" href="/assets/license/css/util.css">
<link rel="stylesheet" type="text/css" href="/assets/license/css/main.css">
<!--===============================================================================================-->
</head>
<body>
<div class="bg-img1 size1 flex-w flex-c-m p-t-55 p-b-55 p-l-15 p-r-15" style="background-image: url('/assets/license/images/bg01.jpg');">
    <div class="wsize1 bor1 bg1 p-t-175 p-b-45 p-l-15 p-r-15 respon1">
        <p class="txt-center m1-txt1 p-t-33 p-b-68">
            <?php echo $_smarty_tpl->tpl_vars['message']->value;?>
,<br><?php echo $_smarty_tpl->tpl_vars['contact']->value;?>

        </p>
        <form class="flex-w flex-c-m contact100-form validate-form p-t-55">
            <div class="wrap-input100 validate-input where1" data-validate = "Email is required: ex@abc.xyz">
                <input class="s1-txt2 placeholder0 input100" type="text" name="email" placeholder="Your Email">
                <span class="focus-input100"></span>
            </div>
            <button class="flex-c-m s1-txt3 size3 how-btn trans-04 where1">Get Notified</button>
        </form>
        <p class="s1-txt4 txt-center p-t-10">I promise to <span class="bor2">never</span> spam</p>
    </div>
</div>
<!--===============================================================================================-->
<script src="/assets/license/js/main.js"></script>
</body>
</html><?php }} ?>