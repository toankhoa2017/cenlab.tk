{* Extend our master template *}
{extends file="master.tpl"}
{block name=css}
    <link rel="stylesheet" href="{$assets_path}css/jquery.gritter.min.css">
    <style type="text/css">
        .profile-info-name{
            width: 200px;
        }
        .btn-action{
            position: initial;
        }
        .highlight { background-color: yellow }
    </style>
{/block}
{block name=body}
    <div class="main-content">
        <div class="main-content-inner">
            <!--PATH BEGINS-->
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li><i class="ace-icon fa fa-home home-icon"></i><a href="#">Home</a></li>
                    <li><a href="{site_url()}customer/ketqua/danhsachketqua">Danh sách phiếu trả kết quả</a></li>
                    <li class="active">Phản hồi kết quả</li>
                </ul>
            </div>
            <!--PATH ENDS-->
            <div class="page-content">
                <div class="row">
                    <div class="col-xs-12">
                        <!-- PAGE CONTENT BEGINS -->
                        <h3 class="header smaller lighter blue"><i class="fa fa-file-text" aria-hidden="true"></i> Phản hồi kết quả</h3>
                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="header green smaller">
                                    <i class="fa fa-briefcase orange"></i> Phiếu trả kết quả
                                </h4>
                                <div class="profile-user-info profile-user-info-striped" style="width: 100%;">
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Mã BN: </div>
                                        <div class="profile-info-value">
                                            <span class="editable" id="username">{$data['ketqua']['hopdong_code']}</span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Ngày xuất kết quả: </div>
                                        <div class="profile-info-value">
                                            <span class="editable" id="username">{$data['ketqua']['create_date']|date_format:"%d/%m/%Y"}</span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Ghi chú: </div>
                                        <div class="profile-info-value">
                                            <span class="editable" id="username">{$data['ketqua']['ketqua_note']}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <h4 class="header green smaller">
                                    <i class="fa fa-info-circle orange"></i> Thông tin phản hồi
                                </h4>
                                {if $data['result']}
                                    <div class="alert alert-info">
                                        <strong>Thành công!</strong> Gửi phản hồi thành công
                                    </div>
                                {else}
                                    <form action="" method="POST" class="form-horizontal" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="phanhoi_content"><strong>Nội dung:</strong></label>
                                            <div class="col-sm-10">
                                                <textarea name="phanhoi_content" id="phanhoi_content" class="form-control" rows="5"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="phanhoi_file" class="form-control"><strong>File chỉnh sửa:</strong></label>
                                            <div class="col-sm-10">
                                                <input type="file" id="phanhoi_file" name="phanhoi_file">
                                            </div>
                                        </div>
                                        <div class="clearfix form-actions">
                                            <div class="col-md-offset-3 col-md-9">
                                                <button class="btn btn-info" type="submit">
                                                    <i class="ace-icon fa fa-check bigger-110"></i> Gửi
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                {/if}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
{/block}
{block name=script}
<script type="text/javascript">
    $('#phanhoi_file').ace_file_input({
        no_file:'No File ...',
        btn_choose:'Choose',
        btn_change:'Change',
        droppable:false,
        onchange:null,
        thumbnail:false
    });
</script>
{/block}