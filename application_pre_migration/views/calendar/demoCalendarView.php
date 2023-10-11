<?php
	$this->load->view('commons/main_template',$nestedView); 
?>
<div class="row"> 
	<div class="col-sm-12 col-md-12">
		<div class="block-flat">
			<div class="header">							
				<h4>View Demo Calendar</h4>
			</div>
			<div class="content">
				<form class="form-horizontal" role="form" action="<?php echo SITE_URL; ?>viewDemoCalendar"  parsley-validate novalidate method="post">
					<div class="form-group">
						<label for="inputProductName" class="col-sm-3 control-label">Product Name <span class="req-fld">*</span></label>
						<div class="col-sm-6">
							<?php echo form_dropdown('product', $products, $product_id,'class="select2 product" required'); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="inputDemoProductName" class="col-sm-3 control-label">Demo Product Name <span class="req-fld">*</span></label>
						<div class="col-sm-6">
							<?php echo form_dropdown('demoProduct', $demoProducts, $demo_product_id,'class="select2 demoProduct" required'); ?>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-3 col-sm-10">
							<button class="btn btn-primary" type="submit" name="submitDemoProduct" value="button"><i class="fa fa-check"></i> Submit</button>
							<a class="btn btn-danger" href="<?php echo SITE_URL;?>viewDemoCalendar"><i class="fa fa-times"></i> Cancel</a>
						</div>
					</div>
				</form>
			</div>
		</div>				
	</div>
</div><br>
<?php 
if(@$flag==1)
{
?>
	<div class="row"> 
		<div class="col-md-12">
	  		<div class="block-flat">
	            <div class="header">							
	              <h3>Demo Product Calendar</h3>
	            </div>
	   			<div class="content">
	      		<div id='calendar'></div>
	    		</div>
	 	 	</div>
		</div>
	</div><br>
<?php
}
	$this->load->view('commons/main_footer.php',$nestedView); 
?>
<script type="text/javascript">
$(document).on("change",".product",function() {		
    var old_this = $(this);
    $.ajax({
        type: "POST",
        url: "<?php echo SITE_URL;?>getDemoProduct",
        data:'product_id='+$(this).val(),
        beforeSend: function()
        {
        },
	    success: function(data){
	        $(old_this).parents('form').find('.demoProduct').html(data);
	    }
    });
});

var events = <?php echo json_encode($demoResults)?>;
$('#calendar').fullCalendar({
    header: {
		left: 'title',
		center: '',
		right: 'month,agendaWeek,agendaDay, today, prev,next',
    },
    editable: true,
    events: events
});
</script>