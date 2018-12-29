
<script type="text/javascript">
    function viewfilecautruc(filePath) {
        var url = "http://docs.google.com/gview?url={site_url()}" + filePath + "&embedded=true";
        $("#file_soan_thao").attr('src', url);
        $('#viewfiledoc').modal('toggle');
    }
</script>
<table id="tablever" class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <th>Lần sửa đổi</th>
            <th>Lần ban hành</th>
            <th>Người ban hành</th>
            <th>Xem</th>
        </tr>
    </thead>
    <tbody>
        {foreach from=$items key=k item=val }
            <tr>
                <td>{$val["tai_lieu_lan_sua_doi"]}</td>
                <td>{$val["tai_lieu_lan_ban_hanh"]}</td>
                <td>{$userInfo[$val["user_ban_hanh"]]}</td>
                <td>
                    <div class="hidden-sm hidden-xs btn-group">
                        <a class="btn btn-xs btn-info" href="{site_url()}tailieu/tailieu/viewpdf?id={$val['file_id']}" target="_blank">
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