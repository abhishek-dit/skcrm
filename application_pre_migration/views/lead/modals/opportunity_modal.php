<?php
$product_disable = ''; $fld_status = ''; $isView = false; $fld_disable = '';
if(@$row['opportunity_id']>0)
{
	$product_disable = ' disabled';
	$quoteCount = getOpenQuoteCountforOpportunity(@$row['opportunity_id']);
	$opportunity_status_arr = @$edit_opportunity_status1;
	if($quoteCount > 0) $opportunity_status_arr = $edit_opportunity_status2;

	if(!(@$lead['user_id'] == $this->session->userdata('user_id')) || @$row['status'] == 6 || @$row['status'] == 7 || @$row['status'] == 8 || $leadStatusID == 19)
	{
		$fld_status = ' readonly'; $fld_disable = 'disabled';
		$action = '#'; $isView = true;
		$modal_header_title = 'Opportunity Details';
		$opportunity_status_arr = @$edit_opportunity_status3;
	}
}
else $opportunity_status_arr = @$opportunity_status;
$formClass = (@$encoded_id == '')?'opportunity-form1':'opportunity-form';
?>
<!--<div class="md-modal colored-header custom-width md-effect-9" id="<?php //echo @$pop_id;?>">-->
<div class="modal fade colored-header" id="<?php echo @$pop_id;?>" role="dialog">
	<div class="modal-dialog">
		<form action="<?php echo @$action;?>" method="post" novalidate="" parsley-validate="" class="<?php echo $formClass; ?> form-horizontal">
			<input type="hidden" name="en_op_id" value="<?php if(@$row['opportunity_id']>0)echo @icrm_encode(@$row['opportunity_id']);?>">
			<div class="md-content">
				<div class="modal-header">
					<span style="font-size:18px"><?php echo @$modal_header_title;?></span>
					<button type="button" class="close md-close" data-dismiss="modal" aria-hidden="true">&times;</button>
				</div>

				<div class="modal-body form">
					<div class="row">
						<div class="col-sm-6 col-md-6">
							<div class="formContentBlock">
								<?php if(@$encoded_id != '') { ?>
								<input type="hidden" name="checkAdd" value="1">
								<input type="hidden" name="encoded_id" value="<?php echo @$encoded_id;?>">
								<?php } else { ?>
								<div class="form-group">
									<input type="hidden" name="checkAdd" value="0">
									<label for="inputCategoryName">Select Lead <span class="req-fld">*</span></label>
									<?php echo form_dropdown('encoded_id', $leads, '',' id="leads" class="select2 category" required'); ?>
								</div>
								<?php } ?>

								<div class="form-group">
									<label for="inputCategoryName">Product Category <span class="req-fld">*</span></label>
									<?php echo form_dropdown('category', $categories, @$row['category_id'],'class="select2 category" required'.$product_disable); ?>
								</div>
								<div class="form-group">
									<?php
									$groups = array(''=>'Select Group');
									if(@$row['product_id']>0){

										$pg_results = getGroupsByCategory(@$row['category_id']);
										foreach ($pg_results as $pg_row) {
											$groups[$pg_row['group_id']]=$pg_row['name'];
										}
									}
									?>
									<label for="inputGroupName">Product Segment <span class="req-fld">*</span></label>
									<?php echo form_dropdown('group', $groups, @$row['group_id'],'class="select2 group" required'.$product_disable); ?>
								</div>
								<div class="form-group">
									<?php
									$products = array(''=>'Select Product');
									if(@$row['product_id']>0){
										$p_results = getProductsByGroup(@$row['group_id']);
										foreach ($p_results as $p_row) {
											$products[$p_row['product_id']]=$p_row['name'].'('.$p_row['description'].')';
										}
									}
									?>
									<label for="inputProductName">Product Name <span class="req-fld">*</span></label>
									<?php 
									if($product_disable != '')
									{
										?>
										<input type="hidden" name='product' value='<?php echo @$row['product_id']; ?>'>
										<?php
									}
									echo form_dropdown('product', $products, @$row['product_id'],'class="select2 product" required'.$product_disable); ?>
								</div>
								<div class="form-group">
									<label for="inputProductName">Quantity<span class="req-fld">*</span></label>
									<input type="number" name="required_quantity" <?php echo $fld_status;?> value="<?php echo @$row['required_quantity'];?>" id="required_quantity" placeholder="Enter Quantity" class="form-control" min="1" required>
								</div>
								<div class="form-group">
									<label for="inputProductName">Source of Funding<span class="req-fld">*</span></label>
									<select class="select2" required name="source_of_funds" id="source_of_funds" <?php echo $fld_disable;?>>
									<option value="">Select funding source</option>
									<?php 
									foreach ($source_of_funds as $sof_row) {
										$sof_selected = ($sof_row['fund_source_id']==@$row['fund_source_id'])?'selected':'';
										echo '<option value="'.$sof_row['fund_source_id'].'" '.$sof_selected.'>'.$sof_row['name'].'</option>';
									}
									?>
									</select>
								</div>
								<div class="form-group">
									<label for="inputProductName">Expected Order Conclusion Date<span class="req-fld">*</span></label>
									<input type="text" class="form-control dateFromToday" <?php echo $fld_status;?> value="<?php echo @$row['expected_order_conclusion'];?>" name="expected_order_conclusion" placeholder="YYYY-MM-DD" required>
								</div>
									<div class="form-group">
									<label for="inputProductName">Expected Invoice Date</label>
									<input type="text" class="form-control dateFromToday" <?php echo $fld_status;?> value="<?php if(@$row['expected_invoicing_date']!='0000-00-00')echo @$row['expected_invoicing_date'];?>" name="expected_invoicing_date" placeholder="YYYY-MM-DD">
								</div>
								
								<?php if(@$encoded_id != '') { ?>
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
								<?php } ?>
								<!-- phase2 changes prasad -->
								<?php $lost_competitor_cls = (@$row['lost_competitor_id'] !='')?'':'hidden'; ?>
								<div class="form-group opp_lost_comp_div <?php echo $lost_competitor_cls; ?> ">
									<label for="opportunityLostReason">Lost Competitor<span class="req-fld">*</span></label>
									<select class="select2 opp_lost_competitor" name="opp_lost_competitor" <?php echo $fld_disable;?> >
									<option value="">Select Lost Competitor</option>
									<?php 

									foreach ($lost_competitors as $st_row) {
										$s_selected = ($st_row['competitor_id']==@$row['lost_competitor_id'])?'selected':'';
										echo '<option value="'.$st_row['competitor_id'].'" '.$s_selected.'>'.$st_row['name'].'</option>';
									}
									?>
									</select>
								</div>
								<?php $model_cls = (@$row['status']==7)?'':'hidden';
								 ?>
								<div class="form-group model_fld_blk <?php echo $model_cls;?>">
									<label for="models" class="model_text">Model<span class="req-fld">*</span></label>
									<!-- <textarea name="model"  <?php echo $fld_disable;?>   class="form-control model" rows="3"><?php echo @$row['model'];?></textarea> -->
									<input type="text" name="model"  <?php echo $fld_disable;?>  class="form-control model" value="<?php echo @$row['model'];?>">
								</div>
								<!-- end prasad -->
							</div>
						</div>

						<div class="col-sm-6 col-md-6">
							<div class="formContentBlock">
								<?php if(@$encoded_id != '') { ?>
									<div class="form-group">
										<label for="inputProductName">Decision Maker1<span class="req-fld">*</span></label>
										<select class="select2_decision_maker decision_maker1" <?php echo $fld_disable;?> name="decision_maker1"  required style="width:100%">
										<?php
										if(@$row['decision_maker1']>0){
											$decision_maker_details = getDecisionMakerDetails(@$row['decision_maker1']);
											echo '<option value="'.@$row['decision_maker1'].'">'.$decision_maker_details.'</option>';
										}
										else{
											echo '<option value="">Select Decision Maker</option>';
										}
										?>

										</select>
									</div>
									<?php
									for($i=2;$i<=5;$i++){
									?>
									<div class="form-group">
										<label for="inputProductName">Decision Maker<?php echo $i;?></label>
										<select class="select2_decision_maker decision_maker<?php echo $i;?>" <?php echo $fld_disable;?> name="decision_maker<?php echo $i;?>" style="width:100%">
										<?php
										if(@$row['decision_maker'.$i]>0){
											$decision_maker_details = getDecisionMakerDetails(@$row['decision_maker'.$i]);
											echo '<option value="'.@$row['decision_maker'.$i].'">'.$decision_maker_details.'</option>';
										}
										else{
											echo '<option value="">Select Decision Maker</option>';
										}
										?>
										</select>
									</div>
									<?php
									}
								}
								else
								{
									for($i=1;$i<=5;$i++)
									{ ?>
									<div class="form-group">
										<label for="inputProductName">Decision Maker<?php echo $i;?><?php if($i == 1) { ?> <span class="req-fld">*</span> <?php } ?></label>
										<select class="select2"  name="decision_maker<?php echo $i;?>" id="decision_maker<?php echo $i; ?>" <?php if($i == 1) { ?> required <?php } ?> style="width:100%">
											<option value="">Select Decision Maker</option>
										</select>
									</div>
									<?php
									}
								}
								?>

								<div class="form-group">
									<label for="inputProductName">Relationship with Decision Maker<span class="req-fld">*</span></label>
									<select class="select2" name="relationship_with_decision_maker" <?php echo $fld_disable;?> id="relationship_with_decision_maker" required>
									<option value="">Select</option>
									<?php 
									foreach ($relationship as $rel_row) {
										$r_selected = ($rel_row['relationship_id']==@$row['relationship_id'])?'selected':'';
										echo '<option value="'.$rel_row['relationship_id'].'" '.$r_selected.'>'.$rel_row['name'].'</option>';
									}
									?>
									</select>
								</div>
								<div class="form-group">
									<label for="inputProductName">Status<span class="req-fld">*</span></label>
									<select class="select2 op_status" name="status" <?php echo $fld_disable;?> required>
									<option value="">Select</option>
									<?php 

									foreach ($opportunity_status_arr as $st_row) {
										$s_selected = ($st_row['status']==@$row['status'])?'selected':'';
										echo '<option value="'.$st_row['status'].'" '.$s_selected.'>'.$st_row['name'].'</option>';
									}
									?>
									</select>
								</div>
								<!-- phase2 changes prasad -->
								<?php $lost_reason_cls = (@$row['oppr_lost_id'] !='')?'':'hidden'; ?>
								<div class="form-group opp_lost_reason_div <?php echo $lost_reason_cls ;?>">
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
								<?php $remarks2_cls = (@$row['oppr_lost_id']==15||@$row['status']==8)?'':'hidden';
								$remarks_text = 'Reason'; $remarks_required = '';
								if(@$row['oppr_lost_id']==15){
									$remarks_text = 'Lost Reason <span class="req-fld">*</span>';
									$remarks_required = 'required';
								}
								else if(@$row['status']==8){
									$remarks_text = 'Drop Reason <span class="req-fld">*</span>';
									$remarks_required = 'required';
								}
								?>
								<div class="form-group remarks_fld_blk <?php echo $remarks2_cls;?>">
									<label for="Remarks" class="remarks_text"><?php echo $remarks_text;?></label>
									<!-- <textarea name="remarks2"  <?php echo $fld_disable;?> <?php echo $remarks_required;?>  class="form-control remarks2" rows="3"><?php echo @$row['remarks2'];?></textarea> -->
									<input type="text" name="remarks2"  <?php echo $fld_disable;?> <?php echo $remarks_required;?> class="form-control remarks2" value="<?php echo @$row['remarks2'];?>">
								</div>
								<?php $comp_remarks2_cls = (@$row['lost_competitor_id']==29)?'':'hidden';
								$comp_remarks_text = 'Reason'; 
								$comp_remarks_text = 'Lost Competitor <span class="req-fld">*</span>';
							    $comp_remarks_required = '';
								
								?>
								<div class="form-group comp_remarks_fld_blk <?php echo $comp_remarks2_cls;?>">
									<label for="Remarks" class="comp_remarks_text"><?php echo $comp_remarks_text;?></label>
									<!-- <textarea name="comp_remarks2"  <?php echo $fld_disable;?> <?php echo $comp_remarks_required;?>  class="form-control comp_remarks2" rows="3"><?php echo @$row['remarks3'];?></textarea> -->
									<input type="text" name="comp_remarks2"  <?php echo $fld_disable;?> <?php echo $comp_remarks_required;?> class="form-control comp_remarks2" value="<?php echo @$row['remarks3'];?>">
								</div>
								<!-- end prasad -->
								<?php if(@$encoded_id == '') { ?>
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
								<?php } ?>
							</div>
						</div>
					</div>
				</div>	
				<div class="modal-footer">
					<span class="opp_error error" style="color:red;float:left;font-weight:bold;"></span>
					<?php if(@$isView){?>
						<button type="button" class="btn btn-primary btn-flat md-close cancel" data-dismiss="modal">Close</button>
					<?php
					}
					else {
					?>
						<button type="button" class="btn btn-default btn-flat md-close cancel" data-dismiss="modal">Cancel</button>
						<input type="submit" class="btn btn-primary btn-flat" name ="submit" value="submit">
					<?php
					}
					?>
				</div>
			</div>	
		</form>
	</div>	
</div>
<div class="md-overlay"></div>