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
    </style>
{/block}
{block name=body}
    <div class="main-content">
        <div class="main-content-inner">
            <!--PATH BEGINS-->
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li><i class="ace-icon fa fa-home home-icon"></i><a href="#">Home</a></li>
                    <li><a href="{site_url()}ketqua/danhsachketqua">Danh sách phiếu trả kết quả</a></li>
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
                                    <div class="col-md-6">
                                        <h4 class="header green smaller">
                                            <i class="fa fa-info-circle orange"></i> Thông tin mẫu
                                        </h4>
                                        <table class="table table-striped table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>STT</th>
                                                    <th>Mã số mẫu</th>
                                                    <th>Tên mẫu</th>
                                                    <th>Ngày nhận mẫu</th>
                                                    <th>Ghi chú</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {foreach from=$data['hopdong']['list_mau'] key=index item=mau_info}
                                                    <tr>
                                                        <td>{($index+1)}</td>
                                                        <td>{$mau_info['mau_code']}</td>
                                                        <td>{$mau_info['mau_name']}</td>
                                                        <td>{$mau_info['date_create']|date_format:"%H:%M:%S %d/%m/%Y"}</td>
                                                        <td>{$mau_info['mau_note']}</td>
                                                    </tr>
                                                {/foreach}
                                            </tbody>
                                        </table>
                                    </div>
                                    {if $data['view_approve']}
                                        <div class="col-md-6">
                                            <h4 class="header green smaller">
                                                <i class="fa fa-info-circle orange"></i> Phiếu kết khác quả cùng BN
                                            </h4>
                                            <table class="table table-striped table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>STT</th>
                                                        <th>Ngày tạo</th>
                                                        <th>Người tạo</th>
                                                        <th>Kết quả duyệt</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                {foreach from=$data['ketqua_other_list'] key=index item=ketqua_info}
                                                    <tr>
                                                        <td>{($index+1)}</td>
                                                        <td><a href="{site_url()}ketqua/detail?ketqua={$ketqua_info['ketqua_id']}">{$ketqua_info['create_date']|date_format:"%H:%M:%S %d/%m/%Y"}</a></td>
                                                        <td>{$ketqua_info['user_name']}</td>
                                                        <td>
                                                            {if $ketqua_info['ketqua_approve'] == '1'}
                                                                <span class="label label-sm label-success">Đồng ý</span>
                                                            {elseif $ketqua_info['ketqua_approve'] == '2'}
                                                                <span class="label label-sm label-danger">Không đồng ý</span>
                                                            {else}
                                                                <span class="label label-sm label-warning">Đang chờ duyệt</span>
                                                            {/if}
                                                        </td>
                                                    </tr>
                                                {/foreach}
                                                </tbody>
                                            </table>
                                        </div>
                                    {/if}
                                </div>
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
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <p style="margin: 0; padding: 8px 0 0 0;">
                                            <i class="fa fa-download" aria-hidden="true"></i> Download kết quả: 
                                            <a href="{$data['file_download_word']}" target="_blank">
                                                <i class="fa fa-file-word-o" aria-hidden="true"></i> Word file
                                            </a>
                                             | 
                                            <a href="{$data['file_download_pdf']}" target="_blank">
                                                <i class="fa fa-file-pdf-o" aria-hidden="true"></i> Pdf file
                                            </a>
                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="{if $data['view_approve']}col-md-8{else}col-md-12{/if}">
                                        <h4 class="header green smaller">
                                            <i class="fa fa-info-circle orange"></i> Lịch sử duyệt
                                        </h4>
                                        {if $data['list_ketqua_duyet']}
                                            <table class="table table-striped table-bordered table-hover list_chitieu" style="margin: 0;">
                                                <thead>
                                                    <tr>
                                                        <th>STT</th>
                                                        <th>Người duyệt</th>
                                                        <th>Ngày duyệt</th>
                                                        <th>Kết quả</th>
                                                        <th>Ghi chú</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {foreach from=$data['list_ketqua_duyet'] key=index item=ketqua_duyet}
                                                    <tr>
                                                        <td>{($index + 1)}</td>
                                                        <td>{$ketqua_duyet['user_receive_name']}</td>
                                                        <td>{$ketqua_duyet['update_date']|date_format:"%H:%M:%S %d/%m/%Y"}</td>
                                                        <td>
                                                            {if $ketqua_duyet['duyet_result'] == '0'}
                                                                <span class="label label-sm label-warning">Đang chờ duyệt</span>
                                                            {elseif $ketqua_duyet['duyet_result'] == '1'}
                                                                <span class="label label-sm label-success">Đồng ý</span>
                                                            {else}
                                                                <span class="label label-sm label-danger">Không đồng ý</span>
                                                            {/if}
                                                        </td>
                                                        <td>{$ketqua_duyet['duyet_note']}</td>
                                                    </tr>
                                                    {/foreach}
                                                </tbody>
                                            </table>
                                        {else}
                                            <div class="alert alert-danger">
                                                <button type="button" class="close" data-dismiss="alert">
                                                    <i class="ace-icon fa fa-times"></i>
                                                </button>
                                                <i class="ace-icon fa fa-times"></i> Phiếu kết quả chưa được duyệt
                                            </div>
                                        {/if}
                                    </div>
                                    {if $data['view_approve']}
                                        <div class="col-md-4">
                                            <h4 class="header green smaller">
                                                <i class="fa fa-info-circle orange"></i> Duyệt kết quả
                                            </h4>
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <button class="btn ketqua-approve" type="button" value="2">
                                                        <i class="ace-icon fa fa-times bigger-110"></i> Không chấp nhận
                                                    </button>
                                                    <button class="btn btn-info ketqua-approve" type="button" value="1">
                                                        <i class="ace-icon fa fa-check bigger-110"></i> Chấp nhận
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    {/if}
                                </div>
                            {else}
                                <div class="alert alert-danger">
                                    <strong>Lỗi!</strong> Phiếu kết quả không tồn tại.
                                </div>
                            {/if}
                        {/if}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="hidden" id="ketqua_approve_accept">
        <div class="row">
            <div class="col-xs-12">
                <form action="" method="post">
                    <input type="hidden" name="approve_result" value="1">
                    <div class="form-group">
                        <label class="control-label" for="duyet_note">Ghi chú</label>
                        <textarea id="duyet_note" class="form-control" name="duyet_note"></textarea>
                    </div>
                    {if $data['user_duyet']}
                        <div class="form-group">
                            <label class="control-label" for="user_duyet">Người duyệt tiếp theo</label>
                            <select id="user_duyet" class="form-control" name="user_duyet">
                                {foreach from=$data['user_duyet'] item=user}
                                    <option value="{$user['id']}">{$user['name']}</option>
                                {/foreach}
                            </select>
                        </div>
                    {/if}
                </form>
            </div>
        </div>
    </div>
    <div class="hidden" id="ketqua_approve_denied">
        <div class="ketqua_approve_denied">
            <form action="" method="post">
                <input type="hidden" name="approve_result" value="2">
                {if !$data['duyet_latest']}
                    <table class="table table-striped table-bordered table-hover" width="100%">
                        <thead>
                            <tr>
                                <th>Loại phản hồi</th>
                                <th>Ghi chú</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <label>
                                        <input type="checkbox" class="ace phanhoi_type phanhoi_type_1" name="phanhoi_type_1" value="1">
                                        <span class="lbl"> Thay đổi thông tin hợp đồng</span>
                                    </label>
                                </td>
                                <td><textarea class="form-control approve_content_1" name="approve_content_1"></textarea></td>
                            </tr>
                            <tr>
                                <td>
                                    <label>
                                        <input type="checkbox" class="ace phanhoi_type phanhoi_type_2" name="phanhoi_type_2" value="2">
                                        <span class="lbl"> Thay đổi thông tin thí nghiệm</span>
                                    </label>
                                </td>
                                <td><textarea class="form-control approve_content_2" name="approve_content_2"></textarea></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="mau_chitieu" style="display: none;">
                        <label for=""><strong>Chỉ tiêu cần thay đổi</strong></label>
                        <table class="table table-striped table-bordered table-hover" width="100%">
                            <thead>
                                <tr>
                                    <th class="center"></th>
                                    <th>Nhóm chỉ tiêu</th>
                                    <th>Chỉ tiêu</th>
                                </tr>
                            </thead>
                            <tbody>
                                {foreach from=$data['hopdong']['list_mau'] item=mau}
                                    <tr>
                                        <td colspan="3"><strong>Mã số mẫu: </strong>{$mau['mau_code']}</td>
                                    </tr>
                                    {foreach from=$mau['list_chitieu_info'] key=index item=chitieu}
                                        <tr>
                                            <td class="center">
                                                <label class="pos-rel">
                                                    <input type="checkbox" class="ace chitieu_ketqua" name="chitieu_ketqua[]" value="{$chitieu['mauketqua_id']}">
                                                    <span class="lbl"></span>
                                                </label>
                                            </td>
                                            <td>{$chitieu['chitieu_name']}</td>
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
                {else}
                    <div class="form-group">
                        <label class="control-label" for="duyet_note">Ghi chú</label>
                        <textarea id="duyet_note" class="form-control" name="duyet_note"></textarea>
                    </div>
                {/if}
            </form>
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
            $(this).parents('.ketqua_approve_denied').find('.mau_chitieu').show();
        }else{
            $(this).parents('.ketqua_approve_denied').find('.mau_chitieu').hide();
        }
        return false;
    });
    $('.ketqua-approve').on('click', function(){
        var ketqua_approve = $(this).attr('value');
        var title = ketqua_approve === '2' ? 'Không đồng ý với kết quả' : 'Xác nhận chấp nhận kết quả';
        var content = ketqua_approve === '2' ? $('#ketqua_approve_denied').html() : $('#ketqua_approve_accept').html();
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
                        {if !$data['duyet_latest']}
                            if(ketqua_approve === '2'){
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
                                if($.isEmptyObject(phanhoi_type)){
                                    $.gritter.add({
                                        title: 'Vui lòng chọn loại phản hồi',
                                        time: 3000,
                                        class_name: 'gritter-error'
                                    });
                                    return false;
                                }else if($.inArray('2', phanhoi_type) !== -1 && $.isEmptyObject(chitieu_select)){
                                    $.gritter.add({
                                        title: 'Vui lòng chọn chỉ tiêu',
                                        time: 3000,
                                        class_name: 'gritter-error'
                                    });
                                    return false;
                                }
                            }
                        {/if}
                        $(this).find('form').submit();
                    }
                }
            }
        });
        return false;
    });
    {if $data['active_result']}
        $.gritter.add({
            title: {if $data['active_result'] == '1'}'Tạo phiếu trả kết quả thành công!'{else}'Duyệt phiếu trả kết quả thành công!'{/if},
            time: 3000,
            class_name: 'gritter-success'
        });
    {/if}
});
</script>
{/block}