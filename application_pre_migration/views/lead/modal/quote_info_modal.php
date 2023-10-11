<!--<div class="md-modal colored-header custom-width md-effect-9" id="<?php //echo @$pop_id;?>">-->
<div class="modal fade colored-header" id="info<?php echo @$row['quote_id'];?>" role="dialog">
	<div class="modal-dialog">
			<div class="md-content">
				<div class="modal-header">
					<span style="font-size:18px">Quote Revision History</span>
					<button type="button" class="close md-close" data-dismiss="modal" aria-hidden="true">&times;</button>
				</div>

				<div class="modal-body form">
					<form action="<?php echo SITE_URL; ?>" method="post" novalidate="" parsley-validate="" class="form-horizontal">
		                <br>
		                <div class="row">
			                <div class="col-sm-12">
			                	<div class="col-sm-1"></div>
			                	<div class="col-sm-10">
					                <div class="form-group">
					                    <div class="col-sm-3" align="right">Quote ID:</div>
					                    <div class="col-sm-9" align="left"><?php echo getQuoteReferenceID1($lead_id, @$row['quote_id']); ?></div>
					                </div>
					                <div class="form-group">
					                    <div class="col-sm-3" align="right">Opportunities:</div>
					                    <div class="col-sm-9" align="left"><?php echo @$row['opportunity'] ?></div>
					                </div>
					                <div class="form-group">
					                    <div class="col-sm-3" align="right">Billing Through:</div>
					                    <div class="col-sm-9"><?php $res=get_channel_partner_details($row['quote_id']); echo @$res['name'] ?></div>
					                </div>
					                <div class="form-group">
					                    <div class="col-sm-3" align="right">Revisions:</div>
					                    <div class="col-sm-9"></div>
					                </div>
					                <div class="form-group">
					                    <div class="col-sm-12">
											<table class="table table-bordered hover">
												<thead>
													<tr>
														<th>Revision</th>
														<th>Billing</th>
														<th>Discount</th>
														<th>Total Price (Rs)</th>
														<th>Status</th>
													</tr>
												</thead>
												<tbody>
												<?php 
												$quoteRevisions = getQuoteRevisions($row['quote_id']);
												$i = 1; $current_waiting_revision = '';
												foreach($quoteRevisions as $row) 
												{
													if($i==count($quoteRevisions)&&($row['status']==3))
													{
														$current_waiting_revision = $row['quote_revision_id'];
													}
													$quote_format_type = getQuoteFormatTypeByQuoteRevisionID($row['quote_revision_id']);
													switch ($quote_format_type) {
														case 1: // Old Format
															$discount = ($row['discount']);
															$quote_price = getQuotePrice($row['quote_id'], $row['discount']);
														break;
														case 2: // New Format
															
															if($i==1)
															{
																$discount = 0;
																$quote_price = getQuotePrice($row['quote_id'], $discount);
															}
															else
															{
																$qrow = getQuoteRevisionPrice($row['quote_revision_id']);
																$quote_price = round($qrow['quote_price']);
																$cost = round($qrow['cost']);
																$discount_amt = ($cost-$quote_price);
																$discount = ($discount_amt/$cost)*100;
															}
														break;
													}
													?>
													<tr>
														<td><?php echo $i; ?></td>
														<td><?php echo $row['name']; ?></td>
														<td><?php echo round($discount,2); ?>%</td>
														<td><?php echo indian_format_price($quote_price); ?></td>
														<td><?php echo getQuoteRevisionStatusLabel($row['status'],$i); ?></td>
													</tr>
													<?php
													$i++;
												}
												?>
												</tbody>
											</table>					                    	
					                    </div>
					                </div>
					                <?php
					                if($current_waiting_revision!='')
					                {
					                	$op_ma_results = getOpportunityMarginAnalysisStatus($current_waiting_revision);
					                	if($op_ma_results)
					                	{
					                		?>
							                <div class="form-group">
							                    <div class="col-sm-12">
							                    	<h5><b>Quote Approval Status</b></h5>
													<table class="table table-bordered hover">
														<thead>
															<tr>
																<th width="40%">Opportunity Details</th>
																<th width="7%">Discount</th>
																<th width="15%">Current Stage</th>
																<th width="20%">Status</th>
																<th width="18%">Final Approver</th>
															</tr>
														</thead>
														<tbody>
														<?php
														foreach ($op_ma_results as $op_ma_row) 
														{
					                						?>
					                						<tr>
																<td><?php echo $op_ma_row['opportunity']?></td>
																<td><?php echo format_advance($op_ma_row['discount'],$op_ma_row['discount_type'])?></td>
																<td><?php if($op_ma_row['status']==1&&$op_ma_row['approval_at']!=''){ echo 'At '.getRoleShortName($op_ma_row['approval_at']);}?></td>
																<td><?php echo getMarginAnalysisStatus($op_ma_row['status']); if($op_ma_row['status']==3){ echo ' At '.getRoleShortName($op_ma_row['close_at']);}?></td>
																<td><?php if($op_ma_row['close_at']!=''){ echo getRoleShortName($op_ma_row['close_at']);}?></td>
															</tr>
					                						<?php
					                					}
														?>
														</tbody>
													</table>					                    	
							                    </div>
							                </div>
					                		<?php
					                		
					                	}
					                }
					                ?>
					                
					            </div>    
				            </div>
				        </div>
					</form>
				
				</div>	
				<div class="modal-footer">
					<button type="button" class="btn btn-primary btn-flat md-close" data-dismiss="modal">Ok</button>
				</div>
			</div>	
	</div>	
</div>
<div class="md-overlay"></div>