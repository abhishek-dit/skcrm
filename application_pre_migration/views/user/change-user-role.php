<?php $this->load->view('commons/main_template',$nestedView); ?>
<?php
echo $this->session->flashdata('response');
?>
<?php
if($role_id=='') {
?>
<div class="row">
	<div class="col-sm-12 col-md-12">
		<div class="block-flat">
			<div class="header">
				<h4>Change User Role</h4>
			</div>
			<div class="content">
				<form class="form-horizontal" role="form" action="<?php echo SITE_URL.'changeUserRole/'.$encoded_id; ?>"  parsley-validate novalidate method="post">
					<input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id;?>">
					<div class="form-group">
							<label class="col-sm-3 control-label">Role</label>
							<div class="col-sm-6">
								<select class="form-control" required name="role_id" id="new_role_id">
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
							<button class="btn btn-primary" type="submit" name="submitRole" value="1" id="chr_next_btn"><i class="fa fa-check"></i> Next</button>
							<button class="btn btn-primary hidden" type="submit" formaction="<?php echo SITE_URL.'updateUserRole'; ?>" name="changeRole" id="chr_submit_btn" value="1">Submit</button>
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
					<li data-target="#step2" class="active">Location Details<span class="chevron"></span></li>
					<li data-target="#step3">Product Details<span class="chevron"></span></li>
				</ul>
				<div class="actions">
					<button type="button" class="btn btn-xs btn-prev btn-default"> <i class="icon-arrow-left"></i>Prev</button>
					<button type="button" class="btn btn-xs btn-next btn-default" data-last="Finish">Next<i class="icon-arrow-right"></i></button>
				</div>
			</div>
			<div class="step-content">
				<form class="form-horizontal" id="userAddForm" method="post" action="<?php echo SITE_URL?>updateUserRole" data-parsley-namespace="data-parsley-" data-parsley-validate novalidate>
                <input type="hidden" name="role_id" id="role_id" value="<?php echo $role_id;?>">
                <input type="hidden" name="role_level_id" id="role_level_id" value="<?php echo $role_level_id;?>">
                <input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id;?>">
					<div class="step-pane active" id="step2">
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
								<a class="btn btn-default" href="<?php echo SITE_URL.'changeUserRole/'.$encoded_id; ?>">Cancel</a>
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
								<button type="submit"class="btn btn-primary">Submit</button>
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