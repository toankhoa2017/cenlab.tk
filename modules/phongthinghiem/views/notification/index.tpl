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
                                <p><a href="{site_url()}phongthinghiem/nhapketqua">Nhập kết quả:</a></p>
                                {assign var="data_ketqua" value=round(($data['count_package_ketqua']/$data['count_all_package'])*100)}
                                <div class="progress">
                                    <div class="progress-bar {if $data_ketqua < 80}progress-bar-warning{/if}" style="width:{$data_ketqua}%">
                                        <span class="pull-right">{$data['count_package_ketqua']}</span>
                                    </div>
                                    <span class="pull-right">{$data['count_all_package']}</span>
                                </div>
                                <p style="margin-top: 15px;"><a href="{site_url()}phongthinghiem/duyetketqua">Duyệt kết quả:</a></p>
                                {assign var="data_duyet" value=round(($data['count_package_duyet']/$data['count_all_package_duyet'])*100)}
                                <div class="progress">
                                    <div class="progress-bar {if $data_duyet < 80}progress-bar-warning{/if}" style="width:{$data_duyet}%">
                                        <span class="pull-right">{$data['count_package_duyet']}</span>
                                    </div>
                                    <span class="pull-right">{$data['count_all_package_duyet']}</span>
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