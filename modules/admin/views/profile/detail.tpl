{* Extend our master template *}
{extends file="master.tpl"}
{block name=css}
<style type="text/css">
.img_nhansu_sign img{
	max-width: 100px;
	max-height: 100px;
}
</style>
{/block}
{block name=script}
<script type="text/javascript">
$(document).ready(function() {
    listmods();
    $('.nhansu_sign').on('change', function(){
        var formData = new FormData();
        formData.append('file', $(this).prop('files')[0]);
        $.ajax({
            type: "POST",
            url: "{site_url()}admin/profile/ajax_upload_sign",
            dataType: "json",
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            success: function (data) {
                if(data && data.code == '1' && data.nhansu_sign){
                    date_load = new Date();
                    $('.img_nhansu_sign').html($('<img>', { 'src': data.nhansu_sign + '?' + date_load.getTime() }));
                    $('.nhansu_sign').val('');
                }else{
                    alert('Không upload được chữ ký, vui lòng kiểm tra lại.');
                }
            },
            error: function () {
                alert('Không upload được chữ ký, vui lòng kiểm tra lại.');
            }
        });
    });
});
function listmods() {
    $('#listMods').html("<center><img src='{$assets_path}images/loadingAnimation.gif' /></center>");
    $.get('{site_url()}admin/profile/listmods', {}, 
        function(response) {
            $('#listMods').html(response);
        }
    );
}
</script>
{/block}
{block name=body}
<div class="main-content">
<div class="main-content-inner">
<div class="page-content">
<div class="row">
<div class="col-xs-12">
<!-- PAGE CONTENT BEGINS -->
<h3 class="header smaller lighter blue">{$profile.name}</h3>
<div class="clearfix">
<div class="pull-left tableTools-container"></div>
</div>
<div class="col-xs-12 col-sm-3 center">
<span class="profile-picture"><img class="editable img-responsive" alt="{$profile->name}'s Avatar" id="avatar2" src="{$assets_path}images/avatars/profile-pic.jpg" /></span>
<div class="space space-4"></div>
</div>
<div class="col-xs-12 col-sm-9">
<div class="profile-user-info">
<div class="profile-info-row">
<div class="profile-info-name">Ngày tham gia</div>
<div class="profile-info-value">
<span>{date('d/m/Y', strtotime($profile.date_create))}</span>
</div>
</div>
<div class="profile-info-row">
<div class="profile-info-name">Email</div>
<div class="profile-info-value">
<i class="fa fa-envelope light-blue bigger-110"></i>
<span><a href="#" target="_blank">{$profile.email}</a></span>
</div>
</div>
<div class="profile-info-row">
<div class="profile-info-name">Điện thoại</div>
<div class="profile-info-value">
<i class="fa fa-phone light-green bigger-110"></i>
<span>{$profile.phone}</span>
</div>
</div>
<div class="profile-info-row">
<div class="profile-info-name">Chữ ký</div>
<div class="profile-info-value">
<p class="img_nhansu_sign">
{if $nhansu_info['nhansu_sign']}
<img src="{$nhansu_info['nhansu_sign']}">
{/if}
</p>
<p>
<form method="post" enctype="multipart/form-data">
	<input type="file" name="nhansu_sign" class="nhansu_sign">
</form>
</p>
</div>
</div>
</div>
<h3 class="header smaller lighter blue">Quyền trên các modules</h3>
<div id="listMods"></div>
</div><!-- /.col -->
<!-- PAGE CONTENT ENDS -->
</div>
</div>
</div>
</div>
</div>
{/block}