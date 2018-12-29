{* Extend our master template *}
{extends file="master.tpl"}
{block name=css}
    <link rel="stylesheet" href="{$assets_path}plugins/colorbox/css1/colorbox.css" />
    <link rel="stylesheet" href="{$assets_path}css/chosen.min.css" />
    <link rel="stylesheet" href="{$assets_path}css/ace.min.css" />
    <link rel="stylesheet" href="{$assets_path}css/bootstrap-datepicker3.min.css" />
    <link rel="stylesheet" href="{site_url()}assets/css/jquery-confirm.css" />

{/block}
{block name=script}
    <script src="{$assets_path}plugins/colorbox/jquery.colorbox.js"></script>
    <script src="{$assets_path}js/chosen.jquery.min.js"></script>
    <script src="{$assets_path}js/bootstrap-datepicker.min.js"></script>
    <script src="{site_url()}assets/js/jquery-confirm.js"></script>
    <script src="{site_url()}assets/js/sweetalert.min.js"></script>
    <script type="text/javascript">
        $(".colorbox_file").colorbox({
            iframe: true, innerWidth: 950, innerHeight: 500,
            onLoad: function () {
                $("#cboxClose").text('X');
            },
            onClosed: function () {

            }
        });
        function trove() {
            window.location = "{site_url()}nhansu/duyetketqua";
        }

        function luu() {
            var nhansu_id = [];
            var file_id = [];
        {foreach from=$danhsach key=k item=v}
            if ($("#hopdong_{$k}").val() != "") {
                nhansu_id.push({$k});
                file_id.push($("#hopdong_{$k}").val());
            }
        {/foreach}
            $.ajax({
                url: "{site_url()}nhansu/luu_condau",
                type: "POST",
                data: {
                    'nhansu_id' : nhansu_id,
                    'file_id' : file_id
                },
                success: function (data) {
                    swal("{$languages.thanhcong}", "{$languages.update_condau_success}", "success");
                }
            });
        }
    </script>
{/block}
{block name=body}
    <div class="main-content">
        <div class="main-content-inner">
            <!--PATH BEGINS-->
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li><i class="ace-icon fa fa-home home-icon"></i><a href="#">{$languages.url_1}</a></li>
                    <li><a href="{site_url()}nhansu">{$languages.url_2}</a></li>
                    <li><a href="{site_url()}nhansu/duyetketqua">{$languages.url_5}</a></li>
                    <li class="active">{$languages.url_6}</li>
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
                        <form id="nhansu" class="form-horizontal" role="form">
                            <h3 class="header smaller lighter blue">{$languages.title}</h3>
                            <table id="simple-table" class="table  table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>{$languages.table_1}</th>
                                        <th>{$languages.table_2}</th>
                                        <th>
                                            {$languages.table_3}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {foreach from=$danhsach key=k item=v}
                                        <tr>
                                            <td>
                                                {$v[0]}
                                            </td>
                                            <td>
                                                <img width="50" height="50" alt="50x50" src="{$v[1]}" id="image_{$k}">
                                            </td>
                                            <td>
                                                <input class="form-control" id="hopdong_{$k}" name="hopdong_{$k}" type="hidden"/>
                                                <a class="colorbox_file cboxElement" href="{site_url()}general/file?check=1&nhansu_id={$k}" title="Xử Lý File Hợp Đồng"><span class="help-button them_loaihopdong" style="height:30px;width:30px;position: relative;"><i class="ace-icon fa fa-plus" style="position: absolute;padding-top:8px;right:8px"></i></span></a>
                                            </td>
                                        </tr>
                                    {/foreach}
                                </tbody>
                            </table>
                            <div class="clearfix form-actions row">
                                <div class="col-md-offset-1 col-md-9">
                                    <button id="btnSave" class="btn btn-xs btn-success" type="button" onclick="luu()"><i class="ace-icon fa fa-check bigger-110"></i>{$languages.button_luu}</button>
                                    <button class="btn btn-xs btn-danger" type="button" onclick="trove()"><i class="ace-icon fa fa-undo bigger-110"></i>{$languages.button_trove}</button>
                                </div>
                            </div>
                        </form>
                        <!-- PAGE CONTENT ENDS -->
                    </div>
                </div>
            </div>
        </div>
    </div>
{/block}