<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function getCategoryByProducts($region='')
{
	$piedata= array();
	/*$sampleResultSet = array( 
							array('status'=>'Critical Care','tool_count'=>54),
							array('status'=>'Radiology','tool_count'=>21)
						);*/
		$CI = & get_instance(); 
	    $sampleResultSet=$CI->Report_model->get_category_wise_product_list();
	   foreach($sampleResultSet as $row){
		   $resData = array('name' => $row['name'], 'y'=>(int)$row['stock']);
		   $piedata[] = $resData;
		}
		$firstPieData = array();
		$firstPieData []= array('name'=>'Stock','data'=>$piedata);
	//	echo json_encode($firstPieData);exit;
      return json_encode($firstPieData);

}
// updated on 7-12-2017 14:21
function getStockInHandChart2Data($region)
{
	
	$CI= & get_instance();
	$sampleResultSet=$CI->Report_model->get_group_wise_products_by_category();
	$xAxisLable = 'Segment Wise Stock';
	$colors=array('#FFD54F','#3F51B5','#FF9800','#F44336', '#4CAF50', '#9C27B0', '#795548', '#FFEB3B', '#CDDC39', '#42A5F5', '#A1887F', '#99b3ff', '#ffb399', '#66b3ff', '#e87d7d', '#bea7a7', '#d9ff66', '	#66ff8c');
	$c1Data1=array();
	$xAxisCategory=array();
	$i=0;
	foreach ($sampleResultSet as $row)
	{   
		    if($row['stock']!='')
		    {
		    	$c1Data1[]=array('y'=>(int)$row['stock'],'color'=>$colors[$i]);
		    }
		    else
		    {
		    	$c1Data1[]=array('y'=>0,'color'=>$colors[$i]);
		    }
			
		    $xAxisCategory[]=$row['name'];
		    $i++;
	}
	
    $series=array();
	$series[]=array('showInLegend'=>FALSE,'name'=>'Segment Wise Stock','data'=>$c1Data1);
	$chart2Data = array('xAxisCategory'=>$xAxisCategory,'chart1Series'=>$series,'xAxisLable'=>$xAxisLable);
	return json_encode($chart2Data);
}

function getStockInHandChart3Data($region,$segment)
{   
	$c1Data1=array();
	$xAxisCategory =array();
	/*$c1Data1 = array(
						 array('y'=>35),
						 array('y'=>22),
						 array('y'=>32),
						 array('y'=>18),
						 array('y'=>12)
					);*/
	$CI=& get_instance();
	$sampleResultSet=$CI->Report_model->get_product_wise_list_by_product($segment);
	//$i=1;
	$type=array();
    foreach ($sampleResultSet as $row) 
    {   
    	if($row['stock']>0)
    	{
	        if($row['product_type_id']==2)
	        {
	                        $type[]=$row['stock'];
	        }
	        else
	        {
	                        $resData=array('y'=>(int)$row['stock']);
	                        $c1Data1[]=$resData;
	                        $xAxisCategory[]=$row['name'];
	        }
	    }
                    
    }
    $type_arr=array_sum($type);
    $type_arr_count=($type_arr>0)?$type_arr:0;
    $value=array('y'=>$type_arr);
    $x_axis_value=$segment.'_ACC';
    array_push($c1Data1, $value);
    array_push($xAxisCategory, $x_axis_value);

		
	//$xAxisCategory = array("Prod1","Prod2","Prod3","Prod4","Prod5");
   $xAxisLable = 'Products';
	

	$series = array();
	$series []= array('name'=>'Product','data'=>$c1Data1);
	/*$series []= array('name'=>'Lockin Period','data'=>$c1Data2);
	$series []= array('name'=>'Usage','data'=>$c1Data2);
	$series []= array('name'=>'Calibration','data'=>$c1Data2);*/
   // print_r($series);exit;
	$chart1Data = array('xAxisCategory'=>$xAxisCategory,'chart1Series'=>$series,'xAxisLable'=>$xAxisLable);
	$chart1Data = json_encode($chart1Data);
	return $chart1Data;
}
/* file end: ./application/helpers/dashboard_helper.php */


function getOutStandingCollectionChart1Data()
{
	/*$c1Data1 = array(
						 array('y'=>35,'color'=>'#3F51B5'),
						 array('y'=>22,'color'=>'#3F51B5'),
						 array('y'=>32,'color'=>'#3F51B5'),
						 array('y'=>18,'color'=>'#3F51B5'),
						 array('y'=>12,'color'=>'#3F51B5')
					);
		$xAxisCategory = array("Central","East","North","South","West");*/
	$c1Data1=array();
	$xAxisCategory=array();
	$xAxisLable = 'Regions';
	$CI =& get_instance();
	$regions=$CI->Report_model->get_region_wise_outstanding_amount();
	foreach ($regions as $row) {
	  $resData=array('y'=>(float)valueInLakhs($row['outstanding_amount']),'color'=>'#3F51B5');
	  $c1Data1[]=$resData;
	  $xAxisCategory[]=$row['region_name'];
     }
    $series = array();
	$series []= array('name'=>'Outstanding','data'=>$c1Data1);

	$chart1Data = array('xAxisCategory'=>$xAxisCategory,'chart1Series'=>$series,'xAxisLable'=>$xAxisLable);
	$chart1Data = json_encode($chart1Data);
	return $chart1Data;
}

function getOutStandingCollectionChart2Data($region_name)
{
	/*$c1Data1 = array(
						 array('y'=>15,'color'=>'#FFB119'),
						 array('y'=>8,'color'=>'#FFB119'),
						 array('y'=>14,'color'=>'#FFB119'),
						 array('y'=>9,'color'=>'#FFB119'),
						 array('y'=>6,'color'=>'#FFB119')
					);
	$xAxisCategory = array("Customer1","Customer2","Customer3","Customer4","Customer5");*/
	$c1Data1=array();
	$xAxisCategory=array();
	$xAxisLable = 'Customers';
	$CI=& get_instance();
	$customer_list=$CI->Report_model->get_customers_list_by_region_wise($region_name);
	foreach ($customer_list as $row) {
		if($row['outstanding_amount']!=0)
		{
			$resData=array('y'=>(float)valueInLakhs($row['outstanding_amount']),'color'=>'#3F51B5');
			$c1Data1[]=$resData;
			$xAxisCategory[]=$row['name'];
		}
	}

	$series = array();
	$series []= array('name'=>'Outstanding','data'=>$c1Data1);

	$chart1Data = array('xAxisCategory'=>$xAxisCategory,'chart2Series'=>$series,'xAxisLable'=>$xAxisLable);
	$chart1Data = json_encode($chart1Data);
	return $chart1Data;
}

function getOutStandingCollectionChart3Data($region_name,$customer_name)
{
	/*$c1Data1 = array(
						 array('y'=>5,'color'=>'#FF5C4F'),
						 array('y'=>8,'color'=>'#FF5C4F'),
						 array('y'=>6,'color'=>'#FF5C4F'),
						 array('y'=>7,'color'=>'#FF5C4F'),
						 array('y'=>2,'color'=>'#FF5C4F')
					);
	$xAxisCategory = array("325852","985623","568235","582364","458254");*/
	$c1Data1=array();
	$xAxisCategory=array();
	$CI=& get_instance();
	$so_numbers=$CI->Report_model->get_outstanding_amount_by_customer($region_name,$customer_name);
	$xAxisLable = 'Sale Orders';
	foreach ($so_numbers as $row) {
		$resData=array('y'=>(float)valueInLakhs($row['outstanding_amount']),'color'=>'#FF5C4F');
		$c1Data1[]=$resData;
		$xAxisCategory[]=$row['so_number'];
	}

	$series = array();
	$series []= array('name'=>'Outstanding','data'=>$c1Data1);

	$chart1Data = array('xAxisCategory'=>$xAxisCategory,'chart3Series'=>$series,'xAxisLable'=>$xAxisLable);
	$chart1Data = json_encode($chart1Data);
	return $chart1Data;
}

//opportunity lost report
function getOpportunityLostChart1DataReason($reporty_by=1,$searchFilters)
{
	$piedata= array();
	$CI=& get_instance();
	if($searchFilters['users']!='')
    {   
    	//echo $searchFilters['users'];
    	$users=$searchFilters['users'];
    	$reportees=getReportingUsers($users);
    	$user_id=$reportees.','.$users;
    }
    else
    {
    	$users=$CI->session->userdata('user_id');
    	$reportees=$CI->session->userdata('reportees');
    	$user_id=$reportees.','.$users;
    }
    $searchFilters['reportee_users']=$user_id;
	$opp_reasons=$CI->Report_model->get_opportunity_lost_by_reasons($searchFilters);
	$search_user_role=$searchFilters['search_user_role'];
	$colors=array('#FFD54F','#3F51B5','#7ABA7A','#FF9800','#F44336', '#4CAF50', '#9C27B0', '#795548', '#FFEB3B', '#42A5F5', '#CDDC39', '#A1887F', '#99b3ff', '#CC1559', '#6D929B', '#e87d7d', '#bea7a7', '#d9ff66','#717D8C', '#66ff8c');
	$i=0;
	$legend=array();
	//echo $CI->db->last_query();exit;
	foreach($opp_reasons as $row)
	{
		if($i==20)
		{ 
			$i=0;
		}
		$resData = array('name' => $row['name'], 'y'=>(float)valueInLakhs($row['total_count'],2),'color'=>$colors[$i]);
		$legend[]=array('name'=>$row['name'],'color_arr'=>$colors[$i],'value'=>(float)valueInLakhs($row['total_count'],2));
		$piedata[] = $resData;
		$sum[]=$row['total_count'];
		$i++;
	}
	$cumulative_sum=(float)valueInLakhs(array_sum($sum));
	$chart1Data = array();
	$chart1Data []= array('name'=>'Value','colorByPoint'=>true,'data'=>$piedata);
	$year=get_current_fiancial_year();
	if(@$searchFilters['duration_text']!='')
	{
		if($searchFilters['vtime']=='w')
		{
			$text='( '.substr(@$searchFilters['duration_text'],0,5).' ) ('.$cumulative_sum.' L)';
		}
		else
		{
			$text= '( '.@$searchFilters['duration_text'].' ) ('.$cumulative_sum.' L)';
		}
	}
	else
	{
		$text=$year['name'].' ('.$cumulative_sum.' L)';
	}
	$label="By Reason ".$text;
	$chart1DataReason = array('chart1Series'=>$chart1Data,'label'=>$label,'user_role'=>$search_user_role,'reason_legend'=>$legend);
	return $chart1DataReason;

}
function getOpportunityLostChart1DataCompetitor($reporty_by=1,$searchFilters)
{
	$piedata= array();
	$CI=& get_instance();
	if($searchFilters['users']!='')
    {   
    	//echo $searchFilters['users'];
    	$users=$searchFilters['users'];
    	$reportees=getReportingUsers($users);
    	$user_id=$reportees.','.$users;
    }
    else
    {
    	$users=$CI->session->userdata('user_id');
    	$reportees=$CI->session->userdata('reportees');
    	$user_id=$reportees.','.$users;
    }
    $searchFilters['reportee_users']=$user_id;
	$opp_reasons=$CI->Report_model->get_opportunity_lost_by_competitors($searchFilters);
	$search_user_role=$searchFilters['search_user_role'];
	$colors=array('#FFD54F','#3F51B5','#7ABA7A','#FF9800','#F44336', '#4CAF50', '#9C27B0', '#795548', '#FFEB3B', '#42A5F5', '#CDDC39', '#A1887F', '#99b3ff', '#CC1559', '#6D929B', '#e87d7d', '#bea7a7', '#d9ff66','#717D8C', '#66ff8c');
	$i=0;
	$legend=array();
	//echo $search_user_role; exit();
	foreach($opp_reasons as $row){
		if($i==20)
		{ 
			$i=0;
		}
		$resData = array('name' => $row['name'], 'y'=>(float)valueInLakhs($row['total_count'],2),'color'=>$colors[$i]);
		$legend[]=array('name'=>$row['name'],'color_arr'=>$colors[$i],'value'=>(float)valueInLakhs($row['total_count'],2));
		$piedata[] = $resData;
		$sum[]=$row['total_count'];
		$i++;
	}
	$cumulative_sum=(float)valueInLakhs(array_sum($sum));
	$chart1Data = array();
	$chart1Data []= array('name'=>'Value','data'=>$piedata);
	$year=get_current_fiancial_year();
	if(@$searchFilters['duration_text']!='')
	{
		if($searchFilters['vtime']=='w')
		{
			$text='( '.substr(@$searchFilters['duration_text'],0,5).' ) ('.$cumulative_sum.' L)';
		}
		else
		{
			$text= '( '.@$searchFilters['duration_text'].' ) ('.$cumulative_sum.' L)';
		}
	}
	else
	{
		$text=$year['name'].' ('.$cumulative_sum.' L)';
	}
	$label="By Competitor ".$text;
	$com_legend=json_encode($legend);
	$chart1DataReason = array('chart1Series'=>$chart1Data,'label'=>$label,'user_role'=>$search_user_role,'competitor_legend'=>$legend);
    //echo "<pre>"; print_r($chart1DataReason);exit;
	return $chart1DataReason;

}

function getOpportunityLostChart2Data($lost_for,$report_by,$searchFilters)
{
	
    //fetching different groups from product category
    $CI = & get_instance();
    if($searchFilters['users']!='')
    {   
    	//echo $searchFilters['users'];
    	$users=$searchFilters['users'];
    	$reportees=getReportingUsers($users);
    	$user_id=$reportees.','.$users;
    }
    else
    {
    	$users=$CI->session->userdata('user_id');
    	$reportees=$CI->session->userdata('reportees');
    	$user_id=$reportees.','.$users;
    }
    $searchFilters['reportee_users']=$user_id;
	$xAxisLable = 'Regions';
	 switch($report_by)
    {	
    	case 1:
		    // foreach ($product_groups as $pg) {
			//$c1Data=array();
				   
				$region=$CI->Common_model->get_data('location',array('status'=>1,'territory_level_id'=>4));
				$xAxisCategory=array();
				$segment_arr=array();
				$location_segment_arr=array();
				$region_list = array();
				foreach($region as $row)
				{   
					$details=$CI->Report_model->get_lost_opp_details($lost_for,$row['location'],$searchFilters);
					
					foreach ($details as $prow) {
						$c1Data1=array();
				    	$segment_arr[$prow['group_id']]=$prow;
					    $location_segment_arr[$row['location_id']][$prow['group_id']]=$prow;
					    if(!in_array($row['location_id'],$region_list))
						{
							$region_list[] = $row['location_id'];
							$xAxisCategory[]=$row['location'];
						}
					}
					
				}
				
				
				//print_r($region_list); exit();
				foreach($segment_arr as $segment_id => $prow1)
			    {   
			    	$c1Data1=array();
					foreach ($region_list as $row1 => $location_id) 
					{
						$pcount=@$location_segment_arr[$location_id][$prow1['group_id']]['total_count'];
						$count=($pcount>0)?$pcount:0;
						$c1Data1[]=(float)valueInLakhs($count);
					} 
					
					$series[]= array('name'=>$prow1['group_name'],'data'=>$c1Data1);
				}
			   //print_r($series);exit;
		    break;
		case 2:
		   $c1Data=array();
				   
		       $region=$CI->Common_model->get_data('location',array('status'=>1,'territory_level_id'=>4));
		       $xAxisCategory=array();
		       $segment_arr=array();
			   $location_segment_arr=array();
			   $region_list = array();
				foreach($region as $row)
		        {
		    		$details=$CI->Report_model->get_lost_opp_details_by_competitor($lost_for,$row['location'],$searchFilters);
		    		foreach ($details as $prow)
		    		{
		    			$c1Data1=array();
				    	$segment_arr[$prow['group_id']]=$prow;
					    $location_segment_arr[$row['location_id']][$prow['group_id']]=$prow;
					    if(!in_array($row['location_id'],$region_list))
						{
							$region_list[] = $row['location_id'];
							$xAxisCategory[]=$row['location'];
						}
					}
				}
				foreach($segment_arr as $segment_id => $prow1)
			    {   
			    	$c1Data1=array();
					foreach ($region_list as $key => $location_id) 
					{
						$pcount=@$location_segment_arr[$location_id][$prow1['group_id']]['total_count'];
						$count=($pcount>0)?$pcount:0;
						//echo $count.'-';
						$c1Data1[]=(float)valueInLakhs($count);
						
					}
					$series []= array('name'=>$prow1['group_name'],'data'=>$c1Data1);
			    }
			break;
	}

	$chart1Data = array('xAxisCategory'=>$xAxisCategory,'chart2Series'=>$series,'xAxisLable'=>$xAxisLable);
	$chart1Data = json_encode($chart1Data);
	//print_r($chart1Data);exit;
	return $chart1Data;
}

function getOpportunityLostChart3Data($lost_for,$region,$segment,$report_by,$searchFilters)
{
	/*$c1Data1 = array(
						 array('y'=>5),
						 array('y'=>8),
						 array('y'=>6),
						 array('y'=>7),
						 array('y'=>2)
					);
		$xAxisCategory = array("Product1","Product2","Product3","Product4","Product5");*/
	$xAxisLable = 'Customers';
	$CI = & get_instance();
	if($searchFilters['users']!='')
    {   
    	//echo $searchFilters['users'];
    	$users=$searchFilters['users'];
    	$reportees=getReportingUsers($users);
    	$user_id=$reportees.','.$users;
    }
    else
    {
    	$users=$CI->session->userdata('user_id');
    	$reportees=$CI->session->userdata('reportees');
    	$user_id=$reportees.','.$users;
    }
    $searchFilters['reportee_users']=$user_id;
	$xAxisCategory=array();
	$c1Data1=array();
	$series = array();
	switch($report_by)
	{   
		case 1:
			$customers=$CI->Report_model->get_opportunity_products_lost_by_region($lost_for,$region,$segment,$searchFilters);
			$product_arr=array();
			$customer_product_arr=array();
			foreach ($customers as $row) {
				$c1Data1=array();
				$products=$CI->Report_model->get_opportunity_products_lost_by_region_product($lost_for,$region,$segment,$searchFilters,$row['customer_id']);
				
				foreach ($products as $prow) {
					$product_arr[$prow['product_id']]=$prow;
					$customer_product_arr[$row['customer_id']][$prow['product_id']]=$prow;
				}
				$xAxisCategory[]=$row['customer_name'];
			}
			foreach($product_arr as $product_id => $prow1)
			{   
				$c1Data1=array();
				foreach ($customers as $row1) {
					$pcount=@$customer_product_arr[$row1['customer_id']][$prow1['product_id']]['total_count'];
					$count=($pcount>0)?$pcount:0;
					//echo $count.'-';
					$c1Data1[]=(float)valueInLakhs($count);
					
				}
				$series []= array('name'=>$prow1['product_name'],'data'=>$c1Data1);
			}
			
			break;
		case 2:
			$customers=$CI->Report_model->get_opportunity_products_lost_by_competitor($lost_for,$region,$segment,$searchFilters);
			$product_arr=array();
			$customer_product_arr=array();
			foreach ($customers as $row) {
				
				$products=$CI->Report_model->get_opportunity_products_lost_by_competitor_product($lost_for,$region,$segment,$searchFilters,$row['customer_id']);
				//ECHO $CI->db->last_query();exit;
				$c1Data1=array();
				foreach ($products as $prow) {
					$product_arr[$prow['product_id']]=$prow;
					$customer_product_arr[$row['customer_id']][$prow['product_id']]=$prow;
					
					//print_r($series);
				}
				$xAxisCategory[]=$row['customer_name'];
			}
			foreach($product_arr as $product_id => $prow1)
			{   
                $c1Data1=array();
				foreach ($customers as $row1) {
					$pcount=@$customer_product_arr[$row1['customer_id']][$prow1['product_id']]['total_count'];
					$count=($pcount>0)?$pcount:0;
					//echo $count.'-';
					$c1Data1[]=(float)valueInLakhs($count);
					
				}
				$series []= array('name'=>$prow1['product_name'],'data'=>$c1Data1);
			}
			break;
	}
    //print_r($xAxisCategory);exit;
	$chart1Data = array('xAxisCategory'=>$xAxisCategory,'chart3Series'=>$series,'xAxisLable'=>$xAxisLable);
	$chart1Data = json_encode($chart1Data);
	//print_r($chart1Data);exit;
	return $chart1Data;
}

// Fresh Business Report
function getFreshBusinessChart1Data($searchFilters)
{
	
		$c1Data1=array();
		$xAxisCategory=array();
		$xAxisLable = 'Regions';
		$all=0;
	    $CI=& get_instance();
	    $xAxis=array();
	    $regions=$CI->Common_model->get_data('location',array('territory_level_id'=>4));
	    $fresh_business=$CI->Report_model->get_fresh_business_cnotes_by_region($searchFilters);
	     foreach ($fresh_business as $row)
	    {
			$c1Data1[]=array('y'=>(float)valueInLakhs($row['total_value'],2));
			$xAxis[]=$row['location'];
			$xAxisCategory[]=$row['location'];
			$all+=$row['total_value'];
	    }
         foreach ($regions as $reg) {
             if(!in_array($reg['location'],$xAxis))
             {
              $xAxisCategory[]=$reg['location'];
             	$c1Data1[]=array('y'=>0);
             }
         }
	    $all_y=array('y'=>(float)valueInLakhs($all,2));
	    $all_x="ALL";
	    array_unshift($c1Data1, $all_y);
	    array_unshift($xAxisCategory,$all_x);
	    $series = array();
		$series []= array('showInLegend'=>FALSE,'name'=>'Cnote','data'=>$c1Data1);

		$chart1Data = array('xAxisCategory'=>$xAxisCategory,'chart1Series'=>$series,'xAxisLable'=>$xAxisLable);
		$chart1Data = json_encode($chart1Data);
		//print_r($chart1Data);exit;
		return $chart1Data;
}

function getFreshBusinessChart2Data($region_name,$searchFilters)
{
	
	$xAxisLable = 'Sales Engineers';
	$c1Data1=array();
	$xAxisCategory=array();
	$all=0;
	$CI=& get_instance();
	$users=$CI->Report_model->get_cn_by_users($region_name,$searchFilters);
	foreach ($users as $row) {
		$c1Data1[]=array('y'=>(float)valueInLakhs($row['total_value'],2),'color'=>'#FFB119');
		$xAxisCategory[]=$row['name'];
		$all+=$row['total_value'];
	}
	$all_y=array('y'=>(float)valueInLakhs($all,2),'color'=>'#FFB119');
	$all_x="ALL";
	array_unshift($c1Data1, $all_y);
	array_unshift($xAxisCategory,$all_x);
	$series = array();
	$series []= array('showInLegend'=>FALSE,'name'=>'Fresh Business','data'=>$c1Data1);

	$chart1Data = array('xAxisCategory'=>$xAxisCategory,'chart2Series'=>$series,'xAxisLable'=>$xAxisLable);
	$chart1Data = json_encode($chart1Data);
	return $chart1Data;
}

function getFreshBusinessChart3Data($region_name,$employee_name,$searchFilters)
{
	$employeeID = get_string_between($employee_name,'(',')'); // Get the data based on employee ID
	/*$c1Data1 = array(
						 array('y'=>5,'color'=>'#FF5C4F'),
						 array('y'=>8,'color'=>'#FF5C4F'),
						 array('y'=>6,'color'=>'#FF5C4F'),
						 array('y'=>7,'color'=>'#FF5C4F'),
						 array('y'=>2,'color'=>'#FF5C4F')
					);
		$xAxisCategory = array("Customer1","Customer2","Customer3","Customer4","Customer5");*/
	$c1Data1=array();
	$xAxisCategory=array();
	$xAxisLable = 'Customers';
	$all=0;
	$CI=& get_instance();
	$customers=$CI->Report_model->get_cn_by_customers($region_name,$employeeID,$searchFilters);

	foreach ($customers as $row) {
		$c1Data1[]=array('y'=>(float)valueInLakhs($row['total_value'],2),'color'=>'#FF5C4F');
		$xAxisCategory[]=$row['name'];
		$all+=$row['total_value'];
	}
	$all_y=array('y'=>(float)valueInLakhs($all,2),'color'=>'#FF5C4F');
	$all_x="ALL";
	array_unshift($c1Data1, $all_y);
	array_unshift($xAxisCategory,$all_x);
	$series = array();
	$series []= array('showInLegend'=>FALSE,'name'=>'Fresh Business','data'=>$c1Data1);

	$chart1Data = array('xAxisCategory'=>$xAxisCategory,'chart3Series'=>$series,'xAxisLable'=>$xAxisLable);
	$chart1Data = json_encode($chart1Data);
	return $chart1Data;
}
 function getOpenOrderChart1Data($searchFilters)
{
 //fetching different groups from product category
    $CI = & get_instance();
   
   	if($searchFilters['users']!='')
    {   
    	//echo $searchFilters['users'];
    	$users=$searchFilters['users'];
    	$reportees=getReportingUsers($users);
    	$user_id=$reportees.','.$users;
    }
    else
    {
    	$users=$CI->session->userdata('user_id');
    	$reportees=$CI->session->userdata('reportees');
    	$user_id=$reportees.','.$users;
    }
    $searchFilters['reportee_users']=$user_id;
     if($users != $CI->session->userdata('user_id'))
	{
		$l = getUserLocations($users);
		$ul = getQueryArray($l);
		$up = getUserProducts($users);
		$userLocations = ($ul == '')? 0: $ul;
		$userProducts = ($up == '')? 0: $up;
	}
	else
	{
		$userLocations = ($CI->session->userdata('locationString') == '')? 0: $CI->session->userdata('locationString');
		$userProducts = ($CI->session->userdata('products') == '')? 0: $CI->session->userdata('products');
	}
	$role_id=getUserRole($users);
	$searchFilters['userLocations']=$userLocations;
	$searchFilters['userProducts']=$userProducts;
	 if($searchFilters['region']!='')
	{
		$loc=$CI->Common_model->get_data_row('location',array('location_id'=>$searchFilters['region']));
		$location=$loc['location'].' Wise ';
	}
	else
	{
		if($role_id==8||$role_id==9||$role_id==10)
		{
			$location="National Wise ";
		}
		elseif($role_id==6||$role_id==7)
		{
			$location="Region Wise ";
		}
	}
	$year=get_current_fiancial_year();
	if(@$searchFilters['duration_text']!='')
	{
		if($searchFilters['vtime']=='w')
		{
			$text= '( '.substr(@$searchFilters['duration_text'],0,5).' )';
		}
		else
		{
			$text= '( '.@$searchFilters['duration_text'].' )';
		}
	}
	else
	{
		$text=$year['name'];
	}
	$Lable = @$location.'Open Orders '.$text;
	$xAxisLable = 'Categories';
    $product_category=$CI->Common_model->get_data('product_category',array('status',1));
    $c1Data=array();
	$c2Data=array();
	$xAxisCategory=array();
	$not_cleared=$p_not_cleared=0;
	$cleared=$p_cleared=0;
	foreach ($product_category as $pc) {
	    	$details=$CI->Report_model->get_open_order_details($pc['category_id'],$searchFilters);
	    	//echo $CI->db->last_query();exit;
	    	$c1Data[]=(float)valueInLakhs($details['cfi']);
	    	$not_cleared+=$details['cfi'];
			$c2Data[]=(float)valueInLakhs($details['so']);
			$cleared+=$details['so'];
		    $xAxisCategory[]=$pc['name'];
		    if($searchFilters['vtime']=='m'||$searchFilters['vtime']=='q')
		    {
		    	$previous_details=$CI->Report_model->get_previous_open_order_details($pc['category_id'],$searchFilters);
		    	$c3Data[]=(float)valueInLakhs($previous_details['pcfi']);
		    	$p_not_cleared+=$previous_details['pcfi'];
			    $c4Data[]=(float)valueInLakhs($previous_details['pso']);
			    $p_cleared+=$previous_details['pso'];
			}
	}//exit;
	$cn_status=open_order_status();
	if($searchFilters['vtime']=='m'||$searchFilters['vtime']=='q')
		{#3F51B5','#FF9800
			$series[]=array('name'=>$cn_status[2].' ('.valueInLakhs($p_not_cleared).')','data'=>$c3Data,'color'=>'#3F51B5','stack'=>"previous");
			$series[]=array('name'=>$cn_status[3].' ('.valueInLakhs($p_cleared).')','data'=>$c4Data,'color'=>'#FF9800','stack'=>"previous");
		}
	$series[]=array('name'=>$cn_status[0].' ('.valueInLakhs($not_cleared).')','data'=>$c1Data,'color'=>'#FF7260','stack'=>"present Open Orders");
	$series[]=array('name'=>$cn_status[1].' ('.valueInLakhs($cleared).')','data'=>$c2Data,'color'=>'#129793','stack'=>"present Open Orders");
	 
	//print_r($series);exit;
	$chart1Data = array('xAxisCategory'=>$xAxisCategory,'chart1Series'=>$series,'xAxisLable'=>$xAxisLable,'lable'=>$Lable);
	$chart1Data = json_encode($chart1Data);
		// /print_r($chart1Data);exit;
	return $chart1Data;
	
}

function getOpenOrderChart2Data($searchFilters,$status,$category)
{
	
	$xAxisLable = 'Segment Wise Open Orders';
	$CI=& get_instance();
	if($searchFilters['users']!='')
    {   
    	//echo $searchFilters['users'];
    	$users=$searchFilters['users'];
    	$reportees=getReportingUsers($users);
    	$user_id=$reportees.','.$users;
    }
    else
    {
    	$users=$CI->session->userdata('user_id');
    	$reportees=$CI->session->userdata('reportees');
    	$user_id=$reportees.','.$users;
    }
    $searchFilters['reportee_users']=$user_id;
     if($users != $CI->session->userdata('user_id'))
	{
		$l = getUserLocations($users);
		$ul = getQueryArray($l);
		$up = getUserProducts($users);
		$userLocations = ($ul == '')? 0: $ul;
		$userProducts = ($up == '')? 0: $up;
	}
	else
	{
		$userLocations = ($CI->session->userdata('locationString') == '')? 0: $CI->session->userdata('locationString');
		$userProducts = ($CI->session->userdata('products') == '')? 0: $CI->session->userdata('products');
	}
	$searchFilters['userLocations']=$userLocations;
	$searchFilters['userProducts']=$userProducts;
	$c1Data1=array();
	$xAxisCategory=array();
	$all=0;
	$cn_status=open_order_status();
	if($cn_status[0]==$status)
	{
		$status=3;
	}
	elseif($cn_status[1]==$status)
	{
		$status=1;
	}
	if($searchFilters['vtime']=='w'||$searchFilters['vtime']=='y')
	{
		$colors=array('#FFD54F','#3F51B5','#7ABA7A','#FF9800','#F44336', '#4CAF50', '#9C27B0', '#795548', '#FFEB3B', '#42A5F5', '#CDDC39', '#A1887F', '#99b3ff', '#CC1559', '#6D929B', '#e87d7d', '#bea7a7', '#d9ff66','#717D8C', '#66ff8c');
		$i=0;
		$orders=$CI->Report_model->get_open_orders_by_segment($status,$category,$searchFilters);
		foreach ($orders as $row) {
			if($i==20)
			{ 
				$i=0;
			}
			$c1Data1[]=array('y'=>(float)valueInLakhs($row['total_orders']),'color'=>$colors[$i]);
			$xAxisCategory[]=$row['name'];
			$all+=$row['total_orders'];
			$i++;
		}

		$all_y=array('y'=>(float)valueInLakhs($all),'color'=>'#99b3ff');
		$all_x="ALL";
		array_unshift($c1Data1, $all_y);
		array_unshift($xAxisCategory,$all_x);
		$series = array();
		$series []= array('showInLegend'=>FALSE,'name'=>'Open Orders','data'=>$c1Data1);
	}
	else
	{
		$orders=$CI->Report_model->get_open_orders_by_segment($status,$category,$searchFilters);
    	$previous_orders=$CI->Report_model->get_previous_open_orders_by_segment($status,$category,$searchFilters);
    	//echo $CI->db->last_query();exit;
    	$segment_arr=array();
    	$order_segment_arr=array();
    	foreach ($orders as $row) 
    	{
    		$segment_arr[$row['group_id']]=$row['name'];
    		$order_segment_arr[$row['group_id']]['orders']=$row;
    	}
    	//print_r($order_segment_arr);exit;
    	foreach($previous_orders as $prow)
    	{
    		if(!in_array($prow['group_id'], $segment_arr))
    		{
    			$segment_arr[$prow['group_id']]=$prow['name'];
    		}
    		$order_segment_arr[$prow['group_id']]['previous_orders']=$prow;
    	}
    	$c1Data1=array();
    	$c1Data2=array();
    	$all_orders=0;
    	$all_previous_orders=0;
    	foreach ($segment_arr as $key => $value) 
    	{
    		$orders=@$order_segment_arr[$key]['orders']['total_orders'];
    		$order_value=($orders>0)?$orders:0;
    		$c1Data1[]=(float)valueInLakhs($order_value);
    		$pre_orders=@$order_segment_arr[$key]['previous_orders']['previous_total_orders'];
    		$pre_order_value=($pre_orders>0)?$pre_orders:0;
    		$c1Data2[]=(float)valueInLakhs($pre_order_value);
    		$xAxisCategory[]=$value;
    		$all_orders+=$orders;
    		$all_previous_orders+=$pre_orders;
    	}
    	$all_orders_value=(float)valueInLakhs($all_orders);
    	$all_pre_orders_value=(float)valueInLakhs($all_previous_orders);
		$all_x="ALL";
		array_unshift($c1Data1, $all_orders_value);
		array_unshift($c1Data2, $all_pre_orders_value);
		array_unshift($xAxisCategory,$all_x);
    	$series = array();
    	if($status==3)
    	{
    		$cur_name="Present";
    		$cur_color='#FF7260';
    		$previous_cur_name="Carry Forwaded";
    		$pre_cur_color='#3F51B5';
    	}
    	else
    	{
    		$cur_name="Present";
    		$cur_color='#129793';
    		$previous_cur_name="Carry Forwaded";
    		$pre_cur_color='#FF9800';
    	}
		$series []= array('name'=>$previous_cur_name,'data'=>$c1Data2,'color'=>$pre_cur_color);
		$series []= array('name'=>$cur_name,'data'=>$c1Data1,'color'=>$cur_color);
	}
	$chart1Data = array('xAxisCategory'=>$xAxisCategory,'chart2Series'=>$series,'xAxisLable'=>$xAxisLable,'groupCategory'=>$category);
	$chart1Data = json_encode($chart1Data);
	return $chart1Data;
}

function getOpenOrderChart3Data($searchFilters,$status,$category,$segment)
{
	$c1Data1=array();
	$xAxisCategory=array();
	$xAxisLable = 'Customers';
	$all=0;
	$CI=& get_instance();
	if($searchFilters['users']!='')
    {   
    	//echo $searchFilters['users'];
    	$users=$searchFilters['users'];
    	$reportees=getReportingUsers($users);
    	$user_id=$reportees.','.$users;
    }
    else
    {
    	$users=$CI->session->userdata('user_id');
    	$reportees=$CI->session->userdata('reportees');
    	$user_id=$reportees.','.$users;
    }
    $searchFilters['reportee_users']=$user_id;
     if($users != $CI->session->userdata('user_id'))
	{
		$l = getUserLocations($users);
		$ul = getQueryArray($l);
		$up = getUserProducts($users);
		$userLocations = ($ul == '')? 0: $ul;
		$userProducts = ($up == '')? 0: $up;
	}
	else
	{
		$userLocations = ($CI->session->userdata('locationString') == '')? 0: $CI->session->userdata('locationString');
		$userProducts = ($CI->session->userdata('products') == '')? 0: $CI->session->userdata('products');
	}
	$searchFilters['userLocations']=$userLocations;
	$searchFilters['userProducts']=$userProducts;
	$cn_status=open_order_status();
	if($cn_status[0]==$status)
	{
		$status=3;
	}
	elseif($cn_status[1]==$status)
	{
		$status=1;
	}
	$colors=array('#FFD54F','#3F51B5','#7ABA7A','#FF9800','#F44336', '#4CAF50', '#9C27B0', '#795548', '#FFEB3B', '#42A5F5', '#CDDC39', '#A1887F', '#99b3ff', '#CC1559', '#6D929B', '#e87d7d', '#bea7a7', '#d9ff66','#717D8C', '#66ff8c');
	$i=0;
	$cust=$CI->Report_model->get_open_orders_by_customers($searchFilters,$status,$category,$segment);
	$customers=array();
	foreach ($cust as $row) {
		$res=array();
		$res['total_orders']=valueInLakhs($row['total_orders']);
		$res['name']=$row['name'];
		$res['region']=$row['location'];
		/*$res['product_name']=$row['product_name'];
		$res['qty']=$row['qty'];*/
		$customers[]=$res;	
	}

	$pro=$CI->Report_model->get_open_orders_by_products($searchFilters,$status,$category,$segment);
	$products=array();
	foreach ($pro as $row) {
		$res=array();
		$res['total_orders']=valueInLakhs($row['total_orders']);
		$res['name']=$row['product_name'];
		$res['description']=$row['product_description'];
		$res['qty']=$row['stock'];
		$res['segment_name']=$row['segment_name'];
		$products[]=$res;	
	}
	/*$all_y=array('y'=>(float)valueInLakhs($all),'color'=>'#99b3ff');
	$all_x="ALL";
	array_unshift($c1Data1, $all_y);
	array_unshift($xAxisCategory,$all_x);
	$series = array();
	$series []= array('showInLegend'=>FALSE,'name'=>'Open Orders','data'=>$c1Data1);

	$chart1Data = array('xAxisCategory'=>$xAxisCategory,'chart3Series'=>$series,'xAxisLable'=>$xAxisLable);*/
	//print_r($customers);exit;
	$list=array('customers'=>$customers,'products'=>$products);
	$chart1Data = json_encode($list);
	return $chart1Data;
}
function get_filter_funnel_table_list($category,$series_name,$searchFilters)
{
	//print_r($searchFilters); exit;
	$CI=& get_instance();
	if($searchFilters['users']!='')
    {   
    	//echo $searchFilters['users'];
    	$users=$searchFilters['users'];
    	$reportees=getReportingUsers($users);
    	$user_id=$reportees.','.$users;
    }
    else
    {
    	$users=$CI->session->userdata('user_id');
    	$reportees=$CI->session->userdata('reportees');
    	$user_id=$reportees.','.$users;
    }
    $role_id=getUserRole($users);
    $searchFilters['role_id']=$role_id;
    $searchFilters['reportee_users']=$user_id;
     if($users != $CI->session->userdata('user_id'))
	{
		$l = getUserLocations($users);
		$ul = getQueryArray($l);
		$up = getUserProducts($users);
		$userLocations = ($ul == '')? 0: $ul;
		$userProducts = ($up == '')? 0: $up;
	}
	else
	{
		$userLocations = ($CI->session->userdata('locationString') == '')? 0: $CI->session->userdata('locationString');
		$userProducts = ($CI->session->userdata('products') == '')? 0: $CI->session->userdata('products');
	}
	

	$searchFilters['userLocations']=$userLocations;
	$searchFilters['userProducts']=$userProducts;
	if($searchFilters['fy_dates']['start_date']!='')
    {
    	$start_date=$searchFilters['fy_dates']['start_date'];
    	$month=date('m',strtotime($start_date));
    	$month1 = $month + 1;
    	$year=date('Y',strtotime($start_date));
    }
    else
    {
    	$month = date('m');
        $month1 = $month + 1;
        $year = date('Y');		
       
    }
	$day = getOpportunityCategorizationDate();
    $hotDay = $year."-".$month."-".$day;
    $warmDate = date('Y-m-d',strtotime( $hotDay . " +1 month " ));
	$searchFilters['userLocations']=$userLocations;
	$searchFilters['userProducts']=$userProducts;
	if($searchFilters['vtime']=='m') 
	{
		$arr = explode(' (',$searchFilters['search_date']);
		$chart_title = @$arr[0];
	}
	else
	{
		$chart_title=$searchFilters['search_date'];
	}
	if($category=='Lost To Competitor')
    {
        if($series_name=='Others')
        {
            //$('.table_name').html("<h4>Other Competitors Products List</h4>");
            $text='Lost To Other Competitors Products List in '.$chart_title;
        }
        else
        {
            //$('.table_name').html("<h4>"+name+" Products List</h4>");
            $text="Lost To Competitor ".$series_name." Products List in ".$chart_title;
        }
    }
    if($category=='Lost By Reason')
    {
        if($series_name=='Others')
        {
            //$('.table_name').html("<h4>Other Reasons Products List</h4>");
            $text='Lost By Other Reasons Products List in '.$chart_title;
        }
        else
        {
            //$('.table_name').html("<h4>"+name+" Products List</h4>");
            $text="Lost By Reason ".$series_name." Products List in ".$chart_title;
        }
    }
    if($series_name=="Hot" || $series_name=="Warm"|| $series_name=="Cold")
    {
        if ($searchFilters['search_date']=='funnel') {
            //$('.table_name').html("<h4>"+"Product wise "+name+" opportunities as on "+category+"</h4>");
            $text='Product Wise '.$series_name.' Opportunities as on '.$category;
        }
        else
        {
            //$('.table_name').html("<h4>"+"Product wise "+name+" opportunities in "+category+"</h4>");
            $text='Product Wise '.$series_name.' Opportunities in '.$category;
        }
    }
    if($series_name=="Dropped")
    {
        /*$('.table_name').html("<h4>"+name+" Products List</h4>");
        $('.table_reason').removeClass('hidden');*/
        $text=$series_name.' Products List in '.$chart_title;
    }
    if($series_name=="Closed Won")
    {
        /*$('.table_name').html("<h4>"+name+" Products List</h4>");
        $('.table_won').removeClass('hidden');*/
        $text=$series_name.' Products List in '.$chart_title;
    }
    //echo $searchFilters['search_date']; exit();

	if($category=='Lost To Competitor')
	{   
		//print_r($searchFilters);
		$results=$CI->Report_model->get_lost_competitor_list_product($searchFilters,$series_name);
	}
	elseif ($category=='Lost By Reason') {
		$results=$CI->Report_model->get_lost_reason_list_product($searchFilters,$series_name);
		//echo $CI->db->last_query();exit;
	}
	elseif($series_name=='Dropped')
	{   
		$results=$CI->Report_model->get_dropped_product_list($searchFilters,$series_name,$category);
	}
	elseif ($series_name=='Closed Won') {
		$results=$CI->Report_model->get_closed_won_product_list($searchFilters,$series_name,$category);
	}
	elseif($searchFilters['search_date']=="New")
	{
		$time_par="between";
		$searchFilters['category_timeline']=$category;
		$results=$CI->Report_model->get_funnel_product_list($searchFilters,$series_name,$time_par,$hotDay,$warmDate);
		//echo $CI->db->last_query();exit;
	}
	else
	{
		$fy_dates=$searchFilters['fy_dates'];
		//print_r($fy_dates);

		if($searchFilters['vtime']=='q'|| $searchFilters['vtime']=='y')
		{
			$date=date('Y-m-d',strtotime($category));
			
			if(date('Y-m-d')<$fy_dates['end_date'])
			{
				$end_date=date('Y-m-d');
			}
			else
			{
				$end_date=$fy_dates['end_date'];
			}
			if($fy_dates['start_date']==$date)
			{
				$time_par="previous";
			}
			elseif ($end_date==$date)
			{
				$time_par="present";
			}
		}
		else
		{
			if($category==date('jM',strtotime($fy_dates['start_date'])))
			{
				$time_par="previous";
			}
			else {
				$time_par="present";
			}
			$yr=date('Y',strtotime($fy_dates['start_date']));
			$date=date('Y-m-d',strtotime($category.' '.$yr));
			
		}
		
		//echo $date.'-->';
		if($date==$fy_dates['start_date'])
		{
			$st_date = date('Y-m-d',strtotime( $date . " -1 day " ));
		}
		else
		{
			$st_date = $date;
		}
		$month=date('m',strtotime($st_date));
    	$year=date('Y',strtotime($st_date));
		$day = getOpportunityCategorizationDate();
	    $hotDay = $year."-".$month."-".$day;
	    $warmDate = date('Y-m-d',strtotime( $hotDay . " +1 month " ));
	    $results=$CI->Report_model->get_funnel_product_list($searchFilters,$series_name,$time_par,$hotDay,$warmDate);
	}
	$res=array('results'=>$results,'lable'=>$text);
	return json_encode($res);
	//return json_encode($results);

}

 function getOpenOpportunitiesChart1Data($searchFilters)
{	
	$searchFilters=$searchFilters;
 	$CI = & get_instance();
	$xAxisLable = 'Week Wise Open Opportunities';
	$fy_weeks=$CI->Report_model->get_financial_year_weeks($searchFilters);
	$week_count= count($fy_weeks);
	$c1Data1=array();
	$xAxisCategory=array();
	$i=1;
	foreach ($fy_weeks as $row)
	{   
		if($i<$week_count)
		{
			$res=$CI->Report_model->get_week_wise_open_opportunites($row['start_date'],$searchFilters);
			//echo $CI->db->last_query();exit;
			$c1Data1[]=array('y'=>(float)valueInLakhs($res['opp_value'],2),'color'=>'#FF5C4F');
		    $xAxisCategory[]='WK'.$i.'Opening';
	    }
	    elseif($i==$week_count)
	    {
	    	
		    $res=$CI->Report_model->get_week_wise_open_opportunites($row['start_date'],$searchFilters);
			$c1Data1[]=array('y'=>(float)valueInLakhs($res['opp_value'],2),'color'=>'#FF5C4F');
		    $xAxisCategory[]='WK'.$i.'Opening';
		    $res=$CI->Report_model->get_week_wise_open_opportunites($row['end_date'],$searchFilters);
			$c1Data1[]=array('y'=>(float)valueInLakhs($res['opp_value'],2),'color'=>'#FFB119');
		    $xAxisCategory[]='WK'.$i.'Closing';
	    }
	    $i++;
	}
	
    $series=array();
	$series[]=array('showInLegend'=>FALSE,'name'=>'Open Opportunites','data'=>$c1Data1);
	$chart1Data = array('xAxisCategory'=>$xAxisCategory,'chart1Series'=>$series,'xAxisLable'=>$xAxisLable);
	//$chart1Data = json_encode($chart1Data);
	//print_r($chart1Data);exit;
	return $chart1Data;
	
}

function getOpenOpportunitiesChart2Data($searchFilters)
{
	
	$chart2Series=array();
	$CI=& get_instance();
	$last_day=get_last_day_of_month($searchFilters);
	$res=$CI->Report_model->get_segment_wise_open_opportunities($last_day['end_date'],$searchFilters);
	foreach($res as $row)
	{
		$chart2Series[]=array('name'=>$row['name'],'y'=>(float)valueInLakhs($row['opp_value'],2));
	}
	$chart2Series[]=array('name'=>'Total','isSum'=>true,'color'=>'#FF5C4F');
	$chart2Data=$chart2Series;
	//print_r($chart2Data);exit;
    return $chart2Data;
}

function getOpenOpportunitiesChart3Data($searchFilters)
{
	
	$chart3Series=array();
	$CI=& get_instance();
	$last_day=get_last_day_of_month($searchFilters);
	$res=$CI->Report_model->get_reason_wise_open_opportunities($last_day['end_date'],$searchFilters);
	foreach($res as $row)
	{
		$chart3Series[]=array('name'=>$row['name'],'y'=>(float)valueInLakhs($row['opp_value'],2));
	}
	$chart3Series[]=array('name'=>'Total','isSum'=>true,'color'=>'#FF5C4F');
	$chart3Data=$chart3Series;
	//print_r($chart2Data);exit;
    return $chart3Data;
}
function getOpenOpportunitiesChart4Data($searchFilters)
{
	$CI = & get_instance();
	$xAxisLable = 'Weeks';
    $series=array();
	$product_groups=$CI->Report_model->get_product_group_from_products($searchFilters['products']);
	//print_r($product_groups);exit;
	foreach ($product_groups as $pg)
    {
		$fy_weeks=$CI->Report_model->get_financial_year_weeks($searchFilters);
		$week_count= count($fy_weeks);
		$c1Data1=array();
		$i=1;
		$xAxisCategory=array();
				
		foreach ($fy_weeks as $row)
		{   
			if($i<$week_count)
			{
				$res=$CI->Report_model->get_week_wise_segment_open_opportunites($row['start_date'],$pg['group_id'],$searchFilters);
				$c1Data1[]=array('y'=>(float)valueInLakhs($res['opp_value'],2));
				$xAxisCategory[]='WK'.$i.'Opening';
		    }
		    elseif($i==$week_count)
		    {
		    	
			    $res=$CI->Report_model->get_week_wise_segment_open_opportunites($row['start_date'],$pg['group_id'],$searchFilters);
				$c1Data1[]=array('y'=>(float)valueInLakhs($res['opp_value'],2));
			    $xAxisCategory[]='WK'.$i.'Opening';
			    $res=$CI->Report_model->get_week_wise_segment_open_opportunites($row['end_date'],$pg['group_id'],$searchFilters);
				$c1Data1[]=array('y'=>(float)valueInLakhs($res['opp_value'],2));
			    $xAxisCategory[]='WK'.$i.'Closing';
		    }
			    $i++;
			}
				$series[]=array('name'=>$pg['name'],'data'=>$c1Data1);
	}
	$chart4Data = array('xAxisCategory'=>$xAxisCategory,'chart4Series'=>$series,'xAxisLable'=>$xAxisLable);
	$chart4Data = $chart4Data;
	//print_r($chart4Data);exit;
	return $chart4Data;
}

function getMarginAnalysisChart1Data($searchFilters)
{
	$xAxisCategory=array();
	$series=array();
	$c1Data1=array();
	//$series[]=array('name'=>'margin','data'=>$c1Data1);
	$CI = & get_instance();
	switch($searchFilters['groups'])
	{
		// By Delear wise
		case 1:
			//$results=$CI->Report_model->get_margin_by_dealer_wise($searchFilters);
		// By product wise
		case 2 :
			$results=$CI->Report_model->get_margin_by_product_wise($searchFilters);
	}
	//print_r($results);exit;
	foreach ($results as $row)
	{
		$c1Data1[]=(int)$row['margin'];
		$xAxisCategory[]=$row['product_name'];
	}
	if($searchFilters['sales']==1)
	{
		$text='Margin Analysis Profit';
	}
	else
	{
		$text='Margin Analysis Loss';
	}
	$series[]=array('name'=>'Margin Analysis','data'=>$c1Data1);

	$chart1Data = array('xAxisCategory'=>$xAxisCategory,'chart1series'=>$series,'lable'=>$text);
	$chart1Data = json_encode($chart1Data);
	return $chart1Data;
}

function getTargetVsSalesChart1Data($searchFilters)
{   
	$CI=& get_instance();
	if($searchFilters['users']!='')
    {
    	$users=$searchFilters['users'];
    	$reportees=getReportingUsers($users);
    	$user_id=$reportees.','.$users;
    }
    else
    {
    	$users=$CI->session->userdata('user_id');
    	$reportees=$CI->session->userdata('reportees');
    	$user_id=$reportees.','.$users;
    }
	
	$role_id=getUserRole($users);
    if($users != $CI->session->userdata('user_id'))
	{
		$l = getUserLocations($users);
		$ul = getQueryArray($l);
		$up = getUserProducts($users);
		$userLocations = ($ul == '')? 0: $ul;
		$userProducts = ($up == '')? 0: $up;
	}
	else
	{
		$userLocations = ($CI->session->userdata('locationString') == '')? 0: $CI->session->userdata('locationString');
		$userProducts = ($CI->session->userdata('products') == '')? 0: $CI->session->userdata('products');
	}
    $searchFilters['user_reportees_tvs']=$user_id;
    $searchFilters['userProducts']=$userProducts;
    $searchFilters['userLocations']=$userLocations;
	 if($searchFilters['fy_dates']['start_date']!='')
    {
    	$start_date=$searchFilters['fy_dates']['start_date'];
    	$month=date('m',strtotime($start_date));
    	$month1 = $month + 1;
    	$year=date('Y',strtotime($start_date));
    }
    else
    {
    	$month = date('m');
        $month1 = $month + 1;
        $year = date('Y');		
    }	
    $day = getOpportunityCategorizationDate();
    $hotDay = $year."-".$month."-".$day;
    $warmDate = date('Y-m-d',strtotime( $hotDay . " +1 month " ));
    if($searchFilters['zone']==1)//graph
    {
	    //fetching previous targets
	    $previous_target=$CI->Report_model->get_previous_target($searchFilters);
	    //fetching previous sales
	    $previous_sales=$CI->Report_model->get_previous_sales($searchFilters);
	    // print_r($previous_sales);
	    if($previous_sales['previous_sales']==''){ $previous_sales = 0;}
	    else { $previous_sales = $previous_sales['previous_sales']; }
        //fetching current target
	    $current_target=$CI->Report_model->get_current_target($searchFilters);
	    //fetching current sales
	    $current_sales=$CI->Report_model->get_current_sales($searchFilters);
	    if($current_sales['current_sales']==''){ $current_sales = 0;}
	    else { $current_sales = $current_sales['current_sales']; }
	    
	    //fetching open orders
	    $open_orders=$CI->Report_model->get_open_orders($searchFilters);
	    if($open_orders['open_orders']==''){ $open_orders = 0;}
	    else { $open_orders = $open_orders['open_orders']; }
	    //fetching open opportunites
	    $open_opportunities=$CI->Report_model->get_open_opportunity($user_id, $role_id, $userProducts, $userLocations, $hotDay, $warmDate,$searchFilters);
	  // echo $CI->db->last_query();exit;
	    if($open_opportunities['Cold']==''){ $cold = 0;}
	    else { $cold = $open_opportunities['Cold']; }
	    if($open_opportunities['Hot']==''){ $hot = 0;}
	    else { $hot = $open_opportunities['Hot']; }
	    if($open_opportunities['Warm']==''){ $warm = 0;}
	    else { $warm = $open_opportunities['Warm']; }

	   $down=$previous_target['previous_target']-$previous_sales;
	   if($down>0)
	   {
	   		$down=$down;
	   }
	   else
	   {
	   	$down=0;
	   }
	   if($searchFilters['vtime']=='y')
	   {
	   	$down=0;
	   }
	   $pending=$down+$current_target['current_target']-$current_sales-$open_orders;
	   if($pending <=0)
	   {
	   	$pending=0;
	   }
	   $cumulative_target=$down+$current_target['current_target'];
	   if($cumulative_target<=0)
	   {
	   	$cumulative_target=0;
	   }

	    $xAxisCategory=array('Target' ,'Acheived','Backlog','Funnel');
		$text='Target Vs Sales';
		$series = array();
		
		$series[] = array('name'=>'Hot','data'=>array('','','',(round($hot,2))));
		$series[] = array('name'=>'Warm','data'=>array('','','',(round($warm,2))));
		$series[] = array('name'=>'Cold','data'=>array('','','',(round($cold,2))));
		$series[] = array('name'=>'Backlog','data'=>array('','',(round($pending,2)),''));
		$series[] = array('name'=>'Current Sales','data'=>array('',(round($current_sales,2)),'',''));
		$series[] = array('name'=>'Open Orders','data'=>array('',(round($open_orders,2)),'',''));
		/*$series[] = array('name'=>'Cumulative Target','data'=>array('','',(int)$cumulative_target,'','','',''));*/
		$series[] = array('name'=>'Current Target','data'=>array((round($current_target['current_target'],2)),'','',''));
		if($searchFilters['vtime']!='y')
		{
			$series[] = array('name'=>'BackLog','data'=>array((round($down,2)),'','',''));
		}
		/*$series[] = array('name'=>'Previous Sales','data'=>array('',(int)$previous_sales,'','','','',''));
		$series[] = array('name'=>'Previous Target','data'=>array((int)$previous_target['previous_target'],'','','','','',''));*/
	    //echo json_encode($series);exit;

		$chart1Data = array('xAxisCategory'=>$xAxisCategory,'chart1series'=>$series,'lable'=>$text);
		$chart1Data = json_encode($chart1Data);
		//print_r($chart1Data);exit;
		return $chart1Data;
	}
	else if($searchFilters['zone']==2)//table
	{   
		if($searchFilters['groups']==1)
		{  
		   //previous target
		   $previous_target_category=$CI->Report_model->get_user_assigned_target_category($searchFilters);
		   foreach ($previous_target_category as $key => $value) 
		   { 
		   		   $category_id = $value['category_id'];
			   		$group_id = '';
			   		$product_id = '';
			   		$previous_target=$CI->Report_model->get_previous_target_by_category_table($searchFilters,$category_id);
			   		$previous_sales =$CI->Report_model->get_previous_sales_category($searchFilters,$category_id,$group_id,$product_id);
			   		$current_target = $CI->Report_model->get_current_target_category($searchFilters,$category_id,$group_id,$product_id);
			   		$current_sales = $CI->Report_model->get_current_sales_category($searchFilters,$category_id,$group_id,$product_id);
			   		$open_orders =  $CI->Report_model->get_open_orders_category($searchFilters,$category_id,$group_id,$product_id);
			   		$funnel_open_opp =$CI->Report_model->get_open_opportunity_category($user_id, $role_id, $userProducts, $userLocations, $hotDay, $warmDate,$searchFilters,$category_id,$group_id,$product_id);

			   		$backlog = $previous_target-$previous_sales;
			   		if($backlog<0) { $backlog = 0;}
			   		$cumm_target = $backlog+$current_target;
			   		$pending = $backlog+$current_target-$current_sales-$open_orders;
			   		if($pending<0) { $pending = 0;}
			   		$previous_target_category[$key]['backlog'] = $backlog;
					$previous_target_category[$key]['previous_sales'] = $previous_sales;
			   		
			   		$previous_target_category[$key]['current_target'] = $current_target;
			   		$previous_target_category[$key]['cumm_target'] = $cumm_target;
			   		$previous_target_category[$key]['current_sales'] = $current_sales;
			   		$previous_target_category[$key]['open_orders'] = $open_orders;
					$previous_target_category[$key]['funnel_open_opp_hot'] = $funnel_open_opp['Hot'];
					$previous_target_category[$key]['funnel_open_opp_warm'] = $funnel_open_opp['Warm'];
					$previous_target_category[$key]['funnel_open_opp_cold'] = $funnel_open_opp['Cold'];
					$previous_target_category[$key]['pending'] = $pending;
					//segment
			   		$segment_list = $CI->Report_model->get_user_assigned_segment_list($searchFilters,$value['category_id']);
			   		foreach ($segment_list as $key1 => $segment1) 
			   		{
			   			//if($segment1['previous_target']>0)
			   			//{
			   				$previous_target_category[$key]['segment_list'][$key1] = $segment1;

			   				$category_id = '';
					   		$group_id = $segment1['group_id'];
					   		$product_id = '';
					   		$pt=$CI->Report_model->get_previous_target_by_segment_table($searchFilters,$category_id,$group_id,$product_id);
					   		/*echo $CI->db->last_query();
					   		print_r($pt);exit;*/
					   		$previous_sales2 =$CI->Report_model->get_previous_sales_category($searchFilters,$category_id,$group_id,$product_id);

					   		$current_target = $CI->Report_model->get_current_target_category($searchFilters,$category_id,$group_id,$product_id);
					   		$current_sales = $CI->Report_model->get_current_sales_category($searchFilters,$category_id,$group_id,$product_id);
					   		$open_orders =  $CI->Report_model->get_open_orders_category($searchFilters,$category_id,$group_id,$product_id);
					   		$funnel_open_opp =$CI->Report_model->get_open_opportunity_category($user_id, $role_id, $userProducts, $userLocations, $hotDay, $warmDate,$searchFilters,$category_id,$group_id,$product_id);
					   		$backlog = $pt['previous_target']-$previous_sales2;
					   		if($backlog<0) { $backlog = 0;}
					   		$cumm_target = $backlog+$current_target;
					   		$pending = ($backlog+$current_target)-$current_sales-$open_orders;
					   		if($pending<0) { $pending = 0;}
					   		$previous_target_category[$key]['segment_list'][$key1]['backlog'] = $backlog;
							$previous_target_category[$key]['segment_list'][$key1]['previous_sales'] = $previous_sales2;
					   		
					   		$previous_target_category[$key]['segment_list'][$key1]['current_target'] = $current_target;
					   		$previous_target_category[$key]['segment_list'][$key1]['cumm_target'] = $cumm_target;
					   		$previous_target_category[$key]['segment_list'][$key1]['current_sales'] = $current_sales;
					   		$previous_target_category[$key]['segment_list'][$key1]['open_orders'] = $open_orders;
							$previous_target_category[$key]['segment_list'][$key1]['funnel_open_opp_hot'] = $funnel_open_opp['Hot'];
							$previous_target_category[$key]['segment_list'][$key1]['funnel_open_opp_warm'] = $funnel_open_opp['Warm'];
							$previous_target_category[$key]['segment_list'][$key1]['funnel_open_opp_cold'] = $funnel_open_opp['Cold'];
							$previous_target_category[$key]['segment_list'][$key1]['pending'] = $pending;
			   			//}
			   		}
		   		
		   	}
		   foreach ($previous_target_category as $key => $data1) 
		   {
		   		//if($data1['qty']!='')
		   		//{
		   			foreach ($data1['segment_list'] as $key1 => $value) 
			   		{
			   			/*$data2 = $CI->Report_model->user_assigned_product_list($searchFilters,$value['group_id']);
		   				foreach ($data2 as $key2 => $value2) 
		   				{*/
		   					//if($value2['previous_target']>0)
		   				//	{
		   						//$previous_target_category[$key]['segment_list'][$key1]['product_list'][$key2] = $value2;
		   						$ss=$value['group_id'];
			   					$category_id = '';
						   		$group_id = '';
						   		$product_id = 1;
						   		$pt3=$CI->Report_model->get_previous_target_by_product_table($searchFilters,$category_id,$group_id,$product_id,$ss);
						   		$previous_sales3 =$CI->Report_model->get_previous_sales_category($searchFilters,$category_id,$group_id,$product_id,$ss);
						   		$current_target = $CI->Report_model->get_current_target_category($searchFilters,$category_id,$group_id,$product_id,$ss);
						   		$current_sales = $CI->Report_model->get_current_sales_category($searchFilters,$category_id,$group_id,$product_id,$ss);
						   		$open_orders =  $CI->Report_model->get_open_orders_category($searchFilters,$category_id,$group_id,$product_id,$ss);
						   		$funnel_open_opp =$CI->Report_model->get_open_opportunity_category($user_id, $role_id, $userProducts, $userLocations, $hotDay, $warmDate,$searchFilters,$category_id,$group_id,$product_id,$ss);
						   		foreach ($pt3 as $key3 => $value3) {
						   			$previous_target_category[$key]['segment_list'][$key1]['product_list'][$value3['product_id']]['previous_target'] = $value3['previous_target'];
						   		}
						   		foreach ($previous_sales3 as $k4 => $v4) {
						   			$previous_target_category[$key]['segment_list'][$key1]['product_list'][$v4['product_id']]['previous_sales'] = $v4['previous_sales'];
						   		}
						   		foreach ($current_target as $k5 => $v5) {
						   			
						   			$previous_target_category[$key]['segment_list'][$key1]['product_list'][$v5['product_id']]['current_target'] = $v5['current_target'];
						   		}
						   		foreach ($current_sales as $k6 => $v6) {
						   			$previous_target_category[$key]['segment_list'][$key1]['product_list'][$v6['product_id']]['current_sales'] = $v6['current_sales'];
						   		}
						   		foreach ($open_orders as $k7 => $v7) {
						   			$previous_target_category[$key]['segment_list'][$key1]['product_list'][$v7['product_id']]['open_orders'] = $v7['open_orders'];
						   		}
						   		foreach ($funnel_open_opp as $k8 => $v8) {
						   			$previous_target_category[$key]['segment_list'][$key1]['product_list'][$v8['product_id']]['funnel_open_opp_hot'] = $v8['Hot'];
									$previous_target_category[$key]['segment_list'][$key1]['product_list'][$v8['product_id']]['funnel_open_opp_warm'] = $v8['Warm'];
									$previous_target_category[$key]['segment_list'][$key1]['product_list'][$v8['product_id']]['funnel_open_opp_cold'] = $v8['Cold'];
								}
						   		/*$backlog = $pt3['previous_target']-$previous_sales3;
						   		if($backlog<0) { $backlog = 0;}
						   		$cumm_target = $backlog+$current_target;
						   		$pending = ($backlog+$current_target)-$current_sales-$open_orders;
						   		if($pending<0) { $pending = 0;}*/
						   		//$previous_target_category[$key]['segment_list'][$key1]['product_list'][$key2]['backlog'] = $backlog;
								//$previous_target_category[$key]['segment_list'][$key1]['product_list'][$key2]['previous_sales'] = $previous_sales3;
						   		
						   		//$previous_target_category[$key]['segment_list'][$key1]['product_list'][$key2]['current_target'] = $current_target;
						   		//$previous_target_category[$key]['segment_list'][$key1]['product_list'][$key2]['cumm_target'] = $cumm_target;
						   		//$previous_target_category[$key]['segment_list'][$key1]['product_list'][$key2]['current_sales'] = $current_sales;
						   		//$previous_target_category[$key]['segment_list'][$key1]['product_list'][$key2]['open_orders'] = $open_orders;
								
		   					//}
		   				//}
			   		}
		   		//}
		   }
		 //  print_r($previous_target_category);exit;
		   return $previous_target_category;

		}
		else if($searchFilters['groups']==2)
		{
			$location_wise = $CI->Common_model->get_data('location',array('territory_level_id'=>4,'status'=>1));

			//fetching previous target by region wise
			$user_target_arr=array();
			foreach($location_wise as $loc) 
			{
				$region_users= report_user_locations_by_region($loc['location_id']);
				$prev = $CI->Report_model->user_targets_per_region($searchFilters,$region_users);
				$user_target_arr[$loc['location']]= $prev['pt'];
			}
			$prev_target_arr=array();
			foreach($location_wise as $loc) 
			{
				$region_users= report_user_locations_by_region($loc['location_id']);
				$prev = $CI->Report_model->get_previous_target_by_region($searchFilters,$region_users);
			    //echo $CI->db->last_query();exit;
				$prev_target_arr[$loc['location']]= $prev['previous_target'];
			}
			//$location_wise = $CI->Common_model->get_data('location',array('territory_level_id'=>4,'status'=>1));

			//fetching previous target by region wise
			$curr_target_arr=array();
			foreach($location_wise as $loc) 
			{
				$region_users= report_user_locations_by_region($loc['location_id']);
				$cur_target = $CI->Report_model->get_current_target_by_region($searchFilters,$region_users);
				$curr_target_arr[$loc['location']] = $cur_target['current_target'];
			}

			//fetching previous sales by region wise
			$previous_sales_by_region =$CI->Report_model->get_previous_sales_by_region($searchFilters);
			$prev_sales_arr = array();
			if(count($previous_sales_by_region)>0)
			{
				foreach ($previous_sales_by_region as $key => $value)
				{
					$prev_sales_arr[$value['location']]['prev_sales'] = $value['previous_sales'];
				}
			}

			// fetching previous sales by region wise
            $current_sales_by_region =$CI->Report_model->get_current_sales_by_region($searchFilters);
            $curr_sales_arr = array();
            if(count($current_sales_by_region)>0)
            {
            	foreach ($current_sales_by_region as $key => $value) 
            	{
            		$curr_sales_arr[$value['location']]['curr_sales'] = $value['current_sales'];
            	}
            }

            $open_orders_by_region =$CI->Report_model->get_open_orders_by_region($searchFilters);
            //echo $CI->db->last_query();exit;
            $open_order_arr = array();
            if(count($open_orders_by_region)>0)
            {
            	foreach ($open_orders_by_region as $key => $value) 
            	{
            		$open_order_arr[$value['location']]['open_orders'] = $value['open_orders'];
            	}
            }

            $open_opportunities_by_region=$CI->Report_model->get_open_opportunity_by_region($user_id, $role_id, $userProducts, $userLocations, $hotDay, $warmDate,$searchFilters);
			$open_opp_arr = array();
            if(count($open_opportunities_by_region)>0)
            {
            	foreach ($open_opportunities_by_region as $key => $value) 
                {
                	$open_opp_arr[$value['location']]['hot'] = $value['Hot'];
                	$open_opp_arr[$value['location']]['warm'] = $value['Warm'];
                	$open_opp_arr[$value['location']]['cold'] = $value['Cold'];
                }
            }
            

            $region_array = array(); 
            if(count($user_target_arr)>0)
            {
            	$i = 0;
            	foreach ($user_target_arr as $key => $value) 
            	{
            		if(isset($prev_target_arr[$key]))
            		{ $previous_target = $prev_target_arr[$key]; }
            		else { $previous_target = 0; }

            		if(isset($prev_sales_arr[$key]['prev_sales']))
            		{ $previous_sales = $prev_sales_arr[$key]['prev_sales']; }
            		else { $previous_sales = 0; }

            		if(isset($curr_target_arr[$key]))
            		{ $current_target = $curr_target_arr[$key]; }
            		else { $current_target = 0; }

            		if(isset($curr_sales_arr[$key]['curr_sales']))
            		{ $current_sales = $curr_sales_arr[$key]['curr_sales']; }
            		else { $current_sales = 0; }

            		if(isset($open_order_arr[$key]['open_orders']))
            		{ $open_orders = $open_order_arr[$key]['open_orders']; }
            		else { $open_orders = 0; }

            		if(isset($open_opp_arr[$key]['hot']))
            		{ $hot = $open_opp_arr[$key]['hot']; }
            		else { $hot = 0; }

            		if(isset($open_opp_arr[$key]['warm']))
            		{ $warm = $open_opp_arr[$key]['warm']; }
            		else { $warm = 0; }

            		if(isset($open_opp_arr[$key]['cold']))
            		{ $cold = $open_opp_arr[$key]['cold']; }
            		else { $cold = 0; }

            		$backlog = $previous_target-$previous_sales;
			   		if($backlog<0) { $backlog = 0;}
			   		$cumm_target = $backlog+$current_target;
			   		$pending = ($backlog+$current_target)-$current_sales-$open_orders;
			   		if($pending<0) { $pending = 0;}

					$region_array[$i]['previous_target'] = @$previous_target;
            		$region_array[$i]['backlog'] = @$backlog;
            		$region_array[$i]['cumm_target'] = @$cumm_target;
            		$region_array[$i]['pending'] = @$pending;
            		$region_array[$i]['region_name'] = @$key;
            		$region_array[$i]['current_target'] = @$current_target;
            		$region_array[$i]['hot'] = @$hot;
            		$region_array[$i]['warm'] = @$warm;
            		$region_array[$i]['cold'] = @$cold;
            		$region_array[$i]['current_sales'] = @$current_sales;
            		$region_array[$i]['open_orders'] = @$open_orders;
            		$region_array[$i]['previous_sales'] = @$previous_sales;
            		$i++;
				}
            }
            return $region_array;
		}
	}
}

function getReportTimelineCheck($searchFilters,$parameter,$fy_year)
{   
	$CI = & get_instance();
	$timeline=$searchFilters['vtime'];
	//$fy_years=get_current_fiancial_year();
	//print_r($fy_years);exit;
	$fy_dates=get_start_end_dates($timeline,'',$searchFilters);
	switch($timeline)
	{   
		
		case 'm':
			$start=$fy_dates['start_date'];
			$end=$fy_dates['end_date'];
			$q = ' date('.$parameter.') BETWEEN "'.$start.'" AND "'.$end.'" ';
			//$CI->db->query($q);
			break;
		case 'q': 
			$start=$fy_dates['start_date'];
			$end=$fy_dates['end_date'];
			$q = 'date('.$parameter.') BETWEEN "'.$start.'" AND "'.$end.'" ';
			break;
		case "y":
		    $start = $fy_year['start_date'];
			$end = $fy_year['end_date'];
			$q = 'date('.$parameter.') BETWEEN "'.$start.'" AND "'.$end.'" ';
			break;
		case "w":
		    $start=$fy_dates['start_date'];
			$end=$fy_dates['end_date'];
			$q = 'date('.$parameter.') BETWEEN "'.$start.'" AND "'.$end.'" ';
			break;
		case "a";
				$q='';
				break;
	}

	return $q;
}
function getCustomReportTimelineCheck($searchFilters,$parameter,$fy_year)
{   
	$CI = & get_instance();
	$timeline=$searchFilters['vtime'];
	//$fy_years=get_current_fiancial_year();
	//print_r($fy_years);exit;
	$fy_dates=get_custom_start_end_dates($timeline,'',$searchFilters);
	switch($timeline)
	{   
		
		case 'm':
			$start=$fy_dates['start_date'];
			$end=$fy_dates['end_date'];
			$q = ' date('.$parameter.') BETWEEN "'.$start.'" AND "'.$end.'" ';
			//$CI->db->query($q);
			break;
		case 'q': 
			$start=$fy_dates['start_date'];
			$end=$fy_dates['end_date'];
			$q = 'date('.$parameter.') BETWEEN "'.$start.'" AND "'.$end.'" ';
			break;
		case "y":
		    $start = $fy_year['start_date'];
			$end = $fy_year['end_date'];
			$q = 'date('.$parameter.') BETWEEN "'.$start.'" AND "'.$end.'" ';
			break;
		case "w":
		    $start=$fy_dates['start_date'];
			$end=$fy_dates['end_date'];
			$q = 'date('.$parameter.') BETWEEN "'.$start.'" AND "'.$end.'" ';
			break;
		case "a";
				$q='';
				break;
	}

	return $q;
}
function getOppTimelineCheck($searchFilters,$parameter,$fy_year)
{
	$CI = & get_instance();
	$timeline=$searchFilters['vtime'];
	//$fy_years=get_current_fiancial_year();
	//print_r($fy_years);exit;
	$fy_dates=get_start_end_dates($timeline,'',$searchFilters);
	switch($timeline)
	{   
		
		case 'm':
			$start=$fy_dates['start_date'];
			$end=$fy_dates['end_date'];
			$q = ' date('.$parameter.') < "'.$start.'"';
			//$CI->db->query($q);
			break;
		case 'q': 
			$start=$fy_dates['start_date'];
			$end=$fy_dates['end_date'];
			$q = 'date('.$parameter.') < "'.$start.'" ';
			break;
		case "y":
		    $start = $fy_year['start_date'];
			$end = $fy_year['end_date'];
			$q = 'date('.$parameter.') < "'.$start.'"  ';
			break;
		case "w":
		    $start=$fy_dates['start_date'];
			$end=$fy_dates['end_date'];
			$q = 'date('.$parameter.') < "'.$start.'" ';
			break;
		case "a";
				$q='';
				break;
	}

	return $q;
}
function getCustomOppTimelineCheck($searchFilters,$parameter,$fy_year)
{
	$CI = & get_instance();
	$timeline=$searchFilters['vtime'];
	//$fy_years=get_current_fiancial_year();
	//print_r($fy_years);exit;
	$fy_dates=get_custom_start_end_dates($timeline,'',$searchFilters);
	switch($timeline)
	{   
		
		case 'm':
			$start=$fy_dates['start_date'];
			$end=$fy_dates['end_date'];
			$q = ' date('.$parameter.') < "'.$start.'"';
			//$CI->db->query($q);
			break;
		case 'q': 
			$start=$fy_dates['start_date'];
			$end=$fy_dates['end_date'];
			$q = 'date('.$parameter.') < "'.$start.'" ';
			break;
		case "y":
		    $start = $fy_year['start_date'];
			$end = $fy_year['end_date'];
			$q = 'date('.$parameter.') < "'.$start.'"  ';
			break;
		case "w":
		    $start=$fy_dates['start_date'];
			$end=$fy_dates['end_date'];
			$q = 'date('.$parameter.') < "'.$start.'" ';
			break;
		case "a";
				$q='';
				break;
	}

	return $q;
}
function getOppTimelineCheckPresent($searchFilters,$parameter,$fy_year)
{
	$CI = & get_instance();
	//$fy_years=get_current_fiancial_year();
	//print_r($fy_years);exit;
	$timeline=$searchFilters['vtime'];
	$fy_dates=get_start_end_dates($timeline,'',$searchFilters);
	switch($timeline)
	{   
		
		case 'm':
			$start=$fy_dates['start_date'];
			$end=$fy_dates['end_date'];
			$q = ' date('.$parameter.') <= "'.$end.'"';
			//$CI->db->query($q);
			break;
		case 'q': 
			$start=$fy_dates['start_date'];
			$end=$fy_dates['end_date'];
			$q = 'date('.$parameter.') <= "'.$end.'" ';
			break;
		case "y":
		    $start = $fy_year['start_date'];
			$end = $fy_year['end_date'];
			$q = 'date('.$parameter.') <= "'.$end.'"  ';
			break;
		case "w":
		    $start=$fy_dates['start_date'];
			$end=$fy_dates['end_date'];
			$q = 'date('.$parameter.') <= "'.$end.'" ';
			break;
		case "a";
				$q='';
				break;
	}

	return $q;
}
function getCustomOppTimelineCheckPresent($searchFilters,$parameter,$fy_year)
{
	$CI = & get_instance();
	//$fy_years=get_current_fiancial_year();
	//print_r($fy_years);exit;
	$timeline=$searchFilters['vtime'];
	$fy_dates=get_custom_start_end_dates($timeline,'',$searchFilters);
//	print_r($fy_dates);exit;
	switch($timeline)
	{   
		
		case 'm':
			$start=$fy_dates['start_date'];
			$end=$fy_dates['end_date'];
			$q = ' date('.$parameter.') <= "'.$end.'"';
			//$CI->db->query($q);
			break;
		case 'q': 
			$start=$fy_dates['start_date'];
			$end=$fy_dates['end_date'];
			$q = 'date('.$parameter.') <="'.$end.'" ';
			break;
		case "y":
		    $start = $fy_dates['start_date'];
			$end = $fy_dates['end_date'];
			$q = 'date('.$parameter.') <="'.$end.'"  ';
			break;
		case "w":
		    $start=$fy_dates['start_date'];
			$end=$fy_dates['end_date'];
			$q = 'date('.$parameter.') <="'.$end.'" ';
			break;
		case "a";
				$q='';
				break;
	}

	return $q;
}
function get_current_fiancial_year()
{   
	$CI = & get_instance();
	$curdate=date('Y-m-d');
	$CI->db->select('min(fw.start_date) as start_date,max(fw.end_date) as end_date,f.fy_id,f.name');
	$CI->db->from('financial_year f');
	$CI->db->join('fy_week fw','f.fy_id=fw.fy_id');
	$CI->db->where('f.start_date<=',$curdate);
	$CI->db->where('f.end_date>=',$curdate);
	$res=$CI->db->get();
	return $res->row_array();
	/*$fy_year = $CI->Common_model->get_data_row('financial_year',array("start_date>="=>$curdate,"end_date<="=>$curdate));
	return $fy_year;*/
}
function get_custom_current_fiancial_year()
{   
	$CI = & get_instance();
	$curdate=date('Y-m-d');
	$CI->db->select('min(fw.start_date) as start_date,max(fw.end_date) as end_date,f.fy_id,f.name');
	$CI->db->from('financial_year f');
	$CI->db->join('custom_fy_week fw','f.fy_id=fw.fy_id');
	$CI->db->where('f.start_date<=',$curdate);
	$CI->db->where('f.end_date>=',$curdate);
	$res=$CI->db->get();
	return $res->row_array();
	/*$fy_year = $CI->Common_model->get_data_row('financial_year',array("start_date>="=>$curdate,"end_date<="=>$curdate));
	return $fy_year;*/
}


function get_last_day_of_month($searchFilters)
{
	$CI=& get_instance();
	$CI->db->select('end_date');
	$CI->db->from('fy_week');
	$CI->db->where('month_no',$searchFilters['cur_month']);
	$CI->db->where('year_no',$searchFilters['cur_year']);
	$CI->db->order_by('fy_week_id','desc');
	$CI->db->limit(1);
	$res=$CI->db->get();
	return $res->row_array();
}
function get_open_opportunity_helper_string()
{
    $string='(1,2,3,4,5)';
    return $string;
}
function get_open_opportunity_helper_array()
{
    
    $opp_array=array(1,2,3,4,5);
    return $opp_array;
}
function get_cities_by_region($region)
{   
	$CI= & get_instance(); 
	$CI->db->select('l4.location_id'); 
    $CI->db->from('location l1');
	$CI->db->join('location l2','l1.location_id=l2.parent_id');
	$CI->db->join('location l3','l2.location_id=l3.parent_id');
	$CI->db->join('location l4','l3.location_id=l4.parent_id');
	$CI->db->where('l1.location_id',$region);
	$CI->db->where('l1.territory_level_id',4);
	$res=$CI->db->get();
	return $res->result_array();
}

/* FUNNEL REPORT START */
function get_funnel_chart1($searchFilters)
{
	$CI=& get_instance();
	$xAxisCategory=array();
	// /print_r($searchFilters);exit;
	if($searchFilters['users']!='')
    {   
    	//echo $searchFilters['users'];
    	$users=$searchFilters['users'];
    	$reportees=getReportingUsers($users);
    	$user_id=$reportees.','.$users;
    }
    else
    {
    	$users=$CI->session->userdata('user_id');
    	$reportees=$CI->session->userdata('reportees');
    	$user_id=$reportees.','.$users;
    }
    $role_id=getUserRole($users);
	//echo $role_id.$user_id;
    if($users != $CI->session->userdata('user_id'))
	{
		$l = getUserLocations($users);
		$ul = getQueryArray($l);
		$up = getUserProducts($users);
		$userLocations = ($ul == '')? 0: $ul;
		$userProducts = ($up == '')? 0: $up;
	}
	else
	{
		$userLocations = ($CI->session->userdata('locationString') == '')? 0: $CI->session->userdata('locationString');
		$userProducts = ($CI->session->userdata('products') == '')? 0: $CI->session->userdata('products');
	}
    if($searchFilters['fy_dates']['start_date']!='')
    {
    	$start_date=$searchFilters['fy_dates']['start_date'];
    	$start_date = date('Y-m-d',strtotime( $start_date . " -1 day " ));
    	$month=date('m',strtotime($start_date));
    	$month1 = $month + 1;
    	$year=date('Y',strtotime($start_date));
    }
    else
    {
    	$month = date('m');
        $month1 = $month + 1;
        $year = date('Y');		
       
    }
	$day = getOpportunityCategorizationDate();
     $hotDay = $year."-".$month."-".$day;
    $warmDate = date('Y-m-d',strtotime( $hotDay . " +1 month " ));
	//fetching previous results of before date
	$fo_before_date=$CI->Report_model->get_funnel_opportunities_before_date($user_id, $role_id, $userProducts, $userLocations, $hotDay, $warmDate,$searchFilters);
	//echo $CI->db->last_query();exit;
	if($searchFilters['vtime']=='m')
	{
		$tline=get_year_based_timeline($searchFilters);
		//print_r($tline);exit;
		 $fo_opened_status=array();
	    $fo_closed_status=array();
	    //echo '<pre>'; print_r($tline); exit;
	    foreach ($tline as $row) {
		   	$k1=array();
		   	$k2=array();
		   	$res=$CI->Report_model->get_funnel_opportunities_opened_status($user_id, $role_id, $userProducts, $userLocations, $hotDay, $warmDate,$searchFilters,$row);
		   	//echo $CI->db->last_query();exit;
		   	 if($searchFilters['vtime']=='y' ||$searchFilters['vtime']=='q' )
		   	 {
		   	 	$yt_timeline=date('M-y',strtotime($row['timeline']));
		   	 }
		   	 else
		   	 {
		   	 	$yt_timeline='';
		   	 }
		   	 
		   	 if(count($res)>0)
		   	 { 
			   	$k1['opened_value']=@$res['opened_value'];
			   	$k1['timeline']=$yt_timeline;
			   	$k1['ctime']=@$res['ctime'];
			   	$k1['month_timeline']=@$res['month_timeline'];
			   	$k1['fy_week_id']=@$row['fy_week_id'];
			   	 $fo_opened_status[]=$k1;
			 }

	   		$res1=$CI->Report_model->get_funnel_opportunities_closed_status($user_id, $role_id, $userProducts, $userLocations, $hotDay, $warmDate,$searchFilters,$row);
	   		
	   	if(count($res1)>0)
	   	{
		   	$k2['closed_value']=@$res1['closed_value'];
		   	$k2['timeline']=@$yt_timeline;
		   	$k2['ctime']=@$res1['ctime'];
		   	$k2['month_timeline']=@$res1['month_timeline'];
		   	$k2['fy_week_id']=@$row['fy_week_id'];
		   	$fo_closed_status[]=$k2;
		}
	   }
	  // echo $CI->db->last_query();exit;
	}
	else
	{
		$fo_opened_status=$CI->Report_model->get_funnel_opportunities_opened_status($user_id, $role_id, $userProducts, $userLocations, $hotDay, $warmDate,$searchFilters);
		$fo_closed_status=$CI->Report_model->get_funnel_opportunities_closed_status($user_id, $role_id, $userProducts, $userLocations, $hotDay, $warmDate,$searchFilters);
	}
  
    $fo_present_date=$CI->Report_model->get_funnel_opportunities_present_date($user_id, $role_id, $userProducts, $userLocations, $hotDay, $warmDate,$searchFilters);
   // echo $CI->db->last_query();exit;
    $ores=get_dates($searchFilters);
    $series=array();
	$count=count($ores);
	$c1Data1=array();
	$c1Data1[]= ($fo_before_date['Hot']>0)? round($fo_before_date['Hot'],2):'';
	for($i=1;$i<=$count;$i++)
	{
		$c1Data1[]='';
	}
	$c1Data1[]=($fo_present_date['Hot']>0)? round($fo_present_date['Hot'],2):'';
	$fy_dates=get_start_end_dates($searchFilters['vtime'],'',$searchFilters);
	//print_r($fy_dates);exit;
	if($searchFilters['vtime']=='m'||$searchFilters['vtime']=='w')
	{
		$timeline=date('jM',strtotime($fy_dates['start_date']));
	}
	else
	{
		$timeline=date('jM-y',strtotime($fy_dates['start_date']));
	}
	//echo $timeline;
	$xAxisCategory[]=$timeline;
    
    $c1Data2=array();
    $c1Data2[]=($fo_before_date['Warm']>0)?round($fo_before_date['Warm'],2):'';
	for($i=1;$i<=$count;$i++)
	{
		$c1Data2[]='';
	}
	$c1Data2[]=($fo_present_date['Warm']>0)?round($fo_present_date['Warm'],2):'';

   
    $c1Data3=array();
    $c1Data3[]=($fo_before_date['Cold']>0)?round($fo_before_date['Cold'],2):'';
	for($i=1;$i<=$count;$i++)
	{
		$c1Data3[]='';
	}
	$c1Data3[]=($fo_present_date['Cold']>0)?round($fo_present_date['Cold'],2):'';

	
	$c1Data4=array();
	$c1Data5=array();
	$c1Data6=array();
	$c1Data4[]='';
	$c1Data5[]='';
	$c1Data6[]='';
	$top=$fo_before_date['Hot']+$fo_before_date['Warm']+$fo_before_date['Cold'];
	//echo $top;
	$new_op = array();
	$closed_op = array();
	$position=array();
	if($searchFilters['vtime']=='m')
	{
		foreach ($fo_opened_status as $orow) {
			$new_op[$orow['fy_week_id']] = $orow;
		}
		foreach ($fo_closed_status as $crow) {
			$closed_op[$crow['fy_week_id']] = $crow;
		}
		foreach($ores as $wrow)
		{   

			$new_op_val = (@$new_op[$wrow['week_id']]['opened_value']!='')?$new_op[$wrow['week_id']]['opened_value']:0;
			$closed_op_val = (@$closed_op[$wrow['week_id']]['closed_value']!='')?$closed_op[$wrow['week_id']]['closed_value']:0;
			$c1Data4[]=($new_op_val>0)?round($new_op_val,2):'';
			$c1Data5[]=($closed_op_val>0)?round($closed_op_val,2):'';
			$xAxisCategory[]=@$wrow['label'];
			$tpo=($top-($new_op_val+$closed_op_val)+($new_op_val-$closed_op_val));
			$top = $top + $new_op_val-$closed_op_val;
			if($tpo<=0)
			{
				$tpo='';
			}
			$c1Data6[]=($tpo>0)?round($tpo,2):'';
			$position[]=$tpo;
			//echo 'tpo:'.$tpo.'--top'.$top.'<br>';
		}
	}
    else
    {
		foreach ($fo_opened_status as $orow) {
			$new_op[$orow['timeline']] = $orow;
		}
		foreach ($fo_closed_status as $crow) {
			$closed_op[$crow['timeline']] = $crow;
		}
		foreach($ores as $x_axix_lable)
		{
			$new_op_val = (@$new_op[$x_axix_lable]['opened_value']!='')?$new_op[$x_axix_lable]['opened_value']:0;
			$closed_op_val = (@$closed_op[$x_axix_lable]['closed_value']!='')?$closed_op[$x_axix_lable]['closed_value']:0;
			$c1Data4[]=($new_op_val>0)?round($new_op_val,2):'';
			$c1Data5[]=($closed_op_val>0)?round($closed_op_val,2):'';
			$xAxisCategory[]=$x_axix_lable;
			$tpo=($top-($new_op_val+$closed_op_val)+($new_op_val-$closed_op_val));
			$top = $top + $new_op_val-$closed_op_val;
			if($tpo<=0)
			{
				$tpo='';
			}
			$c1Data6[]=($tpo>0)?round($tpo,2):'';
			$position[]=$tpo;
			//echo 'tpo:'.$tpo.'--top'.$top.'<br>';
		}
	}
	/*echo "<pre> ";
	print_r($new_op);
    echo "<br>";
    print_r($closed_op);
    echo "<br>";
    print_r($ores);exit;*/

    $c1Data6[]='';
	$months=$searchFilters['duration'];
	$month_arr=explode('to', $months);
	$month_number=$month_arr[0];
	if($searchFilters['vtime']=='w')
	{
		//$timeline=date('jM',strtotime(date('Y-m-d')));
		if($fy_dates['start_date']<=date('Y-m-d') && $fy_dates['end_date']>=date('Y-m-d') )
		{
			$date=date('Y-m-d');
		}
		else
		{
			$date=$fy_dates['end_date'];
		}
		$timeline=date('jM',strtotime($date));
	}
	elseif ($searchFilters['vtime']=='q') 
	{   
		if($fy_dates['start_date']<=date('Y-m-d') && $fy_dates['end_date']>=date('Y-m-d') )
		{
			$date=date('Y-m-d');
		}
		else
		{
			$date=$fy_dates['end_date'];
		}
		$timeline=date('jM-y',strtotime($date));
	}
	elseif($searchFilters['vtime']=='m')
	{   
		$curdate=date('Y-m-d');
		$kk=get_month_no_by_date($curdate);
		if($month_number==$kk['month_no'])
		{
			$timeline=date('jM',strtotime(date('Y-m-d')));
		}
		else
		{
			$timeline=date('jM',strtotime($fy_dates['end_date']));
		}
	}
	else
	{
		$timeline=date('jM-y',strtotime(date('Y-m-d')));
	}
	$xAxisCategory[]=$timeline;
	$series []= array('name'=>'Hot','data'=>$c1Data1,'color'=>'#F44336');
	$series []= array('name'=>'Warm','data'=>$c1Data2,'color'=>'#FF9800');
	$series []= array('name'=>'Cold','data'=>$c1Data3,'color'=>'#95B7DA');
	$series []= array('name'=>'New','data'=>$c1Data4,'color'=>'#3F51B5');
	$series []= array('name'=>'Closed','data'=>$c1Data5,'color'=>'#7ABA7A');
	$series []= array('name'=>'Constant','data'=>$c1Data6,'color'=>'#F1EFE2','dataLabels'=> false,'showInLegend'=>false);
	if($searchFilters['measure']==1)
	{
		$measure='Numbers';
	}
	else
	{
		$measure='Lakhs';
	}
	$yAxisCategory = 'Value In '.$measure;
	if($searchFilters['region']!='')
	{
		$loc=$CI->Common_model->get_data_row('location',array('location_id'=>$searchFilters['region']));
		$location=$loc['location'].' Wise ';
	}
	$year=get_current_fiancial_year();
	if(@$searchFilters['duration_text']!='')
	{
		if($searchFilters['vtime']=='w')
		{
			$text='( '.substr(@$searchFilters['duration_text'],0,5).' )';
		}
		else
		{
			$text= '( '.@$searchFilters['duration_text'].' )';
		}
	}
	else
	{
		$text=$year['name'];
	}
	$xAxisLable = @$location.'Funnel report '.$text;
	$position=min($position);
	$position=(int)(1000*floor($position/1000));
	$chart1Data = array('xAxisCategory'=>$xAxisCategory,'yAxisCategory'=>$yAxisCategory,'chart1Series'=>$series,'xAxisLable'=>$xAxisLable,'tpoPosition'=>$position);
	//echo "<pre>"; print_r($chart1Data); exit();
	$chart1Data = json_encode($chart1Data);
	//print_r($chart1Data);exit;
	return $chart1Data;
}

function get_funnel_chart2($search_date,$series_name,$searchFilters)
{
	$CI=& get_instance();
	if($searchFilters['measure']==1)
	{
		$yAxisCategory='By Qty';
	}
	else
	{
		$yAxisCategory='Value In Lakhs';
	}
	if($searchFilters['users']!='')
    {   
    	//echo $searchFilters['users'];
    	$users=$searchFilters['users'];
    	$reportees=getReportingUsers($users);
    	$user_id=$reportees.','.$users;
    }
    else
    {
    	$users=$CI->session->userdata('user_id');
    	$reportees=$CI->session->userdata('reportees');
    	$user_id=$reportees.','.$users;
    }
   $role_id=getUserRole($users);
	//echo $role_id.$user_id;
    if($users != $CI->session->userdata('user_id'))
	{
		$l = getUserLocations($users);
		$ul = getQueryArray($l);
		$up = getUserProducts($users);
		$userLocations = ($ul == '')? 0: $ul;
		$userProducts = ($up == '')? 0: $up;
	}
	else
	{
		$userLocations = ($CI->session->userdata('locationString') == '')? 0: $CI->session->userdata('locationString');
		$userProducts = ($CI->session->userdata('products') == '')? 0: $CI->session->userdata('products');
	}

	
    $series=array();
    $c2Data1=$c2Data2=$c2Data3=array();
    $xAxisCategory=array();
    $xAxisCategory[]=$search_date;
	if($series_name=='New')
    {
		$opened_results=$CI->Report_model->get_fo_by_date_opened($user_id, $role_id, $userProducts, $userLocations, $search_date,$searchFilters);
		//echo $CI->db->last_query();exit;
		$c2Data1[]=($opened_results['Hot']>0)?round($opened_results['Hot'],2):'';
		$c2Data2[]=($opened_results['Warm'])?round($opened_results['Warm'],2):'';
		$c2Data3[]=($opened_results['Cold'])?round($opened_results['Cold'],2):'';
		$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
	    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
	    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#95B7DA');
		
	}
	elseif($series_name=='Closed')
	{
		$closed_results=$CI->Report_model->get_fo_by_date_closed($user_id, $role_id, $userProducts, $userLocations, $search_date,$searchFilters);
		//print_r($closed_results);
		$color=array('#90ed7d','#FD7456','#717D8C');
		$i=0;
		foreach ($closed_results as $row)
		{
			if($row['status']=='Closed Won')
			{
				$status_color=$color[0];
			}
			elseif($row['status']=='Closed Lost')
			{
				$status_color=$color[1];
			}
			elseif($row['status']=='Dropped')
			{
				$status_color=$color[2];
			}
			$series[]=array('name'=>$row['status'],'data'=>array(round($row['measure'],2)),'color'=>$status_color);
		}
	}
	//echo $CI->db->last_query();
	switch ($searchFilters['vtime']) {
		case 'w':
			$chart_title = $series_name.' opportunites on '.$search_date;
		break;
		case 'm':
			$arr = explode(' (',$search_date);
			$chart_title = $series_name.' opportunites in '.@$arr[0];
		break;
		case 'q': case 'y':
			$chart_title = $series_name.' opportunites in '.$search_date;
		break;
	}
	
	$chart2Data = array('xAxisCategory2'=>$xAxisCategory,'yAxisCategory2'=>$yAxisCategory,'chart2Series'=>$series,'xAxisLable2'=>$chart_title);
	$chart2Data = json_encode($chart2Data);
	return $chart2Data;

}
/* FUNNEL REPORT END*/

function get_funnel_chart3($x_category2,$series_name2,$searchFilters)
{
/*	$x_category2 = $x_category2;
	$series_name2 = $series_name2;
	$c3Data1 = array(5, 3, 4, 7, 2);
	$c3Data2 = array(2, 2, 3, 2, 1);
	$c3Data3 = array(3, 4, 4, 2, 5);

	$xAxisCategory = array('Apples', 'Oranges', 'Pears', 'Grapes', 'Bananas');

	$yAxisCategory = 'Number of Open leads';
	$xAxisLable = 'Stacked report 3';

	$series = array();
	$series []= array('name'=>'Hot','data'=>$c3Data1,'color'=>'#F44336');
	$series []= array('name'=>'Warm','data'=>$c3Data2,'color'=>'#FF9800');
	$series []= array('name'=>'Cold','data'=>$c3Data3,'color'=>'#3F51B5');

	$chart3Data = array('xAxisCategory3'=>$xAxisCategory,'yAxisCategory3'=>$yAxisCategory,'chart3Series'=>$series,'xAxisLable3'=>$xAxisLable);
	$chart3Data = json_encode($chart3Data);
	return $chart3Data;*/
	$CI=& get_instance();
	if($searchFilters['vtime']=='m') 
	{
		$arr = explode(' (',$x_category2);
		$chart_title = @$arr[0];
	}
	else
	{
		$chart_title=$x_category2;
	}
	if($searchFilters['measure']==1)
	{
		$yAxisCategory='By Qty';
	}
	else
	{
		$yAxisCategory='In Lakhs';
	}
	if($searchFilters['users']!='')
    {   
    	//echo $searchFilters['users'];
    	$users=$searchFilters['users'];
    	$reportees=getReportingUsers($users);
    	$user_id=$reportees.','.$users;
    }
    else
    {
    	$users=$CI->session->userdata('user_id');
    	$reportees=$CI->session->userdata('reportees');
    	$user_id=$reportees.','.$users;
    }
    $role_id=getUserRole($users);
    if($users != $CI->session->userdata('user_id'))
	{
		$l = getUserLocations($users);
		$ul = getQueryArray($l);
		$up = getUserProducts($users);
		$userLocations = ($ul == '')? 0: $ul;
		$userProducts = ($up == '')? 0: $up;
	}
	else
	{
		$userLocations = ($CI->session->userdata('locationString') == '')? 0: $CI->session->userdata('locationString');
		$userProducts = ($CI->session->userdata('products') == '')? 0: $CI->session->userdata('products');
	}


	if($searchFilters['fy_dates']['start_date']!='')
    {
    	$start_date=$searchFilters['fy_dates']['start_date'];
    	$month=date('m',strtotime($start_date));
    	$month1 = $month + 1;
    	$year=date('Y',strtotime($start_date));
    }
    else
    {   //get_month_no_by_date($date)
    	$month = date('m');
        $month1 = $month + 1;
        $year = date('Y');		
       
    }	
    $day = getOpportunityCategorizationDate();
    $hotDay = $year."-".$month."-".$day;
    $warmDate = date('Y-m-d',strtotime( $hotDay . " +1 month " ));
    $series=array();
    $xAxisCategory=array();
    $lost_reasons=$CI->Report_model->get_fo_by_closed_lost_reason($user_id,$role_id,$userProducts,$userLocations,$x_category2,$searchFilters);
	$lost_comp=$CI->Report_model->get_fo_by_closed_lost_comp($user_id,$role_id,$userProducts,$userLocations,$x_category2,$searchFilters);
	$series=array();
	$colors=array('#FFD54F','#3F51B5','#7ABA7A','#FF9800','#F44336', '#9C27B0', '#795548', '#FFEB3B', '#42A5F5', '#CDDC39', '#A1887F', '#99b3ff', '#CC1559', '#6D929B', '#e87d7d', '#bea7a7', '#d9ff66','#717D8C', '#66ff8c','#4CAF50');
	$i=0;
	foreach ($lost_comp as $row)
	{
		if($i==20)
		{
			$i=0;
		}
		$series[]=array('name'=>$row['competitor_name'],'data'=>array(round($row['measure'],2),''),'color'=>$colors[$i]);
		$i++;
	}
	$xAxisCategory[]='Lost To Competitor';
	$reason_colors=array('#917567','#7B8D8E','#9F6164','#666633','#990033','#834C24','#443266','#FFFF66','#9D538E','#FF9966','#6F0564','#CD5C5C','#99CC99','#FFCF75','#CC99FF','#9C2A00','#6F684E','#FFBAD2','#462D44','#C25B56');
	$i=0;
	foreach ($lost_reasons as $row)
	{
		if($i==20)
		{
			$i=0;
		}
		$series[]=array('name'=>$row['reason_name'],'data'=>array('',round($row['measure'],2)),'color'=>$reason_colors[$i]);
		$i++;
	}
	$xAxisCategory[]='Lost By Reason';
	$chart3Data = array('xAxisCategory3'=>$xAxisCategory,'yAxisCategory3'=>$yAxisCategory,'chart3Series'=>$series,'xAxisLable3'=>'Closed Lost in '.$chart_title);
	$chart3Data = json_encode($chart3Data);
	return $chart3Data;
}

function get_start_end_dates($timeline,$date='',$searchFilters='')
{  $CI=& get_instance();
	if($date=='')
	{
		$date=date('Y-m-d');
	}
	switch($timeline)
	{
		case 'w':
			$fy_dates=get_week_start_date_end_date($date,$searchFilters);
			break;
		case 'm':
		    $curdate=date('Y-m-d');
		    $kk=get_month_no_by_date($curdate);
		    $month_no=$kk['month_no'];
			$fy_dates=get_month_start_end_date($date,$month_no,$searchFilters);
			break;
		case 'q':
		   $fy_dates=get_week_start_date_end_date($date,$searchFilters);
		   $qtr=get_quarter_array($fy_dates);
		   $fy_dates=get_quarter_start_end_dates($qtr,$searchFilters);
		   break;
	   case "y";
			$fy_dates=get_current_fiancial_year();
			break;
		default :
		     $fy_dates='';
		 break;

	} 
	//print_r($fy_dates);
	return $fy_dates;
}
function get_custom_start_end_dates($timeline,$date='',$searchFilters='')
{  $CI=& get_instance();
	if($date=='')
	{
		$date=date('Y-m-d');
	}
	switch($timeline)
	{
		case 'w':
			$fy_dates=get_custom_week_start_date_end_date($date,$searchFilters);
			break;
		case 'm':
		    $curdate=date('Y-m-d');
		    $kk=get_custom_month_no_by_date($curdate);
		    $month_no=$kk['month_no'];
			$fy_dates=get_custom_month_start_end_date($date,$month_no,$searchFilters);
			break;
		case 'q':
		   $fy_dates=get_custom_week_start_date_end_date($date,$searchFilters);
		   $qtr=get_quarter_array($fy_dates);
		   $fy_dates=get_custom_quarter_start_end_dates($qtr,$searchFilters);
		   break;
	   case "y";
			$fy_dates=get_custom_current_fiancial_year();
			break;
		default :
		     $fy_dates='';
		 break;

	} 
	//print_r($fy_dates);
	return $fy_dates;
}
function get_week_start_date_end_date($date,$searchFilters='')
{    
	if(@$searchFilters['duration']!='' && @$searchFilters['duration']!='null')
	{
		$duration=explode('to',$searchFilters['duration']);
		$date=$duration[0];
	}
	else
	{
		$date=$date;
	}
	$CI=& get_instance();
	$CI->db->select('fws.start_date,fws.end_date,fws.month_no');
	$CI->db->from('financial_year fy');
	$CI->db->join('fy_week fws','fy.fy_id=fws.fy_id');
	$CI->db->where('fws.start_date<=',$date);
	$CI->db->where('fws.end_date>=',$date);
	$res=$CI->db->get();
	return $res->row_array();
}
function get_custom_week_start_date_end_date($date,$searchFilters='')
{    
	if(@$searchFilters['duration']!='' && @$searchFilters['duration']!='null')
	{
		$duration=explode('to',$searchFilters['duration']);
		$date=$duration[0];
	}
	else
	{
		$date=$date;
	}
	$CI=& get_instance();
	$CI->db->select('fws.start_date,fws.end_date,fws.month_no');
	$CI->db->from('financial_year fy');
	$CI->db->join('custom_fy_week fws','fy.fy_id=fws.fy_id');
	$CI->db->where('fws.start_date<=',$date);
	$CI->db->where('fws.end_date>=',$date);
	$res=$CI->db->get();
	return $res->row_array();
}
function get_month_start_end_date($date,$month_no,$searchFilters='')
{   
	//print_r($searchFilters);
	if(@$searchFilters['duration']!='')
	{
		//echo "hi"; exit;
		$duration=explode('to',$searchFilters['duration']);
		$month_no=$duration[0];
	}
	else
	{
		$month_no=$month_no;
	}
	$CI=& get_instance();
	$curdate=date('Y-m-d');
	$CI->db->select('min(fws.start_date) as start_date,max(fws.end_date) as end_date,fws.month_no,fws.year_no');
	$CI->db->from('financial_year fy');
	$CI->db->join('fy_week fws','fy.fy_id=fws.fy_id');
    //$CI->db->where('fy.fy_id',$fy_year['fy_id']);
	$CI->db->where('fws.month_no',$month_no);
	$CI->db->where('fy.start_date<=',$curdate);
	$CI->db->where('fy.end_date>=',$curdate);
	$CI->db->group_by('fws.month_no');
	$res=$CI->db->get();
	return $res->row_array();
}
function get_custom_month_start_end_date($date,$month_no,$searchFilters='')
{   
	//print_r($searchFilters);
	if(@$searchFilters['duration']!='')
	{
		//echo "hi"; exit;
		$duration=explode('to',$searchFilters['duration']);
		$month_no=$duration[0];
	}
	else
	{
		$month_no=$month_no;
	}
	$CI=& get_instance();
	$curdate=date('Y-m-d');
	$CI->db->select('min(fws.start_date) as start_date,max(fws.end_date) as end_date,fws.month_no,fws.year_no');
	$CI->db->from('financial_year fy');
	$CI->db->join('custom_fy_week fws','fy.fy_id=fws.fy_id');
    //$CI->db->where('fy.fy_id',$fy_year['fy_id']);
	$CI->db->where('fws.month_no',$month_no);
	$CI->db->where('fy.start_date<=',$curdate);
	$CI->db->where('fy.end_date>=',$curdate);
	$CI->db->group_by('fws.month_no');
	$res=$CI->db->get();
	return $res->row_array();
}
function get_quarter_array($fy_dates)
{
	switch($fy_dates['month_no'])
	{
		case 4 :case 5:case 6 :
			{
				$qtr=array(4,5,6);
				break;
			}
			case 7 :case 8:case 9 :
			{
				$qtr=array(7,8,9);
				break;
			}
			case 10 :case 11:case 12 :
			{
				$qtr=array(10,11,12);
				break;
			}
			case 1 :case 2:case 3:
			{
				$qtr=array(1,2,3);
				break;
			}
	}
	return $qtr;
}
function  get_quarter_start_end_dates($qtr,$searchFilters='')
{
	$CI=& get_instance();
	if(@$searchFilters['duration']!='null' && @$searchFilters['duration']!='' )
	{   
		$fy_dates=array();
		$duration=explode('to',$searchFilters['duration']);
		$start_date=$duration[0];
		$fy_dates['month_no']=date('m',strtotime($start_date));
		$qtr=get_quarter_array($fy_dates);
	}
	else
	{
		$qtr=$qtr;
	}
	//$fy_year=get_current_fiancial_year();
	$curdate=date('Y-m-d');
	$CI->db->select('min(fws.start_date) as start_date,max(fws.end_date) as end_date');
	$CI->db->from('financial_year fy');
	$CI->db->join('fy_week fws','fy.fy_id=fws.fy_id');
	$CI->db->where('fy.start_date<=',$curdate);
	$CI->db->where('fy.end_date>=',$curdate);
	//$CI->db->where('fy.fy_id',$fy_year['fy_id']);
	$CI->db->where_in('fws.month_no',$qtr);

	//$CI->db->group_by('fw.month_no');
	$res=$CI->db->get();
	return $res->row_array();
}
function  get_custom_quarter_start_end_dates($qtr,$searchFilters='')
{
	$CI=& get_instance();
	if(@$searchFilters['duration']!='null' && @$searchFilters['duration']!='' )
	{   
		$fy_dates=array();
		$duration=explode('to',$searchFilters['duration']);
		$start_date=$duration[0];
		$fy_dates['month_no']=date('m',strtotime($start_date));
		$qtr=get_quarter_array($fy_dates);
	}
	else
	{
		$qtr=$qtr;
	}
	//$fy_year=get_current_fiancial_year();
	$curdate=date('Y-m-d');
	$CI->db->select('min(fws.start_date) as start_date,max(fws.end_date) as end_date');
	$CI->db->from('financial_year fy');
	$CI->db->join('custom_fy_week fws','fy.fy_id=fws.fy_id');
	$CI->db->where('fy.start_date<=',$curdate);
	$CI->db->where('fy.end_date>=',$curdate);
	//$CI->db->where('fy.fy_id',$fy_year['fy_id']);
	$CI->db->where_in('fws.month_no',$qtr);

	//$CI->db->group_by('fw.month_no');
	$res=$CI->db->get();
	return $res->row_array();
}
function get_dates($searchFilters)
{   
	$vtime=$searchFilters['vtime'];
	$CI=&get_instance();
	$fy_dates=get_start_end_dates($vtime,'',$searchFilters);
	$mt=array();
	if($vtime=='y')
	{
		$res=get_months_fo($fy_dates['start_date'],date('Y-m-d'));
	}
	elseif ($vtime=='q') 
	{   $quarter=$searchFilters['duration'];
		$quat_arr=explode('to', $quarter);
		$start_date=$quat_arr[0];
		$end_date=$quat_arr[1];
        if($start_date <=date('Y-m-d') && $end_date>=date('Y-m-d'))
        {
        	$end_date=date('Y-m-d');
        }
        else
        {
        	$end_date=$fy_dates['end_date'];
        }
		$res=get_months_fo($fy_dates['start_date'],$end_date);
	}
	elseif($vtime=='m')
	{
		$months=$searchFilters['duration'];
		$month_arr=explode('to', $months);
		$month_number=$month_arr[0];
		$year=$month_arr[1];
		$search_list=array('cur_year'=>$year,'cur_month'=>$month_number);
		$i=1;
		$weeks=$CI->Report_model->get_financial_year_weeks($search_list);
		foreach($weeks as $row)
		{   
			$ar=array();
			$ar['label']="Week".$i.' ('.$row['start_date'].' to '.$row['end_date']. ')';
			$ar['week_id']=$row['fy_week_id'];
			$i++;
			$res[]=$ar;
		}
	}
	else
	{
		$begin = new DateTime( $fy_dates['start_date'] );
        $end   = new DateTime( $fy_dates['end_date'] );
        $res=array();
		for($i = $begin; $i <= $end; $i->modify('+1 day')){
			if($i->format('Y-m-d')<=date('Y-m-d'))
			{
		   		$res[]=date('dM',strtotime($i->format("Y-m-d")));
		   	}
		}
		
	}
	return $res;
	
}
function get_runrate_dates($searchFilters)
{   
	$vtime=$searchFilters['vtime'];
	$CI=&get_instance();
	$fy_dates=get_start_end_dates($vtime,'',$searchFilters);
	$mt=array();
	if($vtime=='y')
	{
		$res=get_months_fo($fy_dates['start_date'],$fy_dates['end_date']);
	}
	elseif ($vtime=='q') 
	{   $quarter=$searchFilters['duration'];
		$quat_arr=explode('to', $quarter);
		$start_date=$quat_arr[0];
		$end_date=$quat_arr[1];
        $res=get_months_fo($fy_dates['start_date'],$end_date);
	}
	elseif($vtime=='m')
	{
		$months=$searchFilters['duration'];
		$month_arr=explode('to', $months);
		$month_number=$month_arr[0];
		$year=$month_arr[1];
		$search_list=array('cur_year'=>$year,'cur_month'=>$month_number);
		$i=1;
		$weeks=$CI->Report_model->get_financial_year_weeks($search_list);
		foreach($weeks as $row)
		{   
			$ar=array();
			$ar['label']="Week".$i.' ('.$row['start_date'].' to '.$row['end_date']. ')';
			$ar['week_id']=$row['fy_week_id'];
			$i++;
			$res[]=$ar;
		}
	}
	else
	{
		$begin = new DateTime( $fy_dates['start_date'] );
        $end   = new DateTime( $fy_dates['end_date'] );
        $res=array();
		for($i = $begin; $i <= $end; $i->modify('+1 day')){
			if($i->format('Y-m-d')<=date('Y-m-d'))
			{
		   		$res[]=date('dM',strtotime($i->format("Y-m-d")));
		   	}
		}
		
	}
	return $res;
	
}

function get_months_fo($start_date,$end_date)
{
	$ci=& get_instance();
	$ci->db->select('concat(year_no,"-",month_no) as t');
	$ci->db->from('fy_week');
	$ci->db->where('start_date>=',$start_date);
	$ci->db->where('start_date<=',$end_date);
	$ci->db->group_by('month_no');
	$ci->db->order_by('year_no,month_no');
	$res=$ci->db->get();
	$timeline=array();
	 foreach($res->result_array() as $row)
	 {	
	 	$timeline[]=date('M-y',strtotime($row['t']));
	 }
	 return$timeline;
}
function get_fy_months_array($fy_dates)
{
	$fy_year=get_current_fiancial_year();
	$ci=& get_instance();
	$ci->db->select('fw.*,concat(year_no,"-",month_no) as t');
	$ci->db->from('financial_year f');
	$ci->db->join('fy_week fw','f.fy_id=fw.fy_id');
	$ci->db->where('f.fy_id',$fy_year['fy_id']);
	$ci->db->where('fw.start_date<=',date('Y-m-d'));
	$ci->db->group_by('fw.month_no');
	$res=$ci->db->get();
	$qry_data='';
	$date=date('Y-m-d');
	$dates=get_month_no_by_date($date);
	$month_no=$dates['month_no'];
	$fy=get_month_start_end_date($date,$month_no);
	foreach($res->result_array() as $row)
	{   
		
		$selected='';
		$selected=($row['month_no']==$fy_dates['month_no']&& $row['year_no']==$fy_dates['year_no'])?'selected':'';
		$qry_data.='<option value="'.$row['month_no'].'to'.$row['year_no'].'"'.$selected.'>'.date('M-y',strtotime($row['t'])).'</option>';
	}
	return $qry_data;
}
function get_custom_fy_months_array($fy_dates)
{
	$fy_year=get_current_fiancial_year();
	$ci=& get_instance();
	$ci->db->select('fw.*,concat(year_no,"-",month_no) as t');
	$ci->db->from('financial_year f');
	$ci->db->join('custom_fy_week fw','f.fy_id=fw.fy_id');
	$ci->db->where('f.fy_id',$fy_year['fy_id']);
	$ci->db->where('fw.start_date<=',date('Y-m-d'));
	$ci->db->group_by('fw.month_no');
	$res=$ci->db->get();
	$qry_data='';
	$date=date('Y-m-d');
	$dates=get_month_no_by_date($date);
	$month_no=$dates['month_no'];
	$fy=get_month_start_end_date($date,$month_no);
	foreach($res->result_array() as $row)
	{   
		
		$selected='';
		$selected=($row['month_no']==$fy_dates['month_no']&& $row['year_no']==$fy_dates['year_no'])?'selected':'';
		$qry_data.='<option value="'.$row['month_no'].'to'.$row['year_no'].'"'.$selected.'>'.date('M-y',strtotime($row['t'])).'</option>';
	}
	return $qry_data;
}

function get_fy_quarter_array($fy_dates)
{
	$qry_data='';
	$ci=& get_instance();
	$i=1;
	$res=get_quat_array();
	foreach($res as $value)
	{  
        $row=get_quarter_start_end_dates($value);
        $selected='';
		$selected=(($row['start_date']<=$fy_dates['start_date'] && $row['end_date'] >= $fy_dates['end_date'])?'selected':"");
		if($row['start_date']<=date('Y-m-d'))
		{
			$qry_data.='<option value="'.$row['start_date'].'to'.$row['end_date'].'"'.$selected.'>'."Quarter".$i.'</option>';
			$i++;
		}
	}
	return $qry_data;
}
function get_custom_fy_quarter_array($fy_dates)
{
	$qry_data='';
	$ci=& get_instance();
	$i=1;
	$res=get_quat_array();
	foreach($res as $value)
	{  
        $row=get_custom_quarter_start_end_dates($value);
        $selected='';
		$selected=(($row['start_date']<=$fy_dates['start_date'] && $row['end_date'] >= $fy_dates['end_date'])?'selected':"");
		if($row['start_date']<=date('Y-m-d'))
		{
			$qry_data.='<option value="'.$row['start_date'].'to'.$row['end_date'].'"'.$selected.'>'."Quarter".$i.'</option>';
			$i++;
		}
	}
	return $qry_data;
}
function get_custom_fy_week_array($fy_dates)
{
	$qry_data='';
	$ci=& get_instance();
	$i=1;
	$date=date('Y-m-d');
	$kk=get_custom_month_no_by_date($date);
	$searchFilters=array(
		'cur_year'=>$kk['year_no'],
		'cur_month'=>$kk['month_no']
		);
	$res=$ci->Report_model->get_custom_financial_year_weeks($searchFilters);
	foreach($res as $row)
	{  
        $selected='';
		$selected=(($row['start_date']<=$fy_dates['start_date'] && $row['end_date'] >= $fy_dates['end_date'])?'selected':"");
		$qry_data.='<option value="'.$row['start_date'].'to'.$row['end_date'].'"'.$selected.'>'."Week".$i.' ('.$row['start_date'].' to '.$row['end_date']. ')</option>';
		$i++;
	}
	return $qry_data;

}
function get_fy_week_array($fy_dates)
{
	$qry_data='';
	$ci=& get_instance();
	$i=1;
	$date=date('Y-m-d');
	$kk=get_month_no_by_date($date);
	$searchFilters=array(
		'cur_year'=>$kk['year_no'],
		'cur_month'=>$kk['month_no']
		);
	$res=$ci->Report_model->get_financial_year_weeks($searchFilters);
	foreach($res as $row)
	{  
        $selected='';
		$selected=(($row['start_date']<=$fy_dates['start_date'] && $row['end_date'] >= $fy_dates['end_date'])?'selected':"");
		$qry_data.='<option value="'.$row['start_date'].'to'.$row['end_date'].'"'.$selected.'>'."Week".$i.' ('.$row['start_date'].' to '.$row['end_date']. ')</option>';
		$i++;
	}
	return $qry_data;

}
function get_quat_array()
{

	$quat=array(array(4,5,6),
		        array(7,8,9),
		        array(10,11,12),
		        array(1,2,3)
		);
	return $quat;
}
function get_year_based_timeline($searchFilters,$rr='')
{
	$vtime=$searchFilters['vtime'];
	$CI=&get_instance();
	$fy_dates=get_start_end_dates($vtime,'',$searchFilters);
	$fy_year=get_current_fiancial_year();
	$res=array();
	if($vtime=='y')
	{  
		$curdate=date('Y-m-d');
		$kk=get_month_no_by_date($curdate);
	    $month_no=$kk['month_no'];
		$start_date=$fy_year['start_date'];
		$end_date=$fy_year['end_date'];
		if($rr!='')
		{
			$res = get_runrates_months_by_year($start_date,$end_date);
		}
		else {
			$res=get_months_startdate_enddate_by_year($start_date,$end_date,$month_no);
		}
	}
	elseif ($vtime=='q') 
	{   $quarter=$searchFilters['duration'];
		$quat_arr=explode('to', $quarter);
		$start_date=$quat_arr[0];
		$end_date=$quat_arr[1];
		if($rr!='')
		{
			$res = get_runrates_months_by_year($fy_dates['start_date'],$end_date);
		}
		else
		{
			 $res=get_months_startdate_enddate_by_year($fy_dates['start_date'],$end_date);
	    }
	}
       
	elseif($vtime=='m')
	{
		$months=$searchFilters['duration'];
		$month_arr=explode('to', $months);
		$month_number=$month_arr[0];
		$year=$month_arr[1];
		$search_list=array('cur_year'=>$year,'cur_month'=>$month_number);
		$i=1;
		$weeks=$CI->Report_model->get_financial_year_weeks($search_list);
		foreach($weeks as $row)
		{   
			$ar=array();
			$ar['label']="Week".$i.' ('.$row['start_date'].' to '.$row['end_date']. ')';
			$ar['fy_week_id']=$row['fy_week_id'];
			$ar['start_date']=$row['start_date'];
			$ar['end_date']=$row['end_date'];
			$i++;
			$res[]=$ar;
		}
	}
	
	return $res;
}
function get_months_startdate_enddate_by_year($start_date,$end_date,$month_no='')
{
	$CI=& get_instance();
	$CI->db->select('min(fws.start_date) as start_date,max(fws.end_date) as end_date,fws.month_no,fws.year_no,concat(year_no,"-",month_no) as timeline');
	$CI->db->from('financial_year fy');
	$CI->db->join('fy_week fws','fy.fy_id=fws.fy_id');
	$CI->db->where('fws.start_date>=',$start_date);
	$CI->db->where('fws.start_date<=',date('Y-m-d'));
	$CI->db->group_by('fws.month_no');
	$CI->db->order_by('fws.year_no,fws.month_no');
	$res=$CI->db->get();
	return $res->result_array();
}
function get_runrates_months_by_year($start_date,$end_date)
{
	$CI=& get_instance();
	$CI->db->select('min(fws.start_date) as start_date,max(fws.end_date) as end_date,fws.month_no,fws.year_no,concat(year_no,"-",month_no) as timeline');
	$CI->db->from('financial_year fy');
	$CI->db->join('fy_week fws','fy.fy_id=fws.fy_id');
	$CI->db->where('fws.start_date>=',$start_date);
	$CI->db->where('fws.start_date<=',$end_date);
	$CI->db->group_by('fws.month_no');
	$CI->db->order_by('fws.year_no,fws.month_no');
	$res=$CI->db->get();
	return $res->result_array();
}
function get_month_no_by_date($date)
{
	$ci=& get_instance();
	$ci->db->from('fy_week');
	$ci->db->where('start_date <=',$date);
	$ci->db->where('end_date>=',$date);
	$res=$ci->db->get();
	return $res->row_array();
}
function get_custom_month_no_by_date($date)
{
	$ci=& get_instance();
	$ci->db->from('custom_fy_week');
	$ci->db->where('start_date <=',$date);
	$ci->db->where('end_date>=',$date);
	$res=$ci->db->get();
	return $res->row_array();
}
function fresh_business_chart1($searchFilters)
{   
	$CI=& get_instance();
	$xAxisCategory=array();
	if($searchFilters['users']!='')
    {   
    	//echo $searchFilters['users'];
    	$users=$searchFilters['users'];
    	$reportees=getReportingUsers($users);
    	$user_id=$reportees.','.$users;
    }
    else
    {
    	$users=$CI->session->userdata('user_id');
    	$reportees=$CI->session->userdata('reportees');
    	$user_id=$reportees.','.$users;
    }
    $searchFilters['reportee_users']=$user_id;
     if($users != $CI->session->userdata('user_id'))
	{
		$l = getUserLocations($users);
		$ul = getQueryArray($l);
		$up = getUserProducts($users);
		$userLocations = ($ul == '')? 0: $ul;
		$userProducts = ($up == '')? 0: $up;
	}
	else
	{
		$userLocations = ($CI->session->userdata('locationString') == '')? 0: $CI->session->userdata('locationString');
		$userProducts = ($CI->session->userdata('products') == '')? 0: $CI->session->userdata('products');
	}
	$role_id=getUserRole($users);
	$searchFilters['userLocations']=$userLocations;
	$searchFilters['userProducts']=$userProducts;
	if($searchFilters['measure']==1)
	{
		$c1Data1=array();
		$c1Data2=array();
		$xAxisCategory=array();
		//$xAxisCategory=array('Suction','Dental Turbinies','Endodontic','Light Cure','Compressor');
		$segment_list=array();
		$fresh_list=$repeat_list=array();
		$fresh_results=$CI->Report_model->get_fresh_business_cnotes_by_product($searchFilters);
		//echo $CI->db->last_query(); exit;
		$repeat_results=$CI->Report_model->get_repeat_business_cnotes_by_product($searchFilters);
		//echo $CI->db->last_query();exit;
		foreach($fresh_results as $row)
		{
			$segment_list[$row['group_id']]=$row['name'];
			$fresh_list[$row['group_id']]=$row;
		}
		foreach($repeat_results as $row1)
		{
			if(!in_array($row1['group_id'], $segment_list))
			{
				$segment_list[$row1['group_id']]=$row1['name'];
			}
			$repeat_list[$row1['group_id']]=$row1;
		}
		foreach($segment_list as $key => $value)
		{
			
			$fresh_value=@$fresh_list[$key]['total_orders'];
			$fresh_value=($fresh_value>0)?$fresh_value:0;
			$c1Data1[]=(float)valueInLakhs($fresh_value);
			$repeat_value=@$repeat_list[$key]['total_orders'];
			$repeat_value=($repeat_value>0)?$repeat_value:0;
			$c1Data2[]=(float)valueInLakhs($repeat_value);
			$sum=(float)valueInLakhs($fresh_value)+(float)valueInLakhs($repeat_value);
			$fresh_percentage=round( (( (float)valueInLakhs($fresh_value) / $sum) * 100),2);
			$repeat_percentage=round( (( (float)valueInLakhs($repeat_value) / $sum) * 100),2);
			$xAxisCategory[]=$value.'<br> F-('.$fresh_percentage.' %) <br>R-('.$repeat_percentage.' %)';
		}
		//print_r($repeat_percentage); exit();
		$series[]=array('name'=>'Fresh Business','data'=>$c1Data1,'color'=>'#D23641');
		$series[]=array('name'=>'Repeat Business','data'=>$c1Data2,'color'=>'#663399');

		
	}
	if($searchFilters['measure']==2)
	{
		$c1Data1=array();
		$c1Data2=array();
		$xAxisCategory=array();
		//$xAxisCategory=array('Suction','Dental Turbinies','Endodontic','Light Cure','Compressor');
		$segment_list=array();
		$fresh_list=$repeat_list=array();
		$fresh_results=$CI->Report_model->get_fresh_business_cnotes_by_region($searchFilters);
		$repeat_results=$CI->Report_model->get_repeat_business_cnotes_by_region($searchFilters);
		foreach($fresh_results as $row)
		{
			$segment_list[$row['location_id']]=$row['location'];
			$fresh_list[$row['location_id']]=$row;
		}
		foreach($repeat_results as $row1)
		{
			if(!in_array($row1['location_id'], $segment_list))
			{
				$segment_list[$row1['location_id']]=$row1['location'];
			}
			$repeat_list[$row1['location_id']]=$row1;
		}
		foreach($segment_list as $key => $value)
		{
			//$xAxisCategory[]=$value;
			$fresh_value=@$fresh_list[$key]['total_orders'];
			$fresh_value=($fresh_value>0)?$fresh_value:0;
			$c1Data1[]=(float)valueInLakhs($fresh_value);
			$repeat_value=@$repeat_list[$key]['total_orders'];
			$repeat_value=($repeat_value>0)?$repeat_value:0;
			$c1Data2[]=(float)valueInLakhs($repeat_value);
			$sum=(float)valueInLakhs($fresh_value)+(float)valueInLakhs($repeat_value);
			$fresh_percentage=round( (( (float)valueInLakhs($fresh_value) / $sum) * 100),2);
			$repeat_percentage=round( (( (float)valueInLakhs($repeat_value) / $sum) * 100),2);
			$xAxisCategory[]=$value.'<br> F-('.$fresh_percentage.' %) <br>R-('.$repeat_percentage.' %)';
		}
		$series[]=array('name'=>'Fresh Business','data'=>$c1Data1,'color'=>'#D23641');
		$series[]=array('name'=>'Repeat Business','data'=>$c1Data2,'color'=>'#663399');
	}
	$year=get_current_fiancial_year();
	if(@$searchFilters['duration_text']!='')
	{
		if($searchFilters['vtime']=='w')
		{
			$text= '( '.substr(@$searchFilters['duration_text'],0,5).' )';
		}
		else
		{
			$text= '( '.@$searchFilters['duration_text'].' )';
		}
	}
	else
	{
		$text=$year['name'];
	}
	if($searchFilters['measure']==1)
	{
		$xAxisLable = 'Fresh & Repeat Business report By Segment '.$text;
	}
	if($searchFilters['measure']==2)
	{
		$xAxisLable = 'Fresh & Repeat Business report By Region '.$text;
	}
	$yAxisCategory='Value in Lakhs';
	//print_r(json_encode($series));exit;
	$chart1Data = array('xAxisCategory'=>$xAxisCategory,'yAxisCategory'=>$yAxisCategory,'chart1Series'=>$series,'xAxisLable'=>$xAxisLable);

	$chart1Data1 = json_encode($chart1Data);
	return $chart1Data1;
}
function FreshBusinessChart2Data($series_name,$category,$searchFilters)
{
	$CI=& get_instance();
	if($searchFilters['users']!='')
    {   
    	//echo $searchFilters['users'];
    	$users=$searchFilters['users'];
    	$reportees=getReportingUsers($users);
    	$user_id=$reportees.','.$users;
    }
    else
    {
    	$users=$CI->session->userdata('user_id');
    	$reportees=$CI->session->userdata('reportees');
    	$user_id=$reportees.','.$users;
    }
    $searchFilters['reportee_users']=$user_id;
     if($users != $CI->session->userdata('user_id'))
	{
		$l = getUserLocations($users);
		$ul = getQueryArray($l);
		$up = getUserProducts($users);
		$userLocations = ($ul == '')? 0: $ul;
		$userProducts = ($up == '')? 0: $up;
	}
	else
	{
		$userLocations = ($CI->session->userdata('locationString') == '')? 0: $CI->session->userdata('locationString');
		$userProducts = ($CI->session->userdata('products') == '')? 0: $CI->session->userdata('products');
	}
	$role_id=getUserRole($users);
	$searchFilters['userLocations']=$userLocations;
	$searchFilters['userProducts']=$userProducts;
	$c1Data1=array();
    if($searchFilters['measure']==1)
    {  
    	$segment_list=$fresh_list=$repeat_list=array();
    	$val=1;
        $fresh_results=$CI->Report_model->get_fresh_business_cnotes_by_product_customer($searchFilters,$category,$val);
        $val=2;
        $repeat_results=$CI->Report_model->get_fresh_business_cnotes_by_product_customer($searchFilters,$category,$val);

    	foreach($fresh_results as $row)
		{
			$segment_list[$row['product_id']]=$row['name'];
			$fresh_list[$row['product_id']]=$row;
		}
		foreach($repeat_results as $row1)
		{
			if(!in_array($row1['product_id'], $segment_list))
			{
				$segment_list[$row1['product_id']]=$row1['name'];
			}
			$repeat_list[$row1['product_id']]=$row1;
		}
		foreach($segment_list as $key => $value)
		{
			//$xAxisCategory[]=$value;
			$fresh_value=@$fresh_list[$key]['total_orders'];
			$fresh_value=($fresh_value>0)?$fresh_value:0;
			$c1Data1[]=(float)valueInLakhs($fresh_value);
			$repeat_value=@$repeat_list[$key]['total_orders'];
			$repeat_value=($repeat_value>0)?$repeat_value:0;
			$c1Data2[]=(float)valueInLakhs($repeat_value);
			$sum=(float)valueInLakhs($fresh_value)+(float)valueInLakhs($repeat_value);
			$fresh_percentage=round( (( (float)valueInLakhs($fresh_value) / $sum) * 100),2);
			$repeat_percentage=round( (( (float)valueInLakhs($repeat_value) / $sum) * 100),2);
			$xAxisCategory[]=$value.'<br> F-('.$fresh_percentage.' %) <br>R-('.$repeat_percentage.' %)';
		}
		$series[]=array('name'=>'Fresh Business','data'=>$c1Data1,'color'=>'#D23641');
		$series[]=array('name'=>'Repeat Business','data'=>$c1Data2,'color'=>'#663399');

		#for tabular form
		$fresh_cust=$CI->Report_model->get_customer_first_cnotes($searchFilters);
		$fresh_arr = array();
		$cust_arr = array();
		foreach ($fresh_cust as $key => $value) 
		{
			$fresh_arr[] = $value['contract_note_id'];
			$cust_arr[$value['customer_id']] = $value['contract_note_id'];
		}
		$results=$CI->Report_model->get_fresh_business_cnotes_by_product_customer_results($searchFilters,$category);
		$products=array();
		foreach ($results as $row) {
			$ex=explode(",", $row['c_noteid']);
			if(count($ex)>1)
			{
				$business = 'Repeat';
			}
			else
			{
				$check = 0;//fresh
				foreach($ex as $ex_data)
				{
					if(in_array($ex_data, $fresh_arr))
					{
						$check++;
					}

				}
				if($check == 1)
				{
					$business = 'Fresh';
				}
				else
				{
					$business = 'Repeat';
				}
			}
			
			$val = array();
			foreach ($ex as $key => $value) 
			{
				if(!in_array($value, $fresh_arr))
				{
					$val[] = $value;
				}
			}
			$repeat_cn = implode(', ', $val);
			$cn_string = '';
			$cn_string.= "Fresh : ".@$cust_arr[$row['customer_id']];
			if(count($val)>0)
			{
				$cn_string.='<br>Repeat : '.$repeat_cn;
			}
			
			$res=array();
			$res['total_orders']=valueInLakhs($row['total_orders']);
			//$res['name']=$row['c_name'].' ('.$row['c_noteid'].')'.' -fresh: '.$cust_arr[$row['customer_id']].')';
			$res['name']=$row['c_name'];
			$res['business_type'] = $business;
			$products[]=$res;	
	   }

    }
    if($searchFilters['measure']==2)
    {
    	$segment_list=$fresh_list=$repeat_list=array();
        $val=1;
    	$fresh_results=$CI->Report_model->get_fresh_business_cnotes_by_region_customer($searchFilters,$category,$val);
    	$val=2; 
    	$repeat_results=$CI->Report_model->get_fresh_business_cnotes_by_region_customer($searchFilters,$category,$val);
    	foreach($fresh_results as $row)
		{
			$segment_list[$row['user_id']]=$row['name'];
			$fresh_list[$row['user_id']]=$row;
		}
		foreach($repeat_results as $row1)
		{
			if(!in_array($row1['user_id'], $segment_list))
			{
				$segment_list[$row1['user_id']]=$row1['name'];
			}
			$repeat_list[$row1['user_id']]=$row1;
		}
		foreach($segment_list as $key => $value)
		{
			//$xAxisCategory[]=$value;
			$fresh_value=@$fresh_list[$key]['total_orders'];
			$fresh_value=($fresh_value>0)?$fresh_value:0;
			$c1Data1[]=(float)valueInLakhs($fresh_value);
			$repeat_value=@$repeat_list[$key]['total_orders'];
			$repeat_value=($repeat_value>0)?$repeat_value:0;
			$c1Data2[]=(float)valueInLakhs($repeat_value);
			$sum=(float)valueInLakhs($fresh_value)+(float)valueInLakhs($repeat_value);
			$fresh_percentage=round( (( (float)valueInLakhs($fresh_value) / $sum) * 100),2);
			$repeat_percentage=round( (( (float)valueInLakhs($repeat_value) / $sum) * 100),2);
			$xAxisCategory[]=$value.'<br> F-('.$fresh_percentage.' %) <br>R-('.$repeat_percentage.' %)';
		}
		$series[]=array('name'=>'Fresh Business','data'=>$c1Data1,'color'=>'#D23641');
		$series[]=array('name'=>'Repeat Business','data'=>$c1Data2,'color'=>'#663399');   
		$fresh_cust=$CI->Report_model->get_customer_first_cnotes($searchFilters);
		$fresh_arr = array();
		$cust_arr = array();
		foreach ($fresh_cust as $key => $value) 
		{
			$fresh_arr[] = $value['contract_note_id'];
			$cust_arr[$value['customer_id']] = $value['contract_note_id'];
		}
		$results=$CI->Report_model->get_fresh_business_cnotes_by_region_customer_results($searchFilters,$category);
		// /echo $CI->db->last_query();exit;  
		 $products=array(); 
		foreach ($results as $row) {
			$ex=explode(",", $row['c_noteid']);
			if(count($ex)>1)
			{
				$business = 'Repeat';
			}
			else
			{
				$check = 0;//fresh
				foreach($ex as $ex_data)
				{
					if(in_array($ex_data, $fresh_arr))
					{
						$check++;
					}

				}
				if($check == 1)
				{
					$business = 'Fresh';
				}
				else
				{
					$business = 'Repeat';
				}
			}
			
			$val = array();
			foreach ($ex as $key => $value) 
			{
				if(!in_array($value, $fresh_arr))
				{
					$val[] = $value;
				}
			}
			$repeat_cn = implode(', ', $val);
			$cn_string = '';
			$cn_string.= "Fresh : ".@$cust_arr[$row['customer_id']];
			if(count($val)>0)
			{
				$cn_string.='<br>Repeat : '.$repeat_cn;
			}
			$res=array();
			$res['total_orders']=valueInLakhs($row['total_orders']);
			$res['name']=$row['c_name'];
			$res['business_type'] = $business;
			$products[]=$res;	
	   }   
    }
    $year=get_current_fiancial_year();
	if(@$searchFilters['duration_text']!='')
	{
		if($searchFilters['vtime']=='w')
		{
			$text= '( '.substr(@$searchFilters['duration_text'],0,5).' )';
		}
		else
		{
			$text= '( '.@$searchFilters['duration_text'].' )';
		}
	}
	else
	{
		$text=$year['name'];
	}
    if($searchFilters['measure']==1)
	{
		$xAxisLable = 'Fresh & Repeat Business report By Product For '.$category.' '.$text;
		$table_text='Fresh & Repeat Business report Customer List For '.$category.' '.$text;
	}
	if($searchFilters['measure']==2)
	{
		$xAxisLable = 'Fresh & Repeat Business report By Sales Engineer For '.$category.' '.$text;
		$table_text = 'Fresh & Repeat Business report Customer List For '.$category.' '.$text;
	}
	$yAxisCategory='Value in Lakhs';
	$chart1Data = array('xAxisCategory'=>$xAxisCategory,'yAxisCategory'=>$yAxisCategory,'chart2Series'=>$series,'xAxisLable'=>$xAxisLable);
   //print_r($products);exit; 
	$chart1Data1 = json_encode(array('chart1Data'=>$chart1Data,'products'=>$products,'table_text'=>$table_text));
	return $chart1Data1;
}

function outstanding_chart1($searchFilters)
{   
	$CI=& get_instance();
	if($searchFilters['users']!='')
    {   
    	//echo $searchFilters['users'];
    	$users=$searchFilters['users'];
    	$reportees=getReportingUsers($users);
    	$user_id=$reportees.','.$users;
    }
    else
    {
    	$users=$CI->session->userdata('user_id');
    	$reportees=$CI->session->userdata('reportees');
    	$user_id=$reportees.','.$users;
    }
    $searchFilters['reportee_users']=$user_id;
     if($users != $CI->session->userdata('user_id'))
	{
		$l = getUserLocations($users);
		$ul = getQueryArray($l);
		$up = getUserProducts($users);
		$userLocations = ($ul == '')? 0: $ul;
		$userProducts = ($up == '')? 0: $up;
	}
	else
	{
		$userLocations = ($CI->session->userdata('locationString') == '')? 0: $CI->session->userdata('locationString');
		$userProducts = ($CI->session->userdata('products') == '')? 0: $CI->session->userdata('products');
	}
	$searchFilters['userLocations']=$userLocations;
	$searchFilters['userProducts']=$userProducts;
	$xAxisCategory=array();
	$xAxisCategory=array('3 Months','6 Months','9 Months','12 Months','More than Year');
	$yAxisCategory='Value in Lakhs';
	$xAxisLable = 'Outstanding Report';

	$date=date('Y-m-d');
    $months_3=date('Y-m-d',strtotime("-3 months",strtotime($date)));
    $months_6=date('Y-m-d',strtotime("-6 months",strtotime($date)));
    $months_9=date('Y-m-d',strtotime("-9 months",strtotime($date)));
    $months_12=date('Y-m-d',strtotime("-12 months",strtotime($date)));
    $ot_amount=$CI->Report_model->get_outstanding_standing_amount($searchFilters,$months_3,$months_6,$months_9,$months_12);
   // echo $CI->db->last_query();exit;
    $cm_3=valueInLakhs($ot_amount['month3'],2);
    $cm_6=valueInLakhs($ot_amount['month6'],2);
    $cm_9=valueInLakhs($ot_amount['month9'],2);
    $cm_12=valueInLakhs($ot_amount['month12'],2);
    $cm_year=valueInLakhs($ot_amount['gt_year'],2);
   $sales_amount=$CI->Report_model->get_outstanding_sales_amount($searchFilters,$months_3,$months_6,$months_9,$months_12);
  // print_r($sales_amount);
  // echo $CI->db->last_query();exit;
    $sm_3=$sales_amount['sm3'];
    $sm_6=$sales_amount['sm6'];
    $sm_9=$sales_amount['sm9'];
    $sm_12=$sales_amount['sm12'];
    $sm_year=$sales_amount['s_gtyear'];
    $collections_3=$sm_3-$cm_3;
    $collections_6=$sm_6-$cm_6;
    $collections_9=$sm_9-$cm_9;
    $collections_12=$sm_12-$cm_12;
    $collections_year=$sm_year-$cm_year;
    $series[]=array('name'=>'Outstanding','data'=>array((float)$cm_3,(float)$cm_6,(float)$cm_9,(float)$cm_12,(float)$cm_year),'color'=>'#D13F31');
    $series[]=array('name'=>'Collections','data'=>array((float)$collections_3,(float)$collections_6,(float)$collections_9,(float)$collections_12,(float)$collections_year),'color'=>'#669966');
   $chart1Data = array('xAxisCategory'=>$xAxisCategory,'yAxisCategory'=>$yAxisCategory,'chart1Series'=>$series,'xAxisLable'=>$xAxisLable);

	$chart1Data1 = json_encode($chart1Data);
	return $chart1Data1;
}
function outstanding_chart2($series_name,$category,$searchFilters)
{
	$CI=& get_instance();
	if($searchFilters['users']!='')
    {   
    	//echo $searchFilters['users'];
    	$users=$searchFilters['users'];
    	$reportees=getReportingUsers($users);
    	$user_id=$reportees.','.$users;
    }
    else
    {
    	$users=$CI->session->userdata('user_id');
    	$reportees=$CI->session->userdata('reportees');
    	$user_id=$reportees.','.$users;
    }
    $searchFilters['reportee_users']=$user_id;
     if($users != $CI->session->userdata('user_id'))
	{
		$l = getUserLocations($users);
		$ul = getQueryArray($l);
		$up = getUserProducts($users);
		$userLocations = ($ul == '')? 0: $ul;
		$userProducts = ($up == '')? 0: $up;
	}
	else
	{
		$userLocations = ($CI->session->userdata('locationString') == '')? 0: $CI->session->userdata('locationString');
		$userProducts = ($CI->session->userdata('products') == '')? 0: $CI->session->userdata('products');
	}
	$searchFilters['userLocations']=$userLocations;
	$searchFilters['userProducts']=$userProducts;
	$xAxisCategory=array();
	$date=date('Y-m-d');
    $months_3=date('Y-m-d',strtotime("-3 months",strtotime($date)));
    $months_6=date('Y-m-d',strtotime("-6 months",strtotime($date)));
    $months_9=date('Y-m-d',strtotime("-9 months",strtotime($date)));
    $months_12=date('Y-m-d',strtotime("-12 months",strtotime($date)));
	$yAxisCategory='Value in Lakhs';
	if($category=='3 Months')
	{
		$start_date=$months_3;
		$end_date='';
		$xAxisLable = 'Outstanding Amount Within 3 Months Duration';
		$next_tabel='Within 3 Months';
	}
	elseif ($category=='6 Months') {
		$start_date=$months_6;
		$end_date=$months_3;
		$xAxisLable = 'Outstanding Amount Between 3 to 6 Months Duration';
		$next_tabel='Within 3 to 6 Months';
	}
	elseif ($category=='9 Months') {
		$start_date=$months_9;
		$end_date=$months_6;
		$xAxisLable = 'Outstanding Amount Between 6 to 9 Months Duration';
		$next_tabel='Within 6 to 9 Months';
	}
	elseif ($category=='12 Months') {
		$start_date=$months_12;
		$end_date=$months_9;
		$xAxisLable = 'Outstanding Amount Between 9 to 12 Months Duration';
		$next_tabel='Within 9 to 12 Months';
	}
	else
	{
		$start_date='';
		$end_date=$months_12;
		$xAxisLable = 'Outstanding Amount With More Than Year Duration';
		$next_tabel='With More Than Year Duration';
	}
	$c1Data=array();
	$results=$CI->Report_model->get_customer_ot_amount($searchFilters,$start_date,$end_date);
	$colors=array('#FFD54F','#3F51B5','#7ABA7A','#FF9800','#F44336', '#4CAF50', '#9C27B0', '#795548', '#FFEB3B', '#42A5F5', '#CDDC39', '#A1887F', '#99b3ff', '#CC1559', '#6D929B', '#e87d7d', '#bea7a7', '#d9ff66','#717D8C', '#66ff8c');
	$i=0;
	foreach($results as $row)
	{
		if($i==20)
		{
			$i=0;
		}
		$xAxisCategory[]=$row['name'];
		$c1Data[]=array('y'=>(float)valueInLakhs($row['ot_amount']),'color'=>$colors[$i]);
		$i++;
	}
	$series[]=array('name'=>'Outstanding','data'=>$c1Data,'showInLegend'=>false);
	$chart1Data = array('xAxisCategory'=>$xAxisCategory,'yAxisCategory'=>$yAxisCategory,'chart1Series'=>$series,'xAxisLable'=>$xAxisLable,'next_tabel'=>$next_tabel);

	$chart1Data1 = json_encode($chart1Data);
	return $chart1Data1;
}
function outstanding_chart3($searchFilters,$category,$series_name,$aging)
{
	$c1Data1=array();
	$xAxisCategory=array();
	$xAxisLable = 'Customers';
	$CI=& get_instance();
	if($searchFilters['users']!='')
    {   
    	//echo $searchFilters['users'];
    	$users=$searchFilters['users'];
    	$reportees=getReportingUsers($users);
    	$user_id=$reportees.','.$users;
    }
    else
    {
    	$users=$CI->session->userdata('user_id');
    	$reportees=$CI->session->userdata('reportees');
    	$user_id=$reportees.','.$users;
    }
    $searchFilters['reportee_users']=$user_id;
     if($users != $CI->session->userdata('user_id'))
	{
		$l = getUserLocations($users);
		$ul = getQueryArray($l);
		$up = getUserProducts($users);
		$userLocations = ($ul == '')? 0: $ul;
		$userProducts = ($up == '')? 0: $up;
	}
	else
	{
		$userLocations = ($CI->session->userdata('locationString') == '')? 0: $CI->session->userdata('locationString');
		$userProducts = ($CI->session->userdata('products') == '')? 0: $CI->session->userdata('products');
	}
	$searchFilters['userLocations']=$userLocations;
	$searchFilters['userProducts']=$userProducts;
	$date=date('Y-m-d');
    $months_3=date('Y-m-d',strtotime("-3 months",strtotime($date)));
    $months_6=date('Y-m-d',strtotime("-6 months",strtotime($date)));
    $months_9=date('Y-m-d',strtotime("-9 months",strtotime($date)));
    $months_12=date('Y-m-d',strtotime("-12 months",strtotime($date)));
	$yAxisCategory='Value in Lakhs';
	if($aging=='3 Months')
	{
		$start_date=$months_3;
		$end_date='';
		$xAxisLable = 'Outstanding Amount Within 3 Months Duration For'.$category;
	}
	elseif ($aging=='6 Months') {
		$start_date=$months_6;
		$end_date=$months_3;
		$xAxisLable = 'Outstanding Amount Between 3 to 6 Months Duration For'.$category;
	}
	elseif ($aging=='9 Months') {
		$start_date=$months_9;
		$end_date=$months_6;
		$xAxisLable = 'Outstanding Amount Between 6 to 9 Months Duration For'.$category;
	}
	elseif ($aging=='12 Months') {
		$start_date=$months_12;
		$end_date=$months_9;
		$xAxisLable = 'Outstanding Amount Between 9 to 12  Months Duration For'.$category;
	}
	else
	{
		$start_date='';
		$end_date=$months_12;
		$xAxisLable = 'Outstanding Amount With More Than Year Duration For'.$category;
	}
	/*$all_y=array('y'=>(float)valueInLakhs($all),'color'=>'#99b3ff');
	$all_x="ALL";
	array_unshift($c1Data1, $all_y);
	array_unshift($xAxisCategory,$all_x);
	$series = array();
	$series []= array('showInLegend'=>FALSE,'name'=>'Open Orders','data'=>$c1Data1);

	$chart1Data = array('xAxisCategory'=>$xAxisCategory,'chart3Series'=>$series,'xAxisLable'=>$xAxisLable);*/
	//print_r($customers);exit;
	$customer=array();
	$results=$CI->Report_model->get_customer_ot_amount_details($searchFilters,$start_date,$end_date,$category);
	//echo $CI->db->last_query(); exit();
	foreach ($results as $row) {
		$res=array();
		$res['contract_note_id']=$row['contract_note_id'];
		$res['first_name']=$row['first_name'];
		$res['product_details']=$row['product_details'];
		$res['total_orders']=valueInLakhs($row['total_orders'],2);
		$res['outstanding_amount']=valueInLakhs($row['outstanding_amount'],2);
		$res['cus_location']=$row['cus_location'];
		$customer[]=$res;
	}
	$list=array('customers'=>$customer);
	$chart1Data = json_encode($list);
	return $chart1Data;
}
function runrate_chart1($searchFilters)
{	
	$CI=& get_instance();
	if($searchFilters['users']!='')
    {   
    	//echo $searchFilters['users'];
    	$users=$searchFilters['users'];
    	$reportees=getReportingUsers($users);
    	$user_id=$reportees.','.$users;
    }
    else
    {
    	$users=$CI->session->userdata('user_id');
    	$reportees=$CI->session->userdata('reportees');
    	$user_id=$reportees.','.$users;
    }
     $role_id=getUserRole($users);
	//echo $role_id.$user_id;
    if($users != $CI->session->userdata('user_id'))
	{
		$l = getUserLocations($users);
		$ul = getQueryArray($l);
		$up = getUserProducts($users);
		$userLocations = ($ul == '')? 0: $ul;
		$userProducts = ($up == '')? 0: $up;
	}
	else
	{
		$userLocations = ($CI->session->userdata('locationString') == '')? 0: $CI->session->userdata('locationString');
		$userProducts = ($CI->session->userdata('products') == '')? 0: $CI->session->userdata('products');
	}
	$searchFilters['user_reportees_tvs']=$user_id;
    $searchFilters['userProducts']=$userProducts;
    $searchFilters['userLocations']=$userLocations;
    $searchFilters['role_id']=$role_id;

    if($searchFilters['zone']==1)
    {
		$xAxisCategory=array();
		//$xAxisCategory=array('Apr', 'May', 'Jun','Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec','Jan', 'Feb', 'Mar');
		$yAxisCategory='Value In Lakhs';

		if($searchFilters['region']!='')
		{
			$loc=$CI->Common_model->get_data_row('location',array('location_id'=>$searchFilters['region']));
			$location=$loc['location'].' Wise ';
		}
		$year=get_current_fiancial_year();
		if(@$searchFilters['duration_text']!='')
		{
			if($searchFilters['vtime']=='w')
			{
				$text='( '.substr(@$searchFilters['duration_text'],0,5).' )';
			}
			else
			{
				$text= '( '.@$searchFilters['duration_text'].' )';
			}
		}
		else
		{
			$text=$year['name'];
		}
		$ores=get_dates($searchFilters);
		//print_r($ores);exit;
		$xAxisLable = @$location.'Run Rate Projection '.$text;
		$rr=1; 
	    $tline=get_year_based_timeline($searchFilters,$rr);
	    $opp_created=array();
	    foreach ($tline as $row) {
			$k1=array();
			//getting funnel opportunities
			$res = $CI->Report_model->get_run_rate_funnel_opportunites($searchFilters,$row);
			$yt_timeline=date('M-y',strtotime($row['timeline']));
			if(count($res)>0)
			   	 { 
				   	$k1['opened_value']=@$res['opened_value'];
				   	$k1['timeline']=$yt_timeline;
				   	$k1['ctime']=@$res['ctime'];
				   	$k1['month_timeline']=@$res['month_timeline'];
				   	$k1['fy_week_id']=@$row['fy_week_id'];
				   	$opp_created[]=$k1;
				 }
		}
		$ores=get_runrate_dates($searchFilters);
		//getting sales
	    $sales = $CI->Report_model->get_funnel_sales($searchFilters);
	  //  echo $CI->db->last_query();exit;
	    $sales_created = array();
	    if(count($sales)>0)
	    {
	    	foreach ($sales as $row1) {
	    		$k2 = array();
	    		$k2['current_sales']=$row1['current_sales'];
	    		$k2['timeline']=$row1['timeline'];
	    		$k2['ctime']=$row1['ctime'];
	    		$sales_created[]=$k2;
	    	}
	    }
	    $new_op = array();
		foreach ($opp_created as $orow) {
			$new_op[$orow['timeline']] = $orow;
		}
		$new_sales = array();
		foreach ($sales_created as $srow) {
			$new_sales[$srow['timeline']] = $srow;
		}
		/*print_r($new_op);
		echo '<pre>';
		print_r($new_sales);exit;*/
		$c1Data=array();
		$c2Data=array();
		$c3Data=array();
		$c4Data=array();
		$c5Data=array();
		$curdate=date('Y-m-t');
		$end_date=date('M-y',strtotime($curdate));
		$total_opp_value=0;
		$sale_val=0;
		$avg_conversion=0;
		$conversion_rate=array();
		$count=0;
		foreach($ores as $x_axix_lable)
		{   
			//echo $x_axix_lable;exit;
			$new_op_val = (@$new_op[$x_axix_lable]['opened_value']!='')?$new_op[$x_axix_lable]['opened_value']:0;
		    $c1Data[]=($new_op_val>0)?round($new_op_val,2):0;
			$total_opp_value+=@$new_op[$x_axix_lable]['opened_value'];
			$label_date =  date('Y-m-t',strtotime('01-'.$x_axix_lable));
			if($label_date <= $curdate)
			{
				$new_sale_val = (@$new_sales[$x_axix_lable]['current_sales']!='')?$new_sales[$x_axix_lable]['current_sales']:0;
				$sale_val+=$new_sale_val;
				$month_no = date('m',strtotime($x_axix_lable));
				if($new_op_val!='')
				{
					$conversion_rate[]=round((($new_sale_val/@$new_op_val)*100),2);
				}
				else
				{
					$conversion_rate[]=0;
				}
				$c2Data[]=($new_sale_val>0)?round($new_sale_val,2):0;
				$c3Data[]='';
				$c4Data[]='';
				$c5Data[]='';
				$count++;
                if($new_op_val!='')
                {
					$avg_conversion=round((($new_sale_val/@$new_op_val)*100),2);
				}
				else
				{
					$avg_conversion=0;
				}
			}
			else
			{   
				$avg_con=array_sum($conversion_rate)/count($conversion_rate);
                $avg_conversion=round($avg_con,2);
				$conversion_rate[]=$avg_conversion;
				$predicted_sale=($avg_conversion*$new_op_val)/100;
				$max_con_rate=max($conversion_rate);
				$min_con_rate=min($conversion_rate);
				$avg_opp_val=array_sum($c1Data)/count($c1Data);
				$max_con_val=($max_con_rate*$new_op_val)/100;
				$min_con_val=($min_con_rate*$new_op_val)/100;
				if($searchFilters['range']!=''&&$count<11)
				{
					$range_con_val=($searchFilters['range']*$avg_opp_val)/100;
					$c5Data[]=array('y'=>($range_con_val>0)?round($range_con_val,2):'','marker'=>array('symbol'=>'url(https://www.highcharts.com/samples/graphics/sun.png)'));
				}
				$c3Data[]=array('y'=>($max_con_val>0)?round($max_con_val,2):'','marker'=>array('symbol'=>'url(https://www.highcharts.com/samples/graphics/sun.png)'));
				$c2Data[]=array('y'=>($predicted_sale>0)?round($predicted_sale,2):'','marker'=>array('symbol'=>'url(https://www.highcharts.com/samples/graphics/sun.png)'));
				$c4Data[]=array('y'=>($min_con_val>0)?round($min_con_val,2):'','marker'=>array('symbol'=>'url(https://www.highcharts.com/samples/graphics/sun.png)'));
				
			}
			$xAxisCategory[]=$x_axix_lable.'<br>'.'('.$avg_conversion.'%)';
		}
		$max_con_rate=max($conversion_rate);
		$min_con_rate=min($conversion_rate);
		if($searchFilters['range']!=''&&$count<11)
		{
			$series[]=array('name'=>'Conversion Rate('.$searchFilters['range'].'%)','data'=>$c5Data,'color'=>'#CC0000');
		}
	   if($count<11)
		{
			$series[]=array('name'=>'Max Conversion Rate('.$max_con_rate.'%)','data'=>$c3Data,'color'=>'#097054');
	    }
		$series[]=array('name'=>'Open Opportunities','data'=>$c1Data,'marker'=>array(
	            'symbol'=> 'diamond'
	        ),'color'=>'#6599FF');
		$series[]=array('name'=>'Closed Won','color'=>'#FF9900','marker'=>array(
	            'symbol'=> 'square'
	        ),'data'=>$c2Data,'zoneAxis'=> 'x',
	        'zones'=> array(array(
	            'value'=> $count
	        ), array(
	            'dashStyle'=> 'dot'
	        )));
	    if($count<11)
	    {
			$series[]=array('name'=>'Min Conversion Rate('.$min_con_rate.'%)','data'=>$c4Data,'color'=>'#FFDE00');
	    }
		$chart1Data = array('xAxisCategory'=>$xAxisCategory,'yAxisCategory'=>$yAxisCategory,'chart1Series'=>$series,'xAxisLable'=>$xAxisLable,'curr_date'=>$curdate,'end_date'=>$end_date);
		$chart1Data = json_encode($chart1Data);
		// /print_r($chart1Data);exit;
		return $chart1Data;
	}
	else
	{
		$ores=get_dates($searchFilters);
		$rr=1; 
	    $tline=get_year_based_timeline($searchFilters,$rr);
	     foreach ($tline as $row) {
			$k1=array();
			//getting funnel opportunities
			$yt_timeline=date('M-y',strtotime($row['timeline']));
			$res = $CI->Report_model->get_run_rate_funnel_opportunites($searchFilters,$row);
			if(count($res)>0)
			   	 { 
				   	$k1['opened_value']=@$res['opened_value'];
				   	$k1['timeline']=$yt_timeline;
				   	$k1['ctime']=@$res['ctime'];
				   	$k1['month_timeline']=@$res['month_timeline'];
				   	$k1['fy_week_id']=@$row['fy_week_id'];
				   	$opp_created[]=$k1;
				 }
		}
		$ores=get_runrate_dates($searchFilters);
		//getting sales
	    $sales = $CI->Report_model->get_funnel_sales($searchFilters);
	    $sales_created = array();
	    if(count($sales)>0)
	    {
	    	foreach ($sales as $row1) {
	    		$k2 = array();
	    		$k2['current_sales']=$row1['current_sales'];
	    		$k2['timeline']=$row1['timeline'];
	    		$k2['ctime']=$row1['ctime'];
	    		$sales_created[]=$k2;
	    	}
	    }
	    $new_op = array();
		foreach ($opp_created as $orow) {
			$new_op[$orow['timeline']] = $orow;
		}
		$new_sales = array();
		foreach ($sales_created as $srow) {
			$new_sales[$srow['timeline']] = $srow;
		}
		$curdate=date('Y-m-t');
		$total_opp_value=0;
		$sale_val=0;
		$avg_conversion=0;
		$conversion_rate=array();
		$count=0;
		$runrate_results=array();
		foreach($ores as $x_axix_lable)
		{   
			$k3=array();
			$new_op_val = (@$new_op[$x_axix_lable]['opened_value']!='')?$new_op[$x_axix_lable]['opened_value']:0;
			$c1Data[]=($new_op_val>0)?round($new_op_val,2):0;
		    $k3['new_op_val']=($new_op_val>0)?round($new_op_val,2):0;
			$total_opp_value+=@$new_op[$x_axix_lable]['opened_value'];
			$label_date =  date('Y-m-t',strtotime('01-'.$x_axix_lable));
			$month_name=date('F',strtotime('01-'.$x_axix_lable));
			$k3['month_name']=$month_name;
			if($label_date <= $curdate)
			{
				$new_sale_val = (@$new_sales[$x_axix_lable]['current_sales']!='')?$new_sales[$x_axix_lable]['current_sales']:0;
				$k3['new_sale_val']=$new_sale_val;
				$sale_val+=$new_sale_val;
				$month_no = date('m',strtotime($x_axix_lable));
				if($new_op_val!='')
				{   
					$crate=round((($new_sale_val/@$new_op_val)*100),2);
					$conversion_rate[]=$crate;
					$k3['conversion_rate']=$crate;
				}
				else
				{
					$conversion_rate[]=0;
					$k3['conversion_rate']=0;
				}
				$count++;
				$k3['color']='';
				$k3['min_con_rate']='';
				$k3['min_con_val']='';
				$k3['max_con_rate']='';
				$k3['max_con_val']='';
				$k3['cus_con_rate']='';
				$k3['cus_con_val']='';
			}
			else
			{   
				$avg_conversion=array_sum($conversion_rate)/count($conversion_rate);
				$k3['conversion_rate']=round($avg_conversion,2);
				$k3['color']='green';
				$conversion_rate[]=$avg_conversion;
				$predicted_sale=($avg_conversion*$new_op_val)/100;
				$k3['new_sale_val']=round($predicted_sale,2);
				$max_con_rate=max($conversion_rate);
				$k3['max_con_rate']=$max_con_rate;
				$min_con_rate=min($conversion_rate);
				$k3['min_con_rate']=$min_con_rate;
				$avg_opp_val=array_sum($c1Data)/count($c1Data);
				$max_con_val=($max_con_rate*$new_op_val)/100;
				$k3['max_con_val']=round($max_con_val,2);
				$min_con_val=($min_con_rate*$new_op_val)/100;
				$k3['min_con_val']=round($min_con_val,2);
				if($searchFilters['range']!=''&&$count<11)
				{
					$range_con_val=($searchFilters['range']*$avg_opp_val)/100;
					$k3['cus_con_rate']=$searchFilters['range'];
					$k3['cus_con_val']=round($range_con_val,2);
				}
				else
				{
					$k3['cus_con_rate']=0;
					$k3['cus_con_val']=0;
				}
			}
			$runrate_results[]=$k3;
		}
		return array($runrate_results,$conversion_rate);

	}
}
function incentives_chart1($searchFilters)
{
	
	$CI=& get_instance();

	if($searchFilters['users']!='')
    {   
    	//echo $searchFilters['users'];
    	$users=$searchFilters['users'];
    	$reportees=getReportingUsers($users);
    	$reportee_users_id=$reportees.','.$users;
    }
    else
    {
    	$users=$CI->session->userdata('user_id');
    	$reportees=$CI->session->userdata('reportees');
    	$reportee_users_id=$reportees.','.$users;
    }
    $role_id=getUserRole($users);
	//echo $role_id.$user_id;
    


	$yAxisCategory='value In Lakhs';
	$xAxisCategory=array();
	

	$roles=$CI->Report_model->get_all_roles($reportee_users_id);

	$series=array();
	$c1Data=array();
	foreach($roles as $row)
	{
		$role_based_users=$CI->Report_model->get_role_based_users($row['role_id'],$reportee_users_id);
		$amount=0;
		
		foreach($role_based_users as $users)
		{
			$check1=$check2=$check3=0;
			$cat_id='';
			$l = getUserLocations($users['user_id']);
			$ul = getQueryArray($l);
			$up = getUserProducts($users['user_id']);
			$userLocations = ($ul == '')? 0: $ul;
			$userProducts = ($up == '')? 0: $up;
			$reportees=getReportingUsers($users['user_id']);
    		$user_id=$reportees.','.$users['user_id'];
    		//$user_id=27;
    		$product_category=$CI->Report_model->get_product_category($userProducts);
    		//print_r($searchFilters); exit;
    		if(count($product_category)<=1)
			{
				$cat_id=$product_category[0]['category_id'];
			}
			else
			{
				$cat_id='';
			}
    		$sales_without_category = $CI->Report_model->get_incentive_user_sales($user_id,$searchFilters,$userLocations,$userProducts,$cat_id,$row['role_id']);
    		$target_without_category = $CI->Report_model->get_incentive_user_target($user_id,$searchFilters,$cat_id);
    		foreach($product_category as $row1)
    		{
    			$incentive_user_target=$CI->Report_model->get_incentive_user_target($user_id,$searchFilters,$row1['category_id']);
    			$incentive_user_sales=$CI->Report_model->get_incentive_user_sales($user_id,$searchFilters,$userLocations,$userProducts,$row1['category_id'],$row['role_id']);
    			if($incentive_user_sales['current_sales']>0 && $incentive_user_target['current_target']>0)
				{
					$percent = ($incentive_user_sales['current_sales'] / $incentive_user_target['current_target'])*100;
				}
				else
				{
					$percent=0;
				}
				$incentives=$CI->Common_model->get_data_row('incentives',array('role_id'=>$row['role_id'],'fy_id'=>$searchFilters['year'],'status'=>1));

				if($percent >= $incentives['pp_ll'])
				{
					$check1++;
					if($percent>= 100)
					{
						$check2++;
					}
				}
				else if($percent >= $incentives['sp2_ll'] && $percent <= $incentives['sp2_ul'])
				{
					$check3++;
				}
    		}
    		
			if(count($product_category) == $check1 )
			{
				if($sales_without_category['current_sales']>0 && $target_without_category['current_target']>0)
				{   
					$inc_amt = round((($sales_without_category['current_sales']/$target_without_category['current_target'])*$incentives['value'])/2);
					

				}
				else
				{
					$inc_amt=0;
				}

				if($inc_amt>$incentives['upper_value']/2)
				{
					$inc_amt=$incentives['upper_value']/2;
				}

			}
			else if($row['role_id'] == 4)
			{
				//check given is grade B
				if(count($product_category) == ($check2+$check3)  && $check2>0)
				{
					
					$inc_amt = 15000/2;
				}
				else
				{
					$inc_amt = 0;
				}
			}
			else
			{
				//fail
				$inc_amt = 0;
			}
			$amount+=$inc_amt;
		}
		//$user_row=implode(',', $user_ids);
		$xAxisCategory[]=$row['short_name'];
		$c1Data[]=array('y'=>(float)valueInLakhs($amount));
		
		//echo "<pre>"; echo $user_row;exit;
	}
	$text=$searchFilters['year_text'].' - '.$searchFilters['duration_text'];
	$xAxisLable = 'Part1 Incentives For ( '.$text.' )';
    $series[]=array('name'=>'Incentives','showInLegend'=>false,'data'=>$c1Data);	
	$chart1Data = array('xAxisCategory'=>$xAxisCategory,'yAxisCategory'=>$yAxisCategory,'chart1Series'=>$series,'xAxisLable'=>$xAxisLable);


	$chart1Data1 = json_encode($chart1Data);
	//echo "<pre>"; print_r($chart1Data1); exit;
	return $chart1Data1;
}





function get_incentives_quarters($fy_id)
{

	$qry_data='';
	$ci=& get_instance();
	$i=1;
	$res=get_quat_array();
	$curr_fy_arr = get_current_fiancial_year();
	$cur_fy_id = $curr_fy_arr['fy_id'];

	foreach($res as $value)
	{  
		$curr_month = date('m');
		if(in_array($curr_month, $value) && $fy_id == $cur_fy_id)
		{
			break;
		}
		

		$row=get_financial_year_quarters($value,$fy_id);
    	//if($row['start_date']<=date('Y-m-d'))
		//{
			$qry_data[]=array('start_date'=>$row['start_date'],'end_date'=>$row['end_date'],'quarter'=>'Quarter'.$i);
			$i++;
		//}   
	} 
	return $qry_data;
}
function get_financial_year_quarters($qtr,$year_id)
{
	//$curdate=date('Y-m-d');
	$CI=& get_instance();
	$CI->db->select('min(fws.start_date) as start_date,max(fws.end_date) as end_date');
	$CI->db->from('financial_year fy');
	$CI->db->join('fy_week fws','fy.fy_id=fws.fy_id');
	//$CI->db->where('fy.start_date<=',$curdate);
	//$CI->db->where('fy.end_date>=',$curdate);
	$CI->db->where('fy.fy_id',$year_id);
	$CI->db->where_in('fws.month_no',$qtr);

	//$CI->db->group_by('fw.month_no');
	$res=$CI->db->get();
	return $res->row_array();
}
function get_year_quarter_dropdown($fy_id)
{
	$qry_data='';
	$ci=& get_instance();
	$i=1;
	$res=get_quat_array();
	$curr_fy_arr = get_current_fiancial_year();
	$cur_fy_id = $curr_fy_arr['fy_id'];

	foreach($res as $value)
	{  
		$curr_month = date('m');
		if(in_array($curr_month, $value) && $fy_id == $cur_fy_id)
		{
			break;
		}
		$row=get_financial_year_quarters($value,$fy_id);
			
		$qry_data.='<option value="'.$row['start_date'].'to'.$row['end_date'].'">'."Quarter".$i.'</option>';
		$i++;
	} 
	return $qry_data;
}
function incentives_chart2($searchFilters)
{
	$CI=& get_instance();
	if($searchFilters['users']!='')
    {   
    	//echo $searchFilters['users'];
    	$users=$searchFilters['users'];
    	$reportees=getReportingUsers($users);
    	$reportee_users_id=$reportees.','.$users;
    }
    else
    {
    	$users=$CI->session->userdata('user_id');
    	$reportees=$CI->session->userdata('reportees');
    	$reportee_users_id=$reportees.','.$users;
    }
     $role_id=getUserRole($users);
	//echo $role_id.$user_id;
    

	$yAxisCategory='Incentive Amount';
	$xAxisCategory=array();
	$xAxisLable = 'Incentives';
	$role_id=$CI->Common_model->get_value('role',array('short_name'=>$searchFilters['series_name']),'role_id');
	$role_based_users=$CI->Report_model->get_role_based_users($role_id,$reportee_users_id);
	$incentive_data=array();	
	$inc_data=array();
	foreach($role_based_users as $users)
	{
		$check1=$check2=$check3=0;
		$cat_id='';
		$l = getUserLocations($users['user_id']);
		$ul = getQueryArray($l);
		$up = getUserProducts($users['user_id']);
		$userLocations = ($ul == '')? 0: $ul;
		$userProducts = ($up == '')? 0: $up;
		$reportees=getReportingUsers($users['user_id']);
		$user_id=$reportees.','.$users['user_id'];
		$product_category=$CI->Report_model->get_product_category($userProducts);
		if(count($product_category)<=1)
		{
			$cat_id=$product_category[0]['category_id'];
		}
		else
		{
			$cat_id='';
		}
		$sales_without_category = $CI->Report_model->get_incentive_user_sales($user_id,$searchFilters,$userLocations,$userProducts,$cat_id,$role_id);

    	$target_without_category = $CI->Report_model->get_incentive_user_target($user_id,$searchFilters,$cat_id);

    	$incentive_data['role']=$searchFilters['series_name'];
    	$user_data=$CI->Common_model->get_data_row('user',array('user_id'=>$users['user_id']));
    	$incentive_data['name']=$user_data['first_name'].' '.$user_data['last_name'];
    	$incentive_data['user']=$users['user_id'];
    	foreach($product_category as $row1)
		{
			$incentive_user_target=$CI->Report_model->get_incentive_user_target($user_id,$searchFilters,$row1['category_id']);
			//echo $incentive_user_target['current_target']; exit;
    		//echo $CI->db->last_query();  exit;
			$incentive_user_sales=$CI->Report_model->get_incentive_user_sales($user_id,$searchFilters,$userLocations,$userProducts,$row1['category_id'],$role_id);
			if($incentive_user_target['current_target']!='')
			{
				$incentive_data['targets'][$row1['category_id']][$users['user_id']]=$incentive_user_target;
			}
			else
			{
				$incentive_data['targets'][$row1['category_id']][$users['user_id']]=array('current_target'=>0);	
			}
			if($incentive_user_sales['current_sales']>0)
			{
				$incentive_data['sales'][$row1['category_id']][$users['user_id']]=$incentive_user_sales;
			}
			else
			{
				$incentive_data['sales'][$row1['category_id']][$users['user_id']]=array('current_sales'=>0);	
			}
			if($incentive_user_sales['current_sales']>0 && $incentive_user_target['current_target']>0)
			{
				$percent = ($incentive_user_sales['current_sales'] / $incentive_user_target['current_target'])*100;
			}
			else
			{
				$percent=0;
			}
			$incentives=$CI->Common_model->get_data_row('incentives',array('role_id'=>$role_id,'fy_id'=>$searchFilters['year'],'status'=>1));

			if($percent >= $incentives['pp_ll'])
			{
				$check1++;
				if($percent>= 100)
				{
					$check2++;
				}
			}
			else if($percent >= $incentives['sp2_ll'] && $percent <= $incentives['sp2_ul'])
			{
				$check3++;
			}
		}
		
		if(count($product_category) == $check1)
		{
			
			if($sales_without_category['current_sales']>0 && $target_without_category['current_target']>0)
			{   
				$inc_amt = round((($sales_without_category['current_sales']/$target_without_category['current_target'])*$incentives['value'])/2);
			}

			else
			{
				$inc_amt=0;
			}

			if($inc_amt>$incentives['upper_value']/2)
			{
				$inc_amt=$incentives['upper_value']/2;
			}
			$incentive_data['incentive_amount']=indian_format_price($inc_amt);
			$incentive_data['tat_amt']=$inc_amt;
		}
		else if($role_id == 4)
		{
			//check given is grade B
			if(count($product_category) == ($check2+$check3)  && $check2>0)
			{
				
				$inc_amt = 15000/2;
			}
			else
			{
				$inc_amt = 0;
			}
			$incentive_data['incentive_amount']=indian_format_price($inc_amt);
			$incentive_data['tat_amt']=$inc_amt;
		}
		else
		{
			//fail
			$inc_amt = 0;
			$incentive_data['incentive_amount']=$inc_amt;
			$incentive_data['tat_amt']=$inc_amt;
		}
		$inc_data[]=$incentive_data;
	}

	$total_incentive=array_sum(array_column($inc_data, 'tat_amt'));
	//$inc_data['total_amount']=$total_incentive;
	//echo '<pre>'; print_r($inc_data); exit;
	$text=$searchFilters['year_text'].' - '.$searchFilters['duration_text'];
	$table_text = 'Part1 Incentives For ( '.$text.' )';
	$arr=array('results'=>$inc_data,'table_text'=>$table_text,'total_amount'=>indian_format_price($total_incentive),'product_category'=>$product_category);
	return json_encode($arr); 
}

function incentives_user_chart1($searchFilters)
{
	$CI=& get_instance();
	$yAxisCategory='Incentive Amount';
	$xAxisCategory=array();
	$xAxisLable = 'Incentives';
	$role_id=$CI->Common_model->get_value('user',array('user_id'=>$searchFilters['users']),'role_id');
	//$role_based_users=$CI->Report_model->get_role_based_users($role_id);
	$incentive_data=array();	
	$inc_data=array();
	$check1=$check2=$check3=0;
	$cat_id='';
	$l = getUserLocations($searchFilters['users']);
	$ul = getQueryArray($l);
	$up = getUserProducts($searchFilters['users']);
	$userLocations = ($ul == '')? 0: $ul;
	$userProducts = ($up == '')? 0: $up;
	$reportees=getReportingUsers($searchFilters['users']);
	$user_id=$reportees.','.$searchFilters['users'];
	$product_category=$CI->Report_model->get_product_category($userProducts);
	if(count($product_category)<=1)
	{
		$cat_id=$product_category[0]['category_id'];
	}
	else
	{
		$cat_id='';
	}
	$sales_without_category = $CI->Report_model->get_incentive_user_sales($user_id,$searchFilters,$userLocations,$userProducts,$cat_id,$role_id);
	$target_without_category = $CI->Report_model->get_incentive_user_target($user_id,$searchFilters,$cat_id);
	$role_name=$CI->Common_model->get_value('role',array('role_id'=>$role_id),'short_name');
	$incentive_data['role']=$role_name;
	$user_data=$CI->Common_model->get_data_row('user',array('user_id'=>$searchFilters['users']));
	$incentive_data['name']=$user_data['first_name'].' '.$user_data['last_name'];
	$incentive_data['region']=$sales_without_category['region'];
	foreach($product_category as $row1)
	{
		$incentive_user_target=$CI->Report_model->get_incentive_user_target($user_id,$searchFilters,$row1['category_id']);

		$incentive_user_sales=$CI->Report_model->get_incentive_user_sales($user_id,$searchFilters,$userLocations,$userProducts,$row1['category_id'],$role_id);
		if($incentive_user_target['current_target']!='')
		{
			$incentive_data['targets'][$row1['category_id']]=$incentive_user_target;
		}
		else
		{
			$incentive_data['targets'][$row1['category_id']]=array('current_target'=>0);	
		}
		if($incentive_user_sales['current_sales']>0)
		{
			$incentive_data['sales'][$row1['category_id']]=$incentive_user_sales;
		}
		else
		{
			$incentive_data['sales'][$row1['category_id']]=array('current_sales'=>0);	
		}
		if($incentive_user_sales['current_sales']>0 && $incentive_user_target['current_target']>0)
		{
			$percent = ($incentive_user_sales['current_sales'] / $incentive_user_target['current_target'])*100;
		}
		else
		{
			$percent=0;
		}
		$incentives=$CI->Common_model->get_data_row('incentives',array('role_id'=>$role_id,'fy_id'=>$searchFilters['year'],'status'=>1));

		if($percent >= $incentives['pp_ll'])
		{
			$check1++;
			if($percent>= 100)
			{
				$check2++;
			}
		}
		else if($percent >= $incentives['sp2_ll'] && $percent <= $incentives['sp2_ul'])
		{
			$check3++;
		}
	}
	
	if(count($product_category) == $check1 )
	{
		if($sales_without_category['current_sales']>0 && $target_without_category['current_target']>0)
		{   
			$inc_amt = round((($sales_without_category['current_sales']/$target_without_category['current_target'])*$incentives['value'])/2);
			

		}
		else
		{
			$inc_amt=0;
		}

		if($inc_amt>$incentives['upper_value']/2)
		{
			$inc_amt=$incentives['upper_value']/2;
		}
		$incentive_data['incentive_amount']=indian_format_price($inc_amt);
		$incentive_data['tat_amt']=$inc_amt;

	}
	else if($role_id == 4)
	{
		//check given is grade B
		if(count($product_category) == ($check2+$check3)  && $check2>0)
		{
			
			$inc_amt = 15000/2;
		}
		else
		{
			$inc_amt = 0;
		}
		$incentive_data['incentive_amount']=indian_format_price($inc_amt);
		$incentive_data['tat_amt']=$inc_amt;
	}
	else
	{
		//fail
		$inc_amt = 0;
		$incentive_data['incentive_amount']=$inc_amt;
		$incentive_data['tat_amt']=$inc_amt;
	}

	$inc_data[]=$incentive_data;
	$total_incentive=array_sum(array_column($inc_data, 'tat_amt'));

	$text=$searchFilters['year_text'].' - '.$searchFilters['duration_text'];
	$table_text = 'Part1 Incentives For ( '.$text.' )';
	$arr=array('results'=>$inc_data,'table_text'=>$table_text,'total_amount'=>indian_format_price($total_incentive),'product_category'=>$product_category);
	return json_encode($arr); 
}
function get_post_quarter_text($from_date,$to_date,$fy_id)
{
	$quarter=get_incentives_quarters($fy_id);
	foreach($quarter as $val)
    {
    	if($val['start_date']==$from_date && $val['end_date']==$to_date)
    	{
    		$post_quarter=$val['quarter'];
    	}
    	
    }
    return $post_quarter;
}
