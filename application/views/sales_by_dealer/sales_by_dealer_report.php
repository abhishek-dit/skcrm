<?php $this->load->view('commons/main_template', $nestedView);

?>
<div id="loaderID" style="position:fixed; top:50%; left:58%; z-index:2; opacity:0"><img src="<?php echo assets_url(); ?>images/ajax-loading-img.gif" /></div>

<div class="row"> 
	<div class="col-sm-12 col-md-12">
		<div class="block-flat">
			<div class="content">
				<div class="header">
					
				</div>
				<div class="content">
					<div id="container" style="min-width: 310px; max-width: 800px; height: 400px; margin: 0 auto"></div>			
					<div>
					</div>	
				</div>
                <div class="content">
                    <div class="header">
                    </div>
                    <div class="content">
                        <div id="container1" style="min-width: 310px; max-width: 800px; height: 400px; margin: 0 auto"></div>            
                        <div>
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

var categories = ['MEDINICS','MOHI HEALTH CARE','SHAMBHAVI HEALTH CARE','TECHPARK SALES & SERVICE','MEDITRON','APPOLO SURGICAL','MAPLE KORPORATION','WAVE WORKS'];
$(document).ready(function () {
    Highcharts.chart('container', {
        chart: {
            type: 'bar'
        },
        title: {
            text: 'Margin Analysis Loss'
        },
        subtitle: {
            text: 'Sales By Dealer'
        },
        xAxis: [{
            categories: categories,
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
                return '<b>' + this.series.name + ', Category ' + this.point.category + '</b><br/>' +
                    'Margin: ' + this.point.y
            }
        },

        series: [{
           name:"LOSS",
            data: [0,0,-1.40,-0.25,-0.15,-0.1,0,0]
        }]
    });
});


var categories = ['Ajay Enterprices','UPSC JAIN CHARITABLE HOSPITAL','PLUS HEALTH CARE SOLUTIONS PVT LTD', 'MEDITECH ENTERPRISES','RISIS MEDICARE SYSYTEMS','SKANDA ENTERPRISES','ORBITAL NETWORK MARKETING (P)LTD','SADGURU HEALTH CARE SERVICESPVT LTD','NAMDHARI HEALTH CARE & SYSTEMS','UNIQUE MEDICAL SYSTEMS'];
$(document).ready(function () {
    Highcharts.chart('container1', {
        chart: {
            type: 'bar'
        },
        title: {
            text: 'Margin Analysis Profit'
        },
        subtitle: {
            text: 'Sales By Dealer'
        },
        xAxis: [{
            categories: categories,
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
                return '<b>' + this.series.name + ', Category ' + this.point.category + '</b><br/>' +
                    'Margin: ' + this.point.y
            }
        },

        series: [
        {
            name: 'PROFIT',
            data: [11.0,8.1,8.1,7.9,7.9,7.5,5,4.2,3.5,1.5]
        }]
    });
});
</script>