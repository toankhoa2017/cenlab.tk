{* Extend our master template *}
{extends file="master.tpl"}
{block name=css}
    <link rel="stylesheet" href="{$assets_path}plugins/colorbox/css1/colorbox.css" />
    <link rel="stylesheet" href="{$assets_path}css/chosen.min.css" />
    <link rel="stylesheet" href="{site_url()}assets/css/jquery-confirm.css" />
    <link rel="stylesheet" href="{site_url()}assets/css/stylevth.css" />
    <link rel="stylesheet" href="{$assets_path}css/ace.min.css" />
    <link rel="stylesheet" href="{site_url()}assets/css/bootstrap-treeview.css" />
    <link rel="stylesheet" href="{site_url()}assets/css/dropzone.min.css" />
    <link rel="stylesheet" href="{site_url()}assets/css/basic.min.css" />
    <link rel="stylesheet" href="{$assets_path}css/bootstrap-datepicker3.min.css" />
    <style>
        ul.list-group{
            margin-bottom: 0px;
        }
        .label.arrowed-in-right, .label.arrowed-right{
            top:10px;
        }
        .modal-lg {
            width: 90%;
        }
        .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th{
            vertical-align: middle;
        }
    </style>
{/block}
{block name=script}
    <script src="{$assets_path}plugins/colorbox/jquery.colorbox.js"></script>
    <script src="{$assets_path}plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="{site_url()}assets/js/bootstrapvalidator.min.js" /></script>
<script src="{$assets_path}plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="{site_url()}assets/js/sweetalert.min.js"></script>
<script src="{site_url()}assets/js/jquery-confirm.js"></script>
<script src="{$assets_path}js/bootstrap-datepicker.min.js"></script>
<script src="{$assets_path}js/chosen.jquery.min.js"></script>
<script src="{site_url()}assets/js/dropzone.min.js"></script>
<script src="{site_url()}assets/js/bootstrap-treeview.js"></script>
{if $privcongnhan.write OR $privcongnhan.update}
    <!-- Bootstrap modal -->
    <div class="modal fade" id="modal_form" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title"></h3>
                </div>
                <form id='form' method="post" name='set_congnhan' enctype="multipart/form-data" action='{site_url()}nenmau/congnhan/add_congnhan'>
                    <input type="hidden" value="{$id}" id="parent" name="parent" />
                    <div class="modal-body form">
                        <div class="form-group">
                            <label>{$languages.tencongnhan}:</label>
                            <input autocomplete="off" class="form-control" type="text" id="name" name="name" />
                            <input type="hidden" id="id_sua" name="id_sua">
                        </div>
                        <div class="form-group">
                            <label>{$languages.kihieu}:</label>
                            <input autocomplete="off" class="form-control" type="text" id="kihieu" name="kihieu" />
                        </div>
                        <div class="form-group">
                            <label>{$languages.ngayhethan}:</label>
                            <div class="input-group">
                                <input class="form-control date-picker" id="ngayhethan" name="ngayhethan" placeholder="{$languages.placeholder_ngayhethan}" type="text" data-date-format="dd-mm-yyyy" required="Không Được Để Trống"/>
                                <span class="input-group-addon nbd">
                                    <i class="fa fa-calendar bigger-110"></i>
                                </span>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="col-sm-12" for="form-field-1"> {$languages.anhcongnhan} </label>
                            <div class="col-sm-12">
                                <input type="file" name="congnhan" id="id-input-file" />
                            </div>
                        </div>        
                        
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="btnSave" class="btn btn-xs btn-primary">{$languages.button_them}</button>
                        <button type="submit" id="btnSua" class="btn btn-xs btn-primary">{$languages.button_sua}</button>
                        <button type="button" class="btn btn-xs btn-danger" data-dismiss="modal">{$languages.button_thoat}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
{/if}
<script type="text/javascript">
    $(".colorbox_file").colorbox({
        iframe: true, innerWidth: 950, innerHeight: 500,
        onLoad: function () {
            $("#cboxClose").text('X');
        },
        onClosed: function () {

        }
    });
    $(document).ready(function () {
        _load1122();
        $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true
        })
        $("#showhopdong").on("click", function () {
            openfile();
        });
        function check(id, name) {
            $("#hopdong").val(id);
            $("#showhopdong").text(name);
        }
        $('#id-input-file').ace_file_input({
            no_file:'No File1 ...',
            btn_choose:'Choose',
            btn_change:'Change',
            droppable:false,
            onchange:null,
            thumbnail:false //| true | large
            //whitelist:'gif|png|jpg|jpeg'
            //blacklist:'exe|php'
            //onchange:''
            //
        });
    });
    function _load1122() {
        table1122 = $('#table1122').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "{site_url()}nenmau/congnhan/ajax_list",
                "type": "POST"
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
                $('.colorbox_review').colorbox({
                    reposition: true,
                    scalePhotos: true,
                    scrolling: false,
                    maxWidth: '100%',
                    maxHeight: '100%',
                    onLoad: function () {
                        $("#cboxClose").text('X');
                    },
                    onComplete: function () {
                        $("#cboxClose").text('X');
                    }
                });
            }
        });
    }
    function _add() {
        $('#form')[0].reset();
        $("#showhopdong").text('');
        $("#hopdong").val('0');
        $('.form-group').removeClass('has-error');
        $('.help-block').empty();
        $('#modal_form').modal('show');
        $('.modal-title').text('{$languages.title_them}');
        $("#btnSua").hide();
        $("#btnSave").show();
    }
    function _xoa(id) {
        $.confirm({
            title: '{$languages.xacnhan}',
            content: '{$languages.xacnhan_xoa}',
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
                            url: "{site_url()}nenmau/congnhan/xoacongnhan",
                            data: {
                                idcongnhanxoa: id
                            },
                            datatype: "text",
                            success: function (data) {
                                if (data == 1) {
                                    reload_table1122();
                                    swal("{$languages.thanhcong}", "{$languages.xoa_success}", "success");
                                }
                            }
                        });
                    }
                },
                cancel: {
                    text: '{$languages.khong}',
                    btnClass: 'btn-danger',
                    action: function () {
                    }
                }
            }
        });
    }
    var namecu = "";
    function _sua(id, name, kihieu, ngayketthuc, file_id, file_name) {
        $('#form')[0].reset();
        $('.form-group').removeClass('has-error');
        $('.help-block').empty();
        $('#modal_form').modal('show');
        $('.modal-title').text('{$languages.title_sua}');
        $("#name").val(name);
        $("#kihieu").val(kihieu);
        $("#ngayhethan").val(ngayketthuc);
        $("#showhopdong").text(file_name);
        $("#congnhan").val(file_id);
        $("#id_sua").val(id);
        $("#btnSua").show();
        $("#btnSave").hide();
        namecu = name;
    }

    function _sua_oke() {
        var kiemtra = true;
        if ($("#name").val() === "") {
            swal("{$languages.canhbao}", "{$languages.tencongnhan_validation}", "warning");
            kiemtra = false;
        } else if ($("#kihieu").val() === "") {
            swal("{$languages.canhbao}", "{$languages.kihieu_validation}", "warning");
            kiemtra = false;
        } else if ($("#ngayhethan").val() === "") {
            swal("{$languages.canhbao}", "{$languages.ngayhethan_validation}", "warning");
            kiemtra = false;
        } else if ($("#hopdong").val() == "" || $("#hopdong").val() === "0") {
            swal("{$languages.canhbao}", "{$languages.hinhcongnhan_validation}", "warning");
            kiemtra = false;
        }
        if (kiemtra == true) {
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
                                url: "{site_url()}nenmau/congnhan/suacongnhan",
                                data: $('#form').serialize(),
                                dataType: "JSON",
                                success: function (data) {
                                    if (data == 1) {
                                        namecu = "";
                                        reload_table1122();
                                        $("#modal_form").modal("hide");
                                        swal("{$languages.thanhcong}", "{$languages.sua_success}", "success");
                                    } else {
                                        swal("{$languages.thatbai}", "{$languages.tencongnhan_error}", "error");
                                    }
                                }
                            });
                        }
                    },
                    cancel: {
                        text: '{$languages.khong}',
                        btnClass: 'btn-danger',
                        action: function () {
                        }
                    }
                }
            });
        }
    }
    function _save() {
        var kiemtra = true;
        if ($("#name").val() === "") {
            swal("{$languages.canhbao}", "{$languages.tencongnhan_validation}", "warning");
            kiemtra = false;
        } else if ($("#kihieu").val() === "") {
            swal("{$languages.canhbao}", "{$languages.kihieu_validation}", "warning");
            kiemtra = false;
        } else if ($("#ngayhethan").val() === "") {
            swal("{$languages.canhbao}", "{$languages.ngayhethan_validation}", "warning");
            kiemtra = false;
        } else if ($("#hopdong").val() == "" || $("#hopdong").val() === "0") {
            swal("{$languages.canhbao}", "{$languages.hinhcongnhan_validation}", "warning");
            kiemtra = false;
        }
        if (kiemtra == true) {
            $.ajax({
                url: "{site_url()}nenmau/congnhan/ajax_add",
                type: "POST",
                data: $('#form').serialize(),
                dataType: "JSON",
                success: function (data) {
                    if (data.status == 'denied') {
                        swal("{$languages.thatbai}", "{$languages.tencongnhan_error}", "error");
                    } else {
                        $('#modal_form').modal('hide');
                        reload_table1122();
                        swal("{$languages.thanhcong}", "{$languages.them_success}", "success");
                    }
                }
            });
        }
    }
    function reload_table1122() {
        table1122.ajax.reload(null, false);
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
                                    {if $privcongnhan.write}<button class="btn btn-xs btn-primary" onclick="_add()"><i class="ace-icon fa fa-plus"></i> {$languages.button_create}</button>{/if}
                                    <button class="btn btn-xs btn-warning" onclick="reload_table()"><i class="ace-icon fa fa-refresh"></i> {$languages.button_reload}</button>
                                </div>
                            </div>
                        </div>
                        <div class="space-2"></div>
                        {if $validate}
                            <div class="step-pane error-box">
                                <div>
                                    <div class="alert alert-danger">
                                        <button type="button" class="close" data-dismiss="alert">
                                            <i class="ace-icon fa fa-times"></i>
                                        </button>
                                        <div class="message">Vui lòng kiểm tra thông tin</div>
                                    </div>
                                </div>
                            </div>
                        {/if}
                        <table id="table1122" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>{$languages.table_1}</th>
                                    <th>{$languages.table_2}</th>
                                    <th>{$languages.table_3}</th>
                                    <th><i class="ace-icon fa fa-clock-o bigger-110 hidden-480"></i> {$languages.table_4}</th>
                                    <th style="text-align: center">{$languages.table_5}</th>
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