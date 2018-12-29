	{* Extend our master template *}
{extends file="master.tpl"}
{block name=css}
    <link rel="stylesheet" href="{site_url()}assets/css/jquery-confirm.css" />
    <link rel="stylesheet" href="{site_url()}assets/css/stylevth.css" />
    <link rel="stylesheet" href="{$assets_path}css/chosen.min.css" />
    <link rel="stylesheet" href="{$assets_path}css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style"/>
    <link rel="stylesheet" href="{$assets_path}css/ace-skins.min.css" />
    <link rel="stylesheet" href="{$assets_path}css/ace-rtl.min.css" />
    <style>
        body {
            overflow-x: hidden;
        }
        .ui-autocomplete {
            max-height: 200px;
            overflow-y: auto;
            overflow-x: hidden;
            padding-right: 20px;
            position: absolute; cursor: default;z-index:30000 !important;
        }
    </style>
{/block}
{block name=script}
    {if $privchitieu.write OR $privchitieu.update}
        <!-- Bootstrap modal -->
        <div class="modal fade" id="chat_form" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h3 class="modal-title title-chat"></h3>
                    </div>
                    <form id="form_chat" method="post">
                        <div class="modal-body form">
                            <div class="form-group">
                                <label>{$languages.chat_tenchat}</label>
                                <input autocomplete="off" class="form-control" type="text" id="chat" name="chat" />
                                <input type="hidden" value="" id="chitieu_chat_id" name="chitieu_chat_id" />
                                <input type="hidden" value="" id="chat_id" name="chat_id" />
                            </div>
                            <div class="form-group">
                                <label>{$languages.chat_tenchat_eng}</label>
                                <input autocomplete="off" class="form-control" type="text" id="chat_eng" name="chat_eng" />
                            </div>
                            <div class="form-group">
                                <label>{$languages.chat_mota}</label>
                                <input autocomplete="off" class="form-control" type="text" id="mota_chat" name="mota_chat" />
                            </div>
                            <div class="form-group">
                                <label>{$languages.chat_noicongnhan}</label>
                                <input type="hidden" value="" id="congnhan_id" name="congnhan_id" />
                                <select multiple="" class="chosen-select form-control" id="congnhan">
                                    {foreach from=$noicongnhan key=k item=v}
                                        <option value="{$k}">{$v}</option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label>{$languages.chat_capacity}</label>
                                    <select class="chosen-select form-control" id="capacity" name="capacity">
                                        <option value="1">LOD/LOQ</option>
                                        <option value="2">MIN/MAX</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label id="text-capacity1">LOD</label>
                                    <input autocomplete="off" class="form-control" type="number" id="lod" name="lod" />
                                </div>
                                <div class="col-md-6">
                                    <label id="text-capacity2">LOQ</label>
                                    <input autocomplete="off" class="form-control" type="number" id="loq" name="loq" />
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSave_chat" onclick="_save_chat()" class="btn btn-xs btn-primary">{$languages.button_them}</button>
                            <button type="button" id="btnSua_chat" onclick="_sua_chat_oke()" class="btn btn-xs btn-primary">{$languages.button_sua}</button>
                            <button type="button" class="btn btn-xs btn-danger" data-dismiss="modal">{$languages.button_thoat}</button>
                        </div>
                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
    {/if}
    {if $privdinhgia.write OR $privdinhgia.update}
        <!-- Bootstrap modal -->
        <div class="modal fade" id="gia_form" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h3 class="modal-title title-gia"></h3>
                    </div>
                    <input type="hidden" value="" id="id_chitieu_gia" name="id_chitieu_gia"/>
                    <div class="modal-body">
                        <form id="gia_form_1">
                            <div class="form-group">
                                <label>{$languages.giachitieu}</label>
                                <input autocomplete="off" class="form-control" type="text" id="giatien" name="giatien" />
                                <input class="form-control" type="hidden" id="gia_chitieu" name="gia_chitieu" />
                                <input class="form-control" type="hidden" id="gia_nenmau" name="gia_nenmau" />                        </div>
                            <script>
                                $(function () {
                                    $('input[name=giatien]').number(true, 0);
                                });
                            </script>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" onclick="_gia()" class="btn btn-xs btn-primary">{$languages.button_capnhat}</button>
                        <button type="button" class="btn btn-xs btn-danger" data-dismiss="modal">{$languages.button_thoat}</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
    {/if}
    {if $privchitieu.write OR $privchitieu.update}
        <!-- Bootstrap modal -->
        <div class="modal fade" id="chitieu_form" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h3 class="modal-title title-chitieu"></h3>
                    </div>
                    <form id="form" method="post">
                        <input type="hidden" value="" id="chitieu_sua" name="chitieu_sua" />
                        <div class="modal-body form">
                            <input type="hidden" id="nenmau" name="nenmau" value="{$nenmau_selected}">
                            <div class="form-group">
                                <label>{$languages.chitieu_tenchitieu}</label>
                                <input class="form-control" autocomplete="off" type="text" id="chitieu" name="chitieu" />
                                <input class="form-control" type="hidden" id="chitieu_id" name="chitieu_id"/>
                                <input type="hidden" value="" id="chitieu_cu" name="chitieu_cu" />
                            </div>
                            <div class="form-group">
                                <label>{$languages.chitieu_tenchitieu_eng}</label>
                                <input class="form-control" autocomplete="off" type="text" id="chitieu_eng" name="chitieu_eng" />
                            </div>
                            <div class="form-group">
                                <label>{$languages.chitieu_mota}</label>
                                <input class="form-control" type="text" id="mota" name="mota" />
                            </div>
                            <div class="form-group">
                                <label>{$languages.chitieu_thoigianluumau}</label>
                                <input autocomplete="off" class="form-control" type="number" min="0" id="time_luu" name="time_luu" />
                            </div>
                            <div class="form-group">
                                <label>{$languages.chitieu_phuongphap}</label>
                                <select class="chosen-select form-control" id="phuongphap_id">
                                    <option value="0">{$languages.chitieu_chosen_phuongphap}</option>
                                    {foreach from=$phuongphap key=k item=v}
                                        <option value="{$k}">{$v}</option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="form-group">
                                <label>{$languages.chitieu_kythuat}</label>
                                <input class="form-control" autocomplete="off" type="text" id="kythuat" name="kythuat" />
                                <input class="form-control" type="hidden" id="kythuat_id" name="kythuat_id" value="0"/>
                            </div>
                            <input type="hidden" id="phongthinghiem" name="phongthinghiem" value="{$user_info['ssAdminDonvi']}">
                            <div class="form-group">
                                <label>{$languages.chitieu_donvitinh}</label>
                                <select class="chosen-select form-control" id="donvi">
                                    <option value="0">{$languages.chitieu_chosen_donvitinh}</option>
                                    {foreach from=$donvi key=k item=v}
                                        <option value="{$k}">{$v}</option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="form-group">
                                <label>{$languages.chitieu_thoigianthuchien}</label>
                                <input class="form-control" type="text" id="tat" name="tat" />
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSave" onclick="_save()" class="btn btn-xs btn-primary">{$languages.chitieu_button_them}</button>
                            <button type="button" id="btnSua" onclick="_sua_oke()" class="btn btn-xs btn-primary">{$languages.chitieu_button_sua}</button>
                            <button type="button" class="btn btn-xs btn-danger" data-dismiss="modal">{$languages.chitieu_button_thoat}</button>
                        </div>
                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
    {/if}
    <!-- End Bootstrap modal -->
    <script src="{$assets_path}plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="{$assets_path}js/jquery.dataTables.min.js"></script>
    <script src="{$assets_path}js/jquery.dataTables.bootstrap.min.js"></script>
    <script src="{$assets_path}js/ace-elements.min.js"></script>
    <script src="{$assets_path}js/ace.min.js"></script>
    <script src="{site_url()}assets/js/sweetalert.min.js"></script>
    <script src="{site_url()}assets/js/jquery.number.js"></script>
    <script src="{$assets_path}js/chosen.jquery.min.js"></script>
    <script src="{site_url()}assets/js/jquery-confirm.js"></script>
    <script src="{$assets_path}js/jquery-ui.min.js"></script>
    <script src="{$assets_path}js/jquery.ui.touch-punch.min.js"></script>
    <script type="text/javascript">
    $(document).ready(function () {
        //=== CUSTOM AUTOCOMPLETE
        $.widget("custom.catcomplete", $.ui.autocomplete, {
            _create: function () {
                this._super();
                this.widget().menu("option", "items", "> :not(.ui-autocomplete-category)");
            },
            _renderMenu: function (ul, items) {
                var that = this,
                        currentCategory = "";
                $.each(items, function (index, item) {
                    var li;
                    if (item.category !== currentCategory) {
                        ul.append("<li class='ui-autocomplete-category'>" + item.category + "</li>");
                        currentCategory = item.category;
                    }
                    li = that._renderItemData(ul, item);
                    if (item.category) {
                        li.attr("aria-label", item.category + " : " + item.label);
                    }
                });
            }
        });
        //=== AUTOCOMPLETE CHITIEU
        $('#chitieu').on('focus', function () {
            $(this).catcomplete("search");
        });
        $('#chitieu').on('keydown.autocomplete', function () {
            $('#chitieu_id').val('');
        });
        $("#capacity").on("change", function () {
            capa_city($("#capacity").val());
        });

        $('#chitieu').catcomplete({
            delay: 0,
            source: function (request, response) {
                $.ajax({
                    type: "POST",
                    url: "{site_url()}nenmau/chitieu/goiy_chitieu",
                    dataType: "json",
                    data: {
                        key: request.term
                    },
                    success: function (data) {
                        response(data);
                    },
                    error: function () {
                    }
                });
            },
            minLength: 0,
            select: function (event, ui) {
                $('#chitieu_id').val(ui.item.info.chitieu_id);
            }
        });
        //=== AUTOCOMPLETE KYTHUAT
        $('#kythuat').on('focus', function () {
            $(this).catcomplete("search");
        });
        $('#kythuat').on('keydown.autocomplete', function () {
            $('#kythuat_id').val('0');
        });
        $('#kythuat').catcomplete({
            delay: 0,
            source: function (request, response) {
                $.ajax({
                    type: "POST",
                    url: "{site_url()}nenmau/chitieu/goiy_kythuat",
                    dataType: "json",
                    data: {
                        key: request.term
                    },
                    success: function (data) {
                        response(data);
                    },
                    error: function () {
                    }
                });
            },
            minLength: 0,
            select: function (event, ui) {
                $('#kythuat_id').val(ui.item.info.kythuat_id);
            }
        });
    }
    );
    //start chất
    var chitieu_chat_id = 0;
    function hienthi_chat(chitieu_id) {
        chitieu_chat_id = chitieu_id;
        $("#chitieu" + chitieu_id).html('');
        $("#chitieu" + chitieu_id).append('<table id="table_chat' + chitieu_id + '" class="table table-bordered table-hover" style="margin-top:1%"><thead><tr><th>{$languages.table_chat_1}</th><th>{$languages.table_chat_2}</th><th>{$languages.table_chat_3}</th><th style="text-align:center">{$languages.table_chat_4}</th></tr></thead><tbody></tbody></table>');
    }

    function _load_danhsach_chat() {
        tentable = $('#table_chat' + chitieu_chat_id).DataTable({
            "processing": true,
            "serverSide": true,
            "language": {
                "processing": "{$languages.load}",
            },
            "order": [],
            "ajax": {
                "url": "{site_url()}nenmau/chat/danhsach_chat",
                "data": {
                    chitieu_id: chitieu_chat_id
                },
                "type": "POST",
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
    $('#chat_form').on('shown.bs.modal', function () {
        $("#chat").focus();
        $('.chosen-select', this).chosen('destroy').chosen();
    });
    function _add_chat(id_chitieu) {
        $(".title-chat").text("{$languages.title_themchat}");
        $("#form_chat")[0].reset();
        $("#congnhan").find('option').attr("selected", false);
        $("#chitieu_chat_id").val(id_chitieu);
        $('#chat_form').modal('show'); // show bootstrap modal
        $('#btnSua_chat').hide();
        $('#btnSave_chat').show();
        _load_danhsach_chat();
    }
    $("#loq").on("change", function () {
        check_lod_loq();
    });
    $("#lod").on("change", function () {
        check_lod_loq();
    });
    function check_lod_loq() {
        if ($("#lod").val() != "" && $("#loq").val() != "") {
            if ($("#loq").val() < $("#lod").val()) {
                swal("{$languages.canhbao}", "{$languages.canhbao_min_max}", "warning");
                $("#btnSave_chat").attr("disabled", true);
                $("#btnSua_chat").attr("disabled", true);
            } else {
                $("#btnSave_chat").attr("disabled", false);
                $("#btnSua_chat").attr("disabled", false);
            }
        }
    }
    ;
    function _save_chat() {
        var kiemtra = true;
        if ($("#chat").val() == '') {
            swal("{$languages.canhbao}", "{$languages.chat_tenchat_validation}", "warning");
            kiemtra = false;
        } else if ($("#loq").val() == "" && $("#lod").val() == "") {
            swal("{$languages.canhbao}", "{$languages.chat_lod_loq_validation}", "warning");
            kiemtra = false;
        }
        if (kiemtra == true) {
            $.confirm({
                title: '{$languages.xacnhan}',
                content: '{$languages.xacnhan_chat_them}',
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
                                data: {
                                    chitieu_id: $("#chitieu_chat_id").val(),
                                    chat_name: $("#chat").val(),
                                    chat_name_eng: $("#chat_eng").val(),
                                    chat_mota: $("#mota_chat").val(),
                                    congnhan: $("#congnhan").val(),
                                    capacity: $("#capacity").val(),
                                    lod: $("#lod").val(),
                                    loq: $("#loq").val(),
                                },
                                url: "{site_url()}nenmau/chat/them_chat",
                                success: function (data) {
                                    if (data == 1) {
                                        reload_table();
                                        $("#form_chat")[0].reset();
                                        $("#chat_form").modal("hide");
                                        swal("{$languages.thanhcong}", "{$languages.chat_them_success}", "success");
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
    function _xoa_chat(id_chat,chitieu_id) {
        $.confirm({
            title: '{$languages.xacnhan}',
            content: '{$languages.xacnhan_chat_xoa}',
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
                            data: {
                                'chat_id' : id_chat,
                                'chitieu_id' : chitieu_id,
                                'nenmau_id' : $("#nenmau").val()
                            },
                            url: "{site_url()}nenmau/chat/xoa_chat",
                            success: function (data) {
                                if (data == 1) {
                                    reload_table();
                                    swal("{$languages.thanhcong}", "{$languages.chat_xoa_success}", "success");
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
    function _sua_chat(chat_id, chitieu_id, chat_name, chat_name_eng, chat_mota, id_congnhan, lod, loq, capacity) {
        $(".title-chat").text("{$languages.title_suachat}");
        $("#form_chat")[0].reset();
        $("#chat_id").val(chat_id);
        $("#chat").val(chat_name);
        $("#congnhan_id").val(id_congnhan);
        var chuoi = id_congnhan.split("/");
        $("#chitieu_chat_id").val(chitieu_id);
        $('#congnhan').val(chuoi).trigger('chosen:updated');
        $("#mota_chat").val(chat_mota);
        $('#chat_form').modal('show'); // show bootstrap modal
        $("#chat_eng").val(chat_name_eng);
        $('#btnSua_chat').show();
        $('#btnSave_chat').hide();
        $("#capacity").val(capacity).trigger('chosen:updated');
        capa_city(capacity);
        $("#lod").val(lod);
        $("#loq").val(loq);
    }
    function capa_city(giatri) {
        if (giatri === "1") {
            $("#text-capacity1").text('LOD');
            $("#text-capacity2").text('LOQ');
        } else {
            $("#text-capacity1").text('MIN');
            $("#text-capacity2").text('MAX');
        }
    }
    function _sua_chat_oke() {
        var kiemtra = true;
        if ($("#chat").val() == '') {
            swal("{$languages.canhbao}", "{$languages.chat_tenchat_validation}", "warning");
            kiemtra = false;
        } else if ($("#loq").val() == "" && $("#lod").val() == "") {
            swal("{$languages.canhbao}", "{$languages.chat_lod_loq_validation}", "warning");
            kiemtra = false;
        }
        if (kiemtra == true) {
            $.confirm({
                title: '{$languages.xacnhan}',
                content: '{$languages.xacnhan_chat_sua}',
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
                                data: {
                                    chat_id: $("#chat_id").val(),
                                    chat_name: $("#chat").val(),
                                    chat_name_eng: $("#chat_eng").val(),
                                    chat_mota: $("#mota_chat").val(),
                                    congnhan: $("#congnhan").val(),
                                    chitieu_id: $("#chitieu_chat_id").val(),
                                    capacity: $("#capacity").val(),
                                    lod: $("#lod").val(),
                                    loq: $("#loq").val(),
                                },
                                url: "{site_url()}nenmau/chat/sua_chat",
                                success: function (data) {
                                    if (data == 1) {
                                        reload_table();
                                        $("#form_chat")[0].reset();
                                        $("#chat_form").modal("hide");
                                        swal("{$languages.thanhcong}", "{$languages.chat_sua_success}", "success");
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
    function reload_table() {
        tentable.ajax.reload(null, false); //reload datatable ajax 
    }
    function reload_table_chitieu() {
        table_chitieu.ajax.reload(null, false); //reload datatable ajax 
    }
    //end chất
    $("#tat").on("blur", function () {
        if (parseInt($("#tat").val()) < 0 || parseInt($("#tat").val()) == 0) {
            swal("{$languages.canhbao}", "{$languages.tat_validation}", "warning");
            $("#tat").val("1");
        }
    });
    //view danh sách chỉ tiêu
    _load_danhsach_chitieu1();
    function _load_danhsach_chitieu1() {
        table_chitieu = $('#table_chitieu').DataTable({
            "processing": true,
            "serverSide": true,
            colResize: false,
            autoWidth: false,
            scrollX: false,
            "language": {
                "processing": "{$languages.load}",
            },
            "order": [],
            "ajax": {
                "url": "{site_url()}nenmau/chitieu/danhsach_chitieu",
                "data": {
                    nenmau_id: $("#nenmau_sort").val()
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
                if (mData[3] == "3") {
                    $(nRow).find('td').each(function (i, el) {
                        if (i == 0) {
                            $(nRow).find("td:first").attr("colspan", "11");
                            var package_code = $(nRow).find("td:first").text();
                            var chitieu_name = $(nRow).find("td:eq(1)").text();
                            var chitieu_id = $(nRow).find("td:eq(2)").text();
                            $(nRow).find("td:first").html('');
                            $(nRow).find("td:first").append('<div class="table-detail"><div class="row"><div id="chitieu' + chitieu_id + '" class="col-xs-12">table</div></div></div>');
                        } else {
                            $(el).remove();
                        }
                    })
                } else {
                    $(nRow).find('td').each(function (i, el) {
                        if (i == 0) {
                            $(nRow).find("td:first").css("text-align", "center");
                        }
                    })
                }
            },
            "fnDrawCallback": function (data) {
                $(".even").addClass("detail-row");
                $(".paginate_button > a").on("focus", function () {
                    $(this).blur();
                });
                $('.show-details-btn').on('click', function (e) {
                    e.preventDefault();
                    $(this).closest('tr').next().toggleClass('open');
                    $(this).find(ace.vars['.icon']).toggleClass('fa-angle-double-down').toggleClass('fa-angle-double-up');
                    if ($(this).find(ace.vars['.icon']).hasClass("fa-angle-double-up")) {
                        _load_danhsach_chat();
                    }
                });
                $('[data-toggle="tooltip"]').tooltip();
            },
        });
    }
    //end view danh sách chỉ tiêu
    if (!ace.vars['touch']) {
        $('.chosen-select').chosen({
            allow_single_deselect: true
        });
        $('#chosen-multiple-style .btn').on('click', function (e) {
            var target = $(this).find('input[type=radio]');
            var which = parseInt(target.val());
            if (which == 2)
                $('#congnhan').addClass('tag-input-style');
            else
                $('#congnhan').removeClass('tag-input-style');
        });
    }
    $('#chitieu_form').on('shown.bs.modal', function () {
        $('.chosen-select', this).chosen('destroy').chosen();
    });
    function _add_chitieu() {
        $('#donvi').val(0).trigger("chosen:updated");
        $("#form")[0].reset();
        $('#chitieu_form').modal('show'); // show bootstrap modal
        $('.title-chitieu').text('{$languages.title_themchitieu}'); // Set Title to Bootstrap modal title
        $('#btnSua').hide();
        $('#btnSave').show();
    }

    function _save() {
        var kiemtra = true;
        if ($("#chitieu").val() == '') {
            swal("{$languages.canhbao}", "{$languages.chitieu_tenchitieu_validation}", "warning");
            kiemtra = false;
        } else if ($("#kythuat").val() == '') {
            swal("{$languages.canhbao}", "{$languages.chitieu_kythuat_validation}", "warning");
            kiemtra = false;
        } else if ($("#phongthinghiem").val() == '0') {
            swal("{$languages.canhbao}", "{$languages.chitieu_donvi_validation}", "warning");
            kiemtra = false;
        } else if ($("#donvi").val() == '0') {
            swal("{$languages.canhbao}", "{$languages.chitieu_donvitinh_validation}", "warning");
            kiemtra = false;
        } else if (parseInt($("#time_luu").val()) == 0 || parseInt($("#time_luu").val() < 0)) {
            swal("{$languages.canhbao}", "{$languages.chitieu_thoigianluumau_validation}", "warning");
            kiemtra = false;
        }
        if (kiemtra == true) {
            $.confirm({
                title: '{$languages.xacnhan}',
                content: '{$languages.xacnhan_chitieu_them}',
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
                                data: {
                                    nenmau: $("#nenmau").val(),
                                    chitieu: $("#chitieu").val(),
                                    chitieu_eng: $("#chitieu_eng").val(),
                                    chitieu_id: $("#chitieu_id").val(),
                                    mota: $("#mota").val(),
                                    phuongphap: $("#phuongphap").val(),
                                    phuongphap_id: $("#phuongphap_id").val(),
                                    kythuat: $("#kythuat").val(),
                                    kythuat_id: $("#kythuat_id").val(),
                                    phongthinghiem: $("#phongthinghiem").val(),
                                    donvi: $("#donvi").val(),
                                    thoigian: $("#tat").val(),
                                    thoigian_luu: $("#time_luu").val()
                                },
                                url: "{site_url()}nenmau/chitieu/them_chitieu",
                                success: function (data) {
                                    if (data == 1) {
                                        $("#phuongphap_id").val('0');
                                        $("#kythuat_id").val('0'),
                                        swal("{$languages.thanhcong}", "{$languages.chitieu_them_success}", "success");
                                        $("#chitieu_form").modal('hide');
                                        $("#form")[0].reset();
                                        reload_table_chitieu();
                                    } else {
                                        swal("{$languages.thatbai}", "{$languages.chitieu_tenchitieu_error}", "error");
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
    function _sua_oke() {
        var kiemtra = true;
        if ($("#chitieu").val() == '') {
            swal("{$languages.canhbao}", "{$languages.chitieu_tenchitieu_validation}", "warning");
            kiemtra = false;
        } else if ($("#kythuat").val() == '') {
            swal("{$languages.canhbao}", "{$languages.chitieu_kythuat_validation}", "warning");
            kiemtra = false;
        } else if ($("#phongthinghiem").val() == '0') {
            swal("{$languages.canhbao}", "{$languages.chitieu_donvi_validation}", "warning");
            kiemtra = false;
        } else if ($("#donvi").val() == '0') {
            swal("{$languages.canhbao}", "{$languages.chitieu_donvitinh_validation}", "warning");
            kiemtra = false;
        } else if (parseInt($("#time_luu").val()) == 0 || parseInt($("#time_luu").val() < 0)) {
            swal("{$languages.canhbao}", "{$languages.chitieu_thoigianluumau_validation}", "warning");
            kiemtra = false;
        }
        if (kiemtra == true) {
            $.confirm({
                title: '{$languages.xacnhan}',
                content: '{$languages.xacnhan_chitieu_sua}',
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
                                data: {
                                    package_code: $("#chitieu_sua").val(),
                                    nenmau: $("#nenmau").val(),
                                    chitieu: $("#chitieu").val(),
                                    chitieu_eng: $("#chitieu_eng").val(),
                                    id_chitieu_cu: $("#chitieu_cu").val(),
                                    mota: $("#mota").val(),
                                    phuongphap_id: $("#phuongphap_id").val(),
                                    kythuat: $("#kythuat").val(),
                                    kythuat_id: $("#kythuat_id").val(),
                                    phongthinghiem: $("#phongthinghiem").val(),
                                    donvi: $("#donvi").val(),
                                    thoigian: $("#tat").val(),
                                    thoigian_luu: $("#time_luu").val()
                                },
                                url: "{site_url()}nenmau/chitieu/sua_chitieu",
                                success: function (data) {
                                    if (data == 1) {
                                        $("#phuongphap_id").val('0');
                                        $("#kythuat_id").val('0'),
                                        swal("{$languages.thanhcong}", "{$languages.chitieu_sua_success}", "success");
                                        $("#chitieu_form").modal('hide');
                                        $("#form")[0].reset();
                                        reload_table_chitieu();
                                    } else {
                                        swal("{$languages.thatbai}", "{$languages.chitieu_tenchitieu_error}", "error");
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
    function _sua_chitieu(chitieu_id, nenmau_id, package_code, thoigianluu) {
        $('#donvi').val(0).trigger("chosen:updated");
        $.ajax({
            type: "POST",
            url: "{site_url()}nenmau/chitieu/get_chitieu",
            data: {
                chitieuid: chitieu_id,
                nenmauid: nenmau_id,
                packagecode: package_code
            },
            datatype: "text",
            success: function (data) {
                var obj = $.parseJSON(data);
                $('#donvi').val(obj[0].donvitinh_id).trigger("chosen:updated");
                $("#chitieu").val(obj[0].chitieu_name);
                $("#chitieu_cu").val(obj[0].chitieu_id);
                $("#mota").val(obj[0].chitieu_describe);
                $('#phuongphap_id').val(obj[0].phuongphap_id).trigger("chosen:updated");
                $("#kythuat").val(obj[0].kythuat_name);
                $("#kythuat_id").val(obj[0].kythuat_id);
                $('.title-chitieu').text('Sửa Chỉ Tiêu');
                $("#chitieu_sua").val(obj[0].package_code);
                $("#tat").val(obj[0].thoigian);
                $('#btnSua').show();
                $("#chitieu_eng").val(obj[0].chitieu_name_eng);
                $("#time_luu").val(thoigianluu);
                $('#btnSave').hide();
                $('#chitieu_form').modal('show');
            }
        });
    }
    function _xoa_chitieu(idchitieu, idnenmau) {
        $.confirm({
            title: '{$languages.xacnhan}',
            content: '{$languages.xacnhan_chitieu_xoa}',
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
                            data: {
                                chitieu_id: idchitieu,
                                nenmau_id: idnenmau,
                            },
                            url: "{site_url()}nenmau/chitieu/xoa_chitieu",
                            success: function (data) {
                                swal("{$languages.thanhcong}", "{$languages.chitieu_xoa_success}", "success");
                                reload_table_chitieu();
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
    function _gia_chitieu(gia, chitieu, nenmau, namechitieu, namenenmau) {
        $('.title-gia').text('{$languages.giachitieu_title_1} ' + namechitieu + ' {$languages.giachitieu_title_2} ' + namenenmau);
        if (gia != '0') {
            $("#giatien").val(gia);
        }
        $("#gia_chitieu").val(chitieu);
        $("#gia_nenmau").val(nenmau);
        $("#gia_form").modal("show");
    }
    $('#gia_form').on('shown.bs.modal', function () {
        $('#giatien').focus();
    })
    function _gia() {
        if ($("#giatien").val() == "" || parseInt($("#giatien").val()) < 0) {
            swal("{$languages.canhbao}", "{$languages.giachitieu_validation}", "warning");
        } else {
            $.confirm({
                title: '{$languages.xacnhan}',
                content: '{$languages.xacnhan_giachitieu_update}',
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
                                data: {
                                    gia: $("#giatien").val(),
                                    chitieu_id: $("#gia_chitieu").val(),
                                    nenmau_id: $("#gia_nenmau").val(),
                                },
                                url: "{site_url()}nenmau/chitieu/gia_chitieu",
                                success: function (data) {
                                    swal("{$languages.thanhcong}", "{$languages.giachitieu_success}", "success");
                                    reload_table_chitieu();
                                    $("#gia_form").modal("hide");
                                    $("#gia_form_1")[0].reset();
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
    function _trove() {
        window.location = '{site_url()}nenmau';
    }
    function _detail() {
        window.location = '{site_url()}nenmau/bogia/' + $("#nenmau_sort").val();
    }
    </script>
{/block}
{block name=body}
    <div class="main-content">
        <div class="main-content-inner">
            <!--PATH BEGINS-->
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li><i class="ace-icon fa fa-home home-icon"></i><a href="#">{$languages.url_1} </a></li>
                    <li><a href="{site_url()}nenmau">{$languages.url_2}</a></li>
                    <li class="active">{$languages.url_3} {$info_nenmau[0]->nenmau_name}</li>
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
                        <div class="row" style="margin-bottom:5px;">
                            <div class="col-xs-5">
                                {if $privchitieu.write}<button class="btn btn-xs btn-primary" onclick="_add_chitieu()"><i class="ace-icon fa fa-plus"></i> {$languages.button_create}</button>{/if}
                                {if $privtheodoichitieu.master}<button class="btn btn-xs btn-primary" onclick="exportchitieu()"><i class="ace-icon fa fa-file-excel-o"></i> {$languages.button_excel}</button>{/if}
                                {*<button type="button" class="btn btn-xs btn-warning" onclick="_detail()"><i class="ace-icon fa fa-pencil-square-o bigger-120"></i> {$languages.button_bogia}</button>*}
                                <button class="btn btn-xs btn-danger" onclick="_trove()"><i class="ace-icon fa fa-reply icon-only"></i> {$languages.button_trove}</button>
                            </div>
                            <div class="col-xs-7" style="text-align:right">
                                <input type="hidden" id="nenmau_sort" name="nenmau_sort" value="{$info_nenmau[0]->nenmau_id}">
                                {$capbac_nenmau}
                            </div>
                        </div>
                        <table id="table_chitieu" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>{$languages.table_1}</th>
                                    <th>{$languages.table_2}</th>
                                    <th>{$languages.table_3}</th>
                                    {if $privdinhgia.read}<th>{$languages.table_4}</th>{/if}
                                    <th style="text-align:center">{$languages.table_5}</th>
                                </tr>
                            </thead>

                            <tbody id="body_chitieu">

                            </tbody>
                        </table>
                        <!-- PAGE CONTENT ENDS -->
                    </div>
                </div>
            </div>
        </div>
    </div>
{/block}