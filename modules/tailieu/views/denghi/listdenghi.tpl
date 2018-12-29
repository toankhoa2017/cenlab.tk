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
    {include file="./menuheader.tpl" dsdenghi="label-danger"}
    <h3 class="header smaller lighter blue">Danh Sách Đề Nghị</h3>
    <div class="clearfix">
        <div class="pull-left tableTools-container">
            <a type="button" href="{site_url()}tailieu/denghi/themdenghi" class="btn btn-primary">
                Thêm đề nghị
            </a>
        </div>
    </div>
    <table id="table" class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th>Tên đề nghị</th>
                <th>Nội dung</th>
                <th>Bắt đầu</th>
                <th>Kết thúc</th>
                <th>Loại đê nghị</th>
                <th>Tên tài liệu</th>
                <th>Quy trình hiện tại</th>
                <th>Kết quả</th>
            </tr>
        </thead>
        <tbody>
            {foreach from=$list_de_nghi key=k item=val }
                <tr>
                    <td style="width: 16%"><a href="{site_url()}tailieu/denghi/tailieudetail?id={$val["tai_lieu_id"]}">{$val["de_nghi_name"]}</a></td>
                    <td style="max-width: 170px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{$val["de_nghi_content"]}</td>
                    <td>{date("d-m-Y", strtotime($val["de_nghi_date_start"]))}</td>
                    <td>{date("d-m-Y", strtotime($val["de_nghi_date_end"]))}</td>
                    <td>{$val["loai_de_nghi_name"]}</td>
                    <td style="width: 14%">{$val["tai_lieu_name"]}</td>
                    <td>{$denghi_current_array[$val['tai_lieu_id']]}</td>
                    <td>{$denghi_current_ketqua[$val['tai_lieu_id']]}</td>
                </tr>
            {/foreach}    
        </tbody>
    </table>
<!-- PAGE CONTENT ENDS -->
</div>
</div>
</div>
</div>
</div>
{/block}