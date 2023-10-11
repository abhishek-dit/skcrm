<?php $this->load->view('commons/main_template', $nestedView);
echo $this->session->flashdata('response'); 

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
										<input type="text" name="lead_id" value="<?php echo @$searchParams['lead_id'];?>" id="lead_id" class="form-control" placeholder="Lead ID">
									</div>	
									<div class="col-sm-4">
										<select class="checkCustomer" style="width:100%" name="customer">
											<option value="<?php echo $s_cus['customer_id']; ?>"><?php echo $s_cus['customer']; ?></option>
										</select>
									</div>	
									<div class="col-sm-4">
										<select class="getSEAndDistReportees" style="width:100%" name="created_user">
											<option value="<?php echo $s_created_user['user_id']; ?>"><?php echo $s_created_user['cName']; ?></option>
										</select>
									</div>	
									<div class="col-sm-2">
										<button type="submit" name="searchAppLead" value="1" class="btn btn-success"><i class="fa fa-search"></i> Search</button>
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
								<th class="text-center"><strong>Created By</strong></th>
								<th class="text-center"><strong>Created Time</strong></th>
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
										<td class="text-center" style="width:13%;"><?php echo @$row['source'];?></td>
										<td style="width:20%;"><?php echo @$row['customer'];?></td>
										<td style="width:18%;"><?php echo @$row['contact'];?></td>
										<td style="width:15%;"><?php echo getUserName(@$row['user_id']);?></td>
										<td class="text-center" style="width:12%;"><?php echo @DateFormatAM($row['created_time']);?></td>
										<td class="text-center" style="width:15%;">
											<a class="btn btn-default" style="padding:3px 3px;" href="<?php echo SITE_URL;?>editAppLead/<?php echo @icrm_encode($row['lead_id']); ?>"><i class="fa fa-pencil"></i></a> 
											<?php if($row['role_id'] != 5) { ?>
											<a class="btn btn-success" style="padding:3px 3px;" href="<?php echo SITE_URL;?>approveLead/<?php echo @icrm_encode($row['lead_id']); ?>" onclick="return confirm('Are you sure you want to Approve?')"><i class="fa fa fa-thumbs-o-up"></i></a>
											<?php } ?>
											<a class="btn btn-danger" title="Activate" style="padding:3px 3px;" href="<?php echo SITE_URL;?>rejectLead/<?php echo @icrm_encode($row['lead_id']); ?>"  onclick="return confirm('Are you sure you want to Reject?')"><i class="fa fa fa-thumbs-o-down"></i></a>
										</td>
									</tr>
						<?php	}
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
//print_r($_SESSION['reportees']);
$this->load->view('commons/main_footer.php', $nestedView); ?>
