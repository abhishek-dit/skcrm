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
	
	

	<link rel="shortcut icon" href="<?php echo assets_url(); ?>images/favicon.png">
    <!-- Bootstrap core CSS -->
    <link href="<?php echo assets_url(); ?>js/bootstrap/dist/css/bootstrap.css" rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo assets_url(); ?>fonts/font-awesome-4/css/font-awesome.min.css">
    <!-- Custom styles for this template -->
    <link href="<?php echo assets_url(); ?>css/styles-1.css" rel="stylesheet" />

</head>

<body>
	<!--login modal-->
	<div id="loginModal" class="modal show" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog" style="width:600px;">
			<div class="modal-content">
				<div class="modal-header">
					<div class="row">
						<div class="col-md-3">
							<img src="<?php echo assets_url(); ?>images/logo.jpg" >	
						</div>
						<div class="col-md-6">
							<br><br>
							<h3 align="center" class="style2">SKANRAY - iCRM</h3>
						</div>
						<div class="col-md-3">
							<img src="<?php echo assets_url(); ?>images/logo1.png">
						</div>
					</div>
				</div>



					<div class="row">
						<div class="col-sm-12"><?php echo $this->session->flashdata('response'); ?></div>
					</div>
					<div class="row">

						<div class="col-md-2"></div>
						<div class="col-md-8">
						    
							<div class="modal-body" id="divLogin" style="overflow-y:hidden; height: 350px;">
								<form class="form col-md-12 center-block" method = "POST"   parsley-validate novalidate>
									<div class="form-group"><br><br>
										<input type="text" class="form-control input-lg" required name = "user_id"  placeholder="Employee ID">
										<span><h4>&nbsp;  <?php //echo $ssoerr; ?></h4></span>
									</div>
									<div class="form-group">
										<input type="password" class="form-control input-lg" required name="password"  placeholder="Password" >
										<span><h4>&nbsp;  <?php //echo $passerr; ?> </h4></span>
									</div><br>
									<div class="row">
										<div class="col-md-2"> </div>
										<div class="col-md-8"> 
											<div class="form-group">
												<button class="btn btn-lg btn-block btn-primary" type="submit" name="submit">Login</button>
					                             <a href="#" id="forgetAnchor"><p style="text-align:center">Forgot Password</p></a>
												<span align = "center"><h6>&nbsp;v1.1.6 <?php //echo $autherr; ?></h6></span>
											</div>
					                        
										</div>
					                    
									</div>
								</form>
							</div>
						</div>
						<div class="col-md-2"></div>
					</div>
					<div class="row" id="divForget">
						<div class="col-md-2"></div>
						<div class="col-md-8">
							
							<div class="modal-body" style="overflow-y:hidden; height: 350px;">
								<form class="form col-md-12 center-block" action="<?php echo SITE_URL.'forgotPassword'; ?>" method="POST">
									<div class="form-group"><br><br>
									<input type="text" class="form-control input-lg" required name="employeeId" maxlength = "10" placeholder="Enter Employee ID">
									</div>
									<br>
									<div class="row">
										<div class="col-md-2"> </div>
										<div class="col-md-8"> 
											<div class="form-group">
												<button class="btn btn-lg btn-block btn-primary" value="1" type="submit" name="forgetsubmit">Get Reset Link</button>
					                             <a class="btn btn-lg btn-block btn-default" id="LoginForm">Login</a>
												<br><br>     
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
						<div class="col-md-2"></div>
					</div>






				<div class="modal-footer"></div>
			</div>
		</div>
	</div>
	<script type="text/javascript" src="<?php echo assets_url(); ?>js/jquery.js"></script>
	<script src="<?php echo assets_url(); ?>js/bootstrap/dist/js/bootstrap.min.js"></script>
	<script src="<?php echo assets_url(); ?>js/jquery.ui/jquery-ui.js" type="text/javascript"></script>
	<script src="<?php echo assets_url(); ?>js/jquery-ui.min.js" type="text/javascript"></script>	
	<script type="text/javascript" src="<?php echo assets_url(); ?>js/behaviour/general.js"></script>
	<script type="text/javascript" src="<?php echo assets_url(); ?>js/jquery.parsley/parsley.js"></script>

</body>
</html>