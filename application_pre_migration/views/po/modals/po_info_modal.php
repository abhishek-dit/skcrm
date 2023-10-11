<!--<div class="md-modal colored-header custom-width md-effect-9" id="<?php //echo @$pop_id;?>">-->
<div class="modal fade colored-header" id="info<?php echo @$row['purchase_order_id'];?>" role="dialog">
	<div class="modal-dialog">
			<div class="md-content">
				<div class="modal-header">
					<span style="font-size:18px">PO Revision History</span>
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
					                    <div class="col-sm-3" align="right">PO ID:</div>
					                    <div class="col-sm-9" align="left"><?php echo @$row['purchase_order_id']; ?></div>
					                </div>
					                <div class="form-group">
					                    <div class="col-sm-3" align="right">Products:</div>
					                    <div class="col-sm-9" align="left"><?php echo @$row['product_details'] ?></div>
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
														<th>Discount</th>
														<th>Total Price</th>
														<th>Extra Warranty</th>
														<th>Grand Total</th>
													    <th>Status</th>
													</tr>
												</thead>
												<tbody>
												<?php 
												$poRevisions = getPoRevisions($row['purchase_order_id']);
												$i = 1; $current_waiting_revision = '';
												foreach($poRevisions as $row) 
												{
													if($i==count($poRevisions)&&($row['po_revision_status']==1))
													{
														$current_waiting_revision = $row['po_revision_id'];
													}
													$discount = ($row['total_mrp']-$row['total_order_value'])*100/$row['total_mrp'];
													?>
													<tr>
														<td><?php echo $i; ?></td>
														<td><?php echo round($discount,2); ?>%</td>
														<td><?php echo indian_format_price(round($row['total_order_value'])); ?></td>
														<?php if($row['warranty'] > $row['default_warranty'])
														{ 
															$results=get_extra_warranty_cost($row['total_order_value'],$row['dp_value'],$row['warranty'],$row['default_warranty']); ?>
															<td><?php echo indian_format_price($results['war_dis_value']); ?></td>
															<td><?php echo indian_format_price($results['grand_total']); ?></td>

													<?php	}
													else{ ?>
															<td align="center">--</td>
															<td><?php echo indian_format_price(round($row['total_order_value'])); ?></td>
													<?php	} ?>
														<td><?php echo getPoRevisionStatusLable($row['po_revision_status'],$row['po_status']); ?></td>
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
					                	/*$op_ma_results = getOpportunityMarginAnalysisStatus($current_waiting_revision);
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
					                		
					                	}*/
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