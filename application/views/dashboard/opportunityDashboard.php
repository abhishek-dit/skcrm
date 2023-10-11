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
                            <form class="form-horizontal" role="form" action="<?php echo SITE_URL; ?>opportunityDashboard"  parsley-validate novalidate method="post">
                                <input type="hidden" name="action" value="submit">

                                    <div class="col-sm-4">
                                        <input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id; ?>">
                                        <input type="hidden" name="role_id" id="role_id" value="<?php echo $role_id; ?>">
                                        <select class="getUserProductReporteesWithUser" style="width:100%" name="user_id" onchange="this.form.submit()">
                                            <option value="<?php echo $user_id; ?>">
                                                <?php echo getUserDropDownDetails($user_id); ?>
                                            </option>
                                        </select>
                                    </div>
                            </form>

								<div class="col-sm-3">
								<?php if($role_id != 4 && $role_id != 5 && $role_id != 6 && $role_id != 7) { ?>
									<!--<label>Product Category / Region  &nbsp; &nbsp; </label>-->
									<input type="radio" checked="" name="pc_region" value="1" class="pcr"> Product Category &nbsp; &nbsp;
									<input type="radio" name="pc_region" value="2" class="pcr"> Region
								<?php } 
								else { ?>
									<span hidden><input type="radio" checked="" name="pc_region" value="1" class="pcr"></span>
									
								<?php }	?>
								</div>
								<div class="col-sm-5" align="right">
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
								<div class="col-md-4" align="center">
									<div id="container" style="width: 300px; height: 300px; margin: 0 auto"></div>
								</div>
								<div class="col-md-4">
									
									<div id="container1" style="width: 300px; height: 300px; margin: 0 auto"></div>
								</div>
								<div class="col-md-4">
								
									<div id="container2" style="width: 300px; height: 300px; margin: 0 auto"></div>
								</div>
							</div>	
						</div>
						<div class="row">
							<div class="col-sm-12">
								<div class="col-md-4" align="center">
									<div id="container3" style="width: 300px; height: 300px; margin: 0 auto"></div>
								</div>
								<div class="col-md-4">
									
									<div id="container4" style="width: 300px; height: 300px; margin: 0 auto"></div>
								</div>
								<div class="col-md-4">
								
									<div id="container5" style="width: 300px; height: 300px; margin: 0 auto"></div>
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
/*

$b = array();
$b[] = array('Hot', 15);
$b[] = array('Warm', 14);
$b[] = array('Cold', 5);
$b = json_encode($b);
echo $b;

$c = array(12, 13, 14, 6, 23, 12, 24);
$c = json_encode($c);

$d = array('RAD','PMS','CRD','ANS','RMS','SYP','ESU');
$d = json_encode($d);           

*/


//print_r($_SESSION['reportees']);
$this->load->view('commons/main_footer.php', $nestedView); ?>

<script type="text/javascript">

$(document).ready(function(){
    select2Ajax('getUserProductReporteesWithUser', 'getUserProductReporteesWithUser', 0, 0)
});


Highcharts.setOptions({ colors: [ '#3F51B5','#FF9800','#F44336', '#4CAF50', '#9C27B0', '#795548', '#FFEB3B', '#CDDC39']});

//var icrm = $.noConflict();
$('.pcr,.tline').change(function(){
	var pc_region = $('.pcr:checked').val();
	var timeline = $('.tline:checked').val();
    var user_id = $('#user_id').val();
    var role_id = $('#role_id').val();
    //alert(role_id);
	$("#pcont").css("opacity",0.5);
	$("#loaderID").css("opacity",1);
    $.ajax({
        url: SITE_URL + 'getOpportunityDashboardData?user_id='+user_id+'&role_id='+role_id+'&pc_region='+pc_region+'&timeline='+timeline,
        dataType:'json', 
		success: function(data){
			getCharts(pc_region, timeline, data)
			$("#pcont").css("opacity",1);
			$("#loaderID").css("opacity",0);
		}
	});

	//getCharts(pc_region, timeline)
	//alert(pcr+' '+tline);

});

chartsData = <?php echo $chartsData; ?>;
getCharts(1, 3, chartsData);
function getCharts(pc_region, timeline, chartsData) {

	c1Data = chartsData['c1Data'];
	pcrTitle = chartsData['pcrTitle'];
	c5Data1 = chartsData['c5Data1'];
	c5Data2 = chartsData['c5Data2'];
	c5Data3 = chartsData['c5Data3'];
	c2Data = chartsData['c2Data'];
	c4Data1 = chartsData['c4Data1'];
	c4Data2 = chartsData['c4Data2'];
	c3Data1 = chartsData['c3Data1'];
	c3Data2 = chartsData['c3Data2'];
	c6Data1 = chartsData['c6Data1'];
	c6Data2 = chartsData['c6Data2'];

	var pcr = 'Product Category';
	var tline = 'MTD';

	if(pc_region == 2)
	{
		pcr = 'Region';
	}
	if(timeline == 2)
		tline = 'QTD';
	if(timeline == 3)
		tline = 'YTD';

    $('#container').highcharts({
        chart: {
            type: 'funnel',
            marginRight: 100
        },
        title: {
            text: 'Pipeline Opportunities (In Lakhs)',
             style:{
                    fontSize: '10px'
                },
           x: -50
        },
        credits: {
      		enabled: false
  		},
        plotOptions: {
            series: {
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b> ({point.y:.0f})',
                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black',
                    softConnector: true
                },
                neckWidth: '25%',
                neckHeight: '40%'

                //-- Other available options
                // height: pixels or percent
                // width: pixels or percent
            }
        },
        legend: {
            enabled: false
        },
        series: [{
            name: 'Opportunities',
            data: c1Data
        }]
    });


    $('#container1').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Pipeline Opportunities by '+pcr,
            style:{
                    fontSize: '10px'
                }            
        },
        credits: {
      		enabled: false
  		},
        xAxis: {
            categories: pcrTitle,
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Value (Lakhs)'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y} Lakhs</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [{
            name: 'Cold',
            data: c5Data3

        }, {
            name: 'Warm',
            data: c5Data2

        }, {
            name: 'Hot',
            data: c5Data1

        }]
    });

    $('#container2').highcharts({
        chart: {
            type: 'bar'
        },
        title: {
            text: 'Top Hot Opportunities',
            style:{
                    fontSize: '10px'
                }            

        },
        xAxis: {
            categories: c3Data1,
            title: {
                text: null
            },
            labels: {   
                formatter: function () {
                  return this.value.substring(1,12);
                } 
            } 
        },
        yAxis: {
            min: 0,
            title: {
                text: 'In Lakhs',
                align: 'high',

            },
            labels: {
                overflow: 'justify'
            }
        },
        tooltip: {
            valueSuffix: ' Lakhs'
        },
        plotOptions: {
            bar: {
                dataLabels: {
                    enabled: true
                }
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'top',
            x: -40,
            y: 80,
            floating: true,
            borderWidth: 1,
            backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
            shadow: true,
            enabled: false
        },
        credits: {
            enabled: false
        },
        series: [{
            name: 'Opportunity Worth',
            
            data: c3Data2
        }]
    });

    $('#container5').highcharts({
        chart: {
            type: 'bar'
        },
        title: {
            text: 'Top Opportunities Closed '+tline,
            style:{
                    fontSize: '10px'
                }            

        },
        xAxis: {
            categories: c6Data1,
            title: {
                text: null
            },
            labels: {   
                formatter: function () {
                  return this.value.substring(1,12);
                } 
            } 
        },
        yAxis: {
            min: 0,
            title: {
                text: 'In Lakhs',
                align: 'high',

            },
            labels: {
                overflow: 'justify'
            }
        },
        tooltip: {
            valueSuffix: ' Lakhs'
        },
        plotOptions: {
            bar: {
                dataLabels: {
                    enabled: true
                }
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'top',
            x: -40,
            y: 80,
            floating: true,
            borderWidth: 1,
            backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
            shadow: true,
            enabled: false
        },
        credits: {
            enabled: false
        },
        series: [{
            name: 'Opportunity Worth',
            
            data: c6Data2
        }]
    });

    $('#container3').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Opportunities closed by '+pcr+' '+tline,
            style:{
                    fontSize: '10px'
                }            
        },
        credits: {
      		enabled: false
  		},
        xAxis: {
            categories: pcrTitle,
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Value (Lakhs)'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y} Lakhs</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [{
            name: 'Won',
            data: c4Data1

        }, {
            name: 'Lost',
            data: c4Data2

        }]
    });

    $('#container4').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Closure success by '+pcr+' '+tline,
            style:{
                    fontSize: '10px'
                }            
        },
        credits: {
      		enabled: false
  		},
        legend : {
        	enabled: false
        },
        xAxis: {
            categories: pcrTitle,
            crosshair: true
        },
        yAxis: {
            min: 0,
            max: 100,
            title: {
                text: 'Percentage'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0"></td>' +
                '<td style="padding:0"><b>{point.y:.1f} %</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [{
            name: 'Closure Success %',
            colorByPoint: true,
            data: c2Data

        }]
    });


}

</script>