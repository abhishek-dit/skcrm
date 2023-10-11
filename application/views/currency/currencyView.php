<?php
	$this->load->view('commons/main_template',$nestedView); 
?>

	<?php
		if(@$flg!='')
		{
			//$flg = @$this->global_functions->decode_icrm($_REQUEST['flg']);
			if($flg == 1)
			{
				$cur = 0;
				if($val == 1)
				{
					$cur = @$curEdit[0]['currency_id'];
					$formHeading = 'Edit Currency Details';
				}
				else
				{
					$formHeading = 'Add New Currency';
				}
				?>
					<div class="row"> 
						<div class="col-sm-12 col-md-12">
							<div class="block-flat">
								<div class="header">							
									<h4><?php echo $formHeading;?></h4>
								</div>
								<div class="content">
									<form class="form-horizontal" role="form" action="<?php echo SITE_URL; ?>currency_add"  parsley-validate novalidate method="post">
										<input type="hidden" name="cur" id="cur" value="<?php echo $cur; ?>">
										<input type="hidden" name="currency_id" id="currency_id" value="<?php echo @$curEdit[0]['currency_id']?>">
										
										<div class="form-group">
											<label for="inputName" class="col-sm-3 control-label">Currency Name <span class="req-fld">*</span></label>
											<div class="col-sm-6">
												<input type="text" required class="form-control"  placeholder="Currency Name" name="currency_name" value="<?php echo @$curEdit[0]['name']; ?>"  maxlength="100">
											</div>
										</div>
										<div class="form-group">
											<label for="inputName" class="col-sm-3 control-label">Currency Code <span class="req-fld">*</span></label>
											<div class="col-sm-6">
												<input type="text" required class="form-control"  placeholder="Currency Code" id="currency_check" name="currency_code" value="<?php echo @$curEdit[0]['code']; ?>"  maxlength="5">
												<p id="currencyNameValidating" class="hidden"><i class="fa fa-spinner fa-spin"></i> Checking...</p>
                                				<p id="currencyCodeError" class="error hidden"></p>
											</div>
										</div>
										<div class="form-group">
											<div class="col-sm-offset-3 col-sm-10">
												<button class="btn btn-primary" type="submit" name="submit" value="button"><i class="fa fa-check"></i> Submit</button>
												<a class="btn btn-danger" href="<?php echo SITE_URL;?>currency"><i class="fa fa-times"></i> Cancel</a>
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
				<form role="form" class="form-horizontal" method="post" action="<?php echo SITE_URL;?>currency">
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<label class="col-sm-1 control-label">Name</label>
								<div class="col-sm-2">
									<input type="text"  name="currency_name" value="<?php echo @$searchParams['currency_name'];?>"  class="form-control" placeholder="Name"  maxlength="100">
								</div>
								<label class="col-sm-1 control-label">Code</label>
								<div class="col-sm-2">
									<input type="text"  name="code" value="<?php echo @$searchParams['code'];?>" id=""  class="form-control" placeholder="code"  maxlength="6">
									
								</div>
							
				
								<div class="col-sm-2" align="right">
									<button type="submit" title="Search" name="searchCurrency" value="1" class="btn btn-success"><i class="fa fa-search"></i> </button>
		                        	<button type="submit" title="Download" name="downloadCurrency" value="1" formaction="<?php echo SITE_URL;?>downloadCurrency" class="btn btn-success"><i class="fa fa-cloud-download"></i> </button>
									<a href="<?php echo SITE_URL;?>add_currency" title="Add New" class="btn btn-success"><i class="fa fa-plus"></i> </a>
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
								<th class="text-center"><strong>Name</strong></th>
								<th class="text-center"><strong>Code</strong></th>
								<th class="text-center"><strong>Actions</strong></th>
							</tr>
						</thead>
						<tbody>
						<?php
							
							if(@$total_rows>0)
							{
								foreach($currency as $row)
								{?>
									<tr>
										<td class="text-center"><?php echo @$sn;$sn++;?></td>
										<td class="text-center"><?php echo @$row['name'];?></td>
										<td class="text-center"><?php echo @$row['code'];?></td>
										<td class="text-center">
											<a class="btn btn-default" style="padding:3px 3px;" href="<?php echo SITE_URL;?>editCurrency/<?php echo @icrm_encode($row['currency_id']); ?>"><i class="fa fa-pencil"></i></a>
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
	$(document).on('blur keyup keypress','#currency_check',function(){
    var currency_check = $(this).val();
    var currency_id = $('#currency_id').val();
    if(currency_check!=''){
        $("#currencyNameValidating").removeClass("hidden");
        var data = 'currency_name='+currency_check+'&currency_id='+currency_id;
        
        $.ajax({
        type:"POST",
        url:SITE_URL+'isCurCodeExist',
        data:data,
        cache:false,
        success:function(html){ 
     
        $("#currencyNameValidating").addClass("hidden");
            if(html==1)
            {
                $('#currency_check').val('');
                $('#currencyCodeError').html('Currency Code <b>'+currency_check+'</b> already existed');
                $("#currencyCodeError").removeClass("hidden");
                return false;
            }
            else
            {   
                $('#currencyCodeError').html('');
                $("#currencyCodeError").addClass("hidden");
                return false;
            }
        }
        });
    }

    
});
</script>

