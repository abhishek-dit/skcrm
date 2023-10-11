<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cnote_rbh_approval_m extends CI_Model {
	//prasad new enhancements phase2, Updated: 11-10-2017 by Mahesh
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
		if($searchParams['contract_note_id']!='')
			$this->db->where('cn.contract_note_id',$searchParams['contract_note_id']);
		if($searchParams['cnote_type']!='')
			$this->db->where('cn.cnote_type',$searchParams['cnote_type']);
		if($searchParams['billing_party']!='')
			$this->db->like('IF(cn.cnote_type = 1,c.name,dd.distributor_name)',$searchParams['billing_party']);
		$this->db->where('cn.status', 3);
		$this->db->where('cn.created_by IN ('.$this->session->userdata('reportees').','.$this->session->userdata('user_id').')');
		if($role_id==8) // If NSM
		{
			$locations_without_rbh = getRegionsWithoutRbh();
			$this->db->join('user_location ul','ul.user_id = cn.created_by');
			$this->db->join('location l1','l1.location_id = ul.location_id','left');
			$this->db->join('location l2','l2.location_id = l1.parent_id','left');
			$this->db->join('location l3','l3.location_id = l2.parent_id','left');
			$this->db->where('CASE WHEN l1.territory_level_id = 7 THEN l3.parent_id WHEN l1.territory_level_id = 6 THEN l2.parent_id
			WHEN l1.territory_level_id = 5 THEN l1.parent_id WHEN l1.territory_level_id = 4 THEN l1.location_id END   in ('.$locations_without_rbh.')');
			
		}
		$this->db->group_by('cn.contract_note_id');
		$res = $this->db->get();
		return $res->num_rows();

	}
	//prasad new enhancements phase2, Updated: 11-10-2017 by Mahesh
	public function contract_note_results($searchParams, $per_page, $current_offset,$locations)
	{
		$role_id = $this->session->userdata('role_id');
		$this->db->select('cn.contract_note_id, cn.purchase_order_no as po_number, 
                cn.date_of_purchase_order as po_date,cn.billing_to_party,cn.cnote_type,cn.created_time,
                IF(cn.cnote_type = 1,c.name,dd.distributor_name) customer_name
                ');
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

		if($searchParams['contract_note_id']!='')
			$this->db->where('cn.contract_note_id',$searchParams['contract_note_id']);
		if($searchParams['cnote_type']!='')
			$this->db->where('cn.cnote_type',$searchParams['cnote_type']);
		if($searchParams['billing_party']!='')
			$this->db->like('IF(cn.cnote_type = 1,c.name,dd.distributor_name)',$searchParams['billing_party']);
		$this->db->where('cn.status', 3);
		$this->db->where('cn.created_by IN ('.$this->session->userdata('reportees').','.$this->session->userdata('user_id').')');
		if($role_id==8) // If NSM
		{
			$locations_without_rbh = getRegionsWithoutRbh();
			$this->db->join('user_location ul','ul.user_id = cn.created_by');
			$this->db->join('location l1','l1.location_id = ul.location_id','left');
			$this->db->join('location l2','l2.location_id = l1.parent_id','left');
			$this->db->join('location l3','l3.location_id = l2.parent_id','left');
			$this->db->where('CASE WHEN l1.territory_level_id = 7 THEN l3.parent_id WHEN l1.territory_level_id = 6 THEN l2.parent_id
			WHEN l1.territory_level_id = 5 THEN l1.parent_id WHEN l1.territory_level_id = 4 THEN l1.location_id END   in ('.$locations_without_rbh.')');
			
		}
		$this->db->group_by('cn.contract_note_id');
		$this->db->limit($per_page, $current_offset);
		$res = $this->db->get();
		return $res->result_array();
	}

	public function getAllContractQuotes($lead_id)
	{
		$qry = 'SELECT qr.quote_revision_id, q.quote_id, group_concat(concat(p.name, " - Qty ", o.required_quantity) SEPARATOR ", ") as opportunity from lead l
				INNER JOIN opportunity o ON o.lead_id = l.lead_id
				INNER JOIN quote_details qd ON qd.opportunity_id = o.opportunity_id
				INNER JOIN opportunity_product op ON op.opportunity_id = o.opportunity_id
				INNER JOIN product p ON p.product_id = op.product_id
				INNER JOIN quote q ON q.quote_id = qd.quote_id
                INNER JOIN quote_revision qr ON q.quote_id = qr.quote_id
				WHERE q.status IN (2,6,5) AND qr.status = 1 AND l.lead_id ="'.$lead_id.'"
            	group by q.quote_id';
        $res = $this->db->query($qry);
        return $res->result_array();
	}
	public function get_free_supply_items($contract_note_id)
	{
		$this->db->from('free_products fp');
		$this->db->join('product p','fp.product_id=p.product_id');
		$this->db->where('fp.contract_note_id',$contract_note_id);
		$this->db->where('p.status',1);
		$res = $this->db->get();
		return $res->result_array();
	}
	public function get_mails_otr($region_id)
	{
		$this->db->select('u.*');
		$this->db->from('user_location ul');
		$this->db->join('user u','ul.user_id=u.user_id');
		$this->db->join('location l','l.location_id = ul.location_id');
		$this->db->join('location l2','l2.parent_id = l.location_id');
		$this->db->where('ul.status',1);
		$this->db->where('u.status',1);
		$where = '(l.location_id = '.$region_id.' OR l2.location_id = '.$region_id.')';
		$this->db->where($where);
		$this->db->where('u.role_id',15); // OTR
		$this->db->group_by('u.user_id');
		$res=$this->db->get();
		return $res->result_array();
	}

	//Mahesh: 11-10-2017 
	public function getLeadIdByCNote($contract_note_id)
	{   
		$this->db->select('o.lead_id');
		$this->db->from('contract_note cn');
		$this->db->join('contract_note_quote_revision cnqr','cn.contract_note_id = cnqr.contract_note_id');
		$this->db->join('quote_revision qr','qr.quote_revision_id = cnqr.quote_revision_id');
		$this->db->join('quote_details qd','qr.quote_id = qd.quote_id');
		$this->db->join('opportunity o','o.opportunity_id = qd.opportunity_id');
		$this->db->group_by('cn.contract_note_id');
		$res = $this->db->get();
		if($res->num_rows()>0)
		{
			$row = $res->row_array();
			return $row['lead_id'];
		}

	}

	//Mahesh: 12-10-2017 
	public function getPoIdByCNote($contract_note_id)
	{   
		$this->db->select('pr.purchase_order_id');
		$this->db->from('contract_note cn');
		$this->db->join('contract_note_po_revision cnpr','cn.contract_note_id = cnpr.contract_note_id');
		$this->db->join('po_revision pr','pr.po_revision_id = cnpr.po_revision_id');
		$this->db->where('cn.contract_note_id',$contract_note_id);
		$this->db->group_by('cn.contract_note_id');
		$res = $this->db->get();
		if($res->num_rows()>0)
		{
			$row = $res->row_array();
			return $row['purchase_order_id'];
		}

	}
}