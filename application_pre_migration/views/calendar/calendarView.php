<?php
	$this->load->view('commons/main_template',$nestedView); 
?>
	<div class="row"> 
		<div class="col-sm-12 col-md-12">
      		<div class="block-flat">
       			<div class="content">
       				<form class="form-horizontal" role="form" action="<?php echo SITE_URL; ?>viewCalendar"  parsley-validate novalidate method="post">
	       				<?php if($this->session->userdata('reportees') != 0) {?>
	       				<div class="row">
	       					<div class="col-sm-5">
	       						<select class="getReporteesWithUser" style="width:100%" name="reporteeUser" onchange="this.form.submit()">
	       							<option value="<?php echo $user_id; ?>">
	       								<?php echo getUserDropDownDetails($user_id); ?>
	       							</option>
	       						</select>
	       					</div>
	          			</div>
	          			<?php } ?>
	          			<br>
          			</form>
          			<div class="row">
          				<div class="col-sm-3">
          					<br>
          					<br>
          					<div style="position: fixed;">
	          					<span style="background-color:#4352B0; padding:10px 70px;"></span><span style="font-weight: bold;">&nbsp; --> Visit</span><br><br>
	          					<span style="background-color:#30AEF7; padding:10px 70px;"></span><span style="font-weight: bold;">&nbsp; --> Demo</span><br><br>
	          				</div>
          				</div>
          				<div class="col-sm-9" id='calendar'></div>

          			</div>

          			<br>
        		</div>
     	 	</div>
    	</div>
	</div>

<?php
	$this->load->view('commons/main_footer.php',$nestedView); 
?>
<script type="text/javascript">
var visitEvents = <?php echo json_encode($visitCalendarDetails)?>;
var demoEvents = <?php echo json_encode($demoCalendarDetails)?>;

$(document).ready(function(){
    select2Ajax('getReporteesWithUser', 'getReporteesWithUser', 0, 0);
});

$('#calendar').fullCalendar({
    header: {
		left: 'title',
		center: '',
		right: 'month,agendaWeek,agendaDay, today, prev,next',
    },
    editable: true,
    height: $(window).height()-200,
    eventRender: function(event, element) {
      $(element).tooltip({title: event.description,container: "body"});             
  },
    eventSources: [
    	{
	    	events: visitEvents,
	    	color: '#4352B0',     // an option!
	        textColor: 'white',
	        editable: false
    	},
    	{
	    	events: demoEvents,
	    	color: '#30AEF7',     // an option!
	        textColor: 'white',
	        editable: false
    	}
    ]
});
</script>