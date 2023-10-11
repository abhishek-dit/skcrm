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
									<form class="form-horizontal" role="form" action="<?php echo SITE_URL; ?>materialGroupAdd"  parsley-validate novalidate method="post">
										<input type="hidden" name="group_id" id="group_id" value="<?php echo @$groupEdit[0]['group_id']?>">
										<div class="form-group">
											<label for="inputName" class="col-sm-3 control-label">Product Category Name <span class="req-fld">*</span></label>
											<div class="col-sm-6">
												<?php echo form_dropdown('category', $categories, @$groupEdit[0]['category_id'],'class="select2"'); ?>
											</div>
										</div>
										<div class="form-group">
											<label for="inputName" class="col-sm-3 control-label">Segment Name <span class="req-fld">*</span></label>
											<div class="col-sm-6">
                                                <input type="name" required class="form-control" id="name" placeholder="Name" name="name" value="<?php echo @$groupEdit[0]['name']; ?>" maxlength="150">
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-3 control-label">Segment Description</label>
											<div class="col-sm-6">
												<textarea class="form-control" id="description" name="description"><?php echo @$groupEdit[0]['description']; ?></textarea>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-3 control-label">Competitors</label>
											<div class="col-sm-6">
												<?php foreach ($competitors as $key=>$value) : ?>
													<?php $checked = in_array($key,$competitorSelected)?'checked':'';?>
													<div class="radio col-sm-4">
														<label> <input type="checkbox" class="icheck" value="<?php echo $key;?>" name="competitors[]" <?php echo $checked; ?> > <?php echo $value;?></label> 
													</div>
												<?php endforeach; ?>
											</div>
										</div>
										<!-- <?php if($val == 1) { ?>
                                        <div class="form-group">
											<label class="col-sm-3 control-label">Target Product</label>
											<div class="col-sm-6">
												<?php 
                                                @$products=array('0'=>"Select Target Product")+ @$products;   
                                                echo form_dropdown('product_id', @$products, @$target_product,'class="select2"'); ?>
											</div>
										</div>
										<?php } ?> -->
										<div class="form-group">
											<div class="col-sm-offset-3 col-sm-10">
												<button class="btn btn-primary" type="submit" name="submitGroup" value="button"><i class="fa fa-check"></i> Submit</button>
												<a class="btn btn-danger" href="<?php echo SITE_URL;?>materialGroup"><i class="fa fa-times"></i> Cancel</a>
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
				<form role="form" class="form-horizontal" method="post" action="<?php echo SITE_URL;?>materialGroup">
					<div class="row">
						<div class="form-group">
							<div class="col-sm-12">
								<label class="col-sm-1 control-label">Category</label>
                                                                <div class="col-sm-2">
									<?php echo form_dropdown('category', $categories, @$searchParams['category_id'],'class="select2"'); ?>
								</div>
								<label class="col-sm-1 control-label">Segment </label>
								<div class="col-sm-2">
									<input type="text" name="groupName" value="<?php echo @$searchParams['groupName'];?>" id="groupName" class="form-control" placeholder="Name"  maxlength="150">
								</div>
								<label class="col-sm-1 control-label">Description</label>
								<div class="col-sm-2">
									<input type="text" name="groupDescription" placeholder="Segment Description" value="<?php echo @$searchParams['groupDescription'];?>" id="groupDescription" class="form-control"  maxlength="250">
								</div>    
								<div class="col-sm-2" align="right">
									<button type="submit" title="Search" name="searchGroup" value="searchGroup" class="btn btn-success"><i class="fa fa-search"></i> </button>
									<button type="submit" name="downloadGroup" value="downloadGroup" title="Download" formaction="<?php echo SITE_URL;?>downloadGroup" class="btn btn-success"><i class="fa fa-cloud-download"></i> </button>
									<a href="<?php echo SITE_URL;?>addGroup" title="Add New" class="btn btn-success"><i class="fa fa-plus"></i> </a>
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
								<th class="text-center"><strong>Product Category</strong></th>
								<th class="text-center"><strong>Segment</strong></th>
								<th class="text-center"><strong>Description</strong></th>
								<th class="text-center"><strong>Actions</strong></th>
							</tr>
						</thead>
						<tbody>
						<?php
							
							if(@$total_rows>0)
							{
								foreach($groupSearch as $row)
								{?>
									<tr>
										<td class="text-center"><?php echo @$sn;$sn++;?></td>
										<td class="text-center"><?php echo @$row['CategoryName'];?></td>
										<td class="text-center"><?php echo @$row['name'];?></td>
										<td class="text-center"><?php echo @$row['description'];?></td>
										<td class="text-center">
											<a class="btn btn-default" style="padding:3px 3px;" href="<?php echo SITE_URL;?>editGroup/<?php echo @icrm_encode($row['group_id']); ?>"><i class="fa fa-pencil"></i></a> 
											<?php
											if(@$row['status'] == 1)
											{
												$countChildRelation = getChildRelation('product','group_id',$row['group_id']);
												if(!$countChildRelation)
												{
												?>
												<a class="btn btn-danger" style="padding:3px 3px;" href="<?php echo SITE_URL;?>deleteGroup/<?php echo @icrm_encode($row['group_id']); ?>" onclick="return confirm('Are you sure you want to Delete?')"><i class="fa fa-trash-o"></i></a>
												<?php
												}
											}
											else
											{
												?>
												<a class="btn btn-info" title="Activate" style="padding:3px 3px;" href="<?php echo SITE_URL;?>activateGroup/<?php echo @icrm_encode($row['group_id']); ?>"  onclick="return confirm('Are you sure you want to Activate?')"><i class="fa fa-check"></i></a>
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
	checkGroupAvailability();
	getAutocompleteData('groupName','product_group','name');
	getAutocompleteData('groupDescription','product_group','description');
</script>