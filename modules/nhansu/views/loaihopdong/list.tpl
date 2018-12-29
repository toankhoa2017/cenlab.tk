{* Extend our master template *}
{extends file="master.tpl"}
{block name=css}
<link rel="stylesheet" href="{site_url()}assets/css/jquery-confirm.css" />
<link rel="stylesheet" href="{site_url()}assets/css/stylevth.css" />
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
                <input type="hidden" value="{$id}" id="parent" name="parent" />
                <div class="modal-body form">
                    <div class="form-group">
                        <label>{$languages.tenloaihopdong}:</label>
                        <input class="form-control" type="text" id="name" name="name" />
                        <input type="hidden" id="id_sua" name="id_sua">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnSave" onclick="_save()" class="btn btn-xs btn-primary">{$languages.button_them}</button>
                    <button type="button" id="btnSua" onclick="_sua_submit()" class="btn btn-xs btn-primary">{$languages.button_sua}</button>
                    <button type="button" class="btn btn-xs btn-danger" data-dismiss="modal">{$languages.button_thoat}</button>
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
$(document).ready(function () {
    _load();
});
function _load() {
    table = $('#table').DataTable({
        "processing": true,
        "serverSide": true,
        "language": {
            "processing": "Đang Load Dữ Liệu...",
        },
        "order": [],
        "ajax": {
            "url": "{site_url()}nhansu/loaihopdong/ajax_list",
            "type": "POST"
        },
        "columnDefs": [
            {
                "targets": [-1], //last column
                "orderable": false, //set not orderable
            },
        ],
        "fnDrawCallback": function (data) {
            $(".paginate_button > a").on("focus", function () {
                $(this).blur();
            });
            $('[data-toggle="tooltip"]').tooltip();
        }
    });
}
function reload_table() {
    table.ajax.reload(null, false); //reload datatable ajax
}
function _add() {
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('{$languages.title_them}'); // Set Title to Bootstrap modal title
    $("#btnSua").hide();
    $("#btnSave").show();
}
function _save() {
    if ($("#name").val() == "") {
        swal("{$languages.canhbao}", "{$languages.tenloaihopdong_validation}", "warning");
    } else {
        $.ajax({
            url: "{site_url()}nhansu/loaihopdong/ajax_add",
            type: "POST",
            data: $('#form').serialize(),
            dataType: "JSON",
            success: function (data) {
                //if success close modal and reload ajax table
                if (data.status == 'denied') {
                    swal("{$languages.thatbai}", "{$languages.tenloaihopdong_error}", "error");
                } else {
                    $('#modal_form').modal('hide');
                    reload_table();
                    swal("{$languages.thanhcong}", "{$languages.them_success}", "success");
                }
            }
        });
    }
}
function _sua(id, name) {
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('{$languages.title_sua}');
    $("#name").val(name);
    $("#id_sua").val(id);
    $("#btnSua").show();
    $("#btnSave").hide();
}
function _sua_submit() {
    if ($("#name").val() == "") {
        swal("{$languages.canhbao}", "{$languages.tenloaihopdong_validation}", "warning");
    } else {
        $.confirm({
            title: '{$languages.xacnhan}',
            content: '{$languages.xacnhan_sua}',
            icon: 'fa fa-question',
            theme: 'modern',
            closeIcon: true,
            autoClose: 'cancel|10000',
            animation: 'scale',
            type: 'orange',
            buttons: {
                '{$languages.co}': {
                    btnClass: 'btn-primary',
                    action: function () {
                        $.ajax({
                            type: "POST",
                            url: "{site_url()}nhansu/loaihopdong/ajax_edit",
                            data: {
                                idloai_hopdongsua: $("#id_sua").val(),
                                nameloai_hopdongsua: $("#name").val()
                            },
                            datatype: "text",
                            success: function (data) {
                                if (data == 1) {
                                    reload_table();
                                    $("#modal_form").modal("hide");
                                    swal("{$languages.thanhcong}", "{$languages.sua_success}", "success");
                                } else {
                                    swal("{$languages.thatbai}", "{$languages.tenloaihopdong_error}", "error");
                                }
                            }
                        });
                    }
                },
                cancel: {
                    text: '{$languages.khong}',
                    btnClass: 'btn-danger',
                    action: function () {
                        // lets the user close the modal.
                    }
                }
            }
        });
    }
}
function _xoa(id) {
    if (confirm("Bạn có chắc xóa?")) {
        $.ajax({
            url:"{site_url()}nhansu/loaihopdong/delete",
            type : "POST",
            data:  { id : id },
            dataType : 'JSON',
            success:function(data)
            {
                if(data.code == '100') {
                    reload_table();
                }
            }
        });
    }
}
</script>
{/block}
{block name=body}
<div class="main-content">
    <div class="main-content-inner">
        <div class="page-content">
            <div class="row">
                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->
                    <h3 class="header smaller lighter blue">{$languages.title}</h3>
                    <div class="clearfix">
                        <div class="pull-left tableTools-container"></div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="pull-left">
                                <button class="btn btn-xs btn-primary" onclick="_add()"><i class="ace-icon fa fa-plus"></i> {$languages.button_create}</button>
                                <button class="btn btn-xs btn-warning" onclick="reload_table()"><i class="ace-icon fa fa-refresh"></i> {$languages.button_reload}</button>                                </div>
                        </div>
                    </div>
                    <div class="space-2"></div>
                    <table id="table" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>{$languages.table_1}</th>
                                <th>{$languages.table_2}</th>
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