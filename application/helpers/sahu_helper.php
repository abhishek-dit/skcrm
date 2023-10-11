<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function getUserDropDownDetails($user_id)
{
	$CI = & get_instance();
	$CI->db->select('case when (r.role_id != 5) then 
			concat(u.first_name, " ", u.last_name, " - ", u.employee_id, " (", r.name, ")" ) 
			else concat(d.distributor_name, " - ", u.employee_id, " (", r.name, ")") end as name');
	$CI->db->from('user u');
	$CI->db->where('u.user_id', $user_id);
	$CI->db->join('role r', 'r.role_id = u.role_id');
	$CI->db->join('distributor_details d', 'd.user_id = u.user_id', 'left');
	$res = $CI->db->get();
	$data = $res->result_array();
	if($res->num_rows() > 0)
		return $data[0]['name'];
	return 0;
}

function getUserName($user_id)
{
	$ret = '';
	if($user_id > 0 )
	{
		$ret = getUserDropDownDetails($user_id);
	}
	return $ret;
}

function getChildRelation($table,$primary_key,$foreign_key)
{
   	$CI = & get_instance();
    $CI->db->select('*');
    $CI->db->from($table);
    $CI->db->where($primary_key,$foreign_key);
    $res = $CI->db->get();
    return $res->num_rows();
}

function getProductChildRelation($product_id)
{
   	$CI = & get_instance();
    $CI->db->select('*');
    $CI->db->from('opportunity_product op');
    $CI->db->where('op.product_id',$product_id);
    $CI->db->where('o.status >=',1);
    $CI->db->where('o.status <=',5);
    $CI->db->join('opportunity o','o.opportunity_id=op.opportunity_id');
    $res = $CI->db->get();
    return $res->num_rows();
}
/* file end: ./application/helpers/sahu_helper.php */
