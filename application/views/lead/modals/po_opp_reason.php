<!--<div class="md-modal colored-header custom-width md-effect-9" id="<?php //echo @$pop_id;?>">-->
<div class="modal fade colored-header"  id="<?php echo @$pop_id;?>" role="dialog">
	<div class="modal-dialog">
			<div class="md-content">
				<div class="modal-header">
					<span style="font-size:18px"><?php echo @$modal_header_title;?></span>
					<button type="button" class="close md-close" data-dismiss="modal" aria-hidden="true">&times;</button>
				</div>
				<form action="<?php echo SITE_URL.'po_opp_status';?>" method="post" class="form-horizontal">
				<div class="modal-body form">
					
		                <div class="row">
						<div class="col-md-offset-3 col-md-6">
							<div class="formContentBlock">
								<div class="form-group ">
									<label for="inputProductName">Status<span class="req-fld">*</span></label>
									<select class="select2 op_status" name="status" required>
									<option value="">Select</option>
									<?php 

									foreach ($opportunity_status as $st_row) {
										echo '<option value="'.$st_row['status'].'" >'.$st_row['name'].'</option>';
									}
									?>
									</select>
								</div>
								
								<!-- phase2 changes prasad -->
								<div class="form-group opp_lost_reason_div hidden">
									<label for="opportunityLostReason">Lost Reason<span class="req-fld">*</span></label>
									<select class="select2 opp_lost_reason" name="opp_lost_reason" >
									<option value="">Select Lost Reason</option>
									<?php 

									foreach ($opp_lost_reasons as $st_row) {
										
										echo '<option value="'.$st_row['reason_id'].'" >'.$st_row['name'].'</option>';
									}
									?>
									</select>
								</div>
								
								<div class="form-group remarks_fld_blk hidden ">
									<label for="Remarks" class="remarks_text">Lost Reason</label>
									<!-- <textarea name="remarks2"  required  class="form-control remarks2" rows="3"></textarea> -->
									<input type="text" name="remarks2" required class="form-control remarks2">
								</div>
								<input type="hidden" name="opportunity_id" value="<?php echo $row1['opportunity_id'];?>">
								<input type="hidden" name="po_op_id" value="<?php echo $row1['po_op_id'];?>">
								<!-- end prasad -->
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-offset-3 col-md-6">
							<div style="margin:10px">
								<div class="form-group opp_lost_comp_div hidden ">
										<label for="opportunityLostReason">Lost Competitor<span class="req-fld">*</span></label>
										<select class="select2 opp_lost_competitor" name="opp_lost_competitor"  >
										<option value="">Select Lost Competitor</option>
										<?php 

										foreach ($lost_competitors as $st_row) {
											
											echo '<option value="'.$st_row['competitor_id'].'">'.$st_row['name'].'</option>';
										}
										?>
										</select>
									</div>
							
								<div class="form-group comp_remarks_fld_blk hidden ">
										<label for="Remarks" class="comp_remarks_text">Lost Competitor Name</label>
										<!-- <textarea name="comp_remarks2" required  class="form-control comp_remarks2" rows="3"></textarea> -->
										<input type="text" name="comp_remarks2" class="form-control comp_remarks2">
								</div>
								<div class="form-group model_fld_blk hidden ">
										<label for="models" class="model_text">Model<span class="req-fld">*</span></label>
										<input type="text" name="model" class="form-control model">
								</div>
							</div>
						</div>
					</div>
				</div>	
				<div class="modal-footer">
					<button type="button" class="btn btn-default btn-flat md-close" data-dismiss="modal">Cancel</button>
					<button type="submit" class="btn btn-primary btn-flat" value="1">Submit</button>
				</div>
				</form>
			</div>	
	</div>	
</div>
<div class="md-overlay"></div>