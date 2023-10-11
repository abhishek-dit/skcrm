<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Product_bulk_upload_m extends CI_Model {

	public function productDetails()
	{
		$this->db->select('p.*, pc.name as CategoryName, pg.name as GroupName, pg.description as groupDescription,pt.name as pt_name,c.name as category_name');
		$this->db->from('product p');
		$this->db->where('pc.company_id', $this->session->userdata('company'));
		$this->db->where('p.status', 1);
		$this->db->join('product_group pg ','p.group_id = pg.group_id');
		$this->db->join('product_category pc ','pg.category_id = pc.category_id');
		$this->db->join('product_type pt','p.product_type_id=pt.product_type_id','left');
		$this->db->join('sub_category c','c.sub_category_id=p.sub_category_id','left');
		$this->db->order_by('product_id ASC');
		$res = $this->db->get();
		return $res->result_array();
	}

	public function check_group($segment)
	{
		$this->db->select('pg.*');
		$this->db->from('product_group pg');
		$this->db->join('product_category pc','pg.category_id=pc.category_id');
		$this->db->where('pg.name',strtoupper($segment));
		$this->db->where('pc.company_id',$this->session->userdata('company'));
		$res=$this->db->get();
		//echo $this->db->last_query();exit;
		return $res->row_array();
	}
	public function check_sub_system($sub_system)
	{   $where='name like "%'.$sub_system.'%"';
		$this->db->select('sub_category_id,name');
		$this->db->from('sub_category');
		$this->db->where($where);
		$res = $this->db->get();
		return $res->num_rows();
	}
	public function check_product_type($product_type)
	{   $where='name like "%'.$product_type.'%"';
		$this->db->select('product_type_id,name');
		$this->db->from('product_type');
		$this->db->where($where);
		$res = $this->db->get();
		return $res->num_rows();
	}
	public function get_product_type()
	{
		$this->db->select('*');
		$this->db->from('product_type');
		$res=$this->db->get();
		return $res->result_array();
	}
	public function product_list_rows($searchParams)
	{
		$this->db->select('*');
		$this->db->from('upload_csv');
		if($searchParams['upload_id']!='')
		$this->db->where('upload_id',$searchParams['upload_id']);	
	    if($searchParams['start_date']!='')
		$this->db->where('date(created_time) >=', $searchParams['start_date']);
        if($searchParams['end_date']!='')
		$this->db->where('date(created_time)<=', $searchParams['end_date']);
		$this->db->where('type',4);
	    $res = $this->db->get();
		return $res->num_rows();
	}
	public function product_list_results($searchParams, $per_page, $current_offset)
	{
		$this->db->select('*');
		$this->db->from('upload_csv');
		if($searchParams['upload_id']!='')
		$this->db->where('upload_id',$searchParams['upload_id']);	
	    if($searchParams['start_date']!='')
		$this->db->where('date(created_time) >=', $searchParams['start_date']);
        if($searchParams['end_date']!='')
		$this->db->where('date(created_time)<=', $searchParams['end_date']);
		$this->db->where('type',4);
		$this->db->order_by('upload_id desc');
		$this->db->limit($per_page, $current_offset);
	    $res = $this->db->get();
	   //  echo $this->db->last_query();exit;
		return $res->result_array();
	}
	public function product_upload_list($searchParams)
	{
		$this->db->select('*');
		$this->db->from('upload_csv');
		if($searchParams['upload_id']!='')
		$this->db->where('upload_id',$searchParams['upload_id']);	
	    if($searchParams['start_date']!='')
		$this->db->where('date(created_time) >=', $searchParams['start_date']);
        if($searchParams['end_date']!='')
		$this->db->where('date(created_time)<=', $searchParams['end_date']);
		$this->db->where('type',4);
		$res = $this->db->get();
	   //  echo $this->db->last_query();exit;
		return $res->result_array();
	}
}