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
					$formHeading = 'Edit Product Details';
				}
				else
				{
					$formHeading = 'Add New Product';
				}
				?>
					<div class="row"> 
						<div class="col-sm-12 col-md-12">
							<div class="block-flat">
								<div class="header">							
									<h4><?php echo $formHeading;?></h4>
								</div>
								<div class="content">
									<form class="form-horizontal" role="form" action="<?php echo SITE_URL; ?>productAdd"  parsley-validate novalidate method="post">
										<input type="hidden" name="product_id" id="product_id" value="<?php echo @$productEdit[0]['product_id']?>">
										<div class="form-group">
											<label for="inputName" class="col-sm-3 control-label">Product Code<span class="req-fld">*</span></label>
											<div class="col-sm-6">
                                                					<input type="text" required class="form-control" id="name" placeholder="Product Code" name="name" value="<?php echo @$productEdit[0]['name']; ?>" maxlength="150">
											</div>
										</div>
										<div class="form-group">
											<label for="inputCategoryName" class="col-sm-3 control-label">Category <span class="req-fld">*</span></label>
				                            <div class="col-sm-6">
												<?php $disabledText = ($val == 1)?'disabled':'';
												echo form_dropdown('category', $categories, @$productEdit[0]['category_id'],'class="select2 category" required '.$disabledText); ?>
											</div>
										</div>
										<div class="form-group">
											<label for="inputGroupName" class="col-sm-3 control-label">Segment <span class="req-fld">*</span></label>
											<div class="col-sm-6">
												<?php echo form_dropdown('group', $groups, @$productEdit[0]['group_id'],'class="select2 group" required'); ?>
											</div>
										</div>
										<div class="form-group">
											<label for="inputGroupName" class="col-sm-3 control-label">Sub System <span class="req-fld">*</span></label>
											<div class="col-sm-6">
												<?php echo form_dropdown('sub_category', $sub_system, @$productEdit[0]['sub_category_id'],'class="select2" required'); ?>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-3 control-label">Product Name</label></label>
											<div class="col-sm-6">
												<input type="text" class="form-control" id="name" placeholder="Product Name" name="product_name" value="<?php echo @$productEdit[0]['name2']; ?>" maxlength="150">
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-3 control-label">Description</label>
											<div class="col-sm-6">
												<textarea class="form-control" id="description" name="description"><?php echo @$productEdit[0]['description']; ?></textarea>
											</div>
										</div>
										<div class="form-group">
											<label for="inputProductType" class="col-sm-3 control-label">Product Type <span class="req-fld">*</span></label>
											<div class="col-sm-6">
												<?php echo form_dropdown('product_type_id', $product_type, @$productEdit[0]['product_type_id'],'class="select2 product_type" required'); ?>
											</div>
										</div>
										<div class="form-group">
										  <label for="inputName" class="col-sm-3 control-label">Target</label>
												<div class="col-sm-6">
										      <label class="radio-inline"> <input type="radio" <?php if(@$productEdit[0]['target']==1) { echo 'checked';}?>   name="target" value="1" class="icheck"> Yes</label> 
										      <label class="radio-inline"> <input type="radio" <?php if(@$productEdit[0]['target']==2) { echo 'checked';}?>  name="target" style="margin-left: 14px;" value="2" class="icheck"> No</label> 
											</div>
										</div>
										<div class="form-group">
										  <label for="inputName" class="col-sm-3 control-label">Availability</label>
												<div class="col-sm-6">
										      <label class="radio-inline"> <input type="radio" <?php if(@$productEdit[0]['availability']==1 || $flg==1) { echo 'checked';}?>   name="availability" value="1" class="icheck"> Active</label> 
										      <label class="radio-inline"> <input type="radio" <?php if(@$productEdit[0]['availability']==2) { echo 'checked';}?>  name="availability" value="2" class="icheck"> Inactive</label> 
											</div>
										</div>

										<div class="form-group">
											<label for="inputMrp" class="col-sm-3 control-label">MRP <span class="req-fld">*</span></label>
											<div class="col-sm-6">
												<input type="text" required class="form-control only-numbers" id="mrp" placeholder="MRP" name="mrp" value="<?php echo @$productEdit[0]['mrp']; ?>">
											</div>
										</div>
										<div class="form-group">
											<label for="inputBp" class="col-sm-3 control-label">Base Price <span class="req-fld">*</span></label>
											<div class="col-sm-6">
												<input type="text" required class="form-control only-numbers" id="base_price" placeholder="Base Price" name="basePrice" value="<?php echo @$productEdit[0]['base_price']; ?>">
											</div>
										</div>
										<!-- <div class="form-group">
											<label for="inputEd" class="col-sm-3 control-label">ED In %<span class="req-fld">*</span></label>
											<div class="col-sm-6">
												<input type="text" required class="form-control" max-length="5" id="ed" placeholder="ED" name="ed" value="<?php echo @$productEdit[0]['ed']; ?>">
											</div>
										</div>
										<div class="form-group">
											<label for="inputVat" class="col-sm-3 control-label">VAT In % <span class="req-fld">*</span></label>
											<div class="col-sm-6">
												<input type="text" required class="form-control" max-length="5" id="vat" placeholder="VAT" name="vat" value="<?php echo @$productEdit[0]['vat']; ?>">
											</div>
										</div> -->
										<div class="form-group">
											<label for="inputVat" class="col-sm-3 control-label">GST In % <span class="req-fld">*</span></label>
											<div class="col-sm-6">
												<input type="text" required class="form-control" max-length="5" id="gst" placeholder="GST" name="gst" value="<?php echo @$productEdit[0]['gst']; ?>">
											</div>
										</div>
										<div class="form-group">
											<label for="inputFreightInsurance" class="col-sm-3 control-label">Freight Insurance  In % <span class="req-fld">*</span></label>
											<div class="col-sm-6">
												<input type="text" required class="form-control" max-length="5" id="freightInsurance" placeholder="Freight Insurance" name="freightInsurance" value="<?php echo @$productEdit[0]['freight_insurance']; ?>">
											</div>
										</div>
										<div class="form-group">
											<label for="inputRrp" class="col-sm-3 control-label">RRP <span class="req-fld">*</span></label>
											<div class="col-sm-6">
												<input type="text" required class="form-control only-numbers" max-length="20"  id="rrp" placeholder="RRP" name="rrp" value="<?php echo @$productEdit[0]['rrp']; ?>">
											</div>
										</div>
										<div class="form-group">
											<label for="inputDp" class="col-sm-3 control-label">DP <span class="req-fld">*</span></label>
											<div class="col-sm-6">
												<input type="text" required class="form-control only-numbers" max-length="20"  id="dp" placeholder="DP" name="dp" value="<?php echo @$productEdit[0]['dp']; ?>">
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-3 control-label">Technical Specifications</label>
											<div class="col-sm-6">
												<textarea class="form-control " rows="15" id="features" name="features"><?php echo trim_ck_editor_data(@$productEdit[0]['features']); ?></textarea>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-3 control-label">Scope of Supply</label>
											<div class="col-sm-6">
												<textarea class="form-control" rows="15" id="features" name="scope"><?php echo trim_ck_editor_data(@$productEdit[0]['scope']); ?></textarea>
											</div>
										</div>
										<div class="form-group">
											<div class="col-sm-offset-3 col-sm-10">
												<button class="btn btn-primary" type="submit" name="submitProduct" value="button"><i class="fa fa-check"></i> Submit</button>
												<a class="btn btn-danger" href="<?php echo SITE_URL;?>product"><i class="fa fa-times"></i> Cancel</a>
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
				
				<form role="form" class="form-horizontal" method="post" action="<?php echo SITE_URL;?>product">
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<label class="col-sm-1 control-label">Category</label>
	                            <div class="col-sm-2">
									<?php echo form_dropdown('category', $categories, @$searchParams['category_id'],'class="select2 category"'); ?>
								</div>
								<label class="col-sm-1 control-label">Segment</label>
	                            <div class="col-sm-2">
									<?php echo form_dropdown('group', $groups, @$searchParams['group_id'],'class="select2 group"'); ?>
								</div>
								<label class="col-sm-1 control-label">Product</label>
								<div class="col-sm-2">
                                                                    <input type="text" name="productName" placeholder="Name" value="<?php echo @$searchParams['productName'];?>" id="productName" class="form-control" maxlength="150">
								</div>
								<label class="col-sm-1 control-label">Description</label>
								<div class="col-sm-2">
									<input type="text" name="productDescription" placeholder="Product Description" value="<?php echo @$searchParams['productDescription'];?>" id="productDescription" class="form-control"  maxlength="250">
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
							<label class="col-sm-1 control-label">Type</label>
	                            <div class="col-sm-3">
									<?php echo form_dropdown('product_types_id', $product_type, @$searchParams['product_type_id'],'class="select2 product_types"'); ?>
								</div>
								<div class="col-sm-8" align="right">
									<button type="submit" name="searchProduct" value="searchProduct" class="btn btn-success" title="Search"><i class="fa fa-search"></i> </button>
									<button type="submit" name="downloadProduct" value="downloadProduct" formaction="<?php echo SITE_URL;?>downloadProduct" class="btn btn-success" title="Download"><i class="fa fa-cloud-download"></i> </button>
									<a href="<?php echo SITE_URL;?>addProduct" title="Add New" class="btn btn-success"><i class="fa fa-plus"></i> </a>
									<a href="<?php echo SITE_URL;?>product_price_upload" title="Price Upload" class="btn btn-success"><i class="fa fa-cloud-upload"></i> Price Upload</a>
									<a href="<?php echo SITE_URL;?>product_bulk_upload" title="Bulk Product Upload" class="btn btn-success"><i class="fa fa-cloud-upload"></i> Bulk Product Upload</a>
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
								<th class="text-center"><strong>Category</strong></th>
								<th class="text-center"><strong>Segment</strong></th>
								<th class="text-center"><strong>Product</strong></th>
								<th class="text-center"><strong>Description</strong></th>
								<th class="text-center"><strong>Type</strong></th>
								<th class="text-center"><strong>MRP (Rs)</strong></th>
								<th class="text-center"><strong>Base Price (Rs)</strong></th>
								<th class="text-center"><strong>DP (Rs)</strong></th>
								<th class="text-center"><strong>Actions</strong></th>
							</tr>
						</thead>
						<tbody>
						<?php
							if(@$total_rows>0)
							{
								foreach($productSearch as $row)
								{?>
									<tr>
										<td class="text-center"><?php echo @$sn;$sn++;?></td>
										<td class="text-center"><?php echo @$row['CategoryName'];?></td>
										<td class="text-center"><?php echo @$row['GroupName'];?></td>
										<td class="text-center"><?php echo @$row['name'];?></td>
										<td class="text-center"><?php echo @$row['description'];?></td>
										<td class="text-center"><?php echo @$row['pt_name'];?></td>
										<td class="text-center"><?php echo @$row['mrp'];?></td>
										<td class="text-center"><?php echo @$row['base_price'];?></td>
										<td class="text-center"><?php echo @$row['dp'];?></td>
										<td>
											<a class="btn btn-default btn-xs"  href="<?php echo SITE_URL;?>editProduct/<?php echo @icrm_encode($row['product_id']); ?>"><i class="fa fa-pencil"></i></a> 
											<?php
											if(@$row['status'] == 1)
											{
												$countChildRelation = getProductChildRelation($row['product_id']);
												if(!$countChildRelation)
												{
												?>
												<a class="btn btn-danger btn-xs"  href="<?php echo SITE_URL;?>deleteProduct/<?php echo @icrm_encode($row['product_id']); ?>" onclick="return confirm('Are you sure you want to Delete?')"><i class="fa fa-trash-o"></i></a>
												<?php
												}
											}
											else
											{
												?>
												<a class="btn btn-info" title="Activate" style="padding:3px 3px;" href="<?php echo SITE_URL;?>activateProduct/<?php echo @icrm_encode($row['product_id']); ?>"  onclick="return confirm('Are you sure you want to Activate?')"><i class="fa fa-check"></i></a>
												<?php
											}
											?>
										</td>
									</tr>
						<?php	}
							} else {
							?>	<tr><td colspan="9" align="center"><span class="label label-primary">No Records</span></td></tr>
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
<script>
     $(document).on("change",".category",function() {		
        var old_this = $(this);

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

    checkProductAvailability();
    getAutocompleteData('productName','product','name');
    getAutocompleteData('productDescription','product','description');
</script>