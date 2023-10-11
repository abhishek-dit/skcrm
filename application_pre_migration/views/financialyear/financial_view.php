<?php
	$this->load->view('commons/main_template',$nestedView); 
?>
	<?php
		if(@$flg!='')
		{
			//$flg = @$this->global_functions->decode_icrm($_REQUEST['flg']);
			if($flg == 1)
			{
				
					$formHeading = 'Add New Financial Year';
				?>
					<div class="row"> 
						<div class="col-sm-12 col-md-12">
							<div class="block-flat">
								<div class="header">							
									<h4><?php echo $formHeading;?></h4>
								</div>
								<div class="content">
									<form class="form-horizontal" role="form" action="<?php echo SITE_URL; ?>insert_financial_year"  parsley-validate novalidate method="post">
										<div class="form-group">
											<label for="inputStartDate" class="col-sm-3 control-label">Start Date <span class="req-fld">*</span></label>
											<div class="col-sm-6">
				                                <input type="text" required class="form-control" id="start_date" placeholder="Start Date" name="start_date" >
											</div>
										</div>
										<div class="form-group">
											<label for="inputEndDate" class="col-sm-3 control-label">End Date <span class="req-fld">*</span></label>
											<div class="col-sm-6">
												<input type="text" required class="form-control" id="end_date" placeholder="End Date" name="end_date" >
											</div>
										</div>
										<div class="form-group">
											<label for="inputFinancialYear" class="col-sm-3 control-label">Financial Year <span class="req-fld">*</span></label>
											<div class="col-sm-6">
												<input type="text" required class="form-control" placeholder="for ex: xxxx-xx" name="financial_year_name" >
											</div>
										</div>
										<div class="form-group">
											<div class="col-sm-offset-3 col-sm-10">
												<button class="btn btn-primary" type="submit" name="submit" value="button"><i class="fa fa-check"></i> Submit</button>
												<a class="btn btn-danger" href="<?php echo SITE_URL;?>financial_year"><i class="fa fa-times"></i> Cancel</a>
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
					<form role="form" class="form-horizontal" method="post" action="<?php echo SITE_URL;?>financial_year">
						
						<div class="col-md-12">
							<!-- <label class="col-sm-2 control-label">Start Date</label>
							<div class="col-sm-2">
                                <input type="text" class="form-control" id="start_date" placeholder="Start Date" name="start_date" >
							</div>
							<label class="col-sm-2 control-label">End Date</label>
							<div class="col-sm-2">
                                <input type="text" class="form-control" id="end_date" placeholder="End Date" name="end_date" >
							</div> -->
							<label class="col-sm-2 control-label">Financial Year</label>
							<div class="col-sm-2">
                                <select class="form-control" id="fy_year" placeholder="Financial Year" name="fy_year" >
		                            <option value="">Select Year</option>
		                            <?php 
		                            $selected =''; 
		                            foreach($fy_years as $fy)
		                            {    if($fy['fy_id']==@$search_data['fy_year'])
		                                  {
		                                   		$selected ='selected';
		                                  }
		                                  else
		                                  {
		                                  	$selected ='';
		                                  }
		                            	echo '<option value="'.$fy['fy_id'].'"'.$selected.'>'.$fy['name'].'</option>';

		                            } ?>
		                        </select>
							</div>
							<div class="col-sm-4">
								<button type="submit" name="searchyear" title="Search" value="1" class="btn btn-success"><i class="fa fa-search"></i> </button>
                            	<!-- <button type="submit" name="downloadyear" value="1" title="Download" formaction="<?php echo SITE_URL;?>downloadCategory" class="btn btn-success"><i class="fa fa-cloud-download"></i> </button> -->
								<a href="<?php echo SITE_URL;?>add_financial_year" title="Add New" class="btn btn-success"><i class="fa fa-plus"></i> </a>
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
								<th class="text-center"><strong>Financial Year</strong></th>
								<th class="text-center"><strong>Start Date</strong></th>
								<th class="text-center"><strong>End date</strong></th>
								<th class="text-center"><strong>View Calendar</strong></th>
							</tr>
						</thead>
						<tbody>
						<?php
							
							if(@$total_rows>0)
							{
								foreach($yearSearch as $row)
								{?>
									<tr>
										<td class="text-center"><?php echo @$sn;$sn++;?></td>
										<td class="text-center"><?php echo @$row['name'];?></td>
										<td class="text-center"><?php echo @$row['start_date'];?></td>
										<td class="text-center"><?php echo @$row['end_date'];?></td>
										<td class="text-center"><a class="btn btn-primary" style="padding:3px 3px;" href="<?php echo SITE_URL;?>retrieve_weeks/<?php echo @icrm_encode($row['fy_id']); ?>"><i class="fa fa-eye"></i></a></td>
										
									</tr>
						<?php	}
							} else {
							?>	<tr><td colspan="4" align="center"><span class="label label-primary">No Records</span></td></tr>
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
