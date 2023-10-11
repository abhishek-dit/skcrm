<?php $this->load->view('commons/main_template', $nestedView); ?>
<?php
echo $this->session->flashdata('response');
?>

    <div class="row"> 
        <div class="col-sm-12 col-md-12">
            <div class="block-flat">
                <table class="table table-bordered"></table>
                <div class="content">
                    <form method="POST">
                        <div class="col-sm-12 col-md-12" style="margin-bottom:10px;">
                        <div class="col-md-4 col-md-offset-8">
                            <a href="<?php echo SITE_URL.'product_list'?>" class="btn btn-primary"><i class="fa fa-info"></i> View Upload List</a>
                            <!-- <a href="<?php echo SITE_URL.'download_product_csv'?>" class="btn btn-primary btn-==="><i class="fa fa-cloud-download"></i> Product XLSX</a> -->
                            <button class="btn btn-success" value="downloadProduct" name="downloadProduct" type="submit" formaction="<?php echo SITE_URL.'downloadProduct_Upload'; ?>"><i class="fa fa-cloud-download"></i> Product XLSX</button>
                        </div>
                    </div>
                    </form>
                    <form id="bulkUploadFrm1" action="<?php echo SITE_URL.'insert_product_list_upload';?>" method="post"  enctype="multipart/form-data" class="form-horizontal">
                        <div class="form-group">
                            <div class="col-sm-12 col-md-12">
                                <label class="col-sm-4 control-label">Upload File<span class="req-fld"> *</span></label>
                                <div class="col-sm-4">
                                    <input type="file" class="form-control"  id="uploadXlsx" name="userfile" /> 
                                    <p><small>(allowed xlsx,xls files only)</small></p>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12 col-md-12">
                                <div class="col-sm-offset-4 col-sm-8">
                                    <button class="btn btn-success" value="1" name="submit" type="submit"><i class="fa fa-check"></i> Submit</button>
                                    <a class="btn btn-danger" href="<?php echo SITE_URL;?>"><i class="fa fa-times"></i> Cancel</a>
                                </div>
                            </div>
                        </div>
                    </form>
            </div>              
        </div>
    </div>
<?php $this->load->view('commons/main_footer.php', $nestedView); ?>
