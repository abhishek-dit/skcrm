<?php $this->load->view('commons/main_template', $nestedView);
echo $this->session->flashdata('response'); 
//$role_id = $this->session->userdata('role_id');
//$checkRole = ($role_id == 4 || $role_id == 5)?1:0;

?>

<div class="row"> 
	<div class="col-sm-12 col-md-12">
		<div class="block-flat" style="margin-bottom:5px;">
			<div class="content">
						<form class="form-horizontal" role="form" action="<?php echo SITE_URL.'assignInactiveUserLeads';?>" method="post">	
							<div class="form-group">
								<div class="col-sm-12">
									<div class="col-sm-3">
											<select class="getInactiveUsersWithOpenLeads" style="width:100%" name="created_user">
												<option value="<?php echo $s_created_user['user_id']; ?>"><?php echo $s_created_user['cName']; ?></option>
											</select>
									</div>	
									<div class="col-sm-2">
											<select class="select2 s_region" style="width:100%" name="s_region">
												<option value="">Select Region</option>
												<?php
												if($regions)
												{
													foreach ($regions as $row1) {
														$selected = ($row1['location_id']==@$searchParams['s_region'])?'selected':'';
														echo '<option value="'.$row1['location_id'].'" '.$selected.'>'.$row1['location'].'</option>';
													}
												}
												?>
											</select>
									</div>
									<div class="col-sm-2">
											<select class="select2 s_state" style="width:100%" name="s_state">
												<option value="">Select State</option>
												<?php
												if($states)
												{
													foreach ($states as $srow) {
														$selected = ($srow['location_id']==@$searchParams['s_state'])?'selected':'';
														echo '<option value="'.$srow['location_id'].'" '.$selected.'>'.$srow['location'].'</option>';
													}
												}
												?>
											</select>
									</div>
									<div class="col-sm-2">
											<select class="select2 s_district" style="width:100%" name="s_district">
												<option value="">Select District</option>
												<?php
												if($districts)
												{
													foreach ($districts as $srow) {
														$selected = ($srow['location_id']==@$searchParams['s_district'])?'selected':'';
														echo '<option value="'.$srow['location_id'].'" '.$selected.'>'.$srow['location'].'</option>';
													}
												}
												?>
											</select>
									</div>
									<div class="col-sm-2">
											<select class="select2 s_city" style="width:100%" name="s_city">
												<option value="">Select City</option>
												<?php
												if($cities)
												{
													foreach ($cities as $srow) {
														$selected = ($srow['location_id']==@$searchParams['s_city'])?'selected':'';
														echo '<option value="'.$srow['location_id'].'" '.$selected.'>'.$srow['location'].'</option>';
													}
												}
												?>
											</select>
									</div>
									<div class="col-sm-1">
										<button type="submit" name="searchInactiveUserLeads" value="1" class="btn btn-success"><i class="fa fa-search"></i></button>
										<!-- <a href="<?php echo SITE_URL.'assignInactiveUserLeads'?>" class="btn btn-success"><i class="fa fa-refresh"></i></a> -->
									</div>
								</div>
							</div>
						</form>
			</div>
		</div>
		<div class="block-flat">
			<div class="content">
				
				<form id="assignInactiveUserLeads_frm" class="form-horizontal" role="form" action="<?php echo SITE_URL.'submit_assignInactiveUserLeads';?>" method="post">	
							<div class="form-group">
								<div class="col-sm-12">
								<input type="hidden" name="isError" id="isError" value="0">		
									<div class="col-sm-3">
											<select class="getActiveUsersToAssignLeads" required style="width:100%" id="assign_user" name="assign_user">
												<option value="">Select User</option>
											</select>
									</div>
									<div class="col-sm-3">
										<button type="button" id="submit_assignLeads" name="submit_assignLeads" value="1" class="btn btn-success">Submit</button>
										<a href="<?php echo SITE_URL.'assignInactiveUserLeads'?>" class="btn btn-success"><i class="fa fa-refresh"></i>&nbsp;</a>
									</div>
									<div class="col-sm-6">
									<span id="validating_leadLocations" class="hidden"><i class="fa fa-spinner fa-spin"></i> Checking lead customer location is under selected user</span>
									</div>
								</div>
							</div>
							
					
				<div class="table-responsive">
					<table class="table table-bordered hover">
						<thead>
							<tr>
								<th class="text-center"><input type="checkbox" name="checkall" value="1" id="checkall_leads"></th>
								<th class="text-center"><strong>Lead ID</strong></th>
								<th class="text-center"><strong>Lead User</strong></th>
								<th class="text-center"><strong>Customer</strong></th>
								<th class="text-center"><strong>Contact Person</strong></th>
								<th class="text-center"><strong>Status</strong></th>
							</tr>
						</thead>
						<tbody>
						<?php
							
							if(@$total_rows>0)
							{
								foreach($searchResults as $row)
								{?>
									<tr>
										<td class="text-center" style="width:3%;">
										<input type="checkbox" name="lead[]" value="<?php echo @$row['lead_id'];?>" class="chk_lead">
										<input type="hidden" name="lead_customer_location[<?php echo @$row['lead_id'];?>]" value="<?php echo @$row['location_id'];?>">
										<input type="hidden" name="lead_user[<?php echo @$row['lead_id'];?>]" value="<?php echo @$row['user_id'];?>">
										<input type="hidden" name="st[<?php echo @$row['lead_id'];?>]" value="<?php echo @$row['status'];?>">
										</td>
										<td class="text-center" style="width:7%;"><?php echo @$row['lead_id'];?></td>
										<td class="text-center" style="width:20%;"><?php echo @$row['user'];?></td>
										<td style="width:20%;"><?php echo @$row['customer'];?></td>
										<td style="width:20%;"><?php echo @$row['contact'];?></td>
										<td class="text-center" style="width:30%;"><?php echo @leadStatusBar($row['status']);?></td>
									</tr>
						<?php	}
							} else {
								
							?>	<tr><td colspan="6" align="center"><span class="label label-primary">No Records</span></td></tr>
					<?php 	} ?>
						</tbody>
					</table>
				</div>
				</form>
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
<script type="text/javascript">
	
$(document).on('change', ".s_region", function () {
    var location_id = $(this).val();
    var territory = 'State';
    if(location_id != "")
    {
        var data = 'location_id=' + location_id + '&territory='+territory;
        //alert(data);
        $.ajax({
            type: "POST",
            url: SITE_URL + 'getChilds',
            data: data,
            cache: false,
            success: function (html) {
                $(".s_state").html(html);
            }
        });
    }
    else
    {
        $('.s_state').html('<option value="">Select State</option>');
        
    }
    $('.s_district').html('<option value="">Select District</option>');
    $('.s_city').html('<option value="">Select City</option>');
});

$(document).on('change', ".s_state", function () {
    var location_id = $(this).val();
    var territory = 'District';
    if(location_id != "")
    {
        var data = 'location_id=' + location_id + '&territory='+territory;
        //alert(data);
        $.ajax({
            type: "POST",
            url: SITE_URL + 'getChilds',
            data: data,
            cache: false,
            success: function (html) {
                $(".s_district").html(html);
            }
        });
    }
    else
    {
        $('.s_district').html('<option value="">Select District</option>');
        
    }
    $('.s_city').html('<option value="">Select City</option>');
});

$(document).on('change', ".s_district", function () {
    var location_id = $(this).val();
    var territory = 'City';
    if(location_id != "")
    {
        var data = 'location_id=' + location_id + '&territory='+territory;
        //alert(data);
        $.ajax({
            type: "POST",
            url: SITE_URL + 'getChilds',
            data: data,
            cache: false,
            success: function (html) {
                $(".s_city").html(html);
            }
        });
    }
    else
    {
        $('.s_city').html('<option value="">Select City</option>');
    }
});
</script>