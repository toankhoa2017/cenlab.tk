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
                        <input type="hidden" value="{$id}" id="type" name="type" />
                        <input type="hidden" id="setid" name="setid" />
                        <div class="form-group">
                            <label>Tên bộ thiết bị</label>
                            <input class="form-control" type="text" id="name" name="name" />
                        </div>
                        <div class="form-group">
                            <label>Thời gian kiểm tra định kỳ (tháng)</label>
                            <input class="form-control" type="text" id="time_check" name="time_check" value="{$type}" />
                        </div>
                        <div class="form-group">
                            <label>Kiểm tra hằng ngày</label>
                            <select class="form-control" name="check" id="check" >
                                <option value="1">Có</option>
                                <option value="2">Không</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Trạng Thái</label>
                            <select class="form-control" name="status" id="status" >
                                <option value="">Chọn trạng thái</option>
                                <option value="1">Tồn kho</option>
                                <option value="2">Đang kiểm</option>
                                <option value="3">Chờ sửa</option>
                                <option value="4">Đang sửa</option>
                                <option value="5">Đang xuất</option>
                                <option value="6">Thanh lý</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnThem" onclick="_save()" class="btn btn-xs btn-primary">Thêm</button>
                        <button type="button" id="btnSave" onclick="_edit()" class="btn btn-xs btn-primary">Lưu</button>
                        <button type="button" class="btn btn-xs btn-danger" data-dismiss="modal">Hủy</button>
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
        _load({$id});
    });
    function _load(id) {
        table = $('#table').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "{site_url()}thietbi/deviceset/ajax_listset?id=" + id,
                "type": "POST"
            },
            "columnDefs": [
                {
                    "targets": [-1], //last column
                    "orderable": false, //set not orderable
                },
            ]
        });
    }
    function _add() {
        $('#form')[0].reset(); // reset form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string
        $('#modal_form').modal('show'); // show bootstrap modal
        $('.modal-title').text('Thêm bộ thiết bị mới'); // Set Title to Bootstrap modal title
		$('#btnThem').show();
		$('#btnSave').hide();
    }
    
    function _save() {
        if ($("#name").val() == "") {
            swal("Thông báo", "Tên bộ thiết bị không được rỗng", "warning");
            return false;
        }
        if($("#time_check").val() == "") {
            swal("Thông báo", "Thời gian kiểm tra định kỳ không được rỗng", "warning");
            return false;
        }
        else {
            if(isNaN($("#time_check").val()) == true) {
                swal("Thông báo", "Bạn chỉ được nhập số", "error");
                return false;
            }
        }
		if($('#status').val() == "") {
			swal("Thông báo", "Bạn phải chọn trạng thái bộ thiết bị","warning");
			return false;
		}
        $.ajax({
            url: "{site_url()}thietbi/deviceset/addset",
            type: "POST",
            data: $('#form').serialize(),
            dataType: "JSON",
            success: function (data) {
                if(data.err_code == '100') {
                    swal("Thông Báo", "Thêm Thành Công", "success");
                    $('#modal_form').modal('hide');
                    reload_table();                   
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
            	alert('Error adding / update data');
            }
        });  
    }
    
	function edit(id,name, period, daily, status) {
		$('#form')[0].reset(); // reset form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string
        $('#modal_form').modal('show'); // show bootstrap modal
        $('.modal-title').text('Sửa bộ thiết bị mới'); // Set Title to Bootstrap modal title
		$('#setid').val(id);
		$('#name').val(name);
		$('#time_check').val(period);
		$('#check').val(daily);
		$('#status').val(status);
		$('#btnThem').hide();
		$('#btnSave').show();
	}
	function _edit() {
		if ($("#name").val() == "") {
            swal("Thông báo", "Tên bộ thiết bị không được rỗng", "warning");
            return false;
        }
        if($("#time_check").val() == "") {
            swal("Thông báo", "Thời gian kiểm tra định kỳ không được rỗng", "warning");
            return false;
        }
        else {
            if(isNaN($("#time_check").val()) == true) {
                swal("Thông báo", "Bạn chỉ được nhập số", "error");
                return false;
            }
        }
		if($('#status').val() == "") {
			swal("Thông báo", "Bạn phải chọn trạng thái bộ thiết bị","warning");
			return false;
		}
        $.ajax({
            url: "{site_url()}thietbi/deviceset/update",
            type: "POST",
            data: $('#form').serialize(),
            dataType: "JSON",
            success: function (data) {
                if(data.err_code == '100') {
                    swal("Thông Báo", "Sửa Thành Công", "success");
                    $('#modal_form').modal('hide');
                    reload_table();                   
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
            	alert('Error adding / update data');
            }
        });  
	}
	function del(id) {
		if(confirm("Bạn chắc chắn muốn xóa")) {
			$.ajax({
				url: "{site_url()}thietbi/deviceset/delete",
				type: "POST",
				data: { id : id },
				dataType:"JSON",
				success: function(data) {
					reload_table();
				},
				error: function (jqXHR, textStatus, errorThrown) {
					alert('Error adding / update data');
				}
			});
		}
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
                        <h3 class="header smaller lighter blue">BỘ THIẾT BỊ</h3>
                        <div class="clearfix">
                            <div class="pull-left tableTools-container"></div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12">
                                <div class="pull-left">
                                    <button class="btn btn-xs btn-primary" onclick="_add()"><i class="ace-icon fa fa-plus"></i>Thêm mới</button>
                                    <button class="btn btn-xs btn-warning" onclick="reload_table()"><i class="ace-icon fa fa-refresh"></i>Tải lại</button>
                                </div>
                            </div>
                        </div>
                        <div class="space-2"></div>
                        <table id="table" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Tên Bộ Thiết Bị</th>
                                    <th>Thời Gian Kiểm Tra</th>
                                    <th>Kiểm Tra Hàng Ngày</th>
                                    <th>Tình Trạng</th>
                                    <th style="text-align: center">Thao Tác</th>
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