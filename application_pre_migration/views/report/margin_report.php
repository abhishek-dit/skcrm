<?php $this->load->view('commons/main_template', $nestedView); ?>
<style type="text/css">
    .radio-inline{ padding-left: 10px !important;}
    .radio-inline input[type="radio"]{ margin: 0px 4px 0px 0px;}
</style>

<div class="cl-mcont">
    <div id="loaderID" style="position:fixed; top:50%; left:58%; z-index:2; opacity:0"><img src="<?php echo assets_url(); ?>images/ajax-loading-img.gif" /></div>
    <div class="row"> 
        <div class="col-sm-12 col-md-12">
            <div class="block-flat">
                <div class="content">  
                    <form method="post" action="<?php echo SITE_URL;?>margin_analysis_report" class="submit_frm">
                        <input type="hidden" name="search" value="1">
                        <input type='hidden' name="timeline" class="time" value="<?php if(@$searchParams['timeline']!='') { echo $searchParams['timeline']; } ?>">
                                              
                        <div class="row">
                            <div class="form-group">
                                <div class="col-sm-12" style="margin-top:15px;">
                                <?php 
                                $roles_had_regions_filter = array(8,9);
                                 if(in_array($this->session->userdata('role_id'),$roles_had_regions_filter)) { ?>                      
                                    <div class="col-sm-2">                            
                                        <select class="select2 regions" style="width:100%" name="mr_region">
                                            <option value="">All Regions</option>
                                            <?php
                                           foreach ($regions as $reg) {
                                                    $selected = ($reg['location_id']==@$searchParams['mr_region'])?'selected="selected"':'';
                                                    echo '<option value="'.$reg['location_id'].'" '.$selected.'>'.$reg['location'].'</option>';
                                                }
                                            ?>
                                        </select>                        
                                    </div>
                                    <?php }
                                    else
                                    {
                                        ?>
                                        <input type="hidden" class="regions" value=""> <?php
                                    }
                                    ?>
                                    <div class="col-sm-2">
                                        <input type="text" name="mr_fromDate" id="date_from" placeholder="From Date" class="form-control" value="<?php if(@$searchParams['mr_fromDate']!='') { echo $searchParams['mr_fromDate']; } ?>" readonly></div>
                                    <div class="col-sm-2">
                                        <input type="text" placeholder="To Date" name="mr_toDate" id="date_to" class="form-control" value="<?php if(@$searchParams['mr_toDate']!='') { echo $searchParams['mr_toDate']; } ?>" readonly>
                                    </div>
                                    <div class="col-sm-2">                            
                                        <select class="select2 segment" style="width:100%" name="mr_segment">
                                            <option value="">Select Segment</option>
                                            <?php
                                            foreach ($product_segments as $srow) {
                                                $selected = ($srow['group_id']==$searchParams['mr_segment'])?'selected':'';
                                                echo '<option value="'.$srow['group_id'].'" '.$selected.'>'.$srow['name'].'</option>';
                                            }
                                            ?>
                                        </select>                        
                                   </div>
                                   <div class="col-sm-4">                            
                                        <select class="select2 product" style="width:100%" name="mr_product">
                                            <option value="">Select Product</option>
                                            <?php
                                            if(count($products)>0)
                                            {
                                                foreach ($products as $srow) {
                                                    $selected = ($srow['product_id']==$searchParams['mr_product'])?'selected':'';
                                                    echo '<option value="'.$srow['product_id'].'" '.$selected.'>'.$srow['description'].'('.$srow['name'].')</option>';
                                                }
                                            }
                                            ?>
                                        </select>                        
                                   </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-sm-12" style="margin-top:15px;">
                                   <?php
                                   if(count($users)>1) { ?>
                                    <div class="col-sm-3">                            
                                        <select class="select2 users" style="width:100%" name="mr_user">
                                            <option value="">All Users</option>
                                            <?php
                                            foreach ($users as $us) {
                                                $selected = ($us['user_id']==@$searchParams['mr_user'])?'selected="selected"':'';
                                                echo '<option value="'.$us['user_id'].'" '.$selected.'>'.$us['first_name'].' ('.$us['employee_id'].')'.'</option>';
                                            }
                                            ?>
                                        </select>                        
                                   </div>
                                   <?php } 
                                   else
                                    {?>
                                        <input type="hidden" value="" class="users"> <?php
                                    }?>
                                   <div class="col-sm-3">                            
                                        <!-- <select class="customer" style="width:100%" name="mr_customer">
                                            <?php
                                            if ($searchParams['mr_customer']) {
                                                ?>
                                                <option value="<?php echo @$customerDetails['customer_id'] ?>"><?php echo @$customerDetails['customer']; ?></option>
                                                <?php
                                            } else {
                                                ?>
                                                <option value="">Select Customer</option>
                                                <?php
                                            }
                                            ?>
                                        </select>  --> 
                                        <input class="form-control" placeholder="Customer" title="Customer" type="text" name="mr_customer" id="mr_customer" value="<?php echo @$searchParams['mr_customer']?>">
                                        <input type="hidden" name="mr_customer_id" id="mr_customer_id" value="<?php echo @$searchParams['mr_customer_id']?>">                       
                                   </div>
                                   <div class="col-sm-3">                            
                                        <input class="form-control" placeholder="Dealer" title="Dealer" type="text" name="mr_dealer" id="mr_dealer" value="<?php echo @$searchParams['mr_dealer']?>">
                                        <input type="hidden" name="mr_dealer_id" id="mr_dealer_id" value="<?php echo @$searchParams['mr_dealer_id']?>">                       
                                   </div>
                                   <div class="col-sm-3">
                                        <button type="submit" name="searchMarginData" value="1" class="btn btn-success"><i class="fa fa-search"></i></button>
                                        <a  class="btn btn-success" href="<?php echo SITE_URL.'margin_analysis_report';?>"><i class="fa fa-refresh"></i></a>
                                        <button style="margin-left:5px;" type="submit" formaction="<?php echo SITE_URL?>download_margin_analysis_report" name="downloadProductMarginData" value="1" class="btn btn-success"><i class="fa fa-cloud-download"></i></button>
                                   </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive" style="margin-top: 10px;">
                        <table class="table table-bordered hover">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th class="text-center"><strong>Product Segment</strong></th>
                                    <th class="text-center"><strong>Revenue (Lacs)</strong></th>
                                    <th class="text-center"><strong>Gross Margin %</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                if(count(@$table_data['segment_data'])>0)
                                {
                                    $total_nsp = 0; $total_gross_margin = 0;
                                    foreach(@$table_data['segment_data'] as $row)
                                    { 
                                        $order_value = $row['order_value'];
                                        $basic_price = $row['basic_price'];
                                        $nsp = round(get_nsp($order_value));
                                        $gross_margin = $nsp - $basic_price;
                                        $gross_margin_percentage = round(($gross_margin*100/$nsp),2);

                                        $total_nsp += $nsp;
                                        $total_gross_margin += $gross_margin;
                                    ?>
                                        <tr>
                                            <td class="center "><img src="<?php echo assets_url(); ?>images/plus.png" class="toggle-details"></td>
                                            <td class="text-left"><?php echo $row['segment']; ?></td>
                                            <td class="text-right"><?php echo round($nsp/100000); ?></td>
                                            <td class="text-right"><?php echo $gross_margin_percentage.'%'; ?></td>
                                        </tr>
                                    <?php if(count(@$table_data['product_data'][$row['group_id']])>0)
                                        {  $slno = 1; ?>
                                        <tr class="details">
                                            <td  colspan="4">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center"><strong>S.No</strong></th>
                                                            <th class="text-center"><strong>Product Description</strong></th>
                                                            <th class="text-center"><strong>Product Code</strong></th>
                                                            <th class="text-center"><strong>Revenue (Lacs)</strong></th>
                                                            <th class="text-center"><strong>Gross Margin %</strong></th>
                                                            <th class="text-center"><strong>Qty</strong></th>
                                                            <th class="text-center"><strong>ASP (Lacs)</strong></th>
                                                            <th class="text-center"><strong>Unit DP (Lacs)</strong></th>
                                                            <th class="text-center"><strong>Var %</strong></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                <?php foreach(@$table_data['product_data'][$row['group_id']] as $value)
                                                { 
                                                    $product_order_value = $value['order_value'];
                                                    $product_basic_price = $value['basic_price'];
                                                    $product_nsp = round(get_nsp($product_order_value));
                                                    $product_gross_margin = $product_nsp - $product_basic_price;
                                                    $product_gross_margin_percentage = round(($product_gross_margin*100/$product_nsp),2);
                                                    $product_qty = $value['product_total_qty'];
                                                    $product_asp = round($product_nsp/$product_qty);
                                                    $product_unit_dp = $value['unit_dp'];
                                                    if($product_unit_dp>0)
                                                        $variance_percentage = round(((($product_order_value/$product_qty)-$product_unit_dp)/$product_unit_dp)*100,2);
                                                    else $variance_percentage = 0;
                                                ?>
                                                    <tr class="asset_row">
                                                        <td class="text-center"><?php echo $slno++; ?></td>
                                                        <td class="text-left"><?php echo $value['description']; ?></td>
                                                        <td class="text-left"><?php echo $value['name']; ?></td>
                                                        <td class="text-right"><?php echo round($product_nsp/100000); ?></td>
                                                        <td class="text-right"><?php echo $product_gross_margin_percentage.'%'; ?></td>
                                                        <td class="text-right"><?php echo indian_format_price($product_qty); ?></td>
                                                        <td class="text-right"><?php echo round($product_asp/100000,2); ?></td>
                                                        <td class="text-right"><?php echo round($product_unit_dp/100000,2); ?></td>
                                                        <td class="text-right"><?php echo $variance_percentage.'%'; ?></td>
                                                    </tr>
                                                    
                                                    <?php
                                                    
                                                } ?>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr><?php
                                        }
                                        else
                                        {
                                            ?>
                                            <tr class="details"><td colspan="4" align="center"> No records found</td></tr>
                                            <?php
                                        }
                                    }
                                    $total_gross_margin_percentage = round($total_gross_margin*100/$total_nsp,2);
                                    ?>
                                        <tr>
                                            <td class="center "></td>
                                            <td class="text-right"><strong>Total<strong></td>
                                            <td class="text-right"><?php echo round($total_nsp/100000); ?></td>
                                            <td class="text-right"><?php echo $total_gross_margin_percentage.'%'; ?></td>
                                        </tr>
                                    <?php
                                
                                } else {?>
                                    <tr><td colspan="11" align="center"><span class="label label-primary">No Records Found</span></td></tr>
                        <?php   } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>              
    </div>
</div>
<!-- <b style="color:red;">▼ -20</b> -->
<!-- <b style="color:green;">▲ 20</b> -->
<style type="text/css">
    .ui-autocomplete{
        min-height: 250px;
        overflow-x: hidden;
        overflow-y: scroll;
    }
</style>
<?php $this->load->view('commons/main_footer.php', $nestedView); ?>
<script type="text/javascript">
var ASSET_URL = '<?php echo assets_url()?>';
    

    $(document).on('change','.segment',function(){
        //alert($(this).val()); 
        var segment = $(this).val();
        if(segment!='')
        {
            var data='segment='+segment;
            $.ajax({
                url: SITE_URL+'getProductsDropdownBySegment',
                type :"POST",
                data:data,
                success:function(data){
                  $('.product').html(data);
                }
            });
        }
        else
        {
            $('.product').html('<option value="">Select Product</option>');
        }
    });

    $("#date_from").datepicker({
        dateFormat: "yy-mm-dd",
        changeMonth: true,
        changeYear: true,
       // minDate: 0,
        onSelect: function (date) {
           
            var date2 = $(this).datepicker('getDate');
            $('#date_to').datepicker('option', 'minDate', date2);
            //customDateChangeEvent();
        }
    });

    $("#date_to").datepicker({
        dateFormat: "yy-mm-dd",
        changeMonth: true,
        changeYear: true,
        onSelect: function (date) {
           
            var date2 = $(this).datepicker('getDate');
            $('#date_from').datepicker('option', 'maxDate', date2);
            //customDateChangeEvent();
                            
        }
        
    });
    function customDateChangeEvent(){
        var from_date = $('#date_from').val();
        var to_date = $('#date_to').val();
        $('.time').val('');
        if(from_date!=''&&to_date!='')
        {
            $("#pcont").css("opacity",0.5);
            $("#loaderID").css("opacity",1);
            $('.submit_frm').submit();
        }
        
    }

    $('.details, .details2').hide();
    $(document).on('click',".toggle-details",function () { 
        var row=$(this).closest('tr');
        var next=row.next();
        $('.details').not(next).hide();
        $('.toggle-details').not(this).attr('src',ASSET_URL+'images/plus.png');
        next.toggle();
        if (next.is(':hidden')) {
            $(this).attr('src',ASSET_URL+'images/plus.png');
        } else {
            $(this).attr('src',ASSET_URL+'images/minus.png');
        }
    });

    $(document).on('click',".toggle-details2",function () { 
        var row=$(this).closest('tr');
        var next=row.next();
        $('.details2').not(next).hide();
        $('.toggle-details2').not(this).attr('src',ASSET_URL+'images/plus.png');
        next.toggle();
        if (next.is(':hidden')) {
            $(this).attr('src',ASSET_URL+'images/plus.png');
        } else {
            $(this).attr('src',ASSET_URL+'images/minus.png');
        }
    });

    $("#mr_customer").autocomplete({
        source:SITE_URL+'getCustomersAutoCompleteList?region='+$('.regions').val(),
        minLength:1,
        width: 402,
        select: function( event, ui ) {
          var label = ui.item.label;  
          $('#mr_customer_id').val(ui.item.customer_id);
        } 
    });
    $("#mr_dealer").autocomplete({
        source:SITE_URL+'getDealersAutoCompleteList?region='+$('.regions').val(),
        minLength:1,
        width: 402,
        select: function( event, ui ) {
          var label = ui.item.label;  
          $('#mr_dealer_id').val(ui.item.dealer_id);
        } 
    });
</script>