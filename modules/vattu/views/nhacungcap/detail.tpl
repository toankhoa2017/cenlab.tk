{* Extend our master template *}
{extends file="master.tpl"}
{block name=css}
    <link rel="stylesheet" href="{site_url()}assets/css/jquery-confirm.css" />
    <link rel="stylesheet" href="{site_url()}assets/css/stylevth.css" />
    <link rel="stylesheet" href="{$assets_path}css/chosen.min.css" />
  
    <style>
        #profile {
            display: none;
        }
    </style>
{/block}
{block name=script}
    <div class="modal fade" id="modal_form" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title"></h3>
                </div>
                <form id="form" method="post">
                    <div class="modal-body form">
                         <div class="form-group">                             
                            <input type="hidden" id="id_ncc" name="id_ncc" value="{$id}" />
                            <label>Loại Sản Phẩm </label>
                            <select class="chosen-select form-control" id="loaisp" name="loaisp" >
                                <option value="0">Chọn loại sản phẩm</option>
                                {foreach from=$loaisp item=loai}
                                    <option value="{$loai->loai_id}">{$loai->loai_name}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div class="form-group">     
                            
                            <label>Sản Phẩm</label>
                            <select class="chosen-select form-control" id="sanpham" name="sanpham">

                            </select>        
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnSave" onclick="_save()" class="btn btn-xs btn-primary">{$languages.button_success_add}</button>
                        <button type="button" id="btnSua" onclick="_sua_oke()" class="btn btn-xs btn-primary">{$languages.button_success_update}</button>
                        <button type="button" class="btn btn-xs btn-danger" data-dismiss="modal">{$languages.button_cancel}</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    
    <script src="{$assets_path}plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="{$assets_path}plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script src="{site_url()}assets/js/sweetalert.min.js"></script>
    <script src="{site_url()}assets/js/jquery-confirm.js"></script>
    <script src="{$assets_path}js/chosen.jquery.min.js"></script>
    <script type="text/javascript">
    $(document).ready(function () {
        _load({$id});
    });
    function _add() {
        $('#form')[0].reset(); // reset form on modals
        $('#loaisp').trigger("chosen:updated");
        $('#sanpham').trigger("chosen:updated");
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string
        $('#modal_form').modal('show'); // show bootstrap modal
        $('.modal-title').text('Thêm sản phẩm mới'); // Set Title to Bootstrap modal title
        $("#btnSua").hide();
        $("#btnSave").show();
    }
    function _save() {
        var kt = true;
        if($('#loaisp').val() == '0'){
            swal("Thông báo", "Bạn phải chọn loại sản phẩm", "warning");
            kt = false;
        }
        else if($('#sanpham').val() == '0'){
            swal("Thông báo", "Bạn Phải chọn sản phẩm", "warning");
            kt = false;
        }
        if(kt == true) {
            $.confirm({
                title: '{$languages.confirm_title}',
                content: 'Bạn muốn thêm sản phẩm này',
                icon: 'fa fa-question',
                theme: 'modern',
                closeIcon: true,
                autoClose: 'cancel|10000',
                animation: 'scale',
                type: 'orange',
                buttons: {
                    '{$languages.confirm_button_yes}': {
                        btnClass: 'btn-primary',
                        action: function () {
                            $.ajax({
                                url: "{site_url()}vattu/nhacungcap/addspncc",
                                type: "POST",
                                data: {
                                    id_ncc : $("#id_ncc").val(),
                                    id_loai : $("#loaisp").val(),
                                    sanpham : $("#sanpham").val(),
                                },
                                dataType: "JSON",
                                success: function (data) {
                                    if (data.code == '100') {
                                        reload_table();
                                        $("#modal_form").modal("hide");
                                        swal("Thông Báo", "Thêm sản phẩm thành công", "success");
                                    } else {
                                        swal("Thông Báo", "Thất Bại", "error");
                                    }
                                }
                            });
                        }
                    },
                    cancel: {
                        text: '{$languages.confirm_button_no}',
                        btnClass: 'btn-danger',
                        action: function () {
                            // lets the user close the modal.
                        }
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
    function _xoa(idncc, id_sp){
        $.confirm({
            title: '{$languages.confirm_title}',
            content: 'Bạn muốn xóa sản phẩm này',
            icon: 'fa fa-question',
            theme: 'modern',
            closeIcon: true,
            autoClose: 'cancel|10000',
            animation: 'scale',
            type: 'orange',
            buttons: {
                '{$languages.confirm_button_yes}': {
                    btnClass: 'btn-primary',
                    action: function () {
                        $.ajax({
                            url: "{site_url()}vattu/nhacungcap/delspncc",
                            type: "POST",
                            data: {
                                id_ncc : idncc,
                                id_sp : id_sp
                            },
                            dataType: "JSON",
                            success: function (data) {
                                if (data.code == '100') {
                                    reload_table();
                                    swal("Thông Báo", "Xóa sản phẩm thành công", "success");
                                } else {
                                    swal("Thông Báo", "Thất Bại", "error");
                                }
                            }
                        });
                    }
                },
                cancel: {
                    text: '{$languages.confirm_button_no}',
                    btnClass: 'btn-danger',
                    action: function () {
                        // lets the user close the modal.
                    }
                }
            }
        });
    }
   
    $('#loaisp').change(function(){
        var id_loai = $('#loaisp').val();
        var id_ncc = $('#id_ncc').val();
        $.ajax({
            url: "{site_url()}vattu/nhacungcap/listsp",
            type: "POST",
            data: { id_loai : id_loai, id_ncc : id_ncc },
            success: function(data) {
                $('#sanpham').html(data);
                $('#sanpham').trigger("chosen:updated");
            }
        });
    });
{literal}
            $('.chosen-select').chosen({allow_single_deselect:true});
            //resize the chosen on window resize
jQuery(function($) {				
    if(!ace.vars['touch']) {
            $(window)
            .off('resize.chosen')
            .on('resize.chosen', function() {
                $('.chosen-select').each(function() {
                    var $this = $(this);
                    $this.next().css({'width': '100%'});
                })
            }).trigger('resize.chosen');
            //resize chosen on sidebar collapse/expand
            $(document).on('settings.ace.chosen', function(e, event_name, event_val) {
                if(event_name != 'sidebar_collapsed') return;
                $('.chosen-select').each(function() {
                    var $this = $(this);
                    $this.next().css({'width': '100%'});
                })
            });
    }		
});
{/literal}
    function _load(id) {
        table = $('#table_sp').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "{site_url()}vattu/nhacungcap/ajax_listspncc?id=" + id,
                "type": "POST"
            },
            "columnDefs": [
                {
                    "targets": [-1], //last column
                    "orderable": false, //set not orderable
                },
            ]
        });
    }
    function reload_table() {
        table.ajax.reload(null, false); //reload datatable ajax 
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
                        <h3 class="header smaller lighter blue">Các sản phẩm của nhà cung cấp {$name}</h3>
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
                        <table id="table_sp" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Loại sản phẩm</th>
                                    <th>Sản phẩm</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
{/block}