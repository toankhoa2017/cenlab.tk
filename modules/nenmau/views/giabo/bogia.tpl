{* Extend our master template *}
{extends file="master.tpl"}
{block name=css}
    <link rel="stylesheet" href="{site_url()}assets/css/jquery-confirm.css" />
    <link rel="stylesheet" href="{site_url()}assets/css/stylevth.css" />
    <link rel="stylesheet" href="{$assets_path}css/chosen.min.css" />
    <link rel="stylesheet" href="{$assets_path}css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style"/>
    <link rel="stylesheet" href="{$assets_path}css/ace-skins.min.css" />
    <link rel="stylesheet" href="{$assets_path}css/ace-rtl.min.css" />
{/block}
{block name=script}
    <!-- Bootstrap modal -->
    <div class="modal fade" id="modal_crud_bogia" role="dialog">
        <div class="modal-dialog" style="width:700px">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title" id="crud_title"></h3>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="nenmau_id" name="nenmau_id" value="{$nenmau[0]->nenmau_id}">
                    <form id="giabo" method="post">
                        <div class="form-group">
                            <label>{$languages.mabogia}</label>
                            <input autocomplete="off" class="form-control" type="text" id="giabo_code" name="giabo_code" />
                            <input type="hidden" id="giabo_id" name="giabo_id" />
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnSave1" onclick="_save_bogia()" class="btn btn-xs btn-primary">{$languages.button_them}</button>
                    <button type="button" id="btnSua1" onclick="_sua_bogia_oke()" class="btn btn-xs btn-primary">{$languages.button_sua}</button>
                    <button type="button" class="btn btn-xs btn-danger" data-dismiss="modal">{$languages.button_thoat}</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Bootstrap modal -->
    <script src="{$assets_path}plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="{$assets_path}plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script src="{site_url()}assets/js/sweetalert.min.js"></script>
    <script src="{site_url()}assets/js/jquery-confirm.js"></script>
    <script src="{$assets_path}js/chosen.jquery.min.js"></script>
    <script type="text/javascript">
    _load_danhsach('{$nenmau[0]->nenmau_id}');
    function _load_danhsach(id) {
        table = $('#dongia').DataTable({
            "processing": true,
            "serverSide": true,
            colResize: false,
            autoWidth: false,
            scrollX: true,
            "language": {
                "processing": "{$languages.load}",
            },
            "order": [],
            "ajax": {
                "url": "{site_url()}nenmau/danhsach_dongia",
                "data": {
                    nenmau_id: id
                },
                "type": "POST",
            },
            "columnDefs": [
                {
                    "targets": [-1], //last column
                    "orderable": false, //set not orderable
                },
            ],
            "fnRowCallback": function (nRow, mData, iDisplayIndex, iDisplayIndexFull) {
                $(nRow).find('td').each(function (i, el) {
                    $(nRow).find("td").css("width", "25%");
                })
            },
            "fnDrawCallback": function (data) {
                $(".paginate_button > a").on("focus", function () {
                    $(this).blur();
                });
                $('[data-toggle="tooltip"]').tooltip();
            },
        }
        );
    }
    function _add_bogia() {
        $('#giabo')[0].reset();
        $("#crud_title").text("{$languages.bogia_title_them}");
        $("#modal_crud_bogia").modal("show");
        $("#btnSave1").show();
        $("#btnSua1").hide();
    }
    function _save_bogia() {
        var kiemtra = true;
        if ($("#giabo_code").val() == "") {
            kiemtra = false;
            swal("{$languages.canhbao}", "{$languages.mabogia_validation}", "warning");
        }
        if (kiemtra == true) {
            $.ajax({
                type: "POST",
                url: "{site_url()}nenmau/add_bogia",
                data: {
                    nenmau_id: $("#nenmau_id").val(),
                    giabo_code: $("#giabo_code").val(),
                },
                success: function (data) {
                    if (data == 1) {
                        table.destroy();
                        _load_danhsach($("#nenmau_id").val());
                        $("#modal_crud_bogia").modal("hide");
                        swal("{$languages.thanhcong}", "{$languages.bogia_them_success}", "success");
                    } else {
                        swal("{$languages.canhbao}", "{$languages.mabogia_error}", "warning");
                    }
                }
            });
        }
    }
    function _xoa_bogia(id) {
        $.confirm({
            title: '{$languages.xacnhan}',
            content: '{$languages.bogia_xacnhan_xoa}',
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
                            url: "{site_url()}nenmau/xoa_bogia",
                            data: {
                                id_bogia: id
                            },
                            datatype: "text",
                            success: function (data) {
                                if (data == 1) {
                                    swal("{$languages.thanhcong}", "{$languages.bogia_xoa_success}", "success");
                                    table.destroy();
                                    _load_danhsach($("#nenmau_id").val());
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

    function _sua_bogia(id, code) {
        $('#giabo')[0].reset();
        $("#giabo_code").val(code);
        $("#giabo_id").val(id);
        $("#crud_title").text("{$languages.bogia_title_sua}");
        $("#modal_crud_bogia").modal("show");
        $("#btnSave1").hide();
        $("#btnSua1").show();
    }

    function _sua_bogia_oke() {
        $.confirm({
            title: '{$languages.xacnhan}',
            content: '{$languages.bogia_sua_success}',
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
                            url: "{site_url()}nenmau/sua_bogia",
                            data: {
                                'giabo_id': $("#giabo_id").val(),
                                'giabo_code': $("#giabo_code").val()
                            },
                            datatype: "text",
                            success: function (data) {
                                if (data == 1) {
                                    swal("{$languages.thanhcong}", "{$languages.bogia_sua_success}", "success");
                                    table.destroy();
                                    _load_danhsach($("#nenmau_id").val());
                                    $("#modal_crud_bogia").modal("hide");
                                } else {
                                    swal("{$languages.thatbai}", "{$languages.mabogia_error}", "error");
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

    function add_bogia(link) {
        window.location = link;
    }

    $('.chosen-select').chosen({
        allow_single_deselect: true
    });
    function _trove() {
        window.location = '{site_url()}nenmau/chitieu?nenmau={$nenmau[0]->nenmau_id}';
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
                    <li><a href="{site_url()}nenmau">{$languages.url_2}</a></li>
                    <li><a href="{site_url()}nenmau/chitieu?nenmau={$nenmau[0]->nenmau_id}">{$languages.url_3} {$nenmau[0]->nenmau_name}</a></li>
                    <li class="active">{$languages.url_4}</li>
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
                        <h3 class="header smaller lighter blue">{$languages.bogia_title} {$nenmau[0]->nenmau_name}</h3>
                        <div class="row">
                            <div class="col-xs-3">
                                <div class="pull-left">
                                    <button class="btn btn-xs btn-primary" onclick="_add_bogia()"><i class="ace-icon fa fa-plus"></i> {$languages.bogia_button_create}</button>
                                    <button class="btn btn-xs btn-danger" onclick="_trove()"><i class="ace-icon fa fa-reply icon-only"></i> {$languages.bogia_button_trove}</button>
                                </div>
                            </div>
                            <div class="col-xs-9" style="text-align:right">
                                {$capbac_nenmau}
                            </div>
                        </div>
                        <div class="space-2"></div>
                        <table id="dongia" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>{$languages.bogia_table_1}</th>
                                    <th>{$languages.bogia_table_2}</th>
                                    <th>{$languages.bogia_table_3}</th>
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