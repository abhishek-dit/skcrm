
<?php
	$this->load->view('commons/main_template',$nestedView); 
?>

	<?php
		if(isset($flg))
		{
			?>
			<div class="row"> 
				<div class="col-sm-12 col-md-12">
					<div class="block-flat">
						<div class="content">
							<form class="form-horizontal" role="form" action="<?php echo SITE_URL; ?>incentive_insert_settings"  parsley-validate novalidate method="post">
								<div class="form-group">
									<div class="col-md-6">
										<label for="inputStartDate" class="col-sm-4 control-label">Financial Year <span class="req-fld">*</span></label>
										<div class="col-sm-6">
			                                <select class="form-control select2" style="width:100%" name="fy_id">
		                                        <option value="">Select Year</option>
		                                        <?php
		                                        foreach ($fy_year as $row) {
		                                            echo '<option value="'.$row['fy_id'].'">'.$row['name'].'</option>';
		                                        }
		                                        ?>
		                                    </select>
										</div>
									</div>
									<div class="col-md-6">
										<label for="inputStartDate" class="col-sm-4 control-label ">Role <span class="req-fld">*</span></label>
										<div class="col-sm-6">
			                                <select class="form-control select2 role" style="width:100%" name="role_id">
		                                        <option value="">Select Role</option>
		                                        <?php
		                                        foreach ($role as $row) {
		                                            echo '<option value="'.$row['role_id'].'">'.$row['name'].'</option>';
		                                        }
		                                        ?>
		                                    </select>
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-6">
										<label for="inputEndDate" class="col-sm-4 control-label">Quarter Incentive<span class="req-fld">*</span></label>
										<div class="col-sm-6">
											<input type="number" required class="form-control" placeholder="Quarter Incentive" name="value">
										</div>
									</div>
									<!-- <div class="col-md-6">
										<label for="inputEndDate" class="col-sm-3 control-label">OS% <span class="req-fld">*</span></label>
										<div class="col-sm-6">
											<input type="number" required class="form-control" placeholder="Outstanding Percentage" name="os_amount" >
										</div>
									</div> -->
									<div class="col-md-6">
										<label for="inputEndDate" class="col-sm-4 control-label">Upper Cap <span class="req-fld">*</span></label>
										<div class="col-sm-6">
											<input type="number" required class="form-control" placeholder="Upper Value" name="upper_value" >
										</div>
									</div>
								</div>
								<div class="form-group">
									
								</div>
								<div class="form-group">
									<h4><strong><u>Grade A</u></strong></h4>
									<div class="col-md-6">
										<label for="inputFinancialYear" class="col-sm-4 control-label">PP LL <span class="req-fld">*</span></label>
										<div class="col-sm-6">
											<input type="number" required class="form-control" placeholder="Primary Product Lower Limit" name="gradea_pp_ll" >
										</div>
									</div>
									<div class="col-md-6">
										<label for="inputFinancialYear" class="col-sm-4 control-label">PP UL <span class="req-fld">*</span></label>
										<div class="col-sm-6">
											<input type="number" required class="form-control" placeholder="Primary Product Upper Limit" name="gradea_pp_ul" >
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-6">
										<label for="inputFinancialYear" class="col-sm-4 control-label">SP LL <span class="req-fld">*</span></label>
										<div class="col-sm-6">
											<input type="number" required class="form-control" placeholder="Secondary Product Lower Limit" name="gradea_sp_ll" >
										</div>
									</div>
									<div class="col-md-6">
										<label for="inputFinancialYear" class="col-sm-4 control-label">SP UL <span class="req-fld">*</span></label>
										<div class="col-sm-6">
											<input type="number" required class="form-control" placeholder="Secondary Product Upper Limit" name="gradea_sp_ul" >
										</div>
									</div>
								</div>
								<div class="grade_b hidden">
									<div class="form-group">
										<h4><strong><u>Grade B</u></strong></h4>
										<div class="col-md-6">
											<label for="inputFinancialYear" class="col-sm-4 control-label">PP LL <span class="req-fld">*</span></label>
											<div class="col-sm-6">
												<input type="number"  class="form-control req" placeholder="Primary Product Lower Limit" name="gradeb_pp_ll" >
											</div>
										</div>
										<div class="col-md-6">
											<label for="inputFinancialYear" class="col-sm-4 control-label">PP UL <span class="req-fld">*</span></label>
											<div class="col-sm-6">
												<input type="number"  class="form-control req" placeholder="Primary Product Upper Limit" name="gradeb_pp_ul" >
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="col-md-6">
											<label for="inputFinancialYear" class="col-sm-4 control-label">SP LL <span class="req-fld">*</span></label>
											<div class="col-sm-6">
												<input type="number"  class="form-control req" placeholder="Secondary Product Lower Limit" name="gradeb_sp_ll" >
											</div>
										</div>
										<div class="col-md-6">
											<label for="inputFinancialYear" class="col-sm-4 control-label">SP UL <span class="req-fld">*</span></label>
											<div class="col-sm-6">
												<input type="number"  class="form-control req" placeholder="Secondary Product Upper Limit" name="gradeb_sp_ul" >
											</div>
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-offset-5 col-sm-7">
										<button class="btn btn-primary" type="submit" name="submit" value="1"><i class="fa fa-check"></i> Submit</button>
										<a class="btn btn-danger" href="<?php echo SITE_URL;?>incentive_settings"><i class="fa fa-times"></i> Cancel</a>
									</div>
								</div>
							</form>
						</div>
					</div>				
				</div>
			</div><br>
			<?php
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
				<form role="form" class="form-horizontal" method="post" action="<?php echo SITE_URL;?>incentive_settings">
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<label class="col-sm-2 control-label">Financial Year</label>
								<div class="col-sm-2">
									<select class="form-control select2" style="width:100%" name="fy_id">
                                        <option value="">Select Year</option>
                                        <?php
                                        foreach ($fy_year as $row) {
                                        	$selected='';
                                        	if($row['fy_id']==@$searchParams['fy_id'])
                                        	{
                                        		$selected='selected';
                                        	}
                                            echo '<option value="'.$row['fy_id'].'" '.$selected.'>'.$row['name'].'</option>';
                                        }
                                        ?>
                                    </select>
								</div>
								<label class="col-sm-1 control-label">Role</label>
								<div class="col-sm-2">
									<select class="form-control select2 role" style="width:100%" name="role_id">
                                        <option value="">Select Role</option>
                                        <?php
                                        foreach ($role_list as $row) {
                                        	$selected='';
                                        	if($row['role_id']==@$searchParams['inc_role'])
                                        	{
                                        		$selected='selected';
                                        	}
                                            echo '<option value="'.$row['role_id'].'" '.$selected.'>'.$row['name'].'</option>';
                                        }
                                        ?>
                                    </select>
								</div>
								<div class="col-sm-offset-1 col-sm-4" >
									<button type="submit" name="search" title="Search" value="1" class="btn btn-success"><i class="fa fa-search"></i> </button>
		                        	<a href="<?php echo SITE_URL;?>incentive_settings" title="Reset New" class="btn btn-success"><i class="fa fa-refresh"></i> </a>
									<a href="<?php echo SITE_URL;?>add_incentive_settings" title="Add New" class="btn btn-success"><i class="fa fa-plus"></i> </a>
								</div>
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
								<th class="text-center"><strong>Financial Year</strong></th>
								<th class="text-center"><strong>Role</strong></th>
								<th class="text-center"><strong>Actions</strong></th>
							</tr>
						</thead>
						<tbody>
						<?php
							
							if(count($incentivelist)>0)
							{
								foreach($incentivelist as $row)
								{?>
									<tr>
										<td class="text-center"><?php echo $sn++;?></td>
										<td class="text-center"><?php echo @$row['financial_year'];?></td>
										<td class="text-center"><?php echo @$row['role'];?></td>
										<td class="text-center"><a class="btn btn-primary" style="padding:3px 3px;" href="<?php echo SITE_URL; ?>view_incentive_settings/<?php echo @icrm_encode($row['incentives_id']); ?>"><i class="fa fa-info"></i></a></td>
									</tr>
						<?php	}
							} else {
							?>	<tr><td colspan="6" align="center"><span class="label label-primary">No Records</span></td></tr>
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
	$this->load->view('commons/main_footer.php',$nestedView); 
?>
<script type="text/javascript">
	$(document).on('change','.role',function(){
		var role=$(this).val();
		if(role==4)
		{
			$('.grade_b').removeClass('hidden');
			$('.req').prop('required',true);
		}
		else
		{
			$('.grade_b').addClass('hidden');
			$('.req').prop('required',false);
		}
	});
</script>