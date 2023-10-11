<?php $this->load->view('commons/main_template', $nestedView); ?>
<?php
echo $this->session->flashdata('response');
?>

    <div class="row"> 
        <div class="col-sm-12 col-md-12">
            <div class="block-flat">
                <table class="table table-bordered"></table>
                <div class="content vscroll">
                <form action="<?php echo SITE_URL.'updateProductTargets';?>" method="post"  enctype="multipart/form-data" class="form-horizontal">
                    <input type="hidden" name="encoded_id" value="<?php echo @$encoded_id?>">   
                    <div class="col-sm-12 col-md-12" style="margin-bottom:10px;">
                        <div class="col-md-9"><h3>Product Targets For <?php echo (date('m') < 4)? (date('Y') - 1): (date('Y'));?> Financial Year</h3></div>
                        <div class="col-md-3"><button class="btn btn-success" value="1" name="submitTargets" type="submit">Submit</button></div>
                    </div>
                
                    <div class="table-responsive">
                        <table class="table table-bordered hover">
                            <thead>
                                <tr>
                                    <th class="text-center"><strong>S.NO</strong></th>
                                    <th class="text-center"><strong>Product</strong></th>
                                    <?php
                                    foreach ($months as $month) {
                                        echo '<th class="text-center"><strong>'.substr($month['month'],0, 3).'</strong><p><input class=" month_apply_all_cb" value="'.$month['month_id'].'" type="checkbox">
                                        <input type="hidden" name="year['.$month['month_id'].']" value="'.$month['year'].'"></p></th>';
                                    }
                                    ?>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $sn=1;
                            if (count(@$user_products) > 0) {
                                foreach ($user_products as $product) {
                                    ?>
                                        <tr>
                                            <td class="text-center"><?php echo @$sn++; ?></td>
                                            <td class="text-center" style="min-width:250px;">
                                            <p><?php echo @$product['name']; ?><input class="product_apply_all_cb" style="float:right;" value="<?php echo @$product['product_id'];?>" type="checkbox"></p>
                                            <p>(<?php echo substr(@$product['description'],0,250);?>)</p>
                                            </td>
                                            <?php
                                           // echo '<pre>';print_r($user_product_targerts);echo '</pre>';
                                            foreach ($months as $month) {
                                                $quantity='';
                                                if(@$user_product_targerts[$product['product_id']][$month['month_id']][$month['year']]>0);
                                                $quantity = @$user_product_targerts[$product['product_id']][$month['month_id']][$month['year']];
                                                echo '<td class="text-center"><input type="text" maxlength="6" value="'.@$quantity.'" name="product_'.$product['product_id'].'_'.$month['month_id'].'" class="form-control only-numbers product_target product'.$product['product_id'].' month'.$month['month_id'].'">
                                                      </td>';
                                            }
                                            ?>
                                        </tr>
                                                <?php
                                            }
                                        } else {
                                            ?>	<tr><td colspan="14" align="center"><span class="label label-primary">No Records</span></td></tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>				
        </div>
    </div>
<?php $this->load->view('commons/main_footer.php', $nestedView); ?>
