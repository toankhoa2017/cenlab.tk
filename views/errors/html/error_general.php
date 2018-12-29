<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title></title>
<!-- Tell the browser to be responsive to screen width -->
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<!-- Bootstrap 3.3.6 -->
<link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
<!-- Font Awesome -->
<link rel="stylesheet" href="/assets/bootstrap/css/font-awesome.min.css">
<!-- Ionicons -->
<link rel="stylesheet" href="/assets/bootstrap/css/ionicons.min.css">
<!-- Theme style -->
<link rel="stylesheet" href="/assets/dist/css/AdminLTE.min.css">
<!-- AdminLTE Skins. Choose a skin from the css/skins folder instead of downloading all of them to reduce the load. -->
<link rel="stylesheet" href="/assets/dist/css/skins/_all-skins.min.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="/assets/dist/js/html5shiv.min.js"></script>
<script src="/assets/dist/js/respond.min.js"></script>
<![endif]-->
</head>
<body class="hold-transition skin-blue sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">
<header class="main-header">
<!-- Logo -->
<a href="#" class="logo"><span class="logo-lg"><b>One</b>Corp</span></a>
<!-- Header Navbar: style can be found in header.less -->
<nav class="navbar navbar-static-top">
<!-- Sidebar toggle button-->
<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
    <!-- Co the de cac hidden value o day -->
</a>
<div class="navbar-custom-menu">
<ul class="nav navbar-nav"></ul>
</div>
</nav>
</header>
<!-- sidebar: style can be found in sidebar.less -->
<aside class="main-sidebar">
<section class="sidebar">
    <!-- user panel -->
    <div class="user-panel">
    <div class="pull-left image"><img src="/assets/dist/img/user9-160x160.jpg" class="img-circle" alt="User Image"></div>
    <div class="pull-left info">
    <p>Virus Bao</p>
    <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
    </div>
    </div>
    <!-- /user panel -->
    <!-- search form -->
    <form action="#" method="get" class="sidebar-form">
    <div class="input-group">
    <input type="text" name="q" class="form-control" placeholder="Search...">
    <span class="input-group-btn"><button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button></span>
    </div>
    </form>
    <!-- /search form -->
    
    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu">
    <li class="header">MENU QUẢN LÝ</li>
    <li class="treeview">
        <a href="#"><i class="fa fa-th text-blue"></i><span>Quản trị</span><i class="fa fa-angle-left pull-right"></i></a>
        <ul class="treeview-menu">
        <li><a href="#"><i class="fa fa-circle-o text-blue"></i>Danh sách người dùng</a></li>
        <li><a href="#"><i class="fa fa-circle-o text-blue"></i>Danh sách nhóm</a></li>
        <li><a href="#"><i class="fa fa-circle-o text-blue"></i>Danh sách module</a></li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#"><i class="fa fa-dashboard text-orange"></i><span>Quản lý thẻ</span><i class="fa fa-angle-left pull-right"></i></a>
        <ul class="treeview-menu">
        <li><a href="#"><i class="fa fa-circle-o text-orange"></i>Danh sách thẻ</a></li>
        <li><a href="#"><i class="fa fa-circle-o text-orange"></i>Tạo thẻ mới</a></li>
        <li><a href="#"><i class="fa fa-circle-o text-orange"></i>Kích hoạt lô thẻ</a></li>
        <li><a href="#"><i class="fa fa-circle-o text-orange"></i>Gán thẻ</a></li>
        </ul>
    </li>
    <li><a href="#"><i class="fa fa-user"></i><span>Quản lý thành viên</span></a></li>
    <li class="header">MENU TÀI CHÍNH</li>
    <li><a href="#"><i class="fa fa-folder text-green"></i><span>Thông tin lý ví online</span></a></li>
    <li><a href="#"><i class="fa fa-share text-yellow"></i><span>Thông tin cashback</span></a></li>
    <li><a href="#"><i class="fa fa-envelope text-red"></i><span>Thông tin checkout</span></a></li>
    <li><a href="#"><i class="fa fa-pie-chart text-blue"></i><span>Thống kê</span><small class="label pull-right bg-green">new</small></a></li>
    </ul>
    <!-- /sidebar menu: : style can be found in sidebar.less -->
</section>
</aside>
<div class="content-wrapper">
    <section class="content-header"><h1><?php echo $heading; ?></h1></section>
    <section class="content">
    <div class="error-page">
    <h2 class="headline text-yellow">500</h2>
    <div class="error-content" style="padding-top:5px;">
    <h3><i class="fa fa-warning text-yellow"></i> <?php echo $heading; ?></h3>
    <p><?php echo $message; ?></p>
    <form class="search-form">
    <div class="input-group">
    <input type="text" name="search" class="form-control" placeholder="Search">
    <div class="input-group-btn"><button type="submit" name="submit" class="btn btn-warning btn-flat"><i class="fa fa-search"></i></button></div>
    </div>
    </form>
    </div>
    </div>
    </section>
</div>
<footer class="main-footer">
<div class="pull-right hidden-xs"><b>Version</b> 2.3.3</div>
<strong>Copyright &copy; 2016-2017 <a href="#">Dinh Hoai Bao</a>.</strong> All rights reserved.
</footer>
<!-- jQuery 2.2.0 -->
<script src="/assets/plugins/jQuery/jQuery-2.2.0.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="/assets/bootstrap/js/bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="/assets/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="/assets/plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="/assets/dist/js/app.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="/assets/dist/js/demo.js"></script>
</body>
</html>