<?php $image1 = assets_url() . "images/skanray-logo.png"; ?>
<table  width="600" border="0" cellspacing="0" cellpadding="1"  >
    <tr>
        <td>
            <table width="590" border="1" cellspacing="0" cellpadding="1">

                <tr>
                    <td width="270" colspan="2"><img src="<?php //echo @$image1; ?>" width="100" ></td>
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
                    <td width="110"><strong>Region/Branch</strong></td>
                    <td width="160"></td>
                    <td width="110" bgcolor="#F0F0F0"><strong>Order No</strong></td>
                    <td width="160" bgcolor="#F0F0F0">&nbsp;</td>
                </tr>
                <tr>
                    <td><strong>Distributor Name</strong></td>
                    <td></td>
                    <td bgcolor="#F0F0F0"><strong>Order Date</strong></td>
                    <td bgcolor="#F0F0F0">&nbsp;</td>
                </tr>
                <tr>
                    <td ><strong>Distributor Code
                            <!--/Employee Id-->
                        </strong></td>
                    <td ></td>
                    <td bgcolor="#F0F0F0"><strong>Engineer Name</strong></td>
                    <td bgcolor="#F0F0F0"></td>
                </tr>
                <tr>
                    <td ><strong>Purchase Order No</strong></td>
                    <td></td>
                    <td bgcolor="#F0F0F0"><strong>Engineer Ps No</strong></td>
                    <td bgcolor="#F0F0F0"></td>
                </tr>
                <tr>
                    <td ><strong>Date of Purchase Order</strong></td>
                    <td></td>
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
                    <td width="250" colspan="4"></td>
                    <td width="110" colspan="2"  bgcolor="#F0F0F0"><strong>Institution Code</strong></td>
                    <td width="70" bgcolor="#F0F0F0"></td>
                    
                </tr>
                <tr>
                    <td><strong>Billing Name/Designation</strong></td>
                    <td colspan="7"></td>
                </tr>
                <tr>
                    <td><strong>Address Line 1</strong></td>
                    <td  colspan="7"></td>
                </tr>
                <tr>
                    <td><strong>Address Line 2</strong></td>
                    <td  colspan="7"></td>
                </tr>
                <tr>
                    <td><strong>District</strong></td>
                    <td width="200" colspan="4"></td>
                    <td width="150" colspan="2"><strong>Pin Code</strong></td>
                    <td width="80" colspan="1"></td>
                </tr>
                <tr>
                    <td><strong>State</strong></td>
                    <td colspan="7"></td>
                </tr>
                <tr>
                    <td style="word-wrap:break-word;"><strong>Landline Number with STD code</strong></td>
                    <td width="82"> </td>
                    <td width="40"><strong>Mobile No</strong></td>
                    <td  width="70" align="left"></td>
                    <td width="40"><strong>Email ID</strong></td>
                    <td  width="96" align="left"></td>
                    <td  width="25"><strong>PAN</strong></td>
                    <td  width="77"></td>
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
                    <td width="10%"><strong>Opp ID</strong></td>
                    <td width="37%" align="center"><strong>Product Description</strong></td>
                    <td width="15%" align="center"><strong> Cat No</strong></td>
                    <td  width="5%" align="center"><strong>Qty</strong></td>
                    <td  width="14%"  align="center"><strong>Unit Price (Rs.)</strong></td>
                    <td  width="14%" align="center"><strong>Total Price (Rs.)</strong></td>
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
                    <td></td>
                    <td>
                    </td>
                    <td></td>
                    <td align="center"></td>
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
                            <td align="right"></td>
                            <td align="right"></td>
                        <?php
                            $grand_total+=$product['total'];
                        break;
                    }
                    ?>
                    
                </tr>
                <?php } ?>
                <tr>
                    <td colspan="4">&nbsp;</td>
                    <td><strong></strong></td>
                    <td align="right"><strong>Total:</strong></td>
                    <td align="right"></td>
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
                    <td width="65%" align="center"><strong>Free Supply Items</strong></td>
                     <td  width="20%"  align="center"><strong>Part No.</strong></td>
                     <td  width="8%" align="center"><strong>Qty</strong></td>
                    <!-- <td  width="15%"  align="center"><strong>Unit Price (Rs.)</strong></td>
                    <td  width="17%" align="center"><strong>Total Price (Rs.)</strong></td> -->
                    
                </tr>
                <?php $p = 0;
                foreach($freeProducts as $row) 
                {
                    ?>
                    <tr>
                        <td><?php echo ++$p; ?></td>
                        <td><?php echo $row['description']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        
                        <td align="center"><?php echo $row['quantity']; ?></td>
                        <!-- <td align="right"><?php echo indian_format_price($row['unit_price']); ?></td>
                        <td align="right"><?php echo indian_format_price($row['unit_price'] * $row['quantity']); ?></td> -->
                        
                    </tr>
                    <?php 
                } 
                if($p == 0)
                {
                    ?>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td align="center"></td>
                        <!-- <td align="right"></td>
                        <td align="right"></td> -->
                        
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
                                case 2: // New CNote Format
                                    $rowspan = (@$lead_user['role_id']!=5)?4:3;
                                   ?>
                                    <tr>
                                        <!-- <td rowspan="<?php echo $rowspan;?>" valign="top" width="30%"><strong>Conditions of Contract</strong></td> -->
                                        <?php
                                        if($quotes[0]['advance_type']==1&&$quotes[0]['advance']==100)
                                        {
                                            ?>
                                        <td width="100%" align="left"><strong>Payment terms: 100% advance along with confirmed order</strong></td>
                                            <?php
                                        }
                                        else
                                        {
                                            ?>
                                        <td width="100%" align="left"><strong>Payment terms: ____ advance and balance in ____ days from invoice</strong></td>
                                            <?php
                                        }
                                        ?>
                                    </tr>
                                    <tr>
                                        <td align="left"><strong>Delivery: IMMEDIATE-XPS DOOR DELY</strong></td>
                                    </tr>
                                    <tr>
                                        <td align="left"><strong>Warranty: <?php echo '[WARRANTY] Months';?> from date of invoice</strong></td>
                                    </tr>

                                   <?php
                                    //if(@$lead_user['role_id']!=5&&@$quotes[0]['dealer_commission']>0) // If lead owner is not Distributor
                                    {
                                        ?>
                                        <tr>
                                            <td align="left"><strong>Distibutor ORC: </strong></td>
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
    <tr>
        <td colspan="2"><strong>&nbsp;&nbsp;CONTRACT NOTE CREATED ON : </strong></td>
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
                            <td rowspan="<?php echo count($row['opportunities']);?>"></td>
                            <?php } ?>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
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