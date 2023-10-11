<?php
$this->load->view('commons/main_template', $nestedView);
echo $this->session->flashdata('response'); 
?>
<div class="row"> 
    <div class="col-sm-12 col-md-12">
        <div class="block-flat">
            <div class="content">
            <?php if($flag ==1) { ?>
                <div class="row">
                    <div class="form-group" >
                        <form role="form" class="form-horizontal" method="post" action="<?php echo SITE_URL; ?>po_list">
                            <div class="form-group" >
                                <div class="col-sm-12" style="margin-top: 10px">
                                    
                                    <div class="col-sm-1">
                                        <input type="text" title="PO ID" name="purchase_order_id" placeholder="PO ID" maxlength="20"  value="<?php echo @$searchParams['purchase_order_id']; ?>" id="companyName" class="form-control">
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="text" title="PO created: Start Date" required class="form-control" id="start_date" placeholder="Start Date" name="start_date" readonly  value="<?php echo @$searchParams['start_date']; ?>">
                                    </div>     
                                    <div class="col-sm-2">
                                            <input type="text" title="PO created: End Date" required class="form-control" id="end_date" placeholder="End Date" name="end_date" readonly  value="<?php echo @$searchParams['end_date']; ?>">
                                    </div>
                                    <div class="col-sm-2">
                                        <?php $status_list = getPoStatusList();?>
                                        <select class="form-control" name="approval_status"  title="Approval Status">
                                            <option value="">Approval Status</option>
                                            <?php 
                                            foreach ($status_list as $status=>$status_lable) {
                                                $selected = ($searchParams['approval_status']==$status)?'selected':'';
                                                echo '<option value="'.$status.'" '.$selected.'>'.$status_lable.'</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <?php if($this->session->userdata('role_id')!=5) { ?>
                                        <div class="col-sm-2">
                                       <?php
                                         $attrs = '  class="select2 dist_id" id="billing_id"  ';
                                        @$users=array(''=>'Select Distributor Name')+@$users;
                                        echo form_dropdown("users_id", @$users, @$searchParams['users_id'], @$attrs);
                                        ?>
                                        </div>
                                     
                                    <?php } ?>
                                    <div class="col-sm-2" align="right">
                                        <button type="submit" name="search" title="Search" value="1" class="btn btn-success"><i class="fa fa-search"></i> </button>
                                        <a href="<?php echo SITE_URL; ?>po_list" title="Reset Search" class="btn btn-success"><i class="fa fa-refresh"></i></a>
                                        <!-- <button type="submit" name="download" title="" class="btn btn-success" formaction="<?php echo SITE_URL; ?>download_po" value="download"><i class="fa fa-cloud-download"></i> </button> -->
                                        <?php if($this->session->userdata('role_id')==5) 
                                        { 
                                            
                                                ?>
                                                <a href="<?php echo SITE_URL; ?>add_po"  title="Add New" class="btn btn-success"><i class="fa fa-plus"></i></a><?php
                                              
                                        }?>
                                    </div> 
                                </div>
                            </div>
                        </form >
                    </div>
                </div>
                <form method="post" >
                    <div class="table-responsive col-md-12">
                        <table class="table table-bordered hover">
                            <thead>
                                <tr>
                                    <th class="text-center" width="5%"><strong>PO ID</strong></th>
                                    <th class="text-center" width="25%"><strong>Products</strong></th>
                                    <th class="text-center" width="8%"><strong>Order Value</strong></th>
                                    <th class="text-center" width="5%"><strong>Advance</strong></th>
                                    <th class="text-center" width="6%"><strong>Created On</strong></th>
                                    <th class="text-center" width="8%"><strong>Approval Status</strong></th>
                                    <?php if($this->session->userdata('role_id')!=5) { ?>
                                    <th class="text-center" width="20%"><strong>Created By</strong></th>
                                    <?php } ?>
                                    <th class="text-center" width="8%"><strong>Revisions</strong></th>
                                    <th class="text-center" width="11%"><strong>Actions</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                //@$inc = $start + 1;
                                if (!empty($searchResults)) {

                                    foreach (@$searchResults as $row) {
                                        $res=get_extra_warranty_cost($row['order_value'],$row['dp_value'],$row['warranty'],$row['default_warranty']); 
                                        ?>
                                        <tr>
                                           
                                            <td class="text-center"><?php echo $row['po_number']; ?></td>
                                            <td class="text-center" align='center'><?php echo $row['product_details']; ?></td>
                                            <td class="text-center" align='center'><?php echo indian_format_price($res['grand_total']); ?></td>
                                            <td class="text-center" align='center'><?php echo format_advance($row['advance'],$row['advance_type']); ?></td>
                                            <td class="text-center" align='center'><?php echo @format_date($row['created_time']); ?></td>
                                            <td class="text-center"><?php echo getPoStatusLabel($row['status']); ?></td>
                                            <?php if($this->session->userdata('role_id')!=5) { ?>
                                            <td class="text-center"><?php echo $row['distributor_name']; ?></td>
                                            <?php } ?>
                                            <td>
                                                <button type="button" class="btn btn-primary md-trigger"  style="padding: 3px;"  id="add_quote" data-toggle="modal" data-target="#info<?php echo @$row['purchase_order_id']; ?>" ><i class="fa fa-info"></i></button> 
                                                <?php
                                              if($row['status']==3) // PO rejected can revise it
                                              {
                                                ?>
                                                <a href="<?php echo SITE_URL; ?>po_revision/<?php echo @icrm_encode($row['purchase_order_id']); ?>" style="padding:3px 3px;" title="PO Revision"><button type='button' class="btn btn-primary" style="padding: 3px;" ><i class="fa fa-plus"></i></button></a>
                                                <?php 
                                              }
                                              ?>
                                            </td>
                                            <td>
                                             <a href="<?php echo SITE_URL.'view_po/'.@icrm_encode($row['purchase_order_id']); ?>" style="padding:3px 3px;" title="PO View"><button type='button' class="btn btn-primary"  style="padding: 3px;" ><i class="fa  fa-building-o"></i></button></a>
                                             <?php
                                               /*if($redirect==1)
                                               {
                                                  $site_url=SITE_URL.'tag_opportunity/'.@icrm_encode($row['purchase_order_id']);
                                               }
                                               elseif ($redirect==2) {
                                                   $site_url=SITE_URL.'untag_opportunity/'.@icrm_encode($row['purchase_order_id']);
                                               }*/
                                             ?>
                                              <!-- <a href="<?php echo $site_url; ?>" style="padding:3px 3px;" title="<?php echo $title; ?>"><button type='button' class="<?php echo $btn_class;?>"  style="padding: 3px;" ><i class="<?php echo $link_class;?>"></i></button></a> -->
                                              <?php
                                              if($row['status']==4&&$this->session->userdata('user_id')==$row['user_id']) // CNote cleared for invoice
                                              {
                                                ?>
                                                <a href="<?php echo SITE_URL; ?>contractPdf/<?php echo @icrm_encode($row['contract_note_id']); ?>" style="padding:3px 3px;" title="Contract Note Download"><button type='button' class="btn btn-primary" style="padding: 3px;" ><i class="fa fa-cloud-download"></i></button></a>
                                                <?php 
                                              }
                                              ?>
                                            </td>
                                           
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    ?>  <tr><td colspan="7" align="center"><span class="label label-primary">No Records</span></td></tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </form> 
                <div class="row">
                    <div class="col-sm-12">
                        <div class="pull-left"><?php echo @$pagermessage ; ?></div>
                        <div class="pull-right">
                            <div class="dataTables_paginate paging_bs_normal">
                                <?php echo @$pagination_links; ?>
                            </div>
                        </div>
                    </div>
                </div> 
                <?php } if($flag == 2) { ?>
                <div class="row no-gutter">
                    <form class="form-horizontal" role="form" action="<?php echo SITE_URL; ?>insert_po" method="post">
                        <?php $show_warranty = get_preference('enable_warranty','dealer_settings');
                        if($show_warranty==1) {
                        ?>
                        <div class="form-group warranty_block">
                            <label class="col-sm-3 control-label">Warranty<span class="req-fld">*</span></label>
                                <div class="col-sm-6">
                                    <select name="warranty"  class="select2 warranty" <?php if(@$display_results==1 && @$po_results['warranty']!=''){ echo "disabled" ;} ?> style="width:100%">
                                        <option value="">Select</option>
                                        <?php $selected='';
                                        for($i=12; $i<=60; $i+=3){
                                            if($i==@$po_results['warranty'])
                                                {
                                                    $selected="selected";
                                                } 
                                                else
                                                {
                                                    $selected='';
                                                }
                                            echo "<option value=".$i." ".$selected.">".$i." Months"."</option>";
                                            }
                                        ?> 
                                    </select>
                                </div>
                        </div>
                        <?php
                        }
                        else
                        {
                            ?>
                            <input type="hidden" name="warranty" value="<?php echo getDefaultWarranty();?>">
                            <?php
                        }
                        ?>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Advance Payment<span class="req-fld">*</span></label>
                                <div class="col-sm-2">
                                    <select name="advance_type" required id="advance_type"  <?php if(@$display_results==1 && @$po_results['advance_type']!=''){ echo "disabled" ;} ?> class="form-control">
                                        <option value="1" <?php if(@$po_results['advance_type']==1){
                                            echo "selected";
                                            } ?> >in %</option>
                                        <option value="2" <?php if(@$po_results['advance_type']==2){
                                            echo "selected";
                                            } ?> >in <?php echo $currency_val;?></option>
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <input type="number" class="form-control" placeholder="Advance Payment" min="0" max="100" name="advance" id="advance_collected" required  <?php if(@$display_results==1 && @$po_results['advance']!=''){ echo "disabled" ;} ?> value="<?php echo @$po_results['advance'];?>">
                                </div>
                        </div>
                        <?php if(@$po_results['balance_payment_days'] !='' && @$display_results==1){ ?>
                        <div class="form-group ">
                            <label class="col-sm-3 control-label">Balance Payment in <span class="req-fld">*</span></label>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <input type="number"  value="<?php echo @$po_results['balance_payment_days'];?>" disabled  class="form-control">
                                    <span class="input-group-addon">days</span>
                                </div>
                            </div>
                        </div>
                        <?php } else { ?>
                            <div class="form-group bal_payment_block">
                            <label class="col-sm-3 control-label">Balance Payment in <span class="req-fld">*</span></label>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <input type="number" required min="0" max="<?php echo get_preference('max_balance_payment_days','dealer_settings');?>" name="balance_payment_days" id="balance_payment_days" class="form-control">
                                    <span class="input-group-addon">days</span>
                                </div>
                            </div>
                        </div>
                        <?php } ?>

                         <div class="table-responsive  col-lg-12">
                            <div class="col-sm-12" style="padding-left:0px;">    
                                <table border="1" cellspacing="0" id="table1"
                                    class="table table-striped table-hover table-bordered ">
                                    <thead>
                                    <tr>
                                        <th class="text-center" width="13%">Product Segment</th>
                                        <th class="text-center" width="27%">Product</th>
                                        <th class="text-center" width="10%">Dealer Price</th>
                                        <th class="text-center" width="8%">Qty </th>
                                        <th class="text-center" width="20%">Discount </th>
                                        <th class="text-center" width="15%">Discounted Price </th>
                                        <?php if(@$display_results!=1) { ?>
                                        <th width="5%"> </th>
                                        <?php } ?>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if(@$display_results==1) 
                                    {   $total=0;
                                        $total_dp=0;
                                        foreach(@$product_results as $row)
                                        { 
                                            $discount = $row['discount'];
                                            $product_value = $row['unit_price']*$row['qty'];
                                            $dp_value=$row['dp']*$row['qty'];
                                            if($row['discount_type']==1)
                                            {
                                                $order_value = $product_value*(1-$row['discount']/100);
                                            }
                                            else{ 
                                                $order_value = $product_value-$discount;
                                                $discount = ($discount/$product_value)*100;
                                            }
                                            $order_value = round($order_value);
                                            ?>
                                            <tr>
                                                <td class="text-center"><?php echo $row['segment']; ?></td>
                                                <td class="text-center"><?php echo $row['product_name']; ?></td>
                                                <td class="text-center"><?php echo indian_format_price($row['unit_price']); ?></td> 
                                                <td class="text-center"><?php echo $row['qty']; ?></td>
                                                <td class="text-center"><?php echo round($discount,2).'%'; ?></td>
                                                <td class="text-center"><?php echo indian_format_price($order_value);?></td>
                                            </tr>
                                      <?php $total+=$order_value;
                                            $total_dp+=$dp_value;
                                        }
                                    }
                                    else { ?>
                                    <tr class="product_row">
                                       
                                        <td>
                                         <?php
                                            //echo form_dropdown('product_id[]', $product_id, @$searchParams['product_id'],'class="select3" style="width:100%"'); ?>
                                            <select class="segment form-control col1" style="width:100%" name="product_segment_id[]">
                                                <option value="">Select Segment</option>
                                                <?php
                                                foreach($segments as $srow)
                                                {
                                                    echo '<option value="'.$srow['group_id'].'" >'.$srow['name'].'</option>';
                                                }
                                                ?>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="select3 product_list col2 pro" style="width:100%" name="product_id[]">
                                                <option value="">Select Product</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="hidden" class="unit_price">
                                            <input type="hidden" class="unit_currency" name="unit_currency[]">
                                            <span class="unit_price_display"></span>
                                        </td>
                                        <td>
                                            <input type='number' min="1" class='form-control only-numbers product_qty col3' name='qty[]'/>
                                            <input type="hidden" name="" class="preference_value " value="<?php echo get_preference('max_discount','dealer_settings');?>">
                                        </td>
                                        <td class="discount_block">
                                            <select name="discount_type[]" class="discount_type form-control" style="width:70px;display:inline">
                                            <?php
                                                foreach ($discount_types as $key => $value) {
                                                    echo '<option value="'.$key.'">In '.$value.'</option>';
                                                }
                                            ?>
                                            </select>
                                            <input type="number" min="0" max="100" step=".01" name="discount[]" class="form-control discount" style="width:100px;display:inline"></td>
                                        </td>
                                        <td>
                                            <input type="hidden" class="discounted_value">
                                            <span class="discounted_value_display"></span>
                                        </td>
                                         <td colspan="3">
                                             <button type="button" class='btn delete btn-danger' style="padding: 3px;"><i class="fa fa-times"></i></button>
                                         </td>
                                    </tr>
                                   <?php } ?>
                                    </tbody>
                                </table>
                                <div class="row">
                                    <div class="col-md-12">
                                       <?php if(@$display_results!=1){ ?>
                                        <button type="button" class='btn addline2 btn-primary btn-sm col-md-1 ' style="padding: 5px;"><i class="fa fa-plus"></i></button> 
                                        <?php } ?>
                                        <div class ="col-md-offset-7 col-md-2" align="right"><b>Total: </b></div>
                                        <?php if(@$display_results==1)
                                        { ?>
                                         <div class="col-md-2" align="right">
                                            <span><?php echo indian_format_price($total); ?> </span>
                                        </div>
                                         <?php   } else { ?>
                                         <div class="col-md-2" align="right">
                                            <span id="total_value_display"></span>
                                        </div>
                                        <input type="hidden" id="total_value" value="0">
                                        <?php } ?>
                                    </div>
                                     <?php if(@$display_results!=1){ ?>
                                    <div class="col-md-12 discount_div hidden">
                                    <div class="col-md-offset-8 col-md-2" align="right"><b>Extra Warranty : </b></div>
                                    <div class="col-md-2" align="right">
                                        <span id="warranty_cost"></span>
                                    </div>
                                    </div>
                                    <div class="col-md-12 discount_div hidden">
                                    <div class="col-md-offset-8 col-md-2" align="right"><b>Grand Total : </b></div>
                                    <div class="col-md-2" align="right">
                                        <span id="grand_total"></span>
                                    </div>
                                    </div>
                                    <?php }
                                    elseif(@$display_results==1)
                                    {  
                                        if(@$po_results['warranty'] > @$po_results['default_warranty'])
                                        {   $results=get_extra_warranty_cost($total,$total_dp,$po_results['warranty'],$po_results['default_warranty']); ?>

                                            <div class="col-md-12">
                                            <div class="col-md-offset-7 col-md-2" align="right"><b>Extra Warranty : </b></div>
                                            <div class="col-md-2" align="right">
                                                <span><?php echo indian_format_price($results['war_dis_value']); ?> </span>
                                            </div>
                                            </div>
                                            <div class="col-md-12">
                                            <div class="col-md-offset-7 col-md-2" align="right"><b>Grand Total : </b></div>
                                            <div class="col-md-2" align="right">
                                                <span><?php echo indian_format_price($results['grand_total']); ?></span>
                                            </div>
                                            </div>
                                     
                                   <?php } } ?>
                                </div>

                            </div>
                        </div>
                        <?php if(count(@$opportunity_details) >0) { ?>
                        <p class="col-md-3"><strong>Tagged Opportunities :</strong></p>
                        <div class="table-responsive  col-lg-12">
                           <table border="1" cellspacing="0" id="table1"
                                    class="table table-striped table-hover table-bordered ">
                                    <thead>
                                    <tr>
                                        <th class="text-center" width="10%">Opportunity Id</th>
                                        <th class="text-center" width="30%">Product</th>
                                        <th class="text-center" width="5%">Quantity</th>
                                        <th class="text-center" width="10%">Status</th>
                                        <th class="text-center" width="20%">Expected Order Conclusion </th>
                                        <th class="text-center" width='15%'>Expected  Invoice Date</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                        foreach(@$opportunity_details as $row)
                                        { ?>
                                            <tr>
                                                <td class="text-center"><?php echo $row['opportunity_id']; ?></td>
                                                <td class="text-center"><?php echo $row['product_name']; ?></td>
                                                 <td class="text-center"><?php echo $row['required_quantity']; ?></td>
                                                  <td class="text-center"><?php echo po_opportunity_status($row['status']); ?></td>
                                                <td class="text-center"><?php echo $row['expected_order_conclusion']; ?></td>
                                                 <td class="text-center"><?php echo $row['expected_invoicing_date']; ?></td>
                                            </tr>
                                      <?php
                                    } ?>
                                   
                                    </tbody>
                                </table>
                        </div>
                        <?php } ?>
                        <div class="form-group">
                            <div class="col-sm-offset-5 col-sm-10">
                              <?php if(@$display_results==1)
                              {  if($view_list_page==1)
                                {
                                  $url=SITE_URL.'po_list';
                                }
                                elseif($view_list_page==2)
                                {
                                   $url=SITE_URL.'po_opp_tag_list';
                                }
                                ?>

                              <a class="btn btn-primary" href="<?php echo $url; ?> "><i class="fa fa-chevron-left"></i> Back</a>
                            <?php  } else
                              { ?>
                                <button class="btn btn-primary submit_btn" type="submit" name="submit" value="button"><i class="fa fa-check"></i> Submit</button>
                                <a class="btn btn-danger" href="<?php echo SITE_URL;?>po_list"><i class="fa fa-times"></i> Cancel</a>
                           <?php } ?>
                            </div>
                        </div>
                    </form>
                </div>
                <?php } ?>
            </div>              
        </div>
    </div>
</div>
<div class="md-overlay"></div>
<?php 
if (!empty($searchResults)) {
    foreach (@$searchResults as $row) {
        include('modals/po_info_modal.php');

    }
}
?>
<script type="text/javascript">
var default_warranty= <?php echo get_preference('default_warranty','general_settings'); ?>;
var cost_of_warranty=<?php echo get_preference('cost_of_maintaining_warranty','margin_settings'); ?>;
</script>
<?php $this->load->view('commons/main_footer.php', $nestedView); ?>

<style>
    .pagination{
        width: 100%;
    }
</style>