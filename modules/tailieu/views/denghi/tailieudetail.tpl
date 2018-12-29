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
<div class="page-content tai-lieu-detail">
<div class="row">
<div class="col-xs-12">
<!-- PAGE CONTENT BEGINS -->
    {include file="./tailieuinfo.tpl" tai_lieu=$tai_lieu}
    <h3 class="header smaller lighter blue">Danh sách quy trình của tài liệu</h3>
    {include file='./tablequytrinhtailieu.tpl' url_action='' items=$quytrinh_denghi}  
<!-- PAGE CONTENT ENDS -->
</div>
</div>
</div>
</div>
</div>
{/block}