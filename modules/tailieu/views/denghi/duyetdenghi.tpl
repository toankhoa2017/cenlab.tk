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
}
function reload_table() {
    table.ajax.reload(null,false); //reload datatable ajax 
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
    {include file="./menuheader.tpl" duyetdn="label-danger"}
    <h3 class="header smaller lighter blue">Danh sách Đề Nghị Cần Duyệt</h3>
    {include file='./tabledenghi.tpl' url_action='tailieu/denghi/formxetduyetdenghi' url_detail='tailieu/denghi/tailieudetail' tuchoi=$duyetdn_tuchoi items=$list_de_nghi result_text="Đã duyệt"}
    
<!-- PAGE CONTENT ENDS -->
</div>
</div>
</div>
</div>
</div>
{/block}