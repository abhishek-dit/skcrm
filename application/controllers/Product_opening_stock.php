<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once 'Base_controller.php';

class Product_opening_stock extends Base_controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("Product_opening_stock_model");
        
    }

    public function get_rbh_distributor_list()
    {
        $defined_roles=array(7);
        $role=$this->session->userdata('role_id');
        if(in_array($role, $defined_roles))
        {
            # Data Array to carry the require fields to View and Model
            $data['nestedView']['heading'] = "Distributor List";
            $data['nestedView']['cur_page'] = 'get_rbh_distributor_list Details';
            $data['nestedView']['parent_page'] = 'get_rbh_distributor_list';

            # Load JS and CSS Files
            $data['nestedView']['js_includes'] = array();
            $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
            $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/iCRM/customer_contact.js"></script>';
            $data['nestedView']['css_includes'] = array();

            # Breadcrumbs
            $data['nestedView']['breadCrumbTite'] = 'Distributor List';
            $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
            //$data['nestedView']['breadCrumbOptions'][] = array('label' => 'Product Opening Stock', 'class' => 'active', 'url' => '');
            $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Distributor List', 'class' => 'active', 'url' => '');
            //$data['lead_id'] = $lead_id;
            $user_id = $this->session->userdata('user_id');
            //retreving user locations
            $locations =  getUserLocations($user_id);

            # Search Functionality
            $psearch=$this->input->post('search', TRUE);
            if($psearch!='') {
            $searchParams=array(
                          'users_id'=>$this->input->post('users_id')
                          );
            $this->session->set_userdata($searchParams);
            } else {
                
                if($this->uri->segment(2)!='')
                {
                $searchParams=array(
                          'users_id'=>$this->session->userdata('users_id')
                           );
                }
                else {
                    $searchParams=array(
                          'users_id'=>'',
                           );
                    $this->session->unset_userdata(array_keys($searchParams));
                }
                
            }
            $data['searchParams'] = $searchParams;
           // print_r($searchParams);exit;
            /* pagination start */
            $config = get_paginationConfig();
            $config['base_url'] = SITE_URL.'get_rbh_distributor_list/'; 
            # Total Records
            $config['total_rows'] = $this->Product_opening_stock_model->get_dist_list_rows($searchParams,$locations);
            $config['per_page'] = $this->global_functions->getDefaultPerPageRecords();
            $data['total_rows'] = $config['total_rows'];
            $this->pagination->initialize($config);
            $data['pagination_links'] = $this->pagination->create_links(); 
            $current_offset = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
            if($data['pagination_links']!= '') {
                $data['last']=$this->pagination->cur_page*$config['per_page'];
                if($data['last']>$data['total_rows']){
                    $data['last']=$data['total_rows'];
                }
                $data['pagermessage'] = 'Showing '.((($this->pagination->cur_page-1)*$config['per_page'])+1).' to '.($data['last']).' of '.$data['total_rows'];
             } 
             $data['sn'] = $current_offset + 1;
            /* pagination end */
            
            # Search Results
            $data['searchResults'] = $this->Product_opening_stock_model->get_dist_list_results($searchParams,$config['per_page'], $current_offset,$locations);
            $data['users'] = $this->Common_model->get_dropdown("user", "user_id", "first_name",array('role_id'=>5,'status'=>1,'company_id'=>$this->session->userdata('company')),'concat("(",employee_id,")-",first_name,last_name)first_name');
           $this->load->view('product_opening_stock/rbh_dist_list',$data);
        }
        else
        {
            echo 'Your Role is unable to access this page';
        }
    }

    public function product_opening_stock_details()
    {
    	# Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Product Opening Stock Details";
        $data['nestedView']['cur_page'] = 'Product_opening_stock Details';
        $data['nestedView']['parent_page'] = 'Product_opening_stock';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/iCRM/customer_contact.js"></script>';
        $data['nestedView']['css_includes'] = array();

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'Product Opening Stock';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        //$data['nestedView']['breadCrumbOptions'][] = array('label' => 'Product Opening Stock', 'class' => 'active', 'url' => '');
   		$data['nestedView']['breadCrumbOptions'][] = array('label' => 'Product Opening Stock Details', 'class' => 'active', 'url' => '');
        if($this->session->userdata('role_id')==7)
        {
           $user_id = icrm_decode($this->uri->segment(2));
        }
        else
        {
            $user_id = $this->session->userdata('user_id');
        }
        $product_category=$this->Product_opening_stock_model->get_product_category($user_id);
        $product_category_results=array();
            foreach($product_category as $key =>$value)
            {
                $group_results=$this->Product_opening_stock_model->get_group_by_product_category($value['category_id'],$user_id);
                foreach ($group_results as $key1 => $value1) 
                {   
                    $product_category_results[$value['category_id']]['category_name']=$value['name'];
                    $product_category_results[$value['category_id']]['groups'][$value1['group_id']]['group_name']=$value1['group_name'];
                    $product_results=$this->Product_opening_stock_model->get_products_by_product_group($value1['group_id'],$user_id);
                    $product_category_results[$value['category_id']]['groups'][$value1['group_id']]['products']=$product_results;
                }
            }
        $dealer_product= $this->Common_model->get_data('dealer_product_stock',array('status'=> 1,'user_id'=> $user_id));
        $stock=array();
        foreach($dealer_product as $key => $value)
        {
            $stock[$value['product_id']]['stock']=$value['opening_stock'];
        }
        $data['product_category_results']=$product_category_results; 
        $data['stock']=$stock ; 
        $data['user_id']=$user_id; 
        $this->load->view('product_opening_stock/product_opening_stock',$data);
    }

    public function insert_product_opening_stock()
    {
        $product_id= $this->input->post('product_id');
        $product_name= $this->input->post('product_name');
        $opening_stock= $this->input->post('opening_stock');
        $user_id=$this->input->post('user_id');
        $created_by=$this->session->userdata('user_id');
       
        $this->db->trans_begin();
        foreach($opening_stock as $key =>$value)
        {  
            if(($value!='0') && ($value!=''))
             {   
                $opening_stock=array(
                        'product_id'    =>  $product_id[$key],
                        'opening_stock' =>  $value,
                        'user_id'       =>  $user_id,
                        'created_by'    =>  $this->session->userdata('user_id'),
                        'status'        =>  1
                    );
             
                $this->Product_opening_stock_model->update_product_opening_stock($opening_stock,$user_id);
            }
        }
        if($this->db->trans_status()===FALSE)
        {
            $this->db->trans_rollback();
            $this->session->set_flashdata('response','<div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
            <strong>Error!</strong>Product Opening Stock Details has not Inserted. Please check. </div>');  
        }
        else
        {
            $this->db->trans_commit();
            $this->session->set_flashdata('response','<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
            <strong>Success!</strong>Product Opening Stock Details has been added successfully! </div>');
       }
       if($this->session->userdata('role_id')!=7)
       {
            redirect(SITE_URL.'product_opening_stock_details');
        }
        else
        {
            redirect(SITE_URL.'get_rbh_distributor_list');
        }
    }
}