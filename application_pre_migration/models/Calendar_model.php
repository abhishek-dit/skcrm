<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Calendar_model extends CI_Model {

	public function visitResults($searchParams, $per_page, $current_offset)
	{
		
		$this->db->select('v.*, IF(v.end_date < CURDATE(), "1", "0") is_expired, CONCAT("Lead ID - ",v.lead_id," (",c.name," ",c.name1," - ",c.department," (",loc.location,"))") as CustomerName, vp.name as Purpose');
		$this->db->from('visit v');
		if($searchParams['leadId']!='')
		$this->db->where('v.lead_id', $searchParams['leadId']);
		if($searchParams['customer']!='')
		$this->db->where('c.customer_id', $searchParams['customer']);
		if($searchParams['startDate']!='')
		$this->db->where('v.start_date >=', $searchParams['startDate']);
		if($searchParams['endDate']!='')
		$this->db->where('v.end_date <=', $searchParams['endDate']);
		$this->db->where('l.user_id', $this->session->userdata('user_id'));
		$this->db->join('visit_purpose vp','v.purpose_id = vp.purpose_id');
		$this->db->join('lead l','l.lead_id = v.lead_id');
		$this->db->join('customer c','c.customer_id = l.customer_id');
		$this->db->join('customer_location_contact clc','clc.customer_id = c.customer_id AND clc.location_id = l.location_id AND clc.contact_id = l.contact_id');
		$this->db->join('location loc','loc.location_id = clc.location_id');
		$this->db->limit($per_page, $current_offset);
		$this->db->order_by('v.lead_id','DESC');
		$res = $this->db->get();
		return $res->result_array();
	}

	public function visitDetails($searchParams)
	{
		$this->db->select('v.*, CONCAT("Lead ID - ",v.lead_id," (",c.name," ",c.name1," - ",c.department," (",loc.location,"))") as CustomerName, vp.name as Purpose');
		$this->db->from('visit v');
		if($searchParams['leadId']!='')
		$this->db->where('v.lead_id', $searchParams['leadId']);
		if($searchParams['customer']!='')
		$this->db->where('c.customer_id', $searchParams['customer']);
		if($searchParams['startDate']!='')
		$this->db->where('v.start_date >=', $searchParams['startDate']);
		if($searchParams['endDate']!='')
		$this->db->where('v.end_date <=', $searchParams['endDate']);
		$this->db->where('l.user_id', $this->session->userdata('user_id'));
		$this->db->join('visit_purpose vp','v.purpose_id = vp.purpose_id');
		$this->db->join('lead l','l.lead_id = v.lead_id');
		$this->db->join('customer c','c.customer_id = l.customer_id');
		$this->db->join('customer_location_contact clc','clc.customer_id = c.customer_id AND clc.location_id = l.location_id AND clc.contact_id = l.contact_id');
		$this->db->join('location loc','loc.location_id = clc.location_id');
		$res = $this->db->get();
		return $res->result_array();
	}
	
	public function visitTotalRows($searchParams)
	{
		$this->db->from('visit v');
		if($searchParams['leadId']!='')
		$this->db->where('v.lead_id', $searchParams['leadId']);
		if($searchParams['customer']!='')
		$this->db->where('c.customer_id', $searchParams['customer']);
		if($searchParams['startDate']!='')
		$this->db->where('v.start_date >=', $searchParams['startDate']);
		if($searchParams['endDate']!='')
		$this->db->where('v.end_date <=', $searchParams['endDate']);
		$this->db->where('l.user_id', $this->session->userdata('user_id'));
		$this->db->join('visit_purpose vp','v.purpose_id = vp.purpose_id');
		$this->db->join('lead l','l.lead_id = v.lead_id');
		$this->db->join('customer c','c.customer_id = l.customer_id');
		$this->db->join('customer_location_contact clc','clc.customer_id = c.customer_id AND clc.location_id = l.location_id AND clc.contact_id = l.contact_id');
		$this->db->join('location loc','loc.location_id = clc.location_id');
		$res = $this->db->get();
		return $res->num_rows();
	}

	public function checkVisitAvailability($data)
	{
		$array = array('start_date >=' => $data['start_date'], 'start_date <=' => $data['end_date']);
		$array1 = array('start_date <=' => $data['start_date'], 'end_date >=' => $data['end_date']);
		$array2 = array('end_date >=' => $data['start_date'], 'end_date <=' => $data['end_date']);

		$this->db->from('visit');
		//if($data['visit_id']!='')
		$this->db->where('visit_id !=', $data['visit_id']);
		$this->db->where('lead_id', $data['lead_id']);
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
	}

	public function demoResults($searchParams, $per_page, $current_offset)
	{
		$this->db->select('d.*, IF(d.end_date < CURDATE(), "1", "0") is_expired, CONCAT(c.name," ",c.name1," - ",c.department," (",loc.location,")") as CustomerName, CONCAT("ID - ", d.opportunity_id, ": ", p.name," (",p.description,")") as opportunity, CONCAT(dpd.serial_number," - ",dpd.location) as demo');
		$this->db->from('demo d');
		if($searchParams['opportunityId']!='')
		$this->db->where('d.opportunity_id', $searchParams['opportunityId']);
		if($searchParams['customer']!='')
		$this->db->where('c.customer_id', $searchParams['customer']);
		if($searchParams['startDate']!='')
		$this->db->where('d.start_date >=', $searchParams['startDate']);
		if($searchParams['endDate']!='')
		$this->db->where('d.end_date <=', $searchParams['endDate']);
		$this->db->where('l.user_id', $this->session->userdata('user_id'));
		$this->db->join('demo_product dp', 'dp.demo_product_id = d.demo_product_id AND dp.product_id = d.product_id');
		$this->db->join('demo_product_details dpd','dpd.demo_product_id = dp.demo_product_id');
		$this->db->join('product p','p.product_id = d.product_id');
		$this->db->join('opportunity_product op', 'op.opportunity_id = d.opportunity_id AND op.product_id = d.product_id');
		$this->db->join('opportunity o', 'o.opportunity_id = op.opportunity_id');
		$this->db->join('lead l','l.lead_id = o.lead_id');
		$this->db->join('customer c','c.customer_id = l.customer_id');
		$this->db->join('customer_location_contact clc','clc.customer_id = c.customer_id AND clc.location_id = l.location_id AND clc.contact_id = l.contact_id');
		$this->db->join('location loc','loc.location_id = clc.location_id');
		$this->db->limit($per_page, $current_offset);
		$this->db->order_by('d.opportunity_id','DESC');
		$res = $this->db->get();
		return $res->result_array();
	}

	public function demoDetails($searchParams)
	{
		$this->db->select('d.*, CONCAT(c.name," ",c.name1," - ",c.department," (",loc.location,")") as CustomerName, CONCAT(p.name," (",p.description,")") as opportunity, CONCAT(dpd.serial_number," - ",dpd.location) as demo');
		$this->db->from('demo d');
		if($searchParams['opportunityId']!='')
		$this->db->where('d.opportunity_id', $searchParams['opportunityId']);
		if($searchParams['customer']!='')
		$this->db->where('c.customer_id', $searchParams['customer']);
		if($searchParams['startDate']!='')
		$this->db->where('d.start_date >=', $searchParams['startDate']);
		if($searchParams['endDate']!='')
		$this->db->where('d.end_date <=', $searchParams['endDate']);
		$this->db->where('l.user_id', $this->session->userdata('user_id'));
		$this->db->join('demo_product dp', 'dp.demo_product_id = d.demo_product_id AND dp.product_id = d.product_id');
		$this->db->join('demo_product_details dpd','dpd.demo_product_id = dp.demo_product_id');
		$this->db->join('product p','p.product_id = d.product_id');
		$this->db->join('opportunity_product op', 'op.opportunity_id = d.opportunity_id AND op.product_id = d.product_id');
		$this->db->join('opportunity o', 'o.opportunity_id = op.opportunity_id');
		$this->db->join('lead l','l.lead_id = o.lead_id');
		$this->db->join('customer c','c.customer_id = l.customer_id');
		$this->db->join('customer_location_contact clc','clc.customer_id = c.customer_id AND clc.location_id = l.location_id AND clc.contact_id = l.contact_id');
		$this->db->join('location loc','loc.location_id = clc.location_id');
		$res = $this->db->get();
		return $res->result_array();
	}
	
	public function demoTotalRows($searchParams)
	{
		$this->db->from('demo d');
		if($searchParams['opportunityId']!='')
		$this->db->where('d.opportunity_id', $searchParams['opportunityId']);
		if($searchParams['customer']!='')
		$this->db->where('c.customer_id', $searchParams['customer']);
		if($searchParams['startDate']!='')
		$this->db->where('d.start_date >=', $searchParams['startDate']);
		if($searchParams['endDate']!='')
		$this->db->where('d.end_date <=', $searchParams['endDate']);
		$this->db->where('l.user_id', $this->session->userdata('user_id'));
		$this->db->join('demo_product dp', 'dp.demo_product_id = d.demo_product_id AND dp.product_id = d.product_id');
		$this->db->join('demo_product_details dpd','dpd.demo_product_id = dp.demo_product_id');
		$this->db->join('product p','p.product_id = d.product_id');
		$this->db->join('opportunity_product op', 'op.opportunity_id = d.opportunity_id AND op.product_id = d.product_id');
		$this->db->join('opportunity o', 'o.opportunity_id = op.opportunity_id');
		$this->db->join('lead l','l.lead_id = o.lead_id');
		$this->db->join('customer c','c.customer_id = l.customer_id');
		$this->db->join('customer_location_contact clc','clc.customer_id = c.customer_id AND clc.location_id = l.location_id AND clc.contact_id = l.contact_id');
		$this->db->join('location loc','loc.location_id = clc.location_id');
		$res = $this->db->get();
		return $res->num_rows();
	}

	public function checkDemoAvailability($data)
	{
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
	}

	public function visitCalendarDetails($user_id)
	{
		$this->db->select('v.visit_id as id, v.start_date as start,v.end_date as end, CONCAT(c.name," ",c.name1," - ",c.department," (",loc.location,")") as description, c.name as title');
		$this->db->from('visit v');
		$this->db->where('v.status', 1);
		$this->db->where('l.user_id', $user_id);
		$this->db->join('lead l','l.lead_id = v.lead_id');
		$this->db->join('customer c','c.customer_id = l.customer_id');
		$this->db->join('customer_location_contact clc','clc.customer_id = c.customer_id AND clc.location_id = l.location_id AND clc.contact_id = l.contact_id');
		$this->db->join('location loc','loc.location_id = clc.location_id');
		$this->db->order_by('v.lead_id','DESC');
		$res = $this->db->get();
		return $res->result_array();
	}

	public function demoCalendarDetails($user_id)
	{
		$this->db->select('d.demo_id as id, d.start_date as start,d.end_date as end, CONCAT("Customer : ", c.name," ",c.name1," - ",c.department," (",loc.location,") Product : ", p.name," (",p.description,")"," Serial Number : ", dpd.serial_number) as description, c.name as title');
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
		$this->db->select('l.lead_id, CONCAT(c.name," ",c.name1," - ",c.department," (",loc.location,")") as CustomerName');
		$this->db->from('lead l');
		$this->db->where('l.user_id', $this->session->userdata('user_id'));
		$this->db->where('l.status >', 1);
		$this->db->where('l.status <', 20);
		if($check == 0)
		$this->db->where('l.visit_requirement', 1);
		if($check==3){
			$this->db->where('l.site_readiness_id >', 0);
			$this->db->where('l.relationship_id >', 0);
		}
		$this->db->join('customer c','l.customer_id = c.customer_id');
		$this->db->join('customer_location_contact clc','clc.customer_id = c.customer_id AND clc.location_id = l.location_id AND clc.contact_id = l.contact_id');
		$this->db->join('location loc','loc.location_id = clc.location_id');
		$this->db->order_by('l.lead_id','DESC');
		$res = $this->db->get();
		return $res->result_array();
	}

	public function getOpportunity($lead_id)
	{
		$this->db->select('o.opportunity_id as opportunityId, CONCAT("ID - ",o.opportunity_id," ",p.name,"(",p.description,")") as ProductName');
		$this->db->from('opportunity o');
		$this->db->where('o.lead_id', $lead_id);
		$this->db->where('o.status >=', 3);
		$this->db->where('o.status <', 6);
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
		$this->db->select('dpd.demo_product_id as demoProductId, CONCAT(dpd.serial_number," - ",dpd.location) as demoProduct');
		$this->db->from('opportunity_product op');
		$this->db->where('op.opportunity_id', $opportunity_id);
		$this->db->join('demo_product dp','dp.product_id = op.product_id');
		$this->db->join('demo_product_details dpd','dpd.demo_product_id = dp.demo_product_id');
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
		$res = $this->db->get();
		return $res->num_rows();
	}
}