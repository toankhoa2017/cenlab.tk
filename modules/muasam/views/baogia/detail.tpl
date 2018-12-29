{* Extend our master template *}
{extends file="master.tpl"}
{block name=css}
    <link rel="stylesheet" href="{site_url()}assets/css/jquery-confirm.css" />
    <link rel="stylesheet" href="{site_url()}assets/css/stylevth.css" />
    <link rel="stylesheet" href="{$assets_path}css/chosen.min.css" />
    <link rel="stylesheet" href="{$assets_path}css/ace.min.css" />
    <link rel="stylesheet" href="{site_url()}assets/css/dropzone.min.css" />
    <link rel="stylesheet" href="{$assets_path}plugins/colorbox/css1/colorbox.css" />
    <style>
        body {
            overflow-x: hidden;
        }
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
        .lock_cha{
            position: relative;
        }
        .lock_cha .lock_con{
            width: 100%;
            position: absolute;
            height: 100%;
            top: 0;
            left: 0;
            background: rgba(238, 238, 238, 0);
            z-index: 100;
        }
        #divattach {
            display: none;
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
                    <button type="button" class="btn btn-xs btn-danger" data-dismiss="modal">Cancel</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <script src="{$assets_path}plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="{$assets_path}plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script src="{site_url()}assets/js/sweetalert.min.js"></script>
    <script src="{site_url()}assets/js/jquery-confirm.js"></script>
    <script src="{$assets_path}js/chosen.jquery.min.js"></script>
    <script src="{site_url()}assets/js/jquery.number.js"></script>
    <script src="{$assets_path}plugins/colorbox/jquery.colorbox.js"></script>
    <script>
        function upfile() {
            $('#divattach').attr("style", "display: block");
        }
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
            {foreach $denghi_detail as $value}
                var id_sp = {$value->sp_id};
                var id_ncc =  $('input[name=ncc_id1][level={$value->dn_detail_id}]').val();
                if(id_ncc == '') {
                    var ncc_id = 0;
                }
                else{
                    var ncc_id = id_ncc;
                }
                $.ajax({
                    url: "{site_url()}muasam/baogia/ListNccSP",
                    type: "POST",
                    data: { id_sp : id_sp, ncc_id : ncc_id },
                    success: function (data) {
                        $('select[name=ncc_id][level={$value->dn_detail_id}]').html(data);
                        $('select[name=ncc_id][level={$value->dn_detail_id}]').trigger("chosen:updated");
                    }
                });
                
            {/foreach}
        });
        $(function () {
            $('input[name=dn_detail_dongia]').number(true, 0);
        });
        var ds_sanpham = [];

        {foreach $denghi_detail as $value}
        ds_sanpham.push({$value->dn_detail_id});
        {/foreach}
        function _cancel() {
            window.location = "{site_url()}muasam/baogia";
        }

        function _create() {
            var danhsach_sp_id = [];
            var danhsach_dn_detail_soluong = [];
            var danhsach_hang_id = [];
            var danhsach_ncc_id = [];
            var danhsach_dn_detail_dongia = [];
            var danhsach_donvitinh_id = [];
            var danhsach_ghichu = [];
            var check_false = true;
            for (i = 0; i < ds_sanpham.length; i++) {
                var kiemtra = true;
                var check_false2 = 0;//sản phẩm
                var check_false3 = 0;//số lượng
                var check_false4 = 0;//hãng sản xuất
                var check_false5 = 0;//nhà cung cấp
                var check_false6 = 0;//giá tiền
                if ($('input[name=sp_id][level=' + ds_sanpham[i] + ']').val() == '') {
                    kiemtra = false;
                    check_false2 = 2;
                    $('#sp_id' + ds_sanpham[i]).addClass('has-error');
                }
                ;
                if ($('input[name=dn_detail_soluong][level=' + ds_sanpham[i] + ']').val() == '') {
                    kiemtra = false;
                    check_false3 = 3;
                    $('#dn_detail_soluong' + ds_sanpham[i]).addClass('has-error');
                }
                ;
                if ($('input[name=hang_id][level=' + ds_sanpham[i] + ']').val() == '') {
                    kiemtra = false;
                    check_false4 = 4;
                    $('#hang_id' + ds_sanpham[i]).addClass('has-error');
                }
                ;
                if ($('select[name=ncc_id][level=' + ds_sanpham[i] + ']').val() == '0') {
                    kiemtra = false;
                    check_false5 = 5;
                    $('#ncc_id' + ds_sanpham[i]).addClass('has-error');
                }
                ;
                if ($('input[name=dn_detail_dongia][level=' + ds_sanpham[i] + ']').val() == '') {
                    kiemtra = false;
                    check_false6 = 6;
                    $('#dn_detail_dongia' + ds_sanpham[i]).addClass('has-error');
                }
                ;
                if ((check_false2 == 0 && check_false3 == 0 && check_false4 == 0 && check_false5 == 0 && check_false6 == 0) || (check_false2 == 2 && check_false3 == 3 && check_false4 == 4 && check_false5 == 5 && check_false6 == 6)) {
                } else {
                    check_false = false;
                }
                ;
                if (kiemtra == true) {
                    danhsach_sp_id.push($('input[name=sp_id][level=' + ds_sanpham[i] + ']').val());
                    danhsach_dn_detail_soluong.push($('input[name=dn_detail_soluong][level=' + ds_sanpham[i] + ']').val());
                    danhsach_hang_id.push($('input[name=hang_id][level=' + ds_sanpham[i] + ']').val());
                    danhsach_ncc_id.push($('select[name=ncc_id][level=' + ds_sanpham[i] + ']').val());
                    danhsach_dn_detail_dongia.push($('input[name=dn_detail_dongia][level=' + ds_sanpham[i] + ']').val());
                    danhsach_donvitinh_id.push($('input[name=donvitinh_id][level=' + ds_sanpham[i] + ']').val());
                    danhsach_ghichu.push($('input[name=dn_detail_describe][level=' + ds_sanpham[i] + ']').val());
                }
                ;
            }
            if (check_false == false) {
                swal("{$languages.canhbao}", "{$languages.dulieusai}", "warning");
            } else {
                if ($("#nhansu_nhan").val() == "0") {
                    swal("{$languages.canhbao}", "{$languages.chuachonnguoi}", "warning");
                } else {
                    $.confirm({
                        title: '{$languages.xacnhan}',
                        content: '{$languages.xacnhan_title}',
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
                                            'quytrinh_id': 3,
                                            'denghi_id': '{$denghi->denghi_id}',
                                            'denghi_ref': '{$denghi->denghi_ref}',
                                            'denghi_vesion': '{$denghi->denghi_vesion}',
                                            'denghi_title': $("#denghi_title").val(),
                                            'nhansu_nhan': $("#nhansu_nhan").val(),
                                            'denghi_describe': $("#denghi_describe").val(),
                                            'sp_id': danhsach_sp_id,
                                            'dn_detail_soluong': danhsach_dn_detail_soluong,
                                            'hang_id': danhsach_hang_id,
                                            'dn_detail_dongia': danhsach_dn_detail_dongia,
                                            'ncc_id': danhsach_ncc_id,
                                            'donvitinh_id': danhsach_donvitinh_id,
                                            'ghichu' : danhsach_ghichu
                                        },
                                        url: "{site_url()}muasam/yeucau/create",
                                        dataType: "text",
                                        success: function (data) {
                                            if (data == 1) {
                                                window.location = "{site_url()}muasam/baogia";
                                            } else {
                                                swal("{$languages.thatbai}", "{$languages.daduyet}", "error");
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
        }


        function upload() {
            var files = $('#files')[0].files;
            var form_data = new FormData();
            for(var count = 0; count<files.length; count++)
            {
                form_data.append("files[]", files[count]);
            }
            $.ajax({
                url:"{site_url()}muasam/baogia/upfilebaogia?id=" + {$denghi->denghi_id},
                method:"POST",
                data:form_data,
                contentType:false,
                cache:false,
                processData:false,
                success:function(data)
                {
                    swal("Thông Báo", "Upload báo giá thành công", "error");
                    location.reload();
                }
            });
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
                    <li class="active"><a href="{site_url()}muasam/baogia">{$languages.url_2}</a></li>
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
                        <h4 class="header green"><i class="fa fa-car red" aria-hidden="true"></i> {$languages.detail_title1}
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
                            <h4 class="header green"><i class="fa fa-exclamation-triangle red" aria-hidden="true"></i> {$languages.lydokhongduyet}</h4>
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
                                    <input disabled class="form-control data-info" type="text" id="denghi_title" name="denghi_title" placeholder="{$languages.placeholder_tieude}" value="{$denghi->denghi_title}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="congty_phone">{$languages.nguoiduyet}</label>
                                    <select class="chosen-select form-control" id="nhansu_nhan" name="nhansu_nhan">
                                        <option value="0">{$languages.chosen_nguoiduyet}</option>
                                        {foreach from=$nhansu key=k item=v}
                                            <option {if $denghi->denghi_vesion==3&&$denghi->nhansu_nhan==$k} selected {/if} value="{$k}">{$v}</option>
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
                            <div class="col-md-8">
                                <div class="widget-box">
                                    <div class="widget-header">
                                        <h4 class="widget-title">{$languages.detaile_title_sanpham}</h4>
                                    </div>
                                    <div class="widget-body">
                                        <div class="widget-main class-sanpham lock_cha">
                                            <div class="lock_con" style="">
                                            </div>
                                            {foreach $denghi_detail as $value}
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group" id="sp_id{$value->dn_detail_id}">
                                                            <label class="control-label">{$languages.sanpham}</label>
                                                            <input class="form-control" type="hidden" name="sp_id" value="{$value->sp_id}" level="{$value->dn_detail_id}">
                                                            <input class="form-control" type="text" value="{$value->sp_name}" >
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group" id="dn_detail_soluong{$value->dn_detail_id}">
                                                            <label class="control-label">{$languages.soluong}</label>
                                                            <input class="form-control" type="number" min="0" name="dn_detail_soluong" value="{$value->dn_detail_soluong}" level="{$value->dn_detail_id}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group" id="donvitinh_id{$value->dn_detail_id}">
                                                            <label class="control-label">{$languages.donvi}</label>
                                                            <input class="form-control" type="hidden" name="donvitinh_id" value="{$value->donvitinh_id}" level="{$value->dn_detail_id}">
                                                            <input class="form-control" type="text" value="{$value->donvitinh_name}" >
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group" id="hang_id{$value->dn_detail_id}">
                                                            <label class="control-label">{$languages.hang}</label>
                                                            <input class="form-control" type="hidden" name="hang_id" value="{$value->hang_id}" level="{$value->dn_detail_id}">
                                                            <input class="form-control" type="text" value="{$value->hang_name}" >
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group" id="dn_detail_describe{$value->dn_detail_id}">
                                                            <label class="control-label">Ghi Chú</label>
                                                            <input class="form-control" type="text" name="dn_detail_describe" value="{$value->dn_detail_describe}" level="{$value->dn_detail_describe}">
                                                        </div>
                                                    </div>
                                                </div>
                                            {/foreach}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="widget-box">
                                    <div class="widget-header">
                                        <h4 class="widget-title">{$languages.detail_gia_title}</h4>
                                    </div>
                                    <div class="widget-body">
                                        <div class="widget-main class-baogia">
                                            {foreach $denghi_detail as $value}
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group" id="ncc_id{$value->dn_detail_id}">
                                                            <label class="control-label">{$languages.nhacungcap}</label>
                                                            <input class="form-control" type="hidden" name="ncc_id1" value="{$value->ncc_id}" level="{$value->dn_detail_id}">
                                                            <select class="chosen-select form-control" name="ncc_id" level="{$value->dn_detail_id}">
                                                                {*<option value="0">{$languages.chosen_nhacungcap}</option>
                                                                {foreach from=$nhacungcap key=k item=v}
                                                                    <option {if $value->ncc_id==$k} selected {/if} value="{$k}">{$v}</option>
                                                                {/foreach}*}
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group" id="dn_detail_dongia{$value->dn_detail_id}">
                                                            <label class="control-label">{$languages.giatien}</label>
                                                            <input class="form-control" type="text" name="dn_detail_dongia" placeholder="{$languages.placeholder_giatien}" level="{$value->dn_detail_id}" value="{$value->dn_detail_dongia}">
                                                        </div>
                                                    </div>
                                                </div>
                                            {/foreach}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h4 class="header green"><i class="fa fa-upload red" aria-hidden="true"></i> {$languages.detail_title4}</h4>
                        <div class="widget-box">
                            <div class="widget-header">
                                <h4 class="widget-title">{$languages.detail_baogia_title}</h4>
                                <div class="widget-toolbar">
                                    <button class="btn btn-md btn-primary" type="button" onclick="upfile()" title="Xử Lý File Hợp Đồng">
                                        <i class="ace-icon fa fa-plus"></i> {$languages.detail_baogia_create}
                                    </button>
                                </div>
                            </div>
                            <div class="widget-body">
                                <div class="widget-main" id="danhsach_file">
                                    {foreach from=$danhsach_file item=dsf}
                                    <div class="row">
                                        <div class="col-md-11">
                                            <div class="form-group"><a href="{site_url()}muasam/baogia/download?id_denghi={$dsf['denghi_id']}&id_file={$dsf['id_file']}">{$dsf['path_file']}</a></div>
                                        </div>
                                        <div class="col-md-1">
                                            <div>
                                                <button class="btn btn-xs btn-danger" onclick="_xoa({$value->dn_detail_id})" data-toggle="tooltip" title="Xóa"><i class="ace-icon fa fa-trash-o bigger-120"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    {/foreach}
                                </div>
                            </div>
                        </div>
                        <div class="form-group" id="divattach">     
                            <p id="msg"></p>
                            <input type="file" id="files" name="files" multiple />
                            <input type="hidden" id="id_fileattach" name="id_fileattach" />
                            <div>
                                <button class="btn btn-info" id="upload" onclick="upload();"><i class="ace-icon fa fa-cloud-upload bigger-110"></i>Upload</button>
                            </div>
                        </div>         
                        {if $quytrinh >=2&& $quytrinh !=5 }
                            <div class="clearfix form-actions">
                                <div class="col-md-offset-4 col-md-9"> 
                                    <button class="btn btn-md btn-danger" type="button" onclick="_cancel()">
                                        <i class="ace-icon fa fa-chevron-left bigger-110"></i> {$languages.detail_cancel}
                                    </button>
                                    {if $denghi->denghi_approve==0||$denghi->denghi_approve==2}
                                        <button class="btn btn-md btn-primary" type="button" onclick="_create()">
                                            <i class="ace-icon fa fa-share-square-o bigger-110"></i> {$languages.detail_create}
                                        </button>
                                    {/if}
                                </div>
                            </div>
                        {/if}
                    </div>
                </div>
            </div>
        </div>
    </div>
{/block}