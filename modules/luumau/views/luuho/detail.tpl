{* Extend our master template *}
{extends file="master.tpl"}
{block name=css}
    <link rel="stylesheet" href="{site_url()}assets/css/jquery-confirm.css" />
    <link rel="stylesheet" href="{site_url()}assets/css/stylevth.css" />
    <link rel="stylesheet" href="{$assets_path}css/chosen.min.css" />
    <link rel="stylesheet" href="{$assets_path}css/ace.min.css" />
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
    <input type="hidden" id="mau_id" name="mau_id" value="{$mau->id}"/>
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
                            <label>{$languages.nguoiyeucau}:</label>
                            <input class="form-control" autocomplete="off" type="text" id="phone_mail" name="phone_mail" />
                            <input class="form-control" type="hidden" id="user_request" name="user_request" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-xs btn-primary" onclick="_laymau()">{$languages.button_laymau}</button>
                        <button type="button" class="btn btn-xs btn-danger" data-dismiss="modal">{$languages.button_huy}</button>
                    </div>
                </form>
                <form id="form_nhapmau" method="post">
                    <div class="modal-body">
                        <input type="hidden" id="kho1" name="kho1">
                        <div class="form-group">
                            <label>{$languages.khoiluong}:</label>
                            <input autocomplete="off" class="form-control" type="number" id="luumau_khoiluong1" name="luumau_khoiluong1" />
                        </div>
                        <div class="form-group">
                            <label>{$languages.tuluu}:</label>
                            <select class="form-control" id="kho_id" name="kho_id" onchange="myFunction(this, 0, 'tuu_')" level="0">
                                {foreach from=$tuluu key=k item=v}
                                    <option value="{$k}">{$v}</option>
                                {/foreach}
                            </select>
                            <div id="tuu_0"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-xs btn-primary" onclick="_nhapmau()">{$languages.button_nhapmau}</button>
                        <button type="button" class="btn btn-xs btn-danger" data-dismiss="modal">{$languages.button_huy}</button>
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
    <script type="text/javascript">
                        /*
                            function luumau(id) {
                                document.location.href = "{site_url()}luumau/mau/luumau/" + id;
                            }
                        */    
                            $(document).ready(function () {
                                chitietluu({$mau->id});
                            });
                            function chitietluu(id) {
                                $('#chitietluu').html("<center><img src='{$assets_path}images/loadingAnimation.gif' /></center>");
                                $.get('{site_url()}luumau/luuho/detail_list/' + id + '/{$mau->luu}', {},
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
                                                text: '{$languages.co}',
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
                            {if $file!=""}
                                <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                                    <!-- Indicators -->
                                    <ol class="carousel-indicators">
                                        {foreach from=$file key=k item=v}
                                            {if $k==0}
                                                <li data-target="#carousel-example-generic" data-slide-to="{$k}" class="active"></li>
                                                {else}
                                                <li data-target="#carousel-example-generic" data-slide-to="{$k}"></li>
                                                {/if}
                                            {/foreach}
                                    </ol>

                                    <!-- Wrapper for slides -->
                                    <div class="carousel-inner" role="listbox">
                                        {foreach from=$file key=k item=v}
                                            {if $k==0}
                                                <div class="item active">
                                                    <img src="{$v}" alt="..." style="width:100%;height:227px;object-fit: cover">
                                                    <div class="carousel-caption">
                                                    </div>
                                                </div>
                                            {else}
                                                <div class="item">
                                                    <img src="{$v}" alt="..." style="width:100%;height:227px;object-fit: cover">
                                                    <div class="carousel-caption">
                                                    </div>
                                                </div>
                                            {/if}
                                        {/foreach}
                                    </div>
                                    <!-- Controls -->
                                    <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                    <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                                        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </div>
                            {else}
                                <span class="profile-picture">
                                    <img class="editable img-responsive" alt="" id="avatar2" src="{$assets_path}images/avatars/avatar.png" />
                                </span>
                            {/if}
                        </div>
                        <div class="col-xs-12 col-sm-9">
                            <div class="widget-header widget-header-small" style="margin-bottom:1%;">
                                <h4 class="widget-title blue smaller">
                                    <i class="ace-icon fa fa-user orange"></i>
                                    {$mau->name}
                                </h4>
                            </div>
                            <div class="profile-user-info profile-user-info-striped">
                                <div class="profile-info-row row">
                                    <div class="col-sm-6 profile-info-name" style="text-align:right;width:23%;background-color: #e8eaed">{$languages.masomau}</div>
                                    <div class="profile-info-value col-sm-4" style="padding-left:2%">
                                        <i class="ace-icon fa fa-barcode blue"></i>
                                        <span class="editable">{$mau->code}</span>
                                    </div>
                                </div>
                                <div class="profile-info-row row">
                                    <div class="col-sm-6 profile-info-name" style="text-align:right;width:23%;background-color: #e8eaed">{$languages.ngayluu}</div>
                                    <div class="profile-info-value col-sm-4" style="padding-left:2%">
                                        <i class="fa fa-calendar blue bigger-110"></i>
                                        <span class="editable">{if $mau->ngayluuyeucau}{$mau->ngayluuyeucau}{else}{$mau->ngayluu}{/if}</span>
                                    </div>
                                </div>
                                <div class="profile-info-row row">
                                    <div class="col-sm-6 profile-info-name" style="text-align:right;width:23%;background-color: #e8eaed">{$languages.dieukienluu}</div>
                                    <div class="profile-info-value col-sm-4" style="padding-left:2%">
                                        <i class="ace-icon fa fa-cloud bigger-110 blue"></i>
                                        <span class="editable">{$mau->dieukienluu}</span>
                                    </div>
                                </div>
                                <div class="profile-info-row row">
                                    <div class="col-sm-6 profile-info-name" style="text-align:right;width:23%;background-color: #e8eaed">{$languages.soluong}</div>
                                    <div class="profile-info-value col-sm-4" style="padding-left:2%">
                                        <i class="fa fa-tachometer blue bigger-110"></i>
                                        <span class="editable">{$mau->soluong}</span>
                                    </div>
                                </div>
                                <div class="profile-info-row row">
                                    <div class="col-sm-6 profile-info-name" style="text-align:right;width:23%;background-color: #e8eaed">{$languages.khoiluong}</div>
                                    <div class="profile-info-value col-sm-4" style="padding-left:2%">
                                        <i class="fa fa-flask blue bigger-110"></i>
                                        <span class="editable">{$mau->khoiluong} {$mau->donvi}</span>
                                    </div>
                                </div>
                                <div class="profile-info-row row">
                                    <div class="col-sm-6 profile-info-name" style="text-align:right;width:23%;background-color: #e8eaed">{$languages.ghichu}</div>
                                    <div class="profile-info-value col-sm-4" style="padding-left:2%">
                                        <i class="fa fa-comment blue bigger-110"></i>
                                        <span class="editable">{$mau->ghichu}</span>
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