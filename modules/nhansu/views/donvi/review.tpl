{extends file="master.tpl"}
{block name=body}
    <div class="main-content">
        <div class="main-content-inner">
            <!--PATH BEGINS-->
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li><i class="ace-icon fa fa-home home-icon"></i><a href="#">{$languages.url_1}</a></li>
                    <li><a href="{site_url()}nhansu/donvi"> {$languages.url_2}</a></li>
                    <li class="active">{$donvi[0]['donvi_ten']}</li>
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
                        <h3 class="header smaller lighter blue">{$languages.review_title} {$donvi[0]['donvi_ten']}</h3>
                        {*nội dung*}
                        <table class="table table-bordered table-striped" id="table">
                            <thead>
                            <th>{$languages.review_table_1}</th>
                            <th>{$languages.review_table_2}</th>
                            <th>{$languages.review_table_3}</th>
                                {foreach from=$quyen item=foo}
                                <th>{$foo}</th>
                                {/foreach}
                            </thead>
                            <tbody id="noidung">

                            </tbody>
                        </table>
                        {*  end nội dung*}
                    </div>
                </div>
                <button class="btn btn-xs btn-danger" onclick="goBack()"><i class="ace-icon fa fa-reply icon-only"></i> {$languages.review_button_trove}</button>
            </div>
        </div>
    </div>
{/block}
{block name=css}
    <link rel="stylesheet" href="{site_url()}assets/css/jquery-confirm.css" />
    <link rel="stylesheet" href="{site_url()}assets/css/bootstrapValidator.min.css" />
    <link rel="stylesheet" href="{$assets_path}css/bootstrap-duallistbox.min.css" />
{/block}
{block name=script}
    <script src="{site_url()}assets/js/sweetalert.min.js"></script>
    <script src="{site_url()}assets/js/jquery-confirm.js"></script>
    <script src="{$assets_path}js/jquery.bootstrap-duallistbox.min.js"></script>
    <script>
                    function goBack() {
                        window.history.back();
                    }
                    reload_trangthai();
                    function reload_trangthai() {
                        $.ajax({
                            type: "POST",
                            url: "{site_url()}nhansu/donvi/review",
                            data: {
                                idreview: {$donvi[0]['donvi_id']},
                                namereview: name
                            },
                            datatype: "text",
                            success: function (data) {
                                $("#noidung").html('');
                                $("#noidung").append(data);
                            }
                        });
                    }
                    var mangxuly = [];
                    function _check(nhansu_id, quyen_id, trangthai) {
                        {if $privcheck.master}
                        $.ajax({
                            type: "POST",
                            url: "{site_url()}nhansu/donvi/add_quyen",
                            data: {
                                nhansu_id: nhansu_id,
                                quyen_id: quyen_id,
                                trangthai: trangthai,
                            },
                            datatype: "text",
                            success: function (data) {
                                reload_trangthai();
                            }
                        });
                        {else}
                            alert('Bạn không có quyền!');
                        {/if}
                    }
    </script>
{/block}
