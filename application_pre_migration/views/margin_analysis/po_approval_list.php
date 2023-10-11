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
                        <form role="form" class="form-horizontal" method="post" action="<?php echo SITE_URL; ?>po_approval_list">
                            <div class="col-sm-12">
                                
                                <label class="col-sm-2 control-label">PO ID </label>
                                <div class="col-sm-2">
                                    <input type="text" name="purchase_order_id" placeholder="PO ID" maxlength="20"  value="<?php echo @$searchParams['purchase_order_id']; ?>" class="form-control">
                                </div>
                                <label class="col-sm-2 control-label">Product Details </label>
                                <div class="col-sm-3">
                                    <input type="text" name="product_details" placeholder="Product Details" maxlength="60"  value="<?php echo @$searchParams['product_details']; ?>" class="form-control">
                                </div>
                             <div class="col-sm-2">
                                    <button type="submit" name="searchApprveQuote" value="1" class="btn btn-success"><i class="fa fa-search"></i></button>
                                    <a href="<?php echo SITE_URL.'po_approval_list'?>" class="btn btn-success"><i class="fa fa-refresh"></i></a>
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
                                    <th class="text-center" width="17%"><strong>Distributor</strong></th>
                                    <th class="text-center" width="8%"><strong>PO ID</strong></th>
                                    <th class="text-center" width='18%'><strong>Product Details</strong></th>
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
                                    <th class="text-center" width="8%"><strong>Discount %</strong></th>
                                    <th class="text-center" width="5%"><strong>Final Approver</strong></th>
                                    <th class="text-center" width="3%"><strong>View</strong></th>
                                    <th class="text-center" width="3%"><strong>Actions</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                //@$inc = $start + 1;
                                if (!empty($quoteSearch)) {
                                    $ma_arr = array(); $po_ma_arr = array();
                                    foreach (@$quoteSearch as $row) {
                                            $order_value = $row['mrp'];
                                            $discount = $row['discount'];
                                            if($row['discount_type']!=''&&$discount!='')
                                            $order_value = ($row['discount_type']==1)?($order_value*(1-$row['discount']/100)):($order_value-$discount);
                                            $nsp = round($order_value/(1+$row['freight_insurance']/100)/(1+$row['gst']/100));
                                            $discount_percenrage = round((($row['mrp'] - $order_value )/$row['mrp'])*100,2);
                                            $data = array();
                                            $res=get_extra_warranty_cost($order_value,$row['dp'],$row['warranty'],$row['default_warranty']); 
                                            $order_value = $res['grand_total'];
                                            $data['order_value'] = $order_value;
                                            $data['net_selling_price'] = $nsp;
                                            $data['dp'] = $row['dp'];
                                            $data['basic_price'] = round($row['base_price']);
                                            $data['total_warranty_in_years'] = ($row['warranty']>0)?round(($row['warranty']/12),2):0;
                                            $advance = 0;
                                            if($row['advance']!='')
                                            {
                                                switch ($row['advance_type']) {
                                                    case 1:
                                                        $advance = $row['advance'];
                                                    break;
                                                    
                                                    case 2:
                                                        $po_total_order_value = getPoRevisionTotalOrderValue($row['po_revision_id']);
                                                        $advance = round(($row['advance']/$po_total_order_value)*100,2);
                                                    break;
                                                }
                                                
                                            }
                                            $data['advance'] = $advance;
                                            $data['balance_payment_days'] = ($row['balance_payment_days']!='')?$row['balance_payment_days']:0;
                                            $data['dealer_commission'] = 0;
                                            $data['cost_of_free_supply'] = 0;
                                            $data['free_supply'] = '';
                                            $data['exclude_extra_warranty_in_nm'] = 1;
                                            $product_ma_data = marginAnalysis($data);
                                            /*$ma_data['discount_percenrage'] = $discount_percenrage;
                                            $ma_arr[$row['approval_id']] = $ma_data;*/
                                            if(!in_array($row['po_revision_id'], $po_ma_arr))
                                            {
                                                $product_results = getProductsInfoByPoRevision($row['po_revision_id']);
                                                $po_ma_arr[$row['po_revision_id']]['products'] = $product_results;
                                                $total_mrp = $total_order_value = $total_discount = $total_base_price = $total_dp = 0;
                                                $freight_insurance = 2; $gst = 12;
                                                foreach ($product_results as $op_row) {
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
                                                }

                                                $nsp = round($total_order_value/(1+$freight_insurance/100)/(1+$gst/100));
                                                $quote_discount_percenrage = round((($total_mrp - $total_order_value )/$total_mrp)*100,2);
                                                $data = array();
                                                $res=get_extra_warranty_cost($total_order_value,$total_dp,$row['warranty'],$row['default_warranty']); 
                                                $total_order_value = $res['grand_total'];
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
                                                $data['dealer_commission'] = 0;
                                                $data['cost_of_free_supply'] = 0;
                                                $data['free_supply'] = '';
                                                $data['exclude_extra_warranty_in_nm'] = 1;
                                                $ma_data = marginAnalysis($data);
                                                $ma_data['discount_percenrage'] = $quote_discount_percenrage;
                                                $ma_arr[$row['po_revision_id']] = $ma_data;
                                            }
                                            //echo '<pre>';   print_r($product_ma_data); echo '</pre>';
                                        ?>
                                        <tr>
                                            <td class="text-center"><span data-toggle="tooltip" data-original-title="<?php echo $row['user'];?>"><?php echo $row['distributor_name']; ?></span></td>
                                            <td><?php echo @$row['purchase_order_id']; ?></td>
                                            <td><?php echo @$row['product_details']; ?></td>
                                            <td class="text-center"><?php echo indian_format_price(round($order_value)); ?></td>
                                            <?php 
                                            if($login_user_role_id==8||$login_user_role_id==9) // NSM , CH
                                            {
                                            ?>
                                            <td class="text-center" align='cneter'><?php echo $product_ma_data['gross_margin_percentage'];?></td>
                                            <td class="text-center" align='cneter'><?php echo $product_ma_data['net_margin_percentage'];?></td>
                                            <?php
                                            }
                                            ?>
                                            <td class="text-center"><?php echo $discount_percenrage;?></td>
                                            <td class="text-center"><?php echo getRoleShortName($row['close_at']);?></td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-primary md-trigger"  style="padding: 3px;"  data-toggle="modal" data-target="#info<?php echo @$row['approval_id']; ?>" ><i class="fa fa-info"></i></button> 
                                            </td>
                                            <td>
                                                <?php
                                                if($login_user_role_id==$row['approval_at'])
                                                {
                                                    ?>
                                                <button type="button" class="btn btn-primary md-trigger"  style="padding: 3px;"  data-toggle="modal" data-target="#action<?php echo @$row['approval_id']; ?>" ><i class="fa fa-edit"></i></button> 
                                                <?php
                                                }
                                                else
                                                {
                                                    ?>
                                                    <a class="md-trigger"  style="padding: 3px; cursor:pointer"  data-toggle="modal" data-target="#action<?php echo @$row['approval_id']; ?>" >At <?php echo getRoleShortName($row['approval_at']);?></a>
                                                    <?php
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    ?>	<tr><td colspan="10" align="center"><span class="label label-primary">No Records</span></td></tr>
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

    $ma_data = $ma_arr[$row['po_revision_id']];
    $products = $po_ma_arr[$row['po_revision_id']]['products'];
    include('modals/po_margin_modal.php');
    include('modals/po_approval_action.php');
}
?>

<?php $this->load->view('commons/main_footer.php', $nestedView); ?>
<style>
    .pagination{
        width: 100%;
    }
</style>