<?php
$this->load->view('commons/main_template', $nestedView);
echo $this->session->flashdata('response'); 
?>

<div class="row"> 
    <div class="col-sm-12 col-md-12">
        <div class="block-flat">
            <div class="content">
                <div class="content">
                    <div class="row no-gutter" >
                        <form role="form" class="form-horizontal" method="post" action="<?php echo SITE_URL; ?>margin_analysis_list">
                            <div class="col-sm-12" style="margin-bottom:10px;">
                                
                                <label class="col-sm-1 control-label">Quote </label>
                                <div class="col-sm-2">
                                    <input type="text" name="quote_id" placeholder="Quote ID" maxlength="20"  value="<?php echo @$searchParams['quote_id']; ?>" id="companyName" class="form-control">
                                </div>
                                <label class="col-sm-2 control-label">Opportunity </label>
                                <div class="col-sm-3">
                                    <input type="text" name="opportunity_details" placeholder="Opportunity Details" maxlength="60"  value="<?php echo @$searchParams['opportunity_details']; ?>" id="companyName" class="form-control">
                                </div>
                                <?php
                                if($this->session->userdata('role_id')>7)
                                {
                                ?>
                                <div class="col-sm-2">
                                    <select name="ma_region" id="ma_region" class="form-control">
                                        <option value="">Select Region</option>
                                        <?php
                                        foreach ($regions as $r_row) {
                                            $selected = ($r_row['location_id']==@$searchParams['ma_region'])?'selected':'';
                                            echo '<option value="'.$r_row['location_id'].'" '.$selected.'>'.$r_row['location'].'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <?php
                                }
                                ?>
                             <div class="col-sm-1">
                                    <button type="submit" name="searchApprveQuote" value="1" class="btn btn-success"><i class="fa fa-search"></i> Search</button>
                             </div>  
                                
                            </div>
                            </div>
                        </form>
                    </div>
                <form >
                    <div class="table-responsive">
                        <table class="table table-bordered hover">
                            <thead>
                                <tr>
                                    <th class="text-center" width="15%"><strong>Customer</strong></th>
                                    <th class="text-center" width="8%"><strong>Sales Engineer</strong></th>
                                    <th class="text-center" width="10%"><strong>Quote ID</strong></th>
                                <?php if($conditionApproval[0]['condition'] == 0){ ?>
                                    <th class="text-center" width='15%'><strong>Opportunity Details</strong></th>
                                    <th class="text-center" width="8%"><strong>Order value</strong></th>
                                    <?php 
                                    if($login_user_role_id==8||$login_user_role_id==9) // NSM , CH
                                    {
                                    ?>
                                    <th class="text-center" width="6%"><strong>Gross Margin %</strong></th>
                                    <th class="text-center" width="6%"><strong>Net Margin %</strong></th>
                                    <?php
                                    }
                                    ?>
                                    <th class="text-center" width="6%"><strong>Discount %</strong></th>
                                <?php } ?>
                                    <th class="text-center" width="9%"><strong>Date</strong></th>
                                    <th class="text-center" width="5%"><strong>Final Approver</strong></th>
                                    <?php if($login_user_role_id == '8' || $login_user_role_id == '9'){?>
                                    <th class="text-center" width="3%"><strong>View</strong></th>
                                    <?php }?>
                                    <th class="text-center" width="3%"><strong>Actions</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                //@$inc = $start + 1;
                                if (!empty($quoteSearch)) {
                                    $user_products_str = $this->session->userdata('products');
                                    $user_products = explode(',',$user_products_str);
                                    $ma_arr = array(); $quote_ma_arr = array(); 
                                    foreach (@$quoteSearch as $row) {
                                            $order_value = $row['mrp'];
                                            $discount = $row['discount'];
                                            if($row['discount_type']!=''&&$discount!='')
                                            $order_value = ($row['discount_type']==1)?($order_value*(1-$row['discount']/100)):($order_value-$discount);
                                            $discount_percenrage = round((($row['mrp'] - $order_value )/$row['mrp'])*100,2);



                                            if(!in_array($row['quote_revision_id'], $quote_ma_arr))
                                            {
                                                $op_results = getOpportunitiesInfoByQuoteRevision($row['quote_revision_id']);
                                                $quote_ma_arr[$row['quote_revision_id']]['opportunities'] = $op_results;
                                                $total_mrp = $total_order_value = $total_discount = $total_base_price = $total_dp = $dealer_comm_cost = $warranty_cost1 = 0;
                                                $freight_insurance = 2; $gst = 12;
                                                foreach ($op_results as $op_row) {
                                                    $total_mrp += $op_row['mrp'];
                                                    $op_order_value = $op_row['mrp'];
                                                    $discount = $op_row['discount'];
                                                    if($op_row['discount_type']!=''&&$discount!='')
                                                    $op_order_value = ($op_row['discount_type']==1)?($op_order_value*(1-$op_row['discount']/100)):($op_order_value-$discount);
                                                    $total_order_value += $op_order_value;
                                                    $total_base_price += $op_row['base_price'];
                                                    $total_dp += $op_row['dp'];
                                                    $freight_insurance = $op_row['freight_insurance'];
                                                    $gst = $op_row['gst'];

                                                    $nsp2 = round($op_order_value/(1+$freight_insurance/100)/(1+$gst/100));
                                                    $dealer_comm1 = $op_row['dealer_commission'];
                                                    $dealer_comm_cost += round($nsp2*($dealer_comm1/100));
                                                    $warranty_cost1 += round($op_row['warranty_of_cost']);
                                                }


                                            if($login_user_role_id==8||$login_user_role_id==9) // NSM , CH
                                            {
                                                $nsp = $order_value/(1+$row['freight_insurance']/100)/(1+$row['gst']/100);
                                                $discount_percenrage = round((($row['mrp'] - $order_value )/$row['mrp'])*100,2);
                                                $data = array();
                                                $data['order_value'] = $order_value;
                                                $data['net_selling_price'] = $nsp;
                                                $data['basic_price'] = $row['base_price'];
                                                $data['dp'] = $row['dp'];
                                                $data['total_warranty_in_years'] = ($row['warranty']>0)?round(($row['warranty']/12),2):0;
                                                $advance = 0;
                                                if($row['advance']!='')
                                                {
                                                    switch ($row['advance_type']) {
                                                        case 1:
                                                            $advance = $row['advance'];
                                                        break;
                                                        
                                                        case 2:
                                                            $quote_total_order_value = getQuoteRevisionTotalOrderValue($row['quote_revision_id']);
                                                            $advance = round(($row['advance']/$quote_total_order_value)*100,2);
                                                        break;
                                                    }
                                                    
                                                }
                                                $data['advance'] = $advance;
                                                $data['balance_payment_days'] = ($row['balance_payment_days']!='')?$row['balance_payment_days']:0;
                                                $data['dealer_commission'] = ($row['dealer_commission']>0)?$row['dealer_commission']:0;
                                                $cost_of_free_supply = 0;
                                                $free_supplies = getQuoteOppFreeSupplies($row['quote_revision_id'],$row['opportunity_id']);
                                                if($free_supplies)
                                                {
                                                    foreach ($free_supplies as $frow) {
                                                        $cost_of_free_supply += $frow['quantity']*$frow['unit_price'];
                                                    }
                                                }
                                                $data['cost_of_free_supply'] = $cost_of_free_supply;
                                                $data['free_supply'] = $free_supplies;
                                                $data['cost_of_commission1'] = $dealer_comm_cost;
                                                $data['cost_of_warranty1'] = $warranty_cost1;
                                                $op_ma_data = marginAnalysis($data);
                                            }

                                            // if(!in_array($row['quote_revision_id'], $quote_ma_arr))
                                            // {
                                            //     $op_results = getOpportunitiesInfoByQuoteRevision($row['quote_revision_id']);
                                            //     $quote_ma_arr[$row['quote_revision_id']]['opportunities'] = $op_results;
                                            //     $total_mrp = $total_order_value = $total_discount = $total_base_price = $total_dp = $dealer_comm_cost = 0;
                                            //     $freight_insurance = 2; $gst = 12;
                                            //     foreach ($op_results as $op_row) {
                                            //         $total_mrp += $op_row['mrp'];
                                            //         $op_order_value = $op_row['mrp'];
                                            //         $discount = $op_row['discount'];
                                            //         if($op_row['discount_type']!=''&&$discount!='')
                                            //         $op_order_value = ($op_row['discount_type']==1)?($op_order_value*(1-$op_row['discount']/100)):($op_order_value-$discount);
                                            //         $total_order_value += $op_order_value;
                                            //         $total_base_price += $op_row['base_price'];
                                            //         $total_dp += $op_row['dp'];
                                            //         $freight_insurance = $op_row['freight_insurance'];
                                            //         $gst = $op_row['gst'];

                                            //         $nsp2 = round($op_order_value/(1+$freight_insurance/100)/(1+$gst/100));
                                            //         $dealer_comm1 = $op_row['dealer_commission'];
                                            //         $dealer_comm_cost += round($nsp2*($dealer_comm1/100));
                                            //     }

                                                $nsp = round($total_order_value/(1+$freight_insurance/100)/(1+$gst/100));
                                                if($total_mrp>0)
                                                {
                                                    $quote_discount_percenrage = round((($total_mrp - $total_order_value )/$total_mrp)*100,2);
                                                }
                                                else
                                                {
                                                    $quote_discount_percenrage =0;
                                                }
                                               
                                                $data = array();
                                                $data['order_value'] = $total_order_value;
                                                $data['net_selling_price'] = $nsp;
                                                $data['basic_price'] = $total_base_price;
                                                $data['dp'] = $total_dp;
                                                $data['total_warranty_in_years'] = ($row['warranty']>0)?round(($row['warranty']/12),2):0;
                                                $advance = 0;
                                                if($row['advance']!='')
                                                {
                                                    switch ($row['advance_type']) {
                                                        case 1:
                                                            $advance = $row['advance'];
                                                        break;
                                                        
                                                        case 2:
                                                            //$quote_total_order_value = getQuoteRevisionTotalOrderValue($row['quote_revision_id']);
                                                            $advance = round(($row['advance']/$total_order_value)*100,2);
                                                        break;
                                                    }
                                                    
                                                }
                                                $data['advance'] = $advance;
                                                $data['balance_payment_days'] = ($row['balance_payment_days']!='')?$row['balance_payment_days']:0;
                                                $data['dealer_commission'] = ($row['dealer_commission']>0)?$row['dealer_commission']:0;
                                                $cost_of_free_supply = 0;
                                                $free_supplies = getQuoteFreeSupplies($row['quote_revision_id']);
                                                if($free_supplies)
                                                {
                                                    foreach ($free_supplies as $frow) {
                                                        $cost_of_free_supply += $frow['quantity']*$frow['unit_price'];
                                                    }
                                                }
                                                $data['cost_of_free_supply'] = $cost_of_free_supply;
                                                $data['free_supply'] = $free_supplies;
                                                $data['cost_of_commission1'] = $dealer_comm_cost;
                                                $data['cost_of_warranty1'] = $warranty_cost1;
                                                $ma_data = marginAnalysis($data);
                                                $ma_data['discount_percenrage'] = $quote_discount_percenrage;
                                                $ma_data['currency_code'] = $row['currency_code'];
                                                $ma_arr[$row['quote_revision_id']] = $ma_data;
                                            }
                                        ?>
                                        <tr>
                                            <td class="text-center"><?php echo $row['customer'].'('.$row['region'].')'; ?></td>
                                            <td class="text-center"><span data-toggle="tooltip" data-original-title="<?php echo 'Emp ID: '.$row['owner_emp_id'];?>"><?php echo $row['lead_owner_name']; ?></span></td>
                                            <td class="text-center"><?php echo $row['tag'].'<br>'.format_date($row['quote_revision_time']); ?></td>
                                        <?php if($conditionApproval[0]['condition'] == 0){ ?>
                                            <td><?php echo @$row['opp_details']; ?></td>
                                            <td class="text-center"><?php echo indian_format_price(round($order_value)).' '.$row['currency_code']; ?></td>
                                            <?php 
                                            if($login_user_role_id==8||$login_user_role_id==9) // NSM , CH
                                            {
                                            ?>
                                            <td class="text-center" align='cneter'><?php echo $op_ma_data['gross_margin_percentage'];?></td>
                                            <td class="text-center" align='cneter'><?php echo $op_ma_data['net_margin_percentage'];?></td>
                                            <?php
                                            }
                                            ?>
                                            <td class="text-center"><?php echo $discount_percenrage;?></td>
                                        <?php } ?>
                                            <td class="text-center"><?php echo format_date($row['created_time']);?></td>
                                            <td class="text-center"><?php echo getRoleShortName($row['close_at']);?></td>
                                            <?php if($login_user_role_id == '8' || $login_user_role_id == '9'){?>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-primary md-trigger"  style="padding: 3px;"  data-toggle="modal" data-target="#info<?php echo @$row['margin_approval_id']; ?>" ><i class="fa fa-info"></i></button> 
                                            </td>
                                            <?php } ?>
                                            <td>
                                                <?php

                                                if($login_user_role_id==$row['approval_at']&&in_array($row['product_id'],$user_products))
                                                {
                                                    if($row['status']==1)
                                                    {
                                                    ?>
                                                    <button type="button" class="btn btn-primary md-trigger"  style="padding: 3px;"  data-toggle="modal" data-target="#action<?php echo @$row['margin_approval_id']; ?>" ><i class="fa fa-edit"></i></button> 
                                                <?php
                                                    }
                                                    else
                                                    {
                                                        $status_label = '';
                                                        if($row['status']==2) $status_label = 'Approved';
                                                        if($row['status']==3) $status_label = 'Rejected';
                                                        ?>
                                                        <a class="md-trigger"  style="padding: 3px; cursor:pointer"  data-toggle="modal" data-target="#action<?php echo @$row['margin_approval_id']; ?>" ><?php echo $status_label;?></a>
                                                        <?php
                                                    }
                                                }
                                                else
                                                {
                                                    ?>
                                                    <a class="md-trigger"  style="padding: 3px; cursor:pointer"  data-toggle="modal" data-target="#action<?php echo @$row['margin_approval_id']; ?>" >At <?php echo getRoleShortName($row['approval_at']);?></a>
                                                    <?php
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    ?>	<tr><td colspan="11" align="center"><span class="label label-primary">No Records</span></td></tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                         
                </form> 
                <div class="row">
                    <div class="col-sm-12">
                        <div class="pull-left"><?php echo @$pagermessage ; ?></div>
                        <div class="pull-right">
                            <div class="dataTables_paginate paging_bs_normal">
                                <?php echo @$pagination_links; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>              
    </div>
</div>
<div class="md-overlay"></div>

<?php
foreach (@$quoteSearch as $row)
{

    $ma_data = $ma_arr[$row['quote_revision_id']];
    $opportunities = $quote_ma_arr[$row['quote_revision_id']]['opportunities'];
    include('modals/margin_analysis_modal.php');
    include('modals/margin_analysis_approval_action.php');
}
?>

<?php $this->load->view('commons/main_footer.php', $nestedView); ?>
<style>
    .pagination{
        width: 100%;
    }
</style>