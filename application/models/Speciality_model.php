<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Speciality_model extends CI_Model {

   public function specialityDetails($searchParams) {

        $this->db->select('*');
        $this->db->from('speciality');
        if (@$searchParams['specialityName'] != '')
            $this->db->like('name', @$searchParams['specialityName']);
        $this->db->where('company_id',$this->session->userdata('company'));
        $this->db->order_by('name');
        $res = $this->db->get();

        $data = $res->result_array();
        return $data;
    }

   public function specialityResults($searchParams, $per_page, $current_offset) {

        $this->db->select('speciality_id,name,status');
        $this->db->from('speciality');
        if (@$searchParams['specialityName'] != '')
            $this->db->like('name', @$searchParams['specialityName']);
        $this->db->where('company_id',$this->session->userdata('company'));
        $this->db->limit($per_page, $current_offset);
        $this->db->order_by('speciality_id', 'DESC');
        $res = $this->db->get();

        $data = $res->result_array();
        return $data;
    }

     public function specialityTotalRows($searchParams) {

        $this->db->from('speciality');
        if (@$searchParams['specialityName'] != '')
            $this->db->like('name', @$searchParams['specialityName']);
        $this->db->where('company_id',$this->session->userdata('company'));
        $res = $this->db->get();
        return $res->num_rows();
    }

    

}
