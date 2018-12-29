{* Extend our master template *}
{extends file="master.tpl"}
{block name=css}
    <link rel="stylesheet" href="{site_url()}assets/css/stylevth.css" />
    <link rel="stylesheet" href="{$assets_path}css/chosen.min.css" />
    <link rel="stylesheet" href="{$assets_path}css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style"/>
    <link rel="stylesheet" href="{$assets_path}css/ace-skins.min.css" />
    <link rel="stylesheet" href="{$assets_path}css/ace-rtl.min.css" />
    <style type="text/css">
        .highlight { background-color: yellow }
    </style>
{/block}
{block name=script}
    <script src="{$assets_path}js/chosen.jquery.min.js"></script>
    <script src="{$assets_path}plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="{$assets_path}plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script src="{base_url()}assets/tuanlm/jquery.highlight.js"></script>
    <script type="text/javascript">
        if (!ace.vars['touch']) {
            $('.chosen-select').chosen({
                allow_single_deselect: true
            });
        }
        ;
        $("#nenmau_sort").on("change", function () {
            table.destroy();
            _load_danhsach();
            $("#name_nenmau1").text($("#nenmau_sort option:selected").text());
            $("#name_nenmau").text($("#nenmau_sort option:selected").text());
        });
        $('#nenmau_sort').val('{$info[0]->nenmau_id}').trigger('chosen:updated');
        _load_danhsach();
        function _load_danhsach() {
            table = $('#table').DataTable({
                "processing": true,
                "serverSide": true,
                colResize: false,
                autoWidth: false,
                scrollX: false,
                "language": {
                    "processing": "Đang Load Dữ Liệu...",
                },
                "order": [],
                "ajax": {
                    "url": "{site_url()}nenmau/danhsach_dongia",
                    "data": {
                        nenmau_id: $("#nenmau_sort").val()
                    },
                    "type": "POST",
                },
                "columnDefs": [
                    {
                        "targets": [-1], //last column
                        "orderable": false, //set not orderable
                    },
                ],
                "fnDrawCallback": function (data) {
                    $(".paginate_button > a").on("focus", function () {
                        $(this).blur();
                    });
                    $('[data-toggle="tooltip"]').tooltip();
                },
            });
            table.on('draw', function () {
                var body = $(table.table().body());
                body.unhighlight();
                body.highlight(table.search());
            });
        }
        function _trove() {
            window.history.back();
        }
    </script>
{/block}
{block name=body}
    <div class="main-content">
        <div class="main-content-inner">
            <!--PATH BEGINS-->
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li><i class="ace-icon fa fa-home home-icon"></i><a href="#">Quản lý mẫu</a></li>
                    <li><a href="{site_url()}nenmau">Quản lý nền mẫu</a></li>
                    <li class="active" id="name_nenmau1">{$info[0]->nenmau_name}</li>
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
                        <h3 class="header smaller lighter blue" >Chi tiết nền mẫu <y id="name_nenmau">{$info[0]->nenmau_name}</y></h3>
                        <div class="clearfix">
                            <div class="pull-left tableTools-container">
                                <select class="chosen-select form-control" id="nenmau_sort" style="width: 300px">
                                    <option value="0">Tất Cả</option>
                                    {foreach from=$nenmau key=k item=v}
                                        <option value="{$k}">{$v}</option>
                                    {/foreach}
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <table id="table" class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>CODE</th>
                                            <th>Nền Mẫu</th>
                                            <th>Chỉ Tiêu</th>
                                            <th>Giá</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
{/block}