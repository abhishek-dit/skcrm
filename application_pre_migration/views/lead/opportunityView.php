<?php $this->load->view('commons/main_template', $nestedView);
//echo 'prasad';exit;
echo $this->session->flashdata('response'); 
$role_id = $this->session->userdata('role_id');
$checkRole = ($role_id == 4 || $role_id == 5)?1:0;
//print_r(@$searchParams['product_id']); die();

?>

<div class="row"> 
  <div class="col-sm-12 col-md-12">
    <div class="block-flat">
      <div class="content">
        <div class="header">
          
         
          <form class="form-horizontal" role="form" action="<?php echo $form_action?>" method="post">
            <input type="hidden" name="check" value="<?php echo @$check;?>"> 
            <div class="row">
              <div class="form-group">
                <div class="col-sm-12">
                  <div class="col-sm-1">
                    <input type="text" name="opportunity_id" value="<?php echo @$searchParams['opportunity_id'];?>" title="Enter Opp Id" id="opportunity_id" class="form-control" placeholder="Opp ID" maxlength="100">
                  </div>  
                  <div class="col-sm-3">
                    <select class="checkCustomer" style="width:100%" name="customer">
                      <option value="<?php echo $s_cus['customer_id']; ?>"><?php echo $s_cus['customer']; ?></option>
                    </select>
                  </div>  
                  <div class="col-sm-3">
                    <select class="test-category form-control"  title="select Category"  multiple name="product_id[]">
                      <?php
                        foreach ($category_id as $key => $value) 
                        {
                          $selected = '';
                          if($key==@$searchParams['product_id'])$selected='selected';
                          $in_array = in_array($key, @$searchParams['product_id']);
                          $selected = ($in_array)?'selected="selected"': '';
                          ?>
                          <option <?php echo $selected; ?> value="<?php echo $key; ?>">
                            <?php echo $value; ?>
                          </option>
                          <?php
                        }
                      ?>
                    </select>
                      <?php
                     //echo form_dropdown('product_id', $category_id, @$searchParams['product_id'],'class="test-category form-control" multiple'); 
                     ?>
                  </div> 
                  <div class="col-sm-3">
                    <?php
                    //print_r($searchParams);
                     echo form_dropdown('opp_status', $opp_status, @$searchParams['opp_status'],'class="select2"'); ?>
                  </div>
                  <?php if($pageInfo == 1) { ?>
                  <div class="col-sm-2">
                    <?php
                    $opp_category = array('' => 'Select Category', 1 =>'Hot', 2 => 'Warm', 3 => 'Cold'); 
                    echo form_dropdown('opp_category', $opp_category, @$searchParams['opp_category'],'class="select2"'); ?>
                  </div> 
                  <?php } ?>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-12">
                    <!-- <div class="col-sm-1">   
                    </div> -->
                    <div class="col-sm-2">
                            <input type="text" required class="form-control" id="start_date" placeholder="Opp created Start Date" name="start_date" readonly  title="Select Opp Created Date"   value="<?php echo @$searchParams['start_date']; ?>">
                    </div>     
                    <div class="col-sm-2">
                            <input type="text" required class="form-control" id="end_date" placeholder="Opp created End Date" name="end_date" readonly title="Select Opp End Date" value="<?php echo @$searchParams['end_date']; ?>">
                    </div>
                  <?php if($checkRole == 1)
                  {
                    ?>
                    <input type="hidden" name="created_user" value="<?php echo $this->session->userdata('user_id'); ?>">
                  <?php } ?>  
                  <?php if($checkRole != 1)
                  { ?>                    
                    <div class="col-sm-3">
                      <select class="getReporteesWithUser" style="width:100%" name="created_user">
                        <option value="<?php echo $s_created_user['user_id']; ?>"><?php echo $s_created_user['cName']; ?></option>
                      </select>
                    </div>  
                  <?php } ?>
                  <div class="col-sm-2">
                    <select class="select2" style="width:100%" name="region_id">
                      <option value="">Select Region</option>
                      <?php
                      foreach($regions as $srow){
                        $r_selected = ($srow['location_id']==@$searchParams['region_id'])?'selected':'';
                        echo '<option value="'.$srow['location_id'].'" '.$r_selected.'>'.$srow['location'].'</option>';
                      }
                      ?>
                    </select>
                  </div>
                   <div class="col-sm-3">
                    <select class="select2" style="width:100%" name="source_of_lead">
                      <option value="">Select Source of Lead</option>
                      <?php
                      foreach($source_of_leads as $srow){
                        $s_selected = ($srow['source_id']==@$searchParams['source_of_lead'])?'selected':'';
                        echo '<option value="'.$srow['source_id'].'" '.$s_selected.'>'.$srow['name'].'</option>';
                      }
                      ?>
                    </select>
                  </div>  
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-12">
                  <!-- <div class="col-sm-1"></div>   -->
                 
                  <div class="col-sm-2">
                            <input type="text" required class="form-control" id="order_start_date" placeholder="Order Conclusion Start Date" title="Select Order Conclusion Date" name="order_start_date" readonly  value="<?php echo @$searchParams['order_start_date']; ?>">
                    </div>     
                    <div class="col-sm-2">
                            <input type="text" required class="form-control" title="Select Order Conclusion End Date" id="order_end_date" placeholder="Order Conclusion End Date" name="order_end_date" readonly  value="<?php echo @$searchParams['order_end_date']; ?>">
                    </div>
                     <div class="col-sm-2">
                    <select class="form-control" style="width:100%" title="Select Search Filter" name="search_option">
                      <?php
                      foreach(search_types() as $key => $value){
                        $s_selected = ($key==@$searchParams['search_option'])?'selected':'';
                        echo '<option value="'.$key.'" '.$s_selected.'>'.'Search In '.$value.'</option>';
                      }
                      ?>
                    </select>
                  </div>   
                    <div class="col-sm-4">
                   <input type="text"  class="form-control" id="text_search" placeholder="Search By Text" name="text_search" title="Enter Text"   value="<?php echo @$searchParams['text_search']; ?>">
                    </div>
                    <div class="col-sm-2">
                      <button type="submit" name="searchOpenLead" value="1" class="btn btn-success"><i class="fa fa-search"></i> </button>
                      <?php if($pageInfo == 1) { ?>
                      <button data-toggle="modal" data-target="#form-primary" type="button" class="btn btn-primary btn-flat md-trigger"><i class="fa fa-plus"></i></button>
                      <?php } ?>
                      <?php if(in_array($role_id, allowed_download_roles()))
                      { ?>
                      <button style="margin-left:5px;" type="submit" name="downloadOpportunity" formaction="<?php echo SITE_URL; ?>download_opportunities" value="1" class="btn btn-success"><i class="fa fa-cloud-download"></i> </button>
                      <?php } ?>
                       <a  class="btn btn-success" href="<?php echo $form_action;?>" data-original-title="Reset"> <i class="fa fa-refresh"></i></a>
                    </div>

          
                </div>
              </div>  
               

            </div> 
          </form> 
        </div>
        <div class="table-responsive">
          <table class="table table-bordered hover">
            <thead class="table-bordered">
              <tr>
                <th class="text-center"><strong>ID</strong></th>
                <th class="text-center"><strong>Lead Details</strong></th>
                <th class="text-center"><strong>Product</strong></th>
                <th class="text-center"><strong>Qty</strong></th>
                <th class="text-center"><strong>Value (Rs)</strong></th>
                <th class="text-center"><strong>Stage</strong></th>
                <?php if($pageInfo == 1) { ?>
                <th class="text-center"><strong>Category</strong></th>
                <th class="text-center"><strong>Probability</strong></th>
                <?php } ?>
                <th class="text-center"><strong>Life Time(Days)</strong></th>
                <th class="text-center"><strong>Actions</strong></th>
              </tr>
            </thead>
            <tbody>
            <?php
              
              if(@$total_rows>0)
              {
                foreach($searchResults as $row1)
                {?>
                  <!--<a data-toggle="tooltip" href="#" data-original-title="Default tooltip">Jeff Hanneman</a>-->
                  <tr>
                    <td class="text-center" style="width:4%;"><?php echo @$row1['opportunity_id'];?></td>
                    <td style="width:21%;"><a style="color:#000000;" data-toggle="tooltip" href="#" data-original-title="Owner : <?php echo getUserName(@$row1['user_id']); ?>"><?php echo @$row1['lead'];?></a></td>
                    <td style="width:21%;"><a style="color:#000000;" data-toggle="tooltip" href="#" data-original-title="Created On : <?php echo @DateFormatAM($row1['oCTime']) ?>"><?php echo @$row1['product'];?></a></td>
                    <td class="text-center" style="width:3%;"><?php echo @$row1['required_quantity'];?></td>
                    <td class="text-center" style="width:9%;"><?php echo valueInLakhs((@$row1['required_quantity']*@$row1['dp']));?></td>                    
                    <?php if($pageInfo == 1) { ?>
                    <td class="text-center" style="width:12%;"><?php echo @$row1['stage'];?></td>
                    <td class="text-center" style="width:8%;"><a style="color:#000000;" data-toggle="tooltip" href="#" data-original-title="Expected Order Conclusion Date: <?php echo @DateFormat($row1['oDate']); ?>"><?php echo @getOpportunityCategory(@$row1['status'], @$row1['oDate']); ?></a></td>
                    <td class="text-center" style="width:15%;"><?php echo @getProbabilityBar($row1['opportunity_id']);?></td>
                    <?php } 
                    else
                      { ?>
                    <td class="text-center" style="width:13%;"><?php echo getOpStatusBar(@$row1['status'], @$row1['stage']);?></td>
                      <?php
                      } ?>
                    <td class="text-center"><?php echo get_opp_life_time($row1['oCTime'],$row1['opp_mtime'],$row1['status']);?></td>
                    <td class="text-center" style="width:7%;">
                    <?php   $pop_id = 'op_modal'.$row1['opportunity_id']; ?>
                      <?php $edit = ($row1['user_id'] == $this->session->userdata('user_id'))?'fa-info':'fa-info'; ?>
                      <button data-toggle="modal" data-target="#<?php echo $pop_id;?>" type="button" class="btn btn-sm btn-primary btn-flat md-trigger"><i class="fa <?php echo $edit; ?>"></i>
                       
                    </td>
                  </tr>
            <?php }
              } else {
                $colspan = ($checkRole)?7:8;
              ?>  <tr><td colspan="<?php echo $colspan; ?>" align="center"><span class="label label-primary">No Records</span></td></tr>
          <?php   } ?>
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

<?php 

if(count(@$searchResults)>0)
 {
    foreach($searchResults as $row1)
    {
      $opResults = $this->Opportunity_model->getOpportunityResultsByLead($row1['opportunity_id'], 2);
      $row = $opResults[0];
      /*phase 2 changes modified by prasad on 27-07-2017 */
      $opp_lost_reasons= $this->Common_model->get_data('opportunity_lost_reasons',array('status'=>1));
      $lost_competitors = $this->Common_model->get_data('competitor',array('status'=>1));
      /* end prasad */
        // edit OR view popup
      $action = SITE_URL.'updateOpportunity'; $pop_id = 'op_modal'.$row1['opportunity_id'];
      $modal_header_title = 'Edit Opportunity';
      include('modals/oppo_modal.php');
    }
}
//print_r($_SESSION['reportees']);
$action = SITE_URL.'insertOpportunity'; $pop_id = 'form-primary'; $row = array();
$modal_header_title = 'Add New Opportunity';
include('modals/opportunity_modal.php');?>

<?php 
$this->load->view('commons/main_footer.php', $nestedView); ?>
<script type="text/javascript">

$(document).on('change',"#leads",function () { 
  var lead_id=$("#leads").val();
  //var checkRole=$("#checkRole").val();
  //alert(checkRole);
  //alert(customer_id);
//alert(lead_id);
  if(lead_id != "")
  {
    var data = 'lead_id='+lead_id;
    //alert(data);
    $.ajax({
      type:"POST",
      url:SITE_URL+'getDecisionMakerFromLead',
      data:data,
      cache:false,
      success:function(html){
        $("#decision_maker1").html(html);
        $("#decision_maker2").html(html);
        $("#decision_maker3").html(html);
        $("#decision_maker4").html(html);
        $("#decision_maker5").html(html);
      }
    });
  }
  else
  {
    $('#decision_maker1').html('<option value="">select Decision Maker</option>');
    $('#decision_maker2').html('<option value="">select Decision Maker</option>');
    $('#decision_maker3').html('<option value="">select Decision Maker</option>');
    $('#decision_maker4').html('<option value="">select Decision Maker</option>');
    $('#decision_maker5').html('<option value="">select Decision Maker</option>');
  }
});


 $(document).ready(function(){
      select2Ajax('select2_decision_maker', 'getDecisionMakers', '<?php echo @$row1['user_id'];?>', 0);
      
      $(".test-category").select2({
        placeholder: "Select Product Category"
      });      
    });
</script>
