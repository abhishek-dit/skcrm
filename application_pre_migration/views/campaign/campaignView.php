<?php $this->load->view('commons/main_template', $nestedView); ?>
<?php
if (@$flg != '') {
    //$flg = @$this->global_functions->decode_icrm($_REQUEST['flg']);
    if ($flg == 1) {
        if ($val == 1) {
            $formHeading = 'Campaign Details';
        } else {
            $formHeading = 'Add New Campaign';
        }
        ?>
        <div class="row"> 
            <div class="col-sm-12 col-md-12">
                <div class="block-flat">
                    <div class="header">							
                        <h4><?php echo $formHeading; ?></h4>
                    </div>
                    <div class="content">
                        <?php if(@$campaign_data[0]['campaign_id']!=NULL){
                            $is_disable=' disabled="disabled" ';
                            $frm_return=' onsubmit= "return false;" ';
                          } ?>
                       
                        <form id="add_campaign_form"  class="form-horizontal" role="form" action="<?php echo SITE_URL; ?>campaignAdd"  parsley-validate novalidate method="post" enctype="multipart/form-data" <?php echo @$frm_return;?> >
                            <input type="hidden" name="role_level_id" id="role_level_id" value="7">
                            <div class="form-group">
                                <?php
                                $ck5 = 'checked';
                                $ck6 = '';
                                $mail_content_cls = 'hidden';
                                if(@$campaign_data[0]['type']==1){
                                    $ck5 = '';
                                    $ck6 = 'checked';
                                    $mail_content_cls = '';
                                }
                                ?>
                                <label class="col-sm-3  control-label">Campaign Type<span class="req-fld">*</span></label>
                                <div class="col-sm-6 custom_icheck">
                                    <label class="radio-inline"> 
                                        <div class="iradio_square-blue <?php echo $ck5;?>" style="position: relative;" aria-checked="true" aria-disabled="false">
                                            <input type="radio" class="campaign_type" value="0" name="campaign_type" <?php echo $ck5;?> style="position: absolute; opacity: 0;">
                                            <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;"></ins>
                                        </div> 
                                        Offline
                                    </label>
                                    <label class="radio-inline"> 
                                        <div class="iradio_square-blue <?php echo $ck6;?>" style="position: relative;" aria-checked="true" aria-disabled="false">
                                            <input type="radio" class="campaign_type" value="1" name="campaign_type" <?php echo $ck6;?> style="position: absolute; opacity: 0;">
                                            <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;"></ins>
                                        </div> 
                                        Mass Mailing
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3  control-label">Speciality<span class="req-fld">*</span></label>
                                <div class="col-sm-6">
                                    <?php
                                    $attrs = ' multiple required class="select2 speciality_id" id="speciality_id"  '.@$is_disable;
                                    echo form_dropdown("speciality_id[]", @$specialityInfo, @$campaign_data[0]['speciality_id'], @$attrs);
                                    ?>

                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">GEO<span class="req-fld">*</span></label>
                                <div class="col-sm-6 " id="geo_container">
                                    <select multiple="" name="geo[]" class="select2" id="geo5">
                                            <option value="">Select GEOs</option>
                                            <?php
                                            if($geos) {
                                                foreach($geos as $geo) {
                                                    echo '<option value="'.$geo['location_id'].'"> '.$geo['location'].'</option>';
                                                }
                                            }
                                            ?>
                                    </select>
                                   
                                </div>
                            </div>
                            <div class="form-group country-loading hidden">
                            	<label class="col-sm-3"></label>
                            	<label class="col-sm-4"><img src="<?php echo assets_url()?>images/loaders/loader1.gif"> loading countries</label>
                            </div>
                            <div class="form-group country-group hidden">
                                 <label class="col-sm-3 control-label">Country</label>
                                 <div class="col-sm-6" id="country_container">
                                    <select multiple="" name="country[]" class="select2" id="country5">
                                        <option value="">Select</option>
                                    </select>
                                 </div>
                            </div>
                            <div class="form-group region-loading hidden">
                            	<label class="col-sm-3"></label>
                            	<label class="col-sm-4"><img src="<?php echo assets_url()?>images/loaders/loader1.gif"> loading regions</label>
                            </div>
                            <div class="form-group region-group hidden">
                                 <label class="col-sm-3 control-label">Region</label>
                                 <div class="col-sm-6">
                                    <select name="region[]" multiple="" class="select2" id="region5">
                                        <option value="">Select</option>
                                    </select>
                                 </div>
                            </div>
                            <div class="form-group state-loading hidden">
                            	<label class="col-sm-3"></label>
                            	<label class="col-sm-4"><img src="<?php echo assets_url()?>images/loaders/loader1.gif"> loading states</label>
                            </div>
                            <div class="form-group state-group hidden">
                                 <label class="col-sm-3 control-label">State</label>
                                 <div class="col-sm-6 multiselectbox" id="state5">
                                    
                                 </div>
                            </div>
                            <div class="form-group district-loading hidden">
                            	<label class="col-sm-3"></label>
                            	<label class="col-sm-4"><img src="<?php echo assets_url()?>images/loaders/loader1.gif"> loading districts</label>
                            </div>
                            <div class="form-group district-group hidden">
                                 <label class="col-sm-3 control-label">District</label>
                                 <div class="col-sm-6 multiselectbox" id="district5">
                                    
                                 </div>
                            </div>
                            <div class="form-group city-loading hidden">
                            	<label class="col-sm-3"></label>
                            	<label class="col-sm-4"><img src="<?php echo assets_url()?>images/loaders/loader1.gif"> loading cities</label>
                            </div>
                            <div class="form-group city-group hidden">
                                 <label class="col-sm-3 control-label">City</label>
                                 <div class="col-sm-6 multiselectbox" id="city5">
                                    
                                 </div>
                            </div>
                            <div class="form-group mail_fields hidden">
                                 <label class="col-sm-3 control-label"></label>
                                 <div class="col-sm-6 text-right">
                                 	<label class="col-sm-4 contacts-loading hidden"><img src="<?php echo assets_url()?>images/loaders/loader1.gif"> loading contacts</label>
                                 	<button type="button" class="btn btn-primary btn-sm" id="getContacts">Get Contacts</button>
                                 </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Name<span class="req-fld">*</span></label>
                                <div class="col-sm-6">
                                    <input type="text" required  maxlength="100" <?php echo @$is_disable; ?>  class="form-control" id="name" value="<?php echo @$campaign_data[0]['name']; ?>"  name="name" placeholder="Name" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Description</label>
                                <div class="col-sm-6">
                                    <input type="text"  maxlength="100"  <?php echo @$is_disable; ?> class="form-control" id="description" value="<?php echo @$campaign_data[0]['description']; ?>"  name="description" placeholder="Description" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Campaign date<span class="req-fld">*</span></label>
                                <div class="col-sm-6">
                                    <!--<div data-date-format="yyyy-mm-dd" data-min-view="2" class="input-group date datetime col-md-5 col-xs-7">
                                        <input type="text" required  maxlength="100"  class="form-control date" id="campaign_date" value="<?php echo @$campaign_data[0]['campaign_date']; ?>"  name="campaign_date" placeholder="Campaign date" >
                                        <span class="input-group-addon btn btn-primary"><span class="glyphicon glyphicon-th"></span></span>
                                    </div>
                                    -->
                                    <input type="text" required  maxlength="15"  class="form-control date" id="campaign_date" value="<?php echo @$campaign_data[0]['campaign_date']; ?>"  name="campaign_date" data-mask="date" readonly >
                                </div>
                            </div>
                            <div class="form-group mail_fields <?php echo $mail_content_cls;?>">
                                <label class="col-sm-3 control-label"></label>
                                <div class="col-sm-6">
                                   	<span id="contactsCountDisp"></span>
                                </div>
                            </div>
                            <div class="form-group mail_fields <?php echo $mail_content_cls;?>">
                                <label class="col-sm-3 control-label">Mail To<span class="req-fld">*</span></label>
                                <div class="col-sm-6">
                                    <textarea  class="form-control" name="mail_to" id="mail_to" rows="1"></textarea>
                                </div>
                            </div>
                            <div class="form-group mail_fields <?php echo $mail_content_cls;?>">
                                <label class="col-sm-3 control-label">Subject<span class="req-fld">*</span></label>
                                <div class="col-sm-6">
                                    <textarea  class="form-control" name="subject" id="subject" rows="1"></textarea>
                                </div>
                            </div>
                            <div class="form-group mail_fields <?php echo $mail_content_cls;?>">
                                <label class="col-sm-3 control-label">Mail content<span class="req-fld">*</span></label>
                                <div class="col-sm-6">
                                    <textarea id="mail_content"  name="mail_content"  <?php echo @$is_disable; ?>  class="form-control ckeditor"><?php echo @$campaign_data[0]['mail_content']; ?></textarea>
<!--                                    <input type="text" required  maxlength="100"  class="form-control ckeditor" id="mail_content" value="<?php //echo @$campaign_data[0]['mail_content']; ?>"  name="mail_content" placeholder="Mail content" >-->
                                </div>
                            </div>
                             <?php if(@$campaign_data[0]['campaign_id']==NULL){?>
                            <div class="form-group mail_fields <?php echo $mail_content_cls;?>">
                                <label class="col-sm-3 control-label">Attachments</label>
                                <div class="col-sm-6">
                                    <!--                                    <input type="text" required  maxlength="100"  class="form-control ckeditor" id="mail_content" value="<?php //echo @$campaign_data[0]['mail_content']; ?>"  name="mail_content" placeholder="Mail content" >-->
                                    <input type="file" id="attachments" name="file_name[]" multiple value="" class="form-control" style="height: inherit;" >
                                    <small>(Allowed types: jpg, png, gif, pdf, doc, docx, xls, xlsx Max size:2MB)</small>
                                </div>
                            </div>
                             
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-10">
                                    <button class="btn btn-primary" type="submit" name="submitCampaign" value="button"><i class="fa fa-check"></i> Submit</button>
                                    <a class="btn btn-danger" href="<?php echo SITE_URL; ?>campaign"><i class="fa fa-times"></i> Cancel</a>
                                </div>
                            </div>
                                <?php }?>    
                        </form>
                    </div>
                </div>				
            </div>
        </div><br>

        <?php
        // break;
    }
}
echo $this->session->flashdata('response');
?>
<?php
if (@$displayList == 1) {
    ?>

    <div class="row"> 
        <div class="col-sm-12 col-md-12">
            <div class="block-flat">
                <table class="table table-bordered"></table>
                <div class="content">
                    
                    <form role="form" class="form-horizontal" method="post" action="<?php echo SITE_URL; ?>campaign">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">    
                                    <label class="col-sm-1 control-label">Name</label>
                                    <div class="col-sm-2">
                                        <input type="text" name="campaignName" placeholder="Campaign Name" maxlength="100"  value="<?php echo @$searchParams['campaignName']; ?>" id="companyName" class="form-control">
                                    </div>
                                    <label class="col-sm-2 control-label">Campaign Date</label>
                                    <div class="col-sm-2">
                                        <input type="text" name="fromDate" placeholder="From Date"   value="<?php echo @$searchParams['fromDate']; ?>" id="start_date" class="form-control">
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="text" name="toDate" placeholder="To Date" value="<?php echo @$searchParams['toDate']; ?>" id="end_date" class="form-control">
                                    </div>
                                    <div class="col-sm-3">
                                        <button type="submit" name="searchCampaign" value="1" class="btn btn-success"><i class="fa fa-search"></i></button>
                                        <button style="margin-left:5px;" type="submit" name="downloadCampaign" class="btn btn-success" formaction="<?php echo SITE_URL; ?>downloadCampaign" value="downloadCustomer"><i class="fa fa-cloud-download"></i> </button>
                                        <a style="margin-left:5px;" href="<?php echo SITE_URL; ?>addCampaign" class="btn btn-success"><i class="fa fa-plus"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <div class="col-sm-9"></div>
                                    
                                    <div class="col-sm-3">
                                        
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
                                    <th class="text-center"><strong>Campaign Type</strong></th>
                                    <th class="text-center"><strong>Campaign Name</strong></th>
                                    <th class="text-center"><strong>Campaign date</strong></th>
                                    <th class="text-center"><strong>Specialities</strong></th>
                                    <th class="text-center"><strong>Locations </strong></th>
                                    <th class="text-center"><strong>Actions</strong></th>    
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                //@$inc = $start + 1;
                                if (@$count > 0) {
                                    foreach ($campaignSearch as $row) {
										
										$specialities = getCampaignSpecialities(@$row['campaign_id']);
										$locations = getCampaignLocations(@$row['campaign_id']);
										$pop_id = 'view_campaign_'.@$row['campaign_id'];
                                        ?>
                                        <tr>
                                            <td class="text-center"><?php echo @$sn++; //@$sn      ?></td>
                                            <td class="text-center"><?php echo (@$row['type']==1)?'Mass Mailing':'Offline';?></td>
                                            <td class="text-center"><?php echo @$row['name']; ?></td>
                                            <td class="text-center"><?php echo @$row['campaign_date']; ?></td>
                                            <td class="text-center"><?php echo @$specialities; ?></td>
                                            <td class="text-center"><?php echo @$locations; ?></td>

                                            <td class="text-center">
                                                <!--<a class="btn btn-default" title="view" style="padding:3px 3px;" href="<?php echo SITE_URL; ?>editCampaign/<?php echo @icrm_encode($row['campaign_id']); ?>"><i class="fa fa-eye"></i></a> -->
                                               <button class="btn btn-primary" style="padding:3px 3px;" title="view" type="button" data-target="#<?php echo @$pop_id;?>" data-toggle="modal"><i class="fa fa-info"></i></button>
                                               <?php
                                                if (@$row['status'] == 1) {
                                                    ?>
                                                    <a class="btn btn-danger" style="padding:3px 3px;" href="<?php echo SITE_URL;?>deactivateCampaign/<?php echo @icrm_encode($row['campaign_id']); ?>" onclick="return confirm('Are you sure you want to Deactivate?')"><i class="fa fa-trash-o"></i></a>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <a class="btn btn-info" title="Activate" style="padding:3px 3px;" href="<?php echo SITE_URL;?>activateCampaign/<?php echo @icrm_encode($row['campaign_id']); ?>"  onclick="return confirm('Are you sure you want to Activate?')"><i class="fa fa-check"></i></a>
                                                    <?php
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <?php
										include('modals/viewCampaign_modal.php');
                                    }
                                } else {
                                    ?>	<tr><td colspan="8" align="center"><span class="label label-primary">No Records</span></td></tr>
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

<script>
$(document).ready(function(){
    select2Ajax('checkLocation', 'cityLocation');    
});



$("#campaign_date").datepicker({
        dateFormat: "yy-mm-dd",
        
        changeMonth: true,
        changeYear: true,
       
    });	
</script>
<script type="text/javascript">
$( document ).ready(function() {
   var start_date = $("#start_date").val();
   var end_date = $("#end_date").val();
   $("#start_date").datepicker({
        dateFormat: "yy-mm-dd",
		changeMonth: true,
  		changeYear: true,
       // minDate: 0,
        onSelect: function (date) {
           
            var date2 = $(this).datepicker('getDate');
            $('#end_date').datepicker('option', 'minDate', date2);
        }
    });
	$("#end_date").datepicker({
        dateFormat: "yy-mm-dd",
        changeMonth: true,
  		changeYear: true,
        onSelect: function (date) {
           
            var date2 = $(this).datepicker('getDate');
            $('#start_date').datepicker('option', 'maxDate', date2);
							
        }
    });
});

</script>