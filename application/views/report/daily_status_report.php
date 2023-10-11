<?php
	$this->load->view('commons/main_template',$nestedView); 
	$role_id=$this->session->userdata('role_id');
?>

<?php
if(@$displayList==1) {
?>

<div class="row"> 
	<div class="col-sm-12 col-md-12">
		<div class="block-flat">
			<table class="table table-bordered"></table>
			<div class="content">
				<div class="row">
					<form role="form" class="form-horizontal" method="post" action="<?php echo SITE_URL;?>daily_status_report">
						<div class="col-sm-12">
                                <div class="col-sm-3">
                                    <input type="text" required class="form-control" id="start_date" placeholder="Start Date" name="startDate" readonly  value="<?php echo @$searchParams['startDate']; ?>">
                                </div>     
                                <div class="col-sm-3">
                                    <input type="text" required class="form-control" id="end_date" placeholder="End Date" name="endDate" readonly  value="<?php echo @$searchParams['endDate']; ?>">
                                </div>
                                <div class="col-sm-3">
                                    <button type="submit" name="searchlocation" value="1" class="btn btn-success"><i class="fa fa-search"></i></button>
                                <button type="submit" name="download_location_report" value="1" formaction="<?php echo SITE_URL;?>download_daily_status_report" class="btn btn-success"><i class="fa fa-cloud-download"></i></button>
                                <a href="<?php echo SITE_URL.'daily_status_report'; ?>" class="btn btn-success"><i class="fa fa-refresh"></i></a>
                            </div>
                        </div>
                        </div>
					</form>
				</div>
				<div class="header"></div>
				<div class="table-responsive">
					<table class="table table-bordered hover">
						<thead>
							<tr>
								<th class="text-center"><strong>S.NO</strong></th>
                                <th class="text-center"><strong>Parameter</strong></th>
                                <th class="text-center"><strong>No</strong></th>
                            </tr>
						</thead>
						<tbody>
							<tr>
                                <td class="text-center" width="5%">1</td>
                                <td class="text-center" width="10%">Opportunities Created</td>
                                <td class="text-center" width="10%"><?php echo $daily_status_report['row']['count']; ?></td>
                            </tr>
                            <tr>
                                <td class="text-center" width="5%">1a</td>
                                <td class="text-center" width="10%">Conclusion in 30 days</td>
                                <td class="text-center" width="10%">count</td>
                            </tr>
                            <tr>
                                <td class="text-center" width="5%">1b</td>
                                <td class="text-center" width="10%">Conclusion in 60 days</td>
                                <td class="text-center" width="10%">count</td>
                            </tr>
                            <tr>
                                <td class="text-center" width="5%">1c</td>
                                <td class="text-center" width="10%">Conclusion more than 60 days</td>
                                <td class="text-center" width="10%">count</td>
                            </tr>
                            <tr>
                                <td class="text-center" width="5%">&nbsp</td>
                                <td class="text-center" width="10%"></td>
                                <td class="text-center" width="10%"></td>
                            </tr>
                            <tr>
                                <td class="text-center" width="5%">2</td>
                                <td class="text-center" width="10%">Visits as on</td>
                                <td class="text-center" width="10%"></td>
                            </tr>
                            <tr>
                                <td class="text-center" width="5%">2a</td>
                                <td class="text-center" width="10%">Cold Call</td>
                                <td class="text-center" width="10%"><?php echo $daily_status_report['visit_cold_call']['count']; ?></td>
                            </tr> 
                            <tr>
                                <td class="text-center" width="5%">2b</td>
                                <td class="text-center" width="10%">Conference</td>
                                <td class="text-center" width="10%"><?php echo $daily_status_report['visit_cold_conference']['count']; ?></td>
                            </tr>
                            <tr>
                                <td class="text-center" width="5%">2c</td>
                                <td class="text-center" width="10%">Courtesy Call</td>
                                <td class="text-center" width="10%"><?php echo $daily_status_report['visit_courtesy_call']['count']; ?></td>
                            </tr>
                            <tr>
                                <td class="text-center" width="5%">2d</td>
                                <td class="text-center" width="10%">Dealer Visit</td>
                                <td class="text-center" width="10%"><?php echo $daily_status_report['visit_dealer']['count']; ?></td>
                            </tr>
                            <tr>
                                <td class="text-center" width="5%">2e</td>
                                <td class="text-center" width="10%">Demo</td>
                                <td class="text-center" width="10%"><?php echo $daily_status_report['visit_demo']['count']; ?></td>
                            </tr>
                            <tr>
                                <td class="text-center" width="5%">2f</td>
                                <td class="text-center" width="10%">Negotiation</td>
                                <td class="text-center" width="10%"><?php echo $daily_status_report['visit_negotiation']['count']; ?></td>
                            </tr>
                            <tr>
                                <td class="text-center" width="5%">2g</td>
                                <td class="text-center" width="10%">Order Follow Up</td>
                                <td class="text-center" width="10%"><?php echo $daily_status_report['visit_order_follow_up']['count']; ?></td>
                            </tr>
                            <tr>
                                <td class="text-center" width="5%">2h</td>
                                <td class="text-center" width="10%">Payment Collection</td>
                                <td class="text-center" width="10%"><?php echo $daily_status_report['visit_payment_collection']['count']; ?></td>
                            </tr>
                            <tr>
                                <td class="text-center" width="5%">2i</td>
                                <td class="text-center" width="10%">Training</td>
                                <td class="text-center" width="10%"><?php echo $daily_status_report['visit_training']['count']; ?></td>
                            </tr>
                            <tr>
                                <td class="text-center" width="5%">&nbsp</td>
                                <td class="text-center" width="10%"></td>
                                <td class="text-center" width="10%"></td>
                            </tr>
                            <tr>
                                <td class="text-center" width="5%">3</td>
                                <td class="text-center" width="10%">Quotations generated</td>
                                <td class="text-center" width="10%"><?php echo $daily_status_report['row_quote']['count']; ?></td>
                            </tr>
							<!-- <tr><td colspan="6" align="center"><span class="label label-primary">No Records</span></td></tr> -->
						</tbody>
					</table>
				</div>
				<!-- <div class="row">
                	<div class="col-sm-12">
                    	<div class="pull-left"><?php //echo @$pagermessage ; ?></div>
                        <div class="pull-right">
							<div class="dataTables_paginate paging_bs_normal">
                            	<?php //echo @$pagination_links; ?>
                            </div>
                        </div>
                    </div>
				</div>  -->
			</div>
		</div>				
	</div>
</div>
	
<?php
}	
?>
	<div class="md-overlay"></div>
	<?php
	$this->load->view('commons/main_footer.php',$nestedView); 
?>