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


                    <div class="table-responsive">
                        <table class="table table-bordered hover">
                            <thead>
                                <tr>
                                    <th class="text-center" width="10%"><strong>Quote ID</strong></th>
                                    <th class="text-center" width='35%'><strong>Opportunity Details</strong></th>
                                    <th class="text-center" width="10%"><strong>Billing</strong></th>
                                    <th class="text-center" width="9%"><strong>Discount</strong></th>
                                    <th class="text-center" width="16%"><strong>Status</strong></th>
                                    <th class="text-center" width="10%"><strong>Revisions</strong></th>
                                    <th class="text-center" width="10%"><strong>Actions</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                //@$inc = $start + 1;
                                if (!empty($quoteSearch)) {

                                    foreach (@$quoteSearch as $row) {
                                        ?>
                                        <?php $quote_revision_id = getQuoteReference($row['quote_id']); ?>
                                        <tr>
                                            <td class="text-center"><?php echo getQuoteReferenceID($lead_id, @$row['quote_id']); //@$sn        ?></td>
                                            <td class="text-center"><?php echo @$row['opportunity'] ?></td>
                                            <td class="text-center"><?php echo @$row['billing']; ?></td>
                                            <td class="text-center" align='cneter'>
                                            <?php 
                                                //echo round(@$row['discount'],2); 
                                                $quote_format_type = quote_format_type(getQuoteRevisionTime($quote_revision_id));
                                                switch ($quote_format_type) {
                                                    case 1: // Old Format
                                                        $discount = round(@$row['discount'],2);
                                                    break;
                                                    case 2: // New Format
                                                        
                                                        $qrow = getQuoteRevisionPrice($quote_revision_id);
                                                        $quote_price = round($qrow['quote_price']);
                                                        $cost = round($qrow['cost']);
                                                        $discount_amt = ($cost-$quote_price);
                                                        $discount = round(($discount_amt/$cost)*100,2);
                                                    break;
                                                }
                                                echo $discount.'%';
                                            ?>
                                            </td>
                                            <td class="text-center" align='cneter'><?php echo getQuoteStatus(@$row['status']); ?></td>
                                            <td>
                                                <button type="button" class="btn btn-primary md-trigger"  style="padding: 3px;"  id="add_quote" data-toggle="modal" data-target="#info<?php echo @$row['quote_id']; ?>" data-id="<?php echo $lead_id; ?>"><i class="fa fa-info"></i></button> 
                                                <?php if($lead_user_id == $this->session->userdata('user_id') && $leadStatusID != 19) { 
                                                 if(@$row['status'] == 1 || @$row['status'] == 2 || @$row['status'] == 10) { ?>
                                                    <!-- <button type="button" class="btn btn-primary md-trigger"  style="padding: 3px;"  id="add_quote" data-toggle="modal" data-target="#add<?php echo @$row['quote_id']; ?>" data-id="<?php echo $lead_id; ?>"><i class="fa fa-plus"></i></button> --> 
                                                    <a class="btn btn-primary" style="padding: 3px; color:#fff" href="<?php echo SITE_URL.'quoteRevision/'.icrm_encode($row['quote_id']);?>"><i class="fa fa-plus"></i></a>
                                                <?php } } ?>    
                                            </td>
                                            <td class="text-center">
                                                
                                                <a target="_blank" href="<?php echo SITE_URL; ?>quotation/<?php echo @icrm_encode($quote_revision_id); ?>" style="padding:3px 3px;" title="Quote View"><button type='button' class="btn btn-primary"  style="padding: 3px;" ><i class="fa  fa-building-o"></i></button></a>
                                                <a href="<?php echo SITE_URL; ?>quotationPdf/<?php echo @icrm_encode($quote_revision_id); ?>" style="padding:3px 3px;" title="Quote Download"><button type='button' class="btn btn-primary" style="padding: 3px;" ><i class="fa fa-cloud-download"></i></button></a>

                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    ?>	<tr><td colspan="7" align="center"><span class="label label-primary">No Records</span></td></tr>
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
                <div class="form-group">
                    <label class="col-sm-3 control-label">Category<span class="req-fld">*</span></label>
                    <div class="col-sm-7">

                        <?php
                        $attrs = ' required class="select2" id="category" style=" width:100%"  ';
                        echo form_dropdown("category", @$productCategories, '', @$attrs);
                        ?>
                    </div>
                </div>

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
                                        <th><strong>Sub Category</strong></th>
                                        <th class="text-center"><strong>Quantity</strong></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    // print_r($opportunities);
                                    if (count($opportunities) > 0) {
                                        foreach ($opportunities as $v) {
                                            ?>

                                            <tr class="opprow" data-cat-id="<?php echo $v['category_id']; ?>" style="display:none">

                                                <td class="text-center"><input type="checkbox" name="op_id[]" value="<?php echo $v['opportunity_id']; ?>" class="op" > </td>
                                                <td><?php echo @$v['product_name']; ?></td>
                                                <td><?php echo @$v['description']; ?></td>
                                                <td>
                                                    <?php
                                                    $sub_category = $this->quote_model->getProductSubCategory($v['product_id']);
                                                    echo form_dropdown('sub_category_id[]', $sub_category, '','class="form-control subcat" required disabled'); ?>

                                                </td>
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
                <div class="form-group">
                    <label class="col-sm-3 control-label">Billing Name<span class="req-fld">*</span></label>
                    <div class="col-sm-7">

                        <?php
                        $attrs = ' required class="select2" id="billing" style=" width:100%"  ';
                        echo form_dropdown("billing_name", @$billing_name, '', @$attrs);
                        ?>
                    </div>
                </div>
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
                <div class="form-group">
                    <label class="col-sm-3 control-label">Warranty<span class="req-fld">*</span></label>
                    <div class="col-sm-7">
                        <select name="warranty" class="select2" style="width:100%">
                            <option value="">Select</option>
                            <?php 
                            for($i=12; $i<=60; $i+=3){
                                echo "<option value=".$i.">".$i." Months"."</option>";
                                }
                            ?> 
                    </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Advance <span class="req-fld">*</span></label>
                    <div class="col-sm-2">
                        <select name="advance_type" id="advance_type" class="form-control">
                            <option value="1">in %</option>
                            <option value="2">in Rs</option>
                        </select>
                    </div>
                    <div class="col-sm-5">
                        <input type="number" class="form-control" placeholder="Advance Collected" name="advance" id="advance_collected" required>
                    </div>
                </div>
                <div class="form-group bal_payment_block">
                    <label class="col-sm-3 control-label">Balance Payment in <span class="req-fld">*</span></label>
                    <div class="col-sm-7">
                        <div class="input-group">
                            <input type="number" min="0" name="balance_payment_days" id="balance_payment_days" class="form-control">
                            <span class="input-group-addon">days</span>
                        </div>
                    </div>
                </div>
                <?php
                if($this->session->userdata('role_id')!=5) // If not Distributor
                {
                ?>
                <div class="form-group" id="dealer_commission_row">
                    <label class="col-sm-3 control-label">Dealer Commission (%) <span class="req-fld">*</span></label>
                    <div class="col-sm-7">
                        <input type="number" class="form-control" min="0" max="100" placeholder="Dealer Commission (%)" name="dealer_commission" id="dealer_commission">
                    </div>
                </div>
                <?php
                }
                ?>
                
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
foreach (@$quoteSearch as $row)
{
    include('modals/quote_add_modal.php');
    include('modals/quote_info_modal.php');
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
