<?php
$this->load->view('commons/main_template', $nestedView);
echo $this->session->flashdata('response');
$leadStatusID = getLeadStatusID($lead_id);
$encode_lead_id = @icrm_encode($lead_id);

?>

<div class="row wizard-row">
    <div class="col-md-12 fuelux">
        <div class="block-wizard">
            <div id="wizard1" class="wizard wizard-ux">
                <?php include_once('train.php'); ?>
            </div>
            <div class="step-content"> 
                <form >
                    <div class="text-right">
                    <?php if($lead_user_id == $this->session->userdata('user_id') && @$checkPage && $leadStatusID != 19) { ?>
                       <button type="button" class="btn btn-primary btn-flat md-trigger" id="add_quote" data-toggle="modal" data-target="#md-scale" data-id="<?php echo $lead_id; ?>"><i class="fa fa-plus"></i> Add Quote</button> 
                    <?php } ?>
                        <!-- Nifty Modal -->
                    </div>    
                    <?php $customer = getCustomerByLead($lead_id);?>
                    <p>Customer: <?php echo $customer['name'];?></p>
                    <div class="table-responsive">
                        <table class="table table-bordered hover">
                            <thead>
                                <tr>
                                    <th class="text-center" width="10%"><strong>Quote ID</strong></th>
                                    <th class="text-center" width='30%'><strong>Opportunity Details</strong></th>
                                    <th class="text-center" width="9%"><strong>Discount</strong></th>
                                    <th class="text-center" width="9%"><strong>Current Stage</strong></th>
                                    <!-- <th class="text-center" width="10%"><strong>Current Status</strong></th> -->
                                    <th class="text-center" width="10%"><strong>Status</strong></th>
                                    <th class="text-center" width="9%"><strong>Final Approver</strong></th>
                                    <th class="text-center" width="10%"><strong>Revisions</strong></th>
                                    <th class="text-center" width="10%"><strong>Actions</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                //@$inc = $start + 1;
                                if (!empty($quoteSearch)) {
                                    $user_products_str = $this->session->userdata('products');
                                    $user_products = explode(',',$user_products_str);
                                    $ma_arr = array(); $quote_ma_arr = array();
                                    
                                    foreach (@$quoteSearch as $quote_revision_id => $opportunities_arr) {
                                        
                                        $j=0;
                                        foreach ($opportunities_arr as $row) {
                                            //print_r($row);
                                            $quote_format_type = getQuoteFormatTypeByQuoteRevisionID($quote_revision_id); // mahesh: 5th Jan 2018
                                            //$qoute_approved_by = getMarginAnalysisApprovalHistory($opportunities_arr['margin_approval_id']);

                                            // added on 26-11-2020
                                            $order_value = $row['mrp'];
                                            $discount = $row['opp_discount'];
                                            if($row['opp_discount_type']!=''&&$discount!='')
                                            $order_value = ($row['opp_discount_type']==1)?($order_value*(1-$row['discount']/100)):($order_value-$discount);
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
                                                $data['basic_price'] = $row['base_price']&$row['required_quantity'];
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
                                            <?php if($j==0) {?>
                                            <td class="text-center" rowspan="<?php echo count($opportunities_arr);?>"><?php echo getQuoteRevisionReferenceID($lead_id,$row['quote_id'], @$quote_revision_id,$row['quote_number']).'-->'. $row['quote_revision_id']; //@$sn        ?></td>
                                            <?php } ?>
                                            <td class="text-center"><?php echo @$row['opportunity'] ?></td>
                                            <?php 
                                                switch ($quote_format_type) {
                                                    case 1: // Old Format
                                                        $discount = round(@$row['discount'],2);
                                                        if($j==0) {?>
                                                        <td class="text-center" rowspan="<?php echo count($opportunities_arr);?>"><?php echo $discount.'%';?></td>
                                                        <?php }
                                                    break;
                                                    case 2: // New Format
                                                       
                                                        $cost = round($row['mrp']*$row['required_quantity']);
                                                        $discount = ($row['opp_discount_type']==1)?$row['opp_discount']:round(($row['opp_discount']/$cost)*100,2);
                                                        $discount = round($discount,2);
                                                        ?>
                                                        <td class="text-center"><?php echo $discount.'%';?></td>
                                                        <?php
                                                    break;
                                                }
                                            ?>
                                            <?php if(!empty($row['user'])){ ?>
                                                <td class="text-center"><?php echo getRoleShortName($row['approved_by']).'-'.$row['user']; ?></td>
                                                <!-- <td class="text-center">Current Status</td> -->
                                            <?php }else{ ?>
                                                <td class="text-center"><?php echo getRoleShortName($row['approval_at']); ?></td>
                                                <!-- <td class="text-center">-</td> -->
                                            <?php } ?>
                                            <?php 
                                                $status_format = ($quote_format_type==1||$row['quote_revision_status']==1)?1:2;
                                                switch ($status_format) {
                                                    case 1: // Quote Status
                                                        if($j==0) {
                                                            $revision_status_label = ($row['quote_revision_status']==3)?'Waiting for approval':getQuoteStatus(@$row['status']);
                                                        ?>
                                                        <td class="text-center" rowspan="<?php echo count($opportunities_arr);?>" align='center'><?php echo  $revision_status_label;?></td>
                                                        <?php }
                                                    break;
                                                    case 2: // Individual opp status
                                                        if($row['app_status']==2){ ?>
                                                            <td class="text-center" align='center'><?php echo "Rejected" ; ?></td>
                                                        <?php }elseif($row['app_status']==1 || $row['status']==2){ ?>
                                                              <?php if($row['close_at'] == $row['approved_by']) {?> 
                                                                <td class="text-center" align='center'><?php echo "Approved" ; ?></td>
                                                                <?php }else{ ?> 
                                                                    <?php if($row['approval_at'] == 7) {?>
                                                                    <td class="text-center" align='center'><?php echo "Pending with RBH" ; ?></td>
                                                                    <?php }if($row['approval_at'] == 8) {?>
                                                                    <td class="text-center" align='center'><?php echo "Pending with NSM" ; ?></td>
                                                                    <?php }if($row['approval_at'] == 9) {?>
                                                                    <td class="text-center" align='center'><?php echo "Pending with CH" ; ?></td>
                                                                    <?php }?>
                                                                <?php }?> 
                                                        <?php }else{ ?>
                                                            <td class="text-center" align='center'><?php echo "Pending with RBH" ; ?></td>
                                                        <?php } 
                                                    break;
                                                }                                                
                                            ?>
                                            <td class="text-center"><?php echo getRoleShortName(@$row['close_at']); ?></td>
                                            <?php if($j==0) {
                                                ?>
                                                <td rowspan="<?php echo count($opportunities_arr);?>">
                                                <?php
                                                if(@$row['status']!=2 || @$row['quote_revision_status']!=2)
                                                {
                                                    if($_SESSION['role_id'] == '8' || $_SESSION['role_id'] == '9')
                                                    {
                                                    ?>
                                                    
                                                <!-- <button type="button" class="btn btn-primary md-trigger"  style="padding: 3px;"  id="add_quote" data-toggle="modal" data-target="#info<?php //echo @$row['quote_id']; ?>" data-id="<?php //echo $lead_id; ?>"><i class="fa fa-info"></i></button> -->
                                                <button type="button" class="btn btn-primary md-trigger"  style="padding: 3px;"  id="add_quote" data-toggle="modal" data-target="#info<?php echo @$row['quote_revision_id']; ?>" data-id="<?php echo $lead_id; ?>"><i class="fa fa-info"></i></button> <?php
                                                    }
                                                }
                                                ?>
                                                <?php if($lead_user_id == $this->session->userdata('user_id') && $leadStatusID != 19) { 
                                                 if(@$row['quote_revision_status']==3){ ?>
                                                    
                                                <?php }elseif(@$row['status'] == 1 || (@$row['status']==2 && @$row['quote_revision_status']==1) || (@$row['status'] == 10)){  ?>
                                                    <a class="btn btn-primary" style="padding: 3px; color:#fff" href="<?php echo SITE_URL.'quoteRevision/'.icrm_encode($row['quote_id']);?>"><i class="fa fa-plus"></i></a>
                                                <?php }
                                             } ?>    
                                            </td>
                                            <td class="text-center" rowspan="<?php echo count($opportunities_arr);?>">
                                                <?php //echo '<pre>';print_r($row); echo '</pre>';
                                                if($row['quote_revision_status']==1 || $row['quote_revision_status']==4){
                                                 ?>
                                                <a target="_blank" href="<?php echo SITE_URL; ?>quotation/<?php echo @icrm_encode($quote_revision_id); ?>" style="padding:3px 3px;" title="Quote View"><button type='button' class="btn btn-primary"  style="padding: 3px;" ><i class="fa  fa-building-o"></i></button></a>
                                                <a href="<?php echo SITE_URL; ?>quotationPdf/<?php echo @icrm_encode($quote_revision_id); ?>" style="padding:3px 3px;" title="Quote Download"><button type='button' class="btn btn-primary" style="padding: 3px;" ><i class="fa fa-cloud-download"></i></button></a>
                                                <?php } ?>
                                            </td>
                                            <?php } ?>
                                        </tr>
                                        <?php
                                            $j++;
                                        }
                                    }
                                } else {
                                    ?>	<tr><td colspan="8" align="center"><span class="label label-primary">No Records</span></td></tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>


                </form>
            </div>
        </div>
    </div>
</div>
<!-- Form  pop up-->
<div class="modal fade colored-header" id="md-scale" role="dialog">
    <div class="modal-dialog">

<!--<div class="md-modal colored-header custom-width md-effect-1" id="md-scale" style="width: 70%;">-->
    <div class="md-content">
        <div class="modal-header">
            <h3>Add Quote</h3>
            <button type="button" class="close md-close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
        <form class="form-horizontal" role="form" action="<?php echo SITE_URL; ?>quoteAdd" id="quotation_frm"    method="post"  >
        <div class="modal-body form">
        <br>
                <!-- <div class="form-group">
                    <label class="col-sm-3 control-label">Category<span class="req-fld">*</span></label>
                    <div class="col-sm-7">

                        <?php
                        $attrs = ' required class="select2" id="category" style=" width:100%"  ';
                        echo form_dropdown("category", @$productCategories, '', @$attrs);
                        ?>
                    </div>
                </div> -->

                <div class="form-group">
                    
                    <label class="col-sm-3 control-label">Opportunity <span class="req-fld">*</span></label>
                    <div class="col-sm-9">


                        <div class="table-responsive " style="width:100%;">
                            <table class="table no-border hover" style="width:100%;">
                                <thead>
                                    <tr>

                                        <th class="text-center"></th>
                                        <th><strong>Product Name</strong></th>
                                        <th><strong>Description</strong></th>
                                        <!--<?php //if($lead_user_role_id == 5) { ?>
                                            <th><strong>DP</strong></th>
                                        <?php //}else{?> -->
                                            <th><strong>MRP</strong></th>
                                        <?php //} ?>
                                        <!-- <th><strong>Sub Category</strong></th> -->
                                        <th class="text-center"><strong>Quantity</strong></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    // print_r($opportunities);
                                    if (count($opportunities) > 0) {
                                        foreach ($opportunities as $v) {
                                            ?>

                                            <tr class="opprow op_row" data-cat-id="<?php echo $v['category_id']; ?>">

                                                <td class="text-center"><input type="checkbox" name="op_id[]" value="<?php echo $v['opportunity_id']; ?>" class="op" > </td>
                                                <td><?php echo @$v['product_name']; ?></td>
                                                <td><?php echo @$v['description']; ?></td>
                                                <td>
                                                <?php //if($lead_user_role_id == 5) { ?>
                                                    <?php// echo indian_format_price(@$v['dp']); ?>
                                                    <!-- <input type="hidden" class="opp_value order_val" value="<?php // echo $v['dp']*$v['required_quantity'];?>"> -->
                                                <?php //}else{?>
                                                    <?php echo indian_format_price(@$v['mrp']); ?>
                                                    <input type="hidden" class="opp_value order_val" value="<?php echo $v['mrp']*$v['required_quantity'];?>">
                                                <?php// } ?>
                                                <input type="hidden" name="sub_category_id[<?php echo $v['opportunity_id']; ?>]" value="<?php echo $v['sub_category_id'];?>">

                                                <!-- added on 07-07-2021 for warranty -->
                                                <input type="hidden" name="warranty" value="<?php echo $v['warranty'];?>">
                                                <!-- added on 07-07-2021 for warranty end -->
                                                </td>
                                                <!-- <td>
                                                    <?php
                                                    /*$sub_category = $this->quote_model->getProductSubCategory($v['product_id']);
                                                    echo form_dropdown('sub_category_id[]', $sub_category, '','class="form-control subcat" required disabled');*/ ?>

                                                </td> -->
                                                <td class="text-center"> <?php echo @$v['required_quantity']; ?></td>
                                            </tr>


                                        <?php } ?>
                                            <?php /*below checkbox for vlaidation
                                            <tr  colspan="5">
                                                <td class="text-center">
                                                    <input style="display:none;" readonly="true" type="checkbox" parsley-mincheck="1" name="op_id[]" value="" > 
                                                </td>
                                            </tr>*/?>
                                    </tbody>
                                    <tbody>

                                    <?php } else { ?>
                                        <tr><td colspan="5" align="center">Opportunities not Found.</td></tr>



                                    <?php } ?>
                                    </tbody>
                            </table>


                        </div>
                    </div>
                </div>
                <?php
                if($this->session->userdata('role_id')==5) // If Distributor
                {
                    ?>
                    <input type="hidden" name="billing_name" value="2">
                    <?php
                }
                else
                {
                ?>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Billing Name<span class="req-fld">*</span></label>
                    <div class="col-sm-7">

                        <?php
                        $attrs = ' required class="select2 billing" id="billing" style=" width:100%"  ';
                        echo form_dropdown("billing_name", @$billing_name, '', @$attrs);
                        ?>
                    </div>
                </div>
                <?php
                }
                ?>
                <div class="form-group" style="display:none;" id='stokist_div' >
                    <label class="col-sm-3 control-label">Stokist<span class="req-fld">*</span></label>
                    <div class="col-sm-7" id='stokist'>
                        <select style=" width:100%"  id="stokist_id" class="select2"  name="stokist_id">

                        </select>
                        <input type="hidden" name="discount" value="0">
                        <input type="hidden" value="<?php echo $lead_id; ?>" name="lead_id">
                    </div>
                </div>
                <!-- Mahesh Phase2 Capture additional terms in quote start -->
                <div class="form-group hidden">
                    <label class="col-sm-3 control-label">Warranty<span class="req-fld">*</span></label>
                    <div class="col-sm-7">
                        <select name="warranty" class="select2" required style="width:100%">
                            <?php 
                            $default_warranty = get_preference('default_warranty','general_settings');
                            for($i=12; $i<=60; $i+=3){
                                $selected = ($default_warranty==$i)?'selected':'';
                                echo "<option value=".$i." ".$selected.">".$i." Months"."</option>";
                                }
                            ?> 
                    </select>
                    </div>
                </div>
                <div class="form-group hidden">
                    <label class="col-sm-3 control-label">Advance <span class="req-fld">*</span></label>
                    <div class="col-sm-2">
                        <select name="advance_type" id="advance_type" class="form-control">
                            <option value="1">in %</option>
                            <option value="2">in Rs</option>
                        </select>
                    </div>
                    <div class="col-sm-5">
                        <input type="number" min="0" max="100" class="form-control" placeholder="Advance Collected" name="advance" id="advance_collected" value="<?php echo get_preference('default_advance_percentage','general_settings');?>" required>
                    </div>
                </div>
                <div class="form-group bal_payment_block hidden">
                    <label class="col-sm-3 control-label">Balance Payment in <span class="req-fld">*</span></label>
                    <div class="col-sm-7">
                        <div class="input-group">
                            <input type="number" value="0" min="0" name="balance_payment_days" id="balance_payment_days" class="form-control">
                            <span class="input-group-addon">days</span>
                        </div>
                    </div>
                </div>
                <?php
                if($this->session->userdata('role_id')!=5) // If not Distributor
                {
                ?>
                <div class="form-group" id="dealer_commission_row">
                    <label class="col-sm-3 control-label">Dealer Commission (%) </label>
                    <div class="col-sm-7">
                        <input type="number" class="form-control" step=".01" min="0" max="100" placeholder="Dealer Commission (%)" name="dealer_commission" id="dealer_commission">
                    </div>
                </div>
                <div class="form-group hidden" id="dealer_row">
                    <label class="col-sm-3 control-label">Dealer <span class="req-fld">*</span></label>
                    <div class="col-sm-7">
                        <select class="select2" name="dealer" id="dealer" style="width:100%">
                            <option value="">Select Dealer</option>
                           <?php 
                           foreach ($dealers as $dealer) {
                               echo '<option value="'.$dealer['user_id'].'">'.$dealer['distributor_name'].' ('.$dealer['employee_id'].')</option>';
                           }
                           ?>
                        </select>
                    </div>
                </div>
                <?php
                }
                ?>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Billing Through <span class="req-fld">*</span></label>
                    <div class="col-sm-7">
                        <select class="select2" required name="channel_partner_id"  style="width:100%">
                            <option value="">Select Billing Through</option>
                           <?php 
                           foreach ($channel_partners as $row) {
                               echo '<option value="'.$row['channel_partner_id'].'">'.$row['name'].'</option>';
                           }
                           ?>
                        </select>
                    </div>
                </div>
                <!-- Mahesh Phase2 Capture additional terms in quote end -->
                
            <style>
                .table.no-border tr td, .table.no-border tr th {
                    border-width: 0;
                }
            </style>
        </div>
        <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-flat md-close" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" type="submit" name="submitQuote" value="button"><i class="fa fa-check"></i> Submit</button>
        </div>
        </form>

    </div>
</div>
</div>
<div class="md-overlay"></div>

<?php
// foreach (@$quoteSearch as $quote_revision_id => $opportunities_arr) {
//      foreach ($opportunities_arr as $row) {
//         include('modals/quote_info_modal.php');
//     }
// }

foreach (@$quoteSearch as $opportunities_arr)
{
    foreach ($opportunities_arr as $row) {
        $ma_data = $ma_arr[$row['quote_revision_id']];
        $opportunities = $quote_ma_arr[$row['quote_revision_id']]['opportunities'];
        $distributor_name = $row['distributor_name'];
        include('modals/quote_info_modal.php');
    }

    
}
?>

<?php $this->load->view('commons/main_footer.php', $nestedView); ?>
<script type="text/javascript">
$(document).ready(function(){
    $("#category").change(function()
    {
        var cat_id = $(this).val();
        $(".opprow").each(function( index ) 
        {
            $(this).find('input:checkbox').attr('checked',false);
            $(this).show();
            var data_cat_id = $(this).data('cat-id');
            if(cat_id!=data_cat_id)
              $(this).hide();
        });
    });
});

$(document).ready(function(){
    $("#discount_rev").blur(function()
    {
        var discount = $(this).val();
        var totPrice = $("#totPrice").val();
        var frieght_insurance = 2;
        discount = (discount == '')?0:discount;
        //totPrice - totPrice*(discount/100)*(1+(frieght_insurance/100))
        discount1 = discount*(1+(frieght_insurance/100));
        totalPrice = Math.round(totPrice*(1-(discount/100)));
        $('#totalPrice').html(totalPrice);
    });
});



</script>
