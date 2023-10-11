<?php $this->load->view('commons/main_template', $nestedView);
echo $this->session->flashdata('response'); 
 ?>
<div class="row"> 
    <div class="col-sm-12 col-md-12">
        <div class="block-flat">
            <div class="content">
                <div class="header">
                    <form class="form-horizontal" role="form" method="post" action="<?php echo SITE_URL.'insert_product_opening_stock';?>">
                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                        <div class="portlet-body">
                            <?php if($this->session->userdata('role_id')==7)
                                { ?>
                                <div class="header">                            
                                    <h4 align="center"><?php echo getUserName($user_id);?></h4>
                                </div> 
                              <?php }  ?>                          
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <tr style="background-color:#6B9BCF; font-size:12px; font-weight:bold">
                                                <td style="color:#fff">S.No</td> 
                                                <td style="color:#fff">Product Code</td>
                                                <td style="color:#fff">Description</td>
                                                <td style="color:#fff" align="left">Opening Stock</td>
                                            </tr>
                                           <?php $sno=1;
                                           foreach(@$product_category_results as $key =>$value)
                                            {   ?>
                                               <!--  <tr align="center" style="background-color:#889ff3;">
                                                   <td colspan="4" style="color:white;"><b><?php echo @$value['category_name']; ?></b></td>
                                                </tr> -->
                                                <?php 
                                                foreach (@$value['groups'] as $key1 =>$value1)
                                                { ?> 
                                                    <!-- <tr>
                                                        <td colspan="4"><b><h5><?php echo @$value1['group_name']; ?></h5></b></td>
                                                    </tr> -->
                                                    <tr align="left" style="background-color:#889ff3;">
                                                       <td colspan="4" style="color:white;padding: 0px 10px; font-size:15px;"><b><?php echo @$value1['group_name']; ?></b></td>
                                                    </tr>
                                                    <?php foreach(@$value1['products'] as $keys =>$values)
                                                    { ?>

                                                        <tr>
                                                            <td><?php echo  $sno++; ?></td> 
                                                            <input type="hidden" name="product_id[<?php echo $values['product_id'];?>]" value="<?php echo $values['product_id'];?> ">
                                                            <input type="hidden" name="product_name[]" value="<?php echo $values['product_name'];?>">
                                                            <td><?php echo @$values['product_name']; ?></td>
                                                            <td><?php echo @$values['description']; ?></td>
                                                            <td align="left"><input type="text" name="opening_stock[<?php echo $values['product_id'];?>]"  value="<?php if(@$stock[$values['product_id']]['stock']!=''){echo $stock[$values['product_id']]['stock'];} else { echo "0";}?>" class="form-control input-sm" style="width:135px"></td>
                                                        </tr>
                                                    <?php
                                                    } 
                                                } 
                                            } ?>    
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-5 col-sm-10">
                                <button class="btn btn-primary" type="submit" name="submit" value="button"><i class="fa fa-check"></i> Submit</button>
                                <?php if($this->session->userdata('role_id')!=7)
                                { ?>
                                <a class="btn btn-danger" href="<?php echo SITE_URL;?>product_opening_stock_details"><i class="fa fa-times"></i> Cancel</a>
                                <?php }
                                else
                                { ?>
                                    <a class="btn btn-danger" href="<?php echo SITE_URL;?>get_rbh_distributor_list"><i class="fa fa-times"></i> Cancel</a>
                            <?php    } ?>
                            </div>
                        </div>
                    </form>
                </div>
			</div>
		</div>
	</div>
</div>
<?php $this->load->view('commons/main_footer', $nestedView); ?>