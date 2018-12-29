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
    {if $privcheck.write OR $privcheck.update}
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
                                <label>{$languages.themchat_input_1}</label>
                                {*<input class="form-control" autocomplete="off" type="text" id="name" name="name" />
                                <div id="goiy_chat"  style="position: absolute;z-index: 999;width: 96%;display: none"></div>
                                <input class="form-control" type="hidden" id="name_id" name="name_id" />*}
                                <input type="hidden" id="thitruong" name="thitruong">
                                <input type="hidden" id="id_sua" name="id_sua" value="0">
                                <select class="chosen-select form-control" id="name_id" name="name_id">

                                </select>
                            </div>
                            <div class="form-group">
                                <label class="don-vi-tinh" style="display: none"></label>
                            </div>    
                            <div class="form-group">
                                <label>{$languages.themchat_input_2}</label>
                                <input class="form-control" type="number" id="start" name="start" />
                            </div>
                            <div class="form-group">
                                <label>{$languages.themchat_input_3}</label>
                                <input class="form-control" type="number" id="end" name="end" />
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSave" onclick="_save()" class="btn btn-xs btn-primary">{$languages.themchat_button_them}</button>
                            <button type="button" id="btnSua" onclick="_sua_oke()" class="btn btn-xs btn-primary">{$languages.themchat_button_sua}</button>
                            <button type="button" class="btn btn-xs btn-danger" data-dismiss="modal">{$languages.themchat_button_thoat}</button>
                        </div>
                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    {/if}
    <!-- End Bootstrap modal -->
    <script src="{$assets_path}plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="{$assets_path}plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script src="{site_url()}assets/js/sweetalert.min.js"></script>
    <script src="{site_url()}assets/js/jquery-confirm.js"></script>
    <script src="{$assets_path}js/chosen.jquery.min.js"></script>
    <script src="{$assets_path}js/ace-elements.min.js"></script>
    <script src="{$assets_path}js/ace.min.js"></script>
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
                                            "url": "{site_url()}nenmau/thitruong/ajax_list_chat",
                                            "type": "POST",
                                            "data": {
                                                id_thitruong: {$id_thitruong[0]->thitruong_id},
                                            },
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
                                    $('.chosen-select', this).chosen('destroy').chosen();
                                });
                                function _load_ds_chat(id) {
                                    $.ajax({
                                        type: "POST",
                                        url: "{site_url()}nenmau/thitruong/load_ds_chat",
                                        data: {
                                            id_thitruong: {$id_thitruong[0]->thitruong_id},
                                            id_sua: id,
                                        },
                                        datatype: "text",
                                        success: function (data) {
                                            $("#name_id").html('');
                                            $("#name_id").append(data);
                                            $('#name_id').val(id).trigger('chosen:updated');
                                            $("#name_id").trigger("chosen:updated");
                                        }
                                    });
                                }
                                function _add() {
                                    _load_ds_chat(0);
                                    $("#id_sua").val('0');
                                    $('#form')[0].reset(); // reset form on modals
                                    $('.form-group').removeClass('has-error'); // clear error class
                                    $('.help-block').empty(); // clear error string
                                    $('#modal_form').modal('show'); // show bootstrap modal
                                    $('.modal-title').text('{$languages.themchat_title_them}'); // Set Title to Bootstrap modal title
                                    $("#thitruong").val('{$id_thitruong[0]->thitruong_id}');
                                    $("#btnSua").hide();
                                    $("#btnSave").show();
                                }
                                function _xoa(id_thitruong, id_chat) {
                                    $.confirm({
                                        title: '{$languages.xacnhan}',
                                        content: '{$languages.themchat_xacnhan_xoa}',
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
                                                        url: "{site_url()}nenmau/thitruong/xoathitruong",
                                                        data: {
                                                            idthitruong: id_thitruong,
                                                            idchat: id_chat
                                                        },
                                                        datatype: "text",
                                                        success: function (data) {
                                                            if (data == 1) {
                                                                reload_table();
                                                                swal("{$languages.thanhcong}", "{$languages.themchat_xoa_success}", "success");
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
                                var namecu = "";
                                function _sua(id_chat, start, end) {
                                    $('#form')[0].reset(); // reset form on modals
                                    $('.form-group').removeClass('has-error'); // clear error class
                                    $('.help-block').empty(); // clear error string
                                    $('#modal_form').modal('show'); // show bootstrap modal
                                    $('.modal-title').text('{$languages.themchat_title_sua}');
                                    $("#start").val(start);
                                    $("#end").val(end);
                                    $("#id_sua").val(id_chat);
                                    $("#btnSua").show();
                                    $("#btnSave").hide();
                                    $("#thitruong").val('{$id_thitruong[0]->thitruong_id}');
                                    _load_ds_chat(id_chat);
                                    //$('#name_id').val(6).trigger('chosen:updated');
                                }

                                function _sua_oke() {
                                    var kiemtra = true;
                                    if ($("#start").val() == "" && $("#end").val() == "") {
                                        swal("{$languages.canhbao}", "{$languages.themchat_start_end}", "warning");
                                        kiemtra = false;
                                        $("#strat").focus();
                                    }
                                    if (parseInt($("#start").val()) > parseInt($("#end").val())) {
                                        swal("{$languages.canhbao}", "Giá trị bắt đầu phải nhỏ hơn giá trị kết thúc", "warning");
                                        kiemtra = false;
                                        $("#strat").focus();
                                    }
                                    if (kiemtra == true) {
                                        $.confirm({
                                            title: '{$languages.xacnhan}',
                                            content: '{$languages.themchat_xacnhan_sua}',
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
                                                            url: "{site_url()}nenmau/thitruong/suathitruong",
                                                            data: {
                                                                idthitruongsua: $("#thitruong").val(),
                                                                idchatcu: $("#id_sua").val(),
                                                                idchatmoi: $("#name_id").val(),
                                                                start: $("#start").val(),
                                                                end: $("#end").val(),
                                                            },
                                                            datatype: "text",
                                                            success: function (data) {
                                                                if (data == 1) {
                                                                    $('#modal_form').modal('hide');
                                                                    reload_table();
                                                                    $("#id_sua").val('0');
                                                                    swal("{$languages.thanhcong}", "{$languages.themchat_sua_success}", "success");
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
                                function _save() {
                                    var kiemtra = true;
                                    if ($("#start").val() == "" && $("#end").val() == "") {
                                        swal("{$languages.canhbao}", "{$languages.themchat_start_end}", "warning");
                                        kiemtra = false;
                                        $("#strat").focus();
                                    }
                                    if (parseInt($("#start").val()) > parseInt($("#end").val())) {
                                        swal("{$languages.canhbao}", "Giá trị bắt đầu phải nhỏ hơn giá trị kết thúc", "warning");
                                        kiemtra = false;
                                        $("#strat").focus();
                                    }
                                    if (kiemtra == true) {
                                        $.ajax({
                                            url: "{site_url()}nenmau/thitruong/ajax_add_chat",
                                            type: "POST",
                                            data: $('#form').serialize(),
                                            dataType: "JSON",
                                            success: function (data) {
                                                $('#modal_form').modal('hide');
                                                reload_table();
                                                swal("{$languages.thanhcong}", "{$languages.themchat_them_success}", "success");
                                            }
                                        });
                                    }
                                }
                                function reload_table() {
                                    table.ajax.reload(null, false); //reload datatable ajax 
                                }
                                function re_direct(id_thitruong) {
                                    window.location = "{site_url()}nenmau/thitruong/chitietthitruong/" + id_thitruong;
                                }
                                function trove() {
                                    window.location = "{site_url()}nenmau/thitruong";
                                }
                                $("#name").keyup(function (evt) {
                                    $("#name_id").val('0');
                                    $.ajax({
                                        type: "POST",
                                        url: "{site_url()}nenmau/thitruong/goiy_chat",
                                        data: 'key=' + $(this).val(),
                                        success: function (data) {
                                            $("#goiy_chat").show();
                                            $("#goiy_chat").html(data);
                                            $("#name").css("background", "#FFF");
                                            if (evt.keyCode === 40) { // press down
                                                $('div#goiy_chat').on('focus', 'li', function () {
                                                    var $this = $(this);
                                                    $this.closest('div#goiy_chat').scrollTop($this.index() * $this.outerHeight());
                                                }).on('keydown', 'li', function (e) {
                                                    var $this = $(this);
                                                    if (e.keyCode === 40) { // press down
                                                        $this.next().focus();
                                                        return false;
                                                    } else if (e.keyCode === 38) { // press up
                                                        $this.prev().focus();
                                                        return false;
                                                    } else if (e.keyCode === 13) { // press enter
                                                        //alert($(this).val());
                                                        goiy_chat($(this).html());
                                                    } else if (e.keyCode === 27) { // press escape
                                                        $("#goiy_chat").hide();
                                                        $("#name").focus();
                                                        return false;
                                                    }
                                                }).find('li').first().focus();
                                                return false;
                                            } else { // press escape
                                                $('div#goiy_chat').on('focus', 'li', function () {
                                                    var $this = $(this);
                                                    $this.closest('div#goiy_chat').scrollTop($this.index() * $this.outerHeight());
                                                }).on('click', 'li', function (e) {
                                                    goiy_chat($(this).html());
                                                });
                                                return false;
                                            }
                                        }
                                    });
                                });
                                function goiy_chat(val) {
                                    $("#name").val(val);
                                    $("#goiy_chat").hide();
                                }
                                function chat_check(id) {
                                    $("#name_id").val(id);
                                }
                                $("#name_id").on("change", function(){
                                    var optionSelected = $("option:selected", this);
                                    $(".don-vi-tinh").show().text("Đơn vị tính: " + optionSelected.attr("dvtinh"));
                                })
    </script>
{/block}
{block name=body}
    <div class="main-content">
        <div class="main-content-inner">
            <!--PATH BEGINS-->
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li><i class="ace-icon fa fa-home home-icon"></i><a href="#">{$languages.url_1}</a></li>
                    <li><a href="{site_url()}nenmau/thitruong">{$languages.url_2}</a></li>
                    <li class="active">{$languages.url_3} {$id_thitruong[0]->thitruong_name}</li>
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
                        <h3 class="header smaller lighter blue">{$languages.themchat_title} {$id_thitruong[0]->thitruong_name}</h3>
                        <div class="clearfix">
                            <div class="pull-left tableTools-container"></div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12">
                                <div class="pull-left">
                                    {if $privcheck.write}<button class="btn btn-xs btn-primary" onclick="_add()"><i class="ace-icon fa fa-plus"></i> {$languages.themchat_button_create}</button>{/if}
                                    <button class="btn btn-xs btn-warning" onclick="reload_table()"><i class="ace-icon fa fa-refresh"></i> {$languages.themchat_button_reload}</button>
                                    <button class="btn btn-xs btn-danger" onclick="trove()"><i class="ace-icon fa fa-refresh"></i> {$languages.themchat_button_trove}</button>
                                </div>
                            </div>
                        </div>
                        <div class="space-2"></div>
                        <table id="table" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>{$languages.themchat_table_1}</th>
                                    <th>{$languages.themchat_table_2}</th>
                                    <th>{$languages.themchat_table_3}</th>
                                    <th>{$languages.themchat_table_4}</th>
                                    <th style="text-align: center">{$languages.themchat_table_5}</th>
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