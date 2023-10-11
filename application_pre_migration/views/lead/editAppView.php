<?php $this->load->view('commons/main_template', $nestedView);
echo $this->session->flashdata('response'); 

$roleUser = @$leadDetails['role_id'];
if($roleUser == '') $roleUser = 4;
$checkRole = ($roleUser == 5)? 1: 0;
$secondUser = ($checkRole == 1)?'Sales User':'Distributor';
$hide = ($checkRole == 1)?'':'hidden';
$req = ($checkRole == 1)?'required':'';
$chide = ($leadDetails['source_id'] != 2)?'hidden':'';
$rhide = ($leadDetails['source_id'] != 3)?'hidden':'';
$colhide = ($leadDetails['source_id'] != 8)?'hidden':'';
$reporting = 0;
$checkUser = 1;
$checkPage = 1;
?>

<div class="row"> 
	<div class="col-sm-12 col-md-12">
		<div class="block-flat">
			<div class="header">
			</div>
			<div class="content">
				<form class="form-horizontal" role="form" action="<?php echo SITE_URL; ?>editApproveLead"  id=""  parsley-validate novalidate method="post">

				<?php include_once('leadDetails.php'); ?>
					<div class="form-group">
						<div class="col-sm-offset-3 col-sm-10">
							<button class="btn btn-primary" type="submit" name="approveLead" value="1"><i class="fa fa fa-thumbs-o-up"></i> Approve</button>
							<a class="btn btn-danger" title="Activate" href="<?php echo SITE_URL;?>rejectLead/<?php echo @icrm_encode(@$leadDetails['lead_id']); ?>"  onclick="return confirm('Are you sure you want to Reject?')"><i class="fa fa fa-thumbs-o-down"></i> Reject</a>
						</div>
					</div>
				</form>
			</div>

			<?php
			//echo getQueryArray(getUserLocations(13));
			//echo $this->session->userdata('check');
			//echo '<br>'.$this->db->last_query();
			?>

		</div>
	</div>
</div>

<?php include_once('leadCustomerContact.php'); ?>

<?php 
//print_r($_SESSION);
$this->load->view('commons/main_footer.php', $nestedView); ?>
