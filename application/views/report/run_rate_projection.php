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
                               <!--  <div class="col-sm-1">
                                </div> -->
                                <div class="col-sm-2">
                                    <select class="form-control category_id select2" style="width:100%" name="category_id">
                                        <option value="">Select Category</option>
                                        <?php
                                        foreach ($product_category as $pc) {
                                            $selected='';
                                            if($pc['category_id']==$searchFilters['category_id'])
                                            {
                                                $selected='selected';
                                            }
                                            else
                                            {
                                                $selected='';
                                            }
                                            echo '<option value="'.$pc['category_id'].'"'.$selected.'>'.$pc['name'].'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <select class="form-control product_id select2" style="width:100%" name="product_id">
                                        <option value="">Select Product</option>
                                        <?php
                                        foreach ($products as $pc) {
                                            $selected='';
                                            if($pc['product_id']==@$searchFilters['product_id'])
                                            {
                                                $selected='selected';
                                            }
                                            else
                                            {
                                                $selected='';
                                            }
                                            echo '<option value="'.$pc['product_id'].'"'.$selected.'>'.$pc['description'].'</option>';
                                        }
                                        ?>
                                    </select>
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
                                    <input type="number" name="range" class="form-control range" placeholder="Custom Rate">
                                </div>
                                <div class="col-sm-2 custom_icheck" style="padding-left: 0px !important">
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
                </div>
				<div class="content">
					<div>
						<div class="row year">
							<div class="col-md-12">
								<div id="container1" style="width: 1000px; height: 400px; margin: 0 auto"></div>
							</div> 
						</div>
						<div class="row quarter hidden">
							<div class="col-md-12">
								<div id="quarterrunrateprojection" style="width: 700px; height: 400px; margin: 0 auto"></div>
							</div> 
						</div>
						<div class="row month hidden">
							<div class="col-md-12">
								<div id="month" style="width: 700px; height: 400px; margin: 0 auto"></div>
							</div> 
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

$(document).ready(function(){
    select2Ajax('getUserProductReporteesWithUser', 'getUserProductReporteesWithUser', 0, 0)
});

//filterDuration();
$(document).on('click','.view_page',function(){
    $("#pcont").css("opacity",0.5);
    $("#loaderID").css("opacity",1);
    $.ajax({
    context: document.body,
    success: function(s){
      window.location.href = SITE_URL+'rr_pro_table';
    }
  });

});
$(document).on('change','.duration,.users,.category_id,.product_id',function(){
     $('.levels').addClass('hidden');
    //$('.span').addClass('hidden');
    $('.slider').show();
    var duration=$('.duration').val();
  
    var users=$('.users').val();
    filterReport();
});
$(document).on('blur','.range',function(){
    var range = $(this).val();
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
$(document).on('change','.category_id',function(){
    var category_id=$('.category_id').val();
    var data='category_id='+category_id;
    $.ajax({
        url: SITE_URL + 'dependent_products',
        type:"POST",
        data:data,
        success: function(data){
            $('.product_id').html('');
            $('.product_id').html(data);
            
        }
    });
});
$('.measure').click(function(){
    var measure=$('.measure').val();
    filterReport();
});
var vtime='y';
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
    var range=$('.range').val();
    var category_id=$('.category_id').val();
    var product_id=$('.product_id').val();
    $('.tab').addClass('hidden');
    $('.orders').html('');
    var data = 'from_date='+from_date+'&to_date='+to_date+'&vtime='+vtime+'&region='+region+'&measure='+measure+'&users='+users+'&duration='+duration+'&duration_text='+duration_text+'&range='+range+'&category_id='+category_id+'&product_id='+product_id;
     $.ajax({
        url: SITE_URL + 'filter_runrate_chart',
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
    curr_date =  chart1Data['curr_date'];
    end_date  =  chart1Data['end_date'];
	chart = new Highcharts.Chart({
		chart: {
                renderTo: 'container1',
                type: 'spline'
            },

            title: {
                text: xAxisLable
            },

            xAxis: {
                categories: xAxisCategory
            },
	    yAxis: {
	        title: {
                    text: yAxisCategory
                },
            stackLabels: {
                    enabled: true,
                    style: {
                        fontWeight: 'bold',
                        color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                    },
                    formatter: function () {
	                return this.value + 'L';
	            	}
                }
	    },
	    tooltip: {
	        /* crosshairs: true,
             shared: true,*/
            formatter: function() {
                    present_date = '';
                    var data= this.x;
                    var data1= data.split('<br>');
                    var data2=data1[0].split('-');
                    var month_no=months[data2[0]];
                    var month_num = Number(month_no)+Number(1);
                    var  year_no='20'+Number(data2[1]);
                    var present_date=lastday(year_no,month_num);
                    
                    var hover_date=year_no+'-'+month_num+'-'+present_date;
                    if(this.series.name == 'Closed Won' && (new Date(hover_date).getTime() > new Date(curr_date).getTime() )){
                      return '<b>'+data1[0]+'</b><br/>'+
                      '<b>'+'Avg Conversion Rate : '+data1[1]+
                      '<br/><b>'+'Prediction : '+this.y+'</b>'
                       ;
                    }
                    else if(new Date(hover_date).getTime() > new Date(curr_date).getTime() )
                    {
                         return '<b>'+data1[0]+'</b><br/>'+
                        '<b>'+'Avg Conversion Rate : '+data1[1]+
                        '<br/><b>'+this.series.name+' : '+this.y+'</b>'
                       ;
                    }
                    else {
                     return '<b>'+data1[0]+'</b><br/>'+
                      '<b>'+'Conversion Rate : '+data1[1]+
                      '<br/><b>'+this.series.name+' : '+this.y+'</b>'
                       ;
            }   
                }

	    },
             
	    plotOptions: {
	        spline: {
	            marker: {
	                radius: 4,
	                lineColor: '#666666',
	                lineWidth: 1
	            }
	        },
	        series: {
                    cursor: 'pointer'
                }
	    },
	    series: chart1Series
	});
}

Highcharts.setOptions({ colors: [ '#3F51B5','#FF9800','#4CAF50', '#F44336', '#9C27B0', '#795548', '#FFEB3B', '#CDDC39']});

    var months={
        'Jan':'00',
        'Feb':'01',
        'Mar':'02',
        'Apr':'03',
        'May':'04',
        'Jun':'05',
        'Jul':'06',
        'Aug':'07',
        'Sep':'08',
        'Oct':'09',
        'Nov':'10',
        'Dec':'11'};

    function lastday(year,month){
    return new Date(year,month,0).getDate();
    
    };

</script>
<style type="text/css">
    text[style="cursor:pointer;color:#909090;font-size:9px;fill:#909090;"]{ display: none;}
</style>