{* Extend our master template *}
{extends file="master.tpl"}
{block name=script}{/block}
{block name=body}
<div class="main-content">
	<div class="main-content-inner">
		<div class="page-content">
			<div class="row">
				<div class="col-xs-12">
					<!-- PAGE CONTENT BEGINS -->
					<div class="error-container">
						<div class="well">
							<h1 class="grey lighter smaller">
								<span class="blue bigger-125">
									<i class="ace-icon fa fa-random"></i>
									{$code}
								</span>
								{$warning}
							</h1>

							<div class="space"></div>

							<div>
								<h4 class="lighter smaller">Meanwhile, try one of the following:</h4>

								<ul class="list-unstyled spaced inline bigger-110 margin-15">
									<li>
										<i class="ace-icon fa fa-hand-o-right blue"></i>
										Read the faq
									</li>

									<li>
										<i class="ace-icon fa fa-hand-o-right blue"></i>
										Give us more info on how this specific error occurred!
									</li>
								</ul>
							</div>

							<hr />
							<div class="space"></div>

							<div class="center">
								<a href="javascript:history.back()" class="btn btn-grey">
									<i class="ace-icon fa fa-arrow-left"></i>
									Go Back
								</a>
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