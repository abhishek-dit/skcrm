<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class New_outstanding_format_m extends CI_Model {
	public function get_months()
	{
		$this->db->from('month');
		$res=$this->db->get();
		return $res->result_array();
	}
	public function get_regions($country_id)
	{
		$this->db->select('l.*');
		$this->db->from('location l');
		$this->db->join('location l1','l1.location_id=l.parent_id','left');
		$this->db->where('l1.location_id',$country_id);
		$this->db->where('l.territory_level_id',4);
		$res=$this->db->get();
		return $res->result_array();	
	}
	public function get_region_id($region,$country_id)
	{
		$this->db->select('l.location_id');
		$this->db->from('location l');
		$this->db->join('location l1','l1.location_id=l.parent_id','left');
		$this->db->where('l1.location_id',$country_id);
		$this->db->where('l.location',$region);
		$this->db->where('l.territory_level_id',4);
		$res=$this->db->get();
		return $res->row_array();
	}
	function get_financial_year_for_given_date($posted_date)
	{   
		$this->db->from('financial_year f');
		$this->db->where('f.start_date<=',$posted_date);
		$this->db->where('f.end_date>=',$posted_date);
		$res=$this->db->get();
		$result=$res->row_array();
		return $result['fy_id'];
	}
	public function get_last_updated_record()
	{   
		$this->db->select('u.created_by,u.created_time');
		$this->db->from('new_so_outstanding_amount nsoa');
		$this->db->join('upload_csv u','nsoa.upload_id=u.upload_id');
		$this->db->where('u.company_id',$this->session->userdata('company'));
		$this->db->order_by('nsoa.upload_id','desc');
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
		$this->db->where('company_id',$this->session->userdata('company'));
		$this->db->where('type',5);
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
		$this->db->where('company_id',$this->session->userdata('company'));
		$this->db->where('type',5);
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
		$this->db->where('company_id',$this->session->userdata('company'));
		$this->db->where('type',5);
		$res = $this->db->get();
	   //  echo $this->db->last_query();exit;
		return $res->result_array();
	}
	public function fetch_os_results($searchParams)
	{
		$this->db->select('l.location,nosa.*');
		$this->db->from('new_so_outstanding_amount nosa');
		$this->db->join('location l','nosa.region_id=l.location_id');
		if($searchParams['region_id']!='')
		$this->db->where('nosa.region_id',$searchParams['region_id']);
		if($searchParams['month_id']!='')
		$this->db->where('nosa.month_id',$searchParams['month_id']);
		if($searchParams['year_id']!='')
		$this->db->where('nosa.year_id',$searchParams['year_id']);
		$this->db->where('nosa.company_id',$this->session->userdata('company'));
		$res = $this->db->get();
	   //  echo $this->db->last_query();exit;
		return $res->result_array();
	}
	public function get_report_months($searchParams)
	{
		$current_month=date('m');
		$current_year=date('Y');
		$this->db->from('month');
		if($searchParams['month_id']==$current_month && $searchParams['year_id']==$current_year)
		{
			$this->db->where('month_id<=',$searchParams['month_id']);
		}
		$res=$this->db->get();
		return $res->result_array();
	}
}