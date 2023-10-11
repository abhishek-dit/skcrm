<?php
$this->load->view('commons/main_template', $nestedView);
echo $this->session->flashdata('response'); 
?>
<div class="row"> 
    <div class="col-sm-12 col-md-12">
        <div class="block-flat">
            <div class="content">
                <div class="row">
                    <div class="form-group" >
                        <form role="form" class="form-horizontal" method="post" action="<?php echo SITE_URL; ?>distributor_stock_details">
                            <div class="col-sm-12">
                               
                               <label class="col-sm-2 control-label">Product</label>
                                <div class="col-sm-4">
                                     <?php
                                    $attrs = '  class="select2 " id="product_id"  ';
                                    @$product_res=array(''=>'Select Product')+@$product_res;
                                    echo form_dropdown("product_id", @$product_res, @$searchParams['product_id'], @$attrs);
                                    ?>
                                
                                </div>
                                <div class="col-sm-4">
                                    <button type="submit" name="search" title="Search" value="1" class="btn btn-success"><i class="fa fa-search"></i> </button>
                                    <button type="submit" name="download" title="" class="btn btn-success" formaction="<?php echo SITE_URL; ?>download_dist_stock" value="download"><i class="fa fa-cloud-download"> Excel</i> </button>
                                     <button type="submit" name="print" title="" class="btn btn-success" formaction="<?php echo SITE_URL; ?>print_dist_stock" value="print"><i class="fa fa-print"> print</i> </button>
                                </div>
                            </div> 
                        </form >
                    </div>
                </div>
                <br>
                <form method="post" >
                    <div class="table-responsive col-md-12">
                        <table class="table table-bordered hover">
                            <thead>
                                <tr>
                                    <th class="text-center" width="3%"><strong>SNO</strong></th>
                                    <th class="text-center" width="10%"><strong>Product</strong></th>
                                    <th class="text-center" width="8%"><strong>Description</strong></th>
                                    <th class="text-center" width="8%"><strong>Stock Available</strong></th>
                                   <!--  <th class="text-center" width="10%"><strong>Actions</strong></th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                //@$inc = $start + 1;
                                if (!empty($searchResults)) {
                                    $sno=1;
                                    $i=0;
                                    foreach (@$searchResults as $row) {
                                        $quantity=@$product_qty[$row['product_id']]['opening_stock']+@$product_qty[$row['product_id']]['po_stock']-@$product_qty[$row['product_id']]['tagged_stock'];
                                        if($quantity!=''|| $quantity !=0)
                                        {  $i++; ?>
                                           <tr>
                                               
                                                <td class="text-center"><?php echo $sn++; ?></td>
                                                <td class="text-center"><?php echo $row['name']; ?></td>
                                                <td class="text-center" align='center'><?php echo @$row['description']; ?></td>
                                                 <td class="text-center" align='center'><?php echo @$quantity; ?></td>
                                            </tr>
                                        <?php 
                                        }
                                        
                                    }
                                        if($i==0)
                                        { ?>
                                            <tr><td colspan="4" align="center"><span class="label label-primary">No Records</span></td></tr>
                                    <?php    }

                                } else {
                                    ?>	<tr><td colspan="4" align="center"><span class="label label-primary">No Records</span></td></tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </form> 
                 <div class="row">
                    <!-- <div class="col-sm-12">
                        <div class="pull-left"><?php echo @$pagermessage ; ?></div>
                        <div class="pull-right">
                            <div class="dataTables_paginate paging_bs_normal">
                                <?php echo @$pagination_links; ?>
                            </div>
                        </div>
                    </div> -->
                </div> 
            </div>              
        </div>
    </div>
</div>
<?php $this->load->view('commons/main_footer.php', $nestedView); ?>
<style>
    .pagination{
        width: 100%;
    }
</style>