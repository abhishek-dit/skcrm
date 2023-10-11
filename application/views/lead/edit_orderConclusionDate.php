<?php $this->load->view('commons/main_template', $nestedView);
echo $this->session->flashdata('response'); 
$role_id = $this->session->userdata('role_id');
$checkRole = ($role_id == 4 || $role_id == 5)?1:0;

?>

<div class="row"> 
  <div class="col-sm-12 col-md-12">
    <div class="block-flat">
      <div class="content">
        <form action="<?php echo SITE_URL.'update_orderConclusionDate'?>" method="post">
        <?php
        if(@count($searchResults)>0){
        ?>
          <p class="text-right"><button type="submit" name="update_details" value="1" class="btn btn-primary">Submit</button></p>
        <?php
        }
        ?>
          <div class="table-responsive">
            <table class="table table-bordered hover">
              <thead class="table-bordered">
                <tr>
                  <th class="text-center"><strong>ID</strong></th>
                  <th class="text-center"><strong>Lead Details</strong></th>
                  <th class="text-center"><strong>Product</strong></th>
                  <th class="text-center"><strong>Expected Order Conclusion Date</strong></th>
                  <th class="text-center"><strong>Actions</strong></th>
                </tr>
              </thead>
              <tbody>
              <?php
                
                if(@count($searchResults)>0)
                {
                  foreach($searchResults as $row1)
                  {?>
                    <!--<a data-toggle="tooltip" href="#" data-original-title="Default tooltip">Jeff Hanneman</a>-->
                    <tr>
                      <td class="text-center" style="width:8%;"><?php echo @$row1['opportunity_id'];?></td>
                      <td style="width:25%;"><a style="color:#000000;" data-toggle="tooltip" href="#" data-original-title="Owner : <?php echo @$row1['user']; ?>"><?php echo @$row1['lead'];?></a></td>
                      <td style="width:23%;"><a style="color:#000000;" data-toggle="tooltip" href="#" data-original-title="Created On : <?php echo @DateFormatAM($row1['oCTime']) ?>"><?php echo @$row1['product'];?></a></td>
                      <td style="width:35%">
                      <input type="hidden" name="opids[]" value="<?php echo @$row1['opportunity_id'];?>">
                      <input type="text" value="<?php echo @$row1['expected_order_conclusion'];?>" name="conclusion_date_<?php echo @$row1['opportunity_id'];?>" class="form-control datepicker"></td>
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
        </form>
        
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

$this->load->view('commons/main_footer.php', $nestedView); ?>