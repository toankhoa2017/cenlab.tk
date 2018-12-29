{* Extend our master template *}
{extends file="master.tpl"}
{block name=css}
    <link rel="stylesheet" href="{site_url()}assets/css/jquery-confirm.css" />
    <link rel="stylesheet" href="{site_url()}assets/css/stylevth.css" />
    <link rel="stylesheet" href="{$assets_path}plugins/colorbox/css1/colorbox.css" />
    <style>
        #profile {
            display: none;
        }
    </style>
{/block}
{block name=script}
    <script src="{$assets_path}plugins/colorbox/jquery.colorbox.js"></script>
    <script src="{$assets_path}plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="{$assets_path}plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script src="{site_url()}assets/js/sweetalert.min.js"></script>
    <script src="{site_url()}assets/js/jquery-confirm.js"></script>
    <script type="text/javascript">
    $(document).ready(function () {
        //_load();
    });
    function _add() {
        $('#profile').attr('style', 'display:block');
    }
    function _save() {
        if($('#profile_name').val() == ''){
            swal("Thông báo", "Bạn phải nhập tên hồ sơ", "warning");
        }
        else {
            var files = $('#files').prop('files')[0];        
            var form_data = new FormData();
            form_data.append("file", files);
            form_data.append("name_profile", $('#profile_name').val());
            form_data.append("id_ncc", $('#id_ncc').val());
            form_data.append("id_profile", $('#id_profile').val());
            form_data.append("id_file", $("#id_file").val());
            $.ajax({
                url:"{site_url()}vattu/nhacungcap/addprofile",
                type : "POST",
                data:  form_data,
                contentType:false,
                processData:false,
                dataType : 'JSON',
                success:function(data)
                {
                    if(data.code == '100') {
                        //alert(data.mess);
                        window.location = "{site_url()}vattu/nhacungcap/profile?id={$id}&name={$name}";
                    }
                    else {
                        alert(data.mess);
                    }
                }
            });
        }
    }
    
    function _edit(proid,proname,fileid,filename) {
        $('#profile').attr('style', 'display:block');
        $("#file_name").html(filename);
        $("#profile_name").val(proname);
        $("#id_profile").val(proid);
        $("#id_file").val(fileid);
    }
    
    function _delete(id) {
        if (confirm("Bạn có chắc xóa?")) {
            $.ajax({
                url:"{site_url()}vattu/nhacungcap/delprofile",
                type : "POST",
                data:  { id : id },
                dataType : 'JSON',
                success:function(data)
                {
                    if(data.code == '100') {
                        window.location = "{site_url()}vattu/nhacungcap/profile?id={$id}&name={$name}";
                    }
                }
            });
        }
    }
    </script>
{/block}
{block name=body}
    <div class="main-content">
        <div class="main-content-inner">
            <!--PATH BEGINS-->
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li><i class="ace-icon fa fa-home home-icon"></i><a href="#">{$languages.url_1}</a></li>
                    <li class="active">{$languages.url_2}</li>
                </ul>
                <div class="nav-search" id="nav-search">
                    <form class="form-search">
                        <span class="input-icon"><input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" /><i class="ace-icon fa fa-search nav-search-icon"></i></span>
                    </form>
                </div>
            </div>
            <!--PATH ENDS-->
            <div class="page-content">
                <div class="row">
                    <div class="col-xs-12">
                        <!-- PAGE CONTENT BEGINS -->
                        <h3 class="header smaller lighter blue">Thông tin nhà cung cấp {$name}</h3>
                        <div class="clearfix">
                            <div class="pull-left tableTools-container"></div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12">
                                <div class="pull-left">
                                    <button class="btn btn-xs btn-primary" onclick="_add()"><i class="ace-icon fa fa-plus"></i> {$languages.button_add}</button>
                                </div>
                            </div>
                        </div>
                        <div class="space-2"></div>
                        <table id="table" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Tên Profile</th>
                                    <th>File Đính Kèm</th>
                                    <th>Thao Tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                {if $profile}
                                {foreach from=$profile item=pro}
                                    <tr>
                                        <td>{$pro['profile_name']}</td>
                                        {if $pro['file_id'] eq 0}
                                        <td></td>
                                        {else if}
                                            <td><a href="{site_url()}vattu/nhacungcap/getfile?id={$pro['file_id']}">File Đính Kèm</a></td>
                                        {/if}
                                        <td>
                                            <button class="btn btn-xs btn-info" onclick="_edit({$pro['profile_id']},'{$pro['profile_name']}', {$pro['file_id']}, '{$pro['path_file']}')" data-toggle="tooltip" title="Sửa"><i class="ace-icon fa fa-pencil bigger-120"></i></button> 
                                            <button class="btn btn-xs btn-danger" onclick="_delete({$pro['profile_id']});" data-toggle="tooltip" title="Xóa"><i class="ace-icon fa fa-trash-o bigger-120"></i></button>
                                        </td>
                                    </tr>
                                {/foreach}
                                {else}
                                    <tr><td colspan="3" style="text-align:center;">Không có dữ liệu</td></tr>
                                {/if}
                            </tbody>
                        </table>
                        <!-- PAGE CONTENT ENDS -->
                        <div class="clearfix">
                            <div class="pull-left tableTools-container"></div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div id="profile">
                                    <h3 class="header smaller lighter blue">Thêm thông tin nhà cung cấp</h3>
                                    <form id="from_profile" method="post">
                                        <input type="hidden" id="id_ncc" name="id_ncc" value="{$id}" />   
                                        <input type="hidden" id="id_profile" name="id_profile" />   
                                    <div class="form-group">
                                        <label>Tên Hồ Sơ</label>
                                        <input class="form-control" type="text" id="profile_name" name="profile_name" />
                                    </div>
                                    <div class="form-group">     
                                        <label id="file_name" name="file_name"></label> <br/>
                                        <label>File đính kèm</label>
                                        <input type="hidden" id="id_file" name="id_file" value="" />   
                                        <input type="file" id="files" name="files" />         
                                    </div>
                                    </form>  
                                    <div>
                                        <button class="btn btn-info" id="upload" onclick="_save();"><i class="ace-icon fa fa-cloud-upload bigger-110"></i>Lưu</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
{/block}