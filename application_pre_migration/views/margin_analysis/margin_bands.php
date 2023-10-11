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
                                                <th><strong>S.No</strong></th>
                                                <th><strong>Level</strong></th>
                                                <th><strong>Variance %</strong></th>
                                                <th><strong>Net Margin %</strong></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $snum=1;
                                        foreach ($marginApproval as $row) {
                                                $gross_margin = $net_margin = array();
                                                $gross_margin['lower_limit'] = ($row['gm_lower_limit']===NULL)?NULL:round($row['gm_lower_limit'],2);
                                                $gross_margin['lower_check'] = $row['gm_lower_check'];
                                                $gross_margin['upper_limit'] = ($row['gm_upper_limit']===NULL)?NULL:round($row['gm_upper_limit'],2);
                                                $gross_margin['upper_check'] = $row['gm_upper_check'];

                                                $net_margin['lower_limit'] = ($row['nm_lower_limit']===NULL)?NULL:round($row['nm_lower_limit'],2);
                                                $net_margin['lower_check'] = $row['nm_lower_check'];
                                                $net_margin['upper_limit'] = ($row['nm_upper_limit']===NULL)?NULL:round($row['nm_upper_limit'],2);
                                                $net_margin['upper_check'] = $row['nm_upper_check'];
                                            ?>
                                            <tr>
                                                <td><?php echo $snum++;?> <input type="hidden" name="quote_approval_ids[]" value="<?php echo $row['quote_approval_id'];?>"></td>
                                                <td><?php echo ($row['role_id']>0)?getRoleShortName($row['role_id']):'Auto approval';?></td>
                                                <td><?php echo displayRangeLable($gross_margin);?></td>
                                                <td><?php echo displayRangeLable($net_margin);?></td>
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
                        <label class="col-md-3">Cost of Maintaining Warranty : <?php echo @get_preference('cost_of_maintaining_warranty','margin_settings')?>%</label>
                        <label class="col-md-3">Cost of Capital : <?php echo @get_preference('cost_of_capital','margin_settings')?>%</label>
                        <label class="col-md-3">Dealer Warranty : <?php echo @(get_preference('enable_warranty','dealer_settings')==1)?'Enabled':'Disabled'?></label>
                        <div class="col-md-3">
                            <a class="btn btn-primary" href="<?php echo SITE_URL.'marginAnalysisConfig';?>"><i class="fa fa-edit"></i> Edit</a>
                        </div>
                    </div>
                </form> 
            </div>
        </div>              
    </div>
</div>

<?php $this->load->view('commons/main_footer.php', $nestedView); ?>