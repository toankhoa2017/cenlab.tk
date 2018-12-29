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
    jQuery(function($){
        $('.date-picker').datepicker({
            autoclose: false,
            todayHighlight: true
        });
        $("#de_nghi_date_start").datepicker('setDate', 'today');
        $('.select2').select2({
            allowClear:true
        })
        .on('change', function(){
                $(this).closest('form').validate().element($(this));
        });
        $("#de_nghi_ket_qua_id").change(function(){
            if(this.value != 6){ // chấp nhận soạn thảo
                $("#de_nghi_date_start").removeClass("ignore");
                $("#de_nghi_date_end").removeClass("ignore");
                $(".date-start").show();
                $(".date-end").show();
                $(".user-assign").show();
                if(this.value == 7){
                    $(".date-start label").text("Ngày bắt đầu ban hành");
                    $(".date-end label").text("Ngày kết thúc ban hành");
                    $(".user-assign label").text("Người ban hành");
                    var userReceive = $('#de_nghi_user_receive');
                    userReceive.val(null).trigger('change');
                    userReceive.find("optgroup").remove().end();
                    userReceive.find("option").remove().end();
                    var option = new Option("--Select--", "", false, false);
                    userReceive.append(option);
                    {foreach from=$user_phongban key=key item=all_user}
                        var optgroup = $('<optgroup>');
                        optgroup.attr('label', "{$key}");
                        {foreach from=$all_user key=k item=user}
                            var option = new Option('{$user["lastname"]} {$user["firstname"]}', '{$user["id"]}', false, false);
                            optgroup.append(option);
                        {/foreach}
                        userReceive.append(optgroup);
                    {/foreach}
                }else{
                    $(".date-start label").text("Ngày bắt đầu xem xét bản thảo lại");
                    $(".date-end label").text("Ngày kết thúc xem xét bản thảo lại");
                    $(".user-assign label").text("Người xem xét");
                    var userReceive = $('#de_nghi_user_receive');
                    userReceive.val(null).trigger('change');
                    userReceive.find("optgroup").remove().end();
                    userReceive.find("option").remove().end();
                    var option = new Option("--Select--", "", false, false);
                    userReceive.append(option);
                    {foreach from=$xemxet_user key=key item=all_user}
                        var optgroup = $('<optgroup>');
                        optgroup.attr('label', "{$key}");
                        {foreach from=$all_user key=k item=user}
                            var option = new Option('{$user["lastname"]} {$user["firstname"]}', '{$user["id"]}', false, false);
                            optgroup.append(option);
                        {/foreach}
                        userReceive.append(optgroup);
                    {/foreach}
                }
            }else{
                $("#de_nghi_date_start").addClass("ignore");
                $("#de_nghi_date_end").addClass("ignore");
                $(".date-start").hide();
                $(".date-end").hide();
                $(".user-assign").hide();
            }
        });
        $('#validation-form').validate({
            errorElement: 'div',
            errorClass: 'help-block',
            focusInvalid: false,
            ignore: ".ignore",
            rules: {
                de_nghi_date_start: {
                    required: true
                },
                de_nghi_date_end: {
                    required: true
                },
                de_nghi_ket_qua_id: {
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
                de_nghi_ket_qua_id: {
                    required: "Chọn kết quả phê duyệt.",
                },
                de_nghi_user_receive: {
                    required: "chọn user ban hành"
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
        {include file="./tailieuinfo.tpl" tai_lieu=$tai_lieu}
        <form class="form-horizontal" id="validation-form" role="form" action="{site_url()}tailieu/denghi/createPheDuyet" method="post" name="createdenghi" id="createdenghi">
            <h3 class="header smaller lighter blue">Phê Duyệt</h3>
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
            <input type="hidden" name="de_nghi_id" value="{$de_nghi->de_nghi_id}" />
            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Đề nghị </label>
                <div class="col-sm-9">
                    <input type="text" id="de_nghi_name" name="de_nghi_name" value="{$de_nghi->de_nghi_name}" placeholder="Tên Đề Nghị" class="col-xs-10 col-sm-5" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Nội dung đề nghị </label>
                <div class="col-sm-6">
                    <textarea class="form-control" id="de_nghi_content" name="de_nghi_content" >{$de_nghi->de_nghi_content}</textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Kết quả phê duyệt </label>
                <div class="col-sm-6">
                    <select class="select2 form-control" id="de_nghi_ket_qua_id" name="de_nghi_ket_qua_id" data-placeholder="Chọn kết quả...">
			<option value=""> --Select-- </option>
                        {foreach from=$ket_qua key=k item=val}
                            <option value="{$val['de_nghi_ket_qua_id']}"> {$val['de_nghi_ket_qua_name']} </option>
                        {/foreach}    
                   
                    </select>
                </div>
            </div>    
            <div class="form-group date-start ignore">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Ngày bắt đầu ban hành</label>
                <div class="col-sm-6">
                    <div class="input-group">
                        <input class="form-control date-picker" autocomplete="off" id="de_nghi_date_start" name="de_nghi_date_start" type="text" data-date-format="dd-mm-yyyy" />
                        <span class="input-group-addon">
                            <i class="fa fa-calendar bigger-110"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="form-group date-end ignore">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Ngày kết thúc ban hành</label>
                <div class="col-sm-6">
                    <div class="input-group">
                        <input class="form-control date-picker" autocomplete="off" id="de_nghi_date_end" name="de_nghi_date_end" type="text" data-date-format="dd-mm-yyyy" />
                        <span class="input-group-addon">
                            <i class="fa fa-calendar bigger-110"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="form-group user-assign">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Người ban hành </label>
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