{* Extend our master template *}
{extends file="master.tpl"}
{block name=css}
    <link rel="stylesheet" href="{site_url()}assets/css/jquery-confirm.css" />
    <link rel="stylesheet" href="{site_url()}assets/css/stylevth.css" />
    <link rel="stylesheet" href="{site_url()}assets/css/bootstrapValidator.min.css" />
    <link rel="stylesheet" href="{$assets_path}css/bootstrap-datepicker3.min.css" />
</style>
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
                    <input type="hidden" id="id_khachhang" name="id_khachhang" />
                    <input type="hidden" id="id_sua" name="id_sua" />
                    <div class="modal-body form">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{$languages.ho}:</label>
                                    <input class="form-control" type="text" id="ho" name="ho" placeholder="{$languages.nhap}{$languages.ho}"/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{$languages.ten}:</label>
                                    <input class="form-control" type="text" id="ten" name="ten" placeholder="{$languages.nhap}{$languages.ten}"/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>{$languages.ngaysinh}</label>
                            <div class="input-group">
                                <input class="form-control date-picker" id="ngaysinh" placeholder="{$languages.chon}{$languages.ngaysinh}" name="ngaysinh" type="text" data-date-format="dd-mm-yyyy"/>
                                <span class="input-group-addon ns">
                                    <i class="fa fa-calendar bigger-110"></i>
                                </span>
                            </div>
                        </div>
                        <div class="form-group" id="check_phone">
                            <label>{$languages.phone}</label>
                            <input class="form-control" type="text" id="phone" name="phone" placeholder="{$languages.nhap}{$languages.phone}"/>
                        </div>
                        <div class="form-group" id="check_email">
                            <label>{$languages.email}</label>
                            <input class="form-control" type="email" id="email" name="email"  placeholder="{$languages.nhap}{$languages.email}"/>
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
<script src="{$assets_path}js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript">
                            $(document).ready(function () {
                                _load();
                            });
                            $('.date-picker').datepicker({
                                autoclose: true,
                                todayHighlight: true
                            }).on('changeDate', function (e) {
                                $('form#form').bootstrapValidator('updateStatus', 'ngaysinh', 'NOT_VALIDATED').bootstrapValidator('validateField', 'ngaysinh');
                            });
                            $(".ns").on("click", function () {
                                $("#ngayketthuc").focus();
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
                                        "url": "{site_url()}khachhang/contact/ajax_list",
                                        "type": "POST",
                                        "data": {
                                            "khachhang_id": '{$info.khachhang_id}',
                                            "key": $(".dataTables_filter>label>input").val(),
                                        }
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
                                $("form#form").data('bootstrapValidator').resetForm();
                                $('#form')[0].reset(); // reset form on modals
                                $('.form-group').removeClass('has-error'); // clear error class
                                $('.help-block').empty(); // clear error string
                                $('#modal_form').modal('show'); // show bootstrap modal
                                $('.modal-title').text('{$languages.them_title}'); // Set Title to Bootstrap modal title
                                $("#id_khachhang").val('{$info.khachhang_id}');
                                $("#btnSua").hide();
                                $("#btnSave").show();
                            }
                            var phone_cache = "";
                            var check_phone = 0;
                            $("#phone").on("blur", function () {
                                if ($("#phone").val() != phone_cache) {
                                    $.ajax({
                                        url: "{site_url()}khachhang/contact/check_phone",
                                        type: "POST",
                                        data: {
                                            'contact_phone': $("#phone").val(),
                                            'khachhang_id': $("#khachhang").val(),
                                            'contact_id': $("#id_sua").val()
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
                                            } else {
                                                check_phone = 1;
                                                if (check_email == 1 && check_phone == 1) {
                                                    $("#btnSave").attr("disabled", false);
                                                } else {
                                                    $("#btnSave").attr("disabled", true);
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
                                        url: "{site_url()}khachhang/contact/check_email",
                                        type: "POST",
                                        data: {
                                            'contact_email': $("#email").val(),
                                            'khachhang_id': $("#khachhang").val(),
                                            'contact_id': $("#id_sua").val()
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
                                            } else {
                                                check_email = 1;
                                                if (check_email == 1 && check_phone == 1) {
                                                    $("#btnSave").attr("disabled", false);
                                                } else {
                                                    $("#btnSave").attr("disabled", true);
                                                }
                                            }
                                        },
                                        error: function (jqXHR, textStatus, errorThrown) {
                                            alert('Error adding / update data');
                                        }
                                    });
                                }
                            });
                            $(document).ready(function () {
                                $('form#form').bootstrapValidator({
                                    feedbackIcons: {
                                        valid: 'fa fa-check',
                                        invalid: 'fa fa-times',
                                        validating: 'glyphicon glyphicon-refresh'
                                    },
                                    submitHandler: function (e) {
                                        if (check_email == 1 && check_phone == 1) {
                                            $.ajax({
                                                url: "{site_url()}khachhang/contact/ajax_add",
                                                type: "POST",
                                                data: $('#form').serialize(),
                                                dataType: "JSON",
                                                success: function (data) {
                                                    //if success close modal and reload ajax table
                                                    if (data.status == 'denied') {
                                                        swal("{$languages.thatbai}", "{$languages.error}", "error");
                                                    } else {
                                                        $('#modal_form').modal('hide');
                                                        reload_table();
                                                        swal("{$languages.thanhcong}", "{$languages.them_thanhcong}", "success");
                                                    }
                                                },
                                                error: function (jqXHR, textStatus, errorThrown) {
                                                    swal("{$languages.thatbai}", "{$languages.error}", "error");
                                                }
                                            });
                                        }
                                    },
                                    fields: {
                                        ho: {
                                            validators: {
                                                notEmpty: {
                                                    message: '{$languages.validation_ho}'//can\'t
                                                },
                                                trigger: 'change keyup',
                                            }

                                        },
                                        ten: {
                                            validators: {
                                                trigger: 'change keyup',
                                                notEmpty: {
                                                    message: '{$languages.validation_ten}'//can\'t
                                                },
                                                trigger: 'change keyup',
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
                                        ngaysinh: {
                                            validators: {
                                                notEmpty: {
                                                    message: '{$languages.validation_ngaysinh}'//can\'t
                                                },
                                                trigger: 'change keyup',
                                            }
                                        },
                                        email: {
                                            validators: {
                                                emailAddress: {
                                                    message: '{$languages.validation_email_error}'
                                                },
                                                notEmpty: {
                                                    message: '{$languages.validation_email}'//can\'t
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
                                                    url: "{site_url()}khachhang/contact/xoanguoilienhe",
                                                    data: {
                                                        id_contact: id
                                                    },
                                                    datatype: "text",
                                                    success: function (data) {
                                                        if (data.status == 'denied') {
                                                            swal("{$languages.thatbai}", "{$languages.error}", "error");
                                                        } else {
                                                            $('#modal_form').modal('hide');
                                                            reload_table();
                                                            swal("{$languages.thanhcong}", "{$languages.xoa}", "success");
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
                            function _sua(contact_id, contact_lastname, contact_firstname, contact_email, contact_phone, contact_birthday) {
                                $("form#form").data('bootstrapValidator').resetForm();
                                $('#form')[0].reset(); // reset form on modals
                                $('.form-group').removeClass('has-error'); // clear error class
                                $('.help-block').empty(); // clear error string
                                $('#modal_form').modal('show'); // show bootstrap modal
                                $('.modal-title').text('{$languages.modal_title_sua}');
                                $("#ho").val(contact_lastname);
                                $("#ten").val(contact_firstname);
                                $("#ngaysinh").val(contact_birthday);
                                $("#phone").val(contact_phone);
                                $("#email").val(contact_email);
                                $("#id_sua").val(contact_id);
                                $("#btnSua").show();
                                $("#btnSave").hide();
                                namecu = name;
                            }

                            function _sua_oke() {
                                var kiemtra = true;
                                if ($("#ho").val() == "") {
                                    swal("{$languages.canhbao}", "{$languages.validation_ho}", "warning");
                                    $("#ho").focus();
                                    kiemtra = false;
                                } else if ($("#ten").val() == "") {
                                    swal("{$languages.canhbao}", "{$languages.validation_ten}", "warning");
                                    $("#ho").focus();
                                    kiemtra = false;
                                } else if ($("#ngaysinh").val() == "") {
                                    swal("{$languages.canhbao}", "{$languages.validation_ngaysinh}", "warning");
                                    $("#ho").focus();
                                    kiemtra = false;
                                } else if ($("#phone").val() == "") {
                                    swal("{$languages.canhbao}", "{$languages.validation_phone}", "warning");
                                    $("#ho").focus();
                                    kiemtra = false;
                                } else if ($("#email").val() == "") {
                                    swal("{$languages.canhbao}", "{$languages.validation_email}", "warning");
                                    $("#ho").focus();
                                    kiemtra = false;
                                }
                                if (kiemtra == true) {
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
                                                        url: "{site_url()}khachhang/contact/suacontact",
                                                        data: {
                                                            contact_id: $("#id_sua").val(),
                                                            contact_lastname: $("#ho").val(),
                                                            contact_firstname: $("#ten").val(),
                                                            contact_email: $("#email").val(),
                                                            contact_phone: $("#phone").val(),
                                                            contact_birthday: $("#ngaysinh").val(),
                                                        },
                                                        datatype: "text",
                                                        success: function (data) {
                                                            console.log(data);
                                                            if (data.status === "denied") {
                                                                swal("{$languages.thatbai}", "{$languages.error}", "error");
                                                            } else {
                                                                $('#modal_form').modal('hide');
                                                                reload_table();
                                                                swal("{$languages.thanhcong}", "{$languages.sua}", "error");
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
                            }
                            function reload_table() {
                                table.ajax.reload(null, false); //reload datatable ajax 
                            }
                            function _contact(id) {
                                window.location = "{site_url()}khachhang/contact/" + id;
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
                    <li><a href="{site_url()}khachhang">{$languages.linkcuoi}</a></li>
                    <li class="active">{$info.congty_name}</li>
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
                        <h3 class="header smaller lighter blue">{$info.khachhang_name}</h3>
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
                                    <th>{$languages.ho}</th>
                                    <th>{$languages.ten}</th>
                                    <th>{$languages.ngaysinh}</th>
                                    <th>{$languages.email}</th>
                                    <th>{$languages.phone}</th>
                                    <th>Password</th>
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