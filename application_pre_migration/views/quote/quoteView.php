<?php $this->load->view('commons/main_template', $nestedView); ?>
<?php
if (@$flg != '') {
    //$flg = @$this->global_functions->decode_icrm($_REQUEST['flg']);
    if ($flg == 1) {
        $disabledText = '';
        $disabledSelect = '';
        if ($editCheck == 1) {
            $disabledText = 'disabled';
            $disabledSelect = 'disabled = disabled';
        }

        if ($val == 1) {
            $formHeading = 'Edit Quote Details';
        } else {
            $formHeading = 'Genarate Quote';
        }
        ?>
        <div class="row"> 
            <div class="col-sm-12 col-md-12">
                <div class="block-flat">
                    <div class="header">							
                        <h4><?php echo $formHeading; ?></h4>
                    </div>
                    <div class="content">
                        <form class="form-horizontal" role="form" action="<?php echo SITE_URL; ?>quoteAdd"  parsley-validate  method="post">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Opportunity </label>
                                <div class="col-sm-9 multiselectbox">
                                    
                                        <label>
                                            <div class="table-responsive">
                                                <table classass="table table-bordered hover">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center"><strong>S.NO</strong></th>
                                                            <th class="text-center"></th>
                                                            <th class="text-center"><strong>Product ID</strong></th>
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
                                                                    <td class="text-center"><?php echo @$sn++; //@$sn    ?></td>
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

                                                            Opportunities not Found.


                                                        <?php } ?>
                                                </table>
                                                
                                                
                                                </label>
                                            </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Billing Name</label>
                                        <div class="col-sm-6">

                                            <?php
                                            $attrs = ' required class="select2 billing" id="billing"  ' . @$is_disable;
                                            echo form_dropdown("billing_name", @$billing_name, '', @$attrs);
                                            ?>

        <!--                                    <input type="text"  class="form-control" maxlength="100"  id="billing" value=""  name="billing_name"  placeholder="Billing Name" >-->
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

                                            <?php
                                            if ($editCheck == 0) {
                                                $redirect = ($parent == 1) ? 'addQuote' : 'quote';
                                                ?>
                                                <button class="btn btn-primary" type="submit" name="submitQuote" value="button"><i class="fa fa-check"></i> Submit</button>
                                                <a class="btn btn-danger" href="<?php echo SITE_URL . $redirect; ?>"><i class="fa fa-times"></i> Cancel</a>
                                                <?php
                                            }
                                            ?>    
                                        </div>
                                    </div>
                                    </form>
                                </div>
                            </div>				
                    </div>
                </div><br>

                <?php
                // break;
            }
        }
        echo $this->session->flashdata('response');
        ?>
        <?php
        if (@$displayList == 1) {
            ?>

            <div class="row"> 
                <div class="col-sm-12 col-md-12">
                    <div class="block-flat">
                        <table class="table table-bordered"></table>
                        <div class="content">
                            <form role="form" class="form-horizontal" method="post" action="<?php echo SITE_URL; ?>quote">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">                                                                     
                                            <label class="col-sm-2 control-label">Billing Name</label>
                                            <div class="col-sm-2">
                                                <input type="text" name="billingName" placeholder="Billing Name" maxlength="100"  value="<?php echo @$searchParams['billingName']; ?>" id="billingName" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12" align="right">
                                        <div class='form-group'>
                                            <button type="submit" name="searchQuote" value="1" class="btn btn-success"><i class="fa fa-search"></i> Search</button>
                                            <a href="<?php echo SITE_URL; ?>addQuote" class="btn btn-success"><i class="fa fa-plus"></i> Add New</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="header"></div>
                            <div class="table-responsive">
                                <table class="table table-bordered hover">
                                    <thead>
                                        <tr>
                                            <th class="text-center"><strong>S.NO</strong></th>
                                            <th class="text-center"><strong>Billing Name</strong></th>
                                            <th class="text-center"><strong>SO Number</strong></th>
                                            <th class="text-center"><strong>Discount</strong></th>
                                            <th class="text-center"><strong>Actions</strong></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        //@$inc = $start + 1;
                                        if (@$count > 0) {

                                            foreach ($quoteSearch as $row) {
                                                ?>
                                                <tr>
                                                    <td class="text-center"><?php echo @$sn++; //@$sn    ?></td>
                                                    <td class="text-center"><?php echo @$row['name']; ?></td>
                                                    <td class="text-center"><?php echo @$row['SO_number']; ?></td>
                                                    <td class="text-center"><?php echo @$row['discount']; ?></td>
                                                    <td class="text-center">
                                                        <a target="_blank" href="<?php echo SITE_URL; ?>quotation/<?php echo @icrm_encode($row['quote_id']); ?>" style="padding:3px 3px;" class="btn btn-primary" title="Quote View"><i class="fa  fa-building-o"></i></a>
                                                        <a target="_blank" href="<?php echo SITE_URL; ?>quotationPdf/<?php echo @icrm_encode($row['quote_id']); ?>" style="padding:3px 3px;" class="btn btn-primary" title="Quote Download"><i class="fa fa-cloud-download"></i></a>

                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        } else {
                                            ?>	<tr><td colspan="8" align="center"><span class="label label-primary">No Records</span></td></tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="pull-left"><?php echo @$pagermessage; ?></div>
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
                <?php
            }
//print_r($this->session->userdata);
//echo $this->session->userdata('check');
            ?>
            <?php $this->load->view('commons/main_footer.php', $nestedView); ?>
