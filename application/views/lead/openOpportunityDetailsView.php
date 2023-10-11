<?php $this->load->view('commons/main_template', $nestedView);
echo $this->session->flashdata('response'); 
$encode_lead_id = @icrm_encode($lead_id);
$leadStatusID = getLeadStatusID($lead_id);
?>

<div class="row wizard-row">
	<div class="col-md-12 fuelux">
		<div class="block-wizard">
			<div id="wizard1" class="wizard wizard-ux">
				<?php include_once('train.php'); ?>
			</div>
      <div class="step-content"> 

			<div class="header">
				<div class="col-sm-12">
					<div class="col-sm-6">
						
					</div>
					<div align="right" class="col-sm-6">
					<?php
						if((@$lead['user_id']==@$this->session->userdata('user_id') && @$checkPage && $leadStatusID != 19) || @$this->session->userdata('user_id') == 1){
					?>
          <button data-toggle="modal" data-target="#form-primary" type="button" class="btn btn-success cancel"><i class="fa fa-plus"></i> Add Opportunity</button>
						<!--<button class="btn btn-primary btn-flat md-trigger" data-modal="form-primary"><i class="fa fa-plus"></i> Add Opportunity</button>-->
					<?php
						}
					?>
					</div>	
				</div>
			</div>
				<div class="table-responsive">
          <table class="table table-bordered hover">
            <thead class="table-bordered">
              <tr>
                <th class="text-center"><strong>ID</strong></th>
                <th class="text-center"><strong>Product</strong></th>
                <th class="text-center"><strong>Quantity</strong></th>
                <th class="text-center"><strong>Stage</strong></th>
                <th class="text-center"><strong>Category</strong></th>
                <th class="text-center"><strong>Probability</strong></th>
                <th class="text-center"><strong>Actions</strong></th>
              </tr>
            </thead>
            <tbody>
            <?php
              
              if(count(@$opportunities_results)>0)
              {
                foreach($opportunities_results as $row)
                {?>
                  <tr>
                    <td class="text-center" style="width:8%;">
                      <a style="color:#000000;" data-toggle="tooltip" href="#" data-original-title="Created On : <?php echo @DateFormatAM($row['created_time']) ?>"><?php echo @$row['opp_number'];?></a>
                    </td>
                    <td style="width:23%;">
                      <a style="color:#000000;" data-toggle="tooltip" href="#" data-original-title="Category : <?php echo @$row['category'].", Group :".@$row['group'] ?>"><?php echo @$row['name'];?></a>
                    </td>
                    <td style="width:5%;"><?php echo @$row['required_quantity'];?></td>
                    <td style="width:10%;"><?php echo @$row['stage'];?></td>
                    <td style="width:7%;"><?php echo @getOpportunityCategory(@$row['status'], @$row['expected_order_conclusion']); ?></td>
                    <td class="text-center" style="width:18%;"><?php echo @getProbabilityBar($row['opportunity_id']);?></td>
                    <td class="text-center" style="width:8%;">
                    <form action="<?php echo SITE_URL.'planDemo';?>" method="post">
                    <?php   $pop_id = 'op_modal'.$row['opportunity_id']; ?>
                      <?php $edit = (($lead['user_id'] == $this->session->userdata('user_id')) && $leadStatusID != 19)?'fa-edit (alias)':'fa-info'; 
                      // if(@$row['status'] == 7 || @$row['status'] == 8 || @$row['status'] == 6) $edit = 'fa-info';

                      // added on 01-07-2021 for distributor role
                      if(@$row['status'] == 7 || @$row['status'] == 8 || @$row['status'] == 6 || @$row['status'] == 10) $edit = 'fa-info';
                      // added on 01-07-2021 for distributor role end
                      ?>
                      <button style="padding:3px;" data-toggle="modal" data-target="#<?php echo $pop_id;?>" type="button" class="btn btn-sm btn-primary btn-flat md-trigger cancel"><i class="fa <?php echo $edit; ?>"></i></button>
                      <?php
                      // plan a demo
                      if((@$row['status']==3||@$row['status']==4||@$row['status']==5) && $leadStatusID != 19){
                        ?>
                          
                            <input type="hidden" name="lead_id" value="<?php echo $lead_id;?>"> 
                            <input type="hidden" name="opportunity_id" value="<?php echo @$row['opportunity_id'];?>">
                            <button title="Plan Demo" style="padding:3px; margin-left:10px;" type="submit" class="btn btn-sm btn-primary btn-flat"><i class="fa fa-video-camera"></i></button>
                          
                        <?php
                      }
                      ?>
                      </form>
                    </td>
                  </tr>
                  <?php //include('modals/opportunity_modal.php');?>

            <?php }
              } else {
                //$colspan = (@$checkRole)?7:8;
              ?>  <tr><td colspan="7" align="center"><span class="label label-primary">No Records</span></td></tr>
          <?php   } ?>
            </tbody>
          </table>
        </div>
			</div>
			
		</div>
	</div>
</div>				
<?php 
if(count(@$opportunities_results)>0)
 {
    foreach($opportunities_results as $row){
    	// edit OR view popup
    $action = SITE_URL.'updateOpportunity'; $pop_id = 'op_modal'.$row['opportunity_id'];
    //print_r(getOpportunityCompetitors(@$row['opportunity_id']));exit;
		$modal_header_title = 'Edit Opportunity';
		include('modals/opportunity_modal.php');
    }
}
$action = SITE_URL.'insertOpportunity'; $pop_id = 'form-primary'; $row = array();
$modal_header_title = 'Add New Opportunity';
include('modals/opportunity_modal.php');?>

<?php 
$this->load->view('commons/main_footer.php', $nestedView); ?>
<script type="text/javascript">
 $(document).ready(function(){

      select2Ajax('select2_decision_maker', 'getDecisionMakers', <?php echo @$lead['customer_id'];?>, 0);
    });
    $(document).on('click','#reset_modal',function(){
      location.reload();
    });
</script>
