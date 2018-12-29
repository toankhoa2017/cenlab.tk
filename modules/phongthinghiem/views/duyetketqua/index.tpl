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
        .chat_suco{
            text-decoration: line-through;
            color: #D15B47;
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
                    <li><a href="{site_url()}phongthinghiem/duyetketqua">Danh sách mẫu</a></li>
                    <li class="active">Duyệt kết quả</li>
                </ul>
            </div>
            <!--PATH ENDS-->
            <div class="page-content">
                <div class="row">
                    <div class="col-xs-12">
                        <!-- PAGE CONTENT BEGINS -->
                        <h3 class="header smaller lighter blue"><i class="fa fa-list" aria-hidden="true"></i> Duyệt kết quả</h3>
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
                                    <th>Phòng thí nghiệm</th>
                                    <th>Kết quả</th>
                                    <th>Ghi chú</th>
                                    <th>Duyệt kết quả</th>
                                    <th>Người duyệt</th>
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
    LoadPackage(function(){
        //alert(1);
        $('[data-rel=tooltip]').tooltip();
    });
});
function GroupColumn(api, groupColumn){
    var rows = api.rows( { page:'current' } ).nodes();
    var last = null;
    api.column(groupColumn, { page:'current' } ).data().each( function ( group, i ) {
        if ( last !== group ) {
            $(rows).eq( i ).before(
                '<tr class="group">'+
                    '<td colspan="11">'+
                        '<div class="col-md-6">Mã số mẫu: ' + group + '</div>'+
                        '<div class="col-md-6">Tên mẫu: ' + rows.data()[i].mau_name + '</div>'+
                    '</td>'+
                '</tr>'
            );
            last = group;
        }
    });
}
function LoadPackage(callBack = false) {
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
            "url": "{site_url()}phongthinghiem/duyetketqua/ajax_list_package",
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
            { "data": "ptn_name" },
            { "data": "list_chat" },
            { "data": "mauketqua_note" },
            { "data": "mauketqua_approve_txt" },
            { "data": "user_approve" }
        ],
        "columnDefs": [
            { 
                "targets": [ 0 ],
                "orderable": false
            },
            {
                "targets": 1,
                "render": function ( data, type, row, meta ) {
                    return '<a href="{site_url()}phongthinghiem/duyetketqua/detail?' + 
                            'mauchitiet=' + row.mauchitiet_id +
                            '" title="Xem chi tiết">'+data+'</a>';
                }
            },
            {
                "targets": 8,
                "render": function ( data, type, row, meta ) {
                    var list_input_txt = $('<ul>');
                    var list_ketqua = $.parseJSON(row.list_ketqua);
                    $(row.list_chat).each(function(index, value){
                        var class_chat = value.chat_suco?'chat_suco':'';
                        var chat_ketqua = list_ketqua[value.chat_id] ? list_ketqua[value.chat_id] + ' ('+row.donvitinh_name+')' : '';
                        $('<li>', { class: 'chat-txt' + ' ' + class_chat })
                                .html(value.chat_name + ': ' + chat_ketqua)
                                .appendTo(list_input_txt);
                    });
                    return list_input_txt.get(0).outerHTML;
                }
            },
            {
                "targets": 10,
                "render": function ( data, type, row, meta ) {
                    if(data){
                        return $(data.icon).html(' '+data.label).get(0).outerHTML;
                    }
                }
            },
            {
                "targets": 11,
                "render": function ( data, type, row, meta ) {
                    if(!data){
                        return '<a class="btn btn-sm btn-info" href="{site_url()}phongthinghiem/duyetketqua/detail?' + 
                                'mauchitiet=' + row.mauchitiet_id + '" title="Duyệt kết quả"><i class="fa fa-check"></i> Duyệt</a>';
                    }
                    return data;
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
        },
        "initComplete": function(){
            if(callBack){ callBack();}
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