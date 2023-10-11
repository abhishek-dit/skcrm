<!--<div class="md-modal colored-header custom-width md-effect-9" id="<?php //echo @$pop_id;?>">-->
<div class="modal fade colored-header" id="action<?php echo @$row['margin_approval_id'];?>" role="dialog">
	<div class="modal-dialog">
			<div class="md-content">
				<form method="post" action="<?php echo SITE_URL.'submitMarginAnalysisApproval'?>" role="form">
					<input type="hidden" name="margin_approval_id" value="<?php echo $row['margin_approval_id']?>">
					<input type="hidden" name="quote_id" value="<?php echo $row['quote_id']?>">
					<input type="hidden" name="quote_revision_id" value="<?php echo $row['quote_revision_id']?>">
					<input type="hidden" name="lead_id" value="<?php echo $row['lead_id']?>">
					<input type="hidden" name="lead_owner" value="<?php echo $row['lead_owner']?>">
					<input type="hidden" name="opportunity_id" value="<?php echo $row['opp_id']?>">
					<input type="hidden" name="approval_type" value="1">
					<div class="modal-header">
						<span style="font-size:18px">Margin Analysis Report</span>
						<button type="button" class="close md-close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>

					<div class="modal-body form">
						<div class="row">
							<label class="col-md-3">Quote ID</label>
							<div class="col-md-9"><?php 
							$revision_id = getQuoteRevisionNumber($row['quote_id'],$row['quote_revision_id']);
							echo $row['tag'].'-Rev-'.$revision_id;?></div>
						</div>
						<div class="row">
							<label class="col-md-3">Lead ID</label>
							<div class="col-md-9"><?php echo $row['lead_number'];?></div>
						</div>
						<div class="row">
							<label class="col-md-3">Lead Name</label>
							<div class="col-md-9"><?php echo $row['customer'];?></div>
						</div>
						<div class="row">
							<label class="col-md-3">Date</label>
							<div class="col-md-9"><?php echo format_date($row['quote_revision_time']);?></div>
						</div>
						<?php if($conditionApproval[0]['condition'] == 0){ ?>
						<div class="row">
							<label class="col-md-3">Opportunity</label>
							<div class="col-md-9"><?php echo $row['opportunity'];?></div>
						</div>
						<?php } else{ ?>

						<div class="row"> <span style="font-size:18px">Opportunity Details</span></div>
						<div class="row">
						<!-- <label class="col-md-3">Opportunity</label>
						<div class="col-md-9"><?php echo $row['opportunity'];?></div> -->
						<table id="table1" style="width:100%" class="kstyle" cellspacing="0" border="1">
	                        <thead>
		                        <tr>
		                            <th class="text-center">Opportunity ID</th>
		                            <th class="text-center">Product</th>
		                            <th class="text-center">Qty</th>
		                            <th class="text-center">Unit MRP</th>
		                            <th class="text-center">Discount %</th>
									<th class="text-center">Warranty</th>
									<th class="text-center">ORC %</th>
		                            <th class="text-center">Quoted Price</th>
		                            <th class="text-center">DP</th>
		                        </tr>
	                        </thead>
	                        <tbody>
	                        	<?php
	                        	foreach ($opportunities as $orow) {
	                        		$order_value = $orow['mrp'];
                                    $discount = $orow['discount'];
                                    if($orow['discount_type']!=''&&$discount!='')
                                    $order_value = ($orow['discount_type']==1)?($order_value*(1-$orow['discount']/100)):($order_value-$discount);
                                    $discount_percentage = round((($orow['mrp'] - $order_value )/$orow['mrp'])*100,2);

	                        		?>
	                        	<tr>
		                            <td><?php echo $orow['opp_number'];?></td>
		                            <td><?php echo $orow['product_name'];?></td>
		                            <td><?php echo $orow['required_quantity'];?></td>
		                            <td><?php echo indian_format_price(round($orow['mrp']/$orow['required_quantity'])).' '.$orow['currency_code'];?></td>
		                            <td><?php echo $discount_percentage;?></td>
									<td><?php echo $orow['warranty'];?></td>
									<td><?php echo $orow['orc'];?></td>
		                            <td><?php echo indian_format_price(round($order_value/$orow['required_quantity'])).' '.$orow['currency_code'];?></td>
		                            <td><?php echo indian_format_price(round($orow['dp']/$orow['required_quantity'])).' '.$orow['currency_code'];?></td>
		                        </tr>
	                        		<?php
	                        	}
	                        	?>
	                        </tbody>
	                    </table>
					</div>
					<?php } ?>

						<?php
						$approval_history = getMarginAnalysisApprovalHistory($row['margin_approval_id']);
						
						if(count($approval_history)>0)
						{
						?>
						<div class="row">
							<div class="col-md-12">
								<div class="table-responsive">
									<table class="table table-bordered hover">
										<thead>
											<tr>
												<th>S.No</th>
												<th>Level</th>
												<th>Remarks / Business Case</th>
												<th>On Date</th>
												<th>Status</th>
											</tr>
										</thead>
										<tbody>
										<?php
										$snum=1;
										foreach ($approval_history as $ah_row) {
											?>
											<tr>
												<td><?php echo $snum++;?></td>
												<td><?php echo getRoleShortName($ah_row['approved_by']).'('.$ah_row['user'].')';?></td>
												<td><?php echo $ah_row['remarks'];?></td>
												<td><?php echo format_date($ah_row['created_time'],'d-m-Y h:i A');?></td>
												<td><?php echo ($ah_row['status']==1)?'Approved':'Rejected'?></td>
											</tr>  
											<?php
										}
										?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<?php
						}
						?>
						 <?php
	                    if($login_user_role_id==$row['approval_at']&&in_array($row['product_id'],$user_products)&&$row['status']==1)
	                    {
	                     ?>
	                     	<br><br>
	                    	<div class="row">
								<label class="col-md-3">Remarks / Business Case <span class="req-fld">*</span></label>
								<div class="col-md-9">
									<textarea name="remarks" class="remarks form-control" required></textarea>
								</div>
							</div>
	                    <?php
	                    }
	                    ?>		
					</div>	
					<div class="modal-footer">
						<?php
	                    if($login_user_role_id==$row['approval_at']&&in_array($row['product_id'],$user_products)&&$row['status']==1)
	                    {
	                     ?>
	                    	<button type="submit" value="1" name="action" class="btn btn-primary" onclick="return confirm('Are you sure you want to Approve?');"><i class="fa fa-thumbs-o-up"></i> Approve</button>
	                    	<button type="submit" value="2" name="action" class="btn btn-danger" onclick="return confirm('Are you sure you want to Reject?');"><i class="fa fa-thumbs-o-down"></i> Reject</button>
	                    <?php
	                    }
	                    ?>
						<button type="button" class="btn btn-info btn-flat md-close" data-dismiss="modal">Cancel</button>
					</div>
				</form>
			</div>	
	</div>	
</div>
<div class="md-overlay"></div>