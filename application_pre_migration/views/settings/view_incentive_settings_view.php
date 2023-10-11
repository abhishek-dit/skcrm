<?php
	$this->load->view('commons/main_template',$nestedView); 
?>
<div class="row"> 
	<div class="col-sm-12 col-md-12">
		<div class="block-flat">
			<div class="content">
				<form class="form-horizontal" role="form"  parsley-validate novalidate method="post">
					<div class="form-group">
						<div class="col-md-6">
							<label for="inputStartDate" class="col-sm-4 control-label">Financial Year : </label>
							<div class="col-sm-6">
	                            <P style="margin-top: 10px;"><?php echo $incentive_result['financial_year'];?></P>
							</div>
						</div>
						<div class="col-md-6">
							<label for="inputStartDate" class="col-sm-4 control-label ">Role : </label>
							<div class="col-sm-6">
	                            <p style="margin-top: 10px;"><?php echo $incentive_result['role'];?> </p>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-6">
							<label for="inputEndDate" class="col-sm-4 control-label">Quarter Incentive : </label>
							<div class="col-sm-6">
								<p style="margin-top: 10px;"><?php echo $incentive_result['value'];?> </p>
							</div>
						</div>
						<div class="col-md-6">
							<label for="inputEndDate" class="col-sm-4 control-label">Upper Cap : </label>
							<div class="col-sm-6">
								<p style="margin-top: 10px;"><?php echo $incentive_result['upper_value'];?> </p>
							</div>
						</div>
					</div>
					<div class="form-group">
						
					</div>
					<div class="form-group">
						<h4><strong><u>Grade A</u></strong></h4>
						<div class="col-md-6">
							<label for="inputFinancialYear" class="col-sm-4 control-label">PP LL : </label>
							<div class="col-sm-6">
								<p style="margin-top: 10px;"><?php echo $incentive_result['pp_ll'].'%';?> </p>
							</div>
						</div>
						<div class="col-md-6">
							<label for="inputFinancialYear" class="col-sm-4 control-label">PP UL : </label>
							<div class="col-sm-6">
								<p style="margin-top: 10px;"><?php echo $incentive_result['pp_ul'].'%';?> </p>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-6">
							<label for="inputFinancialYear" class="col-sm-4 control-label">SP LL : </label>
							<div class="col-sm-6">
								<p style="margin-top: 10px;"><?php echo $incentive_result['sp_ll'].'%';?> </p>
							</div>
						</div>
						<div class="col-md-6">
							<label for="inputFinancialYear" class="col-sm-4 control-label">SP UL : </label>
							<div class="col-sm-6">
								<p style="margin-top: 10px;"><?php echo $incentive_result['sp_ul'].'%';?> </p>
							</div>
						</div>
					</div>
					<?php
					if($incentive_result['role_id']==4)
					{
					?>
					<div class="grade_b">
						<div class="form-group">
							<h4><strong><u>Grade B</u></strong></h4>
							<div class="col-md-6">
								<label for="inputFinancialYear" class="col-sm-4 control-label">PP LL : </label>
								<div class="col-sm-6">
									<p style="margin-top: 10px;"><?php echo $incentive_result['pp2_ll'].'%';?> </p>
								</div>
							</div>
							<div class="col-md-6">
								<label for="inputFinancialYear" class="col-sm-4 control-label">PP UL : </label>
								<div class="col-sm-6">
									<p style="margin-top: 10px;"><?php echo $incentive_result['pp2_ul'].'%';?> </p>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-6">
								<label for="inputFinancialYear" class="col-sm-4 control-label">SP LL :</label>
								<div class="col-sm-6">
									<p style="margin-top: 10px;"><?php echo $incentive_result['sp2_ll'].'%';?> </p>
								</div>
							</div>
							<div class="col-md-6">
								<label for="inputFinancialYear" class="col-sm-4 control-label">SP UL : </label>
								<div class="col-sm-6">
									<p style="margin-top: 10px;"><?php echo $incentive_result['sp2_ul'].'%';?> </p>
								</div>
							</div>
						</div>
					</div>
					<?php
					}?>
					<div class="form-group">
						<div class="col-sm-offset-5 col-sm-7">
							<a class="btn btn-primary" href="<?php echo SITE_URL;?>incentive_settings"><i class="fa fa-reply"></i> Back</a>
						</div>
					</div>
				</form>
			</div>
		</div>				
	</div>
</div><br>

<?php

	$this->load->view('commons/main_footer.php',$nestedView); 
?>