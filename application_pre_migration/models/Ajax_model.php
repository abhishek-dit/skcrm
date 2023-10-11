<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax_model extends CI_Model 
{	
	public function getContactInfo($customer_id)
	{
		$q = "SELECT c.contact_id, concat(c.first_name,' ',c.last_name,' - ',s.name,' (', c.mobile_no, ')' ) as cName from contact c
			INNER JOIN speciality s on s.speciality_id = c.speciality_id
			INNER JOIN customer_location_contact clc ON clc.contact_id = c.contact_id
			INNER JOIN customer cu ON cu.customer_id = clc.customer_id
			WHERE c.status = 1 AND cu.status = 1 AND clc.customer_id = '".$customer_id."'";
		$res = $this->db->query($q);
		$options = "<option value=''>Select Contact Person</option>";
	    foreach($res->result_array() as $row)
	    {
	    	$options .= '<option value="' . $row['contact_id'] . '">' . $row['cName'] . '</option>';
	    }
	    echo $options;
	}

	public function getDecisionMakerInfo($customer_id)
	{
		$qry = 'SELECT name from customer where customer_id = "'.$customer_id.'"';
		$r = $this->db->query($qry);
		$customer = '';
		if($r->num_rows() > 0)
		{
			$data = $r->result_array();
			$customer = $data[0]['name'];
		}
		$q = "SELECT c.contact_id, concat(c.first_name,' ',c.last_name,' - ',s.name,' (', c.mobile_no, ')' ) as cName from contact c
			INNER JOIN speciality s on s.speciality_id = c.speciality_id
			INNER JOIN customer_location_contact clc ON clc.contact_id = c.contact_id
			INNER JOIN customer cu ON cu.customer_id = clc.customer_id
			WHERE cu.status = 1 AND c.status = 1 AND cu.name = '".$this->db->escape_str($customer)."'";
		$res = $this->db->query($q);
		$options = "<option value=''>Select Decision Maker</option>";
	    foreach($res->result_array() as $row)
	    {
	    	$options .= '<option value="' . $row['contact_id'] . '">' . $row['cName'] . '</option>';
	    }
	    echo $options;
	}

	public function getChilds($location_id, $territory)
	{
		$q = "SELECT l.location_id, l.location from location l where l.parent_id = '".$location_id."'";
		$res = $this->db->query($q);
		$options = "<option value=''>Select ".$territory."</option>";
	    foreach($res->result_array() as $row)
	    {
	    	$options .= '<option value="' . $row['location_id'] . '">' . $row['location'] . '</option>';
	    }
	    echo $options;
	}

	public function getSecondUser($customer_id, $checkRole)
	{
		$role_id = ($checkRole == 1)?4:5;
		$secondUser = ($checkRole == 1)? 'Sales Engineer':'Distributor';
		//if($role_id == 4) $role_id = '4,6,7,8,9,10,11';

		$location_id = getCustomerLocation($customer_id);
		$locations = getAllParents($location_id);
		if($locations == '') $locations = 0;
		$q = "SELECT u.user_id, case when (r.role_id = 5) then concat(distributor_name, ' - ', u.employee_id,' (',r.name, ')') else
			concat(u.first_name, ' ', u.last_name, ' - ', u.employee_id, ' (', r.name,')') end as uName from user u
			INNER JOIN user_location ul on ul.user_id = u.user_id
			INNER JOIN role r ON r.role_id = u.role_id
			LEFT JOIN distributor_details d ON d.user_id = u.user_id
			WHERE u.status = 1 and ul.status = 1 and ul.location_id IN (".$locations.") and u.role_id IN (".$role_id.")";
		$res = $this->db->query($q);	
		$options = "<option value=''>Select ".$secondUser."</option>";
	    foreach($res->result_array() as $row)
	    {
	    	$options .= '<option value="' . $row['user_id'] . '">' . $row['uName'] . '</option>';
	    }
	    echo $options;
	}

	public function getRBHInfo($location_id)
	{
		$region = getAllParents($location_id);
		$q = "SELECT u.user_id, concat(u.first_name, ' ', u.last_name, ' - ', u.employee_id, ' (', r.name, ')') cName from user u
			INNER JOIN user_location ul on ul.user_id = u.user_id 
			INNER JOIN role r on r.role_id = u.role_id
			WHERE u.status = 1 AND ul.status = 1 AND u.role_id IN (4,6,7,8,9) AND ul.location_id IN (".$region.")";
		$res = $this->db->query($q);
		$options = "<option value=''>Select User to Assign</option>";
	    foreach($res->result_array() as $row)
	    {
	    	$options .= '<option value="' . $row['user_id'] . '">' . $row['cName'] . '</option>';
	    }
	    echo $options;
	}

	public function getReportees($location_id, $r)
	{
		if($r == '') $r = 0;
		$region = getAllParents($location_id);
		$q = "SELECT u.user_id, case when (r.role_id = 5) then concat(distributor_name, ' - ', u.employee_id,' (',r.name, ')') else
			concat(u.first_name, ' ', u.last_name, ' - ', u.employee_id, ' (', r.name,')') end as cName from user u
			INNER JOIN user_location ul on ul.user_id = u.user_id 
			INNER JOIN role r on r.role_id = u.role_id
			LEFT JOIN distributor_details d ON d.user_id = u.user_id
			WHERE u.status = 1 AND ul.status = 1 AND u.role_id IN (".$r.") AND ul.location_id IN (".$region.")";
		$res = $this->db->query($q);
		$options = "<option value=''>Select User to Assign</option>";
	    foreach($res->result_array() as $row)
	    {
	    	$options .= '<option value="' . $row['user_id'] . '">' . $row['cName'] . '</option>';
	    }
	    echo $options;

	}

	public function getReportingSEAndDistributorInfo($val)
	{
		$data = [];
		$users = $this->session->userdata('reportees');
		$limit = getDefaultSelect2Limit();
		//$limit = 50;
		$q = "SELECT t.user_id, t.cName from 
			(SELECT u.user_id, case when (r.role_id = 5) then concat(distributor_name, ' - ', u.employee_id,' (',r.name, ')') else
			concat(u.first_name, ' ', u.last_name, ' - ', u.employee_id, ' (', r.name,')') end as cName from user u
			INNER JOIN role r on r.role_id = u.role_id 
			LEFT JOIN distributor_details d ON d.user_id = u.user_id
			where u.user_id IN (".$users.") AND r.role_id IN (4,5)) t 
			WHERE t.cName like '%".$val."%' order by t.user_id limit 0,".$limit;
		$res = $this->db->query($q);
	    foreach($res->result_array() as $row)
	    {
	    	$data[] = array('id'=>$row['user_id'], 'text'=>$row['cName']);
	    }
	    return $data;
	}

	public function getColleagues($val)
	{
		$data = [];
		$limit = getDefaultSelect2Limit();
		//$limit = 50;
		$q = "SELECT t.user_id, t.cName from 
			(SELECT u.user_id, concat(u.first_name, ' ', u.last_name, ' - ', u.employee_id, ' (', r.name, ')') cName from user u
			INNER JOIN role r on r.role_id = u.role_id 
			WHERE u.status = 1 AND r.role_id NOT IN (1, 2, 3, 5, 12, 13, 14) AND u.user_id != '".$this->session->userdata('user_id')."') t 
			WHERE t.cName like '%".$val."%' order by t.user_id limit 0,".$limit;
		$res = $this->db->query($q);
	    foreach($res->result_array() as $row)
	    {
	    	$data[] = array('id'=>$row['user_id'], 'text'=>$row['cName']);
	    }
	    return $data;
	}

	public function getReporteesWithUser($val, $level = 0)
	{
		$roleQuery = '';
		if($level == 1) $roleQuery = ' r.role_id != 5 AND ';
		$data = [];
		$users = $this->session->userdata('reportees');
		$limit = getDefaultSelect2Limit();
		//$limit = 50;
		$q = "SELECT t.user_id, t.cName from 
			(SELECT u.user_id, case when (r.role_id = 5) then concat(distributor_name, ' - ', u.employee_id,' (',r.name, ')') else
			concat(u.first_name, ' ', u.last_name, ' - ', u.employee_id, ' (', r.name,')') end as cName from user u
			INNER JOIN role r on r.role_id = u.role_id 
			LEFT JOIN distributor_details d ON d.user_id = u.user_id
			where ".$roleQuery." u.user_id IN (".$users.", ".$this->session->userdata('user_id').")) t 
			WHERE t.cName like '%".$val."%' order by t.user_id limit 0,".$limit;
		$res = $this->db->query($q);
	    foreach($res->result_array() as $row)
	    {
	    	$data[] = array('id'=>$row['user_id'], 'text'=>$row['cName']);
	    }
	    return $data;
	}

	public function getUserProductReporteesWithUser($val, $level = 0)
	{
		$roleQuery = '';
		if($level == 1) $roleQuery = ' r.role_id != 5 AND ';
		$data = [];
		$users = $this->session->userdata('userProductReportees');
		if($users == '') $users = 0;
		$limit = getDefaultSelect2Limit();
		//$limit = 50;
		$q = "SELECT t.user_id, t.cName from 
			(SELECT u.user_id, case when (r.role_id = 5) then concat(distributor_name, ' - ', u.employee_id,' (',r.name, ')') else
			concat(u.first_name, ' ', u.last_name, ' - ', u.employee_id, ' (', r.name,')') end as cName from user u
			INNER JOIN role r on r.role_id = u.role_id 
			LEFT JOIN distributor_details d ON d.user_id = u.user_id
			where ".$roleQuery." u.user_id IN (".$users.", ".$this->session->userdata('user_id').")) t 
			WHERE t.cName like '%".$val."%' order by t.user_id limit 0,".$limit;
		$res = $this->db->query($q);
	    foreach($res->result_array() as $row)
	    {
	    	$data[] = array('id'=>$row['user_id'], 'text'=>$row['cName']);
	    }
	    return $data;
	}	

	//MAHESH 7TH JULY 2:55 pm
	public function getInactiveUsersWithOpenLeads($val)
	{
		$data = [];
		//$users = $this->session->userdata('reportees');
		$limit = getDefaultSelect2Limit();
		//$limit = 50;
		$q = "SELECT DISTINCT(t.user_id), t.cName from 
			(SELECT u.user_id, case when (r.role_id = 5) then concat(distributor_name, ' - ', u.employee_id,' (',r.name, ')') else
			concat(u.first_name, ' ', u.last_name, ' - ', u.employee_id, ' (', r.name,')') end as cName from user u
			INNER JOIN lead l ON l.user_id = u.user_id INNER JOIN role r ON r.role_id = u.role_id 
			LEFT JOIN distributor_details d ON d.user_id = u.user_id
			where l.status<20 AND (u.status=2 OR l.status=19)) t 
			WHERE t.cName like '%".$val."%' order by t.user_id limit 0,".$limit;
		$res = $this->db->query($q);
	    foreach($res->result_array() as $row)
	    {
	    	$data[] = array('id'=>$row['user_id'], 'text'=>$row['cName']);
	    }
	    return $data;
	}

	//MAHESH 7TH JULY 03:17 pm
	public function getActiveUsersToAssignLeads($val)
	{
		$data = [];
		//$users = $this->session->userdata('reportees');
		$limit = getDefaultSelect2Limit();
		$inc_user_roles = array(4,6,7,8,9);
		//$limit = 50;
		$q = "SELECT DISTINCT(t.user_id), t.cName from 
			(SELECT u.user_id, concat(u.first_name, ' ', u.last_name, ' - ', u.employee_id, ' (', r.name, ')') cName from user u
			INNER JOIN role r ON r.role_id = u.role_id where u.status = 1 AND u.role_id IN( ".implode(',', $inc_user_roles).")) t 
			WHERE t.cName like '%".$val."%' order by t.user_id limit 0,".$limit;
		$res = $this->db->query($q);
	    foreach($res->result_array() as $row)
	    {
	    	$data[] = array('id'=>$row['user_id'], 'text'=>$row['cName']);
	    }
	    return $data;
	}


}