<h3 class="header smaller lighter blue">Thông Tin Tài Liệu</h3>
<div class="profile-user-info profile-user-info-striped">
    <div class="profile-info-row">
        <div class="profile-info-name"> Mã Tài Liệu </div>
        <div class="profile-info-value">
            <span class="editable" id="username">{$tai_lieu->tai_lieu_code}</span>
        </div>
    </div>
    <div class="profile-info-row">
        <div class="profile-info-name"> Tên Tài Liệu </div>
        <div class="profile-info-value">
            <span class="editable" id="username">{$tai_lieu->tai_lieu_name}</span>
        </div>
    </div>
    <div class="profile-info-row">
        <div class="profile-info-name"> Lần Ban Hành </div>
        <div class="profile-info-value">
            <span class="editable" id="username">{$tai_lieu->tai_lieu_lan_ban_hanh}</span>
        </div>
    </div>
    <div class="profile-info-row">
        <div class="profile-info-name"> Lần Sửa Đổi </div>
        <div class="profile-info-value">
            <span class="editable" id="username">{$tai_lieu->tai_lieu_lan_sua_doi}</span>
        </div>
    </div>
    <div class="profile-info-row">
        <div class="profile-info-name"> Tầng Tài Liệu </div>
        <div class="profile-info-value">
            <span class="editable" id="username">{$tai_lieu->tang_tai_lieu_ten}</span>
        </div>
    </div>
    <div class="profile-info-row">
        <div class="profile-info-name"> Loại Tài Liệu </div>
        <div class="profile-info-value">
            <span class="editable" id="username">{$tai_lieu->loai_tai_lieu_name}</span>
        </div>
    </div>
    {if isset($file_soan_thao)}    
        <div class="profile-info-row">
            <div class="profile-info-name"> File Tài Liệu </div>
            <div class="profile-info-value">
                {if $privquanly['master']}
                    <span class="editable editable-click" id="username">{$file_soan_thao->file_name}  <a href="{site_url()}{$_UPLOADS_PATH}{$file_soan_thao->file_path}">Download</a></span>
                {else}
                    <span class="editable editable-click" id="username">
                        {$file_soan_thao->file_name}
                        <a class="btn btn-xs btn-info" href="{site_url()}tailieu/tailieu/viewpdf?id={$file_soan_thao->file_id}" target="_blank">
                            <i class="ace-icon fa fa-search-plus bigger-120"></i>
                        </a>
                    </span>
                {/if}    
            </div>
        </div>
    {/if}        
</div>