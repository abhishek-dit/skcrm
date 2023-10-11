<?php $this->load->view('commons/main_template',$nestedView); ?>
<?php
		if(@$flg != '')
		{
			//$flg = @$this->global_functions->decode_icrm($_REQUEST['flg']);
			if($flg == 1)
			{
				if($val == 1)
				{
					$formHeading = 'Edit Admin User Details';
				}
				else
				{
					$formHeading = 'Add New Admin User';
				}
				?>
				<div class="row"> 
					<div class="col-sm-12 col-md-12">
						<div class="block-flat">
							<div class="header">							
								<h4><?php echo $formHeading;?></h4>
							</div>
							<div class="content">
								<form class="form-horizontal" role="form" action="<?php echo SITE_URL; ?>adminUserAdd"  parsley-validate novalidate method="post">
									<input type="hidden" name="user_id" id="user_id" value="<?php echo @$adminUserEdit[0]['user_id']?>">
									<div class="form-group">
										<?php $disable = (@$adminUserEdit[0]['employee_id'] != '')?'readonly':''; ?>
										<label for="inputName" class="col-sm-3 control-label">Employee ID <span class="req-fld">*</span></label>
										<div class="col-sm-6">
											<input type="text" maxlength="50" <?php //echo $disable; ?> class="form-control" required name="employee_id" id="employee_id" placeholder="Employee ID" value="<?php echo @$adminUserEdit[0]['employee_id']; ?>">
											<p id="empIdValidating" class="hidden"><i class="fa fa-spinner fa-spin"></i> Checking...</p>
											<p id="empIdError" class="error hidden"></p>
										</div>
									</div>
									<div class="form-group">
										<label for="inputName" class="col-sm-3 control-label">Company <span class="req-fld">*</span></label>
										<div class="col-sm-6">
											<?php echo form_dropdown('company_id', $companies, @$adminUserEdit[0]['company_id'],'required class="form-control"'); ?>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">First Name<span class="req-fld">*</span></label>
										<div class="col-sm-6">
											<input type="text" required maxlength="50" class="form-control" id="first_name" value="<?php echo @$adminUserEdit[0]['first_name']; ?>"  name="first_name"  placeholder="First Name" >
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">Last Name<span class="req-fld">*</span></label>
										<div class="col-sm-6">
											<input type="text" required maxlength="50" class="form-control" id="last_name" value="<?php echo @$adminUserEdit[0]['last_name']; ?>"  name="last_name"  placeholder="last_name" >
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">Mobile Number<span class="req-fld">*</span></label>
										<div class="col-sm-1">
		                                    <?php
		                                    $mobile[0] = "91";
		                                    if (@$adminUserEdit[0]['mobile_no'] != NULL) {
		                                        $mobile = explode("-", @$adminUserEdit[0]['mobile_no']);
		                                    }
		                                    echo form_dropdown('isd', @$isd, $mobile[0], 'class="select2"');
		                                    ?>
		                                </div>
		                                <div class="col-sm-5">
		                                    <input type="text" size="10" maxlength="10" class="form-control only-numbers" required name="mobile_no" value="<?php echo @$mobile[1]; ?>" placeholder="Mobile Number">
		                                </div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">Email ID<span class="req-fld">*</span></label>
										<div class="col-sm-6">
											<input type="text" required class="form-control" parsley-type="email" maxlength="100" id="email_id" value="<?php echo @$adminUserEdit[0]['email_id']; ?>"  name="email_id"  placeholder="Email ID" >
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">Address </label>
										<div class="col-sm-6">
											<textarea class="form-control" maxlength="255" id="address1" name="address1" placeholder="Address"><?php echo @$adminUserEdit[0]['address1']; ?></textarea>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">Address 1</label>
										<div class="col-sm-6">
											<textarea class="form-control" id="address1" maxlength="255"  name="address2" placeholder="Address 1" ><?php echo @$adminUserEdit[0]['address2']; ?></textarea>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">State</label>
										<div class="col-sm-6">
											<input type="text" maxlength="100" class="form-control" id="state" value="<?php echo @$adminUserEdit[0]['state']; ?>"  name="state" placeholder="State" >
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">City</label>
										<div class="col-sm-6">
											<input type="text" maxlength="100" class="form-control" id="city" value="<?php echo @$adminUserEdit[0]['city']; ?>"  name="city" placeholder="City">
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">Branch <span class="req-fld">*</span> </label>
										<div class="col-sm-6">
											<?php echo form_dropdown('branch_id', $branch, @$adminUserEdit[0]['branch_id'],'required class="select2"'); ?>
										</div>
									</div>

									<div class="form-group">
										<div class="col-sm-offset-3 col-sm-10">
											<button class="btn btn-primary" type="submit" name="submitAdminUser" value="button"><i class="fa fa-check"></i> Submit</button>
											<a class="btn btn-danger" href="<?php echo SITE_URL;?>adminUser"><i class="fa fa-times"></i> Cancel</a>
										</div>
									</div>
								</form>
							</div>
						</div>				
					</div>
				</div><br>

				<?php
			}
		}
		echo $this->session->flashdata('response');
	?>

<?php
if(@$displayList==1) {
?>
<div class="row"> 
	<div class="col-sm-12 col-md-12">
		<div class="block-flat">
			<table class="table table-bordered"></table>
			<div class="content">
				
				<form role="form" class="form-horizontal" method="post" action="<?php echo SITE_URL;?>adminUser">
					<div class="row">
						<div class="col-sm-12">
							<label class="col-sm-1 control-label">Company</label>
                            <div class="col-sm-2">
								<?php echo form_dropdown('adminUserCompany', $companies, @$searchParams['adminUserCompany'],'class="form-control"'); ?>
							</div>

							<label class="col-sm-2 control-label">Employee ID</label>
							<div class="col-sm-2">
								<input type="text" name="adminUserID" placeholder="Employee ID" value="<?php echo @$searchParams['adminUserID'];?>" id="adminUserID" class="form-control">
							</div>
							<label class="col-sm-1 control-label">Name</label>
							<div class="col-sm-2">
								<input type="text" name="adminUserName" placeholder="Name" value="<?php echo @$searchParams['adminUserName'];?>" id="adminUserID" class="form-control">
							</div>
							<div class="col-sm-2">
								<button type="submit" name="searchAdminUser" class="btn btn-success" value="searchCompany"><i class="fa fa-search"></i></button>
                            	<button type="submit" name="downloadAdminUser" class="btn btn-success" formaction="<?php echo SITE_URL;?>downloadAdminUser" value="downloadAdminUser"><i class="fa fa-cloud-download"></i></button>
								<a href="<?php echo SITE_URL;?>addAdminUser" class="btn btn-success"><i class="fa fa-plus"></i></a>
							</div>
						</div>
					</div>
				</form>
				<div class="header"></div>
				<div class="table-responsive">
					<table class="table table-bordered hover">
						<thead>
							<tr>
								<th class="text-center"><strong>S.NO</strong></th>
								<th class="text-center"><strong>Employee ID</strong></th>
								<th class="text-center"><strong>Name</strong></th>
								<th class="text-center"><strong>Company</strong></th>
								<th class="text-center"><strong>Email</strong></th>
								<th class="text-center"><strong>Mobile</strong></th>
								<th class="text-center"><strong>Action</strong></th>
							</tr>
						</thead>
						<tbody>
						<?php
							
							if(@$total_rows>0)
							{
								foreach($adminUserSearch as $row)
								{?>
									<tr>
										<td class="text-center"><?php echo @$sn;?></td>
										<td class="text-center"><?php echo @$row['employee_id'];?></td>
										<td class="text-center"><?php echo @$row['first_name'];?></td>
										<td class="text-center"><?php echo @$row['name'];?></td>
										<td class="text-center"><?php echo @$row['email_id'];?></td>
										<td class="text-center"><?php echo @$row['mobile_no'];?></td>
																				<td class="text-center">
											<a class="btn btn-default" style="padding:3px 3px;" href="<?php echo SITE_URL;?>editAdminUser/<?php echo @icrm_encode($row['user_id']); ?>"><i class="fa fa-pencil"></i></a> 
											<?php
											if(@$row['status'] == 1)
											{
												?>
												<a class="btn btn-danger" style="padding:3px 3px;" href="<?php echo SITE_URL;?>deleteAdminUser/<?php echo @icrm_encode($row['user_id']); ?>" onclick="return confirm('Are you sure you want to Delete?')"><i class="fa fa-trash-o"></i></a>
												<?php
											}
											else
											{
												?>
												<a class="btn btn-info" title="Activate" style="padding:3px 3px;" href="<?php echo SITE_URL;?>activateAdminUser/<?php echo @icrm_encode($row['user_id']); ?>"  onclick="return confirm('Are you sure you want to Activate?')"><i class="fa fa-check"></i></a>
												<?php
											}
											?>
										</td>
									</tr>
						<?php	
									@$sn++;
								}
							} else {
							?>	<tr><td colspan="7" align="center"><span class="label label-primary">No Records</span></td></tr>
					<?php 	} ?>
						</tbody>
					</table>
				</div>
                <div class="row">
                	<div class="col-sm-12">
                    	<div class="pull-left"><?php echo @$pagermessage ; ?></div>
                        <div class="pull-right">
							<div class="dataTables_paginate paging_bs_normal">
                            	<?php echo @$pagination_links; ?>
                            </div>
                        </div>
                    </div>
				</div> 
			</div>
		</div>				
	</div>
</div>
<?php
}
?>


<?php $this->load->view('commons/main_footer.php',$nestedView);?>

<script type="text/javascript">

//validate employee id unique
$('#employee_id').blur(function(){
	var employee_id = $(this).val();
	var user_id = $('#user_id').val();
	if(employee_id!=''){
		$("#empIdValidating").removeClass("hidden");
		$("#empIdError").addClass("hidden");
		var data = 'employee_id='+employee_id+'&user_id='+user_id;
		
		$.ajax({
		type:"POST",
		url:AJAX_CONTROLLER_URL+'is_employeeIdExist',
		data:data,
		cache:false,
		success:function(html){ 
	//	alert(html);
		$("#empIdValidating").addClass("hidden");
			if(html==1)
			{
				$('#employee_id').val('');
				$('#empIdError').html('Employee ID <b>'+employee_id+'</b> already existed');
				$("#empIdError").removeClass("hidden");
				return false;
			}
			else
			{	
				$('#empIdError').html('');
				$("#empIdError").addClass("hidden");
				return false;
			}
		}
		});
	}
});


</script>
