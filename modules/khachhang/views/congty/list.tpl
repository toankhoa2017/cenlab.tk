{* Extend our master template *}
{extends file="master.tpl"}
{block name=css}
    <link rel="stylesheet" href="{site_url()}assets/css/jquery-confirm.css" />
    <link rel="stylesheet" href="{site_url()}assets/css/stylevth.css" />
    <link rel="stylesheet" href="{site_url()}assets/css/bootstrapValidator.min.css" />
{/block}
{block name=script}
<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title"></h3>
            </div>
            <form id="form" method="post">
                <input type="hidden" value="{$id}" id="parent" name="parent" />
                <div class="modal-body form">
                    <div class="form-group">
                        <label>{$languages.name}:</label>
                        <input class="form-control" type="text" id="name" name="name" placeholder="{$languages.nhap}{$languages.name}"/>
                        <input type="hidden" id="id_sua" name="id_sua">
                    </div>
                    <div class="form-group">
                        <label>{$languages.address}</label>
                        <textarea class="form-control" id="address" name="address" placeholder="{$languages.nhap}{$languages.address}"></textarea>
                    </div>
                    <div class="form-group" id="check_phone">
                        <label>{$languages.phone}</label>
                        <input class="form-control" type="text" id="phone" name="phone" placeholder="{$languages.nhap}{$languages.phone}"/>
                    </div>
                    <div class="form-group">
                        <label>{$languages.fax}</label>
                        <input class="form-control" type="text" id="fax" name="fax"  placeholder="{$languages.nhap}{$languages.fax}"/>
                    </div>
                    <div class="form-group" id="check_email">
                        <label>{$languages.email}</label>
                        <input class="form-control" type="email" id="email" name="email"  placeholder="{$languages.nhap}{$languages.email}"/>
                    </div>
                    <div class="form-group" id="check_tax">
                        <label>{$languages.tax}</label>
                        <input class="form-control" type="text" id="tax" name="tax"  placeholder="{$languages.nhap}{$languages.tax}"/>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="btnSave" class="btn btn-xs btn-primary">{$languages.button_them}</button>
                    <button type="button" id="btnSua" onclick="_sua_oke()" class="btn btn-xs btn-primary">{$languages.button_sua}</button>
                    <button type="button" class="btn btn-xs btn-danger" data-dismiss="modal">{$languages.button_thoat}</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->
<script src="{$assets_path}plugins/datatables/jquery.dataTables.min.js"></script>
<script src="{$assets_path}plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="{site_url()}assets/js/bootstrapvalidator.min.js" /></script>
<script src="{site_url()}assets/js/sweetalert.min.js"></script>
<script src="{site_url()}assets/js/jquery-confirm.js"></script>
<script type="text/javascript">
$(document).ready(function () {
    _load();
});

var phone_cache = "";
var check_phone = 0;
$("#phone").on("blur", function () {
    if ($("#phone").val() != phone_cache) {
        $.ajax({
            url: "{site_url()}khachhang/check_phone",
            type: "POST",
            data: {
                'congty_phone': $("#phone").val(),
                'congty_id': $("#id_sua").val(),
            },
            dataType: "JSON",
            success: function (data) {
                phone_cache = $("#phone").val();
                if (data.status == 'denied') {
                    check_phone = 0;
                    $("#check_phone").removeClass("has-success");
                    $("#check_phone>.form-control-feedback").remove();
                    $("#check_phone").addClass("has-error");
                    swal("{$languages.canhbao}", "{$languages.check_phone}", "warning");
                    $("#btnSave").attr("disabled", true);
                    $("#btnSua").attr("disabled", true);
                } else {
                    check_phone = 1;
                    if (check_email == 1 && check_phone == 1 && check_tax == 1) {
                        $("#btnSave").attr("disabled", false);
                        $("#btnSua").attr("disabled", false);
                    } else {
                        $("#btnSave").attr("disabled", true);
                        $("#btnSua").attr("disabled", true);
                    }
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error adding / update data');
            }
        });
    }
});
var email_cache = "";
var check_email = 0;
$("#email").on("blur", function () {
    if ($("#email").val() != email_cache) {
        $.ajax({
            url: "{site_url()}khachhang/check_email",
            type: "POST",
            data: {
                'congty_email': $("#email").val(),
                'congty_id': $("#id_sua").val(),
            },
            dataType: "JSON",
            success: function (data) {
                email_cache = $("#email").val();
                if (data.status == 'denied') {
                    check_email = 0;
                    $("#email").focus();
                    $("#check_email").removeClass("has-success");
                    $("#check_email>.form-control-feedback").remove();
                    $("#check_email").addClass("has-error");
                    swal("{$languages.canhbao}", "{$languages.check_email}", "warning");
                    $("#btnSave").attr("disabled", true);
                    $("#btnSua").attr("disabled", true);
                } else {
                    check_email = 1;
                    if (check_email == 1 && check_phone == 1 && check_tax == 1) {
                        $("#btnSave").attr("disabled", false);
                        $("#btnSua").attr("disabled", false);
                    } else {
                        $("#btnSave").attr("disabled", true);
                        $("#btnSua").attr("disabled", true);
                    }
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error adding / update data');
            }
        });
    }
});
var tax_cache = "";
var check_tax = 0;
$("#tax").on("blur", function () {
    if ($("#tax").val() != tax_cache) {
        $.ajax({
            url: "{site_url()}khachhang/check_tax",
            type: "POST",
            data: {
                'congty_tax': $("#tax").val(),
                'congty_id': $("#id_sua").val(),
            },
            dataType: "JSON",
            success: function (data) {
                tax_cache = $("#tax").val();
                if (data.status == 'denied') {
                    check_fax = 0;
                    $("#tax").focus();
                    $("#check_tax").removeClass("has-success");
                    $("#check_tax>.form-control-feedback").remove();
                    $("#check_tax").addClass("has-error");
                    swal("{$languages.canhbao}", "{$languages.check_tax}", "warning");
                    $("#btnSave").attr("disabled", true);
                    $("#btnSua").attr("disabled", true);
                } else {
                    check_tax = 1;
                    if (check_email == 1 && check_phone == 1 && check_tax == 1) {
                        $("#btnSave").attr("disabled", false);
                        $("#btnSua").attr("disabled", false);
                    } else {
                        $("#btnSave").attr("disabled", true);
                        $("#btnSua").attr("disabled", true);
                    }
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error adding / update data');
            }
        });
    }
});
function _load() {
    table = $('#table').DataTable({
        "processing": true,
        "serverSide": true,
        //"paging": true,
        //"lengthChange": false,
        //"searching": false,
        //"ordering": true,
        //"info": true,
        //"autoWidth": true,
        "order": [],

        "ajax": {
            "url": "{site_url()}khachhang/ajax_list",
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
function _add() {
    check_fax = 0;
    check_phone = 0;
    check_fax = 0;
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('{$languages.modal_title_them}'); // Set Title to Bootstrap modal title
    $("#btnSua").hide();
    $("#btnSave").show();
}
$(document).ready(function () {
    $('form#form').bootstrapValidator({
        feedbackIcons: {
            valid: 'fa fa-check',
            invalid: 'fa fa-times',
            validating: 'glyphicon glyphicon-refresh'
        },
        submitHandler: function (e) {
            if (check_email == 1 && check_phone == 1 && check_tax == 1) {
                $.ajax({
                    url: "{site_url()}khachhang/ajax_add",
                    type: "POST",
                    data: $('#form').serialize(),
                    dataType: "JSON",
                    success: function (data) {
                        console.log(data);
                        //if success close modal and reload ajax table
                        if (data.status == 'denied') {
                            swal("{$languages.thatbai}", "{$languages.error}", "error");
                        } else {
                            $('#modal_form').modal('hide');
                            reload_table();
                            swal("{$languages.thanhcong}", "{$languages.them}", "success");
                        }
                    }
                });
            } else {
                $("#btnSave").attr("disabled", true);
                swal("{$languages.canhbao}", "{$languages.error}", "warning");
            }
        },
        fields: {
            name: {
                validators: {
                    notEmpty: {
                        message: '{$languages.validation_name}'//can\'t
                    },
                    trigger: 'change keyup',
                }

            },
            address: {
                validators: {
                    trigger: 'change keyup',
                    notEmpty: {
                        message: '{$languages.validation_address}'//can\'t
                    },
                    trigger: 'change keyup',
                }
            },
            tax: {
                validators: {
                    notEmpty: {
                        message: '{$languages.validation_tax}'//can\'t
                    },
                    trigger: 'change keyup',
                    stringLength: {
                        min: 10,
                        max: 30,
                        message: '{$languages.validation_tax_error}'
                    },
                    regexp: {
                        regexp: /^[0-9]+$/,
                        message: '{$languages.validation_tax_error}'
                    }
                }
            },
            phone: {
                validators: {
                    notEmpty: {
                        message: '{$languages.validation_phone}'//can\'t
                    },
                    trigger: 'change keyup',
                }
            },
            fax: {
                validators: {
                    notEmpty: {
                        message: '{$languages.validation_fax}'//can\'t
                    },
                    trigger: 'change keyup',
                }
            },
            email: {
                validators: {
                    emailAddress: {
                        message: '{$languages.validation_email}'
                    },
                    notEmpty: {
                        message: '{$languages.validation_email_error}'//can\'t
                    },
                    stringLength: {
                        min: 0,
                    },
                    trigger: 'change keyup',
                }
            },
        }
    })
})
function _xoa(id) {
    $.confirm({
        title: '{$languages.xacnhan}',
        content: '{$languages.xoa_title}',
        icon: 'fa fa-question',
        theme: 'modern',
        closeIcon: true,
        autoClose: 'cancel|10000',
        animation: 'scale',
        type: 'orange',
        buttons: {
            'Yes': {
                btnClass: 'btn-primary',
                action: function () {
                    $.ajax({
                        type: "POST",
                        url: "{site_url()}khachhang/xoacongty",
                        data: {
                            idcongtyxoa: id
                        },
                        datatype: "text",
                        success: function (data) {
                            if (data == 1) {
                                reload_table();
                                swal("{$languages.thanhcong}", "{$languages.xoa}", "success");
                            } else {
                                swal("{$languages.thatbai}", "{$languages.error}", "error");
                            }
                        }
                    });
                }
            },
            cancel: {
                text: 'No',
                btnClass: 'btn-danger',
                action: function () {
                    // lets the user close the modal.
                }
            }
        }
    });
}
var namecu = "";
function _sua(id, name, address, phone, fax, email, tax) {
    check_fax = 1;
    check_phone = 1;
    check_fax = 1;
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('{$languages.modal_title_sua}');
    $("#name").val(name);
    $("#address").val(address);
    $("#phone").val(phone);
    $("#fax").val(fax);
    $("#email").val(email);
    $("#tax").val(tax);
    $("#id_sua").val(id);
    $("#btnSua").show();
    $("#btnSave").hide();
    namecu = name;
}
function _sua_oke() {
    var kiemtra = true;
    if ($("#name").val() == "") {
        swal("{$languages.canhbao}", "{$languages.validation_name}", "warning");
        kiemtra = false;
    } else if ($("#address").val() == "") {
        swal("{$languages.canhbao}", "{$languages.validation_address}", "warning");
        kiemtra = false;
    } else if ($("#phone").val() == "") {
        swal("{$languages.canhbao}", "{$languages.validation_phone}", "warning");
        kiemtra = false;
    } else if ($("#fax").val() == "") {
        swal("{$languages.canhbao}", "{$languages.validation_fax}", "warning");
        kiemtra = false;
    } else if ($("#email").val() == "") {
        swal("{$languages.canhbao}", "{$languages.validation_email}", "warning");
        kiemtra = false;
    } else if ($("#tax").val() == "") {
        swal("{$languages.canhbao}", "{$languages.validation_tax}", "warning");
        kiemtra = false;
    }
    if (kiemtra == true) {
        if (check_fax == 1 && check_phone == 1 && check_fax == 1) {
            $.confirm({
                title: '{$languages.xacnhan}',
                content: '{$languages.sua_title}',
                icon: 'fa fa-question',
                theme: 'modern',
                closeIcon: true,
                autoClose: 'cancel|10000',
                animation: 'scale',
                type: 'orange',
                buttons: {
                    'Yes': {
                        btnClass: 'btn-primary',
                        action: function () {
                            $.ajax({
                                type: "POST",
                                url: "{site_url()}khachhang/suacongty",
                                data: {
                                    id: $("#id_sua").val(),
                                    name: $("#name").val(),
                                    address: $("#address").val(),
                                    phone: $("#phone").val(),
                                    fax: $("#fax").val(),
                                    email: $("#email").val(),
                                    tax: $("#tax").val(),
                                },
                                datatype: "text",
                                success: function (data) {
                                    if (data.status == 'denied') {
                                        swal("{$languages.thatbai}", "{$languages.error}", "error");
                                    } else {
                                        $('#modal_form').modal('hide');
                                        reload_table();
                                        swal("{$languages.thanhcong}", "{$languages.sua}", "success");
                                    }
                                }
                            });
                        }
                    },
                    cancel: {
                        text: 'No',
                        btnClass: 'btn-danger',
                        action: function () {
                            // lets the user close the modal.
                        }
                    }
                }
            });
        }
    } else {
        swal("{$languages.thatbai}", "{$languages.error}", "warning");
    }
}
function reload_table() {
    table.ajax.reload(null, false); //reload datatable ajax 
}
function _contact(id) {
    window.location = "{site_url()}khachhang/contacts/" + id;
}
</script>
{/block}
{block name=body}
<div class="main-content">
    <div class="main-content-inner">
        <!--PATH BEGINS-->
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li><i class="ace-icon fa fa-home home-icon"></i><a href="#">{$languages.linkdau}</a></li>
                <li class="active">{$languages.linkcuoi}</li>
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
                    <div class="clearfix">
                        <div class="pull-left tableTools-container"></div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="pull-left">
                                <button class="btn btn-xs btn-primary" onclick="_add()"><i class="ace-icon fa fa-plus"></i> {$languages.themmoi}</button>
                                <button class="btn btn-xs btn-warning" onclick="reload_table()"><i class="ace-icon fa fa-refresh"></i> {$languages.load}</button>
                            </div>
                        </div>
                    </div>
                    <div class="space-2"></div>
                    <table id="table" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>{$languages.stt}</th>
                                <th>{$languages.name}</th>
                                <th>{$languages.address}</th>
                                <th>{$languages.phone}</th>
                                <th>{$languages.fax}</th>
                                <th>{$languages.email}</th>
                                <th>{$languages.tax}</th>
                                <th>{$languages.thaotac}</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <!-- PAGE CONTENT ENDS -->
                </div>
            </div>
        </div>
    </div>
</div>
{/block}