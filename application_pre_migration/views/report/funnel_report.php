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
                           <!--  <div class="col-sm-2 custom_icheck" style="padding-left: 0px !important">
                                    <label class="radio-inline"> 
                                        <div class="iradio_square-blue checked" style="position: relative;" aria-checked="true" aria-disabled="false">
                                            <input type="radio" class="measure" value="1" name="measure"  style="position: absolute; opacity: 0;">
                                            <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;"></ins>
                                        </div> 
                                        By &nbsp;Qty
                                    </label>
                                   <br>
                                    <label class="radio-inline"> 
                                        <div class="iradio_square-blue " style="position: relative; padding-left: 2px;" aria-checked="true" aria-disabled="false">
                                            <input type="radio" class="measure" value="2" name="measure" checked  style="position: absolute; opacity: 0;">
                                            <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;"></ins>
                                        </div> 
                                        By Value
                                    </label>
                                </div> -->
                                <div class="col-sm-1">
                                </div>
                                <input type="hidden" name="measure" class="measure" value="2">
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
                                <!--  <div class="col-sm-2"><input type="text" name="date_from" id="date_from" class="form-control" readonly placeholder="From Date"></div>
                                <div class="col-sm-2"><input type="text" name="date_to" id="date_to" class="form-control" readonly placeholder="To Date"></div> -->
                                <div class="col-sm-4">
                                    <div class="btn-group">
                                        <!-- <input type="button" data-id="a" name="timeline" class="timeline btn btn-default" checked  value="All"> -->
                                        <input type="button" data-id="w" name="timeline" class="timeline btn btn-default" value="Week">
                                        <input type="button" data-id="m" name="timeline" class="timeline btn btn-default"  value="Month">
                                        <input type="button" data-id="q" name="timeline" class="timeline btn btn-default" value="Quarter">
                                        <input type="button" data-id="y" name="timeline" class="timeline btn btn-success " checked value="Year">
                                    </div>
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
                                    <div id="container1" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
                                </div>
                               
                            </div>  
                        </div><br><br>
                        <div class="row pro">
                            <div class="col-sm-12">
                                <div class="col-md-6" align="center">
                                    <div id="container2" style=" margin: 0 auto"></div>
                                </div>
                                 <div class="col-md-6" align="center">
                                    <div id="container3" style=" margin: 0 auto"></div>
                                </div>
                            </div>  
                        </div><br>
                        <div class="row hidden tab">
                            <div class="col-sm-12">
                                <div class="col-md-offset-2 col-md-8" align="center">
                                    <div id="customer">
                                        
                                        <div class="table-responsive ">
                                        <span class="table_name"></span>
                                            <table class="table table-bordered hover section" style="background-color: '#cfac6b' !important;" width="75%">
                                                <thead class="order_header">
                                                    <tr>
                                                       <th class="text-center"  width="1%"><strong>S.No </strong></th>
                                                       <th class="text-center"  width="2%"><strong>Segment </strong></th>
                                                       <th class="text-center"  width="24%"><strong>Product Code </strong></th>
                                                       <th class="text-center" width='20%'><strong>Product</strong></th>
                                                       <th class="text-center table_reason hidden" width='40%'><strong>Reason</strong></th>
                                                        <th class="text-center table_won hidden" width='20%'><strong>Order Status</strong></th>
                                                       <th class="text-center" width='2%'><strong>Qty</strong></th>
                                                       <th class="text-center" width='6%'><strong>Value</strong></th>
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
                        </br>
                    </div>
							
				</div>
			</div>
		</div>				
	</div>
</div>


<?php 
$this->load->view('commons/main_footer.php', $nestedView); ?>

<script type="text/javascript">
filterDuration();
$(document).on('change','.duration,.users',function(){
     $('.levels').addClass('hidden');
    //$('.span').addClass('hidden');
    $('.slider').show();
    var duration=$('.duration').val();
  
    var users=$('.users').val();
    filterReport();
});
$(document).on('change','.region',function(){

    $('.levels').addClass('hidden');
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
$('.measure').click(function(){
    var measure=$('.measure').val();
    filterReport();
});
/*var vtime='y';
var from_date, to_date;
$('.timeline').click(function(){

    var tl = $(this).attr('data-id');
    vtime = tl;
    $('#date_from').val('');
    $('#date_to').val('');
    $('.timeline').addClass('btn-default').removeClass('btn-success');
    $(this).addClass('btn-success').removeClass('btn-default');
    var sales=$('.sales:checked').val();
    filterReport();
});*/
$('.tab').addClass('hidden');
$('.orders').html('');
var vtime='y';
var from_date, to_date;
$(document).on('click','.timeline',function(){
    $('.tab').addClass('hidden');
    var tl = $(this).attr('data-id');
    vtime = tl;
  //  alert(vtime);
    $('#date_from').val('');
    $('#date_to').val('');
    $('.timeline').addClass('btn-default').removeClass('btn-success');
    $(this).addClass('btn-success').removeClass('btn-default');
    filterDuration();
   // filterReport();
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
    var from_date = $('#date_from').val();
    var to_date = $('#date_to').val();
    var region = $('.region').val();
    var measure=$('.measure').val();
    var users=$('.users ').val();
    var duration=$('.duration').val();
    var duration_text=$('.duration option:selected').text();
  //alert(users);

    var data = 'from_date='+from_date+'&to_date='+to_date+'&vtime='+vtime+'&region='+region+'&measure='+measure+'&users='+users+'&duration='+duration+'&duration_text='+duration_text;
    $.ajax({
        url: SITE_URL + 'filter_funnel_chart',
        type:"POST",
        data:data,
        dataType:'json', 
        success: function(data){
            first_chart(data);
            $("#pcont").css("opacity",1);
            $("#loaderID").css("opacity",0);
            $('#container2,#container3').html('').hide();
        }
    });
}
var chart1Data = <?php echo $chart1Data;?>;
first_chart(chart1Data);
function first_chart(chart1Data) 
{
    xAxisCategory = chart1Data['xAxisCategory'];
    yAxisCategory = chart1Data['yAxisCategory'];
    chart1Series = chart1Data['chart1Series'];
    xAxisLable = chart1Data['xAxisLable'];
    topPosition= chart1Data['tpoPosition'];
    chart = new Highcharts.Chart({

            chart: {
                renderTo: 'container1',
                type: 'column',
                zoomType:'xy'
            },

            title: {
                text: xAxisLable
            },

            xAxis: {
                categories: xAxisCategory
            },

            yAxis: {
                min: 0,
                title: {
                    text: yAxisCategory
                },
                stackLabels: {
                    enabled: true,
                    style: {
                        fontWeight: 'bold',
                        color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                    },
                     formatter:function()
                        {
                            if(this.total>=1000){
                                return (this.total/1000).toFixed(2)+' K';
                            }
                            else
                            {
                                return this.total;
                            }
                        }
                }
            },

            legend: {
                align: 'right',
                x: -100,
                verticalAlign: 'top',
                y: 20,
              //  floating: true,
                backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColorSolid) || 'white',
                borderColor: '#CCC',
                borderWidth: 1,
                shadow: false
            },
            tooltip: {
                    formatter: function() {

                    if(this.series.name == 'Constant' ){
                      return false ;
                    // to disable the tooltip at a point return false 
                    }
                    else {
                    return '<b>'+ this.series.name +'</b><br/>'+
                     this.y ;
            }   
                }
            },
            plotOptions: {
                column: {
                    stacking: 'normal',
                    shadow: false,
                    dataLabels: {
                        enabled: true,
                        color: '#F1EFE2',
                        formatter:function()
                        {
                            if(this.y>=1000){
                                return (this.y/1000).toFixed(2)+' K';
                            }
                            else
                            {
                                return this.y;
                            }
                        }
                    }
                },
                series: {
                    cursor: 'pointer',
                    point: {
                        events: {
                            click: function () {
                                $('.tab').addClass('hidden');
                                $('.orders').html('');
                                if(this.series.name=='New' || this.series.name=='Closed')
                                {
                                    $('.pro').removeClass('hidden');
                                    $('#container2').show();
                                    $('#container3').hide();
                                    second_chart(this.category,this.series.name);
                                }
                                if(this.series.name=='Hot'|| this.series.name=='Warm'||this.series.name=='Cold')
                                {
                                    $('.orders').html('');
                                    $('.pro').addClass('hidden');
                                   // alert(this.category);
                                    funnel_table(this.category,this.series.name,"funnel");
                                }
                            }
                        }
                    }
                }
            },

            series: chart1Series
        });
   /* $.each(chart.series[5].data, function(i, point) {
        point.graphic.attr({opacity: 0, 'stroke-width': 0});
    });*/
}

function second_chart(category,name)
{
    $("#pcont").css("opacity",0.5);
    $("#loaderID").css("opacity",1);
    var vtime =$('.btn-success').attr('data-id');
    var measure=$('.measure').val();
    var users=$('.users').val();
    var region=$('.region').val();
    var duration=$('.duration').val();
    var duration_text=$('.duration option:selected').text();
    var data = 'x_category='+category+'&series_name='+name+'&vtime='+vtime+'&measure='+measure+'&users='+users+'&region='+region+'&duration='+duration+'&duration_text='+duration_text;
    $.ajax({
        url: SITE_URL + 'funnel_chart2',
        type:"POST",
        data:data,
        dataType:'json', 
        success: function(data){
            createChart2(data);
            $("#pcont").css("opacity",1);
            $("#loaderID").css("opacity",0);
        }
    });

    createChart2 = function (chart2Data) 
    {
        xAxisCategory2 = chart2Data['xAxisCategory2'];
        yAxisCategory2 = chart2Data['yAxisCategory2'];
        chart1Series2 = chart2Data['chart2Series'];
        xAxisLable2 = chart2Data['xAxisLable2'];

        $('#container2').highcharts({
            chart: {
            type: 'column',
             zoomType:'xy'
            },
            title: {
                text: xAxisLable2 
            },
            xAxis: {
                categories: xAxisCategory2
            },
            yAxis: {
                min: 0,
                title: {
                    text: yAxisCategory2
                },
                stackLabels: {
                    enabled: true,
                    style: {
                        fontWeight: 'bold',
                        color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                    },
                    formatter:function()
                        {
                            
                            if(this.total>=1000){
                                return (this.total/1000).toFixed(2)+' K';
                            }
                            else
                            {
                                return this.total;
                            }
                        }
                }
            },
            legend: {
                align: 'right',
                x: -30,
                verticalAlign: 'top',
                y: 25,
              //  floating: true,
                backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
                borderColor: '#CCC',
              //  borderWidth: 1,
                shadow: false
            },
            tooltip: {
                headerFormat: '<b>{point.x}</b><br/>',
                pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
            },
            plotOptions: {
                column: {
                    stacking: 'normal',
                    dataLabels: {
                        enabled: true,
                        color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                        formatter:function()
                        {
                            if(this.y>=1000){
                                return (this.y/1000).toFixed(2)+' K';
                            }
                            else
                            {
                                return this.y;
                            }
                        }
                    },

                },
                series: {
                    cursor: 'pointer',
                    point: {
                        events: {
                            click: function () {
                               // alert('Category: ' + this.category + ', value: ' + this.y);
                                $('.tab').addClass('hidden');
                                $('.orders').html('');
                                $('#container3').hide();
                                if(this.series.name=='Closed Lost')
                                {
                                  $('#container3').show();
                                  third_chart(this.category,this.series.name);

                                }
                                if(this.series.name=='Dropped' || this.series.name=="Closed Won")
                                {
                                    $('.orders').html('');
                                    funnel_table(this.category,this.series.name,category);
                                }
                                if(this.series.name=='Hot'|| this.series.name=='Warm'||this.series.name=='Cold')
                                {
                                    $('.orders').html('');
                                    funnel_table(this.category,this.series.name,name);
                                }
                            }
                        }
                    }
                }
            },
            series: chart1Series2
        });
        
    }
}

function third_chart(category,name)
{
    $("#pcont").css("opacity",0.5);
    $("#loaderID").css("opacity",1);
    var vtime =$('.btn-success').attr('data-id');
    var measure=$('.measure').val();
    var users=$('.users').val();
    var region=$('.region').val();
    var duration=$('.duration').val();
    var duration_text=$('.duration option:selected').text();
    var data = 'x_category2='+category+'&series_name2='+name+'&vtime='+vtime+'&measure='+measure+'&users='+users+'&region='+region+'&duration='+duration+'&duration_text='+duration_text;
    $.ajax({
        url: SITE_URL + 'funnel_chart3',
        type:"POST",
        data:data,
        dataType:'json', 
        success: function(data){
            createChart3(data);
            $("#pcont").css("opacity",1);
            $("#loaderID").css("opacity",0);
        }
    });

    createChart3 = function (chart3Data) 
    {
        xAxisCategory3 = chart3Data['xAxisCategory3'];
        yAxisCategory3 = chart3Data['yAxisCategory3'];
        chart1Series3 = chart3Data['chart3Series'];
        xAxisLable3 = chart3Data['xAxisLable3'];

        $('#container3').highcharts({
            chart: {
            type: 'column',
             zoomType:'xy'
            },
            title: {
                text: xAxisLable3
            },
            xAxis: {
                categories: xAxisCategory3
            },
            yAxis: {
                min: 0,
                title: {
                    text: yAxisCategory3
                },
                stackLabels: {
                    enabled: true,
                    style: {
                        fontWeight: 'bold',
                        color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                    }
                }
            },
            legend: {
                align: 'right',
                x: -20,
                verticalAlign: 'bottom',
                y: 25,
                floating: false,
                backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
                borderColor: '#CCC',
            //    borderWidth: 1,
                shadow: false
            },
            tooltip: {
                headerFormat: '<b>{point.x}</b><br/>',
                pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
            },
            plotOptions: {
                column: {
                    stacking: 'normal',
                    dataLabels: {
                        enabled: true,
                        color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
                    }
                },
                series: {
                    cursor: 'pointer',
                    point: {
                        events: {
                            click: function () {
                               // alert('Category: ' + this.category + this.series.name);
                                //$('#container3').show();
                                //$('.tab').addClass('hidden');
                                $('.orders').html('');
                                //alert(category);
                               funnel_table(this.category,this.series.name,category);
                            }
                        }
                    }
                }
            },
            series: chart1Series3
        });
        
    }
}
function funnel_table(category,name,search_date)
{
    $("#pcont").css("opacity",0.5);
    $("#loaderID").css("opacity",1);
    var vtime =$('.btn-success').attr('data-id');
    var measure=$('.measure').val();
    var users=$('.users').val();
    var region=$('.region').val();
    var duration=$('.duration').val();
    var duration_text=$('.duration option:selected').text();
   var data = 'category='+category+'&series_name='+name+'&vtime='+vtime+'&measure='+measure+'&users='+users+'&region='+region+'&duration='+duration+'&duration_text='+duration_text+'&search_date='+search_date;
    $.ajax({
        url: SITE_URL + 'get_filter_funnel_table',
        type:"POST",
        data:data,
        dataType:'json', 
        success: function(data1){
            var data=data1['results'];
            var text=data1['lable'];
            $('.tab').removeClass('hidden');
            if(category=='Lost To Competitor')
            {
                if(name=='Others')
                {
                    $('.table_name').html("<h4>"+text+"</h4>");
                }
                else
                {
                    $('.table_name').html("<h4>"+text+"</h4>");
                }
            }
            if(category=='Lost By Reason')
            {
                if(name=='Others')
                {
                    $('.table_name').html("<h4>"+text+"</h4>");
                }
                else
                {
                    $('.table_name').html("<h4>"+text+"</h4>");
                }
            }
            if(name=="Hot" || name=="Warm"|| name=="Cold")
            {
                if (search_date=='funnel') {
                    $('.table_name').html("<h4>"+text+"</h4>");
                }
                else
                {
                     $('.table_name').html("<h4>"+text+"</h4>");
                }
            }
            if(name=="Dropped")
            {
                $('.table_name').html("<h4>"+text+"</h4>");
                $('.table_reason').removeClass('hidden');

            }
            if(name=="Closed Won")
            {
                $('.table_name').html("<h4>"+text+"</h4>");
                $('.table_won').removeClass('hidden');
            }
            var j=1;
            var total_qty=0;
            var total_value=0;

            $.each(data,function(i)
            {   
              if(name=="Dropped")  
              {
                     $('.table_won').addClass('hidden');
                     if(data[i].qty>0) {
                        total_qty += parseFloat(data[i].qty);
                        total_value += parseFloat(data[i].value);
                        var customer_data="<tr> <td style='font-size: 12;' align='center'>"+j+"</td><td style='font-size: 12;' align='left'>"+ data[i].segment_name +"</td><td  style='font-size :!2;' align='left'>"+data[i].name+"</td><td  style='font-size :!2;' align='left'>"+data[i].description+"</td><td  style='font-size :!2;' align='left'>"+data[i].reason_name+"</td><td  style='font-size :!2;' align='right'>"+data[i].qty+"</td><td  style='font-size :!2;' align='right'>"+data[i].value+" L</td></tr>";
                        $(".orders").append(customer_data);
                        j++;
                    }
               
              }
            else if(name=="Closed Won")  
              {     
                    if(data[i].status==1)
                    {
                        var st="RBH Cleared";
                    }
                    else if(data[i].status==2)
                    {
                        var st = "Invoiced";
                    }
                    else
                    {
                        var st = "Waiting for RBH Clearance";
                    }
                     $('.table_reason').addClass('hidden');
                     if(data[i].qty>0) {
                        total_qty += parseFloat(data[i].qty);
                        total_value += parseFloat(data[i].value);
                        var customer_data="<tr> <td style='font-size: 12;' align='center'>"+j+"</td><td style='font-size: 12;' align='left'>"+ data[i].segment_name +"</td><td  style='font-size :!2;' align='left'>"+data[i].name+"</td><td  style='font-size :!2;' align='left'>"+data[i].description+"</td><td  style='font-size :!2;' align='left'>"+st+"</td><td  style='font-size :!2;' align='right'>"+data[i].qty+"</td><td  style='font-size :!2;' align='right'>"+data[i].value+" L</td></tr>";
                        $(".orders").append(customer_data);
                        j++;
                    }
               
              }
              else
              {
                     $('.table_reason').addClass('hidden');
                     $('.table_won').addClass('hidden');
                    if(data[i].qty>0) {
                        total_qty += parseFloat(data[i].qty);
                        total_value += parseFloat(data[i].value);
                        var customer_data="<tr> <td style='font-size: 12;' align='center'>"+j+"</td><td style='font-size: 12;' align='left'>"+ data[i].segment_name +"</td><td  style='font-size :!2;' align='left'>"+data[i].name+"</td><td  style='font-size :!2;' align='left'>"+data[i].description+"</td><td  style='font-size :!2;' align='right'>"+data[i].qty+"</td><td  style='font-size :!2;' align='right'>"+data[i].value+" L</td></tr>";
                        $(".orders").append(customer_data);
                        j++;
                    }
                   
                }

            });
            if(name=="Dropped" || name=="Closed Won")
            {
                $('.orders').append("<td  style='font-size :!2;' align='right' colspan=5><strong>Total </strong></td><td  style='font-size :!2;' align='right'>"+total_qty+"</td><td  style='font-size :!2;' align='right'>"+Math.round(total_value,2)+" L</td>");
            }
            else
            {
                 $('.orders').append("<td  style='font-size :!2;' align='right' colspan=4><strong>Total </strong></td><td  style='font-size :!2;' align='right'>"+total_qty+"</td><td  style='font-size :!2;' align='right'>"+Math.round(total_value,2)+" L</td>");
            }
            $("#pcont").css("opacity",1);
            $("#loaderID").css("opacity",0);

        }
    });
}
</script>
<style type="text/css">
    text[style="cursor:pointer;color:#909090;font-size:9px;fill:#909090;"]{ display: none;}
</style>