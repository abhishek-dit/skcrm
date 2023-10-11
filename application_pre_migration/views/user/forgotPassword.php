<!DOCTYPE html>
<html lang="en">

<head>
	<script>
		var SITE_URL = "<?php echo SITE_URL; ?>";
	</script>
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta charset="utf-8">
	<title>Skanray iCRM Login Form</title>
	<meta name="generator" content="Bootply" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	
	

	<link rel="shortcut icon" href="<?php echo SITE_URL;?>application/assets/images/favicon.png">
    <!-- Bootstrap core CSS -->
    <link href="<?php echo SITE_URL;?>application/assets/js/bootstrap/dist/css/bootstrap.css" rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo SITE_URL;?>application/assets/fonts/font-awesome-4/css/font-awesome.min.css">
    <!-- Custom styles for this template -->
    <link href="<?php echo SITE_URL;?>application/assets/css/styles-1.css" rel="stylesheet" />

</head>

<body>
	<!--login modal-->
	<div id="loginModal" class="modal show" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<div class="row">
						<div class="col-md-3">
							<img src="<?php echo SITE_URL;?>application/assets/images/logo1.jpg" >	
						</div>
						<div class="col-md-6">
							<h3 align="center" class="style2">SKANRAY iCRM</h3>
						</div>
						<div class="col-md-3">
							<img src="<?php echo SITE_URL;?>application/assets/images/logo.jpg">
						</div>
					</div>
				</div>




				<div class="cl-mcont">				    
				    <div class="row"> 
				      <div class="col-sm-12 col-md-12">
				        <div class="block-flat">
				          <div class="col-sm-offset-4 col-sm-8">							
				            <h4>Reset Password</h4>
				          </div>
						  <br><br>
						  <br>
				          <div class="content">
				            <form class="form-horizontal" role="form" action=""  parsley-validate novalidate method="post">
				             <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
				              
							<div class="form-group">
				              <label for="inputName" class="col-sm-3 control-label"></label>
				              <div class="col-sm-6">
				                <input type="password" required class="form-control" id="newPassword" placeholder="New Password" name="newPassword">
				              </div>
				              </div>
				              <div class="form-group">
				              <label for="inputName" class="col-sm-3 control-label"></label>
				              <div class="col-sm-6">
				                <input type="password" required class="form-control" id="cnewPassword" placeholder="Confirm New Password" name="cnewPassword" parsley-equalto="#newPassword">
				              </div>
				              </div>
				                 
				              <br>
				              <div class="form-group">
				              <div class="col-sm-offset-4 col-sm-8">
				                <button class="btn btn-primary" type="submit" name="submitForgetPassword">Submit</button>
				                <button class="btn btn-default" type="reset">Reset</button>
				                
				              </div>
				              </div>
				            </form>
				          </div>
				        </div>				
				      </div>
				    </div>	  
				</div>






				<div class="modal-footer"></div>
			</div>
		</div>
	</div>
	<script type="text/javascript" src="<?php echo SITE_URL;?>application/assets/js/jquery.js"></script>
	<script src="<?php echo SITE_URL;?>application/assets/js/bootstrap/dist/js/bootstrap.min.js"></script>
	<script src="<?php echo SITE_URL;?>application/assets/js/jquery.ui/jquery-ui.js" type="text/javascript"></script>
	<script src="<?php echo SITE_URL;?>application/assets/js/jquery-ui.min.js" type="text/javascript"></script>	
	<script type="text/javascript" src="<?php echo SITE_URL;?>application/assets/js/behaviour/general.js"></script>
	<script type="text/javascript" src="<?php echo SITE_URL;?>application/assets/js/jquery.parsley/parsley.js"></script>

</body>
</html>