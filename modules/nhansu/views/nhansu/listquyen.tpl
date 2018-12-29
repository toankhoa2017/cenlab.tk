<style>
table.table_tang td {
        border-top:none !important;
        border-bottom:1px dotted #438eb9;
        padding-bottom:0 !important;
}
</style>
<script type="text/javascript">
function _checkTLQuyen(nhansu_id, quyen_id, tang_id, trangthai) {
{if $privcheck.master}
$.ajax({
    type: "POST",
    url: "{site_url()}nhansu/listquyen/assignquyen",
    data: {
        nhansu_id: nhansu_id,
        quyen_id: quyen_id,
        tang_id: tang_id,
        trangthai: trangthai,
    },
    datatype: "text",
    success: function (data) {
        //listQuyen(nhansu_id);
    }
});
{else}
    alert('{$languages.bankhongcoquyen}');
{/if}
}
</script>

<table id="table" class="table table-bordered">
<thead>
<tr>
<td style="background-color:#438eb9; color:#fff;">Quyền</td>
<td style="background-color:#438eb9; color:#fff;">Tài liệu nội bộ</td>
<td style="background-color:#438eb9; color:#fff;">Tài liệu bên ngoài</td>
</tr>
</thead>
<tbody>
{foreach from=$list_quyen item=q}
<tr>
<td>{$q->quyen_name}</td>
<td>
    <table class="table table_tang">
    {foreach from=$tang['1'] item=t1}
    {if in_array($t1['tang_tai_lieu_id'], $quyen[$q->quyen_id])}
        {assign var=checked value=' checked'}
        {assign var=trangthai value=0}
    {else}
        {assign var=checked value=''}
        {assign var=trangthai value=1}
    {/if}
    <tr>
    <td>{$t1['tai_lieu_code']}</td>
    <td><label>
        <input class="ace ace-switch ace-switch-3" type="checkbox"{$checked}>
        <span class="lbl" onclick="_checkTLQuyen({$nhansu_id}, {$q->quyen_id}, {$t1['tang_tai_lieu_id']}, {$trangthai})"></span>
    </label></td>
    </tr>
    {/foreach}
    </table>
</td>
<td>
    <table class="table table_tang">
    {foreach from=$tang['2'] item=t2}
    {if in_array($t2['tang_tai_lieu_id'], $quyen[$q->quyen_id])}
        {assign var=checked value=' checked'}
        {assign var=trangthai value=0}
    {else}
        {assign var=checked value=''}
        {assign var=trangthai value=1}
    {/if}
    <tr>
    <td>{$t2['tai_lieu_code']}</td>
    <td><label>
        <input class="ace ace-switch ace-switch-3" type="checkbox"{$checked}>
        <span class="lbl" onclick="_checkTLQuyen({$nhansu_id}, {$q->quyen_id}, {$t2['tang_tai_lieu_id']}, {$trangthai})"></span>
    </label></td>
    </tr>
    {/foreach}
    </table>
</td>
</tr>
{/foreach}
</tbody>
</table>