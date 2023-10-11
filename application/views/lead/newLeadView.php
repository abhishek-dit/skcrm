<?php $this->load->view('commons/main_template', $nestedView);
echo $this->session->flashdata('response'); 

$secondUser = ($checkRole == 1)?'Sales Engineer':'Distributor';
$reporting = 0;
$role = $this->session->userdata('role_id');
if($role == 6 || $role == 7 || $role == 8 || $role == 9 || $role == 10 || $role == 11)
	$reporting = 1;
?>

<div class="row"> 
	<div class="col-sm-12 col-md-12">
		<div class="block-flat">
			<div class="header">

			<form class="form-horizontal" role="form" action="#" method="post">	
				<div class="row">
					<div class="col-sm-6">
						<span style="font-size:18px">Enter the Below Details</span>
					</div>
					<div class="col-sm-6" align="right">
						<button type="submit" name="add"  formaction="<?php echo SITE_URL; ?>addCustomer" value="1" class="btn btn-success"><i class="fa fa-plus"></i> Add Customer</button>
						<button type="submit" name="add"  formaction="<?php echo SITE_URL; ?>addContact"  value="1" class="btn btn-success"><i class="fa fa-plus"></i> Add Contact</button>
					</div>	
				</div>
			</form>	
			</div>
			<div class="content">
				<form class="form-horizontal lead_form" role="form" action="<?php echo SITE_URL; ?>newLeadAdd"  id="submitLead"  parsley-validate novalidate method="post">
					<input type="hidden" name="role" id="role" value="<?php echo $role; ?> ">
					<input type="hidden" name="role_type" value="<?php echo $reporting; ?> ">
					<input type="hidden" name="checkSelf" value="1" id="checkSelf">

					<?php if($reporting == 1) { ?>
					<div class="form-group">
					<div class="col-sm-3"></div>
						<div class="col-sm-6" align="right">
							<div class="btn-group">
 								<input class="btn btn-success self" type="button" value="Self" checked="" name="self" data-id="1">
								<input class="btn btn-default self" type="button" value="Assign to Others" name="self" data-id="0">	
							</div>
						</div>
					</div>
					<?php } else { ?>
					<input type="hidden" name="self" value="1">
					<?php } ?>
					<div class="form-group">
						<label for="inputName" class="col-sm-3 control-label">Source of Lead<span class="req-fld">*</span></label>
						<div class="col-sm-6">
                            <?php echo form_dropdown('source', $SourceOfLead, '','class="select2" id="source" required'); ?>
						</div>
					</div>
					<div class="form-group campaign hidden">
						<label for="inputName" class="col-sm-3 control-label">Campaign<span class="req-fld">*</span></label>
						<div class="col-sm-6">
							<select name="campaign" style="width:100%" class="checkCampaign" id="campaign">
								<option value="">Select Campaign</option>
							</select>
						</div>
					</div>
					<div class="form-group colleague hidden">
						<label for="inputName" class="col-sm-3 control-label">Colleague<span class="req-fld">*</span></label>
						<div class="col-sm-6">
							<select name="user3" style="width:100%" class="getColleague" id="colleague">
								<option value="">Select Colleague</option>
							</select>
						</div>
					</div>
					<div class="form-group referral hidden">
						<label for="inputName" class="col-sm-3 control-label">Referral Name<span class="req-fld">*</span></label>
						<div class="col-sm-6">
							<input type="text" class="form-control" id="ref" maxlength="50" placeholder="Referral Name" name="referral">
						</div>
					</div>

					<div class="form-group">
						<label for="inputName" class="col-sm-3 control-label">Customer<span class="req-fld">*</span></label>
						<div class="col-sm-6">
							<select class="checkCustomer" style="width:100%" required name="customer">
								<option value="">Select Customer</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="inputName" class="col-sm-3 control-label">Rapport with Customer<span class="req-fld">*</span></label>
						<div class="col-sm-6">
                            <?php echo form_dropdown('relationship', $rapport, '','class="select2" required'); ?>
						</div>
					</div>


					<?php if($reporting == 1) { ?>
					<div class="form-group assign hidden">
						<label for="inputName" class="col-sm-3 control-label">Assign To<span class="req-fld">*</span></label>
						<div class="col-sm-6">
							<?php 
							$reporteeInfo = array(''=>'Select User to Assign');
							 ?>
                            <?php echo form_dropdown('assign', $reporteeInfo, '','id="reportees" class="select2"'); ?>
						</div>
					</div>
					<?php } ?>
					<div class="form-group">
						<label for="inputName" class="col-sm-3 control-label">Contact Person 1<span class="req-fld">*</span></label>
						<div class="col-sm-6">
							<?php 
							$ContactInfo = array(''=>'Select Contact Person');
							 ?>
                            <?php echo form_dropdown('contact1', $ContactInfo, '','required id="contact1" class="select2"'); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="inputName" class="col-sm-3 control-label">Contact Person 2</label>
						<div class="col-sm-6">
                            <?php echo form_dropdown('contact2', $ContactInfo, '','id="contact2" class="select2"'); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="inputName" class="col-sm-3 control-label">Purchase Potential (Rs)</label>
						<div class="col-sm-6">
							<input type="text" class="form-control" parsley-type="number" maxlength="50" placeholder="Purchase Potential" name="purchase_potential">
						</div>
					</div>
					<div class="form-group">
						<label for="inputName" class="col-sm-3 control-label">Site Readiness<span class="req-fld">*</span></label>
						<div class="col-sm-6">
                            <?php echo form_dropdown('site', $site_readiness, '','class="select2" required'); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="inputName" class="col-sm-3 control-label">Visit Requirement</label>
						<div class="col-sm-6">
		                  <label class="radio-inline"> <input type="radio" name="visit_requirement" value="1" class="icheck"> Yes</label> 
		                  <label class="radio-inline"> <input type="radio" checked="" name="visit_requirement" value="0" class="icheck"> No</label> 
						</div>
					</div>
					<div class="form-group">
						<label for="inputName" class="col-sm-3 control-label">Resource Requirement</label>
						<?php 
							$ck5 = (@$leadDetails['resource_requirement'] == 1)?'checked':'';
							$ck6 = (@$leadDetails['resource_requirement'] == 1)?'':'checked';
						?>
						<div class="col-sm-6">
						  <label class="radio-inline"> 
							  <div class="iradio_square-blue <?php echo $ck5;?>" style="position: relative;" aria-checked="true" aria-disabled="false">
								  <input type="radio" class="resource_requirement" value="1" name="resource_requirement" <?php echo $ck5;?> style="position: absolute; opacity: 0;">
								  <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;"></ins>
							  </div> 
							  Yes
						  </label>
						  <label class="radio-inline"> 
							  <div class="iradio_square-blue <?php echo $ck6;?>" style="position: relative;" aria-checked="true" aria-disabled="false">
								  <input type="radio" class="resource_requirement" value="0" name="resource_requirement" <?php echo $ck6;?> style="position: absolute; opacity: 0;">
								  <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;"></ins>
							  </div> 
							  No
						  </label>
					      <!--<label class="radio-inline"> <input type="radio" <?php echo $ck5; ?> name="resource_requirement" value="1" class="icheck"> Yes</label> 
					      <label class="radio-inline"> <input type="radio" <?php echo $ck6; ?> name="resource_requirement" value="0" class="icheck"> No</label>
					      -->
						</div>
					</div>
					<div class="form-group hidden" id="resource_info_fld">
						<label for="inputName" class="col-sm-3 control-label">Resource Required Information<span class="req-fld">*</span></label>
						<div class="col-sm-6">
					      <textarea name="resource_required_details" id="resource_required_details" class="form-control"></textarea> 
						</div>
					</div>

					<?php if($checkRole == 0) { ?>
					<!-- <div class="form-group">
						<label for="inputName" class="col-sm-3 control-label"><?php //echo $secondUser; ?><span class="req-fld sec hidden">*</span></label>
						<div class="col-sm-6">
							<input type="hidden" name="checkRole" value="<?php //echo $checkRole; ?>">
                            <?php
                            // $secondUserInfo = array(''=>'Select '.$secondUser);
                            // echo form_dropdown('second_user', $secondUserInfo, '','id="secondUser" class="select2"'); ?>
						</div>
					</div> -->
					<?php }
					else{ ?>
						<input type="hidden" name="second_user" value="">
						<?php } ?>
					<div class="form-group">
						<label for="inputName" class="col-sm-3 control-label">Comment Line 1</label>
						<div class="col-sm-6">
							<textarea class="form-control"   maxlength="255"  name="remarks2"></textarea>
						</div>
					</div>
					<div class="form-group">
						<label for="inputName" class="col-sm-3 control-label">Comment Line 2</label>
						<div class="col-sm-6">
							<textarea class="form-control"   maxlength="255"  name="remarks3"></textarea>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-3 col-sm-10" id="contactCheck">
						</div>
					</div>

					<div class="form-group">
						<div class="col-sm-offset-3 col-sm-10">
							<button class="btn btn-primary" type="submit" name="submitLead" value="button"><i class="fa fa-check"></i> Submit</button>
							<a class="btn btn-danger" href="<?php echo SITE_URL;?>newLead"><i class="fa fa-times"></i> Cancel</a>
						</div>
					</div>
				</form>
			</div>

			<?php
			//echo getQueryArray(getUserLocations(13));
			//echo $this->session->userdata('check');
			//echo '<br>'.$this->db->last_query();
			?>

		</div>
	</div>
</div>


<?php 
//print_r($_SESSION);
$this->load->view('commons/main_footer.php', $nestedView); ?>
