<table id="tablever" class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <th>Phòng ban/User</th>
            <th>Ngày phân phối</th>
        </tr>
    </thead>
    <tbody>
        {foreach from=$items key=k item=val }
            {if $val["phong_ban_id"]}
                <tr>
                    <td>{$phongBan_info[$val["phong_ban_id"]]}</td>
                    <td>{$val["date_create"]}</td>
                </tr>
            {else}
                {foreach from=$phanphoi_users key=key item=value }
                    <tr>
                        <td>{$userInfo[$value]}</td>
                        <td>{$val["date_create"]}</td>
                    </tr>
                {/foreach}
            {/if}    
        {/foreach}
        
    </tbody>
</table>

<div class="modal fade" id="sua_phan_phoi" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="false">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{site_url()}tailieu/tailieu/phanphoitailieu" method="post" id="phan_phoi_form">   
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="exampleModalLabel">Phân Phối Tài Liệu</h4>  
        </div>
        <div class="modal-body">
            <input type="hidden" name="tai_lieu_id" id="id_tai_lieu" value="{$tai_lieu->tai_lieu_id}"/>
            <div class="form-group">
                <label for="form-field-1"> Phòng ban/user </label>
                <select class="select2 form-control" multiple="multiple" id="phong_ban_phan_phoi" name="phong_ban[]" style="width: 100%" data-placeholder="Chọn phòng ban...">
                    <option value=""> --Select-- </option>
                    {foreach from=$all_phongBan key=k item=val}
                        <option value="{$val['id']}" {if in_array($val['id'], $phongbans_id)} selected="selected" {/if}> {$val['name']} </option>
                    {/foreach}
                </select>
            </div>
                
            <div class="form-group">
                <label for="form-field-1"> Nhân viên phân phối </label>
                <select class="select2 form-control" multiple="multiple" id="nhan_vien_phan_phoi" name="nhan_vien[]" style="width: 100%" data-placeholder="Chọn kết quả...">
                    <option value=""> --Select-- </option>
                    {foreach from=$user_phongban key=phong item=all_user}
                        <optgroup label="{$phong}">
                            {foreach from=$all_user key=k item=val}
                                <option value="{$val['id']}" {if in_array($val['id'], $phanphoi_users)} selected="selected" {/if}> {$val["lastname"]} {$val["firstname"]} </option>
                            {/foreach}
                        </optgroup>
                    {/foreach}
                </select>
            </div>    
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
          <button type="submit" class="btn btn-primary">Đồng ý</button>
        </div>
      </form>    
    </div>
  </div>
</div>   