<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Contact_model extends CI_Model {

    public function get_details($start, $len,$search_fields=array()) {
        $qry = "SELECT * from 
                (SELECT cu.customer_id, s.speciality_id, c.contact_id, concat(c.first_name, ' ',c.last_name) as first_name, 
                c.last_name, case when cu.department!='' then concat(cu.name, ' ', cu.name1, ' - ',cu.department ,' (', l.location, ')') else  concat(cu.name, ' ', cu.name1, ' (', l.location, ')') end as `customer`, 
                s.name as `speciality`, c.email, c.mobile_no, c.status, c.address1, c.address2, c.fax, c.telephone, c.salutation from contact c
                inner join speciality s on s.speciality_id = c.speciality_id
                inner join customer_location_contact clc on clc.contact_id = c.contact_id
                inner join customer cu on cu.customer_id = clc.customer_id
                inner join location l on l.location_id = clc.location_id
                where l.location_id IN (".$this->session->userdata('locationString').") and cu.company_id ='".$_SESSION['company']."' ) t WHERE 1 ";
       
        if(count($search_fields)>0)
        {

            if($search_fields['customer']!='')
                    $qry .=" AND t.customer_id  = '".$this->db->escape_str($search_fields['customer'])."' ";
            if($search_fields['c_speciality']!='')
                    $qry .=" AND t.speciality_id  = '".$this->db->escape_str($search_fields['c_speciality'])."' ";
            if($search_fields['contactName']!='')
                    $qry .=" AND t.first_name like('".$this->db->escape_str($search_fields['contactName'])."%') ";
        }
        //$qry .=" AND cu.company_id  = '".$this->session->userdata('company')."' ";
        $num_qry = $qry;
        $qry.=" LIMIT " . $start . " , " . $len;

        //echo $qry; die();
        $res1 = $this->db->query($qry);
        //$res2 = $this->db->query($num_qry);
        $res['resArray'] = $res1->result_array();
        $qry_data = $res1->result();
        $res['count'] = 1;
        return $res;
    }
    public function contactTotalRows($search_fields=array()) {
        $qry = "SELECT count(*) cnt FROM contact c
                inner join customer_location_contact clc on clc.contact_id = c.contact_id
                inner join customer cu on cu.customer_id = clc.customer_id WHERE 1 
                and clc.location_id IN (".$this->session->userdata('locationString').") ";
       // 'customerName','customerName','speciality','department','customerName','category_sub_id'
       
        if(count($search_fields)>0){
            if($search_fields['customer']!='')
                    $qry .=" AND clc.customer_id  = '".$this->db->escape_str($search_fields['customer'])."' ";
            if($search_fields['c_speciality']!='')
                    $qry .=" AND c.speciality_id  = '".$this->db->escape_str($search_fields['c_speciality'])."' ";
            if($search_fields['contactName']!='')
                    $qry .=" AND c.first_name like('".$this->db->escape_str($search_fields['contactName'])."%') ";
        }
        $qry .=" AND cu.company_id  = '".$this->session->userdata('company')."' ";
        $num_qry = $qry;
        //$qry.=" LIMIT " . $start . " , " . $len;

        //echo $qry; die();
        $res1 = $this->db->query($qry);
        $qry_data = $res1->result();
         
        return $qry_data[0]->cnt;
        
    }
public function get_download_details($search_fields=array()) {
    
        $qry = "SELECT * from 
                (SELECT cu.customer_id, s.speciality_id, c.contact_id, concat(c.first_name, ' ',c.last_name) as first_name, 
                c.last_name, concat(cu.name, ' (', l.location, ')')  as `customer`, 
                s.name as `speciality`, c.email, c.mobile_no, c.status,c.created_by,c.created_time,c.modified_by,c.modified_time from contact c
                inner join speciality s on s.speciality_id = c.speciality_id
                inner join customer_location_contact clc on clc.contact_id = c.contact_id
                inner join location l on l.location_id = clc.location_id
                inner join customer cu on cu.customer_id = clc.customer_id
               where l.location_id IN (".$this->session->userdata('locationString').") and cu.company_id ='".$_SESSION['company']."' ) t WHERE 1 ";
       
       // 'customerName','customerName','speciality','department','customerName','category_sub_id'
       
        if(count($search_fields)>0){
            if($search_fields['customer']!='')
                    $qry .=" AND t.customer_id  = '".$this->db->escape_str($search_fields['customer'])."' ";
            if($search_fields['c_speciality']!='')
                    $qry .=" AND t.speciality_id  = '".$this->db->escape_str($search_fields['c_speciality'])."' ";
            if($search_fields['contactName']!='')
                    $qry .=" AND t.first_name like('".$this->db->escape_str($search_fields['contactName'])."%') ";
        }
        //$qry .=" AND cu.company_id  = '".$this->session->userdata('company')."' ";
        $num_qry = $qry;
        $res1 = $this->db->query($qry);
        $res2 = $this->db->query($num_qry);
        return $res1->result_array();
    }

    public function getCustomerInfo()
    {
        $qry = "SELECT customer_id, name from customer where status = 1";
        $res = $this->db->query($qry);    
        return $res->result_array();
    }

    public function getSpecialityInfo()
    {
        $qry = "SELECT speciality_id, name from speciality where status = 1";
        $res = $this->db->query($qry);    
        return $res->result_array();
    }

    public function getContactData($contact_id)
    {
        $qry = "SELECT c.*, clc.customer_id from contact c 
                inner join customer_location_contact clc on clc.contact_id = c.contact_id
                where c.contact_id = ".$contact_id;
        $res = $this->db->query($qry);
        return $res->result_array();        
    }

    public function getCustomerDetails($contact_id)
    {
        $q = "SELECT c.customer_id, concat(c.name, ' (', l.location, ')') as customer 
            from customer c INNER JOIN customer_location_contact clc on clc.customer_id = c.customer_id
            INNER JOIN location l ON l.location_id = clc.location_id
            WHERE clc.contact_id = '".$contact_id."'";
        $r = $this->db->query($q);
        if($r->num_rows() > 0)
        {
            $data = $r->result_array();
            return $data[0];
        }    
        else
            return array('customer_id'=>'', 'customer' =>'--Select Customer--');
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

    public function get_contact_details($start, $len,$search_fields=array()) {
        $qry = "SELECT * from 
                (SELECT cu.customer_id, s.speciality_id, c.contact_id, concat(c.first_name, ' ',c.last_name) as first_name, 
                c.last_name, case when cu.department!='' then concat(cu.name, ' ', cu.name1, ' - ',cu.department ,' (', l.location, ')') else  concat(cu.name, ' ', cu.name1, ' (', l.location, ')') end as `customer`, 
                s.name as `speciality`, c.email, c.mobile_no, c.status, c.address1, c.address2, c.fax, c.telephone, c.salutation from contact c
                inner join speciality s on s.speciality_id = c.speciality_id
                inner join customer_location_contact clc on clc.contact_id = c.contact_id
                inner join customer cu on cu.customer_id = clc.customer_id
                inner join location l on l.location_id = clc.location_id
                where l.location_id IN (".$this->session->userdata('locationString').") and cu.company_id ='".$_SESSION['company']."' ) t WHERE 1 ";
       
        if(count($search_fields)>0)
        {

            if($search_fields['customer']!='')
                    $qry .=" AND t.customer_id  = '".$this->db->escape_str($search_fields['customer'])."' ";
            if($search_fields['c_speciality']!='')
                    $qry .=" AND t.speciality_id  = '".$this->db->escape_str($search_fields['c_speciality'])."' ";
            if($search_fields['contactName']!='')
                    $qry .=" AND t.first_name like('".$this->db->escape_str($search_fields['contactName'])."%') ";
        }
        $num_qry = $qry;
        $qry.=" LIMIT " . $start . " , " . $len;
        $res1 = $this->db->query($qry);
        $res1 = $this->db->query($qry);
        $res = $res1->result_array();
        return $res;
    }

}
