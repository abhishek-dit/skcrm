<!DOCTYPE html>
<html lang="en">
<head>
	<script>
		var SITE_URL = "<?php echo SITE_URL; ?>";
	</script>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="shortcut icon" href="<?php echo SITE_URL;?>application/assets/images/favicon.png">

	<title>Skanray iCRM Login Form</title>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,400italic,700,800' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Raleway:300,200,100' rel='stylesheet' type='text/css'>

	<!-- Bootstrap core CSS -->
	<link href="<?php echo SITE_URL;?>application/assets/js/bootstrap/dist/css/bootstrap.css" rel="stylesheet">

	<link rel="stylesheet" href="<?php echo SITE_URL;?>application/assets/fonts/font-awesome-4/css/font-awesome.min.css">

	<!-- Custom styles for this template -->
	<link href="<?php echo SITE_URL;?>application/assets/css/style.css" rel="stylesheet" />	

</head>

<body class="texture">

<div id="cl-wrapper" class="login-container">

	<div class="middle-login">
		<div class="block-flat">
			<div class="header">							
				<h3 class="text-center"><img class="logo-img" src="<?php echo SITE_URL;?>application/assets/images/logo1.png" alt="logo"/> <b>iCRM</b></h3>
			</div>
			<div>
				<form style="margin-bottom: 0px !important;" class="form-horizontal" method = "POST"   parsley-validate novalidate>
					<div class="content">
						<h4 class="title">Login Access</h4>
							<div class="form-group">
								<div class="col-sm-12">
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-building-o"></i></span>
										<input type="text" class="form-control input-lg" required name = "plant" maxlength = "60" placeholder="Plant Name">
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-12">
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-user"></i></span>
										<input type="text" class="form-control input-lg only-numbers" required name = "sso_id" maxlength = "10" placeholder="SSO ID">
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-12">
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-lock"></i></span>
										<input type="password" class="form-control input-lg" required name="password" maxlength = "20" placeholder="Password" >
									</div>
								</div>
							</div>
							
					</div>
					<div class="foot">
						<button class="btn btn-primary" data-dismiss="modal" type="submit">Log in</button>
						<button class="btn btn-default"  name="Forgetsubmit" id="forgetAnchor" data-dismiss="modal" type="button">Forgot Password</button>
					</div>
				</form>
			</div>
			<div id="cl-wrapper" class="forgotpassword-container">
				<div class="row" id="divForget">
				<div class="middle">
					<div class="block-flat">
						<div class="header">							
							<h3 class="text-center"><img class="logo-img" src="<?php echo SITE_URL;?>application/assets/images/logo1.png" alt="logo"/> <b>Smart Forms</b></h3>
						</div>
						<div>
							<form style="margin-bottom: 0px !important;" class="form-horizontal"  parsley-validate novalidate>
								<div class="content">
									<h5 class="title text-center"><strong>Forgot your password?</strong></h5>
									<p class="text-center">Don't worry, we'll send you an email to reset your password.</p><hr/>
									<div class="form-group">
										<div class="col-sm-12">
											<div class="input-group">
												<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
												<input type="email" name="regEmail" parsley-trigger="change" parsley-error-container="#email-error" required placeholder="Your Email" class="form-control">
											</div>
											<div id="email-error"></div>
										</div>
									</div>
									<button class="btn btn-block btn-primary btn-rad btn-lg" type="submit">Reset Password</button>
								</div>
							</form>
						</div>
					</div>
					<div class="text-center out-links"><a href="#">&copy; 2014 Your Company</a></div>
				</div> </div>
			</div>
			
		</div>
		<div class="text-center out-links"><a href="#" style="color:#333;">&copy; 2016 Entransys Pvt. Ltd.</a></div>
	</div> 
	
</div>

<script src="<?php echo SITE_URL;?>application/assets/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL;?>application/assets/js/behaviour/general.js"></script>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
  <script src="<?php echo SITE_URL;?>application/assets/js/behaviour/voice-commands.js"></script>
  <script src="<?php echo SITE_URL;?>application/assets/js/bootstrap/dist/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL;?>application/assets/js/jquery.flot/jquery.flot.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL;?>application/assets/js/jquery.flot/jquery.flot.pie.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL;?>application/assets/js/jquery.flot/jquery.flot.resize.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL;?>application/assets/js/jquery.flot/jquery.flot.labels.js"></script>
</body>
</html>
