<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Lead_model extends CI_Model {

	public function getCustomerInfo($loc)
	{
		$data = [];
		$this->db->select('c.customer_id, c.name, c.name1, l.location');
		$this->db->from('customer c');		
		$this->db->join('customer_location cl','cl.customer_id = c.customer_id');
		$this->db->join('location l','l.location_id = cl.location_id');
		if($loc!='')
		$this->db->where('cl.location_id IN ('.$loc.')');
		$this->db->where('l.status', 1);
		$this->db->where('c.status', 1);
		$res = $this->db->get();
		foreach($res->result_array() as $row)
		{
			$data[$row['customer_id']] = $row['name'].'-'.$row['name1'].'-'.$row['location'];
		}
		return $data;
	}

	public function leadAppTotalRows($searchParams)
	{
		$this->db->select('l.lead_id');
		$this->db->from('lead l');
		$this->db->join('customer c','c.customer_id = l.customer_id');
		$this->db->join('contact cn','cn.contact_id = l.contact_id');
		$this->db->join('source_of_lead s','s.source_id = l.source_id');
		$this->db->join('user u','u.user_id = l.user_id');
		$this->db->join('location l1','l1.location_id = l.location_id');
		$this->db->join('role r','r.role_id = u.role_id');
		$this->db->join('speciality sp','sp.speciality_id = cn.speciality_id');
		$this->db->where('l.company_id',$this->session->userdata('company'));
		if($searchParams['lead_id']!='')
		$this->db->where('l.lead_number',$searchParams['lead_id']);
		if($searchParams['customer']!='')
		$this->db->where('l.customer_id', $searchParams['customer']);
		if($searchParams['created_user']!='')
		$this->db->where('l.user_id', $searchParams['created_user']);
		$this->db->where('l.status', 1);
		$this->db->where('l.location_id IN ('.$this->session->userdata('locationString').')');
		$res = $this->db->get();
		return $res->num_rows();
	}


	public function leadAppRsults($searchParams, $per_page, $current_offset)
	{
		$this->db->select('l.lead_id as lead_id, concat(c.name, " (", l1.location, ")") as customer, 
			concat(cn.first_name," ",cn.last_name," - ",sp.name," (", cn.mobile_no, " - ", cn.email, ")" ) as contact, s.name as source, r.role_id,
			concat(u.first_name, " ", u.last_name, " - ", u.employee_id, " (", r.name, ")") as user, l.user_id, l.created_time,l.lead_number');
		$this->db->from('lead l');
		$this->db->join('customer c','c.customer_id = l.customer_id');
		$this->db->join('contact cn','cn.contact_id = l.contact_id');
		$this->db->join('source_of_lead s','s.source_id = l.source_id');
		$this->db->join('user u','u.user_id = l.user_id');
		$this->db->join('location l1','l1.location_id = l.location_id');
		$this->db->join('role r','r.role_id = u.role_id');
		$this->db->join('speciality sp','sp.speciality_id = cn.speciality_id');
		$this->db->where('l.company_id',$this->session->userdata('company'));
		if($searchParams['lead_id']!='')
		$this->db->where('l.lead_number',$searchParams['lead_id']);
		if($searchParams['customer']!='')
		$this->db->where('l.customer_id', $searchParams['customer']);
		if($searchParams['created_user']!='')
		$this->db->where('l.user_id', $searchParams['created_user']);
		$this->db->where('l.status', 1);
		$this->db->where('l.location_id IN ('.$this->session->userdata('locationString').')');
		$this->db->limit($per_page, $current_offset);
		$res = $this->db->get();
		$data = $res->result_array();
		return $data;
	}

	public function getSearchUser($user_id)
	{
		$q = "SELECT u.user_id, case when (u.role_id != 5) then
			concat(u.first_name, ' ', u.last_name, ' - ', u.employee_id, ' (', r.name, ')') 
			else concat(d.distributor_name, ' - ', u.employee_id, ' (', r.name, ')') end cName from user u
			INNER JOIN role r on r.role_id = u.role_id
			LEFT JOIN distributor_details d ON d.user_id = u.user_id where u.company_id='".$_SESSION['company']."' AND u.user_id = '".$user_id."'";
		$res = $this->db->query($q);
        if($res->num_rows() > 0)
        {
            $data = $res->result_array();
            return $data[0];
        }
        else
            return array('user_id' => '', 'cName' => 'Select Owner');
	}

	public function getApprovalLeaddata($lead_id)
	{
		$this->db->select('l.*, concat(c.name, " (", l1.location, ")") as customer, 
			concat(cn.first_name," ",cn.last_name," - ",sp.name," (", cn.mobile_no, ")" ) as contact, s.name as source, r.role_id,
			concat(u.first_name, " ", u.last_name, " - ", u.employee_id, " (", r.name, ")") as user');
		$this->db->from('lead l');
		$this->db->join('customer c','c.customer_id = l.customer_id');
		$this->db->join('contact cn','cn.contact_id = l.contact_id');
		$this->db->join('source_of_lead s','s.source_id = l.source_id');
		$this->db->join('user u','u.user_id = l.user_id');
		$this->db->join('location l1','l1.location_id = l.location_id');
		$this->db->join('role r','r.role_id = u.role_id');
		$this->db->join('speciality sp','sp.speciality_id = cn.speciality_id');
		$this->db->where('l.lead_id',$lead_id);
		$this->db->where('l.status', 1);
		$res = $this->db->get();
		if($res->num_rows() > 0)
		{
			$data = $res->result_array();
			return $data[0];
		}
		return array();
	}

	public function getLeadData($lead_id, $check = 1)
	{
		$this->db->select('l.*, concat(c.name, " (", l1.location, ")") as customer, 
			concat(cn.first_name," ",cn.last_name," - ",sp.name," (", cn.mobile_no, ")" ) as contact, s.name as source, r.role_id,
			concat(u.first_name, " ", u.last_name, " - ", u.employee_id, " (", r.name, ")") as user');
		$this->db->from('lead l');
		$this->db->join('customer c','c.customer_id = l.customer_id');
		$this->db->join('contact cn','cn.contact_id = l.contact_id');
		$this->db->join('source_of_lead s','s.source_id = l.source_id');
		$this->db->join('user u','u.user_id = l.user_id');
		$this->db->join('location l1','l1.location_id = l.location_id');
		$this->db->join('role r','r.role_id = u.role_id');
		$this->db->join('speciality sp','sp.speciality_id = cn.speciality_id');
		$this->db->where('l.lead_id',$lead_id);
		if($check == 1)
		$this->db->where('l.status < 20');
		else
		$this->db->where('l.status > 19');	
		$res = $this->db->get();
		if($res->num_rows() > 0)
		{
			$data = $res->result_array();
			return $data[0];
		}
		return array();
	}

	public function getSecUser($location_id, $checkRole)
	{
		$role_id = ($checkRole == 1)?4:5;
		$secondUser = ($checkRole == 1)? 'Sales User':'Distributor';
		if($role_id == 4) $role_id = '4,6,7,8,9,10,11';
		//$data = [];
		//$data[] = array('' => 'Select '.$secondUser);
		$data = array();
		$locations = getAllParents($location_id);
		if($locations == '') $locations = 0;
		$q = "SELECT u.user_id, case when (r.role_id = 5) then concat(distributor_name, ' - ', u.employee_id, ' (',r.name, ')') else
			concat(u.first_name, ' ', u.last_name, ' - ', u.employee_id, ' (', r.name,')') end as uName from user u
			INNER JOIN user_location ul on ul.user_id = u.user_id
			INNER JOIN role r ON r.role_id = u.role_id
			LEFT JOIN distributor_details d ON d.user_id = u.user_id
			WHERE u.status = 1 and u.company_id=".$this->session->userdata('company')." and ul.status = 1 and ul.location_id IN (".$locations.") and u.role_id IN (".$role_id.")";
		$res = $this->db->query($q);	
		//$options = "<option value=''>Select ".$secondUser."</option>";
	    foreach($res->result_array() as $row)
	    {
	    	$data[$row['user_id']] = $row['uName'];
	    }
	    return $data;
	}

	public function closedLeadResults($searchParams, $per_page, $current_offset)
	{
		$role_id = $this->session->userdata('role_id');
		$reportees = $this->session->userdata('reportees');
		$reportees = ($role_id == 4 || $role_id == 5)?0:$reportees;
		$this->db->select('l.*, concat(c.name, " (", l1.location, ")") as customer, 
			concat(cn.first_name," ",cn.last_name," - ",sp.name," (", cn.mobile_no, ")" ) as contact, s.name as source, r.role_id,
			concat(u.first_name, " ", u.last_name, " - ", u.employee_id, " (", r.name, ")") as user');
		$this->db->from('lead l');
		$this->db->join('customer c','c.customer_id = l.customer_id');
		$this->db->join('contact cn','cn.contact_id = l.contact_id');
		$this->db->join('source_of_lead s','s.source_id = l.source_id');
		$this->db->join('user u','u.user_id = l.user_id');
		$this->db->join('location l1','l1.location_id = l.location_id');
		$this->db->join('role r','r.role_id = u.role_id');
		$this->db->join('speciality sp','sp.speciality_id = cn.speciality_id');
		if($searchParams['lead_id']!='')
		$this->db->where('l.lead_number',$searchParams['lead_id']);
		if($searchParams['customer']!='')
		$this->db->where('l.customer_id', $searchParams['customer']);
		if($searchParams['created_user']!='')
		$this->db->where('l.user_id', $searchParams['created_user']);
		if($searchParams['closed_status']!='')
		$this->db->where('l.status', $searchParams['closed_status']);
		if(@$searchParams['start_date']!='')
		$this->db->where('DATE(l.created_time) >=', $searchParams['start_date']);
        if(@$searchParams['end_date']!='')
		$this->db->where('DATE(l.created_time)<=', $searchParams['end_date']);

		$this->db->where('l.status > 19');
		if($role_id != 4 && $role_id!=5)
		{
			$this->db->where('l.location_id IN ('.$this->session->userdata('locationString').')');
		}
		$this->db->where('u.user_id IN ('.$reportees.', '.$this->session->userdata('user_id').')');
		$this->db->where('l.company_id',$this->session->userdata('company'));
		$this->db->order_by('l.lead_id', 'DESC');
		$this->db->limit($per_page, $current_offset);
		$res = $this->db->get();
		$data = $res->result_array();
		return $data;
	}

	public function closedLeadTotalRows($searchParams)
	{
		$role_id = $this->session->userdata('role_id');
		$reportees = $this->session->userdata('reportees');
		$reportees = ($role_id == 4 || $role_id == 5)?0:$reportees;
		$this->db->select('l.lead_id');
		$this->db->from('lead l');
		$this->db->join('customer c','c.customer_id = l.customer_id');
		$this->db->join('contact cn','cn.contact_id = l.contact_id');
		$this->db->join('source_of_lead s','s.source_id = l.source_id');
		$this->db->join('user u','u.user_id = l.user_id');
		$this->db->join('location l1','l1.location_id = l.location_id');
		$this->db->join('role r','r.role_id = u.role_id');
		$this->db->join('speciality sp','sp.speciality_id = cn.speciality_id');
		if($searchParams['lead_id']!='')
		$this->db->where('l.lead_number',$searchParams['lead_id']);
		if($searchParams['customer']!='')
		$this->db->where('l.customer_id', $searchParams['customer']);
		if($searchParams['created_user']!='')
		$this->db->where('l.user_id', $searchParams['created_user']);
		if($searchParams['closed_status']!='')
		$this->db->where('l.status', $searchParams['closed_status']);
		if(@$searchParams['start_date']!='')
		$this->db->where('DATE(l.created_time) >=', $searchParams['start_date']);
        if(@$searchParams['end_date']!='')
		$this->db->where('DATE(l.created_time)<=', $searchParams['end_date']);

		$this->db->where('l.status > 19');
		if($role_id != 4 && $role_id!=5)
		{
			$this->db->where('l.location_id IN ('.$this->session->userdata('locationString').')');
		}
		$this->db->where('u.user_id IN ('.$reportees.', '.$this->session->userdata('user_id').')');
		$this->db->where('l.company_id',$this->session->userdata('company'));
		$this->db->order_by('l.lead_id', 'DESC');
		$res = $this->db->get();
		return $res->num_rows();
	}


	public function openLeadTotalRows($searchParams,$source_id='All')
	{
		$role_id = $this->session->userdata('role_id');
		$reportees = $this->session->userdata('reportees');
		$reportees = ($role_id == 4 || $role_id == 5)?0:$reportees;
		$this->db->select('count(*) as total_rows');
		$this->db->from('lead l');
		$this->db->join('customer c','c.customer_id = l.customer_id');
		$this->db->join('contact cn','cn.contact_id = l.contact_id');
		$this->db->join('source_of_lead s','s.source_id = l.source_id');
		$this->db->join('user u','u.user_id = l.user_id');
		$this->db->join('location l1','l1.location_id = l.location_id');
		$this->db->join('role r','r.role_id = u.role_id');
		$this->db->join('speciality sp','sp.speciality_id = cn.speciality_id');
		$this->db->where('l.company_id',$this->session->userdata('company'));
		if($searchParams['lead_id']!='')
		$this->db->where('l.lead_number',$searchParams['lead_id']);
		if($searchParams['customer']!='')
		$this->db->where('l.customer_id', $searchParams['customer']);
		if($searchParams['created_user']!='')
		$this->db->where('l.user_id', $searchParams['created_user']);
		if($searchParams['open_status']!='')
		$this->db->where('l.status', $searchParams['open_status']);
                if(@$searchParams['start_date']!='')
		$this->db->where('l.created_time >=', $searchParams['start_date']);
                if(@$searchParams['end_date']!='')
		$this->db->where('l.created_time<=', $searchParams['end_date']." 23:59:59");
		
                $this->db->where('l.status < 20');
                if($source_id ==2){
                $this->db->where('l.source_id',$source_id );    
                }
        //mahesh code: 21st sep 2016, new filters
        if(@$searchParams['campaign']!='')
		$this->db->where('l.campaign_id', $searchParams['campaign']);

		if($role_id != 4 && $role_id != 5)
		{
			$this->db->where('l.location_id IN ('.$this->session->userdata('locationString').')');
		}
		$this->db->where('u.user_id IN ('.$reportees.', '.$this->session->userdata('user_id').')');
		$res = $this->db->get();
		$result =  $res->row_array();
		return $result['total_rows'];
	}

	public function openLeadResults($searchParams, $per_page, $current_offset,$source_id='All')
	{
		$role_id = $this->session->userdata('role_id');
		$reportees = $this->session->userdata('reportees');
		$reportees = ($role_id == 4 || $role_id == 5)?0:$reportees;
		$this->db->select('l.*,l.modified_time, u.user_id, concat(c.name, " (", l1.location, ")") as customer, 
			concat(cn.first_name," ",cn.last_name," - ",sp.name," (", cn.mobile_no, ")" ) as contact, s.name as source, r.role_id,
			concat(u.first_name, " ", u.last_name, " - ", u.employee_id, " (", r.name, ")") as user');
		$this->db->from('lead l');
		$this->db->join('customer c','c.customer_id = l.customer_id');
		$this->db->join('contact cn','cn.contact_id = l.contact_id');
		$this->db->join('source_of_lead s','s.source_id = l.source_id');
		$this->db->join('user u','u.user_id = l.user_id');
		$this->db->join('location l1','l1.location_id = l.location_id');
		$this->db->join('role r','r.role_id = u.role_id');
		$this->db->join('speciality sp','sp.speciality_id = cn.speciality_id');
		$this->db->where('l.company_id',$this->session->userdata('company'));
		if($searchParams['lead_id']!='')
		$this->db->where('l.lead_number',$searchParams['lead_id']);
		if($searchParams['customer']!='')
		$this->db->where('l.customer_id', $searchParams['customer']);
		if($searchParams['created_user']!='')
		$this->db->where('l.user_id', $searchParams['created_user']);
		if($searchParams['open_status']!='')
		$this->db->where('l.status', $searchParams['open_status']);
                if(@$searchParams['start_date']!='')
		$this->db->where('l.created_time >=', $searchParams['start_date']);
                if(@$searchParams['end_date']!='')
		$this->db->where('l.created_time<=', $searchParams['end_date']." 23:59:59");
		$this->db->where('l.status < 20');
		if($role_id != 4 && $role_id!=5)
		{
			$this->db->where('l.location_id IN ('.$this->session->userdata('locationString').')');
		}
		$this->db->where('u.user_id IN ('.$reportees.', '.$this->session->userdata('user_id').')');
                if($source_id ==2){
                $this->db->where('l.source_id',$source_id );    
                }
        //mahesh code: 21st sep 2016, new filters
        if(@$searchParams['campaign']!='')
		$this->db->where('l.campaign_id', $searchParams['campaign']);

		$this->db->order_by('l.lead_id', 'DESC');
		$this->db->limit($per_page, $current_offset);
		$res = $this->db->get();
		$data = $res->result_array();
		//echo $this->db->last_query(); exit;
		return $data;
	}	

	public function opportunityResults($searchParams, $per_page, $current_offset, $check = 1)
	{
		// $statusValues = ($check == 2)?'(6,7,8)':'(1,2,3,4,5)';
		// added on 16-06-2021 for distributor role 
		$statusValues = ($check == 2)?'(6,7,8,10)':'(1,2,3,4,5)';
		// added on 16-06-2021 for distributor role end
		$role_id = $this->session->userdata('role_id');
		$reportees = $this->session->userdata('reportees');
		$reportees = ($role_id == 4 || $role_id == 5)?0:$reportees;
		// $this->db->select('o.opportunity_id, l.lead_id as lead_id, os.name as stage, o.created_time, o.status, o.required_quantity,o.opp_number,
		// 	u.user_id, concat("ID : ", l.lead_number, " - ", c.name, " ", c.name1, " (", l1.location, ")") as lead, p.dp,
		// 	concat(p.name, " (", p.description, ")") as product, o.status as status, o.expected_order_conclusion as oDate, 
		// 	concat(u.first_name, " ", u.last_name, " - ", u.employee_id, " (", r.name, ")") as user, l.user_id,
		// 	l.created_time as lCTime, o.created_time as oCTime,l.status as lead_status,l.modified_time as lead_mtime,o.modified_time as opp_mtime');

		$this->db->select('o.opportunity_id, l.lead_id as lead_id, os.name as stage, o.created_time, o.status, o.required_quantity,o.opp_number,
			u.user_id, concat("ID : ", l.lead_number, " - ", c.name, " ", c.name1, " (", l1.location, ")") as lead, p.dp,
			concat(p.name, " (", p.description, ")") as product, o.status as status, o.expected_order_conclusion as oDate, 
			concat(u.first_name, " ", u.last_name, " - ", u.employee_id, " (", r.name, ")") as user, l.user_id,
			l.created_time as lCTime, o.created_time as oCTime,l.status as lead_status,l.modified_time as lead_mtime,o.modified_time as opp_mtime,o.created_by');

		$this->db->from('opportunity o');
		$this->db->join('lead l', 'o.lead_id = l.lead_id');

		$this->db->join('opportunity_product op', 'op.opportunity_id = o.opportunity_id');
		$this->db->join('product p', 'p.product_id = op.product_id');
		$this->db->join('product_group pg', 'pg.group_id = p.group_id');
		$this->db->join('product_category pc','pg.category_id=pc.category_id');
		
		$this->db->join('opportunity_status os', 'os.status = o.status');

		$this->db->join('customer c','c.customer_id = l.customer_id');
		$this->db->join('contact cn','cn.contact_id = l.contact_id');
		$this->db->join('user u','u.user_id = l.user_id');
		$this->db->join('location l1','l1.location_id = l.location_id');
		$this->db->join('location l2','l2.location_id=l1.parent_id','left');
	    $this->db->join('location l3','l3.location_id=l2.parent_id','left');
	    $this->db->join('location l4','l4.location_id=l3.parent_id','left');
		$this->db->join('role r','r.role_id = u.role_id');
		$this->db->join('source_of_lead sol','l.source_id=sol.source_id');
		$this->db->join('source_of_funds sof','o.fund_source_id=sof.fund_source_id');
		$this->db->join('site_readiness sr','l.site_readiness_id=sr.site_readiness_id');
		$this->db->join('relationship rs','o.relationship_id=rs.relationship_id');
		if($searchParams['opportunity_id']!='')
		$this->db->where('o.opp_number',$searchParams['opportunity_id']);
		if($searchParams['customer']!='')
		$this->db->where('l.customer_id', $searchParams['customer']);
		if($searchParams['created_user']!='')
		$this->db->where('l.user_id', $searchParams['created_user']);
		if($searchParams['opp_status']!='')
		$this->db->where('o.status', $searchParams['opp_status']);
		//if($searchParams['product_id']!='')
		//$this->db->where('p.product_id', $searchParams['product_id']);
        if(count($searchParams['product_id']) > 0)
        {
        	//$products = implode(",", $searchParams['product_id']);
        	if(@$searchParams['product_id'][0] != '')
			$this->db->where_in('pg.category_id', $searchParams['product_id']);
        }

        if($searchParams['start_date']!='')
		$this->db->where('o.created_time >=', $searchParams['start_date']);
        if($searchParams['end_date']!='')
		$this->db->where('o.created_time<=', $searchParams['end_date']." 23:59:59");
	    if($searchParams['order_start_date']!='')
		$this->db->where('o.expected_order_conclusion >=', $searchParams['order_start_date']);
                if($searchParams['order_end_date']!='')
		$this->db->where('o.expected_order_conclusion<=', $searchParams['order_end_date']." 23:59:59");
        //mahesh code: 21st sep 2016, new filters
        if($searchParams['source_of_lead']!='')
		$this->db->where('l.source_id',$searchParams['source_of_lead']);

		if($searchParams['region_id']!=''){
			$this->db->where('l3.parent_id',$searchParams['region_id']);
			
		}
		if(@$searchParams['opp_category'] != '')
		{
			$month = date('m');
			$month1 = $month + 1;
			$year = date('Y');		
			$day = getOpportunityCategorizationDate();
			$hotDay = $year."-".$month."-".$day;
			//$hotDate = "2016-12-28";
			$warmDate = date('Y-m-d',strtotime( $hotDay . " +1 month " ));
			switch(@$searchParams['opp_category'])
			{
				case 1:
					$this->db->where('o.expected_order_conclusion <= ', $hotDay);
					break;
				case 2:
					$this->db->where('o.expected_order_conclusion > ', $hotDay);
					$this->db->where('o.expected_order_conclusion <= ', $warmDate);
					break;
				default:
					$this->db->where('o.expected_order_conclusion > ', $warmDate);
					break;
			}
		}
		if($searchParams['search_option']!='' && $searchParams['text_search']!='' )
		{   
			$search_text=$searchParams['text_search'];
			switch($searchParams['search_option'])
			{
				default :
					   $where='(l.lead_number like "%'.$search_text.'%" OR o.opportunity_number like "%'.$search_text.'%" OR p.name like "%'.$search_text.'%" OR  p.description like "%'.$search_text.'%"  OR TRIM(c.name) like "%'.$search_text.'%" OR  pg.name like "%'.$search_text.'%" OR  pc.name like "%'.$search_text.'%" OR  os.name like "%'.$search_text.'%" OR  r.name like "%'.$search_text.'%" OR  cn.first_name like "%'.$search_text.'%" OR  cn.last_name like "%'.$search_text.'%" OR  u.first_name like "%'.$search_text.'%" OR  u.last_name like "%'.$search_text.'%" OR  sol.name like "%'.$search_text.'%" OR  sof.name like "%'.$search_text.'%" OR sr.name like "%'.$search_text.'%" OR rs.name like "%'.$search_text.'%" OR l1.location like "%'.$search_text.'%" OR l2.location like "%'.$search_text.'%" OR l3.location like "%'.$search_text.'%" OR l4.location like "%'.$search_text.'%" )';
					       $this->db->where($where);
			    break;
			    case "lead":
					    $where='l.lead_number like "%'.$search_text.'%" ';
					    $this->db->where($where);
					    break;
			    case "opp":
						$where='o.opportunity_number like "%'.$search_text.'%" ';
					    $this->db->where($where);
					    break;
                case "product":
			    		$where='p.name like "%'.$search_text.'%" ';
					    $this->db->where($where);
					    break;
			    case "description":
			    		$where='p.description like "%'.$search_text.'%" ';
					    $this->db->where($where);
					    break; 
				case "customer":
			    		$where='TRIM(c.name) like "%'.$search_text.'%" ';
					    $this->db->where($where);
					    break; 
			    case "segment":
			    		$where='pg.name like "%'.$search_text.'%" ';
					    $this->db->where($where);
					    break;
			    case "category":
			    		$where='pc.name like "%'.$search_text.'%" ';
					    $this->db->where($where);
					    break;
			    case "status":
			    		$where='os.name like "%'.$search_text.'%" ';
					    $this->db->where($where);
					    break;
			    case "role":
			    		$where='r.name like "%'.$search_text.'%" ';
					    $this->db->where($where);
					    break;
			     case "contact":
			    		$where='cn.first_name like "%'.$search_text.'%" OR  cn.last_name like "%'.$search_text.'%"';
					    $this->db->where($where);
					    break;
				case "user":
			    		$where='u.first_name like "%'.$search_text.'%" OR  u.last_name like "%'.$search_text.'%"';
					    $this->db->where($where);
					    break;
			    case "sol":
			    		$where='sol.name like "%'.$search_text.'%" ';
					    $this->db->where($where);
					    break;
			    case "sof":
			    		$where='sof.name like "%'.$search_text.'%" ';
					    $this->db->where($where);
					    break;
			    case "sitereadiness":
			    		$where='sr.name like "%'.$search_text.'%" ';
					    $this->db->where($where);
					    break;
			     case "relationship":
			    		$where='rs.name like "%'.$search_text.'%" ';
					    $this->db->where($where);
					    break;
				 case "city":
			    		$where='l1.location like "%'.$search_text.'%" ';
					    $this->db->where($where);
					    break;
				case "district":
			    		$where='l2.location like "%'.$search_text.'%" ';
					    $this->db->where($where);
					    break;
			    case "state":
			    		$where='l3.location like "%'.$search_text.'%" ';
					    $this->db->where($where);
					    break;
			    case "region":
			    		$where='l4.location like "%'.$search_text.'%" ';
					    $this->db->where($where);
					    break;

			}
		}
		
		if($role_id==4||$role_id==5) // If SE,Dealer
		{
			$this->db->where('l.user_id',$this->session->userdata('user_id'));
		}
		else
		{
			$this->db->where('l.location_id IN ('.$this->session->userdata('locationString').')');
			$this->db->where('op.product_id IN ('.$this->session->userdata('products').')');
		}
		//$this->db->where('u.user_id IN ('.$reportees.', '.$this->session->userdata('user_id').')');
		$this->db->where('o.status IN '.$statusValues);
		$this->db->where('o.company_id',$this->session->userdata('company'));
		$this->db->order_by('o.opportunity_id', 'DESC');
		$this->db->limit($per_page, $current_offset);
		$res = $this->db->get();

        //return $res;

		$data = $res->result_array();
		// echo $this->db->last_query(); exit;
		return $data;
	}

	public function opportunityRows($searchParams, $check = 1)
	{
		// $statusValues = ($check == 2)?'(6,7,8)':'(1,2,3,4,5)';
		// added on 16-06-2021 for distributor role 
		$statusValues = ($check == 2)?'(6,7,8,10)':'(1,2,3,4,5)';
		// added on 16-06-2021 for distributor role end
		$role_id = $this->session->userdata('role_id');
		$reportees = $this->session->userdata('reportees');
		$reportees = ($role_id == 4 || $role_id == 5)?0:$reportees;
		$this->db->select('o.opportunity_id');
		$this->db->from('opportunity o');
		$this->db->join('lead l', 'o.lead_id = l.lead_id');

		$this->db->join('opportunity_product op', 'op.opportunity_id = o.opportunity_id');
		$this->db->join('product p', 'p.product_id = op.product_id');
		$this->db->join('opportunity_status os', 'os.status = o.status');
		$this->db->join('product_group pg', 'pg.group_id = p.group_id');
		$this->db->join('product_category pc','pg.category_id=pc.category_id');
		$this->db->join('customer c','c.customer_id = l.customer_id');
		$this->db->join('contact cn','cn.contact_id = l.contact_id');
		$this->db->join('user u','u.user_id = l.user_id');
		$this->db->join('location l1','l1.location_id = l.location_id');
		$this->db->join('location l2','l2.location_id=l1.parent_id','left');
	    $this->db->join('location l3','l3.location_id=l2.parent_id','left');
	    $this->db->join('location l4','l4.location_id=l3.parent_id','left');
		$this->db->join('role r','r.role_id = u.role_id');
		$this->db->join('source_of_lead sol','l.source_id=sol.source_id');
		$this->db->join('source_of_funds sof','o.fund_source_id=sof.fund_source_id');
		$this->db->join('site_readiness sr','l.site_readiness_id=sr.site_readiness_id');
		$this->db->join('relationship rs','o.relationship_id=rs.relationship_id');
		if($searchParams['opportunity_id']!='')
		$this->db->where('o.opp_number',$searchParams['opportunity_id']);
		if($searchParams['customer']!='')
		$this->db->where('l.customer_id', $searchParams['customer']);
		if($searchParams['created_user']!='')
		$this->db->where('l.user_id', $searchParams['created_user']);
		if($searchParams['opp_status']!='')
		$this->db->where('o.status', $searchParams['opp_status']);
		//if($searchParams['product_id']!='')
		//$this->db->where('p.product_id', $searchParams['product_id']);
        if(count($searchParams['product_id']) > 0)
        {
        	//$products = implode(",", $searchParams['product_id']);
        	if(@$searchParams['product_id'][0] != '')
			$this->db->where_in('pg.category_id', $searchParams['product_id']);
        }
                if($searchParams['start_date']!='')
		$this->db->where('o.created_time >=', $searchParams['start_date']);
                if($searchParams['end_date']!='')
		$this->db->where('o.created_time<=', $searchParams['end_date']." 23:59:59");
	   if($searchParams['order_start_date']!='')
		$this->db->where('o.expected_order_conclusion >=', $searchParams['order_start_date']);
        if($searchParams['order_end_date']!='')
		$this->db->where('o.expected_order_conclusion<=', $searchParams['order_end_date']." 23:59:59");

		//mahesh code: 21st sep 2016, new filters
        if($searchParams['source_of_lead']!='')
		$this->db->where('l.source_id',$searchParams['source_of_lead']);

		if($searchParams['region_id']!=''){
			$this->db->where('l3.parent_id',$searchParams['region_id']);
			
		}

		if(@$searchParams['opp_category'] != '')
		{
			$month = date('m');
			$month1 = $month + 1;
			$year = date('Y');		
			$day = getOpportunityCategorizationDate();
			$hotDay = $year."-".$month."-".$day;
			//$hotDate = "2016-12-28";
			$warmDate = date('Y-m-d',strtotime( $hotDay . " +1 month " ));
			switch(@$searchParams['opp_category'])
			{
				case 1:
					$this->db->where('o.expected_order_conclusion <= ', $hotDay);
					break;
				case 2:
					$this->db->where('o.expected_order_conclusion > ', $hotDay);
					$this->db->where('o.expected_order_conclusion <= ', $warmDate);
					break;
				default:
					$this->db->where('o.expected_order_conclusion > ', $warmDate);
					break;
			}
		}		
		if($searchParams['search_option']!='' && $searchParams['text_search']!='' )
		{   
			$search_text=$searchParams['text_search'];
			switch($searchParams['search_option'])
			{
				default:
					   $where='(l.lead_number like "%'.$search_text.'%" OR o.opportunity_number like "%'.$search_text.'%" OR p.name like "%'.$search_text.'%" OR  p.description like "%'.$search_text.'%"  OR TRIM(c.name) like "%'.$search_text.'%" OR  pg.name like "%'.$search_text.'%" OR  pc.name like "%'.$search_text.'%" OR  os.name like "%'.$search_text.'%" OR  r.name like "%'.$search_text.'%" OR  cn.first_name like "%'.$search_text.'%" OR  cn.last_name like "%'.$search_text.'%" OR  u.first_name like "%'.$search_text.'%" OR  u.last_name like "%'.$search_text.'%" OR  sol.name like "%'.$search_text.'%" OR  sof.name like "%'.$search_text.'%" OR sr.name like "%'.$search_text.'%" OR rs.name like "%'.$search_text.'%" OR l1.location like "%'.$search_text.'%" OR l2.location like "%'.$search_text.'%" OR l3.location like "%'.$search_text.'%" OR l4.location like "%'.$search_text.'%")';
					       $this->db->where($where);
			    break;
			    case "lead":
					    $where='l.lead_number like "%'.$search_text.'%" ';
					    $this->db->where($where);
					    break;
			    case "opp":
						$where='o.opportunity_number like "%'.$search_text.'%" ';
					    $this->db->where($where);
					    break;
                case "product":
			    		$where='p.name like "%'.$search_text.'%" ';
					    $this->db->where($where);
					    break;
			    case "description":
			    		$where='p.description like "%'.$search_text.'%" ';
					    $this->db->where($where);
					    break; 
				case "customer":
			    		$where='TRIM(c.name) like "%'.$search_text.'%" ';
					    $this->db->where($where);
					    break; 
			    case "segment":
			    		$where='pg.name like "%'.$search_text.'%" ';
					    $this->db->where($where);
					    break;
			    case "category":
			    		$where='pc.name like "%'.$search_text.'%" ';
					    $this->db->where($where);
					    break;
			    case "status":
			    		$where='os.name like "%'.$search_text.'%" ';
					    $this->db->where($where);
					    break;
			    case "role":
			    		$where='r.name like "%'.$search_text.'%" ';
					    $this->db->where($where);
					    break;
			     case "contact":
			    		$where='cn.first_name like "%'.$search_text.'%" OR  cn.last_name like "%'.$search_text.'%"';
					    $this->db->where($where);
					    break;
				case "user":
			    		$where='u.first_name like "%'.$search_text.'%" OR  u.last_name like "%'.$search_text.'%"';
					    $this->db->where($where);
					    break;
			    case "sol":
			    		$where='sol.name like "%'.$search_text.'%" ';
					    $this->db->where($where);
					    break;
			    case "sof":
			    		$where='sof.name like "%'.$search_text.'%" ';
					    $this->db->where($where);
					    break;
			    case "sitereadiness":
			    		$where='sr.name like "%'.$search_text.'%" ';
					    $this->db->where($where);
					    break;
			     case "relationship":
			    		$where='rs.name like "%'.$search_text.'%" ';
					    $this->db->where($where);
					    break;
				 case "city":
			    		$where='l1.location like "%'.$search_text.'%" ';
					    $this->db->where($where);
					    break;
				case "district":
			    		$where='l2.location like "%'.$search_text.'%" ';
					    $this->db->where($where);
					    break;
			    case "state":
			    		$where='l3.location like "%'.$search_text.'%" ';
					    $this->db->where($where);
					    break;
			    case "region":
			    		$where='l4.location like "%'.$search_text.'%" ';
					    $this->db->where($where);
					    break;

			}
		}
		
		if($role_id==4||$role_id==5) // If SE,Dealer
		{
			$this->db->where('l.user_id',$this->session->userdata('user_id'));
		}
		else
		{
			$this->db->where('l.location_id IN ('.$this->session->userdata('locationString').')');
			$this->db->where('op.product_id IN ('.$this->session->userdata('products').')');
		}
		$this->db->where('o.company_id',$this->session->userdata('company'));
		$this->db->where('o.status IN '.$statusValues);
		//$this->db->where('u.user_id IN ('.$reportees.', '.$this->session->userdata('user_id').')');
		$res = $this->db->get();
		return $res->num_rows();
	}	


	// mahesh 7th july 11:08 am
	public function inActiveUserLeadsTotalRows($searchParams)
	{
		$role_id = $this->session->userdata('role_id');
		$reportees = $this->session->userdata('reportees');
		$reportees = ($role_id == 4 || $role_id == 5)?0:$reportees;
		$this->db->select('l.lead_id');
		$this->db->from('lead l');
		$this->db->join('customer c','c.customer_id = l.customer_id');
		$this->db->join('contact cn','cn.contact_id = l.contact_id');
		$this->db->join('source_of_lead s','s.source_id = l.source_id');
		$this->db->join('user u','u.user_id = l.user_id');
		$this->db->join('location l1','l1.location_id = l.location_id');
		$this->db->join('location l2','l2.location_id = l1.parent_id');
		$this->db->join('location l3','l3.location_id = l2.parent_id');
		$this->db->join('location l4','l4.location_id = l3.parent_id');
		if($searchParams['s_region']!='')
		$this->db->where('l4.location_id',$searchParams['s_region']);
		if($searchParams['s_state']!='')
		$this->db->where('l3.location_id',$searchParams['s_state']);
		if($searchParams['s_district']!='')
		$this->db->where('l2.location_id',$searchParams['s_district']);
		if($searchParams['s_city']!='')
		$this->db->where('l1.location_id',$searchParams['s_city']);
		$this->db->join('role r','r.role_id = u.role_id');
		$this->db->join('speciality sp','sp.speciality_id = cn.speciality_id');
		if($searchParams['lead_id']!='')
		$this->db->where('l.lead_id',$searchParams['lead_id']);
		if($searchParams['lead_number']!='')
		$this->db->where('l.lead_number',$searchParams['lead_number']);
		if($searchParams['customer']!='')
		$this->db->where('l.customer_id', $searchParams['customer']);
		if($searchParams['created_user']!='')
		$this->db->where('l.user_id', $searchParams['created_user']);
		if($searchParams['open_status']!='')
		$this->db->where('l.status', $searchParams['open_status']);
		$this->db->where('l.status < 20');
		$this->db->where('l.company_id',$this->session->userdata('company'));
		$where = '(u.status=2 OR l.status=19)';
		$this->db->where($where);
		//$this->db->where('u.status',2);
		//$this->db->where('l.location_id IN ('.$this->session->userdata('locationString').')');
		//$this->db->where('u.user_id IN ('.$reportees.', '.$this->session->userdata('user_id').')');
		$res = $this->db->get();
		return $res->num_rows();
	}


	public function inActiveUserLeadsResults($searchParams, $per_page, $current_offset)
	{
		$role_id = $this->session->userdata('role_id');
		$reportees = $this->session->userdata('reportees');
		$reportees = ($role_id == 4 || $role_id == 5)?0:$reportees;
		$this->db->select('l.lead_id as lead_id,l.lead_number as lead_number, u.user_id, concat(c.name, " (", l1.location, ")") as customer, 
			concat(cn.first_name," ",cn.last_name," - ",sp.name," (", cn.mobile_no, ")" ) as contact, s.name as source, r.role_id,
			concat(u.first_name, " ", u.last_name, " - ", u.employee_id, " (", r.name, ")") as user, l.created_time, l.status,cl.location_id');
		$this->db->from('lead l');
		$this->db->join('customer c','c.customer_id = l.customer_id');
		$this->db->join('contact cn','cn.contact_id = l.contact_id');
		$this->db->join('source_of_lead s','s.source_id = l.source_id');
		$this->db->join('user u','u.user_id = l.user_id');
		$this->db->join('location l1','l1.location_id = l.location_id');
		$this->db->join('location l2','l2.location_id = l1.parent_id');
		$this->db->join('location l3','l3.location_id = l2.parent_id');
		$this->db->join('location l4','l4.location_id = l3.parent_id');
		if($searchParams['s_region']!='')
		$this->db->where('l4.location_id',$searchParams['s_region']);
		if($searchParams['s_state']!='')
		$this->db->where('l3.location_id',$searchParams['s_state']);
		if($searchParams['s_district']!='')
		$this->db->where('l2.location_id',$searchParams['s_district']);
		if($searchParams['s_city']!='')
		$this->db->where('l1.location_id',$searchParams['s_city']);

		$this->db->join('role r','r.role_id = u.role_id');
		$this->db->join('speciality sp','sp.speciality_id = cn.speciality_id');
		$this->db->join('customer_location cl','c.customer_id = cl.customer_id');
		if($searchParams['lead_id']!='')
		$this->db->where('l.lead_id',$searchParams['lead_id']);
		if($searchParams['lead_number']!='')
		$this->db->where('l.lead_number',$searchParams['lead_number']);
		if($searchParams['customer']!='')
		$this->db->where('l.customer_id', $searchParams['customer']);
		if($searchParams['created_user']!='')
		$this->db->where('l.user_id', $searchParams['created_user']);
		if($searchParams['open_status']!='')
		$this->db->where('l.status', $searchParams['open_status']);
		$this->db->where('l.status < 20');
		$this->db->where('l.company_id',$this->session->userdata('company'));
		$where = '(u.status=2 OR l.status=19)';
		$this->db->where($where);
		//$this->db->where('u.status',2);
		//$this->db->where('l.location_id IN ('.$this->session->userdata('locationString').')');
		//$this->db->where('u.user_id IN ('.$reportees.', '.$this->session->userdata('user_id').')');
		$this->db->order_by('l.lead_id', 'DESC');
		$this->db->limit($per_page, $current_offset);
		$res = $this->db->get();
		$data = $res->result_array();
		//echo $this->db->last_query();
		//print_r($searchParams);
		return $data;

	}

	//mahesh 15th july 0:48 am
	/*modified by prasad */
	public function download_opportunityResults($searchParams,  $check = 1)
	{
		// $statusValues = ($check == 2)?'(6,7,8)':'(1,2,3,4,5)';
		// added on 23-07-2021 for distributor role
		$statusValues = ($check == 2)?'(6,7,8,10)':'(1,2,3,4,5)';
		// added on 23-07-2021 for distributor role end
		$role_id = $this->session->userdata('role_id');
		$reportees = $this->session->userdata('reportees');
		$reportees = ($role_id == 4 || $role_id == 5)?0:$reportees;
		$select_str = 'o.opportunity_id, o.opp_number, l.lead_id as lead_id, l.lead_number as lead_number, os.name as stage, o.created_time, o.status,l.status as lead_status, l1.location_id, o.required_quantity,c.name as lead_name ,l1.location as lead_location,
			u.user_id, concat("ID : ", l.lead_id, " - ", c.name, " (", l1.location, ")") as lead, so.name as source, p.dp,p.name as product_name,p.description as pro_des,
			concat(p.name, " (", p.description, ")") as product, o.status as status, o.expected_order_conclusion as oDate,concat(u.first_name," ",u.last_name) as user_name,u.employee_id as emp_id ,r.name as role_name,
			concat(u.first_name, " ", u.last_name, " - ", u.employee_id, " (", r.name, ")") as user, l.user_id,
			l.created_time as lCTime, o.created_time as oCTime,l.modified_time as lead_mtime,o.modified_time as opp_mtime,o.decision_maker1,o.remarks2, pc.name as product_category,sr.name as readiness,sof.name as source_of_fund,pg.name as segment';
		if($check==2) // Closed opportunities
		{
			$select_str .= ',cn1.So_number as so_number';
		}
		$this->db->select($select_str);
		$this->db->from('opportunity o');
		$this->db->join('lead l', 'o.lead_id = l.lead_id');
		$this->db->join('source_of_lead so', 'so.source_id = l.source_id');

		$this->db->join('opportunity_product op', 'op.opportunity_id = o.opportunity_id');
		$this->db->join('product p', 'p.product_id = op.product_id');
		$this->db->join('product_group pg', 'pg.group_id = p.group_id');
		$this->db->join('product_category pc', 'pc.category_id = pg.category_id');
		$this->db->join('opportunity_status os', 'os.status = o.status');

		$this->db->join('customer c','c.customer_id = l.customer_id');
		$this->db->join('contact cn','cn.contact_id = l.contact_id');
		$this->db->join('user u','u.user_id = l.user_id');
		$this->db->join('location l1','l1.location_id = l.location_id');
		$this->db->join('location l2','l2.location_id=l1.parent_id','left');
	    $this->db->join('location l3','l3.location_id=l2.parent_id','left');
	    $this->db->join('location l4','l4.location_id=l3.parent_id','left');
		$this->db->join('role r','r.role_id = u.role_id');
		$this->db->join('source_of_lead sol','l.source_id=sol.source_id');
		$this->db->join('source_of_funds sof','o.fund_source_id=sof.fund_source_id');
		$this->db->join('site_readiness sr','l.site_readiness_id=sr.site_readiness_id');
		$this->db->join('relationship rs','o.relationship_id=rs.relationship_id');
		if($check==2) // Closed opportunities
		{
			$this->db->join('quote_details qd','qd.opportunity_id = o.opportunity_id','LEFT');
			$this->db->join('quote_revision qr','qd.quote_id = qr.quote_id','LEFT');
			$this->db->join('contract_note_quote_revision cnqr','cnqr.quote_revision_id = qr.quote_revision_id','LEFT');
			$this->db->join('contract_note cn1','cn1.contract_note_id = cnqr.contract_note_id','LEFT');
			$this->db->group_by('o.opportunity_id');
		}
		if($searchParams['opportunity_id']!='')
		$this->db->where('o.opportunity_id',$searchParams['opportunity_id']);
		if($searchParams['customer']!='')
		$this->db->where('l.customer_id', $searchParams['customer']);
		if($searchParams['created_user']!='')
		$this->db->where('l.user_id', $searchParams['created_user']);
		if($searchParams['opp_status']!='')
		$this->db->where('o.status', $searchParams['opp_status']);
		//if($searchParams['product_id']!='')
		//$this->db->where('p.product_id', $searchParams['product_id']);
        if(count($searchParams['product_id']) > 0)
        {
        	//$products = implode(",", $searchParams['product_id']);
        	if(@$searchParams['product_id'][0] != '')
			$this->db->where_in('pg.category_id', $searchParams['product_id']);
        }
                if($searchParams['start_date']!='')
		$this->db->where('o.created_time >=', $searchParams['start_date']);
                if($searchParams['end_date']!='')
		$this->db->where('o.created_time<=', $searchParams['end_date']." 23:59:59");
         if($searchParams['order_start_date']!='')
		$this->db->where('o.expected_order_conclusion >=', $searchParams['order_start_date']);
        if($searchParams['order_end_date']!='')
		$this->db->where('o.expected_order_conclusion<=', $searchParams['order_end_date']." 23:59:59");
		//mahesh code: 21st sep 2016, new filters
        if($searchParams['source_of_lead']!='')
		$this->db->where('l.source_id',$searchParams['source_of_lead']);

		if($searchParams['region_id']!=''){
			$this->db->where('l3.parent_id',$searchParams['region_id']);
			
		}

		if(@$searchParams['opp_category'] != '')
		{
			$month = date('m');
			$month1 = $month + 1;
			$year = date('Y');		
			$day = getOpportunityCategorizationDate();
			$hotDay = $year."-".$month."-".$day;
			//$hotDate = "2016-12-28";
			$warmDate = date('Y-m-d',strtotime( $hotDay . " +1 month " ));
			switch(@$searchParams['opp_category'])
			{
				case 1:
					$this->db->where('o.expected_order_conclusion <= ', $hotDay);
					break;
				case 2:
					$this->db->where('o.expected_order_conclusion > ', $hotDay);
					$this->db->where('o.expected_order_conclusion <= ', $warmDate);
					break;
				default:
					$this->db->where('o.expected_order_conclusion > ', $warmDate);
					break;
			}
		}
		if($searchParams['search_option']!='' && $searchParams['text_search']!='' )
		{   
			$search_text=$searchParams['text_search'];
			switch($searchParams['search_option'])
			{
				default:
					   $where='(l.lead_id like "%'.$search_text.'%" OR o.opportunity_id like "%'.$search_text.'%" OR p.name like "%'.$search_text.'%" OR  p.description like "%'.$search_text.'%"  OR TRIM(c.name) like "%'.$search_text.'%" OR  pg.name like "%'.$search_text.'%" OR  pc.name like "%'.$search_text.'%" OR  os.name like "%'.$search_text.'%" OR  r.name like "%'.$search_text.'%" OR  cn.first_name like "%'.$search_text.'%" OR  cn.last_name like "%'.$search_text.'%" OR  u.first_name like "%'.$search_text.'%" OR  u.last_name like "%'.$search_text.'%" OR  sol.name like "%'.$search_text.'%" OR  sof.name like "%'.$search_text.'%" OR sr.name like "%'.$search_text.'%" OR rs.name like "%'.$search_text.'%" OR l1.location like "%'.$search_text.'%" OR l2.location like "%'.$search_text.'%" OR l3.location like "%'.$search_text.'%" OR l4.location like "%'.$search_text.'%") ';
			               $this->db->where($where);
			    break;
			    case "lead":
					    $where='l.lead_id like "%'.$search_text.'%" ';
					    $this->db->where($where);
					    break;
			    case "opp":
			    		$where='o.opportunity_id like "%'.$search_text.'%" ';
					    $this->db->where($where);
					    break;
                case "product":
			    		$where='p.name like "%'.$search_text.'%" ';
					    $this->db->where($where);
					    break;
			    case "description":
			    		$where='p.description like "%'.$search_text.'%" ';
					    $this->db->where($where);
					    break; 
				case "customer":
			    		$where='TRIM(c.name) like "%'.$search_text.'%" ';
					    $this->db->where($where);
					    break; 
			    case "segment":
			    		$where='pg.name like "%'.$search_text.'%" ';
					    $this->db->where($where);
					    break;
			    case "category":
			    		$where='pc.name like "%'.$search_text.'%" ';
					    $this->db->where($where);
					    break;
			    case "status":
			    		$where='os.name like "%'.$search_text.'%" ';
					    $this->db->where($where);
					    break;
			    case "role":
			    		$where='r.name like "%'.$search_text.'%" ';
					    $this->db->where($where);
					    break;
			     case "contact":
			    		$where='cn.first_name like "%'.$search_text.'%" OR  cn.last_name like "%'.$search_text.'%"';
					    $this->db->where($where);
					    break;
				case "user":
			    		$where='u.first_name like "%'.$search_text.'%" OR  u.last_name like "%'.$search_text.'%"';
					    $this->db->where($where);
					    break;
			    case "sol":
			    		$where='sol.name like "%'.$search_text.'%" ';
					    $this->db->where($where);
					    break;
			    case "sof":
			    		$where='sof.name like "%'.$search_text.'%" ';
					    $this->db->where($where);
					    break;
			    case "sitereadiness":
			    		$where='sr.name like "%'.$search_text.'%" ';
					    $this->db->where($where);
					    break;
			     case "relationship":
			    		$where='rs.name like "%'.$search_text.'%" ';
					    $this->db->where($where);
					    break;
				 case "city":
			    		$where='l1.location like "%'.$search_text.'%" ';
					    $this->db->where($where);
					    break;
				case "district":
			    		$where='l2.location like "%'.$search_text.'%" ';
					    $this->db->where($where);
					    break;
			    case "state":
			    		$where='l3.location like "%'.$search_text.'%" ';
					    $this->db->where($where);
					    break;
			    case "region":
			    		$where='l4.location like "%'.$search_text.'%" ';
					    $this->db->where($where);
					    break;

			}
		}

		if($role_id==4||$role_id==5) // If SE,Dealer
		{
			$this->db->where('l.user_id',$this->session->userdata('user_id'));
		}
		else
		{
			$this->db->where('l.location_id IN ('.$this->session->userdata('locationString').')');
			$this->db->where('op.product_id IN ('.$this->session->userdata('products').')');
		}
		//$this->db->where('u.user_id IN ('.$reportees.', '.$this->session->userdata('user_id').')');
		$this->db->where('o.company_id',$this->session->userdata('company'));
		$this->db->where('o.status IN '.$statusValues);
		$this->db->order_by('o.opportunity_id', 'DESC');
		$res = $this->db->get();
		$data = $res->result_array();
		return $data;
	}		

	//mahesh 15th july 2016 08:22 PM
	public function all_openLeadResults($searchParams,$source_id='All')
	{
		$role_id = $this->session->userdata('role_id');
		$reportees = $this->session->userdata('reportees');
		$reportees = ($role_id == 4 || $role_id == 5)?0:$reportees;
		$this->db->select('l.lead_id as lead_id, u.user_id, concat(c.name, " (", l1.location, ")") as customer, l.campaign_id,
			concat(cn.first_name," ",cn.last_name," - ",sp.name," (", cn.mobile_no, ")" ) as contact, s.name as source, r.role_id,
			concat(u.first_name, " ", u.last_name, " - ", u.employee_id, " (", r.name, ")") as user, l.created_time,l.modified_time, l.status,l.created_by,l.user2,l.lead_number');
		$this->db->from('lead l');
		$this->db->join('customer c','c.customer_id = l.customer_id');
		$this->db->join('contact cn','cn.contact_id = l.contact_id');
		$this->db->join('source_of_lead s','s.source_id = l.source_id');
		$this->db->join('user u','u.user_id = l.user_id');
		$this->db->join('location l1','l1.location_id = l.location_id');
		$this->db->join('role r','r.role_id = u.role_id');
		$this->db->join('speciality sp','sp.speciality_id = cn.speciality_id');
		if($searchParams['lead_id']!='')
		$this->db->where('l.lead_number',$searchParams['lead_id']);
		if($searchParams['customer']!='')
		$this->db->where('l.customer_id', $searchParams['customer']);
		if($searchParams['created_user']!='')
		$this->db->where('l.user_id', $searchParams['created_user']);
		if($searchParams['open_status']!='')
		$this->db->where('l.status', $searchParams['open_status']);
                if($searchParams['start_date']!='')
		$this->db->where('l.created_time >=', $searchParams['start_date']);
                if($searchParams['end_date']!='')
		$this->db->where('l.created_time<=', $searchParams['end_date']." 23:59:59");
		$this->db->where('l.status < 20');
		if($role_id != 4 && $role_id!=5)
		{
			$this->db->where('l.location_id IN ('.$this->session->userdata('locationString').')');
		}
		$this->db->where('u.user_id IN ('.$reportees.', '.$this->session->userdata('user_id').')');
                if($source_id ==2){
                $this->db->where('l.source_id',$source_id );    
                }
        //mahesh code: 21st sep 2016, new filters
        if($searchParams['campaign']!='')
		$this->db->where('l.campaign_id', $searchParams['campaign']);
		$this->db->where('l.company_id',$this->session->userdata('company'));
		$this->db->order_by('l.lead_id', 'DESC');
		$res = $this->db->get();
		$data = $res->result_array();
		//echo $this->db->last_query(); exit;
		return $data;
	}

	//mahesh 15th july 2016 9:39 PM
	public function all_closedLeadResults($searchParams)
	{
		$role_id = $this->session->userdata('role_id');
		$reportees = $this->session->userdata('reportees');
		$reportees = ($role_id == 4 || $role_id == 5)?0:$reportees;
		$this->db->select('l.lead_id as lead_id,l.lead_number, concat(c.name, " (", l1.location, ")") as customer, 
			concat(cn.first_name," ",cn.last_name," - ",sp.name," (", cn.mobile_no, ")" ) as contact, s.name as source, r.role_id,
			concat(u.first_name, " ", u.last_name, " - ", u.employee_id, " (", r.name, ")") as user, l.created_time,l.modified_time, l.status,l.user_id,l.user2');
		$this->db->from('lead l');
		$this->db->join('customer c','c.customer_id = l.customer_id');
		$this->db->join('contact cn','cn.contact_id = l.contact_id');
		$this->db->join('source_of_lead s','s.source_id = l.source_id');
		$this->db->join('user u','u.user_id = l.user_id');
		$this->db->join('location l1','l1.location_id = l.location_id');
		$this->db->join('role r','r.role_id = u.role_id');
		$this->db->join('speciality sp','sp.speciality_id = cn.speciality_id');
		if($searchParams['lead_id']!='')
		$this->db->where('l.lead_number',$searchParams['lead_id']);
		if($searchParams['customer']!='')
		$this->db->where('l.customer_id', $searchParams['customer']);
		if($searchParams['created_user']!='')
		$this->db->where('l.user_id', $searchParams['created_user']);
		if($searchParams['closed_status']!='')
		$this->db->where('l.status', $searchParams['closed_status']);
		if(@$searchParams['start_date']!='')
		$this->db->where('DATE(l.created_time) >=', $searchParams['start_date']);
        if(@$searchParams['end_date']!='')
		$this->db->where('DATE(l.created_time)<=', $searchParams['end_date']);

		$this->db->where('l.status > 19');
		if($role_id != 4 && $role_id!=5)
		{
			$this->db->where('l.location_id IN ('.$this->session->userdata('locationString').')');
		}
		$this->db->where('u.user_id IN ('.$reportees.', '.$this->session->userdata('user_id').')');
		$this->db->where('l.company_id',$this->session->userdata('company'));
		$this->db->order_by('l.lead_id', 'DESC');
		$res = $this->db->get();
		$data = $res->result_array();
		return $data;
	}

	//mahesh 16th july 2016 04:06 PM
	public function edit_orderConclusionResults()
	{
		$statusValues = '(1,2,3,4,5)';
		$role_id = $this->session->userdata('role_id');
		$reportees = $this->session->userdata('reportees');
		$reportees = ($role_id == 4 || $role_id == 5)?0:$reportees;
		$this->db->select('o.opportunity_id,o.expected_order_conclusion, l.lead_id as lead_id, os.name as stage, o.created_time, o.status, 
			u.user_id, concat("ID : ", l.lead_id, " - ", c.name, " (", l1.location, ")") as lead, 
			concat(p.name, " (", p.description, ")") as product, o.status as status, o.expected_order_conclusion as oDate, 
			concat(u.first_name, " ", u.last_name, " - ", u.employee_id, " (", r.name, ")") as user, l.user_id,
			l.created_time as lCTime, o.created_time as oCTime');
		$this->db->from('opportunity o');
		$this->db->join('lead l', 'o.lead_id = l.lead_id');

		$this->db->join('opportunity_product op', 'op.opportunity_id = o.opportunity_id');
		$this->db->join('product p', 'p.product_id = op.product_id');
		$this->db->join('opportunity_status os', 'os.status = o.status');

		$this->db->join('customer c','c.customer_id = l.customer_id');
		$this->db->join('contact cn','cn.contact_id = l.contact_id');
		$this->db->join('user u','u.user_id = l.user_id');
		$this->db->join('location l1','l1.location_id = l.location_id');
		$this->db->join('role r','r.role_id = u.role_id');

		$this->db->where('o.expected_order_conclusion<',date('Y-m-d'));
		$this->db->where('l.location_id IN ('.$this->session->userdata('locationString').')');
		$this->db->where('u.user_id IN ('.$reportees.', '.$this->session->userdata('user_id').')');
		$this->db->where('o.status IN '.$statusValues);
		$this->db->order_by('o.opportunity_id', 'DESC');
		$res = $this->db->get();
		$data = $res->result_array();
		//echo $this->db->last_query();
		return $data;
	}	

	public function getSearchCampaign($campaign_id = 0)
	{
		$q = "SELECT c.campaign_id, concat(c.name, ' (', c.campaign_date, ')') as cName, type from campaign c
				where c.status = 1 AND c.campaign_id = '".$campaign_id."'";
		$r = $this->db->query($q);
		if($r->num_rows() > 0)
		{
			$data = $r->result_array();
			$t = ($data[0]['type'] == 1)?'Mass Mailing':'Offline';
			$campaign = $t.': '.$data[0]['cName'];
			return array('campaign_id' => $campaign_id, 'campaign' => $campaign);
		}	
		else
            return array('campaign_id' => '', 'campaign' => 'Select campaign');
 

	}

	/** new enhancements  START **/
	///// get all opportunities created by suresh on 4th May 2017

	public function opportunityStatusResults($searchParams, $per_page, $current_offset, $check = 1)
	{
		
		$role_id = $this->session->userdata('role_id');
		$reportees = $this->session->userdata('reportees');
		$reportees = ($role_id == 4 || $role_id == 5)?0:$reportees;
		$this->db->select('o.opportunity_id, l.lead_id as lead_id, os.name as stage, o.created_time, o.status, o.required_quantity,
			u.user_id, concat("ID : ", l.lead_id, " - ", c.name, " ", c.name1, " (", l1.location, ")") as lead, p.dp,
			concat(p.name, " (", p.description, ")") as product, o.status as status, o.expected_order_conclusion as oDate, 
			concat(u.first_name, " ", u.last_name, " - ", u.employee_id, " (", r.name, ")") as user, l.user_id,
			l.created_time as lCTime, o.created_time as oCTime,o.modified_time,os2.name as previous_status');
		$this->db->from('opportunity o');
		$this->db->join('lead l', 'o.lead_id = l.lead_id');

		$this->db->join('opportunity_product op', 'op.opportunity_id = o.opportunity_id');
		$this->db->join('product p', 'p.product_id = op.product_id');
		$this->db->join('product_group pg', 'pg.group_id = p.group_id');

		
		$this->db->join('opportunity_status os', 'os.status = o.status');
		$this->db->join('opportunity_status_history osh', 'osh.opportunity_id = o.opportunity_id');
		$this->db->join('opportunity_status os2', 'os2.status = osh.status');

		$this->db->join('customer c','c.customer_id = l.customer_id');
		$this->db->join('contact cn','cn.contact_id = l.contact_id');
		$this->db->join('user u','u.user_id = l.user_id');
		$this->db->join('location l1','l1.location_id = l.location_id');
		$this->db->join('role r','r.role_id = u.role_id');

		if($searchParams['opportunity_id']!='')
		$this->db->where('o.opportunity_id',$searchParams['opportunity_id']);
		

        $search_date  = ($searchParams['start_date']!='')?$searchParams['start_date']:date('Y-m-d');
        {
			$this->db->where('DATE(o.created_time) <=', $search_date);
			$open_op_status = '1,2,3,4,5';
			$this->db->where('osh.opportunity_status_id = (SELECT osh2.opportunity_status_id FROM opportunity_status_history osh2 
				WHERE osh2.opportunity_id = o.opportunity_id AND  DATE(osh2.created_time) <= "'.$search_date.'" ORDER BY osh2.created_time DESC LIMIT 1)');
			$this->db->where('osh.status in ('.$open_op_status.')');
		}
        
		if($role_id==4||$role_id==5) // If SE,Dealer
		{
			$this->db->where('l.user_id',$this->session->userdata('user_id'));
		}
		else
		{
			$this->db->where('l.location_id IN ('.$this->session->userdata('locationString').')');
			$this->db->where('op.product_id IN ('.$this->session->userdata('products').')');
		}
		//$this->db->where('u.user_id IN ('.$reportees.', '.$this->session->userdata('user_id').')');
		$this->db->where('o.company_id',$this->session->userdata('company'));
		$this->db->group_by('o.opportunity_id');
		$this->db->order_by('o.opportunity_id', 'DESC');
		$this->db->limit($per_page, $current_offset);
		$res = $this->db->get();
		$data = $res->result_array();
		//echo $this->db->last_query(); exit;
		return $data;
	}
	//// end get all opportunities


	/// download all opportunities created by suresh on 4th May 2017
	public function download_allOpportunityResults($searchParams)
	{
		
		$role_id = $this->session->userdata('role_id');
		$reportees = $this->session->userdata('reportees');
		$reportees = ($role_id == 4 || $role_id == 5)?0:$reportees;
		$this->db->select('o.opportunity_id, l.lead_id as lead_id, os.name as stage, o.created_time, o.status, l1.location_id, o.required_quantity,l.lead_id as lead_id,c.name as lead_name, 
			u.user_id, concat("ID : ", l.lead_id, " - ", c.name, " (", l1.location, ")") as lead,l1.location as lead_location, so.name as source, p.dp,
			p.name as product_code,p.description as product_description, o.status as status, o.expected_order_conclusion as oDate, 
			concat(u.first_name, " ", u.last_name, " - ", u.employee_id, " (", r.name, ")") as user, l.user_id,
			l.created_time as lCTime,l.modified_time as lead_mtime,l.status as lead_status,o.modified_time as opp_mtime, o.created_time as oCTime,o.decision_maker1,o.remarks2, pc.name as product_category,pg.name as segment,sr.name as readiness,sof.name as source_of_fund,concat(u.first_name," ",u.last_name) as user_name,u.employee_id as emp_id ,r.name as role_name, os2.name as previous_status,osh.status as op_prev_status');
		$this->db->from('opportunity o');
		$this->db->join('lead l', 'o.lead_id = l.lead_id');
		$this->db->join('source_of_lead so', 'so.source_id = l.source_id');

		$this->db->join('opportunity_product op', 'op.opportunity_id = o.opportunity_id');
		$this->db->join('product p', 'p.product_id = op.product_id');
		$this->db->join('product_group pg', 'pg.group_id = p.group_id');
		$this->db->join('product_category pc', 'pc.category_id = pg.category_id');
		$this->db->join('opportunity_status os', 'os.status = o.status');
		$this->db->join('opportunity_status_history osh', 'osh.opportunity_id = o.opportunity_id');
		$this->db->join('opportunity_status os2', 'os2.status = osh.status');


		$this->db->join('customer c','c.customer_id = l.customer_id');
		$this->db->join('contact cn','cn.contact_id = l.contact_id');
		$this->db->join('user u','u.user_id = l.user_id');
		$this->db->join('location l1','l1.location_id = l.location_id');
		$this->db->join('role r','r.role_id = u.role_id');
		$this->db->join('site_readiness sr','l.site_readiness_id=sr.site_readiness_id');
		$this->db->join('source_of_funds sof','o.fund_source_id = sof.fund_source_id');

		if($searchParams['opportunity_id']!='')
		$this->db->where('o.opportunity_id',$searchParams['opportunity_id']);

        $search_date  = ($searchParams['start_date']!='')?$searchParams['start_date']:date('Y-m-d');
        {
			$this->db->where('DATE(o.created_time) <=', $search_date);
			$open_op_status = '1,2,3,4,5';
			$this->db->where('osh.opportunity_status_id = (SELECT osh2.opportunity_status_id FROM opportunity_status_history osh2 
				WHERE osh2.opportunity_id = o.opportunity_id AND  DATE(osh2.created_time) <= "'.$search_date.'" ORDER BY osh2.created_time DESC LIMIT 1)');
			$this->db->where('osh.status in ('.$open_op_status.')');
		}
       
		
		if($role_id==4||$role_id==5) // If SE,Dealer
		{
			$this->db->where('l.user_id',$this->session->userdata('user_id'));
		}
		else
		{
			$this->db->where('l.location_id IN ('.$this->session->userdata('locationString').')');
			$this->db->where('op.product_id IN ('.$this->session->userdata('products').')');
		}
		//$this->db->where('u.user_id IN ('.$reportees.', '.$this->session->userdata('user_id').')');
		//$this->db->where('o.status IN '.$statusValues);
		$this->db->where('o.company_id',$this->session->userdata('company'));
		$this->db->order_by('o.opportunity_id', 'DESC');
		$res = $this->db->get();
		$data = $res->result_array();
		return $data;
	}	
	
	///// end download all opportunities

	/// get rows count created by suresh on 4th May 2017
	public function opportunityStatusRows($searchParams, $check = 1)
	{
		
		$role_id = $this->session->userdata('role_id');
		$reportees = $this->session->userdata('reportees');
		$reportees = ($role_id == 4 || $role_id == 5)?0:$reportees;
		$this->db->select('o.opportunity_id');
		$this->db->from('opportunity o');
		$this->db->join('lead l', 'o.lead_id = l.lead_id');

		$this->db->join('opportunity_product op', 'op.opportunity_id = o.opportunity_id');
		/*$this->db->join('product p', 'p.product_id = op.product_id');
		$this->db->join('product_group pg', 'pg.group_id = p.group_id');*/

		
		//$this->db->join('opportunity_status os', 'os.status = o.status');
		$this->db->join('opportunity_status_history osh', 'osh.opportunity_id = o.opportunity_id');
		//$this->db->join('opportunity_status os2', 'os2.status = o.status');

		$this->db->join('customer c','c.customer_id = l.customer_id');
		$this->db->join('contact cn','cn.contact_id = l.contact_id');
		$this->db->join('user u','u.user_id = l.user_id');
		/*$this->db->join('location l1','l1.location_id = l.location_id');
		$this->db->join('role r','r.role_id = u.role_id');*/

		if($searchParams['opportunity_id']!='')
		$this->db->where('o.opportunity_id',$searchParams['opportunity_id']);
		

        $search_date  = ($searchParams['start_date']!='')?$searchParams['start_date']:date('Y-m-d');
        {
			$this->db->where('DATE(o.created_time) <=', $search_date);
			$open_op_status = '1,2,3,4,5';
			$this->db->where('osh.opportunity_status_id = (SELECT osh2.opportunity_status_id FROM opportunity_status_history osh2 
				WHERE osh2.opportunity_id = o.opportunity_id AND  DATE(osh2.created_time) <= "'.$search_date.'" ORDER BY osh2.created_time DESC LIMIT 1)');
			$this->db->where('osh.status in ('.$open_op_status.')');
		}
        
		if($role_id==4||$role_id==5) // If SE,Dealer
		{
			$this->db->where('l.user_id',$this->session->userdata('user_id'));
		}
		else
		{
			$this->db->where('l.location_id IN ('.$this->session->userdata('locationString').')');
			$this->db->where('op.product_id IN ('.$this->session->userdata('products').')');
		}
		$this->db->where('o.company_id',$this->session->userdata('company'));
		//$this->db->where('u.user_id IN ('.$reportees.', '.$this->session->userdata('user_id').')');
		//$this->db->group_by('o.opportunity_id');
		$res = $this->db->get();
		return $res->num_rows();
	}	
	
	public function display_lead($searchParams)
	{
		$role_id = $searchParams['role_id'];
		$reportees = $searchParams['reportees'];
		$reportees = ($role_id == 4 || $role_id == 5)?0:$reportees;
		$this->db->select('l.*,l.modified_time, u.user_id, concat(c.name, " (", l1.location, ")") as customer, 
			concat(cn.first_name," ",cn.last_name," - ",sp.name," (", cn.mobile_no, ")" ) as contact, s.name as source, r.role_id,
			concat(u.first_name, " ", u.last_name, " - ", u.employee_id, " (", r.name, ")") as user');
		$this->db->from('lead l');
		$this->db->join('customer c','c.customer_id = l.customer_id');
		$this->db->join('contact cn','cn.contact_id = l.contact_id');
		$this->db->join('source_of_lead s','s.source_id = l.source_id');
		$this->db->join('user u','u.user_id = l.user_id');
		$this->db->join('location l1','l1.location_id = l.location_id');
		$this->db->join('role r','r.role_id = u.role_id');
		$this->db->join('speciality sp','sp.speciality_id = cn.speciality_id');
		$this->db->where('l.company_id',$this->session->userdata('company'));
		$this->db->where('l.status < 20');
		$this->db->where('l.location_id IN ('.$this->session->userdata('locationString').')');
		$this->db->where('u.user_id IN ('.$reportees.', '.$this->session->userdata('user_id').')');
        $this->db->order_by('l.lead_id', 'DESC');
		$res = $this->db->get();
		$data = $res->result_array();
		return $data;
	}

	//// end get rows count
	/** new enhancements  END **/

	public function inActiveUserLeadsDownload($searchParams)

	{
		$role_id = $this->session->userdata('role_id');
		$reportees = $this->session->userdata('reportees');
		$reportees = ($role_id == 4 || $role_id == 5)?0:$reportees;
		$this->db->select('l.lead_id as lead_id, u.user_id, concat(c.name, " (", l1.location, ")") as customer, 
			concat(cn.first_name," ",cn.last_name," - ",sp.name," (", cn.mobile_no, ")" ) as contact, s.name as source, r.role_id,
			concat(u.first_name, " ", u.last_name, " - ", u.employee_id, " (", r.name, ")") as user, l.created_time, l.status,cl.location_id');
		$this->db->from('lead l');
		$this->db->join('customer c','c.customer_id = l.customer_id');
		$this->db->join('contact cn','cn.contact_id = l.contact_id');
		$this->db->join('source_of_lead s','s.source_id = l.source_id');
		$this->db->join('user u','u.user_id = l.user_id');
		$this->db->join('location l1','l1.location_id = l.location_id');
		$this->db->join('location l2','l2.location_id = l1.parent_id');
		$this->db->join('location l3','l3.location_id = l2.parent_id');
		$this->db->join('location l4','l4.location_id = l3.parent_id');
		if($searchParams['s_region']!='')
		$this->db->where('l4.location_id',$searchParams['s_region']);
		if($searchParams['s_state']!='')
		$this->db->where('l3.location_id',$searchParams['s_state']);
		if($searchParams['s_district']!='')
		$this->db->where('l2.location_id',$searchParams['s_district']);
		if($searchParams['s_city']!='')
		$this->db->where('l1.location_id',$searchParams['s_city']);

		$this->db->join('role r','r.role_id = u.role_id');
		$this->db->join('speciality sp','sp.speciality_id = cn.speciality_id');
		$this->db->join('customer_location cl','c.customer_id = cl.customer_id');
		if($searchParams['lead_id']!='')
		$this->db->where('l.lead_id',$searchParams['lead_id']);
		if($searchParams['customer']!='')
		$this->db->where('l.customer_id', $searchParams['customer']);
		if($searchParams['created_user']!='')
		$this->db->where('l.user_id', $searchParams['created_user']);
		if($searchParams['open_status']!='')
		$this->db->where('l.status', $searchParams['open_status']);
		$this->db->where('l.status < 20');
		$this->db->where('l.company_id',$this->session->userdata('company'));
		$where = '(u.status=2 OR l.status=19)';
		$this->db->where($where);
		//$this->db->where('u.status',2);
		//$this->db->where('l.location_id IN ('.$this->session->userdata('locationString').')');
		//$this->db->where('u.user_id IN ('.$reportees.', '.$this->session->userdata('user_id').')');
		$this->db->order_by('l.lead_id', 'DESC');
		$res = $this->db->get();
		// echo $this->db->last_query();die;
		$data = $res->result_array();
		//echo $this->db->last_query();
		//print_r($searchParams);
		return $data;
	}
}