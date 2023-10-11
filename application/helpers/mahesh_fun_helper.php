<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * GET ASSETS URL
 * @param: $exculdeSlash(boolean),  default: false
 * @return URL(string)
 * created by Mahesh on 15th june 2016
*/
function getDefaultPerPageRecords()
{
	return 10;
}

function assets_url($excludeSlash = false)
{
	$assetsUrl = SITE_URL1 . 'application/assets';
	if (!$excludeSlash)
		$assetsUrl .= '/';
	return $assetsUrl;
}

function icrm_encode($id)
{
	$CI = &get_instance();
	//return $CI->encrypt->encode($id);
	return str_replace(array('/'), array('asdf99797'), $CI->encrypt->encode($id));
}
function icrm_decode($id)
{
	$CI = &get_instance();
	$id = str_replace(array('asdf99797', ' '), array('/', '+'), $id);
	return $CI->encrypt->decode($id);
}


function get_paginationConfig()
{
	//config for bootstrap pagination class integration
	$config = array();
	$config['full_tag_open'] = '<ul class="pagination">';
	$config['full_tag_close'] = '</ul>';
	$config['first_link'] = false;
	$config['last_link'] = false;
	$config['first_url'] = '0';
	$config['first_tag_open'] = '<li>';
	$config['first_tag_close'] = '</li>';
	$config['prev_link'] = '&laquo';
	$config['prev_tag_open'] = '<li class="prev">';
	$config['prev_tag_close'] = '</li>';
	$config['next_link'] = '&raquo';
	$config['next_tag_open'] = '<li>';
	$config['next_tag_close'] = '</li>';
	$config['last_tag_open'] = '<li>';
	$config['last_tag_close'] = '</li>';
	$config['cur_tag_open'] = '<li class="active"><a href="#">';
	$config['cur_tag_close'] = '</a></li>';
	$config['num_tag_open'] = '<li>';
	$config['num_tag_close'] = '</li>';

	return $config;
}

/**
 * get all products
 * author: mahesh , created on: 20th june 2016 06:35 PM, updated on: --
 * params: $companyId(int) defalult:0
 * return: $products(array)
 **/
function getAllProducts($companyId = 0)
{
	$CI = &get_instance();
	$CI->db->select('p.*');
	if ($companyId > 0)
		$CI->db->where('pc.company_id', $companyId);
	$CI->db->where('p.status', 1);
	$CI->db->from('product p');
	$CI->db->join('product_group pg', 'pg.group_id = p.group_id', 'INNER');
	$CI->db->join('product_category pc', 'pc.category_id = pg.category_id', 'INNER');
	$CI->db->order_by('p.product_id', 'ASC');
	$query = $CI->db->get();
	return $query->result_array();
}

function map_locations($user_locations)
{
	$str = '';
	if (count($user_locations) > 0) {

		foreach ($user_locations as $userlocation) {

			if (count($userlocation['childs']) > 0) {
				$str .= '<li><label class="tree-toggler nav-header"><i class="fa fa-plus-square-o"></i> ' . $userlocation['location_name'] . '</label>';
				$str .= '<ul class="nav nav-list tree">';
				$str .= map_locations($userlocation['childs']);
				$str .= '</ul>';
			} else {
				$str .= '<li><label><i class="fa fa-check-square-o"></i> ' . $userlocation['location_name'] . '</label></li>';
			}
		}
	}
	return $str;
}

function map_products($user_products)
{
	$str = '';
	//print_r($user_products); exit;
	if (count($user_products) > 0) {

		foreach ($user_products as $userproduct) {

			if (count(@$userproduct['childs']) > 0) {
				$str .= '<li><label class="tree-toggler nav-header"><i class="fa fa-plus-square-o"></i> ' . $userproduct['product_name'] . '</label>';
				$str .= '<ul class="nav nav-list tree">';
				$str .= map_products(@$userproduct['childs']);
				$str .= '</ul>';
			} else {
				$str .= '<li><label><i class="fa fa-check-square-o"></i> ' . $userproduct['product_name'] . '</label></li>';
			}
		}
	}
	return $str;
}

/**
 * get locations by parent
 * author: mahesh , created on: 28th june 2016 03:45 PM, updated on: --
 * params: $parentId(int)
 * return: $locations(array)
 **/
function getLocationsByParent($parentId)
{
	$CI = &get_instance();
	$CI->db->select();
	$CI->db->where('parent_id', $parentId);
	$CI->db->order_by('location', 'ASC');
	$query = $CI->db->get('location');
	return $query->result_array();
}

/* GET ROLES of SALES ENGINEER,RSM,RBH,NSM,COUNTRY HEAD,SALES DIRECTOR,GLOBAL HEAD which are having product targets
** return: roles(array)
** author: mahesh created on: 30th june 3:12PM updated on:
*/
function get_productTargetRoles()
{

	return array(4, 5, 6, 7, 8, 9, 10, 11);
}

/**
 * get months
 * author: mahesh , created on: 30th june 2016 03:47 PM, updated on: --
 * params: --
 * return: $months(array)
 **/
function getMonths()
{
	$CI = &get_instance();
	$CI->db->select();
	$query = $CI->db->get('month');
	return $query->result_array();
}

function getProductsByGroup($productGroupId)
{

	$CI = &get_instance();
	$CI->db->select();
	$CI->db->where('group_id', $productGroupId);
	$CI->db->where('company_id', $CI->session->userdata('company'));
	$CI->db->where('status', 1);
	$CI->db->order_by('name', 'ASC');
	$query = $CI->db->get('product');
	return $query->result_array();
}

function getGroupsByCategory($productCategoryId)
{

	$CI = &get_instance();
	$CI->db->select();
	$CI->db->where('category_id', $productCategoryId);
	$CI->db->where('status', 1);
	$CI->db->order_by('name', 'ASC');
	$query = $CI->db->get('product_group');
	return $query->result_array();
}

function getCompetitorsByProductCategory($productCategoryId)
{

	$CI = &get_instance();
	$CI->db->select('c.*');
	$CI->db->from('product_category_competitor pcc');
	$CI->db->where('pcc.category_id', $productCategoryId);
	$CI->db->join('competitor c', 'c.competitor_id=pcc.competitor_id', 'inner');

	$CI->db->where('c.status', 1);
	$CI->db->where('c.company_id', $CI->session->userdata('company'));
	$CI->db->order_by('name', 'ASC');
	$query = $CI->db->get();

	return $query->result_array();
}

function getOpportunityCompetitors($opportunityId)
{

	$CI = &get_instance();
	$CI->db->from('opportunity_competitor');
	$CI->db->where('opportunity_id', $opportunityId);
	$query = $CI->db->get();
	return $query->result_array();
}

function getDecisionMakerDetails($contact_id)
{
	$CI = &get_instance();
	$CI->db->select('c.*,s.name as speciality');
	$CI->db->from('contact c');
	$CI->db->join('speciality s', 's.speciality_id=c.speciality_id');
	$CI->db->where('c.contact_id', $contact_id);
	$CI->db->where('s.company_id', $_SESSION['company']);
	$query = $CI->db->get();
	$row = $query->row_array();
	$details = $row['first_name'] . ' ' . $row['last_name'] . ' - ' . $row['speciality'] . ' (' . $row['mobile_no'] . ')';

	return $details;
}

/**
 * get location by id
 * author: mahesh , created on: 9th july 2016 10:21 PM, updated on: --
 * params: $locationId(int)
 * return: $location (array)
 **/
function getLocationById($locationId)
{
	$CI = &get_instance();
	$CI->db->where('location_id', $locationId);
	$query = $CI->db->get('location');
	return $query->row_array();
}
/**
 * Send email
 * 
 * 
 */
function send_email($to, $subject = "---", $body, $cc = NULL, $from = 'noreply@skanray-access.com', $from_name = 'Skanray ICRM', $bcc = NULL, $replyto = NULL,  $attachments = [])
{
	//return TRUE;
	$ci = &get_instance();
	$ci->load->helper('email');
	$ci->load->library('email');

	$config['protocol'] = 'smtp';
	$config['smtp_host'] = 'ssl://smtp.gmail.com';
	$config['smtp_port'] = '465';
	$config['smtp_timeout'] = '7';
	$config['smtp_user'] = 'webadmin@skanray-access.com';
	$config['smtp_pass'] = '123';
	/*$config['protocol'] = 'smtp';
    $config['smtp_host'] = 'ssl://smtp.gmail.com';
    $config['smtp_port'] = '465';
    $config['smtp_timeout'] = '7';
    $config['smtp_user'] = 'entransys.test@gmail.com';  
    $config['smtp_pass'] = 'test@2929';  */
	$config['charset'] = 'utf-8';
	$config['newline'] = "\r\n";
	$config['mailtype'] = 'html'; // or html
	$config['validation'] = TRUE; // bool whether to validate email or not      

	$ci->email->initialize($config);
	$email_object = $ci->email;

	$email_object->from($from, $from_name);
	$email_object->to($to);
	$email_object->cc($cc);
	// $email_object->cc("rajender.jakka@gmail.com");
	$email_object->subject($subject);
	$email_object->message($body);
	$email_object->bcc($bcc);
	$email_object->reply_to($replyto);
	if (count($attachments) > 0) {
		foreach ($attachments as $temp_name => $path) {
			$email_object->attach($path, 'attachment', $temp_name);
		}
	}
	$status = $email_object->send();

	return $status;

	//echo $ci->email->print_debugger();


	$email_object->clear(TRUE);
}


function send_email1($to, $cc, $subject = "---", $body, $from = 'noreply@skanray-access.com', $from_name = 'Skanray ICRM', $bcc = NULL, $replyto = NULL,  $attachments = [])
{
	//return TRUE;
	$ci = &get_instance();
	$ci->load->helper('email');
	$ci->load->library('email');

	$config['protocol'] = 'smtp';
	$config['smtp_host'] = 'ssl://smtp.gmail.com';
	$config['smtp_port'] = '465';
	$config['smtp_timeout'] = '7';
	$config['smtp_user'] = 'webadmin@skanray-access.com';
	$config['smtp_pass'] = '1234';
	/*$config['protocol'] = 'smtp';
    $config['smtp_host'] = 'ssl://smtp.gmail.com';
    $config['smtp_port'] = '465';
    $config['smtp_timeout'] = '7';
    $config['smtp_user'] = 'entransys.test@gmail.com';  
    $config['smtp_pass'] = 'test@2929';  */
	$config['charset'] = 'utf-8';
	$config['newline'] = "\r\n";
	$config['mailtype'] = 'html'; // or html
	$config['validation'] = TRUE; // bool whether to validate email or not      

	$ci->email->initialize($config);
	$email_object = $ci->email;

	$email_object->from($from, $from_name);
	$email_object->to($to);
	$email_object->cc($cc);
	// $email_object->cc("rajender.jakka@gmail.com");
	$email_object->subject($subject);
	$email_object->message($body);
	$email_object->bcc($bcc);
	$email_object->reply_to($replyto);
	if (count($attachments) > 0) {
		foreach ($attachments as $temp_name => $path) {
			$email_object->attach($path, 'attachment', $temp_name);
		}
	}
	$status = $email_object->send();

	return $status;

	//echo $ci->email->print_debugger();


	$email_object->clear(TRUE);
}

function date_difference($date1timestamp, $date2timestamp)
{
	$all = round(($date1timestamp - $date2timestamp) / 60);
	$d = floor($all / 1440);
	$h = floor(($all - $d * 1440) / 60);
	$m = $all - ($d * 1440) - ($h * 60);
	//Since you need just hours and mins
	return array('hours' => $h, 'mins' => $m);
}

/* GET Today assigned leads for logged in user
** return: lead_count(int)
** author: mahesh created on: 16th july 2016 1:50 PM updated on:
*/
function get_todayAssignedLeadsCount()
{
	$CI = &get_instance();
	$CI->db->select();
	$CI->db->where('user_id', $CI->session->userdata('user_id'));
	$where  = ' CASE WHEN (type=2 AND re_routed_time IS NULL) THEN (DATE(created_time) = "' . date('Y-m-d') . '" ) ELSE (DATE(re_routed_time) = "' . date('Y-m-d') . '") END ';
	$CI->db->where($where);
	$CI->db->where('company_id', $CI->session->userdata('company'));
	//$CI->db->where('DATE(re_routed_time)',date('Y-m-d'));
	$query = $CI->db->get('lead');
	//echo $CI->db->last_query(); exit;
	return $query->num_rows();
}

/* GET order conclusion expire
** return: count(int)
** author: mahesh created on: 16th july 2016 1:50 PM updated on:
*/
function editOrderConclusionDateCount()
{
	$CI = &get_instance();
	$CI->db->select('o.*');
	$CI->db->from('opportunity o');
	$CI->db->join('lead l', 'l.lead_id = o.lead_id', 'inner');
	$CI->db->where('l.user_id', $CI->session->userdata('user_id'));
	$CI->db->where('o.expected_order_conclusion<', date('Y-m-d'));
	$CI->db->where('o.status>=', 1);
	$CI->db->where('o.status<=', 5);
	$CI->db->where('o.company_id', $CI->session->userdata('company'));
	$query = $CI->db->get();
	//echo $CI->db->last_query();
	return $query->num_rows();
}

/* GET not updated demo count
** return: count(int)
** author: mahesh created on: 16th july 2016 1:50 PM updated on:
*/
function notUpdatedDemoCount()
{
	$CI = &get_instance();
	$CI->db->select('d.*');
	$CI->db->from('demo d');
	$CI->db->join('opportunity o', 'o.opportunity_id = d.opportunity_id', 'inner');
	$CI->db->join('lead l', 'l.lead_id = o.lead_id', 'inner');
	$CI->db->where('l.user_id', $CI->session->userdata('user_id'));
	$CI->db->where('d.end_date<', date('Y-m-d'));
	$CI->db->where('d.remarks2', NULL);
	$CI->db->where('o.company_id', $CI->session->userdata('company'));
	$query = $CI->db->get();
	//echo $CI->db->last_query();
	return $query->num_rows();
}

/* GET not updated VISIT count
** return: count(int)
** author: mahesh created on: 16th july 2016 1:50 PM updated on:
*/
function notUpdatedVisitCount()
{
	$CI = &get_instance();
	$CI->db->select('v.*');
	$CI->db->from('visit v');
	$CI->db->join('lead l', 'l.lead_id = v.lead_id', 'inner');
	$CI->db->where('l.user_id', $CI->session->userdata('user_id'));
	$CI->db->where('v.end_date<', date('Y-m-d'));
	$CI->db->where('v.remarks2', NULL);
	$CI->db->where('l.company_id', $CI->session->userdata('company'));
	$query = $CI->db->get();
	//echo $CI->db->last_query();
	return $query->num_rows();
}

//mahesh 16th july 2016 07:21 PM
function orderConclusionResultsByUser($user_id, $num_days = 1)
{
	$statusValues = '(1,2,3,4,5)';
	$CI = &get_instance();
	/*$role_id = $CI->session->userdata('role_id');
		$reportees = $CI->session->userdata('reportees');
		$reportees = ($role_id == 4 || $role_id == 5)?0:$reportees;*/
	// $CI->db->select('o.opportunity_id,o.expected_order_conclusion, l.lead_id as lead_id,  concat("ID : ", l.lead_id, " - ", c.name, " ", c.name1, " (", l1.location, ")") as lead, concat(p.name, " (", p.description, ")") as product');
	$CI->db->select('o.opportunity_id,o.opp_number,o.expected_order_conclusion, l.lead_id as lead_id,  concat("ID : ", l.lead_number, " - ", c.name, " ", c.name1, " (", l1.location, ")") as lead, 
			concat(p.name, " (", p.description, ")") as product');
	$CI->db->from('opportunity o');
	$CI->db->join('lead l', 'o.lead_id = l.lead_id');

	$CI->db->join('opportunity_product op', 'op.opportunity_id = o.opportunity_id');
	$CI->db->join('product p', 'p.product_id = op.product_id');
	$CI->db->join('opportunity_status os', 'os.status = o.status');

	$CI->db->join('customer c', 'c.customer_id = l.customer_id');
	$CI->db->join('contact cn', 'cn.contact_id = l.contact_id');
	$CI->db->join('location l1', 'l1.location_id = l.location_id');
	/*$CI->db->join('user u','u.user_id = l.user_id');
		
		$CI->db->join('role r','r.role_id = u.role_id');*/

	$CI->db->where('o.expected_order_conclusion<', date('Y-m-d'));
	if ($num_days > 0)
		$where = 'o.expected_order_conclusion = "' . date('Y-m-d') . '" - INTERVAL ' . $num_days . ' DAY ';
	$CI->db->where($where);
	$CI->db->where('l.user_id', $user_id);
	$CI->db->where('o.status IN ' . $statusValues);
	$CI->db->where_not_in('cn.status', '3');
	$CI->db->order_by('o.opportunity_id', 'DESC');
	$res = $CI->db->get();
	$data = $res->result_array();
	//echo $CI->db->last_query();
	return $data;
}

//mahesh 16th july 2016 07:26 PM
function getUserProductReporteesById($user_id)
{
	$CI = &get_instance();
	$ret = [];

	$userReportees = explode(",", $CI->session->userdata('reportees'));
	$products = explode(",", getUserProducts($user_id));
	//print_r($userReportees);
	for ($i = 0; $i < count($userReportees); $i++) {
		$userProduct = [];
		$user = $userReportees[$i];
		$q = 'SELECT p.product_id from product p
				INNER JOIN user_product up ON p.product_id = up.product_id
				where p.status = 1 and up.status = 1 and up.user_id = "' . $user . '"';
		$r = $CI->db->query($q);
		foreach ($r->result_array() as $row) {
			$userProduct[] = $row['product_id'];
		}
		if (count(array_intersect($userProduct, $products)) == count($userProduct)) {
			$ret[] = $user;
		}
	}
	$retu = implode(",", $ret);
	if ($retu == '') $retu = 0;
	return $retu;
}

//mahesh 16th july 2016 08:33 PM
function postDemoNotUpdatedResultsByUser($user_id, $num_days = 1)
{
	$CI = &get_instance();
	$CI->db->select('d.*, IF(d.end_date < CURDATE(), "1", "0") is_expired, CONCAT(c.name," ",c.name1," - ",c.department," (",loc.location,")") as CustomerName, CONCAT("ID - ", d.opportunity_id, ": ", p.name," (",p.description,")") as opportunity, CONCAT(dpd.serial_number," - ",dpd.location) as demo');
	$CI->db->from('demo d');
	$CI->db->where('l.user_id', $user_id);
	$CI->db->join('demo_product dp', 'dp.demo_product_id = d.demo_product_id AND dp.product_id = d.product_id');
	$CI->db->join('demo_product_details dpd', 'dpd.demo_product_id = dp.demo_product_id');
	$CI->db->join('product p', 'p.product_id = d.product_id');
	$CI->db->join('opportunity_product op', 'op.opportunity_id = d.opportunity_id AND op.product_id = d.product_id');
	$CI->db->join('opportunity o', 'o.opportunity_id = op.opportunity_id');
	$CI->db->join('lead l', 'l.lead_id = o.lead_id');
	$CI->db->join('customer c', 'c.customer_id = l.customer_id');
	$CI->db->join('customer_location_contact clc', 'clc.customer_id = c.customer_id AND clc.location_id = l.location_id AND clc.contact_id = l.contact_id');
	$CI->db->join('location loc', 'loc.location_id = clc.location_id');
	$CI->db->order_by('d.opportunity_id', 'DESC');
	$CI->db->where('d.end_date<', date('Y-m-d'));
	$CI->db->where('d.remarks2', NULL);
	if ($num_days > 0)
		$where = 'd.end_date = "' . date('Y-m-d') . '" - INTERVAL ' . $num_days . ' DAY ';
	$CI->db->where($where);
	$res = $CI->db->get();
	return $res->result_array();
}

//mahesh 16th july 2016 08:57 PM
function postVisitNotUpdatedResultsByUser($user_id, $num_days = 1)
{
	$CI = &get_instance();
	$CI->db->select('v.*, IF(v.end_date < CURDATE(), "1", "0") is_expired, CONCAT("Lead ID - ",v.lead_id," (",c.name," ",c.name1," - ",c.department," (",loc.location,"))") as CustomerName, vp.name as Purpose');
	$CI->db->from('visit v');
	$CI->db->where('l.user_id', $user_id);
	$CI->db->join('visit_purpose vp', 'v.purpose_id = vp.purpose_id');
	$CI->db->join('lead l', 'l.lead_id = v.lead_id');
	$CI->db->join('customer c', 'c.customer_id = l.customer_id');
	$CI->db->join('customer_location_contact clc', 'clc.customer_id = c.customer_id AND clc.location_id = l.location_id AND clc.contact_id = l.contact_id');
	$CI->db->join('location loc', 'loc.location_id = clc.location_id');
	$CI->db->where('v.end_date<', date('Y-m-d'));
	$CI->db->where('v.remarks2', NULL);
	if ($num_days > 0)
		$where = 'v.end_date = "' . date('Y-m-d') . '" - INTERVAL ' . $num_days . ' DAY ';
	$CI->db->where($where);
	$CI->db->order_by('v.lead_id', 'DESC');
	$res = $CI->db->get();
	return $res->result_array();
}

//mahesh 17th july 2016 01:31 PM
function email_getUserApprovedLeads($user_id)
{
	$CI = &get_instance();
	$CI->db->select('l.*,CONCAT(c.name," ",c.name1," (",l1.location,")") as customer');
	$CI->db->from('lead l');
	$CI->db->join('customer c', 'l.customer_id = c.customer_id', 'inner');
	$CI->db->join('customer_location cl', 'c.customer_id=cl.customer_id', 'inner');
	$CI->db->join('location l1', 'l1.location_id = cl.location_id', 'inner');
	$where = ' l.approved_time < NOW() AND l.approved_time > NOW() - INTERVAL 5 MINUTE ';
	$CI->db->where($where);
	$CI->db->where('l.user_id', $user_id);
	$query = $CI->db->get();
	return $query->result_array();
}

//mahesh 17th july 2016 03:01 PM
function email_getUserAssignedLeads($user_id)
{
	$CI = &get_instance();
	$CI->db->select('l.*,CONCAT(c.name," ",c.name1," (",l1.location,")") as customer');
	$CI->db->from('lead l');
	$CI->db->join('customer c', 'l.customer_id = c.customer_id', 'inner');
	$CI->db->join('customer_location cl', 'c.customer_id=cl.customer_id', 'inner');
	$CI->db->join('location l1', 'l1.location_id = cl.location_id', 'inner');
	//join assigned user
	//$CI->db->join('user u ','CASE WHEN (l.type=2 AND l.re_routed_time IS NULL) THEN (l.created_by  = u.user_id) ELSE (l.re_routed_by = u.user_id) END','inner');
	$where = ' (CASE WHEN (l.type=2 AND l.re_routed_time IS NULL) THEN (l.created_time ) ELSE (l.re_routed_time) END) < NOW() AND (CASE WHEN (l.type=2 AND l.re_routed_time IS NULL) THEN (l.created_time ) ELSE (l.re_routed_time) END) > NOW() - INTERVAL 15 MINUTE ';
	//$where = ' l.approved_time < NOW() AND l.approved_time > NOW() - INTERVAL 5 MINUTE ';
	$CI->db->where($where);
	$CI->db->where('l.user_id', $user_id);
	$qry = 'SELECT l.*,CONCAT(c.name," ",c.name1," (",l1.location,")") as customer, CONCAT(u.first_name," ",u.last_name," (",r.name,")") as assignedBy
			FROM lead l
			INNER JOIN customer c ON l.customer_id = c.customer_id
			INNER JOIN customer_location cl ON c.customer_id = cl.customer_id
			INNER JOIN location l1 ON l1.location_id = cl.location_id
			INNER JOIN user u ON CASE WHEN (l.type=2 AND l.re_routed_time IS NULL) THEN (l.created_by  = u.user_id) ELSE (l.re_routed_by = u.user_id) END 
			INNER JOIN role r ON u.role_id = r.role_id 
			WHERE (CASE WHEN (l.type=2 AND l.re_routed_time IS NULL) THEN (l.created_time ) ELSE (l.re_routed_time) END) < NOW() AND (CASE WHEN (l.type=2 AND l.re_routed_time IS NULL) THEN (l.created_time ) ELSE (l.re_routed_time) END) > NOW() - INTERVAL 5 MINUTE 
			AND l.user_id = ' . $user_id;
	$query = $CI->db->query($qry);
	//echo $CI->db->last_query();
	return $query->result_array();
}

//mahesh 3rd august 2016 02:57 pm
// Function to get the client IP address
function get_client_ip()
{
	$ipaddress = '';
	if (isset($_SERVER['HTTP_CLIENT_IP']))
		$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
	else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
		$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
	else if (isset($_SERVER['HTTP_X_FORWARDED']))
		$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
	else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
		$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
	else if (isset($_SERVER['HTTP_FORWARDED']))
		$ipaddress = $_SERVER['HTTP_FORWARDED'];
	else if (isset($_SERVER['REMOTE_ADDR']))
		$ipaddress = $_SERVER['REMOTE_ADDR'];
	else
		$ipaddress = 'UNKNOWN';
	return $ipaddress;
}

//mahesh 3rd august 2016 2:59 pm
function getOS($user_agent)
{

	$os_platform    =   "Unknown OS Platform";
	$os_array       =   array(
		'/windows nt 6.2/i'     =>  'Windows 8',
		'/windows nt 6.1/i'     =>  'Windows 7',
		'/windows nt 10.0/i'    =>  'Windows 10',
		'/windows nt 6.0/i'     =>  'Windows Vista',
		'/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
		'/windows nt 5.1/i'     =>  'Windows XP',
		'/windows xp/i'         =>  'Windows XP',
		'/windows nt 5.0/i'     =>  'Windows 2000',
		'/windows me/i'         =>  'Windows ME',
		'/win98/i'              =>  'Windows 98',
		'/win95/i'              =>  'Windows 95',
		'/win16/i'              =>  'Windows 3.11',
		'/macintosh|mac os x/i' =>  'Mac OS X',
		'/mac_powerpc/i'        =>  'Mac OS 9',
		'/linux/i'              =>  'Linux',
		'/ubuntu/i'             =>  'Ubuntu',
		'/iphone/i'             =>  'iPhone',
		'/ipod/i'               =>  'iPod',
		'/ipad/i'               =>  'iPad',
		'/android/i'            =>  'Android',
		'/blackberry/i'         =>  'BlackBerry',
		'/webos/i'              =>  'Mobile'
	);

	foreach ($os_array as $regex => $os_platform) {
		if (preg_match($regex, $user_agent)) {
			return $os_platform;
		}
	}
}

//mahesh 3rd august 2016 03:03 pm
function getBrowser($user_agent)
{

	$browser        =   "Unknown Browser";
	$browser_array  =   array(
		'/msie/i'       =>  'Internet Explorer',
		'/firefox/i'    =>  'Firefox',
		'/safari/i'     =>  'Safari',
		'/chrome/i'     =>  'Chrome',
		'/opera/i'      =>  'Opera',
		'/netscape/i'   =>  'Netscape',
		'/maxthon/i'    =>  'Maxthon',
		'/konqueror/i'  =>  'Konqueror',
		'/mobile/i'     =>  'Handheld Browser'
	);

	foreach ($browser_array as $regex => $browser) {
		if (preg_match($regex, $user_agent)) {
			return $browser;
		}
	}
}

//mahesh 3rd august 2016 03:44 pm
function update_userLastActive()
{
	$CI = &get_instance();
	//UPDATE USER LOG , mahesh 3rd august 2016 03:43 pm
	$log_qry = 'UPDATE user_logs SET last_active = "' . date('Y-m-d H:i:s') . '" WHERE user_id = ' . $CI->session->userdata('user_id') . ' ORDER BY log_id DESC LIMIT 1';
	$CI->db->query($log_qry);
}

// get opporunity closed time
function get_opportunity_closed_data($opportunity_id)
{
	if ($opportunity_id != '') {
		$ci = &get_instance();
		$ci->db->from('opportunity_status_history');
		$ci->db->where('opportunity_id', $opportunity_id);
		$ci->db->order_by('opportunity_status_id', 'DESC');
		$ci->db->limit(1);
		$res = $ci->db->get();
		if ($res->num_rows() > 0) {
			$row = $res->row_array();
			return $row;
		}
	}
}

// get lead, opportunity, quote details by contract note id
function get_details_by_cnoteId($contract_note_id)
{
	if ($contract_note_id != '') {
		$ci = &get_instance();
		$ci->db->select('l.lead_id,l.status as lead_status,o.opportunity_id, o.status as opportunity_status, q.quote_id, q.status as quote_status');
		$ci->db->from('contract_note cn ');
		$ci->db->join('contract_note_quote_revision cnqr', 'cn.contract_note_id = cnqr.contract_note_id');
		$ci->db->join('quote_revision qr', 'qr.quote_revision_id = cnqr.quote_revision_id');
		$ci->db->join('quote q', 'qr.quote_id = q.quote_id');
		$ci->db->join('quote_details qd', 'qd.quote_id = q.quote_id');
		$ci->db->join('opportunity o', 'o.opportunity_id = qd.opportunity_id');
		$ci->db->join('lead l', 'l.lead_id = o.lead_id');
		$ci->db->where('cn.contract_note_id', $contract_note_id);
		$res1 = $ci->db->get();
		/*$row = $res1->result_array();
		echo $ci->db->database.'<br>';
		echo $ci->db->last_query().'<br>';
		print_r($row); exit;*/
		if ($res1->num_rows() > 0) {

			$row = $res1->result_array();
			return $row;
		}
	}
}

// get lead, opportunity, quote details by contract note id
function get_cnoteCountByLeadId($lead_id)
{
	if ($lead_id != '') {
		$ci = &get_instance();
		$ci->db->from('contract_note cn ');
		$ci->db->join('contract_note_quote_revision cnqr', 'cn.contract_note_id = cnqr.contract_note_id');
		$ci->db->join('quote_revision qr', 'qr.quote_revision_id = cnqr.quote_revision_id');
		$ci->db->join('quote q', 'qr.quote_id = q.quote_id');
		$ci->db->join('quote_details qd', 'qd.quote_id = q.quote_id');
		$ci->db->join('opportunity o', 'o.opportunity_id = qd.opportunity_id');
		$ci->db->join('lead l', 'l.lead_id = o.lead_id');
		$ci->db->where('l.lead_id', $lead_id);
		$res = $ci->db->get();
		return ($res->num_rows() > 0) ? $res->num_rows() : 0;
	}
	return 0;
}

function format_date($date, $format = 'd-m-Y')
{
	$timestamp = strtotime($date);
	return date($format, $timestamp);
}

function gst_date()
{
	return '01-07-2017';
}

function tax_type($date)
{
	return (strtotime($date) < strtotime(gst_date())) ? 1 : 2; // 1 : ED&VAT  , 2: After GST
}

// Phase2 update: 31st July 2017
function get_contract_user_name($user_id)
{
	$ret = '';
	if ($user_id != '') {
		$CI = &get_instance();
		$q = 'SELECT concat(c.first_name, " ", c.last_name) as name,s.name as speciality from contact c
				INNER JOIN speciality s ON s.speciality_id = c.speciality_id 
				WHERE c.contact_id = "' . $user_id . '"';
		$r = $CI->db->query($q);
		if ($r->num_rows() > 0) {
			$data = $r->row_array();
			$ret = $data;
		}
	}
	return $ret;
}

function getWeeks($date, $rollover = 'sunday')
{
	$cut = substr($date, 0, 8);
	$daylen = 86400;

	$timestamp = strtotime($date);
	$first = strtotime($cut . "00");
	$elapsed = ($timestamp - $first) / $daylen;

	$weeks = 1;

	for ($i = 1; $i <= $elapsed; $i++) {
		$dayfind = $cut . (strlen($i) < 2 ? '0' . $i : $i);
		$daytimestamp = strtotime($dayfind);

		$day = strtolower(date("l", $daytimestamp));

		if ($day == strtolower($rollover))  $weeks++;
	}

	return $weeks;
}

function quote_new_format_date()
{
	return '26-11-2017';
}


function quote_format_type($quote_time)
{
	if (strtotime($quote_time) < strtotime(quote_new_format_date())) {
		return 1; // Old Format
	} elseif (strtotime($quote_time) >= strtotime(quote_latest_format_date())) {
		return 3; // Format 3
	} else {
		return 2; // Format 2
	}
	//return (strtotime($quote_time)<strtotime(quote_new_format_date()))?1:2; // 1 : Old Format  , 2: New Quote Format
}

function get_advance_types($code = '')
{
	if ($code != '') {
		$cur = $code;
	} else {
		$cur = 'Rs';
	}
	return array(1 => '%', 2 => $cur);
}

function format_advance($advance, $advance_type = 1)
{
	if ($advance > 0) {
		$advance = round($advance, 2);
		switch ($advance_type) {
			case 1:
				return $advance . '%';
				break;
			case 2:
				return 'Rs ' . indian_format_price($advance);
				break;
		}
	}
}

function getQuoteOpportunities($quoteID)
{
	if ($quoteID) {
		$ci = &get_instance();
		// $ci->db->select('p.name as product_name,p.description,p.product_id,qd.opportunity_id,o.required_quantity,p.sub_category_id,qd.mrp,p.dp');
		// Added on 18-06-2021 for warranty field in product master
		$ci->db->select('p.name as product_name,p.description,p.product_id,qd.opportunity_id,o.required_quantity,p.sub_category_id,qd.mrp,p.dp,p.warranty');
		// Added on 18-06-2021 for warranty field in product master end
		$ci->db->from('quote_details qd');
		$ci->db->join('opportunity o', 'o.opportunity_id=qd.opportunity_id');
		$ci->db->join('opportunity_product op', 'o.opportunity_id=op.opportunity_id');
		$ci->db->join('product p', 'p.product_id = op.product_id');
		$ci->db->where('qd.quote_id', $quoteID);
		$res = $ci->db->get();
		if ($res->num_rows() > 0) {
			return $res->result_array();
		}
	}
}

function getNextApprovalRole($role_id)
{
	if ($role_id > 0) {
		$ci = &get_instance();
		$ci->db->from('quote_approval_hirarchy');
		$ci->db->select('parent_role_id');
		$ci->db->where('role_id', $role_id);
		$res = $ci->db->get();
		if ($res->num_rows() > 0) {
			$row = $res->row_array();
			return $row['parent_role_id'];
		}
	}
}

function getApprovalRole($margin_approval_id)
{
	if ($margin_approval_id > 0) {
		$ci = &get_instance();
		$ci->db->from('quote_op_margin_approval_history');
		$ci->db->select('approved_by');
		$ci->db->order_by('approval_hist_id', 'DESC');
		$res = $ci->db->get();
		if ($res->num_rows() > 0) {
			$row = $res->row_array();
			return $row['approved_by'];
		}
	}
}

function getMarginAnalysisOpportunityCountByQuoteRevision($quote_revision_id)
{
	if ($quote_revision_id > 0) {
		$ci = &get_instance();
		$ci->db->from('quote_op_margin_approval');
		$ci->db->where('quote_revision_id', $quote_revision_id);
		$res = $ci->db->get();
		return $res->num_rows();
	}
}

function getApprovedMarginAnalysisOpportunityCountByQuoteRevision($quote_revision_id)
{
	if ($quote_revision_id > 0) {
		$ci = &get_instance();
		$ci->db->from('quote_op_margin_approval');
		$ci->db->where('quote_revision_id', $quote_revision_id);
		$ci->db->where('status', 2);
		$res = $ci->db->get();
		return $res->num_rows();
	}
}

function getCompletedMarginAnalysisOpportunityCountByQuoteRevision($quote_revision_id)
{
	if ($quote_revision_id > 0) {
		$ci = &get_instance();
		$ci->db->from('quote_op_margin_approval');
		$ci->db->where('quote_revision_id', $quote_revision_id);
		$ci->db->where('status not in (1)');
		$res = $ci->db->get();
		return $res->num_rows();
	}
}

function getRoleShortName($role_id)
{
	$short_name = '';
	switch ($role_id) {
		case 7:
			$short_name = 'RBH';
			break;
		case 8:
			$short_name = 'NSM';
			break;
		case 9:
			$short_name = 'CH';
			break;
	}
	return $short_name;
}

function getMarginAnalysisApprovalHistory($margin_approval_id)
{
	if ($margin_approval_id > 0) {
		$ci = &get_instance();
		$ci->db->select('ma.*,u.employee_id,CONCAT(u.first_name," ",u.last_name) as user');
		$ci->db->from('quote_op_margin_approval_history ma');
		$ci->db->join('user u', 'u.user_id = ma.created_by');
		$ci->db->where('ma.margin_approval_id', $margin_approval_id);
		$res = $ci->db->get();
		if ($res->num_rows() > 0) {
			return $res->result_array();
		}
	}
}



function getOpportunityDetails($opportunity_id)
{
	if ($opportunity_id > 0) {
		$ci = &get_instance();
		$ci->db->select('concat("ID - ", o.opp_number, " : ", p.name, " - ", p.description, " (Qty -", o.required_quantity, ")") 
                as opportunity');
		$ci->db->from('opportunity o');
		$ci->db->join('opportunity_product op', 'o.opportunity_id = op.opportunity_id');
		$ci->db->join('product p', 'op.product_id = p.product_id');
		$ci->db->where('o.opportunity_id', $opportunity_id);
		$res = $ci->db->get();
		if ($res->num_rows() > 0) {
			$row = $res->row_array();
			return $row['opportunity'];
		}
	}
}

function getMarginAnalysisCcUsersList($role_id, $margin_approval_id)
{
	if ($role_id != '' && $margin_approval_id != '') {
		$ci = &get_instance();
		$ci->db->select('u.*');
		$ci->db->from('quote_op_margin_approval_history ma');
		$ci->db->join('user u', 'u.user_id = ma.created_by');
		$ci->db->where('ma.approved_by !=', $role_id, FALSE);
		$ci->db->where('ma.margin_approval_id', $margin_approval_id);
		$ci->db->where('u.status', 1);
		$res = $ci->db->get();
		if ($res->num_rows() > 0) {
			return $res->result_array();
		}
	}
}

function getMarginAnalysisCcUsersListFinalApprove($margin_approval_id)
{
	if ($margin_approval_id != '') {
		$ci = &get_instance();
		$ci->db->select('u.*');
		$ci->db->from('quote_op_margin_approval_history ma');
		$ci->db->join('user u', 'u.user_id = ma.created_by');
		$ci->db->where_in('ma.approved_by', array(7, 8, 9));
		$ci->db->where('ma.margin_approval_id', $margin_approval_id);
		$ci->db->where('u.status', 1);
		$res = $ci->db->get();
		// echo $ci->db->last_query();die;
		if ($res->num_rows() > 0) {
			return $res->result_array();
		}
	}
}

/*function getDpQuoteRevisionPrice($quote_revision_id)
{
	$cost = 0;
	$CI = & get_instance();
	$q = 'SELECT (p.dp*o.required_quantity) as cost, CASE WHEN ma.discount_type=1 then round((p.dp*o.required_quantity)*(1-ma.discount/100)) else round((p.dp*o.required_quantity)-ma.discount) end as quote_price
		from quote_op_margin_approval ma
		INNER JOIN opportunity o ON o.opportunity_id = ma.opportunity_id
		INNER JOIN quote_revision qr ON qr.quote_revision_id = ma.quote_revision_id
		INNER JOIN quote_details qd ON qd.quote_id = qr.quote_id AND o.opportunity_id = qd.opportunity_id
		INNER JOIN  opportunity_product op ON op.opportunity_id = o.opportunity_id
		INNER JOIN product p ON p.product_id = op.product_id
		where ma.quote_revision_id = "'.$quote_revision_id.'"';
	$r = $CI->db->query($q);
	//echo $CI->db->last_query();
	if($r->num_rows() > 0)
	{
		$results = $r->result_array();
		$cost = 0; $quote_price = 0;
		foreach ($results as $row) {
			$cost += $row['cost'];
			$quote_price += $row['quote_price'];
		}
		$data = array('cost'=>$cost,'quote_price'=>$quote_price);
		return $data;
	}
}*/

function getQuoteRevisionPrice($quote_revision_id)
{
	$cost = 0;
	$CI = &get_instance();
	$q = 'SELECT (qd.mrp*o.required_quantity) as cost, CASE WHEN ma.discount_type=1 then round((qd.mrp*o.required_quantity)*(1-ma.discount/100)) else round((qd.mrp*o.required_quantity)-ma.discount) end as quote_price
		from quote_op_margin_approval ma
		INNER JOIN opportunity o ON o.opportunity_id = ma.opportunity_id
		INNER JOIN quote_revision qr ON qr.quote_revision_id = ma.quote_revision_id
		INNER JOIN quote_details qd ON qd.quote_id = qr.quote_id AND o.opportunity_id = qd.opportunity_id
		where ma.quote_revision_id = "' . $quote_revision_id . '"';
	$r = $CI->db->query($q);
	if ($r->num_rows() > 0) {
		$results = $r->result_array();
		$cost = 0;
		$quote_price = 0;
		foreach ($results as $row) {
			$cost += $row['cost'];
			$quote_price += $row['quote_price'];
		}
		$data = array('cost' => $cost, 'quote_price' => $quote_price);
		return $data;
	}
}

function getCNoteStatus($status)
{
	$ret = '';
	switch ($status) {
		case 1:
			$ret = 'Waiting at SO Entry';
			break;
		case 2:
			$ret = 'Completed';
			break;
		case 3:
			$ret = 'Waiting for invoice clearance';
			break;
		case 4:
			$ret = 'Rejected By RBH';
			break;
		case 5:
			$ret = 'Waiting for Customer Approval';
			break;
		case 6:
			$ret = 'Rejected By Customer';
			break;
	}
	return $ret;
}

function valueInLakhs($value, $display_type = 1)
{
	if ($value != '') {

		$lakhs = round(($value / 100000), 2);
		$lakhs = number_format($lakhs, 2, '.', '');
		$output = $lakhs;
		switch ($display_type) {
			case 1:
				$output = $lakhs . ' L';
				break;
			case 2:
				$output = $lakhs;
				break;
			case 1:
				$output = $lakhs . ' Lakhs';
				break;
		}
		return $output;
	}
}

function taggedOpportunityStatus($status)
{
	$ret = '';
	switch ($status) {
		case 6:
			$ret = 'Closed Won';
			break;
		case 7:
			$ret = 'Closed Lost';
			break;
		case 10:
			$ret = 'Tagged';
			break;
	}
	return $ret;
}

function getOpportunityMarginAnalysisStatus($quote_revision_id)
{
	if ($quote_revision_id != '') {
		$ci = &get_instance();
		$ci->db->select('CONCAT("ID - ",o.opportunity_id," : ",p.name," - ",p.description,"(Qty - ",o.required_quantity,")") as opportunity, ma.*');
		$ci->db->from('quote_op_margin_approval ma');
		$ci->db->join('opportunity o', 'o.opportunity_id = ma.opportunity_id');
		$ci->db->join('opportunity_product op', 'o.opportunity_id = op.opportunity_id');
		$ci->db->join('product p', 'p.product_id = op.product_id');
		$ci->db->where('ma.quote_revision_id', $quote_revision_id);
		$res = $ci->db->get();
		//echo $ci->db->last_query();
		if ($res->num_rows() > 0) {
			return $res->result_array();
		}
	}
}

function getMarginAnalysisStatus($status)
{
	$ret = '';
	switch ($status) {
		case 1:
			$ret = 'Waiting for Approval';
			break;
		case 2:
			$ret = 'Approved';
			break;
		case 3:
			$ret = 'Rejected';
			break;
	}
	return $ret;
}

//// Opportunity Status history: getting previous_status for opportunity created by suresh on 4th May 2017
function getPreviousStage($opportunity_id, $search_date)
{
	if ($opportunity_id != '' && $search_date != '') {
		$ci = &get_instance();
		$ci->db->from('opportunity_status_history osh');
		$ci->db->join('opportunity_status os', 'osh.status = os.status');
		$ci->db->where('osh.opportunity_id', $opportunity_id);
		$ci->db->where('DATE(osh.created_time)<', $search_date);
		$res = $ci->db->get();
		if ($res->num_rows() > 0) {
			$row = $res->row_array();
			return $row['name'];
		}

		/*$q3 = 'SELECT name as previous_status FROM opportunity_status os inner join opportunity_status_history osh ON osh.status = os.status INNER JOIN opportunity o ON o.opportunity_id = osh.opportunity_id WHERE osh.created_time = "'.$previous_status_date.'" AND o.opportunity_id = "'.$opportunity_id.'"';
		$r3 = $CI->db->query($q3);
	    $previous_status = $r3->row()->previous_status;*/
	}

	//return $previous_status;
}

function get_string_between($string, $start, $end)
{
	$string = ' ' . $string;
	$ini = strpos($string, $start);
	if ($ini == 0) return '';
	$ini += strlen($start);
	$len = strpos($string, $end, $ini) - $ini;
	return substr($string, $ini, $len);
}

function getOpportunityDiscount($quote_revision_id, $opportunity_id)
{
	if ($quote_revision_id != '' && $opportunity_id != '') {
		$ci = &get_instance();
		$ci->db->select('ma.discount,ma.discount_type');
		$ci->db->from('quote_op_margin_approval ma');
		$ci->db->where('ma.quote_revision_id', $quote_revision_id);
		$ci->db->where('ma.opportunity_id', $opportunity_id);
		$res = $ci->db->get();
		if ($res->num_rows() > 0) {
			return $res->row_array();
		}
	}
	return 0;
}

function getQuoteRevisionTime($quote_revision_id)
{
	if ($quote_revision_id != '') {
		$ci = &get_instance();
		$ci->db->select('created_time');
		$ci->db->from('quote_revision');
		$ci->db->where('quote_revision_id', $quote_revision_id);
		$res = $ci->db->get();
		if ($res->num_rows() > 0) {
			$row = $res->row_array();
			return $row['created_time'];
		}
	}
}

function getQuoteApprovalCount()
{
	$ci = &get_instance();
	$role_id = $ci->session->userdata('role_id');
	$allowed_roles = array(7, 8, 9);

	// added on 10-05-2021 for getting location id
	$ci->db->select('ul.location_id');
	$ci->db->from('user_location ul');
	$ci->db->where('ul.user_id', $ci->session->userdata('user_id'));
	$res_l = $ci->db->get();
	$location_id = $res_l->row_array();
	// echo "<pre>";print_r($location_id);//die;
	// end


	if (in_array($role_id, $allowed_roles)) {
		$ci->db->select('count(*) as cnt');
		$ci->db->from('quote_op_margin_approval ma');
		$ci->db->join('quote_revision qr', 'qr.quote_revision_id = ma.quote_revision_id AND qr.status = 3');
		$ci->db->join('opportunity o', 'o.opportunity_id = ma.opportunity_id', 'inner');
		$ci->db->join('opportunity_product op', 'o.opportunity_id = op.opportunity_id', 'inner');
		$ci->db->join('lead l', 'l.lead_id = o.lead_id', 'inner');
		$ci->db->join('customer c', 'c.customer_id = l.customer_id', 'inner');
		$ci->db->join('customer_location cl', 'c.customer_id = cl.customer_id', 'inner');
		// $ci->db->where('cl.location_id IN ('.$ci->session->userdata('locationString').')');

		// added on 10-05-2021 for getting particular location of the user
		if ($role_id == 7) {
			$ci->db->join('location l1', 'l1.location_id = l.location_id', 'inner');
			$ci->db->join('location l2', 'l2.location_id = l1.parent_id', 'inner');
			$ci->db->join('location l3', 'l3.location_id = l2.parent_id', 'inner');
			$ci->db->join('location l4', 'l4.location_id = l3.parent_id', 'inner');
			if ($location_id != '') {
				$ci->db->where('l4.location_id IN (' . $location_id['location_id'] . ')');
			} else {
				$ci->db->where('cl.location_id IN (' . $ci->session->userdata('locationString') . ')');
			}
		} else {
			$ci->db->where('cl.location_id IN (' . $ci->session->userdata('locationString') . ')');
		}
		// end

		$ci->db->where('op.product_id IN (' . $ci->session->userdata('products') . ')');
		//$ci->db->where('l.user_id IN ('.$ci->session->userdata('reportees').','.$ci->session->userdata('user_id').')');
		$ci->db->where('o.company_id', $ci->session->userdata('company'));
		$ci->db->where('ma.approval_at', $role_id);
		$ci->db->where('ma.status', 1);
		$ci->db->where('qr.status', 3);
		$res = $ci->db->get();
		// echo "<pre>";print_r($ci->db->last_query());
		if ($res->num_rows() > 0) {
			$row = $res->row_array();
			return ($row['cnt'] > 0) ? $row['cnt'] : 0;
		}
	}
	return 0;
}
// 
function getLeadDetailsByQuote($quote_id)
{
	if ($quote_id > 0) {
		$ci = &get_instance();
		$ci->db->select('l.lead_id,l.user_id,u.role_id,l.user2,l.status');
		$ci->db->from('quote_details qd');
		$ci->db->join('opportunity o', 'qd.opportunity_id = o.opportunity_id');
		$ci->db->join('lead l', 'o.lead_id = l.lead_id');
		$ci->db->join('user u', 'l.user_id = u.user_id');
		$ci->db->where('qd.quote_id', $quote_id);
		$res = $ci->db->get();
		if ($res->num_rows() > 0) {
			return $res->row_array();
		}
	}
}

function marginAnalysis($data)
{
	if (is_array($data)) {
		$default_warranty = getDefaultWarranty();
		$warranty_in_months = $data['total_warranty_in_years'] * 12;

		$ma_data = $data;
		// print_r($ma_data);die;
		$dp = @$ma_data['dp'] + @$ma_data['cost_of_free_supply'];
		$a = $ma_data['order_value'] = round($ma_data['order_value']);
		$b = $ma_data['net_selling_price'];
		$c = $ma_data['basic_price'];
		$d = $ma_data['gross_margin']	=	$b - $c;
		if ($b != 0) {
			$e = $ma_data['gross_margin_percentage']	=	round((@$d / @$b) * 100, 2);
		} else {
			$e = $ma_data['gross_margin_percentage']	=	0;
		}
		$f = $ma_data['total_warranty_in_years'];
		$g = $ma_data['advance'];
		$h = $ma_data['balance_payment_days'];
		$i = @$ma_data['free_supply'];
		$j = $ma_data['dealer_commission'];
		$k = $ma_data['cost_of_maintaining_warranty'] = get_preference('cost_of_maintaining_warranty', 'margin_settings');
		$l = $ma_data['cost_of_capital'] = get_preference('cost_of_capital', 'margin_settings');
		// $m = $ma_data['cost_of_warranty'] = ($warranty_in_months!=$default_warranty)?round(($dp*pow((1+$k/100),($f-1)))-$dp):0;
		if (isset($ma_data['cost_of_warranty1'])) {
			$m = $ma_data['cost_of_warranty'] = $ma_data['cost_of_warranty1'];
		} else {
			$m = $ma_data['cost_of_warranty'] = ($warranty_in_months != $default_warranty) ? round(($dp * pow((1 + $k / 100), ($f - 1))) - $dp) : 0;
		}
		$n = $ma_data['cost_of_finance'] = round($a * (1 - $g / 100) * (($l / 100) / 365) * ($h - 30));
		if ($n <= 0)
			$n = $ma_data['cost_of_finance'] = 0;
		// $o = $ma_data['cost_of_commission'] = round(($j*$b)/100);
		if (isset($ma_data['cost_of_warranty1'])) {
			$o = $ma_data['cost_of_commission'] = $ma_data['cost_of_commission1'];
		} else {
			$o = $ma_data['cost_of_commission'] = round(($j * $b) / 100);
		}

		$p = $ma_data['cost_of_free_supply'];
		$q = $ma_data['net_margin'] = (@$data['exclude_extra_warranty_in_nm'] == 1) ? ($d - $n - $o - $p) : ($d - $m - $n - $o - $p);
		if ($b != 0) {
			$r = $ma_data['net_margin_percentage'] = round(($q / $b) * 100, 2);
		} else {
			$r = $ma_data['net_margin_percentage'] = 0;
		}
		if (isset($ma_data['orc'])) {
			$s = $ma_data['orc'];
		} else {
			$s = $ma_data['orc'] = 0;
		}
		return $ma_data;
	}
}

function getQuoteOppFreeSupplies($quote_revision_id, $opportunity_id)
{
	if ($quote_revision_id != '' && $opportunity_id != '') {
		$ci = &get_instance();
		$ci->db->select('fs.*,p.name,p.description');
		$ci->db->from('quote_opp_free_supply fs');
		$ci->db->join('product p', 'fs.product_id = p.product_id');
		$ci->db->where('fs.quote_revision_id', $quote_revision_id);
		$ci->db->where('fs.opportunity_id', $opportunity_id);
		$res = $ci->db->get();
		if ($res->num_rows() > 0) {
			return $res->result_array();
		}
	}
}

/*
 * To insert / update into database
 * params: name(string),value(int),section(string)
 * return
 * created by  mahesh on 24th feb 2017
*/
function set_preference($name, $value, $section)
{
	$ci = &get_instance();
	$ci->load->database();
	$ci->db->where('section', $section);
	$ci->db->where('name', $name);
	$ci->db->from('preference');
	$query = $ci->db->get();
	$num = $query->num_rows();

	if ($num > 0) {
		$data = array(
			'value'	=>  $value,
			'modified_by' => $ci->session->userdata('user_id'),
			'modified_time' => date('Y-m-d H:i:s')
		);
		$where = array(
			'section' => $section,
			'name' => $name,
			'company_id' =>	$ci->session->userdata('company')
		);

		$res = $ci->db->update('preference', $data, $where);
	} else {
		$data = array(
			'name' => $name,
			'value' => $value,
			'section' => $section,
			'company_id' =>	$ci->session->userdata('company'),
			'created_by' => $ci->session->userdata('user_id'),
			'created_time' => date('Y-m-d H:i:s')
		);
		$res = $ci->db->insert('preference', $data);
	}
	return $res;
}
/*
 * To get data from database
 * params: name(string),section(string)
 * return: value(int)
 * created by mahesh on 24th feb 2017
*/

function get_preference($name, $section)
{

	$ci = &get_instance();
	$ci->load->database();
	$ci->db->where('section', $section);
	$ci->db->where('name', $name);
	$ci->db->where('company_id', $ci->session->userdata('company'));
	$ci->db->from('preference');
	$query = $ci->db->get();
	$num = $query->num_rows();

	if ($num > 0) {
		$value = $query->row_array();
		return $value['value'];
	}
}

function displayRangeLable($data)
{
	$range = '';
	if ($data['lower_limit'] !== NULL && $data['upper_limit'] !== NULL) {
		if ($data['lower_check'] == 1) {
			if ($data['upper_check'] == 1) {
				$range = $data['lower_limit'] . ' - ' . $data['upper_limit'];
			} else {
				$range = $data['lower_limit'] . ' - ' . ' <' . $data['upper_limit'];
			}
		} else {
			if ($data['upper_check'] == 1) {
				$range = '> ' . $data['lower_limit'] . ' - ' . ' <= ' . $data['upper_limit'];
			} else {
				$range = '> ' . $data['lower_limit'] . ' - ' . ' < ' . $data['upper_limit'];
			}
		}
	} else {
		if ($data['lower_limit'] === NULL) {
			if ($data['upper_check'] == 1) {
				$range = '<= ' . $data['upper_limit'];
			} else {
				$range = '< ' . $data['upper_limit'];
			}
		} else {
			if ($data['lower_check'] == 1) {
				$range = '>= ' . $data['lower_limit'];
			} else {
				$range = '> ' . $data['lower_limit'];
			}
		}
	}
	return $range;
}

function getCNoteClearForInvoiceCount()
{
	$ci = &get_instance();
	$role_id = $ci->session->userdata('role_id');
	if ($role_id == 7) {
		$ci->db->from('contract_note c');
		$ci->db->where('c.status', 3);
		$ci->db->where('c.created_by IN (' . $ci->session->userdata('reportees') . ',' . $ci->session->userdata('user_id') . ')');
		$ci->db->where('c.company_id', $ci->session->userdata('company'));
		$res = $ci->db->get();
		//echo $ci->db->last_query(); exit;
		return $res->num_rows();
	} else if ($role_id == 8) {
		$locations_without_rbh = getRegionsWithoutRbh();
		if ($locations_without_rbh != '') {
			$ci->db->from('contract_note c');
			$ci->db->where('c.status', 3);
			$ci->db->where('c.created_by IN (' . $ci->session->userdata('reportees') . ')');
			$ci->db->where('c.company_id', $ci->session->userdata('company'));
			$ci->db->join('user_location ul', 'ul.user_id = c.created_by', 'left');
			$ci->db->join('location l1', 'l1.location_id = ul.location_id', 'left');
			$ci->db->join('location l2', 'l2.location_id = l1.parent_id', 'left');
			$ci->db->join('location l3', 'l3.location_id = l2.parent_id', 'left');
			$ci->db->where('CASE WHEN l1.territory_level_id = 7 THEN l3.parent_id WHEN l1.territory_level_id = 6 THEN l2.parent_id
			WHEN l1.territory_level_id = 5 THEN l1.parent_id WHEN l1.territory_level_id = 4 THEN l1.location_id END   in (' . $locations_without_rbh . ')');
			$ci->db->group_by('c.contract_note_id');
			$res = $ci->db->get();
			//echo $ci->db->last_query(); exit;
			return $res->num_rows();
		}
	}
	return 0;
}

function getLocationsWithoutRbh()
{
	$ci = &get_instance();
	$qry = 'SELECT group_concat(distinct(l4.location_id)) as locations
			from location l
			join location l2 on l2.parent_id = l.location_id
			join location l3 on l3.parent_id = l2.location_id
			join location l4 on l4.parent_id = l3.location_id
			where l.territory_level_id = 4 and l.status = 1 and l.location_id not in (
			select ul.location_id from user_location ul join user u on u.user_id = ul.user_id 
			where u.status =  1 and ul.status = 1 and u.role_id = 7
			)';
	$res = $ci->db->query($qry);
	if ($res->num_rows() > 0) {
		$row = $res->row_array();
		return ($row['locations'] != '') ? $row['locations'] : 0;
	}
	return 0;
}

function getRegionsWithoutRbh()
{
	$ci = &get_instance();
	$qry = 'SELECT group_concat(distinct(l.location_id)) as regions
			from location l
			where l.territory_level_id = 4 and l.status = 1 and l.location_id not in (
			select ul.location_id from user_location ul join user u on u.user_id = ul.user_id 
			where u.status =  1 and ul.status = 1 and u.role_id = 7
			)';
	$res = $ci->db->query($qry);
	if ($res->num_rows() > 0) {
		$row = $res->row_array();
		return ($row['regions'] != '') ? $row['regions'] : 0;
	}
	return 0;
}
function getQuoteOppPriceDetails($quote_revision_id, $opportunity_id)
{
	if ($quote_revision_id != '' && $opportunity_id != '') {
		$ci = &get_instance();
		$ci->db->select('(o.required_quantity*qd.mrp) as mrp, (o.required_quantity*p.dp) as dp, p.dp as unit_dp, (o.required_quantity*p.base_price) as base_price, o.required_quantity,qd.freight_insurance,qd.gst');
		$ci->db->from('quote_revision qr');
		$ci->db->join('quote_details qd', 'qd.quote_id = qr.quote_id');
		$ci->db->join('opportunity o', 'o.opportunity_id = qd.opportunity_id');
		$ci->db->join('opportunity_product op', 'op.opportunity_id = o.opportunity_id');
		$ci->db->join('product p', 'p.product_id = op.product_id');
		$ci->db->where('qr.quote_revision_id', $quote_revision_id);
		$ci->db->where('o.opportunity_id', $opportunity_id);
		$res = $ci->db->get();
		if ($res->num_rows() > 0) {
			return $res->row_array();
		}
	}
}

// Checks whether value is passing conditions mentioned in $data
function check_range($data, $value)
{
	if ($data['lower_limit'] !== NULL && $data['upper_limit'] !== NULL) {
		if ($data['lower_check'] == 1) {
			if ($data['upper_check'] == 1) {
				$range = ($data['lower_limit'] <= $value && $data['upper_limit'] >= $value);
			} else {
				$range = ($data['lower_limit'] <= $value && $data['upper_limit'] > $value);
			}
		} else {
			if ($data['upper_check'] == 1) {
				$range = ($data['lower_limit'] < $value && $data['upper_limit'] >= $value);
			} else {
				$range = ($data['lower_limit'] < $value && $data['upper_limit'] > $value);
			}
		}
	} else {
		if ($data['lower_limit'] === NULL) {
			if ($data['upper_check'] == 1) {
				$range = ($data['upper_limit'] >= $value);
			} else {
				$range = ($data['upper_limit'] > $value);
			}
		} else {
			if ($data['lower_check'] == 1) {
				$range = ($data['lower_limit'] <= $value);
			} else {
				$range = ($data['lower_limit'] < $value);
			}
		}
	}
	return $range;
}

function checkMarginBand($mb_row)
{
	$gm_data = array();
	$nm_data = array();
	$gm_data['lower_limit'] = $mb_row['gm_lower_limit'];
	$gm_data['lower_check'] = $mb_row['gm_lower_check'];
	$gm_data['upper_limit'] = $mb_row['gm_upper_limit'];
	$gm_data['upper_check'] = $mb_row['gm_upper_check'];

	$nm_data['lower_limit'] = $mb_row['nm_lower_limit'];
	$nm_data['lower_check'] = $mb_row['nm_lower_check'];
	$nm_data['upper_limit'] = $mb_row['nm_upper_limit'];
	$nm_data['upper_check'] = $mb_row['nm_upper_check'];

	return (check_range($gm_data, $mb_row['gross_margin_percentage']) && check_range($nm_data, $mb_row['net_margin_percentage']));
}

function checkRbhExistByQuote($quote_id)
{
	if ($quote_id != '') {
		$ci = &get_instance();
		$ci->db->select('DISTINCT(u.user_id)');
		$ci->db->from('quote_details qd');
		$ci->db->join('opportunity o', 'qd.opportunity_id = o.opportunity_id');
		$ci->db->join('lead l', 'o.lead_id = l.lead_id');
		$ci->db->join('customer_location cl', 'cl.customer_id = l.customer_id');
		$ci->db->join('location l1', 'cl.location_id = l1.location_id');
		$ci->db->join('location l2', 'l1.parent_id = l2.location_id');
		$ci->db->join('location l3', 'l2.parent_id = l3.location_id');
		$ci->db->join('user_location ul', 'ul.location_id = l3.parent_id AND ul.status = 1');
		$ci->db->join('user u', 'u.user_id = ul.user_id AND u.role_id = 7 AND u.status = 1');
		$ci->db->where('qd.quote_id', $quote_id);
		$res = $ci->db->get();
		return ($res->num_rows() > 0) ? TRUE : FALSE;
	}
}

function getQuoteApprovalStatusLabel($status)
{
	$ret = '';
	switch ($status) {
		case 1:
			$ret = 'Pending';
			break;
		case 2:
			$ret = 'Approved';
			break;
		case 3:
			$ret = 'Rejected';
			break;
	}
	return $ret;
}

function getQuoteRevisionReferenceID($lead_id, $quote_id, $quote_revision_id, $quote_number)
{
	if ($lead_id != '') {
		$customer_id = getLeadCustomerID($lead_id);
		$location_id = getCustomerLocation($customer_id);
		$tag = getLocationStateTag($location_id);
		$rev = getQuoteRevisionNumber($quote_id, $quote_revision_id);
		$year = date('y');
		$month = date('m');
		if ($month < 4)
			$year = $year - 1;
		return $tag . '-' . $year . '-' . $quote_number . '-Rev-' . $rev;
	}
}

function getQuoteRevisionNumber($quote_id, $quote_revision_id)
{
	$ret = 1;
	if ($quote_revision_id != '') {
		$CI = &get_instance();
		$q = 'SELECT quote_id, quote_revision_id,  FIND_IN_SET( quote_revision_id, (
				SELECT GROUP_CONCAT( quote_revision_id
				ORDER BY quote_revision_id asc ) 
				FROM quote_revision Where quote_id = "' . $quote_id . '")
				) AS rank
				FROM quote_revision
				Where quote_revision_id = "' . $quote_revision_id . '"';
		$r = $CI->db->query($q);
		$num = $r->num_rows();
		if ($num > 0) {
			$row = $r->row_array();
			return $row['rank'];
		}
	}
	return $ret;
}

function getPoApprovalStatusLabel($status)
{
	$ret = '';
	switch ($status) {
		case 1:
			$ret = 'Pending';
			break;
		case 2:
			$ret = 'Approved';
			break;
		case 3:
			$ret = 'Rejected';
			break;
	}
	return $ret;
}

function getPoStatusLabel($status)
{
	$ret = '';
	switch ($status) {
		case 1:
			$ret = 'Pending';
			break;
		case 2:
			$ret = 'Approved, Wait for invoice clearance';
			break;
		case 3:
			$ret = 'Rejected';
			break;
		case 4:
			$ret = 'Converted to C-Note';
			break;
	}
	return $ret;
}

function getPoStatusList()
{
	$status_list = array(1 => 'Pending', 2 => 'Approved Waiting at Clear for invoice', 3 => 'Rejected', 4 => 'Converted to C-Note');
	return $status_list;
}

function getPoApprovalHistory($approval_id)
{
	if ($approval_id > 0) {
		$ci = &get_instance();
		$ci->db->select('ma.*,u.employee_id,CONCAT(u.first_name," ",u.last_name) as user');
		$ci->db->from('po_product_approval_history ma');
		$ci->db->join('user u', 'u.user_id = ma.created_by');
		$ci->db->where('ma.approval_id', $approval_id);
		$res = $ci->db->get();
		if ($res->num_rows() > 0) {
			return $res->result_array();
		}
	}
}

function getPoProductCountByPoRevision($po_revision_id)
{
	if ($po_revision_id > 0) {
		$ci = &get_instance();
		$ci->db->from('po_product_approval');
		$ci->db->where('po_revision_id', $po_revision_id);
		$res = $ci->db->get();
		return $res->num_rows();
	}
}

function getApprovedPoProductCountByPoRevision($po_revision_id)
{
	if ($po_revision_id > 0) {
		$ci = &get_instance();
		$ci->db->from('po_product_approval');
		$ci->db->where('po_revision_id', $po_revision_id);
		$ci->db->where('status', 2);
		$res = $ci->db->get();
		return $res->num_rows();
	}
}

function getCompletedPoProductCountByPoRevision($po_revision_id)
{
	if ($po_revision_id > 0) {
		$ci = &get_instance();
		$ci->db->from('po_product_approval');
		$ci->db->where('po_revision_id', $po_revision_id);
		$status_arr = array(2, 3);
		$ci->db->where_in('status', $status_arr); // either approved or rejected
		$res = $ci->db->get();
		return $res->num_rows();
	}
}

function addPoStatusHistory($po_id, $status)
{
	if ($po_id != '') {
		$CI = &get_instance();
		$CI->load->model("Common_model");
		$statusData = array(
			'purchase_order_id' => $po_id,
			'status' => $status,
			'created_by' => $CI->session->userdata('user_id'),
			'created_time' => date('Y-m-d H:i:s')
		);
		$CI->Common_model->insert_data('po_status_history', $statusData);
	}
}

function getPoApprovalCcUsersList($role_id, $approval_id)
{
	if ($role_id != '' && $approval_id != '') {
		$ci = &get_instance();
		$ci->db->select('u.*');
		$ci->db->from('po_product_approval_history ma');
		$ci->db->join('user u', 'u.user_id = ma.created_by');
		$ci->db->where('ma.approved_by !=', $role_id, FALSE);
		$ci->db->where('ma.approval_id', $approval_id);
		$ci->db->where('u.status', 1);
		$res = $ci->db->get();
		if ($res->num_rows() > 0) {
			return $res->result_array();
		}
	}
}

function getCNoteTypeLable($cnote_type)
{
	$lable = '';
	switch ($cnote_type) {
		case 1:
			$lable = 'Regular';
			break;
		case 2:
			$lable = 'Purchase Order';
			break;
	}
	return $lable;
}

function getCNoteTypeList()
{
	$list = array(1 => 'Regular', 2 => 'Purchase Order');
	return $list;
}

function getPoApprovalCount()
{
	$ci = &get_instance();
	$role_id = $ci->session->userdata('role_id');
	$allowed_roles = array(7, 8, 9);
	if (in_array($role_id, $allowed_roles)) {
		$ci->db->select('distinct(pa.approval_id)');
		$ci->db->from('po_product_approval pa');
		$ci->db->join('po_revision pr', 'pr.po_revision_id = pa.po_revision_id AND pr.status = 1', 'inner');
		$ci->db->join('purchase_order po', 'po.purchase_order_id = pr.purchase_order_id', 'inner');
		$ci->db->join('po_products pp', 'pp.product_id = pa.product_id', 'inner');
		$ci->db->where('po.user_id IN (' . $ci->session->userdata('reportees') . ')');
		$ci->db->where('pp.product_id IN (' . $ci->session->userdata('products') . ')');
		$ci->db->where('pa.approval_at', $role_id);
		$ci->db->where('po.company_id', $ci->session->userdata('company'));
		$ci->db->where('pa.status', 1);
		$ci->db->group_by('pa.approval_id');
		$res = $ci->db->get();
		//echo $ci->db->last_query(); exit;
		return $res->num_rows();
	}
	return 0;
}

function getPoRevisions($purchase_order_id)
{
	if ($purchase_order_id != '') {
		$ci = &get_instance();
		$ci->db->select('pr.po_revision_id,SUM(pp.qty*pp.unit_price) as total_mrp, pr.status as po_revision_status, po.status as po_status,
			SUM( CASE WHEN pa.discount_type=1 THEN (pp.qty*pp.unit_price)*(1-pa.discount/100) ELSE (pp.qty*pp.unit_price)-pa.discount END ) as total_order_value,po.warranty as warranty,po.default_warranty as default_warranty,sum(p.dp*pp.qty) as dp_value');
		$ci->db->from('po_revision pr');
		$ci->db->join('purchase_order po', 'pr.purchase_order_id = po.purchase_order_id');
		$ci->db->join('po_products pp', 'pp.purchase_order_id = pr.purchase_order_id');
		$ci->db->join('po_product_approval pa', 'pa.po_revision_id = pr.po_revision_id AND pa.product_id = pp.product_id');
		$ci->db->join('product p', 'pp.product_id=p.product_id');
		$ci->db->where('pr.purchase_order_id', $purchase_order_id);
		$ci->db->order_by('pr.po_revision_id', 'ASC');
		$ci->db->group_by('pr.po_revision_id');
		$res = $ci->db->get();
		if ($res->num_rows() > 0)
			return $res->result_array();
	}
}

function getCustomerByLead($lead_id)
{
	if ($lead_id != '') {
		$ci = &get_instance();
		$ci->db->select('');
		$ci->db->from('lead l');
		$ci->db->join('customer c', 'l.customer_id = c.customer_id');
		$ci->db->where('l.lead_id', $lead_id);
		$res = $ci->db->get();
		if ($res->num_rows() > 0)
			return $res->row_array();
	}
}

function getPoRevisionStatusLable($po_revision_status, $po_status)
{
	if ($po_revision_status != '' && $po_status != '') {
		$lable = '';
		switch ($po_revision_status) {
			case 1:
				switch ($po_status) {
					case 1:
						$lable = 'Waiting for Approval';
						break;
					case 3:
						$lable = 'Rejected PO';
						break;
					case 2:
					case 4:
					case 5:
						$lable = 'Approved PO';
						break;
				}
				break;
			case 2:
				$lable = 'Rejected PO';
				break;
		}
		return $lable;
	}
}

function getPoRevisionTotalOrderValue($po_revision_id)
{
	if ($po_revision_id != '') {
		$ci = &get_instance();
		$ci->db->select('SUM( CASE WHEN pa.discount_type=1 THEN (pp.qty*pp.unit_price)*(1-pa.discount/100) ELSE (pp.qty*pp.unit_price)-pa.discount END ) as total_order_value');
		$ci->db->from('po_revision pr');
		$ci->db->join('purchase_order po', 'pr.purchase_order_id = po.purchase_order_id');
		$ci->db->join('po_products pp', 'pp.purchase_order_id = pr.purchase_order_id');
		$ci->db->join('po_product_approval pa', 'pa.po_revision_id = pr.po_revision_id AND pa.product_id = pp.product_id');
		$ci->db->where('pr.po_revision_id', $po_revision_id);
		$ci->db->group_by('pr.po_revision_id');
		$res = $ci->db->get();
		if ($res->num_rows() > 0) {
			$row = $res->row_array();
			return $row['total_order_value'];
		}
	}
}

function getQuoteRevisionTotalOrderValue($quote_revision_id)
{
	if ($quote_revision_id != '') {
		$ci = &get_instance();
		$ci->db->select('SUM( CASE WHEN ma.discount_type=1 THEN (o.required_quantity*qd.mrp)*(1-ma.discount/100) ELSE (o.required_quantity*qd.mrp)-ma.discount END ) as total_order_value');
		$ci->db->from('quote_revision qr');
		$ci->db->join('quote q', 'qr.quote_id = q.quote_id');
		$ci->db->join('quote_details qd', 'qd.quote_id = q.quote_id');
		$ci->db->join('opportunity o', 'o.opportunity_id = qd.opportunity_id');
		$ci->db->join('quote_op_margin_approval ma', 'ma.quote_revision_id = qr.quote_revision_id AND ma.opportunity_id = qd.opportunity_id');
		$ci->db->where('qr.quote_revision_id', $quote_revision_id);
		$ci->db->group_by('qr.quote_revision_id');
		$res = $ci->db->get();
		if ($res->num_rows() > 0) {
			$row = $res->row_array();
			return $row['total_order_value'];
		}
	}
}

function getQuoteRevisionStatusLabel($status, $rev_num)
{
	$label = '';
	if ($rev_num == 1) {
		$label = 'MRP Quote';
		if ($status == 1)
			$label .= ' (Current Quote)';
	} else {
		switch ($status) {
			case 1:
				$label = 'Current Quote';
				break;
			case 2:
				$label = 'Rejected Quote';
				break;
			case 3:
				$label = 'Waiting for Approval';
				break;
			case 4:
				$label = 'Previous Quote';
				break;
		}
	}
	return $label;
}

function addCnoteStatusHistory($contract_note_id, $status)
{
	if ($contract_note_id != '') {
		$CI = &get_instance();
		$CI->load->model("Common_model");
		$statusData = array(
			'contract_note_id' => $contract_note_id,
			'status' => $status,
			'created_by' => $CI->session->userdata('user_id'),
			'created_time' => date('Y-m-d H:i:s')
		);
		$CI->Common_model->insert_data('contract_note_status_history', $statusData);
	}
}

function getDefaultWarranty()
{
	$val = get_preference('default_warranty', 'general_settings');
	return ($val != '') ? $val : 12;
}

function getDefaultBalancePaymentDays()
{
	$val = get_preference('default_balance_payment_days', 'general_settings');
	return ($val != '') ? $val : 30;
}

function getDefaultAdvance()
{
	$val = get_preference('default_advance_percentage', 'general_settings');
	return ($val != '') ? $val : 100;
}

function getOpportunitiesInfoByQuoteRevision($quote_revision_id)
{
	if ($quote_revision_id != '') {
		$ci = &get_instance();
		// $ci->db->select('o.opportunity_id,o.opp_number, CONCAT(p.description," (",p.name,")") as product_name, o.required_quantity, (o.required_quantity*qd.mrp) as mrp,
		// 	ma.discount_type,ma.discount, (o.required_quantity*p.dp) as dp, (o.required_quantity*p.base_price) as base_price,qr.advance,qr.advance_type,qr.balance_payment_days,qr.warranty,qr.dealer_id,qr.dealer_commission,qd.freight_insurance,qd.gst,cur.code as currency_code,ma.dealer_commission as orc');
		$ci->db->select('o.opportunity_id,o.opp_number, CONCAT(p.description," (",p.name,")") as product_name, o.required_quantity, (o.required_quantity*qd.mrp) as mrp,
			ma.discount_type,ma.discount, (o.required_quantity*p.dp) as dp, (o.required_quantity*p.base_price) as base_price,qr.advance,qr.advance_type,qr.balance_payment_days,ma.warranty,qr.dealer_id,ma.dealer_commission,qd.freight_insurance,qd.gst,cur.code as currency_code,ma.dealer_commission as orc, ((ma.warranty/12-1)*(p.dp*4/100)*(o.required_quantity)) as warranty_of_cost');
		$ci->db->from('quote_revision qr');
		$ci->db->join('quote_details qd', 'qd.quote_id = qr.quote_id');
		$ci->db->join('currency cur', 'cur.currency_id = qd.currency_id');
		$ci->db->join('opportunity o', 'o.opportunity_id = qd.opportunity_id');
		$ci->db->join('opportunity_product op', 'o.opportunity_id = op.opportunity_id');
		$ci->db->join('product p', 'p.product_id = op.product_id');
		$ci->db->join('quote_op_margin_approval ma', 'ma.quote_revision_id = qr.quote_revision_id AND ma.opportunity_id = qd.opportunity_id');
		$ci->db->where('qr.quote_revision_id', $quote_revision_id);
		$ci->db->group_by('o.opportunity_id');
		$res = $ci->db->get();
		if ($res->num_rows() > 0) {
			return $res->result_array();
		}
	}
}

function getQuoteFreeSupplies($quote_revision_id)
{
	if ($quote_revision_id != '') {
		$ci = &get_instance();
		$ci->db->select('fs.*,p.name,p.description');
		$ci->db->from('quote_opp_free_supply fs');
		$ci->db->join('product p', 'fs.product_id = p.product_id');
		$ci->db->where('fs.quote_revision_id', $quote_revision_id);
		$res = $ci->db->get();
		if ($res->num_rows() > 0) {
			return $res->result_array();
		}
	}
}


function getPoProductPriceDetails($purchase_order_id, $product_id)
{
	if ($purchase_order_id != '' && $product_id != '') {
		$ci = &get_instance();
		$ci->db->select('(pp.qty*pp.unit_price) as mrp, p.dp as unit_dp, (pp.qty*p.dp) as dp, pp.qty,pp.freight_insurance,pp.gst');
		$ci->db->from('po_products pp');
		$ci->db->join('product p', 'pp.product_id = p.product_id');
		$ci->db->where('pp.purchase_order_id', $purchase_order_id);
		$ci->db->where('pp.product_id', $product_id);
		$res = $ci->db->get();
		if ($res->num_rows() > 0) {
			return $res->row_array();
		}
	}
}

function checkRbhExistForRegion($region_id)
{
	if ($region_id != '') {
		$ci = &get_instance();
		$ci->db->select('DISTINCT(u.user_id)');
		$ci->db->from('user_location ul');
		$ci->db->join('user u', 'u.user_id = ul.user_id AND u.role_id = 7 AND u.status = 1');
		$ci->db->where('ul.location_id', $region_id);
		$ci->db->where('ul.status', 1);
		$res = $ci->db->get();
		return ($res->num_rows() > 0) ? TRUE : FALSE;
	}
}

function getOppApproverEmailsByRole($role_id, $opportunity_id)
{
	if ($role_id != '' && $opportunity_id != '') {
		$ci = &get_instance();
		$ci->db->select('u.*');
		$ci->db->from('lead l');
		$ci->db->join('opportunity o', 'o.lead_id = l.lead_id');
		$ci->db->join('opportunity_product op', 'op.opportunity_id = o.opportunity_id');
		$ci->db->join('location l1', 'l.location_id = l1.location_id');
		$ci->db->join('location l2', 'l2.location_id = l1.parent_id');
		$ci->db->join('location l3', 'l3.location_id = l2.parent_id');
		$ci->db->join('location l4', 'l4.location_id = l3.parent_id');
		if ($role_id == 7) {
			$ci->db->join('user_location ul', 'ul.location_id = l4.location_id AND ul.status = 1'); // Region
		} else {
			$ci->db->join('user_location ul', 'ul.location_id = l4.parent_id AND ul.status = 1'); // country	
		}
		$ci->db->join('user u', 'ul.user_id = u.user_id');
		// $ci->db->join('user_product up','u.user_id = up.user_id AND up.product_id = op.product_id AND up.status = 1');
		$ci->db->where('u.role_id', $role_id);
		$ci->db->where('o.opportunity_id', $opportunity_id);
		$ci->db->where('u.status', 1);
		$res =  $ci->db->get();
		//echo $ci->db->last_query();die;
		if ($res->num_rows() > 0) {
			return $res->result_array();
		}
	}
}

// function getQuoteApprovalEmailData($quote_revision_id,$quote_id,$approval_at,$op_id,$marginaprovalId)
// {
// 	//echo 123;die;
// 	$ci = & get_instance();
// 	$op_results = getOpportunitiesInfoByQuoteRevision($quote_revision_id);
//     //$quote_ma_arr[$row['quote_revision_id']]['opportunities'] = $op_results;
//     $total_mrp = $total_order_value = $total_discount = $total_base_price = $total_dp = $orc = $dealer_commission = 0; 
// 	$lead_id = getLeadIDByQuoteID($quote_id);
// 	$customer = getCustomerAndUserInfoByLead($lead_id);

// 	$quote_reference_id = getQuoteReferenceID1($lead_id,$quote_id);

// 	$revision_id = getQuoteRevisionNumber($quote_id,$quote_revision_id);

// 	if($op_id == 0){
// 		$opr_id = $ci->Common_model->get_data_row('quote_op_margin_approval',array('margin_approval_id'=>$marginaprovalId),"opportunity_id");
// 		$op_number = $ci->Common_model->get_data_row('opportunity',array('opportunity_id'=>$opr_id['opportunity_id']),"opp_number");
// 	}else{
// 		$op_number = $ci->Common_model->get_data_row('opportunity',array('opportunity_id'=>$op_id),"opp_number");
// 	}

//     $ma_report_str = '';
//     $ma_report_str .= '<table style="border-collapse: collapse;" cellpadding="4">
//             <thead style="color:#ffffff; background-color:#6B9BCF;">
//                 <tr>
//                     <th style="border: 1px solid black;">Opportunity ID</th>
//                     <th style="border: 1px solid black;">Product</th>
//                     <th style="border: 1px solid black;">Qty</th>
//                     <th style="border: 1px solid black;">Unit MRP</th>
// 					<th style="border: 1px solid black;">Discount %</th>
// 					<th style="border: 1px solid black;">Warranty</th>
// 					<th style="border: 1px solid black;">ORC %</th>
//                     <th style="border: 1px solid black;">Quoted Price</th>
//                     <th style="border: 1px solid black;">DP</th>
//                 </tr>
//             </thead>
//         <tbody>';
//         $i=0;
//         $freight_insurance = 2;$gst = 12;
//     foreach ($op_results as $op_row) {
//         $total_mrp += $op_row['mrp'];
//         $op_order_value = $op_row['mrp'];
//         $discount = $op_row['discount'];
//         if($op_row['discount_type']!=''&&$discount!='')
//         $op_order_value = ($op_row['discount_type']==1)?($op_order_value*(1-$op_row['discount']/100)):($op_order_value-$discount);
//         $total_order_value += $op_order_value;
//         $total_base_price += $op_row['base_price'];
// 		$total_dp += $op_row['dp'];
// 		$orc += $op_row['orc'];
// 		$dealer_commission += $op_row['dealer_commission'];

//         $order_value = $op_row['mrp'];
//         $discount = $op_row['discount'];
//         if($op_row['discount_type']!=''&&$discount!='')
//         $order_value = ($op_row['discount_type']==1)?($order_value*(1-$op_row['discount']/100)):($order_value-$discount);
//         $discount_percentage = round((($op_row['mrp'] - $order_value )/$op_row['mrp'])*100,2);

//         $ma_report_str .= '<tr>
//                     <td style="border: 1px solid black;">'.$op_row["opp_number"].'</td>
//                     <td style="border: 1px solid black;">'.$op_row['product_name'].'</td>
//                     <td style="border: 1px solid black;">'.$op_row['required_quantity'].'</td>
//                     <td style="border: 1px solid black;">'.indian_format_price(round($op_row['mrp']/$op_row['required_quantity'])).'</td>
// 					<td style="border: 1px solid black;">'.$discount_percentage.'</td>
// 					<td style="border: 1px solid black;">'.$op_row['warranty'].'</td>
// 					<td style="border: 1px solid black;">'.$op_row['orc'].'</td>
//                     <td style="border: 1px solid black;">'.indian_format_price(round($order_value/$op_row['required_quantity'])).'</td>
//                     <td style="border: 1px solid black;">'.indian_format_price(round($op_row['dp']/$op_row['required_quantity'])).'</td>
//                 </tr>';
//         if($i==0)
//         {
//         	$advance = $op_row['advance'];
//         	$advance_type = $op_row['advance_type'];
//         	$balance_payment_days = $op_row['balance_payment_days'];
//         	$warranty = $op_row['warranty'];
//         	// $dealer_commission = $op_row['dealer_commission'];
//         	$dealer = $op_row['dealer_id'];
//         	$freight_insurance = $op_row['freight_insurance'];
//         	$gst = $op_row['gst'];
//         }
//         $i++;
//     }

//     $ma_report_str .= '</tbody></table>';


//     $nsp = round($total_order_value/(1+$freight_insurance/100)/(1+$gst/100));
//     $quote_discount_percenrage = round((($total_mrp - $total_order_value )/$total_mrp)*100,2);
//     $data = array();
//     $data['order_value'] = $total_order_value;
//     $data['net_selling_price'] = $nsp;
//     $data['basic_price'] = $total_base_price;
//     $data['dp'] = $total_dp;
// 	$data['total_warranty_in_years'] = ($warranty>0)?round(($warranty/12),2):0;
// 	$data['orc'] = $orc;
//     if($advance!='')
//     {
//         switch ($advance_type) {
//             case 1:
//                 $advance = $advance;
//             break;

//             case 2:
//                 //$quote_total_order_value = getQuoteRevisionTotalOrderValue($row['quote_revision_id']);
//                 $advance = round(($advance/$total_order_value)*100,2);
//             break;
//         }

//     }
//     else {$advance = 0;}
//     $data['advance'] = $advance;
//     $data['balance_payment_days'] = ($balance_payment_days!='')?$balance_payment_days:0;
// 	// $data['dealer_commission'] = ($dealer_commission>0)?$dealer_commission-1:0;
// 	$data['dealer_commission'] = ($dealer_commission>0)?$dealer_commission:0;
//     $cost_of_free_supply = 0;
//     $free_supplies = getQuoteFreeSupplies($quote_revision_id);
//     $free_supply_str = '';
//     if($free_supplies)
//     {
//         $free_supply_str .= '<p><strong>Free Supply Items</strong></p>
//     <table style="border-collapse: collapse;">
//         <thead style="color:#ffffff; background-color:#6B9BCF;">
//             <tr>
//                 <th style="border: 1px solid black;">Product</th>
//                 <th style="border: 1px solid black;">Qty </th>
//             </tr>
//         </thead>
//         <tbody>';
//         foreach ($free_supplies as $frow) {
//             $cost_of_free_supply += $frow['quantity']*$frow['unit_price'];
//             $free_supply_str .= '<tr>
//                 <td style="border: 1px solid black;">'.$frow['name'].' ('.$frow['description'].')</td>
//                 <td style="border: 1px solid black;">'.$frow['quantity'].'</td>
//             </tr>';
//         }
//         $free_supply_str .= '</tbody></table>';
//     }
//     $data['cost_of_free_supply'] = $cost_of_free_supply;
// 	$data['free_supply'] = $free_supplies;

// 	$ma_data = marginAnalysis($data);
// 	$ma_data['discount_percenrage'] = $quote_discount_percenrage;

//     $ma_report_str .= '<br>';
//     $ma_report_str .= '<table border="0">';
//         $ma_report_str .= '<tr>';
//             $ma_report_str .= '<td valign="top">';
//             $warranty_years = $ma_data['total_warranty_in_years'];
//             $warranty_years .= ($ma_data['total_warranty_in_years']>1)?' YEARS':' YEAR';
//             $balance_payment_in_days = ($ma_data['balance_payment_days']!='')?$ma_data['balance_payment_days'].' DAYS':0;
// 			$dealer_commision_percentage = ($ma_data['dealer_commission']!='')?round($ma_data['dealer_commission'],2).'%':0;
// 			// $dealer_commision_percentage = ($ma_data['orc']!='')?round($ma_data['orc'],2).'%':0;
//                 $ma_report_str .= '<table border="0">
//                                         <tbody
//                                             <tr>
//                                                 <td style="border: 1px solid black;" width="250">Order Value</td>
//                                                 <td style="border: 1px solid black;" width="150">'.indian_format_price(round($ma_data['order_value'])).'</td>
//                                             </tr>
//                                             <tr>
//                                                 <td style="border: 1px solid black;">Net Selling Price</td>
//                                                 <td style="border: 1px solid black;">'.indian_format_price(round($ma_data['net_selling_price'])).'</td>
//                                             </tr>
//                                             <tr>
//                                                 <td style="border: 1px solid black;">Discount %</td>
//                                                 <td style="border: 1px solid black;">'.$ma_data['discount_percenrage'].'%</td>
//                                             </tr>
//                                             <tr>
//                                                 <td style="border: 1px solid black;">Advance Collected %</td>
//                                                 <td style="border: 1px solid black;">'.round($ma_data['advance'],2).'%</td>
//                                             </tr>
//                                             <tr>
//                                                 <td style="border: 1px solid black;">Balance Payment (DAYS)</td>
//                                                 <td style="border: 1px solid black;">'.$balance_payment_in_days.'</td>
//                                             </tr>';
// 											//<tr>
//                                             //     <td style="border: 1px solid black;">Warranty (YEARS)</td>
//                                             //     <td style="border: 1px solid black;">'.$warranty_years.'</td>
//                                             // </tr>
// 											// <tr>
//                                             //     <td style="border: 1px solid black;">Commission to Dealer (%)</td>
//                                             //     <td style="border: 1px solid black;">'.$dealer_commision_percentage.'</td>
//                                             // </tr>
//                                           if($dealer!=''){
//                                             $dealer_row = $ci->Common_model->get_data_row('distributor_details',array('user_id'=>$dealer));
//                                             $ma_report_str .= '<tr>
//                                                 <td style="border: 1px solid black;">Dealer</td>
//                                                 <td style="border: 1px solid black;">'.$dealer_row['distributor_name'].'</td>
//                                             </tr>';
//                                          }
//                                         $ma_report_str .= '</tbody>
//                                     </table>';
//             $ma_report_str .= '</td>';
//             $ma_report_str .= '<td valign="top">';
//                 $ma_report_str .= '<table border="0">
//                                         <tbody>
//                                             <tr>
//                                                 <td style="border: 1px solid black;" width="250">Cost of Warranty</td>
//                                                 <td style="border: 1px solid black;" width="150">'.indian_format_price($ma_data['cost_of_warranty']).'</td>
//                                             </tr>';
//                                             $ch_nsm_report_str = $ma_report_str.'<tr>
//                                                 <td style="border: 1px solid black;">Cost of Finance</td>
//                                                 <td style="border: 1px solid black;">'.indian_format_price($ma_data['cost_of_finance']).'</td>
//                                             </tr>';
//                                             $str = '<tr>
//                                                 <td style="border: 1px solid black;">Commission to Dealer in Rs</td>
//                                                 <td style="border: 1px solid black;">'.indian_format_price($ma_data['cost_of_commission']).'</td>
//                                             </tr>';
//                                             $ch_nsm_report_str .= $str;
//                                             $ma_report_str .= $str;
//                                             $str2 = '<tr>
//                                                 <td style="border: 1px solid black;">Cost of Free Supply</td>
//                                                 <td style="border: 1px solid black;">'.indian_format_price($ma_data['cost_of_free_supply']).'</td>
//                                             </tr>';
//                                             $ch_nsm_report_str .= $str2;
//                                             $ma_report_str .= $str2;
//                         $ch_nsm_report_str .= '<tr>
//                                                 <td style="border: 1px solid black;">Gross Margin %</td>
//                                                 <td style="border: 1px solid black;">'.round($ma_data['gross_margin_percentage'],2).'%</td>
//                                             </tr>
//                                             <tr>
//                                                 <td style="border: 1px solid black;">Gross Margin in Rs</td>
//                                                 <td style="border: 1px solid black;">'.indian_format_price($ma_data['gross_margin']).'</td>
//                                             </tr>
//                                             <tr>
//                                                 <td style="border: 1px solid black;">Net Margin %</td>
//                                                 <td style="border: 1px solid black;">'.($ma_data['net_margin_percentage']).'%</td>
//                                             </tr>
//                                             <tr>
//                                                 <td style="border: 1px solid black;">Net Margin in Rs</td>
//                                                 <td style="border: 1px solid black;">'.indian_format_price($ma_data['net_margin']).'</td>
//                                             </tr>';
//                                         $str3 = '</tbody>
//                                     </table>
//                                 </td>
//                             </tr>
//                     </table>';
//                 $ch_nsm_report_str .= $str3;
//                 $ma_report_str .= $str3;
//             $quote_approvers = array();

//             $subject = $customer['name'].' Quote Approval Request from '.$customer['leadOwner'].' Quote Reference ID: '.$quote_reference_id.'-Rev-'.$revision_id;
//             $message = '';
// 			$message .= '<p>Quote ID : '.$quote_reference_id.'-Rev-'.$revision_id.'<br>';
// 			$message .= 'Lead ID : '.$customer['lead_number'].'<br>';
//             $message .= 'Customer Name : '.$customer['name'].'<br>';
//             $message .= 'Sales Engineer : '.$customer['leadOwner'].'</p>';

//             switch($approval_at)
//             {
//                 case 7:
//                     $message .= $ma_report_str;
//                 break;
//                 case 8: case 9:
//                     $message .= $ch_nsm_report_str;
//                 break;
//             }
//             if($free_supply_str!='')
//                 $message .= $free_supply_str;

//            	$message .= '<br><table border="0">
//                             <tr>
//                                 <td style="background-color: green;border-color: green;border: 2px solid green;border-radius:5px;padding: 10px;text-align: center;">
//                                     <a href="'.SITE_URL.'quoteApprovalAction/1/{ENCODED_ID}" style="display: block;color: #ffffff;font-size: 15px;text-decoration: none;">APPROVE QUOTE'.$op_number['opp_number'].'</a>
//                                 </td>
//                                 <td width="70"></td>
//                                 <td style="background-color: red;border-color: red;border: 2px solid red;border-radius:5px;padding: 10px;text-align: center;">
//                                     <a href="'.SITE_URL.'quoteApprovalAction/2/{ENCODED_ID}" style="display: block;color: #ffffff;font-size: 15px;text-decoration: none;">REJECT QUOTE'.$op_number['opp_number'].'</a>
//                                 </td>
//                             </tr>
//                         </table>';

// 			$message .= '<p>Regards,<br>iCRM,<br>Skanray</p>';
// 			//print_r($message);die;
//     $output = array('subject'=>$subject,'message'=>$message,'lead_id'=>$lead_id,'customer'=>$customer,'quote_reference_id'=>$quote_reference_id);
//     return $output;
// }


function getQuoteApprovalEmailData($quote_revision_id, $quote_id, $approval_at, $op_id, $marginaprovalId)
{
	//echo 123;die;
	$ci = &get_instance();
	$op_results = getOpportunitiesInfoByQuoteRevision($quote_revision_id);
	//$quote_ma_arr[$row['quote_revision_id']]['opportunities'] = $op_results;
	$total_mrp = $total_order_value = $total_discount = $total_base_price = $total_dp = $orc = $dealer_commission = $dealer_comm_cost = $cost_of_warranty1 = 0;
	$lead_id = getLeadIDByQuoteID($quote_id);
	$customer = getCustomerAndUserInfoByLead($lead_id);

	$quote_reference_id = getQuoteReferenceID1($lead_id, $quote_id);

	$revision_id = getQuoteRevisionNumber($quote_id, $quote_revision_id);

	if ($op_id == 0) {
		$opr_id = $ci->Common_model->get_data_row('quote_op_margin_approval', array('margin_approval_id' => $marginaprovalId), "opportunity_id");
		$op_number = $ci->Common_model->get_data_row('opportunity', array('opportunity_id' => $opr_id['opportunity_id']), "opp_number");
	} else {
		$op_number = $ci->Common_model->get_data_row('opportunity', array('opportunity_id' => $op_id), "opp_number");
	}

	$ma_report_str = '';
	$ma_report_str .= '<table style="border-collapse: collapse;" cellpadding="4">
            <thead style="color:#ffffff; background-color:#6B9BCF;">
                <tr>
                    <th style="border: 1px solid black;">Opportunity ID</th>
                    <th style="border: 1px solid black;">Product</th>
                    <th style="border: 1px solid black;">Qty</th>
                    <th style="border: 1px solid black;">Unit MRP</th>
					<th style="border: 1px solid black;">Discount %</th>
					<th style="border: 1px solid black;">Warranty</th>
					<th style="border: 1px solid black;">ORC %</th>
                    <th style="border: 1px solid black;">Quoted Price</th>
                    <th style="border: 1px solid black;">DP</th>
                </tr>
            </thead>
        <tbody>';
	$i = 0;
	$freight_insurance = 2;
	$gst = 12;
	foreach ($op_results as $op_row) {
		$total_mrp += $op_row['mrp'];
		$op_order_value = $op_row['mrp'];
		$discount = $op_row['discount'];
		if ($op_row['discount_type'] != '' && $discount != '')
			$op_order_value = ($op_row['discount_type'] == 1) ? ($op_order_value * (1 - $op_row['discount'] / 100)) : ($op_order_value - $discount);
		$total_order_value += $op_order_value;
		$total_base_price += $op_row['base_price'];
		$total_dp += $op_row['dp'];
		$orc += $op_row['orc'];
		$dealer_commission += $op_row['dealer_commission'];
		$cost_of_warranty1 += round($op_row['warranty_of_cost']);

		$order_value = $op_row['mrp'];
		$discount = $op_row['discount'];
		if ($op_row['discount_type'] != '' && $discount != '')
			$order_value = ($op_row['discount_type'] == 1) ? ($order_value * (1 - $op_row['discount'] / 100)) : ($order_value - $discount);
		$discount_percentage = round((($op_row['mrp'] - $order_value) / $op_row['mrp']) * 100, 2);

		$ma_report_str .= '<tr>
                    <td style="border: 1px solid black;">' . $op_row["opp_number"] . '</td>
                    <td style="border: 1px solid black;">' . $op_row['product_name'] . '</td>
                    <td style="border: 1px solid black;">' . $op_row['required_quantity'] . '</td>
                    <td style="border: 1px solid black;">' . indian_format_price(round($op_row['mrp'] / $op_row['required_quantity'])) . '</td>
					<td style="border: 1px solid black;">' . $discount_percentage . '</td>
					<td style="border: 1px solid black;">' . $op_row['warranty'] . '</td>
					<td style="border: 1px solid black;">' . $op_row['orc'] . '</td>
                    <td style="border: 1px solid black;">' . indian_format_price(round($order_value / $op_row['required_quantity'])) . '</td>
                    <td style="border: 1px solid black;">' . indian_format_price(round($op_row['dp'] / $op_row['required_quantity'])) . '</td>
                </tr>';
		if ($i == 0) {
			$advance = $op_row['advance'];
			$advance_type = $op_row['advance_type'];
			$balance_payment_days = $op_row['balance_payment_days'];
			$warranty = $op_row['warranty'];
			// $dealer_commission = $op_row['dealer_commission'];
			$dealer = $op_row['dealer_id'];
			$freight_insurance = $op_row['freight_insurance'];
			$gst = $op_row['gst'];
		}
		$i++;



		$nsp = round($total_order_value / (1 + $freight_insurance / 100) / (1 + $gst / 100));
		$quote_discount_percenrage = round((($total_mrp - $total_order_value) / $total_mrp) * 100, 2);
		$data = array();
		$data['order_value'] = $total_order_value;
		$data['net_selling_price'] = $nsp;
		$data['basic_price'] = $total_base_price;
		$data['dp'] = $total_dp;
		$data['total_warranty_in_years'] = ($warranty > 0) ? round(($warranty / 12), 2) : 0;
		$data['orc'] = $orc;

		if ($advance != '') {
			switch ($advance_type) {
				case 1:
					$advance = $advance;
					break;

				case 2:
					//$quote_total_order_value = getQuoteRevisionTotalOrderValue($row['quote_revision_id']);
					$advance = round(($advance / $total_order_value) * 100, 2);
					break;
			}
		} else {
			$advance = 0;
		}
		$data['advance'] = $advance;
		$data['balance_payment_days'] = ($balance_payment_days != '') ? $balance_payment_days : 0;
		$data['dealer_commission'] = ($dealer_commission > 0) ? $dealer_commission : 0;
		$cost_of_free_supply = 0;
		$free_supplies = getQuoteFreeSupplies($quote_revision_id);
		$free_supply_str = '';
		if ($free_supplies) {
			$free_supply_str .= '<p><strong>Free Supply Items</strong></p>
			<table style="border-collapse: collapse;">
			<thead style="color:#ffffff; background-color:#6B9BCF;">
				<tr>
					<th style="border: 1px solid black;">Product</th>
					<th style="border: 1px solid black;">Qty </th>
				</tr>
			</thead>
			<tbody>';
			foreach ($free_supplies as $frow) {
				$cost_of_free_supply += $frow['quantity'] * $frow['unit_price'];
				$free_supply_str .= '<tr>
					<td style="border: 1px solid black;">' . $frow['name'] . ' (' . $frow['description'] . ')</td>
					<td style="border: 1px solid black;">' . $frow['quantity'] . '</td>
				</tr>';
			}
			$free_supply_str .= '</tbody></table>';
		}
		$data['cost_of_free_supply'] = $cost_of_free_supply;
		$data['free_supply'] = $free_supplies;


		$nsp2 = round($order_value / (1 + $freight_insurance / 100) / (1 + $gst / 100));
		$dealer_comm1 = $op_row['dealer_commission'];
		$dealer_comm_cost += round($nsp2 * ($dealer_comm1 / 100));
		$data['cost_of_commission1'] = $dealer_comm_cost;
		$data['cost_of_warranty1'] = $cost_of_warranty1;

		$ma_data = marginAnalysis($data);
		$ma_data['discount_percenrage'] = $quote_discount_percenrage;
	}

	$ma_report_str .= '</tbody></table>';





	$ma_report_str .= '<br>';
	$ma_report_str .= '<table border="0">';
	$ma_report_str .= '<tr>';
	$ma_report_str .= '<td valign="top">';
	$warranty_years = $ma_data['total_warranty_in_years'];
	$warranty_years .= ($ma_data['total_warranty_in_years'] > 1) ? ' YEARS' : ' YEAR';
	$balance_payment_in_days = ($ma_data['balance_payment_days'] != '') ? $ma_data['balance_payment_days'] . ' DAYS' : 0;
	$dealer_commision_percentage = ($ma_data['dealer_commission'] != '') ? round($ma_data['dealer_commission'], 2) . '%' : 0;
	// $dealer_commision_percentage = ($ma_data['orc']!='')?round($ma_data['orc'],2).'%':0;
	$ma_report_str .= '<table border="0">
                                        <tbody
                                            <tr>
                                                <td style="border: 1px solid black;" width="250">Order Value</td>
                                                <td style="border: 1px solid black;" width="150">' . indian_format_price(round($ma_data['order_value'])) . '</td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px solid black;">Net Selling Price</td>
                                                <td style="border: 1px solid black;">' . indian_format_price(round($ma_data['net_selling_price'])) . '</td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px solid black;">Discount %</td>
                                                <td style="border: 1px solid black;">' . $ma_data['discount_percenrage'] . '%</td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px solid black;">Advance Collected %</td>
                                                <td style="border: 1px solid black;">' . round($ma_data['advance'], 2) . '%</td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px solid black;">Balance Payment (DAYS)</td>
                                                <td style="border: 1px solid black;">' . $balance_payment_in_days . '</td>
                                            </tr>';
	//<tr>
	//     <td style="border: 1px solid black;">Warranty (YEARS)</td>
	//     <td style="border: 1px solid black;">'.$warranty_years.'</td>
	// </tr>
	// <tr>
	//     <td style="border: 1px solid black;">Commission to Dealer (%)</td>
	//     <td style="border: 1px solid black;">'.$dealer_commision_percentage.'</td>
	// </tr>
	if ($dealer != '') {
		$dealer_row = $ci->Common_model->get_data_row('distributor_details', array('user_id' => $dealer));
		$ma_report_str .= '<tr>
                                                <td style="border: 1px solid black;">Dealer</td>
                                                <td style="border: 1px solid black;">' . $dealer_row['distributor_name'] . '</td>
                                            </tr>';
	}
	$ma_report_str .= '</tbody>
                                    </table>';
	$ma_report_str .= '</td>';
	$ma_report_str .= '<td valign="top">';
	$ma_report_str .= '<table border="0">
                                        <tbody>
                                            <tr>
                                                <td style="border: 1px solid black;" width="250">Cost of Warranty</td>
                                                <td style="border: 1px solid black;" width="150">' . indian_format_price($ma_data['cost_of_warranty']) . '</td>
                                            </tr>';
	$ch_nsm_report_str = $ma_report_str . '<tr>
                                                <td style="border: 1px solid black;">Cost of Finance</td>
                                                <td style="border: 1px solid black;">' . indian_format_price($ma_data['cost_of_finance']) . '</td>
                                            </tr>';
	$str = '<tr>
                                                <td style="border: 1px solid black;">Commission to Dealer in Rs</td>
                                                <td style="border: 1px solid black;">' . indian_format_price($ma_data['cost_of_commission']) . '</td>
                                            </tr>';
	$ch_nsm_report_str .= $str;
	$ma_report_str .= $str;
	$str2 = '<tr>
                                                <td style="border: 1px solid black;">Cost of Free Supply</td>
                                                <td style="border: 1px solid black;">' . indian_format_price($ma_data['cost_of_free_supply']) . '</td>
                                            </tr>';
	$ch_nsm_report_str .= $str2;
	$ma_report_str .= $str2;
	$ch_nsm_report_str .= '<tr>
                                                <td style="border: 1px solid black;">Gross Margin %</td>
                                                <td style="border: 1px solid black;">' . round($ma_data['gross_margin_percentage'], 2) . '%</td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px solid black;">Gross Margin in Rs</td>
                                                <td style="border: 1px solid black;">' . indian_format_price($ma_data['gross_margin']) . '</td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px solid black;">Net Margin %</td>
                                                <td style="border: 1px solid black;">' . ($ma_data['net_margin_percentage']) . '%</td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px solid black;">Net Margin in Rs</td>
                                                <td style="border: 1px solid black;">' . indian_format_price($ma_data['net_margin']) . '</td>
                                            </tr>';
	$str3 = '</tbody>
                                    </table>
                                </td>
                            </tr>
                    </table>';
	$ch_nsm_report_str .= $str3;
	$ma_report_str .= $str3;
	$quote_approvers = array();

	$subject = $customer['name'] . ' Quote Approval Request from ' . $customer['leadOwner'] . ' Quote Reference ID: ' . $quote_reference_id . '-Rev-' . $revision_id;
	$message = '';
	$message .= '<p>Quote ID : ' . $quote_reference_id . '-Rev-' . $revision_id . '<br>';
	$message .= 'Lead ID : ' . $customer['lead_number'] . '<br>';
	$message .= 'Customer Name : ' . $customer['name'] . '<br>';
	$message .= 'Sales Engineer : ' . $customer['leadOwner'] . '</p>';

	switch ($approval_at) {
		case 7:
			$message .= $ma_report_str;
			break;
		case 8:
		case 9:
			$message .= $ch_nsm_report_str;
			break;
	}
	if ($free_supply_str != '')
		$message .= $free_supply_str;

	$message .= '<br><table border="0">
                            <tr>
                                <td style="background-color: green;border-color: green;border: 2px solid green;border-radius:5px;padding: 10px;text-align: center;">
                                    <a href="' . SITE_URL . 'quoteApprovalAction/1/{ENCODED_ID}" style="display: block;color: #ffffff;font-size: 15px;text-decoration: none;">APPROVE QUOTE' . $op_number['opp_number'] . '</a>
                                </td>
                                <td width="70"></td>
                                <td style="background-color: red;border-color: red;border: 2px solid red;border-radius:5px;padding: 10px;text-align: center;">
                                    <a href="' . SITE_URL . 'quoteApprovalAction/2/{ENCODED_ID}" style="display: block;color: #ffffff;font-size: 15px;text-decoration: none;">REJECT QUOTE' . $op_number['opp_number'] . '</a>
                                </td>
                            </tr>
                        </table>';

	$message .= '<p>Regards,<br>iCRM,<br>Skanray</p>';
	//print_r($message);die;
	$output = array('subject' => $subject, 'message' => $message, 'lead_id' => $lead_id, 'customer' => $customer, 'quote_reference_id' => $quote_reference_id);
	return $output;
}

function getPoApprovalEmailData($po_revision_id, $purchase_order_id, $approval_at)
{
	$ci = &get_instance();
	$op_results = getProductsInfoByPoRevision($po_revision_id);
	$total_mrp = $total_order_value = $total_discount = $total_base_price = $total_dp = 0;

	$ma_report_str = '';
	$ma_report_str .= '<table style="border-collapse: collapse;" cellpadding="4">
            <thead style="color:#ffffff; background-color:#6B9BCF;">
                <tr>
                    <th style="border: 1px solid black;">Product</th>
                    <th style="border: 1px solid black;">Qty</th>
                    <th style="border: 1px solid black;">Unit MRP</th>
                    <th style="border: 1px solid black;">Discount %</th>
                    <th style="border: 1px solid black;">Quoted Price</th>
                    <th style="border: 1px solid black;">DP</th>
                </tr>
            </thead>
        <tbody>';
	$i = 0;
	$freight_insurance = 2;
	$gst = 12;
	foreach ($op_results as $op_row) {
		$total_mrp += $op_row['mrp'];
		$op_order_value = $op_row['mrp'];
		$discount = $op_row['discount'];
		if ($op_row['discount_type'] != '' && $discount != '')
			$op_order_value = ($op_row['discount_type'] == 1) ? ($op_order_value * (1 - $op_row['discount'] / 100)) : ($op_order_value - $discount);
		$total_order_value += $op_order_value;
		$total_base_price += $op_row['base_price'];
		$total_dp += $op_row['dp'];

		$order_value = $op_row['mrp'];
		$discount = $op_row['discount'];
		if ($op_row['discount_type'] != '' && $discount != '')
			$order_value = ($op_row['discount_type'] == 1) ? ($order_value * (1 - $op_row['discount'] / 100)) : ($order_value - $discount);
		$discount_percentage = round((($op_row['mrp'] - $order_value) / $op_row['mrp']) * 100, 2);

		$ma_report_str .= '<tr>
                    <td style="border: 1px solid black;">' . $op_row['product_name'] . '</td>
                    <td style="border: 1px solid black;">' . $op_row['qty'] . '</td>
                    <td style="border: 1px solid black;">' . indian_format_price(round($op_row['mrp'] / $op_row['qty'])) . '</td>
                    <td style="border: 1px solid black;">' . $discount_percentage . '</td>
                    <td style="border: 1px solid black;">' . indian_format_price(round($order_value / $op_row['qty'])) . '</td>
                    <td style="border: 1px solid black;">' . indian_format_price(round($op_row['dp'] / $op_row['qty'])) . '</td>
                </tr>';
		if ($i == 0) {
			$advance = $op_row['advance'];
			$advance_type = $op_row['advance_type'];
			$balance_payment_days = $op_row['balance_payment_days'];
			$warranty = $op_row['warranty'];
			$default_warranty = $op_row['default_warranty'];
			$dealer_commission = 0;
			$freight_insurance = $op_row['freight_insurance'];
			$gst = $op_row['gst'];
		}
		$i++;
	}

	$ma_report_str .= '</tbody></table>';


	$nsp = round($total_order_value / (1 + $freight_insurance / 100) / (1 + $gst / 100));
	//echo $nsp; exit;
	$quote_discount_percenrage = round((($total_mrp - $total_order_value) / $total_mrp) * 100, 2);
	$data = array();
	$res = get_extra_warranty_cost($total_order_value, $total_dp, $warranty, $default_warranty);
	$total_order_value = $res['grand_total'];
	$data['order_value'] = $total_order_value;
	$data['net_selling_price'] = $nsp;
	$data['basic_price'] = $total_base_price;
	$data['dp'] = $total_dp;
	$data['total_warranty_in_years'] = ($warranty > 0) ? round(($warranty / 12), 2) : 0;
	if ($advance != '') {
		switch ($advance_type) {
			case 1:
				$advance = $advance;
				break;

			case 2:
				$advance = round(($advance / $total_order_value) * 100, 2);
				break;
		}
	} else {
		$advance = 0;
	}
	$data['advance'] = $advance;
	$data['balance_payment_days'] = ($balance_payment_days != '') ? $balance_payment_days : 0;
	$data['dealer_commission'] = 0;
	$data['cost_of_free_supply'] = 0;
	$data['free_supply'] = '';
	$data['exclude_extra_warranty_in_nm'] = 1;
	$ma_data = marginAnalysis($data);
	$ma_data['discount_percenrage'] = $quote_discount_percenrage;

	$ma_report_str .= '<br>';
	$ma_report_str .= '<table border="0">';
	$ma_report_str .= '<tr>';
	$ma_report_str .= '<td valign="top">';
	$warranty_years = $ma_data['total_warranty_in_years'];
	$warranty_years .= ($ma_data['total_warranty_in_years'] > 1) ? ' YEARS' : ' YEAR';
	$balance_payment_in_days = ($ma_data['balance_payment_days'] != '') ? $ma_data['balance_payment_days'] . ' DAYS' : 0;
	$dealer_commision_percentage = ($ma_data['dealer_commission'] != '') ? round($ma_data['dealer_commission'], 2) . '%' : 0;
	$ma_report_str .= '<table border="0">
                                        <tbody
                                            <tr>
                                                <td style="border: 1px solid black;" width="250">Order Value</td>
                                                <td style="border: 1px solid black;" width="150">' . indian_format_price(round($ma_data['order_value'])) . '</td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px solid black;">Net Selling Price</td>
                                                <td style="border: 1px solid black;">' . indian_format_price(round($ma_data['net_selling_price'])) . '</td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px solid black;">Discount %</td>
                                                <td style="border: 1px solid black;">' . $ma_data['discount_percenrage'] . '%</td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px solid black;">Warranty (YEARS)</td>
                                                <td style="border: 1px solid black;">' . $warranty_years . '</td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px solid black;">Advance Payment %</td>
                                                <td style="border: 1px solid black;">' . round($ma_data['advance'], 2) . '%</td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px solid black;">Balance Payment (DAYS)</td>
                                                <td style="border: 1px solid black;">' . $balance_payment_in_days . '</td>
                                            </tr>';
	$ma_report_str .= '</tbody>
                                    </table>';
	$ma_report_str .= '</td>';
	$ma_report_str .= '<td valign="top">';
	$ma_report_str .= '<table border="0">
                                        <tbody>
                                            <tr>
                                                <td style="border: 1px solid black;" width="250">Cost of Warranty</td>
                                                <td style="border: 1px solid black;" width="150">' . indian_format_price($ma_data['cost_of_warranty']) . '</td>
                                            </tr>';
	$ch_nsm_report_str = $ma_report_str . '<tr>
                                                <td style="border: 1px solid black;">Cost of Finance</td>
                                                <td style="border: 1px solid black;">' . indian_format_price($ma_data['cost_of_finance']) . '</td>
                                            </tr>';
	$ch_nsm_report_str .= '<tr>
                                                <td style="border: 1px solid black;">Gross Margin %</td>
                                                <td style="border: 1px solid black;">' . round($ma_data['gross_margin_percentage'], 2) . '%</td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px solid black;">Gross Margin in Rs</td>
                                                <td style="border: 1px solid black;">' . indian_format_price($ma_data['gross_margin']) . '</td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px solid black;">Net Margin %</td>
                                                <td style="border: 1px solid black;">' . ($ma_data['net_margin_percentage']) . '%</td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px solid black;">Net Margin in Rs</td>
                                                <td style="border: 1px solid black;">' . indian_format_price($ma_data['net_margin']) . '</td>
                                            </tr>';
	$str3 = '</tbody>
                                    </table>
                                </td>
                            </tr>
                    </table>';
	$ch_nsm_report_str .= $str3;
	$ma_report_str .= $str3;
	$quote_approvers = array();
	$distributor = getDistributorDetailsByPoId($purchase_order_id);
	$subject = 'Purchase Order Approval Request from ' . $distributor['distributor_name'];
	$message = '';
	$message .= '<p>Purchse Order ID : ' . $purchase_order_id . '<br>';
	$message .= 'Distributor : ' . $distributor['distributor_name'] . '</p>';

	switch ($approval_at) {
		case 7:
			$message .= $ma_report_str;
			break;
		case 8:
		case 9:
			$message .= $ch_nsm_report_str;
			break;
	}


	$message .= '<br><table border="0">
                            <tr>
                                <td style="background-color: green;border-color: green;border: 2px solid green;border-radius:5px;padding: 10px;text-align: center;">
                                    <a href="' . SITE_URL . 'poApprovalAction/1/{ENCODED_ID}" style="display: block;color: #ffffff;font-size: 15px;text-decoration: none;">APPROVE PO</a>
                                </td>
                                <td width="70"></td>
                                <td style="background-color: red;border-color: red;border: 2px solid red;border-radius:5px;padding: 10px;text-align: center;">
                                    <a href="' . SITE_URL . 'poApprovalAction/2/{ENCODED_ID}" style="display: block;color: #ffffff;font-size: 15px;text-decoration: none;">REJECT PO</a>
                                </td>
                            </tr>
                        </table>';

	$message .= '<p>Regards,<br>iCRM,<br>Skanray</p>';
	$output = array('subject' => $subject, 'message' => $message);
	return $output;
}

function getDistributorDetailsByPoId($purchase_order_id)
{
	$ci = &get_instance();
	$ci->db->select('dd.*');
	$ci->db->from('purchase_order po');
	$ci->db->join('distributor_details dd', 'po.user_id = dd.user_id');
	$res = $ci->db->get();
	if ($res->num_rows() > 0)
		return $res->row_array();
}

function email_mode()
{
	return 1; // 1 for testing, 2 for live
}

function mail_to($to)
{
	$email_mode = email_mode();
	//$test_mails = 'prakhyath.rai@skanray.com';
	//$test_mails = 'manisha@entransys.com';
	//return ($email_mode==1)?$test_mails:$to;
	return $to;
}

function get_nsp($mrp)
{
	return round($mrp / 1.12 / 1.02);
}

function getProductsInfoByPoRevision($po_revision_id)
{
	if ($po_revision_id != '') {
		$ci = &get_instance();
		$ci->db->select('CONCAT(p.description," (",p.name,")") as product_name, pp.qty, (pp.qty*pp.unit_price) as mrp,
			ma.discount_type,ma.discount, (pp.qty*pp.dp) as dp, (pp.qty*p.base_price) as base_price,pr.advance,pr.advance_type,pr.balance_payment_days,
			pr.warranty,po.default_warranty,pp.freight_insurance,pp.gst,cur.code as currency_code');
		$ci->db->from('po_revision pr');
		$ci->db->join('purchase_order po', 'pr.purchase_order_id = po.purchase_order_id');
		$ci->db->join('po_products pp', 'pp.purchase_order_id = po.purchase_order_id');
		$ci->db->join('currency cur', 'cur.currency_id=pp.currency_id');
		$ci->db->join('product p', 'p.product_id = pp.product_id');
		$ci->db->join('po_product_approval ma', 'ma.po_revision_id = pr.po_revision_id AND ma.product_id = pp.product_id');
		$ci->db->where('pr.po_revision_id', $po_revision_id);
		$ci->db->group_by('pp.product_id');
		$ci->db->order_by('p.description asc');
		$res = $ci->db->get();
		if ($res->num_rows() > 0) {
			return $res->result_array();
		}
	}
}

function getCustomerAndUserInfoByLead($lead_id)
{
	if ($lead_id != '') {
		$ci = &get_instance();
		// $ci->db->select('c.*, CONCAT(u.first_name," ",u.last_name) as leadOwner,l.user_id');
		$ci->db->select('c.*, CONCAT(u.first_name," ",u.last_name) as leadOwner,l.user_id,l.lead_number');
		$ci->db->from('lead l');
		$ci->db->join('customer c', 'l.customer_id = c.customer_id');
		$ci->db->join('user u', 'l.user_id = u.user_id');
		$ci->db->where('l.lead_id', $lead_id);
		$res = $ci->db->get();
		if ($res->num_rows() > 0)
			return $res->row_array();
	}
}

function getPoProductApproverEmailsByRole($role_id, $dealer_region, $product_id)
{
	if ($role_id != '' && $dealer_region != '' && $product_id != '') {
		$ci = &get_instance();
		$ci->db->select('u.*');
		$ci->db->from('user u');
		$ci->db->join('user_location ul', 'ul.user_id = u.user_id and ul.status = 1');
		$ci->db->join('location l1', 'ul.location_id = l1.parent_id');
		if ($role_id == 7) {
			$ci->db->where('ul.location_id', $dealer_region); // Region
		} else {
			$ci->db->where('l1.location_id', $dealer_region); // Region
		}
		$ci->db->join('user_product up', 'u.user_id = up.user_id');
		$ci->db->where('u.role_id', $role_id);
		$ci->db->where('up.product_id', $product_id);
		$ci->db->where('u.status', 1);
		$ci->db->group_by('u.user_id');
		$res =  $ci->db->get();
		if ($res->num_rows() > 0) {
			//echo $ci->db->last_query().'<br>';
			return $res->result_array();
		}
	}
}

function getApprovalStatusIcon($status)
{
	$ret = '';
	switch ($status) {
		case 1:
			$ret = '<i class="fa fa-minus-circle" style="color:yellow"></i>';
			break;
		case 2:
			$ret = '<i class="fa fa-check-circle" style="color:green"></i>';
			break;
		case 3:
			$ret = '<i class="fa fa-times-circle" style="color:red"></i>';
			break;
	}
	return $ret;
}

function getDistributorDetails($user_id)
{
	$ci = &get_instance();
	$ci->db->select('*');
	$ci->db->from('user u');
	$ci->db->join('distributor_details dd', 'u.user_id = dd.user_id', 'INNER');
	$ci->db->where('u.user_id', $user_id);
	$res = $ci->db->get();
	if ($res->num_rows() > 0) {
		return $res->row_array();
	}
}

function getAllProductsBySegment($segment_id)
{
	if ($segment_id != '') {
		$ci = &get_instance();
		$ci->db->from('product p');
		$ci->db->where('p.group_id', $segment_id);
		$ci->db->where('status', 1);
		$res = $ci->db->get();
		if ($res->num_rows() > 0)
			return $res->result_array();
	}
}

function marginAnalysisReportAllowedRoles()
{
	$roles = array(7, 8, 9);
	return $roles;
}

function getQuoteFormatTypeByQuoteRevisionID($quote_revision_id)
{
	if ($quote_revision_id != '') {
		$ci = &get_instance();
		$ci->db->from('quote_op_margin_approval');
		$ci->db->where('quote_revision_id', $quote_revision_id);
		$res  = $ci->db->get();
		return ($res->num_rows() > 0) ? 2 : 1; // 1 : Old Format  , 2: New Quote Format
	}
}
//24-05-2018
function getRecentQuoteRevisionID($quote_id)
{
	if ($quote_id != '') {
		$ci = &get_instance();
		$ci->db->select('max(quote_revision_id) as quote_revision_id');
		$ci->db->from('quote_revision');
		$ci->db->where('quote_id', $quote_id);
		$res = $ci->db->get();
		if ($res->num_rows() > 0) {
			$row = $res->row_array();
			return $row['quote_revision_id'];
		}
	}
}
//format 3 for cnote
function quote_latest_format_date()
{
	return '24-01-2019';
}
function getQuoteCreatedDateByCnote($contract_note_id)
{
	$ret = array();
	if ($contract_note_id != '') {
		$CI = &get_instance();
		$q = 'SELECT date(qr.created_time) as created_time from contract_note cn 
				INNER JOIN contract_note_quote_revision cr ON cr.contract_note_id = cn.contract_note_id
				INNER JOIN quote_revision qr ON qr.quote_revision_id = cr.quote_revision_id
				WHERE cn.contract_note_id = "' . $contract_note_id . '"';
		$r = $CI->db->query($q);
		foreach ($r->result_array() as $row) {
			$ret[] = $row['created_time'];
		}
	}
	if (count($ret) == 0) $ret[0] = 0;
	return $ret;
}

function getSuperusermailid()
{
	$CI = &get_instance();
	$CI->load->model("Common_model");
	$mail = 'prakhyath.rai@skanray.com';
	$check = $CI->Common_model->get_value('user', array('email_id' => $mail), 'status');
	echo $check;
	exit;
}

function getAllRolesEmailID($branch_id)
{
	if ($branch_id != '') {
		$ci = &get_instance();
		$ci->db->select('email_id');
		$ci->db->from('user');
		$ci->db->where('branch_id', $branch_id);
		// $ci->db->where('role_id in (3,4,5,7)');
		$res = $ci->db->get();
		if ($res->num_rows() > 0) {
			return $res->result_array();
		}
	}
}


function getOppDetailsByRevisionID($revision_id)
{
	if ($revision_id != '') {
		$ci = &get_instance();
		$ci->db->select('concat("ID - ", o.opp_number, " : ", p.name, " - ", p.description, " (Qty -", o.required_quantity, ")") 
                as opportunity');
		$ci->db->from('opportunity o');
		$ci->db->join('opportunity_product op', 'o.opportunity_id = op.opportunity_id');
		$ci->db->join('product p', 'op.product_id = p.product_id');
		$ci->db->join('quote_op_margin_approval ma', 'ma.opportunity_id=o.opportunity_id');
		$ci->db->where('ma.quote_revision_id', $revision_id);
		$res = $ci->db->get();
		return $res->result_array();
		// echo "<pre>";print_r($res->result_array());die;
		// if($res->num_rows()>0)
		// {
		// 	$row = $res->row_array();
		// 	echo "<pre>";print_r($row);die;
		// 	return $row;
		// }
	}
}
/* file end: ./application/helpers/mahesh_fun_helper.php */

function getOpMarginDataOldOrNew($quote_revision_id)
{
	if ($quote_revision_id != '') {
		$ci = &get_instance();
		$ci->db->from('quote_op_margin_approval');
		$ci->db->where('quote_revision_id', $quote_revision_id);
		$ci->db->where('warranty IS NOT NULL');
		$res  = $ci->db->get();
		return ($res->num_rows() > 0) ? 2 : 1; // 1 : Old Format  , 2: New Quote Format
	}
}
/*
 * Email Notification to demo booked user when create new demo plan
*/

function send_mail_demo_details($demo_id)
{

	$CI = &get_instance();
	// $CI->db->select('d.*,u.email_id,concat(u.first_name," ",u.last_name) as user,u.user_id,d.start_date,d.end_date,o.opportunity_id,p.product_id,p.name,p.description,c.name as customer_name,dpd.serial_number,dpd.location,pc.name as category_name,lr.location as region_name,l.lead_id,l.lead_number');
	$CI->db->select('d.*,u.email_id,concat(u.first_name," ",u.last_name) as user,u.user_id,d.start_date,d.end_date,o.opportunity_id,CONCAT(p.name,"(",p.description,")") as ProductName,c.name as customer_name,dpd.serial_number,dpd.location,pc.name as category_name,l.lead_id,l.lead_number,d.serial_number as snumber');
	$CI->db->from('demo d');
	$CI->db->join('opportunity o', 'o.opportunity_id = d.opportunity_id', 'inner');
	$CI->db->join('lead l', 'l.lead_id = o.lead_id', 'inner');
	$CI->db->join('customer c', 'c.customer_id = l.customer_id', 'inner');
	$CI->db->join('user u', 'u.user_id = l.user_id', 'inner');
	$CI->db->join('opportunity_product op', 'op.opportunity_id = o.opportunity_id');
	$CI->db->join('product p', 'p.product_id = op.product_id', 'inner');
	// $CI->db->join('location lr','lr.location_id = d.region','inner');
	$CI->db->join('product_category pc', 'pc.category_id = d.product_category_id', 'inner');
	$CI->db->join('demo_product_details dpd', 'dpd.demo_product_id = d.demo_machine', 'inner');
	$CI->db->where('d.demo_id', $demo_id);
	$CI->db->group_by('d.demo_id');
	$result = $CI->db->get()->result_array();
	if (count($result) > 0) {
		foreach ($result as $row) {
			// fetch opprtunity detail
			$opportunity_details = $CI->Calendar_model->getOpportunity($row['lead_id']);
			$opportunity_detail = '';
			foreach ($opportunity_details as $key => $value) {
				if ($row['opportunity_id'] == $key) {
					$opportunity_detail = $value;
				}
			}

			// get user region 
			$user_detail = $CI->Common_model->get_data_row('user', array('user_id' => $row['created_by']));
			$user_branch = $CI->Common_model->get_data_row('branch', array('branch_id' => $user_detail['branch_id']));
			$region = $CI->Common_model->get_data_row('location', array('location_id' => $user_branch['region_id']));
			$user_region = $region['location'];
			// send email reminder
			// $to = @$row['email_id'];
			//get email id
			$to = array();
			$email_address = $CI->Common_model->get_data('email', array('status' => 1));
			foreach ($email_address as $key => $email) {
				$to[] = $email['email_id'];
			}
			$to[] = $row['email_id'];
			$to = array_unique($to);
			if (count($to) > 0) {
				$to_email = implode(',', $to);
			}
			$subject = 'Demo Successfully Initialted';
			$message = '<p>Hi ' . $row['user'] . ',</p>';
			$message .= '<p>Your Demo Plan is Confirmed!!! </p>';
			$message .= '<table style="border-collapse: collapse;">
							<thead style="color:#ffffff; background-color:#6B9BCF;">
								<tr>
									<th style="border: 1px solid black;">Particulars</th>
									<th style="border: 1px solid black;">Details</th>
								</tr>
							</thead>
						<tbody>';
			if ($row['product_category_id'] == 3) {
				$message .= 	'<tr>
										<td style="border: 1px solid black;">Product Category Name</td>
										<td style="border: 1px solid black;">Radiology / Critical Care</td>
										</tr>';
			} else {
				$message .= 	'<tr>
										<td style="border: 1px solid black;">Product Category Name</td>
										<td style="border: 1px solid black;">' . $row['category_name'] . '</td>
										</tr>';
			}
			if ($row['requesting_employee_name'] != '') {
				$message .= '<tr>
							<td style="border: 1px solid black;">Requesting Employee Name</td>
							<td style="border: 1px solid black;">' . $row['requesting_employee_name'] . '</td>
							</tr>';
			}
			if ($row['name_of_institute'] != '') {
				$message .= '<tr>
							<td style="border: 1px solid black;">Name of Institute</td>
							<td style="border: 1px solid black;">' . $row['name_of_institute'] . '</td>
							</tr>';
			}
			if ($row['contact_detail'] != '') {
				$message .= '<tr>
							<td style="border: 1px solid black;">Contact Detail</td>
							<td style="border: 1px solid black;">' . $row['contact_detail'] . '</td>
							</tr>';
			}
			if ($row['name_of_contact_institute'] != '') {
				$message .= 	'<tr>
							<td style="border: 1px solid black;">Address</td>
							<td style="border: 1px solid black;">' . $row['name_of_contact_institute'] . '</td>
							</tr>';
			}
			if ($row['key_decision_makers'] != '') {
				$message .= 	'<tr>
							<td style="border: 1px solid black;">Key Decision Makers</td>
							<td style="border: 1px solid black;">' . $row['key_decision_makers'] . '</td>
							</tr>';
			}
			$message .= '<tr>
							<td style="border: 1px solid black;">Region</td>
							<td style="border: 1px solid black;">' . $user_region . '</td>
							</tr>';
			if ($row['nature_of_demo'] != '') {
				if ($row['nature_of_demo'] == 'pre_sale_priority') {
					$message .= 	'<tr>
							<td style="border: 1px solid black;">Nature of Demo</td>
							<td style="border: 1px solid black;">Marketing</td>
							</tr>';
				}else if ($row['nature_of_demo'] == 'marketing') {
					$message .= 	'<tr>
							<td style="border: 1px solid black;">Nature of Demo</td>
							<td style="border: 1px solid black;">Conference/Events</td>
							</tr>';
				}else {
					$message .= 	'<tr>
							<td style="border: 1px solid black;">Nature of Demo</td>
							<td style="border: 1px solid black;">' . ucwords(str_replace('_', ' ', $row['nature_of_demo'])) . '</td>
							</tr>';
				}
			}
			if ($opportunity_detail != '') {
				$message .= 	'<tr>
							<td style="border: 1px solid black;">Opportunity</td>
							<td style="border: 1px solid black;">' . $opportunity_detail . '</td>
							</tr>';
			}
			if ($row['lead_number'] != '' && $row['customer_name'] != '') {
				$message .=	'<tr>
						<td style="border: 1px solid black;">Customer Name</td>
						<td style="border: 1px solid black;">Lead ID(' . $row['lead_number'] . ')-' . $row['customer_name'] . '</td>
						</tr>';
			}
			if ($row['ProductName'] != '') {
				$message .=	'<tr>
									<td style="border: 1px solid black;">Demo Machine</td>
									<td style="border: 1px solid black;">' . $row['ProductName'] . '</td>
									</tr>';
			}
			if ($row['start_date'] != '' && $row['start_date'] != '0000-00-00 00:00:00') {
				$message .=	'<tr>
									<td style="border: 1px solid black;">Start Date</td>
									<td style="border: 1px solid black;">' . $row['start_date'] . '</td>
									</tr>';
			}
			if ($row['end_date'] != '' && $row['end_date'] != '0000-00-00 00:00:00') {
				$message .=	'<tr>
									<td style="border: 1px solid black;">End Date</td>
									<td style="border: 1px solid black;">' . $row['end_date'] . '</td>
									</tr>';
			}
			if ($row['date_of_installation'] != '') {
				$message .=	'
									<td style="border: 1px solid black;">Date of Installation</td>
									<td style="border: 1px solid black;">' . $row['date_of_installation'] . '</td>
									</tr>';
			}
			if ($row['installed_by'] != '') {
				$message .=	'<tr>
									<td style="border: 1px solid black;">Installed By</td>
									<td style="border: 1px solid black;">' . $row['installed_by'] . '</td>
									</tr>';
			}
			if ($row['name_units_installed'] != '') {
				$message .=	'<tr>
									<td style="border: 1px solid black;">Name of units installed</td>
									<td style="border: 1px solid black;">' . $row['name_units_installed'] . '</td>
									</tr>';
			}
			if ($row['customer_complaint_future_prospect'] != '') {
				if ($row['customer_complaint_future_prospect'] == 1) {
					$message .=	'<tr>
									<td style="border: 1px solid black;">Customer Complaint / Future Prospect</td>
									<td style="border: 1px solid black;">Customer Complaint</td>
									</tr>';
				} else {
					$message .=	'<tr>
									<td style="border: 1px solid black;">Customer Complaint / Future Prospect</td>
									<td style="border: 1px solid black;">Future Prospect</td>
									</tr>';
				}
			}
			if ($row['customer_complaint_future_prospect_details'] != '') {
				$message .=	'<tr>
								   <td style="border: 1px solid black;">Details</td>
								   <td style="border: 1px solid black;">' . $row['customer_complaint_future_prospect_details'] . '</td>
								   </tr>';
			}
			if ($row['snumber'] != '') {
				$message .=	'<tr>
									<td style="border: 1px solid black;">Serial Number</td>
									<td style="border: 1px solid black;">' . $row['snumber'] . '</td>
									</tr>';
			}
			if ($row['event_details'] != '') {
				$message .=	'<tr>
									<td style="border: 1px solid black;">Event Details</td>
									<td style="border: 1px solid black;">' . $row['event_details'] . '</td>
									</tr>';
			}
			if ($row['name_of_units_demonstrated'] != '') {
				$message .=	'<tr>
									<td style="border: 1px solid black;">Name of units to be demonstrated</td>
									<td style="border: 1px solid black;">' . $row['name_of_units_demonstrated'] . '</td>
									</tr>';
			}
			if ($row['unit_details_with_specific_model'] != '') {
				$message .=	'<tr>
									<td style="border: 1px solid black;">Existing Unit Details with Specific Model</td>
									<td style="border: 1px solid black;">' . $row['unit_details_with_specific_model'] . '</td>
									</tr>';
			}
			if ($row['competition_info_configuration'] != '') {
				$message .=	'<tr>
									<td style="border: 1px solid black;">Competition Info with Models and Configurations Offered</td>
									<td style="border: 1px solid black;">' . $row['competition_info_configuration'] . '</td>
									</tr>';
			}
			if ($row['no_interactions_end_users'] != '') {
				$message .=	'<tr>
									<td style="border: 1px solid black;">No of Interactions with End Users Before Requesting for Presale Demonstration</td>
									<td style="border: 1px solid black;">' . $row['no_interactions_end_users'] . '</td>
									</tr>
									<tr>';
			}
			$message .= '</tbody>
					</table><br>';

			$message .= '<p>Regards,</p>';
			$message .= '<p>iCRM,<br>SkanRay</p>';

			// file attach to the mail
			$attachments = json_decode($row['file_path']);
			$file_path = FCPATH . "uploads/demo_image/";
			$attachment = array();
			if (count($attachments) > 0) {
				foreach ($attachments as $attach) {
					$file_name = basename($attach);
					$attachment[$file_name] = $file_path . $file_name;
				}
			}
			$attachments_letter = json_decode($row['letter_file_path']);
			if (count($attachments_letter) > 0) {
				foreach ($attachments_letter as $letter_attach) {
					$letter_file_name = basename($letter_attach);
					$attachment[$letter_file_name] = $file_path . $letter_file_name;
				}
			}
			// sending email
			send_email($to_email, $subject, $message, $cc = null, $from = 'noreply@skanray-access.com', $from_name = 'Skanray ICRM', $bcc = NULL, $replyto = NULL,  $attachment);
		}
	} else {
		$CI->db->select('d.*,u.first_name as user,pc.name as category_name');
		$CI->db->from('demo d');
		$CI->db->join('user u', 'u.user_id = d.created_by', 'inner');
		$CI->db->join('product_category pc', 'pc.category_id = d.product_category_id', 'inner');
		$CI->db->where('d.demo_id', $demo_id);
		$CI->db->group_by('d.demo_id');
		$result = $CI->db->get()->row_array();

		// get user region 
		$user_detail = $CI->Common_model->get_data_row('user', array('user_id' => $result['created_by']));
		$user_branch = $CI->Common_model->get_data_row('branch', array('branch_id' => $user_detail['branch_id']));
		$region = $CI->Common_model->get_data_row('location', array('location_id' => $user_branch['region_id']));
		$user_region = $region['location'];

		$subject = 'Demo Successfully Initialted';
		$message = '<p>Hi ' . $result['user'] . ',</p>';
		$message .= '<p>Your Demo Plan is Confirmed!!! </p>';
		$message .= '<table style="border-collapse: collapse;">
							<thead style="color:#ffffff; background-color:#6B9BCF;">
								<tr>
									<th style="border: 1px solid black;">Particulars</th>
									<th style="border: 1px solid black;">Details</th>
								</tr>
							</thead>
						<tbody>';
		if ($result['product_category_id'] == 3) {
			$message .= 	'<tr>
							<td style="border: 1px solid black;">Product Category Name</td>
							<td style="border: 1px solid black;">Radiology / Critical Care</td>
							</tr>';
		} else {
			$message .= 	'<tr>
							<td style="border: 1px solid black;">Product Category Name</td>
							<td style="border: 1px solid black;">' . $result['category_name'] . '</td>
							</tr>';
		}
		if ($result['nature_of_demo'] == 'pre_sale_priority') {
			$message .= 	'<tr>
					<td style="border: 1px solid black;">Nature of Demo</td>
					<td style="border: 1px solid black;">Marketing</td>
					</tr>';
		}else if ($result['nature_of_demo'] == 'marketing') {
			$message .= 	'<tr>
					<td style="border: 1px solid black;">Nature of Demo</td>
					<td style="border: 1px solid black;">Conference/Events</td>
					</tr>';
		} else {
			$message .= 	'<tr>
					<td style="border: 1px solid black;">Nature of Demo</td>
					<td style="border: 1px solid black;">' . ucwords(str_replace('_', ' ', $result['nature_of_demo'])) . '</td>
					</tr>';
		}
		$message .= 	' <tr>
						<td style="border: 1px solid black;">Requesting Employee Name</td>
						<td style="border: 1px solid black;">' . $result['user'] . '</td>
						</tr>
						<tr>
						<td style="border: 1px solid black;">Region</td>
						<td style="border: 1px solid black;">' . $user_region . '</td>
						</tr>';
						if ($result['name_of_institute'] != '') {
							$message .= '<tr>
										<td style="border: 1px solid black;">Name of Institute</td>
										<td style="border: 1px solid black;">' . $result['name_of_institute'] . '</td>
										</tr>';
						}
						if ($result['contact_detail'] != '') {
							$message .= '<tr>
										<td style="border: 1px solid black;">Contact Detail</td>
										<td style="border: 1px solid black;">' . $result['contact_detail'] . '</td>
										</tr>';
						}
						if ($result['name_of_contact_institute'] != '') {
							$message .= 	'<tr>
										<td style="border: 1px solid black;">Address</td>
										<td style="border: 1px solid black;">' . $result['name_of_contact_institute'] . '</td>
										</tr>';
						}
						if ($result['key_decision_makers'] != '') {
							$message .= 	'<tr>
										<td style="border: 1px solid black;">Key Decision Makers</td>
										<td style="border: 1px solid black;">' . $result['key_decision_makers'] . '</td>
										</tr>';
						}
		if ($result['start_date'] != '' && $result['start_date'] != '0000-00-00 00:00:00') {
			$message .=	'<tr>
									<td style="border: 1px solid black;">Start Date</td>
									<td style="border: 1px solid black;">' . $result['start_date'] . '</td>
									</tr>';
		}
		if ($result['end_date'] != '' && $result['end_date'] != '0000-00-00 00:00:00') {
			$message .=	'<tr>
									<td style="border: 1px solid black;">End Date</td>
									<td style="border: 1px solid black;">' . $result['end_date'] . '</td>
								</tr>';
		}
		if ($result['nature_of_demo'] == 'post_sale') {
			$message .=	'
						<tr>
						<td style="border: 1px solid black;">Date of Installation</td>
						<td style="border: 1px solid black;">' . $result['date_of_installation'] . '</td>
						</tr>
						<tr>
						<td style="border: 1px solid black;">Installed By</td>
						<td style="border: 1px solid black;">' . $result['installed_by'] . '</td>
						</tr>
						<tr>
						<td style="border: 1px solid black;">Name of units installed</td>
						<td style="border: 1px solid black;">' . $result['name_units_installed'] . '</td>
						</tr>
						<tr>
						<td style="border: 1px solid black;">Serial Number</td>
						<td style="border: 1px solid black;">' . $result['serial_number'] . '</td>
						</tr>';
		} else if ($result['nature_of_demo'] == 'existing_customer_visit') {
			if ($result['customer_complaint_future_prospect'] == 1) {
				$message .=	'<tr>
						<td style="border: 1px solid black;">Customer Complaint / Future Prospect</td>
						<td style="border: 1px solid black;">Customer Complaint</td>
						</tr>';
			} else {
				$message .=	'<tr>
							<td style="border: 1px solid black;">Customer Complaint / Future Prospect</td>
							<td style="border: 1px solid black;">Future Prospect</td>
							</tr>';
			}
			$message .=	'<tr>
						<td style="border: 1px solid black;">Details</td>
						<td style="border: 1px solid black;">' . $result['customer_complaint_future_prospect_details'] . '</td>
						</tr>
						<tr>
						<td style="border: 1px solid black;">Serial Number</td>
						<td style="border: 1px solid black;">' . $result['serial_number'] . '</td>
						</tr>';
		} else if ($result['nature_of_demo'] == 'marketing') {
			$message .=	'<tr>
						<td style="border: 1px solid black;">Event Details</td>
						<td style="border: 1px solid black;">' . $result['event_details'] . '</td>
						</tr>';
		} else if ($result['nature_of_demo'] == 'pre_sale') {
			$message .=	'
						<tr>
						<td style="border: 1px solid black;">Competition Info with Models and Configurations Offered</td>
						<td style="border: 1px solid black;">' . $result['competition_info_configuration'] . '</td>
						</tr>
						<tr>
						<td style="border: 1px solid black;">No of Interactions with End Users Before Requesting for Presale Demonstration</td>
						<td style="border: 1px solid black;">' . $result['no_interactions_end_users'] . '</td>
						</tr>';
		} else if ($result['nature_of_demo'] == 'pre_sale_priority') {
			$message .=	'
						<tr>
						<td style="border: 1px solid black;">Competition Info with Models and Configurations Offered</td>
						<td style="border: 1px solid black;">' . $result['competition_info_configuration'] . '</td>
						</tr>
						<tr>
						<td style="border: 1px solid black;">No of Interactions with End Users Before Requesting for Presale Demonstration</td>
						<td style="border: 1px solid black;">' . $result['no_interactions_end_users'] . '</td>
						</tr>
						<tr>';
		}
		if ($result['name_of_units_demonstrated'] != '') {
			$message .=	'<tr>
								<td style="border: 1px solid black;">Name of units to be demonstrated</td>
								<td style="border: 1px solid black;">' . $result['name_of_units_demonstrated'] . '</td>
								</tr>';
		}
		if ($result['unit_details_with_specific_model'] != '') {
			$message .=	'<tr>
								<td style="border: 1px solid black;">Existing Unit Details with Specific Model</td>
								<td style="border: 1px solid black;">' . $result['unit_details_with_specific_model'] . '</td>
								</tr>';
		}
		$message .= '</tody>
						</table><br>';

		$message .= '<p>Regards,</p>';
		$message .= '<p>iCRM,<br>SkanRay</p>';
		// file attach to the mail
		$attachments = json_decode($result['file_path']);
		$file_path = FCPATH . "uploads/demo_image/";
		$attachment = array();
		if (count($attachments) > 0) {
			foreach ($attachments as $attach) {
				$file_name = basename($attach);
				$attachment[$file_name] = $file_path . $file_name;
			}
		}
		$attachments_letter = json_decode($result['letter_file_path']);
		if (count($attachments_letter) > 0) {
			foreach ($attachments_letter as $letter_attach) {
				$letter_file_name = basename($letter_attach);
				$attachment[$letter_file_name] = $file_path . $letter_file_name;
			}
		}

		//get email id
		$to = array();
		$email_address = $CI->Common_model->get_data('email', array('status' => 1));
		foreach ($email_address as $key => $email) {
			$to[] = $email['email_id'];
		}
		$to[] = $result['email_id'];
		$to = array_unique($to);
		if (count($to) > 0) {
			$to_email = implode(',', $to);
		}
		// sending email
		send_email($to_email, $subject, $message, $cc = null, $from = 'noreply@skanray-access.com', $from_name = 'Skanray ICRM', $bcc = NULL, $replyto = NULL,  $attachment);
	}
}
