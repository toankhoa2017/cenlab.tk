<li class="light-blue dropdown-modal">
<a data-toggle="dropdown" href="#" class="dropdown-toggle">
<img class="nav-user-photo" src="{$assets_path}images/avatars/user.jpg" alt="Jason's Photo" />
<span class="user-info">
<small>Welcome,</small>
{$ssAdmin.ssAdminFullname}
</span>

<i class="ace-icon fa fa-caret-down"></i>
</a>

<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
<li>
<a href="{site_url()}admin/profile">
<i class="ace-icon fa fa-user"></i>
Profile
</a>
</li>
<li>
<a href="#" onclick="changePWD()">
<i class="ace-icon fa fa-refresh"></i>
Change password
</a>
</li>
<li class="divider"></li>
<li>
<a href="{site_url()}admin/profile/logout">
<i class="ace-icon fa fa-power-off"></i>
Logout
</a>
</li>
</ul>
</li>