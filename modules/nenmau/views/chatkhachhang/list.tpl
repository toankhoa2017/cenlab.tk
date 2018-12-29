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
function cloneGiatien() {
    var countData = {$num_chat};
    console.log(countData);
    var lengthEle = $("#gia_form_2 .gia-group").length;
    if(lengthEle < countData - 1){
        $( ".gia-group" ).last().clone().find("input:text").val("").end().appendTo( "#gia_form_2" );
        $('input[name=giatien_don]').number(true, 0);
    }
    else
        alert("Bạn đã nhập hết giá");
}

function setgia_don() {
    if ($("#giatien_don").val() == "" || parseInt($("#giatien_don").val()) < 0) {
        swal("{$languages.canhbao}", "{$languages.giachitieu_validation}", "warning");
    } else {
        var giatien = [];
        $(".giatien_don").each(function() {
            giatien.push($(this).val());
        });
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
                                gia: giatien,
                                dongia: $("#dongia_don").val(),
                                khachhang: $("#khachhang_don").val()
                            },
                            url: "{site_url()}nenmau/khachhang/setgia_don",
                            success: function (data) {
                                swal("{$languages.thanhcong}", "{$languages.giachitieu_success}", "success");
                                location.reload();
                                $("#gia_form_don").modal("hide");
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
function dinhgia_don(gia, khachhang_id, dongia_id){
    $("#dongia_don").val(dongia_id);
    $(".giatien_don").val(gia);
    $("#khachhang_don").val(khachhang_id);
    $("#gia_form_don").modal("show");
    $('#giatien').focus();
}
</script>
{if $privdinhgia.write OR $privdinhgia.update}
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
                                $('input[name=giatien_don]').number(true, 0);
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
    <h3 class="header smaller lighter blue">Định giá khách hàng - Chỉ tiêu</h3>
</div>
<div class="col-xs-12">
    <div class="row" style="margin-bottom:5px;">
        <div class="col-xs-5">
            {if $num_chat > 1}
                {if $privdinhgia_kh.write OR $privdinhgia_kh.update}<button class="btn btn-xs btn-warning" data-toggle="tooltip" onclick="dinhgia_don(0, {$khachhang}, {$dongia})"><i class="ace-icon fa fa-dollar bigger-110"></i> Định giá đơn</button> {/if}
            {/if}
        </div>
    </div>
    <table id="table" class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th>Tên chất</th>
                <th>Thao Tác</th>
            </tr>
        </thead>
        <tbody>
            {foreach from=$list_chat key=k item=val }
                <tr>
                    <td>{$val->chat_name}</td>
                    <td>
                        <div class="hidden-sm hidden-xs btn-group">
                        </div>
                    </td>
                </tr>
            {/foreach}    
        </tbody>
    </table>
    {if $privdinhgia.write OR $privdinhgia.update}        
        <h3 class="header smaller lighter blue">Giá Chỉ Tiêu</h3>
        {if count($chatGias) > 0 }
            <table id="table_gia_chat" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Thứ tự</th>
                        <th>Giá</th>
                    </tr>
                </thead>
                <tbody> 
                    {foreach from=$chatGias key=k item=val }
                        <tr>
                            <td style="width: 30%;">{$k + 1}</td>
                            <td>{number_format($val)}</td>
                        </tr>
                    {/foreach}    
                </tbody>
            </table>
        {/if}    
        <!-- PAGE CONTENT ENDS -->
    {/if}    
</div>    
<!-- PAGE CONTENT ENDS -->
</div>
</div>
</div>
</div> 
</div>
{/block}