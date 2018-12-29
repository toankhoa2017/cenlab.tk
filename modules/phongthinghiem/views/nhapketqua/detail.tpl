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
                    <li><a href="{site_url()}phongthinghiem/nhapketqua">Danh sách mẫu</a></li>
                    <li class="active">Kết quả thí nghiệm</li>
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
                        <div class="tabbable" style="margin-top: 15px;">
                            <ul class="nav nav-tabs" id="myTab">
                                <li class="active">
                                    <a data-toggle="tab" href="#nhapketqua_info" aria-expanded="true">
                                        <i class="fa fa-file-text orange" aria-hidden="true"></i> Nhập kết quả
                                    </a>
                                </li>
                                <li class="">
                                    <a data-toggle="tab" href="#suco_info" aria-expanded="false">
                                        <i class="fa fa-file-text orange" aria-hidden="true"></i> Phiếu báo sự cố
                                        <span class="badge badge-danger">{if $data['list_suco']}{count($data['list_suco'])}{else}0{/if}</span>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div id="nhapketqua_info" class="tab-pane fade active in">
                                    <form id="form-result" class="form-horizontal" method="POST" action="">
                                        <div class="row">
                                            <div class="col-xs-12" style="margin-bottom: 15px;">
                                                <div class="radio">
                                                    <label>
                                                        <input name="type" type="radio" class="ace select-type" value="1" checked="checked">
                                                        <span class="lbl"> Hóa</span>
                                                    </label>
                                                    <label style="margin-left: 15px;">
                                                        <input name="type" type="radio" class="ace select-type" value="2">
                                                        <span class="lbl"> Vi sinh</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
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
                                            {assign var="last_mauketqua" value=end($data['package']['mauketqua_list'])}
                                            {if !$data['package']['mauketqua_list'] || ($last_mauketqua && $last_mauketqua['mauketqua_approve'] == '2')}
                                                <div class="col-xs-12">
                                                    <div class="profile-activity clearfix">
                                                        <div class="col-md-1 no-padding"></div>
                                                        <div class="col-md-9 no-padding">
                                                            {foreach from=$data['package']['list_chat'] key=index item=chat}
                                                                <div class="col-md-4 no-padding" style="margin-bottom: 5px;">
                                                                    <div class="form-group" style="margin: 0;">
                                                                        <label class="col-sm-5 control-label no-padding {if $chat['chat_suco']}chat_suco{/if}" for="chat-{$chat['chat_id']}"> 
                                                                            <strong>{$chat['chat_name']}</strong> 
                                                                        </label>
                                                                        <div class="col-sm-7">
                                                                            <input type="text" id="chat-{$chat['chat_id']}" name="base[{$chat['chat_id']}]" class="col-xs-10 col-sm-6 result-e" {if $chat['chat_suco']}disabled="disabled"{/if}>
                                                                            <span class="include_exponent" style="display: none;">
                                                                                <span style="display: block; float: left; padding: 7px 5px;">x</span>
                                                                                <input type="text" class="col-xs-10 col-sm-4" name="exponent[{$chat['chat_id']}]" value="10^6" {if $chat['chat_suco']}data-suco="1"{/if}>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            {/foreach}
                                                        </div>
                                                        <div class="col-md-2">
                                                            <strong style="text-decoration: underline;">Ghi chú: </strong>
                                                            <textarea class="form-control" name="mauketqua_ghichu"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            {/if}
                                        </div>
                                        {if $data['nhapketqua_accept']}
                                            {if !$data['package']['mauketqua_list'] || ($last_mauketqua && $last_mauketqua['mauketqua_approve'] == '2')}
                                            <div class="clearfix form-actions" style="padding: 20px 0;">
                                                <div class="col-md-offset-3 col-md-9">
                                                    <a href="{site_url()}phongthinghiem/nhapketqua" class="btn">
                                                        <i class="ace-icon fa fa-times bigger-110"></i> Hủy
                                                    </a>
                                                    <button class="btn btn-info result-save" type="submit" value="save">
                                                            <i class="ace-icon fa fa-floppy-o bigger-110"></i> Lưu kết quả
                                                    </button>
                                                </div>
                                            </div>
                                            {/if}
                                        {else}
                                            <div class="alert alert-warning">
                                                <button type="button" class="close" data-dismiss="alert">
                                                    <i class="ace-icon fa fa-times"></i>
                                                </button>
                                                <strong>Sự cố!</strong> Phiếu báo sự cố đang được giải quyết, vui lòng quay lại sau.
                                            </div>
                                        {/if}
                                    </form>
                                </div>
                                <div id="suco_info" class="tab-pane fade">
                                    <table class="table table-striped table-bordered table-hover" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>STT</th>
                                                <th>Nội dung</th>
                                                <th>Người tạo</th>
                                                <th>Ngày tạo</th>
                                                <th>Chỉ tiêu</th>
                                                <th>Trạng thái</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {if $data['list_suco']}
                                            {foreach from=$data['list_suco'] key=index item=suco_info}
                                                <tr>
                                                    <td>{($index + 1)}</td>
                                                    <td>{$suco_info['suco_content']}</td>
                                                    <td>{$suco_info['nhansu']['nhansu_lastname']} {$suco_info['nhansu']['nhansu_firstname']}</td>
                                                    <td>{$suco_info['suco_createdate']|date_format:"%d/%m/%Y"}</td>
                                                    <td>
                                                        {if $suco_info['list_chat_info']}
                                                            <ul>
                                                            {foreach from=$suco_info['list_chat_info'] item=chat_info}
                                                                <li>{$chat_info['chat_name']}</li>
                                                            {/foreach}
                                                            </ul>
                                                        {/if}
                                                    </td>
                                                    <td>
                                                        <span class="{$suco_info['suco_approve_info']['class']}">
                                                            {$suco_info['suco_approve_info']['icon']} {$suco_info['suco_approve_info']['label']}
                                                        </span>
                                                    </td>
                                                </tr>
                                            {/foreach}
                                            {/if}
                                        </tbody>
                                    </table>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button class="btn btn-xs btn-primary btn-hopdong-suco" type="button">+ Thêm phiếu</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                                    
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="list_chitieu" style="display: none;">
        <p><strong>Chọn chỉ tiêu:</strong></p>
        <p>
            {if $data['package']['list_chat']}
                {foreach from=$data['package']['list_chat'] item=chat_info}
                    <p>
                        <label {if $chat_info['chat_suco']}class="chat_suco"{/if}>
                            <input type="checkbox" class="chitieu_suco" data-mauchitiet="{$data['package']['mauchitiet_id']}" value="{$chat_info['chat_id']}" {if $chat_info['chat_suco']}disabled="disabled"{/if}> {$chat_info['chat_name']}
                        </label>
                    </p>
                {/foreach}
            {/if}
        </p>
        <p><strong>Ghi chú:</strong></p>
        <textarea class="form-control suco_note"></textarea>
    </div>
{/block}
{block name=script}
<script src="{$assets_path}js/bootbox.js"></script>
<script src="{$assets_path}js/jquery.gritter.min.js"></script>
<script type="text/javascript">
function change_type(){
    if($('.select-type:checked').val() === '1'){
        $('.include_exponent input').prop('disabled', true);
        $('.include_exponent').hide();
    }else{
        $('.include_exponent input').each(function(){
            if($(this).attr('data-suco') !== '1'){
                $(this).prop('disabled', false);
            }
        });
        $('.include_exponent').show();
    }
}
$(document).ready(function(){
    $('[data-rel=tooltip]').tooltip();
    change_type();
    $('.select-type').change(function(){
        change_type();
    });
    $('.result-save').on('click', function(){
        var error = '';
        $('.result-e').each(function(){
            if($(this).val() === '' && !$(this).prop('disabled')){
                error = true;
                $(this).parents('.form-group').addClass('has-error');
            }
        });
        if(error){
            bootbox.dialog({
                message: "<span class='bigger-110'>Vui lòng điền đầy đủ thông tin</span>",
                backdrop: true,
                onEscape: true,
                buttons:
                {
                    "danger" :
                    {
                        "label" : "Ok",
                        "className" : "btn-sm btn-danger",
                        "callback": function() {
                                //Example.show("uh oh, look out!");
                        }
                    }
                }
            });
            return false;
        }else{
            bootbox.dialog({
                message: "<span class='bigger-110'><i class='fa fa-exclamation-triangle orange'></i> Xác nhận lưu kết quả</span>",
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
                        "label" : "<i class='ace-icon fa fa-check'></i> Chấp nhận",
                        "className" : "btn-sm btn-primary",
                        "callback": function() {
                            $('#form-result').trigger('submit');
                        }
                    }
                }
            });
            return false;
        }
    });
    $('.btn-hopdong-suco').on('click', function(){
        bootbox.dialog({
            size: 'large',
            title: 'Thêm phiếu báo sự cố',
            message: $('.list_chitieu').html(),
            backdrop: true,
            onEscape: true,
            buttons:
            {
                "button" :
                {
                    "label" : "<i class='ace-icon fa fa-times bigger-110'></i> Đóng",
                    "className" : "btn-sm"
                },
                "click" :
                {
                    "label" : "<i class='ace-icon fa fa-check'></i> Xác nhận",
                    "className" : "btn-sm btn-primary",
                    "callback": function() {
                        var chitieu_suco = { };
                        $(this).find('.chitieu_suco').each(function(){
                            if($(this).prop('checked')){
                                if(typeof chitieu_suco[$(this).attr('data-mauchitiet')] === 'undefined'){
                                    chitieu_suco[$(this).attr('data-mauchitiet')] = [$(this).val()];
                                }else{
                                    chitieu_suco[$(this).attr('data-mauchitiet')].push($(this).val());
                                }
                            }
                        });
                        if($.isEmptyObject(chitieu_suco)){
                            $.gritter.add({
                                title: 'Vui lòng chọn chỉ tiêu cần báo sự cố',
                                time: 3000,
                                class_name: 'gritter-error'
                            });
                            return false;
                        }else{
                            $.ajax({
                                type: "POST",
                                url: "{site_url()}phongthinghiem/nhanmau/ajax_suso",
                                dataType: "json",
                                data: {
                                    hopdong_id: {$data['package']['hopdong_id']},
                                    chitieu_suco: chitieu_suco,
                                    suco_note: $(this).find('.suco_note').val()
                                },
                                success: function (data) {
                                    if(data['code'] === 0){
                                        $.gritter.add({
                                            title: 'Thêm phiếu báo sự cố thất bại!',
                                            text: data['message'],
                                            time: 3000,
                                            class_name: 'gritter-error'
                                        });
                                    }else{
                                        $.gritter.add({
                                            title: 'Thêm phiếu báo sự cố thành công!',
                                            time: 3000,
                                            class_name: 'gritter-success'
                                        });
                                        $('.form-mauptn').remove();
                                    }
                                },
                                error: function () {
                                    $.gritter.add({
                                        title: 'Tạo phiếu báo sự cố thất bại!',
                                        time: 30000,
                                        class_name: 'gritter-error'
                                    });
                                }
                            });
                        }
                    }
                }
            }
        });
        return false;
    });
    
    {if $data['result']}
        bootbox.dialog({
            message: "<span class='bigger-140 green'><i class='fa fa-check-circle'></i> Cập nhật kết quả thành công</span>",
            backdrop: true,
            onEscape: true,
            buttons:
            {
                "button" :
                {
                    "label" : "<i class='fa fa-file-text-o' ></i> Danh sách chờ xử lý",
                    "className" : "btn-sm btn-info",
                    "callback": function() {
                        window.location.href = '{site_url()}phongthinghiem/nhapketqua';
                    }
                }
            }
        });
    {/if}
});
</script>
{/block}