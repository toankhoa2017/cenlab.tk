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
function setgia_tong() {
    if ($("#giatien_tong").val() == "" || parseInt($("#giatien_tong").val()) < 0) {
        swal("{$languages.canhbao}", "{$languages.giachitieu_validation}", "warning");
    } else {
        $.confirm({
            title: '{$languages.xacnhan}',
            content: '{$languages.xacnhan_giachitieu_update}',
            icon: 'fa fa-question',
            theme: 'modern',
            closeIcon: true,
            autoClose: 'cancel|10000',
            animation: 'scale',
            type: 'orange',
            buttons: {
                '{$languages.co}': {
                    btnClass: 'btn-primary',
                    action: function () {
                        $.ajax({
                            type: "POST",
                            data: {
                                gia: $("#giatien_tong").val(),
                                dongia: $("#dongia_tong").val(),
                                khachhang: $("#khachhang_tong").val()
                            },
                            url: "{site_url()}nenmau/khachhang/setgia_tong",
                            success: function (data) {
                                swal("{$languages.thanhcong}", "{$languages.giachitieu_success}", "success");
                                //reload_table();
                                location.reload();
                                $("#gia_form_tong").modal("hide");
                                //$("#gia_form_1")[0].reset();
                            }
                        });
                    }
                },
                cancel: {
                    text: '{$languages.khong}',
                    btnClass: 'btn-danger',
                    action: function () {
                        // lets the user close the modal.
                    }
                }
            }
        });
    }
}
function dinhgia_tong(gia, khachhang_id, dongia_id) {
    $('.title-gia').text('{$languages.giachitieu_title_1}');
    $("#giatien_tong").val(gia);
    $("#dongia_tong").val(dongia_id);
    $("#khachhang_tong").val(khachhang_id);
    $("#gia_form_tong").modal("show");
    $('#giatien').focus();
}
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
                                $('input[name=giatien_tong]').number(true, 0);
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
    <h3 class="header smaller lighter blue">Định giá khách hàng - Nhóm chỉ tiêu</h3>
</div>
<div class="col-xs-12">
    <table id="table" class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th>Tên nhóm chỉ tiêu</th>
                <th>Package Code</th>
                <th>Giá tổng</th>
                <th>Thao Tác</th>
            </tr>
        </thead>
        <tbody>
            {foreach from=$list_chitieu key=k item=val }
                <tr>
                    <td>{$val->chitieu_name}</td>
                    <td>{$val->package_code}</td> 
                    <td>{number_format($data_price[$val->dongia_id])}</td>
                    <td>
                        <div class="hidden-sm hidden-xs btn-group">
                            {if $privdinhgia_kh.write OR $privdinhgia_kh.update}
                                <button class="btn btn-xs btn-warning" data-toggle="tooltip" title="Giá nhóm chỉ tiêu" style="margin-right: 20px" onclick="dinhgia_tong(0, {$khachhang}, {$val->dongia_id})"><i class="ace-icon fa fa-dollar bigger-110"></i> Giá nhóm chỉ tiêu</button>
                            {/if}
                            {if $privdinhgia_kh.read}
                            <a href="{site_url()}nenmau/chatkhachhang?khachhang={$khachhang}&dongia={$val->dongia_id}" class="btn btn-xs btn-warning">
                                <i class="ace-icon fa fa-dollar bigger-110"></i> Giá chỉ tiêu
                            </a>
                            {/if}    
                        </div>
                    </td>
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