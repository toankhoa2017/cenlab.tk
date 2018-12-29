{* Extend our master template *}
{extends file="master.tpl"}
{block name=css}    
<link rel="stylesheet" href="{$assets_path}plugins/colorbox/css1/colorbox.css" />
<link rel="stylesheet" href="{site_url()}assets/css/jquery-confirm.css" />
<link rel="stylesheet" href="{site_url()}assets/css/stylevth.css" />
<link rel="stylesheet" href="{$assets_path}css/ace.min.css" />
<link rel="stylesheet" href="{site_url()}assets/css/bootstrap-treeview.css" />
<link rel="stylesheet" href="{site_url()}assets/css/dropzone.min.css" />
<link rel="stylesheet" href="{site_url()}assets/css/basic.min.css" />
<link rel="stylesheet" href="{$assets_path}css/bootstrap-datepicker3.min.css" />
<style>
ul.list-group{
    margin-bottom: 0px;
}
.label.arrowed-in-right, .label.arrowed-right{
    top:10px;
}
.modal-lg {
    width: 90%;
}
#chucvu-error{
    padding-left: 160px;
}
</style>
{/block}
{block name=script}
<script src="{$assets_path}plugins/colorbox/jquery.colorbox.js"></script>
<script src="{site_url()}assets/js/bootstrapvalidator.min.js" /></script>
<script src="{$assets_path}js/jquery.validate.min.js"></script>
<script src="{$assets_path}plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="{site_url()}assets/js/sweetalert.min.js"></script>
<script src="{site_url()}assets/js/jquery-confirm.js"></script>
<script src="{$assets_path}js/bootstrap-datepicker.min.js"></script>
<div class="modal fade" id="review" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">{$languages.detail_review_hopdong}</h3>
            </div>
            <div class="modal-body review_body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-xs btn-danger" data-dismiss="modal">{$languages.detail_thoat}</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
{if $privcheck.update}
<div class="modal fade" id="thaydoi" role="dialog">
    <div class="modal-dialog">
        <form id='them_hopdong' name='set_hopdong' method='post' enctype="multipart/form-data" action='{site_url()}nhansu/themhopdong'>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Thêm Hợp Đồng</h3>
            </div>
            <div class="modal-body thaydoi_body">
                
                    <div class="row form-group">
                        <label for="example-text-input" class="col-sm-3">{$languages.detail_loaihopdong}</label>
                        <div class="col-sm-9">
                            {html_options class="form-control" id=loaihopdong name=loaihopdong options=$loaihopdong selected=$hopdong}
                        </div>
                    </div>
                    <div class="row form-group">
                        <label for="example-text-input" class="col-sm-3">{$languages.detail_notehopdong}</label>
                        <div class="col-sm-9">
                            <input class="form-control" id="notehopdong" placeholder="{$languages.detail_placeholder_notehopdong}" name="notehopdong" />
                        </div>
                    </div>
                    <div class="row form-group">
                        <label for="example-text-input" class="col-sm-3">{$languages.detail_nbd}</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input class="form-control date-picker" id="ngaybatdau" placeholder="{$languages.detail_placeholder_nbd}" name="ngaybatdau" type="text" data-date-format="dd-mm-yyyy"/>
                                <span class="input-group-addon nbd">
                                    <i class="fa fa-calendar bigger-110"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row form-group">
                        <label for="example-text-input" class="col-sm-3">{$languages.detail_nkt}</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input class="form-control date-picker" id="ngayketthuc" placeholder="{$languages.detail_placeholder_nkt}" name="ngayketthuc" type="text" data-date-format="dd-mm-yyyy"/>
                                <span class="input-group-addon nkt">
                                    <i class="fa fa-calendar bigger-110"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row form-group">
                        <label for="example-text-input" class="col-sm-3">{$languages.detail_donvi}</label>
                        <div class="col-sm-9">
                            {html_options class="form-control" id=donvi name=donvi options=$donvis selected=$donvi}
                        </div>
                    </div>       
                    <div class="row form-group">
                        <label for="example-text-input" class="col-sm-3">{$languages.detail_chucvu}</label>
                        <div class="col-sm-9">
                            {html_options class="form-control" id=chucvu name=chucvu options=$chucvus selected=$chucvu}
                        </div>
                    </div>
                    
                    <div class="row form-group">
                        <label for="example-text-input" class="col-sm-3">{$languages.detail_filehopdong}</label>
                        <div class="col-sm-9">
                            <input type="file" name="hopdong" id="hopdong" />
                        </div>
                    </div>
                    
                    <input type="hidden" id="nhansu_id" value="{$profile['nhansu_id']}" name="nhansu_id">    
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-xs btn-success" id="oke"><i class="ace-icon fa fa-check"></i> Thêm </button>
                <button type="button" class="btn btn-xs btn-danger" data-dismiss="modal">{$languages.detail_button_thoat}</button>
            </div>
        </div><!-- /.modal-content -->
        </form>
    </div><!-- /.modal-dialog -->
</div>
{/if}
<script type="text/javascript">
listQuyen('{$profile['account_id']}');
listmods('{$profile['account_id']}');
function review(id_nhansu) {
    $.ajax({
        type: "POST",
        data: {
            idnhansu: id_nhansu
        },
        url: "{site_url()}nhansu/review",
        success: function (data) {
            $(".review_body").html('');
            $(".review_body").append(data);
            $("#review").modal("show");
        }
    });
}
function thaydoi(id_nhansu, id_hopdong) {
    $.ajax({
        type: "POST",
        data: {
            idnhansu: id_nhansu,
            idhopdong: id_hopdong
        },
        url: "{site_url()}nhansu/thaydoi",
        success: function (data) {
            $(".thaydoi_body").html('');
            $(".thaydoi_body").append(data);
            $("#thaydoi").modal("show");
        }
    });
}

function themhopdong(id_nhansu){
    $("#thaydoi").modal("show");
}

function thaydoi_submit() {
    var thuchien = true;
    if ($("#loaihopdong").val() == "0") {
        swal("{$languages.canhbao}", "{$languages.detail_loaihopdong_validation}", "warning");
        thuchien = false;
    } else if ($("#donvi").val() == "0") {
        swal("{$languages.canhbao}", "{$languages.detail_donvi_validation}", "warning");
        thuchien = false;
    } else if ($("#chucvu").val() == "0") {
        swal("{$languages.canhbao}", "{$languages.detail_chucvu_validation}", "warning");
        thuchien = false;
    }
    if (thuchien == true) {
        //var myForm = document.getElementById('set_hopdong');
        //var formData = new FormData(myForm);
        $.confirm({
            title: '{$languages.xacnhan}',
            content: '{$languages.xacnhan_bosunghopdong}',
            icon: 'fa fa-question',
            theme: 'modern',
            closeIcon: true,
            autoClose: 'cancel|10000',
            animation: 'scale',
            type: 'orange',
            buttons: {
                '{$languages.co}': {
                    btnClass: 'btn-primary',
                    action: function () {
                        $.ajax({
                            type: "POST",
                            data: {
                                nhansu_id: {$profile['nhansu_id']},
                                note: $("#notehopdong").val(),
                                loaihopdong: $("#loaihopdong").val(),
                                ngaybatdau: $("#ngaybatdau").val(),
                                ngayketthuc: $("#ngayketthuc").val(),
                                donvi: $("#donvi").val(),
                                chucvu: $("#chucvu").val(),
                                hopdong: $("#hopdong").val(),
                            },
                            url: "{site_url()}nhansu/thaydoi_submit",
                            success: function (data) {
                                if (data == 1) {
                                    $("#donvi_ten").text($("#donvi option:selected").text());
                                    $("#chucvu_ten").text($("#chucvu option:selected").text());
                                    swal("{$languages.thanhcong}", "{$languages.bosunghopdong_success}", "success");
                                    $("#thaydoi").modal('hide');
                                    $("#set_hopdong")[0].reset();
                                }
                            }
                        });
                    }
                },
                cancel: {
                    text: '{$languages.khong}',
                    btnClass: 'btn-danger',
                    action: function () {
                        // lets the user close the modal.
                    }
                }
            }
        });
    }
}
$(document).ready(function(){
    $('#hopdong').ace_file_input({
        no_file:'No File1 ...',
        btn_choose:'Choose',
        btn_change:'Change',
        droppable:false,
        onchange:null,
        thumbnail:false //| true | large
        //whitelist:'gif|png|jpg|jpeg'
        //blacklist:'exe|php'
        //onchange:''
        //
    });
    
    $('#them_hopdong').validate({
        errorElement: 'div',
        errorClass: 'help-block',
        focusInvalid: false,
        ignore: "",
        rules: {
            chucvu: {
                required: true
            }
        },
        messages: {
            chucvu: {
                required: "Chọn chức vụ."
            }
        },
        highlight: function (e) {
            $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
        },
        success: function (e) {
            $(e).closest('.form-group').removeClass('has-error');//.addClass('has-info');
            $(e).remove();
        },
        errorPlacement: function (error, element) {
            if(element.is('input[type=checkbox]') || element.is('input[type=radio]')) {
                var controls = element.closest('div[class*="col-"]');
                if(controls.find(':checkbox,:radio').length > 1) controls.append(error);
                else error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
            }
            else if(element.is('.select2')) {
                error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
            }
            else if(element.is('.chosen-select')) {
                error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
            }
            else error.insertAfter(element.parent());
        },
        invalidHandler: function (form) {
        }
    });
});    
$(".nbd").on("click", function () {
    $("#ngaybatdau").focus();
});
$(".nkt").on("click", function () {
    $("#ngayketthuc").focus();
});
$('.date-picker').datepicker({
    autoclose: true,
    todayHighlight: true
}).on('changeDate', function (e) {});
$("#donvi").on("change", function () {
    $.ajax({
        type: "POST",
        data: {
            id_donvi: $("#donvi").val(),
        },
        url: "{site_url()}nhansu/donvi/getchucvus",
        success: function (data) {
            $("#chucvu").html('');
            $("#chucvu").append(data);
            $("#chucvu").trigger("chosen:updated");
        }
    });
});
function _xoa(id) {
    $.confirm({
        title: '{$languages.xacnhan}',
        content: '{$languages.xacnhan_xoa}',
        icon: 'fa fa-question',
        theme: 'modern',
        closeIcon: true,
        autoClose: 'cancel|10000',
        animation: 'scale',
        type: 'orange',
        buttons: {
            '{$languages.co}': {
                btnClass: 'btn-primary',
                action: function () {
                    $.ajax({
                        type: "POST",
                        url: "{site_url()}nhansu/xoahopdong",
                        data: {
                            hopdong_id: id
                        },
                        datatype: "text",
                        success: function (data) {
                            if (data == 1) {
                                location.reload();
                                swal("{$languages.thanhcong}", "{$languages.xoa_success}", "success");
                            }
                        }
                    });
                }
            },
            cancel: {
                text: '{$languages.khong}',
                btnClass: 'btn-danger',
                action: function () {
                }
            }
        }
    });
}
function _setPrimary(hopdong_id, nhansu_id) {
    $.confirm({
        title: '{$languages.xacnhan}',
        content: 'Xác nhận đơn vị chính',
        icon: 'fa fa-question',
        theme: 'modern',
        closeIcon: true,
        autoClose: 'cancel|10000',
        animation: 'scale',
        type: 'orange',
        buttons: {
            '{$languages.co}': {
                btnClass: 'btn-primary',
                action: function () {
                    $.ajax({
                        type: "POST",
                        url: "{site_url()}nhansu/phongBanChinh",
                        data: {
                            hopdong_id: hopdong_id,
                            nhansu_id: nhansu_id
                        },
                        datatype: "text",
                        success: function (data) {
                            if (data == 1) {
                                location.reload();
                                swal("{$languages.thanhcong}", "{$languages.xoa_success}", "success");
                            }
                        }
                    });
                }
            },
            cancel: {
                text: '{$languages.khong}',
                btnClass: 'btn-danger',
                action: function () {
                }
            }
        }
    });
}
</script>
{/block}
{block name=body}
<div class="main-content">
    <div class="main-content-inner">
        <!--PATH BEGINS-->
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li><i class="ace-icon fa fa-home home-icon"></i><a href="#">{$languages.url_1}</a></li>
                <li><a href="{site_url()}nhansu">{$languages.url_2}</a></li>
                <li class="active">{$languages.url_4}</li>
            </ul>
            <div class="nav-search" id="nav-search">
                <form class="form-search">
                    <span class="input-icon"><input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" /><i class="ace-icon fa fa-search nav-search-icon"></i></span>
                </form>
            </div>
        </div>
        <!--PATH ENDS-->
        <div class="page-content">
            <div class="row">
                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->
                    <div class="clearfix">
                        <div class="pull-left tableTools-container"></div>
                    </div>
                    <div class="col-xs-12 col-sm-3 center" style="margin-top:0.5%">
                        <span class="profile-picture"><img class="editable img-responsive" alt="" id="avatar2" src="{$assets_path}images/avatars/profile-pic.jpg" /></span>
                        <div class="space space-4"></div>
                        <a href="#" onclick="review({$profile['nhansu_id']})" class="btn btn-xs btn-block btn-primary"><i class="ace-icon fa fa-envelope-o bigger-110"></i> {$languages.detail_xemchitiet}</a>
                    </div>
                    <div class="col-xs-12 col-sm-9">
                        <div class="widget-header widget-header-small" style="margin-bottom:1%;">
                            <h4 class="widget-title blue smaller">
                                <i class="ace-icon fa fa-user orange"></i>
                                {$profile['nhansu_lastname']} {$profile['nhansu_firstname']}
                            </h4>
                        </div>

                        <div class="profile-user-info profile-user-info-striped">
                            <div class="profile-info-row">
                                <div class="profile-info-name">{$languages.detail_ngaysinh}</div>
                                <div class="profile-info-value"><span class="editable" id="xxx">{date('d-m-Y', strtotime($profile.nhansu_birthday))}</span></div>
                            </div>
                            <div class="profile-info-row">
                                <div class="profile-info-name">{$languages.detail_email}</div>
                                <div class="profile-info-value"><span class="editable" id="xxx">{$profile['nhansu_email']}</span></div>
                            </div>
                            <div class="profile-info-row">
                                <div class="profile-info-name">{$languages.detail_phone}</div>
                                <div class="profile-info-value"><span class="editable" id="xxx">{$profile['nhansu_phone']}</span></div>
                            </div>
                            <div class="profile-info-row">
                                <div class="profile-info-name">{$languages.detail_cmnd}</div>
                                <div class="profile-info-value"><span class="editable" id="xxx">{$profile['nhansu_cmnd']}</span></div>
                            </div>
                            <div class="profile-info-row">
                                <div class="profile-info-name">{$languages.detail_address}</div>
                                <div class="profile-info-value"><span class="editable" id="xxx">{$profile['nhansu_address']}</span></div>
                            </div>
                            <div class="profile-info-row">
                                <div class="profile-info-name">{$languages.detail_matkhau}</div>
                                <div class="profile-info-value" style="position:relative;">
                                    <span class="editable matkhau" id="matkhautam">{if $profile['nhansu_password']}{$profile['nhansu_password']}{else}{$languages.detail_dadoi}{/if}</span>
                                    {if $privcheck.master}<span> | </span><spqn><a href="javascript:resetPWD('{$profile['nhansu_id']}', '{$profile['account_id']}');">{$languages.detail_button_reset}</a></span>{/if}
                                </div>
                            </div>
                            {foreach foreach from=$hopdongs item=hopdong }    
                            <div class="profile-info-row">
                                <div class="profile-info-name" style="width: 160px;">{$languages.detail_donvi} - {$languages.detail_chucvu}</div>
                                <div class="profile-info-value" style="position:relative;">
                                    <span class="editable" id="donvi_ten">{$hopdong['donvi_ten']} - {$hopdong['chucvu_ten']}</span>
                                    {if $privcheck.update}
                                        {if $hopdong['hopdong_id'] != $profile['hopdong_id']}
                                            <button style="z-index:500;right:-32px;" class="btn btn-xs btn-primary" onclick="_xoa({$hopdong['hopdong_id']})">{if $profile['donvi_ten'] == '' }{$languages.detail_bosung}{else}Xóa{/if}</button>
                                            <button style="z-index:500;right:-50px;" class="btn btn-xs btn-primary" onclick="_setPrimary({$hopdong['hopdong_id']}, {$profile['nhansu_id']})">Phòng ban chính</button>
                                        {/if}
                                    {/if}
                                </div>
                            </div>
                            {/foreach}
                        </div>
                        <div style="padding-top: 20px; padding-left: 20px">
                            {if $privcheck.update}<button style="" class="btn btn-xs btn-primary" onclick="themhopdong({$profile['nhansu_id']})">Thêm hợp đồng</button>{/if}
                        </div>
                        <!--Phan quyen tren tai lieu-->
                        <div class="widget-header widget-header-small" style="margin-top:40px;">
                            <h4 class="widget-title blue smaller">
                                <i class="ace-icon fa fa-cog orange"></i>
                                Phân quyền trên tài liệu
                            </h4>
                        </div>
                        <div class="profile-user-info">
                            <div id="listQuyen" style="margin-top:10px;"></div>
                        </div>
                        <!--Phan quyen tren module-->
                        <div class="widget-header widget-header-small">
                            <h4 class="widget-title blue smaller">
                                <i class="ace-icon fa fa-cog orange"></i>
                                {$languages.title_quyen}
                            </h4>
                        </div>
                        <div class="profile-user-info">
                            <form id='frmAdmin' name='frmAdmin' method='post' action=''>
                                <input type='hidden' id='aid' name='aid' value='{$profile['account_id']}'>
                                <div id="listMods" style="margin-top:10px;"></div>
                            </form>
                            <div><button class="btn btn-xs btn-success" onclick="setpermission()"><i class="ace-icon fa fa-check"></i> {$languages.title_button_capnhatquyen}</button></div>
                        </div>
                    </div><!-- /.col -->
                    <!-- PAGE CONTENT ENDS -->
                </div>
            </div>
        </div>
    </div>
</div>
{/block}