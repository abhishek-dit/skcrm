<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Report_model extends CI_Model {

	public function get_category_wise_product_list()
	{
		$this->db->select('sum(p.quantity) as stock,pc.category_id,pc.name,pc.description');
		$this->db->from('product p');
		$this->db->join('product_group pg','p.group_id=pg.group_id');
		$this->db->join('product_category pc','pg.category_id=pc.category_id');
		$this->db->where('p.availability',1);
        $this->db->where('p.product_type_id',1);
		$this->db->group_by('pc.category_id');
		$res=$this->db->get();
		return $res->result_array();
	} 

	public function get_group_wise_products_by_category()
	{
		$this->db->select('sum(p.quantity) as stock,pg.group_id,pg.name');
		$this->db->from('product p');
		$this->db->join('product_group pg','p.group_id=pg.group_id');
		$this->db->join('product_category pc','pg.category_id=pc.category_id');
		//$this->db->where('pc.name',$category);
		$this->db->where('p.availability',1);
        $this->db->where('p.product_type_id',1);
		$this->db->group_by('pg.group_id');
		$res=$this->db->get();
		return $res->result_array();
	}
	public function get_stock_in_hand_products_xl($searchParams)
    {
        $this->db->select('pg.name as group_name,pg.group_id,pc.category_id,pc.name as category_name,p.description,p.quantity,p.as_on_date,p.name as name');
        $this->db->from('product p');
        $this->db->join('product_group pg','p.group_id=pg.group_id');
		$this->db->join('product_category pc','pg.category_id=pc.category_id');
		$this->db->where('p.company_id',$this->session->userdata('company'));
        if($searchParams['category']!='')
        {
        	$this->db->where('pc.category_id',$searchParams['category']);
        }
        if($searchParams['segment']!='')
        {
        	$this->db->where('pg.group_id',$searchParams['segment']);
        }
        if($searchParams['product']!='')
        {
        	$this->db->where('p.product_id',$searchParams['product']);
        }
        $this->db->where('p.availability',1);
        $this->db->where('p.product_type_id',1);
        $this->db->where('p.company_id',$this->session->userdata('company'));
        $this->db->order_by('p.product_id,pg.group_id,pc.category_id');
        $res=$this->db->get();
        return $res->result_array();
    }
    public function get_stock_in_hand_products_table($searchParams)
    {
        $this->db->select('pg.name as group_name,pg.group_id,pc.category_id,pc.name as category_name,p.description,p.quantity,p.as_on_date,p.name as product_code');
        $this->db->from('product p');
        $this->db->join('product_group pg','p.group_id=pg.group_id');
        $this->db->join('product_category pc','pg.category_id=pc.category_id');
        if($searchParams['category']!='')
        {
        	$this->db->where('pc.category_id',$searchParams['category']);
        }
        if($searchParams['segment']!='')
        {
        	$this->db->where('pg.group_id',$searchParams['segment']);
        }
        if($searchParams['product']!='')
        {
        	$this->db->where('p.product_id',$searchParams['product']);
        }
        $this->db->where('p.availability',1);
        $this->db->where('p.product_type_id',1);
        $this->db->where('p.company_id',$this->session->userdata('company'));
        $res=$this->db->get();
        return $res->result_array();
    }
    /*public function getproductsforstock($segment)
    {
    	$this->db->select();
    	$this->db->from('product p');
    	$this->db->join('product_group pg','p.group_id=pg.group_id');
    	$this->db->where('p.group_id')
    }*/
	// updated: 7-12-2017 14:21
	public function get_product_wise_list_by_product($segment)
	{
		$this->db->select('sum(p.quantity) as stock,p.product_id,(case when p.name2 !="" then p.name2 else p.name end ) as name,p.product_type_id');
		$this->db->from('product p');
		$this->db->join('product_group pg','p.group_id=pg.group_id');
		$this->db->where('pg.name',$segment);
		$this->db->where('p.availability',1);
		$this->db->group_by('p.product_id');
		$res=$this->db->get();
		return $res->result_array();
	}

	public function get_region_wise_outstanding_amount()
	{
		$this->db->select('sum(cn.outstanding_amount) as outstanding_amount,l4.location_id as region_id,
			l4.location as region_name');
		$this->db->from('contract_note cn');
		$this->db->join('contract_note_quote_revision cnqr','cn.contract_note_id=cnqr.contract_note_id');
		$this->db->join('quote_revision qr','cnqr.quote_revision_id=qr.quote_revision_id');
		$this->db->join('quote_details qd','qr.quote_id=qd.quote_id');
		$this->db->join('opportunity o','qd.opportunity_id=o.opportunity_id');
		$this->db->join('lead l','o.lead_id=l.lead_id');
		$this->db->join('customer c','l.customer_id=c.customer_id');
		$this->db->join('customer_location cl','c.customer_id=cl.customer_id');
		$this->db->join('location l1','cl.location_id=l1.location_id');
		$this->db->join('location l2','l1.parent_id=l2.location_id');
		$this->db->join('location l3','l2.parent_id=l3.location_id');
		$this->db->join('location l4','l3.parent_id=l4.location_id');
		$this->db->group_by('l4.location_id');
		$res=$this->db->get();
		return $res->result_array();
	}

	public function get_customers_list_by_region_wise($region_name)
	{
		$this->db->select('sum(cn.outstanding_amount) as outstanding_amount,c.name');
		$this->db->from('contract_note cn');
		$this->db->join('contract_note_quote_revision cnqr','cn.contract_note_id=cnqr.contract_note_id');
		$this->db->join('quote_revision qr','cnqr.quote_revision_id=qr.quote_revision_id');
		$this->db->join('quote_details qd','qr.quote_id=qd.quote_id');
		$this->db->join('opportunity o','qd.opportunity_id=o.opportunity_id');
		$this->db->join('lead l','o.lead_id=l.lead_id');
		$this->db->join('customer c','l.customer_id=c.customer_id');
		$this->db->join('customer_location cl','c.customer_id=cl.customer_id');
		$this->db->join('location l1','cl.location_id=l1.location_id');
		$this->db->join('location l2','l1.parent_id=l2.location_id');
		$this->db->join('location l3','l2.parent_id=l3.location_id');
		$this->db->join('location l4','l3.parent_id=l4.location_id');
		$this->db->where('l4.location',$region_name);
		$this->db->group_by('l.customer_id');
		$res=$this->db->get();
		return $res->result_array();
	}

	public function get_outstanding_amount_by_customer($region_name,$customer_name)
	{

		$this->db->select('cn.outstanding_amount as outstanding_amount,cn.so_number');
		$this->db->from('contract_note cn');
		$this->db->join('contract_note_quote_revision cnqr','cn.contract_note_id=cnqr.contract_note_id');
		$this->db->join('quote_revision qr','cnqr.quote_revision_id=qr.quote_revision_id');
		$this->db->join('quote_details qd','qr.quote_id=qd.quote_id');
		$this->db->join('opportunity o','qd.opportunity_id=o.opportunity_id');
		$this->db->join('lead l','o.lead_id=l.lead_id');
		$this->db->join('customer c','l.customer_id=c.customer_id');
		$this->db->join('customer_location cl','c.customer_id=cl.customer_id');
		$this->db->join('location l1','cl.location_id=l1.location_id');
		$this->db->join('location l2','l1.parent_id=l2.location_id');
		$this->db->join('location l3','l2.parent_id=l3.location_id');
		$this->db->join('location l4','l3.parent_id=l4.location_id');
		$this->db->where('l4.location',$region_name);
		$this->db->where('c.name',$customer_name);
		$this->db->group_by('cn.contract_note_id');
		$res=$this->db->get();
		return $res->result_array();
	}

	public function get_opportunity_lost_by_reasons($searchfilters)
	{   
		$fy_year=get_current_fiancial_year();
		if($searchfilters['vtime']!='')
		{   
			$parameter="o.closed_time"; 
			$where1=getReportTimelineCheck($searchfilters,$parameter,$fy_year);
		}
		$this->db->select('sum(o.required_quantity*p.dp) as total_count,ols.reason_id,ols.name');
		$this->db->from('opportunity o');
		$this->db->join('opportunity_product op','o.opportunity_id=op.opportunity_id');
		$this->db->join('product p','op.product_id=p.product_id');
		$this->db->join('opportunity_lost_reasons ols','o.oppr_lost_id=ols.reason_id');
		$this->db->join('lead l','o.lead_id=l.lead_id');
		$this->db->join('location l1','l.location_id=l1.location_id');
		$this->db->join('location l2','l1.parent_id=l2.location_id');
		$this->db->join('location l3','l2.parent_id=l3.location_id');
		$this->db->join('location l4','l3.parent_id=l4.location_id');
		$this->db->where('o.status IN (7,8)');
		if($searchfilters['vtime']!='')
		{   
			if($where1!='')
				$this->db->where($where1); 
		}
		if($searchfilters['region_filter']!='')
		{
			$this->db->where('l4.location_id',$searchfilters['region_filter']);
		}
		if($searchfilters['segment']!='')
		{
			$this->db->where('p.group_id',$searchfilters['segment']);
		}
		$this->db->where('o.company_id',$this->session->userdata('company'));
		$this->db->where('l.user_id IN ('.$searchfilters['reportee_users'].')');
		$this->db->group_by('o.oppr_lost_id');
		$res=$this->db->get();
		//echo $this->db->last_query();exit;
		return $res->result_array();
	}

	public function get_opportunity_lost_by_competitors($searchfilters)
	{
		$fy_year=get_current_fiancial_year();
		if($searchfilters['vtime']!='')
		{
			$parameter="o.closed_time"; 
			$where1=getReportTimelineCheck($searchfilters,$parameter,$fy_year);
		}
		$this->db->select('sum(o.required_quantity*p.dp) as total_count,c.competitor_id,c.name');
		$this->db->from('opportunity o');
		$this->db->join('opportunity_product op','o.opportunity_id=op.opportunity_id');
		$this->db->join('product p','op.product_id=p.product_id');
		$this->db->join('competitor c','o.lost_competitor_id=c.competitor_id');
		$this->db->join('lead l','o.lead_id=l.lead_id');
		$this->db->join('location l1','l.location_id=l1.location_id');
		$this->db->join('location l2','l1.parent_id=l2.location_id');
		$this->db->join('location l3','l2.parent_id=l3.location_id');
		$this->db->join('location l4','l3.parent_id=l4.location_id');
		$this->db->where('o.status IN (7,8)');
		if($searchfilters['vtime']!='')
		{
			if($where1!='')
				$this->db->where($where1);
		}
		if($searchfilters['region_filter']!='')
		{
			$this->db->where('l4.location_id',$searchfilters['region_filter']);
		}
		if($searchfilters['segment']!='')
		{
			$this->db->where('p.group_id',$searchfilters['segment']);
		}
		$this->db->where('o.company_id',$this->session->userdata('company'));
		$this->db->where('l.user_id IN ('.$searchfilters['reportee_users'].')');
		$this->db->group_by('c.competitor_id');
		$res=$this->db->get();
		//echo $this->db->last_query();exit;
		return $res->result_array();
	}

	public function get_lost_opp_details_by_competitor($lost_for,$location,$searchfilters)
	{	
		$fy_year=get_current_fiancial_year();
		if($searchfilters['vtime']!='')
		{
			$parameter="o.closed_time"; 
			$where1=getReportTimelineCheck($searchfilters,$parameter,$fy_year);
		}
		$this->db->select('sum(o.required_quantity*p.dp) as total_count,l4.location_id as region_id,
			l4.location as region_name,pg.group_id,pg.name as group_name');
		$this->db->from('opportunity o');
		$this->db->join('competitor c','o.lost_competitor_id=c.competitor_id');
		$this->db->join('opportunity_product op','o.opportunity_id=op.opportunity_id');
		$this->db->join('product p','op.product_id=p.product_id');
		$this->db->join('product_group pg','p.group_id=pg.group_id');
		$this->db->join('lead l','o.lead_id=l.lead_id');
		$this->db->join('location l1','l.location_id=l1.location_id');
		$this->db->join('location l2','l1.parent_id=l2.location_id');
		$this->db->join('location l3','l2.parent_id=l3.location_id');
		$this->db->join('location l4','l3.parent_id=l4.location_id');
		$this->db->where('o.status IN (7,8)');
		$this->db->where('o.company_id',$this->session->userdata('company'));
		$this->db->where('l4.location',$location);
		//$this->db->where('p.group_id',$group_id);
		$this->db->where('c.name',$lost_for);
		if($searchfilters['vtime']!='')
		{
			if($where1!='')
				$this->db->where($where1);
		}
		if($searchfilters['region_filter']!='')
		{
			$this->db->where('l4.location_id',$searchfilters['region_filter']);
		}
		if($searchfilters['segment']!='')
		{
			$this->db->where('p.group_id',$searchfilters['segment']);
		}
		$this->db->where('l.user_id IN ('.$searchfilters['reportee_users'].')');
		$this->db->group_by('p.group_id');
		$res=$this->db->get();
		return $res->result_array();
	}

	public function get_lost_opp_details($lost_for,$location,$searchfilters)
	{
		$fy_year=get_current_fiancial_year();
		if($searchfilters['vtime']!='')
		{
			$parameter="o.closed_time"; 
			$where1=getReportTimelineCheck($searchfilters,$parameter,$fy_year);
		}
		$this->db->select('sum(o.required_quantity*p.dp) as total_count,l4.location_id as region_id,
			l4.location as region_name,p.group_id,pg.name as group_name');
		$this->db->from('opportunity o');
		$this->db->join('opportunity_lost_reasons ols','o.oppr_lost_id=ols.reason_id');
		$this->db->join('opportunity_product op','o.opportunity_id=op.opportunity_id');
		$this->db->join('product p','op.product_id=p.product_id');
		$this->db->join('product_group pg','p.group_id=pg.group_id');
		$this->db->join('lead l','o.lead_id=l.lead_id');
		$this->db->join('location l1','l.location_id=l1.location_id');
		$this->db->join('location l2','l1.parent_id=l2.location_id');
		$this->db->join('location l3','l2.parent_id=l3.location_id');
		$this->db->join('location l4','l3.parent_id=l4.location_id');
		$this->db->where('o.status IN (7,8)');
		$this->db->where('l4.location',$location);
		//$this->db->where('p.group_id',$group_id);
		$this->db->where('ols.name',$lost_for);
		if($searchfilters['vtime']!='')
		{
			if($where1!='')
				$this->db->where($where1);
		}
		$this->db->where('o.company_id',$this->session->userdata('company'));
		if($searchfilters['region_filter']!='')
		{
			$this->db->where('l4.location_id',$searchfilters['region_filter']);
		}
		if($searchfilters['segment']!='')
		{
			$this->db->where('p.group_id',$searchfilters['segment']);
		}
		$this->db->where('l.user_id IN ('.$searchfilters['reportee_users'].')');
		$this->db->group_by('p.group_id');
		$res=$this->db->get();
		return $res->result_array();
	}

	public function get_opportunity_products_lost_by_region($lost_for,$region,$segment,$searchfilters)
	{
		$fy_year=get_current_fiancial_year();
		if($searchfilters['vtime']!='')
		{
			$parameter="o.closed_time"; 
			$where1=getReportTimelineCheck($searchfilters,$parameter,$fy_year);
		}
		$role_id=$this->session->userdata('role_id');
		$this->db->select('cs.customer_id,cs.name as customer_name');
		$this->db->from('opportunity o');
		$this->db->join('opportunity_lost_reasons ols','o.oppr_lost_id=ols.reason_id');
		$this->db->join('opportunity_product op','o.opportunity_id=op.opportunity_id');
		$this->db->join('product p','op.product_id=p.product_id');
		$this->db->join('product_group pg','p.group_id=pg.group_id');
		$this->db->join('lead l','o.lead_id=l.lead_id');
		$this->db->join('customer cs','l.customer_id=cs.customer_id');
		$this->db->join('location l1','l.location_id=l1.location_id');
		$this->db->join('location l2','l1.parent_id=l2.location_id');
		$this->db->join('location l3','l2.parent_id=l3.location_id');
		$this->db->join('location l4','l3.parent_id=l4.location_id');
		$this->db->where('o.status IN (7,8)');
		$this->db->where('o.company_id',$this->session->userdata('company'));
		$this->db->where('l4.location',$region);
		$this->db->where('pg.name',$segment);
		$this->db->where('ols.name',$lost_for);
		if($searchfilters['vtime']!='')
		{
			if($where1!='')
				$this->db->where($where1);
		}
		if($searchfilters['region_filter']!='')
		{
			$this->db->where('l4.location_id',$searchfilters['region_filter']);
		}
		if($searchfilters['seg']!='')
		{
			$this->db->where('p.group_id',$searchfilters['seg']);
		}
		$this->db->where('l.user_id IN ('.$searchfilters['reportee_users'].')');
		$this->db->group_by('l.customer_id');
		$res=$this->db->get();
		return $res->result_array();
	}
	public function get_opportunity_products_lost_by_region_product($lost_for,$region,$segment,$searchfilters,$customer_id)
	{
		$fy_year=get_current_fiancial_year();
		if($searchfilters['vtime']!='')
		{
			$parameter="o.closed_time"; 
			$where1=getReportTimelineCheck($searchfilters,$parameter,$fy_year);
		}
		$role_id=$this->session->userdata('role_id');
		$this->db->select('p.product_id,sum(p.dp*o.required_quantity) as total_count,p.description as product_name');
		$this->db->from('opportunity o');
		$this->db->join('opportunity_lost_reasons ols','o.oppr_lost_id=ols.reason_id');
		$this->db->join('opportunity_product op','o.opportunity_id=op.opportunity_id');
		$this->db->join('product p','op.product_id=p.product_id');
		$this->db->join('product_group pg','p.group_id=pg.group_id');
		$this->db->join('lead l','o.lead_id=l.lead_id');
		$this->db->join('customer cs','l.customer_id=cs.customer_id');
		$this->db->join('location l1','l.location_id=l1.location_id');
		$this->db->join('location l2','l1.parent_id=l2.location_id');
		$this->db->join('location l3','l2.parent_id=l3.location_id');
		$this->db->join('location l4','l3.parent_id=l4.location_id');
		$this->db->where('o.status IN (7,8)');
		$this->db->where('o.company_id',$this->session->userdata('company'));
		$this->db->where('cs.customer_id',$customer_id);
		$this->db->where('l4.location',$region);
		$this->db->where('pg.name',$segment);
		$this->db->where('ols.name',$lost_for);
		if($searchfilters['vtime']!='')
		{
			if($where1!='')
				$this->db->where($where1);
		}
		if($searchfilters['region_filter']!='')
		{
			$this->db->where('l4.location_id',$searchfilters['region_filter']);
		}
		if($searchfilters['seg']!='')
		{
			$this->db->where('p.group_id',$searchfilters['seg']);
		}
		$this->db->where('l.user_id IN ('.$searchfilters['reportee_users'].')');
		$this->db->group_by('p.product_id');
		$res=$this->db->get();
		return $res->result_array();
	}
	public function get_opportunity_products_lost_by_competitor($lost_for,$region,$segment,$searchfilters)
	{
		$fy_year=get_current_fiancial_year();
		if($searchfilters['vtime']!='')
		{
			$parameter="o.closed_time"; 
			$where1=getReportTimelineCheck($searchfilters,$parameter,$fy_year);
		}
		$role_id=$this->session->userdata('role_id');
		$this->db->select('cs.customer_id,cs.name as customer_name');
		$this->db->from('opportunity o');
		$this->db->join('competitor c','o.lost_competitor_id=c.competitor_id');
		$this->db->join('opportunity_product op','o.opportunity_id=op.opportunity_id');
		$this->db->join('product p','op.product_id=p.product_id');
		$this->db->join('product_group pg','p.group_id=pg.group_id');
		$this->db->join('lead l','o.lead_id=l.lead_id');
		$this->db->join('customer cs','l.customer_id=cs.customer_id');
		$this->db->join('location l1','l.location_id=l1.location_id');
		$this->db->join('location l2','l1.parent_id=l2.location_id');
		$this->db->join('location l3','l2.parent_id=l3.location_id');
		$this->db->join('location l4','l3.parent_id=l4.location_id');
		$this->db->where('o.status IN (7,8)');
		$this->db->where('o.company_id',$this->session->userdata('company'));
		$this->db->where('l4.location',$region);
		$this->db->where('pg.name',$segment);
		$this->db->where('c.name',$lost_for);
		if($searchfilters['vtime']!='')
		{
			if($where1!='')
				$this->db->where($where1);
		}
		if($searchfilters['region_filter']!='')
		{
			$this->db->where('l4.location_id',$searchfilters['region_filter']);
		}
		if($searchfilters['seg']!='')
		{
			$this->db->where('p.group_id',$searchfilters['seg']);
		}
		$this->db->where('l.user_id IN ('.$searchfilters['reportee_users'].')');
		$this->db->group_by('l.customer_id');
		$res=$this->db->get();
		return $res->result_array();
	}
	public function get_opportunity_products_lost_by_competitor_product($lost_for,$region,$segment,$searchfilters,$customer_id)
	{
		$fy_year=get_current_fiancial_year();
		if($searchfilters['vtime']!='')
		{
			$parameter="o.closed_time"; 
			$where1=getReportTimelineCheck($searchfilters,$parameter,$fy_year);
		}
		$role_id=$this->session->userdata('role_id');
		$this->db->select('p.description as product_name,sum(p.dp*o.required_quantity) as total_count,p.product_id');
		$this->db->from('opportunity o');
		$this->db->join('competitor c','o.lost_competitor_id=c.competitor_id');
		$this->db->join('opportunity_product op','o.opportunity_id=op.opportunity_id');
		$this->db->join('product p','op.product_id=p.product_id');
		$this->db->join('product_group pg','p.group_id=pg.group_id');
		$this->db->join('lead l','o.lead_id=l.lead_id');
		$this->db->join('customer cs','l.customer_id=cs.customer_id');
		$this->db->join('location l1','l.location_id=l1.location_id');
		$this->db->join('location l2','l1.parent_id=l2.location_id');
		$this->db->join('location l3','l2.parent_id=l3.location_id');
		$this->db->join('location l4','l3.parent_id=l4.location_id');
		$this->db->where('o.status IN (7,8)');
		$this->db->where('o.company_id',$this->session->userdata('company'));
		$this->db->where('cs.customer_id',$customer_id);
		$this->db->where('l4.location',$region);
		$this->db->where('pg.name',$segment);
		$this->db->where('c.name',$lost_for);
		if($searchfilters['vtime']!='')
		{
			if($where1!='')
				$this->db->where($where1);
		}
		if($searchfilters['region_filter']!='')
		{
			$this->db->where('l4.location_id',$searchfilters['region_filter']);
		}
		if($searchfilters['seg']!='')
		{
			$this->db->where('p.group_id',$searchfilters['seg']);
		}
		$this->db->where('l.user_id IN ('.$searchfilters['reportee_users'].')');
		$this->db->group_by('p.product_id');
		$res=$this->db->get();
		return $res->result_array();
	}
	public function get_repeat_business_cnotes_by_region($searchfilters)
	{   
		$fy_year=get_current_fiancial_year();
		if($searchfilters['vtime']!='')
		{
			$parameter="cn.created_time"; 
			$where1=getReportTimelineCheck($searchfilters,$parameter,$fy_year);
		}
		$this->db->select('ROUND(SUM( CASE WHEN ma.margin_approval_id IS NULL THEN (o.required_quantity*qd.total_value)*(1-qr.discount/100) ELSE ( CASE WHEN ma.discount_type = 1 THEN (o.required_quantity*qd.total_value)*(1-ma.discount/100) ELSE (o.required_quantity*qd.total_value)- (ma.discount*qd.currency_factor) END ) END )) AS total_orders,l4.location,l4.location_id,cn.contract_note_id');
		$this->db->from('contract_note cn');
		$this->db->join('contract_note_quote_revision cnqr','cn.contract_note_id=cnqr.contract_note_id');
		$this->db->join('quote_revision qr','cnqr.quote_revision_id=qr.quote_revision_id');
		$this->db->join('quote_details qd','qr.quote_id=qd.quote_id');
		$this->db->join('opportunity o','qd.opportunity_id=o.opportunity_id');
		$this->db->join('opportunity_product op','o.opportunity_id=op.opportunity_id');
		$this->db->join('lead l','o.lead_id=l.lead_id');
		$this->db->join('customer c','l.customer_id=c.customer_id');
		$this->db->join('product p','op.product_id=p.product_id');
		$this->db->join('product_group pg','p.group_id=pg.group_id');
		$this->db->join('customer_location cl','c.customer_id=cl.customer_id','inner join');
		$this->db->join('location l1','cl.location_id=l1.location_id','inner join');
		$this->db->join('location l2','l1.parent_id=l2.location_id','inner join');
		$this->db->join('location l3','l2.parent_id=l3.location_id','inner join');
		$this->db->join('location l4','l3.parent_id=l4.location_id','inner join');
		$this->db->join('quote_op_margin_approval ma','ma.quote_revision_id = qr.quote_revision_id AND o.opportunity_id = ma.opportunity_id','LEFT');
/*		$where=' cn.contract_note_id != (select min(cn1.contract_note_id) from contract_note cn1  inner join contract_note_quote_revision cnqr1 on cn1.contract_note_id=cnqr1.contract_note_id inner join quote_revision qr1 on cnqr1.quote_revision_id=qr1.quote_revision_id inner join quote_details qd1 on qr1.quote_id=qd1.quote_id inner join opportunity o1 on qd1.opportunity_id=o1.opportunity_id inner join lead l1 on o1.lead_id=l1.lead_id where c.customer_id=l1.customer_id)';*/
		$this->db->where('p.product_type_id',1);
		$this->db->where('cn.company_id',$this->session->userdata('company'));
		$this->db->where('cn.business_type',2);
		if($searchfilters['vtime']!='')
		{	
			if($where1!='')
				$this->db->where($where1);
		}
		$this->db->where('op.product_id IN ('.$searchfilters['userProducts'].')');
		$this->db->where('l.location_id IN ('.$searchfilters['userLocations'].') ');
		$this->db->where('l.user_id IN ('.$searchfilters['reportee_users'].')');
		if($searchfilters['region']!='')
		{
	    	$this->db->where('l4.location_id',$searchfilters['region']);
	    }
		$this->db->group_by('l4.location_id');
		$res=$this->db->get();
		return $res->result_array();
	}
	public function get_fresh_business_cnotes_by_product($searchfilters)
	{   
		$fy_year=get_current_fiancial_year();
		if($searchfilters['vtime']!='')
		{
			$parameter="cn.created_time"; 
			$where1=getReportTimelineCheck($searchfilters,$parameter,$fy_year);
		}
		$this->db->select('ROUND(SUM( CASE WHEN ma.margin_approval_id IS NULL THEN (o.required_quantity*qd.total_value)*(1-qr.discount/100) ELSE ( CASE WHEN ma.discount_type = 1 THEN (o.required_quantity*qd.total_value)*(1-ma.discount/100) ELSE (o.required_quantity*qd.total_value)- (ma.discount*qd.currency_factor) END ) END )) AS total_orders,pg.name,pg.group_id,cn.contract_note_id');
		$this->db->from('contract_note cn');
		$this->db->join('contract_note_quote_revision cnqr','cn.contract_note_id=cnqr.contract_note_id');
		$this->db->join('quote_revision qr','cnqr.quote_revision_id=qr.quote_revision_id');
		$this->db->join('quote_details qd','qr.quote_id=qd.quote_id');
		$this->db->join('opportunity o','qd.opportunity_id=o.opportunity_id');
		$this->db->join('opportunity_product op','o.opportunity_id=op.opportunity_id');
		$this->db->join('lead l','o.lead_id=l.lead_id');
		$this->db->join('customer c','l.customer_id=c.customer_id');
		$this->db->join('product p','op.product_id=p.product_id');
		$this->db->join('product_group pg','p.group_id=pg.group_id');
		$this->db->join('customer_location cl','c.customer_id=cl.customer_id','inner join');
		$this->db->join('location l1','cl.location_id=l1.location_id','inner join');
		$this->db->join('location l2','l1.parent_id=l2.location_id','inner join');
		$this->db->join('location l3','l2.parent_id=l3.location_id','inner join');
		$this->db->join('location l4','l3.parent_id=l4.location_id','inner join');
		$this->db->join('quote_op_margin_approval ma','ma.quote_revision_id = qr.quote_revision_id AND o.opportunity_id = ma.opportunity_id','LEFT');
		/*$where=' cn.contract_note_id = (select min(cn1.contract_note_id) from contract_note cn1  inner join contract_note_quote_revision cnqr1 on cn1.contract_note_id=cnqr1.contract_note_id inner join quote_revision qr1 on cnqr1.quote_revision_id=qr1.quote_revision_id inner join quote_details qd1 on qr1.quote_id=qd1.quote_id inner join opportunity o1 on qd1.opportunity_id=o1.opportunity_id inner join lead l1 on o1.lead_id=l1.lead_id where c.customer_id=l1.customer_id)';*/
		$this->db->where('p.product_type_id',1);
		$this->db->where('cn.company_id',$this->session->userdata('company'));
		$this->db->where('cn.business_type',1);
		if($searchfilters['vtime']!='')
		{	
			if($where1!='')
				$this->db->where($where1);
		}
		$this->db->where('op.product_id IN ('.$searchfilters['userProducts'].')');
		$this->db->where('l.location_id IN ('.$searchfilters['userLocations'].') ');
		$this->db->where('l.user_id IN ('.$searchfilters['reportee_users'].')');
		if($searchfilters['region']!='' && $searchfilters['region']!='undefined' )
		{
	    	$this->db->where('l4.location_id',$searchfilters['region']);
	    }
		$this->db->group_by('pg.group_id');
		$res=$this->db->get();
		//echo $this->db->last_query(); exit;
		return $res->result_array();
	}
	public function get_repeat_business_cnotes_by_product($searchfilters)
	{   
		$fy_year=get_current_fiancial_year();
		if($searchfilters['vtime']!='')
		{
			$parameter="cn.created_time"; 
			$where1=getReportTimelineCheck($searchfilters,$parameter,$fy_year);
		}
		$this->db->select('ROUND(SUM( CASE WHEN ma.margin_approval_id IS NULL THEN (o.required_quantity*qd.total_value)*(1-qr.discount/100) ELSE ( CASE WHEN ma.discount_type = 1 THEN (o.required_quantity*qd.total_value)*(1-ma.discount/100) ELSE (o.required_quantity*qd.total_value)- (ma.discount*qd.currency_factor) END ) END )) AS total_orders,pg.name,pg.group_id,cn.contract_note_id');
		$this->db->from('contract_note cn');
		$this->db->join('contract_note_quote_revision cnqr','cn.contract_note_id=cnqr.contract_note_id');
		$this->db->join('quote_revision qr','cnqr.quote_revision_id=qr.quote_revision_id');
		$this->db->join('quote_details qd','qr.quote_id=qd.quote_id');
		$this->db->join('opportunity o','qd.opportunity_id=o.opportunity_id');
		$this->db->join('opportunity_product op','o.opportunity_id=op.opportunity_id');
		$this->db->join('lead l','o.lead_id=l.lead_id');
		$this->db->join('customer c','l.customer_id=c.customer_id');
		$this->db->join('product p','op.product_id=p.product_id');
		$this->db->join('product_group pg','p.group_id=pg.group_id');
		$this->db->join('customer_location cl','c.customer_id=cl.customer_id','inner join');
		$this->db->join('location l1','cl.location_id=l1.location_id','inner join');
		$this->db->join('location l2','l1.parent_id=l2.location_id','inner join');
		$this->db->join('location l3','l2.parent_id=l3.location_id','inner join');
		$this->db->join('location l4','l3.parent_id=l4.location_id','inner join');
		$this->db->join('quote_op_margin_approval ma','ma.quote_revision_id = qr.quote_revision_id AND o.opportunity_id = ma.opportunity_id','LEFT');
		/*$where=' cn.contract_note_id != (select min(cn1.contract_note_id) from contract_note cn1  inner join contract_note_quote_revision cnqr1 on cn1.contract_note_id=cnqr1.contract_note_id inner join quote_revision qr1 on cnqr1.quote_revision_id=qr1.quote_revision_id inner join quote_details qd1 on qr1.quote_id=qd1.quote_id inner join opportunity o1 on qd1.opportunity_id=o1.opportunity_id inner join lead l1 on o1.lead_id=l1.lead_id where c.customer_id=l1.customer_id)';*/
		$this->db->where('p.product_type_id',1);
		$this->db->where('cn.company_id',$this->session->userdata('company'));
		$this->db->where('cn.business_type',2);
		if($searchfilters['vtime']!='')
		{	
			if($where1!='')
				$this->db->where($where1);
		}
		$this->db->where('op.product_id IN ('.$searchfilters['userProducts'].')');
		$this->db->where('l.location_id IN ('.$searchfilters['userLocations'].') ');
		$this->db->where('l.user_id IN ('.$searchfilters['reportee_users'].')');
		if($searchfilters['region']!='')
		{
	    	$this->db->where('l4.location_id',$searchfilters['region']);
	    }
		$this->db->group_by('pg.group_id');
		$res=$this->db->get();
		return $res->result_array();
	}
	public function get_fresh_business_cnotes_by_product_customer($searchfilters,$category,$val)
	{   
		$fy_year=get_current_fiancial_year();
		if($searchfilters['vtime']!='')
		{
			$parameter="cn.created_time"; 
			$where1=getReportTimelineCheck($searchfilters,$parameter,$fy_year);
		}
		$this->db->select('ROUND(SUM( CASE WHEN ma.margin_approval_id IS NULL THEN (o.required_quantity*qd.total_value)*(1-qr.discount/100) ELSE ( CASE WHEN ma.discount_type = 1 THEN (o.required_quantity*qd.total_value)*(1-ma.discount/100) ELSE (o.required_quantity*qd.total_value)- (ma.discount*qd.currency_factor) END ) END )) AS total_orders,p.product_id,p.description as name,cn.contract_note_id');
		$this->db->from('contract_note cn');
		$this->db->join('contract_note_quote_revision cnqr','cn.contract_note_id=cnqr.contract_note_id');
		$this->db->join('quote_revision qr','cnqr.quote_revision_id=qr.quote_revision_id');
		$this->db->join('quote_details qd','qr.quote_id=qd.quote_id');
		$this->db->join('opportunity o','qd.opportunity_id=o.opportunity_id');
		$this->db->join('opportunity_product op','o.opportunity_id=op.opportunity_id');
		$this->db->join('lead l','o.lead_id=l.lead_id');
		$this->db->join('customer c','l.customer_id=c.customer_id');
		$this->db->join('product p','op.product_id=p.product_id');
		$this->db->join('product_group pg','p.group_id=pg.group_id');
		$this->db->join('customer_location cl','c.customer_id=cl.customer_id','inner join');
		$this->db->join('location l1','cl.location_id=l1.location_id','inner join');
		$this->db->join('location l2','l1.parent_id=l2.location_id','inner join');
		$this->db->join('location l3','l2.parent_id=l3.location_id','inner join');
		$this->db->join('location l4','l3.parent_id=l4.location_id','inner join');
		$this->db->join('quote_op_margin_approval ma','ma.quote_revision_id = qr.quote_revision_id AND o.opportunity_id = ma.opportunity_id','LEFT');
		$this->db->where('p.product_type_id',1);
		if($val==1){
		/*$where=' cn.contract_note_id = (select min(cn1.contract_note_id) from contract_note cn1  inner join contract_note_quote_revision cnqr1 on cn1.contract_note_id=cnqr1.contract_note_id inner join quote_revision qr1 on cnqr1.quote_revision_id=qr1.quote_revision_id inner join quote_details qd1 on qr1.quote_id=qd1.quote_id inner join opportunity o1 on qd1.opportunity_id=o1.opportunity_id inner join lead l1 on o1.lead_id=l1.lead_id where c.customer_id=l1.customer_id)';
	    }*/
	    	$this->db->where('cn.business_type',1);
	    }
	    else
	    {
	    	/*$where=' cn.contract_note_id != (select min(cn1.contract_note_id) from contract_note cn1  inner join contract_note_quote_revision cnqr1 on cn1.contract_note_id=cnqr1.contract_note_id inner join quote_revision qr1 on cnqr1.quote_revision_id=qr1.quote_revision_id inner join quote_details qd1 on qr1.quote_id=qd1.quote_id inner join opportunity o1 on qd1.opportunity_id=o1.opportunity_id inner join lead l1 on o1.lead_id=l1.lead_id where c.customer_id=l1.customer_id)';*/
	    	$this->db->where('cn.business_type',2);
	    }
		
		if($searchfilters['vtime']!='')
		{	
			if($where1!='')
				$this->db->where($where1);
		}
		$this->db->where('cn.company_id',$this->session->userdata('company'));
		$this->db->where('op.product_id IN ('.$searchfilters['userProducts'].')');
		$this->db->where('l.location_id IN ('.$searchfilters['userLocations'].') ');
		$this->db->where('l.user_id IN ('.$searchfilters['reportee_users'].')');
		if($searchfilters['region']!='')
		{
	    	$this->db->where('l4.location_id',$searchfilters['region']);
	    }
	    $this->db->where('pg.name',$category);
		$this->db->group_by('p.product_id');
		$res=$this->db->get();
		return $res->result_array();
	}
	public function get_customer_first_cnotes($searchfilters)
	{   
		$fy_year=get_current_fiancial_year();
		/*if($searchfilters['vtime']!='')
		{
			$parameter="cn.created_time"; 
			$where1=getReportTimelineCheck($searchfilters,$parameter,$fy_year);
		}*/
		$this->db->select('c.customer_id,cn.contract_note_id');
		$this->db->from('contract_note cn');
		$this->db->join('contract_note_quote_revision cnqr','cn.contract_note_id=cnqr.contract_note_id');
		$this->db->join('quote_revision qr','cnqr.quote_revision_id=qr.quote_revision_id');
		$this->db->join('quote_details qd','qr.quote_id=qd.quote_id');
		$this->db->join('opportunity o','qd.opportunity_id=o.opportunity_id');
		$this->db->join('opportunity_product op','o.opportunity_id=op.opportunity_id');
		$this->db->join('lead l','o.lead_id=l.lead_id');
		$this->db->join('customer c','l.customer_id=c.customer_id');
		$this->db->join('product p','op.product_id=p.product_id');
		$this->db->join('product_group pg','p.group_id=pg.group_id');
		$this->db->join('customer_location cl','c.customer_id=cl.customer_id','inner join');
		$this->db->join('location l1','cl.location_id=l1.location_id','inner join');
		$this->db->join('location l2','l1.parent_id=l2.location_id','inner join');
		$this->db->join('location l3','l2.parent_id=l3.location_id','inner join');
		$this->db->join('location l4','l3.parent_id=l4.location_id','inner join');
		$this->db->join('quote_op_margin_approval ma','ma.quote_revision_id = qr.quote_revision_id AND o.opportunity_id = ma.opportunity_id','LEFT');
		$this->db->where('p.product_type_id',1);
		/*$where=' cn.contract_note_id = (select min(cn1.contract_note_id) from contract_note cn1  inner join contract_note_quote_revision cnqr1 on cn1.contract_note_id=cnqr1.contract_note_id inner join quote_revision qr1 on cnqr1.quote_revision_id=qr1.quote_revision_id inner join quote_details qd1 on qr1.quote_id=qd1.quote_id inner join opportunity o1 on qd1.opportunity_id=o1.opportunity_id inner join lead l1 on o1.lead_id=l1.lead_id where c.customer_id=l1.customer_id)';*/
	   
		$this->db->where('cn.business_type',1);
		$this->db->where('cn.company_id',$this->session->userdata('company'));
		/*if($searchfilters['vtime']!='')
		{	
			if($where1!='')
				$this->db->where($where1);
		}*/
		$this->db->where('op.product_id IN ('.$searchfilters['userProducts'].')');
		$this->db->where('l.location_id IN ('.$searchfilters['userLocations'].') ');
		$this->db->where('l.user_id IN ('.$searchfilters['reportee_users'].')');
		if($searchfilters['region']!='')
		{
	    	$this->db->where('l4.location_id',$searchfilters['region']);
	    }
	    $this->db->group_by('c.customer_id');
		$res=$this->db->get();
		return $res->result_array();
	}
	public function get_fresh_business_cnotes_by_region($searchfilters)
	{   
		$fy_year=get_current_fiancial_year();
		if($searchfilters['vtime']!='')
		{
			$parameter="cn.created_time"; 
			$where1=getReportTimelineCheck($searchfilters,$parameter,$fy_year);
		}
		$this->db->select('ROUND(SUM( CASE WHEN ma.margin_approval_id IS NULL THEN (o.required_quantity*qd.total_value)*(1-qr.discount/100) ELSE ( CASE WHEN ma.discount_type = 1 THEN (o.required_quantity*qd.total_value)*(1-ma.discount/100) ELSE (o.required_quantity*qd.total_value)- (ma.discount*qd.currency_factor) END ) END )) AS total_orders,l4.location,l4.location_id,cn.contract_note_id');
		$this->db->from('contract_note cn');
		$this->db->join('contract_note_quote_revision cnqr','cn.contract_note_id=cnqr.contract_note_id');
		$this->db->join('quote_revision qr','cnqr.quote_revision_id=qr.quote_revision_id');
		$this->db->join('quote_details qd','qr.quote_id=qd.quote_id');
		$this->db->join('opportunity o','qd.opportunity_id=o.opportunity_id');
		$this->db->join('opportunity_product op','o.opportunity_id=op.opportunity_id');
		$this->db->join('lead l','o.lead_id=l.lead_id');
		$this->db->join('customer c','l.customer_id=c.customer_id');
		$this->db->join('product p','op.product_id=p.product_id');
		$this->db->join('product_group pg','p.group_id=pg.group_id');
		$this->db->join('customer_location cl','c.customer_id=cl.customer_id','inner join');
		$this->db->join('location l1','cl.location_id=l1.location_id','inner join');
		$this->db->join('location l2','l1.parent_id=l2.location_id','inner join');
		$this->db->join('location l3','l2.parent_id=l3.location_id','inner join');
		$this->db->join('location l4','l3.parent_id=l4.location_id','inner join');
		$this->db->join('quote_op_margin_approval ma','ma.quote_revision_id = qr.quote_revision_id AND o.opportunity_id = ma.opportunity_id','LEFT');
		/*$where=' cn.contract_note_id = (select min(cn1.contract_note_id) from contract_note cn1  inner join contract_note_quote_revision cnqr1 on cn1.contract_note_id=cnqr1.contract_note_id inner join quote_revision qr1 on cnqr1.quote_revision_id=qr1.quote_revision_id inner join quote_details qd1 on qr1.quote_id=qd1.quote_id inner join opportunity o1 on qd1.opportunity_id=o1.opportunity_id inner join lead l1 on o1.lead_id=l1.lead_id where c.customer_id=l1.customer_id)';*/
		$this->db->where('p.product_type_id',1);
		$this->db->where('cn.company_id',$this->session->userdata('company'));
		$this->db->where('cn.business_type',1);
		if($searchfilters['vtime']!='')
		{	
			if($where1!='')
				$this->db->where($where1);
		}
		$this->db->where('op.product_id IN ('.$searchfilters['userProducts'].')');
		$this->db->where('l.location_id IN ('.$searchfilters['userLocations'].') ');
		$this->db->where('l.user_id IN ('.$searchfilters['reportee_users'].')');
		if($searchfilters['region']!='')
		{
	    	$this->db->where('l4.location_id',$searchfilters['region']);
	    }
		$this->db->group_by('l4.location_id');
		$res=$this->db->get();
		return $res->result_array();
	}
	public function get_fresh_business_cnotes_by_product_customer_results($searchfilters,$category)
	{   
		$fy_year=get_current_fiancial_year();
		if($searchfilters['vtime']!='')
		{
			$parameter="cn.created_time"; 
			$where1=getReportTimelineCheck($searchfilters,$parameter,$fy_year);
		}
		$this->db->select('ROUND(SUM( CASE WHEN ma.margin_approval_id IS NULL THEN (o.required_quantity*qd.total_value)*(1-qr.discount/100) ELSE ( CASE WHEN ma.discount_type = 1 THEN (o.required_quantity*qd.total_value)*(1-ma.discount/100) ELSE (o.required_quantity*qd.total_value)-(ma.discount*qd.currency_factor) END ) END )) AS total_orders,c.customer_id,c.name as c_name,cn.contract_note_id,GROUP_CONCAT(DISTINCT(cn.contract_note_id) SEPARATOR ",") as c_noteid,c.customer_id');
		$this->db->from('contract_note cn');
		$this->db->join('contract_note_quote_revision cnqr','cn.contract_note_id=cnqr.contract_note_id');
		$this->db->join('quote_revision qr','cnqr.quote_revision_id=qr.quote_revision_id');
		$this->db->join('quote_details qd','qr.quote_id=qd.quote_id');
		$this->db->join('opportunity o','qd.opportunity_id=o.opportunity_id');
		$this->db->join('opportunity_product op','o.opportunity_id=op.opportunity_id');
		$this->db->join('lead l','o.lead_id=l.lead_id');
		$this->db->join('customer c','l.customer_id=c.customer_id');
		$this->db->join('product p','op.product_id=p.product_id');
		$this->db->join('product_group pg','p.group_id=pg.group_id');
		$this->db->join('customer_location cl','c.customer_id=cl.customer_id','inner join');
		$this->db->join('location l1','cl.location_id=l1.location_id','inner join');
		$this->db->join('location l2','l1.parent_id=l2.location_id','inner join');
		$this->db->join('location l3','l2.parent_id=l3.location_id','inner join');
		$this->db->join('location l4','l3.parent_id=l4.location_id','inner join');
		$this->db->join('quote_op_margin_approval ma','ma.quote_revision_id = qr.quote_revision_id AND o.opportunity_id = ma.opportunity_id','LEFT');
		$this->db->where('p.product_type_id',1);
		/*$where=' cn.contract_note_id = (select cn1.contract_note_id from contract_note cn1  inner join contract_note_quote_revision cnqr1 on cn1.contract_note_id=cnqr1.contract_note_id inner join quote_revision qr1 on cnqr1.quote_revision_id=qr1.quote_revision_id inner join quote_details qd1 on qr1.quote_id=qd1.quote_id inner join opportunity o1 on qd1.opportunity_id=o1.opportunity_id inner join lead l1 on o1.lead_id=l1.lead_id where c.customer_id=l1.customer_id)';
		$this->db->where($where);*/
		if($searchfilters['vtime']!='')
		{	
			if($where1!='')
				$this->db->where($where1);
		}
		$this->db->where('cn.company_id',$this->session->userdata('company'));
		$this->db->where('op.product_id IN ('.$searchfilters['userProducts'].')');
		$this->db->where('l.location_id IN ('.$searchfilters['userLocations'].') ');
		$this->db->where('l.user_id IN ('.$searchfilters['reportee_users'].')');
		if($searchfilters['region']!='')
		{
	    	$this->db->where('l4.location_id',$searchfilters['region']);
	    }
	    $this->db->where('pg.name',$category);
	   $this->db->group_by('c.customer_id');
		$res=$this->db->get();
		return $res->result_array();
	}
	public function get_fresh_business_cnotes_by_region_customer($searchfilters,$category,$val)
	{   
		$fy_year=get_current_fiancial_year();
		if($searchfilters['vtime']!='')
		{
			$parameter="cn.created_time"; 
			$where1=getReportTimelineCheck($searchfilters,$parameter,$fy_year);
		}
		$this->db->select('ROUND(SUM( CASE WHEN ma.margin_approval_id IS NULL THEN (o.required_quantity*qd.total_value)*(1-qr.discount/100) ELSE ( CASE WHEN ma.discount_type = 1 THEN (o.required_quantity*qd.total_value)*(1-ma.discount/100) ELSE (o.required_quantity*qd.total_value)-(ma.discount*qd.currency_factor) END ) END )) AS total_orders,l.user_id,u.first_name as name,cn.contract_note_id');
		$this->db->from('contract_note cn');
		$this->db->join('contract_note_quote_revision cnqr','cn.contract_note_id=cnqr.contract_note_id');
		$this->db->join('quote_revision qr','cnqr.quote_revision_id=qr.quote_revision_id');
		$this->db->join('quote_details qd','qr.quote_id=qd.quote_id');
		$this->db->join('opportunity o','qd.opportunity_id=o.opportunity_id');
		$this->db->join('opportunity_product op','o.opportunity_id=op.opportunity_id');
		$this->db->join('lead l','o.lead_id=l.lead_id');
		$this->db->join('customer c','l.customer_id=c.customer_id');
		$this->db->join('product p','op.product_id=p.product_id');
		$this->db->join('product_group pg','p.group_id=pg.group_id');
		$this->db->join('customer_location cl','c.customer_id=cl.customer_id','inner join');
		$this->db->join('location l1','cl.location_id=l1.location_id','inner join');
		$this->db->join('location l2','l1.parent_id=l2.location_id','inner join');
		$this->db->join('location l3','l2.parent_id=l3.location_id','inner join');
		$this->db->join('location l4','l3.parent_id=l4.location_id','inner join');
		$this->db->join('user u','l.user_id=u.user_id');
		$this->db->join('quote_op_margin_approval ma','ma.quote_revision_id = qr.quote_revision_id AND o.opportunity_id = ma.opportunity_id','LEFT');
		$this->db->where('p.product_type_id',1);
		$this->db->where('cn.company_id',$this->session->userdata('company'));
		if($val==1){
		/*$where=' cn.contract_note_id = (select min(cn1.contract_note_id) from contract_note cn1  inner join contract_note_quote_revision cnqr1 on cn1.contract_note_id=cnqr1.contract_note_id inner join quote_revision qr1 on cnqr1.quote_revision_id=qr1.quote_revision_id inner join quote_details qd1 on qr1.quote_id=qd1.quote_id inner join opportunity o1 on qd1.opportunity_id=o1.opportunity_id inner join lead l1 on o1.lead_id=l1.lead_id where c.customer_id=l1.customer_id)';*/
		$this->db->where('cn.business_type',1);
	    }
	    else
	    {
	    	/*$where=' cn.contract_note_id != (select min(cn1.contract_note_id) from contract_note cn1  inner join contract_note_quote_revision cnqr1 on cn1.contract_note_id=cnqr1.contract_note_id inner join quote_revision qr1 on cnqr1.quote_revision_id=qr1.quote_revision_id inner join quote_details qd1 on qr1.quote_id=qd1.quote_id inner join opportunity o1 on qd1.opportunity_id=o1.opportunity_id inner join lead l1 on o1.lead_id=l1.lead_id where c.customer_id=l1.customer_id)';*/
	    	$this->db->where('cn.business_type',2);
	    }
		
		if($searchfilters['vtime']!='')
		{	
			if($where1!='')
				$this->db->where($where1);
		}
		$this->db->where('op.product_id IN ('.$searchfilters['userProducts'].')');
		$this->db->where('l.location_id IN ('.$searchfilters['userLocations'].') ');
		$this->db->where('l.user_id IN ('.$searchfilters['reportee_users'].')');
		if($searchfilters['region']!='')
		{
	    	$this->db->where('l4.location_id',$searchfilters['region']);
	    }
	    $this->db->where('l4.location',$category);
		$this->db->group_by('l.user_id');
		$res=$this->db->get();
		return $res->result_array();
	}
	public function get_fresh_business_cnotes_by_region_customer_results($searchfilters,$category)
	{   
		$fy_year=get_current_fiancial_year();
		if($searchfilters['vtime']!='')
		{
			$parameter="cn.created_time"; 
			$where1=getReportTimelineCheck($searchfilters,$parameter,$fy_year);
		}
		$this->db->select('ROUND(SUM( CASE WHEN ma.margin_approval_id IS NULL THEN (o.required_quantity*qd.total_value)*(1-qr.discount/100) ELSE ( CASE WHEN ma.discount_type = 1 THEN (o.required_quantity*qd.total_value)*(1-ma.discount/100) ELSE (o.required_quantity*qd.total_value)-(ma.discount*qd.currency_factor) END ) END )) AS total_orders,p.product_id,cn.contract_note_id,c.name as c_name,GROUP_CONCAT(DISTINCT(cn.contract_note_id) SEPARATOR ",") as c_noteid,c.customer_id');
		$this->db->from('contract_note cn');
		$this->db->join('contract_note_quote_revision cnqr','cn.contract_note_id=cnqr.contract_note_id');
		$this->db->join('quote_revision qr','cnqr.quote_revision_id=qr.quote_revision_id');
		$this->db->join('quote_details qd','qr.quote_id=qd.quote_id');
		$this->db->join('opportunity o','qd.opportunity_id=o.opportunity_id');
		$this->db->join('opportunity_product op','o.opportunity_id=op.opportunity_id');
		$this->db->join('lead l','o.lead_id=l.lead_id');
		$this->db->join('customer c','l.customer_id=c.customer_id');
		$this->db->join('product p','op.product_id=p.product_id');
		$this->db->join('product_group pg','p.group_id=pg.group_id');
		$this->db->join('customer_location cl','c.customer_id=cl.customer_id','inner join');
		$this->db->join('location l1','cl.location_id=l1.location_id','inner join');
		$this->db->join('location l2','l1.parent_id=l2.location_id','inner join');
		$this->db->join('location l3','l2.parent_id=l3.location_id','inner join');
		$this->db->join('location l4','l3.parent_id=l4.location_id','inner join');
		$this->db->join('quote_op_margin_approval ma','ma.quote_revision_id = qr.quote_revision_id AND o.opportunity_id = ma.opportunity_id','LEFT');
		$this->db->where('p.product_type_id',1);
		$this->db->where('cn.company_id',$this->session->userdata('company'));
		if($searchfilters['vtime']!='')
		{	
			if($where1!='')
				$this->db->where($where1);
		}
		$this->db->where('op.product_id IN ('.$searchfilters['userProducts'].')');
		$this->db->where('l.location_id IN ('.$searchfilters['userLocations'].') ');
		$this->db->where('l.user_id IN ('.$searchfilters['reportee_users'].')');
		if($searchfilters['region']!='')
		{
	    	$this->db->where('l4.location_id',$searchfilters['region']);
	    }
	    $this->db->where('l4.location',$category);
	    $this->db->group_by('c.customer_id');
		$res=$this->db->get();
		return $res->result_array();
	}
	public function get_cn_by_users($region_name,$searchfilters)
	{
		$fy_year=get_current_fiancial_year();
		if($searchfilters['vtime']!='')
		{
			$parameter="c.created_time"; 
			$where1=getReportTimelineCheck($searchfilters,$parameter,$fy_year);
		}
		$this->db->select('concat(u.first_name," ",u.last_name," (",u.employee_id,")") as name,sum(qd.total_value) as total_value');
		$this->db->from('customer c');
		$this->db->join('lead l','c.customer_id=l.customer_id','inner join');
		$this->db->join('user u','l.user_id=u.user_id');
		$this->db->join('opportunity o','l.lead_id=o.lead_id','inner join');
		$this->db->join('quote_details qd','o.opportunity_id=qd.opportunity_id','inner join');
		$this->db->join('quote_revision qr','qd.quote_id=qr.quote_id','inner join');
		$this->db->join('contract_note_quote_revision cnqr','qr.quote_revision_id=cnqr.quote_revision_id','inner join');
		$this->db->join('contract_note cn','cnqr.contract_note_id=cn.contract_note_id','inner join');
		$this->db->join('customer_location cl','c.customer_id=cl.customer_id','inner join');
		$this->db->join('location l1','cl.location_id=l1.location_id','inner join');
		$this->db->join('location l2','l1.parent_id=l2.location_id','inner join');
		$this->db->join('location l3','l2.parent_id=l3.location_id','inner join');
		$this->db->join('location l4','l3.parent_id=l4.location_id','inner join');
		$where=' cn.contract_note_id = (select min(cn1.contract_note_id) from contract_note cn1  inner join contract_note_quote_revision cnqr1 on cn1.contract_note_id=cnqr1.contract_note_id inner join quote_revision qr1 on cnqr1.quote_revision_id=qr1.quote_revision_id inner join quote_details qd1 on qr1.quote_id=qd1.quote_id inner join opportunity o1 on qd1.opportunity_id=o1.opportunity_id inner join lead l1 on o1.lead_id=l1.lead_id where c.customer_id=l1.customer_id)';
		$this->db->where($where);
		if($searchfilters['vtime']!='')
		{
			if($where1!='')
				$this->db->where($where1);
		}
		if($searchfilters['from_date']!='')
		{
			$this->db->where('date(c.created_time)>=',$searchfilters['from_date']); 
		}
		if($searchfilters['to_date']!='')
		{
			$this->db->where('date(c.created_time)<=',$searchfilters['to_date']); 
		}
		if($region_name!='ALL')
		{
		  $this->db->where('l4.location',$region_name);
		}
		$this->db->group_by('l.user_id');
		$res=$this->db->get();
		return $res->result_array();
	}
	public function get_cn_by_customers($region_name,$employee_id,$searchfilters)
	{
		$fy_year=get_current_fiancial_year();
		if($searchfilters['vtime']!='')
		{
			$parameter="c.created_time"; 
			$where1=getReportTimelineCheck($searchfilters,$parameter,$fy_year);
		}
		$this->db->select('c.name,sum(qd.total_value) as total_value');
		$this->db->from('customer c');
		$this->db->join('lead l','c.customer_id=l.customer_id','inner join');
		$this->db->join('user u','l.user_id=u.user_id');
		$this->db->join('opportunity o','l.lead_id=o.lead_id','inner join');
		$this->db->join('quote_details qd','o.opportunity_id=qd.opportunity_id','inner join');
		$this->db->join('quote_revision qr','qd.quote_id=qr.quote_id','inner join');
		$this->db->join('contract_note_quote_revision cnqr','qr.quote_revision_id=cnqr.quote_revision_id','inner join');
		$this->db->join('contract_note cn','cnqr.contract_note_id=cn.contract_note_id','inner join');
		$this->db->join('customer_location cl','c.customer_id=cl.customer_id','inner join');
		$this->db->join('location l1','cl.location_id=l1.location_id','inner join');
		$this->db->join('location l2','l1.parent_id=l2.location_id','inner join');
		$this->db->join('location l3','l2.parent_id=l3.location_id','inner join');
		$this->db->join('location l4','l3.parent_id=l4.location_id','inner join');
		$where=' cn.contract_note_id = (select min(cn1.contract_note_id) from contract_note cn1  inner join contract_note_quote_revision cnqr1 on cn1.contract_note_id=cnqr1.contract_note_id inner join quote_revision qr1 on cnqr1.quote_revision_id=qr1.quote_revision_id inner join quote_details qd1 on qr1.quote_id=qd1.quote_id inner join opportunity o1 on qd1.opportunity_id=o1.opportunity_id inner join lead l1 on o1.lead_id=l1.lead_id where c.customer_id=l1.customer_id)';
		$this->db->where($where);
		if($searchfilters['vtime']!='')
		{
			if($where1!='')
				$this->db->where($where1);
		}
		if($searchfilters['from_date']!='')
		{
			$this->db->where('date(c.created_time)>=',$searchfilters['from_date']); 
		}
		if($searchfilters['to_date']!='')
		{
			$this->db->where('date(c.created_time)<=',$searchfilters['to_date']); 
		}
		if($region_name!='ALL')
		{
		  $this->db->where('l4.location',$region_name);
		}
		if($employee_id!='')
		{
		  $this->db->where('u.employee_id',$employee_id);
		}
		$this->db->group_by('c.customer_id');
		$res=$this->db->get();
		return $res->result_array();
	}

	public function get_open_order_details($category_id,$searchfilters)
	{   
		$fy_year=get_current_fiancial_year();
		if($searchfilters['vtime']!='')
		{
			$parameter="cn.created_time"; 
			$where1=getReportTimelineCheck($searchfilters,$parameter,$fy_year);
		}
		$this->db->select('sum(case when cn.status=3 then ( CASE WHEN ma.margin_approval_id IS NULL THEN (o.required_quantity*qd.total_value)*(1-qr.discount/100) ELSE ( CASE WHEN ma.discount_type = 1 THEN (o.required_quantity*qd.total_value)*(1-ma.discount/100) ELSE (o.required_quantity*qd.total_value)-(ma.discount*qd.currency_factor) END ) END ) else 0 end ) as cfi,
 			sum(case when cn.status=1 then ( CASE WHEN ma.margin_approval_id IS NULL THEN (o.required_quantity*qd.total_value)*(1-qr.discount/100) ELSE ( CASE WHEN ma.discount_type = 1 THEN (o.required_quantity*qd.total_value)*(1-ma.discount/100) ELSE (o.required_quantity*qd.total_value)-(ma.discount*qd.currency_factor) END ) END ) else 0 end ) as so, cn.status');
		$this->db->from('contract_note cn');
		$this->db->join('contract_note_quote_revision cnqr','cn.contract_note_id=cnqr.contract_note_id');
		$this->db->join('quote_revision qr','cnqr.quote_revision_id=qr.quote_revision_id');
		$this->db->join('quote_details qd','qr.quote_id=qd.quote_id');
		$this->db->join('opportunity o','qd.opportunity_id=o.opportunity_id');
		$this->db->join('opportunity_product op','o.opportunity_id=op.opportunity_id');
		$this->db->join('product p','op.product_id=p.product_id');
		$this->db->join('product_group pg','p.group_id=pg.group_id');
		/*$this->db->join('weekly_user_product_target wupt','p.product_id=wupt.product_id and op.product_id=wupt.product_id');*/
		$this->db->join('lead l','o.lead_id=l.lead_id');
		$this->db->join('location l1','l.location_id=l1.location_id');
		$this->db->join('location l2','l1.parent_id=l2.location_id');
		$this->db->join('location l3','l2.parent_id=l3.location_id');
		$this->db->join('location l4','l3.parent_id=l4.location_id');
		$this->db->join('quote_op_margin_approval ma','ma.quote_revision_id = qr.quote_revision_id AND o.opportunity_id = ma.opportunity_id','LEFT');
		$this->db->where('cn.status in (1,3)');
		$this->db->where('cn.company_id',$this->session->userdata('company'));
		$this->db->where('op.product_id IN ('.$searchfilters['userProducts'].')');
		$this->db->where('l.location_id IN ('.$searchfilters['userLocations'].') ');
			/*$where='op.product_id in (SELECT wupt.product_id from weekly_user_product_target wupt join fy_week fw on wupt.fy_week_id=fw.fy_week_id  where wupt.status=1 and fw.fy_id='.$fy_year['fy_id'].' and wupt.user_id in('.$user_reportees.'))';
		$this->db->where($where);*/
		$this->db->where('l.user_id IN ('.$searchfilters['reportee_users'].')');
		if($searchfilters['region']!='')
		{
	    	$this->db->where('l4.location_id',$searchfilters['region']);
	    }
		$this->db->where('pg.category_id',$category_id);
		//$this->db->where('l4.location',$location);
		if($searchfilters['vtime']!='')
		{
			if($where1!='')
				$this->db->where($where1);
		}
		//$this->db->group_by('cn.status');
		$res=$this->db->get();
		return $res->row_array();
	}

	public function get_previous_open_order_details($category_id,$searchfilters)
	{   
		$fy_year=get_current_fiancial_year();
		if($searchfilters['vtime']!='')
		{
			$parameter="cn.created_time"; 
			$where1=getOppTimelineCheck($searchfilters,$parameter,$fy_year);
		}
		$this->db->select('sum(case when cn.status=3 then ( CASE WHEN ma.margin_approval_id IS NULL THEN (o.required_quantity*qd.total_value)*(1-qr.discount/100) ELSE ( CASE WHEN ma.discount_type = 1 THEN (o.required_quantity*qd.total_value)*(1-ma.discount/100) ELSE (o.required_quantity*qd.total_value)-(ma.discount*qd.currency_factor) END ) END ) else 0 end ) as pcfi,
 			sum(case when cn.status=1 then ( CASE WHEN ma.margin_approval_id IS NULL THEN (o.required_quantity*qd.total_value)*(1-qr.discount/100) ELSE ( CASE WHEN ma.discount_type = 1 THEN (o.required_quantity*qd.total_value)*(1-ma.discount/100) ELSE (o.required_quantity*qd.total_value)-(ma.discount*qd.currency_factor) END ) END ) else 0 end ) as pso, cn.status');
		$this->db->from('contract_note cn');
		$this->db->join('contract_note_quote_revision cnqr','cn.contract_note_id=cnqr.contract_note_id');
		$this->db->join('quote_revision qr','cnqr.quote_revision_id=qr.quote_revision_id');
		$this->db->join('quote_details qd','qr.quote_id=qd.quote_id');
		$this->db->join('opportunity o','qd.opportunity_id=o.opportunity_id');
		$this->db->join('opportunity_product op','o.opportunity_id=op.opportunity_id');
		$this->db->join('product p','op.product_id=p.product_id');
		$this->db->join('product_group pg','p.group_id=pg.group_id');
		/*$this->db->join('weekly_user_product_target wupt','p.product_id=wupt.product_id and op.product_id=wupt.product_id');*/
		$this->db->join('lead l','o.lead_id=l.lead_id');
		$this->db->join('location l1','l.location_id=l1.location_id');
		$this->db->join('location l2','l1.parent_id=l2.location_id');
		$this->db->join('location l3','l2.parent_id=l3.location_id');
		$this->db->join('location l4','l3.parent_id=l4.location_id');
		$this->db->join('quote_op_margin_approval ma','ma.quote_revision_id = qr.quote_revision_id AND o.opportunity_id = ma.opportunity_id','LEFT');
		$this->db->where('cn.status in (1,3)');
		$this->db->where('cn.company_id',$this->session->userdata('company'));
		$this->db->where('op.product_id IN ('.$searchfilters['userProducts'].')');
		$this->db->where('l.location_id IN ('.$searchfilters['userLocations'].') ');
			/*$where='op.product_id in (SELECT wupt.product_id from weekly_user_product_target wupt join fy_week fw on wupt.fy_week_id=fw.fy_week_id  where wupt.status=1 and fw.fy_id='.$fy_year['fy_id'].' and wupt.user_id in('.$user_reportees.'))';
		$this->db->where($where);*/
		$this->db->where('l.user_id IN ('.$searchfilters['reportee_users'].')');
		if($searchfilters['region']!='')
		{
	    	$this->db->where('l4.location_id',$searchfilters['region']);
	    }
		$this->db->where('pg.category_id',$category_id);
		//$this->db->where('l4.location',$location);
		if($searchfilters['vtime']!='')
		{
			if($where1!='')
				$this->db->where($where1);
		}
		//$this->db->group_by('cn.status');
		$res=$this->db->get();
		return $res->row_array();
	}

	public function get_open_orders_by_segment($status,$category,$searchfilters)
	{
		$fy_year=get_current_fiancial_year();
		if($searchfilters['vtime']!='')
		{
			$parameter="cn.created_time"; 
			$where1=getReportTimelineCheck($searchfilters,$parameter,$fy_year);
		}
		$this->db->select('ROUND(SUM( CASE WHEN ma.margin_approval_id IS NULL THEN (o.required_quantity*qd.total_value)*(1-qr.discount/100) ELSE ( CASE WHEN ma.discount_type = 1 THEN (o.required_quantity*qd.total_value)*(1-ma.discount/100) ELSE (o.required_quantity*qd.total_value)- (ma.discount*qd.currency_factor) END ) END )) AS total_orders,pg.name,pg.group_id');
		$this->db->from('contract_note cn');
		$this->db->join('contract_note_quote_revision cnqr','cn.contract_note_id=cnqr.contract_note_id');
		$this->db->join('quote_revision qr','cnqr.quote_revision_id=qr.quote_revision_id');
		$this->db->join('quote_details qd','qr.quote_id=qd.quote_id');
		$this->db->join('opportunity o','qd.opportunity_id=o.opportunity_id');
		$this->db->join('opportunity_product op','o.opportunity_id=op.opportunity_id');
		$this->db->join('product p','op.product_id=p.product_id');
		$this->db->join('product_group pg','p.group_id=pg.group_id');
		$this->db->join('product_category pc','pg.category_id=pc.category_id');
		/*$this->db->join('weekly_user_product_target wupt','p.product_id=wupt.product_id and op.product_id=wupt.product_id');*/
		$this->db->join('lead l','o.lead_id=l.lead_id');
		$this->db->join('location l1','l.location_id=l1.location_id');
		$this->db->join('location l2','l1.parent_id=l2.location_id');
		$this->db->join('location l3','l2.parent_id=l3.location_id');
		$this->db->join('location l4','l3.parent_id=l4.location_id');
		$this->db->join('quote_op_margin_approval ma','ma.quote_revision_id = qr.quote_revision_id AND o.opportunity_id = ma.opportunity_id','LEFT');
		//$this->db->where('pg.category_id',$category_id);
		if($searchfilters['vtime']!='')
		{
			if($where1!='')
				$this->db->where($where1);
		}
		$this->db->where('cn.status',$status);
		$this->db->where('cn.company_id',$this->session->userdata('company'));
		$this->db->where('l.user_id IN ('.$searchfilters['reportee_users'].')');
		if($searchfilters['region']!='')
		{
	    	$this->db->where('l4.location_id',$searchfilters['region']);
	    }
		$this->db->where('op.product_id IN ('.$searchfilters['userProducts'].')');
		$this->db->where('l.location_id IN ('.$searchfilters['userLocations'].') ');
		$this->db->where('pc.name',$category);
		$this->db->group_by('pg.name');
		$res=$this->db->get();
		//echo $this->db->last_query();
		return $res->result_array();
	}
	public function get_previous_open_orders_by_segment($status,$category,$searchfilters)
	{
		$fy_year=get_current_fiancial_year();
		if($searchfilters['vtime']!='')
		{
			$parameter="cn.created_time"; 
			$where1=getOppTimelineCheck($searchfilters,$parameter,$fy_year);
		}
		$this->db->select('ROUND(SUM( CASE WHEN ma.margin_approval_id IS NULL THEN (o.required_quantity*qd.total_value)*(1-qr.discount/100) ELSE ( CASE WHEN ma.discount_type = 1 THEN (o.required_quantity*qd.total_value)*(1-ma.discount/100) ELSE (o.required_quantity*qd.total_value)-(ma.discount*qd.currency_factor) END ) END )) AS previous_total_orders,pg.name,pg.group_id');
		$this->db->from('contract_note cn');
		$this->db->join('contract_note_quote_revision cnqr','cn.contract_note_id=cnqr.contract_note_id');
		$this->db->join('quote_revision qr','cnqr.quote_revision_id=qr.quote_revision_id');
		$this->db->join('quote_details qd','qr.quote_id=qd.quote_id');
		$this->db->join('opportunity o','qd.opportunity_id=o.opportunity_id');
		$this->db->join('opportunity_product op','o.opportunity_id=op.opportunity_id');
		$this->db->join('product p','op.product_id=p.product_id');
		$this->db->join('product_group pg','p.group_id=pg.group_id');
		$this->db->join('product_category pc','pg.category_id=pc.category_id');
		/*$this->db->join('weekly_user_product_target wupt','p.product_id=wupt.product_id and op.product_id=wupt.product_id');*/
		$this->db->join('lead l','o.lead_id=l.lead_id');
		$this->db->join('location l1','l.location_id=l1.location_id');
		$this->db->join('location l2','l1.parent_id=l2.location_id');
		$this->db->join('location l3','l2.parent_id=l3.location_id');
		$this->db->join('location l4','l3.parent_id=l4.location_id');
		$this->db->join('quote_op_margin_approval ma','ma.quote_revision_id = qr.quote_revision_id AND o.opportunity_id = ma.opportunity_id','LEFT');
		//$this->db->where('pg.category_id',$category_id);
		if($searchfilters['vtime']!='')
		{
			if($where1!='')
				$this->db->where($where1);
		}
		$this->db->where('cn.status',$status);
		$this->db->where('cn.company_id',$this->session->userdata('company'));
		$this->db->where('l.user_id IN ('.$searchfilters['reportee_users'].')');
		if($searchfilters['region']!='')
		{
	    	$this->db->where('l4.location_id',$searchfilters['region']);
	    }
		$this->db->where('op.product_id IN ('.$searchfilters['userProducts'].')');
		$this->db->where('l.location_id IN ('.$searchfilters['userLocations'].') ');
		$this->db->where('pc.name',$category);
		$this->db->group_by('pg.name');
		$res=$this->db->get();
		//echo $this->db->last_query();
		return $res->result_array();
	}
	public function get_open_orders_by_customers($searchfilters,$status,$category,$segment)
	{   
		$fy_year=get_current_fiancial_year();
		if($searchfilters['vtime']!='')
		{
			$parameter="cn.created_time"; 
			$where1=getReportTimelineCheck($searchfilters,$parameter,$fy_year);
		}
		$this->db->select(' ROUND(SUM( CASE WHEN ma.margin_approval_id IS NULL THEN (o.required_quantity*qd.total_value)*(1-qr.discount/100) ELSE ( CASE WHEN ma.discount_type = 1 THEN (o.required_quantity*qd.total_value)*(1-ma.discount/100) ELSE (o.required_quantity*qd.total_value)- (ma.discount*qd.currency_factor) END ) END )) AS total_orders,c.name,l5.location');
		$this->db->from('contract_note cn');
		$this->db->join('contract_note_quote_revision cnqr','cn.contract_note_id=cnqr.contract_note_id');
		$this->db->join('quote_revision qr','cnqr.quote_revision_id=qr.quote_revision_id');
		$this->db->join('quote_details qd','qr.quote_id=qd.quote_id');
		$this->db->join('opportunity o','qd.opportunity_id=o.opportunity_id');
		$this->db->join('opportunity_product op','o.opportunity_id=op.opportunity_id');
		$this->db->join('product p','op.product_id=p.product_id');
		$this->db->join('product_group pg','p.group_id=pg.group_id');
		$this->db->join('product_category pc','pg.category_id=pc.category_id');

		/*$this->db->join('weekly_user_product_target wupt','p.product_id=wupt.product_id and op.product_id=wupt.product_id');*/
		$this->db->join('lead l','o.lead_id=l.lead_id');
		$this->db->join('location l1','l.location_id=l1.location_id');
		$this->db->join('location l2','l1.parent_id=l2.location_id');
		$this->db->join('location l3','l2.parent_id=l3.location_id');
		$this->db->join('location l4','l3.parent_id=l4.location_id');
		$this->db->join('customer c','l.customer_id=c.customer_id');
		$this->db->join('customer_location cl','cl.customer_id=c.customer_id');
		$this->db->join('location l5','l5.location_id=cl.location_id');
		$this->db->join('quote_op_margin_approval ma','ma.quote_revision_id = qr.quote_revision_id AND o.opportunity_id = ma.opportunity_id','LEFT');
		//$this->db->where('pg.category_id',$category_id);
		$this->db->where('cn.status',$status);
		$this->db->where('cn.company_id',$this->session->userdata('company'));
		$this->db->where('pc.name',$category);
		if($segment!='ALL Segments')
		{
			$this->db->where('pg.name',$segment);
	    }
		if($searchfilters['vtime']!='')
		{
			if($where1!='')
				$this->db->where($where1);
		}
		$this->db->where('l.user_id IN ('.$searchfilters['reportee_users'].')');
		if($searchfilters['region']!='')
		{
	    	$this->db->where('l4.location_id',$searchfilters['region']);
	    }
		$this->db->where('op.product_id IN ('.$searchfilters['userProducts'].')');
		$this->db->where('l.location_id IN ('.$searchfilters['userLocations'].') ');
		$this->db->group_by('c.name');
		$res=$this->db->get();
		return $res->result_array();
	}
	public function get_open_orders_by_products($searchfilters,$status,$category,$segment)
	{   
		$fy_year=get_current_fiancial_year();
		if($searchfilters['vtime']!='')
		{
			$parameter="cn.created_time"; 
			$where1=getReportTimelineCheck($searchfilters,$parameter,$fy_year);
		}
		$this->db->select(' ROUND(SUM( CASE WHEN ma.margin_approval_id IS NULL THEN (o.required_quantity*qd.total_value)*(1-qr.discount/100) ELSE ( CASE WHEN ma.discount_type = 1 THEN (o.required_quantity*qd.total_value)*(1-ma.discount/100) ELSE (o.required_quantity*qd.total_value)- (ma.discount*qd.currency_factor) END ) END )) AS total_orders,sum(o.required_quantity) as stock,p.product_id,pg.name as segment_name,p.description as product_description,p.name as product_name');
		$this->db->from('contract_note cn');
		$this->db->join('contract_note_quote_revision cnqr','cn.contract_note_id=cnqr.contract_note_id');
		$this->db->join('quote_revision qr','cnqr.quote_revision_id=qr.quote_revision_id');
		$this->db->join('quote_details qd','qr.quote_id=qd.quote_id');
		$this->db->join('opportunity o','qd.opportunity_id=o.opportunity_id');
		$this->db->join('opportunity_product op','o.opportunity_id=op.opportunity_id');
		$this->db->join('product p','op.product_id=p.product_id');
		$this->db->join('product_group pg','p.group_id=pg.group_id');
		$this->db->join('product_category pc','pg.category_id=pc.category_id');
		/*$this->db->join('weekly_user_product_target wupt','p.product_id=wupt.product_id and op.product_id=wupt.product_id');*/
		$this->db->join('lead l','o.lead_id=l.lead_id');
		$this->db->join('location l1','l.location_id=l1.location_id');
		$this->db->join('location l2','l1.parent_id=l2.location_id');
		$this->db->join('location l3','l2.parent_id=l3.location_id');
		$this->db->join('location l4','l3.parent_id=l4.location_id');
		$this->db->join('customer c','l.customer_id=c.customer_id');
		$this->db->join('quote_op_margin_approval ma','ma.quote_revision_id = qr.quote_revision_id AND o.opportunity_id = ma.opportunity_id','LEFT');
		//$this->db->where('pg.category_id',$category_id);
		$this->db->where('cn.status',$status);
		$this->db->where('cn.company_id',$this->session->userdata('company'));
		$this->db->where('pc.name',$category);
		if($segment!='ALL Segments')
		{
			$this->db->where('pg.name',$segment);
	    }
		if($searchfilters['vtime']!='')
		{
			if($where1!='')
				$this->db->where($where1);
		}
		$this->db->where('l.user_id IN ('.$searchfilters['reportee_users'].')');
		if($searchfilters['region']!='')
		{
	    	$this->db->where('l4.location_id',$searchfilters['region']);
	    }
		$this->db->where('op.product_id IN ('.$searchfilters['userProducts'].')');
		$this->db->where('l.location_id IN ('.$searchfilters['userLocations'].') ');
		$this->db->group_by('p.product_id');
		$res=$this->db->get();
		return $res->result_array();
	}
	public function get_region($locations)
	{
		//echo $locations.'<br>';
		$this->db->select('l4.location_id,l4.location');
		$this->db->from('location l1');
		$this->db->join('location l2','l1.parent_id=l2.location_id');
		$this->db->join('location l3','l2.parent_id=l3.location_id');
		$this->db->join('location l4','l3.parent_id=l4.location_id');
		$this->db->where_in('l1.location_id',$locations);
		//$this->db->where('l4.territory_level_id',4);
		$this->db->group_by('l4.location_id');
		$res=$this->db->get();
	//	echo $this->db->last_query();
		return $res->result_array();
	}
	public function get_financial_year_weeks($searchfilters)
	{   
		$curyear=$searchfilters['cur_year'];
		$curmonth=$searchfilters['cur_month'];
		//print_r($searchfilters);exit;
		$this->db->select('fw.start_date,fw.end_date,fw.fy_week_id');
		$this->db->from('financial_year f');
		$this->db->join('fy_week fw','f.fy_id=fw.fy_id');
		$this->db->where('fw.year_no',$curyear);
		$this->db->where('fw.month_no',$curmonth);
		$this->db->where('f.company_id',$this->session->userdata('company'));
		if($curmonth==date('m')&&$curyear==date('Y'))
		{
			$this->db->where('fw.start_date<=',date('Y-m-d'));
		}
		$res=$this->db->get();
		return $res->result_array();
	}
	public function get_custom_financial_year_weeks($searchfilters)
	{   
		$curyear=$searchfilters['cur_year'];
		$curmonth=$searchfilters['cur_month'];
		//print_r($searchfilters);exit;
		$this->db->select('fw.start_date,fw.end_date,fw.fy_week_id');
		$this->db->from('financial_year f');
		$this->db->join('custom_fy_week fw','f.fy_id=fw.fy_id');
		$this->db->where('f.company_id',$this->session->userdata('company'));
		$this->db->where('fw.year_no',$curyear);
		$this->db->where('fw.month_no',$curmonth);
		if($curmonth==date('m')&&$curyear==date('Y'))
		{
			$this->db->where('fw.start_date<=',date('Y-m-d'));
		}
		$res=$this->db->get();
		return $res->result_array();
	}
	public function get_week_wise_open_opportunites($search_date,$searchfilters)
	{   
		$open_opportunity_string=get_open_opportunity_helper_string();
		$user_id=$this->session->userdata('user_id');
	    $query='select sum(o.required_quantity*p.dp) as opp_value from opportunity o 
		inner join opportunity_status_history osh on o.opportunity_id=osh.opportunity_id  
		inner join opportunity_product od on o.opportunity_id=od.opportunity_id
		inner join lead l on o.lead_id=l.lead_id
		inner join product p on od.product_id=p.product_id
		where osh.opportunity_status_id = (select max(osh1.opportunity_status_id) from opportunity_status_history osh1
		where osh1.opportunity_id=osh.opportunity_id 
		and date(osh1.created_time)<="'.$search_date.'") and osh.status in'.$open_opportunity_string.'';
		if(@$searchfilters['loc_string']!='')
		{
			$query.=' AND l.location_id in ('.$searchfilters['loc_string'].')';
		}
		else
		{
			$query.=' AND l.user_id='.$user_id.'';
		}
		$query.=' AND p.product_id in ('.$searchfilters['products'].')';
		$res=$this->db->query($query);
		//echo $this->db->last_query();exit;
		return $res->row_array();
	}
	public function get_segment_wise_open_opportunities($last_day,$searchfilters)
	{   
		$open_opportunity_string=get_open_opportunity_helper_string();
		$user_id=$this->session->userdata('user_id');
		$query=	'select pg.name, sum(o.required_quantity*p.dp) as opp_value from opportunity o 
		inner join opportunity_status_history osh on o.opportunity_id=osh.opportunity_id
		inner join opportunity_product od on o.opportunity_id=od.opportunity_id
		inner join lead l on o.lead_id=l.lead_id
		inner join product p on od.product_id=p.product_id 
		inner join product_group pg on p.group_id=pg.group_id
		where osh.opportunity_status_id = (select max(osh1.opportunity_status_id) from opportunity_status_history osh1
		where osh1.opportunity_id=osh.opportunity_id and date(osh1.created_time)<="'.$last_day.'")
		and osh.status in'.$open_opportunity_string.' ';
		if(@$searchfilters['loc_string']!='')
		{
			$query.=' AND l.location_id in ('.$searchfilters['loc_string'].')';
		}
		else
		{
			$query.=' AND l.user_id='.$user_id.'';
		}
		$query.=' AND p.product_id in ('.$searchfilters['products'].')';
		$query.=' group by pg.group_id';
		$res=$this->db->query($query);
		return $res->result_array();
	}
	public function get_reason_wise_open_opportunities($last_day,$searchfilters)
	{   
		$open_opportunity_string=get_open_opportunity_helper_string();
		$user_id=$this->session->userdata('user_id');
		$query='select os.name, sum(o.required_quantity*p.dp) as opp_value from opportunity o 
		inner join opportunity_status_history osh on o.opportunity_id=osh.opportunity_id
		inner join opportunity_product od on o.opportunity_id=od.opportunity_id
		inner join lead l on o.lead_id=l.lead_id
		inner join product p on od.product_id=p.product_id 
		inner join opportunity_status os  on osh.status=os.status
		where osh.opportunity_status_id = (select max(osh1.opportunity_status_id) from opportunity_status_history osh1
		where osh1.opportunity_id=osh.opportunity_id and date(osh1.created_time)<="'.$last_day.'")
		and osh.status in'.$open_opportunity_string.' ';
		if(@$searchfilters['loc_string']!='')
		{
			$query.=' AND l.location_id in ('.$searchfilters['loc_string'].')';
		}
		else
		{
			$query.=' AND l.user_id='.$user_id.'';
		}
		$query.=' AND p.product_id in ('.$searchfilters['products'].')';
		$query.=' group by os.status';
		$res=$this->db->query($query);
		return $res->result_array();
    }
    public function get_week_wise_segment_open_opportunites($search_date,$group_id,$searchfilters)
	{   
		$open_opportunity_string=get_open_opportunity_helper_string();
		$user_id=$this->session->userdata('user_id');
		$query='select sum(o.required_quantity*p.dp) as opp_value from opportunity o 
		inner join opportunity_status_history osh on o.opportunity_id=osh.opportunity_id  
		inner join opportunity_product od on o.opportunity_id=od.opportunity_id
		inner join lead l on o.lead_id=l.lead_id
		inner join product p on od.product_id=p.product_id
		where osh.opportunity_status_id = (select max(osh1.opportunity_status_id) from opportunity_status_history osh1
		where osh1.opportunity_id=osh.opportunity_id 
		and date(osh1.created_time)<="'.$search_date.'") and p.group_id="'.$group_id.'" and osh.status in'.$open_opportunity_string.' ';
		if(@$searchfilters['loc_string']!='')
		{
			$query.=' AND l.location_id in ('.$searchfilters['loc_string'].')';
		}
		else
		{
			$query.=' AND l.user_id='.$user_id.'';
		}
		$res=$this->db->query($query);
		return $res->row_array();
	}
	public function get_product_group_from_products($products)
	{
		$this->db->select('pg.group_id,pg.name');
		$this->db->from('product_group pg');
		$this->db->join('product p','pg.group_id=p.group_id');
		$this->db->where('p.product_id in ('.$products.')');
		$this->db->where('pg.status',1);
		$this->db->group_by('pg.group_id');
		$res=$this->db->get();
		return $res->result_array();
	}
	public function get_margin_by_product_wise($searchfilters)
	{   
		$role_id=$this->session->userdata('role_id'); 
		$fy_year=get_current_fiancial_year();
		if($searchfilters['vtime']!='')
		{
			$parameter="cn.created_time"; 
			$where1=getReportTimelineCheck($searchfilters,$parameter,$fy_year);
		}
		$this->db->select('(CASE WHEN ma.discount_type=1 
						THEN round(((((qd.mrp)*(1-ma.discount/100)-p.dp))/p.dp)*100,2) 
						ELSE round(((((qd.mrp)-ma.discount)-p.dp)/p.dp)*100,2) END ) as margin,p.name as product_name,qd.mrp,p.dp');
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
		//$this->db->join('product_group')
		//$this->db->join('dealer_commission_payment dcp','qr.dealer_id=dcp.user_id AND dcp.status = 1','left');
		$this->db->join('quote_op_margin_approval ma','ma.quote_revision_id=qr.quote_revision_id and ma.opportunity_id=o.opportunity_id','left');
		$this->db->join('distributor_details dd','qr.dealer_id=dd.user_id','left');
		$this->db->join('location l1','l.location_id=l1.location_id');
		$this->db->join('location l2','l1.parent_id=l2.location_id');
		$this->db->join('location l3','l2.parent_id=l3.location_id');
		$this->db->join('location l4','l3.parent_id=l4.location_id');
		
		if($searchfilters['vtime']!='')
		{
			if($where1!='')
				$this->db->where($where1);
		}
		if($searchfilters['from_date']!='')
		{
			$this->db->where('date(cn.created_time)>=',$searchfilters['from_date']); 
		}
		if($searchfilters['to_date']!='')
		{
			$this->db->where('date(cn.created_time)<=',$searchfilters['to_date']); 
		}
		if(in_array($role_id, margin_allowed_roles()))
		{
			if($searchfilters['segment']!='')
			{
				$this->db->where('p.group_id',$searchfilters['segment']);
			}
			if($searchfilters['regions']!='')
			{
				$this->db->where('l4.location_id',$searchfilters['regions']);
			}
			
		}
		elseif($role_id==4||$role_id==5)
		{
			$this->db->where('l.user_id',$this->session->userdata('user_id'));
		}
		elseif($role_id==7||$role_id==8)
		{
			$this->db->where('l.location_id IN ('.$this->session->userdata('locationString').')');
		}
		if($searchfilters['sales']==1)
			{   
				$this->db->where('(CASE WHEN ma.discount_type=1 
							THEN round(((((qd.mrp)*(1-ma.discount/100)-p.dp))/p.dp)*100,2) 
							ELSE round(((((qd.mrp)-ma.discount)-p.dp)/p.dp)*100,2) END ) > 30 ');
				$this->db->order_by('margin','desc');
			}
		if($searchfilters['sales']==2)
		{
			$this->db->where('(CASE WHEN ma.discount_type=1 
						THEN round(((((qd.mrp)*(1-ma.discount/100)-p.dp))/p.dp)*100,2) 
						ELSE round(((((qd.mrp)-ma.discount)-p.dp)/p.dp)*100,2) END ) < 0 ');
			$this->db->order_by('margin','asc');
		}
		$this->db->group_by('o.opportunity_id');
		$this->db->limit($searchfilters['top']);
		$res=$this->db->get();
	 //   echo $this->db->last_query();exit;
		return $res->result_array();
	}

	public function get_previous_target($searchfilters)
	{   
		//print_r($searchfilters['userProducts']);exit;
		$fy_year=get_custom_current_fiancial_year();
		if($searchfilters['vtime']!='')
		{
			$parameter="fw.start_date"; 
		   $where1=getCustomOppTimelineCheck($searchfilters,$parameter,$fy_year);
	   }
	   if($searchfilters['regions']!='')
	    {
	    	$search_by_region=report_user_locations($searchfilters);
			//$this->db->where('wupt.user_id in ('.$search_by_region.')');
	    }
	   
		if($searchfilters['measure']==1)
		{
			$this->db->select('sum(wupt.quantity) as previous_target');
		}
		elseif($searchfilters['measure']==2)
		{
			$this->db->select('round(sum(p.mrp*wupt.quantity)/100000,2) as previous_target');
		}
		$this->db->from('custom_fy_week fw');
		$this->db->join('weekly_user_product_target wupt','fw.fy_week_id=wupt.fy_week_id');
		$this->db->join('product p','wupt.product_id=p.product_id');
		
		if($searchfilters['vtime']!='')
		{
			if($where1!='')
				$this->db->where($where1);
		}
		$user_reportees=$searchfilters['user_reportees_tvs'];
		$this->db->where('wupt.user_id in ('.$user_reportees.')');
		$this->db->where('fw.fy_id',$fy_year['fy_id']);
		$this->db->where('p.target',1);
		if($searchfilters['regions']!='')
	    {
	    	//$search_by_region=report_user_locations($searchfilters);
	    	if($search_by_region=='')
	    	{
	    		$search_by_region=0;
	    	}
			$this->db->where('wupt.user_id in ('.$search_by_region.')');
	    }

		$res=$this->db->get();
		//echo $this->db->last_query();exit;
		return $res->row_array();
	}
	public function get_previous_sales($searchfilters)
	{
		$fy_year=get_custom_current_fiancial_year();
		if($searchfilters['vtime']!='')
		{
			$parameter="cn.created_time"; 
			$where1=getCustomOppTimelineCheck($searchfilters,$parameter,$fy_year);
		}
		if($searchfilters['measure']==1)
		{
			$this->db->select('sum(o.required_quantity) as previous_sales,cn.status');
		}
		elseif($searchfilters['measure']==2)
		{
			$this->db->select('ROUND(SUM( CASE WHEN ma.margin_approval_id IS NULL THEN ((o.required_quantity*qd.total_value)*(1-qr.discount/100))/100000 ELSE ( CASE WHEN ma.discount_type = 1 THEN ((o.required_quantity*qd.total_value)*(1-ma.discount/100))/100000 ELSE ((o.required_quantity*qd.total_value)-(ma.discount*qd.currency_factor))/100000 END ) END ),2) AS previous_sales');
		}
		$this->db->from('contract_note cn');
		$this->db->join('contract_note_quote_revision cnqr','cn.contract_note_id=cnqr.contract_note_id');
		$this->db->join('quote_revision qr','cnqr.quote_revision_id=qr.quote_revision_id');
		$this->db->join('quote_details qd','qr.quote_id=qd.quote_id');
		$this->db->join('opportunity o','qd.opportunity_id=o.opportunity_id');
		$this->db->join('opportunity_product op','o.opportunity_id=op.opportunity_id');
		$this->db->join('product p','op.product_id=p.product_id');
		/*$this->db->join('weekly_user_product_target wupt','p.product_id=wupt.product_id and op.product_id=wupt.product_id');*/
		$this->db->join('lead l','o.lead_id=l.lead_id');
		$this->db->join('location l1','l.location_id=l1.location_id');
		$this->db->join('location l2','l1.parent_id=l2.location_id');
		$this->db->join('location l3','l2.parent_id=l3.location_id');
		$this->db->join('location l4','l3.parent_id=l4.location_id');
		$this->db->join('quote_op_margin_approval ma','ma.quote_revision_id = qr.quote_revision_id AND o.opportunity_id = ma.opportunity_id','LEFT');
		$this->db->where('cn.status',2);
		$this->db->where('cn.company_id',$this->session->userdata('company'));
		$user_reportees=$searchfilters['user_reportees_tvs'];
		/*$where='op.product_id in (SELECT wupt.product_id from weekly_user_product_target wupt join fy_week fw on wupt.fy_week_id=fw.fy_week_id  where wupt.status=1 and fw.fy_id='.$fy_year['fy_id'].' and wupt.user_id in('.$user_reportees.'))';
		$this->db->where($where);*/
		if($searchfilters['vtime']!='')
		{
			if($where1!='')
				$this->db->where($where1);
		}
		$this->db->where('l.user_id in ('.$user_reportees.')');
		$this->db->where('p.target',1);
	    if($searchfilters['regions']!='')
	    {
	    	$this->db->where('l4.location_id',$searchfilters['regions']);
	    }
	    $userProducts=$searchfilters['userProducts'];
	    $userLocations=$searchfilters['userLocations'];
	    $this->db->where('op.product_id IN ('.$userProducts.')');
		$this->db->where('l.location_id IN ('.$userLocations.') ');
	    $res=$this->db->get();
	//	echo $this->db->last_query();exit;
		return $res->row_array();
	    //print_r($kk);exit;
	}
	public function get_current_target($searchfilters)
	{
		/*$month_no=date('m');
		$year_no=date('Y');*/
		$fy_year=get_custom_current_fiancial_year();
		if($searchfilters['regions']!='')
	    {
	    	$search_by_region=report_user_locations($searchfilters);
			//$this->db->where('wupt.user_id in ('.$search_by_region.')');
	    }
	    if($searchfilters['vtime']!='')
		{
			$parameter="fw.start_date"; 
			$where1=getCustomReportTimelineCheck($searchfilters,$parameter,$fy_year);
		}
		if($searchfilters['measure']==1)
		{
			$this->db->select('sum(wupt.quantity) as current_target');
		}
		elseif($searchfilters['measure']==2)
		{
			$this->db->select('round(sum(p.mrp*wupt.quantity)/100000,2) as current_target');
		}
		$this->db->from('custom_fy_week fw');
		$this->db->join('weekly_user_product_target wupt','fw.fy_week_id=wupt.fy_week_id');
		$this->db->join('product p','wupt.product_id=p.product_id');
		$user_reportees=$searchfilters['user_reportees_tvs'];
		$this->db->where('wupt.user_id in ('.$user_reportees.')');
		$this->db->where('p.target',1);
	    if($searchfilters['regions']!='')
	    {
	    	//$search_by_region=report_user_locations($searchfilters);
	    	if($search_by_region=='')
	    	{
	    		$search_by_region=0;
	    	}
			$this->db->where('wupt.user_id in ('.$search_by_region.')');
	    }
	    /*$userProducts=$searchfilters['userProducts'];
		$this->db->where('p.product_id IN ('.$userProducts.')');*/
	    if($searchfilters['vtime']!='')
		{
			if($where1!='')
				$this->db->where($where1);
		}
		$res=$this->db->get();
		//echo $this->db->last_query();exit;
		return $res->row_array();
	//	print_r($kk);exit;
	}
	public function get_current_sales($searchfilters)
	{
		$fy_year=get_custom_current_fiancial_year();
		 if($searchfilters['vtime']!='')
		{
			$parameter="cn.created_time"; 
			$where1=getCustomReportTimelineCheck($searchfilters,$parameter,$fy_year);
		}
		if($searchfilters['measure']==1)
		{
			$this->db->select('sum(o.required_quantity) as current_sales');
		}
		elseif($searchfilters['measure']==2)
		{
			$this->db->select('ROUND(SUM( CASE WHEN ma.margin_approval_id IS NULL THEN ((o.required_quantity*qd.total_value)*(1-qr.discount/100))/100000 ELSE ( CASE WHEN ma.discount_type = 1 THEN ((o.required_quantity*qd.total_value)*(1-ma.discount/100))/100000 ELSE ((o.required_quantity*qd.total_value)-(ma.discount*qd.currency_factor))/100000 END ) END ),2) AS current_sales');
		}
		$this->db->from('contract_note cn');
		$this->db->join('contract_note_quote_revision cnqr','cn.contract_note_id=cnqr.contract_note_id');
		$this->db->join('quote_revision qr','cnqr.quote_revision_id=qr.quote_revision_id');
		$this->db->join('quote_details qd','qr.quote_id=qd.quote_id');
		$this->db->join('opportunity o','qd.opportunity_id=o.opportunity_id');
		$this->db->join('opportunity_product op','o.opportunity_id=op.opportunity_id');
		$this->db->join('product p','op.product_id=p.product_id');
		/*$this->db->join('weekly_user_product_target wupt','p.product_id=wupt.product_id and op.product_id=wupt.product_id');*/
		$this->db->join('lead l','o.lead_id=l.lead_id');
		$this->db->join('location l1','l.location_id=l1.location_id');
		$this->db->join('location l2','l1.parent_id=l2.location_id');
		$this->db->join('location l3','l2.parent_id=l3.location_id');
		$this->db->join('location l4','l3.parent_id=l4.location_id');
		$this->db->join('quote_op_margin_approval ma','ma.quote_revision_id = qr.quote_revision_id AND o.opportunity_id = ma.opportunity_id','LEFT');
		$this->db->where('cn.status',2);
		
		/*$where='op.product_id in (SELECT wupt.product_id from weekly_user_product_target wupt join fy_week fw on wupt.fy_week_id=fw.fy_week_id  where wupt.status=1 and fw.fy_id='.$fy_year['fy_id'].' and wupt.user_id in('.$user_reportees.'))';
		$this->db->where($where);*/
		$user_reportees=$searchfilters['user_reportees_tvs'];
	    $this->db->where('l.user_id in ('.$user_reportees.')');
	    if($searchfilters['regions']!='')
	    {   
	    	$this->db->where('l4.location_id',$searchfilters['regions']);
	    }
	    if($searchfilters['vtime']!='')
		{
			if($where1!='')
				$this->db->where($where1);
		}
		$this->db->where('cn.company_id',$this->session->userdata('company'));
		$this->db->where('p.target',1);
		$userProducts=$searchfilters['userProducts'];
	    $userLocations=$searchfilters['userLocations'];
	    $this->db->where('op.product_id IN ('.$userProducts.')');
		$this->db->where('l.location_id IN ('.$userLocations.') ');
	    $res=$this->db->get();
		//echo $this->db->last_query();exit;
		return $res->row_array();
	    //print_r($kk);exit;
	}
	public function get_open_orders($searchfilters)
	{
		$fy_year=get_custom_current_fiancial_year();
		if($searchfilters['vtime']!='')
		{
			$parameter="cn.created_time"; 
			$where1=getCustomReportTimelineCheck($searchfilters,$parameter,$fy_year);
		}
		if($searchfilters['measure']==1)
		{
			$this->db->select('sum(o.required_quantity) as open_orders,cn.status');
		}
		elseif($searchfilters['measure']==2)
		{
			$this->db->select('ROUND(SUM( CASE WHEN ma.margin_approval_id IS NULL THEN ((o.required_quantity*qd.total_value)*(1-qr.discount/100))/100000 ELSE ( CASE WHEN ma.discount_type = 1 THEN ((o.required_quantity*qd.total_value)*(1-ma.discount/100))/100000 ELSE ((o.required_quantity*qd.total_value)-(ma.discount*qd.currency_factor))/100000 END ) END ),2) AS open_orders');
		}
		$this->db->from('contract_note cn');
		$this->db->join('contract_note_quote_revision cnqr','cn.contract_note_id=cnqr.contract_note_id');
		$this->db->join('quote_revision qr','cnqr.quote_revision_id=qr.quote_revision_id');
		$this->db->join('quote_details qd','qr.quote_id=qd.quote_id');
		$this->db->join('opportunity o','qd.opportunity_id=o.opportunity_id');
		$this->db->join('opportunity_product op','o.opportunity_id=op.opportunity_id');
		$this->db->join('product p','op.product_id=p.product_id');
		/*$this->db->join('weekly_user_product_target wupt','p.product_id=wupt.product_id and op.product_id=wupt.product_id');*/
		$this->db->join('lead l','o.lead_id=l.lead_id');
		$this->db->join('location l1','l.location_id=l1.location_id');
		$this->db->join('location l2','l1.parent_id=l2.location_id');
		$this->db->join('location l3','l2.parent_id=l3.location_id');
		$this->db->join('location l4','l3.parent_id=l4.location_id');
		$this->db->join('quote_op_margin_approval ma','ma.quote_revision_id = qr.quote_revision_id AND o.opportunity_id = ma.opportunity_id','LEFT');
		$this->db->where('cn.status in (1,3)');
		/*$where='op.product_id in (SELECT wupt.product_id from weekly_user_product_target wupt join fy_week fw on wupt.fy_week_id=fw.fy_week_id  where wupt.status=1 and fw.fy_id='.$fy_year['fy_id'].' and wupt.user_id in('.$user_reportees.'))';
		$this->db->where($where);*/
		if($searchfilters['vtime']!='')
		{
			if($where1!='')
				$this->db->where($where1);
		}
		$user_reportees=$searchfilters['user_reportees_tvs'];
		$this->db->where('l.user_id in ('.$user_reportees.')');
	    if($searchfilters['regions']!='')
	    {
	    	$this->db->where('l4.location_id',$searchfilters['regions']);
	    }
	    $userProducts=$searchfilters['userProducts'];
		$userLocations=$searchfilters['userLocations'];
		$this->db->where('p.target',1);
		$this->db->where('cn.company_id',$this->session->userdata('company'));
	    $this->db->where('op.product_id IN ('.$userProducts.')');
		$this->db->where('l.location_id IN ('.$userLocations.') ');
	    $res=$this->db->get();
	    //echo $this->db->last_query();exit;
		return $res->row_array();
	    //print_r($kk);exit;
	}
public function get_open_opportunity($user_id, $role_id, $userProducts, $userLocations, $hotDay, $warmDate,$searchfilters)
	{
		$fy_year=get_custom_current_fiancial_year();
		if($searchfilters['vtime']!='')
		{
			$parameter="o.created_time"; 
			$where1=getCustomOppTimelineCheckPresent($searchfilters,$parameter,$fy_year);
		}

		if($searchfilters['measure']==1)
		{
			$this->db->select('sum(case when expected_order_conclusion <= "'.$hotDay.'" then o.required_quantity else 0 end) as Hot,
				sum(case when (expected_order_conclusion <= "'.$warmDate.'" AND expected_order_conclusion > "'.$hotDay.'") then o.required_quantity else 0 end) as Warm,
				sum(case when expected_order_conclusion > "'.$warmDate.'" then o.required_quantity else 0 end) as Cold');
	    }
	    elseif($searchfilters['measure']==2)
	    {
	    	$this->db->select('sum(case when expected_order_conclusion <= "'.$hotDay.'" then round((o.required_quantity*p.dp)/100000,2) else 0 end) as Hot,
				sum(case when (expected_order_conclusion <= "'.$warmDate.'" AND expected_order_conclusion > "'.$hotDay.'") then round((o.required_quantity*p.dp)/100000,2) else 0 end) as Warm,
				sum(case when expected_order_conclusion > "'.$warmDate.'" then round((o.required_quantity*p.dp)/100000,2) else 0 end) as Cold');
	    }
				$this->db->from('opportunity o');
				$this->db->join('opportunity_status_history osh', 'osh.opportunity_id = o.opportunity_id');
				$this->db->join('opportunity_product op','op.opportunity_id = o.opportunity_id');
				$this->db->join('product p','p.product_id = op.product_id');
				/*$this->db->join('weekly_user_product_target wupt','p.product_id=wupt.product_id and op.product_id=wupt.product_id');*/
				$this->db->join('lead l',' l.lead_id = o.lead_id');
				$this->db->join('location l1','l.location_id=l1.location_id');
				$this->db->join('location l2','l1.parent_id=l2.location_id');
				$this->db->join('location l3','l2.parent_id=l3.location_id');
				$this->db->join('location l4','l3.parent_id=l4.location_id');
				$this->db->where( 'o.status IN (1,2,3,4,5)');
				if($role_id==4||$role_id==5)
				{
					$this->db->where('l.user_id IN ('.$user_id.')');
				}
				else
				{
					$this->db->where('op.product_id IN ('.$userProducts.')');
		    		$this->db->where('l.location_id IN ('.$userLocations.') ');
		    	}
				/*$where='op.product_id in (SELECT wupt.product_id from weekly_user_product_target wupt join fy_week fw on wupt.fy_week_id=fw.fy_week_id  where wupt.status=1 and fw.fy_id='.$fy_year['fy_id'].' and wupt.user_id in('.$user_reportees.'))';
		$this->db->where($where);*/

		$this->db->where('o.company_id',$this->session->userdata('company'));
		$this->db->where('p.target',1);
		if($searchfilters['vtime']!='')
		{
			if($where1!='')
				$this->db->where($where1);
		}
		if($searchfilters['regions']!='')
	    {
	    	$this->db->where('l4.location_id',$searchfilters['regions']);
	    }
	    $st_date=$searchfilters['fy_dates']['end_date'];
		$this->db->where('osh.opportunity_status_id = (SELECT osh2.opportunity_status_id FROM opportunity_status_history osh2 
				WHERE osh2.opportunity_id = o.opportunity_id AND  DATE(osh2.created_time) <= "'.$st_date.'" ORDER BY osh2.created_time DESC LIMIT 1)');
		$r = $this->db->get();
		return $r->row_array();
		
	}
	public function get_user_assigned_target_category($searchfilters)
	{
		/*$user_id=$this->session->userdata('user_id');
		$fy_year=get_custom_current_fiancial_year();
		if($searchfilters['regions']!='')
	    {
	    	$search_by_region=report_user_locations($searchfilters);
		}
	    if($searchfilters['measure']==1)
		{
			$this->db->select('pc.name as category_name,pc.category_id,sum(wupt.quantity) as qty');
		}
		elseif($searchfilters['measure']==2)
		{
			$this->db->select('pc.name as category_name,pc.category_id,sum(wupt.quantity) as qty');
		}
		$this->db->from('custom_fy_week fw');
		$this->db->join('weekly_user_product_target wupt','fw.fy_week_id=wupt.fy_week_id');
		$this->db->join('product p','wupt.product_id=p.product_id','left');
		$this->db->join('product_group pg ','p.group_id=pg.group_id','left');
		$this->db->join('product_category pc','pg.category_id=pc.category_id','left');
		$this->db->where('fw.start_date >=',$fy_year['start_date']);
		$this->db->where('fw.start_date <=',$fy_year['end_date']);
		
		
		$user_reportees=$searchfilters['user_reportees_tvs'];
		$this->db->where('fw.fy_id',$fy_year['fy_id']);
	     if($searchfilters['regions']!='')
	    {
	    	if($search_by_region=='')
	    	{
	    		$search_by_region=0;
	    	}
		}
	    $this->db->group_by('pc.category_id');
		$res=$this->db->get();
		$result= $res->result_array();
		$ret=array();
		foreach($result as $row)
		{
			$ret[]=$row;
			
		}
		return $ret;*/
		$this->db->select('name as category_name,category_id');
		$this->db->from('product_category');
		$this->db->where('company_id',$this->session->userdata('company'));
		$this->db->where('status',1);
		$res=$this->db->get();
		return $res->result_array();

		
	}
	public function get_previous_target_by_category_table($searchfilters,$category_id)
	{
	    $user_id=$this->session->userdata('user_id');
		$fy_year=get_custom_current_fiancial_year();
		if($searchfilters['regions']!='')
	    {
	    	$search_by_region=report_user_locations($searchfilters);
			//$this->db->where('wupt.user_id in ('.$search_by_region.')');
	    }
		if($searchfilters['vtime']!='')
		{
			$parameter="fw.start_date"; 
			$where1=getCustomOppTimelineCheck($searchfilters,$parameter,$fy_year);
		}
		if($searchfilters['measure']==1)
		{
			$this->db->select('sum(wupt.quantity) as previous_target,pc.name as category_name,pc.category_id');
		}
		elseif($searchfilters['measure']==2)
		{
			$this->db->select('round(sum(p.mrp*wupt.quantity)/100000,2) as previous_target,pc.name as category_name,pc.category_id');
		}
		$this->db->from('custom_fy_week fw');
		$this->db->join('weekly_user_product_target wupt','fw.fy_week_id=wupt.fy_week_id');
		$this->db->join('product p','wupt.product_id=p.product_id');
		$this->db->join('product_group pg ','p.group_id=pg.group_id');
		$this->db->join('product_category pc','pg.category_id=pc.category_id');
		if($searchfilters['vtime']!='')
		{
			if($where1!='')
				$this->db->where($where1);
		}
		
		$user_reportees=$searchfilters['user_reportees_tvs'];
		$this->db->where('wupt.user_id in ('.$user_reportees.')');
		$this->db->where('fw.fy_id',$fy_year['fy_id']);
		$this->db->where('p.target',1);
	     if($searchfilters['regions']!='')
	    {
	    	//$search_by_region=report_user_locations($searchfilters);
	    	if($search_by_region=='')
	    	{
	    		$search_by_region=0;
	    	}
			$this->db->where('wupt.user_id in ('.$search_by_region.')');
	    }
	     if($category_id!='')
	    {
	    	$this->db->where('pg.category_id',$category_id);
	    	//$this->db->group_by('pg.group_id');
	    }
		$res=$this->db->get();
		$result= $res->row_array();
		if($result['previous_target']>0)
		{
			$ret = $result['previous_target'];
		}
		else
		{
			$ret = 0;
		}
		return $ret;
	}

	public function get_previous_target_by_segment_table($searchfilters,$category_id,$group_id,$product_id)
	{   
		//print_r($searchfilters);exit;
		$fy_year=get_custom_current_fiancial_year();
		if($searchfilters['vtime']!='')
		{
			$parameter="fw.start_date"; 
		   $where1=getCustomOppTimelineCheck($searchfilters,$parameter,$fy_year);
	   }
	   if($searchfilters['regions']!='')
	    {
	    	$search_by_region=report_user_locations($searchfilters);
			//$this->db->where('wupt.user_id in ('.$search_by_region.')');
	    }
		if($searchfilters['measure']==1)
		{
			$this->db->select('sum(wupt.quantity) as previous_target,pg.name as group_name,pg.group_id');
		}
		elseif($searchfilters['measure']==2)
		{
			$this->db->select('round(sum(p.mrp*wupt.quantity)/100000,2) as previous_target,pg.name as group_name,pg.group_id');
		}
		$this->db->from('custom_fy_week fw');
		$this->db->join('weekly_user_product_target wupt','fw.fy_week_id=wupt.fy_week_id');
		$this->db->join('product p','wupt.product_id=p.product_id');
		$this->db->join('product_group pg ','p.group_id=pg.group_id');
		if($searchfilters['vtime']!='')
		{
			if($where1!='')
				$this->db->where($where1);
		}
		
		$user_reportees=$searchfilters['user_reportees_tvs'];
		$this->db->where('wupt.user_id in ('.$user_reportees.')');
		$this->db->where('p.target',1);
	     if($searchfilters['regions']!='')
	    {
	    	if($search_by_region=='')
	    	{
	    		$search_by_region=0;
	    	}
			$this->db->where('wupt.user_id in ('.$search_by_region.')');
	    }
	    if($category_id!='')
	    {
	    	$this->db->where('pg.category_id',$category_id);
	    	//$this->db->group_by('pg.group_id');
	    }
	    else if($group_id!='')
	    {
	    	$this->db->where('pg.group_id',$group_id);
	    	//$this->db->group_by('p.product_id');
	    }
	    else if($product_id!='')
	    {
	    	$this->db->where('p.product_id',$product_id);	
	    }
	   $res=$this->db->get();
	// $this->db->last_query();exit;
		return $res->row_array();
	}
	public function user_assigned_product_list($searchfilters,$group_id)
	{
		/*$fy_year=get_custom_current_fiancial_year();
		if($searchfilters['regions']!='')
	    {
	    	$search_by_region=report_user_locations($searchfilters);
		}
		if($searchfilters['measure']==1)
		{
			$this->db->select('sum(wupt.quantity) as previous_target,p.name as product_name,p.product_id');
		}
		elseif($searchfilters['measure']==2)
		{
			$this->db->select('sum(wupt.quantity) as previous_target,p.name as product_name,p.product_id');
		}
		$this->db->from('custom_fy_week fw');
		$this->db->join('weekly_user_product_target wupt','fw.fy_week_id=wupt.fy_week_id');
		$this->db->join('product p','wupt.product_id=p.product_id');
		$this->db->where('fw.start_date >=',$fy_year['start_date']);
		$this->db->where('fw.start_date <=',$fy_year['end_date']);
		$user_reportees=$searchfilters['user_reportees_tvs'];
		$this->db->where('fw.fy_id',$fy_year['fy_id']);
	     if($searchfilters['regions']!='')
	    {
	    	if($search_by_region=='')
	    	{
	    		$search_by_region=0;
	    	}
		 }
	    $this->db->where('p.group_id',$group_id);
	    $this->db->group_by('p.product_id');
		$res=$this->db->get();
		return $res->result_array();*/
		$this->db->select('p.name as product_name,p.product_id');
		$this->db->from('product p');
		$this->db->where('p.group_id',$group_id);
		$this->db->where('p.status',1);
		$res=$this->db->get();
		return $res->result_array();
	}
	public function get_previous_target_by_product_table($searchfilters,$category_id,$group_id,$product_id,$ss='')
	{
		$fy_year=get_custom_current_fiancial_year();
		if($searchfilters['regions']!='')
	    {
	    	$search_by_region=report_user_locations($searchfilters);
			//$this->db->where('wupt.user_id in ('.$search_by_region.')');
	    }
		if($searchfilters['vtime']!='')
		{
			$parameter="fw.start_date"; 
			$where1=getCustomOppTimelineCheck($searchfilters,$parameter,$fy_year);
		}
		if($searchfilters['measure']==1)
		{
			$this->db->select('sum(wupt.quantity) as previous_target,p.name as product_name,p.product_id');
		}
		elseif($searchfilters['measure']==2)
		{
			$this->db->select('round(sum(p.mrp*wupt.quantity)/100000,2) as previous_target,p.name as product_name,p.product_id');
		}
		$this->db->from('custom_fy_week fw');
		$this->db->join('weekly_user_product_target wupt','fw.fy_week_id=wupt.fy_week_id');
		$this->db->join('product p','wupt.product_id=p.product_id');
		//$this->db->join('product_group pg ','p.group_id=pg.group_id');
		if($searchfilters['vtime']!='')
		{
			if($where1!='')
				$this->db->where($where1);
		}
		
		$user_reportees=$searchfilters['user_reportees_tvs'];
		$this->db->where('wupt.user_id in ('.$user_reportees.')');
	     if($searchfilters['regions']!='')
	    {
	    	if($search_by_region=='')
	    	{
	    		$search_by_region=0;
	    	}
			$this->db->where('wupt.user_id in ('.$search_by_region.')');
	    }
	    if($category_id!='')
	    {
	    	$this->db->where('pg.category_id',$category_id);
	    	$res=$this->db->get();
		    return $res->row_array();
	    	//$this->db->group_by('pg.group_id');
	    }
	    else if($group_id!='')
	    {
	    	$this->db->where('pg.group_id',$group_id);
	    	$res=$this->db->get();
		    return $res->row_array();
	    }
	    else if($product_id!='')
	    {   
	    	$this->db->where('p.group_id',$ss);
	    	$this->db->group_by('p.product_id');
	    	$res=$this->db->get();
		    return $res->result_array();	
	    }
	    
	}
	public function get_previous_sales_category($searchfilters,$category_id,$group_id,$product_id,$ss='')
	{
		$fy_year=get_custom_current_fiancial_year();
		if($searchfilters['vtime']!='')
		{
			$parameter="cn.created_time"; 
			$where1=getCustomOppTimelineCheck($searchfilters,$parameter,$fy_year);
		}
		if($searchfilters['measure']==1)
		{
			$this->db->select('sum(o.required_quantity) as previous_sales,p.product_id');
		}
		elseif($searchfilters['measure']==2)
		{
			$this->db->select('ROUND(SUM( CASE WHEN ma.margin_approval_id IS NULL THEN ((o.required_quantity*qd.total_value)*(1-qr.discount/100))/100000 ELSE ( CASE WHEN ma.discount_type = 1 THEN ((o.required_quantity*qd.total_value)*(1-ma.discount/100))/100000 ELSE ((o.required_quantity*qd.total_value)-(ma.discount*qd.currency_factor))/100000 END ) END ),2) AS previous_sales,p.product_id');
		}
		$this->db->from('contract_note cn');
		$this->db->join('contract_note_quote_revision cnqr','cn.contract_note_id=cnqr.contract_note_id');
		$this->db->join('quote_revision qr','cnqr.quote_revision_id=qr.quote_revision_id');
		$this->db->join('quote_details qd','qr.quote_id=qd.quote_id');
		$this->db->join('opportunity o','qd.opportunity_id=o.opportunity_id');
		$this->db->join('opportunity_product op','o.opportunity_id=op.opportunity_id');
		$this->db->join('product p','op.product_id=p.product_id');
		/*$this->db->join('weekly_user_product_target wupt','p.product_id=wupt.product_id and op.product_id=wupt.product_id');*/
		$this->db->join('product_group pg ','p.group_id=pg.group_id');
		$this->db->join('lead l','o.lead_id=l.lead_id');
		$this->db->join('location l1','l.location_id=l1.location_id');
		$this->db->join('location l2','l1.parent_id=l2.location_id');
		$this->db->join('location l3','l2.parent_id=l3.location_id');
		$this->db->join('location l4','l3.parent_id=l4.location_id');
		$this->db->join('quote_op_margin_approval ma','ma.quote_revision_id = qr.quote_revision_id AND o.opportunity_id = ma.opportunity_id','LEFT');
		$this->db->where('cn.status',2);
		/*$where='op.product_id in (SELECT wupt.product_id from weekly_user_product_target wupt join fy_week fw on wupt.fy_week_id=fw.fy_week_id  where wupt.status=1 and fw.fy_id='.$fy_year['fy_id'].' and wupt.user_id in('.$user_reportees.'))';
		$this->db->where($where);*/

		if($searchfilters['vtime']!='')
		{
			if($where1!='')
				$this->db->where($where1);
		}
		$user_reportees=$searchfilters['user_reportees_tvs'];
	    $this->db->where('l.user_id in ('.$user_reportees.')');
	    $userProducts=$searchfilters['userProducts'];
		$userLocations=$searchfilters['userLocations'];
		$this->db->where('cn.company_id',$this->session->userdata('company'));
	    $this->db->where('op.product_id IN ('.$userProducts.')');
		$this->db->where('l.location_id IN ('.$userLocations.') ');
		$this->db->where('p.target',1);
	     if($searchfilters['regions']!='')
	    {
	    	$this->db->where('l4.location_id',$searchfilters['regions']);
	    }
	    if($category_id!='')
	    {
	    	$this->db->where('pg.category_id',$category_id);
	    	$res=$this->db->get();
	       $result = $res->row_array();
			if($result['previous_sales']>0)
			{
				$ret = $result['previous_sales'];
			}
			else
			{
				$ret = 0;
			}
			return $ret;
	    }
	    else if($group_id!='')
	    {
	    	$this->db->where('pg.group_id',$group_id);
	    	$res=$this->db->get();
	        $result = $res->row_array();
			if($result['previous_sales']>0)
			{
				$ret = $result['previous_sales'];
			}
			else
			{
				$ret = 0;
			}
			return $ret;
	    }
	    else if($product_id!='')
	    {
	    	$this->db->where('p.group_id',$ss);
	    	$this->db->group_by('p.product_id');
	    	$res=$this->db->get();
	        return $res->result_array();
	    }
	   
	   
		
	}
	public function get_current_target_category($searchfilters,$category_id,$group_id,$product_id,$ss='')
	{
		
		$fy_year=get_custom_current_fiancial_year();
		 if($searchfilters['regions']!='')
	    {
	    	$search_by_region=report_user_locations($searchfilters);
			//$this->db->where('wupt.user_id in ('.$search_by_region.')');
	    }
		 if($searchfilters['vtime']!='')
		{
			$parameter="fw.start_date"; 
			$where1=getCustomReportTimelineCheck($searchfilters,$parameter,$fy_year);
		}
		if($searchfilters['measure']==1)
		{
			$this->db->select('sum(wupt.quantity) as current_target,p.product_id');
		}
		elseif($searchfilters['measure']==2)
		{
			$this->db->select('round(sum(p.mrp*wupt.quantity)/100000,2) as current_target,p.product_id');
		}
		$this->db->from('custom_fy_week fw');
		$this->db->join('weekly_user_product_target wupt','fw.fy_week_id=wupt.fy_week_id');
		$this->db->join('product p','wupt.product_id=p.product_id');
		$this->db->join('product_group pg ','p.group_id=pg.group_id');
		/*$this->db->where('fw.month_no',$month_no);
		$this->db->where('fw.year_no',$year_no);*/
		$user_reportees=$searchfilters['user_reportees_tvs'];
		$this->db->where('wupt.user_id in ('.$user_reportees.')');
		$this->db->where('p.target',1);
		if($searchfilters['vtime']!='')
		{
			if($where1!='')
				$this->db->where($where1);
		}
	   
	     if($searchfilters['regions']!='')
	    {
	    	//$search_by_region=report_user_locations($searchfilters);
	    	if($search_by_region=='')
	    	{
	    		$search_by_region=0;
	    	}
			$this->db->where('wupt.user_id in ('.$search_by_region.')');
	    }
	     if($category_id!='')
	    {
	    	$this->db->where('pg.category_id',$category_id);
	    	$res=$this->db->get();
		    $result = $res->row_array();
			if($result['current_target']>0)
			{
				$ret = $result['current_target'];
			}
			else
			{
				$ret = 0;
			}
			return $ret;
	    }
	     else if($group_id!='')
	    {
	    	$this->db->where('pg.group_id',$group_id);
	    	$res=$this->db->get();
		    $result = $res->row_array();
			if($result['current_target']>0)
			{
				$ret = $result['current_target'];
			}
			else
			{
				$ret = 0;
			}
			return $ret;
	    }
	    else if($product_id!='')
	    {
	    	$this->db->where('pg.group_id',$ss);
	    	$this->db->group_by('p.product_id');
	    	$res=$this->db->get();
	    	return $res->result_array();
	    }
	}

	public function get_current_sales_category($searchfilters,$category_id,$group_id,$product_id,$ss='')
	{
		$fy_year = get_custom_current_fiancial_year();
		 if($searchfilters['vtime']!='')
		{
			$parameter="cn.created_time"; 
			$where1=getCustomReportTimelineCheck($searchfilters,$parameter,$fy_year);
		}
		if($searchfilters['measure']==1)
		{
			$this->db->select('sum(o.required_quantity) as current_sales,p.product_id');
		}
		elseif($searchfilters['measure']==2)
		{
			$this->db->select('ROUND(SUM( CASE WHEN ma.margin_approval_id IS NULL THEN ((o.required_quantity*qd.total_value)*(1-qr.discount/100))/100000 ELSE ( CASE WHEN ma.discount_type = 1 THEN ((o.required_quantity*qd.total_value)*(1-ma.discount/100))/100000 ELSE ((o.required_quantity*qd.total_value)-(ma.discount*qd.currency_factor))/100000 END ) END ),2) AS current_sales,p.product_id');
		}
		$this->db->from('contract_note cn');
		$this->db->join('contract_note_quote_revision cnqr','cn.contract_note_id=cnqr.contract_note_id');
		$this->db->join('quote_revision qr','cnqr.quote_revision_id=qr.quote_revision_id');
		$this->db->join('quote_details qd','qr.quote_id=qd.quote_id');
		$this->db->join('opportunity o','qd.opportunity_id=o.opportunity_id');
		$this->db->join('opportunity_product op','o.opportunity_id=op.opportunity_id');
		$this->db->join('product p','op.product_id=p.product_id');
		/*$this->db->join('weekly_user_product_target wupt','p.product_id=wupt.product_id and op.product_id=wupt.product_id');*/
		$this->db->join('product_group pg ','p.group_id=pg.group_id');
		$this->db->join('lead l','o.lead_id=l.lead_id');
		$this->db->join('location l1','l.location_id=l1.location_id');
		$this->db->join('location l2','l1.parent_id=l2.location_id');
		$this->db->join('location l3','l2.parent_id=l3.location_id');
		$this->db->join('location l4','l3.parent_id=l4.location_id');
		$this->db->join('quote_op_margin_approval ma','ma.quote_revision_id = qr.quote_revision_id AND o.opportunity_id = ma.opportunity_id','LEFT');
		$this->db->where('cn.status',2);
		$this->db->where('cn.company_id',$this->session->userdata('company'));
		/*$where='op.product_id in (SELECT wupt.product_id from weekly_user_product_target wupt join fy_week fw on wupt.fy_week_id=fw.fy_week_id  where wupt.status=1 and fw.fy_id='.$fy_year['fy_id'].' and wupt.user_id in('.$user_reportees.'))';
		$this->db->where($where);*/
		$user_reportees=$searchfilters['user_reportees_tvs'];
		$this->db->where('l.user_id in ('.$user_reportees.')');
		$this->db->where('p.target',1);
	    if($searchfilters['regions']!='')
	    {
	    	$this->db->where('l4.location_id',$searchfilters['regions']);
	    }
	    if($searchfilters['vtime']!='')
		{
			if($where1!='')
				$this->db->where($where1);
		}
		$userProducts=$searchfilters['userProducts'];
	    $userLocations=$searchfilters['userLocations'];
	    $this->db->where('op.product_id IN ('.$userProducts.')');
		$this->db->where('l.location_id IN ('.$userLocations.') ');
	     if($category_id!='')
	    {
	    	$this->db->where('pg.category_id',$category_id);
	    	$res=$this->db->get();
		    $result = $res->row_array();
			if($result['current_sales']>0)
			{
				$ret = $result['current_sales'];
			}
			else
			{
				$ret = 0;
			}
			return $ret;
	    }
	    else if($group_id!='')
	    {
	    	$this->db->where('pg.group_id',$group_id);
	    	$res=$this->db->get();
		    $result = $res->row_array();
			if($result['current_sales']>0)
			{
				$ret = $result['current_sales'];
			}
			else
			{
				$ret = 0;
			}
			return $ret;
	    }
	    else if($product_id!='')
	    {
	    	$this->db->where('pg.group_id',$ss);
	    	$this->db->group_by('p.product_id');
	    	$res=$this->db->get();
		    return $res->result_array();
	    }
	    
		
		
	}
	public function get_open_orders_category($searchfilters,$category_id,$group_id,$product_id,$ss='')
	{
		$fy_year=get_custom_current_fiancial_year();
		if($searchfilters['vtime']!='')
		{
			$parameter="cn.created_time"; 
			$where1=getCustomReportTimelineCheck($searchfilters,$parameter,$fy_year);
		}
		if($searchfilters['measure']==1)
		{
			$this->db->select('sum(o.required_quantity) as open_orders,p.product_id');
		}
		elseif($searchfilters['measure']==2)
		{
			$this->db->select('ROUND(SUM( CASE WHEN ma.margin_approval_id IS NULL THEN ((o.required_quantity*qd.total_value)*(1-qr.discount/100))/100000 ELSE ( CASE WHEN ma.discount_type = 1 THEN ((o.required_quantity*qd.total_value)*(1-ma.discount/100))/100000 ELSE ((o.required_quantity*qd.total_value)-(ma.discount*qd.currency_factor))/100000 END ) END ),2) AS open_orders,p.product_id');
		}
		$this->db->from('contract_note cn');
		$this->db->join('contract_note_quote_revision cnqr','cn.contract_note_id=cnqr.contract_note_id');
		$this->db->join('quote_revision qr','cnqr.quote_revision_id=qr.quote_revision_id');
		$this->db->join('quote_details qd','qr.quote_id=qd.quote_id');
		$this->db->join('opportunity o','qd.opportunity_id=o.opportunity_id');
		$this->db->join('opportunity_product op','o.opportunity_id=op.opportunity_id');
		$this->db->join('product p','op.product_id=p.product_id');
/*		$this->db->join('weekly_user_product_target wupt','p.product_id=wupt.product_id and op.product_id=wupt.product_id');*/
		$this->db->join('product_group pg ','p.group_id=pg.group_id');
		$this->db->join('lead l','o.lead_id=l.lead_id');
		$this->db->join('location l1','l.location_id=l1.location_id');
		$this->db->join('location l2','l1.parent_id=l2.location_id');
		$this->db->join('location l3','l2.parent_id=l3.location_id');
		$this->db->join('location l4','l3.parent_id=l4.location_id');
		$this->db->join('quote_op_margin_approval ma','ma.quote_revision_id = qr.quote_revision_id AND o.opportunity_id = ma.opportunity_id','LEFT');
		$this->db->where('cn.status in (1,3)');
		$this->db->where('p.target',1);
		$this->db->where('cn.company_id',$this->session->userdata('company'));
		/*$where='op.product_id in (SELECT wupt.product_id from weekly_user_product_target wupt join fy_week fw on wupt.fy_week_id=fw.fy_week_id  where wupt.status=1 and fw.fy_id='.$fy_year['fy_id'].' and wupt.user_id in('.$user_reportees.'))';
		$this->db->where($where);*/

		if($searchfilters['vtime']!='')
		{
			if($where1!='')
				$this->db->where($where1);
		}
		
		$user_reportees=$searchfilters['user_reportees_tvs'];
		$this->db->where('l.user_id in ('.$user_reportees.')');
		$userProducts=$searchfilters['userProducts'];
	    $userLocations=$searchfilters['userLocations'];
	    $this->db->where('op.product_id IN ('.$userProducts.')');
		$this->db->where('l.location_id IN ('.$userLocations.') ');
		 if($searchfilters['regions']!='')
	    {
	    	$this->db->where('l4.location_id',$searchfilters['regions']);
	    }
	    if($category_id!='')
	    {
	    	$this->db->where('pg.category_id',$category_id);
	    	$res=$this->db->get();
		    $result = $res->row_array();
			if($result['open_orders']>0)
			{
				$ret = $result['open_orders'];
			}
			else
			{
				$ret = 0;
			}
			return $ret;
		}
	    else if($group_id!='')
	    {
	    	$this->db->where('pg.group_id',$group_id);
	    	$res=$this->db->get();
		    $result = $res->row_array();
			if($result['open_orders']>0)
			{
				$ret = $result['open_orders'];
			}
			else
			{
				$ret = 0;
			}
			return $ret;
		}
	    else if($product_id!='')
	    {
	    	$this->db->where('pg.group_id',$ss);
	    	$this->db->group_by('p.product_id');
	    	$res=$this->db->get();
	    	return $res->result_array();
	    }
	    		
	}
	public function get_open_opportunity_category($user_id, $role_id, $userProducts, $userLocations, $hotDay, $warmDate,$searchfilters,$category_id,$group_id,$product_id,$ss='')
	{
		$fy_year=get_custom_current_fiancial_year();
		if($searchfilters['vtime']!='')
		{
			$parameter="o.created_time"; 
			$where1=getCustomOppTimelineCheckPresent($searchfilters,$parameter,$fy_year);
		}
		if($searchfilters['measure']==1)
		{
			$this->db->select('sum(case when expected_order_conclusion <= "'.$hotDay.'" then o.required_quantity else 0 end) as Hot,
				sum(case when (expected_order_conclusion <= "'.$warmDate.'" AND expected_order_conclusion > "'.$hotDay.'") then o.required_quantity else 0 end) as Warm,
				sum(case when expected_order_conclusion > "'.$warmDate.'" then o.required_quantity else 0 end) as Cold,p.product_id');
	    }
	    elseif($searchfilters['measure']==2)
	    {
	    	$this->db->select('sum(case when expected_order_conclusion <= "'.$hotDay.'" then round((p.dp*o.required_quantity)/100000, 2) else 0 end) as Hot,
				sum(case when (expected_order_conclusion <= "'.$warmDate.'" AND expected_order_conclusion > "'.$hotDay.'") then round((p.dp*o.required_quantity)/100000, 2) else 0 end) as Warm,
				sum(case when expected_order_conclusion > "'.$warmDate.'" then round((p.dp*o.required_quantity)/100000, 2) else 0 end) as Cold,p.product_id');
	  }
				$this->db->from('opportunity o');
				$this->db->join('opportunity_status_history osh', 'osh.opportunity_id = o.opportunity_id');
				$this->db->join('opportunity_product op','op.opportunity_id = o.opportunity_id');
				$this->db->join('product p','p.product_id = op.product_id');
				/*$this->db->join('weekly_user_product_target wupt','p.product_id=wupt.product_id and op.product_id=wupt.product_id');*/
				$this->db->join('product_group pg ','p.group_id=pg.group_id');
				$this->db->join('lead l',' l.lead_id = o.lead_id');
				$this->db->join('location l1','l.location_id=l1.location_id');
				$this->db->join('location l2','l1.parent_id=l2.location_id');
				$this->db->join('location l3','l2.parent_id=l3.location_id');
				$this->db->join('location l4','l3.parent_id=l4.location_id');
				$this->db->where( 'o.status IN (1,2,3,4,5)');
				$this->db->where('p.target',1);
				if($role_id==4||$role_id==5)
				{
					$this->db->where('l.user_id IN ('.$user_id.')');
				}
				else
				{
					$this->db->where('op.product_id IN ('.$userProducts.')');
		    		$this->db->where('l.location_id IN ('.$userLocations.') ');
				}
				$this->db->where('o.company_id',$this->session->userdata('company'));
				/*$where='op.product_id in (SELECT wupt.product_id from weekly_user_product_target wupt join fy_week fw on wupt.fy_week_id=fw.fy_week_id  where wupt.status=1 and fw.fy_id='.$fy_year['fy_id'].' and wupt.user_id in('.$user_reportees.'))';
		$this->db->where($where);*/
		if($searchfilters['regions']!='')
	    {
	    	$this->db->where('l4.location_id',$searchfilters['regions']);
	    }
	    $st_date=$searchfilters['fy_dates']['end_date'];
		$this->db->where('osh.opportunity_status_id = (SELECT osh2.opportunity_status_id FROM opportunity_status_history osh2 
				WHERE osh2.opportunity_id = o.opportunity_id AND  DATE(osh2.created_time) <= "'.$st_date.'" ORDER BY osh2.created_time DESC LIMIT 1)');
		if($searchfilters['vtime']!='')
		{
			if($where1!='')
				$this->db->where($where1);
		}
		if($category_id!='')
	    {
	    	$this->db->where('pg.category_id',$category_id);
	    	$res=$this->db->get();
		    return $res->row_array();
	    }
	    else if($group_id!='')
	    {
	    	$this->db->where('pg.group_id',$group_id);
	    	$res=$this->db->get();
		    return $res->row_array();
	    }
	    else if($product_id!='')
	    {
	    	$this->db->where('pg.group_id',$ss);
	    	$this->db->group_by('p.product_id');
	    	$res=$this->db->get();
		    return $res->result_array();
	    }
	  	
	}
	public function get_open_opportunity_category_download($user_id, $role_id, $userProducts, $userLocations, $hotDay, $warmDate,$searchfilters)
	{
		$fy_year=get_custom_current_fiancial_year();
		if($searchfilters['vtime']!='')
		{
			$parameter="o.created_time"; 
			$where1=getCustomOppTimelineCheckPresent($searchfilters,$parameter,$fy_year);
		}
		if($searchfilters['measure']==1)
		{
			$this->db->select('p.product_id,sum(case when expected_order_conclusion <= "'.$hotDay.'" then o.required_quantity else 0 end) as Hot,
				sum(case when (expected_order_conclusion <= "'.$warmDate.'" AND expected_order_conclusion > "'.$hotDay.'") then o.required_quantity else 0 end) as Warm,
				sum(case when expected_order_conclusion > "'.$warmDate.'" then o.required_quantity else 0 end) as Cold');
	    }
	    elseif($searchfilters['measure']==2)
	    {
	    	$this->db->select('p.product_id,sum(case when expected_order_conclusion <= "'.$hotDay.'" then round((p.dp*o.required_quantity)/100000, 2) else 0 end) as Hot,
				sum(case when (expected_order_conclusion <= "'.$warmDate.'" AND expected_order_conclusion > "'.$hotDay.'") then round((p.dp*o.required_quantity)/100000, 2) else 0 end) as Warm,
				sum(case when expected_order_conclusion > "'.$warmDate.'" then round((p.dp*o.required_quantity)/100000, 2) else 0 end) as Cold');
	  }
				$this->db->from('opportunity o');
				$this->db->join('opportunity_status_history osh', 'osh.opportunity_id = o.opportunity_id');
				$this->db->join('opportunity_product op','op.opportunity_id = o.opportunity_id');
				$this->db->join('product p','p.product_id = op.product_id');
				/*$this->db->join('weekly_user_product_target wupt','p.product_id=wupt.product_id and op.product_id=wupt.product_id');*/
				$this->db->join('product_group pg ','p.group_id=pg.group_id');
				$this->db->join('lead l',' l.lead_id = o.lead_id');
				$this->db->join('location l1','l.location_id=l1.location_id');
				$this->db->join('location l2','l1.parent_id=l2.location_id');
				$this->db->join('location l3','l2.parent_id=l3.location_id');
				$this->db->join('location l4','l3.parent_id=l4.location_id');
				$this->db->where( 'o.status IN (1,2,3,4,5)');
				$this->db->where('p.target',1);
				$this->db->where('o.company_id',$this->session->userdata('company'));
				if($role_id==4||$role_id==5)
				{
					$this->db->where('l.user_id IN ('.$user_id.')');
				}
				else
				{
					$this->db->where('op.product_id IN ('.$userProducts.')');
		    		$this->db->where('l.location_id IN ('.$userLocations.') ');
		    	}
				/*$where='op.product_id in (SELECT wupt.product_id from weekly_user_product_target wupt join fy_week fw on wupt.fy_week_id=fw.fy_week_id  where wupt.status=1 and fw.fy_id='.$fy_year['fy_id'].' and wupt.user_id in('.$user_reportees.'))';
		$this->db->where($where);*/
		
		if($searchfilters['vtime']!='')
		{
			if($where1!='')
				$this->db->where($where1);
		}
		if($searchfilters['regions']!='')
	    {
	    	$this->db->where('l4.location_id',$searchfilters['regions']);
	    }
	  //  $this->db->where('l.user_id IN ('.$user_id.')');
	    $st_date=$searchfilters['fy_dates']['end_date'];
		$this->db->where('osh.opportunity_status_id = (SELECT osh2.opportunity_status_id FROM opportunity_status_history osh2 
				WHERE osh2.opportunity_id = o.opportunity_id AND  DATE(osh2.created_time) <= "'.$st_date.'" ORDER BY osh2.created_time DESC LIMIT 1)');
		$this->db->group_by('p.product_id');
		$res=$this->db->get();
		//echo $this->db->last_query();exit;
		return $res->result_array();
	}
	public function get_user_reportees($user_reportees)
	{
		$this->db->select('*');
		$this->db->from('user');
		$this->db->where('status',1);
		$this->db->where('user_id in ('.$user_reportees.')');
		$this->db->where('company_id',$this->session->userdata('company'));
		$res=$this->db->get();
		return $res->result_array();
	}
	public function get_user_assigned_segment_list($searchfilters,$category_id)
	{   
		/*$fy_year=get_custom_current_fiancial_year();
		if($searchfilters['regions']!='')
	    {
	    	$search_by_region=report_user_locations($searchfilters);
			
	    }
		if($searchfilters['measure']==1)
		{
			$this->db->select('sum(wupt.quantity) as previous_target,pg.name as group_name,pg.group_id');
		}
		elseif($searchfilters['measure']==2)
		{
			$this->db->select('sum(wupt.quantity) as previous_target,pg.name as group_name,pg.group_id');
		}
		$this->db->from('custom_fy_week fw');
		$this->db->join('weekly_user_product_target wupt','fw.fy_week_id=wupt.fy_week_id');
		$this->db->join('product p','wupt.product_id=p.product_id');
		$this->db->join('product_group pg ','p.group_id=pg.group_id');
		$this->db->where('fw.start_date >=',$fy_year['start_date']);
		$this->db->where('fw.start_date <=',$fy_year['end_date']);
		$user_reportees=$searchfilters['user_reportees_tvs'];
		$this->db->where('fw.fy_id',$fy_year['fy_id']);
	     if($searchfilters['regions']!='')
	    {
	    	if($search_by_region=='')
	    	{
	    		$search_by_region=0;
	    	}
		}
	    $this->db->where('pg.category_id',$category_id);
	  	$this->db->group_by('pg.group_id');
		$res=$this->db->get();
		$result= $res->result_array();
	    $ret=array();
	    foreach($result as $row)
		{
			$ret[]=$row;
			
		}
		return $ret;*/
		$this->db->select('name as group_name,group_id');
		$this->db->from('product_group');
		$this->db->where('category_id',$category_id);
		$this->db->where('status',1);
		$res=$this->db->get();
		return $res->result_array();
	}
	public function user_assigned_product_list_download($searchfilters)
	{
		/*$fy_year=get_custom_current_fiancial_year();
		if($searchfilters['regions']!='')
	    {
	    	$search_by_region=report_user_locations($searchfilters);
		}
		if($searchfilters['measure']==1)
		{
			$this->db->select('sum(wupt.quantity) as previous_target,(case when p.name2 !="" then p.name2 else p.name end ) as product_name,p.product_id,pg.name as segment_name');
		}
		elseif($searchfilters['measure']==2)
		{
			$this->db->select('sum(wupt.quantity) as previous_target(case when p.name2 !="" then p.name2 else p.name end ) as product_name,p.product_id,pg.name as segment_name');
		}
		$this->db->from('custom_fy_week fw');
		$this->db->join('weekly_user_product_target wupt','fw.fy_week_id=wupt.fy_week_id');
		$this->db->join('product p','wupt.product_id=p.product_id');
		$this->db->join('product_group pg ','p.group_id=pg.group_id');
		$this->db->where('fw.start_date >=',$fy_year['start_date']);
		$this->db->where('fw.start_date <=',$fy_year['end_date']);
		$user_reportees=$searchfilters['user_reportees_tvs'];
		$this->db->where('fw.fy_id',$fy_year['fy_id']);
	     if($searchfilters['regions']!='')
	    {
	    	if($search_by_region=='')
	    	{
	    		$search_by_region=0;
	    	}
	    }
	    $this->db->group_by('p.product_id');
		$res=$this->db->get();
		return $res->result_array();*/
		$this->db->select('p.name as product_name,p.product_id');
		$this->db->from('product p');
		$this->db->where('p.status',1);
		$this->db->where('p.target',1);
		$this->db->where('p.company_id',$this->session->userdata('company'));
		$res=$this->db->get();
		return $res->result_array();
	}
	public function get_previous_target_by_product_table_download($searchfilters)
	{
		$fy_year=get_custom_current_fiancial_year();
		if($searchfilters['regions']!='')
	    {
	    	$search_by_region=report_user_locations($searchfilters);
			//$this->db->where('wupt.user_id in ('.$search_by_region.')');
	    }
		if($searchfilters['vtime']!='')
		{
			$parameter="fw.start_date"; 
			$where1=getCustomOppTimelineCheck($searchfilters,$parameter,$fy_year);
		}
		if($searchfilters['measure']==1)
		{
			$this->db->select('sum(wupt.quantity) as previous_target,p.name as product_name,p.product_id,pg.name as segment_name');
		}
		elseif($searchfilters['measure']==2)
		{
			$this->db->select('round(sum(p.mrp*wupt.quantity)/100000,2) as previous_target,p.name as product_name,p.product_id,,pg.name as segment_name');
		}
		$this->db->from('custom_fy_week fw');
		$this->db->join('weekly_user_product_target wupt','fw.fy_week_id=wupt.fy_week_id');
		$this->db->join('product p','wupt.product_id=p.product_id');
		$this->db->join('product_group pg ','p.group_id=pg.group_id');
		if($searchfilters['vtime']!='')
		{
			if($where1!='')
				$this->db->where($where1);
		}
		
		$user_reportees=$searchfilters['user_reportees_tvs'];
		$this->db->where('wupt.user_id in ('.$user_reportees.')');
		$this->db->where('p.target',1);
	     if($searchfilters['regions']!='')
	    {
	    	if($search_by_region=='')
	    	{
	    		$search_by_region=0;
	    	}
			$this->db->where('wupt.user_id in ('.$search_by_region.')');
	    }
	   $this->db->group_by('p.product_id');
	   $res=$this->db->get();
		//echo $this->db->last_query();exit;
		return $res->result_array();
	}
	public function get_previous_sales_category_download($searchfilters)
	{
		$fy_year=get_custom_current_fiancial_year();
		if($searchfilters['vtime']!='')
		{
			$parameter="cn.created_time"; 
			$where1=getCustomOppTimelineCheck($searchfilters,$parameter,$fy_year);
		}
		if($searchfilters['measure']==1)
		{
			$this->db->select('sum(o.required_quantity) as previous_sales,p.product_id,(case when p.name2 !="" then p.name2 else p.name end ) as product_name,pg.name as segment_name');
		}
		elseif($searchfilters['measure']==2)
		{
			$this->db->select('ROUND(SUM( CASE WHEN ma.margin_approval_id IS NULL THEN ((o.required_quantity*qd.total_value)*(1-qr.discount/100))/100000 ELSE ( CASE WHEN ma.discount_type = 1 THEN ((o.required_quantity*qd.total_value)*(1-ma.discount/100))/100000 ELSE ((o.required_quantity*qd.total_value)-(ma.discount*qd.currency_factor))/100000 END ) END ),2) AS previous_sales,p.product_id');
		}
		$this->db->from('contract_note cn');
		$this->db->join('contract_note_quote_revision cnqr','cn.contract_note_id=cnqr.contract_note_id');
		$this->db->join('quote_revision qr','cnqr.quote_revision_id=qr.quote_revision_id');
		$this->db->join('quote_details qd','qr.quote_id=qd.quote_id');
		$this->db->join('opportunity o','qd.opportunity_id=o.opportunity_id');
		$this->db->join('opportunity_product op','o.opportunity_id=op.opportunity_id');
		$this->db->join('product p','op.product_id=p.product_id');
		/*$this->db->join('weekly_user_product_target wupt','p.product_id=wupt.product_id and op.product_id=wupt.product_id');*/
		$this->db->join('product_group pg ','p.group_id=pg.group_id');
		$this->db->join('lead l','o.lead_id=l.lead_id');
		$this->db->join('location l1','l.location_id=l1.location_id');
		$this->db->join('location l2','l1.parent_id=l2.location_id');
		$this->db->join('location l3','l2.parent_id=l3.location_id');
		$this->db->join('location l4','l3.parent_id=l4.location_id');
		$this->db->join('quote_op_margin_approval ma','ma.quote_revision_id = qr.quote_revision_id AND o.opportunity_id = ma.opportunity_id','LEFT');
		$this->db->where('cn.status',2);
		$this->db->where('cn.company_id',$this->session->userdata('company'));
		$this->db->where('p.target',1);
		/*$where='op.product_id in (SELECT wupt.product_id from weekly_user_product_target wupt join fy_week fw on wupt.fy_week_id=fw.fy_week_id  where wupt.status=1 and fw.fy_id='.$fy_year['fy_id'].' and wupt.user_id in('.$user_reportees.'))';
		$this->db->where($where);*/

		if($searchfilters['vtime']!='')
		{
			if($where1!='')
				$this->db->where($where1);
		}
		$user_reportees=$searchfilters['user_reportees_tvs'];
	    $this->db->where('l.user_id in ('.$user_reportees.')');
	     if($searchfilters['regions']!='')
	    {
	    	$this->db->where('l4.location_id',$searchfilters['regions']);
	    }
	   
	    $userProducts=$searchfilters['userProducts'];
	    $userLocations=$searchfilters['userLocations'];
	    $this->db->where('op.product_id IN ('.$userProducts.')');
		$this->db->where('l.location_id IN ('.$userLocations.') ');
		$this->db->group_by('p.product_id');
	    $res=$this->db->get();
	    return $res->result_array();
	}
	public function get_current_target_category_download($searchfilters)
	{
		
		$fy_year=get_custom_current_fiancial_year();
		 if($searchfilters['regions']!='')
	    {
	    	$search_by_region=report_user_locations($searchfilters);
			//$this->db->where('wupt.user_id in ('.$search_by_region.')');
	    }
		 if($searchfilters['vtime']!='')
		{
			$parameter="fw.start_date"; 
			$where1=getCustomReportTimelineCheck($searchfilters,$parameter,$fy_year);
		}
		if($searchfilters['measure']==1)
		{
			$this->db->select('sum(wupt.quantity) as current_target,p.product_id');
		}
		elseif($searchfilters['measure']==2)
		{
			$this->db->select('round(sum(p.mrp*wupt.quantity)/100000,2) as current_target,p.product_id');
		}
		$this->db->from('custom_fy_week fw');
		$this->db->join('weekly_user_product_target wupt','fw.fy_week_id=wupt.fy_week_id');
		$this->db->join('product p','wupt.product_id=p.product_id');
		$this->db->join('product_group pg ','p.group_id=pg.group_id');
		/*$this->db->where('fw.month_no',$month_no);
		$this->db->where('fw.year_no',$year_no);*/
		$user_reportees=$searchfilters['user_reportees_tvs'];
		$this->db->where('wupt.user_id in ('.$user_reportees.')');
		$this->db->where('p.target',1);
		if($searchfilters['vtime']!='')
		{
			if($where1!='')
				$this->db->where($where1);
		}
	   
	     if($searchfilters['regions']!='')
	    {
	    	//$search_by_region=report_user_locations($searchfilters);
	    	if($search_by_region=='')
	    	{
	    		$search_by_region=0;
	    	}
			$this->db->where('wupt.user_id in ('.$search_by_region.')');
	    }
	    $this->db->group_by('p.product_id');
		$res=$this->db->get();
		return $res->result_array();
	}
	public function get_current_sales_category_download($searchfilters)
	{
		$fy_year=get_custom_current_fiancial_year();
		 if($searchfilters['vtime']!='')
		{
			$parameter="cn.created_time"; 
			$where1=getCustomReportTimelineCheck($searchfilters,$parameter,$fy_year);
		}
		if($searchfilters['measure']==1)
		{
			$this->db->select('sum(o.required_quantity) as current_sales,p.product_id');
		}
		elseif($searchfilters['measure']==2)
		{
			$this->db->select('ROUND(SUM( CASE WHEN ma.margin_approval_id IS NULL THEN ((o.required_quantity*qd.total_value)*(1-qr.discount/100))/100000 ELSE ( CASE WHEN ma.discount_type = 1 THEN ((o.required_quantity*qd.total_value)*(1-ma.discount/100))/100000 ELSE ((o.required_quantity*qd.total_value)-(ma.discount*qd.currency_factor))/100000 END ) END ),2) AS current_sales,p.product_id');
		}
		$this->db->from('contract_note cn');
		$this->db->join('contract_note_quote_revision cnqr','cn.contract_note_id=cnqr.contract_note_id');
		$this->db->join('quote_revision qr','cnqr.quote_revision_id=qr.quote_revision_id');
		$this->db->join('quote_details qd','qr.quote_id=qd.quote_id');
		$this->db->join('opportunity o','qd.opportunity_id=o.opportunity_id');
		$this->db->join('opportunity_product op','o.opportunity_id=op.opportunity_id');
		$this->db->join('product p','op.product_id=p.product_id');
		/*$this->db->join('weekly_user_product_target wupt','p.product_id=wupt.product_id and op.product_id=wupt.product_id');*/
		$this->db->join('product_group pg ','p.group_id=pg.group_id');
		$this->db->join('lead l','o.lead_id=l.lead_id');
		$this->db->join('location l1','l.location_id=l1.location_id');
		$this->db->join('location l2','l1.parent_id=l2.location_id');
		$this->db->join('location l3','l2.parent_id=l3.location_id');
		$this->db->join('location l4','l3.parent_id=l4.location_id');
		$this->db->join('quote_op_margin_approval ma','ma.quote_revision_id = qr.quote_revision_id AND o.opportunity_id = ma.opportunity_id','LEFT');
		$this->db->where('cn.status',2);
		$this->db->where('cn.company_id',$this->session->userdata('company'));
		/*$where='op.product_id in (SELECT wupt.product_id from weekly_user_product_target wupt join fy_week fw on wupt.fy_week_id=fw.fy_week_id  where wupt.status=1 and fw.fy_id='.$fy_year['fy_id'].' and wupt.user_id in('.$user_reportees.'))';
		$this->db->where($where);*/
		$user_reportees=$searchfilters['user_reportees_tvs'];
	    $this->db->where('l.user_id in ('.$user_reportees.')');
	   
	     if($searchfilters['regions']!='')
	    {
	    	$this->db->where('l4.location_id',$searchfilters['regions']);
	    }
	    if($searchfilters['vtime']!='')
		{
			if($where1!='')
				$this->db->where($where1);
		}
		$userProducts=$searchfilters['userProducts'];
		$userLocations=$searchfilters['userLocations'];
		$this->db->where('p.target',1);
	    $this->db->where('op.product_id IN ('.$userProducts.')');
		$this->db->where('l.location_id IN ('.$userLocations.') ');
		$this->db->group_by('p.product_id');
		$res=$this->db->get();
		return $res->result_array();
	}
	public function get_open_orders_category_download($searchfilters)
	{
		$fy_year=get_custom_current_fiancial_year();
		if($searchfilters['vtime']!='')
		{
			$parameter="cn.created_time"; 
			$where1=getCustomReportTimelineCheck($searchfilters,$parameter,$fy_year);
		}
		if($searchfilters['measure']==1)
		{
			$this->db->select('sum(o.required_quantity) as open_orders,p.product_id');
		}
		elseif($searchfilters['measure']==2)
		{
			$this->db->select('ROUND(SUM( CASE WHEN ma.margin_approval_id IS NULL THEN ((o.required_quantity*qd.total_value)*(1-qr.discount/100))/100000 ELSE ( CASE WHEN ma.discount_type = 1 THEN ((o.required_quantity*qd.total_value)*(1-ma.discount/100))/100000 ELSE ((o.required_quantity*qd.total_value)-(ma.discount*qd.currency_factor))/100000 END ) END ),2) AS open_orders,p.product_id');
		}
		$this->db->from('contract_note cn');
		$this->db->join('contract_note_quote_revision cnqr','cn.contract_note_id=cnqr.contract_note_id');
		$this->db->join('quote_revision qr','cnqr.quote_revision_id=qr.quote_revision_id');
		$this->db->join('quote_details qd','qr.quote_id=qd.quote_id');
		$this->db->join('opportunity o','qd.opportunity_id=o.opportunity_id');
		$this->db->join('opportunity_product op','o.opportunity_id=op.opportunity_id');
		$this->db->join('product p','op.product_id=p.product_id');
/*		$this->db->join('weekly_user_product_target wupt','p.product_id=wupt.product_id and op.product_id=wupt.product_id');*/
		$this->db->join('product_group pg ','p.group_id=pg.group_id');
		$this->db->join('lead l','o.lead_id=l.lead_id');
		$this->db->join('location l1','l.location_id=l1.location_id');
		$this->db->join('location l2','l1.parent_id=l2.location_id');
		$this->db->join('location l3','l2.parent_id=l3.location_id');
		$this->db->join('location l4','l3.parent_id=l4.location_id');
		$this->db->join('quote_op_margin_approval ma','ma.quote_revision_id = qr.quote_revision_id AND o.opportunity_id = ma.opportunity_id','LEFT');
		$this->db->where('cn.status in (1,3)');
		$this->db->where('p.target',1);
		$this->db->where('cn.company_id',$this->session->userdata('company'));
		/*$where='op.product_id in (SELECT wupt.product_id from weekly_user_product_target wupt join fy_week fw on wupt.fy_week_id=fw.fy_week_id  where wupt.status=1 and fw.fy_id='.$fy_year['fy_id'].' and wupt.user_id in('.$user_reportees.'))';
		$this->db->where($where);*/

		if($searchfilters['vtime']!='')
		{
			if($where1!='')
				$this->db->where($where1);
		}
		
		$user_reportees=$searchfilters['user_reportees_tvs'];
		$this->db->where('l.user_id in ('.$user_reportees.')');
	     if($searchfilters['regions']!='')
	    {
	    	$this->db->where('l4.location_id',$searchfilters['regions']);
	    }
	   
	    $userProducts=$searchfilters['userProducts'];
	    $userLocations=$searchfilters['userLocations'];
	    $this->db->where('op.product_id IN ('.$userProducts.')');
		$this->db->where('l.location_id IN ('.$userLocations.') ');
		$this->db->group_by('p.product_id');
		$res=$this->db->get();
		return $res->result_array();
	}
	public function user_targets_per_region($searchfilters,$region_users)
	{   
		//echo $region_users;exit;
		$fy_year=get_custom_current_fiancial_year();
		if($searchfilters['regions']!='')
	    {
	    	$search_by_region=report_user_locations($searchfilters);
			//$this->db->where('wupt.user_id in ('.$search_by_region.')');
	    }
		
		if($searchfilters['measure']==1)
		{
			$this->db->select('sum(wupt.quantity) as pt,');
		}
		elseif($searchfilters['measure']==2)
		{
			$this->db->select('round(sum(p.mrp*wupt.quantity)/100000,2) as pt');
		}
		$this->db->from('custom_fy_week fw');
		$this->db->join('weekly_user_product_target wupt','fw.fy_week_id=wupt.fy_week_id');
		$this->db->join('product p','wupt.product_id=p.product_id');
		$this->db->where('fw.start_date >=',$fy_year['start_date']);
		$this->db->where('fw.start_date <=',$fy_year['end_date']);
		$this->db->where('p.target',1);
		$user_reportees=$searchfilters['user_reportees_tvs'];
		//$this->db->where('wupt.user_id in ('.$user_reportees.')');
		$this->db->where('fw.fy_id',$fy_year['fy_id']);
	     if($searchfilters['regions']!='')
	    {
	    	if($search_by_region=='')
	    	{
	    		$search_by_region=0;
	    	}
			//$this->db->where('wupt.user_id in ('.$search_by_region.')');
	    }
	    if($region_users=='')
	    {
	  	   $region_users=0;
	  	}
	   // $this->db->where('wupt.user_id in ('.$region_users.')');
	    $res=$this->db->get();
        return $res->row_array();
		
	}
	public function get_current_target_by_region($searchfilters,$region_users)
	{
		$fy_year=get_custom_current_fiancial_year();
		if($searchfilters['regions']!='')
	    {
	    	$search_by_region=report_user_locations($searchfilters);
			//$this->db->where('wupt.user_id in ('.$search_by_region.')');
	    }
	    if($searchfilters['vtime']!='')
		{
			$parameter="fw.start_date"; 
			$where1=getCustomReportTimelineCheck($searchfilters,$parameter,$fy_year);
		}
		if($searchfilters['measure']==1)
		{
			$this->db->select('sum(wupt.quantity) as current_target');
		}
		elseif($searchfilters['measure']==2)
		{
			$this->db->select('round(sum(p.mrp*wupt.quantity)/100000,2) as current_target');
		}
		$this->db->from('custom_fy_week fw');
		$this->db->join('weekly_user_product_target wupt','fw.fy_week_id=wupt.fy_week_id');
		$this->db->join('product p','wupt.product_id=p.product_id');
		$user_reportees=$searchfilters['user_reportees_tvs'];
	    $this->db->where('wupt.user_id in ('.$user_reportees.')');
	    if($searchfilters['regions']!='')
	    {
	    	//$search_by_region=report_user_locations($searchfilters);
	    	if($search_by_region=='')
	    	{
	    		$search_by_region=0;
	    	}
			$this->db->where('wupt.user_id in ('.$search_by_region.')');
	    }
	    if($region_users=='')
	    {
	      $region_users=0;
	    }
		$this->db->where('wupt.user_id in ('.$region_users.')');
		$this->db->where('p.target',1);
	    $res=$this->db->get();
		//echo $this->db->last_query();
		return $res->row_array();
	//	print_r($kk);exit;
	}
	public function get_previous_target_by_region($searchfilters,$region_users)
	{   
		//echo $region_users;exit;
		$fy_year=get_custom_current_fiancial_year();
		if($searchfilters['vtime']!='')
		{
			$parameter="fw.start_date"; 
		   $where1=getCustomOppTimelineCheck($searchfilters,$parameter,$fy_year);
	   }
	   if($searchfilters['regions']!='')
	    {
	    	$search_by_region=report_user_locations($searchfilters);
			//$this->db->where('wupt.user_id in ('.$search_by_region.')');
	    }
		if($searchfilters['measure']==1)
		{
			$this->db->select('sum(wupt.quantity) as previous_target,');
		}
		elseif($searchfilters['measure']==2)
		{
			$this->db->select('round(sum(p.mrp*wupt.quantity)/100000,2) as previous_target');
		}
		$this->db->from('custom_fy_week fw');
		$this->db->join('weekly_user_product_target wupt','fw.fy_week_id=wupt.fy_week_id');
		$this->db->join('product p','wupt.product_id=p.product_id');
		
		if($searchfilters['vtime']!='')
		{
			if($where1!='')
				$this->db->where($where1);
		}
		$user_reportees=$searchfilters['user_reportees_tvs'];
		$this->db->where('wupt.user_id in ('.$user_reportees.')');
		$this->db->where('p.target',1);
		if($searchfilters['regions']!='')
	    {
	    	//$search_by_region=report_user_locations($searchfilters);
	    	if($search_by_region=='')
	    	{
	    		$search_by_region=0;
	    	}
			$this->db->where('wupt.user_id in ('.$search_by_region.')');
	    }
	    if($region_users=='')
	    {
	    	$region_users=0;
	    }
	    $this->db->where('wupt.user_id in ('.$region_users.')');
	    
		$res=$this->db->get();
		//echo $this->db->last_query();exit;
		return $res->row_array();
		
	}
	public function get_previous_sales_by_region($searchfilters)
	{
		$fy_year=get_custom_current_fiancial_year();
		if($searchfilters['vtime']!='')
		{
			$parameter="cn.created_time"; 
			$where1=getCustomOppTimelineCheck($searchfilters,$parameter,$fy_year);
		}
		if($searchfilters['measure']==1)
		{
			$this->db->select('sum(o.required_quantity) as previous_sales,l4.location,l4.location_id');
		}
		elseif($searchfilters['measure']==2)
		{
			$this->db->select('ROUND(SUM( CASE WHEN ma.margin_approval_id IS NULL THEN ((o.required_quantity*qd.total_value)*(1-qr.discount/100))/100000 ELSE ( CASE WHEN ma.discount_type = 1 THEN ((o.required_quantity*qd.total_value)*(1-ma.discount/100))/100000 ELSE ((o.required_quantity*qd.total_value)-(ma.discount*qd.currency_factor))/100000 END ) END ),2) AS previous_sales,l4.location,l4.location_id');
		}
		$this->db->from('contract_note cn');
		$this->db->join('contract_note_quote_revision cnqr','cn.contract_note_id=cnqr.contract_note_id');
		$this->db->join('quote_revision qr','cnqr.quote_revision_id=qr.quote_revision_id');
		$this->db->join('quote_details qd','qr.quote_id=qd.quote_id');
		$this->db->join('opportunity o','qd.opportunity_id=o.opportunity_id');
		$this->db->join('opportunity_product op','o.opportunity_id=op.opportunity_id');
		$this->db->join('product p','op.product_id=p.product_id');
		/*$this->db->join('weekly_user_product_target wupt','p.product_id=wupt.product_id and op.product_id=wupt.product_id');*/
		$this->db->join('lead l','o.lead_id=l.lead_id');
		$this->db->join('location l1','l.location_id=l1.location_id');
		$this->db->join('location l2','l1.parent_id=l2.location_id');
		$this->db->join('location l3','l2.parent_id=l3.location_id');
		$this->db->join('location l4','l3.parent_id=l4.location_id');
		$this->db->join('quote_op_margin_approval ma','ma.quote_revision_id = qr.quote_revision_id AND o.opportunity_id = ma.opportunity_id','LEFT');
		$this->db->where('cn.status',2);
		$this->db->where('p.target',1);
		$this->db->where('cn.company_id',$this->session->userdata('company'));
		/*$where='op.product_id in (SELECT wupt.product_id from weekly_user_product_target wupt join fy_week fw on wupt.fy_week_id=fw.fy_week_id  where wupt.status=1 and fw.fy_id='.$fy_year['fy_id'].' and wupt.user_id in('.$user_reportees.'))';
		$this->db->where($where);*/
		if($searchfilters['vtime']!='')
		{
			if($where1!='')
				$this->db->where($where1);
		}
		$user_reportees=$searchfilters['user_reportees_tvs'];
		$this->db->where('l.user_id in ('.$user_reportees.')');
	    if($searchfilters['regions']!='')
	    {
	    	$this->db->where('l4.location_id',$searchfilters['regions']);
	    }
	    $userProducts=$searchfilters['userProducts'];
	    $userLocations=$searchfilters['userLocations'];
	    $this->db->where('op.product_id IN ('.$userProducts.')');
		$this->db->where('l.location_id IN ('.$userLocations.') ');
	    $this->db->group_by('l4.location_id');
	    $res=$this->db->get();
		//echo $this->db->last_query();
		return $res->result_array();
	    //print_r($kk);exit;
	}
	public function get_current_sales_by_region($searchfilters)
	{
		$fy_year=get_custom_current_fiancial_year();
		 if($searchfilters['vtime']!='')
		{
			$parameter="cn.created_time"; 
			$where1=getCustomReportTimelineCheck($searchfilters,$parameter,$fy_year);
		}
		if($searchfilters['measure']==1)
		{
			$this->db->select('sum(o.required_quantity) as current_sales,l4.location,l4.location_id');
		}
		elseif($searchfilters['measure']==2)
		{
			$this->db->select('ROUND(SUM( CASE WHEN ma.margin_approval_id IS NULL THEN ((o.required_quantity*qd.total_value)*(1-qr.discount/100))/100000 ELSE ( CASE WHEN ma.discount_type = 1 THEN ((o.required_quantity*qd.total_value)*(1-ma.discount/100))/100000 ELSE ((o.required_quantity*qd.total_value)-(ma.discount*qd.currency_factor))/100000 END ) END ),2) AS current_sales,l4.location,l4.location_id');
		}
		$this->db->from('contract_note cn');
		$this->db->join('contract_note_quote_revision cnqr','cn.contract_note_id=cnqr.contract_note_id');
		$this->db->join('quote_revision qr','cnqr.quote_revision_id=qr.quote_revision_id');
		$this->db->join('quote_details qd','qr.quote_id=qd.quote_id');
		$this->db->join('opportunity o','qd.opportunity_id=o.opportunity_id');
		$this->db->join('opportunity_product op','o.opportunity_id=op.opportunity_id');
		$this->db->join('product p','op.product_id=p.product_id');
		/*$this->db->join('weekly_user_product_target wupt','p.product_id=wupt.product_id and op.product_id=wupt.product_id');*/
		$this->db->join('lead l','o.lead_id=l.lead_id');
		$this->db->join('location l1','l.location_id=l1.location_id');
		$this->db->join('location l2','l1.parent_id=l2.location_id');
		$this->db->join('location l3','l2.parent_id=l3.location_id');
		$this->db->join('location l4','l3.parent_id=l4.location_id');
		$this->db->join('quote_op_margin_approval ma','ma.quote_revision_id = qr.quote_revision_id AND o.opportunity_id = ma.opportunity_id','LEFT');
		$this->db->where('cn.status',2);
		$this->db->where('p.target',1);
		$this->db->where('cn.company_id',$this->session->userdata('company'));
		/*$where='op.product_id in (SELECT wupt.product_id from weekly_user_product_target wupt join fy_week fw on wupt.fy_week_id=fw.fy_week_id  where wupt.status=1 and fw.fy_id='.$fy_year['fy_id'].' and wupt.user_id in('.$user_reportees.'))';
		$this->db->where($where);*/
		$user_reportees=$searchfilters['user_reportees_tvs'];
	    $this->db->where('l.user_id in ('.$user_reportees.')');
	    if($searchfilters['regions']!='')
	    {   
	    	$this->db->where('l4.location_id',$searchfilters['regions']);
	    }
	    if($searchfilters['vtime']!='')
		{
			if($where1!='')
				$this->db->where($where1);
		}
		$userProducts=$searchfilters['userProducts'];
	    $userLocations=$searchfilters['userLocations'];
	    $this->db->where('op.product_id IN ('.$userProducts.')');
		$this->db->where('l.location_id IN ('.$userLocations.') ');
	    $this->db->group_by('l4.location_id');
	    $res=$this->db->get();
		//echo $this->db->last_query();
		return $res->result_array();
	    //print_r($kk);exit;
	}
	public function get_open_orders_by_region($searchfilters)
	{
		$fy_year=get_custom_current_fiancial_year();
		if($searchfilters['vtime']!='')
		{
			$parameter="cn.created_time"; 
			$where1=getCustomReportTimelineCheck($searchfilters,$parameter,$fy_year);
		}
		if($searchfilters['measure']==1)
		{
			$this->db->select('sum(o.required_quantity) as open_orders,l4.location_id,l4.location');
		}
		elseif($searchfilters['measure']==2)
		{
			$this->db->select('ROUND(SUM( CASE WHEN ma.margin_approval_id IS NULL THEN ((o.required_quantity*qd.total_value)*(1-qr.discount/100))/100000 ELSE ( CASE WHEN ma.discount_type = 1 THEN ((o.required_quantity*qd.total_value)*(1-ma.discount/100))/100000 ELSE ((o.required_quantity*qd.total_value)-(ma.discount*qd.currency_factor))/100000 END ) END ),2) AS open_orders,l4.location_id,l4.location');
		}
		$this->db->from('contract_note cn');
		$this->db->join('contract_note_quote_revision cnqr','cn.contract_note_id=cnqr.contract_note_id');
		$this->db->join('quote_revision qr','cnqr.quote_revision_id=qr.quote_revision_id');
		$this->db->join('quote_details qd','qr.quote_id=qd.quote_id');
		$this->db->join('opportunity o','qd.opportunity_id=o.opportunity_id');
		$this->db->join('opportunity_product op','o.opportunity_id=op.opportunity_id');
		$this->db->join('product p','op.product_id=p.product_id');
		/*$this->db->join('weekly_user_product_target wupt','p.product_id=wupt.product_id and op.product_id=wupt.product_id');*/
		$this->db->join('lead l','o.lead_id=l.lead_id');
		$this->db->join('location l1','l.location_id=l1.location_id');
		$this->db->join('location l2','l1.parent_id=l2.location_id');
		$this->db->join('location l3','l2.parent_id=l3.location_id');
		$this->db->join('location l4','l3.parent_id=l4.location_id');
		$this->db->join('quote_op_margin_approval ma','ma.quote_revision_id = qr.quote_revision_id AND o.opportunity_id = ma.opportunity_id','LEFT');
		$this->db->where('cn.status in (1,3)');
		$this->db->where('p.target',1);
		$this->db->where('cn.company_id',$this->session->userdata('company'));
		/*$where='op.product_id in (SELECT wupt.product_id from weekly_user_product_target wupt join fy_week fw on wupt.fy_week_id=fw.fy_week_id  where wupt.status=1 and fw.fy_id='.$fy_year['fy_id'].' and wupt.user_id in('.$user_reportees.'))';
		$this->db->where($where);*/
		if($searchfilters['vtime']!='')
		{
			if($where1!='')
				$this->db->where($where1);
		}
		$user_reportees=$searchfilters['user_reportees_tvs'];
		$this->db->where('l.user_id in ('.$user_reportees.')');
	    if($searchfilters['regions']!='')
	    {
	    	$this->db->where('l4.location_id',$searchfilters['regions']);
	    }
	    $userProducts=$searchfilters['userProducts'];
	    $userLocations=$searchfilters['userLocations'];
	    $this->db->where('op.product_id IN ('.$userProducts.')');
		$this->db->where('l.location_id IN ('.$userLocations.') ');
	    $this->db->group_by('l4.location_id');
	    $res=$this->db->get();
		//echo $this->db->last_query();
		return $res->result_array();
	    //print_r($kk);exit;
	}
	public function get_open_opportunity_by_region($user_id, $role_id, $userProducts, $userLocations, $hotDay, $warmDate,$searchfilters)
	{
		$fy_year=get_custom_current_fiancial_year();
		if($searchfilters['vtime']!='')
		{
			$parameter="o.created_time"; 
			$where1=getCustomOppTimelineCheckPresent($searchfilters,$parameter,$fy_year);
		}
		if($searchfilters['measure']==1)
		{
			$this->db->select('sum(case when expected_order_conclusion <= "'.$hotDay.'" then o.required_quantity else 0 end) as Hot,
				sum(case when (expected_order_conclusion <= "'.$warmDate.'" AND expected_order_conclusion > "'.$hotDay.'") then o.required_quantity else 0 end) as Warm,
				sum(case when expected_order_conclusion > "'.$warmDate.'" then o.required_quantity else 0 end) as Cold,l4.location_id,l4.location');
	    }
	    elseif($searchfilters['measure']==2)
	    {
	    	$this->db->select('sum(case when expected_order_conclusion <= "'.$hotDay.'" then round((o.required_quantity*p.dp)/100000,2) else 0 end) as Hot,
				(case when (expected_order_conclusion <= "'.$warmDate.'" AND expected_order_conclusion > "'.$hotDay.'") then round(sum(o.required_quantity*p.dp)/100000,2) else 0 end) as Warm,
				(case when expected_order_conclusion > "'.$warmDate.'" then round((o.required_quantity*p.dp)/100000,2) else 0 end) as Cold,l4.location_id,l4.location');
	    }
				$this->db->from('opportunity o');
				$this->db->join('opportunity_status_history osh', 'osh.opportunity_id = o.opportunity_id');
				$this->db->join('opportunity_product op','op.opportunity_id = o.opportunity_id');
				$this->db->join('product p','p.product_id = op.product_id');
				/*$this->db->join('weekly_user_product_target wupt','p.product_id=wupt.product_id and op.product_id=wupt.product_id');*/
				$this->db->join('lead l',' l.lead_id = o.lead_id');
				$this->db->join('location l1','l.location_id=l1.location_id');
				$this->db->join('location l2','l1.parent_id=l2.location_id');
				$this->db->join('location l3','l2.parent_id=l3.location_id');
				$this->db->join('location l4','l3.parent_id=l4.location_id');
				$this->db->where( 'o.status IN (1,2,3,4,5)');
				$this->db->where('p.target',1);
				$this->db->where('o.company_id',$this->session->userdata('company'));
				if($role_id==4||$role_id==5)
				{
					$this->db->where('l.user_id IN ('.$user_id.')');
				}
				else
				{
					$this->db->where('op.product_id IN ('.$userProducts.')');
		    		$this->db->where('l.location_id IN ('.$userLocations.') ');
		    	}
				/*$where='op.product_id in (SELECT wupt.product_id from weekly_user_product_target wupt join fy_week fw on wupt.fy_week_id=fw.fy_week_id  where wupt.status=1 and fw.fy_id='.$fy_year['fy_id'].' and wupt.user_id in('.$user_reportees.'))';
		$this->db->where($where);*/
		if($searchfilters['vtime']!='')
		{
			if($where1!='')
				$this->db->where($where1);
		}
		if($searchfilters['regions']!='')
	    {
	    	$this->db->where('l4.location_id',$searchfilters['regions']);
	    }
		
		 $st_date=$searchfilters['fy_dates']['end_date'];
		$this->db->where('osh.opportunity_status_id = (SELECT osh2.opportunity_status_id FROM opportunity_status_history osh2 
				WHERE osh2.opportunity_id = o.opportunity_id AND  DATE(osh2.created_time) <= "'.$st_date.'" ORDER BY osh2.created_time DESC LIMIT 1)');
		
	    $this->db->group_by('l4.location_id');
		$r = $this->db->get();
		return $r->result_array();
		//echo $CI->db->last_query();
		/*foreach ($r->result_array() as $row) 
		{
			$row['Hot'] = ($row['Hot'] == '')?0:$row['Hot'];
			$row['Warm'] = ($row['Warm'] == '')?0:$row['Warm'];
			$row['Cold'] = ($row['Cold'] == '')?0:$row['Cold'];
			$c1Data[] = array('Cold', $row['Cold']);
			$c1Data[] = array('Warm', $row['Warm']);
			$c1Data[] = array('Hot', $row['Hot']);
		}
		return $c1Data;*/
	}
	public function get_user_location_regions()
	{   
		$this->db->select('l4.location,l4.location_id');
		$this->db->from('location l1');
		$this->db->join('location l2','l1.parent_id=l2.location_id');
		$this->db->join('location l3','l2.parent_id=l3.location_id');
		$this->db->join('location l4','l3.parent_id=l4.location_id');
		$this->db->where('l1.location_id in('.$this->session->userdata('locationString').')');
		$this->db->where('l4.territory_level_id',4);
		$this->db->group_by('l4.location_id');
		$res=$this->db->get();
		return $res->result_array();
	}
	function region_user_locations($region,$user_reportees)
	{   
		$qry_data='';
		$this->db->select('ul.user_id as users_id,u.first_name,u.employee_id');
		$this->db->from('location l1');
		$this->db->join('location l2','l1.location_id=l2.parent_id');
		$this->db->join('location l3','l2.location_id=l3.parent_id');
		$this->db->join('location l4','l3.location_id=l4.parent_id');
		$this->db->join('user_location ul','l1.location_id=ul.location_id or l2.location_id=ul.location_id or l3.location_id=ul.location_id or l4.location_id=ul.location_id');
		$this->db->join('user u','u.user_id=ul.user_id');
		if($region!='')
		{
			$this->db->where('l1.location_id',$region);
		}
		
		$this->db->where('l1.territory_level_id',4);
		$this->db->where('u.role_id!=',5);
		$this->db->where('ul.status',1);
		$this->db->where('u.user_id in ('.$user_reportees.')');
		$this->db->group_by('ul.user_id');
		$res=$this->db->get();
		$users_id =$res->result_array();
		$qry_data.='<option value="">Select Users</option>';
		foreach($users_id as $us)
		{   
			
			$qry_data.='<option value="'.$us['users_id'].'">'.$us['first_name'].' ('.$us['employee_id'].')'.'</option>';
		}
		return $qry_data;
	}
	function get_user_region_locations($region,$user_reportees)
	{   
		$this->db->select('ul.user_id as users_id,u.first_name,u.employee_id');
		$this->db->from('location l1');
		$this->db->join('location l2','l1.location_id=l2.parent_id');
		$this->db->join('location l3','l2.location_id=l3.parent_id');
		$this->db->join('location l4','l3.location_id=l4.parent_id');
		$this->db->join('user_location ul','l1.location_id=ul.location_id or l2.location_id=ul.location_id or l3.location_id=ul.location_id or l4.location_id=ul.location_id');
		$this->db->join('user u','u.user_id=ul.user_id');
		if($region!='')
		{
			$this->db->where('l1.location_id',$region);
		}
		
		$this->db->where('l1.territory_level_id',4);
		$this->db->where('u.role_id!=',5);
		$this->db->where('ul.status',1);
		$this->db->where('u.user_id in ('.$user_reportees.')');
		$this->db->group_by('ul.user_id');
		$res=$this->db->get();
		
		return $res->result_array();
	}
	public function get_funnel_opportunities_before_date($user_id, $role_id, $userProducts, $userLocations, $hotDay, $warmDate,$searchfilters)
	{
		//echo $hotDay.'-->'.$warmDate.'>';
		$fy_year=get_current_fiancial_year();
		if($searchfilters['vtime']!='')
		{
			$parameter="o.created_time"; 
			$where1=getOppTimelineCheck($searchfilters,$parameter,$fy_year);
		}
		
		if($searchfilters['measure']==1)
		{   
			
			  $this->db->select('sum(case when expected_order_conclusion <= "'.$hotDay.'" then o.required_quantity else 0 end) as Hot,
				sum(case when (expected_order_conclusion <= "'.$warmDate.'" AND expected_order_conclusion > "'.$hotDay.'") then o.required_quantity else 0 end) as Warm,
				sum(case when expected_order_conclusion > "'.$warmDate.'" then o.required_quantity else 0 end) as Cold');
		   
	    }
	    elseif($searchfilters['measure']==2)
	    {
	    	$this->db->select('sum(case when expected_order_conclusion <= "'.$hotDay.'" then round((o.required_quantity*p.dp)/100000,2) else 0 end) as Hot,
					sum(case when (expected_order_conclusion <= "'.$warmDate.'" AND expected_order_conclusion > "'.$hotDay.'") then round((o.required_quantity*p.dp)/100000,2) else 0 end) as Warm,
					sum(case when expected_order_conclusion > "'.$warmDate.'" then round((o.required_quantity*p.dp)/100000,2) else 0 end) as Cold');
		}
				$this->db->from('opportunity o');
				$this->db->join('opportunity_status_history osh', 'osh.opportunity_id = o.opportunity_id');
				$this->db->join('opportunity_product op','op.opportunity_id = o.opportunity_id');
				$this->db->join('product p','p.product_id = op.product_id');
				/*$this->db->join('weekly_user_product_target wupt','p.product_id=wupt.product_id and op.product_id=wupt.product_id');*/
				$this->db->join('lead l',' l.lead_id = o.lead_id');
				$this->db->join('location l1','l.location_id=l1.location_id');
				$this->db->join('location l2','l1.parent_id=l2.location_id');
				$this->db->join('location l3','l2.parent_id=l3.location_id');
				$this->db->join('location l4','l3.parent_id=l4.location_id');
				$this->db->where( 'osh.status IN (1,2,3,4,5)');
				$this->db->where('o.company_id',$this->session->userdata('company'));
				if($role_id==4||$role_id==5)
				{
					$this->db->where('l.user_id IN ('.$user_id.')');
				}
				else
				{
					$this->db->where('op.product_id IN ('.$userProducts.')');
		    		$this->db->where('l.location_id IN ('.$userLocations.') ');
		    	}
				/*$where='op.product_id in (SELECT wupt.product_id from weekly_user_product_target wupt join fy_week fw on wupt.fy_week_id=fw.fy_week_id  where wupt.status=1 and fw.fy_id='.$fy_year['fy_id'].' and wupt.user_id in('.$user_reportees.'))';
		$this->db->where($where);*/
		if($searchfilters['vtime']!='')
		{
			if($where1!='')
				$this->db->where($where1);
		}
		
		 if($searchfilters['region']!='')
	    {
	    	$this->db->where('l4.location_id',$searchfilters['region']);
	    }
	   
		$st_date=$searchfilters['fy_dates']['start_date'];
		$this->db->where('osh.opportunity_status_id = (SELECT osh2.opportunity_status_id FROM opportunity_status_history osh2 
				WHERE osh2.opportunity_id = o.opportunity_id AND  DATE(osh2.created_time) < "'.$st_date.'" ORDER BY osh2.created_time DESC LIMIT 1)');
	    $r = $this->db->get();
	    //echo $this->db->last_query();exit;
		return $r->row_array();
	}
	public function get_funnel_opportunities_opened_status($user_id, $role_id, $userProducts, $userLocations, $hotDay, $warmDate,$searchfilters,$row='')
	{
		
		$fy_year=get_current_fiancial_year();
		
		if($searchfilters['vtime']!='m')
		{
			$parameter="o.created_time"; 
			$where1=getReportTimelineCheck($searchfilters,$parameter,$fy_year);
		}

		if($searchfilters['measure']==1)
		{	
			if($searchfilters['vtime']=='w')
			{
				$this->db->select('sum(o.required_quantity) as opened_value,DATE_FORMAT(o.created_time,"%d%b") as timeline,date(o.created_time) as ctime');
		    }
		    elseif($searchfilters['vtime']=='m' )
			{
				$this->db->select('sum(o.required_quantity) as opened_value');
		    }
		    elseif($searchfilters['vtime']=='q' || $searchfilters['vtime']=='y')
		    {
		    	$this->db->select('sum(o.required_quantity) as opened_value,
					DATE_FORMAT(o.created_time,"%b-%y") as timeline,month(o.created_time) as ctime');
		    }
		}
		elseif($searchfilters['measure']==2)
		{
			if($searchfilters['vtime']=='w')
			{
				$this->db->select('sum(round((o.required_quantity*p.dp)/100000,2)) as opened_value,DATE_FORMAT(o.created_time,"%d%b") as timeline,date(o.created_time) as ctime');
		    }
		    elseif($searchfilters['vtime']=='m')
			{
				$this->db->select('sum(round((o.required_quantity*p.dp)/100000,2)) as opened_value');
		    }
		    elseif($searchfilters['vtime']=='q' || $searchfilters['vtime']=='y')
		    {
		    	$this->db->select('sum(round((o.required_quantity*p.dp)/100000,2)) as opened_value,
					DATE_FORMAT(o.created_time,"%b-%y") as timeline,month(o.created_time) as ctime');
		    }
		}
				$this->db->from('opportunity o');
				$this->db->join('opportunity_product op','op.opportunity_id = o.opportunity_id');
				$this->db->join('product p','p.product_id = op.product_id');
				
				/*$this->db->join('weekly_user_product_target wupt','p.product_id=wupt.product_id and op.product_id=wupt.product_id');*/
				$this->db->join('lead l',' l.lead_id = o.lead_id');
				$this->db->join('location l1','l.location_id=l1.location_id');
				$this->db->join('location l2','l1.parent_id=l2.location_id');
				$this->db->join('location l3','l2.parent_id=l3.location_id');
				$this->db->join('location l4','l3.parent_id=l4.location_id');
				//$opened_array=array(1,2,3,4,5);
				//$this->db->where_in( 'o.status',$opened_array);
				if($role_id==4||$role_id==5)
				{
					$this->db->where('l.user_id IN ('.$user_id.')');
				}
				else
				{
					$this->db->where('op.product_id IN ('.$userProducts.')');
		    		$this->db->where('l.location_id IN ('.$userLocations.') ');
		    		
				}
				$this->db->where('o.company_id',$this->session->userdata('company'));
				/*$where='op.product_id in (SELECT wupt.product_id from weekly_user_product_target wupt join fy_week fw on wupt.fy_week_id=fw.fy_week_id  where wupt.status=1 and fw.fy_id='.$fy_year['fy_id'].' and wupt.user_id in('.$user_reportees.'))';
		$this->db->where($where);*/
		//$this->db->where('l.user_id IN ('.$user_id.')');
		
		if($searchfilters['vtime']!='m')
		{
			if($where1!='')
				$this->db->where($where1);
		}
		
		 if($searchfilters['region']!='')
	    {
	    	$this->db->where('l4.location_id',$searchfilters['region']);
	    }
	   if($searchfilters['vtime']=='m')
		{
			$this->db->where('date(o.created_time) >=',$row['start_date']);
	        $this->db->where('date(o.created_time) <=',$row['end_date']);
	       
	    }
	    if($searchfilters['vtime']=='w')
	    {
	    	 $this->db->group_by('date(o.created_time)');
	         $this->db->order_by('ctime','asc');
	    }
	    if($searchfilters['vtime']=='y'||$searchfilters['vtime']=='q')
	    {
	    	$this->db->group_by('month(o.created_time)');
	    	$this->db->order_by('year(o.created_time),month(o.created_time)','asc');
	    }
		
	    $r = $this->db->get();
	  // echo $this->db->last_query();exit;
	   if($searchfilters['vtime']=='m')
	   {
		 return $r->row_array();
	   }
	   else
	   {
	   	 return $r->result_array();
	   }
	}
	public function get_funnel_opportunities_closed_status($user_id, $role_id, $userProducts, $userLocations, $hotDay, $warmDate,$searchfilters,$row='')
	{
		
		$fy_year=get_current_fiancial_year();
		if($searchfilters['vtime']!='m')
		{
			$parameter="o.closed_time"; 
			$where1=getReportTimelineCheck($searchfilters,$parameter,$fy_year);
		}

		if($searchfilters['measure']==1)
		{	
			if($searchfilters['vtime']=='w')
			{
				$this->db->select('sum(o.required_quantity) as closed_value,DATE_FORMAT(o.closed_time,"%d%b") as timeline,date(o.closed_time) as ctime');
		    }
		    elseif($searchfilters['vtime']=='m')
			{
				$this->db->select('sum(o.required_quantity) as closed_value');
		    }
		    elseif($searchfilters['vtime']=='q' || $searchfilters['vtime']=='y')
		    {
		    	$this->db->select('sum(o.required_quantity) as closed_value,DATE_FORMAT(
					o.closed_time,"%b-%y") as timeline,date(CONCAT(year(o.closed_time),"-",month(o.closed_time),"-01")) as ctime');
		    }
		}
		elseif($searchfilters['measure']==2)
		{
			if($searchfilters['vtime']=='w')
			{
				$this->db->select('sum(round((o.required_quantity*p.dp)/100000,2)) as closed_value,DATE_FORMAT(o.closed_time,"%d%b") as timeline,date(o.closed_time) as ctime');
		    }
		    elseif($searchfilters['vtime']=='m')
			{
				$this->db->select('sum(round((o.required_quantity*p.dp)/100000,2)) as closed_value');
		    }
		    elseif($searchfilters['vtime']=='q' || $searchfilters['vtime']=='y')
		    {
		    	$this->db->select('sum(round((o.required_quantity*p.dp)/100000,2)) as closed_value,
					DATE_FORMAT(o.closed_time,"%b-%y") as timeline,date(CONCAT(year(o.closed_time),"-",month(o.closed_time),"-01")) as ctime');
		    }
		}
				$this->db->from('opportunity o');
			//	$this->db->join('opportunity_status_history osh', 'osh.opportunity_id = o.opportunity_id');
				$this->db->join('opportunity_product op','op.opportunity_id = o.opportunity_id');
				$this->db->join('product p','p.product_id = op.product_id');
				
				/*$this->db->join('weekly_user_product_target wupt','p.product_id=wupt.product_id and op.product_id=wupt.product_id');*/
				$this->db->join('lead l',' l.lead_id = o.lead_id');
				$this->db->join('location l1','l.location_id=l1.location_id');
				$this->db->join('location l2','l1.parent_id=l2.location_id');
				$this->db->join('location l3','l2.parent_id=l3.location_id');
				$this->db->join('location l4','l3.parent_id=l4.location_id');
				$closed_array=array(6,7,8);
				$this->db->where_in( 'o.status',$closed_array);
				$this->db->where('o.company_id',$this->session->userdata('company'));
				//$this->db->where('op.product_id IN ('.$userProducts.')');
			    //$this->db->where('l.location_id IN ('.$userLocations.') ');
				/*$where='op.product_id in (SELECT wupt.product_id from weekly_user_product_target wupt join fy_week fw on wupt.fy_week_id=fw.fy_week_id  where wupt.status=1 and fw.fy_id='.$fy_year['fy_id'].' and wupt.user_id in('.$user_reportees.'))';
		       $this->db->where($where);*/
		
		       if($role_id==4||$role_id==5)
				{
					$this->db->where('l.user_id IN ('.$user_id.')');
				}
				else
				{
					$this->db->where('op.product_id IN ('.$userProducts.')');
		    		$this->db->where('l.location_id IN ('.$userLocations.') ');
		    		
				}
		
		 if($searchfilters['region']!='')
	    {
	    	$this->db->where('l4.location_id',$searchfilters['region']);
	    }
	   
		if($searchfilters['vtime']!='m')
		{
			if($where1!='')
				$this->db->where($where1);
		}
		/*$st_date=$searchfilters['fy_dates']['start_date'];
		$end_date=$searchfilters['fy_dates']['end_date'];
		$this->db->where('osh.opportunity_status_id = (SELECT osh2.opportunity_status_id FROM opportunity_status_history osh2 
				WHERE osh2.opportunity_id = o.opportunity_id  ORDER BY osh2.created_time DESC LIMIT 1)');*/
		
	    if($searchfilters['vtime']=='m')
		{
			$this->db->where('date(o.closed_time) >=',$row['start_date']);
	        $this->db->where('date(o.closed_time) <=',$row['end_date']);
	       
	    }
	    if($searchfilters['vtime']=='w')
	    {
	    	 $this->db->group_by('date(o.closed_time)');
	         $this->db->order_by('ctime','asc');
	    }
	     if($searchfilters['vtime']=='y'||$searchfilters['vtime']=='q')
	    {
	    	$this->db->group_by('month(o.closed_time)');
	    	$this->db->order_by('year(o.closed_time),month(o.closed_time)','asc');
	    }
	    $r = $this->db->get();
	  // echo $this->db->last_query();exit;
		if($searchfilters['vtime']=='m')
	   {
		 return $r->row_array();
	   }
	   else
	   {
	   	 return $r->result_array();
	   }
	}
	public function get_funnel_opportunities_present_date($user_id, $role_id, $userProducts, $userLocations, $hotDay, $warmDate,$searchfilters)
	{
		//echo $hotDay.'-->'.$warmDate; //exit;
		$st_date=$searchfilters['fy_dates']['end_date'];
		$start_date = ($st_date<=date('Y-m-d'))?$st_date:date('Y-m-d');
		$month=date('m',strtotime($start_date));
    	$year=date('Y',strtotime($start_date));
    	$day = getOpportunityCategorizationDate();
     	$hotDay = $year."-".$month."-".$day;
    	$warmDate = date('Y-m-d',strtotime( $hotDay . " +1 month " ));
    	//echo $hotDay.'-->'.$warmDate; exit;
		//$searchfilters['measure']=1;
		$fy_year=get_current_fiancial_year();
		if($searchfilters['vtime']!='')
		{
			$parameter="o.created_time"; 
			$where1=getOppTimelineCheckPresent($searchfilters,$parameter,$fy_year);
		}
		if($searchfilters['measure']==1)
		{   
			$this->db->select('sum(case when expected_order_conclusion <= "'.$hotDay.'" then o.required_quantity else 0 end) as Hot,
				sum(case when (expected_order_conclusion <= "'.$warmDate.'" AND expected_order_conclusion > "'.$hotDay.'") then o.required_quantity else 0 end) as Warm,
				sum(case when expected_order_conclusion > "'.$warmDate.'" then o.required_quantity else 0 end) as Cold');
		   
	    }
	    elseif($searchfilters['measure']==2)
	    {
	    	$this->db->select('sum(case when expected_order_conclusion <= "'.$hotDay.'" then round((o.required_quantity*p.dp)/100000,2) else 0 end) as Hot,
					sum(case when (expected_order_conclusion <= "'.$warmDate.'" AND expected_order_conclusion > "'.$hotDay.'") then round((o.required_quantity*p.dp)/100000,2) else 0 end) as Warm,
					sum(case when expected_order_conclusion > "'.$warmDate.'" then round((o.required_quantity*p.dp)/100000,2) else 0 end) as Cold');
		}
				$this->db->from('opportunity o');
				$this->db->join('opportunity_status_history osh', 'osh.opportunity_id = o.opportunity_id');
				$this->db->join('opportunity_product op','op.opportunity_id = o.opportunity_id');
				$this->db->join('product p','p.product_id = op.product_id');
				/*$this->db->join('weekly_user_product_target wupt','p.product_id=wupt.product_id and op.product_id=wupt.product_id');*/
				$this->db->join('lead l',' l.lead_id = o.lead_id');
				$this->db->join('location l1','l.location_id=l1.location_id');
				$this->db->join('location l2','l1.parent_id=l2.location_id');
				$this->db->join('location l3','l2.parent_id=l3.location_id');
				$this->db->join('location l4','l3.parent_id=l4.location_id');
				$this->db->where( 'osh.status IN (1,2,3,4,5)');
				if($role_id==4||$role_id==5)
				{
					$this->db->where('l.user_id IN ('.$user_id.')');
				}
				else
				{
					$this->db->where('op.product_id IN ('.$userProducts.')');
		    		$this->db->where('l.location_id IN ('.$userLocations.') ');
				}
				$this->db->where('o.company_id',$this->session->userdata('company'));
				/*$where='op.product_id in (SELECT wupt.product_id from weekly_user_product_target wupt join fy_week fw on wupt.fy_week_id=fw.fy_week_id  where wupt.status=1 and fw.fy_id='.$fy_year['fy_id'].' and wupt.user_id in('.$user_reportees.'))';
		$this->db->where($where);*/
		//$this->db->where('l.user_id IN('.$user_id.')');
		
		if($searchfilters['vtime']!='')
		{
			if($where1!='')
				$this->db->where($where1);
			//echo 'hi';exit;
		}
		
		 if($searchfilters['region']!='')
	    {
	    	$this->db->where('l4.location_id',$searchfilters['region']);
	    }
	   /* if($searchfilters['users']!='')
		{
			$this->db->where('l.user_id',$searchfilters['users']);
		}*/
		
		$this->db->where('osh.opportunity_status_id = (SELECT osh2.opportunity_status_id FROM opportunity_status_history osh2 
				WHERE osh2.opportunity_id = o.opportunity_id AND  DATE(osh2.created_time) <= "'.$st_date.'" ORDER BY osh2.created_time DESC LIMIT 1)');
	    $r = $this->db->get();
	 //   echo $this->db->last_query();exit;
		return $r->row_array();
	}
	public function get_fo_by_date_opened($user_id, $role_id, $userProducts, $userLocations, $search_date,$searchfilters)
	{   
		
	
		if($searchfilters['vtime']=='y'||$searchfilters['vtime']=='q')
		{   
			$search_date=date('Y-m-d',strtotime($search_date));
			$month_no=date('m',strtotime($search_date));
			$date=date('Y-m-d');
			$row=get_month_start_end_date($date,$month_no);
		}
		elseif($searchfilters['vtime']=='m')
		{
			$row=array();
			$row['start_date']=substr($search_date,7,10);
			$row['end_date']=substr($search_date,21,10);
		}
		else
		{
			$search_date=date('Y-m-d',strtotime($search_date));
			$row = array('end_date'=>date('Y-m-d'));
		}

		$end_date=$row['end_date'];
    	$month=date('m',strtotime($end_date));
    	$year=date('Y',strtotime($end_date));
	    $day = getOpportunityCategorizationDate();
	    $hotDay = $year."-".$month."-".$day;
	    $warmDate = date('Y-m-d',strtotime( $hotDay . " +1 month " ));
		
		$fy_year=get_current_fiancial_year();
		if($searchfilters['measure']==1)
		{   
			 $this->db->select('sum(case when expected_order_conclusion <= CONCAT(YEAR(o.created_time),"-",MONTH(o.created_time),"-",'.$day.') then o.required_quantity else 0 end) as Hot,
				sum(case when (expected_order_conclusion <= DATE_ADD(CONCAT(YEAR(o.created_time),"-",MONTH(o.created_time),"-",'.$day.'),INTERVAL 1 MONTH) AND expected_order_conclusion > CONCAT(YEAR(o.created_time),"-",MONTH(o.created_time),"-",'.$day.')) then o.required_quantity else 0 end) as Warm,
				sum(case when expected_order_conclusion > DATE_ADD(CONCAT(YEAR(o.created_time),"-",MONTH(o.created_time),"-",'.$day.'),INTERVAL 1 MONTH) then o.required_quantity else 0 end) as Cold');
		    
		   
	    }
	    elseif($searchfilters['measure']==2)
	    {
	    	
	    	$this->db->select('sum(case when expected_order_conclusion <= CONCAT(YEAR(o.created_time),"-",MONTH(o.created_time),"-",'.$day.') then round((o.required_quantity*p.dp)/100000,2) else 0 end) as Hot,
				sum(case when (expected_order_conclusion <= DATE_ADD(CONCAT(YEAR(o.created_time),"-",MONTH(o.created_time),"-",'.$day.'),INTERVAL 1 MONTH) AND expected_order_conclusion > CONCAT(YEAR(o.created_time),"-",MONTH(o.created_time),"-",'.$day.')) then round((o.required_quantity*p.dp)/100000,2) else 0 end) as Warm,
				sum(case when expected_order_conclusion > DATE_ADD(CONCAT(YEAR(o.created_time),"-",MONTH(o.created_time),"-",'.$day.'),INTERVAL 1 MONTH) then round((o.required_quantity*p.dp)/100000,2) else 0 end) as Cold');
		}
			$this->db->from('opportunity o');
			$this->db->join('opportunity_product op','op.opportunity_id = o.opportunity_id');
			$this->db->join('product p','p.product_id = op.product_id');
			/*$this->db->join('weekly_user_product_target wupt','p.product_id=wupt.product_id and op.product_id=wupt.product_id');*/
			$this->db->join('lead l',' l.lead_id = o.lead_id');
			$this->db->join('location l1','l.location_id=l1.location_id');
			$this->db->join('location l2','l1.parent_id=l2.location_id');
			$this->db->join('location l3','l2.parent_id=l3.location_id');
			$this->db->join('location l4','l3.parent_id=l4.location_id');
			$this->db->where('o.company_id',$this->session->userdata('company'));
			//$this->db->where( 'o.status IN (1,2,3,4,5)');
			if($role_id==4||$role_id==5)
			{
				$this->db->where('l.user_id IN ('.$user_id.')');
			}
			else
			{
				$this->db->where('op.product_id IN ('.$userProducts.')');
	    		$this->db->where('l.location_id IN ('.$userLocations.') ');
	    	}
			/*$where='op.product_id in (SELECT wupt.product_id from weekly_user_product_target wupt join fy_week fw on wupt.fy_week_id=fw.fy_week_id  where wupt.status=1 and fw.fy_id='.$fy_year['fy_id'].' and wupt.user_id in('.$user_reportees.'))';
		$this->db->where($where);*/
		//$this->db->where('l.user_id IN ('.$user_id.')');
		
		if($searchfilters['vtime']!='w')
		{
			$this->db->where('date(o.created_time)>=',$row['start_date']);
	        $this->db->where('date(o.created_time)<=',$row['end_date']);
	    }
		elseif($searchfilters['vtime']=='w')
		{
			$this->db->where('date(o.created_time)',$search_date);
		}
		 if($searchfilters['region']!='')
		{
	    	$this->db->where('l4.location_id',$searchfilters['region']);
	    }
	    /*if($searchfilters['users']!='')
		{
			$this->db->where('l.user_id',$searchfilters['users']);
		}*/
	    $r = $this->db->get();
	  //  echo $this->db->last_query();exit;
		return $r->row_array();
	}
	public function get_fo_by_date_closed($user_id, $role_id, $userProducts, $userLocations, $search_date,$searchfilters)
	{
		if($searchfilters['vtime']=='y'||$searchfilters['vtime']=='q')
		{   
			$search_date=date('Y-m-d',strtotime($search_date));
			$month_no=date('m',strtotime($search_date));
			$date=date('Y-m-d');
			$row=get_month_start_end_date($date,$month_no);
		}
		elseif($searchfilters['vtime']=='m')
		{
			$row=array();
			$row['start_date']=substr($search_date,7,10);
			$row['end_date']=substr($search_date,21,10);
		}
		else
		{
			$search_date=date('Y-m-d',strtotime($search_date));
		}
		
		$fy_year=get_current_fiancial_year();
		if($searchfilters['measure']==1)
		{   
			 $this->db->select('sum(o.required_quantity) as measure,os.name as status
				');
		}
	    elseif($searchfilters['measure']==2)
	    {
	    	
	    	$this->db->select('sum(round((o.required_quantity*p.dp)/100000,2)) as measure,os.name as status
				');
		}
			$this->db->from('opportunity o');
			//$this->db->join('opportunity_status_history osh', 'osh.opportunity_id = o.opportunity_id');
			$this->db->join('opportunity_product op','op.opportunity_id = o.opportunity_id');
			$this->db->join('product p','p.product_id = op.product_id');
			$this->db->join('opportunity_status os','o.status=os.status');
			$this->db->join('lead l',' l.lead_id = o.lead_id');
			$this->db->join('location l1','l.location_id=l1.location_id');
			$this->db->join('location l2','l1.parent_id=l2.location_id');
			$this->db->join('location l3','l2.parent_id=l3.location_id');
			$this->db->join('location l4','l3.parent_id=l4.location_id');
			$closed_status_arr=array(6,7,8);
		    $this->db->where_in( 'o.status',$closed_status_arr);
			if($role_id==4||$role_id==5)
			{
				$this->db->where('l.user_id IN ('.$user_id.')');
			}
			else
			{
				$this->db->where('op.product_id IN ('.$userProducts.')');
	    		$this->db->where('l.location_id IN ('.$userLocations.') ');
	    	}
			/*$where='op.product_id in (SELECT wupt.product_id from weekly_user_product_target wupt join fy_week fw on wupt.fy_week_id=fw.fy_week_id  where wupt.status=1 and fw.fy_id='.$fy_year['fy_id'].' and wupt.user_id in('.$user_reportees.'))';
		$this->db->where($where);*/
		//$this->db->where('l.user_id IN ('.$user_id.')');
		$this->db->where('o.company_id',$this->session->userdata('company'));
		if($searchfilters['vtime']!='w')
		{
			$this->db->where('date(o.closed_time)>=',$row['start_date']);
	        $this->db->where('date(o.closed_time)<=',$row['end_date']);
	    }
		elseif($searchfilters['vtime']=='w')
		{
			$this->db->where('date(o.closed_time)',$search_date);
		}
		 if($searchfilters['region']!='')
		{
	    	$this->db->where('l4.location_id',$searchfilters['region']);
	    }
	    /*if($searchfilters['users']!='')
		{
			$this->db->where('l.user_id',$searchfilters['users']);
		}*/
		/*$st_date=$searchfilters['fy_dates']['start_date'];
		$end_date=$searchfilters['fy_dates']['end_date'];
		$this->db->where('osh.opportunity_status_id = (SELECT osh2.opportunity_status_id FROM opportunity_status_history osh2 
				WHERE osh2.opportunity_id = o.opportunity_id AND  DATE(osh2.created_time) BETWEEN "'.$st_date.'" and "'.$end_date.'" ORDER BY osh2.created_time DESC LIMIT 1)');*/
		$this->db->group_by('o.status');
	    $r = $this->db->get();
	 // echo $this->db->last_query();exit;
		return $r->result_array();
	}
	public function get_fo_by_closed_lost_reason($user_id,$role_id,$userProducts,$userLocations,$search_date,$searchfilters)
	{   
		if($searchfilters['vtime']=='y'||$searchfilters['vtime']=='q')
		{   
			$search_date=date('Y-m-d',strtotime($search_date));
			$month_no=date('m',strtotime($search_date));
			$date=date('Y-m-d');
			$row=get_month_start_end_date($date,$month_no);
		}
		elseif($searchfilters['vtime']=='m')
		{
			$row=array();
			$row['start_date']=substr($search_date,7,10);
			$row['end_date']=substr($search_date,21,10);
		}
		else
		{
			$search_date=date('Y-m-d',strtotime($search_date));
		}
		
		$fy_year=get_current_fiancial_year();
		if($searchfilters['measure']==1)
		{   
			 $this->db->select('sum(o.required_quantity) as measure, ols.name as reason_name');
		}
	    elseif($searchfilters['measure']==2)
	    {
	    	
	    	$this->db->select('sum(round((o.required_quantity*p.dp)/100000,2)) as measure,ols.name as reason_name');
		}
			$this->db->from('opportunity o');
			//$this->db->join('opportunity_status_history osh', 'osh.opportunity_id = o.opportunity_id');
			$this->db->join('opportunity_product op','op.opportunity_id = o.opportunity_id');
			$this->db->join('product p','p.product_id = op.product_id');
			$this->db->join('opportunity_lost_reasons ols','o.oppr_lost_id=ols.reason_id','left');
			$this->db->join('lead l',' l.lead_id = o.lead_id');
			$this->db->join('location l1','l.location_id=l1.location_id');
			$this->db->join('location l2','l1.parent_id=l2.location_id');
			$this->db->join('location l3','l2.parent_id=l3.location_id');
			$this->db->join('location l4','l3.parent_id=l4.location_id');
			$this->db->where( 'o.status',7);
			$this->db->where('o.company_id',$this->session->userdata('company'));
			if($role_id==4||$role_id==5)
			{
				$this->db->where('l.user_id IN ('.$user_id.')');
			}
			else
			{
				$this->db->where('op.product_id IN ('.$userProducts.')');
	    		$this->db->where('l.location_id IN ('.$userLocations.') ');
	    	}
			/*$where='op.product_id in (SELECT wupt.product_id from weekly_user_product_target wupt join fy_week fw on wupt.fy_week_id=fw.fy_week_id  where wupt.status=1 and fw.fy_id='.$fy_year['fy_id'].' and wupt.user_id in('.$user_reportees.'))';
		$this->db->where($where);*/
		//$this->db->where('l.user_id IN ('.$user_id.')');
		
		if($searchfilters['vtime']!='w')
		{
			$this->db->where('DATE(o.closed_time)>=',$row['start_date']);
	        $this->db->where('DATE(o.closed_time)<=',$row['end_date']);
	    }
		elseif($searchfilters['vtime']=='w')
		{
			$this->db->where('date(o.closed_time)',$search_date);
		}
		 if($searchfilters['region']!='')
		{
	    	$this->db->where('l4.location_id',$searchfilters['region']);
	    }
	    /*$st_date=$searchfilters['fy_dates']['start_date'];
		$end_date=$searchfilters['fy_dates']['end_date'];
		$this->db->where('osh.opportunity_status_id = (SELECT osh2.opportunity_status_id FROM opportunity_status_history osh2 
				WHERE osh2.opportunity_id = o.opportunity_id AND  DATE(osh2.created_time) BETWEEN "'.$st_date.'" and "'.$end_date.'" ORDER BY osh2.created_time DESC LIMIT 1)');*/
		$this->db->group_by('o.oppr_lost_id');
	    $r = $this->db->get();
	  //  echo $this->db->last_query();exit;
		return $r->result_array();
	}
	public function get_fo_by_closed_lost_comp($user_id,$role_id,$userProducts,$userLocations,$search_date,$searchfilters)
	{
		if($searchfilters['vtime']=='y'||$searchfilters['vtime']=='q')
		{   
			$search_date=date('Y-m-d',strtotime($search_date));
			$month_no=date('m',strtotime($search_date));
			$date=date('Y-m-d');
			$row=get_month_start_end_date($date,$month_no);
		}
		elseif($searchfilters['vtime']=='m')
		{
			$row=array();
			$row['start_date']=substr($search_date,7,10);
			$row['end_date']=substr($search_date,21,10);
		}
		else
		{
			$search_date=date('Y-m-d',strtotime($search_date));
		}
		
		$fy_year=get_current_fiancial_year();
		if($searchfilters['measure']==1)
		{   
			 $this->db->select('sum(o.required_quantity) as measure, c.name as competitor_name');
		}
	    elseif($searchfilters['measure']==2)
	    {
	    	
	    	$this->db->select('sum(round((o.required_quantity*p.dp)/100000,2)) as measure,c.name as competitor_name');
		}
			$this->db->from('opportunity o');
			//$this->db->join('opportunity_status_history osh', 'osh.opportunity_id = o.opportunity_id');
			$this->db->join('opportunity_product op','op.opportunity_id = o.opportunity_id');
			$this->db->join('product p','p.product_id = op.product_id');
			$this->db->join('competitor c','o.lost_competitor_id=c.competitor_id','left');
			$this->db->join('lead l',' l.lead_id = o.lead_id');
			$this->db->join('location l1','l.location_id=l1.location_id');
			$this->db->join('location l2','l1.parent_id=l2.location_id');
			$this->db->join('location l3','l2.parent_id=l3.location_id');
			$this->db->join('location l4','l3.parent_id=l4.location_id');
			$this->db->where( 'o.status',7);
			if($role_id==4||$role_id==5)
			{
				$this->db->where('l.user_id IN ('.$user_id.')');
			}
			else
			{
				$this->db->where('op.product_id IN ('.$userProducts.')');
	    		$this->db->where('l.location_id IN ('.$userLocations.') ');
			}
			$this->db->where('o.company_id',$this->session->userdata('company'));
			/*$where='op.product_id in (SELECT wupt.product_id from weekly_user_product_target wupt join fy_week fw on wupt.fy_week_id=fw.fy_week_id  where wupt.status=1 and fw.fy_id='.$fy_year['fy_id'].' and wupt.user_id in('.$user_reportees.'))';
		$this->db->where($where);*/
		//$this->db->where('l.user_id IN ( '.$user_id.')');
		
		if($searchfilters['vtime']!='w')
		{
			$this->db->where('DATE(o.closed_time)>=',$row['start_date']);
	        $this->db->where('DATE(o.closed_time)<=',$row['end_date']);
	    }
		elseif($searchfilters['vtime']=='w')
		{
			$this->db->where('date(o.closed_time)',$search_date);
		}
		 if($searchfilters['region']!='')
		{
	    	$this->db->where('l4.location_id',$searchfilters['region']);
	    }
	   
		/*$st_date=$searchfilters['fy_dates']['start_date'];
		$end_date=$searchfilters['fy_dates']['end_date'];
		$this->db->where('osh.opportunity_status_id = (SELECT osh2.opportunity_status_id FROM opportunity_status_history osh2 
				WHERE osh2.opportunity_id = o.opportunity_id AND  DATE(osh2.created_time) BETWEEN "'.$st_date.'" and "'.$end_date.'" ORDER BY osh2.created_time DESC LIMIT 1)');*/
		$this->db->group_by('o.lost_competitor_id');
	    $r = $this->db->get();
	  //  echo $this->db->last_query();exit;
		return $r->result_array();
	}
	public function get_lost_competitor_list_product($searchfilters,$series_name)
	{
		if($searchfilters['vtime']=='y'||$searchfilters['vtime']=='q')
		{   
			$search_date=date('Y-m-d',strtotime($searchfilters['search_date']));
			$month_no=date('m',strtotime($searchfilters['search_date']));
			$date=date('Y-m-d');
			$row=get_month_start_end_date($date,$month_no);
		}
		elseif($searchfilters['vtime']=='m')
		{
			$row=array();
			$row['start_date']=substr($searchfilters['search_date'],7,10);
			$row['end_date']=substr($searchfilters['search_date'],21,10);
		}
		else
		{
			$search_date=date('Y-m-d',strtotime($searchfilters['search_date']));
		}
		
		$fy_year=get_current_fiancial_year();
		
	    $this->db->select('sum(round((o.required_quantity*p.dp)/100000,2)) as value,sum(o.required_quantity) as qty,c.name as competitor_name,p.name,p.description,pg.name as segment_name');
		
		$this->db->from('opportunity o');
		//$this->db->join('opportunity_status_history osh', 'osh.opportunity_id = o.opportunity_id');
		$this->db->join('opportunity_product op','op.opportunity_id = o.opportunity_id');
		$this->db->join('product p','p.product_id = op.product_id');
		$this->db->join('product_group pg','p.group_id=pg.group_id');
		$this->db->join('competitor c','o.lost_competitor_id=c.competitor_id','left');
		$this->db->join('lead l',' l.lead_id = o.lead_id');
		$this->db->join('location l1','l.location_id=l1.location_id');
		$this->db->join('location l2','l1.parent_id=l2.location_id');
		$this->db->join('location l3','l2.parent_id=l3.location_id');
		$this->db->join('location l4','l3.parent_id=l4.location_id');
		$this->db->where( 'o.status',7);
		$this->db->where('o.company_id',$this->session->userdata('company'));
		if($searchfilters['role_id']==4||$searchfilters['role_id']==5)
		{
			$this->db->where('l.user_id IN ('.$searchfilters['reportee_users'].')');
		}
		else
		{
			$this->db->where('op.product_id IN ('.$searchfilters['userProducts'].')');
    		$this->db->where('l.location_id IN ('.$searchfilters['userLocations'].') ');
    	}
		$this->db->where('c.name',$series_name);
		if($searchfilters['vtime']!='w')
		{
			$this->db->where('DATE(o.closed_time)>=',$row['start_date']);
	        $this->db->where('DATE(o.closed_time)<=',$row['end_date']);
	    }
		elseif($searchfilters['vtime']=='w')
		{
			$this->db->where('date(o.closed_time)',$search_date);
		}
		 if($searchfilters['region']!='')
		{
	    	$this->db->where('l4.location_id',$searchfilters['region']);
	    }
	   
		/*$st_date=$searchfilters['fy_dates']['start_date'];
		$end_date=$searchfilters['fy_dates']['end_date'];
		$this->db->where('osh.opportunity_status_id = (SELECT osh2.opportunity_status_id FROM opportunity_status_history osh2 
				WHERE osh2.opportunity_id = o.opportunity_id AND  DATE(osh2.created_time) BETWEEN "'.$st_date.'" and "'.$end_date.'" ORDER BY osh2.created_time DESC LIMIT 1)');*/
		$this->db->group_by('p.product_id');
	    $r = $this->db->get();
	  //  echo $this->db->last_query();exit;
		return $r->result_array();
	}
	public function get_lost_reason_list_product($searchfilters,$series_name)
	{   
		if($searchfilters['vtime']=='y'||$searchfilters['vtime']=='q')
		{   
			$search_date=date('Y-m-d',strtotime($searchfilters['search_date']));
			$month_no=date('m',strtotime($searchfilters['search_date']));
			$date=date('Y-m-d');
			$row=get_month_start_end_date($date,$month_no);
		}
		elseif($searchfilters['vtime']=='m')
		{
			$row=array();
			$row['start_date']=substr($searchfilters['search_date'],7,10);
			$row['end_date']=substr($searchfilters['search_date'],21,10);
		}
		else
		{
			$search_date=date('Y-m-d',strtotime($searchfilters['search_date']));
		}
		
		$fy_year=get_current_fiancial_year();
		$this->db->select('sum(round((o.required_quantity*p.dp)/100000,2)) as value,ols.name as reason_name,sum(o.required_quantity) as qty,p.name,p.description,pg.name as segment_name');
		
			$this->db->from('opportunity o');
			//$this->db->join('opportunity_status_history osh', 'osh.opportunity_id = o.opportunity_id');
			$this->db->join('opportunity_product op','op.opportunity_id = o.opportunity_id');
			$this->db->join('product p','p.product_id = op.product_id');
			$this->db->join('product_group pg','p.group_id=pg.group_id');
			$this->db->join('opportunity_lost_reasons ols','o.oppr_lost_id=ols.reason_id','left');
			$this->db->join('lead l',' l.lead_id = o.lead_id');
			$this->db->join('location l1','l.location_id=l1.location_id');
			$this->db->join('location l2','l1.parent_id=l2.location_id');
			$this->db->join('location l3','l2.parent_id=l3.location_id');
			$this->db->join('location l4','l3.parent_id=l4.location_id');
			$this->db->where( 'o.status',7);
			$this->db->where('o.company_id',$this->session->userdata('company'));
			if($searchfilters['role_id']==4||$searchfilters['role_id']==5)
			{
				$this->db->where('l.user_id IN ('.$searchfilters['reportee_users'].')');
			}
			else
			{
				$this->db->where('op.product_id IN ('.$searchfilters['userProducts'].')');
	    		$this->db->where('l.location_id IN ('.$searchfilters['userLocations'].') ');
	    	}
			/*$where='op.product_id in (SELECT wupt.product_id from weekly_user_product_target wupt join fy_week fw on wupt.fy_week_id=fw.fy_week_id  where wupt.status=1 and fw.fy_id='.$fy_year['fy_id'].' and wupt.user_id in('.$user_reportees.'))';
		$this->db->where($where);*/
		//$this->db->where('l.user_id IN ('.$user_id.')');
		
		if($searchfilters['vtime']!='w')
		{
			$this->db->where('DATE(o.closed_time)>=',$row['start_date']);
	        $this->db->where('DATE(o.closed_time)<=',$row['end_date']);
	    }
		elseif($searchfilters['vtime']=='w')
		{
			$this->db->where('date(o.closed_time)',$search_date);
		}
		 if($searchfilters['region']!='')
		{
	    	$this->db->where('l4.location_id',$searchfilters['region']);
	    }
	    $this->db->where('ols.name',$series_name);
	   /* $st_date=$searchfilters['fy_dates']['start_date'];
		$end_date=$searchfilters['fy_dates']['end_date'];
		$this->db->where('osh.opportunity_status_id = (SELECT osh2.opportunity_status_id FROM opportunity_status_history osh2 
				WHERE osh2.opportunity_id = o.opportunity_id AND  DATE(osh2.created_time) BETWEEN "'.$st_date.'" and "'.$end_date.'" ORDER BY osh2.created_time DESC LIMIT 1)');*/
		$this->db->group_by('p.product_id');
	    $r = $this->db->get();
	  //  echo $this->db->last_query();exit;
		return $r->result_array();
	}
	public function get_dropped_product_list($searchfilters,$series_name,$category)
	{   
		$search_date=$category;
		if($searchfilters['vtime']=='y'||$searchfilters['vtime']=='q')
		{   
			$search_date=date('Y-m-d',strtotime($search_date));
			$month_no=date('m',strtotime($search_date));
			$date=date('Y-m-d');
			$row=get_month_start_end_date($date,$month_no);
		}
		elseif($searchfilters['vtime']=='m')
		{
			$row=array();
			$row['start_date']=substr($search_date,7,10);
			$row['end_date']=substr($search_date,21,10);
		}
		else
		{
			$search_date=date('Y-m-d',strtotime($search_date));
		}
		
		$fy_year=get_current_fiancial_year();
		$this->db->select('sum(round((o.required_quantity*p.dp)/100000,2)) as value,o.remarks2 as reason_name,sum(o.required_quantity) as qty,p.name,p.description,pg.name as segment_name');
		
			$this->db->from('opportunity o');
			//$this->db->join('opportunity_status_history osh', 'osh.opportunity_id = o.opportunity_id');
			$this->db->join('opportunity_product op','op.opportunity_id = o.opportunity_id');
			$this->db->join('product p','p.product_id = op.product_id');
			$this->db->join('product_group pg','p.group_id=pg.group_id');
			$this->db->join('lead l',' l.lead_id = o.lead_id');
			$this->db->join('location l1','l.location_id=l1.location_id');
			$this->db->join('location l2','l1.parent_id=l2.location_id');
			$this->db->join('location l3','l2.parent_id=l3.location_id');
			$this->db->join('location l4','l3.parent_id=l4.location_id');
			$this->db->where( 'o.status',8);
			$this->db->where('o.company_id',$this->session->userdata('company'));
			if($searchfilters['role_id']==4||$searchfilters['role_id']==5)
			{
				$this->db->where('l.user_id IN ('.$searchfilters['reportee_users'].')');
			}
			else
			{
				$this->db->where('op.product_id IN ('.$searchfilters['userProducts'].')');
	    		$this->db->where('l.location_id IN ('.$searchfilters['userLocations'].') ');
	    	}
			/*$where='op.product_id in (SELECT wupt.product_id from weekly_user_product_target wupt join fy_week fw on wupt.fy_week_id=fw.fy_week_id  where wupt.status=1 and fw.fy_id='.$fy_year['fy_id'].' and wupt.user_id in('.$user_reportees.'))';
		$this->db->where($where);*/
		//$this->db->where('l.user_id IN ('.$user_id.')');
		
		if($searchfilters['vtime']!='w')
		{
			$this->db->where('DATE(o.closed_time)>=',$row['start_date']);
	        $this->db->where('DATE(o.closed_time)<=',$row['end_date']);
	    }
		elseif($searchfilters['vtime']=='w')
		{
			$this->db->where('date(o.closed_time)',$search_date);
		}
		 if($searchfilters['region']!='')
		{
	    	$this->db->where('l4.location_id',$searchfilters['region']);
	    }
	   /* $st_date=$searchfilters['fy_dates']['start_date'];
		$end_date=$searchfilters['fy_dates']['end_date'];
		$this->db->where('osh.opportunity_status_id = (SELECT osh2.opportunity_status_id FROM opportunity_status_history osh2 
				WHERE osh2.opportunity_id = o.opportunity_id AND  DATE(osh2.created_time) BETWEEN "'.$st_date.'" and "'.$end_date.'" ORDER BY osh2.created_time DESC LIMIT 1)');*/
		$this->db->group_by('p.product_id');
	    $r = $this->db->get();
	  //  echo $this->db->last_query();exit;
		return $r->result_array();
	}
	public function get_funnel_product_list($searchfilters,$series_name,$time_par,$hotDay,$warmDate)
	{
		//echo $hotDay.'--'.$warmDate; exit;
		$fy_year=get_current_fiancial_year();
		if($searchfilters['vtime']!='')
		{
			$parameter="o.created_time";
			if($time_par=='previous')
			{
				$where1=getOppTimelineCheck($searchfilters,$parameter,$fy_year);
			} 
			elseif($time_par=='present')
			{
				$where1=getOppTimelineCheckPresent($searchfilters,$parameter,$fy_year);
			}
			elseif($time_par=='between')
			{    $day = getOpportunityCategorizationDate();
				if($searchfilters['vtime']=='y'||$searchfilters['vtime']=='q')
				{   
					$search_date=date('Y-m-d',strtotime($searchfilters['category_timeline']));
					$month_no=date('m',strtotime($searchfilters['category_timeline']));
					$date=date('Y-m-d');
					$row=get_month_start_end_date($date,$month_no);
				}
				elseif($searchfilters['vtime']=='m')
				{
					$row=array();
					$row['start_date']=substr($searchfilters['category_timeline'],7,10);
					$row['end_date']=substr($searchfilters['category_timeline'],21,10);
				}
				else
				{
					$search_date=date('Y-m-d',strtotime($searchfilters['category_timeline']));
					$row = array('end_date'=>date('Y-m-d'));
				}
			}
		}
		        $this->db->select('sum(o.required_quantity ) as qty,sum(round((o.required_quantity*p.dp)/100000,2)) as value,pg.name as segment_name,p.description,p.name');
		
	            $this->db->from('opportunity o');
	           $this->db->join('opportunity_product op','op.opportunity_id = o.opportunity_id');
				$this->db->join('product p','p.product_id = op.product_id');
				$this->db->join('product_group pg','p.group_id=pg.group_id');
				/*$this->db->join('weekly_user_product_target wupt','p.product_id=wupt.product_id and op.product_id=wupt.product_id');*/
				$this->db->join('lead l',' l.lead_id = o.lead_id');
				$this->db->join('location l1','l.location_id=l1.location_id');
				$this->db->join('location l2','l1.parent_id=l2.location_id');
				$this->db->join('location l3','l2.parent_id=l3.location_id');
				$this->db->join('location l4','l3.parent_id=l4.location_id');
				if($time_par!="between")
				{   
					$this->db->join('opportunity_status_history osh', 'osh.opportunity_id = o.opportunity_id');
					$this->db->where( 'osh.status IN (1,2,3,4,5)');
				}
				if($searchfilters['role_id']==4||$searchfilters['role_id']==5)
				{
					$this->db->where('l.user_id IN ('.$searchfilters['reportee_users'].')');
				}
				else
				{
					$this->db->where('op.product_id IN ('.$searchfilters['userProducts'].')');
		    		$this->db->where('l.location_id IN ('.$searchfilters['userLocations'].') ');
				}
				$this->db->where('o.company_id',$this->session->userdata('company'));
				/*$where='op.product_id in (SELECT wupt.product_id from weekly_user_product_target wupt join fy_week fw on wupt.fy_week_id=fw.fy_week_id  where wupt.status=1 and fw.fy_id='.$fy_year['fy_id'].' and wupt.user_id in('.$user_reportees.'))';
		$this->db->where($where);*/

		if($time_par=='between')
		{
			if($searchfilters['vtime']!='w')
			{
				$this->db->where('date(o.created_time)>=',$row['start_date']);
		        $this->db->where('date(o.created_time)<=',$row['end_date']);
		    }
			elseif($searchfilters['vtime']=='w')
			{
				$this->db->where('date(o.created_time)',$search_date);
			}
			switch ($series_name) {
				case 'Hot':
					$this->db->where('expected_order_conclusion <= CONCAT(YEAR(o.created_time),"-",MONTH(o.created_time),"-",'.$day.')');
					break;
				case 'Warm':
				    $warm_where='(expected_order_conclusion <= DATE_ADD(CONCAT(YEAR(o.created_time),"-",MONTH(o.created_time),"-",'.$day.'),INTERVAL 1 MONTH) AND expected_order_conclusion > CONCAT(YEAR(o.created_time),"-",MONTH(o.created_time),"-",'.$day.'))';
					$this->db->where($warm_where);
					break;
				case 'Cold':
					$cold_where='expected_order_conclusion > DATE_ADD(CONCAT(YEAR(o.created_time),"-",MONTH(o.created_time),"-",'.$day.'),INTERVAL 1 MONTH)';
					$this->db->where($cold_where);
					break;
			}
		}
		else
		{
			if($where1!='')
				$this->db->where($where1);
			switch ($series_name) {
				case 'Hot':
					$this->db->where('o.expected_order_conclusion<=',$hotDay);
					break;
				case 'Warm':
					$this->db->where('o.expected_order_conclusion>',$hotDay);
					$this->db->where('o.expected_order_conclusion<=',$warmDate);
					break;
				case 'Cold':
					$this->db->where('o.expected_order_conclusion>',$warmDate);
					break;
			}
		}
		
		if($searchfilters['region']!='')
	    {
	    	$this->db->where('l4.location_id',$searchfilters['region']);
	    }
	    if($time_par=='previous')
		{
			$st_date=$searchfilters['fy_dates']['start_date'];
			$this->db->where('osh.opportunity_status_id = (SELECT osh2.opportunity_status_id FROM opportunity_status_history osh2 
				WHERE osh2.opportunity_id = o.opportunity_id AND  DATE(osh2.created_time) < "'.$st_date.'" ORDER BY osh2.created_time DESC LIMIT 1)');
	    }
	    elseif($time_par=='present')
	    {
	    	$st_date=$searchfilters['fy_dates']['end_date'];
			$this->db->where('osh.opportunity_status_id = (SELECT osh2.opportunity_status_id FROM opportunity_status_history osh2 
				WHERE osh2.opportunity_id = o.opportunity_id AND  DATE(osh2.created_time) <= "'.$st_date.'" ORDER BY osh2.created_time DESC LIMIT 1)');
	    }
	 		$this->db->group_by('p.product_id');
	    $r = $this->db->get();
	    //echo $this->db->last_query();exit;
		return $r->result_array();
	}
	public function get_closed_won_product_list($searchfilters,$series_name,$category)
	{   
		$search_date=$category;
		if($searchfilters['vtime']=='y'||$searchfilters['vtime']=='q')
		{   
			$search_date=date('Y-m-d',strtotime($search_date));
			$month_no=date('m',strtotime($search_date));
			$date=date('Y-m-d');
			$row=get_month_start_end_date($date,$month_no);
		}
		elseif($searchfilters['vtime']=='m')
		{
			$row=array();
			$row['start_date']=substr($search_date,7,10);
			$row['end_date']=substr($search_date,21,10);
		}
		else
		{
			$search_date=date('Y-m-d',strtotime($search_date));
		}
		$fy_year=get_current_fiancial_year();
		$this->db->select('sum(round((o.required_quantity*p.dp)/100000,2)) as value,sum(o.required_quantity) as qty,p.name,p.description,pg.name as segment_name,cn.status');
		$this->db->from('contract_note cn');
		$this->db->join('contract_note_quote_revision cnqr','cn.contract_note_id=cnqr.contract_note_id');
		$this->db->join('quote_revision qr','cnqr.quote_revision_id=qr.quote_revision_id');
		$this->db->join('quote_details qd','qr.quote_id=qd.quote_id');
		$this->db->join('opportunity o','qd.opportunity_id=o.opportunity_id');
		$this->db->join('opportunity_product op','o.opportunity_id=op.opportunity_id');
		$this->db->join('product p','op.product_id=p.product_id');
		$this->db->join('product_group pg','p.group_id=pg.group_id');
		$this->db->join('lead l','o.lead_id=l.lead_id');
		$this->db->join('location l1','l.location_id=l1.location_id');
		$this->db->join('location l2','l1.parent_id=l2.location_id');
		$this->db->join('location l3','l2.parent_id=l3.location_id');
		$this->db->join('location l4','l3.parent_id=l4.location_id');
		$this->db->where('cn.status in (1,2,3)');
		$this->db->where('cn.company_id',$this->session->userdata('company'));
		if($searchfilters['role_id']==4||$searchfilters['role_id']==5)
		{
			$this->db->where('l.user_id IN ('.$searchfilters['reportee_users'].')');
		}
		else
		{
			$this->db->where('op.product_id IN ('.$searchfilters['userProducts'].')');
    		$this->db->where('l.location_id IN ('.$searchfilters['userLocations'].') ');
    	}
		if($searchfilters['vtime']!='w')
		{
			$this->db->where('DATE(o.closed_time)>=',$row['start_date']);
	        $this->db->where('DATE(o.closed_time)<=',$row['end_date']);
	    }
		elseif($searchfilters['vtime']=='w')
		{
			$this->db->where('date(o.closed_time)',$search_date);
		}
		 if($searchfilters['region']!='')
		{
	    	$this->db->where('l4.location_id',$searchfilters['region']);
	    }
	   $this->db->group_by('p.product_id,cn.status');
	    $r = $this->db->get();
	  //  echo $this->db->last_query();exit;
		return $r->result_array();
	}

	public function get_outstanding_standing_amount($searchfilters,$months_3,$months_6,$months_9,$months_12)
	{
		$fy_year=get_current_fiancial_year();
		/* if($searchfilters['vtime']!='')
		{
			$parameter="cn.created_time"; 
			$where1=getReportTimelineCheck($searchfilters,$parameter,$fy_year);
		}*/
		$user_reportees=$searchfilters['reportee_users'];
		$userProducts=$searchfilters['userProducts'];
	    $userLocations=$searchfilters['userLocations'];
		$qry = 'SELECT sum(CASE WHEN date(cn.modified_time)>="'.$months_3.'" then cn.outstanding_amount else 0 end) as month3,
				sum(CASE WHEN date(cn.modified_time)>="'.$months_6.'" and date(cn.modified_time)< "'.$months_3.'" then cn.outstanding_amount else 0 end) as month6,
				sum(CASE WHEN date(cn.modified_time)>="'.$months_9.'" and date(cn.modified_time)<"'.$months_6.'" then cn.outstanding_amount else 0 end) as month9,
				sum(CASE WHEN date(cn.modified_time)>="'.$months_12.'" and date(cn.modified_time)<"'.$months_9.'" then cn.outstanding_amount else 0 end) as month12,
				sum(CASE WHEN date(cn.modified_time)<"'.$months_12.'" then cn.outstanding_amount else 0 end) as gt_year
				FROM contract_note cn
				INNER JOIN (
							SELECT cn1.contract_note_id
							FROM `contract_note` `cn1`
							JOIN `contract_note_quote_revision` `cnqr` ON `cn1`.`contract_note_id`=`cnqr`.`contract_note_id`
							JOIN `quote_revision` `qr` ON `cnqr`.`quote_revision_id`=`qr`.`quote_revision_id`
							JOIN `quote_details` `qd` ON `qr`.`quote_id`=`qd`.`quote_id`
							JOIN `opportunity` `o` ON `qd`.`opportunity_id`=`o`.`opportunity_id`
							JOIN `opportunity_product` `op` ON `o`.`opportunity_id`=`op`.`opportunity_id`
							JOIN `product` `p` ON `op`.`product_id`=`p`.`product_id`
							JOIN `lead` `l` ON `o`.`lead_id`=`l`.`lead_id`
							JOIN `customer` `c` ON `l`.`customer_id`=`c`.`customer_id`
							JOIN `customer_category` `cc` ON `c`.`category_id`=`cc`.`category_id`
							JOIN `location` `l1` ON `l`.`location_id`=`l1`.`location_id`
							JOIN `location` `l2` ON `l1`.`parent_id`=`l2`.`location_id`
							JOIN `location` `l3` ON `l2`.`parent_id`=`l3`.`location_id`
							JOIN `location` `l4` ON `l3`.`parent_id`=`l4`.`location_id`
							WHERE `cn1`.`status` = 2
							AND `l`.`user_id` in ('.$user_reportees.')
							AND `op`.`product_id` IN ('.$userProducts.')
							AND `l`.`location_id` IN ('.$userLocations.') ';
							if($searchfilters['region']!='')
						    {   
						    	$qry .= ' AND l4.location_id = '.$searchfilters['region'].' ';
						    }
						    if($searchfilters['sector']!='')
						    {   
						    	$qry .= ' AND cc.category_id = '.(int)$searchfilters['sector'].' ';
				    		}
							$qry .= ' GROUP BY cn1.contract_note_id
				) t ON cn.contract_note_id = t.contract_note_id';

		/*$this->db->select('sum(CASE WHEN date(cn.modified_time)>="'.$months_3.'" then cn.outstanding_amount else 0 end) as month3,sum(CASE WHEN date(cn.modified_time)>="'.$months_6.'" and date(cn.modified_time)< "'.$months_3.'" then cn.outstanding_amount else 0 end) as month6,sum(CASE WHEN date(cn.modified_time)>="'.$months_9.'" and date(cn.modified_time)<"'.$months_6.'" then cn.outstanding_amount else 0 end) as month9,sum(CASE WHEN date(cn.modified_time)>="'.$months_12.'" and date(cn.modified_time)<"'.$months_9.'" then cn.outstanding_amount else 0 end) as month12,sum(CASE WHEN date(cn.modified_time)<"'.$months_12.'" then cn.outstanding_amount else 0 end) as gt_year');
		$this->db->from('contract_note cn');
		$this->db->join('contract_note_quote_revision cnqr','cn.contract_note_id=cnqr.contract_note_id');
		$this->db->join('quote_revision qr','cnqr.quote_revision_id=qr.quote_revision_id');
		$this->db->join('quote_details qd','qr.quote_id=qd.quote_id');
		$this->db->join('opportunity o','qd.opportunity_id=o.opportunity_id');
		$this->db->join('opportunity_product op','o.opportunity_id=op.opportunity_id');
		$this->db->join('product p','op.product_id=p.product_id');
		$this->db->join('lead l','o.lead_id=l.lead_id');
		$this->db->join('customer c','l.customer_id=c.customer_id');
		$this->db->join('customer_category cc','c.category_id=cc.category_id');
		$this->db->join('location l1','l.location_id=l1.location_id');
		$this->db->join('location l2','l1.parent_id=l2.location_id');
		$this->db->join('location l3','l2.parent_id=l3.location_id');
		$this->db->join('location l4','l3.parent_id=l4.location_id');
		$this->db->where('cn.status',2);
		
		
		$user_reportees=$searchfilters['reportee_users'];
	    $this->db->where('l.user_id in ('.$user_reportees.')');
	    if($searchfilters['region']!='')
	    {   
	    	$this->db->where('l4.location_id',$searchfilters['region']);
	    }
	    if($searchfilters['sector']!='')
	    {   
	    	$this->db->where('cc.category_id',(int)$searchfilters['sector']);
	    }
		$userProducts=$searchfilters['userProducts'];
	    $userLocations=$searchfilters['userLocations'];
	    $this->db->where('op.product_id IN ('.$userProducts.')');
		$this->db->where('l.location_id IN ('.$userLocations.') ');
		$res=$this->db->get();*/
		$res = $this->db->query($qry);
		//echo $this->db->last_query();exit;
		return $res->row_array();
	}

	public function get_outstanding_sales_amount($searchfilters,$months_3,$months_6,$months_9,$months_12)
	{
		$fy_year=get_custom_current_fiancial_year();
		/*if($searchfilters['vtime']!='')
		{
			$parameter="cn.created_time"; 
			$where1=getCustomOppTimelineCheck($searchfilters,$parameter,$fy_year);
		}*/
		$this->db->select('ROUND(SUM(CASE WHEN date(cn.modified_time)>="'.$months_3.'" then ( CASE WHEN ma.margin_approval_id IS NULL THEN ((o.required_quantity*qd.mrp)*(1-qr.discount/100))/100000 ELSE ( CASE WHEN ma.discount_type = 1 THEN ((o.required_quantity*qd.mrp)*(1-ma.discount/100))/100000 ELSE ((o.required_quantity*qd.mrp)-ma.discount)/100000 END ) END ) else 0 end ) ,2) AS sm3,ROUND(SUM(CASE WHEN date(cn.modified_time)>="'.$months_6.'" and date(cn.modified_time)< "'.$months_3.'" then ( CASE WHEN ma.margin_approval_id IS NULL THEN ((o.required_quantity*qd.mrp)*(1-qr.discount/100))/100000 ELSE ( CASE WHEN ma.discount_type = 1 THEN ((o.required_quantity*qd.mrp)*(1-ma.discount/100))/100000 ELSE ((o.required_quantity*qd.mrp)-ma.discount)/100000 END ) END ) else 0 end ) ,2) AS sm6,ROUND(SUM(CASE WHEN date(cn.modified_time)>="'.$months_9.'" and date(cn.modified_time)< "'.$months_6.'" then ( CASE WHEN ma.margin_approval_id IS NULL THEN ((o.required_quantity*qd.mrp)*(1-qr.discount/100))/100000 ELSE ( CASE WHEN ma.discount_type = 1 THEN ((o.required_quantity*qd.mrp)*(1-ma.discount/100))/100000 ELSE ((o.required_quantity*qd.mrp)-ma.discount)/100000 END ) END ) else 0 end ) ,2) AS sm9,ROUND(SUM(CASE WHEN date(cn.modified_time)>="'.$months_12.'" and date(cn.modified_time)< "'.$months_9.'" then ( CASE WHEN ma.margin_approval_id IS NULL THEN ((o.required_quantity*qd.mrp)*(1-qr.discount/100))/100000 ELSE ( CASE WHEN ma.discount_type = 1 THEN ((o.required_quantity*qd.mrp)*(1-ma.discount/100))/100000 ELSE ((o.required_quantity*qd.mrp)-ma.discount)/100000 END ) END ) else 0 end ) ,2) AS sm12,ROUND(SUM(CASE WHEN date(cn.modified_time)<"'.$months_12.'" then ( CASE WHEN ma.margin_approval_id IS NULL THEN ((o.required_quantity*qd.mrp)*(1-qr.discount/100))/100000 ELSE ( CASE WHEN ma.discount_type = 1 THEN ((o.required_quantity*qd.mrp)*(1-ma.discount/100))/100000 ELSE ((o.required_quantity*qd.mrp)-ma.discount)/100000 END ) END ) else 0 end ) ,2) AS s_gtyear');
		
		$this->db->from('contract_note cn');
		$this->db->join('contract_note_quote_revision cnqr','cn.contract_note_id=cnqr.contract_note_id');
		$this->db->join('quote_revision qr','cnqr.quote_revision_id=qr.quote_revision_id');
		$this->db->join('quote_details qd','qr.quote_id=qd.quote_id');
		$this->db->join('opportunity o','qd.opportunity_id=o.opportunity_id');
		$this->db->join('opportunity_product op','o.opportunity_id=op.opportunity_id');
		$this->db->join('product p','op.product_id=p.product_id');
		/*$this->db->join('weekly_user_product_target wupt','p.product_id=wupt.product_id and op.product_id=wupt.product_id');*/
		$this->db->join('product_group pg ','p.group_id=pg.group_id');
		$this->db->join('lead l','o.lead_id=l.lead_id');
		$this->db->join('customer c','l.customer_id=c.customer_id');
		$this->db->join('customer_category cc','c.category_id=cc.category_id');
		$this->db->join('location l1','l.location_id=l1.location_id');
		$this->db->join('location l2','l1.parent_id=l2.location_id');
		$this->db->join('location l3','l2.parent_id=l3.location_id');
		$this->db->join('location l4','l3.parent_id=l4.location_id');
		$this->db->join('quote_op_margin_approval ma','ma.quote_revision_id = qr.quote_revision_id AND o.opportunity_id = ma.opportunity_id','LEFT');
		$this->db->where('cn.status',2);
		/*$where='op.product_id in (SELECT wupt.product_id from weekly_user_product_target wupt join fy_week fw on wupt.fy_week_id=fw.fy_week_id  where wupt.status=1 and fw.fy_id='.$fy_year['fy_id'].' and wupt.user_id in('.$user_reportees.'))';
		$this->db->where($where);*/

		/*if($searchfilters['vtime']!='')
		{
			if($where1!='')
				$this->db->where($where1);
		}*/
		$user_reportees=$searchfilters['reportee_users'];
	    $this->db->where('l.user_id in ('.$user_reportees.')');
	     if($searchfilters['region']!='')
	    {
	    	$this->db->where('l4.location_id',$searchfilters['region']);
	    }
	   	 if($searchfilters['sector']!='')
	    {   
	    	$this->db->where('cc.category_id',(int)$searchfilters['sector']);
	    }
	    $userProducts=$searchfilters['userProducts'];
	    $userLocations=$searchfilters['userLocations'];
	    $this->db->where('op.product_id IN ('.$userProducts.')');
		$this->db->where('l.location_id IN ('.$userLocations.') ');
		 $res=$this->db->get();
	    return $res->row_array();
	}
	public function get_customer_ot_amount($searchfilters,$start_date,$end_date)
	{
		$fy_year=get_current_fiancial_year();
		/* if($searchfilters['vtime']!='')
		{
			$parameter="cn.created_time"; 
			$where1=getReportTimelineCheck($searchfilters,$parameter,$fy_year);
		}*/
		$user_reportees=$searchfilters['reportee_users'];
		$userProducts=$searchfilters['userProducts'];
	    $userLocations=$searchfilters['userLocations'];
		$qry = 'SELECT t.name, sum(cn1.outstanding_amount) as ot_amount
				from contract_note cn1
				Inner join 
				( SELECT 
				c.name, cn.contract_note_id,c.customer_id
				FROM `contract_note` `cn`
				left outer JOIN `contract_note_quote_revision` `cnqr` ON `cn`.`contract_note_id`=`cnqr`.`contract_note_id`
				left outer JOIN `quote_revision` `qr` ON `cnqr`.`quote_revision_id`=`qr`.`quote_revision_id`
				left outer JOIN `quote_details` `qd` ON `qr`.`quote_id`=`qd`.`quote_id`
				left outer JOIN `opportunity` `o` ON `qd`.`opportunity_id`=`o`.`opportunity_id`
				left outer JOIN `opportunity_product` `op` ON `o`.`opportunity_id`=`op`.`opportunity_id`
				left outer JOIN `product` `p` ON `op`.`product_id`=`p`.`product_id`
				left outer JOIN `lead` `l` ON `o`.`lead_id`=`l`.`lead_id`
				left outer JOIN `customer` `c` ON `l`.`customer_id`=`c`.`customer_id`
				left outer JOIN `customer_category` `cc` ON `c`.`category_id`=`cc`.`category_id`
				JOIN `location` `l1` ON `l`.`location_id`=`l1`.`location_id`
				JOIN `location` `l2` ON `l1`.`parent_id`=`l2`.`location_id`
				JOIN `location` `l3` ON `l2`.`parent_id`=`l3`.`location_id`
				JOIN `location` `l4` ON `l3`.`parent_id`=`l4`.`location_id`
				WHERE `cn`.`status` = 2
				AND `cn`.`outstanding_amount` >0
				AND `l`.`user_id` IN ('.$user_reportees.')
				AND `op`.`product_id` IN ('.$userProducts.')
				AND `l`.`location_id` IN ('.$userLocations.') ';
				if($start_date!='')
				{
					$qry .= ' AND date(cn.modified_time) >= "'.$start_date.'" ';
				}
				if($end_date!='')
				{
					$qry .= ' AND date(cn.modified_time) < "'.$end_date.'" ';
				}
				if($searchfilters['region']!='')
			    {   
			    	$qry .= ' AND l4.location_id =  "'.$searchfilters['region'].'" ';
			    }
			    if($searchfilters['sector']!='')
			    {   
			    	$qry .= ' AND cc.category_id =  '.(int)$searchfilters['sector'].' ';
			    }
				$qry .= ' 
				GROUP BY cn.contract_note_id
				order by c.customer_id, cn.contract_note_id
				) t on t.contract_note_id = cn1.contract_note_id
				GROUP BY t.`customer_id`';
		/*$this->db->select('c.name,sum(cn.outstanding_amount) as ot_amount');
		$this->db->from('contract_note cn');
		$this->db->join('contract_note_quote_revision cnqr','cn.contract_note_id=cnqr.contract_note_id');
		$this->db->join('quote_revision qr','cnqr.quote_revision_id=qr.quote_revision_id');
		$this->db->join('quote_details qd','qr.quote_id=qd.quote_id');
		$this->db->join('opportunity o','qd.opportunity_id=o.opportunity_id');
		$this->db->join('opportunity_product op','o.opportunity_id=op.opportunity_id');
		$this->db->join('product p','op.product_id=p.product_id');
		$this->db->join('lead l','o.lead_id=l.lead_id');
		$this->db->join('customer c','l.customer_id=c.customer_id');
		$this->db->join('customer_category cc','c.category_id=cc.category_id');
		$this->db->join('location l1','l.location_id=l1.location_id');
		$this->db->join('location l2','l1.parent_id=l2.location_id');
		$this->db->join('location l3','l2.parent_id=l3.location_id');
		$this->db->join('location l4','l3.parent_id=l4.location_id');
		$this->db->where('cn.status',2);
		$this->db->where('cn.outstanding_amount >',0);
		$user_reportees=$searchfilters['reportee_users'];
	    $this->db->where('l.user_id in ('.$user_reportees.')');
	    if($searchfilters['region']!='')
	    {   
	    	$this->db->where('l4.location_id',$searchfilters['region']);
	    }
	    if($searchfilters['sector']!='')
	    {   
	    	$this->db->where('cc.category_id',(int)$searchfilters['sector']);
	    }
		$userProducts=$searchfilters['userProducts'];
	    $userLocations=$searchfilters['userLocations'];
	    $this->db->where('op.product_id IN ('.$userProducts.')');
		$this->db->where('l.location_id IN ('.$userLocations.') ');
		if($start_date!='')
		{
			$this->db->where('date(cn.modified_time)>=',$start_date);
		}
		if($end_date!='')
		{
			$this->db->where('date(cn.modified_time)<',$end_date);
		}
		$this->db->group_by('c.customer_id');
	    $res=$this->db->get();*/
	    $res = $this->db->query($qry);
		// /echo $this->db->last_query();exit;

		return $res->result_array();
	}
	public function get_customer_ot_amount_details($searchfilters,$start_date,$end_date,$category)
	{
		$fy_year=get_current_fiancial_year();
		/* if($searchfilters['vtime']!='')
		{
			$parameter="cn.created_time"; 
			$where1=getReportTimelineCheck($searchfilters,$parameter,$fy_year);
		}*/
		$this->db->select('ROUND(SUM( CASE WHEN ma.margin_approval_id IS NULL THEN (o.required_quantity*qd.mrp)*(1-qr.discount/100) ELSE ( CASE WHEN ma.discount_type = 1 THEN (o.required_quantity*qd.mrp)*(1-ma.discount/100) ELSE (o.required_quantity*qd.mrp)-ma.discount END ) END )) AS total_orders,p.product_id,cn.contract_note_id,sum(o.required_quantity) as qty,pg.name,p.description,c.name as c_name,GROUP_CONCAT(CONCAT("(",pg.name,")",p.description,"( Qty- ",o.required_quantity,")") SEPARATOR ",") as product_details,c.customer_id,(cn.outstanding_amount) as outstanding_amount,cus_loc.location as cus_location,u.first_name');
		$this->db->from('contract_note cn');
		$this->db->join('contract_note_quote_revision cnqr','cn.contract_note_id=cnqr.contract_note_id');
		$this->db->join('quote_revision qr','cnqr.quote_revision_id=qr.quote_revision_id');
		$this->db->join('quote_details qd','qr.quote_id=qd.quote_id');
		$this->db->join('opportunity o','qd.opportunity_id=o.opportunity_id');
		$this->db->join('opportunity_product op','o.opportunity_id=op.opportunity_id');
		$this->db->join('product p','op.product_id=p.product_id');
		$this->db->join('product_group pg','p.group_id=pg.group_id');
		/*$this->db->join('weekly_user_product_target wupt','p.product_id=wupt.product_id and op.product_id=wupt.product_id');*/
		$this->db->join('lead l','o.lead_id=l.lead_id');
		$this->db->join('user u','l.user_id=u.user_id');
		$this->db->join('customer c','l.customer_id=c.customer_id');
		$this->db->join('customer_location cl','c.customer_id=cl.customer_id');
		$this->db->join('location cus_loc','cl.location_id=cus_loc.location_id');
		$this->db->join('customer_category cc','c.category_id=cc.category_id');
		$this->db->join('location l1','l.location_id=l1.location_id');
		$this->db->join('location l2','l1.parent_id=l2.location_id');
		$this->db->join('location l3','l2.parent_id=l3.location_id');
		$this->db->join('location l4','l3.parent_id=l4.location_id');
		$this->db->join('quote_op_margin_approval ma','ma.quote_revision_id = qr.quote_revision_id AND o.opportunity_id = ma.opportunity_id','LEFT');
		$this->db->where('cn.status',2);
		$this->db->where('cn.outstanding_amount >',0);
		$user_reportees=$searchfilters['reportee_users'];
	    $this->db->where('l.user_id in ('.$user_reportees.')');
	    if($searchfilters['region']!='')
	    {   
	    	$this->db->where('l4.location_id',$searchfilters['region']);
	    }
	    if($searchfilters['sector']!='')
	    {   
	    	$this->db->where('cc.category_id',(int)$searchfilters['sector']);
	    }
		$userProducts=$searchfilters['userProducts'];
	    $userLocations=$searchfilters['userLocations'];
	    $this->db->where('op.product_id IN ('.$userProducts.')');
		$this->db->where('l.location_id IN ('.$userLocations.') ');
		if($start_date!='')
		{
			$this->db->where('date(cn.modified_time)>=',$start_date);
		}
		if($end_date!='')
		{
			$this->db->where('date(cn.modified_time)<',$end_date);
		}
		$this->db->where('c.name',$category);
		$this->db->group_by('cn.contract_note_id');
	    $res=$this->db->get();
		//echo $this->db->last_query();exit;
		return $res->result_array();
	}
	public function get_run_rate_funnel_opportunites($searchfilters,$row)
	{   
		$fy_year=get_current_fiancial_year();
		if($searchfilters['vtime']=='q' || $searchfilters['vtime']=='y')
		{
			$this->db->select('sum(round((o.required_quantity*p.dp)/100000,2)) as opened_value,
				DATE_FORMAT(o.created_time,"%b-%y") as timeline,month(o.created_time) as ctime');
		}
		$this->db->from('opportunity o');
		$this->db->join('opportunity_status_history osh', 'osh.opportunity_id = o.opportunity_id');
		$this->db->join('opportunity_product op','op.opportunity_id = o.opportunity_id');
		$this->db->join('product p','p.product_id = op.product_id');
		$this->db->join('product_group pg','p.group_id=pg.group_id');
		$this->db->join('lead l',' l.lead_id = o.lead_id');
		$this->db->join('location l1','l.location_id=l1.location_id');
		$this->db->join('location l2','l1.parent_id=l2.location_id');
		$this->db->join('location l3','l2.parent_id=l3.location_id');
		$this->db->join('location l4','l3.parent_id=l4.location_id');
		$this->db->where( 'osh.status IN (1,2,3,4,5)');
		$this->db->where('o.company_id',$this->session->userdata('company'));
		if($searchfilters['category_id']!='')
		{
			$this->db->where('pg.category_id',$searchfilters['category_id']);
		}
		if($searchfilters['product_id']!='')
		{
			$this->db->where('p.product_id',$searchfilters['product_id']);
		}
		if($searchfilters['role_id']==4||$searchfilters['role_id']==5)
		{
			$this->db->where('l.user_id IN ('.$searchfilters['user_reportees_tvs'].')');
		}
		else
		{
			$this->db->where('op.product_id IN ('.$searchfilters['userProducts'].')');
    		$this->db->where('l.location_id IN ('.$searchfilters['userLocations'].') ');
    	}
		$this->db->where('date(o.expected_order_conclusion) <=',$row['end_date']);
		if($searchfilters['region']!='')
	    {
	    	$this->db->where('l4.location_id',$searchfilters['region']);
	    }
	    $this->db->where('osh.opportunity_status_id = (SELECT osh2.opportunity_status_id FROM opportunity_status_history osh2 
				WHERE osh2.opportunity_id = o.opportunity_id AND  DATE(osh2.created_time) <= "'.$row['end_date'].'" ORDER BY osh2.created_time DESC LIMIT 1)');
	    $r = $this->db->get();
	    return $r->row_array();
	}

	public function get_funnel_sales($searchfilters)
	{
		$fy_year = get_current_fiancial_year();
		 if($searchfilters['vtime']!='')
		{
			$parameter="cn.created_time"; 
			$where1=getReportTimelineCheck($searchfilters,$parameter,$fy_year);
		}
		$this->db->select('ROUND(SUM( CASE WHEN ma.margin_approval_id IS NULL THEN ((o.required_quantity*qd.total_value)*(1-qr.discount/100))/100000 ELSE ( CASE WHEN ma.discount_type = 1 THEN ((o.required_quantity*qd.total_value)*(1-ma.discount/100))/100000 ELSE ((o.required_quantity*qd.total_value)-(ma.discount*qd.currency_factor))/100000 END ) END ),2) AS current_sales,DATE_FORMAT(cn.created_time,"%b-%y") as timeline,month(cn.created_time) as ctime');
		
		$this->db->from('contract_note cn');
		$this->db->join('contract_note_quote_revision cnqr','cn.contract_note_id=cnqr.contract_note_id');
		$this->db->join('quote_revision qr','cnqr.quote_revision_id=qr.quote_revision_id');
		$this->db->join('quote_details qd','qr.quote_id=qd.quote_id');
		$this->db->join('opportunity o','qd.opportunity_id=o.opportunity_id');
		$this->db->join('opportunity_product op','o.opportunity_id=op.opportunity_id');
		$this->db->join('product p','op.product_id=p.product_id');
		/*$this->db->join('weekly_user_product_target wupt','p.product_id=wupt.product_id and op.product_id=wupt.product_id');*/
		$this->db->join('product_group pg ','p.group_id=pg.group_id');
		$this->db->join('lead l','o.lead_id=l.lead_id');
		$this->db->join('location l1','l.location_id=l1.location_id');
		$this->db->join('location l2','l1.parent_id=l2.location_id');
		$this->db->join('location l3','l2.parent_id=l3.location_id');
		$this->db->join('location l4','l3.parent_id=l4.location_id');
		$this->db->join('quote_op_margin_approval ma','ma.quote_revision_id = qr.quote_revision_id AND o.opportunity_id = ma.opportunity_id','LEFT');
		if($searchfilters['vtime']!='')
		{
			if($where1!='')
				$this->db->where($where1);
		}
		if($searchfilters['region']!='')
	    {
	    	$this->db->where('l4.location_id',$searchfilters['region']);
	    }
	    if($searchfilters['role_id']==4||$searchfilters['role_id']==5)
		{
			$this->db->where('l.user_id IN ('.$searchfilters['user_reportees_tvs'].')');
		}
		else
		{
			$this->db->where('op.product_id IN ('.$searchfilters['userProducts'].')');
    		$this->db->where('l.location_id IN ('.$searchfilters['userLocations'].') ');
    	}
    	if($searchfilters['category_id']!='')
    	{
    		$this->db->where('pg.category_id',$searchfilters['category_id']);
    	}
    	if($searchfilters['product_id']!='')
		{
			$this->db->where('p.product_id',$searchfilters['product_id']);
		}
		$this->db->where('cn.company_id',$this->session->userdata('company'));
    	$this->db->group_by('month(cn.created_time)');
	    $res=$this->db->get();
	//	echo $this->db->last_query();exit;
		return $res->result_array();
	}
	public function get_role_based_users($role_id,$user_id)
	{
		$this->db->select('*');
		$this->db->from('user');
		$this->db->where('role_id',$role_id);
		$this->db->where('user_id in ('.$user_id.')');
		$this->db->where('status',1);
		$res=$this->db->get();
		return $res->result_array();
	}
	public function get_product_category($userProducts)
	{
		$this->db->select('pc.category_id,pc.name');
		$this->db->from('product_category pc');
		$this->db->join('product_group pg','pg.category_id=pc.category_id');
		$this->db->join('product p','p.group_id=pg.group_id');
		$this->db->where_in('p.product_id',$userProducts);
		//$this->db->group_by('pc.category_id');
		$res=$this->db->get();
		return $res->result_array();

	}

	public function get_previous_financial_year()
	{
		$this->db->select('*');
		$this->db->from('financial_year');
		$this->db->where('company_id',$this->session->userdata('company'));
		$this->db->order_by('start_date desc');
		$res=$this->db->get();
		return $res->result_array();
	}

	public function get_incentives_outstanding($users_id,$searchfilters,$role_id,$userProducts,$userLocations)
	{
		$this->db->select('sum(cn.outstanding_amount) as outstanding_amount');
		$this->db->from('contract_note cn');
		$this->db->join('contract_note_quote_revision cnqr','cn.contract_note_id=cnqr.contract_note_id');
		$this->db->join('quote_revision qr','cnqr.quote_revision_id=qr.quote_revision_id');
		$this->db->join('quote_details qd','qr.quote_id=qd.quote_id');
		$this->db->join('opportunity o','qd.opportunity_id=o.opportunity_id');
		$this->db->join('opportunity_product op','o.opportunity_id=op.opportunity_id');
		$this->db->join('product p','op.product_id=p.product_id');
		$this->db->join('lead l','o.lead_id=l.lead_id');
		$this->db->join('customer c','l.customer_id=c.customer_id');
		$this->db->join('customer_category cc','c.category_id=cc.category_id');
		$this->db->join('location l1','l.location_id=l1.location_id');
		$this->db->join('location l2','l1.parent_id=l2.location_id');
		$this->db->join('location l3','l2.parent_id=l3.location_id');
		$this->db->join('location l4','l3.parent_id=l4.location_id');
		
	    if($searchfilters['regions']!='')
	    {   
	    	$this->db->where('l4.location_id',$searchfilters['regions']);
	    }
	    $this->db->where('l.user_id in ('.$users_id.')');
	   
	    $this->db->where('op.product_id IN ('.$userProducts.')');
		$this->db->where('l.location_id IN ('.$userLocations.') ');
	   
		$this->db->where('cn.created_time>=',$searchfilters['from_date']);
		$this->db->where('cn.created_time<=',$searchfilters['to_date']);
		$res=$this->db->get();
		//echo $this->db->last_query();exit;
		return $res->row_array();
	}

	public function get_incentive_user_sales($users_id,$searchfilters,$userLocations,$userProducts,$category_id,$role_id)
	{

		$this->db->select('ROUND(SUM( CASE WHEN ma.margin_approval_id IS NULL THEN ((o.required_quantity*qd.total_value)*(1-qr.discount/100))/100000 ELSE ( CASE WHEN ma.discount_type = 1 THEN ((o.required_quantity*qd.total_value)*(1-ma.discount/100))/100000 ELSE ((o.required_quantity*qd.total_value)-(ma.discount*qd.currency_factor))/100000 END ) END ),2) AS current_sales,l4.location as region');
		$this->db->from('contract_note cn');
		$this->db->join('contract_note_quote_revision cnqr','cn.contract_note_id=cnqr.contract_note_id');
		$this->db->join('quote_revision qr','cnqr.quote_revision_id=qr.quote_revision_id');
		$this->db->join('quote_details qd','qr.quote_id=qd.quote_id');
		$this->db->join('opportunity o','qd.opportunity_id=o.opportunity_id');
		$this->db->join('opportunity_product op','o.opportunity_id=op.opportunity_id');
		$this->db->join('product p','op.product_id=p.product_id');
		if($category_id !='')
		{
			$this->db->join('product_group pg','pg.group_id=p.group_id');
		}
		$this->db->join('lead l','o.lead_id=l.lead_id');
		$this->db->join('location l1','l.location_id=l1.location_id');
		$this->db->join('location l2','l1.parent_id=l2.location_id');
		$this->db->join('location l3','l2.parent_id=l3.location_id');
		$this->db->join('location l4','l3.parent_id=l4.location_id');
		$this->db->join('quote_op_margin_approval ma','ma.quote_revision_id = qr.quote_revision_id AND o.opportunity_id = ma.opportunity_id','LEFT');
		if($searchfilters['regions']!='')
	    {   
	    	$this->db->where('l4.location_id',$searchfilters['regions']);
	    }
	    $this->db->where('l.user_id in ('.$users_id.')');
	   
    	$this->db->where('op.product_id IN ('.$userProducts.')');
		$this->db->where('l.location_id IN ('.$userLocations.') ');
	    $this->db->where('cn.company_id',$this->session->userdata('company'));
		$this->db->where('cn.created_time>=',$searchfilters['from_date']);
		$this->db->where('cn.created_time<=',$searchfilters['to_date']);
		if($category_id !='')
		{
			$this->db->where('pg.category_id',$category_id);
		}
	    $res=$this->db->get();
		//echo $this->db->last_query();exit;
		return $res->row_array();
	}
	public function get_incentive_user_target($users_id,$searchfilters,$category_id)
	{
		$fy_year=get_current_fiancial_year();
		if($searchfilters['regions']!='')
	    {
	    	$search_by_region=report_user_locations($searchfilters);
			//$this->db->where('wupt.user_id in ('.$search_by_region.')');
	    }
	    $this->db->select('round(sum(p.mrp*wupt.quantity)/100000,2) as current_target');
		$this->db->from('fy_week fw');
		$this->db->join('weekly_user_product_target wupt','fw.fy_week_id=wupt.fy_week_id');
		$this->db->join('user u','wupt.user_id=u.user_id');
		$this->db->join('product p','wupt.product_id=p.product_id');
		if($category_id !='')
		{
			$this->db->join('product_group pg','pg.group_id=p.group_id');
			$this->db->where('pg.category_id',$category_id);
		}
		$this->db->where('wupt.user_id in ('.$users_id.')');
	    if($searchfilters['regions']!='')
	    {
	    	//$search_by_region=report_user_locations($searchfilters);
	    	if($search_by_region=='')
	    	{
	    		$search_by_region=0;
	    	}
			$this->db->where('wupt.user_id in ('.$search_by_region.')');
	    }
		$this->db->where('fw.start_date>=',$searchfilters['from_date']);
		$this->db->where('fw.start_date<=',$searchfilters['to_date']);
		$res=$this->db->get();
		//echo $this->db->last_query();exit;
		return $res->row_array();
	}

	public function get_all_roles($user_id)
	{
		$role_id=array(4,6,7,8);
		$this->db->select('r.*');
		$this->db->from('role r');
		$this->db->join('user u','u.role_id=r.role_id');
		$this->db->where_in('r.role_id',$role_id);
		$this->db->where('u.user_id in ('.$user_id.')');
		$this->db->group_by('r.role_id');
		$res=$this->db->get();
		return $res->result_array();
	}
	function get_dependent_products($category_id)
	{   
		$qry_data='';
		$this->db->select('p.*');
		$this->db->from('product p');
		$this->db->join('product_group pg','pg.group_id=p.group_id');
		if($category_id!='')
		{
			$this->db->where('pg.category_id',$category_id);
		}
		$this->db->where('p.status',1);
		$res=$this->db->get();
		$users_id =$res->result_array();

		$qry_data.='<option value="">Select Product</option>';
		foreach($users_id as $us)
		{   
			$qry_data.='<option value="'.$us['product_id'].'">'.$us['description'].'</option>';
		}
		return $qry_data;
	}
	function get_product_data($cat_id)
	{
		$this->db->select('p.*');
		$this->db->from('product p');
		$this->db->join('product_group pg','pg.group_id=p.group_id');
		$this->db->Where('p.company_id',$this->session->userdata('company'));
		$this->db->where('p.status',1);
		if($cat_id!='')
		{
			$this->db->where('pg.category_id',$cat_id);
		}
		$res=$this->db->get();
		return $res->result_array();
	}
	
	function get_product_segments()
	{
		$this->db->select('pg.*');
		$this->db->from('product_group pg');
		$this->db->join('product_category pc','pg.category_id=pc.category_id');
		$this->db->Where('pc.company_id',$this->session->userdata('company'));
		$this->db->where('pc.status',1);
		
		$res=$this->db->get();
		return $res->result_array();
	}
	function get_latest_bulk_upload_date()
	{
		$this->db->select('max(created_time) as as_on_date');
		$this->db->from('upload_csv');
		$this->db->where('company_id',$this->session->userdata('company'));
		$this->db->where('type',1);
		$this->db->limit(1);
		$res=$this->db->get();
		$res1= $res->row_array();
		return $res1['as_on_date'];
	}

	public function visitResults($searchParams, $per_page, $current_offset)
	{
		// echo '<pre>'; print_r($searchParams);die;
		//print_r($this->session->userdata());die;
		$role_id = $this->session->userdata('role_id');
		$user = $this->Common_model->get_data('user',array('user_id'=>$this->session->userdata('user_id'))); 
		$branch_id = $user[0]['branch_id'];
		
		// Logged User Branch 
		$branch_details = $this->Common_model->get_data('branch',array('branch_id'=>$branch_id)); 
		// Logged User Region 
		$region_details = $this->Common_model->get_data('location',array('parent_id'=>$branch_details[0]['region_id'])); 
		
		foreach($region_details as $row){
			// $branch_details[] = $this->Common_model->get_data_row('branch',array('region_id'=>$row['parent_id'])); 
			$region_ids[] = $row['parent_id'];
		}
		$ids = implode(",",array_unique($region_ids));
		$this->db->select('branch_id');
		$this->db->from('branch');
		$this->db->where_in('region_id', explode(',', $ids));
		$res = $this->db->get();
		$region_branches = $res->result_array();
		// getting branch id's
		$result_ids = array_column($region_branches, 'branch_id');
		$branch_ids = implode(",",$result_ids);

		// echo $user['branch_id'];die;		
		$this->db->select('v.*, IF(v.end_date < CURDATE(), "1", "0") is_expired, CONCAT("Lead ID - ",l.lead_number," (",c.name," "," (",loc.location,"))") as CustomerName, vp.name as Purpose,b.name as branch,CONCAT(u.first_name," ",u.last_name) as lead_owner,(CASE WHEN (v.status = 3)  THEN "Completed" WHEN (v.status = 1)  THEN "Planned" WHEN v.status = 2 THEN "Cancelled" WHEN (v.status = 4)  THEN "Postponed" END) AS status, (case when (v.dealer_id is not null and  v.dealer_id != "") then CONCAT("Dealer - ",d.distributor_name) 
		when (v.customer_id is not null and v.customer_id != "") then CONCAT("Customer - ",c.name) when (v.lead_id is not null and v.lead_id != "") then CONCAT("Lead ID - ",l.lead_number," (",lc.name," "," (",loc.location,"))") 
		when (v.city is not null and v.city != "") then CONCAT("City - ",v.city) end) as name');
		$this->db->from('visit v');
		$this->db->join('visit_purpose vp','v.purpose_id = vp.purpose_id');
		$this->db->join('lead l','l.lead_id = v.lead_id', 'left');
		$this->db->join('customer lc','lc.customer_id = l.customer_id','left');
		$this->db->join('customer c','c.customer_id = v.customer_id', 'left');
		// $this->db->join('customer_location_contact clc','clc.customer_id = c.customer_id OR clc.location_id = l.location_id OR clc.contact_id = l.contact_id', 'left');
		$this->db->join('location loc','loc.location_id = l.location_id', 'left');
		$this->db->join('distributor_details d','d.user_id = v.dealer_id', 'left');
		if($searchParams['users']!=''){
		$this->db->join('user u','u.user_id=v.created_by', 'right');
		}else{
		$this->db->join('user u','u.user_id=v.created_by', 'left');
		}
		$this->db->join('branch b','b.branch_id=u.branch_id', 'left');
		if($searchParams['leadId']!='')
		$this->db->where('l.lead_number', $searchParams['leadId']);
		if($searchParams['customer']!=''){
			$this->db->where('l.customer_id', $searchParams['customer']);
			$this->db->or_where('v.customer_id',  $searchParams['customer']);
		}
		if($searchParams['branch']!='')
		$this->db->where('b.branch_id', $searchParams['branch']);
		if($searchParams['purpose']!='')
		$this->db->where('vp.purpose_id', $searchParams['purpose']);
		if($searchParams['users']!='')
		$this->db->where('u.user_id', $searchParams['users']);
		if($searchParams['startDate']!='')
		$this->db->where('date(v.start_date) >=', $searchParams['startDate']);
		if($searchParams['endDate']!='')
		$this->db->where('date(v.end_date) <=', $searchParams['endDate']);
		if($role_id == 4){
			$this->db->where('v.created_by', $this->session->userdata('user_id'));
		}
		else if($role_id == 6 ||$role_id == 7 ||$role_id == 8){
			// $this->db->where('u.branch_id', $branch_id);
			$this->db->where_in('u.branch_id',explode(',', $branch_ids));
		}
		
		// $this->db->where('l.company_id',$this->session->userdata('company'));
		$this->db->limit($per_page, $current_offset);
		$this->db->order_by('v.start_date','DESC');
		$this->db->group_by('v.visit_id');
		$res = $this->db->get();
		// echo $this->db->last_query();die;
 		return $res->result_array();
	}

	public function visitTotalRows($searchParams)
	{
		$role_id = $this->session->userdata('role_id');
		$user = $this->Common_model->get_data('user',array('user_id'=>$this->session->userdata('user_id'))); 
		$branch_id = $user[0]['branch_id'];
		
		// Logged User Branch 
		$branch_details = $this->Common_model->get_data('branch',array('branch_id'=>$branch_id)); 
		// Logged User Region 
		$region_details = $this->Common_model->get_data('location',array('parent_id'=>$branch_details[0]['region_id'])); 
		
		foreach($region_details as $row){
			// $branch_details[] = $this->Common_model->get_data_row('branch',array('region_id'=>$row['parent_id'])); 
			$region_ids[] = $row['parent_id'];
		}
		$ids = implode(",",array_unique($region_ids));
		$this->db->select('branch_id');
		$this->db->from('branch');
		$this->db->where_in('region_id', explode(',', $ids));
		$res = $this->db->get();
		$region_branches = $res->result_array();
		// getting branch id's
		$result_ids = array_column($region_branches, 'branch_id');
		$branch_ids = implode(",",$result_ids);
		$this->db->from('visit v');
		$this->db->join('visit_purpose vp','v.purpose_id = vp.purpose_id');
		$this->db->join('lead l','l.lead_id = v.lead_id','left');
		$this->db->join('customer c','c.customer_id = v.customer_id','left');
		// $this->db->join('customer_location_contact clc','clc.customer_id = c.customer_id OR clc.location_id = l.location_id OR clc.contact_id = l.contact_id','left');
		$this->db->join('location loc','loc.location_id = l.location_id','left');
		$this->db->join('distributor_details d','d.user_id = v.dealer_id', 'left');
		if($searchParams['users']!=''){
		$this->db->join('user u','u.user_id=v.created_by', 'right');
		}else{
		$this->db->join('user u','u.user_id=v.created_by', 'left');
		}
		$this->db->join('branch b','b.branch_id=u.branch_id','left');
		if($searchParams['users']!='')
		$this->db->where('u.user_id', $searchParams['users']);
		if($searchParams['customer']!=''){
			$this->db->where('l.customer_id', $searchParams['customer']);
			$this->db->or_where('v.customer_id',  $searchParams['customer']);
		}
		if($searchParams['branch']!='')
		$this->db->where('b.branch_id', $searchParams['branch']);
		if($searchParams['purpose']!='')
		$this->db->where('vp.purpose_id', $searchParams['purpose']);
		if($searchParams['startDate']!='')
		$this->db->where('date(v.start_date) >=', $searchParams['startDate']);
		if($searchParams['endDate']!='')
		$this->db->where('date(v.end_date) <=', $searchParams['endDate']);
		if($role_id == 4){
			$this->db->where('v.created_by', $this->session->userdata('user_id'));
		}
		else if($role_id == 6 ||$role_id == 7 ||$role_id == 8){
			// $this->db->where('u.branch_id', $branch_id);
			$this->db->where_in('u.branch_id',explode(',', $branch_ids));
		}
		$this->db->group_by('v.visit_id');
		// $this->db->where('l.user_id', $this->session->userdata('user_id'));
		// $this->db->where('l.company_id',$this->session->userdata('company'));
		$res = $this->db->get();
		return $res->num_rows();
	}

	public function locationResults($searchParams, $per_page, $current_offset)
	{
		$user = $this->Common_model->get_data('user',array('user_id'=>$this->session->userdata('user_id'))); 
		$this->db->select('ll.*,CONCAT(u.first_name," ",u.last_name) as lead_owner');
		$this->db->from('live_location_report ll');
		$this->db->join('user u','u.user_id=ll.user_id');
		if($searchParams['users']!='')
		$this->db->where('ll.user_id', $searchParams['users']);
		if($searchParams['startDate']!='')
		$this->db->where('date(ll.created_time) >=', $searchParams['startDate']);
		if($searchParams['endDate']!='')
		$this->db->where('date(ll.created_time) <=', $searchParams['endDate']);
		$this->db->limit($per_page, $current_offset);
		
		$res = $this->db->get();
		// echo '<pre>';print_r($this->db->last_query());die;
 		return $res->result_array();
	}

	public function downloadLocations($searchParams)
	{
		$user = $this->Common_model->get_data('user',array('user_id'=>$this->session->userdata('user_id'))); 
		$this->db->select('ll.*,CONCAT(u.first_name," ",u.last_name) as lead_owner');
		$this->db->from('live_location_report ll');
		$this->db->join('user u','u.user_id=ll.user_id');
		if($searchParams['users']!='')
		$this->db->where('ll.user_id', $searchParams['users']);
		if($searchParams['startDate']!='')
		$this->db->where('date(ll.created_time) >=', $searchParams['startDate']);
		if($searchParams['endDate']!='')
		$this->db->where('date(ll.created_time) <=', $searchParams['endDate']);
		$res = $this->db->get();
		// echo $this->db->last_query();die;
 		return $res->result_array();
	}

	public function locationTotalRows($searchParams)
	{
		$user = $this->Common_model->get_data('user',array('user_id'=>$this->session->userdata('user_id'))); 
		$this->db->select('ll.*');
		$this->db->from('live_location_report ll');
		$this->db->join('user u','u.user_id=ll.user_id');
		if($searchParams['users']!='')
		$this->db->where('ll.user_id', $searchParams['users']);
		if($searchParams['startDate']!='')
		$this->db->where('date(ll.created_time) >=', $searchParams['startDate']);
		if($searchParams['endDate']!='')
		$this->db->where('date(ll.created_time) <=', $searchParams['endDate']);
		$res = $this->db->get();
		// echo $this->db->last_query();die;
 		return $res->num_rows();
	}
	public function demoReportResults($searchParams, $per_page, $current_offset)
	{
		$this->db->select('d.*, CONCAT("ID - ", o.opp_number, ": ", p.name," (",p.description,")") as opportunity, CONCAT(p.name," - ",p.description) as demo,o.status as opportunity_status, p.name as product_name,p.description as product_description,l.lead_number,u.employee_id as ps,u.first_name as employeename,r.location,b.name as city');
		$this->db->from('demo d');
		if($searchParams['startDate']!='')
		$this->db->where('d.start_date >=', $searchParams['startDate']);
		if($searchParams['endDate']!='')
		$this->db->where('d.end_date <=', $searchParams['endDate']);
		if($searchParams['users']!='')
		$this->db->where('d.created_by', $searchParams['users']);
		$this->db->join('demo_product dp', 'dp.demo_product_id = d.demo_product_id AND dp.product_id = d.product_id','left');
		$this->db->join('demo_product_details dpd','dpd.demo_product_id = dp.demo_product_id','left');
		$this->db->join('product p','p.product_id = d.product_id','left');
		$this->db->join('opportunity_product op', 'op.opportunity_id = d.opportunity_id AND op.product_id = d.product_id','left');
		$this->db->join('opportunity o', 'o.opportunity_id = op.opportunity_id','left');
		$this->db->join('lead l','l.lead_id = o.lead_id','left');
		$this->db->join('user u','u.user_id = d.created_by','left');
		$this->db->join('branch b','b.branch_id=u.branch_id','left');
		$this->db->join('location r','r.location_id = b.region_id','left');
		$this->db->limit($per_page, $current_offset);
		$this->db->order_by('d.demo_id','DESC');
		$res = $this->db->get();
		return $res->result_array();
	}

	public function downloadDemoReport($searchParams)
	{
		$this->db->select('d.*, CONCAT("ID - ", o.opp_number, ": ", p.name," (",p.description,")") as opportunity, CONCAT(p.name," - ",p.description) as demo,o.status as opportunity_status, p.name as product_name,p.description as product_description,l.lead_number,u.employee_id as ps,u.first_name as employeename,r.location,b.name as city');
		$this->db->from('demo d');
		if($searchParams['startDate']!='')
		$this->db->where('d.start_date >=', $searchParams['startDate']);
		if($searchParams['endDate']!='')
		$this->db->where('d.end_date <=', $searchParams['endDate']);
		if($searchParams['users']!='')
		$this->db->where('d.created_by', $searchParams['users']);
		$this->db->join('demo_product dp', 'dp.demo_product_id = d.demo_product_id AND dp.product_id = d.product_id','left');
		$this->db->join('demo_product_details dpd','dpd.demo_product_id = dp.demo_product_id','left');
		$this->db->join('product p','p.product_id = d.product_id','left');
		$this->db->join('opportunity_product op', 'op.opportunity_id = d.opportunity_id AND op.product_id = d.product_id','left');
		$this->db->join('opportunity o', 'o.opportunity_id = op.opportunity_id','left');
		$this->db->join('lead l','l.lead_id = o.lead_id','left');
		$this->db->join('user u','u.user_id = d.created_by','left');
		$this->db->join('branch b','b.branch_id=u.branch_id','left');
		$this->db->join('location r','r.location_id = b.region_id','left');
		$this->db->order_by('d.demo_id','DESC');
		$res = $this->db->get();
		return $res->result_array();
	}

	public function demoReportTotalRows($searchParams)
	{
		$this->db->select('d.*, CONCAT("ID - ", o.opp_number, ": ", p.name," (",p.description,")") as opportunity, CONCAT(p.name," - ",p.description) as demo,o.status as opportunity_status, p.name as product_name,p.description as product_description,l.lead_number,u.employee_id as ps,u.first_name as employeename,r.location,b.name as city');
		$this->db->from('demo d');
		if($searchParams['startDate']!='')
		$this->db->where('d.start_date >=', $searchParams['startDate']);
		if($searchParams['endDate']!='')
		$this->db->where('d.end_date <=', $searchParams['endDate']);
		if($searchParams['users']!='')
		$this->db->where('d.created_by', $searchParams['users']);
		$this->db->join('demo_product dp', 'dp.demo_product_id = d.demo_product_id AND dp.product_id = d.product_id','left');
		$this->db->join('demo_product_details dpd','dpd.demo_product_id = dp.demo_product_id','left');
		$this->db->join('product p','p.product_id = d.product_id','left');
		$this->db->join('opportunity_product op', 'op.opportunity_id = d.opportunity_id AND op.product_id = d.product_id','left');
		$this->db->join('opportunity o', 'o.opportunity_id = op.opportunity_id','left');
		$this->db->join('lead l','l.lead_id = o.lead_id','left');
		$this->db->join('user u','u.user_id = d.created_by','left');
		$this->db->join('branch b','b.branch_id=u.branch_id','left');
		$this->db->join('location r','r.location_id = b.region_id','left');
		$res = $this->db->get();
		// echo"<pre>";print_r($this->db->last_query());die;
 		return $res->num_rows();
	}

	public function getSearchCustomer($customer_id)
    {
        $this->db->select('c.customer_id, concat(c.name, " (", l.location, ")") as customer');
        $this->db->from('customer c'); 
        $this->db->join('customer_location cl', 'cl.customer_id = c.customer_id');   
        $this->db->join('location l', 'l.location_id = cl.location_id');
        $this->db->where('c.company_id',$this->session->userdata('company'));
        $this->db->where('c.customer_id', $customer_id);
		$res = $this->db->get();
		
        if($res->num_rows() > 0)
        {
            $data = $res->result_array();
            return $data[0];
        }
        else
            return array('customer_id' => '', 'customer' => 'Select Customer');
 

	}
	public function downloadVisits($searchParams)
	{
		//print_r($searchParams);die;
		$role_id = $this->session->userdata('role_id');
		$user = $this->Common_model->get_data('user',array('user_id'=>$this->session->userdata('user_id'))); 
		$branch_id = $user[0]['branch_id'];

		$userId = $this->session->userdata('user_id');
		$compId = $this->session->userdata('company');
	// 	$where = "";
	// 	if($role_id == 4){
	// 		$where = "l.user_id = $userId AND ";
	// 	}
	// 	else if($role_id == 6 ||$role_id == 7 ||$role_id == 8){
	// 		$where = "u.branch_id = $branch_id AND ";
	// 	}
			
	// 	if($searchParams['customer']!='')
	// 	$where.='c.customer_id =' .$searchParams['customer'].' AND ';
	// 	if($searchParams['branch']!='')
	// 	$where.='br.branch_id ='. $searchParams['branch'].' AND ';
	// 	if($searchParams['purpose']!='')
	// 	$where.='vp.purpose_id ='.$searchParams['purpose'].' AND ';
	// 	if($searchParams['users']!='')
	// 	$where.='u.user_id ='.$searchParams['users'].' AND ';
	// 	if($searchParams['startDate']!='')
	// 	$where.='v.start_date >='.$searchParams['startDate'].' AND ';
	// 	if($searchParams['endDate']!='')
	// 	$where.='v.end_date <='.$searchParams['endDate'].' AND ';
	// 	// echo $where;die;
	// 	$SQL = "SELECT	*,
	// 	IF(A.end_date < CURDATE(), '1', '0') is_expired,
	// 	`br`.`name` AS `branch`,
	// 	CONCAT(u.first_name, ' ', u.last_name) AS lead_owner,
	// 	(CASE
	// 		WHEN A.status = 1 THEN 'Completed'
	// 		WHEN A.status = 2 THEN 'Cancelled'
	// 		ELSE 'Rescheduled'
	// 	END) AS status,
	// 	(IFNULL(leadPlaned, 0) + IFNULL(coldCallPlaned, 0) + IFNULL(dealerPlaned, 0) + IFNULL(curtecyPlaned, 0) + IFNULL(miscPlaned, 0)) AS totalPlan,
	// 	(IFNULL(leadCompleted, 0) + IFNULL(coldCallCompleted, 0) + IFNULL(dealereCompleted, 0) + IFNULL(curtecyCallCompleted, 0) + IFNULL(miscCompleted, 0)) AS totalComplete,l.lead_number
	// FROM
	// 	visit AS A
	// 		LEFT JOIN
	// 	(SELECT 
	// 		B.lead_id, COUNT(*) AS leadPlaned
	// 	FROM
	// 		visit AS B
	// 	WHERE
	// 		B.purpose_id IN (2 , 3, 4, 5, 6)
	// 	GROUP BY B.lead_id) B ON A.lead_id = B.lead_id
	// 		LEFT JOIN
	// 	(SELECT 
	// 		C.lead_id, COUNT(*) AS coldCallPlaned
	// 	FROM
	// 		visit AS C
	// 	WHERE
	// 		C.purpose_id IN (1)
	// 	GROUP BY C.lead_id) C ON A.lead_id = C.lead_id
	// 		LEFT JOIN
	// 	(SELECT 
	// 		D.lead_id, COUNT(*) AS dealerPlaned
	// 	FROM
	// 		visit AS D
	// 	WHERE
	// 		D.purpose_id IN (7)
	// 	GROUP BY D.lead_id) D ON A.lead_id = D.lead_id
	// 		LEFT JOIN
	// 	(SELECT 
	// 		E.lead_id, COUNT(*) AS curtecyPlaned
	// 	FROM
	// 		visit AS E
	// 	WHERE
	// 		E.purpose_id IN (10)
	// 	GROUP BY E.lead_id) E ON A.lead_id = E.lead_id
	// 		LEFT JOIN
	// 	(SELECT 
	// 		F.lead_id, COUNT(*) AS miscPlaned
	// 	FROM
	// 		visit AS F
	// 	WHERE
	// 		F.purpose_id NOT IN (1 , 2, 3, 4, 5, 6, 7, 10)
	// 	GROUP BY F.lead_id) F ON A.lead_id = F.lead_id
	// 		LEFT JOIN
	// 	(SELECT 
	// 		G.lead_id, COUNT(*) AS leadCompleted
	// 	FROM
	// 		visit AS G
	// 	WHERE
	// 		G.purpose_id IN (2 , 3, 4, 5, 6)
	// 			AND G.status IN (1 , 2)
	// 	GROUP BY G.lead_id) G ON A.lead_id = G.lead_id
	// 		LEFT JOIN
	// 	(SELECT 
	// 		H.lead_id, COUNT(*) AS coldCallCompleted
	// 	FROM
	// 		visit AS H
	// 	WHERE
	// 		H.purpose_id IN (1)
	// 			AND H.status IN (1 , 2)
	// 	GROUP BY H.lead_id) H ON A.lead_id = H.lead_id
	// 		LEFT JOIN
	// 	(SELECT 
	// 		I.lead_id, COUNT(*) AS dealereCompleted
	// 	FROM
	// 		visit AS I
	// 	WHERE
	// 		I.purpose_id IN (7)
	// 			AND I.status IN (1 , 2)
	// 	GROUP BY I.lead_id) I ON A.lead_id = I.lead_id
	// 		LEFT JOIN
	// 	(SELECT 
	// 		J.lead_id, COUNT(*) AS curtecyCallCompleted
	// 	FROM
	// 		visit AS J
	// 	WHERE
	// 		J.purpose_id IN (10)
	// 			AND J.status IN (1 , 2)
	// 	GROUP BY J.lead_id) J ON A.lead_id = J.lead_id
	// 		LEFT JOIN
	// 	(SELECT 
	// 		K.lead_id, COUNT(*) AS miscCompleted
	// 	FROM
	// 		visit AS K
	// 	WHERE
	// 		K.purpose_id NOT IN (1 , 2, 3, 4, 5, 6, 7, 10)
	// 			AND K.status IN (1 , 2)
	// 	GROUP BY K.lead_id) K ON A.lead_id = K.lead_id
	// 		JOIN
	// 	`visit_purpose` `vp` ON `A`.`purpose_id` = `vp`.`purpose_id`
	// 		JOIN
	// 	`lead` `l` ON `l`.`lead_id` = `A`.`lead_id`
	// 		JOIN
	// 	`user` `u` ON `u`.`user_id` = `l`.`user_id`
	// 		JOIN
	// 	`branch` `br` ON `br`.`branch_id` = `u`.`branch_id`
	// WHERE
	// 	$where
	// 		 `l`.`company_id` = $compId
	// GROUP BY A.lead_id
	// ORDER BY `A`.`visit_id` DESC";


	$role_id = $this->session->userdata('role_id');
		$user = $this->Common_model->get_data('user',array('user_id'=>$this->session->userdata('user_id'))); 
		$branch_id = $user[0]['branch_id'];
		// echo $user['branch_id'];die;		
		$this->db->select('v.*, IF(v.end_date < CURDATE(), "1", "0") is_expired, CONCAT("Lead ID - ",l.lead_number," (",c.name," "," (",loc.location,"))") as CustomerName, vp.name as Purpose,b.name as branch,CONCAT(u.first_name," ",u.last_name) as lead_owner,(CASE WHEN (v.status = 3)  THEN "Completed" WHEN (v.status = 1)  THEN "Planned" WHEN v.status = 2 THEN "Cancelled" WHEN (v.status = 4)  THEN "Postponed" END) AS status,
		sum(v.purpose_id = 1) as coldcallPlaned,sum(v.purpose_id = 1 and v.status in (3))as coldCallcompleted,
		sum(v.purpose_id in(2 , 3, 4, 5, 6)) as leadPlaned,sum(v.purpose_id in(2 , 3, 4, 5, 6) and v.status in (3))as leadCompleted, 
		sum(v.purpose_id in(7)) as dealerPlaned,sum(v.purpose_id in(7) and v.status in (3))as dealerCompleted, 
		sum(v.purpose_id in(10)) as curtecyCallPlaned,sum(v.purpose_id in(10) and v.status in (3))as curtesyCallCompleted, 
		sum(v.purpose_id NOT IN (1 , 2, 3, 4, 5, 6, 7, 10)) as miscPlaned,sum(v.purpose_id NOT IN (1 , 2, 3, 4, 5, 6, 7, 10) and v.status in (3))as miscCompleted, date(v.start_date) as planedDate');
		$this->db->from('visit v');
		$this->db->join('visit_purpose vp','v.purpose_id = vp.purpose_id');
		$this->db->join('lead l','l.lead_id = v.lead_id','left');
		$this->db->join('customer c','c.customer_id = v.customer_id','left');
		// $this->db->join('customer_location_contact clc','clc.customer_id = c.customer_id AND clc.location_id = l.location_id AND clc.contact_id = l.contact_id','left');
		$this->db->join('location loc','loc.location_id = l.location_id','left');
		$this->db->join('distributor_details d','d.user_id = v.dealer_id', 'left');
		$this->db->join('user u','u.user_id=v.created_by','left');
		$this->db->join('branch b','b.branch_id=u.branch_id','left');
		if($searchParams['leadId']!='')
		$this->db->where('l.lead_number', $searchParams['leadId']);
		if($searchParams['customer']!=''){
			$this->db->where('l.customer_id', $searchParams['customer']);
			$this->db->or_where('v.customer_id',  $searchParams['customer']);
		}		
		if($searchParams['branch']!='')
		$this->db->where('b.branch_id', $searchParams['branch']);
		if($searchParams['purpose']!='')
		$this->db->where('vp.purpose_id', $searchParams['purpose']);
		if($searchParams['users']!='')
		$this->db->where('u.user_id', $searchParams['users']);
		if($searchParams['startDate']!='')
		$this->db->where('date(v.start_date) >=', $searchParams['startDate']);
		if($searchParams['endDate']!='')
		$this->db->where('date(v.end_date) <=', $searchParams['endDate']);
		if($role_id == 4){
			$this->db->where('v.created_by', $this->session->userdata('user_id'));
		}
		else if($role_id == 6 ||$role_id == 7 ||$role_id == 8){
			$this->db->where('b.branch_id', $branch_id);
		}
		
		// $this->db->where('l.company_id',$this->session->userdata('company'));
		
		$this->db->order_by('v.start_date','DESC');
		$this->db->group_by('v.created_by,date(v.start_date)');
		$res = $this->db->get();
		// echo '<pre>';print_r($this->db->last_query());die;
		// echo '<pre>'; print_r($res->result_array());die;
		// $res = $this->db->query($SQL);
		return $res->result_array();
	}

	public function regionDetail()
	{
		$this->db->select('l.location, l.location_id, l.created_time, l.modified_by, l.modified_time, l1.location as CountryName');
		$this->db->from('location l');
		
		$this->db->join('location l1','l1.location_id = l.parent_id','left');
		$this->db->where('tl.name', 'Region');
		$this->db->where('l.status','1');
		$this->db->join('territory_level tl ','tl.territory_level_id = l.territory_level_id');
		$res = $this->db->get();
		// echo $this->db->last_query();die;
		return $res->result_array();
	}

	public function dailyVisitResults($searchParams, $per_page, $current_offset,$user_reportees)
	{
		//print_r($this->session->userdata());die;
		$role_id = $this->session->userdata('role_id');
		$user = $this->Common_model->get_data('user',array('user_id'=>$this->session->userdata('user_id'))); 
		$branch_id = $user[0]['branch_id'];
		// echo $user['branch_id'];die;		
		$this->db->select('v.*, IF(v.end_date < CURDATE(), "1", "0") is_expired, CONCAT("Lead ID - ",l.lead_number," (",c.name," "," (",loc.location,"))") as CustomerName, vp.name as Purpose,b.name as branch,CONCAT(u.first_name," ",u.last_name) as lead_owner,concat(cn.first_name," ",cn.last_name," - ",sp.name," (", cn.mobile_no, ")" ) as contact, 
		(CASE WHEN l.status = 1 THEN "Waiting for Approval" WHEN l.status = 2 THEN "Lead Approved" 
		WHEN l.status = 3 THEN "Opportunity Created"
		WHEN l.status = 4 THEN "All Opportunities Dropped"
		WHEN l.status = 5 THEN "All Opportunities Lost or Dropped"
		WHEN l.status = 6 THEN "Partial Quote"
		WHEN l.status = 7 THEN "Full Quote"
		WHEN l.status = 8 THEN "Partial Contract Note - Partial Quote"
		WHEN l.status = 9 THEN "Partial Contract Note - Full Quote"
		WHEN l.status = 10 THEN "Full Contract Note - Full Quote"
		WHEN l.status = 19 THEN "Lead Owner Role Changed" END) AS leadStatus,l.lead_number,
		(CASE WHEN (v.status = 3)  THEN "Completed" WHEN (v.status = 1)  THEN "Planned" WHEN v.status = 2 THEN "Cancelled" WHEN (v.status = 4)  THEN "Postponed" END) AS status, (case when (v.dealer_id is not null and  v.dealer_id != "") then CONCAT("Dealer - ",d.distributor_name) 
		when (v.customer_id is not null and v.customer_id != "") then CONCAT("Customer - ",c.name) when (v.lead_id is not null and v.lead_id != "") then CONCAT("Lead ID - ",l.lead_number," (",lc.name," "," (",loc.location,"))") 
		when (v.city is not null and v.city != "") then CONCAT("City - ",v.city) end) as name');
		$this->db->from('visit v');
		$this->db->join('visit_purpose vp','v.purpose_id = vp.purpose_id');
		$this->db->join('lead l','l.lead_id = v.lead_id','left');
		$this->db->join('customer lc','lc.customer_id = l.customer_id','left');
		$this->db->join('customer c','c.customer_id = v.customer_id','left');
		// $this->db->join('customer_location_contact clc','clc.customer_id = c.customer_id OR clc.location_id = l.location_id OR clc.contact_id = l.contact_id','left');
		$this->db->join('location loc','loc.location_id = l.location_id','left');
		$this->db->join('distributor_details d','d.user_id = v.dealer_id', 'left');
		$this->db->join('user u','u.user_id=v.created_by','left');
		$this->db->join('branch b','b.branch_id=u.branch_id','left');
		$this->db->join('contact cn','cn.contact_id = l.contact_id','left');
		$this->db->join('speciality sp','sp.speciality_id = cn.speciality_id','left');
		// $this->db->where('l3.parent_id',$searchParams['country_id']);
		$this->db->join('location l1','l1.location_id = loc.parent_id','left');
		$this->db->join('location l2','l2.location_id = l1.parent_id','left');
		$this->db->join('location l3','l3.location_id = l2.parent_id','left');
		$this->db->join('location l4','l4.location_id = l3.parent_id','left');
		if($searchParams['leadId']!='')
		$this->db->where('l.lead_number', $searchParams['leadId']);
		if($searchParams['customer']!=''){
			$this->db->where('l.customer_id', $searchParams['customer']);
			$this->db->or_where('v.customer_id',  $searchParams['customer']);
		}
		if($searchParams['region']!='')
		$this->db->where('l2.parent_id', $searchParams['region']);
		if($searchParams['purpose']!='')
		$this->db->where('vp.purpose_id', $searchParams['purpose']);
		if($searchParams['users']!='')
		$this->db->where('u.user_id', $searchParams['users']);
		if($searchParams['startDate']!='')
		$this->db->where('date(v.start_date) >=', $searchParams['startDate']);
		if($searchParams['endDate']!='')
		$this->db->where('date(v.end_date) <=', $searchParams['endDate']);
		if($role_id == 4){
			$this->db->where('v.created_by', $this->session->userdata('user_id'));
		}
		else if($role_id == 6 ||$role_id == 7 ||$role_id == 8){
			$this->db->where('v.created_by in ('.$user_reportees.')');
		}
		
		// $this->db->where('l.company_id',$this->session->userdata('company'));
		$this->db->limit($per_page, $current_offset);
		$this->db->order_by('v.start_date','DESC');
		$this->db->group_by('v.visit_id');
		$res = $this->db->get();
		// echo '<pre>'; print_r($this->db->last_query());die;
 		return $res->result_array();
	}

	public function dailyVisitReportDownload($searchParams,$user_reportees)
	{
		//print_r($this->session->userdata());die;
		$role_id = $this->session->userdata('role_id');
		$user = $this->Common_model->get_data('user',array('user_id'=>$this->session->userdata('user_id'))); 
		$branch_id = $user[0]['branch_id'];
		// echo $user['branch_id'];die;		
		$this->db->select('v.*, IF(v.end_date < CURDATE(), "1", "0") is_expired, CONCAT("Lead ID - ",l.lead_number," (",c.name," "," (",loc.location,"))") as CustomerName, vp.name as Purpose,b.name as branch,CONCAT(u.first_name," ",u.last_name) as lead_owner,concat(cn.first_name," ",cn.last_name," - ",sp.name," (", cn.mobile_no, ")" ) as contact, 
		(CASE WHEN l.status = 1 THEN "Waiting for Approval" WHEN l.status = 2 THEN "Lead Approved" 
		WHEN l.status = 3 THEN "Opportunity Created"
		WHEN l.status = 4 THEN "All Opportunities Dropped"
		WHEN l.status = 5 THEN "All Opportunities Lost or Dropped"
		WHEN l.status = 6 THEN "Partial Quote"
		WHEN l.status = 7 THEN "Full Quote"
		WHEN l.status = 8 THEN "Partial Contract Note - Partial Quote"
		WHEN l.status = 9 THEN "Partial Contract Note - Full Quote"
		WHEN l.status = 10 THEN "Full Contract Note - Full Quote"
		WHEN l.status = 19 THEN "Lead Owner Role Changed" END) AS leadStatus,l.lead_number
		,date(v.start_date) as date, time(v.start_date) as frm, time(v.end_date) as tot,c.name as customerName,
		(CASE WHEN (v.status = 3)  THEN "Completed" WHEN (v.status = 1)  THEN "Planned" WHEN v.status = 2 THEN "Cancelled" WHEN (v.status = 4)  THEN "Postponed" END) AS status, (case when (v.dealer_id is not null and  v.dealer_id != "") then CONCAT("Dealer - ",d.distributor_name) 
		when (v.customer_id is not null and v.customer_id != "") then CONCAT("Customer - ",c.name) when (v.lead_id is not null and v.lead_id != "") then CONCAT("Lead ID - ",l.lead_number," (",lcl.name," "," (",loc.location,"))") 
		when (v.city is not null and v.city != "") then CONCAT(v.city) end) as name,(case when (v.customer_id is not null AND v.customer_id != "") then lc.location when (v.lead_id is not null AND v.lead_id != "") then loc.location 
		when (v.city is not null AND v.city != "") then v.city end)  as lcity, r.location as region,u.employee_id');
		$this->db->from('visit v');
		$this->db->join('visit_purpose vp','v.purpose_id = vp.purpose_id');
		$this->db->join('lead l','l.lead_id = v.lead_id','left');
		$this->db->join('customer lcl','lcl.customer_id = l.customer_id','left');
		$this->db->join('customer c','c.customer_id = v.customer_id','left');
		// $this->db->join('customer_location_contact clc','clc.customer_id = c.customer_id OR clc.location_id = l.location_id OR clc.contact_id = l.contact_id','left');
		$this->db->join('location loc','loc.location_id = l.location_id','left');
		$this->db->join('customer_location cl','cl.customer_id = v.customer_id', 'left');
		$this->db->join('location lc','lc.location_id = cl.location_id','left');
		$this->db->join('distributor_details d','d.user_id = v.dealer_id', 'left');
		$this->db->join('user u','u.user_id=v.created_by','left');
		$this->db->join('branch b','b.branch_id=u.branch_id','left');
		$this->db->join('location r','r.location_id = b.region_id','left');
		$this->db->join('contact cn','cn.contact_id = l.contact_id','left');
		$this->db->join('speciality sp','sp.speciality_id = cn.speciality_id','left');
		// $this->db->where('l3.parent_id',$searchParams['country_id']);
		$this->db->join('location l1','l1.location_id = loc.parent_id','left');
		$this->db->join('location l2','l2.location_id = l1.parent_id','left');
		$this->db->join('location l3','l3.location_id = l2.parent_id','left');
		$this->db->join('location l4','l4.location_id = l3.parent_id','left');
		if($searchParams['leadId']!='')
		$this->db->where('l.lead_number', $searchParams['leadId']);
		if($searchParams['customer']!=''){
			$this->db->where('l.customer_id', $searchParams['customer']);
			$this->db->or_where('v.customer_id',  $searchParams['customer']);
		}
		if($searchParams['region']!='')
		$this->db->where('l2.parent_id', $searchParams['region']);
		if($searchParams['purpose']!='')
		$this->db->where('vp.purpose_id', $searchParams['purpose']);
		if($searchParams['users']!='')
		$this->db->where('u.user_id', $searchParams['users']);
		if($searchParams['startDate']!='')
		$this->db->where('date(v.start_date) >=', $searchParams['startDate']);
		if($searchParams['endDate']!='')
		$this->db->where('date(v.end_date) <=', $searchParams['endDate']);
		if($role_id == 4){
			$this->db->where('v.created_by', $this->session->userdata('user_id'));
		}
		else if($role_id == 6 ||$role_id == 7 ||$role_id == 8){
			// $this->db->where('u.branch_id', $branch_id);
			$this->db->where('v.created_by in ('.$user_reportees.')');
		}
		$this->db->group_by('v.visit_id');
		// $this->db->where('l.company_id',$this->session->userdata('company'));
		// $this->db->limit($per_page, $current_offset);
		$this->db->order_by('v.start_date','DESC');
		$res = $this->db->get();
		// echo '<pre>'; print_r($res->result_array());die;
		// echo $this->db->last_query();die;
 		return $res->result_array();
	}

	public function dailyVisitTotalRows($searchParams,$user_reportees)
	{
		$role_id = $this->session->userdata('role_id');
		$user = $this->Common_model->get_data('user',array('user_id'=>$this->session->userdata('user_id'))); 
		$branch_id = $user[0]['branch_id'];
		$this->db->from('visit v');
		$this->db->join('visit_purpose vp','v.purpose_id = vp.purpose_id');
		$this->db->join('lead l','l.lead_id = v.lead_id','left');
		$this->db->join('customer lc','lc.customer_id = l.customer_id','left');
		$this->db->join('customer c','c.customer_id = v.customer_id','left');
		// $this->db->join('customer_location_contact clc','clc.customer_id = c.customer_id OR clc.location_id = l.location_id OR clc.contact_id = l.contact_id','left');
		$this->db->join('location loc','loc.location_id = l.location_id','left');
		$this->db->join('contact cn','cn.contact_id = l.contact_id','left');
		$this->db->join('speciality sp','sp.speciality_id = cn.speciality_id','left');
		$this->db->join('user u','u.user_id=v.created_by','left');
		$this->db->join('branch b','b.branch_id=u.branch_id','left');
		// $this->db->where('l3.parent_id',$searchParams['country_id']);
		$this->db->join('location l1','l1.location_id = loc.parent_id','left');
		$this->db->join('location l2','l2.location_id = l1.parent_id','left');
		$this->db->join('location l3','l3.location_id = l2.parent_id','left');
		$this->db->join('location l4','l4.location_id = l3.parent_id','left');
		if($searchParams['users']!='')
		$this->db->where('u.user_id', $searchParams['users']);
		if($searchParams['customer']!=''){
			$this->db->where('l.customer_id', $searchParams['customer']);
			$this->db->or_where('v.customer_id',  $searchParams['customer']);
		}
		if($searchParams['region']!='')
		$this->db->where('l2.parent_id', $searchParams['region']);
		if($searchParams['purpose']!='')
		$this->db->where('vp.purpose_id', $searchParams['purpose']);
		if($searchParams['startDate']!='')
		$this->db->where('date(v.start_date) >=', $searchParams['startDate']);
		if($searchParams['endDate']!='')
		$this->db->where('date(v.end_date) <=', $searchParams['endDate']);
		if($role_id == 4){
			$this->db->where('v.created_by', $this->session->userdata('user_id'));
		}
		else if($role_id == 6 ||$role_id == 7 ||$role_id == 8){
			// $this->db->where('u.branch_id', $branch_id);
			$this->db->where('v.created_by in ('.$user_reportees.')');
		}
		$this->db->group_by('v.visit_id');
		// $this->db->where('l.user_id', $this->session->userdata('user_id'));
		// $this->db->where('l.company_id',$this->session->userdata('company'));
		$res = $this->db->get();
		return $res->num_rows();
	}

	public function leaadPerformanceTotalRows($searchParams){
		$role_id = $this->session->userdata('role_id');
		$user = $this->Common_model->get_data('user',array('user_id'=>$this->session->userdata('user_id'))); 
		$branch_id = $user[0]['branch_id'];
		$this->db->from('lead l');
		// $this->db->join('visit v','v.lead_id = l.lead_id','left');
		// $this->db->join('opportunity op','l.lead_id = op.lead_id','left');
		// $this->db->join('opportunity_product opp','opp.opportunity_id = op.opportunity_id','left');
		// $this->db->join('product p','p.product_id = opp.product_id','left');
		// $this->db->join('product_group pg','pg.group_id = p.group_id','left');
		$this->db->join('location loc','loc.location_id = l.location_id');
		$this->db->join('user u','u.user_id=l.user_id');
		$this->db->join('role r',' r.role_id = u.role_id');
		$this->db->join('branch b','b.branch_id=u.branch_id');
		$this->db->join('location l1','l1.location_id = loc.parent_id','left');
		$this->db->join('location l2','l2.location_id = l1.parent_id','left');
		$this->db->join('location l3','l3.location_id = l2.parent_id','left');
		$this->db->join('location l4','l4.location_id = l3.parent_id','left');
		

		if($searchParams['users']!='')
		$this->db->where('u.user_id', $searchParams['users']);		
		if($searchParams['branch']!='')
		$this->db->where('b.branch_id', $searchParams['branch']);
		if($searchParams['region']!='')
		$this->db->where('l2.parent_id', $searchParams['region']);
		// if($searchParams['productGroup']!='')
		// $this->db->where('p.group_id', $searchParams['productGroup']);		
		if($searchParams['startDate']!='')
		$this->db->where('date(l.created_time) >=', $searchParams['startDate']);
		if($searchParams['endDate']!='')
		$this->db->where('date(l.created_time) <=', $searchParams['endDate']);
		if($role_id == 4){
			$this->db->where('l.user_id', $this->session->userdata('user_id'));
		}
		else if($role_id == 6 ||$role_id == 7 ||$role_id == 8){
			$this->db->where('u.branch_id', $branch_id);
		}
		// $this->db->where('l.user_id', $this->session->userdata('user_id'));
		$this->db->where('l.company_id',$this->session->userdata('company'));
		// $this->db->where('p.group_id = 2');
		// $this->db->limit($per_page, $current_offset);
		$this->db->order_by('l.lead_id','DESC');
		$this->db->group_by('l.lead_id');
		$res = $this->db->get();
				//  echo '<pre>'; print_r($this->db->last_query());die;

		return $res->num_rows();
	}

	public function leaadPerformanceResults($searchParams, $per_page, $current_offset){
		$role_id = $this->session->userdata('role_id');
		$user = $this->Common_model->get_data('user',array('user_id'=>$this->session->userdata('user_id'))); 
		$branch_id = $user[0]['branch_id'];
		$this->db->select('l.lead_id,date(l.created_time) as leadCreated,b.name as branch,l3.location as RegionName,
		concat(u.first_name, " ", `u`.`last_name`, " - ", `u`.`employee_id`, " (", `r`.`name`, ")") as leadOwner,
		coalesce(`v`.`count_lead`,0) as visitCount,b.branch_id,u.user_id,loc.location_id,  CONCAT("Lead ID - ", `l`.`lead_number`, " (", `c`.`name`, " ", " (", `loc`.`location`, "))") as custName');
		$this->db->from('lead l');
		$this->db->join('(select lead_id, count(*) as count_lead from visit group by lead_id) v','v.lead_id = l.lead_id','left');
		// $this->db->join('opportunity op','l.lead_id = op.lead_id','left');
		// $this->db->join('opportunity_product opp','opp.opportunity_id = op.opportunity_id','left');
		// $this->db->join('product p','p.product_id = opp.product_id','left');
		// $this->db->join('product_group pg','pg.group_id = p.group_id','left');
		$this->db->join('location loc','loc.location_id = l.location_id');
		$this->db->join('customer c','c.customer_id = l.customer_id'); 
		$this->db->join('user u','u.user_id=l.user_id');
		$this->db->join('role r',' r.role_id = u.role_id');
		$this->db->join('branch b','b.branch_id=u.branch_id');
		$this->db->join('location l1','l1.location_id = loc.parent_id','left');
		$this->db->join('location l2','l2.location_id = l1.parent_id','left');
		$this->db->join('location l3','l3.location_id = l2.parent_id','left');
		$this->db->join('location l4','l4.location_id = l3.parent_id','left');
		

		if($searchParams['users']!='')
		$this->db->where('u.user_id', $searchParams['users']);		
		if($searchParams['branch']!='')
		$this->db->where('b.branch_id', $searchParams['branch']);
		if($searchParams['region']!='')
		$this->db->where('l2.parent_id', $searchParams['region']);
		// if($searchParams['productGroup']!='')
		// $this->db->where('p.group_id', $searchParams['productGroup']);		
		if($searchParams['startDate']!='')
		$this->db->where('date(l.created_time) >=', $searchParams['startDate']);
		if($searchParams['endDate']!='')
		$this->db->where('date(l.created_time) <=', $searchParams['endDate']);
		if($role_id == 4){
			$this->db->where('l.user_id', $this->session->userdata('user_id'));
		}
		else if($role_id == 6 ||$role_id == 7 ||$role_id == 8){
			$this->db->where('u.branch_id', $branch_id);
		}
		// $this->db->where('l.user_id', $this->session->userdata('user_id'));
		$this->db->where('l.company_id',$this->session->userdata('company'));
		// $this->db->where('p.group_id = 2');
		$this->db->limit($per_page, $current_offset);
		$this->db->order_by('l.lead_id','DESC');
		
		$res = $this->db->get();
		//  echo '<pre>'; print_r($this->db->last_query());die;
		return $res->result_array();
	}

	public function get_opportunity($lead_id,$searchParams){
		$this->db->select('p.description as productName,pg.name as productGroupName, l.lead_id,op.opportunity_id,date(op.created_time) as opportunityCreatedTime,
		op.status,(CASE WHEN op.status = 6 THEN date(op.modified_time) ELSE "-" END) as opportunityWon,
		(CASE WHEN op.status = 7 THEN date(op.modified_time) ELSE "-" END) as opportunityLost,op.opp_number,,p.name as prName, op.required_quantity as quantity, c.name as CustName
		,(CASE WHEN op.status = 8 THEN date(op.modified_time) ELSE "-" END) as opportunityDropped,
		op.remarks1,op.remarks2,op.remarks3,op.remarks4,op.remarks5,');
		$this->db->from('opportunity op');
		$this->db->join('lead l','l.lead_id = op.lead_id');
		$this->db->join('customer c','c.customer_id = l.customer_id'); 
		$this->db->join('opportunity_product opp','opp.opportunity_id = op.opportunity_id');
		$this->db->join('product p','p.product_id = opp.product_id');
		$this->db->join('product_group pg','pg.group_id = p.group_id');
		$this->db->where('l.lead_id',$lead_id);
		if($searchParams['productGroup']!='')
		$this->db->where('p.group_id', $searchParams['productGroup']);

		$res = $this->db->get();
		// echo '<pre>'; print_r($this->db->last_query());die;
		return $res->result_array();
	}

	public function get_product_group(){
		$this->db->select('group_id,name,category_id');
		$this->db->from('product_group');
		$this->db->where('status',1);
		$res = $this->db->get();
		return $res->result_array();
	}
 
   public function downloadLeadPerformance($searchParams){	

   		$role_id = $this->session->userdata('role_id');
		$user = $this->Common_model->get_data('user',array('user_id'=>$this->session->userdata('user_id'))); 
		$branch_id = $user[0]['branch_id'];
		$this->db->select('l.lead_id,date(l.created_time) as leadCreated,b.name as branch,l3.location as RegionName,
		concat(u.first_name, " ", `u`.`last_name`, " - ", `u`.`employee_id`, " (", `r`.`name`, ")") as leadOwner,
		coalesce(`v`.`count_lead`,0) as visitCount,b.branch_id,u.user_id,loc.location_id,l.lead_number,c.name as customerName');
		$this->db->from('lead l');
		$this->db->join('(select lead_id, count(*) as count_lead from visit group by lead_id) v','v.lead_id = l.lead_id','left');
		$this->db->join('customer c','c.customer_id = l.customer_id'); 
		// $this->db->join('opportunity op','l.lead_id = op.lead_id','left');
		// $this->db->join('opportunity_product opp','opp.opportunity_id = op.opportunity_id','left');
		// $this->db->join('product p','p.product_id = opp.product_id','left');
		// $this->db->join('product_group pg','pg.group_id = p.group_id','left');
		$this->db->join('location loc','loc.location_id = l.location_id');
		$this->db->join('user u','u.user_id=l.user_id');
		$this->db->join('role r',' r.role_id = u.role_id');
		$this->db->join('branch b','b.branch_id=u.branch_id');
		$this->db->join('location l1','l1.location_id = loc.parent_id','left');
		$this->db->join('location l2','l2.location_id = l1.parent_id','left');
		$this->db->join('location l3','l3.location_id = l2.parent_id','left');
		$this->db->join('location l4','l4.location_id = l3.parent_id','left');
		

		if($searchParams['users']!='')
		$this->db->where('u.user_id', $searchParams['users']);		
		if($searchParams['branch']!='')
		$this->db->where('b.branch_id', $searchParams['branch']);
		if($searchParams['region']!='')
		$this->db->where('l2.parent_id', $searchParams['region']);
		// if($searchParams['productGroup']!='')
		// $this->db->where('p.group_id', $searchParams['productGroup']);		
		if($searchParams['startDate']!='')
		$this->db->where('date(l.created_time) >=', $searchParams['startDate']);
		if($searchParams['endDate']!='')
		$this->db->where('date(l.created_time) <=', $searchParams['endDate']);
		if($role_id == 4){
			$this->db->where('l.user_id', $this->session->userdata('user_id'));
		}
		else if($role_id == 6 ||$role_id == 7 ||$role_id == 8){
			$this->db->where('u.branch_id', $branch_id);
		}
		// $this->db->where('l.user_id', $this->session->userdata('user_id'));
		$this->db->where('l.company_id',$this->session->userdata('company'));
		// $this->db->where('p.group_id = 2');
		$this->db->order_by('l.lead_id','DESC');
		$this->db->group_by('l.lead_id');
		$res = $this->db->get();
		// echo '<pre>';print_r($this->db->last_query());die;
		return $res->result_array();
	}

	public function orderLostAnalysisTotlRows($searchParams){
		$role_id = $this->session->userdata('role_id');
		$user = $this->Common_model->get_data('user',array('user_id'=>$this->session->userdata('user_id'))); 
		$branch_id = $user[0]['branch_id'];
		
		$this->db->from('lead l');
		$this->db->join('opportunity op','l.lead_id = op.lead_id','left');
		$this->db->join('location loc','loc.location_id = l.location_id');
		$this->db->join('user u','u.user_id=l.user_id');
		$this->db->join('role r',' r.role_id = u.role_id');
		$this->db->join('branch b','b.branch_id=u.branch_id');
		$this->db->join('location l1','l1.location_id = loc.parent_id','left');
		$this->db->join('location l2','l2.location_id = l1.parent_id','left');
		$this->db->join('location l3','l3.location_id = l2.parent_id','left');
		$this->db->join('location l4','l4.location_id = l3.parent_id','left');		

		if($searchParams['users']!='')
		$this->db->where('u.user_id', $searchParams['users']);		
		if($searchParams['branch']!='')
		$this->db->where('b.branch_id', $searchParams['branch']);
		if($searchParams['region']!='')
		$this->db->where('l2.parent_id', $searchParams['region']);
				
		if($searchParams['startDate']!='')
		$this->db->where('date(l.created_time) >=', $searchParams['startDate']);
		if($searchParams['endDate']!='')
		$this->db->where('date(l.created_time) <=', $searchParams['endDate']);
		if($role_id == 4){
			$this->db->where('l.user_id', $this->session->userdata('user_id'));
		}
		else if($role_id == 6 ||$role_id == 7 ||$role_id == 8){
			$this->db->where('u.branch_id', $branch_id);
		}
		// $this->db->where('l.user_id', $this->session->userdata('user_id'));
		$this->db->where('l.company_id',$this->session->userdata('company'));
		$this->db->where('op.status = 7');
		$this->db->order_by('l.lead_id','DESC');
		$this->db->group_by('l.lead_id');
		$res = $this->db->get();
		// echo $this->db->last_query();die;
		return $res->num_rows();
	}

	public function orderLostAnalysisResults($searchParams, $per_page, $current_offset){
		$role_id = $this->session->userdata('role_id');
		$user = $this->Common_model->get_data('user',array('user_id'=>$this->session->userdata('user_id'))); 
		$branch_id = $user[0]['branch_id'];
		$this->db->select('l.lead_id,l.created_time as leadCreated,b.name as branch,l3.location as RegionName,
		concat(u.first_name, " ", `u`.`last_name`, " - ", `u`.`employee_id`, " (", `r`.`name`, ")") as leadOwner,
		b.branch_id,u.user_id,loc.location_id,l.lead_number');
		$this->db->from('lead l');
		$this->db->join('opportunity op','l.lead_id = op.lead_id','left');
		$this->db->join('location loc','loc.location_id = l.location_id');
		$this->db->join('user u','u.user_id=l.user_id');
		$this->db->join('role r',' r.role_id = u.role_id');
		$this->db->join('branch b','b.branch_id=u.branch_id');
		$this->db->join('location l1','l1.location_id = loc.parent_id','left');
		$this->db->join('location l2','l2.location_id = l1.parent_id','left');
		$this->db->join('location l3','l3.location_id = l2.parent_id','left');
		$this->db->join('location l4','l4.location_id = l3.parent_id','left');		

		if($searchParams['users']!='')
		$this->db->where('u.user_id', $searchParams['users']);		
		if($searchParams['branch']!='')
		$this->db->where('b.branch_id', $searchParams['branch']);
		if($searchParams['region']!='')
		$this->db->where('l2.parent_id', $searchParams['region']);
				
		if($searchParams['startDate']!='')
		$this->db->where('date(l.created_time) >=', $searchParams['startDate']);
		if($searchParams['endDate']!='')
		$this->db->where('date(l.created_time) <=', $searchParams['endDate']);
		if($role_id == 4){
			$this->db->where('l.user_id', $this->session->userdata('user_id'));
		}
		else if($role_id == 6 ||$role_id == 7 ||$role_id == 8){
			$this->db->where('u.branch_id', $branch_id);
		}
		// $this->db->where('l.user_id', $this->session->userdata('user_id'));
		$this->db->where('l.company_id',$this->session->userdata('company'));
		$this->db->where('op.status = 7');
		$this->db->limit($per_page, $current_offset);
		$this->db->order_by('l.lead_id','DESC');
		$this->db->group_by('l.lead_id');
		$res = $this->db->get();
		// echo '<pre>';print_r($this->db->last_query());die;
		return $res->result_array();
	}

	public function get_lost_opportunity($lead_id){
		$this->db->select('p.description as productName, l.lead_id,op.opportunity_id,op.created_time as opportunityCreatedTime,
		op.status,(CASE WHEN op.status = 7 THEN op.modified_time ELSE "-" END) as opportunityLost,qd.total_value as value
		,olr.name as reason, op.opp_number,op.remarks2 as reason1,os.name as opp_status');
		
		$this->db->from('opportunity op');
		$this->db->join('opportunity_lost_reasons olr','op.oppr_lost_id = olr.reason_id','left');
		$this->db->join('quote_details qd','qd.opportunity_id = op.opportunity_id');
		$this->db->join('lead l','l.lead_id = op.lead_id');
		$this->db->join('opportunity_product opp','opp.opportunity_id = op.opportunity_id');
		$this->db->join('product p','p.product_id = opp.product_id');
		$this->db->join('opportunity_status os','op.status = os.status');
		// $this->db->join('product_group pg','pg.group_id = p.group_id');
		$this->db->where('l.lead_id',$lead_id);
		// $this->db->where('op.status = 7');
		$this->db->where( 'op.status IN (7,8)');
		$this->db->group_by('op.opportunity_id');
		// if($searchParams['productGroup']!='')
		// $this->db->where('p.group_id', $searchParams['productGroup']);

		$res = $this->db->get();
		return $res->result_array();
	}

	 
   public function downloadOrderLostAnalysis($searchParams){	

	$role_id = $this->session->userdata('role_id');
	$user = $this->Common_model->get_data('user',array('user_id'=>$this->session->userdata('user_id'))); 
	$branch_id = $user[0]['branch_id'];
	// $this->db->select('l.lead_id,l.created_time as leadCreated,b.name as branch,l3.location as RegionName,
	// concat(u.first_name, " ", `u`.`last_name`, " - ", `u`.`employee_id`, " (", `r`.`name`, ")") as leadOwner,
	// b.branch_id,u.user_id,loc.location_id,l.lead_number');
	$this->db->select('l.lead_id,l.created_time as leadCreated,b.name as branch,l3.location as RegionName,
	concat(u.first_name, " ", `u`.`last_name`, " - ", `u`.`employee_id`, " (", `r`.`name`, ")") as leadOwner,
	b.branch_id,u.user_id,loc.location_id,l.lead_number,l3.location as region,pg.name as segment_name,c.name as cname,sum(op.required_quantity ) as qty,cp.name as competitor_name,p.name as product_name,os.name, l.remarks1 as reason');
	$this->db->from('lead l');
	$this->db->join('opportunity op','l.lead_id = op.lead_id','left');
	$this->db->join('location loc','loc.location_id = l.location_id');
	$this->db->join('user u','u.user_id=l.user_id');
	$this->db->join('role r',' r.role_id = u.role_id');
	$this->db->join('branch b','b.branch_id=u.branch_id');
	$this->db->join('location l1','l1.location_id = loc.parent_id','left');
	$this->db->join('location l2','l2.location_id = l1.parent_id','left');
	$this->db->join('location l3','l3.location_id = l2.parent_id','left');
	$this->db->join('location l4','l4.location_id = l3.parent_id','left');
	$this->db->join('opportunity_product op1','op.opportunity_id=op1.opportunity_id');
	$this->db->join('product p','op1.product_id=p.product_id');
	$this->db->join('product_group pg','p.group_id=pg.group_id');	
	$this->db->join('customer c','l.customer_id=c.customer_id');
	$this->db->join('competitor cp','op.lost_competitor_id=cp.competitor_id');	
	$this->db->join('opportunity_status os','op.status=os.status');
		

	if($searchParams['users']!='')
	$this->db->where('u.user_id', $searchParams['users']);		
	if($searchParams['branch']!='')
	$this->db->where('b.branch_id', $searchParams['branch']);
	if($searchParams['region']!='')
	$this->db->where('l2.parent_id', $searchParams['region']);
			
	if($searchParams['startDate']!='')
	$this->db->where('date(l.created_time) >=', $searchParams['startDate']);
	if($searchParams['endDate']!='')
	$this->db->where('date(l.created_time) <=', $searchParams['endDate']);
	if($role_id == 4){
		$this->db->where('l.user_id', $this->session->userdata('user_id'));
	}
	else if($role_id == 6 ||$role_id == 7 ||$role_id == 8){
		$this->db->where('u.branch_id', $branch_id);
	}
	// $this->db->where('l.user_id', $this->session->userdata('user_id'));
	$this->db->where('l.company_id',$this->session->userdata('company'));
	// $this->db->where('op.status = 7');
	$this->db->where( 'op.status IN (7,8)');
	$this->db->order_by('l.lead_id','DESC');
	$this->db->group_by('l.lead_id');
	$res = $this->db->get();
	// echo $this->db->last_query();die;
	return $res->result_array();
	}

	public function get_filter_funnel_download_data($searchfilters)
 	{
 		$fy_year=get_current_fiancial_year();
 		if($searchfilters['vtime']!='')
 		{
 			$parameter="o.created_time";
 			$where1=getReportTimelineCheck($searchfilters,$parameter,$fy_year);
 			// $day = getOpportunityCategorizationDate();
 			// 	if($searchfilters['vtime']=='y'||$searchfilters['vtime']=='q')
 			// 	{   
 			// 		$search_date=date('Y-m-d',strtotime($searchfilters['category_timeline']));
 			// 		$month_no=date('m',strtotime($searchfilters['category_timeline']));
 			// 		$date=date('Y-m-d');
 			// 		$row=get_month_start_end_date($date,$month_no);
 			// 	}
 			// 	elseif($searchfilters['vtime']=='m')
 			// 	{
 			// 		$row=array();
 			// 		$row['start_date']=substr($searchfilters['category_timeline'],7,10);
 			// 		$row['end_date']=substr($searchfilters['category_timeline'],21,10);
 			// 	}
 			// 	else
 			// 	{
 			// 		$search_date=date('Y-m-d',strtotime($searchfilters['category_timeline']));
 			// 		$row = array('end_date'=>date('Y-m-d'));
 			// 	}
 		}
        $this->db->select('sum(o.required_quantity ) as qty,sum(round((o.required_quantity*p.dp)/100000,2)) as value,pg.name as segment_name,p.description,p.name,concat(u.first_name," ",u.last_name) as uname,u.employee_id as psnumber, o.status,l4.location as region,o.created_time');
 		
 	            $this->db->from('opportunity o');
 	           $this->db->join('opportunity_product op','op.opportunity_id = o.opportunity_id');
 				$this->db->join('product p','p.product_id = op.product_id');
 				$this->db->join('product_group pg','p.group_id=pg.group_id');
 				$this->db->join('lead l',' l.lead_id = o.lead_id');
 				$this->db->join('location l1','l.location_id=l1.location_id');
 				$this->db->join('location l2','l1.parent_id=l2.location_id');
 				$this->db->join('location l3','l2.parent_id=l3.location_id');
 				$this->db->join('location l4','l3.parent_id=l4.location_id');
				$this->db->join('user u','l.user_id=u.user_id');
 				// if($time_par!="between")
 				// {   
 				// 	$this->db->join('opportunity_status_history osh', 'osh.opportunity_id = o.opportunity_id');
 				// 	$this->db->where( 'osh.status IN (1,2,3,4,5)');
 				// }
 				// if($searchfilters['role_id']==4||$searchfilters['role_id']==5)
 				// {
 				// 	$this->db->where('l.user_id IN ('.$searchfilters['reportee_users'].')');
 				// }
 				// else
 				// {
 				// 	$this->db->where('op.product_id IN ('.$searchfilters['userProducts'].')');
 		    	// 	$this->db->where('l.location_id IN ('.$searchfilters['userLocations'].') ');
 				// }
 				$this->db->where('o.company_id',$this->session->userdata('company'));
 				if($searchfilters['vtime']!='')
 				{
 					if($where1!='')
 						$this->db->where($where1);
 				}
 
 		// if($time_par=='between')
 		// {
 		// 	if($searchfilters['vtime']!='w')
 		// 	{
 		// 		$this->db->where('date(o.created_time)>=',$row['start_date']);
 		//         $this->db->where('date(o.created_time)<=',$row['end_date']);
 		//     }
 		// 	elseif($searchfilters['vtime']=='w')
 		// 	{
 		// 		$this->db->where('date(o.created_time)',$search_date);
 		// 	}
 		// 	switch ($series_name) {
 		// 		case 'Hot':
 		// 			$this->db->where('expected_order_conclusion <= CONCAT(YEAR(o.created_time),"-",MONTH(o.created_time),"-",'.$day.')');
 		// 			break;
 		// 		case 'Warm':
 		// 		    $warm_where='(expected_order_conclusion <= DATE_ADD(CONCAT(YEAR(o.created_time),"-",MONTH(o.created_time),"-",'.$day.'),INTERVAL 1 MONTH) AND expected_order_conclusion > CONCAT(YEAR(o.created_time),"-",MONTH(o.created_time),"-",'.$day.'))';
 		// 			$this->db->where($warm_where);
 		// 			break;
 		// 		case 'Cold':
 		// 			$cold_where='expected_order_conclusion > DATE_ADD(CONCAT(YEAR(o.created_time),"-",MONTH(o.created_time),"-",'.$day.'),INTERVAL 1 MONTH)';
 		// 			$this->db->where($cold_where);
 		// 			break;
 		// 	}
 		// }
 		// else
 		// {
 		// 	if($where1!='')
 		// 		$this->db->where($where1);
 		// 	switch ($series_name) {
 		// 		case 'Hot':
 		// 			$this->db->where('o.expected_order_conclusion<=',$hotDay);
 		// 			break;
 		// 		case 'Warm':
 		// 			$this->db->where('o.expected_order_conclusion>',$hotDay);
 		// 			$this->db->where('o.expected_order_conclusion<=',$warmDate);
 		// 			break;
 		// 		case 'Cold':
 		// 			$this->db->where('o.expected_order_conclusion>',$warmDate);
 		// 			break;
 		// 	}
 		// }
 		
 		// if($searchfilters['region']!='')
 	    // {
 	    // 	$this->db->where('l4.location_id',$searchfilters['region']);
 	    // }
 	    // if($time_par=='previous')
 		// {
 		// 	$st_date=$searchfilters['fy_dates']['start_date'];
 		// 	$this->db->where('osh.opportunity_status_id = (SELECT osh2.opportunity_status_id FROM opportunity_status_history osh2 
 		// 		WHERE osh2.opportunity_id = o.opportunity_id AND  DATE(osh2.created_time) < "'.$st_date.'" ORDER BY osh2.created_time DESC LIMIT 1)');
 	    // }
 	    // elseif($time_par=='present')
 	    // {
 	    // 	$st_date=$searchfilters['fy_dates']['end_date'];
 		// 	$this->db->where('osh.opportunity_status_id = (SELECT osh2.opportunity_status_id FROM opportunity_status_history osh2 
 		// 		WHERE osh2.opportunity_id = o.opportunity_id AND  DATE(osh2.created_time) <= "'.$st_date.'" ORDER BY osh2.created_time DESC LIMIT 1)');
 	    // }
 	 		$this->db->group_by('p.product_id');
 	    $r = $this->db->get();
 		//echo $this->db->last_query();exit;
 		return $r->result_array();
 	}

	 public function download_fresh_business_report($searchfilters)
 	{   
 	// 	$fy_year=get_current_fiancial_year();
 	// 	if($searchfilters['vtime']!='')
 	// 	{
 	// 		$parameter="cn.created_time"; 
 	// 		$where1=getReportTimelineCheck($searchfilters,$parameter,$fy_year);
 	// 	}
	// 	$this->db->select('ROUND(SUM( CASE WHEN ma.margin_approval_id IS NULL THEN (o.required_quantity*qd.total_value)*(1-qr.discount/100) ELSE ( CASE WHEN ma.discount_type = 1 THEN (o.required_quantity*qd.total_value)*(1-ma.discount/100) ELSE (o.required_quantity*qd.total_value)-(ma.discount*qd.currency_factor) END ) END )) AS total_orders,c.customer_id,c.name as c_name,cn.contract_note_id,GROUP_CONCAT(DISTINCT(cn.contract_note_id) SEPARATOR ",") as c_noteid,c.customer_id,o.opp_number,l.lead_number,pg.name as segment_name,p.description,sum(o.required_quantity ) as qty,sum(round((o.required_quantity*p.dp)/100000,2)) as value,l4.location as region,o.created_time,concat(u.first_name," ",u.last_name) as ename,p.name as part_number');
 	// 	$this->db->from('contract_note cn');
 	// 	$this->db->join('contract_note_quote_revision cnqr','cn.contract_note_id=cnqr.contract_note_id');
 	// 	$this->db->join('quote_revision qr','cnqr.quote_revision_id=qr.quote_revision_id');
 	// 	$this->db->join('quote_details qd','qr.quote_id=qd.quote_id');
 	// 	$this->db->join('opportunity o','qd.opportunity_id=o.opportunity_id');
 	// 	$this->db->join('opportunity_product op','o.opportunity_id=op.opportunity_id');
 	// 	$this->db->join('lead l','o.lead_id=l.lead_id');
 	// 	$this->db->join('customer c','l.customer_id=c.customer_id');
 	// 	$this->db->join('product p','op.product_id=p.product_id');
 	// 	$this->db->join('product_group pg','p.group_id=pg.group_id');
 	// 	$this->db->join('customer_location cl','c.customer_id=cl.customer_id','inner join');
 	// 	$this->db->join('location l1','cl.location_id=l1.location_id','inner join');
 	// 	$this->db->join('location l2','l1.parent_id=l2.location_id','inner join');
 	// 	$this->db->join('location l3','l2.parent_id=l3.location_id','inner join');
 	// 	$this->db->join('location l4','l3.parent_id=l4.location_id','inner join');
 	// 	$this->db->join('quote_op_margin_approval ma','ma.quote_revision_id = qr.quote_revision_id AND o.opportunity_id = ma.opportunity_id','LEFT');
	// 	$this->db->join('user u','l.user_id=u.user_id');
 	// 	$this->db->where('p.product_type_id',1);
 	// 	if($searchfilters['vtime']!='')
 	// 	{	
 	// 		if($where1!='')
 	// 			$this->db->where($where1);
 	// 	}
 	// 	$this->db->where('cn.company_id',$this->session->userdata('company'));
 	// 	// $this->db->where('op.product_id IN ('.$searchfilters['userProducts'].')');
 	// 	// $this->db->where('l.location_id IN ('.$searchfilters['userLocations'].') ');
 	// 	// $this->db->where('l.user_id IN ('.$searchfilters['reportee_users'].')');
 	// 	// if($searchfilters['region']!='')
 	// 	// {
 	//     // 	$this->db->where('l4.location_id',$searchfilters['region']);
 	//     // }
 	//     // $this->db->where('pg.name',$category);
 	//    $this->db->group_by('c.customer_id');
 	// 	$res=$this->db->get();
	// 	 return $res->result_array();

	$fy_year=get_current_fiancial_year();
		if($searchfilters['vtime']!='')
		{
			$parameter="cn.created_time"; 
			$where1=getReportTimelineCheck($searchfilters,$parameter,$fy_year);
		}
		$this->db->select('ROUND(SUM( CASE WHEN ma.margin_approval_id IS NULL THEN (o.required_quantity*qd.total_value)*(1-qr.discount/100) ELSE ( CASE WHEN ma.discount_type = 1 THEN (o.required_quantity*qd.total_value)*(1-ma.discount/100) ELSE (o.required_quantity*qd.total_value)-(ma.discount*qd.currency_factor) END ) END )) AS total_orders,p.product_id,cn.contract_note_id,c.name as c_name,GROUP_CONCAT(DISTINCT(cn.contract_note_id) SEPARATOR ",") as c_noteid,c.customer_id,o.opp_number,l.lead_number,pg.name as segment_name,p.description,sum(o.required_quantity ) as qty,l4.location as region,o.created_time,concat(u.first_name," ",u.last_name) as ename,p.name as part_number');
		$this->db->from('contract_note cn');
		$this->db->join('contract_note_quote_revision cnqr','cn.contract_note_id=cnqr.contract_note_id');
		$this->db->join('quote_revision qr','cnqr.quote_revision_id=qr.quote_revision_id');
		$this->db->join('quote_details qd','qr.quote_id=qd.quote_id');
		$this->db->join('opportunity o','qd.opportunity_id=o.opportunity_id');
		$this->db->join('opportunity_product op','o.opportunity_id=op.opportunity_id');
		$this->db->join('lead l','o.lead_id=l.lead_id');
		$this->db->join('customer c','l.customer_id=c.customer_id');
		$this->db->join('product p','op.product_id=p.product_id');
		$this->db->join('product_group pg','p.group_id=pg.group_id');
		$this->db->join('customer_location cl','c.customer_id=cl.customer_id','inner join');
		$this->db->join('location l1','cl.location_id=l1.location_id','inner join');
		$this->db->join('location l2','l1.parent_id=l2.location_id','inner join');
		$this->db->join('location l3','l2.parent_id=l3.location_id','inner join');
		$this->db->join('location l4','l3.parent_id=l4.location_id','inner join');
		$this->db->join('quote_op_margin_approval ma','ma.quote_revision_id = qr.quote_revision_id AND o.opportunity_id = ma.opportunity_id','LEFT');
		$this->db->join('user u','l.user_id=u.user_id');
		$this->db->where('p.product_type_id',1);
		$this->db->where('cn.company_id',$this->session->userdata('company'));
		if($searchfilters['vtime']!='')
		{	
			if($where1!='')
				$this->db->where($where1);
		}
		// $this->db->where('op.product_id IN ('.$searchfilters['userProducts'].')');
		// $this->db->where('l.location_id IN ('.$searchfilters['userLocations'].') ');
		// $this->db->where('l.user_id IN ('.$searchfilters['reportee_users'].')');
		// if($searchfilters['region']!='')
		// {
	    // 	$this->db->where('l4.location_id',$searchfilters['region']);
	    // }
	    // $this->db->where('l4.location',$category);
	    $this->db->group_by('c.customer_id');
		$res=$this->db->get();
		return $res->result_array();
	}

}