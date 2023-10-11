<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings_m extends CI_Model {

	public function settingsTotalRows($searchParams)
	{
		$this->db->select('i.*,r.name as role,f.name as financial_year');
		$this->db->from('incentives i');
		$this->db->join('role r','r.role_id=i.role_id');
		$this->db->join('financial_year f','f.fy_id=i.fy_id');
		if($searchParams['fy_id']!='')
		$this->db->where('i.fy_id',$searchParams['fy_id']);
		if($searchParams['inc_role']!='')
		$this->db->where('i.role_id',$searchParams['inc_role']);
		$this->db->where('i.status',1);
		$res = $this->db->get();
		return $res->num_rows();
	}

	public function settingsResults($searchParams, $per_page, $current_offset)
	{
		$this->db->select('i.*,r.name as role,f.name as financial_year');
		$this->db->from('incentives i');
		$this->db->join('role r','r.role_id=i.role_id');
		$this->db->join('financial_year f','f.fy_id=i.fy_id');
		if($searchParams['fy_id']!='')
		$this->db->where('i.fy_id',$searchParams['fy_id']);
		if($searchParams['inc_role']!='')
		$this->db->where('i.role_id',$searchParams['inc_role']);
		$this->db->where('i.status',1);
		$this->db->limit($per_page, $current_offset);
		$this->db->order_by('i.incentives_id','DESC');
		$res = $this->db->get();
		return $res->result_array();
	}

	public function get_roles()
	{
		$role_arr=array(4,6,7,8);
		$this->db->select('*');
		$this->db->from('role');
		$this->db->where('status',1);
		$this->db->where_in('role_id',$role_arr);
		$res=$this->db->get();
		return $res->result_array();
	}

	public function get_incentive_data($incentive_id)
	{
		$this->db->select('i.*,r.name as role,f.name as financial_year');
		$this->db->from('incentives i');
		$this->db->join('role r','r.role_id=i.role_id');
		$this->db->join('financial_year f','f.fy_id=i.fy_id');
		$this->db->where('i.incentives_id',$incentive_id);
		$this->db->where('i.status',1);
		$res = $this->db->get();
		return $res->row_array();
	}
}