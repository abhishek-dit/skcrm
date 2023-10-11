<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function po_opportunity_status($status)
{
	if($status==1)
	{
		$opp_status="Tagged";
	}
	elseif($status==2)
	{
		$opp_status="Untagged";
	}
	elseif($status==3)
	{
		$opp_status="Closed Won";
	}
	elseif($status==4)
	{
		$opp_status="Closed Lost";
	}
	return $opp_status;
}
function date_difference_two_days($created_date,$modified_date)
{
	//echo $created_date;exit;
	$days='';
	if($created_date!=''&& $modified_date!='')
	{
		$CI=& get_instance();
		$created_date1=date('Y-m-d',strtotime($created_date));
		$modified_date1=date('Y-m-d',strtotime($modified_date));
		$res=strtotime($modified_date1)-strtotime($created_date1);
		$days=$res/86400;
	}
		return $days;
}
function lead_opp_status($created_date,$modified_date,$status)
{
	$days='';
	if($status<=19)
	{
		    $created_date1=date('Y-m-d',strtotime($created_date));
			$modified_date1=date('Y-m-d');
			$res=strtotime($modified_date1)-strtotime($created_date1);
		    $days=$res/86400;
	}
	else
	{   
		if($modified_date!='')
		{
			$created_date1=date('Y-m-d',strtotime($created_date));
			$modified_date1=date('Y-m-d',strtotime($modified_date));
			$res=strtotime($modified_date1)-strtotime($created_date1);
		    $days=$res/86400;
		}
	}
	return $days;
}
function get_opp_life_time($oCTime,$opp_mtime,$status)
{   $days='';
	if($status<=5)
	{
		$created_date=date('Y-m-d',strtotime($oCTime));
		$modified_date=date('Y-m-d');
		$res=strtotime($modified_date)-strtotime($created_date);
	    $days=$res/86400;
	}
	else
	{   
		if($opp_mtime!='')
		{
			$created_date=date('Y-m-d',strtotime($oCTime));
			$modified_date=date('Y-m-d',strtotime($opp_mtime));
			$res=strtotime($modified_date)-strtotime($created_date);
		    $days=$res/86400;
		}
	}
	return $days;
}
function cnote_status_array()
{
	$status_array=array(
		'1'=>'Waiting at SO Entry',
		'2'=>'Completed',
		'3'=>'Waiting at Clear for invoice'
		);
	return $status_array;
}

// Get Month Name
function get_month_name($month_number)
{
	$time = mktime(0, 0, 0, $month_number);
	$name = strftime("%b", $time);
	return $name;
}
// To validate months format
function validateDate($date)
{
    $d = DateTime::createFromFormat('M-Y', $date);
    return $d && $d->format('M-Y') === $date;
}

// to get month number
function get_month_number($val)
{
	$month_arr=array('1'=>'JAN','2'=>'FEB','3'=>'MAR','4'=>'APR','5'=>'MAY','6'=>'JUN',
		             '7'=>'JUL','8'=>'AUG','9'=>'SEP','10'=>'OCT','11'=>'NOV','12'=>'DEC');
	$res=array_search(strtolower($val), array_map('strtolower', $month_arr));
	if($res!='')
	{
		return $res;
	}
}
// To get months array
function get_upload_months()
{
	$months=array('JAN','FEB','MAR','APR','MAY','JUN',
		             'JUL','AUG','SEP','OCT','NOV','DEC');
	return $months;
}
function get_extra_warranty_cost($total_val,$dp_val,$warranty,$default_warranty)
{   
	//echo $dp_val.'hi';exit;
	if($warranty > 0)
	{
		$cost_of_warranty = get_preference('cost_of_maintaining_warranty','margin_settings'); 
		$f=$warranty/12; //= warranty_in_years
        $k=$cost_of_warranty; 
        $results=array();
        if($warranty > $default_warranty)
        {
        	$war_dis_value= $dp_val*pow((1+$k/100),($f-1))-$dp_val;
        }
        else{
        	$war_dis_value=0;
        }
        $results['war_dis_value']=round($war_dis_value);
        $results['grand_total']=round($total_val+$war_dis_value);
		return $results;
	}
}
function get_uploaded_file_data($upload_id)
{
	$ci=& get_instance();
	$ci->db->select();
	$ci->db->from('new_so_outstanding_amount');
	$ci->db->where('upload_id',$upload_id);
	$res=$ci->db->get();
	return $res->num_rows();
}
function upload_date_format($date,$format='M-Y')
{
	$timestamp = strtotime($date);
	return date($format,$timestamp);
}

function get_po_count_by_status($status)
{
    $CI=& get_instance();
    $start_date=date('Y-m-d', strtotime('-7 days'));
    $end_date=date('Y-m-d');
    $CI->db->select('count(*) as po_count');
    $CI->db->from('purchase_order');
    $CI->db->where('status',$status);
    $CI->db->where('date(modified_time)>=',$start_date);
    $CI->db->where('date(modified_time)<=',$end_date);
    $CI->db->where('user_id',$CI->session->userdata('user_id'));
    $res=$CI->db->get();
    $row=$res->row_array();
    return ($row['po_count']>0)?$row['po_count']:0;
}

function margin_allowed_roles()
{
	$allowed_roles=array(1,2,3,8,9,10,11,12,13);
	return $allowed_roles;
}

function format_upload_amount($str_amount)
{
	if( strpos($str_amount, ',') !== false )
	{
		$amount= str_replace(',','',trim($str_amount));
		$latest_amount=round(trim($amount),2);
	}
	else
	{
		$latest_amount=$str_amount;
	}
	//$new_amount=round($amount,2);
	return $latest_amount;
}


function truncate_missed_record_tables()
{
	$ci=& get_instance();
	$tables=array('missing_product_files');
	foreach($tables as $key=>$value)
	{
		$ci->db->truncate($value);
	} 
}

function report_user_locations($searchfilters)
{   
	$CI= & get_instance();
	$CI->db->select('ul.user_id as users_id');
	$CI->db->from('location l1');
	$CI->db->join('location l2','l1.location_id=l2.parent_id');
	$CI->db->join('location l3','l2.location_id=l3.parent_id');
	$CI->db->join('location l4','l3.location_id=l4.parent_id');
	$CI->db->join('user_location ul','l1.location_id=ul.location_id or l2.location_id=ul.location_id or l3.location_id=ul.location_id or l4.location_id=ul.location_id');
	$CI->db->where('l1.location_id',$searchfilters['regions']);
	$CI->db->where('l1.territory_level_id',4);
	$CI->db->group_by('ul.user_id');
	$res=$CI->db->get();
//	echo $CI->db->last_query();exit;
	$users_id =$res->result_array();
	$user_arr = array();
	foreach ($users_id as $user) 
	{
		$user_arr[] = $user['users_id'];
	}
	$users_id=implode(',',$user_arr); 
	return $users_id;
}

function report_user_locations_by_region($loc)
{   
	$CI= & get_instance();
	$CI->db->select('ul.user_id as users_id');
	$CI->db->from('location l1');
	$CI->db->join('location l2','l1.location_id=l2.parent_id');
	$CI->db->join('location l3','l2.location_id=l3.parent_id');
	$CI->db->join('location l4','l3.location_id=l4.parent_id');
	$CI->db->join('user_location ul','l1.location_id=ul.location_id or l2.location_id=ul.location_id or l3.location_id=ul.location_id or l4.location_id=ul.location_id');
	$CI->db->where('l1.location_id',$loc);
	$CI->db->where('l1.territory_level_id',4);
	$CI->db->group_by('ul.user_id');
	$res=$CI->db->get();
	$users_id =$res->result_array();
	$user_arr = array();
	foreach ($users_id as $user) 
	{
		$user_arr[] = $user['users_id'];
	}
	$users_id=implode(',',$user_arr); 
	return $users_id;
}
function open_order_status()
{
	$cn_status=array(
		0=>'Fresh not cleared',
		1=>'Fresh open orders cleared',
		2=>'Old not cleared',
		3=>'Old open orders cleared');
	return $cn_status;
}
function get_pro_name_by_id($product_id)
{
	$ci=& get_instance();
	$res=$ci->Common_model->get_value('product',array('product_id'=>$product_id),'description');
	return $res;
}
function update_closed_time_opportunity_status($opportunity_id)
{    
	$CI = & get_instance();
	$arr=array(
		'closed_time'=>date('Y-m-d H:i:s'),
	    'closed_by'=>$CI->session->userdata('user_id'));
	$CI->Common_model->update_data('opportunity',$arr,array('opportunity_id'=>$opportunity_id));
}
function clean($string) {
   return preg_replace("/[^0-9.]/", "", $string);
}
// channel partner: 11-10-2018
function get_channel_partner_details($quote_id)
{
	$ci = & get_instance();
	$ci->db->select('cp.*');
	$ci->db->from('quote q');
	$ci->db->join('channel_partner cp','q.channel_partner_id=cp.channel_partner_id');
	$ci->db->where('q.quote_id',$quote_id);
	$ci->db->where('q.company_id',$_SESSION['company']);
	$res= $ci->db->get();
	return $res->row_array();
}
function trim_ck_editor_data($value)
{
	$res3 = '';
	if($value!='')
	{
		$res = trim(strip_tags($value));
	//	$res1 = preg_replace('/-/', " ", $res);
		$res2 = preg_replace('/[\r\n]+/', "\n", $res);
		$res3 = preg_replace('/[ \t]+/', ' ',$res2);
	}
	return $res3;
}