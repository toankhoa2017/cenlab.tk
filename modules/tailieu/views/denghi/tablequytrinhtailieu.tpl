<table id="table" class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <th style="display: none">#</th>
            <th>Tên quy trình</th>
            <th>Kết quả</th>
            <th>Tên đề nghị</th>
            <th>Nội dung</th>
            <th>Ngày thực hiện</th>
            <th style="width: 150px">Người thực hiện</th>
        </tr>
    </thead>
    <tbody>
        {foreach from=$items key=k item=val}
            {if count($val["denghi"]) > 0}
                {foreach from=$val["denghi"] key=k_dn item=val_dn}
                <tr>
                    <td style="display: none">{$val_dn["de_nghi_id"]}</td>
                    <td>
                        {$val["quy_trinh_name"]}
                    </td>
                    <td>
                        {$val_dn["de_nghi_ket_qua_name"]}
                    </td>
                    <td style="width: 20%;">
                        {$val_dn["de_nghi_name"]}
                    </td>
                    <td style="width: 20%;">
                        {$val_dn["de_nghi_content"]}
                    </td>
                    <td>
                        {if isset($val_dn["ngay_thuc_hien"])}
                            {date("d-m-Y", strtotime($val_dn["ngay_thuc_hien"]))}
                        {/if}    
                    </td>
                    <td>
                        {$userInfo[$val_dn["de_nghi_user_send"]]}
                    </td>
                </tr>
                {/foreach}
            {else}
                <tr>
                    <td style="display: none">9999999</td>
                    <td>{$val["quy_trinh_name"]}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            {/if}    
        {/foreach}
    </tbody>
</table>