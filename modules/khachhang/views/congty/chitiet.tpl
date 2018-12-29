{* Extend our master template *}
{extends file="master.tpl"}
{block name=css}
<link rel="stylesheet" href="{site_url()}assets/css/stylevth.css" />
<style>
.profile-info-name{
    width: 200px;
}
</style>
{/block}
{block name=script}
<script type="text/javascript">
$(document).ready(function() {
    $("#viewcontact").click(function() {
        $.post("{site_url()}khachhang/contact", { id: '{$info.congty_id}' },
        function(response) {
            $('#contacts').html(response);
        });
    });
});
</script>
{/block}
{block name=body}
<div class="main-content">
    <div class="main-content-inner">
        <!--PATH BEGINS-->
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li><i class="ace-icon fa fa-home home-icon"></i><a href="#">{$languages.linkdau}</a></li>
                <li><a href="{site_url()}khachhang">{$languages.linkcuoi}</a></li>
                <li class="active">{$info.congty_name}</li>
            </ul>
            <div class="nav-search" id="nav-search">
                <form class="form-search">
                    <span class="input-icon"><input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" /><i class="ace-icon fa fa-search nav-search-icon"></i></span>
                </form>
            </div>
        </div>
        <!--PATH ENDS-->
        <div class="page-content">
            <div class="row">
                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->
                    <h3 class="header smaller lighter blue">{$languages.title_chitiet}</h3>
                    <div class="clearfix">
                        <div class="pull-left tableTools-container"></div>
                    </div>

                    <div id="user-profile-1" class="user-profile row">
                        <div class="col-xs-12 col-sm-3 center">
                            <div>
                                <span class="profile-picture">
                                    <img id="avatar" class="editable img-responsive" alt="Alex's Avatar" src="http://assets.tamducjsc.info/images/avatars/profile-pic.jpg" />
                                </span>

                                <div class="space-4"></div>

                                <div class="width-80 label label-info label-xlg arrowed-in arrowed-in-right">
                                    <div class="inline position-relative">
                                        <a href="#" class="user-title-label dropdown-toggle" data-toggle="dropdown">
                                            <i class="ace-icon fa fa-circle light-green"></i>
                                            &nbsp;
                                            <span class="white">{$info.congty_name}</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-9">
                            <div class="center">
                                <span class="btn btn-app btn-sm btn-light no-hover">
                                    <span class="line-height-1 bigger-170 blue"> 1,411 </span>
                                    <br />
                                    <span class="line-height-1 smaller-90"> Views </span>
                                </span>
                                <span class="btn btn-app btn-sm btn-yellow no-hover">
                                    <span class="line-height-1 bigger-170"> 32 </span>
                                    <br />
                                    <span class="line-height-1 smaller-90"> Followers </span>
                                </span>
                                <span class="btn btn-app btn-sm btn-pink no-hover">
                                    <span class="line-height-1 bigger-170"> 4 </span>
                                    <br />
                                    <span class="line-height-1 smaller-90"> Projects </span>
                                </span>
                                <span class="btn btn-app btn-sm btn-grey no-hover">
                                    <span class="line-height-1 bigger-170"> 23 </span>
                                    <br />
                                    <span class="line-height-1 smaller-90"> Reviews </span>
                                </span>
                                <span class="btn btn-app btn-sm btn-success no-hover">
                                    <span class="line-height-1 bigger-170"> 7 </span>
                                    <br />
                                    <span class="line-height-1 smaller-90"> Albums </span>
                                </span>
                                <span class="btn btn-app btn-sm btn-primary no-hover" id="viewcontact">
                                    <span class="line-height-1 bigger-170"> 55 </span>
                                    <br />
                                    <span class="line-height-1 smaller-90"> Contacts </span>
                                </span>
                            </div>
                            <div class="space-12"></div>
                            <div class="profile-user-info profile-user-info-striped">
                                <div class="profile-info-row">
                                    <div class="profile-info-name"> {$languages.name} </div>
                                    <div class="profile-info-value">
                                        <span class="editable" id="username">{$info.congty_name}</span>
                                    </div>
                                </div>
                                <div class="profile-info-row">
                                    <div class="profile-info-name"> {$languages.address} </div>
                                    <div class="profile-info-value">
                                        <i class="fa fa-map-marker light-orange bigger-110"></i>
                                        <span class="editable" id="country">{$info.congty_address}</span>
                                    </div>
                                </div>
                                <div class="profile-info-row">
                                    <div class="profile-info-name"> {$languages.phone} </div>
                                    <div class="profile-info-value">
                                        <span class="editable" id="age">{$info.congty_phone}</span>
                                    </div>
                                </div>
                                <div class="profile-info-row">
                                    <div class="profile-info-name"> {$languages.fax} </div>
                                    <div class="profile-info-value">
                                        <span class="editable" id="signup">{$info.congty_fax}</span>
                                    </div>
                                </div>
                                <div class="profile-info-row">
                                    <div class="profile-info-name"> {$languages.email} </div>

                                    <div class="profile-info-value">
                                        <span class="editable" id="login">{$info.congty_email}</span>
                                    </div>
                                </div>
                                <div class="profile-info-row">
                                    <div class="profile-info-name"> {$languages.tax} </div>

                                    <div class="profile-info-value">
                                        <span class="editable" id="about">{$info.congty_tax}</span>
                                    </div>
                                </div>
                            </div>
                            <!-- PAGE CONTENT ENDS -->
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>
{/block}