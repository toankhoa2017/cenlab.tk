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
        function luu() {
            var donvi_id = new Array();
            var nhansu_id = new Array();
            var kiemtra = true;
        {foreach from=$phongthinghiem[0] key=kk item=vv}
            donvi_id.push({$kk})
            nhansu_id.push($("#phongthinghiem_{$kk}").val());
        {/foreach}
            if ($("#level_2").val() == 0) {
                kiemtra = false;
            }
            if ($("#level_3").val() == 0) {
                kiemtra = false;
            }
            if (kiemtra == true) {
                $.ajax({
                    type: "POST",
                    data: {
                        donvi_id_level1: donvi_id,
                        nhansu_id_level1: nhansu_id,
                        nhansu_id_level2: $("#level_2").val(),
                        nhansu_id_level3: $("#level_3").val(),
                    },
                    url: "{site_url()}nhansu/duyetketqua/luu_duyetketqua",
                    success: function (data) {
                        window.location = "{site_url()}nhansu/duyetketqua/hinhanh"
                    }
                });
            }
        }
        function _update_image() {
            window.location = "{site_url()}nhansu/duyetketqua/hinhanh"
        }
        
        function trove(){
            window.location = "{site_url()}nhansu"
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
                        <form id="nhansu" class="form-horizontal" role="form">
                            <h3 class="header smaller lighter blue" style="margin-top:0px;margin-left: 10%;margin-right:10%">{$languages.duyetketqua_title_1}</h3>
                            {foreach from=$phongthinghiem[0] key=kk item=vv}
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right">{$vv}</label>
                                    <div class="col-sm-4">
                                        <select class="chosen-select form-control" name="phongthinghiem" id="phongthinghiem_{$kk}" level="{$kk}">
                                            {foreach from=$phongthinghiem[1][$kk] key=k item=v}
                                                <option {if $dachon[1][$kk]==$k } selected {/if} value="{$k}">{$v}</option>
                                            {/foreach}
                                        </select>
                                    </div>
                                </div>  
                            {/foreach}
                            <h3 class="header smaller lighter blue" style="margin-top:0px;margin-left: 10%;margin-right:10%">{$languages.duyetketqua_title_2}</h3>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-6">{$languages.duyetketqua_nhansu}</label>
                                <div class="col-sm-4">
                                    <select class="chosen-select form-control" id="level_2" name="level_2">
                                        {foreach from=$nhansu key=k item=v}
                                            <option {if $dachoncaocap[2]==$k } selected {/if} value="{$k}">{$v}</option>
                                        {/foreach}
                                    </select>
                                </div>
                            </div>
                            <h3 class="header smaller lighter blue" style="margin-top:0px;margin-left: 10%;margin-right:10%">{$languages.duyetketqua_title_3}</h3>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-6">{$languages.duyetketqua_nhansu} </label>
                                <div class="col-sm-4">
                                    <select class="chosen-select form-control" id="level_3" name="level_3">
                                        {foreach from=$nhansu key=k item=v}
                                            <option {if $dachoncaocap[3]==$k } selected {/if} value="{$k}">{$v}</option>
                                        {/foreach}
                                    </select>
                                </div>
                            </div>

                            <div class="clearfix form-actions row">
                                <div class="col-md-offset-3 col-md-9">
                                    <button class="btn btn-xs btn-primary" type="button" onclick="_update_image()"><i class="ace-icon fa fa-check bigger-110"></i>{$languages.duyetketqua_button_updatecondau}</button>
                                    <button id="btnSave" class="btn btn-xs btn-success" type="button" onclick="luu()"><i class="ace-icon fa fa-check bigger-110"></i>{$languages.duyetketqua_button_luu}</button>
                                    <button class="btn btn-xs btn-danger" type="button" onclick="trove()"><i class="ace-icon fa fa-undo bigger-110"></i>{$languages.duyetketqua_button_trove}</button>
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