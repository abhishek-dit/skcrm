<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Loginmodel extends CI_Model {

	public function user_login($user_id, $password)
	{
		$qry = "SELECT user_id from user where employee_id = ".$this->db->escape($user_id)." AND 
				password = ".$this->db->escape(md5($password))."";
		//echo $qry;exit;
		$res = $this->db->query($qry);
		return $res->num_rows();
	}
	public function user_details($user_id, $password)
	{
		$qry = "SELECT u.user_id, u.employee_id, u.first_name, u.last_name, u.role_id, r.name as role, u.company_id, c.name as company, u.status ,b.branch_id,l.location from user u 
				INNER JOIN role r on r.role_id = u.role_id
				INNER JOIN company c on c.company_id = u.company_id
				INNER JOIN branch b on b.branch_id = u.branch_id
				INNER JOIN location l on l.location_id = b.region_id
				WHERE u.employee_id = ".$this->db->escape($user_id)." AND password = ".$this->db->escape(md5($password))."";
		$res = $this->db->query($qry);
		//echo $qry;exit;
		$data = $res->result_array();
		return $data[0];
	}

	public function role_details($role_id)
	{
		$qry = 'SELECT role_id, name, role_level_id from role where role_id in (1, 2, 3, 4, 6, 7, 8, 9, 10, 11, 14,15) order by name';
		$res = $this->db->query($qry);

		return $res->result_array();		
	}

	public function change_password($old, $new, $user_id)
	{
		$oldPassword = mysqli_real_escape_string($this->db->conn_id, $old);
		$newPassword = mysqli_real_escape_string($this->db->conn_id, $new);
		$user_id = mysqli_real_escape_string($this->db->conn_id, $user_id);
		if($oldPassword!="")
		{
			$qry = 'SELECT * FROM user WHERE password="'.md5($oldPassword).'" AND user_id='.$user_id.'';
			$res = $this->db->query($qry);
			if($res->num_rows() == 0)
				return 2;
			else
			{
				$q = 'UPDATE user SET password="'.md5($newPassword).'" WHERE user_id='.$user_id.'';
				$r = $this->db->query($q);
				if($r)
					return 1;
			}
		}
	}

	public function getEmail($user_id)
	{
		$sso_id = mysqli_real_escape_string($this->db->conn_id, $user_id);
		$qry = 'SELECT email_id from user where employee_id = '.$user_id;
		$res = $this->db->query($qry);
		$rows = $res->result_array();
		return $rows[0]['email'];
	}

	public function checkUser($employee_id)
	{
		$sso_id = mysqli_real_escape_string($this->db->conn_id, $employee_id);
		$qry = 'SELECT user_id from user where employee_id = '.$employee_id;
		$res = $this->db->query($qry);
		return $res->num_rows();
	}


	public function updatePassword($user_id, $newPassword)
	{
		$sso_id = mysqli_real_escape_string($this->db->conn_id, $user_id);
		$newPassword = mysqli_real_escape_string($this->db->conn_id, $newPassword);

		$qry = 'UPDATE user SET password="'.md5($newPassword).'" WHERE user_id='.$user_id.'';
		$res = $this->db->query($qry);
		return 1;
	}


	public function getRoleName($role_id)
	{
		$qry = 'SELECT name from role where role_id = '.$role_id.'';
		$res = $this->db->query($qry);
		$row = $res->result_array();
		return $row[0]['name'];
	}

	public function get_last_punch_in()
	{   
		$var = FALSE;
		$this->db->select('*');
		$this->db->from('punch_in');
		$this->db->where('user_id',$this->session->userdata('user_id'));
		$this->db->order_by('punch_in_id','DESC');
		$this->db->limit(1);
		$res= $this->db->get();
		$result = $res->row_array();
		if(count($result)>0)
		{
			if(@$result['end_time']=='NULL' || @$result['end_time']=='')
			{
				$var = TRUE;
			}
		}
		return $var;
	}
}