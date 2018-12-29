<table id="table" class="table table-bordered table-striped">
<thead>
<tr>
<th>Tên module</th>
<th>Đọc</th>
<th>Ghi</th>
<th>Xóa</th>
<th>Sửa</th>
<th>Toàn quyền</th>
</tr>
</thead>
<tbody>
{foreach from=$items key=group item=mods}
    <tr><td colspan="6" style="background-color:#438eb9; color:#fff;">{$group}</td></tr>
    {foreach from=$mods key=myId item=i}
        <tr>
        <td>{$i.module}</td>
        <td><input type='checkbox' id='checkbox_read[{$i.stt}]' name='checkbox_read[{$i.stt}]' value='{$myId}'{$i.read} /></td>
        <td><input type='checkbox' id='checkbox_write[{$i.stt}]' name='checkbox_write[{$i.stt}]' value='{$myId}'{$i.write} /></td>
        <td><input type='checkbox' id='checkbox_delete[{$i.stt}]' name='checkbox_delete[{$i.stt}]' value='{$myId}'{$i.delete} /></td>
        <td><input type='checkbox' id='checkbox_update[{$i.stt}]' name='checkbox_update[{$i.stt}]' value='{$myId}'{$i.update} /></td>
        <td><input type='checkbox' id='checkbox_master[{$i.stt}]' name='checkbox_master[{$i.stt}]' value='{$myId}'{$i.master} /></td>
        </tr>
        <input type='hidden' id='idMod[{$i.stt}]' name='idMod[{$i.stt}]' value='{$myId}'>
    {/foreach}
{/foreach}
</tbody>
</table>