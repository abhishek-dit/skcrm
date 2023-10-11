<?php $this->load->view('commons/main_template', $nestedView);
echo $this->session->flashdata('response'); 
?>
<div class="row ">
	<div class="col-md-12 col-sm-12 ">
		<div class="block-flat">
			<div class ="content">
            <div class="row no-gutter " >
                        <form role="form" class="form-horizontal" method="post" action="<?php echo SITE_URL; ?>otr_list">
                            <div class="col-sm-12">
                                
                                <label class="col-sm-2 control-label">Contract Note ID </label>
                                <div class="col-sm-2">
                                    <input type="text" name="contract_note_id" placeholder="Cnote ID" maxlength="20"  value="<?php echo @$searchParams['contract_note_id']; ?>" id="companyName" class="form-control">
                                </div>
                                <div class="col-sm-2">
                                    <?php $cnote_types = getCNoteTypeList(); ?>
                                    <select name="cnote_type" class="form-control" title="C-Note Type">
                                        <option value="">C-Note Type</option>
                                        <?php
                                        foreach ($cnote_types as $cnote_type => $label) {
                                           $selected = ($cnote_type==@$searchParams['cnote_type'])?'selected':'';
                                           echo '<option value="'.$cnote_type.'" '.$selected.'>'.$label.'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" name="billing_party" placeholder="Customer Name" title="Customer Name" maxlength="60"  value="<?php echo @$searchParams['billing_party']; ?>" id="companyName" class="form-control">
                                </div>
                             <div class="col-sm-2">
                                    <button type="submit" name="search" value="1" class="btn btn-success"><i class="fa fa-search"></i> Search</button>
                             </div>  
                                
                            </div>
                            </div>
                        </form>
                    </div>
            <div class="row"> 
                <form >
                  <div class="table-responsive">
                        <table class="table table-bordered hover">
                            <thead>
                                <tr>
                                    <th class="text-center" width="8%"><strong>C-Note ID</strong></th>
                                    <th class="text-center" width="12%"><strong>C-Note Type</strong></th>
                                    <th class="text-center" width="25%"><strong>Customer Name</strong></th>
                                    <th class="text-center" width="18%"><strong>Sales Engineer</strong></th>
                                    <th class="text-center" width="10%"><strong>C-Note Date</strong></th>
                                    <th class="text-center" width='12%'><strong>Quote Ref ID</strong></th>
                                    <!-- <th class="text-center" width="8%"><strong>Billing</strong></th> -->
                                    <!-- <th class="text-center" width="8%"><strong>Discount</strong></th> -->
                                    <!-- <th class="text-center" width="12%"><strong>PO Number</strong></th>
                                    <th class="text-center" width="8%"><strong>PO Date</strong></th>
                                    <th class="text-center" width="13%"><strong>SO Number</strong></th>--> 
                                    <th class="text-center" width="10%"><strong>Quote Info</strong></th>
                                    <th class="text-center" width="10%"><strong>CNote Info</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                //@$inc = $start + 1;
                                if (!empty($searchResults)) {

                                    foreach (@$searchResults as $row) 
                                    {
                                        $cnote_type = $row['cnote_type'];
                                        if($cnote_type==1)
                                        {
                                            $cNoteQuotes = getCNoteQuotes(@$row['contract_note_id']);
                                            $quotesCount = $cNoteQuotes['count'];
                                            $quotesInfo = $cNoteQuotes['resArr'];
                                            ?>
                                                <?php
                                                $j = 0;
                                                foreach($quotesInfo as $row1)
                                                {
                                                    ?>
                                                    <tr>
                                                        <?php if($j == 0)
                                                        { ?>
                                                        <td rowspan="<?php echo $quotesCount; ?>"  class="text-center"><?php echo @$row['contract_note_id'] ?></td>
                                                        <td rowspan="<?php echo $quotesCount; ?>"  class="text-center" align='cneter'><?php echo getCNoteTypeLable($row['cnote_type']); ?></td>
                                                        <td rowspan="<?php echo $quotesCount; ?>" align="left"><?php echo @$row['customer_name'] ?></td>
                                                        <td rowspan="<?php echo $quotesCount; ?>"  align="left"><?php echo @$row['lead_owner_name'] ?></td>
                                                        <td rowspan="<?php echo $quotesCount; ?>"  align="left"><?php echo format_date(@$row['created_time'],'d M Y'); ?></td>
                                                        <?php } ?>
                                                        <td class="text-center"><?php echo getQuoteReferenceID($row['lead_id'], @$row1['quote_id']); ?></td>
                                                        <!-- <td class="text-center"><?php echo @$row1['billing']; ?></td> -->
                                                        <!-- <td class="text-center" align='center'>
                                                        <?php 
                                                        $quote_format_type = quote_format_type($row1['quote_revision_time']);
                                                        switch ($quote_format_type) {
                                                            case 1: // Old Format
                                                                $discount = round($row1['discount']);
                                                                $quote_price = getQuotePrice($row1['quote_id'], $row1['discount']);
                                                            break;
                                                            case 2: // New Format
                                                                
                                                                $qrow = getQuoteRevisionPrice($row1['quote_revision_id']);
                                                                $quote_price = round($qrow['quote_price']);
                                                                $cost = round($qrow['cost']);
                                                                $discount_amt = ($cost-$quote_price);
                                                                $discount = ($discount_amt/$cost)*100;
                                                            break;
                                                        }
                                                        echo round($discount,2).'%';
                                                        ?>
                                                        </td> -->
                                                       
                                                        <?php if($j == 0)
                                                        { ?>
                                                        <!-- <td rowspan="<?php echo $quotesCount; ?>" class="text-center" align='cneter'><?php echo @$row['po_number']; ?></td>
                                                        <td rowspan="<?php echo $quotesCount; ?>" class="text-center" align='cneter'><?php echo @$row['po_date']; ?></td>
                                                        <td rowspan="<?php echo $quotesCount; ?>" class="text-center" align='cneter'><?php echo @$row['so_number']; ?></td> -->
                                                        
                                                        <?php } ?>
                                                         <td class="text-center" align='center'>
                                                            <!-- <a target="_blank" href="<?php echo SITE_URL; ?>quotation/<?php echo @icrm_encode($row1['quote_revision_id']); ?>" style="padding:3px 3px;" title="Quote View"><button type='button' class="btn btn-primary"  style="padding: 3px;" ><i class="fa  fa-building-o"></i></button></a> -->
                                                            <a href="<?php echo SITE_URL; ?>quotationPdf/<?php echo @icrm_encode($row1['quote_revision_id']); ?>" style="padding:3px 3px;" title="Quote Download"><button type='button' class="btn btn-primary" style="padding: 3px;" ><i class="fa fa-cloud-download"></i></button></a>
                                                        </td>
                                                        <?php if($j == 0)
                                                        { ?>
                                                        <td rowspan="<?php echo $quotesCount; ?>" class="text-center">
                                                           <a href="<?php echo SITE_URL; ?>contractPdf/<?php echo @icrm_encode($row['contract_note_id']); ?>" style="padding:3px 3px;" title="Contract Note Download"><button type='button' class="btn btn-primary" style="padding: 3px;" ><i class="fa fa-cloud-download"></i></button></a>

                                                        </td>
                                                        <?php } ?>
                                                    </tr>
                                                    <?php
                                                $j++;
                                                }
                                                ?>
                                            <?php
                                        }
                                        else
                                        {
                                            ?>
                                            <tr>
                                                <td  class="text-center"><?php echo @$row['contract_note_id'] ?></td>
                                                <td class="text-center" align='cneter'><?php echo getCNoteTypeLable($row['cnote_type']); ?></td>
                                                <td align="left"><?php echo @$row['customer_name'] ?></td>
                                                <td  align="left"><?php echo @$row['lead_owner_name'] ?></td>
                                                <td  align="left"><?php echo format_date(@$row['created_time'],'d M Y'); ?></td>
                                                <td class="text-center"></td>
                                                <!-- <td class="text-center" align='center'></td> -->
                                                <!-- <td class="text-center" align='cneter'><?php echo @$row['po_number']; ?></td>
                                                <td class="text-center" align='cneter'><?php echo @$row['po_date']; ?></td>
                                                <td class="text-center" align='cneter'><?php echo @$row['so_number']; ?></td> -->
                                                <td class="text-center" align='center'></td>
                                                <td class="text-center">
                                                   <a href="<?php echo SITE_URL; ?>contractPdf/<?php echo @icrm_encode($row['contract_note_id']); ?>" style="padding:3px 3px;" title="Contract Note Download"><button type='button' class="btn btn-primary" style="padding: 3px;" ><i class="fa fa-cloud-download"></i></button></a>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                } else {
                                    ?>	<tr><td colspan="8" align="center"><span class="label label-primary">No Records</span></td></tr>
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
                </form>
            </div>
            </div>
        </div>
    </div>
</div>


<?php 
$this->load->view('commons/main_footer.php', $nestedView); ?>
