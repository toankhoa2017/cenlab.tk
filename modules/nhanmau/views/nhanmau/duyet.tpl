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
                    <li><a href="{site_url()}nhanmau/duyet">Danh sách duyệt</a></li>
                    <li class="active">Duyệt hợp đồng</li>
                </ul>
            </div>
            <!--PATH ENDS-->
            <div class="page-content">
                <div class="row">
                    <div class="col-xs-12">
                        <!-- PAGE CONTENT BEGINS -->
                        <h3 class="header smaller lighter blue"><i class="fa fa-list" aria-hidden="true"></i> DUYỆT HỢP ĐỒNG</h3>
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
                            <div class="tabbable">
                                <ul class="nav nav-tabs" id="myTab">
                                    <li {if !$data['approved']}class="active"{/if}>
                                        <a href="{site_url()}nhanmau/duyet" aria-expanded="true">
                                            <i class="green ace-icon bigger-120 fa fa-tasks"></i> Đang chờ duyệt
                                        </a>
                                    </li>
                                    <li {if $data['approved'] && $data['approved'] == '1'}class="active"{/if}>
                                        <a href="{site_url()}nhanmau/duyet?approved=1" aria-expanded="false">
                                            <i class="green ace-icon bigger-120 fa fa-check-square-o"></i> Đã duyệt
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content">
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
                                                <!--<th>Đề nghị sửa</th>-->
                                                <th>Duyệt</th>
                                                <th>Thao tác</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
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
                "get_all" : "all",
                "approved" : {$data['approved']}
            }
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
            /*{ "data": "hopdong_approve_txt" }*/
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
            /*
            {
                "targets": 8,
                "render": function (data, type, row, meta) {
                    var denghi = '<div class="btn-group btn-action">';
                    if(row.edit_request_not_approve){
                        denghi += '<a href="'+row.link_detail+'&edit_request=true" class="btn btn-xs btn-info tooltip-info" data-rel="tooltip" data-original-title="Xem đề nghị sửa">' + 
                                '<span class="badge badge-danger white">'+row.edit_request_not_approve+'/'+row.total_edit_request+'</span> Chưa duyệt</a>';
                    }else{
                        denghi += '<a href="#" class="btn btn-xs btn-info">'+row.total_edit_request+' Đã duyệt</a>';
                    }
                    denghi += '</div>';
                    return denghi;
                }
            },
            */
            {
                "targets": 8,
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
                "targets": 9,
                "render": function(data, type, row, meta){
                    return '<div class="btn-group btn-action">' +
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
    $('#list-hopdong').parent('div').css('overflow-x', 'auto');
}
</script>
{/block}