<?php $this->load->view('commons/main_template', $nestedView); ?>
<?php
echo $this->session->flashdata('response');
?>
<div>									
<form id="bulkUploadFrm" action="<?php echo SITE_URL.'sample_upload';?>" method="post"  enctype="multipart/form-data" class="form-horizontal">                    
<label>Excel File:</label>                        
<input type="file" name="userfile" />				                   
<input type="submit" value="upload" name="upload" />
</form>	
</div>
<?php $this->load->view('commons/main_footer.php', $nestedView); ?>
