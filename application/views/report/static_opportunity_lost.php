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
                               <!--  <label class="col-sm-1 control-label" style="margin-top:4px;"> Display : </label>
                                <div class="col-sm-3">
                                    <input type="radio" class="report_bys" name="report_by" value="1" checked=""> Reason wise
                                    <input type="radio" class="report_bys" name="report_by" value="2" > Competitor wise
                                </div> -->
                             <div class="col-sm-2">
                                    <select class="form-control duration " style="width:100%" name="duration">
                                        
                                    </select>
                                </div> 
                                <div class="col-sm-4 relative">
                                    <div class="btn-group">
                                        <!-- <input type="button" data-id="a" name="timeline" class="timeline btn btn-success" checked  value="All"> -->
                                        <input type="button" data-id="w" name="timeline" class="timeline btn btn-default" value="Week">
                                        <input type="button" data-id="m" name="timeline" class="timeline btn btn-default"  value="Month">
                                        <input type="button" data-id="q" name="timeline" class="timeline btn btn-default" value="Quarter">
                                        <input type="button" data-id="y" name="timeline" class="timeline btn btn-success" checked value="Year">
                                    </div>
                                </div>    
                                </div>
							</div>
						</div>
					</div>
				</div>
				<div class="content">
								
					<div>
						<div class="row">
							<div class="col-sm-6">
								<div class="col-md-11" align="center">
									<div id="container1" style=" margin: 0 auto"></div>
								</div>
							</div>
                            <div class="col-sm-6">
                                <div class="col-md-11" align="center">
                                    <div id="container4" style=" margin: 0 auto"></div>
                                </div>
                            </div>	
						</div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="col-md-11" align="center">
                                    <div id="container2" style=" margin: 0 auto"></div>
                                </div>
                            </div>  
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="col-md-11" align="center">
                                    <div id="container3" style=" margin: 0 auto"></div>
                                </div>
                            </div>  
                        </div>
					</div>	
				</div>

			</div>
		</div>				
	</div>
</div>

<style type="text/css">
    .radio-inline{ padding-left: 10px !important;}
    .radio-inline input[type="radio"]{ margin: 0px 4px 0px 0px;}
</style>

<?php 
$this->load->view('commons/main_footer.php', $nestedView); ?>

<script type="text/javascript">


Highcharts.setOptions({ colors: [ '#42A5F5','#A1887F','#FFD54F','#3F51B5','#FF9800','#F44336', '#4CAF50', '#9C27B0', '#795548', '#FFEB3B', '#CDDC39']});
filterDuration();
$(document).on('change','.duration',function(){
    var duration=$('.duration').val();
    filterReport();
});
var vtime='y';
var from_date, to_date;
$('.timeline').click(function(){

    var tl = $(this).attr('data-id');
    vtime = tl;
    $('#date_from').val('');
    $('#date_to').val('');
    $('.timeline').addClass('btn-default').removeClass('btn-success');
    $(this).addClass('btn-success').removeClass('btn-default');
    filterDuration();
});

function customDateChangeEvent(){
    //alert('hello');
    var from_date = $('#date_from').val();
    var to_date = $('#date_to').val();
    vtime = '';
    if(from_date!=''&&to_date!='')
    {
         $('.timeline').addClass('btn-default').removeClass('btn-success');
         filterReport();
    }
    
}

$("#date_from").datepicker({
    dateFormat: "yy-mm-dd",
    changeMonth: true,
    changeYear: true,
   // minDate: 0,
    onSelect: function (date) {
       
        var date2 = $(this).datepicker('getDate');
        $('#date_to').datepicker('option', 'minDate', date2);
        customDateChangeEvent();
    }
});

$("#date_to").datepicker({
    dateFormat: "yy-mm-dd",
    changeMonth: true,
    changeYear: true,
    onSelect: function (date) {
       
        var date2 = $(this).datepicker('getDate');
        $('#date_from').datepicker('option', 'maxDate', date2);
        customDateChangeEvent();
                        
    }
    
});

function filterDuration()
{   
    var tl = $('.btn-success').attr('data-id');
    var data='vtime='+tl;
     $.ajax({
        url: SITE_URL+'get_filter_duration_ol',
        type :"POST",
        data:data,
        success:function(data){
          //  alert(tl);
          if(tl=='y')
           {
              $('.duration').addClass('hidden');
               $('.duration').html('');
           }
           else
           {  
            $('.duration').removeClass('hidden');
              $('.duration').html(data);
           }
           filterReport();
          }
     });
     
}

function filterReport() {
   
    $("#pcont").css("opacity",0.5);
    $("#loaderID").css("opacity",1);
    var from_date = $('#date_from').val();
    var to_date = $('#date_to').val();
    var report_by =$('input[name=report_by]:checked').val();
    var vtime =$('.btn-success').attr('data-id');
     var duration=$('.duration').val();
    var duration_text=$('.duration option:selected').text();
    //alert(vtime+from_date+to_date);
    var data = 'from_date='+from_date+'&to_date='+to_date+'&vtime='+vtime+'&report_by='+report_by+'&duration='+duration+'&duration_text='+duration_text;
    //alert(data);
    $.ajax({
        url: SITE_URL + 'static_opportunity_lost_report_filter',
        type:"POST",
        data:data,
        dataType:'json', 
        success: function(data){
            //alert(data);
            getToolStatusChartLevel1(data['chart1Data']);
            competitorpiechart(data['chart10Data']);
            $("#pcont").css("opacity",1);
            $("#loaderID").css("opacity",0);
            $('#container2,#container3').html('').hide();
        }
    });
}

var firstPieData = <?php echo $chart1Data;?>;
var competitorpiechartData = <?php echo $chart10Data;?>
//alert(firstPieData);
getToolStatusChartLevel1(firstPieData);
competitorpiechart(competitorpiechartData);
function getToolStatusChartLevel1(firstPieData) {
 chart1Series = firstPieData['chart1Series'];
 Lable = firstPieData['label'];
 var report_by=1;  
$('#container1').highcharts({
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: 1,
        plotShadow: false,
        type: 'pie'
    },
    title: {
            text: Lable
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.y} L ({point.percentage:.1f}%)</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                point: {
                   events: {
                      click: function(event) {
                          //alert('first click');
                         $('#container2').show();
                         draw_chart2(this.options.name,report_by);
                         $('#container3').html('').hide();
                      }
                   }
                },
                dataLabels: {
                    distance: -40,
                    enabled: true,
                    format: '{point.y}',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                },
                showInLegend:true
                
            }
        },
    series: chart1Series
});


}
function competitorpiechart(competitorpiechartData)
{
    chart1Series = competitorpiechartData['chart1Series'];
    Lable = competitorpiechartData['label'];
    var report_by=2;
        $('#container4').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: 1,
            plotShadow: false,
            type: 'pie'
        },
        title: {
                text: Lable
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.y} L ({point.percentage:.1f}%)</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    point: {
                       events: {
                          click: function(event) {
                              //alert('first click');
                             $('#container2').show();
                             draw_chart2(this.options.name,report_by);
                             $('#container3').html('').hide();
                          }
                       }
                    },
                    dataLabels: {
                        distance: -40,
                        enabled: true,
                        format: '{point.y}',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        }
                    },
                    showInLegend:true
                    
                }
            },
        series: chart1Series
    });
}
//draw_chart2('A2');
// Draw Second Chart
function draw_chart2(lost_for,reported_by)
{
    //alert(tool_status);
    var report_by =reported_by;
    var from_date = $('#date_from').val();
    var to_date = $('#date_to').val();
    var duration= $('.duration').val();
    var duration_text=$('.duration option:selected').text();
    var vtime =$('.btn-success').attr('data-id');
   // alert(report_by);
    var chart2Title = 'Region wise Opportunity Lost for '+lost_for;
    $("#pcont").css("opacity",0.5);
    $("#loaderID").css("opacity",1);
    createChart = function (chartsData,lost_for) {
        var chart2Series = chartsData;
    
        var chart2Series = chartsData["chart2Series"];
        var xAxisCategory = chartsData["xAxisCategory"];
        var xAxisLable = chartsData["xAxisLable"];

        $('#container2').highcharts({
        chart: {
            type: 'column',
            inverted:false
        },
        title: {
            text: chart2Title,
            style:{
                    fontSize: '16px'
                }            
        },
        credits: {
            enabled: false
        },
        xAxis: {
            categories: xAxisCategory,
            crosshair: true,
            title: {
                text: xAxisLable
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Opportunites Lost In Lakhs'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:.1f} L</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            },
            series: {
                cursor: 'pointer',
                point: {
                    events: {
                        click: function () {
                           //alert('Category: ' + this.category+' Y val'+this.series.name);
                            $('#container3').show();
                            draw_chart3(lost_for,this.category,this.series.name,report_by);
                        }
                    }
                }
            }
        },
        series: chart2Series
    });
        
    }

    
    var data = 'lost_for='+lost_for+'&report_by='+report_by+'&from_date='+from_date+'&to_date='+to_date+'&vtime='+vtime+'&duration='+duration+'&duration_text='+duration_text;
    //alert(data);
    $.ajax({
        url: SITE_URL + 'static_getOpportunityLostChart2Data',
        type:"POST",
        data:data,
        dataType:'json', 
        success: function(data){
            //alert(data);
            createChart(data,lost_for);
            $("#pcont").css("opacity",1);
            $("#loaderID").css("opacity",0);
        }
    });
    
}

// Draw Second Chart
function draw_chart3(lost_for,region,segment,reported_by)
{
    var report_by=reported_by;
    var from_date = $('#date_from').val();
    var to_date = $('#date_to').val();
    var vtime =$('.btn-success').attr('data-id');
    var chart2Title = segment+' Products list in '+region;
    var duration= $('.duration').val();
    var duration_text=$('.duration option:selected').text();
    $("#pcont").css("opacity",0.5);
    $("#loaderID").css("opacity",1);
    createChart3 = function (chartsData) {
        var chart2Series = chartsData["chart3Series"];
        var xAxisCategories2 = chartsData["xAxisCategory"];
        var xAxisLable = chartsData["xAxisLable"];

        $('#container3').highcharts({
        chart: {
            type: 'column',
            inverted:false
        },
        title: {
            text: chart2Title,
            style:{
                    fontSize: '16px'
                }            
        },
        credits: {
            enabled: false
        },
        xAxis: {
            categories: xAxisCategories2,
            crosshair: true,
            title: {
                text: xAxisLable
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Value in Lakhs'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            },
            series: {
                cursor: 'pointer',
                point: {
                    events: {
                        click: function () {
                           //alert('Category: ' + this.category + ', value: ' + this.y);
                            /*$('#container3').show();
                            draw_chart3(this.category);*/
                        }
                    }
                }
            }
        },
        series: chart2Series
    });
        
    }


    var data = 'lost_for='+lost_for+'&region='+region+'&segment='+segment+'&report_by='+report_by+'&from_date='+from_date+'&to_date='+to_date+'&vtime='+vtime+'&duration='+duration+'&duration_text='+duration_text;
    //alert(data);
    $.ajax({
        url: SITE_URL + 'static_getOpportunityLostChart3Data',
        type:"POST",
        data:data,
        dataType:'json', 
        success: function(data){
            //alert(data);
            createChart3(data);
            $("#pcont").css("opacity",1);
            $("#loaderID").css("opacity",0);
        }
    });
    
}
$(document).on('change','.report_bys',function(){
    var report_by =$('input[name=report_by]:checked').val();
    filterReport();
});
/*$(document).on('change','.timeline',function(){
     var report_by =$('input[name=report_by]:checked').val();
     $('#container2, #container3').html('').hide();
     var timeline =$(this).val();
    // alert(report_by);
});*/
var vtime =$('.btn-success').attr('data-id');
</script>