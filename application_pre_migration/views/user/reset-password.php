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

						<div class="col-md-2"></div>
						<div class="col-md-8">
						    <?php  echo $this->session->flashdata('response'); ?>

							<div class="modal-body" id="divLogin" style="overflow-y:hidden; height: 350px;">
							<?php
							$link_createdTime = icrm_decode(@$_GET['st']);
							$link_time = strtotime($link_createdTime);
							$cur_time = strtotime(date('Y-m-d H:i:s'));
							$diff = $cur_time-$link_time;
							/*echo $link_createdTime;
							echo '<br>'.$link_time;
							echo '<br>'.$cur_time.'<br>';
							echo $diff;*/
							// check if link created time is less than 24 hours or not
							if($diff>(24*60*60)){
								echo '<div class="row"><div class="col-sm-1 col-md-1"></div><div class="col-sm-10 col-md-10"><div class="alert alert-danger alert-white rounded"  style="margin-top:20px;"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><div class="icon"><i class="fa fa-times-circle"></i><strong>Error!</strong> Password reset link has been expired.</div></div><div class="col-sm-1 col-md-1"></div></div>';
							}
							else{
							
							?>
								<form class="form col-md-12 center-block" role="form" action="<?php echo SITE_URL;?>resetPasswordAction"  parsley-validate novalidate method="post">
              <input type="hidden" name="encrypt_id" value="<?php echo @$_GET['reset']?>">
									<div class="form-group"><br><br>
										<input type="password" required class="form-control" id="newPassword" placeholder="New Password" name="newPassword">
										<span><h4>&nbsp;  <?php //echo $ssoerr; ?></h4></span>
									</div>
									<div class="form-group">
										<input type="password" required class="form-control" id="cnewPassword" placeholder="Confirm New Password" name="cnewPassword" parsley-equalto="#newPassword">
										<span><h4>&nbsp;  <?php //echo $passerr; ?> </h4></span>
									</div><br>
									<div class="form-group">
						              <div class="col-sm-offset-4 col-sm-8">
						                <button class="btn btn-primary" type="submit" name="submitForgetPassword" value="1">Submit</button>
						                <a class="btn btn-default" href="javascript:history.back()">Cancel</a>
						              </div>
						            </div>
								</form>
							<?php
							}
							?>
							</div>
						</div>
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