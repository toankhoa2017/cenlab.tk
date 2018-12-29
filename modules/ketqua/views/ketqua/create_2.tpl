{* Extend our master template *}
{extends file="master.tpl"}
{block name=css}
    <style type="text/css">
        .profile-info-name{
            width: 200px;
        }
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
                    <li><a href="{site_url()}ketqua/phieuketqua">Nhận mẫu</a></li>
                    <li class="active">Trả kết quả</li>
                </ul>
            </div>
            <!--PATH ENDS-->
            <div class="page-content">
                <div class="row">
                    <div class="col-xs-12">
                        <!-- PAGE CONTENT BEGINS -->
                        <h3 class="header smaller lighter blue"><i class="fa fa-file-text" aria-hidden="true"></i> Trả kết quả</h3>
                        {if $data['error']}
                            <div class="alert alert-danger">
                                <button type="button" class="close" data-dismiss="alert">
                                    <i class="ace-icon fa fa-times"></i>
                                </button>
                                <strong>
                                    <i class="ace-icon fa fa-times"></i>
                                    Lỗi!
                                </strong>
                                {$data['error']}
                            </div>
                        {else}
                        <form action="" method="post">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="profile-activity">
                                        <div class="row">
                                            <div class="col-xs-4"><strong>BN:</strong></div>
                                            <div class="col-xs-8"><strong class="blue">{$data['hopdong']['hopdong_code']}</strong></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="profile-activity">
                                        <div class="row">
                                            <div class="col-xs-4"><strong>Ngày in:</strong></div>
                                            <div class="col-xs-8"><strong class="blue">{$data['date_export']|date_format:"%d/%m/%Y"}</strong></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <h4 class="header green smaller">
                                        <i class="fa fa-briefcase orange"></i> Công ty
                                    </h4>
                                    <div class="profile-user-info profile-user-info-striped" style="width: 100%;">
                                        <div class="profile-info-row">
                                            <div class="profile-info-name"> Tên công ty </div>
                                            <div class="profile-info-value">
                                                <span class="editable" id="username">{$data['hopdong']['congty']['congty_name']}</span>
                                            </div>
                                        </div>
                                        <div class="profile-info-row">
                                            <div class="profile-info-name"> Địa chỉ </div>
                                            <div class="profile-info-value">
                                                <i class="fa fa-map-marker light-orange bigger-110"></i>
                                                <span class="editable" id="country">{$data['hopdong']['congty']['congty_address']}</span>
                                            </div>
                                        </div>
                                        <div class="profile-info-row">
                                            <div class="profile-info-name"> Email </div>
                                            <div class="profile-info-value">
                                                <span class="editable" id="age">{$data['hopdong']['congty']['congty_email']}</span>
                                            </div>
                                        </div>
                                        <div class="profile-info-row">
                                            <div class="profile-info-name"> Số điện thoại </div>
                                            <div class="profile-info-value">
                                                <span class="editable" id="signup">{$data['hopdong']['congty']['congty_phone']}</span>
                                            </div>
                                        </div>
                                        <div class="profile-info-row">
                                            <div class="profile-info-name"> Fax </div>
                                            <div class="profile-info-value">
                                                <span class="editable" id="login">{$data['hopdong']['congty']['congty_fax']}</span>
                                            </div>
                                        </div>
                                        <div class="profile-info-row">
                                            <div class="profile-info-name"> Mã số thuế </div>
                                            <div class="profile-info-value">
                                                <span class="editable" id="about">{$data['hopdong']['congty']['congty_tax']}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h4 class="header green smaller">
                                        <i class="fa fa-info-circle orange"></i> Thông tin chỉ tiêu
                                    </h4>
                                    <div class="profile-user-info profile-user-info-striped" style="width: 100%;">
                                        <div class="profile-info-row">
                                            <div class="profile-info-name"> Chỉ tiêu phân tích </div>
                                            <div class="profile-info-value">
                                                <span class="editable">{$data['hopdong']['chitieu']['chitieu_name']}</span>
                                            </div>
                                        </div>
                                        <div class="profile-info-row">
                                            <div class="profile-info-name"> Phương pháp </div>
                                            <div class="profile-info-value">
                                                <span class="editable">
                                                    <select name="phuongphap_name">
                                                    {if $data['hopdong']['list_chitieu']}
                                                        {foreach from=array_unique(array_column($data['hopdong']['list_chitieu'], 'phuongphap')) item=phuongphap}
                                                            <option value="{$phuongphap}">{$phuongphap}</option>
                                                        {/foreach}
                                                    {/if}
                                                    </select>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="profile-info-row">
                                            <div class="profile-info-name"> Nền mẫu </div>
                                            <div class="profile-info-value">
                                                <span class="editable">
                                                    <select name="nenmau_name">
                                                    {if $data['hopdong']['list_chitieu']}
                                                        {foreach from=array_unique(array_column($data['hopdong']['list_chitieu'], 'nenmau_name')) item=nenmau}
                                                            <option value="{$nenmau}">{$nenmau}</option>
                                                        {/foreach}
                                                    {/if}
                                                    </select>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="profile-info-row">
                                            <div class="profile-info-name"> Mô tả mẫu </div>
                                            <div class="profile-info-value">
                                                <span class="editable">
                                                    <select name="mau_description">
                                                    {if $data['hopdong']['list_chitieu']}
                                                        {foreach from=array_unique(array_column($data['hopdong']['list_chitieu'], 'mau_description')) item=mau_description}
                                                            <option value="{$mau_description}">{$mau_description}</option>
                                                        {/foreach}
                                                    {/if}
                                                    </select>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="profile-info-row">
                                            <div class="profile-info-name"> Ngày nhận mẫu </div>
                                            <div class="profile-info-value">
                                                <span class="editable">{$data['hopdong']['hopdong_createdate']|date_format:"%d/%m/%Y %H:%M"}</span>
                                            </div>
                                        </div>
                                        <div class="profile-info-row">
                                            <div class="profile-info-name"> Ngày hẹn trả kết quả </div>
                                            <div class="profile-info-value">
                                                <span class="editable">{$data['hopdong']['date_end']|date_format:"%d/%m/%Y"}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <h4 class="header green smaller"><i class="fa fa-flask orange" aria-hidden="true"></i> Thông tin kết quả</h4>
                                    <table class="table table-striped table-bordered table-hover list_chitieu" style="margin: 0;">
                                        <thead>
                                            <tr>
                                                <th>Mã số mẫu</th>
                                                <th>Tên mẫu</th>
                                                <th>Kết quả</th>
                                                <th>LOD/LOQ</th>
                                                <th>Đơn vị tính</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {foreach from=$data['hopdong']['list_chitieu'] item=chitieu}
                                            <tr>
                                                <td>{$chitieu['mau_code']}</td>
                                                <td>{$chitieu['mau_name']}</td>
                                                <td>{$chitieu['ketqua']}</td>
                                                <td>{$chitieu['val_min']}/{$chitieu['val_max']}</td>
                                                <td>{$chitieu['donvitinh']}</td>
                                            </tr>
                                            {/foreach}
                                        </tbody>
                                    </table>
                                    {if $data['hopdong']['list_congnhan']}
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <p style="padding-top: 15px; text-decoration: underline;"><i>Ghi chú:</i></p>
                                            <ul>
                                                {foreach from=$data['hopdong']['list_congnhan'] item=congnhan_info}
                                                <li>({$congnhan_info['congnhan_sign']}) : {$congnhan_info['congnhan_name']}</li>
                                                {/foreach}
                                            </ul>
                                        </div>
                                    </div>
                                    {/if}
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-xs-12">
                                    <h4 class="header green smaller"><i class="fa fa-sticky-note orange" aria-hidden="true"></i> Cách hiển thị kết quả</h4>
                                    <select name="less_select">
                                        {foreach from=$data['result_compare']['less'] key=index item=value}
                                            <option value="{$index}">{$value}</option>
                                        {/foreach}
                                    </select>
                                    <strong> < LOD (MIN) < </strong>
                                    <select name="between_select">
                                        {foreach from=$data['result_compare']['between'] key=index item=value}
                                            <option value="{$index}">{$value}</option>
                                        {/foreach}
                                    </select>
                                    <strong> < LOQ (MAX) < </strong>
                                    <select name="more_select">
                                        {foreach from=$data['result_compare']['more'] key=index item=value}
                                            <option value="{$index}">{$value}</option>
                                        {/foreach}
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-6">
                                    <h4 class="header green smaller"><i class="fa fa-sticky-note orange" aria-hidden="true"></i> Lưu ý</h4>
                                    <textarea name="ketqua_note" class="form-control" rows="5"></textarea>
                                </div>
                                <div class="col-xs-6">
                                    <h4 class="header green smaller"><i class="fa fa-user orange" aria-hidden="true"></i> Người duyệt</h4>
                                    <select name="user_duyet" class="form-control">
                                    {foreach from=$data['user_duyet'] item=user}
                                        <option value="{$user['id']}">{$user['name']}</option>
                                    {/foreach}
                                    </select>
                                </div>
                            </div>
                            <div class="clearfix form-actions" style="text-align: center;">
                                <button class="btn btn-info return_result" type="submit">
                                    <i class="ace-icon fa fa-check bigger-110"></i> Gửi duyệt
                                </button>
                            </div>
                        </form>
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

</script>
{/block}