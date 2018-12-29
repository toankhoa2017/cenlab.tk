{* Extend our master template *}
{extends file="master.tpl"}
{block name="css"}
    <link rel="stylesheet" href="{site_url()}assets/css/denghi.css" />
    <link rel="stylesheet" href="{site_url()}assets/css/jquery-confirm.css" />
    <link rel="stylesheet" href="{site_url()}assets/css/stylevth.css" />
{/block}
{block name=script}
<script src="{$assets_path}plugins/datatables/jquery.dataTables.min.js"></script>
<script src="{$assets_path}plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="{site_url()}assets/js/sweetalert.min.js"></script>
<script src="{site_url()}assets/js/jquery-confirm.js"></script>
<script src="{site_url()}assets/js/jquery.number.js"></script>
<script src="{$assets_path}js/jquery-ui.min.js"></script>
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
    var khachhang_id = $(this).data("khachhang");
    $.ajax({
        method: "GET",
        url: "{site_url()}nenmau/khachhang/exportDinhgia?khachhang_id=" + khachhang_id
    })
    .done(function( result ) {
        let url = "{site_url()}" + result;
        document.location = url;
    });
})
</script>
{if $privdinhgia.write OR $privdinhgia.update}
    <!-- Bootstrap modal -->
    <div class="modal fade" id="gia_form_tong" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title title-gia"></h3>
                </div>
                <div class="modal-body">
                    <form id="gia_form_1">
                        <div class="form-group">
                            <label>{$languages.giachitieu}</label>
                            <input autocomplete="off" class="form-control" type="text" id="giatien_tong" name="giatien_tong" />
                            <input class="form-control" type="hidden" id="dongia_tong" name="dongia_tong" />
                            <input class="form-control" type="hidden" id="khachhang_tong" name="khachhang_tong" />
                        </div>
                        <script>
                            $(function () {
                                $('input[name=giatien]').number(true, 0);
                            });
                        </script>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="setgia_tong()" class="btn btn-xs btn-primary">{$languages.button_capnhat}</button>
                    <button type="button" class="btn btn-xs btn-danger" data-dismiss="modal">{$languages.button_thoat}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <!-- Bootstrap modal -->
    <div class="modal fade" id="gia_form_don" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title title-gia">Cập Nhật Giá Chất</h3>
                </div>
                <div class="modal-body">
                    <form id="gia_form_2">
                        {if count($chatGias) > 0 }
                            {foreach from=$chatGias item=gia}
                                <div class="form-group row gia-group">
                                    <label class="col-sm-3">Giá chất</label>
                                    <div class="col-sm-6">
                                        <input autocomplete="off" class="form-control giatien_don" type="text" value="{$gia}" name="giatien_don" />
                                    </div>
                                    <div class="col-sm-3">
                                        <button type="button" class="btn btn-xs btn-purple" onclick="cloneGiatien()" data-toggle="tooltip" title=""><i class="ace-icon fa fa-plus bigger-110"></i></button>
                                        <button class="btn btn-xs btn-danger" onclick="$(this).parent().parent().remove();" data-toggle="tooltip" title=""><i class="ace-icon fa fa-trash-o bigger-120" ></i></button>
                                    </div>
                                </div>
                            {/foreach}
                        {else}
                            <div class="form-group row gia-group">
                                <label class="col-sm-3">Giá chất</label>
                                <div class="col-sm-6">
                                    <input autocomplete="off" class="form-control giatien_don" type="text" value="" name="giatien_don" />
                                </div>
                                <div class="col-sm-3">
                                    <button type="button" class="btn btn-xs btn-purple" onclick="cloneGiatien()" data-toggle="tooltip" title=""><i class="ace-icon fa fa-plus bigger-110"></i></button>
                                    <button class="btn btn-xs btn-danger" onclick="$(this).parent().parent().remove();" data-toggle="tooltip" title=""><i class="ace-icon fa fa-trash-o bigger-120" ></i></button>
                                </div>
                            </div>
                        {/if}    
                        <input class="form-control" type="hidden" id="dongia_don" name="dongia_don" />
                        <input class="form-control" type="hidden" id="khachhang_don" name="khachhang_don" />
                        <script>
                            $(function () {
                                $('input[name=giatien]').number(true, 0);
                            });
                        </script>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="setgia_don()" class="btn btn-xs btn-primary">{$languages.button_capnhat}</button>
                    <button type="button" class="btn btn-xs btn-danger" data-dismiss="modal">{$languages.button_thoat}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
{/if}
{/block}
{block name=body}
<div class="main-content">
<div class="main-content-inner">
<div class="page-content">
<div class="row">
<div class="col-xs-12">
<!-- PAGE CONTENT BEGINS -->
<div class="col-xs-12">
    <h3 class="header smaller lighter blue">Định giá theo khách hàng</h3>
</div>
<div class="col-xs-12">
    <table id="table" class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th>Tên khách hàng</th>
                <th>Thao Tác</th>
                <th>Export</th>
            </tr>
        </thead>
        <tbody>
            {foreach from=$allCty key=k item=val }
                <tr>
                    <td>{$val["congty_name"]}</td>
                    <td>
                        <div class="hidden-sm hidden-xs btn-group">
                            <a href="{site_url()}nenmau/nenmaukhachhang?khachhang={$val["congty_id"]}" class="btn btn-xs btn-warning">
                                <i class="ace-icon fa fa-dollar bigger-110"></i> Định giá
                            </a>
                        </div>
                    </td>
                    <td><button class="btn btn-primary export-excel" data-khachhang="{$val["congty_id"]}" type="button">Excel</button></td>
                </tr>
            {/foreach}    
        </tbody>
    </table>
</div>    
<!-- PAGE CONTENT ENDS -->
</div>
</div>
</div>
</div>
</div>
{/block}