{* Extend our master template *}
{extends file="master.tpl"}
{block name="css"}
    <link rel="stylesheet" href="{site_url()}assets/css/denghi.css" />
{/block}
{block name=script}
<script src="{$assets_path}js/bootstrap-datepicker.min.js"></script>
<script src="{$assets_path}js/jquery.validate.min.js"></script>
<script type="text/javascript">
    function goBack() {
        window.history.back();
    }
    jQuery(function($){
        $('.date-picker').datepicker({
            todayHighlight: true,
        });
        $("#de_nghi_date_start").datepicker('setDate', 'today');
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
        $("#previous_back").click(function(){
            parent.history.back();
            return false;
        });
        $('#validation-form').validate({
            errorElement: 'div',
            errorClass: 'help-block',
            focusInvalid: false,
            ignore: "",
            rules: {
                de_nghi_date_start: {
                    required: true
                },
                de_nghi_date_end: {
                    required: true
                },
                de_nghi_file: {
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
                de_nghi_file: {
                    required: "Upload file soạn thảo.",
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
            /*submitHandler: function (form) {
                let date_start = $("input#de_nghi_date_start").val();
                let date_end = $("input#de_nghi_date_end").val();
                let file_soan_thao = $("input#id-input-file-2").val();
                $.post( "{site_url()}tailieu/denghi/createsoanthao", 
                { 
                    de_nghi_id: "{$de_nghi->de_nghi_id}", 
                    de_nghi_name: "{$de_nghi->de_nghi_name}",
                    de_nghi_content: "{$de_nghi->de_nghi_content}",
                    de_nghi_date_start: date_start,
                    de_nghi_date_end: date_end, 
                    de_nghi_file: file_soan_thao,
                    fileElementId: 'de_nghi_file',
                    dataType: 'json',
                    secureuri: false,
                })
                .done(function( data ) {
                  alert( "Data Loaded: " + data );
                });
            },*/
            invalidHandler: function (form) {
            }
        });
    })
</script>
{/block}
{block name=body}
{nocache}    
<div class="main-content">
<div class="main-content-inner">
<div class="page-content">
<div class="row">
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        {include file="./tailieuinfo.tpl" tai_lieu=$tai_lieu}
        <form class="form-horizontal" role="form" id="validation-form" action="{site_url()}tailieu/denghi/createsoanthao" method="post" enctype="multipart/form-data" name="createdenghi" id="createdenghi">
            <h3 class="header smaller lighter blue">Soạn Thảo Tài Liệu</h3>
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
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Tên đề nghị </label>
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
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Ngày bắt đầu xem xét</label>
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
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Ngày kết thúc xem xét</label>
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
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Upload file </label>
                <div class="col-sm-6">
                    <input type="file" name="de_nghi_file" id="id-input-file-2" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Người xem xét </label>
                <div class="col-sm-6">
                    <select class="select2 form-control" id="de_nghi_user_receive" name="de_nghi_user_receive" data-placeholder="Chọn kết quả...">
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
                    <button class="btn" id="previous_back" type="reset" onclick="goBack();">
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
{/nocache}
{/block}