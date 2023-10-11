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
                    <form method="post" action="<?php echo SITE_URL;?>tvs_2_report" class="submit_frm">
                        <input type="hidden" name="search" value="1">
                        <input type='hidden' name="timeline" class="time" value="<?php if(@$searchParams['timeline']!='') { echo $searchParams['timeline']; } ?>">
                        <div class="row">
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <!-- <div class="col-sm-offset-1 col-sm-3 custom_icheck" style="padding-left: 0px !important">
                                        <label class="radio-inline"> 
                                            <div class="iradio_square-blue <?php if($searchParams['measure']==1){ echo "checked"; }?>" style="position: relative;" aria-checked="true" aria-disabled="false">
                                                <input type="radio" class="measure" value="1" name="measure" <?php if($searchParams['measure']==1){ echo "checked"; }?> style="position: absolute; opacity: 0;">
                                                <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;"></ins>
                                            </div> 
                                            By &nbsp;Qty
                                        </label>
                                        <label class="radio-inline"> 
                                            <div class="iradio_square-blue <?php if($searchParams['measure']==2){ echo "checked"; }?>" style="position: relative; padding-left: 2px;" aria-checked="true" aria-disabled="false">
                                                <input type="radio" class="measure" value="2" name="measure" <?php if($searchParams['measure']==2){ echo "checked"; }?>  style="position: absolute; opacity: 0;">
                                                <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;"></ins>
                                            </div> 
                                            By Value
                                        </label>
                                    </div> -->
                                    <input type="hidden" name="measure" class="measure" value="2">
                                    <div class="col-md-offset-4 col-sm-4 custom_icheck" style="padding-left: 0px !important">
                                        <label class="radio-inline"> 
                                            <div class="iradio_square-blue <?php if($searchParams['groups']==1){ echo "checked"; }?>" style="position: relative;" aria-checked="true" aria-disabled="false">
                                                <input type="radio" class="groups" value="1" name="groups" <?php if($searchParams['groups']==1){ echo "checked"; }?> style="position: absolute; opacity: 0;">
                                                <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;"></ins>
                                            </div> 
                                            By Product
                                        </label>
                                        <label class="radio-inline"> 
                                            <div class="iradio_square-blue <?php if($searchParams['groups']==2){ echo "checked"; }?>" style="position: relative;" aria-checked="true" aria-disabled="false">
                                                <input type="radio" class="groups" value="2" <?php if($searchParams['groups']==2){ echo "checked"; }?> name="groups"  style="position: absolute; opacity: 0;">
                                                <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;"></ins>
                                            </div> 
                                            By Region
                                        </label>
                                    </div>
                                    
                                    <div class="col-sm-3 custom_icheck" style="padding-left: 0px !important">
                                        <label class="radio-inline"> 
                                            <div class="iradio_square-blue <?php if($searchParams['view_page']==1){ echo "checked"; }?>" style="position: relative;" aria-checked="true" aria-disabled="false">

                                                <input type="radio" class="view_page" value="1" name="view_page" <?php if($searchParams['view_page']==1){ echo "checked"; }?>  style="position: absolute; opacity: 0;">
                                                <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;"></ins>
                                            </div> 
                                            By Graph
                                        </label>
                                        <label class="radio-inline"> 
                                            <div class="iradio_square-blue <?php if($searchParams['view_page']==2){ echo "checked"; }?>" style="position: relative;" aria-checked="true" aria-disabled="false">
                                                <input type="radio" <?php if($searchParams['view_page']==1){ echo "checked"; }?> class="view_page" value="2" name="view_page"  style="position: absolute; opacity: 0;">
                                                <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;"></ins>
                                            </div> 
                                            By Table
                                        </label>
                                    </div>
                                </div> 
                            </div>
                        </div>                         
                        <div class="row">
                            <div class="form-group">
                                <div class="col-sm-12" style="margin-top:15px;">
                                <?php  if(in_array($this->session->userdata('role_id'),margin_allowed_roles())) { ?>                      
                                    <div class="col-sm-2">                            
                                        <select class="form-control regions" style="width:100%" name="regions">
                                            <option value="">Select Regions</option>
                                            <?php
                                           foreach ($region as $reg) {
                                                    $selected = ($reg['location_id']==@$searchParams['regions'])?'selected="selected"':'';
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
                                    <div class="col-sm-2">                            
                                        <select class="form-control users select2 " style="width:100%" name="users">
                                            <option value="">Select Users</option>
                                            <?php
                                            foreach ($users as $us) {
                                                $selected = ($us['users_id']==@$searchParams['users'])?'selected="selected"':'';
                                                echo '<option value="'.$us['users_id'].'" '.$selected.'>'.$us['first_name'].' ('.$us['employee_id'].')'.'</option>';
                                            }
                                            ?>
                                        </select>                        
                                   </div>
                                   <?php } 
                                   else
                                    {?>
                                        <input type="hidden" value="" class="users"> <?php
                                    }?>
                                    <!-- <div class="col-sm-2"><input type="text" name="date_from" id="date_from" placeholder="From Date" class="form-control" value="<?php if(@$searchParams['date_from']!='') { echo $searchParams['date_from']; } ?>" readonly></div>
                                        <label class="col-sm-1">To Date</label>
                                        <div class="col-sm-2"><input type="text" placeholder="To Date" name="date_to" id="date_to" class="form-control" value="<?php if(@$searchParams['date_to']!='') { echo $searchParams['date_to']; } ?>" readonly></div> -->
                                    <div class="col-sm-2">
                                        <input type="hidden" name="dur" class="dur" value="<?php echo @$searchParams['duration']; ?>"> 
                                        <select class="form-control duration" style="width:100%" name="duration">
                                            
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="btn-group">
                                            <input type="button" data-id="w" name="timeline1" class="timeline btn <?php if(@$searchParams['timeline']=='w'){ echo "btn-success";} else { echo "btn-default"; } ?>" value="Week" <?php if(@$searchParams['timeline']=='w'){ echo "checked";} ?>>
                                            <input type="button" data-id="m" name="timeline1" class="timeline btn <?php if(@$searchParams['timeline']=='m'){ echo "btn-success";} else { echo "btn-default"; } ?>" value="Month" <?php if(@$searchParams['timeline']=='m'){ echo "checked";} ?>>
                                            <input type="button" data-id="q" name="timeline1" class="timeline btn <?php if(@$searchParams['timeline']=='q'){ echo "btn-success";} else { echo "btn-default"; } ?>" value="Quarter" <?php if(@$searchParams['timeline']=='q'){ echo "checked";} ?>>
                                            <input type="button" data-id="y" name="timeline1" class="timeline btn <?php if(@$searchParams['timeline']=='y'){ echo "btn-success";} else { echo "btn-default"; } ?>" value="Year" <?php if(@$searchParams['timeline']=='y'){ echo "checked";} ?>>
                                        </div>
                                    </div>
                                    <div class="col-md-1"><button formaction="<?php echo SITE_URL.'download_target_vs_sales_report'?>" class="btn btn-primary" title="Download Report"><i class="fa fa-cloud-download"></i></button></div>
                                 </div>
                            </div>
                        </div>
                    </form>
                    <?php if($searchParams['groups']==1)
                    { ?>
                    <div class="table-responsive" style="margin-top: 10px;">
                        <table class="table table-bordered hover">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th class="text-center"><strong>Category</strong></th>
                                   <!--  <th class="text-center"><strong>Previous Target</strong></th> -->
                                    <th class="text-center"><strong>Backlog</strong></th>
                                   <!--  <th class="text-center"><strong>Previous Sales</strong></th> -->
                                    <th class="text-center"><strong>Current Target</strong></th>
                                    <th class="text-center"><strong>Cumm Target</strong></th>
                                    <th class="text-center"><strong>Current Sales</strong></th>
                                    <th class="text-center"><strong>Open Orders</strong></th>
                                    <th class="text-center"><strong>Pending</strong></th>
                                    <th class="text-center"><strong>Funnel Opp</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                if(count(@$table_data)>0)
                                {
                                    foreach(@$table_data as $row)
                                    {  
                                        if($row['backlog'] >0 ||  $row['current_target']>0 ||  $row['cumm_target']>0 || $row['current_sales']>0 || $row['open_orders']>0 || $row['funnel_open_opp_hot']>0 || $row['funnel_open_opp_warm']>0 || $row['funnel_open_opp_cold']>0 || $row['pending']>0 ) 
                                        {
                                            $hot1=($row['funnel_open_opp_hot']>0)?$row['funnel_open_opp_hot']:0;
                                            $warm1=($row['funnel_open_opp_warm']>0)?$row['funnel_open_opp_warm']:0;
                                            $cold1=($row['funnel_open_opp_cold']>0)?$row['funnel_open_opp_cold']:0;
                                        ?>
                                        <tr>
                                            <td class="center "><img src="<?php echo assets_url(); ?>images/plus.png" class="toggle-details"></td>
                                            <td class="text-center"><?php echo $row['category_name']; ?></td>
                                           <!--  <td class="text-center"><?php echo $row['previous_target']; ?></td> -->
                                            <td class="text-center"><?php echo $row['backlog']; ?></td>
                                           <!--  <td class="text-center"><?php echo $row['previous_sales']; ?></td> -->
                                            <td class="text-center"><?php echo $row['current_target']; ?></td>
                                            <td class="text-center"><?php echo $row['cumm_target']; ?></td>
                                            <td class="text-center"><?php echo $row['current_sales']; ?></td>
                                            <td class="text-center"><?php echo $row['open_orders']; ?></td>
                                            <td class="text-center"><?php echo $row['pending']; ?></td>
                                            <td class="text-center"><?php echo 'Hot :'.$hot1.', Warm :'.$warm1.', Cold :'.$cold1; ?></td>
                                        </tr>
                                    <?php  if(count(@$row['segment_list'])>0)
                                        {  $slno = 1; ?>
                                        <tr class="details">
                                            <td  colspan="11">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th></th>
                                                            <th class="text-center"><strong>Segment</strong></th>
                                                            <!-- <th class="text-center"><strong>Previous Target</strong></th> -->
                                                            <th class="text-center"><strong>Backlog</strong></th>
                                                           <!--  <th class="text-center"><strong>Previous Sales</strong></th> -->
                                                            <th class="text-center"><strong>Current Target</strong></th>
                                                            <th class="text-center"><strong>Cumm Target</strong></th>
                                                            <th class="text-center"><strong>Current Sales</strong></th>
                                                            <th class="text-center"><strong>Open Orders</strong></th>
                                                            <th class="text-center"><strong>Pending</strong></th>
                                                            <th class="text-center"><strong>Funnel Opp</strong></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                <?php foreach(@$row['segment_list'] as $value)
                                                { 
                                                    if($value['backlog'] >0 ||  $value['current_target']>0 ||  $value['cumm_target']>0 || $value['current_sales']>0 || $value['open_orders']>0 || $value['funnel_open_opp_hot']>0 || $value['funnel_open_opp_warm']>0 || $value['funnel_open_opp_cold']>0 || $value['pending']>0 )
                                                    {
                                                        $hot2=($value['funnel_open_opp_hot']>0)?$value['funnel_open_opp_hot']:0;
                                                        $warm2=($value['funnel_open_opp_warm']>0)?$value['funnel_open_opp_warm']:0;
                                                        $cold2=($value['funnel_open_opp_cold']>0)?$value['funnel_open_opp_cold']:0;
                                                    ?>
                                                        <tr class="asset_row">
                                                            <td class="center "><img src="<?php echo assets_url(); ?>images/plus.png" class="toggle-details2"></td>
                                                            <td class="text-center"><?php echo $value['group_name']; ?></td>
                                                            <!-- <td class="text-center"><?php echo $value['previous_target']; ?></td> -->
                                                            <td class="text-center"><?php echo $value['backlog']; ?></td>
                                                           <!--  <td class="text-center"><?php echo $value['previous_sales']; ?></td> -->
                                                            <td class="text-center"><?php echo $value['current_target']; ?></td>
                                                            <td class="text-center"><?php echo $value['cumm_target']; ?></td>
                                                            <td class="text-center"><?php echo $value['current_sales']; ?></td>
                                                            <td class="text-center"><?php echo $value['open_orders']; ?></td>
                                                            <td class="text-center"><?php echo $value['pending']; ?></td>
                                                            <td class="text-center"><?php echo 'Hot :'.$hot2.', Warm :'.$warm2.', Cold :'.$cold2; ?></td>
                                                        </tr>
                                                    <?php 
                                                    }if(count(@$value['product_list'])>0)
                                                    {   $slnum = 1; ?>
                                                    <tr class="details2">
                                                        <td  colspan="11">
                                                            <table class="table">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="text-center"><strong>Product</strong></th>
                                                                        <!-- <th class="text-center"><strong>Previous Target</strong></th> -->
                                                                        <th class="text-center"><strong>Backlog</strong></th>
                                                                       <!--  <th class="text-center"><strong>Previous Sales</strong></th> -->
                                                                        <th class="text-center"><strong>Current Target</strong></th>
                                                                        <th class="text-center"><strong>Cumm Target</strong></th>
                                                                        <th class="text-center"><strong>Current Sales</strong></th>
                                                                        <th class="text-center"><strong>Open Orders</strong></th>
                                                                        <th class="text-center"><strong>Pending</strong></th>
                                                                        <th class="text-center"><strong>Funnel Opp</strong></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php foreach(@$value['product_list'] as $k4=>$value2)
                                                                            {
                                                                                if(@$value2['previous_target'] >0 ||  @$value2['current_target']>0 ||  @$value2['previous_sales']>0 || @$value2['current_sales']>0 || @$value2['open_orders']>0 || @$value2['funnel_open_opp_hot']>0 || @$value2['funnel_open_opp_warm']>0 || @$value2['funnel_open_opp_cold']>0  ) 
                                                                                {
                                                                                    $backlog = @$value2['previous_target']-@$value2['previous_sales'];
                                                                                    if($backlog<0) { $backlog = 0;}
                                                                                    $cumm_target = $backlog+@$value2['current_target'];
                                                                                    $pending = ($backlog+@$value2['$current_target'])-@$value2['current_sales']-@$value2['open_orders'];
                                                                                    if($pending<0) { $pending = 0;}
                                                                                    $hot3=(@$value2['funnel_open_opp_hot']>0)?$value2['funnel_open_opp_hot']:0;
                                                                                    $warm3=(@$value2['funnel_open_opp_warm']>0)?$value2['funnel_open_opp_warm']:0;
                                                                                    $cold3=(@$value2['funnel_open_opp_cold']>0)?$value2['funnel_open_opp_cold']:0;
                                                                                ?>
                                                                                    <tr class="asset_row">
                                                                                        <td class="text-center"><?php echo get_pro_name_by_id($k4); ?></td>
                                                                                        <!-- <td class="text-center"><?php echo $value2['previous_target']; ?></td> -->
                                                                                        <td class="text-center"><?php echo $backlog; ?></td>
                                                                                       <!--  <td class="text-center"><?php echo $value2['previous_sales']; ?></td> -->
                                                                                        <td class="text-center"><?php  if(@$value2['current_target']==''){echo 0;} else{ echo $value2['current_target']; } ?></td>
                                                                                        <td class="text-center"><?php echo $cumm_target; ?></td>
                                                                                        <td class="text-center"><?php  if(@$value2['current_sales']==''){echo 0;} else{ echo $value2['current_sales']; } ?></td>
                                                                                        <td class="text-center"><?php  if(@$value2['open_orders']==''){echo 0;} else{ echo $value2['open_orders']; } ?></td>
                                                                                        <td class="text-center"><?php echo @$pending; ?></td>
                                                                                        <td class="text-center"><?php echo 'Hot :'.$hot3.', Warm :'.$warm3.', Cold :'.$cold3; ?></td>
                                                                                    </tr><?php
                                                                                }
                                                                            } ?>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>

                                                    <?php }
                                                    
                                                } ?>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr><?php
                                        }
                                        else
                                        { ?>
                                            <tr><td colspan="11" class="text-center"><span class="label label-primary">- No Tools Found -</span></td></tr>
                                        <?php 
                                        }
                                    }
                                }
                                } else {?>
                                    <tr><td colspan="11" align="center"><span class="label label-primary">No Records Found</span></td></tr>
                        <?php   } ?>
                            </tbody>
                        </table>
                    </div>
                    <?php }
                    else if($searchParams['groups']==2){ ?>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center"><strong>Region</strong></th>
                                   <!--  <th class="text-center"><strong>Previous Target</strong></th> -->
                                    <th class="text-center"><strong>Backlog</strong></th>
                                   <!--  <th class="text-center"><strong>Previous Sales</strong></th> -->
                                    <th class="text-center"><strong>Current Target</strong></th>
                                    <th class="text-center"><strong>Cumm Target</strong></th>
                                    <th class="text-center"><strong>Current Sales</strong></th>
                                    <th class="text-center"><strong>Open Orders</strong></th>
                                    <th class="text-center"><strong>Pending</strong></th>
                                    <th class="text-center"><strong>Funnel Opp</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if(count($table_data)>0)
                                {
                                    foreach($table_data as $row)
                                    {
                                        if($row['backlog'] >0 ||  $row['current_target']>0 ||  $row['cumm_target']>0 || $row['current_sales']>0 || $row['open_orders']>0 || $row['hot']>0 || $row['warm']>0 || $row['cold']>0 || $row['pending']>0 ) {
                                    ?>
                                        <tr>
                                            <td class="text-center"><?php echo $row['region_name']; ?></td>
                                            <!-- <td class="text-center"><?php echo $row['previous_target']; ?></td> -->
                                            <td class="text-center"><?php echo $row['backlog']; ?></td>
                                           <!--  <td class="text-center"><?php echo $row['previous_sales']; ?></td> -->
                                            <td class="text-center"><?php echo $row['current_target']; ?></td>
                                            <td class="text-center"><?php echo $row['cumm_target']; ?></td>
                                            <td class="text-center"><?php echo $row['current_sales']; ?></td>
                                            <td class="text-center"><?php echo $row['open_orders']; ?></td>
                                            <td class="text-center"><?php echo $row['pending']; ?></td>
                                            <td class="text-center"><?php echo 'Hot :'.$row['hot'].', Warm :'.$row['warm'].', Cold :'.$row['cold']; ?></td>
                                        </tr>
                            <?php   }
                                    }
                                } else {?>
                                    <tr><td colspan="6" align="center"><span class="label label-primary">No Records</span></td></tr>
                        <?php   } ?>
                            </tbody>
                        </table>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>              
    </div>
</div>
<!-- <b style="color:red;">▼ -20</b> -->
<!-- <b style="color:green;">▲ 20</b> -->

<?php $this->load->view('commons/main_footer.php', $nestedView); ?>
<script type="text/javascript">
    filterDuration();
    var ASSET_URL = "<?php echo assets_url();?>";
    $(document).on('click','.view_page',function(){
        $("#pcont").css("opacity",0.5);
        $("#loaderID").css("opacity",1);
        $.ajax({
        context: document.body,
        success: function(s){
          window.location.href = SITE_URL+'target_vs_sales_report';
        }
      });

    });
   /* $('.timeline').click(function(){

    var tl = $(this).attr('data-id');
    vtime = tl;
    $('#date_from').val('');
    $('#date_to').val('');
    $('.timeline').addClass('btn-default').removeClass('btn-success');
    $(this).addClass('btn-success').removeClass('btn-default');
    //var measure=$('.measure:checked').val();
    filterDuration();
    //filterReport();
});*/
/*$(document).on('change','.regions',function(){

    $('.levels').addClass('hidden');
    $('.tab').addClass('hidden');
    $('.orders').html('');
    $('.slider').show();
    var region=$('.regions').val();
    var data='&region='+region;
    $.ajax({
        url: SITE_URL + 'dependent_users',
        type:"POST",
        data:data,
        success: function(data){
            $('.users').html('');
            $('.users').html(data);
            
        }
    });
  //  filterReport();
});*/
function filterDuration()
{   
    var dur = $('.dur').val();
    var tl = $('.btn-success').attr('data-id');
    var data='vtime='+tl+'&dur='+dur;
     $.ajax({
        url: SITE_URL+'get_filter_duration_table',
        type :"POST",
        data:data,
        success:function(data){
          //  alert(data);
          if(tl=='y')
           {
              $('.duration').addClass('hidden');
              $('.duration').html('');
           }
           else
           {  
              $('.duration').removeClass('hidden');
              $('.duration').html(data);
           }
           //$('.submit_frm').submit();
           //filterReport();
          }
     });
     
}

    $(document).on('click','.measure, .groups',function(){
        $("#pcont").css("opacity",0.5);
        $("#loaderID").css("opacity",1);
        $('.submit_frm').submit();
    });
    $(document).on('click','.timeline',function(){
        $("#pcont").css("opacity",0.5);
        $("#loaderID").css("opacity",1);
        $('#date_from').val('');
        $('#date_to').val('');
        var dur= $('.dur').val('');
        timeline = $(this).attr('data-id');
        if(timeline!='')
        {
            $('.time').val(timeline);
        }
        $('.submit_frm').submit();
    });

    $(document).on('change','.users,.regions,.duration',function(){
        $("#pcont").css("opacity",0.5);
        $("#loaderID").css("opacity",1);
        $('.submit_frm').submit();
    });

    $("#date_from").datepicker({
        dateFormat: "yy-mm-dd",
        changeMonth: true,
        changeYear: true,
       // minDate: 0,
        onSelect: function (date) {
           
            var date2 = $(this).datepicker('getDate');
            $('#date_to').datepicker('option', 'minDate', date2);
            customDateChangeEvent();
        }
    });

    $("#date_to").datepicker({
        dateFormat: "yy-mm-dd",
        changeMonth: true,
        changeYear: true,
        onSelect: function (date) {
           
            var date2 = $(this).datepicker('getDate');
            $('#date_from').datepicker('option', 'maxDate', date2);
            customDateChangeEvent();
                            
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


