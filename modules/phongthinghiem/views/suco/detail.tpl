{* Extend our master template *}
{extends file="master.tpl"}
{block name=css}
    <style type="text/css">
        .profile-info-name{
            width: 200px;
        }
        .chat_suco{
            text-decoration: line-through;
            color: #D15B47;
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
                    <li><a href="{site_url()}phongthinghiem/suco">Phiếu báo sự cố</a></li>
                    <li class="active">Duyệt phiếu báo sự cố</li>
                </ul>
            </div>
            <!--PATH ENDS-->
            <div class="page-content">
                <div class="row">
                    <div class="col-xs-12">
                        <!-- PAGE CONTENT BEGINS -->
                        <h3 class="header smaller lighter blue"><i class="fa fa-list" aria-hidden="true"></i> Duyệt phiếu báo sự cố</h3>
                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="widget-title green smaller">
                                    <i class="fa fa-flask orange"></i> Thông tin phiếu báo sự cố
                                </h4>
                                <div class="profile-user-info profile-user-info-striped" style="width: 100%;">
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Nội dung </div>
                                        <div class="profile-info-value">
                                            <span class="editable"><strong>{$data['suco']['suco_content']}</strong></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Người tạo </div>
                                        <div class="profile-info-value">
                                            <span class="editable">{$data['suco']['nhansu']['nhansu_lastname']} {$data['suco']['nhansu']['nhansu_firstname']}</span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Ngày tạo </div>
                                        <div class="profile-info-value">
                                            <span class="editable">{$data['suco']['suco_createdate']}</span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Trạng thái </div>
                                        <div class="profile-info-value">
                                            <span class="editable {$data['suco']['suco_approve_info']['class']}">
                                                {$data['suco']['suco_approve_info']['icon']} {$data['suco']['suco_approve_info']['label']}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <h4 class="header green smaller"><i class="fa fa-file-text orange" aria-hidden="true"></i> Chỉ tiêu báo sự cố</h4>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <table class="table table-striped table-bordered table-hover" style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th>STT</th>
                                                    <th>Nhóm chỉ tiêu</th>
                                                    <th>Chỉ tiêu</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {if $data['suco']['list_suco_chitiet']}
                                                {foreach from=$data['suco']['list_suco_chitiet'] key=index item=suco_chitiet}
                                                    <tr>
                                                        <td>{($index + 1)}</td>
                                                        <td>{$suco_chitiet['chitieu_name']}</td>
                                                        <td>
                                                            {if $suco_chitiet['list_chat_info']}
                                                                <ul>
                                                                {foreach from=$suco_chitiet['list_chat_info'] item=chat_info}
                                                                    <li>{$chat_info['chat_name']}</li>
                                                                {/foreach}
                                                                </ul>
                                                            {/if}
                                                        </td>
                                                    </tr>
                                                {/foreach}
                                                {/if}
                                            </tbody>
                                        </table>
                                        {if $data['suco']['suco_approve'] == '0'}
                                        <form class="form-horizontal" method="POST" action="">
                                            <div class="clearfix form-actions" style="padding: 20px 0;">
                                                <div class="col-md-offset-3 col-md-9">
                                                    <button class="btn" type="submit" name="suco_approve" value="2">
                                                        <i class="ace-icon fa fa-times bigger-110"></i> Không đồng ý
                                                    </button>
                                                    <button class="btn btn-info result-save" type="submit" name="suco_approve" value="1">
                                                        <i class="ace-icon fa fa-check bigger-110"></i> Đồng ý
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                        {/if}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
{/block}
{block name=script}
<script src="{$assets_path}js/bootbox.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    $('[data-rel=tooltip]').tooltip();
    {if $data['result']}
        bootbox.dialog({
            message: "<span class='bigger-140 green'><i class='fa fa-check-circle'></i> Duyệt phiếu sự cố thành công</span>",
            backdrop: true,
            onEscape: true,
            buttons:
            {
                "button" :
                {
                    "label" : "<i class='fa fa-file-text-o' ></i> Danh sách chờ xử lý",
                    "className" : "btn-sm btn-info",
                    "callback": function() {
                        window.location.href = '{site_url()}phongthinghiem/suco';
                    }
                }
            }
        });
    {/if}
});
</script>
{/block}