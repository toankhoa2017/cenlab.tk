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
                    <li><a href="{site_url()}phongthinghiem/duyetketqua">Danh sách mẫu</a></li>
                    <li class="active">Duyệt kết quả thí nghiệm</li>
                </ul>
            </div>
            <!--PATH ENDS-->
            <div class="page-content">
                <div class="row">
                    <div class="col-xs-12">
                        <!-- PAGE CONTENT BEGINS -->
                        <h3 class="header smaller lighter blue"><i class="fa fa-list" aria-hidden="true"></i> Kết quả thí nghiệm</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="widget-title green smaller">
                                    <i class="fa fa-flask orange"></i> Thông tin mẫu
                                </h4>
                                <div class="profile-user-info profile-user-info-striped" style="width: 100%;">
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Mã số Mẫu </div>
                                        <div class="profile-info-value">
                                            <span class="editable"><strong>{$data['package']['mau_code']}</strong></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Tên mẫu </div>
                                        <div class="profile-info-value">
                                            <span class="editable">{$data['package']['mau_name']}</span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Khối lượng mẫu </div>
                                        <div class="profile-info-value">
                                            <span class="editable">{$data['package']['mau_mass']} ({$data['package']['donvitinh_name']})</span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Mô tả </div>
                                        <div class="profile-info-value">
                                            <span class="editable">{$data['package']['mau_description']}</span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Ghi chú </div>
                                        <div class="profile-info-value">
                                            <span class="editable">{$data['package']['mau_note']}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h4 class="widget-title green smaller">
                                    <i class="fa fa-flask orange"></i> Thông tin thí nghiệm
                                </h4>
                                <div class="profile-user-info profile-user-info-striped" style="width: 100%;">
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Nền mẫu </div>
                                        <div class="profile-info-value">
                                            <span class="editable">{$data['package']['nenmau_name']}</span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Chỉ tiêu </div>
                                        <div class="profile-info-value">
                                            <span class="editable">{$data['package']['chitieu_name']}</span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Phương pháp </div>
                                        <div class="profile-info-value">
                                            <span class="editable">{$data['package']['phuongphap_name']}</span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Kỹ thuật </div>
                                        <div class="profile-info-value">
                                            <span class="editable">{$data['package']['kythuat_name']}</span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Phòng thí nghiệm </div>
                                        <div class="profile-info-value">
                                            <span class="editable">{$data['package']['ptn_name']}</span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Đơn vị tính </div>
                                        <div class="profile-info-value">
                                            <span class="editable">{$data['package']['chitieu_donvitinh_name']}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <h4 class="header green smaller"><i class="fa fa-file-text orange" aria-hidden="true"></i> Kết quả</h4>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <form id="form-result" class="form-horizontal" method="POST" action="">
                                            <div class="row">
                                                {if $data['package']['mauketqua_list']}
                                                    {foreach from=$data['package']['mauketqua_list'] item=mauketqua}
                                                        <div class="col-xs-12">
                                                            <div class="profile-activity clearfix">
                                                                <div class="col-md-1 no-padding" style="font-size: 20px; line-height: 0;">
                                                                    <span data-rel="tooltip" data-original-title="{$mauketqua['mauketqua_approve_txt']['label']}" class="{$mauketqua['mauketqua_approve_txt']['class']}">
                                                                        {$mauketqua['mauketqua_approve_txt']['icon']}
                                                                    </span>
                                                                </div>
                                                                <div class="col-md-9 no-padding">
                                                                    {foreach from=$data['package']['list_chat'] key=index item=chat}
                                                                        <div class="col-md-4 no-padding">
                                                                            <div class="form-group" style="margin: 0;">
                                                                                <label class="col-sm-5 control-label no-padding {if $chat['chat_suco']}chat_suco{/if}" for="chat-{$chat['chat_id']}"> 
                                                                                    <strong>{$chat['chat_name']}: </strong> 
                                                                                </label>
                                                                                <div class="col-sm-7" style="font-size: 14px;">
                                                                                    {$mauketqua['list_ketqua'][$chat['chat_id']]}
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    {/foreach}
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <strong style="text-decoration: underline;">Ghi chú:</strong> <i>{$mauketqua['mauketqua_ghichu']}</i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    {/foreach}
                                                {/if}
                                            </div>
                                            {assign var="last_mauketqua" value=end($data['package']['mauketqua_list'])}
                                            {if !$data['package']['mauketqua_list'] || ($last_mauketqua && $last_mauketqua['mauketqua_approve'] == '0')}
                                            <div class="clearfix form-actions" style="padding: 20px 0;">
                                                <div class="col-md-offset-3 col-md-9">
                                                    <button class="btn btn-info result-save" type="submit" name="mauketqua_approve" value="1">
                                                            <i class="ace-icon fa fa-check bigger-110"></i> Đồng ý
                                                    </button>
                                                    <button class="btn" type="submit" name="mauketqua_approve" value="2">
                                                            <i class="ace-icon fa fa-times bigger-110"></i> Không đồng ý
                                                    </button>
                                                </div>
                                            </div>
                                            {/if}
                                        </form>
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
            message: "<span class='bigger-140 green'><i class='fa fa-check-circle'></i> Duyệt kết quả thành công</span>",
            backdrop: true,
            onEscape: true,
            buttons:
            {
                "button" :
                {
                    "label" : "<i class='fa fa-file-text-o' ></i> Danh sách chờ xử lý",
                    "className" : "btn-sm btn-info",
                    "callback": function() {
                        window.location.href = '{site_url()}phongthinghiem/duyetketqua';
                    }
                }
            }
        });
    {/if}
});
</script>
{/block}