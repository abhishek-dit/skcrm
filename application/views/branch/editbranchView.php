<?php
$this->load->view('commons/main_template', $nestedView);
?>

<?php
if (@$flg != '') {
    //$flg = @$this->global_functions->decode_icrm($_REQUEST['flg']);
    if ($flg == 1) {
        if ($val == 1) {
            $formHeading = 'Edit Branch Details';
        } else {
            $formHeading = 'Add Branch';
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
                        <form class="form-horizontal" role="form" action="<?php echo SITE_URL; ?>branchAdd"  parsley-validate novalidate method="post">
                            <input type="hidden" id="branch_id" name="branch_id" value="<?php echo @icrm_encode($branchEdit[0]['branch_id']); ?>">
                            
                            <div class="form-group">
                                <label for="inputName" class="col-sm-3 control-label">Branch Name <span class="req-fld">*</span></label>
                                <div class="col-sm-6">
                                    <input type="name" required class="form-control" id="branch_check" placeholder="Name" name="name" value="<?php echo @$branchEdit[0]['name']; ?>"  maxlength="5">
                                    <p id="branchNameValidating" class="hidden"><i class="fa fa-spinner fa-spin"></i> Checking...</p>
                                <p id="branchCodeError" class="error hidden"></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">GEO</label>
                                <div class="col-sm-6">
                                    <select class="form-control load_iCheck" name="geo" id="geo">
                                        <option value="">select</option>
                                        <?php
                                            if($geos) {
                                                foreach($geos as $geo) {
                                                    $selected=(@$geo_id == $geo['location_id'])?'selected':'';
                                                    echo '<option value="'.$geo['location_id'].'" '.$selected.'>'.$geo['location'].'</option>';
                                                }
                                            }
                                            ?>
                                    </select>
                                </div>
                            </div>
                            <?php
                            if(@$country_id!='')
                            {
                                ?>
                                <div class="form-group edit-country-group">
                                    <label class="col-sm-3 control-label">Country</label>
                                    <div class="col-sm-6">
                                        <select name="country" class="form-control" id="edit_country">
                                            <option value="">Select</option>
                                            <?php
                                            if($countries) {
                                                foreach($countries as $country) {
                                                    $selected=(@$country_id == $country['location_id'])?'selected':'';
                                                    echo '<option value="'.$country['location_id'].'" '.$selected.'>'.$country['location'].'</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div><?php
                            }
                            ?>
                            <?php
                            if(@$region_id!='')
                            {
                                ?>
                                <div class="form-group edit-region-group">
                                    <label class="col-sm-3 control-label">Region</label>
                                    <div class="col-sm-6">
                                        <select name="region" class="form-control" id="edit_region">
                                            <option value="">Select</option>
                                            <?php
                                            if($regions) {
                                                foreach($regions as $region) {
                                                    $selected=(@$region_id == $region['location_id'])?'selected':'';
                                                    echo '<option value="'.$region['location_id'].'" '.$selected.'>'.$region['location'].'</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div> <?php
                            }
                            ?>
                            
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-10">
                                    <button class="btn btn-primary" type="submit" name="submitbranch" value="button"><i class="fa fa-check"></i> Submit</button>
                                    <a class="btn btn-danger" href="<?php echo SITE_URL; ?>branch"><i class="fa fa-times"></i> Cancel</a>
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

$this->load->view('commons/main_footer.php', $nestedView);
?>