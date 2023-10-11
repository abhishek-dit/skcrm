<?php
//$opportunity_status_arr = @$edit_opportunity_status;
$fld_status = ' readonly';
$fld_disable = 'disabled';
$modal_header_title = 'Opportunity Details';
$product_disable = ' disabled';
$action = '#';
$isView = true;


// changed on 26-07-2021 for distributor
$quoteCount = getOpenQuoteCountforOpportunity(@$row['opportunity_id']);
	$opportunity_status_arr = @$edit_opportunity_status1;
	if($quoteCount > 0) $opportunity_status_arr = $edit_opportunity_status2;
// end

// added on 28-06-2021 for distributor role
if(!(@$lead['user_id'] == $this->session->userdata('user_id')) || @$row['status'] == 6 || @$row['status'] == 7 || @$row['status'] == 8 || $leadStatusID == 19 || @$row['status'] == 10)
{
	if($_SESSION['role_id'] == '7' && @$row['status'] != 10)
	{
		$action = SITE_URL.'updateOpportunityDistributorRole'; $pop_id = 'op_modal'.$row['opportunity_id'];
	}
	else
	{
		$action = '#'; $isView = true;
	}
	// $fld_status = ' readonly'; $fld_disable = 'disabled';
	// // $action = '#'; $isView = true;
	// $modal_header_title = 'Opportunity Details';
	// $opportunity_status_arr = @$edit_opportunity_status3;
}
$formClass = (@$encoded_id == '')?'opportunity-form1':'opportunity-form';
// added on 28-06-2021 for distributor role end

?>
<!--<div class="md-modal colored-header custom-width md-effect-9" id="<?php //echo @$pop_id;?>">-->
<div class="modal fade colored-header" id="<?php echo @$pop_id;?>" role="dialog">
	<div class="modal-dialog">
		<form action="<?php echo @$action;?>" method="post" novalidate="" parsley-validate="" class="<?php echo $formClass; ?> form-horizontal">
		<input type="hidden" name="en_op_id" value="<?php if(@$row['opportunity_id']>0)echo @icrm_encode(@$row['opportunity_id']);?>">
		<?php 
		if($_SESSION['role_id'] == '7' && @$row['status'] != 10)
		{
		?>
			<input type="hidden" name="u_op_id" value="1">
			<input type="hidden" name="checkAdd" value="1">
			<input type="hidden" name="encoded_id" value="<?php echo @$encoded_id;?>">
		<?php } ?>
			<div class="md-content">
				<div class="modal-header">
					<span style="font-size:18px"><?php echo @$modal_header_title;?></span>
					<button type="button" class="close md-close" data-dismiss="modal" aria-hidden="true">&times;</button>
				</div>

				<div class="modal-body form">
	
					<div class="row">
						<div class="col-sm-6 col-md-6">
							<div class="formContentBlock">
								<div class="form-group">
									<label for="inputCategoryName">Product Category </label>
									<input type="text" readonly="" class="form-control" value="<?php echo @$row['category']; ?>">
								</div>
								<div class="form-group">
									<label for="inputGroupName">Product Segment </label>
									<input type="text" readonly="" class="form-control" value="<?php echo @$row['group']; ?>">
								</div>
								<div class="form-group">
									<label for="inputProductName">Product Name </label>
									<input type="text" readonly="" class="form-control" value="<?php echo @$row['name']; ?>">
								</div>
								<div class="form-group">
									<label for="inputProductName">Quantity</label>
									<input type="text" readonly="" class="form-control" value="<?php echo @$row['required_quantity']; ?>">
								</div>
								<div class="form-group">
									<label for="inputProductName">Source of Funding</label>
									<input type="text" readonly="" class="form-control" value="<?php echo @$row['source_of_fund']; ?>">
								</div>
								<div class="form-group">
									<label for="inputProductName">Expected Order Conclusion Date</label>
									<input type="text" class="form-control datepicker" <?php echo $fld_status;?> value="<?php echo @$row['expected_order_conclusion'];?>" name="expected_order_conclusion" placeholder="YYYY-MM-DD" required>
								</div>
									<div class="form-group">
									<label for="inputProductName">Expected Invoice Date</label>
									<input type="text" class="form-control datepicker" <?php echo $fld_status;?> value="<?php if(@$row['expected_invoicing_date']!='0000-00-00')echo @$row['expected_invoicing_date'];?>" name="expected_invoicing_date" placeholder="YYYY-MM-DD">
								</div>
								<div class="form-group">
									<label for="inputProductName">Competitors</label>
									<select class="select2 opportunity_competitors" multiple="" name="opportunity_competitors[]" <?php echo $fld_disable;?> id="">
									<option value="">Select</option>
									<?php
									if(@$row['product_id']>0){

										$Competitors = getCompetitorsByProductCategory(@$row['category_id']);
										$op_comp_res =getOpportunityCompetitors(@$row['opportunity_id']);
										$op_competitors = array();
										foreach ($op_comp_res as $op_comp_row) {
											$op_competitors[]=$op_comp_row['competitor_id'];
										}
										foreach ($Competitors as $com_row) {
											$comp_selected = (in_array($com_row['competitor_id'],$op_competitors))?'selected':'';
											echo '<option value="'.$com_row['competitor_id'].'" '.$comp_selected.'>'.$com_row['name'].'</option>';
										}
									}
									?>
									</select>
								</div>
								<!-- phase2 changes prasad -->
								<?php
								 if($row['lost_competitor_id'] !='' || $row['lost_competitor_id'] !=0) { ?>
								<div class="form-group opp_lost_comp_div  ">
									<label for="opportunityLostReason">Lost Compititor<span class="req-fld">*</span></label>
									<select class="select2 opp_lost_competitor" name="opp_lost_competitor" <?php echo $fld_disable;?> >
									<option value="">Select Lost Compititor</option>
									<?php 

									foreach ($lost_competitors as $st_row) {
										$s_selected = ($st_row['competitor_id']==@$row['lost_competitor_id'])?'selected':'';
										echo '<option value="'.$st_row['competitor_id'].'" '.$s_selected.'>'.$st_row['name'].'</option>';
									}
									?>
									</select>
								</div>
								<?php } ?>
								<!--end prasad -->
							</div>
						</div>

						<div class="col-sm-6 col-md-6">
							<div class="formContentBlock">
								<?php
								for($i=1;$i<=5;$i++){
								?>
								<div class="form-group">
									<label for="inputProductName">Decision Maker<?php echo $i;?></label>
									<input type="text" value="<?php echo (@$row['decision_maker'.$i] > 0)?getDecisionMakerDetails(@$row['decision_maker'.$i]): ''; ?>" class="form-control" readonly>
								</div>
								<?php
								}
								?>
								<div class="form-group">
									<label for="inputProductName">Relationship with Decision Maker</label>
									<input type="text" readonly="" class="form-control" value="<?php echo @$row['relationship']; ?>">
								</div>
								<div class="form-group">
									<label for="inputProductName">Status</label>
									<!-- <input type="text" readonly="" class="form-control" value="<?php //echo @$row['stage']; ?>"> -->

									<!-- changed for distributor role on 16-06-2021 -->
									<?php
									if(($_SESSION['role_id'] == '7' && @$row['status'] != 10 && $u_role_id[0]['role_id'] == '5') && ($_SESSION['role_id'] == '7' && @$row['status'] != 8 && $u_role_id[0]['role_id'] == '5') && ($_SESSION['role_id'] == '7' && @$row['status'] != 7 && $u_role_id[0]['role_id'] == '5'))
									{ ?>
									<select class="select2 op_status" name="status"  required>
									<?php }
									else{
									?>
									<select class="select2 op_status" name="status" <?php echo $fld_disable;?> required>
									<?php } ?>
									<!-- changed for distributor role on 16-06-2021 end -->

									<option value="">Select</option>
									<?php 

									foreach ($opportunity_status_arr as $st_row) {
										$s_selected = ($st_row['status']==@$row['status'])?'selected':'';
										echo '<option value="'.$st_row['status'].'" '.$s_selected.'>'.$st_row['name'].'</option>';
									}
									?>
									</select>

								</div>


								<!-- changed made on 29-06-2021 for distributor role -->
								<?php 
								// if($u_role_id[0]['role_id'] == '5' && @$row['status']==10)
								if(@$row['status']==10)
								{
									// echo "<pre>";print_r($_SESSION['role_id']);
									if($_SESSION['role_id'] == '4' || $_SESSION['role_id'] == '7' || $_SESSION['role_id'] == '8' || $_SESSION['role_id'] == '9')
									{
								?>
										<div class="form-group">
											<label for="inputProductName">Reason<span class="req-fld">*</span></label>
											<input type="text" class="form-control" name="reason" id="reason" value="<?php echo @$row['remarks2'];?>" <?php echo $fld_disable;?>>
											
										</div>
								<?php } 
								}?>
								<!-- changed made on 29-06-2021 for distributor role end -->


								<!-- phase2 changes prasad -->
								<div class="form-group opp_lost_reason_div <?php if($row['oppr_lost_id'] ==0 || $row['oppr_lost_id'] =='' ){ echo "hidden"; } ?>">
									<label for="opportunityLostReason">Lost Reason<span class="req-fld">*</span></label>
									<select class="select2 opp_lost_reason" name="opp_lost_reason" <?php echo $fld_disable;?> >
									<option value="">Select Lost Reason</option>
									<?php 

									foreach ($opp_lost_reasons as $st_row) {
										$s_selected = ($st_row['reason_id']==@$row['oppr_lost_id'])?'selected':'';
										echo '<option value="'.$st_row['reason_id'].'" '.$s_selected.'>'.$st_row['name'].'</option>';
									}
									?>
									</select>
								</div>
								
								<?php $remarks2_cls = (@$row['status']==7||@$row['status']==8)?'':'hidden';
								$remarks_text = 'Reason'; $remarks_required = '';
								if(@$row['status']==7){
									$remarks_text = 'Lost Reason';
									$remarks_required = 'required';
								}
								else if(@$row['status']==8){
									$remarks_text = 'Drop Reason';
									$remarks_required = 'required';
								}
								// added on 26-07-2021 for distributor role
								else if(@$row['status']==10){
									$remarks_text = 'Order Collected by Dealer Reason <span class="req-fld">*</span>';
									$remarks_required = 'required';
								}
								// added on 26-07-2021 for distributor role end
								?>
								<div class="form-group remarks_fld_blk <?php echo $remarks2_cls;?>">
									<label for="Remarks" class="remarks_text"><?php echo $remarks_text;?></label>
									<!-- <textarea name="remarks2"  <?php //echo $fld_disable;?>  class="form-control remarks2" rows="3"><?php //echo @$row['remarks2'];?></textarea> -->
									<!-- added on 26-07-2021 for distributor role -->
									<?php
									if($_SESSION['role_id'] == '7')
									{ ?>
									<input type="text" name="remarks2" <?php echo $remarks_required;?> class="form-control remarks2" value="<?php echo @$row['remarks2'];?>">
									<?php } else{ ?>
										<input type="text" name="remarks2"  <?php echo $fld_disable;?> <?php echo $remarks_required;?> class="form-control remarks2" value="<?php echo @$row['remarks2'];?>">
									<?php }?>
									<!-- added on 26-07-2021 for distributor role end -->
								</div>
								<div class="form-group comp_remarks_fld_blk <?php if($row['remarks3'] == '')echo "hidden" ;?>">
									<label for="Remarks" class="comp_remarks_text"><?php echo "Lost Competitor ";?></label>
									<textarea name="comp_remarks2"  <?php echo $fld_disable;?>   class="form-control comp_remarks2" rows="3"><?php echo @$row['remarks3'];?></textarea>
								</div>
								<!-- end prasad -->
							</div>
						</div>
					</div>
				</div>	
				<div class="modal-footer">
					<span class="opp_error error" style="color:red;float:left;font-weight:bold;"></span>
					<?php 
					// added on 28-06-2021 for distributor role
					if($_SESSION['role_id'] == '7' && isset($row['status']) == 10)
					{?>
						<button type="button" id="reset_modal" class="btn btn-default btn-flat md-close cancel" data-dismiss="modal">Cancel</button>
						<input type="submit" class="btn btn-primary btn-flat" name ="submit" value="submit">	
						
					<?php }
					else
					{
						if(@$isView){?>
							<button type="button" class="btn btn-primary btn-flat md-close cancel" data-dismiss="modal">Close</button>
						<?php
						}
						else {
						?>
							<button type="button" id="reset_modal" class="btn btn-default btn-flat md-close cancel" data-dismiss="modal">Cancel</button>
							<input type="submit" class="btn btn-primary btn-flat" name ="submit" value="submit">
						<?php
						}
					} ?>
					 <!-- added on 28-06-2021 for distributor role end -->
					 
					<!-- // if(@$isView){ -->
						
						<!-- <button type="button" class="btn btn-primary btn-flat md-close" data-dismiss="modal">Close</button> -->
					<?php
					// }
					// else {
					?>
						<!-- <button type="button" class="btn btn-default btn-flat md-close" data-dismiss="modal">Cancel</button>
						<button type="submit" class="btn btn-primary btn-flat">Submit</button> -->
					<?php
					// }
					?>
				</div>
			</div>	
		</form>
	</div>	
</div>
<div class="md-overlay"></div>