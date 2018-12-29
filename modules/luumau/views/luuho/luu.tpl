<div class="widget-header widget-header-small" style="margin-bottom:1%;">
    <h4 class="widget-title blue smaller">
        <i class="ace-icon fa fa-user orange"></i>
        {$languages.luu_title}
    </h4>
</div>
<div class="profile-user-info">
    <table id="table" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>{$languages.luu_thongtinmau}</th>
                <th>{$languages.luu_vitri}</th>
                <th>{$languages.luu_trangthai}</th>
                <th>{$languages.luu_nguoigiumau}</th>
                <th>{$languages.luu_thaotac}</th>
            </tr>
        </thead>
        <tbody>
            {foreach from=$mau_chia key=k item=v}
                <tr valign="top">
                    <td>
                        <ul>
                            <li>{$languages.luu_ten}: <a href="{site_url()}luumau/mau/history/{$v['luumau_id']}">{$v['luumau_name']}</a></li>
                            <li>{$languages.luu_loai}: {if $v['luuho_loai'] == '0'}
                                {$languages.luu_loai_1}
                                {else}
                                    {$languages.luu_loai_2}
                                    {/if}</li>
                                    <li>{$languages.luu_khoiluong}: {$v['luumau_khoiluong']}g</li>
                                    <li>{$languages.luu_dieukienluu}: {$v['luuho_dieukien']}</li>
                                </ul>
                            </td>
                            <td>{$v['vitri']}</td>
                            <td>{if $v['luumau_status'] == '0'}
                                {$languages.vitri_1}
                                {elseif $v['luumau_status'] == '1'}
                                    {$languages.vitri_2}
                                    {elseif $v['luumau_status'] == '2'}
                                        {$languages.vitri_4}
                                        {elseif $v['luumau_status'] == '3'}
                                        {$languages.vitri_3}
                                        {/if}</td>
                                        <td>{$v['nhansu']}</td>
                                        <td>
                                            {if $mau_luu != 'H'}
                                            {if $v['luumau_status'] == '0'}
                                                <button class="btn btn-primary btn-xs" onclick="laymau({$v['luumau_id']})">{$languages.luu_button_laymau}</button>
                                            {elseif ($v['luumau_status'] == '1'||$v['luumau_status'] == '3')}
                                                <button class="btn btn-purple btn-xs" onclick="nhapmau({$v['luumau_id']})">{$languages.luu_button_nhapmau}</button>
                                                <button class="btn btn-danger btn-xs" onclick="_hetmau({$v['luumau_id']})">{$languages.luu_button_hetmau}</button>
                                            {/if}
                                            {/if}
                                        </td>
                                    </tr>
                                    {/foreach}
                                    </tbody>
                                </table>                            
                            </div>