<?php 
$disableField = (@$checkUser && @$checkPage)?'':'disabled';
?>
<input type="hidden" name="lead" value="<?php echo @$leadDetails['lead_id']; ?>">
<div class="form-group">
	<label for="inputName" class="col-sm-3 control-label">Source of Lead<span class="req-fld">*</span></label>
	<div class="col-sm-6">
        <?php echo form_dropdown('source', $SourceOfLead, @$leadDetails['source_id'],'class="select2" id="source" disabled'); ?>
	</div>
</div>
<div class="form-group campaign <?php echo $chide; ?>">
	<label for="inputName" class="col-sm-3 control-label">Campaign<span class="req-fld">*</span></label>
	<div class="col-sm-6">
		<?php $campaign = getCampaignName(@$leadDetails['campaign_id']); ?>
		<input type="text" disabled="" class="form-control" value="<?php echo @$campaign; ?>">
	</div>
</div>
<div class="form-group referral <?php echo $rhide; ?> ">
	<label for="inputName" class="col-sm-3 control-label">Referral Name<span class="req-fld">*</span></label>
	<div class="col-sm-6">
		<input type="text" disabled class="form-control" id="ref" maxlength="50" value="<?php echo @$leadDetails['remarks1']; ?>" placeholder="Referral Name" name="referral">
	</div>
</div>
<div class="form-group referral <?php echo $colhide; ?> ">
	<label for="inputName" class="col-sm-3 control-label">Colleague Name<span class="req-fld">*</span></label>
	<div class="col-sm-6">
		<input type="text" disabled class="form-control" id="ref" maxlength="50" value="<?php echo getUserName(@$leadDetails['user3']); ?>" placeholder="Referral Name" name="referral">
	</div>
</div>
<div class="form-group">
	<label for="inputName" class="col-sm-3 control-label">Customer<span class="req-fld">*</span></label>
	<div class="col-sm-6">
		<input type="text" class="form-control" disabled="" value="<?php echo @$leadDetails['customer']; ?>">
	</div>
	<div class="col-sm-3">
		<button type="button" class="btn btn-primary btn-flat md-trigger" title="Customer Details" data-toggle="modal" data-target="#customerDetails1"><i class="fa fa-info"></i></button>
		<button type="button" class="btn btn-primary btn-flat md-trigger" title="Installation Base" data-toggle="modal" data-target="#installationBase"><i class="fa fa-book"></i></button>
	</div>
</div>
<div class="form-group">
	<label for="inputName" class="col-sm-3 control-label">Rapport with Customer<span class="req-fld">*</span></label>
	<div class="col-sm-6">
        <?php echo form_dropdown('relationship', $rapport, @$leadDetails['relationship_id'],'class="select2" required '.$disableField.''); ?>
	</div>
</div>

<div class="form-group">
	<label for="inputName" class="col-sm-3 control-label">Contact Person 1<span class="req-fld">*</span></label>
	<div class="col-sm-6">
		<input type="text" class="form-control" disabled="" value="<?php echo @$leadDetails['contact']; ?>">
	</div>
</div>
<div class="form-group">
	<label for="inputName" class="col-sm-3 control-label">Contact Person 2</label>
	<div class="col-sm-6">
		<input type="text" class="form-control" disabled="" value="<?php echo getContactUserName(@$leadDetails['contact_id2']); ?>">
	</div>
</div>
<div class="form-group">
	<label for="inputName" class="col-sm-3 control-label">Purchase Potential (Rs)</label>
	<div class="col-sm-6">
		<input type="text" class="form-control" <?php echo $disableField; ?> value="<?php echo @$leadDetails['purchase_potential']; ?>" parsley-type="number" maxlength="50" placeholder="Purchase Potential" name="purchase_potential">
	</div>
</div>
<div class="form-group">
	<label for="inputName" class="col-sm-3 control-label">Site Readiness<span class="req-fld">*</span></label>
	<div class="col-sm-6">
        <?php echo form_dropdown('site', $site_readiness, @$leadDetails['site_readiness_id'],'class="select2" required '.$disableField.''); ?>
	</div>
</div>
<div class="form-group">
	<label for="inputName" class="col-sm-3 control-label">Visit Requirement</label>
	<?php 
		$ck3 = (@$leadDetails['visit_requirement'] == 1)?'checked':'';
		$ck4 = (@$leadDetails['visit_requirement'] == 1)?'':'checked';
	?>
	<div class="col-sm-6">
      <label class="radio-inline"> <input type="radio" <?php echo $disableField; ?> <?php echo $ck3; ?> name="visit_requirement" value="1" class="icheck"> Yes</label> 
      <label class="radio-inline"> <input type="radio" <?php echo $disableField; ?> <?php echo $ck4; ?> name="visit_requirement" value="0" class="icheck"> No</label> 
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
			  <input type="radio" <?php echo $disableField; ?> class="resource_requirement" value="1" name="resource_requirement" <?php echo $ck5;?> style="position: absolute; opacity: 0;">
			  <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;"></ins>
		  </div> 
		  Yes
	  </label>
	  <label class="radio-inline"> 
		  <div class="iradio_square-blue <?php echo $ck6;?>" style="position: relative;" aria-checked="true" aria-disabled="false">
			  <input type="radio" <?php echo $disableField; ?> class="resource_requirement" value="0" name="resource_requirement" <?php echo $ck6;?> style="position: absolute; opacity: 0;">
			  <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;"></ins>
		  </div> 
		  No
	  </label>
      <!--<label class="radio-inline"> <input type="radio" <?php echo $ck5; ?> name="resource_requirement" value="1" class="icheck"> Yes</label> 
      <label class="radio-inline"> <input type="radio" <?php echo $ck6; ?> name="resource_requirement" value="0" class="icheck"> No</label>
      -->
	</div>
</div>
<?php $resource_req_info_cls = (@$leadDetails['resource_requirement'] == 1)?'':'hidden';?>
<div class="form-group <?php echo @$resource_req_info_cls;?>" id="resource_info_fld">
	<label for="inputName" class="col-sm-3 control-label">Resource Required Information<span class="req-fld">*</span></label>
	<div class="col-sm-6">
      <textarea name="resource_required_details" <?php echo $disableField; ?> id="resource_required_details" class="form-control"><?php echo @$leadDetails['remarks4']?></textarea> 
	</div>
</div>
<div class="form-group">
	<label for="inputName" class="col-sm-3 control-label"><?php echo $secondUser; ?><span class="req-fld sec <?php echo $hide ?>">*</span></label>
	<div class="col-sm-6">
		<input type="hidden" name="checkRole" value="<?php echo $checkRole; ?>">
        <?php
        $user2Disable = ($this->session->role_id == 5)?'disabled':$disableField;
        $secondUserInfo = array(''=>'Select '.$secondUser)+$this->Lead_model->getSecUser(@$leadDetails['location_id'], $checkRole);
        echo form_dropdown('second_user', $secondUserInfo, @$leadDetails['user2'],' '.$req.' class="select2" '.$user2Disable.''); ?>
	</div>
</div>
<div class="form-group">
	<label for="inputName" class="col-sm-3 control-label">Comment Line 1</label>
	<div class="col-sm-6">
		<textarea class="form-control" maxlength="255"  <?php echo $disableField; ?> name="remarks2"><?php echo @$leadDetails['remarks2']; ?></textarea>
	</div>
</div>
<div class="form-group">
	<label for="inputName" class="col-sm-3 control-label">Comment Line 2</label>
	<div class="col-sm-6">
		<textarea class="form-control" maxlength="255" <?php echo $disableField; ?>  name="remarks3"><?php echo @$leadDetails['remarks3']; ?></textarea>
	</div>
</div>
