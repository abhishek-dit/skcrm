<?php $this->load->view('commons/main_template', $nestedView);
echo $this->session->flashdata('response'); 
?>
<div class="row ">
	<div class="col-md-12 col-sm-12 ">
		<div class="block-flat">
			<div class ="content">
            <div class="row no-gutter " >
                        <form role="form" class="form-horizontal" method="post" action="<?php echo SITE_URL; ?>contract_note_approval_list">
                            <div class="col-sm-12" style="margin-bottom:5px;">
                                <div class="col-sm-2">
                                    <input type="text" name="contract_note_id" title="C-Note ID" placeholder="C-Note ID" maxlength="20"  value="<?php echo @$searchParams['contract_note_id']; ?>" id="companyName" class="form-control">
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
                                 <div class="col-sm-2">
                                    <button type="submit" name="search" value="1" class="btn btn-success"><i class="fa fa-search"></i></button>
                                    <a href="<?php echo SITE_URL.'contract_note_approval_list'?>" class="btn btn-success"><i class="fa fa-refresh"></i></a>
                                 </div>  
                            </div>
                            </div>
                        </form>
                    </div>
            <div class="row"> 
                <form method ="post" action="<?php echo SITE_URL.'cNote_approval';?>" >
                  <div class="table-responsive">
                        <table class="table table-bordered hover">
                            <thead>
                                <tr>
                                    <th class="text-center" width="8%"><strong>C Note ID</strong></th>
                                    <th class="text-center" width='10%'><strong>C-Note Type</strong></th>
                                    <th class="text-center" width='20%'><strong>Customer Name</strong></th>
                                    <th class="text-center" width='8%'><strong>Created On</strong></th>
                                    <th class="text-center" width="10%"><strong>PO Number</strong></th>
                                    <th class="text-center" width="10%"><strong>PO Date</strong></th>
                                     <th class="text-center" width="5%"><strong>View</strong></th>
                                    <th class="text-center" width="5%"><strong>Actions</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                //@$inc = $start + 1;
                                if (!empty($searchResults)) {
                                    foreach (@$searchResults as $row) 
                                    {
                                        ?>
                                            <tr>
                                                <td  class="text-center"><?php echo @$row['contract_note_id'] ?></td>
                                                <td class="text-center" align='cneter'><?php echo getCNoteTypeLable($row['cnote_type']); ?></td>
                                                <td class="text-center" align='cneter'><?php echo @$row['customer_name']; ?></td>
                                                <td class="text-center" align='cneter'><?php echo format_date(@$row['created_time'],'d M Y'); ?></td>
                                                <td class="text-center" align='cneter'><?php echo @$row['po_number']; ?></td>
                                                <td class="text-center" align='cneter'><?php echo format_date(@$row['po_date'],'d M Y'); ?></td>
                                                <td class="text-center">
                                                    <!-- <a target="_blank" href="<?php echo SITE_URL; ?>view_contract_note_pdf/<?php echo @icrm_encode($row['contract_note_id']); ?>" style="padding:3px 3px;" title="Contract Note View"><button type='button' class="btn btn-primary"  style="padding: 3px;" ><i class="fa  fa-building-o"></i></button></a> -->
                                                    <a href="<?php echo SITE_URL; ?>contractPdf/<?php echo @icrm_encode($row['contract_note_id']); ?>" style="padding:3px 3px;" title="Contract Note Download"><button type='button' class="btn btn-primary" style="padding: 3px;" ><i class="fa fa-cloud-download"></i></button></a>
                                                </td>
                                                <?php $id=$row['contract_note_id'].'_'.$row['cnote_type'];?>
                                                <td class="text-center">
                                                     <button type="submit" class="btn btn-primary btn-flat" style="padding:3px 3px;" formaction="<?php echo  SITE_URL; ?>cNote_approval/<?php echo @icrm_encode($id);?>" name="approve" style="padding: 3px;" value="approve" ><i class="fa fa fa-thumbs-o-up"></i></button>
                                                 </td>
                                            </tr>
                                        <?php
                                    }
                                } else {
                                    ?>	<tr><td colspan="8" align="center"><span class="label label-primary">No Records</span></td></tr>
                                <?php } ?>
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
                </form>
            </div>
            </div>
        </div>
    </div>
</div>


<?php 
$this->load->view('commons/main_footer.php', $nestedView); ?>
