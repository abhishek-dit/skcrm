<?php $this->load->view('commons/main_template',$nestedView); ?>
<?php
		if(@$flg != '')
		{
			//$flg = @$this->global_functions->decode_icrm($_REQUEST['flg']);
			if($flg == 1)
			{
				if($val == 1)
				{
					$formHeading = 'Edit Email';
				}
				else
				{
					$formHeading = 'Add New Email';
				}
				?>
				<div class="row"> 
					<div class="col-sm-12 col-md-12">
						<div class="block-flat">
							<div class="header">							
								<h4><?php echo $formHeading;?></h4>
							</div>
							<div class="content">
								<form class="form-horizontal" role="form" action="<?php echo SITE_URL; ?>adminEmailAdd"  parsley-validate novalidate method="post">
									<input type="hidden" name="id" id="id" value="<?php echo @$adminEmailEdit['id']?>">
									<div class="form-group">
										<label class="col-sm-3 control-label">Email ID<span class="req-fld">*</span></label>
										<div class="col-sm-6">
											<input type="text" required class="form-control" parsley-type="email" maxlength="100" id="email_id" value="<?php echo @$adminEmailEdit['email_id']; ?>"  pattern="[^ @]*@[^ @]*" name="email_id"  placeholder="Email ID" >
										</div>
									</div>
									<div class="form-group">
										<div class="col-sm-offset-3 col-sm-10">
											<button class="btn btn-primary" type="submit" name="submitAdminEmail" value="button"><i class="fa fa-check"></i> Submit</button>
											<a class="btn btn-danger" href="<?php echo SITE_URL;?>adminEmail"><i class="fa fa-times"></i> Cancel</a>
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
				
				<form role="form" class="form-horizontal" method="post" action="<?php echo SITE_URL;?>adminEmail">
					<div class="row">
						<div class="col-sm-12">
							<label class="col-sm-2 control-label">Email ID</label>
							<div class="col-sm-2">
								<input type="text" name="adminEmailId" placeholder="Email ID" value="<?php echo @$searchParams['adminEmailId'];?>" id="adminEmailId" class="form-control">
							</div>
							<div class="col-sm-3">
								<button type="submit" name="searchAdminEmail" class="btn btn-success" value="searchCompany"><i class="fa fa-search"></i></button>
								<a href="<?php echo SITE_URL;?>adminEmail" class="btn btn-success"><i class="fa fa-refresh"></i></a>
								<!-- <a href="<?php echo SITE_URL;?>adminUser" class="btn btn-success"><i class="fa fa-plus"></i></a> -->
                            	<!-- <button type="submit" name="downloadAdminUser" class="btn btn-success" formaction="<?php echo SITE_URL;?>downloadAdminUser" value="downloadAdminUser"><i class="fa fa-cloud-download"></i></button> -->
								<a href="<?php echo SITE_URL;?>addAdminEmail" class="btn btn-success"><i class="fa fa-plus"></i></a>
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
								<th class="text-center"><strong>Email</strong></th>
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
										<td class="text-center"><?php echo @$row['email_id'];?></td>
											<td class="text-center">
											<!-- <a class="btn btn-default" style="padding:3px 3px;" href="<?php echo SITE_URL;?>editAdminEmail/<?php echo @icrm_encode($row['id']); ?>"><i class="fa fa-pencil"></i></a>  -->
												<a class="btn btn-danger" style="padding:3px 3px;" href="<?php echo SITE_URL;?>deleteAdminEmail/<?php echo @icrm_encode($row['id']); ?>" onclick="return confirm('Are you sure you want to De-Activate?')"><i class="fa fa-trash-o"></i></a>
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
