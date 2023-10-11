<?php $this->load->view('commons/main_template', $nestedView);
echo $this->session->flashdata('response'); 
$encode_lead_id = @icrm_encode($lead_id);
$role_id=$this->session->userdata('role_id');
?>

<div class="row wizard-row">
	<div class="col-md-12 fuelux">
		<div class="block-wizard">
            <div class="step-content"> 
                <form action="<?php echo $form_action;?>" method="post">
                    <input type="hidden" name="status" value="<?php echo $status;?>">
                    <div class="col-sm-12" style="margin-bottom:10px;">
                        
                        <div class="col-sm-2">
                            <input type="text" name="contract_note_id" placeholder="Cnote ID" maxlength="20"  value="<?php echo @$searchParams['contract_note_id']; ?>" id="companyName" class="form-control">
                        </div>
                        <div class="col-sm-2">
                            <?php $cnote_types = getCNoteTypeList(); ?>
                            <select name="cnote_type" class="form-control" title="C-Note Type">
                                <option value="">C-Note Type</option>
                                <?php
                                foreach ($cnote_types as $cnote_type => $label) {
                                   $selected = ($cnote_type==@$searchParams['cnote_type'])?'selected':'';
                                   echo '<option value="'.$cnote_type.'" '.$selected.'>'.$label.'</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" name="billing_party" placeholder="Customer Name" title="Customer Name" maxlength="60"  value="<?php echo @$searchParams['billing_party']; ?>" id="companyName" class="form-control">
                        </div>
                        <div class="col-sm-5">
                            <button type="submit" name="search" value="1" class="btn btn-success"><i class="fa fa-search"></i></button>
                            <a class="btn btn-primary" href="<?php echo $form_action;?>"><i class="fa fa-refresh"></i></a>
                            <button class="btn btn-primary" type="submit" formaction="<?php echo SITE_URL;?>download_soEntry" name="download_so" value="1"><i class="fa fa-cloud-download"></i> </button>
                            <?php
                            if($status==1) {
                            ?>
                            <button class="btn btn-primary md-trigger" data-modal="bulkUploadSOEntry" type="button" value="1"><i class="fa fa-cloud-upload"></i> Bulk Upload</button>
                            
                            <button style="float:right;" class="btn btn-primary" type="submit" formaction="<?php echo SITE_URL;?>insert_soEntry" name="insert_so" value="1">Submit</button>
                            <?php
                            }
                            ?>
                        </div>  
                        
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered hover">
                            <thead>
                                <tr>
                                    <th class="text-center" width="8%"><strong>C-Note ID</strong></th>
                                    <th class="text-center" width="12%"><strong>C-Note Type</strong></th>
                                    <th class="text-center" width="25%"><strong>Customer Name</strong></th>
                                    <th class="text-center" width="18%"><strong>Sales Engineer</strong></th>
                                    <th class="text-center" width="10%"><strong>C-Note Date</strong></th>
                                    <th class="text-center" width='12%'><strong>Quote Ref ID</strong></th>
                                    <th class="text-center" width="10%"><strong>SO Number</strong></th>
                                    <th class="text-center" width="10%"><strong>CNote Info</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                //@$inc = $start + 1;
                                if (!empty($cNoteDetails)) {

                                    foreach (@$cNoteDetails as $row) 
                                    {
                                        $cnote_type = $row['cnote_type'];
                                        if($cnote_type==1)
                                        {
                                            $cNoteQuotes = getCNoteQuotes(@$row['contract_note_id']);
                                            $quotesCount = $cNoteQuotes['count'];
                                            $quotesInfo = $cNoteQuotes['resArr'];
                                            ?>
                                                <?php
                                                $j = 0;
                                                foreach($quotesInfo as $row1)
                                                {
                                                    ?>
                                                    <tr>
                                                        <?php if($j == 0)
                                                        { ?>
                                                        <td rowspan="<?php echo $quotesCount; ?>"  class="text-center"><?php echo @$row['contract_note_id'] ?></td>
                                                        <td rowspan="<?php echo $quotesCount; ?>"  class="text-center" align='cneter'><?php echo getCNoteTypeLable($row['cnote_type']); ?></td>
                                                        <td rowspan="<?php echo $quotesCount; ?>" align="left"><?php echo @$row['customer_name'] ?></td>
                                                        <td rowspan="<?php echo $quotesCount; ?>"  align="left"><?php echo @$row['lead_owner_name'] ?></td>
                                                        <td rowspan="<?php echo $quotesCount; ?>"  align="left"><?php echo format_date(@$row['created_time'],'d M Y'); ?></td>
                                                        <?php } ?>
                                                        <td class="text-center"><?php echo getQuoteReferenceID($row['lead_id'], @$row1['quote_id']); ?></td>
                                                        <?php if($j == 0)
                                                        { ?>
                                                        <td class="text-center" align='center'>
                                                            <?php 
                                                           // echo @$row['created_by'].'--'.$this->session->userdata('user_id');
                                                            if(@$row['status']==1&&((@$row['created_by']==$this->session->userdata('user_id'))||(@$this->session->userdata('role_id')==2)||(@$this->session->userdata('role_id')==14))){
                                                                echo '<input type="hidden" name="contratct_note_id[]" value="'.@$row['contract_note_id'].'">';
                                                                echo '<input type="tex'.$this->session->userdata('user_id').'t" name="so_number'.@$row['contract_note_id'].'" class="form-control">';
                                                            }
                                                            else{
                                                            echo @$row['so_number'];
                                                            } ?>
                                                        </td>
                                                        <td rowspan="<?php echo $quotesCount; ?>" class="text-center">
                                                           <a href="<?php echo SITE_URL; ?>contractPdf/<?php echo @icrm_encode($row['contract_note_id']); ?>" style="padding:3px 3px;" title="Contract Note Download"><button type='button' class="btn btn-primary" style="padding: 3px;" ><i class="fa fa-cloud-download"></i></button></a>

                                                        </td>
                                                        <?php } ?>
                                                    </tr>
                                                    <?php
                                                $j++;
                                                }
                                                ?>
                                            <?php
                                        }
                                        else
                                        {
                                            ?>
                                            <tr>
                                                <td  class="text-center"><?php echo @$row['contract_note_id'] ?></td>
                                                <td class="text-center" align='cneter'><?php echo getCNoteTypeLable($row['cnote_type']); ?></td>
                                                <td align="left"><?php echo @$row['customer_name'] ?></td>
                                                <td  align="left"><?php echo @$row['lead_owner_name'] ?></td>
                                                <td  align="left"><?php echo format_date(@$row['created_time'],'d M Y'); ?></td>
                                                <td class="text-center"></td>
                                                <!-- <td class="text-center" align='center'></td> -->
                                                <!-- <td class="text-center" align='cneter'><?php echo @$row['po_number']; ?></td>
                                                <td class="text-center" align='cneter'><?php echo @$row['po_date']; ?></td>
                                                <td class="text-center" align='cneter'><?php echo @$row['so_number']; ?></td> -->
                                                <td class="text-center" align='center'>
                                                    <?php 
                                                   // echo @$row['created_by'].'--'.$this->session->userdata('user_id');
                                                    if(@$row['status']==1&&((@$row['created_by']==$this->session->userdata('user_id'))||(@$this->session->userdata('role_id')==2)||(@$this->session->userdata('role_id')==14))){
                                                        echo '<input type="hidden" name="contratct_note_id[]" value="'.@$row['contract_note_id'].'">';
                                                        echo '<input type="tex'.$this->session->userdata('user_id').'t" name="so_number'.@$row['contract_note_id'].'" class="form-control">';
                                                    }
                                                    else{
                                                    echo @$row['so_number'];
                                                    } ?>
                                                </td>
                                                <td class="text-center">
                                                   <a href="<?php echo SITE_URL; ?>contractPdf/<?php echo @icrm_encode($row['contract_note_id']); ?>" style="padding:3px 3px;" title="Contract Note Download"><button type='button' class="btn btn-primary" style="padding: 3px;" ><i class="fa fa-cloud-download"></i></button></a>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                } else {
                                    ?>	<tr><td colspan="9" align="center"><span class="label label-primary">No Records</span></td></tr>
                                <?php } ?>
                                
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="pull-left"><?php echo @$pagermessage; ?></div>
                            <div class="pull-right">
                                <div class="dataTables_paginate paging_bs_normal">
                                <?php echo @$pagination_links; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php
if($status==1){
include('modals/bulkUploadSoEntry_modal.php');
?>
<div class="md-overlay"></div>
<?php 
}
$this->load->view('commons/main_footer.php', $nestedView); ?>