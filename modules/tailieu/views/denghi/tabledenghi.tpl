<table id="table" class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <th>Tên đề nghị</th>
            <th>Nội dung</th>
            <th>Bắt đầu</th>
            <th>Kết thúc</th>
            <th>Loại đề nghị</th>
            <th>Tài liệu</th>
            <th style="width: 130px">Người đề nghị</th>
            <th style="width: 100px">Trạng thái</th>
            <th style="width: 100px">Thao tác</th>
        </tr>
    </thead>
    <tbody>
        {foreach from=$items key=k item=val }
            <tr>
                <td style="width: 16%" class="ellipsis-text"><a href="{site_url()}{$url_detail}?id={$val["tai_lieu_id"]}">{$val["de_nghi_name"]}</a></td>
                <td style="max-width: 170px;" class="ellipsis-text">{$val["de_nghi_content"]}</td>
                <td>{date("d-m-Y", strtotime($val["de_nghi_date_start"]))}</td>
                <td>{date("d-m-Y", strtotime($val["de_nghi_date_end"]))}</td>
                <td>{$val["loai_de_nghi_name"]}</td>
                <td style="width: 14%">{$val["tai_lieu_name"]}</td>
                <td>{$userInfo[$val["de_nghi_user_send"]]}</td>
                <td>
                    {if $val["denghi_tai_lieu"] == $val["de_nghi_id"]}
                        <span class="label label-sm label-warning">Chưa duyệt</span>
                    {elseif !in_array($val["tai_lieu_id"], $tuchoi)}
                        <span class="label label-sm label-success">{$result_text}</span>
                    {else}
                        <span class="label label-sm label-danger">Từ chối</span>
                    {/if}
                </td>
                <td style="text-align: center;">
                    <div class="hidden-sm hidden-xs btn-group">
                        {if $val["denghi_tai_lieu"] == $val["de_nghi_id"]}
                            <a href="{site_url()}{$url_action}?id={$val["de_nghi_id"]}" class="btn btn-xs btn-warning">
                                <i class="ace-icon fa fa-pencil bigger-120"></i>
                            </a>
                       {/if}     
                    </div>
                </td>
            </tr>
        {/foreach}    
    </tbody>
</table>