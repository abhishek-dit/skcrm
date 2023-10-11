<?php
$this->load->view('commons/main_template', $nestedView);
echo $this->session->flashdata('response'); 
?>

<div class="row"> 
    <div class="col-sm-12 col-md-12">
        <div class="block-flat">
            <div class="content">
                <form class="form-horizontal" role="form" action="<?php echo SITE_URL; ?>quoteDiscountApp"  id="quoteDiscount1"  parsley-validate novalidate method="post">
                    <div class="form-group">
                        <label for="inputName" class="col-sm-4 control-label">Country Head ( >= ) <span class="req-fld">*</span></label>
                        <div class="col-sm-2">
                            <input type="text" required class="form-control only-numbers" maxlength="2" name="ch" id="ch" value="<?php echo $quoteApproval[9]['min']; ?>" placeholder="Discount in %">
                        </div>
                    </div> 
                    <div class="form-group">
                        <label for="inputName" class="col-sm-4 control-label">National Sales Manager ( < ) <span class="req-fld">*</span></label>
                        <div class="col-sm-2">
                            <input type="text" required class="form-control only-numbers" maxlength="2" name="nsm" id="nsm" value="<?php echo $quoteApproval[8]['max']; ?>" placeholder="Discount in %">
                        </div>
                    </div> 
                    <div class="form-group">
                        <label for="inputName" class="col-sm-4 control-label">Regional Branch Manager ( < ) <span class="req-fld">*</span></label>
                        <div class="col-sm-2">
                            <input type="text" required class="form-control only-numbers" maxlength="2" name="rbh" id="rbh" value="<?php echo $quoteApproval[7]['max']; ?>" placeholder="Discount in %">
                        </div>
                    </div> 
                    <br>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-10">
                            <button class="btn btn-primary" type="submit" name="quoteDiscountApp" value="button"><i class="fa fa-check"></i> Submit</button>
                            <a class="btn btn-danger" href="<?php echo SITE_URL;?>quoteDiscount"><i class="fa fa-times"></i> Cancel</a>
                        </div>
                    </div>
                </form> 
            </div>
        </div>              
    </div>
</div>

<?php $this->load->view('commons/main_footer.php', $nestedView); ?>