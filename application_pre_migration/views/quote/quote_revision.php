<?php
$this->load->view('commons/main_template', $nestedView);
echo $this->session->flashdata('response'); 
?>

<div class="row"> 
    <div class="col-sm-12 col-md-12">
        <div class="block-flat">
            <div class="content">
                <div class="content">
                <form action="<?php echo SITE_URL; ?>addQuoteRevision" method="post"  class="form-horizontal" id='quote_revision_frm'>
                <input type="hidden" name="quote_id" value="<?php echo @$row['quote_id']; ?>">
                <input type="hidden" name="prev_quote_revision_id" value="<?php echo @$row['quote_revision_id']; ?>">
                <input type="hidden" name="lead_id" value="<?php echo @$lead_id; ?>">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Quote ID</label>
                        <div class="col-sm-7">
                            <input type="text" disabled style="width:100%" value="<?php echo getQuoteReferenceID1($lead_id, @$row['quote_id']); ?>">
                        </div>
                    </div>
                    <?php
                    $opportunities = getQuoteOpportunities($row['quote_id']);
                    ?>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <div class="table-responsive " style="width:100%;">
                                <table class="table no-border hover" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th class="text-center"></th>
                                            <th width="20%"><strong>Product Name</strong></th>
                                            <th><strong>Description</strong></th>
                                            <th class="text-center"><strong>Quantity</strong></th>
                                            <th class="text-center"><strong>MRP</strong></th>
                                            <th colspan="1" align="center"><strong>Discount</strong></th>
                                            <th colspan="1" align="center"><strong>Discounted Value</strong></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        //print_r($opportunities);
                                        $quote_value = 0;
                                        if (count($opportunities) > 0) {
                                            foreach ($opportunities as $v) {
                                                $discount = (@$op_details[$v['opportunity_id']]['discount']>0)?@$op_details[$v['opportunity_id']]['discount']:0;
                                                $disc_type = @$op_details[$v['opportunity_id']]['discount_type'];
                                                $mrp = $v['required_quantity']*$v['mrp'];
                                                $discounted_value = round((@$op_details[$v['opportunity_id']]['discount_type']==1)?($mrp*(1-$discount/100)):($mrp-$discount));
                                                $max_discount = ($disc_type==1)?100:$mrp;
                                                $quote_value += $discounted_value;
                                                ?>

                                                <tr class="quote_opprow op_row">

                                                    <td class="text-center">
                                                        <input type="checkbox" name="rev_op_id[]" value="<?php echo $v['opportunity_id']; ?>" class="rev_quote_op" > 
                                                    </td>
                                                    <input type="hidden" name="op_id[]" value="<?php echo $v['opportunity_id']; ?>" class="quote_op" >

                                                    <td><span><?php echo getApprovalStatusIcon(@$op_details[$v['opportunity_id']]['status']);?></span> <?php echo @$v['product_name']; ?> </td>
                                                    <td><?php echo @$v['description']; ?></td>
                                                    <td class="text-center"> <?php echo @$v['required_quantity']; ?></td>
                                                    <td class="text-right"> <?php echo indian_format_price(@$v['required_quantity']*$v['mrp']); ?>
                                                    <input type="hidden" name="opp_value" class="opp_value" value="<?php echo $v['required_quantity']*$v['mrp'];?>">
                                                    </td>
                                                    <td>

                                                        <select disabled name="discount_type[<?php echo $v['opportunity_id']; ?>]" class="discount_type form-control" style="width:70px;display:inline">
                                                        <?php
                                                            foreach ($discount_types as $key => $value) {
                                                                $selected = ($key==@$disc_type)?'selected':'';
                                                                echo '<option value="'.$key.'" '.$selected.'>In '.$value.'</option>';
                                                            }
                                                        ?>
                                                        </select>
                                                    <input type="number" disabled value="<?php echo @$op_details[$v['opportunity_id']]['discount']?>" min="0" max="<?php echo $max_discount;?>" step=".01" name="discount[<?php echo $v['opportunity_id']; ?>]" class="form-control op_discount" style="width:150px;display:inline"></td>
                                                    <td>
                                                        <input type="checkbox" checked="" class="op hidden" value="<?php echo $v['opportunity_id']; ?>">
                                                        <input type="hidden"  value="<?php echo $discounted_value; ?>" class="order_val" >
                                                        <span class="discounted_value"><?php echo $discounted_value;?></span>
                                                        <input type="hidden" class="prev_disc_type" value="<?php echo $disc_type?>">
                                                        <input type="hidden" class="prev_disc" value="<?php echo $discount?>">
                                                        <input type="hidden" class="prev_disc_val" value="<?php echo $discounted_value?>">
                                                    </td>
                                                </tr>
                                                <tr class="free_row hidden" id="free_<?php echo $v['opportunity_id'];?>">
                                                    <td></td>
                                                    <td colspan="1">
                                                        <input type="checkbox" name="free_check" class="free_check"> Free Supply Items
                                                    </td>
                                                    <td colspan="5" >
                                                        <div class="col-sm-9 free_supplys hidden" style="padding-left:0px;">    
                                                            <table border="1" cellspacing="0" class="free_table table table-striped table-hover table-bordered"
                                                                class="table table-striped table-hover table-bordered ">
                                                                <thead>
                                                                <tr>
                                                                    <th  width="55%">Free Supply Item/s</th>
                                                                    <th width="15%">Qty </th>
                                                                    <th width="15%"> </th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <tr class="free_product_row">
                                                                   
                                                                    <td>
                                                                     <?php
                                                                        //echo form_dropdown('product_id[]', $product_id, @$searchParams['product_id'],'class="select3" style="width:100%"'); ?>
                                                                        <select class="select3 free_item_product" style="width:100%" name="product_id_<?php echo $v['opportunity_id'];?>[]">
                                                                            <option value="">Select Product</option>
                                                                            <?php
                                                                            foreach($products as $prow)
                                                                            {
                                                                                echo '<option value="'.$prow['product_id'].'" data-unitPrice="'.$prow['rrp'].'">'.$prow['name'].' ( '.$prow['description'].')</option>';
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <input type="hidden" class="free_item_unitprice" value="0">
                                                                        <input type='number' min="1" max="100000"
                                                                        class='form-control only-numbers free_item_qty'
                                                                        id="qty_1" name='qty_<?php echo $v['opportunity_id'];?>[]'/>
                                                                    </td>
                                                                     <td colspan="3">
                                                                         
                                                                         <button type="button" class='btn delete_free_item btn-danger' style="padding: 3px;"><i class="fa fa-times"></i></button>
                                                                         
                                                                     </td>
                                                                </tr>
                                                               
                                                                </tbody>
                                                            </table>
                                                                <button type="button" class='btn add_free_item btn-primary ' style="padding: 3px;"><i class="fa fa-plus"></i></button>
                                                                
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php } ?>

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
                        <label class="col-sm-3 control-label">Billing <span class="req-fld">*</span></label>
                        <div class="col-sm-7">
                            
                            <?php
                            $attrs = " required class='select2 billing' id='billing_rev' style='width:100%'  ";
                            echo form_dropdown("billing_name", @$billing_name, '', @$attrs);
                            ?>
                        </div>
                    </div>
                    <?php
                    }
                    ?>
                    <div class="form-group stokist_div" style="display:none;">
                        <label class="col-sm-3 control-label">Stokist<span class="req-fld">*</span></label>
                        <div class="col-sm-7" id='stokist'>
                            <select style=" width:100%"  id="stokist_id_rev" class="select2 stokist_id"  name="stokist_id">

                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Warranty<span class="req-fld">*</span></label>
                        <div class="col-sm-7">
                            <input type="hidden" name="quote_id" value="<?php echo @$row['quote_id']; ?>">
                            <select name="warranty" required class="select2" style="width:100%">
                                <option value="">Select</option>
                                <?php 
                                for($i=12; $i<=60; $i+=3){
                                        $selected = ($i==$row['warranty'])?'selected':'';
                                    echo "<option value=".$i." ".$selected.">".$i." Months"."</option>";
                                    }
                                ?> 
                        </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Advance <span class="req-fld">*</span></label>
                        <div class="col-sm-2">
                            <select name="advance_type" id="advance_type" class="form-control">
                                <option value="1" <?php if($row['advance_type']==1) echo 'selected';?>>in %</option>
                                <option value="2" <?php if($row['advance_type']==2) echo 'selected';?>>in Rs</option>
                            </select>
                        </div>
                        <div class="col-sm-5">
                            <?php $max_advance = ($row['advance_type']==1)?100:$quote_value; ?>
                            <input type="number" class="form-control" min="0" max="<?php echo $max_advance;?>" step=".01" placeholder="Advance Collected" value="<?php echo $row['advance'];?>" name="advance" id="advance_collected" required>
                        </div>
                    </div>
                    <?php  
                    
                    if(($row['advance_type']==1&&$row['advance']==100)||$row['advance_type']==2&&$row['advance']==$quote_value)
                    {
                        $bal_payment_cls = 'hidden';
                        $bal_payment_attr = '';
                        $bal_payment_val = 0;
                    }
                    else
                    {
                        $bal_payment_cls = '';
                        $bal_payment_attr = 'required';
                        $bal_payment_val = $row['balance_payment_days'];
                    }
                        ?>
                    <div class="form-group bal_payment_block <?php echo @$bal_payment_cls;?>">
                        <label class="col-sm-3 control-label">Balance Payment in <span class="req-fld">*</span></label>
                        <div class="col-sm-7">
                            <div class="input-group">
                                <input type="number" name="balance_payment_days" <?php echo @$bal_payment_attr;?> min="0" value="<?php echo @$bal_payment_val;?>" id="balance_payment_days" class="form-control">
                                <span class="input-group-addon">days</span>
                            </div>
                        </div>
                    </div>
                    <?php
                    if($this->session->userdata('role_id')!=5) // If not Distributor
                    {
                        $dealer_row_cls = ($row['dealer_commission']>0)?'':'hidden';
                    ?>
                    <div class="form-group" id="dealer_commission_row">
                        <label class="col-sm-3 control-label">Dealer Commission (%) <span class="req-fld">*</span></label>
                        <div class="col-sm-7">
                            <input type="number" id="dealer_commission" class="form-control" step=".01" min="0" max="100" placeholder="Dealer Commission (%)" name="dealer_commission" value="<?php echo $row['dealer_commission'];?>">
                        </div>
                    </div>
                    <div class="form-group <?php echo $dealer_row_cls?>" id="dealer_row">
                        <label class="col-sm-3 control-label">Dealer <span class="req-fld">*</span></label>
                        <div class="col-sm-7">
                            <select class="select2" name="dealer" id="dealer" style="width:100%">
                                <option value="">Select Dealer</option>
                               <?php 
                               foreach ($dealers as $dealer) {
                                   $selected = ($dealer['user_id']==$row['dealer_id'])?'selected':'';
                                   echo '<option value="'.$dealer['user_id'].'" '.$selected.'>'.$dealer['distributor_name'].' ('.$dealer['employee_id'].')</option>';
                               }
                               ?>
                            </select>
                        </div>
                    </div>
                    <?php
                    }
                    ?>
                    <div class="form-group">
                    <label class="col-sm-3 control-label">Billing Through </label>
                    <div class="col-sm-7">
                        <select class="select2" disabled name="channel_partner_id"  style="width:100%">
                            <option value="">Select Billing Through</option>
                           <?php 
                             $selected='';
                           foreach ($channel_partners as $row) { 
                                   $selected = ($row['channel_partner_id']==$channel_partner_id)?'selected':'';
                               echo '<option value="'.$row['channel_partner_id'].'"'.$selected.'>'.$row['name'].'</option>';
                           }
                           ?>
                        </select>
                    </div>
                </div>

            
            <div class="row">
                <div class="col-sm-12">
                    <div class="col-sm-6">
                        <span class="opp_error error" style="color:red;float:left;font-weight:bold;"></span>
                    </div>
                    <div class="col-sm-6">
                        <a class="btn btn-default btn-flat md-close" href="<?php echo SITE_URL.'openQuoteDetails/'.icrm_encode($lead_id);?>">Cancel</a>
                        <button type="submit" name="submitAddRevision" value="1" class="btn btn-primary btn-flat">Submit</button>
                    </div>
                </div>
            </div>
            </form>
            </div>
        </div>              
    </div>
</div>
<div class="md-overlay"></div>
<?php $this->load->view('commons/main_footer.php', $nestedView); ?>
<style>
    .pagination{
        width: 100%;
    }
</style>