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
                                <!-- <label class="col-sm-1 control-label" style="margin-top:4px;"> Region : </label>
                                <div class="col-sm-2">
                                    <select class="form-control region" style="width:100%" name="region">
                                        <option value="">Select Region</option>
                                        <?php
                                       /* foreach ($warehouses as $wh_row) {
                                            echo '<option value="'.$wh_row['wh_id'].'">'.$wh_row['wh_code'].' - '.$wh_row['name'].'</option>';
                                        }*/
                                        ?>
                                    </select>
                                </div> -->
                                <div class="col-md-offset-11 col-md-1"><a href="<?php echo SITE_URL.'download_stock_in_hand_xl'?>" class="btn btn-primary" title="Download Product Excel"><i class="fa fa-cloud-download"></i></a></div>
							</div>
						</div>
					</div>
				</div>
				<div class="content">
								
					<div>
						<div class="row">
							<div class="col-sm-12">
								<div class="col-md-5" align="center">
									<div id="container1" style="width:300px; height: 330px;margin: 0 auto"></div>
								</div>
                                <div class="col-md-7" align="center">
                                    <div id="container2" style="height:330px; margin: 0 auto"></div>
                                </div>
                                
							</div>	
						</div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="col-md-11" align="center">
                                    <div id="container4" style=" margin: 0 auto"></div>
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

//var icrm = $.noConflict();
$('.zone').change(function(){
    var zone = $('.region').val();
    var data = 'zone='+zone;
    $.ajax({
        url: SITE_URL + 'getWarehousesByZone',
        type:"POST",
        data:data,
        success: function(data){
            //alert(data);
            $('.warehouse').html(data);
            filterTools();

        }
    });
});

$('.warehouse,.modality').change(function(){
	
    filterTools();
});

function filterTools()
{
    var zone = $('.region').val();
    var warehouse = $('.warehouse').val();
    var modality = $('.modality').val();
    var data = 'zone='+zone+'&warehouse='+warehouse+'&modality='+modality;
    //alert(zone+'-->'+warehouse+'-->'+modality);
    // remove second, third level chart
    $('#container2, #container3').html('').hide();
    $("#pcont").css("opacity",0.5);
    $("#loaderID").css("opacity",1);
    $.ajax({
        url: SITE_URL + 'getToolStatusChart',
        type:"POST",
        data:data,
        dataType:'json', 
        success: function(data){
            //alert(data);
            getProductWiseCategory(data)
            $("#pcont").css("opacity",1);
            $("#loaderID").css("opacity",0);
        }
    });
}

var categoryWiseData = <?php echo $categoryWiseData;?>;
getProductWiseCategory(categoryWiseData);
function getProductWiseCategory(categoryWiseData) {

    
$('#container1').highcharts({
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: 1,
        plotShadow: false,
        type: 'pie'
    },
    title: {
            text: 'Category Wise Stock'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                
                allowPointSelect: true,
                cursor: 'pointer',
                point: {
                   events: {
                      click: function(event) {
                          //alert('first click');
                         /*$('#container2').show();
                         draw_chart2(this.options.name);*/
                         $('#container4').html('').hide();
                      }
                   }
                },
                dataLabels: {
                     distance: -30,
                    enabled: true,
                    format: '{point.y}',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                },
                 showInLegend: true
                
            }
        },
    series: categoryWiseData
});


}
var chart2Data = <?php echo $chart2Data;?>;
draw_chart2(chart2Data);
function draw_chart2(chart2Data) {

    
   var chart2Series = chart2Data;

    var chart2Series = chart2Data["chart1Series"];
    var xAxisCategory = chart2Data["xAxisCategory"];
    var xAxisLable = chart2Data["xAxisLable"];

    $('#container2').highcharts({
        chart: {
            type: 'column',
            inverted:false,
            zoomType:'xy'
        },
        title: {
            text: "Segment wise  Stock",
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
            /*title: {
                text: xAxisLable
            }*/
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Stock'
            }
        },
        tooltip: {
            headerFormat: '',
            pointFormat: '<b>{point.y} </b>',
            footerFormat: '',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                borderWidth: 0,
                dataLabels: {
                    enabled: true
                }
            },
            series: {
                cursor: 'pointer',
                point: {
                    events: {
                        click: function () {
                          $('#container4').show();
                        //  alert(this.category);
                        draw_chart4(this.category);
                        }
                    }
                }
            }
        },
        series: chart2Series
    });

}



// Draw Second Chart1
function draw_chart4(segment)
{
    //alert(tool_status);
    var chart2Title = segment+' Segment Stock ';
    $("#pcont").css("opacity",0.5);
    $("#loaderID").css("opacity",1);
    createChart3 = function (chartsData) {
        //alert(chartsData);
        var chart2Series = chartsData["chart1Series"];
        var xAxisCategories2 = chartsData["xAxisCategory"];
        var xAxisLable = chartsData["xAxisLable"];

        $('#container4').highcharts({
        chart: {
            type: 'column',
            inverted:false,
            zoomType:'xy'
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
                text: 'Stock in Hand'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y}</b></td></tr>',
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

    var region = $('.region').val();
    var data = 'region='+region+'&segment='+segment;
    //alert(data);
    $.ajax({
        url: SITE_URL + 'getStockInHandChart3Data',
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