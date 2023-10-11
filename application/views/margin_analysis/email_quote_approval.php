<?php
$this->load->view('commons/header2', $nestedView);
echo $this->session->flashdata('response'); 
?>

<div class="row"> 
    <div class="col-sm-12 col-md-12">
        <div class="block-flat">
            <div class="content">
                <form method="post" id="form_submit" action="<?php echo SITE_URL.'submitQuoteApprovalAction'?>" role="form">
                    <input type="hidden" name="margin_approval_id" value="<?php echo $row['margin_approval_id']?>">
                    <input type="hidden" name="quote_id" value="<?php echo $row['quote_id']?>">
                    <input type="hidden" name="quote_revision_id" value="<?php echo $row['quote_revision_id']?>">
                    <input type="hidden" name="lead_id" value="<?php echo $row['lead_id']?>">
                    <input type="hidden" name="lead_owner" value="<?php echo $row['lead_owner']?>">
                    <input type="hidden" name="opportunity_id" value="<?php echo $row['opportunity_id']?>">
                    <input type="hidden" name="approval_type" value="2">
                    <input type="hidden" name="user_id" value="<?php echo $user_id;?>">
                        <div class="row">
                            <label class="col-md-3">Quote ID</label>
                            <div class="col-md-9"><?php 
                            $revision_id = getQuoteRevisionNumber($row['quote_id'],$row['quote_revision_id']);
                            echo getQuoteReferenceID1($row['lead_id'],$row['quote_id']).'-Rev-'.$revision_id;?></div>
                        </div>
                        <div class="row">
                            <label class="col-md-3">Lead ID</label>
                            <div class="col-md-9"><?php echo $row['lead_number'];?></div>
                        </div>
                        <div class="row">
                            <label class="col-md-3">Lead Name</label>
                            <div class="col-md-9"><?php echo $row['customer_name'];?></div>
                        </div>
                        <?php if($conditionApproval[0]['condition'] == 0){ ?>
                        <div class="row">
                            <label class="col-md-3">Opportunity</label>
                            <div class="col-md-9"><?php echo $row['opp_number'].' '.$row['opportunity_details']; //echo $row['opportunity_details'];?></div>
                        </div>
                        <?php } else{ ?>

                        <div class="row"> <span style="font-size:18px">Opportunity Details</span></div>
                        <div class="row">
                            <!-- <label class="col-md-3">Opportunity</label>
                        <div class="col-md-9"><?php echo $row['opportunity'];?></div> -->
                        <table id="table1" style="width:100%" class="kstyle" cellspacing="0" border="1">
                        <thead>
                            <tr>
                                <th class="text-center">Opportunity ID</th>
                                <th class="text-center">Product</th>
                                <th class="text-center">Qty</th>
                                <th class="text-center">Unit MRP</th>
                                <th class="text-center">Discount %</th>
                                <th class="text-center">Warranty</th>
                                <th class="text-center">ORC %</th>
                                <th class="text-center">Quoted Price</th>
                                <th class="text-center">DP</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach ($ma_rows as $orow) {
                                $order_value = $orow['mrp'];
                                $discount = $orow['discount'];
                                if($orow['discount_type']!=''&&$discount!='')
                                $order_value = ($orow['discount_type']==1)?($order_value*(1-$orow['discount']/100)):($order_value-$discount);
                                $discount_percentage = round((($orow['mrp'] - $order_value )/$orow['mrp'])*100,2);

                            ?>
                            <tr>
                                <td><?php echo $orow['opp_number'];?></td>
                                <td><?php echo $orow['product_name'];?></td>
                                <td><?php echo $orow['required_quantity'];?></td>
                                <td><?php echo indian_format_price(round($orow['mrp']/$orow['required_quantity'])).' '.$orow['currency_code'];?></td>
                                <td><?php echo $discount_percentage;?></td>
                                <td><?php echo $orow['warranty'];?></td>
                                <td><?php echo $orow['orc'];?></td>
                                <td><?php echo indian_format_price(round($order_value/$orow['required_quantity'])).' '.$orow['currency_code'];?></td>
                                <td><?php echo indian_format_price(round($orow['dp']/$orow['required_quantity'])).' '.$orow['currency_code'];?></td>
                            </tr>
                            <?php
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            <?php } ?>



                        <?php
                        $approval_history = getMarginAnalysisApprovalHistory($row['margin_approval_id']);
                        if(count($approval_history)>0)
                        {
                        ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered hover">
                                        <thead>
                                            <tr>
                                                <th>S.No</th>
                                                <th>Level</th>
                                                <th>Remarks / Business Case</th>
                                                <th>On Date</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $snum=1;
                                        foreach ($approval_history as $ah_row) {
                                            ?>
                                            <tr>
                                                <td><?php echo $snum++;?></td>
                                                <td><?php echo getRoleShortName($ah_row['approved_by']).'('.$ah_row['user'].')';?></td>
                                                <td><?php echo $ah_row['remarks'];?></td>
                                                <td><?php echo format_date($ah_row['created_time'],'d-m-Y h:i A');?></td>
                                                <td><?php echo ($ah_row['status']==1)?'Approved':'Rejected'?></td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?php
                        }
                        if($user['role_id']==$row['approval_at'])
                        {
                        ?>
                        <div class="row" style="margin-top:10px;">
                            <label class="col-md-3">Remarks / Business Case <span class="req-fld">*</span></label>
                            <div class="col-md-9">
                                <textarea name="remarks" class="remarks form-control" required></textarea>
                            </div>
                        </div>
                        <div class="row" style="margin-top:20px">
                            <div class="col-md-4 col-md-offset-3">
                                <?php
                                if($type==1)
                                {
                                ?>
                                    <button type="submit" value="1" id="approve_submit" name="action" class="btn btn-primary" onclick="return confirm('Are you sure you want to Approve?');"><i class="fa fa-thumbs-o-up"></i> Approve</button>
                                    <!-- <button type="submit" value="1" id="approve_submit" name="action" class="btn btn-primary" onclick="this.style.visibility='hidden'; return confirm('Are you sure you want to Approve?');"><i class="fa fa-thumbs-o-up"></i> Approve</button> -->
                                <?php
                                }
                                else
                                {
                                    ?>
                                <button type="submit" value="2" name="action" class="btn btn-danger" onclick="return confirm('Are you sure you want to Reject?');"><i class="fa fa-thumbs-o-down"></i> Reject</button>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>    
                        <?php
                        }
                         ?>
                    </div>
                    
                </form>
            </div>
        </div>              
    </div>
</div>
<?php $this->load->view('commons/footer2.php', $nestedView); ?>

<script>
//$('#approve_submit').on('click', function () {   
// var myForm = $("#form_submit");   
// if (myForm) {   
//$(this).prop('disabled', true);   
// $(myForm).submit();   
// return confirm('Are you sure you want to Approve?');
//}   
//});   
</script>