<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Mahesh
 * Description: Custom model class
 */
class Ajax_m extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	
	/**
	* get locations by parent
	* author: mahesh , created on: 17th june 2016 12:00 PM, updated on: --
	* params: $parentId(int)
	* return: $locations(array)
	**/
	function getLocationsByParent($parentId){
		$this->db->select();
		$this->db->where('parent_id',$parentId);
		$this->db->order_by('location','ASC');
		$query = $this->db->get('location');
		return $query->result_array();
	}
	
	/**
	* get locations by parent
	* author: mahesh , created on: 17th june 2016 12:00 PM, updated on: --
	* params: $parentId(int)
	* return: $location name(string)
	**/
	function getLocationNameById($locationId){
		$this->db->select('location');
		$this->db->where('location_id',$locationId);
		$query = $this->db->get('location');
		$res = $query->row_array();
		return $res['location'];
	}
	
	/**
	* get product groups by category
	* author: mahesh , created on: 20th june 2016 12:52 PM, updated on: --
	* params: $productCategoryId(int)
	* return: $productgroups(array)
	**/
	function getPorductGroupsByCategory($productCategoryId){
		$this->db->select();
		$this->db->where('category_id',$productCategoryId);
		$this->db->where('status',1);	
		$this->db->order_by('name','ASC');
		$query = $this->db->get('product_group');
		return $query->result_array();
	}
	
	/**
	* get product category name by id
	* author: mahesh , created on: 20th june 2016 12:55 PM, updated on: --
	* params: $productCategoryId(int)
	* return: $productCategoryName(string)
	**/
	function getProductCategoryNameById($productCategoryId){
		$this->db->select('name');
		$this->db->where('category_id',$productCategoryId);
		$query = $this->db->get('product_category');
		$res = $query->row_array();
		return $res['name'];
	}
	
	/**
	* get products by group
	* author: mahesh , created on: 20th june 2016 01:26 PM, updated on: --
	* params: $productGroupId(int)
	* return: $products(array)
	**/
	function getPorductsByGroup($productGroupId){
		$this->db->select();
		$this->db->where('group_id',$productGroupId);
		$this->db->where('status',1);	
		$this->db->order_by('name','ASC');
		$query = $this->db->get('product');
		return $query->result_array();
	}

	/**
	* check employee id exist or not
	* author: mahesh , created on: 23rd june 2016 05:40 PM, updated on: --
	* @param: $employee_id(string)
	* @param: $user_id(int)
	* return: 1/0(boolean)
	**/
	function is_employeeIdExist($employee_id,$user_id){
		
		$this->db->select();
		$this->db->where('employee_id',$employee_id);
		if($user_id!='')
		$this->db->where('user_id<>',$user_id);	
		$query = $this->db->get('user');
		return ($query->num_rows()>0)?1:0;
  	}

  	/**
	* check employee email exist or not
	* author: mahesh , created on: 23rd june 2016 07:00 PM, updated on: --
	* @param: $email(string)
	* @param: $user_id(int)
	* return: 1/0(boolean)
	**/
	function is_employeeEmailExist($email,$user_id){
		
		$company_id=$this->session->userdata('company');
		$this->db->select();
		$this->db->where('email_id',$email);
		if($user_id!='')
		$this->db->where('user_id<>',$user_id);	
		$this->db->where('company_id',$company_id);
		$query = $this->db->get('user');
		return ($query->num_rows()>0)?1:0;
  	}

  	/**
	* Get competitors by product category
	* author: mahesh , created on: 5th july 2016 04:36 PM, updated on: --
	* @param: $product_category(string)
	* return: competitors_options_list(String)
	**/
	function getCompetitorsByProductCategory($category_id){
		$this->db->select('c.*');
		$this->db->from('product_category_competitor pcc');
		$this->db->where('pcc.category_id',$category_id);
		$this->db->join('competitor c','c.competitor_id=pcc.competitor_id','inner');
		$this->db->where('c.company_id',$this->session->userdata('company'));	
		$this->db->where('c.status',1);	
		$this->db->order_by('name','ASC');
		$query = $this->db->get();
		return $query->result_array();
	}

	/**
	* check customer code exist or not
	* author: mahesh , created on: 19th july  2016 02:00 AM, updated on: --
	* @param: $customer code(string)
	* @param: $customer id(int)
	* return: 1/0(boolean)
	**/
	function is_customerCodeExist($customer_code,$customer_id){
		
		$this->db->select();
		$this->db->where('remarks2',$customer_code);
		$this->db->where('company_id',$this->session->userdata('company'));
		if($customer_id!='')
		$this->db->where('customer_id<>',$customer_id);	
		$query = $this->db->get('customer');
		return ($query->num_rows()>0)?1:0;
  	}


  	function is_customerNameExist($customer_name,$customer_id){
		$arr = array(2,3);
		$this->db->select();
		$this->db->where('name',$customer_name);
		$this->db->where('company_id',$this->session->userdata('company'));
		if($customer_id!='')
		$this->db->where('customer_id<>',$customer_id);	
		$this->db->where_not_in('status',$arr);
		$query = $this->db->get('customer');
		return ($query->num_rows()>0)?1:0;
  	}

  	function is_companyNameExist($company_name,$company_id){
		$this->db->select();
		$this->db->where('name',$company_name);
		if($company_id!='')
		$this->db->where('company_id<>',$company_id);	
		$this->db->where('status',1);
		$query = $this->db->get('company');
		return ($query->num_rows()>0)?1:0;
  	}
	  function is_emailExist($email){
		$this->db->select('*');
		$this->db->from('email');
		$this->db->where('email_id',$email);
		$this->db->where('status',1);
		$query = $this->db->get();
		return ($query->num_rows()>0)?1:0;
  	}
        
    function is_specialityNameExist($speciality_name,$speciality_id)
    {
		
		$this->db->select();
		$this->db->where('name',$speciality_name);	
        if($speciality_id!='')
		$this->db->where('speciality_id<>',$speciality_id);
		$this->db->where('company_id',$this->session->userdata('company'));
		$query = $this->db->get('speciality');
		return ($query->num_rows()>0)?1:0;
  	}
  	public function is_channel_partnerNameExist($name,$channel_partner_id)
  	{
  		$this->db->select();
		$this->db->where('name',$name);	
                if($channel_partner_id!='')
		$this->db->where('channel_partner_id<>',$channel_partner_id);
		$this->db->where('company_id',$this->session->userdata('company'));
		$query = $this->db->get('channel_partner');
		return ($query->num_rows()>0)?1:0;
  	}
	
}