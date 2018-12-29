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
                    <li><a href="{site_url()}ketqua/phanhoi">Phản hồi</a></li>
                    <li class="active">Danh sách chờ xử lý</li>
                </ul>
            </div>
            <!--PATH ENDS-->
            <div class="page-content">
                <div class="row">
                    <div class="col-xs-12">
                        <!-- PAGE CONTENT BEGINS -->
                        <h3 class="header smaller lighter blue"><i class="fa fa-list" aria-hidden="true"></i> Danh sách phản hồi chờ xử lý</h3>
                        <table id="list-suco" class="table table-striped table-bordered table-hover" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Mã hợp đồng</th>
                                    <th>Nội dung</th>
                                    <th>Người tạo</th>
                                    <th>Ngày tạo</th>
                                    <th>Tình trạng</th>
                                    <th>Ghi chú</th>
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
var active = false;
var groupColumn = 1;
table = $('#list-suco').DataTable({
    "stateSave": false,
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
        "url": "{site_url()}ketqua/phanhoi/ajax_list_phanhoi",
        "type": "GET"
    },
    "order": [ ],
    "columns": [
        { "data": "index" },
        { "data": "hopdong_code" },
        { "data": "phanhoi_content" },
        { "data": "" },
        { "data": "phanhoi_date" },
        { "data": "" },
        { "data": "phanhoi_approve_note" }
    ],
    "columnDefs": [
        { 
            "targets": [ 0 ],
            "orderable": false
        },
        {
            "targets": 1,
            "render": function ( data, type, row, meta ) {
                return '<a href="{site_url()}ketqua/phanhoi/detail?' + 
                        'phanhoi_id=' + row.phanhoi_id +
                        '" title="Xem chi tiết">'+data+'</a>';
            }
        },
        {
            "targets": 3,
            "render": function ( data, type, row, meta ) {
                return row.contact_info.contact_fullname;
            }
        },
        {
            "targets": 5,
            "render": function ( data, type, row, meta ) {
                return '<span class="'+row.phanhoi_approve_info.class+'">' + row.phanhoi_approve_info.icon + ' ' + row.phanhoi_approve_info.label + '</span>';
            }
        },
        {
            "targets": 7,
            "render": function ( data, type, row, meta ) {
                var button_list = '';
                if(typeof row.link_detail !== "undefined"){
                    button_list +=   '<a href="'+row.link_detail+'" class="btn btn-xs btn-info" title="Xem chi tiết">' +
                                        '<i class="ace-icon fa fa-search-plus bigger-120"></i>' +
                                    '</a>';
                }
                return  '<div class="btn-group btn-action">' +
                    button_list +
                '</div>';
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
</script>
{/block}