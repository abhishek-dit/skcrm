<?php $this->load->view('commons/main_template', $nestedView);
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
          
         
          <form class="form-horizontal" role="form" action="<?php echo SITE_URL.'opportunityStatus'?>" method="post">
            <input type="hidden" name="check" value="<?php echo @$check;?>"> 
            <div class="row">
              <div class="form-group">
                <div class="col-sm-12">
                  <div class="col-sm-1">
                    <input type="text" name="opportunity_id" value="<?php echo @$searchParams['opportunity_id'];?>" id="opportunity_id" class="form-control" placeholder="Opp ID" maxlength="100">
                  </div>  
                   
                   <div class="col-sm-3">
                            <input type="text" required class="form-control" id="start_date" placeholder="Search Date" name="start_date" readonly  value="<?php echo @$searchParams['start_date']; ?>">
                    </div>     
                    
                  <div class="col-sm-2">
                      <button type="submit" name="searchOpenLead" value="1" class="btn btn-success"><i class="fa fa-search"></i> </button>
                      <?php if(in_array($role_id, allowed_download_roles()))
                       { ?>
                      <button style="margin-left:5px;" type="submit" name="downloadOpportunity" formaction="<?php echo SITE_URL; ?>download_allOpportunities" value="1" class="btn btn-success"><i class="fa fa-cloud-download"></i> </button>
                      <?php } ?>
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
                <th class="text-center"><strong>Value (Lakhs)</strong></th>
                 <th class="text-center"><strong>Life Time(Days)</strong></th>
                <th class="text-center"><strong>Stage</strong></th>
				        <th class="text-center"><strong>Current Stage</strong></th>
              </tr>
            </thead>
            <tbody>
            <?php
              
              if(@$total_rows>0)
              {
                foreach($searchResults as $row1)
                {
                  $cur_date = ($searchParams['start_date']!='')?$searchParams['start_date']:date('Y-m-d');
                  ?>
                  <!--<a data-toggle="tooltip" href="#" data-original-title="Default tooltip">Jeff Hanneman</a>-->
                  <tr>
                    <td class="text-center" style="width:4%;"><?php echo @$row1['opportunity_id'];?></td>
                    <td style="width:21%;"><a style="color:#000000;" data-toggle="tooltip" href="#" data-original-title="Owner : <?php //echo getUserName(@$row1['user_id']); ?>"><?php echo @$row1['lead'];?></a></td>
                    <td style="width:21%;"><a style="color:#000000;" data-toggle="tooltip" href="#" data-original-title="Created On : <?php echo @DateFormatAM($row1['oCTime']) ?>"><?php echo @$row1['product'];?></a></td>
                    <td class="text-center" style="width:3%;"><?php echo @$row1['required_quantity'];?></td>
                    <td class="text-center" style="width:9%;"><?php echo valueInLakhs(@$row1['required_quantity']*@$row1['dp'],2);?></td>  
                    <td class="text-center"><?php echo date_difference_two_days($row1['oCTime'],$cur_date.' 23:59:59');?></td>                  
                    <?php if($pageInfo == 1) { ?>
                    
          					<?php 

                    //$prev_stage = getPreviousStage(@$row1['opportunity_id'],@$searchParams['start_date']);
                    ?>
						        <td class="text-center" style="width:12%;"><?php echo @$row1['previous_status'];?></td>
                    <td class="text-center" style="width:12%;"><?php echo @$row1['stage'];?></td>
                  </tr>
            <?php }
              }
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