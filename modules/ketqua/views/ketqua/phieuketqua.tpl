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
                    <li><a href="{site_url()}ketqua/phieuketqua">Trả kết quả</a></li>
                    <li class="active">Danh sách hợp đồng</li>
                </ul>
            </div>
            <!--PATH ENDS-->
            <div class="page-content">
                <div class="row">
                    <div class="col-xs-12">
                        <!-- PAGE CONTENT BEGINS -->
                        <h3 class="header smaller lighter blue"><i class="fa fa-list" aria-hidden="true"></i> DANH SÁCH HỢP ĐỒNG</h3>
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
                            <table id="list-hopdong" class="table table-striped table-bordered table-hover" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Mã hợp đồng</th>
                                        <th>Công ty</th>
                                        <th>Người liên hệ</th>
                                        <th>Giá hệ thống</th>
                                        <th>Giá hợp đồng</th>
                                        <th>Ngày tạo</th>
                                        <th>Ngày kết thúc</th>
                                        <th>Số lượng mẫu</th>
                                        <th>Duyệt</th>
                                        <th>Kết quả</th>
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
            "url": "{site_url()}ketqua/ajax_list_hopdong",
            "type": "GET"
        },
        "order": [ ],
        "columns": [
            { "data": "index" },
            { "data": "hopdong_code" },
            { "data": "congty_name" },
            { "data": "contact_name" },
            { "data": "hopdong_pricetmp" },
            { "data": "hopdong_price" },
            { "data": "hopdong_createdate" },
            { "data": "hopdong_dateend" },
            { "data": "total_mau" }
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
                "targets": 9,
                "render": function(data, type, row, meta){
                    var class_status = 'btn-danger';
                    var icon_status = '';
                    switch(row.hopdong_approve){
                        case '1': icon_status = 'fa fa-check'; class_status = 'label-success'; break;
                        case '2': icon_status = 'fa fa-times'; class_status = 'label-danger'; break;
                        case '3': icon_status = 'fa fa-pencil'; class_status = 'label-warning'; break;
                        default: icon_status = ''; class_status = 'label-warning'; break;
                    }
                    return '<span class="label label-sm ' + class_status + '">' + 
                            '<i class="' + icon_status + '" aria-hidden="true"></i> ' + row.hopdong_approve_txt + 
                            '</span>';
                }
            },
            { 
                "targets": [ 10 ],
                "render": function(data, type, row, meta){
                    return row.chitieu_has_result + '/' + row.total_chitieu + ' (' + row.total_chitieu_export + ')';
                }
            },
            {
                "targets": 11,
                "render": function(data, type, row, meta){
                    var button_list = '';
                    if(typeof row.link_detail !== "undefined"){
                        button_list +=   '<a href="'+row.link_detail+'" class="btn btn-xs btn-info tooltip-info" data-rel="tooltip" data-original-title="Xem chi tiết">' +
                                            '<i class="ace-icon fa fa-search-plus bigger-120"></i>' +
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