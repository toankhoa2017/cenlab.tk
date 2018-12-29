<div id="sidebar" class="sidebar responsive ace-save-state">
<div class="sidebar-shortcuts" id="sidebar-shortcuts">
<div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
    <span><a href="javascript:void(-1);" onclick="changeLanguage('vni')" title="Vietnamese"><img src="/assets/images/icons/vni.png" style="max-width:50px; max-height:20px;"></a></span>
    <span><a href="javascript:void(-1);" onclick="changeLanguage('eng')" title="Vietnamese"><img src="/assets/images/icons/eng.png" style="max-width:50px; max-height:20px;"></a></span>
</div>

<div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
<span class="btn btn-success"></span>
<span class="btn btn-info"></span>
<span class="btn btn-warning"></span>
<span class="btn btn-danger"></span>
</div>
</div>

<ul class="nav nav-list">
{foreach from=$list_group key=gid item=group}
    <li{if $ssLink.group eq $group['link']} class="active open"{/if}>
        {if count($list_module[$gid]) > 0}
            <a href="#" class="dropdown-toggle"><i class="menu-icon fa {$group['icon']}"></i><span class="menu-text">{$group['name']}</span><b class="arrow fa fa-angle-down"></b></a>
            <b class="arrow"></b>
            <ul class="submenu">
            {foreach from=$list_module[$gid] item=module}
                <li{if $ssLink.module eq $module['mlink']} class="active"{/if}>
                <a href="{site_url()}{$group['link']}/{$module['mlink']}"><i class="menu-icon fa fa-caret-right"></i>{$module['mname']}</a>
                <b class="arrow"></b>
                </li>
            {/foreach}
            </ul>
        {else}
            <a href="{site_url()}{$group['link']}"><i class="menu-icon fa {$group['icon']}"></i><span class="menu-text">{$group['name']}</span></a>
        {/if}
    </li>
{/foreach}
</ul><!-- /.nav-list -->

<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
<i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-left ace-save-state" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
</div>

</div>
