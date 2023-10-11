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
                    <form method="post" action="<?php echo SITE_URL;?>cnote_margin_analysis" class="submit_frm">
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
                                    if(count($users)>1) { ?>
                                    <div class="col-sm-4">                            
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
                                    <div class="col-sm-2">
                                        <input type="text" name="mr_fromDate" id="date_from" placeholder="From Date" class="form-control" value="<?php if(@$searchParams['mr_fromDate']!='') { echo $searchParams['mr_fromDate']; } ?>" readonly></div>
                                    <div class="col-sm-2">
                                        <input type="text" placeholder="To Date" name="mr_toDate" id="date_to" class="form-control" value="<?php if(@$searchParams['mr_toDate']!='') { echo $searchParams['mr_toDate']; } ?>" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-sm-12" style="margin-top:15px;">
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
                                   <div class="col-sm-3">
                                        <button type="submit" name="searchMarginData" value="1" class="btn btn-success"><i class="fa fa-search"></i></button>
                                        <a  class="btn btn-success" href="<?php echo SITE_URL.'cnote_margin_analysis';?>"><i class="fa fa-refresh"></i></a>
                                        <button style="margin-left:5px;" type="submit" formaction="<?php echo SITE_URL?>download_cnote_margin_report" name="downloadCNoteMarginData" value="1" class="btn btn-success"><i class="fa fa-cloud-download"></i></button>
                                   </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive" style="margin-top: 10px;">
                        <table class="table table-bordered hover">
                            <thead>
                                <tr>
                                    <th class="text-center"><strong>C-Note ID</strong></th>
                                    <th class="text-center"><strong>Type</strong></th>
                                    <th class="text-center"><strong>Customer Name</strong></th>
                                    <th class="text-center"><strong>Created By</strong></th>
                                    <th class="text-center"><strong>SO Number</strong></th>
                                    <th class="text-center"><strong>C-Note Date</strong></th>
                                    <th class="text-center"><strong>Product Details</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            if(count($cnote_results)>0)
                            {
                                foreach ($cnote_results as $row) {
                                    ?>
                                <tr>
                                    <td><?php echo $row['cnote_id'];?></td>
                                    <td><?php echo ($row['cnote_type']==1)?'Regular':'Purchase Order';?></td>
                                    <td><?php echo $row['customer'];?></td>
                                    <td><?php echo $row['sales_engineer'];?></td>
                                    <td><?php echo $row['SO_number'];?></td>
                                    <td><?php echo format_date($row['cnote_created_time']);?></td>
                                    <td><?php echo $row['product_details'];?></td>
                                </tr>
                                    <?php
                                }
                            }
                            else
                            {
                                echo '<tr><td colspan="7" align="center">No Records Found</td></tr>';
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
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
                </div>
            </div>
        </div>              
    </div>
</div>
<!-- <b style="color:red;">▼ -20</b> -->
<!-- <b style="color:green;">▲ 20</b> -->

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
</script>