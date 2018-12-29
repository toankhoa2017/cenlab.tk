<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta charset="utf-8" />
<title>Tools</title>
<meta name="description" content="" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
<!-- bootstrap & fontawesome -->
<link rel="stylesheet" href="{$assets_path}css/bootstrap.min.css" />
<link rel="stylesheet" href="/assets/fonts/font-awesome.css" />
<!-- text fonts -->
<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
<!-- TuanLM: chosen style -->
<link rel="stylesheet" href="{$assets_path}css/chosen.min.css" />
<!-- TuanLM: JQuery UI style -->
<link rel="stylesheet" href="{$assets_path}css/jquery-ui.min.css" />
<!-- ace styles -->
<link rel="stylesheet" href="{$assets_path}css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style" />
<!--[if lte IE 9]>
<link rel="stylesheet" href="{$assets_path}css/ace-part2.min.css" class="ace-main-stylesheet" />
<![endif]-->
<link rel="stylesheet" href="{$assets_path}css/ace-skins.min.css" />
<link rel="stylesheet" href="{$assets_path}css/ace-rtl.min.css" />
<!--[if lte IE 9]>
<link rel="stylesheet" href="{$assets_path}css/ace-ie.min.css" />
<![endif]-->
<!-- inline styles related to this page -->
{block name=css}{/block}
<!-- ace settings handler -->
<script src="{$assets_path}js/ace-extra.min.js"></script>
<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->
<!--[if lte IE 8]>
<script src="{$assets_path}js/html5shiv.min.js"></script>
<script src="{$assets_path}js/respond.min.js"></script>
<![endif]-->
</head>
<body class="no-skin">
<div class="main-container ace-save-state" id="main-container">
{block name=body}{/block}
</div>

<!-- basic scripts -->
<!--[if !IE]> -->
<script src="{$assets_path}js/jquery-2.1.4.min.js"></script>
<!-- <![endif]-->
<!--[if IE]>
    <script src="{$assets_path}js/jquery-1.11.3.min.js"></script>
<![endif]-->
<script type="text/javascript">
    if ('ontouchstart' in document.documentElement) document.write("<script src='{$assets_path}js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
</script>
<script src="{$assets_path}js/bootstrap.min.js"></script>
<!-- ace scripts -->
<script src="{$assets_path}js/ace-elements.min.js"></script>
<script src="{$assets_path}js/ace.min.js"></script>
<!-- inline scripts related to this page -->
<script type="text/javascript">
function changePWD() {
    $('#formChangePWD')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_changepwd').modal('show'); // show bootstrap modal
    $('.modal-title').text('Đổi mật khẩu'); // Set Title to Bootstrap modal title
}
function resetPWD(id, code) {
    $.ajax({
        url : "{site_url()}nhansu/resetpwd",
        type: "POST",
        data: {
            id: id,
            code: code
        },
        dataType: "JSON",
        success: function(response) {
           $('#matkhautam').text(response.pwd);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('Khong the cap quyen');
        }
    });    
}
function changeLanguage(lang) {
    $.post("{site_url()}admin/setLang/" + lang, {},
    function(response) {
        location.reload();
    });
}
function listmods(id) {
    $('#listMods').html("<center><img src='{$assets_path}images/loadingAnimation.gif' /></center>");
    $.get('{site_url()}nhansu/listmods?id='+ id, {}, 
        function(response) {
            $('#listMods').html(response);
        }
    );
}
function setpermission() {
    $.ajax({
        url : "{site_url()}nhansu/listmods/setpermission",
        type: "POST",
        data: $('#frmAdmin').serialize(),
        dataType: "JSON",
        success: function(data) {
           if (data.status == 'denied') document.location.href = '{site_url()}system/denied?w=update';
           else {
               listmods(data.nhansu_code);
           }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('Khong the cap quyen');
        }
    });
}
</script>
{block name=script}{/block}
</body>
</html>