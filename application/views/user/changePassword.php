<?php
$this->load->view('commons/main_template',$nestedView); 

?>

<div class="cl-mcont">
	<!-- Password Validation Checks -->
	<?php
	if(isset($_REQUEST['flg'])) {
		switch(@$_REQUEST['flg']) {
			case 1:
			echo '<div class="alert alert-success alert-white rounded">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									<div class="icon"><i class="fa fa-check"></i></div>
									<strong>Success!</strong> Password Updated Successfully!
								 </div>';
			break;					 
			case 2:
			echo '<div class="alert alert-danger alert-white rounded">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									<div class="icon"><i class="fa fa-times-circle"></i></div>
									<strong>Error!</strong> Invalid Old Password / Please Enter a Valid Password.
								 </div>';
			break;	
		}
	}
	?>
	
    <!-- Form Content -->
	<div class="row"> 
		<div class="col-sm-12 col-md-12">
			<div class="block-flat">
				<div class="header">							
					<h4>Change Password</h4>
				</div>
				<div class="content">
					<form class="form-horizontal" role="form" action=""  parsley-validate novalidate method="post">
						<input type="hidden" name="spare_id" value="<?php echo @$row['spare_id']?>">
						<div class="form-group">
							<label for="inputName" class="col-sm-3 control-label">Old Password</label>
							<div class="col-sm-6">
								<input type="password" required class="form-control" id="oldPassword" placeholder="Old Password" name="oldPassword" >
							</div>
						</div>
						<div class="form-group">
							<label for="inputName" class="col-sm-3 control-label">New Password</label>
							<div class="col-sm-6">
								<input type="password" required class="form-control" id="newPassword" placeholder="New Password" name="newPassword">
							</div>
						</div>
						<div class="form-group">
							<label for="inputName" class="col-sm-3 control-label">Confirm New Password</label>
							<div class="col-sm-6">
								<input type="password" required class="form-control" id="cnewPassword" placeholder="Confirm New Password" name="cnewPassword" parsley-equalto="#newPassword">
							</div>
						</div><br>
						<div class="form-group">
							<div class="col-sm-offset-3 col-sm-10">
								<button class="btn btn-primary" type="submit" name="submitChangePassword"><i class="fa fa-check"></i> Submit</button>
								<a class="btn btn-danger" href="<?php echo SITE_URL;?>" ><i class="fa fa-times"></i> Cancel</a>
							</div>
						</div><br>
					</form>
				</div>
			</div>				
		</div>
    </div>	  
</div>

<?php
$this->load->view('commons/main_footer.php',$nestedView); 
?>
