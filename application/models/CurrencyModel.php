<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class CurrencyModel extends CI_Model {
	public function currencyResults($searchParams, $per_page, $current_offset)
	{	
		$this->db->select('c.*');
		$this->db->from('currency c');
		if($searchParams['currency_name']!='')
		$this->db->like('c.name',$searchParams['currency_name']);
		if($searchParams['code']!='')
		$this->db->where('c.code',$searchParams['code']);
		$this->db->limit($per_page, $current_offset);
		$this->db->order_by('c.currency_id','DESC');
		$res = $this->db->get();
		return $res->result_array();
	}

	public function currencyTotalRows($searchParams)
	{
		$this->db->from('currency c');
		if($searchParams['currency_name']!='')
		$this->db->like('c.name',$searchParams['currency_name']);
		if($searchParams['code']!='')
		$this->db->where('c.code',$searchParams['code']);
		$res = $this->db->get();
		return $res->num_rows();
	}
	public function is_currencyCodeExist($currency_code,$currency_id){
		
		$this->db->select();
		$this->db->where('code',$currency_code);	
        if($currency_id!='')
		$this->db->where('currency_id!=',$currency_id);
		$query = $this->db->get('currency');
		return ($query->num_rows()>0)?1:0;
	}
	public function get_download_details($searchParams)
	{
		$this->db->from('currency c');
		if($searchParams['currency_name']!='')
		$this->db->like('c.name',$searchParams['currency_name']);
		if($searchParams['code']!='')
		$this->db->where('c.code',$searchParams['code']);
		$res = $this->db->get();
		return $res->result_array();
	}
}