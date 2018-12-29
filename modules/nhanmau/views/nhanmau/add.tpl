{* Extend our master template *}
{extends file="master.tpl"}
{block name=css}
    <link href="{$assets_path}css/jquery.gritter.min.css" rel="stylesheet">
    <link href="{$assets_path}css/bootstrap-datepicker3.min.css" rel="stylesheet">
    <link href="{base_url()}assets/tuanlm/jquery.multiselect.css" rel="stylesheet">
    <style type="text/css">
        .list_chitieu label,
        .list_chitieu .form-control,
        .list_chitieu output,
        table.list_chitieu{
            font-size: 13px !important;
        }
        .table>thead>tr>th{
            background: #eeeeee;
        }
        .table>tbody>tr>td, .table>tbody>tr>th, 
        .table>tfoot>tr>td, .table>tfoot>tr>th, 
        .table>thead>tr>td, .table>thead>tr>th{
            vertical-align: middle;
        }
        .table-striped>tbody>tr:nth-of-type(odd){
            background: #ffffff;
        }
        .table-striped>tbody>tr:nth-of-type(even){
            background: #f9f9f9;
        }
        .mau_e{
            margin-bottom: 20px;
            border: 1px solid #6fb3e0;
            border-top-width: 3px;
        }
        .mau_e .form-group{
            margin-bottom: 8px;
        }
        .mau_e .panel-group{
            margin: 0;
        }
        .lbl_mau_order{
            padding: 4px 0;
            font-weight: bold;
            color: #438eb9;
            font-size: 14px;
            display: block;
            float: left;
        }
        .mau_chitieu{
            position: relative;
        }
        .mau_chitieu .chitieu_cover{
            width: 100%;
            position: absolute;
            height: 100%;
            top: 0;
            left: 0;
            background: rgba(238, 238, 238, 0.8);
            z-index: 3;
        }
        .mau_chitieu .chitieu_cover span{
            text-transform: uppercase;
            font-size: 14px;
            color: #d15b47;
            padding: 8px;
            background: #fff;
            border: #d15b47 solid 0.5px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        .list_chitieu{
            margin: 0;
        }
        .list_chitieu ul{
            margin-left: 16px;
            margin-bottom: 0;
        }
        .mau_e .other-info{
            background: none;
            padding: 0;
        }
        .mau_e .other-info .checkbox,
        .mau_e .other-info .radio{
            margin: 0;
        }
        .mau_e .other-info .form-group{
            margin: 0;
        }
        .ui-datepicker{
            z-index: 3!important;
        }
        .ui-autocomplete {
            max-height: 200px;
            overflow-y: auto;
            /* prevent horizontal scrollbar */
            overflow-x: hidden;
            /* add padding to account for vertical scrollbar */
            padding-right: 20px;
        }
        .has-error .ms-options-wrap > button:focus,
        .has-error .ms-options-wrap > button {
            border-color: #f2a696!important;
        }
        .checkbox label,
        .radio label{
            padding-left: 10px;
        }
        #gritter-notice-wrapper{
            width: auto;
        }
        .modal-open #gritter-notice-wrapper{
            right: 37px;
        }
        .gritter-close {
            left: auto;
            right: 4px;
        }
        #upload-file{
            border: 2px dashed #dcdcdc;
            height: 100px;
            margin-bottom: 8px;
        }
        #upload-file.in {
            background: #f3f3f3;
        }
        #upload-file.hover {
            border-color: #46a5e5;
        }
        #upload-file.fade {
            -webkit-transition: all 0.3s ease-out;
            -moz-transition: all 0.3s ease-out;
            -ms-transition: all 0.3s ease-out;
            -o-transition: all 0.3s ease-out;
            transition: all 0.3s ease-out;
            opacity: 1;
        }
        #upload-file .drag-drop-inside{
            margin: 25px auto 0;
        }
        #upload-file .drag-drop-inside p{
            text-align: center;
            color: #a0a5aa;
        }
        #upload-file .drag-drop-inside p .btn{
            color: #333;
            background: #ddd;
        }
        #upload-file .drag-drop-inside p .btn:active{
            background: #d2d2d2;
            -webkit-transform: translateY(1px);
            -ms-transform: translateY(1px);
            transform: translateY(1px);
        }
    </style>
{/block}
{block name=body}
    <div class="main-content">
        <div class="main-content-inner">
            <!--PATH BEGINS-->
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li><i class="ace-icon fa fa-home home-icon"></i><a href="#">Home</a></li>
                    <li><a href="{site_url()}nhanmau">Nhận mẫu</a></li>
                    <li class="active">Thêm Phiếu yêu cầu thử nghiệm</li>
                </ul>
            </div>
            <!--PATH ENDS-->
            <div class="page-content">
                <div class="row">
                    <div class="col-xs-12">
                        <!-- PAGE CONTENT BEGINS -->
                        <h3 class="header smaller lighter blue"><i class="fa fa-file-text" aria-hidden="true"></i> Thêm PHIẾU YÊU CẦU THỬ NGHIỆM</h3>
                        <form id="form-hopdong" role="form" method="post">
                            <div id="form-error" class="alert alert-danger {if count($data['error']) == 0}hidden{/if}" style="margin: 0;">
                                <button type="button" class="close" data-dismiss="alert">
                                    <i class="ace-icon fa fa-times"></i>
                                </button>
                                <strong>
                                    <i class="ace-icon fa fa-exclamation-triangle red bigger-130"></i> Lỗi!
                                </strong>
                                &nbsp;Vui lòng kiểm tra lại thông tin trước khi tiếp tục. {if $data['error']['general']}({$data['error']['general']}){/if}
                                <br>
                            </div>
                            {if $data['hopdong_mau']}
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label" for="hopdong_code">Mã hợp đồng</label>
                                            <input class="form-control" type="text" id="hopdong_code" name="hopdong_code" placeholder="Nhập mã hợp đồng" value="{$data['hopdong']['hopdong_code']}">
                                        </div>
                                    </div>
                                </div>
                            {/if}
                            <div class="row">
                                <div class="col-md-6">
                                    <h4 class="header green"><i class="fa fa-briefcase orange" aria-hidden="true"></i> Thông tin khách hàng</h4>
                                    <div class="form-group {if $data['error']['congty_name']}has-error{/if}">
                                        <label class="control-label" for="congty_name">Tên khách hàng <span class="red">*</span></label>
                                        <input class="form-control" type="text" id="congty_name" name="congty_name" placeholder="Nhập tên khách hàng" value="{$data['hopdong']['congty']['congty_name']}">
                                        <input type="hidden" id="congty_id" name="congty_id" value="{$data['hopdong']['congty']['congty_id']}">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="congty_address">Địa chỉ</label>
                                        <input class="form-control" type="text" id="congty_address" name="congty_address" placeholder="Nhập địa chỉ khách hàng" value="{$data['hopdong']['congty']['congty_address']}">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label" for="congty_email">Email</label>
                                                <input class="form-control data-info" data-check-type="congty" data-type="email" type="text" id="congty_email" name="congty_email" placeholder="Nhập email khách hàng" value="{$data['hopdong']['congty']['congty_email']}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label" for="congty_phone">Số điện thoại</label>
                                                <input class="form-control data-info" data-check-type="congty" data-type="phone" type="text" id="congty_phone" name="congty_phone" placeholder="Nhập SĐT khách hàng" value="{$data['hopdong']['congty']['congty_phone']}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label" for="congty_fax">Fax</label>
                                                <input class="form-control" type="text" id="congty_fax" name="congty_fax" placeholder="Nhập fax khách hàng" value="{$data['hopdong']['congty']['congty_fax']}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label" for="congty_tax">Mã số thuế</label>
                                                <input class="form-control data-info" data-check-type="congty" data-type="tax" type="text" id="congty_tax" name="congty_tax" placeholder="Nhập MST khách hàng" value="{$data['hopdong']['congty']['congty_tax']}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h4 class="header green"><i class="fa fa-user orange" aria-hidden="true"></i> Người liên hệ</h4>
                                    <div class="form-group {if $data['error']['contact_fullname']}has-error{/if}">
                                        <label class="control-label" for="contact_fullname">Họ và tên <span class="red">*</span></label>
                                        <input class="form-control" type="text" id="contact_fullname" name="contact_fullname" placeholder="Nhập họ và tên người liên hệ" value="{$data['hopdong']['contact']['contact_fullname']}">
                                        <input type="hidden" id="contact_id" name="contact_id" value="{$data['hopdong']['contact']['contact_id']}">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="contact_birthday">Ngày sinh</label>
                                        <input class="form-control" type="text" id="contact_birthday" name="contact_birthday" placeholder="Nhập ngày sinh người liên hệ" value="{$data['hopdong']['contact']['contact_birthday']}" autocomplete="off">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label" for="contact_email">Email</label>
                                                <input class="form-control data-info" data-check-type="contact" data-type="email" type="text" id="contact_email" name="contact_email" placeholder="Nhập email người liên hệ" value="{$data['hopdong']['contact']['contact_email']}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label" for="contact_phone">Số điện thoại</label>
                                                <input class="form-control data-info" data-check-type="contact" data-type="phone" type="text" id="contact_phone" name="contact_phone" placeholder="Nhập SĐT người liên hệ" value="{$data['hopdong']['contact']['contact_phone']}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 list_mau">
                                    <h4 class="header green"><i class="fa fa-flask orange" aria-hidden="true"></i> Thông tin mẫu</h4>
                                    {if $data['error']['mau']}
                                        <p class="red">Có lỗi sảy ra ({$data['error']['mau']}), vui lòng nhập lại thông tin mẫu</p>
                                    {/if}
                                    <input type="hidden" class="mau_number" value="{max(1,count($data['hopdong']['list_mau']))}">
                                    {foreach from=$data['hopdong']['list_mau'] key=index_mau item=mau}
                                        <div class="widget-box mau_e" data-number="{($index_mau + 1)}">
                                            <div class="widget-toolbox clearfix" style="background: none; padding: 5px 12px;">
                                                <span class="lbl_mau_order">Mẫu số: <span class="mau_order">{($index_mau + 1)|string_format:"%03d"}</span></span>
                                                <button class="btn btn-xs btn-info pull-right mau_copy" type="button">
                                                    <i class="fa fa-clone bigger-120"></i>
                                                </button>
                                                <button class="btn btn-xs btn-danger pull-right mau_delete" type="button">
                                                    <i class="ace-icon fa fa-trash-o bigger-120"></i>
                                                </button>
                                            </div>
                                            <div class="widget-header widget-header-blue widget-header-flat" style="padding: 5px 12px;">
                                                <div class="row">
                                                    <div class="col-md-3 form-group">
                                                        <label class="control-label" for="">Tên mẫu</label>
                                                        <input class="form-control mau_name" type="text" placeholder="Nhập tên mẫu" name="mau[{($index_mau + 1)}][name]" value="{$mau['mau_name']}">
                                                        <input class="mau_id" type="hidden" name="mau[{($index_mau + 1)}][mau_id]" value="{$mau['mau_id']}">
                                                        <input class="mau_code" type="hidden" name="mau[{($index_mau + 1)}][mau_code]" value="{$mau['mau_code']}">
                                                        <input class="date_create" type="hidden" name="mau[{($index_mau + 1)}][date_create]" value="{$mau['date_create']}">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="row">
                                                            <div class="col-md-8 form-group">
                                                                <label class="control-label" for="">Khối lượng</label>
                                                                <div class="input-group" style="width: 100%;">
                                                                    <input class="form-control mau_mass" style="width: 60%;" type="text" placeholder="Nhập khối lượng mẫu" name="mau[{($index_mau + 1)}][mass]" value="{$mau['mau_mass']}">
                                                                    <select class="form-control mau_donvitinh" style="width: 40%;" name="mau[{($index_mau + 1)}][donvitinh]" data-name="dvt">
                                                                        <option value="">ĐVT</option>
                                                                        {foreach from=$data['list_dvt'] item=dvt}
                                                                            <option value="{$dvt['donvitinh_id']}" {if $mau['donvitinh_id'] == $dvt['donvitinh_id']}selected="selected"{/if}>{$dvt['donvitinh_name']}</option>
                                                                        {/foreach}
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 form-group">
                                                                <label class="control-label" for="">Số lượng</label>
                                                                <input class="form-control mau_amount" type="text" placeholder="Nhập số lượng mẫu" name="mau[{($index_mau + 1)}][amount]" value="{$mau['mau_amount']}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 form-group">
                                                        <label class="control-label" for="">Mô tả mẫu</label>
                                                        <input class="form-control mau_description" type="text" placeholder="Nhập mô tả mẫu" name="mau[{($index_mau + 1)}][description]" value="{$mau['mau_description']}">
                                                    </div>
                                                    <div class="col-md-3 form-group">
                                                        <label class="control-label" for="">Nền mẫu</label>
                                                        <select class="form-control chosen-select mau_nenmau" name="mau[{($index_mau + 1)}][nenmau]" data-name="nenmau" {if count($mau['list_chitieu'])>0}disabled="disabled" data-disabled="disabled"{/if}>
                                                            <option value="">Chọn nền mẫu</option>
                                                            {foreach from=$data['list_nenmau'] item=nenmau}
                                                                <option value="{$nenmau['nenmau_id']}" {if $mau['nenmau_id'] == $nenmau['nenmau_id']}selected="selected"{/if}>{$nenmau['nenmau_name']}</option>
                                                            {/foreach}
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="widget-body">
                                                <div class="widget-main mau_chitieu">
                                                    <div class="chitieu_cover" style="{if $mau['nenmau_id']}display: none;{/if}">
                                                        <span><i class="fa fa-lock" aria-hidden="true"></i> Chọn nền mẫu để tiếp tục</span>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="row">
                                                                <div class="col-md-6 form-horizontal">
                                                                    <div class="form-group">
                                                                        <div class="col-md-12">
                                                                            <div class="input-group">
                                                                                <span class="input-group-addon">
                                                                                    Nhập nhanh
                                                                                </span>
                                                                                <span class="block input-icon input-icon-right">
                                                                                    <input type="text" class="form-control package_code" placeholder="Nhập CODE">
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="col-md-4 control-label" for="">Chỉ tiêu</label>
                                                                        <div class="col-md-8">
                                                                            <select class="form-control chosen-select chitieu_e" data-name="chitieu" placeholder="test">
                                                                                <option value="">Chọn chỉ tiêu</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="col-md-4 control-label" for="">Phương pháp</label>
                                                                        <div class="col-md-8">
                                                                            <select class="form-control chosen-select chitieu_e" data-name="phuongphap">
                                                                                <option value="">Chọn phương pháp</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6 form-horizontal">
                                                                    <div class="form-group">
                                                                        <label class="col-md-4 control-label" for="">Kỹ thuật</label>
                                                                        <div class="col-md-8">
                                                                            <select class="form-control chosen-select chitieu_e" data-name="kythuat">
                                                                                <option value="">Chọn kỹ thuật</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="col-md-4 control-label" for="">Phòng TN</label>
                                                                        <div class="col-md-8">
                                                                            <select class="form-control chosen-select chitieu_e" data-name="ptn">
                                                                                <option value="">Chọn PTN</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group chat">
                                                                        <label class="col-md-4 control-label" for="">Chất PT</label>
                                                                        <div class="col-md-8">
                                                                            <div style="width: 100%; position: relative;">
                                                                                <select class="form-control multiple-select chitieu_e" data-name="chat" data-placeholder="Chọn chất" multiple="multiple"></select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 form-horizontal">
                                                            <div class="form-group">
                                                                <label class="col-md-4 control-label" for="">Ngày trả KQ</label>
                                                                <div class="col-md-8">
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control chitieu_dateend" data-name="chitieu_dateend">
                                                                        <span class="input-group-addon">
                                                                            <i class="fa fa-calendar bigger-110"></i>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-md-4 control-label" for="">LOD/LOQ</label>
                                                                <div class="col-md-8">
                                                                    <select class="form-control chosen-select lod_loq" name="mau[{($index_mau + 1)}][lod_loq]" data-name="lod_loq">
                                                                        <option value="1">LOD</option>
                                                                        <option value="2">LOQ</option>
                                                                        <option value="3">LOD & LOQ</option>
                                                                    </select>
                                                                        <!--
                                                                    <div class="row">
                                                                        
                                                                        <div class="col-md-3">
                                                                            <div class="radio">
                                                                                <label>
                                                                                    <input type="radio" class="ace lod_loq" name="mau[{($index_mau + 1)}][lod_loq]" data-name="lod_loq" value="1" checked="checked">
                                                                                    <span class="lbl"> LOD</span>
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <div class="radio">
                                                                                <label>
                                                                                    <input type="radio" class="ace lod_loq" name="mau[{($index_mau + 1)}][lod_loq]" data-name="lod_loq" value="2">
                                                                                    <span class="lbl"> LOQ</span>
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="radio">
                                                                                <label>
                                                                                    <input type="radio" class="ace lod_loq" name="mau[{($index_mau + 1)}][lod_loq]" data-name="lod_loq" value="3">
                                                                                    <span class="lbl"> LOD&LOQ</span>
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                        -->
                                                                </div>
                                                            </div>
                                                            <div class="form-group" style="margin-bottom: 0;">
                                                                <div class="col-md-12 text-right">
                                                                    <input type="hidden" class="chitieu_number" value="{count($mau['list_chitieu'])}">
                                                                    <input type="hidden" class="package_price" data-name="package_price" value="0">
                                                                    <input type="hidden" class="chatgia_list" data-name="chatgia_list" value="">
                                                                    <input class="date_save" type="hidden" value="">
                                                                    <button class="btn btn-xs btn-info pull-right chitieu_add" type="button"><i class="fa fa-plus" aria-hidden="true"></i> Thêm</button>
                                                                    <button class="btn btn-xs pull-right chitieu_reset" style="margin-right: 1px;" type="button"><i class="fa fa-refresh" aria-hidden="true"></i> Reset</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div><!-- /.widget-main -->
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <table class="table table-striped table-bordered table-hover list_chitieu">
                                                            <thead>
                                                                <tr>
                                                                    <th>Nhóm chỉ tiêu</th>
                                                                    <th>Phương pháp</th>
                                                                    <th>Kỹ thuật</th>
                                                                    <th>Phòng TN</th>
                                                                    <th>Chỉ tiêu</th>
                                                                    <th>LOD/LOQ</th>
                                                                    <th>Ngày trả KQ</th>
                                                                    <th>Dịch vụ</th>
                                                                    <th>Giá đề nghị</th>
                                                                    <th>Giá</th>
                                                                    <!--<th>Đã có KQ</th>-->
                                                                    <th></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                {if $mau['list_chitieu']}
                                                                    {foreach from=$mau['list_chitieu'] key=index_chitieu item=chitieu}
                                                                        <tr class="chitieu">
                                                                            <td>{$chitieu['chitieu_name']}
                                                                                <input type="hidden" value="{$chitieu['chitieu_id']}" name="mau[{($index_mau + 1)}][chitieu][{($index_chitieu + 1)}][chitieu]">
                                                                                <input type="hidden" value="{$chitieu['mauchitiet_id']}" name="mau[{($index_mau + 1)}][chitieu][{($index_chitieu + 1)}][mauchitiet_id]">
                                                                            </td>
                                                                            <td>{$chitieu['phuongphap_name']}
                                                                                <input type="hidden" value="{$chitieu['phuongphap_id']}" name="mau[{($index_mau + 1)}][chitieu][{($index_chitieu + 1)}][phuongphap]">
                                                                            </td>
                                                                            <td>{$chitieu['kythuat_name']}
                                                                                <input type="hidden" value="{$chitieu['kythuat_id']}" name="mau[{($index_mau + 1)}][chitieu][{($index_chitieu + 1)}][kythuat]">
                                                                            </td>
                                                                            <td>{$chitieu['ptn_name']}
                                                                                <input type="hidden" value="{$chitieu['donvi_id']}" name="mau[{($index_mau + 1)}][chitieu][{($index_chitieu + 1)}][ptn]">
                                                                            </td>
                                                                            <td>
                                                                                <ul>
                                                                                {foreach from=$chitieu['list_chat'] item=chat}
                                                                                    <li class="chat-txt {$chat['class']}">
                                                                                        {$chat['chat_name']} {if $chat['info_lod_loq']}<span class="info_lod_loq">{$chat['info_lod_loq']}</span>{/if}
                                                                                    </li>
                                                                                {/foreach}
                                                                                </ul>
                                                                                <input type="hidden" value="{$chitieu['list_chat_id']}" name="mau[{($index_mau + 1)}][chitieu][{($index_chitieu + 1)}][chat]">
                                                                            </td>
                                                                            <td>{$chitieu['lod_loq_txt']}
                                                                                <input type="hidden" value="{$chitieu['lod_loq']}" name="mau[{($index_mau + 1)}][chitieu][{($index_chitieu + 1)}][lod_loq]">
                                                                            </td>
                                                                            <td>{$chitieu['chitieu_dateend']|date_format:"%d/%m/%Y"}
                                                                                <input type="hidden" value="{$chitieu['chitieu_dateend']|date_format:"%d/%m/%Y"}" name="mau[{($index_mau + 1)}][chitieu][{($index_chitieu + 1)}][chitieu_dateend]" class="val_chitieu_dateend">
                                                                            </td>
                                                                            <td>
                                                                                <select class="form-control dichvu" name="mau[{($index_mau + 1)}][chitieu][{($index_chitieu + 1)}][dichvu]" data-name="chitieu_dichvu">
                                                                                {foreach from=$data['list_dichvu'] item=dichvu}
                                                                                    <option value="{$dichvu['dichvu_id']}" {if $dichvu['dichvu_id'] == $chitieu['dichvu_id']}selected="selected"{/if} data-price="{$dichvu['dichvu_price']}">
                                                                                        {$dichvu['dichvu_name']}
                                                                                    </option>
                                                                                {/foreach}
                                                                                </select>
                                                                            </td>
                                                                            <td>
                                                                                {assign var="dichvu_price" value=$data['list_dichvu'][array_search($chitieu['dichvu_id'], array_column($data['list_dichvu'], 'dichvu_id'))]['dichvu_price']}
                                                                                <span class="lbl_price_default">{$chitieu['price_tmp']}</span>
                                                                                <input class="price_default_root" type="hidden" value="{($chitieu['price_tmp'] - $dichvu_price)}">
                                                                                <input class="price_default" type="hidden" value="{$chitieu['price_tmp']}" name="mau[{($index_mau + 1)}][chitieu][{($index_chitieu + 1)}][price_default]">
                                                                            </td>
                                                                            <td><input type="text" class="price" value="{$chitieu['price']}" name="mau[{($index_mau + 1)}][chitieu][{($index_chitieu + 1)}][price]"></td>
                                                                            <!--<td>{$chitieu['list_ketqua']}</td>-->
                                                                            <td>
                                                                                <a class="chitieu_delete" href="#">
                                                                                    <input class="date_save_chitieu" type="hidden" value="{$chitieu['time_save']}">
                                                                                    <i class="ace-icon fa fa-trash-o bigger-120 red"></i>
                                                                                </a>
                                                                            </td>
                                                                        </tr>
                                                                    {/foreach}
                                                                {/if}
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div><!-- /.widget-body -->
                                            <div class="widget-toolbox clearfix other-info">
                                                <div id="accordion-mau-{($index_mau + 1)}" class="accordion-style1 panel-group">
                                                    <div class="panel panel-default">
                                                        <div class="panel-heading">
                                                            <h4 class="panel-title">
                                                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion-mau-{($index_mau + 1)}" href="#collapse-mau-{($index_mau + 1)}" aria-expanded="false">
                                                                    <i class="ace-icon fa fa-angle-right bigger-110" data-icon-hide="ace-icon fa fa-angle-down" data-icon-show="ace-icon fa fa-angle-right"></i> &nbsp; Thông tin khác
                                                                </a>
                                                            </h4>
                                                        </div>
                                                        <div class="panel-collapse collapse" id="collapse-mau-{($index_mau + 1)}" aria-expanded="false">
                                                            <div class="panel-body">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group" style="margin-bottom: 12px;">
                                                                            <label class="control-label bolder">Lưu mẫu</label>
                                                                            <div class="row">
                                                                                <div class="col-md-6">
                                                                                    <div class="radio">
                                                                                        <label>
                                                                                            <input name="mau[{($index_mau + 1)}][mau_save]" type="radio" class="ace mau_save" value="0" {if $mau['mau_save'] == '0'}checked="checked"{/if}>
                                                                                            <span class="lbl"> Không lưu mẫu</span>
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <div class="radio">
                                                                                        <label>
                                                                                            <input name="mau[{($index_mau + 1)}][mau_save]" type="radio" class="ace mau_save" value="1" {if !$mau['mau_save'] || $mau['mau_save'] == '1'}checked="checked"{/if}>
                                                                                            <span class="lbl"> Có lưu mẫu</span>
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <div class="row">
                                                                                <div class="col-md-6">
                                                                                    <label class="control-label bolder">Ngày lưu mặc định</label>
                                                                                    <input type="text" class="form-control mau_datesave" name="mau[{($index_mau + 1)}][mau_datesave]" value="{$mau['mau_datesave']|date_format:"%d/%m/%Y"}" readonly="readonly">
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <label class="control-label bolder">Ngày lưu khách hàng yêu cầu</label>
                                                                                    <input type="text" class="form-control mau_datesave_yeucau mau-datepicker" name="mau[{($index_mau + 1)}][mau_datesave_yeucau]" value="{$mau['mau_datesave_yeucau']|date_format:"%d/%m/%Y"}" {if $mau['mau_save'] == '0'}readonly="readonly"{/if} autocomplete="off">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="control-label bolder">Ghi chú</label>
                                                                            <textarea class="form-control" rows="4" name="mau[{($index_mau + 1)}][mau_note]">{$mau['mau_note']}</textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    {/foreach}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="header green"><i class="fa fa-file-text orange" aria-hidden="true"></i> Hình ảnh mẫu</h4>
                                    <div id="upload-file">
                                        <div class="drag-drop-inside">
                                            <p>
                                                <label for="fileupload">
                                                    <span class="btn" type="button">Chọn File</span>
                                                </label>
                                                <input id="fileupload" type="file" name="hopdong_files" data-url="{site_url()}nhanmau/upload" multiple style="display: none;">
                                                hoặc 
                                                <strong>Kéo thả file vào đây</strong>
                                            </p>
                                        </div>
                                    </div>
                                    <div id="hopdong-list-file">
                                        {if $data['hopdong']['list_file']}
                                            {foreach from=$data['hopdong']['list_file'] item=file}
                                                <div class="file-element file-{$file['file_id']}">
                                                    <div class="row">
                                                        <div class="col-xs-4 file-view">
                                                            {if strpos($file['file_type'], 'image/') !== FALSE}
                                                                <img src="{$file['file_url']}">
                                                            {else}
                                                                <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                                            {/if}
                                                        </div>
                                                        <div class="col-xs-6 file-name">{$file['file_name']}</div>
                                                        <div class="col-xs-2 file-processing" aria-valuenow="100">
                                                            <i class="fa fa-times hopdong-file-delete" data-file-id="{$file['file_id']}" data-file-exts="{$file['file_exts']}"></i>
                                                        </div>
                                                        <input type="hidden" class="profile-file" name="hopdong_file[]" value="{$file['file_id']}">
                                                    </div>
                                                </div>
                                            {/foreach}
                                        {/if}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="header green"><i class="fa fa-file-text orange" aria-hidden="true"></i> Thông tin trả kết quả</h4>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label bolder">Ngôn ngữ</label>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="radio">
                                                            <label>
                                                                <input name="hopdong_resultlang" type="radio" class="ace" value="0" {if !$data['hopdong']['hopdong_resultlang'] || $data['hopdong']['hopdong_resultlang'] == 0 }checked="checked"{/if}>
                                                                <span class="lbl"> Tiếng Việt</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="radio">
                                                            <label>
                                                                <input name="hopdong_resultlang" type="radio" class="ace" value="1" {if $data['hopdong']['hopdong_resultlang'] == 1 }checked="checked"{/if}>
                                                                <span class="lbl"> Tiếng Anh</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="radio">
                                                            <label>
                                                                <input name="hopdong_resultlang" type="radio" class="ace" value="2" {if $data['hopdong']['hopdong_resultlang'] == 2 }checked="checked"{/if}>
                                                                <span class="lbl"> Anh-Việt</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label bolder" style="width: 100%;">Cách thức nhận kết quả</label>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="radio">
                                                            <label>
                                                                <input name="hopdong_resultvia" type="radio" class="ace" value="0" {if !$data['hopdong']['hopdong_resultvia'] || $data['hopdong']['hopdong_resultvia'] == 0 }checked="checked"{/if}>
                                                                <span class="lbl"> Trực tiếp</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="radio">
                                                            <label>
                                                                <input name="hopdong_resultvia" type="radio" class="ace" value="1" {if $data['hopdong']['hopdong_resultvia'] == 1 }checked="checked"{/if}>
                                                                <span class="lbl"> Thư</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="radio">
                                                            <label>
                                                                <input name="hopdong_resultvia" type="radio" class="ace" value="2" {if $data['hopdong']['hopdong_resultvia'] == 2 }checked="checked"{/if}>
                                                                <span class="lbl"> Email</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label bolder" for="">Thị trường áp dụng</label>
                                                <select class="form-control" name="hopdong_thitruong">
                                                    <option value="">Chọn thị trường</option>
                                                    {foreach from=$data['list_thitruong'] item=thitruong}
                                                        <option value="{$thitruong['thitruong_id']}" {if $data['hopdong']['thitruong_id'] == $thitruong['thitruong_id'] }selected="selected"{/if}>{$thitruong['thitruong_name']}</option>
                                                    {/foreach}
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label bolder" for="">Quy chuẩn so sánh</label>
                                                <textarea class="form-control" name="hopdong_quychuan">{$data['hopdong']['hopdong_quychuan']}</textarea>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label bolder" for="">Yêu cầu khác</label>
                                                <textarea class="form-control" name="hopdong_yeucaukhac">{$data['hopdong']['hopdong_yeucaukhac']}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label bolder" for="">Phí thử nghiệm</label>
                                                <input type="text" class="form-control total-price" name="total_price" value="{$data['hopdong']['hopdong_price']}" readonly="readonly">
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label bolder" for="">Tạm ứng</label>
                                                <input type="text" class="form-control hopdong_deposit" name="hopdong_deposit" value="{$data['hopdong']['hopdong_deposit']}">
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label bolder" for="">Còn lại</label>
                                                <input type="text" class="form-control hopdong_remaining" name="hopdong_remaining" value="{$data['hopdong']['hopdong_remaining']}" readonly="readonly">
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label bolder" for="">Ngày trả kết quả</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control hopdong_dateend" name="hopdong_dateend" value="{$data['hopdong']['hopdong_dateend']|date_format:"%d/%m/%Y"}" autocomplete="off">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-calendar bigger-110"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix form-actions">
                                {if $data['hopdong_mau']}
                                    <div class="col-md-offset-3 col-md-9">
                                        <a href="{site_url()}nhanmau" class="btn">
                                            <i class="ace-icon fa fa-times bigger-110"></i> Hủy
                                        </a>
                                        <button class="btn btn-info" type="submit" value="save" name="submit">
                                            <i class="ace-icon fa fa-check bigger-110"></i> Lưu
                                        </button>
                                    </div>
                                {else}
                                    <div class="col-md-offset-3 col-md-9">
                                        <a href="{site_url()}nhanmau" class="btn">
                                            <i class="ace-icon fa fa-times bigger-110"></i> Hủy
                                        </a>
                                        <!--
                                        <button class="btn btn-info" type="submit" value="save" name="submit">
                                                <i class="ace-icon fa fa-check bigger-110"></i> Lưu nháp
                                        </button>
                                        -->
                                        <button class="btn btn-primary" type="submit" value="send" name="submit">
                                                <i class="ace-icon fa fa-share-square-o bigger-110"></i> Gửi mẫu
                                        </button>
                                    </div>
                                {/if}
                            </div>
                        </form>
                        <!-- PAGE CONTENT ENDS -->
                    </div>
                </div>
            </div>
        </div>
    </div>
{/block}
{block name=script}
    <script src="{$assets_path}js/jquery-ui.min.js"></script>
    <script src="{$assets_path}js/jquery.ui.touch-punch.min.js"></script>
    <script src="{$assets_path}js/chosen.jquery.min.js"></script>
    <script src="{$assets_path}plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="{$assets_path}plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script src="{$assets_path}js/bootstrap-datepicker.min.js"></script>
    <script src="{$assets_path}js/bootbox.js"></script>
    <script src="{$assets_path}js/jquery.gritter.min.js"></script>
    <!-- Jquery table -->
    <script src="{base_url()}assets/tuanlm/dataTables.treeGrid.js"></script>
    <script src="{base_url()}assets/tuanlm/jquery.multiselect.js"></script>
    <!--    Import Jquery Upload    -->
    <script src="{base_url()}assets/tuanlm/jquery-file-upload/js/vendor/jquery.ui.widget.js"></script>
    <script src="{base_url()}assets/tuanlm/jquery-file-upload/js/jquery.iframe-transport.js"></script>
    <script src="{base_url()}assets/tuanlm/jquery-file-upload/js/jquery.fileupload.js"></script>
    <script type="text/javascript">
        //var url_api = 'http://dev.tamducjsc.info/khachhang/api/';
        //=== DEFINE DATA THITRUONG
        var data_thitruong = {};
        {if $data['hopdong']['list_thitruong']}
        {foreach from=$data['hopdong']['list_thitruong'] key=chat_id item=thitruong_chat}
            var thitruong = {};
            {foreach from=$thitruong_chat item=thitruong}
                {if $thitruong}
                    {if $thitruong['thitruong_id'] == '0'}
                        thitruong[{$thitruong['thitruong_id']}] = {
                            'data_type': {if $thitruong['capacity']}{$thitruong['capacity']}{else}null{/if},
                            'data_val_min': {if $thitruong['val_min']}{$thitruong['val_min']}{else}null{/if},
                            'data_val_max': {if $thitruong['val_max']}{$thitruong['val_max']}{else}null{/if},
                        };
                    {else}
                        thitruong[{$thitruong['thitruong_id']}] = {
                            "data_mrl_min": {if $thitruong['mrl_min']}{$thitruong['mrl_min']}{else}null{/if},
                            "data_mrl_max": {if $thitruong['mrl_max']}{$thitruong['mrl_max']}{else}null{/if}
                        };
                    {/if}
                {/if}
            {/foreach}
            data_thitruong[{$chat_id}] = thitruong;
        {/foreach}
        {/if}
            
        //=== DEMO GIALE
        var  price_item  = {
            'rank': false,
            'other': false
        };
        /*
        var  price_item  = {
            //'rank': [500000,1000000,1500000],
            'rank': [300000],
            'other': 300000
        };*/

        //=== CONFIG FOR SELECT
        var chosen_config = {
            allow_single_deselect: true,
            search_contains: true,
            width: '100%',
            placeholder_text_single: 'Loading...'
        };
        var multiple_select_config = {
            search : true,
            texts: {
                placeholder    : 'Chọn chất phân tích', // text to use in dummy input
                search         : 'Tìm chất',         // search input placeholder text
                selectedOptions: ' chất đã chọn',      // selected suffix text
                selectAll      : 'Chọn tất cả',     // select all text
                unselectAll    : 'Bỏ chọn tất cả',   // unselect all text
                noneSelected   : 'Không chọn chất nào'   // None selected text
            },
            selectAll : true
        };

        //=== CONFIG FOR DATEPICKER
        var datepicker_config = {
            format: 'dd/mm/yyyy' ,
            autoclose: true
        };
        
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

        //=== CALCULATOR TOTAL PRICE
        function calculatorTotalPrice(){
            var totalPrice = 0;
            if($(document).find('.price').length > 0){
                $(document).find('.price').each(function(){
                    totalPrice += parseInt($(this).val());
                });
            }
            $('.total-price').val(totalPrice);
            $('.hopdong_remaining').val(totalPrice - $('.hopdong_deposit').val());
        };

        //=== CALCULATOR DATE END OF HOPDONG
        function calculatorDateEnd(){
            var maxDateEnd = '';
            if($(document).find('.val_chitieu_dateend').length > 0){
                $(document).find('.val_chitieu_dateend').each(function(){
                    if(!maxDateEnd || $.datepicker.parseDate("dd/mm/yy", maxDateEnd) <= $.datepicker.parseDate("dd/mm/yy", $(this).val())){
                        maxDateEnd = $.datepicker.parseDate("dd/mm/yy", $(this).val());
                        maxDateEnd.setDate(maxDateEnd.getDate() + 1);
                        maxDateEnd = $.datepicker.formatDate("dd/mm/yy", maxDateEnd);
                    }
                });
            }
            $('.hopdong_dateend').val(maxDateEnd);
        };

        //=== CALCULATOR MAX DATE SAVE MAU
        function calculatorMaxDateSave(mau_e){
            var maxDateSave = '';
            if(mau_e.find('.date_save_chitieu').length > 0){
                mau_e.find('.date_save_chitieu').each(function(){
                    if(!maxDateSave || $.datepicker.parseDate("dd/mm/yy", maxDateSave) <= $.datepicker.parseDate("dd/mm/yy", $(this).val())){
                        maxDateSave = $(this).val();
                    }
                });
            }
            mau_e.find('.mau_datesave').val(maxDateSave);
            mau_e.find('.mau_datesave_yeucau').val(maxDateSave);
        }
        
        //=== CHANGE THITRUONG
        function changeThiTruong(thitruong_select){
            var thitruong_original = 0;
            console.log(data_thitruong);
            if(thitruong_select){
                $.each(data_thitruong, function(index, value){
                    if(typeof value[thitruong_select] !== 'undefined' && typeof value[thitruong_original] !== 'undefined'){
                        var error = false;
                        if( value[thitruong_original]['data_type'] == '2'){
                            // Process MIN, MAX
                            if(value[thitruong_original]['data_val_min'] && value[thitruong_select]['data_mrl_min'] 
                                    && value[thitruong_original]['data_val_min'] > value[thitruong_select]['data_mrl_min']){
                                error = true;
                            }
                            if(value[thitruong_original]['data_val_max'] && value[thitruong_select]['data_mrl_max'] 
                                    && value[thitruong_original]['data_val_max'] < value[thitruong_select]['data_mrl_max']){
                                error = true;
                            }
                            if(error){
                                value[thitruong_original]['data_val_min'] = value[thitruong_original]['data_val_min']?value[thitruong_original]['data_val_min']:'';
                                value[thitruong_original]['data_val_max'] = value[thitruong_original]['data_val_max']?value[thitruong_original]['data_val_max']:'';
                                value[thitruong_select]['data_mrl_min'] = value[thitruong_select]['data_mrl_min']?value[thitruong_select]['data_mrl_min']:'';
                                value[thitruong_select]['data_mrl_max'] = value[thitruong_select]['data_mrl_max']?value[thitruong_select]['data_mrl_max']:'';
                                $('.chat-'+index+':not(.has-compare)').addClass('red has-compare').append(
                                    $('<span>',{ class: 'info_lod_loq' }).append(' [('+value[thitruong_original]['data_val_min']+'-'+value[thitruong_original]['data_val_max']+'),('+value[thitruong_select]['data_mrl_min']+'-'+value[thitruong_select]['data_mrl_max']+')]')
                                );
                            }
                        }else{
                            // Process LOD,LOQ
                            if(value[thitruong_original]['data_val_max'] && value[thitruong_select]['data_mrl_min'] 
                                    && value[thitruong_original]['data_val_max'] > value[thitruong_select]['data_mrl_min']){
                                error = true;
                            }
                            if(value[thitruong_original]['data_val_max'] && value[thitruong_select]['data_mrl_max'] 
                                    && value[thitruong_original]['data_val_max'] > value[thitruong_select]['data_mrl_max']){
                                error = true;
                            }
                            if(error){
                                value[thitruong_original]['data_val_min'] = value[thitruong_original]['data_val_min']?value[thitruong_original]['data_val_min']:'';
                                value[thitruong_original]['data_val_max'] = value[thitruong_original]['data_val_max']?value[thitruong_original]['data_val_max']:'';
                                value[thitruong_select]['data_mrl_min'] = value[thitruong_select]['data_mrl_min']?value[thitruong_select]['data_mrl_min']:'';
                                value[thitruong_select]['data_mrl_max'] = value[thitruong_select]['data_mrl_max']?value[thitruong_select]['data_mrl_max']:'';
                                $('.chat-'+index+':not(.has-compare)').addClass('red has-compare').append(
                                    $('<span>',{ class: 'info_lod_loq' }).append(' [('+value[thitruong_original]['data_val_min']+','+value[thitruong_original]['data_val_max']+'),('+value[thitruong_select]['data_mrl_min']+','+value[thitruong_select]['data_mrl_max']+')]')
                                );
                            }
                        }
                    }
                });
            }
        }
        
        //=== CHECK INFO CONGTY, CONTACT
        function checkInfo(congty_contact, type, value, id_current = 0, callback = false){
            $.ajax({
                type: "POST",
                url: "{site_url()}nhanmau/ajax_call_api",
                dataType: "json",
                async: false,
                data: {
                    congty_contact: congty_contact,
                    type: type,
                    value: value,
                    id_current: id_current
                },
                success: function (result) {
                    if(callback){
                        callback(result);
                    }
                },
                error: function () {
                    alert('Lỗi!');
                }
            });
        }
        
        //=== ALL PROCESS
        $(document).ready(function () {
            
            //=== WHEN EDIT HOPDONG
            {if $data['hopdong']['thitruong_id']}
                changeThiTruong({$data['hopdong']['thitruong_id']});
            {/if}
            
            //=== LOAD PACKAGE
            function loadPackage(element, package, callback = false){
                var mau_e = element.parents('.mau_e');
                $.ajax({
                    type: "POST",
                    url: "{site_url()}nhanmau/ajax_load_package",
                    dataType: "json",
                    data: {
                        congty_id: $('#congty_id').val(),
                        package: package
                    },
                    success: function (result) {
                        if(result && result.code === 1){
                            $.each(result.package, function(key, data){
                                if(key === 'info'){
                                    if(data){
                                        mau_e.find('[data-name=chitieu_dateend]').val(data.thoigian);
                                        mau_e.find('[data-name=package_price]').val(data.price);
                                        mau_e.find('[data-name=chatgia_list]').val(data.chatgia_list);
                                    }
                                }else if(key === 'time_save'){
                                    mau_e.find('.date_save').val(data);
                                }else if(data !== null && mau_e.find('[data-name='+key+']').html() === ''){
                                    // Remove thitruong
                                    mau_e.find('.thitruong_chitieu').remove();
                                    // Add option
                                    $.each(data, function( index, value ) {
                                        if(key === 'chat'){
                                            mau_e.find('[data-name='+key+']').append($('<option>', { value: value.id, selected: 'selected' }).html(value.name));
                                            if(value.list_thitruong){
                                                var thitruong = {};
                                                // LOD, LOQ from chitieu_chat
                                                thitruong[0] = {
                                                    "data_type": value.capacity,
                                                    "data_val_min": value.val_min,
                                                    "data_val_max": value.val_max
                                                };
                                                // LOD, LOQ from thitruong_chat
                                                $.each(value.list_thitruong, function(key, value){
                                                    thitruong[value.thitruong_id] = {
                                                        "data_mrl_min": value.mrl_min,
                                                        "data_mrl_max": value.mrl_max,
                                                    };
                                                });
                                                data_thitruong[value.id] = thitruong;
                                            }
                                        }else{
                                            mau_e.find('[data-name='+key+']').append($('<option>', { value: value.id }).html(value.name));
                                        }
                                    });
                                    mau_e.find('[data-name='+key+']').trigger("chosen:updated").multiselect('reload');
                                }
                            });
                            if(callback){
                                callback();
                            }
                        }else{
                            alert('Lỗi!');
                        }
                    },
                    error: function () {
                        alert('Lỗi!');
                    }
                });
            }
            
            //=== SELECT WITH PLUGIN
            $('.chosen-select').chosen(chosen_config);
            $('.multiple-select').multiselect(multiple_select_config);
            
            //=== ENABLE SELECT WHEN EDIT HOPDONG
            $('.chosen-select').removeAttr('disabled');
            
            //=== AUTOCOMPLETE CONTACT
            $('#contact_fullname').on('focus', function(){
                $(this).catcomplete("search");
            });
            $('#contact_fullname').on('keydown.autocomplete', function () {
                $('#contact_id').val('');
                $('#contact_email').val('');
                $('#contact_phone').val('');
            });
            $('#contact_fullname').catcomplete({
                delay: 0,
                source: function (request, response) {
                    $.ajax({
                        type: "POST",
                        url: "{site_url()}nhanmau/ajax_search_contact",
                        dataType: "json",
                        data: {
                            congty_id: $('#congty_id').val(),
                            contact_name: request.term
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
                    $('#contact_id').val(ui.item.info.contact_id);
                    $('#contact_birthday').val(ui.item.info.contact_birthday);
                    $('#contact_email').val(ui.item.info.contact_email);
                    $('#contact_phone').val(ui.item.info.contact_phone);
                }
            });
            
            //=== AUTOCOMPLETE CONGTY
            $('#congty_name').on('focus', function(){
                $(this).catcomplete("search");
            });
            $('#congty_name').on('keydown.autocomplete', function () {
                $('#congty_id').val('');
                $('#congty_email').val('');
                $('#congty_phone').val('');
                $('#congty_address').val('');
                $('#congty_fax').val('');
                $('#congty_tax').val('');
            });
            $('#congty_name').catcomplete({
                delay: 0,
                source: function (request, response) {
                    $.ajax({
                        type: "POST",
                        url: "{site_url()}nhanmau/ajax_search_congty",
                        dataType: "json",
                        data: {
                            congty_name: request.term
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
                    $('#congty_id').val(ui.item.info.congty_id);
                    $('#congty_email').val(ui.item.info.congty_email);
                    $('#congty_phone').val(ui.item.info.congty_phone);
                    $('#congty_address').val(ui.item.info.congty_address);
                    $('#congty_fax').val(ui.item.info.congty_fax);
                    $('#congty_tax').val(ui.item.info.congty_tax);
                }
            });
            
            //=== AUTOCOMPLETE PACKAGE CODE
            $('.list_mau').on('keydown.autocomplete', '.package_code', function () {
                // Get nenmau
                var nenmau_id = $(this).parents('.mau_e').find('.mau_nenmau').val();
                if(!nenmau_id){
                    $.gritter.add({
                        title: 'Vui lòng chọn [nền mẫu] trước khi tiếp tục',
                        time: 3000,
                        class_name: 'gritter-error'
                    });
                    return false;
                }
                // Get data package
                $(this).catcomplete({
                    delay: 0,
                    source: function (request, response) {
                        $.ajax({
                            type: "POST",
                            url: "{site_url()}nhanmau/ajax_search_package",
                            dataType: "json",
                            data: {
                                nenmau_id: nenmau_id,
                                package_code: request.term
                            },
                            success: function (data) {
                                response(data);
                            },
                            error: function () {
                            }
                        });
                    },
                    select: function (event, ui) {
                        var element = $(this);
                        element.parents('.mau_e').find('.chitieu_e').html('').trigger("chosen:updated").multiselect('reload');
                        // Load package data
                        loadPackage(element, ui.item.package, function(){
                            // Remove error
                            element.parents('.mau_e').find('.has-error').removeClass('has-error');
                            // Add data by package_code
                            element.parents('.mau_e').find('[data-name=chitieu]').val('').val(ui.item.package.chitieu_id).trigger("chosen:updated");
                            element.parents('.mau_e').find('[data-name=phuongphap]').val('').val(ui.item.package.phuongphap_id).trigger("chosen:updated");
                            element.parents('.mau_e').find('[data-name=kythuat]').val('').val(ui.item.package.kythuat_id).trigger("chosen:updated");
                            element.parents('.mau_e').find('[data-name=ptn]').val('').val(ui.item.package.donvi_id).trigger("chosen:updated");
                        });
                    }
                });
            });
            
            //=== SELECT NENMAU OR ELEMENT IN CHITIEU
            $('.list_mau').on('change', '.mau_nenmau, .chitieu_e', function () {
                // Change date chitieu
                if($(this).attr('data-name') === 'chitieu_dateend'){
                    return false;
                }
                // Remove error
                $(this).parents('.has-error').removeClass('has-error');
                // Check element select
                var select_e = $(this).attr('data-name');
                if($(this).hasClass('mau_nenmau')){
                    select_e = 'nenmau';
                    if($(this).parents('.mau_e').find('.mau_nenmau').val()){
                        $(this).parents('.mau_e').find('.chitieu_cover').slideUp('fast');
                    }else{
                        $(this).parents('.mau_e').find('.chitieu_cover').slideDown('fast');
                    }
                }
                // Reset all input
                switch(select_e){
                    case 'nenmau':
                        $(this).parents('.mau_e').find('[data-name=chitieu]').val('').html('').trigger("chosen:updated");
                    case 'chitieu':
                        $(this).parents('.mau_e').find('[data-name=phuongphap]').val('').html('').trigger("chosen:updated");
                    case 'phuongphap':
                        $(this).parents('.mau_e').find('[data-name=kythuat]').val('').html('').trigger("chosen:updated");
                    case 'kythuat':
                        $(this).parents('.mau_e').find('[data-name=ptn]').val('').html('').trigger("chosen:updated");
                    case 'ptn':
                        $(this).parents('.mau_e').find('[data-name=chat]').val('').html('').multiselect('reload');
                    default:
                        $(this).parents('.mau_e').find('[data-name=chitieu_dateend]').val('').html('');
                }
                // Load package
                var package = {
                    nenmau_id: $(this).parents('.mau_e').find('.mau_nenmau').val(),
                    chitieu_id: $(this).parents('.mau_e').find('[data-name=chitieu]').val(),
                    phuongphap_id: $(this).parents('.mau_e').find('[data-name=phuongphap]').val(),
                    kythuat_id: $(this).parents('.mau_e').find('[data-name=kythuat]').val(),
                    donvi_id: $(this).parents('.mau_e').find('[data-name=ptn]').val()
                };
                loadPackage($(this), package);
            });
            
            //=== WHEN NENMAU SELECTED
            $('.mau_e').each(function(){
                var element = $(this).find('.mau_nenmau');
                var nenmau_selected = element.val();
                if(typeof nenmau_selected !== 'undefined' && nenmau_selected !== ''){
                    element.trigger('change');
                }
            });
            
            //=== RESET CHITIEU
            $('.list_mau').on('click', '.chitieu_reset', function () {
                // Remove error
                $(this).parents('.mau_e').find('.has-error').removeClass('has-error');
                // Reset input
                $(this).parents('.mau_e').find('.package_code').val('');
                $(this).parents('.mau_e').find('.chitieu_e').html('').trigger("chosen:updated").multiselect('reload');
                // Reset chitieu_dateend
                $(this).parents('.mau_e').find('.chitieu_dateend').val('');
                // Reset lod/loq
                $(this).parents('.mau_e').find('.lod_loq').val('1').trigger("chosen:updated");
                // Load package
                var package = { nenmau_id: $(this).parents('.mau_e').find('.mau_nenmau').val(), chitieu_id: '', phuongphap_id: '', kythuat_id: '', donvi_id: '' };
                loadPackage($(this), package);
                return false;
            });
            
            //=== ADD CHITIEU
            $('.list_mau').on('click', '.chitieu_add', function () {
                var error = false;
                var mau_number_current = $(this).parents('.mau_e').attr('data-number');
                var chitieu_number = parseInt($(this).parents('.mau_e').find('.chitieu_number').val());
                var chitieu = $('<tr>', { class: 'chitieu' });
                // Get chitieu group [process select]
                $(this).parents('.mau_e').find('.chitieu_e').each(function () {
                    // Remove error
                    $(this).parents('.form-group').removeClass('has-error');
                    // Check select value
                    if (!$(this).val()) {
                        error = true;
                        $(this).parents('.form-group').addClass('has-error');
                    } else {
                        // Add input hidden to list chitieu
                        var input_e = $('<input>', {
                            type: 'hidden',
                            value: $(this).val(),
                            name: 'mau[' + mau_number_current + '][chitieu][' + (chitieu_number + 1) + '][' + $(this).attr('data-name') + ']'
                        });
                        // Add text to list chitieu
                        if($(this).attr('data-name') === 'chat'){
                            //var input_lod_loq_val = $(this).parents('.mau_e').find('[data-name=lod_loq]:checked').val();
                            var list_input_txt = $('<ul>');
                            $("option:selected", this).each(function(){
                                $('<li>', { class: 'chat-txt chat-'+$(this).val() }).html($(this).text()).appendTo(list_input_txt);
                            });
                            $('<td>').append(list_input_txt).append(input_e).appendTo(chitieu);
                        }else{
                            var input_txt = [];
                            $("option:selected", this).each(function(){
                                input_txt.push($(this).text());
                            });
                            $('<td>').append(input_txt.join(' | ')).append(input_e).appendTo(chitieu);
                        }
                    }
                });
                // Add LOD/LOQ
                var input_lod_loq_val = $(this).parents('.mau_e').find('.lod_loq').val();
                if(input_lod_loq_val){
                    $(this).parents('.mau_e').find('[data-name=lod_loq]').parents('.form-group').removeClass('has-error');
                    var input_lod_loq = $('<input>', {
                        type: 'hidden',
                        class: 'val_lod_loq',
                        value: input_lod_loq_val,
                        name: 'mau[' + mau_number_current + '][chitieu][' + (chitieu_number + 1) + '][lod_loq]'
                    });
                    $('<td>').append($(this).parents('.mau_e').find('.lod_loq option:selected').text()).append(input_lod_loq).appendTo(chitieu);
                }else{
                    error = true;
                    $(this).parents('.mau_e').find('[data-name=lod_loq]').parents('.form-group').addClass('has-error');
                }
                // Add ngaytra KQ
                var input_chitieu_dateend_val = $(this).parents('.mau_e').find('[data-name=chitieu_dateend]').val();
                if(input_chitieu_dateend_val){
                    $(this).parents('.mau_e').find('[data-name=chitieu_dateend]').parents('.form-group').removeClass('has-error');
                    var input_chitieu_dateend = $('<input>', {
                        type: 'hidden',
                        class: 'val_chitieu_dateend',
                        value: input_chitieu_dateend_val,
                        name: 'mau[' + mau_number_current + '][chitieu][' + (chitieu_number + 1) + '][chitieu_dateend]'
                    });
                    $('<td>').append(input_chitieu_dateend_val).append(input_chitieu_dateend).appendTo(chitieu);
                }else{
                    error = true;
                    $(this).parents('.mau_e').find('[data-name=chitieu_dateend]').parents('.form-group').addClass('has-error');
                }
                if(!error){
                    // Add dichvu
                    var dichvu = $('<select>', 
                    { 
                        'class': 'form-control dichvu', 
                        'name': 'mau[' + mau_number_current + '][chitieu][' + (chitieu_number + 1) + '][dichvu]', 
                        'data-name': 'chitieu_dichvu' 
                    });
                    {foreach from=$data['list_dichvu'] item=dichvu}
                        dichvu.append($('<option>', { value: {$dichvu['dichvu_id']}, 'data-price': {$dichvu['dichvu_price']} }).html('{$dichvu['dichvu_name']}'));
                    {/foreach}
                    $('<td>').append(dichvu).appendTo(chitieu);
                    //Calculator price when select chat
                    var price_tmp = 0;
                    var chatgia_list = $(this).parents('.mau_e').find('[data-name="chatgia_list"]').val();
                    if(chatgia_list){
                        var chatgia_array = chatgia_list.split(',');
                        if(chatgia_array.length > 0){
                            if(chatgia_array.length > 1){
                                price_item.other = chatgia_array[chatgia_array.length - 1];
                                chatgia_array.splice(-1,1);
                                price_item.rank = chatgia_array;
                            }else{
                                price_item.other = chatgia_array[chatgia_array.length - 1];
                                price_item.rank = chatgia_array;
                            }
                        }
                    }
                    if(price_item.rank){
                        if($(this).parents('.mau_e').find('[data-name="chat"]').val().length <= price_item.rank.length){
                            price_tmp = parseFloat(price_item.rank[$(this).parents('.mau_e').find('[data-name="chat"]').val().length - 1]);
                        }else{
                            price_tmp = Math.max(...price_item.rank) + ($(this).parents('.mau_e').find('[data-name="chat"]').val().length - price_item.rank.length) * parseFloat(price_item.other);
                        }
                    }
                    console.log(price_item);
                    var price_root = $(this).parents('.mau_e').find('.package_price').val();
                    console.log(price_root);
                    price_root = price_item.rank?Math.min(price_tmp, price_root):price_root;
                    var price_default = parseFloat(price_root) * {$data['list_dichvu'][0]['dichvu_price']};
                    $('<td>').append($('<span>', { class: 'lbl_price_default' }).html(price_default))
                            .append($('<input>', { class: 'price_default_root', type: 'hidden', value: price_root }))
                            .append($('<input>', { class: 'price_default', type: 'hidden', value: price_default, name: 'mau[' + mau_number_current + '][chitieu][' + (chitieu_number + 1) + '][price_default]' }))
                            .appendTo(chitieu);
                    $('<td>').append($('<input>', { type: 'text', class: 'price', value: price_default, name: 'mau[' + mau_number_current + '][chitieu][' + (chitieu_number + 1) + '][price]' })).
                            appendTo(chitieu);
                    $('<td>').append(
                            $('<a>', { class:'chitieu_delete', href: '#' })
                            // Add date save mau of chitieu
                            .append($('<input>', { class: 'date_save_chitieu', type: 'hidden', value: $(this).parents('.mau_e').find('.date_save').val()}))
                            // Add delete icon
                            .append($('<i>', { class: 'ace-icon fa fa-trash-o bigger-120 red' }))
                    ).appendTo(chitieu);
                }
                // Add chitieu group
                if (error) {
                    alert('Điền đầy đủ thông tin');
                } else {
                    $(this).parents('.mau_e').find('.list_chitieu').children('tbody').append(chitieu);
                    $(this).parents('.mau_e').find('.chitieu_number').val(chitieu_number + 1);
                    $(this).parents('.mau_e').find('.mau_nenmau').attr('disabled', 'disabled').attr('data-disabled', 'disabled')
                            .trigger("chosen:updated").multiselect('reload');
                    $(this).parents('.mau_e').find('.mau_nenmau').removeAttr('disabled');
                    calculatorMaxDateSave($(this).parents('.mau_e'));
                    calculatorTotalPrice();
                    calculatorDateEnd();
                    if($('[name=hopdong_thitruong]').val()){
                        changeThiTruong($('[name=hopdong_thitruong]').val());
                    }
                }
                return false;
            });
            
            //=== CHANGE DICHVU
            $('.list_mau').on('change', '.dichvu', function(){
                var price_default = parseFloat($("option:selected", this).attr('data-price')) * parseFloat($(this).parents('tr').find('.price_default_root').val());
                $(this).parents('tr').find('.lbl_price_default').text(price_default);
                $(this).parents('tr').find('.price_default').val(price_default);
                $(this).parents('tr').find('.price').val(price_default);
                calculatorTotalPrice();
            });
            
            //=== CHANGE PRICE
            $('.list_mau').on('keyup', '.price', function(){
                calculatorTotalPrice();
            });
            
            //=== DELETE CHITIEU
            $('.list_mau').on('click', '.chitieu_delete', function () {
                var mau_e = $(this).parents('.mau_e');
                $(this).parents('tr').remove();
                if(mau_e.find('.chitieu').length === 0){
                    mau_e.find('.mau_nenmau').removeAttr('readonly').trigger("chosen:updated").multiselect('reload');
                }
                calculatorMaxDateSave(mau_e);
                calculatorTotalPrice();
                calculatorDateEnd();
                return false;
            });
            
            //=== COPY MAU
            $('.list_mau').on('click', '.mau_copy', function () {
                //alert($(this).attr('class'));return false;
                var mau_original = $(this).parents('.mau_e');
                // Get number of mau
                var mau_number = parseInt($('.mau_number').val());
                var mau_number_current = mau_original.attr('data-number');
                var mau_number_new = mau_number + 1;
                // Create new mau
                var mau_new = mau_original.clone();
                mau_new.attr('data-number', mau_number + 1);
                mau_new.find('input, select, textarea').each(function () {
                    this.name = this.name.replace('mau[' + mau_number_current + ']', 'mau[' + mau_number_new + ']');
                });
                // Set value select 
                mau_new.find('select').each(function(){
                    $(this).val(mau_original.find('select[data-name='+$(this).attr('data-name')+']').val());
                    if($(this).attr('data-disabled') === 'disabled'){
                        $(this).attr('disabled', 'disabled');
                    }
                });
                // Reset accordion
                mau_new.find('#accordion-mau-' + mau_number_current).attr('id', 'accordion-mau-' + mau_number_new);
                mau_new.find('#collapse-mau-' + mau_number_current).attr('id', 'collapse-mau-' + mau_number_new);
                mau_new.find('[data-parent=#accordion-mau-'+ mau_number_current + ']').attr('data-parent', '#accordion-mau-' + mau_number_new);
                mau_new.find('[href=#collapse-mau-'+ mau_number_current + ']').attr('href', '#collapse-mau-' + mau_number_new);
                // Delete mau_id [when edit hopdong]
                mau_new.find('.mau_id').val('');
                mau_new.find('.mau_code').val('');
                mau_new.find('.date_create').val('');
                // Reset input chitieu
                mau_new.find('.package_code').val('');
                mau_new.find('.chosen-container').remove();
                mau_new.find('.ms-options-wrap').remove();
                mau_new.find('.jqmsLoaded').removeClass('jqmsLoaded');
                mau_new.find('.chitieu_dateend').removeClass('hasDatepicker').removeAttr('id')
                .datepicker(datepicker_config).next().on(ace.click_event, function(){
                    $(this).prev().focus();
                });
                mau_new.find('.mau-datepicker').removeClass('hasDatepicker').removeAttr('id')
                .datepicker(datepicker_config).next().on(ace.click_event, function(){
                    $(this).prev().focus();
                });
                // Reset calculator DateSave
                calculatorMaxDateSave(mau_new);
                // Add mau to list mau
                $('.list_mau').append(mau_new);
                // Reset select with plugin
                mau_new.find('.chosen-select').chosen(chosen_config);
                mau_new.find('.multiple-select').multiselect(multiple_select_config);
                // Remove disable select input
                mau_new.find('select[data-disabled=disabled]').removeAttr('disabled');
                // Reset order of list mau
                $('.mau_number').val(mau_number + 1);
                $('.mau_e').each(function () {
                    $(this).find('.mau_order').html(String('00000' + ($('.mau_e').index(this) + 1)).slice(-3));
                });
                calculatorTotalPrice();
                calculatorDateEnd();
                return false;
            });
            
            //=== DELETE MAU
            $('.list_mau').on('click', '.mau_delete', function () {
                // Remove mau
                $(this).parents('.mau_e').remove();
                // Reset order of list mau
                $('.mau_e').each(function () {
                    $(this).find('.mau_order').html(String('00000' + ($('.mau_e').index(this) + 1)).slice(-3));
                });
                calculatorTotalPrice();
                calculatorDateEnd();
            });
            
            //=== CHANGE DEPOSIT
            $('.hopdong_deposit').on('keyup', function(){
                $('.hopdong_remaining').val(parseInt($('.total-price').val()) - parseInt($(this).val()));
                return false;
            });
            
            //=== CHANGE SAVE MAU
            $('.list_mau').on('click', '.mau_save', function(){
                if($(this).val() === '1'){
                    $(this).parents('.mau_e').find('.mau_datesave_yeucau').removeAttr('readonly');
                }else{
                    $(this).parents('.mau_e').find('.mau_datesave_yeucau').attr('readonly', 'readonly');
                }
            });
            
            //=== CHANGE THITRUONG
            $('[name=hopdong_thitruong]').on('change', function(){
                // Reset all check
                $('.chat-txt').removeClass('red has-compare');
                $('.info_lod_loq').remove();
                // Process change thitruong
                changeThiTruong($(this).val());
            });
            
            //=== Add datepicker
            $('.hopdong_dateend').datepicker(datepicker_config).next().on(ace.click_event, function(){
                $(this).prev().focus();
            });
            
            $('.chitieu_dateend').datepicker(datepicker_config).next().on(ace.click_event, function(){
                $(this).prev().focus();
            });
            
            $('.mau-datepicker').datepicker(datepicker_config).next().on(ace.click_event, function(){
                $(this).prev().focus();
            });
            
            $('#contact_birthday').datepicker(datepicker_config).next().on(ace.click_event, function(){
                $(this).prev().focus();
            });
            
            $('#fileupload').fileupload({
                dropZone: $('#upload-file'),
                formAcceptCharset: 'utf-8',
                dataType: 'json',
                type: 'POST',
                autoUpload: true,
                formData : { },
                add: function (e, data){
                    data.context = $('<div/>').addClass('file-element').prependTo('#hopdong-list-file'); //create new DIV with "file-element" class
                    $.each(data.files, function (index, file) { //loop though each file
                        var node = $('<div/>').addClass('row'); //create a new node with "row" class
                        //$('<div/>').addClass('col-xs-1 file-select').appendTo(node);  //create div file preview
                        $('<div/>').addClass('col-xs-4 file-view').appendTo(node);  //create div file preview
                        $('<div/>').addClass('col-xs-6 file-name').append(file.name).appendTo(node); //create div file name
                        $('<div/>').addClass('col-xs-2 file-processing').append('<div class="bar"></div>').appendTo(node); //create div file processing
                        node.appendTo(data.context); //attach node to data context
                    });
                    data.submit();
                },
                progress: function (e, data) {
                    var progress = parseInt(data.loaded / data.total * 100, 10);
                    if(data.context){
                        data.context.each(function () {
                            $(this).find('.file-processing').attr('aria-valuenow', progress).find('.bar').css('width',progress + '%');
                        });
                    }
                },
                done: function (e, data) {
                    $.each(data.result.hopdong_files, function (index, file) { //loop though each file
                        if (file.url){ //successful upload returns a file url
                            if(typeof file.id !== "undefined"){
                                $(data.context.find('.file-name')).append('<input type="hidden" name="hopdong_file[]" value="'+file.id+'">');
                            }
                            if(typeof file.thumbnailUrl !== "undefined"){
                                $(data.context.find('.file-view')).html($('<img />').attr('src', file.thumbnailUrl));
                            }
                            if(typeof file.fileIcon !== "undefined"){
                                $(data.context.find('.file-view')).html(file.fileIcon);
                            }
                        } else if (file.error) {
                            var error = $('<span class="text-danger"/>').text(file.error); //error text
                            $(data.context).append(error); //add to data context
                        }
                        $(data.context.find('.file-processing')).html(
                            $('<i />').addClass('fa fa-times hopdong-file-delete').
                            attr('data-file-id', file.id).attr('data-file-exts', file.exts)
                        );
                    });
                }
            });
            $(document).bind('dragover', function (e) {
                var dropZone = $('#upload-file'), timeout = window.dropZoneTimeout;
                if (!timeout) {
                    dropZone.addClass('in');
                } else {
                    clearTimeout(timeout);
                }
                var found = false, node = e.target;
                do {
                    if (node === dropZone[0]) {
                        found = true;
                        break;
                    }
                    node = node.parentNode;
                } while (node !== null);
                if (found) {
                    dropZone.addClass('hover');
                } else {
                    dropZone.removeClass('hover');
                }
                window.dropZoneTimeout = setTimeout(function () {
                    window.dropZoneTimeout = null;
                    dropZone.removeClass('in hover');
                }, 100);
            });
            $(document).bind('drop dragover', function (e) {
                e.preventDefault();
            });
            
            $('#hopdong-list-file').on('click', '.hopdong-file-delete', function(){
                var file_id = $(this).attr('data-file-id');
                var file_element = $(this).parents('.file-element');
                if($(this).attr('data-file-id') === "undefined" || $(this).attr('data-file-exts') === "undefined"){
                    file_element.slideUp(100, function(){
                        file_element.remove();
                    });
                }else{
                    //  Remove in list file of user
                    file_element.slideUp(100, function(){
                        file_element.remove();
                    });
                    return false;
                    /*
                    var file_name = {$data['nhansu_id']}+'_'+$(this).attr('data-file-id')+'.'+$(this).attr('data-file-exts');
                    $.ajax({
                        url: '{site_url()}nhanmau/upload?hopdong_file='+file_name,
                        type: 'DELETE',
                        contentType: 'application/json',
                        dataType: 'json',
                        success: function (response) {
                            //  Remove in list file of user
                            file_element.slideUp(100, function(){
                                file_element.remove();
                            });
                            return false;
                        }
                    });
                    */
                }
                return false;
            });
            
            //=== VALIDATION CONGTY, CONTACT
            /*
            $('.data-info').on('change', function(){
                var element = $(this);
                var data_check_type = element.attr('data-check-type');
                var type = element.attr('data-type');
                var id_current = data_check_type==='congty'?$('#congty_id').val():$('#contact_id').val();
                element.parents('.form-group').removeClass('has-error');
                checkInfo(data_check_type, type, element.val(), id_current, function(result){
                    if(result.err_code === '101'){
                        element.parents('.form-group').addClass('has-error');
                    }
                });
            });
            */
            //=== VALIDATION FORM
            $('#form-hopdong').submit(function(){
                var error = false;
                // Remove all error class
                $(document).find('.has-error').removeClass('has-error');
                // Validation input
                if(!$('#congty_name').val().trim()){
                    error = true;
                    $('#congty_name').parents('.form-group ').addClass('has-error');
                }
                if(!$('#contact_fullname').val().trim()){
                    error = true;
                    $('#contact_fullname').parents('.form-group ').addClass('has-error');
                }
                $('.mau_name, .mau_mass, .mau_donvitinh, .mau_description, .mau_amount, .mau_nenmau').each(function(){
                    if(!$(this).val().trim()){
                        error = true;
                        $(this).parents('.form-group ').addClass('has-error');
                    }
                });
                $('.data-info').each(function(){
                    var element = $(this);
                    var data_check_type = element.attr('data-check-type');
                    var type = element.attr('data-type');
                    var id_current = data_check_type==='congty'?$('#congty_id').val():$('#contact_id').val();
                    element.parents('.form-group').removeClass('has-error');
                    checkInfo(data_check_type, type, element.val(), id_current, function(result){
                        if(result.err_code === '101'){
                            error = true;
                            element.parents('.form-group').addClass('has-error');
                        }
                    });
                });
                //return false;
                $('.list_chitieu').each(function(){
                    var mau_e = $(this).parents('.mau_e');
                    if($(this).find('tbody tr').length <= 0){
                        error = true;
                        mau_e.find('.chitieu_e').parents('.form-group ').addClass('has-error');
                    }
                });
                
                if(error){
                    $('#form-error').removeClass('hidden');
                    $('html, body').animate({
                        scrollTop: $("#form-error").offset().top - 50
                    }, 500);
                }
                return !error;
            });
            
            //=== MESSAGE SUCCESS
            {if $data['add']}
                bootbox.dialog({
                    message: "<span class='bigger-140 green'><i class='fa fa-check-circle'></i> Thêm Phiếu yêu cầu thử nghiệm thành công</span>",
                    backdrop: true,
                    onEscape: true,
                    buttons:
                    {
                        "button" :
                        {
                            "label" : "<i class='fa fa-file-text-o' ></i> Danh sách phiếu",
                            "className" : "btn-sm btn-info",
                            "callback": function() {
                                window.location.href = '{site_url()}nhanmau';
                            }
                        },
                        "add" :
                        {
                            "label" : "<i class='fa fa-plus' ></i> Thêm phiếu mới",
                            "className" : "btn-sm btn-info"
                        }
                    }
                });
            {/if}
        });
    </script>
{/block}