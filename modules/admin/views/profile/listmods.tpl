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
		<td><i class="fa {if $i.read}fa-check-square-o{else}fa-square-o{/if} light-blue bigger-110"></i></td>
		<td><i class="fa {if $i.write}fa-check-square-o{else}fa-square-o{/if} light-blue bigger-110"></i></td>
		<td><i class="fa {if $i.delete}fa-check-square-o{else}fa-square-o{/if} light-blue bigger-110"></i></td>
		<td><i class="fa {if $i.update}fa-check-square-o{else}fa-square-o{/if} light-blue bigger-110"></i></td>
		<td><i class="fa {if $i.master}fa-check-square-o{else}fa-square-o{/if} light-blue bigger-110"></i></td>
		</tr>
    {/foreach}
{/foreach}
</tbody>
</table>