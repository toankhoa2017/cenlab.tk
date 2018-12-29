{* Extend our master template *}
{extends file="master.tpl"}
{block name=css}
    <link rel="stylesheet" href="{site_url()}assets/css/jquery-confirm.css" />
    <link rel="stylesheet" href="{site_url()}assets/css/stylevth.css" />
    <link rel="stylesheet" href="{$assets_path}css/chosen.min.css" />
    <link rel="stylesheet" href="{$assets_path}css/ace.min.css" />
    <style>
        body {
            overflow-x: hidden;
        }
        #quytrinh-muasam{
            width: 100%;
            margin: 0;
            display: table;
            table-layout: fixed;
        }
        #quytrinh-muasam li{
            display: table-cell;
            padding-right: 20px;
        }
        #quytrinh-muasam .label-xlg{
            font-size: 14px;
            padding: 15px 0;
            margin: 0;
            height: 50px;
            width: 100%;
        }
        #quytrinh-muasam .label-xlg.arrowed-in:before{
            border-width: 25px 10px;
            left: -10px;
        }
        #quytrinh-muasam .label-xlg.arrowed-right:after{
            border-width: 25px 10px;
            right: -20px;
        }
        #quytrinh-muasam .not-allowed a{
            cursor: not-allowed;
        }
        .lock_cha{
            position: relative;
        }
        .lock_cha .lock_con{
            width: 100%;
            position: absolute;
            height: 100%;
            top: 0;
            left: 0;
            background: rgba(238, 238, 238, 0);
            z-index: 100;
        }
    </style>
{/block}
{block name=script}
    <div class="modal fade" id="history_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title" id="history_title"></h3>
                </div>
                <div class="modal-body">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>{$languages.table_sanpham}</th>
                                <th>{$languages.table_soluong}</th>
                                <th>{$languages.table_hang}</th>
                                <th>{$languages.table_nhacungcap}</th>
                                <th>{$languages.table_gia}</th>
                            </tr>
                        </thead>
                        <tbody id="history_body">
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-xs btn-danger" data-dismiss="modal">Thoát</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

    <div class="modal fade" id="review_hopdong" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title">T{$languages.filebaogia_title}</h3>
                </div>
                <div class="modal-body">
                    <iframe id="hopdong_review" style="width: 100%;height: 600px;" frameborder="0"></iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-xs btn-danger" data-dismiss="modal">{$languages.thoat}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

    <div class="modal fade" id="khongduyet_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title">{$languages.khongduyetphieu}</h3>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>{$languages.khongduyetphieu_comment}</label>
                        <textarea class="form-control" rows="5" id="comment" name="comment" placeholder=""></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-xs btn-success" onclick="khongduyet()">{$languages.khongduyetphieu_gui}</button>
                    <button type="button" class="btn btn-xs btn-danger" data-dismiss="modal">{$languages.khongduyetphieu_thoat}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <script src="{$assets_path}plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="{$assets_path}plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script src="{site_url()}assets/js/sweetalert.min.js"></script>
    <script src="{site_url()}assets/js/jquery-confirm.js"></script>
    <script src="{$assets_path}js/chosen.jquery.min.js"></script>
    <script src="{site_url()}assets/js/jquery.number.js"></script>
    <script>
function history(denghi_id, name, vesion) {
    $.ajax({
        type: "POST",
        data: {
            'denghi_id': denghi_id,
            'denghi_vesion': vesion
        },
        url: "{site_url()}muasam/yeucau/history",
        success: function (data) {
            console.log(data);
            $("#history_title").text("{$languages.history_title} " + name);
            $("#history_body").html('');
            $("#history_body").append(data);
            $("#history_modal").modal("show");
        }
    });
}

$(document).ready(function () {
    $('.chosen-select', this).chosen();
    var now = new Date();
    var day = now.getDate();
    var month = now.getMonth() +1;
    var year = now.getFullYear();
    if(day <10) {
        day = '0'+day;
    }
    if(month <10) {
        month = '0'+ month;
    }
    var today = year+'-'+month+'-'+day;
    {foreach $denghi_detail as $value}
        $('#ngaynhap{$value->dn_detail_id}').val(today);
        $('#sldanhap{$value->dn_detail_id}').val(0);
        $('#slnhap{$value->dn_detail_id}').val(0);
    {/foreach}
        
    {foreach $soluongdanhap as $sl}
        $('#sldanhap{$sl['dn_detail_id']}').val({$sl['soluong']});
        if($('#sldanhap{$sl['dn_detail_id']}').val() = $('#dn_detail_soluong{$sl['dn_detail_id']}').val()) {
            $('#slnhap{$sl['dn_detail_id']}').attr("disabled", "disabled");
        }
    {/foreach}
});

var ds_sanpham = [];
{foreach $denghi_detail as $value}
    ds_sanpham.push({$value->dn_detail_id});
{/foreach}
                        
function _create() {
    var danhsach_de_detail_id = [];
    var danhsach_soluong_nhap = [];
    var danhsach_ngaynhap = [];
    var danhsach_hang = [];
    var danhsach_ghichu = [];
    var kt = false;
    for (i = 0; i < ds_sanpham.length; i++) {
        if($('#slnhap' + ds_sanpham[i]).val() != 0 ) {
            if(isNaN($('#slnhap' + ds_sanpham[i]).val()) == false) {
                if($('#slnhap' + ds_sanpham[i]).val() <= $('#dn_detail_soluong' + ds_sanpham[i]).val() - $('#sldanhap' + ds_sanpham[i]).val()) {
                kt = true;
                danhsach_de_detail_id.push($('#de_detail_id' + ds_sanpham[i]).val());
                danhsach_soluong_nhap.push($('#slnhap' + ds_sanpham[i]).val());
                danhsach_ngaynhap.push($('#ngaynhap' + ds_sanpham[i]).val());
                danhsach_ghichu.push($('#ghichu' + ds_sanpham[i]).val());
                danhsach_hang.push($('#hang' + ds_sanpham[i]).val());
                }
                else {
                    kt = false;
                    break;
                }
            }
            else {
                kt = false;
                break;
            }
        }
    }
    if(kt == false) {
        swal("Cảnh báo", "Chưa có dữ liệu hoặc dữ liệu bị sai", "warning");
    } else {
        $.ajax({
            type: "POST",
            data: {
                de_detail_id : danhsach_de_detail_id,
                soluong_nhap : danhsach_soluong_nhap,
                ngaynhap : danhsach_ngaynhap,
                ghichu : danhsach_ghichu,
                hang : danhsach_hang,
                denghi_id : {$denghi->denghi_id}
            },
            url: "{site_url()}muasam/nhaphang/insertnhaphang",
            dataType: "text",
            success: function (data) {
                if (data == 1) {
                    window.location = "{site_url()}muasam/nhaphang";
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
                    <li class="active"><a href="{site_url()}muasam/nhaphang">Danh sách nhập hàng</a></li>
                    <li class="active">Nhập hàng</li>
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
                        <h4 class="header green"><i class="fa fa-car red" aria-hidden="true"></i> {$languages.detail_title1}
                            <div class="widget-toolbar">
                                <a href="#history" onclick="history({$denghi->denghi_id}, '{$denghi->denghi_title}',{$denghi->denghi_vesion})">
                                    <i class="ace-icon fa fa-bookmark"></i> {$languages.history}
                                </a>
                            </div>
                        </h4>
                        <ul id="quytrinh-muasam">
                            <li><span class="label label-xlg arrowed-right label-info {if $quytrinh>=1} label-success {else} label-info {/if}">{$languages.procedure_1}</span></a></li>
                            <li><span class="label label-xlg arrowed-right arrowed-in arrowed {if $quytrinh == 1} label-danger {elseif $quytrinh>=2} label-success {else} label-info {/if}">{$languages.procedure_2}</span></li>
                            <li><span class="label label-xlg arrowed-right arrowed-in label-info arrowed {if $quytrinh == 2} label-danger {elseif $quytrinh>=3} label-success {else} label-info {/if}">{$languages.procedure_3}</span></li>
                            <li><span class="label label-xlg arrowed-right arrowed-in label-info arrowed {if $quytrinh == 3} label-danger {elseif $quytrinh>=4} label-success {else} label-info {/if}">{$languages.procedure_4}</span></li>
                            <li><span class="label label-xlg arrowed-right arrowed-in label-info arrowed {if $quytrinh == 4} label-danger {elseif $quytrinh>=5} label-success {else} label-info {/if}">{$languages.procedure_5}</span></li>
                            <li><span class="label label-xlg arrowed-right arrowed-in label-info arrowed {if $quytrinh == 5&&$denghi->denghi_success==1} label-danger {elseif $denghi->denghi_success==2} label-success {else} label-info {/if}">{$languages.procedure_6}</span></li>
                        </ul>
                        <h4 class="header green"><i class="fa fa-info-circle red" aria-hidden="true"></i> {$languages.detail_title2}</h4>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label" for="congty_email">{$languages.tieude}</label>
                                    <input disabled class="form-control data-info" type="text" id="denghi_title" name="denghi_title" placeholder="{$languages.placeholder_tieude}" value="{$denghi->denghi_title}">
                                </div>
                            </div>
                            
                        </div>
                        <div class="form-group ">
                            <label class="control-label" for="congty_name">{$languages.ghichu} <span class="red">*</span></label>
                            <textarea class="form-control" name="denghi_describe" id="denghi_describe" rows="5" placeholder="{$languages.placeholder_ghichu}">{$denghi->denghi_describe}</textarea>
                        </div>
                        <h4 class="header green"><i class="fa fa-product-hunt red" aria-hidden="true"></i> {$languages.detail_title3}</h4>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="widget-box">
                                    <div class="widget-header">
                                        <h4 class="widget-title">{$languages.detaile_title_sanpham}</h4>
                                    </div>
                                    <div class="widget-body">
                                        <div class="widget-main class-sanpham">
                                            <div class="lock_con" style="">
                                            </div>
                                            {foreach $denghi_detail as $value}
                                                <div class="row">
                                                    <div class="col-md-7">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="control-label">{$languages.sanpham}</label>
                                                                <input class="form-control" type="hidden" name="de_detail_id" value="{$value->dn_detail_id}" id="de_detail_id{$value->dn_detail_id}">
                                                                <input class="form-control" type="hidden" name="sp_id" value="{$value->sp_id}" id="sp_id{$value->dn_detail_id}">
                                                                <input class="form-control" type="text" disabled="true" value="{$value->sp_name}" >
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="control-label">Số lượng cần mua</label>
                                                                <input class="form-control" type="number" disabled="true" name="dn_detail_soluong" value="{$value->dn_detail_soluong}" id="dn_detail_soluong{$value->dn_detail_id}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="control-label">Số lượng đã nhập</label>                                                       
                                                                <input class="form-control" type="text" disabled="true" id="sldanhap{$value->dn_detail_id}" >
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group" >
                                                                <label class="control-label">Số lượng nhập</label>                                                            
                                                                <input class="form-control" type="text" id="slnhap{$value->dn_detail_id}"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="control-label">Hãng</label>                                                            
                                                                <input class="form-control" type="text" value="{$value->hang_name}" id="hang{$value->dn_detail_id}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <div class="form-group">
                                                                <label class="control-label">Ngày Nhập</label>                                                            
                                                                <input class="form-control" type="date" value="" id="ngaynhap{$value->dn_detail_id}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label">Ghi Chú</label>                                                            
                                                                <input class="form-control" type="text" id="ghichu{$value->dn_detail_id}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            {/foreach}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        <div class="clearfix form-actions">
                            <div class="col-md-offset-4 col-md-9">
                                <button class="btn btn-md btn-primary" type="button" onclick="_create()">
                                    <i class="ace-icon fa fa-share-square-o bigger-110"></i> Nhập Hàng
                                </button>                                    
                            </div>
                        </div>
                               
                    </div>
                </div>
            </div>
        </div>
    </div>
{/block}