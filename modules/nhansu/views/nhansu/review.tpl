{foreach from=$hopdong item=hd}
<div class="timeline-items">
    <div class="timeline-item clearfix">
        <div class="timeline-info">
            <i class="timeline-indicator ace-icon fa fa-star btn btn-warning no-hover green"></i>
        </div>
        <div class="widget-box transparent">
            <div class="widget-header widget-header-small">
                <span class="widget-toolbar no-border">
                    <i class="ace-icon fa fa-clock-o bigger-110"></i>
                    {date('d/m/Y', strtotime($hd['hopdong_created']))}
                </span>
            </div>
            <div class="widget-body">
                <div class="widget-main">
                    <div class="col-md-4">Đơn Vị:</div><div class="col-md-8">{$hd['donvi_ten']}</div>
                    <div class="col-md-4">Chức Vụ:</div><div class="col-md-8">{$hd['chucvu_ten']}</div>
                    <div class="col-md-4">Thời hạn:</div><div class="col-md-8">{date('d/m/Y', strtotime($hd['hopdong_datestart']))} &rightarrow; {date('d/m/Y', strtotime($hd['hopdong_dateend']))}</div>
                    <div class="col-md-4">File Hợp Đồng:</div><div class="col-md-8">{$hd['file_id']}</div>
                    <div class="space-6"></div>
                    <div class="widget-toolbox clearfix">
                        <div class="pull-left" style="margin-top:10px;margin-left:5px">
                            <i class="ace-icon fa fa-hand-o-right grey bigger-125"></i>
                            Note: <span class="red">{$hd['hopdong_note']}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{/foreach}
