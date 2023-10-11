<?php
$this->load->view('commons/main_template', $nestedView);
echo $this->session->flashdata('response'); 
?>
<div class="row"> 
    <div class="col-sm-12 col-md-12">
        <div class="block-flat">
            <div class="content">
                <div class="row no-gutter">
                    <form class="form-horizontal" role="form" action="<?php echo SITE_URL; ?>submitPoRevision" method="post">
                        <input type="hidden" name="encoded_id" value="<?php echo icrm_encode($po_results['purchase_order_id'])?>">
                        <?php $show_warranty = get_preference('enable_warranty','dealer_settings');
                        if($show_warranty==1) {
                        ?>
                        <div class="form-group warranty_block">
                            <label class="col-sm-3 control-label">Warranty<span class="req-fld">*</span></label>
                                <div class="col-sm-6">
                                    <select name="warranty"  class="select2 warranty" style="width:100%">
                                        <option value="">Select</option>
                                        <?php $selected='';
                                        for($i=12; $i<=60; $i+=3){
                                            if($i==@$po_results['warranty'])
                                                {
                                                    $selected="selected";
                                                } 
                                                else
                                                {
                                                    $selected='';
                                                }
                                            echo "<option value=".$i." ".$selected.">".$i." Months"."</option>";
                                            }
                                        ?> 
                                    </select>
                                </div>
                        </div>
                        <?php
                        }
                        else
                        {
                            ?>
                            <input type="hidden" name="warranty" value="<?php echo getDefaultWarranty();?>">
                            <?php
                        }
                        ?>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Advance Payment<span class="req-fld">*</span></label>
                                <div class="col-sm-2">
                                    <select name="advance_type" required id="advance_type" class="form-control">
                                        <option value="1" <?php if(@$po_results['advance_type']==1){
                                            echo "selected";
                                            } ?> >in %</option>
                                        <option value="2" <?php if(@$po_results['advance_type']==2){
                                            echo "selected";
                                            } ?> >in Rs</option>
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <input type="number" class="form-control" placeholder="Advance Collected" min="0" max="100" name="advance" id="advance_collected" required  value="<?php echo @$po_results['advance'];?>">
                                </div>
                        </div>
                        <div class="form-group bal_payment_block">
                            <label class="col-sm-3 control-label">Balance Payment in <span class="req-fld">*</span></label>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <input type="number" required value="<?php echo @$po_results['balance_payment_days'];?>" min="0" max="<?php echo get_preference('max_balance_payment_days','dealer_settings');?>"  name="balance_payment_days" id="balance_payment_days" class="form-control">
                                    <span class="input-group-addon">days</span>
                                </div>
                            </div>
                        </div>
                         <div class="table-responsive  col-lg-12">
                            <div class="col-sm-12" style="padding-left:0px;">    
                                <table border="1" cellspacing="0" id="table1"
                                    class="table table-striped table-hover table-bordered ">
                                    <thead>
                                    <tr>
                                        <th width="13%">Product Segment</th>
                                        <th width="27%">Product</th>
                                        <th width="10%">Unit Price</th>
                                        <th width="8%">Qty </th>
                                        <th width="20%">Discount </th>
                                        <th width="15%">Discounted Value </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if(@$product_results) 
                                    {   $total=0;
                                        foreach(@$product_results as $row)
                                        { 
                                            $discount = $row['discount'];
                                            $product_value = $row['unit_price']*$row['qty'];
                                            if($row['discount_type']==1)
                                            {
                                                $order_value = $product_value*(1-$row['discount']/100);
                                            }
                                            else{ 
                                                $order_value = $product_value-$discount;
                                                $discount = ($discount/$product_value)*100;
                                            }
                                            $order_value = round($order_value);
                                            ?>
                                            <tr class="product_row">
                                                <td class="text-center"><?php echo $row['segment']; ?></td>
                                                <td class="text-center">
                                                    <?php echo $row['product_name']; ?>
                                                    <input type="hidden" name="product_id[]" value="<?php echo $row['product_id']?>">
                                                </td>
                                                <td class="text-center">
                                                    <input type="hidden" class="unit_price" value="<?php echo $row['unit_price'];?>">
                                                    <span class="unit_price_display"><?php echo ($row['unit_price']); ?></span>
                                                </td> 
                                                <td class="text-center">
                                                <input type="hidden" value="<?php echo $row['qty']; ?>" min="1" class='form-control only-numbers product_qty' name='qty[<?php echo $row['product_id']?>]'/>
                                                <?php echo $row['qty']; ?>
                                                <input type="hidden" name="" class="preference_value" value="<?php echo get_preference('max_discount','dealer_settings');?>">
                                                </td>
                                                <td class="discount_block">
                                                    <select name="discount_type[<?php echo $row['product_id']?>]" class="discount_type form-control" style="width:70px;display:inline">
                                                    <?php
                                                        foreach ($discount_types as $key => $value) {
                                                            $selected = ($key==$row['discount_type'])?'selected':'';
                                                            echo '<option value="'.$key.'" '.$selected.'>In '.$value.'</option>';
                                                        }
                                                    ?>
                                                    </select>
                                                    <input type="number" min="0"  value="<?php echo $row['discount']?>" name="discount[<?php echo $row['product_id']?>]" class="form-control discount" style="width:100px;display:inline"></td>
                                                </td>
                                                <td>
                                                    <input type="hidden" class="discounted_value" value="<?php echo $order_value;?>">
                                                    <span class="discounted_value_display"><?php echo ($order_value);?></span>
                                                </td>
                                            </tr>
                                      <?php $total+=$order_value;
                                        }
                                    } ?>
                                    </tbody>
                                </table>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class ="col-md-offset-8 col-md-1"><b>Total: </b></div>
                                        <span id="total_value_display"><?php echo ($total); ?></span>
                                        <input type="hidden" id="total_value" value="<?php echo $total;?>">
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-5 col-sm-10">
                                <button class="btn btn-primary" type="submit" name="revisePo" value="button"><i class="fa fa-check"></i> Submit</button>
                                <a class="btn btn-danger" href="<?php echo SITE_URL;?>po_list"><i class="fa fa-times"></i> Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>              
        </div>
    </div>
</div>
<?php $this->load->view('commons/main_footer.php', $nestedView); ?>
<style>
    .pagination{
        width: 100%;
    }
</style>