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
                                <div class="col-sm-1"></div>
                                <?php if(count(@$region)!='')
                                { ?>
                                 <label class="col-sm-1 control-label" style="margin-top:4px;"> Region : </label>
                                 <div class="col-sm-2">
                                    <select class="form-control region select2" style="width:100%" name="region">
                                        <option value="">Select Region</option>
                                        <?php
                                        foreach ($region as $reg) {
                                            $selected='';
                                            if($reg['location_id']==$searchFilters['regions'])
                                            {
                                                $selected='selected';
                                            }
                                            else
                                            {
                                                $selected='';
                                            }
                                            echo '<option value="'.$reg['location_id'].'"'.$selected.'>'.$reg['location'].'</option>';
                                        }
                                        ?>
                                    </select>
                                </div> 
                                <?php } ?>
                                <label class="col-sm-1 control-label" style="margin-top:4px;"> Month : </label>
                                 <div class="col-sm-2">
                                    <select class="form-control month select2" style="width:100%" name="month">
                                        <option value="">Select Month</option>
                                        <?php
                                        foreach ($months_array as $key =>$value) {
                                            $selected='';
                                            if($key==$searchFilters['cur_month'])
                                            {
                                                $selected='selected';
                                            }
                                            else
                                            {
                                                $selected='';
                                            }
                                            echo '<option value="'.$key.'"'.$selected.'>'.$value.'</option>';
                                        }
                                        ?>
                                    </select>
                                </div> 
                                 <label class="col-sm-1 control-label" style="margin-top:4px;"> Years : </label>
                                 <div class="col-sm-2">
                                    <select class="form-control year select2" style="width:100%" name="year">
                                        <option value="">Select Year</option>
                                        <?php
                                        for($i=2016;$i<=date('Y');$i++) {
                                            $selected='';
                                            if($i==$searchFilters['cur_year'])
                                            {
                                                $selected='selected';
                                            }
                                            else
                                            {
                                                $selected='';
                                            }
                                            echo '<option value="'.$i.'"'.$selected.'>'.$i.'</option>';
                                        }
                                        ?>
                                    </select>
                                </div> 
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12"><br>
                            <div class="col-md-4" align="center">
                                <div id="container1" style="width: 333px; height: 342px;margin: 0 auto"></div>
                            </div>
                            <div class="col-md-4">
                                <div id="container2" style="width: 333px; height: 310px;margin: 0 auto"></div>
                            </div>
                            <div class="col-md-4" align="center">
                                <div id="container3" style="width: 333px;margin: 0 auto"></div>
                            </div>
                        </div> 
                    </div>
                    <div class="row">
                        <div class="col-sm-12"><br>
                            <div id="container4" style="width: 1000px; height: 350px;margin : 0 auto"></div>
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
//var vtime='m';
//var from_date, to_date;
var chart1Data = <?php echo $chart1Data;?>;
var chart2Data = <?php echo $chart2Data;?>;
var chart3Data = <?php echo $chart3Data;?>;
var chart4Data = <?php echo $chart4Data;?>;
$(document).on('change','.month,.year,.region',function(){

   $("#pcont").css("opacity",0.5);
    $("#loaderID").css("opacity",1);
    var cur_month = $('.month').val();
    var cur_year = $('.year').val();
    var region = $('.region').val();
    //alert(vtime+from_date+to_date);
    var data = 'cur_month='+cur_month+'&cur_year='+cur_year+'&region='+region;
    //alert(data);
    $.ajax({
        url: SITE_URL + 'openOpportunitiesFilterData',
        type:"POST",
        data:data,
        dataType:'json', 
        success: function(data){
          //  var arr=jQuery.parseJSON(data);
           // console.log(data.chart1Data);
            openOrdersChartLevel1(data['chart1Data']);
            openOrdersChartLevel2(data['chart2Data']);
            openOrdersChartLevel3(data['chart3Data']);
            openOrdersChartLevel4(data['chart4Data']);
            $("#pcont").css("opacity",1);
            $("#loaderID").css("opacity",0);
        }
    });
});




openOrdersChartLevel1(chart1Data);
function openOrdersChartLevel1(chart1Data) {

    
      var chart1Series = chart1Data;
    
        var chart1Series = chart1Data["chart1Series"];
        var xAxisCategory = chart1Data["xAxisCategory"];
        var xAxisLable = chart1Data["xAxisLable"];

        $('#container1').highcharts({
        chart: {
            type: 'column',
            inverted:false
        },
        title: {
            text: "Week Wise Open Opportunities",
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
                text: 'Opportunites'
            }
        },
        tooltip: {
            headerFormat: '',
            pointFormat: '<b>{point.y:.1f} L</b>',
            footerFormat: '',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                borderWidth: 0,
                /*dataLabels: {
                    enabled: true
                }*/
            },
            series: {
                cursor: 'pointer',
                point: {
                    events: {
                        click: function () {
                          // alert('Category: ' + this.category+' Y val'+this.series.name);
                           // $('#container2').show();
                          //  draw_chart2(this.category,this.series.name);
                        }
                    }
                }
            }
        },
        series: chart1Series
    });

}

openOrdersChartLevel2(chart2Data);
function openOrdersChartLevel2(chart2Data) {
     var chart2Series = chart2Data;
    $('#container2').highcharts({
        chart: {
            type: 'waterfall'
        },

        title: {
            text: 'Value In Lakhs By Segment',
             style:{
                    fontSize: '16px'
                }  
        },

        xAxis: {
            crosshair: true,
            type: 'category'
        },
        credits: {
            enabled: false
        },
        yAxis: {
             min: 0,
            title: {
                text: 'Lakhs'
            }
        },

        legend: {
            enabled: false
        },

        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0"></td>' +
                '<td style="padding:0"><b>{point.y} L</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },

        series: [{
            upColor: Highcharts.getOptions().colors[2],
            color: Highcharts.getOptions().colors[3],
            format:'{point.y:.1f}%',
            data: chart2Series,
           /* dataLabels: {
                enabled: true,
                formatter: function () {
                    return Highcharts.numberFormat(this.y / 1000, 0, ',') + 'k';
                },
                style: {
                    fontWeight: 'bold'
                }
            },*/
            pointPadding: 0.2,
            borderWidth:0
        }]
    });
}


openOrdersChartLevel3(chart3Data);
function openOrdersChartLevel3(chart3Data) {
     var chart3Series = chart3Data;
    $('#container3').highcharts({
        chart: {
            type: 'waterfall'
        },

        title: {
            text: 'Value In Lakhs By Stage',
             style:{
                    fontSize: '16px'
                }  
        },

        xAxis: {
            crosshair: true,
            type: 'category'
        },
        credits: {
            enabled: false
        },
        yAxis: {
             min: 0,
            title: {
                text: 'Lakhs'
            }
        },

        legend: {
            enabled: false
        },

        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0"></td>' +
                '<td style="padding:0"><b>{point.y}L</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },

        series: [{
            upColor: Highcharts.getOptions().colors[2],
            color: Highcharts.getOptions().colors[3],
            data: chart3Series,
            /*dataLabels: {
                enabled: true,
                formatter: function () {
                    return Highcharts.numberFormat(this.y / 1000, 0, ',') + 'k';
                },
                style: {
                    fontWeight: 'bold'
                }
            },*/
            pointPadding: 0.2,
            borderWidth:0
        }]
    });
}


//console.log(chart4Data);
openOrdersChartLevel4(chart4Data);
function openOrdersChartLevel4(chart4Data)
    {
        var chart4Series = chart4Data;
        var chart4Series = chart4Data["chart4Series"];
        var xAxisCategory = chart4Data["xAxisCategory"];
        var xAxisLable = chart4Data["xAxisLable"];

        $('#container4').highcharts({
        chart: {
            type: 'column',
            inverted:false
        },
        title: {
            text: "Segment Wise Open Opportunites ",
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
                        
                    }
                }
            }
        },
        series: chart4Series
    });
}
</script>