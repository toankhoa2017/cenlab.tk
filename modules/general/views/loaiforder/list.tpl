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
                    <div class="modal-body form">
                        <div class="form-group">
                            <label>Tên Hiển Thị:</label>
                            <input autocomplete="off" class="form-control" type="text" id="name" name="name" />
                            <input type="hidden" id="id_sua" name="id_sua">
                        </div>
                        <div class="form-group">
                            <label>Tên Forder:</label>
                            <input autocomplete="off" class="form-control" type="text" id="path" name="path" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnSave" onclick="_save()" class="btn btn-xs btn-primary">Thêm</button>
                        <button type="button" id="btnSua" onclick="_sua_oke()" class="btn btn-xs btn-primary">Sửa</button>
                        <button type="button" class="btn btn-xs btn-danger" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Bootstrap modal -->
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
                                    //"paging": true,
                                    //"lengthChange": false,
                                    //"searching": false,
                                    //"ordering": true,
                                    //"info": true,
                                    //"autoWidth": true,
                                    "order": [],

                                    "ajax": {
                                        "url": "{site_url()}general/loaiforder/ajax_list",
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
                            function _add() {
                                $('#form')[0].reset(); // reset form on modals
                                $('.form-group').removeClass('has-error'); // clear error class
                                $('.help-block').empty(); // clear error string
                                $('#modal_form').modal('show'); // show bootstrap modal
                                $('.modal-title').text('Thêm Thư Mục Mới'); // Set Title to Bootstrap modal title
                                $("#btnSua").hide();
                                $("#btnSave").show();
                            }
                            function _xoa(id) {
                                $.confirm({
                                    title: 'Xác nhận',
                                    content: 'Xác nhận xóa thư mục này?',
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
                                                    url: "{site_url()}general/loaiforder/xoaloaiforder",
                                                    data: {
                                                        id_xoa: id
                                                    },
                                                    datatype: "text",
                                                    success: function (data) {
                                                        if (data == 1) {
                                                            reload_table();
                                                            swal("Thành Công!", "Bạn đã xóa thư mục thành công", "success");
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
                            var namecu = "";
                            function _sua(id, name , path) {
                                $('#form')[0].reset(); // reset form on modals
                                $('.form-group').removeClass('has-error'); // clear error class
                                $('.help-block').empty(); // clear error string
                                $('#modal_form').modal('show'); // show bootstrap modal
                                $('.modal-title').text('Sửa Thư Mục');
                                $("#name").val(name);
                                $("#id_sua").val(id);
                                $("#path").val(path);
                                $("#btnSua").show();
                                $("#btnSave").hide();
                            }

                            function _sua_oke() {
                                if ($("#name").val() == "") {
                                    swal("Cảnh Báo!", "Tên thư mục không để trống", "warning");
                                } else if ($("#path").val() == "") {
                                    swal("Cảnh Báo!", "Tên đường dẫn không để trống", "warning");
                                } else {
                                    $.confirm({
                                        title: 'Xác nhận',
                                        content: 'Xác nhận sửa thư mục này?',
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
                                                        url: "{site_url()}general/loaiforder/sualoaiforder",
                                                        data: {
                                                            id: $("#id_sua").val(),
                                                            name: $("#name").val(),
                                                            path: $("#path").val(),
                                                        },
                                                        datatype: "text",
                                                        success: function (data) {
                                                            if (data == 1) {
                                                                namecu = "";
                                                                reload_table();
                                                                $("#modal_form").modal("hide");
                                                                swal("Thành Công!", "Bạn đã sửa thư mục thành công", "success");
                                                            } else {
                                                                swal("Thất Bại!", "Tên thư mục hoặc đường dẫn Đã Tồn Tại", "error");
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
                            function _save() {
                                $.ajax({
                                    url: "{site_url()}general/loaiforder/ajax_add",
                                    type: "POST",
                                    data: $('#form').serialize(),
                                    dataType: "JSON",
                                    success: function (data) {
                                        //if success close modal and reload ajax table
                                        if (data.status == 'denied') {
                                            swal("Thất Bại!", "Tên thư mục hoặc đường dẫn Đã Tồn Tại", "error");
                                        } else {
                                            $('#modal_form').modal('hide');
                                            reload_table();
                                            swal("Thành Công!", "Bạn đã thêm thư mục thành công", "success");
                                        }
                                    },
                                    error: function (jqXHR, textStatus, errorThrown) {
                                        alert('Error adding / update data');
                                    }
                                });
                            }
                            function reload_table() {
                                table.ajax.reload(null, false); //reload datatable ajax 
                            }
    </script>
{/block}
{block name=body}
    <div class="main-content">
        <div class="main-content-inner">
            <!--PATH BEGINS-->
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li><i class="ace-icon fa fa-home home-icon"></i><a href="#">Quản Lý File</a></li>
                    <li class="active">Quản lý thư mục</li>
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
                        <h3 class="header smaller lighter blue">Danh sách thư mục</h3>
                        <div class="clearfix">
                            <div class="pull-left tableTools-container"></div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12">
                                <div class="pull-left">
                                    <button class="btn btn-xs btn-primary" onclick="_add()"><i class="ace-icon fa fa-plus"></i> Create</button>
                                    <button class="btn btn-xs btn-warning" onclick="reload_table()"><i class="ace-icon fa fa-refresh"></i> Reload</button>
                                </div>
                            </div>
                        </div>
                        <div class="space-2"></div>
                        <table id="table" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Tên Thư Mục</th>
                                    <th>Đường dẫn</th>
                                    <th>Thao tác</th>
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