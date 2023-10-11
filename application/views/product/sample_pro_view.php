<?php
	$this->load->view('commons/main_template',$nestedView); 
?>
<form class="form-horizontal" role="form" action="<?php echo SITE_URL; ?>sampleProductAdd">
<div class="col-sm-6">
       <input type="text" required class="form-control" id="name" placeholder="Product Code" name="name"  maxlength="150">
      <input type="submit" name="submit" value="1">
             </div>
</form>
<?php
$this->load->view('commons/main_footer',$nestedView);
?>