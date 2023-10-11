<?php
$this->load->view('commons/main_template', $nestedView);
$role_id = $this->session->userdata('role_id');
?>

<?php
if (@$flg != '') {
	//$flg = @$this->global_functions->decode_icrm($_REQUEST['flg']);
	if ($flg == 1) {
		if ($val == 1) {
			$formHeading = 'Edit Demo Details';
		} else {
			$formHeading = 'Plan New Demo';
		}
?>
		<div class="row">
			<div class="col-sm-12 col-md-12">
				<?php echo $this->session->flashdata('error'); ?>
				<div class="block-flat">
					<div class="header">
						<h4><?php echo $formHeading; ?></h4>
					</div>
					<div class="content"> <!-- parsley-validate -->
						<form class="form-horizontal" id="add_demo" role="form" action="<?php echo SITE_URL; ?>demoAdd" novalidate method="post" enctype="multipart/form-data">
							<input type="hidden" name="demo_id" value="<?php echo @$demoEdit[0]['demo_id'] ?>">
							<input type="hidden" name="submitDemo" value="add-update-demo">
							<div class="form-group">
								<label for="inputLead" class="col-sm-4 control-label">Product Category<span class="req-fld">*</span></label>
								<div class="col-sm-6">
									<select name="product_category_id" class="select2" id="product_category_id" required>
										<option value="">Select Product Category</option>
										<option value="1" <?php echo (1 == $demoEdit[0]['product_category_id']) ? 'selected' : ''; ?>>Radiology</option>
										<option value="2" <?php echo (2 == $demoEdit[0]['product_category_id']) ? 'selected' : ''; ?>>Critical Care</option>
										<option value="3" <?php echo (3 == $demoEdit[0]['product_category_id']) ? 'selected' : ''; ?>>Radiology / Critical Care</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="inputLead" class="col-sm-4 control-label">Employee Name</label>
								<div class="col-sm-6">
									<?php if ($demoEdit[0]['requesting_employee_name'] == '') {    ?>
										<input type="text" class="form-control" id="requesting_employee_name" name="requesting_employee_name" value="<?php echo $this->session->userdata('name'); ?>" />
									<?php } else { ?>
										<input type="text" class="form-control" id="requesting_employee_name" name="requesting_employee_name" value="<?php echo $demoEdit[0]['requesting_employee_name']; ?>" />
									<?php } ?>
								</div>
							</div>
							<!-- <div class="form-group">
								<label for="inputLead" class="col-sm-4 control-label">Name of Institute<span class="req-fld">*</span></label>
								<div class="col-sm-6">
									<input type="text" class="form-control" id="name_of_institute" name="name_of_institute" value="<?php echo $demoEdit[0]['name_of_institute'] ?>" required />
								</div>
							</div>
							<div class="form-group">
								<label for="inputLead" class="col-sm-4 control-label">Name of Contact in the Institute<span class="req-fld">*</span></label>
								<div class="col-sm-6">
									<input type="text" class="form-control" id="name_of_contact_institute" name="name_of_contact_institute" value="<?php echo $demoEdit[0]['name_of_contact_institute'] ?>" required />
								</div>
							</div>
							<div class="form-group">
								<label for="inputLead" class="col-sm-4 control-label">Contact Detail<span class="req-fld">*</span></label>
								<div class="col-sm-6">
									<input type="text" class="form-control" id="contact_detail" name="contact_detail" value="<?php echo $demoEdit[0]['contact_detail'] ?>" required />
								</div>
							</div>
							<div class="form-group">
								<label for="inputLead" class="col-sm-4 control-label">Key Opinion / Decision Makers of the institute<span class="req-fld">*</span></label>
								<div class="col-sm-6">
									<input type="text" class="form-control" id="key_decision_makers" name="key_decision_makers" value="<?php echo $demoEdit[0]['key_decision_makers'] ?>" required />
								</div>
							</div>
							<div class="form-group">
								<label for="inputLead" class="col-sm-4 control-label">Existing Unit details with Specific model<span class="req-fld">*</span></label>
								<div class="col-sm-6">
									<input type="text" class="form-control" id="existing_unit_details" name="existing_unit_details" value="<?php echo $demoEdit[0]['existing_unit_details'] ?>" required />
								</div>
							</div> -->
							<div class="form-group">
								<label for="inputLead" class="col-sm-4 control-label">Region</label>
								<div class="col-sm-6">
									<input type="text" class="form-control" id="region" name="region" value="<?php echo $user_region; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label for="inputLead" class="col-sm-4 control-label">Nature of Demos<span class="req-fld">*</span></label>
								<div class="col-sm-6">
									<?php //$demo_disabled = @$demoEdit[0]['nature_of_demo'] != '' ? 'disabled' : '' 
									?>
									<select name="nature_of_demo" class="select2" id="nature_of_demo" required <?php //echo $demo_disabled; 
																												?>>
										<!-- The pre-sale priority was changed as marketing and marketing was changed as conference/events only label -->
										<option value="">Select Nature of Demo</option>
										<option value="marketing" <?php echo ('marketing' == $demoEdit[0]['nature_of_demo']) ? 'selected' : ''; ?>> Conference/Events </option>
										<option value="pre_sale" <?php echo ('pre_sale' == $demoEdit[0]['nature_of_demo']) ? 'selected' : ''; ?>>Pre-sale</option>
										<option value="pre_sale_priority" <?php echo ('pre_sale_priority' == $demoEdit[0]['nature_of_demo']) ? 'selected' : ''; ?>> Marketing</option>
										<option value="post_sale" <?php echo ('post_sale' == $demoEdit[0]['nature_of_demo']) ? 'selected' : ''; ?>>Post-sale</option>
										<option value="existing_customer_visit" <?php echo ('existing_customer_visit' == $demoEdit[0]['nature_of_demo']) ? 'selected' : ''; ?>>Existing Customer Visit</option>
									</select>
								</div>
							</div>

							<div id="marketing" class="hide">
								<div class="form-group">
									<label for="inputLead" class="col-sm-4 control-label">Lead ID</label>
									<div class="col-sm-6">
										<?php $disabled = @$demoEdit[0]['lead_id'] != '' ? 'disabled' : '' ?>
										<?php echo form_dropdown('lead_marketing', $leads, @$demoEdit[0]['lead_id'], 'class="select2 non_validate"  id="lead_marketing"  ' . $disabled . ''); ?>
										<?php if ($disabled == 'disabled') { ?>
											<input type="hidden" name="lead_marketing" value="<?php echo @$demoEdit[0]['lead_id'] ?>">
										<?php } ?>
									</div>
								</div>
								<div class="form-group">
									<label for="inputLead" class="col-sm-4 control-label">Name of Institute</label>
									<div class="col-sm-6">
										<input type="text" class="form-control non-validate" id="name_of_institute_marketing" name="name_of_institute_marketing" value="<?php echo $demoEdit[0]['name_of_institute'] ?>" />
									</div>
								</div>
								<div class="form-group">
									<label for="inputLead" class="col-sm-4 control-label">Contact Detail</label>
									<div class="col-sm-6">
										<input type="text" class="form-control non-validate" id="contact_detail_marketing" name="contact_detail_marketing" value="<?php echo $demoEdit[0]['contact_detail'] ?>" />
									</div>
								</div>
								<div class="form-group">
									<label for="inputLead" class="col-sm-4 control-label">Address</label>
									<div class="col-sm-6">
										<input type="text" class="form-control non-validate" id="address_marketing" name="address_marketing" value="<?php echo $demoEdit[0]['name_of_contact_institute'] ?>" />
									</div>
								</div>

								<div class="form-group">
									<label for="inputOpportunity" class="col-sm-4 control-label">Opportunity</label>
									<div class="col-sm-6">
										<?php $disabled = @$demoEdit[0]['opportunity_id'] != '' ? 'disabled' : '' ?>
										<?php echo form_dropdown('opportunity_marketing', $opportunities, @$demoEdit[0]['opportunity_id'], 'class="select2 non_validate"  id="opportunity_marketing"  ' . $disabled . ''); ?>
										<?php if ($disabled == 'disabled') { ?>
											<input type="hidden" name="opportunity_marketing" value="<?php echo @$demoEdit[0]['opportunity_id'] ?>">
										<?php } ?>
									</div>
								</div>
								<div class="form-group">
									<label for="inputLead" class="col-sm-4 control-label">Key Opinion / Decision Makers of the institute</label>
									<div class="col-sm-6">
										<input type="text" class="form-control non-validate" id="key_decision_makers_marketing" name="key_decision_makers_marketing" value="<?php echo $demoEdit[0]['key_decision_makers'] ?>" required />
									</div>
								</div>
								<div class="form-group">
									<label for="inputDemo" class="col-sm-4 control-label">Demo Machine</label>
									<div class="col-sm-6">
										<?php $disabled = @$demoEdit[0]['demo_product_id'] != '' ? 'disabled' : '' ?>
										<?php echo form_dropdown('demo_marketing', $demos, @$demoEdit[0]['demo_product_id'], 'class="select2 non_validate"  id="demo_marketing"  ' . $disabled . ''); ?>
										<?php if ($disabled == 'disabled') { ?>
											<input type="hidden" name="demo_marketing" value="<?php echo @$demoEdit[0]['demo_product_id'] ?>">
										<?php } ?>
									</div>
								</div>
								<div class="form-group">
									<label for="inputLead" class="col-sm-4 control-label">Name of units to be demonstrated<span class="req-fld">*</span></label>
									<div class="col-sm-6">
										<input type="text" class="form-control marketing_validate" id="name_of_units_demonstrated_marketing" name="name_of_units_demonstrated_marketing" value="<?php echo @$demoEdit[0]['name_of_units_demonstrated']; ?>" />
									</div>
								</div>
								<!-- <div class="form-group">
									<label class="col-sm-4 control-label">Planned Time Line<span class="req-fld">*</span></label>
									<div class="col-sm-6">
										<input type="text" class="form-control non-validate" id="planned_timeline_marketing" name="planned_timeline_marketing" value="<?php //echo $demoEdit[0]['planned_timeline'];; 
																																										?>" />
									</div>
								</div> -->
								<div class="form-group">
									<label class="col-sm-4 control-label">Planned Time Line</label>
									<div class="col-sm-6">
									</div>
								</div>
								<div class="form-group">
									<label for="inputStartDate" class="col-sm-4 control-label">Start Date<span class="req-fld">*</span></label>
									<div class="col-sm-6">
										<div class="input-group date datetime col-sm-6 col-xs-7" data-date-startdate="<?php echo date('Y-m-d H:i'); ?>" data-show-meridian="true" data-start-view="1" data-date="<?php echo date('Y-m-d H:i'); ?>" data-date-format="yyyy-mm-dd hh:i" data-link-field="dtp_input1"> <!-- echo date('Y-m-d') . 'T' . date('H:i:s') . 'Z'; -->
											<input class="form-control marketing_validate" size="16" type="text" id="start_date_marketing" name="start_date_marketing" value="<?php if ($demoEdit[0]['start_date'] != '' && $demoEdit[0]['start_date'] != '0000-00-00 00:00:00') {
																																													echo @$demoEdit[0]['start_date'];
																																												} ?>" readonly placeholder="Start Date">
											<span class="input-group-addon btn btn-primary"><span class="glyphicon glyphicon-th"></span></span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label for="inputEndDate" class="col-sm-4 control-label">End Date<span class="req-fld">*</span></label>
									<div class="col-sm-6">
										<div class="input-group date datetime col-sm-6 col-xs-7" data-date-startdate="<?php echo date('Y-m-d H:i'); ?>" data-show-meridian="true" data-start-view="1" data-date="<?php echo date('Y-m-d H:i'); ?>" data-date-format="yyyy-mm-dd hh:i" data-link-field="dtp_input1">
											<input class="form-control marketing_validate" size="16" type="text" id="end_date_marketing" name="end_date_marketing" value="<?php if ($demoEdit[0]['end_date'] != '' && $demoEdit[0]['end_date'] != '0000-00-00 00:00:00') {
																																												echo @$demoEdit[0]['end_date'];
																																											} ?>" readonly placeholder="End Date" parsley-afterdate="#start_date_marketing">
											<span class="input-group-addon btn btn-primary"><span class="glyphicon glyphicon-th"></span></span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label">Please Share Event Details<span class="req-fld">*</span></label>
									<div class="col-sm-6">
										<textarea class="form-control marketing_validate" id="event_details" name="event_details"><?php echo $demoEdit[0]['event_details']; ?></textarea>
									</div>
								</div>
								<div class="form-group">
									<label for="inputLead" class="col-sm-4 control-label">Attach the Demo Request Form<span class="req-fld">*</span></label>
									<div class="col-sm-6">
										<!-- <input type="file" class="form-control" id="demo_request_letter" name="demo_request_letter" /> -->
										<?php
										$files = json_decode($demoEdit[0]['file_path']);
										if (isset($files)) {
											foreach ($files as $val) {
												$file = basename($val);
										?>
												<a href="<?php echo $val ?>" target="_blank"><?php echo $file; ?></a></br>
										<?php }
										} ?>
										<input type="file" class="form-control marketing_validate" id="marketingRequestFile" name="marketingRequestFile[]" accept="application/pdf" />
									</div>
								</div>
								<div class="form-group">
									<label for="inputLead" class="col-sm-4 control-label">Attach Demo Letter<span class="req-fld">*</span></label>
									<div class="col-sm-6">
										<!-- <input type="file" class="form-control" id="demo_request_letter" name="demo_request_letter" /> -->
										<?php
										$files = json_decode($demoEdit[0]['letter_file_path']);
										if (isset($files)) {
											foreach ($files as $val) {
												$file = basename($val);
										?>
												<a href="<?php echo $val ?>" target="_blank"><?php echo $file; ?></a></br>
										<?php }
										} ?>
										<input type="file" class="form-control marketing_validate" id="marketingLetterFile" name="marketingLetterFile[]" accept="application/pdf" />
									</div>
								</div>
								<!-- <div class="form-group">
									<label for="inputLead" class="col-sm-4 control-label">Units for display at the event<span class="req-fld">*</span></label>
									<div class="col-sm-6">
										<input type="text" class="form-control marketing_validate" id="units_for_display" name="units_for_display" value="<?php //echo $demoEdit[0]['units_for_display'] 
																																							?>" />
									</div>
								</div> -->
							</div>

							<div id="pre_sale_priority" class="hide">
								<div class="form-group">
									<label for="inputLead" class="col-sm-4 control-label">Lead ID<span class="req-fld">*</span></label>
									<div class="col-sm-6">
										<?php $disabled = @$demoEdit[0]['lead_id'] != '' ? 'disabled' : '' ?>
										<?php echo form_dropdown('lead_presale_priority', $leads, @$demoEdit[0]['lead_id'], 'class="select2 pre_sale_priority_validate" id="lead_presale_priority"  ' . $disabled . ''); ?>
										<?php if ($disabled == 'disabled') { ?>
											<input type="hidden" name="lead_presale_priority" value="<?php echo @$demoEdit[0]['lead_id'] ?>">
										<?php } ?>
									</div>
								</div>
								<div class="form-group">
									<label for="inputLead" class="col-sm-4 control-label">Name of Institute</label>
									<div class="col-sm-6">
										<input type="text" class="form-control non-validate" id="name_of_institute_presale_priority" name="name_of_institute_presale_priority" value="<?php echo $demoEdit[0]['name_of_institute'] ?>" />
									</div>
								</div>
								<div class="form-group">
									<label for="inputLead" class="col-sm-4 control-label">Contact Detail</label>
									<div class="col-sm-6">
										<input type="text" class="form-control non-validate" id="contact_detail_presale_priority" name="contact_detail_presale_priority" value="<?php echo $demoEdit[0]['contact_detail'] ?>" />
									</div>
								</div>
								<div class="form-group">
									<label for="inputLead" class="col-sm-4 control-label">Address</label>
									<div class="col-sm-6">
										<input type="text" class="form-control non-validate" id="address_presale_priority" name="address_presale_priority" value="<?php echo $demoEdit[0]['name_of_contact_institute'] ?>" />
									</div>
								</div>
								<div class="form-group">
									<label for="inputOpportunity" class="col-sm-4 control-label">Opportunity<span class="req-fld">*</span></label>
									<div class="col-sm-6">
										<?php $disabled = @$demoEdit[0]['opportunity_id'] != '' ? 'disabled' : '' ?>
										<?php echo form_dropdown('opportunity_presale_priority', $opportunities, @$demoEdit[0]['opportunity_id'], 'class="select2 pre_sale_priority_validate"  id="opportunity_presale_priority"  ' . $disabled . ''); ?>
										<?php if ($disabled == 'disabled') { ?>
											<input type="hidden" name="opportunity_presale_priority" value="<?php echo @$demoEdit[0]['opportunity_id'] ?>">
										<?php } ?>
									</div>
								</div>
								<div class="form-group">
									<label for="inputLead" class="col-sm-4 control-label">Key Opinion / Decision Makers of the institute</label>
									<div class="col-sm-6">
										<input type="text" class="form-control non-validate" id="key_decision_makers_presale_priority" name="key_decision_makers_presale_priority" value="<?php echo $demoEdit[0]['key_decision_makers'] ?>" />
									</div>
								</div>
								<!-- <div class="form-group">
									<label for="inputLead" class="col-sm-4 control-label">Name of units to be demonstrated<span class="req-fld">*</span></label>
									<div class="col-sm-6">
										<input type="text" class="form-control non_validate" id="name_of_units_demonstrated_presale_priority" name="name_of_units_demonstrated_presale_priority" value="<?php echo @$demoEdit[0]['name_of_units_demonstrated']; ?>" />
									</div>
								</div> -->

								<div class="form-group">
									<label for="inputDemo" class="col-sm-4 control-label">Demo Machine<span class="req-fld">*</span></label>
									<div class="col-sm-6">
										<?php $disabled = @$demoEdit[0]['demo_product_id'] != '' ? 'disabled' : '' ?>
										<?php echo form_dropdown('demo_presale_priority', $demos, @$demoEdit[0]['demo_product_id'], 'class="select2 pre_sale_priority_validate"  id="demo_presale_priority"  ' . $disabled . ''); ?>
										<?php if ($disabled == 'disabled') { ?>
											<input type="hidden" name="demo_presale_priority" value="<?php echo @$demoEdit[0]['demo_product_id'] ?>">
										<?php } ?>
									</div>
								</div>

								<!-- <div class="form-group">
									<label class="col-sm-4 control-label">Planned Time Line<span class="req-fld">*</span></label>
									<div class="col-sm-6">
										<input type="text" class="form-control non-validate" id="planned_timeline_presale_priority" name="planned_timeline_presale_priority" value="<?php //echo $demoEdit[0]['planned_timeline']; 
																																													?>" />
									</div>
								</div> -->
								<div class="form-group">
									<label class="col-sm-4 control-label">Planned Time Line</label>
									<div class="col-sm-6">
									</div>
								</div>
								<div class="form-group">
									<label for="inputStartDate" class="col-sm-4 control-label">Start Date<span class="req-fld">*</span></label>
									<div class="col-sm-6">
										<div class="input-group date datetime col-sm-6 col-xs-7" data-date-startdate="<?php echo date('Y-m-d H:i'); ?>" data-show-meridian="true" data-start-view="1" data-date="<?php echo date('Y-m-d H:i'); ?>" data-date-format="yyyy-mm-dd  hh:i" data-link-field="dtp_input1">
											<input class="form-control pre_sale_priority_validate" size="16" type="text" id="start_date_presale_priority" name="start_date_presale_priority" value="<?php if ($demoEdit[0]['start_date'] != '' && $demoEdit[0]['start_date'] != '0000-00-00 00:00:00') {
																																																		echo @$demoEdit[0]['start_date'];
																																																	} ?>" readonly placeholder="Start Date">
											<span class="input-group-addon btn btn-primary"><span class="glyphicon glyphicon-th"></span></span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label for="inputEndDate" class="col-sm-4 control-label">End Date<span class="req-fld">*</span></label>

									<div class="col-sm-6">
										<div class="input-group date datetime col-sm-6 col-xs-7" data-date-startdate="<?php echo date('Y-m-d H:i'); ?>" data-show-meridian="true" data-start-view="1" data-date="<?php echo date('Y-m-d H:i'); ?>" data-date-format="yyyy-mm-dd  hh:i" data-link-field="dtp_input1">
											<input class="form-control pre_sale_priority_validate" size="16" type="text" id="end_date_presale_priority" name="end_date_presale_priority" value="<?php if ($demoEdit[0]['end_date'] != '' && $demoEdit[0]['end_date'] != '0000-00-00 00:00:00') {
																																																	echo @$demoEdit[0]['end_date'];
																																																} ?>" readonly placeholder="End Date" parsley-afterdate="#start_date_presale_priority">
											<span class="input-group-addon btn btn-primary"><span class="glyphicon glyphicon-th"></span></span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label for="inputLead" class="col-sm-4 control-label">Existing unit details with specific model</label>
									<div class="col-sm-6">
										<input type="text" class="form-control non-validate" id="unit_details_with_specific_model" name="unit_details_with_specific_model_presale_priority" value="<?php echo @$demoEdit[0]['unit_details_with_specific_model']; ?>" />
									</div>
								</div>
								<div class="form-group">
									<label for="inputLead" class="col-sm-4 control-label">Competition Info with models and configurations offered</label>
									<div class="col-sm-6">
										<input type="text" class="form-control non-validate" id="competition_info_configuration" name="competition_info_configuration_presale_priority" value="<?php echo @$demoEdit[0]['competition_info_configuration']; ?>" />
									</div>
								</div>
								<div class="form-group">
									<label for="inputLead" class="col-sm-4 control-label">No of interactions with end users before requesting for presale demonstration<span class="req-fld">*</span></label>
									<div class="col-sm-6">
										<input type="text" class="form-control pre_sale_priority_validate" id="no_interactions_end_users" name="no_interactions_end_users_presale_priority" value="<?php echo @$demoEdit[0]['no_interactions_end_users']; ?>" />
									</div>
								</div>
								<div class="form-group">
									<label for="inputLead" class="col-sm-4 control-label">Attach the demo request form<span class="req-fld">*</span></label>
									<div class="col-sm-6">
										<!-- <input type="file" class="form-control" id="demo_request_letter" name="demo_request_letter" /> -->
										<?php
										$files = json_decode($demoEdit[0]['file_path']);
										if (isset($files)) {
											foreach ($files as $val) {
												$file = basename($val);
										?>
												<a href="<?php echo $val ?>" target="_blank"><?php echo $file; ?></a></br>
										<?php }
										} ?>
										<input type="file" class="form-control pre_sale_priority_validate" id="preSalePriorityFile" name="preSalePriorityFile[]" accept="application/pdf" />
									</div>
								</div>
								<div class="form-group">
									<label for="inputLead" class="col-sm-4 control-label">Attach demo letter<span class="req-fld">*</span></label>
									<div class="col-sm-6">
										<?php
										$files = json_decode($demoEdit[0]['letter_file_path']);
										if (isset($files)) {
											foreach ($files as $val) {
												$file = basename($val);
										?>
												<a href="<?php echo $val ?>" target="_blank"><?php echo $file; ?></a></br>
										<?php }
										} ?>
										<input type="file" class="form-control pre_sale_priority_validate" id="preSalePriorityFileLetter" name="preSalePriorityFileLetter[]" accept="application/pdf" />
									</div>
								</div>
								<!-- <div class="form-group">
									<label for="inputLead" class="col-sm-4 control-label">Attach the demo request letter <span class="req-fld">*</span></label>
									<div class="col-sm-6">
										<input type="file" class="form-control" id="demo_request_letter" name="demo_request_letter" />
									</div>
								</div> -->
							</div>


							<div id="pre_sale" class="hide">
								<div class="form-group">
									<label for="inputLead" class="col-sm-4 control-label">Lead ID<span class="req-fld">*</span></label>
									<div class="col-sm-6">
										<?php $disabled = @$demoEdit[0]['lead_id'] != '' ? 'disabled' : '' ?>
										<?php echo form_dropdown('lead', $leads, @$demoEdit[0]['lead_id'], 'class="select2 pre_sale_validate"  id="lead"  ' . $disabled . ''); ?>
										<?php if ($disabled == 'disabled') { ?>
											<input type="hidden" name="lead" value="<?php echo @$demoEdit[0]['lead_id'] ?>">
										<?php } ?>
									</div>
								</div>
								<div class="form-group">
									<label for="inputLead" class="col-sm-4 control-label">Name of Institute</label>
									<div class="col-sm-6">
										<input type="text" class="form-control non-validate" id="name_of_institute_presale" name="name_of_institute_presale" value="<?php echo $demoEdit[0]['name_of_institute'] ?>" />
									</div>
								</div>
								<div class="form-group">
									<label for="inputLead" class="col-sm-4 control-label">Contact Detail</label>
									<div class="col-sm-6">
										<input type="text" class="form-control non-validate" id="contact_detail_presale" name="contact_detail_presale" value="<?php echo $demoEdit[0]['contact_detail'] ?>" />
									</div>
								</div>
								<div class="form-group">
									<label for="inputLead" class="col-sm-4 control-label">Address</label>
									<div class="col-sm-6">
										<input type="text" class="form-control non-validate" id="address_presale" name="address_presale" value="<?php echo $demoEdit[0]['name_of_contact_institute'] ?>" />
									</div>
								</div>
								<div class="form-group">
									<label for="inputOpportunity" class="col-sm-4 control-label">Opportunity<span class="req-fld">*</span></label>
									<div class="col-sm-6">
										<?php $disabled = @$demoEdit[0]['opportunity_id'] != '' ? 'disabled' : '' ?>
										<?php echo form_dropdown('opportunity', $opportunities, @$demoEdit[0]['opportunity_id'], 'class="select2 pre_sale_validate"  id="opportunity"  ' . $disabled . ''); ?>
										<?php if ($disabled == 'disabled') { ?>
											<input type="hidden" name="opportunity" value="<?php echo @$demoEdit[0]['opportunity_id'] ?>">
										<?php } ?>
									</div>
								</div>
								<div class="form-group">
									<label for="inputLead" class="col-sm-4 control-label">Key Opinion / Decision Makers of the institute</label>
									<div class="col-sm-6">
										<input type="text" class="form-control non-validate" id="key_decision_makers_presale" name="key_decision_makers_presale" value="<?php echo $demoEdit[0]['key_decision_makers'] ?>" required />
									</div>
								</div>
								<!-- <div class="form-group">
									<label for="inputLead" class="col-sm-4 control-label">Name of units to be demonstrated<span class="req-fld">*</span></label>
									<div class="col-sm-6">
										<input type="text" class="form-control non_validate" id="name_of_units_demonstrated" name="name_of_units_demonstrated" value="<?php echo $demoEdit[0]['name_of_units_demonstrated'] ?>" />
									</div>
								</div> -->

								<div class="form-group">
									<label for="inputDemo" class="col-sm-4 control-label">Demo Machine<span class="req-fld">*</span></label>
									<div class="col-sm-6">
										<?php $disabled = @$demoEdit[0]['demo_product_id'] != '' ? 'disabled' : '' ?>
										<?php echo form_dropdown('demo', $demos, @$demoEdit[0]['demo_product_id'], 'class="select2 pre_sale_validate"  id="demo"  ' . $disabled . ''); ?>
										<?php if ($disabled == 'disabled') { ?>
											<input type="hidden" name="demo" value="<?php echo @$demoEdit[0]['demo_product_id'] ?>">
										<?php } ?>
									</div>
								</div>

								<!-- <div class="form-group">
									<label class="col-sm-4 control-label">Planned Time Line<span class="req-fld">*</span></label>
									<div class="col-sm-6">
										<input type="text" class="form-control non-validate" id="planned_timeline_presale" name="planned_timeline_presale" value="<?php echo $demoEdit[0]['planned_timeline']; ?>" />
									</div>
								</div> -->
								<div class="form-group">
									<label class="col-sm-4 control-label">Planned Time Line</label>
									<div class="col-sm-6">
									</div>
								</div>
								<div class="form-group">
									<label for="inputStartDate" class="col-sm-4 control-label">Start Date<span class="req-fld">*</span></label>
									<div class="col-sm-6">
										<div class="input-group date datetime col-sm-6 col-xs-7" data-date-startdate="<?php echo date('Y-m-d H:i'); ?>" data-show-meridian="true" data-start-view="1" data-date="<?php echo date('Y-m-d H:i'); ?>" data-date-format="yyyy-mm-dd  hh:i" data-link-field="dtp_input1">
											<input class="form-control pre_sale_validate" size="16" type="text" id="start_date_presale" name="start_date_presale" value="<?php if ($demoEdit[0]['start_date'] != '' && $demoEdit[0]['start_date'] != '0000-00-00 00:00:00') {
																																												echo @$demoEdit[0]['start_date'];
																																											} ?>" readonly placeholder="Start Date">
											<span class="input-group-addon btn btn-primary"><span class="glyphicon glyphicon-th"></span></span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label for="inputStartDate" class="col-sm-4 control-label">End Date<span class="req-fld">*</span></label>
									<div class="col-sm-6">
										<div class="input-group date datetime col-sm-6 col-xs-7" data-date-startdate="<?php echo date('Y-m-d H:i'); ?>" data-show-meridian="true" data-start-view="1" data-date="<?php echo date('Y-m-d H:i'); ?>" data-date-format="yyyy-mm-dd  hh:i" data-link-field="dtp_input1">
											<input class="form-control pre_sale_validate" size="16" type="text" id="end_date_presale" name="end_date_presale" value="<?php if ($demoEdit[0]['end_date'] != '' && $demoEdit[0]['end_date'] != '0000-00-00 00:00:00') {
																																											echo @$demoEdit[0]['end_date'];
																																										} ?>" readonly placeholder="Start Date">
											<span class="input-group-addon btn btn-primary"><span class="glyphicon glyphicon-th"></span></span>
										</div>
									</div>
								</div>

								<!-- <div class="form-group">
									<label for="inputLead" class="col-sm-4 control-label">Key Opinion / Decision makers of the institute<span class="req-fld">*</span></label>
									<div class="col-sm-6">
										<input type="text" class="form-control" id="key_decision_makers_individual" name="key_decision_makers_individual" value="<?php echo @$demoEdit[0]['key_decision_makers_individual']; ?>" />
									</div>
								</div> -->
								<div class="form-group">
									<label for="inputLead" class="col-sm-4 control-label">Existing unit details with specific model</label>
									<div class="col-sm-6">
										<input type="text" class="form-control" id="unit_details_with_specific_model" name="unit_details_with_specific_model" value="<?php echo $demoEdit[0]['unit_details_with_specific_model'] ?>" />
									</div>
								</div>
								<div class="form-group">
									<label for="inputLead" class="col-sm-4 control-label">Competition Info with models and configurations offered</label>
									<div class="col-sm-6">
										<input type="text" class="form-control" id="competition_info_configuration" name="competition_info_configuration" value="<?php echo $demoEdit[0]['competition_info_configuration'] ?>" />
									</div>
								</div>
								<div class="form-group">
									<label for="inputLead" class="col-sm-4 control-label">No of interactions with end users before requesting for presale demonstration<span class="req-fld">*</span></label>
									<div class="col-sm-6">
										<input type="text" class="form-control pre_sale_validate" id="no_interactions_end_users" name="no_interactions_end_users" value="<?php echo $demoEdit[0]['no_interactions_end_users'] ?>" />
									</div>
								</div>
								<div class="form-group">
									<label for="inputLead" class="col-sm-4 control-label">Attach the demo request form<span class="req-fld">*</span></label>
									<div class="col-sm-6">
										<!-- <input type="file" class="form-control" id="demo_request_letter" name="demo_request_letter" /> -->
										<?php
										$files = json_decode($demoEdit[0]['file_path']);
										if (isset($files)) {
											foreach ($files as $val) {
												$file = basename($val);
										?>
												<a href="<?php echo $val ?>" target="_blank"><?php echo $file; ?></a></br>
										<?php }
										} ?>
										<input type="file" class="form-control pre_sale_validate" id="presaleFile" name="presaleFile[]" accept="application/pdf" />
									</div>
								</div>
								<div class="form-group">
									<label for="inputLead" class="col-sm-4 control-label">Attach demo letter<span class="req-fld">*</span></label>
									<div class="col-sm-6">
										<?php
										$files = json_decode($demoEdit[0]['letter_file_path']);
										if (isset($files)) {
											foreach ($files as $val) {
												$file = basename($val);
										?>
												<a href="<?php echo $val ?>" target="_blank"><?php echo $file; ?></a></br>
										<?php }
										} ?>
										<input type="file" class="form-control pre_sale_validate" id="preSaleFileLetter" name="preSaleFileLetter[]" accept="application/pdf" />
									</div>
								</div>
							</div>

							<div id="post_sale" class="hide">
								<div class="form-group">
									<label for="inputLead" class="col-sm-4 control-label">Lead ID</label>
									<div class="col-sm-6">
										<?php $disabled = @$demoEdit[0]['lead_id'] != '' ? 'disabled' : '' ?>
										<?php echo form_dropdown('lead_post_sale', $leads, @$demoEdit[0]['lead_id'], 'class="select2 non-validate"  id="lead_post_sale"  ' . $disabled . ''); ?>
										<?php if ($disabled == 'disabled') { ?>
											<input type="hidden" name="lead_post_sale" value="<?php echo @$demoEdit[0]['lead_id'] ?>">
										<?php } ?>
									</div>
								</div>
								<div class="form-group">
									<label for="inputLead" class="col-sm-4 control-label">Name of Institute</label>
									<div class="col-sm-6">
										<input type="text" class="form-control non-validate" id="name_of_institute_postsale" name="name_of_institute_postsale" value="<?php echo $demoEdit[0]['name_of_institute'] ?>" />
									</div>
								</div>
								<div class="form-group">
									<label for="inputLead" class="col-sm-4 control-label">Contact Detail</label>
									<div class="col-sm-6">
										<input type="text" class="form-control non-validate" id="contact_detail_postsale" name="contact_detail_postsale" value="<?php echo $demoEdit[0]['contact_detail'] ?>" />
									</div>
								</div>
								<div class="form-group">
									<label for="inputLead" class="col-sm-4 control-label">Address</label>
									<div class="col-sm-6">
										<input type="text" class="form-control non-validate" id="address_postsale" name="address_postsale" value="<?php echo $demoEdit[0]['name_of_contact_institute'] ?>" />
									</div>
								</div>
								<div class="form-group">
									<label for="inputOpportunity" class="col-sm-4 control-label">Opportunity</label>
									<div class="col-sm-6">
										<?php $disabled = @$demoEdit[0]['opportunity_id'] != '' ? 'disabled' : '' ?>
										<?php echo form_dropdown('opportunity_post_sale', $opportunities, @$demoEdit[0]['opportunity_id'], 'class="select2 non-validate"  id="opportunity_post_sale"  ' . $disabled . ''); ?>
										<?php if ($disabled == 'disabled') { ?>
											<input type="hidden" name="opportunity_post_sale" value="<?php echo @$demoEdit[0]['opportunity_id'] ?>">
										<?php } ?>
									</div>
								</div>
								<div class="form-group">
									<label for="inputLead" class="col-sm-4 control-label">Existing unit details with specific model</label>
									<div class="col-sm-6">
										<input type="text" class="form-control non-validate" id="unit_details_with_specific_model_postsale" name="unit_details_with_specific_model_postsale" value="<?php echo @$demoEdit[0]['unit_details_with_specific_model']; ?>" />
									</div>
								</div>
								<div class="form-group">
									<label for="inputLead" class="col-sm-4 control-label">Key Opinion / Decision Makers of the institute</label>
									<div class="col-sm-6">
										<input type="text" class="form-control non-validate" id="key_decision_postsale" name="key_decision_postsale" value="<?php echo $demoEdit[0]['key_decision_makers'] ?>" required />
									</div>
								</div>
								<div class="form-group">
									<label for="inputDemo" class="col-sm-4 control-label">Demo Machine</label>
									<div class="col-sm-6">
										<?php $disabled = @$demoEdit[0]['demo_product_id'] != '' ? 'disabled' : '' ?>
										<?php echo form_dropdown('demo_post_sale', $demos, @$demoEdit[0]['demo_product_id'], 'class="select2 non-validate"  id="demo_post_sale"  ' . $disabled . ''); ?>
										<?php if ($disabled == 'disabled') { ?>
											<input type="hidden" name="demo_post_sale" value="<?php echo @$demoEdit[0]['demo_product_id'] ?>">
										<?php } ?>
									</div>
								</div>
								<div class="form-group">
									<label for="inputLead" class="col-sm-4 control-label">Name of units to be demonstrated<span class="req-fld">*</span></label>
									<div class="col-sm-6">
										<input type="text" class="form-control post_sale_validate" id="name_of_units_demonstrated_postsale" name="name_of_units_demonstrated_postsale" value="<?php echo @$demoEdit[0]['name_of_units_demonstrated']; ?>" />
									</div>
								</div>
								<!-- <div class="form-group">
									<label class="col-sm-4 control-label">Planned Time Line<span class="req-fld">*</span></label>
									<div class="col-sm-6">
										<input type="text" class="form-control non-validate" id="planned_timeline_postsale" name="planned_timeline_postsale" value="<?php // echo $demoEdit[0]['planned_timeline']; 
																																									?>" />
									</div>
								</div> -->
								<div class="form-group">
									<label class="col-sm-4 control-label">Timeline for demo if any specified by Customer</label>
									<div class="col-sm-6">
									</div>
								</div>
								<div class="form-group">
									<label for="inputStartDate" class="col-sm-4 control-label">Start Date</label>
									<div class="col-sm-6">
										<div class="input-group date datetime col-sm-6 col-xs-7" data-date-startdate="<?php echo date('Y-m-d H:i'); ?>" data-show-meridian="true" data-start-view="1" data-date="<?php echo date('Y-m-d H:i'); ?>" data-date-format="yyyy-mm-dd  hh:i" data-link-field="dtp_input1">
											<input class="form-control" size="16" type="text" id="start_date_post_sale" name="start_date_post_sale" value="<?php if ($demoEdit[0]['start_date'] != '' && $demoEdit[0]['start_date'] != '0000-00-00 00:00:00') {
																																								echo @$demoEdit[0]['start_date'];
																																							} ?>" readonly placeholder="Start Date">
											<span class="input-group-addon btn btn-primary"><span class="glyphicon glyphicon-th"></span></span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label for="inputEndDate" class="col-sm-4 control-label">End Date</label>

									<div class="col-sm-6">
										<div class="input-group date datetime col-sm-6 col-xs-7" data-date-startdate="<?php echo date('Y-m-d H:i'); ?>" data-show-meridian="true" data-start-view="1" data-date="<?php echo date('Y-m-d H:i'); ?>" data-date-format="yyyy-mm-dd  hh:i" data-link-field="dtp_input1">
											<input class="form-control" size="16" type="text" id="end_date_post_sale" name="end_date_post_sale" value="<?php if ($demoEdit[0]['end_date'] != '' && $demoEdit[0]['end_date'] != '0000-00-00 00:00:00') {
																																							echo @$demoEdit[0]['end_date'];
																																						} ?>" readonly placeholder="End Date" parsley-afterdate="#start_date_post_sale">
											<span class="input-group-addon btn btn-primary"><span class="glyphicon glyphicon-th"></span></span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label for="inputStartDate" class="col-sm-4 control-label">Date of Installation<span class="req-fld">*</span></label>
									<div class="col-sm-6">
										<div class="input-group date datetime col-sm-6 col-xs-7" data-show-meridian="true" data-start-view="1" data-date="<?php echo date('Y-m-d') . 'T' . date('H:i:s') . 'Z'; ?>" data-date-format="yyyy-mm-dd  h:i" data-link-field="dtp_input1">
											<input class="form-control post_sale_validate" size="16" type="text" name="date_of_installation" value="<?php echo @$demoEdit[0]['date_of_installation']; ?>" readonly placeholder="Date of Installation">
											<span class="input-group-addon btn btn-primary"><span class="glyphicon glyphicon-th"></span></span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label for="inputLead" class="col-sm-4 control-label">Installed By<span class="req-fld">*</span></label>
									<div class="col-sm-6">
										<input type="text" class="form-control post_sale_validate" id="installed_by" name="installed_by" value="<?php echo @$demoEdit[0]['installed_by']; ?>" />
									</div>
								</div>
								<div class="form-group">
									<label for="inputLead" class="col-sm-4 control-label">Name of units installed<span class="req-fld">*</span></label>
									<div class="col-sm-6">
										<input type="text" class="form-control post_sale_validate" id="name_units_installed" name="name_units_installed" value="<?php echo @$demoEdit[0]['name_units_installed']; ?>" />
									</div>
								</div>
								<!-- <div class="form-group">
									<label for="inputLead" class="col-sm-4 control-label">Timeline for post sale demo if any specified by hospital<span class="req-fld">*</span></label>
									<div class="col-sm-6">
										<input type="text" class="form-control post_sale_validate" id="timeline_post_sale_demo" name="timeline_post_sale_demo" value="<?php echo @$demoEdit[0]['timeline_post_sale_demo']; ?>" />
									</div>
								</div> -->
								<div class="form-group">
									<label class="col-sm-4 control-label">Serial Number<span class="req-fld">*</span></label>
									<div class="col-sm-6">
										<input type="text" class="form-control post_sale_validate" id="serial_number_postsale" name="serial_number_postsale" value="<?php echo $demoEdit[0]['serial_number']; ?>" />
									</div>
								</div>
								<div class="form-group">
									<label for="inputLead" class="col-sm-4 control-label">Attach Installation Report Form</label>
									<div class="col-sm-6">
										<?php
										$files = json_decode($demoEdit[0]['file_path']);
										if (isset($files)) {
											foreach ($files as $val) {
												$file = basename($val);
										?>
												<a href="<?php echo $val ?>" target="_blank"><?php echo $file; ?></a></br>
										<?php }
										} ?>
										<input type="file" class="form-control" id="attachReportFile" name="attachReportFile[]" accept="application/pdf" />
									</div>
								</div>
							</div>

							<div id="existing_customer_visit" class="hide">
								<div class="form-group">
									<label for="inputLead" class="col-sm-4 control-label">Lead ID</label>
									<div class="col-sm-6">
										<?php $disabled = @$demoEdit[0]['lead_id'] != '' ? 'disabled' : '' ?>
										<?php echo form_dropdown('lead_existing', $leads, @$demoEdit[0]['lead_id'], 'class="select2 non-validate" id="lead_existing"  ' . $disabled . ''); ?>
										<?php if ($disabled == 'disabled') { ?>
											<input type="hidden" name="lead_existing" value="<?php echo @$demoEdit[0]['lead_id'] ?>">
										<?php } ?>
									</div>
								</div>
								<div class="form-group">
									<label for="inputLead" class="col-sm-4 control-label">Name of Institute</label>
									<div class="col-sm-6">
										<input type="text" class="form-control non-validate" id="name_of_institute_existing" name="name_of_institute_existing" value="<?php echo $demoEdit[0]['name_of_institute'] ?>" />
									</div>
								</div>
								<div class="form-group">
									<label for="inputLead" class="col-sm-4 control-label">Contact Detail</label>
									<div class="col-sm-6">
										<input type="text" class="form-control non-validate" id="contact_detail_existing" name="contact_detail_existing" value="<?php echo $demoEdit[0]['contact_detail'] ?>" />
									</div>
								</div>
								<div class="form-group">
									<label for="inputLead" class="col-sm-4 control-label">Address</label>
									<div class="col-sm-6">
										<input type="text" class="form-control non-validate" id="address_existing" name="address_existing" value="<?php echo $demoEdit[0]['name_of_contact_institute'] ?>" />
									</div>
								</div>
								<div class="form-group">
									<label for="inputOpportunity" class="col-sm-4 control-label">Opportunity</label>
									<div class="col-sm-6">
										<?php $disabled = @$demoEdit[0]['opportunity_id'] != '' ? 'disabled' : '' ?>
										<?php echo form_dropdown('opportunity_existing', $opportunities, @$demoEdit[0]['opportunity_id'], 'class="select2 non-validate"  id="opportunity_existing"  ' . $disabled . ''); ?>
										<?php if ($disabled == 'disabled') { ?>
											<input type="hidden" name="opportunity_existing" value="<?php echo @$demoEdit[0]['opportunity_id'] ?>">
										<?php } ?>
									</div>
								</div>
								<div class="form-group">
									<label for="inputLead" class="col-sm-4 control-label">Existing unit details with specific model</label>
									<div class="col-sm-6">
										<input type="text" class="form-control non-validate" id="unit_details_with_specific_model_existing" name="unit_details_with_specific_model_existing" value="<?php echo @$demoEdit[0]['unit_details_with_specific_model']; ?>" />
									</div>
								</div>
								<div class="form-group">
									<label for="inputLead" class="col-sm-4 control-label">Key Opinion / Decision Makers of the institute</label>
									<div class="col-sm-6">
										<input type="text" class="form-control non-validate" id="key_decision_existing" name="key_decision_existing" value="<?php echo $demoEdit[0]['key_decision_makers'] ?>" required />
									</div>
								</div>
								<div class="form-group">
									<label for="inputDemo" class="col-sm-4 control-label">Demo Machine</label>
									<div class="col-sm-6">
										<?php $disabled = @$demoEdit[0]['demo_product_id'] != '' ? 'disabled' : '' ?>
										<?php echo form_dropdown('demo_existing', $demos, @$demoEdit[0]['demo_product_id'], 'class="select2 non-validate"  id="demo_existing"  ' . $disabled . ''); ?>
										<?php if ($disabled == 'disabled') { ?>
											<input type="hidden" name="demo_existing" value="<?php echo @$demoEdit[0]['demo_product_id'] ?>">
										<?php } ?>
									</div>
								</div>
								<div class="form-group">
									<label for="inputLead" class="col-sm-4 control-label">Name of units to be demonstrated<span class="req-fld">*</span></label>
									<div class="col-sm-6">
										<input type="text" class="form-control existing_customer_visit_validate" id="name_of_units_demonstrated_existing" name="name_of_units_demonstrated_existing" value="<?php echo @$demoEdit[0]['name_of_units_demonstrated']; ?>" />
									</div>
								</div>
								<!-- <div class="form-group">
									<label class="col-sm-4 control-label">Planned Time Line<span class="req-fld">*</span></label>
									<div class="col-sm-6">
										<input type="text" class="form-control non-validate" id="planned_timeline_existing" name="planned_timeline_existing" value="<?php //echo $demoEdit[0]['planned_timeline']; 
																																									?>" />
									</div>
								</div> -->
								<div class="form-group">
									<label class="col-sm-4 control-label">Planned Time Line</label>
									<div class="col-sm-6">
									</div>
								</div>
								<div class="form-group">
									<label for="inputStartDate" class="col-sm-4 control-label">Start Date</label>
									<div class="col-sm-6">
										<div class="input-group date datetime col-sm-6 col-xs-7" data-date-startdate="<?php echo date('Y-m-d H:i'); ?>" data-show-meridian="true" data-start-view="1" data-date="<?php echo date('Y-m-d H:i'); ?>" data-date-format="yyyy-mm-dd  hh:i" data-link-field="dtp_input1">
											<input class="form-control" size="16" type="text" id="start_date_existing" name="start_date_existing" value="<?php if ($demoEdit[0]['start_date'] != '' && $demoEdit[0]['start_date'] != '0000-00-00 00:00:00') {
																																								echo @$demoEdit[0]['start_date'];
																																							} ?>" readonly placeholder="Start Date">
											<span class="input-group-addon btn btn-primary"><span class="glyphicon glyphicon-th"></span></span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label for="inputEndDate" class="col-sm-4 control-label">End Date</label>

									<div class="col-sm-6">
										<div class="input-group date datetime col-sm-6 col-xs-7" data-date-startdate="<?php echo date('Y-m-d H:i'); ?>" data-show-meridian="true" data-start-view="1" data-date="<?php echo date('Y-m-d H:i'); ?>" data-date-format="yyyy-mm-dd  hh:i" data-link-field="dtp_input1">
											<input class="form-control" size="16" type="text" id="start_date_existing" name="end_date_existing" value="<?php if ($demoEdit[0]['end_date'] != '' && $demoEdit[0]['end_date'] != '0000-00-00 00:00:00') {
																																							echo @$demoEdit[0]['end_date'];
																																						} ?>" readonly placeholder="End Date" parsley-afterdate="#start_date_existing">
											<span class="input-group-addon btn btn-primary"><span class="glyphicon glyphicon-th"></span></span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label for="inputLead" class="col-sm-4 control-label">Customer Complaint / Future Prospect Details<span class="req-fld">*</span></label>
									<div class="col-sm-6">
										<select name="customer_complaint_future_prospect" class="select2 existing_customer_visit_validate" id="customer_complaint_future_prospect">
											<option value="">Select Details</option>
											<option value="1" <?php echo (1 == $demoEdit[0]['customer_complaint_future_prospect']) ? 'selected' : ''; ?>>Customer Complaint</option>
											<option value="2" <?php echo (2 == $demoEdit[0]['customer_complaint_future_prospect']) ? 'selected' : ''; ?>>Future Prospect</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label">Details<span class="req-fld">*</span></label>
									<div class="col-sm-6">
										<textarea class="form-control existing_customer_visit_validate" id="customer_complaint_future_prospect_details" name="customer_complaint_future_prospect_details"><?php echo $demoEdit[0]['customer_complaint_future_prospect_details'] ?></textarea>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label">Serial Number<span class="req-fld">*</span></label>
									<div class="col-sm-6">
										<input type="text" class="form-control existing_customer_visit_validate" id="serial_number_existing" name="serial_number_existing" value="<?php echo $demoEdit[0]['serial_number']; ?>" />
									</div>
								</div>
							</div>

							<!-- <div class="form-group">
								<label class="col-sm-4 control-label">Unit Details<span class="req-fld">*</span></label>
								<div class="col-sm-6">
									<input type="text" class="form-control" id="unit_details" name="unit_details" value="<?php echo $demoEdit[0]['unit_details'] ?>" required />
								</div>
							</div> -->
							<!-- <div class="form-group">
								<label for="inputLead" class="col-sm-3 control-label">Lead<span class="req-fld">*</span></label>
								<div class="col-sm-6">
									<?php $disabled = @$demoEdit[0]['lead_id'] != '' ? 'disabled' : '' ?>
									<?php echo form_dropdown('lead', $leads, @$demoEdit[0]['lead_id'], 'class="select2" id="lead"  ' . $disabled . ''); ?>
									<?php if ($disabled == 'disabled') { ?>
										<input type="hidden" name="lead" value="<?php echo @$demoEdit[0]['lead_id'] ?>">
									<?php } ?>
								</div>
							</div>
							<div class="form-group">
								<label for="inputOpportunity" class="col-sm-3 control-label">Opportunity<span class="req-fld">*</span></label>
								<div class="col-sm-6">
									<?php $disabled = @$demoEdit[0]['opportunity_id'] != '' ? 'disabled' : '' ?>
									<?php echo form_dropdown('opportunity', $opportunities, @$demoEdit[0]['opportunity_id'], 'class="select2" id="opportunity"  ' . $disabled . ''); ?>
									<?php if ($disabled == 'disabled') { ?>
										<input type="hidden" name="opportunity" value="<?php echo @$demoEdit[0]['opportunity_id'] ?>">
									<?php } ?>
								</div>
							</div>
							<div class="form-group">
								<label for="inputDemo" class="col-sm-3 control-label">Demo Machine<span class="req-fld">*</span></label>
								<div class="col-sm-6">
									<?php $disabled = @$demoEdit[0]['demo_product_id'] != '' ? 'disabled' : '' ?>
									<?php echo form_dropdown('demo', $demos, @$demoEdit[0]['demo_product_id'], 'class="select2" id="demo"  ' . $disabled . ''); ?>
									<?php if ($disabled == 'disabled') { ?>
										<input type="hidden" name="demo" value="<?php echo @$demoEdit[0]['demo_product_id'] ?>">
									<?php } ?>
								</div>
							</div>
							<div class="form-group">
								<label for="inputStartDate" class="col-sm-3 control-label">Start Time<span class="req-fld">*</span></label>
								<div class="col-sm-6">
									<div class="input-group date datetime col-sm-6 col-xs-7" data-show-meridian="true" data-start-view="1" data-date="<?php echo date('Y-m-d') . 'T' . date('H:i:s') . 'Z'; ?>" data-date-format="yyyy-mm-dd  h:i" data-link-field="dtp_input1">
										<input class="form-control" size="16" type="text" name="start_date" value="<?php echo @$demoEdit[0]['start_date']; ?>" readonly  placeholder="Start Time">
										<span class="input-group-addon btn btn-primary"><span class="glyphicon glyphicon-th"></span></span>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="inputEndDate" class="col-sm-3 control-label">End Time<span class="req-fld">*</span></label>

								<div class="col-sm-6">
									<div class="input-group date datetime col-sm-6 col-xs-7" data-show-meridian="true" data-start-view="1" data-date="<?php echo date('Y-m-d') . 'T' . date('H:i:s') . 'Z'; ?>" data-date-format="yyyy-mm-dd  h:i" data-link-field="dtp_input1">
										<input class="form-control" size="16" type="text" name="end_date" value="<?php echo @$demoEdit[0]['end_date']; ?>" readonly  placeholder="End Time">
										<span class="input-group-addon btn btn-primary"><span class="glyphicon glyphicon-th"></span></span>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Remark</label>
								<div class="col-sm-6">
									<textarea class="form-control" id="remarks1" name="remarks1"><?php echo @$demoEdit[0]['remarks1']; ?></textarea>
								</div>
							</div> -->
							<div class="form-group" style="margin-top:20px">
								<div class="col-sm-offset-3 col-sm-10 pt-5">
									<button class="btn btn-primary add" type="button" onclick="validateForm()" value="button"><i class="fa fa-check"></i> Submit</button>
									<a class="btn btn-danger" href="<?php echo SITE_URL; ?>demo"><i class="fa fa-times"></i> Cancel</a>
								</div>
							</div>
							<input type="hidden" id="is_expired" value="<?php echo @$demoEdit[0]['is_expired']; ?>">
						</form>
					</div>
				</div>
			</div>
			<div class="col-md-6 col-sm-6" hidden>
				<div class="block-flat">
					<div class="content">
						<div class="spacer2 text-center">
							<button class="btn btn-primary btn-flat md-trigger" style="display:none;" id="calendar_modal" data-modal="form-primary">Basic Form</button>
						</div>
					</div>
				</div>
			</div>
		</div><br>

<?php
	}
}
echo $this->session->flashdata('response');
echo $this->session->flashdata('activate_error');
?>

<?php
if (@$displayList == 1) {
?>

	<div class="row">
		<div class="col-sm-12 col-md-12">
			<div class="block-flat">
				<table class="table table-bordered"></table>
				<div class="content">
					<div class="row">
						<form role="form" class="form-horizontal" method="post" action="<?php echo SITE_URL; ?>demo">
							<div class="col-sm-12">
								<div class="col-sm-2">
									<input type="text" name="opportunityId" placeholder="Opportunity ID" value="<?php echo @$searchParams['opportunityId']; ?>" id="opportunityId" class="form-control">
								</div>
								<div class="col-sm-2">
									<select class="checkCustomer" style="width:100%" name="customer">
										<option value="<?php echo $customer['customer_id']; ?>"><?php echo $customer['customer']; ?></option>
									</select>
								</div>
								<div class="col-sm-3">
									<div class="input-group date datetime col-sm-10 col-xs-7" data-show-meridian="true" data-start-view="1" data-date="<?php echo date('Y-m-d') . 'T' . date('H:i:s') . 'Z'; ?>" data-date-format="yyyy-mm-dd  h:i" data-link-field="dtp_input1">
										<input class="form-control" size="16" type="text" id="startDate" name="startDate" value="<?php echo @$searchParams['startDate']; ?>" readonly placeholder="Start Time">
										<span class="input-group-addon btn btn-primary"><span class="glyphicon glyphicon-th"></span></span>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="input-group date datetime col-sm-10 col-xs-7" data-show-meridian="true" data-start-view="1" data-date="<?php echo date('Y-m-d') . 'T' . date('H:i:s') . 'Z'; ?>" data-date-format="yyyy-mm-dd  h:i" data-link-field="dtp_input1">
										<input class="form-control" size="16" type="text" id="endDate" name="endDate" value="<?php echo @$searchParams['endDate']; ?>" readonly placeholder="End Time">
										<span class="input-group-addon btn btn-primary"><span class="glyphicon glyphicon-th"></span></span>
									</div>
								</div>
								<div class="col-sm-2">
									<button type="submit" name="searchOpportunity" value="1" class="btn btn-success"><i class="fa fa-search"></i></button>
									<?php if (in_array($role_id, allowed_download_roles())) { ?>
										<button type="submit" name="downloadDemo" value="1" formaction="<?php echo SITE_URL; ?>downloadDemo" class="btn btn-success"><i class="fa fa-cloud-download"></i></button>
									<?php } ?>
									<a href="<?php echo SITE_URL; ?>planDemo" class="btn btn-success"><i class="fa fa-plus"></i></a>
								</div>
							</div>
						</form>
					</div>
					<div class="header"></div>
					<div class="table-responsive">
						<table class="table table-bordered hover">
							<thead>
								<tr>
									<th class="text-center"><strong>S.NO</strong></th>
									<th class="text-center"><strong>Customer Name</strong></th>
									<th class="text-center"><strong>Opportunity</strong></th>
									<!-- <th class="text-center"><strong>Demo Machine</strong></th> -->
									<th class="text-center"><strong>Product Details</strong></th>
									<th class="text-center"><strong>Start Date</strong></th>
									<th class="text-center"><strong>End Date</strong></th>
									<th class="text-center"><strong>Actions</strong></th>
								</tr>
							</thead>
							<tbody>
								<?php
								if (@$total_rows > 0) {
									foreach ($demoSearch as $row) { ?>
										<tr>
											<td class="text-center" width="5%"><?php echo @$sn;
																				$sn++; ?></td>
											<td class="text-center" width="15%"><?php echo @$row['CustomerName']; ?></td>
											<td class="text-center" width="20%"><?php echo @$row['opportunity']; ?></td>
											<!-- <td class="text-center" width="15%"><?php // echo @$row['demo']; 
																						?></td> -->
											<td class="text-center" width="15%"><?php echo @$row['product_name'] . '-' . @$row['product_description']; ?></td>
											<?php if ($row['start_date'] != '' && $row['start_date'] != '0000-00-00 00:00:00') { ?>
												<td class="text-center" width="10%"><?php echo format_date($row['start_date'], 'd-m-Y h:i a'); ?></td>
											<?php } else { ?>
												<td class="text-center" width="10%"></td>
											<?php } ?>
											<?php if ($row['end_date'] != '' && $row['end_date'] != '0000-00-00 00:00:00') { ?>
												<td class="text-center" width="10%"><?php echo format_date($row['end_date'], 'd-m-Y h:i a'); ?></td>
											<?php } else { ?>
												<td class="text-center" width="10%"></td>
											<?php } ?>
											<td class="text-center" width="10%">
												<a class="btn btn-default" style="padding:3px 3px;" href="<?php echo SITE_URL; ?>editDemo/<?php echo @icrm_encode($row['demo_id']); ?>"><i class="fa fa-pencil"></i></a>
												<?php
												$start_date_time = strtotime(@$row['start_date']);
												$cur_time = strtotime(date('Y-m-d'));
												if ($cur_time > $start_date_time) {
													$pop_id = 'demo_modal' . @$row['demo_id'];
													$btn_cls = (@$row['remarks2'] != '') ? 'btn-primary' : 'btn-default';
												?>
													<button title="Update demo feedback" class="btn <?php echo $btn_cls; ?> btn-flat md-trigger" data-modal="<?php echo $pop_id; ?>" style="padding:3px 3px;"><i class="fa fa-paperclip"></i></button>

												<?php
												}
												?>
												<?php
												if (@$row['status'] == 1) {
													if (@$row['is_expired'] == 0) {
												?>
														<a class="btn btn-danger" style="padding:3px 3px;" href="<?php echo SITE_URL; ?>deleteDemo/<?php echo @icrm_encode($row['demo_id']); ?>" onclick="return confirm('Are you sure you want to Delete?')"><i class="fa fa-trash-o"></i></a>
													<?php
													}
												} else {
													?>
													<a class="btn btn-info" title="Activate" style="padding:3px 3px;" href="<?php echo SITE_URL; ?>activateDemo/<?php echo @icrm_encode($row['demo_id']); ?>" onclick="return confirm('Are you sure you want to Activate?')"><i class="fa fa-check"></i></a>
												<?php
												}
												?>
											</td>
										</tr>
									<?php	}
								} else {
									?> <tr>
										<td colspan="6" align="center"><span class="label label-primary">No Records</span></td>
									</tr>
								<?php 	} ?>
							</tbody>
						</table>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="pull-left"><?php echo @$pagermessage; ?></div>
							<div class="pull-right">
								<div class="dataTables_paginate paging_bs_normal">
									<?php echo @$pagination_links; ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php
}
?>
<!-- Nifty Modal -->
<div class="md-modal colored-header custom-width md-effect-9" id="form-primary">
	<div class="md-content">
		<div id='calendar'></div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default btn-flat md-close" data-dismiss="modal">Cancel</button>
		</div>
	</div>
</div>
<div class="md-overlay"></div>


<?php
if (@$total_rows > 0) {
	foreach ($demoSearch as $row) {
		$start_date_time = strtotime(@$row['start_date']);
		$cur_time = strtotime(date('Y-m-d'));
		if ($cur_time > $start_date_time) {
			$pop_id = 'demo_modal' . @$row['demo_id'];
			include('modals/demoUpdate_modal.php');
		}
	}
}
?>
<div class="md-overlay"></div>
<?php
$this->load->view('commons/main_footer.php', $nestedView);
?>
<script type="text/javascript">
	$(document).ready(function() {
		var start_date = $("#start_date").val();
		var end_date = $("#end_date").val();
		var min_date = (start_date == '') ? '-14d' : new Date(new Date(start_date) - 12096e5);

		$('#start_date_time').datetimepicker({
			startDate: new Date()
		});

		if ($("#is_expired").val() != 1) {
			$("#startDate").datepicker({
				dateFormat: "yy-mm-dd",
				changeMonth: true,
				changeYear: true,
				minDate: min_date,
				onSelect: function(date) {

					var date2 = $(this).datepicker('getDate');
					$('#end_date').datepicker('option', 'minDate', date2);
				}
			});
			$("#endDate").datepicker({
				dateFormat: "yy-mm-dd",
				changeMonth: true,
				changeYear: true,
				minDate: min_date,
				onSelect: function(date) {

					var date2 = $(this).datepicker('getDate');
					$('#start_date').datepicker('option', 'maxDate', date2);
				}
			});
		}
		$("#requesting_employee_name").prop("readonly", true);
		$("#region").prop("readonly", true);
		$(document).on("change", "#lead", function() {
			var old_this = $(this);
			$(old_this).parents('form').find('#opportunity').html('<option value="">Select Opportunity</option>');
			$.ajax({
				type: "POST",
				url: "<?php echo SITE_URL; ?>getOpportunity",
				data: 'lead_id=' + $(this).val(),
				beforeSend: function() {},
				success: function(data) {
					// $(old_this).parents('form').find('#opportunity').html(data);
					data = JSON.parse(data);
					$(old_this).parents('form').find('#opportunity').html(data.opportunities);
					$('#name_of_institute_presale').val(data.nameofinstitute);
					$('#contact_detail_presale').val(data.contactdetail);
					$('#address_presale').val(data.address);
					$("#name_of_institute_presale").prop("readonly", true);
					$("#contact_detail_presale").prop("readonly", true);
					$("#address_presale").prop("readonly", true);
				}
			});
			$('#demo').html('<option value="">Select Demo Machine</option>');
		});

		$(document).on("change", "#opportunity", function() {
			var old_this = $(this);
			$(old_this).parents('form').find('#demo').html('<option value="">Select Demo Machine</option>');
			$.ajax({
				type: "POST",
				url: "<?php echo SITE_URL; ?>getDemo",
				data: 'opportunity_id=' + $(this).val(),
				beforeSend: function() {},
				success: function(data) {
					// $(old_this).parents('form').find('#demo').html(data);
					data = JSON.parse(data);
					$(old_this).parents('form').find('#demo').html(data.demo);
					$('#key_decision_makers_presale').val(data.key_makers);
					$("#key_decision_makers_presale").prop("readonly", true);

				}
			});
		});

		// marketing option to get lead and opportunity
		$(document).on("change", "#lead_marketing", function() {
			var old_this = $(this);
			var lead_id = $(this).val();
			$(old_this).parents('form').find('#opportunity_marketing').html('<option value="">Select Opportunity</option>');
			$.ajax({
				type: "POST",
				url: "<?php echo SITE_URL; ?>getOpportunity",
				data: 'lead_id=' + $(this).val(),
				beforeSend: function() {},
				success: function(data) {
					data = JSON.parse(data);
					$(old_this).parents('form').find('#opportunity_marketing').html(data.opportunities);
					$('#name_of_institute_marketing').val(data.nameofinstitute);
					$('#contact_detail_marketing').val(data.contactdetail);
					$('#address_marketing').val(data.address);
					$("#name_of_institute_marketing").prop("readonly", true);
					$("#contact_detail_marketing").prop("readonly", true);
					$("#address_marketing").prop("readonly", true);


				}
			});
			$('#demo').html('<option value="">Select Demo Machine</option>');
		});

		$(document).on("change", "#opportunity_marketing", function() {
			var old_this = $(this);
			$(old_this).parents('form').find('#demo_marketing').html('<option value="">Select Demo Machine</option>');
			$.ajax({
				type: "POST",
				url: "<?php echo SITE_URL; ?>getDemo",
				data: 'opportunity_id=' + $(this).val(),
				beforeSend: function() {},
				success: function(data) {
					data = JSON.parse(data);
					$(old_this).parents('form').find('#demo_marketing').html(data.demo);
					$('#key_decision_makers_marketing').val(data.key_makers);
					$("#key_decision_makers_marketing").prop("readonly", true);

				}
			});
		});
		// Pre-sale priority option to get lead and opportunity
		$(document).on("change", "#lead_presale_priority", function() {
			var old_this = $(this);
			$(old_this).parents('form').find('#opportunity_presale_priority').html('<option value="">Select Opportunity</option>');
			$.ajax({
				type: "POST",
				url: "<?php echo SITE_URL; ?>getOpportunity",
				data: 'lead_id=' + $(this).val(),
				beforeSend: function() {},
				success: function(data) {
					// $(old_this).parents('form').find('#opportunity_presale_priority').html(data);
					data = JSON.parse(data);
					$(old_this).parents('form').find('#opportunity_presale_priority').html(data.opportunities);
					$('#name_of_institute_presale_priority').val(data.nameofinstitute);
					$('#contact_detail_presale_priority').val(data.contactdetail);
					$('#address_presale_priority').val(data.address);
					$("#name_of_institute_presale_priority").prop("readonly", true);
					$("#contact_detail_presale_priority").prop("readonly", true);
					$("#address_presale_priority").prop("readonly", true);
				}
			});
			$('#demo').html('<option value="">Select Demo Machine</option>');
		});
		$(document).on("change", "#opportunity_presale_priority", function() {
			var old_this = $(this);
			$(old_this).parents('form').find('#demo_presale_priority').html('<option value="">Select Demo Machine</option>');
			$.ajax({
				type: "POST",
				url: "<?php echo SITE_URL; ?>getDemo",
				data: 'opportunity_id=' + $(this).val(),
				beforeSend: function() {},
				success: function(data) {
					// $(old_this).parents('form').find('#demo_presale_priority').html(data);
					data = JSON.parse(data);
					$(old_this).parents('form').find('#demo_presale_priority').html(data.demo);
					$('#key_decision_makers_presale_priority').val(data.key_makers);
					$("#key_decision_makers_presale_priority").prop("readonly", true);

				}
			});
		});
		// Post-sale option to get lead and opportunity
		$(document).on("change", "#lead_post_sale", function() {
			var old_this = $(this);
			$(old_this).parents('form').find('#opportunity_post_sale').html('<option value="">Select Opportunity</option>');
			$.ajax({
				type: "POST",
				url: "<?php echo SITE_URL; ?>getOpportunity",
				data: 'lead_id=' + $(this).val(),
				beforeSend: function() {},
				success: function(data) {
					// $(old_this).parents('form').find('#opportunity_post_sale').html(data);
					data = JSON.parse(data);
					$(old_this).parents('form').find('#opportunity_post_sale').html(data.opportunities);
					$('#name_of_institute_postsale').val(data.nameofinstitute);
					$('#contact_detail_postsale').val(data.contactdetail);
					$('#address_postsale').val(data.address);
					$("#name_of_institute_postsale").prop("readonly", true);
					$("#contact_detail_postsale").prop("readonly", true);
					$("#address_postsale").prop("readonly", true);
				}
			});
			$('#demo').html('<option value="">Select Demo Machine</option>');
		});
		$(document).on("change", "#opportunity_post_sale", function() {
			var old_this = $(this);
			$(old_this).parents('form').find('#demo_post_sale').html('<option value="">Select Demo Machine</option>');
			$.ajax({
				type: "POST",
				url: "<?php echo SITE_URL; ?>getDemo",
				data: 'opportunity_id=' + $(this).val(),
				beforeSend: function() {},
				success: function(data) {
					// $(old_this).parents('form').find('#demo_post_sale').html(data);
					data = JSON.parse(data);
					$(old_this).parents('form').find('#demo_post_sale').html(data.demo);
					$('#key_decision_postsale').val(data.key_makers);
					$("#key_decision_postsale").prop("readonly", true);
					$('#unit_details_with_specific_model_postsale').val(data.unit_details_with_specific_model);
					$("#unit_details_with_specific_model_postsale").prop("readonly", true);
					$('#name_of_units_demonstrated_postsale').val(data.unit_details_with_specific_model);
				}
			});
		});
		// existing customer visit option to get lead and opportunity
		$(document).on("change", "#lead_existing", function() {
			var old_this = $(this);
			$(old_this).parents('form').find('#opportunity_existing').html('<option value="">Select Opportunity</option>');
			$.ajax({
				type: "POST",
				url: "<?php echo SITE_URL; ?>getOpportunity",
				data: 'lead_id=' + $(this).val(),
				beforeSend: function() {},
				success: function(data) {
					// $(old_this).parents('form').find('#opportunity_existing').html(data);
					data = JSON.parse(data);
					$(old_this).parents('form').find('#opportunity_existing').html(data.opportunities);
					$('#name_of_institute_existing').val(data.nameofinstitute);
					$('#contact_detail_existing').val(data.contactdetail);
					$('#address_existing').val(data.address);
					$("#name_of_institute_existing").prop("readonly", true);
					$("#contact_detail_existing").prop("readonly", true);
					$("#address_existing").prop("readonly", true);
				}
			});
			$('#demo').html('<option value="">Select Demo Machine</option>');
		});
		$(document).on("change", "#opportunity_existing", function() {
			var old_this = $(this);
			$(old_this).parents('form').find('#demo_existing').html('<option value="">Select Demo Machine</option>');
			$.ajax({
				type: "POST",
				url: "<?php echo SITE_URL; ?>getDemo",
				data: 'opportunity_id=' + $(this).val(),
				beforeSend: function() {},
				success: function(data) {
					// $(old_this).parents('form').find('#demo_existing').html(data);
					data = JSON.parse(data);
					$(old_this).parents('form').find('#demo_existing').html(data.demo);
					$('#key_decision_existing').val(data.key_makers);
					$("#key_decision_existing").prop("readonly", true);
					$('#unit_details_with_specific_model_existing').val(data.unit_details_with_specific_model);
					$("#unit_details_with_specific_model_existing").prop("readonly", true);
					$('#name_of_units_demonstrated_existing').val(data.unit_details_with_specific_model);
				}
			});
		});

		$("#demo").change(function() {
			var demo_product_id = $(this).val();
			$('#calendar').html('');
			$('#calendar').fullCalendar({
				header: {
					left: 'title',
					center: '',
					right: 'month,agendaWeek,agendaDay, today, prev,next',
				},
				height: 350,
				editable: true,
				eventRender: function(event, element) {
					$(element).tooltip({
						title: event.description,
						container: $("#calendar")
					});
				},
				events: "<?php echo SITE_URL; ?>getDemoCalendar?demo_product_id=" + demo_product_id,
				editable: false
			});
			if (demo_product_id != '')
				$("#calendar_modal").click();
		});

		$("#startDate").datepicker({
			dateFormat: "yy-mm-dd",
			changeMonth: true,
			changeYear: true,
			minDate: 0,
			onSelect: function(date) {

				var date2 = $(this).datepicker('getDate');
				$('#endDate').datepicker('option', 'minDate', date2);
			}
		});

		$("#endDate").datepicker({
			dateFormat: "yy-mm-dd",
			changeMonth: true,
			changeYear: true,
			onSelect: function(date) {

				var date2 = $(this).datepicker('getDate');
				$('#startDate').datepicker('option', 'maxDate', date2);
			}
		});

		var edit_demo_val = "<?php echo $demoEdit[0]['nature_of_demo']; ?>";
		if (edit_demo_val == 'marketing') {
			$('#marketing').removeClass('hide');
			$('#pre_sale_priority').addClass('hide');
			$('#pre_sale').addClass('hide');
			$('#post_sale').addClass('hide');
			$('#existing_customer_visit').addClass('hide');
		} else if (edit_demo_val == 'pre_sale_priority') {
			$('#pre_sale_priority').removeClass('hide');
			$('#marketing').addClass('hide');
			$('#pre_sale').addClass('hide');
			$('#post_sale').addClass('hide');
			$('#existing_customer_visit').addClass('hide');

		} else if (edit_demo_val == 'pre_sale') {
			$('#pre_sale').removeClass('hide');
			$('#marketing').addClass('hide');
			$('#pre_sale_priority').addClass('hide');
			$('#post_sale').addClass('hide');
			$('#existing_customer_visit').addClass('hide');

		} else if (edit_demo_val == 'post_sale') {
			$('#post_sale').removeClass('hide');
			$('#marketing').addClass('hide');
			$('#pre_sale_priority').addClass('hide');
			$('#pre_sale').addClass('hide');
			$('#existing_customer_visit').addClass('hide');

		} else if (edit_demo_val == 'existing_customer_visit') {
			$('#existing_customer_visit').removeClass('hide');
			$('#marketing').addClass('hide');
			$('#pre_sale_priority').addClass('hide');
			$('#pre_sale').addClass('hide');
			$('#post_sale').addClass('hide');

		} else {
			$('#existing_customer_visit').addClass('hide');
			$('#marketing').addClass('hide');
			$('#pre_sale_priority').addClass('hide');
			$('#pre_sale').addClass('hide');
			$('#post_sale').addClass('hide');
		}

		$('#nature_of_demo').on('change', function() {
			var selected_val = $('#nature_of_demo').val();

			if (selected_val == 'marketing') {

				$('#marketing').removeClass('hide');
				$('#pre_sale_priority').addClass('hide');
				$('#pre_sale').addClass('hide');
				$('#post_sale').addClass('hide');
				$('#existing_customer_visit').addClass('hide');

				//Adding conditional validation
				$('.marketing_validate').prop('required', true);
				$('.pre_sale_priority_validate').prop('required', false);
				$('.pre_sale_validate').prop('required', false);
				$('.post_sale_validate').prop('required', false);
				$('.existing_customer_visit_validate').prop('required', false);

			} else if (selected_val == 'pre_sale_priority') {
				$('#pre_sale_priority').removeClass('hide');
				$('#marketing').addClass('hide');
				$('#pre_sale').addClass('hide');
				$('#post_sale').addClass('hide');
				$('#existing_customer_visit').addClass('hide');

				//Adding conditional validation
				$('.pre_sale_priority_validate').prop('required', true);
				$('.marketing_validate').prop('required', false);
				$('.pre_sale_validate').prop('required', false);
				$('.post_sale_validate').prop('required', false);
				$('.existing_customer_visit_validate').prop('required', false);

			} else if (selected_val == 'pre_sale') {
				$('#pre_sale').removeClass('hide');
				$('#marketing').addClass('hide');
				$('#pre_sale_priority').addClass('hide');
				$('#post_sale').addClass('hide');
				$('#existing_customer_visit').addClass('hide');

				//Adding conditional validation
				$('.pre_sale_validate').prop('required', true);
				$('.pre_sale_priority_validate').prop('required', false);
				$('.marketing_validate').prop('required', false);
				$('.post_sale_validate').prop('required', false);
				$('.existing_customer_visit_validate').prop('required', false);

			} else if (selected_val == 'post_sale') {
				$('#post_sale').removeClass('hide');
				$('#marketing').addClass('hide');
				$('#pre_sale_priority').addClass('hide');
				$('#pre_sale').addClass('hide');
				$('#existing_customer_visit').addClass('hide');

				//Adding conditional validation
				$('.post_sale_validate').prop('required', true);
				$('.pre_sale_validate').prop('required', false);
				$('.pre_sale_priority_validate').prop('required', false);
				$('.marketing_validate').prop('required', false);
				$('.existing_customer_visit_validate').prop('required', false);

			} else if (selected_val == 'existing_customer_visit') {
				$('#existing_customer_visit').removeClass('hide');
				$('#marketing').addClass('hide');
				$('#pre_sale_priority').addClass('hide');
				$('#pre_sale').addClass('hide');
				$('#post_sale').addClass('hide');

				//Adding conditional validation
				$('.existing_customer_visit_validate').prop('required', true);
				$('.post_sale_validate').prop('required', false);
				$('.pre_sale_validate').prop('required', false);
				$('.pre_sale_priority_validate').prop('required', false);
				$('.marketing_validate').prop('required', false);

			} else {
				$('#existing_customer_visit').addClass('hide');
				$('#marketing').addClass('hide');
				$('#pre_sale_priority').addClass('hide');
				$('#pre_sale').addClass('hide');
				$('#post_sale').addClass('hide');

				//Adding conditional validation
				$('.existing_customer_visit_validate').prop('required', false);
				$('.post_sale_validate').prop('required', false);
				$('.pre_sale_validate').prop('required', false);
				$('.pre_sale_priority_validate').prop('required', false);
				$('.marketing_validate').prop('required', false);
			}
		});

	});
</script>
<script type="text/javascript">
	$(document).ready(function() {
		//initialize the javascript
		// App.init();
		//$('.md-trigger').modalEffects();
		select2Ajax('checkCustomer', 'getCustomer');
	});

	function validateForm() {
		$('#add_demo').parsley().destroy();

		// //Disable validation for fields
		$('.non-validate').removeAttr('required');

		var form_status = $('#add_demo').parsley('validate');

		if (form_status) {
			$('#add_demo').submit();
		}
	}
</script>