<?php $this->load->view('commons/main_template', $nestedView); ?>
<style type="text/css">
    .radio-inline{ padding-left: 10px !important;}
    .radio-inline input[type="radio"]{ margin: 0px 4px 0px 0px;}
</style>

<div class="cl-mcont">
    <div id="loaderID" style="position:fixed; top:50%; left:58%; z-index:2; opacity:0"><img src="<?php echo assets_url(); ?>images/ajax-loading-img.gif" /></div>
    <div class="row"> 
    	<div class="col-sm-12 col-md-12">
            <div class="block-flat">
                <div class="content">  
                    <div class="row">
                        <div class="form-group">
                            <div class="col-sm-12">
                                <!-- <div class="col-sm-offset-1 col-sm-3 custom_icheck" style="padding-left: 0px !important">
                                    <label class="radio-inline"> 
                                        <div class="iradio_square-blue checked" style="position: relative;" aria-checked="true" aria-disabled="false">
                                            <input type="radio" class="measure" value="1" name="measure" checked style="position: absolute; opacity: 0;">
                                            <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;"></ins>
                                        </div> 
                                        By &nbsp;Qty
                                    </label>
                                    <label class="radio-inline"> 
                                        <div class="iradio_square-blue " style="position: relative; padding-left: 2px;" aria-checked="true" aria-disabled="false">
                                            <input type="radio" class="measure" value="2" name="measure"  style="position: absolute; opacity: 0;">
                                            <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;"></ins>
                                        </div> 
                                        By Value
                                    </label>
                                </div> -->
                                <div class="col-sm-1">
                                </div>
                               
                            </div> 
                        </div>
                    </div>                         
                    <div class="row">
                        <div class="form-group">
                            <div class="col-sm-12">
                                    <?php  if(in_array($this->session->userdata('role_id'),margin_allowed_roles())) { ?>  
                               
                                 <!-- <label class="col-sm-1 control-label" style="margin-top:4px;"> Region : </label> -->
                                 <div class="col-sm-2">
                                    <select class="form-control regions select2" style="width:100%" name="region">
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
                                    <input type="hidden" class="regions" name="region" value="">
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
                                 <div class="col-sm-4">
                                    <div class="btn-group">
                                        <!-- <input type="button" data-id="a" name="timeline" class="timeline btn btn-success" checked  value="All"> -->
                                        <input type="button" data-id="w" name="timeline" class="timeline btn btn-default" value="Week">
                                        <input type="button" data-id="m" name="timeline" class="timeline btn btn-default"  value="Month">
                                        <input type="button" data-id="q" name="timeline" class="timeline btn btn-default" value="Quarter">
                                        <input type="button" data-id="y" name="timeline" class="timeline btn btn-success" value="Year">
                                    </div>
                                </div>
                                <input type="hidden" name="measure" class="measure" value="2">
                                <div class=" col-sm-2 custom_icheck" style="padding-left: 0px !important">
                                    <label class="radio-inline"> 
                                        <div class="iradio_square-blue checked" style="position: relative;" aria-checked="true" aria-disabled="false">
                                            <input type="radio" class="view_page" value="1" name="view_page" checked style="position: absolute; opacity: 0;">
                                            <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;"></ins>
                                        </div> 
                                        By Graph
                                    </label>
                                    <br>
                                    <label class="radio-inline"> 
                                        <div class="iradio_square-blue " style="position: relative;" aria-checked="true" aria-disabled="false">
                                            <input type="radio" class="view_page" value="2" name="view_page"  style="position: absolute; opacity: 0;">
                                            <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;"></ins>
                                        </div> 
                                        By Table
                                    </label>
                                </div>                                
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="col-md-11" align="center">
                                <div id="container1" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
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
<!-- <b style="color:red;">▼ -20</b> -->
<!-- <b style="color:green;">▲ 20</b> -->

<?php $this->load->view('commons/main_footer.php', $nestedView); ?>
<script type="text/javascript">
filterDuration();
$(document).on('click','.view_page',function(){
    $("#pcont").css("opacity",0.5);
    $("#loaderID").css("opacity",1);
    $.ajax({
    context: document.body,
    success: function(s){
      window.location.href = SITE_URL+'tvs_2_report';
    }
  });

});
$(document).on('change','.regions',function(){

    $('.levels').addClass('hidden');
    $('.tab').addClass('hidden');
    $('.orders').html('');
    $('.slider').show();
    var region=$('.regions').val();
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

$('.view_page').click(function(){
    var zone=$('.view_page:checked').val();
    filterReport();
});

$(document).on('change','.users,.regions,.duration',function(){
    var users=$('.users').val();
    var regions=$('.regions').val();
    var duration=$('.duration').val();
    //var top=$('.top').val();
  //  alert(top);
    filterReport();
});
var vtime='y';
//var from_date, to_date;
$('.timeline').click(function(){

    var tl = $(this).attr('data-id');
    vtime = tl;
    $('#date_from').val('');
    $('#date_to').val('');
    $('.timeline').addClass('btn-default').removeClass('btn-success');
    $(this).addClass('btn-success').removeClass('btn-default');
    //var measure=$('.measure:checked').val();
    filterDuration();
    //filterReport();
});
function filterDuration()
{   
    var tl = $('.btn-success').attr('data-id');
    var data='vtime='+tl;
     $.ajax({
        // url: SITE_URL+'get_custom_filter_duration',
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
    /*var from_date = $('#date_from').val();
    var to_date = $('#date_to').val();*/
    var measure=$('.measure').val();
    var groups= '';
    var users=$('.users').val();
    var regions=$('.regions').val();
    var zone=$('.view_page:checked').val();
    var duration=$('.duration').val();
    var duration_text=$('.duration option:selected').text();
    //alert(vtime+from_date+to_date+sales+groups+segment+regions+top);
    var data = 'vtime='+vtime+'&measure='+measure+'&groups='+groups+'&users='+users+'&regions='+regions+'&zone='+zone+'&duration='+duration+'&duration_text='+duration_text;
   
    $.ajax({
        url: SITE_URL + 'targetVsSalesChart2Data',
        type:"POST",
        data:data,
        dataType:'json', 
        success: function(data){
            //console.log(data);
            //alert(data);
            targetVsSalesChart1Data(data);
            $("#pcont").css("opacity",1);
            $("#loaderID").css("opacity",0);
            
        }
    });
}
Highcharts.setOptions({ colors: [ '#F44336','#FF9800','#3F51B5', '#4CAF50', '#9C27B0', '#795548', '#FFEB3B', '#CDDC39','#FF00FF','#FFFC39','#FF00FF']});
var chart1Data = <?php echo $chart1Data;?>;
targetVsSalesChart1Data(chart1Data);
function targetVsSalesChart1Data(chart1Data)  {
    var xAxisCategory=chart1Data['xAxisCategory'];
    var xAxisLable = chart1Data['xAxisLable'];
    var chart1series=chart1Data['chart1series'];
    $('#container1').highcharts({
        chart: {
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
                text: 'Value In Lacs'
            },
            stackLabels: {
             style: {
                color: 'black'
              },
              enabled: true
          }
        },
        tooltip: {
            pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> <br/>',
            shared: true
        },
        plotOptions: {
            column: {
                stacking: 'normal',
                dataLabels: {
                    enabled: true,
                    crop: false,
                    overflow: 'none'
                }
            }
        },
        series: chart1series
    });
}
</script>
<style type="text/css">
    text[style="cursor:pointer;color:#909090;font-size:9px;fill:#909090;"]{ display: none;}
</style>
