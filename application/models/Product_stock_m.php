<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Product_stock_m extends CI_Model {
	public function get_last_updated_record()
	{   
		$this->db->select('u.created_by,u.created_time');
		$this->db->from('product_stock ps');
		$this->db->join('upload_csv u','ps.upload_id=u.upload_id');
		$this->db->where('company_id',$this->session->userdata('company'));
		$this->db->order_by('ps.upload_id','desc');
		$this->db->limit(1);
		$res=$this->db->get();
		return $res->row_array();
	}

	public function product_stock_rows($searchParams)
	{
		$this->db->select('*');
		$this->db->from('upload_csv');
		if($searchParams['upload_id']!='')
		$this->db->where('upload_id',$searchParams['upload_id']);	
	    if($searchParams['start_date']!='')
		$this->db->where('date(created_time) >=', $searchParams['start_date']);
        if($searchParams['end_date']!='')
		$this->db->where('date(created_time)<=', $searchParams['end_date']);
		$this->db->where('type',1);
		$this->db->where('company_id',$this->session->userdata('company'));
	    $res = $this->db->get();
		return $res->num_rows();
	}

	public function product_stock_results($searchParams, $per_page, $current_offset)
	{
		$this->db->select('*');
		$this->db->from('upload_csv');
		if($searchParams['upload_id']!='')
		$this->db->where('upload_id',$searchParams['upload_id']);	
	    if($searchParams['start_date']!='')
		$this->db->where('date(created_time) >=', $searchParams['start_date']);
        if($searchParams['end_date']!='')
		$this->db->where('date(created_time)<=', $searchParams['end_date']);
		$this->db->where('type',1);
		$this->db->where('company_id',$this->session->userdata('company'));
		$this->db->order_by('upload_id desc');
		$this->db->limit($per_page, $current_offset);
	    $res = $this->db->get();
	   //  echo $this->db->last_query();exit;
		return $res->result_array();
	}
	public function ps_upload_list($searchParams)
	{
		$this->db->select('*');
		$this->db->from('upload_csv');
		if($searchParams['upload_id']!='')
		$this->db->where('upload_id',$searchParams['upload_id']);	
	    if($searchParams['start_date']!='')
		$this->db->where('date(created_time) >=', $searchParams['start_date']);
        if($searchParams['end_date']!='')
		$this->db->where('date(created_time)<=', $searchParams['end_date']);
		$this->db->where('type',1);
		$this->db->where('company_id',$this->session->userdata('company'));
		$res = $this->db->get();
	   //  echo $this->db->last_query();exit;
		return $res->result_array();
	}
	public function get_product_details($upload_id)
	{
		$this->db->select('p.name,p.description,ps.quantity,ps.on_date');
		$this->db->from('product_stock ps');
		$this->db->join('product p','ps.product_id=p.product_id');
		$this->db->where('ps.upload_id',$upload_id);
		$res=$this->db->get();
		return $res->result_array();
	}
}