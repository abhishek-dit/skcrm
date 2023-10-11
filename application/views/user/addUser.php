<?php $this->load->view('commons/main_template',$nestedView); ?>
<?php
if($role_id=='') {
?>
<div class="row">
	<div class="col-sm-12 col-md-12">
		<div class="block-flat">
			<div class="header">
				<h4>Choose User Role</h4>
			</div>
			<div class="content">
				<form class="form-horizontal" role="form" action="<?php echo SITE_URL; ?>addUser"  parsley-validate novalidate method="post">
					<div class="form-group">
							<label class="col-sm-3 control-label">Role</label>
							<div class="col-sm-6">
								<select class="form-control" required name="role_id" id="role_id">
									<option value="">select</option>
									<?php
										if($roles) {
											foreach($roles as $role) {
												
												echo '<option value="'.$role['role_id'].'">'.$role['name'].'</option>';
											}
										}
										?>
								</select>
							</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-3 col-sm-10">
							<button class="btn btn-primary" type="submit" name="submitRole" value="1"><i class="fa fa-check"></i> Next</button>
							<!--<a class="btn btn-danger" href="<?php echo SITE_URL;?>productCategory"><i class="fa fa-times"></i> Cancel</a>-->
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php
}
else if($role_id>0) {
?>
<div class="row wizard-row">
	<div class="col-md-12 fuelux">
		<div class="block-wizard">
			<div id="wizard1" class="wizard wizard-ux">
				<ul class="steps">
					<li data-target="#step1" class="active">User Details<span class="chevron"></span></li>
					<li data-target="#step2">Location Details<span class="chevron"></span></li>
					<li data-target="#step3">Product Details<span class="chevron"></span></li>
				</ul>
				<div class="actions">
					<button type="button" class="btn btn-xs btn-prev btn-default"> <i class="icon-arrow-left"></i>Prev</button>
					<button type="button" class="btn btn-xs btn-next btn-default" data-last="Finish">Next<i class="icon-arrow-right"></i></button>
				</div>
			</div>
			<div class="step-content">
				<form class="form-horizontal" id="userAddForm" method="post" action="<?php echo SITE_URL?>insertUser" data-parsley-namespace="data-parsley-" data-parsley-validate novalidate>
                <input type="hidden" name="role_id" id="role_id" value="<?php echo $role_id;?>">
                <input type="hidden" name="role_level_id" id="role_level_id" value="<?php echo $role_level_id;?>">
                <input type="hidden" name="user_id" id="user_id" value="">
					<div class="step-pane active" id="step1">
						<div class="form-group no-padding">
							<div class="col-sm-7">
								<h3 class="hthin">User Info</h3>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">First Name <span class="req-fld">*</span> </label>
							<div class="col-sm-6">
								<input type="text" maxlength="50" class="form-control" required name="first_name" placeholder="First name">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Last Name </label>
							<div class="col-sm-6">
								<input type="text" maxlength="50" class="form-control" name="last_name" placeholder="Last name">
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
							<label class="col-sm-3 control-label"><?php echo $tag; ?> ID <span class="req-fld">*</span> </label>
							<div class="col-sm-6">
								<input type="text" maxlength="10" class="form-control" required name="employee_id" id="employee_id" placeholder="<?php echo $tag; ?> ID">
								<p id="empIdValidating" class="hidden"><i class="fa fa-spinner fa-spin"></i> Checking...</p>
								<p id="empIdError" class="error hidden"></p>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">E-Mail <span class="req-fld">*</span> </label>
							<div class="col-sm-6">
								<input type="email" maxlength="60" name="email" id="email" class="form-control" required placeholder="User E-Mail">
								<p id="emailValidating" class="hidden"><i class="fa fa-spinner fa-spin"></i> Checking...</p>
								<p id="emailError" class="error hidden"></p>
							</div>
						</div>
                        <div class="form-group">
							<label class="col-sm-3 control-label">Mobile <span class="req-fld">*</span> </label>
							<div class="col-sm-1">
								<?php echo form_dropdown('isd1', $isd, 91,'class="select2"'); ?>
							</div>
							<div class="col-sm-5">
								<input type="text" size="10" maxlength="10" class="form-control only-numbers" required name="mobile" placeholder="Mobile Number">
							</div>
						</div>
                        <div class="form-group">
							<label class="col-sm-3 control-label">Alternate Number  </label>
							<div class="col-sm-1">
								<?php echo form_dropdown('isd2', $isd, 91,'class="select2"'); ?>
							</div>
							<div class="col-sm-5">
								<input type="text" size="10" maxlength="10" class="form-control only-numbers" name="alternate_number" placeholder="Mobile Number">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Branch <span class="req-fld">*</span> </label>
							<div class="col-sm-6">
								<?php echo form_dropdown('branch_id', $branch, '','required class="select2"'); ?>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Address Line1</label>
							<div class="col-sm-6">
								<textarea class="form-control" maxlength="255" name="address1"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Address Line2</label>
							<div class="col-sm-6">
								<textarea class="form-control" maxlength="255"  name="address2"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">City <span class="req-fld">*</span> </label>
							<div class="col-sm-6">
								<input type="text" maxlength="50" class="form-control" required name="cityName" placeholder="City">
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
							<label class="col-sm-3 control-label"><?php echo $name;?> <span class="req-fld">*</span> </label>
							<div class="col-sm-6">
								<input type="text" class="form-control" maxlength="255" required name="distributor_name" placeholder="<?php echo $name;?>">
							</div>
						</div>
                        <div class="form-group">
							<label class="col-sm-3 control-label">PAN Number  </label>
							<div class="col-sm-6">
								<input type="text" maxlength="20" class="form-control" name="pan_number" placeholder="PAN Number">
							</div>
						</div>
                        <div class="form-group">
							<label class="col-sm-3 control-label">TIN Number  </label>
							<div class="col-sm-6">
								<input type="text" maxlength="20" class="form-control" name="tin_number" placeholder="TIN Number">
							</div>
						</div>
                        <div class="form-group">
							<label class="col-sm-3 control-label">TAN Number  </label>
							<div class="col-sm-6">
								<input type="text" maxlength="20" class="form-control" name="tan_number" placeholder="TAN Number">
							</div>
						</div>
                        <div class="form-group">
							<label class="col-sm-3 control-label">Service Tax Number  </label>
							<div class="col-sm-6">
								<input type="text" maxlength="20" class="form-control" name="service_tax_number" placeholder="Service Tax Number">
							</div>
						</div>
                        <div class="form-group">
							<label class="col-sm-3 control-label">Sales Tax Number  </label>
							<div class="col-sm-6">
								<input type="text" maxlength="20" class="form-control" name="sales_tax_number" placeholder="Sales Tax Number">
							</div>
						</div>
                        <div class="form-group">
							<label class="col-sm-3 control-label">Excise Number  </label>
							<div class="col-sm-6">
								<input type="text" maxlength="20" class="form-control" name="excise_number" placeholder="Excise Number">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Bank </label>
							<div class="col-sm-6">
								<input type="text" maxlength="100" class="form-control" name="bank_name" placeholder="Bank Name">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Bank Branch </label>
							<div class="col-sm-6">
								<input type="text" maxlength="100" class="form-control" name="branch" placeholder="Bank Branch">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Account Name </label>
							<div class="col-sm-6">
								<input type="text" maxlength="100" class="form-control" name="ac_name" placeholder="Account Name">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Account Number </label>
							<div class="col-sm-6">
								<input type="text" maxlength="20" class="form-control" name="ac_no" placeholder="Bank Account Number">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">IFSC </label>
							<div class="col-sm-6">
								<input type="text" maxlength="20" class="form-control" name="ifsc" placeholder="IFSC Code">
							</div>
						</div>

                        <?php
						}
						?>
                        <br>
						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
								<a class="btn btn-default" href="<?php echo SITE_URL?>addUser">Cancel</a>
                                <?php
								$only_step1_roles = array(1,2,3,11,13,12,14);
								if(in_array($role_id,$only_step1_roles)) {
								?>
									<button type="submit" class="btn btn-primary ">Submit</button>
                                <?php
								}
								else {
								?>
									<button data-wizard="#wizard1" class="btn btn-primary wizard-next">Next Step <i class="fa fa-caret-right"></i></button>
                                <?php
								}
								?>
							</div>
						</div>
					</div>
					<div class="step-pane" id="step2">
						<div class="form-group no-padding">
							<div class="col-sm-7">
								<h3 class="hthin">Assign locations to user</h3>
							</div>
						</div>
                        <?php
						switch($role_level_id) {
							case 1: case 8:
								include_once('location_view/level1.php');
							break;
							case 2:
								include_once('location_view/level2.php');
							break;
							case 3: case 4:
								include_once('location_view/level3.php');
							break;
							case 5: case 6:
								include_once('location_view/level4.php');
							break;
							case 7:
								include_once('location_view/level5.php');
							break;
						}
						?>
						<div class="form-group">
							<div class="col-sm-12">
								<button data-wizard="#wizard1" class="btn btn-default wizard-previous"><i class="fa fa-caret-left"></i> Previous</button>
								<button data-wizard="#wizard1" class="btn btn-primary wizard-next">Next Step <i class="fa fa-caret-right"></i></button>
							</div>
						</div>
					</div>
					<div class="step-pane" id="step3">
						<div class="form-group no-padding">
							<div class="col-sm-7">
								<h3 class="hthin">Assign products to user</h3>
							</div>
						</div>
						<?php
						/*echo $sess_company;
						print_r($productCategories);*/
						switch($role_id) {
							case 4: // sales engineer
							case 5: // distributor
							case 6: // RSM
							case 8: // NSM
								include_once('products_view/level2.php');
							break;
							default:
								include_once('products_view/level1.php');
							break;
						}
						?>
                        <br><br>
                        <div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
								<button data-wizard="#wizard1" class="btn btn-default wizard-previous"><i class="fa fa-caret-left"></i> Previous</button>
								<button type="submit"class="btn btn-primary submit_user">Submit</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php
}
?>
<?php $this->load->view('commons/main_footer.php',$nestedView); ?>
<script>
	$(document).on('click','.submit_user',function(){
		var products = $('input:checkbox:checked.product').map(function () {
	  return this.value;
	}).get();
		var res= products.join('p');
		$('.prod_hidd').val(res);
		var city_parent = [];
	var indexes = $('.city_parent').map(function(){
    return this.value;
    }).get();
	var res1= indexes.join('p');
    $('.city_parent_hidd').val(res1);
	var district_parent = $('.district_parent').map(function(){
    return this.value;
    }).get();
	var res2= district_parent.join('p');
	console.log(res2);
    $('.district_parent_hidd').val(res2);
	//	return false;
	});
</script>