<?php $this->load->view('commons/main_template',$nestedView); ?>
<div class="cl-mcont">
	<div class="row">
		<div class="block-flat">
			<div class="content">
				<form class="form-horizontal" role="form" action="<?php echo SITE_URL; ?>updateUserDetails"  parsley-validate novalidate method="post">
					<input type="hidden" name="role_id" value="<?php echo $role_id;?>">
					<input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id;?>">
					<div class="form-group no-padding">
						<div class="col-sm-7">
							<h3 class="hthin">User Info</h3>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">First Name <span class="req-fld">*</span> </label>
						<div class="col-sm-6">
							<input type="text" maxlength="50" class="form-control" required name="first_name" placeholder="First name" value="<?php echo @$user['first_name'];?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Last Name </label>
						<div class="col-sm-6">
							<input type="text" maxlength="50" class="form-control" name="last_name" placeholder="Last name" value="<?php echo @$user['last_name'];?>">
						</div>
					</div>
					<div class="form-group">
							<?php 
							switch($role_id)
							{
								case 5:
									$tag = 'Distributor';
									break;
								case 12:
									$tag = 'Stockist';
									break;
								default:
									$tag = 'Employee';
									break;		
							}
							?>
						<label class="col-sm-3 control-label"><?php echo $tag;?> ID <span class="req-fld">*</span> </label>
						<div class="col-sm-6">
							<input type="text" maxlength="10" class="form-control" required name="employee_id" id="employee_id" placeholder="<?php echo $tag;?> ID" value="<?php echo @$user['employee_id'];?>">
							<p id="empIdValidating" class="hidden"><i class="fa fa-spinner fa-spin"></i> Checking...</p>
							<p id="empIdError" class="error hidden"></p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">E-Mail <span class="req-fld">*</span> </label>
						<div class="col-sm-6">
							<input type="email" maxlength="60" name="email" id="email" class="form-control" required placeholder="User E-Mail" value="<?php echo $user['email_id'];?>">
							<p id="emailValidating" class="hidden"><i class="fa fa-spinner fa-spin"></i> Checking...</p>
							<p id="emailError" class="error hidden"></p>
						</div>
					</div>
                    <div class="form-group">
                    	<?php
                    		$mobile = explode("-", $user['mobile_no']);
                    	?>
						<label class="col-sm-3 control-label">Mobile <span class="req-fld">*</span> </label>
						<div class="col-sm-1">
							<?php echo form_dropdown('isd1', $isd, @$mobile[0],'class="select2"'); ?>
						</div>
						<div class="col-sm-5">
							<input type="text" size="10" maxlength="10" class="form-control only-numbers" value="<?php echo @$mobile[1];?>" required name="mobile" placeholder="Mobile Number">
						</div>
					</div>
                    <div class="form-group">
                    	<?php
                    		$alternate = explode("-", @$user['alternate_number']);
                    		$isd1 = ($alternate[0] == '')?91:$alternate[0];
                    	?>                    
						<label class="col-sm-3 control-label">Alternate Number  </label>
						<div class="col-sm-1">
							<?php echo form_dropdown('isd2', $isd, $isd1,'class="select2"'); ?>
						</div>
						<div class="col-sm-5">
							<input type="text" size="10" maxlength="10" class="form-control only-numbers" value="<?php echo @$alternate[1];?>" name="alternate_number" placeholder="Mobile Number">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Branch <span class="req-fld">*</span> </label>
						<div class="col-sm-6">
							<?php echo form_dropdown('branch_id', $branch, @$user['branch_id'],'required class="select2"'); ?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Address Line1</label>
						<div class="col-sm-6">
							<textarea class="form-control" maxlegnth="255" name="address1"><?php echo @$user['address1'];?></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Address Line2</label>
						<div class="col-sm-6">
							<textarea class="form-control" maxlength="255" name="address2"><?php echo @$user['address2'];?></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">City <span class="req-fld">*</span> </label>
						<div class="col-sm-6">
							<input type="text" maxlength="50" class="form-control" name="city" required placeholder="City" value="<?php echo @$user['city'];?>">
						</div>
					</div>
					<?php
						if($role_id==5||$role_id==12) { // if distributor, stockist
							if($role_id==5) {$heading = 'Distributor details'; $name='Distributor Name';}
							if($role_id==12) {$heading = 'Stockist details';  $name='Stockist Name';}
						?>
					<div class="form-group no-padding">
						<div class="col-sm-7">
							<h3 class="hthin"><?php echo $heading;?></h3>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Distributor Name <span class="req-fld">*</span> </label>
						<div class="col-sm-6">
							<input type="text" maxlength="255" class="form-control" required name="distributor_name" placeholder="<?php echo $name;?>" value="<?php echo @$distributor['distributor_name'];?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">PAN Number  </label>
						<div class="col-sm-6">
							<input type="text" maxlength="20" class="form-control" name="pan_number" placeholder="PAN Number" value="<?php echo @$distributor['PAN_number'];?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">TIN Number  </label>
						<div class="col-sm-6">
							<input type="text" maxlength="20" class="form-control" name="tin_number" placeholder="TIN Number" value="<?php echo @$distributor['TIN_number'];?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">TAN Number  </label>
						<div class="col-sm-6">
							<input type="text" maxlength="20" class="form-control" name="tan_number" placeholder="TAN Number" value="<?php echo @$distributor['TAN_number'];?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Service Tax Number  </label>
						<div class="col-sm-6">
							<input type="text" maxlength="20" class="form-control" name="service_tax_number" placeholder="Service Tax Number" value="<?php echo @$distributor['service_tax_number'];?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Sales Tax Number  </label>
						<div class="col-sm-6">
							<input type="text" maxlength="20" class="form-control" name="sales_tax_number" placeholder="Sales Tax Number" value="<?php echo @$distributor['sales_tax_number'];?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Excise Number  </label>
						<div class="col-sm-6">
							<input type="text" maxlength="20" class="form-control" name="excise_number" placeholder="Excise Number" value="<?php echo @$distributor['excise_number'];?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Bank </label>
						<div class="col-sm-6">
							<input type="text" maxlength="100" class="form-control" name="bank_name" placeholder="Bank Name" value="<?php echo @$distributor['bank_name'];?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Bank Branch </label>
						<div class="col-sm-6">
							<input type="text"  maxlength="100" class="form-control" name="branch" placeholder="Bank Branch" value="<?php echo @$distributor['branch'];?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Account Name </label>
						<div class="col-sm-6">
							<input type="text"  maxlength="100" class="form-control" name="ac_name" placeholder="Account Name" value="<?php echo @$distributor['ac_name'];?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Account Number </label>
						<div class="col-sm-6">
							<input type="text"  maxlength="20" class="form-control" name="ac_no" placeholder="Bank Account Number" value="<?php echo @$distributor['ac_no'];?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">IFSC </label>
						<div class="col-sm-6">
							<input type="text"  maxlength="20" class="form-control" name="ifsc" placeholder="IFSC Code" value="<?php echo @$distributor['ifsc'];?>">
						</div>
					</div>
					<?php
						}
						?>
					<br>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<a class="btn btn-default" href="<?php echo SITE_URL.'editUser/'.$encoded_id;?>">Cancel</a>
							<button type="submit" class="btn btn-primary">Submit</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>


<?php $this->load->view('commons/main_footer.php',$nestedView); ?>