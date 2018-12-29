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
$(".export-excel").on("click", function(e){
    e.preventDefault(); 
    $.ajax({
        method: "GET",
        url: "{site_url()}tailieu/tailieu/exportTailieu"
    })
    .done(function( result ) {
        let url = "{site_url()}" + result;
        document.location = url;
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
<div class="col-xs-10">
    <h3 class="header smaller lighter blue">Danh sách tài liệu phân phối</h3>
</div>
{if $privquanly["master"]}
    <div class="col-xs-2">
        <button class="btn btn-primary export-excel" type="button">Download</button>
    </div>
{/if}
<div class="col-xs-12">
    {include file='./tabletailieu.tpl' url_action='tailieu/tailieu/tailieudetail' items=$TLPhanPhoi}
</div>    
<!-- PAGE CONTENT ENDS -->
</div>
</div>
</div>
</div>
</div>
{/block}