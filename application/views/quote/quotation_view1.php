<?php
//echo "<pre>";
//print_r($quotation);
//echo "</pre>";
if (!isset($table_width)) {
    $table_width = 900;
}
?>
<table  width="<?php echo $table_width; ?>" border="0" cellspacing="0" cellpadding="0" bordercolor="#CCCCCC" >

    <tr>
        <td width="5">&nbsp;</td>
        <td align="center"><br /></td> <td width="5">&nbsp;</td>
    </tr>
    <tr> <td width="5">&nbsp;</td>
        <td>
            <p><?php echo $quotation['customer_details']['name']; ?>
                <br><?php echo trim($quotation['customer_details']['address']); ?>
                <?php if($quotation['customer_details']['email'] != '') {
                    ?>
                    <br><?php echo $quotation['customer_details']['email']; ?>
                    <?php
                    } 
                    if($quotation['customer_details']['mobile'] != '') {
                        ?>
                    <br><?php echo $quotation['customer_details']['mobile']; ?><br>
                        <?php
                    } ?>
           </p> 
        </td> <td width="5">&nbsp;</td>
    </tr>
    <tr> <td width="5">&nbsp;</td>
        <td><br><p>Dear Sir/ Madam,</p></td> 
        <td width="5">&nbsp;</td>
    </tr>
    <tr> <td width="5">&nbsp;</td>
        <td>
                        <p><strong>Subject:Your requirement of <?php echo $quotation['product_category_desc']; ?></strong></p><br>
        </td> <td width="5">&nbsp;</td>
    </tr>
    <tr> <td width="5">&nbsp;</td>
        <td><p>Thank you for your interest in Skanray’s products. We are confident that our products will satisfy your clinical requirements. 
            </p>
            <p>In line with your requirements, we are pleased to herewith submit our offer for the following:
            </p>
            <ol>
                <?php
                foreach ($quotation['product_details'] as $row) {
                    ?>
                    <li><?php echo $row['description']; ?></li>
                    <?php
                }
                ?>
            </ol>
            <p>The offer document has the following enclosures
            </p>
            <ol>
                <li>Product Brochure</li>
                <li>Commercial Offer</li>
                <li>Technical Specifications</li>
                <li>Scope of supply</li>
                <li>Terms and Conditions</li>
            </ol>
            <p>We sincerely hope that this offer is in line with your requirements. For further clarifications and information, we will be glad to furnish you with the same.
            </p>
            <p>We look forward to your valued order and long term relationship. 
            </p>

            <p>Assuring you of our best services at all times.
            </p>
            <p>Yours truly,<br><?php if($quotation['roleCheck'] == 1) { ?>Skanray Technologies Limited<?php } 
            else{ echo $quotation['lead_owner']['distributor']; } ?><br><br>
            <p><?php echo $quotation['quote_creator']['name']; ?><?php if($quotation['roleCheck'] == 1) { ?><br><?php echo $quotation['quote_creator']['role']; } ?></p></p><span style="font-size:8px">*This is an electronically generated quotation, no signature required.</span>
                    
        </td> <td width="5">&nbsp;</td>
    </tr>

    <br pagebreak="true"/>

    <tr> <td width="5">&nbsp;</td>
        <td><h2>Offer for</h2>
            <ol>
                <?php
                foreach ($quotation['product_details'] as $row) {
                    ?>
                    <li><?php echo $row['description']; ?></li>
                    <?php
                }
                ?>
            </ol>     		
        </td> <td width="5">&nbsp;</td>
    </tr>
    <tr> <td width="5">&nbsp;</td>
        <td>
            <h2>Contact</h2>
            <?php echo $quotation['lead_owner']['name']; ?>
            <br><?php echo $quotation['lead_owner']['mobile']; ?>
            <?php if($quotation['lead_owner']['phone'] != '') {
                ?>
                <br><?php echo $quotation['lead_owner']['phone']; ?>
                <?php
                } ?>
            <br><?php echo $quotation['lead_owner']['email']; ?>
            <?php if($quotation['roleCheck'] == 2){ ?>
            <br><?php echo $quotation['lead_owner']['address1']; ?>
            <br><?php echo $quotation['lead_owner']['address2'].' '.$quotation['lead_owner']['city']; ?>
            <?php } ?>
            <br>

        </td> <td width="5">&nbsp;</td>
    </tr>
    <tr> <td width="5">&nbsp;</td>
        <td>
            <br>
            <p>For further details on our local sales and services offices please visit our website <a href="www.skanray.com">www.skanray.com</a>
            </p>
            <p>You may also call our Toll Free Customer Interaction Centre on 1800-425-7002 between 10AM to 6:45PM on all weekdays or email your queries to <a href="cic@skanray.com">cic@skanray.com</a>
            </p>
            <br>
        </td> <td width="5">&nbsp;</td>
    </tr>
    <br pagebreak="true"/>

    <tr> <td width="5">&nbsp;</td>
        <td>
            <h2>Commercial Offer<span style="font-size:12px; font-weight:normal;"> (All values in <?php echo get_quote_currency_details($quote_id); ?>)</span></h2> <br><br><br></td> <td width="5">&nbsp;</td>
    </tr>
<?php $description_width = ($quote_revision_number==1)?39:26;?>
    <tr> <td width="5">&nbsp;</td>
        <td>
            <table width="100%" border="1" cellspacing="0" cellpadding="2">
                <tr>
                    <th width="5%" style="font-size:8px"><strong>Sno</strong></th>
                    <th width="17%" style="font-size:8px"><strong>Material Part #</strong></th>
                    <th width="<?php echo $description_width;?>%" style="font-size:8px"><strong>Material Description</strong></th>
                    <th width="10%" style="font-size:8px"><strong>Unit Price</strong></th>
                    <?php
                    switch ($tax_type) {
                        case 1: // ED, VAT
                            ?>
                            <th width="5%" style="font-size:8px"><strong>ED</strong></th>
                            <th width="8%" style="font-size:8px"><strong>VAT</strong></th>      
                            <?php
                        break;
                        case 2: // GST
                            if($quote_revision_number>1)
                            {
                        ?>
                            <th width="13%" style="font-size:8px"><strong>Discount</strong></th>
                        <?php
                            }
                        break;
                    }
                    ?>
                    <th width="12%" style="font-size:8px"><strong>Total Price</strong></th>
                    <th width="5%" style="font-size:8px"><strong>Qty</strong></th>
                    <th width="13%" style="font-size:8px"><strong>Gross Price</strong></th>
                </tr>
                <?php
                $cnt = 1;
                $grand_total = 0;
                if (count($quotation['product_details']) > 0) {
                    foreach ($quotation['product_details'] as $product) {
                        $total_amt = 0;
                        $mrp_m = $product['mrp'];
                        switch ($tax_type) {
                            case 1: // ED, VAT
                                $mrp = round($mrp_m/(1+(($product['ed']+$product['vat'])/100)));
                                //$product['ed']=6.0;
                                //$product['vat']=5.5;
                                //$product['qty']=1; 
                                $ed_in_per = round(get_percentage($mrp, $product['ed']));
                                $st_in_per = round(get_percentage($mrp, $product['vat']));
                                $total_amt = ($mrp + $ed_in_per + $st_in_per);
                            break;
                            case 2: // GST
                                $mrp = round($mrp_m/(1+$product['freight_insurance']/100)/(1+$product['gst']/100)); //$mrp_m*100/112
                                //$gst_in_per = round(get_percentage($mrp, $product['gst']));
                                $discount_amt = 0; $discount = 0;
                                /*if($quotation['discount'] > 0)
                                {
                                    $discount = $quotation['discount'];
                                    $discount_amt = round(get_percentage($mrp, $discount)); 
                                }
                                */
                                
                                switch ($quote_format_type) {
                                    case 1: // Old Format
                                        $discount = $quotation['discount'];
                                        $discount_amt = round(get_percentage($mrp, $discount)); 
                                    break;
                                    case 2: // New Format
                                        
                                        $orow = getOpportunityDiscount($quote_revision_id,$product['opportunity_id']);
                                        $discount_val = $orow['discount'];
                                        $discount_type = $orow['discount_type'];
                                        if($discount_type==1) // In %
                                        {
                                            $discount = $discount_val;
                                            $discount_amt = round(get_percentage($mrp, $discount)); 
                                        }
                                        else // In Rs
                                        {
                                            $discount_amt = round($discount_val);
                                            $discount = round((($discount_amt/$mrp)*100),2);
                                            $discount_amt = round($discount_amt/(1+$product['freight_insurance']/100)/(1+$product['gst']/100));
                                        }
                                    break;
                                }
                                $total_amt = ($mrp - $discount_amt);
                            break;
                        } 
                        $gross_price = $total_amt * $product['qty'];
                        $grand_total+=$gross_price;
                        ?>
                        <tr>
                            <td width="5%" style="font-size:8px" align="center" valign="top"><?php echo $cnt;
                $cnt++; ?></td>
                            <td width="17%" style="font-size:8px" valign="top"><?php echo $product['name']; ?></td>
                            <td width="<?php echo $description_width;?>%" style="font-size:8px" valign="top"> <?php echo $product['description'];?></td>
                            <td width="10%" style="font-size:8px" valign="top" align="right"><strong><?php echo indian_format_price($mrp); ?></strong></td>
                            
                            <?php
                            switch ($tax_type) {
                                case 1: // ED, VAT
                                    ?>
                                    <td width="5%" style="font-size:8px" valign="top" align="center"><strong><?php echo round($product['ed'],2); ?>%</strong></td>
                                    <td width="8%" style="font-size:8px" valign="top" align="center"><strong><?php echo round($product['vat'],2); ?>%</strong></td>      
                                    <?php
                                break;
                                case 2: // GST
                                if($quote_revision_number>1)
                                {
                                ?>
                                    <td width="13%" style="font-size:8px" valign="top" align="center"><strong><?php echo round($discount,2); ?>%</strong></td>      
                                <?php
                                }
                                break;
                            }
                            ?>

                            <td width="12%" style="font-size:8px" valign="top" align="center"><strong><?php echo indian_format_price($total_amt); ?></strong></td>
                            <td width="5%" style="font-size:8px" valign="top" align="center"><strong><?php echo $product['qty']; ?></strong></td>

                            <td width="13%" style="font-size:8px" valign="top" align="right"><strong><?php echo indian_format_price($gross_price); ?></strong></td>
                        </tr>
                        <?php
                    }

                    if(count(@$free_supply_items)>0)
                    {
                        foreach ($free_supply_items as $frow) {
                            ?>
                            <tr>
                                <td width="5%" style="font-size:8px" align="center" valign="top"><?php echo $cnt;
                    $cnt++; ?></td>
                                <td width="17%" style="font-size:8px" valign="top"><?php echo $frow['name']; ?></td>
                                <td width="<?php echo $description_width;?>%" style="font-size:8px" valign="top"> <?php echo $frow['description'];?></td>
                                <td width="10%" style="font-size:8px" valign="top" align="right"><strong>0</strong></td>
                                <?php
                            switch ($tax_type) {
                                case 1: // ED, VAT
                                    ?>
                                    <td width="5%" style="font-size:8px" valign="top" align="center"><strong>0</strong></td>
                                    <td width="8%" style="font-size:8px" valign="top" align="center"><strong>0</strong></td>      
                                    <?php
                                break;
                                case 2: // GST
                                if($quote_revision_number>1)
                                {
                                ?>
                                    <td width="13%" style="font-size:8px" valign="top" align="center"><strong>0%</strong></td>      
                                <?php
                                }
                                break;
                            }
                            ?>
                                <td width="12%" style="font-size:8px" valign="top" align="center"><strong>0</strong></td>
                                <td width="5%" style="font-size:8px" valign="top" align="center"><strong><?php echo $frow['quantity']; ?></strong></td>

                                <td width="13%" style="font-size:8px" valign="top" align="right"><strong>0</strong></td>
                            </tr>
                            <?php
                        }
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="9">No Products Available</td>
                    </tr>       
                <?php } ?>
            </table>

        </td> <td width="5">&nbsp;</td>
    </tr>
    <?php $sub_total_width = 83;?>
    <tr> <td width="5">&nbsp;</td>
        <td>
            <table width="100%" border="1" cellspacing="0" cellpadding="2">

                <tr>
                    <td width="5%" align="center" valign="top">&nbsp;</td>
                    <td width="<?php echo $sub_total_width?>%">Sub-total (Ex-works Mysore) 
                    </td>
                    <td width="13%" valign="top" align="right" style="font-size:8px" >
                        <strong><?php echo indian_format_price($grand_total); ?></strong>
                    </td>
                </tr>
                <?php
                switch ($tax_type) {
                    case 1: // ED, VAT
                        $discount_amt = 0;
                    if ($quotation['discount'] > 0) {
                        $discount = $quotation['discount'];
                    ?>
                    <tr>
                        <td width="5%" align="center" valign="top">&nbsp;</td>
                        <td width="83%">Discount(<?php echo round($discount,2); ?>%)</td>
                        <td valign="top" width="13%" align="right" style="font-size:8px" >
                        <strong><?php echo indian_format_price($discount_amt = round(get_percentage($grand_total, $discount))); ?></strong>
                        </td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td width="5%" align="center" valign="top">&nbsp;</td>
                        <td  width="83%">Add  Freight& Insurance@<?php echo round($product['freight_insurance'],2); ?>%
                        </td>
                        <td valign="top" align="right" width="13%"  style="font-size:8px" >
                            <strong><?php echo indian_format_price($freight = round(get_percentage(($grand_total-$discount_amt), $product['freight_insurance']))); ?></strong>
                        </td>
                    </tr>
                    <?php
                        $final_amt = (($grand_total + $freight) - $discount_amt);
                    break;
                    case 2: // GST
                        $freight = round(get_percentage($grand_total, $product['freight_insurance']));
                        $new_sub_total = $grand_total+$freight;
                        $gst_total = round(get_percentage($new_sub_total, $product['gst']));
                        ?>
                        <tr>
                            <td width="5%" align="center" valign="top">&nbsp;</td>
                            <td  width="<?php echo $sub_total_width?>%">Add  Freight& Insurance@<?php echo round($product['freight_insurance'],2); ?>%
                            </td>
                            <td valign="top" align="right" width="13%"  style="font-size:8px" >
                                <strong><?php echo indian_format_price($freight); ?></strong>
                            </td>
                        </tr>
                        <tr>
                            <td width="5%" align="center" valign="top">&nbsp;</td>
                            <td  width="<?php echo $sub_total_width?>%">Add GST @<?php echo round($product['gst'],2); ?>%
                            </td>
                            <td valign="top" align="right" width="13%"  style="font-size:8px" >
                                <strong><?php echo indian_format_price($gst_total); ?></strong>
                            </td>
                        </tr>
                        <?php
                        $final_amt = $new_sub_total+$gst_total;
                    break;
                } 
                ?>
                <tr>
                    <td width="5%" align="center" valign="top">&nbsp;</td>
                    <td  width="<?php echo $sub_total_width?>%">Total Price - F.O.R Destination in <?php echo get_quote_currency_forms($quote_id); ?>.
                    </td>
                    <td valign="top" align="right" width="13%" style="font-size:8px" >
                        <strong><?php
                        
                        echo indian_format_price($final_amt = ceil($final_amt));
                        ?></strong>
                    </td>
                </tr>
                <tr>
                    <td width="5%" align="center" valign="top">&nbsp;</td>
                    <td width="<?php echo $sub_total_width?>%">Total Price in words - <?php echo get_quote_currency_forms($quote_id); ?>. (<?php echo ucwords(convert_number_to_words($final_amt)); ?> Only)
                    </td>
                    <td valign="top"  width="13%" >&nbsp;

                    </td>
                </tr>
            </table>
        </td> <td width="5">&nbsp;</td>
    </tr>
    <tr> <td width="5">&nbsp;</td>
        <td>
        </td> <td width="5">&nbsp;</td>
    </tr>

    <tr> <td width="5">&nbsp;</td>
        <td>
            <table width="100%" border="0" cellspacing="0" cellpadding="5">
                <tr>
                    <td width="20%"   valign="top" ><strong>Note:</strong></td>

                </tr>
                <tr>

                    <td width="100%">
                        <ol>
                            <?php
                            switch ($tax_type) {
                                case 1: // ED, VAT
                                    ?>
                                    <li>The above prices are in <?php echo get_quote_currency_forms($quote_id); ?>. & inclusive of currently applicable ED & Sales Tax. Any change in these rates at the time of billing, will be extra to your account, at actual.</li>
                                    <li>Octroi, LBT, Entry tax etc., if applicable, will be extra to your account, at actual.</li>
                                    <?php
                                break;
                                case 2: // GST
                                ?>
                                    <li>The above prices are in <?php echo get_quote_currency_forms($quote_id); ?>. & inclusive of currently applicable GST. Any change in these rates at the time of billing, will be extra to your account, at actual.</li>
                                <?php
                                break;
                            }
                            ?>
                            
                            <li>All the orders should be placed on M/s Skanray Technologies Limited., Mysore for supply of goods</li>
                            <?php

                            switch ($quote_format_type) {
                                case 1: // Old Format
                                    ?>
                                    <li>Payment terms: 100% advance along with confirmed order</li>
                                    <li>Validity: 30 days from the date of offer.</li>
                                    <li>Other Terms & Conditions, as per enclosed Terms & Conditions of Sale (Supply only)</li>
                                    <?php
                                break;
                                case 2: // New Format
                                    $warranty = ($quote_revision_number==1)?getDefaultWarranty():$quote_info['warranty'];
                                    if(($quote_info['advance_type']==1&&$quote_info['advance']==100)||$quote_revision_number==1)
                                    {
                                        ?>
                                    <li>Payment terms: 100% advance along with confirmed order</li>
                                        <?php
                                    }
                                    else
                                    {
                                        ?>
                                    <li>Payment terms: <?php echo format_advance($quote_info['advance'],$quote_info['advance_type']);?> advance and balance in <?php echo $quote_info['balance_payment_days'];?> days from invoice</li>
                                        <?php
                                    }
                                    ?>
                                    <li>Validity: 30 days from the date of offer.</li>
                                    <li>Warranty: <?php echo $warranty.' Months';?> from date of invoice </li>
                                    <li>Other Terms & Conditions, as per enclosed Terms & Conditions of Sale (Supply only)</li>
                                    <?php
                                break;
                            }
                            ?>
                            
                            
                        </ol>
                        <div style="border:1px solid #050505; padding:10px;">

                            <h4><strong><br>
                            Payment Details</strong><br></h4>
                            <br>


                            Bank Name: <?php echo $quotation['bank_details']['bank_name']; ?><br>
                            Branch: <?php echo $quotation['bank_details']['branch']; ?><br>
                            A/C Name: <?php echo $quotation['bank_details']['ac_name']; ?><br>
                            A/C no: <?php echo $quotation['bank_details']['ac_no']; ?><br>
                            IFSC Code: <?php echo $quotation['bank_details']['ifsc']; ?><br>

                        </div>
                    </td>
                </tr>
            </table>
        </td> <td width="5">&nbsp;</td>
    </tr>
    <br pagebreak="true"/>

    <tr> <td width="5">&nbsp;</td>
        <td >
            <?php
            $k = 1;
            foreach ($quotation['product_details'] as $row) {
                if ($row['features'] != '') {
                    ?>

                    <h2><?php echo $k . '. ' . $row['name'] . ' - ' . $row['description']; ?></h2>


                    <h4>Technical Specifications</h4>
        <?php
        echo $row['features'];
        ?>
                </td> <td width="5">&nbsp;</td>
            </tr>
            <tr> <td width="5">&nbsp;</td>
                <td>
                    <h4>Scope of Supply</h4>
        <?php echo $row['scope']; ?>
                    <br pagebreak="true"/>
                    <?php
                    $k++;
                }
            }
            ?>
        </td> <td width="5">&nbsp;</td>
    </tr>


    <tr> <td width="5">&nbsp;</td>
        <td>
            <h2>Terms and Conditions</h2>
        </td> <td width="5">&nbsp;</td>
    </tr>
    <tr> <td width="5">&nbsp;</td>
        <td align="left"><br><p>Terms & Conditions of Sale (Supply only)</p>
            <p>Unless otherwise stated in the quotation</p>
        </td> <td width="5">&nbsp;</td>
    </tr>
    <tr> <td width="5">&nbsp;</td>
        <td align="left">&nbsp;</td> 
        <td width="5">&nbsp;</td>
    </tr>
    <tr> <td width="5">&nbsp;</td>
        <td align="left">
            <h3><strong>PRICE:</strong></h3>
            <?php
            switch ($tax_type) {
                case 1: // ED, VAT
                    ?>
                    <div style=" text-align:justify;">Prices quoted are for supply, on FOR destination basis, inclusive of excise duty at the currently applicable rates. Sales tax, octroi, entry tax, LBT, and such other statutory levies, if any, will be extra to your account. Any variation in the above referred duties and taxes will be to your account.</div><div style=" text-align:justify;">Any entry permit / Way bill / such statutory local permissions, if required, have to be provided to us by your Institution, prior to dispatch of Goods from our factory at Mysore. </div><div style=" text-align:justify;">The prices do not include unloading charges at the site and demurrage charges, if any. </div>
                    <?php
                break;
                case 2: // GST
                ?>
                    <div style=" text-align:justify;">Prices quoted are for supply, on FOR destination basis, inclusive of currently applicable GST. Any variation in the above referred taxes will be to your account.</div>
                    <div style=" text-align:justify;">Any entry permit / Way bill / such statutory local permissions, if required, have to be provided to us by your Institution, prior to dispatch of Goods from our factory at Mysore.</div>
                    <div style=" text-align:justify;">The prices do not include unloading charges at the site and demurrage charges, if any. </div>
                <?php
                break;
            }
            ?>
        </td>   
        <td width="5">&nbsp;</td>
    </tr>

    <tr> <td width="5">&nbsp;</td>
        <td  align="left">
            <h3><strong>VALIDITY:</strong></h3>
            <div style=" text-align:justify;">The offer shall be valid for your acceptance for a period of thirty days from the date of quotation and thereafter subject to supplier’s confirmation. Offer would be considered as accepted on receipt, by the supplier, of technically and commercially clear order along with the dispatch instructions in writing.


            </div>
       
</td> <td width="5">&nbsp;</td>
</tr>

<tr> <td width="5">&nbsp;</td>
    <td  align="left">
        <h3><strong>SCOPE:</strong></h3>
        <div style=" text-align:justify;">The scope of supply and other terms and conditions of this contract shall be strictly governed by supplier’s offer and supplier’s acknowledgement of purchaser’s order. The purchaser shall be deemed to have understood and accepted the conditions contained herein and the specific terms and conditions contained in the offer.

        </div>
    </td>

<td width="5">&nbsp;</td>
</tr>

<tr> <td width="5">&nbsp;</td>
    <td  align="left">
        <h3><strong>PAYMENT TERMS:</strong></h3>
        <div style=" text-align:justify;">90% of the order value as advance payment, along with the confirmed order. Balance 10% of the order value (together with taxes and duties, if applicable), will be payable against submission of proof of despatch. The documents that will be submitted to you or your bankers upon your instruction will be the Sale Invoice and copy of the Lorry / transporter’s receipt, evidencing the movement. Please provide the full address of your bankers in your order. Please provide us your banker’s confirmation letter, with regard to the balance payment along with your despatch clearance.
        </div>
        <div style=" text-align:justify;">The purchaser shall make payments as per the agreed terms of payment. If for any reason payment is not forthcoming within the agreed time, supplier, on his own discretion without giving intimation to the purchaser, can call back the consignment and adjust the costs incurred in the said transaction from the advances paid.
        </div>
        <div style=" text-align:justify;">Please ensure that payment is made on priority basis so that the transporter / truck deliver the material when it reaches purchasers site. Non clearance of the payment and non handing over of the consignee copy of LR on time, can not only involve additional loading and unloading but will also cost purchaser, storage charges as well as the to and fro freight, to the nearest transit warehouse, of the transporter. This unnecessary loading and unloading is also not considered good for the sophisticated medical equipment.   
        </div>
</td> <td width="5">&nbsp;</td>
</tr>

<tr> <td width="5">&nbsp;</td>
    <td  align="left">
        <h3><strong>DELIVERY:</strong></h3>
        <div style=" text-align:justify;">Shipment of most orders can normally be made, on FOR station of despatch basis, within 4 to 8 weeks from the date of receipt of technically and commercially clear order along with despatch instructions / agreed advance amount etc., whichever is later.

        </div>
    </td>
<td width="5">&nbsp;</td>
</tr>

<tr> <td width="5">&nbsp;</td>
    <td  align="left">
        <h3><strong>INSTALLATION / ASSEMBLY - DEMONSTRATION :</strong></h3>
        <div style=" text-align:justify;">The purchaser shall provide a suitable site, carry out preliminaries connected with installation, civil and structural alterations (as per supplier’s specifications) to enable to the supplier to install the equipment. Purchaser shall comply with requirement of Atomic Energy Regulatory Board (AERB) Guidelines for setting up of new diagnostic X-Ray installation.</div>
    </td>
 <td width="5">&nbsp;</td>
</tr>

<tr> <td width="5">&nbsp;</td>
    <td  align="left">
        <h3><strong>OPERATIONAL REQUIREMENT :</strong></h3>
        <div style=" text-align:justify;">The Purchaser shall maintain the environmental conditions recommended by the supplier so as to ensure that the equipment does not suffer damage due to humidity, dust, pests, severe temperature etc. The purchaser shall ensure that the equipment is operated, as per the operating instructions and supplier’s recommendations. The purchaser shall also ensure that competent, trained and skilled personnel operate the equipment. The supplier shall not be responsible for any loss, damage or injuries caused due to purchaser’s non fulfillment of these conditions.

        </div>
</td> <td width="5">&nbsp;</td>
</tr>

<tr> <td width="5">&nbsp;</td>
    <td  align="left">
        <h3><strong>AERB GUIDELINES FOR INSTALLATION :</strong></h3>
        <div style=" text-align:justify;">The Atomic Energy Regulatory Board (AERB) Safety Code AERB/SC/MED-2 for Medical Diagnostic X-Ray Equipment and installations is applicable for all x-ray installations

        </div>
    </td>
 <td width="5">&nbsp;</td>
</tr>

<tr> <td width="5">&nbsp;</td>
    <td  align="left">
        <h3><strong>PROCUREMENT APPROVAL:</strong></h3>
        <div style=" text-align:justify;">End user shall obtain “Permission for Procurement” from AERB through e-LORA web portal.
        </div>
        <div style=" text-align:justify;">Refer “guidelines document for user” under link below<br>https://elora.aerb.gov.in/ELORA/PDFs/Guidelines%20for%20users.pdf
        </div>
        <div style=" text-align:justify;">No X-Ray machine shall be installed / commissioned unless the layout of the proposed X-Ray installation is approved by the competent authority. The application for approval shall be made by the person owning responsibility for the installation site.
        </div>
        <div style=" text-align:justify;">As the above is mandatory for the installation and commissioning of the X-Ray equipment, the purchaser shall comply with the above requirement and get the room layout plan duly approved by the AERB through e-LORA web portal. 


        </div>
    </td>
 <td width="5">&nbsp;</td>
</tr>
<tr> <td width="5">&nbsp;</td>
    <td  align="left">
        <h3><strong>OPERATIONAL LICENSE:</strong></h3>
        <div style=" text-align:justify;">SKANRAY will perform the installation and On-site QA tests. Purchaser shall obtain license to operate within 3 months of completion of on-site QA tests
        </div>
        <div style=" text-align:justify;">After completion of installation & On-site QA tests, end user shall obtain license to use the equipment from AERB through e-LORA web portal. 
        </div>
        <div style=" text-align:justify;">For further information, one can log on to 
        </div>
        <div style=" text-align:justify;">www.aerb.gov.in , https://elora.aerb.gov.in/ELORA/populateLoginAction.htm
        </div>

    </td>
 <td width="5">&nbsp;</td>
</tr>
<tr> <td width="5">&nbsp;</td>
    <td  align="left">
        <h3><strong>WARRANTY: </strong></h3>
        <div style=" text-align:justify;">All goods manufactured by us are guaranteed against defects arising from material or workmanship for a period of 12months from the date of installation or 13 months from the date of shipment, whichever is earlier. 	
        </div>
        <div style=" text-align:justify;">Our liability under this warranty is limited to either repairing the defective parts free of charge, or at our option, providing a free replacement, in exchange of the defective part. The defective part shall be sent, duly packed, to our concerned office/service station, at purchaser’s cost including freight, insurance and forwarding charges. The warranty is applicable only if the equipment is used in the way prescribed by the supplier. No accidental damages to any part of the machine or its accessories will be covered under this warranty.
        </div>
        <div style=" text-align:justify;">Bought out items such as trolleys, stabilisers, cameras, any recording devices, monitor stands, cables are not tested by us prior to supply. Manufacturer’s warranty shall apply for such items.  
        </div>
        <div style=" text-align:justify;">This warranty shall not extend to glassware items & parts, which are subject to normal wear & tear. The warrantyalso does not cover breakages of any item due to misuse.
        </div>
        <div style=" text-align:justify;">All Vacumatic items (X-Ray Tubes, Camera & Image Intensifying Tubes) are subject to pro-rata warranty and these itemsgoing defective during the warranty period shall be replaced as per following replacements policy. (This is in linewith warranty being extended to us by our suppliers of these parts)


            <br><br>a) Price of the X-Ray Tube or I.I. Tube or Camera or HF X-Ray Generators = Rs. X
            <br>b) Un-expired portion of the warranty = Y Months
            <br>c) Pro-Rate Credit to be allowed to customer = X x Y
            <br>d) Replacement cost to be borne by customer = a) - c) =Rs._______
            <br> <br>The warranty, however does not extend to the following:
            <br>For X-Ray machines or Surgical C-Arm units:H.T cables and accessories.
            <br> <br>For Patient Monitoring systems: Probes / sensors, Pressure transducers, temperature probes, patient cables, batteries & other consumables
            <br> <br>For Ventillators / Anaesthesia systems: Flow transducer, pressure transducer, heater wires, O2 cells, patient tubes / bellows, batteries & other consumables.
</div>
    </td>
 <td width="5">&nbsp;</td>
</tr>

<tr> <td width="5">&nbsp;</td>
    <td  align="left">
        <h3><strong>Post Warranty Service:</strong></h3>
        <div style=" text-align:justify;">After completion of warranty period, the equipment can be covered by Annual Maintenance Contract (Labour only) @4% of the Purchase Order Value + service taxes or Comprehensive Annual Maintenance Contract @10% of the Purchase Order Value + service taxes or Service on Call Basis at Rs.20,000.00 + service taxes. The Comprehensive Annual Maintenance Contract covers labor and all parts excluding Image Intensifier, CCD Camera, X-Ray tube, H.T cables and accessories.
        </div>
    </td>
 <td width="5">&nbsp;</td>
</tr>
<tr> <td width="5">&nbsp;</td>
    <td align="left">&nbsp;
    </td> <td width="5">&nbsp;</td>
</tr>
<tr> <td width="5">&nbsp;</td>
    <td  align="left">
        <h3><strong>LIABILITIES: </strong></h3>
        <div style=" text-align:justify;">Except as otherwise provided explicitly hereinabove, we shall not be liable for any special or consequential damages of any kind or nature, arising out of use of this equipment. We shall also not be liable in any manner for use of or failure in the performance of other equipment, to which the equipment is attached or connected. 
        </div>
    </td>
 <td width="5">&nbsp;</td>
</tr>

<tr> <td width="5">&nbsp;</td>
    <td  align="left">
        <h3><strong>EXEMPTION:</strong></h3>
        <div style=" text-align:justify;">We shall not be responsible for any failure in performing our obligations, if such non performance is due to reasons beyond our control.
        </div>
    </td>
<td width="5">&nbsp;</td>
</tr>
<tr> <td width="5">&nbsp;</td>
    <td  align="left">
        <h3><strong>AGREEMENT:</strong></h3>
        <div style=" text-align:justify;">The foregoing terms & conditions shall prevail notwithstanding any variations contained in any document received from the customer, unless such variations have been specifically agreed upon in writing by Skanray Technologies Limited.
        </div>
    </td>
 <td width="5">&nbsp;</td>
</tr>
<tr> <td width="5">&nbsp;</td>
    <td  align="left">
        <h3><strong>INVOICING:</strong></h3>
        <div style=" text-align:justify;">Invoicing can be raised through Skanray authorized stockist or distributor based on the availablity of the goods.
        </div>
    </td>
 <td width="5">&nbsp;</td>
</tr>

<tr>
    <td width="5">&nbsp;</td>
    <td>&nbsp;</td>
    <td width="5">&nbsp;</td>
</tr>
<tr>
    <td width="5">&nbsp;</td>
    <td>&nbsp;</td>
    <td width="5">&nbsp;</td>
</tr>
<tr>
    <td width="5">&nbsp;</td>
    <td >
        <table border="1" cellspacing="0" cellpadding="2">
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
    <td width="5">&nbsp;</td>
</tr>
</table>