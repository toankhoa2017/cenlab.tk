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
                    <li><a href="{site_url()}phongthinghiem/nhanmau">Danh sách mẫu</a></li>
                    <li class="active">Nhận mẫu</li>
                </ul>
            </div>
            <!--PATH ENDS-->
            <div class="page-content">
                <div class="row">
                    <div class="col-xs-12">
                        <!-- PAGE CONTENT BEGINS -->
                        <h3 class="header smaller lighter blue"><i class="fa fa-list" aria-hidden="true"></i> Nhận mẫu</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="widget-title green smaller">
                                    <i class="fa fa-flask orange"></i> Thông tin mẫu
                                </h4>
                                <div class="profile-user-info profile-user-info-striped" style="width: 100%;">
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Mã số Mẫu </div>
                                        <div class="profile-info-value">
                                            <span class="editable"><strong>{$data['mau_info']['mau_code']}</strong></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Tên mẫu </div>
                                        <div class="profile-info-value">
                                            <span class="editable">{$data['mau_info']['mau_name']}</span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Khối lượng mẫu </div>
                                        <div class="profile-info-value">
                                            <span class="editable">{$data['mau_info']['mau_mass']} ({$data['mau_info']['donvitinh_name']})</span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Mô tả </div>
                                        <div class="profile-info-value">
                                            <span class="editable">{$data['mau_info']['mau_description']}</span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Số nhóm chỉ tiêu </div>
                                        <div class="profile-info-value">
                                            <span class="editable">{$data['mau_info']['total_chitieu']}</span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Ghi chú </div>
                                        <div class="profile-info-value">
                                            <span class="editable">{$data['mau_info']['mau_note']}</span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Ngày nhận mẫu </div>
                                        <div class="profile-info-value">
                                            <span class="editable">{$data['mau_info']['date_create']|date_format:"%d/%m/%Y %H:%M:%S"}</span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Vị trí mẫu </div>
                                        <div class="profile-info-value">
                                            <span class="editable">{$data['mau_info']['mau_vitri']}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h4 class="widget-title green smaller">
                                    <i class="fa fa-flask orange"></i> Phòng thí nghiệm nhận mẫu
                                </h4>
                                <div class="profile-user-info profile-user-info-striped" style="width: 100%;">
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Tình trạng </div>
                                        <div class="profile-info-value">
                                            {if $data['mau_info']['mauptn_approve'] == '1'}
                                                <span class="green"><i class="fa fa-check"></i> Đã nhận mẫu</span>
                                            {elseif $data['mau_info']['mauptn_approve'] == '2'}
                                                <span class="red"><i class="fa fa-times"></i> Đã từ chối mẫu</span>
                                            {else}
                                                Chưa nhận mẫu
                                            {/if}
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Ghi chú </div>
                                        <div class="profile-info-value">
                                            <span class="editable">{$data['mau_info']['mauptn_note']}</span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Ngày xử lý </div>
                                        <div class="profile-info-value">
                                            <span class="editable">{$data['mau_info']['mauptn_createdate']|date_format:"%d/%m/%Y %H:%M:%S"}</span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Người xử lý </div>
                                        <div class="profile-info-value">
                                            <span class="editable">{$data['mau_info']['nhansu']['nhansu_lastname']} {$data['mau_info']['nhansu']['nhansu_firstname']}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tabbable" style="margin-top: 15px;">
                            <ul class="nav nav-tabs" id="myTab">
                                <li class="active">
                                    <a data-toggle="tab" href="#chitieu_info" aria-expanded="true">
                                        <i class="fa fa-flask orange" aria-hidden="true"></i> Thông tin thí nghiệm
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
                                <div id="chitieu_info" class="tab-pane fade active in">
                                    <table id="list-chitieu" class="table table-striped table-bordered table-hover" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>STT</th>
                                                <th>Nhóm chỉ tiêu</th>
                                                <th>Chỉ tiêu</th>
                                                <th>Phương pháp</th>
                                                <th>Kỹ thuật</th>
                                                <th>Ngày trả kết quả</th>
                                                <th>Dịch vụ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {if $data['list_chitieu']}
                                                {foreach from=$data['list_chitieu'] key=index item=chitieu}
                                                <tr>
                                                    <td>{($index + 1)}</td>
                                                    <td>{$chitieu['chitieu_name']}</td>
                                                    <td>
                                                        {if $chitieu['list_chat_info']}
                                                            <ul>
                                                            {foreach from=$chitieu['list_chat_info'] item=chat_info}
                                                                <li {if $chat_info['chat_suco']}class="chat_suco"{/if}>{$chat_info['chat_name']}</li>
                                                            {/foreach}
                                                            </ul>
                                                        {/if}
                                                    </td>
                                                    <td>{$chitieu['phuongphap_name']}</td>
                                                    <td>{$chitieu['kythuat_name']}</td>
                                                    <td>{$chitieu['chitieu_dateend']|date_format:"%d/%m/%Y"}</td>
                                                    <td>{$chitieu['dichvu_name']}</td>
                                                </tr>
                                                {/foreach}
                                            {/if}
                                        </tbody>
                                    </table>
                                    {if !$data['mau_info']['mauptn_approve']}
                                    <form class="form-mauptn" class="form-horizontal" method="POST" action="">
                                        <div class="clearfix form-actions" style="padding: 20px 0;">
                                            <div class="col-md-offset-3 col-md-9">
                                                <button class="btn mauptn-save" type="button" data-value="2">
                                                    <i class="ace-icon fa fa-times bigger-110"></i> Không chấp nhận
                                                </button>
                                                <button class="btn btn-info mauptn-save" type="button" data-value="1">
                                                    <i class="ace-icon fa fa-floppy-o bigger-110"></i> Chấp nhận mẫu
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                    {/if}
                                </div>
                                <div id="suco_info" class="tab-pane fade">
                                    <table class="table table-striped table-bordered table-hover" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>STT</th>
                                                <th>Nội dung</th>
                                                <th>Người tạo</th>
                                                <th>Ngày tạo</th>
                                                <th>Nhóm chỉ tiêu</th>
                                                <th>Chỉ tiêu</th>
                                                <th>Trạng thái</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {if $data['list_suco']}
                                            {foreach from=$data['list_suco'] key=index item=suco_info}
                                                <tr>
                                                    <td rowspan="{count($suco_info['list_suco_chitiet'])}">{($index + 1)}</td>
                                                    <td rowspan="{count($suco_info['list_suco_chitiet'])}">{$suco_info['suco_content']}</td>
                                                    <td rowspan="{count($suco_info['list_suco_chitiet'])}">{$suco_info['nhansu']['nhansu_lastname']} {$suco_info['nhansu']['nhansu_firstname']}</td>
                                                    <td rowspan="{count($suco_info['list_suco_chitiet'])}">{$suco_info['suco_createdate']|date_format:"%d/%m/%Y"}</td>
                                                    <td>{$suco_info['list_suco_chitiet'][0]['chitieu_name']}</td>
                                                    <td>
                                                        {if $suco_info['list_suco_chitiet'][0]['list_chat_info']}
                                                            <ul>
                                                            {foreach from=$suco_info['list_suco_chitiet'][0]['list_chat_info'] item=chat_info}
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
                                                {if count($suco_info['list_suco_chitiet']) > 1}
                                                    {foreach from=$suco_info['list_suco_chitiet'] key=index item=suco_chitiet}
                                                        {if $index > 0}
                                                            <tr>
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
                                                        {/if}
                                                    {/foreach}
                                                {/if}
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
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Nhóm chỉ tiêu</th>
                    <th>Phương pháp</th>
                    <th>Kỹ thuật</th>
                </tr>
            </thead>
            <tbody>
                {if $data['list_chitieu']}
                    {foreach from=$data['list_chitieu'] key=index item=chitieu}
                    <tr>
                        <td>{($index + 1)}</td>
                        <td>{$chitieu['chitieu_name']}</td>
                        <td>{$chitieu['phuongphap_name']}</td>
                        <td>{$chitieu['kythuat_name']}</td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            {if $chitieu['list_chat_info']}
                                {foreach from=$chitieu['list_chat_info'] item=chat_info}
                                    <p>
                                        <label {if $chat_info['chat_suco']}class="chat_suco"{/if}>
                                            <input type="checkbox" class="chitieu_suco" data-mauchitiet="{$chitieu['mauchitiet_id']}" value="{$chat_info['chat_id']}" {if $chat_info['chat_suco']}disabled="disabled"{/if}> {$chat_info['chat_name']}
                                        </label>
                                    </p>
                                {/foreach}
                            {/if}
                        </td>
                    </tr>
                    {/foreach}
                {/if}
            </tbody>
        </table>
        <p><strong>Ghi chú:</strong></p>
        <textarea class="form-control suco_note"></textarea>
    </div>
{/block}
{block name=script}
<script src="{$assets_path}js/bootbox.js"></script>
<script src="{$assets_path}js/jquery.gritter.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('.mauptn-save').on('click', function(){
            var mauptn_approve = $(this).attr('data-value');
            var mptn_title = '<h4><i class="fa fa-exclamation-triangle green"></i> Xác nhận đã nhận mẫu: {$data['mau_info']['mau_code']}</h4>';
            var mptn_content = '<p><strong>Ghi chú</strong></p><p><textarea class="form-control mauptn_note"></textarea></p>';
            if(mauptn_approve === '2'){
                mptn_title = '<h4><i class="fa fa-exclamation-triangle red"></i> Xác nhận không thực hiện thử nghiệm mẫu: {$data['mau_info']['mau_code']}</h4>';
            }else{
                mptn_content += '<p><strong>Mẫu nguyên (khối lượng - {$data['mau_info']['donvitinh_name']}):</strong></p><p><input class="form-control mau_0" type="text"></p>' + 
                                '<p><strong>Mẫu đã xử lý (khối lượng - {$data['mau_info']['donvitinh_name']}):</strong></p><p><input class="form-control mau_1" type="text"></p>';
            }
            bootbox.dialog({
                title: mptn_title,
                message: mptn_content,
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
                            var mau_nguyen = $('.mau_0').val();
                            var mau_xuly = $('.mau_1').val();
                            if(mauptn_approve === '1'){
                                if((typeof mau_nguyen === "undefined" && typeof mau_xuly === "undefined") || (!mau_nguyen && !mau_xuly)){
                                    $.gritter.add({
                                        title: 'Vui lòng nhập khối lượng mẫu nguyên hoặc mẫu đã xử lý!',
                                        time: 3000,
                                        class_name: 'gritter-error'
                                    });
                                    return false;
                                }
                            }
                            $.ajax({
                                type: "POST",
                                url: "{site_url()}phongthinghiem/nhanmau/ajax_nhanmau",
                                dataType: "json",
                                data: {
                                    mau_id: {$data['mau_info']['mau_id']},
                                    mauptn_approve: mauptn_approve,
                                    mauptn_note: $('.mauptn_note').val(),
                                    mau_0: typeof $('.mau_0').val() === "undefined"?0:$('.mau_0').val(),
                                    mau_1: typeof $('.mau_1').val() === "undefined"?0:$('.mau_1').val()
                                },
                                success: function (data) {
                                    if(data['code'] === 0){
                                        $.gritter.add({
                                            title: 'Nhận mẫu thất bại!',
                                            text: data['message'],
                                            time: 3000,
                                            class_name: 'gritter-error'
                                        });
                                    }else{
                                        $('.form-mauptn').remove();
                                        bootbox.dialog({
                                            message: "<span class='bigger-140 green'><i class='fa fa-check-circle'></i> Nhận mẫu thành công</span>",
                                            backdrop: true,
                                            onEscape: function(){
                                                location.reload();
                                            },
                                            buttons:
                                            {
                                                "button" :
                                                {
                                                    "label" : "<i class='fa fa-file-text-o' ></i> Danh sách chờ xử lý",
                                                    "className" : "btn-sm btn-info",
                                                    "callback": function() {
                                                        window.location.href = '{site_url()}phongthinghiem/nhanmau';
                                                    }
                                                }
                                            }
                                        });
                                    }
                                },
                                error: function () {
                                    $.gritter.add({
                                        title: 'Nhận mẫu thất bại!',
                                        time: 30000,
                                        class_name: 'gritter-error'
                                    });
                                }
                            });
                        }
                    }
                }
            });
            return false;
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
                                        hopdong_id: {$data['mau_info']['hopdong_id']},
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
    });
</script>
{/block}