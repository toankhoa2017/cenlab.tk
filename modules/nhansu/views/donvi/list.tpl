{* Extend our master template *}
{extends file="master.tpl"}
{block name=css}
<link rel="stylesheet" href="{site_url()}assets/css/jquery-confirm.css" />
<link rel="stylesheet" href="{site_url()}assets/css/stylevth.css" />
<style>
.updateSL {
   display: none;
}
.editable-label-text {
   width: 80%;
}
</style>
{/block}
{block name=script}
{if $privcheck.write OR $privcheck.update}
<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title"></h3>
            </div>
            <form id="form" method="post">
                <input type="hidden" value="OK" id="isSent" name="isSent" />
                <input type="hidden" value="{$id}" id="parent" name="parent" />
                <div class="modal-body form">
                    <div class="form-group">
                        <label>{$languages.tendonvi}:</label>
                        <input class="form-control" type="text" id="name" name="name" />
                        <input type="hidden" id="id_sua" name="id_sua">
                    </div>
                    <div class="control-group">
                        <label>{$languages.loaidonvi}:</label>
                        <div class="radio">
                            <label>
                                <input name="form-field-radio" id="loai1" type="radio" class="ace" checked/>
                                <span class="lbl" onclick="chon(1)"> {$languages.loaidonvi_1}</span>
                            </label>
                            <label>
                                <input name="form-field-radio" id="loai2" type="radio" class="ace" />
                                <span class="lbl" onclick="chon(2)"> {$languages.loaidonvi_2}</span>
                            </label>
                            <label>
                                <input name="form-field-radio" id="loai3" type="radio" class="ace" />
                                <span class="lbl" onclick="chon(3)"> {$languages.loaidonvi_3}</span>
                            </label>
                        </div>
                        <input type="hidden" id="loai_donvi" name="loai_donvi" value="1">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnSave" onclick="_save()" class="btn btn-xs btn-primary">{$languages.button_them}</button>
                    <button type="button" id="btnSua" onclick="_sua_submit()" class="btn btn-xs btn-primary">{$languages.button_sua}</button>
                    <button type="button" class="btn btn-xs btn-danger" data-dismiss="modal">{$languages.button_thoat}</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
{/if}
<script src="{$assets_path}plugins/datatables/jquery.dataTables.min.js"></script>
<script src="{$assets_path}plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="{site_url()}assets/js/sweetalert.min.js"></script>
<script src="{site_url()}assets/js/jquery-confirm.js"></script>
<script type="text/javascript">
$(document).ready(function () {
    _load({$id});
});
$('#modal_form').on('shown.bs.modal', function () {
    $('#name').focus();
});

$('.table').on('click','.addchucvu', function() {
    var iddv = $(this).parents('.table tr').find('.addcv').data('id');
    var row = '<tr><td><select class="form-control" id="chucvu_'+iddv+'" name="chucvu"></select></td><td><input type="text" id="soluong_'+iddv+'" name="soluong" /></td><td><button class="btn btn-warning btn-xs btnumail insert" value="'+iddv+'"><i class="ace-icon fa fa-floppy-o bigger-160"></i></button></td></tr>'
    $(this).parents('#table tr').find('.table').append(row);
    $.ajax({
        url:  "{site_url()}nhansu/chucvu/dschucvu",
        type: "POST",
        data: { id : iddv },	
        dataType: "JSON",	
        success: function(data) {
                $('#chucvu_'+iddv).html(data.a);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('Error adding / update data');
        }
    });
    $(this).parents('#table tr').find('#themcv_'+iddv).attr("style", "display:none");
});
$('.table').on('click','.insert', function() {
    var id_donvi = this.value;
    var idcv = $(this).parents('.table').find('#chucvu_'+id_donvi).val();
    if(idcv == '') {
        alert("bạn phải chọn chức vụ");
    }
    else {
        $(this).parents('#table').find('#themcv_'+id_donvi).attr("style", "display:block");
        var tencv = $(this).parents('.table').find('#chucvu_'+id_donvi+' option:selected').text();
        var soluong = $(this).parents('.table').find('#soluong_'+id_donvi).val();
        $(this).parents('tr tr').attr("id", "cv_" + id_donvi + "_" + idcv);        
        var $lbl1 = $('<div class="tencv">'+tencv+'</div>');
        var $lbl2 = $('<div class="sluongcv" data-id="'+idcv+'">'+soluong+'</div>');
        var $lbl3 = $('<button class="btn btn-minier btn-danger delcv"><i class="ace-icon fa fa-trash-o bigger-110"></i></button>');
        $(this).parents('tr tr').find('#chucvu_'+id_donvi).replaceWith($lbl1);
        $(this).parents('tr tr').find('#soluong_'+id_donvi).replaceWith($lbl2);
        $(this).parents('tr tr').find('button.insert').replaceWith($lbl3);
        $.ajax({
            url: "{site_url()}nhansu/chucvu/insertDV_CV",
            type: "POST",
            data: { id_donvi : id_donvi, id_chucvu : idcv, soluong : soluong },
            dataType: "JSON",
            success: function(data) {
                if(data.code == "100") {
                    //reload_table();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error adding / update data');
            }
        });
    }
});
$('.table').on('click','.sluongcv', function() {
    var id_donvi = $(this).parents('#table tr').find('.tendv').data('id');
    $(this).parents('tr tr').find('.updateSL').attr("style", "display:inline");
    var idcv = $(this).data('id');
    var $lbl =  $(this), value = $lbl.text();
    var $txt = $('<input type="text" id="txtsoluong_'+id_donvi+idcv+'" class="editable-label-text" value="'+value+'" />');
    $lbl.replaceWith($txt);	
});
$('.table').on('click','.updateSL',function() {
	var id_chucvu = this.value;
	var id_donvi = $(this).parents('#table tr').find('.tendv').data('id');
	var soluong = $(this).parents('tr tr').find('#txtsoluong_'+id_donvi+id_chucvu).val();
	var $lbl = $('<div class="sluongcv" data-id="'+id_chucvu+'">'+soluong+'</div>');
	$(this).parents('tr tr').find('#txtsoluong_'+id_donvi+id_chucvu).replaceWith($lbl);
	$(this).attr("style", "display:none");
	$.ajax({
            url: "{site_url()}nhansu/chucvu/updataDV_CV",
            type: "POST",
            data: { id_donvi : id_donvi, id_chucvu : id_chucvu,  soluong : soluong },
            dataType: "JSON",
            success: function(data) {
                if(data.code == "100") {}
            },	
            error: function (jqXHR, textStatus, errorThrown) {
                alert('không thể cập nhật');
            }
	});
});
$('.table').on('click','.delcv', function() {
    var id_donvi = $(this).parents('#table tr').find('.tendv').data('id');
    var id_chucvu = $(this).parents('tr tr').find('.sluongcv').data('id');
    if(confirm("Bạn chắc chắn muốn xóa")) {
        $.ajax({
            url: "{site_url()}nhansu/chucvu/delcv",
            type: "POST",
            data: { id_donvi : id_donvi, id_chucvu : id_chucvu },
            dataType: "JSON",
            success: function(data) {
                if(data.code == '100') {
                    $('.table tr#cv_'+id_donvi+'_'+id_chucvu).remove();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('không thể xóa')
            }
        });
    }
});
function _load(id) {
    table = $('#table').DataTable({
        "processing": true,
        "language": {
            "processing": "Đang Load dữ liệu...",
        },
        "serverSide": true,
        //"paging": true,
        //"lengthChange": false,
        //"searching": false,
        //"ordering": true,
        //"info": true,
        //"autoWidth": true,
        "order": [],
        "ajax": {
            "url": "{site_url()}nhansu/donvi/ajax_list?id=" + id,
            "type": "POST"
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
function chon(id) {
    $("#loai_donvi").val(id);
}
function _add() {
    $("input[name='form-field-radio']").removeAttr('checked');
    $("input[id='loai1']").attr("checked", true);
    $("#loai_donvi").val('1');
    $("#btnSua").hide();
    $("#btnSave").show();
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('{$languages.them_title}'); // Set Title to Bootstrap modal title
}
function _save() {
    if ($("#name").val() == "") {
        swal("{$languages.canhbao}", "{$languages.tendonvi_validation}", "warning");
    } else {
        $.ajax({
            url: "{site_url()}nhansu/donvi/ajax_add",
            type: "POST",
            data: $('#form').serialize(),
            dataType: "JSON",
            success: function (data) {
                //if success close modal and reload ajax table
                if (data.status == 'denied') {
                    swal("{$languages.thatbai}", "{$languages.tendonvi_error}", "error");
                } else {
                    $('#modal_form').modal('hide');
                    reload_table();
                    swal("{$languages.thanhcong}", "{$languages.them_success}", "success");
                }
            }
        });
    }
}
function _sua(id, name, loai) {
    $("input[name='form-field-radio']").removeAttr('checked');
    $("input[id='loai"+loai+"']").attr("checked", true);
    $("#loai_donvi").val(loai);
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('{$languages.sua_title}');
    $("#name").val(name);
    $("#id_sua").val(id);
    $("#btnSua").show();
    $("#btnSave").hide();
}
function _sua_submit() {
    if ($("#name").val() == "") {
        swal("{$languages.canhbao}", "{$languages.tendonvi_validation}", "warning");
    } else {
        $.confirm({
            title: '{$languages.xacnhan}',
            content: '{$languages.xacnhan_sua}',
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
                            url: "{site_url()}nhansu/donvi/ajax_edit",
                            data: {
                                iddonvisua: $("#id_sua").val(),
                                namedonvisua: $("#name").val(),
                                loai_donvi: $("#loai_donvi").val()
                            },
                            datatype: "text",
                            success: function (data) {
                                if (data == 1) {
                                    reload_table();
                                    $("#modal_form").modal("hide");
                                    swal("{$languages.thanhcong}", "{$languages.sua_success}", "success");
                                } else {
                                    swal("{$languages.thatbai}", "{$languages.tendonvi_error}", "error");
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
    }
}
function _xoa(id, name) {
    $.confirm({
        title: 'Xác nhận',
        content: 'Xóa đơn vị ' + name + '?',
        icon: 'fa fa-question',
        theme: 'modern',
        closeIcon: true,
        autoClose: 'cancel|10000',
        animation: 'scale',
        type: 'orange',
        buttons: {
            'Có': {
                btnClass: 'btn-primary',
                action: function () {
                    $.ajax({
                        type: "POST",
                        url: "{site_url()}nhansu/donvi/delete",
                        data: {
                            iddonvi: id
                        },
                        datatype: "text",
                        success: function (data) {
                            if (data == 1) {
                                reload_table();
                                swal("Thành Công!", "Bạn đã xóa đơn vị thành công", "success");
                            } else {
                                swal("Thất Bại!", "Có Lỗi Sảy Ra", "error");
                            }
                        }
                    });
                }
            },
            cancel: {
                text: 'Không',
                btnClass: 'btn-danger',
                action: function () {
                    // lets the user close the modal.
                }
            }
        }
    });
}
$('#table').on('click','.view', function() {
	var id_donvi = $(this).parents('#table tr').find('.tendv').data('id');
	var ten_donvi = $(this).parents('#table tr').find('.tendv').text();
	window.location = '{site_url()}nhansu/donvi/view?id=' + id_donvi + '&ten=' + ten_donvi;
});
function reload_table() {
    table.ajax.reload(null, false); //reload datatable ajax 
}
function _review(id) {
    window.location = '{base_url()}nhansu/donvi/chitiet/' + id;
}
</script>
{/block}
{block name=body}
<div class="main-content">
    <div class="main-content-inner">
        <!--PATH BEGINS-->
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li><i class="ace-icon fa fa-home home-icon"></i><a href="{site_url()}nhansu/donvi">{$languages.url_1}</a></li>
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
                    <h3 class="header smaller lighter blue">{$languages.title}</h3>
                    <div>
                        {assign var="stt" value=0}
                        {foreach $listUps as $path}
                            {assign var="stt" value={$stt} + 1}
                            {if $stt < count($listUps)}
                                <a href="{site_url()}nhansu/donvi?id={$path['id']}">{$path['name']}</a>
                                &rightarrow;
                            {else}
                                {$path['name']}
                            {/if}
                        {/foreach}
                    </div>
                    <div class="clearfix">
                        <div class="pull-left tableTools-container"></div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="pull-left">
                                {if $privcheck.write}<button class="btn btn-xs btn-primary" onclick="_add()"><i class="ace-icon fa fa-plus"></i> {$languages.button_create}</button>{/if}
                                <button class="btn btn-xs btn-warning" onclick="reload_table()"><i class="ace-icon fa fa-refresh"></i> {$languages.button_reload}</button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive-sm">
                        <table id="table" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>{$languages.table_1}</th>
                                    <th>Danh sách chức vụ</th>
                                    <th>{$languages.table_2}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <!-- PAGE CONTENT ENDS -->
                </div>
            </div>
        </div>
    </div>
</div>
{/block}