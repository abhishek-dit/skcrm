<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Preference_model extends CI_Model {

	public function get_preference_section_list()
	{
		$this->db->select('*');
		$this->db->from('preference_section');
		$this->db->where('b_display',1);
		$this->db->order_by('rank ASC');
		$res=$this->db->get();
		return $res->result_array();
	}
	public function get_preference_list($section_id,$company_id) 
	{
		$this->db->select('*');
		$this->db->from('preference');
		$this->db->where('section_id',$section_id);
		$this->db->where('company_id',$company_id);
		$this->db->where('b_display',1);
		$this->db->order_by('rank ASC');
		$res=$this->db->get();
		return $res->result_array();
	}

}