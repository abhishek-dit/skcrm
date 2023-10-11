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
                        <form role="form" class="form-horizontal" method="post" action="<?php echo SITE_URL; ?>quoteApprovalList">
                            <div class="col-sm-12">
                                
                                <label class="col-sm-2 control-label">Quote ID </label>
                                <div class="col-sm-2">
                                    <input type="text" name="quote_id" placeholder="Quote ID" maxlength="20"  value="<?php echo @$searchParams['quote_id']; ?>" id="companyName" class="form-control">
                                </div>
                                <label class="col-sm-2 control-label">Billing TO </label>
                                <div class="col-sm-2">
                                    
                                     <?php
                                    $attrs = '  class="select2 category_id" id="billing_id"  ';
                                    @$billing_name=array(''=>'Select Billing Name')+@$billing_name;
                                    echo form_dropdown("billing_id", @$billing_name, @$searchParams['billing_id'], @$attrs);
                                    ?>
<!--                                    <input type="text" name="billing_id" placeholder="Document Name" maxlength="100"  value="<?php echo @$searchParams['campaignDocumentName']; ?>" id="companyName" class="form-control">-->
                                </div>
                             <div class="col-sm-2">
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
                                    <th class="text-center" width="10%"><strong>Quote ID</strong></th>
                                    <th class="text-center" width='32%'><strong>Opportunity Details</strong></th>
                                    <th class="text-center" width="10%"><strong>Billing TO</strong></th>
                                    <th class="text-center" width="8%"><strong>Discount</strong></th>
                                    <th class="text-center" width="20%"><strong>Created By</strong></th>
                                    <th class="text-center" width="10%"><strong>View</strong></th>
                                    <th class="text-center" width="10%"><strong>Actions</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                //@$inc = $start + 1;
                                if (!empty($quoteSearch)) {

                                    foreach (@$quoteSearch as $row) {
                                        ?>
                                        <tr>
                                            <td class="text-center"><?php 
                                            $lead_id = getLeadFromQuote(@$row['quote_id']);
                                            
                                            echo getQuoteReferenceID1($lead_id, @$row['quote_id']); //@$sn        ?></td>
                                            <td><?php echo @$row['opportunity']; ?></td>
                                            <td class="text-center"><?php echo @$row['billing']; ?></td>
                                            <td class="text-center" align='cneter'><?php echo @$row['discount']; ?></td>
                                            <td class="text-center"><?php echo getUserName(@$row['created_by']); //@$sn        ?></td>
                                            <td class="text-center">
                                            <?php $quote_revision_id = getQuoteReference($row['quote_id'], 2); ?>
                                                <a target="_blank" href="<?php echo SITE_URL; ?>quotation/<?php echo @icrm_encode($quote_revision_id); ?>" style="padding:3px 3px;" title="Quote View"><button type='button' class="btn btn-primary"  style="padding: 3px;" ><i class="fa  fa-building-o"></i></button></a>
                                                <a href="<?php echo SITE_URL; ?>quotationPdf/<?php echo @icrm_encode($quote_revision_id); ?>" style="padding:3px 3px;" title="Quote Download"><button type='button' class="btn btn-primary" style="padding: 3px;" ><i class="fa fa-cloud-download"></i></button></a>
                                            </td>
                                            <td>
                                                <?php if($row['status']== 1 || $row['status'] == 6){

                                                    ?>
                                                <a style="padding:3px 3px;" href="<?php echo SITE_URL; ?>approveQuote/<?php echo @icrm_encode($row['quote_id']); ?>" onclick="return confirm('Are you sure you want to Approve?')"><button type='button' class="btn btn-primary" style="padding: 3px;" ><i class="fa fa fa-thumbs-o-up"></i></button></a>        
                                                <a style="padding:3px 3px;" href="<?php echo SITE_URL; ?>rejectQuote/<?php echo @icrm_encode($row['quote_id']); ?>" onclick="return confirm('Are you sure you want to Reject?')"><button type='button' class="btn btn-danger" style="padding: 3px;" ><i class="fa fa fa-thumbs-o-down"></i></button></a>        
                                                <?php }?>
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


<?php $this->load->view('commons/main_footer.php', $nestedView); ?>
<style>
    .pagination{
        width: 100%;
    }
</style>