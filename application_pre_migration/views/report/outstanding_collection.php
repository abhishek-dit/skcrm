<?php $this->load->view('commons/main_template', $nestedView);

?>
<div id="loaderID" style="position:fixed; top:50%; left:58%; z-index:2; opacity:0"><img src="<?php echo assets_url(); ?>images/ajax-loading-img.gif" /></div>

<div class="row"> 
	<div class="col-sm-12 col-md-12">
		<div class="block-flat">
			<div class="content">
				<div class="header hidden">
					
					<div class="row">
						<div class="form-group">
							<div class="col-sm-12">
                                <label class="col-sm-1 control-label" style="margin-top:4px;"> Region : </label>
                                <div class="col-sm-2">
                                    <select class="form-control region" style="width:100%" name="region">
                                        <option value="">Select Region</option>
                                        <?php
                                       /* foreach ($warehouses as $wh_row) {
                                            echo '<option value="'.$wh_row['wh_id'].'">'.$wh_row['wh_code'].' - '.$wh_row['name'].'</option>';
                                        }*/
                                        ?>
                                    </select>
                                </div>
                                
							</div>
						</div>
					</div>
				</div>
				<div class="content">
								
					<div>
						<div class="row">
							<div class="col-sm-12">
								<div class="col-md-11" align="center">
									<div id="container1" style=" margin: 0 auto"></div>
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


var chart1Data = <?php echo $chart1Data;?>;
//alert(chart1Data);
outStandingCollectionChartLevel1(chart1Data);
function outStandingCollectionChartLevel1(chartsData) {

    
        var chart1Series = chartsData["chart1Series"];
        var xAxisCategory = chartsData["xAxisCategory"];
        var xAxisLable = chartsData["xAxisLable"];

        $('#container1').highcharts({
        chart: {
            type: 'column',
            inverted:false
        },
        title: {
            text: 'Outstanding Collections',
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
                text: 'Value in Lakhs'
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
                           //alert(', value: ' + this.category);
                            $('#container2').show();
                            $('#container3').hide().html('');
                            draw_chart2(this.category);
                        }
                    }
                }
            }
        },
        series: chart1Series
    });


}
//draw_chart2('A2');
// Draw Second Chart
function draw_chart2(category)
{
    //alert(tool_status);
    var chart2Title = 'Customer wise Outstanding in '+category;
    $("#pcont").css("opacity",0.5);
    $("#loaderID").css("opacity",1);
    createChart = function (chartsData) {

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
                text: 'Value in Lakhs'
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
                           //alert('Category: ' + this.category);
                            $('#container3').show();
                            draw_chart3(category,this.category);
                        }
                    }
                }
            }
        },
        series: chart2Series
    });
        
    }

    var data = 'region_name='+category;
    //alert(data);
    $.ajax({
        url: SITE_URL + 'getOutStandingCollectionChart2Data',
        type:"POST",
        data:data,
        dataType:'json', 
        success: function(data){
            //alert(data);
            createChart(data);
            $("#pcont").css("opacity",1);
            $("#loaderID").css("opacity",0);
        }
    });
    
}

// Draw Second Chart
function draw_chart3(region_name,customer_name)
{
    //alert(tool_status);
    var chart2Title = customer_name+' Outstanding';
    $("#pcont").css("opacity",0.5);
    $("#loaderID").css("opacity",1);
    createChart3 = function (chartsData) {
        var chart3Series = chartsData["chart3Series"];
        var xAxisCategory = chartsData["xAxisCategory"];
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
            categories: xAxisCategory,
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
            headerFormat: '<span style="font-size:10px">So Number :{point.key}</span><table>',
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
                           //alert('Category: ' + this.category + ', value: ' + this.y);
                            /*$('#container3').show();
                            draw_chart3(this.category);*/
                        }
                    }
                }
            }
        },
        series: chart3Series
    });
        
    }

    var data = 'region_name='+region_name+'&customer_name='+customer_name;
    //alert(data);
    $.ajax({
        url: SITE_URL + 'getOutStandingCollectionChart3Data',
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
</script>