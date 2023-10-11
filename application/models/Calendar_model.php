<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Calendar_model extends CI_Model {

	public function visitResults($searchParams, $per_page, $current_offset)
	{
		
		// $this->db->select('v.*, IF(v.end_date < CURDATE(), "1", "0") is_expired, (CASE WHEN v.lead_id is NOT NULL OR "" THEN l.lead_number WHEN v.customer_id is NOT NULL OR "" THEN c.name WHEN v.dealer_id is NOT NULL OR "" THEN dd.distributor_name WHEN v.city is NOT NULL OR "" THEN v.city END) as CustomerName, vp.name as Purpose,b.name as branch,CONCAT(u.first_name," ",u.last_name) as lead_owner,(CASE WHEN v.status = 1 THEN "Completed" WHEN v.status = 2 THEN "Cancelled" ELSE "Rescheduled" END) AS status');
		$this->db->select('v.*, IF(v.end_date < NOW(), "1", "0") is_expired, (CASE WHEN v.lead_id is NOT NULL OR "" THEN c.name END) as LeadNumber, (CASE WHEN v.customer_id is NOT NULL OR "" THEN c.name END) as CName, (CASE WHEN v.dealer_id is NOT NULL OR "" THEN dd.distributor_name END) as DistName, (CASE WHEN v.city is NOT NULL OR "" THEN v.city END) as City, vp.name as Purpose,b.name as branch,CONCAT(u.first_name," ",u.last_name) as lead_owner,v.status AS status, (CASE WHEN v.lead_id is NOT NULL OR "" THEN l.lead_number END) as lead_number');
		$this->db->from('visit v');
		$this->db->join('visit_purpose vp','v.purpose_id = vp.purpose_id');
		$this->db->join('lead l','l.lead_id = v.lead_id','left');
		$this->db->join('customer c','c.customer_id = l.customer_id OR c.customer_id = v.customer_id','left');
		//$this->db->join('customer_location_contact clc','clc.customer_id = c.customer_id AND clc.location_id = l.location_id AND clc.contact_id = l.contact_id');
		//$this->db->join('location loc','loc.location_id = clc.location_id');
		$this->db->join('distributor_details dd','dd.user_id=v.dealer_id','left');
		$this->db->join('user u','u.user_id=l.user_id','left');
		$this->db->join('branch b','b.branch_id=u.branch_id','left');
		if($searchParams['leadId']!='')
		$this->db->where('l.lead_number', $searchParams['leadId']);
		// if($searchParams['customer']!='')
		// {
		// 	$this->db->where('v.customer_id', $searchParams['customer']);
		// 	$this->db->or_where('l.customer_id', $searchParams['customer']);
		// }
		if($searchParams['customer']!='')
		{
			$str1 = 'v.customer_id = '. $searchParams['customer'];
			$str2 = 'l.customer_id = '. $searchParams['customer'];
			$this->db->where('('.$str1.' or '.$str2.')');
			// $this->db->or_where('l.customer_id', $searchParams['customer']);
		}
		if($searchParams['startDate']!='')
		$this->db->where('date(v.start_date) >=', $searchParams['startDate']);
		if($searchParams['endDate']!='')
		$this->db->where('date(v.end_date) <=', $searchParams['endDate']);
		$this->db->where('v.created_by',$this->session->userdata('user_id'));
		//$this->db->where('l.user_id', $this->session->userdata('user_id'));
		//$this->db->where('l.company_id',$this->session->userdata('company'));
		$this->db->limit($per_page, $current_offset);
		// $this->db->order_by('v.visit_id','DESC');
		$this->db->order_by('v.created_time','DESC');
		$res = $this->db->get();
		// echo $this->db->last_query();die;
		return $res->result_array();
	}


	public function visitDetails($searchParams)
	{
		// $this->db->select('v.*, CONCAT("Lead ID - ",l.lead_number," (",c.name," "," (",loc.location,"))") as CustomerName, vp.name as Purpose');
		// $this->db->from('visit v');
		// if($searchParams['leadId']!='')
		// $this->db->where('l.lead_number', $searchParams['leadId']);
		// if($searchParams['customer']!='')
		// $this->db->where('c.customer_id', $searchParams['customer']);
		// if($searchParams['startDate']!='')
		// $this->db->where('v.start_date >=', $searchParams['startDate']);
		// if($searchParams['endDate']!='')
		// $this->db->where('v.end_date <=', $searchParams['endDate']);
		// $this->db->where('l.user_id', $this->session->userdata('user_id'));
		// $this->db->where('l.company_id',$this->session->userdata('company'));
		// $this->db->join('visit_purpose vp','v.purpose_id = vp.purpose_id');
		// $this->db->join('lead l','l.lead_id = v.lead_id');
		// $this->db->join('customer c','c.customer_id = l.customer_id');
		// $this->db->join('customer_location_contact clc','clc.customer_id = c.customer_id AND clc.location_id = l.location_id AND clc.contact_id = l.contact_id');
		// $this->db->join('location loc','loc.location_id = clc.location_id');


		// $this->db->select('v.*, IF(v.end_date < CURDATE(), "1", "0") is_expired, (CASE WHEN v.lead_id is NOT NULL OR "" THEN l.c.name END) as LeadNumber, (CASE WHEN v.customer_id is NOT NULL OR "" THEN c.name END) as CName, (CASE WHEN v.dealer_id is NOT NULL OR "" THEN dd.distributor_name END) as DistName, (CASE WHEN v.city is NOT NULL OR "" THEN v.city END) as City, vp.name as Purpose,b.name as branch,CONCAT(u.first_name," ",u.last_name) as lead_owner,v.status AS status');
		// $this->db->from('visit v');
		// $this->db->join('visit_purpose vp','v.purpose_id = vp.purpose_id');
		// $this->db->join('lead l','l.lead_id = v.lead_id','left');
		// $this->db->join('customer c','c.customer_id = l.customer_id','left');
		// //$this->db->join('customer_location_contact clc','clc.customer_id = c.customer_id AND clc.location_id = l.location_id AND clc.contact_id = l.contact_id');
		// //$this->db->join('location loc','loc.location_id = clc.location_id');
		// $this->db->join('distributor_details dd','dd.user_id=v.dealer_id','left');
		// $this->db->join('user u','u.user_id=l.user_id','left');
		// $this->db->join('branch b','b.branch_id=u.branch_id','left');
		// if($searchParams['leadId']!='')
		// $this->db->where('l.lead_number', $searchParams['leadId']);
		// if($searchParams['customer']!='')
		// $this->db->where('c.customer_id', $searchParams['customer']);
		// if($searchParams['startDate']!='')
		// $this->db->where('v.start_date >=', $searchParams['startDate']);
		// if($searchParams['endDate']!='')
		// $this->db->where('v.end_date <=', $searchParams['endDate']);
		// $this->db->where('v.created_by',$this->session->userdata('user_id'));
		// //$this->db->where('l.user_id', $this->session->userdata('user_id'));
		// //$this->db->where('l.company_id',$this->session->userdata('company'));
		// // $this->db->limit($per_page, $current_offset);
		// $this->db->order_by('v.visit_id','DESC');


		$this->db->select('v.*, IF(v.end_date < NOW(), "1", "0") is_expired, (CASE WHEN v.lead_id is NOT NULL OR "" THEN c.name END) as LeadNumber, (CASE WHEN v.customer_id is NOT NULL OR "" THEN c.name END) as CName, (CASE WHEN v.dealer_id is NOT NULL OR "" THEN dd.distributor_name END) as DistName, (CASE WHEN v.city is NOT NULL OR "" THEN v.city END) as City, vp.name as Purpose,b.name as branch,CONCAT(u.first_name," ",u.last_name) as lead_owner,v.status AS status, (CASE WHEN v.lead_id is NOT NULL OR "" THEN l.lead_number END) as lead_number');
		$this->db->from('visit v');
		$this->db->join('visit_purpose vp','v.purpose_id = vp.purpose_id');
		$this->db->join('lead l','l.lead_id = v.lead_id','left');
		$this->db->join('customer c','c.customer_id = l.customer_id OR c.customer_id = v.customer_id','left');
		$this->db->join('distributor_details dd','dd.user_id=v.dealer_id','left');
		$this->db->join('user u','u.user_id=l.user_id','left');
		$this->db->join('branch b','b.branch_id=u.branch_id','left');
		if($searchParams['leadId']!='')
		$this->db->where('l.lead_number', $searchParams['leadId']);
		// if($searchParams['customer']!='')
		// {
		// 	$this->db->where('v.customer_id', $searchParams['customer']);
		// 	$this->db->or_where('l.customer_id', $searchParams['customer']);
		// }
		if($searchParams['customer']!='')
		{
			$str1 = 'v.customer_id = '. $searchParams['customer'];
			$str2 = 'l.customer_id = '. $searchParams['customer'];
			$this->db->where('('.$str1.' or '.$str2.')');
			// $this->db->or_where('l.customer_id', $searchParams['customer']);
		}
		if($searchParams['startDate']!='')
		$this->db->where('date(v.start_date) >=', $searchParams['startDate']);
		if($searchParams['endDate']!='')
		$this->db->where('date(v.end_date) <=', $searchParams['endDate']);
		$this->db->where('v.created_by',$this->session->userdata('user_id'));
		$this->db->order_by('v.created_time','DESC');
		$res = $this->db->get();
		return $res->result_array();
	}
	
	public function visitTotalRows($searchParams)
	{
		// $this->db->from('visit v');
		// if($searchParams['leadId']!='')
		// $this->db->where('l.lead_number', $searchParams['leadId']);
		// if($searchParams['customer']!='')
		// $this->db->where('c.customer_id', $searchParams['customer']);
		// if($searchParams['startDate']!='')
		// $this->db->where('v.start_date >=', $searchParams['startDate']);
		// if($searchParams['endDate']!='')
		// $this->db->where('v.end_date <=', $searchParams['endDate']);
		// $this->db->where('l.user_id', $this->session->userdata('user_id'));
		// $this->db->where('l.company_id',$this->session->userdata('company'));
		// $this->db->join('visit_purpose vp','v.purpose_id = vp.purpose_id');
		// $this->db->join('lead l','l.lead_id = v.lead_id');
		// $this->db->join('customer c','c.customer_id = l.customer_id');
		// $this->db->join('customer_location_contact clc','clc.customer_id = c.customer_id AND clc.location_id = l.location_id AND clc.contact_id = l.contact_id');
		// $this->db->join('location loc','loc.location_id = clc.location_id');
		$this->db->from('visit v');
		$this->db->join('visit_purpose vp','v.purpose_id = vp.purpose_id');
		$this->db->join('lead l','l.lead_id = v.lead_id','left');
		$this->db->join('customer c','c.customer_id = l.customer_id OR c.customer_id = v.customer_id','left');
		$this->db->join('distributor_details dd','dd.user_id=v.dealer_id','left');
		$this->db->join('user u','u.user_id=l.user_id','left');
		$this->db->join('branch b','b.branch_id=u.branch_id','left');
		if($searchParams['leadId']!='')
		$this->db->where('l.lead_number', $searchParams['leadId']);
		// if($searchParams['customer']!='')
		// {
		// 	$this->db->where('v.customer_id', $searchParams['customer']);
		// 	$this->db->or_where('l.customer_id', $searchParams['customer']);
		// }
		if($searchParams['customer']!='')
		{
			$str1 = 'v.customer_id = '. $searchParams['customer'];
			$str2 = 'l.customer_id = '. $searchParams['customer'];
			$this->db->where('('.$str1.' or '.$str2.')');
			// $this->db->or_where('l.customer_id', $searchParams['customer']);
		}
		if($searchParams['startDate']!='')
		$this->db->where('date(v.start_date) >=', $searchParams['startDate']);
		if($searchParams['endDate']!='')
		$this->db->where('date(v.end_date) <=', $searchParams['endDate']);
		$this->db->where('v.created_by',$this->session->userdata('user_id'));
		$this->db->order_by('v.created_time','DESC');
		$res = $this->db->get();
		return $res->num_rows();
	}

	public function checkVisitAvailability($data)
	{
		// echo '<pre>'; print_r($data);die;
		$array = array('start_date >=' => $data['start_date'], 'start_date <=' => $data['end_date']);
		$array1 = array('start_date <=' => $data['start_date'], 'end_date >=' => $data['end_date']);
		$array2 = array('end_date >=' => $data['start_date'], 'end_date <=' => $data['end_date']);

		$this->db->from('visit');
		//if($data['visit_id']!='')
		$this->db->where('visit_id !=', $data['visit_id']);
		if($data['lead_id'] != null)
		$this->db->where('lead_id', $data['lead_id']);
		if($data['customer_id'] != null)
		$this->db->where('customer_id', $data['customer_id']);
		if($data['dealer_id'] != null)
		$this->db->where('dealer_id', $data['dealer_id']);
		if($data['city'] != null)
		$this->db->where('city', $data['city']);
		$this->db->where('status =', 1);
		$this->db->group_start();
		$this->db->group_start();
		$this->db->where($array);
		$this->db->group_end();
		$this->db->or_group_start();
		$this->db->where($array1);
		$this->db->group_end();
		$this->db->or_group_start();
		$this->db->where($array2);
		$this->db->group_end();
		$this->db->group_end();
		$res = $this->db->get();
		//echo $this->db->last_query(); exit();
		return $res->num_rows();
	}

	public function demoResults($searchParams, $per_page, $current_offset)
	{
		$this->db->select('d.*, IF(d.end_date < NOW(), "1", "0") is_expired, CONCAT(c.name," "," (",loc.location,")") as CustomerName, CONCAT("ID - ", o.opp_number, ": ", p.name," (",p.description,")") as opportunity, CONCAT(dpd.serial_number," - ",dpd.location) as demo,o.status as opportunity_status, p.name as product_name,p.description as product_description,l.lead_number');
		$this->db->from('demo d');
		if($searchParams['opportunityId']!='')
		$this->db->where('o.opp_number', $searchParams['opportunityId']);
		if($searchParams['customer']!='')
		$this->db->where('c.customer_id', $searchParams['customer']);
		if($searchParams['startDate']!='')
		$this->db->where('d.start_date >=', $searchParams['startDate']);
		if($searchParams['endDate']!='')
		$this->db->where('d.end_date <=', $searchParams['endDate']);
		$this->db->where('d.created_by', $this->session->userdata('user_id'));
		// $this->db->where('o.company_id',$this->session->userdata('company'));
		$this->db->join('demo_product dp', 'dp.demo_product_id = d.demo_product_id AND dp.product_id = d.product_id','left');
		$this->db->join('demo_product_details dpd','dpd.demo_product_id = dp.demo_product_id','left');
		$this->db->join('product p','p.product_id = d.product_id','left');
		$this->db->join('opportunity_product op', 'op.opportunity_id = d.opportunity_id AND op.product_id = d.product_id','left');
		$this->db->join('opportunity o', 'o.opportunity_id = op.opportunity_id','left');
		$this->db->join('lead l','l.lead_id = o.lead_id','left');
		$this->db->join('customer c','c.customer_id = l.customer_id','left');
		$this->db->join('customer_location_contact clc','clc.customer_id = c.customer_id AND clc.location_id = l.location_id AND clc.contact_id = l.contact_id','left');
		$this->db->join('location loc','loc.location_id = clc.location_id','left');
		$this->db->limit($per_page, $current_offset);
		$this->db->order_by('d.demo_id','DESC');
		$res = $this->db->get();
		return $res->result_array();
	}

	public function demoDetails($searchParams)
	{
		$this->db->select('d.*, CONCAT(c.name," ",c.name1," - ",c.department," (",loc.location,")") as CustomerName, CONCAT("ID - ", o.opp_number, ": ", p.name," (",p.description,")") as opportunity, CONCAT(dpd.serial_number," - ",dpd.location) as demo,p.name as product_name,p.description as product_description,l.lead_number');
		$this->db->from('demo d');
		if($searchParams['opportunityId']!='')
		$this->db->where('o.opp_number', $searchParams['opportunityId']);
		if($searchParams['customer']!='')
		$this->db->where('c.customer_id', $searchParams['customer']);
		if($searchParams['startDate']!='')
		$this->db->where('d.start_date >=', $searchParams['startDate']);
		if($searchParams['endDate']!='')
		$this->db->where('d.end_date <=', $searchParams['endDate']);
		$this->db->where('d.created_by', $this->session->userdata('user_id'));
		// $this->db->where('o.company_id',$this->session->userdata('company'));
		$this->db->join('demo_product dp', 'dp.demo_product_id = d.demo_product_id AND dp.product_id = d.product_id','left');
		$this->db->join('demo_product_details dpd','dpd.demo_product_id = dp.demo_product_id','left');
		$this->db->join('product p','p.product_id = d.product_id','left');
		$this->db->join('opportunity_product op', 'op.opportunity_id = d.opportunity_id AND op.product_id = d.product_id','left');
		$this->db->join('opportunity o', 'o.opportunity_id = op.opportunity_id','left');
		$this->db->join('lead l','l.lead_id = o.lead_id','left');
		$this->db->join('customer c','c.customer_id = l.customer_id','left');
		$this->db->join('customer_location_contact clc','clc.customer_id = c.customer_id AND clc.location_id = l.location_id AND clc.contact_id = l.contact_id','left');
		$this->db->join('location loc','loc.location_id = clc.location_id','left');
		$res = $this->db->get();
		return $res->result_array();
	}

	
	public function demoTotalRows($searchParams)
	{
		$this->db->from('demo d');
		if($searchParams['opportunityId']!='')
		$this->db->where('o.opp_number', $searchParams['opportunityId']);
		if($searchParams['customer']!='')
		$this->db->where('c.customer_id', $searchParams['customer']);
		if($searchParams['startDate']!='')
		$this->db->where('d.start_date >=', $searchParams['startDate']);
		if($searchParams['endDate']!='')
		$this->db->where('d.end_date <=', $searchParams['endDate']);
		$this->db->where('d.created_by', $this->session->userdata('user_id'));
		// $this->db->where('o.company_id', $this->session->userdata('company'));
		$this->db->join('demo_product dp', 'dp.demo_product_id = d.demo_product_id AND dp.product_id = d.product_id','left');
		$this->db->join('demo_product_details dpd','dpd.demo_product_id = dp.demo_product_id','left');
		$this->db->join('product p','p.product_id = d.product_id','left');
		$this->db->join('opportunity_product op', 'op.opportunity_id = d.opportunity_id AND op.product_id = d.product_id','left');
		$this->db->join('opportunity o', 'o.opportunity_id = op.opportunity_id','left');
		$this->db->join('lead l','l.lead_id = o.lead_id','left');
		$this->db->join('customer c','c.customer_id = l.customer_id','left');
		$this->db->join('customer_location_contact clc','clc.customer_id = c.customer_id AND clc.location_id = l.location_id AND clc.contact_id = l.contact_id','left');
		$this->db->join('location loc','loc.location_id = clc.location_id','left');
		$res = $this->db->get();
		return $res->num_rows();
	}

	public function checkDemoAvailability($data)
	{
		if($data['start_date'] !='' & $data['end_date'] !=''){
		$array = array('start_date >=' => $data['start_date'], 'start_date <=' => $data['end_date']);
		$array1 = array('start_date <=' => $data['start_date'], 'end_date >=' => $data['end_date']);
		$array2 = array('end_date >=' => $data['start_date'], 'end_date <=' => $data['end_date']);

		$this->db->from('demo');
		//if($data['demo_id']!='')
		$this->db->where('demo_id !=', $data['demo_id']);
		$this->db->where('demo_product_id', $data['demo_product_id']);
		$this->db->where('status =', 1);
		$this->db->group_start();
		$this->db->group_start();
		$this->db->where($array);
		$this->db->group_end();
		$this->db->or_group_start();
		$this->db->where($array1);
		$this->db->group_end();
		$this->db->or_group_start();
		$this->db->where($array2);
		$this->db->group_end();
		$this->db->group_end();
		$res = $this->db->get();
		return $res->num_rows();
		}else{
			return 0;
		}
	}

	public function visitCalendarDetails($user_id)
    {
		// $this->db->select('v.visit_id as id,  v.start_date  as start, v.end_date as end,(case when (v.dealer_id is not null and v.dealer_id != "") then CONCAT("Dealer - ", d.distributor_name) when (v.customer_id is not null and v.customer_id != "") then CONCAT("Customer - ", c.name) when (v.lead_id is not null and v.lead_id != "") then CONCAT("Lead ID - ", `l`.`lead_number`, " (", `lc`.`name`, " ", " (", `loc`.`location`, "))") when (v.city is not null and v.city != "") then CONCAT("City - ", v.city) end) as description, (case when (v.dealer_id is not null and v.dealer_id != "") then CONCAT("Dealer - ", d.distributor_name) when (v.customer_id is not null and v.customer_id != "") then CONCAT("Customer - ", c.name) when (v.lead_id is not null and v.lead_id != "") then CONCAT("Lead ID - ", `l`.`lead_number`, " (", `lc`.`name`, " ", " (", `loc`.`location`, "))") when (v.city is not null and v.city != "") then CONCAT("City - ", v.city) end) as title');
		$where = array(1,2,3,4);
		// $where = ();
		$this->db->select('v.visit_id as id,  v.start_date  as start, v.end_date as end,(case when (v.dealer_id is not null and v.dealer_id != "") then CONCAT("Dealer - ", d.distributor_name) when (v.customer_id is not null and v.customer_id != "") then CONCAT("Customer - ", c.name) when (v.lead_id is not null and v.lead_id != "") then CONCAT( " (", `lc`.`name`, " ", " (", `loc`.`location`, ")) ", `v`.`remarks1`, "") when (v.city is not null and v.city != "") then CONCAT("City - ", v.city) end) as description, (case when (v.dealer_id is not null and v.dealer_id != "") then CONCAT("Dealer - ", d.distributor_name) when (v.customer_id is not null and v.customer_id != "") then CONCAT("Customer - ", c.name) when (v.lead_id is not null and v.lead_id != "") then CONCAT(" (", `lc`.`name`, " ", " (", `loc`.`location`, ")) ", `v`.`remarks1`, "") when (v.city is not null and v.city != "") then CONCAT("City - ", v.city) end) as title');
		
		$this->db->from('visit v');
		$this->db->where_in('v.status',$where);
		$this->db->where('v.created_by', $user_id);
		$this->db->join('lead l','l.lead_id = v.lead_id','left');
		$this->db->join('customer c','c.customer_id = v.customer_id','left');
		$this->db->join('customer_location_contact clc','clc.customer_id = c.customer_id OR clc.location_id = l.location_id OR clc.contact_id = l.contact_id','left');
		$this->db->join('location loc','loc.location_id = clc.location_id','left');
		$this->db->join('distributor_details d','d.user_id = v.dealer_id','left');
		$this->db->join('customer lc ',' lc.customer_id = l.customer_id','left');
		$this->db->group_by('v.visit_id');
		$this->db->order_by('v.lead_id','DESC');
		$res = $this->db->get();
		// echo '<pre>'; print_r($this->db->last_query());die;
		return $res->result_array();
    }

	public function demoCalendarDetails($user_id)
	{
		$this->db->select('d.demo_id as id, d.start_date as start,d.end_date as end, CONCAT("Customer : ", c.name," (",loc.location,") Product : ", p.name," (",p.description,")"," Serial Number : ", dpd.serial_number) as description, c.name as title');
		$this->db->from('demo d');
		$this->db->where('d.status', 1);
		$this->db->where('l.user_id', $user_id);
		$this->db->join('demo_product_details dpd','dpd.demo_product_id = d.demo_product_id');
		$this->db->join('product p','p.product_id = d.product_id');
		$this->db->join('opportunity o','o.opportunity_id = d.opportunity_id');
		$this->db->join('lead l','l.lead_id = o.lead_id');
		$this->db->join('customer c','c.customer_id = l.customer_id');
		$this->db->join('customer_location_contact clc','clc.customer_id = c.customer_id AND clc.location_id = l.location_id AND clc.contact_id = l.contact_id');
		$this->db->join('location loc','loc.location_id = clc.location_id');
		$this->db->order_by('d.demo_id','DESC');
		$res = $this->db->get();
		return $res->result_array();
	}

	public function demoCalendarResults($product_id, $demo_product_id)
	{
		$this->db->select('d.demo_id as id, d.start_date as start,d.end_date as end, "Booked" as title');
		$this->db->from('demo d');
		$this->db->where('d.status', 1);
		$this->db->where('d.product_id', $product_id);
		$this->db->where('d.demo_product_id', $demo_product_id);
		$this->db->order_by('d.demo_id','DESC');
		$res = $this->db->get();
		return $res->result_array();	
	}

	public function getLeadDetails($check = 0)
	{
		$this->db->select('l.lead_id,l.lead_number, CONCAT(c.name," - "," (",loc.location,")") as CustomerName,l.customer_id');
		$this->db->from('lead l');
		$this->db->where('l.user_id', $this->session->userdata('user_id'));
		$this->db->where('l.status >', 1);
		$this->db->where('l.status <', 20);
		$this->db->where('l.company_id',$this->session->userdata('company'));
		if($check == 0)
		$this->db->where('l.visit_requirement', 1);
		if($check==3){
			$this->db->where('l.site_readiness_id >', 0);
			$this->db->where('l.relationship_id >', 0);
		}
		if($this->session->userdata('role_id')!=4)
		{
			$this->db->where('l.location_id IN ('.$this->session->userdata('locationString').')');
		}
		$this->db->join('customer c','l.customer_id = c.customer_id');
		$this->db->join('customer_location_contact clc','clc.customer_id = c.customer_id AND clc.location_id = l.location_id AND clc.contact_id = l.contact_id');
		$this->db->join('location loc','loc.location_id = clc.location_id');
		$this->db->order_by('l.lead_id','DESC');
		$res = $this->db->get();
		//echo $this->db->last_query();die;
		return $res->result_array();
	}

	public function getOpportunity($lead_id)
	{
		$this->db->select('o.opportunity_id as opportunityId, CONCAT("ID - ",o.opp_number," ",p.name,"(",p.description,")") as ProductName');
		$this->db->from('opportunity o');
		$this->db->where('o.lead_id', $lead_id);
		if($this->session->userdata('company') !='')
		$this->db->where('o.company_id',$this->session->userdata('company'));
		$this->db->where('o.status >=', 3);
		$this->db->where('o.status <', 6);
		if($this->session->userdata('user_id') !='')
		$this->db->where('l.user_id', $this->session->userdata('user_id'));
		$this->db->join('lead l','l.lead_id = o.lead_id');
		$this->db->join('opportunity_product op','op.opportunity_id = o.opportunity_id');
		$this->db->join('product p','p.product_id = op.product_id');
		$res = $this->db->get();
		$data = [];
		foreach($res->result_array() as $row)
        {
            $data[$row['opportunityId']] = $row['ProductName'];
        }
        return $data;
	}

	public function getDemo($opportunity_id)
	{
		$this->db->select('dpd.demo_product_id as demoProductId, CONCAT(p.name," - ",p.description) as demoProduct,p.name as product,p.description as description');
		$this->db->from('opportunity_product op');
		$this->db->where('op.opportunity_id', $opportunity_id);
		$this->db->join('demo_product dp','dp.product_id = op.product_id');
		$this->db->join('product p','p.product_id = dp.product_id');
		$this->db->join('demo_product_details dpd','dpd.demo_product_id = dp.demo_product_id');
	    $this->db->group_by('p.product_id');
		$res = $this->db->get();
		$data = [];
		foreach($res->result_array() as $row)
        {
            $data[$row['demoProductId']] = $row['demoProduct'];
        }
        return $data;
	}

	public function getDemoCalendar($demo_product_id)
	{
		$this->db->select('d.demo_id as id, d.start_date as start,d.end_date as end, CONCAT("Booked By : ",u.first_name, " ", u.last_name, " - ", u.employee_id, " (", r.name, ")"," Customer : ", c.name," ",c.name1," - ",c.department," (",loc.location,")" ) as description,"Booked" as title');
		$this->db->from('demo d');
		$this->db->where('d.demo_product_id', $demo_product_id);
		$this->db->where('d.status', 1);
		$this->db->join('opportunity o','o.opportunity_id = d.opportunity_id');
		$this->db->join('lead l','l.lead_id = o.lead_id');
		$this->db->join('customer c','c.customer_id = l.customer_id');
		$this->db->join('customer_location_contact clc','clc.customer_id = c.customer_id AND clc.location_id = l.location_id AND clc.contact_id = l.contact_id');
		$this->db->join('location loc','loc.location_id = clc.location_id');
		$this->db->join('user u', 'u.user_id = d.created_by');
		$this->db->join('role r', 'r.role_id = u.role_id');
		$this->db->order_by('d.demo_id','DESC');
		$res = $this->db->get();
		return $res->result_array();	
	}

	public function getDemoCalendarDetails($demo_product_id)
	{
		$this->db->select('d.demo_id as id, d.start_date as start,d.end_date as end, CONCAT(u.first_name, " ", u.last_name, " - ", u.employee_id, " (", r.name, ")") as booked_by, CONCAT(c.name," ",c.name1," - ",c.department," (",loc.location,")" ) as customer,"Booked" as title');
		$this->db->from('demo d');
		$this->db->where('d.demo_product_id', $demo_product_id);
		$this->db->where('d.status', 1);
		$this->db->join('opportunity o','o.opportunity_id = d.opportunity_id');
		$this->db->join('lead l','l.lead_id = o.lead_id');
		$this->db->join('customer c','c.customer_id = l.customer_id');
		$this->db->join('customer_location_contact clc','clc.customer_id = c.customer_id AND clc.location_id = l.location_id AND clc.contact_id = l.contact_id');
		$this->db->join('location loc','loc.location_id = clc.location_id');
		$this->db->join('user u', 'u.user_id = d.created_by');
		$this->db->join('role r', 'r.role_id = u.role_id');
		$this->db->order_by('d.demo_id','DESC');
		$res = $this->db->get();
		return $res->result_array();	
	}

	public function demoProductResults($searchParams, $per_page, $current_offset)
	{
		$product = getUserProducts();
		$this->db->select('dpd.demo_product_id, dpd.status, dpd.location, dpd.serial_number, 
							concat(p.name, " (", p.description, ")") as ProductName, pc.name as CategoryName, 
							concat(pg.name, " (", pg.description, ")") as GroupName, l1.location as city,
							b.name as branch, l.location as region');
		$this->db->from('demo_product_details dpd');
		if($searchParams['location']!='')
		$this->db->like('dpd.location',$searchParams['location']);
	     if($searchParams['city']!='')
		$this->db->like('l1.location',$searchParams['city']);
		if($searchParams['serialNumber']!='')
		$this->db->like('dpd.serial_number',$searchParams['serialNumber']);
		if($searchParams['branch']!='')
		$this->db->like('b.name',$searchParams['branch']);
		$this->db->where('pc.company_id', $this->session->userdata('company'));
		$this->db->join('demo_product dp ','dp.demo_product_id = dpd.demo_product_id');
		$this->db->join('product p ','dp.product_id = p.product_id');
		$this->db->join('product_group pg ','p.group_id = pg.group_id');
		$this->db->join('product_category pc ','pg.category_id = pc.category_id');
		$this->db->join('branch b ','b.branch_id = dpd.branch_id');
		$this->db->join('location l ','l.location_id = b.region_id');
		$this->db->join('location l1 ','l1.location_id = dpd.city_id');
		$this->db->where('p.product_id IN ('.$product.')');
		$this->db->where('p.company_id',$this->session->userdata('company'));
		$this->db->limit($per_page, $current_offset);
		$this->db->order_by('l1.location_id','DESC');
		$res = $this->db->get();
		return $res->result_array();
	}

	public function demoProductDetails($searchParams)
	{
		$product = getUserProducts();
		$this->db->select('dpd.location, dpd.serial_number, dpd.modified_by, dpd.modified_time, dpd.created_time,
						p.name as ProductName, p.description as ProductDescription, pc.name as CategoryName,  
							pg.name as GroupName, pg.description as GroupDescription, p.rrp, l1.location as city,
							b.name as branch, l.location as region, b.region_id as region_id');
		$this->db->from('demo_product_details dpd');
		if($searchParams['location']!='')
		$this->db->like('dpd.location',$searchParams['location']);
		if($searchParams['serialNumber']!='')
		$this->db->like('dpd.serial_number',$searchParams['serialNumber']);
		if($searchParams['branch']!='')
		$this->db->like('b.name',$searchParams['branch']);
		$this->db->where('pc.company_id', $this->session->userdata('company'));
		$this->db->join('demo_product dp ','dp.demo_product_id = dpd.demo_product_id');
		$this->db->join('product p ','dp.product_id = p.product_id');
		$this->db->join('product_group pg ','p.group_id = pg.group_id');
		$this->db->join('product_category pc ','pg.category_id = pc.category_id');
		$this->db->join('branch b ','b.branch_id = dpd.branch_id');
		$this->db->join('location l ','l.location_id = b.region_id');
		$this->db->join('location l1 ','l1.location_id = dpd.city_id');
		$this->db->where('p.product_id IN ('.$product.')');
		$this->db->where('p.company_id',$this->session->userdata('company'));
		$res = $this->db->get();
		return $res->result_array();
	}

	public function demoProductTotalRows($searchParams)
	{
		$product = getUserProducts();
		$this->db->from('demo_product_details dpd');
		if($searchParams['location']!='')
		$this->db->like('dpd.location',$searchParams['location']);
		if($searchParams['city']!='')
		$this->db->like('l1.location',$searchParams['city']);
		if($searchParams['serialNumber']!='')
		$this->db->like('dpd.serial_number',$searchParams['serialNumber']);
		if($searchParams['branch']!='')
		$this->db->like('b.name',$searchParams['branch']);
		$this->db->where('pc.company_id', $this->session->userdata('company'));
		$this->db->join('demo_product dp ','dp.demo_product_id = dpd.demo_product_id');
		$this->db->join('product p ','dp.product_id = p.product_id');
		$this->db->join('product_group pg ','p.group_id = pg.group_id');
		$this->db->join('product_category pc ','pg.category_id = pc.category_id');
		$this->db->join('branch b ','b.branch_id = dpd.branch_id');
		$this->db->join('location l ','l.location_id = b.region_id');
		$this->db->join('location l1 ','l1.location_id = dpd.city_id');
		$this->db->where('p.product_id IN ('.$product.')');
		$this->db->where('p.company_id',$this->session->userdata('company'));
		$res = $this->db->get();
		return $res->num_rows();
	}
	public function get_lead_customer($lead_id)
	{
		$this->db->select('CONCAT("Lead ID -"," ",l.lead_number,"( ",c.name," - "," (",loc.location,")") as CustomerName');
		$this->db->from('lead l');
		$this->db->where('l.lead_id', $lead_id);
		$this->db->join('customer c','l.customer_id = c.customer_id');
		$this->db->join('customer_location_contact clc','clc.customer_id = c.customer_id AND clc.location_id = l.location_id AND clc.contact_id = l.contact_id');
		$this->db->join('location loc','loc.location_id = clc.location_id');
		$this->db->order_by('l.lead_id','DESC');
		$res = $this->db->get();
		return $res->row_array();
	}

	public function getOpportunity_for_edit_demo($lead_id,$opportunity_id)
	{
		$this->db->select('CONCAT("ID - ",o.opp_number," ",p.name,"(",p.description,")") as Opportunity');
		$this->db->from('opportunity o');
		$this->db->where('o.lead_id', $lead_id);
		$this->db->where('o.opportunity_id', $opportunity_id);
		$this->db->where('o.company_id',$this->session->userdata('company'));
		$this->db->where('o.status >=', 3);
		$this->db->where('o.status <=', 6);
		$this->db->where('l.user_id', $this->session->userdata('user_id'));
		$this->db->join('lead l','l.lead_id = o.lead_id');
		$this->db->join('opportunity_product op','op.opportunity_id = o.opportunity_id');
		$this->db->join('product p','p.product_id = op.product_id');
		$res = $this->db->get();
        return $res->row_array();
	}

	public function getDemoname_for_api($demo_product_id)
	{
		$this->db->select(' CONCAT(dpd.serial_number," - ",dpd.location) as demoProduct');
		$this->db->from('demo_product dp');
		$this->db->join('demo_product_details dpd','dpd.demo_product_id = dp.demo_product_id');
		$this->db->where('dp.demo_product_id', $demo_product_id);
		$res = $this->db->get();
		return $res->row_array();
	}

	public function get_near_by_customers($latitude,$longitude,$radius)
	{
		$qry = "SELECT customer_id,name,telephone as contact_number,address1 as address,latitude,longitude,
				(6371 *acos(cos(radians( ".$latitude." )) * 
				cos(radians(latitude)) * 
   				cos(radians(longitude) - 
   				radians(".$longitude.")) + 
   				sin(radians(".$latitude.")) * 
   				sin(radians(latitude )))
				) AS distance 
				FROM customer 
				HAVING distance < ".$radius." 
				ORDER BY distance;";
		$result = $this->db->query($qry);
		return $result->result_array();
	}
	public function getDealerByLocation($userLocation){
		// $this->db->select(' CONCAT(dpd.serial_number," - ",dpd.location) as demoProduct');
		// $this->db->from('user u');
		// $this->db->join('user_location ul','u.user_id = ul.user_id');
		
		// foreach($userLocation as $key){
		// 	$this->db->or_where('ul.location_id',$key['location_id']);
		// }
		// $this->db->where('u.role_id', 5);
		// $this->db->group_by('u.user_id');
		// $res = $this->db->get();
		$q='SELECT * FROM user u JOIN user_location ul ON u.user_id = ul.user_id WHERE  `u`.`role_id` = 5 AND ';
		$q.= '(';
		foreach($userLocation as $key){
				// $this->db->or_where('ul.location_id',$key['location_id']);
				$q.= 'ul.location_id ='.$key['location_id'].' OR ';
			}
			$q= substr($q,0,-3);
			$q.=') GROUP BY u.user_id';
			// echo $q;die;
			$res = $this->db->query($q);	
			return $res->result_array();
		}

		public function getLeadDetail($lead_id){
		$CI = & get_instance();
		$CI->db->select('l.*,c.telephone as contactdetails,c.name as nameofistitue,cd.address1 as address');
		$CI->db->from('lead l');
		$CI->db->join('customer c','c.customer_id = l.customer_id','left');
		$CI->db->join('contact cd','cd.contact_id = l.contact_id','left');
		$CI->db->where('l.lead_id',$lead_id);
		$query = $CI->db->get();
		$data = $query->row_array();
		return $data;
	}

}