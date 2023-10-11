<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Customer_category_model extends CI_Model {

    public function categoryDetails($searchParams) {

        $this->db->select('*');
        $this->db->from('customer_category');
        if (@$searchParams['category_name'] != '')
            $this->db->like('name', @$searchParams['category_name']);
        $this->db->where('company_id',$this->session->userdata('company'));
        $this->db->order_by('name');
        $res = $this->db->get();

        $data = $res->result_array();
        return $data;
    }

    public function categoryResults($searchParams, $per_page, $current_offset) {

        $this->db->select('category_id,name,status');
        $this->db->from('customer_category');
        if (@$searchParams['category_name'] != '')
            $this->db->like('name', @$searchParams['category_name']);
        $this->db->where('company_id',$this->session->userdata('company'));
        $this->db->limit($per_page, $current_offset);
        $this->db->order_by('category_id', 'DESC');
        $res = $this->db->get();

        $data = $res->result_array();
        return $data;
    }

    public function categoryTotalRows($searchParams) {

        $this->db->from('customer_category');
        if (@$searchParams['category_name'] != '')
            $this->db->like('name', @$searchParams['category_name']);
        $this->db->where('company_id',$this->session->userdata('company'));
        $res = $this->db->get();
        return $res->num_rows();
    }

    function is_categoryNameExist($name,$category_id)
    {
        //echo $branch_id; exit;
        $this->db->select();
        $this->db->where('name',$name);  
                if($category_id!='')
        $this->db->where('category_id !=',$category_id);
        $this->db->where('company_id',$this->session->userdata('company'));
        $query = $this->db->get('customer_category');
        return ($query->num_rows()>0)?1:0;
    }

    public function subResults($searchParams, $per_page, $current_offset) {

        $this->db->select('csc.*,cc.name as customer,ccd.status as ccd_status');
        $this->db->from('customer_sub_category csc');
        $this->db->join('customer_category_details ccd','ccd.category_sub_id=csc.category_sub_id');
        $this->db->join('customer_category cc','cc.category_id=ccd.category_id');
        if (@$searchParams['sub_category'] != '')
            $this->db->like('csc.name', @$searchParams['sub_category']);
        if (@$searchParams['category'] != '')
            $this->db->like('cc.name', @$searchParams['category']);
        $this->db->where('cc.company_id',$this->session->userdata('company'));

        $this->db->limit($per_page, $current_offset);
        $this->db->order_by('category_sub_id', 'DESC');
        $res = $this->db->get();

        $data = $res->result_array();
        return $data;
    }

    public function subTotalRows($searchParams) {

        $this->db->from('customer_sub_category csc');
        $this->db->join('customer_category_details ccd','ccd.category_sub_id=csc.category_sub_id');
        $this->db->join('customer_category cc','cc.category_id=ccd.category_id');
        if (@$searchParams['sub_category'] != '')
            $this->db->like('csc.name', @$searchParams['sub_category']);
        if (@$searchParams['category'] != '')
            $this->db->like('cc.name', @$searchParams['category']);
        $this->db->where('cc.company_id',$this->session->userdata('company'));
        $res = $this->db->get();
        return $res->num_rows();
    }

    public function subDetails($searchParams) {

        $this->db->select('csc.*,cc.name as customer');
        $this->db->from('customer_sub_category csc');
        $this->db->join('customer_category_details ccd','ccd.category_sub_id=csc.category_sub_id');
        $this->db->join('customer_category cc','cc.category_id=ccd.category_id');
        if (@$searchParams['sub_category'] != '')
            $this->db->like('csc.name', @$searchParams['sub_category']);
        if (@$searchParams['category'] != '')
            $this->db->like('cc.name', @$searchParams['category']);
        $this->db->where('cc.company_id',$this->session->userdata('company'));
        $this->db->order_by('name');
        $res = $this->db->get();

        $data = $res->result_array();
        return $data;
    }

    function is_subcategoryNameExist($name,$sub_category_id,$category_id)
    {
        //echo $branch_id; exit;
        $this->db->select();
        $this->db->from('customer_sub_category csc');
        $this->db->join('customer_category_details ccd','ccd.category_sub_id=csc.category_sub_id');
        $this->db->join('customer_category cc','cc.category_id=ccd.category_id');
        $this->db->where('csc.name',$name);  
                if($sub_category_id!='')
        $this->db->where('csc.category_sub_id !=',$sub_category_id);
        $this->db->where('ccd.category_id',$category_id);
        $this->db->where('cc.company_id',$this->session->userdata('company'));
        $query = $this->db->get();
        
        return ($query->num_rows()>0)?1:0;
    }

    public function insert_update($category_id,$sub_category_id)
    {
        $qry = "INSERT INTO customer_category_details(category_id,category_sub_id, status) 
                    VALUES (".$category_id.",".$sub_category_id.",'1')  
                    ON DUPLICATE KEY UPDATE status = VALUES(status);";
        $this->db->query($qry);
    }

}
