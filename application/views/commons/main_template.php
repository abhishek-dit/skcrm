<script>
	var SITE_URL = "<?php echo SITE_URL; ?>";
	var AJAX_CONTROLLER_URL = "<?php echo SITE_URL; ?>Ajax_ci/";
</script>
<?php

if (@$_SESSION['user_id'] == '') {
	echo "<script language='javascript'>";
	echo "window.location.href= SITE_URL + 'login'";
	echo "</script>";
}
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
	<!-- <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,400italic,700,800' type='text/css'>
	<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Raleway:300,200,100' type='text/css'> -->

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
	if (count($css_includes) > 0) {
		foreach ($css_includes as $css_file) {
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
	<link rel="stylesheet" href="<?php echo assets_url(); ?>css/custom.css" />
</head>
</head>

<body>

	<!-- START: Fixed Top navbar -->
	<div id="head-nav" class="navbar navbar-default navbar-fixed-top">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
					<span class="fa fa-gear"></span>
				</button>
				<a href="<?php echo SITE_URL; ?>home" class="navbar-brand"><strong>iCRM</strong></a>
			</div>
			<div class="navbar-collapse collapse">
				<ul class="nav navbar-nav">
					<li><a><b>Employee ID: <?php echo $_SESSION['employee_id']; ?> (<?php echo $_SESSION['name']; ?>)</b></a></li>
				</ul>
				<ul class="nav navbar-nav navbar-right user-nav">
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-gear"> </i><b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a href="<?php echo SITE_URL; ?>changePassword">Change Password</a>
								<a href="<?php echo SITE_URL; ?>logout"><span>Logout</span></a>
						</ul>
					</li>
					<!--
					<li class="active dropdown">
						<a href="logout.php"><span>LogOut </span><i class="fa fa-sign-out"></i></a>
					</li>
					-->
				</ul>
				<ul class="nav navbar-nav navbar-right user-nav">
					<li class="dropdown_menu col-menu-1">
						<a></a>
					</li>
				</ul>

				<ul class="nav navbar-nav navbar-right user-nav">
					<li><a><b>Role: <?php echo $_SESSION['role_name']; ?></b></a></li>

					<?php
					if (@$_SESSION['s_role_id'] == 13) { ?>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">Login As<b class="caret"></b></a>
							<ul class="dropdown-menu">
								<?php
								foreach (@$_SESSION['role'] as $role) {
									if ($role['role_id'] != $_SESSION['role_id']) {
								?>

										<li><a href="<?php echo SITE_URL; ?>roles?role=<?php echo $role['role_id']; ?>"><?php echo $role['name']; ?></a></li>
								<?php
									}
								}
								?>
							</ul>
						</li>
					<?php
					}

					?>

				</ul>

			</div>
		</div>
	</div>
	<!-- END: Fixed Top navbar -->
	<!-- START: Fixed Sidebar navbar -->
	<div id="cl-wrapper" class="fixed-menu">
		<div class="cl-sidebar">
			<div class="cl-toggle"><i class="fa fa-bars"></i></div>
			<div class="cl-navblock">
				<div class="menu-space">
					<div class="content">
						<ul class="cl-vnavigation">
							<?php if ($_SESSION['role_id'] != 14) { ?>
								<li <?php if ($cur_page == 'index') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>home"><i class="fa fa-home"></i><span>Home</span></a></li>
							<?php }
							if ($_SESSION['role_id'] == 14) {  // OTR
							?>
								<li <?php if ($cur_page == 'otr_list') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>otr_list"><i class="fa fa-cloud-download"></i><span>C-Note Download</span></a></li>
							<?php }
							if ($_SESSION['role_id'] == 15) {  // OTR
							?>
								<li <?php if ($cur_page == 'otr_list') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>otr_list"><i class="fa fa-cloud-download"></i><span>C-Note Download</span></a></li>
								<li <?php if ($cur_page == 'commission_report') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>otr_commission_report"><i class="fa fa-money"></i><span>Commission</span></a></li>
								<!-- <li><a href="#"><i class="fa fa-map-marker"></i><span>Live Location</span></a>
									<ul class="sub-menu">
										<li <?php if ($cur_page == 'track_live_location') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>live_location_list"><i class="fa fa-location-arrow"></i><span>Live Location Report</span></a></li>
										<li <?php if ($cur_page == 'live_location') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>live_location"><i class="fa fa-map-marker"></i><span>Live Location</span></a></li>
										<li <?php if ($cur_page == 'new_live_location') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>track_live_location"><i class="fa fa-map-marker"></i><span>Zoom Live Location</span></a></li>
									</ul>
								</li> -->
								<!-- <li><a href="#"><i class="fa  fa-pencil"></i><span>SO Number Entry</span></a>
								<ul class="sub-menu">
									<li <?php if ($parent_page == 'soEntryOpen') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>soEntryOpen"><i class="fa fa-folder-open-o"></i><span>Open</span></a></li>
									<li <?php if ($parent_page == 'soEntryClose') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>soEntryClose"><i class="fa fa-folder-o"></i><span>Closed</span></a></li>
									
								</ul>
							</li> -->
								<li <?php if ($cur_page == 'approveCustomers') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>approveCustomers"><i class="fa fa-check"></i><span>Approve Customer</span></a></li>
							<?php }
							if ($_SESSION['role_id'] == 1) { ?>
								<li <?php if ($cur_page == 'company') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>company"><i class="fa fa-building-o"></i><span>Manage Company</span></a></li>
								<li <?php if ($cur_page == 'adminUser') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>adminUser"><i class="fa fa-user"></i><span>Manage Admin User</span></a></li>
								<li <?php if ($cur_page == 'adminEmail') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>adminEmail"><i class="fa fa-envelope"></i><span>Manage Email</span></a></li>
							<?php } ?>
							<?php if ($_SESSION['role_id'] == 3) { ?>
								<li <?php if ($cur_page == 'campaign') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>campaign"><i class="fa fa-building-o"></i><span>Manage Campaign</span></a></li>
								<li <?php if ($cur_page == 'Documents') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>campaignDocuments"><i class="fa fa-upload"></i><span>Upload Documents</span></a></li>
								<li <?php if ($cur_page == 'assignLeads') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>assignLeads"><i class="fa fa-check-square-o"></i><span>Assign Leads</span></a></li>
								<li <?php if ($cur_page == 'trackLeads') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>trackLeads"><i class="fa fa-calendar"></i><span>Track Leads</span></a></li>
								<li><a href="#"><i class="fa fa-users"></i><span>Manage Customer</span></a>
									<ul class="sub-menu">
										<li <?php if ($parent_page == 'customer') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>customer"><i class="fa fa-user"></i><span>Customer</span></a></li>
										<li <?php if ($parent_page == 'contact') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>contact"><i class="fa fa-book"></i><span>Contact</span></a></li>
										<li <?php if ($parent_page == 'speciality') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>speciality"><i class="fa fa-star"></i><span>Speciality</span></a></li>
									</ul>
								</li>

							<?php } ?>
							<?php if ($_SESSION['role_id'] == 2) { ?>
								<li <?php if ($cur_page == 'user') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>users"><i class="fa fa-users"></i><span>Manage Users</span></a></li>
								<li <?php if ($cur_page == 'branch') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>branch"><i class="fa fa-building-o"></i><span>Manage Branch</span></a></li>
								<li <?php if ($cur_page == 'adminEmail') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>adminEmail"><i class="fa fa-envelope"></i><span>Manage Email</span></a></li>
								<li><a href="#"><i class="fa fa-bar-chart-o"></i><span>Reports</span></a>
									<ul class="sub-menu">

										<li <?php if ($cur_page == 'location_report') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>location_report"><i class="fa fa-bar-chart-o"></i><span>Location Report</span></a></li>

									</ul>
								</li>
								<li <?php if ($cur_page == 'channel_partner') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>channel_partner"><i class="fa fa-thumbs-o-up"></i><span>Manage Channel Partner</span></a></li>
								<li><a href="#"><i class="fa fa-book"></i><span>Manage Product</span></a>
									<ul class="sub-menu">
										<li <?php if ($parent_page == 'productCategory') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>productCategory"><i class="fa fa-dropbox"></i><span> Category</span></a></li>
										<li <?php if ($parent_page == 'materialGroup') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>materialGroup"><i class="fa fa-qrcode"></i><span>Segment</span></a></li>
										<li <?php if ($parent_page == 'competitor') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>competitor"><i class="fa fa-thumbs-o-up"></i><span>Competitor</span></a></li>
										<li <?php if ($parent_page == 'productSubCategory') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>productSubCategory"><i class="fa fa-crosshairs"></i><span>Sub System</span></a></li>
										<li <?php if ($parent_page == 'product') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>product"><i class="fa fa-list-alt"></i><span>Products</span></a></li>
										<li <?php if ($parent_page == 'demoProduct') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>demoProduct"><i class="fa fa-suitcase"></i><span>Demo</span></a></li>
										<!-- <li <?php if ($parent_page == 'productTargetUser') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>productTargetUsers"><i class="fa fa-list-ol"></i><span>User Product Target</span></a></li> -->
										<li <?php if ($parent_page == 'financialyear') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>financial_year"><i class="fa fa-dropbox"></i><span> Financial Year</span></a></li>
										<li <?php if ($parent_page == 'productTargetUser') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>weekly_user_product_targets"><i class="fa fa-list-ol"></i><span>User Product Target</span></a></li>
										<li <?php if ($parent_page == 'customer_category') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>customer_category"><i class="fa fa-user"></i><span>Customer Type</span></a></li>
										<li <?php if ($parent_page == 'sub_category') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>sub_category"><i class="fa fa-crosshairs"></i><span>Customer Sub Category</span></a></li>
									</ul>
								</li>
								<li><a href="#"><i class="fa fa-hospital-o"></i><span>Manage Territory</span></a>
									<ul class="sub-menu">
										<li <?php if ($parent_page == 'geo') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>geo"><i class="fa fa-globe"></i><span>Geo</span></a></li>
										<li <?php if ($parent_page == 'country') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>country"><i class="fa fa-plane"></i><span>Country</span></a></li>
										<li <?php if ($parent_page == 'region') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>region"><i class="fa fa-map-marker"></i><span>Region</span></a></li>
										<li <?php if ($parent_page == 'state') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>state"><i class="fa fa-truck"></i><span>State</span></a></li>
										<li <?php if ($parent_page == 'district') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>district"><i class="fa fa-road"></i><span>District</span></a></li>
										<li <?php if ($parent_page == 'city') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>city"><i class="fa fa-thumb-tack"></i><span>City/Town</span></a></li>
										<li <?php if ($parent_page == 'currency') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>currency"><i class="fa fa-money"></i><span>Currency</span></a></li>
										<li <?php if ($parent_page == 'currency_conversion') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>currency_conversion"><i class="fa fa-arrow-right"></i><span>Currency Conversion</span></a></li>
									</ul>
								</li>
								<li><a href="#"><i class="fa fa-users"></i><span>Manage Customer</span></a>
									<ul class="sub-menu">
										<li <?php if ($parent_page == 'customer') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>customer"><i class="fa fa-user"></i><span>Customer</span></a></li>
										<li <?php if ($parent_page == 'contact') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>contact"><i class="fa fa-book"></i><span>Contact</span></a></li>
										<li <?php if ($parent_page == 'speciality') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>speciality"><i class="fa fa-star"></i><span>Speciality</span></a></li>
									</ul>
								</li>
								<li><a href="#"><i class="fa  fa-pencil"></i><span>SO Number Entry</span></a>
									<ul class="sub-menu">
										<li <?php if ($parent_page == 'soEntryOpen') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>soEntryOpen"><i class="fa fa-folder-open-o"></i><span>Open</span></a></li>
										<li <?php if ($parent_page == 'soEntryClose') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>soEntryClose"><i class="fa fa-folder-o"></i><span>Closed</span></a></li>

									</ul>
								</li>
								<li><a href="#"><i class="fa fa-bar-chart-o"></i><span>Log Report</span></a>
									<ul class="sub-menu">
										<li <?php if ($cur_page == 'userLogs') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>userLogs"><i class="fa fa-cloud-download"></i><span>User Logs</span></a></li>
										<li <?php if ($cur_page == 'punch_in_userLogs') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>punch_in_report"><i class="fa fa-mobile"></i><span>Punch In Report</span></a></li>
										<li <?php if ($cur_page == 'PunchinLogs') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>punchinlogs"><i class="fa fa-mobile"></i><span>Punch In Logs</span></a></li>
									</ul>
								</li>

								<!-- <li <?php if ($cur_page == 'quoteDiscount') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>quoteDiscount"><i class="fa fa-thumbs-o-up"></i><span>Quote Discount</span></a></li> -->

								<li <?php if ($cur_page == 'assignInactiveUserLeads') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>assignInactiveUserLeads"><i class="fa  fa-exclamation-triangle"></i><span>Inactive User Leads</span></a></li>
								<!-- New enhancements 20 Apr 2017 contract notes start -->
								<li <?php if ($cur_page == 'manageContractNotes') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>manageContractNotes"><i class="fa fa-trash-o"></i><span>Delete Contract Notes</span></a></li>
								<!-- New enhancements 20 Apr 2017 contract notes end -->
								<!-- Phase2 update 21-08-2017 start-->
								<li><a href="#"><i class="fa  fa-cloud-upload"></i><span>Bulk Uploads</span></a>
									<ul class="sub-menu">
										<li <?php if ($cur_page == 'product_stock_upload') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>product_stock_upload"><i class="fa fa-tasks"></i><span>Stock In Hand</span></a></li>
										<li <?php if ($cur_page == 'new_so_amount_upload') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>new_so_amount_upload"><i class="fa fa-money"></i><span>Outstanding Amount</span></a></li>
									</ul>
								</li>
								<li><a href="#"><i class="fa  fa-cogs"></i><span>Settings</span></a>
									<ul class="sub-menu">
										<li <?php if ($cur_page == 'settings') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>settings"><i class="fa fa-cogs"></i><span>General Settings</span></a></li>
										<li <?php if ($cur_page == 'margin_bands') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>margin_bands"><i class="fa fa-thumbs-o-up"></i><span>Margin Bands</span></a></li>
										<li <?php if ($cur_page == 'incentive_settings') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>incentive_settings"><i class="fa fa-cog"></i><span>Incentives Settings</span></a></li>
										<li <?php if ($cur_page == 'manageFreeSupplyItems') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>manageFreeSupplyItems"><i class="fa fa-book"></i><span>Manage Free-supply Item %</span></a></li>
										<li <?php if ($cur_page == 'addConditionForApprovalMail') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>addConditionForApprovalMail"><i class="fa fa-book"></i><span>Condition For Approval Mail</span></a></li>
										<li <?php if ($cur_page == 'quoteRevStatusChange') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>quoteRevStatusChange"><i class="fa fa-book"></i><span>Quote Revision Status Change</span></a></li>
									</ul>
								</li>
								<li><a href="#"><i class="fa fa-map-marker"></i><span>Live Location</span></a>
									<ul class="sub-menu">
										<li <?php if ($cur_page == 'track_live_location') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>live_location_list"><i class="fa fa-location-arrow"></i><span>Live Location Report</span></a></li>
										<li <?php if ($cur_page == 'live_location') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>live_location"><i class="fa fa-map-marker"></i><span>Live Location</span></a></li>
										<li <?php if ($cur_page == 'new_live_location') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>track_live_location"><i class="fa fa-map-marker"></i><span>Zoom Live Location</span></a></li>
									</ul>
								</li>
								<!-- Phase2 update 21-08-2017  end -->

							<?php } ?>
							<?php if ($_SESSION['role_id'] == 4 || $_SESSION['role_id'] == 5 || $_SESSION['role_id'] == 6 || $_SESSION['role_id'] == 7 || $_SESSION['role_id'] == 8 || $_SESSION['role_id'] == 9 || $_SESSION['role_id'] == 10 || $_SESSION['role_id'] == 11) { ?>
								<?php if ($_SESSION['role_id'] != 5) { ?>
									<li><a href="#"><i class="fa  fa-dashboard (alias)"></i><span>Dashboards</span></a>
										<ul class="sub-menu">
											<li <?php if ($parent_page == 'leadsDashboard') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>leadsDashboard"><i class="fa fa-dashboard (alias)"></i><span>Leads Dashboard</span></a></li>
											<li <?php if ($parent_page == 'opportunityDashboard') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>opportunityDashboard"><i class="fa fa-bar-chart-o"></i><span>Opportunity Dashboard</span></a></li>
										</ul>
									</li>

								<?php } ?>
								<?php
								$reports_allowed_roles = array(4, 6, 7, 8, 9, 10);
								if (in_array($_SESSION['role_id'], $reports_allowed_roles)) { ?>
									<li><a href="#"><i class="fa fa-bar-chart-o"></i><span>Reports</span></a>
										<ul class="sub-menu">
											<?php if ($_SESSION['role_id'] == 9 || $_SESSION['role_id'] == 10) { ?>
												<li <?php if ($cur_page == 'location_report') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>location_report"><i class="fa fa-bar-chart-o"></i><span>Location Report</span></a></li>
												<li <?php if ($cur_page == 'demo_report') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>demo_report"><i class="fa fa-bar-chart-o"></i><span>Demo Report</span></a></li>
											<?php } ?>
												<li <?php if ($cur_page == 'funnel_report') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>funnel_report"><i class="fa fa-bar-chart-o"></i><span>Funnel Report</span></a></li>
											<?php
											if (in_array($_SESSION['role_id'], marginAnalysisReportAllowedRoles())) {
											?>
												<li <?php if ($cur_page == 'margin_analysis_report') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>margin_analysis_report"><i class="fa fa-bar-chart-o"></i><span>Margin Analysis</span></a></li>
												<li <?php if ($cur_page == 'cnote_margin_analysis') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>cnote_margin_analysis"><i class="fa fa-bar-chart-o"></i><span>Margin Analysis (C-Note)</span></a></li>
											<?php
											}
											?>
											<li <?php if ($cur_page == 'opportunityLost') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>opportunity_lost_report"><i class="fa fa-bar-chart-o"></i><span>Opportunity Lost</span></a></li>
											<li <?php if ($cur_page == 'open_orders') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>open_orders"><i class="fa fa-bar-chart-o"></i><span>Open Orders</span></a></li>
											<!-- <li <?php if ($parent_page == 'open_opportunities') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>open_opportunities"><i class="fa fa-bar-chart-o"></i><span>Funnel / Open Opportunities</span></a></li>
									<li <?php if ($parent_page == 'open_orders') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>open_orders"><i class="fa fa-bar-chart-o"></i><span>Open Orders</span></a></li>
									<li <?php if ($parent_page == 'opportunity_lost_report') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>opportunity_lost_report"><i class="fa fa-bar-chart-o"></i><span>Opportunity Lost</span></a></li>
									<li <?php if ($parent_page == 'fresh_business_report') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>fresh_business_report"><i class="fa fa-bar-chart-o"></i><span>Fresh Business</span></a></li> -->
											<li <?php if ($parent_page == 'stock_in_hand_table') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>stock_in_hand_table"><i class="fa fa-bar-chart-o"></i><span>Stock In Hand</span></a></li>
											<li <?php if ($cur_page == 'fresh_business_report') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>fresh_business_report"><i class="fa fa-bar-chart-o"></i><span>Fresh Business report </span></a></li>
											<li <?php if ($cur_page == 'target_vs_sales_report') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>target_vs_sales_report"><i class="fa fa-bar-chart-o"></i><span>Target Vs Sales</span></a></li>
											<li <?php if ($cur_page == 'run_rate') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>run_rate"><i class="fa fa-bar-chart-o"></i><span>Runrate Projection</span></a></li>
											<li <?php if ($cur_page == 'visit_plan_report') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>visit_plan_report"><i class="fa fa-bar-chart-o"></i><span>Visit Performance Report</span></a></li>
											<li <?php if ($cur_page == 'daily_visit_plan_report') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>daily_visit_plan_report"><i class="fa fa-bar-chart-o"></i><span>Daily Visit Plan Report</span></a></li>
											<li <?php if ($cur_page == 'lead_performance_report') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>lead_performance_report"><i class="fa fa-bar-chart-o"></i><span>Lead Performance Report</span></a></li>
											<li <?php if ($cur_page == 'order_lost_analysis_report') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>order_lost_analysis_report"><i class="fa fa-bar-chart-o"></i><span>Order Lost Analysis Report</span></a></li>
											<!-- <li <?php //if($cur_page=='location_report') echo 'class="active"';
														?>><a href="<?php //echo SITE_URL; 
																																?>location_report"><i class="fa fa-bar-chart-o"></i><span>Location Report</span></a></li> -->
											<?php
											$incentives_allowed_roles = array(4, 6, 7, 8, 9); // SE, RSM, RBH, NSM, CH
											if (in_array($_SESSION['role_id'], $incentives_allowed_roles)) {
											?>
												<li <?php if ($cur_page == 'incentives') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>incentives"><i class="fa fa-bar-chart-o"></i><span>Incentives report</span></a></li>
											<?php
											}
											?>
											<?php
											$outstanding_report_enabled_roles = array(8, 9); // NSM, CH
											if (in_array($_SESSION['role_id'], $outstanding_report_enabled_roles)) {
											?>
												<li <?php if ($cur_page == 'get_new_outstanding_report') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>get_new_outstanding_report"><i class="fa fa-bar-chart-o"></i><span>Outstanding report</span></a></li>
											<?php
											}
											?>
										</ul>
									</li>

								<?php } ?>
								<li <?php if ($parent_page == 'viewCalendar') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>viewCalendar"><i class="fa fa-calendar"></i><span>Calendar</span></a></li>
								<!-- <li <?php if ($parent_page == 'userProductTargetVsActual') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>user_productTargetVsActual"><i class="fa fa-bar-chart-o"></i><span>Targets Vs Actual</span></a></li> -->
								<?php
								if ($_SESSION['role_id'] == 7) // RBH
								{
								?>
									<li <?php if ($cur_page == 'commission_report') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>commission_report"><i class="fa fa-money"></i><span>Commission</span></a></li>
								<?php
								}
								?>
								<li><a href="#"><i class="fa  fa-user-md"></i><span>Leads</span></a>
									<ul class="sub-menu">
										<li <?php if ($cur_page == 'newLead') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>newLead"><i class="fa fa-edit"></i><span>New</span></a></li>
										<?php if ($_SESSION['role_id'] == 6 || $_SESSION['role_id'] == 7 || $_SESSION['role_id'] == 8 || $_SESSION['role_id'] == 9 || $_SESSION['role_id'] == 10 || $_SESSION['role_id'] == 11) {
										?>
										<?php
										} ?>

										<li <?php if ($cur_page == 'openLeads') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>openLeads"><i class="fa  fa-folder-open-o"></i><span>Open</span></a></li>
										<li <?php if ($cur_page == 'closedLeads') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>closedLeads"><i class="fa fa-folder-o"></i><span>Closed</span></a></li>
									</ul>
								</li>
								<li><a href="#"><i class="fa fa-edit"></i><span>Opportunity</span></a>
									<ul>
										<li <?php if ($cur_page == 'opportunity') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>opportunity"><i class="fa fa-folder-open-o"></i><span>Open</span></a></li>
										<li <?php if ($cur_page == 'opportunityClosed') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>opportunityClosed"><i class="fa fa-folder-o"></i><span>Closed</span></a></li>
										<!-- all opportunities added by suresh on 4th May 2017-->
										<li <?php if ($cur_page == 'opportunityStatus') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>opportunityStatus"><i class="fa fa-clock-o"></i><span>Funnel History</span></a></li>
										<?php
										/*if($_SESSION['role_id']==8) // NSM
									{
									?>
									<li <?php if($cur_page=='po_list') echo 'class="active"';?>><a href="<?php echo SITE_URL; ?>po_list"><i class="fa fa-link"></i><span>Tag Opportunities</span></a></li>
									<li <?php if($cur_page=='po_opp_tag_list') echo 'class="active"';?>><a href="<?php echo SITE_URL; ?>po_opp_tag_list"><i class="fa fa-pencil"></i><span>Tag Opp Status Change</span></a></li>
									<?php
									}
									if($_SESSION['role_id']==7) // RBH
									{
									?>
									<li <?php if($cur_page=='po_list') echo 'class="active"';?>><a href="<?php echo SITE_URL; ?>po_list"><i class="fa fa-link"></i><span>Tag Opportunities</span></a></li>
									<li <?php if($cur_page=='untag_po_list') echo 'class="active"';?>><a href="<?php echo SITE_URL; ?>untag_po_list"><i class="fa fa-unlink"></i><span>UnTag Opportunities</span></a></li>
									<li <?php if($cur_page=='po_opp_tag_list') echo 'class="active"';?>><a href="<?php echo SITE_URL; ?>po_opp_tag_list"><i class="fa fa-edit"></i><span>Update Opportunity</span></a></li>
									<?php
									}*/
										?>
									</ul>
								<li><a href="#"><i class="fa fa-calendar-o"></i><span>Plan Visit/ Demo</span></a>
									<ul class="sub-menu">
										<li <?php if ($parent_page == 'visit') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>visit"><i class="fa fa-dashboard"></i><span>Plan a Visit</span></a></li>
										<li <?php if ($parent_page == 'demo') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>demo"><i class="fa fa-suitcase"></i><span>Plan a Demo</span></a></li>

									</ul>
								</li>
								<!-- <li><a href="#"><i class="fa  fa-pencil"></i><span>SO Number Entry</span></a>
								<ul class="sub-menu">
									<li <?php if ($parent_page == 'soEntryOpen') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>soEntryOpen"><i class="fa fa-folder-open-o"></i><span>Open</span></a></li>
									<li <?php if ($parent_page == 'soEntryClose') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>soEntryClose"><i class="fa fa-folder-o"></i><span>Closed</span></a></li>
									
								</ul>
							</li> -->
								<?php if ($_SESSION['role_id'] == 7 || $_SESSION['role_id'] == 8 || $_SESSION['role_id'] == 9) { ?>

									<!-- <li <?php if ($parent_page == 'quoteApprovalList') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>quoteApprovalList"><i class="fa fa-thumbs-o-up"></i><span>Quote Approval</span></a></li> -->
									<li><a href="#"><i class="fa fa-thumbs-o-up"></i><span>Approvals</span></a>
										<ul class="sub-menu">
											<?php
											if ($_SESSION['role_id'] == 7 || $_SESSION['role_id'] == 8) // RBH
											{
											?>
												<li <?php if ($cur_page == 'contract_note_approval_list') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>contract_note_approval_list"><i class="fa fa-thumbs-o-up"></i><span>CNote Approval</span></a></li>
											<?php
											}
											?>

											<li <?php if ($cur_page == 'marginAnalysisList') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>margin_analysis_list"><i class="fa fa-thumbs-o-up"></i><span>Quote Approval</span></a></li>
											<li <?php if ($cur_page == 'po_approval_list') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>po_approval_list"><i class="fa fa-thumbs-o-up"></i><span>Purchase Order Approval</span></a></li>
										</ul>
									</li>
									<li <?php if ($parent_page == 'demoDetails') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>demoDetails"><i class="fa fa-lightbulb-o"></i><span>Demo Products</span></a></li>
								<?php } ?>
								<li><a href="#"><i class="fa fa-users"></i><span>Manage Customer</span></a>
									<ul class="sub-menu">
										<li <?php if ($parent_page == 'customer') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>customer"><i class="fa fa-user"></i><span>Customer</span></a></li>
										<li <?php if ($parent_page == 'contact') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>contact"><i class="fa fa-book"></i><span>Contact</span></a></li>
									</ul>
								</li>
								<li <?php if ($cur_page == 'viewCampaignDocuments') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>viewCampaignDocuments"><i class="fa fa-files-o"></i><span>Marketing Documents</span></a></li>

								<!-- Blocked for distributor role -->
								<?php if ($_SESSION['role_id'] != 5) { ?>
									<li><a href="#"><i class="fa fa-calendar"></i><span>Track Quote/PO</span></a>
										<ul class="sub-menu">
											<li <?php if ($cur_page == 'quoteTracking') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>track_quotes"><i class="fa fa-calendar"></i><span>Track Quotes</span></a></li>
											<li <?php if ($cur_page == 'po_tracking') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>po_tracking"><i class="fa fa-calendar"></i><span>Track Purchase Orders</span></a></li>
										</ul>
									</li>

								<?php
								}
								//  Blocked for distributor role end-->

								if ($_SESSION['role_id'] == 7) // RBH
								{
								?>
									<li <?php if ($cur_page == 'get_rbh_distributor_list') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>get_rbh_distributor_list"><i class="fa fa-pencil"></i><span>Dealer Opening Stock</span></a></li>
								<?php
								}
								?>

							<?php }
							// Phase2 update: distributor purchase order 21-08-2017
							// if($_SESSION['role_id'] == 5)
							// {
							?>
							<!-- <li <?php //if($cur_page=='po_list') echo 'class="active"';
										?>><a href="<?php //echo SITE_URL; 
																										?>po_list"><i class="fa fa-shopping-cart"></i><span>Purchase Order</span></a></li>
							
							<li <?php //if($cur_page=='product_opening_stock_details') echo 'class="active"';
								?>><a href="<?php //echo SITE_URL; 
																															?>product_opening_stock_details"><i class="fa  fa-pencil"></i><span>Opening Stock Entry</span></a></li>
							<li <?php //if($cur_page=='distributor_stock_details') echo 'class="active"';
								?>><a href="<?php //echo SITE_URL; 
																														?>distributor_stock_details"><i class="fa fa-building-o"></i><span>Stock Details</span></a></li>
							<li <?php //if($cur_page=='commission_report') echo 'class="active"';
								?>><a href="<?php //echo SITE_URL; 
																												?>commission_report"><i class="fa fa-money"></i><span>Commission</span></a></li> -->
							<?php if ($_SESSION['role_id'] == 9 || $_SESSION['role_id'] == 10) { ?>
							<li><a href="#"><i class="fa fa-map-marker"></i><span>Live Location</span></a>
									<ul class="sub-menu">
										<li <?php if ($cur_page == 'track_live_location') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>live_location_list"><i class="fa fa-location-arrow"></i><span>Live Location Report</span></a></li>
										<li <?php if ($cur_page == 'live_location') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>live_location"><i class="fa fa-map-marker"></i><span>Live Location</span></a></li>
										<li <?php if ($cur_page == 'new_live_location') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>track_live_location"><i class="fa fa-map-marker"></i><span>Zoom Live Location</span></a></li>
									</ul>
								</li>
								<?php } ?>
							<?php
							// }
							if ($_SESSION['role_id'] == 14) {
							?>
								<li <?php if ($cur_page == 'approveLeads') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>approveLeads"><i class="fa fa-check"></i><span>Approve Lead</span></a></li>
								<li <?php if ($cur_page == 'approveCustomers') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>approveCustomers"><i class="fa fa-check"></i><span>Approve Customer</span></a></li>
								<li><a href="#"><i class="fa  fa-pencil"></i><span>SO Number Entry</span></a>
									<ul class="sub-menu">
										<li <?php if ($parent_page == 'soEntryOpen') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>soEntryOpen"><i class="fa fa-folder-open-o"></i><span>Open</span></a></li>
										<li <?php if ($parent_page == 'soEntryClose') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>soEntryClose"><i class="fa fa-folder-o"></i><span>Closed</span></a></li>

									</ul>
								</li>
								<!-- <li><a href="#"><i class="fa fa-map-marker"></i><span>Live Location</span></a>
									<ul class="sub-menu">
										<li <?php if ($cur_page == 'track_live_location') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>live_location_list"><i class="fa fa-location-arrow"></i><span>Live Location Report</span></a></li>
										<li <?php if ($cur_page == 'live_location') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>live_location"><i class="fa fa-map-marker"></i><span>Live Location</span></a></li>
										<li <?php if ($cur_page == 'new_live_location') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>track_live_location"><i class="fa fa-map-marker"></i><span>Zoom Live Location</span></a></li>
									</ul>
								</li> -->
								<?php if ($_SESSION['role_id'] == 14) { ?>
									<li><a href="#"><i class="fa fa-users"></i><span>Manage Customer</span></a>
										<ul class="sub-menu">
											<li <?php if ($parent_page == 'customer') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>customer"><i class="fa fa-user"></i><span>Customer</span></a></li>
											<li <?php if ($parent_page == 'contact') echo 'class="active"'; ?>><a href="<?php echo SITE_URL; ?>contact"><i class="fa fa-book"></i><span>Contact</span></a></li>
										</ul>
									</li>
							<?php
								}
							}
							?>


						</ul>
					</div>
				</div>
			</div>
		</div>

		<div class="container-fluid" id="pcont">
			<div class="page-head">
				<!--<div class="page-head">-->
				<h3><?php echo $breadCrumbTite; ?></h3>
				<ol class="breadcrumb">
					<?php
					if (isset($breadCrumbOptions)) {
						$bCount = count($breadCrumbOptions);
						if ($bCount > 0) {
							foreach ($breadCrumbOptions as $bCrumb) {
								$bClass = $bCrumb['class'];
								$bLable = $bCrumb['label'];
								$bUrl = $bCrumb['url'];
								echo '<li class="' . $bClass . '">';
								if ($bUrl != '')
									echo '<a href="' . $bUrl . '">' . $bLable . '</a>';
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