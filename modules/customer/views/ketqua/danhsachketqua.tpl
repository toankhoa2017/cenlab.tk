{* Extend our master template *}
{extends file="master.tpl"}
{block name=css}
    <style type="text/css">
        .btn-action{
            position: initial;
        }
        .highlight { background-color: yellow }
    </style>
{/block}
{block name=body}
    <div class="main-content">
        <div class="main-content-inner">
            <!--PATH BEGINS-->
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li><i class="ace-icon fa fa-home home-icon"></i><a href="#">Home</a></li>
                    <li><a href="{site_url()}customer/ketqua/danhsachketqua">Kết quả</a></li>
                    <li class="active">Danh sách Phiếu kết quả</li>
                </ul>
            </div>
            <!--PATH ENDS-->
            <div class="page-content">
                <div class="row">
                    <div class="col-md-12">
                        <style type="text/css">
                            #quytrinh-tailieu{
                                width: 100%;
                                margin: 0;
                                display: table;
                                table-layout: fixed;
                            }
                            #quytrinh-tailieu li{
                                display: table-cell;
                                padding-right: 20px;
                            }
                            #quytrinh-tailieu .label-xlg{
                                font-size: 18px;
                                padding: 13px 0;
                                margin: 0;
                                height: 50px;
                                width: 100%;
                            }
                            #quytrinh-tailieu .label-xlg.arrowed-in:before{
                                border-width: 25px 10px;
                                left: -10px;
                            }
                            #quytrinh-tailieu .label-xlg.arrowed-right:after{
                                border-width: 25px 10px;
                                right: -20px;
                            }
                            #quytrinh-tailieu .not-allowed a{
                                cursor: not-allowed;
                            }
                        </style>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <!-- PAGE CONTENT BEGINS -->
                        <h3 class="header smaller lighter blue"><i class="fa fa-list" aria-hidden="true"></i> Danh sách Phiếu kết quả</h3>
                        {if $data['congty']}
                            <table id="list-ketqua" class="table table-striped table-bordered table-hover" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Mã hợp đồng</th>
                                        <th>Tên khách hàng</th>
                                        <th>Ngày tạo</th>
                                        <th>Người tạo</th>
                                        <th></th>
                                    </tr>
                                </thead>
                            </table>
                        {else}
                            <div class="alert alert-danger">
                                <button type="button" class="close" data-dismiss="alert">
                                    <i class="ace-icon fa fa-times"></i>
                                </button>
                                <strong>
                                    <i class="ace-icon fa fa-times"></i>
                                    Lỗi!
                                </strong>
                                Khách hàng không tồn tại
                            </div>
                        {/if}
                    </div>
                </div>
            </div>
        </div>
    </div>
{/block}
{block name=script}
<script src="{$assets_path}plugins/datatables/jquery.dataTables.min.js"></script>
<script src="{$assets_path}plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="{base_url()}assets/tuanlm/jquery.highlight.js"></script>
<script type="text/javascript">
var table;
$(document).ready(function() {
    LoadKetQua(function(){
        $('[data-rel=tooltip]').tooltip();
    });
});
function LoadKetQua(callBack = false) {
    table = $('#list-ketqua').DataTable({
        "stateSave": true,
        "processing": true,
        "serverSide": true,
        "lengthMenu": [[10, 20, 50, -1], [10, 20, 50, "All"]],
        "language": {
            "lengthMenu": "Hiển thị _MENU_ kết quả",
            "zeroRecords": "Không có kết quả nào",
            "info": "Hiển thị kết quả từ _START_ tới _END_ của _TOTAL_ kết quả (trong tổng số _MAX_)",
            "search": "Tìm kiếm: ",
            "infoFiltered": ""
        },
        "ajax": {
            "url": "{site_url()}customer/ketqua/ajax_list",
            "type": "GET",
            "data":{
                "congty": {$data['congty']}
            }
        },
        "order": [ ],
        "columns": [
            { "data": "index" },
            { "data": "hopdong_code" },
            { "data": "congty_name" },
            { "data": "create_date" },
            { "data": "user_name" }
        ],
        "columnDefs": [
            { 
                "targets": [ 0 ],
                "orderable": false
            },
            {
                "targets": 5,
                "render": function(data, type, row, meta){
                    return  '<div class="btn-group btn-action">' +
                                '<a href="'+row.link_detail+'" class="btn btn-xs btn-success tooltip-success" data-rel="tooltip" data-original-title="Xem chi tiết">' +
                                    '<i class="ace-icon fa fa-search-plus bigger-120"></i>' +
                                '</a>' +
                            '</div>';
                }
            }
        ],
        "initComplete": function(){
            if(callBack){ callBack();}
        }
    });
    table.on('draw', function () {
        var body = $(table.table().body());
        body.unhighlight();
        body.highlight(table.search());
    });
    $('#list-ketqua').parent('div').css('overflow-x', 'auto');
}
</script>
{/block}