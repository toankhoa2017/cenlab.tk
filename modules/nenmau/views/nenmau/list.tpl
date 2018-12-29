{* Extend our master template *}
{extends file="master.tpl"}
{block name=css}
    <link rel="stylesheet" href="{$assets_path}css/chosen.min.css" />
    <link rel="stylesheet" href="{site_url()}assets/css/jquery-confirm.css" />
    <link rel="stylesheet" href="{site_url()}assets/css/stylevth.css" />
    <link rel="stylesheet" href="{site_url()}assets/css/bootstrap-treeview.css" />
    <link rel="stylesheet" href="{$assets_path}css/chosen.min.css" />
    <link rel="stylesheet" href="{$assets_path}css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style"/>
    <link rel="stylesheet" href="{$assets_path}css/ace-skins.min.css" />
    <link rel="stylesheet" href="{$assets_path}css/ace-rtl.min.css" />
    <style>
        ul.list-group{
            margin-bottom: 0px;
        }
        .label.arrowed-in-right, .label.arrowed-right{
            top:10px;
        }
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
    {if $privnenmau.write OR $privnenmau.update}
        <!-- Bootstrap modal -->
        <div class="modal fade" id="modal_form" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h3 class="modal-title"></h3>
                    </div>
                    <form id="form" method="post">
                        <input type="hidden" value="0" id="parent" name="parent" />
                        <div class="modal-body form">
                            <input type="hidden" id="idparent" name="idparent">
                            <div class="form-group">
                                <label>{$languages.tennenmau}:</label>
                                <input autocomplete="off" class="form-control" type="text" id="name" name="name" />
                                <input type="hidden" id="id_sua" name="id_sua">
                            </div>
                            <div class="form-group">
                                <label>{$languages.tennenmau_eng}:</label>
                                <input autocomplete="off" class="form-control" type="text" id="name_eng" name="name_eng" />
                            </div>
                            <div class="form-group">
                                <label>{$languages.mota}:</label>
                                <textarea class="form-control" id="mota" name="mota" placeholder=""></textarea>
                            </div>
                            <div class="form-group">
                                <label>{$languages.dieukienluu}</label>
                                <input class="form-control" autocomplete="off" type="text" id="dieukienluu" name="dieukienluu" />
                                <input class="form-control" type="hidden" id="dieukienluu_id" name="dieukienluu_id" />
                                <div id="dsdieukienluu"></div>
                            </div>
                            <div class="form-group">
                                <label>{$languages.donviluu}</label>
                                <select class="chosen-select form-control" id="donvi" name="donvi">
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
                        <button type="button" onclick="setgia()" class="btn btn-xs btn-primary">Cập Nhật</button>
                        <button type="button" class="btn btn-xs btn-danger" data-dismiss="modal">{$languages.button_thoat}</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
    {/if}
    <script src="{$assets_path}js/jquery.dataTables.min.js"></script>
    <script src="{$assets_path}js/jquery.dataTables.bootstrap.min.js"></script>
    <script src="{$assets_path}js/ace-elements.min.js"></script>
    <script src="{$assets_path}js/ace.min.js"></script>
    <script src="{site_url()}assets/js/jquery.number.js"></script>
    <script src="{$assets_path}js/select2.min.js"></script>
    <script src="{site_url()}assets/js/sweetalert.min.js"></script>
    <script src="{site_url()}assets/js/jquery-confirm.js"></script>
    <script src="{$assets_path}js/chosen.jquery.min.js"></script>
    <script src="{$assets_path}js/jquery-ui.min.js"></script>
    <script src="{$assets_path}js/jquery.ui.touch-punch.min.js"></script>
    <script src="{site_url()}assets/js/bootstrap-treeview.js"></script>
    <script src="{$assets_path}plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="{$assets_path}plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript">
        var site_url = '{site_url()}';
    </script>
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
            //=== AUTOCOMPLETE DIEUKIENLUU
            $('#dieukienluu').on('focus', function () {
                $(this).catcomplete("search");
            });
            $('#dieukienluu').on('keydown.autocomplete', function () {
                $('#dieukienluu_id').val('');
            });
            $('#dieukienluu').catcomplete({
                delay: 0,
                source: function (request, response) {
                    $.ajax({
                        type: "POST",
                        url: "{site_url()}nenmau/ajax_listdieukienluu",
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
                    $('#dieukienluu_id').val(ui.item.info.dieukienluu_id);
                }
            });
        });
    </script>
    {literal}
        <script type="text/javascript">
            function load_select_nenmau(id) {
                $.ajax({
                    type: "POST",
                    url: site_url + "nenmau/load_select_nenmau",
                    datatype: "text",
                    success: function (data) {
                        $("#idparent").html('');
                        $("#idparent").append(data);
                        if (id === 0) {
                            $('#idparent').trigger("chosen:updated");
                        } else {
                            $("#idparent").val(id).trigger("chosen:updated");
                        }
                    }
                });
            }
            load_tree();
            function load_tree() {
                $.ajax({
                    type: "POST",
                    url: site_url + "nenmau/tree_data",
                    data: {
                        'search': $("#tukhoa").val()
                    },
                    dataType: "json",
                    success: function (response)
                    {
                        var dulieu = response;
                        $('#tree').treeview({
                            levels: 1,
                            highlightSelected: false,
                            data: dulieu,
                            onNodeSelected: function (event, node) {

                            },
                            onNodeUnselected: function (event, node) {
                                $("#themfile").html('');
                            }
                        });
                    }
                });
            }
            function hihi(id) {
                window.location = site_url + "nenmau/chitieu?nenmau=" + id;
            }
        </script>
    {/literal}
    <script type="text/javascript">
        $('#modal_form').on('shown.bs.modal', function () {
            $('.chosen-select', this).chosen();
        });
        function _add(id) {
            $('#form')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            $('#modal_form').modal('show'); // show bootstrap modal
            $('.modal-title').text('{$languages.title_them}'); // Set Title to Bootstrap modal title
            $("#btnSua").hide();
            $("#btnSave").show();
            $("#idparent").val(id);
            $("#parent").val(id);
            load_select_nenmau(0);
            load_select_donvi(0);
        }

        function load_select_donvi(id) {
            $.ajax({
                type: "POST",
                url: "{site_url()}nenmau/danhsach_donvi",
                datatype: "text",
                success: function (data) {
                    $("#donvi").html('');
                    $("#donvi").append(data);
                    if (id === 0) {
                        $('#donvi').trigger("chosen:updated");
                    } else {
                        $("#donvi").val(id).trigger("chosen:updated");
                    }
                }
            });
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
                                url: "{site_url()}nenmau/xoanenmau",
                                data: {
                                    idnenmauxoa: id
                                },
                                datatype: "text",
                                success: function (data) {
                                    if (data == 1) {
                                        swal("{$languages.thanhcong}", "{$languages.xoa_success}", "success");
                                        load_tree();
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
        function _sua(id, name, mota, name_eng, dieukienluu, dieukienluu_id, id_donvi) {
            $('#form')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            $('#modal_form').modal('show'); // show bootstrap modal
            $('.modal-title').text('Sửa nền mẫu');
            $("#name").val(name);
            $("#mota").val(mota);
            $("#id_sua").val(id);
            $("#btnSua").show();
            $("#btnSave").hide();
            $("#name_eng").val(name_eng);
            $("#dieukienluu").val(dieukienluu);
            $("#dieukienluu_id").val(dieukienluu_id);
            load_select_nenmau(id);
            load_select_donvi(id_donvi);
            namecu = name;
        }

        function _sua_oke() {
            if ($("#name").val() == "") {
                swal("{$languages.canhbao}", "{$languages.tennenmau_validation}", "warning");
            } else if ($("#dieukienluu").val() == "") {
                swal("{$languages.canhbao}", "{$languages.dieukienluu_validation}", "warning");
            } else {
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
                                    url: "{site_url()}nenmau/suanenmau",
                                    data: {
                                        idnenmausua: $("#id_sua").val(),
                                        namenenmausua: $("#name").val(),
                                        motanenmausua: $("#mota").val(),
                                        idparent: $("#idparent").val(),
                                        tennenmautruocthaydoi: namecu,
                                        name_eng: $("#name_eng").val(),
                                        dieukienluu: $("#dieukienluu").val(),
                                        dieukienluu_id: $("#dieukienluu_id").val(),
                                        donvi: $("#donvi").val(),
                                    },
                                    datatype: "text",
                                    success: function (data) {
                                        if (data == 1) {
                                            namecu = "";
                                            $("#modal_form").modal("hide");
                                            swal("{$languages.thanhcong}", "{$languages.sua_success}", "success");
                                            load_tree();
                                        } else {
                                            swal("{$languages.thatbai}", "{$languages.tennenmau_error}", "error");
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
            if ($("#name").val() == "") {
                swal("{$languages.canhbao}", "{$languages.tennenmau_validation}", "warning");
            } else if ($("#dieukienluu").val() == "") {
                swal("{$languages.canhbao}", "{$languages.dieukienluu_validation}", "warning");
            } else {
                $.ajax({
                    url: "{site_url()}nenmau/ajax_add",
                    type: "POST",
                    data: $('#form').serialize(),
                    dataType: "JSON",
                    success: function (data) {
                        //if success close modal and reload ajax table
                        if (data.status == 'denied') {
                            swal("{$languages.thatbai}", "{$languages.tennenmau_error}", "error");
                        } else {
                            $('#modal_form').modal('hide');
                            swal("{$languages.thanhcong}", "{$languages.themm_success}", "success");
                            load_tree();
                        }
                    }
                });
            }
        }
        $("#tukhoa").keyup(function (evt) {
            load_tree();
        })
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
        function cloneGiatien() {
            $( ".gia-group" ).last().clone().find("input:text").val("").end().appendTo( "#gia_form_1" );
        }
        function _dinhgia_chat(dongia_id){
            $("#dongia").val(dongia_id);
            $("#gia_form").modal("show");

            $('#giatien').focus();
        }
        
        {*$('#dieukienluu').keyup(function(){
            var keyword = $(this).val();
            if(keyword != '') 
            {
                $.ajax({
                    url: "{site_url()}nenmau/ajax_listdieukienluu",
                    method: "POST",
                    data: { keyword : keyword },
                    success:function(data)
                    {
                        $('#dsdieukienluu').fadeIn();
                        $('#dsdieukienluu').html(data);
                    }
                });
            }
        });
        $(document).on('click','.dk_item', function(){
            $('#dieukienluu').val($(this).find('#name_dk').text());
            $('#dieukienluu_id').val($(this).find('#id_dk').text());
            $('#dsdieukienluu').fadeOut();
       });*}
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
                                    {if $privnenmau.write}<button class="btn btn-xs btn-primary" onclick="_add(0)"><i class="ace-icon fa fa-plus"></i> {$languages.button_create}</button>{/if}
                                    {if $privdinhgia.write}<button class="btn btn-xs btn-warning" data-toggle="tooltip" onclick="_dinhgia_chat({$package})"><i class="ace-icon fa fa-dollar bigger-110"></i> Định giá</button> {/if}
                                </div>
                                <div class="pull-right">
                                    <label style="padding-right:10px">{$languages.search}: </label><input type="text" id="tukhoa" name="tukhoa">
                                </div>
                            </div>
                        </div>
                        <div class="space-2"></div>
                        <div class="widget-box widget-color-blue2">
                            <div class="widget-header">
                                <h4 class="widget-title lighter smaller">{$languages.title}</h4>
                            </div>

                            <div class="widget-body">
                                <div class="widget-main padding-8">
                                    <div id="tree"></div>
                                </div>
                            </div>
                        </div>
                        <!-- PAGE CONTENT ENDS -->
                    </div>
                </div>
            </div>
        </div>
    </div>
{/block}