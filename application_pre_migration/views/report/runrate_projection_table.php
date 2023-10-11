<style type="text/css">
    table#table1 thead th{
        font-size: 11px !important;
    }
    tr:nth-child(even) {background: #fff !important}
tr:nth-child(odd) {background: #fff !important}
</style>

<style type="text/css">
    .radio-inline{ padding-left: 10px !important;}
    .radio-inline input[type="radio"]{ margin: 0px 4px 0px 0px;}
</style>
<?php $this->load->view('commons/main_template', $nestedView); ?>
<div class="cl-mcont">
    <div id="loaderID" style="position:fixed; top:50%; left:58%; z-index:2; opacity:0"><img src="<?php echo assets_url(); ?>images/ajax-loading-img.gif" /></div>
    <div class="row"> 
        <div class="col-sm-12 col-md-12">
            <div class="block-flat">
                <table class="table table-bordered"></table>
                <div class="content">
                    <form method="post" action="<?php echo SITE_URL;?>rr_pro_table" class="submit_frm form-horizontal" role="form">
                        <input type="hidden" name="search" value="1">
                        <input type='hidden' name="timeline" class="time" value="<?php if(@$searchParams['timeline']!='') { echo $searchParams['timeline']; } ?>">
                        <div class="row">
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="col-sm-1">
                                    </div>
                                    <?php  if(in_array($this->session->userdata('role_id'),margin_allowed_roles())) { ?>  
                                    <div class="col-sm-2">
                                        <select class="form-control region select2" style="width:100%" name="region">
                                            <option value="">Select Region</option>
                                            <?php
                                            foreach ($region as $reg) {
                                                $selected='';
                                                if($reg['location_id']==$searchFilters['region'])
                                                {
                                                    $selected='selected';
                                                }
                                                else
                                                {
                                                    $selected='';
                                                }
                                                echo '<option value="'.$reg['location_id'].'"'.$selected.'>'.$reg['location'].'</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <?php }
                                    else
                                    { ?>
                                        <input type="hidden" class="region" name="region" value="">
                                 <?php   } 
                                    if(count($users)>1) { ?>
                                    <div class="col-sm-2">
                                        <select class="form-control users select2" style="width:100%" name="users">
                                            <option value="">Select Users</option>
                                            <?php
                                            foreach ($users as $us) {
                                                 $selected='';
                                                if($us['user_id']==$searchFilters['users'])
                                                {
                                                    $selected='selected';
                                                }
                                                else
                                                {
                                                    $selected='';
                                                }
                                                echo '<option value="'.$us['user_id'].'" '.$selected.'>'.$us['first_name'].' ('.$us['employee_id'].')'.'</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                     <?php } else
                                    { ?>
                                        <input type="hidden" class="users" name="users" value="">
                                 <?php   } ?>
                                    <div class="col-sm-2">
                                        <input type="number" name="range" class="form-control range" placeholder="Custom Rate" value="<?php echo @$searchFilters['range'];?>">
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
                                <div class="col-sm-12">
                                    <div class="col-sm-1">
                                    </div>
                                    
                                      <div class="col-sm-2">
                                        <select class="form-control category_id select2" style="width:100%" name="category_id">
                                            <option value="">Select Category</option>
                                            <?php
                                            foreach ($product_category as $pc) {
                                                $selected='';
                                                if($pc['category_id']==$searchFilters['category_id'])
                                                {
                                                    $selected='selected';
                                                }
                                                else
                                                {
                                                    $selected='';
                                                }
                                                echo '<option value="'.$pc['category_id'].'"'.$selected.'>'.$pc['name'].'</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <select class="form-control product_id select2" style="width:100%" name="product_id">
                                            <option value="">Select Product</option>
                                            <?php
                                            foreach ($products as $pc) {
                                                $selected='';
                                                if($pc['product_id']==@$searchFilters['product_id'])
                                                {
                                                    $selected='selected';
                                                }
                                                else
                                                {
                                                    $selected='';
                                                }
                                                echo '<option value="'.$pc['product_id'].'"'.$selected.'>'.$pc['description'].'</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                   <div class="col-sm-offset-5  col-md-2"><button formaction="<?php echo SITE_URL.'download_rr_report'?>" class="btn btn-primary" title="Download Report"><i class="fa fa-cloud-download"></i> Download</button></div>
                                </div>
                            </div>
                        </div>
                  <!--   </form> -->
                    <div class="table-responsive">
                        <div class="">
                              <table class="table table-bordered hover" id="table1">
                                <thead>
                                    <tr>
                                        <th class="text-center">Sno</th>
                                        <th class="text-center">Month</th>
                                        <th class="text-center">Funnel (Lacs)</th>
                                        <th class="text-center">Closed Won(Lacs)</th>
                                        <th class="text-center">Conversion Rate %</th>
                                        <th class="text-center">Min Conversion (<?php echo min($conversion_rate).'%'; ?>)(Lacs)</th>
                                        <th class="text-center">Max Conversion (<?php echo max($conversion_rate).'%'; ?>)(Lacs)</th>
                                        <?php if($searchFilters['range']!='') { ?>
                                        <th class="text-center">Custom Conversion (<?php echo $searchFilters['range'].'%'; ?>)(Lacs) </th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $sno=1; foreach($table_data as $row)
                                    { ?>
                                        <tr <?php if($row['color']=='green') { ?> style="background-color:#CCCC99 !important" <?php }  ?> >
                                             <td class="text-center"><?php echo $sno++; ?> </td>
                                             <td class="text-center"><?php echo $row['month_name']; ?></td>
                                             <td class="text-center"><?php echo $row['new_op_val']; ?></td>
                                             <td class="text-center"><?php echo $row['new_sale_val']; ?></td>
                                             <td class="text-center"><?php echo $row['conversion_rate']; ?></td>
                                             <?php if($row['min_con_val']!='') { ?>
                                             <td class="text-center"><?php echo $row['min_con_val']; ?></td>
                                             <?php } else { ?>
                                             <td>--</td>
                                             <?php } ?>
                                            <?php if($row['max_con_val']!='') { ?>
                                             <td class="text-center"><?php echo $row['max_con_val']; ?></td>
                                             <?php } else { ?>
                                             <td>--</td>
                                             <?php } ?>
                                            <?php if($row['cus_con_val']!='') { ?>
                                             <td class="text-center"><?php echo $row['cus_con_val']; ?></td>
                                            <?php } else if($row['cus_con_val']==''&& $searchFilters['range']!='') { ?>
                                            <td>--</td>
                                            <?php } ?>
                                           </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                  </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('commons/main_footer.php', $nestedView); ?>
<script type="text/javascript">
    $(document).on('click','.view_page',function(){
    $("#pcont").css("opacity",0.5);
    $("#loaderID").css("opacity",1);
    $.ajax({
    context: document.body,
    success: function(s){
      window.location.href = SITE_URL+'run_rate';
    }
  });

});

$(document).on('change','.duration,.users,.region,.category_id,.product_id',function(){
     $('.levels').addClass('hidden');
    //$('.span').addClass('hidden');
    $('.slider').show();
    var duration=$('.duration').val();
  
    var users=$('.users').val();
    $('.submit_frm').submit();
});
$(document).on('blur','.range',function(){
    var range = $(this).val();
    $('.submit_frm').submit();
});

    </script>