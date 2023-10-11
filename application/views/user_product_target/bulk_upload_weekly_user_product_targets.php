<?php $this->load->view('commons/main_template', $nestedView); ?>
<?php
echo $this->session->flashdata('response');
?>

    <div class="row"> 
        <div class="col-sm-12 col-md-12">
            <div class="block-flat">
                <table class="table table-bordered"></table>
                <div class="content">
                <form id="bulkUploadFrms" action="<?php echo SITE_URL.'download_weekly_user_product_target_csv';?>" method="post"  enctype="multipart/form-data" class="form-horizontal">
                    <input type="hidden" name="encoded_id" value="<?php echo @$encoded_id?>">   
                    <div class="col-sm-12 col-md-12" style="margin-bottom:10px;">
                        <div class="col-md-9"><h3>Product Targets For Financial Year</h3></div>
                        <div class="col-md-3">
                        <button class="btn btn-success" value="1" name="submitTargetss" type="submit">Download User Product Targets XLS</button></div>
                        <input type ="hidden" name="fy_id" class="fy_id" value="<?php echo $fy_id; ?>">
                    </div>
                </form>
                 <form id="bulkUploadFrm" action="<?php echo SITE_URL.'csv_upload_weekly_user_product_targets';?>" method="post"  enctype="multipart/form-data" class="form-horizontal">
                    <input type="hidden" name="encoded_id" value="<?php echo @$encoded_id?>">
                    <div class="form-group">
                        <label for="inputStartDate" class="col-sm-3 control-label">Select Year <span class="req-fld">*</span></label>
                        <div class="col-sm-6">
                            <select required class="form-control" id="fy_year" placeholder="Financial Year" name="fy_year" >
                            <?php  
                            foreach($fy_years as $fy)
                            {  
                                $selected = '';
                                if($fy['fy_id']==$fy_id) $selected='selected';
                                echo '<option value="'.$fy['fy_id'].'"'.$selected.'>'.$fy['name'].'</option>';

                            } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                            <label class="col-sm-3 control-label">Upload File<span class="req-fld">*</span>
                                <p><small>(allowed csv files only)</small></p>
                            </label>
                            <div class="col-sm-6">
                                <input type="file" name="uploadCsv" id="uploadCsv" required="" class="form-control">
                            </div>
                    </div>
                    <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button class="btn btn-success" value="1" name="submitTargets" type="submit">Submit</button>
                            </div>
                        </div>
                </form>
            </div>				
        </div>
    </div>
<?php $this->load->view('commons/main_footer.php', $nestedView); ?>

