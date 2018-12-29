{* Extend our master template *}
{extends file="master.tpl"}
{block name=css}
    <link rel="stylesheet" href="{site_url()}assets/css/jquery-confirm.css" />
    <link rel="stylesheet" href="{site_url()}assets/css/stylevth.css" />
    <link rel="stylesheet" href="{$assets_path}css/chosen.min.css" />
    <link rel="stylesheet" href="{$assets_path}css/ace.min.css" />
    <link rel="stylesheet" href="{$assets_path}plugins/colorbox/css1/colorbox.css" />
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
    <div class="modal fade" id="modal_laymau" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title chiamau-luuho"></h3>
                </div>
                <div class="modal-body form">
                    <form id="form_luumau" method="post">
                        <input type="hidden" id="mau_id" name="mau_id" value="{$mau['id']}"/>
                        <input type="hidden" id="kho" name="kho"/> 
                        <div class="form-group">
                            <label>{$languages.chiamau_tenmau}:</label>
                            <input autocomplete="off" class="form-control" type="text" id="luumau_name" value="{$mau['name']}" name="luumau_name" />
                        </div>
                        <div class="form-group">
                            <label>{$languages.chiamau_khoiluong}:</label>
                            <input autocomplete="off" class="form-control" type="number" id="luumau_khoiluong" value="{$mau['khoiluong']}" name="luumau_khoiluong" />
                        </div>
                        <div class="form-group">
                            <label>{$languages.chiamau_loaimau}:</label>
                            <select class="form-control" id="luumau_loai" name="luumau_loai">
                                <option value="0">{$languages.chiamau_loaimau_1}</option>
                                <option value="1">{$languages.chiamau_loaimau_2}</option>
                            </select>
                        </div>
                        <div class="form-group" id="group_tuluu">
                            <label>{$languages.chiamau_tuluu}:</label>
                            <select class="form-control" onchange="myFunction(this, 0, 'tu_')" level="0">
                                {foreach from=$tuluu key=k item=v}
                                    <option value="{$k}">{$v}</option>
                                {/foreach}
                            </select>
                            <div id="tu_0"></div>
                        </div>
                        <div class="form-group" id="group_luuho">
                            <div class="form-group">
                                <label>{$languages.donvi}:</label>
                                <select class="form-control" id="donvi_id" name="donvi_id">
                                    {foreach from=$donvi key=k item=v}
                                        <option value="{$k}">{$v}</option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="form-group">
                                <label>{$languages.dieukien}:</label>
                                <input autocomplete="off" class="form-control" type="text" id="luuho_dieukien" name="luuho_dieukien" />
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="_luumau" onclick="luumau_success()" class="btn btn-xs btn-primary">{$languages.button_chiamau}</button>
                    <button type="button" id="_luuho" onclick="_luuho()" class="btn btn-xs btn-primary">{$languages.button_luuho}</button>
                    <button type="button" class="btn btn-xs btn-danger" data-dismiss="modal">{$languages.button_thoat}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

    <div class="modal fade" id="modal_xulymau" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title title-xulymau"></h3>
                </div>
                <input type="hidden" id="luumau_id" name="luumau_id">
                <form id="form_laymau" method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>{$languages.xlm_nguoiyeucau}:</label>
                            <input class="form-control" autocomplete="off" type="text" id="phone_mail" name="phone_mail" />
                            <input class="form-control" type="hidden" id="user_request" name="user_request" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-xs btn-primary" onclick="_laymau()">{$languages.button_laymau}</button>
                        <button type="button" class="btn btn-xs btn-danger" data-dismiss="modal">{$languages.button_thoat}</button>
                    </div>
                </form>
                <form id="form_nhapmau" method="post">
                    <div class="modal-body">
                        <input type="hidden" id="kho1" name="kho1">
                        <div class="form-group">
                            <label>{$languages.nhapmau_khoiluong}</label>
                            <input autocomplete="off" class="form-control" type="number" id="luumau_khoiluong1" name="luumau_khoiluong1" />
                        </div>
                        <div class="form-group">
                            <label>{$languages.nhapmau_tuluu}:</label>
                            <select class="form-control" id="kho_id" name="kho_id" onchange="myFunction(this, 0, 'tuu_')" level="0">
                                {foreach from=$tuluu key=k item=v}
                                    <option value="{$k}">{$v}</option>
                                {/foreach}
                            </select>
                            <div id="tuu_0"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-xs btn-primary" onclick="_nhapmau()">{$languages.nhapmau}</button>
                        <button type="button" class="btn btn-xs btn-danger" data-dismiss="modal">{$languages.button_thoat}</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

    <script src="{site_url()}assets/js/sweetalert.min.js"></script>
    <script src="{site_url()}assets/js/jquery-confirm.js"></script>
    <script src="{$assets_path}js/chosen.jquery.min.js"></script>
    <script src="{$assets_path}js/jquery-ui.min.js"></script>
    <script src="{$assets_path}js/jquery.ui.touch-punch.min.js"></script>
    <script src="{$assets_path}plugins/colorbox/jquery.colorbox.js"></script>
    <script type="text/javascript">
                                            $(document).ready(function () {
                                                chitietluu({$mau['id']});
                                            });
                                            function chitietluu(id) {
                                                $('#chitietluu').html("<center><img src='{$assets_path}images/loadingAnimation.gif' /></center>");
                                                $.get('{site_url()}luumau/mau/detail_list/' + id + '/{$mau['luu']}?donvi={$mau['donvi']}', {},
                                                        function (response) {
                                                            $('#chitietluu').html(response);
                                                        }
                                                );
                                            }
                                            /*
                                             * Open modal chia mau
                                             * Cac ham ajax xu ly viec chia mau
                                             * Ket thuc cac ham ajax thi goi lai ham chitietluu de load lai trang chi tiet luu mau
                                             */
                                            var tuluucuoicung = 0;
                                            function myFunction(tusave, level, key) {
                                                var giatri = $(tusave).val();
                                                if (giatri == 0) {
                                                    var lever = parseInt(level) - 1;
                                                    tuluucuoicung = $("select[level='" + lever + "']").val();
                                                } else {
                                                    tuluucuoicung = giatri;
                                                }
                                                tuluu(giatri, level, key);
                                            }
                                            var check_luucha = false;
                                            function tuluu(id, level, key) {
                                                $.ajax({
                                                    url: "{site_url()}luumau/mau/get_kho",
                                                    type: "POST",
                                                    data: {
                                                        'kho_id': id,
                                                        'level': level,
                                                        'mau_id': $("#mau_id").val(),
                                                        'key': key
                                                    },
                                                    success: function (data) {
                                                        if (data == "") {
                                                            check_luucha = true;
                                                        } else {
                                                            check_luucha = false;
                                                        }
                                                        var lever = parseInt(level) + 1;
                                                        $("#" + key + lever).remove();
                                                        if (id != '0') {
                                                            $("#" + key + level).append(data);
                                                        }
                                                    }
                                                });
                                            }

                                            function luumau(id) {
                                                $("#group_luuho").hide();
                                                $("#_luuho").hide();
                                                $("#group_tuluu").show();
                                                $("#_luumau").show();
                                                $('#form_luumau')[0].reset();
                                                $("#tu_0").html('');
                                                $("#mau_id").val(id);
                                                $(".chiamau-luuho").text("Lưu Mẫu");
                                                $("#modal_laymau").modal("show");
                                            }

                                            function luuho(id) {
                                                $("#group_luuho").show();
                                                $("#_luuho").show();
                                                $("#group_tuluu").hide();
                                                $("#_chiamau").hide();
                                                $("#_luumau").hide();
                                                $('#form_luumau')[0].reset();
                                                $("#tu_0").html('');
                                                $("#mau_id").val(id);
                                                $(".chiamau-luuho").text("{$languages.title_luuho}");
                                                $("#modal_laymau").modal("show");
                                            }

                                            function luumau_success() {
                                                var klMau = "{$mau['khoiluong']}";
                                                if ($("#luumau_name").val() == "") {
                                                    swal("{$languages.canhbao}", "{$languages.tenmau_validation}", "warning");
                                                } else if ($("#luumau_khoiluong").val() === "") {
                                                    swal("{$languages.canhbao}", "{$languages.khoiluong_validation}", "warning");
                                                } else if ((parseInt($("#luumau_khoiluong").val()) + parseInt($("#total_khoiluong").val())) > parseInt(klMau)){
                                                    swal("{$languages.canhbao}", "Tổng khối lượng lưu phải nhỏ hơn hoặc bằng khối lượng mẫu", "warning");
                                                } else if (tuluucuoicung === 0) {
                                                    swal("{$languages.canhbao}", "{$languages.tuluu_validation}", "warning");
                                                } else {
                                                    if (check_luucha == true) {
                                                        $("#kho").val(tuluucuoicung);
                                                        $.ajax({
                                                            url: "{site_url()}luumau/mau/chiamau_add",
                                                            type: "POST",
                                                            data: $('#form_luumau').serialize(),
                                                            dataType: "JSON",
                                                            success: function (data) {
                                                                if (data == '1') {
                                                                    chitietluu($("#mau_id").val());
                                                                    $('#modal_laymau').modal('hide');
                                                                    swal("{$languages.thanhcong}", "{$languages.chiamau_success}", "success");
                                                                    tuluucuoicung = 0;
                                                                }
                                                            }
                                                        });
                                                    } else {
                                                        swal("{$languages.canhbao}", "{$languages.tuluu_error}", "warning");
                                                    }
                                                }
                                            }

                                            function _luuho() {
                                                if ($("#luuho_name").val() == "") {
                                                    swal("{$languages.canhbao}", "{$languages.tenmau_validation}", "warning");
                                                } else if ($("#luuho_dieukien").val() == "") {
                                                    swal("{$languages.canhbao}", "{$languages.dieukien_validation}", "warning");
                                                } else if ($("#luuho_khoiluong").val() == "") {
                                                    swal("{$languages.canhbao}", "{$languages.khoiluong_validation}", "warning");
                                                } else {
                                                    $.ajax({
                                                        url: "{site_url()}luumau/mau/luuho_add",
                                                        type: "POST",
                                                        data: $('#form_luumau').serialize(),
                                                        dataType: "JSON",
                                                        success: function (data) {
                                                            if (data == '1') {
                                                                chitietluu($("#mau_id").val());
                                                                $('#modal_laymau').modal('hide');
                                                                swal("{$languages.thanhcong}", "{$languages.luuho_success}", "success");
                                                            }
                                                        }
                                                    });
                                                }
                                            }

                                            function laymau(luumau_id) {
                                                $('#form_laymau')[0].reset();
                                                $("#luumau_id").val(luumau_id);
                                                $("#form_nhapmau").hide();
                                                $("#user_request").val("");
                                                $("#form_laymau").show();
                                                $(".title-xulymau").text("{$languages.title_laymau}")
                                                $("#modal_xulymau").modal("show");
                                            }

                                            function nhapmau(luumau_id) {
                                                $('#form_nhapmau')[0].reset();
                                                $("#luumau_id").val(luumau_id);
                                                $("#form_nhapmau").show();
                                                $("#tuu_0").html('');
                                                $("#form_laymau").hide();
                                                $(".title-xulymau").text("{$languages.title_nhapmau}");
                                                $("#modal_xulymau").modal("show");
                                            }
                                            $('#modal_xulymau').on('shown.bs.modal', function () {
                                                $('.kholuu1', this).chosen('destroy').chosen();
                                            });

                                            function _nhapmau() {
                                                if ($("#luumau_khoiluong1").val() === "") {
                                                    swal("{$languages.canhbao}", "{$languages.khoiluong_validation}", "warning");
                                                } else if (tuluucuoicung === 0) {
                                                    swal("{$languages.canhbao}", "{$languages.tuluu_validation}", "warning");
                                                } else {
                                                    if (check_luucha == true) {
                                                        $("#kho1").val(tuluucuoicung);
                                                        $.confirm({
                                                            title: '{$languages.xacnhan}',
                                                            content: '{$languages.xacnhan_nhapmau}',
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
                                                                            url: "{site_url()}luumau/mau/nhapmau",
                                                                            data: {
                                                                                'luumau_khoiluong': $("#luumau_khoiluong1").val(),
                                                                                'kho_id': $("#kho1").val(),
                                                                                'luumau_id': $("#luumau_id").val()
                                                                            },
                                                                            datatype: "text",
                                                                            success: function (data) {
                                                                                if (data == 1) {
                                                                                    swal("{$languages.thanhcong}", "{$languages.nhapmau_success}", "success");
                                                                                    $("#modal_xulymau").modal("hide");
                                                                                    chitietluu($("#mau_id").val());
                                                                                    tuluucuoicung = 0;
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
                                                    } else {
                                                        swal("{$languages.canhbao}", "{$languages.tuluu_error}", "warning");
                                                    }
                                                }
                                            }

                                            function _laymau() {
                                                if ($("#user_request").val() == "") {
                                                    swal("{$languages.canhbao}", "{$languages.nguoilaymau_validation}", "warning");
                                                } else {
                                                    $.confirm({
                                                        title: '{$languages.xacnhan}',
                                                        content: '{$languages.xacnhan_laymau}',
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
                                                                        url: "{site_url()}luumau/mau/laymau",
                                                                        data: {
                                                                            'user_request': $("#user_request").val(),
                                                                            'luumau_id': $("#luumau_id").val()
                                                                        },
                                                                        datatype: "text",
                                                                        success: function (data) {
                                                                            if (data == 1) {
                                                                                swal("{$languages.thanhcong}", "{$languages.laymau_success}", "success");
                                                                                $("#modal_xulymau").modal("hide");
                                                                                chitietluu($("#mau_id").val());
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

                                            function _hetmau(luumau_id) {
                                                $.confirm({
                                                    title: '{$languages.xacnhan}',
                                                    content: '{$languages.xacnhan_hetmau}',
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
                                                                    url: "{site_url()}luumau/mau/hetmau",
                                                                    data: {
                                                                        'luumau_id': luumau_id
                                                                    },
                                                                    datatype: "text",
                                                                    success: function (data) {
                                                                        if (data == 1) {
                                                                            swal("{$languages.thanhcong}", "{$languages.hetmau_success}", "success");
                                                                            chitietluu($("#mau_id").val());
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
                                                $('#phone_mail').on('focus', function () {
                                                    $(this).catcomplete("search");
                                                });
                                                $('#phone_mail').on('keydown.autocomplete', function () {
                                                    $('#user_request').val('');
                                                });
                                                $('#phone_mail').catcomplete({
                                                    delay: 0,
                                                    source: function (request, response) {
                                                        $.ajax({
                                                            type: "POST",
                                                            url: "{site_url()}luumau/mau/goiy_nhansu",
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
                                                        $('#user_request').val(ui.item.info.nhansu_id);
                                                    }
                                                });
                                            });
                                            $('.carousel').carousel({
                                                interval: 2000
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
                    <li><a href="{site_url()}luumau/mau">{$languages.url_2}</a></li>
                    <li class="active">{$languages.url_3}</li>
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
                        <div class="clearfix">
                            <div class="pull-left tableTools-container"></div>
                        </div>
                        <div class="col-xs-12 col-sm-3 center" style="margin-top:0.5%">
                            <div class="space space-4"></div>
                            <div class="col-sm-6">
                                <button id="chiamau_button" class="btn btn-xs btn-block btn-success" onclick="luumau({$mau['id']})">Lưu mẫu</button>
                            </div>
                            <div class="col-sm-6">
                                <button href="#" id="luuho_button" class="btn btn-xs btn-block btn-info" onclick="luuho({$mau['id']})">{$languages.goiluuho}</button>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-9">
                            <div class="widget-header widget-header-small" style="margin-bottom:1%;">
                                <h4 class="widget-title blue smaller">
                                    <i class="ace-icon fa fa-user orange"></i>
                                    {$mau['name']}
                                </h4>
                            </div>
                            <div class="profile-user-info profile-user-info-striped">
                                <div class="profile-info-row row">
                                    <div class="col-sm-6 profile-info-name" style="text-align:right;width:23%;background-color: #e8eaed">{$languages.masomau}</div>
                                    <div class="profile-info-value col-sm-4" style="padding-left:2%">
                                        <i class="ace-icon fa fa-barcode blue"></i>
                                        <span class="editable">{$mau['code']}</span>
                                    </div>
                                </div>
                                <div class="profile-info-row row">
                                    <div class="col-sm-6 profile-info-name" style="text-align:right;width:23%;background-color: #e8eaed">{$languages.ngayluu}</div>
                                    <div class="profile-info-value col-sm-4" style="padding-left:2%">
                                        <i class="fa fa-calendar blue bigger-110"></i>
                                        <span class="editable">{if $mau['ngayluuyeucau']}{$mau['ngayluuyeucau']}{else}{$mau['ngayluu']}{/if}</span>
                                    </div>
                                </div>
                                <div class="profile-info-row row">
                                    <div class="col-sm-6 profile-info-name" style="text-align:right;width:23%;background-color: #e8eaed">{$languages.dieukienluu}</div>
                                    <div class="profile-info-value col-sm-4" style="padding-left:2%">
                                        <i class="ace-icon fa fa-cloud bigger-110 blue"></i>
                                        <span class="editable">{$mau['dieukienluu']}</span>
                                    </div>
                                </div>
                                <div class="profile-info-row row">
                                    <div class="col-sm-6 profile-info-name" style="text-align:right;width:23%;background-color: #e8eaed">{$languages.soluong}</div>
                                    <div class="profile-info-value col-sm-4" style="padding-left:2%">
                                        <i class="fa fa-tachometer blue bigger-110"></i>
                                        <span class="editable">{$mau['soluong']}</span>
                                    </div>
                                </div>
                                <div class="profile-info-row row">
                                    <div class="col-sm-6 profile-info-name" style="text-align:right;width:23%;background-color: #e8eaed">{$languages.khoiluong}</div>
                                    <div class="profile-info-value col-sm-4" style="padding-left:2%">
                                        <i class="fa fa-flask blue bigger-110"></i>
                                        <span class="editable">{$mau['khoiluong']} {$mau['donvi']}</span>
                                    </div>
                                </div>
                                <div class="profile-info-row row">
                                    <div class="col-sm-6 profile-info-name" style="text-align:right;width:23%;background-color: #e8eaed">{$languages.ghichu}</div>
                                    <div class="profile-info-value col-sm-4" style="padding-left:2%">
                                        <i class="fa fa-comment blue bigger-110"></i>
                                        <span class="editable">{$mau['ghichu']}</span>
                                    </div>
                                </div>
                            </div>
                            <div style="height:10px;"></div>
                            <div id="chitietluu"></div>
                        </div><!-- /.col -->
                        <!-- PAGE CONTENT ENDS -->
                    </div>
                </div>
            </div>
        </div>
    </div>
{/block}