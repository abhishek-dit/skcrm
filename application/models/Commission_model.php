<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Commission_model extends CI_Model {

	public function commissionTotalRows($searchParams)
	{
		$role_id=$this->session->userdata('role_id');
		$user_id=$this->session->userdata('user_id');
		$this->db->select('c.name as customer_name,cn.contract_note_id,cn.created_time as cnote_ctime, group_concat(concat(p.name," - ",p.description," (Qty -",o.required_quantity,")") separator "<br>") as opportunity_details,cn.so_number,cn.status as cnote_status, cn.dealer_payment_status as payment_status,dd.distributor_name,qr.dealer_id ');
		$this->db->from('contract_note cn');
		$this->db->join('contract_note_quote_revision cnqr','cn.contract_note_id=cnqr.contract_note_id');
		$this->db->join('quote_revision qr','cnqr.quote_revision_id=qr.quote_revision_id');
		$this->db->join('quote_details qd','qr.quote_id=qd.quote_id');
		//$this->db->join('quote q','qd.quote_id=q.quote_id');
		$this->db->join('opportunity o','qd.opportunity_id=o.opportunity_id');
		$this->db->join('lead l','o.lead_id=l.lead_id');
		$this->db->join('customer c','l.customer_id=c.customer_id');
		$this->db->join('opportunity_product op','o.opportunity_id=op.opportunity_id');
		$this->db->join('product p','op.product_id=p.product_id');
		$this->db->join('dealer_commission_payment dcp','qr.dealer_id=dcp.user_id AND dcp.contract_note_id = cn.contract_note_id AND dcp.status = 1','left');
		$this->db->join('distributor_details dd','qr.dealer_id=dd.user_id','left');
		$this->db->where('qr.dealer_id >',0);
		$this->db->where('cn.company_id',$this->session->userdata('company'));
		if($role_id==5)
		{
			$this->db->where('qr.dealer_id',$user_id);
		}
		else
		{
			$location_string=$this->session->userdata('locationString');
			$this->db->where('l.location_id IN ('.$this->session->userdata('locationString').')');
		}
		if($searchParams['invoice_status']!='')
		{
			$this->db->where('cn.status',$searchParams['invoice_status']);
		}
		if($searchParams['customer_name']!='')
		{
			$this->db->like('c.name',$searchParams['customer_name']);
		}
		if($searchParams['distributor_name']!='')
		{
			$this->db->like('dd.distributor_name',$searchParams['distributor_name']);
		}
		if($searchParams['product_name']!='')
		{
			$where='p.name like "%'.$searchParams['product_name'].'%" OR  p.description like "%'.$searchParams['product_name'].'%"';
					    $this->db->where($where);
		}
		if($searchParams['so_number']!='')
		{
			$this->db->where('cn.so_number',$searchParams['so_number']);
		}
		if($searchParams['payment_status']!='')
		{
			$this->db->where('cn.dealer_payment_status',$searchParams['payment_status']);
		}

		if($searchParams['start_date']!='')
		$this->db->where('DATE(cn.created_time) >=', $searchParams['start_date']);
        if($searchParams['end_date']!='')
		$this->db->where('DATE(cn.created_time) <=', $searchParams['end_date']);
		$this->db->order_by('cn.dealer_payment_status','DESC');
		$this->db->group_by('cn.contract_note_id');
		//$this->db->limit($per_page, $current_offset);
		$res = $this->db->get();
		return $res->num_rows();
	}
	public function commission_results($searchParams,$per_page,$current_offset)
	{   
		$role_id=$this->session->userdata('role_id');
		$user_id=$this->session->userdata('user_id');
		$this->db->select('c.name as customer_name,cn.contract_note_id,cn.created_time as cnote_ctime, group_concat(distinct(concat(p.name," - ",p.description," (Qty -",o.required_quantity,")")) separator "<br>") as opportunity_details,cn.so_number,cn.status as cnote_status, cn.dealer_payment_status as payment_status,dd.distributor_name,qr.dealer_id ,
			round(sum(qr.dealer_commission*((CASE WHEN ma.discount_type=1 
						THEN (o.required_quantity*qd.mrp)*(1-ma.discount/100) 
						ELSE ((o.required_quantity*qd.mrp)-ma.discount) END )/(1+qd.freight_insurance/100)/(1+qd.gst/100)) / 100 ))as amount

			');
		$this->db->from('contract_note cn');
		$this->db->join('contract_note_quote_revision cnqr','cn.contract_note_id=cnqr.contract_note_id');
		$this->db->join('quote_revision qr','cnqr.quote_revision_id=qr.quote_revision_id and qr.dealer_commission >0 ');
		$this->db->join('quote_details qd','qr.quote_id=qd.quote_id');
		//$this->db->join('quote q','qd.quote_id=q.quote_id');
		$this->db->join('opportunity o','qd.opportunity_id=o.opportunity_id');
		$this->db->join('lead l','o.lead_id=l.lead_id');
		$this->db->join('customer c','l.customer_id=c.customer_id');
		$this->db->join('opportunity_product op','o.opportunity_id=op.opportunity_id');
		$this->db->join('product p','op.product_id=p.product_id');
		$this->db->join('dealer_commission_payment dcp','qr.dealer_id=dcp.user_id AND dcp.contract_note_id = cn.contract_note_id AND dcp.status = 1','left');
		$this->db->join('quote_op_margin_approval ma','ma.quote_revision_id=qr.quote_revision_id and ma.opportunity_id=o.opportunity_id','left');
		$this->db->join('distributor_details dd','qr.dealer_id=dd.user_id','left');
		$this->db->where('qr.dealer_id >',0);
		$this->db->where('cn.company_id',$this->session->userdata('company'));
		if($role_id==5)
		{
			$this->db->where('qr.dealer_id',$user_id);
		}
		else
		{
			$location_string=$this->session->userdata('locationString');
			$this->db->where('l.location_id IN ('.$this->session->userdata('locationString').')');
		}
		if($searchParams['invoice_status']!='')
		{
			$this->db->where('cn.status',$searchParams['invoice_status']);
		}
		if($searchParams['customer_name']!='')
		{
			$this->db->like('c.name',$searchParams['customer_name']);
		}
		if($searchParams['distributor_name']!='')
		{
			$this->db->like('dd.distributor_name',$searchParams['distributor_name']);
		}
		if($searchParams['product_name']!='')
		{
			$where='p.name like "%'.$searchParams['product_name'].'%" OR  p.description like "%'.$searchParams['product_name'].'%"';
					    $this->db->where($where);
		}
		if($searchParams['so_number']!='')
		{
			$this->db->where('cn.so_number',$searchParams['so_number']);
		}
		if($searchParams['payment_status']!='')
		{
			$this->db->where('cn.dealer_payment_status',$searchParams['payment_status']);
		}

		if($searchParams['start_date']!='')
		$this->db->where('DATE(cn.created_time) >=', $searchParams['start_date']);
        if($searchParams['end_date']!='')
		$this->db->where('DATE(cn.created_time)<=', $searchParams['end_date']);
		$this->db->order_by('cn.dealer_payment_status','DESC');
		$this->db->group_by('cn.contract_note_id');
		$this->db->limit($per_page, $current_offset);
		$res = $this->db->get();
		//echo $this->db->last_query();exit;
		if($res->num_rows()>0)
		{
			return $res->result_array();
		}

	}
}
