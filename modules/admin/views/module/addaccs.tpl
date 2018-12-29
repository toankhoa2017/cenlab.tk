{* Extend our master template *}
{extends file="master.tpl"}
{block name=css}
<!-- page specific plugin styles -->
<link rel="stylesheet" href="{$assets_path}css/bootstrap-duallistbox.min.css" />
<link rel="stylesheet" href="{$assets_path}css/bootstrap-multiselect.min.css" />
<link rel="stylesheet" href="{$assets_path}css/select2.min.css" />
{/block}
{block name=script}
<!-- page specific plugin scripts -->
<script src="{$assets_path}js/jquery.bootstrap-duallistbox.min.js"></script>
<script src="{$assets_path}js/jquery.raty.min.js"></script>
<script src="{$assets_path}js/bootstrap-multiselect.min.js"></script>
<script src="{$assets_path}js/select2.min.js"></script>
<script src="{$assets_path}js/jquery-typeahead.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('select[name="dualacc[]"]').bootstrapDualListbox();
});
</script>
{/block}
{block name=body}
<div class="main-content">
<div class="main-content-inner">
<!--PATH BEGINS-->
<div class="breadcrumbs ace-save-state" id="breadcrumbs">
<ul class="breadcrumb">
<li><i class="ace-icon fa fa-home home-icon"></i><a href="#">Quản lý nhóm</a></li>
<li><a href="{site_url()}admin/module/detail?id={$group->id}">Chi tiết nhóm</a></li>
<li class="active">Thêm account vào nhóm</li>
</ul>
<div class="nav-search" id="nav-search">
<form class="form-search">
<span class="input-icon"><input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" /><i class="ace-icon fa fa-search nav-search-icon"></i></span>
</form>
</div>
</div>
<!--PATH ENDS-->
<div class="page-content">
<div class="row">
<div class="col-xs-12">
<!-- PAGE CONTENT BEGINS -->
<h3 class="header smaller lighter blue">{$group->name}</h3>
<div class="clearfix">
<div class="pull-left tableTools-container"></div>
</div>
<form class="form-horizontal" role="form" id="validation-form" action="" method="post">
<input type="hidden" id="isSent" name="isSent" value="OK" />
<div class="form-group">
<div class="col-sm-12">
<select multiple="multiple" size="10" name="dualacc[]" id="dualacc">
   {html_options options=$option_account}
</select>
<div class="hr hr-16 hr-dotted"></div>
</div>
</div>
<div class="clearfix form-actions">
<div class="col-md-offset-3 col-md-9">
<button class="btn btn-xs btn-info" type="submit">
<i class="ace-icon fa fa-check bigger-110"></i>
Submit
</button>
<button class="btn btn-xs" type="reset">
<i class="ace-icon fa fa-undo bigger-110"></i>
Reset
</button>
</div>
</div>
</form>
<!-- PAGE CONTENT ENDS -->
</div>
</div>
</div>
</div>
</div>
{/block}