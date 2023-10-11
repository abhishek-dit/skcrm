<!-- Nifty Modal -->
<div class="md-modal colored-header small-width md-effect-9" id="<?php echo $pop_id;?>">
	<div class="md-content">
		<form action="<?php echo SITE_URL.'update_demoFeedback';?>" method="post">
			<input type="hidden" name="encoded_id" value="<?php echo icrm_encode($row['demo_id']);?>">
			<div class="modal-header">
				<h3>Update Demo Feedback</h3>
				<button type="button" class="close md-close" data-dismiss="modal" aria-hidden="true">&times;</button>
			</div>
			<div class="modal-body form">
				<div class="form-group">
					<div class="col-sm-3">Customer</div><label class="col-sm-8"><?php echo @$row['CustomerName'];?></label><div class="col-sm-1"></div>
				</div>
				<div class="form-group">
					<div class="col-sm-3">Opportunity</div><label class="col-sm-8"><?php echo @$row['opportunity'];?></label><div class="col-sm-1"></div>
				</div>
				<div class="form-group">
					<div class="col-sm-3">Demo</div><label class="col-sm-8"><?php echo @$row['demo'];?></label><div class="col-sm-1"></div>
				</div>
				<div class="form-group">
					<label>Feed back</label> <textarea name="remarks2" required class="form-control" rows="3" placeholder="Enter Demo Feedback"><?php echo @$row['remarks2']?></textarea>
				</div>
				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-flat md-close" data-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-primary btn-flat" value="1" name="demoUpdate_submit">Submit</button>
			</div>
		</form>
	</div>
</div>
