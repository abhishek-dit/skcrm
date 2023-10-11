<?php $image1 = assets_url() . "images/skanray-logo.png"; ?>
<table  width="600" border="0" cellspacing="0" cellpadding="1"  >
    <tr>
        <td>
            <table width="590" border="1" cellspacing="0" cellpadding="1">

                <tr>
                    <td width="270" rowspan="4" colspan="2"><img src="<?php //echo @$image1; ?>" width="100" ></td>
                    <td width="270" colspan="2">
                        <table border="0" cellspacing="0" width="100%;" align="left">
                            <tr>
                                <td colspan="2" align="center" style="border-bottom:1px solid #000000;"><strong style="font-size:18px;">Contract Note</strong></td>
                            </tr>
                            <tr>
                                <td colspan="2" valign="top" align="center"  height="20"><div style="font-size:8px;"><strong>For Office use only</strong></div></td>
                            </tr>
                        </table>

                    </td>
                </tr>
                <tr>
                    
                    <td width="110" bgcolor="#F0F0F0"><strong>Order No</strong></td>
                    <td width="160" bgcolor="#F0F0F0"><?php echo @$contract_note['purchase_order_id']; ?></td>
                </tr>
                <tr>
                    <td bgcolor="#F0F0F0"><strong>Order Date</strong></td>
                    <td bgcolor="#F0F0F0"><?php echo format_date(@$contract_note['purchase_order_date']); ?></td>
                </tr>
                <tr>
                    <td width="110"><strong>Region/Branch</strong></td>
                    <td width="160"><?php echo @$contract_note['branch']; ?></td>
                </tr>
            </table>

        </td>

    </tr>
    <tr>
        <td height="0">&nbsp;</td>
    </tr>
    <tr>

        <td >
            <table width="590" border="1" cellpadding="1">
                <tr>
                    <td colspan="8" align="center" width="540"><strong style="font-size:8px; text-align:center;">Customer & Consignee (If consignee Is not same,  attaching saparate sheet with details)</strong></td>
                </tr>
                <tr>
                    <td width="110"><strong>Institution  Name</strong></td>
                    <td width="250" colspan="4"><?php echo @$contract_note['distributor_name'];?></td>
                    <td width="110" colspan="2"  bgcolor="#F0F0F0"><strong>Institution Code</strong></td>
                    <td width="70" bgcolor="#F0F0F0"><?php echo @$contract_note['employee_id'];?></td>
                    
                </tr>
                <tr>
                    <td><strong>Billing Name/Designation</strong></td>
                    <td colspan="7"><?php echo @$contract_note['person_name'];?></td>
                </tr>
                <tr>
                    <td><strong>Address Line 1</strong></td>
                    <td  colspan="7"><?php echo @$contract_note['address1'];?></td>
                </tr>
                <tr>
                    <td><strong>Address Line 2</strong></td>
                    <td  colspan="7"><?php echo @$contract_note['address2'];?></td>
                </tr>
                <tr>
                    <td><strong>District</strong></td>
                    <td width="200"><?php echo @$contract_note['city']; ?></td>
                    <td width="150"><strong>Pin Code</strong></td>
                    <td width="80"><?php echo @$customer_details['pin_code'];?></td>
                </tr>
                <tr>
                    <td width="110"><strong>State</strong></td>
                    <td colspan="7" width="430"><?php echo @$contract_note['state']; ?></td>
                </tr>
                <tr>
                    <td style=" word-wrap:break-word;"><strong>Landline Number with STD code</strong></td>
                    <td width="82"> <?php //echo @$contract_note['landline'];?></td>
                    <td width="40"><strong>Mobile No</strong></td>
                    <td  width="70" align="left"><?php echo $contract_note['mobile_no'];?></td>
                    <td width="40"><strong>Email ID</strong></td>
                    <td  width="96" align="left"><?php echo $contract_note['email_id'];?></td>
                    <td  width="25"><strong>PAN</strong></td>
                    <td  width="77"><?php echo $contract_note['pan'];?></td>
    </tr>
   			</table>
            </td>
    </tr>
    <tr>
        <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="2">&nbsp;</td>
    </tr>
    <tr>

        <td colspan="2"><strong>&nbsp;&nbsp;ORDER DETAILS</strong></td>

    </tr>

    <tr>

        <td colspan="2">
            <table width="540" border="1" cellspacing="0" cellpadding="1">
                <tr>
                    <td width="5%"><strong>S.No.</strong></td>
                    <td width="39%" align="center"><strong>Product Description</strong></td>
                    <td width="17%" align="center"><strong> Cat No</strong></td>
                    <td  width="7%" align="center"><strong>Qty</strong></td>
                    <td  width="16%"  align="center"><strong>Unit Price (Rs.)</strong></td>
                    <td  width="16%" align="center"><strong>Total Price (Rs.)</strong></td>
                </tr>
                <?php 
				$pro_cnt=1;
				$grand_total=0;
                $k = get_preference('cost_of_maintaining_warranty','margin_settings');
				foreach($product_details as $product)
                {
					$total_amt= $discounted_value = $product['total'];
                    $default_warranty = $product['default_warranty'];
                    $warranty = $product['warranty'];
                    if($warranty>$default_warranty)
                    {
                        $f = round(($warranty/12),2);
                        $extra_warranty_cost = round($product['dp']*pow((1+$k/100),($f-1)) - $product['dp']);
                        //echo $total_amt.'-->'.$discounted_value.'-->'.$f.'-->'.$k.'-->'.$extra_warranty_cost.'<Br>';
                        $total_amt += $extra_warranty_cost;
                    }
					
					?>
                <tr>
                    <td><?php echo $pro_cnt++; ?></td>
                    <td><?php echo $product['description']; ?>
                    </td>
                    <td><?php echo $product['name'];?></td>
                    <td align="center"><?php echo $product['qty'];?></td>
                    
                    <td align="right"><?php echo indian_format_price(round($total_amt/$product['qty']));?></td>
                    <td align="right"><?php echo indian_format_price($total_amt);?></td>
                    <?php $grand_total+=$total_amt;?>
                    
                </tr>
                <?php } /*exit;*/?>
                <tr>
                    <td colspan="3">&nbsp;</td>
                    <td><strong></strong></td>
                    <td align="right"><strong>Total:</strong></td>
                    <td align="right"><?php echo indian_format_price($grand_total);?></td>
                </tr>
            </table>

        </td>

    </tr>
    <tr>

        <td colspan="2"><strong>&nbsp;</strong></td>

    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>

        <td >
            <table width="540" border="1" cellspacing="0" cellpadding="1">
                <tr>
                        <?php
                        if($contract_note['advance_type']==1&&$contract_note['advance']==100)
                        {
                            ?>
                        <td width="100%" align="left"><strong>Payment terms: 100% advance along with confirmed order</strong></td>
                            <?php
                        }
                        else
                        {
                            ?>
                        <td width="100%" align="left"><strong>Payment terms: <?php echo format_advance($contract_note['advance'],$contract_note['advance_type']);?> advance and balance in <?php echo $contract_note['balance_payment_days'];?> days from invoice</strong></td>
                            <?php
                        }
                        ?>
                    </tr>
                    <tr>
                        <td align="left"><strong>Delivery: IMMEDIATE-XPS DOOR DELY</strong></td>
                    </tr>
                    <tr>
                        <td align="left"><strong>Warranty: <?php echo $contract_note['warranty'].' Months';?></strong></td>
                    </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="2"><strong>&nbsp;&nbsp;CONTRACT NOTE CREATED ON : <?php echo strtoupper(format_date($contract_note['created_time'],'d M Y'));?></strong></td>
    </tr>
    <tr>
        <td>
            <table width="540" border="1" cellspacing="0" cellpadding="2">
                <tr>
                    <td align="center" rowspan="2"><strong>Product</strong></td>
                    <td align="center" colspan="3"><strong>Approved By</strong></td>
                </tr>
                <tr>
                    <td align="center"><strong>RBH</strong></td>
                    <td align="center"><strong>NSM</strong></td>
                    <td align="center"><strong>CH</strong></td>
                </tr>
                <?php
                foreach ($quote_op_approvals as $quote_id => $row) {
                    $j=0;
                    foreach ($row['opportunities'] as $orow) {
                        ?>
                        <tr>
                            <td><?php echo $orow['product'];?></td>
                            <td><?php echo ($orow['close_at']>=7)?$approval_history[$orow['product_id']][7]:'-';?></td>
                            <td><?php echo ($orow['close_at']>=8)?$approval_history[$orow['product_id']][8]:'-';?></td>
                            <td><?php echo ($orow['close_at']==9)?$approval_history[$orow['product_id']][9]:'-';?></td>
                        </tr>
                    <?php
                        $j++;
                    }  
                }
                ?>
            </table>
        </td>
    </tr>
    <tr><td colspan="2">&nbsp;</td></tr>
    <tr><td>&nbsp;&nbsp;<strong>Form No F:5624</strong></td></tr>
</table>