{* Extend our master template *}
{extends file="master.tpl"}
{block name=css}
    <style type="text/css">
        .highlight { background-color: yellow }
        .group td{
            background: #eff3f8;
            color: #438eb9;
            font-weight: bold;
        }
        .group div{
            margin: 0;
            padding: 0;
        }
    </style>
{/block}
{block name=body}
    <div class="main-content">
        <div class="main-content-inner">
            <!--PATH BEGINS-->
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li><i class="ace-icon fa fa-home home-icon"></i><a href="#">Home</a></li>
                    <li><a href="{site_url()}phongthinghiem/nhanmau">Danh sách mẫu</a></li>
                    <li class="active">Danh sách chờ xử lý</li>
                </ul>
            </div>
            <!--PATH ENDS-->
            <div class="page-content">
                <div class="row">
                    <div class="col-xs-12">
                        <!-- PAGE CONTENT BEGINS -->
                        <h3 class="header smaller lighter blue"><i class="fa fa-list" aria-hidden="true"></i> Danh sách mẫu chờ xử lý</h3>
                        <table id="list-mau" class="table table-striped table-bordered table-hover" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Mã số mẫu</th>
                                    <th>Tên mẫu</th>
                                    <th>Nền mẫu</th>
                                    <th>Mô tả mẫu</th>
                                    <th>Ngày nhận mẫu</th>
                                    <th>Tình trạng</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                        </table>
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
    LoadPackage();
});
function LoadPackage() {
    var active = false;
    var groupColumn = 1;
    table = $('#list-mau').DataTable({
        "stateSave": false,
        "processing": true,
        "serverSide": true,
        "lengthMenu": [[10, 20, 50, -1], [10, 20, 50, "All"]],
        "language": {
            "lengthMenu": "Hiển thị _MENU_ kết quả",
            "zeroRecords": "Không có kết quả nào",
            "info": "Hiển thị kết quả từ _START_ tới _END_ của _TOTAL_ kết quả (trong tổng số _MAX_)",
            "search": "Tìm mã số mẫu: ",
            "infoFiltered": ""
        },
        "ajax": {
            "url": "{site_url()}phongthinghiem/nhanmau/ajax_list_mau",
            "type": "GET"
        },
        "order": [ ],
        "columns": [
            { "data": "index" },
            { "data": "mau_code" },
            { "data": "mau_name" },
            { "data": "nenmau_name" },
            { "data": "mau_description" },
            { "data": "date_create" }
        ],
        "columnDefs": [
            { 
                "targets": [ 0 ],
                "orderable": false
            },
            {
                "targets": 1,
                "render": function ( data, type, row, meta ) {
                    return '<a href="{site_url()}phongthinghiem/nhanmau/detail?' + 
                            'mau=' + row.mau_id +
                            '" title="Xem chi tiết">'+data+'</a>';
                }
            },
            {
                "targets": 6,
                "render": function ( data, type, row, meta ) {
                    if(row.mauptn_approve){
                        if(row.mauptn_approve === '1'){
                            return '<span class="green"><i class="fa fa-check"></i> Đã nhận mẫu</span>';
                        }else{
                            return '<span class="red"><i class="fa fa-times"></i> Đã từ chối mẫu</span>';
                        }
                    }
                    return '';
                }
            },
            {
                "targets": 7,
                "render": function ( data, type, row, meta ) {
                    return '<a href="{site_url()}phongthinghiem/nhanmau/detail?' + 'mau=' + row.mau_id + '" ' 
                            + 'class="btn btn-xs btn-info tooltip-info" ' 
                            + 'title="Xem chi tiết"><i class="ace-icon fa fa-search-plus bigger-120"></i></a>';
                }
            }
        ]
    });
    //table.column([groupColumn,groupColumn+1]).visible(false);
    table.on('draw', function () {
        var body = $(table.table().body());
        body.unhighlight();
        body.highlight(table.search());
    });
    $('#list-mau').parent('div').css('overflow-x', 'auto');
}
</script>
{/block}