<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Product_price_m extends CI_Model {

	public function get_last_updated_record()
	{   
		$this->db->select('u.created_by,u.created_time');
		$this->db->from('product_price_history pp');
		$this->db->join('upload_csv u','pp.upload_id=u.upload_id');
		$this->db->where('u.company_id',$this->session->userdata('company'));
		$this->db->order_by('pp.upload_id','desc');
		$this->db->limit(1);
		$res=$this->db->get();
		return $res->row_array();
	}
	public function get_latest_price_record($product_id,$currency_id,$company_id)
	{
		$this->db->from('product_price_history');
		$this->db->where('product_id',$product_id);
		$this->db->where('currency_id',$currency_id);
		$this->db->where('company_id',$company_id);
		$this->db->order_by('created_time','desc');
		$this->db->limit(1);
		$res=$this->db->get();
		return $res->row_array();
	}
	public function product_price_rows($searchParams)
	{
		$this->db->select('*');
		$this->db->from('upload_csv');
		if($searchParams['upload_id']!='')
		$this->db->where('upload_id',$searchParams['upload_id']);	
	    if($searchParams['start_date']!='')
		$this->db->where('date(created_time) >=', $searchParams['start_date']);
        if($searchParams['end_date']!='')
		$this->db->where('date(created_time)<=', $searchParams['end_date']);
		$this->db->where('type',3);
		$this->db->where('company_id',$this->session->userdata('company'));
	    $res = $this->db->get();
		return $res->num_rows();
	}
	public function product_price_results($searchParams, $per_page, $current_offset)
	{
		$this->db->select('*');
		$this->db->from('upload_csv');
		if($searchParams['upload_id']!='')
		$this->db->where('upload_id',$searchParams['upload_id']);	
	    if($searchParams['start_date']!='')
		$this->db->where('date(created_time) >=', $searchParams['start_date']);
        if($searchParams['end_date']!='')
		$this->db->where('date(created_time)<=', $searchParams['end_date']);
	    $this->db->where('company_id',$this->session->userdata('company'));
		$this->db->where('type',3);
		$this->db->order_by('upload_id desc');
		$this->db->limit($per_page, $current_offset);
	    $res = $this->db->get();
	   //  echo $this->db->last_query();exit;
		return $res->result_array();
	}
	public function pp_upload_list($searchParams)
	{
		$this->db->select('*');
		$this->db->from('upload_csv');
		if($searchParams['upload_id']!='')
		$this->db->where('upload_id',$searchParams['upload_id']);	
	    if($searchParams['start_date']!='')
		$this->db->where('date(created_time) >=', $searchParams['start_date']);
        if($searchParams['end_date']!='')
		$this->db->where('date(created_time)<=', $searchParams['end_date']);
		$this->db->where('type',3);
		$this->db->where('company_id',$this->session->userdata('company'));
		$res = $this->db->get();
	   //  echo $this->db->last_query();exit;
		return $res->result_array();
	}
	public function get_product_details($upload_id)
	{
		$this->db->select('p.*,pph.*,pt.name as product_type');
		$this->db->from('product_price_history pph');
		$this->db->join('product p','pph.product_id=p.product_id');
		$this->db->join('product_type pt','p.product_type_id=pt.product_type_id','left');
		$this->db->where('pph.upload_id',$upload_id);
		$res=$this->db->get();
		return $res->result_array();
	}
}