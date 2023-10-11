<!--<div class="md-modal colored-header custom-width md-effect-9" id="<?php //echo @$pop_id;?>">-->
<div class="modal fade colored-header" id="info<?php echo @$row['quote_revision_id'];?>" role="dialog">
	<div class="modal-dialog">
			<div class="md-content">
				<div class="modal-header">
					<span style="font-size:18px">Quote Revision History</span>
					<button type="button" class="close md-close" data-dismiss="modal" aria-hidden="true">&times;</button>
				</div>

				<div class="modal-body form">
					<form action="<?php echo SITE_URL; ?>" method="post" novalidate="" parsley-validate="" class="form-horizontal">
		                <br>
		                <div class="row">
			                <div class="col-sm-12">
			                	<div class="col-sm-1"></div>
			                	<div class="col-sm-10">
					                <div class="form-group">
					                    <div class="col-sm-3" align="right">Quote ID:</div>
					                    <div class="col-sm-9" align="left"><?php echo getQuoteReferenceID1($lead_id, @$row['quote_id']); ?></div>
					                </div>
					                <div class="form-group">
					                    <div class="col-sm-3" align="right">Opportunities:</div>
										<div class="col-sm-9" align="left"><?php 
										$opp = get_opportunities_in_quote($lead_id,$row['quote_id'],$row['quote_revision_id']);
										if(!empty($opp) && isset($opp))
										{
											echo $opp;
										}
										else{
											echo '';
										}
										//echo get_opportunities_in_quote($lead_id,$row['quote_id'],$row['quote_revision_id']); ?></div>
					                </div>
					                <div class="form-group">
					                    <div class="col-sm-3" align="right">Billing Through:</div>
					                    <div class="col-sm-9"><?php $res=get_channel_partner_details($row['quote_id']); echo @$res['name'] ?></div>
					                </div>
					                <div class="form-group">
					                    <div class="col-sm-3" align="right">Revisions:</div>
					                    <div class="col-sm-9"></div>
					                </div>
					                <div class="form-group">
					                    <div class="col-sm-12">
											<table class="table table-bordered hover">
												<thead>
													<tr>
														<th>Revision</th>
														<th>Billing</th>
														<th>Discount</th>
														<th>Total Price (<?php echo get_quote_currency_details($row['quote_id']) ?>)</th>
														<th>Status</th>
													</tr>
												</thead>
												<tbody>
												<?php 
												//print_r($row);
												$quoteRevisions = getQuoteRevisions($row['quote_id']);
												
												$i = 1; $current_waiting_revision = '';
												foreach($quoteRevisions as $row) 
												{
													if($i==count($quoteRevisions)&&($row['status']==3))
													{
														$current_waiting_revision = $row['quote_revision_id'];
													}
													$quote_format_type = getQuoteFormatTypeByQuoteRevisionID($row['quote_revision_id']);
													switch ($quote_format_type) {
														case 1: // Old Format
															$discount = ($row['discount']);
															/*if($lead_user_role_id == 5){
																$quote_price = getDPQuotePrice($row['quote_id'], $row['discount']);
															}else{*/
																$quote_price = getQuotePrice($row['quote_id'], $row['discount']);
															//}
														break;
														case 2: // New Format
															
															if($i==1)
															{
																$discount = 0;
																/*if($lead_user_role_id == 5){
																	$quote_price = getDPQuotePrice($row['quote_id'], $row['discount']);
																}else{*/
																	$quote_price = getQuotePrice($row['quote_id'], $discount);
																//}
															}
															else
															{
																/*if($lead_user_role_id == 5){
																	$qrow = getDpQuoteRevisionPrice($row['quote_revision_id']);
																}else{*/
																	$qrow = getQuoteRevisionPrice($row['quote_revision_id']);
																//}
																//print_r($qrow);
																$quote_price = round($qrow['quote_price']);
																$cost = round($qrow['cost']);
																$discount_amt = ($cost-$quote_price);
																$discount = ($discount_amt/$cost)*100;
															}
														break;
													}
													?>
													<tr>
														<td><?php echo $i; ?></td>
														<td><?php echo $row['name']; ?></td>
														<td><?php echo round($discount,2); ?>%</td>
														<td><?php echo indian_format_price($quote_price); ?></td>
														<?php //if($lead_user_role_id == 5){ ?>
															<!--<td>DP Quote (Current Quote)</td>-->
														<?php //}else{ ?>
															<td><?php echo getQuoteRevisionStatusLabel($row['status'],$i); ?></td>
														<?php //} ?>
													</tr>
													<?php
													$i++;
												}
												?>
												</tbody>
											</table>					                    	
					                    </div>
					                </div>



									<!-- quote details start -->
									<?php 
									$opp_discount = 0;
									foreach ($opportunities as $opp_dis) {
										$opp_discount += $opp_dis['discount'];
									}
									if($opp_discount !=0)
									{
									?>

									<hr>
									<span style="font-size:22px">Margin Analysis Details</span>

									<div class="modal-body form">
									<div class="row">
										<!-- <label class="col-md-3">Opportunity</label>
										<div class="col-md-9"><?php echo $row['opportunity'];?></div> -->
										<table id="table1" style="width:100%" class="kstyle" cellspacing="0" border="1">
											<thead>
												<tr>
													<th class="text-center">Opportunity ID</th>
													<th class="text-center">Product</th>
													<th class="text-center">Qty</th>
													<th class="text-center">Unit MRP</th>
													<th class="text-center">Discount %</th>
													<th class="text-center">Warranty</th>
													<th class="text-center">ORC %</th>
													<th class="text-center">Quoted Price</th>
													<th class="text-center">DP</th>
												</tr>
											</thead>
											<tbody>
	                        	<?php
	                        	foreach ($opportunities as $orow) {
	                        		$order_value = $orow['mrp'];
                                    $discount = $orow['discount'];
                                    if($orow['discount_type']!=''&&$discount!='')
                                    $order_value = ($orow['discount_type']==1)?($order_value*(1-$orow['discount']/100)):($order_value-$discount);
                                    $discount_percentage = round((($orow['mrp'] - $order_value )/$orow['mrp'])*100,2);

	                        		?>
	                        	<tr>
		                            <td><?php echo $orow['opp_number'];?></td>
		                            <td><?php echo $orow['product_name'];?></td>
		                            <td><?php echo $orow['required_quantity'];?></td>
		                            <td><?php echo indian_format_price(round($orow['mrp']/$orow['required_quantity'])).' '.$orow['currency_code'];?></td>
		                            <td><?php echo $discount_percentage;?></td>
									<td><?php echo $orow['warranty'];?></td>
									<td><?php echo $orow['orc'];?></td>
		                            <td><?php echo indian_format_price(round($order_value/$orow['required_quantity'])).' '.$orow['currency_code'];?></td>
		                            <td><?php echo indian_format_price(round($orow['dp']/$orow['required_quantity'])).' '.$orow['currency_code'];?></td>
		                        </tr>
	                        		<?php
	                        	}
	                        	?>
	                        </tbody>
	                    </table>
					</div>
					<div class="row"><br>
						<div class="col-md-6">
							<table class="kstyle">
								<tbody>
									<tr>
										<td class="data-lable" width="50%">Order Value</td>
										<td class="data-item" width="50%"><?php echo indian_format_price(round($ma_data['order_value'])).' '.$ma_data['currency_code'];?></td>
									</tr>
									<tr>
										<td class="data-lable">Net Selling Price</td>
										<td class="data-item"><?php echo indian_format_price(round($ma_data['net_selling_price'])).' '.$ma_data['currency_code'];?></td>
									</tr>
									<tr>
										<td class="data-lable">Discount %</td>
										<td class="data-item"><?php echo $ma_data['discount_percenrage'].'%'?></td>
									</tr>
									
									<!-- <tr>
										<td class="data-lable">Warranty (YEARS)</td>
										<td class="data-item"><?php //if($ma_data['total_warranty_in_years']!=''){ echo $ma_data['total_warranty_in_years'].' '; echo ($ma_data['total_warranty_in_years']>1)?'YEARS':'YEAR';}?></td>
									</tr> -->
									<tr>
										<td class="data-lable">Advance Collected %</td>
										<td class="data-item"><?php echo round($ma_data['advance'],2).'%';?></td>
									</tr>
									<tr>
										<td class="data-lable">Balance Payment (DAYS)</td>
										<td class="data-item"><?php if($ma_data['balance_payment_days']!=''){ echo $ma_data['balance_payment_days'].' DAYS';}?></td>
									</tr>
									<!-- <tr>
										<td class="data-lable" width="50%">Commission to Dealer (%)</td>
										<td class="data-item" width="50%"><?php //if($row['dealer_commission']!=''){ echo round($row['dealer_commission'],2).'%';}?></td>
									</tr> -->
									<?php if($distributor_name!=''){ ?>
									<tr>
										<td class="data-lable">Dealer</td>
										<td class="data-item"><?php echo $distributor_name;?></td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
						<div class="col-md-6">
							<table class="kstyle">
								<tbody >
									
									
									<?php /*if($cost_of_maintaining_warranty) {?>
									<tr>
										<td class="data-lable">Cost of Maintaining Warranty (%)</td>
										<td class="data-item"><?php echo $ma_data['cost_of_maintaining_warranty'].'%';?></td>
									</tr>
									<?php } */?>
									<!-- <tr>
										<td class="data-lable">Cost of Capital (%)</td>
										<td class="data-item"><?php echo $ma_data['cost_of_capital'].'%'?></td>
									</tr> -->
									
									<tr>
										<td class="data-lable" width="50%">Cost of Warranty</td>
										<td class="data-item" width="50%"><?php echo indian_format_price($ma_data['cost_of_warranty']).' '.$ma_data['currency_code'];?></td>
									</tr>
									<?php if($ma_data['cost_of_finance']) {?>
									<tr>
										<td class="data-lable">Cost of Finance</td>
										<td class="data-item"><?php echo indian_format_price($ma_data['cost_of_finance']).' '.$ma_data['currency_code']?></td>
									</tr>
									<?php } ?>
									<tr>
										<td class="data-lable">Commission to Dealer</td>
										<td class="data-item"><?php echo indian_format_price($ma_data['cost_of_commission']).' '.$ma_data['currency_code'];?></td>
									</tr>
									<?php /*if($cost_of_commission) {?>
									<tr>
										<td class="data-lable">Cost of Commission</td>
										<td class="data-item"><?php echo indian_format_price($ma_data['cost_of_commission']);?></td>
									</tr>
									<?php }*/ ?>
									<tr>
										<td class="data-lable">Cost of Free Supply</td>
										<td class="data-item"><?php echo indian_format_price($ma_data['cost_of_free_supply']).' '.$ma_data['currency_code'];?></td>
									</tr>
									<?php if($ma_data['gross_margin_percentage']) {?>
									<tr>
										<td class="data-lable">Gross Margin %</td>
										<td class="data-item"><?php echo round($ma_data['gross_margin_percentage'],2).'%';?></td>
									</tr>
									<?php } ?>
									<?php if($ma_data['gross_margin']) {?>
									<tr>
										<td class="data-lable">Gross Margin</td>
										<td class="data-item"><?php echo indian_format_price($ma_data['gross_margin']).' '.$ma_data['currency_code'];?></td>
									</tr>
									<?php } ?>
									<?php if($ma_data['net_margin_percentage']) {?>
									<tr>
										<td class="data-lable">Net Margin %</td>
										<td class="data-item"><?php echo ($ma_data['net_margin_percentage']).'%'?></td>
									</tr>
									<?php } ?>
									<?php if($ma_data['net_margin']) {?>
									<tr>
										<td class="data-lable">Net Margin</td>
										<td class="data-item"><?php echo indian_format_price($ma_data['net_margin']).' '.$ma_data['currency_code']?></td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
							
						</div>
					</div>
					<?php
					if($ma_data['free_supply'])
					{
					?>
					<div class="row"><br>
						<div class="col-md-12 title-menu text-center">
							<h3><u>Free Supply Items</u></h3>
						</div>
						<div class="col-md-12" style="margin-top: 10px;">
							<div class="col-md-offset-2 col-md-8">
								<table id="table1" width="60%" class="kstyle" cellspacing="0" border="1">
			                        <thead>
				                        <tr>
				                            <th width="90%" class="text-center">Product</th>
				                            <th width="10%" class="text-center">Qty</th>
				                        </tr>
			                        </thead>
			                        <tbody>
			                        	<?php
			                        	foreach ($ma_data['free_supply'] as $frow) {
			                        		?>
			                        	<tr>
				                            <td><?php echo $frow['description'].' ('.$frow['name'].')';?></td>
				                            <td><?php echo $frow['quantity'];?></td>
				                        </tr>
			                        		<?php
			                        	}
			                        	?>
			                        </tbody>
			                    </table>
			                </div>
						</div>	
					</div>			
                    <?php
                	}
                    ?>
				</div>

				<?php } ?>
									<!-- quote details end -->


					                <?php
					                if($current_waiting_revision!='')
					                {
										$op_ma_results = getOpportunityMarginAnalysisStatus($current_waiting_revision);
										//print_r($current_waiting_revision);
					                	if($op_ma_results)
					                	{
					                		?>
							                <div class="form-group">
							                    <div class="col-sm-12">
							                    	<h5><b>Quote Approval Status</b></h5>
													<table class="table table-bordered hover">
														<thead>
															<tr>
																<th width="40%">Opportunity Details</th>
																<th width="7%">Discount</th>
																<th width="15%">Current Stage</th>
																<th width="20%">Status</th>
																<th width="18%">Final Approver</th>
															</tr>
														</thead>
														<tbody>
														<?php
														foreach ($op_ma_results as $op_ma_row) 
														{
															//print_r($op_ma_row);
					                						?>
					                						<tr>
																<td><?php echo $op_ma_row['opportunity']?></td>
																<td><?php echo format_advance($op_ma_row['discount'],$op_ma_row['discount_type'])?></td>
																<td><?php if($op_ma_row['status']==1&&$op_ma_row['approval_at']!=''){ echo 'At '.getRoleShortName($op_ma_row['approval_at']);}?></td>
																<td><?php echo getMarginAnalysisStatus($op_ma_row['status']); if($op_ma_row['status']==3){ echo ' At '.getRoleShortName($op_ma_row['close_at']);}?></td>
																<td><?php if($op_ma_row['close_at']!=''){ echo getRoleShortName($op_ma_row['close_at']);}?></td>
															</tr>
					                						<?php
					                					}
														?>
														</tbody>
													</table>					                    	
							                    </div>
							                </div>
					                		<?php
					                		
					                	}
					                }
					                ?>
					                
					            </div>    
				            </div>
				        </div>
					</form>
				
				</div>	
				<div class="modal-footer">
					<button type="button" class="btn btn-primary btn-flat md-close" data-dismiss="modal">Ok</button>
				</div>
			</div>	
	</div>	
</div>
<div class="md-overlay"></div>