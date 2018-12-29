{* Extend our master template *}
{extends file="master.tpl"}
{block name=css}
    <style>
        .updategroup {
           display: none;
       }
       .editable-label-text {
           width: 100%;
       }
    </style>
{/block}
{block name=script}
<script type="text/javascript">

$('#table').on('click','.editgroup', function(){
	var id = $(this).parents('tr').find('div.gname').data('id');
	var $lbl1 =  $(this).parents('tr').find('div.gname a'), value1 = $lbl1.text();
    var $txt1 = $('<input type="text" id="txtgname_'+id+'" class="editable-label-text" value="'+value1+'" />');
    $lbl1.replaceWith($txt1);
	
	var $lbl2 =  $(this).parents('tr').find('div.gorder'), value2 = $lbl2.text();
    var $txt2 = $('<input type="text" id="txtgorder_'+id+'" class="editable-label-text" value="'+value2+'" />');
    $lbl2.replaceWith($txt2);
	
	$(this).parents('tr').find('button.updategroup').attr("style", "display:inline");
});
$('#table').on('click','.updategroup', function(){
	var id = this.value;
	var gname = $(this).parents('tr').find('#txtgname_'+id).val();
	var gorder = $(this).parents('tr').find('#txtgorder_'+id).val();
	var $lbl1 = $('<div class="gname" data-id="'+id+'"><a href="{site_url()}admin/module/detail?id='+id+'">'+gname+'</a></div>');
	var $lbl2 = $('<div class="gorder" data-id="'+id+'">'+gorder+'</div>');
	$(this).parents('tr').find('#txtgname_'+id).replaceWith($lbl1);
	$(this).parents('tr').find('#txtgorder_'+id).replaceWith($lbl2);
	$(this).parents('tr').find('button.updategroup').attr("style", "display:none");
	  $.ajax({
		url: "{site_url()}admin/module/updategroup",
		type: "POST",
		data: { id: id, gname: gname, gorder : gorder },
		dataType: "JSON",
		success: function(data) {
			if(data.code == "100") {
				//reload_table();
				//alert(data.status);               
			}
		},
		error: function (jqXHR, textStatus, errorThrown) {
			alert('Error adding / update data');
		}
	});

});
</script>
{/block}
{block name=body}
<div class="main-content">
<div class="main-content-inner">
<!--PATH BEGINS-->
<div class="breadcrumbs ace-save-state" id="breadcrumbs">
<ul class="breadcrumb">
<li><i class="ace-icon fa fa-home home-icon"></i><a href="#">Quản lý nhóm module</a></li>
<li class="active">Danh sách nhóm</li>
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
<h4 class="header smaller lighter blue">Nhóm các modules</h4>
<div class="clearfix">
<div class="pull-left tableTools-container"></div>
</div>
<table id="table" class="table table-striped table-bordered table-hover">
<thead>
<tr>
    <th>Nhóm</th>
    <th>Thứ Tự</th>
    <th></th>
</tr>
</thead>
<tbody>
{if $groups}
    {foreach from=$groups item=group}
    <tr>
        <td>
        	<div class="gname" data-id="{$group->id}"><a href="{site_url()}admin/module/detail?id={$group->id}">{$group->name}</a></div>
        </td>
        <td>
        	<div class="gorder" data-id="{$group->id}">{$group->thutu}</div>   	
        </td>
        <td>
        	<button class="btn btn-xs btn-info editgroup"><i class="ace-icon fa fa-pencil smaller"></i></button>
            <button class="btn btn-warning btn-xs btnumail updategroup" value="{$group->id}"><i class="ace-icon fa fa-floppy-o smaller"></i></button>
        </td>
    </tr>
    {/foreach}
{else}
    <tr><td style="text-align:center;" colspan="3">Không có dữ liệu</td>
{/if}
</tbody>
</table>
<!-- PAGE CONTENT ENDS -->
</div>
</div>
</div>
</div>
</div>
{/block}