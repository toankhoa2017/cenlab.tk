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
                        <input type="hidden" value="" id="spareid" name="spareid" />
                        <div class="form-group">
                            <label>Mã phụ tùng</label>
                            <input class="form-control" type="text" id="code" name="code" onblur="checkcode()" />
                        </div>
                        <div class="form-group">
                            <label>Tên phụ tùng</label>
                            <input class="form-control" type="text" id="name" name="name" />
                        </div>
                        <div class="form-group">
                            <label>Thiết bị</label>
                            <select class="form-control chosen-select" name="device" id="device" >
                                <option value="">Chọn thiết bị</option>
                                {foreach from=$device item=dev}
                                	<option value="{$dev['device_id']}">{$dev['device_name']}</option>
                                {/foreach}
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
    <script src="{site_url()}assets/js/chosen.jquery.min.js"></script>
    <script type="text/javascript">
    $(document).ready(function () {
        _load();
    });
	
    function _load() {
        table = $('#table').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "{site_url()}thietbi/sparepart/ajax_list",
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
		$('#deviceset').trigger("chosen:updated");
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string
        $('#modal_form').modal('show'); // show bootstrap modal
        $('.modal-title').text('Thêm thiết bị mới'); // Set Title to Bootstrap modal title
		$('#btnSave').hide();
		$('#btnThem').show();
    }
    function checkcode() {
		$.ajax({
			url: "{site_url()}thietbi/sparepart/checkcode",
			type: "POST",
			data: $('#code').serialize(),
			dataType:"JSON",
			success: function(data) {
				if(data.err_code == '100') {
					swal("Thông báo", data.err_mess, "warning");
				}
			}
		});
	}
    function _save() {
        if ($("#code").val() == "") {
            swal("Thông báo", "Mã phụ tùng không được rỗng", "warning");
            return false;
        }
        if($("#name").val() == "") {
            swal("Thông báo", "Tên phụ tùng không được rỗng", "warning");
            return false;
        }
        if($("#device").val() == "") {
            swal("Thông báo", "Bạn phải chọn thiết bị", "warning");
            return false;
        }
        $.ajax({
            url: "{site_url()}thietbi/sparepart/ajax_add",
            type: "POST",
            data: $('#form').serialize(),
            dataType: "JSON",
            success: function (data) {
                if(data.err_code == '100') {
                    $('#modal_form').modal('hide');
                    reload_table();
                    swal("Thông Báo", "Thêm Thành Công", "success");
                }
            }
        });  
    }
    function edit(id,code,name,idset) {
        $('#form')[0].reset(); // reset form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string
        $('#modal_form').modal('show'); // show bootstrap modal
        $('.modal-title').text('Sửa thiết bị'); // Set Title to Bootstrap modal title
        $('#spareid').val(id);
        $('#code').val(code);
        $('#name').val(name);
        $('#device').val(idset);
		$('#device').trigger("chosen:updated");
		$('#btnSave').show();
		$('#btnThem').hide();
    }
	
	function _edit() {
		if ($("#code").val() == "") {
            swal("Thông báo", "Mã phụ tùng không được rỗng", "warning");
            return false;
        }
        if($("#name").val() == "") {
            swal("Thông báo", "Tên phụ tùng không được rỗng", "warning");
            return false;
        }
        if($("#deviceset").val() == "") {
            swal("Thông báo", "Bạn phải chọn thiết bị", "warning");
            return false;
        }
        $.ajax({
            url: "{site_url()}thietbi/sparepart/update",
            type: "POST",
            data: $('#form').serialize(),
            dataType: "JSON",
            success: function (data) {
                if(data.err_code == '100') {
                    $('#modal_form').modal('hide');
                    reload_table();
                    swal("Thông Báo", "Cập nhật Thành Công", "success");
                }
            }
        });  
	}
    function del(id) {
		if(confirm("Bạn chắc chắn muốn xóa")) {
            $.ajax({
                url: "{site_url()}thietbi/sparepart/delete",
                type: "POST",
                data: { id : id },
                dataType: "JSON",
                success: function(data) {
                    if(data.err_code == '100') {
                        reload_table();
                    }
                }
            });
        }
	}
    function reload_table() {
        table.ajax.reload(null, false); //reload datatable ajax 
    }
{literal}
jQuery(function($) {				
    if(!ace.vars['touch']) {
            $('.chosen-select').chosen({allow_single_deselect:true});
            //resize the chosen on window resize
            $(window)
            .off('resize.chosen')
            .on('resize.chosen', function() {
                $('.chosen-select').each(function() {
                    var $this = $(this);
                    $this.next().css({'width': '100%'});
                })
            }).trigger('resize.chosen');
            //resize chosen on sidebar collapse/expand
            $(document).on('settings.ace.chosen', function(e, event_name, event_val) {
                if(event_name != 'sidebar_collapsed') return;
                $('.chosen-select').each(function() {
                    var $this = $(this);
                    $this.next().css({'width': '100%'});
                })
            });
    }		
});
{/literal}
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
                        <h3 class="header smaller lighter blue">PHỤ TÙNG</h3>
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
                                    <th>Mã Thiết Bị</th>
                                    <th>Tên Thiết Bị</th>
                                    <th>Bộ Thiết Bị</th>
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