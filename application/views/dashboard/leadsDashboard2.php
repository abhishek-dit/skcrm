<?php $this->load->view('commons/main_template', $nestedView);

?>
<div id="loaderID" style="position:fixed; top:50%; left:58%; z-index:2; opacity:0"><img src="<?php echo assets_url(); ?>images/ajax-loading-img.gif" /></div>

<div class="row"> 
	<div class="col-sm-12 col-md-12">
		<div class="block-flat">
			<div class="content">
				<div class="header">
					
					<div class="row">
						<div class="form-group">
							<div class="col-sm-12">
								<div class="col-sm-6">
								<?php /* if($this->session->userdata('role_id') != 4 && $this->session->userdata('role_id') != 5 && $this->session->userdata('role_id') != 6 && $this->session->userdata('role_id') != 7) { ?>
									<label>Product Category / Region  &nbsp; &nbsp; </label>
									<input type="radio" checked="" name="pc_region" value="1" class="pcr"> Product Category &nbsp; &nbsp;
									<input type="radio" name="pc_region" value="2" class="pcr"> Region
								<?php } 
								else { ?>
									<input type="hidden" name="pc_region" value="1" class="pcr">
								<?php }	*/ ?>
								</div>
								<div class="col-sm-6" align="right">
									<label>Timeline &nbsp; &nbsp; </label>
									<input type="radio" name="timeline" value="1" class="tline"> Month &nbsp; &nbsp;
									<input type="radio" name="timeline" value="2" class="tline"> Quarter &nbsp; &nbsp;
									<input type="radio" checked="" name="timeline" value="3" class="tline"> Year
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="content">
								
					<div>
						<div class="row">
							<div class="col-sm-12">
								<div class="col-md-6" align="center">
									<div id="speedometer" style="width: 450px; height: 300px; margin: 0 auto"></div>
								</div>
								<div class="col-md-6">
									
									<div id="container1" style="width: 450px; height: 300px; margin: 0 auto"></div>
								</div>
							</div>	
						</div>
						<div class="row">
							<div class="col-sm-12">
								<div class="col-md-12" align="center">
									<div id="container2" style="width: 900px; height: 300px; margin: 0 auto"></div>
								</div>
							</div>	
						</div>
					</div>	
				</div>

			</div>
		</div>				
	</div>
</div>


<?php

$t[] = array('name' => 'Apr', 'data' => array(5));
$t[] = array('name' => 'Mar', 'data' => array(2));
$t[] = array('name' => 'Jun', 'data' => array(4));
$t = json_encode($t);
echo $t;
/*

[{
    name: 'John',
    data: [5, 3, 4, 7, 2]
}, {
    name: 'Jane',
    data: [2, 2, 3, 2, 1]
}, {
    name: 'Joe',
    data: [3, 4, 4, 2, 5]
}]

*/



$this->load->view('commons/main_footer.php', $nestedView); ?>

<script type="text/javascript">

Highcharts.setOptions({ colors: [ '#3F51B5','#FF9800','#4CAF50', '#F44336', '#9C27B0', '#795548', '#FFEB3B', '#CDDC39']});

//var icrm = $.noConflict();
$('.tline').change(function(){
	var timeline = $('.tline:checked').val();
	$("#pcont").css("opacity",0.5);
	$("#loaderID").css("opacity",1);
    $.ajax({
        url: SITE_URL + 'getLeadDashboardData?&timeline='+timeline,
        dataType:'json', 
		success: function(data){
			getCharts(timeline, data)
			$("#pcont").css("opacity",1);
			$("#loaderID").css("opacity",0);
		}
	});

	//getCharts(pc_region, timeline)
	//alert(pcr+' '+tline);

});

chartsData = <?php echo $chartsData; ?>;
t = <?php echo $t; ?>;
getCharts(3, chartsData, t);
function getCharts(timeline, chartsData, t) {

	regionTitle = chartsData['regionTitle'];
	targetVsActualPercent = chartsData['targetVsActualPercent'];

	var pcr = 'Region';
	var tline = 'MTD';
	if(timeline == 2)
		tline = 'QTD';
	if(timeline == 3)
		tline = 'YTD';

    $('#speedometer').highcharts({

        chart: {
            type: 'gauge',
            plotBackgroundColor: null,
            plotBackgroundImage: null,
            plotBorderWidth: 0,
            plotShadow: false
        },

        title: {
            text: 'Target Vs Actual'
        },

        pane: {
            startAngle: -90,
            endAngle: 90 ,
            background: {
            	borderWidth: 0,
                backgroundColor: 'none',
                innerRadius: '60%',
                outerRadius: '100%',
                shape: 'arc'
            }
        },

        // the value axis
        yAxis: {
            min: 0,
            max: 100,

            minorTickInterval: 'auto',
            minorTickWidth: 0,
            minorTickLength: 10,
            minorTickPosition: 'inside',
            minorTickColor: '#666',

            tickPixelInterval: 30,
            tickWidth: 0,
            tickPosition: 'inside',
            tickLength: 10,
            tickColor: '#666',
            labels: {
                step: 2,
                /*format: '{value} %',*/
                rotation: 'auto'
            },
            title: {
                text: null
            },
            plotBands: [{
                from: 0,
                to: 40,
                color: '#ED7D31' // orange
            }, {
                from: 40,
                to: 60,
                color: '#A5A5A5' // ash
            }, {
                from: 60,
                to: 80,
                color: '#FFC000' // yellow
            }, {
                from: 80,
                to: 100,
                color: '#4472C4' // blue
            }]
        },
        credits: {
            enabled: false
        },

        series: [{
            name: 'Actual',
            dataLabels: {
		        x: 0, y: 20,
		        format: '{y} %',
		    },
            data: [targetVsActualPercent],
            tooltip: {
                valueSuffix: ' %'
            }
        }]

    });




}

</script>