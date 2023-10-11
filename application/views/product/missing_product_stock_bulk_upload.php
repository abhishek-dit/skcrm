<?php $this->load->view('commons/main_template', $nestedView); ?>
<?php echo $this->session->flashdata('response');
?>

    <div class="row"> 
        <div class="col-sm-12 col-md-12">
            <div class="block-flat">
                <div class="content">
                <form >
                     <div class="col-sm-12 col-md-12" style="margin-bottom:10px;">
                        <div class="col-md-9"><h3>Missing Product Stock Files</h3></div>
                        <div class="col-md-3"><a href="<?php echo SITE_URL;?>download_missing_product_stock_files/<?php echo @icrm_encode($upload_id); ?>" class="btn btn-primary">Download Missing Files XLS</a></div>
                    </div>
                
                   <div class="table-responsive">
					<table class="table table-bordered hover">
						<thead>
							<tr>
								<th class="tex9t-center"><strong>S.NO</strong></th>
								<th class="text-center"><strong>Product Code</strong></th>
								<th class="text-center"><strong>Description</strong></th>
								<th class="text-center"><strong>Quantity</strong></th>
								<th class="text-center"><strong>Date</strong></th>
								<th class="text-center"><strong>Remarks</strong></th>
							</tr>
						</thead>
						<tbody>
						<?php
							
							if(@$missing_results>0)
							{  $sn=1;
								foreach($missing_results as $row)
								{?>
									<tr>
										<td class="text-center"><?php echo $sn++;?></td>
										<td class="text-center"><?php echo @$row['product_code'];?></td>
										<td class="text-center"><?php echo @$row['description'];?></td>
										<td class="text-center"><?php echo @$row['quantity'];?></td>
										<td class="text-center"><?php if($row['on_date'] !='') echo @date('d-m-Y',strtotime($row['on_date']));?></td>
										<td class="text-center"><?php echo $row['remarks_text'] ; ?>
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
					<div class="col-sm-offset-11 col-sm-2">
							<a href="<?php echo SITE_URL;?>product_stock_upload" class="btn btn-primary" style="padding:6px 6px;" align="right"> <i class="fa fa-arrow-left" ></i>back</a>
						</div>
					</div>
				</form>
            </div>              
        </div>
    </div>
  </div>
<?php $this->load->view('commons/main_footer.php', $nestedView);  ?>
