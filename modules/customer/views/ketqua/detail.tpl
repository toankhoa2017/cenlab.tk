{* Extend our master template *}
{extends file="master.tpl"}
{block name=css}
    <link rel="stylesheet" href="{$assets_path}css/jquery.gritter.min.css">
    <style type="text/css">
        .profile-info-name{
            width: 200px;
        }
        .btn-action{
            position: initial;
        }
        .highlight { background-color: yellow }
    </style>
{/block}
{block name=body}
    <div class="main-content">
        <div class="main-content-inner">
            <!--PATH BEGINS-->
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li><i class="ace-icon fa fa-home home-icon"></i><a href="#">Home</a></li>
                    <li><a href="{site_url()}customer/ketqua/danhsachketqua">Danh sách phiếu trả kết quả</a></li>
                    <li class="active">Trả kết quả</li>
                </ul>
            </div>
            <!--PATH ENDS-->
            <div class="page-content">
                <div class="row">
                    <div class="col-xs-12">
                        <!-- PAGE CONTENT BEGINS -->
                        <h3 class="header smaller lighter blue"><i class="fa fa-file-text" aria-hidden="true"></i> Trả kết quả</h3>
                        {if $data['error']}
                            <div class="alert alert-danger">
                                <button type="button" class="close" data-dismiss="alert">
                                    <i class="ace-icon fa fa-times"></i>
                                </button>
                                <strong>
                                    <i class="ace-icon fa fa-times"></i>
                                    Lỗi!
                                </strong>
                                {$data['error']}
                            </div>
                        {else}
                            {if $data['file_result']}
                                <div class="row">
                                    <div class="col-md-12">
                                        <iframe 
                                            allowfullscreen="" 
                                            webkitallowfullscreen="" 
                                            src="{site_url()}pdfviewer/web/viewer.php?url={$data['file_result']}" 
                                            style="width: 100%; min-height: 700px;"> 
                                        </iframe>
                                        <!--
                                        <div class="col-xs-4"><strong>Phiếu kết quả:</strong></div>
                                        <div class="col-xs-8"><a href="{$data['file_result']}" target="_blank"><strong class="blue">{$data['hopdong']['hopdong_code']}</strong></a></div>
                                        -->
                                        <h4 class="header green smaller">
                                            <i class="fa fa-info-circle orange"></i> Phản hồi kết quả
                                        </h4>
                                        {if $data['list_phanhoi']}
                                            <table id="list-phanhoi" class="table table-striped table-bordered table-hover" style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th>STT</th>
                                                        <th>Nội dung</th>
                                                        <th>Ngày tạo</th>
                                                        <th>Tình trạng</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {foreach from=$data['list_phanhoi'] key=index item=phanhoi}
                                                    <tr>
                                                        <td>{($index+1)}</td>
                                                        <td>{$phanhoi['phanhoi_content']}</td>
                                                        <td>{$phanhoi['phanhoi_date']|date_format:"%d/%m/%Y %H:%M:%S"}</td>
                                                        <td>
                                                            <span class="editable {$phanhoi['approve_info']['class']}">
                                                                {$phanhoi['approve_info']['icon']} {$phanhoi['approve_info']['label']}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    {/foreach}
                                                </tbody>
                                            </table>
                                        {/if}
                                        <a class="btn btn-primary" href="{site_url()}customer/ketqua/phanhoi?ketqua_id={$data['ketqua_id']}">Phản Hồi</a>
                                    </div>
                                </div>
                            {else}
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="profile-activity">
                                            <div class="row">
                                                <div class="col-xs-4"><strong>BN:</strong></div>
                                                <div class="col-xs-8"><strong class="blue">{$data['hopdong']['hopdong_code']}</strong></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="profile-activity">
                                            <div class="row">
                                                <div class="col-xs-4"><strong>Ngày in:</strong></div>
                                                <div class="col-xs-8"><strong class="blue">{$data['ketqua']['create_date']|date_format:"%d/%m/%Y %H:%M:%S"}</strong></div>
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
                                            <i class="fa fa-info-circle orange"></i> Thông tin mẫu
                                        </h4>
                                        <div class="profile-user-info profile-user-info-striped" style="width: 100%;">
                                            <div class="profile-info-row">
                                                <div class="profile-info-name"> Tên mẫu </div>
                                                <div class="profile-info-value">
                                                    <span class="editable" id="username">{$data['hopdong']['mau']['mau_name']}</span>
                                                </div>
                                            </div>
                                            <div class="profile-info-row">
                                                <div class="profile-info-name"> Mô tả mẫu </div>
                                                <div class="profile-info-value">
                                                    <span class="editable" id="username">{$data['hopdong']['mau']['mau_description']}</span>
                                                </div>
                                            </div>
                                            <div class="profile-info-row">
                                                <div class="profile-info-name"> Nền mẫu </div>
                                                <div class="profile-info-value">
                                                    <span class="editable" id="username">{$data['hopdong']['mau']['nenmau_name']}</span>
                                                </div>
                                            </div>
                                            <div class="profile-info-row">
                                                <div class="profile-info-name"> Ngày nhận mẫu </div>
                                                <div class="profile-info-value">
                                                    <span class="editable" id="username">{$data['hopdong']['mau']['date_create']|date_format:"%d/%m/%Y %H:%M"}</span>
                                                </div>
                                            </div>
                                            <div class="profile-info-row">
                                                <div class="profile-info-name"> Ngày hẹn trả kết quả </div>
                                                <div class="profile-info-value">
                                                    <span class="editable" id="username">{$data['hopdong']['date_end']|date_format:"%d/%m/%Y"}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <h4 class="header green smaller"><i class="fa fa-flask orange" aria-hidden="true"></i> Thông tin chỉ tiêu</h4>
                                        <table class="table table-striped table-bordered table-hover list_chitieu" style="margin: 0;">
                                            <thead>
                                                <tr>
                                                    <th>Chỉ tiêu</th>
                                                    <th>Kết quả</th>
                                                    <th>LOD/LOQ (Min-Max)</th>
                                                    <th>Đơn vị tính</th>
                                                    <th>Phương pháp</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {foreach from=$data['hopdong']['list_chitieu'] item=chitieu}
                                                <tr>
                                                    <td>{$chitieu['chat_name']}</td>
                                                    <th>{$chitieu['chat_ketqua']}</th>
                                                    <th>
                                                        {if $chitieu['capacity'] == '1'}
                                                            {$chitieu['val_min']} / {$chitieu['val_max']}
                                                        {else}
                                                            {$chitieu['val_min']} - {$chitieu['val_max']}
                                                        {/if}
                                                    </th>
                                                    <th>{$chitieu['donvitinh']}</th>
                                                    <td>{$chitieu['phuongphap']}</td>
                                                </tr>
                                                {/foreach}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            {/if}
                        {/if}
                    </div>
                </div>
            </div>
        </div>
    </div>
{/block}
{block name=script}
{/block}