<!--<div class="md-modal colored-header custom-width md-effect-9" id="<?php //echo @$pop_id; ?>">-->
<div class="modal fade colored-header" id="add<?php echo @$row['quote_id']; ?>" role="dialog">
    <form action="<?php echo SITE_URL; ?>addQuoteRevision" method="post" novalidate="" parsley-validate="" class="form-horizontal" id='quote_revision_frm'>
    <div class="modal-dialog">
        <div class="md-content">
            <div class="modal-header">
                <span style="font-size:18px">Add New Revision to Quote</span>
                <button type="button" class="close md-close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>

            <div class="modal-body form">
                <input type="hidden" name="quote_id" value="<?php echo @$row['quote_id']; ?>">
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
                                            <!-- <th class="text-center"></th> -->
                                            <th><strong>Product Name</strong></th>
                                            <th><strong>Description</strong></th>
                                            <th class="text-center"><strong>Quantity</strong></th>
                                            <th colspan="2" align="center"><strong>Discount</strong></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        // print_r($opportunities);
                                        if (count($opportunities) > 0) {
                                            foreach ($opportunities as $v) {
                                                ?>

                                                <tr class="quote_opprow">

                                                    <!-- <td class="text-center"><input type="checkbox" name="op_id[]" value="<?php echo $v['opportunity_id']; ?>" class="quote_op" > </td> -->
                                                    <input type="hidden" name="op_id[]" value="<?php echo $v['opportunity_id']; ?>" class="quote_op" >
                                                    <td><?php echo @$v['product_name']; ?></td>
                                                    <td><?php echo @$v['description']; ?></td>
                                                    <td class="text-center"> <?php echo @$v['required_quantity']; ?></td>
                                                    <td>

                                                        <select name="discount_type[<?php echo $v['opportunity_id']; ?>]" class="discount_type form-control">
                                                        <?php
                                                            foreach ($discount_types as $key => $value) {
                                                                echo '<option value="'.$key.'">In '.$value.'</option>';
                                                            }
                                                        ?>
                                                        </select>
                                                    </td>
                                                    <td><input type="text" name="discount[<?php echo $v['opportunity_id']; ?>]" class="form-control op_discount" ></td>
                                                </tr>
                                                <tr class="free_row">
                                                    <td colspan="1">
                                                        <input type="checkbox" name="free_check" class="free_check"> Free Supply Items
                                                    </td>
                                                    <td colspan="4" >
                                                        <div class="col-sm-9 free_supplys hidden" style="padding-left:0px;">    
                                                            <table border="1" cellspacing="0" class="free_table"
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
                                                                        <select class="select3 free_item" style="width:100%" name="product_id[]">
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
                                                                        class='form-control qty only-numbers free_item_qty'
                                                                        id="qty_1" name='qty[]'/>
                                                                    </td>
                                                                     <td colspan="3">
                                                                         
                                                                         <button type="button" class='btn delete btn-danger' style="padding: 3px;"><i class="fa fa-times"></i></button>
                                                                         
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
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Billing <span class="req-fld">*</span></label>
                        <div class="col-sm-7">
                            <input type="hidden" name="quote_id" value="<?php echo @$row['quote_id']; ?>">
                            <?php
                            $attrs = " required class='select2 billing' id='billing_rev' style='width:100%'  ";
                            echo form_dropdown("billing_name", @$billing_name, '', @$attrs);
                            ?>
                        </div>
                    </div>
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
                            <select name="warranty" class="select2" style="width:100%">
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
                            <input type="number" class="form-control" placeholder="Advance Collected" value="<?php echo $row['advance'];?>" name="advance" id="advance_collected" required>
                        </div>
                    </div>
                    <div class="form-group bal_payment_block">
                        <label class="col-sm-3 control-label">Balance Payment in <span class="req-fld">*</span></label>
                        <div class="col-sm-7">
                            <div class="input-group">
                                <input type="number" name="balance_payment_days" value="<?php echo $row['balance_payment_days'];?>" id="balance_payment_days" class="form-control">
                                <span class="input-group-addon">days</span>
                            </div>
                        </div>
                    </div>
                    <?php
                    if($this->session->userdata('role_id')!=5) // If not Distributor
                    {
                    ?>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Dealer Commission (%) <span class="req-fld">*</span></label>
                        <div class="col-sm-7">
                            <input type="number" class="form-control" max="100" placeholder="Dealer Commission (%)" name="dealer_commission" value="<?php echo $row['dealer_commission'];?>" required>
                        </div>
                    </div>
                    <?php
                    }
                    ?>

            </div>	
            <div class="modal-footer">
                <span class="opp_error error" style="color:red;float:left;font-weight:bold;"></span>
                <button type="button" class="btn btn-default btn-flat md-close" data-dismiss="modal">Cancel</button>
                <button type="submit" name="submitAddRevision" value="1" class="btn btn-primary btn-flat">Submit</button>
            </div>
        </div>	
    </div>	
        </form>
</div>
<div class="md-overlay"></div>