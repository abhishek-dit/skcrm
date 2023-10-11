<?php $this->load->view('commons/main_template', $nestedView);
//echo 'prasad';exit;
echo $this->session->flashdata('response'); 
$role_id = $this->session->userdata('role_id');

//print_r(@$searchParams['product_id']); die();

?>

<div class="row"> 
  <div class="col-sm-12 col-md-12">
    <div class="block-flat">
      <div class="content">
        <div class="header">
          
         
          <form class="form-horizontal" role="form" action="<?php echo SITE_URL.'otr_commission_report';?>" method="post">
            <div class="row">
              <div class="form-group">
                <div class="col-sm-12">
                  <div class="col-sm-3">
                    <input type="text" name="customer_name" value="<?php echo @$searchParams['customer_name'];?>" title="Customer Name" id="customer_name" class="form-control" placeholder="Customer Name" maxlength="100">
                  </div>  
                  <div class="col-sm-4">
                    <input type="text" name="product_name" value="<?php echo @$searchParams['product_name'];?>" id="product_name" class="form-control" placeholder="Product">
                  </div>  
                  <div class="col-sm-2">
                    <input type="text" name="so_number" value="<?php echo @$searchParams['so_number'];?>" id="so_number" class="form-control" placeholder="SO Number">
                  </div>  
                  <div class="col-sm-3">
                    <select name="invoice_status" class="form-control">
                      <option value="">Select Cnote Status</option>
                        <?php foreach(cnote_status_array() as $key =>$value)
                        {
                          $selected='';
                          if($searchParams['invoice_status']==$key)$selected='selected';
                          echo '<option value="'.$key.'"'.$selected.'>'.$value.'</option>';
                        } ?>
                      
                      </select>
                  </div>
                  
                </div>
              </div>

              <div class="form-group">
                <div class="col-sm-12">
                <?php if($this->session->userdata('role_id')!=5) { ?>
                  <div class="col-sm-3">
                    <input type="text" name="distributor_name" value="<?php echo @$searchParams['distributor_name'];?>" title="Distributor Name" id="distributor_name" class="form-control" placeholder="Distributor Name" maxlength="100">
                  </div>
                  <?php } ?>
                    <div class="col-sm-2">
                            <input type="text" required class="form-control" id="start_date" placeholder="Cnote created Start Date" name="start_date" readonly  title="Select Cnote Created Date"   value="<?php echo @$searchParams['start_date']; ?>">
                    </div>     
                    <div class="col-sm-2">
                            <input type="text" required class="form-control" id="end_date" placeholder="Cnote created End Date" name="end_date" readonly title="Select Cnote End Date" value="<?php echo @$searchParams['end_date']; ?>">
                    </div>
                    <div class="col-sm-2">
                    <select name="payment_status" class="form-control" title="Payment Status">
                      <option value="">All</option>
                      <option value="1" <?php if($searchParams['payment_status']==1){echo "selected"; } ?>>Paid</option>
                      <option value="2"<?php if($searchParams['payment_status']==2){echo "selected"; } ?>>Due</option>
                      </select>
                  </div>
                     <div class="col-sm-2">
                      <button type="submit" name="search" value="1" class="btn btn-success"><i class="fa fa-search"></i></button>
                      <a href="<?php echo SITE_URL.'otr_commission_report'?>" class="btn btn-success"><i class="fa fa-refresh"></i></a>
                     </div>
                 
                 
                   
                </div>
              </div>
             </div> 
          </form> 
        </div>
         <form method="post" action="<?php echo SITE_URL.'add_dealer_payment'?>" >
          <div class="table-responsive">
            <table class="table table-bordered hover">
              <thead class="table-bordered">
                <tr>
                  <th ><input type="checkbox" id="checkAll" name="checkAll"></th>
                  <th class="text-center"><strong>Distributor Name</strong></th>
                  <th class="text-center"><strong>Customer Name</strong></th>
                  <th class="text-center"><strong>Opp Details</strong></th>
                  <th class="text-center"><strong>Cnote Date</strong></th>
                  <th class="text-center"><strong>Commission Pay</strong></th>
                  <th class="text-center"><strong>Invoice Status</strong></th>
                  <th class="text-center"><strong>SO Number</strong></th>
                  <th class="text-center"><strong>Status</strong></th>
                  <!-- <th class="text-center"><strong>Probability</strong></th>
                  <th class="text-center"><strong>Life Time(Days)</strong></th>
                  <th class="text-center"><strong>Actions</strong></th> -->
                </tr>
              </thead>
              <tbody>
              <?php
                
                if(@$total_rows>0)
                {
                  foreach($commission_results as $row1)
                  { if($row1['payment_status']==1)
                    {
                      $status='Paid';
                    }
                    else
                    {
                      $status='Due';
                    }
                  ?>
                    
                    <tr>
                    <?php if($row1['payment_status']==2)
                    { ?>
                    <td> <input type="checkbox"  class="chkAll openOrder" name="cnote_id[]" value="<?php echo @$row1['contract_note_id'];?> " ></td>
                    <?php } else { ?>
                      <td></td>
                   <?php } ?>
                    <input type="hidden" name="dealer_id[]" value="<?php echo $row1['dealer_id']; ?>" >
                     <td class="text-center" style="width:15%;"><?php echo @$row1['distributor_name'];?></td>
                     <td class="text-center" style="width:15%;"><?php echo @$row1['customer_name'];?></td>
                       <td class="text-center" style="width:15%;"><?php echo @$row1['opportunity_details'];?></td>
                       <td class="text-center" style="width:10%;"><?php echo date('d-m-Y',strtotime($row1['cnote_ctime']));?></td>
                       <td width="10%"><?php echo indian_format_price($row1['amount']);?></td>
                      <td class="text-center" style="width:10%;"><?php echo getCNoteStatus($row1['cnote_status']);?></td>
                      <td class="text-center" style="width:8%;"><?php echo @$row1['so_number'];?></td> 
                      <td class="text-center" style="width:15%;"><?php echo @$status;?></td>           
                     
                     
                      
                    </tr>
              <?php }
                } else {
                  
                ?>  <tr><td colspan="9" align="center"><span class="label label-primary">No Records</span></td></tr>
            <?php   } ?>
              </tbody>
            </table>
          </div>
          <div class="row">
            <div class="form-group">
                    <div class="col-sm-offset-5 col-sm-10">
                        <button class="btn btn-primary" type="submit" name="tag_submit" value="button"><i class="<?php echo "fa fa-check";?>"></i>Mark As Paid</button>
                        <a class="btn btn-danger" href="<?php echo SITE_URL;?>otr_commission_report"><i class="fa fa-times"></i> Cancel</a>
                    </div>
            </div>
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
      </div>
    </div>        
  </div>
</div>

<?php
$this->load->view('commons/main_footer.php', $nestedView); ?>

