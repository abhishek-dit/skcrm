<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Channel_partner_model extends CI_Model {

    public function channelDetails($searchParams) {

        $this->db->select('*');
        $this->db->from('channel_partner');
        if (@$searchParams['channelname'] != '')
            $this->db->like('name', @$searchParams['channelname']);
        $this->db->where('company_id',$this->session->userdata('company'));
        $this->db->where('type',1);
        $this->db->order_by('name');
        $res = $this->db->get();

        $data = $res->result_array();
        return $data;
    }

    public function channelResults($searchParams, $per_page, $current_offset) {

        $this->db->select('*');
        $this->db->from('channel_partner');
        if (@$searchParams['channelname'] != '')
            $this->db->like('name', @$searchParams['channelname']);
        $this->db->where('company_id',$this->session->userdata('company'));
        $this->db->where('type',1);
        $this->db->limit($per_page, $current_offset);
        $this->db->order_by('channel_partner_id', 'DESC');
        $res = $this->db->get();

        $data = $res->result_array();
        return $data;
    }

    public function channelTotalRows($searchParams) {

        $this->db->from('channel_partner');
        if (@$searchParams['channelname'] != '')
            $this->db->like('name', @$searchParams['channelname']);
        $this->db->where('company_id',$this->session->userdata('company'));
        $this->db->where('type',1);
        $res = $this->db->get();
        return $res->num_rows();
    }

    

}
