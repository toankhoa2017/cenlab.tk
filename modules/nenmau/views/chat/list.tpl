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
                        <div class="form-group">
                            <label>Dung Sai</label>
                            <input autocomplete="off" class="form-control" type="number" id="dung_sai" name="dung_sai" />
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
                        <h3 class="modal-title title-gia">Cập Nhật Giá Chất</h3>
                    </div>
                    <div class="modal-body">
                        <form id="gia_form_1">
                            {if count($chatGias) > 0 }
                                {foreach from=$chatGias item=gia}
                                    <div class="form-group row gia-group">
                                        <label class="col-sm-3">Giá chất</label>
                                        <div class="col-sm-6">
                                            <input autocomplete="off" class="form-control giatien" type="text" value="{$gia}" name="giatien" />
                                        </div>
                                        <div class="col-sm-3">
                                            <button type="button" class="btn btn-xs btn-purple" onclick="cloneGiatien()" data-toggle="tooltip" title=""><i class="ace-icon fa fa-plus bigger-110"></i></button>
                                            <button class="btn btn-xs btn-danger" onclick="$(this).parent().parent().remove();" data-toggle="tooltip" title=""><i class="ace-icon fa fa-trash-o bigger-120" ></i></button>
                                        </div>
                                    </div>
                                {/foreach}
                            {else}
                                <div class="form-group row gia-group">
                                    <label class="col-sm-3">Giá chất</label>
                                    <div class="col-sm-6">
                                        <input autocomplete="off" class="form-control giatien" type="text" value="" name="giatien" />
                                    </div>
                                    <div class="col-sm-3">
                                        <button type="button" class="btn btn-xs btn-purple" onclick="cloneGiatien()" data-toggle="tooltip" title=""><i class="ace-icon fa fa-plus bigger-110"></i></button>
                                        <button class="btn btn-xs btn-danger" onclick="$(this).parent().parent().remove();" data-toggle="tooltip" title=""><i class="ace-icon fa fa-trash-o bigger-120" ></i></button>
                                    </div>
                                </div>
                            {/if}    
                            <input class="form-control" type="hidden" id="dongia" name="dongia" />
                            <script>
                                $(function () {
                                    $('input[name=giatien]').number(true, 0);
                                });
                            </script>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" onclick="setgia()" class="btn btn-xs btn-primary">{$languages.button_capnhat}</button>
                        <button type="button" class="btn btn-xs btn-danger" data-dismiss="modal">{$languages.button_thoat}</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
    {/if}
    <!-- End Bootstrap modal -->
    <script src="{$assets_path}plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="{$assets_path}plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script src="{$assets_path}js/ace-elements.min.js"></script>
    <script src="{$assets_path}js/ace.min.js"></script>
    <script src="{site_url()}assets/js/sweetalert.min.js"></script>
    <script src="{site_url()}assets/js/jquery.number.js"></script>
    <script src="{$assets_path}js/chosen.jquery.min.js"></script>
    <script src="{site_url()}assets/js/jquery-confirm.js"></script>
    <script src="{$assets_path}js/chosen.jquery.min.js"></script>
    <script src="{$assets_path}js/jquery-ui.min.js"></script>
    <script src="{$assets_path}js/jquery.ui.touch-punch.min.js"></script>
    <script type="text/javascript">
    $(document).ready(function () {
        _load({$package});
        $("#capacity").on("change", function () {
            capa_city($("#capacity").val());
        });
        $(".xoa-giachat").on("click", function(){
            $(this).parent().parent().remove();
            return false;
            
        });
        customAutocomplete();
        searchChat();
    });
    function customAutocomplete(){
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
    }
    
    function searchChat(){
        //=== AUTOCOMPLETE CHITIEU
        $('#chat').on('focus', function () {
            $(this).catcomplete("search");
        });
        $('#chat').on('keydown.autocomplete', function () {
            $('#chat_id').val('');
            $("#chat_eng").attr("disabled", false);
            $("#mota_chat").attr("disabled", false);
        });
        $('#chat').catcomplete({
            delay: 0,
            source: function (request, response) {
                $.ajax({
                    type: "POST",
                    url: "{site_url()}nenmau/chat/goiy_chat",
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
                $('#chat_id').val(ui.item.info.chat_id);
                $("#chat_eng").attr("disabled", true);
                $("#mota_chat").attr("disabled", true);
            }
        });
    }
    function _load(id) {
        table = $('#table_chat').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "{site_url()}nenmau/chat/ajax_list?package="+ id,
                "type": "POST"
            },
            "columnDefs": [
                {
                    "targets": [-1], //last column
                    "orderable": false, //set not orderable
                },
            ],
        });
    }
    function reload_table() {
        table.ajax.reload(null, false); //reload datatable ajax 
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
        //_load();
    }
    $("#loq").on("change", function () {
        check_lod_loq();
    });
    $("#lod").on("change", function () {
        check_lod_loq();
    });
    function check_lod_loq() {
        if ($("#lod").val() != "" && $("#loq").val() != "") {
            if (parseInt($("#loq").val()) < parseInt($("#lod").val())) {
                swal("{$languages.canhbao}", "{$languages.canhbao_min_max}", "warning");
                $("#btnSave_chat").attr("disabled", true);
                $("#btnSua_chat").attr("disabled", true);
            } else {
                $("#btnSave_chat").attr("disabled", false);
                $("#btnSua_chat").attr("disabled", false);
            }
        }
    };
    function cloneGiatien() {
        var table = $('#table_chat').DataTable();
        var countData = table.data().length;
        var lengthEle = $("#gia_form_1 .gia-group").length;
        if(lengthEle < countData - 1){
            $( ".gia-group" ).last().clone().find("input:text").val("").end().appendTo( "#gia_form_1" );
            $('input[name=giatien]').number(true, 0);
        }
        else
            alert("Bạn đã nhập hết giá");
    }
    
    function setgia() {
        if ($("#giatien").val() == "" || parseInt($("#giatien").val()) < 0) {
            swal("{$languages.canhbao}", "{$languages.giachitieu_validation}", "warning");
        } else {
            var giatien = [];
            $(".giatien").each(function() {
                giatien.push($(this).val());
            });
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
                                    gia: giatien,
                                    dongia: $("#dongia").val()
                                },
                                url: "{site_url()}nenmau/chat/setgia",
                                success: function (data) {
                                    swal("{$languages.thanhcong}", "{$languages.giachitieu_success}", "success");
                                    location.reload();
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
                                    chat_id: $("#chat_id").val(),
                                    chat_name: $("#chat").val(),
                                    chat_name_eng: $("#chat_eng").val(),
                                    chat_mota: $("#mota_chat").val(),
                                    congnhan: $("#congnhan").val(),
                                    capacity: $("#capacity").val(),
                                    lod: $("#lod").val(),
                                    loq: $("#loq").val(),
                                    dung_sai: $("#dung_sai").val()
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
    function _sua_chat(chat_id, chitieu_id, chat_name, chat_name_eng, chat_mota, id_congnhan, lod, loq, capacity, dung_sai) {
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
        $("#dung_sai").val(dung_sai);
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
                                    dung_sai: $("#dung_sai").val()
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
    function _dinhgia_chat(dongia_id){
        $("#dongia").val(dongia_id);
        $("#gia_form").modal("show");
        
        $('#giatien').focus();
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
                <li><a href="{site_url()}nenmau/chitieu?nenmau={$dongiaInfo->nenmau_id}">Nhóm Chỉ Tiêu</a></li>
                <li class="active">Chỉ Tiêu</li>
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
                    <h3 class="header smaller lighter blue">Chỉ Tiêu</h3>
                    <div class="row" style="margin-bottom:5px;">
                        <div class="col-xs-5">
                            {if $privchitieu.write}<button class="btn btn-xs btn-primary" onclick="_add_chat({$package})"><i class="ace-icon fa fa-plus"></i> Thêm Chỉ Tiêu</button>{/if}
                            {if $num_chat > 1}
                                {if $privdinhgia.write}<button class="btn btn-xs btn-warning" data-toggle="tooltip" onclick="_dinhgia_chat({$package})"><i class="ace-icon fa fa-dollar bigger-110"></i> Định giá đơn</button> {/if}
                            {/if}
                        </div>
                    </div>
                    <table id="table_chat" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>{$languages.table_chat_1}</th>
                                <th>Chỉ Tiêu</th>
                                <th>{$languages.table_chat_3}</th>
                                <th style="text-align:center">{$languages.table_chat_4}</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                    {if $privdinhgia.write OR $privdinhgia.update}        
                        <h3 class="header smaller lighter blue">Giá Chỉ Tiêu</h3>
                        {if count($chatGias) > 0 }
                            <table id="table_gia_chat" class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Thứ tự</th>
                                        <th>Giá</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {foreach from=$chatGias key=k item=val }
                                        <tr>
                                            <td style="width: 30%;">{$k + 1}</td>
                                            <td>{number_format($val)}</td>
                                        </tr>
                                    {/foreach}    
                                </tbody>
                            </table>
                        {/if}    
                        <!-- PAGE CONTENT ENDS -->
                    {/if}
                </div>
            </div>
        </div>
    </div>
</div>
{/block}