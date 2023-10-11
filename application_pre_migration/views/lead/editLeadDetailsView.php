<?php $this->load->view('commons/main_template', $nestedView);
echo $this->session->flashdata('response'); 
$encode_lead_id = @icrm_encode($lead_id);

$user_id = @$leadDetails['user_id'];
$leadStatus = @$leadDetails['status'];
$checkUser = (($user_id == $this->session->userdata('user_id')) && $leadStatus != 19)?1:0;

$formAction = ($checkPage == 1)?'updateLead':'';


?>

<div class="row wizard-row">
	<div class="col-md-12 fuelux">
		<div class="block-wizard">
			
			<div class="step-content">
				<form class="form-horizontal lead_form" id="leadDetailsForm" method="post" action="<?php echo SITE_URL.'updateRejectedLead';?>" data-parsley-validate novalidate>
                <input type="hidden" name="encoded_id" value="<?php echo $encoded_id;?>">
					
                    <?php 
                    $disableField = (@$checkUser)?'':'disabled';
                    ?>
                    <input type="hidden" name="lead" value="<?php echo @$leadDetails['lead_id']; ?>">
                    <input type="hidden" name="contact_id" value="<?php echo @$leadDetails['contact_id']; ?>">
                    <div class="form-group">
                        <label for="inputName" class="col-sm-3 control-label">Customer</label>
                        <div class="col-sm-6" style="margin-top:7px;">
                            <?php echo @$leadDetails['customer']; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputName" class="col-sm-3 control-label">Contact Person 1</label>
                        <div class="col-sm-6" style="margin-top:7px;">
                            <?php echo $leadDetails['contact']; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Telephone</label>
                        <div class="col-sm-1">
                            <?php
                            $telephone[0] = "91";
                            if (@$contact_data[0]['telephone'] != NULL) {
                                $telephone = explode("-", @$contact_data[0]['telephone']);
                            }
                            echo form_dropdown('isd1', @$isd, @$telephone[0], 'class="select2"');
                            ?>
                        </div>
                        <div class="col-sm-5">
                            <input type="text"  maxlength="10"  class="form-control only-numbers" id="telephone" value="<?php echo @$telephone[1]; ?>"  name="telephone" placeholder="Telephone" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Mobile <span class="req-fld">*</span> </label>
                        <div class="col-sm-1">
                            <?php
                            $mobile[0] = "91";
                            if (@$contact_data[0]['mobile_no'] != NULL) {
                                $mobile = explode("-", @$contact_data[0]['mobile_no']);
                            }
                            echo form_dropdown('isd2', @$isd, $mobile[0], 'class="select2"');
                            ?>
                        </div>
                        <div class="col-sm-5">
                            <input type="text" size="10" maxlength="10" class="form-control only-numbers" required name="mobile_no" value="<?php echo @$mobile[1]; ?>" placeholder="Mobile Number">
                        </div>
                    </div>
                    <div class="form-group">
                                <label class="col-sm-3 control-label">Email<span class="req-fld">*</span></label>
                                <div class="col-sm-6">
                                    <input type="text" required parsley-type="email" maxlength="80"  class="form-control" id="email" value="<?php echo @$contact_data[0]['email']; ?>"  name="email" placeholder="Email" >
                                </div>
                            </div>
                    <?php if($checkUser) { ?>
					<div class="form-group">
						<div class="col-sm-offset-3 col-sm-10">
							<button class="btn btn-primary" type="submit" name="updateLead" value="1"><i class="fa fa-check"></i> Update</button>
							<a href="<?php echo SITE_URL;?>closedLeads"><button type="button" class="btn btn-danger" ><i class="fa fa-times"></i> Cancel</button></a>
						</div>
					</div>
                    <?php
                    }
                    ?>
				</form>	
			</div>
		</div>
	</div>
</div>				

<?php 
$this->load->view('commons/main_footer.php', $nestedView); ?>
