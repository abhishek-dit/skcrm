<?php
$this->load->view('commons/main_template', $nestedView);
echo $this->session->flashdata('response'); 
?>
<style type="text/css">
    .pagination{
        width: 100%;
    }
    .btn-file {
        position: relative;
        overflow: hidden;
    }
    .btn-file input[type=file] {
        position: absolute;
        top: 0;
        right: 0;
        min-width: 100%;
        min-height: 100%;
        font-size: 100px;
        text-align: right;
        filter: alpha(opacity=0);
        opacity: 0;
        outline: none;
        background: white;
        cursor: inherit;
        display: block;
    }
</style>

<div class="row"> 
    <div class="col-sm-12 col-md-12">
        <?php
        if(@$flag==1)
        {
            ?>
            <div class="block-flat">
                <div class="content">
                    <div class="content">
                        <form role="form" class="form-horizontal" method="post" action="<?php echo SITE_URL; ?>po_tracking" enctype="multipart/form-data">
                            <div class="row no-gutter" >
                                <div class="col-sm-12" style="margin-bottom:5px;">
                                    
                                    <div class="col-sm-2">
                                        <input type="text" title="PO ID" name="purchase_order_id" placeholder="PO ID" maxlength="20"  value="<?php echo @$searchParams['purchase_order_id']; ?>" id="companyName" class="form-control">
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" title="Product Details" name="product_details" placeholder="Product Details" maxlength="60"  value="<?php echo @$searchParams['product_details']; ?>" id="companyName" class="form-control">
                                    </div>
                                    <?php if($this->session->userdata('role_id')!=5) { ?>
                                        <div class="col-sm-2">
                                            <input type="text" title="Distributor" name="distributor_name" placeholder="Distributor" maxlength="60"  value="<?php echo @$searchParams['distributor_name']; ?>" id="companyName" class="form-control">
                                        </div>
                                    <?php } ?>
                                    <div class="col-sm-2">
                                        <?php $status_list = getPoStatusList(); ?>
                                        <select class="form-control" name="po_status">
                                            <option value="">PO Status</option>
                                            <?php
                                            foreach ($status_list as $status => $label) {
                                                $selected = ($status == @$searchParams['po_status'])?'selected':'';
                                                echo '<option value="'.$status.'" '.$selected.'>'.$label.'</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                            <button type="submit" name="searchApprveQuote" value="1" class="btn btn-success"><i class="fa fa-search"></i></button>
                                            <a href="<?php echo SITE_URL.'po_tracking'?>" class="btn btn-success"><i class="fa fa-refresh"></i></a>
                                            <?php
                                            if($this->session->userdata('role_id')==5)
                                            {
                                                ?>
                                                <button type="submit" name="upload_files" class="btn btn-success" value="1" formaction="<?php echo SITE_URL;?>insert_po_documents"><i class="fa fa-check"></i> Submit</button><?php
                                            }
                                            ?>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered hover">
                                    <thead>
                                        <tr>
                                            <th class="text-center" width="5%"><strong>S.No</strong></th>
                                            <th class="text-center" width="8%"><strong>PO ID</strong></th>
                                            <?php if($this->session->userdata('role_id')!=5) { ?>
                                                <th class="text-center" width='10%'><strong>Distributor</strong></th>
                                            <?php } ?>
                                            <th class="text-center" width='28%'><strong>Product Details</strong></th>
                                            <th class="text-center"><strong>Discount</strong></th>
                                            <th class="text-center" width="3%"><strong>Current Stage</strong></th>
                                            <th class="text-center" width="8%"><strong>Status</strong></th>
                                            <th class="text-center" width="5%"><strong>Final Approver</strong></th>
                                            <th class="text-center" width="20%"><strong>PO documents</strong></th> 
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        //@$inc = $start + 1;
                                        if (!empty($poSearch)) {

                                            foreach (@$poSearch as $purchase_order_id => $products) {
                                                $j=0;
                                                foreach ($products as $row) {
                                                    $status_format = ($row['po_status']==1)?2:1;
                                                   ?>
                                                   
                                                <tr>
                                                    <?php if($j==0) {?>
                                                    <td class="text-center" rowspan="<?php echo count($products);?>" style="vertical-align: middle;"><?php echo @$sn++; ?></td>
                                                    <td class="text-center" rowspan="<?php echo count($products);?>" style="vertical-align: middle;"><?php echo $row['purchase_order_id'];?></td>
                                                    <?php if($this->session->userdata('role_id')!=5) { ?>
                                                        <td class="text-center" rowspan="<?php echo count($products);?>" style="vertical-align: middle;"><?php echo $row['distributor_name'];?></td>
                                                    <?php } ?>
                                                    <?php } ?>
                                                    <td><?php echo @$row['product_details'] ?></td>
                                                    <td class="text-center"><?php echo round($row['discount_percentage'],2).'%';?></td>
                                                    <td class="text-center"><?php if($status_format==2) { echo getRoleShortName(@$row['approval_at']); } ?></td>
                                                    <?php 
                                                        
                                                        switch ($status_format) {
                                                            case 1: // PO Status
                                                                if($j==0) {?>
                                                                <td class="text-center" rowspan="<?php echo count($products);?>" style="vertical-align: middle;"><?php echo getPoStatusLabel(@$row['po_status']); ?></td>
                                                                <?php }
                                                            break;
                                                            case 2: // Individual product status

                                                                ?>
                                                                <td class="text-center" align='cneter'><?php echo getPoApprovalStatusLabel(@$row['approval_status']); ?></td>
                                                                <?php
                                                            break;
                                                        }
                                                    ?>
                                                    <td class="text-center"><?php if($status_format==2) { echo getRoleShortName(@$row['close_at']); } ?></td>

                                                    <?php if($j==0) { ?>
                                                    <td rowspan="<?php echo count($products);?>" style="vertical-align: middle;">
                                                    <?php
                                                    if(@$row['po_status']==2 || @$row['po_status']==4 || @$row['po_status']==5)
                                                    {
                                                        if(count($docs[$row['purchase_order_id']])>0)
                                                        {
                                                            foreach ($docs[$row['purchase_order_id']] as $key => $value) 
                                                            { ?>
                                                                <div style="margin-bottom:5px;">
                                                                <a data-container="body" data-placement="top"  data-toggle="tooltip" title="Download" href="<?php echo SITE_URL1; ?>uploads/dealer_po_documents/<?php echo $value['document_name'] ?>" class="btn blue btn-circle btn-xs" download><i class="fa fa-cloud-download"></i></a> <?php echo $value['name']; ?>
                                                                <br>
                                                                </div>
                                                            <?php }
                                                        }
                                                        else if($this->session->userdata('role_id')==5)
                                                        {
                                                            if($enable_po_upload==1)
                                                            { ?>
                                                                <input type="hidden" name="po_list[]" value="<?php echo $row['purchase_order_id']; ?>">
                                                                <input type="file" id="file_name" name="po_files[<?php echo @$row['purchase_order_id'];?>][]"  multiple  >
                                                                <p><small>(Allowed: jpg, png, gif, pdf, doc, docx, xls, xlsx)</small></p> 
                                                            <?php
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                    </td>
                                                    <?php } ?>
                                                </tr>
                                                <?php
                                                    $j++;
                                                }
                                            }
                                        } else {
                                            ?>  <tr><td colspan="9" align="center"><span class="label label-primary">No Records</span></td></tr>
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
            </div><?php
        }
        if(@$flag==2)
        {
            ?>
            <div class="row"> 
                <div class="col-sm-12 col-md-12">
                    <div class="block-flat">
                        <table class="table table-bordered"></table>
                        <div class="content">
                            <form id="bulkUploadFrm1" action="<?php echo SITE_URL.'insert_po_documents';?>" method="post"  enctype="multipart/form-data" class="form-horizontal">
                                <input type="hidden" name="po_id" value="<?php echo icrm_encode($po_id)?>">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Upload File<span class="req-fld">*</span>
                                        
                                    </label>
                                    <div class="col-sm-6">
                                        <input type="file" name="file_name" id="file_name" required  class="form-control" style="height: inherit;" >
                                        <p><small>(Allowed types: jpg, png, gif, pdf, doc, docx, xls, xlsx)</small></p>  
                                       <!--  <input type="file" name="uploadCsv" id="uploadCsv" required="" class="form-control"> -->
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-3 col-sm-1">
                                        <button class="btn btn-success" value="1" name="submit" type="submit">Submit</button>
                                    </div>
                                    <div class=" col-sm-1">
                                        <a href="<?php echo SITE_URL.'po_tracking'?>" class="btn btn-danger">Cancel</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>              
                </div>
            </div> <?php
        }?>             
    </div>
</div>

<?php $this->load->view('commons/main_footer.php', $nestedView); ?>
