{* Extend our master template *}
{extends file="master.tpl"}
{block name=css}
<link rel="stylesheet" href="{$assets_path}plugins/colorbox/css1/colorbox.css" />
<link rel="stylesheet" href="{$assets_path}css/chosen.min.css" />
<link rel="stylesheet" href="{$assets_path}css/ace.min.css" />
<link rel="stylesheet" href="{$assets_path}css/bootstrap-datepicker3.min.css" />
<link rel="stylesheet" href="{site_url()}assets/css/jquery-confirm.css" />
{/block}
{block name=script}
<script src="{$assets_path}plugins/colorbox/jquery.colorbox.js"></script>
<script src="{$assets_path}js/chosen.jquery.min.js"></script>
<script src="{$assets_path}js/bootstrap-datepicker.min.js"></script>
<script src="{site_url()}assets/js/jquery-confirm.js"></script>
<script src="{site_url()}assets/js/sweetalert.min.js"></script>
<script type="text/javascript">
function trove() {
    window.history.back();
}
$(document).ready(function () {
    $(".ns").on("click", function () {
        $("#ngaysinh").focus();
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
    })
    $('.chosen-select', this).chosen();
    //Examples of how to assign the Colorbox event to elements
    $(".colorbox_file").colorbox({
        iframe: true, innerWidth: 950, innerHeight: 500,
        onLoad: function () {
            $("#cboxClose").text('X');
        },
        onClosed: function () {}
    });
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
    $("#nhansu").submit(function (e) {
        e.preventDefault();
        var oke = true;
        if ($("#loaihopdong").val() == '0') {
            swal("Cảnh Báo?", "Loại Hợp Đồng Đã Tồn Tại", "warning");
            oke = false;
        } else if ($("#donvi").val() == '0') {
            swal("Cảnh Báo?", "Đơn Vị Đã Tồn Tại", "warning");
            oke = false;
        } else if ($("#chucvu").val() == '0') {
            swal("Cảnh Báo?", "Bạn chưa chọn chức vụ", "warning");
            oke = false;
        }
        var form = $(this);
        var formdata = false;
        formdata = new FormData(form[0]);
        //formdata.append('hopdong', $("#hopdong")[0].files[0], 'chris.jpg');
        if (oke == true) {
            $.confirm({
                title: 'Xác nhận',
                content: 'Xác nhận thêm nhân sự này?',
                icon: 'fa fa-question',
                theme: 'modern',
                closeIcon: true,
                autoClose: 'cancel|10000',
                animation: 'scale',
                type: 'orange',
                buttons: {
                    'Có': {
                        btnClass: 'btn-primary',
                        action: function () {
                            $.ajax({
                                type: "POST",
                                data: formdata,
                                cache       : false,
                                contentType : false,
                                processData : false,
                                url: "{site_url()}nhansu/ajax_add",
                                success: function (data) {
                                    if (data == 200) {
                                        window.location = "{site_url()}nhansu";
                                    }
                                    ;
                                    if (data == 101) {
                                        swal("Thất Bại?", "Tạo Thất Bại", "error");
                                    }
                                    ;
                                    if (data == 102) {
                                        swal("Thất Bại?", "Số Điện Thoại Đã Tồn Tại", "error");
                                    }
                                    ;
                                    if (data == 103) {
                                        swal("Thất Bại?", "Email đã tồn tại", "error");
                                    }
                                    ;
                                }
                            });
                        }
                    },
                    cancel: {
                        text: 'Không',
                        btnClass: 'btn-danger',
                        action: function () {
                            // lets the user close the modal.
                        }
                    }
                }
            });
        }
    });
    $("#email").on('blur', function () {
        check_unique('email');
    });
    $("#phone").on('blur', function () {
        check_unique('phone');
    });
    $("#cmnd").on('blur', function () {
        check_unique('cmnd');
    });
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
});
function check_unique(field) {
    if ($("#"+field).val() != "") {
        $.ajax({
            type: "post",
            data: {
                field: field,
                val: $("#"+field).val()
            },
            url: "{site_url()}nhansu/check_unique",
            success: function (data) {
                if (data == 1) {
                    $("#btnSave").prop("disabled", false);
                } else {
                    $("#btnSave").attr("disabled", "disabled");
                    swal("Thất Bại?", data, "error");
                }
            }
        });
    }
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
                <li class="active">{$languages.url_3}</li>
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
                    <form id="nhansu" class="form-horizontal" role="form" method='post' enctype="multipart/form-data" action=''>
                        <h3 class="header smaller lighter blue" style="margin-top:0px;margin-left: 10%;margin-right:10%">{$languages.themnhansu_title1}</h3>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right">{$languages.hovaten}</label>
                            <div class="col-sm-2">
                                <input class="form-control" autocomplete="off" id="ho" name="ho" placeholder="{$languages.placeholder_ho}" required/>
                            </div>
                            <div class="col-sm-2">
                                <input class="form-control" autocomplete="off" id="ten" name="ten" placeholder="{$languages.placeholder_ten}" required/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right">{$languages.ngaysinh}</label>
                            <div class="col-sm-4">
                                <div class="input-group col-sm-12">
                                    <input required class="form-control date-picker" autocomplete="off" id="ngaysinh" name="ngaysinh" placeholder="{$languages.placeholder_ngaysinh}" data-date-format="dd-mm-yyyy" required="Không Được Để Trống"/>
                                    <span class="input-group-addon ns">
                                        <i class="fa fa-calendar bigger-110"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right">{$languages.email}</label>
                            <div class="col-sm-4">
                                <input required autocomplete="off" class="form-control" id="email" name="email" placeholder="{$languages.placeholder_email}"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right">{$languages.phone}</label>
                            <div class="col-sm-4">
                                <input required autocomplete="off" class="form-control" id="phone" name="phone" placeholder="{$languages.placeholder_phone}"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right">{$languages.cmnd}</label>
                            <div class="col-sm-4">
                                <input required autocomplete="off" class="form-control" id="cmnd" name="cmnd" placeholder="{$languages.placeholder_cmnd}"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right">{$languages.diachi}</label>
                            <div class="col-sm-4">
                                <input required autocomplete="off" class="form-control" id="diachi" name="diachi" placeholder="{$languages.placeholder_diachi}"/>
                            </div>
                        </div>
                        <h3 class="header smaller lighter blue" style="margin-top:0px;margin-left: 10%;margin-right:10%">{$languages.themnhansu_title2}</h3>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-6">{$languages.loaihopdong}</label>
                            <div class="col-sm-4">
                                <select class="chosen-select form-control" id="loaihopdong" name="loaihopdong">
                                    {foreach from=$loaihopdong key=k item=v}
                                        <option value="{$k}">{$v}</option>
                                    {/foreach}
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right">{$languages.notehopdong}</label>
                            <div class="col-sm-4">
                                <input class="form-control" id="notehopdong" autocomplete="off" placeholder="{$languages.placeholder_notehopdong}" name="notehopdong" id="notehopdong" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right">{$languages.ngaybatdau}</label>
                            <div class="col-sm-4">
                                <div class="input-group col-sm-12">
                                    <input class="form-control date-picker" autocomplete="off" id="ngaybatdau" name="ngaybatdau" placeholder="{$languages.placeholder_ngaybatdau}" data-date-format="dd-mm-yyyy" required="Không Được Để Trống"/>
                                    <span class="input-group-addon nbd">
                                        <i class="fa fa-calendar bigger-110"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right">{$languages.ngayketthuc}</label>
                            <div class="col-sm-4">
                                <div class="input-group col-sm-12">
                                    <input class="form-control date-picker" autocomplete="off" id="ngayketthuc" name="ngayketthuc" placeholder="{$languages.placeholder_ngayketthuc}" data-date-format="dd-mm-yyyy" required="Không Được Để Trống"/>
                                    <span class="input-group-addon nkt">
                                        <i class="fa fa-calendar bigger-110"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right">{$languages.donvi}</label>
                            <div class="col-sm-4">
                                <select class="chosen-select form-control" name="donvi" id="donvi">
                                    <option value="0">{$languages.chosen_donvi}</option>
                                    {foreach from=$donvi key=k item=v}
                                        <option value="{$k}">{$v}</option>
                                    {/foreach}
                                </select>
                            </div>
                        </div>  
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right">{$languages.chucvu}</label>
                            <div class="col-sm-4">
                               <select class="chosen-select form-control" name="chucvu" id="chucvu">
                                    <option value="0">{$languages.chosen_chucvu}</option>
                                </select>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label for="example-text-input" class="control-label no-padding-right col-sm-3">{$languages.detail_filehopdong}</label>
                            <div class="col-sm-4">
                                <input type="file" name="hopdong" id="hopdong" />
                            </div>
                        </div>        
                        
                        <div class="form-group">   
                        </div>
                        <div class="clearfix form-actions row">
                            <div class="col-md-offset-3 col-md-9">
                                <button id="btnSave" class="btn btn-xs btn-success" type="submit"><i class="ace-icon fa fa-check bigger-110"></i>{$languages.themnhansu_button_them}</button>
                                <button class="btn btn-xs" type="reset"><i class="ace-icon fa fa-undo bigger-110"></i>{$languages.themnhansu_button_reset}</button>
                                <button class="btn btn-xs btn-danger" type="button" onclick="trove()"><i class="ace-icon fa fa-undo bigger-110"></i>{$languages.themnhansu_button_trove}</button>
                            </div>
                        </div>
                    </form>
                    <!-- PAGE CONTENT ENDS -->
                </div>
            </div>
        </div>
    </div>
</div>
{/block}