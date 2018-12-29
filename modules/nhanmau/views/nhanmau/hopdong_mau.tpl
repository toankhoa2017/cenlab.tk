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
                    <li><a href="{site_url()}nhanmau">Nhận mẫu</a></li>
                    <li class="active">Phiếu yêu cầu thử nghiệm</li>
                </ul>
            </div>
            <!--PATH ENDS-->
            <div class="page-content">
                <div class="row">
                    <div class="col-xs-12">
                        <!-- PAGE CONTENT BEGINS -->
                        <h3 class="header smaller lighter blue"><i class="fa fa-list" aria-hidden="true"></i> PHIẾU YÊU CẦU THỬ NGHIỆM</h3>
                        <div class="tabbable">
                            <ul class="nav nav-tabs" id="myTab">
                                <li {if !$data['hopdong_mau'] || $data['hopdong_mau'] != '1'}class="active"{/if}>
                                    <a href="{site_url()}nhanmau/" aria-expanded="true">
                                        <i class="green ace-icon fa fa-file-text bigger-120"></i> Phiếu đã tạo
                                    </a>
                                </li>
                                <li {if $data['hopdong_mau'] && $data['hopdong_mau'] == '1'}class="active"{/if}>
                                    <a href="{site_url()}nhanmau?hopdong_mau=1" aria-expanded="false">
                                        <i class="green ace-icon fa fa-file-text-o bigger-120"></i> Phiếu mẫu
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                {if $data['error']}
                                    <div class="alert alert-warning">
                                        <button type="button" class="close" data-dismiss="alert">
                                            <i class="ace-icon fa fa-times"></i>
                                        </button>
                                        <strong>Lỗi!</strong>
                                        {$data['error']}
                                        <br>
                                    </div>
                                {else}
                                    <div class="row">
                                        <div class="col-md-12 text-right" style="margin-bottom: 15px;">
                                            <a class="btn btn-sm btn-info" href="{site_url()}nhanmau/add?hopdong_mau=1"><i class="fa fa-plus" aria-hidden="true"></i> Thêm phiếu</a>
                                        </div>
                                    </div>
                                    <table id="list-hopdong" class="table table-striped table-bordered table-hover" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>STT</th>
                                                <th>Mã hợp đồng</th>
                                                <th>Giá hệ thống</th>
                                                <th>Giá hợp đồng</th>
                                                <th>Thao tác</th>
                                            </tr>
                                        </thead>
                                    </table>
                                {/if}
                            </div>
                         </div>
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
    LoadHopdong(function(){
        $('[data-rel=tooltip]').tooltip();
    });
});
function LoadHopdong(callBack = false) {
    table = $('#list-hopdong').DataTable({
        "stateSave": true,
        "processing": true,
        "serverSide": true,
        "lengthMenu": [[10, 20, 50, -1], [10, 20, 50, "All"]],
        "language": {
            "lengthMenu": "Hiển thị _MENU_ kết quả",
            "zeroRecords": "Không có kết quả nào",
            "info": "Hiển thị kết quả từ _START_ tới _END_ của _TOTAL_ kết quả (trong tổng số _MAX_)",
            "search": "Tìm mã hợp đồng: ",
            "infoFiltered": ""
        },
        "ajax": {
            "url": "{site_url()}nhanmau/ajax_list",
            "type": "GET",
            "data": {
                'hopdong_mau' : {$data['hopdong_mau']}
            }
        },
        "order": [ ],
        "columns": [
            { "data": "index" },
            { "data": "hopdong_code" },
            { "data": "hopdong_pricetmp" },
            { "data": "hopdong_price" }
        ],
        "columnDefs": [
            { 
                "targets": [ 0 ],
                "orderable": false
            },
            {
                "targets": 1,
                "render": function (data, type, row, meta) {
                    return '<a href="'+row.link_detail+'" title="Xem chi tiết">'+data+'</a>';
                }
            },
            {
                "targets": 4,
                "render": function(data, type, row, meta){
                    var button_list = '';
                    if(typeof row.link_copy !== "undefined"){
                        button_list +=   '<a href="'+row.link_copy+'" class="btn btn-xs btn-info tooltip-info" data-rel="tooltip" data-original-title="Sử dụng mẫu">' +
                                            '<i class="ace-icon fa fa-files-o bigger-120"></i>' +
                                        '</a>'
                    }
                    if(typeof row.link_detail !== "undefined"){
                        button_list +=   '<a href="'+row.link_detail+'" class="btn btn-xs btn-info tooltip-info" data-rel="tooltip" data-original-title="Xem chi tiết">' +
                                            '<i class="ace-icon fa fa-search-plus bigger-120"></i>' +
                                        '</a>'
                    }
                    if(typeof row.link_edit !== "undefined"){
                        button_list +=   '<a href="'+row.link_edit+'" class="btn btn-xs btn-info tooltip-info" data-rel="tooltip" data-original-title="Sửa phiếu">' +
                                            '<i class="ace-icon fa fa-pencil bigger-120"></i>' +
                                        '</a>'
                    }
                    return  '<div class="btn-group btn-action">' +
                                button_list +
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
    $('#list-hopdong').parent('div').css('overflow-x', 'auto');
}
</script>
{/block}