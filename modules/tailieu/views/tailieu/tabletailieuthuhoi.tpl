
<script type="text/javascript">
    function viewfilecautruc(filePath) {
        var url = "http://docs.google.com/gview?url={site_url()}" + filePath + "&embedded=true";
        $("#file_soan_thao").attr('src', url);
        $('#viewfiledoc').modal('toggle');
    }
</script>
<table id="table" class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <th>Tên</th>
            <th>Mã Số</th>
            <th>Tầng</th>
            <th>Loại</th>
            <th style="width: 100px">Lần SĐ</th>
            <th style="width: 100px">Lần BH</th>
            <th style="width: 150px">Ngày TH</th>
            <th>Xem</th>
        </tr>
    </thead>
    <tbody>
        {foreach from=$items key=k item=val }
            <tr>
                <td style="width: 30%;" class="ellipsis-text"><a href="{site_url()}{$url_action}?id={$val["tai_lieu_id"]}&thuhoi=1">{$val["tai_lieu_name"]}</a></td>
                <td>{$val["tai_lieu_code"]}</td>
                <td>{$val["tang_tai_lieu_ten"]}</td>
                <td>{$val["loai_tai_lieu_name"]}</td>
                <td>{$val["tai_lieu_lan_sua_doi"]}</td>
                <td>{$val["tai_lieu_lan_ban_hanh"]}</td>
                <td>{date("d-m-Y", strtotime($val["ngay_thu_hoi"]))}</td>
                <td>
                    <div class="hidden-sm hidden-xs btn-group">
                        <a class="btn btn-xs btn-info" href="{site_url()}tailieu/tailieu/viewpdf?id={$val['file']}" target="_blank">
                            <i class="ace-icon fa fa-search-plus bigger-120"></i>
                        </a>
                    </div>
                </td>
            </tr>
        {/foreach}    
    </tbody>
</table>

<div class="modal fade" id="viewfiledoc" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="false">
  <div class="modal-dialog modal-lg" style="height: 100%" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="exampleModalLabel">File soạn thảo tài liệu</h4>  
      </div>
      <div class="modal-body" style="width:100%">
          <iframe width="100%" id="file_soan_thao" height="500" style="border: none;" src=""></iframe> 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>    