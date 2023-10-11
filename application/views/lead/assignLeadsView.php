<?php $this->load->view('commons/main_template', $nestedView);
echo $this->session->flashdata('response'); 

?>

<div class="row"> 
	<div class="col-sm-12 col-md-12">
		<div class="block-flat">
			<div class="header">

			<form class="form-horizontal" role="form" action="#" method="post">	
				<div class="row">
					<div class="col-sm-6">
						<span style="font-size:18px">Enter the Below Details to assign the lead</span>
					</div>
					<div class="col-sm-6" align="right">
						<button type="submit" name="add"  formaction="<?php echo SITE_URL; ?>addCustomer" value="2" class="btn btn-success"><i class="fa fa-plus"></i> Add Customer</button>
						<button type="submit" name="add"  formaction="<?php echo SITE_URL; ?>addContact"  value="2" class="btn btn-success"><i class="fa fa-plus"></i> Add Contact</button>
					</div>	
				</div>
			</form>	
			</div>
			<div class="content">
				<form class="form-horizontal lead_form" role="form" action="<?php echo SITE_URL; ?>assignLeadAdd"  id="submitLead"  parsley-validate novalidate method="post">
					<div class="form-group">
						<label for="inputName" class="col-sm-3 control-label">Campaign<span class="req-fld">*</span></label>
						<div class="col-sm-6">
							<select name="campaign" required style="width:100%" class="checkCampaign">
								<option value="">Select Campaign</option>
							</select>
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
					<!--<div class="form-group">
						<label for="inputName" class="col-sm-3 control-label">Rapport with Customer<span class="req-fld">*</span></label>
						<div class="col-sm-6">
                            <?php echo form_dropdown('relationship', $rapport, '','class="select2" required'); ?>
						</div>
					</div>-->
					<div class="form-group">
						<label for="inputName" class="col-sm-3 control-label">Assign To<span class="req-fld">*</span></label>
						<div class="col-sm-6">
							<?php 
							$rbhInfo = array(' '=>'Select User to Assign');
							 ?>
                            <?php echo form_dropdown('rbh', $rbhInfo, '','required id="rbh" class="select2"'); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="inputName" class="col-sm-3 control-label">Contact Person 1<span class="req-fld">*</span></label>
						<div class="col-sm-6">
							<?php 
							$ContactInfo = array(' '=>'Select Contact Person');
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
							<input type="text" class="form-control" maxlength="50" parsley-type="number" placeholder="Purchase Potential" name="purchase_potential">
						</div>
					</div>
					<!--<div class="form-group">
		                <label class="col-sm-3 control-label">Site Readiness<span class="req-fld">*</span></label>
		                <div class="col-sm-6">
                            <?php echo form_dropdown('site', $site_readiness, '','class="select2" required'); ?>
		                </div>
		            </div>-->    

					<div class="form-group">
						<label for="inputName" class="col-sm-3 control-label">Visit Requirement</label>
						<div class="col-sm-6">
		                  <label class="radio-inline"> <input type="radio" name="visit_requirement" value="1" class="icheck"> Yes</label> 
		                  <label class="radio-inline"> <input type="radio" checked="" name="visit_requirement" value="0" class="icheck"> No</label> 
						</div>
					</div>
					<div class="form-group">
						<label for="inputName" class="col-sm-3 control-label">Resource Requirement</label>
						<div class="col-sm-6">
						  <label class="radio-inline"> 
							  <div class="iradio_square-blue" style="position: relative;" aria-checked="true" aria-disabled="false">
								  <input type="radio" class="resource_requirement" value="1" name="resource_requirement" style="position: absolute; opacity: 0;">
								  <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;"></ins>
							  </div> 
							  Yes
						  </label>
						  <label class="radio-inline"> 
							  <div class="iradio_square-blue checked" style="position: relative;" aria-checked="true" aria-disabled="false">
								  <input type="radio" class="resource_requirement" value="0" name="resource_requirement" checked="" style="position: absolute; opacity: 0;">
								  <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;"></ins>
							  </div> 
							  No
						  </label>
						</div>
					</div>
					<div class="form-group hidden" id="resource_info_fld">
						<label for="inputName" class="col-sm-3 control-label">Resource Required Information<span class="req-fld">*</span></label>
						<div class="col-sm-6">
					      <textarea name="resource_required_details" id="resource_required_details" class="form-control"></textarea> 
						</div>
					</div>
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
							<button class="btn btn-primary" type="submit" name="submitAssignLead" value="button"><i class="fa fa-check"></i> Submit</button>
							<a class="btn btn-danger" href="<?php echo SITE_URL;?>assignLeads"><i class="fa fa-times"></i> Cancel</a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>


<?php $this->load->view('commons/main_footer.php', $nestedView); ?>
