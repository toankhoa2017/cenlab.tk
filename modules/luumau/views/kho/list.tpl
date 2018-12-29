{* Extend our master template *}
{extends file="master.tpl"}
{block name=css}
    <link rel="stylesheet" href="{site_url()}assets/css/jquery-confirm.css" />
    <link rel="stylesheet" href="{site_url()}assets/css/stylevth.css" />
{/block}
{block name=script}
    {if $privcheck.write OR $privcheck.update}
    <!-- Bootstrap modal -->
    <div class="modal fade" id="modal_form" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title"></h3>
                </div>
                <form id="form" method="post">
                    <input type="hidden" value="0" id="parent" name="parent"/>
                    <div class="modal-body form">
                        <div class="form-group">
                            <label>{$languages.input1}:</label>
                            <input autocomplete="off" class="form-control" type="text" id="kho_name" name="kho_name" />
                            <input type="hidden" id="kho_id" name="kho_id">
                        </div>
                        <div class="form-group">
                            <label>{$languages.input2}:</label>
                            <textarea class="form-control" id="kho_mota" name="kho_mota" placeholder=""></textarea>
                        </div>
                        <div class="form-group">
                            <label>{$languages.input3}:</label>
                            <select class="form-control" id="kho_loai" name="kho_loai">
                                <option value="0">Không Phải Thiết Bị</option>
                                <option value="1">Thiết Bị</option>
                            </select>
                        </div>
                        <div class="form-group" id="mathietbi_check" style="display: none">
                            <label>{$languages.input4}:</label>
                            <input type="text" name="thietbi_id" id="thietbi_id" class="form-control">
                        </div>
                        {if $kho_info[0]->kho_loai eq ""}
                        <div class="form-group">
                            <label>{$languages.input5}:</label>
                            <input type="number" name="kho_max_level" id="kho_max_level" class="form-control">
                        </div>
                        {/if}
                        {if $kho_info[0]->kho_max_level eq 1}
                        <div class="form-group">
                            <label>{$languages.input6}:</label>
                            <input type="number" name="soluong" id="soluong" class="form-control">
                        </div>
                        {/if}
                        <input type="hidden" class="form-control" id="donvi_id" name="donvi_id" value="{$donvi_id}">
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnSave" onclick="_save()" class="btn btn-xs btn-primary">{$languages.button_them}</button>
                        <button type="button" id="btnSua" onclick="_sua_oke()" class="btn btn-xs btn-primary">{$languages.button_sua}</button>
                        <button type="button" class="btn btn-xs btn-danger" data-dismiss="modal">{$languages.button_thoat}</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Bootstrap modal -->
    {/if}
    <script src="{$assets_path}plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="{$assets_path}plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script src="{site_url()}assets/js/sweetalert.min.js"></script>
    <script src="{site_url()}assets/js/jquery-confirm.js"></script>
    <script>
                            _load();
                            function _load() {
                                table = $('#table').DataTable({
                                    "processing": true,
                                    "serverSide": true,
                                    "order": [],
                                    "ajax": {
                                        "url": "{site_url()}luumau/kho/ajax_list",
                                        "type": "POST",
                                        "data": {
                                            'id': '{$kho_id}'
                                        }
                                    },
                                    "columnDefs": [
                                        {
                                            "targets": [-1],
                                            "orderable": false,
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
                            $("#kho_loai").on("change", function () {
                                if ($(this).val() == '1') {
                                    $("#mathietbi_check").show();
                                } else {
                                    $("#mathietbi_check").hide();
                                }
                            });
                            function _add() {
                                $('#form')[0].reset();
                                $('#modal_form').modal('show');
                                $('.modal-title').text('{$languages.title_create}');
                                $("#btnSua").hide();
                                $("#btnSave").show();
                                $("#parent").val("{$kho_id}");
                                if ({$kho_id} != '0') {
                                    $("#kho_loai").val('{$kho_info[0]->kho_loai}');
                                    if ('{$kho_info[0]->kho_loai}' == '1') {
                                        $("#mathietbi_check").show();
                                    } else {
                                        $("#mathietbi_check").hide();
                                    }
                                    ;
                                    $("#thietbi_id").val('{$kho_info[0]->thietbi_id}');
                                    $("#donvi_id").val('{$kho_info[0]->donvi_id}');
                                }
                            }
                            function _save() {
                                if ($("#kho_name").val() == "") {
                                    swal("{$languages.canhbao}", "{$languages.input1_validation}", "warning");
                                } else if ($("#kho_loai").val() === 1) {
                                    if ($("#thietbi_id").val() === "") {
                                        swal("{$languages.canhbao}", "{$languages.input4_validation}", "warning");
                                    }
                                } else {
                                    $.ajax({
                                        url: "{site_url()}luumau/kho/ajax_add",
                                        type: "POST",
                                        data: $('#form').serialize(),
                                        dataType: "JSON",
                                        success: function (data) {
                                            if (data == '2') {
                                                swal("{$languages.thatbai}", "{$languages.input1_validation_error}", "error");
                                            } else {
                                                reload_table();
                                                $('#modal_form').modal('hide');
                                                swal("{$languages.thanhcong}", "{$languages.xacnhan_create_success}", "success");
                                            }
                                        }
                                    });
                                }
                            }

                            function _sua(kho_id, kho_name, kho_mota, kho_loai, thietbi_id) {
                                $("#kho_id").val(kho_id);
                                $("#kho_name").val(kho_name);
                                $("#kho_mota").val(kho_mota);
                                $("#kho_loai").val(kho_loai);
                                if (kho_loai === '1') {
                                    $("#mathietbi_check").show();
                                } else {
                                    $("#mathietbi_check").hide();
                                }
                                $("#thietbi_id").val(thietbi_id);
                                //$("#donvi_id").val(donvi_id);
                                $('#modal_form').modal('show');
                                $('.modal-title').text('{$languages.title_update}');
                                $("#btnSua").show();
                                $("#btnSave").hide();
                            }

                            function _sua_oke() {
                                if ($("#kho_name").val() == "") {
                                    swal("{$languages.canhbao}", "{$languages.input1_validation}", "warning");
                                } else if ($("#kho_loai").val() === 1) {
                                    if ($("#thietbi_id").val() === "") {
                                        swal("{$languages.canhbao}", "{$languages.input4_validation}", "warning");
                                    }
                                } else {
                                    $.confirm({
                                        title: '{$languages.xacnhan}',
                                        content: '{$languages.xacnhan_update}',
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
                                                        url: "{site_url()}luumau/kho/suakho",
                                                        data: {
                                                            kho_id: $("#kho_id").val(),
                                                            kho_name: $("#kho_name").val(),
                                                            kho_mota: $("#kho_mota").val(),
                                                            kho_loai: $("#kho_loai").val(),
                                                            thietbi_id: $("#thietbi_id").val(),
                                                            donvi_id: $("#donvi_id").val(),
                                                        },
                                                        datatype: "text",
                                                        success: function (data) {
                                                            if (data == 1) {
                                                                reload_table();
                                                                $("#modal_form").modal("hide");
                                                                swal("{$languages.thanhcong}", "{$languages.xacnhan_update_success}", "success");
                                                            } else {
                                                                swal("{$languages.thatbai}", "{$languages.input1_validation_error}", "error");
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
                                $.confirm({
                                    title: '{$languages.xacnhan}',
                                    content: '{$languages.xacnhan_delete}',
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
                                                    url: "{site_url()}luumau/kho/xoakho",
                                                    data: {
                                                        kho_id: id
                                                    },
                                                    datatype: "text",
                                                    success: function (data) {
                                                        if (data == 1) {
                                                            reload_table();
                                                            swal("{$languages.thanhcong}", "{$languages.xacnhan_delete_success}", "success");
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
    </script>
{/block}
{block name=body}
    <div class="main-content">
        <div class="main-content-inner">
            <!--PATH BEGINS-->
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li><i class="ace-icon fa fa-home home-icon"></i><a href="#">{$languages.url_1}</a></li>
                    <li><a href="#">{$languages.url_2}</a></li>
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
                        <h3 class="header smaller lighter blue">{$languages.title} {$kho_info[0]->kho_name}</h3>
                        <div class="clearfix">
                            <div class="pull-left tableTools-container"></div>
                        </div>

                        <div class="row">
                            <div class="col-xs-6">
                                <div class="pull-left">
                                    {if $privcheck.write}<button class="btn btn-xs btn-primary" onclick="_add()"><i class="ace-icon fa fa-plus"></i> {$languages.button_create}</button>{/if}
                                    <button class="btn btn-xs btn-warning" onclick="reload_table()"><i class="ace-icon fa fa-refresh"></i> {$languages.button_reload}</button>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="pull-right">
                                    {$ref}
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
                                    <th style="text-align: center">{$languages.table_6}</th>
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