{* Extend our master template *}
{extends file="master.tpl"}
{block name=css}
<style>
.updatename {
   display: none;
}
.editable-label-text {
   width: 80%;
}
.updatelink {
        display: none;
}
.updateorder {
        display: none;
}
#table td div { width: 80%; margin-right: 0px;}
</style>
{/block}
{block name=script}
<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title"></h3>
            </div>
            <form id="form" method="post">
            <input type="hidden" value="OK" id="isSent" name="isSent" />
            <input type="hidden" value="{$group.GROUP_ID}" id="group" name="group" />
            <div class="modal-body form">
                <div class="form-group">
                    <label>Module name:</label>
                    <input class="form-control" type="text" id="name" name="name" />
                </div>
                <div class="form-group">
                    <label>Module define:</label>
                    <input class="form-control" type="text" id="define" name="define" />
                </div>
                <div class="form-group">
                    <label>Module link:</label>
                    <input class="form-control" type="text" id="link" name="link" />
                </div>
                <div class="form-group">
                    <label>Module order:</label>
                    <input class="form-control" type="text" id="order" name="order" />
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="_savemod()" class="btn btn-xs btn-primary">Submit</button>
                <button type="button" class="btn btn-xs btn-danger" data-dismiss="modal">Cancel</button>
            </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->
<script type="text/javascript">
$('#table').on('click','.mname', function(){
    $(this).parents('tr').find('button.updatename').attr("style", "display:inline");
    var id = $(this).data('id');
    var $lbl1 =  $(this), value = $lbl1.text();
    $txt = $('<input type="text" id="txtmname_'+id+'" class="editable-label-text" value="'+value+'" />');
    $lbl1.replaceWith($txt);
});
$('#table').on('click','.mlink', function(){
    $(this).parents('tr').find('button.updatelink').attr("style", "display:inline");
    var id = $(this).data('id');
    var $lbl2 =  $(this), value = $lbl2.text();
    $txt = $('<input type="text" id="txtmlink_'+id+'" class="editable-label-text" value="'+value+'" />');
    $lbl2.replaceWith($txt);
});
$('#table').on('click','.morder', function(){
    $(this).parents('tr').find('button.updateorder').attr("style", "display:inline");
    var id = $(this).data('id');
    var $lbl3 =  $(this), value = $lbl3.text();
    $txt = $('<input type="text" id="txtmorder_'+id+'" class="editable-label-text" value="'+value+'" />');
    $lbl3.replaceWith($txt);
});
$('#table').on('click','button.updatename', function(){
    var mid = this.value;
    var mname = $('#txtmname_'+mid).val();
    var $lbl = $('<div class="mname" data-id="'+mid+'">'+mname+'</div>');
    $(this).parents('tr').find('#txtmname_'+mid).replaceWith($lbl);
    $(this).attr("style", "display:none");
    update(mid,'MOD_NAME', mname);
});
$('#table').on('click','button.updatelink', function(){
    var mid = this.value;
    var mlink = $('#txtmlink_'+mid).val();
    var $lbl = $('<div class="mlink" data-id="'+mid+'">'+mlink+'</div>');
    $(this).parents('tr').find('#txtmlink_'+mid).replaceWith($lbl);
    $(this).attr("style", "display:none");
    update(mid,'MOD_LINK', mlink); 	
});
$('#table').on('click','button.updateorder', function(){
    var mid = this.value;
    var morder = $('#txtmorder_'+mid).val();
    var $lbl = $('<div class="mlink" data-id="'+mid+'">'+morder+'</div>');
    $(this).parents('tr').find('#txtmorder_'+mid).replaceWith($lbl);
    $(this).attr("style", "display:none");
    update(mid,'MOD_ORDER', morder); 	
});
function _addmod() {
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Create new module'); // Set Title to Bootstrap modal title
}
function update(id1, column_name, value) {
    $.ajax({
        url: "{site_url()}system/updatemod",
        type: "POST",
        data: { id: id1, column_name: column_name, value: value },
        dataType: "JSON",
        success: function(data) {
           if(data.code == '100') {}
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('Error adding / update data xxxx');
        }
    });
}
function _savemod() {
    $.ajax({
        url : "{site_url()}system/addmod",
        type: "POST",
        data: $('#form').serialize(),
        dataType: "JSON",
        success: function(data) {
           //if success close modal and reload ajax table
           $('#modal_form').modal('hide');
           if (data.status == 'denied') document.location.href = '{site_url()}system/denied?w=update';
           else location.reload();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('Khong them duoc module');
        }
    });
}
$(document).ready(function() {
    $("#sidebar-group").addClass("active");
    $(".delmod").click(function() {
        if (confirm("Bạn có chắc xóa?")) {
            var mid = this.value;
            $.ajax({
                url : "{site_url()}system/delmod",
                type: "POST",
                data: {
                    gid: {$group.GROUP_ID},
                    mid: mid
                },
                dataType: "JSON",
                success: function(data) {
                    if (data.status == 'denied') document.location.href = '{site_url()}system/denied?w=update';
                    $('table tr#mod_'+ mid).remove();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Khong the cap nhat');
                }
            });
        }
    });
    $(".delacc").click(function() {
        if (confirm("Bạn có chắc xóa?")) {
            var aid = this.value;
            $.post("{site_url()}system/delacc", { gid: {$group.GROUP_ID}, aid: aid },
            function(response) {
                if (response.status == 'denied') document.location.href = '{site_url()}system/denied?w=update';
                $('table tr#acc_'+ aid).remove();
            });
        }
    });
});
function sethide(id){
    var giatri = "";
    if($("#_check"+id).attr('level')==="Y"){ giatri="N"; }else{ giatri="Y" };
    $.ajax({
        url : "{site_url()}system/sethide",
        type: "POST",
        data: {
            'MOD_ID': id,
            'MOD_HIDE': giatri
        },
        success: function(data) {
            if(data=='1'){ 
                $("#_check"+id).attr('level',giatri);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('Khong the cap nhat');
        }
    });
}
</script>
{/block}
{block name=body}
<div class="main-content">
<div class="main-content-inner">
<!--PATH BEGINS-->
<div class="breadcrumbs ace-save-state" id="breadcrumbs">
<ul class="breadcrumb">
<li><i class="ace-icon fa fa-home home-icon"></i><a href="{site_url()}system?project={$group.PROJECT_ID}">Quản lý nhóm</a></li>
<li class="active">chi tiết nhóm</li>
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
<h3>{$group.GROUP_NAME}</h3>
<h4 class="header smaller lighter blue">List modules</h4>
<div class="clearfix">
<div class="pull-left tableTools-container"></div>
</div>
<div style="margin-bottom:5px;"><a class="btn btn-xs btn-primary" href="javascript:void(-1)" onclick="_addmod()"><i class="ace-icon fa fa-plus"></i> Add new</a></div>
<table id="table" class="table table-striped table-bordered table-hover">
<thead>
<tr>
    <th>Name</th>
    <th>Define</th>
    <th>Link</th>
    <th>Value</th>
    <th>Hide</th>
    <th>Order</th>
    <th></th>
</tr>
</thead>
<tbody>
{if $listmod}
    {foreach from=$listmod item=m}
    <tr id="mod_{$m.MOD_ID}">
         <td >
            <div class="mname" data-id='{$m.MOD_ID}'>{$m.MOD_NAME}</div>
            <button class="btn btn-warning btn-xs btnumail updatename" value="{$m.MOD_ID}"><i class="ace-icon fa fa-floppy-o smaller"></i></button>
        </td>
        <td>{$m.MOD_DEFINE}</td>
        <td>
            <div class="mlink" data-id='{$m.MOD_ID}'>{$m.MOD_LINK}</div>
            <button class="btn btn-warning btn-xs btnumail updatelink" value="{$m.MOD_ID}"><i class="ace-icon fa fa-floppy-o smaller"></i></button>
        </td>
        <td>{$m.MOD_ID}</td>
        <td>
            <label>
                <input class="ace ace-switch ace-switch-3" {if $m.MOD_HIDE == 'Y'}checked{/if} type="checkbox">
                <span class="lbl" id="_check{$m.MOD_ID}" onclick="sethide('{$m.MOD_ID}')" level="{$m.MOD_HIDE}"></span>
            </label>
        </td>
        <td>
            <div class="morder" data-id='{$m.MOD_ID}'>{if $m.MOD_ORDER}{$m.MOD_ORDER}{else}0{/if}</div>
            <button class="btn btn-warning btn-xs btnumail updateorder" value="{$m.MOD_ID}"><i class="ace-icon fa fa-floppy-o smaller"></i></button>
        </td>
        <td>
            <button class="btn btn-xs btn-danger delmod" value="{$m.MOD_ID}"><i class="ace-icon fa fa-trash-o bigger-120"></i></button>
        </td>
    </tr>
    {/foreach}
{else}
    <tr><td colspan="5" style="text-align:center;">Không có dữ liệu</td>
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