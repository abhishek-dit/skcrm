<?php
	$this->load->view('commons/main_template',$nestedView); 
?>

	<?php
		if(@$flg!='')
		{
			//$flg = @$this->global_functions->decode_icrm($_REQUEST['flg']);
			if($flg == 1)
			{
				$loc = 0;
				if($val == 1)
				{
					$loc = @$regionEdit[0]['location_id'];
					$formHeading = 'Edit Region Details';
				}
				else
				{
					$formHeading = 'Add New Region';
				}
				?>
					<div class="row"> 
						<div class="col-sm-12 col-md-12">
							<div class="block-flat">
								<div class="header">							
									<h4><?php echo $formHeading;?></h4>
								</div>
								<div class="content">
									<form class="form-horizontal" role="form" action="<?php echo SITE_URL; ?>locationAdd"  parsley-validate novalidate method="post">
										<input type="hidden" name="loc" id="loc" value="<?php echo $loc; ?>">
										<input type="hidden" name="location_id" value="<?php echo @$regionEdit[0]['location_id']?>">
										<input type="hidden" name="territory_level_id" value="<?php echo @$territory_level_id ?>">
										<div class="form-group">
											<label for="inputName" class="col-sm-3 control-label">Select Country <span class="req-fld">*</span></label>
											<div class="col-sm-6">
												<select style="width:100%" class="territoryEntry" name="parent"> 
													<?php
													if($val == 1)
													{
														?>
														<option value="<?php echo $parentInfo['location_id']; ?>"><?php echo $parentInfo['location']; ?></option>
														<?php

													}
													else
													{
														?>
														<option value="">Select Country</option>
														<?php
													}
													?>
													
												</select>
											</div>
										</div>
										<div class="form-group">
											<label for="inputName" class="col-sm-3 control-label">Region Name <span class="req-fld">*</span></label>
											<div class="col-sm-6">
												<input type="name" required class="form-control" id="name" placeholder="Name" name="name" value="<?php echo @$regionEdit[0]['location']; ?>"  maxlength="100">
											</div>
										</div>
										<div class="form-group">
											<div class="col-sm-offset-3 col-sm-10">
												<button class="btn btn-primary" type="submit" name="submitLocation" value="button"><i class="fa fa-check"></i> Submit</button>
												<a class="btn btn-danger" href="<?php echo SITE_URL;?>region"><i class="fa fa-times"></i> Cancel</a>
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
					<form role="form" class="form-horizontal" method="post" action="<?php echo SITE_URL;?>region">
						<div class="col-sm-12">
							<label class="col-sm-2 control-label">Country</label>
							<div class="col-sm-2">
								<?php echo form_dropdown('country', $countryDetails, @$searchParams['country_id'],'class="select2" id="Country" '); ?>
							</div>
							<label class="col-sm-2 control-label">Region</label>
							<div class="col-sm-2">
								<input type="text" name="regionName" value="<?php echo @$searchParams['regionName'];?>" id="regionName" class="form-control" placeholder="Name" maxlength="100">
							</div>
							<div class="col-sm-4">
								<button type="submit" name="searchRegion" title="" value="1" title="Search" class="btn btn-success"><i class="fa fa-search"></i> </button>
                            	<button type="submit" name="downloadRegion" value="1" formaction="<?php echo SITE_URL;?>downloadRegion" class="btn btn-success" title="Download"><i class="fa fa-cloud-download"></i> </button>
								<a href="<?php echo SITE_URL;?>addRegion" title="Add New" class="btn btn-success"><i class="fa fa-plus"></i> </a>
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
								<th class="text-center"><strong>Country</strong></th>
								<th class="text-center"><strong>Region</strong></th>
								<th class="text-center"><strong>Actions</strong></th>
							</tr>
						</thead>
						<tbody>
						<?php
							
							if(@$total_rows>0)
							{
								foreach($regionSearch as $row)
								{?>
									<tr>
										<td class="text-center"><?php echo @$sn;$sn++;?></td>
										<td class="text-center"><?php echo @$row['CountryName'];?></td>
										<td class="text-center"><?php echo @$row['location'];?></td>
										<td class="text-center">
											<a class="btn btn-default" style="padding:3px 3px;" href="<?php echo SITE_URL;?>editRegion/<?php echo @icrm_encode($row['location_id']); ?>"><i class="fa fa-pencil"></i></a>
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

<script>

$(document).ready(function(){
    select2Territory('Country');
});

</script>