<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Currency_conversion_Model extends CI_Model {

	public function get_company_currency_transactions($to_currency_id)
	{
		$this->db->select('ct.from_currency_id,ct.to_currency_id,ct.value,c.code as to_currency_code');
		$this->db->from('currency_transaction ct');
		$this->db->join('currency c','c.currency_id=ct.to_currency_id');
		$this->db->where('ct.to_currency_id',$to_currency_id);
		$this->db->where('ct.status',1);
		$res = $this->db->get();
		return $res->result_array();
	}
	public function get_old_currency_data($from_currency_id,$to_currency_id)
	{
		$this->db->select('from_currency_id,to_currency_id,value,currency_transaction_id');
		$this->db->from('currency_transaction');
		$this->db->where('from_currency_id',$from_currency_id);
		$this->db->where('to_currency_id',$to_currency_id);
		$this->db->where('status',1);
		$res = $this->db->get();
		return $res->row_array();
	}

}