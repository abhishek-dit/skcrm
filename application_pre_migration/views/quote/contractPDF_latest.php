<?php $image1 = assets_url() . "images/skanray-logo.png"; ?>
<table  width="600" border="0" cellspacing="0" cellpadding="1"  >
    <tr>
        <td>
            <table width="590" border="1" cellspacing="0" cellpadding="1">
                <tr>
                    <td width="70">
                        <img src="<?php echo FCPATH.'application/assets/quote_header_img.txt' ?>" width="100" >
                    </td>
                    <td width="470" align="center" style="font-size:12px;">
                        <br><br>SKANRAY TECHNOLOGIES PVT.LTD <br><strong>CONTRACT NOTE</strong>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td height="0">&nbsp;</td>
    </tr>
    <tr>
        <td>
            <table width="590" border="1" cellspacing="0" cellpadding="1">
                <tr height="25">
                    <td colspan="8" align="center" width="540"><strong style="font-size:8px; text-align:left;">For Office Use Only</strong></td>
                </tr>
                <tr>
                    <td width="110"><strong>Region/Branch</strong></td>
                    <td width="160"><?php echo @$engineer['branch']; ?></td>
                    <td width="110" bgcolor="#F0F0F0"><strong>Engineer Name</strong></td>
                    <td width="160" bgcolor="#F0F0F0"><?php echo @$engineer['name'];?></td>
                </tr>
                <tr>
                    <td><strong>Distributor Name</strong></td>
                    <td><?php echo @$dist_row['distributor_name'] ?></td>
                    <td bgcolor="#F0F0F0"><strong>Engineer Ps No</strong></td>
                    <td bgcolor="#F0F0F0"><?php echo @$engineer['employee_id']; ?></td>
                </tr>
                <tr>
                    <td ><strong>Distributor Code
                            <!--/Employee Id-->
                        </strong></td>
                    <td ><?php echo @$dist_row['employee_id']; ?></td>
                    
                </tr>
                <tr>
                    <td ><strong>Purchase Order No</strong></td>
                    <td><?php echo @$contract_note['purchase_order_no'];?></td>
                    <td width="110" bgcolor="#F0F0F0"><strong>Quote No</strong></td>
                    <td width="160" bgcolor="#F0F0F0"><?php echo $quote_val; ?></td>
                </tr>
                <tr>
                    <td ><strong>Date of Purchase Order</strong></td>
                    <td><?php echo date('d M Y',strtotime(@$contract_note['date_of_purchase_order']));?></td>
                    <td bgcolor="#F0F0F0"><strong>Date of Quote</strong></td>
                    <td bgcolor="#F0F0F0"><?php echo $quote_date; ?></td>
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
                    <td colspan="8" align="left" width="540"><strong style="font-size:8px;">Customer & Consignee (If consignee Is not same,  attaching saparate sheet with details)</strong></td>
                </tr>
                <tr>
                    <td width="110"><strong>Institution  Name</strong></td>
                    <td width="250" colspan="4"><?php echo @$customer_details['name'];?></td>
                    <td width="110" colspan="2"  bgcolor="#F0F0F0"><strong>Institution Code</strong></td>
                    <td width="70" bgcolor="#F0F0F0"><?php echo @$contract_note['institution_code'];?></td>
                </tr>
                <tr>
                    <td><strong>Billing Name/Designation</strong></td>
                    <td colspan="7"><?php echo @$contract_note['billing_to_party'];?></td>
                </tr>
                <tr>
                    <td><strong>Address Line 1</strong></td>
                    <td width="200" colspan="7"><?php echo @$customer_details['address1'];?></td>
                    <td width="100"><strong>Address Line 2</strong></td>
                    <td width="130"  colspan="7"><?php echo @$customer_details['address2'];?></td>
                </tr>
                <tr>
                    <td><strong>District</strong></td>
                    <td width="200" colspan="4"><?php echo @$customer_details['district']; ?></td>
                    <td width="100" colspan="2"><strong>Pin Code</strong></td>
                    <td width="130" colspan="1"><?php echo @$customer_details['pin_code'];?></td>
                </tr>
                <tr>
                    <td><strong>State</strong></td>
                    <td colspan="7"><?php echo @$customer_details['state']; ?></td>
                </tr>
                <tr>
                    <td  style="word-wrap:break-word;"><strong>Landline Number with STD code</strong></td>
                    <td  width="82"> <?php echo @$customer_details['landline'];?></td>
                    <td  width="40"><strong>Mobile No</strong></td>
                    <td  width="70" align="left"><?php echo $customer_details['mobile'];?></td>
                    <td  width="40"><strong>Email ID</strong></td>
                    <td  width="96" align="left"><?php echo $customer_details['email'];?></td>
                    <td  width="25"><strong>PAN</strong></td>
                    <td  width="77"><?php echo $customer_details['pan'];?></td>
                </tr>
                <tr>
                    <td><strong>GST Number</strong></td>
                    <td colspan="7"><?php echo @$customer_details['gst'];?></td>
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

        <td colspan="2">
            <table width="540" border="1" cellspacing="0" cellpadding="1">
                <tr>

                    <td align="left"><strong>ORDER DETAILS</strong></td>

                </tr>
                <tr>
                    <td width="5%"><strong>S.No.</strong></td>
                    <td width="42%" align="center"><strong>Product Description</strong></td>
                    <td width="20%" align="center"><strong> Part No</strong></td>
                    <td  width="5%" align="center"><strong>Qty</strong></td>
                    <td  width="14%"  align="center"><strong>Unit Price (<?php echo 'Rs'; ?>)</strong></td>
                    <td  width="14%" align="center"><strong>Total Price (<?php echo 'Rs'; ?>.)</strong></td>
                </tr>
                <?php 
				$pro_cnt=1;
				$grand_total=0;
				foreach($product_details as $product)
                {
					$total_amt=0;
					
					?>
                <tr>
                    <td><?php echo $pro_cnt++; ?></td>
                    <td><?php
                                echo $product['description'];
                         ?>
                    </td>
                    <td><?php echo $product['name'];?></td>
                    <td align="center"><?php echo $product['qty'];?></td>
                     <?php
                    switch ($tax_type) {
                        case 1: // ED, VAT
                            ?>
                            <td align="right"><?php echo indian_format_price(round($product['total1']/$product['qty']));?></td>
                            <td align="right"><?php echo indian_format_price($product['total1']);?></td>
                            <?php
                            $grand_total+=$product['total1'];
                        break;
                        case 2: // GST
                        ?>
                            <td align="right"><?php echo indian_format_price(round($product['total']/$product['qty']));?></td>
                            <td align="right"><?php echo indian_format_price($product['total']);?></td>
                        <?php
                            $grand_total+=$product['total'];
                        break;
                    }
                    ?>
                    
                </tr>
                <?php } ?>
                <tr>
                    <td align="left" colspan="4"><strong>Total Rs:</strong></td>
                    <td align="right"><strong></strong></td>
                    <td align="right"><?php echo indian_format_price($grand_total);?></td>
                </tr>
            </table>

        </td>

    </tr>
    <tr>

        <td colspan="2"><strong>&nbsp;</strong></td>

    </tr>
    <tr>

        <td >
            <table width="540" border="1" cellspacing="0" cellpadding="1">
                <tr>
                    <td width="7%"><strong>S.No.</strong></td>
                    <td width="80%" align="center"><strong>Free Supply Items</strong></td>
                     <td  width="13%" align="center"><strong>Qty</strong></td>
                    
                </tr>
                <?php $p = 0;
                foreach($freeProducts as $row) 
                {
                    ?>
                    <tr>
                        <td><?php echo ++$p; ?></td>
                        <td><?php echo $row['description']; ?></td>
                        <td align="center"><?php echo $row['quantity']; ?></td>
                        
                    </tr>
                    <?php 
                } 
                if($p == 0)
                {
                    ?>
                    <tr>
                        <td></td>
                        <td></td>
                        <td align="center"></td>
                    </tr>
                    <?php
                }    ?>
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

                        <td >
                            <table width="540" border="1" cellspacing="0" cellpadding="1">
                            <?php
                            switch ($quote_format_type) {
                                case 1: // Old CNote Format
                                   ?>
                                    <tr>
                                        <td rowspan="3" valign="top" width="30%"><strong>Conditions of Contract</strong></td>
                                        <td width="70%" align="center"><strong>Payment: Within 30 Days OF DELIVERY</strong></td>
                                    </tr>
                                    <tr>
                                        <td align="center"><strong>Delivery: IMMEDIATE-XPS DOOR DELY</strong></td>
                                    </tr>
                                    <tr>
                                        <td align="center"><strong>Warranty: ONE YEAR</strong></td>
                                    </tr>
                                   <?php
                                break;
                                case 3: // New CNote Format
                                    $rowspan = (@$lead_user['role_id']!=5)?4:3;
                                   ?>
                                    <tr>
                                        <td width="110"><strong>Payment Terms</strong></td>
                                        <td width="430"></td>
                                    </tr>
                                    <tr>
                                        <td width="110">Delivery Period</td>
                                        <td width="150"><?php echo $cnote_data['delivery_period']; ?></td>
                                        <td width="140">LD Applicable/Date</td>
                                        <td width="140"><?php if($cnote_data['ld_applicable_date']!='0000-00-00' || $cnote_data['ld_applicable_date']!=''){ echo 'Yes / '.$cnote_data['ld_applicable_date']; } else {echo '';} ?></td>
                                    </tr>
                                    <tr>
                                        <td>Warranty</td>
                                        <td width="150"><?php echo $cnote_data['warranty']; ?></td>
                                        <td width="140"></td>
                                        <td width="140"></td>
                                    </tr>
                                    <tr>
                                        <td>Other Conditions (If any)</td>
                                        <td width="430"><?php echo $cnote_data['other_conditions']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Amendment</td>
                                        <td width="430"><?php echo $cnote_data['amendment']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Reason for Amendment</td>
                                        <td width="430"><?php echo $cnote_data['reason_for_amendment']; ?></td>
                                    </tr>

                                   <?php
                                    if(@$lead_user['role_id']!=5&&@$quotes[0]['dealer_commission']>0) // If lead owner is not Distributor
                                    {
                                        ?>
                                        <tr>
                                            <td align="left"><strong>Distibutor ORC: <?php echo round($quotes[0]['dealer_commission'],2).'%';?></strong></td>
                                        </tr>
                                        <?php
                                    }
                                break;
                            }
                            ?>
                            </table>
                        </td>
                    </tr>
    <tr>
        <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="2">&nbsp;</td>
    </tr>
    <!-- <tr>
        <td colspan="2"><strong>&nbsp;&nbsp;CONTRACT NOTE CREATED ON : <?php echo strtoupper(format_date($cnote_date,'d M Y'));?></strong></td>
    </tr>
    <tr>
        <td>
            <table width="540" border="1" cellspacing="0" cellpadding="2">
                <tr>
                    <td rowspan="2"><strong>Quote ID</strong></td>
                    <td rowspan="2"><strong>Opportunity ID</strong></td>
                    <td align="center" colspan="4"><strong>Approved By</strong></td>
                </tr>
                <tr>
                    <td align="center"><strong>SE</strong></td>
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
                            <?php if($j==0) {?>
                            <td rowspan="<?php echo count($row['opportunities']);?>"><?php echo $quote_id;?></td>
                            <?php } ?>
                            <td><?php echo $orow['opportunity_id'];?></td>
                            <td><?php echo ($orow['close_at']=='')?$orow['se']:'-';?></td>
                            <td><?php echo ($orow['close_at']>=7)?@$approval_history[$quote_id][$orow['opportunity_id']][7]:'-';?></td>
                            <td><?php echo ($orow['close_at']>=8)?@$approval_history[$quote_id][$orow['opportunity_id']][8]:'-';?></td>
                            <td><?php echo ($orow['close_at']==9)?@$approval_history[$quote_id][$orow['opportunity_id']][9]:'-';?></td>
                        </tr>
                    <?php
                        $j++;
                    }  
                }
                ?>
            </table>
        </td>
    </tr> -->
    <tr>
        <td>
            <table width="540" border="1" cellspacing="0" cellpadding="2">
                <tr>
                    <td>The undersigned hereby orders that afore-mentioned goods from Skanray Technologies Pvt.Ltd (Healthcare Division). The goods specified above to be delivered as per the condition of sales and terms of business set out in this contract. Seller's terms of business as printed overleaf are considered to form part of contract unless expressly overruled by any of the conditions stipulated therein.</td>
                </tr>
                <tr>
                    <td width="110" rowspan="3" align="center" style="margin-top: 40px;"><strong>Acceptance of Order</strong></td>
                    <td width="140" height="50"></td>
                    <td width="150" height="50"></td>
                    <td width="140" height="50"></td>
                </tr>
                <tr>
                    <td width="140">Customer Signature and seal</td>
                    <td width="150">Accepted on behalf of Skanray Technologies Pvt.Ltd</td>
                    <td width="140">Regulatory Approval (If Applicable)</td>
                </tr>
                <tr>
                    <td width="140">Date:</td>
                    <td width="150">Date:</td>
                    <td width="140">Date:</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr><td colspan="2">&nbsp;</td></tr>
    <tr><td>&nbsp;&nbsp;<strong>Form No :3F5007_D Rev# 2.0</strong></td></tr>
    <tr><td>&nbsp;&nbsp;Note: E for Exports/ D for Domestic</td></tr>
</table>