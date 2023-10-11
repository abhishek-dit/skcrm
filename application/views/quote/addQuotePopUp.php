
<form class="form-horizontal" role="form" action="<?php echo SITE_URL; ?>quoteAdd"  parsley-validate  method="post">
    <div class="form-group">
        <label class="col-sm-3 control-label">Opportunity </label>
        <div class="col-sm-6">

            <label>
                <div class="table-responsive ">
                    <table class="table no-border hover">
                        <thead>
                            <tr>

                                <th class="text-center"></th>
                                <th class="text-center"><strong>ID</strong></th>
                                <th class="text-center"><strong>Product Name</strong></th>
                                <th class="text-center"><strong>Description</strong></th>
                                <th class="text-center"><strong>Quantity</strong></th>
                            </tr>
                            <?php
                            // print_r($opportunities);
                            if (count($opportunities) > 0) {
                                foreach ($opportunities as $v) {
                                    ?>

                                    <tr>

                                        <td class="text-center"><input type="checkbox" name="op_id[]" value="<?php echo $v['opportunity_id']; ?>" class="icheck1"> </td>
                                        <td class="text-center"><?php echo @$v['product_id']; ?></td>
                                        <td class="text-center"><?php echo @$v['product_name']; ?></td>
                                        <td class="text-center"><?php echo @$v['description']; ?></td>
                                        <td class="text-center"> <?php echo @$v['required_quantity']; ?></td>
                                    </tr>


                                <?php } ?>
                            </thead>
                            <tbody>

                            <?php } else { ?>
                                <tr><td colspan="6" align="center">Opportunities not Found.</td></tr>



                            <?php } ?>
                    </table>


            </label>
        </div>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label">Billing Name</label>
    <div class="col-sm-6">

        <?php
        $attrs = ' required class="select2" id="billing" style=" width:100%"  ' . @$is_disable;
        echo form_dropdown("billing_name", @$billing_name, '', @$attrs);
        ?>
    </div>
</div>
<div class="form-group" style="display:none;" id='stokist_div' >
    <label class="col-sm-3 control-label">Stokist</label>
    <div class="col-sm-6" id='stokist'>
        <select style=" width:100%" id="stokist_id" class="select2" required="" name="stokist_id">

        </select>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-3 control-label">Discount</label>
    <div class="col-sm-6">
        <input type="text"  class="form-control" maxlength="100"  id="name1" value=""  name="discount" parsley-type="Number" required  placeholder="Discount" >
    </div>
</div>

<div class="form-group">
    <div class="col-sm-offset-3 col-sm-10">


        <button class="btn btn-primary" type="submit" name="submitQuote" value="button"><i class="fa fa-check"></i> Submit</button>


    </div>
</div>
</form>

<style>
    .table.no-border tr td, .table.no-border tr th {
        border-width: 0;
    }
</style>



