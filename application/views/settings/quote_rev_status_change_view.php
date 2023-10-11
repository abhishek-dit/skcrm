<?php
	$this->load->view('commons/main_template',$nestedView); 
?>

	

<?php
echo $this->session->flashdata('response');
?>

<div class="row"> 
	<div class="col-sm-12 col-md-12">
		<div class="block-flat">
			<table class="table table-bordered"></table>
			<div class="content">
				<div class="row">
					<form role="form" class="form-horizontal" method="post" action="<?php echo SITE_URL;?>updateRevStatus">

						<div class="col-sm-12">
							<label class="col-sm-5 control-label">Lead ID</label>
							<div class="col-sm-2">
								<input type="text" class="form-control" value="" required name="lead_id" placeholder="Lead ID">

							</div>
						</div>

						<div class="col-sm-12">
							<label class="col-sm-5 control-label">Please Add Quote Rivision ID</label>
							<div class="col-sm-2">
                            <!-- <input type="text" name="percentage" value="<?php //echo @$freeSupplyItems['percentage'];?>" id="percentage" class="form-control" placeholder="Percentage" maxlength="150"> -->
							<input type="text" class="form-control only-numbers" value="" required name="rev_id" placeholder="Rev ID">

							</div>
							<div class="col-sm-4">
								<button type="submit" name="updateRevStatus" title="Search" value="1" class="btn btn-success">Submit</i> </button>
							</div>
						</div>
					</form>
				</div>
				
			</div>
		</div>				
	</div>
</div>
	
<?php

	$this->load->view('commons/main_footer.php',$nestedView); 
?>
<script type="text/javascript">
	checkCategoryAvailability();
	getAutocompleteData('categoryName','product_category','name');
</script>