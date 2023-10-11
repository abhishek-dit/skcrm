<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Contract_model extends CI_Model {

	public function getCNoteDetails($lead_id)
	{
        $qry = 'SELECT cn.contract_note_id, cn.purchase_order_no as po_number, 
                cn.date_of_purchase_order as po_date, cn.SO_number as so_number,cn.status as status from contract_note cn 
                WHERE cn.contract_note_id IN (
                    SELECT contract_note_id from contract_note_quote_revision cr
                    INNER JOIN quote_revision qr ON qr.quote_revision_id = cr.quote_revision_id
                    INNER JOIN quote q ON q.quote_id = qr.quote_id
                    INNER JOIN quote_details qd ON qd.quote_id = q.quote_id
                    INNER JOIN opportunity o ON o.opportunity_id = qd.opportunity_id
                    WHERE o.lead_id = "'.$lead_id.'" GROUP by cr.contract_note_id)';
        $res1 = $this->db->query($qry);        
        $res['resArray'] = $res1->result_array();
        $res['count'] = $res1->num_rows();
        return $res;

	}

	public function getAllLeadQuotes($lead_id)
	{
		$qry = 'SELECT qr.quote_revision_id, q.quote_id, group_concat(concat(p.description, " - Qty ", o.required_quantity) SEPARATOR ", ") as opportunity from lead l
				INNER JOIN opportunity o ON o.lead_id = l.lead_id
				INNER JOIN quote_details qd ON qd.opportunity_id = o.opportunity_id
				INNER JOIN opportunity_product op ON op.opportunity_id = o.opportunity_id
				INNER JOIN product p ON p.product_id = op.product_id
				INNER JOIN quote q ON q.quote_id = qd.quote_id
                INNER JOIN quote_revision qr ON q.quote_id = qr.quote_id
				WHERE q.status IN (2,6) AND qr.status = 1 AND l.lead_id ="'.$lead_id.'"
            	group by q.quote_id';
        $res = $this->db->query($qry);
        return $res->result_array();
	}

    public function soEntry($status)
    {
        $this->db->select('c.contract_note_id, q.quote_id, b.name as billing, q.discount, c.purchase_order_no as po_number, c.so_number, c.date_of_purchase_order as po_date');
        $this->db->from('contract_note c'); 
        $this->db->join('quote q', 'q.quote_id = c.quote_id');   
        $this->db->join('billing b', 'b.billing_info_id = q.billing_info_id');
        $this->db->where('c.status', $status);  
        $res = $this->db->get();
        $data = $res->result_array();    
        return $data;
    }

    //mahesh 14th july 2016 7:36 pm
    public function soEntryTotalRows($status,$searchParams)
    {

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
        $this->db->join('user u2','cn.created_by = u2.user_id');
        if($searchParams['contract_note_id']!='')
            $this->db->where('cn.contract_note_id',$searchParams['contract_note_id']);
        if($searchParams['cnote_type']!='')
            $this->db->where('cn.cnote_type',$searchParams['cnote_type']);
        if($searchParams['billing_party']!='')
            $this->db->like('IF(cn.cnote_type = 1,c.name,dd.distributor_name)',$searchParams['billing_party']);

        $this->db->where('cn.status', $status);
        $this->db->where('u2.company_id',$this->session->userdata('company'));
        $this->db->group_by('cn.contract_note_id');
        $res = $this->db->get();
        //echo $this->db->last_query(); exit;
        return $res->num_rows();
    }

    //mahesh 14th july 2016 7:45 pm
    public function soEntryResults($status,$searchParams,$current_offset=0,$per_page=10)
    {
        
        $this->db->select('cn.contract_note_id, cn.purchase_order_no as po_number, 
                cn.date_of_purchase_order as po_date,cn.billing_to_party,cn.cnote_type,cn.created_time,
                IF(cn.cnote_type = 1,c.name,dd.distributor_name) customer_name , CONCAT(u.first_name," ",u.last_name) as lead_owner_name, 
                u.employee_id as lead_owner_emp_id, cn.SO_number as so_number, l.lead_id,cn.status
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
        $this->db->join('user u2','cn.created_by = u2.user_id');

        if($searchParams['contract_note_id']!='')
            $this->db->where('cn.contract_note_id',$searchParams['contract_note_id']);
        if($searchParams['cnote_type']!='')
            $this->db->where('cn.cnote_type',$searchParams['cnote_type']);
        if($searchParams['billing_party']!='')
            $this->db->like('IF(cn.cnote_type = 1,c.name,dd.distributor_name)',$searchParams['billing_party']);
        $this->db->where('cn.status', $status);
        $this->db->where('u2.company_id',$this->session->userdata('company'));
        $this->db->group_by('cn.contract_note_id');
        $this->db->order_by('cn.contract_note_id','desc');
        $this->db->limit($per_page, $current_offset);
        $res = $this->db->get();
        //echo $this->db->last_query(); exit;
        return $res->result_array();
    }

    //mahesh 14th july 2016 7:54 pm
    public function soEntryDetails($status,$searchParams)
    {
        
        /*$qry = 'SELECT cn.contract_note_id, cn.purchase_order_no as po_number, 
                cn.date_of_purchase_order as po_date, cn.SO_number as so_number,cn.status,cn.created_by,cn.created_time, cn.modified_by, cn.modified_time from contract_note cn 
                WHERE cn.contract_note_id IN (
                    SELECT contract_note_id from contract_note_quote_revision cr
                    INNER JOIN quote_revision qr ON qr.quote_revision_id = cr.quote_revision_id
                    INNER JOIN quote q ON q.quote_id = qr.quote_id
                    INNER JOIN quote_details qd ON qd.quote_id = q.quote_id
                    INNER JOIN opportunity o ON o.opportunity_id = qd.opportunity_id
                    ';
        if(@$searchParams['search_lead_id']!='')
            $qry .= ' WHERE o.lead_id = "'.@$searchParams['search_lead_id'].'" ';
        $qry .= ' GROUP by cr.contract_note_id) AND cn.created_by IN ('.$this->session->userdata("reportees").', '.$this->session->userdata("user_id").')';
        if(@$status!='')
            $qry .= ' AND cn.status = "'.@$status.'" ';
        if(@$searchParams['contract_id']!='')
            $qry .= ' AND cn.contract_note_id = "'.@$searchParams['contract_id'].'" ';
        if(@$searchParams['po_number']!='')
            $qry .= ' AND cn.purchase_order_no like "%'.@$searchParams['po_number'].'%" ';
        if($this->session->userdata('role_id')!=2&&$this->session->userdata('role_id')!=14){ // If logged in user role is not admin and CIC
            if(@$status == 2)
            {
                $qry .= ' AND cn.created_by IN ('.$this->session->userdata('userProductReportees').','.$this->session->userdata('user_id').')';
            }
            else
            {
                $qry .= ' AND cn.created_by IN ('.$this->session->userdata('user_id').')';
            }
        }
        $qry .= ' order by cn.contract_note_id DESC ';
        $res = $this->db->query($qry);  
        return $res->result_array();*/
        $this->db->select('cn.contract_note_id, cn.purchase_order_no as po_number, 
                cn.date_of_purchase_order as po_date,cn.billing_to_party,cn.cnote_type,cn.created_time,
                IF(cn.cnote_type = 1,c.name,dd.distributor_name) customer_name , CONCAT(u.first_name," ",u.last_name) as lead_owner_name, 
                u.employee_id as lead_owner_emp_id, cn.SO_number as so_number, l.lead_id,cn.status,cn.created_by,cn.modified_by,cn.modified_time
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
        $this->db->join('user u2','cn.created_by = u2.user_id');

        if($searchParams['contract_note_id']!='')
            $this->db->where('cn.contract_note_id',$searchParams['contract_note_id']);
        if($searchParams['cnote_type']!='')
            $this->db->where('cn.cnote_type',$searchParams['cnote_type']);
        if($searchParams['billing_party']!='')
            $this->db->like('IF(cn.cnote_type = 1,c.name,dd.distributor_name)',$searchParams['billing_party']);
        $this->db->where('cn.status', $status);
        $this->db->where('u2.company_id',$this->session->userdata('company'));
        $this->db->group_by('cn.contract_note_id');
        $this->db->order_by('cn.contract_note_id','desc');
        $res = $this->db->get();
        //echo $this->db->last_query(); exit;
        return $res->result_array();
    }

    /** new enhancement: Suresh 20th april 2017
    *** Delete Contract Notes
                                      START **/
    public function manageCNoteDetails($searchParams, $per_page, $current_offset)
    {
        if($searchParams != ''){
            $contract_note_id = $searchParams['contract_note_id'];
        }
        
        /*$qry = 'SELECT cn.contract_note_id, cn.purchase_order_no as po_number, 
                cn.date_of_purchase_order as po_date, cn.SO_number as so_number from contract_note cn 
                ';*/
        $this->db->select('cn.contract_note_id, cn.purchase_order_no as po_number, 
                cn.date_of_purchase_order as po_date, cn.SO_number as so_number,l.lead_id');
        $this->db->from('contract_note cn ');
        $this->db->join('contract_note_quote_revision cnqr','cn.contract_note_id = cnqr.contract_note_id');
        $this->db->join('quote_revision qr','qr.quote_revision_id = cnqr.quote_revision_id','left');
        $this->db->join('quote q','qr.quote_id = q.quote_id','left');
        $this->db->join('quote_details qd','qd.quote_id = q.quote_id','left');
        $this->db->join('opportunity o','o.opportunity_id = qd.opportunity_id','left');
        $this->db->join('lead l','l.lead_id = o.lead_id');
        if($searchParams['contract_note_id']!='')
        {
            $cnoteids = explode(',',$searchParams['contract_note_id']);
            $this->db->where_in('cn.contract_note_id',$cnoteids);
        }
        $this->db->order_by('contract_note_id','DESC');
        $this->db->limit($per_page,$current_offset);
        $this->db->group_by('cn.contract_note_id');
        /*if($searchParams['contract_note_id']!='')
            $qry.=' WHERE cn.contract_note_id IN ('.trim($searchParams['contract_note_id'],', ').') ';
        $qry.=' ORDER BY contract_note_id DESC LIMIT '.$current_offset.','.$per_page.' ';
        $res1 = $this->db->query($qry); */   
        $res1 = $this->db->get();    
        $res['resArray'] = $res1->result_array();
        $res['count'] = $res1->num_rows();
        return $res;

    }
    
    public function manageCNoteDetailsTotalRows($searchParams){
        /*$qry = 'SELECT cn.contract_note_id, cn.purchase_order_no as po_number, 
                cn.date_of_purchase_order as po_date, cn.SO_number as so_number from contract_note cn 
                ';
        if($searchParams['contract_note_id']!='')
            $qry.=' WHERE cn.contract_note_id IN ('.trim($searchParams['contract_note_id'],', ').') ';

        $res1 = $this->db->query($qry)*/;
        $this->db->select();
        $this->db->from('contract_note cn ');
        if($searchParams['contract_note_id']!='')
        {
            $cnoteids = explode(',',$searchParams['contract_note_id']);
            $this->db->where_in('cn.contract_note_id',$cnoteids);
        }        
        $res1 = $this->db->get();
        $res = $res1->num_rows();
        return $res;
    }

    /** new enhancement: Suresh 20th april 2017
    *** Delete Contract Notes
                                      END **/
    /** Phase2 update: Mahesh 04-092017
    *** Get Free supply itmes by quote
                                      START **/
    public function getQuoteFreeSupplyItems($quote_revision_ids=array())
    {
        if($quote_revision_ids)
        {
            $this->db->select('product_id,quantity,unit_price');
            $this->db->from('quote_opp_free_supply');
            $this->db->where_in('quote_revision_id',$quote_revision_ids);
            $this->db->where('status',1);
            $res = $this->db->get();
            if($res->num_rows()>0)
            {
                return $res->result_array();
            }
        }
    }

    public function getQuoteRevisionsInfo($quote_revision_ids=array())
    {
        if($quote_revision_ids)
        {
            $this->db->from('quote_revision');
            $this->db->where_in('quote_revision_id',$quote_revision_ids);
            $res = $this->db->get();
            if($res->num_rows()>0)
            {
                return $res->result_array();
            }
        }
    }

    // to manage buiness type fresh or repeat
    public function get_customer_previous_data($customer_id)
    {
        $this->db->select('cn.contract_note_id');
        $this->db->from('contract_note cn');
        $this->db->join('contract_note_quote_revision cnqr','cn.contract_note_id=cnqr.contract_note_id');
        $this->db->join('quote_revision qr','cnqr.quote_revision_id=qr.quote_revision_id');
        $this->db->join('quote_details qd','qr.quote_id=qd.quote_id');
        $this->db->join('opportunity o','qd.opportunity_id=o.opportunity_id');
        $this->db->join('lead l','o.lead_id=l.lead_id');
        $this->db->join('customer c','l.customer_id=c.customer_id');
        $this->db->where('c.customer_id',$customer_id);
        $res=$this->db->get();
        return $res->result_array();
    }
    public function get_lead_id($cnote_id)
    {
        $this->db->select('l.lead_id');
        $this->db->from('contract_note cn');
        $this->db->join('contract_note_quote_revision cnqr','cn.contract_note_id=cnqr.contract_note_id');
        $this->db->join('quote_revision qr','cnqr.quote_revision_id=qr.quote_revision_id');
        $this->db->join('quote_details qd','qr.quote_id=qd.quote_id');
        $this->db->join('opportunity o','qd.opportunity_id=o.opportunity_id');
        $this->db->join('lead l','o.lead_id=l.lead_id');
        $this->db->join('customer c','l.customer_id=c.customer_id');
        $this->db->where('cn.contract_note_id',$cnote_id);
        $res=$this->db->get();
        return $res->row_array();
    }
    public function get_first_cnote($customer_id)
    {
        $this->db->select('min(cn.contract_note_id) as first_cnote');
        $this->db->from('contract_note cn');
        $this->db->join('contract_note_quote_revision cnqr','cn.contract_note_id=cnqr.contract_note_id');
        $this->db->join('quote_revision qr','cnqr.quote_revision_id=qr.quote_revision_id');
        $this->db->join('quote_details qd','qr.quote_id=qd.quote_id');
        $this->db->join('opportunity o','qd.opportunity_id=o.opportunity_id');
        $this->db->join('lead l','o.lead_id=l.lead_id');
        $this->db->where('l.customer_id',$customer_id);
        $res=$this->db->get();
        return $res->row_array();

    }
    /** Phase2 update: Mahesh 04-092017
                                      END **/

    #Channel partner: 11-10-2018
    public function check_for_multiple_quotes($quotes)
    {
        $this->db->select('qr.quote_id');
        $this->db->from('quote_revision qr');
        $this->db->where('qr.quote_revision_id IN ('.$quotes.') ');
        $res=$this->db->get();
        $results = $res->result_array();
        $quote_id=array();
        foreach ($results as $row) {
           $quote_id[]=$row['quote_id'];
        }
        $this->db->select('q.channel_partner_id');
        $this->db->from('quote q');
        $this->db->where_in('quote_id',$quote_id);
        $this->db->where('q.company_id',$this->session->userdata('company'));
        $res=$this->db->get();
        $results1 = $res->result_array();
        $unique_com = array();
        foreach($results1 as $row)
        {
            $unique_com[] = $row['channel_partner_id'];
        }
        $unique_type = array_unique($unique_com);
        if(count($unique_type)==1)
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }
}