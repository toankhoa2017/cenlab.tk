{* Extend our master template *}
{extends file="master.tpl"}
{block name="css"}
    <link rel="stylesheet" href="{site_url()}assets/css/denghi.css" />
{/block}
{block name=script}
<script src="{$assets_path}plugins/datatables/jquery.dataTables.min.js"></script>
<script src="{$assets_path}plugins/datatables/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    _load();
});
function _load() {
    table = $('#table').DataTable();
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
<div class="page-content">
<div class="row">
<div class="col-xs-12">
<!-- PAGE CONTENT BEGINS -->
<div class="tab-tailieu">
    <ul class="nav nav-tabs" id="recent-tab">
        <li class="active">
            <a data-toggle="tab" href="#tailieu-tab" aria-expanded="true">Danh sách tài liệu thu hồi</a>
        </li>
        <li class="">
            <a data-toggle="tab" href="#version-tab" aria-expanded="false">Danh sách các phiên bản sửa đổi của tài liệu</a>
        </li>
    </ul>
</div>
<div class="tab-content">
    <div class="tai-lieu-phanphoi tab-pane active" id="tailieu-tab">
        <h3 class="header smaller lighter blue">Danh sách tài liệu thu hồi</h3>
        {include file='./tabletailieuthuhoi.tpl' url_action='tailieu/tailieu/tailieudetail' items=$TLThuHoi}
    </div>
    <div class="tai-lieu-version tab-pane" id="version-tab">
        <h3 class="header smaller lighter blue">Danh sách các phiên bản sửa đổi của tài liệu</h3>
        {include file='./tabletailieuver.tpl' url_action='tailieu/denghi/tailieudetail' items=$tailieu_ver}
    </div>
</div>
<!-- PAGE CONTENT ENDS -->
</div>
</div>
</div>
</div>
</div>
{/block}