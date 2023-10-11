<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class So_amount_upload_model extends CI_Model {
	public function get_last_updated_record()
	{   
		$this->db->select('u.created_by,u.created_time');
		$this->db->from('so_outstanding_amount soa');
		$this->db->join('upload_csv u','soa.upload_id=u.upload_id');
		$this->db->order_by('soa.upload_id','desc');
		$this->db->limit(1);
		$res=$this->db->get();
		return $res->row_array();
	}

	public function so_amount_rows($searchParams)
	{
		$this->db->select('*');
		$this->db->from('upload_csv');
		if($searchParams['upload_id']!='')
		$this->db->where('upload_id',$searchParams['upload_id']);	
	    if($searchParams['start_date']!='')
		$this->db->where('date(created_time) >=', $searchParams['start_date']);
        if($searchParams['end_date']!='')
		$this->db->where('date(created_time)<=', $searchParams['end_date']);
		$this->db->where('type',2);
		$res = $this->db->get();
		return $res->num_rows();
	}

	public function so_amount_results($searchParams, $per_page, $current_offset)
	{
		$this->db->select('*');
		$this->db->from('upload_csv');
		if($searchParams['upload_id']!='')
		$this->db->where('upload_id',$searchParams['upload_id']);	
	    if($searchParams['start_date']!='')
		$this->db->where('date(created_time) >=', $searchParams['start_date']);
        if($searchParams['end_date']!='')
		$this->db->where('date(created_time)<=', $searchParams['end_date']);
		$this->db->where('type',2);
		$this->db->order_by('upload_id','desc');
		$this->db->limit($per_page, $current_offset);
		 $res = $this->db->get();
	   // echo $this->db->last_query();exit;
		return $res->result_array();
	}
	public function so_upload_list($searchParams)
	{
		$this->db->select('*');
		$this->db->from('upload_csv');
		if($searchParams['upload_id']!='')
		$this->db->where('upload_id',$searchParams['upload_id']);	
	    if($searchParams['start_date']!='')
		$this->db->where('date(created_time) >=', $searchParams['start_date']);
        if($searchParams['end_date']!='')
		$this->db->where('date(created_time)<=', $searchParams['end_date']);
		$this->db->where('type',2);
		$res = $this->db->get();
	   //  echo $this->db->last_query();exit;
		return $res->result_array();
	}

	public function get_upload_months_data($so_number,$upload_id)
    {
        $this->db->select('*');
        $this->db->from('so_collection_plan');
        $this->db->where('so_number',$so_number);
        $this->db->where('upload_id',$upload_id);
        $res=$this->db->get();
        //echo $this->db->last_query(); exit;
        return $res->result_array();
    }

}