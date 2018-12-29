{* Extend our master template *}
{extends file="master.tpl"}
{block name=css}
    <style type="text/css">
        .profile-info-name{
            width: 200px;
        }
        .highlight { background-color: yellow }
        .group td{
            background: #eff3f8;
            color: #438eb9;
            font-weight: bold;
        }
        .group div{
            margin: 0;
            padding: 0;
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
                    <li class="active">Phiếu báo sự cố</li>
                </ul>
            </div>
            <!--PATH ENDS-->
            <div class="page-content">
                <div class="row">
                    <div class="col-xs-12">
                        <h3 class="header smaller lighter blue"><i class="fa fa-list" aria-hidden="true"></i> Phiếu báo sự cố</h3>
                        <!-- PAGE CONTENT BEGINS -->
                        <h4 class="header smaller lighter green"><i class="fa fa-file-text" aria-hidden="true"></i> Thông tin hợp đồng</h4>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="profile-user-info profile-user-info-striped" style="width: 100%;">
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Số BN </div>
                                        <div class="profile-info-value">
                                            <span class="editable"><strong>{$data['hopdong']['hopdong_code']}</strong></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Ngày lập </div>
                                        <div class="profile-info-value">
                                            <span class="editable"><strong>{$data['hopdong']['hopdong_createdate']}</strong></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Người lập </div>
                                        <div class="profile-info-value">
                                            <span class="editable"><strong>{$data['hopdong']['nhansu']['nhansu_lastname']} {$data['hopdong']['nhansu']['nhansu_firstname']}</strong></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Ngày hẹn trả kết quả </div>
                                        <div class="profile-info-value">
                                            <span class="editable"><strong>{$data['hopdong']['hopdong_dateend']}</strong></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tabbable" style="margin-top: 15px;">
                            <ul class="nav nav-tabs" id="myTab">
                                <li class="active">
                                    <a data-toggle="tab" href="#list_suco" aria-expanded="true">
                                        <i class="fa fa-list orange" aria-hidden="true"></i> Danh sách phiếu báo sự cố
                                        <span class="badge badge-danger">{if $data['list_suco']}{count($data['list_suco'])}{else}0{/if}</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a data-toggle="tab" href="#list_suco_khachhang" aria-expanded="false">
                                        <i class="fa fa-list orange" aria-hidden="true"></i> Thay đổi thông tin khách hàng
                                        <span class="badge badge-danger">{if $data['list_suco_khachhang']}{count($data['list_suco_khachhang'])}{else}0{/if}</span>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div id="list_suco" class="tab-pane fade active in">
                                    {if $data['list_suco']}
                                        {foreach from=$data['list_suco'] key=index item=suco_info}
                                        <div class="widget-box" style="border: 1px solid #6fb3e0; border-top-width: 3px; margin-bottom: 15px;">
                                            <div class="widget-toolbox clearfix" style="background: none; padding: 10px 12px;">
                                                <div class="row">
                                                    <div class="col-xs-3"><strong>Mã số mẫu:</strong> {$suco_info['mau_code']}</div>
                                                    <div class="col-xs-5"><strong>Tên mẫu:</strong> {$suco_info['mau_name']}</div>
                                                    <div class="col-xs-2"><strong>Người tạo:</strong> {$suco_info['nhansu']['nhansu_lastname']} {$suco_info['nhansu']['nhansu_firstname']}</div>
                                                    <div class="col-xs-2"><strong>Ngày tạo:</strong> {$suco_info['suco_createdate']|date_format:"%d/%m/%Y"}</div>
                                                </div>
                                            </div>
                                            <div class="widget-body">
                                                <div class="widget-main">
                                                    <p><strong>Nội dung:</strong> {$suco_info['suco_content']}</p>
                                                    {if $suco_info['list_suco_chitiet']}
                                                        <table class="table table-striped table-bordered table-hover" style="width: 100%;">
                                                            <thead>
                                                                <tr>
                                                                    <th>Nhóm chỉ tiêu</th>
                                                                    <th>Chỉ tiêu</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            {foreach from=$suco_info['list_suco_chitiet'] item=suco_chitiet}
                                                                <tr>
                                                                    <td>{$suco_chitiet['chitieu_name']}</td>
                                                                    <td>{if $suco_chitiet['list_chat_info']}
                                                                    <ul>
                                                                    {foreach from=$suco_chitiet['list_chat_info'] item=chat_info}
                                                                        <li>{$chat_info['chat_name']}</li>
                                                                    {/foreach}
                                                                    </ul>
                                                                {/if}</td>
                                                                </tr>
                                                            {/foreach}
                                                            </tbody>
                                                        </table>
                                                    {/if}
                                                </div>
                                            </div>
                                        </div>
                                        {/foreach}
                                    {/if}
                                </div>
                                <div id="list_suco_khachhang" class="tab-pane fade">
                                    {if $data['list_suco_khachhang']}
                                    <table class="table table-striped table-bordered table-hover" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>STT</th>
                                                <th>Nội dung</th>
                                                <th>Người tạo (duyệt)</th>
                                                <th>Ngày tạo (duyệt)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        {foreach from=$data['list_suco_khachhang'] key=index item=suco_khachhang_info}
                                            <tr>
                                                <th>{($index+1)}</th>
                                                <th>{$suco_khachhang_info['suco_content']}</th>
                                                <th>{$suco_khachhang_info['nhansu']['nhansu_lastname']} {$suco_khachhang_info['nhansu']['nhansu_firstname']}</th>
                                                <th>{$suco_khachhang_info['suco_createdate']|date_format:"%d/%m/%Y"}</th>
                                            </tr>
                                        {/foreach}
                                        </tbody>
                                    </table>
                                    {else}
                                        Không có phản hồi nào
                                    {/if}
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
{/block}
{block name=script}{/block}