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
                    <li><a href="{site_url()}phongthinghiem/suco">Phiếu báo sự cố</a></li>
                    <li class="active">Danh sách chờ xử lý</li>
                </ul>
            </div>
            <!--PATH ENDS-->
            <div class="page-content">
                <div class="row">
                    <div class="col-xs-12">
                        <!-- PAGE CONTENT BEGINS -->
                        <h3 class="header smaller lighter blue"><i class="fa fa-list" aria-hidden="true"></i> Danh sách phiếu báo sự cố chờ xử lý</h3>
                        <table id="list-suco" class="table table-striped table-bordered table-hover" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Mã số mẫu</th>
                                    <th>Tên mẫu</th>
                                    <th>Nội dung</th>
                                    <th>Người tạo</th>
                                    <th>Ngày tạo</th>
                                    <th>Nhóm chỉ tiêu/Chỉ tiêu</th>
                                    <th>Tình trạng</th>
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
        "search": "Tìm mã số mẫu: ",
        "infoFiltered": ""
    },
    "ajax": {
        "url": "{site_url()}phongthinghiem/suco/ajax_list_suco",
        "type": "GET"
    },
    "order": [ ],
    "columns": [
        { "data": "index" },
        { "data": "mau_code" },
        { "data": "mau_name" },
        { "data": "suco_content" },
        { "data": "nhansu_id" },
        { "data": "suco_createdate" }
    ],
    "columnDefs": [
        { 
            "targets": [ 0 ],
            "orderable": false
        },
        {
            "targets": 1,
            "render": function ( data, type, row, meta ) {
                return '<a href="{site_url()}phongthinghiem/suco/detail?' + 
                        'suco=' + row.suco_id +
                        '" title="Xem chi tiết">'+data+'</a>';
            }
        },
        {
            "targets": 6,
            "render": function ( data, type, row, meta ) {
                var chitieu_chat_txt = $('<p>');
                console.log(row.suco_chitieu);
                $(row.suco_chitieu).each(function(index, value){
                    $('<p>').html('- ' + value.chitieu_name).appendTo(chitieu_chat_txt);
                    if(typeof value.list_chat_info !== 'undefined'){
                        /*
                        var chat_txt = $('<ul>');
                        $(value.list_chat_info).each(function(index, chat_info){
                            $('<li>').html(chat_info.chat_name).appendTo(chat_txt);
                        });
                        chat_txt.appendTo(chitieu_chat_txt);
                        */
                    }
                });
                return chitieu_chat_txt.get(0).outerHTML;
            }
        },
        {
            "targets": 7,
            "render": function ( data, type, row, meta ) {
                return '<span class="'+row.suco_approve_info.class+'">' + row.suco_approve_info.icon + ' ' + row.suco_approve_info.label + '</span>';
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