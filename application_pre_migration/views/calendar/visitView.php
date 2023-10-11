<?php
	$this->load->view('commons/main_template',$nestedView); 
	$role_id=$this->session->userdata('role_id');
?>

	<?php
		if(@$flg!='')
		{
			//$flg = @$this->global_functions->decode_icrm($_REQUEST['flg']);
			
			if($flg == 1)
			{
				if($val == 1)
				{
					$formHeading = 'Edit Visit Details';
				}
				else
				{
					$formHeading = 'Plan New Visit';
				}
				?>
					<div class="row"> 
						<div class="col-sm-12 col-md-12">
							<?php echo $this->session->flashdata('error'); ?>
							<div class="block-flat">
								<div class="header">							
									<h4><?php echo $formHeading;?></h4>
								</div>
								<div class="content">
									<form class="form-horizontal" role="form" action="<?php echo SITE_URL; ?>visitAdd"  parsley-validate novalidate method="post">
										<input type="hidden" name="visit_id" value="<?php echo @$visitEdit[0]['visit_id']?>">
										<div class="form-group">
											<label for="inputLead" class="col-sm-3 control-label">Lead<span class="req-fld">*</span></label>
											<div class="col-sm-6">
												<?php $readonly = @$visitEdit[0]['lead_id']!=''?1:0; 
												if(!$readonly) {
												 echo form_dropdown('lead', $leads, @$visitEdit[0]['lead_id'],'class="form-control" id="lead" required ');
												}
												else { 
												 echo form_dropdown('lead', $leads, @$visitEdit[0]['lead_id'],'class="form-control" id="lead" disabled '); ?>
												<input type="hidden" name="lead" value="<?php echo @$visitEdit[0]['lead_id']; ?>">
												<?php } ?>
											</div>
										</div>
										<div class="form-group">
											<label for="inputPuopose" class="col-sm-3 control-label">Purpose<span class="req-fld">*</span></label>
											<div class="col-sm-6">
												<?php echo form_dropdown('purpose', $purpose, @$visitEdit[0]['purpose_id'],'class="form-control" id="purpose" required '); ?>
											</div>
										</div>
										<div class="form-group">
											<label for="inputStartDate" class="col-sm-3 control-label">Start Time<span class="req-fld">*</span></label>
											<div class="col-sm-6">
								                  <div class="input-group date datetime col-sm-6 col-xs-7" data-show-meridian="true" data-start-view="1" data-date="<?php echo date('Y-m-d').'T'.date('H:i:s').'Z';?>" data-date-format="yyyy-mm-dd  h:i" data-link-field="dtp_input1">
								                    <input class="form-control" size="16" type="text" name="start_date" value="<?php echo @$visitEdit[0]['start_date']; ?>" readonly required  placeholder="Start Time" >
								                    <span class="input-group-addon btn btn-primary"><span class="glyphicon glyphicon-th"></span></span>
								                  </div>									
								            </div>
										</div>
										<div class="form-group">
											<label for="inputEndDate" class="col-sm-3  control-label">End Time<span class="req-fld">*</span></label>
											<div class="col-sm-6">
								                  <div class="input-group date datetime col-sm-6 col-xs-7" data-show-meridian="true" data-start-view="1" data-date="<?php echo date('Y-m-d').'T'.date('H:i:s').'Z';?>" data-date-format="yyyy-mm-dd  h:i" data-link-field="dtp_input1">
								                    <input class="form-control" size="16" type="text" name="end_date" value="<?php echo @$visitEdit[0]['end_date']; ?>" readonly required  placeholder="End Time" >
								                    <span class="input-group-addon btn btn-primary"><span class="glyphicon glyphicon-th"></span></span>
								                  </div>									
								            </div>
										</div>
										<div class="form-group">
											<label class="col-sm-3 control-label">Remark</label>
											<div class="col-sm-6">
												<textarea class="form-control" id="remarks1" name="remarks1"><?php echo @$visitEdit[0]['remarks1']; ?></textarea>
											</div>
										</div>
										<div class="form-group">
											<div class="col-sm-offset-3 col-sm-10">
												<button class="btn btn-primary" type="submit" name="submitVisit" value="button"><i class="fa fa-check"></i> Submit</button>
												<a class="btn btn-danger" href="<?php echo SITE_URL;?>visit"><i class="fa fa-times"></i> Cancel</a>
											</div>
										</div>
										<input type="hidden" id="is_expired" value="<?php echo @$visitEdit[0]['is_expired']; ?>" >
									</form>
								</div>
							</div>				
						</div>
					</div><br>

					<?php
			}
		}
		echo $this->session->flashdata('response');
		echo $this->session->flashdata('activate_error');
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
					<form role="form" class="form-horizontal" method="post" action="<?php echo SITE_URL;?>visit">
						<div class="col-sm-12">
							<div class="col-sm-2">
								<input type="text" name="leadId" placeholder="Lead ID" value="<?php echo @$searchParams['leadId'];?>" id="leadId" class="form-control">
							</div>
							<div class="col-sm-2">
			                    <select class="checkCustomer" style="width:100%" name="customer">
			                     	<option value="<?php echo $customer['customer_id']; ?>"><?php echo $customer['customer']; ?></option>
			                    </select>
			                </div>
			                <div class="col-sm-3">
				                <div class="input-group date datetime col-sm-10 col-xs-7" data-show-meridian="true" data-start-view="1" data-date="<?php echo date('Y-m-d').'T'.date('H:i:s').'Z';?>" data-date-format="yyyy-mm-dd  h:i" data-link-field="dtp_input1">
			                    <input class="form-control" size="16" type="text" name="startDate" value="<?php echo @$searchParams['startDate']; ?>" readonly required  placeholder="Start Time" >
			                    <span class="input-group-addon btn btn-primary"><span class="glyphicon glyphicon-th"></span></span>
			                  </div>
			                </div>
							<div class="col-sm-3">
								<div class="input-group date datetime col-sm-10 col-xs-7" data-show-meridian="true" data-start-view="1" data-date="<?php echo date('Y-m-d').'T'.date('H:i:s').'Z';?>" data-date-format="yyyy-mm-dd  h:i" data-link-field="dtp_input1">
			                    <input class="form-control" size="16" type="text" name="endDate" value="<?php echo @$searchParams['endDate']; ?>" readonly required  placeholder="End Time" >
			                    <span class="input-group-addon btn btn-primary"><span class="glyphicon glyphicon-th"></span></span>
			                  </div>
							</div>
							<div class="col-sm-2">
								<button type="submit" name="searchVisit" value="1" class="btn btn-success"><i class="fa fa-search"></i></button>
								<?php if(in_array($role_id, allowed_download_roles()))
                            			{ ?>
                            	<button type="submit" name="downloadVisit" value="1" formaction="<?php echo SITE_URL;?>downloadVisit" class="btn btn-success"><i class="fa fa-cloud-download"></i></button>
                            	<?php } ?>
								<a href="<?php echo SITE_URL;?>planVisit" class="btn btn-success"><i class="fa fa-plus"></i></a>
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
								<th class="text-center"><strong>Customer Name</strong></th>
								<th class="text-center"><strong>Purpose</strong></th>
								<th class="text-center"><strong>Start Date</strong></th>
								<th class="text-center"><strong>End Date</strong></th>
								<th class="text-center"><strong>Actions</strong></th>
							</tr>
						</thead>
						<tbody>
						<?php
							if(@$total_rows>0)
							{
								foreach($visitSearch as $row)
								{?>
									<tr>
										<td class="text-center" width="5%"><?php echo @$sn;$sn++;?></td>
										<td class="text-center" width="40%"><?php echo @$row['CustomerName'];?></td>
										<td class="text-center" width="15%"><?php echo @$row['Purpose'];?></td>
										<td class="text-center" width="15%"><?php echo format_date($row['start_date'],'d-m-Y h:i a');?></td>
										<td class="text-center" width="15%"><?php echo @format_date($row['end_date'],'d-m-Y h:i a');?></td>
										<td class="text-center" width="10%">
											<a class="btn btn-default" style="padding:3px 3px;" href="<?php echo SITE_URL;?>editVisit/<?php echo @icrm_encode($row['visit_id']); ?>"><i class="fa fa-pencil"></i></a> 
											<?php
											$start_date_time = strtotime(@$row['start_date']);
											$cur_time = strtotime(date('Y-m-d'));
											if($cur_time>$start_date_time){
												$pop_id = 'visit_modal'.@$row['visit_id'];
												$btn_cls = (@$row['remarks2']!='')?'btn-primary':'btn-default';
												?>
												<button title="Update visit feedback" class="btn <?php echo $btn_cls;?> btn-flat md-trigger" data-modal="<?php echo $pop_id;?>" style="padding:3px 3px;"><i class="fa fa-paperclip"></i></button>

												<?php
											}
											if(@$row['status'] == 1)
											{
												if(@$row['is_expired']==0)
												{
												?>
													<a class="btn btn-danger" style="padding:3px 3px;" href="<?php echo SITE_URL;?>deleteVisit/<?php echo @icrm_encode($row['visit_id']); ?>" onclick="return confirm('Are you sure you want to Delete?')"><i class="fa fa-trash-o"></i></a>			
												<?php }
											}
											else
											{
												?>
												<a class="btn btn-info" title="Activate" style="padding:3px 3px;" href="<?php echo SITE_URL;?>activateVisit/<?php echo @icrm_encode($row['visit_id']); ?>"  onclick="return confirm('Are you sure you want to Activate?')"><i class="fa fa-check"></i></a>
												<?php
											}
											?>
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
}	
if(@$total_rows>0)
{
	foreach($visitSearch as $row)
	{
		$start_date_time = strtotime(@$row['start_date']);
		$cur_time = strtotime(date('Y-m-d'));
		if($cur_time>$start_date_time){
			$pop_id = 'visit_modal'.@$row['visit_id'];
			include('modals/visitUpdate_modal.php');
		}
	}
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

});
</script>