{* Extend our master template *}
{extends file="printer.tpl"}
{block name=css}
<style type="text/css">
@page{
    margin: 5mm 5mm 5mm 5mm;
}
body{
    font-family: "Times New Roman", Times, serif;
    line-height: 1.2;
}
.row{
    margin-left: -5px;
    margin-right: -5px;
}
.col-xs-3{
    padding-left: 5px;
    padding-right: 5px;
}
.mau_element{
    border: #000 solid 2px;
    margin-bottom: 5px;
}
.congty_name{
    border-bottom: #000 solid 1px;
    font-size: 11px;
    text-align: center;
    padding: 5px 8px;
}
.mau_info{
    padding: 5px 8px;
}
.mau_title{
    text-align: center;
    font-size: 11px;
    font-weight: bold;
}
.mau_maso{
    line-height: 1;
    padding: 3px 0;
}
.mau_maso strong{
    float: left;
    padding-top: 3px;
}
.mau_maso span{
    font-size: 20px;
    font-weight: bold;
    display: block;
    width: 100%;
    text-align: center;
}
.mau_nenmau strong{
    float: left;
}
.mau_nenmau span{
    display: block;
    width: 100%;
    text-align: center;
}
</style>
{/block}
{block name=body}
    <div class="row">
        {foreach from=$data['hopdong']['list_mau'] item=mau_info}
        <div class="col-xs-3">
            <div class="mau_element">
                <div class="congty_name">CÔNG TY TNHH MTV KHCN HOÀN VŨ</div>
                <div class="mau_info">
                    <div class="mau_title">NHÃN LƯU MẪU</div>
                    <div class="mau_maso"><strong>Mã số mẫu:</strong> <span>{$mau_info['mau_code']}</span></div>
                    <div class="mau_nenmau"><strong>Nền mẫu:</strong> <span>{$mau_info['nenmau_name']}</span></div>
                    <div class="mau_date">Ngày nhận mẫu: {$mau_info['date_create']|date_format:"%d/%m/%Y"}</div>
                    <div class="mau_timesave">Thời gian lưu mẫu: {$mau_info['mau_datesave_yeucau']|date_format:"%d/%m/%Y"}</div>
                    <div class="mau_mota">Mô tả mẫu: {$mau_info['mau_description']}</div>
                </div>
            </div>
        </div>
        {/foreach}
    </div>
{/block}
{block name=script}
{/block}