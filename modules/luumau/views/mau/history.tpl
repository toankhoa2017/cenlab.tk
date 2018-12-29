{* Extend our master template *}
{extends file="master.tpl"}
{block name=script}
    <script src="{$assets_path}plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="{$assets_path}plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script>
        table = $('#table').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "{site_url()}luumau/mau/history_list",
                "type": "POST",
                "data": {
                    'luumau_id': '{$luumau[0]->luumau_id}'
                }
            },
            "columnDefs": [
                {
                    "targets": [-1],
                    "orderable": false,
                },
            ],
            "fnDrawCallback": function (data) {
                $(".paginate_button > a").on("focus", function () {
                    $(this).blur();
                });
                $('[data-toggle="tooltip"]').tooltip();
            }
        });
    </script>
{/block}
{block name=body}
    <div class="main-content">
        <div class="main-content-inner">
            <!--PATH BEGINS-->
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li><i class="ace-icon fa fa-home home-icon"></i><a href="#">{$languages.url_1}</a></li>
                    <li><a href="{site_url()}luumau/mau">{$languages.url_2}</a></li>
                    <li><a href="{site_url()}luumau/mau/detail/{$luumau[0]->mau_id}">{$languages.url_3}</a></li>
                    <li class="active">{$luumau[0]->luumau_name}</li>
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
                        <div class="clearfix">
                            <div class="pull-left tableTools-container"></div>
                        </div>
                        <div class="col-xs-12 col-sm-12">
                            <div class="widget-header widget-header-small" style="margin-bottom:1%;">
                                <h4 class="widget-title blue smaller">
                                    <i class="fa fa-flask blue bigger-110 orange"></i>
                                    {$luumau[0]->luumau_name}
                                </h4>
                            </div>

                            <table id="table" class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>{$languages.history_table_1}</th>
                                        <th>{$languages.history_table_2}</th>
                                        <th>{$languages.history_table_3}</th>
                                        <th>{$languages.history_table_4}</th>
                                        <th>{$languages.history_table_5}</th>
                                        <th>{$languages.history_table_6}</th>
                                        <th>{$languages.history_table_7}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>

                        </div><!-- /.col -->
                        <!-- PAGE CONTENT ENDS -->
                    </div>
                </div>
            </div>
        </div>
    </div>
{/block}