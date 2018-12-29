{* Extend our master template *}
{extends file="master.tpl"}
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
$(document).ready(function() {
    $("#sidebar_quantri").addClass("active open");
    $("#sidebar_quantri_module").addClass("active");
    
    _load();
});
function _load() {
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
            "url": "{site_url()}system/module/ajax_list",
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
    $('.modal-title').text('Create new module'); // Set Title to Bootstrap modal title
}
function _save() {
    $.ajax({
        url : "{site_url()}system/module/ajax_add",
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
</script>
{/block}
{block name=body}
<div class="main-content">
<div class="main-content-inner">
<!--PATH BEGINS-->
<div class="breadcrumbs ace-save-state" id="breadcrumbs">
<ul class="breadcrumb">
<li><i class="ace-icon fa fa-home home-icon"></i><a href="#">Home</a></li>
<li><a href="#">Other Pages</a></li>
<li class="active">Blank Page</li>
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
<h3 class="header smaller lighter blue">Modules</h3>
<div class="clearfix">
<div class="pull-left tableTools-container"></div>
</div>
    <div style="margin-bottom:5px;">
        <button class="btn btn-xs btn-primary" onclick="_add()"><i class="ace-icon fa fa-plus"></i> Create</button>
        <button class="btn btn-xs btn-warning" onclick="reload_table()"><i class="ace-icon fa fa-refresh"></i> Reload</button>
    </div>
    <div>
    <table id="table" class="table table-striped table-bordered table-hover">
    <thead>
    <tr>
    <th>VAL</th>
    <th>Name</th>
    <th>Define</th>
    <th>Link</th>
    </tr>
    </thead>
    <tbody>
    </tbody>
    </table>
    </div>
<!-- PAGE CONTENT ENDS -->
</div>
</div>
</div>
</div>
</div>
{/block}