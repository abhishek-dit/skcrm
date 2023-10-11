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
					$formHeading = 'Edit Sub System Details';
				}
				else
				{
					$formHeading = 'Add New Sub System';
				}
				?>
					<div class="row"> 
						<div class="col-sm-12 col-md-12">
							<div class="block-flat">
								<div class="header">							
									<h4><?php echo $formHeading;?></h4>
								</div>
								<div class="content">
									<form class="form-horizontal" role="form" action="<?php echo SITE_URL; ?>productSubCategoryAdd"  parsley-validate novalidate method="post">
										<input type="hidden" name="sub_category_id" id="sub_category_id" value="<?php echo @$subCategoryEdit[0]['sub_category_id']?>">
										<div class="form-group">
											<label for="inputName" class="col-sm-3 control-label">Sub System <span class="req-fld">*</span></label>
											<div class="col-sm-6">
												<input type="name" required class="form-control" id="sub_system" placeholder="Name" name="name" value="<?php echo @$subCategoryEdit[0]['name']; ?>">
											</div>
										</div>
										<div class="form-group">
											<div class="col-sm-offset-3 col-sm-10">
												<button class="btn btn-primary" type="submit" name="submitSubCategory" value="button"><i class="fa fa-check"></i> Submit</button>
												<a class="btn btn-danger" href="<?php echo SITE_URL;?>productSubCategory"><i class="fa fa-times"></i> Cancel</a>
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
					<form role="form" class="form-horizontal" method="post" action="<?php echo SITE_URL;?>productSubCategory">
						<div class="col-sm-12">
							<label class="col-sm-2 control-label">Sub System</label>
							<div class="col-sm-2">
								<input type="text" name="subCategoryName" value="<?php echo @$searchParams['subCategoryName'];?>" id="subCategoryName" class="form-control" placeholder="Name">
							</div>
							<div class="col-sm-8">
								<button type="submit" name="searchSubCategory" value="1" class="btn btn-success"><i class="fa fa-search"></i> Search</button>
                            	<button type="submit" name="downloadSubCategory" value="1" formaction="<?php echo SITE_URL;?>downloadSubCategory" class="btn btn-success"><i class="fa fa-cloud-download"></i> Download</button>
								<a href="<?php echo SITE_URL;?>addSubCategory" class="btn btn-success"><i class="fa fa-plus"></i> Add New</a>
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
								<th class="text-center"><strong>Sub System</strong></th>
								<th class="text-center"><strong>Actions</strong></th>
							</tr>
						</thead>
						<tbody>
						<?php
							
							if(@$total_rows>0)
							{
								foreach($subCategorySearch as $row)
								{?>
									<tr>
										<td class="text-center"><?php echo @$sn;$sn++;?></td>
										<td class="text-center"><?php echo @$row['name'];?></td>
										<td class="text-center">
											<a class="btn btn-default" style="padding:3px 3px;" href="<?php echo SITE_URL;?>editSubCategory/<?php echo @icrm_encode($row['sub_category_id']); ?>"><i class="fa fa-pencil"></i></a> 
											<?php
											if(@$row['status'] == 1)
											{
												?>
												<a class="btn btn-danger" style="padding:3px 3px;" href="<?php echo SITE_URL;?>deleteSubCategory/<?php echo @icrm_encode($row['sub_category_id']); ?>" onclick="return confirm('Are you sure you want to Delete?')"><i class="fa fa-trash-o"></i></a>
												<?php
											}
											else
											{
												?>
												<a class="btn btn-info" title="Activate" style="padding:3px 3px;" href="<?php echo SITE_URL;?>activateSubCategory/<?php echo @icrm_encode($row['sub_category_id']); ?>"  onclick="return confirm('Are you sure you want to Activate?')"><i class="fa fa-check"></i></a>
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
	checksubcategoryAvailability();
</script>