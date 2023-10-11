<!--<div class="md-modal colored-header custom-width md-effect-9" id="<?php //echo @$pop_id;?>">-->
<div class="modal fade colored-header" id="action<?php echo @$row['approval_id'];?>" role="dialog">
	<div class="modal-dialog">
			<div class="md-content">
				<form method="post" action="<?php echo SITE_URL.'submitPoApproval'?>" role="form">
					<input type="hidden" name="approval_id" value="<?php echo $row['approval_id']?>">
					<input type="hidden" name="purchase_order_id" value="<?php echo $row['purchase_order_id']?>">
					<input type="hidden" name="po_revision_id" value="<?php echo $row['po_revision_id']?>">
					<input type="hidden" name="product_id" value="<?php echo $row['product_id']?>">
					<input type="hidden" name="distributor_id" value="<?php echo $row['user_id']?>">
					<div class="modal-header">
						<span style="font-size:18px">Margin Analysis Report</span>
						<button type="button" class="close md-close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>

					<div class="modal-body form">
						<div class="row">
							<label class="col-md-3">PO ID</label>
							<div class="col-md-9"><?php echo $row['po_number'];?></div>
						</div>
						<div class="row">
							<label class="col-md-3">Product</label>
							<div class="col-md-9"><?php echo $row['product_details'];?></div>
						</div>
						<?php
						$approval_history = getPoApprovalHistory($row['approval_id']);
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
	                    if($login_user_role_id==$row['approval_at'])
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
	                    if($login_user_role_id==$row['approval_at'])
	                    {
	                     ?>
	                    	<button type="submit" value="1" name="action" class="btn btn-primary" ><i class="fa fa-thumbs-o-up"></i> Approve</button>
	                    	<button type="submit" value="2" name="action" class="btn btn-danger"><i class="fa fa-thumbs-o-down"></i> Reject</button>
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