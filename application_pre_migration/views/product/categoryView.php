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
					$formHeading = 'Edit Category Details';
				}
				else
				{
					$formHeading = 'Add New Category';
				}
				?>
					<div class="row"> 
						<div class="col-sm-12 col-md-12">
							<div class="block-flat">
								<div class="header">							
									<h4><?php echo $formHeading;?></h4>
								</div>
								<div class="content">
									<form class="form-horizontal" role="form" action="<?php echo SITE_URL; ?>productCategoryAdd"  parsley-validate novalidate method="post">
										<input type="hidden" name="category_id" id="category_id" value="<?php echo @$categoryEdit[0]['category_id']?>">
										<div class="form-group">
											<label for="inputName" class="col-sm-3 control-label">Product Category <span class="req-fld">*</span></label>
											<div class="col-sm-6">
                                                                                            <input type="name" required class="form-control" id="name" placeholder="Name" maxlength="150" name="name" value="<?php echo @$categoryEdit[0]['name']; ?>">
											</div>
										</div>
										<!-- <div class="form-group">
											<label for="inputSubCategoryName" class="col-sm-3 control-label">Sub Category <span class="req-fld">*</span></label>
				                            <div class="col-sm-6">
												<?php echo form_dropdown('subCategory[]', $subCategory, @$subCategorySelected,'class="select2 subCategory" required multiple'); ?>
											</div>
										</div> -->
										<div class="form-group">
											<label class="col-sm-3 control-label">Description</label>
											<div class="col-sm-6">
												<textarea class="form-control" id="description" name="description"><?php echo @$categoryEdit[0]['description']; ?></textarea>
											</div>
										</div>
										<!-- <div class="form-group">
											<label class="col-sm-3 control-label">Competitors</label>
											<div class="col-sm-6">
												<?php foreach ($competitors as $key=>$value) : ?>
													<?php $checked = in_array($key,$competitorSelected)?'checked':'';?>
													<div class="radio col-sm-4">
														<label> <input type="checkbox" class="icheck" value="<?php echo $key;?>" name="competitors[]" <?php echo $checked; ?> > <?php echo $value;?></label> 
													</div>
												<?php endforeach; ?>
											</div>
										</div> -->
										<div class="form-group">
											<div class="col-sm-offset-3 col-sm-10">
												<button class="btn btn-primary" type="submit" name="submitCategory" value="button"><i class="fa fa-check"></i> Submit</button>
												<a class="btn btn-danger" href="<?php echo SITE_URL;?>productCategory"><i class="fa fa-times"></i> Cancel</a>
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
				<div class="row">
					<form role="form" class="form-horizontal" method="post" action="<?php echo SITE_URL;?>productCategory">
						<div class="col-sm-12">
							<label class="col-sm-2 control-label">Product Category</label>
							<div class="col-sm-2">
                                                            <input type="text" name="categoryName" value="<?php echo @$searchParams['categoryName'];?>" id="categoryName" class="form-control" placeholder="Name" maxlength="150">
							</div>
							<div class="col-sm-4">
								<button type="submit" name="searchCategory" title="Search" value="1" class="btn btn-success"><i class="fa fa-search"></i> </button>
                            	<button type="submit" name="downloadCategory" value="1" title="Download" formaction="<?php echo SITE_URL;?>downloadCategory" class="btn btn-success"><i class="fa fa-cloud-download"></i> </button>
								<a href="<?php echo SITE_URL;?>addCategory" title="Add New" class="btn btn-success"><i class="fa fa-plus"></i> </a>
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
								<th class="text-center"><strong>Product Category</strong></th>
								<th class="text-center"><strong>Description</strong></th>
								<!-- <th class="text-center"><strong>Sub Categories</strong></th> -->
								<th class="text-center"><strong>Actions</strong></th>
							</tr>
						</thead>
						<tbody>
						<?php
							
							if(@$total_rows>0)
							{
								foreach($categorySearch as $row)
								{?>
									<tr>
										<td class="text-center"><?php echo @$sn;$sn++;?></td>
										<td class="text-center"><?php echo @$row['name'];?></td>
										<td class="text-center"><?php echo @$row['description'];?></td>
										<!-- <td><?php echo @$row['subcategories'];?> --></td>
										<td class="text-center">
											<a class="btn btn-default" style="padding:3px 3px;" href="<?php echo SITE_URL;?>editCategory/<?php echo @icrm_encode($row['category_id']); ?>"><i class="fa fa-pencil"></i></a> 
											<?php
											if(@$row['status'] == 1)
											{
												$countChildRelation = getChildRelation('product_group','category_id',$row['category_id']);
												if(!$countChildRelation)
												{
												?>
												<a class="btn btn-danger" style="padding:3px 3px;" href="<?php echo SITE_URL;?>deleteCategory/<?php echo @icrm_encode($row['category_id']); ?>" onclick="return confirm('Are you sure you want to Delete?')"><i class="fa fa-trash-o"></i></a>
												<?php
												}
											}
											else
											{
												?>
												<a class="btn btn-info" title="Activate" style="padding:3px 3px;" href="<?php echo SITE_URL;?>activateCategory/<?php echo @icrm_encode($row['category_id']); ?>"  onclick="return confirm('Are you sure you want to Activate?')"><i class="fa fa-check"></i></a>
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
	$this->load->view('commons/main_footer.php',$nestedView); 
?>
<script type="text/javascript">
	checkCategoryAvailability();
	getAutocompleteData('categoryName','product_category','name');
</script>