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
                        <form role="form" class="form-horizontal" method="post" action="<?php echo SITE_URL; ?>track_quotes">
                            <div class="col-sm-12">
                                
                                <label class="col-sm-1 control-label">Quote ID </label>
                                <div class="col-sm-2">
                                    <input type="text" name="quote_id" placeholder="Quote ID" maxlength="20"  value="<?php echo @$searchParams['quote_id']; ?>" id="companyName" class="form-control">
                                </div>
                                <label class="col-sm-1 control-label">Customer</label>
                                <div class="col-sm-2">
                                    <input type="text" name="customer_name" placeholder="Customer Name" maxlength="20"  value="<?php echo @$searchParams['customer_name']; ?>" id="companyName" class="form-control">
                                </div>
                                <label class="col-sm-1 control-label">Opportunity </label>
                                <div class="col-sm-2">
                                    <input type="text" name="opportunity_details" placeholder="Opportunity Details" maxlength="60"  value="<?php echo @$searchParams['opportunity_details']; ?>" id="companyName" class="form-control">
                                </div>
                             <div class="col-sm-2">
                                    <button type="submit" name="searchApprveQuote" value="1" class="btn btn-success"><i class="fa fa-search"></i></button>
                                    <a href="<?php echo SITE_URL.'track_quotes'?>" class="btn btn-success"><i class="fa fa-refresh"></i></a>
                             </div>  
                                
                            </div>
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered hover">
                            <thead>
                                <tr>
                                    <th class="text-center" width="5%"><strong>S.No</strong></th>
                                    <th class="text-center" width="8%"><strong>Quote ID</strong></th>
                                    <th class="text-center" width='20%'><strong>Customer</strong></th>
                                    <th class="text-center" width='30%'><strong>Opportunity Details</strong></th>
                                    <th class="text-center" width="6%"><strong>Discount</strong></th>
                                    <th class="text-center" width="7%"><strong>Current Stage</strong></th>
                                    <th class="text-center" width="10%"><strong>Status</strong></th>
                                    <th class="text-center" width="7%"><strong>Final Approver</strong></th>
                                    <!-- <th class="text-center" width="5%"><strong>Revisions</strong></th>
                                    <th class="text-center" width="10%"><strong>Actions</strong></th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                //@$inc = $start + 1;
                                if (!empty($quoteSearch)) {

                                    foreach (@$quoteSearch as $quote_revision_id => $opportunities) {
                                        $j=0;
                                        foreach ($opportunities as $row) {
                                            $quote_format_type = getQuoteFormatTypeByQuoteRevisionID($quote_revision_id); // mahesh: 5th Jan 2018
                                            $lead_id = $row['lead_id'];
                                            $lead_user_id = $row['lead_user_id'];
                                            $leadStatusID = $row['leadStatusID'];
                                           ?>
                                        <tr>
                                            <?php if($j==0) {?>
                                            <td class="text-center" rowspan="<?php echo count($opportunities);?>"><?php echo @$sn++; ?></td>
                                            <td class="text-center" rowspan="<?php echo count($opportunities);?>"><?php echo $row['quote_id'].' Rev-'.getQuoteRevisionNumber($row['quote_id'],$row['quote_revision_id']); ?></td>
                                            <td class="text-center" rowspan="<?php echo count($opportunities);?>"><?php echo $row['customer_name']; ?></td>
                                            <?php } ?>
                                            <td class="text-center"><?php echo @$row['opportunity'] ?></td>
                                            <?php 
                                                switch ($quote_format_type) {
                                                    case 1: // Old Format
                                                        $discount = round(@$row['discount'],2);
                                                        if($j==0) {?>
                                                        <td class="text-center" rowspan="<?php echo count($opportunities);?>"><?php echo $discount.'%';?></td>
                                                        <?php }
                                                    break;
                                                    case 2: // New Format
                                                       
                                                        $cost = round($row['mrp']);
                                                        $discount = ($row['opp_discount_type']==1)?$row['opp_discount']:round(($row['opp_discount']/$cost)*100,2);
                                                        $discount = round($discount,2);
                                                        ?>
                                                        <td class="text-center"><?php echo $discount.'%';?></td>
                                                        <?php
                                                    break;
                                                }
                                            ?>
                                            <td class="text-center"><?php echo getRoleShortName(@$row['approval_at']); ?></td>
                                            <?php 
                                                $status_format = ($quote_format_type==1||$row['quote_revision_status']==1)?1:2;
                                                switch ($status_format) {
                                                    case 1: // Quote Status
                                                        if($j==0) {
                                                            $revision_status_label = ($row['quote_revision_status']==3)?'Waiting for approval':getQuoteStatus(@$row['status']);
                                                            ?>
                                                        <td class="text-center" rowspan="<?php echo count($opportunities);?>" align='cneter'><?php echo $revision_status_label; ?></td>
                                                        <?php }
                                                    break;
                                                    case 2: // Individual opp status
                                                        switch($row['quote_revision_status'])
                                                        {
                                                           /* case 2: // Rejected
                                                                if($j==0) {?>
                                                                <td class="text-center" rowspan="<?php echo count($opportunities);?>" align='cneter'><?php echo 'Rejected'; ?></td>
                                                                <?php }
                                                            break;*/
                                                            case 2 : case 3: // Rejected, Waiting for approval
                                                                ?>
                                                                <td class="text-center" align='cneter'><?php echo getQuoteApprovalStatusLabel(@$row['approval_status']); ?></td>
                                                                <?php
                                                            break;
                                                            case 4: // Previously Approved
                                                                if($j==0) {?>
                                                                <td class="text-center" rowspan="<?php echo count($opportunities);?>" align='cneter'><?php echo 'Previous Quote'; ?></td>
                                                                <?php }
                                                            break;
                                                        }
                                                        ?>
                                                        
                                                        <?php
                                                    break;
                                                }
                                            ?>
                                            <td class="text-center"><?php echo getRoleShortName(@$row['close_at']); ?></td>
                                            <?php /*if($j==0) {?>
                                            <td rowspan="<?php echo count($opportunities);?>">
                                                <button type="button" class="btn btn-primary md-trigger"  style="padding: 3px;"  id="add_quote" data-toggle="modal" data-target="#info<?php echo @$row['quote_id']; ?>" data-id="<?php echo $lead_id; ?>"><i class="fa fa-info"></i></button> 
                                                
                                            </td>
                                            <td class="text-center" rowspan="<?php echo count($opportunities);?>">
                                                
                                                <a target="_blank" href="<?php echo SITE_URL; ?>quotation/<?php echo @icrm_encode($quote_revision_id); ?>" style="padding:3px 3px;" title="Quote View"><button type='button' class="btn btn-primary"  style="padding: 3px;" ><i class="fa  fa-building-o"></i></button></a>
                                                <a href="<?php echo SITE_URL; ?>quotationPdf/<?php echo @icrm_encode($quote_revision_id); ?>" style="padding:3px 3px;" title="Quote Download"><button type='button' class="btn btn-primary" style="padding: 3px;" ><i class="fa fa-cloud-download"></i></button></a>

                                            </td>
                                            <?php } */?>
                                        </tr>
                                        <?php
                                            $j++;
                                        }
                                    }
                                } else {
                                    ?>  <tr><td colspan="8" align="center"><span class="label label-primary">No Records</span></td></tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
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
foreach (@$quoteSearch as $quote_revision_id => $opportunities) {
     foreach ($opportunities as $row) {
        include('modals/quote_info_modal.php');
    }
}
?>
<?php $this->load->view('commons/main_footer.php', $nestedView); ?>
<style>
    .pagination{
        width: 100%;
    }
</style>