<?php
	$this->load->view('commons/main_template',$nestedView); 
?>

<?php
// echo '<pre>'; print_r($conditionApproval);
echo $this->session->flashdata('response');
if(@$displayList==1) {
?>

<div class="row"> 
	<div class="col-sm-12 col-md-12">
		<div class="block-flat">
			<table class="table table-bordered"></table>
			<div class="content">
				<div class="row">
					<form role="form" class="form-horizontal" method="post" action="<?php echo SITE_URL;?>submitConditionForApprovigMail">
					<div class="col-sm-12">
					    	<div class="col-sm-8">
								<label for="inputName" class="col-sm-6 control-label">Condition For Approval Mail</label>

								<div class="form-group">
								<?php 
										$ck3 = (@$conditionApproval[0]['condition'] == 0)?'checked':'';
										$ck4 = (@$conditionApproval[0]['condition'] == 1)?'checked':'';
								?>
		                            <!-- <label class="radio-inline"> <input type="radio" name="condition" value="1" class="icheck"> Opportunity wise</label> 
		                        	<label class="radio-inline"> <input type="radio" checked="" name="condition" value="0" class="icheck"> Single Mail for whole Quote</label>  -->
									<label class="radio-inline"> <input type="radio" <?php echo $ck3; ?> name="condition" value="0" class="icheck"> Opportunity wise</label> 
      								<label class="radio-inline"> <input type="radio"  <?php echo $ck4; ?> name="condition" value="1" class="icheck"> Single Mail for whole Quote</label> 
						    	  </div>
				        	</div>
							<div class="col-sm-4">
								<button type="submit" name="submitConditionForApprovigMail" title="Search" value="1" class="btn btn-success">Submit</i> </button>
							</div>
						</div>
                            <!-- <div class="form-group">
						        <label for="inputName" class="col-sm-3 control-label">Condition For Approval Mail</label>
						          <div class="col-sm-6">
		                            <label class="radio-inline"> <input type="radio" name="condition" value="1" class="icheck"> Yes</label> 
		                        	<label class="radio-inline"> <input type="radio" checked="" name="condition" value="0" class="icheck"> No</label> 
						    	  </div>
				        	</div> -->
							<!-- <div class="col-sm-4">
								<button type="submit" name="submitConditionForApprovigMail" title="Search" value="1" class="btn btn-success">Submit</i> </button>
							</div> -->
					</form>
				</div>
				
			</div>
		</div>				
	</div>
</div>
	
<?php
}
	$this->load->view('commons/main_footer.php',$nestedView); 
?>
