<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Product_opening_stock_model extends CI_Model {


    public function get_product_group()
    {
    	$this->db->select('pg.*,pg.name as group_name');
    	$this->db->from('product_group pg');
    	$this->db->where('pg.status',1);
    	$res = $this->db->get();
		return $res->result_array();
    }

    public function get_product_category($user_id)
    {
    	$this->db->select('pc.*,pc.name as category_name');
    	$this->db->from('user_product up');
        $this->db->join('product p','p.product_id=up.product_id');
        $this->db->join('product_group pg','pg.group_id=p.group_id');
        $this->db->join('product_category pc','pc.category_id=pg.category_id');
        $this->db->group_by('pc.category_id');
        $this->db->where('up.user_id',$user_id);
    	$this->db->where('up.status',1);
    	$res = $this->db->get();
		return $res->result_array();
    }

    public function get_group_by_product_category($category_id,$user_id)
    {
    	$this->db->select('pg.*,pg.name as group_name');
        $this->db->from('user_product up');
        $this->db->join('product p','p.product_id=up.product_id');
        $this->db->join('product_group pg','pg.group_id=p.group_id');
        $this->db->join('product_category pc','pc.category_id=pg.category_id');
        $this->db->where('pg.category_id',$category_id);
        $this->db->where('up.user_id',$user_id);
        $this->db->where('up.status',1);
        $this->db->group_by('pg.group_id');
        $res = $this->db->get();
        return $res->result_array();
	}

    public function get_products_by_product_group($group_id,$user_id)
	{
		$this->db->select('p.*,p.name as product_name');
        $this->db->from('user_product up');
        $this->db->join('product p','p.product_id=up.product_id');
        $this->db->join('product_group pg','pg.group_id=p.group_id');
        $this->db->join('product_category pc','pc.category_id=pg.category_id');
        $this->db->where('p.group_id',$group_id);
        $this->db->where('up.user_id',$user_id);
        $this->db->where('up.status',1);
        $this->db->group_by('up.product_id');
        $res = $this->db->get();
        //echo $this->db->last_query();exit;
        return $res->result_array();
	}

    public function update_product_opening_stock($opening_stock,$user_id)
    {
        $this->db->select('*');
        $this->db->from('dealer_product_stock dps');
        $this->db->where('product_id',$opening_stock['product_id']);
        $this->db->where('dps.user_id',$user_id);
        $res=$this->db->get();
        if($res->num_rows()>0)
        { 
           $query='update dealer_product_stock set opening_stock="'.$opening_stock['opening_stock'].'" where product_id="'.$opening_stock['product_id'].'" AND user_id = '.$opening_stock['user_id'];
            $this->db->query($query);
        }
        else
        {
            $this->Common_model->insert_data('dealer_product_stock',$opening_stock);
        }
    }
    public function get_dist_list_rows($searchParams,$locations)
    {
        $this->db->select('dd.*');
        $this->db->from('user_location ul');
        $this->db->join('distributor_details dd','ul.user_id=dd.user_id');
        $this->db->join('user u','dd.user_id=u.user_id');
        if($searchParams['users_id']!='')
        $this->db->where('dd.user_id',$searchParams['users_id']);
        $this->db->where('ul.status',1);
        $this->db->where_in('ul.location_id',$locations);
        $this->db->group_by('dd.user_id');
        $res = $this->db->get();
        return $res->num_rows();
    }
    public function get_dist_list_results($searchParams, $per_page, $current_offset,$locations)
    {
        $this->db->select('dd.*,u.*');
        $this->db->from('user_location ul');
        $this->db->join('distributor_details dd','ul.user_id=dd.user_id');
        $this->db->join('user u','dd.user_id=u.user_id');
        if($searchParams['users_id']!='')
        $this->db->where('dd.user_id',$searchParams['users_id']);
        $this->db->where('ul.status',1);
        $this->db->where_in('ul.location_id',$locations);
        $this->db->group_by('dd.user_id');
        $this->db->limit($per_page, $current_offset);
        $res = $this->db->get();
        return $res->result_array();
    }
}