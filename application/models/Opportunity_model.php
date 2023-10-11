<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Opportunity_model extends CI_Model {


	function __construct(){
		parent::__construct();
	}
	
	/**
	* get OPPORTUNITIES BY LEAD ID
	* author: mahesh , created on: 6th july 2016 12:39 PM, updated on: --
	* params: $leadID(int)
	* return: $opportunities(array)
	**/
	function getOpportunityResultsByLead($leadID, $type = 1){
		//type = 1 -- By Lead ------ type = 2 -- By Opportunity
		$this->db->select('o.*,op.product_id,concat(p.name, " (", p.description, ")") as name,
						os.name as stage,pg.group_id,pg.category_id, concat(pg.name, " (", pg.description, ")") as `group`, 
						pc.name as category, sf.name as source_of_fund, r.name as relationship');
		$this->db->from('opportunity o');
		$this->db->join('opportunity_product op','op.opportunity_id=o.opportunity_id');
		$this->db->join('product p','op.product_id=p.product_id');
		$this->db->join('product_group pg','pg.group_id=p.group_id');
		$this->db->join('product_category pc','pc.category_id=pg.category_id');
		$this->db->join('source_of_funds sf','sf.fund_source_id=o.fund_source_id');
		$this->db->join('relationship r','r.relationship_id=o.relationship_id');
		$this->db->join('opportunity_status os','os.status=o.status');
		$field = 'o.lead_id';
		if($type == 2)
			$field = 'o.opportunity_id';

		$this->db->where($field,$leadID);
		$this->db->where('o.company_id',$this->session->userdata('company'));
		$this->db->order_by('opportunity_id','DESC');
		$query = $this->db->get();
		//echo $this->db->last_query();die;
		return $query->result_array();
	}

	/*mahesh code: 24-12-2017*/
	function getLoggedInUserProductCategoriesDropdown()
	{
		$this->db->select('pc.*');
		$this->db->from('product_category pc');
		$this->db->join('product_group pg','pc.category_id = pg.category_id');
		$this->db->join('product p','pg.group_id = p.group_id');
		$this->db->join('user_product up','p.product_id = up.product_id and up.status = 1');
		$this->db->where('up.user_id',$this->session->userdata('user_id'));
		$this->db->where('pc.company_id',$this->session->userdata('company'));
		$this->db->group_by('pc.category_id');
		$res = $this->db->get();
		if($res->num_rows()>0)
		{
			$data = array();
			foreach($res->result_array() as $row)
                {
                    $data[$row['category_id']] = $row['name'];
                }
                return $data;
		}
		return array();
	}

}