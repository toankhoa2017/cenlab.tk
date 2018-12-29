{extends file="master.tpl"}
{block name=body}
    <div class="main-content">
        <div class="main-content-inner">
            <!--PATH BEGINS-->
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li><i class="ace-icon fa fa-home home-icon"></i><a href="#">{$languages.url_1}</a></li>
                    <li><a href="{site_url()}nhansu/donvi"> {$languages.url_2}</a></li>
                    <li class="active">{$languages.url_3}</li>
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
                        <form id="form_soluong" class="form-horizontal" role="form">
                            <h3 class="header smaller lighter blue">{$languages.themsoluong_title} {$donvi[0]['donvi_ten']}</h3>
                            {foreach from=$chucvu key=id item=ten}
                                <div class="form-group row">
                                    <label class="col-sm-3 control-label no-padding-right">{$languages.themsoluong_tenchucvu}:</label>
                                    <div class="col-sm-2">
                                        <label class="form-control">{$ten}<label>
                                                </div>
                                                <label class="col-sm-2 control-label no-padding-right">{$languages.themsoluong_soluong}:</label>
                                                <div class="col-sm-2">
                                                    <input class="form-control" autocomplete="off" value="{$soluong[$id]}" id="soluong_{$id}" name="soluong_{$id}" placeholder="Số Lượng" required/>
                                                    <input id="chucvu_id_{$id}" name="chucvu_id_{$id}" value="{$id}" type="hidden"/>
                                                </div>
                                                </div>
                                            {/foreach}  
                                            <div class="clearfix form-actions">
                                                <div class="row">
                                                    <div class="col-sm-3"></div>    
                                                    <button type="button" onclick="_save()" class="btn btn-sm btn-success">{$languages.themsoluong_button_luu}</button>
                                                    <button type="button" class="btn btn-sm btn-danger" onclick="goBack()">{$languages.themsoluong_button_trove}</button>
                                                </div>
                                            </div>
                                            </form>
                                            </div>
                                            </div>
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
                                                            window.location = '{site_url()}nhansu/donvi';
                                                        }
                                                        function _save() {
                                                            $.confirm({
                                                                title: '{$languages.xacnhan}',
                                                                content: '{$languages.themsoluong_xacnhan_luu}',
                                                                icon: 'fa fa-question',
                                                                theme: 'modern',
                                                                closeIcon: true,
                                                                autoClose: 'cancel|10000',
                                                                animation: 'scale',
                                                                type: 'orange',
                                                                buttons: {
                                                                    '{$languages.co}': {
                                                                        btnClass: 'btn-primary',
                                                                        action: function () {
                                                                            $.ajax({
                                                                                url: "{site_url()}nhansu/donvi/add_soluong/{$donvi_id}",
                                                                                                            type: "POST",
                                                                                                            data: $('#form_soluong').serialize(),
                                                                                                            success: function (data) {
                                                                                                                console.log(data);
                                                                                                                //if success close modal and reload ajax table
                                                                                                                if (data == '1') {
                                                                                                                    swal("{$languages.thanhcong}", "{$languages.themsoluong_luu_success}", "success");
                                                                                                                }
                                                                                                            }
                                                                                                        });
                                                                                                    }
                                                                                                },
                                                                                                cancel: {
                                                                                                    text: '{$languages.khong}',
                                                                                                    btnClass: 'btn-danger',
                                                                                                    action: function () {
                                                                                                        // lets the user close the modal.
                                                                                                    }
                                                                                                }
                                                                                            }
                                                                                        });
                                                                                    }
                                            </script>
                                        {/block}
