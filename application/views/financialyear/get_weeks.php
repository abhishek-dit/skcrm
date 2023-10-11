<?php
	$this->load->view('commons/main_template',$nestedView); 
	echo $this->session->flashdata('response'); 

?>

	<div class="row"> 
		<div class="col-sm-12 col-md-12">
			<div class="block-flat">
				<!-- <div class="header">							
					<h4><?php echo @$formHeading;?></h4>
				</div>  -->
				<?php if($flag==1)
				{ ?>
					<div class="content center">
						<div class="row">
						</div>
					</div>
					<div class="content">
						<form class="form-horizontal" role="form" action="<?php echo SITE_URL; ?>retrieve_weeks"  parsley-validate novalidate method="post">
							
							<div class="form-group">
								<label for="inputStartDate" class="col-sm-4 control-label">Select Year <span class="req-fld">*</span></label>
								<div class="col-sm-5">
		                            <select required class="form-control" id="fy_year" placeholder="Financial Year" name="fy_year" >
		                            <option value="">Select Year</option>
		                            <?php  
		                            foreach($fy_years as $fy)
		                            {
		                            	echo '<option value="'.$fy['fy_id'].'">'.date('Y',strtotime($fy['start_date'])).'-'.date('y',strtotime($fy['end_date'])).'</option>';

		                            } ?>
		                            </select>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-offset-5 col-sm-6">
									<button class="btn btn-primary" type="submit" name="submit" value="button"><i class="fa fa-check"></i>Get Weeks</button>
									<a class="btn btn-danger" href="<?php echo SITE_URL;?>get_weeks"><i class="fa fa-times"></i> Cancel</a>
								</div>
							</div>
						</form>
					</div>
				<?php } elseif($flag==2) { ?>
				<div class="header">							
					<h4 align="center">Financial Year :<?php echo $year['name']; ?></h4>
				</div> 
				<div class="table-responsive">
					<div class="row">
					<br>

						<?php foreach($weeks as $key => $value) 
						{   ?> <div class="col-sm-4">
								<table class="table table-bordered hover">
                                	<thead>
                                		<tr>
                                			<th class="text-center" colspan="4"><strong><?php echo $value['month'] ;?></strong></th>
                                		</tr>
                                		<tr>
                                			<th class="text-center"><strong>S No</strong></th>
                                			<th class="text-center"><strong>Week No</strong></th>
                                    		<th class="text-center"><strong>Start Date</strong></th>
                                    		<th class="text-center"><strong>End Date</strong></th>
                                		</tr>
                                	</thead>
                                	<tbody>
								 		<?php
								 		$i=1;
										foreach($value['week'] as $k1 => $v1)
										{ ?>
									
										<tr>
											<?php
										    $strong='';
										    if((date('Y-m-d')>=$v1['start_date']) && (date('Y-m-d')<=$v1['end_date']))
										    	$strong="font-weight: bold; color: #F7F9FA; background-color:#bb9595";
										    ?>
										    <td class="text-center" style="<?php echo $strong?>"><?php echo  $i++;?></td>
										    <td class="text-center" style="<?php echo $strong?>"><?php echo  $v1['week_no'];?></td>
										    
											<td class="text-center" style="<?php echo $strong?>"> <?php echo date('d-m-Y',strtotime($v1['start_date']));?></td>
											<td class="text-center" style="<?php echo $strong?>"> <?php echo date('d-m-Y',strtotime($v1['end_date']));?></td>
										</tr>
										<?php } 
										if(count($value['week'])==4) 
											{ ?>
												<tr >
													<td> <br></td>
													<td></td>
													<td></td>
													<td></td>
												</tr><?php 
											} ?>

									</tbody>
								</table>
								<br>

							 </div>
						<?php } ?>
						<div class="col-sm-offset-11 col-sm-2">
							<a href="<?php echo SITE_URL;?>financial_year" class="btn btn-primary" style="padding:6px 6px;" align="right"> <i class="fa fa-arrow-left" ></i>back</a>
						</div>
						</div>
				</div>
				<?php } ?>
			</div>				
		</div>
	</div><br>
	<?php
		$this->load->view('commons/main_footer.php',$nestedView); 
    ?>
