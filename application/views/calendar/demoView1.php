<?php
	$this->load->view('commons/main_template',$nestedView); 
?>

	<?php
		if(@$flg!='')
		{
			//$flg = @$this->global_functions->decode_icrm($_REQUEST['flg']);
			if($flg == 1)
			{
				if($val == 1)
				{
					$formHeading = 'Edit Demo Details';
				}
				else
				{
					$formHeading = 'Plan New Demo';
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
									<form class="form-horizontal" role="form" action="<?php echo SITE_URL; ?>demoAdd"  parsley-validate novalidate method="post">
										<input type="hidden" name="demo_id" value="<?php echo @$demoEdit[0]['demo_id']?>">
										<div class="form-group">
											<label for="inputLead" class="col-sm-3 control-label">Lead<span class="req-fld">*</span></label>
											<div class="col-sm-6">
												<?php $disabled = @$demoEdit[0]['lead_id']!=''?'disabled':'' ?>
												<?php echo form_dropdown('lead', $leads, @$demoEdit[0]['lead_id'],'class="form-control" id="lead" required '.$disabled.''); ?>
												<?php if($disabled=='disabled'){?>
													<input type="hidden" name="lead" value="<?php echo @$demoEdit[0]['lead_id']?>">
												<?php }?>
											</div>
										</div>
										<div class="form-group">
											<label for="inputOpportunity" class="col-sm-3 control-label">Opportunity<span class="req-fld">*</span></label>
											<div class="col-sm-6">
												<?php $disabled = @$demoEdit[0]['opportunity_id']!=''?'disabled':'' ?>
												<?php echo form_dropdown('opportunity', $opportunities, @$demoEdit[0]['opportunity_id'],'class="form-control" id="opportunity" required '.$disabled.''); ?>
												<?php if($disabled=='disabled'){?>
													<input type="hidden" name="opportunity" value="<?php echo @$demoEdit[0]['opportunity_id']?>">
												<?php }?>
											</div>
										</div>
										<div class="form-group">
											<label for="inputDemo" class="col-sm-3 control-label">Demo<span class="req-fld">*</span></label>
											<div class="col-sm-6">
												<?php $disabled = @$demoEdit[0]['demo_product_id']!=''?'disabled':'' ?>
												<?php echo form_dropdown('demo', $demos, @$demoEdit[0]['demo_product_id'],'class="form-control" id="demo" required '.$disabled.''); ?>
												<?php if($disabled=='disabled'){?>
													<input type="hidden" name="demo" value="<?php echo @$demoEdit[0]['demo_product_id']?>">
												<?php }?>
											</div>
										</div>
										<div class="form-group">
											<label for="inputStartDate" class="col-sm-3 control-label">Start Date<span class="req-fld">*</span></label>
											<div class="col-sm-6">
												<input type="text" required class="form-control" id="start_date" placeholder="Start Date" name="start_date" readonly  value="<?php echo @$demoEdit[0]['start_date']; ?>">
											</div>
										</div>
										<div class="form-group">
											<label for="inputEndDate" class="col-sm-3 control-label">End Date<span class="req-fld">*</span></label>
											<div class="col-sm-6">
												<input type="text" required class="form-control" id="end_date" placeholder="End Date" name="end_date" readonly  value="<?php echo @$demoEdit[0]['end_date']; ?>">
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-3 control-label">Remark</label>
											<div class="col-sm-6">
												<textarea class="form-control" id="remarks1" name="remarks1"><?php echo @$demoEdit[0]['remarks1']; ?></textarea>
											</div>
										</div>
										<div class="form-group">
											<div class="col-sm-offset-3 col-sm-10">
												<button class="btn btn-primary" type="submit" name="submitDemo" value="button"><i class="fa fa-check"></i> Submit</button>
												<a class="btn btn-danger" href="<?php echo SITE_URL;?>demo"><i class="fa fa-times"></i> Cancel</a>
											</div>
										</div>
									</form>
								</div>
							</div>				
						</div>
						<div class="col-md-6 col-sm-6" hidden>
							<div class="block-flat">
								<div class="content">
									<div class="spacer2 text-center">
										<button data-toggle="modal" data-target="#mod-success" type="button" id="calendar_modal" class="btn btn-success"><i class="fa fa-check"></i> Success</button>
										<!--<button class="btn btn-primary btn-flat md-trigger" style="display:none;" id="calendar_modal" data-modal="form-primary">Basic Form</button>-->
									</div>
								</div>
							</div>
						</div>
					</div><br>

					<?php
			}
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
				<div class="row">
					<form role="form" class="form-horizontal" method="post" action="<?php echo SITE_URL;?>demo">
						<div class="col-sm-12">
							<div class="col-sm-4">
                            	<button type="submit" name="downloadDemo" value="1" formaction="<?php echo SITE_URL;?>downloadDemo" class="btn btn-success"><i class="fa fa-cloud-download"></i> Download</button>
								<a href="<?php echo SITE_URL;?>planDemo" class="btn btn-success"><i class="fa fa-plus"></i> Plan Demo</a>
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
								<th class="text-center"><strong>Opportunity</strong></th>
								<th class="text-center"><strong>Demo</strong></th>
								<th class="text-center"><strong>Start Date</strong></th>
								<th class="text-center"><strong>End Date</strong></th>
								<th class="text-center"><strong>Actions</strong></th>
							</tr>
						</thead>
						<tbody>
						<?php
							if(@$total_rows>0)
							{
								foreach($demoSearch as $row)
								{?>
									<tr>
										<td class="text-center"><?php echo @$sn;$sn++;?></td>
										<td class="text-center"><?php echo @$row['CustomerName'];?></td>
										<td class="text-center"><?php echo @$row['opportunity'];?></td>
										<td class="text-center"><?php echo @$row['demo'];?></td>
										<td class="text-center"><?php echo @$row['start_date'];?></td>
										<td class="text-center"><?php echo @$row['end_date'];?></td>
										<td class="text-center">
											<a class="btn btn-default" style="padding:3px 3px;" href="<?php echo SITE_URL;?>editDemo/<?php echo @icrm_encode($row['demo_id']); ?>"><i class="fa fa-pencil"></i></a> 
											<?php
											if(@$row['status'] == 1)
											{
												?>
												<a class="btn btn-danger" style="padding:3px 3px;" href="<?php echo SITE_URL;?>deleteDemo/<?php echo @icrm_encode($row['demo_id']); ?>" onclick="return confirm('Are you sure you want to Delete?')"><i class="fa fa-trash-o"></i></a>
												<?php
											}
											else
											{
												?>
												<a class="btn btn-info" title="Activate" style="padding:3px 3px;" href="<?php echo SITE_URL;?>activateDemo/<?php echo @icrm_encode($row['demo_id']); ?>"  onclick="return confirm('Are you sure you want to Activate?')"><i class="fa fa-check"></i></a>
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
?>
<!-- Nifty Modal -->
<div class="modal fade" id="mod-success" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="md-content">
			<div class="modal-header">
				<h3>Selected Demo Product's Calender</h3>
				<button type="button" class="close md-close" data-dismiss="modal" aria-hidden="true">&times;</button>
			</div>    
			<div class="modal-body form">
				<div id='calendar'></div>
			</div>	
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-flat md-close" data-dismiss="modal">Ok</button>
			</div>
		</div>
	</div>	
</div>
<div class="md-overlay"></div>

<?php
	$this->load->view('commons/main_footer.php',$nestedView); 
?>
<script type="text/javascript">
$( document ).ready(function() {
   var start_date = $("#start_date").val();
   var end_date = $("#end_date").val();
   $("#start_date").datepicker({
        dateFormat: "yy-mm-dd",
		changeMonth: true,
  		changeYear: true,
       // minDate: 0,
        onSelect: function (date) {
           
            var date2 = $(this).datepicker('getDate');
            $('#end_date').datepicker('option', 'minDate', date2);
        }
    });
	$("#end_date").datepicker({
        dateFormat: "yy-mm-dd",
        changeMonth: true,
  		changeYear: true,
        onSelect: function (date) {
           
            var date2 = $(this).datepicker('getDate');
            $('#start_date').datepicker('option', 'maxDate', date2);
							
        }
    });

    $(document).on("change","#lead",function() {		
        var old_this = $(this);
        $(old_this).parents('form').find('#opportunity').html('<option value="">Select Opportunity</option>');
        $.ajax({
            type: "POST",
            url: "<?php echo SITE_URL;?>getOpportunity",
            data:'lead_id='+$(this).val(),
            beforeSend: function()
            {
            },
	        success: function(data){
	            $(old_this).parents('form').find('#opportunity').html(data);
	        }
        });
        $('#demo').html('<option value="">Select Demo</option>');
    });

    $(document).on("change","#opportunity",function() {		
        var old_this = $(this);
        $(old_this).parents('form').find('#demo').html('<option value="">Select Demo</option>');
        $.ajax({
            type: "POST",
            url: "<?php echo SITE_URL;?>getDemo",
            data:'opportunity_id='+$(this).val(),
            beforeSend: function()
            {
            },
	        success: function(data){
	            $(old_this).parents('form').find('#demo').html(data);
	        }
        });
    });
    
    $("#demo").change(function(){
    	var opportunity_id = $("#opportunity").val();
    	var demo_product_id = $(this).val();
    	$('#calendar').html('');
    	$('#calendar').fullCalendar({
		    header: {
				left: 'title',
				center: '',
				right: 'month,agendaWeek,agendaDay, today, prev,next',
		    },
		    height: 350,
		    editable: true,
		    events: "<?php echo SITE_URL;?>getDemoCalendar?opportunity_id="+opportunity_id+"&demo_product_id="+demo_product_id
		});
		if(demo_product_id != '')
		$("#calendar_modal").click();
    });
});
</script>