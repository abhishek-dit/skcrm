<?php
	$this->load->view('commons/main_template',$nestedView); 
	$role_id=$this->session->userdata('role_id');
?>
<div class="row"> 
	<div class="col-sm-12 col-md-12">
		<div class="block-flat">
			<table class="table table-bordered"></table>
			<div class="content">
				
				<form role="form" class="form-horizontal" method="post" action="<?php echo SITE_URL;?>demoDetails">
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<div class="col-sm-12">
									<div class="col-sm-2">
										<input type="text" name="location" placeholder="Location" value="<?php echo @$searchParams['location'];?>" id="location" class="form-control"  maxlength="100">
									</div>
									<div class="col-sm-2">
										<input type="text" name="city" placeholder="City" value="<?php echo @$searchParams['city'];?>" id="city" class="form-control"  maxlength="100">
									</div>
									<div class="col-sm-2">
										<input type="text" name="serialNumber" placeholder="Serial Number" value="<?php echo @$searchParams['serialNumber'];?>" id="serial_number" class="form-control" maxlength="20">
									</div>
									<div class="col-sm-2">
										<input type="text" name="branch" placeholder="Branch" value="<?php echo @$searchParams['branch'];?>" id="branch" class="form-control"  maxlength="20">
									</div>
									<div class="col-sm-4">
										<button type="submit" name="searchDemoProduct" value="searchDemoProduct" class="btn btn-success"><i class="fa fa-search"></i> </button>
										<?php if(in_array($role_id, allowed_download_roles()))
                            			{ ?>
										<button type="submit" name="downloadDemoProduct" value="downloadDemoProduct" formaction="<?php echo SITE_URL;?>downloadDemoDetails" class="btn btn-success"><i class="fa fa-cloud-download"></i> </button>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>	
					</div>
				</form>
				
				<div class="header"></div>
				<div class="table-responsive">
					<table class="table table-bordered hover">
						<thead>
							<tr>
								<th class="text-center"><strong>S.NO</strong></th>
								<th class="text-center"><strong>Location</strong></th>
								<th class="text-center"><strong>City</strong></th>
								<th class="text-center"><strong>Product</strong></th>
								<th class="text-center"><strong>Serial Number</strong></th>
								<!-- <th class="text-center"><strong>Region</strong></th>
								<th class="text-center"><strong>Branch</strong></th> -->
								<th class="text-center"><strong>Actions</strong></th>
							</tr>
						</thead>
						<tbody>
						<?php
							if(@$total_rows>0)
							{
								foreach($demoProductSearch as $row)
								{?>
									<tr>
										<td class="text-center"><?php echo @$sn;$sn++;?></td>
										<td class="text-center"><?php echo @$row['location'];?></td>
										<td class="text-center"><?php echo @$row['city'];?></td>
<!--										<td class="text-center"><?php //echo @$row['CategoryName'];?></td>
										<td class="text-center"><?php //echo @$row['GroupName'];?></td>
-->										<td><a style="color:#000000;" data-toggle="tooltip" href="#" data-original-title="Category: <?php echo @$row['CategoryName']; ?>, Group: <?php echo @$row['GroupName'];?>"><?php echo @$row['ProductName'];?></a></td>
										<td class="text-center"><?php echo @$row['serial_number'];?></td>
										<!-- <td class="text-center"><?php echo @$row['region'];?></td>
										<td class="text-center"><?php echo @$row['branch'];?></td> -->
										<td class="text-center">
											<a class="btn btn-primary demo" title="View Calendar" style="padding:3px 3px;" data-demo-product-id="<?php echo $row['demo_product_id']; ?>"><i class="fa fa-eye"></i></a>
											<a class="btn btn-primary" type="submit" title="Download Excel" style="padding:3px 3px;" data-demo-product-id="<?php echo $row['demo_product_id']; ?>" href="<?php echo SITE_URL;?>downloadDemoCalendarDetails/<?php echo @icrm_encode($row['demo_product_id']); ?>"><i class="fa fa-cloud-download"></i></a>
									</tr>
						<?php	}
							} else {
							?>	<tr><td colspan="8" align="center"><span class="label label-primary">No Records</span></td></tr>
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
<!-- Nifty Modal -->
<div class="col-md-6 col-sm-6" hidden>
	<div class="block-flat">
		<div class="content">
			<div class="spacer2 text-center">
				<button class="btn btn-primary btn-flat md-trigger" style="display:none;" id="calendar_modal" data-modal="form-primary">Basic Form</button>
			</div>
		</div>
	</div>
</div>
<div class="md-modal colored-header custom-width md-effect-9" id="form-primary">
    <div class="md-content">
      <div id='calendar'></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-flat md-close" data-dismiss="modal">Cancel</button>
      </div>
    </div>
</div>
<div class="md-overlay"></div>
<?php
	$this->load->view('commons/main_footer.php',$nestedView); 
?>
<script type="text/javascript">
$(document).ready(function(){
	$(".demo").click(function(){
    	var demo_product_id = $(this).data('demo-product-id');
    	$('#calendar').html('');
    	$('#calendar').fullCalendar({
		    header: {
				left: 'title',
				center: '',
				right: 'month,agendaWeek,agendaDay, today, prev,next',
		    },
		    height: 350,
		    editable: true,
		    eventRender: function(event, element) {
				$(element).tooltip({title: event.description,container: $("#calendar")});             
			},
		    events: "<?php echo SITE_URL;?>getDemoCalendar?demo_product_id="+demo_product_id,
		    editable: false
		});
		if(demo_product_id != '')
		$("#calendar_modal").click();
    });

	getAutocompleteData('location','demo_product_details','location');
	getAutocompleteData('serial_number','demo_product_details','serial_number');
	getAutocompleteData('branch','branch','name');
});
</script>