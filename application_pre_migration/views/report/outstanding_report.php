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
                                    <select class="form-control sector select2" style="width:100%" name="sector">
                                        <option value="">All Sectors</option>
                                        <?php
                                        foreach ($sector as $sec) {
                                            $selected='';
                                            if($sec['category_id']==$searchFilters['sector'])
                                            {
                                                $selected='selected';
                                            }
                                            else
                                            {
                                                $selected='';
                                            }
                                            echo '<option value="'.$sec['category_id'].'"'.$selected.'>'.$sec['name'].'</option>';
                                        }
                                        ?>
                                    </select>
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
                                <div id="container2" style=" margin: 0 auto"></div>
                            </div>
                        </div>  
                    </div>
                    <div class="row hidden tab">
                        <div class="col-sm-12">
                           <div class="col-md-12" align="center">
                                <div id="customer">
                                    <div class="table-responsive ">
                                        <span class="table_name"></span>
                                        <table class="table table-bordered hover section" width="100%" style="background-color: '#cfac6b' !important;">
                                            <thead>
                                                <tr>
                                                    <th class="text-center"  width="5%"><strong>S.No </strong></th>
                                                    <th class="text-center"  width='3%'><strong>Location</strong></th>
                                                    <th class="text-center"  width="5%"><strong>Cnote Id </strong></th>
                                                    <th class="text-center"  width='10%'><strong>Sales Person</strong></th>
                                                   <!--  <th class="text-center"  width='5%'><strong>Segment</strong></th> -->
                                                    <th class="text-center"  width='30%'><strong>Cnote Details</strong></th>
                                                    <th class="text-center"  width='10%'><strong>Sale(In Lacs)</strong></th>
                                                    <th class="text-center"  width='10%'><strong>Outstanding(In Lacs)</strong></th>
                                                    
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
$(document).on('change','.users,.sector',function(){
    $('.levels').addClass('hidden');
    $('.slider').show();
    $('.tab').addClass('hidden');
    filterReport();
});
$(document).on('change','.region',function(){

    $('.levels').addClass('hidden');
    $('.slider').show();
    $('.tab').addClass('hidden');
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
$('#level1').click(function(){
    $("#pcont").css("opacity",0.5);
    $("#loaderID").css("opacity",1);
    $('.slider2').hide();
    $('.slider').show();
    $('.orders').html('');
    $('.tab').addClass('hidden');
    $('#level2').addClass('hidden');
    $('#button2').attr('disabled',false);
    $('#button2').css('color','blue');
    $('.levels').addClass('hidden');
    $("#pcont").css("opacity",1);
    $("#loaderID").css("opacity",0);
   
    
});
$('#level2').click(function(){
    $("#pcont").css("opacity",0.5);
    $("#loaderID").css("opacity",1);
    $('.slider2').show();
    $('.slider').hide();
    $('#level3').addClass('hidden');
    $('.orders').html('');
    $('.tab').addClass('hidden');
    $('#button2').attr('disabled',true);
    $('#button2').css('color','black');
    $("#pcont").css("opacity",1);
    $("#loaderID").css("opacity",0);
});
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
    var users=$('.users').val();
    $(".orders").html("");
    var sector=$('.sector').val();
    var data = 'from_date='+from_date+'&to_date='+to_date+'&region='+region+'&users='+users+'&sector='+sector;
    $.ajax({
        url: SITE_URL + 'getoutstandingChart1Data',
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
    xAxisLable = chart1Data['xAxisLable'];
    xAxisCategory = chart1Data['xAxisCategory'];
    yAxisCategory = chart1Data['yAxisCategory'];
    chart1Series = chart1Data['chart1Series'];
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
                align: 'center',
                x: -100,
                verticalAlign: 'bottom',
                y: 20,
              //  floating: true,
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
                    stacking: 'normal',
                    shadow: false,
                    dataLabels: {
                        enabled: true,
                        color: '#ffffff'
                    }
                },
                series: {
                    cursor: 'pointer',
                    point: {
                        events: {
                            click: function () {
                            if(this.series.name=='Outstanding')
                            {
                                    $('.slider').hide();
                                     $(".orders").html("");
                                    $('#level1').removeClass('hidden');
                                    $('#level2').removeClass('hidden');
                                    $('#button2').attr('disabled', true);
                                    $('#button2').css('color','black');
                                    $('#level3').addClass('hidden');
                                    $('.slider2').show();
                                    $('#container2').show();
                                    second_chart(this.category,this.series.name);
                                }
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
    var users=$('.users').val();
    var region=$('.region').val();
    var sector=$('.sector').val();
    var data = 'x_category='+category+'&series_name='+name+'&users='+users+'&region='+region+'&sector='+sector;
    $.ajax({
        url: SITE_URL + 'getoutstandingChart2Data',
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
        
    xAxisLable = chart2Data['xAxisLable'];
    xAxisCategory = chart2Data['xAxisCategory'];
    yAxisCategory = chart2Data['yAxisCategory'];
    chart1Series = chart2Data['chart1Series'];
    next_tabel = chart2Data['next_tabel'];
    $('#container2').highcharts({

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
                align: 'center',
                x: -100,
                verticalAlign: 'bottom',
                y: 20,
                //floating: true,
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
                    
                    shadow: false,
                    dataLabels: {
                        enabled: true,
                        color: '#ffffff'
                    }
                },
                series: {
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
                                     $(".orders").html("");
                                   //alert('Category: ' + this.category+' Y val'+this.series.name);
                                    $('#container3').show();
                                    draw_chart3(this.category,this.series.name,category,next_tabel);
                           }
                        }
                    }
                }
            },

            series: chart1Series
        });
        
    }
}

function draw_chart3(category,series_name,aging,next_tabel)
{
    var users=$('.users ').val();
    var region=$('.region').val();
    var sector=$('.sector').val();
    $("#pcont").css("opacity",0.5);
    $("#loaderID").css("opacity",1);
    var category1=category.replace("&", "/AB");
    

    var data = '&category='+category1+'&series_name='+series_name+'&users='+users+'&region='+region+'&sector='+sector+'&aging='+aging;
    $.ajax({
        url: SITE_URL + 'getoutstandingChart3Data',
        type:"POST",
        data:data,
        dataType:'json', 

        success: function(list){
            var data=list['customers'];
            var sales=0;
            var ot=0;
            $('.tab').removeClass('hidden');
            var j=1;
            $('.table_name').html("<h4>"+category+" Outstanding List "+next_tabel+"</h4>");
            $.each(data,function(i)
            {   
                sales+= parseFloat(data[i].total_orders);
                ot+= parseFloat(data[i].outstanding_amount);
                var customer_data="<tr> <td style='font-size: 12;' align='center'>"+j+"</td><td  style='font-size :!2;' align='left'>"+data[i].cus_location+"</td><td style='font-size: 12;' align='left'>"+ data[i].contract_note_id +"</td><td  style='font-size :!2;' align='left'>"+data[i].first_name+"</td><td style='font-size: 12;' align='left'>"+ data[i].product_details +"</td><td  style='font-size :!2;' align='right'>"+data[i].total_orders+"</td><td  style='font-size :!2;' align='right'>"+data[i].outstanding_amount+"</td></tr>";
                $(".orders").append(customer_data);
                j++;

            });
            $('.orders').append("<td  style='font-size :!2;' align='right' colspan=5><strong>Total </strong></td><td  style='font-size :!2;' align='right'>"+sales+"L</td><td  style='font-size :!2;' align='right'>"+ot+" L</td>");
            $("#pcont").css("opacity",1);
           $("#loaderID").css("opacity",0);
        }
    });
    
}
</script>