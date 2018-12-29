{* Extend our master template *}
{extends file="master.tpl"}
{block name=css}
<link rel="stylesheet" href="{site_url()}assets/css/jquery-confirm.css" />
<link rel="stylesheet" href="{site_url()}assets/css/stylevth.css" />

{/block}
{block name=script}
<script src="{site_url()}assets/js/sweetalert.min.js"></script>
<script src="{site_url()}assets/js/jquery-confirm.js"></script>
<script type="text/javascript">
$(document).ready(function () {
    //_load({$id});
});


$('#table').on('click','.view', function() {
	var id_donvi = $(this).parents('#table tr').find('.tendv').data('id');
	var ten_donvi = $(this).parents('#table tr').find('.tendv').text();
	window.location = '{site_url()}nhansu/donvi/view?id=' + id_donvi + '&ten=' + ten_donvi;
});
</script>
{/block}
{block name=body}
<div class="main-content">
    <div class="main-content-inner">
        <!--PATH BEGINS-->
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li><i class="ace-icon fa fa-home home-icon"></i><a href="{site_url()}nhansu/donvi">{$languages.url_1}</a></li>
                <li class="active">{$languages.url_2}</li>
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
                    <h3 class="header smaller lighter blue">DANH SÁCH NHÂN VIÊN CỦA ĐƠN VỊ {$tendv}</h3>
                    <div class="clearfix">
                        <div class="pull-left tableTools-container"></div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            
                        </div>
                    </div>
                    <div class="table-responsive-sm">
                        <table id="table" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Tên Nhân Viên</th>
                                    <th>Email</th>
                                    <th>Số Điện Thoại</th>
                                    <th>Chức vụ</th>
                                </tr>
                            </thead>
                            <tbody>
                            	{foreach from=$data item=nv}
                                	<tr>
                                		<td>{$nv['nhansu_lastname']} {$nv['nhansu_firstname']}</td>
                                        <td>{$nv['nhansu_email']}</td>
                                        <td>{$nv['nhansu_phone']}</td>
                                        <td>{$nv['chucvu_ten']}</td>
                                    </tr>
                                {/foreach}
                            </tbody>
                        </table>
                    </div>
                    <!-- PAGE CONTENT ENDS -->
                </div>
            </div>
        </div>
    </div>
</div>
{/block}