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
                                <div class="col-sm-2 custom_icheck" style="padding-left: 0px !important">
                                    <label class="radio-inline"> 
                                        <div class="iradio_square-blue checked" style="position: relative;" aria-checked="true" aria-disabled="false">
                                            <input type="radio" class="measure" value="1" name="measure" checked style="position: absolute; opacity: 0;">
                                            <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;"></ins>
                                        </div> 
                                        By &nbsp;Product
                                    </label>
                                   <br>
                                    <label class="radio-inline"> 
                                        <div class="iradio_square-blue " style="position: relative; padding-left: 2px;" aria-checked="true" aria-disabled="false">
                                            <input type="radio" class="measure" value="2" name="measure"  style="position: absolute; opacity: 0;">
                                            <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;"></ins>
                                        </div> 
                                        By Region
                                    </label>
                                </div>
                                <?php  if(in_array($this->session->userdata('role_id'),margin_allowed_roles())) { ?>  
                               
                                <!-- <label class="col-sm-1 control-label" style="margin-top:4px;"> Region : </label>  -->
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
                               <?php } ?>
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
                    <div class="row slider">
                        <div class="col-sm-12">
                            <div class="col-md-11" align="center">
                                <div id="container1" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
                            </div>
                        </div>  
                    </div>
                    <div class="row slider2">
                        <div class="col-sm-12">
                            <div class="col-md-11" align="center">
                                <div class="col-sm-offset-9 col-sm-2 hidden table_view" align="right">
                                    <span class="" id="table_switch"><a style="color:blue" class="customer_list">Customer List</a></span>
                                </div>
                                <div id="container2" style=" margin: 0 auto"></div>
                            </div>
                        </div>  
                    </div>
                    <div class="row hidden tab">
                        <div class="col-sm-12">
                            <div class="col-sm-offset-8 col-sm-2 hidden graph_view" align="right">
                                <span class="" id="graph_switch"><a style="color:blue" class="graph_list">View Graph</a></span>
                            </div>
                            <div class="col-md-offset-1 col-md-10" align="center">
                                <div class="table-responsive">
                                    <span class="table_name"></span>
                                    <table class="table table-bordered hover section" style="background-color: '#cfac6b' !important;" width="100%">
                                        <thead>
                                            <tr>
                                                <th class="text-center"  width="5%"><strong>S.No </strong></th>
                                                <th class="text-center" width='25%'><strong>Customer</strong></th>
                                               <!--  <th class="text-center" width='25%'><strong>Product</strong></th>
                                                <th class="text-center" width='20%'><strong>C Note Number</strong></th> -->
                                                <th class="text-center" width='10%'><strong>Business</strong></th>
                                                <!-- <th class="text-center" width='5%'><strong>Qty</strong></th> -->
                                                <th class="text-center" width='10%'><strong>Value</strong></th>
                                            </tr>
                                        </thead>  
                                        <tbody class="orders">
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>  
                    </div></br>
                </div>
            </div>
        </div>              
    </div>
</div>
<style type="text/css">
   
    a{
        cursor: pointer;
    }
</style>

<?php 
$this->load->view('commons/main_footer.php', $nestedView); ?>

<script type="text/javascript">
filterDuration();
$(document).on('change','.users,.duration',function(){
    $('.levels').addClass('hidden');
    $('.tab').addClass('hidden');
    $('.orders').html('');
    $('.slider').show();
    $('.table_view').addClass('hidden');
    $('.graph_view').addClass('hidden');
    filterReport();
});
$(document).on('change','.region',function(){

    $('.levels').addClass('hidden');
    $('.tab').addClass('hidden');
    $('.orders').html('');
    $('.slider').show();
    $('.table_view').addClass('hidden');
    $('.graph_view').addClass('hidden');
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
$('.measure').click(function(){
    var measure=$('.measure:checked').val();
    $('.levels').addClass('hidden');
    $('.slider').show();
    $('.slider2').hide();
    $('.tab').addClass('hidden');
    $('.orders').html('');
    $('.table_view').addClass('hidden');
    $('.graph_view').addClass('hidden');
    filterReport();
});
$('#level1').click(function(){
    $("#pcont").css("opacity",0.5);
    $("#loaderID").css("opacity",1);
    $('.slider2').hide();
    $('.slider').show();

    $('#level2').addClass('hidden');
    $('#button2').attr('disabled',false);
    $('#button2').css('color','blue');
    $('.levels').addClass('hidden');
    $('.orders').html('');
    $('.tab').addClass('hidden');
    $("#pcont").css("opacity",1);
    $("#loaderID").css("opacity",0);
   
    
});
$('#level2').click(function(){
    $("#pcont").css("opacity",0.5);
    $("#loaderID").css("opacity",1);
    $('.slider2').show();
    $('.slider').hide();
    $('#level3').addClass('hidden');
    $('#button2').attr('disabled',true);
    $('#button2').css('color','black');
    $('.orders').html('');
    $('.tab').addClass('hidden');
    $("#pcont").css("opacity",1);
    $("#loaderID").css("opacity",0);
});
$('.table_view').click(function(){
   var measure=$('.measure:checked').val();
   $('.tab').removeClass('hidden');
   $('.graph_view').removeClass('hidden');
   if(measure==1)
   {
        $('.graph_view').text('Product List');
        $('.graph_view').css('color','blue');
        $('.graph_view').css('cursor','pointer');
   }
   if(measure==2)
   {
        $('.graph_view').text('Sales Engineers List');
        $('.graph_view').css('color','blue');
        $('.graph_view').css('cursor','pointer');
   }
   $('.table_view').addClass('hidden');
   $('#container2').hide();
});
$('.graph_view').click(function(){
   $('.tab').addClass('hidden');
   $('.table_view').removeClass('hidden');
   $('#container2').show();
});
$('.levels').addClass('hidden');
var vtime='y';
var from_date, to_date;
$(document).on('click','.timeline',function(){
    $('.tab').addClass('hidden');
    $('.orders').html('');
    $('.levels').addClass('hidden');
    $('.slider').show();
    $('.table_view').addClass('hidden');
    $('.graph_view').addClass('hidden');
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
    var measure=$('.measure:checked').val();
    var users=$('.users').val();
    var duration=$('.duration').val();
    var duration_text=$('.duration option:selected').text();
    //alert(vtime+from_date+to_date);
    var data = 'from_date='+from_date+'&to_date='+to_date+'&vtime='+vtime+'&region='+region+'&measure='+measure+'&users='+users+'&duration='+duration+'&duration_text='+duration_text;
    $.ajax({
        url: SITE_URL + 'getFreshBusinessChart1Data',
        type:"POST",
        data:data,
        dataType:'json', 
        success: function(data){
            //alert(data);
            first_chart(data);
            $("#pcont").css("opacity",1);
            $("#loaderID").css("opacity",0);
            $('#container2,#container3').html('').hide();
        }
    });
}
var chart1Data = <?php echo $chart1Data1;?>;
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
                    }
                }
            },

            legend: {
                align: 'right',
                x: -100,
                verticalAlign: 'top',
                y: 20,
                floating: true,
                backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColorSolid) || 'white',
                borderColor: '#CCC',
                borderWidth: 1,
                shadow: false
            },
             tooltip: {
            pointFormat: '{series.name}: <b>{point.y} Lacs </b>',
            },
            plotOptions: {
                column: {
                    
                    //shadow: false,
                    dataLabels: {
                        enabled: true,
                        color: '#ffffff',
                         allowOverlap: true
                    }
                },
                series: {
                    cursor: 'pointer',
                    point: {
                        events: {
                            click: function () {
                                var s =this.category;
                                s = s.substring(0, s.indexOf('<br>')).trim();
                                //alert(s);
                                $('.slider').hide();
                                $('#level1').removeClass('hidden');
                                $('#level2').removeClass('hidden');
                                $('#button2').attr('disabled', true);
                                $('#button2').css('color','black');
                                $('#level3').addClass('hidden');
                                $('.slider2').show();
                                $('#container2').show();
                                $('.table_view').removeClass('hidden');
                                $('.tab').addClass('hidden');
                                $('.orders').html('');
                                second_chart(s,this.series.name);
                            }
                        }
                    }
                }
            },

            series: chart1Series
        });
  
}

function second_chart(category,name)
{
    $("#pcont").css("opacity",0.5);
    $("#loaderID").css("opacity",1);
    var vtime =$('.btn-success').attr('data-id');
    var measure=$('.measure:checked').val();
    var users=$('.users').val();
    var region=$('.region').val();
    var duration=$('.duration').val();
    var duration_text=$('.duration option:selected').text();
    var category1=category;
    var category2=category1.replace("&", "/AB");
    var data = 'x_category='+category2+'&series_name='+name+'&vtime='+vtime+'&measure='+measure+'&users='+users+'&region='+region+'&duration='+duration+'&duration_text='+duration_text;
    $.ajax({
        url: SITE_URL + 'getFreshBusinessChart2Data',
        type:"POST",
        data:data,
        dataType:'json', 
        success: function(data){
            $chartdata=data['chart1Data'];
            $product_list=data['products'];
            $table_text=data['table_text'];
            create_product_list($product_list,$table_text);
            $('.tab').addClass('hidden');
            createChart2( $chartdata);
            $("#pcont").css("opacity",1);
            $("#loaderID").css("opacity",0);
        }
    });

    createChart2 = function (chart2Data) 
    {
        xAxisCategory2 = chart2Data['xAxisCategory'];
        yAxisCategory2 = chart2Data['yAxisCategory'];
        chart1Series2 = chart2Data['chart2Series'];
        xAxisLable2 = chart2Data['xAxisLable'];

        $('#container2').highcharts({
            chart: {
                trenderTo: 'container1',
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
                    }
                }
            },
           
             tooltip: {
            pointFormat: '{series.name}: <b>{point.y} Lacs </b>',
            },
            plotOptions: {
                column: {
                   // stacking: 'normal',
                   // shadow: false,
                },
                series: {
                     dataLabels: {
                        enabled: true,
                        formatter:function()
                        {
                            
                            if(this.y>0){
                                return this.y;
                            }
                        },
                        allowOverlap: true
                    },
                    cursor: 'pointer',
                    point: {
                        events: {

                        }
                    }
                }
            },
            series: chart1Series2
        });
        
    }
}

function create_product_list(data,table_text)
{
    $('.tab').removeClass('hidden');
    //$('.table_name').html("<h4>"+name+" For "+category+ " Product List</h4>");
    var j=1;
    var total_qty=0;
    var total_value=0;
    $('.table_name').html("<h4>"+table_text+"</h4>");
    $.each(data,function(i)
    {   
        total_value += parseFloat(data[i].total_orders);
        var customer_data="<tr> <td style='font-size: 12;' align='center'>"+j+"</td><td  style='font-size :!2;' align='left'>"+data[i].name+"</td><td  style='font-size :!2;' align='left'>"+data[i].business_type+"</td><td  style='font-size :!2;' align='right'>"+data[i].total_orders+"</td></tr>";
        $(".orders").append(customer_data);
        j++;
       

    });
    $('.orders').append("<td  style='font-size :!2;' align='right' colspan=3><strong>Total </strong></td><td  style='font-size :!2;' align='right'><strong>"+Math.round(total_value,2)+" L</strong></td>");
    $("#pcont").css("opacity",1);
    $("#loaderID").css("opacity",0);
}
</script>
<style type="text/css">
    text[style="cursor:pointer;color:#909090;font-size:9px;fill:#909090;"]{ display: none;}
</style>