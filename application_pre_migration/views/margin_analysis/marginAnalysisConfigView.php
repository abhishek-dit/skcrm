<?php
$this->load->view('commons/main_template', $nestedView);
echo $this->session->flashdata('response'); 
?>

<div class="row"> 
    <div class="col-sm-12 col-md-12">
        <div class="block-flat">
            <div class="content">
                <form class="form-horizontal" role="form" action="<?php echo SITE_URL; ?>submitMarginAnalysisConfig"  id="quoteDiscount1"  parsley-validate novalidate method="post">
                    <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered hover">
                                        <thead>
                                            <tr>
                                                <th rowspan="2" style="vertical-align:middle"><strong>S.No</strong></th>
                                                <th rowspan="2" style="vertical-align:middle"><strong>Level</strong></th>
                                                <th colspan="4" style="text-align:center"><strong>Variance % </strong></th>
                                                <th colspan="4" style="text-align:center"><strong>Net Margin % </strong></th>
                                            </tr>
                                            <tr>
                                                <th><strong>Lower Limit</strong></th>
                                                <th><strong>Include Lower Limit</strong></th>
                                                <th><strong>Upper Limit</strong></th>
                                                <th><strong>Include Upper Limit</strong></th>
                                                <th><strong>Lower Limit</strong></th>
                                                <th><strong>Include Lower Limit</strong></th>
                                                <th><strong>Upper Limit</strong></th>
                                                <th><strong>Include Upper Limit</strong></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $snum=1;
                                        foreach ($marginApproval as $row) {
                                            ?>
                                            <tr>
                                                <td><?php echo $snum++;?> <input type="hidden" name="quote_approval_ids[]" value="<?php echo $row['quote_approval_id'];?>"></td>
                                                <td><?php echo ($row['role_id']>0)?getRoleShortName($row['role_id']):'Auto approval';?></td>
                                                <td><input type="text" name="gm_lower_limit[<?php echo $row['quote_approval_id'];?>]" class="form-control" value="<?php if($row['gm_lower_limit']!='') echo round($row['gm_lower_limit'],2)?>" ></td>
                                                <td><input type="checkbox" name="gm_lower_check[<?php echo $row['quote_approval_id'];?>]" class="form-control" <?php if($row['gm_lower_check']==1) echo 'checked';?> value="1"></td>
                                                <td><input type="text" name="gm_upper_limit[<?php echo $row['quote_approval_id'];?>]" class="form-control" value="<?php if($row['gm_upper_limit']!='')  echo round($row['gm_upper_limit'],2)?>" ></td>
                                                <td><input type="checkbox" name="gm_upper_check[<?php echo $row['quote_approval_id'];?>]" class="form-control" <?php if($row['gm_upper_check']==1) echo 'checked';?> value="1"></td>
                                                <td><input type="text" name="nm_lower_limit[<?php echo $row['quote_approval_id'];?>]" class="form-control" value="<?php if($row['nm_lower_limit']!='') echo round($row['nm_lower_limit'],2)?>" ></td>
                                                <td><input type="checkbox" name="nm_lower_check[<?php echo $row['quote_approval_id'];?>]" class="form-control" <?php if($row['nm_lower_check']==1) echo 'checked';?> value="1"></td>
                                                <td><input type="text" name="nm_upper_limit[<?php echo $row['quote_approval_id'];?>]" class="form-control" value="<?php if($row['nm_upper_limit']!='') echo round($row['nm_upper_limit'],2)?>" ></td>
                                                <td><input type="checkbox" name="nm_upper_check[<?php echo $row['quote_approval_id'];?>]" class="form-control" <?php if($row['nm_upper_check']==1) echo 'checked';?> value="1"></td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <br>
                    <div class="form-group">
                        <label class="col-md-3">Cost of Maintaining Warranty % <span class="req-fld">*</span></label>
                        <div class="col-md-1">
                            <input type="text" required class="form-control" name="cost_of_maintaining_warranty" value="<?php echo @get_preference('cost_of_maintaining_warranty','margin_settings')?>">
                        </div>
                        <label class="col-md-2">Cost of Capital % <span class="req-fld">*</span></label>
                        <div class="col-md-1">
                            <input type="text" required class="form-control" name="cost_of_capital" value="<?php echo @get_preference('cost_of_capital','margin_settings')?>">
                        </div>
                        <label class="col-md-2"> Dealer Warranty <input style="margin-left:5px" type="checkbox" <?php echo (@get_preference('enable_warranty','dealer_settings')==1)?'checked':''?> name="enable_warranty" value="1"></label>
                        <div class="col-md-3">
                            <button class="btn btn-primary" type="submit" name="marginAnalysisApp" value="button"><i class="fa fa-check"></i> Submit</button>
                            <a class="btn btn-danger" href="<?php echo SITE_URL.'margin_bands';?>"><i class="fa fa-times"></i> Cancel</a>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-10">
                            
                        </div>
                    </div>
                </form> 
            </div>
        </div>              
    </div>
</div>

<?php $this->load->view('commons/main_footer.php', $nestedView); ?>