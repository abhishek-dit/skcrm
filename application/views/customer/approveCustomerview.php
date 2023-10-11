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
									<div class="col-sm-4">
                                        <input type="text" name="s_customerName" maxlength="100" placeholder="Name" value="<?php echo @$search_data['customerName']; ?>" id="companyName" class="form-control">
                                    </div>	
                                    <div class="col-sm-4">
                                        <select style="width:100%" name="s_location" class="checkLocation">
                                            <option value="<?php echo $s_loc['location_id']; ?>"><?php echo $s_loc['location']; ?></option>
                                        </select>
                                    </div>
									<div class="col-sm-4">
										<button type="submit" name="searchcustomer" value="1" class="btn btn-success"><i class="fa fa-search"></i> Search</button>
										<a href="<?php echo SITE_URL; ?>approveCustomers" title="Reset" class="btn btn-primary"><i class="fa fa-refresh"></i> Refresh</a>
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
								<th class="text-center"><strong>S.NO</strong></th>
								<th class="text-center"><strong>Customer Name</strong></th>
								<th class="text-center"><strong>Sales Engineer</strong></th>
								<th class="text-center"><strong>Telephone</strong></th>
								<th class="text-center"><strong>Mobile</strong></th>
								<th class="text-center"><strong>Date</strong></th>
								<th class="text-center"><strong>Location</strong></th>
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
										<td class="text-center" style="width:2%;"><?php echo @$sn++;?></td>
										<td class="text-center" style="width:20%;"><?php echo @$row['name'];?></td>
										<td class="text-center" style="width:18%;"><?php echo getUserName(@$row['created_by']);?></td>
										<td class="text-center" style="width:8%;"><?php echo @$row['telephone'];?></td>
										<td class="text-center" style="width:8%;"><?php echo @$row['mobile'];?></td>
										<td class="text-center" style="width:10%;"><?php echo @DateFormat($row['created_time']);?></td>
										<td class="text-center" style="width:10%;"><?php echo @$row['location'];?></td>
										<td class="text-center" style="width:20%;">
											<a class="btn btn-default" style="padding:3px 3px;" href="<?php echo SITE_URL; ?>editCustomer/<?php echo @icrm_encode($row['customer_id']); ?>"><i class="fa fa-pencil"></i></a> 
											<a class="btn btn-danger" style="padding:3px 3px;" href="<?php echo SITE_URL; ?>deleteCustomer/<?php echo @icrm_encode($row['customer_id']); ?>" onclick="return confirm('Are you sure you want to Delete?')"><i class="fa fa-trash-o"></i></a>
											<a class="btn btn-success" title="Approve" style="padding:3px 3px;" href="<?php echo SITE_URL;?>approveCustomer/<?php echo @icrm_encode($row['customer_id']); ?>" onclick="return confirm('Are you sure you want to Approve?')"><i class="fa fa fa-thumbs-o-up"></i></a>
											<a class="btn btn-warning" title="Reject" style="padding:3px 3px;" href="<?php echo SITE_URL;?>rejectCustomer/<?php echo @icrm_encode($row['customer_id']); ?>"  onclick="return confirm('Are you sure you want to Reject?')"><i class="fa fa fa-thumbs-o-down"></i></a>
											
										</td>
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
//print_r($_SESSION['reportees']);
$this->load->view('commons/main_footer.php', $nestedView); ?>
