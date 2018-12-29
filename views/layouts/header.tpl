<div id="navbar" class="navbar navbar-default ace-save-state">
<div class="navbar-container ace-save-state" id="navbar-container">
<button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
<span class="sr-only">Toggle sidebar</span>
<span class="icon-bar"></span>
<span class="icon-bar"></span>
<span class="icon-bar"></span>
</button>
    <div class="navbar-header pull-left"><a href="#" class="navbar-brand"><small><i class="fa fa-leaf"></i> DEV LABORATORY</small></a></div>
<div class="navbar-buttons navbar-header pull-right" role="navigation">
<ul class="nav ace-nav">
<!--include file='header_task.tpl'-->
{if ($tailieu + $ketqua) > 0}
{include file='header_notification.tpl'}
{/if}
{include file='header_profile.tpl'}
</ul>
</div>
</div>
</div>