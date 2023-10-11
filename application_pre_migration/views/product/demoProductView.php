<?php
	$this->load->view('commons/main_template',$nestedView); 
?>

	<?php
		if(@$flg != '')
		{
			//$flg = @$this->global_functions->decode_icrm($_REQUEST['flg']);
			if($flg == 1)
			{
				if($val == 1)
				{
					$formHeading = 'Edit Demo Product Details';
				}
				else
				{
					$formHeading = 'Add New Demo Product';
				}
				$region_id_js = (@$demoProductEdit[0]['region_id'] == '')?0:@$demoProductEdit[0]['region_id'];
				?>
					<div class="row"> 
						<div class="col-sm-12 col-md-12">
							<div class="block-flat">
								<div class="header">							
									<h4><?php echo $formHeading;?></h4>
								</div>
								<div class="content">
									<form class="form-horizontal" role="form" action="<?php echo SITE_URL; ?>demoProductAdd"  parsley-validate novalidate method="post">
										<input type="hidden" name="demo_product_id" value="<?php echo @$demoProductEdit[0]['demo_product_id']?>">
										<div class="form-group">
											<label for="inputCategoryName" class="col-sm-3 control-label">Product Category <span class="req-fld">*</span></label>
				                            <div class="col-sm-6">
												<?php echo form_dropdown('category', $categories, @$demoProductEdit[0]['category_id'],'class="select2 category" required'); ?>
											</div>
										</div>
										<div class="form-group">
											<label for="inputGroupName" class="col-sm-3 control-label">Segment <span class="req-fld">*</span></label>
											<div class="col-sm-6">
												<?php echo form_dropdown('group', $groups, @$demoProductEdit[0]['group_id'],'class="select2 group" required'); ?>
											</div>
										</div>
										<div class="form-group">
											<label for="inputProductName" class="col-sm-3 control-label">Product Name <span class="req-fld">*</span></label>
											<div class="col-sm-6">
												<?php echo form_dropdown('product', $products, @$demoProductEdit[0]['product_id'],'class="select2 product" required'); ?>
											</div>
										</div>
										<div class="form-group">
											<label for="inputProductName" class="col-sm-3 control-label">Region <span class="req-fld">*</span></label>
											<div class="col-sm-6">
												<?php echo form_dropdown('region', $regions, @$demoProductEdit[0]['region_id'],'class="select2 region" required'); ?>
											</div>
										</div>
										<div class="form-group">
											<label for="inputProductName" class="col-sm-3 control-label">Branch <span class="req-fld">*</span></label>
											<div class="col-sm-6">
												<?php echo form_dropdown('branch_id', $branch, @$demoProductEdit[0]['branch_id'],'class="select2 branch" required'); ?>
											</div>
										</div>
										<div class="form-group">
											<label for="inputLocation" class="col-sm-3 control-label">City <span class="req-fld">*</span></label>
											<div class="col-sm-6">
												<?php 
                                                                                                    if (@$demoProductEdit[0]['city_id'] != '') {
                                                                                                        $city_id = @$demoProductEdit[0]['city_id'];
                                                                                                        $cityName = @$demoProductEdit[0]['cityName'];
                                                                                                    } else {
                                                                                                        $city_id = "";
                                                                                                        $cityName = "Select City";
                                                                                                    }

												?>
												<select name="city_id" style="width:100%" class="select2city" required>
													<option value="<?php echo @$city_id; ?>"><?php echo @$cityName ?></option>
												</select>
											</div>
										</div>
										<div class="form-group">
											<label for="inputLocation" class="col-sm-3 control-label">Location <span class="req-fld">*</span></label>
											<div class="col-sm-6">
                                                                                            <input type="text" required class="form-control" id="location" maxlength="100" placeholder="Location" name="location" value="<?php echo @$demoProductEdit[0]['location']; ?>">
											</div>
										</div>
										<div class="form-group">
											<label for="inputSerialNumber" class="col-sm-3 control-label">Serial Number <span class="req-fld">*</span></label>
											<div class="col-sm-6">
												<input type="text" required class="form-control" id="serialNumber"   maxlength="80"  placeholder="Serial Number" name="serialNumber" value="<?php echo @$demoProductEdit[0]['serial_number']; ?>">
											</div>
										</div>
										<div class="form-group">
											<div class="col-sm-offset-3 col-sm-10">
												<button class="btn btn-primary" type="submit" name="submitDemoProduct" value="button"><i class="fa fa-check"></i> Submit</button>
												<a class="btn btn-danger" href="<?php echo SITE_URL;?>demoProduct"><i class="fa fa-times"></i> Cancel</a>
											</div>
										</div>
									</form>
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
				
				<form role="form" class="form-horizontal" method="post" action="<?php echo SITE_URL;?>demoProduct">
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<div class="col-sm-12">
									<label class="col-sm-1 control-label">Category</label>
		                            <div class="col-sm-2">
										<?php echo form_dropdown('category', $categories, @$searchParams['category_id'],'class="select2 category"'); ?>
									</div>
									<label class="col-sm-1 control-label">Segment</label>
		                            <div class="col-sm-2">
										<?php echo form_dropdown('group', $groups, @$searchParams['group_id'],'class="select2 group"'); ?>
									</div>
									<label class="col-sm-1 control-label">Product</label>
		                            <div class="col-sm-4">
										<?php echo form_dropdown('product', $products, @$searchParams['product_id'],'class="select2 product"'); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<div class="col-sm-12">
									<label class="col-sm-1 control-label">Location</label>
									<div class="col-sm-2">
                                        <input type="text" name="location" placeholder="Location" value="<?php echo @$searchParams['location'];?>" id="location" class="form-control" maxlength="100">
									</div>
									<label class="col-sm-1 control-label">Serial.No</label>
									<div class="col-sm-2">
										<input type="text" name="serialNumber" placeholder="Serial Number" value="<?php echo @$searchParams['serialNumber'];?>" id="serial_number" class="form-control"  maxlength="80">
									</div>
									<label class="col-sm-1 control-label">Region</label>
									<div class="col-sm-2">
										<?php echo form_dropdown('region', @$regions, @$searchParams['region_id'],'class="select2"'); ?>
									</div>
									<div class="col-sm-3" align="left">
										<button type="submit" name="searchDemoProduct" value="searchDemoProduct" class="btn btn-success" title="Search"><i class="fa fa-search"></i> </button>
										<button type="submit" name="downloadDemoProduct" value="downloadDemoProduct" formaction="<?php echo SITE_URL;?>downloadDemoProduct" class="btn btn-success" title="Download"><i class="fa fa-cloud-download"></i> </button>
										<a title="Add New" href="<?php echo SITE_URL;?>addDemoProduct" class="btn btn-success"><i class="fa fa-plus"></i> </a>
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
								<th class="text-center"><strong>Product</strong></th>
								<th class="text-center"><strong>Serial Number</strong></th>
								<th class="text-center"><strong>City</strong></th>
								<th class="text-center"><strong>Location</strong></th>
								<th class="text-center"><strong>Region</strong></th>
								<th class="text-center"><strong>Branch</strong></th>
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
<!--										<td class="text-center"><?php //echo @$row['CategoryName'];?></td>
										<td class="text-center"><?php //echo @$row['GroupName'];?></td>
-->										<td><a style="color:#000000;" data-toggle="tooltip" href="#" data-original-title="Category: <?php echo @$row['CategoryName']; ?>, Group: <?php echo @$row['GroupName'];?>"><?php echo @$row['ProductName'];?></a></td>
										<td class="text-center"><?php echo @$row['serial_number'];?></td>
										<td class="text-center"><?php echo @$row['city'];?></td>
										<td class="text-center"><?php echo @$row['location'];?></td>
										<td class="text-center"><?php echo @$row['region'];?></td>
										<td class="text-center"><?php echo @$row['branch'];?></td>
										<td class="text-center">
											<a class="btn btn-default" style="padding:3px 3px;" href="<?php echo SITE_URL;?>editDemoProduct/<?php echo @icrm_encode($row['demo_product_id']); ?>"><i class="fa fa-pencil"></i></a> 
											<?php
											if(@$row['status'] == 1)
											{
												$countChildRelation = getChildRelation('demo','demo_product_id',$row['demo_product_id']);
												if(!$countChildRelation)
												{
												?>
												<a class="btn btn-danger" style="padding:3px 3px;" href="<?php echo SITE_URL;?>deleteDemoProduct/<?php echo @icrm_encode($row['demo_product_id']); ?>" onclick="return confirm('Are you sure you want to Delete?')"><i class="fa fa-trash-o"></i></a>
												<?php
												}
											}
											else
											{
												?>
												<a class="btn btn-info" title="Activate" style="padding:3px 3px;" href="<?php echo SITE_URL;?>activateDemoProduct/<?php echo @icrm_encode($row['demo_product_id']); ?>"  onclick="return confirm('Are you sure you want to Activate?')"><i class="fa fa-check"></i></a>
												<?php
											}
											?>
										</td>
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
	
<?php
}
	$this->load->view('commons/main_footer.php',$nestedView); 
?>
<script type="text/javascript">

var region_id_js = '<?php echo @$region_id_js?>';

    $(document).on("change",".category",function() {
    	//alert('hello');		
        var old_this = $(this);
        $(old_this).parents('form').find('.product').html('<option value="">Select Product</option>');
        $.ajax({
            type: "POST",
            url: "<?php echo SITE_URL;?>getProductGroup",
            data:'category_id='+$(this).val(),
            beforeSend: function()
            {
            },
        success: function(data){
            $(old_this).parents('form').find('.group').html(data);
        }
        });

    });

    $(document).on("change",".group",function() {		
        var old_this = $(this);

        $.ajax({
            type: "POST",
            url: "<?php echo SITE_URL;?>getProduct",
            data:'group_id='+$(this).val(),
            beforeSend: function()
            {
            },
        success: function(data){
            $(old_this).parents('form').find('.product').html(data);
        }
        });

    });

    $(document).on("change",".region",function() {		
        var old_this = $(this);
        $(old_this).parents('form').find('.branch').html('<option value="">Select Branch</option>');
        $.ajax({
            type: "POST",
            url: "<?php echo SITE_URL;?>getBranch",
            data:'region_id='+$(this).val(),
            beforeSend: function()
            {
            },
        success: function(data){
            $(old_this).parents('form').find('.branch').html(data);
        }
        });

    });

$(document).ready(function(){
    select2Ajax('select2city', 'getCityFromRegion', region_id_js, 0);
});


$(document).on('change',".region",function () { 
  var region_id=$(".region").val();
  select2Ajax('select2city', 'getCityFromRegion', region_id, 0);
});

    checkDemoProductSerialNumberAvailability();
    getAutocompleteData('location','demo_product_details','location');
    getAutocompleteData('serial_number','demo_product_details','serial_number');
</script>