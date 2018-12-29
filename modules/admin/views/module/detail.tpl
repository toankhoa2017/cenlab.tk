{* Extend our master template *}
{extends file="master.tpl"}
{block name=css}
    <link rel="stylesheet" href="{site_url()}assets/css/jquery-confirm.css" />
    <link rel="stylesheet" href="{site_url()}assets/css/stylevth.css" />
    <style>
        .updatemodule {
           display: none;
       }
       .editable-label-text {
           width: 100%;
       }
    </style>
{/block}
{block name=script}

<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title"></h3>
            </div>
            <form id="form" method="post">
                <input type='hidden' id='aid' name='aid' value=''>
                <div class="modal-body form">
                    <div id="listmods"></div>
                </div>
                <div id="test3011"></div>
                <div class="modal-footer">
                    <button type="button" id="btnSave" onclick="_save()" class="btn btn-xs btn-primary">Cập Nhật Quyền</button>
                    <button type="button" class="btn btn-xs btn-danger" data-dismiss="modal">Hủy</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script src="{$assets_path}plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="{$assets_path}plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script src="{site_url()}assets/js/sweetalert.min.js"></script>
    <script src="{site_url()}assets/js/jquery-confirm.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $(".removeacc").click(function() {
        if (confirm("Bạn có chắc xóa?")) {
            var aid = this.value;
            $.post("{site_url()}admin/module/removeacc", { gid: {$group->id}, aid: aid },
            function(data) {
                if (data == 'denied') document.location.href = '{site_url()}admin/denied?w=delete';
                else $('table tr#acc_'+ aid).remove();
            });
        }
    });
});

$('#table').on('click','.editmodule', function(){
    var id = $(this).parents('tr').find('div.mname').data('id');
    var $lbl1 =  $(this).parents('tr').find('div.mname'), value1 = $lbl1.text();
    $txt1 = $('<input type="text" id="txtmname_'+id+'" class="editable-label-text" value="'+value1+'" />');
    $lbl1.replaceWith($txt1);
	
    var $lbl2 =  $(this).parents('tr').find('div.morder'), value2 = $lbl2.text();
    $txt2 = $('<input type="text" id="txtmorder_'+id+'" class="editable-label-text" value="'+value2+'" />');
    $lbl2.replaceWith($txt2);
	
    $(this).parents('tr').find('button.updatemodule').attr("style", "display:inline");
});
$('#table').on('click','.updatemodule', function(){
    var id = this.value;
    var mname = $(this).parents('tr').find('#txtmname_'+id).val();
    var morder = $(this).parents('tr').find('#txtmorder_'+id).val();
    var $lbl1 = $('<div class="mname" data-id="'+id+'">'+mname+'</div>');
    var $lbl2 = $('<div class="morder">'+morder+'</div>');
    $(this).parents('tr').find('#txtmname_'+id).replaceWith($lbl1);
    $(this).parents('tr').find('#txtmorder_'+id).replaceWith($lbl2);
    $(this).parents('tr').find('button.updatemodule').attr("style", "display:none");
    $.ajax({
        url: "{site_url()}admin/module/updatemod",
        type: "POST",
        data: { id:id, mname:mname, morder:morder, gid:{$group->id} },
        dataType: "JSON",
        success: function(data) {
            if(data.code == "100") {}
        },
        error: function (jqXHR, textStatus, errorThrown) {
                alert('Error adding / update data');
        }
    });

});
function _add(user, id) {
    $.ajax({
        url: "{site_url()}admin/module/listmoduser",
        type: "post",
        data: { id : id, gid: {$gid} },
        success: function(data) {
            $('#form')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            $('#modal_form').modal('show'); // show bootstrap modal
            $('.modal-title').text(user); // Set Title to Bootstrap modal title
            $('#modal_form').on('shown.bs.modal', function() {
                $('#listmods').html(data);
                $('#aid').val(id);
            });
        }
    });
}

function _save() {
    $.ajax({
        url: "{site_url()}admin/module/setpermission",
        type: "post",
        data:  $('#form').serialize(),
        dataType: "JSON",
        success: function(data) {
            if(data.code == '200') {
                alert('ook');
            }
        }
    });
}
{*function _save() {
    $.ajax({
        url: "{site_url()}admin/module/setpermission",
        type: "post",
        data:  $('#form').serialize(),
        dataType: "JSON",
        success: function(data) {
            if(data.code == '100') }{
                alert('ook');
            }
        }
    });
}*}
</script>
{/block}
{block name=body}
<div class="main-content">
<div class="main-content-inner">
<!--PATH BEGINS-->
<div class="breadcrumbs ace-save-state" id="breadcrumbs">
<ul class="breadcrumb">
<li><i class="ace-icon fa fa-home home-icon"></i><a href="{site_url()}admin/module">Quản lý nhóm</a></li>
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
<h3>Nhóm {$group->name}</h3>
<h4 class="header smaller lighter blue">Danh sách modules trong nhóm</h4>
<div class="clearfix">
<div class="pull-left tableTools-container"></div>
</div>
<table id="table" class="table table-striped table-bordered table-hover">
<thead>
<tr>
    <th>Name</th>
    <th>Define</th>
    <th>Thứ tự</th>
    <th></th>
</tr>
</thead>
<tbody>
{if $modules}
    {foreach from=$modules item=module}
    <tr>
        <td><div class="mname" data-id="{$module->id}">{$module->name}</div></td>
        <td>{$module->dinhnghia}</td>
        <td><div class="morder">{$module->thutu}</div></td>
        <td>
            <button class="btn btn-xs btn-info editmodule"><i class="ace-icon fa fa-pencil smaller"></i></button>
            <button class="btn btn-warning btn-xs btnumail updatemodule" value="{$module->id}"><i class="ace-icon fa fa-floppy-o smaller"></i></button>
        </td>
    </tr>
    {/foreach}
{else}
    <tr><td colspan="4" style="text-align:center;">Không có dữ liệu</td>
{/if}
</tbody>
</table>
<h4 class="header smaller lighter blue">Danh sách tài khoản trong nhóm</h4>
<div class="clearfix">
<div class="pull-left tableTools-container"></div>
</div>
{if $privcheck.update}<div style="margin-bottom:5px;"><a class="btn btn-xs btn-primary" href="{site_url()}admin/module/addaccs?id={$group->id}"><i class="ace-icon fa fa-plus"></i> Thêm tài khoản vào nhóm</a></div>{/if}
<table id="table" class="table table-striped table-bordered table-hover">
<thead>
<tr>
    <th>Name</th>
    <th>Email</th>
    <th>Phone</th>
    <th>Đơn vị</th>
    <th>Chức vụ</th>
    {if $privcheck.delete}<th></th>{/if}
</tr>
</thead>
<tbody>
{if $accounts}
    {foreach from=$accounts item=account}
    <tr id="acc_{$account.account_id}">
        <td><a onclick="_add('{$account.nhansu_lastname} {$account.nhansu_firstname}', {$account.account_id})">{$account.nhansu_lastname} {$account.nhansu_firstname}</a></td>
        <td>{$account.nhansu_email}</td>
        <td>{$account.nhansu_phone}</td>
        <td>{$account.donvi_ten}</td>
        <td>{$account.chucvu_ten}</td>
        {if $privcheck.delete}<td><button class="btn btn-xs btn-danger removeacc" value="{$account.account_id}"><i class="ace-icon fa fa-trash-o bigger-120"></i></button></td>{/if}
    </tr>
    {/foreach}
{else}
    <tr><td colspan="6" style="text-align:center;">Không có dữ liệu</td>
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