{* Extend our master template *}
{extends file="master.tpl"}
{block name="css"}
    <link rel="stylesheet" href="{site_url()}assets/css/jquery-confirm.css" />
    <link rel="stylesheet" href="{site_url()}assets/css/stylevth.css" />
    <link rel="stylesheet" href="{site_url()}assets/css/denghi.css" />
{/block}
{block name=script}
<script src="{$assets_path}plugins/datatables/jquery.dataTables.min.js"></script>
<script src="{$assets_path}plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="{site_url()}assets/js/sweetalert.min.js"></script>
<script src="{site_url()}assets/js/jquery-confirm.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    _load();
    //select/deselect all rows according to table header checkbox
    var active_class = 'active';
    $('#checkbox_all').change(function(){
        var th_checked = this.checked;//checkbox inside "TH" table header
        $(this).closest('table').find('tbody > tr').each(function(){
            var row = this;
            if(th_checked) $(row).addClass(active_class).find('input[type=checkbox]').eq(0).prop('checked', true);
            else $(row).removeClass(active_class).find('input[type=checkbox]').eq(0).prop('checked', false);
        });
    });

    //select/deselect a row when the checkbox is checked/unchecked
    $('input[name="thanhly"]').change(function(){
        var $row = $(this).closest('tr');
        if($row.is('.detail-row ')) return;
        if(this.checked) $row.addClass(active_class);
        else $row.removeClass(active_class);
    });
});
function _load() {
    table = $('#table_thanhly').DataTable();
}
function reload_table() {
    table.ajax.reload(null,false); //reload datatable ajax 
}

function thanhly() {
    let luumau_ids = new Array();
    $('input[name="thanhly"]:checked').each(function() {
        luumau_ids.push(this.value);
    });
    if (luumau_ids.length == 0) {
        swal("{$languages.canhbao}", "Bạn phải chọn ít nhất một lưu mẫu", "warning");
    } else {
        $.confirm({
            title: '{$languages.xacnhan}',
            content: '{$languages.xacnhan_laymau}',
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
                            url: "{site_url()}luumau/thanhly/thanhly",
                            data: {
                                'luumau_ids': luumau_ids
                            },
                            datatype: "text",
                            success: function (data) {
                                if (data) {
                                    swal("{$languages.thanhcong}", "{$languages.laymau_success}", "success");
                                    location.reload();
                                }
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
    <h3 class="header smaller lighter blue">Danh sách lưu mẫu cần thanh lý</h3>
</div>
<div class="col-xs-12">
    {if count($luumaus) > 0}
        <div style="font-weight: bold; font-size: 15px; padding-bottom: 20px;">Ngày Thanh Lý: {date("d-m-Y", strtotime($ngay_thanh_ly))}</div>    
    <table id="table table_thanhly" class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th class="center">
                    <label class="pos-rel">
                        <input type="checkbox" id="checkbox_all" class="ace" />
                        <span class="lbl"></span>
                    </label>
                </th>
                <th>Tên mẫu lưu</th>
                <th>Loại mẫu</th>
                <th>Vị trí</th>
                <th>Khối lượng</th>
                <th>Người giữ</th>
            </tr>
        </thead>
        <tbody>
            {foreach from=$luumaus key=k item=val }
                <tr>
                    <td class="center">
                        <label class="pos-rel">
                            <input type="checkbox" class="ace" value="{$val['luumau_id']}" name="thanhly" />
                            <span class="lbl"></span>
                        </label>
                    </td>
                    <td>{$val["luumau_name"]}</td>
                    <td>
                        {if $val["luumau_loai"] == 0}
                            Mẫu nguyên
                        {else}
                            Mẫu đã xử lý
                        {/if}   
                    </td>
                    <td>{$val["vitri"]}</td>
                    <td>{$val["luumau_khoiluong"]}</td>
                    <td>{$val["nhansu"]}</td>
                </tr>
            {/foreach}    
        </tbody>
    </table>
    <div>
        <button class="btn btn-info" onClick="thanhly()">
            <i class="ace-icon fa fa-check bigger-110"></i>
            Thanh Lý
        </button>
    </div>
    {else}
        Không có mẫu thanh lý.
    {/if}    
</div>    
<!-- PAGE CONTENT ENDS -->
</div>
</div>
</div>
</div>
</div>
{/block}