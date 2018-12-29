<li class="purple dropdown-modal">
<a data-toggle="dropdown" class="dropdown-toggle" href="#">
<i class="ace-icon fa fa-bell icon-animated-bell"></i>
<span class="badge badge-important">{$tailieu + $ketqua}</span>
</a>

<ul class="dropdown-menu-right dropdown-navbar navbar-pink dropdown-menu dropdown-caret dropdown-close">
<li class="dropdown-header">
<i class="ace-icon fa fa-exclamation-triangle"></i>
Thông báo
</li>

<li class="dropdown-content">
<ul class="dropdown-menu dropdown-navbar navbar-pink">
{if $tailieu > 0}
<li><a href="{site_url()}tailieu/notification"><div class="clearfix">
	<span class="pull-left"><i class="btn btn-xs no-hover btn-pink fa fa-comment"></i> Tài liệu cần xử lý</span>
	<span class="pull-right badge badge-info">{$tailieu}</span>
</div></a></li>
{/if}
{if $ketqua > 0}
<li><a href="{site_url()}ketqua/notification"><div class="clearfix">
	<span class="pull-left"><i class="btn btn-xs no-hover btn-pink fa fa-comment"></i> Kết quả cần xử lý</span>
	<span class="pull-right badge badge-info">{$ketqua}</span>
</div></a></li>
{/if}
</ul>
</li>

</ul>
</li>