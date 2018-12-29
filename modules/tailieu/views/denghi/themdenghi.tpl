{* Extend our master template *}
{extends file="master.tpl"}
{block name="css"}
    <link rel="stylesheet" href="{$assets_path}css/select2.min.css" />
    <link rel="stylesheet" href="{site_url()}assets/css/denghi.css" />
{/block}
{block name=script}
<script src="{$assets_path}js/bootstrap-datepicker.min.js"></script>
<script src="{$assets_path}js/select2.min.js"></script>
<script src="{$assets_path}js/jquery.validate.min.js"></script>
<script type="text/javascript">
    function goBack() {
        window.history.back();
    } 
    function userDuyetDN(tangId){
        var userReceiveSelect = $('#de_nghi_user_receive');
        userReceiveSelect.val(null).trigger('change'); 
        userReceiveSelect.find("option").remove().end();
        userReceiveSelect.find("optgroup").remove().end();
        var option = new Option("--Select--", "", false, false);
        userReceiveSelect.append(option);
        $.ajax({ 
            type: 'GET',
            url: '/tailieu/denghi/getUserQuyTrinhAjax?tangId=' + tangId
        }).then(function (data) {
            var json_data = JSON.parse(data);
            $.each(json_data, function (index, items) {
                var optgroup = $('<optgroup>');
                optgroup.attr('label', index);
                for(i = 0; i < items.length; i++){
                    var item = items[i];
                    var option = new Option(item.lastname + " " + item.firstname, item.id, false, false);
                    optgroup.append(option);
                }
                userReceiveSelect.append(optgroup);
            })
             
        });
    }
    jQuery(function($){
        $('.date-picker').datepicker({
            todayHighlight: true,
        });
        $("#de_nghi_date_start").datepicker('setDate', 'today');
        $('.select2').select2({
            allowClear:true
        })
        .on('change', function(){
            $(this).closest('form').validate().element($(this));
        });
        $('#id-input-file-2').ace_file_input({
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
        $(".edit-tai-lieu").hide();
        //$("#id-input-file-2").hide();
        $("#loai_tai_lieu_id").change(function(){
            if(this.value == 1) {
                $(".file-ben-ngoai").hide();
                $("#id-input-file-2").addClass("ignore");
                var tailieuNB = $('#tai_lieu_id');
                tailieuNB.val(null).trigger('change');
                tailieuNB.find("option").remove().end();
                var option = new Option("--Select--", "", false, false);
                tailieuNB.append(option);
                {foreach from=$taiLieuNB item=tailieu}
                    var option = new Option('{$tailieu["tai_lieu_name"]}', '{$tailieu["tai_lieu_id"]}', false, false);
                    tailieuNB.append(option);
                {/foreach}
            }else{
                $(".file-ben-ngoai").show();
                $("#id-input-file-2").removeClass("ignore");
                var tailieuBN = $('#tai_lieu_id');
                tailieuBN.val(null).trigger('change');
                tailieuBN.find("option").remove().end();
                var option = new Option("--Select--", "", false, false);
                tailieuBN.append(option);
                {foreach from=$taiLieuBN item=tailieu}
                    var option = new Option('{$tailieu["tai_lieu_name"]}', '{$tailieu["tai_lieu_id"]}', false, false);
                    tailieuBN.append(option);
                {/foreach}
            }
            $(".tang-tai-lieu-1").show();
            $(".tang-tai-lieu-1 span.select2").width("548");
            for(var l = 2; l <= {$maxLevel}; l++){
                $(".tang-tai-lieu-" + l).hide();
            }
            var TangTLSelect = $('#tang_tai_lieu_id_1');
            TangTLSelect.val(null).trigger('change');
            TangTLSelect.find("option").remove().end();
            var option = new Option("--Select--", "", false, false);
            TangTLSelect.append(option);
            $.ajax({
                type: 'GET',
                url: '/tailieu/tangtailieu/getTangTaiLieuByLoaiTL?loaiTLId=' + this.value + '&level=1'
            }).then(function (data) {
                var json_data = JSON.parse(data);
                for(i = 0; i < json_data.length; i++){
                    var item = json_data[i];
                    var option = new Option(item.tang_tai_lieu_ten, item.tang_tai_lieu_id, false, false);
                    TangTLSelect.append(option);
                }
            });
        });
        for(var l = 1; l <= {$maxLevel}; l++){
            $("#tang_tai_lieu_id_" + l).change(function(){
                console.log(this.value);
                if(parseInt(this.value) > 0){
                    var nextLevel = parseInt($(this).attr("level")) + 1;
                    $("#tang_tai_lieu_id").val(this.value);
                    $(".tang-tai-lieu-" + nextLevel).show();
                    $(".tang-tai-lieu-" + nextLevel + " span.select2").width("548");
                    var TangTLSelect = $('#tang_tai_lieu_id_' + nextLevel);
                    TangTLSelect.find("option").remove().end();
                    TangTLSelect.val(null).trigger('change');
                    var option = new Option("--Select--", "", false, false);
                    TangTLSelect.append(option);
                    $.ajax({
                        type: 'GET',
                        url: '/tailieu/tangtailieu/getTangTaiLieuByParentId?parentId=' + this.value + '&level=' + nextLevel
                    }).then(function (data) {
                        var json_data = JSON.parse(data);
                        if(json_data.length == 0){
                            $(".tang-tai-lieu-" + nextLevel).hide();
                        }
                        for(i = 0; i < json_data.length; i++){
                            var item = json_data[i];
                            var option = new Option(item.tang_tai_lieu_ten, item.tang_tai_lieu_id, false, false);
                            TangTLSelect.append(option);
                        }
                    });
                    if(nextLevel==2){
                        userDuyetDN(this.value);
                    }
                    
                    var TLSelect = $("#tai_lieu_id");
                    TLSelect.find("option").remove().end();
                    TLSelect.val(null).trigger('change');
                    var option = new Option("--Select--", "", false, false);
                    TLSelect.append(option);
                    $.ajax({
                        type: 'GET',
                        url: '/tailieu/tailieu/getTaiLieuByTangTL?TangTLId=' + this.value
                    }).then(function (data) {
                        var json_data = JSON.parse(data);
                        for(i = 0; i < json_data.length; i++){
                            var item = json_data[i];
                            var option = new Option(item.tai_lieu_name, item.tai_lieu_id, false, false);
                            TLSelect.append(option);
                        }
                    });
                }    
            });
        }
        $('input[type=radio][name=loai_de_nghi_id]').change(function() {
            if (this.value == 1) {
                $(".edit-tai-lieu").addClass("ignore");
                $(".new-tai-lieu").removeClass("ignore");
                $(".edit-tai-lieu select").addClass("ignore");
                $(".new-tai-lieu input").removeClass("ignore");
                $(".new-tai-lieu select").removeClass("ignore");
                $("#id-input-file-2").removeClass("ignore");
                $(".edit-tai-lieu").hide();
            }
            else if (this.value == 2) {
                $(".edit-tai-lieu").show();
                $(".edit-tai-lieu").removeClass("ignore");
                $(".new-tai-lieu").addClass("ignore");
                $(".new-tai-lieu input").addClass("ignore");
                $(".new-tai-lieu select").addClass("ignore");
                $(".edit-tai-lieu select").removeClass("ignore");
                $("#id-input-file-2").addClass("ignore");
            }
        });
        //$("#validation-form").validate().settings.ignore = ':hidden';
        $('#validation-form').validate({
            errorElement: 'div',
            errorClass: 'help-block',
            focusInvalid: false,
            ignore: ".ignore",
            rules: {
                tai_lieu_name: {
                    required: true
                },
                loai_tai_lieu_id: {
                    required: true
                },
                tang_tai_lieu_id: {
                    required: true
                },
                de_nghi_name: {
                    required: true
                },
                de_nghi_content: {
                    required: true
                },
                de_nghi_date_start: {
                    required: true
                },
                de_nghi_date_end: {
                    required: true
                },
                loai_de_nghi_id: {
                    required: true
                },
                tai_lieu_id: {
                    required: true
                },
                de_nghi_file: {
                    required: true
                },
                de_nghi_user_receive: {
                    required: true
                }
            },
            messages: {
                de_nghi_date_start: {
                    required: "Chọn ngày bắt đầu.",
                },
                de_nghi_date_end: {
                    required: "Chọn ngày kết thúc.",
                },
                tai_lieu_name: {
                    required: "Nhập tên tài liệu"
                },
                loai_tai_lieu_id: {
                    required: "Chọn loại tài liệu"
                },
                tang_tai_lieu_id: {
                    required: "Chọn tầng tài liệu"
                },
                de_nghi_name: {
                    required: "Nhập tên đề nghị"
                },
                de_nghi_content: {
                    required: "Nhập nội dung đề nghị"
                },
                loai_de_nghi_id: {
                    required: "Chọn loại đề nghị"
                },
                tai_lieu_id: {
                    required: "Chọn tài liệu để sửa đổi"
                },
                de_nghi_file: {
                    required: "Upload file tài liệu"
                },
                de_nghi_user_receive: {
                    required: "chọn user duyệt đề nghị"
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
    })
</script>
{/block}
{block name=body}
<div class="main-content">
<div class="main-content-inner">
<div class="page-content">
<div class="row">
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        <form class="form-horizontal" role="form" id="validation-form" action="{site_url()}tailieu/denghi/createdenghi" method="post" name="createdenghi" enctype="multipart/form-data" id="createdenghi">            
            <h3 class="header smaller lighter blue">Thông Tin Đề Nghị</h3>
            {if $validate}
            <div class="step-pane error-box">
                <div>
                    <div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert">
                            <i class="ace-icon fa fa-times"></i>
                        </button>
                        <div class="message">Vui lòng kiểm tra thông tin bạn nhập</div>
                    </div>
                </div>
            </div>
            {/if}
            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Tên đề nghị </label>
                <div class="col-sm-6">
                    <div class="input-group" style="width: 100%">
                        <input class="form-control" type="text" id="de_nghi_name" name="de_nghi_name" placeholder="Tên Đề Nghị" class="col-xs-10 col-sm-5" />
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Nội dung đề nghị </label>
                <div class="col-sm-6">
                    <div class="input-group" style="width: 100%">
                        <textarea class="form-control" id="de_nghi_content" name="de_nghi_content" placeholder="Nội Dung Đề Nghị"></textarea>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Ngày bắt đầu duyệt</label>
                <div class="col-sm-6">
                    <div class="input-group">
                        <input class="form-control date-picker" autocomplete="off" id="de_nghi_date_start" name="de_nghi_date_start" type="text" data-date-format="dd-mm-yyyy" />
                        <span class="input-group-addon">
                            <i class="fa fa-calendar bigger-110"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Ngày kết thúc duyệt</label>
                <div class="col-sm-6">
                    <div class="input-group">
                        <input class="form-control date-picker" autocomplete="off" id="de_nghi_date_end" name="de_nghi_date_end" type="text" data-date-format="dd-mm-yyyy" />
                        <span class="input-group-addon">
                            <i class="fa fa-calendar bigger-110"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Loại đề nghị </label>
                <div class="col-sm-4">
                    <div class="radio">
                        <label>
                            <input name="loai_de_nghi_id" type="radio" checked="checked" value="1" class="ace" />
                            <span class="lbl"> Thêm mới tài liệu </span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="radio">
                        <label>
                            <input name="loai_de_nghi_id" type="radio" value="2" class="ace" />
                            <span class="lbl"> Sửa đổi tài liệu </span>
                        </label>
                    </div>
                </div>
            </div>
            <h3 class="header smaller lighter blue">
                Thông Tin Tài Liệu
            </h3>
            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Loại tài liệu </label>
                <div class="col-sm-6">
                    <select class="select2 form-control" id="loai_tai_lieu_id" name="loai_tai_lieu_id" data-placeholder="Chọn loại tài liệu...">
                        <option value=""> --Select-- </option>
                        {foreach from=$loai_tai_lieu key=k item=val}
                            <option value="{$val['loai_tai_lieu_id']}"> {$val['loai_tai_lieu_name']} </option>
                        {/foreach}
                    </select>
                </div>
            </div>
            {for $i=1 to $maxLevel }     
                <div class="form-group ignore tang-tai-lieu-{$i}">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Tầng Tài Liệu Cấp {$i} </label>
                    <div class="col-sm-6">
                        <select class="select2 form-control" id="tang_tai_lieu_id_{$i}" level="{$i}" name="tang_tai_lieu_id_{$i}" data-placeholder="Chọn tầng tài liệu...">

                        </select>
                    </div>
                </div>
            {/for}        
            <div class="form-group edit-tai-lieu">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Chọn tài liệu </label>
                <div class="col-sm-6">
                    <div class="input-group" style="width: 100%">
                        <select class="select2 form-control ignore" id="tai_lieu_id" name="tai_lieu_id" data-placeholder="Chọn tài liệu...">
                            <option value=""> --Select-- </option>
                            {foreach from=$tai_lieus key=k item=val}
                                <option value="{$val['tai_lieu_id']}"> {$val['tai_lieu_name']} </option>
                            {/foreach}    
                        </select>
                    </div>
                </div>
            </div>
            <div class="new-tai-lieu">
                <input type="hidden" id="tang_tai_lieu_id" name="tang_tai_lieu_id" value="">
                <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Tên tài liệu </label>
                    <div class="col-sm-6">
                        <div class="input-group" style="width: 100%"> 
                            <input class="form-control" type="text" id="tai_lieu_name" name="tai_lieu_name" placeholder="Tên tài liệu" class="col-xs-10 col-sm-5" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Tên viết tắt </label>
                    <div class="col-sm-6">
                        <div class="input-group" style="width: 100%"> 
                            <input class="form-control" type="text" id="tai_lieu_shortname" name="tai_lieu_shortname" placeholder="Tên tài liệu viết tắt" class="col-xs-10 col-sm-5" />
                        </div>
                    </div>
                </div>
            </div>                
            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Người duyệt đề nghị </label>
                <div class="col-sm-6">
                    <select class="select2 form-control" id="de_nghi_user_receive" name="de_nghi_user_receive" data-placeholder="Chọn user...">
                        {foreach from=$user_phongban key=phong item=all_user}
                            <optgroup label="{$phong}">
                                {foreach from=$all_user key=k item=val}
                                    <option value="{$val['id']}"> {$val["lastname"]} {$val["firstname"]} </option>
                                {/foreach}
                            </optgroup>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="form-group file-ben-ngoai ignore">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Upload file soạn thảo </label>
                <div class="col-sm-6">
                    <input type="file" class="ignore" name="de_nghi_file" id="id-input-file-2" />
                </div>
            </div>        
            <div class="clearfix form-actions">
                <div class="col-md-offset-3 col-md-9">
                    <button class="btn btn-info" type="submit">
                        <i class="ace-icon fa fa-check bigger-110"></i>
                        Gửi
                    </button>

                    &nbsp; &nbsp; &nbsp;
                    <button class="btn" type="reset" onclick="goBack()">
                        <i class="ace-icon fa fa-undo bigger-110"></i>
                        Hủy
                    </button>
                </div>
            </div>            
        </form>    
    </div>
</div>
</div>
</div>
</div>

{/block}