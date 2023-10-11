<?php $this->load->view('commons/main_template', $nestedView); ?>

<?php
echo $this->session->flashdata('response');
if (@$flg != '') {
    //$flg = @$this->global_functions->decode_icrm($_REQUEST['flg']);
    if ($flg == 1) {
        if ($val == 1) {
            $formHeading = 'Edit Document Details';
        } else {
            $formHeading = 'Add Document';
        }
        ?>
        <div class="row"> 
            <div class="col-sm-12 col-md-12">
                <div class="block-flat">
                    <div class="header">                            
                        <h4><?php echo $formHeading; ?></h4>
                    </div>
                    <div class="content">
                        <form class="form-horizontal" role="form" action="<?php echo SITE_URL; ?>campaignDocumentsAdd" parsley-validate  method="post" enctype="multipart/form-data">
                            <input type="hidden" name="campaign_document_id" value="<?php echo @$this->global_functions->encode_icrm(@$campaign_document_data[0]['campaign_document_id']); ?>">

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Roles<span class="req-fld">*</span></label>
                                <div class="col-sm-6">
                                    <?php
                                   
                                   // print_r(@$campaign_document_data);
                                    $attrs = ' required class="select2" multiple id="role_id" placeholder="Location"  ';
                                    @$roles=array(' '=>'Select Role')+@$roles;
                                    echo form_dropdown("role_id[]", @$roles,@$campaign_roles_data, @$attrs, 'multiple');
                                    ?>

                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Name<span class="req-fld">*</span></label>
                                <div class="col-sm-6">
                                    <input type="text" required  maxlength="100"  class="form-control" id="name" value="<?php echo @$campaign_document_data[0]['name']; ?>"  name="name" placeholder="Name" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Description</label>
                                <div class="col-sm-6">
                                    <input type="text"   maxlength="225"  class="form-control" id="description" value="<?php echo @$campaign_document_data[0]['description']; ?>"  name="description" placeholder="description" >
                                </div>
                            </div>
                           <?php if(@$campaign_document_data[0]['campaign_document_id']==NULL){?>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Attachments<span class="req-fld">*</span></label>
                                <div class="col-sm-6">
                                    <!--                                    <input type="text" required  maxlength="100"  class="form-control ckeditor" id="mail_content" value="<?php //echo @$campaign_document_data[0]['mail_content']; ?>"  name="mail_content" placeholder="Mail content" >-->
                                    <input type="file" name="file_name" id="file_name" required  class="form-control" style="height: inherit;" >
                                    <small>(Allowed types: jpg, png, gif, pdf, doc, docx, xls, xlsx Max size:4MB)</small>
                                </div>
                            </div>
                           <?php }?>    
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-10">
                                    <button class="btn btn-primary" type="submit" name="submitCampaignDocument" value="button"><i class="fa fa-check"></i> Submit</button>
                                    <a class="btn btn-danger" href="<?php echo SITE_URL; ?>campaignDocuments"><i class="fa fa-times"></i> Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>              
            </div>
        </div><br>

        <?php
        // break;
    }
}

?>
<?php
if (@$displayList == 1) {
    ?>

    <div class="row"> 
        <div class="col-sm-12 col-md-12">
            <div class="block-flat">
                <table class="table table-bordered"></table>
                <div class="content">
                    <form role="form" class="form-horizontal" method="post" action="<?php echo SITE_URL; ?>campaignDocuments">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">    
                                    <label class="col-sm-2 control-label">Name</label>
                                    <div class="col-sm-2">
                                        <input type="text" name="campaignDocumentName" placeholder="Document Name" maxlength="100"  value="<?php echo @$searchParams['campaignDocumentName']; ?>" id="companyName" class="form-control">
                                    </div>
                                 <div class="col-sm-2">
                                        <button type="submit" name="searchCampaignDocument" title="Search" value="1" class="btn btn-success"><i class="fa fa-search"></i> </button>
                                 
                                        <a href="<?php echo SITE_URL; ?>addCampaignDocuments" title="Add New" class="btn btn-success"><i class="fa fa-plus"></i> </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                    <div class="header"></div>
                    <div class="table-responsive">
                        <table class="table table-bordered hover">
                            <thead>
                                <tr>
                                    <th class="text-center"><strong>S.NO</strong></th>
                                    <th class="text-center"><strong>Document Name</strong></th>
                                    <th class="text-center"><strong>Description </strong></th>
                                    <th class="text-center"><strong>Document Access </strong></th>
                                    <th class="text-center"><strong>Attachment</strong></th>
                                    <th class="text-center"><strong>Actions</strong></th>    
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                //@$inc = $start + 1;
                                if (@$count > 0) {
                                    foreach ($campaignDocumentSearch as $row) {
                                        ?>
                                        <tr>
                                            <td class="text-center"><?php echo @$sn++; //@$sn      ?></td>
                                            <td class="text-center"><?php echo @$row['name']; ?></td>
                                            <td class="text-center"><?php echo @$row['description']; ?></td>
                                            <td class="text-center"><?php echo @$row['roles']; ?></td>
                                            <td class="text-center"> 
                                                <a target="_blank" href="<?php echo SITE_URL1; ?>uploads/campaign_documents/<?php echo @$row['path'];?>"  style="padding:3px 3px;" class="btn btn-default"><i class="fa fa-paperclip" aria-hidden="true"></i></a>
                                                                    
                                            </td>
                                            

                                            <td class="text-center">
                                                <a class="btn btn-default" style="padding:3px 3px;" href="<?php echo SITE_URL; ?>editCampaignDocuments/<?php echo @icrm_encode($row['campaign_document_id']); ?>"><i class="fa fa-pencil"></i></a> 
                                                <?php
                                                if (@$row['status'] == 1) {
                                                    ?>
                                                    <a class="btn btn-danger" style="padding:3px 3px;" href="<?php echo SITE_URL; ?>deleteCampaignDocuments/<?php echo @icrm_encode($row['campaign_document_id']); ?>" onclick="return confirm('Are you sure you want to Delete?')"><i class="fa fa-trash-o"></i></a>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <a class="btn btn-info" title="Activate" style="padding:3px 3px;" href="<?php echo SITE_URL; ?>activateCampaignDocuments/<?php echo @icrm_encode($row['campaign_document_id']); ?>"  onclick="return confirm('Are you sure you want to Activate?')"><i class="fa fa-check"></i></a>
                                                    <?php
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    ?>  <tr><td colspan="8" align="center"><span class="label label-primary">No Records</span></td></tr>
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
                </div>              
            </div>
        </div>
    <?php } ?>
    <?php $this->load->view('commons/main_footer.php', $nestedView); ?>
