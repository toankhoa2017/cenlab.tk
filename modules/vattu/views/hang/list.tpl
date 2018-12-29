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
                            <label>{$languages.input_1}</label>
                            <input autocomplete="off" class="form-control" type="text" id="hang_name" name="hang_name" />
                            <input type="hidden" id="hang_id" name="hang_id">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnSave" onclick="_save()" class="btn btn-xs btn-primary">{$languages.button_success_add}</button>
                        <button type="button" id="btnSua" onclick="_sua_oke()" class="btn btn-xs btn-primary">{$languages.button_success_update}</button>
                        <button type="button" class="btn btn-xs btn-danger" data-dismiss="modal">{$languages.button_cancel}</button>
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
            "order": [],
            "ajax": {
                "url": "{site_url()}vattu/hang/ajax_list",
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
        $('.modal-title').text('{$languages.title_add}'); // Set Title to Bootstrap modal title
        $("#btnSua").hide();
        $("#btnSave").show();
    }
    
    function _sua(hang_id, hang_name) {
        $('#form')[0].reset(); // reset form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string
        $('#modal_form').modal('show'); // show bootstrap modal
        $('.modal-title').text('{$languages.title_update}');
        $("#hang_id").val(hang_id);
        $("#hang_name").val(hang_name);
        $("#btnSua").show();
        $("#btnSave").hide();
    }

    function _sua_oke() {
        if ($("#hang_name").val() == "") {
            swal("{$languages.confirm_title_warning}", "{$languages.input_1_validation}", "warning");
        } else {
            $.confirm({
                title: '{$languages.confirm_title}',
                content: '{$languages.confirm_update}',
                icon: 'fa fa-question',
                theme: 'modern',
                closeIcon: true,
                autoClose: 'cancel|10000',
                animation: 'scale',
                type: 'orange',
                buttons: {
                    '{$languages.confirm_button_yes}': {
                        btnClass: 'btn-primary',
                        action: function () {
                            $.ajax({
                                url: "{site_url()}vattu/hang/suahangsanxuat",
                                type: "POST",
                                data: {
                                    'hang_id' : $("#hang_id").val(),
                                    'hang_name' : $("#hang_name").val(),
                                },
                                datatype: "text",
                                success: function (data) {
                                    if (data == 1) {
                                        namecu = "";
                                        reload_table();
                                        $("#modal_form").modal("hide");
                                        swal("{$languages.confirm_title_success}", "{$languages.confirm_update_success}", "success");
                                    } else {
                                        swal("{$languages.confirm_title_error}", "{$languages.confirm_update_error}", "error");
                                    }
                                }
                            });
                        }
                    },
                    cancel: {
                        text: '{$languages.confirm_button_no}',
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
        if ($("#hang_name").val() == "") {
            swal("{$languages.confirm_title_warning}", "{$languages.input_1_validation}", "warning");
        } else {
            $.ajax({
                url: "{site_url()}vattu/hang/ajax_add",
                type: "POST",
                data: $('#form').serialize(),
                dataType: "JSON",
                success: function (data) {
                    //if success close modal and reload ajax table
                    if (data.status == 'denied') {
                        swal("{$languages.confirm_title_error}", "{$languages.confirm_add_error}", "error");
                    } else {
                        $('#modal_form').modal('hide');
                        reload_table();
                        swal("{$languages.confirm_title_success}", "{$languages.confirm_add_success}", "success");
                    }
                }
            });
        }
    }
    function reload_table() {
        table.ajax.reload(null, false); //reload datatable ajax 
    }
	$('#table').on('click','.delete',function() {
		var id = $(this).parents('tr').find('.name_hang').data('id');
		if(confirm("Bạn có chắc chắn muốn xóa")) {
			$.ajax({
				url: "{site_url()}vattu/hang/delete",
				type: "POST",
				data: { id : id },
				dataType:"JSON",
				success: function(data) {
					if(data.code == '100') {
						reload_table();
					}
				},
				error: function (jqXHR, textStatus, errorThrown) {
                	alert('không thể xóa')
            	}
			});
		}
	});
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
                                    <button class="btn btn-xs btn-primary" onclick="_add()"><i class="ace-icon fa fa-plus"></i> {$languages.button_add}</button>
                                    <button class="btn btn-xs btn-warning" onclick="reload_table()"><i class="ace-icon fa fa-refresh"></i> {$languages.button_reload}</button>
                                </div>
                            </div>
                        </div>
                        <div class="space-2"></div>
                        <table id="table" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>{$languages.table_1}</th>
                                    <th>{$languages.table_2}</th>
                                    <th style="text-align: center">{$languages.table_3}</th>
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