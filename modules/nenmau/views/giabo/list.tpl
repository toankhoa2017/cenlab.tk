{* Extend our master template *}
{extends file="master.tpl"}
{block name=css}
    <link rel="stylesheet" href="{site_url()}assets/css/jquery-confirm.css" />
    <link rel="stylesheet" href="{site_url()}assets/css/stylevth.css" />
    <link rel="stylesheet" href="{$assets_path}css/bootstrap-duallistbox.min.css" />
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
                            <select multiple="multiple" size="18" name="dongia[]" id="package_code">
                                {foreach from=$dongia key=k item=v}
                                    <option value="{$k}">{$v}</li>
                                    {/foreach}
                            </select>
                        </div>
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
    <script src="{$assets_path}plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="{$assets_path}plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script src="{site_url()}assets/js/sweetalert.min.js"></script>
    <script src="{site_url()}assets/js/jquery-confirm.js"></script>
    <script src="{$assets_path}js/jquery.bootstrap-duallistbox.min.js"></script>
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
                "url": "{site_url()}nenmau/giabo/ajax_list",
                "type": "POST",
                "data": {
                    'giabo_id': '{$giabo_id}'
                }
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
    $('#modal_form').on('shown.bs.modal', function () {
        $('select[name="dongia[]"]').bootstrapDualListbox({
            filterTextClear: 'show all',
            filterPlaceHolder: 'Filter',
            moveSelectedLabel: 'Move selected',
            moveAllLabel: 'Move all',
            removeSelectedLabel: 'Remove selected',
            removeAllLabel: 'Remove all',
        });
        $('select[name="dongia[]"]').bootstrapDualListbox('refresh', true);
    })

    function reload_dongia() {
        $.ajax({
            type: "POST",
            url: "{site_url()}nenmau/giabo/load_bogia",
            data: {
                'nenmau_id': {$nenmau[0]->nenmau_id},
                'giabo_id': {$giabo_id},
            },
            datatype: "text",
            success: function (data) {
                console.log(data);
                $("#package_code").html('');
                $("#package_code").append(data);
            }
        });
    }

    function _add() {
        reload_dongia();
        $('#form')[0].reset(); // reset form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string
        $('#modal_form').modal('show'); // show bootstrap modal
        $('.modal-title').text('{$languages.title_modal}'); // Set Title to Bootstrap modal title
        $("#btnSua").hide();
        $("#btnSave").show();
    }
    function _xoa_giabo(id) {
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
                            url: "{site_url()}nenmau/giabo/xoa_bogia",
                            data: {
                                'package_code': id,
                                'giabo_id': '{$giabo_id}'
                            },
                            datatype: "text",
                            success: function (data) {
                                if (data == 1) {
                                    reload_table();
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
                        // lets the user close the modal.
                    }
                }
            }
        });
    }
    function _save() {
        $.confirm({
            title: '{$languages.xacnhan}',
            content: '{$languages.xacnhan_them} {$giabo_code}?',
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
                            url: "{site_url()}nenmau/giabo/add_bogia",
                            data: {
                                'package_code': $('[name="dongia[]"]').val(),
                                'giabo_id': '{$giabo_id}'
                            },
                            datatype: "text",
                            success: function (data) {
                                if (data == 1) {
                                    reload_table();
                                    swal("{$languages.thanhcong}", "{$languages.them_success}", "success");
                                    $("#modal_form").modal("hide");
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
    function reload_table() {
        table.ajax.reload(null, false); //reload datatable ajax 
    }
    function _trove() {
        window.location="{site_url()}nenmau/bogia/{$nenmau[0]->nenmau_id}";
    }
    </script>
{/block}
{block name=body}
    <div class="main-content">
        <div class="main-content-inner">
            <!--PATH BEGINS-->
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li><i class="ace-icon fa fa-home home-icon"></i><a href="{site_url()}nenmau">{$languages.url_1}</a></li>
                    <li><a href="{site_url()}nenmau">{$languages.url_2}</a></li>
                    <li><a href="{site_url()}nenmau/chitieu?nenmau={$nenmau[0]->nenmau_id}">{$languages.url_3} {$nenmau[0]->nenmau_name}</a></li>
                    <li><a href="{site_url()}nenmau/bogia/{$nenmau[0]->nenmau_id}">{$languages.url_4}</a></li>
                    <li class="active">{$languages.url_5} {$giabo_code}</li>
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
                        <h3 class="header smaller lighter blue">{$languages.title} {$giabo_code}</h3>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="pull-left">
                                    <button class="btn btn-xs btn-primary" onclick="_add()"><i class="ace-icon fa fa-plus"></i> {$languages.button_create}</button>
                                    <button class="btn btn-xs btn-danger" onclick="_trove()"><i class="ace-icon fa fa-reply icon-only"></i> {$languages.button_trove}</button>
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
                                    <th style="text-align: center">{$languages.table_5}</th>
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