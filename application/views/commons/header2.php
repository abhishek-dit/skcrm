<script>
	var SITE_URL = "<?php echo SITE_URL; ?>";
	var AJAX_CONTROLLER_URL = "<?php echo SITE_URL; ?>Ajax_ci/";
</script>
<?php 

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="shortcut icon" href="<?php echo assets_url(); ?>images/favicon.png">
	
	<!-- Page Title -->
	<title><?php echo $heading; ?></title>

	<!-- Font Styles -->
	<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,400italic,700,800' type='text/css'>
	<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Raleway:300,200,100' type='text/css'>
	
	<!-- CSS Files -->
	<link rel="stylesheet" href="<?php echo assets_url(); ?>js/bootstrap/dist/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="<?php echo assets_url(); ?>js/jquery.gritter/css/jquery.gritter.css" />
	<link rel="stylesheet" href="<?php echo assets_url(); ?>fonts/font-awesome-4/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo assets_url(); ?>js/jquery.nanoscroller/nanoscroller.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo assets_url(); ?>js/jquery.easypiechart/jquery.easy-pie-chart.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo assets_url(); ?>js/bootstrap.switch/bootstrap-switch.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo assets_url(); ?>js/bootstrap.datetimepicker/css/bootstrap-datetimepicker.min.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo assets_url(); ?>js/jquery.select2/select2.css" />
	<!--<link rel="stylesheet" type="text/css" href="assets/js/bootstrap.slider/css/slider.css" />-->
	<link rel="stylesheet" type="text/css" href="<?php echo assets_url(); ?>js/jquery.magnific-popup/dist/magnific-popup.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo assets_url(); ?>js/jquery.niftymodals/css/component.css" />
    <?php
	if(count($css_includes)>0)
	{
	  foreach($css_includes as $css_file)
	  {
		 echo $css_file;
	  }
	}
	?>
	
	<!-- Style Sheets -->
	<link rel="stylesheet" href="<?php echo assets_url(); ?>css/token-inputs/token-input.css" />	
	<link rel="stylesheet" href="<?php echo assets_url(); ?>css/token-inputs/token-input-facebook.css" />	
	<link rel="stylesheet" href="<?php echo assets_url(); ?>css/token-inputs/token-input-mac.css" />	
	
	<link rel="stylesheet" href="<?php echo assets_url(); ?>css/sb-admin.css" />	
	<link rel="stylesheet" href="<?php echo assets_url(); ?>css/quick_buttons.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo assets_url(); ?>css/jquery-ui.css" />
	<link rel="stylesheet" href="<?php echo assets_url(); ?>css/style.css" />	
</head>
<body>

  <!-- START: Fixed Top navbar -->
	<div id="head-nav" class="navbar navbar-default navbar-fixed-top">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				  <span class="fa fa-gear"></span>
				</button>
				<a href="<?php echo SITE_URL;?>home" class="navbar-brand" ><strong>iCRM</strong></a>
			</div>
			<div class="navbar-collapse collapse">
				
				<ul class="nav navbar-nav">
				<li><a><b> <?php if(@$user_id!=''){echo 'Welcome: '.$user['employee_id']; ?> (<?php echo $user['first_name'].' '.$user['last_name'].')';} ?></b></a></li>
				</ul>
			</div>
		</div>
	</div>
  <!-- END: Fixed Top navbar -->
  <!-- START: Fixed Sidebar navbar -->
	<div id="cl-wrapper" class="fixed-menu">
		
		
<div class="container-fluid" id="pcont">
<div class="page-head">
	<!--<div class="page-head">-->
	<h3><?php echo $breadCrumbTite;?></h3>
	<ol class="breadcrumb">
    	<?php
		if(isset($breadCrumbOptions))
		{
			$bCount = count($breadCrumbOptions);
			if($bCount>0)
			{
				foreach($breadCrumbOptions as $bCrumb)
				{
					$bClass = $bCrumb['class'];
					$bLable = $bCrumb['label'];
					$bUrl = $bCrumb['url'];
					echo '<li class="'.$bClass.'">';
					if($bUrl!='')
					echo '<a href="'.$bUrl.'">'.$bLable.'</a>';
					else
					echo $bLable;
					echo '</li>';
					
				}
			}
		}
		?>
		<!--<li><a href="#">Home</a></li>
		<li class="active">Generate PO</li>-->
	</ol>
	<!--</div>-->
</div>
	<div class="cl-mcont">