<!-- Nifty Modal -->
<div class="md-modal colored-header small-width md-effect-9" id="bulkUploadSOEntry">
	<div class="md-content">
		<form id="bulkUploadFrm" action="<?php echo SITE_URL.'bulkUpload_soEntry';?>" enctype="multipart/form-data" method="post">
			<div class="modal-header">
				<h3>Bulk Upload SO Entries</h3>
				<button type="button" class="close md-close" data-dismiss="modal" aria-hidden="true">&times;</button>
			</div>
			<div class="modal-body form">
				
				<div class="form-group">
					<label>Upload File <span class="req-fld">*</span> <small>(allowed csv files only)</small></label> 
					<input type="file" class="form-control" required="" id="uploadCsv" name="uploadCsv">
				</div>
				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-flat md-close" data-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-primary btn-flat" value="1" name="visitUpdate_submit">Submit</button>
			</div>
		</form>
	</div>
</div>