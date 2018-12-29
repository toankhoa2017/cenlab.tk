{* Extend our master template *}
{extends file="master.tpl"}
{block name=css}
<style>
.update {
   display: none;
}
.editable-label-text {
   width: 100%;
}
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
            <input type="hidden" value="{$project}" id="project" name="project" />
            <div class="modal-body form">
                <div class="form-group">
                    <label>Group name:</label>
                    <input class="form-control" type="text" id="name" name="name" />
                </div>
                <div class="form-group">
                    <label>Group link:</label>
                    <input class="form-control" type="text" id="link" name="link" />
                </div>
                <div class="form-group">
                    <label>Group icon:</label>
                    <input class="form-control" type="text" id="icon" name="icon" />
                </div>
                <div class="form-group">
                	<label>Group order:</label>
                    <input class="form-control" type="text" id="order" name="order" />
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="_save()" class="btn btn-xs btn-primary">Submit</button>
                <button type="button" class="btn btn-xs btn-danger" data-dismiss="modal">Cancel</button>
            </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->
<script src="{$assets_path}plugins/datatables/jquery.dataTables.min.js"></script>
<script src="{$assets_path}plugins/datatables/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">
var id;
var $lbl1;
var $lbl2;
var $lbl3;
var $lbl4;
$(document).ready(function() {
    $("#sidebar-group").addClass("active ");
    _load({$project});
});
$('#table').on('click','.editgroup', function(){
    id = $(this).parents('tr').find('.gname').data('id');
    $lbl1 =  $(this).parents('tr').find('div.gname a'), value1 = $lbl1.text();
    $txt1 = $('<input type="text" id="txtgname" class="editable-label-text" value="'+value1+'" />');
    $lbl1.replaceWith($txt1);	 
	
    $lbl2 =  $(this).parents('tr').find('div.glink'), value2 = $lbl2.text();
    $txt2 = $('<input type="text" id="txtglink" class="editable-label-text" value="'+value2+'" />');
    $lbl2.replaceWith($txt2);	
	
    $lbl3 =  $(this).parents('tr').find('div.gicon'), value3 = $lbl3.text();
    $txt3 = $('<input type="text" id="txtgicon" class="editable-label-text" value="'+value3+'" />');
    $lbl3.replaceWith($txt3);	
	
    $lbl4 =  $(this).parents('tr').find('div.gorder'), value4 = $lbl4.text();
    $txt4 = $('<input type="text" id="txtgorder" class="editable-label-text" value="'+value4+'" />');
    $lbl4.replaceWith($txt4);	
	
    $(this).parents('tr').find('.update').attr("style", "display:inline");
}); 
$('#table').on('click','.update', function(){
	var gname = $(this).parents('tr').find('#txtgname').val();
	var glink = $(this).parents('tr').find('#txtglink').val();
	var gicon = $(this).parents('tr').find('#txtgicon').val();
	var gorder = $(this).parents('tr').find('#txtgorder').val();
	$(this).parents('tr').find('#txtgname').replaceWith($lbl1.text(gname));
	$(this).parents('tr').find('#txtglink').replaceWith($lbl2.text(glink));
	$(this).parents('tr').find('#txtgicon').replaceWith($lbl3.text(gicon));
	$(this).parents('tr').find('#txtgorder').replaceWith($lbl4.text(gorder));
	$(this).attr("style", "display:none");
	$.ajax({
        url: "{site_url()}system/update",
        type: "POST",
        data: { id:id, gname: gname, glink: glink, gicon: gicon, gorder: gorder },
        dataType: "JSON",
        success: function(data) {
           if(data.code== '100') {
             //  reload_table();
           }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('Error adding / update data xxxx');
        }
    });
	
});
function _load(project) {
    table = $('#table').DataTable({ 
        "processing": true,
        "serverSide": true,
        //"paging": true,
        //"lengthChange": false,
        //"searching": false,
        //"ordering": true,
        //"info": true,
        //"autoWidth": true,
        "order": [],

        "ajax": {
            "url": "{site_url()}system/ajax_list?project="+ project,
            "type": "POST"
        },
        "columnDefs": [
        { 
            "targets": [ -1 ], //last column
            "orderable": false, //set not orderable
        },
        ],
    });
}
function _add() {
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Create new group'); // Set Title to Bootstrap modal title
}
function _save() {
    $.ajax({
        url : "{site_url()}system/ajax_add",
        type: "POST",
        data: $('#form').serialize(),
        dataType: "JSON",
        success: function(data) {
           //if success close modal and reload ajax table
           $('#modal_form').modal('hide');
           if (data.status == 'denied') document.location.href = '{site_url()}system/denied?w=write';
           else reload_table();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('Error adding / update data');
        }
    });
}
function reload_table() {
    table.ajax.reload(null,false); //reload datatable ajax 
}
function gotoType(id) {
    document.location.href='{site_url()}system?project='+ id;
}
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
<h3 class="header smaller lighter blue">Group</h3>
<div class="clearfix">
<div class="pull-left tableTools-container"></div>
</div>
<div class="row">
<div class="col-xs-12">
<div class="pull-left">
    <button class="btn btn-xs btn-primary" onclick="_add()"><i class="ace-icon fa fa-plus"></i> Create</button>
    <button class="btn btn-xs btn-warning" onclick="reload_table()"><i class="ace-icon fa fa-refresh"></i> Reload</button>
</div>
<div class="pull-right">
    Project: 
    <select id="project" name="project" aria-invalid="false" onchange="gotoType($('#project').val())">
    <option value="">Chọn dự án</option>
    {html_options options=$projects selected=$project}
    </select>
</div>
</div>
</div>
<div class="space-2"></div>
<table id="table" class="table table-striped table-bordered table-hover">
<thead>
<tr>
<th>Name</th>
<th>Link</th>
<th>Icon</th>
<th>Order</th>
<th></th>
</tr>
</thead>
<tbody>
</tbody>
</table>
<!-- PAGE CONTENT ENDS -->
</div>
</div>
</div>
</div>
</div>
{/block}