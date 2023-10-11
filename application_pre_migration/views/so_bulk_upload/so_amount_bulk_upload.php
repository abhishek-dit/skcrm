<?php $this->load->view('commons/main_template', $nestedView); ?>
<?php
echo $this->session->flashdata('response');
?>

    <div class="row"> 
        <div class="col-sm-12 col-md-12">
            <div class="block-flat">
                <table class="table table-bordered"></table>
                <div class="content">
                <form id="bulkUploadFrm1" action="<?php echo SITE_URL.'insert_outstanding_amount_upload';?>" method="post"  enctype="multipart/form-data" class="form-horizontal">
                     <div class="col-sm-12 col-md-12" style="margin-bottom:10px;">
                        <div class="col-md-9"><h3>Manage SO Outstanding Amount</h3></div>
                        <div class="col-md-3"><a href="<?php echo SITE_URL.'generate_so_outstanding_xl'?>" class="btn btn-primary"><i class="fa fa-cloud-download"></i> SO Outstanding XLSX</a></div>
                    </div>
                
                    <div class="form-group">
                            <label class="col-sm-3 control-label">Upload File<span class="req-fld">*</span>
                                <p><small>(allowed xlsx,xls files only)</small></p>
                            </label>
                            <div class="col-sm-6">
                                <input type="file" class="form-control" id="uploadXlsx" name="userfile" />   
                               <!--  <input type="file" name="uploadCsv" id="uploadCsv" required="" class="form-control"> -->
                            </div>
                    </div>
                    <div class="form-group">
                            <label class="col-sm-3 control-label">Missing Files Reupload :
                            </label>
                            <div class="col-sm-1">
                                <input type="checkbox" name="missing_files" value="1" class="icheck form-control">
                            </div>
                    </div>
                    <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-1">
                                <button class="btn btn-success" value="1" name="submit" type="submit">Submit</button>
                            </div>
                            
                            <?php if($updated_record !='')
                            { ?>
                            <div class="col-sm-offset-3 col-sm-4">
                                <span class="fa fa-clock-o"><i> Last Updated: </i><?php echo $updated_record['created_time'].'('. getUserName(@$updated_record['created_by']).')';?></span>
                            </div>
                            <div class="col-sm-2">
                               <a href="<?php echo SITE_URL.'so_amount_list'?>" class="btn btn-sm btn-primary">View Upload List</a>
                            </div>
                            <?php } ?>
                        </div>
                </form>
            </div>              
        </div>
    </div>
<?php $this->load->view('commons/main_footer.php', $nestedView); ?>
