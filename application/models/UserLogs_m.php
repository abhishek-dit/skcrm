<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UserLogs_m extends CI_Model {

	//mahesh 3rd aug 2016 04:53 pm
	public function userLogResults($current_offset, $per_page, $searchParams)
	{
		
		$this->db->select('u.*,ul.*,r.name as role, b.name as branch');
		$this->db->from('user u');
		$this->db->join('role r','r.role_id=u.role_id','INNER');
		$this->db->join('branch b','b.branch_id=u.branch_id','INNER');
		$this->db->join('user_logs ul','ul.user_id=u.user_id','INNER');
		//$this->db->where('u.status',1);
		$not_include_roles = array(1,2); // GET ALL ROLES EXCLUDING ADMIN, SUPER ADMIN
		$this->db->where_not_in('u.role_id',$not_include_roles);
		$this->db->where_not_in('u.user_id',1);
		if($this->session->userdata('company')!='')
		$this->db->where('u.company_id',$this->session->userdata('company'));
		if($searchParams['user_role']!='')
			$this->db->where('u.role_id',$searchParams['user_role']);
		if($searchParams['user_name']!='')
			$this->db->where('concat(u.first_name, " ", u.last_name) like "%'.$searchParams['user_name'].'%"');
		if($searchParams['employeeId']!='')
			$this->db->like('u.employee_id',$searchParams['employeeId']);
		if($searchParams['email']!='')
			$this->db->like('u.email_id',$searchParams['email']);
		if($searchParams['mobile']!='')
			$this->db->like('u.mobile_no',$searchParams['mobile']);
		if($searchParams['fromDate']!='')
			$this->db->where('DATE(ul.login_time)>=',$searchParams['fromDate']);
		if($searchParams['toDate']!='')
			$this->db->where('DATE(ul.login_time)<=',$searchParams['toDate']);
		$this->db->order_by('ul.login_time','DESC');
		$this->db->limit($per_page, $current_offset);
		$res = $this->db->get();
		//echo $this->db->last_query();
		//exit;
		return $res->result_array();
	}
	
	//mahesh 3rd aug 2016 04:53 pm
	public function userLogTotalRows($searchParams)
	{
		
		$this->db->select('u.*,r.name as role');
		$this->db->from('user u');
		$this->db->join('role r','r.role_id=u.role_id','INNER');
		$this->db->join('user_logs ul','ul.user_id=u.user_id','INNER');
		//$this->db->where('u.status',1);
		$not_include_roles = array(1,2); // GET ALL ROLES EXCLUDING ADMIN, SUPER ADMIN
		$this->db->where_not_in('u.role_id',$not_include_roles);
		$this->db->where_not_in('u.user_id',1);
		if($this->session->userdata('company')!='')
		$this->db->where('u.company_id',$this->session->userdata('company'));
		if($searchParams['user_role']!='')
			$this->db->where('u.role_id',$searchParams['user_role']);
		if($searchParams['user_name']!='')
			$this->db->where('concat(u.first_name, " ", u.last_name) like "%'.$searchParams['user_name'].'%"');
		if($searchParams['employeeId']!='')
			$this->db->like('u.employee_id',$searchParams['employeeId']);
		if($searchParams['email']!='')
			$this->db->like('u.email_id',$searchParams['email']);
		if($searchParams['mobile']!='')
			$this->db->like('u.mobile_no',$searchParams['mobile']);
		if($searchParams['fromDate']!='')
			$this->db->where('DATE(ul.login_time)>=',$searchParams['fromDate']);
		if($searchParams['toDate']!='')
			$this->db->where('DATE(ul.login_time)<=',$searchParams['toDate']);
		$res = $this->db->get();
		return $res->num_rows();
	}

	//mahesh 3rd august 2016 07:25 pm
	public function userLogDetails($searchParams)
	{
		
		$this->db->select('r.name as role, b.name as branch,u.first_name,u.last_name,u.employee_id,ul.login_time,ul.logout_time,ul.last_active,ul.ip_address,ul.user_agent_info');
		$this->db->from('user u');
		$this->db->join('role r','r.role_id=u.role_id','INNER');
		$this->db->join('branch b','b.branch_id=u.branch_id','INNER');
		$this->db->join('distributor_details d','d.user_id = u.user_id','LEFT');
		$this->db->join('user_logs ul','ul.user_id=u.user_id','INNER');
		//$this->db->where('u.status',1);
		$not_include_roles = array(1,2); // GET ALL ROLES EXCLUDING ADMIN, SUPER ADMIN
		$this->db->where_not_in('u.role_id',$not_include_roles);
		$this->db->where_not_in('u.user_id',1);
		if($this->session->userdata('company')!='')
		$this->db->where('u.company_id',$this->session->userdata('company'));
		if($searchParams['user_role']!='')
			$this->db->where('u.role_id',$searchParams['user_role']);
		if($searchParams['user_name']!='')
			$this->db->where('concat(u.first_name, " ", u.last_name) like "%'.$searchParams['user_name'].'%"');
		if($searchParams['employeeId']!='')
			$this->db->like('u.employee_id',$searchParams['employeeId']);
		if($searchParams['email']!='')
			$this->db->like('u.email_id',$searchParams['email']);
		if($searchParams['mobile']!='')
			$this->db->like('u.mobile_no',$searchParams['mobile']);
		if($searchParams['fromDate']!='')
			$this->db->where('DATE(ul.login_time)>=',$searchParams['fromDate']);
		if($searchParams['toDate']!='')
			$this->db->where('DATE(ul.login_time)<=',$searchParams['toDate']);
		$this->db->order_by('ul.login_time','DESC');
		$res = $this->db->get();
		return $res->result_array();
	}


	public function PunchInTotalRows($searchParams)
	{
		$this->db->select('pi.user_id,pi.punch_in_id,min(pi.start_time)as start_time,max(pi.end_time) as end_time,r.name as role,u.first_name as employee_name,u.employee_id,b.name as branch,pi.battery_percentage');
		$this->db->from('punch_in pi');
		$this->db->join('user u','u.user_id=pi.user_id','INNER');
		$this->db->join('role r','r.role_id=u.role_id','INNER');
		$this->db->join('branch b','b.branch_id=u.branch_id','INNER');
		$not_include_roles = array(1,2); // GET ALL ROLES EXCLUDING ADMIN, SUPER ADMIN
		$this->db->where_not_in('u.role_id',$not_include_roles);
		$this->db->where_not_in('u.user_id',1);
		if($this->session->userdata('company')!='')
		$this->db->where('u.company_id',$this->session->userdata('company'));
		if($searchParams['user_role']!='')
			$this->db->where('u.role_id',$searchParams['user_role']);
		if($searchParams['user_name']!='')
			$this->db->where('concat(u.first_name, " ", u.last_name) like "%'.$searchParams['user_name'].'%"');
		if($searchParams['employeeId']!='')
			$this->db->like('u.employee_id',$searchParams['employeeId']);
		if($searchParams['from_Date']!='')
			$this->db->where('DATE(pi.start_time)>=',$searchParams['from_Date']);
		if($searchParams['to_Date']!='')
			$this->db->where('DATE(pi.start_time)<=',$searchParams['to_Date']);
		$this->db->group_by('DATE(pi.start_time),pi.user_id');
		$res = $this->db->get();
		return $res->num_rows();
	}


	public function punchInResults($current_offset, $per_page, $searchParams)
	{
		
		$this->db->select('pi.user_id,pi.punch_in_id,min(pi.start_time) as start_time,max(pi.end_time) as end_time,r.name as role,u.first_name,u.last_name,u.employee_id,b.name as branch,pi.battery_percentage');
		$this->db->from('punch_in pi');
		$this->db->join('user u','u.user_id=pi.user_id','INNER');
		$this->db->join('role r','r.role_id=u.role_id','INNER');
		$this->db->join('branch b','b.branch_id=u.branch_id','INNER');
		$not_include_roles = array(1,2); // GET ALL ROLES EXCLUDING ADMIN, SUPER ADMIN
		$this->db->where_not_in('u.role_id',$not_include_roles);
		$this->db->where_not_in('u.user_id',1);
		if($this->session->userdata('company')!='')
		$this->db->where('u.company_id',$this->session->userdata('company'));
		if($searchParams['user_role']!='')
			$this->db->where('u.role_id',$searchParams['user_role']);
		if($searchParams['user_name']!='')
			$this->db->where('concat(u.first_name, " ", u.last_name) like "%'.$searchParams['user_name'].'%"');
		if($searchParams['employeeId']!='')
			$this->db->like('u.employee_id',$searchParams['employeeId']);
		if($searchParams['from_Date']!='')
			$this->db->where('DATE(pi.start_time)>=',$searchParams['from_Date']);
		if($searchParams['to_Date']!='')
			$this->db->where('DATE(pi.start_time)<=',$searchParams['to_Date']);
		$this->db->group_by('DATE(pi.start_time),pi.user_id');
		$this->db->order_by('pi.punch_in_id','DESC');
		$this->db->limit($per_page, $current_offset);
		$res = $this->db->get();
		return $res->result_array();
	}
	
	public function punchInDetails($searchParams)
	{
		
		$this->db->select('pi.user_id,pi.punch_in_id,min(pi.start_time) as start_time,max(pi.end_time) as end_time,r.name as role,u.first_name,u.last_name,u.employee_id,b.name as branch,pi.battery_percentage');
		$this->db->from('punch_in pi');
		$this->db->join('user u','u.user_id=pi.user_id','INNER');
		$this->db->join('role r','r.role_id=u.role_id','INNER');
		$this->db->join('branch b','b.branch_id=u.branch_id','INNER');
		$not_include_roles = array(1,2); // GET ALL ROLES EXCLUDING ADMIN, SUPER ADMIN
		$this->db->where_not_in('u.role_id',$not_include_roles);
		$this->db->where_not_in('u.user_id',1);
		if($this->session->userdata('company')!='')
		$this->db->where('u.company_id',$this->session->userdata('company'));
		if($searchParams['user_role']!='')
			$this->db->where('u.role_id',$searchParams['user_role']);
		if($searchParams['user_name']!='')
			$this->db->where('concat(u.first_name, " ", u.last_name) like "%'.$searchParams['user_name'].'%"');
		if($searchParams['employeeId']!='')
			$this->db->like('u.employee_id',$searchParams['employeeId']);
		if($searchParams['from_Date']!='')
			$this->db->where('DATE(pi.start_time)>=',$searchParams['from_Date']);
		if($searchParams['to_Date']!='')
			$this->db->where('DATE(pi.start_time)<=',$searchParams['to_Date']);
		$this->db->group_by('DATE(pi.start_time),pi.user_id');
		$this->db->order_by('pi.punch_in_id','DESC');
		$res = $this->db->get();
		return $res->result_array();
	}

	public function PunchInlogsTotalRows($searchParams)
	{
		$this->db->select('pi.user_id,pi.punch_in_id,pi.start_time,pi.end_time,r.name as role,u.first_name as employee_name,u.employee_id,b.name as branch,pi.battery_percentage');
		$this->db->from('punch_in pi');
		$this->db->join('user u','u.user_id=pi.user_id','INNER');
		$this->db->join('role r','r.role_id=u.role_id','INNER');
		$this->db->join('branch b','b.branch_id=u.branch_id','INNER');
		$not_include_roles = array(1,2); // GET ALL ROLES EXCLUDING ADMIN, SUPER ADMIN
		$this->db->where_not_in('u.role_id',$not_include_roles);
		$this->db->where_not_in('u.user_id',1);
		if($this->session->userdata('company')!='')
		$this->db->where('u.company_id',$this->session->userdata('company'));
		if($searchParams['user_role']!='')
			$this->db->where('u.role_id',$searchParams['user_role']);
		if($searchParams['user_name']!='')
			$this->db->where('concat(u.first_name, " ", u.last_name) like "%'.$searchParams['user_name'].'%"');
		if($searchParams['employeeId']!='')
			$this->db->like('u.employee_id',$searchParams['employeeId']);
		if($searchParams['from_Date']!='')
			$this->db->where('DATE(pi.start_time)>=',$searchParams['from_Date']);
		if($searchParams['to_Date']!='')
			$this->db->where('DATE(pi.start_time)<=',$searchParams['to_Date']);
		//$this->db->group_by('DATE(pi.start_time),pi.user_id');
		$res = $this->db->get();
		return $res->num_rows();
	}

	public function punchInlogsResults($current_offset, $per_page, $searchParams)
	{
		
		$this->db->select('pi.user_id,pi.punch_in_id,pi.start_time,pi.end_time,r.name as role,u.first_name,u.last_name,u.employee_id,b.name as branch,pi.battery_percentage');
		$this->db->from('punch_in pi');
		$this->db->join('user u','u.user_id=pi.user_id','INNER');
		$this->db->join('role r','r.role_id=u.role_id','INNER');
		$this->db->join('branch b','b.branch_id=u.branch_id','INNER');
		$not_include_roles = array(1,2); // GET ALL ROLES EXCLUDING ADMIN, SUPER ADMIN
		$this->db->where_not_in('u.role_id',$not_include_roles);
		$this->db->where_not_in('u.user_id',1);
		if($this->session->userdata('company')!='')
		$this->db->where('u.company_id',$this->session->userdata('company'));
		if($searchParams['user_role']!='')
			$this->db->where('u.role_id',$searchParams['user_role']);
		if($searchParams['user_name']!='')
			$this->db->where('concat(u.first_name, " ", u.last_name) like "%'.$searchParams['user_name'].'%"');
		if($searchParams['employeeId']!='')
			$this->db->like('u.employee_id',$searchParams['employeeId']);
		if($searchParams['from_Date']!='')
			$this->db->where('DATE(pi.start_time)>=',$searchParams['from_Date']);
		if($searchParams['to_Date']!='')
			$this->db->where('DATE(pi.start_time)<=',$searchParams['to_Date']);
		//$this->db->group_by('DATE(pi.start_time),pi.user_id');
		$this->db->order_by('pi.punch_in_id','DESC');
		$this->db->limit($per_page, $current_offset);
		$res = $this->db->get();
		return $res->result_array();
	}

	public function punchInlogsDetails($searchParams)
	{
		
		$this->db->select('pi.user_id,pi.punch_in_id,pi.start_time,pi.end_time,r.name as role,u.first_name,u.last_name,u.employee_id,b.name as branch,pi.battery_percentage');
		$this->db->from('punch_in pi');
		$this->db->join('user u','u.user_id=pi.user_id','INNER');
		$this->db->join('role r','r.role_id=u.role_id','INNER');
		$this->db->join('branch b','b.branch_id=u.branch_id','INNER');
		$not_include_roles = array(1,2); // GET ALL ROLES EXCLUDING ADMIN, SUPER ADMIN
		$this->db->where_not_in('u.role_id',$not_include_roles);
		$this->db->where_not_in('u.user_id',1);
		if($this->session->userdata('company')!='')
		$this->db->where('u.company_id',$this->session->userdata('company'));
		if($searchParams['user_role']!='')
			$this->db->where('u.role_id',$searchParams['user_role']);
		if($searchParams['user_name']!='')
			$this->db->where('concat(u.first_name, " ", u.last_name) like "%'.$searchParams['user_name'].'%"');
		if($searchParams['employeeId']!='')
			$this->db->like('u.employee_id',$searchParams['employeeId']);
		if($searchParams['from_Date']!='')
			$this->db->where('DATE(pi.start_time)>=',$searchParams['from_Date']);
		if($searchParams['to_Date']!='')
			$this->db->where('DATE(pi.start_time)<=',$searchParams['to_Date']);
		$this->db->group_by('DATE(pi.start_time),pi.user_id');
		$this->db->order_by('pi.punch_in_id','DESC');
		$res = $this->db->get();
		return $res->result_array();
	}

}