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
    {if isset($fileCauTruc) and $tai_lieu->loai_tai_lieu_id==1}    
    <div class="profile-info-row">
        <div class="profile-info-name"> File Cấu Trúc </div>
        <div class="profile-info-value">
            <span class="editable editable-click" id="username">{$fileCauTruc->file_name}  <a href="{site_url()}{$_UPLOADS_PATH}{$fileCauTruc->file_path}">Download</a></span>
        </div>
    </div>
    {/if}    
    {if isset($file_ban_hanh) and $tai_lieu->loai_tai_lieu_id==1 }    
        <div class="profile-info-row">
            <div class="profile-info-name"> File Ban Hành </div>
            <div class="profile-info-value">
                <span class="editable editable-click" id="username">{$file_ban_hanh->file_name}  <a href="{site_url()}{$_UPLOADS_PATH}{$file_ban_hanh->file_path}">Download</a></span>
            </div>
        </div>
    {/if}
    {if isset($file_ban_hanh_goc_path) and $tai_lieu->loai_tai_lieu_id==1}
        <div class="profile-info-row">
            <div class="profile-info-name"> File Ban Hành Gốc </div>
            <div class="profile-info-value">
                <span class="editable editable-click" id="username">{$file_ban_hanh->file_name}  <a href="{site_url()}{$file_ban_hanh_goc_path}">Download</a></span>
            </div>
        </div>
    {/if}
    {if isset($file_soan_thao)}    
        <div class="profile-info-row">
            <div class="profile-info-name"> File Bản Thảo </div>
            <div class="profile-info-value">
                <span class="editable editable-click" id="username">{$file_soan_thao->file_name}  <a href="{site_url()}{$_UPLOADS_PATH}{$file_soan_thao->file_path}">Download</a></span>
            </div>
        </div>
    {/if}        
</div>