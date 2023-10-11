<!-- Nifty Modal -->
<div class="md-modal colored-header small-width md-effect-9" id="<?php echo $pop_id;?>">
	<div class="md-content">
		<form action="<?php echo SITE_URL.'update_visitFeedback';?>" method="post">
			<input type="hidden" name="encoded_id" value="<?php echo icrm_encode($row['visit_id']);?>">
			<div class="modal-header">
				<h3>Update Visit Feedback</h3>
				<button type="button" class="close md-close" data-dismiss="modal" aria-hidden="true">&times;</button>
			</div>
			<div class="modal-body form">
				<div class="form-group">
					<label><?php echo @$row['CustomerName'];?></label>
				</div>
				<div class="form-group">
					<label>Feed back</label> <textarea name="remarks2" required class="form-control" rows="3" placeholder="Enter Visit Feedback"><?php echo @$row['remarks2']?></textarea>
				</div>

				<div class="form-group">
					<label>Status</label> 
					<select name="status" class="form-control" required>
                      <option value="">Select Status</option>
                      <option value="3">Completed</option>
		              <option value="4">Postponed</option>
		              <option value="2">Cancelled</option>
                    </select>
				</div>
				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-flat md-close" data-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-primary btn-flat" value="1" name="visitUpdate_submit">Submit</button>
			</div>
		</form>
	</div>
</div>
