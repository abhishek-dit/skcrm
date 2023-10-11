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
							<input type="hidden" name="source_id" value="1">
							<div class="form-group">
								<div class="col-sm-12">
									<div class="col-sm-1">
										<input type="text" name="lead_id" value="<?php echo @$searchParams['lead_id'];?>" id="lead_id" class="form-control" placeholder="Lead ID">
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
										$open_status = array('' => 'Select Status')	 + getLeadStatusArray();
										 echo form_dropdown('open_status', $open_status, @$searchParams['open_status'],'class="select2"'); ?>
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
                                                <div class="col-sm-2 ">
                                                    <button type="submit" name="searchOpenLead" value="1" class="btn btn-success"><i class="fa fa-search"></i></button>
                                                    <?php if(in_array($role_id, allowed_download_roles()))
                                                    { ?>
                                                    <button style="margin-left:10px;" type="submit" formaction="<?php echo SITE_URL.'download_openLeads';?>" name="downloadLeads" value="1" class="btn btn-success"><i class="fa fa-cloud-download"></i></button>
                                                    <?php } ?>
                                                </div>
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
								<th class="text-center"><strong>Customer</strong></th>
								<th class="text-center"><strong>Contact Person</strong></th>
								<th class="text-center"><strong>Owner</strong></th>
								<th class="text-center"><strong>Life Time(Days)</strong></th>
								<th class="text-center"><strong>Status</strong></th>
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
										<td class="text-center" style="width:8%; <?php if($row['status'] == 19) { ?><?php } ?>"><?php echo @$row['lead_id'];?></td>
										<td style="width:20%;"><?php echo @$row['customer'];?></td>
										<td style="width:20%;"><?php echo @$row['contact'];?></td>
										<td class="text-center" style="width:20%;"><?php echo getUserName(@$row['user_id']);?></td>
										<td class="text-center"><?php echo date_difference_two_days($row['created_time'],date('Y-m-d'));?></td>
										<td class="text-center" style="width:25%;"><?php echo @leadStatusBar($row['status'], @$row['lead_id']);?></td>
										<td class="text-center" style="width:7%;">
											<?php $edit = (($row['user_id'] == $this->session->userdata('user_id')) && $row['status'] != 19)?'fa-edit (alias)':'fa-info'; ?>
											<a class="btn btn-success" style="padding:3px 3px;" href="<?php echo SITE_URL;?>openLeadDetails/<?php echo @icrm_encode($row['lead_id']); ?>"><i class="fa <?php echo $edit; ?>"></i></a> 
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
 