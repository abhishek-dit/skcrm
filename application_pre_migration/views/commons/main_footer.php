	</div>
</div>
	
	<!-- Java Scripts -->
	<script type="text/javascript" src="<?php echo assets_url(); ?>js/jquery.js"></script>
	<script type="text/javascript" src="<?php echo assets_url(); ?>js/jquery-ui.min.js"></script>
	<script type="text/javascript" src="<?php echo assets_url(); ?>js/jquery.select2/select2.min.js"></script>
	<script type="text/javascript" src="<?php echo assets_url(); ?>js/jquery.nanoscroller/jquery.nanoscroller.js"></script>
	<script type="text/javascript" src="<?php echo assets_url(); ?>js/jquery.nestable/jquery.nestable.js"></script>
	<script type="text/javascript" src="<?php echo assets_url(); ?>js/behaviour/general.js"></script>
	<!--<script src="<?php echo assets_url(); ?>js/jquery.ui/jquery-ui.js" type="text/javascript"></script>-->
	<?php if(@$enableFormWizard!=1) { ?>
	<script type="text/javascript" src="<?php echo assets_url(); ?>js/bootstrap/dist/js/bootstrap.min.js"></script>
	<?php	} ?>
	<script type="text/javascript" src="<?php echo assets_url(); ?>js/bootstrap.switch/bootstrap-switch.min.js"></script>
	<script type="text/javascript" src="<?php echo assets_url(); ?>js/bootstrap.datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
	<script type="text/javascript" src="<?php echo assets_url(); ?>js/jquery.magnific-popup/dist/jquery.magnific-popup.min.js"></script>
	<script type="text/javascript" src="<?php echo assets_url(); ?>js/jquery.niftymodals/js/jquery.modalEffects.js"></script>

	<!--<script type="text/javascript" src="<?php echo assets_url(); ?>js/iCRM/jquery.tokeninput.js"></script>-->

	<!-- Bootstrap core JavaScript
	================================================== -->
	<!-- Placed at the end of the document so the pages load faster -->
    <script type="text/javascript">
      $(document).ready(function(){
        App.init();
		<?php if(@$enableFormWizard==1) { ?>
		 App.wizard();
		 <?php
		 }
		 ?>
		 //for modal popups
		 $('.md-trigger').modalEffects();
		 
		 $('label.tree-toggler').click(function () {
        var icon = $(this).children(".fa");
          if(icon.hasClass("fa-plus-square-o")){
            icon.removeClass("fa-plus-square-o").addClass("fa-minus-square-o");
          }else{
            icon.removeClass("fa-minus-square-o").addClass("fa-plus-square-o");
          }        
          
        $(this).parent().children('ul.tree').toggle(300,function(){
          $(this).parent().toggleClass("open");
          $(".tree .nscroller").nanoScroller({ preventPageScrolling: true });
        });
        
      });       /* App.dashBoard();        
        
          introJs().setOption('showBullets', false).start();*/
		 //MagnificPopup for images zoom
		  $('.image-zoom').magnificPopup({ 
			type: 'image',
			mainClass: 'mfp-with-zoom', // this class is for CSS animation below
			zoom: {
			enabled: true, // By default it's false, so don't forget to enable it
	
			duration: 300, // duration of the effect, in milliseconds
			easing: 'ease-in-out', // CSS transition easing function 
	
			// The "opener" function should return the element from which popup will be zoomed in
			// and to which popup will be scaled down
			// By defailt it looks for an image tag:
			opener: function(openerElement) {
			  // openerElement is the element on which popup was initialized, in this case its <a> tag
			  // you don't need to add "opener" option if this code matches your needs, it's defailt one.
			  var parent = $(openerElement).parents("div.img");
			  return parent;
			}
			}
	
		  });
      });
	   $(document).on('click',".date",function () {
		   //alert($(this).attr('id'));
           App.init();
      });
	  $("#grnDate").datepicker({
            dateFormat: "yy-mm-dd",
			changeMonth: true,
      		changeYear: true,
            maxDate: 0,
        });
      $(".dateFromToday").datepicker({
            dateFormat: "yy-mm-dd",
			changeMonth: true,
      		changeYear: true,
            minDate: 0,
        });	  
	  $("#dateFrom").datepicker({
            dateFormat: "yy-mm-dd",
			changeMonth: true,
      		changeYear: true,
           // minDate: 0,
            onSelect: function (date) {
               
                var date2 = $(this).datepicker('getDate');
                $('#dateTo').datepicker('option', 'minDate', date2);
            }
        });
		$("#dateTo").datepicker({
            dateFormat: "yy-mm-dd",
            changeMonth: true,
      		changeYear: true,
            onSelect: function (date) {
               
                var date2 = $(this).datepicker('getDate');
                $('#dateFrom').datepicker('option', 'maxDate', date2);
				<?php
				if(@$parent_page=='reports')
				{
					?>
					getFirstPieByDateRange();
					<?php
				}
				if(@$parent_page=='consumption-by-cost')
				{
					?>
					getFirstPieByDateRange();
					<?php
				}
				if(@$parent_page=='con_dashboard_cost')
				{
					?>
					getChartsbyDataRange();
					<?php
				}				
				?>				
            }
            
        });
        

   
    $( document ).ready(function() {
    $("#start_date").datepicker({
	    dateFormat: "yy-mm-dd",
		changeMonth: true,
		changeYear: true,
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
	
	<?php
	if(count($js_includes)>0)
	{
	  foreach($js_includes as $js_file)
	  {
		  echo $js_file;
	  }
	}
	?>
<style type="text/css">

</style>
</body>
</html>

<?php
//update user last active timestamp
update_userLastActive();
?>