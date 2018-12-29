{* Extend our master template *}
{extends file="master.tpl"}
{block name=css}
    <link rel="stylesheet" href="{$assets_path}css/jquery.gritter.min.css">
    <style type="text/css">
        .list_chitieu label,
        .list_chitieu .form-control,
        .list_chitieu output,
        table.list_chitieu{
            font-size: 13px !important;
        }
        .table>tbody>tr>td, .table>tbody>tr>th, 
        .table>tfoot>tr>td, .table>tfoot>tr>th, 
        .table>thead>tr>td, .table>thead>tr>th{
            vertical-align: middle;
        }
        .mau_e{
            margin-bottom: 20px;
            border: 1px solid #6fb3e0;
            border-top-width: 3px;
        }
        .lbl_mau_order{
            padding: 4px 0;
            font-weight: bold;
            color: #438eb9;
            font-size: 14px;
            display: block;
            float: left;
        }
        .price-bold{
            font-weight: bold;
            color: #bf2409;
        }
        .widget-main{
            overflow-x: auto;
        }
        .list_chitieu ul{
            margin-left: 16px;
            margin-bottom: 0;
        }
        .form-actions{
            position: relative;
            overflow: hidden;
        }
        .btn_pre_approve.btn-hidden,
        .btn_approve.btn-hidden{
            opacity: 0;
            visibility: hidden;
            position: absolute;
            float: left;
            -webkit-transform: translateX(20px);
            -moz-transform: translateX(20px);
            -ms-transform: translateX(20px);
            -o-transform: translateX(20px);
            transform: translateX(20px);
        }
        .btn_pre_approve,
        .btn_approve{
            position: relative;
            opacity: 1;
            visibility: visible;
            float: left;
            width: 100%;
            text-align: center;
            -webkit-transition: all 0.6s ;
            -moz-transition: all 0.6s;
            -ms-transition: all 0.6s;
            -o-transition: all 0.6s;
            transition: all 0.6s;
            -webkit-transform: translateX(0);
            -moz-transform: translateX(0);
            -ms-transform: translateX(0);
            -o-transform: translateX(0);
            transform: translateX(0);
        }
        .modal-open #gritter-notice-wrapper{
            right: 37px;
        }
        .gritter-close {
            left: auto;
            right: 4px;
        }
        @media print {
            .col-md-6{
                width: 50%;
                float: left;
            }
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
                    <li class="active">Chi tiết Phiếu yêu cầu thử nghiệm</li>
                </ul>
            </div>
            <!--PATH ENDS-->
            <div class="page-content">
                <div class="row">
                    <div class="col-xs-12">
                        <!-- PAGE CONTENT BEGINS -->
                        <h3 class="header smaller lighter blue"><i class="fa fa-list" aria-hidden="true"></i> Chi tiết PHIẾU YÊU CẦU THỬ NGHIỆM</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="profile-activity">
                                    <div class="row">
                                        <div class="col-xs-4"><strong>Mã số phiếu:</strong></div>
                                        <div class="col-xs-8"><strong class="blue">{$data['hopdong']['hopdong_code']}</strong></div>
                                    </div>
                                </div>
                                <div class="profile-activity">
                                    <div class="row">
                                        <div class="col-xs-4"><strong>Ngày lập phiếu:</strong></div>
                                        <div class="col-xs-8">{$data['hopdong']['hopdong_createdate']|date_format:"%d/%m/%Y %H:%M:%S"}</div>
                                    </div>
                                </div>
                                <div class="profile-activity">
                                    <div class="row">
                                        <div class="col-xs-4"><strong>Người lập:</strong></div>
                                        <div class="col-xs-8">{$data['hopdong']['nhansu']['nhansu_lastname']} {$data['hopdong']['nhansu']['nhansu_firstname']}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="profile-activity">
                                    <div class="row">
                                        <div class="col-xs-4"><strong>Tình trạng:</strong></div>
                                        <div class="col-xs-8">
                                            {if $data['hopdong']['hopdong_approve'] == '1'}
                                                <span class="label label-sm label-success">
                                                    <i class="fa fa-check" aria-hidden="true"></i> {$data['hopdong']['hopdong_approve_txt']}
                                                </span>
                                            {elseif $data['hopdong']['hopdong_approve'] == '2'}
                                                <span class="label label-sm label-danger">
                                                    <i class="fa fa-times" aria-hidden="true"></i> {$data['hopdong']['hopdong_approve_txt']}
                                                </span>
                                            {elseif $data['hopdong']['hopdong_approve'] == '3'}
                                                <span class="label label-sm label-warning">
                                                    <i class="fa fa-pencil" aria-hidden="true"></i> {$data['hopdong']['hopdong_approve_txt']}
                                                </span>
                                            {elseif $data['hopdong']['hopdong_approve'] == '4'}
                                                <span class="label label-sm label-warning">
                                                    <i class="fa fa-pencil" aria-hidden="true"></i> {$data['hopdong']['hopdong_approve_txt']}
                                                </span>
                                            {else}
                                                <span class="label label-sm label-warning">
                                                    <i class="fa fa-pencil" aria-hidden="true"></i> Chưa duyệt
                                                </span>
                                            {/if}
                                        </div>
                                    </div>
                                </div>
                                <div class="profile-activity">
                                    <div class="row">
                                        <div class="col-xs-4"><strong>Ghi chú:</strong></div>
                                        <div class="col-xs-8">{$data['hopdong']['duyet_content']}</div>
                                    </div>
                                </div>
                                <div class="profile-activity">
                                    <div class="btn-group btn-action">
                                        <button type="button" class="btn btn-sm btn-info approve-history"><i class="fa fa-history" aria-hidden="true"></i> Lịch sử duyệt</button>
                                        <!--
                                        {if $data['hopdong']['suahopdong_id'] == NULL}
                                            <button type="button" class="btn btn-sm btn-info edit-request"><i class="fa fa-file-text-o" aria-hidden="true"></i> Thêm Đề nghị sửa</button>
                                        {else}
                                            <button type="button" class="btn btn-sm btn-info edit-request"><i class="fa fa-file-text-o" aria-hidden="true"></i> Cập nhật Đề nghị sửa</button>
                                        {/if}
                                        -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="header green smaller">
                                    <i class="fa fa-briefcase orange"></i> Công ty
                                </h4>
                                <div class="profile-user-info profile-user-info-striped" style="width: 100%;">
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Tên công ty </div>
                                        <div class="profile-info-value">
                                            <span class="editable" id="username">{$data['hopdong']['congty']['congty_name']}</span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Địa chỉ </div>
                                        <div class="profile-info-value">
                                            <i class="fa fa-map-marker light-orange bigger-110"></i>
                                            <span class="editable" id="country">{$data['hopdong']['congty']['congty_address']}</span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Email </div>
                                        <div class="profile-info-value">
                                            <span class="editable" id="age">{$data['hopdong']['congty']['congty_email']}</span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Số điện thoại </div>
                                        <div class="profile-info-value">
                                            <span class="editable" id="signup">{$data['hopdong']['congty']['congty_phone']}</span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Fax </div>
                                        <div class="profile-info-value">
                                            <span class="editable" id="login">{$data['hopdong']['congty']['congty_fax']}</span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Mã số thuế </div>
                                        <div class="profile-info-value">
                                            <span class="editable" id="about">{$data['hopdong']['congty']['congty_tax']}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h4 class="header green smaller">
                                    <i class="fa fa-user orange"></i> Người liên hệ
                                </h4>
                                <div class="profile-user-info profile-user-info-striped" style="width: 100%;">
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Họ và tên </div>
                                        <div class="profile-info-value">
                                            <span class="editable" id="username">{$data['hopdong']['contact']['contact_fullname']}</span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Ngày sinh </div>
                                        <div class="profile-info-value">
                                            <span class="editable" id="country">{$data['hopdong']['contact']['contact_birthday']|date_format:"%d/%m/%Y"}</span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Email </div>
                                        <div class="profile-info-value">
                                            <span class="editable" id="country">{$data['hopdong']['contact']['contact_email']}</span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Số điện thoại </div>
                                        <div class="profile-info-value">
                                            <span class="editable" id="age">{$data['hopdong']['contact']['contact_phone']}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <h4 class="header green smaller"><i class="fa fa-flask orange" aria-hidden="true"></i> Thông tin mẫu</h4>
                                {foreach from=$data['hopdong']['list_mau'] item=mau}
                                    <div class="widget-box mau_e">
                                        <div class="widget-toolbox clearfix" style="background: none; padding: 5px 12px;">
                                            <span class="lbl_mau_order">Mã số: <span class="mau_order">{$mau['mau_code']}</span></span>
                                        </div>
                                        <div class="widget-header widget-header-small widget-header-blue widget-header-flat" style="padding: 5px 12px;">
                                            <div class="row">
                                                <div class="col-md-7">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <h5 class="widget-title smaller">Tên mẫu: {$mau['mau_name']}</h5>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <h5 class="widget-title smaller">Khối lượng mẫu: {$mau['mau_mass']} ({$mau['donvitinh_name']})</h5>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <h5 class="widget-title smaller">Số lượng mẫu: {$mau['mau_amount']}</h5>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <h5 class="widget-title smaller">Mô tả mẫu: {$mau['mau_description']}</h5>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <h5 class="widget-title smaller">Nền mẫu: {$mau['nenmau_name']}</h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="widget-body">
                                            <div class="widget-main">
                                                <table class="table table-striped table-bordered table-hover list_chitieu" style="margin: 0;">
                                                    <thead>
                                                        <tr>
                                                            <th>Chỉ tiêu</th>
                                                            <th>Phương pháp</th>
                                                            <th>Kỹ thuật</th>
                                                            <th>Phòng TN</th>
                                                            <th>Chất phân tích</th>
                                                            <th>LOD/LOQ</th>
                                                            <th>Ngày trả KQ</th>
                                                            <th>Dịch vụ</th>
                                                            <th>Giá hệ thống<br/>(VNĐ)</th>
                                                            <th>Giá<br/>(VNĐ)</th>
                                                            <th>PTN Nhận mẫu</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        {foreach from=$mau['list_chitieu'] item=chitieu}
                                                        <tr>
                                                            <td>{$chitieu['chitieu_name']}</td>
                                                            <td>{$chitieu['phuongphap_name']}</td>
                                                            <td>{$chitieu['kythuat_name']}</td>
                                                            <td>{$chitieu['ptn_name']}</td>
                                                            <td>
                                                                <ul>
                                                                {foreach from=$chitieu['list_chat'] item=chat}
                                                                    <li>{$chat['chat_name']}</li>
                                                                {/foreach}
                                                                </ul>
                                                            </td>
                                                            <td>{$chitieu['lod_loq_txt']}</td>
                                                            <td>{$chitieu['chitieu_dateend']|date_format:"%d/%m/%Y"}</td>
                                                            <td>{$chitieu['dichvu_name']}</td>
                                                            <td>{$chitieu['price_tmp']|number_format:0:",":"."}</td>
                                                            <td>
                                                                <span class="{if $chitieu['price_tmp'] > $chitieu['price']}price-bold{/if}">
                                                                    {$chitieu['price']|number_format:0:",":"."}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                {if $chitieu['mauptn_approve'] == '1'}
                                                                    <span class="green"><i class="fa fa-check"></i> Đã nhận mẫu</span>
                                                                {elseif $chitieu['mauptn_approve'] == '2'}
                                                                    <span class="red"><i class="fa fa-times"></i> Đã từ chối mẫu</span>
                                                                {/if}
                                                            </td>
                                                        </tr>
                                                        {/foreach}
                                                    </tbody>
                                                </table>
                                            </div><!-- /.widget-main -->
                                        </div><!-- /.widget-body -->
                                    </div>
                                {/foreach}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <h4 class="header green smaller"><i class="fa fa-flask orange" aria-hidden="true"></i> Hình ảnh mẫu</h4>
                                <div id="hopdong-list-file">
                                    {if $data['hopdong']['list_file']}
                                        {foreach from=$data['hopdong']['list_file'] item=file}
                                            <div class="col-md-4" style="text-align: center;">
                                                {if strpos($file['file_type'], 'image/') !== FALSE}
                                                    <img src="{$file['file_url']}">
                                                {else}
                                                    <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                                {/if}
                                                <p class="file-name" style="text-align: center;">{$file['file_name']}</p>
                                            </div>
                                        {/foreach}
                                    {/if}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <h4 class="header green smaller"><i class="fa fa-file-text orange" aria-hidden="true"></i> Thông tin trả kết quả</h4>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="profile-activity">
                                            <div class="row">
                                                <div class="col-xs-4"><strong>Ngôn ngữ:</strong></div>
                                                <div class="col-xs-8">
                                                    {($data['hopdong']['hopdong_resultlang'] == '0')?'Tiếng Việt':(($data['hopdong']['hopdong_resultlang'] == '1')?'Tiếng Anh':'Anh-Việt')}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="profile-activity">
                                            <div class="row">
                                                <div class="col-xs-4"><strong>Cách thức nhận kết quả:</strong></div>
                                                <div class="col-xs-8">
                                                    {($data['hopdong']['hopdong_resultvia'] == '0')?'Trực tiếp':(($data['hopdong']['hopdong_resultvia'] == '1')?'Thư':'Email')}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="profile-activity">
                                            <div class="row">
                                                <div class="col-xs-4"><strong>Thị trường áp dụng:</strong></div>
                                                <div class="col-xs-8">{$data['hopdong']['thitruong_name']}</div>
                                            </div>
                                        </div>
                                        <div class="profile-activity">
                                            <div class="row">
                                                <div class="col-xs-4"><strong>Quy chuẩn so sánh:</strong></div>
                                                <div class="col-xs-8">{$data['hopdong']['hopdong_quychuan']}</div>
                                            </div>
                                        </div>
                                        <div class="profile-activity">
                                            <div class="row">
                                                <div class="col-xs-4"><strong>Yêu cầu khác:</strong></div>
                                                <div class="col-xs-8">{$data['hopdong']['hopdong_yeucaukhac']}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="profile-activity">
                                            <div class="row">
                                                <div class="col-xs-4"><strong>Phí thử nghiệm:</strong></div>
                                                <div class="col-xs-8">{$data['hopdong']['hopdong_price']}</div>
                                            </div>
                                        </div>
                                        <div class="profile-activity">
                                            <div class="row">
                                                <div class="col-xs-4"><strong>Tạm ứng:</strong></div>
                                                <div class="col-xs-8">{$data['hopdong']['hopdong_deposit']}</div>
                                            </div>
                                        </div>
                                        <div class="profile-activity">
                                            <div class="row">
                                                <div class="col-xs-4"><strong>Còn lại:</strong></div>
                                                <div class="col-xs-8">{$data['hopdong']['hopdong_remaining']}</div>
                                            </div>
                                        </div>
                                        <div class="profile-activity">
                                            <div class="row">
                                                <div class="col-xs-4"><strong>Ngày trả kết quả:</strong></div>
                                                <div class="col-xs-8">{$data['hopdong']['hopdong_dateend']|date_format:"%d/%m/%Y"}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <p style="margin: 0; margin-top: 15px; padding: 8px 0;">
                                    <i class="fa fa-download" aria-hidden="true"></i> Download hợp đồng: 
                                    <a href="{$data['hopdong']['file_download_word']}" target="_blank">
                                        <i class="fa fa-file-word-o" aria-hidden="true"></i> Word file
                                    </a>
                                     | 
                                    <a href="{$data['hopdong']['file_download_pdf']}" target="_blank">
                                        <i class="fa fa-file-pdf-o" aria-hidden="true"></i> Pdf file
                                    </a>
                                </p>
                            </div>
                        </div>
                        <div class="clearfix form-actions">
                            {if $data['hopdong']['hopdong_status'] == 3}
                                <a href="{site_url()}nhanmau/add?hopdong={$data['hopdong']['hopdong_code']}&hopdong_copy=1" class="btn btn-info">Sử dụng mẫu</a>
                            {elseif $data['hopdong']['hopdong_approve'] != 1 && $data['hopdong']['hopdong_approve'] != 2}
                                {if $data['duyet_hopdong']}
                                    {if $data['hopdong']['mau_in_ptn']}
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="alert alert-warning">
                                                <strong class="red">Lưu ý!</strong> Mẫu đã vào phòng thí nghiệm. <a href="{site_url()}nhanmau/detail?hopdong_id={$data['hopdong']['hopdong_idparent']}" target="_blank">Phiếu thử nghiệm trước khi sửa</a>
                                            </div>
                                        </div>
                                    </div>
                                    {/if}
                                    <div class="btn_approve {if $data['hopdong']['hopdong_approve'] != 0}btn-hidden{/if}">
                                        <button class="btn btn-warning hopdong_approve" type="button" data-value="2">
                                            <i class="ace-icon fa fa-times bigger-110"></i> Không đồng ý
                                        </button>
                                        <button class="btn btn-info hopdong_approve" type="button" data-value="1">
                                            <i class="ace-icon fa fa-check bigger-110"></i> Đồng ý
                                        </button>
                                        <button class="btn btn-yellow hopdong_approve" type="button" data-value="3">
                                            <i class="ace-icon fa fa-pencil bigger-110"></i> Yêu cầu sửa lại
                                        </button>
                                    </div>
                                {/if}
                                <div class="btn_pre_approve {if $data['hopdong']['hopdong_approve'] == 0}btn-hidden{/if}">
                                    {if $data['duyet_hopdong'] && $data['hopdong']['hopdong_approve'] != '1'}
                                        <button class="btn btn-warning hopdong_approve" type="button" data-value="2">
                                            <i class="ace-icon fa fa-retweet bigger-110"></i> Duyệt lại
                                        </button>
                                    {/if}
                                </div>
                            {/if}
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
{/block}
{block name=script}
<script src="{$assets_path}js/bootbox.js"></script>
<script src="{$assets_path}js/jquery.gritter.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('.approve-history').on('click', function(){
            $.ajax({
                type: "POST",
                url: "{site_url()}nhanmau/ajax_approve_history",
                dataType: "json",
                data: {
                    hopdong_id: {$data['hopdong']['hopdong_id']}
                },
                success: function (data) {
                    if(data['code'] == 0){
                        $.gritter.add({
                            title: 'Lấy lịch sử duyệt thất bại!',
                            text: data['message'],
                            time: 3000,
                            class_name: 'gritter-error'
                        });
                    }else{
                        var content_history = $('<table>', { 'class': 'table table-striped table-bordered' });
                        content_history.append($('<tr>').append('<th>STT</th><th>Kết quả</th><th>Ghi chú</th><th>Người duyệt</th>'));
                        if(data['data']){
                            $.each(data['data'], function(index, value){
                                var row_history = $('<tr>');
                                row_history.append($('<td>').html(index + 1));
                                row_history.append($('<td>').html(value.hopdong_approve_txt));
                                row_history.append($('<td>').html(value.duyet_content));
                                row_history.append($('<td>').html(value.duyet_user));
                                content_history.append(row_history);
                            });
                        }
                        bootbox.dialog({
                            title: 'Lịch sử duyệt hợp đồng',
                            message: content_history,
                            size: 'large',
                            backdrop: true,
                            onEscape: true
                        });
                    }
                },
                error: function () {
                    $.gritter.add({
                        title: 'Lấy lịch sử duyệt thất bại!',
                        time: 3000,
                        class_name: 'gritter-error'
                    });
                }
            });
            
        });
        $('.edit-request').on('click', function(){
            bootbox.dialog({
                title: 'Thêm phiếu đề nghị sửa hợp đồng',
                message: '<p>Tiêu đề</p>'+
                        '<p><input type="text" class="form-control edit_title" value="{$data['hopdong']['suahopdong_name']}"><input type="hidden" class="edit_id" value="{$data['hopdong']['suahopdong_id']}"></p>'+
                        '<p><strong>Nội dung đề nghị</strong></p>'+
                        '<textarea class="form-control edit_content" rows="5">{$data['hopdong']['suahopdong_content']}</textarea>',
                size: 'large',
                backdrop: true,
                onEscape: true,
                buttons:
                {
                    "button" :
                    {
                        "label" : "<i class='ace-icon fa fa-times bigger-110'></i> Hủy bỏ",
                        "className" : "btn-sm"
                    },
                    "click" :
                    {
                        "label" : "<i class='ace-icon fa fa-check'></i> Xác nhận",
                        "className" : "btn-sm btn-primary",
                        "callback": function() {
                            var edit_title = $('.edit_title').val();
                            var edit_content = $('.edit_content').val();
                            if(!edit_title || !edit_content || edit_title === '' || edit_content === ''){
                                $.gritter.add({
                                    title: 'Tiêu đề và nội dung đề nghị không để trống!',
                                    time: 3000,
                                    class_name: 'gritter-error'
                                });
                                return false;
                            }else{
                                $.ajax({
                                    type: "POST",
                                    url: "{site_url()}nhanmau/ajax_edit_request",
                                    dataType: "json",
                                    data: {
                                        hopdong_id: {$data['hopdong']['hopdong_id']},
                                        edit_id: $('.edit_id').val(),
                                        edit_title: edit_title,
                                        edit_content: edit_content
                                    },
                                    success: function (data) {
                                        if(data['code'] == 0){
                                            $.gritter.add({
                                                title: 'Thêm phiếu đề nghị sửa hợp đồng thất bại!',
                                                text: data['message'],
                                                time: 3000,
                                                class_name: 'gritter-error'
                                            });
                                        }else{
                                            $.gritter.add({
                                                title: 'Thêm phiếu đề nghị sửa hợp đồng thành công!',
                                                time: 3000,
                                                class_name: 'gritter-success'
                                            });
                                        }
                                    },
                                    error: function () {
                                    }
                                });
                            }
                        }
                    }
                }
            });
            return false;
        });
        $('.btn_approve').on('click', '.hopdong_approve', function(){
            var form_action = $(this).parents('.form-actions');
            var hopdong_approve = $(this).attr('data-value');
            var title = '';
            if(hopdong_approve === '1' || hopdong_approve === '4'){
                title = 'Xác nhận chấp nhận phiếu yêu cầu thử nghiệm';
            }else if(hopdong_approve === '2'){
                title = 'Xác nhận hủy phiếu yêu cầu thử nghiệm';
            }else{
                title = 'Xác nhận sửa đổi phiếu yêu cầu thử nghiệm';
            }
            bootbox.dialog({
                title: title,
                message: '<p><strong>Ghi chú</strong></p><textarea class="form-control duyet_content"></textarea>',
                backdrop: true,
                onEscape: true,
                buttons:
                {
                    "button" :
                    {
                        "label" : "<i class='ace-icon fa fa-times bigger-110'></i> Hủy bỏ",
                        "className" : "btn-sm"
                    },
                    "click" :
                    {
                        "label" : "<i class='ace-icon fa fa-check'></i> Xác nhận",
                        "className" : "btn-sm btn-primary",
                        "callback": function() {
                            var duyet_content = $('.duyet_content').val();
                            $.ajax({
                                type: "POST",
                                url: "{site_url()}nhanmau/ajax_approve",
                                dataType: "json",
                                data: {
                                    hopdong_id: {$data['hopdong']['hopdong_id']},
                                    hopdong_approve: hopdong_approve,
                                    duyet_content: duyet_content
                                },
                                success: function (data) {
                                    if(data['code'] == 0){
                                        $.gritter.add({
                                            title: 'Duyệt hợp đồng thất bại!',
                                            text: data['message'],
                                            time: 3000,
                                            class_name: 'gritter-error'
                                        });
                                    }else{
                                        window.location.href = '{site_url()}nhanmau/detail?hopdong={$data['hopdong']['hopdong_code']}&approve=true';
                                    }
                                },
                                error: function () {
                                }
                            });
                        }
                    }
                }
            });
            return false;
        });
        
        $('.btn_pre_approve').on('click', '.hopdong_approve',function(){
            $(this).parents('.btn_pre_approve').addClass('btn-hidden');
            $('.btn_approve').removeClass('btn-hidden');
        });
        
        //=== MESSAGE EDIT SUCCESS
        {if $data['edit']}
            bootbox.dialog({
                message: "<span class='bigger-140 green'><i class='fa fa-check-circle'></i> Sửa Phiếu yêu cầu thử nghiệm thành công</span>",
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
                        "label" : "<i class='fa fa-retweet' ></i> Kiểm tra lại",
                        "className" : "btn-sm btn-info"
                    }
                }
            });
        {/if}

        //=== MESSAGE APPROVE SUCCESS
        {if $data['approve']}
            $.gritter.add({
                title: 'Duyệt hợp đồng thành công!',
                time: 3000,
                class_name: 'gritter-success'
            });
        {/if}
    });
</script>
{/block}