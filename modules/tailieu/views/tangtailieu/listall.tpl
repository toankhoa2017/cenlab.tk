{* Extend our master template *}
{extends file="master.tpl"}
{block name="css"}
<link rel="stylesheet" href="{site_url()}assets/css/bootstrap-treefy.css" />
<link rel="stylesheet" href="{$assets_path}css/select2.min.css" />
<link rel="stylesheet" href="{site_url()}assets/css/denghi.css" />
{/block}
{block name=script}      
<script src="{site_url()}assets/js/bootstrap-treefy.js"></script>
<script src="{site_url()}assets/js/tree.min.js"></script>
<script src="{$assets_path}js/select2.min.js"></script>
<script type="text/javascript">
    jQuery(function($){
        $("#table").treeFy({
            treeColumn: 1,
            initStatusClass: 'treetable-collapsed'
        });
        $('.select2').select2({
            allowClear:true
        })
        .on('change', function(){
                //$(this).closest('form').validate().element($(this));
        });
        $('#id-input-file-2').ace_file_input({
            no_file:'No File1 ...',
            btn_choose:'Choose',
            btn_change:'Change',
            droppable:false,
            onchange:null,
            thumbnail:false //| true | large
        });
        $('#id-input-file-3').ace_file_input({
            no_file:'No File1 ...',
            btn_choose:'Choose',
            btn_change:'Change',
            droppable:false,
            onchange:null,
            thumbnail:false //| true | large
        });
        $('#id-input-file-4').ace_file_input({
            no_file:'No File1 ...',
            btn_choose:'Choose',
            btn_change:'Change',
            droppable:false,
            onchange:null,
            thumbnail:false //| true | large
        });
    function viewfilecautruc() {
        $('#viewfiledoc').modal('toggle');
    }
    function addTangTL(){
        let name = $("#ten_tang_tl").val();
        $.ajax({
            method: "POST",
            url: "{site_url()}tailieu/tangtailieu/ajax_add_tangtl",
            data: { ten: name }
        })
        .done(function( msg ) {
            location.reload();
        });
    }
    
    $("#add_tang_tl_cha").on("submit", function(e){
        e.preventDefault(); 
        $.ajax({
            method: "POST",
            url: "{site_url()}tailieu/tangtailieu/ajax_add_tangtl",
            data:new FormData(this),
            dataType: 'json',
            contentType: false, processData: false
        })
        .done(function( result ) {
            if(result.error_code){
                $(".error-box").show();
                $(".error-box .message").text(result.error_mess);
            }else{
                location.reload();
            }
        });
    })
    
    $("#add_tang_tl_con").on("submit", function(e){
        e.preventDefault(); 
        $.ajax({
            method: "POST",
            url: "{site_url()}tailieu/tangtailieu/ajax_add_tangtl",
            data:new FormData(this),
            dataType: 'json',
            contentType: false, processData: false
        })
        .done(function( result ) {
            if(result.error_code){
                $(".error-box").show();
                $(".error-box .message").text(result.error_mess);
            }else{
                location.reload();
            }
        });
    })
    
    $("#edit_tang_tl").on("submit", function(e){
        e.preventDefault(); 
        $.ajax({
            method: "POST",
            url: "{site_url()}tailieu/tangtailieu/ajax_update_tangtl",
            data:new FormData(this),
            dataType: 'json',
            contentType: false, processData: false
        })
        .done(function( result ) {
            location.reload();
        });
    })
    
    $("#delete_tang_tl").on("submit", function(e){
        e.preventDefault(); 
        $.ajax({
            method: "POST",
            url: "{site_url()}tailieu/tangtailieu/ajax_delete_tangtl",
            data:new FormData(this),
            dataType: 'json',
            contentType: false, processData: false
        })
        .done(function( result ) {
            location.reload();
        });
    })
    $("#edit_ma_tl_checkbox").change(function(e){
        if(this.checked){
            $("#tang_tai_lieu_code").show();
        }else{
            $("#tang_tai_lieu_code").hide();
        }
    });
    $("#edit_file_ct_checkbox").change(function(e){
        if(this.checked){
            $("#file_tai_lieu_ct").show();
        }else{
            $("#file_tai_lieu_ct").hide();
        }
    });
    $("#loai_tai_lieu_id").change(function(e){
        console.log(this.value);
        if(this.value == 1){
            $("#add_tang_tl_cha .cau-truc-file").show();
        }else{
            $("#add_tang_tl_cha .cau-truc-file").hide();
        }
    });
    $(document).on("click", ".open-AddTangTL", function () {
        var parentId = $(this).data('id');
        var loaiId = $(this).data('loaiid');
        var level = $(this).data('level');
        $(".modal-body #parent_id").val( parentId );
        $(".modal-body #loai_id").val( loaiId );
        $(".modal-body #level_id").val( parseInt(level) + 1 );
        $('#addTangTLDialog').modal('show');
        $("#add_tang_tl_con")[0].reset();
        if(loaiId == 2){
            $("#add_tang_tl_con .cau-truc-file").hide();
        }
    });
    
    $(document).on("click", ".open-EditTangTL", function () {
        var Id = $(this).data('id');
        var name = $(this).data('name');
        var ma = $(this).data('ma');
        var type = $(this).data('type');
        var loai = $(this).data('loai');
        $(".modal-body #ten_tang_tl_edit").val( name );
        $(".modal-body #ma_tang_tl_edit").val( ma );
        $(".modal-body #id_tang_tl").val( Id );
        if(type == 1)
            $(".modal-body #edit_phuong_phap").prop("checked", true);
        else
            $(".modal-body #edit_phuong_phap").prop("checked", false);
        if(loai == 1) // loai tl noi bo
            $(".file-cau-truc-tl-group").show();
        else
            $(".file-cau-truc-tl-group").hide();
        $('#editTangTLDialog').modal('show');
        //$("#edit_tang_tl")[0].reset(); 
    });
    $(document).on("click", ".open-DeleteTangTL", function () {
        var Id = $(this).data('id');
        $(".modal-body #id_tang_tl_delete").val( Id );
        $('#deleteTangDialog').modal('show');
    });
    
    $("#addTangTLDialog").on("hidden.bs.modal", function () {
        $('.error-box').hide();
    });
    
    $("#add_tang_tl").on("hidden.bs.modal", function() {
        $('.error-box').hide();
    });
});
</script>
{/block}
{block name=body}
<div class="main-content">
<div class="main-content-inner">
<div class="page-content">
<div class="row">
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        <h3 class="header smaller lighter blue">Tầng tài liệu</h3>
        <div class="clearfix">
            <div class="pull-left tableTools-container">
                <button type="button" class="btn btn-primary" style="margin-right: 15px;" data-toggle="modal" data-target="#add_tang_tl">
                    Thêm Tầng Tài Liệu
                </button>
                <a class="btn btn-primary" href="{site_url()}{$_UPLOADS_URL}tailieu/trangdau.docx">File Mẫu</a>
                <a class="btn btn-primary" href="{site_url()}{$_UPLOADS_URL}tailieu/trangdau_bieumau_dung.docx">File Biểu Mẫu 1</a>
                <a class="btn btn-primary" href="{site_url()}{$_UPLOADS_URL}tailieu/trangdau_bieumau_ngang.docx">File Biểu Mẫu 2</a>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="widget-box widget-color-blue2">
                    <div class="widget-header">
                        <h4 class="widget-title lighter smaller">Tầng tài liệu & cấu trúc</h4>
                    </div>
                    <div class="widget-body">
                        <div class="widget-main padding-8">
                            <table class="table" id="table">
                                <thead>
                                    <tr>
                                        <th></th><th>Tên Tầng Tài Liệu</th><th>Mã Tầng Tài Liệu</th>{if $privcheck.write}<th>Thêm Tầng Con</th>{/if}<th>Cấu Trúc File</th>{if $privcheck.update}<th>Sửa</th>{/if}{if $privcheck.delete}<th>Xóa</th>{/if}<th>Loại Tài Liệu</th><th>Ghi Chú</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {foreach from=$tangtls key=k item=tangtl}
                                        {if $tangtl['parent_id'] > 0}
                                            <tr data-node="treetable-{$tangtl['tang_tai_lieu_id']}" data-pnode="treetable-parent-{$tangtl['parent_id']}">
                                                <td><input type="checkbox" name="row.id"/></td>
                                                <td>{$tangtl["tang_tai_lieu_ten"]}</td>
                                                <td>{$tangtl["tai_lieu_code"]}</td>
                                                {if $privcheck.write}
                                                <td>
                                                    <a data-toggle="modal" data-id="{$tangtl['tang_tai_lieu_id']}" data-loaiid="{$tangtl['loai_tai_lieu']}" data-level="{$tangtl['level']}" title="Add this item" class="open-AddTangTL btn btn-sm btn-primary" href="#addTangTLDialog">Thêm</a>
                                                </td>
                                                {/if}
                                                <td>
                                                    {if $tangtl['loai_tai_lieu'] == 1 && isset($tangTLFiles[$tangtl['tang_tai_lieu_id']]["file_path"])}
                                                        <a href="{site_url()}{$_UPLOADS_PATH}{$tangTLFiles[$tangtl['tang_tai_lieu_id']]["file_path"]}">Xem</a>
                                                    {/if}
                                                </td>
                                                {if $privcheck.update}
                                                <td>
                                                    <a data-toggle="modal" data-id="{$tangtl['tang_tai_lieu_id']}" data-name="{$tangtl['tang_tai_lieu_ten']}" data-ma="{$tangtl['tai_lieu_code']}" data-type="{$tangtl['type_use']}" data-loai="{$tangtl['loai_tai_lieu']}" title="Sửa tầng tài liệu" class="open-EditTangTL btn btn-sm btn-primary" href="#editTangTLDialog">Sửa</a>
                                                </td>
                                                {/if}
                                                {if $privcheck.delete}
                                                <td>
                                                    <a data-toggle="modal" data-id="{$tangtl['tang_tai_lieu_id']}" title="" class="open-DeleteTangTL btn btn-sm btn-primary" href="#deleteTangTLDialog">Xóa</a>
                                                </td>
                                                {/if}
                                                <td>{$tangtl["loai_tai_lieu_name"]}</td>
                                                <td>
                                                    {if $tangtl["type_use"] == 1}
                                                        Phương pháp
                                                    {else if $tangtl["type_use"] == 2}
                                                        Biểu mẫu
                                                    {else}
                                                    {/if}   
                                                </td>
                                            </tr>
                                        {else}
                                            <tr data-node="treetable-{$tangtl['tang_tai_lieu_id']}">
                                                <td><input type="checkbox" name="row.id"/></td>
                                                <td>{$tangtl["tang_tai_lieu_ten"]}</td>
                                                <td>{$tangtl["tai_lieu_code"]}</td>
                                                {if $privcheck.write}
                                                <td>
                                                    <a data-toggle="modal" data-id="{$tangtl['tang_tai_lieu_id']}" data-loaiid="{$tangtl['loai_tai_lieu']}" data-level="{$tangtl['level']}" title="Add this item" class="open-AddTangTL btn btn-sm btn-primary" href="#addTangTLDialog">Thêm</a>
                                                </td>
                                                {/if}
                                                <td>
                                                    {if $tangtl['loai_tai_lieu'] == 1 && isset($tangTLFiles[$tangtl['tang_tai_lieu_id']]["file_path"])}
                                                        <a href="{site_url()}{$_UPLOADS_PATH}{$tangTLFiles[$tangtl['tang_tai_lieu_id']]["file_path"]}">Xem</a>
                                                    {/if}
                                                </td>
                                                
                                                {if $privcheck.update}
                                                <td>
                                                   <a data-toggle="modal" data-id="{$tangtl['tang_tai_lieu_id']}" data-name="{$tangtl['tang_tai_lieu_ten']}" data-type="{$tangtl['type_use']}" data-ma="{$tangtl['tai_lieu_code']}" data-loai="{$tangtl['loai_tai_lieu']}" title="Sửa tầng tài liệu" class="open-EditTangTL btn btn-sm btn-primary" href="#editTangTLDialog">Sửa</a>
                                                </td>
                                                {/if}
                                                {if $privcheck.delete}
                                                <td>
                                                    <a data-toggle="modal" data-id="{$tangtl['tang_tai_lieu_id']}" title="" class="open-DeleteTangTL btn btn-sm btn-primary" href="#deleteTangTLDialog">Xóa</a>
                                                </td>
                                                {/if}
                                                <td>{$tangtl["loai_tai_lieu_name"]}</td>
                                                <td>
                                                    {if $tangtl["type_use"] == 1}
                                                        Phương pháp
                                                    {else if $tangtl["type_use"] == 2}
                                                        Biểu mẫu
                                                    {else}
                                                    {/if}   
                                                </td>
                                            </tr>
                                        {/if}
                                    {/foreach}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
<!-- Modal -->
<!---Edit modal -->
<div class="modal fade" id="editTangTLDialog" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content" id="validation-form">
      <form method="post" id="edit_tang_tl" enctype="multipart/form-data">  
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="exampleModalLabel">Sửa Tầng Tài Liệu</h4>  
      </div>
      <div class="modal-body">
        <input type="hidden" name="tang-id" id="id_tang_tl" value=""/>   
        <div class="form-group">
          <label for="exampleInputEmail1">Tên tầng tài liệu</label>
          <input type="text" class="form-control" name="ten-tang-tl" id="ten_tang_tl_edit" aria-describedby="emailHelp" placeholder="Nhập tên tầng tài liệu">
        </div>
        <div class="form-group">
            <label>
                <input name="type_use" id="edit_phuong_phap" value="1" type="checkbox" class="ace">
                <span class="lbl">Tầng tài liệu phương pháp</span>
            </label>
        </div>
        <div class="form-group">
            <label>
                <input name="edit_ma_tl" id="edit_ma_tl_checkbox" type="checkbox" class="ace">
                <span class="lbl">Sửa mã tài liệu</span>
            </label>
        </div>
        <div class="form-group ignore" id="tang_tai_lieu_code">
            <label for="exampleInputEmail1">Mã tầng tài liệu</label>
            <input type="text" class="form-control" name="ma-tang-tl" id="ma_tang_tl_edit" aria-describedby="emailHelp">
        </div>
        <div class="file-cau-truc-tl-group">
            <div class="form-group">
                <label>
                    <input name="edit_file_ct" id="edit_file_ct_checkbox" type="checkbox" class="ace">
                    <span class="lbl">Sửa file cấu trúc</span>
                </label>
            </div>
            <div class="form-group ignore" id="file_tai_lieu_ct">
                <label for="exampleInputEmail1">File cấu trúc tài liệu</label>
                <input type="file" name="cau_truc_file" id="id-input-file-4" />
            </div>
        </div>    
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
        <button type="submit" class="btn btn-primary">Lưu</button>
      </div>
      </form>    
    </div>
  </div>
</div>

<div class="modal fade" id="addTangTLDialog" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form method="post" id="add_tang_tl_con" enctype="multipart/form-data">  
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="exampleModalLabel">Thêm Tầng Tài Liệu Con</h4>  
      </div>
      <div class="modal-body">
        <div class="step-pane error-box" style="display: none;">
            <div>
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert">
                        <i class="ace-icon fa fa-times"></i>
                    </button>
                    <div class="message"></div>
                </div>
            </div>
        </div>  
        <div class="form-group">
          <label for="exampleInputEmail1">Tên tầng tài liệu</label>
          <input type="text" class="form-control" name="ten-tang-tl" id="ten_tang_tl_child" aria-describedby="emailHelp" placeholder="Nhập tên tầng tài liệu">
        </div>
        <input type="hidden" name="parent-id" id="parent_id" value=""/> 
        <input type="hidden" name="loai-id" id="loai_id" value=""/> 
        <input type="hidden" name="level" id="level_id" value=""/> 
        <div class="form-group">
            <label for="exampleInputEmail1">Mã tầng tài liệu</label>
            <input type="text" class="form-control" name="ma-tang-tl" id="ma_tang_tl_child" aria-describedby="emailHelp" placeholder="Nhập mã tầng tài liệu">
        </div>
        <div class="form-group">
            <label>
                <input name="type_use" id="is_phuong_phap" value="1" type="checkbox" class="ace">
                <span class="lbl">Tầng tài liệu phương pháp</span>
            </label>
        </div>
        <div class="form-group cau-truc-file">
            <label for="form-field-1"> Upload file cấu trúc </label>
            <input type="file" name="cau_truc_file" id="id-input-file-2" />
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
        <button type="submit" class="btn btn-primary">Lưu</button>
      </div>
      </form>    
    </div>
  </div>
</div>

<div class="modal fade" id="deleteTangDialog" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="false">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form method="post" id="delete_tang_tl">   
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Cấu trúc tài liệu</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <input type="hidden" name="tang-id" id="id_tang_tl_delete" value=""/>
            <lable>Bạn có muốn xóa tầng tài liệu này ?</lable>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
          <button type="submit" class="btn btn-primary">Xóa</button>
        </div>
      </form>    
    </div>
  </div>
</div>

<div class="modal fade" id="add_tang_tl" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form method="post" id="add_tang_tl_cha" enctype="multipart/form-data">  
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="exampleModalLabel">Thêm Tầng Tài Liệu</h4>
      </div>
      <div class="modal-body">
        <div class="step-pane error-box" style="display: none;">
            <div>
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert">
                        <i class="ace-icon fa fa-times"></i>
                    </button>
                    <div class="message"></div>
                </div>
            </div>
        </div>  
        <div class="form-group">
          <label for="exampleInputEmail1">Tên tầng tài liệu</label>
          <input type="text" class="form-control" name="ten-tang-tl" id="ten_tang_tl" aria-describedby="emailHelp" placeholder="Nhập tên tầng tài liệu">
        </div> 
        <div class="form-group">
            <label for="exampleInputEmail1">Mã tầng tài liệu</label>
            <input type="text" class="form-control" name="ma-tang-tl" id="ma_tang_tl" aria-describedby="emailHelp" placeholder="Nhập mã tầng tài liệu">
        </div>
        <input type="hidden" name="parent-id" id="parent_id_cha" value="0"/>
        <input type="hidden" name="level" id="level_cha" value="1"/>
        <div class="form-group">
            <label for="form-field-1"> Loại tài liệu </label>
            <select class="select2 form-control" id="loai_tai_lieu_id" name="loai-id" style="width: 100%" name="loai_tai_lieu_id" data-placeholder="Chọn loại tài liệu...">
                <option value=""> --Select-- </option>
                {foreach from=$loai_tai_lieu key=k item=val}
                    <option value="{$val['loai_tai_lieu_id']}"> {$val['loai_tai_lieu_name']} </option>
                {/foreach}
            </select>
        </div>
        <div class="form-group">
            <label>
                <input name="type_use" id="is_phuong_phap" value="1" type="radio" class="ace">
                <span class="lbl">Tầng tài liệu phương pháp</span>
            </label>
        </div>
        <div class="form-group">
            <label>
                <input name="type_use" id="is_bieu_mau" value="2" type="radio" class="ace">
                <span class="lbl">Tầng tài liệu biểu mẫu</span>
            </label>
        </div>    
        <div class="form-group cau-truc-file">
            <label for="form-field-1"> Upload file cấu trúc </label>
            <input type="file" name="cau_truc_file" id="id-input-file-3" />
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
        <button type="submit" class="btn btn-primary">Lưu</button>
      </div>
      </form>        
    </div>
  </div>
</div>    
{/block}