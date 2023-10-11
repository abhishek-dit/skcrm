<?php $this->load->view('commons/main_template',$nestedView); ?>
<?php
		if(@$flg != '')
		{
			//$flg = @$this->global_functions->decode_icrm($_REQUEST['flg']);
			if($flg == 1)
			{
				if($val == 1)
				{
					$formHeading = 'Edit Company Details';
				}
				else
				{
					$formHeading = 'Add New Company';
				}
				?>
				<div class="row"> 
					<div class="col-sm-12 col-md-12">
						<div class="block-flat">
							<div class="header">							
								<h4><?php echo $formHeading;?></h4>
							</div>
							<div class="content">
								<form class="form-horizontal" role="form" action="<?php echo SITE_URL; ?>companyAdd"  parsley-validate novalidate method="post">
									<input type="hidden" name="company_id" value="<?php echo @$companyEdit[0]['company_id']?>">
									<input type="hidden" name="created_by" value="<?php echo @$this->session->userdata('user_id'); ?> ">

									<div class="form-group">
										<label for="inputName" class="col-sm-3 control-label">Company Name <span class="req-fld">*</span></label>
										<div class="col-sm-6">
											<input type="name" required class="form-control" id="name" placeholder="Name" name="name" value="<?php echo @$companyEdit[0]['name']; ?>">
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">PAN Number<span class="req-fld">*</span></label>
										<div class="col-sm-6">
											<input type="text" required class="form-control" id="PAN" value="<?php echo @$companyEdit[0]['PAN_number']; ?>"  name="PAN"  placeholder="PAN Number" >
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">TIN Number<span class="req-fld">*</span></label>
										<div class="col-sm-6">
											<input type="text" required class="form-control" id="TIN" value="<?php echo @$companyEdit[0]['TIN_number']; ?>"  name="TIN"  placeholder="TIN Number" >
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">CIN Number<span class="req-fld">*</span></label>
										<div class="col-sm-6">
											<input type="text" required class="form-control" id="CIN" value="<?php echo @$companyEdit[0]['CIN_number']; ?>"  name="CIN"  placeholder="CIN Number" >
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">TAN Number<span class="req-fld">*</span></label>
										<div class="col-sm-6">
											<input type="text" required class="form-control" id="TAN" value="<?php echo @$companyEdit[0]['TAN_number']; ?>"  name="TAN"  placeholder="TAN Number" >
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">Service Tax Number 1<span class="req-fld">*</span></label>
										<div class="col-sm-6">
											<input type="text" required class="form-control" id="service" value="<?php echo @$companyEdit[0]['service_tax_number']; ?>"  name="service"  placeholder="Service Tax Number1" >
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">Service Tax Number 2</label>
										<div class="col-sm-6">
											<input type="text" class="form-control" id="service" value="<?php echo @$companyEdit[0]['service_tax_number2']; ?>"  name="service2"  placeholder="Service Tax Number2" >
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">Sales Tax Number<span class="req-fld">*</span></label>
										<div class="col-sm-6">
											<input type="text" required class="form-control" id="sales" value="<?php echo @$companyEdit[0]['sales_tax_number']; ?>"  name="sales" placeholder="Sales Tax Number" >
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">Excise Number 1<span class="req-fld">*</span></label>
										<div class="col-sm-6">
											<input type="text" required class="form-control" id="excise" value="<?php echo @$companyEdit[0]['excise_number']; ?>"  name="excise" placeholder="Excise Number 1" >
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">Excise Number 2</label>
										<div class="col-sm-6">
											<input type="text" class="form-control" id="excise" value="<?php echo @$companyEdit[0]['excise_number2']; ?>"  name="excise2" placeholder="Excise Number 2" >
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">Country<span class="req-fld">*</span></label>
										<div class="col-sm-6">
											<input type="text" required class="form-control" id="country" value="<?php echo @$companyEdit[0]['country']; ?>"  name="country" placeholder="Country" >
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">State<span class="req-fld">*</span></label>
										<div class="col-sm-6">
											<input type="text" required class="form-control" id="state" value="<?php echo @$companyEdit[0]['state']; ?>"  name="state" placeholder="State" >
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">City<span class="req-fld">*</span></label>
										<div class="col-sm-6">
											<input type="text" required class="form-control" id="city" value="<?php echo @$companyEdit[0]['city']; ?>"  name="city" placeholder="City">
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">Area<span class="req-fld">*</span></label>
										<div class="col-sm-6">
											<textarea required class="form-control" id="address1" name="address1"><?php echo @$companyEdit[0]['address1']; ?></textarea>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">Address <span class="req-fld">*</span></label>
										<div class="col-sm-6">
											<textarea required class="form-control" id="address2" name="address2"><?php echo @$companyEdit[0]['address2']; ?></textarea>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">Bank </label>
										<div class="col-sm-6">
											<input type="text" class="form-control" name="bank_name" placeholder="Bank Name" value="<?php echo @$companyEdit[0]['bank_name'];?>">
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">Bank Branch </label>
										<div class="col-sm-6">
											<input type="text" class="form-control" name="branch" placeholder="Bank Branch" value="<?php echo @$companyEdit[0]['branch'];?>">
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">Account Name </label>
										<div class="col-sm-6">
											<input type="text" class="form-control" name="ac_name" placeholder="Account Name" value="<?php echo @$companyEdit[0]['ac_name'];?>">
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">Account Number </label>
										<div class="col-sm-6">
											<input type="text" class="form-control" name="ac_no" placeholder="Bank Account Number" value="<?php echo @$companyEdit[0]['ac_no'];?>">
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">IFSC </label>
										<div class="col-sm-6">
											<input type="text" class="form-control" name="ifsc" placeholder="IFSC Code" value="<?php echo @$companyEdit[0]['ifsc'];?>">
										</div>
									</div>
									<div class="form-group">
										<div class="col-sm-offset-3 col-sm-10">
											<button class="btn btn-primary" type="submit" name="submitCompany" value="button"><i class="fa fa-check"></i> Submit</button>
											<a class="btn btn-danger" href="<?php echo SITE_URL;?>company"><i class="fa fa-times"></i> Cancel</a>
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
					<form role="form" class="form-horizontal" method="post" action="<?php echo SITE_URL;?>company">
						<div class="col-sm-12">
							<label class="col-sm-3 control-label">Company</label>
							<div class="col-sm-2">
								<input type="text" name="companyName" placeholder="Name" value="<?php echo @$searchParams['companyName'];?>" id="companyName" class="form-control">
							</div>
							
							<div class="col-sm-5">
								<button type="submit" name="searchCompany" class="btn btn-success" value="searchCompany"><i class="fa fa-search"></i></button>
                            	<button type="submit" name="downloadCompany" class="btn btn-success" formaction="<?php echo SITE_URL;?>downloadCompany" value="downloadCompany"><i class="fa fa-cloud-download"></i></button>
								<a href="<?php echo SITE_URL;?>addCompany" class="btn btn-success"><i class="fa fa-plus"></i></a>
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
								<th class="text-center"><strong>Company Name</strong></th>
								<th class="text-center"><strong>Action</strong></th>
							</tr>
						</thead>
						<tbody>
						<?php
							
							if(@$total_rows>0)
							{
								foreach($companySearch as $row)
								{?>
									<tr>
										<td class="text-center"><?php echo @$sn;?></td>
										<td class="text-center"><?php echo @$row['name'];?></td>
										<td class="text-center">
											<a class="btn btn-default" style="padding:3px 3px;" href="<?php echo SITE_URL;?>editCompany/<?php echo @icrm_encode($row['company_id']); ?>"><i class="fa fa-pencil"></i></a> 
											<?php
											if(@$row['status'] == 1)
											{
												?>
												<a class="btn btn-danger" style="padding:3px 3px;" href="<?php echo SITE_URL;?>deleteCompany/<?php echo @icrm_encode($row['company_id']); ?>" onclick="return confirm('Are you sure you want to De-Activate?')"><i class="fa fa-trash-o"></i></a>
												<?php
											}
											else
											{
												?>
												<a class="btn btn-info" title="Activate" style="padding:3px 3px;" href="<?php echo SITE_URL;?>activateCompany/<?php echo @icrm_encode($row['company_id']); ?>"  onclick="return confirm('Are you sure you want to Activate?')"><i class="fa fa-check"></i></a>
												<?php
											}
											?>
										</td>
									</tr>
						<?php	
									@$sn++;
								}
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

//print_r($_SESSION);
?>
<?php $this->load->view('commons/main_footer.php',$nestedView);?>