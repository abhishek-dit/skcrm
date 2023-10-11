<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


function getDefaultSelect2Limit()
{
	return 10;
}

function getOpportunityCategorizationDate()
{
	return 28;
}

function statusCheck($status=1) 
{
	if($status == 1)
		return 'Active';
	else
		return 'In-Active';
}

function getPhoneNumber($number)
{
	$ret = '';
	if($number != '')
	{
		$num = explode("-", $number);
		if(count($num) == 2)
		{
			$ret = "+".$num[0]." ".$num[1];
		}
		else $ret = $num[0];
	}
	$ret = $number;
	return $ret;
}

function getUserRole($user_id)
{
	$CI = & get_instance();

		
	$CI->db->select('role_id');
	$CI->db->from('user u');
	$CI->db->where('u.user_id', $user_id);
	$res = $CI->db->get();
	$data = $res->result_array();
	if($res->num_rows() > 0)
		return $data[0]['role_id'];
	return 0;
}
function getDistributorCompany($user_id)
{
	$CI = & get_instance();
	$CI->db->select('distributor_name');
	$CI->db->from('distributor_details');
	$CI->db->where('user_id', $user_id);
	$res = $CI->db->get();
	$data = $res->result_array();
	if(count($data) > 0){
		return $data[0]['distributor_name'];
	}else{
	return 0;
	}
}

function getLeadCustomerID($lead_id)
{
	$CI = & get_instance();

		
	$CI->db->select('customer_id');
	$CI->db->from('lead');
	$CI->db->where('lead_id', $lead_id);
	$res = $CI->db->get();
	$data = $res->result_array();
	if($res->num_rows() > 0)
		return $data[0]['customer_id'];
	return 0;

}


function getUserLocations($user_id)
{
	$locations = array();
	$CI = & get_instance();
	$role_id = getUserRole($user_id);
	// if($role_id == 1 || $role_id == 2 || $role_id == 3 || $role_id == 13 || $role_id == 12 || $role_id == 14)
	if($role_id == 1 || $role_id == 2 || $role_id == 3 || $role_id == 4 || $role_id == 5 || $role_id == 6 || $role_id == 7 || $role_id == 8 || $role_id == 9 || $role_id == 13 || $role_id == 12 || $role_id == 14)
	{
		$q = 'SELECT l.location_id as location from location l
				left join location l1 on l1.parent_id = l.location_id
				where l1.location_id IS NULL and l.status = 1 and l.territory_level_id = 7';
		$r = $CI->db->query($q);
		foreach($r->result_array() as $res)
		{
			$locations[] = $res['location'];
		}
	}
	else
	{
		$q1 = 'SELECT l.location_id as location_id, l.territory_level_id as territory_level_id from user_location ul
				inner join location l on l.location_id = ul.location_id
				where ul.user_id = "'.$user_id.'" and ul.status = 1 and l.status = 1';
		$r1 = $CI->db->query($q1);
		foreach($r1->result_array() as $res1)
		{
			$territory_level_id = $res1['territory_level_id'];
			$loc = $res1['location_id'];
			$level = 7 - $territory_level_id;
			$q2 = 'SELECT l'.$level.'.location_id as location from location l0';
			for($i = 1; $i <= $level; $i++)
			{
				$j = $i - 1;
				$q2 .= ' left join location l'.$i.' on l'.$j.'.location_id = l'.$i.'.parent_id';
			}
			$q2 .= ' where l0.location_id = "'.$loc.'" AND l'.$level.'.location_id != "" order by l'.$level.'.location_id';
			
			$r2 = $CI->db->query($q2);
			foreach($r2->result_array() as $res2)
			{
				$locations[] = $res2['location'];
			}
			//echo $q2; die();
		}		
	}
	return $locations;
}

function getUserLocationTree($user_id)
{
	$ret = '';
	$locations = array();
	$role_id = getUserRole($user_id);
	$CI = & get_instance();
	if($role_id == 13 || $role_id == 11 || $role_id == 14 || $role_id == 3)
	{
		//echo 1;
		$q = 'SELECT location_id from location where status = 1';
		$r = $CI->db->query($q);
		foreach($r->result_array() as $row)
		{
			$locations[] = $row['location_id'];
		}
	}
	else
	{
		$q = 'SELECT l.location_id as location_id, l.territory_level_id as territory_level_id from user_location ul
				inner join location l on l.location_id = ul.location_id
				where ul.user_id = "'.$user_id.'" and ul.status = 1 and l.status = 1';
		$r = $CI->db->query($q);
		foreach($r->result_array() as $res)
		{
			$locations[] = $res['location_id'];
			$locString = $res['location_id'];
			$territory_level_id = $res['territory_level_id'];
			for($i = $territory_level_id + 1; $i <= 7; $i++)
			{
				$locArray = array();
				if($locString != '')
				{
					$locArray = array();
					$q = 'SELECT location_id from location where parent_id IN ('.$locString.')';
					$r = $CI->db->query($q);
					foreach($r->result_array() as $row)
					{
						$locArray[] = $row['location_id'];
						$locations[] = $row['location_id'];
					}
					$locString = getQueryArray($locArray);
				}
			}

		}
	}
	$ret = getQueryArray($locations);
	return $ret;
}

function getReportingUsers($user_id)
{
	$ret = '';
	$CI = & get_instance();
	$role_id = getUserRole($user_id);
	if($role_id == 1 || $role_id == 2 || $role_id == 12 || $role_id == 4 || $role_id == 5)
	{
		$ret = '';
	}	
	else
	{
		$users = array();
		$roles = getReporteeRoles($role_id, 1);
		$locations = getUserLocationTree($user_id);
		if($locations == '') $locations = 0;
		if($roles == '') $roles = 0;
		$q = 'SELECT u.user_id from user u
			INNER JOIN user_location ul on u.user_id = ul.user_id
			where u.status = 1 AND u.company_id = '.$_SESSION['company'].' AND ul.status = 1 AND ul.location_id IN ('.$locations.') AND u.role_id IN ('.$roles.', 5)  
			group by u.user_id';
		$r = $CI->db->query($q);
		foreach($r->result_array() as $row)
		{
			$users[] = $row['user_id'];
		}
		$ret = getQueryArray($users);
	}
	if($ret == '') $ret = 0;
	return $ret;
}

function getQueryArray($array)
{
	$ret = '';
	for($i = 0; $i < count($array); $i++)
	{
		if($i > 0) $ret .= ',';
		$ret .= $array[$i];
	}
	return $ret;
}

function getLocationInfo($val)
{
	$CI = & get_instance();
	$limit = getDefaultSelect2Limit();
    //$locations = getUserLocations($CI->session->userdata('user_id'));
    //$loc = getQueryArray($locations);
    $loc = $CI->session->userdata('locationString');
    //echo "<pre>"; print_r($loc); exit();
    $data = [];
    if($loc == '')
    	$loc = 0;
    $q = "SELECT t.location_id, t.location from (
    	SELECT l.location_id, concat(l.location, ' (', l1.location, ')') as location from location l
    	LEFT JOIN location l1 on l1.location_id = l.parent_id 
    	WHERE l.location_id IN (".$loc.") and l.status = 1) t where t.location like '%".$val."%' order by t.location_id
		limit 0,".$limit;
	$res = $CI->db->query($q);
    foreach($res->result_array() as $row)
    {
    	$data[] = array('id'=>$row['location_id'], 'text'=>$row['location']);
    }
    return $data;
}

function getCustomerInfo($val)
{
	$CI = & get_instance();
	$limit = getDefaultSelect2Limit();
    //$locations = getUserLocations($CI->session->userdata('user_id'));
    //$loc = getQueryArray($locations);
    $loc = $CI->session->userdata('locationString');
    $data = [];
    //$data[] = array('id'=>'', 'text'=>'Select Customer');
    if($loc == '')
    	$loc = 0;
    /*$q = "SELECT t.customer_id, cName from
		(SELECT c.customer_id, concat(c.name, ' (', l.location, ')') as cName from customer c
		INNER JOIN customer_location cl on cl.customer_id = c.customer_id
		INNER JOIN location l on l.location_id = cl.location_id
		where cl.location_id in (".$loc.") and c.status = 1 and l.status = 1) t
		where t.cName like '%".$val."%'
		order by t.customer_id limit 0,".$limit;*/
	/*
	$q = "SELECT c.customer_id, concat(c.name, ' (', l.location, ')') as cName from customer c
			INNER JOIN customer_location cl on cl.customer_id = c.customer_id
			INNER JOIN location l on l.location_id = cl.location_id
			where cl.location_id in (743,744,745,746,747,748,749,750,751,752,753,754,755,839,840,841,842,843,844,845,846,847,848,849,850,851,852,853,854,855,856,857,858,859,860,861,862,863,864,865,1037,1038,1039,1040,1041,1042,1043,1044,1045,1046,1047,1048,1049,1050,1051,1052,1053,1054,1055,1056,1057,1058,1059,1060,1061,1062,1063,1064,1065,1066,1067,1068,1069,1070,1071,1072,1073,1074,1075,1076,1077,1078,1079,1080,1081,1082,1083,1084,1085,1086,1286,1287,1288,1289,1290,1291,1292,1293,1294,1295,1417,1429,1431,1443,1447,1454,1458,1466,1468,1472,1479,1481,1489,1491,1494,1498,1512,1514,1523,1524,1525,1527,1528,1535,1543,1549,1552,1565,1566,1584,1590,1592,1594,1595,1604,1607,1617,1620,1643,1655,1665,1672,1673,1674,1681,1683,1694,1701,1705,1707,1708,1712,1724,1725,1751,1752,1755,1757,1759,1762,1773,1776,1786,1805,1807,1808,1811,1813,1816,1817,1831,1832,1833,1842,1851,1852,1860,1861,1864,1869,1872,1877,1885,1892,1894,1895,1898,1903,1904,1906,1907,1908,1911,1918,1926,1935,1937,1938,1940,1941,1942,1946,1961,1965,1970,1971,1972,1980,1987,2006,2012,2016,2020,2022,2023,2031,2033,2055,2060,2061,2062,2063,2067,2071,2077,2079,2081,2083,2089,2090,2095,2100,2114,2153,2158,2166,2171,2174,2182,2190,2192,2194,2195,2198,2201,2202,2203,2208,2217,2227,2228,2237,2247,2256,2264,2266,2269,2270,2276,2278,2280,2284,2286,2290,2291,2296,2300,2304,2311,2312,2314,2316,2318,2319,2320,2321,2330,2331,2333,2346,2349,2352,2361)  
			and c.status = 1 and l.status = 1
			AND concat(c.name, ' (', l.location, ')') like '%".$val."%'
			limit 0,".$limit;
	*/
	$q = "SELECT c.customer_id, concat(c.name, ' (', l.location, ')') as cName from customer c
			INNER JOIN customer_location cl on cl.customer_id = c.customer_id
			INNER JOIN location l on l.location_id = cl.location_id
			where cl.location_id in (".$loc.")  
			and c.status = 1 and l.status = 1
			AND c.company_id = '".$CI->session->userdata('company')."'
			AND concat(c.name, ' (', l.location, ')') like '%".$CI->db->escape_str($val)."%'
			limit 0,".$limit;
	$res = $CI->db->query($q);	
    foreach($res->result_array() as $row)
    {
    	$data[] = array('id'=>$row['customer_id'], 'text'=>$row['cName']);
    }
    return $data;

}

function getAllCustomersInfo($val)
{
	$CI = & get_instance();
	$limit = getDefaultSelect2Limit();
    //$locations = getUserLocations($CI->session->userdata('user_id'));
    //$loc = getQueryArray($locations);
    $loc = $CI->session->userdata('locationString');
    $data = [];
    //$data[] = array('id'=>'', 'text'=>'Select Customer');
    if($loc == '')
    	$loc = 0;
    /*$q = "SELECT t.customer_id, cName from
		(SELECT c.customer_id, concat(c.name, ' (', l.location, ')') as cName from customer c
		INNER JOIN customer_location cl on cl.customer_id = c.customer_id
		INNER JOIN location l on l.location_id = cl.location_id
		where cl.location_id in (".$loc.") and c.status = 1 and l.status = 1) t
		where t.cName like '%".$val."%'
		order by t.customer_id limit 0,".$limit;*/
	/*
	$q = "SELECT c.customer_id, concat(c.name, ' (', l.location, ')') as cName from customer c
			INNER JOIN customer_location cl on cl.customer_id = c.customer_id
			INNER JOIN location l on l.location_id = cl.location_id
			where cl.location_id in (743,744,745,746,747,748,749,750,751,752,753,754,755,839,840,841,842,843,844,845,846,847,848,849,850,851,852,853,854,855,856,857,858,859,860,861,862,863,864,865,1037,1038,1039,1040,1041,1042,1043,1044,1045,1046,1047,1048,1049,1050,1051,1052,1053,1054,1055,1056,1057,1058,1059,1060,1061,1062,1063,1064,1065,1066,1067,1068,1069,1070,1071,1072,1073,1074,1075,1076,1077,1078,1079,1080,1081,1082,1083,1084,1085,1086,1286,1287,1288,1289,1290,1291,1292,1293,1294,1295,1417,1429,1431,1443,1447,1454,1458,1466,1468,1472,1479,1481,1489,1491,1494,1498,1512,1514,1523,1524,1525,1527,1528,1535,1543,1549,1552,1565,1566,1584,1590,1592,1594,1595,1604,1607,1617,1620,1643,1655,1665,1672,1673,1674,1681,1683,1694,1701,1705,1707,1708,1712,1724,1725,1751,1752,1755,1757,1759,1762,1773,1776,1786,1805,1807,1808,1811,1813,1816,1817,1831,1832,1833,1842,1851,1852,1860,1861,1864,1869,1872,1877,1885,1892,1894,1895,1898,1903,1904,1906,1907,1908,1911,1918,1926,1935,1937,1938,1940,1941,1942,1946,1961,1965,1970,1971,1972,1980,1987,2006,2012,2016,2020,2022,2023,2031,2033,2055,2060,2061,2062,2063,2067,2071,2077,2079,2081,2083,2089,2090,2095,2100,2114,2153,2158,2166,2171,2174,2182,2190,2192,2194,2195,2198,2201,2202,2203,2208,2217,2227,2228,2237,2247,2256,2264,2266,2269,2270,2276,2278,2280,2284,2286,2290,2291,2296,2300,2304,2311,2312,2314,2316,2318,2319,2320,2321,2330,2331,2333,2346,2349,2352,2361)  
			and c.status = 1 and l.status = 1
			AND concat(c.name, ' (', l.location, ')') like '%".$val."%'
			limit 0,".$limit;
	*/
	$q = "SELECT c.customer_id, concat(c.name, ' (', l.location, ')') as cName from customer c
			INNER JOIN customer_location cl on cl.customer_id = c.customer_id
			INNER JOIN location l on l.location_id = cl.location_id
			where c.status = 1 and l.status = 1
			AND c.company_id = '".$CI->session->userdata('company')."'
			AND concat(c.name, ' (', l.location, ')') like '%".$CI->db->escape_str($val)."%'
			limit 0,".$limit;
	$res = $CI->db->query($q);	
    foreach($res->result_array() as $row)
    {
    	$data[] = array('id'=>$row['customer_id'], 'text'=>$row['cName']);
    }
    return $data;

}

/*function getBranchInfo($val)
{
	//print_r($val);
	$CI = & get_instance();
	$CI->db->select('b.branch_id, b.name');
    $CI->db->from('branch b');
	$CI->db->where('company_id',$CI->session->userdata('company'));
	//$CI->db->where('branch_id',$val);
	$res = $CI->db->get();
	//echo $CI->db->last_query();die;
	foreach($res->result_array() as $row)
    {
    	$data[] = array('id'=>$row['customer_id'], 'text'=>$row['name']);
    }
    return $data;

}*/


function getDecisionMakerInfo($val, $customer_id)
{
	$CI = & get_instance();
	$data = [];
	$limit = getDefaultSelect2Limit();
	$qry = 'SELECT name from customer where customer_id = "'.$customer_id.'" AND company_id="'.$_SESSION['company'].'"';
	$r = $CI->db->query($qry);
	$customer = '';
	if($r->num_rows() > 0)
	{
		$dat = $r->result_array();
		$customer = $dat[0]['name'];
	}
	$q = "SELECT c.contact_id, concat(c.first_name,' ',c.last_name,' - ',s.name,' (', c.mobile_no, ')' ) as cName from contact c
		INNER JOIN speciality s on s.speciality_id = c.speciality_id
		INNER JOIN customer_location_contact clc ON clc.contact_id = c.contact_id
		INNER JOIN customer cu ON cu.customer_id = clc.customer_id
		WHERE cu.status = 1 AND cu.company_id='".$_SESSION['company']."' AND c.status = 1 AND cu.name = '".$CI->db->escape_str($customer)."' AND 
		concat(c.first_name,' ',c.last_name,' - ',s.name,' (', c.mobile_no, ')' ) like '%".$val."%'
		order by cu.customer_id limit 0,".$limit;
	$res = $CI->db->query($q);
    foreach($res->result_array() as $row)
    {
		$data[] = array('id'=>$row['contact_id'], 'text'=>$row['cName']);
		//$default = array('id'=> " ",'text'=>"Select Decision Maker");
		//array_push($data,$default);
	}
	//print_r($data);
    return $data;
		
}



function getDecisionMakerInfo_api($val, $customer_id)
{
	$CI = & get_instance();
	$data = [];
	$qry = 'SELECT name from customer where customer_id = "'.$customer_id.'" AND company_id="'.$_SESSION['company'].'"';
	$r = $CI->db->query($qry);
	$customer = '';
	if($r->num_rows() > 0)
	{
		$dat = $r->result_array();
		$customer = $dat[0]['name'];
	}
	$q = "SELECT c.contact_id, concat(c.first_name,' ',c.last_name,' - ',s.name,' (', c.mobile_no, ')' ) as cName from contact c
		INNER JOIN speciality s on s.speciality_id = c.speciality_id
		INNER JOIN customer_location_contact clc ON clc.contact_id = c.contact_id
		INNER JOIN customer cu ON cu.customer_id = clc.customer_id
		WHERE cu.status = 1 AND cu.company_id='".$_SESSION['company']."' AND c.status = 1 AND cu.name = '".$CI->db->escape_str($customer)."' AND 
		concat(c.first_name,' ',c.last_name,' - ',s.name,' (', c.mobile_no, ')' ) like '%".$val."%'
		order by cu.customer_id";
	$res = $CI->db->query($q);
    foreach($res->result_array() as $row)
    {
    	$data[] = array('id'=>$row['contact_id'], 'text'=>$row['cName']);
    }
    return $data;
		
}


function getTerritoryLevel($level)
{
	$CI = & get_instance();
	if($level == '')
		return 0;
	$q = 'SELECT territory_level_id from territory_level where name = "'.$level.'"';
	$r = $CI->db->query($q);
	if($r->num_rows() > 0)
	{
		$d = $r->result_array();
		return $d[0]['territory_level_id'];
	}
	else return 0;
}

function getLocationAndParent($val, $level)
{
	$data = [];
	$CI = & get_instance();
	$limit = getDefaultSelect2Limit();
	$level_id = getTerritoryLevel($level);
	//$level_id = $level;
	if($level_id > 2)
	{
		$q = "SELECT t.location_id, t.location from (
			SELECT l.location_id, concat(l.location, ' (', l1.location, ')') as location from location l
			LEFT JOIN location l1 ON l1.location_id = l.parent_id
			WHERE l.territory_level_id = '".$level_id."' and l.status = 1)
			t where t.location like '%".$val."%' order by t.location_id limit 0,".$limit;
	}
	if($level_id == 2)
	{
		$q = "SELECT l.location_id, l.location as location from location l
			WHERE l.territory_level_id = '".$level_id."' and l.status = 1
			and l.location like '%".$val."%' order by l.location_id limit 0,".$limit;
	}
	$res = $CI->db->query($q);
    foreach($res->result_array() as $row)
    {
    	$data[] = array('id'=>$row['location_id'], 'text'=>$row['location']);
    }
    return $data;
}

function getParentLocation($location_id)
{
	$CI = & get_instance();
	if($location_id != '')
	{
		$q = "SELECT l1.location_id, CASE WHEN (l1.territory_level_id = 2) then l1.location else
				concat(l1.location, ' (', l2.location, ')') end as location from location l
				LEFT JOIN location l1 on l1.location_id = l.parent_id
				LEFT JOIN location l2 on l2.location_id = l1.parent_id
				WHERE l.location_id = '".$location_id."'";
		$res = $CI->db->query($q);
		if($res->num_rows() > 0)
		{
			$data = $res->result_array();
			return $data[0];
		}
		else
			return array('location_id' => '', 'location' => '-Select Parent-');		
	}
	else
		return array('location_id' => '', 'location' => '-Select Parent-');
}

function getCampaignInfo($val)
{
	$data = [];
	$CI = & get_instance();
	$limit = getDefaultSelect2Limit();
	$loc = $CI->session->userdata('locationString');
	if($loc == '')
		$loc = 0;
	$q = "SELECT t.campaign_id, t.cName, t.type from (
		SELECT c.campaign_id, concat(c.name, ' (', c.campaign_date, ')') as cName, type from campaign c
		where c.status = 1 and c.company_id='".$_SESSION['company']."') 
		t where t.cName like '%".$val."%'
		order by t.campaign_id desc limit 0,".$limit;
	$res = $CI->db->query($q);
    foreach($res->result_array() as $row)
    {
    	$t = ($row['type'] == 1)?'Mass Mailing':'Offline';
    	$data[] = array('id'=>$row['campaign_id'], 'text'=>$t.': '.$row['cName']);
    }
    return $data;
}




function getCustomerLocation($customer_id)
{
	$loc = 0;
	$CI = & get_instance();
	$q = "SELECT location_id from customer_location
			WHERE customer_id = '".$customer_id."' ";
	$res = $CI->db->query($q);
	if($res->num_rows() > 0)
	{
		$data = $res->result_array();
		$loc = $data[0]['location_id'];
	}
	return $loc;
}

function getAllParents($location_id)
{
	$data = '';
	if($location_id == '')
		$location_id = 0;
	$CI = & get_instance();
	$q = "SELECT l.location_id as l7, l1.location_id as l6, l2.location_id as l5, l3.location_id as l4, 
			l4.location_id as l3, l5.location_id as l2, l5.parent_id as l1 from location l
			left join location l1 on l1.location_id = l.parent_id
			left join location l2 on l2.location_id = l1.parent_id
			left join location l3 on l3.location_id = l2.parent_id
			left join location l4 on l4.location_id = l3.parent_id
			left join location l5 on l5.location_id = l4.parent_id
			where l.location_id = '".$location_id."'";
	$res = $CI->db->query($q);
	if($res->num_rows() > 0)
	{
		$r = $res->result_array();
		$row = $r[0];
		$l1 = $row['l1'];
		$l2 = $row['l2'];
		$l3 = $row['l3'];
		$l4 = $row['l4'];
		$l5 = $row['l5'];
		$l6 = $row['l6'];
		$l7 = $row['l7'];
		$data = $l1.', '.$l2.', '.$l3.', '.$l4.', '.$l5.', '.$l6.', '.$l7;
	}
	return $data;
}

function getLocationName($location_id)
{
	$ret = '';
	if($location_id != '')
	{
		$CI = & get_instance();
		$q = 'SELECT concat(l.location, " (", l1.location, ")") as location from location l
				INNER JOIN location l1 ON l1.location_id = l.parent_id
				where l.location_id = "'.$location_id.'"';
		$r = $CI->db->query($q);
		if($r->num_rows() > 0)
		{
			$data = $r->result_array();
			$ret = $data[0]['location'];
		}		
	}
	return $ret;
}

function getCityFromRegion($val, $region_id)
{
	$ret = [];
	$limit = getDefaultSelect2Limit();
	if($region_id != '')
	{
		$CI = & get_instance();
		$q = 'SELECT l4.location_id, concat(l4.location, " (", l3.location, ")") as location from location l1
				LEFT JOIN location l2 ON l2.parent_id = l1.location_id
				LEFT JOIN location l3 ON l3.parent_id = l2.location_id
				LEFT JOIN location l4 ON l4.parent_id = l3.location_id
				where l1.status = 1 AND l2.status = 1 AND l3.status = 1 AND l4.status = 1 AND l1.location_id = "'.$region_id.'"
				AND concat(l4.location, " (", l3.location, ")") like "%'.$val.'%"
				order by l4.location_id desc limit 0,'.$limit;
		$res = $CI->db->query($q);
		if($res->num_rows() > 0)
		{
			foreach($res->result_array() as $row)
			{
				$ret[] = array('id' => $row['location_id'], 'text' => $row['location']);
			}
		}
	}
	return $ret;
}

function getRegionFromCity($l)
{
	if($l != '')
	{
		$CI = & get_instance();
		$q = "SELECT l2.parent_id as location from location l 
			LEFT JOIN location l1 ON l1.location_id = l.parent_id
			LEFT JOIN location l2 ON l2.location_id = l1.parent_id
			WHERE l.location_id = '".$l."'";
		$res = $CI->db->query($q);
		if($res->num_rows() > 0)
		{
			$data = $res->result_array();
			return $data[0]['location'];
		}
	}
	else return 0;
}

function getRegionNameFromCity($l)
{
	if($l != '')
	{
		$CI = & get_instance();
		$q = "SELECT l3.location as location from location l 
			LEFT JOIN location l1 ON l1.location_id = l.parent_id
			LEFT JOIN location l2 ON l2.location_id = l1.parent_id
			LEFT JOIN location l3 ON l3.location_id = l2.parent_id
			WHERE l.location_id = '".$l."'";
		$res = $CI->db->query($q);
		if($res->num_rows() > 0)
		{
			$data = $res->result_array();
			return $data[0]['location'];
		}
	}
	else return 0;
}

function getLeadStatusArray()
{
	return array(1 => 'Waiting for Approval',
				2 => 'Lead Approved',
				3 => 'Opportunity Created',
				4 => 'All Opportunities Dropped',
				5 => 'All Opportunities Lost or Dropped',
				6 => 'Partial Quote',
				7 => 'Full Quote',
				8 => 'Partial Contract Note - Partial Quote',
				9 => 'Partial Contract Note - Full Quote',
				10 => 'Full Contract Note - Full Quote',
				19 => 'Lead Owner Role Changed');	
}

function getLeadClosedStatusArray()
{
	return array(20 => 'Lead Rejected',
				21 => 'Lead Dropped',
				22 => 'Lead Closed');
}


function getLeadStatus($status)
{
	$ret = '';
	switch($status)
	{
		case 1:
			$ret = 'Waiting for Approval';
			break;
		case 2:
			$ret = 'Lead Approved';
			break;
		case 3:
			$ret = 'Opportunity Created';
			break;
		case 4:
			$ret = 'All Opportunities Dropped';
			break;
		case 5:
			$ret = 'All Opportunities Lost or Dropped';
			break;		
		case 6:
			$ret = 'Partial Quote';
			break;
		case 7:
			$ret = 'Full Quote';
			break;
		case 8:
			$ret = 'Partial Contract Note - Partial Quote';
			break;
		case 9:
			$ret = 'Partial Contract Note - Full Quote';
			break;															
		case 10:
			$ret = 'Full Contract Note - Full Quote';
			break;	
		case 19:
			$ret = 'Lead Owner Role Changed';
			break;				
		case 20:
			$ret = 'Lead Rejected';
			break;
		case 21:
			$ret = 'Lead Dropped';	
			break;		
		case 22:
			$ret = 'Lead Closed';
			break;		
	}
	return $ret;
}

function getOpportunityStatusArray()
{
	return array(1 => 'Requirement identified',
				2 => 'Budgetary offer submitted',
				3 => 'Demo required',
				4 => 'Technically cleared',
				5 => 'Price approval from customer',
				6 => 'Closed Won',
				7 => 'Closed Lost',
				8 => 'Dropped');	
}


function getRoleLevelID($role_id)
{
	if($role_id != '')
	{
		$CI = & get_instance();
		$q = 'SELECT role_level_id from role where role_id = "'.$role_id.'"';
		$r = $CI->db->query($q);
		if($r->num_rows() > 0)
		{
			$data = $r->result_array();
			return $data[0]['role_level_id'];
		}
		return 0;
	}
	return 0;
}

function getReporteeRoles($role_id, $check = '')
{
	$ret = '';
	if($role_id != '')
	{
		$CI = & get_instance();
		$role_level_id = getRoleLevelID($role_id);
		$q = 'SELECT group_concat(role_id) role from role where role_level_id > "'.$role_level_id.'" AND role_level_id != 8 ';
		if($check == '')
		{
			$q .= ' AND role_id != 5';
		}
		$r = $CI->db->query($q);
		if($r->num_rows() > 0)
		{
			$data = $r->result_array();
			return $data[0]['role'];
		}
	}
	return $ret;
}

function DateFormatAM($timestamp)
{
	if($timestamp != '')
	{
		$time = strtotime($timestamp);
		return date('d M Y h:i A',$time);
	}
	else return '';
}

function DateFormat($timestamp)
{
	if($timestamp != '')
	{
		$time = strtotime($timestamp);
		return date('dMY',$time);
	}
	else return '';
}

function getCampaignName($campaign_id)
{
	if($campaign_id != '')
	{
		$CI = & get_instance();
		$q = "SELECT c.campaign_id, concat(c.name, ' (', c.campaign_date, ')') as cName, type from campaign c
			where c.campaign_id = '".$campaign_id."'";
		//Removed status = 1, to include de-activation in campaign management
		$r = $CI->db->query($q);
		if($r->num_rows() > 0)
		{
			$data = $r->result_array();
			$retType = ($data[0]['type'] == 1)?'Mass Mailing: ':'Offline'; 
			
			$ret = $retType.$data[0]['cName'];
			return $ret;
		}
	}
	return '';
}

function leadStatusToottip($lead_id, $status = 0)
{
	$ret = '';
	if($lead_id != '')
	{
		$CI = & get_instance();
		if($status == 6) $status = '6,7';
		if($status == 8) $status = '8,9,10';
		$q = 'SELECT case when (u.role_id != 5) then concat(u.first_name, " ", u.last_name, " (", u.employee_id, ")") 
			else concat(d.distributor_name, " (", u.employee_id, ")") end created_by, 
			l.created_time from lead_status_history l 
			INNER JOIN user u ON u.user_id = l.created_by
			LEFT JOIN distributor_details d ON d.user_id = u.user_id
			where l.lead_id = "'.$lead_id.'" AND l.status IN ('.$status.')
			order by lead_status_history_id limit 0,1;';
		$r = $CI->db->query($q);
		if($r->num_rows() > 0)
		{
			$data = $r->result_array();
			$created_by = $data[0]['created_by'];
			$created_time = date('d M Y h:i A',strtotime($data[0]['created_time']));
			$ret = 'By - '.$created_by.' On - '.$created_time;
		}

	}
	return $ret;
}

function getLeadOldStatus($lead_id)
{
	$ret = 1;
	if($lead_id != '')
	{
		$CI = & get_instance();
		$q = 'SELECT status from lead_status_history WHERE lead_id = "'.$lead_id.'"
				order by lead_status_history_id desc limit 1,1';
		$r = $CI->db->query($q);
		if($r->num_rows() > 0)
		{
			$data = $r->result_array();
			$ret = $data[0]['status'];
		}		
	}
	return $ret;
}


function leadStatusBar($status, $lead_id)
{

	$leadS = 'success';
	$appS = 'grey';
	$opS = 'grey';
	$q1S = 'grey';
	$q2S = 'grey';
	$c1S = 'grey';
	$c2S = 'grey';
	$statusBar = '';
	$status1 = 0;
	if($status == 19) 
	{
		$status1 = 1;
		$status = getLeadOldStatus($lead_id);
	}
	if($status > 1) $appS = 'success';
	if($status > 2) $opS = 'success';
	if($status > 5) $q1S = 'success';
	if($status == 7) $q2S = 'success';		
	if($status == 8) $c1S = 'success';		
	if($status > 8)
	{
		$q2S = 'success';
		$c1S = 'success';		
	}
	if($status == 10) $c2S = 'success';	

	$statusBar .= '	<div class="progress progress-striped active">
						<div class="progress-bar progress-bar-'.$leadS.'" style="width: 19%"  data-toggle="tooltip" title="'.leadStatusToottip($lead_id, 1).'"><b>Lead</b></div>
						<div class="progress-bar progress-bar-division" style="width: 1%"></div>
						<div class="progress-bar progress-bar-'.$appS.'" style="width: 19%" data-toggle="tooltip" title="'.leadStatusToottip($lead_id, 2).'"><b>Approved</b></div>
						<div class="progress-bar progress-bar-division" style="width: 1%"></div>
						<div class="progress-bar progress-bar-'.$opS.'" style="width: 19%" data-toggle="tooltip" title="'.leadStatusToottip($lead_id, 3).'"><b>Opportunity</b></div>
						<div class="progress-bar progress-bar-division" style="width: 1%"></div>
						<div class="progress-bar progress-bar-'.$q1S.'" style="width: 12%" data-toggle="tooltip" title="'.leadStatusToottip($lead_id, 6).'"><b>Quote</b></div>
						<div class="progress-bar progress-bar-'.$q2S.'" style="width: 7%"><b></b></div>
						<div class="progress-bar progress-bar-division" style="width: 1%"></div>
						<div class="progress-bar progress-bar-'.$c1S.'" style="width: 14%" data-toggle="tooltip" title="'.leadStatusToottip($lead_id, 8).'"><b>C Note</b></div>
						<div class="progress-bar progress-bar-'.$c2S.'" style="width: 6%"><b></b></div>
					</div>';	
	return $statusBar;
}

function generatePassword($emp_id, $name, $mobile)
{
	$emp = substr($emp_id, 0, 2);
	$nam = substr($name, 0, 2);
	$mob = substr($mobile, 0, 2);
	$pass = $emp.$nam.$mob;
	//echo $pass.'<br>';
	return $pass;
}

function getLeadStatusID($lead_id)
{
	$CI = & get_instance();
	$q = 'SELECT status from lead where lead_id = "'.$lead_id.'"';
	$r = $CI->db->query($q);
	if($r->num_rows() > 0)
	{
		$data = $r->result_array();
		return $data[0]['status'];
	}
}

function checkLead($lead_id)
{
	$CI = & get_instance();
	$q = 'SELECT lead_id from lead where status < 20 AND lead_id = "'.$lead_id.'"';
	$r = $CI->db->query($q);
	return $r->num_rows();
}

function checkClosedLead($lead_id)
{
	$CI = & get_instance();
	$q = 'SELECT lead_id from lead where status > 19 AND lead_id = "'.$lead_id.'"';
	$r = $CI->db->query($q);
	return $r->num_rows();
}

function checkOpportunity($lead_id)
{
	$CI = & get_instance();
	$q = 'SELECT lead_id from lead where status > 1 AND status < 20 AND lead_id = "'.$lead_id.'"';
	$r = $CI->db->query($q);
	return $r->num_rows();
}

function checkQuote($lead_id)
{
	$CI = & get_instance();
	$q = 'SELECT lead_id from lead where status > 2 AND status < 20 AND status != 4 AND lead_id = "'.$lead_id.'"';
	$r = $CI->db->query($q);
	return $r->num_rows();
}

function checkCNote($lead_id)
{
	$CI = & get_instance();
	$q = 'SELECT lead_id from lead where status > 5 AND status < 20 AND lead_id = "'.$lead_id.'"';
	$r = $CI->db->query($q);
	return $r->num_rows();
}

function getProbabilityBar($opportunity_id)
{
	$probability = getProbabilityForOpportunity($opportunity_id);
	//$probability = 70;
	$bar = ($probability < 30)?'danger':(($probability < 65)?'warning':'success');
	//$bar = 'danger';
	$ret = '<div class="progress progress-striped active">
                <div class="progress-bar progress-bar-'.$bar.'"  data-toggle="tooltip" title="'.$probability.'%" style=" width: '.$probability.'%">'.$probability.'%</div>
			</div>';
	return $ret;

}

function getProbabilityForOpportunity($opportunity_id)
{
	if($opportunity_id == '')
	{
		return 0;
	}
	else
	{
		$CI = & get_instance();
		$q = 'SELECT status from opportunity where opportunity_id = "'.$opportunity_id.'"';
		$r = $CI->db->query($q);
		if($r->num_rows() > 0)
		{
			$dat = $r->result_array();
			$status = $dat[0]['status'];
			// if($status == 6) return 100;

			// added on 23-06-2021 for distributor role
			if($status == 6 || $status == 10) return 100;
			// added on 23-06-2021 for distributor role end
			else if($status == 7 || $status == 8) return 0;
			else
			{
				$q1 = 'SELECT weight from opportunity_weightage';
				$r1 = $CI->db->query($q1);
				$i = 0;
				$weight = [];
				foreach($r1->result_array() as $row)
				{
					$weight[$i] = $row['weight'];
					$i++;
				}
				// Phase2 update: Prasad/Mahesh 04-09-2017 changed product category weight to product group weight
				$qry = 'SELECT ct.weight as customer, cc.weight as customer_category, pg.weight as product_category,
						r1.weight as relationship_opportunity, r2.weight as relationship_lead, s.weight as funding,
						os.weight as opportunity_status, sr.weight as site_readiness,
						round((('.$weight[0].' * ct.weight) + ('.$weight[1].' * cc.weight) + ('.$weight[2].' * pg.weight) + 
								('.$weight[3].' * r1.weight) + ('.$weight[4].' * r2.weight) + ('.$weight[5].' * s.weight) +
								('.$weight[6].' * os.weight) + ('.$weight[7].' * sr.weight))/100, 2) `probability` from opportunity o 
						INNER JOIN lead l ON l.lead_id = o.lead_id
						INNER JOIN opportunity_product op ON op.opportunity_id = o.opportunity_id
						INNER JOIN product p ON p.product_id = op.product_id
						INNER JOIN product_group pg ON pg.group_id = p.group_id
						INNER JOIN product_category pc ON pc.category_id = pg.category_id
						INNER JOIN customer c ON c.customer_id = l.customer_id
						INNER JOIN customer_type ct ON ct.type = c.type
						INNER JOIN customer_category_details cc ON (cc.category_id = c.category_id AND cc.category_sub_id = c.category_sub_id)
						INNER JOIN relationship r1 ON r1.relationship_id = o.relationship_id
						INNER JOIN relationship r2 ON r2.relationship_id = l.relationship_id
						INNER JOIN source_of_funds s ON s.fund_source_id = o.fund_source_id
						INNER JOIN site_readiness sr ON sr.site_readiness_id = l.site_readiness_id
						INNER JOIN opportunity_status os ON os.status = o.status
						WHERE o.opportunity_id = "'.$opportunity_id.'"';
				$res = $CI->db->query($qry);
				if($res->num_rows() > 0)
				{
					$data = $res->result_array();
					return round($data[0]['probability']);
				}
				else return 0;
			}

		}
		else return 0;
	}

}

function getOpportunityCategory($status, $orderDate,$year = '',$month = '')
{
	//echo $orderDate;die();
	if($status == '' || $orderDate == '')
		return '';
	if($status > 5)
		return '';
	else
	{
		if($month=='')
		$month = date('m');
		if($year=='')
		$year = date('Y');		
		$day = getOpportunityCategorizationDate();
		$hotDay = $year."-".$month."-".$day;
		$warmDate = date('Y-m-d',strtotime( $hotDay . " +1 month " ));
		$orderConclusionDay = strtotime($orderDate);
		$hotDay = strtotime($hotDay);
		$warmDay = strtotime($warmDate);
		if($orderConclusionDay <= $hotDay)
			return 'Hot';
		if($orderConclusionDay <= $warmDay)
			return 'Warm';
		else return 'Cold';
	}
}

function getLeadOpportunities($lead_id)
{
	$ret = array();
	if($lead_id != '')
	{
		$CI = & get_instance();
		$q = 'SELECT opportunity_id from opportunity where status < 6 AND lead_id = "'.$lead_id.'"';
		$r = $CI->db->query($q);
		if($r->num_rows() > 0)
		{
			$ret = $r->result_array();
		}
	}
	return $ret;
}

function getCompanyID($user_id)
{
	$ret = 0;
	if($user_id != '')
	{
		$CI = & get_instance();
		$q = 'SELECT company_id form user WHERE user_id = "'.$user_id.'"';
		$r = $CI->db->query($q);
		if($r->num_rows() > 0)
		{
			$data = $r->result_array();
			$ret = $data[0]['company_id'];
		}
	}
	return $ret;
}

function getCampaignSpecialities($campaign_id)
{
	$ret = '';
	if($campaign_id != '')
	{
		$CI = & get_instance();
		$q = 'SELECT group_concat(s.name) speciality from campaign_speciality cs
			INNER JOIN speciality s ON s.speciality_id = cs.speciality_id
			where cs.campaign_id ="'.$campaign_id.'"';
		$r = $CI->db->query($q);
		if($r->num_rows() > 0)
		{
			$data = $r->result_array();
			$speciality = $data[0]['speciality'];
			$ret = str_replace(',', ', ', $speciality);
		}
	}
	return $ret;
}

function getCampaignLocations($campaign_id)
{
	$ret = '';
	if($campaign_id != '')
	{
		$CI = & get_instance();
		$q = 'SELECT group_concat(l.location) location from campaign_location cl
			INNER JOIN location l ON l.location_id = cl.location_id
			where cl.campaign_id ="'.$campaign_id.'"';
		$r = $CI->db->query($q);
		if($r->num_rows() > 0)
		{
			$data = $r->result_array();
			$location = $data[0]['location'];
			$ret = str_replace(',', ', ', $location);
		}
	}
	return $ret;
}

function getRBHForRegion($region_id)
{
	$ret = 'No RBH for this Region';
	if($region_id != '')
	{
		$CI = & get_instance();
		$q = 'SELECT group_concat(concat(u.first_name, " ", u.last_name, " - ", u.employee_id)) as user from user u
				INNER JOIN user_location ul ON ul.user_id = u.user_id
                WHERE u.status = 1 AND ul.status = 1 AND u.role_id = 7 AND ul.location_id = "'.$region_id.'"';	
        $r = $CI->db->query($q);
        if($r->num_rows() > 0)
        {
        	$data = $r->result_array();
        	$users = $data[0]['user'];
        	$ret = str_replace(",", ", ", $users);
        }	
	}
	return $ret;
}

function getQuoteStatus($status)
{
	$ret = '';
	switch($status)
	{
		case 1:
			$ret = 'Waiting for Approval';
			break;
		case 2:
			$ret = 'Quote Approved';
			break;
		case 3:
			$ret = 'Converted to Contract Note';
			break;
		case 4:
			$ret = 'Opportunity Lost';
			break;	
		case 5:
			$ret = 'Quote Dropped';
			break;
		case 6:
			$ret = 'Quote Approved';
			break;
		case 10:
			$ret = 'Quote Rejected';
			break;
	}
	return $ret;
}

function getContactUserName($user_id)
{
	$ret = '';
	if($user_id != '')
	{
		$CI = & get_instance();
		$q = 'SELECT concat(c.first_name, " ", c.last_name, " (", s.name ,")") as name from contact c
				INNER JOIN speciality s ON s.speciality_id = c.speciality_id 
				WHERE c.contact_id = "'.$user_id.'"';
		$r = $CI->db->query($q);
		if($r->num_rows() > 0)
		{
			$data = $r->result_array();
			$ret = $data[0]['name'];
		}
	}
	return $ret;
}

function getLocationStateTag($location_id)
{
	$ret = '';
	if($location_id != '')
	{
		$CI = & get_instance();
		$q = "SELECT l2.tag from location l 
			INNER JOIN location l1 ON l.parent_id = l1.location_id
			INNER JOIN location l2 ON l1.parent_id = l2.location_id
			WHERE l.location_id = '".$location_id."'";
		$r = $CI->db->query($q);
        if($r->num_rows() > 0)
        {
        	$data = $r->result_array();
        	$ret = $data[0]['tag'];
        }	
	}
	return $ret;
}

function getQuoteRevisionID($quote_id)
{
	$ret = 1;
	if($quote_id != '')
	{
		$CI = & get_instance();
		$q = 'SELECT quote_id, status from quote_revision
				WHERE quote_id = "'.$quote_id.'"';
		$r = $CI->db->query($q);
		$num = $r->num_rows();
		$i = 1;
		foreach($r->result_array() as $row)
		{
			if($row['status'] == 1)
				break;
			$i++;
		}
		$ret = $i;
	}
	return $ret;
}

function getQuoteReferenceID($lead_id, $quote_id)
{
	if($lead_id != '')
	{
		$customer_id = getLeadCustomerID($lead_id);
		$location_id = getCustomerLocation($customer_id);
		$tag = getLocationStateTag($location_id);
		$rev = getQuoteRevisionID($quote_id);
		$quote_number = get_current_quote_number($quote_id);
		$year = date('y');
		$month = date('m');
		if($month < 4)
			$year = $year - 1;
		return $tag.'-'.$year.'-'.$quote_number.'-Rev-'.$rev;
	}
}

function getQuoteReferenceIDNew($lead_id, $quote_id, $quote_revision_id)
{
	if($lead_id != '')
	{
		$customer_id = getLeadCustomerID($lead_id);
		$location_id = getCustomerLocation($customer_id);
		$tag = getLocationStateTag($location_id);
		// $rev = getQuoteRevisionID($quote_id);
		$rev = getQuoteRevisionNumber($quote_id,$quote_revision_id);
		$quote_number = get_current_quote_number($quote_id);
		$year = date('y');
		$month = date('m');
		if($month < 4)
			$year = $year - 1;
		return $tag.'-'.$year.'-'.$quote_number.'-Rev-'.$rev;
	}
}

function getQuoteReferenceID1($lead_id, $quote_id)
{
	if($lead_id != '')
	{
		$customer_id = getLeadCustomerID($lead_id);
		$location_id = getCustomerLocation($customer_id);
		$tag = getLocationStateTag($location_id);
		$quote_number = get_current_quote_number($quote_id);
		$year = date('y');
		$month = date('m');
		if($month < 4)
			$year = $year - 1;
		return $tag.'-'.$year.'-'.$quote_number;
	}
}


function getQuoteStatusByDiscount($discount)
{
	$quoteApp[7] = array('min' => 0, 'max' => 30);
	$quoteApp[8] = array('min' => 30, 'max' => 35);
	$quoteApp[9] = array('min' => 35, 'max' => 100);
	$status = 1;
	if($discount == "") $discount = 0;
	if($discount != 0)
	{
		$CI = & get_instance();
		$q = 'SELECT * from quote_approval';
		$r = $CI->db->query($q);
		foreach($r->result_array() as $row)
		{
			$quoteApp[$row['role_id']] = array('min' => $row['min'], 'max' => $row['max']);
		}

		$role_id = $CI->session->userdata('role_id');
		if($role_id == 9 || $role_id == 10 || $role_id == 11)
		{
			$status = 2;
		}
		else if($role_id == 8)
		{
			if($discount < $quoteApp[8]['max']) $status = 2;
		}
		else if($role_id == 7)
		{
			if($discount < $quoteApp[7]['max']) $status = 2;
		}
	}
	else $status = 2;
	return $status;
}

function addQuoteStatusHistory($quote_id, $status)
{
	if($quote_id != '')
	{
		$CI = & get_instance();
		$CI->load->model("Common_model");		
		$statusData = array('quote_id' => $quote_id, 
							'status' => $status,
							'created_by' => $CI->session->userdata('user_id'),
							'created_time' => date('Y-m-d H:i:s'));
		$CI->Common_model->insert_data('quote_status_history', $statusData);
	}
}

function addLeadUserHistory($lead_id = '', $user1 = '', $user2 = '')
{
	if($lead_id != '')
	{
		$CI = & get_instance();
		$CI->load->model("Common_model");		

		if($user2 != '')
		{
			$q = 'SELECT lead_user_history_id from lead_user_history where lead_id = "'.$lead_id.'" AND user_id = "'.$user2.'"';
			$r = $CI->db->query($q);
			if($r->num_rows() > 0)
			{
				$data = $r->row_array();
				//update lead_user_history
				$lead_history_where = array('lead_user_history_id'=>$data['lead_user_history_id']);
				$lead_history_data = array('end_time'=>date('Y-m-d H:i:s'),'changed_by'=>$CI->session->userdata('user_id'));
				$CI->Common_model->update_data('lead_user_history',$lead_history_data,$lead_history_where);
			}
		}

		// Insertig lead user history
		$lead_user_history_data = array('lead_id'=>$lead_id,'user_id'=>$user1,'start_time'=>date('Y-m-d H:i:s'));
		$CI->Common_model->insert_data('lead_user_history',$lead_user_history_data);
	}
}


function addLeadStatusHistory($lead_id, $status)
{
	if($lead_id != '')
	{
		$CI = & get_instance();
		$CI->load->model("Common_model");		
		$statusData = array('lead_id' => $lead_id, 
							'status' => $status,
							'created_by' => $CI->session->userdata('user_id'),
							'created_time' => date('Y-m-d H:i:s'));
		$CI->Common_model->insert_data('lead_status_history', $statusData);
	}
}

function addOpportunityStatusHistory($opportunity_id, $status)
{
	if($opportunity_id != '')
	{
		$CI = & get_instance();
		$CI->load->model("Common_model");		
		$statusData = array('opportunity_id' => $opportunity_id, 
							'status' => $status,
							'created_by' => $CI->session->userdata('user_id'),
							'created_time' => date('Y-m-d H:i:s'));
		$CI->Common_model->insert_data('opportunity_status_history', $statusData);
	}
}


function addOpportunityStatusByQuote($opportunity_id, $quote_status)
{
	if($opportunity_id != '')
	{
		$CI = & get_instance();
		$CI->load->model("Common_model");		
		$current_status = getCurrentOpportunityStatus($opportunity_id);
		if($quote_status == 2)
		{
			if($current_status != 5)
			{
				//Update Opportunity status to 5 when quote is approved
				$updateData = array('status' => 5,
								'modified_by' => $CI->session->userdata('user_id'),
								'modified_time' => date('Y-m-d H:i:s'));
				$CI->Common_model->update_data('opportunity', $updateData, array('opportunity_id'=>$opportunity_id));
				//Opportunity status history
				addOpportunityStatusHistory($opportunity_id, 5);
			}
		}
		if($quote_status == 3)
		{
			if($current_status != 6)
			{
				//Update Opportunity status to 6 when quote is won
				$updateData = array('status' => 6,
								'modified_by' => $CI->session->userdata('user_id'),
								'modified_time' => date('Y-m-d H:i:s'));
				$CI->Common_model->update_data('opportunity', $updateData, array('opportunity_id'=>$opportunity_id));
				//Opportunity status history
				addOpportunityStatusHistory($opportunity_id, 6);			
			}
		}
	}
}

function leadStatusUpdate($lead_id, $status = 1)
{
	if($lead_id != '')
	{
		$CI = & get_instance();
		$CI->load->model("Common_model");		
		$current_status = getCurrentLeadStatus($lead_id);
		if($current_status > 1)
		{
			$data = array_merge(getOpportunitiesCount($lead_id), getQuoteCount($lead_id));
			//print_r($data);die();
			$total = ($data['total'] != '')?$data['total']:0;
			$open = ($data['open'] != '')?$data['open']:0;
			$dropped = ($data['dropped'] != '')?$data['dropped']:0;
			$lost = ($data['lost'] != '')?$data['lost']:0;
			$won = ($data['won'] != '')?$data['won']:0;
			$quote = $data['quote'];
			$cNote = $data['cNote'];
			$finalTotal = $total - ($dropped + $lost);
			if($total == 0) $status = 2;
			else if($total == $dropped) $status = 4;
			else if($total == ($lost + $dropped)) $status = 5;
			else if($finalTotal == $open && $quote == 0 && $cNote == 0) $status = 3;
			else if($finalTotal == $quote && $cNote == 0) $status = 7;
			else if($finalTotal == ($cNote + $quote) && $quote != 0) $status = 9;
			else if($finalTotal == $cNote) $status = 10;
			else if($cNote == 0) $status = 6;
			else $status = 8;
			//echo '<br>'.$status;
		}
		
		if($current_status != $status)
		{
				$updateData = array('status' => $status, 
								'modified_by' => $CI->session->userdata('user_id'),
								'modified_time' => date('Y-m-d H:i:s'));
				$CI->Common_model->update_data('lead', $updateData, array('lead_id'=>$lead_id));
				//Opportunity status history
				addLeadStatusHistory($lead_id, $status);
		}
		
	}
}

function getOpportunitiesCount($lead_id)
{
	$ret = array('total' => 0, 'open' => 0, 'lost' => 0, 'won' => 0);
	if($lead_id != '')
	{
		$CI = & get_instance();
		$q = 'SELECT sum(1) `total`, sum(case when(status IN (1,2,3,4,5)) then 1 else 0 end) `open`,
				sum(case when(status IN (7)) then 1 else 0 end) `lost`,
				sum(case when(status IN (6)) then 1 else 0 end) `won`,
				sum(case when(status IN (8)) then 1 else 0 end) `dropped` 
				from opportunity where lead_id = "'.$lead_id.'"';
		$r = $CI->db->query($q);
		if($r->num_rows() > 0)
		{
			$data = $r->result_array();
			$ret = $data[0];
		}
	}
	return $ret;
}

function getQuoteCount($lead_id)
{
	$quoteCount = 0;
	$cNoteCount = 0;
	if($lead_id != '')
	{
		$CI = & get_instance();
		$q = 'SELECT opportunity_id from opportunity WHERE lead_id = "'.$lead_id.'"';
		$r = $CI->db->query($q);
		foreach($r->result_array() as $row)
		{
			$opportunity_id = $row['opportunity_id'];
			$qry = 'SELECT sum(case when (status = 2 || status = 6) then 1 else 0 end) `quote`,
					sum(case when status = 3 then 1 else 0 end) `cNote`  from quote q
					INNER join quote_details qo ON qo.quote_id = q.quote_id
					WHERE q.status > 1 AND opportunity_id = "'.$opportunity_id.'"';
			$res = $CI->db->query($qry);
			if($res->num_rows() > 0)
			{
				$data = $res->result_array();
				$qCount = $data[0]['quote'];
				$cCount = $data[0]['cNote'];
				if($cCount > 0) $cNoteCount = $cNoteCount + 1; 
				else if($qCount > 0) $quoteCount = $quoteCount + 1;
			}
		}
	}
	return array('quote' => $quoteCount, 'cNote' => $cNoteCount);
}

function getCurrentLeadStatus($lead_id)
{
	$ret = 1;
	if($lead_id != '')
	{
		$CI = & get_instance();
		$q = "SELECT status from lead where lead_id = '".$lead_id."'";
		$r = $CI->db->query($q);
		if($r->num_rows() > 0)
		{
			$data = $r->result_array();
			$ret = $data[0]['status'];
		}
	}
	return $ret;
}

function getCurrentQuoteStatus($quote_id)
{
	$ret = '';
	if($quote_id != '')
	{
		$CI = & get_instance();
		$q = "SELECT status from quote where quote_id = '".$quote_id."'";
		$r = $CI->db->query($q);
		if($r->num_rows() > 0)
		{
			$data = $r->result_array();
			$ret = $data[0]['status'];
		}
	}
	return $ret;
}

function getCurrentOpportunityStatus($opportunity_id)
{
	$ret = 1;
	if($opportunity_id != '')
	{
		$CI = & get_instance();
		$q = "SELECT status from opportunity where opportunity_id = '".$opportunity_id."'";
		$r = $CI->db->query($q);
		if($r->num_rows() > 0)
		{
			$data = $r->result_array();
			$ret = $data[0]['status'];
		}
	}
	return $ret;
}

function getLeadFromQuote($quote_id)
{
	$ret = '';
	if($quote_id != '')
	{
		$CI = & get_instance();
		$q = "SELECT lead_id from quote q
				INNER JOIN quote_details qd ON qd.quote_id = q.quote_id
				INNER JOIN opportunity o ON o.opportunity_id = qd.opportunity_id
				WHERE q.quote_id = '".$quote_id."'
				group by o.lead_id";
		$r = $CI->db->query($q);
		if($r->num_rows() > 0)
		{
			$data = $r->result_array();
			$ret = $data[0]['lead_id'];
		}
	}
	return $ret;
}

function getNameAndRole($user_id)
{
	$ret = array('name' => '', 'role' => '', 'phone' => '', 'mobile' => '', 'email' => '', 'address1' => '', 'address2', 'city' => '');
	if($user_id != '')
	{
		$CI = & get_instance();
		$q = 'SELECT concat(u.first_name, " ", u.last_name) as name, r.name as role, 
				mobile_no as mobile, alternate_number as phone, email_id as email, u.address1, 
				u.address2, u.city, d.distributor_name from user u
				INNER JOIN role r ON r.role_id = u.role_id
				LEFT JOIN distributor_details d ON d.user_id = u.user_id
				WHERE u.user_id = "'.$user_id.'"';
		$r = $CI->db->query($q);
		if($r->num_rows() > 0)
		{
			$data = $r->result_array();
			$ret = $data[0];
			$ret['mobile'] = getPhoneNumber($ret['mobile']);
			$ret['phone'] = getPhoneNumber($ret['phone']);
		}
	}
	return $ret;
}

function getNameAndID($user_id)
{
	$ret = array('name' => '', 'employee_id' => '', 'distributor_name' => '');
	if($user_id != '')
	{
		$CI = & get_instance();
		$q = 'SELECT concat(u.first_name, " ", u.last_name) as name, u.employee_id, d.distributor_name from user u
				LEFT JOIN distributor_details d ON d.user_id = u.user_id
				WHERE u.user_id = "'.$user_id.'"';
		$r = $CI->db->query($q);
		if($r->num_rows() > 0)
		{
			$data = $r->result_array();
			$ret = $data[0];
		}
	}
	return $ret;
}


function updateOtherQuotes($opportunity_id, $quote_id)
{
	$CI = & get_instance();
	$CI->load->model("Common_model");
	$q = "SELECT q.quote_id, q.status from quote_details qd
		INNER JOIN quote q ON q.quote_id = qd.quote_id
		where qd.opportunity_id = '".$opportunity_id."' AND q.quote_id NOT IN (".$quote_id.")";
	$r = $CI->db->query($q);
	foreach($r->result_array() as $row)
	{
		$status = 4;
		if($row['status'] != 4)
		{
			$where = array('quote_id' => $row['quote_id']);
			$updateArray = array('status' => $status, 
									'modified_by' => $CI->session->userdata('user_id'),
									'modified_time' => date('Y-m-d H:i:s'));
			$CI->Common_model->update_data('quote', $updateArray, $where);
			addQuoteStatusHistory($row['quote_id'], $status);
		} 
	}
}

function getUserProducts($user_id=0)
{
	$ret = '';
	$CI = & get_instance();
	if($user_id == 0) $user_id = $CI->session->userdata('user_id');
	$role_id = getUserRole($user_id);

	// if($role_id == 1 || $role_id == 2 || $role_id == 3 || $role_id == 5 || $role_id == 12 || $role_id == 13 || $role_id == 14)
	if($role_id == 1 || $role_id == 2 || $role_id == 3 || $role_id == 4 || $role_id == 5 || $role_id == 7 || $role_id == 8 || $role_id == 9 || $role_id == 12 || $role_id == 13 || $role_id == 14)
	{
		$q = 'SELECT product_id from product where status = 1 AND company_id ='.$_SESSION['company'];
		$r = $CI->db->query($q);
		if($r->num_rows() > 0)
		{
			$i = 0;
			foreach($r->result_array() as $row)
			{
				if($i > 0) $ret .= ',';
				$ret .= $row['product_id'];
				$i++;
			}

		}
	}
	else
	{
		$q = 'SELECT p.product_id from user_product u 
				INNER JOIN product p ON p.product_id = u.product_id
				where p.status = 1 and u.status = 1 AND user_id = "'.$user_id.'"';
		$r = $CI->db->query($q);
		if($r->num_rows() > 0)
		{
			$i = 0;
			foreach($r->result_array() as $row)
			{
				if($i > 0) $ret .= ',';
				$ret .= $row['product_id'];
				$i++;
			}

		}
	}

	return $ret;

}

function getLeadContact($lead_id)
{
	$ret = array('first_name' => '', 'last_name' => '');
	$q = "SELECT first_name, last_name from lead l 
		INNER JOIN user u ON u.user_id = l.user_id
		WHERE lead_id = '".$lead_id."'";
	$CI = & get_instance();
	$r = $CI->db->query($q);
	if($r->num_rows() > 0)
	{
		$data = $r->result_array();
		$ret = $data[0];
	}
	return $ret;
}

function getProductsInGroup($product_id)
{
	$ret = '';
	if($product_id != '')
	{
		$CI = & get_instance();
		$q = 'SELECT group_id from product where product_id = "'.$product_id.'"';
		$r = $CI->db->query($q);
		if($r->num_rows() > 0)
		{
			$groupD = $r->result_array();
			$group = $groupD[0]['group_id'];
			$q1 = 'SELECT product_id from product where group_id = "'.$group.'"';
			$r1 = $CI->db->query($q1);
			$i = 0;
			foreach($r1->result_array() as $row)
			{
				if($i > 0) $ret .= ',';
				$ret .= $row['product_id'];
				$i++;
			}
			//echo $q1;
		}
	}
	if($ret == '') $ret = 0;
	return $ret;
}

function userRevenueGenerated($year = 2016, $month = 7, $user_id = 0,  $role_id = 0, $locations = '')
{
	$ret = 0;
	$CI = & get_instance();
	if($locations == '') $locations = 0;
	$q = 'SELECT round(sum(mrp*o.required_quantity*(1-(discount/100))*(1+(freight_insurance/100)))) as total from opportunity o
			INNER JOIN opportunity_product op ON op.opportunity_id = o.opportunity_id
			INNER JOIN lead l ON l.lead_id = o.lead_id
			where o.status = 6 AND op.product_id IN (
				SELECT product_id from user_product where status = 1 AND user_id = "'.$user_id.'") 
			AND month(o.modified_time) = "'.$month.'" AND year(o.modified_time) = "'.$year.'"';
	if($role_id == 4 || $role_id == 5)
	{
		$q .= ' AND l.user_id = "'.$user_id.'"';
	}
	else
	{
		$q .= ' AND l.location_id IN ('.$locations.')';
	}
	//echo $q;
	$r = $CI->db->query($q);
	if($r->num_rows() > 0)
	{
		$data = $r->result_array();
		$ret = $data[0]['total'];
	}
	return $ret;
}

function userProductTarget($year = 2016, $month = 7, $user_id = 0, $role_id = 0, $product_id = 0, $locations = '')
{
	$ret = 0;
	$CI = & get_instance();
	$year1 = $year + 1;
	if($locations == '') $locations = 0;
	$q = 'SELECT sum(required_quantity) as quantity from opportunity o
			INNER JOIN opportunity_product op ON op.opportunity_id = o.opportunity_id
			INNER JOIN lead l ON l.lead_id = o.lead_id
			where o.status = 6 AND ';
	if($month != 0)
	{
		$q .= 'month(o.modified_time) = "'.$month.'" AND year(o.modified_time) = "'.$year.'" AND ';
	}		
	else
	{
		$q .= '((year(o.modified_time) = "'.$year.'" AND month(o.modified_time) > 3) OR (year(o.modified_time) = "'.$year1.'" AND month(o.modified_time) < 4)) AND ';
	}

			
	$q .=' op.product_id IN ('.getProductsInGroup($product_id).')';
	//echo $q;
	if($role_id == 4 || $role_id == 5)
	{
		$q .= ' AND l.user_id = "'.$user_id.'"';
	}
	else
	{
		$q .= ' AND l.location_id IN ('.$locations.')';
	}
	//echo $q;
	$r = $CI->db->query($q);
	if($r->num_rows() > 0)
	{
		$data = $r->result_array();
		$ret = $data[0]['quantity'];
	}
	return $ret;
}

function userTargetRevenue($year = 2016, $month = 7, $user_id = 0)
{
	$ret = 0;
	$CI = & get_instance();
	$q = 'SELECT sum(p.rrp*up.quantity) as target from user_product_target up 
			INNER JOIN user_product u ON u.user_id = up.user_id AND up.product_id = u.product_id 
			INNER JOIN product p ON p.product_id = up.product_id
			WHERE p.status = 1 AND p.target = 1 AND u.status = 1 AND up.status = 1
			AND up.year_id = "'.$year.'" 
			and up.month_id = "'.$month.'" AND up.user_id = "'.$user_id.'"';
	$r = $CI->db->query($q);
	if($r->num_rows() > 0)
	{
		$data = $r->result_array();
		$ret = $data[0]['target'];
	}
	return $ret;
}





function getUserProductReportees()
{
	$CI = & get_instance();
	$ret = [];

	$userReportees = explode(",",$CI->session->userdata('reportees'));
	$products = explode(",", $CI->session->userdata('products'));
	//print_r($userReportees);
	for($i = 0; $i < count($userReportees); $i++)
	{
		$userProduct = [];
		$user = $userReportees[$i];
		$q = 'SELECT p.product_id from product p
				INNER JOIN user_product up ON p.product_id = up.product_id
				where p.status = 1 and up.status = 1 and up.user_id = "'.$user.'"';
		$r = $CI->db->query($q);
		foreach($r->result_array() as $row)
		{
			$userProduct[] = $row['product_id'];
		}
		if(count(array_intersect($userProduct, $products)) == count($userProduct))
		{
			$ret[] = $user;
		}

	}
	$retu = implode(",",$ret);
	if($retu == '') $retu = 0;
	return $retu;
}

function getTargetBar($target, $completed)
{
	$width = 0;
	$reached = $completed;
	$reached1 = 0;
	if($target != 0) $reached1 = round(($completed/$target)*100);
	if($completed > $target) $width = 100;
	else if($target == 0) $width = 0;
	else $width = round(($completed/$target)*100);

	//echo $width.'-'.$reached.'<br>';

	$bar = ($width < 30)?'danger':(($width < 65)?'warning':'success');
	$ret = '<div class="progress progress-striped active">
                <div class="progress-bar progress-bar-'.$bar.'"  data-toggle="tooltip" title="Completed - '.$reached.'" style=" width: '.$width.'%">'.$reached.' ('.$reached1.' %)</div>
			</div>';
	return $ret;

}


function addQuoteStatusByOpportunity($opportunity_id, $status = 0)
{
	if($status == 7 || $status == 8)
	{
		$CI = & get_instance();
		$quote_status = ($status == 7)?4:5;
		$q = 'SELECT qd.quote_id, q.status from quote_details qd
			INNER JOIN quote q ON q.quote_id = qd.quote_id
			where q.status IN (1, 2) AND opportunity_id = "'.$opportunity_id.'"';
		$r = $CI->db->query($q);
		foreach($r->result_array() as $row)
		{
			$where = array('quote_id' => $row['quote_id']);
			$updateArray = array('status' => $quote_status, 
									'modified_by' => $CI->session->userdata('user_id'),
									'modified_time' => date('Y-m-d H:i:s'));
			$CI->Common_model->update_data('quote', $updateArray, $where);
			addQuoteStatusHistory($row['quote_id'], $quote_status);
		}
	}
}

function getOpenQuoteCountforOpportunity($opportunity_id)
{
	$ret = 0;
	if($opportunity_id != '')
	{
		$q = 'SELECT qd.quote_id from quote_details qd
				INNER JOIN quote q ON q.quote_id = qd.quote_id
				where q.status IN (2) AND opportunity_id = "'.$opportunity_id.'"';
		$CI = & get_instance();
		$r = $CI->db->query($q);
		$ret = $r->num_rows();
	}
	return $ret;
}

function getOpStatusBar($status = 0, $stage = '')
{
	$ret = '';
	// if($status == 6 || $status == 7 || $status == 8)
	// changed on 17-06-2021 for distributor role
	if($status == 6 || $status == 7 || $status == 8 || $status == 10)
	// changed on 17-06-2021 for distributor role end
	{
		// $bar = ($status == 6)?'success':(($status == 7)?'danger':'warning');
		$bar = ($status == 6 || $status == 10)?'success':(($status == 7)?'danger':'warning');
		$ret = '<div class="progress progress-striped active">
                <div class="progress-bar progress-bar-'.$bar.'"  data-toggle="tooltip" title="'.$stage.'" style=" width: 100%">'.$stage.'</div>
			</div>';		
	}
	return $ret;
}

function getQuotesLeadID($quote_id)
{
	$ret = '';
	if($quote_id != '')
	{
		$q = 'SELECT l.lead_id from lead l
				INNER JOIN opportunity o ON o.lead_id = l.lead_id
				INNER JOIN quote_details qd ON qd.opportunity_id = o.opportunity_id
				WHERE qd.quote_id = "'.$quote_id.'"
				group by l.lead_id';
		$CI = & get_instance();
		$r = $CI->db->query($q);
		if($r->num_rows() > 0)
		{
			$data = $r->result_array();
			$ret = $data[0]['lead_id'];
		}
	}
	return $ret;
}

function getQuoteRevisions($quote_id = 0)
{
	$CI = & get_instance();
	$q = 'SELECT quote_revision_id, quote_id, b.name, discount, status, qr.created_time from quote_revision qr
			INNER JOIN billing b ON b.billing_info_id = qr.billing_info_id
			WHERE qr.quote_id = "'.$quote_id.'"
			order by quote_revision_id ';
	$r = $CI->db->query($q);
	return $r->result_array();	
}

/*function getDPQuotePrice($quote_id = 0, $discount = 0)
{
	$cost = 0;
	$CI = & get_instance();
	$q = 'SELECT sum(p.dp*o.required_quantity) as cost, q.freight_insurance from quote_details q
		INNER JOIN opportunity o ON o.opportunity_id = q.opportunity_id
		INNER JOIN opportunity_product op ON op.opportunity_id = o.opportunity_id
		INNER JOIN product p ON p.product_id = op.product_id
		where quote_id = "'.$quote_id.'"';
	$r = $CI->db->query($q);
	if($r->num_rows() > 0)
	{
		$row = $r->result_array();
		$cost = $row[0]['cost'];
		$cost = $cost*(1-($discount/100));
		//$cost = $cost*(1+($row[0]['freight_insurance'])/100);
	}
	return round($cost);
}*/

function getQuotePrice($quote_id = 0, $discount = 0)
{
	$cost = 0;
	$CI = & get_instance();
	$q = 'SELECT sum(q.mrp*o.required_quantity) as cost, q.freight_insurance from quote_details q
		INNER JOIN opportunity o ON o.opportunity_id = q.opportunity_id
		where quote_id = "'.$quote_id.'"';
	$r = $CI->db->query($q);
	if($r->num_rows() > 0)
	{
		$row = $r->result_array();
		$cost = $row[0]['cost'];
		$cost = $cost*(1-($discount/100));
		//$cost = $cost*(1+($row[0]['freight_insurance'])/100);
	}
	return round($cost);
}

function getopportunitiesPerQuote($quote_id = 0)
{
	$CI = & get_instance();
	$q = 'SELECT opportunity_id from quote_details WHERE quote_id = "'.$quote_id.'"';
	$r = $CI->db->query($q);
	return $r->result_array();
}


function updateOtherQuoteRevisionStatus($quote_id, $status = 1, $toStatus = 2)
{
	if($quote_id != '')
	{
		$CI = & get_instance();
		$q = 'UPDATE quote_revision SET status = "'.$toStatus.'", modified_by = "'.$CI->session->userdata('user_id').'", modified_time = NOW() 
				WHERE quote_revision_id > 0 and status = "'.$status.'" AND quote_id = "'.$quote_id.'"';
		
		$r = $CI->db->query($q);
	}
}

function getCNoteQuotes($contract_note_id)
{
	$ret['count'] = 1;
	$ret['resArr'] = array();
	if($contract_note_id != '')
	{
		$CI = & get_instance();

		$q = 'SELECT qr.quote_id, qr.quote_revision_id, b.name billing, qr.discount,qr.created_time as quote_revision_time from contract_note c
				INNER JOIN contract_note_quote_revision cq ON cq.contract_note_id = c.contract_note_id
				INNER JOIN quote_revision qr ON qr.quote_revision_id = cq.quote_revision_id
				INNER JOIN billing b on b.billing_info_id = qr.billing_info_id
				WHERE c.contract_note_id = "'.$contract_note_id.'"';
		$r = $CI->db->query($q);
		$ret['count'] = $r->num_rows();
		$ret['resArr'] = $r->result_array();		
	}
	return $ret;
}

function getQuoteReference($quote_id, $approve = 1)
{
	$ret = '';
	$status = 1;
	if($approve != 1)
	{
		$quoteStatus = getCurrentQuoteStatus($quote_id);
		if($quoteStatus == 6)
		{
			$status = 3;
		}
	}
	if($quote_id != '')
	{
		$CI = & get_instance();
		$q = 'SELECT quote_revision_id from quote_revision where status = "'.$status.'" AND quote_id = "'.$quote_id.'"';
		$r = $CI->db->query($q);
		if($r->num_rows() > 0)
		{
			$data = $r->result_array();
			$ret = $data[0]['quote_revision_id'];
		}
		//echo $q;
	}
	return $ret;
}

/**
 * Formats the price to Indian thousand separator.
 *
 * @param price(int)
 * @return formatted_prcie(string)
 * Author: Mahesh created on: 15th july 2016 11:35 am, updated on: Srilekha 6th Nov 2017
 */
function indian_format_price($price) 
{
    $str=strrev($price);
    $len = strlen($str);
    if($len>3)
    {
        $str1 = substr($str,0,3);
        $str = preg_replace('/'.$str1.'/','',$str,1);
        $str1.=',';
        $str2 = '';
        while(strlen($str)>2)
        {
            $substr = substr($str,0,2);
            $str = preg_replace('/'.$substr.'/','',$str,1);
            $str2.=$substr.',';
        }
        $mainstr = $str1.$str2.$str;
        //echo $mainstr;
        $finalPrice = strrev($mainstr);
        
    }
    else
    {
        $finalPrice = $price;
         
    }
    return $finalPrice;
}

function getLeadIDByQuoteID($quote_id)
{
	$ret = 0;
	if($quote_id != '')
	{
		$CI = & get_instance();
		$q = 'SELECT o.lead_id from quote q
				INNER JOIN quote_details qd ON qd.quote_id = q.quote_id
				INNER JOIN opportunity o ON o.opportunity_id = qd.opportunity_id
				WHERE q.quote_id = "'.$quote_id.'"
				group by o.lead_id';
		$r = $CI->db->query($q);
		if($r->num_rows() > 0)
		{
			$data = $r->row_array();
			$ret = $data['lead_id'];
		}
	}
	return $ret;
}

function getContractFreeProducts($contract_note_id)
{
	$ret = array();
	if($contract_note_id != '')
	{
		$CI = & get_instance();
		$q = 'SELECT p.name, p.description, f.quantity,f.unit_price, p.rrp from free_products f
				INNER JOIN product p ON p.product_id = f.product_id 
				WHERE f.contract_note_id = "'.$contract_note_id.'"';
		$r = $CI->db->query($q);
		$ret = $r->result_array();
	}
	return $ret;
}

function getQuoteIDByContractNote($contract_note_id)
{
	$ret = array();
	if($contract_note_id != '')
	{
		$CI = & get_instance();
		$q = 'SELECT qr.quote_id from contract_note cn 
				INNER JOIN contract_note_quote_revision cr ON cr.contract_note_id = cn.contract_note_id
				INNER JOIN quote_revision qr ON qr.quote_revision_id = cr.quote_revision_id
				WHERE cn.contract_note_id = "'.$contract_note_id.'"';
		$r = $CI->db->query($q);
		foreach($r->result_array() as $row)
		{
			$ret[] = $row['quote_id'];
		}
	}
	if(count($ret) == 0) $ret[0] = 0;
	//echo $q;
	return $ret;
}

function getBranchForUser($user_id)
{
	$CI = & get_instance();
	$ret = '';
	if($user_id != '')
	{
		$q = 'SELECT b.name from branch b
			INNER JOIN user u ON u.branch_id = b.branch_id
			WHERE u.user_id = "'.$user_id.'"';
		$r = $CI->db->query($q);
		if($r->num_rows() > 0)
		{
			$data = $r->result_array();
			$ret = $data[0]['name'];
		}
	}
	return $ret;
}


function getQuoteApprovalCount_old()
{
	$ret = 0;
	$CI = & get_instance();
	$role_id = $CI->session->userdata('role_id');
	if($role_id == 7 || $role_id == 8 || $role_id == 9)
	{
		$reportees = $CI->session->userdata('reportees');
		if($reportees == '') $reportees = 0;
		$products = $CI->session->userdata('products');
		if($products == '') $products = 0;

	    $quoteApp[7] = array('min' => 0, 'max' => 30);
	    $quoteApp[8] = array('min' => 30, 'max' => 35);
	    $quoteApp[9] = array('min' => 35, 'max' => 100);
	    $q = 'SELECT * from quote_approval';
	    $r = $CI->db->query($q);
	    foreach($r->result_array() as $row)
	    {
	        $quoteApp[$row['role_id']] = array('min' => $row['min'], 'max' => $row['max']);
	    }

	    $discount_con=" ";
	    if($role_id == '7')
	    { //RBH
	         $discount_con=" AND qr.discount< ".$quoteApp[7]['max'];
	    }
	    elseif($role_id == '8')
	    { // NSM
	        $discount_con=" AND qr.discount >= ".$quoteApp[8]['min']." AND qr.discount< ".$quoteApp[8]['max'];
	    }
	    elseif($role_id == '9')
	    { //CH
	        $discount_con=" AND qr.discount >= ".$quoteApp[9]['min'];
	    }
	    // new update: 31-05-2018, added qr.discount > 0 in quote_revision table join condition to avoid new quote revisions not listed in old quote approvals count
	    $q = 'SELECT q.quote_id from quote q
	        INNER JOIN quote_revision qr ON qr.quote_id = q.quote_id AND case when q.status = 1 then qr.status = 1 else 
	        CASE when q.status = 6 then qr.status = 3 end end AND qr.discount > 0
	        INNER JOIN quote_details qd ON qd.quote_id = q.quote_id
	        INNER JOIN opportunity o ON o.opportunity_id = qd.opportunity_id
	        INNER JOIN opportunity_product op ON op.opportunity_id = o.opportunity_id
	        INNER JOIN product p ON p.product_id = op.product_id AND q.company_id='.$CI->session->userdata('company').' AND p.product_id IN ('.$products.')
	        WHERE q.created_by IN ('.$reportees.', 1) '.$discount_con.'
	        GROUP BY q.quote_id';
	     $r = $CI->db->query($q);
		 //echo $q;
	     $ret = $r->num_rows();
	 }
     return $ret;
}

function getBankDetails($user_id = 0,$quote_id=0)
{
	$ret = array('bank_name' => '', 'branch' => '', 'ac_name' => '', 'ac_no' => '', 'ifsc' => '','bank_address'=>'','account_type'=>'','benificiary_name'=>'','benificiary_address'=>'','channel_type'=>'','communication_address'=>'');
	if($user_id == 0)
	{
		/*$ret = array('bank_name' => 'Corporation Bank', 
					'branch' => 'Industrial Finance Branch, Bangalore', 
					'ac_name' => 'SKANRAY TECHNOLOGIES PRIVATE LIMITED', 
					'ac_no' => '43801601000260', 
					'ifsc' => 'CORP0000438');*/
		// Get company details
		$CI = & get_instance();
		$channel_partner_details = get_channel_partner_details($quote_id);
		if($channel_partner_details['type']==2)
		{
			$q = 'SELECT * FROM company WHERE company_id = '.$CI->session->userdata('company');
			$r = $CI->db->query($q);
			if($r->num_rows() > 0)
			{
				$row = $r->row_array();
			}
			$ret = array('bank_name' => @$row['bank_name'], 
						'branch' => @$row['branch'], 
						'ac_name' => @$row['ac_name'], 
						'ac_no' => @$row['ac_no'], 
						'ifsc' => @$row['ifsc']);
		}
		else
		{
			$q = "SELECT * FROM channel_partner WHERE company_id = '".$CI->session->userdata('company')."' and channel_partner_id='".$channel_partner_details['channel_partner_id']."' " ;
			$res=$CI->db->query($q);
			$row  = $res->row_array();
			$ret = array(
				'bank_name' => $row['bank_name'],
				'bank_address'=>$row['bank_address'],
				'ac_no' => $row['account_number'],
				'ifsc'  => $row['ifsc_code'],
                'account_type'=>$row['account_type'],
                'benificiary_name'=>$row['benificiary_name'],
                'benificiary_address'=>$row['benificiary_address'],
                'channel_type'=>1,
                'communication_address'=>$row['communication_address']
				);

		}
	}
	else
	{
		$CI = & get_instance();
		$q = 'SELECT bank_name, branch, ac_name, ac_no, ifsc from distributor_details WHERE user_id = "'.$user_id.'"';
		$r = $CI->db->query($q);
		if($r->num_rows() > 0)
		{
			$data = $r->result_array();
			$ret = $data[0];
		}
	}
	return $ret;
}

function getDistributorName($user_id = '')
{
	$ret = '';
	if($user_id != '')
	{
		$CI = & get_instance();
		$q = 'SELECT distributor_name from distributor_details WHERE user_id = "'.$user_id.'"';
		$r = $CI->db->query($q);
		if($r->num_rows() > 0)
		{
			$data = $r->result_array();
			$ret = $data[0]['distributor_name'];
		}
	}
	return $ret;
}

function getProductDetailsForOpprotunity($opportunity_id)
{
	$ret = array('mrp' => '', 'ed' => '', 'vat' => '', 'freight_insurance' => '','gst'=>'','currency_id'=>'');
	if($opportunity_id != '')
	{

		$CI = & get_instance();
		$default_currency = $CI->Common_model->get_value('company',array('company_id'=>$CI->session->userdata('company'),'status'=>1),'currency_id');
		$opp_product_currency = $CI->Common_model->get_value('opportunity_product',array('opportunity_id'=>$opportunity_id),'currency_id');
		if($default_currency == $opp_product_currency)
		{
			$q = 'SELECT p.mrp, p.ed, p.vat, p.freight_insurance, p.gst from opportunity o
					INNER JOIN opportunity_product op ON op.opportunity_id = o.opportunity_id
					INNER JOIN product p ON p.product_id = op.product_id
					WHERE o.opportunity_id = "'.$opportunity_id.'"';
			$r = $CI->db->query($q);
			if($r->num_rows() > 0)
			{
				$data = $r->row_array();
				$data['currency_id']=$opp_product_currency;
				$ret = $data;
			}
	    }
	    else
	    {
	    	$q1 = 'SELECT p.mrp, p.ed, p.vat, p.freight_insurance, p.gst from opportunity o
					INNER JOIN opportunity_product op ON op.opportunity_id = o.opportunity_id
					INNER JOIN product_currency p ON p.product_id = op.product_id
					WHERE o.opportunity_id = "'.$opportunity_id.'"';
			$r1 = $CI->db->query($q1);
			if($r1->num_rows() > 0)
			{
				$data1 = $r1->row_array();
				$data1['currency_id']=$opp_product_currency;
				$ret = $data1;
			}
			else
			{
				$q2 = 'SELECT p.mrp, p.ed, p.vat, p.freight_insurance, p.gst from opportunity o
					INNER JOIN opportunity_product op ON op.opportunity_id = o.opportunity_id
					INNER JOIN product p ON p.product_id = op.product_id
					WHERE o.opportunity_id = "'.$opportunity_id.'"';
				$r2 = $CI->db->query($q2);
				if($r2->num_rows() > 0)
				{
					$data2 = $r2->row_array();
					$data2['currency_id']=$default_currency;
					$ret = $data2;
				}
			}
		}
	}
	return $ret;
}

function getProductCostingDetails($product_id,$lead_id)
{
	$ret = array('mrp' => '', 'ed' => '', 'vat' => '', 'freight_insurance' => '','gst'=>'','currency_id'=>'');
	if($product_id != '')
	{
		$CI = & get_instance();
		$customer_id = $CI->Common_model->get_value('lead',array('lead_id'=>$lead_id,'company_id'=>$CI->session->userdata('company')),'customer_id');
		$customer_location = $CI->Common_model->get_value('customer_location',array('customer_id'=>$customer_id),'location_id');
		$default_currency = $CI->Common_model->get_value('company',array('company_id'=>$CI->session->userdata('company'),'status'=>1),'currency_id');
		$cur_res = get_customer_currency($customer_location,$product_id);
		$def_cur = $cur_res[0];
		$def_cur_id = $cur_res[1];
		if($def_cur==1)
		{
			$q = 'SELECT p.mrp, p.ed, p.vat, p.freight_insurance, p.gst from product p
					WHERE p.product_id = "'.$product_id.'" AND p.company_id = "'.$_SESSION['company'].'"';
			$r = $CI->db->query($q);
			if($r->num_rows() > 0)
			{
				$data = $r->row_array();
				$data['currency_id']=$def_cur_id;
				$ret = $data;
			}
		}
		else
		{
			$q1 = 'SELECT p.mrp, p.ed, p.vat, p.freight_insurance, p.gst from product_currency p
					WHERE p.product_id = "'.$product_id.'" AND p.company_id = "'.$_SESSION['company'].'" and p.currency_id="'.$def_cur_id.'"';
			$r1 = $CI->db->query($q1);
			if($r1->num_rows()>0)
			{
				$data1 = $r1->row_array();
				$data1['currency_id']=$def_cur_id;
				$ret = $data1;
			}
			else
			{
				$q2 = 'SELECT p.mrp, p.ed, p.vat, p.freight_insurance, p.gst from product p
					WHERE p.product_id = "'.$product_id.'" AND p.company_id = "'.$_SESSION['company'].'"';
				$r2 = $CI->db->query($q2);
				if($r2->num_rows() > 0)
				{
					$data2 = $r2->row_array();
					$data2['currency_id']=$default_currency;
					$ret = $data2;
				}
			}
		}
	}
	return $ret;

}

function getUserExactLocation($user_id)
{
	$ret = '';
	if($user_id != '')
	{
		$CI = & get_instance();
		$q = 'SELECT ul.location_id from user_location ul where ul.status = 1 AND user_id = "'.$user_id.'"';
		$r = $CI->db->query($q);
		$i = 0;
		foreach($r->result_array() as $row)
		{
			if($i > 0) $ret .= ',';
			$ret .= $row['location_id'];
		}  
	}
	if($ret == '') $ret = 0;
	return $ret;
}

function getRSMAboveNextLevel($first_level_role_id, $userLocations)
{
	$CI = & get_instance();
	$ret = '';
	$q = 'SELECT u.user_id, u.email_id from user u 
		INNER JOIN user_location ul ON ul.user_id = u.user_id 
		where u.status = 1 AND u.role_id = "'.$first_level_role_id.'" AND ul.location_id IN ('.$userLocations.')
		GROUP BY u.user_id';
	$r = $CI->db->query($q);
	$b = 0;
	foreach($r->result_array() as $row)
	{
		if($b > 0) $ret .= ',';
		$ret .= $row['email_id'];
	}
	return $ret;
}

function getCHforRBH($first_level_role_id, $userLocations)
{
	$ret = '';
	$parent = 0;
	$CI = & get_instance();
	$locations = explode(",", $userLocations);
	$location_id = $locations[0];
	$q = "SELECT parent_id from location where location_id = '".$location_id."'";
	$r = $CI->db->query($q);
	if($r->num_rows() > 0)
	{
		$data = $r->result_array();
		$parent = $data[0]['parent_id'];
	}
	//echo $parent;
	$ret = getRSMAboveNextLevel($first_level_role_id, $parent);
	return $ret;
}

function getRegionforUser($userLocations)
{
	$region = 0;
	$CI = & get_instance();
	$locations = explode(",", $userLocations);
	$location_id = $locations[0];
	$q = 'SELECT l2.parent_id from location l
		INNER JOIN location l1 ON l1.location_id = l.parent_id
		INNER JOIN location l2 ON l2.location_id = l1.parent_id
		WHERE l.location_id = "'.$location_id.'"';
	$r = $CI->db->query($q);
	if($r->num_rows() > 0)	
	{
		$data = $r->result_array();
		$region = $data[0]['parent_id'];
	}
	return $region;
}

function getUserAllProducts($user_id)
{
	$productArr = [];
	if($user_id != '')
	{
		$CI = & get_instance();
		$q1 = 'SELECT p.product_id from product p
				INNER JOIN user_product up ON p.product_id = up.product_id
				where p.status = 1 and up.status = 1 and up.user_id = "'.$user_id.'"';
		$r1 = $CI->db->query($q1);
		foreach($r1->result_array() as $row1)
		{
			$productArr[] = $row1['product_id'];
		}
	}
	return $productArr;
}

function getReporting($user_id, $level = 1)
{
	$ret = '';
	if($user_id != '')
	{
		$CI = & get_instance();
		$role_id = getUserRole($user_id);
		$userProductArr = getUserAllProducts($user_id);
 		if($role_id == 4)
		{
			$arr = [];
			$userLocationsArr = getUserLocations($user_id);
			$userLocations = implode(",", $userLocationsArr);
			$region = getRegionforUser($userLocations);
			$first_level_role_id = '6,7';
			//if(count(array_intersect($userProduct, $products)) == count($userProduct))
			$q = 'SELECT u.user_id, u.email_id from user u 
				INNER JOIN user_location ul ON ul.user_id = u.user_id
				WHERE u.status = 1 AND u.role_id IN ('.$first_level_role_id.') AND ul.location_id IN ('.$region.') AND ul.status = 1
				GROUP BY u.user_id';
			$r = $CI->db->query($q);
			foreach($r->result_array() as $row)
			{
				$productArr = [];
				$user = $row['user_id'];
				$productArr = getUserAllProducts($user);
				if(count(array_intersect($productArr, $userProductArr)) == count($userProductArr))
				{
					$arr[] = $row['email_id'];
				}
			}
			$ret = implode(",", $arr);

		}
		if($role_id == 6)
		{
			$userLocations = getUserExactLocation($user_id);
			if($level == 1)
			{
				$first_level_role_id = 7;
				$ret = getRSMAboveNextLevel($first_level_role_id, $userLocations);
			}
			else
			{
				$arr = [];
				$first_level_role_id = 8;
				$userLoc = 0;
				$qry = 'SELECT parent_id from location where location_id IN ('.$userLocations.')';
				$res = $CI->db->query($qry);
				if($res->num_rows() > 0)
				{
					$d = $res->result_array();
					$userLoc = $d[0]['parent_id'];
				}
				$q = 'SELECT u.user_id, u.email_id from user u 
					INNER JOIN user_location ul ON ul.user_id = u.user_id
					WHERE u.status = 1 AND u.role_id IN ('.$first_level_role_id.') AND ul.location_id IN ('.$userLoc.')
					GROUP BY u.user_id';
				$r = $CI->db->query($q);
				foreach($r->result_array() as $row)
				{
					$productArr = [];
					$user = $row['user_id'];
					$productArr = getUserAllProducts($user);
					if(count(array_intersect($productArr, $userProductArr)) == count($userProductArr))
					{
						$arr[] = $row['email_id'];
					}
				}
				$ret = implode(",", $arr);

			}
		}
		if($role_id == 7)
		{
			$userLocations = getUserExactLocation($user_id);
			$first_level_role_id = 9;
			$ret = getCHforRBH($first_level_role_id, $userLocations);
		}
		if($role_id == 8)
		{
			$userLocations = getUserExactLocation($user_id);
			$first_level_role_id = 9;
			$ret = getRSMAboveNextLevel($first_level_role_id, $userLocations);
		}

	}
	return $ret;
}

function getCustomerSAPCode($lead_id)
{
	$ret = '';
	if($lead_id != '')
	{
		$CI = & get_instance();
		$q = 'SELECT c.remarks2 from customer c
				INNER JOIN lead l ON l.customer_id = c.customer_id
				WHERE l.lead_id = "'.$lead_id.'"';
		$r = $CI->db->query($q);
		if($r->num_rows() > 0)
		{
			$data = $r->result_array();
			$ret = $data[0]['remarks2'];
		}
	}
	return $ret;
}

function getTargetValue($val = 0)
{
	$ret = indian_format_price($val);
	$retVal = 0;
	if($val == 0) $ret = '';
	else
	{
		$lakhValue = round(($val/100000));
		$retVal = indian_format_price($lakhValue);
	}
	/*
	else if($val > 10000000)
	{
		$croreValue = round(($val/10000000), 2);
		$ret = $croreValue.' Crores';
	} 
	else if($val > 100000)
	{
		$lakhValue = round(($val/100000), 2);
		$ret = $lakhValue.' Lakhs';
	}
	else if($val > 1000)
	{
		$thousandValue = round(($val/1000), 2);
		$ret = $thousandValue.' Thousands';
	}
	*/
	if($retVal == 1) $ret = $retVal.' Lakh';
	else if($retVal != 0) $ret = $retVal.' Lakhs';


	return $ret;
}

function getTargetValueBar($reached = 0, $target = 0)
{
	return getTargetValue($reached);
	if($reached == 0) $ret = '';
	else if($target == 0) $ret = getTargetValue($reached);
	else
	{
		$reachedPer = ($reached/$target)*100;
		$width = $reachedPer;
		if($reachedPer > 100) $width = 100;
		if($width < 30) $bar = 'danger';
		else if($width < 65) $bar = 'warning';
		else $bar = 'success';
		$reachedValue = getTargetValue($reached);
		$ret = '<div class="progress progress-striped active">
	                <div class="progress-bar progress-bar-'.$bar.'"  data-toggle="tooltip" title="Generated - '.$reachedValue.'" style=" width: '.$width.'%">'.$reachedValue.' ('.$reachedPer.' %)</div>
				</div>';

	}
	return $ret;
}

function userYearTargetRevenue($year = 2016, $user_id = 0)
{
	$ret = 0;
	if($user_id != '' && $year != '')
	{
		$year1 = $year + 1;
		$CI = & get_instance();
		$q = 'SELECT sum(p.rrp*up.quantity) as target from user_product_target up
				INNER JOIN user_product u ON u.user_id = up.user_id AND up.product_id = u.product_id 
				INNER JOIN product p ON p.product_id = up.product_id
				WHERE p.status = 1 AND p.target = 1 AND u.status = 1 AND up.status = 1 AND
				((up.year_id = "'.$year.'" AND up.month_id > 3) OR (up.year_id = "'.$year1.'" AND up.month_id < 4))
				AND up.user_id = "'.$user_id.'"';
		$r = $CI->db->query($q);
		if($r->num_rows() > 0)
		{
			$data = $r->result_array();
			$ret = $data[0]['target'];
		}
	}
	return $ret;
}

function userYearRevenueGenerated($year = 2016, $user_id = 0, $role_id = 0, $locations = '')
{
	$ret = 0;
	$year1 = $year + 1;
	$CI = & get_instance();
	if($locations == '') $locations = 0;
	$q = 'SELECT round(sum(mrp*o.required_quantity*(1-(discount/100))*(1+(freight_insurance/100)))) as total from opportunity o
			INNER JOIN opportunity_product op ON op.opportunity_id = o.opportunity_id
			INNER JOIN lead l ON l.lead_id = o.lead_id
			where o.status = 6 AND op.product_id IN (
				SELECT product_id from user_product where status = 1 AND user_id = "'.$user_id.'") 
				AND ((year(o.modified_time) = "'.$year.'" AND month(o.modified_time) > 3) OR 
					(year(o.modified_time) = "'.$year1.'" AND month(o.modified_time) < 4))';
	if($role_id == 4 || $role_id == 5)
	{
		$q .= ' AND l.user_id = "'.$user_id.'"';
	}
	else
	{
		$q .= ' AND l.location_id IN ('.$locations.')';
	}
	//echo $q;
	$r = $CI->db->query($q);
	if($r->num_rows() > 0)
	{
		$data = $r->result_array();
		$ret = $data[0]['total'];
	}


	return $ret;
}


function getLeadStatus1($status)
{
	$ret = '';
	switch($status)
	{
		case 1:
			$ret = 'Lead Created';
			break;
		case 2:
			$ret = 'Lead Approved';
			break;
		case 3:
			$ret = 'Opportunity Created';
			break;
		case 4:
			$ret = 'All Opportunities Dropped';
			break;
		case 5:
			$ret = 'All Opportunities Lost or Dropped';
			break;		
		case 6:
			$ret = 'Partial Quote';
			break;
		case 7:
			$ret = 'Full Quote';
			break;
		case 8:
			$ret = 'Partial Contract Note - Partial Quote';
			break;
		case 9:
			$ret = 'Partial Contract Note - Full Quote';
			break;															
		case 10:
			$ret = 'Full Contract Note';
			break;
		case 15:
			$ret = 'Planned Visit';
			break;
		case 16:
			$ret = 'Planned Demo';
			break;			
		case 19:
			$ret = 'Lead Owner Role Changed';
			break;				
		case 20:
			$ret = 'Lead Rejected';
			break;
		case 21:
			$ret = 'Lead Dropped';	
			break;		
		case 22:
			$ret = 'Lead Closed';
			break;		
	}
	return $ret;
}

function getLeadEvents($lead_id)
{
	$ret = array();
	$CI = & get_instance();
	if($lead_id != '')
	{
		$q = 'SELECT l.status, concat(u.first_name, " ", u.last_name) created_user, 
				date(l.created_time) as created_date, case when (l.status = 15 OR l.status = 16) then 1 else "NA" end as end_date,
				case when (l.status = 15 OR l.status = 16) then 1 end as remarks
				from lead_status_history l 
				INNER JOIN user u ON u.user_id = l.created_by
				where l.lead_id = "'.$lead_id.'"
				UNION ALL
				SELECT 15 as status, concat(u.first_name, " ", u.last_name) created_user,
				v.start_date as created_date, v.end_date, v.remarks1 as remarks from lead l
				INNER JOIN visit v on v.lead_id = l.lead_id
				INNER JOIN user u ON u.user_id = v.created_by
				where l.lead_id = "'.$lead_id.'"
				UNION ALL
				SELECT 16 as status, concat(u.first_name, " ", u.last_name) created_user,
				d.start_date as created_date, d.end_date, d.remarks1 as remarks from lead l
				INNER JOIN opportunity o ON l.lead_id = o.lead_id
				INNER JOIN demo d ON d.opportunity_id = o.opportunity_id
				INNER JOIN user u ON u.user_id = d.created_by
				where l.lead_id = "'.$lead_id.'"
				order by created_date, status';
		$r = $CI->db->query($q);
		$ret = $r->result_array();
	}
	return $ret;
}

/** new enhancement: 20th april 2017  START **/
function getLeadIDByCNoteID($contract_note_id){
	if($contract_note_id != ''){
		$CI = & get_instance();
		
		$q = 'SELECT l.lead_id from lead l
		      INNER JOIN opportunity o ON o.lead_id = l.lead_id
			  INNER JOIN quote_details qd ON qd.opportunity_id = o.opportunity_id
			  INNER JOIN quote q ON q.quote_id = qd.quote_id
              INNER JOIN quote_revision qr ON qr.quote_id = q.quote_id
              INNER JOIN contract_note_quote_revision cnr ON cnr.quote_revision_id = qr.quote_revision_id
              INNER JOIN contract_note cn ON cn.contract_note_id = cnr.contract_note_id
              WHERE cn.contract_note_id = "'.$contract_note_id.'"';
	    $query = $CI->db->query($q);
		$res = $query->result_array();
		return $res;
	}
}
/** new enhancement: 20th april 2017  END **/
//prasad
function allowed_download_roles()
{   
	//RSM,RBH,NSM,CH,GH,SD,ADMIN,ADMIN MARKETING
	$roles=array(2,3,6,7,8,9,10,11,4);
	return $roles;
}
//prasad
function search_types()
{
	$search_types=array(
		'all'=>'All',
		'lead'=>'Lead Id',
		'opp'=>'Opp Id',
		'product'=>'Product',
		'description'=>'Product Des',
		'customer'=>'Customer',
		'segment'=>'Segment',
		'category'=>'Category',
		'status'=>'opportunity Status',
		'role'=>'Designation',
		'contact'=>'Contact',
		'user'=>'Lead Owner',
		'sol'=>'SourceOfLead',
		'sof'=>'SourceOfFund',
		'sitereadiness'=>'Site Readiness',
		'relationship'=>'Customer Relation',
		'city'=>'City',
		'district'=>'District',
		'state'=>'State',
		'region'=>'Region'
		);
	return $search_types;
}
/* file end: ./application/helpers/naveen_helper.php */