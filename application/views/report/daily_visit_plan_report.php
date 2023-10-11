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
					<form role="form" class="form-horizontal" method="post" action="<?php echo SITE_URL;?>daily_visit_plan_report">
						<div class="col-sm-12">
                                <!--<div class="col-sm-2">
                                    <input type="text" name="leadId" placeholder="Lead ID" value="<?php echo @$searchParams['leadId'];?>" id="leadId" class="form-control">
                                </div>-->
                                <div class="col-sm-2">
									<select class="checkCustomer" style="width:100%" name="customer">
										<option value="<?php echo $customer['customer_id']; ?>"><?php echo $customer['customer']; ?></option>
									</select>
			                	</div>
								<div class="col-sm-2">
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
                                <div class="col-sm-2">
									<select class="select2" name="purpose" >
										<option value="">Select Purpose</option>
													<?php
													foreach($purposeList as $purpose)
													{
														$selected = ($purpose['purpose_id']==$searchParams['purpose'])?'selected="selected"':'';
														echo '<option value="'.$purpose['purpose_id'].'" '.$selected.'>'.$purpose['name'].'</option>';
													}
													?>
									</select>
                                </div>
                                <div class="col-sm-2">
									<select class="select2" name="region" >
										<option value="">Select Region</option>
													<?php
													foreach($regionList as $region)
													{
														$selected = ($region['location_id']==$searchParams['region'])?'selected="selected"':'';
														echo '<option value="'.$region['location_id'].'" '.$selected.'>'.$region['location'].'</option>';
													}
													?>
									</select>
                                </div>
                            </div>&nbsp;
                            <div class="col-sm-12">
												<div class="col-sm-3">
                                                        <input type="text" required class="form-control" id="start_date" placeholder="Start Date" name="startDate" readonly  value="<?php echo @$searchParams['startDate']; ?>">
                                                </div>     
                                                <div class="col-sm-3">
                                                        <input type="text" required class="form-control" id="end_date" placeholder="End Date" name="endDate" readonly  value="<?php echo @$searchParams['endDate']; ?>">
                                                </div>
                                <!-- <div class="col-sm-3">
                                    <div class="input-group date datetime col-sm-10 col-xs-7" data-show-meridian="true" data-start-view="1" data-date="<?php echo date('Y-m-d').'T'.date('H:i:s').'Z';?>" data-date-format="yyyy-mm-dd  h:i" data-link-field="dtp_input1">
                                    <input class="form-control" size="16"  type="text" name="startDate" value="<?php echo @$searchParams['startDate']; ?>" readonly required  placeholder="Start Time" >
                                    <span class="input-group-addon btn btn-primary"><span class="glyphicon glyphicon-th"></span></span>
                                </div>
                                </div> -->
                                <!-- <div class="col-sm-3">
                                    <div class="input-group date datetime col-sm-10 col-xs-7" data-show-meridian="true" data-start-view="1" data-date="<?php echo date('Y-m-d').'T'.date('H:i:s').'Z';?>" data-date-format="yyyy-mm-dd  h:i" data-link-field="dtp_input1">
                                    <input class="form-control" size="16" type="text" name="endDate" value="<?php echo @$searchParams['endDate']; ?>" readonly required  placeholder="End Time" >
                                    <span class="input-group-addon btn btn-primary"><span class="glyphicon glyphicon-th"></span></span>
                                </div>
                                </div> -->
                                <div class="col-sm-2">
                                    <button type="submit" name="searchDailyVisitPlan" value="1" class="btn btn-success"><i class="fa fa-search"></i></button>
                                    <?php if(in_array($role_id, allowed_download_roles()))
                                            { ?>
                                    <button type="submit" name="daily_visit_plan_report_download" value="1" formaction="<?php echo SITE_URL;?>daily_visit_plan_report_download" class="btn btn-success"><i class="fa fa-cloud-download"></i></button>
                                    <?php } ?>
                                    <a href="<?php echo SITE_URL.'daily_visit_plan_report'; ?>" class="btn btn-success"><i class="fa fa-refresh"></i></a>
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
                                <th width= "5%"></th>
								<th class="text-center"><strong>S.NO</strong></th>
                                <!-- <th class="text-center"><strong>Lead Id</strong></th> -->
                                <th class="text-center"><strong>Sales Engineer Name</strong></th>
                                <!-- <th class="text-center"><strong>Contact Person</strong></th> -->
                                <th class="text-center"><strong>Branch</strong></th>
								<th class="text-center"><strong>Name</strong></th>
                                <th class="text-center"><strong>Purpose</strong></th>
								<th class="text-center"><strong>Start Date</strong></th>
								<th class="text-center"><strong>End Date</strong></th>
                                <!-- <th class="text-center"><strong>Lead Status</strong></th> -->
								<th class="text-center"><strong>Status</strong></th>
							</tr>
						</thead>
						<tbody>
						<?php
							if(@$total_rows>0)
							{
								foreach($visitSearch as $row)
								{?>
									<tr> 
									    <?php if($row['lead_id'] != null){ ?>
                                        <td class="text-center"><img src="<?php echo assets_url(); ?>images/plus.png" class="toggle-details" title='expand'></td>
										<?php } else { ?>
											<td> </td>
										<?php } ?>
                                        <td class="text-center" width="5%"><?php echo @$sn;$sn++;?></td>
                                        <td class="text-center" width="10%"><?php echo @$row['lead_owner'];?></td>
                                        <td class="text-center" width="10%"><?php echo @$row['branch'];?></td>
										<td class="text-center" width="10%"><?php echo @$row['name'];?></td>
										<td class="text-center" width="10%"><?php echo @$row['Purpose'];?></td>
										<td class="text-center" width="10%"><?php echo format_date($row['start_date'],'d-m-Y h:i a');?></td>
										<td class="text-center" width="10%"><?php echo @format_date($row['end_date'],'d-m-Y h:i a');?></td>
										<td class="text-center" width="10%"><?php echo @$row['status'];?></td>
									</tr>
									<?php if($row['lead_id'] != null) {?>
                                    <tr class="details">
                                        <td  colspan="9">
                                        <table class="table">
                                        	<thead>
                                                <th class="text-center"><strong>Lead Id</strong></th>
                                                <th class="text-center"><strong>Contact Person</strong></th>
                                                <th class="text-center"><strong>Lead Status</strong></th>
                                            </thead>
                                            <tbody>
                                                <td class="text-center" width="10%"><?php echo @$row['lead_number'];?></td>                                        
                                                <td class="text-center" width="10%"><?php echo @$row['contact'];?></td>
                                                <td class="text-center" width="8%"><?php echo @$row['leadStatus'];?></td>

                                            </tbody>
                                        </table>    
                                        
                                        </td>
                                    </tr>
									<?php }?>
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
<script type="text/javascript">
$( document ).ready(function() {
   var start_date = $("#start_date").val();
   var end_date = $("#end_date").val();
   var min_date = (start_date=='')?'-14d':new Date(new Date(start_date) - 12096e5);
   
   if($("#is_expired").val()!=1)
   {
	   $("#start_date").datepicker({
	        dateFormat: "yy-mm-dd",
			changeMonth: true,
	  		changeYear: true,
	       	minDate: min_date,
	        onSelect: function (date) {
	           
	            var date2 = $(this).datepicker('getDate');
	            $('#end_date').datepicker('option', 'minDate', date2);
	        }
	    });
		$("#end_date").datepicker({
	        dateFormat: "yy-mm-dd",
	        changeMonth: true,
	  		changeYear: true,
	  		minDate: min_date,
	        onSelect: function (date) {
	           
	            var date2 = $(this).datepicker('getDate');
	            $('#start_date').datepicker('option', 'maxDate', date2);
	        }
	    });
	}

	$("#startDate").datepicker({
	    dateFormat: "yy-mm-dd",
		changeMonth: true,
		changeYear: true,
	    onSelect: function (date) {
	       
	        var date2 = $(this).datepicker('getDate');
	        $('#endDate').datepicker('option', 'minDate', date2);
	    }
	});

	$("#endDate").datepicker({
	    dateFormat: "yy-mm-dd",
	    changeMonth: true,
		changeYear: true,
	    onSelect: function (date) {
	       
	        var date2 = $(this).datepicker('getDate');
	        $('#startDate').datepicker('option', 'maxDate', date2);
	    }
	});

	select2Ajax('checkCustomer', 'getCustomer');
	


    $('.datetime').datetimepicker({ minDate: new Date() });     

});
var ASSET_URL = '<?php echo assets_url()?>'
$('.details').hide();
  $(document).on('click',".toggle-details",function () { 
    var row=$(this).closest('tr');
    var next=row.next();
    $('.details').not(next).hide();
    $('.toggle-details').not(this).attr('src',ASSET_URL+'images/plus.png');
    next.toggle();
    if (next.is(':hidden')) {
      $(this).attr('src',ASSET_URL+'images/plus.png');
      $(this).attr('title','expand');
    } else {
      $(this).attr('src',ASSET_URL+'images/minus.png');
      $(this).attr('title','collapse');
    }
  });
</script>