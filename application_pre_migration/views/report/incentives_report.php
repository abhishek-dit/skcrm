<?php $this->load->view('commons/main_template', $nestedView);

?>
<div id="loaderID" style="position:fixed; top:50%; left:58%; z-index:2; opacity:0"><img src="<?php echo assets_url(); ?>images/ajax-loading-img.gif" /></div>

<div class="row"> 
    <div class="col-sm-12 col-md-12">
        <div class="block-flat">
            <div class="content">
                <form method="post"  class="submit_frm">
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
                                                /*$selected='';
                                                if($reg['location_id']==$searchFilters['region'])
                                                {
                                                    $selected='selected';
                                                }
                                                else
                                                {
                                                    $selected='';
                                                }*/
                                                echo '<option value="'.$reg['location_id'].'">'.$reg['location'].'</option>';
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
                                   <?php }
                                   else
                                    { ?>
                                        <input type="hidden" class="users" name="users" value="">

                                 <?php   } ?>
                                    
                                    <div class="col-sm-2">
                                        <input type="hidden" class="user_role" name="user_role">
                                        <select class="form-control quarter_id" style="width:100%" name="quarter_id">
                                            <option value="">Select Quarter</option>
                                            <?php
                                                $count=count($quarter);

                                                for($i=0;$i<$count;$i++)
                                                {
                                                    /*$selected = '';
                                                    if($i == $count-1)
                                                    {
                                                        $selected = 'selected';
                                                    }*/

                                                    echo '<option value="'.$quarter[$i]['start_date'].'to'.$quarter[$i]['end_date'].'">'.$quarter[$i]['quarter'].'</option>';
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <select class="form-control year_id" style="width:100%" name="year_id">
                                            <?php
                                            $fy_year1=get_current_fiancial_year();
                                            $curr_month = date('m');
                                            $check = 0;
                                            if($curr_month == 4 || $curr_month == 5 || $curr_month == 6)
                                            {
                                                $check++;
                                            }
                                            foreach($fy_year as $row)
                                            {
                                                if($check<1)
                                                {
                                                    $selected=($fy_year1['fy_id']==$row['fy_id'])?'selected':'';

                                                    echo '<option value="'.$row['fy_id'].'"'.$selected.'>'.$row['name'].'</option>';
                                                }
                                                $check--;
                                                
                                            }?>
                                        </select>
                                    </div>
                                    <div class="col-sm-1">
                                        <a  class="btn btn-primary search" title="Search"><i class="fa fa-search"></i></a>
                                    </div>  
                                    <div class="col-sm-1">
                                        <button formaction="<?php echo SITE_URL.'download_incentives'?>"  class="btn btn-primary" title="Download Report"><i class="fa fa-cloud-download"></i></button>
                                    </div>                
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="content">
                    <div class="col-sm-12">
                        <div class="col-sm-offset-10 col-sm-2">
                            <span class="levels" id="level1"><a style="color:blue"><i class="fa fa-angle-double-left"></i> Back </a></span>
                        </div>
                    </div>
                </div>
                <div class="content">
                    <div class="row slider">
                        <div class="col-sm-12">
                            <div class="col-md-11" align="center">
                                <div id="container1" ></div>
                            </div>
                        </div>  
                    </div>
                    <div class="row hidden tab">
                        <div class="col-sm-12">
                            
                            <div class="col-md-12" align="center">
                                <div class="table-responsive">
                                    <span class="table_name"></span>
                                    <table class="table table-bordered hover section" style="background-color: '#cfac6b' !important;" width="150%">
                                        <thead class="tab_head">
                                            
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

$('document').ready(function(){
    //filterDuration();
    $(".quarter_id option:last").attr("selected", "selected");
    $('.levels').addClass('hidden');
});

/*$(document).on('change','.users,.quarter_id,.year_id',function(){
    $('.tab').addClass('hidden');
    $('.orders').html('');
    
    filterReport();
});*/
$(document).on('change','.region',function(){

    $('.tab').addClass('hidden');
    $('.orders').html('');
    $('.tab_head').html('');
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
    //filterReport();
});
$(document).on('click','.search',function(){
    $('.tab_head').html('');
    //$('#container2').html('').hide();
    filterReport();
});

$('#level1').click(function(){
    $('#container1').show();
    $('.levels').addClass('hidden');
    $('.tab').addClass('hidden');
    $('.user_role').val('');
});


var vtime='q';

$(document).on('change','.year_id',function(){
    var year_id=$(this).val();
    var data='year_id='+year_id;
    $.ajax({
        url: SITE_URL+'get_quarter_based_on_year',
        type :"POST",
        data:data,
        success:function(data){
            $('.quarter_id').html(data);
            $(".quarter_id option:last").attr("selected", "selected");
          }
     });
});
/*$(document).on('change','.quarter_id',function(){
    filterReport();
});*/
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




var role=<?php echo $role;?>;
function filterReport() {
   
    $("#pcont").css("opacity",0.5);
    $("#loaderID").css("opacity",1);
    var region = $('.region').val();
    var users=$('.users').val();
    var duration=$('.quarter_id').val();
    var year=$('.year_id').val();
    var duration_text=$('.quarter_id option:selected').text();
    var year_text=$('.year_id option:selected').text();
    $('.orders').html('');
    $('.tab_head').html('');
    $('.user_role').val('');
    //$('#container2').html('').hide();
    $('.levels').addClass('hidden');
    var data = 'region='+region+'&users='+users+'&duration='+duration+'&year='+year+'&duration_text='+duration_text+'&year_text='+year_text;
    $.ajax({
        url: SITE_URL + 'filter_incentives_chart',
        type:"POST",
        data:data,
        dataType:'json', 
        success: function(data){
            if(users!='' || role==4)
            {
                
                $('#container1').hide();
                $('#container2').show();
                $('.tab').removeClass('hidden');
                $table_text=data['table_text'];
                $total_incentive=data['total_amount'];
                $table_head=data['table_head'];

                $('.tab_head').append($table_head);

                create_product_list(data['results'],$table_text,$total_incentive);


            }
            else
            {
                first_chart(data);
                $('.tab').addClass('hidden');
                //$('#container2').html('').hide();
                $('#container1').show();
            }
            
            $("#pcont").css("opacity",1);
            $("#loaderID").css("opacity",0);
            
        }
    });
}
var chart1Data = <?php echo $chart1Data1;?>;

if(role==4)
{
    $('#container1').hide();
    $('#container2').show();
    $('.tab').removeClass('hidden');
    $table_text=chart1Data['table_text'];
    $total_incentive=chart1Data['total_amount'];
    $table_head=chart1Data['table_head'];
    $('.tab_head').append($table_head);
    //$product_category=chart1Data['product_category'];
    create_product_list(chart1Data['results'],$table_text,$total_incentive);
}
else
{
    first_chart(chart1Data);
}

function first_chart(chart1Data) 
{
    xAxisCategory = chart1Data['xAxisCategory'];
    yAxisCategory = chart1Data['yAxisCategory'];
    chart1Series = chart1Data['chart1Series'];
    xAxisLable = chart1Data['xAxisLable'];
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
            pointFormat: '{series.name}: <b>{point.y} L  </b>',
            },
            plotOptions: {
                column: {
                    
                    //shadow: false,
                    dataLabels: {
                        enabled: true,
                        color: '#ffffff',
                        formatter:function()
                        {
                            
                            return this.y+' L';
                        },
                         allowOverlap: true
                    }
                },
                series: {
                    cursor: 'pointer',
                    point: {
                        events: {
                            click: function () {
                                //alert(s);
                                $('#container1').hide();
                                $('#container2').show();
                                $('.tab').removeClass('hidden');
                                $('.levels').removeClass('hidden');
                                $('.orders').html('');
                                $('.tab_head').html('');
                                second_chart(this.category);
                            }
                        }
                    }
                }
            },

            series: chart1Series
        });
  
}

function second_chart(cat_name)
{
    $("#pcont").css("opacity",0.5);
    $("#loaderID").css("opacity",1);
    var region = $('.region').val();
    var users=$('.users').val();
    var duration=$('.quarter_id').val();
    var year=$('.year_id').val();
    var duration_text=$('.quarter_id option:selected').text();
    var year_text=$('.year_id option:selected').text();
    var data = 'series_name='+cat_name+'&region='+region+'&users='+users+'&duration='+duration+'&year='+year+'&duration_text='+duration_text+'&year_text='+year_text;
    $('.user_role').val(cat_name);
    $.ajax({
        url: SITE_URL + 'get_incentives_chart2',
        type:"POST",
        data:data,
        dataType:'json', 
        success: function(data){
            $chartdata=data['results'];
            $table_text=data['table_text'];
            $total_amount=data['total_amount'];
            $tab_data=data['table_head'];
            $('.tab_head').append($tab_data);
            create_product_list($chartdata,$table_text,$total_amount);
            $("#pcont").css("opacity",1);
            $("#loaderID").css("opacity",0);
        }
    });

    
}

function create_product_list(data,table_text,total_amount)
{
    $('.tab').removeClass('hidden');
    //$('.table_name').html("<h4>"+name+" For "+category+ " Product List</h4>");
    var j=1;
    var total_qty=0;
    var total_value=0;
    var len=$('.tab_head tr th').length-1;
    $('.table_name').html("<h4><strong> "+table_text+"</strong></h4>");

    $(".orders").append(data);
    /*$.each(data,function(i)
    {   
        var customer_data="<tr> <td style='font-size: 12;' align='center'>"+j+"</td><td  style='font-size :!2;' align='left'>"+data[i].role+"</td><td  style='font-size :!2;' align='left'>"+data[i].name+"</td><td  style='font-size :!2;' align='right'>"+data[i].targets.Radiology.current_target+"</td><td  style='font-size :!2;' align='right'>"+data[i].sales.Radiology.current_sales+"</td><td  style='font-size :!2;' align='right'>"+data[i].targets.CC.current_target+"</td><td  style='font-size :!2;' align='right'>"+data[i].sales.CC.current_sales+"</td><td  style='font-size :!2;' align='right'>"+data[i].incentive_amount+"</td></tr>";
        $(".orders").append(customer_data);
        j++;
       

    });*/
    $('.orders').append("<td  style='font-size :!2;' align='right' colspan="+len+"><strong>Incentives Total </strong></td><td  style='font-size :!2;' align='right'><strong>"+total_amount+"</strong></td>");
    $("#pcont").css("opacity",1);
    $("#loaderID").css("opacity",0);
}
</script>
<style type="text/css">
    text[style="cursor:pointer;color:#909090;font-size:9px;fill:#909090;"]{ display: none;}
</style>