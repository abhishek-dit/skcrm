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
                        <br><br>SKANRAY TECHNOLOGIES LIMITED <br><strong>CONTRACT NOTE</strong>
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
                    <td width="110"><strong>CRM-CNote Number / Date</strong></td>
                    <td width="160"><?php echo @$contract_note['cnote_number'].' / '.date('d M Y',strtotime(@$contract_note['created_time']));?></td>
                    <td width="110" ><strong>Region / Branch</strong></td>
                    <td width="160" ><?php echo @$engineer['branch'];?></td>
                </tr>
                <tr>
                    <td><strong>Engineer PS No</strong></td>
                    <td><?php echo @$engineer['employee_id']; ?></td>
                    <td ><strong>Engineer Name</strong></td>
                    <td ><?php echo @$engineer['name']; ?></td>
                </tr>
                <tr>
                    <td ><strong>Dealer Code</strong></td>
                    <td ><?php echo @$dist_row['employee_id']; ?></td>
                    <td ><strong>Dealer Name</strong></td>
                    <td ><?php echo @$dist_row['distributor_name']; ?></td>
                </tr>
                <tr>
                    <td ><strong>Purchase Order No</strong></td>
                    <td><?php echo @$contract_note['purchase_order_no'];?></td>
                    <td width="110" ><strong>Quote No</strong></td>
                    <td width="160" ><?php echo $quotes[0]['quote_number'] .' - Rev - '.$rev; ?></td>
                </tr>
                <tr>
                    <td ><strong>Date of Purchase Order</strong></td>
                    <td><?php echo date('d M Y',strtotime(@$contract_note['date_of_purchase_order']));?></td>
                    <td ><strong>Date of Quote</strong></td>
                    <td ><?php echo date('d M Y',strtotime(@$quote_date)); ?></td>
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
                <!-- <tr>
                    <td colspan="6" align="left"><strong style="font-size:8px;">Customer    ( Sold to Party) </strong></td>
                    <td colspan="6" align="left"><strong style="font-size:8px;">Consignee   (Ship to Party)</strong></td>
                </tr> -->
                <tr>
                    <td width="270"><strong>Customer    ( Sold to Party)</strong></td>
                    <td width="270" ><strong>Consignee   (Ship to Party)</strong></td>
                </tr>
                <!-- <tr>
                    <td width="110"><strong>Institution Name</strong></td>
                    <td width="250" colspan="4"><?php echo @$customer_details['name'];?></td>
                    <td width="110" colspan="2"  ><strong>Institution Code</strong></td>
                    <td width="70" ><?php echo @$contract_note['institution_code'];?></td>
                </tr> -->
                <!-- <tr>
                    <td width="110"><strong>Institution Name</strong></td>
                    <td width="160"><?php //echo @$customer_details['name']; ?></td>
                    <td width="110" ><strong>Institution Name</strong></td>
                    <td width="160" ><?php //echo @$shipToParty['name'];?></td>
                </tr> -->
                <tr>
                    <td width="110"><strong>Customer Name</strong></td>
                    <td width="160"><?php echo @$customer_details['name']; ?></td>
                    <td width="110"><strong>Customer Name</strong></td>
                    <td width="160"><?php echo @$shipToParty[0]['name'];?></td>
                </tr>
                <tr>
                    <td width="110"><strong>Address Line 1</strong></td>
                    <td width="160" colspan="7"><?php echo @$customer_details['address1'];?></td>
                    <td width="110"><strong>Address Line 1</strong></td>
                    <td width="160"  colspan="7"><?php echo @$shipToParty[0]['address1'];?></td>
                </tr>
                <tr>
                    <td width="110"><strong>Address Line 2</strong></td>
                    <td width="160" colspan="4"><?php echo @$customer_details['address2']; ?></td>
                    <td width="110" colspan="2"><strong>Address Line 2</strong></td>
                    <td width="160" colspan="1"><?php echo @$shipToParty[0]['address2'];?></td>
                </tr>
                <tr>
                    <td width="110"><strong>City / PIN</strong></td>
                    <td width="160" colspan="4"><?php //echo @$customer_details['pincode']; 
                    if(@$customer_details['pincode'] != null && @$customer_details['city'])
                    {
                        echo @$customer_details['city'].' / '. @$customer_details['pincode'];
                    }
                     else
                     { 
                         echo @$customer_details['pincode'].''.@$customer_details['city'];
                    }
                    ?></td>
                    <td width="110" colspan="2"><strong>City / PIN</strong></td>
                    <td width="160" colspan="1"><?php //echo @$shipToParty['pincode'];
                    if(@$shipToParty[0]['pincode'] != null && @$shipToParty[0]['city'])
                    {
                        echo @$shipToParty[0]['city'].' / '. @$shipToParty[0]['pincode'];
                    }
                     else
                     { 
                         echo @$shipToParty[0]['pincode'].''.@$shipToParty[0]['city'];
                    }
                    ?></td>
                </tr>
                <tr>
                    <td width="110"><strong>District / State</strong></td>
                    <td width="160" colspan="4"><?php echo @$customer_details['district'].' / '.@$customer_details['state']; ?></td>
                    <td width="110" colspan="2"><strong>District / State</strong></td>
                    <td width="160" colspan="1"><?php echo @$shipToParty[0]['district'].' / '.@$shipToParty[0]['state'];;?></td>
                </tr>
                <tr>
                    <td width="110"><strong>Email</strong></td>
                    <td width="160" colspan="4"><?php echo $customer_details['email']; ?></td>
                    <td width="110" colspan="2"><strong>Email</strong></td>
                    <td width="160" colspan="1"><?php echo @$shipToParty[0]['email'];?></td>
                </tr>
                <tr>
                    <td width="110"><strong>Tel / Mobile</strong></td>
                    <td width="160" colspan="4"><?php //echo $customer_details['telMobile']; 
                    if(@$customer_details['landline'] != null && @$customer_details['mobile'])
                    {
                        echo @$customer_details['landline'].' / '. @$customer_details['mobile'];
                    }
                     else
                     { 
                         echo @$customer_details['landline'].''.@$customer_details['mobile'];
                    }
                    ?></td>
                    <td width="110" colspan="2"><strong>Tel / Mobile</strong></td>
                    <td width="160" colspan="1"><?php echo @$shipToParty[0]['telMobile'];
                    if(@$shipToParty[0]['landline'] != null && @$shipToParty[0]['mobile'])
                    {
                        echo @$shipToParty[0]['landline'].' / '. @$shipToParty[0]['mobile'];
                    }
                     else
                     { 
                         echo @$shipToParty[0]['landline'].''.@$shipToParty[0]['mobile'];
                    }
                    ?></td>
                </tr>
                <tr>
                    <td width="110"><strong>PAN / GST</strong></td>
                    <td width="160" colspan="4"><?php //echo @$customer_details['gst']; 
                    if(@$customer_details['pan'] != null && @$customer_details['gst'])
                    {
                        echo @$customer_details['pan'].' / '. @$customer_details['gst'];
                    }
                     else
                     { 
                         echo @$customer_details['pan'].''.@$customer_details['gst'];
                    }
                    ?></td>
                    <td width="110" colspan="2"><strong>PAN / GST</strong></td>
                    <td width="160" colspan="1"><?php //echo @$shipToParty[0]['gst'];
                    if(@$shipToParty[0]['pan'] != null && @$shipToParty[0]['gst'])
                    {
                        echo @$shipToParty[0]['pan'].' / '. @$shipToParty[0]['gst'];
                    }
                     else
                     { 
                         echo @$shipToParty[0]['pan'].''.@$shipToParty[0]['gst'];
                    }
                    ?></td>
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
                    <td width="35%" align="center"><strong>Product Description</strong></td>
                    <td width="18%" align="center"><strong> Part No</strong></td>
                    <td width="9%" align="center"><strong> Warranty(Months)</strong></td>
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
                    <td><?php echo @$product['wrrnty'];?></td>
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
                    <td align="left" colspan="5"><strong>Total Rs:</strong></td>
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
                                    $cnote_data1 = array($cnote_data);
                                    foreach($cnote_data1 as $cnote)
                                    {
                                        
                                    ?>
                                        <tr>
                                            <td width="110"><strong>Payment Terms</strong></td>
                                            <td width="430"></td>
                                        </tr>
                                        <tr>
                                            <td width="110">Advance</td>
                                            <td width="150"><?php if(@$quotes[0]['advance_type'] == 1){echo @$quotes[0]['advance'].' %';}else{echo indian_format_price('Rs '. @$quotes[0]['advance']);} ?></td>
                                            <td width="140">Balance Payment In</td>
                                            <td width="140"><?php  
                                            if(@$quotes[0]['balance_payment_days'] != '')
                                            {
                                                echo (@$quotes[0]['balance_payment_days']. ' days');
                                            }
                                            else
                                            {
                                                echo '0 days';
                                            } ?></td>
                                        </tr>
                                        <tr>
                                            <td width="110">Delivery Period</td>
                                            <td width="150"><?php echo $cnote['delivery_period']; ?></td>
                                            <td width="140">LD Applicable/Date</td>
                                            <td width="140"><?php if($cnote['ld_applicable_date']!='0000-00-00' || $cnote['ld_applicable_date']!=''){ echo 'Yes / '.$cnote['ld_applicable_date']; } else {echo '';} ?></td>
                                        </tr>
                                        <!-- <tr>
                                            <td>Warranty</td>
                                            <td width="150"><?php //echo $cnote['warranty']; ?></td>
                                            <td width="140"></td>
                                            <td width="140"></td>
                                        </tr> -->
                                        <tr>
                                            <td>Other Conditions (If any)</td>
                                            <td width="430"><?php echo $cnote['other_conditions']; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Amendment</td>
                                            <td width="430"><?php echo $cnote['amendment']; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Reason for Amendment</td>
                                            <td width="430"><?php echo $cnote['reason_for_amendment']; ?></td>
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
                    <td>The undersigned hereby orders that afore-mentioned goods from Skanray Technologies Limited (Healthcare Division). The goods specified above to be delivered as per the condition of sales and terms of business set out in this contract. Seller's terms of business as printed overleaf are considered to form part of contract unless expressly overruled by any of the conditions stipulated therein.</td>
                </tr>
                <tr>
                    <td width="110" rowspan="3" align="center" style="margin-top: 40px;"><strong>Acceptance of Order</strong></td>
                    <td width="140" height="50"></td>
                    <td width="150" height="50">
                    Digitally Accepted by
                    <!-- <p> RBH- <?php //echo "<pre>";print_r($approval_history);die; ?></p>
                    <p> NSM- </p>
                    <p> CH- </p> -->
                    <?php

                    if(isset($approval_history) && !empty($approval_history))
                    {
                        foreach ($approval_history as $key => $value) 
                        {
                            
                            foreach ($value as $key1 => $value1) {
                                foreach ($value1 as $key2 => $value2) {
                                    // echo "<pre>";print_r($key2);
                                    if($key2 == '7')
                                    {
                                        $role = 'RBH-';
                                    }
                                    elseif($key2 == '9')
                                    {
                                        $role = 'CH-';
                                    }
                                    elseif($key2 == '8')
                                    {
                                        $role = 'NSM-';
                                    }
                                     ?>
                                    <p><?php echo $role." ". $value2;?></p>
                                    
                            <?php }break;
                            }
                        }
                    }
                    ?>
                    </td>
                    <td width="140" height="50"></td>
                </tr>
                <tr>
                    <td width="140">Customer Signature and seal</td>
                    <td width="150">Accepted on behalf of Skanray Technologies Limited</td>
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
    <tr><td>&nbsp;&nbsp;<strong>Form No :3F5007_D Rev# 5.0</strong></td></tr>
    <!-- <tr><td>&nbsp;&nbsp;Note: E for Exports/ D for Domestic</td></tr> -->
    <tr><td style="font-size:5px;font-weight:bold;display: inline-block; padding: 5px;">&nbsp;&nbsp;Note:Effective from 1st Oct 2020 , as per Govt Of India Notification TCS ( Tax collection at Source) will be charged if applicable at the rate of 0.1% if PAN / Aadhaar is submited other wise 1%</td></tr>
</table>