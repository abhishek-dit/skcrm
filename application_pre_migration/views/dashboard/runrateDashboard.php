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
		       				<form class="form-horizontal" role="form" action="<?php echo SITE_URL; ?>leadsDashboard"  parsley-validate novalidate method="post">
			       				<input type="hidden" name="action" value="submit">

			       					<div class="col-sm-4">
			       						<?php $user_id = $this->session->userdata('user_id'); ?>
			       						<select class="getUserProductReporteesWithUser" style="width:100%" name="user_id" onchange="this.form.submit()">
			       							<option value="<?php echo $user_id; ?>">
		                                        <?php echo getUserDropDownDetails($user_id); ?>
		                                    </option>
			       						</select>
			       					</div>
			       			</form>
								<div class="col-sm-2">
								</div>
								<div class="col-sm-6" align="right">
									<label>Timeline &nbsp; &nbsp; </label>
									<input type="radio" name="timeline" value="1" class="tline"> Month &nbsp; &nbsp;
									<input type="radio"  name="timeline" value="2" class="tline"> Quarter &nbsp; &nbsp;
									<input type="radio" checked="" name="timeline" value="3" class="tline"> Year
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="content">
								
					<div>
						<div class="row year">
							<div class="col-md-12">
								<div id="yearrunrateprojection" style="width: 1000px; height: 400px; margin: 0 auto"></div>
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


Highcharts.setOptions({ colors: [ '#3F51B5','#FF9800','#4CAF50', '#F44336', '#9C27B0', '#795548', '#FFEB3B', '#CDDC39']});

//var icrm = $.noConflict();
$('.tline').change(function(){
	var timeline = $('.tline:checked').val();
	if(timeline == 1)
	{
		$('.quarter').addClass('hidden');
		$('.year').addClass('hidden');
		$('.month').removeClass('hidden');
	}
	else if(timeline == 2)
	{
		$('.month').addClass('hidden');
		$('.year').addClass('hidden');
		$('.quarter').removeClass('hidden');
	}
	else if(timeline == 3)
	{
		$('.month').addClass('hidden');
		$('.quarter').addClass('hidden');
		$('.year').removeClass('hidden');
	}

});


	$('#yearrunrateprojection').highcharts({
		chart: {
	        type: 'spline'
	    },
	    title: {
	        text: 'Run Rate Projection (Year)'
	    },
	    xAxis: {
	        categories: ['Apr', 'May', 'Jun','Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec','Jan', 'Feb', 'Mar']
	    },
	    yAxis: {
	        title: {
	            text: 'Price'
	        },
	        labels: {
	            formatter: function () {
	                return this.value + 'L';
	            }
	        }
	    },
	    tooltip: {
	        crosshairs: true,
	        shared: true
	    },
	    plotOptions: {
	        spline: {
	            marker: {
	                radius: 4,
	                lineColor: '#666666',
	                lineWidth: 1
	            }
	        }
	    },
	    series: [{
	        name: 'Actual Target',
	        marker: {
	            symbol: 'diamond'
	        },
	        data: [20.0, 20.0, 20.0, 20.0, 20.0, 20.0, 20.0,20.0,20.0,20.0,20.0,20.0]
	    },
	    {
	        name: 'Reached Target',
	        marker: {
	            symbol: 'square'
	        },
	        data: [7.0, 6.0, 9.0, 14.0, 18.0, 21.0, {
	            y: 26.0,
	            marker: {
	                symbol: 'url(https://www.highcharts.com/samples/graphics/sun.png)'
	            }
	        }]

	    }, {
	        name: 'Same RunRate',
	        marker: {
	            symbol: 'diamond'
	        },
	        data: ['', '', '', '', '', '','',30.0,35.0,30.0,30.0,{
	        	y: 36.0,
	            marker: {
	                symbol: 'url(https://www.highcharts.com/samples/graphics/sun.png)'
	            }

	        }]
	    }, {
	        name: '50% RunRate',
	        marker: {
	            symbol: 'diamond'
	        },
	        data: ['', '', '', '', '', '', '',25.0,29.0,31.0,{
	        	y: 23.0,
	            marker: {
	                symbol: 'url(https://www.highcharts.com/samples/graphics/sun.png)'
	            }

	        }]
	    }, {
	        name: '100% RunRate',
	        marker: {
	            symbol: 'diamond'
	        },
	        data: ['', '', '', '', '', '', '',28.0,30.0,{
	        	y: 31.0,
	            marker: {
	                symbol: 'url(https://www.highcharts.com/samples/graphics/sun.png)'
	            }

	        }]
	    }]
	});



	$('#quarterrunrateprojection').highcharts({
		chart: {
        type: 'spline'
    },
    title: {
        text: 'Run Rate Projection (Quarter)'
    },
    xAxis: {
        categories: ['Oct', 'Nov', 'Dec']
    },
    yAxis: {
        title: {
            text: 'Price'
        },
        labels: {
            formatter: function () {
                return this.value + 'L';
            }
        }
    },
    tooltip: {
        crosshairs: true,
        shared: true
    },
    plotOptions: {
        spline: {
            marker: {
                radius: 4,
                lineColor: '#666666',
                lineWidth: 1
            }
        }
    },
    series: [{
        name: 'Actual Target',
        marker: {
            symbol: 'diamond'
        },
        data: [20.0, 20.0, 20.0]
    },
    {
        name: 'Reached Target',
        marker: {
            symbol: 'square'
        },
        data: [5.0,'','']

    }, {
        name: 'Same RunRate',
        marker: {
            symbol: 'diamond'
        },
        data: ['',8.0,7.0]
    }, {
        name: '50% RunRate',
        marker: {
            symbol: 'diamond'
        },
        data: ['',6.0,{
	            y: 9.0,
	            marker: {
	                symbol: 'url(https://www.highcharts.com/samples/graphics/sun.png)'
	            }
	        }]
    }, {
        name: '100% RunRate',
        marker: {
            symbol: 'diamond'
        },
        data: ['',5.0,{
	            y: 15.0,
	            marker: {
	                symbol: 'url(https://www.highcharts.com/samples/graphics/sun.png)'
	            }
	        }]
    }]
	});

	$('#month').highcharts({
	 chart: {
        type: 'column'
    },

    title: {
        text: 'Run Rate Projection (Month)'
    },

    xAxis: {
        categories: ['Oct']
    },

    yAxis: {
        allowDecimals: false,
        min: 0,
        title: {
            text: 'Price'
        }
    },

    tooltip: {
        formatter: function () {
            return '<b>' + this.x + '</b><br/>' +
                this.series.name + ': ' + this.y + '<br/>' +
                'Total: ' + this.point.stackTotal;
        }
    },

    plotOptions: {
        column: {
            stacking: 'normal'
        }
    },

    series: [{
        name: 'Actual Target',
        data: [20],
        stack: 'male'
    },{
        name: 'Reached Target',
        data: [8],
        stack: 'female'
    }]

	});

</script>