<?php $this->load->view('commons/main_template', $nestedView);
echo $this->session->flashdata('response'); 
$role_id = $this->session->userdata('role_id');
$checkRole = ($role_id == 4 || $role_id == 5)?1:0;

?>

<div class="row"> 
	<div class="col-sm-12 col-md-12">
		<div class="block-flat">
			<div class="content">
				<div class="header">
					
					<div class="row">
						<form class="form-horizontal" role="form" action="#" method="post">	
							<div class="form-group">
								<div class="col-sm-12">
									<div class="col-sm-1">
										<input type="text" name="lead_id" value="<?php echo @$searchParams['lead_id'];?>" id="lead_id" class="form-control" placeholder="Lead ID" maxlength="100">
									</div>	
									<div class="col-sm-5">
										<select class="checkCustomer" style="width:100%" name="customer">
											<option value="<?php echo $s_cus['customer_id']; ?>"><?php echo $s_cus['customer']; ?></option>
										</select>
									</div>	
									<?php 
									if($checkRole == 1) 
									{
										?>
										<input type="hidden" name="created_user" value="<?php echo $this->session->userdata('user_id'); ?>">
										<?php
									}
									else
									{
										?>
										<div class="col-sm-3">
											<select class="getReporteesWithUser" style="width:100%" name="created_user">
												<option value="<?php echo $s_created_user['user_id']; ?>"><?php echo $s_created_user['cName']; ?></option>
											</select>
										</div>	
										<?php
									}
									?>
									<div class="col-sm-3">
										<?php
										$closed_status = array('' => 'Select Closed Status', 
															22 => 'Lead Closed',
															21 => 'Lead Dropped',
															20 => 'Lead Rejected');
										 echo form_dropdown('closed_status', $closed_status, @$searchParams['closed_status'],'class="select2"'); ?>
									</div>
								</div>
							</div>
							<div class="form-group">
                                <div class="col-sm-12 text-right">
                                    <div class="col-sm-3">   
                                    </div>
                                    <div class="col-sm-3">
                                            <input type="text" required class="form-control" id="start_date" placeholder="Start Date" name="start_date" readonly  value="<?php echo @$searchParams['start_date']; ?>">
                                    </div>     
                                    <div class="col-sm-3">
                                            <input type="text" required class="form-control" id="end_date" placeholder="End Date" name="end_date" readonly  value="<?php echo @$searchParams['end_date']; ?>">
                                    </div>	
									<div class="col-sm-2">
										<button type="submit" name="searchClosedLead" value="1" class="btn btn-success"><i class="fa fa-search"></i></button>
										<?php if(in_array($role_id, allowed_download_roles()))
                                                    { ?>
										<button style="margin-left:10px;" type="submit" formaction="<?php echo SITE_URL.'download_closedLeads';?>" name="downloadLeads" value="1" class="btn btn-success"><i class="fa fa-cloud-download"></i></button>
										<?php } ?>
									</div>
                                </div>
                            </div>
						</form>	
					</div>
				</div>
				<div class="table-responsive">
					<table class="table table-bordered hover">
						<thead>
							<tr>
								<th class="text-center"><strong>Lead ID</strong></th>
								<th class="text-center"><strong>Source of Lead</strong></th>
								<th class="text-center"><strong>Customer</strong></th>
								<th class="text-center"><strong>Contact Person</strong></th>
								<?php if($checkRole == 0) { ?>
								<th class="text-center"><strong>Owner</strong></th>
								<?php } ?>
								<th class="text-center"><strong>Created Time</strong></th>
								<th class="text-center"><strong>Status</strong></th>
								<th class="text-center"><strong>Life Time(Days)</strong></th>
								<th class="text-center"><strong>Actions</strong></th>
							</tr>
						</thead>
						<tbody>
						<?php
							
							if(@$total_rows>0)
							{
								foreach($searchResults as $row)
								{?>
									<tr>
										<td class="text-center" style="width:7%;"><?php echo @$row['lead_id'];?></td>
										<td class="text-center" style="width:11%;"><?php echo @$row['source'];?></td>
										<td style="width:20%;"><?php echo @$row['customer'];?></td>
										<td style="width:18%;"><?php echo @$row['contact'];?></td>
										<?php if($checkRole == 0) { ?>
										<td style="width:15%;"><?php echo getUserName(@$row['user_id']);?></td>
										<?php } ?>
										<td class="text-center" style="width:12%;"><?php echo @DateFormatAM($row['created_time']);?></td>
										<td class="text-center" style="width:10%;"><?php echo @getLeadStatus($row['status']);?></td>
										<td class="text-center"><?php echo date_difference_two_days($row['created_time'],$row['modified_time']);?></td>
										<td class="text-center" style="width:5%;">
											<a class="btn btn-success" style="padding:3px 3px;" href="<?php echo SITE_URL;?>closedLeadDetails/<?php echo @icrm_encode($row['lead_id']); ?>"><i class="fa fa-info"></i></a> 
											<?php
											if(($row['status']==20)&&($row['user_id']==$this->session->userdata('user_id'))) // If lead rejected and logged in user is the owner
											{
												?>
												<a class="btn btn-success" style="padding:3px 3px;" href="<?php echo SITE_URL;?>editRejectedLead/<?php echo @icrm_encode($row['lead_id']); ?>"><i class="fa fa-pencil"></i></a> 
												<?php
											}
											?>
										</td>
									</tr>
						<?php	}
							} else {
								$colspan = ($checkRole)?7:8;
							?>	<tr><td colspan="<?php echo $colspan; ?>" align="center"><span class="label label-primary">No Records</span></td></tr>
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
//print_r($_SESSION['reportees']);
$this->load->view('commons/main_footer.php', $nestedView); ?>
