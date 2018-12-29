{* Extend our master template *}
{extends file="master.tpl"}
{block name=css}
    <link rel="stylesheet" href="{site_url()}assets/css/jquery-confirm.css" />
    <link rel="stylesheet" href="{site_url()}assets/css/stylevth.css" />
    <link rel="stylesheet" href="{$assets_path}css/chosen.min.css" />
    <link rel="stylesheet" href="{$assets_path}css/ace.min.css" />
    <style>
        #quytrinh-muasam{
            width: 100%;
            margin: 0;
            display: table;
            table-layout: fixed;
        }
        #quytrinh-muasam li{
            display: table-cell;
            padding-right: 20px;
        }
        #quytrinh-muasam .label-xlg{
            font-size: 14px;
            padding: 15px 0;
            margin: 0;
            height: 50px;
            width: 100%;
        }
        #quytrinh-muasam .label-xlg.arrowed-in:before{
            border-width: 25px 10px;
            left: -10px;
        }
        #quytrinh-muasam .label-xlg.arrowed-right:after{
            border-width: 25px 10px;
            right: -20px;
        }
        #quytrinh-muasam .not-allowed a{
            cursor: not-allowed;
        }
    </style>
{/block}
{block name=script}
    <div class="modal fade" id="history_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title" id="history_title"></h3>
                </div>
                <div class="modal-body">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>{$languages.table_sanpham}</th>
                                <th>{$languages.table_soluong}</th>
                                <th>{$languages.table_hang}</th>
                                <th>{$languages.table_nhacungcap}</th>
                                <th>{$languages.table_gia}</th>
                            </tr>
                        </thead>
                        <tbody id="history_body">
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-xs btn-danger" data-dismiss="modal">Thoát</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <script src="{$assets_path}plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="{$assets_path}plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script src="{site_url()}assets/js/sweetalert.min.js"></script>
    <script src="{site_url()}assets/js/jquery-confirm.js"></script>
    <script src="{$assets_path}js/chosen.jquery.min.js"></script>
    <script>
        function history(denghi_id, name, vesion) {
            $.ajax({
                type: "POST",
                data: {
                    'denghi_id': denghi_id,
                    'denghi_vesion': vesion
                },
                url: "{site_url()}muasam/yeucau/history",
                success: function (data) {
                    $("#history_title").text("{$languages.history_title} " + name);
                    $("#history_body").html('');
                    $("#history_body").append(data);
                    $("#history_modal").modal("show");
                }
            });
        }
        $(document).ready(function () {
            $('.chosen-select', this).chosen();
        });

        var ds_sanpham = [];

        {foreach $denghi_detail as $value}
        ds_sanpham.push({$value->dn_detail_id});
        var sl_sanpham = {$value->dn_detail_id};
        {/foreach}

        function add_sanpham(level) {
            var sp_id = '<div class="col-md-2"><div class="form-group" id="sp_id' + level + '"><label class="control-label">{$languages.sanpham}</label><select class="chosen-select form-control" name="sp_id" id="sp_id' + level + '" level="' + level + '"><option value="0">{$languages.chosen_sanpham}</option>{foreach from=$sanpham key=k item=v}<option value="{$k}">{$v}</option>{/foreach}</select></div></div>';
            var dn_detail_soluong = '<div class="col-md-2"><div class="form-group" id="dn_detail_soluong' + level + '"><label class="control-label">{$languages.soluong}</label><input class="form-control" type="number" min="0" name="dn_detail_soluong" id="dn_detail_soluong' + level + '" placeholder="{$languages.placeholder_soluong}" value="" level="' + level + '"></div></div>';
            var hang_id = '<div class="col-md-2"><div class="form-group" id="hang_id' + level + '"><label class="control-label">{$languages.hang}</label><select class="chosen-select form-control" name="hang_id" id="hang_id' + level + '" level="' + level + '"><option value="0">{$languages.chosen_hang}</option>{foreach from=$hang key=k item=v}<option value="{$k}">{$v}</option>{/foreach}</select></div></div>';
            var donvitinh_id = '<div class="col-md-2"><div class="form-group" id="donvitinh_id' + level + '"><label class="control-label">{$languages.donvi}</label><select class="chosen-select form-control" name="donvitinh_id" id="donvitinh_id' + level + '" level="' + level + '">{foreach from=$donvitinh key=k item=v}<option value="{$k}">{$v}</option>{/foreach}</select></div></div>';
            var ghichu = '<div class="col-md-4"><div class="form-group" id="ghichu' + level + '"><label class="control-label">Ghi Chú</label><input class="form-control" type="text" name="ghichu" id="ghichu' + level + '" placeholder="" value="" level="' + level + '"></div></div>';
            $(".class-sanpham").append('<div class="row">' + sp_id + dn_detail_soluong + donvitinh_id + hang_id + ghichu + '</div>');
            $('select[name=loai_id][level=' + level + ']').chosen('destroy').chosen();
            $('select[name=sp_id][level=' + level + ']').chosen('destroy').chosen();
            $('select[name=hang_id][level=' + level + ']').chosen('destroy').chosen();
            $('select[name=donvitinh_id][level=' + level + ']').chosen('destroy').chosen();
        }

        function _add_sanpham() {
            var kiemtra = true;
            var err_code = "";
            if ($('select[name=loai_id][level=' + sl_sanpham + ']').val() == '0') {
                kiemtra = false;
                $('#loai_id' + sl_sanpham).addClass('has-error');
                err_code = "{$languages.err_loai}";
            } else if ($('select[name=sp_id][level=' + sl_sanpham + ']').val() == '0') {
                kiemtra = false;
                $('#sp_id' + sl_sanpham).addClass('has-error');
                err_code = "{$languages.err_sanpham}";
            } else if ($('input[name=dn_detail_soluong][level=' + sl_sanpham + ']').val() == '') {
                kiemtra = false;
                $('#dn_detail_soluong' + sl_sanpham).addClass('has-error');
                err_code = "{$languages.err_soluong}";
            } else if ($('select[name=hang_id][level=' + sl_sanpham + ']').val() == '0') {
                kiemtra = false;
                $('#hang_id' + sl_sanpham).addClass('has-error');
                err_code = "{$languages.err_hang}";
            }
            if (kiemtra == true) {
                $('#loai_id' + sl_sanpham).removeClass('has-error');
                $('#sp_id' + sl_sanpham).removeClass('has-error');
                $('#dn_detail_soluong' + sl_sanpham).removeClass('has-error');
                $('#hang_id' + sl_sanpham).removeClass('has-error');
                sl_sanpham++;
                ds_sanpham.push(sl_sanpham);
                add_sanpham(sl_sanpham);
            } else {
                swal("{$languages.canhbao}", err_code, "warning");
            }
        }

        function load_sanpham(level_check, check) {
            $.ajax({
                type: "POST",
                data: {
                    loai_id: $('select[name=loai_id][level=' + level_check + ']').val()
                },
                url: "{site_url()}muasam/yeucau/get_sanpham",
                success: function (data) {
                    $('select[name=sp_id][level=' + level_check + ']').html('');
                    $('select[name=sp_id][level=' + level_check + ']').append(data);
                    $('select[name=sp_id][level=' + level_check + ']').chosen('destroy').chosen();
                    if (check != "") {
                        $('select[name=sp_id][level=' + level_check + ']').val(check).trigger("chosen:updated");
                    }
                }
            });
        }

        function _cancel() {
            window.location = "{site_url()}muasam/yeucau";
        }

        function _create() {
            var danhsach_sp_id = [];
            var danhsach_dn_detail_soluong = [];
            var danhsach_hang_id = [];
            var danhsach_donvitinh_id = [];
            var danhsach_ghichu = [];
            var check_false = true;
            for (i = 0; i < ds_sanpham.length; i++) {
                var kiemtra = true;
                var check_false2 = 0;
                var check_false3 = 0;
                var check_false4 = 0;
                if ($('select[name=sp_id][level=' + ds_sanpham[i] + ']').val() == '0') {
                    kiemtra = false;
                    check_false2 = 2;
                    $('#sp_id' + ds_sanpham[i]).addClass('has-error');
                }
                ;
                if ($('input[name=dn_detail_soluong][level=' + ds_sanpham[i] + ']').val() == '' || $('input[name=dn_detail_soluong][level=' + ds_sanpham[i] + ']').val() == '0') {
                    kiemtra = false;
                    check_false3 = 3;
                    $('#dn_detail_soluong' + ds_sanpham[i]).addClass('has-error');
                }
                ;
                if ($('select[name=hang_id][level=' + ds_sanpham[i] + ']').val() == '0') {
                    kiemtra = false;
                    check_false4 = 4;
                    $('#hang_id' + ds_sanpham[i]).addClass('has-error');
                }
                ;
                if ((check_false2 == 0 && check_false3 == 0 && check_false4 == 0) || (check_false3 == 3)) {
                } else {
                    check_false = false;
                }
                if (kiemtra == true) {
                    danhsach_sp_id.push($('select[name=sp_id][level=' + ds_sanpham[i] + ']').val());
                    danhsach_dn_detail_soluong.push($('input[name=dn_detail_soluong][level=' + ds_sanpham[i] + ']').val());
                    danhsach_hang_id.push($('select[name=hang_id][level=' + ds_sanpham[i] + ']').val());
                    danhsach_donvitinh_id.push($('select[name=donvitinh_id][level=' + ds_sanpham[i] + ']').val());
                    danhsach_ghichu.push($('input[name=ghichu][level=' + ds_sanpham[i] + ']').val());
                }
            }
            if (check_false == false) {
                swal("{$languages.canhbao}", "{$languages.dulieusai}", "warning");
            } else {
                if ($("#denghi_title").val() == "") {
                    swal("{$languages.canhbao}", "{$languages.validation_tieude}", "warning");
                } else if ($("#denghi_describe").val() == "") {
                    swal("{$languages.canhbao}", "{$languages.validation_mota}", "warning");
                } else {
                    $.confirm({
                        title: '{$languages.xacnhan}',
                        content: '{$languages.xacnhan_title_update}',
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
                                            'quytrinh_id': 1,
                                            'denghi_title': $("#denghi_title").val(),
                                            'nhansu_nhan': $("#nhansu_nhan").val(),
                                            'denghi_describe': $("#denghi_describe").val(),
                                            'sp_id': danhsach_sp_id,
                                            'dn_detail_soluong': danhsach_dn_detail_soluong,
                                            'hang_id': danhsach_hang_id,
                                            'denghi_id_update': '{$denghi->denghi_id}',
                                            'donvitinh_id': danhsach_donvitinh_id,
                                            'ghichu' : danhsach_ghichu
                                        },
                                        url: "{site_url()}muasam/yeucau/create",
                                        dataType: "text",
                                        success: function (data) {
                                            if (data == 1) {
                                                window.location = "{site_url()}muasam/yeucau";
                                            } else {
                                                swal("{$languages.thatbai}", "{$languages.thatbai_false2}", "error");
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
                    <li class="active"><a href="{site_url()}muasam/yeucau">{$languages.url_2}</a></li>
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
                        <h4 class="header green">
                            <i class="fa fa-car red" aria-hidden="true"></i> {$languages.detail_title1}
                            <div class="widget-toolbar">
                                <a href="#history" onclick="history({$denghi->denghi_id}, '{$denghi->denghi_title}',{$denghi->denghi_vesion})">
                                    <i class="ace-icon fa fa-bookmark"></i> {$languages.history}
                                </a>
                            </div>
                        </h4>
                        <ul id="quytrinh-muasam">
                            <li><span class="label label-xlg arrowed-right label-info {if $quytrinh>=1} label-success {else} label-info {/if}">{$languages.procedure_1}</span></a></li>
                            <li><span class="label label-xlg arrowed-right arrowed-in arrowed {if $quytrinh == 1} label-danger {elseif $quytrinh>=2} label-success {else} label-info {/if}">{$languages.procedure_2}</span></li>
                            <li><span class="label label-xlg arrowed-right arrowed-in label-info arrowed {if $quytrinh == 2} label-danger {elseif $quytrinh>=3} label-success {else} label-info {/if}">{$languages.procedure_3}</span></li>
                            <li><span class="label label-xlg arrowed-right arrowed-in label-info arrowed {if $quytrinh == 3} label-danger {elseif $quytrinh>=4} label-success {else} label-info {/if}">{$languages.procedure_4}</span></li>
                            <li><span class="label label-xlg arrowed-right arrowed-in label-info arrowed {if $quytrinh == 4} label-danger {elseif $quytrinh>=5} label-success {else} label-info {/if}">{$languages.procedure_5}</span></li>
                            <li><span class="label label-xlg arrowed-right arrowed-in label-info arrowed {if $quytrinh == 5&&$denghi->denghi_success==1} label-danger {elseif $denghi->denghi_success==2} label-success {else} label-info {/if}">{$languages.procedure_6}</span></li>
                        </ul>
                        {if $denghi_approve!=""}
                            <h4 class="header green"><i class="fa fa-exclamation-triangle red" aria-hidden="true"></i> {$languages.detail_title4}</h4>
                            <div class="alert alert-danger" style="margin-top:10px">
                                {foreach from=$denghi_approve item=comment}
                                    <p>- {$comment->approve_comment}</p>
                                {/foreach}
                            </div>
                        {/if}
                        <h4 class="header green"><i class="fa fa-info-circle red" aria-hidden="true"></i> {$languages.detail_title2}</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="congty_email">{$languages.tieude}</label>
                                    <input class="form-control data-info" type="text" id="denghi_title" name="denghi_title" placeholder="{$languages.placeholder_tieude}" value="{$denghi->denghi_title}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="congty_phone">{$languages.nguoiduyet}</label>
                                    <select class="chosen-select form-control" id="nhansu_nhan" name="nhansu_nhan">
                                        {foreach from=$nhansu key=k item=v}
                                            <option {if $denghi->nhansu_nhan==$k} selected {/if} value="{$k}">{$v}</option>
                                        {/foreach}
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label class="control-label" for="congty_name">{$languages.ghichu} <span class="red">*</span></label>
                            <textarea class="form-control" name="denghi_describe" id="denghi_describe" rows="5" placeholder="{$languages.placeholder_ghichu}">{$denghi->denghi_describe}</textarea>
                        </div>
                        <h4 class="header green"><i class="fa fa-product-hunt red" aria-hidden="true"></i> {$languages.detail_title3}</h4>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="widget-box">
                                    <div class="widget-header">
                                        <h4 class="widget-title">{$languages.detaile_title_sanpham}</h4>
                                        {if $quytrinh==1}
                                            <div class="widget-toolbar">
                                                <button onclick="_add_sanpham()" class="btn btn-xs btn-primary"><i class="ace-icon fa fa-plus"></i> {$languages.sanpham}</button>
                                            </div>
                                        {/if}
                                    </div>
                                    <div class="widget-body">
                                        <div class="widget-main class-sanpham">
                                            {foreach $denghi_detail as $value}
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <div class="form-group" id="sp_id{$value->dn_detail_id}">
                                                            <label class="control-label">{$languages.sanpham}</label>
                                                            <select class="chosen-select form-control" name="sp_id" level="{$value->dn_detail_id}">
                                                                <option value="0">{$languages.chosen_sanpham}</option>
                                                                {foreach from=$sanpham key=k item=v}
                                                                    <option {if $value->sp_id==$k} selected {/if} value="{$k}">{$v}</option>
                                                                {/foreach}
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group" id="dn_detail_soluong{$value->dn_detail_id}">
                                                            <label class="control-label">{$languages.soluong}</label>
                                                            <input class="form-control" type="number" min="0" name="dn_detail_soluong" placeholder="{$languages.placeholder_soluong}" value="{$value->dn_detail_soluong}" level="{$value->dn_detail_id}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group" id="donvitinh_id{$value->dn_detail_id}">
                                                            <label class="control-label">{$languages.donvi}</label>
                                                            <select class="chosen-select form-control" name="donvitinh_id" level="{$value->dn_detail_id}">
                                                                {foreach from=$donvitinh key=k item=v}
                                                                    <option {if $value->donvitinh_id==$k} selected {/if} value="{$k}">{$v}</option>
                                                                {/foreach}
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group" id="hang_id{$value->dn_detail_id}">
                                                            <label class="control-label">{$languages.hang}</label>
                                                            <select class="chosen-select form-control" name="hang_id" level="{$value->dn_detail_id}">
                                                                <option value="0">{$languages.chosen_hang}</option>
                                                                {foreach from=$hang key=k item=v}
                                                                    <option {if $value->hang_id==$k} selected {/if} value="{$k}">{$v}</option>
                                                                {/foreach}
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group" id="ghichu{$value->dn_detail_id}">
                                                            <label class="control-label">Ghi Chú</label>
                                                            <input class="form-control" type="text" name="ghichu" value="{$value->dn_detail_describe}" level="{$value->dn_detail_id}">
                                                        </div>
                                                    </div>
                                                </div>
                                            {/foreach}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {if $quytrinh==1}
                            <div class="clearfix form-actions">
                                <div class="col-md-offset-4 col-md-9">
                                    <button class="btn btn-md btn-danger" type="button" onclick="_cancel()">
                                        <i class="ace-icon fa fa-chevron-left bigger-110"></i> {$languages.detail_cancel}
                                    </button>
                                    <button class="btn btn-md btn-primary" type="button" onclick="_create()">
                                        <i class="ace-icon fa fa-share-square-o bigger-110"></i> {$languages.detail_update}
                                    </button>
                                </div>
                            </div>
                        {/if}
                    </div>
                </div>
            </div>
        </div>
    </div>
{/block}