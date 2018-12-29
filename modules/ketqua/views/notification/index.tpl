{* Extend our master template *}
{extends file="master.tpl"}
{block name="css"}
    <style type="text/css">
    .profile-skills .progress{
        background-color: #e9e9e9;
        margin-bottom: 5px;
    }
    .profile-skills .progress a{
        color: #fff;
    }
    .profile-skills .progress>span{
        line-height: 26px;
        margin-right: 5px;
        font-family: "Open Sans";
    }
    </style>
{/block}
{block name=script}{/block}
{block name=body}
<div class="main-content">
<div class="main-content-inner">
<div class="page-content">
<div class="row">
<div class="col-xs-12">
<!-- PAGE CONTENT BEGINS -->
<h3 class="header smaller lighter blue">Notification</h3>
<div class="widget-box transparent">
    <div class="widget-body">
        <div class="profile-skills">
            <p><a href="{site_url()}ketqua/phieuketqua">Chỉ tiêu đã xuất kết quả:</a></p>
            {assign var="data_export" value=round(($data['count_package_export']/$data['count_package_duyet_accept'])*100)}
            <div class="progress">
                <div class="progress-bar {if $data_export < 80}progress-bar-warning{/if}" style="width:{$data_export}%">
                    <span class="pull-right">{$data['count_package_export']}</span>
                </div>
                <span class="pull-right">{$data['count_package_duyet_accept']}</span>
            </div>
            <p style="margin-top: 15px;"><a href="{site_url()}ketqua/duyetketqua">Duyệt phiếu kết quả:</a></p>
            {assign var="data_approve" value=round(($data['count_all_approve']/$data['count_all_ketqua'])*100)}
            <div class="progress">
                <div class="progress-bar {if $data_approve < 80}progress-bar-warning{/if}" style="width:{$data_approve}%">
                    <span class="pull-right">{$data['count_all_approve']}</span>
                </div>
                <span class="pull-right">{$data['count_all_ketqua']}</span>
            </div>
        </div>
    </div>
</div>
<!-- PAGE CONTENT ENDS -->
</div>
</div>
</div>
</div>
</div>
{/block}