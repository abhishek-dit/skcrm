<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class AdminModel extends CI_Model {

	public function companyResults($searchParams, $per_page, $current_offset)
	{
		
		$this->db->select('company_id, name, status');
		$this->db->from('company');
		if($searchParams['companyName']!='')
		$this->db->like('name',$searchParams['companyName']);
		$this->db->limit($per_page, $current_offset);
		$this->db->order_by('company_id','DESC');
		$res = $this->db->get();
		return $res->result_array();
	}
	
	public function companyTotalRows($searchParams)
	{
		
		$this->db->select('company_id, name, status');
		$this->db->from('company');
		if($searchParams['companyName']!='')
		$this->db->like('name',$searchParams['companyName']);
		$res = $this->db->get();
		return $res->num_rows();
	}
	
	public function companyDetails($searchParams)
	{
		
		$this->db->select('c.*,cu.code as currency');
		$this->db->from('company c');
		$this->db->join('currency cu','cu.currency_id=c.currency_id');
		if($searchParams['companyName']!='')
		$this->db->like('c.name',$searchParams['companyName']);
		$res = $this->db->get();
		return $res->result_array();
	}

	public function editCompanyDetails($company_id)
	{
		$ret = array();
		$qry = "SELECT * from company where company_id = '$company_id'";
		$res = $this->db->query($qry);
		if($res->num_rows() > 0)
		{
			$r = $res->result_array();
			$ret = $r[0];
		}
		return $ret;
	}

	public function adminUserResults($searchParams, $per_page, $current_offset)
	{
		/*
		$q = 'SELECT user_id, employee_id, concat(first_name, " ", last_name) as first_name, c.name, mobile_no, email_id, u.status
			from user u INNER JOIN company c ON c.company_id = u.company_id
			WHERE role_id = 2 AND u.status = 1 ';
		if($searchParams['adminUserName']!='')	
		{
			$q .= ' AND concat(first)';
		}
		*/
		$this->db->select('u.user_id, u.employee_id, concat(first_name, " ", last_name) as first_name,c.name as name, mobile_no, email_id, u.status');
		$this->db->from('user u');
		if($searchParams['adminUserName']!='')
		$this->db->like('concat(first_name, " ", last_name)',$searchParams['adminUserName']);
		if($searchParams['adminUserID']!='')
		$this->db->like('employee_id',$searchParams['adminUserID']);
		if($searchParams['adminUserCompany']!='')
		$this->db->where('c.company_id',$searchParams['adminUserCompany']);
		$this->db->where('role_id',2);
		$this->db->join('company c','c.company_id = u.company_id');
		$this->db->limit($per_page, $current_offset);
		$this->db->order_by('u.user_id','DESC');
		$res = $this->db->get();
		return $res->result_array();
	}
	
	public function adminUserTotalRows($searchParams)
	{
		
		$this->db->select('u.user_id');
		$this->db->from('user u');
		if($searchParams['adminUserName']!='')
		$this->db->like('concat(first_name, " ", last_name)',$searchParams['adminUserName']);
		if($searchParams['adminUserID']!='')
		$this->db->like('employee_id',$searchParams['adminUserID']);
		if($searchParams['adminUserCompany']!='')
		$this->db->where('u.company_id',$searchParams['adminUserCompany']);
		$this->db->where('role_id',2);
		$this->db->join('company c','c.company_id = u.company_id');
		/*$this->db->join('user_company uc','uc.user_id = u.user_id');
		$this->db->join('company c','c.company_id = uc.company_id');
		$this->db->where('uc.status',1);
		$this->db->group_by('uc.user_id');*/
		$res = $this->db->get();
		return $res->num_rows();
	}
	
	public function adminUserDetails($searchParams)
	{
		
		$this->db->select('u.*, c.name as name,b.name as branch' );
		$this->db->from('user u');
		if($searchParams['adminUserName']!='')
		$this->db->like('concat(first_name, " ", last_name)',$searchParams['adminUserName']);
		if($searchParams['adminUserID']!='')
		$this->db->like('employee_id',$searchParams['adminUserID']);
		if($searchParams['adminUserCompany']!='')
		$this->db->where('u.company_id',$searchParams['adminUserCompany']);
		$this->db->where('role_id',2);
		$this->db->join('company c','c.company_id = u.company_id');
		$this->db->join('branch b','b.branch_id=u.branch_id');
		/*$this->db->join('user_company uc','uc.user_id = u.user_id');
		$this->db->join('company c','c.company_id = uc.company_id');
		$this->db->where('uc.status',1);
		$this->db->group_by('uc.user_id');*/
		$this->db->order_by('u.user_id','DESC');
		$res = $this->db->get();
		return $res->result_array();
	}	
	public function adminEmailTotalRows($searchParams)
	{
		$this->db->select('e.id');
		$this->db->from('email e');
		if($searchParams['adminEmailId']!='')
		$this->db->like('email_id',$searchParams['adminEmailId']);
		$this->db->where('status',1);
		$res = $this->db->get();
		return $res->num_rows();
	}
	
	public function adminEmailDetails($searchParams)
	{
		
		$this->db->select('e.*');
		$this->db->from('email e');
		if($searchParams['adminEmailId']!='')
		$this->db->like('email_id',$searchParams['adminEmailId']);
		$this->db->where('status',1);
		$this->db->order_by('e.id','DESC');
		$res = $this->db->get();
		return $res->result_array();
	}
}