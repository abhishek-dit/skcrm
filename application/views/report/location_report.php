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
					<form role="form" class="form-horizontal" method="post" action="<?php echo SITE_URL;?>location_report">
						<div class="col-sm-12">
                            <!-- <div class="col-sm-2">                            
                                <select class="form-control users select2" style="width:100%" name="users">
                                    <option value="">Select Users</option>
                                    <?php
                                    // foreach ($users as $us) {
                                    //     echo '<option value="'.$us['user_id'].'">'.$us['first_name'].' ('.$us['employee_id'].')'.'</option>';
                                    // }
                                    ?>
                                </select>                        
                            </div> -->
                                <div class="col-sm-4">
									<select class="select2" style="width:100%" name="users">
									<option value="">Select Users</option>
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
                            <!-- <div class="col-sm-3">
                                <div class="input-group date datetime col-sm-10 col-xs-7" data-show-meridian="true" data-start-view="1" data-date="<?php echo date('Y-m-d').'T'.date('H:i:s').'Z';?>" data-date-format="yyyy-mm-dd  h:i" data-link-field="dtp_input1">
                                <input class="form-control" size="16"  type="text" name="startDate" value="<?php //echo @$searchParams['startDate']; ?>" readonly required  placeholder="Start Time" >
                                <span class="input-group-addon btn btn-primary"><span class="glyphicon glyphicon-th"></span></span>
                            </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="input-group date datetime col-sm-10 col-xs-7" data-show-meridian="true" data-start-view="1" data-date="<?php echo date('Y-m-d').'T'.date('H:i:s').'Z';?>" data-date-format="yyyy-mm-dd  h:i" data-link-field="dtp_input1">
                                <input class="form-control" size="16" type="text" name="endDate" value="<?php //echo @$searchParams['endDate']; ?>" readonly required  placeholder="End Time" >
                                <span class="input-group-addon btn btn-primary"><span class="glyphicon glyphicon-th"></span></span>
                            </div> -->
                            </div>
                            <div class="col-sm-2">
                                <button type="submit" name="searchlocation" value="1" class="btn btn-success"><i class="fa fa-search"></i></button>
                                <?php if(in_array($role_id, allowed_download_roles()))
                                        { ?>
                                <button type="submit" name="download_location_report" value="1" formaction="<?php echo SITE_URL;?>download_location_report" class="btn btn-success"><i class="fa fa-cloud-download"></i></button>
                                <?php } ?>
                                <a href="<?php echo SITE_URL.'location_report'; ?>" class="btn btn-success"><i class="fa fa-refresh"></i></a>
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
                                <th class="text-center"><strong>Sales Engineer</strong></th>
                                <th class="text-center"><strong>Date</strong></th>
                                <th class="text-center"><strong>Place</strong></th>
                                <th class="text-center"><strong>Street</strong></th>
                                <th class="text-center"><strong>City</strong></th>
                            </tr>
						</thead>
						<tbody>
						<?php
							if(@$total_rows>0)
							{
								foreach($locationSearch as $row)
								{?>
									<tr>
                                        <td class="text-center" width="5%"><?php echo @$sn;$sn++;?></td>
                                        <td class="text-center" width="10%"><?php echo @$row['lead_owner'];?></td>
                                        <td class="text-center" width="10%"><?php echo format_date($row['created_time'],'d-m-Y h:i a');?></td>
                                        <td class="text-center" width="10%"><?php echo @$row['street'];?></td>
                                        <td class="text-center" width="10%"><?php echo @$row['place'];?></td>
                                        <td class="text-center" width="10%"><?php echo @$row['city'];?></td>
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
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDJwBZyKfho2MpWqVgwtHNHQpkK4tOq9Gc&callback=initMap"></script>
