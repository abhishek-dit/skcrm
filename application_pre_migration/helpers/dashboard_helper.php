<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function getOpportunityDashboardData($user_id, $role_id, $pc_region, $timeline)
{
	$CI = & get_instance();
	$user_id = $user_id;
	$role_id = $role_id;

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

	$chartsData = array();

	// Chart 1 Data 
	$month = date('m');
	$month1 = $month + 1;
	$year = date('Y');		
	$day = getOpportunityCategorizationDate();
	$hotDay = $year."-".$month."-".$day;
	$warmDate = date('Y-m-d',strtotime( $hotDay . " +1 month " ));
	$userPCRID = getPCRID($pc_region, $userProducts, $userLocations);

	//$c1Data = json_encode($c1Data, JSON_NUMERIC_CHECK);
	$c1Data = getFunnelOpportunity($user_id, $role_id, $userProducts, $userLocations, $hotDay, $warmDate,$timeline);
	$c2AND4Data = getOpportunityClosureData($user_id, $role_id, $pc_region, $userProducts, $userLocations, $hotDay, $warmDate, $userPCRID, $timeline);
	$c5Data = getPipelineOpportunity($user_id, $role_id, $pc_region, $userProducts, $userLocations, $hotDay, $warmDate, $userPCRID,$timeline);
	$c3Data = getTopHotOpportunitiesByCustomer($user_id, $role_id, $hotDay, $userLocations, $userProducts,$timeline);
	$c6Data = getTopOpportuntityClosed($user_id, $role_id, $userLocations, $userProducts, $timeline);

	$chartsData['c1Data'] = $c1Data;
	$chartsData['pcrTitle'] = getPCRTitle($pc_region, $userProducts, $userLocations);
	$chartsData['c5Data1'] = $c5Data['Hot'];
	$chartsData['c5Data2'] = $c5Data['Warm'];
	$chartsData['c5Data3'] = $c5Data['Cold'];
	$chartsData['c2Data'] = $c2AND4Data['Percentage'];
	$chartsData['c4Data1'] = $c2AND4Data['Won'];
	$chartsData['c4Data2'] = $c2AND4Data['Lost'];
	$chartsData['c3Data1'] = $c3Data['customer'];
	$chartsData['c3Data2'] = $c3Data['price'];
	$chartsData['c6Data1'] = $c6Data['customer'];
	$chartsData['c6Data2'] = $c6Data['price'];


	$chartsData = json_encode($chartsData, JSON_NUMERIC_CHECK);
	return $chartsData;

}

function getPCRID($pc_region, $userProducts, $userLocations)
{
	$CI = & get_instance();
	if($pc_region == 1)
	{
		$q = 'SELECT pc.category_id from product_category pc 
				INNER JOIN product_group pg on pg.category_id = pc.category_id
				INNER JOIN product p ON p.group_id = pg.group_id
				WHERE pc.status = 1 AND pg.status = 1 AND p.status = 1 AND p.product_id IN ('.$userProducts.')
				group by pc.category_id';
		$r = $CI->db->query($q);
		foreach($r->result_array() as $row)
		{
			$ret[] = $row['category_id'];
		}		
		//$ret = array('RAD', 'PMS', 'CRD', 'ANS', 'RMS', 'SYP', 'ESU');
	}
	else
	{
		/*
		$q = 'SELECT l3.location from location l
				LEFT JOIN location l1 ON l.parent_id = l1.location_id
				LEFT JOIN location l2 ON l1.parent_id = l2.location_id
				LEFT JOIN location l3 ON l2.parent_id = l3.location_id
				where l.territory_level_id = 7 AND l.location_id IN ('.$userLocations.')
				group by l3.location_id';
		*/
		$q = 'SELECT l.location_id from location l where l.territory_level_id = 4 AND status = 1';		
		$r = $CI->db->query($q);
		foreach($r->result_array() as $row)
		{
			$ret[] = $row['location_id'];
		}
	}
	$ret = implode(',', $ret);
	$ret = ($ret == '')?0:$ret;
	return $ret;
}

function getPCRTitle($pc_region, $userProducts, $userLocations)
{
	$CI = & get_instance();
	if($pc_region == 1)
	{
		$q = 'SELECT pc.name from product_category pc 
				INNER JOIN product_group pg on pg.category_id = pc.category_id
				INNER JOIN product p ON p.group_id = pg.group_id
				WHERE pc.status = 1 AND pg.status = 1 AND p.status = 1 AND p.product_id IN ('.$userProducts.')
				group by pc.category_id';
		$r = $CI->db->query($q);
		foreach($r->result_array() as $row)
		{
			$ret[] = $row['name'];
		}		
		//$ret = array('RAD', 'PMS', 'CRD', 'ANS', 'RMS', 'SYP', 'ESU');
	}
	else
	{
		/*
		$q = 'SELECT l3.location from location l
				LEFT JOIN location l1 ON l.parent_id = l1.location_id
				LEFT JOIN location l2 ON l1.parent_id = l2.location_id
				LEFT JOIN location l3 ON l2.parent_id = l3.location_id
				where l.territory_level_id = 7 AND l.location_id IN ('.$userLocations.')
				group by l3.location_id';
		*/
		$q = 'SELECT l.location from location l where l.territory_level_id = 4 AND status = 1';		
		$r = $CI->db->query($q);
		foreach($r->result_array() as $row)
		{
			$ret[] = $row['location'];
		}
	}

	return $ret;
}

function role4And5($user_id, $role_id)
{
	$CI = & get_instance();
	$q = '';
	if($role_id == 4 || $role_id == 5)
	{
		$q .= ' AND l.user_id = "'.$user_id.'"';
	}
	return $q;
}

function role4And5CI($user_id, $role_id)
{
	$ci = & get_instance();
	if($role_id == 4 || $role_id == 5){
		return $ci->db->where('l.user_id',$user_id);
	}
	return '';
}

function getFunnelOpportunity($user_id, $role_id, $userProducts, $userLocations, $hotDay, $warmDate,$timeline)
{
	$CI = & get_instance();
	$q = 'SELECT sum(case when expected_order_conclusion <= "'.$hotDay.'" then round((p.rrp*o.required_quantity)/100000, 2) else 0 end) as Hot,
			sum(case when (expected_order_conclusion <= "'.$warmDate.'" AND expected_order_conclusion > "'.$hotDay.'") then round((p.rrp*o.required_quantity)/100000, 2) else 0 end) as Warm,
			sum(case when expected_order_conclusion > "'.$warmDate.'" then round((p.rrp*o.required_quantity)/100000, 2) else 0 end) as Cold
			from opportunity o
			INNER JOIN opportunity_product op ON op.opportunity_id = o.opportunity_id
			INNER JOIN product p ON p.product_id = op.product_id
			INNER JOIN lead l ON l.lead_id = o.lead_id
			where o.status IN (1,2,3,4,5) AND op.product_id IN ('.$userProducts.') AND l.location_id IN ('.$userLocations.') ';
	
	$q .= role4And5($user_id, $role_id);
	$q.=getOpportunityTimelineCheck($timeline);

	$r = $CI->db->query($q);
	//echo $CI->db->last_query();
	foreach ($r->result_array() as $row) 
	{
		$row['Hot'] = ($row['Hot'] == '')?0:$row['Hot'];
		$row['Warm'] = ($row['Warm'] == '')?0:$row['Warm'];
		$row['Cold'] = ($row['Cold'] == '')?0:$row['Cold'];
		$c1Data[] = array('Cold', $row['Cold']);
		$c1Data[] = array('Warm', $row['Warm']);
		$c1Data[] = array('Hot', $row['Hot']);
	}
	return $c1Data;
}

function getPipelineOpportunity($user_id, $role_id, $pc_region, $userProducts, $userLocations, $hotDay, $warmDate, $userPCRID,$timeline)
{
	$CI = & get_instance();
	if($pc_region == 1)
	{
		$q = 'SELECT pc.name, case when t.Hot IS NULL then 0 else t.Hot end as Hot,
				case when t.Warm IS NULL then 0 else t.Warm end as Warm,
				case when t.Cold IS NULL then 0 else t.Cold end as Cold from
				(SELECT pc.category_id, sum(case when expected_order_conclusion <= "'.$hotDay.'" then round((p.rrp*o.required_quantity)/100000, 2) else 0 end) as Hot,
				sum(case when (expected_order_conclusion <= "'.$warmDate.'" AND expected_order_conclusion > "'.$hotDay.'") then round((p.rrp*o.required_quantity)/100000, 2) else 0 end) as Warm,
				sum(case when expected_order_conclusion > "'.$warmDate.'" then round((p.rrp*o.required_quantity)/100000, 2) else 0 end) as Cold
				from opportunity o
				INNER JOIN lead l ON l.lead_id = o.lead_id
				INNER JOIN opportunity_product op ON op.opportunity_id = o.opportunity_id
				INNER JOIN product p ON p.product_id = op.product_id
				INNER JOIN product_group pg ON pg.group_id = p.group_id
				INNER JOIN product_category pc ON pc.category_id = pg.category_id
				where o.status IN (1,2,3,4,5) AND  op.product_id IN ('.$userProducts.') AND l.location_id IN ('.$userLocations.')';

		$q .= role4And5($user_id, $role_id);
		$q.=getOpportunityTimelineCheck($timeline);
		
		$q .= 'group by pc.category_id) t
				RIGHT JOIN product_category pc ON pc.category_id = t.category_id
				WHERE pc.category_id IN ('.$userPCRID.')';
	}
	else
	{
		$q = 'SELECT l.location, case when t.Hot IS NULL then 0 else t.Hot end as Hot,
				case when t.Warm IS NULL then 0 else t.Warm end as Warm,
				case when t.Cold IS NULL then 0 else t.Cold end as Cold from
				(SELECT l3.parent_id, sum(case when expected_order_conclusion <= "'.$hotDay.'" then round((p.rrp*o.required_quantity)/100000, 2) else 0 end) as Hot,
				sum(case when (expected_order_conclusion <= "'.$warmDate.'" AND expected_order_conclusion > "'.$hotDay.'") then round((p.rrp*o.required_quantity)/100000, 2) else 0 end) as Warm,
				sum(case when expected_order_conclusion > "'.$warmDate.'" then round((p.rrp*o.required_quantity)/100000, 2) else 0 end) as Cold
				from opportunity o
				INNER JOIN lead l ON l.lead_id = o.lead_id
				INNER JOIN opportunity_product op ON op.opportunity_id = o.opportunity_id
				INNER JOIN product p ON p.product_id = op.product_id
				INNER JOIN location l1 ON l1.location_id = l.location_id
				INNER JOIN location l2 ON l1.parent_id = l2.location_id
				INNER JOIN location l3 ON l2.parent_id = l3.location_id
				where o.status IN (1,2,3,4,5) AND  op.product_id IN ('.$userProducts.') AND l.location_id IN ('.$userLocations.') ';
				$q.=getOpportunityTimelineCheck($timeline);
				$q.= ' group by l3.parent_id) t
				RIGHT JOIN location l ON l.location_id = t.parent_id
				WHERE l.location_id IN ('.$userPCRID.')';
	}
	$r = $CI->db->query($q);
	foreach($r->result_array() as $row)
	{
		$ret['Hot'][] = $row['Hot'];
		$ret['Warm'][] = $row['Warm'];
		$ret['Cold'][] = $row['Cold'];
	}

	return $ret;
}

function getOpportunityClosureData($user_id, $role_id, $pc_region, $userProducts, $userLocations, $hotDay, $warmDate, $userPCRID, $timeline)
{
	$CI = & get_instance();
	if($pc_region == 1)
	{
		$q = 'SELECT pc.category_id, pc.name, case when t.won IS NULL then 0 else t.won end as won,
				case when t.lost IS NULL then 0 else t.lost end as lost,
				case when t.wonnumber IS NULL then 0 else t.wonnumber end as wonnumber,
				case when t.lostnumber IS NULL then 0 else t.lostnumber end as lostnumber from
				(SELECT pc.category_id, sum(case when o.status = 6 then round((p.rrp*o.required_quantity)/100000, 2) else 0 end) as won, 
				sum(case when (o.status = 7 OR o.status = 8) then round((p.rrp*o.required_quantity)/100000, 2) else 0 end) as lost,
				sum(case when o.status = 6 then 1 else 0 end) as wonnumber, 
				sum(case when (o.status = 7 OR o.status = 8) then 1 else 0 end) as lostnumber
				from opportunity o
				INNER JOIN opportunity_status_history os ON os.opportunity_id = o.opportunity_id AND os.status = o.status 
				INNER JOIN lead l ON l.lead_id = o.lead_id
				INNER JOIN opportunity_product op ON op.opportunity_id = o.opportunity_id
				INNER JOIN product p ON p.product_id = op.product_id
				INNER JOIN product_group pg ON pg.group_id = p.group_id
				INNER JOIN product_category pc ON pc.category_id = pg.category_id
				where o.status IN (7,6,8) AND op.product_id IN ('.$userProducts.') AND l.location_id IN ('.$userLocations.')';

		$q .= role4And5($user_id, $role_id);
	
		$q .= getOpportunityTimelineCheck($timeline);

		$q .= ' group by pc.category_id) as t
				RIGHT JOIN product_category pc ON pc.category_id = t.category_id
				WHERE pc.category_id IN ('.$userPCRID.')';
	}
	else
	{
		$q = 'SELECT l.location, case when t.won IS NULL then 0 else t.won end as won,
				case when t.lost IS NULL then 0 else t.lost end as lost,
				case when t.wonnumber IS NULL then 0 else t.wonnumber end as wonnumber,
				case when t.lostnumber IS NULL then 0 else t.lostnumber end as lostnumber from
				(SELECT l3.parent_id, sum(case when o.status = 6 then round((p.rrp*o.required_quantity)/1000, 2) else 0 end) as won, 
				sum(case when (o.status = 7 OR o.status = 8) then round((p.rrp*o.required_quantity)/1000, 2) else 0 end) as lost,
				sum(case when o.status = 6 then 1 else 0 end) as wonnumber, 
				sum(case when (o.status = 7 OR o.status = 8) then 1 else 0 end) as lostnumber
				from opportunity o
				INNER JOIN lead l ON l.lead_id = o.lead_id
				INNER JOIN opportunity_status_history os ON os.opportunity_id = o.opportunity_id AND os.status = o.status 
				INNER JOIN opportunity_product op ON op.opportunity_id = o.opportunity_id
				INNER JOIN product p ON p.product_id = op.product_id
				INNER JOIN location l1 ON l1.location_id = l.location_id
				INNER JOIN location l2 ON l1.parent_id = l2.location_id
				INNER JOIN location l3 ON l2.parent_id = l3.location_id
				where o.status IN (7,6,8) AND  op.product_id IN ('.$userProducts.') AND l.location_id IN ('.$userLocations.')';

		$q .= getOpportunityTimelineCheck($timeline);

		$q .= ' group by l3.parent_id) t
				RIGHT JOIN location l ON l.location_id = t.parent_id
				WHERE l.location_id IN ('.$userPCRID.')';
	}
	$r = $CI->db->query($q);
	foreach($r->result_array() as $row)
	{
		$ret['Won'][] = $row['won'];
		$ret['Lost'][] = $row['lost'];
		//$ret['Percentage'][] = 10;
		$ret['Percentage'][] = getPercentage($row['wonnumber'], $row['lostnumber']);
	}

	return $ret;
}

function getPercentage($won, $lost)
{
	if($won == 0) $ret = 0;
	else $ret = $won*100/($won+$lost);
	return $ret;
}

function getTopHotOpportunities($user_id, $role_id, $hotDay, $userLocations, $userProducts)
{
	$CI = & get_instance();
	$q = 'SELECT LEFT(c.name, 12) as customer, p.name, round(p.mrp/100000,2) as price from opportunity o
			INNER JOIN lead l ON l.lead_id = o.lead_id
			INNER JOIN customer c ON c.customer_id = l.customer_id
			INNER JOIN opportunity_product op ON op.opportunity_id = o.opportunity_id
			INNER JOIN product p ON p.product_id = op.product_id
			WHERE o.status IN (1,2,3,4,5) AND o.expected_order_conclusion <= "'.$hotDay.'"
			AND p.product_id IN ('.$userProducts.') AND l.location_id IN ('.$userLocations.')';

	$q .= role4And5($user_id, $role_id);

	$q .= ' Order by p.mrp desc
			limit 0, 5';

	$r = $CI->db->query($q);
	if($r->num_rows() > 0)
	{
		foreach($r->result_array() as $row)
		{
			$ret['customer'][] = $row['customer'];
			$ret['price'][] = $row['price'];
		}
	}
	else
	{
		$ret['customer'][] = 'No Records';
		$ret['price'][] = 0;
	}
	return $ret;
}

function getTopHotOpportunitiesByCustomer($user_id, $role_id, $hotDay, $userLocations, $userProducts,$timeline)
{
	$CI = & get_instance();
	$q = 'SELECT LEFT(c.name, 12) as customer, CONCAT(c.name," - ",l4.location) as customer_info, round(sum(p.mrp)/100000,2) as price from opportunity o
			INNER JOIN lead l ON l.lead_id = o.lead_id
			INNER JOIN customer c ON c.customer_id = l.customer_id
			INNER JOIN customer_location cl ON c.customer_id = cl.customer_id
			INNER JOIN location l1 ON l1.location_id = cl.location_id
			INNER JOIN location l2 ON l1.parent_id = l2.location_id
			INNER JOIN location l3 ON l2.parent_id = l3.location_id
			INNER JOIN location l4 ON l3.parent_id = l4.location_id
			INNER JOIN opportunity_product op ON op.opportunity_id = o.opportunity_id
			INNER JOIN product p ON p.product_id = op.product_id
			WHERE o.status IN (1,2,3,4,5)
			AND p.product_id IN ('.$userProducts.') AND l.location_id IN ('.$userLocations.')';

	$q .= role4And5($user_id, $role_id);
	$q.=getOpportunityTimelineCheck($timeline);
	$q.= ' group by c.customer_id Order by sum(p.mrp) desc
			limit 0, 5';

	$r = $CI->db->query($q);
	if($r->num_rows() > 0)
	{
		foreach($r->result_array() as $row)
		{
			$ret['customer'][] = $row['customer_info'];
			$ret['price'][] = $row['price'];
		}
	}
	else
	{
		$ret['customer'][] = 'No Records';
		$ret['price'][] = 0;
	}
	return $ret;
}


function getTopOpportuntityClosed($user_id, $role_id, $userLocations, $userProducts, $timeline)
{
	$CI = & get_instance();
	$q = 'SELECT LEFT(c.name, 12) as customer, CONCAT(c.name," - ",l4.location) as customer_info, p.name, round(p.mrp/100000,2) as price from opportunity o
			INNER JOIN lead l ON l.lead_id = o.lead_id
			INNER JOIN opportunity_status_history os ON os.opportunity_id = o.opportunity_id AND os.status = o.status 
			INNER JOIN customer c ON c.customer_id = l.customer_id
			INNER JOIN customer_location cl ON c.customer_id = cl.customer_id
			INNER JOIN location l1 ON l1.location_id = cl.location_id
			INNER JOIN location l2 ON l1.parent_id = l2.location_id
			INNER JOIN location l3 ON l2.parent_id = l3.location_id
			INNER JOIN location l4 ON l3.parent_id = l4.location_id
			INNER JOIN opportunity_product op ON op.opportunity_id = o.opportunity_id
			INNER JOIN product p ON p.product_id = op.product_id
			WHERE o.status IN (6)
			AND p.product_id IN ('.$userProducts.') AND l.location_id IN ('.$userLocations.')';

	$q .= role4And5($user_id, $role_id);

	$q .= getOpportunityTimelineCheck($timeline);

	$q .= 'Order by p.mrp desc
			limit 0, 5';

	$r = $CI->db->query($q);
	if($r->num_rows() > 0)
	{
		foreach($r->result_array() as $row)
		{
			$ret['customer'][] = $row['customer_info'];
			$ret['price'][] = $row['price'];
		}
	}
	else
	{
		$ret['customer'][] = 'No Records';
		$ret['price'][] = 0;
	}
	return $ret;

}

function getOpportunityTimelineCheck($timeline)
{   
	$CI = & get_instance();
	switch($timeline)
	{
		case 1:
			$q = ' AND month(o.created_time) = month(curdate()) AND year(o.created_time) = year(curdate()) ';
			break;
		case 2: 
			$curdate=date('Y-m-d');
		    $fy_year = $CI->Common_model->get_data_row('financial_year',array("start_date>="=>$curdate,"end_date<="=>$curdate,'status'=>1));
		    $cur_month=date('m');
            
            		switch($cur_month)
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
            		//print_r($qtr);exit;
			$q = ' AND month(o.created_time) IN ('.implode(',',$qtr).') AND year(o.created_time) = year(curdate()) ';
			break;
		case 3:
		    $curdate=date('Y-m-d');
		    $fy_year = $CI->Common_model->get_data_row('financial_year',array("start_date<="=>$curdate,"end_date>="=>$curdate,'status'=>1));
		    $start = $fy_year['start_date'];
			$end = $fy_year['end_date'];
			$q = ' AND date(o.created_time) BETWEEN "'.$start.'" AND "'.$end.'" ';
			break;
	}

	return $q;
}



// Leads Dashboard Data


function getTargetVsActualPercentage($user_id, $role_id, $frequency=1,$userLocations, $userProducts){
	$target = getTargetRevenue($user_id, $frequency);
	$reached = getRevenueGenerated($user_id, $role_id, $frequency,$userLocations, $userProducts);
	if($target==0) return 0;
	$percentage =round(($reached/$target)*100,2);
	//echo $percentage;
	return ($percentage>100)?100:$percentage;
}

function getTargetRevenue($user_id, $frequency=1)
{
	$ret = 0;
	$ci = & get_instance();
	$ci->db->select('sum(p.rrp*up.quantity) as target'); // Phase2 update: mahesh 04-09-2017
	$ci->db->from('user_product_target up');
	$ci->db->join('user_product u','u.user_id=up.user_id AND up.product_id=u.product_id','inner');
	$ci->db->join('product p','p.product_id=up.product_id','inner');
	$ci->db->where('p.status',1);
	$ci->db->where('up.status',1);
	$ci->db->where('p.target',1);
	$ci->db->where('up.user_id',$user_id);
	switch ($frequency) {
		case 1:
			$ci->db->where('up.month_id',date('m'));
			$ci->db->where('up.year_id',date('Y'));
		break;
		case 2:
			$months_in_quarter = array();
			switch(date('m')){
				case 1: case 2: case 3:
					$months_in_quarter = array(1,2,3);
				break;
				case 4: case 5: case 6:
					$months_in_quarter = array(4,5,6);
				break;
				case 7: case 8: case 9:
					$months_in_quarter = array(7,8,9);
				break;
				case 10: case 11: case 12:
					$months_in_quarter = array(10,11,12);
				break;
			}
			$ci->db->where_in('up.month_id',$months_in_quarter);
			$ci->db->where('up.year_id',date('Y'));
		break;
		case 3:
			$current_month = date('m');
			$current_year = date('Y');
			// defining year
			if($current_month<=3){
				$yr1 = $current_year-1;
				$yr2 = $current_year;
			}
			else{
				$yr1 = $current_year;
				$yr2 = $current_year+1;
			}
			$where_str = '((up.year_id='.$yr1.' AND up.month_id>3) OR (up.year_id = "'.$yr2.'" AND up.month_id < 4))';
			$ci->db->where($where_str);
		break;
	}
	$r = $ci->db->get();
	//echo $ci->db->last_query();
	if($r->num_rows() > 0)
	{
		$data = $r->row_array();
		$ret = $data['target'];
		$ret = ($ret>0)?$ret:0;
	}
	return $ret;
}

//mahesh 9th sept 2016, 03:16 pm
function getRevenueGenerated($user_id, $role_id, $frequency=1,$locations, $userProducts)
{
	$ret = 0;
	$ci = & get_instance();
	$ci->db->select('round(sum(mrp*o.required_quantity*(1-(discount/100))*(1+(freight_insurance/100)))) as total');
	$ci->db->from('opportunity o');
	$ci->db->join('opportunity_product op','op.opportunity_id = o.opportunity_id','inner');
	$ci->db->join('lead l','l.lead_id = o.lead_id','inner');
	$ci->db->where('o.status',6);

	if($role_id == 4 || $role_id == 5){
		$ci->db->where('l.user_id',$user_id);
	}
	else {
		$ci->db->where('l.location_id IN ('.$locations.')');
		$ci->db->where('op.product_id IN ('.$userProducts.')');
	}
	switch ($frequency) {
		case 1:
			$ci->db->where('month(o.modified_time)',date('m'));
			$ci->db->where('year(o.modified_time)',date('Y'));
		break;
		case 2:
			$months_in_quarter = array();
			switch(date('m')){
				case 1: case 2: case 3:
					$months_in_quarter = array(1,2,3);
				break;
				case 4: case 5: case 6:
					$months_in_quarter = array(4,5,6);
				break;
				case 7: case 8: case 9:
					$months_in_quarter = array(7,8,9);
				break;
				case 10: case 11: case 12:
					$months_in_quarter = array(10,11,12);
				break;
			}
			$ci->db->where_in('month(o.modified_time)',$months_in_quarter);
			$ci->db->where('year(o.modified_time)',date('Y'));
		break;
		case 3:
			$current_month = date('m');
			$current_year = date('Y');
			// defining year
			if($current_month<=3){
				$yr1 = $current_year-1;
				$yr2 = $current_year;
			}
			else{
				$yr1 = $current_year;
				$yr2 = $current_year+1;
			}
			$where_str = '((year(o.modified_time)='.$yr1.' AND month(o.modified_time)>3) OR (year(o.modified_time) = "'.$yr2.'" AND month(o.modified_time) < 4))';
			$ci->db->where($where_str);
		break;
	}
	$r = $ci->db->get();
	//echo $ci->db->last_query();
	if($r->num_rows() > 0)
	{
		$data = $r->row_array();
		$ret = $data['total'];
		$ret = ($ret>0)?$ret:0;
	}


	return $ret;
}

//mahesh 9th sept 2016, 05:39 pm
function getLeadToOpportunityConversionData($user_id, $role_id, $timeline=1,$userLocations){
	$data = array();
	$regions = getRegionsTitleAndID($userLocations);
	

	switch ($timeline) {
		case 1:
			$row = array('name'=>date('M'));
			$row_data = array();
			foreach ($regions['id'] as $key => $regionId) {
				$totalLeadsCreated = get_totalLeadsCreated($user_id, $role_id, $timeline,$regionId);
				$totalLeadsConverted = get_totalLeadsToOpportunityConverted($user_id, $role_id, $timeline,$regionId);
				if($totalLeadsCreated==0) $row_data[] = 0;
				else{
					$percentage =round(($totalLeadsConverted/$totalLeadsCreated)*100,2);
					//echo $percentage;
					$row_data[] = ($percentage>100)?100:$percentage;
				}
			}
			$row['data'] = $row_data;
			$data[]=$row;
		break;
		case 2:
			$months_in_quarter = array();
			switch(date('m')){
				case 1: case 2: case 3:
					$months_in_quarter = array('Jan'=>1,'Feb'=>2,'Mar'=>3);
				break;
				case 4: case 5: case 6:
					$months_in_quarter = array('Apr'=>4,'May'=>5,'Jun'=>6);
				break;
				case 7: case 8: case 9:
					$months_in_quarter = array('Jul'=>7,'Aug'=>8,'Sep'=>9);
				break;
				case 10: case 11: case 12:
					$months_in_quarter = array('Oct'=>10,'Nov'=>11,'Dec'=>12);
				break;
			}
			foreach ($months_in_quarter as $monthName=>$month) {
				$row = array('name'=>$monthName);
				$row_data = array();
				foreach ($regions['id'] as $key => $regionId) {
					$totalLeadsCreated = get_totalLeadsCreated($user_id, $role_id, $timeline,$regionId,$month);
					$totalLeadsConverted = get_totalLeadsToOpportunityConverted($user_id, $role_id, $timeline,$regionId,$month);
					if($totalLeadsCreated==0) $row_data[] = 0;
					else{
						$percentage =round(($totalLeadsConverted/$totalLeadsCreated)*100,2);
						//echo $percentage;
						$row_data[] = ($percentage>100)?100:$percentage;
					}
				}
				$row['data'] = $row_data;
				$data[]=$row;
			}
		break;
		case 3:
			$year = date('Y');
			$month = date('m');
			$year1 = ($month<4)?$year-1:$year;
			$year2 = $year1+1;
			$row = array('name'=>$year1.'-'.$year2);
			$row_data = array();
			foreach ($regions['id'] as $key => $regionId) {
				$totalLeadsCreated = get_totalLeadsCreated($user_id, $role_id, $timeline,$regionId);
				$totalLeadsConverted = get_totalLeadsToOpportunityConverted($user_id, $role_id, $timeline,$regionId);
				if($totalLeadsCreated==0) $row_data[] = 0;
				else{
					$percentage =round(($totalLeadsConverted/$totalLeadsCreated)*100,2);
					//echo $percentage;
					$row_data[] = ($percentage>100)?100:$percentage;
				}
			}
			$row['data'] = $row_data;
			$data[]=$row;
		break;
	}

	return $data;	
}

//mahesh 9th sept 2016, 05:41 pm
function get_totalLeadsCreated($user_id, $role_id, $timeline=1,$regionId,$month=0){
	$ci = & get_instance();
	$ret= 0;
	$ci->db->select('count(*) as total');
	$ci->db->from('lead l');
	$ci->db->join('location l1','l1.location_id=l.location_id','inner');
	$ci->db->join('location l2','l2.location_id=l1.parent_id','inner');
	$ci->db->join('location l3','l3.location_id=l2.parent_id','inner');
	$ci->db->where('l3.parent_id',$regionId);
	role4And5CI($user_id, $role_id);
	//$ci->db->where('l.location_id IN ('.$userLocations.')');
	switch ($timeline) {
		case 1:
			$ci->db->where('month(l.created_time)',date('m'));
			$ci->db->where('year(l.created_time)',date('Y'));
		break;
		case 2:
			
			$ci->db->where('month(l.created_time)',$month);
			$ci->db->where('year(l.created_time)',date('Y'));
		break;
		case 3:
			$current_month = date('m');
			$current_year = date('Y');
			// defining year
			if($current_month<=3){
				$yr1 = $current_year-1;
				$yr2 = $current_year;
			}
			else{
				$yr1 = $current_year;
				$yr2 = $current_year+1;
			}
			$where_str = '(year(l.created_time)='.$yr1.' AND month(l.created_time)>3) OR (year(l.created_time) = "'.$yr2.'" AND month(l.created_time) < 4)';
			$ci->db->where($where_str);
		break;
	}
	$r = $ci->db->get();
	//echo $ci->db->last_query();
	if($r->num_rows() > 0)
	{
		$data = $r->row_array();
		$ret = $data['total'];
		$ret = ($ret>0)?$ret:0;
	}
	return $ret;
}

//mahesh 9th sept 2016, 05:41 pm
function get_totalLeadsToOpportunityConverted($user_id, $role_id, $timeline=1,$regionId,$month=0){
	$ci = & get_instance();
	$ret= 0;
	$ci->db->select('count(*) as total');
	$ci->db->from('lead l');
	$ci->db->join('lead_status_history lsh','l.lead_id=lsh.lead_id','inner');
	$ci->db->where('lsh.status',3);
	$ci->db->join('location l1','l1.location_id=l.location_id','inner');
	$ci->db->join('location l2','l2.location_id=l1.parent_id','inner');
	$ci->db->join('location l3','l3.location_id=l2.parent_id','inner');
	$ci->db->where('l3.parent_id',$regionId);
	role4And5CI($user_id, $role_id);
	//$ci->db->where('l.location_id = "'.$userLocations.'"');
	switch ($timeline) {
		case 1:
			$ci->db->where('month(l.created_time)',date('m'));
			$ci->db->where('year(l.created_time)',date('Y'));
		break;
		case 2:
			$ci->db->where('month(l.created_time)',$month);
			$ci->db->where('year(l.created_time)',date('Y'));
		break;
		case 3:
			$current_month = date('m');
			$current_year = date('Y');
			// defining year
			if($current_month<=3){
				$yr1 = $current_year-1;
				$yr2 = $current_year;
			}
			else{
				$yr1 = $current_year;
				$yr2 = $current_year+1;
			}
			$where_str = '(year(l.created_time)='.$yr1.' AND month(l.created_time)>3) OR (year(l.created_time) = "'.$yr2.'" AND month(l.created_time) < 4)';
			$ci->db->where($where_str);
		break;
	}
	$r = $ci->db->get();
	//echo $ci->db->last_query();
	if($r->num_rows() > 0)
	{
		$data = $r->row_array();
		$ret = $data['total'];
		$ret = ($ret>0)?$ret:0;
	}
	return $ret;
}

function getLeadDashboardData($user_id, $role_id, $timeline)
{
	$CI = & get_instance();
	$user_id = $user_id;
	$role_id = $role_id;

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

	$chartsData = array();


	$regionTitleAndID = getRegionsTitleAndID($userLocations);
	$regionIDs = $regionTitleAndID['id'];
	$regionIDs = implode(',', $regionIDs);
	$leadsCreated = getLeadsCreated($user_id, $role_id, $timeline, $userLocations, $userProducts, $regionIDs, $regionTitleAndID['title']);

	$chartsData['regionTitle'] = $regionTitleAndID['title'];
	$chartsData['targetVsActualPercent'] = getTargetVsActualPercentage($user_id, $role_id, $timeline, $userLocations, $userProducts);
	$chartsData['l2o_data'] = getLeadToOpportunityConversionData($user_id, $role_id, $timeline,$userLocations);

	$leadsCumilative = getLeadsCumilative($user_id, $role_id, $timeline, $userLocations, $userProducts);
	$chartsData['weeksData'] = $leadsCumilative['weeksData'];
	$chartsData['leadsCumilative'] = $leadsCumilative['leadsCumilative'];



	$chartsData['leadsCreated'] = $leadsCreated['value'];
	$chartsData['leadsCreatedTitle'] = $leadsCreated['title'];

	$chartsData = json_encode($chartsData, JSON_NUMERIC_CHECK);
	//echo $chartsData;
	return $chartsData;
}

function getLeadsCumilative($user_id, $role_id, $timeline, $userLocations, $userProducts)
{
	//echo $timeline;
	$weeksData = array();
	$leadsCumilative = array();
	$CI = & get_instance();

	$q = 'SELECT week(created_time) as week, count(*) as value from lead l 
			where l.location_id IN ('.$userLocations.') AND date(created_time) >= ';
	$current_year = date('Y');
	$previous_year = $current_year - 1;
	$current_month = date('m');
	//$current_month = 1;
	$previous_month = ($current_month == 1)? 12: ($current_month - 1);
	$previous_month_year = ($current_month == 1)? ($current_year - 1) : $current_year;

	switch($timeline)
	{
		case 1:
			$start_date = $previous_month_year.'-'.$previous_month.'-1';
			break;
		case 2:
			$month = $current_month;
			$quarter_month = $month%3;
			$month_check = ceil($month/3);
			if($quarter_month == 1)
			{
				if($month_check == 1)
				{
					$start_date = $previous_month_year.'-'.$previous_month.'-1';
				}
				else
					$start_date = $current_year.'-'.$previous_month.'-1';
			}
			else
			{
				$start_date = $current_year.'-'.((($month_check-1)*3)+1).'-1';
			}
			break;
		
		case 3:
			if($current_month == 4)
			{
				$start_date = $current_year.'-3-1';
			}
			else if($current_month < 4)
			{
				$start_date = $previous_year.'-4-1';
			}
			else
			{
				$start_date = $current_year.'-4-1';
			}
			//echo $timeline; exit;
			//$start_date = $previous_month_year.'-'.$previous_month.'-1';
			break;		
	}

	//$start_date = $previous_month_year.'-'.$previous_month.'-1';

	$q .= "'$start_date'";
	$q .= role4And5($user_id, $role_id);
	$q .= ' AND date(created_time) <= CURDATE() group by week(created_time) order by date(created_time)';
	//echo $q;
	
	$last_week = date('W');
	$duedt = explode("-", "$start_date");
	$ddate  = mktime(0, 0, 0, $duedt[1], $duedt[2], $duedt[0]);
	$first_week = date('W', $ddate);

	if($last_week > $first_week)
	{
		for($j = $first_week; $j <= $last_week; $j++)
		{
			$weeksData[] = 'Wk '.$j;
			$leads['Wk '.$j] = 0;
		}
	}
	else
	{
		for($j = $first_week; $j <= 52; $j++)
		{
			$weeksData[] = 'Wk '.$j;
			$leads['Wk '.$j] = 0;
		}
		for($j = 1; $j <= $last_week; $j++)
		{
			$weeksData[] = 'Wk '.$j;
			$leads['Wk '.$j] = 0;
		}
	}


	$r = $CI->db->query($q);
	$i = 0;
	foreach($r->result_array() as $row)
	{
		$leads['Wk '.$row['week']] = $row['value'];
		//$weeksData[] = 'Wk '.$row['week'];
		//$i = $i + $row['value'];
		//$leadsCumilative[] = $i;
	}
	if($last_week > $first_week)
	{
		for($j = $first_week; $j <= $last_week; $j++)
		{
			$i = $i + $leads['Wk '.$j];
			$leadsCumilative[] = $i;
		}
	}
	else
	{
		for($j = $first_week; $j <= 52; $j++)
		{
			$i = $i + $leads['Wk '.$j];
			$leadsCumilative[] = $i;
		}
		for($j = 1; $j <= $last_week; $j++)
		{
			$i = $i + $leads['Wk '.$j];
			$leadsCumilative[] = $i;
		}
	}	


	$ret['weeksData'] = $weeksData;
	$ret['leadsCumilative'] = $leadsCumilative;
	//print_r($ret);	
	return $ret;
}

function getRegionsTitleAndID($userLocations)
{
	$CI = & get_instance();
	$q = 'SELECT l3.location, l3.location_id from location l
			INNER JOIN location l1 ON l1.location_id = l.parent_id
			INNER JOIN location l2 ON l2.location_id = l1.parent_id
			INNER JOIN location l3 ON l3.location_id = l2.parent_id
			WHERE l.location_id IN ('.$userLocations.')
			group by l3.location_id';
	$r = $CI->db->query($q);
	foreach($r->result_array() as $row)
	{
		$ret['title'][] = $row['location'];
		$ret['id'][] = $row['location_id'];
	}
	return $ret;
}


function getLeadsCreated($user_id, $role_id, $timeline, $userLocations, $userProducts, $regionIDs, $regionTitle)
{
	$CI = & get_instance();

	if($timeline == 1)
	{
		$q = 'SELECT l.location, case when t.total IS NULL then 0 else t.total end as total from
				(SELECT l3.parent_id, count(*) as total from lead l
				INNER JOIN location l1 ON l1.location_id = l.location_id
				INNER JOIN location l2 ON l2.location_id = l1.parent_id
				INNER JOIN location l3 ON l3.location_id = l2.parent_id
				WHERE month(l.created_time) = month(curdate() ';

		$q .= role4And5($user_id, $role_id);

		$q .= ' )group by l3.parent_id) t
				RIGHT JOIN location l ON l.location_id = t.parent_id
				WHERE l.territory_level_id = 4 AND l.location_id IN ('.$regionIDs.')';

		$r = $CI->db->query($q);
		foreach($r->result_array() as $row)
		{
			$ret['value'][] = $row['total'];
			$ret['title'] = 0;
		}
	}
	else
	{
		if($timeline == 2)
		{
			switch (date('m'))
			{
				case 1: case 2: case 3:
					$ret['title'] = array('Jan', 'Feb', 'Mar');
					break;
				case 4: case 5: case 6:
					$ret['title'] = array('Apr', 'May', 'Jun');
					break;
				case 7:case 8: case 9:
					$ret['title'] = array('Jul', 'Aug', 'Sep');
					break;
				case 10: case 11: case 12:
					$ret['title'] = array('Oct', 'Nov', 'Dec');
					break;

			}
		}
		else
		{
			$ret['title'] = array('Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar');
		}

		$regionIDs = explode(',', $regionIDs);
		$i = 0;
		foreach ($regionIDs as $key => $region) 
		{
			if($timeline == 2)
			{
				$val = array(0,0,0);

				$q = 'SELECT month(l.created_time) as month, count(*) as total from lead l
						INNER JOIN location l1 ON l1.location_id = l.location_id
						INNER JOIN location l2 ON l2.location_id = l1.parent_id
						INNER JOIN location l3 ON l3.location_id = l2.parent_id
						WHERE quarter(l.created_time) = quarter(curdate()) AND l3.parent_id = "'.$region.'" ';

				$q .= role4And5($user_id, $role_id);

				$q .= ' group by month(l.created_time)';
			
				$r = $CI->db->query($q);
				foreach($r->result_array() as $row)
				{
					$month = $row['month'];
					$month = ($month%3 == 0)? 2:($month%3) - 1;
					$val[$month] = $row['total'];
				}
			}
			else
			{
				$val = array(0,0,0,0,0,0,0,0,0,0,0,0);

				$year = date('Y');
				$month1 = date('m');
				$year1 = ($month1 < 4)? $year-1:$year;
				$year2 = $year1 + 1;
				$start = $year1.'-04-01';
				$end = $year2.'-03-31';
				//$q = ' AND year('.created_time) BETWEEN "'.$start.'" AND "'.$end.'" ';


				$q = 'SELECT month(l.created_time) as month, count(*) as total from lead l
						INNER JOIN location l1 ON l1.location_id = l.location_id
						INNER JOIN location l2 ON l2.location_id = l1.parent_id
						INNER JOIN location l3 ON l3.location_id = l2.parent_id
						WHERE date(l.created_time) BETWEEN "'.$start.'" AND "'.$end.'" AND l3.parent_id = "'.$region.'" ';

				$q .= role4And5($user_id, $role_id);

				$q .= ' group by month(l.created_time)';
				$r = $CI->db->query($q);
				foreach($r->result_array() as $row)
				{
					$month = $row['month'];
					$month = ($month%12 == 0)? 8:($month%12 > 3)?($month%12 - 4):($month%12) + 8;
					$val[$month] = $row['total'];
				}

			}
			

			$ret['value'][] = array('name' => $regionTitle[$i], 'data' => $val);

			$i++;
		}

	}

	return $ret;
}

/* file end: ./application/helpers/dashboard_helper.php */
