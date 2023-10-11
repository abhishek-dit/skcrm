<div class="row">
	<div class="col-md-9">
		<div class="col-md-5">
			<div class="block-flat">
				<div class="header">
					<h4><i class="fa fa-user-md"></i><b> Leads</b></h4>
				</div>
				<div class="content">
					<div class="row">
						<div class="col-md-4">
							<a class="quick-button-small metro greenDark span2" href="<?php echo SITE_URL ?>newLead">
								<i class="fa fa-edit"></i>
								<p>New</p>
							</a>
						</div>
						<div class="col-md-4">
							<a class="quick-button-small metro pink span2" href="<?php echo SITE_URL ?>openLeads">
								<i class="fa fa-folder-open-o"></i>
								<p>Open</p>
							</a>
						</div>
						<div class="col-md-4">
							<a class="quick-button-small metro greenLight span2" href="<?php echo SITE_URL ?>closedLeads">
								<i class="fa fa-folder-o"></i>
								<p>Closed</p>
							</a>
						</div>
					</div><br>
				</div>

			</div>
		</div>
		<div class="col-md-3">
			<div class="block-flat">
				<div class="header">
					<h4><i class="fa fa-calendar"></i><b> Calendar</b></h4>
				</div>
				<div class="content">
					<div class="row">
						<div class="col-md-12">
							<a class="quick-button-small metro red span2" href="<?php echo SITE_URL ?>viewCalendar">
								<i class="fa fa-calendar"></i>
								<p>Calendar</p>
							</a>
						</div>
					</div>
				</div><br>		
			</div><br>
		</div>
		<div class="col-md-4">
			<div class="block-flat">
				<div class="header">
					<h4><i class="fa fa-file-o"></i><b> Target Vs Actual</b></h4>
				</div>
				<div class="content">
					<div class="row">
						<div class="col-md-2"></div>
						<div class="col-md-8">
							<a class="quick-button-small metro grey span2" href="<?php echo SITE_URL ?>user_productTargetVsActual">
								<i class="fa fa-bar-chart-o"></i>
								<p>Report</p>
							</a>
						</div>
					</div><br>
				</div>
			</div>
		</div>
		
		<div class="clear"></div>
		<div class="col-md-4">
			<div class="block-flat">
				<div class="header">
					<h4><i class="fa fa-edit"></i><b> Opportunities</b></h4>
				</div>
				<div class="content">
					<div class="row">
						<div class="col-md-6">
							<a class="quick-button-small metro brown span2" href="<?php echo SITE_URL ?>opportunity">
								<i class="fa fa-folder-open-o"></i>
								<p>Open</p>
							</a>
						</div>
						<div class="col-md-6">
							<a class="quick-button-small metro blue span2" href="<?php echo SITE_URL ?>opportunityClosed">
								<i class="fa fa-folder-o"></i>
								<p>Closed</p>
							</a>
						</div>
					</div><br>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="block-flat">
				<div class="header">
					<h4><i class="fa fa-calendar-o"></i><b> Plan</b></h4>
				</div>
				<div class="content">
					<div class="row">
						<div class="col-md-6">
							<a class="quick-button-small metro orange span2" href="<?php echo SITE_URL ?>visit">
								<i class="fa fa-dashboard"></i>
								<p>Visit</p>
							</a>
						</div>
						<div class="col-md-6">
							<a class="quick-button-small metro black span2" href="<?php echo SITE_URL ?>demo">
								<i class="fa fa-suitcase"></i>
								<p>Demo</p>
							</a>
						</div>
					</div><br>
				</div>
			</div>
		</div>
		<div class="col-md-3">	
			<div class="block-flat">
				<div class="header">
					<h4><i class="fa fa-file-o"></i><b> Marketing</b></h4>
				</div>
				<div class="content">
					<div class="row">
						<div class="col-md-12">
							<a class="quick-button-small metro pink span2" href="<?php echo SITE_URL ?>viewCampaignDocuments">
								<i class="fa fa-files-o"></i>
								<p>Documents</p>
							</a>
						</div>
					</div><br>
				</div>
			</div>
		</div>
		 


	</div>
	<div class="col-md-3">
			<div class="block-flat" style="padding:0px 15px;">
				<div class="header row" style="background:#ff7300;color:#ffffff;padding-left:20px;">
					<h4><i class="fa fa-warning"></i><b> Alerts</b></h4>
				</div>
				<div class="content">
					<ul class="list-unstyled">
						<li><a href="<?php echo SITE_URL.'openLeads';?>">Leads assigned today (<?php echo get_todayAssignedLeadsCount();?>)</a></li>
						<?php
						$role = $this->session->userdata('role_id');
						if($role == 7 || $role == 8) 
	 					{
						?>
							<li><a href="<?php echo SITE_URL.'contract_note_approval_list';?>">CNote Clear for invoice (<?php echo getCNoteClearForInvoiceCount();?>)</a></li>
						<?php
						}
	 					if($role == 7 || $role == 8 || $role == 9) 
	 					{
						?>
							<li><a href="<?php echo SITE_URL.'margin_analysis_list';?>">Pending Quote approvals (<?php echo getQuoteApprovalCount();?>)</a></li>
							<li><a href="<?php echo SITE_URL.'po_approval_list';?>">Purchase Order approvals (<?php echo getPoApprovalCount();?>)</a></li>
							<li><a href="<?php echo SITE_URL.'quoteApprovalList';?>">Old Quote approvals (<?php echo getQuoteApprovalCount_old();?>)</a></li>
						<?php
						}
						?>
						<strong>Pending Tasks:</strong>
						<li><a href="<?php echo SITE_URL.'edit_orderConclusionDate';?>">Opportunity date Review - (<?php echo editOrderConclusionDateCount();?>)</a></li>
						<li><a href="<?php echo SITE_URL.'demo';?>">Demo Feedback - (<?php echo notUpdatedDemoCount();?>)</a></li>
						<li><a href="<?php echo SITE_URL.'visit';?>">Visit Feedback - (<?php echo notUpdatedVisitCount();?>)</a></li>
						<?php if($role ==5)
                        { ?>
                        <strong>Recent Approvals:</strong>
                        <li><a href="<?php echo SITE_URL.'po_list';?>">Approved PO's- (<?php echo get_po_count_by_status(2);?>)</a></li>
                        <li><a href="<?php echo SITE_URL.'po_list';?>">Approved C-Notes - (<?php echo get_po_count_by_status(4);?>)</a></li>
                        <li><a href="<?php echo SITE_URL.'po_list';?>">Rejected PO's - (<?php echo get_po_count_by_status(3);?>)</a></li>
        				<?php   } ?>

					</ul>
				
			</div>
		</div>
	</div>

</div>

