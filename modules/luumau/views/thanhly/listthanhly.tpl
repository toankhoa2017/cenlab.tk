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
<script src="{$assets_path}js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    var table = $('#table_thanhly').DataTable();
    $('.date-picker').datepicker({
        todayHighlight: true,
    });
    $("#de_nghi_date_start").datepicker('setDate', 'today');
    $("#de_nghi_date_end").datepicker('setDate', 'today');
    //$('#table_thanhly').DataTable().columns(0).search( "luu mau ffd" ).draw();
    $('#de_nghi_date_start').change( function() { table.draw(); } );
    $('#de_nghi_date_end').change( function() { table.draw(); } );
});
$.fn.dataTableExt.afnFiltering.push(
    function( oSettings, aData, iDataIndex ) {
        var iFini = document.getElementById('de_nghi_date_start').value;
        var iFfin = document.getElementById('de_nghi_date_end').value;
        var iDateCol = 4;
        
        iFini=iFini.substring(6,10) + iFini.substring(3,5)+ iFini.substring(0,2);
        iFfin=iFfin.substring(6,10) + iFfin.substring(3,5)+ iFfin.substring(0,2);
        
        var dato=aData[iDateCol].substring(6,10) + aData[iDateCol].substring(3,5)+ aData[iDateCol].substring(0,2);
        if ( iFini === "" && iFfin === "" )
        {
            return true;
        }
        else if ( iFini <= dato && iFfin === "")
        {
            return true;
        }
        else if ( iFfin >= dato && iFini === "")
        {
            return true;
        }
        else if (iFini <= dato && iFfin >= dato)
        {
            return true;
        }
        return false;
    }
);
function _load() {
    
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
<div class="col-xs-12">
    <h3 class="header smaller lighter blue col-xs-6">Danh sách mẫu đã thanh lý</h3>
    <h3 class="header smaller lighter blue col-xs-6" style="text-align: right;">
        <a style="color: red;" href="{site_url()}luumau/mau">Danh sách mẫu lưu</a>
    </h3>
</div>
<div class="col-xs-12">
    <div class="col-sm-6">
        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Ngày bắt đầu</label>
        <div class="input-group">
            <input class="form-control date-picker" autocomplete="off" id="de_nghi_date_start" name="de_nghi_date_start" type="text" data-date-format="dd-mm-yyyy" />
            <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
            </span>
        </div>
    </div>
    <div class="col-sm-6">
        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Ngày kết thúc</label>
        <div class="input-group">
            <input class="form-control date-picker" autocomplete="off" id="de_nghi_date_end" name="de_nghi_date_end" type="text" data-date-format="dd-mm-yyyy" />
            <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
            </span>
        </div>
    </div>
</div>
<div class="col-xs-12">
    {if count($mau_da_thanhly) > 0}
    <table id="table_thanhly" class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th>Tên mẫu lưu</th>
                <th>Mã số mẫu</th>
                <th>Loại mẫu</th>
                <th>Khối lượng</th>
                <th>Ngày thanh lý</th>
            </tr>
        </thead>
        <tbody>
            {foreach from=$mau_da_thanhly key=k item=val }
                <tr>
                    <td>{$val["luumau_name"]}</td>
                    <td>{$mauInfos[$val["mau_id"]]["code"]}</td>
                    <td>
                        {if $val["luumau_loai"] == 0}
                            Mẫu nguyên
                        {else}
                            Mẫu đã xử lý
                        {/if}   
                    </td>
                    <td>{$val["luumau_khoiluong"]}{$mauInfos[$val["mau_id"]]["donvi"]}</td>
                    <td>{date("d-m-Y", strtotime($val["luumau_ngay_thanhly"]))}</td>
                </tr>
            {/foreach}    
        </tbody>
    </table>
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