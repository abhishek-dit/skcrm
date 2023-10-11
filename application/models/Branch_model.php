<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Branch_model extends CI_Model {

    public function branchDetails($searchParams) {

        $this->db->select('*');
        $this->db->from('branch');
        if (@$searchParams['branchName'] != '')
            $this->db->like('name', @$searchParams['branchName']);
        $this->db->where('company_id',$this->session->userdata('company'));
        $this->db->order_by('name');
        $res = $this->db->get();

        $data = $res->result_array();
        return $data;
    }

    public function branchResults($searchParams, $per_page, $current_offset) {

        $this->db->select('branch_id,name,status');
        $this->db->from('branch');
        if (@$searchParams['branchName'] != '')
            $this->db->like('name', @$searchParams['branchName']);
        $this->db->where('company_id',$this->session->userdata('company'));
        $this->db->limit($per_page, $current_offset);
        $this->db->order_by('branch_id', 'DESC');
        $res = $this->db->get();

        $data = $res->result_array();
        return $data;
    }

    public function branchTotalRows($searchParams) {

        $this->db->from('branch');
        if (@$searchParams['branchName'] != '')
            $this->db->like('name', @$searchParams['branchName']);
        $this->db->where('company_id',$this->session->userdata('company'));
        $res = $this->db->get();
        return $res->num_rows();
    }

    function is_branchNameExist($branch_name,$branch_id)
    {
        //echo $branch_id; exit;
        $this->db->select();
        $this->db->where('name',$branch_name);  
                if($branch_id!='')
        $this->db->where('branch_id !=',$branch_id);
        $this->db->where('company_id',$this->session->userdata('company'));
        $query = $this->db->get('branch');
        return ($query->num_rows()>0)?1:0;
    }

    public function get_SE_users()
    {
        // $sql = "SELECT u.user_id,pi.punch_in_id, ll.latitude, ll.longitude, concat(u.first_name, ' _ ', u.employee_id) as user_name, pi.end_time FROM user u
        // JOIN punch_in pi ON pi.user_id=u.user_id JOIN live_location ll ON u.user_id=ll.user_id
        // WHERE u.company_id = '".$this->session->userdata('company')."' 
        // AND u.status=1 AND pi.created_time IN (SELECT MAX( p.created_time ) FROM punch_in p WHERE
        // p.user_id = pi.user_id ) GROUP BY pi.user_id ORDER BY u.user_id DESC";
        // $res = $this->db->query($sql);
        // return $res->result_array();
        
        // new code from 11/26/2021
        // $sql = "SELECT u.user_id,pi.punch_in_id,ll.live_location_id, ll.latitude, ll.longitude, concat(u.first_name, ' _ ', u.employee_id) as user_name, ll.end_time FROM user u
        // JOIN punch_in pi ON pi.user_id=u.user_id JOIN live_location ll ON u.user_id=ll.user_id
        // WHERE u.company_id = '".$this->session->userdata('company')."' 
        // AND u.status=1 AND ll.created_time IN (SELECT MAX( p.created_time ) FROM live_location p WHERE
        // p.user_id = pi.user_id ) GROUP BY pi.user_id ORDER BY u.user_id DESC";
        // $res = $this->db->query($sql);
        // return $res->result_array();
        // end.

        //new code from 18/03/2022 adding index
        $sql = "SELECT pi.status,u.user_id,pi.punch_in_id,ll.mobile_live_location_id, ll.latitude, ll.longitude, concat(u.first_name, ' _ ', u.employee_id) as user_name, ll.end_time FROM user u
        JOIN punch_in pi ON pi.user_id=u.user_id JOIN mobile_live_location ll ON u.user_id=ll.user_id
        WHERE u.company_id = '".$this->session->userdata('company')."' 
        AND u.status=1 AND ll.created_time IN (SELECT p.created_time FROM mobile_live_location p WHERE
        p.user_id = pi.user_id ) GROUP BY pi.user_id ORDER BY u.user_id DESC";
        $res = $this->db->query($sql);
        return $res->result_array();
        //end

        // $sql = "SELECT u.user_id,pi.live_location_id, pi.latitude, pi.longitude, concat(u.first_name, ' _ ', u.employee_id) 
        // as user_name FROM user u JOIN live_location pi ON pi.user_id=u.user_id 
        // WHERE u.company_id = '".$this->session->userdata('company')."' AND u.status=1 AND pi.created_time 
        // IN (SELECT MAX( p.created_time ) FROM punch_in p WHERE p.user_id = pi.user_id ) 
        // GROUP BY pi.user_id ORDER BY u.user_id DESC";
        // $res = $this->db->query($sql);
        // return $res->result_array();

        // $currentDateTime = date('Y-m-d H:i:s');
        // $sql = "SELECT u.user_id,pi.live_location_id, pi.latitude, pi.longitude, concat(u.first_name, ' _ ', u.employee_id) as user_name FROM user u JOIN live_location pi ON pi.user_id=u.user_id WHERE u.company_id = '".$this->session->userdata('company')."' AND u.status=1 AND pi.created_time='".$currentDateTime."' FROM live_location p WHERE p.user_id = pi.user_id ) GROUP BY pi.user_id ORDER BY u.user_id DESC";
        // $res = $this->db->query($sql);
        // return $res->result_array();


        //=======query working only for 1 user when logged in and logout===========
        // $this->db->select("u.user_id,pi.live_location_id, pi.latitude, pi.longitude, 
        //concat(u.first_name, ' _ ', u.employee_id) as user_name, pi.end_time");
        // $this->db->from('user u');
        // $this->db->join('live_location pi','pi.user_id=u.user_id');
        // $this->db->where('u.company_id',$this->session->userdata('company'));
        // $this->db->where('u.status',1);
        // $this->db->where('DATE(pi.created_time)',date('Y-m-d'));
        // $this->db->order_by('u.user_id','DESC');
		// $this->db->group_by('pi.user_id');
        // $qry = $this->db->get();
        // return $qry->result_array();
    }
    
    public function check_punch_in($user_id)
    {
        $this->db->select('punch_in_id');
        $this->db->from('punch_in');
        $this->db->where('user_id',$user_id);
        $this->db->where('end_time IS NULL');
        $this->db->where('DATE(start_time)',date('Y-m-d'));
        $qry = $this->db->get();
        return $qry->row_array();
    }

}
