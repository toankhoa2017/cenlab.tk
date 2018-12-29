{extends file="master.tpl"}
{block name=css}
<link rel="stylesheet" href="{site_url()}assets/css/jquery-confirm.css" />
<link rel="stylesheet" href="{site_url()}assets/css/bootstrapValidator.min.css" />
<link rel="stylesheet" href="{$assets_path}css/bootstrap-duallistbox.min.css" />
{/block}
{block name=script}
<script src="{site_url()}assets/js/sweetalert.min.js"></script>
<script src="{site_url()}assets/js/jquery-confirm.js"></script>
<script src="{$assets_path}js/jquery.bootstrap-duallistbox.min.js"></script>
<script>
var demo1 = $('select[name="chucvu[]"]').bootstrapDualListbox();
$("#addchucvu").submit(function () {
    $.ajax({
        type: "POST",
        url: "{site_url()}nhansu/donvi/addchucvu/{$iddonvi}",
        data: {
            dschucvu: $('[name="chucvu[]"]').val(),
        },
        datatype: "text",
        success: function (data) {
            if (data == 1) {
                swal("{$languages.thanhcong}", "{$languages.themchucvu_save_success}", "success");
            }
        }
    });
    return false;
});
</script>
{/block}
{block name=body}
<div class="main-content">
    <div class="main-content-inner">
        <!--PATH BEGINS-->
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li><i class="ace-icon fa fa-home home-icon"></i><a href="#">{$languages.url_1}</a></li>
                <li><a href="{site_url()}nhansu/donvi"> {$languages.url_2}</a></li>
                <li class="active">{$tendonvi}</li>
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
                    <h3 class="header smaller lighter blue">{$languages.themchucvu_title} {$tendonvi}</h3>
                    <div class="clearfix">
                        <div class="pull-left tableTools-container"></div>
                    </div>
                    {*nội dung*}
                    <div class="row" style="padding-bottom:10px;text-align:center">
                        <div class="col-md-6"><b style="font-size:16px">{$languages.themchucvu_title_1}</b></div>
                        <div class="col-md-6"><b style="font-size:16px">{$languages.themchucvu_title_2}</b></div>
                    </div>
                    <form id="addchucvu" action="#" method="post">
                        <select multiple="multiple" size="18" name="chucvu[]" >
                            {foreach from=$alldonvi key=k item=v}
                                <option
                                    {if in_array($k,$dachon)}
                                        selected
                                    {/if} value="{$k}">{$v}</li>
                                {/foreach}
                        </select>
                        <br>
                        <div class="clearfix form-actions">
                            <button type="submit" class="btn btn-sm btn-success">{$languages.themchucvu_button_luu}</button>
                        </div>
                    </form>
                    {*  end nội dung*}
                </div>
            </div>
        </div>
    </div>
</div>
{/block}
