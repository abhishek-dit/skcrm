<?php
$this->load->view('commons/main_template', $nestedView);
?>

<?php
if (@$flg != '') {
    //$flg = @$this->global_functions->decode_icrm($_REQUEST['flg']);
    if ($flg == 1) {
        if ($val == 1) {
            $formHeading = 'Edit Channel Partner Details';
        } else {
            $formHeading = 'Add Channel Partner';
        }
        ?>
        <div class="row"> 
            <div class="col-sm-12 col-md-12">
                <div class="block-flat">
                    <div class="header">							
                        <h4><?php echo $formHeading; ?></h4>
                    </div>
                    <div class="content">
                    <?php echo validation_errors(); ?>
                        <form class="form-horizontal" role="form" action="<?php echo SITE_URL; ?>channel_partnerAdd"  parsley-validate novalidate method="post">
                            <input type="hidden"  name="channel_partner_id" value="<?php echo @icrm_encode($channel_partnerEdit['channel_partner_id']); ?>">
                            <input type="hidden" id="channel_partner_id"  value="<?php echo @$channel_partnerEdit['channel_partner_id']; ?>">
                            <div class="form-group">
                                <label for="inputName" class="col-sm-3 control-label">Channel Partner Name <span class="req-fld">*</span></label>
                                <div class="col-sm-6">
                                    <input type="name" required class="form-control" id="channel_partner_check" placeholder="Name" name="name" value="<?php echo @$channel_partnerEdit['name']; ?>"  maxlength="100">
                                    <p id="channel_partnerNameValidating" class="hidden"><i class="fa fa-spinner fa-spin"></i> Checking...</p>
                                <p id="channel_partnerCodeError" class="error hidden"></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Bank <span class="req-fld">*</span></label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="bank_name" placeholder="Bank Name" required value="<?php echo @$channel_partnerEdit['bank_name'];?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Account Number <span class="req-fld">*</span></label>
                                <div class="col-sm-6">
                                    <input type="number" class="form-control" onkeypress="return blockSpecialChar(event)" required name="ac_no" placeholder="Bank Account Number" value="<?php echo @$channel_partnerEdit['account_number'];?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">IFSC <span class="req-fld">*</span></label>
                                <div class="col-sm-6">
                                    <input type="text" required onkeypress="return blockSpecialChar(event)" class="form-control" name="ifsc" placeholder="IFSC Code" value="<?php echo @$channel_partnerEdit['ifsc_code'];?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Account Type <span class="req-fld">*</span></label>
                                <div class="col-sm-6">
                                    <input type="text" required class="form-control" name="ac_type" placeholder="Account Type" value="<?php echo @$channel_partnerEdit['account_type'];?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">City <span class="req-fld">*</span></label>
                                <div class="col-sm-6">
                                    <input type="text" required class="form-control" name="city" placeholder="City" value="<?php echo @$channel_partnerEdit['city'];?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Bank Address <span class="req-fld">*</span></label>
                                <div class="col-sm-6">
                                    <textarea class="form-control" required maxlength="250" name="bank_address"><?php echo @$channel_partnerEdit['bank_address'];?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Beneficiary Name <span class="req-fld">*</span></label>
                                <div class="col-sm-6">
                                    <input type="text" required class="form-control" name="beneficiary_name" placeholder="Beneficiary Name" value="<?php echo @$channel_partnerEdit['benificiary_name'];?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Beneficiary Address <span class="req-fld">*</span></label>
                                <div class="col-sm-6">
                                    <textarea class="form-control" required maxlength="250" name="beneficiary_address"><?php echo @$channel_partnerEdit['benificiary_address'];?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Communication Address <span class="req-fld">*</span></label>
                                <div class="col-sm-6">
                                    <textarea class="form-control" required maxlength="250" name="communication_address"><?php echo @$channel_partnerEdit['communication_address'];?></textarea>
                                </div>
                            </div>

                            
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-10">
                                    <button class="btn btn-primary" type="submit" name="submitchannel_partner" value="button"><i class="fa fa-check"></i> Submit</button>
                                    <a class="btn btn-danger" href="<?php echo SITE_URL; ?>channel_partner"><i class="fa fa-times"></i> Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>				
            </div>
        </div><br>

        <?php
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
                    <div class="row">
                        <form role="form" class="form-horizontal" method="post" action="<?php echo SITE_URL; ?>channel_partner">
                            <div class="col-sm-12">

                                <label class="col-sm-2 control-label">Channel Partner</label>
                                <div class="col-sm-2">
                                    <input type="text" name="channelname" value="<?php echo @$searchParams['channelname']; ?>"  class="form-control" placeholder="Name"  maxlength="100">
                                </div> 
                                <div class="col-sm-4">
                                    <button type="submit" title="Search" name="searchchannel" value="1" class="btn btn-success"><i class="fa fa-search"></i> </button>
                                    <button type="submit" name="downloadchannel_partner" value="1" title="Download" formaction="<?php echo SITE_URL; ?>downloadchannel_partner" class="btn btn-success"><i class="fa fa-cloud-download"></i> </button>
                                    <a href="<?php echo SITE_URL; ?>addchannel_partner" title="Add New" class="btn btn-success"><i class="fa fa-plus"></i> </a>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="header"></div>
                    <div class="table-responsive">
                        <table class="table table-bordered hover">
                            <thead>
                                <tr>
                                    <th class="text-center"><strong>S.NO</strong></th>

                                    <th class="text-center"><strong>Name</strong></th>
                                    <th class="text-center"><strong>Beneficiary</strong></th>
                                    <th class="text-center"><strong>City</strong></th>
                                    <th class="text-center"><strong>Actions</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            if (@$total_rows > 0) {
                                foreach ($channels as $row) {
                                    ?>
                                        <tr>
                                            <td class="text-center"><?php echo @$sn;$sn++; ?></td>
                                            <td class="text-center"><?php echo @$row['name']; ?></td>
                                            <td class="text-center"><?php echo @$row['benificiary_name']; ?></td>
                                            <td class="text-center"><?php echo @$row['city']; ?></td>
                                            <td class="text-center">
                                                <a class="btn btn-default" style="padding:3px 3px;" href="<?php echo SITE_URL; ?>editchannel_partner/<?php echo @icrm_encode($row['channel_partner_id']); ?>"><i class="fa fa-pencil"></i></a> 
                                        <?php
                                        if (@$row['status'] == 1) {
                                            ?>
                                                    <a class="btn btn-danger" title="De Activate" style="padding:3px 3px;" href="<?php echo SITE_URL; ?>deletechannel_partner/<?php echo @icrm_encode($row['channel_partner_id']); ?>" onclick="return confirm('Are you sure you want to Delete?')"><i class="fa fa-trash-o"></i></a>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <a class="btn btn-info" title="Activate" style="padding:3px 3px;" href="<?php echo SITE_URL; ?>activatechannel_partner/<?php echo @icrm_encode($row['channel_partner_id']); ?>"  onclick="return confirm('Are you sure you want to Activate?')"><i class="fa fa-check"></i></a>
                                                    <?php
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                            <?php
                                            }
                                        } else {
                                            ?>	<tr><td colspan="6" align="center"><span class="label label-primary">No Records</span></td></tr>
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
    </div>

    <?php
}
$this->load->view('commons/main_footer.php', $nestedView);
?>

<script type="text/javascript">
function blockSpecialChar(e){
    var k;
    document.all ? k = e.keyCode : k = e.which;
    return ((k > 64 && k < 91) || (k > 96 && k < 123) || k == 8 || k == 32 || (k >= 48 && k <= 57));
    }
</script>
