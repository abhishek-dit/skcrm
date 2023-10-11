<?php
	$this->load->view('commons/main_template',$nestedView); 
	$role_id=$this->session->userdata('role_id');
?>

<?php
if(@$displayList==1) {
?>

<div class="row"> 
	<div class="col-sm-12 col-md-12">
		<div class="block-flat">
			<table class="table table-bordered"></table>
			<div class="content">
				<div class="row">
					<form role="form" class="form-horizontal" method="post" action="<?php echo SITE_URL;?>demo_report">
						    <div class="col-sm-12">
                                <div class="col-sm-3">
									<select class="select2" style="width:100%" name="users">
									<option value="">Select Employee</option>
									<?php
									foreach ($users as $us) {
										$selected =  ($us['user_id']==@$searchParams['users'])?'selected="selected"':'';
										echo '<option value="'.$us['user_id'].'"'.$selected.'>'.$us['first_name'].' ('.$us['employee_id'].')'.'</option>';
									}
									?>
									</select>
								</div>
                                <div class="col-sm-3">
                                    <input type="text" required class="form-control" id="start_date" placeholder="Start Date" name="startDate" readonly  value="<?php echo @$searchParams['startDate']; ?>">
                                </div>     
                                <div class="col-sm-3">
                                    <input type="text" required class="form-control" id="end_date" placeholder="End Date" name="endDate" readonly  value="<?php echo @$searchParams['endDate']; ?>">
                                </div>
                                 <div class="col-sm-3">
                                    <button type="submit" name="searchdemo" value="1" class="btn btn-success"><i class="fa fa-search"></i></button>
                                <?php if(in_array($role_id, allowed_download_roles()))
                                        { ?>
                                    <button type="submit" name="download_demo_report" value="1" formaction="<?php echo SITE_URL;?>download_demo_report" class="btn btn-success"><i class="fa fa-cloud-download"></i></button>
                                <?php } ?>
                                    <a href="<?php echo SITE_URL.'demo_report'; ?>" class="btn btn-success"><i class="fa fa-refresh"></i></a>
                            </div>
                        </div>
                        </div>
					</form>
				</div>
				<div class="header"></div>
				<div class="table-responsive">
					<table class="table table-bordered hover">
						<thead>
							<tr>
								<th class="text-center"><strong>S.NO</strong></th>
								<th class="text-center"><strong>Demo Was Created Date</strong></th>
                                <th class="text-center"><strong>Employee Name</strong></th>
                                <th class="text-center"><strong>PS#</strong></th>
                                <th class="text-center"><strong>Region</strong></th>
                                <th class="text-center"><strong>Location/City</strong></th>
                                <th class="text-center"><strong>Lead ID</strong></th>
                                <th class="text-center"><strong>Opportunity ID</strong></th>
                                <th class="text-center"><strong>Demo Machine</strong></th>
                                <th class="text-center"><strong>Start Date</strong></th>
                                <th class="text-center"><strong>End Date</strong></th>
                            </tr>
						</thead>
						<tbody>
						<?php
							if(@$total_rows>0)
							{
								foreach($demoSearch as $row)
								{?>
									<tr>
                                        <td class="text-center" width="1%"><?php echo @$sn;$sn++;?></td>
                                        <td class="text-center" width="5%"><?php echo format_date($row['created_time'],'d-m-Y h:i a');?></td>
                                        <td class="text-center" width="5%"><?php echo @$row['employeename'];?></td>
                                        <td class="text-center" width="5%"><?php echo @$row['ps'];?></td>
                                        <td class="text-center" width="5%"><?php echo @$row['location'];?></td>
                                        <td class="text-center" width="5%"><?php echo @$row['city'];?></td>
                                        <td class="text-center" width="5%"><?php echo @$row['lead_number'];?></td>
                                        <td class="text-center" width="10%"><?php echo @$row['opportunity'];?></td>
                                        <td class="text-center" width="10%"><?php echo @$row['demo'];?></td>
                                        <td class="text-center" width="5%"><?php echo format_date($row['start_date'],'d-m-Y h:i a');?></td>
                                        <td class="text-center" width="5%"><?php echo format_date($row['end_date'],'d-m-Y h:i a');?></td>
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
?>
	<div class="md-overlay"></div>
	<?php
	$this->load->view('commons/main_footer.php',$nestedView); 
?>