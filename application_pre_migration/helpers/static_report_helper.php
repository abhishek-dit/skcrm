<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
function static_getTargetVsSalesChart1Data($searchFilters)
{   
	$CI=& get_instance();
	if($searchFilters['users']!='')
    {
    	$user_id=$searchFilters['users'];
    }
    else
    {
    	$user_id=$CI->session->userdata('user_id');
    }
	
	$role_id=getUserRole($user_id);
    if($user_id != $CI->session->userdata('user_id'))
	{
		$l = getUserLocations($user_id);
		$ul = getQueryArray($l);
		$up = getUserProducts($user_id);
		$userLocations = ($ul == '')? 0: $ul;
		$userProducts = ($up == '')? 0: $up;
	}
	else
	{
		$userLocations = ($CI->session->userdata('locationString') == '')? 0: $CI->session->userdata('locationString');
		$userProducts = ($CI->session->userdata('products') == '')? 0: $CI->session->userdata('products');
	}

	$month = date('m');
    $month1 = $month + 1;
    $year = date('Y');		
    $day = getOpportunityCategorizationDate();
    $hotDay = $year."-".$month."-".$day;
    $warmDate = date('Y-m-d',strtotime( $hotDay . " +1 month " ));

    if($searchFilters['zone']==1)//graph
    {
	    //fetching previous targets
	    $previous_target=$CI->Report_model->get_previous_target($searchFilters);

	    //fetching previous sales
	    $previous_sales=$CI->Report_model->get_previous_sales($searchFilters);
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

	    $xAxisCategory=array('Target' ,'Acheived','Pending','Funnel');
	    $year=get_current_fiancial_year();
	    if(@$searchFilters['duration_text']!='')
		{
			$dat= '('.@$searchFilters['duration_text'].' )';
		}
		else
		{
			$dat=$year['name'];
		}
		$text = @$location.'Target Vs Sales '.$dat;

		//$text='Target Vs Sales';
		$series = array();
		
		
		if($searchFilters['vtime']=='y')
		{
			$series[] = array('name'=>'Hot','data'=>array('','','',10));
			$series[] = array('name'=>'Warm','data'=>array('','','',15));
			$series[] = array('name'=>'Cold','data'=>array('','','',20));
			$series[] = array('name'=>'Pending','data'=>array('','',15,''));
			$series[] = array('name'=>'Current Sales','data'=>array('',5,'',''));
			$series[] = array('name'=>'Open Orders','data'=>array('',10,'',''));
			/*$series[] = array('name'=>'Cumulative Target','data'=>array('','',(int)$cumulative_target,'','','',''));*/
			$series[] = array('name'=>'Current Target','data'=>array(30,'','',''));
			//$series[] = array('name'=>'BackLog','data'=>array(5,'','',''));
		}
		if($searchFilters['vtime']=='q')
		{
			if($searchFilters['duration']=='2017-10-02to2017-12-31')
			{
				$series[] = array('name'=>'Hot','data'=>array('','','',8));
				$series[] = array('name'=>'Warm','data'=>array('','','',11));
				$series[] = array('name'=>'Cold','data'=>array('','','',13));
				$series[] = array('name'=>'Pending','data'=>array('','',4,''));
				$series[] = array('name'=>'Current Sales','data'=>array('',3,'',''));
				$series[] = array('name'=>'Open Orders','data'=>array('',3,'',''));
				/*$series[] = array('name'=>'Cumulative Target','data'=>array('','',(int)$cumulative_target,'','','',''));*/
				$series[] = array('name'=>'Current Target','data'=>array(7,'','',''));
				$series[] = array('name'=>'BackLog','data'=>array(3,'','',''));
			}
			if($searchFilters['duration']=='2017-07-03to2017-10-01')
			{
				$series[] = array('name'=>'Hot','data'=>array('','','',7));
				$series[] = array('name'=>'Warm','data'=>array('','','',9));
				$series[] = array('name'=>'Cold','data'=>array('','','',10));
				$series[] = array('name'=>'Pending','data'=>array('','',3,''));
				$series[] = array('name'=>'Current Sales','data'=>array('',5,'',''));
				$series[] = array('name'=>'Open Orders','data'=>array('',3,'',''));
				/*$series[] = array('name'=>'Cumulative Target','data'=>array('','',(int)$cumulative_target,'','','',''));*/
				$series[] = array('name'=>'Current Target','data'=>array(7,'','',''));
				$series[] = array('name'=>'BackLog','data'=>array(4,'','',''));
			}
			if($searchFilters['duration']=='2017-04-03to2017-07-02')
			{
				$series[] = array('name'=>'Hot','data'=>array('','','',2));
				$series[] = array('name'=>'Warm','data'=>array('','','',5));
				$series[] = array('name'=>'Cold','data'=>array('','','',7));
				$series[] = array('name'=>'Pending','data'=>array('','',4,''));
				$series[] = array('name'=>'Current Sales','data'=>array('',1,'',''));
				$series[] = array('name'=>'Open Orders','data'=>array('',2,'',''));
				/*$series[] = array('name'=>'Cumulative Target','data'=>array('','',(int)$cumulative_target,'','','',''));*/
				$series[] = array('name'=>'Current Target','data'=>array(7,'','',''));
				$series[] = array('name'=>'BackLog','data'=>array(0,'','',''));
			}
			
		}
		if($searchFilters['vtime']=='m')
		{

			if($searchFilters['duration_text']=='Nov-17')
			{
				$series[] = array('name'=>'Hot','data'=>array('','','',2));
				$series[] = array('name'=>'Warm','data'=>array('','','',5));
				$series[] = array('name'=>'Cold','data'=>array('','','',7));
				$series[] = array('name'=>'Pending','data'=>array('','',1,''));
				$series[] = array('name'=>'Current Sales','data'=>array('',1,'',''));
				$series[] = array('name'=>'Open Orders','data'=>array('',1,'',''));
				/*$series[] = array('name'=>'Cumulative Target','data'=>array('','',(int)$cumulative_target,'','','',''));*/
				$series[] = array('name'=>'Current Target','data'=>array(3,'','',''));
				$series[] = array('name'=>'BackLog','data'=>array(0,'','',''));
			}
			if($searchFilters['duration_text']=='Oct-17')
			{
				$series[] = array('name'=>'Hot','data'=>array('','','',2));
				$series[] = array('name'=>'Warm','data'=>array('','','',5));
				$series[] = array('name'=>'Cold','data'=>array('','','',7));
				$series[] = array('name'=>'Pending','data'=>array('','',0,''));
				$series[] = array('name'=>'Current Sales','data'=>array('',1,'',''));
				$series[] = array('name'=>'Open Orders','data'=>array('',2,'',''));
				/*$series[] = array('name'=>'Cumulative Target','data'=>array('','',(int)$cumulative_target,'','','',''));*/
				$series[] = array('name'=>'Current Target','data'=>array(2,'','',''));
				$series[] = array('name'=>'BackLog','data'=>array(1,'','',''));
			}
			if($searchFilters['duration_text']=='Sep-17')
			{
				$series[] = array('name'=>'Hot','data'=>array('','','',2));
				$series[] = array('name'=>'Warm','data'=>array('','','',5));
				$series[] = array('name'=>'Cold','data'=>array('','','',7));
				$series[] = array('name'=>'Pending','data'=>array('','',1,''));
				$series[] = array('name'=>'Current Sales','data'=>array('',1,'',''));
				$series[] = array('name'=>'Open Orders','data'=>array('',0,'',''));
				/*$series[] = array('name'=>'Cumulative Target','data'=>array('','',(int)$cumulative_target,'','','',''));*/
				$series[] = array('name'=>'Current Target','data'=>array(1,'','',''));
				$series[] = array('name'=>'BackLog','data'=>array(1,'','',''));
			}
			if($searchFilters['duration_text']=='Aug-17')
			{
				$series[] = array('name'=>'Hot','data'=>array('','','',2));
				$series[] = array('name'=>'Warm','data'=>array('','','',5));
				$series[] = array('name'=>'Cold','data'=>array('','','',7));
				$series[] = array('name'=>'Pending','data'=>array('','',1,''));
				$series[] = array('name'=>'Current Sales','data'=>array('',1,'',''));
				$series[] = array('name'=>'Open Orders','data'=>array('',1,'',''));
				/*$series[] = array('name'=>'Cumulative Target','data'=>array('','',(int)$cumulative_target,'','','',''));*/
				$series[] = array('name'=>'Current Target','data'=>array(2,'','',''));
				$series[] = array('name'=>'BackLog','data'=>array(1,'','',''));
			}
			if($searchFilters['duration_text']=='Jul-17')
			{
				$series[] = array('name'=>'Hot','data'=>array('','','',2));
				$series[] = array('name'=>'Warm','data'=>array('','','',5));
				$series[] = array('name'=>'Cold','data'=>array('','','',7));
				$series[] = array('name'=>'Pending','data'=>array('','',1,''));
				$series[] = array('name'=>'Current Sales','data'=>array('',2,'',''));
				$series[] = array('name'=>'Open Orders','data'=>array('',3,'',''));
				/*$series[] = array('name'=>'Cumulative Target','data'=>array('','',(int)$cumulative_target,'','','',''));*/
				$series[] = array('name'=>'Current Target','data'=>array(4,'','',''));
				$series[] = array('name'=>'BackLog','data'=>array(2,'','',''));
			}
			if($searchFilters['duration_text']=='Jun-17')
			{
				$series[] = array('name'=>'Hot','data'=>array('','','',2));
				$series[] = array('name'=>'Warm','data'=>array('','','',5));
				$series[] = array('name'=>'Cold','data'=>array('','','',7));
				$series[] = array('name'=>'Pending','data'=>array('','',2,''));
				$series[] = array('name'=>'Current Sales','data'=>array('',1,'',''));
				$series[] = array('name'=>'Open Orders','data'=>array('',0,'',''));
				/*$series[] = array('name'=>'Cumulative Target','data'=>array('','',(int)$cumulative_target,'','','',''));*/
				$series[] = array('name'=>'Current Target','data'=>array(2,'','',''));
				$series[] = array('name'=>'BackLog','data'=>array(1,'','',''));
			}
			if($searchFilters['duration_text']=='May-17')
			{
				$series[] = array('name'=>'Hot','data'=>array('','','',2));
				$series[] = array('name'=>'Warm','data'=>array('','','',5));
				$series[] = array('name'=>'Cold','data'=>array('','','',7));
				$series[] = array('name'=>'Pending','data'=>array('','',1,''));
				$series[] = array('name'=>'Current Sales','data'=>array('',1,'',''));
				$series[] = array('name'=>'Open Orders','data'=>array('',0,'',''));
				/*$series[] = array('name'=>'Cumulative Target','data'=>array('','',(int)$cumulative_target,'','','',''));*/
				$series[] = array('name'=>'Current Target','data'=>array(2,'','',''));
				$series[] = array('name'=>'BackLog','data'=>array(0,'','',''));
			}
			if($searchFilters['duration_text']=='Apr-17')
			{
				$series[] = array('name'=>'Hot','data'=>array('','','',2));
				$series[] = array('name'=>'Warm','data'=>array('','','',5));
				$series[] = array('name'=>'Cold','data'=>array('','','',7));
				$series[] = array('name'=>'Pending','data'=>array('','',0,''));
				$series[] = array('name'=>'Current Sales','data'=>array('',1,'',''));
				$series[] = array('name'=>'Open Orders','data'=>array('',2,'',''));
				/*$series[] = array('name'=>'Cumulative Target','data'=>array('','',(int)$cumulative_target,'','','',''));*/
				$series[] = array('name'=>'Current Target','data'=>array(3,'','',''));
				$series[] = array('name'=>'BackLog','data'=>array(0,'','',''));
			}
		}
		if($searchFilters['vtime']=='w')
		{
			
			if($searchFilters['duration']=='2017-11-27to2017-12-03')
			{
				$series[] = array('name'=>'Hot','data'=>array('','','',1));
				$series[] = array('name'=>'Warm','data'=>array('','','',3));
				$series[] = array('name'=>'Cold','data'=>array('','','',4));
				$series[] = array('name'=>'Pending','data'=>array('','',1,''));
				$series[] = array('name'=>'Current Sales','data'=>array('',0,'',''));
				$series[] = array('name'=>'Open Orders','data'=>array('',1,'',''));
				/*$series[] = array('name'=>'Cumulative Target','data'=>array('','',(int)$cumulative_target,'','','',''));*/
				$series[] = array('name'=>'Current Target','data'=>array(1,'','',''));
				$series[] = array('name'=>'BackLog','data'=>array(1,'','',''));
			}
			elseif($searchFilters['duration']=='2017-11-20to2017-11-26')
			{
				$series[] = array('name'=>'Hot','data'=>array('','','',1));
				$series[] = array('name'=>'Warm','data'=>array('','','',3));
				$series[] = array('name'=>'Cold','data'=>array('','','',4));
				$series[] = array('name'=>'Pending','data'=>array('','',1,''));
				$series[] = array('name'=>'Current Sales','data'=>array('',0,'',''));
				$series[] = array('name'=>'Open Orders','data'=>array('',1,'',''));
				/*$series[] = array('name'=>'Cumulative Target','data'=>array('','',(int)$cumulative_target,'','','',''));*/
				$series[] = array('name'=>'Current Target','data'=>array(0,'','',''));
				$series[] = array('name'=>'BackLog','data'=>array(2,'','',''));
			}
			elseif($searchFilters['duration']=='2017-11-13to2017-11-19')
			{
				$series[] = array('name'=>'Hot','data'=>array('','','',1));
				$series[] = array('name'=>'Warm','data'=>array('','','',3));
				$series[] = array('name'=>'Cold','data'=>array('','','',4));
				$series[] = array('name'=>'Pending','data'=>array('','',2,''));
				$series[] = array('name'=>'Current Sales','data'=>array('',0,'',''));
				$series[] = array('name'=>'Open Orders','data'=>array('',0,'',''));
				/*$series[] = array('name'=>'Cumulative Target','data'=>array('','',(int)$cumulative_target,'','','',''));*/
				$series[] = array('name'=>'Current Target','data'=>array(1,'','',''));
				$series[] = array('name'=>'BackLog','data'=>array(1,'','',''));
			}
			elseif($searchFilters['duration']=='2017-11-06to2017-11-12')
			{
				$series[] = array('name'=>'Hot','data'=>array('','','',1));
				$series[] = array('name'=>'Warm','data'=>array('','','',3));
				$series[] = array('name'=>'Cold','data'=>array('','','',4));
				$series[] = array('name'=>'Pending','data'=>array('','',1,''));
				$series[] = array('name'=>'Current Sales','data'=>array('',1,'',''));
				$series[] = array('name'=>'Open Orders','data'=>array('',0,'',''));
				/*$series[] = array('name'=>'Cumulative Target','data'=>array('','',(int)$cumulative_target,'','','',''));*/
				$series[] = array('name'=>'Current Target','data'=>array(1,'','',''));
				$series[] = array('name'=>'BackLog','data'=>array(1,'','',''));
			}
		}
		if($searchFilters['measure']==2)
		{
			$y_axis_lable='By Value';
		}
		elseif($searchFilters['measure']==1)
		{
			$y_axis_lable='By Quantity';
		}
		/*$series[] = array('name'=>'Previous Sales','data'=>array('',(int)$previous_sales,'','','','',''));
		$series[] = array('name'=>'Previous Target','data'=>array((int)$previous_target['previous_target'],'','','','','',''));*/
	    //echo json_encode($series);exit;
		$chart1Data = array('xAxisCategory'=>$xAxisCategory,'chart1series'=>$series,'xAxisLable'=>$text,'y_axis_lable'=>$y_axis_lable);
		$chart1Data = json_encode($chart1Data);
		
		return $chart1Data;
	}
	else if($searchFilters['zone']==2)//table
	{   
		if($searchFilters['groups']==1)
		{  
		   //previous target
		   $previous_target_category=$CI->Report_model->get_previous_target_by_category_table($searchFilters);
		 //  echo '<pre>'; print_r($previous_target_category);exit;
		   foreach ($previous_target_category as $key => $value) 
		   { 
		   		if($value['previous_target']>0)
		   		{
		   			$category_id = $value['category_id'];
			   		$group_id = '';
			   		$product_id = '';
			   		$previous_sales =$CI->Report_model->get_previous_sales_category($searchFilters,$category_id,$group_id,$product_id);

			   		$current_target = $CI->Report_model->get_current_target_category($searchFilters,$category_id,$group_id,$product_id);

			   		$current_sales = $CI->Report_model->get_current_sales_category($searchFilters,$category_id,$group_id,$product_id);
			   		$open_orders =  $CI->Report_model->get_open_orders_category($searchFilters,$category_id,$group_id,$product_id);
			   		$funnel_open_opp =$CI->Report_model->get_open_opportunity_category($user_id, $role_id, $userProducts, $userLocations, $hotDay, $warmDate,$searchFilters,$category_id,$group_id,$product_id);

			   		$backlog = $value['previous_target']-$previous_sales;
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
			   		$segment_list = $CI->Report_model->get_previous_target_by_segment_table($searchFilters,$value['category_id']);
			   		foreach ($segment_list as $key1 => $segment1) 
			   		{
			   			if($segment1['previous_target']>0)
			   			{
			   				$previous_target_category[$key]['segment_list'][$key1] = $segment1;

			   				$category_id = '';
					   		$group_id = $segment1['group_id'];
					   		$product_id = '';
					   		$previous_sales2 =$CI->Report_model->get_previous_sales_category($searchFilters,$category_id,$group_id,$product_id);

					   		$current_target = $CI->Report_model->get_current_target_category($searchFilters,$category_id,$group_id,$product_id);
					   		$current_sales = $CI->Report_model->get_current_sales_category($searchFilters,$category_id,$group_id,$product_id);
					   		$open_orders =  $CI->Report_model->get_open_orders_category($searchFilters,$category_id,$group_id,$product_id);
					   		$funnel_open_opp =$CI->Report_model->get_open_opportunity_category($user_id, $role_id, $userProducts, $userLocations, $hotDay, $warmDate,$searchFilters,$category_id,$group_id,$product_id);

					   		$backlog = $segment1['previous_target']-$previous_sales2;
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
			   			}
			   		}
		   		}
		   	}
		   foreach ($previous_target_category as $key => $data1) 
		   {
		   		if($data1['previous_target']>0)
		   		{
		   			foreach ($data1['segment_list'] as $key1 => $value) 
			   		{
			   			$data2 = $CI->Report_model->get_previous_target_by_product_table($searchFilters,$value['group_id']);
		   				foreach ($data2 as $key2 => $value2) 
		   				{
		   					if($value2['previous_target']>0)
		   					{
		   						$previous_target_category[$key]['segment_list'][$key1]['product_list'][$key2] = $value2;

			   					$category_id = '';
						   		$group_id = '';
						   		$product_id = $value2['product_id'];
						   		$previous_sales3 =$CI->Report_model->get_previous_sales_category($searchFilters,$category_id,$group_id,$product_id);
						   		$current_target = $CI->Report_model->get_current_target_category($searchFilters,$category_id,$group_id,$product_id);
						   		$current_sales = $CI->Report_model->get_current_sales_category($searchFilters,$category_id,$group_id,$product_id);
						   		$open_orders =  $CI->Report_model->get_open_orders_category($searchFilters,$category_id,$group_id,$product_id);
						   		$funnel_open_opp =$CI->Report_model->get_open_opportunity_category($user_id, $role_id, $userProducts, $userLocations, $hotDay, $warmDate,$searchFilters,$category_id,$group_id,$product_id);

						   		$backlog = $value2['previous_target']-$previous_sales3;
						   		if($backlog<0) { $backlog = 0;}
						   		$cumm_target = $backlog+$current_target;
						   		$pending = ($backlog+$current_target)-$current_sales-$open_orders;
						   		if($pending<0) { $pending = 0;}
						   		$previous_target_category[$key]['segment_list'][$key1]['product_list'][$key2]['backlog'] = $backlog;
								$previous_target_category[$key]['segment_list'][$key1]['product_list'][$key2]['previous_sales'] = $previous_sales3;
						   		
						   		$previous_target_category[$key]['segment_list'][$key1]['product_list'][$key2]['current_target'] = $current_target;
						   		$previous_target_category[$key]['segment_list'][$key1]['product_list'][$key2]['cumm_target'] = $cumm_target;
						   		$previous_target_category[$key]['segment_list'][$key1]['product_list'][$key2]['current_sales'] = $current_sales;
						   		$previous_target_category[$key]['segment_list'][$key1]['product_list'][$key2]['open_orders'] = $open_orders;
								$previous_target_category[$key]['segment_list'][$key1]['product_list'][$key2]['funnel_open_opp_hot'] = $funnel_open_opp['Hot'];
								$previous_target_category[$key]['segment_list'][$key1]['product_list'][$key2]['funnel_open_opp_warm'] = $funnel_open_opp['Warm'];
								$previous_target_category[$key]['segment_list'][$key1]['product_list'][$key2]['funnel_open_opp_cold'] = $funnel_open_opp['Cold'];
								$previous_target_category[$key]['segment_list'][$key1]['product_list'][$key2]['pending'] = $pending;
		   					}
		   				}
			   		}
		   		}
		   }
		   //print_r($previous_target_category);exit;
		   return $previous_target_category;
		}
		else if($searchFilters['groups']==2)
		{
			$location_wise = $CI->Common_model->get_data('location',array('territory_level_id'=>4,'status'=>1));

			//fetching previous target by region wise
			$prev_target_arr=array();
			foreach($location_wise as $loc) 
			{
				$region_users= report_user_locations_by_region($loc['location_id']);
				$prev = $CI->Report_model->get_previous_target_by_region($searchFilters,$region_users);
				$prev_target_arr[$loc['location']]= $prev['previous_target'];
			}
			$location_wise = $CI->Common_model->get_data('location',array('territory_level_id'=>4,'status'=>1));

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
            if(count($prev_target_arr)>0)
            {
            	$i = 0;
            	foreach ($prev_target_arr as $key => $value) 
            	{
            		if($value !=''){ $previous_target = $value;}
            		else{ $previous_target = 0;}

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
function static_get_funnel_chart1($searchFilters)
{   
	//print_r($searchFilters);exit;
	$CI=& get_instance();
	$xAxisCategory=array();
	if($searchFilters['users']!='')
    {
    	$user_id=$searchFilters['users'];
    }
    else
    {
    	$user_id=$CI->session->userdata('user_id');
    }
	
	$role_id=getUserRole($user_id);
    if($user_id != $CI->session->userdata('user_id'))
	{
		$l = getUserLocations($user_id);
		$ul = getQueryArray($l);
		$up = getUserProducts($user_id);
		$userLocations = ($ul == '')? 0: $ul;
		$userProducts = ($up == '')? 0: $up;
	}
	else
	{
		$userLocations = ($CI->session->userdata('locationString') == '')? 0: $CI->session->userdata('locationString');
		$userProducts = ($CI->session->userdata('products') == '')? 0: $CI->session->userdata('products');
	}

	$month = date('m');
    $month1 = $month + 1;
    $year = date('Y');		
    $day = getOpportunityCategorizationDate();
    $hotDay = $year."-".$month."-".$day;
    $warmDate = date('Y-m-d',strtotime( $hotDay . " +1 month " ));
	$fo_before_date=array('Hot'=>588,'Warm'=>320,'Cold'=>100);
	if($searchFilters['vtime']=='m')
	{
		$months=$searchFilters['duration'];
		$month_arr=explode('to', $months);
		$month_number=$month_arr[0];
		$year=$month_arr[1];
		$curdate=date('Y-m-d');
		$kk=get_month_no_by_date($curdate);
		if($month_number==$kk['month_no'])
		{
			$fo_opened_status=array(
						array('opened_value'=>200,'timeline'=>'Week1 (2017-11-06 to 2017-11-12)'),
						array('opened_value'=>150,'timeline'=>'Week2 (2017-11-13 to 2017-11-19)'),
						array('opened_value'=>177,'timeline'=>'Week3 (2017-11-20 to 2017-11-26)'),
						array('opened_value'=>200,'timeline'=>'Week4 (2017-11-27 to 2017-12-03)')
						/*array('opened_value'=>250,'timeline'=>'Jul-17'),
						array('opened_value'=>330,'timeline'=>'Aug-17'),
						array('opened_value'=>240,'timeline'=>'Sep-17'),
						array('opened_value'=>140,'timeline'=>'Oct-17'),
						array('opened_value'=>170,'timeline'=>'Nov-17')*/
						);
			$fo_closed_status=array(
						array('closed_value'=>50,'timeline'=>'Week1 (2017-11-06 to 2017-11-12)'),
						array('closed_value'=>95,'timeline'=>'Week2 (2017-11-13 to 2017-11-19)'),
						array('closed_value'=>90,'timeline'=>'Week3 (2017-11-20 to 2017-11-26)'),
						array('closed_value'=>100,'timeline'=>'Week4 (2017-11-27 to 2017-12-03)')
						/*array('closed_value'=>140,'timeline'=>'Jul-17'),
						array('closed_value'=>120,'timeline'=>'Aug-17'),
						array('closed_value'=>130,'timeline'=>'Sep-17'),
						array('closed_value'=>100,'timeline'=>'Oct-17'),
						array('closed_value'=>90,'timeline'=>'Nov-17')*/
						);
		}
		elseif($month_number==10)
		{
			$fo_opened_status=array(
						array('opened_value'=>326,'timeline'=>'Week1 (2017-10-02 to 2017-10-08)'),
						array('opened_value'=>250,'timeline'=>'Week2 (2017-10-09 to 2017-10-15)'),
						array('opened_value'=>250,'timeline'=>'Week3 (2017-10-16 to 2017-10-22)'),
						array('opened_value'=>150,'timeline'=>'Week4 (2017-10-23 to 2017-10-29)'),
						array('opened_value'=>230,'timeline'=>'Week5 (2017-10-30 to 2017-11-05)'),
						/*array('opened_value'=>340,'timeline'=>'Sep-17'),
						array('opened_value'=>240,'timeline'=>'Oct-17'),
						array('opened_value'=>270,'timeline'=>'Nov-17')*/
						);
			$fo_closed_status=array(
						array('closed_value'=>150,'timeline'=>'Week1 (2017-10-02 to 2017-10-08)'),
						array('closed_value'=>95,'timeline'=>'Week2 (2017-10-09 to 2017-10-15)'),
						array('closed_value'=>109,'timeline'=>'Week3 (2017-10-16 to 2017-10-22)'),
						array('closed_value'=>240,'timeline'=>'Week4 (2017-10-23 to 2017-10-29)'),
						array('closed_value'=>220,'timeline'=>'Week5 (2017-10-30 to 2017-11-05)'),
						/*array('closed_value'=>100,'timeline'=>'Sep-17'),
						array('closed_value'=>100,'timeline'=>'Oct-17'),
						array('closed_value'=>90,'timeline'=>'Nov-17')*/
						);
		}
		elseif($month_number==9)
		{
			$fo_opened_status=array(
						array('opened_value'=>487,'timeline'=>'Week1 (2017-09-04 to 2017-09-10)'),
						array('opened_value'=>350,'timeline'=>'Week2 (2017-09-11 to 2017-09-17)'),
						array('opened_value'=>330,'timeline'=>'Week3 (2017-09-18 to 2017-09-24)'),
						array('opened_value'=>200,'timeline'=>'Week4 (2017-09-25 to 2017-10-01)'),
						/*array('opened_value'=>530,'timeline'=>'Aug-17'),
						array('opened_value'=>440,'timeline'=>'Sep-17'),
						array('opened_value'=>340,'timeline'=>'Oct-17'),
						array('opened_value'=>270,'timeline'=>'Nov-17')*/
						);
			$fo_closed_status=array(
						array('closed_value'=>350,'timeline'=>'Week1 (2017-09-04 to 2017-09-10)'),
						array('closed_value'=>195,'timeline'=>'Week2 (2017-09-11 to 2017-09-17)'),
						array('closed_value'=>190,'timeline'=>'Week3 (2017-09-18 to 2017-09-24)'),
						array('closed_value'=>240,'timeline'=>'Week4 (2017-09-25 to 2017-10-01)'),
						/*array('closed_value'=>320,'timeline'=>'Aug-17'),
						array('closed_value'=>230,'timeline'=>'Sep-17'),
						array('closed_value'=>150,'timeline'=>'Oct-17'),
						array('closed_value'=>190,'timeline'=>'Nov-17')*/
						);
		}
		elseif($month_number==8)
		{
			$fo_opened_status=array(
						array('opened_value'=>100,'timeline'=>'Week1 (2017-08-07 to 2017-08-13)'),
						array('opened_value'=>150,'timeline'=>'Week2 (2017-08-14 to 2017-08-20)'),
						array('opened_value'=>250,'timeline'=>'Week3 (2017-08-21 to 2017-08-27)'),
						array('opened_value'=>347,'timeline'=>'Week4 (2017-08-28 to 2017-09-03)'),
						/*array('opened_value'=>130,'timeline'=>'Aug-17'),
						array('opened_value'=>240,'timeline'=>'Sep-17'),
						array('opened_value'=>150,'timeline'=>'Oct-17'),
						array('opened_value'=>190,'timeline'=>'Nov-17')*/
						);
			$fo_closed_status=array(
						array('closed_value'=>50,'timeline'=>'Week1 (2017-08-07 to 2017-08-13)'),
						array('closed_value'=>75,'timeline'=>'Week2 (2017-08-14 to 2017-08-20)'),
						array('closed_value'=>190,'timeline'=>'Week3 (2017-08-21 to 2017-08-27)'),
						array('closed_value'=>140,'timeline'=>'Week4 (2017-08-28 to 2017-09-03)'),
						/*array('closed_value'=>100,'timeline'=>'Aug-17'),
						array('closed_value'=>150,'timeline'=>'Sep-17'),
						array('closed_value'=>90,'timeline'=>'Oct-17'),
						array('closed_value'=>90,'timeline'=>'Nov-17')*/
						);
		}
		elseif($month_number==7)
		{
			$fo_opened_status=array(
						array('opened_value'=>100,'timeline'=>'Week1 (2017-07-03 to 2017-07-09)'),
						array('opened_value'=>150,'timeline'=>'Week2 (2017-07-10 to 2017-07-16)'),
						array('opened_value'=>250,'timeline'=>'Week3 (2017-07-17 to 2017-07-23)'),
						array('opened_value'=>347,'timeline'=>'Week4 (2017-07-24 to 2017-07-30)'),
						array('opened_value'=>140,'timeline'=>'Week5 (2017-07-31 to 2017-08-06)'),
						/*array('opened_value'=>130,'timeline'=>'Aug-17'),
						array('opened_value'=>240,'timeline'=>'Sep-17'),
						array('opened_value'=>150,'timeline'=>'Oct-17'),
						array('opened_value'=>190,'timeline'=>'Nov-17')*/
						);
			$fo_closed_status=array(
						array('closed_value'=>50,'timeline'=>'Week1 (2017-07-03 to 2017-07-09)'),
						array('closed_value'=>75,'timeline'=>'Week2 (2017-07-10 to 2017-07-16)'),
						array('closed_value'=>190,'timeline'=>'Week3 (2017-07-17 to 2017-07-23)'),
						array('closed_value'=>140,'timeline'=>'Week4 (2017-07-24 to 2017-07-30)'),
						array('closed_value'=>140,'timeline'=>'Week5 (2017-07-31 to 2017-08-06)'),
						/*array('closed_value'=>100,'timeline'=>'Aug-17'),
						array('closed_value'=>150,'timeline'=>'Sep-17'),
						array('closed_value'=>90,'timeline'=>'Oct-17'),
						array('closed_value'=>90,'timeline'=>'Nov-17')*/
						);
		}
		elseif($month_number==6)
		{
			$fo_opened_status=array(
						array('opened_value'=>100,'timeline'=>'Week1 (2017-06-05 to 2017-06-11)'),
						array('opened_value'=>150,'timeline'=>'Week2 (2017-06-12 to 2017-06-18)'),
						array('opened_value'=>250,'timeline'=>'Week3 (2017-06-19 to 2017-06-25)'),
						array('opened_value'=>347,'timeline'=>'Week4 (2017-06-26 to 2017-07-02)'),
						/*array('opened_value'=>130,'timeline'=>'Aug-17'),
						array('opened_value'=>240,'timeline'=>'Sep-17'),
						array('opened_value'=>150,'timeline'=>'Oct-17'),
						array('opened_value'=>190,'timeline'=>'Nov-17')*/
						);
			$fo_closed_status=array(
						array('closed_value'=>50,'timeline'=>'Week1 (2017-06-05 to 2017-06-11)'),
						array('closed_value'=>75,'timeline'=>'Week2 (2017-06-12 to 2017-06-18)'),
						array('closed_value'=>190,'timeline'=>'Week3 (2017-06-19 to 2017-06-25)'),
						array('closed_value'=>140,'timeline'=>'Week4 (2017-06-26 to 2017-07-02)'),
						/*array('closed_value'=>100,'timeline'=>'Aug-17'),
						array('closed_value'=>150,'timeline'=>'Sep-17'),
						array('closed_value'=>90,'timeline'=>'Oct-17'),
						array('closed_value'=>90,'timeline'=>'Nov-17')*/
						);
		}
		elseif($month_number==5)
		{
			$fo_opened_status=array(
						array('opened_value'=>100,'timeline'=>'Week1 (2017-05-01 to 2017-05-07)'),
						array('opened_value'=>150,'timeline'=>'Week2 (2017-05-08 to 2017-05-14)'),
						array('opened_value'=>250,'timeline'=>'Week3 (2017-05-15 to 2017-05-21)'),
						array('opened_value'=>347,'timeline'=>'Week4 (2017-05-22 to 2017-05-28)'),
						array('opened_value'=>140,'timeline'=>'Week5 (2017-05-29 to 2017-06-04)'),
						/*array('opened_value'=>130,'timeline'=>'Aug-17'),
						array('opened_value'=>240,'timeline'=>'Sep-17'),
						array('opened_value'=>150,'timeline'=>'Oct-17'),
						array('opened_value'=>190,'timeline'=>'Nov-17')*/
						);
			$fo_closed_status=array(
						array('closed_value'=>50,'timeline'=>'Week1 (2017-05-01 to 2017-05-07)'),
						array('closed_value'=>75,'timeline'=>'Week2 (2017-05-08 to 2017-05-14)'),
						array('closed_value'=>190,'timeline'=>'Week3 (2017-05-15 to 2017-05-21)'),
						array('closed_value'=>140,'timeline'=>'Week4 (2017-05-22 to 2017-05-28)'),
						array('closed_value'=>140,'timeline'=>'Week5 (2017-05-29 to 2017-06-04)'),
						/*array('closed_value'=>100,'timeline'=>'Aug-17'),
						array('closed_value'=>150,'timeline'=>'Sep-17'),
						array('closed_value'=>90,'timeline'=>'Oct-17'),
						array('closed_value'=>90,'timeline'=>'Nov-17')*/
						);
		}
		elseif($month_number==4)
		{
			$fo_opened_status=array(
						array('opened_value'=>100,'timeline'=>'Week1 (2017-04-03 to 2017-04-09)'),
						array('opened_value'=>150,'timeline'=>'Week2 (2017-04-10 to 2017-04-16)'),
						array('opened_value'=>250,'timeline'=>'Week3 (2017-04-17 to 2017-04-23)'),
						array('opened_value'=>347,'timeline'=>'Week4 (2017-04-24 to 2017-04-30)'),
						/*array('opened_value'=>130,'timeline'=>'Aug-17'),
						array('opened_value'=>240,'timeline'=>'Sep-17'),
						array('opened_value'=>150,'timeline'=>'Oct-17'),
						array('opened_value'=>190,'timeline'=>'Nov-17')*/
						);
			$fo_closed_status=array(
						array('closed_value'=>50,'timeline'=>'Week1 (2017-04-03 to 2017-04-09)'),
						array('closed_value'=>75,'timeline'=>'Week2 (2017-04-10 to 2017-04-16)'),
						array('closed_value'=>190,'timeline'=>'Week3 (2017-04-17 to 2017-04-23)'),
						array('closed_value'=>140,'timeline'=>'Week4 (2017-04-24 to 2017-04-30)'),
						/*array('closed_value'=>100,'timeline'=>'Aug-17'),
						array('closed_value'=>150,'timeline'=>'Sep-17'),
						array('closed_value'=>90,'timeline'=>'Oct-17'),
						array('closed_value'=>90,'timeline'=>'Nov-17')*/
						);
		}
	}
	if($searchFilters['vtime']=='y')
	{
		$fo_opened_status=array(
						array('opened_value'=>100,'timeline'=>'Apr-17'),
						array('opened_value'=>150,'timeline'=>'May-17'),
						array('opened_value'=>120,'timeline'=>'Jun-17'),
						array('opened_value'=>197,'timeline'=>'Jul-17'),
						array('opened_value'=>130,'timeline'=>'Aug-17'),
						array('opened_value'=>240,'timeline'=>'Sep-17'),
						array('opened_value'=>150,'timeline'=>'Oct-17'),
						array('opened_value'=>190,'timeline'=>'Nov-17')
						);
		$fo_closed_status=array(
						array('closed_value'=>50,'timeline'=>'Apr-17'),
						array('closed_value'=>75,'timeline'=>'May-17'),
						array('closed_value'=>190,'timeline'=>'Jun-17'),
						array('closed_value'=>140,'timeline'=>'Jul-17'),
						array('closed_value'=>100,'timeline'=>'Aug-17'),
						array('closed_value'=>150,'timeline'=>'Sep-17'),
						array('closed_value'=>90,'timeline'=>'Oct-17'),
						array('closed_value'=>90,'timeline'=>'Nov-17')
						);
	}
	if($searchFilters['vtime']=='w')
	{
		$quarter=$searchFilters['duration'];
		$quat_arr=explode('to', $quarter);
		$start_date=$quat_arr[0];
		$end_date=$quat_arr[1];
		if($searchFilters['duration']=='2017-11-27to2017-12-03')
		{
			$fo_opened_status=array(
							array('opened_value'=>257,'timeline'=>'27Nov'),
							array('opened_value'=>100,'timeline'=>'28Nov'),
							array('opened_value'=>100,'timeline'=>'29Nov'),
							array('opened_value'=>120,'timeline'=>'30Nov'),
							array('opened_value'=>230,'timeline'=>'1Dec'),
							/*array('opened_value'=>240,'timeline'=>'18Nov'),
							array('opened_value'=>250,'timeline'=>'19Nov')*/
							/*array('opened_value'=>190,'timeline'=>'Nov-17')*/
							);
			$fo_closed_status=array(
							array('closed_value'=>80,'timeline'=>'27Nov'),
							array('closed_value'=>75,'timeline'=>'28Nov'),
							array('closed_value'=>100,'timeline'=>'29Nov'),
							array('closed_value'=>60,'timeline'=>'30Nov'),
							array('closed_value'=>100,'timeline'=>'1Dec'),
							/*array('closed_value'=>150,'timeline'=>'18Nov'),
							array('closed_value'=>90,'timeline'=>'19Nov'),*/
							/*array('closed_value'=>90,'timeline'=>'Nov-17')*/
							);
		}
		elseif($searchFilters['duration']=='2017-11-20to2017-11-26')
		{
			$fo_opened_status=array(
							array('opened_value'=>100,'timeline'=>'20Nov'),
							array('opened_value'=>150,'timeline'=>'21Nov'),
							array('opened_value'=>120,'timeline'=>'22Nov'),
							array('opened_value'=>197,'timeline'=>'23Nov'),
							array('opened_value'=>130,'timeline'=>'24Nov'),
							array('opened_value'=>240,'timeline'=>'25Nov'),
							array('opened_value'=>250,'timeline'=>'26Nov'),
							/*array('opened_value'=>190,'timeline'=>'Nov-17')*/
							);
			$fo_closed_status=array(
							array('closed_value'=>50,'timeline'=>'20Nov'),
							array('closed_value'=>75,'timeline'=>'21Nov'),
							array('closed_value'=>190,'timeline'=>'22Nov'),
							array('closed_value'=>140,'timeline'=>'23Nov'),
							array('closed_value'=>100,'timeline'=>'24Nov'),
							array('closed_value'=>150,'timeline'=>'25Nov'),
							array('closed_value'=>90,'timeline'=>'26Nov'),
							/*array('closed_value'=>90,'timeline'=>'Nov-17')*/
							);
		}

		elseif($searchFilters['duration']=='2017-11-13to2017-11-19')
		{
			$fo_opened_status=array(
							array('opened_value'=>100,'timeline'=>'13Nov'),
							array('opened_value'=>150,'timeline'=>'14Nov'),
							array('opened_value'=>120,'timeline'=>'15Nov'),
							array('opened_value'=>197,'timeline'=>'16Nov'),
							array('opened_value'=>130,'timeline'=>'17Nov'),
							array('opened_value'=>240,'timeline'=>'18Nov'),
							array('opened_value'=>250,'timeline'=>'19Nov')
							/*array('opened_value'=>190,'timeline'=>'Nov-17')*/
							);
			$fo_closed_status=array(
							array('closed_value'=>50,'timeline'=>'13Nov'),
							array('closed_value'=>75,'timeline'=>'14Nov'),
							array('closed_value'=>190,'timeline'=>'15Nov'),
							array('closed_value'=>140,'timeline'=>'16Nov'),
							array('closed_value'=>100,'timeline'=>'17Nov'),
							array('closed_value'=>150,'timeline'=>'18Nov'),
							array('closed_value'=>90,'timeline'=>'19Nov'),
							/*array('closed_value'=>90,'timeline'=>'Nov-17')*/
							);
		}
		elseif($searchFilters['duration']=='2017-11-06to2017-11-12')
		{
			$fo_opened_status=array(
							array('opened_value'=>100,'timeline'=>'6Nov'),
							array('opened_value'=>150,'timeline'=>'7Nov'),
							array('opened_value'=>120,'timeline'=>'8Nov'),
							array('opened_value'=>197,'timeline'=>'9Nov'),
							array('opened_value'=>130,'timeline'=>'10Nov'),
							array('opened_value'=>240,'timeline'=>'11Nov'),
							array('opened_value'=>250,'timeline'=>'12Nov')
							/*array('opened_value'=>190,'timeline'=>'Nov-17')*/
							);
			$fo_closed_status=array(
							array('closed_value'=>50,'timeline'=>'6Nov'),
							array('closed_value'=>75,'timeline'=>'7Nov'),
							array('closed_value'=>190,'timeline'=>'8Nov'),
							array('closed_value'=>140,'timeline'=>'9Nov'),
							array('closed_value'=>100,'timeline'=>'10Nov'),
							array('closed_value'=>150,'timeline'=>'11Nov'),
							array('closed_value'=>90,'timeline'=>'12Nov'),
							/*array('closed_value'=>90,'timeline'=>'Nov-17')*/
							);
		}
	}
	if($searchFilters['vtime']=='q')
	{
		$quarter=$searchFilters['duration'];
		$quat_arr=explode('to', $quarter);
		$start_date=$quat_arr[0];
		$end_date=$quat_arr[1];
		if($searchFilters['duration']=='2017-10-02to2017-12-31')
		{
			$fo_opened_status=array(
						
						array('opened_value'=>260,'timeline'=>'Oct-17'),
						array('opened_value'=>312,'timeline'=>'Nov-17')
						);
			$fo_closed_status=array(
						
						array('closed_value'=>90,'timeline'=>'Oct-17'),
						array('closed_value'=>90,'timeline'=>'Nov-17')
						);
		}
		elseif($searchFilters['duration']=='2017-07-03to2017-10-01')
		{
			$fo_opened_status=array(
						
						array('opened_value'=>260,'timeline'=>'Jul-17'),
						array('opened_value'=>200,'timeline'=>'Aug-17'),
						array('opened_value'=>162,'timeline'=>'Sep-17'),
						);
			$fo_closed_status=array(
						
						array('closed_value'=>90,'timeline'=>'Jul-17'),
						array('closed_value'=>90,'timeline'=>'Aug-17'),
						array('closed_value'=>50,'timeline'=>'Sep-17'),
						);
		}
		elseif($searchFilters['duration']=='2017-04-03to2017-07-02')
		{
			$fo_opened_status=array(
						
						array('opened_value'=>260,'timeline'=>'Apr-17'),
						array('opened_value'=>200,'timeline'=>'May-17'),
						array('opened_value'=>162,'timeline'=>'Jun-17'),
						);
			$fo_closed_status=array(
						
						array('closed_value'=>90,'timeline'=>'Apr-17'),
						array('closed_value'=>90,'timeline'=>'May-17'),
						array('closed_value'=>50,'timeline'=>'Jun-17'),
						);
		}
		
	}
	$fo_present_date=array('Hot'=>500,'Warm'=>500,'Cold'=>400);
	$ores=static_get_dates($searchFilters);
	//print_r($ores); exit;
	$series=array();
	$count=count($ores);
	$static_c1data1=array();
	$static_c1data1[]=588 ;
	for($i=1;$i<=$count;$i++)
	{
		$static_c1data1[]='' ;
	}
	$static_c1data1[]=500 ;
	$static_c1data2=array();
	$static_c1data2[]=320; 
	for($i=1;$i<=$count;$i++)
	{
		$static_c1data2[]='' ;
	}
	$static_c1data2[]=500 ;
	$static_c1data3=array();
	$static_c1data3[]=100;
	for($i=1;$i<=$count;$i++)
	{
		$static_c1data3[]='' ;
	}
	$static_c1data3[]=400 ;
	$fy_dates=get_start_end_dates($searchFilters['vtime'],'',$searchFilters);
	if($searchFilters['vtime']=='m'||$searchFilters['vtime']=='w')
	{
		$timeline=date('jM',strtotime($fy_dates['start_date']));
	}
	else
	{
		$timeline=date('jM-y',strtotime($fy_dates['start_date']));
	}
	
	$xAxisCategory[]=$timeline;
	$c1Data4=array();
	$c1Data5=array();
	$c1Data6=array();
	$c1Data4[]='';
	$c1Data5[]='';
	$c1Data6[]='';
	$top=$fo_before_date['Hot']+$fo_before_date['Warm']+$fo_before_date['Cold'];
	//echo $top;
	$new_op = array();
	foreach ($fo_opened_status as $orow) {
		$new_op[$orow['timeline']] = $orow;
	}
	$closed_op = array();
	foreach ($fo_closed_status as $crow) {
		$closed_op[$crow['timeline']] = $crow;
	}
	$position=array();
	foreach($ores as $x_axix_lable)
	{
		$new_op_val = (@$new_op[$x_axix_lable]['opened_value']!='')?$new_op[$x_axix_lable]['opened_value']:0;
		$closed_op_val = (@$closed_op[$x_axix_lable]['closed_value']!='')?$closed_op[$x_axix_lable]['closed_value']:0;
		$c1Data4[]=(int)$new_op_val;
		$c1Data5[]=(int)$closed_op_val;
		$xAxisCategory[]=$x_axix_lable;
		$tpo=($top-($new_op_val+$closed_op_val)+($new_op_val-$closed_op_val));
		$top = $top + $new_op_val-$closed_op_val;
		$c1Data6[]=(int)$tpo;
		$position[]=$tpo;
	}

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
	   $kk= get_month_no_by_date($curdate);
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
	$series []= array('name'=>'Hot','data'=>$static_c1data1,'color'=>'#F44336');
	$series []= array('name'=>'Warm','data'=>$static_c1data2,'color'=>'#FF9800');
	$series []= array('name'=>'Cold','data'=>$static_c1data3,'color'=>'#3F51B5');
	$series []= array('name'=>'New','data'=>$c1Data4,'color'=>'#95B7DA');
	$series []= array('name'=>'Closed','data'=>$c1Data5,'color'=>'#B5F78D');
	$series []= array('name'=>'Constant','data'=>$c1Data6,'color'=>'#b3b3b3');
	if($searchFilters['measure']==1)
	{
		$measure='Numbers';
	}
	else
	{
		$measure='Lakhs';
	}
	$yAxisCategory = 'Value In '.$measure;
	//echo "<pre>"; print_r($series); exit;
	/*if($searchFilters['region']!='')
	{
		$loc=$CI->Common_model->get_data_row('location',array('location_id'=>$searchFilters['region']));
		$location=$loc['location'].' Wise ';
	}*/
	$year=get_current_fiancial_year();
	//print_r($searchFilters); exit;
	if(@$searchFilters['duration_text']!='')
	{
		$text= '('.@$searchFilters['duration_text'].' )';
	}
	else
	{
		$text=$year['name'];
	}
	$xAxisLable = @$location.'Funnel report '.$text;
	$position=min($position);
	$position=(int)(1000*floor($position/1000));
	$chart1Data = array('xAxisCategory'=>$xAxisCategory,'yAxisCategory'=>$yAxisCategory,'chart1Series'=>$series,'xAxisLable'=>$xAxisLable,'tpoPosition'=>$position);
	$chart1Data1 = json_encode($chart1Data);
	return $chart1Data1;
}

function static_get_funnel_chart2($search_date,$series_name,$searchFilters)
{
	$CI=& get_instance();
	if($searchFilters['measure']==1)
	{
		$yAxisCategory='Value in Numbers';
	}
	else
	{
		$yAxisCategory='Value In Lakhs';
	}
	if($searchFilters['users']!='')
    {
    	$user_id=$searchFilters['users'];
    }
    else
    {
    	$user_id=$CI->session->userdata('user_id');
    }

	$role_id=getUserRole($user_id);
    if($user_id != $CI->session->userdata('user_id'))
	{
		$l = getUserLocations($user_id);
		$ul = getQueryArray($l);
		$up = getUserProducts($user_id);
		$userLocations = ($ul == '')? 0: $ul;
		$userProducts = ($up == '')? 0: $up;
	}
	else
	{
		$userLocations = ($CI->session->userdata('locationString') == '')? 0: $CI->session->userdata('locationString');
		$userProducts = ($CI->session->userdata('products') == '')? 0: $CI->session->userdata('products');
	}

	$month = date('m');
    $month1 = $month + 1;
    $year = date('Y');		
    $day = getOpportunityCategorizationDate();
    $hotDay = $year."-".$month."-".$day;
    $warmDate = date('Y-m-d',strtotime( $hotDay . " +1 month " ));
    $series=array();
    $c2Data1=$c2Data2=$c2Data3=array();
    $xAxisCategory=array();
    $xAxisCategory[]=$search_date;
    if($searchFilters['vtime']=='y')
    {
    	$search_month=$search_date;
    	$month_arr=explode('-', $search_month);
		$month_number=$month_arr[0];
		$year=$month_arr[1];
		if($series_name=='New')
		{
			if($search_date=='Nov-17')
			{
				$opened_results=array('Hot'=>45,'Warm'=>70,'Cold'=>75);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
			}
			elseif($search_date=='Oct-17')
			{
				$opened_results=array('Hot'=>50,'Warm'=>70,'Cold'=>30);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
			}
			elseif ($search_date=='Sep-17') 
			{
				$opened_results=array('Hot'=>80,'Warm'=>70,'Cold'=>90);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
			}
			elseif($search_date=='Aug-17')
			{
				$opened_results=array('Hot'=>60,'Warm'=>40,'Cold'=>30);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
			}
			elseif($search_date=='Jul-17')
			{
				$opened_results=array('Hot'=>80,'Warm'=>40,'Cold'=>77);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
			}
			elseif($search_date=='Jun-17')
			{
				$opened_results=array('Hot'=>40,'Warm'=>50,'Cold'=>30);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
			}
			elseif($search_date=='May-17')
			{
				$opened_results=array('Hot'=>70,'Warm'=>30,'Cold'=>50);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
			}
			elseif($search_date=='Apr-17')
			{
				$opened_results=array('Hot'=>35,'Warm'=>35,'Cold'=>30);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
			}
		}
		if($series_name=='Closed')
		{
			if($search_date=='Nov-17')
			{
				$closed_results=array(
								array('measure'=>30,'status'=>'Dropped'),
								array('measure'=>30,'status'=>'Closed Lost'),
								array('measure'=>30,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
			}
			elseif($search_date=='Oct-17')
			{
				$closed_results=array(
								array('measure'=>30,'status'=>'Dropped'),
								array('measure'=>30,'status'=>'Closed Lost'),
								array('measure'=>30,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
			}
			elseif($search_date=='Sep-17')
			{
				$closed_results=array(
								array('measure'=>50,'status'=>'Dropped'),
								array('measure'=>50,'status'=>'Closed Lost'),
								array('measure'=>50,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
			}
			elseif($search_date=='Aug-17')
			{
				$closed_results=array(
								array('measure'=>30,'status'=>'Dropped'),
								array('measure'=>30,'status'=>'Closed Lost'),
								array('measure'=>40,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
			}
			elseif($search_date=='Jul-17')
			{
				$closed_results=array(
								array('measure'=>40,'status'=>'Dropped'),
								array('measure'=>30,'status'=>'Closed Lost'),
								array('measure'=>70,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
			}
			elseif($search_date=='Jun-17')
			{
				$closed_results=array(
								array('measure'=>45,'status'=>'Dropped'),
								array('measure'=>45,'status'=>'Closed Lost'),
								array('measure'=>100,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
			}
			elseif($search_date=='May-17')
			{
				$closed_results=array(
								array('measure'=>30,'status'=>'Dropped'),
								array('measure'=>30,'status'=>'Closed Lost'),
								array('measure'=>15,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
			}
			elseif($search_date=='Apr-17')
			{
				$closed_results=array(
								array('measure'=>10,'status'=>'Dropped'),
								array('measure'=>10,'status'=>'Closed Lost'),
								array('measure'=>30,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
			}
		}
		
    }
    if($searchFilters['vtime']=='q')
    {
    	$search_month=$search_date;
    	$month_arr=explode('-', $search_month);
		$month_number=$month_arr[0];
		$year=$month_arr[1];
		if($series_name=='New')
		{
			if($search_date=='Nov-17')
			{
				$opened_results=array('Hot'=>100,'Warm'=>100,'Cold'=>112);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
			}
			elseif($search_date=='Oct-17')
			{
				$opened_results=array('Hot'=>130,'Warm'=>70,'Cold'=>60);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
			}
			elseif ($search_date=='Sep-17') 
			{
				$opened_results=array('Hot'=>80,'Warm'=>40,'Cold'=>42);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
			}
			elseif($search_date=='Aug-17')
			{
				$opened_results=array('Hot'=>60,'Warm'=>70,'Cold'=>70);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
			}
			elseif($search_date=='Jul-17')
			{
				$opened_results=array('Hot'=>130,'Warm'=>70,'Cold'=>60);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
			}
		}
		if($series_name=='Closed')
		{
			if($search_date=='Nov-17')
			{
				$closed_results=array(
								array('measure'=>30,'status'=>'Dropped'),
								array('measure'=>30,'status'=>'Closed Lost'),
								array('measure'=>30,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
			}
			elseif($search_date=='Oct-17')
			{
				$closed_results=array(
								array('measure'=>30,'status'=>'Dropped'),
								array('measure'=>30,'status'=>'Closed Lost'),
								array('measure'=>30,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
			}
			elseif($search_date=='Sep-17')
			{
				$closed_results=array(
								array('measure'=>20,'status'=>'Dropped'),
								array('measure'=>10,'status'=>'Closed Lost'),
								array('measure'=>20,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
			}
			elseif($search_date=='Aug-17')
			{
				$closed_results=array(
								array('measure'=>30,'status'=>'Dropped'),
								array('measure'=>30,'status'=>'Closed Lost'),
								array('measure'=>30,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
			}
			elseif($search_date=='Jul-17')
			{
				$closed_results=array(
								array('measure'=>40,'status'=>'Dropped'),
								array('measure'=>30,'status'=>'Closed Lost'),
								array('measure'=>20,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
			}
		}
    }
    if($searchFilters['vtime']=='m')
    {
    	if($series_name=='New')
    	{
    		if($search_date=='Week3 (2017-11-20 to 2017-11-26)')
    		{
    			$opened_results=array('Hot'=>100,'Warm'=>100,'Cold'=>177);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
    		}
    		elseif($search_date=='Week2 (2017-11-13 to 2017-11-19)')
    		{
    			$opened_results=array('Hot'=>50,'Warm'=>50,'Cold'=>50);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
    		}
    		elseif($search_date=='Week1 (2017-11-06 to 2017-11-12)')
    		{
    			$opened_results=array('Hot'=>50,'Warm'=>100,'Cold'=>50);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
    		}
    		elseif($search_date=='Week5 (2017-10-30 to 2017-11-05)')
    		{
    			$opened_results=array('Hot'=>100,'Warm'=>60,'Cold'=>70);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
    		}
    		elseif($search_date=='Week4 (2017-10-23 to 2017-10-29)')
    		{
    			$opened_results=array('Hot'=>50,'Warm'=>50,'Cold'=>50);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
    		}
    		elseif($search_date=='Week3 (2017-10-16 to 2017-10-22)')
    		{
    			$opened_results=array('Hot'=>100,'Warm'=>100,'Cold'=>50);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
    		}
    		elseif($search_date=='Week2 (2017-10-09 to 2017-10-15)')
    		{
    			$opened_results=array('Hot'=>100,'Warm'=>100,'Cold'=>50);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
    		}
    		elseif($search_date=='Week1 (2017-10-02 to 2017-10-08)')
    		{
    			$opened_results=array('Hot'=>100,'Warm'=>100,'Cold'=>126);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
    		}
    		elseif($search_date=='Week4 (2017-09-25 to 2017-10-01)')
    		{
    			$opened_results=array('Hot'=>100,'Warm'=>50,'Cold'=>50);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
    		}
    		elseif($search_date=='Week3 (2017-09-18 to 2017-09-24)')
    		{
    			$opened_results=array('Hot'=>100,'Warm'=>100,'Cold'=>130);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
    		}
    		elseif($search_date=='Week2 (2017-09-11 to 2017-09-17)')
    		{
    			$opened_results=array('Hot'=>100,'Warm'=>150,'Cold'=>100);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
    		}
    		elseif($search_date=='Week1 (2017-09-04 to 2017-09-10)')
    		{
    			$opened_results=array('Hot'=>150,'Warm'=>150,'Cold'=>187);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
    		}
    		elseif($search_date=='Week4 (2017-08-28 to 2017-09-03)')
    		{
    			$opened_results=array('Hot'=>47,'Warm'=>150,'Cold'=>150);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
    		}
    		elseif($search_date=='Week3 (2017-08-21 to 2017-08-27)')
    		{
    			$opened_results=array('Hot'=>100,'Warm'=>50,'Cold'=>100);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
    		}
    		elseif($search_date=='Week2 (2017-08-14 to 2017-08-20)')
    		{
    			$opened_results=array('Hot'=>50,'Warm'=>50,'Cold'=>50);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
    		}
    		elseif($search_date=='Week1 (2017-08-07 to 2017-08-13)')
    		{
    			$opened_results=array('Hot'=>30,'Warm'=>40,'Cold'=>30);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
    		}
    		elseif($search_date=='Week5 (2017-07-31 to 2017-08-06)')
    		{
    			$opened_results=array('Hot'=>70,'Warm'=>35,'Cold'=>35);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
    		}
    		elseif($search_date=='Week4 (2017-07-24 to 2017-07-30)')
    		{
    			$opened_results=array('Hot'=>100,'Warm'=>100,'Cold'=>147);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
    		}
    		elseif($search_date=='Week3 (2017-07-17 to 2017-07-23)')
    		{
    			$opened_results=array('Hot'=>100,'Warm'=>100,'Cold'=>50);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
    		}
    		elseif($search_date=='Week2 (2017-07-10 to 2017-07-16)')
    		{
    			$opened_results=array('Hot'=>50,'Warm'=>50,'Cold'=>50);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
    		}
    		elseif($search_date=='Week1 (2017-07-03 to 2017-07-09)')
    		{
    			$opened_results=array('Hot'=>30,'Warm'=>30,'Cold'=>40);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
    		}
    		elseif($search_date=='Week4 (2017-06-26 to 2017-07-02)')
    		{
    			$opened_results=array('Hot'=>100,'Warm'=>100,'Cold'=>147);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
    		}
    		elseif($search_date=='Week3 (2017-06-19 to 2017-06-25)')
    		{
    			$opened_results=array('Hot'=>100,'Warm'=>100,'Cold'=>50);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
    		}
    		elseif($search_date=='Week2 (2017-06-12 to 2017-06-18)')
    		{
    			$opened_results=array('Hot'=>50,'Warm'=>50,'Cold'=>50);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
    		}
    		elseif($search_date=='Week1 (2017-06-05 to 2017-06-11)')
    		{
    			$opened_results=array('Hot'=>30,'Warm'=>30,'Cold'=>40);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
    		}
    		elseif($search_date=='Week5 (2017-05-29 to 2017-06-04)')
    		{
    			$opened_results=array('Hot'=>70,'Warm'=>35,'Cold'=>35);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
    		}
    		elseif($search_date=='Week4 (2017-05-22 to 2017-05-28)')
    		{
    			$opened_results=array('Hot'=>100,'Warm'=>100,'Cold'=>147);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
    		}
    		elseif($search_date=='Week3 (2017-05-15 to 2017-05-21)')
    		{
    			$opened_results=array('Hot'=>100,'Warm'=>100,'Cold'=>50);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
    		}
    		elseif($search_date=='Week2 (2017-05-08 to 2017-05-14)')
    		{
    			$opened_results=array('Hot'=>50,'Warm'=>50,'Cold'=>50);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
    		}
    		elseif($search_date=='Week1 (2017-05-01 to 2017-05-07)')
    		{
    			$opened_results=array('Hot'=>30,'Warm'=>30,'Cold'=>40);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
    		}
    		elseif($search_date=='Week4 (2017-04-24 to 2017-04-30)')
    		{
    			$opened_results=array('Hot'=>100,'Warm'=>100,'Cold'=>147);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
    		}
    		elseif($search_date=='Week3 (2017-04-17 to 2017-04-23)')
    		{
    			$opened_results=array('Hot'=>100,'Warm'=>100,'Cold'=>50);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
    		}
    		elseif($search_date=='Week2 (2017-04-10 to 2017-04-16)')
    		{
    			$opened_results=array('Hot'=>50,'Warm'=>50,'Cold'=>50);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
    		}
    		elseif($search_date=='Week1 (2017-04-03 to 2017-04-09)')
    		{
    			$opened_results=array('Hot'=>30,'Warm'=>30,'Cold'=>40);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
    		}

    	}
    	if($series_name=='Closed')
    	{
    		if($search_date=='Week3 (2017-11-20 to 2017-11-26)')
    		{
    			$closed_results=array(
								array('measure'=>100,'status'=>'Dropped'),
								array('measure'=>45,'status'=>'Closed Lost'),
								array('measure'=>45,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
    		}
    		elseif($search_date=='Week2 (2017-11-13 to 2017-11-19)')
    		{
    			$closed_results=array(
								array('measure'=>30,'status'=>'Dropped'),
								array('measure'=>30,'status'=>'Closed Lost'),
								array('measure'=>35,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
    		}
    		elseif($search_date=='Week1 (2017-11-06 to 2017-11-12)')
    		{
    			$closed_results=array(
								array('measure'=>20,'status'=>'Dropped'),
								array('measure'=>20,'status'=>'Closed Lost'),
								array('measure'=>10,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
    		}
    		elseif($search_date=='Week5 (2017-10-30 to 2017-11-05)')
    		{
    			$closed_results=array(
								array('measure'=>100,'status'=>'Dropped'),
								array('measure'=>50,'status'=>'Closed Lost'),
								array('measure'=>70,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
    		}
    		elseif($search_date=='Week4 (2017-10-23 to 2017-10-29)')
    		{
    			$closed_results=array(
								array('measure'=>100,'status'=>'Dropped'),
								array('measure'=>50,'status'=>'Closed Lost'),
								array('measure'=>90,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
    		}
    		elseif($search_date=='Week3 (2017-10-16 to 2017-10-22)')
    		{
    			$closed_results=array(
								array('measure'=>30,'status'=>'Dropped'),
								array('measure'=>30,'status'=>'Closed Lost'),
								array('measure'=>49,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
    		}
    		elseif($search_date=='Week2 (2017-10-09 to 2017-10-15)')
    		{
    			$closed_results=array(
								array('measure'=>30,'status'=>'Dropped'),
								array('measure'=>30,'status'=>'Closed Lost'),
								array('measure'=>35,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
    		}
    		elseif($search_date=='Week1 (2017-10-02 to 2017-10-08)')
    		{
    			$closed_results=array(
								array('measure'=>50,'status'=>'Dropped'),
								array('measure'=>50,'status'=>'Closed Lost'),
								array('measure'=>50,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
    		}
    		elseif($search_date=='Week4 (2017-09-25 to 2017-10-01)')
    		{
    			$closed_results=array(
								array('measure'=>100,'status'=>'Dropped'),
								array('measure'=>100,'status'=>'Closed Lost'),
								array('measure'=>40,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
    		}
    		elseif($search_date=='Week3 (2017-09-18 to 2017-09-24)')
    		{
    			$closed_results=array(
								array('measure'=>60,'status'=>'Dropped'),
								array('measure'=>100,'status'=>'Closed Lost'),
								array('measure'=>30,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
    		}
    		elseif($search_date=='Week2 (2017-09-11 to 2017-09-17)')
    		{
    			$closed_results=array(
								array('measure'=>60,'status'=>'Dropped'),
								array('measure'=>100,'status'=>'Closed Lost'),
								array('measure'=>35,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
    		}
    		elseif($search_date=='Week1 (2017-09-04 to 2017-09-10)')
    		{
    			$closed_results=array(
								array('measure'=>100,'status'=>'Dropped'),
								array('measure'=>100,'status'=>'Closed Lost'),
								array('measure'=>150,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
    		}
    		elseif($search_date=='Week4 (2017-08-28 to 2017-09-03)')
    		{
    			$closed_results=array(
								array('measure'=>70,'status'=>'Dropped'),
								array('measure'=>30,'status'=>'Closed Lost'),
								array('measure'=>40,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
    		}
    		elseif($search_date=='Week3 (2017-08-21 to 2017-08-27)')
    		{
    			$closed_results=array(
								array('measure'=>70,'status'=>'Dropped'),
								array('measure'=>30,'status'=>'Closed Lost'),
								array('measure'=>90,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
    		}
    		elseif($search_date=='Week2 (2017-08-14 to 2017-08-20)')
    		{
    			$closed_results=array(
								array('measure'=>30,'status'=>'Dropped'),
								array('measure'=>10,'status'=>'Closed Lost'),
								array('measure'=>35,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
    		}
    		elseif($search_date=='Week1 (2017-08-07 to 2017-08-13)')
    		{
    			$closed_results=array(
								array('measure'=>10,'status'=>'Dropped'),
								array('measure'=>20,'status'=>'Closed Lost'),
								array('measure'=>20,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
    		}
    		elseif($search_date=='Week5 (2017-07-31 to 2017-08-06)')
    		{
    			$closed_results=array(
								array('measure'=>60,'status'=>'Dropped'),
								array('measure'=>40,'status'=>'Closed Lost'),
								array('measure'=>40,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
    		}
    		elseif($search_date=='Week4 (2017-07-24 to 2017-07-30)')
    		{
    			$closed_results=array(
								array('measure'=>70,'status'=>'Dropped'),
								array('measure'=>30,'status'=>'Closed Lost'),
								array('measure'=>40,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
    		}
    		elseif($search_date=='Week3 (2017-07-17 to 2017-07-23)')
    		{
    			$closed_results=array(
								array('measure'=>70,'status'=>'Dropped'),
								array('measure'=>30,'status'=>'Closed Lost'),
								array('measure'=>90,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
    		}
    		elseif($search_date=='Week2 (2017-07-10 to 2017-07-16)')
    		{
    			$closed_results=array(
								array('measure'=>30,'status'=>'Dropped'),
								array('measure'=>10,'status'=>'Closed Lost'),
								array('measure'=>35,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
    		}
    		elseif($search_date=='Week1 (2017-07-03 to 2017-07-09)')
    		{
    			$closed_results=array(
								array('measure'=>10,'status'=>'Dropped'),
								array('measure'=>20,'status'=>'Closed Lost'),
								array('measure'=>20,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
    		}
    		elseif($search_date=='Week4 (2017-06-26 to 2017-07-02)')
    		{
    			$closed_results=array(
								array('measure'=>70,'status'=>'Dropped'),
								array('measure'=>30,'status'=>'Closed Lost'),
								array('measure'=>40,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
    		}
    		elseif($search_date=='Week3 (2017-06-19 to 2017-06-25)')
    		{
    			$closed_results=array(
								array('measure'=>70,'status'=>'Dropped'),
								array('measure'=>30,'status'=>'Closed Lost'),
								array('measure'=>90,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
    		}
    		elseif($search_date=='Week2 (2017-06-12 to 2017-06-18)')
    		{
    			$closed_results=array(
								array('measure'=>30,'status'=>'Dropped'),
								array('measure'=>10,'status'=>'Closed Lost'),
								array('measure'=>35,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
    		}
    		elseif($search_date=='Week1 (2017-06-05 to 2017-06-11)')
    		{
    			$closed_results=array(
								array('measure'=>10,'status'=>'Dropped'),
								array('measure'=>20,'status'=>'Closed Lost'),
								array('measure'=>20,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
    		}
    		elseif($search_date=='Week5 (2017-05-29 to 2017-06-04)')
    		{
    			$closed_results=array(
								array('measure'=>60,'status'=>'Dropped'),
								array('measure'=>40,'status'=>'Closed Lost'),
								array('measure'=>40,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
    		}
    		elseif($search_date=='Week4 (2017-05-22 to 2017-05-28)')
    		{
    			$closed_results=array(
								array('measure'=>70,'status'=>'Dropped'),
								array('measure'=>30,'status'=>'Closed Lost'),
								array('measure'=>40,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
    		}
    		elseif($search_date=='Week3 (2017-05-15 to 2017-05-21)')
    		{
    			$closed_results=array(
								array('measure'=>70,'status'=>'Dropped'),
								array('measure'=>30,'status'=>'Closed Lost'),
								array('measure'=>90,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
    		}
    		elseif($search_date=='Week2 (2017-05-08 to 2017-05-14)')
    		{
    			$closed_results=array(
								array('measure'=>30,'status'=>'Dropped'),
								array('measure'=>10,'status'=>'Closed Lost'),
								array('measure'=>35,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
    		}
    		elseif($search_date=='Week1 (2017-05-01 to 2017-05-07)')
    		{
    			$closed_results=array(
								array('measure'=>10,'status'=>'Dropped'),
								array('measure'=>20,'status'=>'Closed Lost'),
								array('measure'=>20,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
    		}
    		elseif($search_date=='Week4 (2017-04-24 to 2017-04-30)')
    		{
    			$closed_results=array(
								array('measure'=>70,'status'=>'Dropped'),
								array('measure'=>30,'status'=>'Closed Lost'),
								array('measure'=>40,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
    		}
    		elseif($search_date=='Week3 (2017-04-17 to 2017-04-23)')
    		{
    			$closed_results=array(
								array('measure'=>70,'status'=>'Dropped'),
								array('measure'=>30,'status'=>'Closed Lost'),
								array('measure'=>90,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
    		}
    		elseif($search_date=='Week2 (2017-04-10 to 2017-04-16)')
    		{
    			$closed_results=array(
								array('measure'=>30,'status'=>'Dropped'),
								array('measure'=>10,'status'=>'Closed Lost'),
								array('measure'=>35,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
    		}
    		elseif($search_date=='Week1 (2017-04-03 to 2017-04-09)')
    		{
    			$closed_results=array(
								array('measure'=>10,'status'=>'Dropped'),
								array('measure'=>20,'status'=>'Closed Lost'),
								array('measure'=>20,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
    		}
    	}
    }
    if($searchFilters['vtime']=='w')
    {
    	
		if($series_name=='New')
		{
			if($search_date=='28Nov')
			{
				$opened_results=array('Hot'=>120,'Warm'=>100,'Cold'=>70);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
			}
			elseif($search_date=='27Nov')
			{
				$opened_results=array('Hot'=>57,'Warm'=>100,'Cold'=>100);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
			}
			elseif($search_date=='26Nov')
			{
				$opened_results=array('Hot'=>100,'Warm'=>100,'Cold'=>50);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
			}
			elseif($search_date=='25Nov')
			{
				$opened_results=array('Hot'=>100,'Warm'=>100,'Cold'=>40);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
			}
			elseif($search_date=='24Nov')
			{
				$opened_results=array('Hot'=>40,'Warm'=>40,'Cold'=>50);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
			}
			elseif($search_date=='23Nov')
			{
				$opened_results=array('Hot'=>100,'Warm'=>40,'Cold'=>57);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
			}
			elseif($search_date=='22Nov')
			{
				$opened_results=array('Hot'=>40,'Warm'=>40,'Cold'=>40);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
			}
			elseif($search_date=='21Nov')
			{
				$opened_results=array('Hot'=>50,'Warm'=>70,'Cold'=>30);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
			}
			elseif($search_date=='20Nov')
			{
				$opened_results=array('Hot'=>50,'Warm'=>20,'Cold'=>30);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
			}
			elseif($search_date=='19Nov')
			{
				$opened_results=array('Hot'=>100,'Warm'=>100,'Cold'=>50);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
			}
			elseif($search_date=='18Nov')
			{
				$opened_results=array('Hot'=>100,'Warm'=>100,'Cold'=>40);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
			}
			elseif($search_date=='17Nov')
			{
				$opened_results=array('Hot'=>60,'Warm'=>30,'Cold'=>40);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
			}
			elseif($search_date=='16Nov')
			{
				$opened_results=array('Hot'=>100,'Warm'=>40,'Cold'=>57);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
			}
			elseif($search_date=='15Nov')
			{
				$opened_results=array('Hot'=>60,'Warm'=>40,'Cold'=>20);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
			}
			elseif($search_date=='14Nov')
			{
				$opened_results=array('Hot'=>100,'Warm'=>20,'Cold'=>30);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
			}
			elseif($search_date=='13Nov')
			{
				$opened_results=array('Hot'=>30,'Warm'=>40,'Cold'=>30);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
			}
			elseif($search_date=='12Nov')
			{
				$opened_results=array('Hot'=>50,'Warm'=>100,'Cold'=>100);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
			}
			elseif($search_date=='11Nov')
			{
				$opened_results=array('Hot'=>100,'Warm'=>100,'Cold'=>40);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
			}
			elseif($search_date=='10Nov')
			{
				$opened_results=array('Hot'=>30,'Warm'=>60,'Cold'=>40);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
			}
			elseif($search_date=='9Nov')
			{
				$opened_results=array('Hot'=>70,'Warm'=>50,'Cold'=>77);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
			}
			elseif($search_date=='8Nov')
			{
				$opened_results=array('Hot'=>40,'Warm'=>30,'Cold'=>10);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
			}
			elseif($search_date=='7Nov')
			{
				$opened_results=array('Hot'=>50,'Warm'=>50,'Cold'=>50);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
			}
			elseif($search_date=='6Nov')
			{
				$opened_results=array('Hot'=>30,'Warm'=>30,'Cold'=>40);
				$c2Data1[]=(int)$opened_results['Hot'];
				$c2Data2[]=(int)$opened_results['Warm'];
				$c2Data3[]=(int)$opened_results['Cold'];
				$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
			    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
			    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
			}
		}
		if($series_name=='Closed')
		{
			if($search_date=='28Nov')
			{
				$closed_results=array(
								array('measure'=>30,'status'=>'Dropped'),
								array('measure'=>20,'status'=>'Closed Lost'),
								array('measure'=>25,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
			}
			elseif($search_date=='27Nov')
			{
				$closed_results=array(
								array('measure'=>20,'status'=>'Dropped'),
								array('measure'=>40,'status'=>'Closed Lost'),
								array('measure'=>20,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
			}
			elseif($search_date=='26Nov')
			{
				$closed_results=array(
								array('measure'=>30,'status'=>'Dropped'),
								array('measure'=>30,'status'=>'Closed Lost'),
								array('measure'=>30,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
			}
			elseif($search_date=='25Nov')
			{
				$closed_results=array(
								array('measure'=>50,'status'=>'Dropped'),
								array('measure'=>50,'status'=>'Closed Lost'),
								array('measure'=>50,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
			}
			elseif($search_date=='24Nov')
			{
				$closed_results=array(
								array('measure'=>30,'status'=>'Dropped'),
								array('measure'=>20,'status'=>'Closed Lost'),
								array('measure'=>50,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
			}
			elseif($search_date=='23Nov')
			{
				$closed_results=array(
								array('measure'=>60,'status'=>'Dropped'),
								array('measure'=>40,'status'=>'Closed Lost'),
								array('measure'=>40,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
			}
			elseif($search_date=='22Nov')
			{
				$closed_results=array(
								array('measure'=>100,'status'=>'Dropped'),
								array('measure'=>40,'status'=>'Closed Lost'),
								array('measure'=>50,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
			}
			elseif($search_date=='21Nov')
			{
				$closed_results=array(
								array('measure'=>20,'status'=>'Dropped'),
								array('measure'=>30,'status'=>'Closed Lost'),
								array('measure'=>25,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
			}
			elseif($search_date=='20Nov')
			{
				$closed_results=array(
								array('measure'=>20,'status'=>'Dropped'),
								array('measure'=>20,'status'=>'Closed Lost'),
								array('measure'=>15,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
			}
			elseif($search_date=='19Nov')
			{
				$closed_results=array(
								array('measure'=>30,'status'=>'Dropped'),
								array('measure'=>30,'status'=>'Closed Lost'),
								array('measure'=>35,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
			}
			elseif($search_date=='18Nov')
			{
				$closed_results=array(
								array('measure'=>70,'status'=>'Dropped'),
								array('measure'=>30,'status'=>'Closed Lost'),
								array('measure'=>50,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
			}
			elseif($search_date=='17Nov')
			{
				$closed_results=array(
								array('measure'=>30,'status'=>'Dropped'),
								array('measure'=>30,'status'=>'Closed Lost'),
								array('measure'=>40,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
			}
			elseif($search_date=='16Nov')
			{
				$closed_results=array(
								array('measure'=>70,'status'=>'Dropped'),
								array('measure'=>30,'status'=>'Closed Lost'),
								array('measure'=>40,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
			}
			elseif($search_date=='15Nov')
			{
				$closed_results=array(
								array('measure'=>40,'status'=>'Dropped'),
								array('measure'=>70,'status'=>'Closed Lost'),
								array('measure'=>80,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
			}
			elseif($search_date=='14Nov')
			{
				$closed_results=array(
								array('measure'=>30,'status'=>'Dropped'),
								array('measure'=>30,'status'=>'Closed Lost'),
								array('measure'=>15,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
			}
			elseif($search_date=='13Nov')
			{
				$closed_results=array(
								array('measure'=>20,'status'=>'Dropped'),
								array('measure'=>20,'status'=>'Closed Lost'),
								array('measure'=>10,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
			}
			elseif($search_date=='12Nov')
			{
				$closed_results=array(
								array('measure'=>30,'status'=>'Dropped'),
								array('measure'=>30,'status'=>'Closed Lost'),
								array('measure'=>30,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
			}
			elseif($search_date=='11Nov')
			{
				$closed_results=array(
								array('measure'=>70,'status'=>'Dropped'),
								array('measure'=>30,'status'=>'Closed Lost'),
								array('measure'=>50,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
			}
			elseif($search_date=='10Nov')
			{
				$closed_results=array(
								array('measure'=>60,'status'=>'Dropped'),
								array('measure'=>20,'status'=>'Closed Lost'),
								array('measure'=>20,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
			}
			elseif($search_date=='9Nov')
			{
				$closed_results=array(
								array('measure'=>60,'status'=>'Dropped'),
								array('measure'=>40,'status'=>'Closed Lost'),
								array('measure'=>40,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
			}
			elseif($search_date=='8Nov')
			{
				$closed_results=array(
								array('measure'=>70,'status'=>'Dropped'),
								array('measure'=>50,'status'=>'Closed Lost'),
								array('measure'=>70,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
			}
			elseif($search_date=='7Nov')
			{
				$closed_results=array(
								array('measure'=>30,'status'=>'Dropped'),
								array('measure'=>10,'status'=>'Closed Lost'),
								array('measure'=>35,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
			}
			elseif($search_date=='6Nov')
			{
				$closed_results=array(
								array('measure'=>30,'status'=>'Dropped'),
								array('measure'=>10,'status'=>'Closed Lost'),
								array('measure'=>10,'status'=>'Closed Won')
							);
				foreach ($closed_results as $row)
				{
					$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
				}
			}
		}
    }
	/*if($series_name=='New')
    {
		//$opened_results=$CI->Report_model->get_fo_by_date_opened($user_id, $role_id, $userProducts, $userLocations, $hotDay, $warmDate,$search_date,$searchFilters);
		$opened_results=array('Hot'=>45,'Warm'=>50,'Cold'=>55);
		$c2Data1[]=(int)$opened_results['Hot'];
		$c2Data2[]=(int)$opened_results['Warm'];
		$c2Data3[]=(int)$opened_results['Cold'];
		$series []= array('name'=>'Hot','data'=>$c2Data1,'color'=>'#F44336');
	    $series []= array('name'=>'Warm','data'=>$c2Data2,'color'=>'#FF9800');
	    $series []= array('name'=>'Cold','data'=>$c2Data3,'color'=>'#3F51B5');
		
	}*/
    /*if($series_name=='Closed')
	{
		//$closed_results=$CI->Report_model->get_fo_by_date_closed($user_id, $role_id, $userProducts, $userLocations, $hotDay, $warmDate,$search_date,$searchFilters);
		$closed_results=array(
								array('measure'=>30,'status'=>'Dropped'),
								array('measure'=>30,'status'=>'Closed Lost'),
								array('measure'=>35,'status'=>'Closed Won')
							);
		foreach ($closed_results as $row)
		{
			$series[]=array('name'=>$row['status'],'data'=>array((int)$row['measure']));
		}
	}*/
	//echo $CI->db->last_query();

	$chart2Data = array('xAxisCategory2'=>$xAxisCategory,'yAxisCategory2'=>$yAxisCategory,'chart2Series'=>$series,'xAxisLable2'=>$series_name.' Opportunities');
	$chart2Data = json_encode($chart2Data);
	return $chart2Data;

}

function static_get_funnel_chart3($x_category2,$series_name2,$searchFilters)
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
    	$user_id=$searchFilters['users'];
    }
    else
    {
    	$user_id=$CI->session->userdata('user_id');
    }
	
	$role_id=getUserRole($user_id);
    if($user_id != $CI->session->userdata('user_id'))
	{
		$l = getUserLocations($user_id);
		$ul = getQueryArray($l);
		$up = getUserProducts($user_id);
		$userLocations = ($ul == '')? 0: $ul;
		$userProducts = ($up == '')? 0: $up;
	}
	else
	{
		$userLocations = ($CI->session->userdata('locationString') == '')? 0: $CI->session->userdata('locationString');
		$userProducts = ($CI->session->userdata('products') == '')? 0: $CI->session->userdata('products');
	}

	$month = date('m');
    $month1 = $month + 1;
    $year = date('Y');		
    $day = getOpportunityCategorizationDate();
    $hotDay = $year."-".$month."-".$day;
    $warmDate = date('Y-m-d',strtotime( $hotDay . " +1 month " ));
    $series=array();
    $xAxisCategory=array();
    if($searchFilters['vtime']=='y')
    {
    	$search_date=$x_category2;
		if($search_date=='Nov-17')
		{
			$lost_reasons=array(
						array('measure'=>15,'reason_name'=>'Brand Value'),
						array('measure'=>10,'reason_name'=>'Product Features'),
						array('measure'=>5,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>20,'competitor_name'=>'GE'),
								array('measure'=>10,'competitor_name'=>'Philips')
							);
		}
		elseif($search_date=='Oct-17')
		{
			$lost_reasons=array(
						array('measure'=>15,'reason_name'=>'Brand Value'),
						array('measure'=>10,'reason_name'=>'Product Features'),
						array('measure'=>5,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>20,'competitor_name'=>'GE'),
								array('measure'=>10,'competitor_name'=>'Philips')
							);
		}
		elseif($search_date=='Sep-17')
		{
			$lost_reasons=array(
						array('measure'=>20,'reason_name'=>'Brand Value'),
						array('measure'=>20,'reason_name'=>'Product Features'),
						array('measure'=>10,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>20,'competitor_name'=>'GE'),
								array('measure'=>30,'competitor_name'=>'Philips')
							);
		}
		elseif($search_date=='Aug-17')
		{
			$lost_reasons=array(
						array('measure'=>10,'reason_name'=>'Brand Value'),
						array('measure'=>10,'reason_name'=>'Product Features'),
						array('measure'=>10,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>20,'competitor_name'=>'GE'),
								array('measure'=>10,'competitor_name'=>'Philips')
							);
		}
		elseif($search_date=='Jul-17')
		{
			$lost_reasons=array(
						array('measure'=>15,'reason_name'=>'Brand Value'),
						array('measure'=>10,'reason_name'=>'Product Features'),
						array('measure'=>5,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>20,'competitor_name'=>'GE'),
								array('measure'=>10,'competitor_name'=>'Philips')
							);
		}
		elseif($search_date=='Jun-17')
		{
			$lost_reasons=array(
						array('measure'=>15,'reason_name'=>'Brand Value'),
						array('measure'=>15,'reason_name'=>'Product Features'),
						array('measure'=>15,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>30,'competitor_name'=>'GE'),
								array('measure'=>15,'competitor_name'=>'Philips')
							);
		}
		elseif($search_date=='May-17')
		{
			$lost_reasons=array(
						array('measure'=>15,'reason_name'=>'Brand Value'),
						array('measure'=>10,'reason_name'=>'Product Features'),
						array('measure'=>5,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>20,'competitor_name'=>'GE'),
								array('measure'=>10,'competitor_name'=>'Philips')
							);
		}
		elseif($search_date=='Apr-17')
		{
			$lost_reasons=array(
						array('measure'=>3,'reason_name'=>'Brand Value'),
						array('measure'=>3,'reason_name'=>'Product Features'),
						array('measure'=>4,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>4,'competitor_name'=>'GE'),
								array('measure'=>6,'competitor_name'=>'Philips')
							);
		}
    }
    if($searchFilters['vtime']=='q')
    {
    	$search_date=$x_category2;
		if($search_date=='Nov-17')
		{
			$lost_reasons=array(
						array('measure'=>15,'reason_name'=>'Brand Value'),
						array('measure'=>10,'reason_name'=>'Product Features'),
						array('measure'=>5,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>20,'competitor_name'=>'GE'),
								array('measure'=>10,'competitor_name'=>'Philips')
							);
		}
		elseif($search_date=='Oct-17')
		{
			$lost_reasons=array(
						array('measure'=>15,'reason_name'=>'Brand Value'),
						array('measure'=>10,'reason_name'=>'Product Features'),
						array('measure'=>5,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>20,'competitor_name'=>'GE'),
								array('measure'=>10,'competitor_name'=>'Philips')
							);
		}
		elseif($search_date=='Sep-17')
		{
			$lost_reasons=array(
						array('measure'=>3,'reason_name'=>'Brand Value'),
						array('measure'=>3,'reason_name'=>'Product Features'),
						array('measure'=>4,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>5,'competitor_name'=>'GE'),
								array('measure'=>5,'competitor_name'=>'Philips')
							);
		}
		elseif($search_date=='Aug-17')
		{
			$lost_reasons=array(
						array('measure'=>10,'reason_name'=>'Brand Value'),
						array('measure'=>10,'reason_name'=>'Product Features'),
						array('measure'=>10,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>20,'competitor_name'=>'GE'),
								array('measure'=>10,'competitor_name'=>'Philips')
							);
		}
		elseif($search_date=='Jul-17')
		{
			$lost_reasons=array(
						array('measure'=>15,'reason_name'=>'Brand Value'),
						array('measure'=>10,'reason_name'=>'Product Features'),
						array('measure'=>5,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>20,'competitor_name'=>'GE'),
								array('measure'=>10,'competitor_name'=>'Philips')
							);
		}
    }
    if($searchFilters['vtime']=='m')
    {
    	$search_date=$x_category2;
    	if($search_date=='Week3 (2017-11-20 to 2017-11-26)')
		{
			$lost_reasons=array(
						array('measure'=>10,'reason_name'=>'Brand Value'),
						array('measure'=>10,'reason_name'=>'Product Features'),
						array('measure'=>25,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>22,'competitor_name'=>'GE'),
								array('measure'=>23,'competitor_name'=>'Philips')
							);
		}
		elseif($search_date=='Week2 (2017-11-13 to 2017-11-19)')
		{
			$lost_reasons=array(
						array('measure'=>10,'reason_name'=>'Brand Value'),
						array('measure'=>10,'reason_name'=>'Product Features'),
						array('measure'=>15,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>20,'competitor_name'=>'GE'),
								array('measure'=>10,'competitor_name'=>'Philips')
							);
		}
		elseif($search_date=='Week3 (2017-11-06 to 2017-11-12)')
		{
			$lost_reasons=array(
						array('measure'=>5,'reason_name'=>'Brand Value'),
						array('measure'=>10,'reason_name'=>'Product Features'),
						array('measure'=>5,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>10,'competitor_name'=>'GE'),
								array('measure'=>10,'competitor_name'=>'Philips')
							);
		}
		elseif($search_date=='Week5 (2017-10-30 to 2017-11-05)')
		{
			$lost_reasons=array(
						array('measure'=>20,'reason_name'=>'Brand Value'),
						array('measure'=>20,'reason_name'=>'Product Features'),
						array('measure'=>10,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>25,'competitor_name'=>'GE'),
								array('measure'=>25,'competitor_name'=>'Philips')
							);
		}
		elseif($search_date=='Week4 (2017-10-23 to 2017-10-29)')
		{
			$lost_reasons=array(
						array('measure'=>30,'reason_name'=>'Brand Value'),
						array('measure'=>10,'reason_name'=>'Product Features'),
						array('measure'=>10,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>30,'competitor_name'=>'GE'),
								array('measure'=>20,'competitor_name'=>'Philips')
							);
		}
		elseif($search_date=='Week3 (2017-10-16 to 2017-10-22)')
		{
			$lost_reasons=array(
						array('measure'=>15,'reason_name'=>'Brand Value'),
						array('measure'=>10,'reason_name'=>'Product Features'),
						array('measure'=>5,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>10,'competitor_name'=>'GE'),
								array('measure'=>20,'competitor_name'=>'Philips')
							);
		}
    	elseif($search_date=='Week2 (2017-10-09 to 2017-10-15)')
		{
			$lost_reasons=array(
						array('measure'=>10,'reason_name'=>'Brand Value'),
						array('measure'=>10,'reason_name'=>'Product Features'),
						array('measure'=>10,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>10,'competitor_name'=>'GE'),
								array('measure'=>20,'competitor_name'=>'Philips')
							);
		}
		elseif($search_date=='Week1 (2017-10-02 to 2017-10-08)')
		{
			$lost_reasons=array(
						array('measure'=>25,'reason_name'=>'Brand Value'),
						array('measure'=>10,'reason_name'=>'Product Features'),
						array('measure'=>15,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>30,'competitor_name'=>'GE'),
								array('measure'=>20,'competitor_name'=>'Philips')
							);
		}
		elseif($search_date=='Week4 (2017-09-25 to 2017-10-01)')
		{
			$lost_reasons=array(
					array('measure'=>25,'reason_name'=>'Brand Value'),
					array('measure'=>25,'reason_name'=>'Product Features'),
					array('measure'=>50,'reason_name'=>'Price Issue')

				);
	    	$lost_comp=array(
							array('measure'=>75,'competitor_name'=>'GE'),
							array('measure'=>25,'competitor_name'=>'Philips')
						);
		}
		elseif($search_date=='Week3 (2017-09-18 to 2017-09-24)')
		{
			$lost_reasons=array(
						array('measure'=>75,'reason_name'=>'Brand Value'),
						array('measure'=>10,'reason_name'=>'Product Features'),
						array('measure'=>15,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>60,'competitor_name'=>'GE'),
								array('measure'=>40,'competitor_name'=>'Philips')
							);
		}
		elseif($search_date=='Week2 (2017-09-11 to 2017-09-17)')
		{
			$lost_reasons=array(
						array('measure'=>25,'reason_name'=>'Brand Value'),
						array('measure'=>25,'reason_name'=>'Product Features'),
						array('measure'=>50,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>50,'competitor_name'=>'GE'),
								array('measure'=>50,'competitor_name'=>'Philips')
							);
		}
		elseif($search_date=='Week1 (2017-09-04 to 2017-09-10)')
		{
			$lost_reasons=array(
						array('measure'=>25,'reason_name'=>'Brand Value'),
						array('measure'=>25,'reason_name'=>'Product Features'),
						array('measure'=>50,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>50,'competitor_name'=>'GE'),
								array('measure'=>50,'competitor_name'=>'Philips')
							);
		}
		elseif($search_date=='Week4 (2017-08-28 to 2017-09-03)')
		{
			$lost_reasons=array(
						array('measure'=>10,'reason_name'=>'Brand Value'),
						array('measure'=>10,'reason_name'=>'Product Features'),
						array('measure'=>10,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>20,'competitor_name'=>'GE'),
								array('measure'=>10,'competitor_name'=>'Philips')
							);
		}
		elseif($search_date=='Week3 (2017-08-21 to 2017-08-27)')
		{
			$lost_reasons=array(
						array('measure'=>10,'reason_name'=>'Brand Value'),
						array('measure'=>10,'reason_name'=>'Product Features'),
						array('measure'=>10,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>15,'competitor_name'=>'GE'),
								array('measure'=>15,'competitor_name'=>'Philips')
							);
		}
		elseif($search_date=='Week2 (2017-08-14 to 2017-08-20)')
		{
			$lost_reasons=array(
						array('measure'=>5,'reason_name'=>'Brand Value'),
						array('measure'=>3,'reason_name'=>'Product Features'),
						array('measure'=>2,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>5,'competitor_name'=>'GE'),
								array('measure'=>5,'competitor_name'=>'Philips')
							);
		}
		elseif($search_date=='Week1 (2017-08-07 to 2017-08-13)')
		{
			$lost_reasons=array(
						array('measure'=>5,'reason_name'=>'Brand Value'),
						array('measure'=>10,'reason_name'=>'Product Features'),
						array('measure'=>5,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>10,'competitor_name'=>'GE'),
								array('measure'=>10,'competitor_name'=>'Philips')
							);
		}
		elseif($search_date=='Week5 (2017-07-31 to 2017-08-06)')
		{
			$lost_reasons=array(
						array('measure'=>25,'reason_name'=>'Brand Value'),
						array('measure'=>10,'reason_name'=>'Product Features'),
						array('measure'=>5,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>20,'competitor_name'=>'GE'),
								array('measure'=>20,'competitor_name'=>'Philips')
							);
		}
		elseif($search_date=='Week4 (2017-07-24 to 2017-07-30)')
		{
			$lost_reasons=array(
						array('measure'=>15,'reason_name'=>'Brand Value'),
						array('measure'=>10,'reason_name'=>'Product Features'),
						array('measure'=>5,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>10,'competitor_name'=>'GE'),
								array('measure'=>20,'competitor_name'=>'Philips')
							);
		}
		elseif($search_date=='Week3 (2017-07-17 to 2017-07-23)')
		{
			$lost_reasons=array(
						array('measure'=>10,'reason_name'=>'Brand Value'),
						array('measure'=>10,'reason_name'=>'Product Features'),
						array('measure'=>10,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>15,'competitor_name'=>'GE'),
								array('measure'=>15,'competitor_name'=>'Philips')
							);
		}
		elseif($search_date=='Week2 (2017-07-10 to 2017-07-16)')
		{
			$lost_reasons=array(
						array('measure'=>5,'reason_name'=>'Brand Value'),
						array('measure'=>3,'reason_name'=>'Product Features'),
						array('measure'=>2,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>5,'competitor_name'=>'GE'),
								array('measure'=>5,'competitor_name'=>'Philips')
							);
		}
		elseif($search_date=='Week1 (2017-07-03 to 2017-07-09)')
		{
			$lost_reasons=array(
						array('measure'=>5,'reason_name'=>'Brand Value'),
						array('measure'=>10,'reason_name'=>'Product Features'),
						array('measure'=>5,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>10,'competitor_name'=>'GE'),
								array('measure'=>10,'competitor_name'=>'Philips')
							);
		}
		elseif($search_date=='Week4 (2017-06-26 to 2017-07-02)')
		{
			$lost_reasons=array(
						array('measure'=>15,'reason_name'=>'Brand Value'),
						array('measure'=>10,'reason_name'=>'Product Features'),
						array('measure'=>5,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>10,'competitor_name'=>'GE'),
								array('measure'=>20,'competitor_name'=>'Philips')
							);
		}
		elseif($search_date=='Week3 (2017-06-19 to 2017-06-25)')
		{
			$lost_reasons=array(
						array('measure'=>10,'reason_name'=>'Brand Value'),
						array('measure'=>10,'reason_name'=>'Product Features'),
						array('measure'=>10,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>15,'competitor_name'=>'GE'),
								array('measure'=>15,'competitor_name'=>'Philips')
							);
		}
		elseif($search_date=='Week2 (2017-06-12 to 2017-06-18)')
		{
			$lost_reasons=array(
						array('measure'=>5,'reason_name'=>'Brand Value'),
						array('measure'=>3,'reason_name'=>'Product Features'),
						array('measure'=>2,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>5,'competitor_name'=>'GE'),
								array('measure'=>5,'competitor_name'=>'Philips')
							);
		}
		elseif($search_date=='Week1 (2017-06-05 to 2017-06-11)')
		{
			$lost_reasons=array(
						array('measure'=>5,'reason_name'=>'Brand Value'),
						array('measure'=>10,'reason_name'=>'Product Features'),
						array('measure'=>5,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>10,'competitor_name'=>'GE'),
								array('measure'=>10,'competitor_name'=>'Philips')
							);
		}
		elseif($search_date=='Week5 (2017-05-29 to 2017-06-04)')
		{
			$lost_reasons=array(
						array('measure'=>25,'reason_name'=>'Brand Value'),
						array('measure'=>10,'reason_name'=>'Product Features'),
						array('measure'=>5,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>20,'competitor_name'=>'GE'),
								array('measure'=>20,'competitor_name'=>'Philips')
							);
		}
		elseif($search_date=='Week4 (2017-05-22 to 2017-05-28)')
		{
			$lost_reasons=array(
						array('measure'=>15,'reason_name'=>'Brand Value'),
						array('measure'=>10,'reason_name'=>'Product Features'),
						array('measure'=>5,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>10,'competitor_name'=>'GE'),
								array('measure'=>20,'competitor_name'=>'Philips')
							);
		}
		elseif($search_date=='Week3 (2017-05-15 to 2017-05-21)')
		{
			$lost_reasons=array(
						array('measure'=>10,'reason_name'=>'Brand Value'),
						array('measure'=>10,'reason_name'=>'Product Features'),
						array('measure'=>10,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>15,'competitor_name'=>'GE'),
								array('measure'=>15,'competitor_name'=>'Philips')
							);
		}
		elseif($search_date=='Week2 (2017-05-08 to 2017-05-14)')
		{
			$lost_reasons=array(
						array('measure'=>5,'reason_name'=>'Brand Value'),
						array('measure'=>3,'reason_name'=>'Product Features'),
						array('measure'=>2,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>5,'competitor_name'=>'GE'),
								array('measure'=>5,'competitor_name'=>'Philips')
							);
		}
		elseif($search_date=='Week1 (2017-05-01 to 2017-05-07)')
		{
			$lost_reasons=array(
						array('measure'=>5,'reason_name'=>'Brand Value'),
						array('measure'=>10,'reason_name'=>'Product Features'),
						array('measure'=>5,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>10,'competitor_name'=>'GE'),
								array('measure'=>10,'competitor_name'=>'Philips')
							);
		}
		elseif($search_date=='Week4 (2017-04-24 to 2017-04-30)')
		{
			$lost_reasons=array(
						array('measure'=>15,'reason_name'=>'Brand Value'),
						array('measure'=>10,'reason_name'=>'Product Features'),
						array('measure'=>5,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>10,'competitor_name'=>'GE'),
								array('measure'=>20,'competitor_name'=>'Philips')
							);
		}
		elseif($search_date=='Week3 (2017-04-17 to 2017-04-23)')
		{
			$lost_reasons=array(
						array('measure'=>10,'reason_name'=>'Brand Value'),
						array('measure'=>10,'reason_name'=>'Product Features'),
						array('measure'=>10,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>15,'competitor_name'=>'GE'),
								array('measure'=>15,'competitor_name'=>'Philips')
							);
		}
		elseif($search_date=='Week2 (2017-04-10 to 2017-04-16)')
		{
			$lost_reasons=array(
						array('measure'=>5,'reason_name'=>'Brand Value'),
						array('measure'=>3,'reason_name'=>'Product Features'),
						array('measure'=>2,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>5,'competitor_name'=>'GE'),
								array('measure'=>5,'competitor_name'=>'Philips')
							);
		}
		elseif($search_date=='Week1 (2017-04-03 to 2017-04-09)')
		{
			$lost_reasons=array(
						array('measure'=>5,'reason_name'=>'Brand Value'),
						array('measure'=>10,'reason_name'=>'Product Features'),
						array('measure'=>5,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>10,'competitor_name'=>'GE'),
								array('measure'=>10,'competitor_name'=>'Philips')
							);
		}	
    }
    if($searchFilters['vtime']=='w')
    {
    	$search_date=$x_category2;
    	if($search_date=='28Nov')
		{
			$lost_reasons=array(
						array('measure'=>5,'reason_name'=>'Brand Value'),
						array('measure'=>10,'reason_name'=>'Product Features'),
						array('measure'=>5,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>10,'competitor_name'=>'GE'),
								array('measure'=>10,'competitor_name'=>'Philips')
							);
		}
    	elseif($search_date=='27Nov')
		{
			$lost_reasons=array(
						array('measure'=>10,'reason_name'=>'Brand Value'),
						array('measure'=>10,'reason_name'=>'Product Features'),
						array('measure'=>20,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>20,'competitor_name'=>'GE'),
								array('measure'=>20,'competitor_name'=>'Philips')
							);
		}
    	elseif($search_date=='26Nov')
		{
			$lost_reasons=array(
						array('measure'=>10,'reason_name'=>'Brand Value'),
						array('measure'=>10,'reason_name'=>'Product Features'),
						array('measure'=>10,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>20,'competitor_name'=>'GE'),
								array('measure'=>10,'competitor_name'=>'Philips')
							);
		}
    	elseif($search_date=='25Nov')
		{
			$lost_reasons=array(
						array('measure'=>10,'reason_name'=>'Brand Value'),
						array('measure'=>10,'reason_name'=>'Product Features'),
						array('measure'=>30,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>25,'competitor_name'=>'GE'),
								array('measure'=>25,'competitor_name'=>'Philips')
							);
		}
    	elseif($search_date=='24Nov')
		{
			$lost_reasons=array(
						array('measure'=>5,'reason_name'=>'Brand Value'),
						array('measure'=>10,'reason_name'=>'Product Features'),
						array('measure'=>5,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>10,'competitor_name'=>'GE'),
								array('measure'=>10,'competitor_name'=>'Philips')
							);
		}
    	elseif($search_date=='23Nov')
		{
			$lost_reasons=array(
						array('measure'=>10,'reason_name'=>'Brand Value'),
						array('measure'=>10,'reason_name'=>'Product Features'),
						array('measure'=>20,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>20,'competitor_name'=>'GE'),
								array('measure'=>20,'competitor_name'=>'Philips')
							);
		}
    	elseif($search_date=='22Nov')
		{
			$lost_reasons=array(
						array('measure'=>10,'reason_name'=>'Brand Value'),
						array('measure'=>10,'reason_name'=>'Product Features'),
						array('measure'=>20,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>20,'competitor_name'=>'GE'),
								array('measure'=>20,'competitor_name'=>'Philips')
							);
		}
		elseif($search_date=='21Nov')
		{
			$lost_reasons=array(
						array('measure'=>10,'reason_name'=>'Brand Value'),
						array('measure'=>10,'reason_name'=>'Product Features'),
						array('measure'=>10,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>15,'competitor_name'=>'GE'),
								array('measure'=>15,'competitor_name'=>'Philips')
							);
		}
		elseif($search_date=='20Nov')
		{
			$lost_reasons=array(
						array('measure'=>5,'reason_name'=>'Brand Value'),
						array('measure'=>5,'reason_name'=>'Product Features'),
						array('measure'=>10,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>10,'competitor_name'=>'GE'),
								array('measure'=>10,'competitor_name'=>'Philips')
							);
		}
		elseif($search_date=='19Nov')
		{
			$lost_reasons=array(
						array('measure'=>15,'reason_name'=>'Brand Value'),
						array('measure'=>10,'reason_name'=>'Product Features'),
						array('measure'=>5,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>15,'competitor_name'=>'GE'),
								array('measure'=>15,'competitor_name'=>'Philips')
							);
		}
		elseif($search_date=='18Nov')
		{
			$lost_reasons=array(
						array('measure'=>10,'reason_name'=>'Brand Value'),
						array('measure'=>10,'reason_name'=>'Product Features'),
						array('measure'=>10,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>15,'competitor_name'=>'GE'),
								array('measure'=>15,'competitor_name'=>'Philips')
							);
		}
		elseif($search_date=='17Nov')
		{
			$lost_reasons=array(
						array('measure'=>10,'reason_name'=>'Brand Value'),
						array('measure'=>10,'reason_name'=>'Product Features'),
						array('measure'=>10,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>15,'competitor_name'=>'GE'),
								array('measure'=>15,'competitor_name'=>'Philips')
							);
		}
		elseif($search_date=='16Nov')
		{
			$lost_reasons=array(
						array('measure'=>10,'reason_name'=>'Brand Value'),
						array('measure'=>10,'reason_name'=>'Product Features'),
						array('measure'=>10,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>15,'competitor_name'=>'GE'),
								array('measure'=>15,'competitor_name'=>'Philips')
							);
		}
		elseif($search_date=='15Nov')
		{
			$lost_reasons=array(
						array('measure'=>30,'reason_name'=>'Brand Value'),
						array('measure'=>30,'reason_name'=>'Product Features'),
						array('measure'=>10,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>30,'competitor_name'=>'GE'),
								array('measure'=>40,'competitor_name'=>'Philips')
							);
		}
		elseif($search_date=='14Nov')
		{
			$lost_reasons=array(
						array('measure'=>10,'reason_name'=>'Brand Value'),
						array('measure'=>10,'reason_name'=>'Product Features'),
						array('measure'=>10,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>15,'competitor_name'=>'GE'),
								array('measure'=>15,'competitor_name'=>'Philips')
							);
		}
		elseif($search_date=='13Nov')
		{
			$lost_reasons=array(
						array('measure'=>10,'reason_name'=>'Brand Value'),
						array('measure'=>5,'reason_name'=>'Product Features'),
						array('measure'=>5,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>5,'competitor_name'=>'GE'),
								array('measure'=>15,'competitor_name'=>'Philips')
							);
		}
		elseif($search_date=='12Nov')
		{
			$lost_reasons=array(
						array('measure'=>10,'reason_name'=>'Brand Value'),
						array('measure'=>10,'reason_name'=>'Product Features'),
						array('measure'=>10,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>15,'competitor_name'=>'GE'),
								array('measure'=>15,'competitor_name'=>'Philips')
							);
		}
		elseif($search_date=='11Nov')
		{
			$lost_reasons=array(
						array('measure'=>10,'reason_name'=>'Brand Value'),
						array('measure'=>10,'reason_name'=>'Product Features'),
						array('measure'=>10,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>15,'competitor_name'=>'GE'),
								array('measure'=>15,'competitor_name'=>'Philips')
							);
		}
		elseif($search_date=='10Nov')
		{
			$lost_reasons=array(
						array('measure'=>10,'reason_name'=>'Brand Value'),
						array('measure'=>5,'reason_name'=>'Product Features'),
						array('measure'=>5,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>5,'competitor_name'=>'GE'),
								array('measure'=>15,'competitor_name'=>'Philips')
							);
		}
		elseif($search_date=='9Nov')
		{
			$lost_reasons=array(
						array('measure'=>10,'reason_name'=>'Brand Value'),
						array('measure'=>20,'reason_name'=>'Product Features'),
						array('measure'=>10,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>20,'competitor_name'=>'GE'),
								array('measure'=>20,'competitor_name'=>'Philips')
							);
		}
		elseif($search_date=='8Nov')
		{
			$lost_reasons=array(
						array('measure'=>10,'reason_name'=>'Brand Value'),
						array('measure'=>25,'reason_name'=>'Product Features'),
						array('measure'=>15,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>25,'competitor_name'=>'GE'),
								array('measure'=>25,'competitor_name'=>'Philips')
							);
		}
		elseif($search_date=='7Nov')
		{
			$lost_reasons=array(
						array('measure'=>5,'reason_name'=>'Brand Value'),
						array('measure'=>2,'reason_name'=>'Product Features'),
						array('measure'=>3,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>5,'competitor_name'=>'GE'),
								array('measure'=>5,'competitor_name'=>'Philips')
							);
		}
		elseif($search_date=='6Nov')
		{
			$lost_reasons=array(
						array('measure'=>5,'reason_name'=>'Brand Value'),
						array('measure'=>3,'reason_name'=>'Product Features'),
						array('measure'=>2,'reason_name'=>'Price Issue')

					);
		    $lost_comp=array(
								array('measure'=>5,'competitor_name'=>'GE'),
								array('measure'=>5,'competitor_name'=>'Philips')
							);
		}

    }
    //$lost_reasons=$CI->Report_model->get_fo_by_closed_lost_reason($role_id,$userProducts,$userLocations,$x_category2,$searchFilters);
    /*$lost_reasons=array(
							array('measure'=>15,'reason_name'=>'Brand Value'),
							array('measure'=>10,'reason_name'=>'Product Features'),
							array('measure'=>5,'reason_name'=>'Price Issue')

						);
    //$lost_comp=$CI->Report_model->get_fo_by_closed_lost_comp($role_id,$userProducts,$userLocations,$x_category2,$searchFilters);
    $lost_comp=array(
						array('measure'=>20,'competitor_name'=>'GE'),
						array('measure'=>10,'competitor_name'=>'Philips')
					);*/
	$series=array();
	foreach ($lost_comp as $row)
	{
		$series[]=array('name'=>$row['competitor_name'],'data'=>array((int)$row['measure'],''));
	}
	$xAxisCategory[]='Lost Competitors';
	foreach ($lost_reasons as $row)
	{
		$series[]=array('name'=>$row['reason_name'],'data'=>array('',(int)$row['measure']));
	}
	$xAxisCategory[]='Lost Reason';
	$chart3Data = array('xAxisCategory3'=>$xAxisCategory,'yAxisCategory3'=>$yAxisCategory,'chart3Series'=>$series,'xAxisLable3'=>'Closed Lost');
	$chart3Data = json_encode($chart3Data);
	return $chart3Data;
}
function staticGetOpportunityLostChart1DataReason($reporty_by=1,$searchFilters)
{
	$piedata= array();

	/*foreach($opp_reasons as $row){
		   $resData = array('name' => $row['name'], 'y'=>(float)valueInLakhs($row['total_count'],2));
		   $piedata[] = $resData;
		}*/
	if($searchFilters['vtime']=='y')
	{
		$piedata[]=array('name' => "Product Features", 'y'=>75);
		$piedata[]=array('name' => "Pricing Issue", 'y'=>117);
		$piedata[]=array('name' => "Technically Rejected", 'y'=>65);
		$piedata[]=array('name' => "Competitor Brand Value", 'y'=>73);
	}
	if($searchFilters['vtime']=='q')
	{
		if($searchFilters['duration']=='2017-10-02to2017-12-31')
		{
			$piedata[]=array('name' => "Product Features", 'y'=>20);
			$piedata[]=array('name' => "Pricing Issue", 'y'=>30);
			$piedata[]=array('name' => "Technically Rejected", 'y'=>20);
			$piedata[]=array('name' => "Competitor Brand Value", 'y'=>30);
		}
		elseif($searchFilters['duration']=='2017-07-03to2017-10-01')
		{
			$piedata[]=array('name' => "Product Features", 'y'=>25);
			$piedata[]=array('name' => "Pricing Issue", 'y'=>57);
			$piedata[]=array('name' => "Technically Rejected", 'y'=>15);
			$piedata[]=array('name' => "Competitor Brand Value", 'y'=>20);
		}
		elseif($searchFilters['duration']=='2017-04-03to2017-07-02')
		{
			$piedata[]=array('name' => "Product Features", 'y'=>30);
			$piedata[]=array('name' => "Pricing Issue", 'y'=>30);
			$piedata[]=array('name' => "Technically Rejected", 'y'=>30);
			$piedata[]=array('name' => "Competitor Brand Value", 'y'=>23);
		}
	}
	if($searchFilters['vtime']=='m')
	{
		if($searchFilters['duration_text']=='Nov-17')
		{
			$piedata[]=array('name' => "Product Features", 'y'=>5);
			$piedata[]=array('name' => "Pricing Issue", 'y'=>10);
			$piedata[]=array('name' => "Technically Rejected", 'y'=>10);
			$piedata[]=array('name' => "Competitor Brand Value", 'y'=>10);
		}
		elseif($searchFilters['duration_text']=='Oct-17')
		{
			$piedata[]=array('name' => "Product Features", 'y'=>10);
			$piedata[]=array('name' => "Pricing Issue", 'y'=>10);
			$piedata[]=array('name' => "Technically Rejected", 'y'=>5);
			$piedata[]=array('name' => "Competitor Brand Value", 'y'=>10);
		}
		elseif($searchFilters['duration_text']=='Sep-17')
		{
			$piedata[]=array('name' => "Product Features", 'y'=>15);
			$piedata[]=array('name' => "Pricing Issue", 'y'=>20);
			$piedata[]=array('name' => "Technically Rejected", 'y'=>5);
			$piedata[]=array('name' => "Competitor Brand Value", 'y'=>10);
		}
		elseif($searchFilters['duration_text']=='Aug-17')
		{
			$piedata[]=array('name' => "Product Features", 'y'=>5);
			$piedata[]=array('name' => "Pricing Issue", 'y'=>20);
			$piedata[]=array('name' => "Technically Rejected", 'y'=>5);
			$piedata[]=array('name' => "Competitor Brand Value", 'y'=>5);
		}
		elseif($searchFilters['duration_text']=='Jul-17')
		{
			$piedata[]=array('name' => "Product Features", 'y'=>10);
			$piedata[]=array('name' => "Pricing Issue", 'y'=>17);
			$piedata[]=array('name' => "Technically Rejected", 'y'=>5);
			$piedata[]=array('name' => "Competitor Brand Value", 'y'=>5);
		}
		elseif($searchFilters['duration_text']=='Jun-17')
		{
			$piedata[]=array('name' => "Product Features", 'y'=>10);
			$piedata[]=array('name' => "Pricing Issue", 'y'=>10);
			$piedata[]=array('name' => "Technically Rejected", 'y'=>10);
			$piedata[]=array('name' => "Competitor Brand Value", 'y'=>10);
		}
		elseif($searchFilters['duration_text']=='May-17')
		{
			$piedata[]=array('name' => "Product Features", 'y'=>10);
			$piedata[]=array('name' => "Pricing Issue", 'y'=>10);
			$piedata[]=array('name' => "Technically Rejected", 'y'=>10);
			$piedata[]=array('name' => "Competitor Brand Value", 'y'=>7);
		}
		elseif($searchFilters['duration_text']=='Apr-17')
		{
			$piedata[]=array('name' => "Product Features", 'y'=>10);
			$piedata[]=array('name' => "Pricing Issue", 'y'=>10);
			$piedata[]=array('name' => "Technically Rejected", 'y'=>10);
			$piedata[]=array('name' => "Competitor Brand Value", 'y'=>6);
		}
	}
	if($searchFilters['vtime']=='w')
	{
		if($searchFilters['duration']=='2017-11-27to2017-12-03')
		{
			$piedata[]=array('name' => "Product Features", 'y'=>1);
			$piedata[]=array('name' => "Pricing Issue", 'y'=>2);
			$piedata[]=array('name' => "Technically Rejected", 'y'=>4);
			$piedata[]=array('name' => "Competitor Brand Value", 'y'=>2);
		}
		elseif($searchFilters['duration']=='2017-11-20to2017-11-26')
		{
			$piedata[]=array('name' => "Product Features", 'y'=>1);
			$piedata[]=array('name' => "Pricing Issue", 'y'=>2);
			$piedata[]=array('name' => "Technically Rejected", 'y'=>2);
			$piedata[]=array('name' => "Competitor Brand Value", 'y'=>4);
		}
		elseif($searchFilters['duration']=='2017-11-13to2017-11-19')
		{
			$piedata[]=array('name' => "Product Features", 'y'=>1);
			$piedata[]=array('name' => "Pricing Issue", 'y'=>2);
			$piedata[]=array('name' => "Technically Rejected", 'y'=>2);
			$piedata[]=array('name' => "Competitor Brand Value", 'y'=>2);
		}
		elseif($searchFilters['duration']=='2017-11-06to2017-11-12')
		{
			$piedata[]=array('name' => "Product Features", 'y'=>1);
			$piedata[]=array('name' => "Pricing Issue", 'y'=>4);
			$piedata[]=array('name' => "Technically Rejected", 'y'=>2);
			$piedata[]=array('name' => "Competitor Brand Value", 'y'=>2);
		}
	}
	$chart1Data = array();
	$chart1Data []= array('name'=>'Value','colorByPoint'=>true,'data'=>$piedata);
	$year=get_current_fiancial_year();
	if(@$searchFilters['duration_text']!='')
	{
		$text= '('.@$searchFilters['duration_text'].' )';
	}
	else
	{
		$text=$year['name'];
	}
	$label="Opportunity Lost By Reasons".$text;
	$chart1DataReason = array('chart1Series'=>$chart1Data,'label'=>$label);
	return $chart1DataReason;

}
function staticGetOpportunityLostChart1DataCompetitor($reporty_by=1,$searchFilters)
{
	$piedata= array();
	/*foreach($opp_reasons as $row){
		   $resData = array('name' => $row['name'], 'y'=>(float)valueInLakhs($row['total_count'],2));
		   $piedata[] = $resData;
		}*/
	if($searchFilters['vtime']=='y')
	{
		$piedata[]=array('name' => "GE", 'y'=>122);
		$piedata[]=array('name' => "Philips", 'y'=>81);
		$piedata[]=array('name' => "Siemens", 'y'=>74);
		$piedata[]=array('name' => "BPL", 'y'=>53);
	}
	if($searchFilters['vtime']=='q')
	{
		if($searchFilters['duration']=='2017-10-02to2017-12-31')
		{
			$piedata[]=array('name' => "GE", 'y'=>50);
			$piedata[]=array('name' => "Philips", 'y'=>30);
			$piedata[]=array('name' => "Siemens", 'y'=>30);
			$piedata[]=array('name' => "BPL", 'y'=>25);
		}
		elseif($searchFilters['duration']=='2017-07-03to2017-10-01')
		{
			$piedata[]=array('name' => "GE", 'y'=>30);
			$piedata[]=array('name' => "Philips", 'y'=>20);
			$piedata[]=array('name' => "Siemens", 'y'=>14);
			$piedata[]=array('name' => "BPL", 'y'=>10);
		}
		elseif($searchFilters['duration']=='2017-04-03to2017-07-02')
		{
			$piedata[]=array('name' => "GE", 'y'=>42);
			$piedata[]=array('name' => "Philips", 'y'=>31);
			$piedata[]=array('name' => "Siemens", 'y'=>30);
			$piedata[]=array('name' => "BPL", 'y'=>18);
		}
	}
	if($searchFilters['vtime']=='m')
	{
		if($searchFilters['duration_text']=='Nov-17')
		{
			$piedata[]=array('name' => "GE", 'y'=>20);
			$piedata[]=array('name' => "Philips", 'y'=>10);
			$piedata[]=array('name' => "Siemens", 'y'=>10);
			$piedata[]=array('name' => "BPL", 'y'=>10);
		}
		elseif($searchFilters['duration_text']=='Oct-17')
		{
			$piedata[]=array('name' => "GE", 'y'=>20);
			$piedata[]=array('name' => "Philips", 'y'=>10);
			$piedata[]=array('name' => "Siemens", 'y'=>10);
			$piedata[]=array('name' => "BPL", 'y'=>10);
		}
		elseif($searchFilters['duration_text']=='Sep-17')
		{
			$piedata[]=array('name' => "GE", 'y'=>10);
			$piedata[]=array('name' => "Philips", 'y'=>10);
			$piedata[]=array('name' => "Siemens", 'y'=>7);
			$piedata[]=array('name' => "BPL", 'y'=>5);
		}
		elseif($searchFilters['duration_text']=='Aug-17')
		{
			$piedata[]=array('name' => "GE", 'y'=>10);
			$piedata[]=array('name' => "Philips", 'y'=>5);
			$piedata[]=array('name' => "Siemens", 'y'=>4);
			$piedata[]=array('name' => "BPL", 'y'=>3);
		}
		elseif($searchFilters['duration_text']=='Jul-17')
		{
			$piedata[]=array('name' => "GE", 'y'=>10);
			$piedata[]=array('name' => "Philips", 'y'=>5);
			$piedata[]=array('name' => "Siemens", 'y'=>3);
			$piedata[]=array('name' => "BPL", 'y'=>2);
		}
		elseif($searchFilters['duration_text']=='Jun-17')
		{
			$piedata[]=array('name' => "GE", 'y'=>10);
			$piedata[]=array('name' => "Philips", 'y'=>10);
			$piedata[]=array('name' => "Siemens", 'y'=>10);
			$piedata[]=array('name' => "BPL", 'y'=>6);
		}
		elseif($searchFilters['duration_text']=='May-17')
		{
			$piedata[]=array('name' => "GE", 'y'=>10);
			$piedata[]=array('name' => "Philips", 'y'=>10);
			$piedata[]=array('name' => "Siemens", 'y'=>10);
			$piedata[]=array('name' => "BPL", 'y'=>6);
		}
		elseif($searchFilters['duration_text']=='Apr-17')
		{
			$piedata[]=array('name' => "GE", 'y'=>22);
			$piedata[]=array('name' => "Philips", 'y'=>11);
			$piedata[]=array('name' => "Siemens", 'y'=>10);
			$piedata[]=array('name' => "BPL", 'y'=>6);
		}
	}
	if($searchFilters['vtime']=='w')
	{
		if($searchFilters['duration']=='2017-11-27to2017-12-03')
		{
			$piedata[]=array('name' => "GE", 'y'=>10);
			$piedata[]=array('name' => "Philips", 'y'=>4);
			$piedata[]=array('name' => "Siemens", 'y'=>1);
			$piedata[]=array('name' => "BPL", 'y'=>3);
		}
		elseif($searchFilters['duration']=='2017-11-20to2017-11-26')
		{
			$piedata[]=array('name' => "GE", 'y'=>10);
			$piedata[]=array('name' => "Philips", 'y'=>2);
			$piedata[]=array('name' => "Siemens", 'y'=>4);
			$piedata[]=array('name' => "BPL", 'y'=>2);
		}
		elseif($searchFilters['duration']=='2017-11-13to2017-11-19')
		{
			$piedata[]=array('name' => "GE", 'y'=>10);
			$piedata[]=array('name' => "Philips", 'y'=>2);
			$piedata[]=array('name' => "Siemens", 'y'=>3);
			$piedata[]=array('name' => "BPL", 'y'=>4);
		}
		elseif($searchFilters['duration']=='2017-11-06to2017-11-12')
		{
			$piedata[]=array('name' => "GE", 'y'=>10);
			$piedata[]=array('name' => "Philips", 'y'=>2);
			$piedata[]=array('name' => "Siemens", 'y'=>2);
			$piedata[]=array('name' => "BPL", 'y'=>1);
		}
	}

	$chart1Data = array();
	$chart1Data []= array('name'=>'Value','colorByPoint'=>true,'data'=>$piedata);
	$year=get_current_fiancial_year();
	if(@$searchFilters['duration_text']!='')
	{
		$text= '('.@$searchFilters['duration_text'].' )';
	}
	else
	{
		$text=$year['name'];
	}
	$label="Opportunity Lost By Competitors".$text;
	$chart1DataReason = array('chart1Series'=>$chart1Data,'label'=>$label);
	return $chart1DataReason;

}
function staticGetOpportunityLostChart2Data($lost_for,$report_by,$searchFilters)
{
	
    //fetching different groups from product category
    $CI = & get_instance();
	$xAxisLable = 'Zones';
	//print_r($searchFilters); exit;
    switch($report_by)
    {	
    	case 1:
    		$xAxisCategory=array("Central","East","West","North","North2","South1","South2","South3","MP&CG");
    		$c1Data=array();
    		if($searchFilters['vtime']=='y')
    		{
    			if($lost_for=='Pricing Issue')
			    {
			    	$series[]=array('name'=>'ANS','data'=>array(1,2,3,4,5,2,1,6,4));
			    	$series[]=array('name'=>'CRD','data'=>array(2,7,3,5,2,0,0,1,2));
			    	$series[]=array('name'=>'ESU','data'=>array(6,2,2,5,3,1,0,2,2));
			    	$series[]=array('name'=>'PMS','data'=>array(4,2,2,2,7,0,1,0,1));
			    	$series[]=array('name'=>'RAD','data'=>array(5,2,1,1,2,1,0,0,1));
			    	$series[]=array('name'=>'RMS','data'=>array(3,2,3,0,1,2,1,0,0));
			    }
			    if($lost_for=='Product Features')
			    {
			    	$series[]=array('name'=>'ANS','data'=>array(2,2,0,0,1,2,1,6,4));
			    	$series[]=array('name'=>'CRD','data'=>array(1,4,3,1,2,0,0,1,2));
			    	$series[]=array('name'=>'ESU','data'=>array(4,1,2,2,3,1,0,2,2));
			    	$series[]=array('name'=>'PMS','data'=>array(2,0,2,0,1,0,1,0,1));
			    	$series[]=array('name'=>'RAD','data'=>array(4,2,0,1,0,1,0,2,1));
			    	$series[]=array('name'=>'RMS','data'=>array(3,1,0,0,1,2,1,0,0));
			    }
			    if($lost_for=='Technically Rejected')
			    {
			    	$series[]=array('name'=>'ANS','data'=>array(1,2,0,0,1,2,1,1,4));
			    	$series[]=array('name'=>'CRD','data'=>array(3,1,3,0,2,0,0,0,2));
			    	$series[]=array('name'=>'ESU','data'=>array(1,3,2,3,3,1,0,2,2));
			    	$series[]=array('name'=>'PMS','data'=>array(2,0,1,0,1,0,1,0,1));
			    	$series[]=array('name'=>'RAD','data'=>array(1,2,0,1,2,4,0,1,0));
			    	$series[]=array('name'=>'RMS','data'=>array(3,1,0,0,1,2,1,0,0));
			    }
			    if($lost_for=='Competitor Brand Value')
			    {
			    	$series[]=array('name'=>'ANS','data'=>array(2,2,0,0,1,2,1,1,4));
			    	$series[]=array('name'=>'CRD','data'=>array(1,1,3,0,2,0,5,0,2));
			    	$series[]=array('name'=>'ESU','data'=>array(1,1,2,3,3,1,0,2,2));
			    	$series[]=array('name'=>'PMS','data'=>array(2,0,1,2,1,0,1,0,1));
			    	$series[]=array('name'=>'RAD','data'=>array(1,2,0,1,1,4,3,0,1));
			    	$series[]=array('name'=>'RMS','data'=>array(3,1,0,0,1,2,1,2,0));
			    }
    		}
		    if($searchFilters['vtime']=='q')
		    {
		    	if($searchFilters['duration']=='2017-10-02to2017-12-31')
		    	{
		    		if($lost_for=='Pricing Issue')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(1,0,1,0,0,0,1,0,1));
				    	$series[]=array('name'=>'CRD','data'=>array(2,0,1,0,2,0,0,1,1));
				    	$series[]=array('name'=>'ESU','data'=>array(1,0,2,0,1,1,0,1,2));
				    	$series[]=array('name'=>'PMS','data'=>array(0,2,0,0,1,0,1,0,1));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,1,1,0,1,0,0,1));
				    	$series[]=array('name'=>'RMS','data'=>array(1,0,1,0,1,0,1,0,0));
				    }
				    if($lost_for=='Product Features')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,1,0,1,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(1,0,1,1,0,0,0,1,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,1,1,0,0,1,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(1,0,0,0,1,0,1,0,1));
				    	$series[]=array('name'=>'RAD','data'=>array(0,1,0,1,0,1,0,1,1));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,1,0,1,0,0));
				    }
				    if($lost_for=='Technically Rejected')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(1,0,0,0,1,0,1,0,1));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,1,1,0,0,1,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,2,1,0,1,1,0,1,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,0,0,0,1));
				    	$series[]=array('name'=>'RAD','data'=>array(1,0,0,1,1,1,0,1,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,1,0,0));
				    }
				    if($lost_for=='Competitor Brand Value')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,1,0,0,0,1,0,1));
				    	$series[]=array('name'=>'CRD','data'=>array(2,0,1,0,0,0,0,1,1));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,1,0,1,1,0,1,2));
				    	$series[]=array('name'=>'PMS','data'=>array(0,2,0,1,0,0,3,0,1));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,1,1,0,1,0,0,1));
				    	$series[]=array('name'=>'RMS','data'=>array(3,0,1,0,2,0,1,0,0));
				    }
		    	}
		    	elseif($searchFilters['duration']=='2017-07-03to2017-10-01')
		    	{
		    		if($lost_for=='Pricing Issue')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(1,2,0,1,5,2,1,1,4));
				    	$series[]=array('name'=>'CRD','data'=>array(2,0,3,0,2,0,0,1,0));
				    	$series[]=array('name'=>'ESU','data'=>array(3,0,0,1,3,1,0,2,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,2,2,2,1,0,1,0,1));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,3,2,0,1,0,1,1));
				    	$series[]=array('name'=>'RMS','data'=>array(1,2,0,0,1,2,1,0,0));
				    }
				    if($lost_for=='Product Features')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,1,0,0,1,1,1,0,1));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,3,1,0,0,0,1,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,1,0,0,3,0,0,0,2));
				    	$series[]=array('name'=>'PMS','data'=>array(2,0,0,0,0,0,1,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,1,0,1,0,2,0));
				    	$series[]=array('name'=>'RMS','data'=>array(3,0,0,0,0,0,1,0,0));
				    }
				    if($lost_for=='Technically Rejected')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(1,2,0,0,0,0,1,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,1,3,0,2,0,0,0,2));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,0,1,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,1,0,0,0,1,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
				    if($lost_for=='Competitor Brand Value')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(1,2,0,0,0,0,1,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,1,2,0,2,0,0,0,1));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,2,0,1,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,1,0,1,0,0,0,1));
				    	$series[]=array('name'=>'RAD','data'=>array(1,0,0,0,0,1,1,1,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
		    	}
		    	elseif($searchFilters['duration']=='2017-04-03to2017-07-02')
		    	{
		    		if($lost_for=='Pricing Issue')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(1,0,0,0,0,0,1,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,2,0,0,1,0));
				    	$series[]=array('name'=>'ESU','data'=>array(3,0,4,0,2,0,0,1,2));
				    	$series[]=array('name'=>'PMS','data'=>array(0,2,0,0,0,0,1,0,3));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,1,1,0,2,0,0,1));
				    	$series[]=array('name'=>'RMS','data'=>array(1,0,1,0,1,0,1,0,0));
				    }
				    if($lost_for=='Product Features')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(1,0,1,0,0,0,1,0,1));
				    	$series[]=array('name'=>'CRD','data'=>array(2,0,1,0,2,0,0,1,1));
				    	$series[]=array('name'=>'ESU','data'=>array(1,0,2,0,1,1,0,1,2));
				    	$series[]=array('name'=>'PMS','data'=>array(0,2,0,0,1,0,1,0,1));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,1,1,0,1,0,0,1));
				    	$series[]=array('name'=>'RMS','data'=>array(1,0,1,0,1,0,1,0,0));
				    }
				    if($lost_for=='Technically Rejected')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(1,0,1,0,0,0,1,0,1));
				    	$series[]=array('name'=>'CRD','data'=>array(1,0,1,0,2,0,0,2,1));
				    	$series[]=array('name'=>'ESU','data'=>array(1,0,2,0,0,1,0,0,2));
				    	$series[]=array('name'=>'PMS','data'=>array(1,2,0,0,2,0,1,0,1));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,1,1,0,1,0,0,1));
				    	$series[]=array('name'=>'RMS','data'=>array(1,0,1,0,1,0,1,0,0));
				    }
				    if($lost_for=='Competitor Brand Value')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,3,0,0,0,1,0,1));
				    	$series[]=array('name'=>'CRD','data'=>array(2,0,0,0,2,0,0,1,1));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,2,0,0,1,0,1,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,2,0,0,1,0,0,0,1));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,1,0,1,0,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(2,0,0,0,1,0,1,0,0));
				    }
		    	}
		    }
		    if($searchFilters['vtime']=='m')
		    {
		    	if($searchFilters['duration_text']=='Nov-17')
		    	{
		    		if($lost_for=='Pricing Issue')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(1,0,1,0,0,0,1,0,1));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,0,0,0,1,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,2,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,1,0,1,0,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,1,0,0,0,0));
				    }
				    if($lost_for=='Product Features')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,2,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,0,0,0,1,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,1,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,1,0,0,0,0));
				    }
				    if($lost_for=='Technically Rejected')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,2,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,0,0,0,1,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,1,0,1,0,0,1,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(1,0,0,0,0,0,0,0,1));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,0,0,1,0,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,1,0,0,0,0));
				    }
				    if($lost_for=='Competitor Brand Value')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,1,0,0,0,0,1,0,1));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,0,0,0,1,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,1,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,1,2,0,0,0,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(1,0,0,0,1,0,0,0,0));
				    }
		    	}
		    	elseif($searchFilters['duration_text']=='Oct-17')
		    	{
		    		if($lost_for=='Pricing Issue')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,1,1,1,0,1,0,1));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,0,0,0,1,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(1,2,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,0,0,1,0,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
				    if($lost_for=='Product Features')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,1,0,0,0,0,0,0,1));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,0,0,0,1,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,1,0,1,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,2,0,0,0,1,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(1,0,0,0,1,0,0,0,0));
				    }
				    if($lost_for=='Technically Rejected')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,0,0,0,2,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,1,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,1,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,1,0,0,0,0));
				    }
				    if($lost_for=='Competitor Brand Value')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,1,0,0,0,0,1,0,1));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,0,0,0,1,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,1,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,1,2,0,0,0,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(1,0,0,0,1,0,0,0,0));
				    }
		    	}
		    	elseif($searchFilters['duration_text']=='Sep-17')
		    	{
		    		if($lost_for=='Pricing Issue')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,1,1,1,0,1,0,1));
				    	$series[]=array('name'=>'CRD','data'=>array(0,1,0,0,0,0,0,1,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,1,0,2,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(1,2,0,0,0,0,2,0,1));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,2,0,1,0,1,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
				    if($lost_for=='Product Features')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,1,0,0,0,0,0,0,1));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,2,0,0,0,1,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,1,0,2,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,1,0,1,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,2,0,0,0,1,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(1,0,0,0,1,0,0,0,0));
				    }
				    if($lost_for=='Technically Rejected')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,0,0,0,2,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,1,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,2,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
				    if($lost_for=='Competitor Brand Value')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,1,0,0,0,0,1,0,1));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,0,0,0,1,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,1,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,1,2,0,0,0,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(1,0,0,0,1,0,0,0,0));
				    }
		    	}
		    	elseif($searchFilters['duration_text']=='Aug-17')
		    	{
		    		if($lost_for=='Pricing Issue')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,1,0,1,0,1,0,1));
				    	$series[]=array('name'=>'CRD','data'=>array(0,1,0,0,0,0,0,1,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,1,0,2,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(1,1,0,0,0,0,2,0,1));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,3,0,1,0,1,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,1,0,0,0,0,0,0,0));
				    }
				    if($lost_for=='Product Features')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,1,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,1,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,1,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(1,0,0,1,0,0,0,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
				    if($lost_for=='Technically Rejected')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,2,0,0,0,2,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,1,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
				    if($lost_for=='Competitor Brand Value')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(2,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,2,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,1,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
		    	}
		    	elseif($searchFilters['duration_text']=='Jul-17')
		    	{
		    		if($lost_for=='Pricing Issue')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,0,0,0,2,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,2,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,2,0,0,0,0,1,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(2,0,0,1,0,1,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,1,0,2,0,0,3,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
				    if($lost_for=='Product Features')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,2,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,2,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,1,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,0,2,0,0,3,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
				    if($lost_for=='Technically Rejected')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,2,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,1,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,0,2,0,0,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
				    if($lost_for=='Competitor Brand Value')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,2,0,0,0,0,0,1,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,1,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,0,1,0,0,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
		    	}
		    	elseif($searchFilters['duration_text']=='Jun-17')
		    	{
		    		if($lost_for=='Pricing Issue')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,2,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,1,0,0,0,0,2,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,1,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,1,0,0,0,0,3,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
				    if($lost_for=='Product Features')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,2,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,0,0,0,0,1));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,0,0,2,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,1,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,0,1,0,0,3,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
				    if($lost_for=='Technically Rejected')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,1,0,0,0,2));
				    	$series[]=array('name'=>'ESU','data'=>array(0,2,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,0,2,0,0,3,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
				    if($lost_for=='Competitor Brand Value')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,2,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(3,0,0,0,0,1,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,0,2,0,0,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,2,0,0,0,0));
				    }
		    	}
		    	elseif($searchFilters['duration_text']=='May-17')
		    	{
		    		if($lost_for=='Pricing Issue')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,1,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(2,0,0,0,0,1,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,3,0,0,0,0,3,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
				    if($lost_for=='Product Features')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,1,0,0,0,2));
				    	$series[]=array('name'=>'ESU','data'=>array(0,2,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,0,2,0,0,3,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
				    if($lost_for=='Technically Rejected')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,2,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,0,0,0,0,1));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,0,0,2,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,1,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,0,1,0,0,3,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
				    if($lost_for=='Competitor Brand Value')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,4,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(3,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
		    	}
		    	elseif($searchFilters['duration_text']=='Apr-17')
		    	{
		    		if($lost_for=='Pricing Issue')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,2,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,0,0,0,0,1));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,0,0,2,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,1,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,0,1,0,0,3,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
				    if($lost_for=='Product Features')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,0,0,0,0,2));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(1,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,0,2,2,0,3,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
				    if($lost_for=='Technically Rejected')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,1,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(2,0,0,0,0,1,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,3,0,0,0,0,3,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
				    if($lost_for=='Competitor Brand Value')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(3,0,0,0,0,3,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
		    	}
		    }
		    if($searchFilters['vtime']=='w')
		    {
		    	if($searchFilters['duration']=='2017-11-27to2017-12-03')
		    	{
		    		if($lost_for=='Pricing Issue')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,2,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
				    if($lost_for=='Product Features')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,1,0,0,0,0));
				    }
				    if($lost_for=='Technically Rejected')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,2,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,1,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(1,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
				    if($lost_for=='Competitor Brand Value')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,1,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(1,0,0,0,0,0,0,0,0));
				    }
		    	}
		    	elseif($searchFilters['duration']=='2017-11-20to2017-11-26')
		    	{
		    		if($lost_for=='Pricing Issue')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,0,2,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
				    if($lost_for=='Product Features')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,1,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
				    if($lost_for=='Technically Rejected')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(1,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,1,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
				    if($lost_for=='Competitor Brand Value')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,1,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,0,0,0,2,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(1,0,0,0,0,0,0,0,0));
				    }
		    	}
		    	elseif($searchFilters['duration']=='2017-11-13to2017-11-19')
		    	{
		    		if($lost_for=='Pricing Issue')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,0,2,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
				    if($lost_for=='Product Features')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,1,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
				    if($lost_for=='Technically Rejected')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(1,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,1,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
				    if($lost_for=='Competitor Brand Value')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,1,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,0,0,0,2,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(1,0,0,0,0,0,0,0,0));
				    }
		    	}
		    	elseif($searchFilters['duration']=='2017-11-06to2017-11-12')
		    	{
		    		if($lost_for=='Pricing Issue')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,0,2,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(2,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
				    if($lost_for=='Product Features')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,1,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
				    if($lost_for=='Technically Rejected')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(1,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,1,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
				    if($lost_for=='Competitor Brand Value')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,0,0,0,2,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
		    	}
		    }
		    /*$xAxisCategory[]=$r['location'];
			$series[]=array('name'=>$pg['name'],'data'=>$c1Data);*/
		    break;
		case 2:
		    $xAxisCategory=array("Central","East","West","North","North2","South1","South2","South3","MP&CG");
    		$c1Data=array();
    		if($searchFilters['vtime']=='y')
    		{
    			if($lost_for=='GE')
			    {
			    	$series[]=array('name'=>'ANS','data'=>array(3,2,3,4,5,2,1,6,4));
			    	$series[]=array('name'=>'CRD','data'=>array(2,7,3,2,2,0,0,1,2));
			    	$series[]=array('name'=>'ESU','data'=>array(6,2,2,5,1,1,0,2,2));
			    	$series[]=array('name'=>'PMS','data'=>array(2,3,2,3,7,0,1,0,1));
			    	$series[]=array('name'=>'RAD','data'=>array(5,2,1,1,2,1,0,3,1));
			    	$series[]=array('name'=>'RMS','data'=>array(3,2,3,0,1,7,1,0,0));
			    }
			    if($lost_for=='Philips')
			    {
			    	$series[]=array('name'=>'ANS','data'=>array(2,2,0,3,1,2,1,6,4));
			    	$series[]=array('name'=>'CRD','data'=>array(1,4,3,1,2,0,0,1,2));
			    	$series[]=array('name'=>'ESU','data'=>array(4,1,3,2,3,1,0,2,2));
			    	$series[]=array('name'=>'PMS','data'=>array(2,0,2,0,1,0,1,0,1));
			    	$series[]=array('name'=>'RAD','data'=>array(4,2,0,1,0,1,0,2,1));
			    	$series[]=array('name'=>'RMS','data'=>array(3,1,0,2,1,2,1,0,0));
			    }
			    if($lost_for=='Siemens')
			    {
			    	$series[]=array('name'=>'ANS','data'=>array(1,2,0,0,1,2,1,1,4));
			    	$series[]=array('name'=>'CRD','data'=>array(3,1,3,0,2,0,4,0,2));
			    	$series[]=array('name'=>'ESU','data'=>array(1,3,2,3,3,1,0,2,2));
			    	$series[]=array('name'=>'PMS','data'=>array(2,0,1,2,1,0,1,0,1));
			    	$series[]=array('name'=>'RAD','data'=>array(1,2,0,1,2,4,0,1,0));
			    	$series[]=array('name'=>'RMS','data'=>array(3,1,0,3,1,2,1,0,0));
			    }
			    if($lost_for=='BPL')
			    {
			    	$series[]=array('name'=>'ANS','data'=>array(2,0,0,1,0,2,1,1,1));
			    	$series[]=array('name'=>'CRD','data'=>array(1,2,0,0,2,0,5,0,2));
			    	$series[]=array('name'=>'ESU','data'=>array(1,0,2,3,3,1,0,1,2));
			    	$series[]=array('name'=>'PMS','data'=>array(2,0,1,2,1,0,1,0,1));
			    	$series[]=array('name'=>'RAD','data'=>array(1,2,0,1,0,1,0,0,1));
			    	$series[]=array('name'=>'RMS','data'=>array(0,1,0,1,0,2,1,2,0));
			    }
    		}
		    if($searchFilters['vtime']=='q')
		    {
		    	if($searchFilters['duration']=='2017-10-02to2017-12-31')
		    	{
		    		if($lost_for=='GE')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(1,1,0,1,0,2,1,0,1));
				    	$series[]=array('name'=>'CRD','data'=>array(2,0,2,0,1,0,3,1,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,1,2,0,1,3,0,2,2));
				    	$series[]=array('name'=>'PMS','data'=>array(1,4,0,3,4,0,1,1,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,2,1,0,1,0,1,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,1,1,0,1,0,1,0,0));
				    }
				    if($lost_for=='Philips')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(1,2,1,1,0,1,1,0,1));
				    	$series[]=array('name'=>'CRD','data'=>array(2,0,0,0,1,0,0,1,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,2,1,0,1,1,0,2,1));
				    	$series[]=array('name'=>'PMS','data'=>array(1,0,0,0,1,0,1,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,1,0,1,0,1,0,1,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,1,0,1,0,1,0,0));
				    }
				    if($lost_for=='Siemens')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(1,1,0,1,0,2,1,0,1));
				    	$series[]=array('name'=>'CRD','data'=>array(2,0,0,0,1,0,0,1,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,1,2,0,1,1,0,2,2));
				    	$series[]=array('name'=>'PMS','data'=>array(1,2,0,0,0,0,1,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,1,0,1,0,1,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,1,0,1,0,1,0,0));
				    }
				    if($lost_for=='BPL')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(1,0,1,1,0,1,1,0,1));
				    	$series[]=array('name'=>'CRD','data'=>array(1,0,0,0,1,0,0,1,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,1,0,0,0,1,0,0,1));
				    	$series[]=array('name'=>'PMS','data'=>array(1,2,0,0,0,0,1,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(1,0,0,1,0,1,0,1,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,1,0,1,0,1,0,0));
				    }
		    	}
		    	elseif($searchFilters['duration']=='2017-07-03to2017-10-01')
		    	{
		    		if($lost_for=='GE')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(1,3,0,1,0,2,1,0,1));
				    	$series[]=array('name'=>'CRD','data'=>array(2,0,0,0,1,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,2,0,2,1,0,2,2));
				    	$series[]=array('name'=>'PMS','data'=>array(1,0,0,0,0,0,1,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,1,0,1,0,2,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,1,1,0,0,0,1,0,0));
				    }
				    if($lost_for=='Philips')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(1,0,1,1,0,1,1,0,1));
				    	$series[]=array('name'=>'CRD','data'=>array(1,0,0,0,1,0,0,1,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,1,0,0,0,0,0,0,1));
				    	$series[]=array('name'=>'PMS','data'=>array(1,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(1,0,0,1,0,1,0,1,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,1,0,0,0,1,0,0));
				    }
				    if($lost_for=='Siemens')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(1,0,1,2,0,1,0,0,1));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,1,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,1,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(1,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,0,0,0,0,2,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,1,0,0,0,0,0,0));
				    }
				    if($lost_for=='BPL')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(2,0,0,2,0,1,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,1,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,0,0,0,0,2,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
		    	}
		    	elseif($searchFilters['duration']=='2017-04-03to2017-07-02')
		    	{
		    		if($lost_for=='GE')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,1,1,0,2,1,0,1));
				    	$series[]=array('name'=>'CRD','data'=>array(3,1,0,0,1,0,1,1,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,3,2,1,1,0,2,1));
				    	$series[]=array('name'=>'PMS','data'=>array(1,3,0,0,2,0,2,0,3));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,1,1,0,1,0,1,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,1,1,0,1,0,0,0,1));
				    }
				    if($lost_for=='Philips')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,1,0,2,1,0,1));
				    	$series[]=array('name'=>'CRD','data'=>array(3,0,0,0,1,0,0,1,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,3,0,1,1,0,2,1));
				    	$series[]=array('name'=>'PMS','data'=>array(1,3,0,0,0,0,2,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,1,0,1,0,1,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,1,1,0,1,0,0,0,1));
				    }
				    if($lost_for=='Siemens')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(1,1,0,1,0,2,1,0,1));
				    	$series[]=array('name'=>'CRD','data'=>array(2,0,0,0,1,0,0,1,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,1,2,0,1,1,0,2,2));
				    	$series[]=array('name'=>'PMS','data'=>array(1,2,0,0,0,0,1,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,1,0,1,0,1,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,1,0,1,0,1,0,0));
				    }
				    if($lost_for=='BPL')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(1,0,1,2,0,1,0,0,1));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,1,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,1,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(1,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,0,1,0,1,3,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,2,0,0,0,0,0,0));
				    }
		    	}
		    }
		    if($searchFilters['vtime']=='m')
		    {
		    	if($searchFilters['duration_text']=='Nov-17')
		    	{
		    		if($lost_for=='GE')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(1,1,0,1,0,0,1,0,1));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,2,0,1,0,1,1,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,1,0,0,1,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(1,0,0,0,0,0,1,1,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,1,0,1,0,1,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,1,1,0,1,0,1,0,0));
				    }
				    if($lost_for=='Philips')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(1,0,0,0,0,1,0,0,1));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,1,0,0,1,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,2,1,0,0,0,0,0,1));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,0,1,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,1,0,0,0,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
				    if($lost_for=='Siemens')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,0,2,0,0,1));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,1,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,0,1,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(1,0,2,1,0,0,0,0,1));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
				    if($lost_for=='BPL')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,0,3,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,3,0,0,0,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
		    	}
		    	elseif($searchFilters['duration_text']=='Oct-17')
		    	{
		    		if($lost_for=='GE')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,0,1,0,0,1));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,1,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(2,0,0,3,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,0,1,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,1,0,0,0,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
				    if($lost_for=='Philips')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,0,2,0,0,1));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,1,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,0,1,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(1,0,2,1,0,0,0,0,1));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
				    if($lost_for=='Siemens')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,3,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,1,0,0,0,1,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(1,0,2,1,0,0,0,0,1));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
				    if($lost_for=='BPL')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,0,2,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,2,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,2,0,0,0,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
		    	}
		    	elseif($searchFilters['duration_text']=='Sep-17')
		    	{
		    		if($lost_for=='GE')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,0,1,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,1,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,4,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,0,1,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,1,0,0,0,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,2));
				    }
				    if($lost_for=='Philips')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,0,2,0,0,1));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,3,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,0,1,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,1,0,0,0,0,2));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
				    if($lost_for=='Siemens')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,1,0,0,0,1,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(1,0,5,1,0,0,0,0,1));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
				    if($lost_for=='BPL')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,3,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,1,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,0,0,0,0,2,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
		    	}
		    	elseif($searchFilters['duration_text']=='Aug-17')
		    	{
		    		if($lost_for=='GE')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,1,0,0,0,1,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(1,0,5,1,0,0,0,0,1));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
				    if($lost_for=='Philips')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,5,0,0,0,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
				    if($lost_for=='Siemens')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(3,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
				    if($lost_for=='BPL')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,1,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,1,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
		    	}
		    	elseif($searchFilters['duration_text']=='Jul-17')
		    	{
		    		if($lost_for=='GE')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,0,0,1,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,6,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,0,1,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,1,0,0,0,0,1));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
				    if($lost_for=='Philips')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,5,0,0,0,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
				    if($lost_for=='Siemens')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,4,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
				    if($lost_for=='BPL')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,1,0,0,0,0,0,1,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,0,1,0,0,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
		    	}
		    	elseif($searchFilters['duration_text']=='Jun-17')
		    	{
		    		if($lost_for=='GE')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,0,0,1,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,2,0,0,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,4,0,0,0,0,1,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,1,0,0,0,0,1));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
				    if($lost_for=='Philips')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,0,0,0,5,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,5,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
				    if($lost_for=='Siemens')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,0,0,1,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,0,3,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,3,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
				    if($lost_for=='BPL')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,1,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,0,0,0,1,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,2,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,0,1,0,0,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
		    	}
		    	elseif($searchFilters['duration_text']=='May-17')
		    	{
		    		if($lost_for=='GE')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(3,0,0,0,0,0,1,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,2,0,0,0,2,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,5,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,4,0,0,0,0,1,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,1,0,0,0,0,1));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
				    if($lost_for=='Philips')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,5,0,0,0,3,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,2,0,0,0,0));
				    }
				    if($lost_for=='Siemens')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,0,0,2,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,1,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,0,3,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,3,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,1,0,0));
				    }
				    if($lost_for=='BPL')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,2,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,0,0,0,5,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,2,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,0,1,0,0,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
		    	}
		    	elseif($searchFilters['duration_text']=='Apr-17')
		    	{
		    		if($lost_for=='GE')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,2,0,0,1,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,2,0,0,0,2,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,5,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,2,0,0,0,0,1,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,3,1,0,0,0,0,1));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
				    if($lost_for=='Philips')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,0,0,2,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,1,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,0,3,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,3,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,1,0,0));
				    }
				    if($lost_for=='Siemens')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,2,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,0,0,0,5,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,2,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,0,1,0,0,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
				    if($lost_for=='BPL')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,5,0,0,0,3,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,2,0,0,0,0));
				    }
		    	}
		    }
		    if($searchFilters['vtime']=='w')
		    {
		    	if($searchFilters['duration']=='2017-11-27to2017-12-03')
		    	{
		    		if($lost_for=='GE')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,0,2,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,2,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(2,0,0,0,0,0,0,2,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,2,0,0,0,0,0));
				    }
				    if($lost_for=='Philips')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,1,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,0,0,0,3,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
				    if($lost_for=='Siemens')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(1,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
				    if($lost_for=='BPL')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,1,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,0,0,0,1,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(1,0,0,0,0,0,0,0,0));
				    }
		    	}
		    	elseif($searchFilters['duration']=='2017-11-20to2017-11-26')
		    	{
		    		if($lost_for=='GE')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(2,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,0,2,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(2,0,0,0,0,0,0,2,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,2,0,0,0,0,0));
				    }
				    if($lost_for=='Philips')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,2,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
				    if($lost_for=='Siemens')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(1,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,3,0,0,0,0,0,0));
				    }
				    if($lost_for=='BPL')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,1,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,0,0,0,1,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
		    	}
		    	elseif($searchFilters['duration']=='2017-11-13to2017-11-19')
		    	{
		    		if($lost_for=='GE')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,0,2,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,2,0,0,2,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(2,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,0,0,0,2,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
				    if($lost_for=='Philips')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,2,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
				    if($lost_for=='Siemens')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(1,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,2,0,0,0,0,0,0));
				    }
				    if($lost_for=='BPL')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,1,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,0,0,0,1,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(2,0,0,0,0,0,0,0,0));
				    }
		    	}
		    	elseif($searchFilters['duration']=='2017-11-06to2017-11-12')
		    	{
		    		if($lost_for=='GE')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(2,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,0,2,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(2,0,0,0,0,0,0,2,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,2,0,0,0,0,0));
				    }
				    if($lost_for=='Philips')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,2,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
				    if($lost_for=='Siemens')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(1,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,1,0,0,0,0,0,0));
				    }
				    if($lost_for=='BPL')
				    {
				    	$series[]=array('name'=>'ANS','data'=>array(0,1,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'CRD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'ESU','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'PMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RAD','data'=>array(0,0,0,0,0,0,0,0,0));
				    	$series[]=array('name'=>'RMS','data'=>array(0,0,0,0,0,0,0,0,0));
				    }
		    	}
		    }
		    break;
	}

	$chart1Data = array('xAxisCategory'=>$xAxisCategory,'chart2Series'=>$series,'xAxisLable'=>$xAxisLable);
	$chart1Data = json_encode($chart1Data);
	return $chart1Data;
}
function staticGetOpportunityLostChart3Data($lost_for,$region,$segment,$report_by,$searchFilters)
{
	/*$c1Data1 = array(
						 array('y'=>5),
						 array('y'=>8),
						 array('y'=>6),
						 array('y'=>7),
						 array('y'=>2)
					);
		$xAxisCategory = array("Product1","Product2","Product3","Product4","Product5");*/
	$xAxisLable = 'Products';
	$xAxisCategory=array();
	$c1Data1=array();
	$series = array();
	$CI = & get_instance();
	switch($report_by)
	{   
		case 1:
			$xAxisCategory=array("NARAYANA MEDICAL COLLEGE & GENERAL","SHRIKRISHNA HEALTHCARE ","NRI HOSPITAL");
    		$c1Data=array();
			if($searchFilters['vtime']=='y')
			{
				if($lost_for=='Pricing Issue')
				{
					if($region=='Central')
					{
						if($segment=='ANS')
						{
							$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
						}
						if($segment=='CRD')
						{
							$series[]=array('name'=>'F303-000108-0','data'=>array(0,1,0));
					    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(1,0,0));
						}
						if($segment=='ESU')
						{
							$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,2));
					    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(2,0,0));
					    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(2,0,0));
						}
						if($segment=='PMS')
						{
							$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(1,0,1));
					    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,1,0));
					    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,1,0));
						}
						if($segment=='RAD')
						{
							$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,2));
					    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,2));
					    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,1));
						}
						if($segment=='RMS')
						{
							$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,3,0));
					    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
						}
					}
					if($region=='East')
					{
						if($segment=='ANS')
						{
							$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(1,0,0));
						}
						if($segment=='CRD')
						{
							$series[]=array('name'=>'F303-000108-0','data'=>array(4,3,0));
					    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
						}
						if($segment=='ESU')
						{
							$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,2,0));
					    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
						}
						if($segment=='PMS')
						{
							$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,1));
					    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,1,0));
						}
						if($segment=='RAD')
						{
							$series[]=array('name'=>'F303-000274-2','data'=>array(2,0,0));
					    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
					    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
						}
						if($segment=='RMS')
						{
							$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,0,1));
					    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,1));
						}
					}
					if($region=='West')
					{
						if($segment=='ANS')
						{
							$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,3,0));
					    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
						}
						if($segment=='CRD')
						{
							$series[]=array('name'=>'F303-000108-0','data'=>array(3,0,0));
					    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
						}
						if($segment=='ESU')
						{
							$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(1,1,0));
						}
						if($segment=='PMS')
						{
							$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(2,0,0));
					    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,0,0));
						}
						if($segment=='RAD')
						{
							$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F303-000018-0','data'=>array(1,0,0));
					    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
						}
						if($segment=='RMS')
						{
							$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,1,0));
					    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,1,0));
					    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,1,0));
						}
					}
					if($region=='North')
					{
						if($segment=='ANS')
						{
							$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,1,0));
					    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,1,0));
					    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,1));
						}
						if($segment=='CRD')
						{
							$series[]=array('name'=>'F303-000108-0','data'=>array(2,3,0));
					    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
						}
						if($segment=='ESU')
						{
							$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,1));
					    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,2,0));
					    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(2,0,0));
						}
						if($segment=='PMS')
						{
							$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,2,0));
					    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,0,0));
						}
						if($segment=='RAD')
						{
							$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
					    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,1));
						}
					}
					if($region=='North2')
					{
						if($segment=='ANS')
						{
							$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(4,1,0));
						}
						if($segment=='CRD')
						{
							$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,2,0));
					    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
						}
						if($segment=='ESU')
						{
							$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(3,0,0));
					    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
						}
						if($segment=='PMS')
						{
							$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(2,5,0));
					    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,0,0));
						}
						if($segment=='RAD')
						{
							$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F303-000018-0','data'=>array(1,0,0));
					    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,1));
						}
						if($segment=='RMS')
						{
							$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,1,0));
						}
					}
					if($region=='South1')
					{
						if($segment=='ANS')
						{
							$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(1,1,0));
						}
						if($segment=='ESU')
						{
							$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,1,0));
						}
						if($segment=='RAD')
						{
							$series[]=array('name'=>'F303-000274-2','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
					    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
						}
						if($segment=='RMS')
						{
							$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,1,1));
					    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
						}
					}
					if($region=='South2')
					{
						if($segment=='ANS')
						{
							$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,1));
					    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
						}
						if($segment=='PMS')
						{
							$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,1,0));
						}
						if($segment=='RMS')
						{
							$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
						}
					}
					if($region=='South3')
					{
						if($segment=='ANS')
						{
							$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(4,0,0));
						}
						if($segment=='CRD')
						{
							$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,1));
					    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
						}
						if($segment=='ESU')
						{
							$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(1,1,0));
						}
					}
					if($region=='MP')
					{
						if($segment=='ANS')
						{
							$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,0,1));
					    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,1,0));
						}
						if($segment=='CRD')
						{
							$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,2,0));
					    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
						}
						if($segment=='ESU')
						{
							$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(1,1,0));
						}
						if($segment=='PMS')
						{
							$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,1,0));
						}
						if($segment=='RAD')
						{
							$series[]=array('name'=>'F303-000274-2','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
					    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
						}
					}
				}
				if($lost_for=='Product Features')
				{
					if($region=='Central')
					{
						if($segment=='ANS')
						{
							$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,2));
					    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
						}
						if($segment=='CRD')
						{
							$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,1));
					    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
						}
						if($segment=='ESU')
						{
							$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,3,0));
					    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,1,0));
						}
						if($segment=='PMS')
						{
							$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,1));
					    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,1,0));
						}
						if($segment=='RAD')
						{
							$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F303-000018-0','data'=>array(2,1,0));
					    	$series[]=array('name'=>'M54-900-1001','data'=>array(1,0,0));
						}
						if($segment=='RMS')
						{
							$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,0,3));
					    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
						}
					}
					if($region=='East')
					{
						if($segment=='ANS')
						{
							$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(1,0,0));
						}
						if($segment=='CRD')
						{
							$series[]=array('name'=>'F303-000108-0','data'=>array(4,0,0));
					    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
						}
						if($segment=='ESU')
						{
							$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
						}
						if($segment=='RAD')
						{
							$series[]=array('name'=>'F303-000274-2','data'=>array(2,0,0));
					    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
					    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
						}
						if($segment=='RMS')
						{
							$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,1,0));
						}
					}
					if($region=='West')
					{
						if($segment=='CRD')
						{
							$series[]=array('name'=>'F303-000108-0','data'=>array(3,0,0));
					    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
						}
						if($segment=='ESU')
						{
							$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,2));
					    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
						}
						if($segment=='PMS')
						{
							$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,1));
					    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,1,0));
						}
					}
					if($region=='North')
					{
						if($segment=='CRD')
						{
							$series[]=array('name'=>'F303-000108-0','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
						}
						if($segment=='ESU')
						{
							$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,1,0));
						}
						if($segment=='RAD')
						{
							$series[]=array('name'=>'F303-000274-2','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
					    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
						}
					}
					if($region=='North2')
					{
						if($segment=='ANS')
						{
							$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,1));
					    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
						}
						if($segment=='CRD')
						{
							$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,2,0));
					    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
						}
						if($segment=='ESU')
						{
							$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,3,0));
					    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
						}
						if($segment=='PMS')
						{
							$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,1,0));
						}
						if($segment=='RMS')
						{
							$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,1,0));
					    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
						}
					}
					if($region=='South1')
					{
						if($segment=='ANS')
						{
							$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(2,0,0));
					    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
						}
						if($segment=='ESU')
						{
							$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
						}
						if($segment=='RAD')
						{
							$series[]=array('name'=>'F303-000274-2','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
					    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
						}
						if($segment=='RMS')
						{
							$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,2));
						}
					}
					if($region=='South2')
					{
						if($segment=='ANS')
						{
							$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
						}
						if($segment=='PMS')
						{
							$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,1));
					    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,0,0));
						}
						if($segment=='RMS')
						{
							$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,1,0));
						}
					}
					if($region=='South3')
					{
						if($segment=='ANS')
						{
							$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,1));
					    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(4,1,0));
						}
						if($segment=='CRD')
						{
							$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,1));
					    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
						}
						if($segment=='ESU')
						{
							$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(1,1,0));
						}
						if($segment=='RAD')
						{
							$series[]=array('name'=>'F303-000274-2','data'=>array(2,0,0));
					    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
					    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
						}
					}
					if($region=='MP')
					{
						if($segment=='ANS')
						{
							$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(4,0,0));
						}
						if($segment=='CRD')
						{
							$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,1));
					    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,1));
						}
						if($segment=='ESU')
						{
							$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,2));
					    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
						}
						if($segment=='PMS')
						{
							$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,1,0));
						}
						if($segment=='RAD')
						{
							$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F303-000018-0','data'=>array(1,0,0));
					    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
						}
					}
				}
				if($lost_for=='Technically Rejected')
				{
					if($region=='Central')
					{
						if($segment=='ANS')
						{
							$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,1,0));
						}
						if($segment=='CRD')
						{
							$series[]=array('name'=>'F303-000108-0','data'=>array(3,0,0));
					    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
						}
						if($segment=='ESU')
						{
							$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
						}
						if($segment=='PMS')
						{
							$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(2,0,0));
					    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,0,0));
						}
						if($segment=='RAD')
						{
							$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,1,0));
					    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
						}
						if($segment=='RMS')
						{
							$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(2,0,0));
					    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,1,0));
						}
					}
					if($region=='East')
					{
						if($segment=='ANS')
						{
							$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(1,0,0));
						}
						if($segment=='CRD')
						{
							$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,1));
					    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
						}
						if($segment=='ESU')
						{
							$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(1,0,2));
					    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
						}
						if($segment=='RAD')
						{
							$series[]=array('name'=>'F303-000274-2','data'=>array(2,0,0));
					    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
					    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
						}
						if($segment=='RMS')
						{
							$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,1,0));
						}
					}
					if($region=='West')
					{
						if($segment=='CRD')
						{
							$series[]=array('name'=>'F303-000108-0','data'=>array(3,0,0));
					    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
						}
						if($segment=='ESU')
						{
							$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(1,1,0));
						}
						if($segment=='PMS')
						{
							$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,1));
					    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,0,0));
						}
					}
					if($region=='North')
					{
						if($segment=='ESU')
						{
							$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(3,0,0));
					    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
						}
						if($segment=='RAD')
						{
							$series[]=array('name'=>'F303-000274-2','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
					    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
						}
					}
					if($region=='North2')
					{
						if($segment=='ANS')
						{
							$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
						}
						if($segment=='CRD')
						{
							$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,1));
					    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
						}
						if($segment=='ESU')
						{
							$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,1));
					    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(1,1,0));
						}
						if($segment=='PMS')
						{
							$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,1));
					    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,0,0));
						}
						if($segment=='RAD')
						{
							$series[]=array('name'=>'F303-000274-2','data'=>array(2,0,0));
					    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
					    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
						}
						if($segment=='RMS')
						{
							$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,1,0));
					    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
						}
					}
					if($region=='South1')
					{
						if($segment=='ANS')
						{
							$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(2,0,0));
					    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
						}
						if($segment=='ESU')
						{
							$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
						}
						if($segment=='RAD')
						{
							$series[]=array('name'=>'F303-000274-2','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F303-000018-0','data'=>array(1,0,0));
					    	$series[]=array('name'=>'M54-900-1001','data'=>array(1,1,0));
						}
						if($segment=='RMS')
						{
							$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(2,0,0));
					    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
						}
					}
					if($region=='South2')
					{
						if($segment=='ANS')
						{
							$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
						}
						if($segment=='PMS')
						{
							$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,1,0));
						}
						if($segment=='RMS')
						{
							$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,1,0));
					    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
						}
					}
					if($region=='South3')
					{
						if($segment=='ANS')
						{
							$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
						}
						if($segment=='ESU')
						{
							$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(1,1,0));
						}
						if($segment=='RAD')
						{
							$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F303-000018-0','data'=>array(1,0,0));
					    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
						}
					}
					if($region=='MP')
					{
						if($segment=='ANS')
						{
							$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(4,0,0));
						}
						if($segment=='CRD')
						{
							$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,2,0));
					    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
						}
						if($segment=='ESU')
						{
							$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,1));
					    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(1,0,0));
						}
						if($segment=='PMS')
						{
							$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,1,0));
						}
					}
				}
				if($lost_for=='Competitor Brand Value')
				{
					if($region=='Central')
					{
						if($segment=='ANS')
						{
							$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,2));
					    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
						}
						if($segment=='CRD')
						{
							$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,1));
					    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
						}
						if($segment=='ESU')
						{
							$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
						}
						if($segment=='PMS')
						{
							$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(2,0,0));
					    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,0,0));
						}
						if($segment=='RAD')
						{
							$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,1,0));
					    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
						}
						if($segment=='RMS')
						{
							$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,3,0));
					    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
						}
					}
					if($region=='East')
					{
						if($segment=='ANS')
						{
							$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(2,0,0));
					    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
						}
						if($segment=='CRD')
						{
							$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,1));
						}
						if($segment=='ESU')
						{
							$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
						}
						if($segment=='RAD')
						{
							$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,1));
					    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,1));
					    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
						}
						if($segment=='RMS')
						{
							$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,1,0));
						}
					}
					if($region=='West')
					{
						if($segment=='CRD')
						{
							$series[]=array('name'=>'F303-000108-0','data'=>array(3,0,0));
					    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
						}
						if($segment=='ESU')
						{
							$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(1,1,0));
						}
						if($segment=='PMS')
						{
							$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,1,0));
						}
					}
					if($region=='North')
					{
						if($segment=='ESU')
						{
							$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(3,0,0));
					    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
						}
						if($segment=='PMS')
						{
							$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,0,1));
						}
						if($segment=='RAD')
						{
							$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
					    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,1));
						}
					}
					if($region=='North2')
					{
						if($segment=='ANS')
						{
							$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
						}
						if($segment=='CRD')
						{
							$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,2,0));
					    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
						}
						if($segment=='ESU')
						{
							$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(3,0,0));
					    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
						}
						if($segment=='PMS')
						{
							$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,1));
					    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,0,0));
						}
						if($segment=='RAD')
						{
							$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F303-000018-0','data'=>array(1,0,0));
					    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
						}
						if($segment=='RMS')
						{
							$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,1,0));
					    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
						}
					}
					if($region=='South1')
					{
						if($segment=='ANS')
						{
							$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(2,0,0));
					    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
						}
						if($segment=='ESU')
						{
							$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,1,0));
						}
						if($segment=='RAD')
						{
							$series[]=array('name'=>'F303-000274-2','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F303-000018-0','data'=>array(1,0,0));
					    	$series[]=array('name'=>'M54-900-1001','data'=>array(1,1,0));
						}
						if($segment=='RMS')
						{
							$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,1,1));
					    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
						}
					}
					if($region=='South2')
					{
						if($segment=='ANS')
						{
							$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
						}
						if($segment=='CRD')
						{
							$series[]=array('name'=>'F303-000108-0','data'=>array(3,1,1));
					    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
						}
						if($segment=='PMS')
						{
							$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,1,0));
						}
						if($segment=='RAD')
						{
							$series[]=array('name'=>'F303-000274-2','data'=>array(0,2,0));
					    	$series[]=array('name'=>'F303-000018-0','data'=>array(1,0,0));
					    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
						}
						if($segment=='RMS')
						{
							$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,1,0));
					    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
						}
					}
					if($region=='South3')
					{
						if($segment=='ANS')
						{
							$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
						}
						if($segment=='ESU')
						{
							$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(1,1,0));
						}
						if($segment=='RMS')
						{
							$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,2));
						}
					}
					if($region=='MP')
					{
						if($segment=='ANS')
						{
							$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,3,0));
					    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
						}
						if($segment=='CRD')
						{
							$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,2,0));
					    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
						}
						if($segment=='ESU')
						{
							$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,1));
					    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(1,0,0));
						}
						if($segment=='PMS')
						{
							$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,1,0));
						}
						if($segment=='RAD')
						{
							$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F303-000018-0','data'=>array(1,0,0));
					    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
						}
					}
				}
			}
			if($searchFilters['vtime']=='q')
			{
				if($searchFilters['duration']=='2017-10-02to2017-12-31')
				{
					if($lost_for=='Pricing Issue')
					{
						if($region=='Central')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,1,0));
							}
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,1));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,1));
							}
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
							}
							if($segment=='RMS')
							{
								$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,1,0));
							}
						}
						if($region=='East')
						{
							if($segment=='PMS')
							{
								$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,2,0));
						    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,0,0));
							}
						}
						if($region=='West')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,1,0));
							}
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,1));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,2));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
							}
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(1,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
							}
							if($segment=='RMS')
							{
								$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,1,0));
							}
						}
						if($region=='North')
						{
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,1));
							}
						}
						if($region=='North2')
						{
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,2,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(1,0,0));
							}
							if($segment=='PMS')
							{
								$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,1,0));
							}
							if($segment=='RMS')
							{
								$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,1,0));
						    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
							}
						}
						if($region=='South1')
						{
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
							}
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(1,0,0));
							}
						}
						if($region=='South2')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,1,0));
							}
							if($segment=='PMS')
							{
								$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,1,0));
							}
							if($segment=='RMS')
							{
								$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,1,0));
						    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
							}
						}
						if($region=='South3')
						{
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,1));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(1,0,0));
							}
						}
						if($region=='MP')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
							}
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,1));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,2));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
							}
							if($segment=='PMS')
							{
								$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,1,0));
							}
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(1,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
							}
						}
					}
					if($lost_for=='Product Features')
					{
						if($region=='Central')
						{
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,1));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
							if($segment=='PMS')
							{
								$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(5,3,1));
						    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(2,4,3));
						    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(3,1,4));
							}
						}
						if($region=='East')
						{
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
							}
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,1));
							}
						}
						if($region=='West')
						{
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,1,0));
							}
						}
						if($region=='North')
						{
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,1));
							}
						}
						if($region=='North2')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
							}
							if($segment=='PMS')
							{
								$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,1,0));
							}
							if($segment=='RMS')
							{
								$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,1,0));
						    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
							}
						}
						if($region=='South1')
						{
							
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
							}
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(1,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
							}
						}
						if($region=='South2')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
							}
							if($segment=='PMS')
							{
								$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,0,0));
							}
							if($segment=='RMS')
							{
								$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,0,1));
						    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
							}
						}
						if($region=='South3')
						{
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,1));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(1,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
							}
						}
						if($region=='MP')
						{
							if($segment=='PMS')
							{
								$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,1,0));
							}
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(1,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
							}
						}
					}
					if($lost_for=='Technically Rejected')
					{
						if($region=='Central')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
							}
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(1,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
							}
						}
						if($region=='East')
						{
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,2));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
							}
						}
						if($region=='West')
						{
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,1));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,1,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
							}
						}
						if($region=='North')
						{
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,1,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,1));
							}
						}
						if($region=='North2')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,1,0));
							}
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(1,0,0));
							}
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(1,0,0));
							}
						}
						if($region=='South1')
						{
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
							}
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(1,0,0));
							}
						}
						if($region=='South2')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,1,0));
							}
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,1));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
							if($segment=='RMS')
							{
								$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,1,0));
						    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
							}
						}
						if($region=='South3')
						{
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(1,0,0));
							}
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(1,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
							}
						}
						if($region=='MP')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
							}
							if($segment=='PMS')
							{
								$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,1,0));
							}
						}
					}
					if($lost_for=='Competitor Brand Value')
					{
						if($region=='Central')
						{
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,1));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,1));
							}
							if($segment=='RMS')
							{
								$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(2,0,0));
						    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,1,0));
							}
						}
						if($region=='East')
						{
							if($segment=='PMS')
							{
								$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,2,0));
						    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,0,0));
							}
						}
						if($region=='West')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,1,0));
							}
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,1));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,2));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
							}
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(1,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
							}
							if($segment=='RMS')
							{
								$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,1,0));
							}
						}
						if($region=='North')
						{
							if($segment=='PMS')
							{
								$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,1,0));
							}
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,1));
							}
						}
						if($region=='North2')
						{
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(1,0,0));
							}
							if($segment=='RMS')
							{
								$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,2,0));
						    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
							}
						}
						if($region=='South1')
						{
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
							}
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(1,0,0));
							}
						}
						if($region=='South2')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,1,0));
							}
							if($segment=='PMS')
							{
								$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,3,0));
							}
							if($segment=='RMS')
							{
								$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,1,0));
						    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
							}
						}
						if($region=='South3')
						{
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,1));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(1,0,0));
							}
						}
						if($region=='MP')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
							}
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,1));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,2));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
							}
							if($segment=='PMS')
							{
								$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,1,0));
							}
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(1,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
							}
						}
					}
				}
				elseif($searchFilters['duration']=='2017-07-03to2017-10-01')
				{
					if($lost_for=='Pricing Issue')
					{
						if($region=='Central')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,1,0));
							}
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,1));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,1));
							}
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(3,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
							}
							if($segment=='RMS')
							{
								$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,1,0));
							}
						}
						if($region=='East')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,2,0));
							}
							if($segment=='PMS')
							{
								$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,2,0));
						    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,0,0));
							}
							if($segment=='RMS')
							{
								$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,2,0));
							}
						}
						if($region=='West')
						{
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,3));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
							if($segment=='PMS')
							{
								$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,2,0));
						    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,0,0));
							}
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(3,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
							}
						}
						if($region=='North')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,1,0));
							}
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
							}
							if($segment=='PMS')
							{
								$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,2,0));
						    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,0,0));
							}
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,2));
							}
						}
						if($region=='North2')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,5,0));
							}
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,2,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(3,0,0));
							}
							if($segment=='PMS')
							{
								$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,1,0));
							}
							if($segment=='RMS')
							{
								$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,1,0));
						    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
							}
						}
						if($region=='South1')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,2,0));
							}
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
							}
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(1,0,0));
							}
							if($segment=='RMS')
							{
								$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,2,0));
						    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
							}
						}
						if($region=='South2')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,1,0));
							}
							if($segment=='PMS')
							{
								$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,1,0));
							}
							if($segment=='RMS')
							{
								$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,1,0));
						    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
							}
						}
						if($region=='South3')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
							}
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,1));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(2,0,0));
							}
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(1,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
							}
						}
						if($region=='MP')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(4,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
							}
							if($segment=='PMS')
							{
								$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,1,0));
							}
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(1,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
							}
						}
					}					
					if($lost_for=='Product Features')
					{
						if($region=='Central')
						{
							if($segment=='PMS')
							{
								$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,2,0));
							}
							if($segment=='RMS')
							{
								$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,3,0));
						    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
							}
						}
						if($region=='East')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
							}
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
							}
						}
						if($region=='West')
						{
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(3,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
						}
						if($region=='North')
						{
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,1));
							}
						}
						if($region=='North2')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
							}
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(3,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
							}
						}
						if($region=='South1')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
							}
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(1,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
							}
						}
						if($region=='South2')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
							}
							if($segment=='PMS')
							{
								$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,0,0));
							}
							if($segment=='RMS')
							{
								$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,0,1));
						    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
							}
						}
						if($region=='South3')
						{
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,1));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(2,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
							}
						}
						if($region=='MP')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
							}
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(2,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
							}
						}
					}
					if($lost_for=='Technically Rejected')
					{
						if($region=='Central')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
							}
						}
						if($region=='East')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(2,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
							}
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,1));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
						}
						if($region=='West')
						{
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,3));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
						}
						if($region=='North')
						{
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,1));
							}
						}
						if($region=='North2')
						{
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,2));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
						}
						if($region=='South1')
						{
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
							}
						}
						if($region=='South2')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,1,0));
							}
						}
						if($region=='South3')
						{
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(1,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
							}
						}
						if($region=='MP')
						{
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,2));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
						}
					}
					if($lost_for=='Competitor Brand Value')
					{
						if($region=='Central')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,1,0));
							}
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(1,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
							}
						}
						if($region=='East')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,2,0));
							}
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,1));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
						}
						if($region=='West')
						{
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,2));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
							if($segment=='PMS')
							{
								$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,1,0));
							}
						}
						if($region=='North')
						{
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(2,0,0));
							}
						}
						if($region=='North2')
						{
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,2));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
							if($segment=='PMS')
							{
								$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,1,0));
							}
						}
						if($region=='South1')
						{
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
							}
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(1,0,0));
							}
						}
						if($region=='South2')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,1,0));
							}
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(1,0,0));
							}
						}
						if($region=='South3')
						{
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(1,0,0));
							}
						}
						if($region=='MP')
						{
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,1));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
							if($segment=='PMS')
							{
								$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,1,0));
							}
						}
					}
				}
				elseif($searchFilters['duration']=='2017-04-03to2017-07-02')
				{
					if($lost_for=='Pricing Issue')
					{
						if($region=='Central')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,1,0));
							}
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(3,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
							}
							if($segment=='RMS')
							{
								$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,1,0));
							}
						}
						if($region=='East')
						{
							if($segment=='PMS')
							{
								$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,2,0));
						    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,0,0));
							}
						}
						if($region=='West')
						{
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(4,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
							}
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(1,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
							}
							if($segment=='RMS')
							{
								$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,1,0));
							}
						}
						if($region=='North')
						{
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,1));
							}
						}
						if($region=='North2')
						{
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,2,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(2,0,0));
							}
							if($segment=='RMS')
							{
								$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,1,0));
						    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
							}
						}
						if($region=='South1')
						{
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(2,0,0));
							}
						}
						if($region=='South2')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,1,0));
							}
							if($segment=='PMS')
							{
								$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,1,0));
							}
							if($segment=='RMS')
							{
								$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,1,0));
						    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
							}
						}
						if($region=='South3')
						{
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,1));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(1,0,0));
							}
						}
						if($region=='MP')
						{
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(2,0,0));
							}
							if($segment=='PMS')
							{
								$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,3,0));
							}
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(1,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
							}
						}
					}					
					if($lost_for=='Product Features')
					{
						if($region=='Central')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
							}
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(2,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
							}
							if($segment=='RMS')
							{
								$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,1,0));
						    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
							}
						}
						if($region=='East')
						{
							if($segment=='PMS')
							{
								$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(2,0,0));
						    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,0,0));
							}
						}
						if($region=='West')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
							}
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(2,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
							}
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,1));
							}
							if($segment=='RMS')
							{
								$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,1,0));
						    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
							}
						}
						if($region=='North')
						{
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,1));
							}
						}
						if($region=='North2')
						{
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(2,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
							}
							if($segment=='PMS')
							{
								$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,0,0));
							}
							if($segment=='RMS')
							{
								$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,1,0));
						    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
							}
						}
						if($region=='South1')
						{
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
							}
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(1,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
							}
						}
						if($region=='South2')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
							}
							if($segment=='PMS')
							{
								$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,0,0));
							}
							if($segment=='RMS')
							{
								$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,0,1));
						    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
							}
						}
						if($region=='South3')
						{
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,1));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
							}
						}
						if($region=='MP')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
							}
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,1));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(2,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
							}
							if($segment=='PMS')
							{
								$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,0,0));
							}
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,1));
							}
						}
					}
					if($lost_for=='Technically Rejected')
					{
						if($region=='Central')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
							}
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,1));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
							}
							if($segment=='PMS')
							{
								$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,0,0));
							}
							if($segment=='RMS')
							{
								$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,1,0));
						    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
							}
						}
						if($region=='East')
						{
							if($segment=='PMS')
							{
								$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(2,0,0));
						    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,0,0));
							}
						}
						if($region=='West')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
							}
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,1));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(2,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
							}
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,1));
							}
							if($segment=='RMS')
							{
								$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,1,0));
						    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
							}
						}
						if($region=='North')
						{
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,1));
							}
						}
						if($region=='North2')
						{
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,2));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
							if($segment=='PMS')
							{
								$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(2,0,0));
						    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,0,0));
							}
							if($segment=='RMS')
							{
								$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,1,0));
						    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
							}
						}
						if($region=='South1')
						{
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
							}
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,1));
							}
						}
						if($region=='South2')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,1,0));
							}
							if($segment=='PMS')
							{
								$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,0,0));
							}
							if($segment=='RMS')
							{
								$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,1,0));
						    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
							}
						}
						if($region=='South3')
						{
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,2));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
						}
						if($region=='MP')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,1,0));
							}
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,1));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(2,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
							}
							if($segment=='PMS')
							{
								$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,1,0));
							}
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(1,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
							}
						}
					}
					if($lost_for=='Competitor Brand Value')
					{
						if($region=='Central')
						{
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,2));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
							if($segment=='RMS')
							{
								$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,2,0));
						    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
							}
						}
						if($region=='East')
						{
							if($segment=='PMS')
							{
								$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,2,0));
							}
						}
						if($region=='West')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,3,0));
							}
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(2,0,0));
							}
						}
						if($region=='North')
						{
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(1,0,0));
							}
						}
						if($region=='North2')
						{
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,2));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
							if($segment=='PMS')
							{
								$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,1,0));
							}
							if($segment=='RMS')
							{
								$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,1,0));
						    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
							}
						}
						if($region=='South1')
						{
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
							}
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(1,0,0));
							}
						}
						if($region=='South2')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,1,0));
							}
							if($segment=='RMS')
							{
								$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,1,0));
						    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
							}
						}
						if($region=='South3')
						{
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,1));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
							}
						}
						if($region=='MP')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,1,0));
							}
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,1));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
							if($segment=='PMS')
							{
								$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,1,0));
							}
						}
					}
				}
			}
			if($searchFilters['vtime']=='m')
			{
				if($searchFilters['duration_text']=='Nov-17')
				{
					if($lost_for=='Technically Rejected')
					{
						if($region=='Central')
						{
							if($segment=='PMS')
							{
								$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,1,0));
						    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,0,0));
							}
						}
						if($region=='East')
						{
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
							}
						}
						if($region=='West')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,1,0));
							}
						}
						if($region=='North')
						{
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(1,0,0));
							}
						}
						if($region=='North2')
						{
							if($segment=='RMS')
							{
								$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,1,0));
						    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
							}
						}
						if($region=='South1')
						{
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(1,0,0));
							}
						}
						if($region=='South2')
						{
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(1,0,0));
							}
						}
						if($region=='South3')
						{
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,1));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
						}
						if($region=='MP')
						{
							if($segment=='PMS')
							{
								$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,1,0));
							}
						}
					}
				}
			}
			if($searchFilters['vtime']=='w')
			{
				if($searchFilters['duration']=='2017-11-27to2017-12-03')
				{
					if($lost_for=='Competitor Brand Value')
					{
						if($region=='Central')
						{
							if($segment=='RMS')
							{
								$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,1,0));
						    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
							}
						}
						if($region=='East')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,1,0));
							}
						}
					}
				}
			}
			break;
		case 2:
			$xAxisCategory=array("NARAYANA MEDICAL COLLEGE & GENERAL","SHRIKRISHNA HEALTHCARE ","NRI HOSPITAL");
    		$c1Data=array();
			if($searchFilters['vtime']=='y')
			{
				if($lost_for=='GE')
				{
					if($region=='Central')
					{
						if($segment=='ANS')
						{
							$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,2));
					    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
						}
						if($segment=='CRD')
						{
							$series[]=array('name'=>'F303-000108-0','data'=>array(0,1,0));
					    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(1,0,0));
						}
						if($segment=='ESU')
						{
							$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(2,2,0));
					    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(2,0,0));
						}
						if($segment=='PMS')
						{
							$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,1,0));
						}
						if($segment=='RAD')
						{
							$series[]=array('name'=>'F303-000274-2','data'=>array(2,0,0));
					    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,2));
					    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,1));
						}
						if($segment=='RMS')
						{
							$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,2,0));
					    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
						}
					}
					if($region=='East')
					{
						if($segment=='ANS')
						{
							$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(1,0,0));
						}
						if($segment=='CRD')
						{
							$series[]=array('name'=>'F303-000108-0','data'=>array(4,0,0));
					    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(3,0,0));
						}
						if($segment=='ESU')
						{
							$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(2,0,0));
					    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
						}
						if($segment=='PMS')
						{
							$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(1,0,1));
					    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,1,0));
						}
						if($segment=='RAD')
						{
							$series[]=array('name'=>'F303-000274-2','data'=>array(2,0,0));
					    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
					    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
						}
						if($segment=='RMS')
						{
							$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,0,1));
					    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,1));
						}
					}
					if($region=='West')
					{
						if($segment=='ANS')
						{
							$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,3,0));
					    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
						}
						if($segment=='CRD')
						{
							$series[]=array('name'=>'F303-000108-0','data'=>array(3,0,0));
					    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
						}
						if($segment=='ESU')
						{
							$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(1,1,0));
						}
						if($segment=='PMS')
						{
							$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(2,0,0));
					    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,0,0));
						}
						if($segment=='RAD')
						{
							$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F303-000018-0','data'=>array(1,0,0));
					    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
						}
						if($segment=='RMS')
						{
							$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(1,1,0));
					    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,1,0));
					    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
						}
					}
					if($region=='North')
					{
						if($segment=='ANS')
						{
							$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,1,0));
					    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,1,0));
					    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,1));
						}
						if($segment=='CRD')
						{
							$series[]=array('name'=>'F303-000108-0','data'=>array(2,0,0));
					    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
						}
						if($segment=='ESU')
						{
							$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,1));
					    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,2,0));
					    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(2,0,0));
						}
						if($segment=='PMS')
						{
							$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,2,0));
					    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(1,0,0));
						}
						if($segment=='RAD')
						{
							$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
					    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,1));
						}
					}
					if($region=='North2')
					{
						if($segment=='ANS')
						{
							$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,4,0));
					    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(1,0,0));
						}
						if($segment=='CRD')
						{
							$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,2,0));
					    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
						}
						if($segment=='ESU')
						{
							$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
						}
						if($segment=='PMS')
						{
							$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(2,0,0));
					    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,5,0));
					    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,0,0));
						}
						if($segment=='RAD')
						{
							$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F303-000018-0','data'=>array(1,0,0));
					    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,1));
						}
						if($segment=='RMS')
						{
							$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,1,0));
						}
					}
					if($region=='South1')
					{
						if($segment=='ANS')
						{
							$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(1,1,0));
						}
						if($segment=='ESU')
						{
							$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,1,0));
						}
						if($segment=='RAD')
						{
							$series[]=array('name'=>'F303-000274-2','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
					    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
						}
						if($segment=='RMS')
						{
							$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,4,0));
					    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,1,1));
					    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(1,0,0));
						}
					}
					if($region=='South2')
					{
						if($segment=='ANS')
						{
							$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,1));
					    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
						}
						if($segment=='PMS')
						{
							$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,1,0));
						}
						if($segment=='RMS')
						{
							$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
						}
					}
					if($region=='South3')
					{
						if($segment=='ANS')
						{
							$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(4,0,0));
						}
						if($segment=='CRD')
						{
							$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,1));
					    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
						}
						if($segment=='ESU')
						{
							$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(1,1,0));
						}
						if($segment=='RAD')
						{
							$series[]=array('name'=>'F303-000274-2','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
					    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,2,0));
						}
					}
					if($region=='MP')
					{
						if($segment=='ANS')
						{
							$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,0,1));
					    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,1,0));
						}
						if($segment=='CRD')
						{
							$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,2,0));
					    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
						}
						if($segment=='ESU')
						{
							$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(1,1,0));
						}
						if($segment=='PMS')
						{
							$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,1,0));
						}
						if($segment=='RAD')
						{
							$series[]=array('name'=>'F303-000274-2','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
					    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
						}
					}
				}
				if($lost_for=='Philips')
				{
					if($region=='Central')
					{
						if($segment=='ANS')
						{
							$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,2));
					    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
						}
						if($segment=='CRD')
						{
							$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,1));
					    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
						}
						if($segment=='ESU')
						{
							$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,3,0));
					    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,1,0));
						}
						if($segment=='PMS')
						{
							$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,1));
					    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,1,0));
						}
						if($segment=='RAD')
						{
							$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F303-000018-0','data'=>array(2,1,0));
					    	$series[]=array('name'=>'M54-900-1001','data'=>array(1,0,0));
						}
						if($segment=='RMS')
						{
							$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,0,3));
					    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
						}
					}
					if($region=='East')
					{
						if($segment=='ANS')
						{
							$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(1,0,0));
						}
						if($segment=='CRD')
						{
							$series[]=array('name'=>'F303-000108-0','data'=>array(4,0,0));
					    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
						}
						if($segment=='ESU')
						{
							$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
						}
						if($segment=='RAD')
						{
							$series[]=array('name'=>'F303-000274-2','data'=>array(2,0,0));
					    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
					    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
						}
						if($segment=='RMS')
						{
							$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,1,0));
						}
					}
					if($region=='West')
					{
						if($segment=='CRD')
						{
							$series[]=array('name'=>'F303-000108-0','data'=>array(3,0,0));
					    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
						}
						if($segment=='ESU')
						{
							$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,2));
					    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
						}
						if($segment=='PMS')
						{
							$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,1));
					    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,1,0));
						}
					}
					if($region=='North')
					{
						if($segment=='ANS')
						{
							$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,1));
					    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,2,0));
					    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
						}
						if($segment=='CRD')
						{
							$series[]=array('name'=>'F303-000108-0','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
						}
						if($segment=='ESU')
						{
							$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,1,0));
						}
						if($segment=='RAD')
						{
							$series[]=array('name'=>'F303-000274-2','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
					    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
						}
						if($segment=='RMS')
						{
							$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,1,0));
						}
					}
					if($region=='North2')
					{
						if($segment=='ANS')
						{
							$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,1));
					    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
						}
						if($segment=='CRD')
						{
							$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,2,0));
					    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
						}
						if($segment=='ESU')
						{
							$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,3,0));
					    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
						}
						if($segment=='PMS')
						{
							$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,1,0));
						}
						if($segment=='RMS')
						{
							$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,1,0));
					    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
						}
					}
					if($region=='South1')
					{
						if($segment=='ANS')
						{
							$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(2,0,0));
					    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
						}
						if($segment=='ESU')
						{
							$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
						}
						if($segment=='RAD')
						{
							$series[]=array('name'=>'F303-000274-2','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
					    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
						}
						if($segment=='RMS')
						{
							$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,2));
						}
					}
					if($region=='South2')
					{
						if($segment=='ANS')
						{
							$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
						}
						if($segment=='PMS')
						{
							$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,1));
					    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,0,0));
						}
						if($segment=='RMS')
						{
							$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,1,0));
						}
					}
					if($region=='South3')
					{
						if($segment=='ANS')
						{
							$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,1));
					    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(4,1,0));
						}
						if($segment=='CRD')
						{
							$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,1));
					    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
						}
						if($segment=='ESU')
						{
							$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(1,1,0));
						}
						if($segment=='RAD')
						{
							$series[]=array('name'=>'F303-000274-2','data'=>array(2,0,0));
					    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
					    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
						}
					}
					if($region=='MP')
					{
						if($segment=='ANS')
						{
							$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(4,0,0));
						}
						if($segment=='CRD')
						{
							$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,1));
					    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,1));
						}
						if($segment=='ESU')
						{
							$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,2));
					    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
						}
						if($segment=='PMS')
						{
							$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,1,0));
						}
						if($segment=='RAD')
						{
							$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F303-000018-0','data'=>array(1,0,0));
					    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
						}
					}
				}
				if($lost_for=='Siemens')
				{
					if($region=='Central')
					{
						if($segment=='ANS')
						{
							$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,1,0));
						}
						if($segment=='CRD')
						{
							$series[]=array('name'=>'F303-000108-0','data'=>array(3,0,0));
					    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
						}
						if($segment=='ESU')
						{
							$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
						}
						if($segment=='PMS')
						{
							$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(2,0,0));
					    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,0,0));
						}
						if($segment=='RAD')
						{
							$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,1,0));
					    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
						}
						if($segment=='RMS')
						{
							$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(2,0,0));
					    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,1,0));
						}
					}
					if($region=='East')
					{
						if($segment=='ANS')
						{
							$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(1,0,0));
						}
						if($segment=='CRD')
						{
							$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,1));
					    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
						}
						if($segment=='ESU')
						{
							$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(1,0,2));
					    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
						}
						if($segment=='RAD')
						{
							$series[]=array('name'=>'F303-000274-2','data'=>array(2,0,0));
					    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
					    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
						}
						if($segment=='RMS')
						{
							$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,1,0));
						}
					}
					if($region=='West')
					{
						if($segment=='CRD')
						{
							$series[]=array('name'=>'F303-000108-0','data'=>array(3,0,0));
					    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
						}
						if($segment=='ESU')
						{
							$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(1,1,0));
						}
						if($segment=='PMS')
						{
							$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,1));
					    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,0,0));
						}
					}
					if($region=='North')
					{
						if($segment=='ESU')
						{
							$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(3,0,0));
					    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
						}
						if($segment=='PMS')
						{
							$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,1));
					    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,0,0));
						}
						if($segment=='RAD')
						{
							$series[]=array('name'=>'F303-000274-2','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
					    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
						}
						if($segment=='RMS')
						{
							$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(2,0,0));
					    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,1,0));
						}
					}
					if($region=='North2')
					{
						if($segment=='ANS')
						{
							$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
						}
						if($segment=='CRD')
						{
							$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,1));
					    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
						}
						if($segment=='ESU')
						{
							$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,1));
					    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(1,1,0));
						}
						if($segment=='PMS')
						{
							$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,1));
					    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,0,0));
						}
						if($segment=='RAD')
						{
							$series[]=array('name'=>'F303-000274-2','data'=>array(2,0,0));
					    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
					    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
						}
						if($segment=='RMS')
						{
							$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,1,0));
					    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
						}
					}
					if($region=='South1')
					{
						if($segment=='ANS')
						{
							$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(2,0,0));
					    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
						}
						if($segment=='ESU')
						{
							$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
						}
						if($segment=='RAD')
						{
							$series[]=array('name'=>'F303-000274-2','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F303-000018-0','data'=>array(1,0,0));
					    	$series[]=array('name'=>'M54-900-1001','data'=>array(1,1,0));
						}
						if($segment=='RMS')
						{
							$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(2,0,0));
					    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
						}
					}
					if($region=='South2')
					{
						if($segment=='ANS')
						{
							$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
						}
						if($segment=='CRD')
						{
							$series[]=array('name'=>'F303-000108-0','data'=>array(2,0,0));
					    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,2,0));
					    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
						}
						if($segment=='PMS')
						{
							$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,1,0));
						}
						if($segment=='RMS')
						{
							$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,1,0));
					    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
						}
					}
					if($region=='South3')
					{
						if($segment=='ANS')
						{
							$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
						}
						if($segment=='ESU')
						{
							$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(1,1,0));
						}
						if($segment=='RAD')
						{
							$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F303-000018-0','data'=>array(1,0,0));
					    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
						}
					}
					if($region=='MP')
					{
						if($segment=='ANS')
						{
							$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(4,0,0));
						}
						if($segment=='CRD')
						{
							$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,2,0));
					    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
						}
						if($segment=='ESU')
						{
							$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,1));
					    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(1,0,0));
						}
						if($segment=='PMS')
						{
							$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,1,0));
						}
					}
				}
				if($lost_for=='BPL')
				{
					if($region=='Central')
					{
						if($segment=='ANS')
						{
							$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,2));
					    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
						}
						if($segment=='CRD')
						{
							$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,1));
					    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
						}
						if($segment=='ESU')
						{
							$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
						}
						if($segment=='PMS')
						{
							$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(2,0,0));
					    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,0,0));
						}
						if($segment=='RAD')
						{
							$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,1,0));
					    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
						}
					}
					if($region=='East')
					{
						if($segment=='CRD')
						{
							$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,2,0));
					    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
						}
						if($segment=='RAD')
						{
							$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,1));
					    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,1));
					    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
						}
						if($segment=='RMS')
						{
							$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
						}
					}
					if($region=='West')
					{
						
						if($segment=='ESU')
						{
							$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(1,1,0));
						}
						if($segment=='PMS')
						{
							$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,1,0));
						}
					}
					if($region=='North')
					{
						if($segment=='ANS')
						{
							$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
						}
						if($segment=='ESU')
						{
							$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(3,0,0));
					    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
						}
						if($segment=='PMS')
						{
							$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,0,1));
						}
						if($segment=='RAD')
						{
							$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
					    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,1));
						}
						if($segment=='RMS')
						{
							$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,1,0));
					    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
						}
					}
					if($region=='North2')
					{
						if($segment=='CRD')
						{
							$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,2,0));
					    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
						}
						if($segment=='ESU')
						{
							$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(3,0,0));
					    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
						}
						if($segment=='PMS')
						{
							$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,1));
					    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,0,0));
						}
					}
					if($region=='South1')
					{
						if($segment=='ANS')
						{
							$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(2,0,0));
					    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
						}
						if($segment=='ESU')
						{
							$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,1,0));
						}
						if($segment=='RAD')
						{
							$series[]=array('name'=>'F303-000274-2','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
					    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
						}
						if($segment=='RMS')
						{
							$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,1,1));
					    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
						}
					}
					if($region=='South2')
					{
						if($segment=='ANS')
						{
							$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
						}
						if($segment=='CRD')
						{
							$series[]=array('name'=>'F303-000108-0','data'=>array(3,1,0));
					    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(1,0,0));
						}
						if($segment=='PMS')
						{
							$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,1,0));
						}
						if($segment=='RMS')
						{
							$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,1,0));
					    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
						}
					}
					if($region=='South3')
					{
						if($segment=='ANS')
						{
							$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
						}
						if($segment=='ESU')
						{
							$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,1,0));
						}
						if($segment=='RMS')
						{
							$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(2,0,0));
					    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
						}
					}
					if($region=='MP')
					{
						if($segment=='ANS')
						{
							$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,0,0));
					    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
						}
						if($segment=='CRD')
						{
							$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,2,0));
					    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
						}
						if($segment=='ESU')
						{
							$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,1));
					    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(1,0,0));
						}
						if($segment=='PMS')
						{
							$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,1,0));
						}
						if($segment=='RAD')
						{
							$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
					    	$series[]=array('name'=>'F303-000018-0','data'=>array(1,0,0));
					    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
						}
					}
				}
			}
			if($searchFilters['vtime']=='q')
			{
				if($searchFilters['duration']=='2017-10-02to2017-12-31')
				{
					if($lost_for=='GE')
					{
						if($region=='Central')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,1,0));
							}
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,1));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,1));
							}
							if($segment=='PMS')
							{
								$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,1,0));
						    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,0,0));
							}
						}
						if($region=='East')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,1,0));
							}
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
							}
							if($segment=='PMS')
							{
								$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,2,0));
						    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(2,0,0));
							}
							if($segment=='RMS')
							{
								$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,1,0));
							}
						}
						if($region=='West')
						{
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,1));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(1,0,0));
							}
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,2));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
							}
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(1,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,1));
							}
							if($segment=='RMS')
							{
								$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,1,0));
							}
						}
						if($region=='North')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,1,0));
							}
							if($segment=='PMS')
							{
								$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,2));
						    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,1,0));
							}
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,1));
							}
						}
						if($region=='North2')
						{
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,1));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(1,0,0));
							}
							if($segment=='PMS')
							{
								$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(1,2,0));
						    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,1,0));
							}
							if($segment=='RMS')
							{
								$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,1,0));
						    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
							}
						}
						if($region=='South1')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,1));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,1,0));
							}
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,2));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
							}
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(1,0,0));
							}
						}
						if($region=='South2')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,1,0));
							}
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(1,0,1));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(1,0,0));
							}
							if($segment=='PMS')
							{
								$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,1,0));
							}
							if($segment=='RMS')
							{
								$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,1,0));
						    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
							}
						}
						if($region=='South3')
						{
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,1));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(2,0,0));
							}
							if($segment=='PMS')
							{
								$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,1,0));
							}
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(1,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
							}
						}
						if($region=='MP')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
							}
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,2));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
							}
						}
					}
					if($lost_for=='Philips')
					{
						if($region=='Central')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
							}
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(2,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
							if($segment=='PMS')
							{
								$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,1,0));
							}
						}
						if($region=='East')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(2,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
							}
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(2,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
							}
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,1));
							}
						}
						if($region=='West')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
							}
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
							}
							if($segment=='RMS')
							{
								$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,0,1));
						    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
							}
						}
						if($region=='North')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
							}
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,1));
							}
						}
						if($region=='North2')
						{
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
							}
							if($segment=='PMS')
							{
								$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,1,0));
							}
							if($segment=='RMS')
							{
								$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,0,1));
						    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
							}
						}
						if($region=='South1')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
							}
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
							}
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(1,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
							}
						}
						if($region=='South2')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
							}
							if($segment=='PMS')
							{
								$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,0,0));
							}
							if($segment=='RMS')
							{
								$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,0,1));
						    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
							}
						}
						if($region=='South3')
						{
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,1));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(2,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
							}
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(1,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
							}
						}
						if($region=='MP')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
							}
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
							}
						}
					}
					if($lost_for=='Siemens')
					{
						if($region=='Central')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
							}
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,2));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
							if($segment=='PMS')
							{
								$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,0,0));
							}
						}
						if($region=='East')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
							}
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
							}
							if($segment=='PMS')
							{
								$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(2,0,0));
						    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,0,0));
							}
						}
						if($region=='West')
						{
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(2,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
							}
							if($segment=='RMS')
							{
								$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,0,1));
						    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
							}
						}
						if($region=='North')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
							}
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,1));
							}
						}
						if($region=='North2')
						{
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,1));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
							}
							if($segment=='RMS')
							{
								$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,0,1));
						    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
							}
						}
						if($region=='South1')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,2,0));
							}
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
							}
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,1));
							}
						}
						if($region=='South2')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,1,0));
							}
							if($segment=='PMS')
							{
								$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,0,0));
							}
							if($segment=='RMS')
							{
								$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,0,1));
						    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
							}
						}
						if($region=='South3')
						{
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,1));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(2,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
							}
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(1,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
							}
						}
						if($region=='MP')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,1,0));
							}
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(2,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
							}
						}
					}
					if($lost_for=='BPL')
					{
						if($region=='Central')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,1,0));
							}
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,1));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
							if($segment=='PMS')
							{
								$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,1,0));
							}
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(1,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
							}
						}
						if($region=='East')
						{
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(2,0,0));
							}
							if($segment=='PMS')
							{
								$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,2,0));
							}
						}
						if($region=='West')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,1,0));
							}
							if($segment=='RMS')
							{
								$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,0,1));
						    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
							}
						}
						if($region=='North')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,1,0));
							}
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(1,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
							}
						}
						if($region=='North2')
						{
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,1));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
							if($segment=='RMS')
							{
								$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,0,1));
						    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
							}
						}
						if($region=='South1')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,1,0));
							}
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
							}
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(1,0,0));
							}
						}
						if($region=='South2')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,1,0));
							}
							if($segment=='PMS')
							{
								$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,1,0));
							}
							if($segment=='RMS')
							{
								$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,0,1));
						    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
							}
						}
						if($region=='South3')
						{
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,1));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(1,0,0));
							}
						}
						if($region=='MP')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,1,0));
							}
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
							}
						}
					}
				}
				elseif($searchFilters['duration']=='2017-07-03to2017-10-01')
				{
					if($lost_for=='GE')
					{
						if($region=='Central')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,1,0));
							}
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,2));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
							if($segment=='PMS')
							{
								$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,2,0));
						    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,0,0));
							}
						}
						if($region=='East')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,3,0));
							}
							if($segment=='RMS')
							{
								$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,1,0));
							}
						}
						if($region=='West')
						{
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(2,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
							}
							if($segment=='RMS')
							{
								$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,1,0));
							}
						}
						if($region=='North')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,1,0));
							}
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,1));
							}
						}
						if($region=='North2')
						{
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,1,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(2,0,0));
							}
						}
						if($region=='South1')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,2,0));
							}
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
							}
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(1,0,0));
							}
						}
						if($region=='South2')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,1,0));
							}
							if($segment=='PMS')
							{
								$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,1,0));
							}
							if($segment=='RMS')
							{
								$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,1,0));
						    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
							}
						}
						if($region=='South3')
						{
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(2,0,0));
							}
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(2,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
							}
						}
						if($region=='MP')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
							}
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(2,0,0));
							}
						}
					}					
					if($lost_for=='Philips')
					{
						if($region=='Central')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
							}
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(1,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
							}
						}
						if($region=='East')
						{
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
							}
						}
						if($region=='West')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
							}
							if($segment=='RMS')
							{
								$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,1,0));
						    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
							}
						}
						if($region=='North')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
							}
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,1));
							}
						}
						if($region=='North2')
						{
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,1));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
						}
						if($region=='South1')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
							}
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(1,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
							}
						}
						if($region=='South2')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
							}
							if($segment=='RMS')
							{
								$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,0,1));
						    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
							}
						}
						if($region=='South3')
						{
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,1));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(1,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
							}
						}
						if($region=='MP')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
							}
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
							}
						}
					}
					if($lost_for=='Siemens')
					{
						if($region=='Central')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
							}
							if($segment=='PMS')
							{
								$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,1,0));
							}
						}
						if($region=='East')
						{
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(2,0,0));
							}
						}
						if($region=='West')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
							}
							if($segment=='RMS')
							{
								$series[]=array('name'=>'F3-75-380-0064-78','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-380-0065-75','data'=>array(0,1,0));
						    	$series[]=array('name'=>'F3-75-380-0066-72','data'=>array(0,0,0));
							}
						}
						if($region=='North')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(2,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
							}
						}
						if($region=='North2')
						{
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,1));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
						}
						if($region=='South1')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,1,0));
							}
						}
						if($region=='South3')
						{
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(2,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(0,0,0));
							}
						}
						if($region=='MP')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,1,0));
							}
						}
					}
					if($lost_for=='BPL')
					{
						if($region=='Central')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,2,0));
							}
						}
						if($region=='East')
						{
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(1,0,0));
							}
						}
						if($region=='North')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,1,0));
							}
						}
						if($region=='South1')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,1,0));
							}
						}
						if($region=='South3')
						{
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(1,0,0));
							}
						}
					}
				}	
			}
			if($searchFilters['vtime']=='m')
			{
				if($searchFilters['duration_text']=='Nov-17')
				{
					if($lost_for=='Philips')
					{
						if($region=='Central')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(1,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,0,0));
							}
						}
						if($region=='East')
						{
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,2,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(0,0,0));
							}
						}
						if($region=='West')
						{
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(1,0,0));
							}
						}
						if($region=='North')
						{
							if($segment=='RAD')
							{
								$series[]=array('name'=>'F303-000274-2','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F303-000018-0','data'=>array(0,0,0));
						    	$series[]=array('name'=>'M54-900-1001','data'=>array(1,0,0));
							}
						}
						if($region=='North2')
						{
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,1));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
						}
						if($region=='South1')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,1,0));
							}
						}
						if($region=='South2')
						{
							if($segment=='PMS')
							{
								$series[]=array('name'=>'F3-85-390-0303-12','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0316-70','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-85-390-0329-31','data'=>array(0,1,0));
							}

						}
						if($region=='South3')
						{
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,1));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
						}
						if($region=='MP')
						{
							if($segment=='ANS')
							{
								$series[]=array('name'=>'F3-75-390-0227-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0192-95','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-75-390-0016-49','data'=>array(0,1,0));
							}
							if($segment=='ESU')
							{
								$series[]=array('name'=>'F3-05-390-0063-57','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0034-39','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-10-190-0131-39','data'=>array(1,0,0));
							}
						}
					}
				}
			}
			if($searchFilters['vtime']=='w')
			{
				if($searchFilters['duration']=='2017-11-27to2017-12-03')
				{
					if($lost_for=='Siemens')
					{
						if($region=='Central')
						{
							if($segment=='CRD')
							{
								$series[]=array('name'=>'F303-000108-0','data'=>array(0,0,1));
						    	$series[]=array('name'=>'F3-19-390-0033-59','data'=>array(0,0,0));
						    	$series[]=array('name'=>'F3-19-390-0034-56','data'=>array(0,0,0));
							}
						}
					}
				}
			}
			break;
	}

	$chart1Data = array('xAxisCategory'=>$xAxisCategory,'chart3Series'=>$series,'xAxisLable'=>$xAxisLable);
	$chart1Data = json_encode($chart1Data);
	return $chart1Data;
}
function static_get_dates($searchFilters)
{   
	$vtime=$searchFilters['vtime'];
	$CI=&get_instance();
	$fy_dates=get_start_end_dates($vtime,'',$searchFilters);

	if($vtime=='y')
	{
       $res=get_months_fo($fy_dates['start_date'],date('Y-m-d'));
	}
	elseif ($vtime=='q') 
	{   
		$quarter=$searchFilters['duration'];
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
            $res[]="Week".$i.' ('.$row['start_date'].' to '.$row['end_date']. ')';
            $i++;
        }
	}
	else
	{
		$begin = new DateTime( $fy_dates['start_date'] );
		$end   = new DateTime( $fy_dates['end_date'] );
		$res=array();
		for($i = $begin; $i <= $end; $i->modify('+1 day'))
		{
	        if($i->format('Y-m-d')<=date('Y-m-d'))
	        {
				$res[]=date('jM',strtotime($i->format("Y-m-d")));
	        }
		}
	                
	}
	return $res;

	}
function static_get_fresh_business_bar($searchFilters)
{   
	$CI=& get_instance();
	$xAxisCategory=array();
	if($searchFilters['measure']==1)
	{
		$xAxisCategory=array('Suction','Dental Turbinies','Endodontic','Light Cure','Compressor');
	}
	if($searchFilters['measure']==2)
	{
		$xAxisCategory=array('Central','East','West','North','South');
	}
	if($searchFilters['measure']==1)
	{
		$series[]=array('name'=>'Fresh Business','data'=>array(5,8,7,6,5),'color'=>'#66b3ff');
		$series[]=array('name'=>'Repeat Business','data'=>array(10,12,15,11,16),'color'=>'#ffb399');
	}
	if($searchFilters['measure']==2)
	{
		$series[]=array('name'=>'Fresh Business','data'=>array(15,8,10,6,12),'color'=>'#bea7a7');
		$series[]=array('name'=>'Repeat Business','data'=>array(8,12,5,11,16),'color'=>'#e87d7d');
	}
	
	if($searchFilters['measure']==1)
	{
		$xAxisLable = 'Fresh Business report By Products';
	}
	if($searchFilters['measure']==2)
	{
		$xAxisLable = 'Fresh Business report By Region';
	}
	$yAxisCategory='Value in Lakhs';
	$chart1Data = array('xAxisCategory'=>$xAxisCategory,'yAxisCategory'=>$yAxisCategory,'chart1Series'=>$series,'xAxisLable'=>$xAxisLable);

	$chart1Data1 = json_encode($chart1Data);
	return $chart1Data1;
}
function fresh_business_bar2_chart($category,$series_name,$searchFilters)
{
	if($searchFilters['measure']==1)
	{
		if($series_name=='Fresh Business')
		{
			$series[]=array('name'=>'Fresh Business','data'=>array(2,2,1,0,0),'color'=>'#FF9800');
			$series[]=array('name'=>'Repeat Business','data'=>array(4,0,2,0,1),'color'=>'#3F51B5');
		}
		if($series_name=='Repeat Business')
		{
			$series[]=array('name'=>'Repeat Business','data'=>array(4,0,2,1,3),'color'=>'#3F51B5');
			$series[]=array('name'=>'Fresh Business','data'=>array(2,2,1,0,0),'color'=>'#FF9800');
		}
	}
	if($searchFilters['measure']==2)
	{
		if($series_name=='Fresh Business')
		{
			$series[]=array('name'=>'Fresh Business','data'=>array(5,2,3,3,2),'color'=>'#666699');
			$series[]=array('name'=>'Repeat Business','data'=>array(4,0,2,2,0),'color'=>'#E18A07');
		}
		if($series_name=='Repeat Business')
		{
			$series[]=array('name'=>'Repeat Business','data'=>array(4,0,2,2,0),'color'=>'#E18A07');
			$series[]=array('name'=>'Fresh Business','data'=>array(5,2,3,3,2),'color'=>'#666699');
		}
	}
	if($searchFilters['measure']==1)
	{
		$xAxisLable = 'Fresh Business report for '.$category;
	}
	if($searchFilters['measure']==2)
	{
		$xAxisLable = 'Fresh Business report For '.$category;
	}
	$xAxisCategory=array('Apollo Speciality Hospital','Gail India Limited','Johnson & Johnson Limited','Raghavendra Diagnostics','Relianace Industries Ltd.');
	$yAxisCategory='Value in Lakhs';
	$chart1Data = array('xAxisCategory'=>$xAxisCategory,'yAxisCategory'=>$yAxisCategory,'chart2Series'=>$series,'xAxisLable'=>$xAxisLable);

	$chart1Data1 = json_encode($chart1Data);
	return $chart1Data1;

}