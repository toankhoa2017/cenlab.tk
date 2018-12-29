{* Extend our master template *}
{extends file="master.tpl"}
{block name="css"}{/block}
{block name=script}{/block}
{block name=body}
<div class="main-content">
<div class="main-content-inner">
<div class="page-content">
<div class="row">
<div class="col-xs-12">
<!-- PAGE CONTENT BEGINS -->
<h3 class="header smaller lighter blue">Notification</h3>
<div class="widget-box transparent">
	<div class="widget-body">
		<div class="widget-main padding-16">
			<div class="clearfix">
				<div class="grid3 center">
					<div class="easy-pie-chart percentage" data-percent="45" data-color="#CA5952">
						<span class="percent">45</span>%
					</div>
					<div class="space-2"></div>
					Graphic Design
				</div>
				<div class="grid3 center">
					<div class="center easy-pie-chart percentage" data-percent="90" data-color="#59A84B">
						<span class="percent">90</span>%
					</div>
					<div class="space-2"></div>
					HTML5 & CSS3
				</div>
				<div class="grid3 center">
					<div class="center easy-pie-chart percentage" data-percent="80" data-color="#9585BF">
						<span class="percent">80</span>%
					</div>
					<div class="space-2"></div>
					Javascript/jQuery
				</div>
			</div>
			<div class="hr hr-16"></div>
			<div class="profile-skills">
				<div class="progress">
					<div class="progress-bar" style="width:80%">
                                            <span class="pull-left"><a href="{site_url()}tailieu/denghi/duyetdenghi?trangthai=chuaduyet">Duyệt đề nghị</a></span>
						<span class="pull-right">{$duyetDeNghi_number}</span>
					</div>
				</div>
				<div class="progress">
					<div class="progress-bar progress-bar-success" style="width:70%">
                                            <span class="pull-left"><a href="{site_url()}tailieu/denghi/danhsachsoanthao?trangthai=chuaduyet">Soạn thảo</a></span>
                                            <span class="pull-right">{$soanThao_number}</span>
					</div>
				</div>
				<div class="progress">
					<div class="progress-bar progress-bar-purple" style="width:30%">
                                            <span class="pull-left"><a href="{site_url()}tailieu/denghi/danhsachxemxetsoanthao?trangthai=chuaduyet">Xem xét bản thảo</a></span>
						<span class="pull-right">{$xemXetSoanThao_number}</span>
					</div>
				</div>
				<div class="progress">
					<div class="progress-bar progress-bar-warning" style="width:50%">
                                            <span class="pull-left"><a href="{site_url()}tailieu/denghi/danhsachpheduyet?trangthai=chuaduyet">Phê duyệt</a></span>
						<span class="pull-right">{$pheDuyet_number}</span>
					</div>
				</div>
				<div class="progress">
					<div class="progress-bar progress-bar-danger" style="width:60%">
                                            <span class="pull-left"><a href="{site_url()}tailieu/denghi/danhsachbanhanh?trangthai=chuaduyet">Ban hành</a></span>
						<span class="pull-right">{$banHanh_number}</span>
					</div>
				</div>
				<div class="progress">
					<div class="progress-bar progress-bar-pink" style="width:20%">
                                            <span class="pull-left"><a href="{site_url()}tailieu/denghi/danhsachphanphoi?trangthai=chuaduyet">Phân phối</a></span>
						<span class="pull-right">{$phanPhoi_number}</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- PAGE CONTENT ENDS -->
</div>
</div>
</div>
</div>
</div>
{/block}