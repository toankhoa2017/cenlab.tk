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
<!--<link rel="stylesheet" href="{$assets_path}css/fonts.googleapis.com.css" />-->
<!-- Google fonts -->
<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
<!-- Default setting print -->
<style type="text/css">
    @page { size: landscape; }
</style>
<!-- inline styles related to this page -->
{block name=css}{/block}
</head>
<body> <!--onload="print()"-->
{block name=body}{/block}
<!-- basic scripts -->
<!--[if !IE]> -->
<script src="{$assets_path}js/jquery-2.1.4.min.js"></script>
<!-- <![endif]-->
<!--[if IE]>
    <script src="{$assets_path}js/jquery-1.11.3.min.js"></script>
<![endif]-->
<script src="{$assets_path}js/bootstrap.min.js"></script>
{block name=script}{/block}
</body>
</html>