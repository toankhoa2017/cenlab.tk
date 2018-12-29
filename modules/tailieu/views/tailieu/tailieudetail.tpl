{* Extend our master template *}
{extends file="master.tpl"}
{block name="css"}
<link rel="stylesheet" href="{$assets_path}css/select2.min.css" />
<link rel="stylesheet" href="{site_url()}assets/css/denghi.css" />
{/block}
{block name=script}
<script src="{$assets_path}plugins/datatables/jquery.dataTables.min.js"></script>
<script src="{$assets_path}plugins/datatables/dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" href="{site_url()}assets/css/denghi.css" />
<script src="{$assets_path}js/select2.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    _load();
    $('.select2').select2({
        allowClear:true
    });
});
function _load() {
    table = $('#table').DataTable({
        "order": [[ 0, "asc" ]]
    });
    tablever = $('#tablever').DataTable();
}
function reload_table() {
    table.ajax.reload(null,false); //reload datatable ajax
    tablever.ajax.reload(null,false); //reload datatable ajax
}
</script>
{/block}
{block name=body}
<div class="main-content">
<div class="main-content-inner">
<div class="page-content tai-lieu-detail">
<div class="row">
<div class="col-xs-12">
<!-- PAGE CONTENT BEGINS -->
    {include file="../tailieu/tailieuinfo.tpl" tai_lieu=$tai_lieu}
    {if !$detailThuHoi}
        <h3 class="header smaller lighter blue">Thu hồi tài liệu</h3>
        {if $thuhoi}
            {include file='../tailieu/thuhoitailieu.tpl'}
        {else}
            Tài liệu này không được thu hồi
        {/if}    
    {/if}
    <div style="margin-top: 20px;">
        <div class="tab-tailieu">
            <ul class="nav nav-tabs" id="recent-tab">
                <li class="active">
                    <a data-toggle="tab" href="#quytrinh-tab" aria-expanded="true">Danh sách quy trình của tài liệu</a>
                </li>
                {if $privquanly['master']}
                <li class="">
                    <a data-toggle="tab" href="#version-tab" aria-expanded="false">Danh sách các phiên bản của tài liệu</a>
                </li>
                {if !$detailThuHoi}
                <li class="">
                    <a data-toggle="tab" href="#phanphoi-tab" aria-expanded="false">Danh sách phòng ban phân phối</a>
                </li>
                {/if}
                {/if}
            </ul>
        </div>
        <div class="tab-content">
            <div class="tai-lieu-phanphoi tab-pane active" id="quytrinh-tab">
                <h3 class="header smaller lighter blue">Danh sách quy trình của tài liệu</h3>
                {include file='../denghi/tablequytrinhtailieu.tpl' url_action='' items=$quytrinh_denghi}
            </div>
            {if $privquanly['master']}
                <div class="tai-lieu-version tab-pane" id="version-tab">
                    <h3 class="header smaller lighter blue">Danh sách các phiên bản của tài liệu</h3>
                    {include file='../denghi/tablevertailieu.tpl' url_action='' items=$taiLieuVersion}
                </div>
                <div class="tai-lieu-version tab-pane" id="phanphoi-tab">
                    
                    <h3 class="header smaller lighter blue">
                        Danh sách phòng ban phân phối của tài liệu
                        <div class="pull-right tableTools-container" style="bottom: 10px;">
                            <button type="button" class="btn btn-primary" style="margin-right: 15px;" data-toggle="modal" data-target="#sua_phan_phoi">
                                Phân Phối Lại
                            </button>
                        </div>
                    </h3>
                    
                    {include file='../tailieu/tablephongbantailieu.tpl' url_action='' items=$phongbans}
                </div>
            {/if}
        </div>
    </div>        
<!-- PAGE CONTENT ENDS -->
</div>
</div>
</div>
</div>
</div>
{/block}