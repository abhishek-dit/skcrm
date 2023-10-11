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
                                    <?php  if(in_array($this->session->userdata('role_id'),margin_allowed_roles())) { ?>  
                               
                                 <!-- <label class="col-sm-1 control-label" style="margin-top:4px;"> Region : </label> -->
                                 <div class="col-sm-2">
                                    <select class="form-control region select2" style="width:100%" name="region">
                                        <option value="">Select Region</option>
                                        <?php
                                        foreach ($region as $reg) {
                                            $selected='';
                                            if($reg['location_id']==$searchFilters['region'])
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
                                            <option value="">Select Users</option>
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
                                    <select class="form-control duration " style="width:100%" name="duration">
                                    </select>
                                </div>
                                 <div class="col-sm-5">
                                    <div class="btn-group">
                                        <!-- <input type="button" data-id="a" name="timeline" class="timeline btn btn-success" checked  value="All"> -->
                                        <input type="button" data-id="w" name="timeline" class="timeline btn btn-default" value="Week">
                                        <input type="button" data-id="m" name="timeline" class="timeline btn btn-default"  value="Month">
                                        <input type="button" data-id="q" name="timeline" class="timeline btn btn-default" value="Quarter">
                                        <input type="button" data-id="y" name="timeline" class="timeline btn btn-success" value="Year">
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
                            <div class="col-sm-12">
                                <div class="col-md-11" align="center">
                                    <div id="container1" style=" margin: 0 auto"></div>
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
                        <div class="row hidden tab">
                            <div class="col-sm-12">
                                <div class="col-sm-offset-9 col-sm-2" align="right">
                                    <span class="" id="customer_switch"><a style="color:blue" >View Product List</a></span>
                                </div>
                                <div class="col-md-offset-2 col-md-8" align="center">
                                    <div id="customer">
                                        
                                        <div class="table-responsive ">
                                        <span class="table_name"></span>
                                            <table class="table table-bordered hover section" style="background-color: '#cfac6b' !important;">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center"  width="2%"><strong>S.No </strong></th>
                                                        <th class="text-center"  width="8%"><strong>Customer </strong></th>
                                                       <th class="text-center" width='3%'><strong>Order Value</strong></th>
                                                    </tr>
                                                </thead>  
                                                <tbody class="orders">
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>  
                        </div>
                         <div class="row hidden pro">
                            <div class="col-sm-12">
                                <div class="col-sm-offset-9 col-sm-2" align="right">
                                    <span class="" id="product_switch"><a style="color:blue">View Customer List</a></span>
                                </div>
                                <div class="col-md-offset-2 col-md-8" align="center">
                                    
                                    <div id="">
                                        <div class="table-responsive ">
                                        <span class="table_name">Open Order List</span>
                                            <table class="table table-bordered hover product_section" style="background-color: '#cfac6b' !important;">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center"  width="2%"><strong>S.No </strong></th>
                                                        <th class="text-center"  width="8%"><strong>Product Code</strong></th>
                                                        <th class="text-center"  width="8%"><strong>Product</strong></th>
                                                        <th class="text-center"  width="8%"><strong>Qty </strong></th>
                                                        <th class="text-center" width='3%'><strong>Order Value</strong></th>
                                                    </tr>
                                                </thead>  
                                                <tbody class="product_orders">
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>  
                        </div>
                        <div class="col-sm-12">
                            <div class="order">
                                <h1 class="hello"></h1>
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
    a{
        cursor: pointer;
    }
</style>

<?php 
$this->load->view('commons/main_footer.php', $nestedView); ?>

<script type="text/javascript">

Highcharts.setOptions({ colors: [ '#42A5F5','#A1887F','#FFD54F','#3F51B5','#FF9800','#F44336', '#4CAF50', '#9C27B0', '#795548', '#FFEB3B', '#CDDC39']});
filterDuration();
$(document).on('change','.duration,.users',function(){
    $('.levels').addClass('hidden');
    $('.tab').addClass('hidden');
    $('.orders').html('');
    $('.product_orders').html('');
    $('.slider').show();
    filterReport();
});

$(document).on('change','.region',function(){

    $('.levels').addClass('hidden');
    $('.tab').addClass('hidden');
    $('.orders').html('');
    $('.product_orders').html('');
    $('.slider').show();
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
$('.tab').addClass('hidden');
$('.pro').addClass('hidden');
$('#level2').click(function(){
    $("#pcont").css("opacity",0.5);
    $("#loaderID").css("opacity",1);
    $('.slider2').show();
    //$('.slider3').hide();
    $('.slider').hide();
    $('#level3').addClass('hidden');
    $('#button2').attr('disabled',true);
    $('#button2').css('color','black');
    $('.orders').html('');
    $('.product_orders').html('');
    $('.tab').addClass('hidden');
    $('.pro').addClass('hidden');
    //sleep(1000);
    $("#pcont").css("opacity",1);
    $("#loaderID").css("opacity",0);
    //$('.levels').removeClass('hidden');
    //$('.span').addClass('hidden');
});
$('#level1').click(function(){
    $("#pcont").css("opacity",0.5);
    $("#loaderID").css("opacity",1);
    $('.slider2').hide();
    //$('.slider3').hide();
   
    $('.slider').show();

    $('#level2').addClass('hidden');
    $('#button2').attr('disabled',false);
    $('#button2').css('color','blue');
    $('.levels').addClass('hidden');
    $('.orders').html('');
    $('.product_orders').html('');
    $('.tab').addClass('hidden');
    $('.pro').addClass('hidden');
    //sleep(1000);
    $("#pcont").css("opacity",1);
    $("#loaderID").css("opacity",0);
    //$('.span').addClass('hidden');
});

var vtime='y';
var from_date, to_date;
$('.timeline').click(function(){
    $('.tab').addClass('hidden');
    $('.pro').addClass('hidden');
    $('.orders').html('');
    $('.product_orders').html('');
    $('.levels').addClass('hidden');
    //$('.span').addClass('hidden');
    $('.slider').show();
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

function filterReport() {
   
    $("#pcont").css("opacity",0.5);
    $("#loaderID").css("opacity",1);
    $('.orders').html('');
    $('.product_orders').html('');
    var from_date = $('#date_from').val();
    var to_date = $('#date_to').val();
    var vtime =$('.btn-success').attr('data-id');
    var duration=$('.duration').val();
    var duration_text=$('.duration option:selected').text();
    var users=$('.users ').val();
    var region=$('.region').val();
    //alert(vtime+from_date+to_date);
    var data = 'from_date='+from_date+'&to_date='+to_date+'&vtime='+vtime+'&duration='+duration+'&duration_text='+duration_text+'&users='+users+'&region='+region;
    //alert(data);
    $.ajax({
        url: SITE_URL + 'openOrderChart1Data',
        type:"POST",
        data:data,
        dataType:'json', 
        success: function(data){
            //alert(data);
            openOrdersChartLevel1(data);
            $("#pcont").css("opacity",1);
            $("#loaderID").css("opacity",0);
            $('#container2,#container3').html('').hide();
        }
    });
}
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
            //   $('.duration').addClass('hidden');
            //    $('.duration').html('');
                $('.duration').removeClass('hidden');
                $('.duration').html(data);
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

var chart1Data = <?php echo $chart1Data;?>;
openOrdersChartLevel1(chart1Data);
function openOrdersChartLevel1(chart1Data) {

    
      var chart1Series = chart1Data;
    
        var chart1Series = chart1Data["chart1Series"];
        var xAxisCategory = chart1Data["xAxisCategory"];
        var xAxisLable = chart1Data["xAxisLable"];

        $('#container1').highcharts({
        chart: {
            type: 'column',
            inverted:false,
            zoomType:'xy'
        },
        title: {
            text: chart1Data["lable"],
            style:{
                    fontSize: '16px'
                }            
        },
        credits: {
            enabled: true
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
                text: 'Value In Lacs'
            }
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.y} Lacs </b>',
            },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0,
                stacking : 'normal',
                 dataLabels: {
                        enabled: true,
                       // color: '#ffffff'
                    }
            },
            series: {
                cursor: 'pointer',
                point: {
                    events: {
                        click: function () {
                            var s =this.series.name;
                            s = s.substring(0, s.indexOf('(')).trim();
                            if(s=='Fresh not cleared'||s=='Fresh open orders cleared')
                            {
                               // alert(this.series.name);
                                 $('.slider').hide();
                                 $('.orders').html('');
                                 $('.product_orders').html('');
                                 //$('.levels').removeClass('hidden');
                                 $('#level1').removeClass('hidden');
                                 $('#level2').removeClass('hidden');
                                 $('#button2').attr('disabled', true);
                                 $('#button2').css('color','black');
                                 $('#level3').addClass('hidden');
                                 $('.slider2').show();
                               //alert('Category: ' + this.category+' Y val'+this.series.name);
                                $('#container2').show();
                                draw_chart2(this.category,s);
                            }
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
function draw_chart2(category,status)
{
   // alert(region+category);
    var from_date = $('#date_from').val();
    var to_date = $('#date_to').val();
    var vtime =$('.btn-success').attr('data-id');
    var duration=$('.duration').val();
    var duration_text=$('.duration option:selected').text();
    var users=$('.users ').val();
    var region=$('.region').val();
    var chart2Title = 'Open Orders';
    if(category!='All'){ chart2Title +=' For '+category+' in '+status;}
    $("#pcont").css("opacity",0.5);
    $("#loaderID").css("opacity",1);
    createChart = function (chartsData) {

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
                text: 'Value In Lacs'
            }
        },
         tooltip: {
            pointFormat:'  <b>{point.y} Lacs </b>',
            },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0,
                /* dataLabels: {
                        enabled: true,
                       // color: '#ffffff'
                    }*/
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
                           
                        if(this.series.name=='Present'|| vtime=='y' || vtime=='w') 
                        {
                            $('.slider2').hide();
                            //$('.slider3').show();
                             $('.orders').html('');
                             $('.product_orders').html('');
                            $('#level1').removeClass('hidden');
                            $('#level2').removeClass('hidden');
                            $('#button2').attr('disabled', false);
                            $('#button2').css('color','blue');
                           
                         //alert('Category: ' + this.category+region+category+this.series.name);
                           //$('#container3').show();
                           if(this.category!="ALL")
                           {
                              var segment=this.category;
                           }
                           else
                           {
                              var segment="ALL Segments";
                           }
                              draw_chart3(this.category,category,status,segment);
                        }
                       
                        }
                    }
                }
            }
        },
        series: chart2Series
    });
        
    }

    var data = 'status='+status+'&category='+category+'&from_date='+from_date+'&to_date='+to_date+'&vtime='+vtime+'&duration='+duration+'&duration_text='+duration_text+'&users='+users+'&region='+region;
    //alert(category);
    $.ajax({
        url: SITE_URL + 'openOrderChart2Data',
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

// Draw Third Chart
function draw_chart3(segment,category,status,segment)
{
    var from_date = $('#date_from').val();
    var to_date = $('#date_to').val();
    var vtime =$('.btn-success').attr('data-id');
    var duration=$('.duration').val();
    var duration_text=$('.duration option:selected').text();
    var users=$('.users ').val();
    var region=$('.region').val();
    var segment_check=segment;
    var chart2Title = segment+' Open Orders';
    $("#pcont").css("opacity",0.5);
    $("#loaderID").css("opacity",1);
  
    

    var data = 'status='+status+'&category='+category+'&segment='+segment+'&from_date='+from_date+'&to_date='+to_date+'&vtime='+vtime+'&duration='+duration+'&duration_text='+duration_text+'&users='+users+'&region='+region;
    // /alert(data);
    $.ajax({
        url: SITE_URL + 'openOrderChart3Data',
        type:"POST",
        data:data,
        dataType:'json', 

        success: function(list){
            //$('.tab').removeClass('hidden');
            //$('.hello').text('hello');
            //$('#container3').show();
            $('#customer_switch').click(function(){
                $('.pro').removeClass('hidden');
                $('.tab').addClass('hidden');
                $('.table_name').html('');
                $('.table_name').html("<h4>"+segment_check+" Product Open Orders</h4>");
            });
            $('#product_switch').click(function(){
                $('.tab').removeClass('hidden');
                $('.pro').addClass('hidden');
                $('.table_name').html('');
                $('.table_name').html("<h4>"+segment_check+" Customer Open Orders</h4>");
            });
            var data=list['customers'];
            var pro_data=list['products'];
             $('#level3').removeClass('hidden'); 
             $('.tab').removeClass('hidden');
             $('.pro').addClass('hidden');
             var section = $(this).closest('.section');
            $('.table_name').html("<h4>"+segment_check+" Customer Open Orders</h4>");
            var j=1;
            var total_qty=0;
            var total_value=0;
            $.each(data,function(i)
            {   
                total_value += parseFloat(data[i].total_orders);
                var customer_data="<tr> <td style='font-size: 12;' align='center'>"+j+"</td><td style='font-size: 12;' align='left'>"+ data[i].name +" ("+data[i].region+" )</td><td  style='font-size :!2;' align='right'>"+data[i].total_orders+"</td></tr>";
                $(".orders").append(customer_data);
                j++;

            });
             $('.orders').append("<td  style='font-size :!2;' align='right' colspan=2><strong>Total </strong></td><td  style='font-size :!2;' align='right'><strong>"+Math.round(total_value,2)+" L</strong></td>"); 
            var j=1;
            var total_qty=0;
            var total_value=0;
            $.each(pro_data,function(i)
            {   
                 total_qty += parseFloat(pro_data[i].qty);
                total_value += parseFloat(pro_data[i].total_orders);
                var product_data="<tr> <td style='font-size: 12;' align='center'>"+j+"</td><td style='font-size: 12;' align='left'>"+ pro_data[i].name +"</td><td style='font-size: 12;' align='left'>"+ pro_data[i].description +"</td><td  style='font-size :!2;' align='right'>"+pro_data[i].qty+"</td><td  style='font-size :!2;' align='right'>"+pro_data[i].total_orders+"</td></tr>";
                $(".product_orders").append(product_data);
                j++;

            });
             $('.product_orders').append("<td  style='font-size :!2;' align='right' colspan=3><strong>Total </strong></td><td  style='font-size :!2;' align='right'><strong>"+total_qty+"</strong></td><td  style='font-size :!2;' align='right'><strong>"+Math.round(total_value,2)+" L</strong></td>"); 
            $("#pcont").css("opacity",1);
           $("#loaderID").css("opacity",0);
        }
    });
    
}
</script>
<style type="text/css">
    text[style="cursor:pointer;color:#909090;font-size:9px;fill:#909090;"]{ display: none;}
</style>