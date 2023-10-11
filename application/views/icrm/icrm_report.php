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
                           <div class="col-sm-2 custom_icheck" style="padding-left: 0px !important">
                                <label class="radio-inline"> 
                                    <div class="iradio_square-blue checked" style="position: relative;" aria-checked="true" aria-disabled="false">
                                        <input type="radio" class="zone" value="1" name="zone" checked style="position: absolute; opacity: 0;">
                                        <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;"></ins>
                                    </div> 
                                    By Qty
                                </label><br>
                                <label class="radio-inline"> 
                                    <div class="iradio_square-blue " style="position: relative;" aria-checked="true" aria-disabled="false">
                                        <input type="radio" class="zone" value="2" name="zone"  style="position: absolute; opacity: 0;">
                                        <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;"></ins>
                                    </div> 
                                    By Value
                                </label>
                                    
                            </div>
                            <div class="col-sm-4">
                                <div class="btn-group">
                                
                                <input type="button" data-id="w" name="timeline" class="timeline btn btn-default" value="Week">
                                <input type="button" data-id="m" name="timeline" class="timeline btn btn-success" checked value="Month">
                                <input type="button" data-id="q" name="timeline" class="timeline btn btn-default" value="Quarter">
                                <input type="button" data-id="y" name="timeline" class="timeline btn btn-default" value="Year">
                                </div>
                           </div>
                           <div class="col-sm-2">                            
                                <select class="form-control warehouse" style="width:100%" name="warehouse">
                                    <option value="">Select Regions</option>
                                    <?php
                                    foreach ($regions as $reg) {
                                        echo '<option value="'.$reg['location_id'].'">'.$reg['location'].'</option>';
                                    }
                                    ?>
                                </select>                        
                           </div>
                           <div class="col-sm-2">                            
                                <select class="form-control warehouse" style="width:100%" name="warehouse">
                                    <option value="">Select User</option>
                                    <?php
                                    foreach ($users as $us) {
                                        echo '<option value="'.$us['user_id'].'">'.$us['first_name'].' ('.$us['employee_id'].')'.'</option>';
                                    }
                                    ?>
                                </select>                        
                           </div>
                            <div class="col-sm-2 custom_icheck" style="">
                                <label class="radio-inline"> 
                                    <div class="iradio_square-blue checked" style="position: relative;" aria-checked="true" aria-disabled="false">
                                        <input type="radio" class="zone" value="1" name="zone1" checked style="position: absolute; opacity: 0;">
                                        <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;"></ins>
                                    </div> 
                                    By Region
                                </label><br>
                                <label class="radio-inline"> 
                                    <div class="iradio_square-blue " style="position: relative;" aria-checked="true" aria-disabled="false">
                                        <input type="radio" class="zone" value="2" name="zone1"  style="position: absolute; opacity: 0;">
                                        <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;"></ins>
                                    </div> 
                                    By Product
                                </label>                                    
                            </div>

                                
                            </div>
                        </div>
                    </div>
                </div>
				
				
				<div class="row">
					<div class="col-sm-12">
						<div class="col-md-11" align="center">
							<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
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


<style type="text/css">
    .radio-inline{ padding-left: 10px !important;}
    .radio-inline input[type="radio"]{ margin: 0px 4px 0px 0px;}
</style>
<!-- <b style="color:red;">▼ -20</b> -->
<!-- <b style="color:green;">▲ 20</b> -->

<?php 
$this->load->view('commons/main_footer.php', $nestedView); ?>
<script type="text/javascript">

Highcharts.setOptions({ colors: [ '#3F51B5','#FF9800','#F44336', '#4CAF50', '#9C27B0', '#795548', '#FFEB3B', '#CDDC39','#CDFC37','#FFFC39','#FFEB3B']});

Highcharts.chart('container', {
    chart: {
        type: 'column'
    },
    title: {
        text: ' Target Vs Sales '
    },
    xAxis: {
        categories: ['Previous Targets', 'Previous Sales<b>▼ -8</b>', 'Current Target','Actual Target','Current Sales','Open Orders','Funnel']
    },
    yAxis: {
        min: 0,
        title: {
            text: 'By Quantity'
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
    series: [ 
    { 
        name: 'Cold',
        data: ['','','','','','',4]
    },
     {   name: 'Hot',
        data: ['','','','','','',4]
    },
     {   name: 'Warm',
        data: ['','','','','','',4]
    },{
        name: 'Open Orders',
        data: ['','','','','',2,'']
    }, {
        name: 'Actual Sales',
        data: ['','','','',8,'','']
    },{
        name: 'Actual Target',
        data: ['','','',18,'','','']
    }, {
        name: 'Current Target',
        data: ['','',10,'','','','']
    }, {
        name: 'Previous Sales ',
        data: ['',2,'','','','','']
    }, {
        name: 'Previous Target',
        data: [10,'','','','','','']
    }]
});
</script>
