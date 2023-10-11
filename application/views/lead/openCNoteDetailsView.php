<?php $this->load->view('commons/main_template', $nestedView);
echo $this->session->flashdata('response'); 
$encode_lead_id = @icrm_encode($lead_id);
$leadStatusID = getLeadStatusID($lead_id);
$role_id = $this->session->userdata('role_id');
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
                       <button type="button" class="btn btn-primary btn-flat md-trigger" id="add_cNote" data-toggle="modal" data-target="#cNote"><i class="fa fa-plus"></i> Add Contract Note</button> 
                    <?php } ?>
                        <!-- Nifty Modal -->
                    </div>    


                    <div class="table-responsive">
                        <table class="table table-bordered hover">
                            <thead>
                                <tr>
                                    <th class="text-center" width="12%"><strong>C Note ID</strong></th>
                                    <th class="text-center" width='15%'><strong>Quote Ref ID</strong></th>
                                    <th class="text-center" width="12%"><strong>Billing</strong></th>
                                    <th class="text-center" width="12%"><strong>Discount</strong></th>
                                    <th class="text-center" width="8%"><strong>PO Number</strong></th>
                                    <th class="text-center" width="8%"><strong>PO Date</strong></th>
                                    <th class="text-center" width="10%"><strong>SO Number</strong></th>
                                    <th class="text-center" width="16%"><strong>Stage</strong></th>
                                    <?php
                                    if($role_id!=4) // Not a SE
                                    {
                                    ?>
                                    <th class="text-center" width="6%"><strong>Actions</strong></th>
                                    <?php
                                    }
                                    ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                //@$inc = $start + 1;
                                if (!empty($cNoteDetails)) {

                                    foreach (@$cNoteDetails as $row) 
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
                                                    <td rowspan="<?php echo $quotesCount; ?>" class="text-center"><?php echo @$row['cnote_number'] ?></td>
                                                    <?php } ?>
                                                    <td class="text-center"><?php echo getQuoteReferenceID($lead_id, @$row1['quote_id']); //@$sn        ?></td>
                                                    <td class="text-center"><?php echo @$row1['billing']; ?></td>
                                                    <td class="text-center" align='center'>
                                                        <?php 
                                                        //echo @round($row1['discount']); 
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
                                                        echo round($discount,2).'%';
                                                        ?>
                                                    </td>
                                                    <?php if($j == 0)
                                                    { ?>
                                                    <td rowspan="<?php echo $quotesCount; ?>" class="text-center" align='cneter'><?php echo @$row['po_number']; ?></td>
                                                    <td rowspan="<?php echo $quotesCount; ?>" class="text-center" align='cneter'><?php echo @$row['po_date']; ?></td>
                                                    <td rowspan="<?php echo $quotesCount; ?>" class="text-center" align='cneter'><?php echo @$row['so_number']; ?></td>
                                                    <td rowspan="<?php echo $quotesCount; ?>" class="text-center" align='cneter'><?php echo getCNoteStatus(@$row['status']); ?></td>
                                                    
                                                    <?php
                                                    if($role_id!=4) // Not a SE
                                                    {
                                                    ?>
                                                        <td rowspan="<?php echo $quotesCount; ?>" class="text-center">
                                                            <!--<a target="_blank" href="<?php //echo SITE_URL; ?>contract/<?php //echo @icrm_encode($row['contract_note_id']); ?>" style="padding:3px 3px;" title="Contract Note View"><button type='button' class="btn btn-primary"  style="padding: 3px;" ><i class="fa  fa-building-o"></i></button></a>-->
                                                            <a href="<?php echo SITE_URL; ?>contractPdf/<?php echo @icrm_encode($row['contract_note_id']); ?>" style="padding:3px 3px;" title="Contract Note Download"><button type='button' class="btn btn-primary" style="padding: 3px;" ><i class="fa fa-cloud-download"></i></button></a>

                                                        </td>
                                                    <?php
                                                    }
                                                    ?>

                                                    <?php } ?>
                                                </tr>
                                                <?php
                                            $j++;
                                            }
                                            ?>
                                        <?php
                                    }
                                } else {
                                    ?>	<tr><td colspan="10" align="center"><span class="label label-primary">No Records</span></td></tr>
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
<div class="modal fade colored-header" id="cNote"  role="dialog">
    <div class="modal-dialog">

<!--<div class="md-modal colored-header custom-width md-effect-1" id="md-scale" style="width: 70%;">-->
    <div class="md-content">
        <div class="modal-header">
            <h3>Add Contract Note</h3>
            <button type="button" class="close md-close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
        <form class="form-horizontal" role="form" action="<?php echo SITE_URL; ?>cNoteAdd" id="quotation_frm"    method="post"  >
        <div class="modal-body form">
        	
        	<input type="hidden" value="<?php echo $lead_id; ?>" name="lead_id">	
        	<div class="form-group">
				<label for="inputName" class="col-sm-3 control-label">Quote<span class="req-fld">*</span></label>
				<div class="col-sm-7">
					<select name="quote_id[]" style="width:100%" class="select2 cnote_quote" required multiple id="quotes">
						<!--<option value="">Select Quote</option>-->
						<?php foreach($quoteDetails as $quote) 
						{ ?>
							<option value="<?php echo $quote['quote_revision_id'] ?>"><?php echo getQuoteReferenceID($lead_id, $quote['quote_id'])." : ".$quote['opportunity']; ?></option>
						<?php }
						?>
					</select>
                    <p  class="error hidden cnote_error"></p>
				</div>
                
        	</div>
        	<div class="form-group">
				<label for="inputName" class="col-sm-3 control-label">PO Number<span class="req-fld">*</span></label>
				<div class="col-sm-7">
					<input type="text" name="po_number" required class="form-control" maxlength="20" placeholder="Po Number">
				</div>
        	</div>
        	<div class="form-group">
				<label for="inputName" class="col-sm-3 control-label">PO Date<span class="req-fld">*</span></label>
				<div class="col-sm-7">
					<input type="text" class="form-control datepicker" autocomplete="off" value="" name="po_date" placeholder="YYYY-MM-DD" required>
				</div>
        	</div>
        	<div class="form-group">
				<label for="inputName" class="col-sm-3 control-label">Institution Code<span class="req-fld">*</span></label>
				<div class="col-sm-7">
					<input type="text" required name="institution_code" class="form-control" value="<?php echo getCustomerSAPCode($lead_id); ?>" maxlength="20" placeholder="Institution Code">
				</div>
        	</div>
        	<!-- <div class="form-group">
				<label for="inputName" class="col-sm-3 control-label">Ship to Party<span class="req-fld">*</span></label>
				<div class="col-sm-7">
					<input type="text" name="billing_to_party" required class="form-control" maxlength="20" placeholder="Billing to Party">
				</div>
                <div class="col-sm-2">
									<select class="checkCustomer" required style="width:100%" name="customer">
										<option value="<?php echo $customer['customer_id']; ?>"><?php echo $customer['customer']; ?></option>
									</select>
			                	</div>
        	</div> -->
            <div class="form-group">
						<label for="inputName" class="col-sm-3 control-label">Ship to Party<span class="req-fld"></span></label>
						<div class="col-sm-7">
							<select class="checkCustomers" style="width:100%" name="customer">
								<option value="">Select Customer</option>
							</select>
						</div>
					</div>
            <div class="form-group">
                <label for="inputName" class="col-sm-3 control-label">Delivery Period</label>
                <div class="col-sm-7">
                    <input type="text" autocomplete="off" name="delivery_period" class="form-control" placeholder="Delivery Period">
                </div>
            </div>
            <div class="form-group">
                <label for="inputName" class="col-sm-3 control-label">LD Applicable</label>
                <div class="col-sm-7">
                    <input type="text" autocomplete="off" name="ld_applicable" class="form-control datepicker" placeholder="YYYY-MM-DD">
                </div>
            </div>
            <div class="form-group" hidden="hidden">
                <label for="inputName" class="col-sm-3 control-label">Warranty</label>
                <div class="col-sm-7">
                    <input type="text" autocomplete="off" name="warranty" class="form-control" placeholder="Warranty">
                </div>
            </div>
            <div class="form-group">
                <label for="inputName" class="col-sm-3 control-label">Amendment</label>
                <div class="col-sm-7">
                    <input type="text" autocomplete="off" name="amendment" class="form-control" placeholder="Amendment">
                </div>
            </div>
            <div class="form-group">
                <label for="inputName" class="col-sm-3 control-label">Reason for Amendment</label>
                <div class="col-sm-7">
                    <textarea name="reason_for_amendment" class="form-control"> </textarea>
                </div>
            </div>
            <div class="form-group">
                <label for="inputName" class="col-sm-3 control-label">Other Conditions</label>
                <div class="col-sm-7">
                    <textarea name="other_conditions" class="form-control"> </textarea>
                </div>
            </div>
           <!-- <div class="form-group">
                <label for="inputName" class="col-sm-3 control-label">Ship to party</label>
                <div class="col-sm-7">
                    <textarea name="ship_to_party" class="form-control"> </textarea>
                </div>
            </div>-->
            </div>
        <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-flat md-close" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary submit_cnote" type="submit" name="submitCNote" value="button"><i class="fa fa-check "></i> Submit</button>
        </div>
        </form>

    </div>
</div>
</div>
<div class="md-overlay"></div>

<?php 
$this->load->view('commons/main_footer.php', $nestedView); ?>
<script type="text/javascript">
$(document).on('click','.submit_cnote',function(){
    var quotes = [];
    $('.cnote_quote').each(function(index,ele){
      quotes.push($(this).val());
    });
  var data = 'quotes='+quotes;
        
        $.ajax({
        type:"POST",
        url:SITE_URL+'check_quotes',
        data:data,
        cache:false,
        success:function(html){ 

            if(html==0)
            {   
              //  $("#quotes").select2('val','');
                $('.cnote_error').html(' Please make sure that selected quotes should have same Billing through');
                $(".cnote_error").removeClass("hidden");
                return false;
            }
            else
            {   
                $('.cnote_error').html('');
                $(".cnote_error").addClass("hidden");
                
            }
        }

        });

});
</script>
