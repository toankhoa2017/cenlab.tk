{* Extend our master template *}
{extends file="master.tpl"}
{block name=css}
    <link rel="stylesheet" href="{$assets_path}css/jquery.gritter.min.css">
    <style type="text/css">
        .profile-info-name{
            width: 200px;
        }
        #gritter-notice-wrapper{
            top: 50px;
        }
        .modal-open #gritter-notice-wrapper{
            right: 37px;
        }
        .gritter-close {
            left: auto;
            right: 4px;
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
                    <li><a href="{site_url()}ketqua/phanhoi">Phản hồi</a></li>
                    <li class="active">Duyệt phiếu phản hồi</li>
                </ul>
            </div>
            <!--PATH ENDS-->
            <div class="page-content">
                <div class="row">
                    <div class="col-xs-12">
                        <!-- PAGE CONTENT BEGINS -->
                        <h3 class="header smaller lighter blue"><i class="fa fa-list" aria-hidden="true"></i> Duyệt phiếu phản hồi</h3>
                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="widget-title green smaller">
                                    <i class="fa fa-flask orange"></i> Thông tin phiếu phản hồi
                                </h4>
                                <div class="profile-user-info profile-user-info-striped" style="width: 100%;">
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Mã hợp đồng </div>
                                        <div class="profile-info-value">
                                            <span class="editable"><strong>{$data['phanhoi']['hopdong_code']}</strong></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Nội dung </div>
                                        <div class="profile-info-value">
                                            <span class="editable"><strong>{$data['phanhoi']['phanhoi_content']}</strong></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> File upload </div>
                                        <div class="profile-info-value">
                                            <span class="editable">
                                                {if $data['phanhoi']['phanhoi_file_url']}
                                                    <a href="{$data['phanhoi']['phanhoi_file_url']}" target="_blank">{$data['phanhoi']['phanhoi_file']}</a>
                                                {/if}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Người tạo </div>
                                        <div class="profile-info-value">
                                            <span class="editable">{$data['phanhoi']['contact_info']['contact_fullname']}</span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Ngày tạo </div>
                                        <div class="profile-info-value">
                                            <span class="editable">{$data['phanhoi']['phanhoi_date']|date_format:"%d/%m/%Y %H:%M:%S"}</span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Phiếu kết quả </div>
                                        <div class="profile-info-value"><a href="{site_url()}ketqua/detail?ketqua={$data['phanhoi']['ketqua_id']}" target="_blank">Xem chi tiết</a></div>
                                    </div>
                                </div>
                                {if $data['phanhoi']['phanhoi_approve'] == 0}
                                <div class="clearfix form-actions" style="padding: 20px 0;">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button class="btn phanhoi_approve" type="submit" name="phanhoi_approve" value="2">
                                                <i class="ace-icon fa fa-times bigger-110"></i> Không đồng ý
                                        </button>
                                        <button class="btn btn-info phanhoi_approve" type="submit" name="phanhoi_approve" value="1">
                                                <i class="ace-icon fa fa-check bigger-110"></i> Đồng ý
                                        </button>
                                    </div>
                                </div>
                                {/if}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="hidden" id="phanhoi_approve_accept">
        <div class="phanhoi_approve_accept">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for=""><strong>Loại phản hồi</strong></label>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace phanhoi_type phanhoi_type_1" value="1">
                                <span class="lbl"> Thay đổi thông tin hợp đồng</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace phanhoi_type phanhoi_type_2" value="2">
                                <span class="lbl"> Thay đổi thông tin thí nghiệm</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for=""><strong>Ghi chú</strong></label>
                        <textarea class="form-control approve_content"></textarea>
                    </div>
                </div>
            </div>
            <div class="mau_chitieu" style="display: none;">
                <label for=""><strong>Chỉ tiêu cần thay đổi</strong></label>
                <table class="table table-striped table-bordered table-hover" width="100%">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Nhóm chỉ tiêu</th>
                            <th>Chỉ tiêu</th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach from=$data['list_mau'] item=mau}
                            <tr>
                                <td colspan="3"><strong>Mã số mẫu: </strong>{$mau['mau_code']}</td>
                            </tr>
                            {foreach from=$mau['list_chitieu_info'] key=index item=chitieu}
                                <tr>
                                    <td>{($index+1)}</td>
                                    <td>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" class="ace chitieu_ketqua" value="{$chitieu['mauketqua_id']}">
                                                <span class="lbl"> {$chitieu['chitieu_name']}</span>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <ul>
                                            {foreach from=$chitieu['list_chat_info'] item=chat}
                                                <li>{$chat['chat_name']} (KQ: {$chitieu['list_ketqua'][$chat['chat_id']]})</li>
                                            {/foreach}
                                        </ul>
                                    </td>
                                </tr>
                            {/foreach}
                        {/foreach}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
{/block}
{block name=script}
<script src="{$assets_path}js/bootbox.js"></script>
<script src="{$assets_path}js/jquery.gritter.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    $('body').on('change', '.phanhoi_type_2',function(){
        if($(this).is(':checked')){
            $(this).parents('.phanhoi_approve_accept').find('.mau_chitieu').show();
        }else{
            $(this).parents('.phanhoi_approve_accept').find('.mau_chitieu').hide();
        }
        return false;
    });
    $('.phanhoi_approve').on('click', function(){
        var phanhoi_approve = $(this).attr('value');
        var title = phanhoi_approve === '2' ? 'Không đồng ý với phản hồi của Khách hàng' : 'Xác nhận phản hồi của Khách hàng';
        var content = phanhoi_approve === '2' ? '<p><strong>Ghi chú</strong></p><textarea class="form-control approve_content"></textarea>' : $('#phanhoi_approve_accept').html();
        bootbox.dialog({
            title: title,
            message: content,
            size: "large",
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
                        var approve_content = $(this).find('.approve_content').val();
                        var phanhoi_type = [];
                        $(this).find('.phanhoi_type').each(function(){
                            if($(this).prop('checked')){
                                phanhoi_type.push($(this).attr('value'));
                            }
                        });
                        var chitieu_select = [];
                        $(this).find('.chitieu_ketqua').each(function(){
                            if($(this).prop('checked')){
                                chitieu_select.push($(this).attr('value'));
                            }
                        });
                        if(phanhoi_approve === '1' && $.isEmptyObject(phanhoi_type)){
                            $.gritter.add({
                                title: 'Vui lòng chọn loại phản hồi',
                                time: 3000,
                                class_name: 'gritter-error'
                            });
                            return false;
                        }else if(phanhoi_approve === '1' && $.inArray('2', phanhoi_type) !== -1 && $.isEmptyObject(chitieu_select)){
                            $.gritter.add({
                                title: 'Vui lòng chọn chỉ tiêu',
                                time: 3000,
                                class_name: 'gritter-error'
                            });
                            return false;
                        }else{
                            $.ajax({
                                type: "POST",
                                url: "{site_url()}ketqua/phanhoi/ajax_approve",
                                dataType: "json",
                                data: {
                                    phanhoi_id: {$data['phanhoi']['phanhoi_id']},
                                    phanhoi_approve: phanhoi_approve,
                                    phanhoi_type: phanhoi_type,
                                    approve_content: approve_content,
                                    chitieu_select: chitieu_select
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
                                        alert('Duyệt thành công');
                                        window.location.href = '{site_url()}ketqua/phanhoi';
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