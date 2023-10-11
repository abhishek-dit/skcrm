<?php $image1 = assets_url() . "images/skanray-logo.png"; ?>
<table  width="600" border="0" cellspacing="0" cellpadding="1"  >
    <tr>
        <td>
            <table width="1000" border="1" cellspacing="0" cellpadding="1">

                <tr>
                    <td width="370" colspan="2"><img src="<?php //echo @$image1; ?>" width="100" ></td>
                    <td width="370" colspan="2">
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
                    <td width="160"><?php echo @$engineer['branch']; ?></td>
                    <td width="110" bgcolor="#F0F0F0"><strong>Order No</strong></td>
                    <td width="160" bgcolor="#F0F0F0">&nbsp;</td>
                </tr>
                <tr>
                    <td><strong>Distributor Name</strong></td>
                    <td><?php echo @$distributor['distributor_name'] ?></td>
                    <td bgcolor="#F0F0F0"><strong>Order Date</strong></td>
                    <td bgcolor="#F0F0F0">&nbsp;</td>
                </tr>
                <tr>
                    <td ><strong>Distributor Code
                            <!--/Employee Id-->
                        </strong></td>
                    <td ><?php echo @$distributor['employee_id']; ?></td>
                    <td bgcolor="#F0F0F0"><strong>Engineer Name</strong></td>
                    <td bgcolor="#F0F0F0"><?php echo @$engineer['name'];?></td>
                </tr>
                <tr>
                    <td ><strong>Purchase Order No</strong></td>
                    <td><?php echo @$contract_note['purchase_order_no'];?></td>
                    <td bgcolor="#F0F0F0"><strong>Engineer Ps No</strong></td>
                    <td bgcolor="#F0F0F0"><?php echo @$engineer['employee_id']; ?></td>
                </tr>
                <tr>
                    <td ><strong>Date of Purchase Order</strong></td>
                    <td><?php echo date('d M Y',strtotime(@$contract_note['date_of_purchase_order']));?></td>
                </tr>
            </table>

        </td>

    </tr>
    <tr>
        <td height="0">&nbsp;</td>
    </tr>
    <tr>

        <td >
            <table width="1000" border="1" cellpadding="1">
                <tr>
                    <td colspan="8" align="center" width="540"><strong style="font-size:8px; text-align:center;">Customer & Consignee (If consignee Is not same,  attaching saparate sheet with details)</strong></td>
                </tr>
                <tr>
                    <td width="110"><strong>Institution Name</strong></td>
                    <td width = "250" colspan="4"><?php echo @$customer_details['name'];?></td>
                    <td width="112" colspan="2"  bgcolor="#F0F0F0"><strong>Institution Code</strong></td>
                    <td bgcolor="#F0F0F0"><?php echo @$contract_note['institution_code'];?></td>
                    
                </tr>
                <tr>
                <td><strong>Billing Name/Designation</strong></td>
                    <td width="200"><?php echo @$contract_note['city']; ?></td>
                    <td width="150"><strong>Ship To Party</strong></td>
                    <td width="80"><?php echo @$contract_note['ship_to_party'];?></td>
                </tr>
                <tr>
                    <td><strong>Address Line 1</strong></td>
                    <td  colspan="7"><?php echo @$customer_details['address1'];?></td>
                </tr>
                <tr>
                    <td><strong>Address Line 2</strong></td>
                    <td  colspan="7"><?php echo @$customer_details['address2'];?></td>
                </tr>
                <tr>
                    <td><strong>District</strong></td>
                    <td width="30%"><?php echo @$customer_details['district']; ?></td>
                    <td width="20%"><strong>Pin Code</strong></td>
                    <td width="23%"><?php echo @$customer_details['pin_code'];?></td>
                </tr>
                <tr>
                    <td><strong>State</strong></td>
                    <td width="73%" colspan="7"><?php echo @$customer_details['state']; ?></td>
                </tr>
                <tr>
                    <td style=" word-wrap:break-word;"><strong>Landline Number with STD code</strong></td>
                    <td width="14%"> <?php echo @$customer_details['landline'];?></td>
                    <td width="6%"><strong>Mobile No</strong></td>
                    <td  width="14%" align="left"><?php echo $customer_details['mobile'];?></td>
                    <td width="7%"><strong>Email ID</strong></td>
                    <td  width="17%" align="left"><?php echo $customer_details['email'];?></td>
                    <td  width="4%"><strong>PAN</strong></td>
                    <td  width="11%"><?php echo $customer_details['pan'];?></td>
    </tr>
   			</table>
            </td>
            </tr>
    <tr>

        <td colspan="2"><strong>&nbsp;&nbsp;ORDER DETAILS</strong></td>

    </tr>

    <tr>

        <td colspan="2">
            <table width="900" border="1" cellspacing="0" cellpadding="1">
                <tr>
                    <td width="7%"><strong>S.No.</strong></td>
                    <td width="37%" align="center"><strong>Product Description</strong></td>
                    <td width="18%" align="center"><strong> Cat No</strong></td>
                    <td  width="6%" align="center"><strong>Qty</strong></td>
                    <td  width="15%"  align="center"><strong>Unit Price (Rs.)</strong></td>
                    <td  width="17%" align="center"><strong>Total Price (Rs.)</strong></td>
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
                    <td><?php echo $product['name'];?>
                                </td>
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

        <td >
            <table width="900" border="1" cellspacing="0" cellpadding="1">
                <tr>
                    <td width="7%"><strong>S.No.</strong></td>
                    <td width="37%" align="center"><strong>Free Supply Items</strong></td>
                     <td  width="18%"  align="center"><strong>Part No.</strong></td>
                     <td  width="6%" align="center"><strong>Qty</strong></td>
                    <td  width="15%"  align="center"><strong>Unit Price (Rs.)</strong></td>
                    <td  width="17%" align="center"><strong>Total Price (Rs.)</strong></td>
                    
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
                        <td align="right"><?php echo indian_format_price($row['rrp']); ?></td>
                        <td align="right"><?php echo indian_format_price($row['rrp'] * $row['quantity']); ?></td>
                        
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
                        <td align="right"></td>
                        <td align="right"></td>
                        
                    </tr>
                    <?php
                }    ?>
            </table>

        </td>

    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>

                        <td >
                            <table width="900" border="1" cellspacing="0" cellpadding="1">
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
                                <tr>
                                    <td><strong>Additional conditions of the<br />
                                            contract, if any</strong></td>
                                    <td>&nbsp;</td>
                                </tr>
                            </table>


                        </td>

                    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>

        <td >
            <table width="900" border="1" cellspacing="0" cellpadding="2">
                <tr>
                    <td colspan="3"><div style="padding:10px; text-align:justify; font-weight:bold;">The undersigned hereby orders the afore-mentioned goods from Skanray Technologies Limited. The goods specified above to be delivered as per the conditions of sales and terms of business set out in this contract. Seller\'s terms of business as printed overleaf are considered to form part of contract unless expressly overruled by any of the conditions stipulated therein.</div>

                    </td>
                </tr>
                <tr>
                    <td rowspan="3" width="29%" valign="top"><strong>Acceptance</strong></td>
                    <td width="29%" height="100">&nbsp;</td>
                    <td width="42%">&nbsp;</td>
                </tr>
                <tr>
                    <td><strong>Customer's signature and seal</strong></td>
                    <td><strong>Accepted on behalf of Skanray Technologies</strong></td>
                </tr>
                <tr>
                    <td><strong>Date:</strong></td>
                    <td><strong>Date:</strong></td>
                </tr>
            </table>

        </td>

    </tr>
</table>