<div class="modal fade colored-header" id="<?php echo @$pop_id;?>" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		
			<div class="md-content">
				<div class="modal-header">
					<span style="font-size:18px">View Campaign Details</span>
					<button type="button" class="close md-close" data-dismiss="modal" aria-hidden="true">&times;</button>
				</div>

				<div class="modal-body form form-horizontal campaign_modal">
			<div class="form-group">
                                <label class="col-sm-3  control-label">Campaign Type</label>
                                <div class="col-sm-6"><?php echo (@$row['type']==1)?'Mass Mailing':'Offline';?></div>
                        </div>
                        <div class="form-group">
                                <label class="col-sm-3  control-label">Speciality</label>
                                <div class="col-sm-6"><?php echo @$specialities;?></div>
                        </div>
                        <div class="form-group">
                                <label class="col-sm-3  control-label">Locations</label>
                                <div class="col-sm-6"><?php echo @$locations;?></div>
                        </div>
                        <div class="form-group">
                                <label class="col-sm-3  control-label">Campaign Name</label>
                                <div class="col-sm-6"><?php echo @$row['name'];?></div>
                        </div>
                        <div class="form-group">
                                <label class="col-sm-3  control-label">Description</label>
                                <div class="col-sm-6"><?php echo @$row['description'];?></div>
                        </div>
                        <div class="form-group">
                                <label class="col-sm-3  control-label">Campaign date</label>
                                <div class="col-sm-6"><?php echo @$row['campaign_date'];?></div>
                        </div>
                        <?php if(@$row['type']==1){?>
                        <div class="form-group">
                                <label class="col-sm-3  control-label">Subject</label>
                                <div class="col-sm-6"><?php echo @$row['subject'];?></div>
                        </div>
                        <div class="form-group">
                                <label class="col-sm-3  control-label">Mail Content</label>
                                <div class="col-sm-6"><?php echo @$row['mail_content'];?></div>
                        </div>
                        <?php }?>
				</div>	
				<div class="modal-footer">
					<span class="opp_error error" style="color:red;float:left;font-weight:bold;"></span>
					<button type="button" class="btn btn-primary btn-flat md-close" data-dismiss="modal">Close</button>
				</div>
			</div>	
		
	</div>	
</div>
<div class="md-overlay"></div>