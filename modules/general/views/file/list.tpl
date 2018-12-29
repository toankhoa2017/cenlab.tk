{extends file="master_colorbox.tpl"}
{block name=body}
    <div class="main-content">
        <div class="main-content-inner">
            <div class="page-content">
                <div class="row">
                    <div class="col-xs-12">
                        <h4 class="header smaller lighter blue">{$languages.title}</h4>
                        <!-- PAGE CONTENT BEGINS -->
                        {*nội dung*}
                        <div class="row">
                            <div class="col-md-3">
                                <div class="widget-box widget-color-blue2">
                                    <div class="widget-header">
                                        <h4 class="widget-title lighter smaller">
                                            {$languages.title_1}
                                            <span class="smaller-80" style="position: absolute;top:1%;right:1%"><button class="btn btn-xs btn-danger" onclick="themthumuc()" data-toggle="modal" data-target="#myModal">{$languages.danhmuc_button_them}</button></span>
                                        </h4>
                                    </div>
                                    <div class="widget-body">
                                        <div class="widget-main padding-8">
                                            <div id="tree"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="myModal" class="modal fade" role="dialog">
                                <div class="modal-dialog">
                                    <!-- Modal content-->
                                    <div class="modal-content" style="color:black">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">{$languages.danhmuc_modal_title}</h4>
                                        </div>
                                        <div class="modal-body">
                                            <form id="formthumuc">
                                                <label>{$languages.danhmuc_modal_input_1}</label>
                                                <select class="chosen-select form-control" id="idparent">
                                                    <option value="0">{$languages.danhmuc_modal_chosen_input_1}</option>
                                                    {foreach from=$caythumuc key=k item=v}
                                                        <option value="{$k}">{$v}</option>
                                                    {/foreach}
                                                </select>
                                                <label>{$languages.danhmuc_modal_input_2}</label>
                                                <select class="chosen-select form-control" id="idforder">
                                                    <option value="0">{$languages.danhmuc_modal_chosen_input_2}</option>
                                                    {foreach from=$forder key=k item=v}
                                                        <option value="{$k}">{$v}</option>
                                                    {/foreach}
                                                </select>
                                                <label>{$languages.danhmuc_modal_input_3}</label>
                                                <input class="form-control" id="tenthumuc"/>
                                                <input type="hidden" id="idsua">
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" id="them" onclick="luuthumuc()" class="btn btn-xs btn-success">{$languages.danhmuc_modal_button_them}</button>
                                            <button type="button" id="sua" onclick="suathumuc1()" class="btn btn-xs btn-success">{$languages.danhmuc_modal_button_sua}</button>
                                            <button type="button" class="btn btn-xs btn-primary" data-dismiss="modal">{$languages.danhmuc_modal_button_thoat}</button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            {* upload file *}
                            <div id="uploadfile" class="modal fade" role="dialog">
                                <div class="modal-dialog modal-lg">
                                    <!-- Modal content-->
                                    <div class="modal-content" style="color:black">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">{$languages.danhsachfile_modal_title}</h4>
                                        </div>
                                        <div class="modal-body">
                                            <form id="form_multi_upload" class="dropzone well dz-clickable" style="padding-top:0px;margin-top:0px">
                                                <input type="hidden" name="ftype_id" id="ftype_id" />
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" onclick="save_multi_upload()" class="btn btn-xs btn-success">{$languages.danhsachfile_modal_button_them}</button>
                                            <button type="button" class="btn btn-xs btn-primary" data-dismiss="modal">{$languages.danhsachfile_modal_button_thoat}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="rename" class="modal fade" role="dialog">
                                <div class="modal-dialog">
                                    <!-- Modal content-->
                                    <div class="modal-content" style="color:black">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">{$languages.rename_title}</h4>
                                        </div>
                                        <div class="modal-body">
                                            <form id="formtaptin">
                                                <label>{$languages.rename_input1}</label>
                                                <input type="text" class="form-control" name="tentaptin" id="tentaptin" required/>
                                                <input type="hidden" name="mataptin" id="mataptin">
                                                <input type="hidden" name="thumuccha" id="thumuccha" />
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-xs btn-success" onclick="rename_oke()">{$languages.rename_button_luu}</button>
                                            <button type="button" class="btn btn-xs btn-primary" data-dismiss="modal">{$languages.rename_button_thoat}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="widget-box widget-color-blue2">
                                    <div class="widget-header">
                                        <h4 class="widget-title lighter smaller">
                                            {$languages.title_2}
                                            <span class="smaller-80" style="position: absolute;top:1%;right:1%" id="themfile"></span>
                                        </h4>
                                    </div>
                                    <div class="widget-body">
                                        <div class="widget-main padding-8">
                                            <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%" style="margin-bottom:0px">
                                                <thead>
                                                    <tr>
                                                        <th style="width:35%">{$languages.danhsachfile_table_1}</th>
                                                        <th style="width:35%">{$languages.danhsachfile_table_2}</th>
                                                        <th style="text-align: center;width:30%">{$languages.danhsachfile_table_3}</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="danhsachfile">

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="widget-box widget-color-blue2">
                                    <div class="widget-header">
                                        <h4 class="widget-title lighter smaller">
                                            {$languages.title_3} {$chon}
                                        </h4>
                                    </div>
                                    <div class="widget-body">
                                        <div class="widget-main padding-8">
                                            <p style="line-height:2">{$languages.rename_label_1}: <o id="tenfile"></o></p>
                                            <p style="line-height:2">{$languages.rename_label_2}: <o id="ngaytao"></o></p>
                                            <p style="line-height:2">{$languages.rename_label_3}: <o id="loaifile"></o></p>
                                            <p style="line-height:2">{$languages.rename_label_4}: <o id="dungluong"></o></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {*  end nội dung*}
                    </div>
                </div>
            </div>
        </div>
    </div>
{/block}
{block name=css}
    <link rel="stylesheet" href="{$assets_path}css/chosen.min.css" />
    <link rel="stylesheet" href="{site_url()}assets/css/jquery-confirm.css" />
    <link rel="stylesheet" href="{site_url()}assets/css/stylevth.css" />
    <link rel="stylesheet" href="{$assets_path}css/ace.min.css" />
    <link rel="stylesheet" href="{site_url()}assets/css/bootstrap-treeview.css" />
    <link rel="stylesheet" href="{site_url()}assets/css/dropzone.min.css" />
    <link rel="stylesheet" href="{site_url()}assets/css/basic.min.css" />
    <link rel="stylesheet" href="{$assets_path}css/bootstrap-datepicker3.min.css" />
    <style>
        ul.list-group{
            margin-bottom: 0px;
        }
        .label.arrowed-in-right, .label.arrowed-right{
            top:10px;
        }
    </style>
{/block}
{block name=script}
    <script src="{$assets_path}plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="{site_url()}assets/js/bootstrapvalidator.min.js" /></script>
<script src="{$assets_path}plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="{site_url()}assets/js/sweetalert.min.js"></script>
<script src="{site_url()}assets/js/jquery-confirm.js"></script>
<script src="{$assets_path}js/bootstrap-datepicker.min.js"></script>
<script src="{$assets_path}js/chosen.jquery.min.js"></script>
<script src="{site_url()}assets/js/dropzone.min.js"></script>
<script src="{site_url()}assets/js/bootstrap-treeview.js"></script>
<script>
            var site_url = '{site_url()}general/';
    
            $('#myModal').on('shown.bs.modal', function () {
                $('.chosen-select', this).chosen();
            });
            function save_multi_upload() {
                if ($(".dz-preview").html() === undefined || $(".dz-preview").html() == "") {
                    swal("{$languages.canhbao}", "{$languages.file_error}", "warning");
                } else {
                    file.processQueue();
                }
            }
            Dropzone.autoDiscover = false;
            var file = new Dropzone(".dropzone", {
                url: site_url + "file/upload_files",
                // maxFilesize: 20,  // maximum size to uplaod 
                parallelUploads: 100,
                dictDefaultMessage: "<div class='dz-default dz-message'><span><span class='bigger-150 bolder'><i class='ace-icon fa fa-caret-right red'></i> Files</span> to upload <span class='smaller-80 grey'>(kéo thả File hoặc kích vào đây)</span> <br><i class='upload-icon ace-icon fa fa-cloud-upload blue fa-3x'></i></span></div>",
                dictRemoveFile: "<span class='label label-lg label-danger arrowed-in arrowed-in-right'>{$languages.file_delete}</span>",
                method: "post",
                data: {
                    ftype_id: $("#ftype_id").val(),
                },
                // acceptedFiles:"image/*", // allow only images
                paramName: "userfile",
                // dictInvalidFileType:"Image files only allowed", // error message for other files on image only restriction 
                addRemoveLinks: true,
                autoProcessQueue: false
            });
            file.on("sending", function (a, b, c) {
                a.token = Math.random();
                c.append("token", a.token); //Random Token generated for every files 
            });
            file.on("complete", function (file1) {
                file.removeFile(file1);
                swal("{$languages.thanhcong}", "{$languages.upload_file_rename}", "success");
                table.destroy();
                _load(noidungtruyen);
                $("#uploadfile").modal("hide");
            });
            {literal}
            file.on('addedfile', function (files) {
                var ext = files.name.split('.').pop();
                if (ext == "pdf") {
                    $(files.previewElement).find(".dz-image img").attr({"src": "../assets/images/icons/pdf.png", "style": "object-fit: cover;width:100%"});
                } else if (ext.indexOf("doc") != -1 || ext.indexOf("docx") != -1) {
                    $(files.previewElement).find(".dz-image img").attr({"src": "../assets/images/icons/word.png", "style": "object-fit: cover;width:100%"});
                } else if (ext.indexOf("xls") != -1 || ext.indexOf("xlsx") != -1) {
                    $(files.previewElement).find(".dz-image img").attr({"src": "../assets/images/icons/excel.png", "style": "object-fit: cover;width:100%"});
                } else if (ext.indexOf("rar") != -1 || ext.indexOf("zip") != -1) {
                    $(files.previewElement).find(".dz-image img").attr({"src": "../assets/images/icons/rar.png", "style": "object-fit: cover;width:100%"});
                } else {
                    $(files.previewElement).find(".dz-image img").attr({"src": "../assets/images/icons/error.png", "style": "object-fit: cover;width:100%"});
                }
            });
            
            $('#uploadfile').on('hidden.bs.modal', function () {
                file.removeAllFiles();
            });
            //file.on("removedfile", function (a) {
            //    var token = a.token;
            //    $.ajax({
            //        type: "post",
            //        data: {token: token},
            //        url: site_url+"file/delete_files",
            //        cache: false,
            //        dataType: 'json',
            //        success: function (res) {
            //        }
            //    });
            //});
            //xử lý tập tin
            function themthumuc() {
                $('#formthumuc')[0].reset();
                $("#them").show();
                $("#sua").hide();
            }
            
            function loadinfomation(id) {
                $.ajax({
                    type: "POST",
                    url: site_url + "file/informationfile",
                    data: {
                        idfile: id,
                    },
                    datatype: "text",
                    success: function (data) {
                        var obj = $.parseJSON(data);
                        $("#tenfile").html('');
                        $("#tenfile").append(obj.file_name);
                        $("#ngaytao").html('');
                        $("#ngaytao").append(obj.file_date);
                        $("#loaifile").html('');
                        $("#loaifile").append(obj.loaifile);
                        $("#dungluong").html('');
                        $("#dungluong").append(obj.kichthuoc);
                        $('[data-toggle="tooltip"]').tooltip();
                    }
                });
            }
            function rename(id, name, idcha) {
                $("#tentaptin").val(name);
                $("#mataptin").val(id);
                $("#thumuccha").val(idcha);
                $("#rename").modal("show");
            }
            //end xử lý tập tin
            $(document).ready(function () {
                //$('.chosen-select').chosen();
                table = $('#table').DataTable({
                    
                });
                $("#sua").hide();
                loadthumuc();
            });
            
            function loaddanhsachfile(id) {
                table.destroy();
                _load(id);
            }
            {/literal}
            var noidungtruyen = "";
            function _check(id_file, ten_file) {
                if ({$nhansu_id} == "0") {
                    parent.$("#showhopdong").text(ten_file);
                    parent.$("#hopdong").val(id_file);
                    try {
                        parent.danhsach_file(id_file, ten_file);
                    } catch (err) { }
                    parent.jQuery.fn.colorbox.close();
                    return false;
                } else {
                    $.ajax({
                        type: "POST",
                        url: "{site_url()}general/file/get_link_file",
                        data: {
                            'file_id': id_file
                        },
                        success: function (data)
                        {
                            parent.$("#hopdong_{$nhansu_id}").val(id_file);
                            parent.$("#image_{$nhansu_id}").attr('src', data);
                            parent.jQuery.fn.colorbox.close();
                            return false;
                        }
                    });
                }
            }
            
                function _load(id_file_type_1) {
                noidungtruyen = id_file_type_1;
                table = $('#table').DataTable({
                    "processing": true,
                    "serverSide": true,
                    colResize: false,
                    autoWidth: false,
                    scrollX: false,
                    "language": {
                        "processing": "Đang Load Dữ Liệu...",
                    },
                    "order": [],
                    "ajax": {
                        "url": site_url + "file/listfile",
                        "data": {
                            'id_file_typee' : noidungtruyen,
                            'chon': '{$chon}'
                        },
                        "type": "POST",
                        "error": function (data)
                        {
                            console.log(data);
                        },
                    },
                    "columnDefs": [
                        {
                            "targets": [-1], //last column
                            "orderable": false, //set not orderable
                        },
                    ],
                    "fnDrawCallback": function (data) {
                        $(".paginate_button > a").on("focus", function () {
                            $(this).blur();
                        });
                        $('[data-toggle="tooltip"]').tooltip();
                    }
                });
            }
                function loadthumuc() {
                $.ajax({
                    type: "GET",
                    url: site_url + "file/danhsachfile_type",
                    dataType: "json",
                    success: function (response)
                    {
                        var dulieu = response;
                        $('#tree').treeview({
                            levels: 1,
                            data: dulieu,
                            onNodeSelected: function (event, node) {
                                loaddanhsachfile(node.id);
                                $("#themfile").html('');
                                $("#themfile").append('<button class="btn btn-xs btn-danger" data-id="@book.Id" data-toggle="modal" data-target="#uploadfile">{$languages.button_them}</button>')
                                $("#ftype_id").val(node.id);
                            },
                            onNodeUnselected: function (event, node) {
                                $("#themfile").html('');
                            }
                        });
                        $('[data-toggle="tooltip"]').tooltip();

                    }
                });
            }
            function rename_oke() {
                if ($("#tentaptin").val() != "") {
                    $.confirm({
                        title: '{$languages.xacnhan}',
                        content: '{$languages.xacnhan_rename}',
                        icon: 'fa fa-question',
                        theme: 'modern',
                        closeIcon: true,
                        autoClose: 'cancel|10000',
                        animation: 'scale',
                        type: 'orange',
                        buttons: {
                            '{$languages.co}': {
                                btnClass: 'btn-primary',
                                action: function () {
                                    $.ajax({
                                        type: "POST",
                                        url: site_url + "file/renamefile",
                                        data: {
                                            idfile: $("#mataptin").val(),
                                            tenfile: $("#tentaptin").val(),
                                            file_type: $("#thumuccha").val(),
                                        },
                                        datatype: "text",
                                        success: function (data) {
                                            if (data == 1) {
                                                table.destroy();
                                                _load(noidungtruyen);
                                                loadinfomation($("#mataptin").val());
                                                $('#formtaptin')[0].reset();
                                                $("#rename").modal("hide");
                                                swal("{$languages.thanhcong}", "{$languages.rename_success}", "success");
                                            } else {
                                                swal("{$languages.thatbai}", "{$languages.rename_error}", "error");
                                            }
                                        }
                                    });
                                }
                            },
                            cancel: {
                                text: '{$languages.khong}',
                                btnClass: 'btn-danger',
                                action: function () {
                                    // lets the user close the modal.
                                }
                            }
                        }
                    });
                } else {
                    swal("{$languages.canhbao}", "{$languages.rename_validation}", "warning");
                }
            }
            function luuthumuc() {
                if ($("#tenthumuc").val() == "") {
                    swal("{$languages.canhbao}", "{$languages.thumuc_validation}", "error");
                } else {
                    $.ajax({
                        type: "POST",
                        url: site_url + "file/themthumuc",
                        data: {
                            tenthumuc1: $("#tenthumuc").val(),
                            idparent1: $("#idparent").val(),
                            idforder: $("#idforder").val(),
                        },
                        datatype: "text",
                        success: function (data) {
                            if (data == 1) {
                                loadthumuc();
                                $('#myModal').modal('hide');
                                $('#formthumuc')[0].reset();
                                swal("{$languages.thanhcong}", "{$languages.themthumuc_success}", "success");
                            } else {
                                swal("{$languages.thatbai}", "{$languages.themthumuc_error}", "error");
                            }
                        }
                    });
                }
            }
            function suathumuc(id) {
                $.ajax({
                    type: "POST",
                    url: site_url + "file/suathumuc",
                    data: {
                        idthumuc: id,
                    },
                    datatype: "text",
                    success: function (data) {
                            var obj = $.parseJSON(data);
                            $("#idparent").val(obj.ftype_idparent).trigger("chosen:updated");
                            $("#tenthumuc").val(obj.ftype_name);
                            $("#idsua").val(obj.ftype_id);
                            $("#idforder").val(obj.file_forder_id).trigger("chosen:updated");
                            $("#them").hide();
                            $("#sua").show();
                            $("#myModal").modal("show");
                    }
                });
            }
            function suathumuc1() {
                if ($("#tenthumuc").val() == "") {
                    swal("{$languages.canhbao}", "{$languages.thumuc_validation}", "error");
                } else {
                    $.ajax({
                        type: "POST",
                        url: site_url + "file/xuly_suathumuc",
                        data: {
                            tenthumuc2: $("#tenthumuc").val(),
                            idparent2: $("#idparent").val(),
                            idforder2: $("#idforder").val(),
                            id2: $("#idsua").val(),
                        },
                        datatype: "text",
                        success: function (data) {
                            if (data == "1") {
                                loadthumuc();
                                swal("{$languages.thanhcong}", "{$languages.suathumuc_success}", "success");
                                $('#myModal').modal('hide');
                                $('#formthumuc')[0].reset();
                                $('#them').show();
                                $('#sua').hide();
                            } else {
                                swal("{$languages.thatbai}", "{$languages.thumuctontai}", "error");
                            }
                        }
                    });
                }
            }
            function xoathumuc(id) {
                $.confirm({
                    title: '{$languages.xacnhan}',
                    content: '{$languages.xacnhan_thumuc}',
                    icon: 'fa fa-question',
                    theme: 'modern',
                    closeIcon: true,
                    autoClose: 'cancel|10000',
                    animation: 'scale',
                    type: 'orange',
                    buttons: {
                        '{$languages.co}': {
                            btnClass: 'btn-primary',
                            action: function () {
                                $.ajax({
                                    type: "POST",
                                    url: site_url + "file/xoathumuc",
                                    data: {
                                        idthumuc: id,
                                    },
                                    datatype: "text",
                                    success: function (data) {
                                        loadthumuc();
                                        swal("{$languages.thanhcong}", "{$languages.xoathumuc_success}", "success");
                                    }
                                });
                            }
                        },
                        cancel: {
                            text: '{$languages.khong}',
                            btnClass: 'btn-danger',
                            action: function () {
                                // lets the user close the modal.
                            }
                        }
                    }
                });
            }
            //end xử lý thư mục

            // xử lý tập tin
            function xoafile(id) {
                $.confirm({
                    title: '{$languages.xacnhan}',
                    content: '{$languages.xacnhan_xoafile}',
                    icon: 'fa fa-question',
                    theme: 'modern',
                    closeIcon: true,
                    autoClose: 'cancel|10000',
                    animation: 'scale',
                    type: 'orange',
                    buttons: {
                        '{$languages.co}': {
                            btnClass: 'btn-primary',
                            action: function () {
                                $.ajax({
                                    type: "POST",
                                    url: site_url + "file/xoafile",
                                    data: {
                                        idfile: id,
                                    },
                                    datatype: "text",
                                    success: function (data) {
                                        table.destroy();
                                        _load(noidungtruyen);
                                        swal("{$languages.thanhcong}", "{$languages.xoafile_success}", "success");
                                    }
                                });
                            }
                        },
                        cancel: {
                            text: '{$languages.khong}',
                            btnClass: 'btn-danger',
                            action: function () {
                                // lets the user close the modal.
                            }
                        }
                    }
                });
            }
</script>

{/block}
