<?php $this->load->view('commons/main_template', $nestedView); ?>
<?php
echo $this->session->flashdata('response');
?>

    <div class="row"> 
        <div class="col-sm-12 col-md-12">
            <div class="block-flat">
                <table class="table table-bordered"></table>
                <div class="content">
                <form id="bulkUploadFrm" action="<?php echo SITE_URL.'csvUploadUserProductTargets';?>" method="post"  enctype="multipart/form-data" class="form-horizontal">
                    <input type="hidden" name="encoded_id" value="<?php echo @$encoded_id?>">   
                    <div class="col-sm-12 col-md-12" style="margin-bottom:10px;">
                        <div class="col-md-9"><h3>Product Targets For <?php echo (date('m') < 4)? (date('Y') - 1): (date('Y'));?> Financial Year</h3></div>
                        <div class="col-md-3"><a href="<?php echo SITE_URL.'downloadUserProductTargetsCsv/'.$encoded_id;?>" class="btn btn-primary">Download User Product Targets XLS</a></div>
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
