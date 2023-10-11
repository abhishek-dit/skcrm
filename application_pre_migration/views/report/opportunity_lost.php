<?php $this->load->view('commons/main_template', $nestedView);

?>
<div id="loaderID" style="position:fixed; top:50%; left:58%; z-index:2; opacity:0"><img src="<?php echo assets_url(); ?>images/ajax-loading-img.gif" /></div>

<div class="row"> 
    <div class="col-sm-12 col-md-12">
        <div class="block-flat">
            <div class="content">
                <form method="post" >
                    <div class="header">
                        <div class="row">
                            <div class="form-group">
                                    <div class="col-sm-12">
                                     <?php  if(in_array($this->session->userdata('role_id'),margin_allowed_roles())) { ?>  
                                        <div class="col-sm-2">
                                            <select class="form-control region select2 " style="width:100%" name="region">
                                                <option value="">Select Region</option>
                                                <?php
                                                foreach ($region as $reg) {
                                                    $selected='';
                                                    if($reg['location_id']==@$searchFilters['region'])
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
                                    <?php }
                                    else
                                    { ?>
                                        <input type="hidden" class="region" name="region" value="">
                                 <?php   } 
                                        if(count($users)>1) { ?>
                                        <div class="col-sm-2">                            
                                            <select class="form-control users select2" style="width:100%" name="users">
                                                <option value="">Select All Users</option>
                                                <?php
                                                foreach ($users as $us) {
                                                    echo '<option value="'.$us['user_id'].'">'.$us['first_name'].' ('.$us['employee_id'].')'.'</option>';
                                                }
                                                ?>
                                            </select>                        
                                       </div> 
                                       <?php } else
                                        { ?>
                                            <input type="hidden" class="users" name="users" value="">
                                       <?php   } ?>
                                        <div class="col-sm-2">
                                            <select class="form-control segment select2 " style="width:100%" name="region">
                                                <option value="">Select Segment</option>
                                                <?php
                                                foreach ($segment as $seg) {
                                                    $selected='';
                                                    if($seg['group_id']==@$searchFilters['segment'])
                                                    {
                                                        $selected='selected';
                                                    }
                                                    else
                                                    {
                                                        $selected='';
                                                    }
                                                    echo '<option value="'.$seg['group_id'].'"'.$selected.'>'.$seg['name'].'</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-sm-2">
                                            <select class="form-control duration " style="width:100%" name="duration">
                                                
                                            </select>
                                        </div>
                                        <div class="col-sm-4 relative">
                                            <div class="btn-group">
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
                        <div class="col-sm-12">
                            <div class="col-sm-offset-9 col-sm-3">
                                <!-- <span class="span"><button class="btn btn-success">back</button></span>
                                <span class="span1 hidden"><button class="btn btn-success">back</button></span> -->
                                <span class="levels" id="level1"><a style="color:blue">Level1 </a><i class="fa fa-angle-double-right"></i></span>
                                <span class="levels" id="level2"><a id="button2" style="color:blue">Level2 </a></span>
                                <span class="levels hidden" id="level3"><i class="fa fa-angle-double-right"></i><a disabled style="color:black"> Level3</a></span>
                            </div>
                        </div>
                    </div>
                    <div class="content">
                        <div>
                            <div class="row slider">
                                <div class="col-sm-6">
                                    <div class="col-md-11" align="center">
                                        <div id="container1" style=" margin: 0 auto"></div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="col-md-11" align="left">
                                        <div id="container4" style=" margin: 0 auto"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 reason_legend">
                                    <div class="col-md-11" align="center">
                                        <div class="orders" ></div>
                                    </div>
                                </div>
                                <div class="col-sm-6 comp_legend">
                                    <div class="col-md-11" align="left">
                                        <div class="pre_orders"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row slider2">
                                <div class="col-sm-12">
                                    <div class="col-md-11" align="center">
                                        <div id="container2" style=" margin: 0 auto"></div>
                                    </div>
                                </div>  
                            </div>
                            <div class="row slider3">
                                <div class="col-sm-12">
                                    <div class="col-md-11" align="center">
                                        <div id="container3" style=" margin: 0 auto"></div>
                                    </div>
                                </div>  
                            </div>
                        </div>  
                    </div>
                </form>
            </div>
        </div>              
    </div>
</div>

<style type="text/css">
    .radio-inline{ padding-left: 10px !important;}
    .radio-inline input[type="radio"]{ margin: 0px 4px 0px 0px;}
    a{
        cursor: pointer;
    }
    .input-color {
    position: relative;
}
.input-color input {
    padding-left: 20px;
}
/*.orders {
    width: 10px;
    height: 10px;
    display: inline-block;
    position: absolute;
    left: 5px;
    top: 5px;
}*/
}
</style>

<?php 
$this->load->view('commons/main_footer.php', $nestedView); ?>

<script type="text/javascript">

Highcharts.setOptions({ colors: [ '#42A5F5','#A1887F','#FFD54F','#3F51B5','#FF9800','#F44336', '#4CAF50', '#9C27B0', '#795548', '#FFEB3B', '#CDDC39']});
filterDuration();
$(document).on('change','.duration,.users,.segment',function(){
     $('.levels').addClass('hidden');
    //$('.span').addClass('hidden');
    $('.slider').show();
    $('.comp_legend').show();
    $('.reason_legend').show();
    var duration=$('.duration').val();
  //  var region_filter=$('.region').val();
    /*var data='&region='+region;
     $.ajax({
        url: SITE_URL + 'dependent_users',
        type:"POST",
        data:data,
     //   dataType:'json', 
        success: function(data){

            $('.users').html(data);
            
        }
    });*/
    var users=$('.users').val();
    filterReport();
});
$(document).on('change','.region',function(){

    $('.levels').addClass('hidden');
    $('.slider').show();
    $('.comp_legend').show();
    $('.reason_legend').show();
    var region=$('.region').val();
    var data='&region='+region;
    $.ajax({
        url: SITE_URL + 'dependent_users',
        type:"POST",
        data:data,
        success: function(data){
            $('.users').html('');
            $('.users').html(data);
            
        }
    });
    filterReport();
});
$('.levels').addClass('hidden');
$('#level2').click(function(){
    $("#pcont").css("opacity",0.5);
    $("#loaderID").css("opacity",1);
    $('.slider2').show();
    $('.slider3').hide();
    $('.slider').hide();
    $('#level3').addClass('hidden');
    $('#button2').attr('disabled',true);
    $('#button2').css('color','black');
    $("#pcont").css("opacity",1);
    $("#loaderID").css("opacity",0);
    //$('.levels').removeClass('hidden');
    //$('.span').addClass('hidden');
});
$('#level1').click(function(){
    $("#pcont").css("opacity",0.5);
    $("#loaderID").css("opacity",1);
    $('.slider2').hide();
    $('.slider3').hide();
    $("#pcont").css("opacity",1);
    $("#loaderID").css("opacity",0);
    $('.slider').show();
    $('.comp_legend').show();
    $('.reason_legend').show();
    $('#level2').addClass('hidden');
    $('#button2').attr('disabled',false);
    $('#button2').css('color','blue');
    $('.levels').addClass('hidden');
   
    //$('.span').addClass('hidden');
});
var vtime='y';
var from_date, to_date;
$('.timeline').click(function(){
    $('.levels').addClass('hidden');
    //$('.span').addClass('hidden');
    $('.slider').show();
    var tl = $(this).attr('data-id');
    vtime = tl;
    $('#date_from').val('');
    $('#date_to').val('');
    $('.comp_legend').show();
    $('.reason_legend').show();
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
        url: SITE_URL+'get_filter_duration',
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
    $('.pre_orders').html('');
    $('.orders').html('');
    var from_date = $('#date_from').val();
    var to_date = $('#date_to').val();
    var report_by =$('input[name=report_by]:checked').val();
    var vtime =$('.btn-success').attr('data-id');
    var duration=$('.duration').val();
    var duration_text=$('.duration option:selected').text();
    var users=$('.users ').val();
    var region_filter=$('.region').val();
    var segment=$('.segment').val();
   // alert(region_filter);
    var data = 'from_date='+from_date+'&to_date='+to_date+'&vtime='+vtime+'&report_by='+report_by+'&duration='+duration+'&duration_text='+duration_text+'&users='+users+'&region_filter='+region_filter+'&segment='+segment;
    //alert(data);
    $.ajax({
        url: SITE_URL + 'opportunity_lost_report_filter',
        type:"POST",
        data:data,
        dataType:'json', 
        success: function(data){
            var reason_legend=data['chart1Data']['reason_legend'];
            var con_legend=data['chart10Data']['competitor_legend'];
            $.each(con_legend,function(i)
            {   
                var comp_arr="<span style='background-color:"+con_legend[i].color_arr+";width: 10px; height: 10px; position: absolute; margin-top: 8px; ' > </span>&nbsp;&nbsp;&nbsp;&nbsp;<span class='competitor_span' style='cursor:pointer'>"+con_legend[i].name+" ("+con_legend[i].value+" L)</span>&nbsp;&nbsp;";
                $(".pre_orders").append(comp_arr);
            });
            $.each(reason_legend,function(i)
            {   
                var reason_arr="<span style='background-color:"+reason_legend[i].color_arr+";width: 10px; height: 10px; position: absolute; margin-top: 8px; ' > </span>&nbsp;&nbsp;&nbsp;&nbsp;<span class='reason_span' style='cursor:pointer'>"+reason_legend[i].name+" ("+reason_legend[i].value+" L)</span>&nbsp;&nbsp;";
                $(".orders").append(reason_arr);
            });
            getToolStatusChartLevel1(data['chart1Data']);
            competitorpiechart(data['chart10Data']);
            $("#pcont").css("opacity",1);
            $("#loaderID").css("opacity",0);
            $('#container2,#container3').html('').hide();
        }
    });
}
$(document).on('click','.competitor_span',function(){
    var dat=$(this).text();
    var dat1=dat.split('(');
    var val1=dat1[0].trim();
    $('.slider').hide();
    $('#level1').removeClass('hidden');
    $('#level2').removeClass('hidden');
    $('#button2').attr('disabled', true);
    $('#button2').css('color','black');
    $('#level3').addClass('hidden');
    $('.slider2').show();
    $('.comp_legend').hide();
    $('.reason_legend').hide();
    //alert('first click');
    $('#container2').show();
    draw_chart2(val1,2);
    $('#container3').html('').hide();
});
$(document).on('click','.reason_span',function(){
    var dat=$(this).text();
    var dat1=dat.split('(');
    var val1=dat1[0].trim();
    $('.slider').hide();
    //$('.levels').removeClass('hidden');
    $('#level1').removeClass('hidden');
    $('#level2').removeClass('hidden');
    $('#button2').attr('disabled', true);
    $('#button2').css('color','black');
    $('#level3').addClass('hidden');
    $('.slider2').show();
    $('.comp_legend').hide();
    $('.reason_legend').hide();
    //alert('first click');
    $('#container2').show();
    draw_chart2(val1,1);
    $('#container3').html('').hide();
});

var firstPieData = <?php echo $chart1Data;?>;
var competitorpiechartData = <?php echo $chart10Data;?>

getToolStatusChartLevel1(firstPieData);
competitorpiechart(competitorpiechartData);
function getToolStatusChartLevel1(firstPieData) {
 var report_by=1;
 var chart1series=firstPieData['chart1Series'];
 var Lable=firstPieData['label']; 
 var user_role=firstPieData['user_role'];
 var role=<?php echo $this->session->userdata['role_id']; ?>;
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
            pointFormat: '{series.name}: <b>{point.y} Lacs ({point.percentage:.1f}%)</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                point: {
                   events: {
                      click: function(event) {
                         $('.slider').hide();
                         //$('.levels').removeClass('hidden');
                         $('#level1').removeClass('hidden');
                         $('#level2').removeClass('hidden');
                         $('#button2').attr('disabled', true);
                         $('#button2').css('color','black');
                         $('#level3').addClass('hidden');
                         $('.slider2').show();
                         $('.comp_legend').hide();
                         $('.reason_legend').hide();
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
                   // format: '{point.y} L',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    },
                     formatter:function()
                        {
                            if(this.y>0 && this.y<1){
                               return this.y+' L';
                            }
                            else if(this.y>=1)
                            {
                                if(user_role==4 || role==4)
                                {
                                    return (this.y).toFixed(2)+' L'
                                }
                                else
                                {
                                    return Math.round(this.y)+' L';
                                }
                                
                            }
                        }
                },
                showInLegend:false
                
            }
        },
    series: chart1series
});


}
function competitorpiechart(competitorpiechartData)
{
    chart1Series = competitorpiechartData['chart1Series'];
    Lable = competitorpiechartData['label'];
    var user_role=competitorpiechartData['user_role'];
    var role=<?php echo $this->session->userdata['role_id']; ?>;
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
                pointFormat: '{series.name}: <b>{point.y} Lacs ({point.percentage:.1f}%)</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    point: {
                       events: {
                          click: function(event) {
                            $('.slider').hide();
                                $('#level1').removeClass('hidden');
                                $('#level2').removeClass('hidden');
                                $('#button2').attr('disabled', true);
                                $('#button2').css('color','black');
                                $('#level3').addClass('hidden');
                                $('.slider2').show();
                                $('.comp_legend').hide();
                                $('.reason_legend').hide();
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
                       // format: '{point.y} L',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        },
                         formatter:function()
                        {
                            if(this.y>0 && this.y<1){
                               return this.y+' L';
                            }
                            else if(this.y>=1)
                            {
                                if(user_role==4 || role==4)
                                {
                                    return (this.y).toFixed(2)+' L'
                                }
                                else
                                {
                                    return Math.round(this.y)+' L';
                                }
                            }
                        }
                    },
                    showInLegend:false
                    
                }
            },
        series: chart1Series
    });
}
//draw_chart2('A2');
// Draw Second Chart
function draw_chart2(lost_for,report_by)
{
    //alert(tool_status);
    var from_date = $('#date_from').val();
    var to_date = $('#date_to').val();
    var vtime =$('.btn-success').attr('data-id');
    var users=$('.users ').val();
    var duration=$('.duration').val();
    var duration_text=$('.duration option:selected').text();
    var region_filter=$('.region').val();
    var segment=$('.segment').val();
    //alert(report_by);
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
                '<td style="padding:0"><b>{point.y:.2f} L</b></td></tr>',
            footerFormat: '</table>',
            shared: false,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            },
            series: {
                 dataLabels: {
                        enabled: true,
                       // color: '#ffffff',
                       formatter:function()
                        {
                            if(this.y>0 && this.y<1){
                               return this.y;
                            }
                            else if(this.y>=1)
                            {
                                 return Math.round(this.y);
                            }
                        }
                    },
                cursor: 'pointer',
                point: {
                    events: {
                        click: function () {
                            $('.slider2').hide();
                            $('.slider3').show();
                            $('#level1').removeClass('hidden');
                            $('#level2').removeClass('hidden');
                            $('#button2').attr('disabled', false);
                            $('#button2').css('color','blue');
                            $('#level3').removeClass('hidden');
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

    
    var data = 'lost_for='+lost_for+'&report_by='+report_by+'&from_date='+from_date+'&to_date='+to_date+'&vtime='+vtime+'&users='+users+'&duration='+duration+'&duration_text='+duration_text+'&region_filter='+region_filter+'&segment='+segment;
    //alert(data);
    $.ajax({
        url: SITE_URL + 'getOpportunityLostChart2Data',
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
function draw_chart3(lost_for,region,segment,report_by)
{
    //alert(region);
    var users=$('.users ').val();
    var from_date = $('#date_from').val();
    var to_date = $('#date_to').val();
    var vtime =$('.btn-success').attr('data-id');
    var duration=$('.duration').val();
    var duration_text=$('.duration option:selected').text();
    var region1=region.replace("&", "/AB");
    var chart2Title = segment+' Products list in '+region;
    var region_filter=$('.region').val();
    var seg=$('.segment').val();
    $("#pcont").css("opacity",0.5);
    $("#loaderID").css("opacity",1);
    createChart3 = function (chartsData) {
       // console.log(chartsData);
        var chart2Series = chartsData["chart3Series"];
        var xAxisCategories2 = chartsData["xAxisCategory"];
        var xAxisLable = chartsData["xAxisLable"];

        $('#container3').highcharts({
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
                text: 'Value in Lakhs'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:.2f} L</b></td></tr>',
            footerFormat: '</table>',
            shared: false,
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


    var data = 'lost_for='+lost_for+'&region='+region1+'&segment='+segment+'&report_by='+report_by+'&from_date='+from_date+'&to_date='+to_date+'&vtime='+vtime+'&users='+users+'&duration='+duration+'&duration_text='+duration_text+'&region_filter='+region_filter+'&seg='+seg;
    //alert(data);
    $.ajax({
        url: SITE_URL + 'getOpportunityLostChart3Data',
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
/*$(document).on('change','.report_bys',function(){
    var report_by =$('input[name=report_by]:checked').val();
    filterReport();
});*/
/*$(document).on('change','.timeline',function(){
     var report_by =$('input[name=report_by]:checked').val();
     $('#container2, #container3').html('').hide();
     var timeline =$(this).val();
    // alert(report_by);
});*/
var vtime =$('.btn-success').attr('data-id');


</script>
<style type="text/css">
    text[style="cursor:pointer;color:#909090;font-size:9px;fill:#909090;"]{ display: none;}
</style>