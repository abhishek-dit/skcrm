<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Financialyear_model extends CI_Model {

	public function financialyearTotalRows($searchParams)
	{
		$this->db->from('financial_year f');
		if($searchParams['fy_year']!='')
		$this->db->where('f.fy_id',$searchParams['fy_year']);
		$this->db->where('f.company_id', $this->session->userdata('company'));
		$this->db->where('f.status',1);
		$res = $this->db->get();
		return $res->num_rows();
	}

	public function financial_year_details($searchParams, $per_page, $current_offset)
	{
		$this->db->from('financial_year f');
		if($searchParams['fy_year']!='')
		$this->db->where('f.fy_id',$searchParams['fy_year']);
		$this->db->where('f.company_id', $this->session->userdata('company'));
		$this->db->where('f.status',1);
		$this->db->limit($per_page, $current_offset);
		$this->db->order_by('f.fy_id','DESC');
		$res = $this->db->get();
		return $res->result_array();
	}


}