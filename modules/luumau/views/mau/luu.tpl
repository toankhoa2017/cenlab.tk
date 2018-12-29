<div class="widget-header widget-header-small" style="margin-bottom:1%;">
    <h4 class="widget-title blue smaller">
        <i class="ace-icon fa fa-user orange"></i>
        {$languages.detail_tile}
    </h4>
</div>
<div class="profile-user-info">
    <input type="hidden" value="{$totalKhoiLuong}" id="total_khoiluong" >
    <table id="table" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>{$languages.thongtinmau}</th>
                <th>{$languages.vitri}</th>
                <th>{$languages.trangthai}</th>
                <th>{$languages.nguoigiumau}</th>
                <th>{$languages.thaotac}</th>
            </tr>
        </thead>
        <tbody>
            {foreach from=$mau_chia key=k item=v}
                <tr valign="top">
                    <td>
                        <ul>
                            <li>{$languages.luu_ten}: <a href="{site_url()}luumau/mau/history/{$v['luumau_id']}">{$v['luumau_name']}</a></li>
                            <li>{$languages.luu_loai}: {if $v['luumau_loai'] == '0'}
                                {$languages.chiamau_loaimau_1}
                                {else}
                                    {$languages.chiamau_loaimau_2}
                                    {/if}</li>
                                    <li>{$languages.luu_khoiluong}: {$v['luumau_khoiluong']}{$donvi}</li>
                                    {if $v['luumau_goi'] == '1'}
                                       <li>{$languages.luu_donvi}: {$v['donvi']}</li> 
                                    {/if}
                                </ul>
                            </td>
                            <td>{$v['vitri']}</td>
                            <td>{if $v['luumau_status'] == '0'}
                                {$languages.trangthai_1}
                                {elseif $v['luumau_status'] == '1'}
                                    {$languages.trangthai_2}
                                    {elseif $v['luumau_status'] == '2'}
                                        {$languages.trangthai_4}
                                        {elseif $v['luumau_status'] == '3'}
                                        {$languages.trangthai_3}
                                        {/if}</td>
                                        <td>{$v['nhansu']}</td>
                                        <td>
                                            {if $mau_luu != 'H'}
                                                {if $v['luumau_goi'] == '0'}
                                            {if $v['luumau_status'] == '0'}
                                                <button class="btn btn-primary btn-xs" onclick="laymau({$v['luumau_id']})">{$languages.button_laymau}</button>
                                            {elseif $v['luumau_status'] == '1'}
                                                <button class="btn btn-purple btn-xs" onclick="nhapmau({$v['luumau_id']})">{$languages.nhapmau}</button>
                                                <button class="btn btn-danger btn-xs" onclick="_hetmau({$v['luumau_id']})">{$languages.hetmau}</button>
                                            {elseif $v['luumau_status'] == '3'}

                                            {/if}
                                        {/if}
                                    {/if}
                                </td>
                            </tr>
                            {/foreach}
                            </tbody>
                        </table>                            
                    </div>