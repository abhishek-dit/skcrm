<!--<div class="md-modal colored-header custom-width md-effect-9" id="<?php //echo @$pop_id;?>">-->
<?php
$role_id = $this->session->userdata('role_id');
switch ($role_id) {
	case 7:
		$show_gross_margin = $show_gross_margin_percentage = $cost_of_maintaining_warranty = $cost_of_finance = $cost_of_commission = $cost_of_free_supply = $net_margin = $net_margin_percentage = false;
	break;
	case 8:
		$show_gross_margin = $show_gross_margin_percentage = $cost_of_finance = $cost_of_commission = $cost_of_free_supply = $net_margin = $net_margin_percentage = true;
		$cost_of_maintaining_warranty = false;
	break;
	case 9:
		$show_gross_margin = $show_gross_margin_percentage = $cost_of_maintaining_warranty = $cost_of_finance = $cost_of_commission = $cost_of_free_supply = $net_margin = $net_margin_percentage = true;
	break;
}
?>
<div class="modal fade colored-header" id="info<?php echo @$row['approval_id'];?>" role="dialog">
	<div class="modal-dialog">
			<div class="md-content">
				<div class="modal-header">
					<div class="text-center">
						<span style="font-size:22px">Margin Analysis Report</span>
						<button type="button" class="close md-close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					
				</div>
				<div class="m-header">
					<div class="row">
						<div class="col-md-offset-1 col-md-8" style="margin-top: 7px;">
							<label>Purchase Order ID <span style="margin-left:10px;">:</span><span style="margin-left: 15px;"><?php echo $row['po_number'];?></span></label>
						</div>
					</div>
				</div>

				<div class="modal-body form">
					<div class="row">
						<!-- <label class="col-md-3">Opportunity</label>
						<div class="col-md-9"><?php echo $row['opportunity'];?></div> -->
						<table id="table1" style="width:100%" class="kstyle" cellspacing="0" border="1">
	                        <thead>
		                        <tr>
		                            <th class="text-center">Product</th>
		                            <th class="text-center">Qty</th>
		                            <th class="text-center">Unit MRP</th>
		                            <th class="text-center">Discount %</th>
		                            <th class="text-center">Quoted Price</th>
		                            <th class="text-center">DP</th>
		                        </tr>
	                        </thead>
	                        <tbody>
	                        	<?php
	                        	foreach ($products as $orow) {
	                        		$order_value = $orow['mrp'];
                                    $discount = $orow['discount'];
                                    if($orow['discount_type']!=''&&$discount!='')
                                    $order_value = ($orow['discount_type']==1)?($order_value*(1-$orow['discount']/100)):($order_value-$discount);
                                    $discount_percentage = round((($orow['mrp'] - $order_value )/$orow['mrp'])*100,2);

	                        		?>
	                        	<tr>
		                            <td><?php echo $orow['product_name'];?></td>
		                            <td><?php echo $orow['qty'];?></td>
		                            <td><?php echo indian_format_price(round($orow['mrp']/$orow['qty'])).' '.$row['currency_code'];?></td>
		                            <td><?php echo $discount_percentage;?></td>
		                            <td><?php echo indian_format_price(round($order_value/$orow['qty'])).' '.$row['currency_code'];?></td>
		                            <td><?php echo indian_format_price(round($orow['dp']/$orow['qty'])).' '.$row['currency_code'];?></td>
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
										<td class="data-item" width="50%"><?php echo indian_format_price(round($ma_data['order_value'])).' '.$row['currency_code'];?></td>
									</tr>
									<tr>
										<td class="data-lable">Net Selling Price</td>
										<td class="data-item"><?php echo indian_format_price(round($ma_data['net_selling_price'])).' '.$row['currency_code'];?></td>
									</tr>
									<tr>
										<td class="data-lable">Discount %</td>
										<td class="data-item"><?php echo $ma_data['discount_percenrage'].'%'?></td>
									</tr>
									
									<tr>
										<td class="data-lable">Warranty (YEARS)</td>
										<td class="data-item"><?php if($ma_data['total_warranty_in_years']!=''){ echo $ma_data['total_warranty_in_years'].' '; echo ($ma_data['total_warranty_in_years']>1)?'YEARS':'YEAR';}?></td>
									</tr>
									<tr>
										<td class="data-lable">Advance Payment %</td>
										<td class="data-item"><?php echo round($ma_data['advance'],2).'%';?></td>
									</tr>
									<tr>
										<td class="data-lable">Balance Payment (DAYS)</td>
										<td class="data-item"><?php if($row['balance_payment_days']!=''){ echo $row['balance_payment_days'].' DAYS';}?></td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="col-md-6">
							<table class="kstyle">
								<tbody >
									<tr>
										<td class="data-lable" width="50%">Cost of Warranty</td>
										<td class="data-item" width="50%"><?php echo indian_format_price($ma_data['cost_of_warranty']).' '.$row['currency_code'];?></td>
									</tr>
									<?php if($cost_of_finance) {?>
									<tr>
										<td class="data-lable">Cost of Finance</td>
										<td class="data-item"><?php echo indian_format_price($ma_data['cost_of_finance']).' '.$row['currency_code']?></td>
									</tr>
									<?php } ?>
									<?php if($show_gross_margin_percentage) {?>
									<tr>
										<td class="data-lable">Gross Margin %</td>
										<td class="data-item"><?php echo round($ma_data['gross_margin_percentage'],2).'%';?></td>
									</tr>
									<?php } ?>
									<?php if($show_gross_margin) {?>
									<tr>
										<td class="data-lable">Gross Margin</td>
										<td class="data-item"><?php echo indian_format_price($ma_data['gross_margin']).' '.$row['currency_code'];?></td>
									</tr>
									<?php } ?>
									<?php if($net_margin_percentage) {?>
									<tr>
										<td class="data-lable">Net Margin %</td>
										<td class="data-item"><?php echo ($ma_data['net_margin_percentage']).'%'?></td>
									</tr>
									<?php } ?>
									<?php if($net_margin) {?>
									<tr>
										<td class="data-lable">Net Margin</td>
										<td class="data-item"><?php echo indian_format_price($ma_data['net_margin']).' '.$row['currency_code']?></td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
							
						</div>
					</div>
				</div>	
				<div class="modal-footer" style="padding-top: 10px; padding-bottom: 10px;">
					<!-- <span style="float:left"> <strong>Note: Margin are calculated on DP</strong></span> -->
					<button type="button" class="btn btn-primary btn-flat md-close" data-dismiss="modal">Ok</button>
				</div>
			</div>	
	</div>	
</div>
<div class="md-overlay"></div>