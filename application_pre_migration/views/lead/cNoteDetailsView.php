<?php $this->load->view('commons/main_template', $nestedView);
echo $this->session->flashdata('response'); 
?>
<div class="row wizard-row">
	<div class="col-md-12 fuelux">
		<div class="block-wizard">

            <div class="step-content"> 
                
                    <form class="form-horizontal" role="form" action="<?php echo SITE_URL;?>manageContractNotes" method="post">
    					<div class="form-group">
        					<div class="col-sm-4">
        						<input type="text" name="contract_note_id" value="<?php echo @$searchParams['contract_note_id'];?>" id="cNote_id" class="form-control" placeholder="CNOTE ID">
        					</div>
                            <div class="col-sm-1"> 
                                <button type="submit" name="searchCNote"  value="1" class="btn btn-success"><i class="fa fa-search"></i></button>
                            </div>
                            <label class="col-sm-6"><small>NOTE: seperate multiple C Note IDs  with comma(,) (Ex: 25,28,78)</small></label>
    					</div>
					</form>

                    <div class="table-responsive">
                        <table class="table table-bordered hover">
                            <thead>
                                <tr>
                                    <th class="text-center" width="10%"><strong>C Note ID</strong></th>
									<th class="text-center" width="10%">Lead ID </th>
                                    <th class="text-center" width='15%'><strong>Quote Ref ID</strong></th>
									
                                    <th class="text-center" width="10%"><strong>Billing</strong></th>
                                    <th class="text-center" width="10%"><strong>Discount</strong></th>
                                    <th class="text-center" width="10%"><strong>PO Number</strong></th>
                                    <th class="text-center" width="10%"><strong>PO Date</strong></th>
                                    <th class="text-center" width="13%"><strong>SO Number</strong></th>
                                    <th class="text-center" width="12%"><strong>Actions</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                //@$inc = $start + 1;
                                if (!empty($cNoteDetails)) {

                                    foreach (@$cNoteDetails as $row) 
                                    {
                                        $cNoteQuotes = getCNoteQuotes(@$row['contract_note_id']);
										$lead_id = getLeadIDByCNoteID($row['contract_note_id']);
										foreach ($lead_id as $row2){
											$lead_id = $row2['lead_id'];
										}
                                        $quotesCount = $cNoteQuotes['count'];
                                        $quotesInfo = $cNoteQuotes['resArr'];
                                        ?>
                                            <?php
                                            $j = 0;
                                            foreach($quotesInfo as $row1)
                                            {
                                                $quote_format_type = getQuoteFormatTypeByQuoteRevisionID($row1['quote_revision_id']); // mahesh: 5th Jan 2018
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
                                                ?>
                                                <tr>
                                                    <?php if($j == 0)
                                                    { ?>
                                                    <td rowspan="<?php echo $quotesCount; ?>" class="text-center"><?php echo @$row['contract_note_id'] ?></td>
                                                    <?php } ?>
													<td class="text-center"><?php echo $row['lead_id'];?></td>
                                                    <td class="text-center"><?php echo getQuoteReferenceID($lead_id, @$row1['quote_id']);?></td>
													
                                                    <td class="text-center"><?php echo @$row1['billing']; ?></td>
                                                    <td class="text-center" align='center'><?php echo @round($discount,2); ?>%</td>
                                                    <?php if($j == 0)
                                                    { ?>
                                                    <td rowspan="<?php echo $quotesCount; ?>" class="text-center" align='cneter'><?php echo @$row['po_number']; ?></td>
                                                    <td rowspan="<?php echo $quotesCount; ?>" class="text-center" align='cneter'><?php echo @$row['po_date']; ?></td>
                                                    <td rowspan="<?php echo $quotesCount; ?>" class="text-center" align='cneter'><?php echo @$row['so_number']; ?></td>
                                                    <td rowspan="<?php echo $quotesCount; ?>" class="text-center">
                                                        <a href="<?php echo SITE_URL; ?>deleteContractNote/<?php echo @icrm_encode($row['contract_note_id']); ?>" onclick="return confirm('Are you sure you want to delete?')" style="padding:3px 3px;" title="Delete Contract Note"><button type='button' class="btn btn-danger"  style="padding: 3px;" ><i class="fa fa-trash-o"></i></button></a>
                                                        

                                                    </td>
                                                    <?php } ?>
                                                </tr>
                                                <?php
                                            $j++;
                                            }
                                            ?>
                                        <?php
                                    }
                                } else {
                                    ?>	<tr><td colspan="8" align="center"><span class="label label-primary">No Records</span></td></tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
            </div>
			<div class="row">
                	<div class="col-sm-12">
                    	<div class="pull-left col-sm-4"><?php echo @$pagermessage ; ?></div>
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
$this->load->view('commons/main_footer.php', $nestedView); ?>
