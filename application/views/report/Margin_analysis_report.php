<?php $this->load->view('commons/main_template', $nestedView);

?>
<div class="cl-mcont">
<div id="loaderID" style="position:fixed; top:50%; left:58%; z-index:2; opacity:0"><img src="<?php echo assets_url(); ?>images/ajax-loading-img.gif" /></div>

<div class="row"> 
    <div class="col-sm-12 col-md-12">
        <div class="block-flat">
            <div class="content">                           
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-12">                     
                           <div class="col-sm-4 custom_icheck" style="padding-left: 0px !important">
                                <label class="radio-inline"> 
                                    <div class="iradio_square-blue checked" style="position: relative;" aria-checked="true" aria-disabled="false">
                                        <input type="radio" class="sales" value="1" name="sales" checked style="position: absolute; opacity: 0;">
                                        <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;"></ins>
                                    </div> 
                                    By &nbsp;Profit
                                </label>
                                <label class="radio-inline"> 
                                    <div class="iradio_square-blue " style="position: relative; padding-left: 2px;" aria-checked="true" aria-disabled="false">
                                        <input type="radio" class="sales" value="2" name="sales"  style="position: absolute; opacity: 0;">
                                        <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;"></ins>
                                    </div> 
                                    By Loss
                                </label>
                                    
                            </div>
                            <div class="col-sm-2"><input type="text" name="date_from" id="date_from" placeholder="From Date" class="form-control" readonly></div>
                                <!-- <label class="col-sm-1">To Date</label> -->
                                <div class="col-sm-2"><input type="text" placeholder="To Date" name="date_to" id="date_to" class="form-control" readonly></div>
                            <div class="col-sm-4">
                                <div class="btn-group">
                                
                                <input type="button" data-id="w" name="timeline" class="timeline btn btn-default" value="Week">
                                <input type="button" data-id="m" name="timeline" class="timeline btn btn-default" checked value="Month">
                                <input type="button" data-id="q" name="timeline" class="timeline btn btn-default" value="Quarter">
                                <input type="button" data-id="y" name="timeline" class="timeline btn btn-success" value="Year">
                                </div>
                           
                             </div>
                           
                          
                           

                                
                        </div>
                    </div>
                </div>
            </div>
                            
            <div class="content">
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-12">
                             <div class="col-sm-4 custom_icheck" style="padding-left: 0px !important">
                                <label class="radio-inline"> 
                                    <div class="iradio_square-blue " style="position: relative;" aria-checked="true" aria-disabled="false">
                                        <input type="radio" class="groups" value="1" name="groups" checked style="position: absolute; opacity: 0;">
                                        <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;"></ins>
                                    </div> 
                                    By Dealer 
                                </label><!-- </div>
                                <div class="col-sm-2 custom_icheck" style=""> -->
                                <label class="radio-inline"> 
                                    <div class="iradio_square-blue checked " style="position: relative;" aria-checked="true" aria-disabled="false">
                                        <input type="radio" class="groups" value="2" name="groups"  style="position: absolute; opacity: 0;">
                                        <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;"></ins>
                                    </div> 
                                    By Product 
                                </label>                                   
                            </div>
                             <?php  if(in_array($this->session->userdata('role_id'),margin_allowed_roles())) { ?> 
                            <div class="col-sm-2">                            
                            <select class="form-control segment" style="width:100%" name="segment">
                                <option value="">Select All Segments</option>
                                <?php
                                foreach ($groups as $pg) {
                                    echo '<option value="'.$pg['group_id'].'">'.$pg['name'].'</option>';
                                }
                                ?>
                            </select>                        
                       </div>
                        <div class="col-sm-2">                            
                            <select class="form-control regions" style="width:100%" name="regions">
                                <option value="">Select All Regions</option>
                                <?php
                                foreach ($regions as $reg) {
                                    echo '<option value="'.$reg['location_id'].'">'.$reg['location'].'</option>';
                                }
                                ?>
                            </select>                        
                       </div> 
                       <?php } ?>
                       <div class="col-sm-2">                            
                            <select class="form-control top" style="width:100%" name="top">
                               <?php
                                for($i=10;$i<=30;$i=$i+10){
                                    echo '<option value="'.$i.'">'.'Top '.$i.'</option>';
                                }
                                ?>
                            </select>                        
                       </div>
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
               
            </div>
        </div>              
    </div>
</div>


<?php 
$this->load->view('commons/main_footer.php', $nestedView); ?>

<script type="text/javascript">
$('.sales').click(function(){
    var sales=$('.sales:checked').val();
    filterReport();
});
$('.groups').click(function(){
    var groups=$('.groups:checked').val();
    alert(groups);
    filterReport();
});
$(document).on('change','.segment,.regions,.top',function(){
    var segment=$('.segment').val();
    var regions=$('.regions').val();
    var top=$('.top').val();
  //  alert(top);
    filterReport();
});
var vtime='y';
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
    var from_date = $('#date_from').val();
    var to_date = $('#date_to').val();
    var sales=$('.sales:checked').val();
    var groups=$('.groups:checked').val();
    var segment=$('.segment').val();
    var regions=$('.regions').val();
    var top=$('.top').val();
    //alert(vtime+from_date+to_date+sales+groups+segment+regions+top);
    var data = 'from_date='+from_date+'&to_date='+to_date+'&vtime='+vtime+'&sales='+sales+'&groups='+groups+'&segment='+segment+'&regions='+regions+'&top='+top;
   
    $.ajax({
        url: SITE_URL + 'getMarginAnalysisChart2Data',
        type:"POST",
        data:data,
        dataType:'json', 
        success: function(data){
            //alert(data);
            marginAnalysisChart1Data(data);
            $("#pcont").css("opacity",1);
            $("#loaderID").css("opacity",0);
            
        }
    });
}


var chart1Data = <?php echo $chart1Data;?>;
marginAnalysisChart1Data(chart1Data);
function marginAnalysisChart1Data(chart1Data)  {
    var xAxisCategory=chart1Data['xAxisCategory'];
    var chart1series=chart1Data['chart1series'];
    $('#container1').highcharts({
        chart: {
            type: 'bar'
        },
        title: {
            text: chart1Data['lable']
        },
        subtitle: {
            text: 'Sales By Product'
        },
        xAxis: [{
            categories: xAxisCategory,
            reversed: false,
            labels: {
                step: 1
            }
        }],
        yAxis: {
            title: {
                text: null
            }
        },
        plotOptions: {
            series: {
                stacking: 'normal'
            }
        },

       tooltip: {
            formatter: function () {
                return '<b>' + this.point.category + '</b><br/>' +
                    'Margin: ' + this.point.y
            }
        },

        series: chart1series
    });
};
</script>