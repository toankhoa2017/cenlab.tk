{* Extend our master template *}
{extends file="master.tpl"}
{block name=css}
<link rel="stylesheet" href="{site_url()}assets/css/jquery-confirm.css" />
<link rel="stylesheet" href="{site_url()}assets/css/stylevth.css" />
<link rel="stylesheet" href="{$assets_path}css/bootstrap-datepicker3.min.css" />
{/block}
{block name=script}
<!-- Bootstrap thêm modal -->
<div class="modal fade" id="modal_sua" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title"></h3>
            </div>
            <form id="form_sua" role="form">
                <input type="hidden" id="id_sua" name="id_sua" />
                <div class="modal-body form">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Họ</label>
                                <input class="form-control" type="text" id="ho_sua" name="ho_sua" placeholder="Nhập Họ Và Tên Lót" required="Không Được Để Trống"/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tên</label>
                                <input class="form-control" type="text" id="ten_sua" name="ten_sua" placeholder=" Nhập Tên" required="Không Được Để Trống"/>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="id-date-picker-1">Ngày Sinh</label>
                        <input class="form-control date-picker" id="ngaysinh_sua" placeholder="Chọn Ngày Sinh" name="ngaysinh_sua" type="text" data-date-format="dd-mm-yyyy" required="Không Được Để Trống"/>
                    </div>
                    <div class="form-group">
                        <label>Địa Chỉ</label>
                        <input class="form-control" type="text" id="diachi_sua" name="diachi_sua" placeholder="Nhập Địa Chỉ" required="Không Được Để Trống"/>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="_sua_submit()" class="btn btn-xs btn-primary">Lưu</button>
                    <button type="button" class="btn btn-xs btn-danger" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- End Bootstrap modal -->

<script src="{$assets_path}plugins/datatables/jquery.dataTables.min.js"></script>
<script src="{$assets_path}plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="{site_url()}assets/js/sweetalert.min.js"></script>
<script src="{site_url()}assets/js/jquery-confirm.js"></script>
<script src="{$assets_path}js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript">
$(document).ready(function () {
    _loadList();
    $('.date-picker').datepicker({
        autoclose: true,
        todayHighlight: true
    })
});
function _loadList() {
    table = $('#table').DataTable({
        "processing": true,
        "language": {
            "processing": "Đang Load Dữ Liệu...",
        },
        "serverSide": true,
        "order": [],
        "ajax": {
            "url": "{site_url()}nhansu/ajax_list",
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
function _sua(id) {
    $.ajax({
        type: "POST",
        url: "{site_url()}nhansu/get",
        data: {
            id_nhansu: id,
        },
        datatype: "JSON",
        success: function (data) {
            var nhansu = $.parseJSON(data);
            $('#form_sua')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            $('#modal_sua').modal('show'); // show bootstrap modal
            $('.modal-title').text('Sửa nhân sự');
            $("#id_sua").val(id);
            $("#ho_sua").val(nhansu.nhansu_lastname);
            $("#ten_sua").val(nhansu.nhansu_firstname);
            $("#ngaysinh_sua").val(nhansu.nhansu_ngaysinh);
            $("#diachi_sua").val(nhansu.nhansu_address);
        }
    });
}
function _sua_validate() {
    var oke = true;
    if ($("#ho_sua").val() == "") {
        swal("Cảnh Báo!", "Họ Không Để Trống", "warning");
        oke = false;
    } else if ($("#ho_ten").val() == "") {
        swal("Cảnh Báo!", "Tên Không Để Trống", "warning");
        oke = false;
    } else if ($("#ngaysinh_sua").val() == "") {
        swal("Cảnh Báo!", "Ngày Sinh Không Để Trống", "warning");
        oke = false;
    } else if ($("#diachi_sua").val() == "") {
        swal("Cảnh Báo!", "Địa Chỉ Không Để Trống", "warning");
        oke = false;
    }
    return oke;
}
function _sua_submit() {
    var kiemtra = _sua_validate();
    if (kiemtra == true) {
        $.confirm({
            title: 'Xác nhận',
            content: 'Xác nhận sửa nhân sự này?',
            icon: 'fa fa-question',
            theme: 'modern',
            closeIcon: true,
            autoClose: 'cancel|10000',
            animation: 'scale',
            type: 'orange',
            buttons: {
                'Có': {
                    btnClass: 'btn-primary',
                    action: function () {
                        $.ajax({
                            type: "POST",
                            url: "{site_url()}nhansu/update",
                            data: {
                                id: $("#id_sua").val(),
                                ho: $("#ho_sua").val(),
                                ten: $("#ten_sua").val(),
                                ngaysinh: $("#ngaysinh_sua").val(),
                                diachi: $("#diachi_sua").val()
                            },
                            datatype: "text",
                            success: function (data) {
                                if (data == 1) {
                                    reload_table();
                                    $("#modal_sua").modal("hide");
                                    swal("Thành Công!", "Bạn đã sửa đơn vị thành công", "success");
                                } else {
                                    swal("Thất Bại!", "Tên Đơn Vị Đã Tồn Tại", "error");
                                }
                            }
                        });
                    }
                },
                cancel: {
                    text: 'Không',
                    btnClass: 'btn-danger',
                    action: function () {
                        // lets the user close the modal.
                    }
                }
            }
        });
    }
}
function _xoa(id, name) {
    $.confirm({
        title: 'Xác nhận',
        content: 'Xóa nhân viên ' + name + '?',
        icon: 'fa fa-question',
        theme: 'modern',
        closeIcon: true,
        autoClose: 'cancel|10000',
        animation: 'scale',
        type: 'orange',
        buttons: {
            'Có': {
                btnClass: 'btn-primary',
                action: function () {
                    $.ajax({
                        type: "POST",
                        url: "{site_url()}nhansu/delete",
                        data: {
                            idnhansuxoa: id
                        },
                        datatype: "text",
                        success: function (data) {
                            if (data == 1) {
                                reload_table();
                                swal("Thành Công!", "Bạn đã xóa nhân sự thành công", "success");
                            } else {
                                swal("Thất Bại!", "Có Lỗi Sảy Ra", "error");
                            }
                        }
                    });
                }
            },
            cancel: {
                text: 'Không',
                btnClass: 'btn-danger',
                action: function () {
                    // lets the user close the modal.
                }
            }
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
                <li><i class="ace-icon fa fa-home home-icon"></i><a href="#">{$languages.url_1}</a></li>
                <li class="active">{$languages.url_2}</li>
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
                    <h3 class="header smaller lighter blue">{$languages.title}</h3>
                    <div class="clearfix">
                        <div class="pull-left tableTools-container"></div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="pull-left">
                                <button class="btn btn-xs btn-primary" onclick="window.location='{site_url()}nhansu/add'"><i class="ace-icon fa fa-plus"></i> {$languages.button_create}</button>
                                <button class="btn btn-xs btn-warning" onclick="reload_table()"><i class="ace-icon fa fa-refresh"></i> {$languages.button_reload}</button>
                                <button class="btn btn-xs btn-success" onclick="window.location='{site_url()}nhansu/duyetketqua'"><i class="ace-icon fa fa-wrench icon-only"></i> {$languages.button_danhsachduyet}</button>
                            </div>
                        </div>
                    </div>
                    <div class="space-2"></div>
                    <table id="table" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>{$languages.table_1}</th>
                                <th>{$languages.table_2}</th>
                                <th>{$languages.table_3}</th>
                                <th>{$languages.table_4}</th>
                                <th>{$languages.table_5}</th>
                                <th>{$languages.table_6}</th>
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