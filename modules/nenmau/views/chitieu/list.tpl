	{* Extend our master template *}
{extends file="master.tpl"}
{block name=css}
    <link rel="stylesheet" href="{site_url()}assets/css/jquery-confirm.css" />
    <link rel="stylesheet" href="{site_url()}assets/css/stylevth.css" />
    <link rel="stylesheet" href="{$assets_path}css/chosen.min.css" />
    <link rel="stylesheet" href="{$assets_path}css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style"/>
    <link rel="stylesheet" href="{$assets_path}css/ace-skins.min.css" />
    <link rel="stylesheet" href="{$assets_path}css/ace-rtl.min.css" />
    <link rel="stylesheet" href="{$assets_path}css/select2.min.css" />
    <link rel="stylesheet" href="{site_url()}assets/css/denghi.css" />
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
        .ignore{
            display: none;
        }
        .chitieu-deactive{
            color: red;
            text-decoration: line-through;
        }
    </style>
{/block}
{block name=script}
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
                                <input class="form-control" type="hidden" id="dongia" name="dongia" />
                            </div>
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
                        <div class="modal-body form">
                            <input type="hidden" id="nenmau" name="nenmau" value="{$info_nenmau[0]->nenmau_id}">
                            <div class="form-group">
                                <label>{$languages.chitieu_tenchitieu}</label>
                                <input class="form-control" autocomplete="off" type="text" id="chitieu" name="chitieu" />
                                <input class="form-control" type="hidden" id="chitieu_id" name="chitieu_id"/>
                                <input type="hidden" value="" id="chitieu_cu" name="chitieu_cu" />
                                <input type="hidden" value="" id="chitieu_sua" name="chitieu_sua" />
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
                                <label>KQ/KF:</label>
                                <div class="radio">
                                    <label>
                                        <input name="kqkf" id="yes" checked type="radio" value="yes" class="ace" />
                                        <span class="lbl">Có</span>
                                    </label>
                                    <label>
                                        <input name="kqkf" id="no" type="radio" value="no" class="ace" />
                                        <span class="lbl"> Không</span>
                                    </label>
                                </div>
                                <input type="hidden" id="loai_donvi" name="loai_donvi" value="1">
                            </div>
                            <div class="form-row">    
                                <div class="form-group col-md-6" style="padding: 0 6px 0px 0px;">
                                    <label>{$languages.chitieu_thoigianluumau}</label>
                                    <input autocomplete="off" class="form-control" type="number" min="0" id="time_luu" name="time_luu" />
                                </div>
                                <div class="form-group col-md-6" style="padding: 0 6px 0px 0px;">
                                    <label>{$languages.chitieu_thoigianthuchien}</label>
                                    <input class="form-control" type="number" min="0" id="tat" name="tat" />
                                </div>    
                            </div>   
                            <div class="form-group">
                                <label>Phương pháp</label>
                                <select class="select2 form-control" style="width: 100%" id="phuongphap_id">
                                    <option value="0">{$languages.chitieu_chosen_phuongphap}</option>
                                    {foreach from=$phuongphap key=k item=v}
                                        <option data-loai="{$v['loai']}" value="{$k}">{$v["code"]}</option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="form-group pp_tham_khao ignore">
                                <label>Phương pháp tham khảo</label>
                                <select class="select2 form-control" style="width: 100%" id="phuongphap_id_bn">
                                    <option value="0">{$languages.chitieu_chosen_phuongphap}</option>
                                    {foreach from=$phuongphapBN key=k item=v}
                                        <option value="{$k}">{$v}</option>
                                    {/foreach}
                                </select>
                            </div>    
                            <div class="form-group">
                                <label>{$languages.chitieu_kythuat}</label>
                                <select class="chosen-select form-control" id="kythuat_id">
                                    <option value="0">{$languages.chitieu_chosen_kythuat}</option>
                                    {foreach from=$kythuat key=k item=v}
                                        <option value="{$k}">{$v}</option>
                                    {/foreach}
                                </select>
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
                           
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSave" onclick="luuchitieu()" class="btn btn-xs btn-primary">{$languages.chitieu_button_them}</button>
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
    <script src="{$assets_path}js/select2.min.js"></script>
    <script type="text/javascript">
    $(document).ready(function () {
        _load({$info_nenmau[0]->nenmau_id});
        customAutocomplete();
        searchChiTieu();
        searchKyThuat();
        $('.select2').select2({
            allowClear:true
        })
        .on('change', function(){
                //$(this).closest('form').validate().element($(this));
        });
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
    $("#phuongphap_id").on("change", function(){
       var loai = $('option:selected', this).data('loai');
       $("#phuongphap_id_bn").val("");
       if(loai == 1){
           $(".pp_tham_khao").show();
       }else{
           $(".pp_tham_khao").hide();
       }
    });
    function searchChiTieu(){
        //=== AUTOCOMPLETE CHITIEU
        $('#chitieu').on('focus', function () {
            $(this).catcomplete("search");
        });
        $('#chitieu').on('keydown.autocomplete', function () {
            $('#chitieu_id').val('');
            $("#chitieu_eng").attr("disabled", false);
            $("#mota").attr("disabled", false);
            $("input[name=kqkf]").attr('disabled', false);
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
                $("#chitieu_eng").attr("disabled", true);
                $("#mota").attr("disabled", true);
                $("input[name=kqkf]").attr('disabled', true);
            }
        });
    }
    
    function searchKyThuat(){
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
    
    function reload_table() {
        table.ajax.reload(null, false); //reload datatable ajax 
    }
    //end chất
    $("#tat").on("blur", function () {
        if (parseInt($("#tat").val()) < 0 || parseInt($("#tat").val()) == 0) {
            swal("{$languages.canhbao}", "{$languages.tat_validation}", "warning");
            $("#tat").val("1");
        }
    });
    function _load(id) {
        table = $('#table_chitieu').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],

            "ajax": {
                "url": "{site_url()}nenmau/chitieu/ajax_list?nenmau="+ id,
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
    function validate_formchitieu(){
        var kiemtra = true;
        if ($("#chitieu").val() == '') {
            swal("{$languages.canhbao}", "{$languages.chitieu_tenchitieu_validation}", "warning");
            kiemtra = false;
        }else if ($("#donvi").val() == '0') {
            swal("{$languages.canhbao}", "Bạn chưa chọn đơn vị tính", "warning");
            kiemtra = false;
        }else if ($("#phuongphap_id").val() == '0') {
            swal("{$languages.canhbao}", "{$languages.chitieu_phuongphap_validation}", "warning");
            kiemtra = false;
        } else if ($("#kythuat_id").val() == '0') {
            swal("{$languages.canhbao}", "{$languages.chitieu_kythuat_validation}", "warning");
            kiemtra = false;
        } else if ($("#phongthinghiem").val() == '0') {
            swal("{$languages.canhbao}", "{$languages.chitieu_donvi_validation}", "warning");
            kiemtra = false;
        } else if (parseInt($("#time_luu").val()) == 0 || parseInt($("#time_luu").val() < 0)) {
            swal("{$languages.canhbao}", "{$languages.chitieu_thoigianluumau_validation}", "warning");
            kiemtra = false;
        }
        return kiemtra;
    }
    function luuchitieu() {
        if (validate_formchitieu() == true) {
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
                                    chitieu_id: $("#chitieu_id").val(),
                                    chitieu_eng: $("#chitieu_eng").val(),
                                    mota: $("#mota").val(),
                                    phuongphap_id: $("#phuongphap_id").val(),
                                    phuongphap_id_bn: $("#phuongphap_id_bn").val(),
                                    kythuat_id: $("#kythuat_id").val(),
                                    phongthinghiem: $("#phongthinghiem").val(),
                                    donvi: $("#donvi").val(),
                                    thoigian: $("#tat").val(),
                                    thoigian_luu: $("#time_luu").val(),
                                    kqkf: $('input[name=kqkf]:checked').val()
                                },
                                url: "{site_url()}nenmau/chitieu/themchitieu",
                                success: function (data) {
                                    if (data == 1) {
                                        swal("{$languages.thanhcong}", "{$languages.chitieu_them_success}", "success");
                                        $("#chitieu_form").modal('hide');
                                        $("#form")[0].reset();
                                        reload_table();
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
        if (validate_formchitieu() == true) {
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
                                        reload_table();
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
                                reload_table();
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
    function dinhgia(gia, dongia) {
        $('.title-gia').text('{$languages.giachitieu_title_1}');
        $("#giatien").val(gia);
        $("#dongia").val(dongia);
        $("#gia_form").modal("show");
        
        $('#giatien').focus();
    }
    function setgia() {
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
                                    dongia: $("#dongia").val()
                                },
                                url: "{site_url()}nenmau/chitieu/setgia",
                                success: function (data) {
                                    swal("{$languages.thanhcong}", "{$languages.giachitieu_success}", "success");
                                    reload_table();
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
                        <h3 class="header smaller lighter blue">Danh Sách Nhóm Chỉ Tiêu</h3>
                        <div class="row" style="margin-bottom:5px;">
                            <div class="col-xs-5">
                                {if $privchitieu.write}<button class="btn btn-xs btn-primary" onclick="_add_chitieu()"><i class="ace-icon fa fa-plus"></i> Nhóm Chỉ Tiêu </button>{/if}
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
                                    <th>Nhóm Chỉ Tiêu</th>
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