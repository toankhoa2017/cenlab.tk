{* Extend our master template *}
{extends file="master.tpl"}
{block name=script}
    <script src="{$assets_path}plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="{$assets_path}plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            _load();
        });
        function _load() {
            table = $('#table').DataTable({

                "processing": true,
                "serverSide": true,
                //"paging": true,
                //"lengthChange": false,
                //"searching": false,
                //"ordering": true,
                //"info": true,
                //"autoWidth": true,
                "order": [],

                "ajax": {
                    "url": "{site_url()}luumau/mau/ajax_list",
                    "type": "POST"
                },
                "columnDefs": [
                    {
                        "targets": [-1], //last column
                        "orderable": false, //set not orderable
                    },
                ],
            });
        }
        function reload_table() {
            table.ajax.reload(null, false); //reload datatable ajax 
        }
    </script>
{/block}
{block name=body}
    <div class="main-content">
        <div class="main-content-inner">
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li><i class="ace-icon fa fa-home home-icon"></i><a href="#">{$languages.url_1}</a></li>
                    <li><a href="#">{$languages.url_2}</a></li>
                </ul>
                <div class="nav-search" id="nav-search">
                    <form class="form-search">
                        <span class="input-icon"><input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" /><i class="ace-icon fa fa-search nav-search-icon"></i></span>
                    </form>
                </div>
            </div>
            <div class="page-content">
                <div class="row">
                    <div class="col-xs-12">
                        <!-- PAGE CONTENT BEGINS -->
                        <h3 class="header smaller lighter blue col-xs-6">
                            {$languages.title}
                        </h3>
                        <h3 class="header smaller lighter blue col-xs-6" style="text-align: right;">
                            <a style="color: red;" href="{site_url()}luumau/thanhly/history_thanhly">Danh sách mẫu đã thanh lý</a>
                        </h3>
                        <div class="clearfix">
                            <div class="pull-left tableTools-container"></div>
                        </div>
                        <table id="table" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>{$languages.table_1}</th>
                                    <th>{$languages.table_2}</th>
                                    <th>{$languages.table_3}</th>
                                    <th>{$languages.table_4}</th>
                                    <th>{$languages.table_5}</th>
                                    <th>Thanh Lý Mẫu</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        <!-- PAGE CONTENT ENDS -->
                    </div>
                </div>
            </div>
        </div>
    </div>
{/block}