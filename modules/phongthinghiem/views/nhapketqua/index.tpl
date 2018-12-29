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
                    <li><a href="{site_url()}phongthinghiem/nhapketqua">Danh sách mẫu</a></li>
                    <li class="active">Danh sách chờ xử lý</li>
                </ul>
            </div>
            <!--PATH ENDS-->
            <div class="page-content">
                <div class="row">
                    <div class="col-xs-12">
                        <!-- PAGE CONTENT BEGINS -->
                        <h3 class="header smaller lighter blue"><i class="fa fa-list" aria-hidden="true"></i> Danh sách chờ xử lý</h3>
                        <div class="row">
                            <div class="col-md-3">
                                <label><input type="checkbox" class="group-mau-code" name="group-chitieu"> Nhóm theo "Mã số mẫu"</label>
                            </div>
                        </div>
                        <table id="list-package" class="table table-striped table-bordered table-hover" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Mã số mẫu</th>
                                    <th>Tên mẫu</th>
                                    <th>Nền mẫu</th>
                                    <th>Chỉ tiêu</th>
                                    <th>Phương pháp</th>
                                    <th>Kỹ thuật</th>
                                    <!--<th>Phòng thí nghiệm</th>-->
                                    <th>Chất phân tích</th>
                                    <th>Tình trạng thí nghiệm</th>
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
function GroupColumn(api, groupColumn){
    var rows = api.rows( { page:'current' } ).nodes();
    var last = null;
    api.column(groupColumn, { page:'current' } ).data().each( function ( group, i ) {
        if ( last !== group ) {
            $(rows).eq( i ).before(
                '<tr class="group">'+
                    '<td colspan="10">'+
                        '<div class="col-md-6">Mã số mẫu: ' + group + '</div>'+
                        '<div class="col-md-6">Tên mẫu: ' + rows.data()[i].mau_name + '</div>'+
                    '</td>'+
                '</tr>'
            );
            last = group;
        }
    });
}
function LoadPackage() {
    var active = false;
    var groupColumn = 1;
    table = $('#list-package').DataTable({
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
            "url": "{site_url()}phongthinghiem/nhapketqua/ajax_list_package",
            "type": "GET"
        },
        "order": [ ],
        "columns": [
            { "data": "index" },
            { "data": "mau_code" },
            { "data": "mau_name" },
            { "data": "nenmau_name" },
            { "data": "chitieu_name" },
            { "data": "phuongphap_name" },
            { "data": "kythuat_name" },
            /*{ "data": "ptn_name" },*/
            { "data": "list_chat" },
            { "data": "mauketqua_approve" }
        ],
        "columnDefs": [
            { 
                "targets": [ 0 ],
                "orderable": false
            },
            {
                "targets": 1,
                "render": function ( data, type, row, meta ) {
                    return '<a href="{site_url()}phongthinghiem/nhapketqua/detail?' + 
                            'mauchitiet=' + row.mauchitiet_id +
                            '" title="Xem chi tiết">'+data+'</a>';
                }
            },
            {
                "targets": 7,
                "render": function ( data, type, row, meta ) {
                    var list_input_txt = $('<ul>');
                    $(row.list_chat).each(function(index, value){
                        $('<li>', { class: 'chat-txt' }).html(value.chat_name).appendTo(list_input_txt);
                    });
                    return list_input_txt.html();
                }
            },
            {
                "targets": 8,
                "render": function ( data, type, row, meta ) {
                    if(data.mauketqua_approve){
                        var mauketqua_approve_txt = $(data.mauketqua_approve_txt.icon).append(' ' + data.mauketqua_approve_txt.label).get(0).outerHTML + '<br>';
                        if(data.mauketqua_approve === '2'){
                            mauketqua_approve_txt +=   '<a href="{site_url()}phongthinghiem/nhapketqua/detail?' + 
                                                    'mauchitiet=' + row.mauchitiet_id +
                                                    '" title="Xem chi tiết">Nhập kết quả</a>';
                        }
                        return mauketqua_approve_txt;
                    }else{
                        return '<a href="{site_url()}phongthinghiem/nhapketqua/detail?' + 
                            'mauchitiet=' + row.mauchitiet_id +
                            '" title="Xem chi tiết">Nhập kết quả</a>';
                    }
                }
            }
        ],
        "drawCallback": function ( settings ) {
            var api = this.api();
            if($('.group-mau-code').is(":checked")){
                GroupColumn(api, groupColumn);
            }
            if(active) return false;
            $('.group-mau-code').on('change', function(){
                if($(this).is(":checked")){
                    GroupColumn(api, groupColumn);
                }else{
                    $('#list-package').find('.group').remove();
                }
                return false;
            });
            active = true;
        }
    });
    //table.column([groupColumn,groupColumn+1]).visible(false);
    table.on('draw', function () {
        var body = $(table.table().body());
        body.unhighlight();
        body.highlight(table.search());
    });
    $('#list-hopdong').parent('div').css('overflow-x', 'auto');
}
</script>
{/block}