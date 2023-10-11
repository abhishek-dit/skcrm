<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Otr_m extends CI_Model {
	//prasad new enhancements phase2
	public function contract_note_total_rows($searchParams,$locations)
	{   
		$role_id = $this->session->userdata('role_id');
		$this->db->from('contract_note cn');
		$this->db->join('contract_note_quote_revision cnqr','cnqr.contract_note_id = cn.contract_note_id','left');
		$this->db->join('quote_revision qr','qr.quote_revision_id = cnqr.quote_revision_id','left');
		$this->db->join('quote_details qd','qd.quote_id = qr.quote_id','left');
		$this->db->join('opportunity o','o.opportunity_id = qd.opportunity_id','left');
		$this->db->join('lead l','l.lead_id = o.lead_id','left');
		$this->db->join('customer c','c.customer_id = l.customer_id','left');
		$this->db->join('contract_note_po_revision cnpr','cnpr.contract_note_id = cn.contract_note_id','left');
		$this->db->join('po_revision pr','pr.po_revision_id = cnpr.po_revision_id','left');
		$this->db->join('purchase_order po','po.purchase_order_id = pr.purchase_order_id','left');
		$this->db->join('distributor_details dd','dd.user_id = po.user_id','left');
		$this->db->where('cn.company_id',$this->session->userdata('company'));
		if($searchParams['contract_note_id']!='')
			$this->db->where('cn.contract_note_id',$searchParams['contract_note_id']);
		if($searchParams['cnote_type']!='')
			$this->db->where('cn.cnote_type',$searchParams['cnote_type']);
		if($searchParams['billing_party']!='')
			$this->db->like('IF(cn.cnote_type = 1,c.name,dd.distributor_name)',$searchParams['billing_party']);
		$status=array(1,2);
		$this->db->where_in('cn.status', $status);
		$this->db->where('cn.created_by IN ('.$this->session->userdata('reportees').','.$this->session->userdata('user_id').')');
		$res = $this->db->get();
		return $res->num_rows();

	}
	//prasad new enhancements phase2
	public function contract_note_results($searchParams, $per_page, $current_offset,$locations)
	{
		/*$this->db->select('l.lead_id,qr.quote_id,qr.quote_revision_id,cn.contract_note_id, cn.purchase_order_no as po_number, 
                cn.date_of_purchase_order as po_date, cn.SO_number as so_number,o.opportunity_id,cn.billing_to_party,
                c.name as customer_name, CONCAT(u.first_name," ",u.last_name) as lead_owner_name, u.employee_id as lead_owner_emp_id,cn.created_time');*/
		$this->db->select('cn.contract_note_id,cn.cnote_number, cn.purchase_order_no as po_number, 
                cn.date_of_purchase_order as po_date,cn.billing_to_party,cn.cnote_type,cn.created_time,
                IF(cn.cnote_type = 1,c.name,dd.distributor_name) customer_name , CONCAT(u.first_name," ",u.last_name) as lead_owner_name, 
                u.employee_id as lead_owner_emp_id, cn.SO_number as so_number, l.lead_id
                ');
		$this->db->from('contract_note cn');
		$this->db->join('contract_note_quote_revision cnqr','cnqr.contract_note_id = cn.contract_note_id','left');
		$this->db->join('quote_revision qr','qr.quote_revision_id = cnqr.quote_revision_id','left');
		$this->db->join('quote_details qd','qd.quote_id = qr.quote_id','left');
		$this->db->join('opportunity o','o.opportunity_id = qd.opportunity_id','left');
		$this->db->join('lead l','l.lead_id = o.lead_id','left');
		$this->db->join('customer c','c.customer_id = l.customer_id','left');
		$this->db->join('user u','u.user_id = l.user_id','left');
		$this->db->join('contract_note_po_revision cnpr','cnpr.contract_note_id = cn.contract_note_id','left');
		$this->db->join('po_revision pr','pr.po_revision_id = cnpr.po_revision_id','left');
		$this->db->join('purchase_order po','po.purchase_order_id = pr.purchase_order_id','left');
		$this->db->join('distributor_details dd','dd.user_id = po.user_id','left');
		$this->db->where('cn.company_id',$this->session->userdata('company'));
		if($searchParams['contract_note_id']!='')
			$this->db->where('cn.cnote_number',$searchParams['contract_note_id']);
		if($searchParams['cnote_type']!='')
			$this->db->where('cn.cnote_type',$searchParams['cnote_type']);
		if($searchParams['billing_party']!='')
			$this->db->like('IF(cn.cnote_type = 1,c.name,dd.distributor_name)',$searchParams['billing_party']);
		$status=array(1,2);
		$this->db->where_in('cn.status', $status);
		$this->db->group_by('cn.contract_note_id');
		$this->db->order_by('cn.contract_note_id','desc');
		$this->db->limit($per_page, $current_offset);
	    $res = $this->db->get();
		return $res->result_array();
	}
}