<?php
$this->load->view('commons/main_template', $nestedView);
echo $this->session->flashdata('response'); 
?>
<div class="row"> 
	<div class="col-sm-12 col-md-12">
        <div class="block-flat">
            <div class="content">
            	<div class="alert alert-danger">
                    <strong>Check!</strong> Please attach customer order documents for the following POs 
                    <strong><?php echo $po_id;?> </strong>. documents need to be uploaded for the previous PO's to raise new PO
                    <a href="<?php echo SITE_URL; ?>po_tracking">Click Here</a> to upload documents.
                 </div>
            </div>
        </div>
    </div>    
</div>
<?php $this->load->view('commons/main_footer.php', $nestedView); ?>