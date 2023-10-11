<?php
$this->load->view('commons/header2', $nestedView);
echo $this->session->flashdata('response'); 
?>

<div class="row"> 
    <div class="col-sm-12 col-md-12">
        <div class="block-flat">
            <div class="content">
                <form method="post" action="<?php echo SITE_URL.'submitPoApprovalAction'?>" role="form">
                    <input type="hidden" name="approval_id" value="<?php echo $row['approval_id']?>">
                    <input type="hidden" name="purchase_order_id" value="<?php echo $row['purchase_order_id']?>">
                    <input type="hidden" name="distributor_id" value="<?php echo $row['distributor_id']?>">
                    <input type="hidden" name="po_revision_id" value="<?php echo $row['po_revision_id']?>">
                    <input type="hidden" name="product_id" value="<?php echo $row['product_id']?>">
                    <input type="hidden" name="approval_type" value="2">
                    <input type="hidden" name="user_id" value="<?php echo $user_id;?>">
                        <div class="row">
                            <label class="col-md-3">Purchase Order ID</label>
                            <div class="col-md-9"><?php echo $row['purchase_order_id'];?></div>
                        </div>
                        <div class="row">
                            <label class="col-md-3">Product</label>
                            <div class="col-md-9"><?php echo $row['product_details'];?></div>
                        </div>
                        <?php
                        $approval_history = getPoApprovalHistory($row['approval_id']);
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
                                    <button type="submit" value="1" name="action" class="btn btn-primary" onclick="return confirm('Are you sure you want to Approve?');"><i class="fa fa-thumbs-o-up"></i> Approve</button>
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